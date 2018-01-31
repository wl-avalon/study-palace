<?php
namespace rrxframework\util\lib\rsa;
use Yii;

/**
 * RSA算法加/解密工具
 * 
 * @author hongcq
 */
class RsaCryptor
{
    /**
     * 公钥加密
     *
     * @param string $text 明文
     * @param string $key default:'default'
     * @return string 密文（base64编码）
     */
    public static function rsaEncode($text, $key = 'default')
    {
        $pubKey = self::getPrivateKey($key);
        $key = openssl_get_publickey($pubKey);
        $result = $crypttext = false;
        try {
            $result = openssl_public_encrypt($text, $crypttext, $key);
        } catch (\Exception $ex) {
            Yii::error($ex->getTraceAsString());
        }
        
        if (empty($result)) {
            return false;
        }
        
        return base64_encode("" . $crypttext);
    }
    
    /**
     * 私钥解密
     *
     * @param string $crypttext 密文（二进制格式且base64编码）
     * @param string $fromjs 密文是否来源于JS的RSA加密
     * @return string 明文
     */
    public static function rsaDecode($crypttext, $fromjs = false, $key = 'default')
    {
        $priKey = self::getPrivateKey($key);
        $key = openssl_get_privatekey($priKey);
        $crypttext = base64_decode($crypttext);
        $padding = $fromjs ? OPENSSL_NO_PADDING : OPENSSL_PKCS1_PADDING;
        
        if (openssl_private_decrypt($crypttext, $sourcestr, $key, $padding)) {
            return $fromjs ? rtrim(strrev($sourcestr), "/0") : "".$sourcestr;
        } else {
            Yii::error("msg[decode_rsa_ret_nill], text:" . $crypttext);
        }
        
        return null;
    }
    
    /**
     * 获取指定的私钥信息
     * 
     * @param string $keyId
     * @return string 私钥字符串
     */
    public static function getPrivateKey($keyId = null) {
        static $keys = [];
        $keyName = 'default';
        if (!empty($keyId)) {
            $keyName = $keyId;
        }
        
        if (isset($keys[$keyName])) {
            return $keys[$keyName];
        }
        
        $priName = "{$keyName}PriKey";
        $ref = new \ReflectionClass(new self());
        $proes = $ref->getStaticProperties();
        if (isset($proes[$priName])) {
            $keys[$keyName] = $proes[$priName];
        } else {
            new \Exception("rsa private key[$priName] not config");
        }
        
        return $keys[$keyName];
    }
    
    
    public static function test() {
        
        //JS->PHP 测试
        $txt_en = $_POST['password'];
        $txt_en = base64_encode(pack("H*", $txt_en));
        $file = 'ssl/server.pem';
        $txt_de = self::decode($txt_en, $file, TRUE);
        var_dump($txt_de);
        
        //PHP->PHP 测试
        $data = "汉字:1a2b3c";
        $file1 = 'ssl/server.crt';
        $file2 = 'ssl/server.pem';
        $a = self::encode($data, $file1);
        $b = self::decode($a, $file2);
        var_dump($b);
    }
    
    
    
    
    
    
    
    
    
