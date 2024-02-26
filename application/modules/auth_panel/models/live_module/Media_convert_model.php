<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(APPPATH . '/third_party/aws/aws-autoloader.php');

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\MediaConvert\MediaConvertClient;

class Media_convert_model extends CI_Model {

    protected $pallycon_token = "eyJhY2Nlc3Nfa2V5IjoiMmRFT2J5aFwvSlMxcUVzbFNcL3JndTh3WXc2VEZQNFU2djJpSGhzdjdSaWVhcnplOTJWekJzS250WVRjS2FhTzJ0Iiwic2l0ZV9pZCI6IlhJSVUifQ==";

    function __construct() {
        parent::__construct();
        define("TIME_STAMP", time());
        define("ARN_QUEUE_RESERVED", '');
        define("ARN_IAM_ROLE", "");
        define("ARN_QUEUE_CF", 'arn:aws:mediaconvert:ap-south-1:771608383469:queues/Default');
        define("ARN_IAM_ROLE_CF", 'arn:aws:iam::771608383469:role/MediaConvert_Default_Role');
        define("API_ENDPOINT", "https://xnbzilj6c.mediaconvert.ap-south-1.amazonaws.com");
    }

    function create_job($file_name, $directory, $drm_dash_dir, $drm_hls_dir, $video_id, $is_demand_request = 0) {

        if (!$file_name)
            die("Invalid File Name");
        if (!$directory)
            die("Invalid Directory");

        /*
         * Input Bucket Configuration Dynamic (Same/Cross Account)
         */
        $input_bucket = AMS_BUCKET_NAME;
        if (strpos($file_name, "me.s3")) {
//            $input_bucket = "me-internal";
        } else if (strpos($file_name, "utk-media.s3")) {
            $input_bucket = "utk-media";
        }

        $file_name = explode("amazonaws.com/", $file_name);
        $file_name = end($file_name);

        $output_group = array();
        $output_group[] = $this->get_hls_output($directory);
//        if ($drm_hls_dir) {
//            $resource_id = $this->generate_resource_id($file_name, $video_id);
//            $output_group[] = $this->get_hls_output($drm_hls_dir, $resource_id);
//        }

        $output_group = array();
        if ($drm_hls_dir) {
            $resource_id = $this->generate_resource_id($file_name, $video_id);
            $output_group[] = $this->get_hls_output($drm_hls_dir, $resource_id);
        } else {
            $output_group[] = $this->get_hls_output($directory);
        }

        if ($drm_dash_dir) {
            $resource_id = $this->generate_resource_id($file_name, $video_id);
            $output_group[] = $this->get_dash_output($drm_dash_dir, $resource_id);
        }

        $client = new MediaConvertClient($this->Credentials_model->media_convert_credentials($is_demand_request));
        $result = $client->createJob(
                array(
                    'Queue' => ARN_QUEUE_CF,//$is_demand_request ? ARN_QUEUE_CF : ARN_QUEUE_RESERVED,
                    'UserMetadata' => array(),
                    'Role' => ARN_IAM_ROLE_CF,//$is_demand_request ? ARN_IAM_ROLE_CF : ARN_IAM_ROLE,
                    'Settings' => array(
                        'OutputGroups' => $output_group,
                        'AdAvailOffset' => 0,
                        'Inputs' => array(
                            array(
                                'AudioSelectors' => array(
                                    'Audio Selector 1' => array(
                                        'Offset' => 0,
                                        'DefaultSelection' => 'DEFAULT',
                                        'ProgramSelection' => 1,
                                    ),
                                ),
                                'VideoSelector' => array(
                                    'ColorSpace' => 'FOLLOW',
                                    'Rotate' => 'DEGREE_0',
                                    'AlphaBehavior' => 'DISCARD',
                                ),
                                'FilterEnable' => 'AUTO',
                                'PsiControl' => 'USE_PSI',
                                'FilterStrength' => 0,
                                'DeblockFilter' => 'DISABLED',
                                'DenoiseFilter' => 'DISABLED',
                                'TimecodeSource' => 'ZEROBASED',
                                'FileInput' => 's3://' . $input_bucket . '/' . $file_name,
                            ),
                        ),
                    ),
                    'AccelerationSettings' => array(
                        'Mode' => 'DISABLED',
                    ),
                    'StatusUpdateInterval' => 'SECONDS_10',
                    'Priority' => 0,
        ));

        $result = (array) $result;
        $data = $this->Credentials_model->refine_array($result);
        $return = array(
            "id" => $data["Aws\Resultdata"]["Job"]['Id'],
            "status" => $data["Aws\Resultdata"]["Job"]['Status'],
            "is_demand_request" => $is_demand_request
        );
        return $return;
    }

