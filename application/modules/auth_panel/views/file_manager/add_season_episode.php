
<script src="<?= AUTH_ASSETS ?>assets/ckeditor/ckeditor.js"></script>

<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>aws/aws-sdk-2.1.12.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>aws/aws-init.php?r=<?php echo $ams_region; ?>&b=<?php echo $ams_bucket_name; ?>&i=<?php echo $cognito; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?=AUTH_ASSETS?>js/jquery.dataTables.js"></script>
<link href="<?= AUTH_ASSETS ?>shaka_player/css/video-js.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>assets/bootstrap-datetimepicker/css/datetimepicker.css" />
<script type="text/javascript" src="<?=AUTH_ASSETS?>assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js" defer></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/shaka-player.compiled.debug.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/video.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/videojs-shaka.min.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/shaka-player.ui.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/videojs-seek-buttons.min.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/mux.min.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/custom-videojs.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/Youtube.min.js"></script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://www.youtube.com/iframe_api"></script>
<?php
$error = validation_errors();
$form_cat_ids = set_value('category_ids');
$form_name_ids = set_value('season_name');
$form_artist_ids = set_value('artist'); 
?>
        
<style type="text/css">
    .form{
        text-align: center;
    }

    .video-nput-2 label{display: block!important;}


    .video-nput-2 .vid-wid {
    display: inline-block;
    width: 94%;
    /* padding: 0px!important; */
}
.select2-container{
    border: none !important;
}
.select2-container .select2-selection--single .select2-selection__rendered{
    border: 1px solid gray;
    border-radius: 4px;
}
.videoSearcHBox {
    display: flex;
    justify-content: space-between;
    gap: 3px;
}
#video_search {
    width: 34px;
    height: 34px;
}

video#video_player_aws_new , div#video_player_aws_new{
	width: 100%;
	margin: auto;
	display: block;
}
</style>

