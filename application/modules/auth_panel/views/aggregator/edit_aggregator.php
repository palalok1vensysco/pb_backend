<link href="<?= AUTH_ASSETS ?>shaka_player/css/video-js.css" rel="stylesheet">
<script src="<?= AUTH_ASSETS ?>shaka_player/js/shaka-player.compiled.debug.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/video.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/videojs-shaka.min.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/shaka-player.ui.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/videojs-seek-buttons.min.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/mux.min.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/custom-videojs.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/Youtube.min.js"></script>

<script src="<?= AUTH_ASSETS ?>assets/ckeditor/ckeditor.js"></script>

<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>aws/aws-sdk-2.1.12.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>aws/aws-init.js"></script>
<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?=AUTH_ASSETS?>js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>assets/bootstrap-datetimepicker/css/datetimepicker.css" />
<script type="text/javascript" src="<?=AUTH_ASSETS?>assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js" defer></script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://www.youtube.com/iframe_api"></script>
<!-- <link href="<?= AUTH_ASSETS ?>shaka_player/css/video-js.css" rel="stylesheet"> -->
<?php

$error = validation_errors();
$form_cat_ids = set_value('category_ids');
$form_name_ids = set_value('season_name');
$form_artist_ids = set_value('artist'); 
$display = "display:none;";
if (!empty($error)) {
    $display = "";
}
if (isset($validation_error)) {
    $validation = $validation_error;
} else {
    $validation = 0;
}
// $sql = "SELECT count(*) as total
// FROM premium_season where status !=2 ";
// $total = $this->db->query($sql)->row()->total;
//pre($video_detail);die;
//pre($f_lists);die;
//pre($categories);die;
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
#video_player_aws_new{
    width:100% !important;
    height:220px !important
}
.select2-container{
            border: none !important;
        }
        .select2-container .select2-selection--single .select2-selection__rendered{
            border: 1px solid gray;
            border-radius: 4px;
        }
</style>

<div class="col-md-6">
    <section class="panel">
        <header class="panel-heading displa_flex align-items-center justify-content-between bg-dark editVideoHead">
            <span>Edit Video</span>                 
                 <button class="btn btn-xs display_color text-white"  type="button" > 
                    <a href="javascript:history.back()" class="text-white">Go Back</a>
                </button>        
        </header>
        <div class="panel-body">
            <form role="form"  method="post" enctype="multipart/form-data" autocomplete="off" >
        

                <div class="form-group col-md-12 ">
                    <label >Title <span style="color:#ff0000">*</span></label>
                    <input type="text" placeholder="Enter Aggregator Title" value = "<?= $agg_detail['title']; ?>" name = "title" id="title" class="form-control input-xs vid-wid" >                    

                </div>
                <div class="form-group col-md-12 ">
                    <label class="file_title">Upload Background Video</label>
                    <input type="file" accept="image/gif" name ="bg_video" id="bg_video" placeholder="choose your file" class="form-control input-xs" value="<?= $agg_detail['bg_video'] ?>" >
                    
                 </div>
                <div class="form-group col-md-12 video">
                    <label>Video Image</label>
                    <small>
                       <p>               
                        <strong>Image Type :</strong> jpg, jpeg, gif, png
                        ,<strong>Width  :</strong> 720 pixels
                        , <strong>Height :</strong> 420 pixels
                       </p>
                    </small>
                    <input type="file" accept="image/*" name="thumbnail" class="form-control input-xs" id="thumbnail" >
                      <?php if (($agg_detail['thumbnail'])) { ?>
                        <img width="150" src="<?= $agg_detail['thumbnail'] ?>" class="img-responsive" id="post_img">
                    <?php } ?>
                    <span id="thumbnailmsg" style="color: red;"></span>
                </div>

            

                <div class="form-group col-md-12">
                    <button class="btn btn-sm display_color text-white f-600"  type="submit" >Update</button>
                    <!-- <button class="btn btn-xs display_color"  type="button" onclick="$('.add_file_element').hide('slow');" >Cancel</button>
                    <button type="button" class="btn btn-xs resetvideo display_color">Clear</button> -->
                    <!--onclick="$('.add_file_element').hide('slow');"-->
                </div>
            </form>

        </div>
    </section>
</div>
<div class="col-lg-6 hide_data_chk">
      <section class="panel">
        <header class="panel-heading bg-dark text-white">
            <span>Video Preview</span>
        </header>
        <div class="panel-body">
          
            <img width="300" height="300" id="video_player_aws_new" class="video-js vjs-default-skin m-auto" src="<?= $agg_detail['bg_video'] ?>" autoplay controls preload="auto">
     
        </div>
      </section>
    </div>
</div>


<?php 


?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://devadmin.videocrypt.in/auth_panel_assets/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
<script type="text/javascript" src="https://devadmin.videocrypt.in/auth_panel_assets/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>




<script>

    $(".video_thumbnail").click(function () { 
        if ($(this).text() == "File") {//file
            $(this).text("Url");
            $("input[name=thumbnail]").attr('type', 'file')
        } else {//url
            $(this).text("File");
            $("input[name=thumbnail]").attr('type', 'text')
        }
    });


    $("#video_file").change(function (e) {
        if ($(this).attr("file") != "file")
            return false;
        var source = $('#video_here');
        if (isVideo($(this).val())) {
            $("#player").hide();
            var size = upload_file_size($("#video_file")[0]);

            source.parent().show();
            source[0].src = URL.createObjectURL(this.files[0]);
            source.parent()[0].load();

            size = size.split(" ");
            $("input[name=size]").val(size[0]);
            $("input[name=name]").val(e.target.files[0].name);

        } else {
            source.parent().hide();
            $("#player").show();
        }
    });

  

</script>
<script src="https://unpkg.com/mathjs/lib/browser/math.js"></script>
<script>
     var _URL = window.URL || window.webkitURL;
          $("#thumbnailInputFile1").change(function(e) {
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
                         document.getElementById("thumbnailmsg").textContent="Please Enter aspect ratio size 720:420";
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
</script>


<?php $this->load->view("file_manager/common_script");?>