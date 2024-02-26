<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Video_control extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->load->library('aws_s3_file_upload');
        $this->load->helper('services');
        $this->load->model("Video_control_model");
        $this->load->model("guru_model");
        $this->load->helper('cookie');
    }

    public function upload_file($key) {
        if ($_FILES) {
            if ($_FILES[$key]["size"] > 1000000 * 500) {
                return array('status' => false, 'message' => 'Sorry, your file is too large. size should below 500mb');
            }
            if ($key == 'video_url') {
                $file_path = $_SERVER['DOCUMENT_ROOT'] . '/' . CONFIG_PROJECT_DIR_NAME . '/uploads/videos/' . $_FILES[$key]["name"];
                $file_url = base_url() . 'uploads/videos/' . $_FILES[$key]["name"];
            }
            if ($key == 'thumbnail_url') {
                $file_path = $_SERVER['DOCUMENT_ROOT'] . '/' . CONFIG_PROJECT_DIR_NAME . '/uploads/thumbnail/' . $_FILES[$key]["name"];
                $file_url = base_url() . 'uploads/thumbnail/' . $_FILES[$key]["name"];
            }
            if (move_uploaded_file($_FILES[$key]["tmp_name"], $file_path)) {
                return $file_url;
            } else {
                return array('status' => false, 'message' => 'Server issue not able to upload file.');
            }
        } else {
            return array('status' => false, 'message' => 'Not able to upload file.');
        }
    }

    public function video_list() {
        $data['page_title'] = "Videos List";
        $view_data['page'] = 'video_list';
        $view_data['updated_at'] = $this->db->where('id',1)->get('video_meta')->row()->updated_at;
        $view_data['videos'] = $this->Video_control_model->get_video_list();
        $view_data['category'] = $this->Video_control_model->get_category();
        $data['page_data'] = $this->load->view('videos/video_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function add_video() {
        $this->cleardir();
        //$aws='https://s3.ap-south-1.amazonaws.com/bhaktiappproduction/';
        if ($this->input->post()) {
            $this->form_validation->set_rules('video_title', 'Video title', 'required');
            $this->form_validation->set_rules('video_desc', 'Video description', 'required');
            $this->form_validation->set_rules('category[]', 'Category', 'required');
            $this->form_validation->set_rules('days[]', 'Days', 'trim');
            $this->form_validation->set_rules('tags', 'Tags', 'required');
            $this->form_validation->set_rules('published_date', 'published_date', 'required');

            if (empty($_FILES['thumbnail_url']['name'])) {
                $this->form_validation->set_rules('thumbnail_url', 'Thumbnail', 'required');
            }
            // if (empty($_FILES['thumbnail_url1']['name'])) {
            // 	$this->form_validation->set_rules('thumbnail_url1', 'Thumbnail', 'required');
            // }

            if ($this->form_validation->run() == FALSE) {
                
            } else {
                //video file
                if (!empty($_FILES['video_url']['name'])) {
                    $video_url = $this->aws_s3_file_upload->aws_s3_video_upload($_FILES['video_url'], 'videos/video');
                    // print_r($video_url);exit;
                } else {
                    $video_url = '';
                }
                if (!empty($_FILES['thumbnail_url']['name'])) {

//					$file_name = time() . $_FILES['thumbnail_url']['name'];
//					$upload_dir = "resize-image/";
//					$upload_file = $upload_dir . $file_name;
//
//					if (move_uploaded_file($_FILES['thumbnail_url']['tmp_name'], $upload_file)) {
//						$source_image = $upload_file;
//						$image_destination = $upload_dir . "min-" . $file_name;
//						$width = 280;
//						$height = 150;
//						$quality = 90;
//						$listimage = $this->compress_image($source_image, $image_destination, $width, $height, $quality);
//
//						$source_image1 = $upload_file;
//						$image_destination1 = $upload_dir . "max-" . $file_name;
//						$width1 = 170;
//						$height1 = 90;
//						$quality1 = 90;
//						$listimage1 = $this->compress_image($source_image1, $image_destination1, $width1, $height1, $quality1);
//					}
//					$path1 = $_SERVER["DOCUMENT_ROOT"] .'/'.CONFIG_PROJECT_DIR_NAME. "/resize-image/min-" . $file_name;
//					$_FILES['thumbnail_url'] = array(
//						'name' => 'min-' . $file_name,
//						'tmp_name' => $path1,
//					);

                    $thumbnail_url = $this->aws_s3_file_upload->aws_s3_file_upload($_FILES['thumbnail_url'], 'videos/thumbnails');
//					$path1 = $_SERVER["DOCUMENT_ROOT"] .'/'.CONFIG_PROJECT_DIR_NAME. "/resize-image/max-" . $file_name;
//					$_FILES['thumbnail_url'] = array(
//						'name' => 'max-' . $file_name,
//						'tmp_name' => $path1,
//					);

                    $thumbnail_url1 = $this->aws_s3_file_upload->aws_s3_file_upload($_FILES['thumbnail_url'], 'videos/thumbnails');
                } else {
                    $thumbnail_url1 = $thumbnail_url = '';
                }

                $category = implode(",", $this->input->post('category'));
                $days = implode(",", $this->input->post('days'));
                if ($this->input->post('is_popular') != '') {
                    $is_popular = $this->input->post('is_popular');
                } else {
                    $is_popular = 0;
                }
                if ($this->input->post('related_guru') != '') {
                    $related_guru = implode(",", $this->input->post('related_guru'));
                } else {
                    $guru_name = $this->Video_control_model->get_default_guru();
                    $related_guru = $guru_name['id'];
                }
                $insert_data = array(
                    'mobile_menu_ids' => (isset($_POST['mobile_menu_ids']) && !empty($_POST['mobile_menu_ids']) ? implode(',', $_POST['mobile_menu_ids']) : ''),
                    'android_tv_ids' => (isset($_POST['android_tv_ids']) && !empty($_POST['android_tv_ids']) ? implode(',', $_POST['android_tv_ids']) : ''),
                    'video_title' => $this->input->post('video_title'),
                    'video_url' => $video_url,
                    'youtube_url' => $this->input->post('youtube_url'),
                    'video_desc' => $this->input->post('video_desc'),
                    'author_name' => $this->input->post('author_name'),
                    'thumbnail_url' => $thumbnail_url,
                    'thumbnail_url1' => $thumbnail_url1,
                    'published_date' => (strtotime($_POST['published_date']) * 1000),
                    'category' => $category,
                    'days' => $days,
                    'related_guru' => $related_guru,
                    'tags' => $this->input->post('tags'),
                    'is_sankirtan' => $this->input->post('is_sankirtan'),
                    'is_popular' => $is_popular,
                    'uploaded_by' => $this->session->userdata('active_backend_user_id'),
                    'creation_time' => milliseconds(),
                );

                // echo '<pre>'; echo $thumbnail_url.','.$video_url; die;
                $id = $this->Video_control_model->insert_video($insert_data);
                page_alert_box('success', 'Video Added', 'New video added successfully');
                redirect(BASE_URL . 'admin-panel/video-list');
//				redirect(AUTH_PANEL_URL . 'videos/video_control/add_video');
            }
        }
        $view_data['mobile_menu_category'] = $this->Video_control_model->get_mobile_menu_category();
        $view_data['android_tv_category'] = $this->Video_control_model->get_android_tv_category();
        $view_data['category'] = $this->Video_control_model->get_category();
        $view_data['week_days'] = $this->Video_control_model->get_week_days();
        $view_data['guru'] = $this->guru_model->get_guru_list();
        $view_data['page'] = 'add_video';
        $data['page_data'] = $this->load->view('videos/add_video', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_video($id) {
        $this->cleardir();
        if ($this->input->post()) {
            $this->form_validation->set_rules('video_title', 'Video title', 'required');
            $this->form_validation->set_rules('video_desc', 'Video description', 'required');
            $this->form_validation->set_rules('published_date', 'published_date', 'required');
            $this->form_validation->set_rules('tags', 'Tags', 'required');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                if (!empty($_FILES['video_url']['name'])) {
                    $video_url = $this->aws_s3_file_upload->aws_s3_video_upload($_FILES['video_url'], 'videos/video');
                } else {
                    $video_url = $this->input->post('pre_video_url');
                }
                if (!empty($_FILES['thumbnail_url']['name'])) {
//					$file_name = time() . $_FILES['thumbnail_url']['name'];
//					$upload_dir = "resize-image/";
//					$upload_file = $upload_dir . $file_name;
//
//					if (move_uploaded_file($_FILES['thumbnail_url']['tmp_name'], $upload_file)) {
//						$source_image = $upload_file;
//						$image_destination = $upload_dir . "min-" . $file_name;
//						$width = 280;
//						$height = 150;
//						$quality = 90;
//						$listimage = $this->compress_image($source_image, $image_destination, $width, $height, $quality);
//
//						$source_image1 = $upload_file;
//						$image_destination1 = $upload_dir . "max-" . $file_name;
//						$width1 = 170;
//						$height1 = 90;
//						$quality1 = 90;
//						$listimage1 = $this->compress_image($source_image1, $image_destination1, $width1, $height1, $quality1);
//					}
//					$path1 = $_SERVER["DOCUMENT_ROOT"] .'/'.CONFIG_PROJECT_DIR_NAME. "/resize-image/min-" . $file_name;
//					$_FILES['thumbnail_url'] = array(
//						'name' => 'min-' . $file_name,
//						'tmp_name' => $path1,
//					);
//
                    $thumbnail_url = $this->aws_s3_file_upload->aws_s3_file_upload($_FILES['thumbnail_url'], 'videos/thumbnails');
//
//					$path2 = $_SERVER["DOCUMENT_ROOT"] .'/'.CONFIG_PROJECT_DIR_NAME. "/resize-image/max-" . $file_name;
//					$_FILES['thumbnail_url'] = array(
//						'name' => 'min-' . $file_name,
//						'tmp_name' => $path2,
//					);

                    $thumbnail_url1 = $this->aws_s3_file_upload->aws_s3_file_upload($_FILES['thumbnail_url'], 'videos/thumbnails');
                } else {
                    $thumbnail_url = $this->input->post('pre_thumbnail_url');
                    $thumbnail_url1 = $this->input->post('pre_thumbnail_url1');
                }

                $category = implode(",", $this->input->post('category'));
                if ($this->input->post('is_popular') != '') {
                    $is_popular = $this->input->post('is_popular');
                } else {
                    $is_popular = 0;
                }
                if ($this->input->post('related_guru') != '') {
                    $related_guru = implode(",", $this->input->post('related_guru'));
                } else {
                    $guru_name = $this->Video_control_model->get_default_guru();
                    $related_guru = $guru_name['id'];
                }
                $update_data = array(
                    'id' => $this->input->post('id'),
                    'mobile_menu_ids' => (isset($_POST['mobile_menu_ids']) && !empty($_POST['mobile_menu_ids']) ? implode(',', $_POST['mobile_menu_ids']) : ''),
                    'android_tv_ids' => (isset($_POST['android_tv_ids']) && !empty($_POST['android_tv_ids']) ? implode(',', $_POST['android_tv_ids']) : ''),
                    'video_title' => $this->input->post('video_title'),
                    'video_url' => $video_url,
                    'youtube_url' => $this->input->post('youtube_url'),
                    'video_desc' => $this->input->post('video_desc'),
                    'author_name' => $this->input->post('author_name'),
                    'thumbnail_url' => $thumbnail_url,
                    'thumbnail_url1' => $thumbnail_url1,
                    'published_date' => (strtotime($_POST['published_date']) * 1000),
                    'category' => $category,
                    'related_guru' => $related_guru,
                    'tags' => $this->input->post('tags'),
                    'is_sankirtan' => $this->input->post('is_sankirtan'),
                    'is_popular' => $is_popular,
                    'uploaded_by' => $this->session->userdata('active_backend_user_id'),
                    'creation_time' => milliseconds(),
                );
                $updated = $this->Video_control_model->update_video($update_data);
                page_alert_box('success', 'Video Updated', 'Video updated successfully');
                redirect(BASE_URL . 'admin-panel/video-list');
            }
        }
        $view_data['page'] = "edit_video";
        $view_data['mobile_menu_category'] = $this->Video_control_model->get_mobile_menu_category();
        $view_data['android_tv_category'] = $this->Video_control_model->get_android_tv_category();
        $view_data['category'] = $this->Video_control_model->get_category();
        $view_data['guru'] = $this->guru_model->get_guru_list();
        $view_data['video'] = $this->Video_control_model->get_video_by_id($id);
        $data['page_data'] = $this->load->view('videos/edit_video', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function get_request_for_csv_download() {
        $this->ajax_all_video_list();
    }

    public function ajax_all_video_list() {
        // storing  request (ie, get/post) global array to a variable
        $output_csv = false;
        $requestData = $_REQUEST;
        if (isset($_POST['input_json'])) {
            $requestData = json_decode($_POST['input_json'], true);
            $output_csv = true;
        }
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'video_title',
            2 => 'author_name',
            3 => 'video_desc',
            4 => 'thumbnail_url',
            5 => 'likes',
            6 => 'views',
//			7 => 'creation_time',
            8 => 'is_sankirtan',
            9 => 'is_popular',
            10 => 'published_date'
        );

        $query = "SELECT count(id) as total
				  FROM video_master where status=0";

        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT id,DATE_FORMAT(FROM_UNIXTIME(creation_time/1000), '%d-%m-%Y') as creation_time,video_title,video_desc,likes,youtube_likes,views,youtube_views,thumbnail_url,is_sankirtan,is_popular,author_name,published_date,category,youtube_url
				FROM video_master where status=0";

        // getting records as per search parameters

        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND video_title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][2]['search']['value'])) {
            $sql .= " AND author_name LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $sql .= " AND video_master.video_desc LIKE '" . '%' . $requestData['columns'][3]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][4]['search']['value'])) {
            $cat_id = $requestData['columns'][4]['search']['value'];
            $sql .= " AND FIND_IN_SET($cat_id,video_master.category) ";
        }
//        if (!empty($requestData['columns'][4]['search']['value'])) {
//            if ($requestData['columns'][4]['search']['value'] == 1) {
//                $sql .= " AND is_sankirtan=1";
//            }
//            if ($requestData['columns'][4]['search']['value'] == 2) {
//                $sql .= " AND is_sankirtan=0";
//            }
//            if ($requestData['columns'][4]['search']['value'] == '') {
//                $sql .= " AND is_sankirtan=0";
//            }
//        }
        //if (!empty($requestData['columns'][5]['search']['value'])) {
        //$sql .= " AND is_popular LIKE '" . $requestData['columns'][5]['search']['value'] . "%' ";
        //}

        if (!empty($requestData['columns'][5]['search']['value'])) {
            if ($requestData['columns'][5]['search']['value'] == 1) {
                $sql .= " AND is_popular=1";
            }
            if ($requestData['columns'][5]['search']['value'] == 2) {
                $sql .= " AND is_popular=0";
            }
            if ($requestData['columns'][5]['search']['value'] == '') {
                $sql .= " AND is_popular=0";
            }
        }

        if (!empty($requestData['columns'][10]['search']['value'])) {  //salary
            $date = explode(',', $requestData['columns'][10]['search']['value']);
            $start = $date[0];
            $end = $date[1];
            $sql .= "  AND  published_date >= '$start' and published_date <= '$end'";
        }


        $query = $this->db->query($sql)->result();
        //print_r($columns);
        //echo $this->db->last_query();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
        //$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
        if ($output_csv == false) {
            $sql .= " ORDER BY published_date desc";
            $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } else {
            $sql .= " ORDER BY video_master.published_date desc";
        }


        $result = $this->db->query($sql)->result();
        //echo $this->db->last_query();

        $data = array();

        if ($output_csv == true) {
            // for csv loop
            $head = array('S.no', 'Title', 'Author', 'Most Popular', 'Video URL', 'Publish Date');
            $start = 0;
            foreach ($result as $r) {
                $nestedData = array();
                $nestedData[] = ++$start;
                $nestedData[] = $r->video_title;
                $nestedData[] = $r->author_name;
                $nestedData[] = ($r->is_popular == '1') ? 'Yes' : 'No';
                $nestedData[] = "https://www.youtube.com/embed/$r->youtube_url";
                $nestedData[] = $r->published_date;
                $data[] = $nestedData;
            }
            if ($output_csv == true) {
                $this->all_video_to_csv_download($data, $filename = "Video.csv", $delimiter = ";", $head);
                die;
            }
        }

        foreach ($result as $r) {
            // preparing an array
            $short_desc = $this->word_formatter($r->video_desc);
            if ($r->is_sankirtan == '1') {
                $is_sankirtan = 'Sankirtan';
            }
            if ($r->is_sankirtan == '0') {
                $is_sankirtan = 'Normal Video';
            }
            if ($r->is_popular == '1') {
                $is_popular = 'Most Popular';
            }
            if ($r->is_popular == '0') {
                $is_popular = 'Normal';
            }
            if ($r->category != '0') {
                $category_name = $this->db->where('id', $r->category)->get('video_category')->row()->category_name;
            } else {
                $category_name = 'Normal Video';
            }
            $nestedData = array();
            $nestedData[] = "<input name='selected_id' type='checkbox' value='$r->id'/>&nbsp&nbsp" . ++$requestData['start'];
//			$nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->video_title;
            $nestedData[] = $r->author_name;
//			$nestedData[] = $r->video_desc;
            $nestedData[] = $short_desc . "...";
            $nestedData[] = "<img width='50px' height='50px' src='" . $r->thumbnail_url . "'></a>";
            $website_video_url = base_url() . "video/$r->id";
            $nestedData[] = "<a class='btn-xs bold btn btn-info preview' youtube_url='$r->youtube_url'>Preview</a> &nbsp;"
                    . "<a class='btn-lg btn bold copy_url' data-url='" . $website_video_url . "' ><i class='fa fa-copy'></i> </a>";
//            $nestedData[] = $is_sankirtan;
            $nestedData[] = $category_name;
            $nestedData[] = $is_popular;
            $views=convert_number_to_text($r->views);
            $likes=convert_number_to_text($r->likes);
            $youtube_views=convert_number_to_text($r->youtube_views);
            $youtube_likes=convert_number_to_text($r->youtube_likes);
            $nestedData[] = "<span class='btn-xs bold btn btn-success'><i class='fa fa-eye'></i> " . $views . "</span></br><span class='btn-xs bold btn btn-success' style='margin-top: 6%;'><i class='fa fa-youtube'></i> " . $youtube_views . "</span>";
            $nestedData[] = "<span class='btn-xs bold btn btn-info'><i class='fa fa-thumbs-up'></i> " . $likes . "</span></br><span class='btn-xs bold btn btn-info' style='margin-top: 6%;'><i class='fa fa-youtube'></i> " . $youtube_likes . "</span>";
//			$nestedData[] = $r->creation_time;
            $nestedData[] = date("d-m-Y h:i:s A", $r->published_date / 1000);
            $nestedData[] = "
            <a class='btn-xs bold btn btn-success' title='View Video' href='" . base_url('admin-panel/view-video/') . $r->id . "'><i class='fa fa-eye'></i></a>&nbsp;
            <a class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . base_url('admin-panel/edit-video/') . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;
				<a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "videos/video_control/delete_video/" . $r->id . "'><i class='fa fa-trash-o'></i></a>&nbsp;
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

    public function all_video_to_csv_download($array, $filename = "Video.csv", $delimiter = ";", $header) {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        $f = fopen('php://output', 'w');
        fputcsv($f, $header);
        foreach ($array as $line) {
            fputcsv($f, $line);
        }
    }

    public function view_video($id) {
//        $view_data['mobile_menu_category'] = $this->Video_control_model->get_mobile_menu_category();
//        $view_data['android_tv_category'] = $this->Video_control_model->get_android_tv_category();
        $view_data['video'] = $this->Video_control_model->get_video_by_id($id);
        $data['page_data'] = $this->load->view('videos/view_video', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function add_category() {
        if ($this->input->post()) {
            //echo '<pre>'; print_r($_FILES); die;
            $this->form_validation->set_rules('category_name', 'Category', 'required');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                //video file

                $insert_data = array(
                    'category_name' => $this->input->post('category_name'),
                    'creation_time' => milliseconds(),
                );
                //echo '<pre>'; echo $thumbnail_url.','.$video_url; die;
                $id = $this->Video_control_model->insert_category($insert_data);
                page_alert_box('success', 'Video Category Added', 'New Category added successfully');
                redirect(BASE_URL . 'admin-panel/video-category-list');
//				redirect(AUTH_PANEL_URL . 'videos/video_control/add_category');
            }
        }
        $view_data['page'] = 'video_category';
        $data['page_data'] = $this->load->view('videos/add_category', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_category($id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('category_name', 'Category Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $update_data = array(
                    'id' => $this->input->post('id'),
                    'category_name' => $this->input->post('category_name'),
                    'creation_time' => milliseconds(),
                );
                //echo '<pre>'; print_r($update_data); die;
                $update = $this->Video_control_model->update_category($update_data);
                page_alert_box('success', 'Category Updated', 'Category has been updated successfully');
                redirect(BASE_URL . 'admin-panel/video-category-list');
//				redirect(AUTH_PANEL_URL . 'videos/video_control/category_list');
            }
        }
        $view_data['page'] = "edit_category";
        $view_data['category'] = $this->Video_control_model->get_category_by_id($id);
        $data['page_data'] = $this->load->view('videos/edit_category', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function category_list() {
        $data['page_title'] = "Category List";
        $view_data['page'] = 'video_category_list';
        $view_data['category'] = $this->Video_control_model->get_category();
        $data['page_data'] = $this->load->view('videos/category_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_category_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'category_name',
            2 => 'creation_time',
        );

        $query = "SELECT count(id) as total
				  FROM video_category where status=0";

        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT id,DATE_FORMAT(FROM_UNIXTIME(creation_time/1000), '%d-%m-%Y') as creation_time,category_name
				FROM video_category where status=0";

        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND category_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length

        $result = $this->db->query($sql)->result();

        $data = array();

        foreach ($result as $r) {
            // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = ucfirst($r->category_name);
            $nestedData[] = $r->creation_time;
            $nestedData[] = "<a class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . base_url('admin-panel/video-edit-category/') . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;
				<a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "videos/video_control/delete_video_category/" . $r->id . "'><i class='fa fa-trash-o'></i></a>&nbsp;
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

    public function delete_video_category($id) {
        $delete_user = $this->Video_control_model->delete_video_category($id);
        page_alert_box('success', 'Video Category Deleted', 'Video Category has been deleted successfully');
        redirect(BASE_URL . 'admin-panel/video-category-list');
//		redirect(AUTH_PANEL_URL . 'videos/video_control/category_list');
    }

    public function delete_video($id) {
        $delete_video = $this->Video_control_model->delete_video($id);
        page_alert_box('success', 'Video deleted', 'Video has been deleted successfully');
        redirect(BASE_URL . 'admin-panel/video-list');
//		redirect(AUTH_PANEL_URL . 'videos/video_control/video_list');
    }

    function compress_image($source_file, $target_file, $nwidth, $nheight, $quality) {
        //Return an array consisting of image type, height, widh and mime type.
        $image_info = getimagesize($source_file);
        if (!($nwidth > 0)) {
            $nwidth = $image_info[0];
        }

        if (!($nheight > 0)) {
            $nheight = $image_info[1];
        }

        if (!empty($image_info)) {
            switch ($image_info['mime']) {
                case 'image/jpeg':
                    if ($quality == '' || $quality < 0 || $quality > 100) {
                        $quality = 75;
                    }
                    //Default quality
                    // Create a new image from the file or the url.
                    $image = imagecreatefromjpeg($source_file);
                    $thumb = imagecreatetruecolor($nwidth, $nheight);
                    //Resize the $thumb image
                    imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
                    //Output image to the browser or file.
                    return imagejpeg($thumb, $target_file, $quality);

                    break;

                case 'image/png':
                    if ($quality == '' || $quality < 0 || $quality > 9) {
                        $quality = 6;
                    }
                    //Default quality
                    // Create a new image from the file or the url.
                    $image = imagecreatefrompng($source_file);
                    $thumb = imagecreatetruecolor($nwidth, $nheight);
                    //Resize the $thumb image
                    imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
                    // Output image to the browser or file.
                    return imagepng($thumb, $target_file, $quality);
                    break;

                case 'image/gif':
                    if ($quality == '' || $quality < 0 || $quality > 100) {
                        $quality = 75;
                    }
                    //Default quality
                    // Create a new image from the file or the url.
                    $image = imagecreatefromgif($source_file);
                    $thumb = imagecreatetruecolor($nwidth, $nheight);
                    //Resize the $thumb image
                    imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
                    // Output image to the browser or file.
                    return imagegif($thumb, $target_file, $quality); //$success = true;
                    break;

                default:
                    echo "<h4>File type not supported!</h4>";
                    break;
            }
        }
    }

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

    public function home_video() {

        if (!empty($_FILES)) {

            //video file
            if (!empty($_FILES['video']['name'])) {
                $video_url = $this->aws_s3_file_upload->aws_s3_video_upload($_FILES['video'], 'videos/video');
                // print_r($video_url);exit;
            } else {
                $video_url = '';
            }

            $insert_data = array(
                'video_url' => $video_url,
                'creation_time' => milliseconds(),
            );

            // echo '<pre>'; echo $video_url; die;
            //db home_video

            $id = $this->Video_control_model->insert_home_video($insert_data);
            page_alert_box('success', 'Video Added', 'New video added successfully');
            redirect(AUTH_PANEL_URL . 'videos/video_control/home_video');
        }
        $view_data = '';
        $data['page_data'] = $this->load->view('videos/home_video', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function add_multiple_videos() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('video_id', 'Video', 'trim|required');
            $this->form_validation->set_rules('days', 'Days', 'trim|required');
//             for($j=1;$j<=$this->input->post('days');$j++){
//                 $this->form_validation->set_rules('video_title_'.$j, 'Video title '.$j, 'trim|required');
//             }
            if ($this->form_validation->run() == FALSE) {
                $view_data['validation_error'] = '1';
            } else {
                $days = $this->input->post('days');
                $insert_data = array('video_id' => $this->input->post('video_id'),
                    'days' => $this->input->post('days'),
                    'created' => time()
                );
                for ($i = 1; $i <= $days; $i++) {
                    if (!empty($_FILES['video_url_' . $i]['name'])) {
                        $video_url = $this->aws_s3_file_upload->aws_s3_video_upload($_FILES['video_url_' . $i], 'videos/video');
                    } else {
                        $video_url = '';
                    }
                    if (!empty($_FILES['thumbnail_url_' . $i]['name'])) {

                        $file_name = time() . $_FILES['thumbnail_url_' . $i]['name'];
                        $upload_dir = "resize-image/";
                        $upload_file = $upload_dir . $file_name;

                        if (move_uploaded_file($_FILES['thumbnail_url_' . $i]['tmp_name'], $upload_file)) {
                            $source_image = $upload_file;
                            $image_destination = $upload_dir . "min-" . $file_name;
                            $width = 280;
                            $height = 150;
                            $quality = 90;
                            $listimage = $this->compress_image($source_image, $image_destination, $width, $height, $quality);

                            $source_image1 = $upload_file;
                            $image_destination1 = $upload_dir . "max-" . $file_name;
                            $width1 = 170;
                            $height1 = 90;
                            $quality1 = 90;
                            $listimage1 = $this->compress_image($source_image1, $image_destination1, $width1, $height1, $quality1);
                        }
                        $path1 = $_SERVER["DOCUMENT_ROOT"] . '/' . CONFIG_PROJECT_DIR_NAME . "/resize-image/min-" . $file_name;
                        $_FILES['thumbnail_url_' . $i] = array(
                            'name' => 'min-' . $file_name,
                            'tmp_name' => $path1,
                        );

                        $thumbnail_url = $this->aws_s3_file_upload->aws_s3_file_upload($_FILES['thumbnail_url_' . $i], 'videos/thumbnails');
                        $path1 = $_SERVER["DOCUMENT_ROOT"] . '/' . CONFIG_PROJECT_DIR_NAME . "/resize-image/max-" . $file_name;
                        $_FILES['thumbnail_url_' . $i] = array(
                            'name' => 'max-' . $file_name,
                            'tmp_name' => $path1,
                        );
                    } else {
                        $thumbnail_url = '';
                    }
//                 $day = array('day_'.$i => $this->input->post('day_'.$i),
//                                     'video_title_'.$i => $this->input->post('video_title_'.$i),
//                                     'thumbnail_url_'.$i => $thumbnail_url,
//                                     'video_url_'.$i => $video_url,
//                                     'youtube_url_'.$i => $this->input->post('youtube_url_'.$i),
//                                     'creation' => time()
//                                      );
                    $day = array('day' => 'day ' . $i,
                        'video_title' => $this->input->post('video_title_' . $i),
                        'thumbnail_url' => $thumbnail_url,
                        'video_url' => $video_url,
                        'youtube_url' => $this->input->post('youtube_url_' . $i)
                    );
                    $insert_data['day_' . $i] = json_encode($day);
                }
                $id = $this->Video_control_model->insert_multiple_video($insert_data);
                page_alert_box('success', 'Video Added', 'video added successfully');
                redirect(AUTH_PANEL_URL . 'videos/video_control/add_multiple_videos');
            }
        }
        $view_data['videos'] = $this->Video_control_model->added_videos();
        // $view_data['categories']   = $this->Video_control_model->get_category();
        $view_data['page'] = 'multiple_video';
        $data['page_data'] = $this->load->view('videos/add_multiple_videos', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_get_videos_by_category() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;

        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'video_title',
            2 => 'author_name',
            3 => 'days'
        );

        $query = "SELECT count(id) as total
								FROM multiple_videos where status=0 
								";
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT multiple_videos.id as id,multiple_videos.days as days,multiple_videos.created as created,video_master.video_title as title,video_master.author_name FROM multiple_videos
                        JOIN video_master  ON multiple_videos.video_id = video_master.id
                        where multiple_videos.status=0
                        ";

        // getting records as per search parameters
//		if (!empty($requestData['columns'][0]['search']['value'])) {
//			//name
//			$sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
//		}
        if (!empty($requestData['columns'][1]['search']['value'])) {
            //salary
            $sql .= " AND title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            //salary
            $sql .= " AND author_name LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            //salary
            $sql .= " AND days LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length

        $result = $this->db->query($sql)->result();
        $data = array();
        foreach ($result as $r) {
            // preparing an array
            $nestedData = array();

            $nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->title;
            $nestedData[] = $r->author_name;
            $nestedData[] = $r->days;
            $nestedData[] = date('d/M/Y', $r->created);
            $nestedData[] = "<a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "videos/video_control/delete_multiple_videos/" . $r->id . "'><i class='fa fa-trash-o'></i></a>&nbsp; ";
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

    public function delete_multiple_videos($id) {
        $delete_videos = $this->Video_control_model->delete_multiple_videos($id);
        page_alert_box('success', 'Videos deleted', 'videos has been deleted successfully');
        redirect(AUTH_PANEL_URL . 'videos/video_control/add_multiple_videos');
    }

    public function delete_all_selected_data() {
        $array = $this->input->post('selected_ids');
        foreach ($array as $id) {
            $this->db->where('id', $id);
            $this->db->update('video_master', ['status' => 2, 'deleted_by' => 1]);
        }
        echo json_encode(array("data" => 1));
    }

    public function get_all_youtube_likes_views() {
        $this->db->select('id,youtube_url');
        $this->db->where('status', 0);
        $this->db->where('youtube_url !=', '');
        $this->db->order_by("id", "desc");
        $result_array = $this->db->get('video_master')->result_array();
        foreach ($result_array as $array) {
            $youtube_data = $this->get_youtube_data($array['youtube_url']);
            if ($youtube_data) {
                $youtube_views = $youtube_data->viewCount;
                $youtube_likes = $youtube_data->likeCount;
                $this->db->where('id', $array['id']);
                $this->db->update('video_master', ['youtube_views' => $youtube_views, 'youtube_likes' => $youtube_likes]);
            }
        }
        //for updating last time
        $current_time = milliseconds();
        $this->db->where('id', 1);
        $this->db->update('video_meta', ['updated_at' => $current_time]);
        echo json_encode(array("data" => 1));
        die;
    }

    public function get_youtube_data($youtube_video_id) {
        $api_key = API_key;
        $json = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics&id=$youtube_video_id&key=$api_key");
//        $json = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics&id=" . $youtube_video_id . "&key=" . API_key);
        $jsonData = json_decode($json);
        if ($jsonData->items) {
            return $jsonData->items[0]->statistics;
        }
        return false;
    }

}
