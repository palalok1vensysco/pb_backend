<script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/shaka-player.compiled.debug.js"></script>
<script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/video.js"></script>
<script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/videojs-shaka.min.js"></script>
<script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/shaka-player.ui.js"></script>
<script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/videojs-seek-buttons.min.js"></script>
<script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/custom-videojs.js"></script>
<link href="<?= base_url() ?>/auth_panel_assets/shaka_player/css/video-js.css" rel="stylesheet">
<style>
    /*dont remove this line it help for identidy font*/
    @import url(https://fonts.googleapis.com/css?family=Material+Icons);

    #video_player_aws{
        /*width:640px;*/
        height:275px;
        width:100%;
        /*height:30vh;*/
    }
    .btn__style{
        /*display: flex;*/
        flex-direction: column;
        text-align: right;
    }
    .btn__style a,.btn__style button{
        width: 180px;
        margin: 3px 0;
        color: #fff;
    }
</style>
<section class="panel add_channel" style="display: none">
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
                        <option value="HD" selected="">HD</option>
                        <option value="SD" >SD</option>
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
<section class="panel">
    <header class="panel-heading bg-dark text-white">
        Channel(s) LIST
       
        <span class="pull-right">
            <a class='display_color btn-xs btn pull-right clr_green dropdown_ttgl text-white' href="<?php echo site_url('auth_panel/live_module/channels/fetch_channel');?>">Fetch Channel</a>
        </span>
    </header>
    <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Channel Name</th>
                            <th>Channel Id</th>
                            <th>State</th> 
                            <th>Rtmp Url</th> 
                            <th>Rtmp Key</th> 
                            <th>Creation Date</th> 
                            <!-- <th>Action </th>      -->
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="0"  class="search-input-text form-control"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th> 
                            <!-- <th></th>  -->
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
</section>

<div role="dialog"  id="video_preview" class="modal fade" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">�</button>
                <h4 class="modal-title "> Video Live Preview</h4>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <div class="col-md-12">
                        <video id="video_player_aws" class="video-js vjs-default-skin" autoplay controls preload="auto"></video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(".preview_button").click(function () {
        $("#video_preview").modal('show');
        let selector = $(this);
        $.ajax({
            type: 'POST',
            url: "<?= AUTH_PANEL_URL ?>live_module/channels/create_cloudfront_url",
            data: {
                name: selector.data("name"),
                flag: 1,
                course_id: "0"
            },
            dataType: 'json',
            success: function (data) {
                if (data.data == 1) {
                    init_shaka_player("video_player_aws", data.url, data.type, data.token);
                } else {
                    show_toast('error', 'Internal Error', 'Aws Url');
                }
            },
            error: function (data) {
                show_toast('error', 'Not able to generate url. Please try after sometime', 'Error');
            }
        });
    });

    $('#video_preview').on('hidden.bs.modal', function () {
        pause_shaka_player("video_player_aws");
        //window.location.reload();
    });

    $("input[name=channel_type]").change(function () {
        let channel_type = 0;
        if ($(this).is(":checked")) {
            channel_type = 1;
        }
        $(".timeline-messages").children().each(function () {
            if (channel_type == 0) {
                $(this).css({display: ($(this).data("state") == "Running" ? "block" : "none")});
            } else {
                $(this).css({display: ($(this).data("state") == "Running" ? "none" : "block")});
            }
        });
    });

    var switchery = new Switchery($("input[name=channel_type]")[0], {size: 'small'});
</script>

<?php

$adminurl = AUTH_PANEL_URL;

$custum_js = <<<EOD
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
                 <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >                   
        jQuery(document).ready(function() {
                       var table = 'all-user-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                            "pageLength": 15,
                            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
                           "serverSide": true,
                           "ordering": false,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"live_module/channels/ajax_channel/", 
                               type: "post", 
                               error: function(){  
                                   jQuery("."+table+"-error").html("");
                                   jQuery("#"+table+"_processing").css("display","none");
                               }
                           }
                       } );
                       jQuery("#"+table+"_filter").css("display","none");
                       $('.search-input-text').on( 'keyup', function () {  
                           var i =$(this).attr('data-column');  
                           var v =$(this).val(); 
                           dataTable.columns(i).search(v).draw();
                       } );
                        $('.search-input-select').on( 'change', function () {  
                            var i =$(this).attr('data-column');
                            var v =$(this).val();
                            dataTable.columns(i).search(v).draw();
                        } );    

            $("body").on("click", ".copy_url", function(event){
                var tmpInput = $('<input>');
                  tmpInput.val($(this).data('url'));
                  $('body').append(tmpInput);
                  tmpInput.select();
                  document.execCommand('copy');
                  tmpInput.remove();
                  alert("Url copied paste it anywhere to use image url")
            });                                     
                   } );
                   
     function checkpricee(input) {
    if (input.value == 0) {
      input.setCustomValidity('The category value must not be zero.');
    } else {
      // input is fine -- reset the error message
      input.setCustomValidity('');
    }
  }
  function cancel() {
    document.getElementById("category_form").reset();
    $('.add_file_element').hide('slow');  
}
                                   
               </script>  
EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
