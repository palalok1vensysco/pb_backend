<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('form_validation', 'uploads');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('html');
        //$this->load->helper('common');
        $this->load->helper('custom');
        $this->load->model('Api_model');
    }

////////////////////////////////////////
//                                    //
//      Modified by: Shashank Mishra //
//      Created On : 20 FEB 2021      //
//                                    //
/////////////////////////////////////-->



    public function index() {
        //echo base_url('ios_certificates/Distributionck.pem');
        $filters = [];
        $data = array('title' => 'API Doc', 'pageTitle' => 'API Doc');
        $filters['id'] = 2;
        $filters['user'] = $this->uri->segment(4);
        if (isset($filters['user']) && $filters['user'] == "admin") {
            $this->session->set_userdata('profile', 1);
        }
        //pre($this->session->userdata['profile']);
        $data['api_detail'] = $this->Api_model->get_api($filters);
        $data['controllers'] = $this->Api_model->get_sidebar_menu(array());
        //pre($data); die;
        $this->load->view('api/home', $data);
    }

    public function doc() {
        $filters = [];
        $data = array('title' => 'API Doc', 'pageTitle' => 'API Doc');
        $filters['id'] = $this->uri->segment(4);
        $data['api_detail'] = $this->Api_model->get_api($filters);
        $data['controllers'] = $this->Api_model->get_sidebar_menu(array());
        $this->load->view('api/api_doc', $data);
    }

    public function api_list() {
        //pre($this->session->userdata); die;
        $filters = [];
        $data = array('title' => 'All API list', 'pageTitle' => 'API list');
        $filters['status'] = 1;
        $data['api_all'] = $this->Api_model->get_api($filters);
        //pre($data['api_all']); die;
        $data['controllers'] = $this->Api_model->get_sidebar_menu(array());
        $this->load->view('api/api_list', $data);
    }

    public function create() {
        if (!isset($this->session->userdata['profile']) or empty($this->session->userdata['profile'])) {
            redirect('api_doc/Api');
        }

        $filters = [];
        $data = array('title' => 'Create New', 'pageTitle' => 'Create New');
        $filters['id'] = $this->uri->segment(4);
        if (isset($filters['id']) && !empty($filters['id'])) {
            $data = array('title' => 'Edit API', 'pageTitle' => 'Edit API');
            $data['api_detail'] = $this->Api_model->get_api($filters);
        }
        if ($this->input->post()) {
            $input = $this->input->post();
            $input['controller'] = ucfirst($this->input->post('controller'));
            $inserted = $this->Api_model->create_api($input);
            if ($inserted) {
                redirect('api_doc/Api/api_list');
            }
        }
        $data['controllers'] = $this->Api_model->get_sidebar_menu(array());
        $this->load->view('api/api_create', $data);
    }

    public function copy() {
        if (!isset($this->session->userdata['profile']) or empty($this->session->userdata['profile'])) {
            redirect('api_doc/Api');
        }
        $filters['id'] = $this->uri->segment(4);
        if (isset($filters['id']) && !empty($filters['id'])) {
            $data = array('title' => 'Save New', 'pageTitle' => 'Save New');
            $data['api_detail'] = $this->Api_model->get_api($filters);
        }
        if ($this->input->post()) {
            $input = $this->input->post();
            $input['controller'] = ucfirst($this->input->post('controller'));
            $inserted = $this->Api_model->copy_api($input);
            if ($inserted) {
                redirect('api_doc/Api/api_list');
            }
        }
        $data['controllers'] = $this->Api_model->get_sidebar_menu(array());
        $this->load->view('api/api_create', $data);
    }

    public function deleted_api_list() {
        if (!isset($this->session->userdata['profile']) or empty($this->session->userdata['profile'])) {
            redirect('api_doc/Api');
        }
        $filters = [];
        $data = array('title' => 'Deleted API list', 'pageTitle' => 'API list');
        $filters['status'] = 0;
        //pre($filters); die;
        $data['api_all'] = $this->Api_model->get_api($filters);
        $data['controllers'] = $this->Api_model->get_sidebar_menu(array());
        $data['opt_for_dlt'] = 1;
        $this->load->view('api/api_list', $data);
    }

    public function change_status() {
        if (!isset($this->session->userdata['profile']) or empty($this->session->userdata['profile'])) {
            redirect('api_doc/Api');
        }
        $filters['id'] = $this->uri->segment(4);
        $delete = $this->Api_model->change_api_status($filters);
        if ($delete) {
            $url = $_SERVER['HTTP_REFERER'];
            redirect($url);
        }
    }

    public function delete_api_perma() {
        if (!isset($this->session->userdata['profile']) or empty($this->session->userdata['profile'])) {
            redirect('api_doc/Api');
        }
        $filters['id'] = $this->uri->segment(4);
        $delete = $this->Api_model->delete_api($filters);
        if ($delete) {
            $url = $_SERVER['HTTP_REFERER'];
            redirect($url);
        }
    }

    public function logout() {
        $this->session->unset_userdata();
        $this->session->sess_destroy();
        redirect('api_doc/Api');
    }

}