    function create_job_dash($file_name, $directory, $video_id) {
        if (!$file_name)
            die("Invalid File Name");
        if (!$directory)
            die("Invalid Directory");
        $file_name = explode("amazonaws.com/", $file_name);
        $file_name = end($file_name);

        $resource_id = $this->generate_resource_id($file_name, $video_id);

        $output_groups = array();
        $output_groups[] = $this->get_dash_output($directory, $resource_id);

        $client = new MediaConvertClient($this->Credentials_model->media_convert_credentials());
        $result = $client->createJob(
                array(
                    'Queue' => ARN_QUEUE_CF,
                    'UserMetadata' => array(),
                    'Role' => ARN_IAM_ROLE_CF,
                    'Settings' => array(
                        'TimecodeConfig' => array(
                            'Source' => 'ZEROBASED',
                        ),
                        'OutputGroups' => $output_groups,
                        'AdAvailOffset' => 0,
                        'Inputs' => array(
                            array(
                                'AudioSelectors' => array(
                                    'Audio Selector 1' => array(
                                        'Offset' => 0,
                                        'DefaultSelection' => 'DEFAULT',
                                        'ProgramSelection' => 1,
                                    ),
                                ),
                                'VideoSelector' => array(
                                    'ColorSpace' => 'FOLLOW',
                                    'Rotate' => 'DEGREE_0',
                                    'AlphaBehavior' => 'DISCARD',
                                ),
                                'FilterEnable' => 'AUTO',
                                'PsiControl' => 'USE_PSI',
                                'FilterStrength' => 0,
                                'DeblockFilter' => 'DISABLED',
                                'DenoiseFilter' => 'DISABLED',
                                'InputScanType' => 'AUTO',
                                'TimecodeSource' => 'ZEROBASED',
                                'FileInput' => 's3://' . AMS_BUCKET_NAME . '/' . $file_name,
                            ),
                        ),
                    ),
                    'AccelerationSettings' => array(
                        'Mode' => 'DISABLED',
                    ),
                    'StatusUpdateInterval' => 'SECONDS_10',
                    'Priority' => 0,
                )
        );
        $result = (array) $result;
//        pre($result);
        $data = $this->Credentials_model->refine_array($result);
        $return = array(
            "id" => $data["Aws\Resultdata"]["Job"]['Id'],
            "status" => $data["Aws\Resultdata"]["Job"]['Status'],
            "is_demand_request" => 0
        );
        return $return;
    }

    function track_job($job_id, $is_demand_request = 0) {
        try {
            $client = new MediaConvertClient($this->Credentials_model->media_convert_credentials($is_demand_request));
            $result = $client->getJob([
                'Id' => $job_id
            ]);

            $result = (array) $result;

            $data = $this->Credentials_model->refine_array($result);
            if ($this->router->fetch_class() == "media_convert" && $this->router->fetch_method() == "ajax_track_job") {
                return $data["Aws\Resultdata"]["Job"];
            }
            $return = array(
                "id" => $data["Aws\Resultdata"]["Job"]['Id'],
                "status" => $data["Aws\Resultdata"]["Job"]['Status'],
                "percent" => $data["Aws\Resultdata"]["Job"]['JobPercentComplete'] ?? 0
            );

            if ($return['status'] == "COMPLETE") {
                $return['percent'] = 100;
            }
            return $return;
        } catch (Exception $exc) {
            return array();
        }
    }

