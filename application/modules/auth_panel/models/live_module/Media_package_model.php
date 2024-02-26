<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(APPPATH . '/third_party/aws/aws-autoloader.php');

use Aws\MediaPackage;
use Aws\MediaPackage\Exception;

class Media_package_model extends CI_Model {

    protected $pallycon_token = "eyJhY2Nlc3Nfa2V5IjoiMmRFT2J5aFwvSlMxcUVzbFNcL3JndTh3WXc2VEZQNFU2djJpSGhzdjdSaWVhcnplOTJWekJzS250WVRjS2FhTzJ0Iiwic2l0ZV9pZCI6IlhJSVUifQ==";
    protected $mep_pallycon_token = "eyJhY2Nlc3Nfa2V5IjoiMmRFT2J5aFwvSlMxcUVzbFNcL3JndTh3WXc2VEZQNFU2djJpSGhzdjdSaWVhcnplOTJWekJzS250WVRjS2FhTzJ0Iiwic2l0ZV9pZCI6IlhJSVUifQ==";

    function __construct() {
        parent::__construct();
        define("MEDIA_LIVE_ACCESS_ROLE", "arn:aws:iam::771608383469:role/SpekeAccess");
        define("MP_PACKAGE_GROUP_ID_DASH", 'UTK-DASH-1'); //android
        define("MP_PACKAGE_GROUP_ID_HLS", 'UTK-HLS-1'); //ios
        $this->load->helper(['aes', 'aul', 'custom']);
    }

    private function create_media_package_channel($input) {
        $client = new Aws\MediaPackage\MediaPackageClient($this->Credentials_model->get_credentials());
        return (array) $client->createChannel([
                    'Description' => $input['description'],
                    'Id' => $input['channel_id']
        ]);
    }

    function index($input) {
        $this->db->where("channel_id", $input['channel_id']);
        $result = $this->db->get("aws_media_package_channel")->row_array();
        if ($result) {
            page_alert_box("error", "Error!", "This Channel Id Already Exist");
            redirect($_SERVER['HTTP_REFERER']);
        }

        $data = $this->Credentials_model->refine_array($this->create_media_package_channel($input));
        $aws_data = $data["Aws\Resultdata"];

        $insert = array(
            "channel_id" => $aws_data['Id'],
            "arn" => $aws_data['Arn'],
            "url_a" => $aws_data['HlsIngest']['IngestEndpoints'][0]['Url'],
            "url_b" => $aws_data['HlsIngest']['IngestEndpoints'][1]['Url'],
            "description" => $aws_data['Description'],
            "json" => json_encode($aws_data),
            "remark" => $input['remark'],
            "created_by" => $this->session->userdata('active_backend_user_id'),
            "created" => time(),
            "app_id" => (defined("APP_ID") ? "" . APP_ID . "" : "0")
        );

        $this->db->insert("aws_media_package_channel", $insert);
    }

    function get_media_package_channels() {
        if (defined("APP_ID"))
         $this->db->where("app_id", APP_ID);
        return $this->db->get("aws_media_package_channel")->result_array();
    }

    function delete_media_package_channel($id, $channel_id) {
        $this->db->where("channel_id", $channel_id);
        $result = $this->db->get("aws_media_package_channel")->row_array();

        if (!$result || $result['endpoint_count']) {
            page_alert_box("error", "Error!", "This Channel Attached to Endpoint");
            redirect($_SERVER['HTTP_REFERER']);
        }

        $client = new Aws\MediaPackage\MediaPackageClient($this->Credentials_model->get_credentials());
        $result = $client->deleteChannel([
            'Id' => $channel_id, // REQUIRED
        ]);
        $this->db->where('id', $id);
        $this->db->delete('aws_media_package_channel');
        page_alert_box("success", "Success!", "Media Package Channel Deleted");
        redirect($_SERVER['HTTP_REFERER']);
    }

