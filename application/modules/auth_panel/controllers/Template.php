<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('template');
    }

    public function call_default_template($data = null) {

        $this->load->view('template/template', $this->clean_html($data));
    }

    /*
     *  !!!!!!!!!!!!!!    DO NOT EDIT  CODE BELOW HERE !!!!!!!!!!!!!!!!!!!!!!!!!!!!
     */

    public function add_custum_js($javascript = "") {

        return ($javascript != "") ? "##custum_script{ $javascript }custum_script##" : '';
    }

    private function decode_custum_js($data) {
        $output = array('javascript' => "", 'page_data' => $data['page_data']);
        $var = explode('}custum_script##', $output['page_data']);
        if (count($var) > 0) {
            $javascript = [];
            foreach ($var as $v) {
                $v = explode('##custum_script{', $v);
                if (count($v) > 1) {
                    $javascript[] = $v[1];
                    $output['page_data'] = str_replace($v[1], '', $data['page_data']);
                }
            }

            if (is_array($javascript) && count($javascript) > 0) {
                $output['javascript'] = implode('', $javascript);
            }
            $output['page_data'] = str_replace('##custum_script{', '', $output['page_data']);
            $output['page_data'] = str_replace('}custum_script##', '', $output['page_data']);
        }

        if (count($var) == 1 && $output['page_data']) {
            preg_match_all('#<script(.*?)<\/script>#is', $data['page_data'], $matches);
            foreach ($matches[0] as $value) {
                $output['javascript'] .= $value;
            }
            $output['page_data'] = preg_replace('#<script(.*?)<\/script>#is', '', $output['page_data']);
        }

        return $output;
    }

    private function clean_html($data) {
        $clean_data = array();
        $clean_data = $this->decode_custum_js($data);
        $clean_data['page_title'] = (isset($data['page_title'])) ? $data['page_title'] : '';
        $clean_data['page_toast'] = (isset($data['page_toast'])) ? $data['page_toast'] : '';
        $clean_data['page_toast_type'] = (isset($data['page_toast_type'])) ? $data['page_toast_type'] : '';
        $clean_data['page_toast_title'] = (isset($data['page_toast_type'])) ? $data['page_toast_title'] : '';
        return $clean_data;
    }

    /*
     *  !!!!!!!!!!!!!!    DO NOT EDIT  CODE ABOVE  HERE !!!!!!!!!!!!!!!!!!!!!!!!!!!!
     */
}
