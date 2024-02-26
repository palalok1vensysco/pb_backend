<style>
    /*dont remove this line it help for identidy font*/
    @import url(https://fonts.googleapis.com/css?family=Material+Icons);

    body{
        -webkit-user-select: none;  /* Chrome all / Safari all */
        -moz-user-select: none;     /* Firefox all */
        -ms-user-select: none;      /* IE 10+ */
        -o-user-select: none;
        user-select: none;
    }

    .vjs-picture-in-picture-control{
        display: none !important;
    }

    #video_player_aws{
        /*width:640px;*/
        /*height:268px;*/
        width:100%;
        /*height:30vh;*/  
    }
</style>
<div class="col-sm-12 px-0">
    <div class="panel-body">

        <script src="<?= AUTH_ASSETS ?>js/jquery.js"></script>
        <script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/shaka-player.compiled.debug.js"></script>
        <script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/video.js"></script>
        <script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/videojs-shaka.min.js"></script>
        <script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/shaka-player.ui.js"></script>
        <script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/videojs-seek-buttons.min.js"></script>
        <script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/custom-videojs.js"></script>
        <link href="<?= base_url() ?>/auth_panel_assets/shaka_player/css/video-js.css" rel="stylesheet">

        <?php
        $file_url = "https://cf1.madeeasyprime.com/file_library/videos/vod_private/197055755784043940_video_44.m3u8";

        $cloud_front_domain = "madeeasyprime.com";


        function createSignedCookie($streamHostUrl, $resourceKey, $timeout) {
            $keyPairId = CLOUDFRONT_KEY_PAIR; // Key Pair
            $expires = time() + $timeout; // Expire Time
            $url = $streamHostUrl . '/' . $resourceKey; // Service URL
            $ip = $_SERVER["REMOTE_ADDR"] . "\/24"; // IP
            $json = '{"Statement":[{"Resource":"' . $url . '","Condition":{"DateLessThan":{"AWS:EpochTime":' . $expires . '}}}]}';

            $fp = fopen(FCPATH . 'made_easy_cloudfront.pem', "r");
            $priv_key = fread($fp, 8192);
            fclose($fp);

            $key = openssl_get_privatekey($priv_key);
            if (!$key) {
                echo "<p>Failed to load private key!</p>";
                return;
            }
            if (!openssl_sign($json, $signed_policy, $key, OPENSSL_ALGO_SHA1)) {
                echo '<p>Failed to sign policy: ' . opeenssl_error_string() . '</p>';
                return;
            }

            $base64_signed_policy = base64_encode($signed_policy);

            $policy = strtr(base64_encode($json), '+=/', '-_~'); //Canned Policy

            $signature = str_replace(array('+', '=', '/'), array('-', '_', '~'), $base64_signed_policy);

            //In case you want to use signed URL, just use the below code
            //$signedUrl = $url.'?Expires='.$expires.'&Signature='.$signature.'&Key-Pair-Id='.$keyPairId; //Manual Policy
            $signedCookie = array(
                "CloudFront-Key-Pair-Id" => $keyPairId,
                "CloudFront-Policy" => $policy,
                "CloudFront-Signature" => $signature
            );

            return $signedCookie;
        }

        $url = createSignedCookie("https://cf1.madeeasyprime.com", "file_library/videos/vod_private/*", 300);
        foreach ($url as $name => $value) {
            setcookie($name, $value, 0, "/", $cloud_front_domain);
        }
        ?>
        <video id="video_player_aws" class="video-js vjs-default-skin" autoplay controls preload="auto"></video>
        <script>
            var player = videojs("video_player_aws", {
                html5: {
                    vjs: {
                        withCredentials: true,
                        overrideNative: true
                    },
                },
//                techOrder: ['shaka'],
//                playbackRates: [0.5, 1, 1.5, 2, 4],
//                shaka: {
//                    debug: false,
//                    sideload: true
//                }
            });

//            player.qualityPickerPlugin();
            player.src([
                {
                    type: 'application/x-mpegURL',
                    src: "<?= $file_url ?>",
//                    withCredentials: true
                }
            ]);

//        player.seekButtons({
//            forward: 30,
//            back: 10
//        });

//            player.on('error', function () {
//                console.log(JSON.parse(JSON.stringify(player.error())));
//            });
//
//            player.play().then(function () {
//                console.log("autoplay was successful!");
//            }).catch(function (error) {
//                console.log(error);
//            });
        </script>
    </div>
