<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/1
 * Time: 上午11:27
 */

namespace app\models\question;


use yii\db\Query;

class CommonModel
{
    private static $db_question_chinese                = null;
    private static $db_question_math                   = null;
    private static $db_question_english                = null;
    private static $db_question_physical               = null;
    private static $db_question_chemistry              = null;
    private static $db_question_biological             = null;
    private static $db_question_political              = null;
    private static $db_question_history                = null;
    private static $db_question_geography              = null;
    private static $db_question_common_technology      = null;
    private static $db_question_internet_technology    = null;

    const DEL_STATUS_NORMAL = 0;
    const DEL_STATUS_DELETE = 1;

    public static function getQuestionDb($dbName){
        switch($dbName){
            case 'question_chinese':{
                if(is_null(self::$db_question_chinese)){
                    self::$db_question_chinese = \Yii::$app->db_question_chinese;
                }
                return self::$db_question_chinese;
            }
            case 'question_math':{
                if(is_null(self::$db_question_math)){
                    self::$db_question_math = \Yii::$app->db_question_math;
                }
                return self::$db_question_math;
            }
            case 'question_english':{
                if(is_null(self::$db_question_english)){
                    self::$db_question_english = \Yii::$app->db_question_english;
                }
                return self::$db_question_english;
            }
            case 'question_physical':{
                if(is_null(self::$db_question_physical)){
                    self::$db_question_physical = \Yii::$app->db_question_physical;
                }
                return self::$db_question_physical;
            }
            case 'question_chemistry':{
                if(is_null(self::$db_question_chemistry)){
                    self::$db_question_chemistry = \Yii::$app->db_question_chemistry;
                }
                return self::$db_question_chemistry;
            }
            case 'question_biological':{
                if(is_null(self::$db_question_biological)){
                    self::$db_question_biological = \Yii::$app->db_question_biological;
                }
                return self::$db_question_biological;
            }
            case 'question_political':{
                if(is_null(self::$db_question_political)){
                    self::$db_question_political = \Yii::$app->db_question_political;
                }
                return self::$db_question_political;
            }
            case 'question_history':{
                if(is_null(self::$db_question_history)){
                    self::$db_question_history = \Yii::$app->db_question_history;
                }
                return self::$db_question_history;
            }
            case 'question_geography':{
                if(is_null(self::$db_question_geography)){
                    self::$db_question_geography = \Yii::$app->db_question_geography;
                }
                return self::$db_question_geography;
            }
            case 'question_common_technology':{
                if(is_null(self::$db_question_common_technology)){
                    self::$db_question_common_technology = \Yii::$app->db_question_common_technology;
                }
                return self::$db_question_common_technology;
            }
            case 'question_internet_technology':{
                if(is_null(self::$db_question_internet_technology)){
                    self::$db_question_internet_technology = \Yii::$app->db_question_internet_technology;
                }
                return self::$db_question_internet_technology;
            }
            default:{
                throw new \Exception('dbName is not exist,name is' . $dbName, 1);
            }
        }
    }

    public static function getQuestionConstDb($tableName){
        switch($tableName){
            case 'grade':{
                return 'grade_enum';
            }
            case 'subject':{
                return 'subject_enum';
            }
            case 'version':{
                return 'version_enum';
            }
            case 'module':{
                return 'module_enum';
            }
            case 'product_type':{
                return 'product_type_enum';
            }
            case 'difficulty':{
                return 'difficulty_enum';
            }
            default:{
                throw new \Exception('$ableName is not exist,name is' . $tableName, 1);
            }
        }
    }

    public static function createSelectCommand($db, $where, $tableName, $fields = []){
        $whereCondition = [
            'AND',
            ['=', 'del_status', self::DEL_STATUS_NORMAL],
            $where,
        ];
        $command = (new Query())->select($fields)->where($whereCondition)->from($tableName)->createCommand($db);
        return $command;
    }
}