    /**
     * @var string openssl genrsa -out rsa_private_key.pem 2048
     */
    public static $defaultPriKey = <<<EOF
-----BEGIN RSA PRIVATE KEY-----
MIIEpgIBAAKCAQEA1NigeQW/kDP0TZmTdVViGhF915SR8jhiicMLAx9aypppgZ+p
3JckaDFWNWiBEQ8m0jA4e/dEpoghFuUKYwXK3enSW2Sftm9ugiwt0crAZK/Y4LIY
xkPwEvIxaB7DoEbvnWAb3PY6KXDdjMEz+io2uZtTiVPd4hp/MkqvAKATlSjxIrVY
2OEsyW/JDCOmtF/pmFidlgp1UXUeToHplz1S6/M90PGaXSZ9OZBbgUO00ML86QvQ
Me8ZnHLRmN4S/qOJgxuqSdozltV1IFg5RNftFF8KqiW3TKGKmvUnN7fSHsZsCai3
XqLju/c+ZYKYC503ccHvsW0zTsid2M0GuPBPqwIDAQABAoIBAQCdym7E6X8HX4zI
jobj4EWW/8qkLE86G5Tpfj/Gs9LyCiEXaI9bqmziFVlMxMmSJQJppjfppvks4BRv
zaKyoFOxyisZd/JzJA7vz+Kz+bRBsJ9+jGmTakArO4NnJg9u3ce7isl9OMkLedG1
DHIFDpB9buoyD4uZmH0dgoJUW1fJJjPZDFuByIUf8kfsBYJAwM/lkR4jAvaH3Fzp
t8pupL3KoAxoPQHC+PO5U5NUcgJ1HkCZuCT6UCqyxOQsX9vmRAy5BQ7LwZhpf4pu
GbgnXUWs63qMhAiRlQtB4XGMDgG3z5CDaGb51oGbRp1tNRbbpPE5nAJMc2Dpfs38
Pf2DC0jBAoGBAO46ynyMaqfy05NoECsEgOr7e/6QbYXIWh/y6iLLRuLItKB4fIrs
pf+sXhcZmzjYesYyMeBZgniFK7yInpLgiIpjCnJob/Y4zBuC4xm1kVNfJm36uzfb
6wUo8lpxemWI7wTzpPIgYZ210mlG6XSPZhcgv2r8RTrp37Nd2RmxWPMLAoGBAOS5
HbW5Bb0941PWLYg/R78Xb+plXOWy9WZOdWJddo3mpo2ULjY3CdeSq10kr5Zo27gG
FvG+wfkbSMuIE/9IIzCrnva35cdNP9afGG16mlDgjbSvol2lbWBRzsPfSVFYTqQc
8FSoHpha5tZwD7An/VR6UC4tAP4jX8IhD9UaN/nhAoGBAJXIJwl565Ee7oGumwPA
+CFF7ubV/dZwaqHNxAIOVso9sLt4Ja3fLlt7D0ls0xhBm6fDZXKONKb768zFBRaJ
z69ap/XYzhQ8D7B4cbr3WqDVoT7itxVe0vxSi1XsJS1zk/xECAAn9dgHunxwllWv
11tRPqjQZeKtqvWGWvp5c9iTAoGBANOmbVSsyx/MNiFtTyMGE7lD0d9gPu+bg7RR
JBSLElrLNhJLLdwjYCso4QRHH2iazb9jezXm5I5Ebj9RQ9f2BAJUvbvmfm3IESvk
evFMA6KVuOubny7zFtyLtz7Pz4PDqLn6wXZFQFKRw4xplErZJnZW3P1tzb5eM41r
lMAJDAVhAoGBAK/4iJ3iHHf2IeVhMVs0fFefuj/xn7IHrzpYpZbWovQqEOwaGQfl
yuVJnpVFNLkQHsbBnUyFTf00Sm9J4Fevj6s0T9MJ554jviFcLm4nfIG5AzF+tMR4
TjBE87czqOZNjBiy2kP3Np1y08UIED/qt/ZyqPWyMpT0k42hXKM4OCJ4
-----END RSA PRIVATE KEY-----
EOF;
    
    /**
     * @var string openssl rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem
     */
    public static $defaultPubKey = <<<EOF
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1NigeQW/kDP0TZmTdVVi
GhF915SR8jhiicMLAx9aypppgZ+p3JckaDFWNWiBEQ8m0jA4e/dEpoghFuUKYwXK
3enSW2Sftm9ugiwt0crAZK/Y4LIYxkPwEvIxaB7DoEbvnWAb3PY6KXDdjMEz+io2
uZtTiVPd4hp/MkqvAKATlSjxIrVY2OEsyW/JDCOmtF/pmFidlgp1UXUeToHplz1S
6/M90PGaXSZ9OZBbgUO00ML86QvQMe8ZnHLRmN4S/qOJgxuqSdozltV1IFg5RNft
FF8KqiW3TKGKmvUnN7fSHsZsCai3XqLju/c+ZYKYC503ccHvsW0zTsid2M0GuPBP
qwIDAQAB
-----END PUBLIC KEY-----
EOF;
    
}