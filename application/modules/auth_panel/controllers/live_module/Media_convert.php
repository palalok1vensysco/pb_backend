<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Media_convert extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        $this->load->helper('aul');
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->load->model(['live_module/Credentials_model', 'live_module/Media_convert_model']);
    }

//    function sc() {
//        $this->index("https://drishti-production.s3.ap-south-1.amazonaws.com/file_library/videos/original/112726022506843260_video_VOD.mp4", "file_library/videos/vod/");
//    }

    function index($file, $directory, $drm_dash_dir, $drm_hls_dir, $video_id, $is_demand_request = 0) {
        return $this->Media_convert_model->create_job($file, $directory, $drm_dash_dir, $drm_hls_dir, $video_id, 1);
    }

    function create_job_dash($file, $directory, $video_id) {
        return $this->Media_convert_model->create_job_dash($file, $directory, $video_id);
    }

    function track_job($job_id, $is_demand_request = 0) {
        return $this->Media_convert_model->track_job($job_id, $is_demand_request);
    }

    private function convert_ts($time) {
        if (!$time)
            return "";
        return date("d/m/Y h:i A", strtotime($time));
    }

    function ajax_track_job() {
        $input = $this->input->post();
        $result = $this->Media_convert_model->track_job($input['id'], $input['is_demand']);
        if ($result) {
            $return = array(
                "timing" => array(
                    "StartTime" => $this->convert_ts($result['Timing']['StartTime'] ?? ""),
                    "SubmitTime" => $this->convert_ts($result['Timing']['SubmitTime'] ?? ""),
                    "FinishTime" => $this->convert_ts($result['Timing']['FinishTime'] ?? "")
                )
            );

            $return['output'] = array();
            foreach ($result['Settings']['OutputGroups'] as $value) {
                $data = array(
                    "name" => $value['Name'],
                    "bitrate" => array()
                );

                foreach ($value['Outputs'] as $v_value) {
                    if (isset($v_value['VideoDescription'])) {
                        $v_value = $v_value['VideoDescription'];
                        $data['bitrate'][] = array(
                            "height" => $v_value['Height'],
                            "width" => $v_value['Width'],
                            "codec_setting" => array(
                                "max_bitrate" => $v_value['CodecSettings']['H264Settings']['MaxBitrate'],
                                "framerate_numerator" => $v_value['CodecSettings']['H264Settings']['FramerateNumerator'] ?? $v_value['CodecSettings']['H264Settings']['FramerateControl'],
                                "framerate_denominator" => $v_value['CodecSettings']['H264Settings']['FramerateDenominator'] ?? ""
                            )
                        );
                    }
                }

                $return['output'][] = $data;
            }
            echo json_encode($return);
        } else
            echo json_encode(array());
    }

}
