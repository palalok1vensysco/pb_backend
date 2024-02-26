<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master_hit extends MX_Controller {

    protected $master_hit_content = "master_hit_content";
    protected $redis_table_expire_time = 3600; //for 1 hours
    protected $redis_magic;

    function __construct() {
        parent::__construct();
        $this->load->library(['form_validation']);
        $this->redis_magic = new Redis_magic("data");
    }

    public function get_states() {
        $this->db->select("id,name");
        $this->db->where("country_id", 101);
        app_permission("app_id",$this->db);
        $states = $this->db->get("states")->result_array();
        if ($states)
            return_data(true, "States Displayed", $states);
        return_data(false, "States Not Found", $states);
    }

    public function get_cities() {
        $input = $this->validate_get_cities();

        $this->db->select("id,name");
        app_permission("app_id",$this->db);
        $this->db->where("state_id", $input['state_id']);
        $states = $this->db->get("cities")->result_array();
        if ($states)
            return_data(true, "Cities Displayed", $states);
        return_data(false, "Cities Not Found", $states);
    }

    private function validate_get_cities() {
        post_check();

        $this->form_validation->set_rules('state_id', 'state_id', 'trim|required|integer');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();

        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
        return $this->input->post();
    }

    private function get_notification_count() {
        app_permission("app_id",$this->db);
        return array(
            "count" => $this->db->select("count(id) as total")->where(array("user_id" => USER_ID, "view_state" => 0))->get("user_activity_relation")->row()->total
        );
    }

    public function content() {
        $this->validate_content();
        $user_id = USER_ID;

        $redis_data = $this->redis_magic->GET($this->master_hit_content);
        if ($redis_data) {
            $redis_data = json_decode($redis_data,true);
            $redis_data['notification'] = $this->get_notification_count();
            return_data(true, 'Master hit content', $redis_data, array());
        }

        if ($user_id) {
            $this->db->where("id", $user_id);
            $this->db->set("app_version", VERSION_CODE);
            $this->db->update("users");
        }

        if (API_REQUEST_LANG == 2) {
            $name = " name_2 as name";
        } else {
            $name = " name ";
        }
        $this->db->select("csnm.id,csnm.$name,csnm.parent_id,csnm.master_type,IFNULL(cssm.subject_id,'') as subject_id");
        $this->db->order_by("position", "asc");
        $this->db->where("published_courses >", 0);
        app_permission("csnm.app_id",$this->db);
        $this->db->join("course_stream_subject_master cssm", "cssm.stream_id=csnm.id", "left");
        $return['all_cat'] = $this->db->get('course_stream_name_master csnm')->result_array();

        //map subject_filter to sub stream start
        $subject_ids = $return['all_cat'] ? implode(',', array_column($return['all_cat'], 'subject_id')) : "";

        if ($subject_ids) {
            $subject_ids = explode(",", $subject_ids);
            $subject_ids = array_filter($subject_ids);
            $subject_ids = array_unique($subject_ids);
            $subject_ids = implode(",", $subject_ids);

            $subjects = array();
            if ($subject_ids) {
                $this->db->select("id,name" . (API_REQUEST_LANG == 2 ? "_2" : "") . " as title");
                $this->db->where_in("id", $subject_ids, false);
                $this->db->where("status", 0);
                app_permission("app_id",$this->db);
                $subjects = $this->db->get("course_subject_master")->result_array();

                foreach ($return['all_cat'] as $key => $value) {
                    if ($value['parent_id']) {
                        $subject_ids = $value['subject_id'] ? explode(",", $value['subject_id']) : array();

                        $filter = array();
                        foreach ($subjects as $sub) {
                            if (in_array($sub['id'], $subject_ids)) {
                                $filter[] = $sub;
                            }
                        }
                        $return['all_cat'][$key]['filters'] = $filter;
                    }
                }
            }
        }
        foreach ($return['all_cat'] as $key => $value) {
            if ($value['parent_id']) {
                if (!isset($value['filters']))
                    $return['all_cat'][$key]['filters'] = array();
            }
        }
        app_permission("app_id",$this->db);
        $return['languages'] = $this->db->get("language_code")->result_array();
        //map subject_filter to sub stream end

        
        //banner data
        app_permission("app_id",$this->db);
        $return['banner_list'] = $this->db->select("id,banner_title,banner_url")->where("status",1)->get("banner_master")->result();
        
        $master_cat = array();
        $master_cat = array_column($return['all_cat'], 'master_type');
        $master_cat = array_values(array_unique($master_cat));
        foreach ($master_cat as $key => $value) {
            $return['master_cat'][] = array(
                "id" => (string) ($key + 1),
                "cat" => $value
            );
        }
        foreach ($return['all_cat'] as $key => $value) {
            foreach ($return['master_cat'] as $cat) {
                if ($cat['cat'] == $value['master_type']) {
                    $return['all_cat'][$key]['master_type'] = $cat['id'];
                }
            }
        }
        app_permission("app_id",$this->db);
        $return['course_type_master'] = $this->db->select("id,$name,icon,font_color,bg_color")->order_by("position", "asc")->get('course_type_master')->result_array();

        $type = array(
            "id" => "0",
            "name" => "All",
//            "name_2" => "All",
//            "font_color" => "",
//            "bg_color" => "",
//            "position" => "0"
        );

        array_unshift($return['course_type_master'], $type);

        if ($return) {
            $this->redis_magic->SETEX($this->master_hit_content, $this->redis_table_expire_time, json_encode($return));
        }
        $return['notification'] = $this->get_notification_count();
        return_data(true, 'Master hit content1', $return, array());
    }

    public function validate_content() {
        post_check();

        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();

        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
    }

    function get_app_faq() {
        $this->db->select("id,question,description");
        $this->db->order_by("position", "asc");
        $this->db->where("course_id", -1);
        $result = $this->db->get("course_faq_master")->result_array();

        return_data(true, "FAQ Displayed", $result);
    }

    function get_app_contact_us() {
        $data = json_decode(get_db_meta_key($this->db, "CONTACT_US"), true);
        return_data(true, "Conctact Us Displayed", $data);
    }

    function policies() {
        echo get_db_meta_key($this->db, "POLICY");
    }

    function terms() {
        echo get_db_meta_key($this->db, "TERMS");
    }

    function about() {
        echo get_db_meta_key($this->db, "ABOUT_US");
    }

    function contact() {
        echo get_db_meta_key($this->db, "CONTACT_US");
    }

    function refund() {
        echo get_db_meta_key($this->db, "REFUND_POLICY");
    }

    function get_pay_gateway() {
        $return['rzp'] = get_db_meta_key($this->db, "RZP_DETAIL");
        if ($return['rzp'])
            $return['rzp'] = json_decode(aes_cbc_decryption($return['rzp'], ""), true);
        if (!$return['rzp'])
            $return['rzp'] = array("mode" => "", "key" => "", "secret" => "");
        return_data(true, "Details Displayed", $return);
    }

}
