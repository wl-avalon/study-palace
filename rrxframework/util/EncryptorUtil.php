<?php
namespace rrxframework\util;
use rrxframework\util\lib\rncryptor\Encryptor;
use rrxframework\util\lib\rncryptor\Decryptor;
use rrxframework\util\lib\aes\AesCtr;
use rrxframework\util\lib\rsa\RsaCryptor;

/**
 * 加密工具类
 * 
 * @author hongcq
 */
class EncryptorUtil {

    /**
     * RSA加密
     * 
     * @param string $txt
     * @param string $from default:app
     * @param string $key default:'default'
     * @return string
     */
    public static function rsaEncode($txt, $key = 'default') {
        return RsaCryptor::rsaEncode($txt, $key);
    }
    
    /**
     * RSA解密
     *
     * @param string $txt
     * @param string $from default:app
     * @param string $key default:'default'
     * @return string
     */
    public static function rsaDecode($txt, $from = 'app', $key = 'default') {
        return RsaCryptor::rsaDecode($txt, $from, $key);
    }
    
    /**
     * AES加密<br>
     * Note:需保持app端加密方法一致
     *
     * @param string $data 预加密数据
     * @param string $key 私钥
     * @return string
     * @see https://github.com/RNCryptor/RNCryptor
     */
    public static function aesEncode($data, $key) {
        return (new Encryptor())->encrypt($data, $key);
    }
    
    /**
     * 对JdbUtils::aesEncode()后的数据进行解密
     *
     * @param string $data 待解密数据
     * @param string $key 私钥
     * @param string $from 来源
     * @return string
     */
    public static function aesDecode($data, $key, $from = 'app') {
        $ret = null;
        if ($from == 'app') {
            $ret = (new Decryptor())->decrypt($data, $key);
            
            // JS加密的特殊处理
        } else if ($from == 'web') {
            // 这是个坑, 注意, 需要h5 把 base64_encode的结果的+替换成-; / 替换成_, 注意还有其他模式 MCRYPT_RIJNDAEL_256 AES_256
            // $data = str_replace('-', '+', $data);
            // $data = str_replace('_', '/', $data);
            $data = str_replace('-', '+', $data);
            $data = str_replace('_', '/', $data);
            $ret = AesCtr::decrypt($data, $key, 256);
        }
        
        return $ret;
    }
    
    /**
     * 加密服务
     * @param array $data
     * @param string $key
     * @return string
     */
    public static function encrypt($data, $key) {
        $prep_code = serialize($data);
        $block = mcrypt_get_block_size('des', 'ecb');
        if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
            $prep_code .= str_repeat(chr($pad), $pad);
        }
        $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB);
        $ret = base64_encode($encrypt);
    
        $ret = str_replace('+', '-', $ret);
        $ret = str_replace('/', '_', $ret);
        return $ret;
    }
    
    /**
     * 解密服务
     * @param string $str
     * @param string $key
     * @return mixed
     */
    public static function decrypt($str, $key) {
        $str = str_replace('-', '+', $str);
        $str = str_replace('_', '/', $str);
        $str = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
            $str = substr($str, 0, strlen($str) - $pad);
        }
        return unserialize($str);
    }


    const PAY_DES_KEY = 'cputest';

    /**
     * 与支付系统一致的des加密
     *
     * des加密模式 : ECB
     * 填充模式 : pkcs5padding
     * 偏移量 : 无
     * 输出 : hex
     *
     * @param $text
     * @param $key
     * @return string
     */
    public static function payDesEncode($text, $key = self::PAY_DES_KEY) {
        $iv   = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TRIPLEDES,MCRYPT_MODE_ECB), MCRYPT_RAND);
        $text = self::pkcs5Pad($text);
        $td = mcrypt_module_open(MCRYPT_3DES,'',MCRYPT_MODE_ECB,'');
        mcrypt_generic_init($td,$key,$iv);
        $data = strtolower(bin2hex(mcrypt_generic($td, $text)));
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $data;
    }

    protected static function pkcs5Pad($text)
    {
        $pad = 8 - (strlen($text) % 8);
        return $text . str_repeat(chr($pad), $pad);
    }


    /**
     * 与支付系统一致的des解密
     *
     * @param $str
     * @param $key
     * @return string
     */
    public static function payDesDecode($text, $key = self::PAY_DES_KEY) {
        $text = hex2bin(strtolower($text));

        $iv   = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TRIPLEDES,MCRYPT_MODE_ECB), MCRYPT_RAND);
        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
        mcrypt_generic_init($td, $key, $iv);
        $data  = self::pkcs5UnPad(mdecrypt_generic($td, $text));
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $data;
    }

    protected static function pkcs5UnPad($text)
    {
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }
}

