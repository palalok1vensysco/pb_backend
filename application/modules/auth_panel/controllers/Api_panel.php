<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api_panel extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->load->model("api_model");
        $this->load->helper('services');
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> API BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    public function add_api() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('api_name', 'Api Name', 'trim|required');
            $this->form_validation->set_rules('method', 'Method', 'trim|required');
//            $this->form_validation->set_rules('parameters', 'Parameters', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
            } else {
                $insert_data = array(
                    'api_name' => $this->input->post('api_name'),
                    'method' => $this->input->post('method'),
                    'creation_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
                $id = $this->api_model->insert_api($insert_data);
                page_alert_box('success', 'Added', 'New Api has been added successfully');
               redirect('admin-panel/add-api');
            }
        }
        $view_data['page'] = 'add_api';
        $data['page_data'] = $this->load->view('api_panel/add_api', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_api($id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('api_name', 'Api Name', 'trim|required');
            $this->form_validation->set_rules('method', 'Method', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $update_data = array(
                    'id' => $this->input->post('id'),
                    'api_name' => (isset($_POST['api_name']) && !empty($_POST['api_name']) ? $_POST['api_name'] : ''),
                    'method' => $this->input->post('method'),
                    'modified_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
                $update = $this->api_model->update_api($update_data);
                page_alert_box('success', 'Updated', 'Api has been updated successfully');
                redirect('admin-panel/add-api');
            }
        }
        $view_data['api'] = $this->api_model->get_api_by_id($id);
        $view_data['page'] = 'add_api';
        $data['page_data'] = $this->load->view('api_panel/edit_api', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function delete_api($id) {
        $delete_user = $this->api_model->delete_api($id);
        page_alert_box('success', 'Deleted', 'Api has been deleted successfully');
        redirect('admin-panel/add-api');
    }

    public function ajax_api_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'api_name'
        );

        $query = "SELECT count(id) as total
                  FROM api_master where status=0";

        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT id,api_name,method,parameters
                FROM api_master where status=0";

        // getting records as per search parameters
        //        if (!empty($requestData['columns'][0]['search']['value'])) {
        //            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        //        }

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND api_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length

        $result = $this->db->query($sql)->result();        
        $data = array();
        foreach ($result as $r) {
            // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->api_name;
            $nestedData[] = $r->method;
            $nestedData[] = $r->parameters;
            $nestedData[] = "<a class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . base_url('admin-panel/edit-api/') . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;
                <a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "api_panel/delete_api/" . $r->id . "'><i class='fa fa-trash-o'></i></a>&nbsp;
            ";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
        );
       // print_r($json_data);die;
        echo json_encode($json_data); // send data as json format
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> API BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
}
