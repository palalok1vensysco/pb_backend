<?php
////////////////////////////////////////
//                                    //
//      Modified by: Shashank Mishra //
//      Created On : 20 FEB 2021      //
//                                    //
/////////////////////////////////////-->


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_api($document) { //pre($document); die;
        if (isset($document['controller']) && !empty($document['controller'])) {
            $this->db->where('controller', $document['controller']);
        }
        if (isset($document['id']) && !empty($document['id'])) {
            $this->db->where('id', $document['id']);
        }
        if (isset($document['status'])) {
            $this->db->where('status', $document['status']);
        }
        //$this->db->select('controller,name,url');
        $this->db->order_by('name', 'asc');
        $controller = $this->db->get('api_doc')->result_array();
        if ($controller) {
            if (isset($document['id']) && !empty($document['id'])) {
                return $controller[0];
            } else {
                return $controller;
            }
        } else {
            return false;
        }
    }

    function get_sidebar_menu($document) { //pre($document); die;
        $new_api = $filter = $results = array();
        $this->db->select('controller');
        $this->db->order_by('name', 'asc');
        $controllers = $this->db->get('api_doc')->result_array();
        if (!empty($controllers)) {
            foreach ($controllers as $controller) {
                $api_controller[] = $controller['controller'];
            }
            $unique = array_unique($api_controller);

            foreach ($unique as $value) {
                $new_api[] = $value;
            }
            for ($i = 0; $i < count($new_api); $i++) {
                $controller_name['controller'] = $new_api[$i];
                $controller_name['status'] =1;
                $new_api[$i] = $this->get_api($controller_name);
            }
        }
        return $new_api;
    }

    function create_api($document) { //pre($document); die;
        if (isset($document['id']) && !empty($document['id'])) {
            $this->db->where('id', $document['id']);
            $this->db->update('api_doc', $document);
            $insert_id = $document['id'];
        } else {
            $inserted = $this->db->insert('api_doc', $document);
            $insert_id = $this->db->insert_id();
        }
        return $insert_id;
    }
    
    
    function copy_api($document){
        unset($document['id']);
        $inserted = $this->db->insert('api_doc', $document);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    
    function change_api_status($document){
        $id = $document['id'];
        $status = 1;
        $result = $this->get_api(array("id" => $id));
        //pre($result); die;
        if ($result['status'] == '1') {
            $status = 0;
        }
        $this->db->where('id', $id);
        $this->db->update('api_doc', array('status' => $status));
        return true;
        
    }
    
    function delete_api($document){
        $this->db->where('id', $document['id']);
        $deleted = $this->db->delete('api_doc');
        return $deleted;
    }
    
    
    
    

}


