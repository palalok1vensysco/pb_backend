<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Banner_model extends CI_Model{
    function __construct() {
        parent::__construct();
    }

    
   
    public function get_category() {
        $this->db->where('status', '0');
        $result = $this->db->get('category')->result_array();
        return $result;
    }
	
	public function insert_banner($data){ 
		$this->db->insert('banner',$data);
		return $this->db->insert_id();
	}

	public function update_banner($data){
		$this->db->where('id',$data['id']); 
		$this->db->update('banner',$data);
		return $this->db->affected_rows();
	}

	public function get_banner_list(){ 
		$this->db->where('status','0');
		$result=$this->db->get('banner')->result_array();
		return $result;
	}

	public function get_banner_by_id($id){
		$this->db->where('id',$id); 
		$result=$this->db->get('banner')->row_array();
		return $result;
	}
	
	public function delete_banner($id){
		$data['status']=2;
		$this->db->where('id',$id);
		$result=$this->db->update('banner',$data);
		return $result;
	}
	
	

}