<div class="col-lg-6 no-padding" >
    <section class="panel">
        <header class="panel-heading displa_flex align-items-center justify-content-between">
        Add Season Episode for <?php if($cate=="3"){ echo "TV Serials";}else if($cate=="2"){echo "Web Series";}else{ echo "Default";} ?>

        <a href="<?= base_url()?>/auth_panel/file_manager/library/add_video"><button class="pull-right btn display_color btn-xs bold text-white f-600">Back to list</button></a>
        </header>
        <div class="panel-body">
            <form role="form"  method="post" enctype="multipart/form-data" autocomplete="off" >
                <div class="form-group col-md-12 ">
                    <label >Video Type <span style="color:#ff0000">*</span> </label>
                    <select class="form-control input-xs" name="video_type" required>
                        <option value="">Select Video Type</option>
                      <!--    <option value="8"  >Videocrypt LIVE</option> -->
                        <option value="7">Videocrypt VOD</option>
                    </select>
                </div> 
                <div class="form-group col-md-12 videocript video-nput-2">   
                    <input type="text" name = "ctfmm_id" value="<?= $ctfmm_id; ?>" class="form-control input-xs vid-wid hide"> 
                    <input type="text" name = "cate_id" value="<?= $cate; ?>" class="form-control input-xs vid-wid hide">      
                    <label >Videocript ID</label>
                    <div class="videoSearcHBox">
                        <input type="text" placeholder="Enter ID Here" name = "videocript_id" id="videocript_id" class="form-control input-xs vid-wid" >                    
                        <button class="btn btn-success btn-xs" id="video_search" type="button" onclick="player_stop()"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label >Video Title <span style="color:#ff0000">*</span> </label>
                    <input type="text" placeholder="Enter Title Here" name = "title" id="title" class="form-control input-xs" required="" maxlength="55">
                    <span class="error bold"><?php echo form_error('title'); ?></span>
                </div>
                <div class="form-group col-md-12 hide  ">
                    <label >Video Title transcribe <span style="color:#ff0000">*</span></label>
                    <input type="" placeholder="Enter Title Here" name = "transcribe"  id="transcribe"  class="form-control input-xs" >
                    <span class="error bold"><?php echo form_error('title'); ?></span>
                </div>
                <div class="form-group col-md-12 upload_video_cls vod_url">
                    <label class="file_title">Upload Video </label><!-- <button class="btn btn-info btn-xs cover_video" type="button">Url</button> -->&nbsp;&nbsp;
                    <input type="input" accept="video/mp4" name ="video_file" id="video_file" placeholder="Enter Video URL" class="form-control input-xs" >
                 </div>
                <div class="form-group col-md-6 playtime enter_class ">
                    <label >Enter Video Duration(in Minutes)</label>
                    <input type="text" placeholder="Enter playtime in min." maxlength="5" name = "playtime" class="form-control input-xs">
                    <span class="error bold"><?php echo form_error('playtime'); ?></span>
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Episode No.<span style="color:#ff0000">*</span> </label>
                    <input class="form-control" name="episode_no" min="1" max="5" placeholder="Enter Episode" onkeypress="return isNumber(event)" oninput="check(this)" required>
                </div>

                <div class="form-group col-md-6 "  >
                    <label class="control-label  pl-0" ><strong>View Mode</strong></label>
                    <select name="movie_view" class="form-control input-xs">
                        <option value="0">FREE</option>
                        <option value="1">PAID</option> 
                        <?php if(isset($f_lists->paid) && $f_lists->paid == 1 ){ ?>
                        <option value="1">PAID</option>
                        <?php } ?>
                    </select>
                    
                </div>

                <div class="form-group col-md-6 is_drm" >
                    <label>Enable DRM Protection</label>
                    <select class="form-control input-xs" name="is_drm_protected">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

                <div class="form-group col-md-6 skip_intro" >
                    <input type="checkbox" id="skip_intro" name="skip_intro" value="1" onkeypress="return isNumber(event)"  onclick="skipIntro()">
                    <label>Skip Intro</label>
                </div>
                
                <div class="form-group col-md-6 seconds" style="display:none" >
                <label>Seconds</label>
                    <input type="text" id="skip_time" class="form-control" maxlength="2" min="1" name="skip_time" oninput="checkpricee(this)" onkeydown="return (event.ctrlKey || event.altKey
                            || (47 < event.keyCode && event.keyCode < 58 && event.shiftKey == false)
                            || (95 < event.keyCode && event.keyCode < 106)
                            || (event.keyCode == 8) || (event.keyCode == 9)
                            || (event.keyCode > 34 && event.keyCode < 40)
                            || (event.keyCode == 46))">
                   
                </div>
             

                <div class="form-group col-md-12 released_date">
                    <label for="published-date-user">Released Date</label>
                    <div data-date-format="yyyy-mm-dd" data-date="2013/12/1">
                        <input class="form-control published-date-user" type="date" name="published_date" id="published-date-user" placeholder=" Select Published Date From" value="<?php echo set_value('published_date'); ?>">
                    </div>
                    <span class="text-danger"><?php echo form_error('published_date'); ?></span>
                </div>
                

                <div class="form-group col-md-12 drm_hls_url hide" style="display:none">
                    <label >DRM Hls Url</label>
                    <input type="input" accept="video/mp4" name ="drm_hls_url" id="drm_hls_url" placeholder="Enter DRM DASH Video URL" class="form-control input-xs" >
                </div>
                <div class="form-group col-md-12 drm_dash_url hide"  style="display:none">
                    <label >DRM Dash Url</label>
                    <input type="input" accept="video/mp4" name ="drm_dash_url" id="drm_dash_url" placeholder="Enter DRM HLS Video URL" class="form-control input-xs" >
                </div>

                <!-- <div class="form-group col-md-12 price_per_video">
                    <label>Price</label>
                    <input type="input" name ="ppv"  id="price_per_video" placeholder="Enter Price Per Video " class="form-control input-xs" oninput="checkprice(this)" onkeydown="return (event.ctrlKey || event.altKey
                            || (47 < event.keyCode && event.keyCode < 58 && event.shiftKey == false)
                            || (95 < event.keyCode && event.keyCode < 106)
                            || (event.keyCode == 8) || (event.keyCode == 9)
                            || (event.keyCode > 34 && event.keyCode < 40)
                            || (event.keyCode == 46))" maxlength="5">
                </div> -->

                <div class="form-group col-md-12 video_thumbnail">
                    <label>Video Portrait <span style="color:#ff0000">*</span></label>
                       <small>
                        <p>               
                        <strong>Image Type :</strong> jpg, jpeg, gif, png
                        ,<strong>Width  :</strong>540 pixels
                        , <strong>Height :</strong>720 pixels
                        </p>
                    </small> 
                    <input type="file" accept="image/*" name = "thumbnail" id="thumbnailInputFile1" class="form-control input-xs" required>
                    <span class="error bold"><?php echo form_error('thumbnail'); ?></span>
                    <span id="thumbnailmsg" style="color: red;"></span>
                </div>

                <div class="form-group col-md-12 video">
                    <label>Video Landscape <span style="color:#ff0000">*</span> 
                        <!-- <button class="btn btn-info btn-xs video_poster display_color" type="button">Url</button> -->
                    </label>
                       <p>               
                <strong>Image Type :</strong> jpg, jpeg, gif, png
                ,<strong>Width  :</strong> 720 pixels
                , <strong>Height :</strong> 420 pixels
                </p>
                    <input type="file" accept="image/*" name = "poster" class="form-control input-xs" id="posterInputFile1" placeholder="Enter Poster Url" required="">
                    <span class="error bold"><?php echo form_error('poster'); ?></span>
                    <span id="postermsg" style="color: red;"></span>
                </div>

               <!--  <div class="form-group col-md-12 released_date">
                    <label for="published-date-user">Released Date</label>
                    <div data-date-format="yyyy-mm-dd" data-date="2013/12/1">
                        <input class="form-control published-date-user" type="date" name="published_date" id="published-date-user" placeholder=" Select Published Date From" value="<?php echo set_value('published_date'); ?>">
                    </div>
                    <span class="text-danger"><?php echo form_error('published_date'); ?></span>
                </div> -->

                <div class="form-group col-md-12" >
                    <span style="color:#ff0000">*</span> 
                    <label class="col-sm-12 control-label col-sm-2">Description</label>
                    <div class="col-sm-12">
                        <textarea placeholder="Enter Description" class="form-control " name="description" rows="6"  maxlength="300" required><?php echo set_value('movie_desc'); ?></textarea>
                        <br>
                    </div>
                    <span class="custom-error"><?php echo form_error('video_desc'); ?></span>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-sm display_color text-white f-600"  type="submit" >Upload</button>
                    <button class="btn btn-sm display_color text-white f-600"  type="button" onclick="$('.add_file_element').hide('slow');" >Cancel</button>
                   <!--  <button type="button" class="btn btn-xs resetvideo display_color">Clear</button> -->
                    <!--onclick="$('.add_file_element').hide('slow');"-->
                </div>
            </form>
         
        </div>
    </section>
