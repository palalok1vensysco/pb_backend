<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


#[AllowDynamicProperties]
class MY_Model extends CI_Model {
    function __construct()
    {
        parent::__construct();
		$this->db_read = $this->load->database('reader', TRUE);
    }
}