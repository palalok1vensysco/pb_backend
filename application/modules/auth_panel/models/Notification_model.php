<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Notification_model extends CI_Model{
    function __construct() {
        parent::__construct();
    }

	public function insert_notification($data){ 
		$result=$this->db->insert('notification',$data);
		return $result;
	}
	

}