</div>

   <div class="col-lg-6 hide_data_chk add_file_element" style="display:block;">
     <section class="panel">
        <header class="panel-heading">
            Video Preview
        </header>
           <div class="panel-body" id="test_player">
            <video width="450" autoplay  controls preload="auto" height="300" id="video_player_aws_new" class="video-js vjs-default-skin" ></video>
         </div>
      </section>
   </div>
   
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://devadmin.videocrypt.in/auth_panel_assets/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
<script type="text/javascript" src="https://devadmin.videocrypt.in/auth_panel_assets/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script src="<?= AUTH_ASSETS ?>js/jquery.validate.min.js" type="text/javascript"></script>

<script src="https://unpkg.com/mathjs/lib/browser/math.js"></script>
<script>
 function checkpricee(input) {
   if (input.value == 0) {
     input.setCustomValidity('The seconds must not be zero.');
   } else {
     // input is fine -- reset the error message
     input.setCustomValidity('');
   }
 }
</script>

   <script>
        var _URL = window.URL || window.webkitURL;
            $("#thumbnailInputFile1").change(function(e) {
                var file, img;
                    var n_width=540,n_height=720;
                if ((file = this.files[0])) {
                    img = new Image();
                    img.onload = function() {
                        var ratio = this.width/this.height;
                    var ratio1 = ratio.toFixed(1);
                   //  alert(ratio1);
                     if(ratio1 != '0.8')
                     {
                         document.getElementById("thumbnailmsg").textContent="Please Enter aspect ratio size 540:720";
                         $("#thumbnailInputFile1").val('');
                     }
                     else
                     {
                         document.getElementById("thumbnailmsg").textContent=" ";
                     }
                     
                    };
                    img.onerror = function() {
                        alert( "not a valid file: " + file.type);
                        $("#thumbnailInputFile1").val('');
                    };
                    img.src = _URL.createObjectURL(file);

                }
            });

            $("#posterInputFile1").change(function(e) {
                var file, img;
                    var n_width=720,n_height=420;
                if ((file = this.files[0])) {
                    img = new Image();
                    img.onload = function() {
                        var ratio = this.width/this.height;
                    var ratio1 = ratio.toFixed(1);
                   // alert(ratio1);
                     if(ratio1 != '1.7')
                     {
                         document.getElementById("postermsg").textContent="Please Enter aspect ratio size 720:420";
                         $("#posterInputFile1").val('');
                     }
                     else
                     {
                         document.getElementById("postermsg").textContent=" ";
                     }
                     
                    };
                    img.onerror = function() {
                        alert( "not a valid file: " + file.type);
                        $("#posterInputFile1").val('');
                    };
                    img.src = _URL.createObjectURL(file);

                }
            });
   </script> 

