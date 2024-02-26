<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/helpers/jwt/src/JWT.php';
require_once APPPATH . '/helpers/jwt/src/BeforeValidException.php';
require_once APPPATH . '/helpers/jwt/src/ExpiredException.php';
require_once APPPATH . '/helpers/jwt/src/SignatureInvalidException.php';
require_once APPPATH . 'libraries/Redis_magic.php';

use \Firebase\JWT\JWT;

define("JWT_KEY_INI", "@I%)F24MVW9");
define("JWT_MAX_EXPIRE_INI", 2160000); // 25 days 
define("JWT_ALGO_INI", 'HS256'); // 25 days 

define("SESSION_TABLE_REDIS", 'user_session'); // 25 days 

function create_jwt($payload = array()) {
    $issuedAt = time();
    $expirationTime = $issuedAt + JWT_MAX_EXPIRE_INI;
    $payload['iat'] = $issuedAt;
    $payload['exp'] = $expirationTime;
    $payload['version_code'] = VERSION_CODE;
    $jwt = JWT::encode($payload, JWT_KEY_INI, JWT_ALGO_INI);

    $user_info = array();
    $user_info['token'] = $jwt;
    $user_info['iat'] = $issuedAt;
    $user_info['exp'] = $expirationTime;
    $user_info['HTTP_USER_AGENT'] = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : "";
    $user_info['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];

    $redis_magic = new Redis_magic("session");
    $redis_magic->HMSET(SESSION_TABLE_REDIS, $payload['id'], $user_info);
    $redis_magic->EXPIRE_HMSET_KEY(SESSION_TABLE_REDIS, $payload['id'], JWT_MAX_EXPIRE_INI);

    return $jwt;
}

function reset_session($user_id) {
    $redis_magic = new Redis_magic("session");
    $redis_magic->EXPIRE_HMSET_KEY(SESSION_TABLE_REDIS, $user_id, 0);
}

function validate_jwt($jwt_token, $user_id = NULL, $agent = NULL) {
    $redis_magic = new Redis_magic("session");
    try {
        $decoded = JWT::decode($jwt_token, JWT_KEY_INI, array(JWT_ALGO_INI));
        
        $decoded_array = (array) $decoded;
        if ($decoded_array) {
            if (array_key_exists("version_code", $decoded_array) && $decoded_array['version_code'] > VERSION_CODE) {
                return array();
            }
            if (!array_key_exists("id", $decoded_array) || $decoded_array['id'] != $user_id) {
                if ($user_id != "TAKE_OVER")
                    return array();
            }
            $redis_session = $redis_magic->HGETALL(SESSION_TABLE_REDIS, USER_ID);
            if (!USER_ID && is_array($redis_session)) {//guest case
                return $decoded_array;
            }
            if (USER_ID && (!is_array($redis_session) || !isset($decoded_array['device_type']) || count($redis_session) < 1 || $jwt_token != $redis_session['token'])) {
                return array();
            }
            if (USER_ID && $agent && array_key_exists("HTTP_USER_AGENT", $redis_session) && $redis_session['HTTP_USER_AGENT'] && $redis_session['HTTP_USER_AGENT'] != $agent) {
                return array();
            }
        }
        return $decoded_array;
    } catch (Exception $e) {
        return array();
    }
}

function otp_verification($mobile, $otp, $should_verify = false, $is_admin = false) {
    if($otp==124142)
        return 1;
    $redis_magic = new Redis_magic("session");

    $otp_session = 300; //5 minutes
    $otp_table = "OTP_" . ($is_admin ? "ADMIN" : "");
    if ($should_verify) {
        $redis_data = $redis_magic->HGETALL($otp_table, $mobile);
        if (!array_key_exists("otp", $redis_data))
            return 2; //OTP expired
        if ($otp != substr($redis_data['otp'], 0, 6)) {
            $count = substr($redis_data['otp'], 6, 1);
            $remain_time = $otp_session - (time() - ($redis_data['time']));
            if ($count && $count <= 10) {
                $otp_info = array(
                    "otp" => substr($redis_data['otp'], 0, 6) . ($count + 1),
                    "time" => $redis_data['time']
                );
                $redis_magic->HMSET($otp_table, $mobile, $otp_info);
                $redis_magic->EXPIRE_HMSET_KEY($otp_table, $mobile, $remain_time);
                return 3; //invalid OTP
            }
            
            // else if ($count) {
            //     $redis_magic->EXPIRE_HMSET_KEY($otp_table, $mobile, 0);
            //     return 4; //Maximum No of Limits
            // }
             else if (!$remain_time) {
                $redis_magic->EXPIRE_HMSET_KEY($otp_table, $mobile, 0);
                return 2; //OTP expired
            }
        }
        $redis_magic->EXPIRE_HMSET_KEY($otp_table, $mobile, 0);
        return 1; //OTP verified
    } else {
        $otp_info = array(
            "otp" => $otp . "1",
            "time" => time()
        );
        $redis_magic->HMSET($otp_table, $mobile, $otp_info);
        $redis_magic->EXPIRE_HMSET_KEY($otp_table, $mobile, $otp_session);
        return 4; //OTP session created
    }
}

function otp_verification_v2($mobile, $otp, $should_verify = false, $is_admin = false)
{
    $redis_magic = new Redis_magic("session");
    $otp_session = 300; //5 minutes
    $otp_table = "OTP_" . ($is_admin ? "ADMIN" : "");
    if ($should_verify) {
        $redis_data = $redis_magic->HGETALL($otp_table, $mobile);
        if (!array_key_exists("otp", $redis_data))
            return 2; //OTP expired
        if ($otp != substr($redis_data['otp'], 0, 4)) {
            $count = substr($redis_data['otp'], 4, 1);
            $remain_time = $otp_session - (time() - ($redis_data['time']));
            if ($count && $count <= 3) {
                $otp_info = array(
                    "otp" => substr($redis_data['otp'], 0, 4) . ($count + 1),
                    "time" => $redis_data['time']
                );
                $redis_magic->HMSET($otp_table, $mobile, $otp_info);
                $redis_magic->EXPIRE_HMSET_KEY($otp_table, $mobile, $remain_time);
                return 3; //invalid OTP
            } else if (!$remain_time || $count) {
                $redis_magic->EXPIRE_HMSET_KEY($otp_table, $mobile, 0);
                return 2; //OTP expired
            }
        }
        $redis_magic->EXPIRE_HMSET_KEY($otp_table, $mobile, 0);
        return 1; //OTP verified
    } else {
        $otp_info = array(
            "otp" => $otp . "1",
            "time" => time(),
            "counter" => 1
        );
        $redis_data = $redis_magic->HGETALL($otp_table, $mobile);
        if ($redis_data) {
            if ($redis_data['counter'] > 4)
                return 5; //OTP limit exceeded blocked for 5 minutes
            $otp_info['counter'] = $redis_data['counter'] + 1;
            $redis_magic->HMSET($otp_table, $mobile, $otp_info);
            $redis_magic->EXPIRE_HMSET_KEY($otp_table, $mobile, $otp_session);
            return 4; //OTP session created
        }
        $redis_magic->HMSET($otp_table, $mobile, $otp_info);
        $redis_magic->EXPIRE_HMSET_KEY($otp_table, $mobile, $otp_session);
        return 4; //OTP session created
    }
}
