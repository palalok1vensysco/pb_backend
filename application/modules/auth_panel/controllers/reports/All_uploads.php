<?php

class All_uploads extends MX_Controller {

    public function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        $this->load->helper('aul');
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        /* do not remove helper and grocey_crud
         * It will put you in danger
         */
        $this->load->library('grocery_CRUD');
    }

    public function index() {
        $view_data['page'] = 'reports';
        $data['page_title'] = "Course Transactions";
        $view_data['is_support'] = 0;
        $data['page_data'] = $this->load->view('all_uploads/all_reports', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    function support() {
        $view_data['page'] = 'reports';
        $data['page_title'] = "Course Transactions";
        $view_data['is_support'] = 1;
        $data['page_data'] = $this->load->view('course_product/course/course_transactions', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }


    public function get_ajax_all_list($user_id = '') {
        $output_csv = false;
        
        $table_name = "";
         $title = "";
         $file_name="";
        if ($_GET['type'] != "") {
            $type = $_GET['type'];
            if ($type == "news") {
                $table_name .= "news";
                $title .= "title";
                $file_name .= "News.csv";
            } elseif ($type == "bhajan") {
                $table_name .= "bhajan";
                 $title .= "title";
                  $file_name .= "Bhajan.csv";
            } elseif ($type == "video") {
                $table_name .= "video_master";
                 $title .= "video_title";
                  $file_name .= "Video.csv";
            } elseif ($type == "all") {
                $table_name .= "news";
                 $title .= "title";
                 $file_name .= "News.csv";
            }
        }
        
        $where = "";
        /* ------------------------------------------ */
        if ($_GET['period'] != "") {
            $period = $_GET['period'];
            $today = date('d-m-Y');
            if ($period == "today") {
                $where .= " AND DATE_FORMAT(FROM_UNIXTIME(SUBSTR($table_name.creation_time,1,10)), '%d-%m-%Y')='$today' ";
            } elseif ($period == "yesterday") {
                $yesterday = date('d-m-Y', strtotime($today . ' - 1 days'));
                $where .= " AND DATE_FORMAT(FROM_UNIXTIME(SUBSTR($table_name.creation_time,1,10)), '%d-%m-%Y')='$yesterday' ";
            } elseif ($period == "7days") {
                //$yesterday = date('d-m-Y', strtotime($today. ' - 7 days'));
                $yesterday = strtotime("-1 week") . "000";
                $where .= " AND $table_name.creation_time >= '$yesterday'  ";
            } elseif ($period == "current_month") {
                $current_month = date('m-Y');
                $where .= " AND DATE_FORMAT(FROM_UNIXTIME(SUBSTR($table_name.creation_time,1,10)), '%m-%Y') = '$current_month'  ";
            } elseif ($period == "all") {
                $where .= "";
            }
        }
        /* ------------------------------------------ */
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;

        /*         * *****  Check For Instructor User Ends ****** */

        //ini_set('memory_limit', '-1');
        if (isset($_POST['input_json'])) {
                $output_csv = true;
            $requestData = json_decode($_POST['input_json'], true);
        }


        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => $title,
            2 => 'bu.uploaded_by',
            3 => 'creation_time'
        );

        $query = "SELECT count(id) as total FROM $table_name where 1 = 1 $where";
        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT $table_name.id,$title as title,$table_name.uploaded_by,DATE_FORMAT(FROM_UNIXTIME($table_name.creation_time/1000), '%d-%m-%Y') as creation_time,bu.username as name
                FROM $table_name 
                left join backend_user as bu on bu.id = $table_name.uploaded_by
                WHERE $table_name.status=0 and 1 = 1 $where";
        $for_client_filters = array();

        // getting records as per search parameters
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND $table_name.$title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][3]['search']['value'])  ) {  //salary
         	$date = explode(',',$requestData['columns'][3]['search']['value']);
         	$start = (strtotime($date[0])*1000); 
         	$end = (strtotime($date[1])*1000);
         	$sql.="  AND  $table_name.creation_time >= '$start' and $table_name.creation_time <= '$end'";
         }
        $query = $this->db->query($sql)->result();
        //echo $this->db->last_query();
        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        if ($output_csv == false) {
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length
        } else {
            $sql .= " ORDER BY $table_name.id desc ";
        }
        $result = $this->db->query($sql)->result();
        $data = array();

        if ($output_csv == true ) {

            $head = array(
                'S.No',
                'Title',
                'Uploaded By',
                'Creation Date'
            );
            foreach ($result as $r) {
                $nestedData = array();
                $nestedData[] = ++$requestData['start'];
                $nestedData[] = $r->title;
                $nestedData[] = $r->name;
                $nestedData[] = $r->creation_time;
                $data[] = $nestedData;
            }

            if ($output_csv == true) {
                $file_name=
                $this->all_list_to_csv_download($data, $filename = "$file_name", $delimiter = ";", $head);
                die;
            }
        }

        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
               $nestedData[] = ++$requestData['start'];
                $nestedData[] = $r->title;
                $nestedData[] = $r->name;
                $nestedData[] = $r->creation_time;
                $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
            "client_filters" => $for_client_filters,
            "posted_data" => $_POST,
        );
        echo json_encode($json_data);  // send data as json format
    }



    /*
     * this function is to generated csv w.r.t. to user inout in ajax datatable
     * why i use it ?
     * it is realted with authetication machenism
     * Warning !! please check ajax data table view and other related function before changing it
     * happy coding :)
     */
    public function get_ajax_all_download() {
        if (!$_POST) {
            show_404();
        }
        $this->get_ajax_all_list();
    }

    public function all_list_to_csv_download($array, $filename = "export.csv", $delimiter = ";", $header) {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        $f = fopen('php://output', 'w');

        fputcsv($f, $header);

        foreach ($array as $line) {
            fputcsv($f, $line);
        }
    }


}