    private function create_endpoint_to_channel($input) {
        $client = new Aws\MediaPackage\MediaPackageClient($this->Credentials_model->get_credentials());

        $conf = [
            'Id' => $input['endpoint_id'],
            'ChannelId' => $input['channel_id'],
            'Description' => $input['endpoint_id'],
            'Origination' => 'ALLOW', //'ALLOW|DENY',
            'StartoverWindowSeconds' => $input['start_over_window'],
            'TimeDelaySeconds' => 5
        ];

        if ($input['type'] == 1) {
            $conf['HlsPackage'] = [
                'ManifestWindowSeconds' => 60,
                'SegmentDurationSeconds' => $input['segment_duration'],
                'StreamSelection' => [
                    'StreamOrder' => $input['stream_order']
                ],
            ];
        } else if ($input['type'] == 2) {
            $conf['DashPackage'] = [
                'Encryption' => [
                    'KeyRotationIntervalSeconds' => 0,
                    'SpekeKeyProvider' => [
                        'ResourceId' => $input['endpoint_id'], // REQUIRED
                        'RoleArn' => MEDIA_LIVE_ACCESS_ROLE, // REQUIRED
                        'SystemIds' => [
                            '9A04F079-9840-4286-AB92-E65BE0885F95',
                            'EDEF8BA9-79D6-4ACE-A3C8-27DCD51D21ED'
                        ],
                        'Url' => 'https://kms.pallycon.com/cpix/getKey?enc-token=' . $this->pallycon_token, // REQUIRED
                    ],
                ],
                'ManifestWindowSeconds' => 60,
                'SegmentDurationSeconds' => $input['segment_duration'],
                'StreamSelection' => [
                    'StreamOrder' => 'VIDEO_BITRATE_DESCENDING',
                ]
            ];
        } else if ($input['type'] == 3) {
            $conf['HlsPackage'] = [
                'ManifestWindowSeconds' => 60,
                'SegmentDurationSeconds' => $input['segment_duration'],
                'StreamSelection' => [
                    'StreamOrder' => $input['stream_order']
                ],
                'Encryption' => [
                    'EncryptionMethod' => 'SAMPLE_AES',
                    'RepeatExtXKey' => false,
                    'KeyRotationIntervalSeconds' => 0,
                    'SpekeKeyProvider' => [// REQUIRED
                        'ResourceId' => $input['endpoint_id'], // REQUIRED
                        'RoleArn' => MEDIA_LIVE_ACCESS_ROLE, // REQUIRED
                        'SystemIds' => [
                            '94CE86FB-07FF-4F43-ADB8-93D2FA968CA2'
                        ],
                        'Url' => 'https://kms.pallycon.com/cpix/getKey?enc-token=' . $this->mep_pallycon_token, // REQUIRED
                    ],
                ]
            ];
        }

        return (array) $client->createOriginEndpoint($conf);
    }

    function add_endpoint_to_channel($input) {
        $this->db->where("channel_id", $input['channel_id']);
        $result = $this->db->get("aws_media_package_channel")->row_array();
        if (!$result) {
            page_alert_box("error", "Error!", "This Channel Does Not Exist");
            redirect($_SERVER['HTTP_REFERER']);
        }

//        $data = $this->Credentials_model->refine_array($this->create_endpoint_to_channel($input));
//        $aws_data = $data["Aws\Resultdata"];
//        
        $cf_array = array(
            '3e46f9f3de79c4d4'=>'d2qnoev5qjpur5.cloudfront.net'
        );
        $url = $aws_data['Url'];
        $url = explode('.mediapackage.',$url);
        $url = str_replace('https://', '', $url[0]);
        
        
        $insert = array(
            "channel_id" => $aws_data['ChannelId'],
            "endpoint_id" => $aws_data['Id'],
            "description" => $aws_data['Description'],
            "arn" => $aws_data['Arn'],
            "start_over_window" => $aws_data['StartoverWindowSeconds'],
            "segment_duration" => 0,
            "stream_order" => $input['stream_order'],
            "url" => $aws_data['Url'],
            "cf_domain" => ($cf_array[$url])?$cf_array[$url]:'',
            "json" => json_encode($aws_data),
            "remark" => $input['remark'],
            "created_by" => $this->session->userdata('active_backend_user_id'),
            "created" => time()
        );

        $this->db->insert("aws_media_package_endpoint", $insert);

        $this->db->where("channel_id", $input['channel_id']);
        $this->db->set("endpoint_count", "endpoint_count+1", false);
        $this->db->update("aws_media_package_channel");

        page_alert_box("success", "Success!", "Media Package Endpoint Added");
        redirect($_SERVER['HTTP_REFERER']);
    }

