<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Version extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->load->helper('aes');
    }

    public function versioning() {
        if ($_POST) {
            $array = array(
                "android" => $this->input->post('android'),
                "is_hard_update_android" => $this->input->post("is_hard_update_android"),
                "android_tv" => $this->input->post('android_tv'),
                "is_hard_update_android_tv" => $this->input->post("is_hard_update_android_tv"),
                "ios" => $this->input->post('ios'),
                "is_hard_update_ios" => $this->input->post("is_hard_update_ios")
            );
            $this->db->where('id', 1);
            $this->db->update('version_control', $array);
            if ($this->db->affected_rows() > 0) {
                page_alert_box("success", "Operation Successful", "Settings changed successfully");
            } else {
                page_alert_box("warning", "Operation Successful", "There was no changes");
            }
        }
        $view_data['page']  = "version_control";
        $data['page_data'] = $this->load->view('version/version_view', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function app_configuration(){
        $input = $this->input->post();
        $view_data = array(
           "result" => array(),
           "bottom_array"=>array("home","library","live_class","live_test","courses","batch_course","top_banner","top_layout","downloads","create_test","notification","floatingActionButtonLayer3","is_paid_course","is_free_course","is_purchase_toolbar","filter","layout_type","bottom_banner","invoice_tnc","contact_us","poll_management","attachment","seek_bar","audio","doubt"), 
           "left_array"=>array("downloads","coursetransfer","coupon","usagehistory","purchasehistory","invitefriends","contactus","settings","logout","create_test","hide_offer_price","custom_payment","book_store","feedback","user_support","current_affair"),
           "extra_array"=>array("banner_height"=>"text","banner_height_bottom"=>"text","book_store_link"=>"text","book_store"=>"text","master_category_style"=>"text","mobile_number"=>"text","custom_course"=>"text","coupon"=>"checkbox","google_login"=>"checkbox","main_category_image"=>"checkbox","email_show" => "checkbox","greeting" => "checkbox","country"=>"checkbox","date_of_birth"=>"checkbox","is_expert"=>"checkbox","course_delete"=>"checkbox","share_content"=>"checkbox","disable_name_edit"=>"checkbox","payment_privacy"=>"checkbox","contact_us_query"=>"checkbox")      
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

    function configuration() { //pre($_POST);die;
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
        $view_data['info']->vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"),''), true);
        $view_data['info']->FIREBASE_API_KEY = json_decode(get_db_meta_key($this->db, "FIREBASE_API_KEY"), true);
        $view_data['info']->s3bucket_detail = json_decode(get_db_meta_key($this->db, "s3bucket_detail"), true);
        $view_data['info']->zoom_detail = json_decode(get_db_meta_key($this->db, "zoom_detail"), true);
         $view_data['info']->CASH_FREE_DETAIL =json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "CASH_FREE_DETAIL"), ''),true);
         $view_data['info']->GOOGLE_DETAIL =json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "GOOGLE_DETAIL"), ''),true);

        $meta = get_db_meta_key($this->db, "maintenance_break");
        $view_data['info']->break_from = explode("#", $meta)[0] ?? "";
        $view_data['info']->break_to = explode("#", $meta)[1] ?? "";
        
        $this->db->order_by("position", "asc");
        $this->db->where('course_id', -1);
        $view_data['faq'] = $this->db->where('app_id',APP_ID)->or_where('app_id',0)->get('course_faq_master')->result_array();
        //$view_data['breadcrum']=array('configuration'=>"#");
        $data['page_data'] = $this->load->view('version/configuration', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
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

    function terms_and_policy() {        
        $view_data['info']['terms'] = get_db_meta_key($this->db, "TERMS");
        $view_data['info']['policy'] = get_db_meta_key($this->db, "POLICY");
        $view_data['info']['refund_policy'] = get_db_meta_key($this->db, "REFUND_POLICY");
        $view_data['info']['about_us'] = get_db_meta_key($this->db, "ABOUT_US");
        $view_data['info']['contact_us'] = get_db_meta_key($this->db, "CONTACT_US");
        $view_data['info']['footer_detail'] = get_db_meta_key($this->db, "FOOTER_DETAIL");
       // $view_data['breadcrum']=array('Terms And Condition'=>"#");
        $view_data['faq'] = $this->db->where('app_id',APP_ID)->or_where('app_id',0)->get('course_faq_master')->result_array();
        $data['page_data'] = $this->load->view('version/terms_and_policy', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    function set_rzp_detail() {
        $any_detail = json_encode($this->input->post());
         // print_r($any_detail);die;
        $meta_name = $this->input->post("meta_name");
        if ($meta_name == "FIREBASE_DETAIL") {
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