</div>
<div class="col-sm-12 add_channel" style="display: none">
    <section class="panel">
        <header class="panel-heading">
            Add Channel
        </header>
        <div class="panel-body">
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group col-sm-6">
                        <label>Channel Name</label>
                        <input type="text" placeholder="Enter Channel Name" name="name"  class="form-control input-sm" required="">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Input Id</label>
                        <select class="form-control input-sm" required="" name="input_id">
                            <option value="">Select</option>
                            <?php
                            if ($inputs) {
                                foreach ($inputs as $in) {
                                    ?>
                                    <option value="<?= $in['input_id'] ?>"><?= $in['input_id'], " (" . $in['name'] . ")" ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Select MediaPackage</label>
                        <select class="form-control input-sm" required="" name="media_package_id">
                            <option value="">Select</option>
                            <?php
                            if ($media_package) {
                                foreach ($media_package as $in) {
                                    ?>
                                    <option value="<?= $in['id'] ?>"><?= $in['channel_id'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Select Resolution</label>
                        <select class="form-control input-sm" name="resolution" required="">
                            <option value="">Select</option>
                            <option value="SD" selected="">SD</option>
                            <option value="HD">HD</option>
                            <option value="UHD" >UHD</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Select Codec</label>
                        <select class="form-control input-sm" name="codec" required="">
                            <option value="">Select</option>
                            <option value="MPEG2" selected="">MPEG2</option>
                            <option value="AVC">AVC</option>
                            <option value="HEVC" >HEVC</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Select Max Bit Rate</label>
                        <select class="form-control input-sm" name="bitrate" required="">
                            <option value="">Select</option>
                            <option value="MAX_10_MBPS" selected="">MAX_10_MBPS</option>
                            <option value="MAX_20_MBPS">MAX_20_MBPS</option>
                            <option value="MAX_50_MBPS" >MAX_50_MBPS</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Type Remark</label>
                        <textarea class="form-control" name="remark"  required=""></textarea>
                    </div>
                    <div class="form-group col-sm-12">
                        <button class="btn btn-info" type="submit">Submit</button>
                        <button class="btn btn-danger" type="button"  onclick="$('.add_channel').hide('slow')">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </section>
</div>
<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading">
            Channel(s) LIST
            <span class="pull-right">
                <a class='btn-sm btn btn-success' onclick="$('.add_channel').show('slow')">Add</a>
            </span>
        </header>
        <div class="panel-body">
            <div class="card-body" style="">
                <div class="timeline-messages">
                    <?php
                    if ($channels) {
                        foreach ($channels as $ch) {
                            ?>
                            <!-- Comment -->
                            <div class="msg-time-chat">
                                <div class="message-body msg-in">
                                    <span class="arrow"></span>
                                    <div class="text">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a class="pull-right btn-xs hide" <?= ($ch['state'] == "idle") ? "onclick='return confirm(\"Are You Sure Want To Delete This Channel\");'" : "onclick='alert(\"Please Stop Channel First\");return false;'" ?> href="<?= AUTH_PANEL_URL . "live_module/channels/delete_channel/" . $ch['id'] . "/" . $ch['channel_id'] ?>"><i class="fa fa-times"></i></a>
                                                <?php
                                                if ($ch['state'] == 'idle') {
                                                    ?>
                                                    <a class="btn btn-xs btn-info pull-right" onclick="if (!confirm('Are You Sure Want To Start Channel')) {
                                                                return false;
                                                            }" href="<?= AUTH_PANEL_URL . "live_module/channels/start_channel/" . $ch['id'] . "/" . $ch['channel_id'] ?>">Start</a>
                                                       <?php
                                                   } else {
                                                       ?>
                                                    <a class="btn btn-xs btn-warning pull-right" onclick="if (!confirm('Are You Sure Want To Start Channel')) {
                                                                return false;
                                                            }" href="<?= AUTH_PANEL_URL . "live_module/channels/stop_channel/" . $ch['id'] . "/" . $ch['channel_id'] ?>">Stop</a>
                                                       <?php
                                                   }
                                                   ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Name</a> <?= $ch['channel_name'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Channel Id</a> <?= $ch['channel_id'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Input Id</a><?= json_decode($ch['input_ids'], TRUE)[0]['InputId'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Media Package Id</a><?= $ch['media_package_ids'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Input Name</a><?= json_decode($ch['input_ids'], TRUE)[0]['InputAttachmentName'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">ARN </a> <?= $ch['arn'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">State </a> <?= $ch['state'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Codec </a> <?= $ch['codec'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Bit Rate </a> <?= $ch['bit_rate'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Resolution </a> <?= $ch['resolution'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Log Level </a> <?= $ch['log_level'] ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="attribution"><a href="#">Input URL-1 </a> <?= $ch['input_a'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">Output URL-1 </a> <?= $ch['output_a'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">Input URL-2 </a> <?= $ch['input_b'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">Output URL-2 </a> <?= $ch['output_b'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">Output URL-3 </a> <?= $ch['output_c'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">Remark </a> <?= $ch['remark'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /comment -->
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>