<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Aws\CloudFront\CloudFrontClient;

class Channels_model extends CI_Model {

    function __construct() {
        parent::__construct();
        define("ARN_USER", 'arn:aws:iam::771608383469:role/MediaLiveAccessRole');
        define("CLOUDFRONT_KEY_PAIR", "APKAJ45GELAODKU5PP5A");
        $this->load->helper(['aes', 'aul', 'custom']);
    }

    private function create_channel_aws($input) {
        $client = new Aws\MediaLive\MediaLiveClient($this->Credentials_model->get_credentials());

        return (array) $client->createChannel([
                    'RoleArn' => ARN_USER,
                    'ChannelClass' => 'SINGLE_PIPELINE', //'STANDARD|SINGLE_PIPELINE',
                    'Destinations' => [
                        [
                            "Id" => "destination2",
                            "MediaPackageSettings" => [
                                [
                                    "ChannelId" => $input['media_package_id']
                                ]
                            ]
                        ]
                    ],
                    'EncoderSettings' => [
                        'AudioDescriptions' => [
                            [
                                'AudioSelectorName' => 'Default',
                                'AudioTypeControl' => 'FOLLOW_INPUT',
                                'LanguageCodeControl' => 'FOLLOW_INPUT',
                                'Name' => 'audio_1',
                            ],
                            [
                                'AudioSelectorName' => 'Default',
                                'AudioTypeControl' => 'FOLLOW_INPUT',
                                'LanguageCodeControl' => 'FOLLOW_INPUT',
                                'Name' => 'audio_2',
                            ], [
                                'AudioSelectorName' => 'Default',
                                'AudioTypeControl' => 'FOLLOW_INPUT',
                                'LanguageCodeControl' => 'FOLLOW_INPUT',
                                'Name' => 'audio_3',
                            ], [
                                'AudioSelectorName' => 'Default',
                                "CodecSettings" => [
                                    "AacSettings" => [
                                        "InputType" => "NORMAL",
                                        "Bitrate" => 64000,
                                        "CodingMode" => "CODING_MODE_2_0",
                                        "RawFormat" => "NONE",
                                        "Spec" => "MPEG4",
                                        "Profile" => "LC",
                                        "RateControlMode" => "CBR",
                                        "SampleRate" => 48000
                                    ]
                                ],
                                'AudioTypeControl' => 'FOLLOW_INPUT',
                                'LanguageCodeControl' => 'FOLLOW_INPUT',
                                'Name' => 'audio_4',
                            ]
                        ],
//                        'AvailBlanking' => [
//                            'State' => 'DISABLED',
//                        ],
                        'CaptionDescriptions' => [],
                        'OutputGroups' => [
                            [
                                'Name' => 'Media Package',
                                'OutputGroupSettingsChoice' => 'mediapackage_group',
                                'OutputGroupSettings' => [
                                    'MediaPackageGroupSettings' => [
                                        'Destination' => [
                                            'DestinationRefId' => 'destination2',
                                        ],
                                    ],
                                ],
                                'Outputs' => [
                                    [
                                        'AudioDescriptionNames' => ['audio_4'],
                                        'OutputSettings' => [
                                            'MediaPackageOutputSettings' => [
                                            ],
                                        ],
                                        'OutputName' => '240p30',
                                        'VideoDescriptionName' => 'video_240p30',
                                        'OutputSettingsChoice' => 'mediapackage'
                                    ],
                                    [
                                        'AudioDescriptionNames' => ['audio_3'],
                                        'OutputSettings' => [
                                            'MediaPackageOutputSettings' => [
                                            ],
                                        ],
                                        'OutputName' => '360p30',
                                        'VideoDescriptionName' => 'video_360p30',
                                        'OutputSettingsChoice' => 'mediapackage'
                                    ],
                                    [
                                        'AudioDescriptionNames' => ['audio_2'],
                                        'OutputSettings' => [
                                            'MediaPackageOutputSettings' => [
                                            ],
                                        ],
                                        'OutputName' => '480p30',
                                        'VideoDescriptionName' => 'video_480p30',
                                        'OutputSettingsChoice' => 'mediapackage'
                                    ],
                                    [
                                        'AudioDescriptionNames' => ['audio_1'],
                                        'OutputSettings' => [
                                            'MediaPackageOutputSettings' => [
                                            ],
                                        ],
                                        'OutputName' => '720p30',
                                        'VideoDescriptionName' => 'video_720p30',
                                        'OutputSettingsChoice' => 'mediapackage'
                                    ]
                                ],
                            ],
                        ],
                        "TimecodeConfig" => [
//                            "Source" => "EMBEDDED"
                            "Source" => "SYSTEMCLOCK",
                            "SyncThreshold" => 1
                        ],
                        'VideoDescriptions' => [
                            [
                                'CodecSettings' => [
                                    'H264Settings' => [
                                        'AfdSignaling' => 'NONE',
                                        'ColorMetadata' => 'INSERT',
                                        'AdaptiveQuantization' => 'MEDIUM', //HIGH',
                                        'Bitrate' => 400000, //500000,
                                        'MaxBitrate' => 400000, //500000,
                                        'BufSize' => 400000,
                                        'EntropyEncoding' => 'CABAC',
                                        'FlickerAq' => 'DISABLED', //ENABLED',
                                        'ForceFieldPictures' => 'DISABLED',
                                        'FramerateControl' => 'SPECIFIED',
                                        'FramerateNumerator' => 25, //30,
                                        'FramerateDenominator' => 1,
                                        'GopBReference' => 'ENABLED', //'DISABLED',
                                        'GopClosedCadence' => 1,
                                        'GopNumBFrames' => 2, //3,
                                        'GopSize' => 1, //30,
                                        'GopSizeUnits' => 'SECONDS', //'FRAMES',
                                        'SubGopLength' => 'DYNAMIC',
                                        'ScanType' => 'PROGRESSIVE',
                                        'Level' => 'H264_LEVEL_AUTO',
                                        'LookAheadRateControl' => 'HIGH',
                                        'NumRefFrames' => 3,
                                        'ParControl' => 'SPECIFIED',
                                        'ParNumerator' => 1,
                                        'ParDenominator' => 1,
                                        'Profile' => 'MAIN',
                                        'RateControlMode' => 'QVBR',
                                        'QvbrQualityLevel' => 6,
                                        'Syntax' => 'DEFAULT',
                                        'SceneChangeDetect' => 'ENABLED',
                                        'Slices' => 1,
                                        'SpatialAq' => 'ENABLED',
                                        'TemporalAq' => 'ENABLED',
                                        'TimecodeInsertion' => 'DISABLED',
                                    ],
                                ],
                                'Height' => 240,
                                'Name' => 'video_240p30',
                                'RespondToAfd' => 'NONE',
                                'ScalingBehavior' => 'DEFAULT',
                                'Sharpness' => 100,
                                'Width' => 426,
                            ],
                            [
                                'CodecSettings' => [
                                    'H264Settings' => [
                                        'AfdSignaling' => 'NONE',
                                        'ColorMetadata' => 'INSERT',
                                        'AdaptiveQuantization' => 'HIGH',
                                        'Bitrate' => 700000, //800000,
                                        'MaxBitrate' => 700000, //800000,
                                        'BufSize' => 700000,
                                        'QvbrQualityLevel' => 7, //6,
                                        'EntropyEncoding' => 'CABAC',
                                        'FlickerAq' => 'ENABLED',
                                        'ForceFieldPictures' => 'DISABLED',
                                        'FramerateControl' => 'SPECIFIED',
                                        'FramerateNumerator' => 25,
                                        'FramerateDenominator' => 1,
                                        'GopBReference' => 'ENABLED', //DISABLED',
                                        'GopClosedCadence' => 1,
                                        'GopNumBFrames' => 2, //3,
                                        'GopSize' => 1, //30,
                                        'GopSizeUnits' => 'SECONDS', //FRAMES',
                                        'SubGopLength' => 'DYNAMIC',
                                        'ScanType' => 'PROGRESSIVE',
                                        'Level' => 'H264_LEVEL_AUTO',
                                        'LookAheadRateControl' => 'HIGH',
                                        'NumRefFrames' => 3,
                                        'ParControl' => 'SPECIFIED',
                                        'ParNumerator' => 1,
                                        'ParDenominator' => 1,
                                        'Profile' => 'MAIN',
                                        'RateControlMode' => 'QVBR',
                                        'Syntax' => 'DEFAULT',
                                        'SceneChangeDetect' => 'ENABLED',
                                        'Slices' => 1,
                                        'SpatialAq' => 'ENABLED',
                                        'TemporalAq' => 'ENABLED',
                                        'TimecodeInsertion' => 'DISABLED',
                                    ],
                                ],
                                'Height' => 360,
                                'Name' => 'video_360p30',
                                'RespondToAfd' => 'NONE',
                                'ScalingBehavior' => 'DEFAULT',
                                'Sharpness' => 100,
                                'Width' => 640,
                            ],
                            [
                                'CodecSettings' => [
                                    'H264Settings' => [
                                        'AfdSignaling' => 'NONE',
                                        'ColorMetadata' => 'INSERT',
                                        'AdaptiveQuantization' => 'HIGH',
                                        'Bitrate' => 1000000,
                                        'MaxBitrate' => 1000000,
                                        'BufSize' => 1000000,
                                        'QvbrQualityLevel' => 7, //6,
                                        'EntropyEncoding' => 'CABAC',
                                        'FlickerAq' => 'ENABLED',
                                        'ForceFieldPictures' => 'DISABLED',
                                        'FramerateControl' => 'SPECIFIED',
                                        'FramerateNumerator' => 25, //30,
                                        'FramerateDenominator' => 1,
                                        'GopBReference' => 'ENABLED', //DISABLED',
                                        'GopClosedCadence' => 1,
                                        'GopNumBFrames' => 2, //3,
                                        'GopSize' => 1, //30,
                                        'GopSizeUnits' => 'SECONDS', //FRAMES',
                                        'SubGopLength' => 'DYNAMIC', //
                                        'ScanType' => 'PROGRESSIVE',
                                        'Level' => 'H264_LEVEL_AUTO',
                                        'LookAheadRateControl' => 'HIGH',
                                        'NumRefFrames' => 3,
                                        'ParControl' => 'SPECIFIED',
                                        'ParNumerator' => 1,
                                        'ParDenominator' => 1,
                                        'Profile' => 'HIGH',
                                        'RateControlMode' => 'QVBR',
                                        'Syntax' => 'DEFAULT',
                                        'SceneChangeDetect' => 'ENABLED',
                                        'Slices' => 1,
                                        'SpatialAq' => 'ENABLED',
                                        'TemporalAq' => 'ENABLED',
                                        'TimecodeInsertion' => 'DISABLED',
                                    ],
                                ],
                                'Height' => 480,
                                'Name' => 'video_480p30',
                                'RespondToAfd' => 'NONE',
                                'ScalingBehavior' => 'DEFAULT',
                                'Sharpness' => 100,
                                'Width' => 854,
                            ],
                            [
                                'CodecSettings' => [
                                    'H264Settings' => [
                                        'AfdSignaling' => 'NONE',
                                        'ColorMetadata' => 'INSERT',
                                        'AdaptiveQuantization' => 'HIGH',
                                        'Bitrate' => 1700000, //1500000,
                                        'MaxBitrate' => 1700000, //1500000,
                                        'BufSize' => 1700000,
                                        'QvbrQualityLevel' => 8, //6,
                                        'EntropyEncoding' => 'CABAC',
                                        'FlickerAq' => 'ENABLED',
                                        'FramerateControl' => 'SPECIFIED',
                                        'FramerateNumerator' => 25, //30,
                                        'ForceFieldPictures' => 'DISABLED',
                                        'FramerateDenominator' => 1,
                                        'GopBReference' => 'ENABLED', //DISABLED',
                                        'GopClosedCadence' => 1,
                                        'GopNumBFrames' => 2, //3,
                                        'GopSize' => 1, //30,
                                        'GopSizeUnits' => 'SECONDS', //FRAMES',
                                        'SubGopLength' => 'DYNAMIC',
                                        'ScanType' => 'PROGRESSIVE',
                                        'Level' => 'H264_LEVEL_AUTO',
                                        'LookAheadRateControl' => 'HIGH',
                                        'NumRefFrames' => 3,
                                        'ParControl' => 'SPECIFIED',
                                        'ParNumerator' => 1,
                                        'ParDenominator' => 1,
                                        'Profile' => 'HIGH', //MAIN',
                                        'RateControlMode' => 'QVBR',
                                        'Syntax' => 'DEFAULT',
                                        'SceneChangeDetect' => 'ENABLED',
                                        'Slices' => 1,
                                        'SpatialAq' => 'ENABLED',
                                        'TemporalAq' => 'ENABLED',
                                        'TimecodeInsertion' => 'DISABLED',
                                    ],
                                ],
                                'Height' => 720,
                                'Name' => 'video_720p30',
                                'RespondToAfd' => 'NONE',
                                'ScalingBehavior' => 'DEFAULT',
                                'Sharpness' => 100,
                                'Width' => 1280,
                            ]
                        ],
                    ],
                    "InputAttachments" => [
                        [
                            'InputAttachmentName' => $input['InputAttachments'][0]['name'],
                            "InputId" => $input['InputAttachments'][0]['input_id'],
                            "inputSettings" => [
                                "sourceEndBehavior" => "CONTINUE",
                                "inputFilter" => "AUTO",
                                "filterStrength" => 1,
                                "deblockFilter" => "DISABLED",
                                "denoiseFilter" => "DISABLED",
                                "smpte2038DataPreference" => "IGNORE",
                                "audioSelectors" => [],
                                "captionSelectors" => []
                            ]
                        ]
                    ],
                    'InputSpecification' => [
                        'Codec' => $input['codec'],
                        'MaximumBitrate' => $input['bitrate'],
                        'Resolution' => $input['resolution'],
                    ],
                    'LogLevel' => 'ERROR',
                    'Name' => $input['name'],
        ]);
    }