    private function get_hls_output($directory, $resource_id = "") {
        $return = array(
            'CustomName' => 'Bitrate',
            'Name' => ($resource_id ? 'DRM ' : '') . 'Apple HLS',
            'Outputs' => array(
                array(
                    'ContainerSettings' => array(
                        'Container' => 'M3U8',
                        'M3u8Settings' => array(
                            'AudioFramesPerPes' => 4,
                            'PcrControl' => 'PCR_EVERY_PES_PACKET',
                            'PmtPid' => 480,
                            'PrivateMetadataPid' => 503,
                            'ProgramNumber' => 1,
                            'PatInterval' => 0,
                            'PmtInterval' => 0,
                            "Scte35Source" => "PASSTHROUGH",
                            "Scte35Pid" => 500,
                            "NielsenId3" => "INSERT",
                            "TimedMetadata" => "PASSTHROUGH",
                            "TimedMetadataPid" => 502,
                            'VideoPid' => 481,
                            'AudioPids' => array(
                                0 => 482,
                                1 => 483,
                                2 => 484,
                                3 => 485,
                                4 => 486,
                                5 => 487,
                                6 => 488,
                                7 => 489,
                                8 => 490,
                                9 => 491,
                                10 => 492,
                            ),
                        ),
                    ),
                    'VideoDescription' => array(
                        'Width' => 426,
                        'ScalingBehavior' => 'DEFAULT',
                        'Height' => 240,
                        'TimecodeInsertion' => 'DISABLED',
                        'AntiAlias' => 'ENABLED',
                        'Sharpness' => 80,
                        'CodecSettings' => array(
                            'Codec' => 'H_264',
                            'H264Settings' => array(
                                'InterlaceMode' => 'PROGRESSIVE',
                                'NumberReferenceFrames' => 2,
                                'Syntax' => 'DEFAULT',
                                'Softness' => 0,
                                'FramerateDenominator' => 1,
                                'GopClosedCadence' => 1,
                                "GopSize" => 2,
                                "Slices" => 1,
                                "GopBReference" => "DISABLED",
                                "HrdBufferSize" => 600000,
                                'MaxBitrate' => 300000,
                                'SlowPal' => 'DISABLED',
                                'SpatialAdaptiveQuantization' => 'ENABLED',
                                'TemporalAdaptiveQuantization' => 'ENABLED',
                                'FlickerAdaptiveQuantization' => 'DISABLED',
                                'EntropyEncoding' => 'CAVLC',
//                              'Bitrate' => 100000,
                                'FramerateControl' => 'SPECIFIED', //INITIALIZE_FROM_SOURCE
                                'RateControlMode' => 'QVBR',
                                "QvbrSettings" => array(
                                    "QvbrQualityLevel" => 5,
                                    "QvbrQualityLevelFineTune" => 0
                                ),
                                'CodecProfile' => 'BASELINE',
                                'Telecine' => 'NONE',
                                "FramerateNumerator" => 25,
                                'MinIInterval' => 0,
                                'AdaptiveQuantization' => 'HIGH',
                                'CodecLevel' => 'AUTO',
                                'FieldEncoding' => 'PAFF',
                                'SceneChangeDetect' => 'ENABLED',
                                'QualityTuningLevel' => 'SINGLE_PASS',
                                'FramerateConversionAlgorithm' => 'DUPLICATE_DROP',
                                'UnregisteredSeiTimecode' => 'DISABLED',
                                'GopSizeUnits' => 'SECONDS',
                                'ParControl' => 'SPECIFIED',
                                'ParDenominator' => 1,
                                'ParNumerator' => 1,
                                'NumberBFramesBetweenReferenceFrames' => 0,
                                'RepeatPps' => 'DISABLED',
                                'DynamicSubGop' => 'ADAPTIVE',
                            ),
                        ),
                        'AfdSignaling' => 'NONE',
                        'DropFrameTimecode' => 'ENABLED',
                        'RespondToAfd' => 'NONE',
                        'ColorMetadata' => 'INSERT',
                    ),
                    'AudioDescriptions' => array(
                        array(
                            'AudioTypeControl' => 'FOLLOW_INPUT',
                            'CodecSettings' => array(
                                'Codec' => 'AAC',
                                'AacSettings' => array(
                                    'AudioDescriptionBroadcasterMix' => 'NORMAL',
                                    'Bitrate' => 64000,
                                    'RateControlMode' => 'CBR',
                                    'CodecProfile' => 'LC',
                                    'CodingMode' => 'CODING_MODE_2_0',
                                    'RawFormat' => 'NONE',
                                    'SampleRate' => 48000,
                                    'Specification' => 'MPEG4',
                                ),
                            ),
                            'LanguageCodeControl' => 'FOLLOW_INPUT',
                        ),
                    ),
                    'OutputSettings' => array(
                        'HlsSettings' => array(
                            'AudioGroupId' => 'program_audio',
                            'AudioOnlyContainer' => 'AUTOMATIC',
                            'IFrameOnlyManifest' => 'EXCLUDE',
                        ),
                    ),
                    'NameModifier' => '240b_' . TIME_STAMP,
                ),
                array(
                    'ContainerSettings' => array(
                        'Container' => 'M3U8',
                        'M3u8Settings' => array(
                            'AudioFramesPerPes' => 4,
                            'PcrControl' => 'PCR_EVERY_PES_PACKET',
                            'PmtPid' => 480,
                            'PrivateMetadataPid' => 503,
                            'ProgramNumber' => 1,
                            'PatInterval' => 0,
                            'PmtInterval' => 0,
                            "Scte35Source" => "PASSTHROUGH",
                            "Scte35Pid" => 500,
                            "NielsenId3" => "INSERT",
                            "TimedMetadata" => "PASSTHROUGH",
                            "TimedMetadataPid" => 502,
                            'VideoPid' => 481,
                            'AudioPids' => array(
                                0 => 482,
                                1 => 483,
                                2 => 484,
                                3 => 485,
                                4 => 486,
                                5 => 487,
                                6 => 488,
                                7 => 489,
                                8 => 490,
                                9 => 491,
                                10 => 492,
                            ),
                        ),
                    ),
                    'VideoDescription' => array(
                        'Width' => 640,
                        'ScalingBehavior' => 'DEFAULT',
                        'Height' => 360,
                        'TimecodeInsertion' => 'DISABLED',
                        'AntiAlias' => 'ENABLED',
                        'Sharpness' => 80,
                        'CodecSettings' => array(
                            'Codec' => 'H_264',
                            'H264Settings' => array(
                                'InterlaceMode' => 'PROGRESSIVE',
                                'NumberReferenceFrames' => 2,
                                'Syntax' => 'DEFAULT',
                                'Softness' => 0,
                                'FramerateDenominator' => 1,
                                'GopClosedCadence' => 1,
                                'GopSize' => 2,
                                'Slices' => 1,
                                'GopBReference' => 'ENABLED',
                                "HrdBufferSize" => 1400000,
                                "MaxBitrate" => 700000,
                                'SlowPal' => 'DISABLED',
                                'SpatialAdaptiveQuantization' => 'ENABLED',
                                'TemporalAdaptiveQuantization' => 'ENABLED',
                                'FlickerAdaptiveQuantization' => 'ENABLED',
                                'EntropyEncoding' => 'CABAC',
//                              'Bitrate' => 200000,
                                'FramerateControl' => 'SPECIFIED', //INITIALIZE_FROM_SOURCE
                                'RateControlMode' => 'QVBR',
                                "QvbrSettings" => array(
                                    "QvbrQualityLevel" => 6,
                                    "QvbrQualityLevelFineTune" => 0
                                ),
                                'CodecProfile' => 'HIGH',
                                'Telecine' => 'NONE',
                                "FramerateNumerator" => 25,
                                'MinIInterval' => 0,
                                'AdaptiveQuantization' => 'HIGH',
                                'CodecLevel' => 'AUTO',
                                'FieldEncoding' => 'PAFF',
                                'SceneChangeDetect' => 'ENABLED',
                                'QualityTuningLevel' => 'SINGLE_PASS',
                                'FramerateConversionAlgorithm' => 'DUPLICATE_DROP',
                                'UnregisteredSeiTimecode' => 'DISABLED',
                                'GopSizeUnits' => 'SECONDS',
                                'ParControl' => 'SPECIFIED',
                                'ParDenominator' => 1,
                                'ParNumerator' => 1,
                                'NumberBFramesBetweenReferenceFrames' => 2,
                                'RepeatPps' => 'DISABLED',
                                'DynamicSubGop' => 'ADAPTIVE',
                            ),
                        ),
                        'AfdSignaling' => 'NONE',
                        'DropFrameTimecode' => 'ENABLED',
                        'RespondToAfd' => 'NONE',
                        'ColorMetadata' => 'INSERT',
                    ),
                    'AudioDescriptions' => array(
                        array(
                            'AudioTypeControl' => 'FOLLOW_INPUT',
                            'CodecSettings' => array(
                                'Codec' => 'AAC',
                                'AacSettings' => array(
                                    'AudioDescriptionBroadcasterMix' => 'NORMAL',
                                    'Bitrate' => 64000,
                                    'RateControlMode' => 'CBR',
                                    'CodecProfile' => 'LC',
                                    'CodingMode' => 'CODING_MODE_2_0',
                                    'RawFormat' => 'NONE',
                                    'SampleRate' => 48000,
                                    'Specification' => 'MPEG4',
                                ),
                            ),
                            'LanguageCodeControl' => 'FOLLOW_INPUT',
                        ),
                    ),
                    'OutputSettings' => array(
                        'HlsSettings' => array(
                            'AudioGroupId' => 'program_audio',
                            'AudioOnlyContainer' => 'AUTOMATIC',
                            'IFrameOnlyManifest' => 'EXCLUDE',
                        ),
                    ),
                    'NameModifier' => '360b_' . TIME_STAMP,
                ),
                array(
                    'ContainerSettings' => array(
                        'Container' => 'M3U8',
                        'M3u8Settings' => array(
                            'AudioFramesPerPes' => 4,
                            'PcrControl' => 'PCR_EVERY_PES_PACKET',
                            'PmtPid' => 480,
                            'PrivateMetadataPid' => 503,
                            'ProgramNumber' => 1,
                            'PatInterval' => 0,
                            'PmtInterval' => 0,
                            "Scte35Source" => "PASSTHROUGH",
                            "Scte35Pid" => 500,
                            "NielsenId3" => "INSERT",
                            "TimedMetadata" => "PASSTHROUGH",
                            'TimedMetadataPid' => 502,
                            'VideoPid' => 481,
                            'AudioPids' => array(
                                0 => 482,
                                1 => 483,
                                2 => 484,
                                3 => 485,
                                4 => 486,
                                5 => 487,
                                6 => 488,
                                7 => 489,
                                8 => 490,
                                9 => 491,
                                10 => 492,
                            ),
                        ),
                    ),
                    'VideoDescription' => array(
                        'Width' => 854,
                        'ScalingBehavior' => 'DEFAULT',
                        'Height' => 480,
                        'TimecodeInsertion' => 'DISABLED',
                        'AntiAlias' => 'ENABLED',
                        'Sharpness' => 80,
                        'CodecSettings' => array(
                            'Codec' => 'H_264',
                            'H264Settings' => array(
                                'InterlaceMode' => 'PROGRESSIVE',
                                'NumberReferenceFrames' => 2,
                                'Syntax' => 'DEFAULT',
                                'Softness' => 0,
                                "FramerateDenominator" => 1,
                                'GopClosedCadence' => 1,
                                'GopSize' => 2,
                                'Slices' => 1,
                                'GopBReference' => 'ENABLED',
                                "HrdBufferSize" => 2200000,
                                "MaxBitrate" => 1100000,
                                'SlowPal' => 'DISABLED',
                                'SpatialAdaptiveQuantization' => 'ENABLED',
                                'TemporalAdaptiveQuantization' => 'ENABLED',
                                'FlickerAdaptiveQuantization' => 'ENABLED',
                                'EntropyEncoding' => 'CABAC',
//                              'Bitrate' => 400000,
                                'FramerateControl' => 'SPECIFIED', //INITIALIZE_FROM_SOURCE
                                'RateControlMode' => 'QVBR',
                                "QvbrSettings" => array(
                                    "QvbrQualityLevel" => 7,
                                    "QvbrQualityLevelFineTune" => 0
                                ),
                                'CodecProfile' => 'MAIN',
                                'Telecine' => 'NONE',
                                "FramerateNumerator" => 25,
                                'MinIInterval' => 0,
                                'AdaptiveQuantization' => 'HIGH',
                                'CodecLevel' => 'AUTO',
                                'FieldEncoding' => 'PAFF',
                                'SceneChangeDetect' => 'ENABLED',
                                'QualityTuningLevel' => 'SINGLE_PASS',
                                'FramerateConversionAlgorithm' => 'DUPLICATE_DROP',
                                'UnregisteredSeiTimecode' => 'DISABLED',
                                'GopSizeUnits' => 'SECONDS',
                                'ParControl' => 'SPECIFIED',
                                'ParDenominator' => 1,
                                'ParNumerator' => 1,
                                'NumberBFramesBetweenReferenceFrames' => 2,
                                'RepeatPps' => 'DISABLED',
                                'DynamicSubGop' => 'ADAPTIVE',
                            ),
                        ),
                        'AfdSignaling' => 'NONE',
                        'DropFrameTimecode' => 'ENABLED',
                        'RespondToAfd' => 'NONE',
                        'ColorMetadata' => 'INSERT',
                    ),
                    'AudioDescriptions' => array(
                        array(
                            'AudioTypeControl' => 'FOLLOW_INPUT',
                            'CodecSettings' => array(
                                'Codec' => 'AAC',
                                'AacSettings' => array(
                                    'AudioDescriptionBroadcasterMix' => 'NORMAL',
                                    'Bitrate' => 96000,
                                    'RateControlMode' => 'CBR',
                                    'CodecProfile' => 'LC',
                                    'CodingMode' => 'CODING_MODE_2_0',
                                    'RawFormat' => 'NONE',
                                    'SampleRate' => 48000,
                                    'Specification' => 'MPEG4',
                                ),
                            ),
                            'LanguageCodeControl' => 'FOLLOW_INPUT',
                        ),
                    ),
                    'OutputSettings' => array(
                        'HlsSettings' => array(
                            'AudioGroupId' => 'program_audio',
                            'AudioOnlyContainer' => 'AUTOMATIC',
                            'IFrameOnlyManifest' => 'EXCLUDE',
                        ),
                    ),
                    'NameModifier' => '480b_' . TIME_STAMP,
                ),
                array(
                    'ContainerSettings' => array(
                        'Container' => 'M3U8',
                        'M3u8Settings' => array(
                            'AudioFramesPerPes' => 4,
                            'PcrControl' => 'PCR_EVERY_PES_PACKET',
                            'PmtPid' => 480,
                            'PrivateMetadataPid' => 503,
                            'ProgramNumber' => 1,
                            'PatInterval' => 0,
                            "Scte35Source" => "PASSTHROUGH",
                            "Scte35Pid" => 500,
                            "NielsenId3" => "INSERT",
                            "TimedMetadata" => "PASSTHROUGH",
                            'TimedMetadataPid' => 502,
                            'VideoPid' => 481,
                            'AudioPids' => array(
                                0 => 482,
                                1 => 483,
                                2 => 484,
                                3 => 485,
                                4 => 486,
                                5 => 487,
                                6 => 488,
                                7 => 489,
                                8 => 490,
                                9 => 491,
                                10 => 492,
                            ),
                        ),
                    ),
                    'VideoDescription' => array(
                        'Width' => 1280,
                        'ScalingBehavior' => 'DEFAULT',
                        'Height' => 720,
                        'TimecodeInsertion' => 'DISABLED',
                        'AntiAlias' => 'ENABLED',
                        'Sharpness' => 80,
                        'CodecSettings' => array(
                            'Codec' => 'H_264',
                            'H264Settings' => array(
                                'InterlaceMode' => 'PROGRESSIVE',
                                'NumberReferenceFrames' => 2,
                                'Syntax' => 'DEFAULT',
                                'Softness' => 0,
                                "FramerateDenominator" => 1,
                                'GopClosedCadence' => 1,
                                'GopSize' => 2,
                                'Slices' => 1,
                                'GopBReference' => 'ENABLED',
                                "HrdBufferSize" => 3400000,
                                "MaxBitrate" => 1700000,
                                'SlowPal' => 'DISABLED',
                                'SpatialAdaptiveQuantization' => 'ENABLED',
                                'TemporalAdaptiveQuantization' => 'ENABLED',
                                'FlickerAdaptiveQuantization' => 'ENABLED',
                                'EntropyEncoding' => 'CABAC',
//                              'Bitrate' => 800000,
                                'FramerateControl' => 'SPECIFIED', //INITIALIZE_FROM_SOURCE
                                'RateControlMode' => 'QVBR',
                                "QvbrSettings" => array(
                                    "QvbrQualityLevel" => 8,
                                    "QvbrQualityLevelFineTune" => 0
                                ),
                                'CodecProfile' => 'MAIN',
                                'Telecine' => 'NONE',
                                "FramerateNumerator" => 25,
                                'MinIInterval' => 0,
                                'AdaptiveQuantization' => 'HIGH',
                                'CodecLevel' => 'AUTO',
                                'FieldEncoding' => 'PAFF',
                                'SceneChangeDetect' => 'ENABLED',
                                'QualityTuningLevel' => 'SINGLE_PASS',
                                'FramerateConversionAlgorithm' => 'DUPLICATE_DROP',
                                'UnregisteredSeiTimecode' => 'DISABLED',
                                'GopSizeUnits' => 'SECONDS',
                                'ParControl' => 'SPECIFIED',
                                'ParDenominator' => 1,
                                'ParNumerator' => 1,
                                'NumberBFramesBetweenReferenceFrames' => 2,
                                'RepeatPps' => 'DISABLED',
                                'DynamicSubGop' => 'ADAPTIVE',
                            ),
                        ),
                        'AfdSignaling' => 'NONE',
                        'DropFrameTimecode' => 'ENABLED',
                        'RespondToAfd' => 'NONE',
                        'ColorMetadata' => 'INSERT',
                    ),
                    'AudioDescriptions' => array(
                        array(
                            'AudioTypeControl' => 'FOLLOW_INPUT',
                            'CodecSettings' => array(
                                'Codec' => 'AAC',
                                'AacSettings' => array(
                                    'AudioDescriptionBroadcasterMix' => 'NORMAL',
                                    'Bitrate' => 96000,
                                    'RateControlMode' => 'CBR',
                                    'CodecProfile' => 'LC',
                                    'CodingMode' => 'CODING_MODE_2_0',
                                    'RawFormat' => 'NONE',
                                    'SampleRate' => 48000,
                                    'Specification' => 'MPEG4',
                                ),
                            ),
                            'LanguageCodeControl' => 'FOLLOW_INPUT',
                        ),
                    ),
                    'OutputSettings' => array(
                        'HlsSettings' => array(
                            'AudioGroupId' => 'program_audio',
                            'AudioOnlyContainer' => 'AUTOMATIC',
                            'IFrameOnlyManifest' => 'EXCLUDE',
                        ),
                    ),
                    'NameModifier' => '720b_' . TIME_STAMP,
                ),
            ),
            'OutputGroupSettings' => array(
                'Type' => 'HLS_GROUP_SETTINGS',
                'HlsGroupSettings' => array(
                    'ManifestDurationFormat' => 'INTEGER',
                    'SegmentLength' => 6,
                    'TimedMetadataId3Period' => 10,
                    'CaptionLanguageSetting' => 'OMIT',
                    'Destination' => 's3://' . AMS_BUCKET_NAME . '/' . $directory,
                    'DestinationSettings' => [
                        'S3Settings' => [
                            'AccessControl' => [
//                                'CannedAcl' => $resource_id ? 'PUBLIC_READ' : 'BUCKET_OWNER_FULL_CONTROL',
                                'CannedAcl' => 'PUBLIC_READ',
                            ],
                        ],
                    ],
                    'TimedMetadataId3Frame' => 'PRIV',
                    'CodecSpecification' => 'RFC_4281',
                    'OutputSelection' => 'MANIFESTS_AND_SEGMENTS',
                    'ProgramDateTimePeriod' => 600,
                    "SegmentsPerSubdirectory" => 1000000,
                    'MinSegmentLength' => 0,
                    'MinFinalSegmentLength' => 0,
                    'DirectoryStructure' => 'SUBDIRECTORY_PER_STREAM',
                    'ProgramDateTime' => 'INCLUDE',
                    "AdMarkers" => [
                        "ELEMENTAL_SCTE35"
                    ],
                    'SegmentControl' => 'SEGMENTED_FILES',
                    'ManifestCompression' => 'NONE',
                    'ClientCache' => 'ENABLED',
                    'StreamInfResolution' => 'INCLUDE',
                ),
            ),
        );
        if ($resource_id) {
            $return['OutputGroupSettings']['HlsGroupSettings']['Encryption'] = array(
                'EncryptionMethod' => 'SAMPLE_AES',
                'OfflineEncrypted' => 'DISABLED',
                'SpekeKeyProvider' => array(
                    'ResourceId' => $resource_id,
                    'SystemIds' => array(
                        '94CE86FB-07FF-4F43-ADB8-93D2FA968CA2',
                    ),
                    'Url' => 'https://kms.pallycon.com/cpix/getKey?enc-token=' . $this->pallycon_token,
                ),
                'Type' => 'SPEKE',
            );
        }
        return $return;
    }

