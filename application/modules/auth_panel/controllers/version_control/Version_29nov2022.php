    <?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Version extends MX_Controller {

    protected $redis_magic;

    function __construct() {
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
  
    public function bottom_bar() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('type', 'type', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $insert_data = array(
                    'title' => $this->input->post('title'),
                    'type' => $this->input->post('type'),
                    'param_value' => $this->input->post('parameter')??0,
                    'menu_side' => $this->input->post('menu_side'),
                    'app_id' => APP_ID,
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
        redirect(AUTH_PANEL_URL .'/version_control/version/app_configuration');
    }
    public function delete_version_review($id="") {
        $id = $_GET['id']; 
        //print_r($id);die;

        $status = $this->Version_model->delete_version_review($id);
        page_alert_box('success', 'Action performed', 'Bottom Bar deleted successfully');
        if ($status) {

            update_api_version($this->db, 9);
            redirect('auth_panel//version_control/version/app_configuration');
        }
    }

    public function app_configuration(){
        $input = $this->input->post();
        $view_data = array(
           "result" => array(),
           "bottom_array"=>array("home","library","live_class","live_test","courses","batch_course","top_banner","top_layout","downloads","create_test","notification","floatingActionButtonLayer3","is_paid_course","is_free_course","is_purchase_toolbar","filter","layout_type","bottom_banner","invoice_tnc","contact_us","poll_management","attachment","seek_bar","audio","doubt"), 
           "left_array"=>array("downloads","coursetransfer","coupon","usagehistory","purchasehistory","invitefriends","contactus","settings","logout","create_test","hide_offer_price","custom_payment","book_store","feedback","user_support","current_affair","youtube_link",'instagram_link',"facebook_link","linkedin_link","twitter_link","telegram_link","book_mark","faq","reward","suggestion","complaint","bookmark","pdfDownload"),
           "extra_array"=>array("banner_height"=>"text","banner_height_bottom"=>"text","book_store_link"=>"text","youtube_link_data"=>"text","instagram_link_data"=>"text","facebook_link_data"=>"text","linkedin_link_data"=>"text","twitter_link_data"=>"text","telegram_link_data"=>"text","book_store"=>"text","master_category_style"=>"text","mobile_number"=>"text","custom_course"=>"text","coupon"=>"checkbox","google_login"=>"checkbox","contactus_email"=>"text","contactus_mobile1"=>"text","contactus_mobile2"=>"text","libmedia_key"=>"text","main_category_image"=>"checkbox","email_show" => "checkbox","greeting" => "checkbox","country"=>"checkbox","date_of_birth"=>"checkbox","is_expert"=>"checkbox","course_delete"=>"checkbox","share_content"=>"checkbox","disable_name_edit"=>"checkbox","payment_privacy"=>"checkbox","contact_us_query"=>"checkbox","kyc_form"=>"checkbox","single_device"=>"checkbox","is_cart" =>"checkbox","is_rating"=> "checkbox")      
        ); 
        if($input){ 
                  $appid = (defined("APP_ID") ? "" . APP_ID . "" : "0");
                  
                  if($input['menu_type']=='left'){
                    $leftmenujson=array();
                    foreach($view_data['left_array'] as $left_array){
                        $leftmenujson[$left_array]=$input[$left_array]??"0";
                    }
                    $update_data['left_menu']=json_encode($leftmenujson,true);
                  }

                  if($input['menu_type']=='bottom'){
                    $bottommenujson=array();
                    foreach($view_data['bottom_array'] as $bottom_array){
                        $bottommenujson[$bottom_array]=$input[$bottom_array]??"0";
                    }
                    $update_data['bottom']=json_encode($bottommenujson,true);
                  }
                  if($input['menu_type']=='extra'){
                    $extramenujson=array();
                    foreach ($view_data['extra_array'] as $key => $value) {
                        if($value=='text'){
                            $extramenujson[$key]=$input[$key]??"0";
                        }else{
                            $extramenujson[$key]=$input[$key]??"0";                            
                        } 
                    }
                    $update_data['extra_json']=json_encode($extramenujson,true);
                  }

                    $this->db->where('app_id', $appid);
                    $this->db->update('application_meta',$update_data);
                    update_api_version($this->db, 9);
            if ($this->db->affected_rows() > 0) {
                page_alert_box("success","App Setting","App Setting has been updated successfully.");
                redirect(AUTH_PANEL_URL .'/version_control/version/app_configuration');
                }
                else{ 
                   // page_alert_box("error","App Setting","Something went wrong.");
                redirect(AUTH_PANEL_URL .'/version_control/version/app_configuration'); 
                }
             }
        else{
            $appid = (defined("APP_ID") ? "" . APP_ID . "" : "0");
            $this->db->where('app_id', $appid);
            $app_data = $this->db->get("application_meta")->row_array();
            $view_data['result'] = $app_data?$app_data:array(); 
            //echo "<pre>";print_r($data);
            $view_data['menu'] = $this->db->get("version_control vc")->result();
            $this->db->where('app_id', $appid);
            $this->db->order_by('position', 'asc');
            $view_data['bottom_bar'] = $this->db->get("menus")->result_array();
            $data['page_data'] = $this->load->view('version/app_setting', $view_data, TRUE);
            echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
      }
    }
    public function setPosition(){
        $sr = 0;
        foreach($this->input->post('id') as $id){
            $this->db->update('menus', ['position' => $sr], ['id' => $id, 'app_id' => APP_ID]);
            $sr++;
        }
        echo json_encode(['status' => true]);die;
    }
    public function versioning() {
        if ($this->input->post()) {

            $backend_user_id = $this->session->userdata("active_backend_user_id");
            $input_data = $this->input->post();
            $insert_data = array(
                "platform" => $input_data['device_type'],
                "version" => $input_data['version'],
                "note" => $input_data['note'],
                "created_by" => $backend_user_id,
                "created" => time(),
                "status" => 0,
                'app_id' => (defined("APP_ID") ? "" . APP_ID . "" : "0")
            );
            $this->db->insert("v_release_mgmt", $insert_data);
            if ($this->db->affected_rows() > 0) {
                backend_log_genration($this, "Release has been submit successfully.", "Release Management");
                page_alert_box("success", "Release Management", "Release has been submit successfully.");
                 echo json_encode(array("status" => true, "message" => "Release has been submit successfully."));
                 die;
                // redirect($_SERVER['HTTP_REFERER']);

            } else {
                echo json_encode(array("status" => false, "error" => "Release has not been submit successfully."));
                die;
            }
        }
        $appid = (defined("APP_ID") ? "" . APP_ID . "" : "0");
            $this->db->where('vrm.app_id', $appid);
        $this->db->select("vc.*,vrm.url")->join("v_release_mgmt vrm", "vrm.platform = vc.platform and vrm.status = 1 and vrm.app_id=".APP_ID, "LEFT");
        $this->db->group_by('vrm.id');
        $view_data['versions'] = $this->db->get_where("version_control vc",array('vc.app_id'=>APP_ID))->result();
        // $view_data['versions'] =  $this->db->where('app_id',APP_ID)->get("version_control")->result();
        //  print_r($view_data['versions']);die;
        $data['page_data'] = $this->load->view('version/version_view', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    private function exe_validate($device_type, $url) {
        $allowed_ext = array(1 => "apk", 2 => "ipa", 3 => "exe");
        $ext = explode(".", $url);
        if (end($ext) != $allowed_ext[$device_type]) {
            echo json_encode(array("status" => false, "error" => "file should have " . $allowed_ext[$device_type] . " extention for given platform(" . device_type($device_type) . ") "));
            die;
        }
    }

    public function ajax_release_list() {
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
        $totalData = $this->db->count_all_results("v_release_mgmt vrm");
        $totalFiltered = $totalData;

        $this->db->select("bu.username,vrm.*");
        if (defined("APP_ID"))
            app_permission("vrm.app_id", $this->db);
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $where_arr['vrm.platform'] = $requestData['columns'][0]['search']['value'];
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {   //name
            $where_arr['vrm.version'] = $requestData['columns'][2]['search']['value'];
        }
        if (!empty($requestData['columns'][4]['search']['value'])) {   //name
            $where_arr['bu.username LIKE'] = $requestData['columns'][4]['search']['value'] . '%';
        }

        $this->db->join("backend_user bu", "bu.id = vrm.created_by", "LEFT");
        $this->db->where($where_arr);
        $this->db->order_by($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir']);
        $this->db->limit($requestData['length'], $requestData['start']);
        $result = $this->db->get("v_release_mgmt vrm")->result();

        $this->db->join("backend_user bu", "bu.id = vrm.created_by", "LEFT");
        $this->db->where($where_arr);
        $totalFiltered = $this->db->count_all_results("v_release_mgmt vrm");

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

    public function update_release_status() {
        $release_id = $this->input->get("id");
        if (is_numeric($release_id)) {
            app_permission("app_id",$this->db);
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
                
                app_permission("app_id",$this->db);
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

    public function cache_management() {
        if ($this->input->post()) {
            $this->redis_magic->SET("ES_UT_009", $this->input->post('ES_UT_009'));
        }

        $view_data['versions'] = $this->redis_magic->GET("ES_UT_009") ? $this->redis_magic->GET("ES_UT_009") : 0;
         $view_data['breadcrum']=array('Cache Management'=>"#");
        $data['page_data'] = $this->load->view('version/cache_management', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    function configuration() {

        $view_data['info'] = new stdClass();
        $view_data['info']->slider_interval = get_db_meta_key($this->db, "slider_interval");
        $view_data['info']->ceo_message = json_decode(get_db_meta_key($this->db, "ceo_message"), true);
        $view_data['info']->contact_us = json_decode(get_db_meta_key($this->db, "CONCAT_US"), true);
        $view_data['info']->payment_gateways = explode(",", get_db_meta_key($this->db, "GLOBAL_PAYMENT_GATEWAYS"));
        $view_data['info']->rzp_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "RZP_DETAIL"), ''), true);
        $view_data['info']->payu_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "PAYU_DETAIL"), ''), true);
        $view_data['info']->ccavenue_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "CCAVENUE_DETAIL"), ''), true);
        $view_data['info']->payubiz_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "PAYUBIZ_DETAIL"), ''), true);

        $view_data['info']->instamojo_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "INSTAMOJO_DETAIL"), ''), true);
        $view_data['info']->paytm_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "PAYTM_DETAIL"), ''), true);
        $view_data['info']->email_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "EMAIL_DETAIL"), ''), true);
        $view_data['info']->firebase_detail = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "FIREBASE_DETAIL"), ''), true);
        $view_data['info']->gsm_key = json_decode(get_db_meta_key($this->db, "GSM_KEY"), true);
        $view_data['info']->deep = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "DEEPLINKING_DETAIL"),''), true);
        // $view_data['info']->deep = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "deep"),''), true);
        $view_data['info']->vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"),''), true);
        $view_data['info']->FIREBASE_API_KEY = json_decode(get_db_meta_key($this->db, "FIREBASE_API_KEY"), true);
        $view_data['info']->s3bucket_detail = json_decode(get_db_meta_key($this->db, "s3bucket_detail"), true);
        $view_data['info']->zoom_detail = json_decode(get_db_meta_key($this->db, "zoom_detail"), true);
         $view_data['info']->CASH_FREE_DETAIL =json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "CASH_FREE_DETAIL"), ''),true);
         $view_data['info']->GOOGLE_DETAIL =json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "GOOGLE_DETAIL"), ''),true);
         // print_r($view_data['info']);die;