    public function index($input) {
        $this->db->where('input_id', $input['input_id']);
        $channel_input = $this->db->get('aws_channel_input')->row_array();

        $this->db->where('id', $input['media_package_id']);
        $media_package = $this->db->get('aws_media_package_channel')->row_array();
        if (!$channel_input) {
            page_alert_box('error', "Invalid Input Id", "Input Id");
        } else if ($channel_input['state'] != "DETACHED") {
            page_alert_box('error', "Input Is Attatched With Another Channel", "Invalid Input Id");
        } else if (!$media_package) {
            page_alert_box('error', "Invalid Media Package Id", "Invalid Request");
        } else if ($media_package['state']) {
            page_alert_box('error', "Media Package Is Attatched With Another Channel", "Invalid Request");
        } else if (!$media_package['endpoint_count']) {
            page_alert_box('error', "Media Package Should Have Atleast 1 Endpoint", "Invalid Request");
        } else {
            $input['InputAttachments'][] = array(
                'name' => $channel_input['name'],
                'input_id' => $channel_input['input_id']
            );
            $input['media_package_id'] = $media_package['channel_id'];
            $data = $this->Credentials_model->refine_array($this->create_channel_aws($input));

            $aws_input = $data["Aws\Resultdata"]["Channel"];

            $this->db->where("channel_id", $media_package['channel_id']);
            $media_package_endpoint = $this->db->get("aws_media_package_endpoint")->result_array();

            $urla = $urlb = $urlc = "";
            if ($media_package_endpoint) {
                foreach ($media_package_endpoint as $url) {
                    if (strpos($url['endpoint_id'], "DASH-DRM")) {
                        $urlb = $url['url'];
                    } else if (strpos($url['endpoint_id'], "HLS-DRM")) {
                        $urlc = $url['url'];
                    } else {
                        $urla = $url['url'];
                    }
                }
            }
            $insert = array(
                'channel_id' => $aws_input['Id'],
                'input_ids' => json_encode($aws_input['InputAttachments']),
                'media_package_ids' => $input['media_package_id'],
                'channel_name' => $aws_input['Name'],
                'output_a' => explode(".com/", $urla)[1],
                'output_b' => explode(".com/", $urlb)[1],
                'output_c' => explode(".com/", $urlc)[1],
                'state' => "idle",
                'arn' => $aws_input['RoleArn'],
                'channel_class' => $aws_input['ChannelClass'],
                'codec' => $aws_input['InputSpecification']['Codec'],
                'bit_rate' => $aws_input['InputSpecification']['MaximumBitrate'],
                'resolution' => $aws_input['InputSpecification']['Resolution'],
                'log_level' => $aws_input['LogLevel'],
                'remark' => $input['remark'],
                'json' => json_encode($data),
                'created_by' => $this->session->userdata('active_backend_user_id'),
                'created' => time(),
                'app_id' => (defined("APP_ID") ? "" . APP_ID . "" : "0")
            );

            $this->db->insert('aws_channel', $insert);

            $this->db->where('input_id', $input['input_id']);
            $this->db->update('aws_channel_input', array('state' => 'Attached'));

            $this->db->where('channel_id', $media_package['channel_id']);
            $this->db->update('aws_media_package_channel', array('state' => '1'));
        }
    }

