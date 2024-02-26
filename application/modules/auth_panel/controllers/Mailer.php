<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mailer extends MX_Controller {
    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        $this->load->helper('aul');
        $this->load->helper('aes');
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation', 'uploads');
        $this->load->model("mailer_model");
    }

    public function send_email() {
        $view_data['page'] = 'send_email';
        $data['page_title'] = "Mail";
        $data['page_data'] = $this->load->view('mailer/send_email', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function add_email_template() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('template_name', 'Template name', 'required|is_unique[mailer.template_name]');
           // $this->form_validation->set_rules('template_html', 'template html', 'required');


            if ($this->form_validation->run() == FALSE) {
                $error = $this->form_validation->get_all_errors();
                page_alert_box("error","Add Email Template",array_values($error)[0]);
            } else {
                $insert_data = array(
                    'template_name' => str_replace(" ", "_", $this->input->post('template_name')),
                    'template_html' => $this->input->post('template_html'),
                    'type' => "open",
                    // 'app_id' => (defined("APP_ID") ? "" . APP_ID . "" : "0")
                );

                $add_series = $this->db->insert('mailer', $insert_data);
                backend_log_genration(
                    $this,
                    "Template has been added successfully",
                    "Add Email Template"
                );
                page_alert_box('success', 'Action performed', 'Template added successfully');
            }
            $view_data['page'] = 'send_email';
            $data['page_title'] = "Mail";
            $data['page_data'] = $this->load->view('mailer/send_email', $view_data, TRUE);
            echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
        } else {
            $view_data['page'] = 'add_email_template';
            $data['page_title'] = "Mail Template";
            $data['page_data'] = $this->load->view('mailer/add_email_template', $view_data, TRUE);
            echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
        }
    }

     public function getsmstype() {
        $view_data['page'] = 'send_email';
        $data['page_title'] = "Mail";
        $data['page_data'] = $this->load->view('mailer/get_smstype', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

     function is_status_active($data){
        // $appid = (defined("APP_ID") && APP_ID)?APP_ID:0; 
                 $this->db->where("status","0");
                // $this->db->where("app_id",$appid);
                 $this->db->where("meta_name",$data["meta_name"]);
        $app =   $this->db->get("meta_information");
        return ($app->num_rows() > 0 )?true:false;
    }

    public function addsmstype() {
        if ($this->input->post()) {
        $any_detail = json_encode($this->input->post());
        // print_r($any_detail);die;
        //validate message status start
        
        $input = $this->input->post();
        // print_r($input);
           $is_status_active = $this->is_status_active($input);
           //echo $this->db->last_query();die;
                    if ($is_status_active == true) {
                        page_alert_box("error", "Email Exist", "The status is already activate, please deactivate first.");
                        redirect(AUTH_PANEL_URL . "mailer/addsmstype");
                    }
        //validate message status end
        $meta_name = $this->input->post("meta_name");
        if ($meta_name == "TWILIO_DETAIL") {          
           $result =  set_db_meta_key($this->db, "TWILIO_DETAIL", aes_cbc_encryption($any_detail, ''));
           if($result){
            //update strat
                $data = array(               
                    "status" => $input['status'],            
                );   
                // $appid = (defined("APP_ID") && APP_ID)?APP_ID:0; 
                // $this->db->where("app_id",$appid);        
                $this->db->where("meta_name", $meta_name);
                $this->db->update('application_manager', $data);    
           //update end        
            }
        }
        else if ($meta_name == "ASPIRE_DETAIL") {
            // print_r($any_detail);die;
           $result =  set_db_meta_key($this->db, "ASPIRE_DETAIL", aes_cbc_encryption($any_detail, ''));
            print_r($result);die;
            if($result){
                //update strat
                $data = array(               
                    "status" => $input['status'],            
                );           
                // $appid = (defined("APP_ID") && APP_ID)?APP_ID:0; 
                // $this->db->where("app_id",$appid);        
                $this->db->where("meta_name", $meta_name);
                $this->db->update('application_manager', $data);   
                echo $this->db->last_query(); 
           //update end  
            }
        } 
        else if ($meta_name == "MSG91_DETAIL") {
            // print_r($any_detail);die;
            $result = set_db_meta_key($this->db, "MSG91_DETAIL", aes_cbc_encryption($any_detail, ''));
            if($result){
                //update strat
                    $data = array(               
                        "status" => $input['status'],            
                    );           
                // $appid = (defined("APP_ID") && APP_ID)?APP_ID:0; 
                // $this->db->where("app_id",$appid);        
                $this->db->where("meta_name", $meta_name);
                $this->db->update('application_manager', $data);    
             //update end  
            }
        } 
          backend_log_genration($this, 'Gateway Detail Changed', 'Gateway Detail');
        page_alert_box("success", "Configuration", "Configuration Saved Successfully");
        redirect(AUTH_PANEL_URL . "mailer/addsmstype"); 
    }else{
        $view_data['info'] = new stdClass();
        $view_data['info']->twilio_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "TWILIO_DETAIL"),''), true);
        $view_data['info']->aspire_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "ASPIRE_DETAIL"),''), true);
        $view_data['info']->msg91_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "MSG91_DETAIL"),''), true);
        // echo $this->db->last_query();
        // echo "<pre";
          //print_r($view_data);
         $view_data['page'] = 'Add_SMS_template';
            $data['page_title'] = "SMS Template";
            $data['page_data'] = $this->load->view('mailer/add_smstype', $view_data, TRUE);
            echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
      
        // echo $this->db->last_query();die;
      
    }

    public function ajax_get_all_template() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'template_name',
        );

        $query = "SELECT count(id) as total FROM mailer ";
        // $query .= app_permission("app_id",$this->db);
        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT * FROM mailer where 1=1 ";
        // $sql .= app_permission("app_id");
        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " AND template_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();
        $data = array();
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = $r->id;
            $nestedData[] = $r->template_name;
            $action = "<a class='btn-sm btn btn-success' href='" . AUTH_PANEL_URL . "mailer/edit_email_template/" . $r->id . "'>Edit</a>&nbsp";
            if ($r->type != "fix") {
                $action .= "<a class='btn-sm btn btn-danger' href='" . AUTH_PANEL_URL . "mailer/delete_email_template/" . $r->id . "'>Delete</a>";
            }
            $nestedData[] = $action;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
    }

    public function edit_email_template($id) {
        $view_data['page'] = 'send_email';
        $data['page_title'] = "Edit Email Template";
        $view_data['template'] = $this->mailer_model->get_single_email_template($id);
        $data['page_data'] = $this->load->view('mailer/edit_email_template', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function delete_email_template($id) {

        $this->db->where('id', $id);
        $result = $this->db->delete("mailer");

        page_alert_box('success', 'Action performed', 'Template deleted successfully');
        $view_data['page'] = 'send_email';
        $data['page_title'] = "Mail";
        $data['page_data'] = $this->load->view('mailer/send_email', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function update_edited_template() {

        $result =   $this->mailer_model->update_edited_template($_POST);
        if($result){
            page_alert_box("success","Update Email Template","Template has been updated successfully.");
            backend_log_genration(
                $this,
                "Template has been udpated successfully",
                "Edit Email Template"
            );
        }else{
            page_alert_box("error","Update Email Template","Something Went Wrong.");
        }
        redirect('auth_panel/mailer/edit_email_template/' . $_POST['id']);
    }

}
