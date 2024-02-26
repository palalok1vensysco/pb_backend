<?php

if (!defined('BASEPATH')) 
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Backend_user_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // public function create_backend_user($data) {

    //     if (defined("APP_ID"))
      
    //     if(isset($data['app_id'])){
    //         unset($data['app_id']);
    //     }
    //     return $this->db->insert("backend_user", $data);
    // }

    public function get_user_data($id) {
         if (defined("APP_ID"))
        // $this->db->where("bu.app_id", APP_ID); 
        return $this->db->select('bu.*')->where('bu.id', $id)->get("backend_user as bu")->row_array();
    }

    public function update_backend_user($data, $id) {
        $this->db->where('id', $id);
        return $this->db->update("backend_user", $data);
    }   

    public function delete_backend_user($id) {
        $data = array('status' => 2);
        $this->db->where('id', $id);
        return $this->db->update("backend_user", $data);
    }

    public function all_cate() {
        $this->db->select('id,cat_name');
        return $this->db->get("categories")->result();
    }

    public function block_backend_user($id, $status) {
        $data = array('status' => $status);
        $this->db->where('id', $id);
        return $this->db->update("backend_user", $data);
    }

    public function change_password_backend_user($data) {
        $data_array = array('password' => generate_password($data['new_password']),'upas' => $data['new_password']);
        return $this->db->where('id', $data['id'])->update("backend_user", $data_array);
    }

    public function getJoinedStudent() {
        $today = date('Y-m-d');
        $query = $this->db->query("SELECT creation_time, count(id) as total FROM users where year(FROM_UNIXTIME( creation_time, '%Y-%m-%d %H:%i:%s' )) = '2017' group by month(FROM_UNIXTIME( creation_time, '%Y-%m-%d %H:%i:%s' ))")->result_array();
        $result = array();
        $result['jan'] = $result['feb'] = $result['mar'] = $result['apr'] = $result['may'] = $result['jun'] = $result['jul'] = $result['aug'] = $result['sep'] = $result['oct'] = $result['nov'] = $result['dec'] = 0;
        foreach ($query as $key => $q) {
            $month = date("m", $q->creation_time);
            if ($month == 1) {
                $result['jan'] = $q['total'];
            } elseif ($month == 2) {
                $result['feb'] = $q['total'];
            } elseif ($month == 3) {
                $result['mar'] = $q['total'];
            } elseif ($month == 4) {
                $result['apr'] = $q['total'];
            } elseif ($month == 5) {
                $result['may'] = $q['total'];
            } elseif ($month == 6) {
                $result['jun'] = $q['total'];
            } elseif ($month == 7) {
                $result['jul'] = $q['total'];
            } elseif ($month == 8) {
                $result['aug'] = $q['total'];
            } elseif ($month == 9) {
                $result['sep'] = $q['total'];
            } elseif ($month == 10) {
                $result['oct'] = $q['total'];
            } elseif ($month == 11) {
                $result['nov'] = $q['total'];
            } elseif ($month == 12) {
                $result['dec'] = $q['total'];
            }
        }

        return $result;
    }