    function get_channels() {
        $studio_id = isset($this->session->userdata("active_user_data")->channel_ids) ? $this->session->userdata("active_user_data")->channel_ids : 0;
        app_permission("aws_channel.app_id",$this->db);
        $this->db->select('aws_channel.*,'
                . 'CONCAT("rtmp://",ip_a,":",port_a,"/",destination_a_name,"  --Stream Key: ",destination_a_key) as input_a,'
                . 'CONCAT("rtmp://",ip_b,":",port_b,"/",destination_b_name,"  --Stream Key: ",destination_b_key) as input_b', FALSE);
        $this->db->join('aws_channel_input', 'aws_channel.input_ids LIKE CONCAT("%",`aws_channel_input`.`input_id`,"%")');
        if ($studio_id)
            $this->db->where("aws_channel.studio_id", $studio_id);
        return $this->db->get('aws_channel')->result_array();

    }

    /*     * ********************Code for analytics start *************************************** */

    public function channel_metrics($id, $metrics, $time = 0) {
        $client = new Aws\CloudWatch\CloudWatchClient($this->Credentials_model->get_credentials());
        if ($time) {
            $result = $this->get_time_filter($time);
            $start = $result['start'];
            $period = $result['period'];
            $end = date('Y-m-d H:i:s', time()); //date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($start)));;//
//             echo "start :".$start ." period :".$period." end :".$end;die;
            return $result = $client->getMetricStatistics([
                'Dimensions' => [
                    [
                        'Name' => 'Pipeline', // REQUIRED
                        'Value' => '0', // REQUIRED
                    ],
                    [
                        'Name' => 'ChannelId', // REQUIRED
                        'Value' => $id, // REQUIRED
                    ],
                // ...
                ],
                'EndTime' => $end, //"2021-06-16T11:05:00.589Z", // REQUIRED
//    'ExtendedStatistics' => ['<string>', ...],
                'LabelOptions' => [
                    'Timezone' => +0530,
                ],
                'MetricName' => $metrics, //InputVideoFrameRate', //SvqTime', // REQUIRED
                'Namespace' => 'MediaLive', // REQUIRED
                'Period' => $period, // 60 REQUIRED 
                'StartTime' => $start, //"2021-06-16T11:00:33.589Z", // REQUIRED
                'Statistics' => ["Average"],
//    'Unit' => 'Count',//Seconds|Microseconds|Milliseconds|Bytes|Kilobytes|Megabytes|Gigabytes|Terabytes|Bits|Kilobits|Megabits|Gigabits|Terabits|Percent|Count|Bytes/Second|Kilobytes/Second|Megabytes/Second|Gigabytes/Second|Terabytes/Second|Bits/Second|Kilobits/Second|Megabits/Second|Gigabits/Second|Terabits/Second|Count/Second|None',
            ]);
        } else {
            $start = date('Y-m-d H:i:s');
            $period = 60;
            $end = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($start)));
            return $result = $client->getMetricStatistics([
                'Dimensions' => [
                    [
                        'Name' => 'Pipeline', // REQUIRED
                        'Value' => '0', // REQUIRED
                    ],
                    [
                        'Name' => 'ChannelId', // REQUIRED
                        'Value' => $id, // REQUIRED
                    ],
                // ...
                ],
                'EndTime' => $start, //"2021-06-16T11:05:00.589Z", // REQUIRED
//    'ExtendedStatistics' => ['<string>', ...],
                'LabelOptions' => [
                    'Timezone' => +0530,
                ],
                'MetricName' => $metrics, //InputVideoFrameRate', //SvqTime', // REQUIRED
                'Namespace' => 'MediaLive', // REQUIRED
                'Period' => $period, // 60 REQUIRED 
                'StartTime' => $end, //"2021-06-16T11:00:33.589Z", // REQUIRED
                'Statistics' => ["Average"],
//    'Unit' => 'Count',//Seconds|Microseconds|Milliseconds|Bytes|Kilobytes|Megabytes|Gigabytes|Terabytes|Bits|Kilobits|Megabits|Gigabits|Terabits|Percent|Count|Bytes/Second|Kilobytes/Second|Megabytes/Second|Gigabytes/Second|Terabytes/Second|Bits/Second|Kilobits/Second|Megabits/Second|Gigabits/Second|Terabits/Second|Count/Second|None',
            ]);
        }
    }

    public function get_time_filter($time) {
        if ($time == "1") {
            $start = date('Y-m-d H:i:s', strtotime('-60 minutes', time()));
            $period = 60 * 1;
        } else if ($time == "2") {
            $start = date('Y-m-d H:i:s', strtotime('-180 minutes', time()));
            $period = 60 * 1;
        } else if ($time == "3") {
            $start = date('Y-m-d H:i:s', strtotime('-720 minutes', time()));
            $period = 60 * 5;
        } else if ($time == "4") {
            $start = date('Y-m-d H:i:s', strtotime('-1 day', time()));
            $period = 60 * 5;
        } else if ($time == "5") {
            $start = date('Y-m-d H:i:s', strtotime('-3 day', time()));
            $period = 60 * 15;
        } else if ($time == "6") {
            $start = date('Y-m-d H:i:s', strtotime('-1 week', time()));
            $period = 60 * 15;
        }
        $result['start'] = $start;
        $result['period'] = $period;
        return $result;
    }

    /*     * ********************Code for analytics End *************************************** */

    // function start_channel($id, $channel_id) {
    //     // $client = new Aws\MediaLive\MediaLiveClient($this->Credentials_model->get_credentials());
    //     // $result = $client->startChannel([
    //     //     'ChannelId' => $channel_id, // REQUIRED
    //     // ]);
    //     $vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"),''));
    //     if($vc_key->vc_access_key && $vc_key->vc_secret_key){
    //             $data=array(
    //             "channel_id"=> $channel_id,
    //             "other_id"=> 2);
    //             $accesskey= base64_encode($vc_key->vc_access_key);
    //             $secret=base64_encode($vc_key->vc_secret_key);
    //             $headers = array("accessKey:$accesskey","secretKey:$secret");
    //             $ch = curl_init();
    //             curl_setopt($ch, CURLOPT_URL, "https://www.videocrypt.in/rest_api/channel/start_channel");
    //             curl_setopt($ch, CURLOPT_POST, true);
    //             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //             curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
    //             $data = curl_exec($ch);
    //             curl_close($ch);        
    //             $play_list = json_decode($data,true);
                
    //             if($play_list['status']){
    //                 $this->db->where('id', $id);
    //                 $this->db->update('aws_channel', array('state' => 'Running'));
    //                 $data = $this->db->select('aws_channel.id,aws_channel.channel_id,aws_channel.channel_name')->from('aws_channel')->where('aws_channel.id', $id)->get()->row_array();
    //                 backend_log_genration($this, 'Channel Start -: ' . $data['channel_name'], 'Channel');
    //                 page_alert_box("success", "Request Triggered Successfully", "Request Sent For Start Channel");
    //             }
    //          }
      
    //     /* put a log */
        
    //     redirect($_SERVER['HTTP_REFERER']);
    // }

      function start_channel($id,$channel_id) {
        $vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"),''));
        // if($vc_key->vc_access_key && $vc_key->vc_secret_key){
                $data=array(
                "channel_id"=> $channel_id);
                $accesskey= base64_encode($vc_key->vc_access_key);
                $secret=base64_encode($vc_key->vc_secret_key);
         $id = $channel_id;
         $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://www.videocrypt.in/rest_api/channel/start_channel',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('channel_id' => $id ),
          CURLOPT_HTTPHEADER => array(
             'accessKey: $accesskey',
                    'secretKey: $secret',
                ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        header('Content-Type: application/json');
        $response = json_decode($response, true);
        $this->db->where('channel_id', $channel_id);
        $this->db->update('aws_channel', array('state' => 'Running'));
        // page_alert_box("success", "Request Triggered Successfully", "Request Sent For Start Channel");
        redirect($_SERVER['HTTP_REFERER']);
    }

    // function stop_channel($id, $channel_id) {
    //     // $client = new Aws\MediaLive\MediaLiveClient($this->Credentials_model->get_credentials());
    //     // $result = $client->stopChannel([
    //     //     'ChannelId' => $channel_id, // REQUIRED
    //     // ]);
    //     $vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"),''));
    //     if($vc_key->vc_access_key && $vc_key->vc_secret_key){
    //             $data=array(
    //             "channel_id"=> $channel_id);
    //             $accesskey= base64_encode($vc_key->vc_access_key);
    //             $secret=base64_encode($vc_key->vc_secret_key);
    //             $headers = array("accessKey:$accesskey","secretKey:$secret");
    //             $ch = curl_init();
    //             curl_setopt($ch, CURLOPT_URL, "https://www.videocrypt.in/rest_api/channel/stop_channel");
    //             curl_setopt($ch, CURLOPT_POST, true);
    //             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //             curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //             $data = curl_exec($ch);
    //             curl_close($ch);        
    //             $play_list = json_decode($data,true);
    //             if($play_list['status']){
    //                 $this->db->where('id', $id);
    //                 $this->db->update('aws_channel', array('state' => 'idle'));
    //                 $data = $this->db->select('aws_channel.id,aws_channel.channel_id,aws_channel.channel_name')->from('aws_channel')->where('aws_channel.id', $id)->get()->row_array();
    //             }
    //          }

        

    //     $data = $this->db->select('aws_channel.id,aws_channel.channel_id,aws_channel.channel_name')->from('aws_channel')->where('aws_channel.id', $id)->get()->row_array();
    //     /* put a log */
    //     backend_log_genration($this, 'Channel Stop -: ' . $data['channel_name'], 'Channel');
    // }

    function stop_channel($id,$channel_id ) {
       $vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"),''));
          $id = $channel_id;
           $accesskey= base64_encode($vc_key->vc_access_key);
           $secret=base64_encode($vc_key->vc_secret_key);
          $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://www.videocrypt.in/rest_api/channel/stop_channel',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('channel_id' => $id ),
          CURLOPT_HTTPHEADER => array(
               'accessKey: $accesskey',
                'secretKey: $secret',
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        header('Content-Type: application/json');
        $response = json_decode($response, true);
        //pre($id);die("test");
        $this->db->where('channel_id', $id);
        $a = $this->db->update('aws_channel', array('state' => 'idle'));
       // pre($a);die("aa");

        page_alert_box("success", "Request Triggered Successfully", "Request Sent For Stop Channel");
        //redirect($_SERVER['HTTP_REFERER']);
    }

    function delete_channel($id, $channel_id) {
        $client = new Aws\MediaLive\MediaLiveClient($this->Credentials_model->get_credentials());
        $result = $client->deleteChannel([
            'ChannelId' => $channel_id, // REQUIRED
        ]);

        $this->db->where('id', $id);
        $channel = $this->db->get('aws_channel')->row_array();
        if ($channel) {
            $input_ids = json_decode($channel['input_ids'], TRUE);
            if ($input_ids) {
                foreach ($input_ids as $ids) {
                    $this->db->where('input_id', $ids['InputId']);
                    $this->db->update('aws_channel_input', array('state' => 'DETACHED'));
                }
            }
        }

        $this->db->where('id', $id);
        $this->db->delete('aws_channel');

        /* put a log */
        backend_log_genration($this, 'Deleted Channel -: ' . $channel_id, 'Channel');

        redirect($_SERVER['HTTP_REFERER']);
    }

    function create_cloudfront_url($type = "") {
        $input = $this->input->post();
        if (($input) || ($type != "")) {

            if ($type != "") {


                $_POST['flag'] = '1';
                $file_meta['file_url'] = $type;
            } else {
                $this->load->helper("aes");
                //            $input['url'] = '44_5_6271191221196205';
                $url_meta = explode("_", $input['url']);
                if (!$url_meta || count($url_meta) < 3) {
                    return_data(false, 'Invalid Request', array());
                }
                $this->db->select("video_type,file_url");
                $this->db->where("id", $url_meta[0]);
                $file_meta = $this->db->get("course_topic_file_meta_master")->row_array();

                $file_meta['file_url'] = aes_cbc_decryption($file_meta['file_url'], end($url_meta));
            }

//             $this->load->helper("aes");
// //            $input['url'] = '44_5_6271191221196205';
//             $url_meta = explode("_", $input['url']);
//             if (!$url_meta || count($url_meta) < 3) {
//                 return_data(false, 'Invalid Request', array());
//             }
//             $this->db->select("video_type,file_url");
//             $this->db->where("id", $url_meta[0]);
//             $file_meta = $this->db->get("course_topic_file_meta_master")->row_array();
//             $file_meta['file_url'] = aes_cbc_decryption($file_meta['file_url'], end($url_meta));

            $cloud_front_domain = "";
            if (strpos($file_meta['file_url'], "out") !== false) {
                if (strpos($file_meta['file_url'], "5d720e47102d4a9f896841ab8a68b83d") !== false)
                    $cloud_front_domain = "cf2.nextias.com"; //"d2lu779ncj5san.cloudfront.net"; //mediapackage distribution for channel (NI-Live-A)
                else if (strpos($file_meta['file_url'], "7f62175ea5c04205a00c066249721476") !== false)
                    $cloud_front_domain = "cf2.nextias.com"; //"d2lu779ncj5san.cloudfront.net"; //mediapackage distribution for channel (NI-Live-B)
                else if (strpos($file_meta['file_url'], "26a8fcfad5f241ef9c06553c40b9e393") !== false)
                    $cloud_front_domain = "cf4.nextias.com"; //"duuhvvtaqafir.cloudfront.net"; //mediapackage distribution for channel (NI-Live-C)
                else if (strpos($file_meta['file_url'], "eee8fafa234d4f058cc895a32595a480") !== false)
                    $cloud_front_domain = "cf4.nextias.com"; //"duuhvvtaqafir.cloudfront.net"; //mediapackage distribution for channel (NI-Live-D)
                else if (strpos($file_meta['file_url'], "eb9cc7eea25a48cc992f01ccd6065244") !== false)
                    $cloud_front_domain = "cf4.nextias.com"; //"duuhvvtaqafir.cloudfront.net"; //mediapackage distribution for channel (NI-Live-E)
                else if (strpos($file_meta['file_url'], "bc53ac5be8164129b1bbf2dd4a5b48b9") !== false)
                    $cloud_front_domain = "cf2.nextias.com"; //"d2lu779ncj5san.cloudfront.net"; //mediapackage distribution for channel (NI-Live-F)
                else if (strpos($file_meta['file_url'], "003ff09fb4d048b18551ca510fa765e9") !== false)
                    $cloud_front_domain = "cf2.nextias.com"; //mediapackage distribution for channel (NI-Live-G)	
                else if (strpos($file_meta['file_url'], "123806cb3c204e4da9f8bd6da2f3ec02") !== false)
                    $cloud_front_domain = "cf2.nextias.com"; //mediapackage distribution for channel (NI-Live-H)
            } else if ($file_meta["video_type"] == 0) {
                $cloud_front_domain = "cf3.nextias.com"; //"d3mb5zdyi9tun5.cloudfront.net";  //s3 distribution domain
            }

            $cloudfront = new Aws\CloudFront\CloudFrontClient([
                'version' => 'latest',
                'region' => AMS_REGION,
            ]);

            $url = $cloudfront->getSignedUrl([
                'url' => "https://$cloud_front_domain/{$file_meta['file_url']}",
                'expires' => time() + 500000000, //5:30 hours for ist + 1 hour for expire
                'private_key' => FCPATH . 'next_ias_cloudfront.pem',
                'key_pair_id' => CLOUDFRONT_KEY_PAIR
            ]);

            $url = str_replace('%2F', "/", $url);
            if ($this->input->post("flag"))
                return $url;
            else
                return aes_cbc_encryption($url, end($url_meta));
        }
        return "";
    }

    function on_request_cf_link($url, $video_type, $cf_domain = "") {
        return $url;
        $input = $this->input->post();
        if ($input) {
            $file_meta['file_url'] = $url;
            $file_meta["video_type"] = $video_type;
            //in case of harvest job
//            if (strpos($url, 'https://utk-media.s3.ap-south-1.amazonaws.com/') !== false) {
//            }
            return $url;


            $cloud_front_domain = ""; //media store distribution domain
            if ($file_meta["video_type"] == 0) {
                $cloud_front_domain = S3_CLOUDFRONT_DOMAIN;
            } else if (strpos($file_meta['file_url'], "out") !== false) {
                /* Streaming URL Generation */
                if (!$cf_domain)
                    return_data(false, "Unable to generate URL. Please try again after some time or contact to " . CONFIG_PROJECT_FULL_NAME . " support");
                $cloud_front_domain = $cf_domain;
            }
            $cloudfront = new Aws\CloudFront\CloudFrontClient([
                'version' => 'latest',
                'region' => AMS_REGION,
            ]);

            $file_url = str_replace(".m4v", ".m3u8", $file_meta['file_url']);
            $url = "https://{$cloud_front_domain}/{$file_url}";
//            $url = $cloudfront->getSignedUrl([
//                'url' => "https://$cloud_front_domain/{$file_meta['file_url']}",
//                'expires' => time() + 500000000, //5:30 hours for ist + 1 hour for expire
//                'private_key' => FCPATH . 'utkarsh_cf.pem',
//                'key_pair_id' => CLOUDFRONT_KEY_PAIR
//            ]);

            return str_replace('%2F', "/", $url);
        }
        return "";
    }

    function create_cloudfront_url_v2() {
        $input = $this->input->post();
        if ($input) {
            $this->load->helper("aes");
//            $input['url'] = '44_5_6271191221196205';
            $url_meta = explode("_", $input['url']);
            if (!$url_meta || count($url_meta) < 3) {
                return_data(false, 'Invalid Request', array());
            }
            $this->db->select("video_type,file_url");
            $this->db->where("id", $url_meta[0]);
            $file_meta = $this->db->get("course_topic_file_meta_master")->row_array();

            $file_meta['file_url'] = aes_cbc_decryption($file_meta['file_url'], end($url_meta));

            if (strpos($file_meta['file_url'], "net") !== false) {
                $explode = explode("net/", $file_meta['file_url']);
                $file_meta['file_url'] = $explode[1];
                $explode = $explode[0];
                $explode .= "net";
                $cloud_front_domain = str_replace("https://", "", $explode);
            } else {
                $cloud_front_domain = "cf3.nextias.com"; //"d3mb5zdyi9tun5.cloudfront.net";  //s3 distribution domain
            }

            $cloudfront = new Aws\CloudFront\CloudFrontClient([
                'version' => 'latest',
                'region' => AMS_REGION,
            ]);
//                    $cloud_front_domain = "d1j2jjb36vtf3u.cloudfront.net";
//            $file_meta['file_url'] = "images.jpeg";
            $url = "";
            if (strpos($cloud_front_domain, "d1j2jjb36vtf3u") !== false) {
                $url = $cloudfront->getSignedCookie([
                    'url' => "https://$cloud_front_domain/{$file_meta['file_url']}",
                    'expires' => time() + 500000000, //5:30 hours for ist + 1 hour for expire
                    'private_key' => FCPATH . 'next_ias_cloudfront.pem',
                    'key_pair_id' => CLOUDFRONT_KEY_PAIR
                ]);

                if ($this->input->post("flag")) {
                    foreach ($url as $name => $value) {
                        setcookie($name, $value, 0, "", $cloud_front_domain, true, true);
                    }
                }
            } else {
                $url = $cloudfront->getSignedUrl([
                    'url' => "https://$cloud_front_domain/{$file_meta['file_url']}",
                    'expires' => time() + 500000000, //5:30 hours for ist + 1 hour for expire
                    'private_key' => FCPATH . 'next_ias_cloudfront.pem',
                    'key_pair_id' => CLOUDFRONT_KEY_PAIR
                ]);
            }
//            echo "https://$cloud_front_domain/{$file_meta['file_url']}";
//            pre($url);
            if ($this->input->post("flag"))
                return $url;
            else {
                $return = array();
                if (is_array($url)) {
                    $return = $url;
                    $return['url'] = aes_cbc_encryption("https://$cloud_front_domain/{$file_meta['file_url']}", end($url_meta));
                } else {
                    $url = str_replace('%2F', "/", $url);
                    $return['url'] = aes_cbc_encryption($url, end($url_meta));
                }
                return $return;
            }
        }
        return array();
    }

    //Dist with signed Cookie. we are using it for VIDEO link only	
    function create_cloudfront_signed_cookie($url, $video_type, $cf_domain = "") {
        $input = $this->input->post();
        if ($input) {
            $file_meta['file_url'] = $url;
            $file_meta["video_type"] = $video_type;
//            echo $file_meta['file_url'];die;	
            $cloud_front_domain = ""; //media store distribution domain	
            if ($file_meta["video_type"] == 0) {
                if (strpos($file_meta['file_url'], "out") !== false)
                    $cloud_front_domain = "cf1.nextias.com";
                else
                    $cloud_front_domain = "cf3.nextias.com"; //s3 distribution domain	
            } else if (strpos($file_meta['file_url'], "out") !== false) {
                /* Streaming URL Generation */
                if (!$cf_domain)
                    return_data(false, "Unable to generate URL. Please try again after some time or contact to " . CONFIG_PROJECT_FULL_NAME . " support");
                $cloud_front_domain = $cf_domain;
            }
            $return['cookies'] = $cloud_front_domain == "cf3.nextias.com" ? $this->createSignedCookie("https://$cloud_front_domain", "file_library/videos/*", (3600 * 3)) : array();
            $return['url'] = str_replace('%2F', "/", "https://$cloud_front_domain/" . $url);
            return $return;
        }
        return array();
    }

    private function createSignedCookie($streamHostUrl, $resourceKey, $timeout) {
        $expires = time() + $timeout; // Expire Time	
        $url = $streamHostUrl . '/' . $resourceKey; // Service URL	
        $ip = $_SERVER["REMOTE_ADDR"] . "\/24"; // IP	
        $json = '{"Statement":[{"Resource":"' . $url . '","Condition":{"DateLessThan":{"AWS:EpochTime":' . $expires . '}}}]}';
        $fp = fopen(FCPATH . 'next_ias_cloudfront.pem', "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $key = openssl_get_privatekey($priv_key);
        if (!$key) {
            return_data(false, "Failed to load private key", array());
        }
        if (!openssl_sign($json, $signed_policy, $key, OPENSSL_ALGO_SHA1)) {
            //echo '<p>Failed to sign policy: ' . opeenssl_error_string() . '</p>';	
            return_data(false, "Failed to sign policy", array());
        }
        $base64_signed_policy = base64_encode($signed_policy);
        $policy = strtr(base64_encode($json), '+=/', '-_~'); //Canned Policy	
        $signature = str_replace(array('+', '=', '/'), array('-', '_', '~'), $base64_signed_policy);
        //In case you want to use signed URL, just use the below code	
        //$signedUrl = $url.'?Expires='.$expires.'&Signature='.$signature.'&Key-Pair-Id='.CLOUDFRONT_KEY_PAIR; //Manual Policy	
        $signedCookie = array(
            "CloudFront-Key-Pair-Id" => CLOUDFRONT_KEY_PAIR,
            "CloudFront-Policy" => $policy,
            "CloudFront-Signature" => $signature
        );
        return $signedCookie;
    }

    function create_cloudfront_signed_url($url) {
        $cloud_front_domain = "cf5.nextias.com"; //S3 singed URL distribution domain
        $cloudfront = new Aws\CloudFront\CloudFrontClient([
            'version' => 'latest',
            'region' => AMS_REGION,
        ]);

        $url = $cloudfront->getSignedUrl([
            'url' => "https://$cloud_front_domain/{$url}",
            'expires' => time() + 500000000, //5:30 hours for ist + 1 hour for expire
            'private_key' => FCPATH . 'next_ias_cloudfront.pem',
            'key_pair_id' => CLOUDFRONT_KEY_PAIR
        ]);

        return $url;
    }

}