<script>
 function checkprice(input) {
   if (input.value == 0) {
     input.setCustomValidity('The price must not be zero.');
   } else {
     // input is fine -- reset the error message
     input.setCustomValidity('');
   }
 }
</script>

<script>
    function copy_url(copy_btn_id,text_to_copy_id) {
        var copy        = document.getElementById(copy_btn_id);
        //console.log(copy); return false;
        var selection   = window.getSelection();
        var range       = document.createRange();
        var textToCopy  = document.getElementById(text_to_copy_id);
        range.selectNodeContents(textToCopy);
        selection.removeAllRanges();
        selection.addRange(range);
        var successful  = document.execCommand('copy');
        if (successful) {
            save_url_to_db(selection);
        } else {
            alert('Unable to copy!');
        }
        window.getSelection().removeAllRanges()
    }
    function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("myInput");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

  /* Alert the copied text */
  alert("Copied the text: " + copyText.value);
}
    var META_ID = "_video_VOD";

    var player = null;
    async function customLoader(id) {
        return new Promise(function (resolve) {
            overlay("Please Wait.. We are preparing Player Preview.");
            player = new YT.Player('player', {
                height: '300',
                width: '450',
                videoId: id,
                playerVars: {
                    'playsinline': 1
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
            resolve(player);
        })
    }

    // 4. The API will call this function when the video player is ready.
    function onPlayerReady(event) {
        event.target.playVideo();
        $("input[name=playtime]").val(parseInt(player.getDuration() / 60));
        if (player.getDuration() == 0) {
            $(".live_start_date").show();
        } else {
            $(".live_start_date").hide();
            $("#start_date").val("");
        }

        overlay("");
        if (player != null)
            stopVideo();
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.
    var done = false;
    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
            setTimeout(stopVideo, 6000);
            done = true;
        }
    }
    function stopVideo() {
        try {
            player.stopVideo();
        } catch (e) {
            console.log("Video Stop Exception");
        }

    }

    function youtube_parser(url) {
        var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
        var match = url.match(regExp);
        return (match && match[7].length == 11) ? match[7] : false;
    }

    $("#video_file").keyup(function (e) {
        let val = $(this).val();
        if (player != null) {
            stopVideo();
        }
        let video_type = $('select[name=video_type]').val();
        if (video_type == 0 || video_type == 5)
            return false;

        let yt_id = youtube_parser(val);
        if (!val.includes("yout") || yt_id == undefined || yt_id == "") {
            player_src = $('#video_here');
            (val.includes("jwplatform")) ? player_src.parent().show() : "";
            player_src[0].src = val;
            player_src.parent()[0].load();

            var id = val.substring(val.indexOf("videos/") + 7, val.lastIndexOf("-"));
            let thumbanil = "https://cdn.jwplayer.com/v2/media/" + id + "/poster.jpg?width=720";
            $("input[name=thumbnail]").attr("type", "text");
            $("input[name=thumbnail]").val(thumbanil);
        } else {
            $("input[name=thumbnail]").attr("type", "text");
            $("#video_file").val(yt_id);

            //YT API
            $.getJSON("https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=" + yt_id + "&format=json", function (json) {
                $("input[name=thumbnail]").attr("type", "text");
                $("input[name=thumbnail]").val(json.thumbnail_url);
                $("input[name=title]").val(json.title);
            });
            customLoader(yt_id);
        }
    });

        function skipIntro() {
          var skip_int = document.getElementById("skip_intro");
         
         if (skip_int.checked == true){
            $(".seconds").show();
          } else{
            $(".seconds").hide();


          }
        }

</script>
<script type="text/javascript" language="javascript" >
    jQuery(document).ready(function () {
        var table = 'all-user-grid';
        var dataTable = jQuery("#" + table).DataTable({
            "processing": true,
            "pageLength": 15,
            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
            "serverSide": true,
            "order":[[0,"desc"]],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [2,5,6]},
            ],
            "ajax": {
                url: "<?= AUTH_PANEL_URL ?>file_manager/library/ajax_video_file_list/", // json datasource
                type: "post", // method  , by default get
                error: function () {  // error handling
                    jQuery("." + table + "-error").html("");
                    jQuery("#" + table + "_processing").css("display", "none");
                }
            }
        });
        var table = 'all-user-grid'
        jQuery("#" + table + "_filter").css("display", "none");
        //bind_table_search(dataTable, table, 'keyup');
        //bind_table_search(dataTable, table, 'change');
        $(".Downloadclass").hide();

    });

    $(".fetch_list").click(function () {
        let id = $(this).data("id");
        alert(id);
        jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>file_manager/library/fetch_video_playlist",
            method: 'Post',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (data) {
                if (data.type == "success") {
                    let table = $(".offline_vod_auto").find("tbody");
                    table.html("");
                    $.each(data.data, function (key, value) {
                        table.append("<tr><td>" + (key + 1) + "</td><td>" + value.name + "</td><td>" + (value.size == "" ? "<i class='fa fa-times'></i>" : value.size) + "</td><td><button data-file_name='" + value.name + "' " + (value.size == "" ? "" : "disabled") + " data-id='" + id + "' data-link='" + value.link + "' class='btn btn-" + (value.size == "" ? "warning" : "info") + " btn-xs download_vod_offline'>" + (value.size == "" ? "<i class='fa fa-download'></i>" : "<i class='fa fa-check'></i>") + "</button></td></tr>");
                    });
                }
                show_toast(data.type, data.title, data.message);
            }
        });
    });

    $(".cover_video").click(function () {
        $('#video_here').parent().hide();
        $('#player').hide();
        if ($(this).text() == "File") {//file
            $(this).text("Url");
            $(".instant_upload").removeClass("hide");
            $(".file_title").text("Upload Video");
            $("#video_file").attr('type', 'file');
        } else {//url
            $(this).text("File");
            $(".instant_upload").addClass("hide");
            $(".file_title").text("Enter File URL");
            $("#video_file").attr('type', 'text');
        }
    });

    //Subject Search
   
    $(".content_thumb").click(function () { 
        if ($(this).text() == "File") {//file
            $(this).text("Url");
            $("input[name=thumbnail]").attr('type', 'file')
        } else {//url
            $(this).text("File");
            $("input[name=thumbnail]").attr('type', 'text')
        }
    });

    function load_video_play_time() {
        var video_duration = document.getElementById("videoid").duration;
        $("input[name=playtime]").val(Math.floor(video_duration));
    }

    function player_stop() { 
        if(sr > 0){
            var widevineToken = '';
            let player = document.getElementById('video_player_aws_new');;

            videojs(player).dispose();
            $("#test_player").html('<video width="450" autoplay  controls preload="auto" height="300" id="video_player_aws_new" class="video-js vjs-default-skin" ></video>');
            
        }
    }

    // If user tries to upload videos other than these extension , it will throw error.
    function isVideo(filename) {
        var parts = filename.split('.');
        var ext = parts[parts.length - 1];
        switch (ext.toLowerCase()) {
            case 'm4v':
            case 'avi':
            case 'mp4':
            case 'mov':
            case 'mpg':
            case 'mpeg':
                return true;
        }
        return false;
    }

    // $('#start_date').datetimepicker({
    //     startDate: new Date()
    // });
     var sr = 0;



    $("#video_search").click(function(){ 
        $(".is_drm").hide();
        $('.video_file_dash').hide();
        $('.play_via').hide();
        var vid=$('#videocript_id').val();
        if(vid!=""){
            jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>file_manager/library/fetch_videocrypt_playlist",
            method: 'Post',
            dataType: 'json',
            data: {
                v_id: vid
            },
             success: function (data) {
               // console.log(data.data.transcripts_data);
                const data_trans = data.data.transcripts_data;
                const jsonStringArray = data_trans.map(obj => JSON.stringify(obj));
                   // console.log(jsonStringArray);
                if (data.type == "success") {
                    sr++; 
                     $("input[name=playtime]").val(data.data.duration);
                     $("input[name=video_file]").val(data.data.file_url_hls);
                     $("#title").val(data.data.title);
                     $("#videoid").val(data.data.original_url);
                     $("#transcribe").val(jsonStringArray);
                     if(data.data.drm_dash_url!=""){
                         $(".is_drm").show();
                         $('.drm_dash_url').show();
                         $('#drm_dash_url').val(data.data.drm_dash_url);
                     }
                     if( data.data.drm_hls_url!=""){
                         $(".is_drm").show();
                         $('.drm_hls_url').show();
                         $('#drm_hls_url').val(data.data.drm_hls_url);
                     }
                     if( data.data.file_url_dash!=""){
                         $('.video_file_dash').show();
                         $('#video_file_dash').val(data.data.file_url_dash);
                         $('.play_via').show();
                     }
                       if(data.data.vod_vtt!=""){
                        // $(".is_drm").show();
                         //$('.vod_vtt').show();
                         $('#vod_vtt').val(data.data.vod_vtt);
                     }  
                     if(data.data.vod_srt!=""){
                        // $(".is_drm").show();
                        // $('.vod_srt').show();
                         $('#vod_srt').val(data.data.vod_srt);
                     }  

                    init_shaka_player("video_player_aws_new", data.data.file_url_hls, 'm3u8', 'm3u8');          
                }
                show_toast(data.type, data.title, data.message);
            }
        });
        }
    });


</script>
<script>
 function check(input) {
   if (input.value == 0) {
     input.setCustomValidity('The number must not be zero.');
   } else {
     // input is fine -- reset the error message
     input.setCustomValidity('');
   }
 }
</script>

<script type="text/javascript">
    function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
<?php $this->load->view("file_manager/common_script");?>