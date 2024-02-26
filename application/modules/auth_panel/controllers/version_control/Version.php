    <?php

    defined('BASEPATH') or exit('No direct script access allowed');

    class Version extends MX_Controller
    {

        protected $redis_magic;

        function __construct()
        {
            parent::__construct();
            /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
            modules::run('auth_panel/auth_panel_ini/auth_ini');
            $this->load->library('form_validation');
            $this->load->library("security");
            $this->load->helper('aes');
            $this->load->model("Version_model");
            $this->redis_magic = new Redis_magic("session");
        }

        public function bottom_bar()
        {
            if ($this->input->post()) {
                $this->form_validation->set_rules('title', 'Title', 'required');
                $this->form_validation->set_rules('type', 'type', 'trim|required');
                if ($this->form_validation->run() == FALSE) {
                } else {
                    $insert_data = array(
                        'title' => $this->input->post('title'),
                        'type' => $this->input->post('type'),
                        'param_value' => $this->input->post('parameter') ?? 0,
                        'menu_side' => $this->input->post('menu_side'),
                        // 'app_id' => APP_ID,
                    );
                    $this->db->insert('menus', $insert_data);
                    $id = $this->db->insert_id();
                    //upload image start
                    $image = array();
                    $thumbnail;
                    if (!empty($_FILES['icon']['name'])) {
                        $allowed_image_extension = array("jpeg", "jpg", "png");
                        $file_extension = pathinfo($_FILES["icon"]["name"], PATHINFO_EXTENSION);
                        if (in_array($file_extension, $allowed_image_extension)) {
                            $thumbnail = amazon_s3_upload($_FILES['icon'], "menus/icon", $id);
                            $image['icon'] = $thumbnail;
                            $this->db->where("id", $id);
                            $this->db->set($image);
                            $this->db->update("menus");
                        }
                    }
                    update_api_version($this->db, 9);
                    backend_log_genration(
                        $this,
                        "Menu(ID : $id) has been added successfully.",
                        "Course Type Master"
                    );
                    page_alert_box('success', 'Type Added', 'Type has been added successfully');
                }
            }
            redirect(AUTH_PANEL_URL . '/version_control/version/app_configuration');
        }
        public function delete_version_review($id = "")
        {
            $id = $_GET['id'];
            //print_r($id);die;

            $status = $this->Version_model->delete_version_review($id);
            page_alert_box('success', 'Action performed', 'Bottom Bar deleted successfully');
            if ($status) {

                update_api_version($this->db, 9);
                redirect('auth_panel//version_control/version/app_configuration');
            }
        }
        
        public function app_configuration()
        {
            $menu_id = $this->input->get("menu_id");
            $parent_id = $this->input->get("parent_id");
            if ($this->input->post()) {
                $input = $this->input->post();
                $this->form_validation->set_rules("title", "Menu Title", "required|min_length[2]");
                $this->form_validation->set_rules("is_publish", "Publish", "required|trim|numeric");
                $this->form_validation->set_rules("is_visible", "Visible", "required|trim|numeric");
                if ($this->form_validation->run() == false) {
                    $error = $this->form_validation->get_all_errors();
                    page_alert_box("error", "Add Menu", array_values($error)[0]);
                    redirect_to_back();
                }
                if (isset($input['meta_info'])) {
                    $meta_data = array(
                        'meta' => array(
                            'title' => $input['info'],
                            'popup_title' => $input['popup_title'],
                            'contact_title' => $input['contact_title'],
                            'contact' => $input['mobile']
                        )
                    );
                    $input['meta_information'] = json_encode($meta_data);
                }
                if ($input['is_child'] == '0') {
                    unset($input['parent_id']);
                }
                unset($input['meta_info'], $input['info'], $input['mobile'], $input['popup_title'], $input['contact_title'], $input['is_child']);
                if (!$menu_id) {
                    $result = $this->Version_model->add_menu($input);
                } else {
                    $input['id'] = $menu_id;
                    $result = $this->Version_model->update_menu($input);
                }
                if ($result['status']) {
                    if ($_FILES['thumbnail']['name'] != '') {
                        $thumbnail_url = amazon_s3_upload($_FILES['thumbnail'], 'app_config/menu_icon', $result['menu_id']);
                        $update_menu = array(
                            "id" => $result['menu_id'],
                            "thumbnail" => $thumbnail_url
                        );
                        $this->Version_model->update_menu($update_menu);
                    }
                    //UPDATE DATA IN ELASTIC CACHE STARTED HERE...
                    $this->update_master_hit_redis();
                    //UPDATE DATA IN ELASTIC CACHE END HERE...
                    page_alert_box("success", "App Setting", $result['message']);
                    redirect(AUTH_PANEL_URL . 'version_control/version/app_configuration');
                } else {
                    page_alert_box("error", "App Setting", $result['message']);
                    redirect(AUTH_PANEL_URL . 'version_control/version/app_configuration');
                }
            } else {
                $view_data['menu_detail'] = "";
                $view_data['meta_data'] = array();
                if ($menu_id) {
                    $this->db->where("id", $menu_id);
                    $menu_detail = $this->db->get("menus")->row_array();
                    $view_data['menu_detail'] = $menu_detail;
                    $meta = json_decode($view_data['menu_detail']['meta_information'], true);
                    $view_data['meta_data'] = !empty($meta) ? $meta : array();
                }
                $view_data['page'] = "Menu";
                $view_data['page_title'] = "Add Menu";
                $view_data['breadcrum'] = array('Configuration' => "version_control/version/app_configuration", 'App Settings' => "version_control/version/app_configuration");
                $view_data['language_list'] = $this->Version_model->get_language_list();
                $data['page_data'] = $this->load->view('version/app_setting', $view_data, TRUE);
                echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
            }
        }

        public function ajax_child_list($parent)
        {
            $columns = array(
                0 => 'lang_id',
                1 => 'landing_page',
                2 => 'menu_status',
                7 => 'is_publish',
                8 => 'is_visible',
                4 => 'title'
            );
            $requestData = $_REQUEST;
            $final_list = array();
            $total_data = $total_filtered = 0;
            // app_language("m.lang_id", $this->db);
            // app_permission("m.app_id", $this->db);
            if ($parent > 0)
                $this->db->where('parent_id', $parent);
            else
                $this->db->where('parent_id', 0);

            $total_data = $this->db->count_all_results("menus m");
            $this->db->select("ifnull(l.title,'') as title,m.id,m.position,m.lang_id,m.title,m.thumbnail,menu_side,m.menu_status,m.is_publish,m.is_visible,m.created_on,m.landing_page");
            $this->db->join("languages l", " l.id = m.lang_id", "LEFT");
            $this->db->where('parent_id', $parent);
            // app_language("m.lang_id", $this->db);
            // app_permission("m.app_id", $this->db);
            $this->db->order_by('m.position', 'asc');
            if ($requestData['columns'][4]['search']['value'] != '') { //menu pagetype
                $this->db->like("m.title", $requestData['columns'][4]['search']['value']);
            }
            if ($requestData['columns'][1]['search']['value'] != '') { //menu pagetype
                $this->db->like("m.landing_page", $requestData['columns'][1]['search']['value']);
            }
            if ($requestData['columns'][0]['search']['value'] != '') { //menu language
                $this->db->where("lang_id", $requestData['columns'][0]['search']['value']);
            }
            if ($requestData['columns'][2]['search']['value'] != '') { //menu status
                $status_value = $requestData['columns'][2]['search']['value'];
                if ($status_value == 2) {
                    $f_status = '0';
                } else {
                    $f_status = $status_value;
                }
                $this->db->where("m.menu_status", $f_status);
            }
            if ($requestData['columns'][7]['search']['value'] != '') { //is_publish
                $publish_value = $requestData['columns'][7]['search']['value'];
                if ($publish_value == 2) {
                    $publish_status = '0';
                } else {
                    $publish_status = $publish_value;
                }
                $this->db->where("m.is_publish", $publish_status);
            }
            if ($requestData['columns'][8]['search']['value'] != '') { //is_visible
                $visible_value = $requestData['columns'][8]['search']['value'];
                if ($visible_value == 2) {
                    $visible_value = '0';
                } else {
                    $visible_value = $publish_value;
                }
                $this->db->where("m.is_visible", $visible_value);
            }
            $total_filtered = $this->db->get("menus m")->num_rows();
            $sql = $this->db->last_query();
            $sql .= " LIMIT {$requestData['start']},{$requestData['length']}";
            $menu_list = $this->db->query($sql)->result_array();
            if ($menu_list) {
                $row = 0;
                foreach ($menu_list as $menu) {
                    $nestedData = array();
                    $nestedData[] = ++$requestData['start'];
                    $nestedData[] = $menu['title'];
                    $this->db->select('title');
                    $this->db->where('id', $menu['lang_id']);
                    $language = $this->db->get("languages")->row_array();
                    $nestedData[] = $language['title'] ?? '';
                    $nestedData[] = 'No';
                    $nestedData[] = menu_side($menu['menu_side']);
                    $nestedData[] = $menu['landing_page'];
                    $menu_status = $menu['menu_status'] == 0 ? "Enabled" : "Disabled";
                    $nestedData[] = $menu['is_publish'] == 0 ? "<a class='btn-xs bold btn-success' title='Publish'>Publish</a>" : "<a class='btn-xs bold btn-danger' title='Comming soon'>Comming soon</a>";
                    $nestedData[] = $menu['is_visible'] == 0 ? "<a class='btn-xs bold btn-success' title='Publish'>Yes</a>" : "<a class='btn-xs bold btn-danger' title='Comming soon'>No</a>";
                    $nestedData[] = $menu_status;
                    if ($menu['menu_status'] == 1) {
                        $change_status = "0";
                        $menu_status = "<i class='fa fa-unlock'></i> Enable";
                    } else {
                        $change_status = "1";
                        $menu_status = "<i class='fa fa-lock'></i> Disable";
                    }
                    $action = "<div class='dropdown toggle_menus_icons'> <button class='btn btn-primary dropdown-toggle' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
                    <ul class='dropdown-menu'>
                        <li><a class='' title='" . (($menu['menu_status']) ? "Enable" : "Disable") . "' href='" . AUTH_PANEL_URL . "version_control/version/change_status?menu_id=" . $menu['id'] . "&status=" . $change_status . "' >" . $menu_status . "</a></li>
                        <li><a class='' title='Update' href='" . AUTH_PANEL_URL . "version_control/version/app_configuration?menu_id=" . $menu['id'] . "' ><i class='fa fa-edit'></i> Edit </a></li>
                        <li><a class='' title='App Screen Ordering' href='" . AUTH_PANEL_URL . "version_control/version/home_configuration?menu_id=" . $menu['id'] . "' ><i class='fa fa-map-marker' aria-hidden='true'></i> App Screen Ordering</a></li>
                          
                    </ul>
                    </div>";
                    $nestedData[] = $action;
                    $final_list[] = $nestedData;
                }
            }
            $json_data = array(
                "draw" => intval($requestData['draw']),
                // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($total_data),
                // total number of records
                "recordsFiltered" => intval($total_filtered),
                // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $final_list // total data array
            );
            echo json_encode($json_data);
            die;
        }

        public function all_menus()
        {
            $response = array("status" => false, "result" => "Invalid Request!");
            if ($this->input->is_ajax_request()) {
                if (array_key_exists("menu_side", $this->input->post()) && $this->input->post('menu_side') != '') {
                    $this->db->where('menu_side', $this->input->post('menu_side'));
                    $this->db->where('parent_id', 0);
                }
                // app_permission('app_id', $this->db);
                // app_language("lang_id", $this->db);
                $this->db->order_by('position', 'asc');
                $result = $this->db->where('menu_status', 0)->get('menus')->result();
                if ($result) {
                    $response['status'] = true;
                    $response['result'] = $result;
                } else {
                    $response['result'] = "No Data Found!";
                }
            }
            echo json_encode($response);
            die;
        }


        public function all_menus_child($parent_id)
        {
            $response = array("status" => false, "result" => "Invalid Request!");
            if ($this->input->is_ajax_request()) {
                if (array_key_exists("menu_side", $this->input->post()) && $this->input->post('menu_side') != '') {
                    $this->db->where('menu_side', $this->input->post('menu_side'));
                }
                // app_permission('app_id', $this->db);
                // app_language("lang_id", $this->db);
                if ($parent_id > 0)
                    $this->db->where('parent_id', $parent_id);
                else
                    $this->db->where('parent_id', 0);

                $this->db->order_by('position', 'asc');
                $result = $this->db->get('menus')->result();
                if ($result) {
                    $response['status'] = true;
                    $response['result'] = $result;
                } else {
                    $response['result'] = "No Data Found!";
                }
            }
            echo json_encode($response);
            die;
        }

        public function save_menu_position()
        {
            $ids = $_POST['ids'];
            $counter = 1;
            foreach ($ids as $id) {
                $this->db->where('id', $id);
                $array = array('position' => $counter);
                $this->db->update('menus', $array);
                $counter++;
            }
            //UPDATE DATA IN ELASTIC CACHE STARTED HERE...
            $this->update_master_hit_redis();
            //UPDATE DATA IN ELASTIC CACHE END HERE...
            echo json_encode(array("status" => true, 'message' => "Position Saved"));
            die;
        }

        public function setPosition()
        {
            $sr = 0;
            foreach ($this->input->post('id') as $id) {
                $this->db->update('menus', ['position' => $sr], ['id' => $id]);
                $sr++;
            }
            echo json_encode(['status' => true]);
            die;
        }
        public function versioning()
        {
            if ($this->input->post()) {

                $backend_user_id = $this->session->userdata("active_backend_user_id");
                $input_data = $this->input->post();
                $insert_data = array(
                    "platform" => $input_data['device_type'],
                    "version" => $input_data['version'],
                    "created_by" => $backend_user_id,
                    "created_at" => time(),
                    "status" => 0,
                );
                $this->db->insert("version_control", $insert_data);
            }

            $this->db->select("vc.*");
            $view_data['versions'] = $this->db->get("version_control vc")->result();
            $data['page_data'] = $this->load->view('version/version_view', $view_data, TRUE);
            echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
        }

        private function exe_validate($device_type, $url)
        {
            $allowed_ext = array(1 => "apk", 2 => "ipa", 3 => "exe");
            $ext = explode(".", $url);
            if (end($ext) != $allowed_ext[$device_type]) {
                echo json_encode(array("status" => false, "error" => "file should have " . $allowed_ext[$device_type] . " extention for given platform(" . device_type($device_type) . ") "));
                die;
            }
        }

        public function ajax_menu_list()
        {
            $columns = array(
                0 => 'lang_id',
                1 => 'landing_page',
                2 => 'menu_status',
                7 => 'is_publish',
                8 => 'is_visible',
                4 => 'title'
            );
            $requestData = $_REQUEST;
            $final_list = array();
            $total_data = $total_filtered = 0;
            $where['m.menu_side'] = $this->input->post('menu_side');
            $where['m.parent_id'] = '0';
            // app_language("m.lang_id", $this->db);
            // app_permission("m.app_id", $this->db);
            $this->db->where($where);
            $total_data = $this->db->count_all_results("menus m");
            $this->db->select("ifnull(l.title,'') as title,m.id,m.position,m.parent_id,m.lang_id,m.title,m.thumbnail,menu_side,m.menu_status,m.is_publish,m.is_visible,m.created_on,m.landing_page");
            $this->db->join("languages l", " l.id = m.lang_id", "LEFT");
            $this->db->where($where);
            // app_language("m.lang_id", $this->db);
            // app_permission("m.app_id", $this->db);
            $this->db->order_by('m.position', 'asc');
            if ($requestData['columns'][4]['search']['value'] != '') { //menu pagetype
                $this->db->like("m.title", $requestData['columns'][4]['search']['value']);
            }
            if ($requestData['columns'][1]['search']['value'] != '') { //menu pagetype
                $this->db->like("m.landing_page", $requestData['columns'][1]['search']['value']);
            }
            if ($requestData['columns'][0]['search']['value'] != '') { //menu language
                $this->db->where("lang_id", $requestData['columns'][0]['search']['value']);
            }
            if ($requestData['columns'][2]['search']['value'] != '') { //menu status
                $status_value = $requestData['columns'][2]['search']['value'];
                if ($status_value == 2) {
                    $f_status = '0';
                } else {
                    $f_status = $status_value;
                }
                $this->db->where("m.menu_status", $f_status);
            }
            if ($requestData['columns'][7]['search']['value'] != '') { //is_publish
                $publish_value = $requestData['columns'][7]['search']['value'];
                if ($publish_value == 2) {
                    $publish_status = '0';
                } else {
                    $publish_status = $publish_value;
                }
                $this->db->where("m.is_publish", $publish_status);
            }
            if ($requestData['columns'][8]['search']['value'] != '') { //is_visible
                $visible_value = $requestData['columns'][8]['search']['value'];
                if ($visible_value == 2) {
                    $visible_value = '0';
                } else {
                    $visible_value = $publish_value;
                }
                $this->db->where("m.is_visible", $visible_value);
            }
            $total_filtered = $this->db->get("menus m")->num_rows();
            $sql = $this->db->last_query();
            $sql .= " LIMIT {$requestData['start']},{$requestData['length']}";
            $menu_list = $this->db->query($sql)->result_array();
            if ($menu_list) {
                $row = 0;
                foreach ($menu_list as $menu) {
                    $nestedData = array();
                    $nestedData[] = ++$requestData['start'];
                    $nestedData[] = $menu['title'];
                    $this->db->select('title');
                    $this->db->where('id', $menu['lang_id']);
                    $language = $this->db->get("languages")->row_array();

                    $nestedData[] = $language['title'] ?? '';

                    $this->db->select('count(*) as count');
                    $this->db->where('parent_id', $menu['id']);
                    $menu_count = $this->db->get("menus")->row_array();
                    if ($menu_count['count'] > 0) {
                        $parent = 'Yes';
                    } else {
                        $parent = 'No';
                    }
                    $nestedData[] = $parent;
                    $nestedData[] = menu_side($menu['menu_side']);
                    $nestedData[] = $menu['landing_page'];
                    $menu_status = $menu['menu_status'] == 0 ? "Enabled" : "Disabled";
                    $nestedData[] = $menu['is_publish'] == 0 ? "<a class='btn-xs bold btn-success' title='Publish'>Publish</a>" : "<a class='btn-xs bold btn-danger' title='Comming soon'>Comming soon</a>";
                    $nestedData[] = $menu['is_visible'] == 0 ? "<a class='btn-xs bold btn-success' title='Publish'>Yes</a>" : "<a class='btn-xs bold btn-danger' title='Comming soon'>No</a>";
                    $nestedData[] = $menu_status;
                    if ($menu['menu_status'] == 1) {
                        $change_status = "0";
                        $menu_status = "<i class='fa fa-unlock'></i> Enable";
                    } else {
                        $change_status = "1";
                        $menu_status = "<i class='fa fa-lock'></i> Disable";
                    }
                    if ($menu_count['count'] > 0) {
                        $action = "<div class='dropdown toggle_menus_icons'> <button class='btn btn-primary dropdown-toggle' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
                    <ul class='dropdown-menu'>
                        <li><a class='' title='" . (($menu['menu_status']) ? "Enable" : "Disable") . "' href='" . AUTH_PANEL_URL . "version_control/version/change_status?menu_id=" . $menu['id'] . "&status=" . $change_status . "' >" . $menu_status . "</a></li>
                        <li><a class='' title='Update' href='" . AUTH_PANEL_URL . "version_control/version/app_configuration?menu_id=" . $menu['id'] . "' ><i class='fa fa-edit'></i> Edit </a></li>
                        <li><a class='' title='App Screen Ordering' href='" . AUTH_PANEL_URL . "version_control/version/home_configuration?menu_id=" . $menu['id'] . "' ><i class='fa fa-map-marker' aria-hidden='true'></i> App Screen Ordering</a></li>
                        <li><a class='' title='Child List' href='" . AUTH_PANEL_URL . "version_control/version/app_configuration?parent_id=" . $menu['id'] . "&menu_side=" . $menu['menu_side'] . "' ><i class='fa fa-sitemap' aria-hidden='true'></i> Child List</a></li>    
                    </ul>
                    </div>";
                    } else {
                        $action = "<div class='dropdown toggle_menus_icons'> <button class='btn btn-primary dropdown-toggle' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
                    <ul class='dropdown-menu'>
                        <li><a class='' title='" . (($menu['menu_status']) ? "Enable" : "Disable") . "' href='" . AUTH_PANEL_URL . "version_control/version/change_status?menu_id=" . $menu['id'] . "&status=" . $change_status . "' >" . $menu_status . "</a></li>
                        <li><a class='' title='Update' href='" . AUTH_PANEL_URL . "version_control/version/app_configuration?menu_id=" . $menu['id'] . "' ><i class='fa fa-edit'></i> Edit </a></li>
                        <li><a class='' title='App Screen Ordering' href='" . AUTH_PANEL_URL . "version_control/version/home_configuration?menu_id=" . $menu['id'] . "' ><i class='fa fa-map-marker' aria-hidden='true'></i> App Screen Ordering</a></li>
                          
                    </ul>
                    </div>";
                    }
                    $nestedData[] = $action;
                    $final_list[] = $nestedData;
                }
            }
            $json_data = array(
                "draw" => intval($requestData['draw']),
                // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($total_data),
                // total number of records
                "recordsFiltered" => intval($total_filtered),
                // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $final_list // total data array
            );
            echo json_encode($json_data);
            die;
        }

        public function ajax_release_list()
        {
            $requestData = $this->security->xss_clean($_REQUEST);

            $columns = array(
                // datatable column index  => database column name
                0 => 'platform',
                2 => 'version',
                3 => 'note',
                4 => 'created_by',
                5 => 'status',
                6 => 'created',
            );
            $where_arr = array();
            $this->db->join("backend_user bu", "bu.id = vrm.created_by", "LEFT");
            $totalData = $this->db->count_all_results("version_control vrm");
            $totalFiltered = $totalData;

            $this->db->select("bu.username,vrm.*");

            if (!empty($requestData['columns'][0]['search']['value'])) {   //name
                $where_arr['vrm.platform'] = $requestData['columns'][0]['search']['value'];
            }
            if (!empty($requestData['columns'][2]['search']['value'])) {   //name
                $where_arr['vrm.version'] = $requestData['columns'][2]['search']['value'];
            }
            if (!empty($requestData['columns'][4]['search']['value'])) {   //name
                $where_arr['bu.username LIKE'] = $requestData['columns'][4]['search']['value'] . '%';
            }

            $this->db->join("backend_user bu", "bu.id = vrm.created_at", "LEFT");
            $this->db->where($where_arr);
            $this->db->order_by($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir']);
            $this->db->limit($requestData['length'], $requestData['start']);
            $result = $this->db->get("version_control vrm")->result();

            $this->db->join("backend_user bu", "bu.id = vrm.created_by", "LEFT");
            $this->db->where($where_arr);
            $totalFiltered = $this->db->count_all_results("version_control vrm");

            $data = array();

            foreach ($result as $r) {  // preparing an array
                $nestedData = array();

                $nestedData[] = device_type($r->platform);
                // $nestedData[] = $r->url ? $r->url : "--NA--";
                $nestedData[] = $r->version ? $r->version : "--NA--";
                $nestedData[] = $r->note;
                $nestedData[] = $r->username;
                $nestedData[] = ($r->status) ? '<span class="text-success">Active</span>' : '<span class="text-danger">In-Active</span>';
                $status_cls = ($r->status) ? 'btn-success' : 'btn-warning';
                $status_link = $r->status != '1' ? '<a href="' . AUTH_PANEL_URL . 'version_control/version/update_release_status?id=' . $r->id . '" class="btn-xs btn-success">Activate</a>' : '<span class="text-success">Activated</span>';
                $nestedData[] = get_time_format($r->created);
                $nestedData[] = $status_link;
                $data[] = $nestedData;
            }

            $json_data = array(
                "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData), // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            $json_data = json_encode($json_data);
            echo s3_to_cf($json_data);
        }

        public function update_release_status()
        {
            $release_id = $this->input->get("id");
            if (is_numeric($release_id)) {
                app_permission("app_id", $this->db);
                $this->db->where("id", $release_id);
                $this->db->set("status", '1');
                $this->db->update("v_release_mgmt");
                if ($this->db->affected_rows() > 0) {
                    $platform = $this->db->where("id", $release_id)->get("v_release_mgmt")->row_array();

                    $updateVersion = $this->Version_model->update_version($platform);
                    //                app_permission("app_id",$this->db);
                    //                $this->db->where("platform", $platform['platform']);
                    //                $this->db->set("app_url", $platform['url']);
                    //                $this->db->update("version_control");

                    app_permission("app_id", $this->db);
                    $this->db->where("id !=", $release_id);
                    $this->db->where("platform", $platform['platform']);
                    $this->db->set("status", '0');
                    $this->db->update("v_release_mgmt");

                    backend_log_genration($this, "Release status has been updated successfully", "Update Release Status");
                    page_alert_box("success", "Update Release Status", "Release status has been updated successfully");
                } else {
                    page_alert_box("error", "Update Release Status", "Something went wrong.");
                }
                redirect_to_back();
            }
        }

        public function cache_management()
        {
            if ($this->input->post()) {
                $this->redis_magic->SET("ES_UT_009", $this->input->post('ES_UT_009'));
            }

            $view_data['versions'] = $this->redis_magic->GET("ES_UT_009") ? $this->redis_magic->GET("ES_UT_009") : 0;
            $view_data['breadcrum'] = array('Cache Management' => "#");
            $data['page_data'] = $this->load->view('version/cache_management', $view_data, TRUE);
            echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
        }

        function configuration()
        {

            $view_data['info'] = new stdClass();
            $view_data['info']->slider_interval = get_db_meta_key($this->db, "slider_interval");
            $view_data['info']->ceo_message = json_decode(get_db_meta_key($this->db, "ceo_message"), true);
            $view_data['info']->contact_us = json_decode(get_db_meta_key($this->db, "CONCAT_US"), true);
            $view_data['info']->payment_gateways = explode(",", get_db_meta_key($this->db, "GLOBAL_PAYMENT_GATEWAYS"));
            $view_data['info']->rzp_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "RZP_DETAIL"), ''), true);
            $view_data['info']->payu_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "PAYU_DETAIL"), ''), true);
            $view_data['info']->easebuzz_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "EASEBUZZ_DETAIL"), ''), true);
            $view_data['info']->ccavenue_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "CCAVENUE_DETAIL"), ''), true);
            $view_data['info']->payubiz_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "PAYUBIZ_DETAIL"), ''), true);

            $view_data['info']->instamojo_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "INSTAMOJO_DETAIL"), ''), true);
            $view_data['info']->paytm_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "PAYTM_DETAIL"), ''), true);
            $view_data['info']->email_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "EMAIL_DETAIL"), ''), true);
            $view_data['info']->firebase_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "FIREBASE_DETAIL"), ''), true);
            $view_data['info']->gsm_key = json_decode(get_db_meta_key($this->db, "GSM_KEY"), true);
            $view_data['info']->deep = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "DEEPLINKING_DETAIL"), ''), true);
            // $view_data['info']->deep = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "deep"),''), true);
            $view_data['info']->vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"), ''), true);
            $view_data['info']->FIREBASE_API_KEY = json_decode(get_db_meta_key($this->db, "FIREBASE_API_KEY"), true);
            $view_data['info']->s3bucket_detail = json_decode(get_db_meta_key($this->db, "s3bucket_detail"), true);
            $view_data['info']->zoom_detail = json_decode(get_db_meta_key($this->db, "zoom_detail"), true);
            $view_data['info']->CASH_FREE_DETAIL = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "CASH_FREE_DETAIL"), ''), true);
            $view_data['info']->GOOGLE_DETAIL = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "GOOGLE_DETAIL"), ''), true);
            $view_data['info']->SOCIAL_MEDIA = json_decode(get_db_meta_key($this->db, "SOCIAL_MEDIA"), true);
        
            $meta = get_db_meta_key($this->db, "maintenance_break");
            $view_data['info']->break_from = explode("#", $meta)[0] ?? "";
            $view_data['info']->break_to = explode("#", $meta)[1] ?? "";

            // $this->db->order_by("position", "asc");
            // $this->db->where('course_id', -1);
            // $view_data['faq'] = $this->db->get('course_faq_master')->result_array();
            // $view_data['socials'] = $this->db->get('link_share')->row_array();

            //print_r($view_data['socials']);die;
            $view_data['breadcrum'] = array('configuration' => "#");
            $data['page_data'] = $this->load->view('version/configuration', $view_data, TRUE);
            echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
        }

        public function usd_conversion()
        {
            if ($this->input->post()) {
                // $this->db->where('app_id', APP_ID);
                $this->db->where('meta_name', 'usd_conversion');
                $check = $this->db->get('meta_information')->row_array();

                if (empty($check)) {
                    $data = [

                        'meta_value' => $this->input->post('usd_conversion'),
                        'meta_name' => 'usd_conversion',
                        // 'app_id' => APP_ID,
                    ];

                    $this->db->insert('meta_information', $data);
                } else {
                    // $this->db->where('app_id', APP_ID);
                    $this->db->where('meta_name', 'usd_conversion');
                    $this->db->update('meta_information', ['meta_value' => $this->input->post('usd_conversion')]);
                }

                page_alert_box('success', 'USD Converstion', 'USD Conversion been added successfully');
            }

            // $this->db->where('app_id', APP_ID);
            $this->db->where('meta_name', 'usd_conversion');
            $view_data['result'] = $this->db->get('meta_information')->row_array();

            $view_data['breadcrum'] = array('configuration' => "#");
            $data['page_data'] = $this->load->view('version/usd_conversion', $view_data, TRUE);
            echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
        }

        function terms_and_policy()
        {
            $view_data['info']['terms'] = get_db_meta_key($this->db, "TERMS");
            $view_data['info']['policy'] = get_db_meta_key($this->db, "POLICY");
            $view_data['info']['refund_policy'] = get_db_meta_key($this->db, "REFUND_POLICY");
            $view_data['info']['about_us'] = get_db_meta_key($this->db, "ABOUT_US");
            $view_data['info']['contact_us'] = get_db_meta_key($this->db, "CONTACT_US");
            $view_data['info']['packages'] = get_db_meta_key($this->db, "PACKAGES");
            $view_data['info']['footer_detail'] = get_db_meta_key($this->db, "FOOTER_DETAIL");
            $view_data['breadcrum'] = array('Terms And Conditions' => "#");
            $view_data['faq'] = $this->db->get('course_faq_master')->result_array();

            app_permission("app_id", $this->db);
            $this->db->select("functionality");
            $this->db->where("status", 1);
            $f_list = $this->db->get("application_meta")->result_array(); //pre($f_list);die;
            $view_data['f_lists'] = json_decode($f_list[0]['functionality']);
            $data['page_data'] = $this->load->view('version/terms_and_policy', $view_data, TRUE);
            echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
        }

        function set_maintenance_break()
        {
            $array = array(
                "break_from" => $this->input->post("break_from") ? strtotime($this->input->post("break_from")) : 0,
                "break_to" => $this->input->post("break_to") ? strtotime($this->input->post("break_to")) : 0
            );
            set_db_meta_key($this->db, "maintenance_break", implode("#", $array));
            backend_log_genration($this, 'Maintenance Break-: ' . implode("#", $array), 'maintenance_break');
            page_alert_box("success", "Configuration", "Configuration Saved Successfully");
            redirect_to_back();
        }

        function set_ceo_message()
        {
            $ceo_message = json_encode(array(
                "ceo_message_english" => $this->input->post("ceo_message_english"),
                "ceo_message_hindi" => $this->input->post("ceo_message_hindi")
            ));
            // var_dump($ceo_message); die;
            set_db_meta_key($this->db, "ceo_message", $ceo_message);
            update_api_version($this->db, 11);
            backend_log_genration($this, 'CEO Message Changed', 'CEO MESSAGE');
            page_alert_box("success", "Configuration", "Configuration Saved Successfully");
            redirect_to_back();
        }

        function set_rzp_detail()
        {

            $any_detail = json_encode($this->input->post());
            // print_r($any_detail);die;
            $meta_name = $this->input->post("meta_name");
            if ($meta_name == "FIREBASE_DETAIL") {
                if ($_FILES && !empty($_FILES["service_account_file"]["name"])) {
                    $target = 'uploads/service_account_file/' . APP_ID . '_' . basename($_FILES["service_account_file"]["name"]);
                    $google_json_file = move_uploaded_file($_FILES["service_account_file"]["tmp_name"], $target);
                    chmod($target, 0755);
                    $any_detail = json_decode($any_detail, true);
                    $any_detail['service_account_file'] = $target;
                    $any_detail = json_encode($any_detail);
                }
                set_db_meta_key($this->db, "FIREBASE_DETAIL", aes_cbc_encryption($any_detail, ''));
                $gsm_key = $this->input->post("gsm_key");
                if ($gsm_key != '') {

                    $gsm_arr = json_encode(array("GSM_KEY" => $gsm_key, "FIREBASE_API_KEY" => $this->input->post("FIREBASE_API_KEY")));
                    set_db_meta_key($this->db, "GSM_KEY", $gsm_arr);
                }
            } else if ($meta_name == "vc_key") {
                set_db_meta_key($this->db, "vc_key", aes_cbc_encryption($any_detail, ''));
            } else if ($meta_name == "DEEPLINKING_DETAIL") {
                set_db_meta_key($this->db, "DEEPLINKING_DETAIL", aes_cbc_encryption($any_detail, ''));
            } else if ($meta_name == "EMAIL_DETAIL") {
                set_db_meta_key($this->db, "EMAIL_DETAIL", aes_cbc_encryption($any_detail, ''));
            } else if ($meta_name == "PAYTM_DETAIL") {
                set_db_meta_key($this->db, "PAYTM_DETAIL", aes_cbc_encryption($any_detail, ''));
            } else if ($meta_name == "INSTAMOJO_DETAIL") {
                set_db_meta_key($this->db, "INSTAMOJO_DETAIL", aes_cbc_encryption($any_detail, ''));
            } else if ($meta_name == "PAYUBIZ_DETAIL") {
                set_db_meta_key($this->db, "PAYUBIZ_DETAIL", aes_cbc_encryption($any_detail, ''));
            } else if ($meta_name == "CCAVENUE_DETAIL") {
                set_db_meta_key($this->db, "CCAVENUE_DETAIL", aes_cbc_encryption($any_detail, ''));
            } else if ($meta_name == "PAYU_DETAIL") {
                set_db_meta_key($this->db, "PAYU_DETAIL", aes_cbc_encryption($any_detail, ''));
            } else if ($meta_name == "EASEBUZZ_DETAIL") {
                set_db_meta_key($this->db, "EASEBUZZ_DETAIL", aes_cbc_encryption($any_detail, ''));
            } else if ($meta_name == "RZP_DETAIL") {
                // print_r($any_detail);die;
                set_db_meta_key($this->db, "RZP_DETAIL", aes_cbc_encryption($any_detail, ''));
            } else if ($meta_name == "CASH_FREE_DETAIL") {
                set_db_meta_key($this->db, "CASH_FREE_DETAIL", aes_cbc_encryption($any_detail, ''));
            } else if ($meta_name == "GOOGLE_DETAIL") {
                // print_r($any_detail);die;
                set_db_meta_key($this->db, "GOOGLE_DETAIL", aes_cbc_encryption($any_detail, ''));
            }

            // echo $this->db->last_query();die;
            backend_log_genration($this, 'Gateway Detail Changed', 'Gateway Detail');
            page_alert_box("success", "Configuration", "Configuration Saved Successfully");
            redirect(AUTH_PANEL_URL . "/version_control/version/configuration");
        }
        function set_footer()
        {
            // set_db_meta_key($this->db, "TERMS", $this->input->post("terms"));
            $any_detail = json_encode($this->input->post());
            // set_db_meta_key($this->db, "FOOTER_DETAIL", aes_cbc_encryption($any_detail, ''));
            set_db_meta_key($this->db, "FOOTER_DETAIL", $any_detail);
            backend_log_genration($this, 'Footer Details Changed', 'Footer Details');
            page_alert_box("success", "Configuration", "Configuration Saved Successfully");
            redirect_to_back();
        }
        //function fro s3 bucket
        function set_s3_bucket()
        {
            $s3_bucket_name = json_encode(array(
                "secret_key" => $this->input->post("secret_key"),
                "access_key" => $this->input->post("access_key"),
                "bucket_key" => $this->input->post("bucket_key"),
                "cloudfront" => $this->input->post("cloudfront"),
                "region" => $this->input->post("region"),
                "congnito_id" => $this->input->post("congnito_id"),
            ));
            // var_dump($ceo_message); die;
            set_db_meta_key($this->db, "s3bucket_detail", $s3_bucket_name);
            update_api_version($this->db, 11);
            backend_log_genration($this, 's3 bucket detail Changed', 's3 bucket detail MESSAGE');
            page_alert_box("success", "s3 bucket", "s3 bucket detail Saved Successfully");
            redirect_to_back();
        }
        //function for Zoom
        function set_zoom()
        {
            $set_zoom = json_encode(array(
                "secret_key" => $this->input->post("secret_key"),
                "access_key" => $this->input->post("access_key"),
                "Zoom_email_id" => $this->input->post("zoom_email_id"),
            ));
            set_db_meta_key($this->db, "zoom_detail", $set_zoom);
            update_api_version($this->db, 11);
            backend_log_genration($this, 'Zoom detail Changed', 'Zoom detail MESSAGE');
            page_alert_box("success", "Zoom detail", "Zoom detail Saved Successfully");
            redirect_to_back();
        }
        //function for cashfree
        function set_cashfree()
        {
            $set_cashfree = json_encode(array(
                "secret_key" => $this->input->post("secret_key"),
                "api_id" => $this->input->post("api_id"),
                "mode" => $this->input->post("mode"),
            ));
            // var_dump($set_cashfree); die;
            set_db_meta_key($this->db, "cashfree_data", $set_cashfree);
            update_api_version($this->db, 11);
            backend_log_genration($this, 'cashfree  detail Changed', 'cashfree detail MESSAGE');
            page_alert_box("success", "cashfree", "cashfree detail Saved Successfully");
            redirect_to_back();
        }


        function set_terms()
        {
            set_db_meta_key($this->db, "TERMS", $this->input->post("terms"));
            backend_log_genration($this, 'Terms and Condition Changed', 'Terms and Condition');
            page_alert_box("success", "Configuration", "Configuration Saved Successfully");
            redirect_to_back();
        }

        function set_policy()
        {
            set_db_meta_key($this->db, "POLICY", $this->input->post("policy"));
            backend_log_genration($this, 'Policy Changed', 'Privacy Policy');
            page_alert_box("success", "Configuration", "Configuration Saved Successfully");
            redirect_to_back();
        }

        function set_refund_policy()
        {
            set_db_meta_key($this->db, "REFUND_POLICY", $this->input->post("refund_policy"));
            backend_log_genration($this, 'Contact us Changed', 'CONCAT_US');
            page_alert_box("success", "Configuration", "Configuration Saved Successfully");
            redirect_to_back();
        }
        function set_packages()
        {
            set_db_meta_key($this->db, "PACKAGES", $this->input->post("packages"));
            backend_log_genration($this, 'Packages Changed', 'PACKAGES');
            page_alert_box("success", "Configuration", "Packages Saved Successfully");
            redirect_to_back();
        }

        function set_about_us()
        {
            set_db_meta_key($this->db, "ABOUT_US", $this->input->post("about_us"));
            backend_log_genration($this, 'Contact us Changed', 'CONCAT_US');
            page_alert_box("success", "Configuration", "Configuration Saved Successfully");
            redirect_to_back();
        }

        function set_contact_us()
        {
            set_db_meta_key($this->db, "CONTACT_US", $this->input->post("contact_us"));
            backend_log_genration($this, 'Contact us Changed', 'CONCAT_US');
            page_alert_box("success", "Configuration", "Configuration Saved Successfully");
            redirect_to_back();
        }

        function set_payment_gateways()
        {
            $ids = "";
            if ($this->input->post("payment_gateways")) {
                $ids = implode(",", $this->input->post("payment_gateways"));
            }
            set_db_meta_key($this->db, "GLOBAL_PAYMENT_GATEWAYS", $ids);
            backend_log_genration($this, 'Payment Gateway Changed To: ' . $ids, 'GLOBAL_PAYMENT_GATEWAYS');
            page_alert_box("success", "Configuration", "Configuration Saved Successfully");
            redirect_to_back();
        }

        function update_version()
        {
            $input_data = $this->security->xss_clean($this->input->post());
            if (!empty($input_data)) {
                $this->form_validation->set_rules("id", "Device Type", "required|trim|is_natural_no_zero");
                $this->form_validation->set_rules("version", "Device Version", "required|trim");
                $this->form_validation->set_rules("min_version", "Device Minimum Version", "required|trim");
                $this->form_validation->set_rules("force_update", "Force Update", "required|trim");
                $this->form_validation->run();

                $errors = $this->form_validation->get_all_errors();
                if (!empty($errors)) {
                    page_alert_box("error", "Update Version", array_values($errors)[0]);
                    echo json_encode(array("status" => 0, "message" => array_values($errors)[0]));
                    die;
                }
                $input = $this->input->post();
                $input['platform'] = $this->db->where("id", $this->input->post('id'))->get("version_control")->row()->platform;

                // $device = device_type($this->input->post('id'));
                $result = $this->Version_model->update_version($input);
                if ($result) {
                    $this->redis_magic->EXPIRE("MASTER_VERSION_" . APP_ID, 0);
                    page_alert_box("success", "Update Version", " version has been updated successfully.");
                    backend_log_genration($this, "Update Version", "Version Control", $this->input->post());
                    echo json_encode(array("status" => 1, "message" => " version has been updated successfully."));
                    die;
                } else {
                    page_alert_box("error", "Update Version", "Somthing went worng!");
                    echo json_encode(array("status" => 0, "message" => "Somthing went worng!"));
                    die;
                }
            }
        }
        public function watsapp_link()
        {
            if ($this->input->post()) {

                $old = $this->db->get('link_share')->row_array();



                $insert_data = array(
                    "whatsapp_link" => $this->input->post('whatsapp_link'),
                    "telegram_link" => $this->input->post('telegram_link'),
                    // "app_id" =>  (defined("APP_ID") ? "" . APP_ID . "" : "0")
                );
                //pre($insert_data);die;
                if (empty($old)) {
                    if (!empty($insert_data)) {
                        $this->db->insert('link_share', $insert_data);
                    }
                } else {
                    if (!empty($insert_data)) {
                        // $this->db->where('app_id', APP_ID);
                        $this->db->update('link_share', $insert_data);
                    }
                }

                redirect_to_back();
            }
        }
        
    public function social_link() {
        $data = json_encode(array(
            "facebook_link" => $this->input->post("facebook_link"),
            "instagram_link" => $this->input->post("instagram_link"),
            "youtube_link" => $this->input->post("youtube_link"),
            "twitter_link" => $this->input->post("twitter_link"),
            "telegram_link" => $this->input->post("telegram_link"),
        ));       
        set_db_meta_key($this->db, "SOCIAL_MEDIA", $data);        
        page_alert_box("success", "Configuration", "Social Media  Saved Successfully");
        redirect_to_back();
    }

    }
