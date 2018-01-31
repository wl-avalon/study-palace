<?php

namespace ploanframework\components;

use Closure;
use ploanframework\constants\JdbErrors;
use ploanframework\utils\Arr;
use ploanframework\utils\Str;
use rrxframework\base\JdbException;

/**
 * 参数校验类
 * Class Validator
 * @package app\modules\datashop\components
 */
class Validator{
    private $messages;
    private $customMessages = [];
    private $fallbackMessages = [];
    private $data;
    private $after = [];
    private $rules = [];
    private $failedRules;
    private $errors = [];

    private $numericRules = ['Numeric', 'Integer'];

    private $implicitRules = ['Required', 'Filled', 'RequiredWith', 'RequiredWithAll', 'RequiredWithout', 'RequiredWithoutAll', 'RequiredIf', 'RequiredUnless', 'Accepted', 'Present', 'Default',];

    public static function make(array $data, array $rules, array $messages = []){
        if(!Arr::isAssoc($rules)){
            $temp = [];
            foreach($rules as $rule){
                $temp[$rule] = 'Required';
            }
            $rules = $temp;
        }
        return new Validator($data, $rules, $messages);
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    public function getRules(){
        return $this->rules;
    }

    /**
     * Get all attributes.
     *
     * @return array
     */
    public function attributes(){
        return $this->data;
    }

    /**
     * Determine if the data passes the validation rules.
     *
     * @return bool
     */
    public function passes(){
        $this->messages = [];
        foreach($this->rules as $attribute => $rules){
            foreach($rules as $rule){
                $this->validate($attribute, $rule);
                if($this->shouldStopValidating($attribute)){
                    break;
                }
            }
        }

        foreach($this->after as $after){
            call_user_func($after);
        }
        return count($this->errors) === 0;
    }

    /**
     * Determine if the data fails the validation rules.
     *
     * @return bool
     */
    public function fails(){
        return !$this->passes();
    }

    /**
     * Returns the data which was invalid.
     *
     * @return array
     */
    public function invalid(){
        if(!$this->messages){
            $this->passes();
        }

        return array_intersect_key($this->data, $this->messages);
    }

    /**
     * An alternative more semantic shortcut to the message container.
     *
     * @return array
     */
    public function errors(){
        return $this->errors;
    }

    public function throws(){
        if(count($this->errors) > 0 || $this->fails()){
            foreach($this->errors as $error){
                if(count($error) > 0){
                    throw new JdbException(JdbErrors::ERR_NO_PARAM_INVALID, null, $error[0]);
                }else{
                    $attr = array_keys($this->errors);
                    $msg = count($attr) > 0 ? "parameter $attr[0] is invalid." : 'parameter is invalid';
                    throw new JdbException(JdbErrors::ERR_NO_PARAM_INVALID, null, $msg);
                }
            }
        }
    }

    /**
     * Register a custom validator extension.
     *
     * @param  string $rule
     * @param  \Closure|string $extension
     * @param  string $message
     * @return void
     */
    public function extend($rule, $extension, $message = null){
        $this->extensions[$rule] = $extension;

        if($message){
            $this->fallbackMessages[Str::snake($rule)] = $message;
        }
    }

    /**
     * Handle dynamic calls to class methods.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters){
        $rule = Str::snake(substr($method, 8));

        if(isset($this->extensions[$rule])){
            return $this->callExtension($rule, $parameters);
        }

        throw new \BadMethodCallException("Method [$method] does not exist.");
    }

    /**
     * After an after validation callback.
     *
     * @param  callable|string $callback
     * @return $this
     */
    public function after(callable $callback){
        $this->after[] = function() use ($callback){
            return call_user_func_array($callback, [$this]);
        };

        return $this;
    }

    /**
     * Add a message.
     *
     * @param  string $key
     * @param  string $message
     * @return $this
     */
    public function addMessage($key, $message){
        if($this->isUniqueMessage($key, $message)){
            $this->customMessages[$key][] = $message;
        }

        return $this->messages;
    }

    /**
     * Create a new Validator instance.
     *
     * @param  array $data
     * @param  array $rules
     * @param  array $messages
     */
    private function __construct(array $data, array $rules, array $messages = []){
        if(!isset($this->messages)){
            //$this->messages = require_once(__DIR__ . '/../constants/Messages.php');
        }
        $this->customMessages = array_merge($this->customMessages, $messages);

        $this->data = $data;
        $rules = $this->explodeRules($rules);
        $this->rules = array_merge($this->rules, $rules);
    }

    /**
     * Add an error message to the validator's collection of messages.
     *
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return void
     */
    private function addError($attribute, $rule, $parameters){
        $message = $this->getMessage($attribute, $rule);
        $message = $this->doReplacements($message, $attribute, $rule, $parameters);
        $this->errors[$attribute][] = $message;
        $this->failedRules[$attribute][$rule] = $parameters;
    }

    /**
     * Call a custom validator extension.
     *
     * @param  string $rule
     * @param  array $parameters
     * @return bool|null
     */
    private function callExtension($rule, $parameters){
        $callback = $this->extensions[$rule];
        if($callback instanceof Closure){
            return call_user_func_array($callback, $parameters);
        }elseif(is_string($callback)){
            list($class, $method) = explode('@', $callback);

            return call_user_func_array([$class, $method], $parameters);
        }
    }

    /**
     * Validate a given attribute against a rule.
     *
     * @param  string $attribute
     * @param  string $rule
     * @return void
     */
    private function validate($attribute, $rule){
        list($rule, $parameters) = $this->parseRule($rule);
        if($rule == ''){
            return;
        }
        $value = $this->getValue($attribute);
        $validatable = $this->isValidatable($rule, $attribute, $value);
        $method = "validate{$rule}";
        if($validatable && !$this->$method($attribute, $value, $parameters)){
            $this->addError($attribute, $rule, $parameters);
        }
    }

    /**
     * Validate that an attribute is an integer.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    private function validateInteger($attribute, $value){
        if(!$this->hasAttribute($attribute)){
            return true;
        }

        return is_null($value) || filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Validate that an attribute is numeric.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    private function validateNumeric($attribute, $value){
        if(!$this->hasAttribute($attribute)){
            return true;
        }

        return is_null($value) || is_numeric($value);
    }

    /**
     * Validate the size of an attribute is between a set of values.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    private function validateBetween($attribute, $value, $parameters){
        $this->requireParameterCount(2, $parameters, 'between');

        $size = $this->getSize($attribute, $value);

        return $size >= $parameters[0] && $size <= $parameters[1];
    }

    /**
     * Validate that an attribute is between a given number of digits.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    private function validateDigitsBetween($attribute, $value, $parameters){
        $this->requireParameterCount(2, $parameters, 'digits_between');

        $length = strlen((string)$value);

        return $this->validateNumeric($attribute, $value) && $length >= $parameters[0] && $length <= $parameters[1];
    }

    /**
     * Validate that an attribute is a string.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    private function validateString($attribute, $value){
        if(!$this->hasAttribute($attribute)){
            return true;
        }

        return is_null($value) || is_string($value);
    }

    /**
     * Validate that an attribute is an phone number.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    private function validatePhone($attribute, $value){
        if(!$this->hasAttribute($attribute)){
            return true;
        }

        return preg_match('/1[3458]{1}\d{9}$/', $value);
    }

    private function validateIdentity($attribute, $value){
        if(!$this->hasAttribute($attribute)){
            return true;
        }

        return preg_match('/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/', $value);
    }

    /**
     * Validate that an attribute is an mail address.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    private function validateMail($attribute, $value){
        if(!$this->hasAttribute($attribute)){
            return true;
        }

        return preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $value);
    }

    private function validateRegex($attribute, $value, $parameters){
        $this->requireParameterCount(1, $parameters, 'regex');
        return preg_match($parameters[0], $value);
    }

    /**
     * Validate that an attribute exists when another attribute has a given value.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  mixed $parameters
     * @return bool
     */
    private function validateRequiredIf($attribute, $value, $parameters){
        $this->requireParameterCount(2, $parameters, 'required_if');

        $data = Arr::get($this->data, $parameters[0]);

        $values = array_slice($parameters, 1);

        if(in_array($data, $values)){
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Validate the given attribute is filled if it is present.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    private function validateFilled($attribute, $value){
        if(Arr::has($this->data, $attribute)){
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Determine if any of the given attributes fail the required test.
     *
     * @param  array $attributes
     * @return bool
     */
    private function anyFailingRequired(array $attributes){
        foreach($attributes as $key){
            if(!$this->validateRequired($key, $this->getValue($key))){
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if all of the given attributes fail the required test.
     *
     * @param  array $attributes
     * @return bool
     */
    private function allFailingRequired(array $attributes){
        foreach($attributes as $key){
            if($this->validateRequired($key, $this->getValue($key))){
                return false;
            }
        }

        return true;
    }

    /**
     * Validate that an attribute exists when any other attribute exists.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  mixed $parameters
     * @return bool
     */
    private function validateRequiredWith($attribute, $value, $parameters){
        if(!$this->allFailingRequired($parameters)){
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Validate that an attribute exists when all other attributes exists.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  mixed $parameters
     * @return bool
     */
    private function validateRequiredWithAll($attribute, $value, $parameters){
        if(!$this->anyFailingRequired($parameters)){
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Validate that an attribute exists when another attribute does not.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  mixed $parameters
     * @return bool
     */
    private function validateRequiredWithout($attribute, $value, $parameters){
        if($this->anyFailingRequired($parameters)){
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Validate that an attribute exists when all other attributes do not.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  mixed $parameters
     * @return bool
     */
    private function validateRequiredWithoutAll($attribute, $value, $parameters){
        if($this->allFailingRequired($parameters)){
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Validate that an attribute exists when another attribute does not have a given value.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  mixed $parameters
     * @return bool
     */
    private function validateRequiredUnless($attribute, $value, $parameters){
        $this->requireParameterCount(2, $parameters, 'required_unless');

        $data = Arr::get($this->data, $parameters[0]);

        $values = array_slice($parameters, 1);

        if(!in_array($data, $values)){
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    private function validateAccepted($attribute, $value, $parameters){
        if($this->validateRequired($attribute, $value)){
            $this->requireParameterCount(1, $parameters, 'accepted');
            return Arr::has($parameters, $value);
        }

        return false;
    }

    /**
     * Checks if an attribute exists.
     *
     * @param  string $attribute
     * @return bool
     */
    private function hasAttribute($attribute){
        return Arr::has($this->attributes(), $attribute);
    }

    /**
     * Explode the rules into an array of rules.
     *
     * @param  string|array $rules
     * @return array
     */
    private function explodeRules($rules){
        foreach($rules as $key => $rule){
            $rules[$key] = (is_string($rule)) ? explode('|', $rule) : $rule;
        }
        return $rules;
    }

    /**
     * Extract the rule name and parameters from a rule.
     *
     * @param  array|string $rules
     * @return array
     */
    private function parseRule($rules){
        if(is_array($rules)){
            $rules = $this->parseArrayRule($rules);
        }else{
            $rules = $this->parseStringRule($rules);
        }
        $rules[0] = $this->normalizeRule($rules[0]);
        return $rules;
    }

    /**
     * Get the value of a given attribute.
     *
     * @param  string $attribute
     * @return mixed
     */
    private function getValue($attribute){
        if(!is_null($value = Arr::get($this->data, $attribute))){
            return $value;
        }else{
            return null;
        }
    }

    /**
     * Determine if the attribute is validatable.
     *
     * @param  string $rule
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    private function isValidatable($rule, $attribute, $value){
        return $this->presentOrRuleIsImplicit($rule, $attribute, $value) && $this->passesOptionalCheck($attribute);
    }

    /**
     * Determine if the field is present, or the rule implies required.
     *
     * @param  string $rule
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    private function presentOrRuleIsImplicit($rule, $attribute, $value){
        return $this->validateRequired($attribute, $value) || $this->isImplicit($rule);
    }

    /**
     * Determine if the attribute passes any optional check.
     *
     * @param  string $attribute
     * @return bool
     */
    private function passesOptionalCheck($attribute){
        if($this->hasRule($attribute, ['Sometimes'])){
            return array_key_exists($attribute, Arr::dot($this->data)) || in_array($attribute, array_keys($this->data));
        }

        return true;
    }

    /**
     * Parse an array based rule.
     *
     * @param  array $rules
     * @return array
     */
    private function parseArrayRule(array $rules){
        return [Str::studly(trim(Arr::get($rules, 0))), array_slice($rules, 1)];
    }

    /**
     * Parse a string based rule.
     *
     * @param  string $rules
     * @return array
     */
    private function parseStringRule($rules){
        $parameters = [];
        if(strpos($rules, ':') !== false){
            list($rules, $parameter) = explode(':', $rules, 2);
            $parameters = $this->parseParameters($rules, $parameter);
        }
        return [Str::studly(trim($rules)), $parameters];
    }

    /**
     * Parse a parameter list.
     *
     * @param  string $rule
     * @param  string $parameter
     * @return array
     */
    private function parseParameters($rule, $parameter){
        if(strtolower($rule) == 'regex'){
            return [$parameter];
        }

        return str_getcsv($parameter);
    }

    /**
     * Normalizes a rule so that we can accept short types.
     *
     * @param  string $rule
     * @return string
     */
    private function normalizeRule($rule){
        switch($rule){
            case 'Int':
                return 'Integer';
            case 'Bool':
                return 'Boolean';
            default:
                return $rule;
        }
    }

    /**
     * Get the numeric keys from an attribute flattened with dot notation.
     *
     * E.g. 'foo.1.bar.2.baz' -> [1, 2]
     *
     * @param  string $attribute
     * @return array
     */
    private function getNumericKeys($attribute){
        $keys = [];
        if(preg_match_all('/\.(\d+)\./', $attribute, $keys)){
            return $keys[1];
        }

        return [];
    }

    /**
     * Validate that a required attribute exists.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    private function validateRequired($attribute, $value){
        if(is_null($value)){
            return false;
        }elseif(is_string($value) && trim($value) === ''){
            return false;
        }elseif(is_array($value) && count($value) < 1){
            return false;
        }

        return true;
    }

    /**
     * Set a default value to the attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    private function validateDefault($attribute, $value, $parameters){
        $this->requireParameterCount(1, $parameters, 'default');
        if(!$this->validateRequired($attribute, $value)){
            $this->data[$attribute] = $parameters[0];;
        }

        return true;
    }

    /**
     * Determine if a given rule implies the attribute is required.
     *
     * @param  string $rule
     * @return bool
     */
    private function isImplicit($rule){
        return in_array($rule, $this->implicitRules);
    }

    /**
     * Determine if the given attribute has a rule in the given set.
     *
     * @param  string $attribute
     * @param  string|array $rules
     * @return bool
     */
    private function hasRule($attribute, $rules){
        return !is_null($this->getRule($attribute, $rules));
    }

    /**
     * Get a rule and its parameters for a given attribute.
     *
     * @param  string $attribute
     * @param  string|array $rules
     * @return array|null|void
     */
    private function getRule($attribute, $rules){
        if(!array_key_exists($attribute, $this->rules)){
            return;
        }

        foreach($this->rules[$attribute] as $rule){
            list($rule, $parameters) = $this->parseRule($rule);
            if(in_array($rule, $rules)){
                return [$rule, $parameters];
            }
        }
    }

    /**
     * Stop on error if "bail" rule is assigned and attribute has a message.
     *
     * @param  string $attribute
     * @return bool
     */
    private function shouldStopValidating($attribute){
        if(!$this->hasRule($attribute, ['Bail'])){
            return false;
        }

        return array_key_exists($attribute, $this->messages);
    }

    /**
     * Require a certain number of parameters to be present.
     *
     * @param  int $count
     * @param  array $parameters
     * @param  string $rule
     * @return void
     *
     * @throws JdbException
     */
    private function requireParameterCount($count, $parameters, $rule){
        if(count($parameters) < $count){
            throw new JdbException(JdbErrors::ERR_NO_PARAM_INVALID, null, "Validation rule $rule requires at least $count parameters.");
        }
    }

    /**
     * Get the size of an attribute.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return mixed
     */
    private function getSize($attribute, $value){
        $hasNumeric = $this->hasRule($attribute, $this->numericRules);

        if(is_numeric($value) && $hasNumeric){
            return $value;
        }elseif(is_array($value)){
            return count($value);
        }

        return mb_strlen($value);
    }

    /**
     * Get the validation message for an attribute and rule.
     *
     * @param  string $attribute
     * @param  string $rule
     * @return string
     */
    private function getMessage($attribute, $rule){
        $lowerRule = Str::camel($rule);
        $keys = ["{$attribute}.{$lowerRule}", $lowerRule];
        foreach($keys as $key){
            foreach(array_keys($this->fallbackMessages) as $sourceKey){
                if(Str::is($sourceKey, $key)){
                    return $this->fallbackMessages[$sourceKey];
                }
            }

            foreach(array_keys($this->customMessages) as $sourceKey){
                if(Str::is($sourceKey, $key)){
                    return $this->customMessages[$sourceKey];
                }
            }

            foreach(array_keys($this->messages) as $sourceKey){
                if(Str::is($sourceKey, $key)){
                    return $this->messages[$sourceKey];
                }
            }
        }
    }

    /**
     * Replace all error message place-holders with actual values.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    private function doReplacements($message, $attribute, $rule, $parameters){
        $message = str_replace([':ATTRIBUTE', ':Attribute', ':attribute'], [Str::upper($attribute), Str::ucfirst($attribute), $attribute], $message);

        if(method_exists($this, $replacer = "replace{$rule}")){
            $message = $this->$replacer($message, $attribute, $rule, $parameters);
        }else{
            $message = Str::format($message, $this->data);
        }
        return $message;
    }

    /**
     * Transform an array of attributes to their displayable form.
     *
     * @param  array $values
     * @return array
     */
    private function getAttributeList(array $values){
        $attributes = [];

        foreach($values as $key => $value){
            $attributes[$key] = $value;
        }

        return $attributes;
    }

    /**
     * Determine if a key and message combination already exists.
     *
     * @param  string $key
     * @param  string $message
     * @return bool
     */
    private function isUniqueMessage($key, $message){
        $messages = $this->messages;

        return !isset($messages[$key]) || !in_array($message, $messages[$key]);
    }

    /**
     * Replace all place-holders for the between rule.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    private function replaceBetween($message, $attribute, $rule, $parameters){
        return str_replace([':min', ':max'], $parameters, $message);
    }

    /**
     * Replace all place-holders for the digits (between) rule.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    private function replaceDigitsBetween($message, $attribute, $rule, $parameters){
        return $this->replaceBetween($message, $attribute, $rule, $parameters);
    }

    /**
     * Replace all place-holders for the min rule.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    private function replaceMin($message, $attribute, $rule, $parameters){
        return str_replace(':min', $parameters[0], $message);
    }

    /**
     * Replace all place-holders for the max rule.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    private function replaceMax($message, $attribute, $rule, $parameters){
        return str_replace(':max', $parameters[0], $message);
    }

    /**
     * Replace all place-holders for the in rule.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    private function replaceIn($message, $attribute, $rule, $parameters){
        return str_replace(':values', implode(', ', $parameters), $message);
    }

    /**
     * Replace all place-holders for the required_with rule.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    private function replaceRequiredWith($message, $attribute, $rule, $parameters){
        $parameters = $this->getAttributeList($parameters);
        return str_replace(':values', implode(' / ', $parameters), $message);
    }

    /**
     * Replace all place-holders for the required_with_all rule.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    private function replaceRequiredWithAll($message, $attribute, $rule, $parameters){
        return $this->replaceRequiredWith($message, $attribute, $rule, $parameters);
    }

    /**
     * Replace all place-holders for the required_without rule.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    private function replaceRequiredWithout($message, $attribute, $rule, $parameters){
        return $this->replaceRequiredWith($message, $attribute, $rule, $parameters);
    }

    /**
     * Replace all place-holders for the required_without_all rule.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    private function replaceRequiredWithoutAll($message, $attribute, $rule, $parameters){
        return $this->replaceRequiredWith($message, $attribute, $rule, $parameters);
    }

    /**
     * Replace all place-holders for the required_if rule.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    private function replaceRequiredIf($message, $attribute, $rule, $parameters){
        $parameters[1] = Arr::get($this->data, $parameters[0]);

        return str_replace([':other', ':value'], $parameters, $message);
    }

    /**
     * Replace all place-holders for the required_unless rule.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    private function replaceRequiredUnless($message, $attribute, $rule, $parameters){
        $other = array_shift($parameters);

        return str_replace([':other', ':values'], [$other, implode(', ', $parameters)], $message);
    }
}
