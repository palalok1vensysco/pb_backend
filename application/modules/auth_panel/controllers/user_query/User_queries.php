<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_queries extends MX_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('user_queries_model');
		$this->load->helper("services");

	}

	public function submit_query(){

		$this->validate_submit_query();
        //$this->input->post('status')==1;
		$this->user_queries_model->submit_query($this->input->post());
		/* send email to admin */
//		$user = services_helper_user_basic($this->input->post('user_id'));
//		$email['from'] = $user['email'];
//		$email['name'] = $user['name'];
//		$email['description'] =$this->input->post('description');

		//modules::run('data_model/emailer/User_query_email/send_email_to_support', $email);
		/* send message to user */
		//modules::run('data_model/user/Mobile_auth/send_while_user_submit_feedback',  array("user_id"=>$this->input->post('user_id')));
		
		//$msg = $this->lang->line('thanks_msg_for_query');	
		// 'Thank you, we have received your message, we will get back to you as soon as possible.'
		return_data(true,"Query Submitted ",array());
	}

	private function validate_submit_query(){
		
		post_check();
		
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->set_rules('category','category', 'trim|required');
		$this->form_validation->set_rules('title','title', 'trim|required');
		$this->form_validation->set_rules('description','description', 'trim|required');
		
		
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}
	public function submit_query_reply() {
        $this->validate_submit_query_reply();
        $this->user_queries_model->submit_query_reply($this->input->post());

        return_data(true, "Reply Submitted", array());
    }

    private function validate_submit_query_reply() {
        post_check();
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
        $this->form_validation->set_rules('query_id', 'query_id', 'trim|required');
        $this->form_validation->set_rules('text', 'text', 'trim|required|strip_tags');
        $this->form_validation->run();
        if ($error = $this->form_validation->get_all_errors()) {
            return_data(false, array_values($error)[0], array());
        }
    }

    function get_my_queries() {
        $this->validate_get_my_queries();

        $input = $this->input->post();

       // $this->db->order_by("close_date", "asc");
        $this->db->where("user_id", $input['user_id']);
        $queries = $this->db->get("user_queries")->result_array();
        if ($queries)
            return_data(true, "Queries Displplayed", $queries);
        return_data(false, "Queries Not Displplayed", array());
    }

    private function validate_get_my_queries() {
        post_check();
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
        $this->form_validation->run();
        if ($error = $this->form_validation->get_all_errors()) {
            return_data(false, array_values($error)[0], array());
        }
    }

    function get_query_replies() {
        $this->validate_get_query_replies();

        $input = $this->input->post();

        $this->db->select("*");
        $this->db->where("query_id", $input['query_id']);
        $queries = $this->db->get("user_query_admin_reply")->result_array();
        if ($queries)
            return_data(true, "Query Replies Displplayed", $queries);
        return_data(false, "Query Replies Not Found", array());
    }

    private function validate_get_query_replies() {
        post_check();
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
        $this->form_validation->set_rules('query_id', 'query_id', 'trim|required');
        $this->form_validation->run();
        if ($error = $this->form_validation->get_all_errors()) {
            return_data(false, array_values($error)[0], array());
        }
    }
}