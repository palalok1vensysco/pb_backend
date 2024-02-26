<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Studio extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->load->model("live_module/Studio_model");

    }

    public function index() {
        if ($insert = $this->input->post()) {
            $this->form_validation->set_rules("name", "Studio Name", "required|trim");
            $this->form_validation->set_rules("channel_ids[]", "Channel Ids", "required");
            if ($this->form_validation->run() == false) {
                $error = $this->form_validation->get_all_errors();
                page_alert_box("error", "Studio management", array_values($error)[0]);
            } else {
                $channel_ids = $this->input->post("channel_ids");
                $update_data['name'] = $this->input->post('name');
                $insert['status'] = 1;
                $insert['created'] = time();
                $insert['channel_ids'] = implode(",", $channel_ids);
                $insert['app_id'] = (defined("APP_ID") ? "" . APP_ID . "" : "0");
                $this->db->insert('studio_management', $insert);
                if ($this->db->affected_rows() > 0) {
                    $studio_id = $this->db->insert_id();

                    $this->db->where_in("id", $channel_ids);
                    $this->db->set("studio_id", $studio_id);
                    $this->db->set("remark", "Assigned to " . $update_data['name']);
                    $this->db->update("aws_channel");

                    page_alert_box('success', 'Studio Management', 'Studio has been added successfully.');
                    backend_log_genration($this, 'Add Studio -: ' . $insert['name'], 'STUDIO');
                }
            }
        }
        if ($this->input->get("studio_id")) {
            $studio_id = $this->input->get("studio_id");
            if (defined("APP_ID"))
            $this->db->where("app_id", APP_ID);

            $view_data['studio_detail'] = $this->db->get_where("studio_management", array("id" => $studio_id))->row();
            app_permission("app_id",$this->db);
            $view_data['edit_channel_list'] = $this->Studio_model->channel_list($studio_id);
        }
        app_permission("app_id",$this->db);
        $view_data['channel_list'] = $this->Studio_model->channel_list();
        $view_data['breadcrum']=!empty($_GET['studio_id']) ? array('Studio'=>"live_module/studio/index", $view_data['studio_detail']->name) :array('Studio'=>"#");
        $data['page_data'] = $this->load->view('live_module/studio', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_studio() {
        $this->form_validation->set_rules("name", "Studio Name", "required|trim");
        $this->form_validation->set_rules("status", "Status", "required");
        if ($this->form_validation->run() == false) {
            $error = $this->form_validation->get_all_errors();
            page_alert_box("error", "Studio management", array_values($error)[0]);
        } else {
            $channel_ids = $this->input->post("channel_ids");
            $studio_id = $this->input->post("studio_id");

            $update_data = $this->input->post();
            $update_data['channel_ids'] = $channel_ids ? implode(",", $channel_ids) : "";
            unset($update_data['studio_id']);

            $this->db->where("id", $studio_id);
            $this->db->set($update_data);
            $this->db->update('studio_management');
            if ($this->db->affected_rows() > 0) {

                $this->db->where("studio_id", $studio_id);
                $this->db->set("studio_id", 0);
                $this->db->update("aws_channel");

                if ($channel_ids) {
                    $this->db->where_in("id", $channel_ids);
                    $this->db->set("studio_id", $studio_id);
                    $this->db->set("remark", "Assigned to " . $update_data['name']);
                    $this->db->update("aws_channel");
                }

                page_alert_box('success', 'Studio Management', 'Studio has been updated successfully.');
                backend_log_genration($this, 'Update Studio -: ' . $update_data['name'], 'STUDIO');
            } 
        }
        
        redirect_to_back();
    }
    //function for delete studio
    public function delete_studio($id) {
        // $id = $_GET['id'];
        $status = $this->Studio_model->delete_studio($id);
        page_alert_box('success', 'Action performed', 'studio deleted successfully');
        if ($status) {
            redirect('auth_panel/live_module/studio/index');
        }
    }

    public function ajax_update_studio_status() {
        $studio_id = $this->input->post("studio_id");
        if ($studio_id) {
            $status = ($this->input->post("status") == "enable") ? 1 : 0;
            $this->db->where("id", $studio_id);
            $this->db->set("status", $status);
            $this->db->update("studio_management");
            if ($this->db->affected_rows() > 0) {
                backend_log_genration($this, "Studio status has been {$this->input->post("status")}d.", "Update Studio Status");
                echo json_encode(array("type" => "success", "title" => "Update Studio Status", "message" => "Studio status has been {$this->input->post("status")}d."));
                die;
            }
        }
        echo json_encode(array("type" => "error", "title" => "Update Studio Status", "message" => "Something went wrong,Please try again."));
    }

    public function ajax_studio_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'name',
            4 => 'created'
        );
        $where_arr = array();
        $this->db->where($where_arr);
        
        $totalData = $totalFiltered = $this->db->count_all_results("studio_management");
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $where_arr['sm.id'] = $requestData['columns'][0]['search']['value'];
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $where_arr['sm.name LIKE'] = $requestData['columns'][1]['search']['value'] . '%';
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $where_arr['ac.channel_name LIKE'] = $requestData['columns'][2]['search']['value'] . '%';
        }
        if ($where_arr) {
            $this->db->join("aws_channel ac", "ac.studio_id = sm.id", "LEFT");
            $this->db->where($where_arr);
            $totalFiltered = $this->db->count_all_results("studio_management sm");
        }
        if (defined("APP_ID"))
        $this->db->where("sm.app_id", APP_ID);
        $this->db->select("sm.*,group_concat(ac.channel_name) as channel_name");
        $this->db->join("aws_channel ac", "ac.studio_id = sm.id", "LEFT");
        $this->db->group_by("sm.id");
        $this->db->order_by($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir']);
        $this->db->limit($requestData['length'], $requestData['start']);
        $result = $this->db->get_where("studio_management sm", $where_arr)->result();      
        $data = array();
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = $r->id;
            $nestedData[] = $r->name;
            $channel = "";
            $ch = explode(",", $r->channel_name);
            $ch = array_filter($ch);
            foreach ($ch as $key => $value) {
                $channel .= "<span class='badge badge-success'>" . $value . "</span>";
            }
            $nestedData[] = $channel ? $channel : "N/A";
            $nestedData[] = $r->status ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>In-Active</span>";
            $nestedData[] = ($r->created > 0) ? get_time_format($r->created) : "--NA--";

            if ($r->status == 1) {
                $control = "<a class='btn-xs bold btn btn-danger action_element' data-id='" . $r->id . "' data-status='disable' title='Disable Studio' ><i class='fa fa-lock' aria-hidden='true'></i>&nbsp</a>";
            } else {
                $control = "<a class='btn-xs bold btn btn-warning action_element' data-id='" . $r->id . "' data-status='enable' title='Enable Studio'> <i class='fa fa-unlock' aria-hidden='true'></i>&nbsp</a>";
            }

            $nestedData[] = "<a class='btn-xs bold btn btn-info' href='" . AUTH_PANEL_URL . "live_module/studio/index?studio_id=" . $r->id . "' title='Edit Studio'><i class='fa fa-pencil'></i></a>&nbsp; $control&nbsp; <a class='btn-xs bold btn btn-info' href='" . AUTH_PANEL_URL . "live_module/Studio/delete_studio/" . $r->id . "' title='Edit Studio'><i class='fa fa-trash'></i></a>";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they 			first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        echo json_encode($json_data);
    }

}
