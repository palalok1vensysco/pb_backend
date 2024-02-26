<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//require_once APPPATH . 'third_party/AWS_MediaLive/autoload.php';
require_once(FCPATH . 'aws/aws-autoloader.php');

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\MediaLive\MediaLiveClient;
use Aws\CloudFront\CloudFrontClient;
use Aws\Exception\AwsException;

class All_code extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        $this->load->helper('aul');
//        modules::run('auth_panel/auth_panel_ini/auth_ini');
    }

    function aws_create_video_link() {
        // Create a CloudFront Client
//        $client = new Aws\CloudFront\CloudFrontClient([
//            'profile' => 'default',
//            'version' => '2014-11-06',
//            'region' => 'us-east-2'
//        ]);
        $cloudfront = CloudFrontClient::factory([
                    'version' => 'latest',
                    'region' => 'us-east-2',
                    'private_key' => FCPATH . 'exampur_cloudfront.pem',
                    'key_pair_id' => 'APKAI337RWCCJDQXGHKQ'
        ]);
        $object = 'livea/main.m3u8';
        $expiry = new DateTime('+100 minute');

        $url = $cloudfront->getSignedUrl([
            'url' => "https://d93no27bcsirz.cloudfront.net/{$object}",
            'expires' => $expiry->getTimestamp(),
            'private_key' => 'exampur_cloudfront.pem',
            'key_pair_id' => 'APKAI337RWCCJDQXGHKQ'
        ]);

        echo $url;
//        die;
        ?>

        <html>
            <head>
                <title>|CFlong| Streaming Example</title>
                <script type="text/javascript" src="https://jwpsrv.com/library/4+R8PsscEeO69iIACooLPQ.js"></script>
            </head>
            <body>
                <script type="text/javascript" src="//player.wowza.com/player/latest/wowzaplayer.min.js"></script>
                <div id="playerElement" style="width:50%; height:0; padding:0 0 56.25% 0"></div>
                <script type="text/javascript">
                    WowzaPlayer.create('playerElement',
                            {
                                "license": "PLAY2-9zpvQ-CanaV-rFfBx-upy74-jjP4k",
                                "title": "sda",
                                "description": "adasd",
                                "sourceURL": "<?= $url ?>",
                                "autoPlay": false,
                                "volume": "75",
                                "mute": false,
                                "loop": false,
                                "audioOnly": false,
                                "uiShowQuickRewind": true,
                                "uiQuickRewindSeconds": "30"
                            }
                    );
                </script>
                <!--                <div id="video">The canned policy video will be here.</div>
                                <video id="myElement"></video>
                                <script src="https://cdn.jwplayer.com/libraries/hVTBp9wx.js"  ></script>
                
                                                                 <script>
                                                jwplayer("myElement").setup({
                                                        autostart: false,
                                                        image: "https://needy-app.s3.amazonaws.com/relation_ship/467974354059086300.jpg",
                                                        //"file": "https://tvekbj4siqkim7.data.mediastore.us-east-1.amazonaws.com/livea/main_720p30.m3u8"
                                                        file:"https://tvekbj4siqkim7.data.mediastore.us-east-1.amazonaws.com/livea/main_240p30.m3u8",
                                                        playbackRateControls: [0.75, 1, 1.25, 1.5],
                                                        tracks:[]});
                                        </script>-->
                <!--                <script type="text/javascript">
                
                        // Start User Controlled Example
                                    jwplayer('myElement').setup({
                                        playlist: <?= $this->get_json() ?>,
                        // Set the available playback rates of your choice
                                        playbackRateControls: [0.75, 1, 1.25, 1.5],
                                        autostart: false,
                                        mute: false
                                    });
                        // End User Controlled Example
                
                        // Start Publisher Curated Example
                                    var seekComplete, automationComplete;
                                    var SLOW_PLAYBACK_RATE = 0.5;
                                    var PLAYBACK_TIMES = {
                                        startEvent: 16,
                                        endEvent: 20
                                    }; // Time in seconds of start and end of an interesting point in video
                
                                    function initPublisherPlayer(config) {
                                        var player = jwplayer('publisher-player').setup(config);
                
                        // Set custom, out-of-player controls
                                        setupPlayerControls(player);
                
                        // Listen if the video is at the time when we want to change playback rate
                                        player.on('time', automatePlayback.bind(this, player));
                                    }
                
                                    function setupPlayerControls(player) {
                                        var playBtn = document.querySelector('.play-btn');
                                        var pauseBtn = document.querySelector('.pause-btn');
                                        var unmuteBtn = document.querySelector('.unmute-btn');
                                        var muteBtn = document.querySelector('.mute-btn');
                
                                        playBtn.addEventListener('click', function () {
                                            player.play(true);
                                            toggleControls(playBtn, pauseBtn);
                                        });
                
                                        pauseBtn.addEventListener('click', function () {
                                            player.pause(true);
                                            toggleControls(pauseBtn, playBtn);
                                        });
                
                                        unmuteBtn.addEventListener('click', function () {
                                            player.setMute(false);
                                            toggleControls(unmuteBtn, muteBtn);
                                        });
                
                                        muteBtn.addEventListener('click', function () {
                                            player.setMute(true);
                                            toggleControls(muteBtn, unmuteBtn);
                                        });
                
                                        player.on('complete', function () {
                                            toggleControls(pauseBtn, playBtn);
                
                        // Reset flags in case video is replayed
                                            seekComplete = automationComplete = undefined;
                                        });
                                    }
                
                                    function toggleControls(currentBtn, otherBtn) {
                                        currentBtn.style.display = 'none';
                                        otherBtn.style.display = 'block';
                                    }
                
                                    function automatePlayback(player) {
                                        var position = Math.floor(player.getPosition());
                                        var timeBox = document.querySelector('.publisher-player-time');
                        // Demo video is less than one minute long; you may want to build/install a time formatter.
                                        timeBox.innerHTML = '00:' + (position.toString().length > 1 ? '' : '0') + position;
                
                        // If automation is complete, do nothing
                                        if (automationComplete) {
                                            return;
                                        }
                
                        // If seek action hasn't yet occurred, attempt it
                                        if (!seekComplete) {
                                            seekVideo(player, position);
                                            return;
                                        }
                
                        // If seek action has occured and playback rate hasn't been set back to normal,
                        // attempt to reset it
                                        resetPlaybackRate(player, position);
                                    }
                
                                    function seekVideo(player, currentTime) {
                                        if (currentTime >= PLAYBACK_TIMES.endEvent) {
                                            seekComplete = true;
                
                        // Rewind video to start of the interesting action
                                            player.seek(PLAYBACK_TIMES.startEvent);
                
                        // Slow playback rate on replay of the interesting action
                                            player.setPlaybackRate(SLOW_PLAYBACK_RATE);
                
                                            document.querySelector('.publisher-player-replay-copy').style.display = 'block'; // For demo purposes only
                                        }
                                    }
                
                                    function resetPlaybackRate(player, currentTime) {
                                        if (currentTime >= PLAYBACK_TIMES.endEvent) {
                        // We have reached end of the interesting action and need to reset the playback to normal
                                            player.setPlaybackRate(1);
                                            automationComplete = true;
                
                                            document.querySelector('.publisher-player-replay-copy').style.display = 'none'; // For demo purposes only
                                        }
                                    }
                
                                    initPublisherPlayer({
                                        playlist: <?= $this->get_json() ?>,
                                        controls: false,
                                        autostart: false,
                                        mute: true
                                    });
                        // End Publisher Curated Example
                
                                </script>-->
                                <!--                        <script type="text/javascript">
                                                            jwplayer('video').setup({
                                                                file: "https://tvekbj4siqkim7.data.mediastore.us-east-1.amazonaws.com/livea/main_720p30.m3u8",
                                                            });
                                                        </script>-->
            </body>
        </html>
        <?php
////        echo $signedUrlCannedPolicy;
        die;

        $config = $this->aws_config();
//        $s3 = S3Client::factory([
//                    'version' => 'latest',
//                    'region' => 'ap-south-1',
//                    'key' => $config['s3']['key'],
//                    'secret' => $config['s3']['secret']
//        ]);
        $cloudfront = CloudFrontClient::factory([
                    'version' => 'latest',
                    'region' => 'us-east-1',
                    'private_key' => FCPATH . 'exampur_cloudfront.pem',
                    'key_pair_id' => 'APKAI337RWCCJDQXGHKQ'
        ]);
        $object = "SampleVideo_360x240_5mb.mp4";
        $object = "rtmp://s3onv9jff9mq9u.cloudfront.net/SampleVideo_360x240_5mb.mp4";
        $object = "https://tvekbj4siqkim7.data.mediastore.us-east-1.amazonaws.com/livea/main_240p30.m3u8";
        $expiry = new DateTime('+100 minute');

        $url = $cloudfront->getStreamingDistribution([
            'Id' => "ENDK8R5U1WQW8",
            'url' => "{$config['cloudfront']['url']}/{$object}",
            'expires' => $expiry->getTimestamp(),
            'private_key' => 'exampur_cloudfront.pem',
            'key_pair_id' => 'APKAINLPC4YDYYIC6ZOA'
        ]);

        echo $url;
    }

    //exampur
    public function aws_config() {
        return [
            's3' => [
                'key' => AMS_S3_KEY,
                'secret' => AMS_SECRET,
                'bucket' => AMS_BUCKET_NAME
            ],
            'cloudfront' => [
                'url' => 'https://s3onv9jff9mq9u.cloudfront.net'
            ]
        ];
    }

    public function index() {
        $this->stop_channel();
//        $this->start_channel();
//        $this->create_channel();
//        $this->delete_channel();
//        $this->attach_input();
//        $this->create_input();
//        $this->delete_input();
//        $this->get_origin_list();
//        $this->get_channels_list();
    }

    function delete_input() {
        $client = new Aws\MediaLive\MediaLiveClient($this->get_credentials());
        $result = $client->deleteInput([
            'InputId' => '<string>', // REQUIRED
        ]);
    }

    function stop_channel() {
        $client = new Aws\MediaLive\MediaLiveClient($this->get_credentials());
        $result = $client->stopChannel([
            'ChannelId' => '8429565', // REQUIRED
        ]);
        pre($result);
    }

    function start_channel() {
        $client = new Aws\MediaLive\MediaLiveClient($this->get_credentials());
        $result = $client->startChannel([
            'ChannelId' => '8429565', // REQUIRED
        ]);
        pre($result);
    }

    function create_channel() {
        $client = new Aws\MediaLive\MediaLiveClient($this->get_credentials());

        $result = $client->createChannel([
            'RoleArn' => 'arn:aws:iam::213369717207:role/MediaLiveAccessRole',
            'ChannelClass' => 'STANDARD',
            'Destinations' => [
                [
                    "Id" => "destination2",
                    "Settings" => [
                        [
                            "Url" => "mediastoressl://tvekbj4siqkim7.data.mediastore.us-east-1.amazonaws.com/livea/main"
                        ], [
                            "Url" => "mediastoressl://tvekbj4siqkim7.data.mediastore.us-east-1.amazonaws.com/liveb/main"
                        ]
                    ]
                ]
            ],
            'EncoderSettings' => [
                'AudioDescriptions' => [
                    [
                        'AudioSelectorName' => 'Default',
                        'AudioType' => 'CLEAN_EFFECTS', //|HEARING_IMPAIRED|UNDEFINED|VISUAL_IMPAIRED_COMMENTARY',
                        'AudioTypeControl' => 'FOLLOW_INPUT',
                        'CodecSettings' => [
                            'AacSettings' => [
                                'Bitrate' => 192000,
                                'CodingMode' => 'CODING_MODE_2_0',
                                'InputType' => 'NORMAL',
                                'Profile' => 'LC',
                                'RateControlMode' => 'CBR',
                                'RawFormat' => 'NONE',
                                'SampleRate' => 48000,
                                'Spec' => 'MPEG4',
                            ],
                        ],
                        'LanguageCodeControl' => 'FOLLOW_INPUT',
                        'Name' => 'audio_1',
                    ],
                    [
                        'AudioSelectorName' => 'Default',
                        'AudioTypeControl' => 'FOLLOW_INPUT',
                        'CodecSettings' => [
                            'AacSettings' => [
                                'Bitrate' => 192000,
                                'CodingMode' => 'CODING_MODE_2_0',
                                'InputType' => 'NORMAL',
                                'Profile' => 'LC',
                                'RateControlMode' => 'CBR',
                                'RawFormat' => 'NONE',
                                'SampleRate' => 48000,
                                'Spec' => 'MPEG4',
                            ],
                        ],
                        'LanguageCodeControl' => 'FOLLOW_INPUT',
                        'Name' => 'audio_2',
                    ], [
                        'AudioSelectorName' => 'Default',
                        'AudioTypeControl' => 'FOLLOW_INPUT',
                        'CodecSettings' => [
                            'AacSettings' => [
                                'Bitrate' => 128000,
                                'CodingMode' => 'CODING_MODE_2_0',
                                'InputType' => 'NORMAL',
                                'Profile' => 'LC',
                                'RateControlMode' => 'CBR',
                                'RawFormat' => 'NONE',
                                'SampleRate' => 48000,
                                'Spec' => 'MPEG4',
                            ],
                        ],
                        'LanguageCodeControl' => 'FOLLOW_INPUT',
                        'Name' => 'audio_3',
                    ], [
                        'AudioSelectorName' => 'Default',
                        'AudioTypeControl' => 'FOLLOW_INPUT',
                        'CodecSettings' => [
                            'AacSettings' => [
                                'Bitrate' => 128000,
                                'CodingMode' => 'CODING_MODE_2_0',
                                'InputType' => 'NORMAL',
                                'Profile' => 'LC',
                                'RateControlMode' => 'CBR',
                                'RawFormat' => 'NONE',
                                'SampleRate' => 48000,
                                'Spec' => 'MPEG4',
                            ],
                        ],
                        'LanguageCodeControl' => 'FOLLOW_INPUT',
                        'Name' => 'audio_4',
                    ]
                ],
                'AvailBlanking' => [
                    'State' => 'ENABLED',
                ],
                'CaptionDescriptions' => [],
                'OutputGroups' => [
                    [
                        'Name' => 'HD',
                        'OutputGroupSettings' => [
                            'HlsGroupSettings' => [
                                'CaptionLanguageSetting' => 'OMIT',
                                'ClientCache' => 'ENABLED',
                                'CodecSpecification' => 'RFC_4281',
                                'Destination' => [
                                    'DestinationRefId' => 'destination2',
                                ],
                                'DirectoryStructure' => 'SINGLE_DIRECTORY', //|SUBDIRECTORY_PER_STREAM',
                                'HlsCdnSettings' => [
                                    'HlsWebdavSettings' => [
                                        'ConnectionRetryInterval' => 1,
                                        'FilecacheDuration' => 300,
                                        'HttpTransferMode' => 'NON_CHUNKED',
                                        'NumRetries' => 10,
                                        'RestartDelay' => 15,
                                    ],
                                ],
                                'IFrameOnlyPlaylists' => 'DISABLED',
                                'IndexNSegments' => 10,
                                'InputLossAction' => 'EMIT_OUTPUT',
                                'IvInManifest' => 'INCLUDE',
                                'IvSource' => 'FOLLOWS_SEGMENT_NUMBER',
                                'KeepSegments' => 21,
                                'ManifestCompression' => 'NONE',
                                'ManifestDurationFormat' => 'INTEGER',
                                'Mode' => 'LIVE',
                                'OutputSelection' => 'MANIFESTS_AND_SEGMENTS',
                                'ProgramDateTime' => 'EXCLUDE',
                                'ProgramDateTimePeriod' => 600,
                                'RedundantManifest' => 'DISABLED',
                                'SegmentLength' => 6,
                                'SegmentationMode' => 'USE_SEGMENT_DURATION',
                                'SegmentsPerSubdirectory' => 10000,
                                'StreamInfResolution' => 'INCLUDE',
                                'TimedMetadataId3Frame' => 'PRIV',
                                'TimedMetadataId3Period' => 10,
                            ],
                        ],
                        'Outputs' => [
                            [
                                'AudioDescriptionNames' => ['audio_1'],
                                'OutputSettings' => [
                                    'HlsOutputSettings' => [
                                        'NameModifier' => '_720p60',
                                        'HlsSettings' => [
                                            'StandardHlsSettings' => [
                                                'AudioRenditionSets' => 'program_audio',
                                                'M3u8Settings' => [
                                                    'AudioFramesPerPes' => 4,
                                                    'AudioPids' => '492-498',
                                                    'EcmPid' => '480',
                                                    'PcrControl' => 'PCR_EVERY_PES_PACKET',
                                                    'PmtPid' => '480',
                                                    'ProgramNum' => 1,
                                                    'Scte35Behavior' => 'NO_PASSTHROUGH',
                                                    'Scte35Pid' => '500',
                                                    'TimedMetadataBehavior' => 'NO_PASSTHROUGH',
                                                    'TimedMetadataPid' => '502',
                                                    'VideoPid' => '481',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'VideoDescriptionName' => 'video_720p60',
                            ],
                            [
                                'AudioDescriptionNames' => ['audio_2'],
                                'OutputSettings' => [
                                    'HlsOutputSettings' => [
                                        'NameModifier' => '_720p30',
                                        'HlsSettings' => [
                                            'StandardHlsSettings' => [
                                                'AudioRenditionSets' => 'program_audio',
                                                'M3u8Settings' => [
                                                    'AudioFramesPerPes' => 4,
                                                    'AudioPids' => '492-498',
                                                    'EcmPid' => '480',
                                                    'PcrControl' => 'PCR_EVERY_PES_PACKET',
                                                    'PmtPid' => '480',
                                                    'ProgramNum' => 1,
                                                    'Scte35Behavior' => 'NO_PASSTHROUGH',
                                                    'Scte35Pid' => '500',
                                                    'TimedMetadataBehavior' => 'NO_PASSTHROUGH',
                                                    'TimedMetadataPid' => '502',
                                                    'VideoPid' => '481',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'VideoDescriptionName' => 'video_720p30',
                            ],
                            [
                                'AudioDescriptionNames' => ['audio_3'],
                                'OutputSettings' => [
                                    'HlsOutputSettings' => [
                                        'NameModifier' => '_480p30',
                                        'HlsSettings' => [
                                            'StandardHlsSettings' => [
                                                'AudioRenditionSets' => 'program_audio',
                                                'M3u8Settings' => [
                                                    'AudioFramesPerPes' => 4,
                                                    'AudioPids' => '492-498',
                                                    'EcmPid' => '8182',
                                                    'PcrControl' => 'PCR_EVERY_PES_PACKET',
                                                    'PmtPid' => '480',
                                                    'ProgramNum' => 1,
                                                    'Scte35Behavior' => 'NO_PASSTHROUGH',
                                                    'Scte35Pid' => '500',
                                                    'TimedMetadataBehavior' => 'NO_PASSTHROUGH',
                                                    'TimedMetadataPid' => '502',
                                                    'VideoPid' => '481',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'VideoDescriptionName' => 'video_480p30',
                            ],
                            [
                                'AudioDescriptionNames' => ['audio_4'],
                                'OutputSettings' => [
                                    'HlsOutputSettings' => [
                                        'NameModifier' => '_240p30',
                                        'HlsSettings' => [
                                            'StandardHlsSettings' => [
                                                'AudioRenditionSets' => 'program_audio',
                                                'M3u8Settings' => [
                                                    'AudioFramesPerPes' => 4,
                                                    'AudioPids' => '492-498',
                                                    'EcmPid' => '8182',
                                                    'PcrControl' => 'PCR_EVERY_PES_PACKET',
                                                    'PmtPid' => '480',
                                                    'ProgramNum' => 1,
                                                    'Scte35Behavior' => 'NO_PASSTHROUGH',
                                                    'Scte35Pid' => '500',
                                                    'TimedMetadataBehavior' => 'NO_PASSTHROUGH',
                                                    'TimedMetadataPid' => '502',
                                                    'VideoPid' => '481',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'VideoDescriptionName' => 'video_240p30',
                            ]
                        ],
                    ],
                ],
                "TimecodeConfig" => [
                    "Source" => "EMBEDDED"
                ],
                'VideoDescriptions' => [
                    [
                        'CodecSettings' => [
                            'H264Settings' => [
                                'AfdSignaling' => 'NONE',
                                'ColorMetadata' => 'INSERT',
                                'AdaptiveQuantization' => 'HIGH',
                                'Bitrate' => 5000000,
                                'EntropyEncoding' => 'CABAC',
                                'FlickerAq' => 'ENABLED',
                                'FramerateControl' => 'SPECIFIED',
                                'FramerateNumerator' => 60,
                                'FramerateDenominator' => 1,
                                'GopBReference' => 'DISABLED',
                                'GopClosedCadence' => 1,
                                'GopNumBFrames' => 1,
                                'GopSize' => 120,
                                'GopSizeUnits' => 'FRAMES',
                                'ScanType' => 'PROGRESSIVE',
                                'Level' => 'H264_LEVEL_AUTO',
                                'LookAheadRateControl' => 'HIGH',
                                'NumRefFrames' => 3,
                                'ParControl' => 'INITIALIZE_FROM_SOURCE',
                                'Profile' => 'MAIN',
                                'RateControlMode' => 'CBR',
                                'Syntax' => 'DEFAULT',
                                'SceneChangeDetect' => 'ENABLED',
                                'Slices' => 1,
                                'SpatialAq' => 'ENABLED',
                                'TemporalAq' => 'ENABLED',
                                'TimecodeInsertion' => 'DISABLED',
                            ],
                        ],
                        'Height' => 720,
                        'Name' => 'video_720p60',
                        'RespondToAfd' => 'NONE',
                        'ScalingBehavior' => 'DEFAULT',
                        'Sharpness' => 100,
                        'Width' => 1280,
                    ],
                    [
                        'CodecSettings' => [
                            'H264Settings' => [
                                'AfdSignaling' => 'NONE',
                                'ColorMetadata' => 'INSERT',
                                'AdaptiveQuantization' => 'HIGH',
                                'Bitrate' => 3000000,
                                'EntropyEncoding' => 'CABAC',
                                'FlickerAq' => 'ENABLED',
                                'FramerateControl' => 'SPECIFIED',
                                'FramerateNumerator' => 30,
                                'FramerateDenominator' => 1,
                                'GopBReference' => 'DISABLED',
                                'GopClosedCadence' => 1,
                                'GopNumBFrames' => 1,
                                'GopSize' => 60,
                                'GopSizeUnits' => 'FRAMES',
                                'ScanType' => 'PROGRESSIVE',
                                'Level' => 'H264_LEVEL_AUTO',
                                'LookAheadRateControl' => 'HIGH',
                                'NumRefFrames' => 3,
                                'ParControl' => 'INITIALIZE_FROM_SOURCE',
                                'Profile' => 'HIGH',
                                'RateControlMode' => 'CBR',
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
                    ],
                    [
                        'CodecSettings' => [
                            'H264Settings' => [
                                'AfdSignaling' => 'NONE',
                                'ColorMetadata' => 'INSERT',
                                'AdaptiveQuantization' => 'HIGH',
                                'Bitrate' => 1500000,
                                'EntropyEncoding' => 'CABAC',
                                'FlickerAq' => 'ENABLED',
                                'FramerateControl' => 'SPECIFIED',
                                'FramerateNumerator' => 30,
                                'FramerateDenominator' => 1,
                                'GopBReference' => 'DISABLED',
                                'GopClosedCadence' => 1,
                                'GopNumBFrames' => 1,
                                'GopSize' => 60,
                                'GopSizeUnits' => 'FRAMES',
                                'ScanType' => 'PROGRESSIVE',
                                'Level' => 'H264_LEVEL_AUTO',
                                'LookAheadRateControl' => 'HIGH',
                                'NumRefFrames' => 3,
                                'ParControl' => 'SPECIFIED',
                                'Profile' => 'MAIN',
                                'RateControlMode' => 'CBR',
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
                        'ScalingBehavior' => 'STRETCH_TO_OUTPUT',
                        'Sharpness' => 100,
                        'Width' => 640,
                    ],
                    [
                        'CodecSettings' => [
                            'H264Settings' => [
                                'AfdSignaling' => 'NONE',
                                'ColorMetadata' => 'INSERT',
                                'AdaptiveQuantization' => 'HIGH',
                                'Bitrate' => 750000,
                                'EntropyEncoding' => 'CABAC',
                                'FlickerAq' => 'ENABLED',
                                'FramerateControl' => 'SPECIFIED',
                                'FramerateNumerator' => 30,
                                'FramerateDenominator' => 1,
                                'GopBReference' => 'ENABLED',
                                'GopClosedCadence' => 1,
                                'GopNumBFrames' => 3,
                                'GopSize' => 60,
                                'GopSizeUnits' => 'FRAMES',
                                'ScanType' => 'PROGRESSIVE',
                                'Level' => 'H264_LEVEL_AUTO',
                                'LookAheadRateControl' => 'HIGH',
                                'NumRefFrames' => 3,
                                'ParControl' => 'SPECIFIED',
                                'Profile' => 'MAIN',
                                'RateControlMode' => 'CBR',
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
                        'ScalingBehavior' => 'STRETCH_TO_OUTPUT',
                        'Sharpness' => 100,
                        'Width' => 320,
                    ]
                ],
            ],
            "InputAttachments" => [[
            'InputAttachmentName' => 'Testing',
            "InputId" => "6487730"
                ]],
            'InputSpecification' => [
                'Codec' => 'MPEG2',
                'MaximumBitrate' => 'MAX_10_MBPS',
                'Resolution' => 'SD',
            ],
            'LogLevel' => 'ERROR',
            'Name' => 'Mohit',
        ]);
        pre($result);
    }

    function delete_channel() {
        $client = new Aws\MediaLive\MediaLiveClient($this->get_credentials());
        $result = $client->deleteChannel([
            'ChannelId' => '<string>', // REQUIRED
        ]);
    }

    function attach_input() {
        $client = new Aws\MediaLive\MediaLiveClient($this->get_credentials());
        $result = $client->updateChannel([
            'ChannelId' => '8920470', // REQUIRED
            'InputAttachments' => [
                [
                    'InputAttachmentName' => 'testing',
                    'InputId' => '866540',
                ],
                [
                    'InputAttachmentName' => 'LiveChannel',
                    'InputId' => '2365010',
                ]
            // ...
            ],
            'LogLevel' => 'DISABLED',
            'Name' => 'testing',
        ]);
    }

    function create_input() {
        $client = new Aws\MediaLive\MediaLiveClient($this->get_credentials());
        $result = $client->createInput([
            'Destinations' => [
                [
                    'StreamName' => 'live3/c',
                ],
                [
                    'StreamName' => 'live4/d',
                ]
            ],
            'InputSecurityGroups' => ['5364901'],
            'MediaConnectFlows' => [],
            'Name' => 'testing',
            'RoleArn' => 'arn:aws:medialive:us-east-1:213369717207:input:2365010',
            'Type' => 'RTMP_PUSH',
        ]);
        pre($result);
    }

    function get_origin_list() {
        $client = new \Aws\MediaPackage\MediaPackageClient($this->get_credentials());
        $result = $client->listOriginEndpoints(array('channelId' => 8920470));
        pre($result);
    }

    function get_channels_list() {
        $client = new MediaLiveClient($this->get_credentials());
        $result = $client->listChannels();
        pre($result);
    }

    function get_json() {
        $var = '{
  "feed_instance_id": "e3d64fba-539a-4d74-9e03-5ba241821732",
  "title": "Intro to the JW Player API",
  "kind": "Single Item",
  "playlist": [
    {
      "mediaid": "gaCRFWjn",
      "description": "Developers, welcome to JW Player! This video is for you if you are familiar with JavaScript development and new to using JW Player. It will provide an introduction to the API structure, capabilities, and documentation on developer.jwplayer.com.",
      "pubdate": 1496792147,
      "tags": "demo,playback-rate,education",
      "image": "https://cdn.jwplayer.com/thumbs/gaCRFWjn-720.jpg",
      "title": "Intro to the JW Player API",
      "variations": {
        "images": [
          {
            "image": "https://cdn.jwplayer.com/v2/media/gaCRFWjn/thumbnails/3lv2gffa.jpg?width=720",
            "id": "3lv2gffa",
            "weight": 0.512
          },
          {
            "image": "https://cdn.jwplayer.com/v2/media/gaCRFWjn/thumbnails/v0vkvqcf.jpg?width=720",
            "id": "v0vkvqcf",
            "weight": 0.028
          }
        ]
      },
      "sources": [
        {
          "width": 320,
          "height": 180,
          "file": "https://tvekbj4siqkim7.data.mediastore.us-east-1.amazonaws.com/livea/main_480p30.m3u8",
          "label": "320px"
        },
        {
          "width": 480,
          "height": 270,
          "file": "https://cdn.jwplayer.com/videos/gaCRFWjn-TNpruJId.mp4",
          "label": "480px"
        }],
      "tracks": [
        {
          "kind": "thumbnails",
          "file": "https://cdn.jwplayer.com/strips/gaCRFWjn-120.vtt"
        }
      ],
      "link": "https://cdn.jwplayer.com/previews/gaCRFWjn",
      "duration": 161
    }
  ],
  "description": "Developers, welcome to JW Player! This video is for you if you are familiar with JavaScript development and new to using JW Player. It will provide an introduction to the API structure, capabilities, and documentation on developer.jwplayer.com."
}';
        return $var;
    }

    function get_credentials() {
        return [
            'version' => 'latest',
            'region' => "us-east-1",
            'credentials' => [
                'key' => "AKIAIEI3V27S2D7WQPSQ",
                'secret' => "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",
            ],
        ];
    }

}
