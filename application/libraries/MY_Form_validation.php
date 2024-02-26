<?php

class MY_Form_validation extends CI_Form_validation{

    // public  function run($module = '', $group = '') {
    //     (is_object($module)) AND $this->CI = &$module;
    //     // return parent::run($group);
    // }
    // function get_all_errors() {
    //     $error_array = array();
    //     if (!empty($this->_error_array)) {
    //         foreach ($this->_error_array as $k => $v) {
    //             $error_array[$k] = $v;
    //         }
    //         return $error_array;
    //     }
    //     return false;
    // }
    public function edit_unique_with_status($str, $field)
    {
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field, $id);
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, array($field => $str, 'id !=' => $id, 'status !=' => 2))->num_rows() === 0)
            : FALSE;
    }
    public function is_unique_with_status($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, array($field => $str, 'status !=' => 2))->num_rows() === 0)
            : FALSE;
    }



}