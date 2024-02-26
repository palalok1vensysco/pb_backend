<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
    .btncolorchange .btn-default{
        background-color: #ffffff !important;
        border-color: #a3a3a3 !important;
        color: #85899b !important;
        margin-bottom: 20px;
        padding: 3px
    }
</style>

<!--Loader CSS-->

<!--Loader CSS-->

<?php
$mobile_menu_ids = set_value('mobile_menu_ids');
$android_tv_ids = set_value('android_tv_ids');
$movies_sub_categorys = set_value('sub_category');
$form_artist_ids = set_value('artist');
$related_subscription = set_value('related_sub');
$form_day_ids = set_value('days');
?>
<div>
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading text-white bg-dark">
                ADD MOVIES
                <a href="<?= base_url('admin-panel/list-movies'); ?>"><button class="pull-right btn btn-info btn-xs bold">Back to Movies list</button></a>
                
            </header>
            <div class="panel-body  bg-white">
                <form autocomplete="off"  role="form" method= "POST" enctype="multipart/form-data">
                    <div class="form-group col-md-6 p-3" >
                        <div class="row m-0">
                            <div class="form-group col-md-12 " >
                                <label for="addvideo">Select Genres</label>
                                <select class="form-control selectpicker" name="sub_category" id="sub_category" data-live-search="true" required> 
                                  
                        <option value="" >---select---</option>
                                   <?php
                                if (isset($sub_caegories)) {

                                    foreach ($sub_caegories as $sub_caegory) {

                                        foreach ($categories as $category) {
                                             $cats = explode(',', $category['genres']);

                                        if (in_array($sub_caegory['id'], $cats)) {
                                        ?>
                                        <option value="<?php echo $sub_caegory['id']; ?>" >

                                        <?php 

                                        

                                           
                                             echo $sub_caegory['sub_category_name'];

                                            
                                     
                                    
                                                    ?>
                                            </option>

                                    <?php 
                                }
                            }
                                  }  } ?>


                                </select> 
                            </div>

                           <!--  <div class="form-group col-sm-3">
                                <label class="control-label" ><strong>Age Restrict(18+)</strong></label>
                                <br>
                                <center><label>
                                    <input type="checkbox"name="age_restrict" value="1" <?php
                                    if (set_value('age_restrict') == 1) {
                                        echo 'checked';
                                    }
                                    ?> >
                                </label></center>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" ><strong>Cover Image</strong></label>
                                <br>
                                <center><label>
                                    <input type="checkbox"name="is_cover" value="1" <?php
                                    if (set_value('is_cover') == 1) {
                                        echo 'checked';
                                    }
                                    ?> >
                                </label></center>
                            </div> --> 

                          

                        </div><!--DIV ROW-->


                        
                        <div class="row m-0">
                            <div class="form-group col-sm-12">
                                <label for="rlguru">Select Related Artists</label>
                                <select class="form-control input-sm m-bot15 selectpicker" name = "related_artist[]" id="rlguru" multiple="" data-live-search="true" required>


                                    <?php
                                    if (isset($form_artist_ids) && !empty($form_artist_ids)) {
                                        foreach ($guru as $artist_name) {
                                            ?>
                                            <option value="<?= $artist_name['id'] ?>" <?= (in_array($artist_name['id'], $form_artist_ids)) ? 'selected' : '' ?>><?= $artist_name['name'] ?></option>
                                            <?php
                                        }
                                    } else {
                                        foreach ($guru as $guru_name) {
                                            ?>
                                            <option value="<?= $guru_name['id'] ?>" ><?= $guru_name['name']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>        

                                </select>


                                <span class="custom-error"><?php echo form_error('related_guru'); ?></span>
                            </div>
                        </div><!--DIV ROW--> 

                        <div class="row m-0" >
                            <div class="form-group col-md-12"  >
                                <label for="addvideo"class="text-center"  >MOVIES INFORMATION</label>
                            </div>
                            <div class="form-group col-md-12"  >
                                <label for="published-date-user">Movie Title</label>
                                <input type="text" class="form-control" name = "movie_title" id="movie_title" placeholder="Enter Title" value="<?php echo set_value('movie_title'); ?>" required>
                                <span class="custom-error"><?php echo form_error('movie_title'); ?></span>
                                <br>
                            </div>
                            <div class="form-group col-md-12" >
                                <label class="col-sm-12 control-label col-sm-2">Description</label>
                                <div class="col-sm-12">
                                    <textarea placeholder="Enter Description" class="form-control " name="movie_desc" rows="6" required><?php echo set_value('movie_desc'); ?></textarea>
                                    <br>
                                </div>
                                <span class="custom-error"><?php echo form_error('video_desc'); ?></span>
                            </div>



                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                            <script>
                                function show1() {
                                    document.getElementById('div1').style.display = 'none';
                                }
                                function show2() {
                                    document.getElementById('div1').style.display = 'block';
                                }
                            </script>
                            <div class="form-group radio col-sm-12 mb-4"  >
                                <label class="control-label  pl-0" ><strong>View Mode</strong></label>
                                <input type="radio" name="movie_view" value="1" <?=(set_value('movie_view')==1)?'checked':'checked'?>>FREE
                                <input type="radio" name="movie_view" value="0" <?=(set_value('movie_view')==0)?'checked':''?>>PAID
                            </div>
                            
                           <!--  <div  class="form-group hide col-md-12" id="subscribtion_plan">
                                <label for="rlguru">Select Subscription</label>
                                <select class="form-control input-sm m-bot15 selectpicker" name = "related_sub[]" id="rlguru1" multiple="" data-live-search="true">
                                    <?php
                                    if (isset($related_subscription) && !empty($related_subscription)) {
                                        foreach ($premium_plan as $plan) {
                                            ?>
                                            <option value="<?= $plan['id'] ?>" <?= (in_array($plan['id'], $related_subscription)) ? 'selected' : '' ?>><?= $plan['plan_name'] ?></option>
                                            <?php
                                        }
                                    } else {
                                        foreach ($premium_plan as $plann) {
                                            ?>
                                            <option value="<?= $plann['id'] ?>" ><?= $plann['plan_name']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>        
                                </select>
                                <span class="custom-error"><?php echo form_error('related_guru'); ?></span>
                            </div> -->
                        </div> 

                         <div class="row m-0">
                            <label for="thumbnail_file">Movie Poster Image</label>
                            
                                <input type="file" accept=".jpg,.png,.jpeg" name = "poster_url" id="poster_file" onchange="loadFile1(event)"  required>
                                <span class="custom-error"><?php echo form_error('thumbnail_url'); ?></span>   
                                <img id="output" width="100%" height="250px" style="    background: #ffff;">
                                <div class="form-group col-md-12" ><small> Image Size -: 2408X1152px</small>
                                <script>
                                    var loadFile1 = function (event) {
                                        var output = document.getElementById('output');
                                        output.src = URL.createObjectURL(event.target.files[0]);
                                        output.onload = function () {
                                            URL.revokeObjectURL(output.src) // free memory
                                        }
                                    };
                                </script>
                            </div>
                        </div>

                    </div><!--DIV COL-->
         
                    <div class="form-group col-md-6 p-3" >
                        <div class="row m-0">
                            <input name="custom_movie_url" hidden="">
                            <div class="form-group col-md-12">
                                <label for="addvideo">Add Movie URL</label>
                                <input  type="text" class="form-control" name ="movie_url" id="movie_url" required>
                                <!-- <small>Format supported -: mp4 </small> -->
                               <!--  <button type="button" class="instant_upload_movie btn btn-xs btn-info">Instant Upload</button> -->
                                <span class="custom-error"><?php echo form_error('movie_url'); ?></span>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="addvideo" >Add Movie TYPE</label>
                                <select class="form-control input-sm m-bot15 selectpicker" name="url_type" id="url_type"  data-live-search="true" required>
                                    <option value="1">Youtube</option>
                                     <option value="2">AWS Account</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="movie_trailer">Movie Trailer URL</label>
                                <input type="text" class="form-control" name = "movie_trail" id="movie_trail" required>
                            <!--     <small>Format supported -: mp4 </small> -->
                                <span class="custom-error"><?php echo form_error('movie_trail'); ?></span>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="addvideo" >Add Movie Trailer TYPE</label>
                                <select class="form-control input-sm m-bot15 selectpicker" name="url_t_type" id="url_t_type"  data-live-search="true" required>
                                    <option value="1">Youtube</option>
                                     <option value="2">AWS Account</option>
                                </select>
                            </div>

                        </div> 
                        <div class="row m-0">
                            <label for="thumbnail_file">Movie Thumbnail Image</label>
                            <div class="form-group col-md-12" >
                                <input type="file" accept=".jpg,.png,.jpeg" name = "thumbnail_url" id="thumbnail_file" onchange="loadFile(event)" required>
                               
                                <span class="custom-error"><?php echo form_error('thumbnail_url'); ?></span>   

                                <img id="output1" width="40%" height="290px" style="    background: #ffff;"><br/>
                                 <small> Image Size -: 500X750</small>
                                <script>
                                    var loadFile = function (event) {
                                        var output = document.getElementById('output1');
                                        output.src = URL.createObjectURL(event.target.files[0]);
                                        output.onload = function () {
                                            URL.revokeObjectURL(output1.src) // free memory
                                        }
                                    };
                                </script>
                            </div> 
                        </div>
                        

                       
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <button type="submit" class="btn btn-info btn-sm">Submit</button>
                </form>
            </div>
        </section>
    </div>
    <div class="clearfix"></div>
</div>

<script>
    $(document).ready(function(){
        var view_mode=$('input[name=movie_view]').val();
        if(view_mode==2){
            $('#subscribtion_plan').removeClass('hide');
        }else{
            $('#subscribtion_plan').addClass('hide');
        }
    });
    $('input[name=movie_view]').change(function(){
        var view_mode=$(this).val();
        if(view_mode==2){
            $('#subscribtion_plan').removeClass('hide');
        }else{
            $('#subscribtion_plan').addClass('hide');
        }
    });
</script>
<script>
    $(function() {

    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('form').ajaxForm({
        beforeSend: function() {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal);
            percent.html(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal);
            percent.html(percentVal);
        },
        complete: function(xhr) {
            status.html(xhr.responseText);
        }
    });
}); 
</script>
<?php
$adminurl = AUTH_PANEL_URL;
$assetsurl = AUTH_ASSETS;
$custum_js = <<<EOD
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="{$assetsurl}/aws/aws-sdk-2.1.12.min.js?1"></script>
<script type="text/javascript" charset="utf8" src="{$assetsurl}/aws/aws-init.js?1"></script>
<script type="text/javascript" language="javascript" >
    $( "#maincategory" ).change(function() {
        id = $(this).val();
        jQuery.ajax({
            url: "$adminurl"+"video_channel/video_control/get_video_subcategory/"+id+"?return=json",
            method: 'Get',
            dataType: 'json',
            success: function (data) {
                var html = "<option value=''>--select--</option>";
                $.each( data , function( key , value ) {
                  html += "<option value='"+value.id+"'>"+value.text+"</option>";
                });
                $("#subcategory").html(html);
            }
        });
    }).change();
//////////////////////////////////////instant upload oprations///////////////////////////
    $(".instant_upload_movie").click(async function(){
        var size = upload_file_size("movie_url");
        var size = size.split(" ");
        if (parseFloat(size[0]) == 0) {
            show_toast("error", 'Choose Valid File',"Image Size Exceed. Max Size 500KB");
            $("input[name=custom_movie_url]").val("");
            return false;
        }
        let json = await s_s3_file_upload("file_library/videos/original/", $("#movie_url")[0],"custom_movie_url");
        $("#movie_url").val("");
    });
//////////////////////////////////////instant upload oprations ends///////////////////////////     
    $( function() {
        $( ".dpd1" ).datetimepicker();
        $( ".dpd2" ).datetimepicker();
    }); // datepicker closed
        
    $('#published-date-user').datetimepicker({
        format: 'd-mm-yyyy H:i:ss P',
        autoclose: true
    });

    $('#day').datetimepicker({
        format: 'd',
        autoclose: true
    });
</script>
EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
