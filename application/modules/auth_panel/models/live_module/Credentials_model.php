<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Credentials_model extends CI_Model {

    function __construct() {
        parent::__construct();
        define("AMS_CLOUDFRONT_KEY", "");
        define("AMS_CLOUDFRONT_SECRET", "");
    }

    function get_credentials() {
        return [
            'version' => 'latest',
            'region' => AMS_REGION,
            'credentials' => [
                'key' => AMS_CLOUDFRONT_KEY, //AMS_S3_KEY,
                'secret' => AMS_CLOUDFRONT_SECRET//AMS_SECRET,
            ],
        ];
    }

    function media_convert_credentials($is_demand_request = 0) {
        return array(
            'version' => 'latest',
            'region' => AMS_REGION,
            'credentials' => array(
                'key' => $is_demand_request ? AMS_CLOUDFRONT_KEY : AMS_S3_KEY,
                'secret' => $is_demand_request ? AMS_CLOUDFRONT_SECRET : AMS_SECRET,
            ),
            'endpoint' => API_ENDPOINT, //defined on media convert controller
        );
    }

    function refine_array($array) {
        if (is_array($array)) {
            $array = (json_encode($array, TRUE));
            $array = str_replace("\u0000", "", $array);
            $array = json_decode($array, TRUE);
        }
        return $array;
    }

}
