
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User_query_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_query_detail($id) {
        //get User id from user query table..
        $this->db->where('id', $id);
        $result['query'] = $this->db->get('user_queries')->row_array();
       // pre($result['query']); die;

        // get user query record by user id from  user query table..
        $this->db->select('u.id,u.name,u.email,u.mobile,u.created_at,u.profile_picture');
        $this->db->where("id", $result['query']['user_id']);
        $result['user_info'] = $this->db->get("users u")->row_array();

        $this->db->select('uqar.*,bu.username,bu.profile_picture as backend_image');
        $this->db->where('uqar.query_id', $result['query']['id']);
        $this->db->join('backend_user as bu', 'uqar.backend_user_id = bu.id', 'LEFT');
        $result['query_reply'] = $this->db->get('user_query_admin_reply as uqar')->result_array();

        return $result;
    }

}