    private function generate_resource_id($file_name, $video_id) {
        $resource_id = explode("/", $file_name);
        $resource_id = end($resource_id);
        $resource_id = explode(".", $resource_id);
        array_pop($resource_id);
        $resource_id = implode(".", $resource_id);

        return $video_id . "_" . $resource_id;
    }

    private function get_dash_output($directory, $resource_id) {
        return array(
            'CustomName' => 'dash_output_group',
            'Name' => 'DASH ISO',
            'Outputs' => array(
                array(
                    'ContainerSettings' => array(
                        'Container' => 'MPD',
                    ),
                    'AudioDescriptions' => array(
                        array(
                            'AudioTypeControl' => 'FOLLOW_INPUT',
                            'AudioSourceName' => 'Audio Selector 1',
                            'CodecSettings' => array(
                                'Codec' => 'AAC',
                                'AacSettings' => array(
                                    'AudioDescriptionBroadcasterMix' => 'NORMAL',
                                    'Bitrate' => 96000,
                                    'RateControlMode' => 'CBR',
                                    'CodecProfile' => 'HEV1',
                                    'CodingMode' => 'CODING_MODE_2_0',
                                    'RawFormat' => 'NONE',
                                    'SampleRate' => 48000,
                                    'Specification' => 'MPEG4',
                                ),
                            ),
                            'LanguageCodeControl' => 'FOLLOW_INPUT',
                            'AudioType' => 0,
                        ),
                    ),
                    'NameModifier' => '_AAC',
                ),
                array(
                    'ContainerSettings' => array(
                        'Container' => 'MPD',
                    ),
                    'VideoDescription' => array(
                        'Width' => 360,
                        'ScalingBehavior' => 'DEFAULT',
                        'Height' => 240,
                        'TimecodeInsertion' => 'DISABLED',
                        'AntiAlias' => 'ENABLED',
                        'Sharpness' => 80,
                        'CodecSettings' => array(
                            'Codec' => 'H_264',
                            'H264Settings' => array(
                                'InterlaceMode' => 'PROGRESSIVE',
                                'NumberReferenceFrames' => 2,
                                'Syntax' => 'DEFAULT',
                                'Softness' => 0,
                                'FramerateDenominator' => 1,
                                'GopClosedCadence' => 1,
                                'GopSize' => 2,
                                'Slices' => 1,
                                'GopBReference' => 'DISABLED',
                                "HrdBufferSize" => 600000,
                                'MaxBitrate' => 300000,
                                'SpatialAdaptiveQuantization' => 'ENABLED',
                                'TemporalAdaptiveQuantization' => 'ENABLED',
                                'FlickerAdaptiveQuantization' => 'ENABLED',
                                'EntropyEncoding' => 'CAVLC',
                                'FramerateControl' => 'SPECIFIED',
                                'RateControlMode' => 'QVBR',
                                'QvbrSettings' => array(
                                    'QvbrQualityLevel' => 5,
                                    'QvbrQualityLevelFineTune' => 0,
                                ),
                                'CodecProfile' => 'BASELINE',
                                'Telecine' => 'NONE',
                                "FramerateNumerator" => 25,
                                'MinIInterval' => 0,
                                'AdaptiveQuantization' => 'HIGH',
                                'CodecLevel' => 'AUTO',
                                'FieldEncoding' => 'PAFF',
                                'SceneChangeDetect' => 'ENABLED',
                                'QualityTuningLevel' => 'SINGLE_PASS',
                                'FramerateConversionAlgorithm' => 'DUPLICATE_DROP',
                                'UnregisteredSeiTimecode' => 'DISABLED',
                                'GopSizeUnits' => 'SECONDS',
                                'ParControl' => 'SPECIFIED',
                                'ParDenominator' => 1,
                                'ParNumerator' => 1,
                                'NumberBFramesBetweenReferenceFrames' => 0,
                                'RepeatPps' => 'DISABLED',
                                'DynamicSubGop' => 'ADAPTIVE',
                            ),
                        ),
                        'AfdSignaling' => 'NONE',
                        'DropFrameTimecode' => 'ENABLED',
                        'RespondToAfd' => 'NONE',
                        'ColorMetadata' => 'INSERT',
                    ),
                    'NameModifier' => '_240',
                ),
                array(
                    'ContainerSettings' => array(
                        'Container' => 'MPD',
                    ),
                    'VideoDescription' => array(
                        'Width' => 640,
                        'ScalingBehavior' => 'DEFAULT',
                        'Height' => 360,
                        'TimecodeInsertion' => 'DISABLED',
                        'AntiAlias' => 'ENABLED',
                        'Sharpness' => 80,
                        'CodecSettings' => array(
                            'Codec' => 'H_264',
                            'H264Settings' => array(
                                'InterlaceMode' => 'PROGRESSIVE',
                                'NumberReferenceFrames' => 2,
                                'Syntax' => 'DEFAULT',
                                'Softness' => 0,
                                'FramerateDenominator' => 1,
                                'GopClosedCadence' => 1,
                                'GopSize' => 2,
                                'Slices' => 1,
                                'GopBReference' => 'ENABLED',
                                "HrdBufferSize" => 1400000,
                                'MaxBitrate' => 700000,
                                'SlowPal' => 'DISABLED',
                                'SpatialAdaptiveQuantization' => 'ENABLED',
                                'TemporalAdaptiveQuantization' => 'ENABLED',
                                'FlickerAdaptiveQuantization' => 'ENABLED',
                                'EntropyEncoding' => 'CABAC',
                                'FramerateControl' => 'SPECIFIED',
                                'RateControlMode' => 'QVBR',
                                'QvbrSettings' => array(
                                    'QvbrQualityLevel' => 6,
                                    'QvbrQualityLevelFineTune' => 0,
                                ),
                                'CodecProfile' => 'MAIN',
                                'Telecine' => 'NONE',
                                "FramerateNumerator" => 25,
                                'MinIInterval' => 0,
                                'AdaptiveQuantization' => 'HIGH',
                                'CodecLevel' => 'AUTO',
                                'FieldEncoding' => 'PAFF',
                                'SceneChangeDetect' => 'ENABLED',
                                'QualityTuningLevel' => 'SINGLE_PASS',
                                'FramerateConversionAlgorithm' => 'DUPLICATE_DROP',
                                'UnregisteredSeiTimecode' => 'DISABLED',
                                'GopSizeUnits' => 'SECONDS',
                                'ParControl' => 'SPECIFIED',
                                'ParDenominator' => 1,
                                'ParNumerator' => 1,
                                'NumberBFramesBetweenReferenceFrames' => 2,
                                'RepeatPps' => 'DISABLED',
                                'DynamicSubGop' => 'ADAPTIVE',
                            ),
                        ),
                        'AfdSignaling' => 'NONE',
                        'DropFrameTimecode' => 'ENABLED',
                        'RespondToAfd' => 'NONE',
                        'ColorMetadata' => 'INSERT',
                    ),
                    'NameModifier' => '_360',
                ),
                array(
                    'ContainerSettings' => array(
                        'Container' => 'MPD',
                    ),
                    'VideoDescription' => array(
                        'Width' => 854,
                        'ScalingBehavior' => 'DEFAULT',
                        'Height' => 480,
                        'TimecodeInsertion' => 'DISABLED',
                        'AntiAlias' => 'ENABLED',
                        'Sharpness' => 80,
                        'CodecSettings' => array(
                            'Codec' => 'H_264',
                            'H264Settings' => array(
                                'InterlaceMode' => 'PROGRESSIVE',
                                'NumberReferenceFrames' => 2,
                                'Syntax' => 'DEFAULT',
                                'Softness' => 0,
                                'FramerateDenominator' => 1,
                                'GopClosedCadence' => 1,
                                'GopSize' => 2,
                                'Slices' => 1,
                                'GopBReference' => 'ENABLED',
                                "HrdBufferSize" => 2200000,
                                'MaxBitrate' => 1100000,
                                'SlowPal' => 'DISABLED',
                                'SpatialAdaptiveQuantization' => 'ENABLED',
                                'TemporalAdaptiveQuantization' => 'ENABLED',
                                'FlickerAdaptiveQuantization' => 'ENABLED',
                                'EntropyEncoding' => 'CABAC',
                                'FramerateControl' => 'SPECIFIED',
                                'RateControlMode' => 'QVBR',
                                'QvbrSettings' => array(
                                    'QvbrQualityLevel' => 7,
                                    'QvbrQualityLevelFineTune' => 0,
                                ),
                                'CodecProfile' => 'MAIN',
                                'Telecine' => 'NONE',
                                "FramerateNumerator" => 25,
                                'MinIInterval' => 0,
                                'AdaptiveQuantization' => 'HIGH',
                                'CodecLevel' => 'AUTO',
                                'FieldEncoding' => 'PAFF',
                                'SceneChangeDetect' => 'ENABLED',
                                'QualityTuningLevel' => 'SINGLE_PASS',
                                'FramerateConversionAlgorithm' => 'DUPLICATE_DROP',
                                'UnregisteredSeiTimecode' => 'DISABLED',
                                'GopSizeUnits' => 'SECONDS',
                                'ParControl' => 'SPECIFIED',
                                'ParDenominator' => 1,
                                'ParNumerator' => 1,
                                'NumberBFramesBetweenReferenceFrames' => 2,
                                'RepeatPps' => 'DISABLED',
                                'DynamicSubGop' => 'ADAPTIVE',
                            ),
                        ),
                        'AfdSignaling' => 'NONE',
                        'DropFrameTimecode' => 'ENABLED',
                        'RespondToAfd' => 'NONE',
                        'ColorMetadata' => 'INSERT',
                    ),
                    'NameModifier' => '_480',
                ),
                array(
                    'ContainerSettings' => array(
                        'Container' => 'MPD',
                    ),
                    'VideoDescription' => array(
                        'Width' => 1280,
                        'ScalingBehavior' => 'DEFAULT',
                        'Height' => 720,
                        'TimecodeInsertion' => 'DISABLED',
                        'AntiAlias' => 'ENABLED',
                        'Sharpness' => 80,
                        'CodecSettings' => array(
                            'Codec' => 'H_264',
                            'H264Settings' => array(
                                'InterlaceMode' => 'PROGRESSIVE',
                                'NumberReferenceFrames' => 2,
                                'Syntax' => 'DEFAULT',
                                'Softness' => 0,
                                'FramerateDenominator' => 1,
                                'GopClosedCadence' => 1,
                                'GopSize' => 2,
                                'Slices' => 1,
                                'GopBReference' => 'ENABLED',
                                "HrdBufferSize" => 3400000,
                                'MaxBitrate' => 1700000,
                                'SlowPal' => 'DISABLED',
                                'SpatialAdaptiveQuantization' => 'ENABLED',
                                'TemporalAdaptiveQuantization' => 'ENABLED',
                                'FlickerAdaptiveQuantization' => 'ENABLED',
                                'EntropyEncoding' => 'CABAC',
                                'FramerateControl' => 'SPECIFIED',
                                'RateControlMode' => 'QVBR',
                                'QvbrSettings' => array(
                                    'QvbrQualityLevel' => 7,
                                    'QvbrQualityLevelFineTune' => 0,
                                ),
                                'CodecProfile' => 'MAIN',
                                'Telecine' => 'NONE',
                                "FramerateNumerator" => 25,
                                'MinIInterval' => 0,
                                'AdaptiveQuantization' => 'HIGH',
                                'CodecLevel' => 'AUTO',
                                'FieldEncoding' => 'PAFF',
                                'SceneChangeDetect' => 'ENABLED',
                                'QualityTuningLevel' => 'SINGLE_PASS',
                                'FramerateConversionAlgorithm' => 'DUPLICATE_DROP',
                                'UnregisteredSeiTimecode' => 'DISABLED',
                                'GopSizeUnits' => 'SECONDS',
                                'ParControl' => 'SPECIFIED',
                                'ParDenominator' => 1,
                                'ParNumerator' => 1,
                                'NumberBFramesBetweenReferenceFrames' => 2,
                                'RepeatPps' => 'DISABLED',
                                'DynamicSubGop' => 'ADAPTIVE',
                            ),
                        ),
                        'AfdSignaling' => 'NONE',
                        'DropFrameTimecode' => 'ENABLED',
                        'RespondToAfd' => 'NONE',
                        'ColorMetadata' => 'INSERT',
                    ),
                    'NameModifier' => '_720',
                ),
            ),
            'OutputGroupSettings' => array(
                'Type' => 'DASH_ISO_GROUP_SETTINGS',
                'DashIsoGroupSettings' => array(
                    'SegmentLength' => 6,
                    'Destination' => 's3://' . AMS_BUCKET_NAME . '/' . $directory,
                    'DestinationSettings' => [
                        'S3Settings' => [
                            'AccessControl' => [
                                'CannedAcl' => 'PUBLIC_READ',
                            ],
                        ],
                    ],
                    'Encryption' => array(
                        'PlaybackDeviceCompatibility' => 'CENC_V1',
                        'SpekeKeyProvider' => array(
                            'ResourceId' => $resource_id,
                            'SystemIds' => array(
                                '9A04F079-9840-4286-AB92-E65BE0885F95',
                                'EDEF8BA9-79D6-4ACE-A3C8-27DCD51D21ED',
                            ),
                            'Url' => 'https://kms.pallycon.com/cpix/getKey?enc-token=' . $this->pallycon_token,
                        ),
                    ),
                    'FragmentLength' => 2,
                    'SegmentControl' => 'SINGLE_FILE',
                    'MpdProfile' => 'MAIN_PROFILE',
                    'HbbtvCompliance' => 'NONE',
                ),
            ),
        );
    }

