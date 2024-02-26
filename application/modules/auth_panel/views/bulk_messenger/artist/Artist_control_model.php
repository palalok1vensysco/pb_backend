<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Artist_control_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	public function get_artist() {
		$this->db->where('status', '0');
		$result = $this->db->get('singer')->result_array();
		return $result;
	}

	public function get_artist_by_id($id) {
		$this->db->where('id', $id);
		$result = $this->db->get('singer')->row_array();
		return $result;
	}

	public function get_artist_by_ids($ids) {
		pre($ids);
		$this->db->select('artist_name');
		$this->db->where_in('id', $ids);
		$result = $this->db->get('singer')->result_array();
		echo $this->db->last_query();
		return $result;
	}

	public function insert_artist($data) {
		$data['artist_name'] = ucwords($data['artist_name']);

		$this->db->insert('singer', $data);
		return $this->db->insert_id();
	}

	public function update_artist($data) {
		$id = $data['id'];
		$this->db->where('id', $id);
		$result = $this->db->update('singer', $data);
		return $result;
	}

	public function delete_artist($id) {
		$this->db->where('id', $id);
		$data['status'] = '2';
		$result = $this->db->update('singer', $data);
		return $result;
	}

}
