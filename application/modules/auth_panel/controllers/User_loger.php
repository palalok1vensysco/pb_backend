<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_loger extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        $this->load->model('user_query_model');
        modules::run('auth_panel/auth_panel_ini/auth_ini');
    }

    public function index() {
        $view_data['page'] = 'user_logger';
        $view_data['bu_id'] = $this->input->get("id");
        //$view_data['breadcrum'] = !empty($_GET['id']) ? array("Backend user"=>"admin/backend_user_list", "User Logger"=>"#") : array("User Logger"=>"#");
        $data['page_data'] = $this->load->view('user_loger/logs', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_user_loger_list() {
        $requestData = $_REQUEST;

        $columns = array(
            // datatable column index  => database column name
            0 => 'buag.id',
            1 => 'bu.username',
            2 => 'buag.comment',
            3 => 'buag.segment',
            4 => 'buag.creation_time',
        );

        $where = "";
        if ($this->input->get("bu_id"))
            $where = " AND user_id=" . $this->input->get("bu_id");

        $query = "SELECT count(id) as total FROM backend_user_activity_log where 1 $where";
        $query .= app_permission("app_id");
        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        //getting records as per search parameters
        $where1 = "";
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $where1 .= " AND username LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {   //name
            $where1 .= " AND comment LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {   //name
            $where1 .= " AND segment LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
        }

        $sql = "SELECT  buag.id,bu.username as name ,
		buag.comment,buag.segment, buag.creation_time as ctime from backend_user_activity_log buag
		JOIN backend_user bu ON buag.user_id = bu.id
		where 1=1 $where $where1";

        $sql .= app_permission("buag.app_id");

        if ($where1)
            $totalFiltered = $this->db->query($sql)->num_rows();

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length
        $result = $this->db->query($sql)->result();

        $data = array();

        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->name;
            $nestedData[] = $r->comment;
            $nestedData[] = $r->segment;
            $nestedData[] = $r->ctime ? get_time_format($r->ctime) : "--NA--";
            $nestedData[] = '<button class="btn btn-xs btn-info view_json eyesTabView" id="' . $r->id . '"><i class="fa fa-eye"></i> </button>';                                    
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

    public function ajax_json() { 
        $this->db->select('backend_user_activity_log.*,username');
        $this->db->where('backend_user_activity_log.id', $this->input->post('id'));
        $this->db->join('backend_user', 'user_id=backend_user.id');
        $data = $this->db->get_where('backend_user_activity_log')->row_array();
        $data['creation_time'] = get_time_format($data['creation_time']);
        $data['data'] = 1;
        echo json_encode($data);
    }

}