    public function sns_media_convert_reciever($input) {
        $request_json = json_decode($input, true);
        if ($request_json) {
            $detail = json_decode($request_json['Message'], true);
            $job_id = $detail['detail']['jobId'];
            $job_percent = $detail['detail']['jobProgress']['jobPercentComplete'];
            $job_detail = $this->track_job($job_id);
            $inputs = explode("/", $job_detail['Settings']['Inputs'][0]['FileInput']);
            $input_url['bucket_name'] = $inputs[2];
            unset($inputs[0], $inputs[1], $inputs[2]);
            $input_url['key'] = implode("/", $inputs);
            $fileSize = get_s3_size($input_url);
            $video_id = $this->db->select("id")
                            ->from("course_topic_file_meta_master")
                            ->like("mediaconvert_tracking", $job_id)
                            ->get()->row()->id;

            $is_exist = $this->db->get_where('aws_media_converter_jobs', array('job_id' => $job_id))->row_array();

// For Destination
            $name = array();
            foreach ($job_detail['Settings']['OutputGroups'] as $outputGroups) {
                $temp_data = array();
                $temp_data['type'] = $outputGroups['Name'];
                if (array_key_exists('HlsGroupSettings', $outputGroups['OutputGroupSettings'])) {
                    $temp_data['DestinationUrl'] = $outputGroups['OutputGroupSettings']['HlsGroupSettings']['Destination'];
                }
                if (array_key_exists('DashIsoGroupSettings', $outputGroups['OutputGroupSettings'])) {
                    $temp_data['DestinationUrl'] = $outputGroups['OutputGroupSettings']['DashIsoGroupSettings']['Destination'];
                }
                $name[] = $temp_data;
            }
// For Destination

            $insert_data = array(
                "video_id" => ($video_id) ? $video_id : 0, //$video_id,
                "job_id" => $job_id,
                "input" => $job_detail['Settings']['Inputs'][0]['FileInput'],
// "destination" => $job_detail['Settings']['OutputGroups'][0]['OutputGroupSettings']['HlsGroupSettings']['Destination'],
                "destination" => json_encode($name),
                "start_time" => strtotime($job_detail['Timing']['StartTime']),
                "end_time" => strtotime($job_detail['Timing']['FinishTime']),
                "submit_time" => strtotime($job_detail['Timing']['SubmitTime']),
                "queue" => $job_detail['Queue'],
                "size" => $fileSize,
                "status" => $job_detail['Status'],
                "job_percentage" => $detail['detail']['jobProgress']['jobPercentComplete']
            );
// pre($insert_data); die;
            if ($is_exist) {//update
                $this->db->where('id', $is_exist['id']);
                $this->db->update('aws_media_converter_jobs', $insert_data);
            } else {//insert
                $this->db->insert('aws_media_converter_jobs', $insert_data);
            }
        }
    }

}