    function get_media_package_end_points() {
        $this->db->where("channel_id", $this->input->get('id'));
        $result = $this->db->get("aws_media_package_channel")->row_array();
        if (!$result) {
            page_alert_box("error", "Error!", "This Channel Does Not Exist");
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->db->where("channel_id", $this->input->get('id'));
        return $this->db->get("aws_media_package_endpoint")->result_array();
    }

    function delete_media_package_endpoint($id, $channel_id, $endpoint_id) {
        $this->db->where("endpoint_id", $endpoint_id);
        $result = $this->db->get("aws_media_package_endpoint")->row_array();

        if (!$result) {
            page_alert_box("error", "Error!", "This Endpoint Does Not Exist");
            redirect($_SERVER['HTTP_REFERER']);
        } else if ($result['channel_id'] != $channel_id) {
            page_alert_box("error", "Error!", "This Endpoint Is not of requested channel");
            redirect($_SERVER['HTTP_REFERER']);
        }

        $client = new Aws\MediaPackage\MediaPackageClient($this->Credentials_model->get_credentials());
        $result = $client->deleteOriginEndpoint([
            'Id' => $endpoint_id, // REQUIRED
        ]);

        $this->db->where("channel_id", $channel_id);
        $this->db->set("endpoint_count", "endpoint_count-1", false);
        $this->db->update("aws_media_package_channel");

        $this->db->where('id', $id);
        $this->db->delete('aws_media_package_endpoint');
        page_alert_box("success", "Success!", "Media Package Endpoint Deleted");
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function create_asset($url, $id, $resource_id, $type) {
        $client = new Aws\MediaPackageVod\MediaPackageVodClient($this->Credentials_model->get_credentials());
        $result = $client->createAsset([
            'Id' => $id . "_" . time(), // REQUIRED
            'PackagingGroupId' => $type == "dash" ? MP_PACKAGE_GROUP_ID_DASH : MP_PACKAGE_GROUP_ID_HLS,
            'ResourceId' => $resource_id,
            'SourceArn' => 'arn:aws:s3:::' . AMS_BUCKET_NAME . '/' . $url,
            'SourceRoleArn' => MEDIA_LIVE_ACCESS_ROLE
        ]);
        $result = $result->toArray();
        return $result['EgressEndpoints'][0]['Url'];
    }

    function schedule_harvest($input) {
        $vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"),''));
        if($vc_key->vc_access_key && $vc_key->vc_secret_key){
            $data=array(
            "channel_id"=> $input['endpoint_id'],
            "start_time"=> date("Y-m-d h:i A",$input['from']),
            "end_time"  => date("Y-m-d h:i A",$input['to']));
            $accesskey= base64_encode($vc_key->vc_access_key);
           // print_r($data);die;
            $secret=base64_encode($vc_key->vc_secret_key);
            $headers = array("accessKey:$accesskey","secretKey:$secret");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.videocrypt.in/rest_api/channel/create_harvest_v1");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $data = curl_exec($ch);
            curl_close($ch);        
            $play_list = json_decode($data,true);
            if($play_list['status']){
                $this->db->where("id",$input['id']);
                $this->db->update('course_topic_file_meta_master',array("vdc_id"=>$play_list['video_id'],"video_type"=>7));
            }
            return $play_list;
             }
    }

    function schedule_harvest_old($input) {
        try {
            $client = new Aws\MediaPackage\MediaPackageClient($this->Credentials_model->get_credentials());

            $content = array(
                'Id' => $input["id"] . "_" . time(), // REQUIRED
                'OriginEndpointId' => $input['endpoint_id'], // REQUIRED
                'S3Destination' => [// REQUIRED
                    'BucketName' => "utk-media", // REQUIRED
                    'ManifestKey' => "file_library/videos/harvesting/{$input['video_id']}/{$input['endpoint_id']}/" . time() . "/index.m3u8", // REQUIRED
                    'RoleArn' => MEDIA_LIVE_ACCESS_ROLE, // REQUIRED
                ],
                'StartTime' => date("Y-m-d", $input['from']) . "T" . date("H:i:s", $input['from']) . "+05:30", //"2021-11-29T22:31:00+05:30", // //"2021-02-22T15:44:00+05:30", // REQUIRED
                'EndTime' => date("Y-m-d", $input['to']) . "T" . date("H:i:s", $input['to']) . "+05:30"//"2021-11-29T22:41:00+05:30", // //"2021-03-22T15:50:30+05:30", // REQUIRED - 2020-05-06T17:28:27+05:30
            );


            $result = $client->createHarvestJob($content);

            $result = (array) $result;
            $result = $this->Credentials_model->refine_array($result);

            return array(
                "harvest_id" => $result["Aws\Resultdata"]['Id'],
                "channel_id" => $result["Aws\Resultdata"]['ChannelId'],
                "harvest_from" => $result["Aws\Resultdata"]['StartTime'],
                "harvest_to" => $result["Aws\Resultdata"]['EndTime'],
                "s3_key" => $result["Aws\Resultdata"]['S3Destination']['ManifestKey'],
                "s3_name" => $result["Aws\Resultdata"]['S3Destination']['BucketName'],
                "status" => $result["Aws\Resultdata"]['Status']
            );
        } catch (Exception $exc) {
            return array();
        }
    }

    function describe_harvest($harvest_id) {
        $client = new Aws\MediaPackage\MediaPackageClient($this->Credentials_model->get_credentials());

        $result = $client->describeHarvestJob([
            'Id' => $harvest_id
        ]);
        $result = (array) $result;
        $result = $this->Credentials_model->refine_array($result);
        return array(
            "status" => $result["Aws\Resultdata"]['Status'] //'IN_PROGRESS|SUCCEEDED|FAILED'
        );
    }

    private function get_m3u8_urls($m3u8_url) {
        if ($m3u8_url) {
            exec("ffmpeg -i $m3u8_url 2>&1", $a, $b);

            $play_list = array();
            foreach ($a as $value) {
                if (strpos($value, "m3u8") && strpos($value, "reading")) {
                    preg_match('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $value, $match);
                    foreach ($match as $link) {
                        if (strpos($link, "m3u8")) {
                            $play_list[] = $link;
                        }
                    }
                }
            }
            $size_wise_names = array(
                "0" => "240x426",
                "1" => "360x640",
                "2" => "480x854",
                "3" => "720x1280",
                "4" => "1080x1920"
            );

            $data = array();
            foreach ($play_list as $key => $link) {
                $file_name = $size_wise_names[$key] . ".mp4";

                $data[] = array(
                    "name" => $file_name,
                    "link" => $link
                );
            }
        }
        return $data;
    }

    private function m3u8_to_mp4($m3u8_url, $video_id) {
        if ($m3u8_url) {
            $target_location = getcwd() . '/uploads/harvest_video/';
            ini_set('memory_limit', '-1');

            $file_name = $video_id . '.mp4';
            $target_location .= $file_name;
            shell_exec("ffmpeg -i $m3u8_url -bsf:a aac_adtstoasc -vcodec copy -c copy -crf 50 " . $target_location);

            $enc = array();
            if ($target_location) {
                $this->load->library('s3_upload');
                $mp4_url = $this->s3_upload->upload_s3($target_location, "file_library/videos/harvesting/raw_hq/$video_id/", $video_id);
                if (file_exists($target_location))
                    unlink($target_location);

                return $mp4_url;
            }
        }
    }

    private function get_mp4($m3u8_url) {
        if ($m3u8_url) {
            $this->load->library('jwplayer');
            $this->jwplayer->key('utk_test');
            $this->jwplayer->secret('kIaXy7N6jXgC86A2GvCh5WInYUVZemFHZFZkbE42UTNsSlJVOVRkMFZPUVU5SU5VaG4n');
            $this->jwplayer->reportingAPIKey('kIaXy7N6jXgC86A2GvCh5WInYUVZemFHZFZkbE42UTNsSlJVOVRkMFZPUVU5SU5VaG4n');
            $video_id = explode("harvesting/", $m3u8_url);
            $video_id = current(explode("/", end($video_id)));
            $m3u8_urls = $this->get_m3u8_urls($m3u8_url);
            if ($m3u8_urls) {
                $m3u8_url = end($m3u8_urls)['link'];
                $mp4_url = $this->m3u8_to_mp4($m3u8_url, $video_id);
            }

            $file = $mp4_url;

            $d['title'] = $video_id;
            $d['link'] = $file;

            $response = json_encode($this->jwplayer->call('/videos/create', $d));
            print_r($response);
            die;
            $decoded = json_decode(trim($response), TRUE);
            $upload_link = $decoded['link'];

            $response = $this->jwplayer->upload($file, $upload_link);
            $jw_media_id = $response['media']['key'];
            return $jw_media_id;
        }
    }

    function harvest_job_to_vod($job_meta) {
       
        $s3_url = "https://" . $job_meta['s3_name'] . ".s3.ap-south-1.amazonaws.com/" . $job_meta['s3_key'];
//        $jw_url = $this->get_mp4($s3_url);
        $result = file_get_contents($s3_url);
        $pieces = explode("\n", $result); // make an array out of curl return value
        unset($pieces[0]); // remove #EXTM3U
        $pieces = array_map('trim', $pieces); // remove unnecessary space
        $pieces = array_chunk($pieces, 2); // group them by two's
        $s3_url = str_replace("index.m3u8", $pieces[1][1], $s3_url);

        if (!$result) {
            return array();
        } else {
            $this->load->helper("aes");

            $json = json_decode($job_meta['json'], true);

            $insert_id =  $job_meta['video_id'];
            $existing_use = false;
            if (array_key_exists("harvest_parent_id", $json)) {
                $this->db->where("id", $json['harvest_parent_id']);
                $insert_id = $this->db->get("aws_media_package_harvesting")->row()->video_id;
                $existing_use = true;
            } else {
//                $insert = $json['video_data'];
//                $insert['created'] = time();
//                unset($insert['course_id']);
//
//                $this->db->insert("course_topic_file_meta_master", $insert);
//
//                $insert_id = $this->db->insert_id();
            }

            $this->db->where("id", $job_meta['id']);
            $this->db->update("aws_media_package_harvesting", array("is_approved" => 1));

            $rand = random_token();
            $new_token = "{$insert_id}_0_{$rand}";
            
//            if (!$existing_use) {
//                foreach ($json['attatch_detail'] as $value) {
//                    $value['element_fk'] = $insert_id;
//                    $value['l1_id'] = $json['video_data']['subject_id'];
//                    $value['l2_id'] = $json['video_data']['topic_id'];
//                    $value['course_id'] = $json['course_id']; //$json['video_data']
//                    $value['v_name'] = $value['v_name_2'] = $json['video_data']['title'];
//
//                    $this->db->insert("course_segment_element", $value);
//
//                    $position = $this->db->insert_id();
//
//                    $this->db->where("id", $position);
//                    $this->db->insert("course_segment_element", array("position" => $position));
//                }
//            }
            $url = $s3_url; //end($play_list);

            $drm_hls_dir = ADMIN_VERSION . "/file_library/videos/hls_drm/{$insert_id}/";
            $drm_dash_dir = ADMIN_VERSION . "/file_library/videos/dash_drm/{$insert_id}/";

            $this->load->model("live_module/Media_convert_model");
            $mediaconvert_tracking = array();
            $mediaconvert_tracking[] = $this->Media_convert_model->create_job($url, ADMIN_VERSION . "/file_library/videos/vod/", $drm_dash_dir, $drm_hls_dir, $insert_id, 1);

            $update = array(
//                "token" => $new_token,
//                "is_vod" => 1,
                "video_type" => 0, //6
                "playtime" => strtotime($job_meta['harvest_to']) - strtotime($job_meta['harvest_from']),
                "mediaconvert_tracking" => json_encode($mediaconvert_tracking)
            );

            $dash_url = ADMIN_VERSION . "/file_library/videos/dash_drm/" . basename($url, ".m3u8") . ".mpd";
            $update['drm_dash_url'] = $dash_url; //aes_cbc_encryption($dash_url, $rand);

            $hls_url = ADMIN_VERSION . "/file_library/videos/hls_drm/{$insert_id}/" . basename($url);
            $update['drm_hls_url'] = $hls_url; //aes_cbc_encryption($hls_url, $rand);

            $update['file_url'] = "https://" . $job_meta['s3_name'] . ".s3.ap-south-1.amazonaws.com/" . $job_meta['s3_key'];//$jw_url; //
            $update['page_count'] = 1;//for listen
            
            $this->db->where('id', $insert_id);
            $this->db->update("course_topic_file_meta_master", $update);
            
//            echo $this->db->last_query();die;
//            if ($json['chat_user'] && $json['chat_user']['ids']) {
//                $chat_user = $this->db->get_where('chat_user', array('id' => $json['chat_user']['ids']))->row_array();
//                unset($chat_user['id']);
//                $this->db->insert('chat_user', $chat_user);
//
//                $this->db->where_in("id", $json['chat_user']['ids']);
//                $this->db->update("chat_user", array("video_id" => $insert_id));
//            }

            return $update;
        }
    }
    
    private function harvest_job_to_vod_deprecated($job_meta) {
        $s3_url = "https://" . $job_meta['s3_name'] . ".s3.ap-south-1.amazonaws.com/" . $job_meta['s3_key'];
//        $jw_url = $this->get_mp4($s3_url);
        $result = file_get_contents($s3_url);
        $pieces = explode("\n", $result); // make an array out of curl return value
        unset($pieces[0]); // remove #EXTM3U
        $pieces = array_map('trim', $pieces); // remove unnecessary space
        $pieces = array_chunk($pieces, 2); // group them by two's
        $s3_url = str_replace("index.m3u8", $pieces[1][1], $s3_url);

        if (!$result) {
            return array();
        } else {
            $this->load->helper("aes");

            $json = json_decode($job_meta['json'], true);

            $insert_id = 0;
            $existing_use = false;
            if (array_key_exists("harvest_parent_id", $json)) {
                $this->db->where("id", $json['harvest_parent_id']);
                $insert_id = $this->db->get("aws_media_package_harvesting")->row()->video_id;
                $existing_use = true;
            } else {
                $insert = $json['video_data'];
                $insert['created'] = time();
                unset($insert['course_id']);

                $this->db->insert("course_topic_file_meta_master", $insert);

                $insert_id = $this->db->insert_id();
            }

            $this->db->where("id", $job_meta['id']);
            $this->db->update("aws_media_package_harvesting", array("video_id" => $insert_id));

            $rand = random_token();
            $new_token = "{$insert_id}_0_{$rand}";

            if (!$existing_use) {
                foreach ($json['attatch_detail'] as $value) {
                    $value['element_fk'] = $insert_id;
                    $value['l1_id'] = $json['video_data']['subject_id'];
                    $value['l2_id'] = $json['video_data']['topic_id'];
                    $value['course_id'] = $json['course_id']; //$json['video_data']
                    $value['v_name'] = $value['v_name_2'] = $json['video_data']['title'];

                    $this->db->insert("course_segment_element", $value);

                    $position = $this->db->insert_id();

                    $this->db->where("id", $position);
                    $this->db->insert("course_segment_element", array("position" => $position));
                }
            }
            $url = $s3_url; //end($play_list);

            $drm_hls_dir = ADMIN_VERSION . "/file_library/videos/hls_drm/{$insert_id}/";
            $drm_dash_dir = ADMIN_VERSION . "/file_library/videos/dash_drm/{$insert_id}/";

            $this->load->model("live_module/Media_convert_model");
            $mediaconvert_tracking = array();
            $mediaconvert_tracking[] = $this->Media_convert_model->create_job($url, ADMIN_VERSION . "/file_library/videos/vod/", $drm_dash_dir, $drm_hls_dir, $insert_id, 1);

            $update = array(
//                "token" => $new_token,
//                "is_vod" => 1,
                "video_type" => 0, //6
                "playtime" => strtotime($job_meta['harvest_to']) - strtotime($job_meta['harvest_from']),
                "mediaconvert_tracking" => json_encode($mediaconvert_tracking)
            );

            $dash_url = ADMIN_VERSION . "/file_library/videos/dash_drm/" . basename($url, ".m3u8") . ".mpd";
            $update['drm_dash_url'] = $dash_url; //aes_cbc_encryption($dash_url, $rand);

            $hls_url = ADMIN_VERSION . "/file_library/videos/hls_drm/{$insert_id}/" . basename($url);
            $update['drm_hls_url'] = $hls_url; //aes_cbc_encryption($hls_url, $rand);

            $update['file_url'] = "https://" . $job_meta['s3_name'] . ".s3.ap-south-1.amazonaws.com/" . $job_meta['s3_key'];//$jw_url; //
            $update['page_count'] = 1;//for listen
            $this->db->where('id', $insert_id);
            $this->db->update("course_topic_file_meta_master", $update);

            if ($json['chat_user'] && $json['chat_user']['ids']) {
                $chat_user = $this->db->get_where('chat_user', array('id' => $json['chat_user']['ids']))->row_array();
                unset($chat_user['id']);
                $this->db->insert('chat_user', $chat_user);

                $this->db->where_in("id", $json['chat_user']['ids']);
                $this->db->update("chat_user", array("video_id" => $insert_id));
            }

            return $update;
        }
    }

}
