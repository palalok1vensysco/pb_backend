<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Mailer_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_single_email_template($id) {
        $this->db->where('id', $id);
        return $this->db->get('mailer')->row_array();
    }

    public function update_edited_template($document) {

        $this->db->where('id', $document['id']);
        return $this->db->update('mailer', $document);
    }

}
