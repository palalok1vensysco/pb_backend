<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Push_notification extends MX_Controller
{
    protected $redis_magic = null;

    function __construct()
    {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->load->helper("push_helper");
        $this->redis_magic = new Redis_magic("session");
    }

    public function send_push_notification($id=null)
    {
        if ($_POST) {
            $this->form_validation->set_rules('custom_message', 'Message', 'required');
            if ($this->form_validation->run() != FALSE) {
                $course_id = 0;
                $url = "";
                if ($this->input->post('notification_type') == 2) {
                    $course_id = $this->input->post('notification_text');
                } else {
                    $url = $this->input->post('notification_text');
                }
                $push_data = json_encode(
                    array(
                        'notification_code' => 90001,
                        'message' => strip_tags(trim($this->input->post('custom_message'))),
                        'title' => $this->input->post('title'),
                        'data' => array(
                            "message_target" => $this->input->post('notification_type'),
                            "url" => $url,
                            'course_id' => $course_id
                        )
                    )
                );

                generatePush($this->input->post('device_type'), $this->input->post('device_token'), $push_data);                
                $this->save_notification($this->input->post('user_id'), $course_id, $this->input->post('notification_text'), $this->input->post('device_type'), $this->input->post('title'), $this->input->post('custom_message'), $this->input->post('notification_type'));
                page_alert_box("success", 'Action performed.', 'Message sent successfully.');
            } else
                $this->session->set_flashdata('error', 'custom_error');
        }
        $view_data['data_id'] = $id;
        $view_data['res_data'] = $this->db->get_where('notification_scheduler',['id'=>$id])->row_array();
        $view_data['page'] = 'push_notification';
        
        $data['page_data'] = $this->load->view('bulk_messenger/push_notification', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    public function delete($id)
    {
        backend_log_genration($this, 'Push Notification has been deleted id:-' . $id, 'push_notification message ', array('id' => $id, 'request' => $_REQUEST));
        secured_delete('user_activity_generator', ['id' => $id]);
        page_alert_box("success", 'Action performed.', 'Notification Deleted successfully.');
        redirect(AUTH_PANEL_URL . "bulk_messenger/push_notification/send_push_notification");
        // return true;
    }

    public function send_batch_push_notification()
    {
        if ($_POST) {

            $this->form_validation->set_rules('custom_message', 'Message', 'required');
            if ($this->form_validation->run() != FALSE) {
                $course_id = 0;
                $url = "";
                if ($this->input->post('notification_type') == 2) {
                    $course_id = $this->input->post('notification_text');
                } else {
                    $url = $this->input->post('notification_text');
                }
                $push_data = json_encode(
                    array(
                        'notification_code' => 90001,
                        'message' => $this->input->post('custom_message'),
                        'title' => $this->input->post('title'),
                        'data' => array(
                            "message_target" => $this->input->post('notification_type'),
                            "url" => $url,
                            'course_id' => $course_id
                        )
                    )
                );
                backend_log_genration($this, 'generate push', 'generate push message ', array('post' => $push_data, 'request' => $_REQUEST));
                generatePush($this->input->post('device_type'), $this->input->post('device_token'), $push_data);

                $this->save_notification($this->input->post('user_id'), $course_id, $this->input->post('notification_text'), $this->input->post('device_type'), $this->input->post('title'), $this->input->post('custom_message'), $this->input->post('notification_type'));
                page_alert_box("success", 'Action performed.', 'Message sent successfully.');
            } else
                $this->session->set_flashdata('error', 'custom_error');
        }

        $view_data['page'] = 'push_notification';
        $data['page_data'] = $this->load->view('bulk_messenger/batch_push_notification', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function notification_scheduler()
    {
        if ($this->input->post()) {
            $input = $this->input->post('data');
            $insert = ($this->input->post('data')) ?? $this->input->post();
            $courses = ($insert['course_ids']) ?? [];
            $insert["created"] = time();
            $insert["created_by"] = $this->session->userdata('active_backend_user_id');
            $insert['schedule_time'] = strtotime($insert['schedule_time']);

            if (isset($insert['course_ids']) && is_array($insert['course_ids']) && count($insert['course_ids']) > 1) {
                foreach ($courses as $key => $value) {
                    $insert['course_id'] = $value;
                    unset($insert['course_ids']);
                    unset($insert['notification_text']);
                    // pre($insert);die;
                    $this->db->insert('notification_scheduler', $insert);
                    $id = $this->db->insert_id();
                    backend_log_genration($this, 'Notification schedule has been added id:-' . $id, 'notification_scheduler Added', array('post' => $_POST, 'request' => $_REQUEST));
                }
            } else {
                if (!isset($insert['course_ids'])) {
                    $insert['course_id'] = $insert['notification_text'];
                } else {
                    $insert['course_id'] = $insert['course_ids'][0];
                }
                unset($insert['course_ids']);
                unset($insert['notification_text']);
                // pre($insert);die;
                $this->db->insert('notification_scheduler', $insert);
                $id = $this->db->insert_id();
            }

            $url = "";
            if ($input['notification_type'] == 2) {
                $url = $insert['course_id'];
            } else {
                $url = $insert['course_id'];
            }



            $expiry_time  = $insert['schedule_time'] - time();
            $insert = array(
                "state" => "notification",
                "message" =>  $insert["message"],
                "title" => $insert["title"],
                "user_type" => $insert["device_type"] == '' ? 'ALL' : $insert["device_type"],
                "data" => array(
                    "message_target" => $input['notification_type'] ?? 1,
                    "url" => $url
                )
            );
            // $expiry_time=100;
            if ($expiry_time > 0) {
                $this->redis_magic->SET("schedule_notification_detector#" . $id, json_encode($insert)); //for start/end class
                $this->redis_magic->SETEX("t:schedule_notification_detector#" . $id, $expiry_time, "");
                $this->redis_magic->HMSET("schedule_notification_detector", "t:schedule_notification_detector#" . $id.'_'.APP_ID,  time() + $expiry_time);
            }
            echo json_encode(array('status' => 'true', 'message' => 'Schedule Notification', 'title' => 'Successfully'));
            die;



            backend_log_genration($this, 'Notification Scheduler: ', 'SCHEDULER', $insert . "Redi" . $a . $b . $c);
            page_alert_box("success", "Action performed", "Notification scheduled successfully.");
        }
        $view_data['page'] = 'push_notification_scheduler';
        $data['page_data'] = $this->load->view('bulk_messenger/notification_scheduler', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    private function save_notification($user_id, $course_id = 0, $notification_text = '', $device_type = '', $title = '', $message = '', $action_element = '', $additional_json = '', $input = '')
    {
        if ($action_element == 1) {
            $additional_json = $notification_text;
        } else if ($action_element == 5) { //image
            $additional_json = json_encode(array("image" => $notification_text));
        } else if ($action_element == 6) { //url
            $additional_json = json_encode(array("url" => $notification_text));
        }


        $insert_data = array(
            'from_user_id' => $this->session->userdata('active_backend_user_id'),
            'action_element' => (isset($action_element) ? "" . $action_element . "" : "0"),
            'action_element_id' => (isset($course_id) ? "" . $course_id . "" : "0"),
            'device_type' => $device_type,
            'title' => $title,
            'message' => ($message) ? $message : $title,
            'created' => time(),
            'extra' => $additional_json
        );
        $this->db->insert("user_activity_generator", $insert_data);
        $id = $this->db->insert_id();
        backend_log_genration($this, 'User Notification has been  Added id:-' . $id, 'user_activity_generator Added', array('post' => $insert_data, 'request' => $_REQUEST));
        if (is_array($_POST['user_id'])) {

            $user_id = array_unique($this->input->post('user_id'));
            foreach ($user_id as $uid) {
                $insert_array[] = array(
                    'user_id' => $uid,
                    'n_id' => $this->db->insert_id(),
                    'view_state' => 0                    
                );
            }
        } else {
            $insert_array[] = array(
                'user_id' => $this->input->post('user_id'),
                'n_id' => $this->db->insert_id(),
                'view_state' => 0
            );
        }
        $this->db->insert_batch('user_activity_relation', $insert_array);
        $id = $this->db->insert_id();
        backend_log_genration($this, 'User Activity relation added id:-' . $id, 'user_activity_relation added ', array('post' => $_POST, 'request' => $_REQUEST));
    }

    public function ajax_push_messages()
    {
        $requestData = $_REQUEST;

        $columns = array(
            0 => 'id',
            3 => 'message',
            5 => 'created'
        );

        $where_arr = array();

        $totalData = $totalFiltered = $this->db->count_all_results("user_activity_generator");

        if (!empty($requestData['columns'][0]['search']['value']))
            $where_arr["id LIKE"] = $requestData['columns'][0]['search']['value'] . '%';

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $where_arr["sent_by LIKE"] = $requestData['columns'][1]['search']['value'] . '%';
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $where_arr["text LIKE"] = $requestData['columns'][3]['search']['value'] . '%';
        }
        $this->db->where($where_arr);
        app_permission("app_id", $this->db);
        $this->db->order_by($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir']);
        $this->db->limit($requestData['length'], $requestData['start']);
        $result = $this->db->get("user_activity_generator")->result();

        $this->db->where($where_arr);
        app_permission("app_id", $this->db);
        $totalFiltered = $this->db->count_all_results("user_activity_generator");
        $data = array();
        $type = array(
            "1" => "General",
            "2" => "Couse detail",
            "3" => "User profile",
            "4" => "Video",
            '5' => "Image",
            '6' => 'Url'
        );
        foreach ($result as $r) {

            $nestedData = array();
            $nestedData[] = $r->id;
            $nestedData[] = "ADMIN";
            $nestedData[] = device_type($r->device_type);
            $nestedData[] = $r->message;
            $nestedData[] =   !empty($type[$r->action_element]) ? $type[$r->action_element] : "0";
            $nestedData[] = get_time_format($r->created);
            $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn btn-primary dropdown-toggle pushNoticeBtn' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
                        <ul class='dropdown-menu'>
                       <li><a href='" . AUTH_PANEL_URL . "bulk_messenger/push_notification/view_detail/" . $r->id . "'><i class='fa fa-eye'></i> view</a></li>
                       <li><a onclick=\"return confirm('Warning !!!!  Do you really want to delete?');\" href='" . AUTH_PANEL_URL . "bulk_messenger/push_notification/delete/" . $r->id . "'><i class='fa fa-trash'></i> delete</a></li>
                        </ul>
                        </div>";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
        );
        echo json_encode($json_data); // send data as json format
    }

    public function ajax_push_scheduler_list()
    {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;

        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            3 => 'message',
            6 => 'schedule_time'
        );

        $where_arr = array();
        if (defined("APP_ID"))
            app_permission("app_id", $this->db);
        $totalData = $this->db->count_all_results("notification_scheduler");
        $totalFiltered = $totalData;

        if (!empty($requestData['columns'][0]['search']['value']))
            $where_arr["id LIKE"] = $requestData['columns'][0]['search']['value'] . '%';

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $where_arr["sent_by LIKE"] = $requestData['columns'][1]['search']['value'] . '%';
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $where_arr["text LIKE"] = $requestData['columns'][3]['search']['value'] . '%';
        }
        $this->db->where($where_arr);
        if (defined("APP_ID"))
            app_permission("app_id", $this->db);
        $this->db->order_by($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir']);
        $this->db->limit($requestData['length'], $requestData['start']);
        $result = $this->db->get("notification_scheduler")->result();

        $totalFiltered = count($result); // when there is a search parameter then we have to modify total number filtered rows as 

        $data = array();
        $device_type = array(
            "0" => "All",
            "1" => "Andorid",
            "2" => "iOS",
            "3" => "Window",
            "4" => "Web"
        );
        $type = array(
            "1" => "General",
            "2" => "Couse detail",
            "3" => "User profile",
            "4" => "Video",
            '5' => "Image",
            '6' => 'Url'
        );
        foreach ($result as $r) {

            $nestedData = array();
            $nestedData[] = $r->id;
            $nestedData[] = "ADMIN";
            $nestedData[] = ($r->device_type == '') ? 'ALL' : $device_type[$r->device_type];
            $nestedData[] = $r->message;
            $nestedData[] = $type[$r->notification_type];
            if ($r->is_sent) {
                $status = '<span class="badge badge-success">sent</span></h1>';
            } else {
                $status = '<span class="badge badge-primary">Pending</span></h1>';
            }
            $nestedData[] = $status;
            $date = '<span><b>Scheduled at</b> : ' . get_time_format($r->schedule_time) . '</span><br><span><b>Created at</b> : ' . get_time_format($r->created) . '</span>';
            $nestedData[] = $date;
            if ($r->status == 0) {
                $action = "<li><a href='" . AUTH_PANEL_URL . "bulk_messenger/push_notification/delete_scheduler/" . $r->id . "' ><i class='fa fa-trash'></i> cancel</a></li>";
            } else {
                $action = "<li><a href='javascript:void(0)' ><i class='fa fa-eye'></i>cancelled</a></li>";
            }
            $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn btn-primary dropdown-toggle pushNoticeBtn' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
                        <ul class='dropdown-menu'>
                            $action
                        </ul>
                        </div>";

            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
        );
        echo json_encode($json_data); // send data as json format
    }

    public function delete_announcement($id)
    {
        if ($id) {
            app_permission("app_id", $this->db);
            $data = $this->db->get_where('user_activity_generator', array('id' => $id))->row_array();
            $del_where = array('action_element' => $data['action_element'], 'created' => $data['creation_time']);
            secured_delete('user_activity_generator', $del_where);
            page_alert_box('success', 'Action performed', 'Announcement deleted successfully');
        } else {
            page_alert_box('warning', 'Action not performed', 'Please try again');
        }
        redirect(AUTH_PANEL_URL . 'bulk_messenger/push_notification/send_announcement');
    }

    public function delete_scheduler($id)
    {
        if ($id) {
            $this->db->where(array('id' => $id));
            $this->db->update('notification_scheduler', array('status' => 1));
            page_alert_box('success', 'Action performed', 'Schedule cancelled successfully');
        } else {
            page_alert_box('warning', 'Action not performed', 'Please try again');
        }
        redirect(AUTH_PANEL_URL . 'bulk_messenger/push_notification/notification_scheduler');
    }

    public function view_detail($id)
    {
        $view_data['page'] = 'push_notification';
        $view_data['id'] = $id;
        $view_data['breadcrum'] = array('Push notification' => "bulk_messenger/push_notification/send_push_notification", 'Push notification Details' => "#");
        $data['page_data'] = $this->load->view('bulk_messenger/push_notification_detail', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_push_to_user($id)
    {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;

        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'name',
            2 => 'mobile',
            3 => 'email'
        );

        $where_arr = array();

        $query = "SELECT count(id) as total FROM user_activity_relation where n_id = $id ";
        $query .= app_permission("app_id");
        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;
        $sql = "SELECT user_activity_relation.*,name,mobile,email,device_type,view_state  FROM `user_activity_relation` join users on users.id=user_id where n_id=$id ";
        $sql .= app_permission("user_activity_relation.app_id");
        if (!empty($requestData['columns'][0]['search']['value']))
            $where_arr["id LIKE"] = $requestData['columns'][0]['search']['value'] . '%';

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $where_arr["name LIKE"] = $requestData['columns'][1]['search']['value'] . '%';
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $where_arr["mobile LIKE"] = $requestData['columns'][2]['search']['value'] . '%';
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $where_arr["email LIKE"] = $requestData['columns'][3]['search']['value'] . '%';
        }
        $sql = clean_sql($sql);
        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query);
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
        $result = $this->db->query($sql)->result();
        // echo $this->db->last_query();die;
        $start = $requestData['start'];
        $data = array();
        $masked_email = check_permission('web_user/mobile_email_masked');
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = ++$start;
            $nestedData[] = ($r->user_id != 0 ? ($r->name==$r->mobile?'No-Name':$r->name) : 'ALL');
            $nestedData[] = ($r->user_id != 0 ? hide_mobile($r->mobile,$masked_email) : 'ALL');
            $nestedData[] = ($r->user_id != 0 ? hide_email($r->email,$r->mobile,$masked_email) : 'ALL');
            $nestedData[] = ($r->user_id != 0 ? device_type($r->device_type) : 'ALL');
            $nestedData[] = ($r->view_state != 0 ? 'viewed' : 'not viewed');

            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data, // total data array
        );
        echo json_encode($json_data); // send data as json format
    }

    public function complete_notification()
    {
        $view_data['page'] = "complete_notification";
        $view_data['page_title'] = "Complete Notification";
        $data['page_data'] = $this->load->view('bulk_messenger/complete_notification', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function filter_target_payload()
    {
        $requestData = $_REQUEST;
        var_dump($requestData);
        die;

        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'name',
            2 => 'mobile',
            3 => 'email'
        );

        $where_arr = array();

        $query = "SELECT count(id) as total FROM user_activity_relation where n_id = $id ";
        $query .= app_permission("app_id");
        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;
        $sql = "SELECT user_activity_relation.*,name,mobile,email,device_type,view_state  FROM `user_activity_relation` join users on users.id=user_id where n_id=$id ";
        $sql .= app_permission("user_activity_relation.app_id");
        if (!empty($requestData['columns'][0]['search']['value']))
            $where_arr["id LIKE"] = $requestData['columns'][0]['search']['value'] . '%';

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $where_arr["name LIKE"] = $requestData['columns'][1]['search']['value'] . '%';
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $where_arr["mobile LIKE"] = $requestData['columns'][2]['search']['value'] . '%';
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $where_arr["email LIKE"] = $requestData['columns'][3]['search']['value'] . '%';
        }
        $sql = clean_sql($sql);
        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query);
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
        $result = $this->db->query($sql)->result();
        $start = $requestData['start'];
        $data = array();
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = ++$start;
            $nestedData[] = ($r->user_id != 0 ? $r->name : 'ALL');
            $nestedData[] = ($r->user_id != 0 ? $r->mobile : 'ALL');
            $nestedData[] = ($r->user_id != 0 ? $r->email : 'ALL');
            $nestedData[] = ($r->user_id != 0 ? device_type($r->device_type) : 'ALL');
            $nestedData[] = ($r->view_state != 0 ? 'viewed' : 'not viewed');

            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        echo json_encode($json_data);
    }

    public function schedule_notification_list()
    {
        $view_data['page'] = "Schedule_Notification";
        $view_data['page_title'] = "Schedule Notification";
        $data['page_data'] = $this->load->view('bulk_messenger/schedule_notification', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

     public function ajax_schedule_notification_list()
    {
        $requestData = $_REQUEST;
        $start = 0;
        $columns = array(
            0 => 'id',
            3 => 'message',
            5 => 'created'
        );

         $where_arr = array();
        if (!empty($requestData['columns'][1]['search']['value'])) {   //name
            $where_arr['device_type'] =  $requestData['columns'][1]['search']['value'];
        }
        $this->db_read->select(" count(id) as total");
        app_permission("app_id", $this->db_read);
           $this->db_read->where('status',0);
        $totalFiltered = $totalData = $this->db_read->get_where('notification_scheduler', $where_arr)->row()->total;

        app_permission("app_id", $this->db_read);
        $this->db_read->where($where_arr);
         $this->db_read->where('status',0);
        $this->db_read->order_by($columns[0], 'DESC');
        $this->db_read->limit($requestData['length'], $requestData['start']);
        $result = $this->db_read->get("notification_scheduler")->result();
        // echo $this->db_read->last_query();
        $data = array();
        $type = array(
            "1" => "General",
            "2" => "Couse detail",
            "3" => "User profile",
            "4" => "Video",
            '5' => "Image",
            '6' => 'Url'
        );
        if(!empty($result)){
            foreach ($result as $r) {

                if ($r->device_type == 1) {
                    $device_type = 'ANDROID';
                } elseif ($r->device_type == 2) {
                    $device_type = 'iOS';
                } else {
                    $device_type = 'All';
                }

                $nestedData = array();
                $nestedData[] = ++$start;
                $nestedData[] = $r->id;
                $nestedData[] =$device_type;
                $nestedData[] = $type[$r->notification_type];
                $nestedData[] = $r->title;
                $nestedData[] = strip_tags($r->message);
                $nestedData[] = '';
                $nestedData[] = get_time_format($r->schedule_time,3);
                $nestedData[] = get_time_format($r->created,3);
                $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn btn-primary dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
                            <ul class='dropdown-menu'>
                        <li><a href='" . AUTH_PANEL_URL . "bulk_messenger/push_notification/send_push_notification/" . $r->id . "'><i class='fa fa-pencil'></i> Edit</a></li>
                        <li><a href='" . AUTH_PANEL_URL . "bulk_messenger/push_notification/delete_shedule_notification/" . $r->id . "'><i class='fa fa-trash'></i> Delete</a></li>

                            </ul>
                            </div>";
                $data[] = $nestedData;
            }
        }


        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
        );
        echo json_encode($json_data); // send data as json format
    }

     public function delete_shedule_notification($id=null){
        app_permission("app_id", $this->db_read);
        $data = $this->db_read->get_where('notification_scheduler', array('id' => $id))->row_array();
        if((time()+600)<$data['schedule_time']){
             if(!empty($data)){
            app_permission("app_id", $this->db);
            secured_delete('notification_scheduler',['id'=>$id]);
            page_alert_box("success", 'Action performed.', 'Deleted successfully.');
            redirect(AUTH_PANEL_URL . "bulk_messenger/push_notification/schedule_notification_list");
        }
       }else{
        page_alert_box("error", 'Alert', "You Cann't delete notification before 10 minutes schedule time");
            redirect(AUTH_PANEL_URL . "bulk_messenger/push_notification/schedule_notification_list");

       }
     }
    
    
}
