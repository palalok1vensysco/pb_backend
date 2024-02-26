<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bulk_message extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
    }

    public function send_bulk_message() {

        if ($_POST && $_POST['messanger_type'] == 'raw') {
            $this->form_validation->set_rules('message', 'Message', 'required');
            if ($this->form_validation->run() != FALSE) {
                $this->trigger_message();
                //insert message details in table..
                $this->db->insert('sent_sms', array(
                    'send_by' => $this->input->post('send_by'),
                    'send_to' => $this->input->post('user_type'),
                    'message' => $this->input->post('message'),
                    'send_date' => time()
                ));

                $data['page_toast'] = 'Message sent successfully.';
                $data['page_toast_type'] = 'success';
                $data['page_toast_title'] = 'Action performed.';
            }
            $this->session->set_flashdata('error', 'raw_error');
        } else if ($_POST && $_POST['messanger_type'] == 'custom') {
            $this->form_validation->set_rules('mobile', 'Mobile Number', 'required');
            $this->form_validation->set_rules('message', 'Message', 'required');
            if ($this->form_validation->run() != FALSE) {
                $this->trigger_custom_message();
                //insert message details in table..
                $this->db->insert('sent_sms', array(
                    'send_by' => $this->input->post('send_by'),
                    'send_to' => $this->input->post('mobile'),
                    'message' => $this->input->post('message'),
                    'send_date' => time()
                ));

                $data['page_toast'] = 'Message sent successfully.';
                $data['page_toast_type'] = 'success';
                $data['page_toast_title'] = 'Action performed.';
            }
            $this->session->set_flashdata('error', 'custom_error');
        }

        $view_data['page'] = 'send_sms';
        $data['page_data'] = $this->load->view('bulk_messenger/send_mobile_message', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    private function trigger_message() {

        if ($this->input->post('user_type') == "dams") {
            $total = $this->db->query('select count(id) as total  from users where status = 0 and erp_token != ""')->row()->total;
            $query = 'select * from users where status = 0 and erp_token != ""';
        } elseif ($this->input->post('user_type') == "non_dams") {
            $total = $this->db->query('select count(id) as total  from users where status = 0 and erp_token = ""')->row()->total;
            $query = 'select * from users where status = 0 and erp_token = ""';
        } else {
            $total = $this->db->query('select count(id) as total  from users where status = 0 ')->row()->total;
            $query = 'select * from users where status = 0 ';
        }

        $sms = "";
        for ($i = 0; $i < $total; $i++) {

            $record = $this->db->query($query . ' limit ' . $i . ' , 20')->result_array();
            foreach ($record as $r) {
                $sms .= "<sms><to>" . $r['mobile'] . "</to></sms>";
            }

            $sms = "";
            $i = $i + 20;
        }
    }

    private function trigger_custom_message() {

        $mobile = $this->input->post('mobile');
        $mobile = explode(',', $mobile);

        $sms = "";

        foreach ($mobile as $r) {
            $sms .= "<sms><to>" . $r . "</to></sms>";
        }
    }

    public function ajax_send_messages() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;

        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'send_by',
            3 => 'message'
        );

        $query = "SELECT count(id) as total FROM sent_sms where 1 = 1 ";
        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT * FROM sent_sms   where 1 = 1 ";

        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " AND send_by LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {  //salary
            $sql .= " AND message LIKE '%" . $requestData['columns'][3]['search']['value'] . "%' ";
        }


        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length
        $result = $this->db->query($sql)->result();
        $data = array();
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = $r->id;
            $nestedData[] = $r->send_by;
            $nestedData[] = $r->send_to;
            $nestedData[] = $r->message;
            $date = date('Y-m-d H:i:s', $r->send_date);
            $date = date("d M Y", strtotime($date));
            $nestedData[] = $date;
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

}
