<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Welcome extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper(['custom']);
        header("Access-Control-Allow-Origin: *");
    }
    
    public function index(){
        $this->load->view('coming_soon');
    }
}