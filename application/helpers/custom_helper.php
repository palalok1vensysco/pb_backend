<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('pre')) {

    function pre($array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

}
if(!function_exists('format_number')){
    function format_number($number){
        return  number_format((float)$number, 2, '.', '');
     }
}

if(!function_exists('ternary')){
    function ternary($val){
         return @$val?@$val:0; 
    }
}
if (!function_exists('get_master_category')) {

    function get_master_category() {
            $CI = & get_instance();
            if(defined("APP_ID") && APP_ID){
                $CI->db->where("app_id",APP_ID);
            }
            $CI->db->where("status",1);
            $meta = $CI->db->get('master_category')->result_array();

            $mster=array();
                foreach ($meta as $key => $value) {
                    $mster[$value['id']]=  $value['name'];
                }
                return $mster;
    }

}

if (!function_exists('generate_password')) {

    function generate_password($password) {
        $options = array(
            'cost' => 10
        );
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

}

if (!function_exists('backend_log_genration')) {

    function backend_log_genration($CI, $comment = "", $segment = "", $data = array()) {
        if (is_array($data)) {
            $data['user_device'] = getallheaders()["User-Agent"];
            $data['remote_ip'] = !empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER['REMOTE_ADDR'];
        } else {
            $data = array(
                "user_device" => getallheaders()["User-Agent"],
                "remote_ip" => !empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER['REMOTE_ADDR']
            );
        }

        $array = array(
            'user_id' => $CI->session->userdata("active_backend_user_id") ?? 0,
            'comment' => $comment,
            'segment' => $segment,
            'creation_time' => time(),
            'json' => $data ? json_encode($data) : json_encode($_POST)
        );
        $CI->db->insert('backend_user_activity_log', $array);
    }

}