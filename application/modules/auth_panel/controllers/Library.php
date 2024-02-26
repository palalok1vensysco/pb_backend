<?php

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

defined('BASEPATH') OR exit('No direct script access allowed');

class Library extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library(['form_validation', 'upload', 's3_upload']);
        $this->load->model("Library_model");
        $this->load->helper(['aul', 'aes', 'custom']);
    }

    public function amazon_s3_upload($name, $aws_path) {

        $_FILES['file'] = $name;
        $type = $name['type'];
        //print_r($type);die;
        require_once(FCPATH . 'aws/aws-autoloader.php');
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
            'ContentType' => $type,
            'ACL' => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'Metadata' => array('param1' => 'value 1', 'param2' => 'value 2')
        ));
        $data = $result->toArray();
        return $data['ObjectURL'];
    }


    public function add_image() {
        if ($this->input->post()) {
            $user_data = $this->session->userdata('active_user_data');
            $backend_user_id = $user_data->id;

            if (empty($_FILES['image_file']['name'])) {
                $this->form_validation->set_rules('image_file', 'Image File', 'required');
            }

            if ($this->form_validation->run() == FALSE) {
                
            } else {
                if (!empty($_FILES['image_file']['name'])) {
                    $file = $this->amazon_s3_upload($_FILES['image_file'], "course_file_meta");
                } else {
                    $file = '';
                }

                $insert_data = array(
                    'file_url' => $file,
                    'backend_user_id' => $backend_user_id,
                    'created'=> time()
                );

                $this->db->insert('course_topic_file_meta_master', $insert_data);
                page_alert_box('success', 'Action performed', 'File added successfully');
            }
        }

        $view_data['page'] = 'add_image';
        $data['page_title'] = "Add Image";
        $data['page_data'] = $this->load->view('library/add_image', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_image_file_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $user_data = $this->session->userdata('active_user_data');
        $instructor_id = $user_data->instructor_id;
        $backend_user_id = $user_data->id;
        $where = "";
        if ($instructor_id != 0) {
            $where = "AND ctfmm.backend_user_id = $backend_user_id";
        }

        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            2 => 'URL',
        );

        $query = "SELECT count(ctfmm.id) as total
								FROM course_topic_file_meta_master ctfmm where ctfmm.file_type =6 $where
								";
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT ctfmm.id as id,ctfmm.file_url as URL FROM course_topic_file_meta_master as  ctfmm $where";

        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " having URL LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }




        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();
        $data = array();
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();

            $nestedData[] = $r->id;
            $nestedData[] = "<img  height = '60' width ='60' src= " . $r->URL . ">";
            $action = "<a class='btn-sm btn btn-success btn-xs bold' href='" . AUTH_PANEL_URL . "library/library/edit_image_library/" . $r->id . "'>Edit</a>";
            $action .= "<a class=' pull-right btn-sm btn btn-success btn-xs bold copy_url' data-url='" . $r->URL . "' >Copy Url </a>";
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

    public function edit_image_library($id) {
        if ($this->input->post()) {

            /* 		if (empty($_FILES['image_file']['name']))
              {
              $this->form_validation->set_rules('image_file', 'File', 'required');
              }
             */
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $update_data = array();
                if (!empty($_FILES['image_file']['name'])) {
                    $file = $this->amazon_s3_upload($_FILES['image_file'], "course_file_meta");

                    $update_data['file_url'] = $file;
                }

                $file_id = $this->input->post('id');

                $this->db->where('id', $file_id);
                $this->db->update('course_topic_file_meta_master', $update_data);
                page_alert_box('success', 'Action performed', 'File updated successfully');
            }
        }
        $view_data['page'] = 'edit_image';
        $data['page_title'] = "Edit Image ";
        $view_data['video_detail'] = $this->Library_model->get_library_file_by_id($id);
        $data['page_data'] = $this->load->view('library/edit_image', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

}
