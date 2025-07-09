<?php
// ANA MOLINA 08/05/2024
namespace App\EncryptDecrypt;

class Encrypt_Decrypt
{
    public static function encrypt($data)
    {

        $method = "AES-256-CBC";
        //$key = "encryptionKey123";
        $key = "CobachSICE2024";
        $options = 0;
        $iv = '1234567891011121';

        $encryptedData = openssl_encrypt($data, $method, $key, $options,$iv);
        return $encryptedData;
    }

    public static function decrypt($encryptedData)
    {
        $method = "AES-256-CBC";
        //$key = "encryptionKey123";
        $key = "CobachSICE2024";
        $options = 0;
        $iv = '1234567891011121';

        $decryptedData = openssl_decrypt($encryptedData, $method, $key, $options, $iv);

        return $decryptedData;
    }

}

