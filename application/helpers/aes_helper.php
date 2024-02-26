<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

define('OPENSSL_CIPHER_NAME', 'aes-128-cbc');
define('AEC_CBC_KEY', '%!F*&^$)_*%3f&B+');
define('CIPHER_KEY_LEN', '16');
define('ini_vector', '#*$DJvyw2w%!_-$@');

if (!function_exists('random_token')) {

    function random_token() {
        return rand(1000000000000000, 9999999999999999);
    }

}

if (!function_exists('fixKey')) {

    function fixKey($key) {
        if (strlen($key) < CIPHER_KEY_LEN) {
            //0 pad to len 16
            return str_pad("$key", CIPHER_KEY_LEN, "0");
        }
        if (strlen($key) > CIPHER_KEY_LEN) {
            //truncate to 16 bytes
            return substr($key, 0, CIPHER_KEY_LEN);
        }
        return $key;
    }

}
if (!function_exists('get_keys')) {

    function get_keys($key, $string) {
        $key = str_split($key);
        $string = str_split($string);
        $return = "";
        foreach ($string as $value) {
            $return .= $key[$value];
        }
        return $return;
    }

}


if (!function_exists('aes_cbc_encryption')) {

    function aes_cbc_encryption($string, $key) {
        $cbc_key = $key ? get_keys(AEC_CBC_KEY, $key) : AEC_CBC_KEY;
        $ini_vector = $key ? get_keys(ini_vector, $key) : ini_vector;
        return base64_encode(openssl_encrypt($string, OPENSSL_CIPHER_NAME, fixKey($cbc_key), OPENSSL_RAW_DATA, $ini_vector)) . ":" . base64_encode("1234567890123456");
    }

}

if (!function_exists('aes_cbc_decryption')) {

    function aes_cbc_decryption($string, $key) {
        $cbc_key = $key ? get_keys(AEC_CBC_KEY, $key) : AEC_CBC_KEY;
        $ini_vector = $key ? get_keys(ini_vector, $key) : ini_vector;
        return openssl_decrypt(base64_decode(explode(':', $string)[0]), OPENSSL_CIPHER_NAME, fixKey($cbc_key), OPENSSL_RAW_DATA, $ini_vector);
    }

}



if (!function_exists('aes_cbc_encryption_file')) {

    function aes_cbc_encryption_file($file) {
        $contenuto = file_get_contents($file);

        $encodedEncryptedData = openssl_encrypt($contenuto, OPENSSL_CIPHER_NAME, fixKey(AEC_CBC_KEY), OPENSSL_RAW_DATA, ini_vector);
        if ($encodedEncryptedData === false)
            return 'Errors occurred while encrypting the file ';

        $file = str_replace(".mp4", "", $file);
        if (file_put_contents($file, $encodedEncryptedData))
            return $file;
        else
            return 'Errors occurred while saving the encrypted file ';
    }

}

if (!function_exists('aes_cbc_decryption_file')) {

    function aes_cbc_decryption_file($file) {
        $contenuto = file_get_contents($file);
        $decryptedData = openssl_decrypt($data, OPENSSL_CIPHER_NAME, fixKey(AEC_CBC_KEY), OPENSSL_RAW_DATA, ini_vector);
        if ($decryptedData === false)
            return 'Errors occurred while decrypting the file ';
        $file .= ".mp4";
        if (file_put_contents($file, $decryptedData))
            return $file;
        else
            return 'Errors occurred while saving the decrypted file ';
    }

}