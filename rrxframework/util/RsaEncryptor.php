<?php
namespace rrxframework\util;

class RsaEncryptor {
    /**
     * @param $data
     * @param $privateKey
     * @return mixed bool|string
     */
    static public function DecodeByPrivateKey($data, $privateKey) {
        $piKey =  openssl_pkey_get_private($privateKey);

        if ($piKey === false) {
            return false;
        }

        $status = openssl_private_decrypt(base64_decode($data), $decrypted, $piKey);

        if ($status === false) {
            return false;
        }

        return $decrypted;
    }
}