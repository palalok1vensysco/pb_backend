<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Question_bank_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_question_by_id($id) {
        $this->db->select("cqbm.*,csm.name as subject,ctm.topic as topic_name");
        $this->db->join("course_subject_master csm","csm.id = cqbm.subject_id","LEFT");
        $this->db->join("course_subject_topic_master ctm","ctm.id = cqbm.topic_id","LEFT");
        $this->db->where('cqbm.config_id', $id);
        return $this->db->get('course_question_bank_master cqbm')->result_array();
    }
    public function question_by_id($id) {
        $this->db->select("cqbm.*,csm.name as subject,ctm.topic as topic_name");
        $this->db->join("course_subject_master csm","csm.id = cqbm.subject_id","LEFT");
        $this->db->join("course_subject_topic_master ctm","ctm.id = cqbm.topic_id","LEFT");
        $this->db->where('cqbm.id', $id);
        return $this->db->get('course_question_bank_master cqbm')->row_array();
    }

    public function get_languages_by_id($id) {
        return $this->db->get('language_code')->result_array();
    }

    public function get_lang_on_id($id) {
        $this->db->select('lang_code');
        $this->db->where('id', $id);
        return $this->db->get('course_question_bank_master')->result_array();
    }

    public function get_question_by_id_langcode($id, $lang) {
        $this->db->select('*');
        $this->db->where('config_id', $id);
        $this->db->where('lang_code', $lang);
        return $this->db->get('course_question_bank_master')->row_array();
    }

}
