<?php
use Aws\S3\S3Client;
use Aws\MediaPackageVod\MediaPackageVodClient;
defined('BASEPATH') OR exit('No direct script access allowed');

class Premium_video extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        // $this->load->library(['form_validation','s3_upload']);
         $this->load->helper('aes');
        $this->load->library(['form_validation','s3_upload']);
       // $this->load->helper(['aes', 'compress', 'aul','services','cookie','custom']);
        $this->load->helper(['aul','services','cookie','custom']);
        $this->load->model("Premium_video_model");
        $this->load->model("guru_model");
        $this->load->model("Movies_model");
       
    }

public function amazon_s3_upload($name, $aws_path) {
        $_FILES['file'] = $name;
        require_once FCPATH . 'aws/aws-autoloader.php';      

        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => AMS_REGION,
            'credentials' => [
                'key' => AMS_S3_KEY,
                'secret' => AMS_SECRET,
            ],
        ]);
        $result = $s3Client->putObject(array(
            'Bucket' => AMS_BUCKET_NAME,
            'Key' => $aws_path . '/' . rand(0, 7896756) . str_replace([':', ' ', '/', '*', '#', '@', '%'], "_", $_FILES["file"]["name"]),
            'SourceFile' => $_FILES["file"]["tmp_name"],
            'ContentType' => $_FILES["file"]["type"],
            'ACL' => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'Metadata' => array('param1' => 'value 1', 'param2' => 'value 2'),
        ));
        $data = $result->toArray();
        return $data['ObjectURL'];
    }

    private function video_operation($name,$is_drm=0) {
        $time = time();
        $target_location = getcwd() . '/uploads/bitrate';
        //1920*1280
        $all_names[] = $original = Upload($name, $target_location . "/", $time . "_1920x1280");
        //pre($original);
        $oldmask = umask(0);
        //1280*720
        $all_names[] = "b"; //$this->video_bit_rate($original, 1280, 720, $target_location, $time);
        //640*480
        $all_names[] = "c"; //$this->video_bit_rate($original, 640, 480, $target_location, $time);
        //320*240
        $all_names[] = "d"; //$this->video_bit_rate($original, 320, 240, $target_location, $time);

        /* Encryption Start on files */
        $s3_files = array("original" => "", "encrypted_url" => array());
        
        foreach ($all_names as $key => $current_name) {
            $encrypted_file = "a"; //aes_cbc_encryption_file($target_location . '/' . $current_name);
            if ($encrypted_file) {
                $enc = array();
                $enc["url"] = "https://mvfplayerbucket.s3.ap-south-1.amazonaws.com/file_library/videos/original/1588012383_1920x1280"; 
                $enc['name'] = "1920x1280.mp4"; //end($enc['name']);
                $enc['size'] = "1.01 MB"; //$this->getFormatSizeUnits(filesize($encrypted_file));
                $s3_files["encrypted_url"][] = $enc;
            } 
            if ($key == 0) {
                if ($this->input->post("custom_movie_url"))
                    $s3_files['original'] = str_replace('%2F', "/", $this->input->post("custom_movie_url"));
                else
                    $s3_files["original"] = $this->s3_upload->upload_s3($target_location . "/" . $current_name, "file_library/videos/original/");
            }
            if (file_exists($target_location . "/" . $current_name) && !$this->input->post("custom_s3_url"))
                unlink($target_location . "/" . $current_name);
        }
        /* Encryption End on files */
        umask($oldmask);
        /* creating job of original file in s3 */
        if ($s3_files["original"])
            modules::run('auth_panel/live_module/media_convert/index', $s3_files["original"], "file_library/videos/vod/");
        if ($is_drm==1)
            modules::run('auth_panel/live_module/media_convert/create_job_dash', $s3_files["original"], "file_library/videos/vod/");
        return $s3_files;
    }
      private function is_plan_exists($data){
            // $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0; 
        $this->db->select('id,plan_name');
        $this->db->where('plan_name', $data);
        // $this->db->where('app_id', $app_id);      
        $app = $this->db->get("premium_plan");
        return ($app->num_rows() > 0 )?true:false;
    }

    public function premium_plan() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('validity', 'Validity', 'trim|required');
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');
          //  $this->form_validation->set_rules('offeramount', 'offer amount', 'trim|required|numeric');
            $this->form_validation->set_rules('plan_name', 'Plan Name', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
            } else {
                $input = $this->input->post('plan_name');
                $is_plan_already_exists = $this->is_plan_exists($input);                
                  if ($is_plan_already_exists == true) {
                        page_alert_box("error", "plan Name", "This plan Name is already exist.", "");
                        redirect_to_back();
                    }
                $insert_data = array(
                    'validity' => $this->input->post('validity'), //in days...
                    'amount' => $this->input->post('amount'),
                    'offer_amount' => $this->input->post('offeramount')??0,
                    'total_amount' => $this->input->post('totalamount')??0,
                    'plan_name' => $this->input->post('plan_name'),
                    'gst_name' => $this->input->post('gst_name')??0,
                     'gst_intro' => $this->input->post('gst_intro')??0,
                    'total_devices' => $this->input->post('total_users'),
                    'creation_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id'),
                    // 'app_id'=>APP_ID
                );
                $id = $this->Premium_video_model->insert_plan($insert_data);
                if($id)
                 update_api_version_new($this->db, 'menu_master');
                page_alert_box('success', 'Added', 'New Plan added successfully');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        $view_data['page'] = 'premium_plan';
        $data['page_data'] = $this->load->view('premium_videos/add_plan', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
     public function country() {
           if ($this->input->post()) {           
            $this->form_validation->set_rules('price', 'Price', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
               // echo'dd';
            } else {
                $this->db->where('phonecode',$this->input->post('countryName'));
                $countryname=$this->db->get('country')->result_array();
                // echo $this->db->last_query();
                // print_r($countryname['0']['name']);die;
                $insert_data = array(                    
                    'c_name' => $countryname['0']['name'], //in days...
                    'c_currency' => $this->input->post('currency'),
                    'c_price' => $this->input->post('price'),
                    'c_code' => $this->input->post('countryName'),
                    // 'app_id'=>APP_ID,
                    'datacountryCode' => $this->input->post('datacountryCode')??0
                );
               /// pre($insert_data);die;
                $id = $this->Premium_video_model->country_plan($insert_data);
                if($id){
                    echo json_encode(array("type" => "success", "title" => "Success..", "message" => "price added success", "data" => $id));
                     update_api_version_new($this->db, 'menu_master');
                   // echo "Success";
                }else{
                     echo json_encode(array("type" => "error", "title" => "Error!", "message" => "price not added please try again."));
                     //echo "error";
                  //  die;
                }
                page_alert_box('success', 'Added', 'New Plan added successfully');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        $view_data['page'] = 'premium_plan';
        $data['page_data'] = $this->load->view('premium_videos/add_price', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    public function edit_country($id="") {
        if ($this->input->post()) {
           
            $this->form_validation->set_rules('price', 'Plan Name', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                 $this->db->where('phonecode',$this->input->post('countryName'));
                $countryname=$this->db->get('country')->result_array();
                $update_data = array(
                    'id' => $this->input->post('id'),
                    'c_name' => $countryname['0']['name'],
                    'c_code' => $this->input->post('countryName'), //in days...
                    'c_currency' => $this->input->post('currency'),
                    'c_price' => $this->input->post('price'), 
                    'modify_date' => date('Y-m-d')           
                );
                // print_r($update_data);die;
             
                 $update = $this->Premium_video_model->country_update_plan($update_data);
                 if($update)
                  update_api_version_new($this->db, 'menu_master');
                page_alert_box('success', 'Updated', 'Plan has been updated successfully');
                redirect(AUTH_PANEL_URL . 'videos/premium_video/country');
            }
        }
        $view_data['plan'] = $this->Premium_video_model->country_plan_by_id($id);
        $view_data['page'] = 'premium_plan';
        $data['page_data'] = $this->load->view('premium_videos/edit_country', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    public function delete_country_plan($id) {
        // $delete_user = $this->Premium_video_model->delete_country_plan($id);
        $this->db->where('id',$id);
        $this->db->delete('country_price');
         update_api_version_new($this->db, 'menu_master');
        page_alert_box('success', 'Deleted', 'Premium Plan has been deleted successfully');
        redirect(AUTH_PANEL_URL . 'videos/premium_video/country');
    }

    public function save_season_name() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('season_name', 'Season Name', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
            } else {
                $insert_data = array(
                    'season_name' => $this->input->post('season_name'),
                    'creation_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
                $id = $this->Premium_video_model->insert_season_name($insert_data);
                page_alert_box('success', 'Added', 'New Season Name added successfully');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        $view_data['page'] = 'add_season_Name';
         $view_data['genres'] = $this->Premium_video_model->get_season_name();
        $data['page_data'] = $this->load->view('premium_videos/add_season_name', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }


   

    public function edit_plan($id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('validity', 'Validity', 'trim|required');
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
            $this->form_validation->set_rules('plan_name', 'Plan Name', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $update_data = array(
                    'id' => $this->input->post('id'),
                    'validity' => $this->input->post('validity'), //in days...
                    'amount' => $this->input->post('amount'),
                    'offer_amount' => $this->input->post('offeramount')??0,
                    'total_amount' => $this->input->post('totalamount')??0,
                    'total_devices' => $this->input->post('total_users'),
                    'plan_name' => (isset($_POST['plan_name']) && !empty($_POST['plan_name']) ? $_POST['plan_name'] : ''),
                    'gst_name' => $this->input->post('gst_name')??0,
                     'gst_intro' => $this->input->post('gst_intro')??0,
                    'modified_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id'),
                    // 'app_id'=>APP_ID
                );
               // print_r($update_data); die;
                $update = $this->Premium_video_model->update_plan($update_data);
                if($update)
                     update_api_version_new($this->db, 'menu_master');
                page_alert_box('success', 'Updated', 'Plan has been updated successfully');
                redirect('admin-panel/premium-add-plan');
            }
        }
        $view_data['plan'] = $this->Premium_video_model->get_plan_by_id($id);
       // pre($view_data);die;
        $view_data['page'] = 'premium_plan';
        $data['page_data'] = $this->load->view('premium_videos/edit_plan', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function delete_premium_plan($id) {
        $this->db->where('id',$id);
        $this->db->delete('premium_plan');
         update_api_version_new($this->db, 'menu_master');
        page_alert_box('success', 'Deleted', 'Premium Plan has been deleted successfully');
        redirect(AUTH_PANEL_URL . 'videos/premium_video/premium_plan');
    }
    public function ajax_country_plan_list() {
        // storing  request (ie, get/post) global array to a variable
       // app_permission("app_id",$this->db);
        $requestData = $_REQUEST;
         $output_csv = $output_pdf = false;
        if (isset($_POST['input_json'])) {
            //$requestData = json_decode($_POST['input_json'], true);
            if (ISSET($_POST['download_pdf'])) {
                $output_pdf = true;
            } else {
                $output_csv = true;
            }
        } 
        
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'c_name',
            2 => 'c_currency',
            3 => 'c_price',
           // 4 => 'total_devices',
        );

        $query = "SELECT count(id) as total
				  FROM country_price where status=0";
        $query .=  (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");


        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT id,c_name,c_currency,c_price
				FROM country_price as cp where status=0";
        $sql .=  (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }


        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND c_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $sql .= " AND c_currency LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $sql .= " AND c_price LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	if(isset($requestData['length']))
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length

        $result = $this->db->query($sql)->result();
          // echo $this->db->last_query();  
       //pre($result);die;
	
        $data = array();
        if ($output_csv == true) {
            // for csv loop
            $head = array('Sr.No', 'c_name','c_currency','c_price');
            $start = 0;
            foreach ($result as $r) {
                $nestedData = array();
                $nestedData[] = $r->id;
                $nestedData[] = $r->c_name; 
                $nestedData[] = $r->c_currency;
                $nestedData[] = $r->c_price;
               // $nestedData[] = $r->validity;
                $data[] = $nestedData;
            }
            if ($output_csv == true) {
                $this->all_premium_video_to_csv_download($data, $filename = "Category.csv", $delimiter = ";", $head);
                
            }
        }
      //  $result= $this->db->get('country_price')->result();
         // echo $this->db->last_query();
        // print_r($result);die;
        $data=array();
        $start=0;
        foreach ($result as $r) {
            // preparing an array
            $nestedData = array();
             $nestedData[] = $r->id;
            $nestedData[] = $r->c_name;
            $nestedData[] = $r->c_currency;
            $nestedData[] = $r->c_price;
           // $nestedData[] = $r->app_id;
          //  $nestedData[] = '<i class="fa fa-inr" aria-hidden="true"></i> ' . $r->price;
           // $nestedData[] = $r->validity;
            $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu'>
           <li><a  onclick=\"return confirm('Are you sure you want to edit?')\" href='" . AUTH_PANEL_URL . "videos/premium_video/edit_country/". $r->id . "'><i class='fa fa-pencil'></i> Edit</a></li>
           <li><a  onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "videos/premium_video/delete_country_plan/" . $r->id . "'><i class='fa fa-trash'></i> Delete</a></li>
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



    public function ajax_premium_plan_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
         $output_csv = $output_pdf = false;
        if (isset($_POST['input_json'])) {
            //$requestData = json_decode($_POST['input_json'], true);
            if (ISSET($_POST['download_pdf'])) {
                $output_pdf = true;
            } else {
                $output_csv = true;
            }
        } 
        
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'plan_name',
            2 => 'amount',
            3 => 'validity',
            4 => 'total_devices',
        );

        $query = "SELECT count(id) as total
				  FROM premium_plan where status IN(0,1)";
        $query .=  (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");


        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT id,validity,amount,offer_amount,total_amount,plan_name,total_devices,status,gst_name
				FROM premium_plan where status IN(0,1)";
        $sql .=  (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND plan_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $sql .= " AND amount LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $sql .= " AND validity LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	if(isset($requestData['length']))
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length

        $result = $this->db->query($sql)->result();
      //  pre($this->db->last_query());die;
	
        $data = array();
        if ($output_csv == true) {
            // for csv loop
            $head = array('Sr.No', 'Plan Name','Total devices','amount', 'validity');
            $start = 0;
            foreach ($result as $r) {
                $nestedData = array();
                $nestedData[] = ++$start; //$r->id;
                $nestedData[] = $r->plan_name; 
                // $nestedData[] = $r->gst_name; 
                $nestedData[] = $r->total_devices;
                $nestedData[] = $r->amount;
                $nestedData[] = $r->validity;
                $data[] = $nestedData;
            }
            if ($output_csv == true) {
                $this->all_premium_video_to_csv_download($data, $filename = "Premium_plan.csv", $delimiter = ";", $head);
                die;
            }
        }
        foreach ($result as $r) {
            // preparing an array
            $action = '';
            $action_data ='';
               if ($r->status == '0') {
                $action .= "<a onclick=\"return confirm('Are you sure you want to Deactivate this plan ')\" class='btn-xs btn  bold btn-warning green_color' href='" . AUTH_PANEL_URL . "videos/premium_video/block_plan/" . $r->id . "/1'>Active</a>";
                $action_data .="<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu'>
           <li><a  onclick=\"return confirm('Are you sure you want to edit?')\" href='" . base_url('admin-panel/premium-edit-plan/') . $r->id . "'><i class='fa fa-pencil'></i> Edit</a></li>
           </ul>
            </div>";
            } 
             if ($r->status == '1') {
                $action .= "<a  onclick=\"return confirm('Are you sure you want to Activate this plan ')\" class='btn-xs bold btn btn-success red_color' href='" . AUTH_PANEL_URL . "videos/premium_video/block_plan/" . $r->id . "/0'>Inactive</a>";
                $action_data .="<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
                <ul class='dropdown-menu'>
               <li><a  onclick=\"return confirm('Are you sure you want to edit?')\" href='" . base_url('admin-panel/premium-edit-plan/') . $r->id . "'><i class='fa fa-pencil'></i> Edit</a></li>
               <li><a  onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "videos/premium_video/delete_premium_plan/" . $r->id . "'><i class='fa fa-trash'></i> Delete</a></li>
               </ul>
                </div>";
            }

            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->plan_name;
             // $nestedData[] = ($r->gst_name? $r->gst_name.'%':'NA'); 
            $nestedData[] = $r->total_devices;
            $nestedData[] = '<i class="fa fa-inr" aria-hidden="true"></i> ' . $r->amount;
            // $nestedData[] = '<i class="fa fa-inr" aria-hidden="true"></i> ' . $r->offer_amount;
            // $nestedData[] = '<i class="fa fa-inr" aria-hidden="true"></i> ' . $r->total_amount;
            $nestedData[] = $r->validity.' days';
            $nestedData[] = $action;
            $nestedData[] = $action_data;
        //     "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
        //     <ul class='dropdown-menu'>
        //    <li><a  onclick=\"return confirm('Are you sure you want to edit?')\" href='" . base_url('admin-panel/premium-edit-plan/') . $r->id . "'><i class='fa fa-pencil'></i> Edit</a></li>
        //    <li><a  onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "videos/premium_video/delete_premium_plan/" . $r->id . "'><i class='fa fa-trash'></i> Delete</a></li>
        //                </ul>
        //     </div>";
                       
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

    public function block_plan($id, $status) {
        $delete_user = $this->Premium_video_model->block_user_plan($id, $status);
        if ($delete_user == true) {
            backend_log_genration(
                    $this,
                    "User has been Blocked succssfully.",
                    "View Backend Users"
            );
            $this->session->set_flashdata('success_message', 'User has been Blcoked succssfully');
        } else {
            $this->session->set_flashdata('error_message', 'User not Deleted');
        }
                redirect(AUTH_PANEL_URL . 'videos/premium_video/premium_plan');
    }

    public function add_season() {
         if ($this->input->post()) {
            $this->form_validation->set_rules('season_title', 'Season Title', 'trim|required');
             $this->form_validation->set_rules('description', 'Season Description', 'required');
            $this->form_validation->set_rules('category_ids', 'Sub Category', 'trim|required');
            
            $this->form_validation->set_rules('published_date', 'Published date from', 'required');
            if ($this->form_validation->run() == FALSE) {
                $view_data['validation_error'] = '1';
            } else {
                 /*
                 * token is combined by three variables and combined by "_" underscore
                 * first one-primary id
                 * socond one-video type
                 * third-key //16 digit
                 */
                $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();

                if ($this->input->post('related_artist') != '') {
                    $related_artist = implode(",", $this->input->post('related_artist'));
                } else {
                    $artist_name = $this->Movies_model->get_default_artist();
                    $related_artist = $artist_name['id'];
                }
                if (!empty($_FILES['season_thumbnail']['name'])) {
                   $season_thumbnail = $this->amazon_s3_upload($_FILES['season_thumbnail'], "season_thumbnail/Thumbnail");
                } else {
                    $season_thumbnail = '';
                }
                if (!empty($_FILES['poster']['name'])) {
                   $poster = $this->amazon_s3_upload($_FILES['poster'], "season_poster/poster");
                } else {
                    $poster = '';
                }
                
                if ($this->input->post('related_sub') != '') {
                    $related_sub = implode(",", $this->input->post('related_sub'));
                } else {
                        $related_sub = '0';
                }
                $season_name=$this->input->post('season_name');
                $season_name_id=$this->Premium_video_model->get_season_id($season_name);
                $insert_data = array(
                    'season_title' => $this->input->post('season_title'),
                    'season_name' => $this->input->post('season_name'),
                     'url_type' => $this->input->post('url_type'),
                    'season_name_id' => $season_name_id['id'],
                    'category_ids' => ucwords($this->input->post('category_ids')),
                    'age_18' => ucwords($this->input->post('age_restrict')),
                    'author_id' => $related_artist,
                    'season_id' => $this->input->post('season_type'),
                    'season_type' => "Season " .$this->input->post('season_type'),
                    'description' => $this->input->post('description'),
                    'published_date' => (strtotime($_POST['published_date']) * 1000),
                    'season_thumbnail' => $season_thumbnail,
                    'poster' => $poster,
                    'promo_video' => $this->input->post('promo_video'),
                    'view_mode' => ucwords($this->input->post('view')),
                    'subscription' => $related_sub,
                );
                if(defined("APP_ID") && APP_ID)
                    $insert_data['app_id'] = APP_ID;

                $season_id = $this->Premium_video_model->insert_season($insert_data);
                
                page_alert_box('success', 'Added', 'Season added successfully');
                redirect(AUTH_PANEL_URL . 'videos/premium_video/add_season');
            }
        }

         $view_data['categories'] = $this->Premium_video_model->get_categories();
        
         $view_data['sub_caegories'] = $this->Premium_video_model->get_sub_category();
        $view_data['season_names'] = $this->Premium_video_model->get_season_name();
        $view_data['authors'] = $this->Premium_video_model->get_authors();
        $view_data['premium_plan'] = $this->Premium_video_model->get_plans();
        $view_data['page'] = 'premium_season';
        $data['page_data'] = $this->load->view('premium_videos/add_season', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function view_season() {

        $id=$_GET['id'];
        $token=$_GET['token'];
        $data_id=$_GET['data_id'];
        $data_type=$_GET['data_type'];
         $view_data['categories'] = $this->Premium_video_model->get_categories();
        $view_data['artists'] = $this->Premium_video_model->get_authors();
         $view_data['sub_caegories'] = $this->Premium_video_model->get_sub_category();
        $view_data['season_names'] = $this->Premium_video_model->get_season_name();
        $view_data['mobile_menu_category'] = $this->Premium_video_model->get_mobile_menu_category();
        $view_data['authors'] = $this->Premium_video_model->get_authors();
        $view_data['season_details'] = $this->Premium_video_model->get_season_details($id);
        $view_data['page'] = 'premium_season';
        $view_data['updated_at'] = $this->db->where('id',1)->get('video_meta')->row()->updated_at;
        $view_data['videos'] = $this->Movies_model->get_video_list();
        $data['page_data'] = $this->load->view('premium_videos/view_season', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_season($season_id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('season_title', 'Season Title', 'trim|required');
             $this->form_validation->set_rules('description', 'Season Description', 'required');
            if ($this->form_validation->run() == FALSE) {
            } else {
                $id = $this->input->post('id');
                /*
                 * token is combined by three variables and combined by "_" underscore
                 * first one-primary id
                 * socond one-video type
                 * third-key //16 digit
                 */
                $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();

                if ($this->input->post('related_artist') != '') {
                    $related_artist = implode(",", $this->input->post('related_artist'));
                } else {
                    $artist_name = $this->Movies_model->get_default_artist();
                    $related_artist = $artist_name['id'];
                }
                $update_data = array(
                    'season_title' => $this->input->post('season_title'),
                    'promo_video' => $this->input->post('promo_video'),
                     'url_type' => $this->input->post('url_type'),
                    'category_ids' => ucwords($this->input->post('category_ids')),
                    'language' => ucwords($this->input->post('language')),
                    'age_18' => ucwords($this->input->post('age_restrict')),
                    'author_id' => $related_artist,
                    'season_id' => $this->input->post('season_type'),
                    'season_type' => $this->input->post('season_type'),
                    'description' => $this->input->post('description'),
                    'published_date' => (strtotime($_POST['published_date']) * 1000),
                    
                    'view_mode' => ucwords($this->input->post('view')),


                );
                if (!empty($_FILES['season_thumbnail']['name'])) {
                   $season_thumbnail = $this->amazon_s3_upload($_FILES['season_thumbnail'], "season_thumbnail/Thumbnail");
                   $update_data['season_thumbnail']=$season_thumbnail;

                } 
                if (!empty($_FILES['poster']['name'])) {
                   $poster = $this->amazon_s3_upload($_FILES['poster'], "season_poster/poster");
                   $update_data['poster']=$poster;

                } 
               if (!empty($_FILES['promo_video']['name'])) {
                   $promo_video = $this->amazon_s3_upload($_FILES['promo_video'], "promo_video/Seasons");
                   $drm_encrypted_url = "file_library/videos/vod_drm_encrypted/$season_id/";
                    $s3_files = $this->video_operation("promo_video");
                    if ($s3_files) {
                        $video_url=$file = $s3_files['original'];
                        $file = convert_normal_to_m3u8($file);
                        $file = explode(".com/", $file)[1];
                        $url_json = $s3_files['encrypted_url'];
                    }
                 
                foreach ($url_json as $key => $value) {
                    $url_json[$key]['url'] = aes_cbc_encryption($value['url'], $token);
                }
                $update_data = array(
                    'promo_video' => aes_cbc_encryption($file, $token),
                    'encrypted_urls' => json_encode($url_json),
                    'token' => $season_id . "_" . '2' . "_" . $token
                );
            }
                $this->db->where('id',$season_id)->update('premium_season',$update_data);
                page_alert_box('success', 'Updated', 'Season has been updated successfully');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
           $view_data['categories'] = $this->Premium_video_model->get_categories();
        
         $view_data['sub_caegories'] = $this->Premium_video_model->get_sub_category();
        $view_data['artists'] = $this->Premium_video_model->get_authors();
        $view_data['premium_plan'] = $this->Premium_video_model->get_plans();
        $view_data['season_details'] = $this->Premium_video_model->get_season_details($season_id);
        $view_data['page'] = 'premium_season';
        $data['page_data'] = $this->load->view('premium_videos/edit_season', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_get_season_list() {
        $requestData = $_REQUEST;
        $output_csv = $output_pdf = false;
        if (isset($_POST['input_json'])) {
            //$requestData = json_decode($_POST['input_json'], true);
            if (ISSET($_POST['download_pdf'])) {
                $output_pdf = true;
            } else {
                $output_csv = true;
            }
        } 
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'season_title',
            1 => 'season_title',
            2 => 'category_ids',
            3 => 'author_id',
            4 => 'published_date',
            5 => 'status'
        );

        $query = "SELECT count(id) as total
								From premium_season where status !=2 
								";
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT ps.id,ps.season_title,ps.description,ps.category_ids,ps.author_id,ps.status,ps.published_date,ps.position,ps.view_mode,ps.promo_video,ps.token,ps.season_type,ps.season_name,sc.sub_category_name as category_name
            From premium_season as ps
            LEFT JOIN sub_category sc  ON ps.category_ids = sc.id
                where ps.status !=2
                ";
        $sql .=  (defined("APP_ID") ? "" . app_permission("ps.app_id") . "" : "");

        if (!empty($requestData['columns'][1]['search']['value'])) {
            //salary
            $sql .= " AND ps.season_title LIKE '" . '%' . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $sql .= " AND ps.description LIKE '" . '%' . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            //$sql .= " AND ps.description LIKE '" . '%' . $requestData['columns'][2]['search']['value'] . "%' ";
            $cat_id = $requestData['columns'][3]['search']['value'];
            $sql .= " AND FIND_IN_SET($cat_id,sc.id) ";
        }
        if (isset($requestData['columns'][4]['search']['value']) && $requestData['columns'][4]['search']['value'] != "") {  //salary
            $sql .= " AND ps.view_mode = " . $requestData['columns'][4]['search']['value'];
        }
        if (isset($requestData['columns'][5]['search']['value']) && $requestData['columns'][5]['search']['value'] != "") {  //salary
            $sql .= " AND ps.status = " . $requestData['columns'][5]['search']['value'];
        }
        //$sql.="order by ps.position asc";

        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); 
        // when there is a search parameter then we have to modify total number filtered rows as per search result.
        if(isset($requestData['start']))
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
        //$sql .= "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length


        $result = $this->db->query($sql)->result();
        $data = array();
        if ($output_csv == true) {
            // for csv loop
            $head = array('Sr.No', 'Category Name','Type Id', 'Status', 'Registered On');
            $start = 0;
            foreach ($result as $r) {
                $nestedData = array();
                $nestedData[] = ++$start; //$r->id;
                $nestedData[] = $r->season_name; 
                $nestedData[] = $r->season_type;
                $nestedData[] = $r->season_title;
                $nestedData[] = ($r->status == 0 ) ? 'Active' : 'Disabled';
                $data[] = $nestedData;
            }
            if ($output_csv == true) {
                $this->all_premium_video_to_csv_download($data, $filename = "Category.csv", $delimiter = ";", $head);
                die;
            }
        }
        $start = 0;
        foreach ($result as $r) {
            if($r->view_mode == 1){
                $r->view_mode = "Free";
            }elseif($r->view_mode == 0){
                $r->view_mode = "Premium";
            }

             $short_desc = $this->word_formatter($r->description);
            $status = ($r->status == 0) ? 'Active' : 'Disabled';
            $alert_status = ($r->status == 0) ? 'success' : 'danger';
            // preparing an array
            $nestedData = array();
            $nestedData[] = ++$start;
            $nestedData[] = $r->id;
            $nestedData[] = $r->season_name;
            $nestedData[] = $r->season_type;
            $nestedData[] = $r->season_title;
            $nestedData[] = $short_desc . "...";
            $nestedData[] = $r->category_name;
            $nestedData[] = "<span class='bold'>".$r->view_mode."</span>";
            $nestedData[] = date("d-m-Y h:i:s A", $r->published_date / 1000);
            // $nestedData[] = ($r->status == 0 ) ? '<span class="btn btn-xs bold btn-success">Active</span>' : '<span class="btn btn-xs btn-warning">Locked</span>';
           $nestedData[] =" <a class='btn-xs bold btn btn-$alert_status view_vid' onclick=\"return confirm('Are you sure you want to $alert_status?')\" href='" . AUTH_PANEL_URL . "videos/premium_video/lock_unlock_season/" . $r->id . '/' . $r->status . "'> $status</a>&nbsp;";
            $nestedData[] = "
            <a class='btn-xs bold btn btn-success preview' title='View Video' href='" . AUTH_PANEL_URL . "videos/premium_video/view_season?id=" . $r->id . "&data_id=".$r->id."&token=".$r->token."&data_type=2'><i class='fa fa-eye'></i></a>&nbsp;
         <a class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . AUTH_PANEL_URL . "videos/premium_video/edit_season/" . $r->id . "' ><i class='fa fa-edit'></i></a>&nbsp;
				
                                    <a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to Delete?')\" href='" . AUTH_PANEL_URL . "videos/premium_video/delete_season/" . $r->id . '/' . $r->status . "'><i class='fa fa-trash-o'></i></a>&nbsp;
				";
            $nestedData[] = "
            <a class='btn-xs bold btn btn-success' title='Add Episode' href='" . AUTH_PANEL_URL . "videos/premium_video/add_season_episode/" . $r->id . "'><i class='fa fa-plus'></i>Add Episode</a>&nbsp;&nbsp;&nbsp;
         <a class='btn-xs bold btn btn-primary'href='" . AUTH_PANEL_URL . "videos/premium_video/episode_list/" . $r->id . "'><i class='fa fa-edit'></i>Manage Episodes</a>&nbsp;
<!--<a class='btn-sm btn btn-success btn-xs bold' href='" . AUTH_PANEL_URL . "videos/premium_video/getDuration/'>Get Duration</a>;--!>
				
				";
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
    private function word_formatter($string) {
        $string = explode(" ", strip_tags($string));
        if ($string && count($string) > 25) {
            $string = array_slice($string, 0, 25, true);
        }
        return implode(" ", $string);
    }

    public function lock_unlock_season($id, $status) {
        if ($status == 0) {
            $header = 'Disable';
            $title = 'Season has been locked successfully';
        }
        if ($status == 1) {
            $header = 'Enable';
            $title = 'Season has been unlocked successfully';
        }
        $delete_videos = $this->Premium_video_model->lock_unlock_season($id, $status);
        page_alert_box('success', $header, $title);
        redirect(AUTH_PANEL_URL . 'videos/premium_video/add_season');
    }

    public function delete_season($id) {
        $delete_season = $this->Premium_video_model->delete_season($id);
        page_alert_box('success', 'Deleted', 'Season has been deleted successfully');
        redirect(AUTH_PANEL_URL . 'videos/premium_video/add_season');
    }

    public function save_position_season() {

        $ids = $_POST['ids'];
        $counter = 1;
        foreach ($ids as $id) {
            $this->db->where('id', $id);
            $array = array('position' => $counter);
            $this->db->update('premium_season', $array);
            $counter++;
        }
        echo json_encode(array('status' => true, 'message' => 'position saved'));
        die;
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX--->SEASON BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX--->EPISODES BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    public function add_season_episode($id = NULL) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('season_id', 'Season', 'trim|required');
            $this->form_validation->set_rules('episode_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('episode_description', 'Description', 'trim|required');
            if (empty($_FILES['movie_url']['name']) && !$this->input->post('custom_movie_url')) {
                $this->form_validation->set_rules('movie_url', 'File', 'required');
            }
            $season_id = $this->input->post('season_id');

             if (!empty($_FILES['thumbnail1_url']['name'])) {
                   $thumbnail1_url = $this->amazon_s3_upload($_FILES['thumbnail1_url'], "episode_thumbnail_url/Thumbnails");
                } else {
                    $thumbnail1_url = '';
                }
           

            if ($this->form_validation->run() == FALSE) {
               $view_data['validation_error'] = '1';
            } else {
                 /*
                 * token is combined by three variables and combined by "_" underscore
                 * first one-primary id
                 * socond one-video type
                 * third-key //16 digit
                 */
                $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();

                $insert_data = array(
                    'season_id' => $season_id,
                    'episode_title' => $this->input->post('episode_title'),
                    'url_type' => $this->input->post('url_type'),
                    'episode_description' => $this->input->post('episode_description'),
                    'ep_no' => $this->input->post('ep_no'),
                    'thumbnail_url' =>$thumbnail1_url,
                     'episode_url' =>  $this->input->post('movie_url'),
                    // 'runtime' =>  $this->input->post('hours') . ":" . $this->input->post('minutes') . ":" . $this->input->post('seconds'),
                    'release_date' => $this->input->post('released_date'),
                    // 'publish' => ucwords($this->input->post('publish')),
                    //  'download' => ucwords($this->input->post('download')),

                    'creation_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
                // echo '<pre>'; print_r($insert_data);echo '</pre>';
                // die;

                $id = $this->Premium_video_model->insert_episode($insert_data);

               
                page_alert_box('success', 'Added', 'Episode added successfully');
                redirect(AUTH_PANEL_URL . 'videos/premium_video/episode_list/' . $season_id);
            }
//            redirect(AUTH_PANEL_URL . 'videos/premium_video/add_season_episode/' . $season_id);
        }
        $view_data['season_id'] = $id;
        $view_data['page'] = 'premium_season';
        $data['page_data'] = $this->load->view('premium_videos/add_season_episode', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

//    public function upload_season_episode() {
//        if ($this->input->post()) {
//            $this->form_validation->set_rules('season_id', 'Season', 'trim|required');
//            $this->form_validation->set_rules('episode_title', 'Title', 'trim|required');
//             if (empty($_FILES['thumbnail_url']['name'])) {
//                    $this->form_validation->set_rules('thumbnail_url', 'Thumbnail', 'trim|required');
//                }
//            if ($this->form_validation->run() == FALSE) {
//                $view_data['validation_error'] = '1';
//            } else {
//                $insert_data = array(
//                    'season_id' => $this->input->post('season_id'),
//                    'episode_title' => $this->input->post('episode_title'),
//                    'yt_episode_url' => (isset($_POST['yt_episode_url']) && !empty($_POST['yt_episode_url']) ? $_POST['yt_episode_url'] : ''),
//                    'creation_time' => milliseconds(),
//                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
//                );
//                if (!empty($_FILES['thumbnail_url']['name'])) {
//                    $insert_data['thumbnail_url'] = $this->aws_s3_file_upload->aws_s3_file_upload($_FILES['thumbnail_url'], 'premium_videos/thumbnails');
//                } else {
//                    $insert_data['thumbnail_url'] = '';
//                }
//                if (!empty($_FILES['episode_url']['name'])) {
//                    $insert_data['episode_url'] = $this->aws_s3_file_upload->aws_s3_video_upload($_FILES['episode_url'], 'premium_videos/videos');
//                } else {
//                    $insert_data['episode_url'] = '';
//                }
//                $episode_id = $this->Premium_video_model->insert_episode($insert_data);
//                page_alert_box('success', 'Added', 'Episode added successfully');
//                redirect(AUTH_PANEL_URL . 'videos/premium_video/add_season');
//            }
//            redirect(AUTH_PANEL_URL . 'videos/premium_video/add_season_episode');
//        }
//    }

    public function episode_list($id,$cate) {
        $view_data['season_id'] = $id;
        $view_data['cate_id'] = $cate;
        $view_data['page'] = 'premium_season';
        $data['page_data'] = $this->load->view('premium_videos/episode_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function view_episode($id = null) {
        $id=$_GET['id'];
        $token=$_GET['token'];
        $data_id=$_GET['data_id'];
        $data_type=$_GET['data_type'];
        $view_data['episode_details'] = $this->Premium_video_model->get_episode_by_id($id);
        $view_data['page'] = 'premium_season';
        $data['page_data'] = $this->load->view('premium_videos/view_season_episode', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_episode($id) {
        $cat_id ='2';
        $view_data['episode_details'] = $this->Premium_video_model->get_episode_by_id($id);
        // $view_data['cid'] = $view_data['episode_details']['season_id'];
        // $view_data['season_id'] = $view_data['episode_details']['url_type'];

               $view_data['season_id'] = $id;
           $view_data['cid'] = $this->uri->segment('6');

      //  echo "<per>";print_r($view_data['episode_details']);die;
        $view_data['typeid'] = $view_data['episode_details'];
        $view_data['frame'] = $this->Premium_video_model->get_time_frames($id,$cat_id);
        $view_data['page'] = 'premium_season';
        $data['page_data'] = $this->load->view('premium_videos/edit_season_episode', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_season_episode() {
        if ($this->input->post()) {//pre($_POST); pre($_FILES); die;
            $this->form_validation->set_rules('season_id', 'Season', 'trim|required');
            $this->form_validation->set_rules('episode_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('episode_description', 'Title', 'trim|required');

           
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $id = $this->input->post('id');
                /*
                 * token is combined by three variables and combined by "_" underscore
                 * first one-primary id
                 * socond one-video type
                 * third-key //16 digit
                 */
                $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();

                $update_data = array(
                    'season_id' => $this->input->post('season_id'),
                    'url_type' => $this->input->post('url_type'),
                    'episode_url' =>  $this->input->post('movie_url'),
                    'episode_title' => $this->input->post('episode_title'),
                    'episode_description' => $this->input->post('episode_description'),
                    //'runtime' =>  $this->input->post('hours') . ":" . $this->input->post('minutes') . ":" . $this->input->post('seconds'),
                    'ep_no' => $this->input->post('ep_no'),
                   'release_date' => $this->input->post('released_date'),
                    'modified_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
                
                $season_id=$this->input->post('season_id');

              //  $this->Premium_video_model->update_episode($update_data, $id);

            if (!empty($_FILES['thumbnail_url']['name'])) {
                   $thumbnail1_url = $this->amazon_s3_upload($_FILES['thumbnail_url'], "episode_thumbnail_url/Thumbnails");
                     $update_data['thumbnail_url']=$thumbnail1_url;


                } 

            //    $m_url = $this->input->post('custom_movie_url');
            //     if (!empty($m_url)) {
                   
                
            //      if (!empty($_FILES['movie_url']['name']) || $this->input->post("custom_movie_url")) {
            //        //$movie_url = $this->amazon_s3_upload($_FILES['movie_url'], "movie_url/Movies");
                   
            //         $s3_files = $this->video_operation("movie_url");
            //         if ($s3_files) {
            //             $video_url=$movie_url = $s3_files['original'];
            //             $movie_url = convert_normal_to_m3u8($movie_url);
                        
            //             $movie_url = explode(".com/", $movie_url)[1];
            //             $url_json = $s3_files['encrypted_url'];
            //         }
            //     } 

            //     foreach ($url_json as $key => $value) {
            //         $url_json[$key]['url'] = aes_cbc_encryption($value['url'], $token);
            //     }
            //     $update_data = array(
            //         'episode_url' => aes_cbc_encryption($movie_url, $token),
            //         'encrypted_urls' => json_encode($url_json),
            //         'token' => $id . "_" . '0' . "_" . $token
            //     );
            // }
                //echo '<pre>'; print_r($update_dataa);echo '</pre>';
               //die;
                
                 $this->db->where('id',$id)->update('premium_episodes',$update_data);

                page_alert_box('success', 'Updated', 'Episode has been updated successfully');
                redirect(AUTH_PANEL_URL . 'videos/premium_video/episode_list/' . $season_id);
//                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

     public function time_frame()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('frame_type', 'FRAME', 'required');

            if ($this->form_validation->run() == FALSE) {
//                $view_data['add_premium_plan_display'] = 'block';
            } else {
                //  echo "2".'<pre>'; print_r($_POST); die;
                 /*
                 * token is combined by three variables and combined by "_" underscore
                 * first one-primary id
                 * socond one-video type
                 * third-key //16 digit
                 */
                 $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();
                $category ='2';
                $insert_data = array(
                    'web_series_id' => $this->input->post('id'),
                    'frame_type' => $this->input->post('frame_type'), 
                     'category_id' => $category,
                    'hrs' => $this->input->post('hours'),
                    'mins' => $this->input->post('minutes'),
                    'sec' => $this->input->post('seconds'),
                    'creation_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
               // echo '<pre>'; print_r($insert_data); die;
                $frame_id = $this->Premium_video_model->insert_frame($insert_data);
                if (!empty($_FILES['frame_type']['name'])) {
                   $frame_type = $this->amazon_s3_upload($_FILES['frame_type'], "frame_type/Advertisement");
                   $drm_encrypted_url = "file_library/videos/vod_drm_encrypted/$frame_id/";
                   
                    $s3_files = $this->video_operation("frame_type");
                    if ($s3_files) {
                        $video_url=$file = $s3_files['original'];
                        $file = convert_normal_to_m3u8($file);
//                        
                        $file = explode(".com/", $file)[1];
                        $url_json = $s3_files['encrypted_url'];
                    }
                } else {
                    $frame_type = '';
                }

                foreach ($url_json as $key => $value) {
                    $url_json[$key]['url'] = aes_cbc_encryption($value['url'], $token);
                }
                $update_data = array(
                    'add_url' => aes_cbc_encryption($file, $token),
                    'encrypted_urls' => json_encode($url_json),
                    'token' => $frame_id . "_" . '6' . "_" . $token
                );
                $this->db->where('id',$frame_id)->update('time_frame',$update_data);

                page_alert_box('success', 'Added', 'New Time Frame added successfully');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
    }

public function time_frame_delete($id)
{
    $delete_video = $this->Movies_model->delete_frame($id);
        page_alert_box('success', 'Time Frame deleted', 'Time Frame has been deleted successfully');
         redirect($_SERVER['HTTP_REFERER']);

}

    public function ajax_get_episode_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
         $id = $requestData['id'];
        $ctype = $requestData['ctype']; 
        $id = $requestData['id'];
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'title',
            2 => 'episode_title',
            3 => 'episode_description',
            4 => 'status',
            5 => 'ep_no'
        );

        $query = "SELECT count(id) as total
								From premium_episodes where status !=2 and season_id=$id 
								";
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        if($ctype=="2"){
             $sql = "SELECT ep.id,ep.season_id,ep.episode_title,ep.episode_description,ep.position,ep.status,ep.ep_no,ep.token,ps.title as season_title
                From premium_episodes as ep
                LEFT JOIN course_topic_file_meta_master as ps  ON ep.season_id = ps.id
                where ep.status !=2 and ps.id = $id
                "; 

                 if (!empty($requestData['columns'][1]['search']['value'])) {
                $sql .= " AND ps.title LIKE '" . '%' . $requestData['columns'][1]['search']['value'] . "%' ";
            }
            if (!empty($requestData['columns'][2]['search']['value'])) {
                $sql .= " AND ep.episode_title LIKE '" . '%' . $requestData['columns'][2]['search']['value'] . "%' ";
            }
             if (!empty($requestData['columns'][5 ]['search']['value'])) {
                $sql .= " AND ep.ep_no LIKE '" . '%' . $requestData['columns'][2]['search']['value'] . "%' ";
            }
            if (!empty($requestData['columns'][3]['search']['value'])) {
                $sql .= " AND ep.episode_description LIKE '" . '%' . $requestData['columns'][3]['search']['value'] . "%' ";
            }
            if (isset($requestData['columns'][4]['search']['value']) && $requestData['columns'][4]['search']['value'] != "") {  //salary
                $sql .= " AND ep.status = " . $requestData['columns'][4]['search']['value'];
            }
            $sql.=" order by ep.ep_no asc ";

        }
        else{
            $sql = "SELECT tv.id,tv.season_id,tv.episode_title,tv.episode_description,tv.position,tv.status,tv.ep_no,tv.token,ps.title as season_title
                From tv_serial_episodes as tv
                LEFT JOIN course_topic_file_meta_master as ps  ON tv.season_id = ps.id
                where tv.status !=2 and ps.id = $id
                "; 

                 if (!empty($requestData['columns'][1]['search']['value'])) {
                    $sql .= " AND tv.title LIKE '" . '%' . $requestData['columns'][1]['search']['value'] . "%' ";
                }
                if (!empty($requestData['columns'][2]['search']['value'])) {
                    $sql .= " AND tv.episode_title LIKE '" . '%' . $requestData['columns'][2]['search']['value'] . "%' ";
                }
             if (!empty($requestData['columns'][5 ]['search']['value'])) {
                $sql .= " AND tv.ep_no LIKE '" . '%' . $requestData['columns'][2]['search']['value'] . "%' ";
            }
            if (!empty($requestData['columns'][3]['search']['value'])) {
                $sql .= " AND tv.episode_description LIKE '" . '%' . $requestData['columns'][3]['search']['value'] . "%' ";
            }
            if (isset($requestData['columns'][4]['search']['value']) && $requestData['columns'][4]['search']['value'] != "") {  //salary
                $sql .= " AND tv.status = " . $requestData['columns'][4]['search']['value'];
            }
            $sql.=" order by tv.ep_no asc ";

        }

        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
        $sql .= "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
        $result = $this->db->query($sql)->result();
        $data = array();
        $start = 0;
        foreach ($result as $r) {
            $short_desc = $this->word_formatter($r->episode_description);
            $status = ($r->status == 0) ? 'Active' : 'Disabled';
            $alert_status = ($r->status == 0) ? 'success' : 'danger';
            // preparing an array
            $nestedData = array();
            $nestedData[] = ++$start;
            $nestedData[] = $r->season_title;
            $nestedData[] = $r->ep_no;
            $nestedData[] = $r->episode_title;
            $nestedData[] = $short_desc . "...";

             $nestedData[] ="<a class='btn-xs bold btn btn-$alert_status' onclick=\"return confirm('Are you sure you want to $status?')\" href='" . AUTH_PANEL_URL . "videos/premium_video/lock_unlock_episodes/" . $r->id . '/' . $r->status . '/'. $r->season_id . "'>$status</a>&nbsp;";
            $nestedData[] = "
            <a style = 'display: none;' class='btn-xs bold btn btn-success' title='View Video' href='" . AUTH_PANEL_URL . "videos/premium_video/view_episode?id=" . $r->id . "&data_id=".$r->id."&token=".$r->token."&data_type=2'><i class='fa fa-eye'></i></a>&nbsp;
         <a style = 'display: none;' class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . AUTH_PANEL_URL . "videos/premium_video/edit_episode/" . $r->id .'/' . $ctype . "'><i class='fa fa-edit'></i></a>&nbsp;
            <a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to Delete?')\" href='" . AUTH_PANEL_URL . "videos/premium_video/delete_episodes/" . $r->id . '/' . $r->season_id . "'><i class='fa fa-trash-o'></i></a>&nbsp;
				";

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

    public function lock_unlock_episodes($id, $status, $season_id) {
        if ($status == 0) {
            $header = 'Disable';
            $title = 'Episodes has been locked successfully';
        }
        if ($status == 1) {
            $header = 'Enable';
            $title = 'Episodes has been unlocked successfully';
        }
        $delete_videos = $this->Premium_video_model->lock_unlock_episodes($id, $status);
        page_alert_box('success', $header, $title);
       // redirect(AUTH_PANEL_URL . 'videos/premium_video/episode_list/' . $season_id .'/'. $id);
       redirect($_SERVER['HTTP_REFERER']);
    }

    public function delete_episodes($id, $season_id) { 
        $delete_season = $this->Premium_video_model->delete_episodes($id,$season_id);
        page_alert_box('success', 'Deleted', 'Episodes has been deleted successfully');
        redirect(AUTH_PANEL_URL . 'videos/premium_video/episode_list/' . $season_id . '/'. $id);
    }

    public function save_position_episodes() {

        $ids = $_POST['ids'];
        $counter = 1;
        foreach ($ids as $id) {
            $this->db->where('id', $id);
            $array = array('position' => $counter);
            $this->db->update('premium_episodes', $array);
            $counter++;
        }
        echo json_encode(array('status' => true, 'message' => 'position saved'));
        die;
    }




//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX--->EPISODES BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

private function cleardir() {

        $folder_path = "resize-image";

// List of name of files inside
        // specified folder
        $files = glob($folder_path . '/*');

// Deleting all the files in the list
        foreach ($files as $file) {

            if (is_file($file)) {

                // Delete the given file
                unlink($file);
            }
        }
    }
    
    // public function ajax_generate_video_url($type=2)
    // {
    //     $url = base_url("/data_model/live_modules/on_request_create_video_link");
    //     $document = ['file_url' => $url, 'name' => $_POST['token'],'type'=>$type];
    //     $res=file_curl_contents($document);
    //     $type='application/x-mpegURL';
    //     $video_type=$_POST['video_type'];
    //     $token= $_POST['token'];
    //     $video =$res['data'];
    //     $token_str=explode("_",$token);
    //     $token=end($token_str);
    //     $bucket_path='https://'.AMS_BUCKET_NAME.'.s3.'.AMS_REGION.'.amazonaws.com/';
    //     if($video_type==1)
    //     {
    //         $type='video/youtube';
    //         $video_url=aes_cbc_decryption($video,$token);
    //     }
    //     else
    //     {
    //         $type='application/x-mpegURL';
    //         $video_url=aes_cbc_decryption($video,$token);
    //     }
    //     echo json_encode(['status'=>true,'message'=>'url listed.','data'=>['url'=>$video_url,'type'=>$type]]);
    //     die;
    // }
    
    public function get_request_for_csv_download($device_type="") {
        $this->ajax_premium_plan_list($device_type);
    }
    
     public function get_season_for_csv_download($device_type="") {
        $this->ajax_get_season_list($device_type);
    }


    public function all_premium_video_to_csv_download($array, $filename = "Users.csv", $delimiter = ";", $header) {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        $f = fopen('php://output', 'w');
        fputcsv($f, $header);
        foreach ($array as $line) {
            fputcsv($f, $line);
        }
    }
    
    

}




