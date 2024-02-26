<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Trending extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        $this->load->model("Library_model");
        $this->load->model("Movies_model");
        modules::run('auth_panel/auth_panel_ini/auth_ini');
    }

    public function add_trending()
    {

        //  pre($_POST);die;
        if ($this->input->post()) {
            $this->db->set('is_trending', 1);
            $this->db->where('id', $this->input->post('show_id'));
            $this->db->update('shows');
            backend_log_genration($this, "Trending has been set by User(ID : {$this->session->userdata('active_backend_user_id')}).", "Trending");
            page_alert_box('success', 'Added', 'Trending set successfully');
            redirect(base_url('auth_panel/trending/add_trending'));
        }

        $view_data['page'] = 'add_trending';
        $view_data['categories'] = $this->Library_model->get_all_category();
        $data['page_data'] = $this->load->view('trending/add_trending', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }


    public function ajax_set_trending()
    {
        $requestData = $_REQUEST;
  
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'created_at'
        );

        $this->db_read->select('COUNT(s.id) as total');
        $this->db_read->from('shows s');
        $this->db_read->join('categories cate', 'cate.id = s.category_id', 'left');
        $this->db_read->where('is_trending', 1);
        $this->db_read->where('cate.status', 0);
        $totalData = $this->db_read->get()->row()->total;
           
        $totalFiltered = $totalData;

        

        if ($text = $requestData['columns'][0]['search']['value']) {   
            $this->db_read->where('cate.title', $text);
        }

        if ($title = $requestData['columns'][1]['search']['value']) {  
            $this->db_read->where('s.title', $title);
        }

        if(isset($requestData['start'])){
            $this->db_read->order_by($columns[$requestData['order'][0]['column']], 'DESC');
            $this->db_read->limit($requestData['length'], $requestData['start']);
             } 
        $this->db_read->select('cate.title as cat_title, s.title as show_title, s.created_at, s.id');     
        $this->db_read->from('shows s');
        $this->db_read->join('categories cate', 'cate.id = s.category_id', 'left');
        $this->db_read->where('is_trending', 1);
        $this->db_read->where('s.status', 0);
        $query = $this->db_read->get();
      
        $result = $query->result();
     
        $data = array();
        $sr_no = 0;
        foreach ($result as $r) { 
            $nestedData = array();
            $nestedData[] = ++$sr_no; 
            $nestedData[] = $r->cat_title;
            $nestedData[] = $r->show_title;
            $nestedData[] = $r->created_at ? get_date_format($r->created_at) : "--NA--";
            $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
                <ul class='dropdown-menu'>               
                    <li><a  class='' onclick=\"return confirm('Are you sure you want to delete?')\"  href='" . AUTH_PANEL_URL . "trending/trending_delete/" . $r->id . "'><i class='fa fa-trash'></i> Delete</a></li>
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

    public function all_trending()
    {
        $this->db->order_by("trending_order_by", "asc");
        $all = $this->db->select('shows.title show_title, categories.title cate_title,shows.id show_id')->from('shows')->join('categories', 'shows.category_id = categories.id')->where('is_trending', 1)->where('shows.status = 0')->get()->result_array();
        echo json_encode($all);
    }

    public function save_position_stream()
    {
        $ids = $_POST['ids'];
        //pre($ids);die;
        $counter = 1;
        foreach ($ids as $id) {
            $this->db->where('id', $id);
            $array = array('trending_order_by' => $counter);
            $this->db->update('shows', $array);
            $counter++;
        }
        backend_log_genration($this, "Trending {$this->input->post('title')} has been swap by User(ID : {$this->session->userdata('active_backend_user_id')}).", "Trending");
        echo json_encode(array('status' => true, 'message' => 'Position swap successfully'));
        die;
    }

    public function get_categorywise_geners($id = null)
    {
        $cate_id = $id;
        $cate = $this->Library_model->get_categorywise_geners($cate_id);
        echo json_encode($cate);
    }
    public function get_genreswise_show($id = null)
    {
        $genre_id = $id;
        $cate = $this->Library_model->get_genreswise_show($genre_id);
        echo json_encode($cate);
    }
    public function trending_delete($id)
    {
        $this->db->set('is_trending', 0);
        $this->db->where('id', $id);
        $this->db->update('shows');
        redirect(base_url('auth_panel/trending/add_trending'));
    }

    // public function set_trending() {
    //     $data['page_title'] = "Artist's List";
    //     $view_data['page'] = "Trending List";
    //     $data['page_data'] = $this->load->view('trending/set_trending', $view_data, TRUE);
    //     echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    // }
}
