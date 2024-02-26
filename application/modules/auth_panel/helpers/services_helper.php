<?php


if (!defined('BASEPATH'))
	exit('No direct script access allowed');


if (!function_exists('pre')) {
	function pre($array) {
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}
}


if (!function_exists('return_data')) {
	function return_data($status=false,$message="",$data = array(),$error=array()){
		if(!$data){
			$data = array("status"=>0);
		}
		echo json_encode(array('status'=>$status,'message'=>$message,'data' => $data ,'error'=>$error)); 
		die;	
	}
}


if (!function_exists('post_check')){
	function post_check() {
		if ($_SERVER['REQUEST_METHOD'] != 'POST'){
			echo json_encode(array('status'=>false,'message'=>"Invalid input parameter.Please use post method.",'data' => array() ,'error'=>array())); 
		die;	
		}
	}
}

if (!function_exists('milliseconds')){
	function milliseconds() {
	    $mt = explode(' ', microtime());
	    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
	}
}

if(!function_exists('is_comma_seprated')){
	function is_comma_seprated($string = "" , $return = "" ){
		
		if ($string != "" && count(explode(",",$string)) > 0) {
			if($return === True){
				return explode(',',$string);
			}
  			return true;
		}else{
			if($return === false){
				return array();
			}
			return false;			
		}
	}
}

//get_user_basic_data
if (!function_exists('services_helper_user_basic')) {
	function services_helper_user_basic($user_id) {
		$CI = & get_instance();
		$CI->db->where('id', $user_id);
		return $CI->db->get('users')->row_array();
	}
}

if (!function_exists('convert_number_to_text')) {

    function convert_number_to_text($number) {
        $number = (int) preg_replace('/[^0-9]/', '', $number);
        if ($number >= 1000) {
            $rn = round($number);
            $format_number = number_format($rn);
            $ar_nbr = explode(',', $format_number);
            $x_parts = array('K', 'M', 'B', 'T', 'Q');
            $x_count_parts = count($ar_nbr) - 1;
            $dn = $ar_nbr[0] . ((int) $ar_nbr[1][0] !== 0 ? '.' . $ar_nbr[1][0] : '');
            $dn .= $x_parts[$x_count_parts - 1];
            return $dn;
            die;
        }
        return $number;
        die;
    }
}