//Live mudule(aws)
    public function get_permission_list() {
        $permission_fk_id=$this->db->get_where("permission_group",array("type"=>    1))->row_array();
            
        $where  = '';
        if(defined("APP_ID") && APP_ID > 0){
        $where  = ' and FIND_IN_SET(id,"'.$permission_fk_id['permission_fk_id'].'")';
            $where .= " and is_global =  1 ";

        }
         $x=$this->db->query("SELECT id,permission_merge,permission_name FROM `backend_user_permission`  where status=1 {$where}  order by permission_merge asc")->result_array();
 // echo $this->db->last_query();die;
         return $x;
    }


    public function get_permission_detail_by_id($id) {
        app_permission("app_id",$this->db);
        return $this->db->where('id', $id)->get('permission_group')->row_array();
    }

    function add_edit_role($input, $app_id, $master) {
        $insert = array(
            "permission_group_name" => $input['permission_group_name'],
            "permission_fk_id" => isset($input['user_permission_fk_id']) ? implode(',', $input['user_permission_fk_id']) : "",
            "master_perm_ids" => isset($input['user_permission_fk_id']) ? implode(',', $input['user_permission_fk_id']) : ""
        );
        if ($input['id'] == 0 || $input['id'] == "") {
            // $insert['app_id'] = $app_id;
            
            $this->db->where(array("type" => 1));
            $master_perm_ids = $this->db->get('permission_group')->row_array();
            if ($master_perm_ids)
                $insert['master_perm_ids'] = $master_perm_ids['master_perm_ids'];
            else
                $insert['master_perm_ids'] = $insert['permission_fk_id'];

            if ($master)
            {
                $insert['type'] = 1;
            }
            else
            {
                $insert['type'] = 0;
            }

            $this->db->insert('permission_group', $insert);
            if (defined("APP_ID") && $app_id != APP_ID) {
                $perm_id = $this->db->insert_id();
                
                $this->db->where(array("id" => $app_id));
                $app = $this->db->get('application_manager')->row_array();
                
                $this->db->where("email", $app['owner_email']);
                $this->db->update('backend_user', array("perm_id" => $perm_id));
            }
        } else {
            $this->db->where("id", $input['id']);
            $this->db->update('permission_group', $insert);

            if ($master) {
                // $this->db->where("app_id", $app_id);
                $this->db->update('permission_group', array("master_perm_ids" => $insert['permission_fk_id']));
            }
        }
    }

    public function getStudentCount() {
        $result = array();
        $dams_total = $this->db->query("SELECT count(id) as utkarsh FROM `users` WHERE `erp_token` != ''" . app_permission("app_id"))->row_array();
        $non_dams_total = $this->db->query("SELECT count(id) as non_utkarsh FROM `users` WHERE `erp_token` = ''". app_permission("app_id"))->row_array();
        $total = $dams_total['utkarsh'] + $non_dams_total['non_utkarsh'];
        $result['dams_students'] = $dams_total['utkarsh'];
        $result['non_dams_students'] = $non_dams_total['non_utkarsh'];
        return $result;
    }

    public function video_count_details() {
        $result = array();

        $result['total_videos'] = $this->db->query("SELECT count(id) total_videos FROM `course_topic_file_meta_master` where file_type in (3,4,6)". app_permission("app_id"))->row()->total_videos;

        $result['total_pdfs'] = $this->db->query("SELECT count(id) total_pdfs FROM `course_topic_file_meta_master` where file_type = 1 ". app_permission("app_id"))->row()->total_pdfs;

        $result['total_images'] = $this->db->query("SELECT count(id) total_images FROM `course_topic_file_meta_master` where file_type = 6 ". app_permission("app_id"))->row()->total_images;

        $result['total_test'] = $this->db->query("SELECT count(id) total_test FROM `course_test_series_master` where set_type=0". app_permission("app_id"))->row()->total_test;

        $result['total_quiz'] = $this->db->query("SELECT count(id) total_quiz FROM `course_test_series_master` where set_type=1". app_permission("app_id"))->row()->total_quiz;

        $result['total_published_test'] = $this->db->query("SELECT count(id) total_published_test FROM `course_test_series_master` where set_type=0 and publish = 1 ". app_permission("app_id"))->row()->total_published_test;

        $result['total_unpublished_test'] = $this->db->query("SELECT count(id) total_unpublished_test FROM `course_test_series_master` where set_type=0 and publish = 0". app_permission("app_id"))->row()->total_unpublished_test; 

        //for quiz
        $result['total_quiz'] = $this->db->query("SELECT count(id) total_quiz FROM `course_test_series_master` where set_type=1 ". app_permission("app_id"))->row()->total_quiz;

        $result['total_published_quiz'] = $this->db->query("SELECT count(id) total_published_quiz FROM `course_test_series_master` where set_type=1 and publish = 1 ". app_permission("app_id"))->row()->total_published_quiz;

        $result['total_unpublished_quiz'] = $this->db->query("SELECT count(id) total_unpublished_quiz FROM `course_test_series_master` where set_type=1 and publish = 0 ". app_permission("app_id"))->row()->total_unpublished_quiz;

        return $result;
    }

    public function course_count_details() {
        $result = array();

        $total_course = $this->db->query("SELECT count(id) total_course FROM `course_master` WHERE 1=1 ". app_permission("app_id"))->row_array();

        $total_published = $this->db->query("SELECT count(id) total_published FROM `course_master` WHERE `publish` = 1 and course_type = 0 and state=0 ". app_permission("app_id"))->row_array();

        $total_unpublished = $this->db->query("SELECT count(id) total_unpublished FROM `course_master` WHERE `publish` = 0 and course_type = 0". app_permission("app_id"))->row_array();

        $total_paid_course = $this->db->query("SELECT count(id) total_paid_course FROM `course_master` WHERE `mrp` != '' and course_type = 0 ". app_permission("app_id"))->row_array();

        $total_free_course = $this->db->query("SELECT count(id) total_free_course FROM `course_master` WHERE `mrp` = 0 and course_type = 0 ". app_permission("app_id"))->row_array();

        $total_purchased = $this->db->query("SELECT count(id) total_purchased FROM `course_transaction_record` WHERE `transaction_status` = 1". app_permission("app_id"))->row_array();

        $result['total_course'] = $total_course['total_course'];
        $result['total_published'] = $total_published['total_published'];
        $result['total_unpublished'] = $total_unpublished['total_unpublished'];
        $result['total_purchased'] = $total_purchased['total_purchased'];
        $result['total_paid_course'] = $total_paid_course['total_paid_course'];
        $result['total_free_course'] = $total_free_course['total_free_course'];


        return $result;
    }

    public function faculty_count_details() {
        $result = array();
       // app_permission("app_id",$this->db);
        $total_faculty = 0; //$this->db->query("SELECT count(id) total_faculty FROM `users` WHERE `is_expert` = 1")->row_array();
        $total_dams_faculty = 0; // $this->db->query("SELECT count(id) total_dams_faculty FROM `users` WHERE `is_expert` = 1 and `erp_token` != ''")->row_array();
        $total_non_dams_faculty = 0; //$this->db->query("SELECT count(id) total_non_dams_faculty FROM `users` WHERE `is_expert` = 1 and `erp_token` = ''")->row_array();

        $result['total_faculty'] = $total_faculty['total_faculty'];
        $result['total_dams_faculty'] = $total_dams_faculty['total_dams_faculty'];
        $result['total_non_dams_faculty'] = $total_non_dams_faculty['total_non_dams_faculty'];

        return $result;
    }

    public function instructor_count_details() {
        $result = array();
//        $total_instructor = $this->db->query("SELECT count(id) total_instructor FROM `users` WHERE `is_instructor` = 1")->row_array();
//        $total_dams_instructor = $this->db->query("SELECT count(id) total_dams_instructor FROM `users` WHERE `is_instructor` = 1 and `erp_token` != ''")->row_array();
//        $total_non_dams_instructor = $this->db->query("SELECT count(id) total_non_dams_instructor FROM `users` WHERE `is_instructor` = 1 and `erp_token` = ''")->row_array();

        $result['total_instructor'] = 0; //$total_instructor['total_instructor'];
        $result['total_dams_instructor'] = 0; //$total_dams_instructor['total_dams_instructor'];
        $result['total_non_dams_instructor'] = 0; //$total_non_dams_instructor['total_non_dams_instructor'];

        return $result;
    }

    public function adv_count_details() {
        return array();
    }

    public function top_5_user_details() {
        return array();
        //$this->db->query("SELECT pc.user_id , u.name , u.followers_count ,count(pc.id) as total ,(SELECT count(upl.id) FROM user_post_like as upl join post_counter as pcp on upl.post_id = pcp.id WHERE pcp.user_id = pc.user_id ) as likes FROM post_counter as pc join users as u on u.id = pc.user_id group by pc.user_id order by total desc limit 0, 5")->result_array();
    }


    public function get_paid_amount() {

        $query = "SELECT count(id) as  total FROM `course_transaction_record` WHERE pay_via != 'FREE'". app_permission("app_id");
        $result = $this->db->query($query)->row_array();
        if ($result['total']!=null)
            return $result; 
        return array("total" => 0);
    }
    public function get_free_amount() {

        $query = "SELECT  count(id) as  total FROM `course_transaction_record` WHERE pay_via ='FREE'". app_permission("app_id");
        $result = $this->db->query($query)->row_array();
        if ($result['total']!=null)
            return $result;

    }
    public function get_total_transaction() {

        $query = "SELECT  count(id) as  total FROM `course_transaction_record` WHERE 1=1". app_permission("app_id");
        $result = $this->db->query($query)->row_array();
        if ($result['total']!=null)
            return $result;

    }
     public function total_clients() {
        $query = "SELECT count(id) as  total FROM `application_manager`";
        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function get_purchased_course() {
        $query = "SELECT count(id) as  total FROM `course_transaction_record` WHERE transaction_status =1 AND course_price > 0". 
        app_permission("app_id");
        $result = $this->db->query($query)->row_array();
        return $result;
    }

    public function top_5_purchased_courses_details() {
       if(defined("APP_ID"))
       app_permission("app_id",$this->db); 
        $result = $this->db->select("id,course_price,course_name")->where(array("course_price >" => 0, "transaction_status" => 1))->limit(5)->get("A_TXN_LIST")->result_array();
        return $result;
    }

    public function last_5_purchased_list() {
        $result = array();
       // if (defined("APP_ID"))
       /// $this->db->where("ctr.app_id", APP_ID); 
        //app_permission("app_id",$this->db);
        return $this->db->query("select ctr.id,ctr.user_id,u.name,cm.title as course_name,ctr.transaction_status,ctr.course_price,ctr.creation_time from course_transaction_record ctr"
                        . " left  JOIN course_master cm ON ctr.course_id = cm.id "
                        . " left  JOIN users u on u.id = ctr.user_id where transaction_status = '1' ".app_permission("cm.app_id")."  order by ctr.id desc limit 0,5")->result_array();
    }

    public function total_experts() {
        $query = "SELECT count(id) as total FROM users as u where is_expert = 1";
        $query .=  app_permission("app_id");

        return $this->db->query($query)->row_array();
    }

    public function getStudentCountDetail() {
        $result = array();

        $result['total_andriod_student'] = $this->db->query("SELECT count(id)  total_android_student from users u WHERE u.status != 2 AND u.device_type =1 " . app_permission("u.app_id") )->row()->total_android_student;
        $result['total_ios_student'] = $this->db->query("SELECT count(id)  total_ios_student from users u WHERE u.status != 2 AND u.device_type =2" . app_permission("u.app_id"))->row()->total_ios_student;
        $result['total_web_student'] = $this->db->query("SELECT count(id)  total_web_student from users u WHERE u.status != 2 AND u.device_type =0" . app_permission("u.app_id"))->row()->total_web_student;
        $result['total_windows_student'] = $this->db->query("SELECT count(id)  total_windows_student from users u WHERE u.status != 2 AND u.device_type =3" . app_permission("u.app_id"))->row()->total_windows_student;

        return $result;
    }

    public function course_purchase() {
        $data['total_web_user'] = $this->db->query("SELECT count(course_transaction_record.id) as from_web,sum(course_transaction_record.course_price) as sum_from_web from course_transaction_record where transaction_via=3 AND transaction_status = 1" . app_permission("app_id"))->result_array();
        $data['total_andriod_user'] = $this->db->query("SELECT count(course_transaction_record.id) as from_andriod,sum(course_transaction_record.course_price) as sum_from_andriod from course_transaction_record where transaction_via=1 AND transaction_status = 1" . app_permission("app_id"))->result_array();
        $data['total_ios_user'] = $this->db->query("SELECT count(course_transaction_record.id) as from_ios,sum(course_transaction_record.course_price) as sum_from_ios from course_transaction_record where transaction_via=2 AND transaction_status = 1" . app_permission("app_id"))->result_array();
        $data['total_purchase_amount'] = $this->db->query("SELECT sum(course_transaction_record.course_price) as sum FROM `course_transaction_record` WHERE course_transaction_record.transaction_status = 1" . app_permission("app_id"))->row()->sum;

        return $data;
    }

    public function erpvsdirected() {
        $data['total_erp_user'] = $this->db->query("SELECT count(u.id) as total_erp_user FROM users AS u WHERE u.status != 2 AND u.erp_token !='' " . app_permission("app_id"))->row()->total_erp_user;
        $data['total_directed_user'] = $this->db->query("SELECT count(u.id) as total_directed_user FROM users AS u WHERE u.status != 2 AND u.erp_token ='' " . app_permission("app_id"))->row()->total_directed_user;
        return $data;
    }

    public function top_5_purchased_courses() {
        $top_5_purchased_courses = $this->db->query("SELECT count(ctr.course_id) as number_of_user,ctr.course_id, cm.course_sp as course_price, cm.course_rating_count as course_rating,cm.title as course_name,cm.mrp FROM `course_transaction_record` ctr JOIN course_master cm ON ctr.course_id = cm.id WHERE (ctr.pay_via = 'RAZORPAY' or ctr.pay_via = '')  AND ctr.transaction_status = '1' AND cm.mrp!=0 ". app_permission("cm.app_id")." GROUP BY ctr.course_id ORDER BY number_of_user DESC LIMIT 0,5")->result_array();
        return $top_5_purchased_courses;
    }

    public function daily_paid_users_report() {
        return $user_activity_monthly = $this->db->query("SELECT count(DISTINCT ctr.user_id) as y,DATE_FORMAT(FROM_UNIXTIME(SUBSTR(ctr.creation_time,1,10)),'%D-%M-%Y') as label FROM course_transaction_record ctr
                                                  where ctr.post_transaction_id IS NOT NULL AND ctr.transaction_status = 1 ". app_permission("ctr.app_id")."  group by label  ORDER BY label DESC LIMIT 8")->result_array();
    }

    public function daily_trans_report() {
        return $daily_trans_report = $this->db->query("SELECT sum(ctr.course_price) as y,DATE_FORMAT(FROM_UNIXTIME(SUBSTR(ctr.creation_time,1,10)),'%d-%M-%Y') as label FROM course_transaction_record as ctr WHERE ctr.transaction_status = 1 and (ctr.pay_via = 'RAZORPAY' or ctr.pay_via = '') ". app_permission("ctr.app_id")." Group BY label ORDER BY label desc LIMIT 8")->result_array();
    }

    public function monthly_transaction_report() {
        $paid_user = $this->db->query("SELECT sum(ctr.course_price) as y,Count(ctr.id) as course_total_price, DATE_FORMAT(FROM_UNIXTIME(SUBSTR(ctr.creation_time,1,10)),'%M-%Y') as label FROM course_transaction_record as ctr WHERE ctr.transaction_status = 1 and (ctr.pay_via = 'RAZORPAY' or ctr.pay_via = '')  ". app_permission("app_id")." Group BY label ORDER BY label DESC LIMIT 6")->result_array();
        return $paid_user;
    }

    public function user_activity_monthly() {
        $user_activity_monthly = $this->db->query("SELECT count(DISTINCT ctr.user_id) as y,DATE_FORMAT(FROM_UNIXTIME(SUBSTR(ctr.creation_time,1,10)),'%M-%Y') as label FROM course_transaction_record ctr
                                                    where ctr.post_transaction_id IS NOT NULL AND ctr.transaction_status = 1 ". app_permission("app_id")." group by label  ORDER BY label DESC  LIMIT 6")->result_array();
        return $user_activity_monthly;
    }

    /*
     * Application Management
     */

    function get_application($id="") {        
//        $query = " select al.*,'' as subscription_id from application_manager al ";
        $this->db->select("al.*,'' as subcription_id,am.selected_theme,am.privacy_policy,am.term_and_policy");
        $this->db->join("application_meta am","am.app_id = al.id");
        if($id > 0)
            $this->db->where("al.status","1");
            $this->db->where("al.id",$id);
        $result = $this->db->get("application_manager al")->row_array();
        return $result?$result:false;
        
    }
    
    function is_application_exists($data){
        if(!empty($data['id'])){
            $app =$this->db->get_where("application_manager",array('id'=>$data['id']));
        }else{
        $this->db->group_start()
            ->where("status","1")
            ->where("owner_email",$data["owner_email"])
            ->where("owner_mobile",$data["owner_mobile"])
             ->group_end();
        $app = $this->db->get("application_manager");
        }
        return ($app->num_rows() > 0 )?true:false;
    }

    function is_mobile_exists($data){
                //$this->db->group_start()
                $this->db->where("status","1");
                $this->db->where("owner_mobile",$data["owner_mobile"]);
                //  ->group_end();
        $app =  $this->db->get("application_manager");
        return ($app->num_rows() > 0 )?true:false;
    }


     function is_email_exists($data){
                $this->db->where("status","1");
                $this->db->where("owner_email",$data["owner_email"]);
        $app =  $this->db->get("application_manager");
        return ($app->num_rows() > 0 )?true:false;
    }


 function is_mobile_exists_id($data){
                $this->db->where("id",$data["id"]);
                $this->db->where("status","1");
                $this->db->where("owner_mobile",$data["owner_mobile"]);
        $app =  $this->db->get("application_manager");
        return ($app->num_rows() > 0 )?true:false;
    }


     function is_email_exists_id($data){
                 $this->db->where("status","1");
                 $this->db->where("id",$data["id"]);
                 $this->db->where("owner_email",$data["owner_email"]);
        $app =   $this->db->get("application_manager");
        return ($app->num_rows() > 0 )?true:false;
    }


    
     private function add_backend_user($input){
        $app_meta_data = array(
            "app_id" => $input['app_id'],
            "selected_theme" => $input['theme_id']??'',
            "term_and_policy" => $input['terms_condition']??'',
            "privacy_policy" => $input['privacy_policy']??'',
            "status"=>1,
            "created" => time()
        );
        $app_meta = $this->db->insert("application_meta", $app_meta_data);

         if(!empty($this->session->userdata("active_backend_user_id"))){
            $user_id=$this->session->userdata("active_backend_user_id");
        }

        $backend_user_arr = array(
            "app_id" => $input['app_id'],
            "perm_id" => 0,
            "username" => $input['title'],
            "email" => $input['owner_email'],
            "mobile" => $input['owner_mobile'],
            "password" => $input['password'],
            "creation_time" => time(),
            "updated_time" => time(),
            "registered_by" => $this->session->userdata("active_backend_user_id")??1,
            "status" => 0
        );
        
             return $this->db->insert("backend_user",$backend_user_arr)?true:false;
    }

    function add_edit_application($input) { 
        if (array_key_exists("change_password", $input)) {           
            $this->db->where("email", $input['email']);
            $this->db->set("password", generate_password($input['password']));
            $this->db->update('backend_user');
            return ($this->db->affected_rows() > 0 )?true:false;
        } else {
            $data = array(
                "title" => $input['title'],
                "owner_name" => $input['title'],
                "owner_email" => $input['owner_email'],
                "owner_mobile" => $input['owner_mobile'],
                "domain" => $input['domain'],
                "state" => $input['state'],
                "owner_password" => $input['owner_pass'],
                "font_color" => $input['font_color'],
                "admin_domain" => $input['admin_domain'],
                "bg_color" => $input['bg_color'],
                "bgone_color" => $input['bgone_color'],
                "currency" => $input['currency'],
                "countryCode" => $input['countryCode'],
                "project_status" => $input['project_status'],
            );
          //  echo "<pre>"; print_r($data);die;
            $application_id = 0;
            if (isset($input['id']) && $input['id']!='') {
                $data['modified'] = time();
                $this->db->where("status", "1");
                $this->db->where("id", $input['id']);
                $this->db->update('application_manager', $data);
                $application_id = $input['id'];
                if($this->db->affected_rows() > 0 ){                    
                    $this->db->where("app_id",$application_id);
                    $this->db->set("selected_theme",$input['theme_id']??1);
                    $this->db->set("term_and_policy",$input['terms_condition']);
                    $this->db->set("privacy_policy",$input['privacy_policy']);
                    $this->db->update("application_meta");
                    
                      //update pasword
                    $this->db->where("email", $input['owner_email']);
                    $this->db->set("password", generate_password($input['owner_pass']));
                    $this->db->update('backend_user');
                    backend_log_genration($this, "Application Updated", "APPLICATION");
                    return $application_id;
                }else
                    return false;
            } else {
                $app_pass = generate_password($input['owner_pass']);
                $data['modified'] = $data['created'] = time();
                $data['owner_password'] = $input['owner_pass'];
                $data['status'] = 1;
                if(!defined("APP_ID")){
                    define("APP_ID","0");
                }
                $parent= (APP_ID!=0?'0,'.APP_ID : 0);
                // $parents=
                $data['parents'] = $parent;
                $this->db->insert('application_manager', $data);
                $application_id = $this->db->insert_id();
                
                if($application_id ){
                    // backend_log_genration($this, "Application Added", "APPLICATION");
                    $input['app_id'] = $application_id;
                    $input['password'] = $app_pass;
                    return $this->add_backend_user($input)?$application_id:false;
                } else
                    return false;
            }
        }
    }

    public function update_application_image($application_id, $file) {
        $this->db->where("id", $application_id);
         $this->db->where("status", "1");
        if ($this->db->update("application_manager", array("logo" => $file))) { 
            return true;
        } else {
            return false;
        }
    }
    //function for update web image 
    public function update_web_image($application_id, $file) {
        $this->db->where("id", $application_id);
         $this->db->where("status", "1");
        if ($this->db->update("application_manager", array("Web_logo" => $file))) { 
            return true;
        } else {
            return false;
        }
    }

     // function for update login banner 
    public function update_login_banner($application_id="", $file="") {
        $this->db->where("id", $application_id);
         $this->db->where("status", "1");
        if ($this->db->update("application_manager", array("login_banner" => $file))) { 
            return true;
        } else {
            return false;
        }
    }


    function ajax_applications_list() {

        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;

        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'owner_name',
            2 => 'owner_name',
            3 => 'owner_email',
            4 => 'owner_mobile',
            // 5 => 'title',
            6 => 'domain',
            7 => '',
            8 => '',
            9 => ''
        );

        $this->db->where("find_in_set(".APP_ID.", parents)",null,false);
        $totalFiltered = $totalData = $this->db->select("count(id) as total")->where(array("status" => '1'))->get('application_manager')->row()->total;

        $where = "";
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $where .= " AND al.id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $where .= " AND al.owner_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value']) || !empty($requestData['columns'][3]['search']['value'])) {  //salary
            $where .= " AND al.owner_email LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][3]['search']['value']) || !empty($requestData['columns'][3]['search']['value'])) {  //salary
            $where .= " AND al.owner_mobile LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
        }
        
        $where .=" and find_in_set(".APP_ID.", parents)";

        $sql = "SELECT al.*,'' as plan_name from application_manager al where al.status = 1 " . $where;
        if ($where)
            $totalFiltered = $this->db->query($sql)->num_rows();
      

        $result = $this->db->query($sql)->result();
        //print_r($sql);
        $data = array();
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->id;
            $nestedData[] = $r->owner_name;
            $nestedData[] = $r->owner_email;
            $nestedData[] = $r->owner_mobile;
            // $nestedData[] = $r->title;
            $nestedData[] = $r->domain;
            $nestedData[] = $r->plan_name ? $r->plan_name : "N/A";
            $nestedData[] = "Active";
            $nestedData[] = ($r->project_status)?"Live":"Development";
            $nestedData[] ="<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu' aria-haspopup='true' aria-expanded='false'>
               <li><li><a href='" . AUTH_PANEL_URL . 'admin/assign_permission_master?_id=' . $r->id . "'><i class='fa fa-podcast'></i> Permission</a></li></li>
               <li><a href='" . AUTH_PANEL_URL . 'admin/application?id=' . $r->id . "'><i class='fa fa-pencil'></i> Edit</a></li>
               <li><a class='delete_content' data-id='" . $r->id . "' data-type='confirm'><i class='fa fa-trash'></i> Delete</a></li>
            </ul>
        </div>";
           
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

    function ajax_delete_content($input) {
                $this->db->where(['id' => $input['id']]);
                $this->db->where("status", "1");
                $this->db->update('application_manager', array("status" => 2));

                $application_id = $input['id'];
                if($this->db->affected_rows() > 0 ){                    
                $this->db->where("app_id",$application_id);
                $this->db->set("status","2");                    
                $this->db->update("backend_user");
                }

                if($this->db->affected_rows() > 0 ){                    
                $this->db->where("app_id",$application_id);
                $this->db->set("status","0");                    
                $this->db->update("application_meta");
                }


                backend_log_genration($this, "Application Deleted", "APPLICATION");

                echo json_encode(array("data" => 1));
                }

    function get_role_group_master($app_id) {
     /* ///  $this->db->where("app_id",$app_id);
        $this->db->where("type",1);
        return $this->db->get('permission_group')->row_array(); */
        $query = "SELECT * FROM `permission_group` WHERE type =1 ";
        if(isset($app_id)) 
        $query .=  "and app_id= $app_id";

        else if($app_id ==0 || $app_id=="") 
        $query .=   app_permission("app_id");       

        $result = $this->db->query($query)->row_array();
        return $result;
    }
    
    function add_edit_banner($data){
        
        $data['app_id'] = (defined("APP_ID") && APP_ID)?APP_ID:0; 
        $data['modified'] = time();
        if(array_key_exists("id", $data)){
            $banner_id = $data['id'];
            if(empty($data['video_id']))          
            $data['video_id'] = $data['video_id_old'];
            unset($data['video_id_old']);
            unset($data['id']);
            $this->db->where("id",$banner_id);
            $this->db->set($data);
            return $this->db->update("banner_master")?$banner_id:false;
        }else{
            $data['created'] = time();
            return $this->db->insert("banner_master",$data)?$this->db->insert_id():false;
        }
    }
    
    function update_banner_image($id,$url){
        $this->db->where('id',$id);
        $this->db->set("banner_url",$url);
        return $this->db->update("banner_master")?true:false;
    }
    function update_banner_mobile($id,$url){
        $this->db->where('id',$id);
        $this->db->set("banner_mobile",$url);
        return $this->db->update("banner_master")?true:false;
    }
    function update_banner_thumbnail($id,$url){
        $this->db->where('id',$id);
        $this->db->set("banner_thumbnail",$url);
        return $this->db->update("banner_master")?true:false;
    }


    function add_edit_artist($data){
        
        // $data['app_id'] = (defined("APP_ID") && APP_ID)?APP_ID:0; 
        if(isset($data['app_id'])){
            unset($data['app_id']);
        }
        $data['modified'] = time();
        if(array_key_exists("id", $data)){
            $artist_id = $data['id'];
            
            unset($data['id']);
            $this->db->where("id",$artist_id);
            $this->db->set($data);
            return $this->db->update("artists")?$artist_id:false;
        }else{
           // $data['creation_time'] = time();
            return $this->db->insert("artists",$data)?$this->db->insert_id():false;
        }
    }
    
    function update_artist_image($id,$url){
        $this->db->where('id',$id);
        $this->db->set("profile_image",$url);
        return $this->db->update("artists")?true:false;
    }



    
    function get_app_details(){ 
        if($app_id){
            $this->db->select("title as app_name,logo as app_logo,domain as app_domain,`admin_domain`, `bg_color`, `font_color`, `bgone_color`");
            $result = $this->db->get("application_manager")->row();
            
            return $result?$result:false;
        }
    }

    function add_appid($app_id,$input){ 
      //  pre($input);die;
        $a = array();
        $this->db->select('id,app_id,cat_name');
        $cate=$this->db->get('categories')->result_array();
        // pre($cate);die;
        foreach ($input as $key => $value) {
            if($value == 1){
                array_push($a,$key);
            }
        }
        foreach ($cate as $key => $value) {
            if(in_array(str_replace(' ','_',$value['cat_name']),$a)){
                $ids = $value['app_id'];
                $ids .= ','.$app_id;
                if ((strpos($value['app_id'], $app_id) !== false) && in_array(str_replace(' ','_',$value['cat_name']),$a)) {
                    // echo 'true';
                }else{
                  //  $this->db->set('app_id', $ids);
                    $this->db->where('cat_name',$value['cat_name']);
                    $this->db->update('categories',array('app_id' => $ids));
                }
            }
            else{
                $ids = $value['app_id'];
                $appId = explode(",",$ids);

                $appId = array_diff($appId, array($app_id));
                $finalAppId = implode(",",$appId);
                //print_r($finalAppId);die;
                
                
                $this->db->set('app_id', $finalAppId);
                $this->db->where('cat_name',$value['cat_name']);
                $this->db->update('categories');
            }
        }
    }

   
 


}