//        var_dump(get_db_meta_key($this->db, "FIREBASE_API_KEY")); die;

        $meta = get_db_meta_key($this->db, "maintenance_break");
        $view_data['info']->break_from = explode("#", $meta)[0] ?? "";
        $view_data['info']->break_to = explode("#", $meta)[1] ?? "";
        
        $this->db->order_by("position", "asc");
        $this->db->where('course_id', -1);
        $view_data['faq'] = $this->db->where('app_id',APP_ID)->or_where('app_id',0)->get('course_faq_master')->result_array();
        // print_r($view_data['faq']);die;
        $view_data['breadcrum']=array('configuration'=>"#");
        $data['page_data'] = $this->load->view('version/configuration', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function usd_conversion()
    {
        if ($this->input->post()) 
        {
            $this->db->where('app_id', APP_ID);
            $this->db->where('meta_name', 'usd_conversion');
            $check = $this->db->get('meta_information')->row_array();

            if (empty($check)) 
            {
                $data = [

                    'meta_value' => $this->input->post('usd_conversion'),
                    'meta_name' => 'usd_conversion',
                    'app_id' => APP_ID,
                ];

                $this->db->insert('meta_information', $data);
            }
            else
            {
                $this->db->where('app_id', APP_ID);
                $this->db->where('meta_name', 'usd_conversion');
                $this->db->update('meta_information', ['meta_value' => $this->input->post('usd_conversion')]);
            }

            page_alert_box('success', 'USD Converstion', 'USD Conversion been added successfully');
        }

        $this->db->where('app_id', APP_ID);
        $this->db->where('meta_name', 'usd_conversion');
        $view_data['result'] = $this->db->get('meta_information')->row_array();

        $view_data['breadcrum'] = array('configuration' => "#");
        $data['page_data'] = $this->load->view('version/usd_conversion', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    function terms_and_policy() {        
        $view_data['info']['terms'] = get_db_meta_key($this->db, "TERMS");
        $view_data['info']['policy'] = get_db_meta_key($this->db, "POLICY");
        $view_data['info']['refund_policy'] = get_db_meta_key($this->db, "REFUND_POLICY");
        $view_data['info']['about_us'] = get_db_meta_key($this->db, "ABOUT_US");
        $view_data['info']['contact_us'] = get_db_meta_key($this->db, "CONTACT_US");
        $view_data['info']['footer_detail'] = get_db_meta_key($this->db, "FOOTER_DETAIL");
        $view_data['breadcrum']=array('Terms And Condition'=>"#");
        $view_data['faq'] = $this->db->where('app_id',APP_ID)->or_where('app_id',0)->get('course_faq_master')->result_array();
        $data['page_data'] = $this->load->view('version/terms_and_policy', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    function set_maintenance_break() {
        $array = array(
            "break_from" => $this->input->post("break_from") ? strtotime($this->input->post("break_from")) : 0,
            "break_to" => $this->input->post("break_to") ? strtotime($this->input->post("break_to")) : 0
        );
        set_db_meta_key($this->db, "maintenance_break", implode("#", $array));
        backend_log_genration($this, 'Maintenance Break-: ' . implode("#", $array), 'maintenance_break');
        page_alert_box("success", "Configuration", "Configuration Saved Successfully");
        redirect_to_back();
    }

    function set_ceo_message() {
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

    function set_rzp_detail() {
      
        $any_detail = json_encode($this->input->post());
         // print_r($any_detail);die;
        $meta_name = $this->input->post("meta_name");
        if ($meta_name == "FIREBASE_DETAIL") {
            if($_FILES && !empty($_FILES["service_account_file"]["name"])){
                $target='uploads/service_account_file/'.APP_ID.'_'.basename($_FILES["service_account_file"]["name"]);
                $google_json_file=move_uploaded_file($_FILES["service_account_file"]["tmp_name"], $target);
                chmod($target, 0755);
                $any_detail = json_decode($any_detail,true);
                $any_detail['service_account_file']=$target;
                $any_detail = json_encode($any_detail);
            }
            set_db_meta_key($this->db, "FIREBASE_DETAIL", aes_cbc_encryption($any_detail, ''));
            $gsm_key = $this->input->post("gsm_key");
            if($gsm_key != ''){
                
                $gsm_arr = json_encode(array("GSM_KEY"=>$gsm_key,"FIREBASE_API_KEY"=>$this->input->post("FIREBASE_API_KEY")));
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
        } else if ($meta_name == "RZP_DETAIL") {
            // print_r($any_detail);die;
            set_db_meta_key($this->db, "RZP_DETAIL", aes_cbc_encryption($any_detail, ''));
        } 
         else if ($meta_name == "CASH_FREE_DETAIL") {
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
    function set_footer() {
       // set_db_meta_key($this->db, "TERMS", $this->input->post("terms"));
         $any_detail = json_encode($this->input->post());
       // set_db_meta_key($this->db, "FOOTER_DETAIL", aes_cbc_encryption($any_detail, ''));
        set_db_meta_key($this->db, "FOOTER_DETAIL",$any_detail);
        backend_log_genration($this, 'Footer Details Changed', 'Footer Details');
        page_alert_box("success", "Configuration", "Configuration Saved Successfully");
        redirect_to_back();
    }
    //function fro s3 bucket
    function set_s3_bucket() {
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
    function set_zoom() {
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
    function set_cashfree() {
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
   

    function set_terms() {
        set_db_meta_key($this->db, "TERMS", $this->input->post("terms"));
        backend_log_genration($this, 'Terms and Condition Changed', 'Terms and Condition');
        page_alert_box("success", "Configuration", "Configuration Saved Successfully");
        redirect_to_back();
    }

    function set_policy() {
        set_db_meta_key($this->db, "POLICY", $this->input->post("policy"));
        backend_log_genration($this, 'Policy Changed', 'Privacy Policy');
        page_alert_box("success", "Configuration", "Configuration Saved Successfully");
        redirect_to_back();
    }

    function set_refund_policy() {
        set_db_meta_key($this->db, "REFUND_POLICY", $this->input->post("refund_policy"));
        backend_log_genration($this, 'Contact us Changed', 'CONCAT_US');
        page_alert_box("success", "Configuration", "Configuration Saved Successfully");
        redirect_to_back();
    }

    function set_about_us() {
        set_db_meta_key($this->db, "ABOUT_US", $this->input->post("about_us"));
        backend_log_genration($this, 'Contact us Changed', 'CONCAT_US');
        page_alert_box("success", "Configuration", "Configuration Saved Successfully");
        redirect_to_back();
    }

    function set_contact_us() {
        set_db_meta_key($this->db, "CONTACT_US", $this->input->post("contact_us"));
        backend_log_genration($this, 'Contact us Changed', 'CONCAT_US');
        page_alert_box("success", "Configuration", "Configuration Saved Successfully");
        redirect_to_back();
    }

    function set_payment_gateways() {
        $ids = "";
        if ($this->input->post("payment_gateways")) {
            $ids = implode(",", $this->input->post("payment_gateways"));
        }
        set_db_meta_key($this->db, "GLOBAL_PAYMENT_GATEWAYS", $ids);
        backend_log_genration($this, 'Payment Gateway Changed To: ' . $ids, 'GLOBAL_PAYMENT_GATEWAYS');
        page_alert_box("success", "Configuration", "Configuration Saved Successfully");
        redirect_to_back();
    }

    function update_version() {
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
            $input=$this->input->post();
            $input['platform'] = $this->db->where("id", $this->input->post('id'))->get("version_control")->row()->platform;
               
            // $device = device_type($this->input->post('id'));
            $result = $this->Version_model->update_version($input);
            if ($result) {
                $this->redis_magic->EXPIRE("MASTER_VERSION_" . APP_ID, 0);
                page_alert_box("success", "Update Version"," version has been updated successfully.");
                backend_log_genration($this, "Update Version", "Version Control", $this->input->post());
                echo json_encode(array("status" => 1, "message" =>" version has been updated successfully."));
                die;
            } else {
                page_alert_box("error", "Update Version", "Somthing went worng!");
                echo json_encode(array("status" => 0, "message" => "Somthing went worng!"));
                die;
            }
        }
    }

}
