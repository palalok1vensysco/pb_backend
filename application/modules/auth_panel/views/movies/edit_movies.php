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



<?php
$sql = "SELECT count(*) as total
FROM time_frame where status=0 ";
$total = $this->db->query($sql)->row()->total;
$total_in_text_format=convert_number_to_text($total);
$this->db->where('status', '0');
$frames = $this->db->get('time_frame')->result_array();
?>

<?php

$movies_sub_categorys = set_value('sub_category');
$form_artist_ids = set_value('artist');
$related_subscription = set_value('related_sub');
$form_day_ids = set_value('days');
?>

<div>
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading text-white bg-dark">
                EDIT MOVIES
                <a href="<?= base_url('admin-panel/list-movies'); ?>"><button class="pull-right btn btn-info btn-xs bold">Back to Movies list</button></a>
            </header>

            
            <div class="panel-body bg-white">
                <form autocomplete="off"  role="form" method="POST" enctype="multipart/form-data" id="myform">
                    <?php if (isset($video['id'])) { ?>
                    <input type="hidden" value="<?php echo $video['id']; ?>" name="id">
                <?php } ?>
                       

                    <div class="form-group col-md-6" >
                        <div class="row">
                            <div class="form-group col-md-12" >
                               <?php
                if (isset($video['movie_category']) && !empty($video['movie_category'])) {
                    ?>
                    
                        <label>Genres</label>
                        <select class="form-control selectpicker" name="sub_category" id="sub_category" data-live-search="true"  > 
                             <option value="" >---select---</option>
                                   <?php
                                if (isset($sub_caegories)) {

                                    foreach ($sub_caegories as $sub_caegory) {

                                        foreach ($categories as $category) {
                                             $cats = explode(',', $category['genres']);

                                        if (in_array($sub_caegory['id'], $cats)) {
                                        ?>
                                        <option value="<?php echo $sub_caegory['id']; ?>" <?= ($sub_caegory['id'] == $video['movie_category']) ? 'selected' : '' ?>>

                                        <?php 

                                        

                                           
                                             echo $sub_caegory['sub_category_name'];

                                            
                                     
                                    
                                                    ?>
                                            </option>

                                    <?php 
                                }
                            }
                                  }  } ?>    
                        </select> 
                    
                <?php }
                ?>  
                            </div>
                             <!--  <div class="form-group col-sm-3">
                                <label class="control-label" ><strong>Age Restriction(18+)</strong></label><br>
                                <center>
                                    <label>
                                    <input type="checkbox"name="age_restrict" value="1" <?= ( $video['age_18']=='1'?  "checked" : "") ?>>
                                </label>
                                </center>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" ><strong>Cover Image</strong></label><br>
                               <center>
                                    <label>
                                    <input type="checkbox"name="is_cover" value="1" <?= ( $video['movie_is_cover']=='1'?  "checked" : "") ?>>
                                </label>
                               </center>
                            </div> -->

                          <!--   <div class="form-group radio col-sm-6">
                                <label class="control-label" ><strong>Select Language</strong></label>
                                <br>
                                <label>
                                    <?php $lang = $video['movie_language'];
                                    ?>
                                    <input type="radio" name="movie_language"  value="1" <?php if ($lang == '1') {echo ' checked ';} ?>>
                                    Bhojpuri
                                </label>
                                <label>
                                    <input type="radio" name="movie_language" value="2" <?php if ($lang == '2') {echo ' checked ';} ?>>
                                    Maithli
                                </label>
                            </div> -->


                        </div><!--DIV ROW-->


                       <!--  <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="control-label" ><strong>Age Restriction(18+)</strong></label>
                                <label>
                                    <input type="checkbox"name="age_restrict" value="1" <?= ( $video['age_18']=='1'?  "checked" : "") ?>>
                                </label>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="control-label" ><strong>Cover Image</strong></label>
                                <label>
                                    <input type="checkbox"name="is_cover" value="1" <?= ( $video['movie_is_cover']=='1'?  "checked" : "") ?>>
                                </label>
                            </div> 
                        </div> --><!--DIV ROW-->
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="rlguru">Select Related Artists</label>
                                <select class="form-control input-sm m-bot15 selectpicker" name = "related_artist[]" id="rlguru" multiple="" data-live-search="true">


                                    <?php
                                if (isset($guru)) {
                                    foreach ($guru as $artists) {
                                        ?>
                                        <option value="<?php echo $artists['id']; ?>" <?php
                                        $cats = explode(',', $video['movie_artists']);
                                        if (in_array($artists['id'], $cats)) {
                                            echo "selected";
                                        }
                                        ?>>
                                                    <?php echo $artists['name']; ?>

                                        </option>
                                        <?php
                                    }
                                }
                                ?>       

                                </select>

                                <span class="custom-error"><?php echo form_error('related_guru'); ?></span>
                            </div>
                        </div><!--DIV ROW--> 
                        <div class="row" >
                            <div class="form-group col-md-12" >
                                <label for="addvideo"class="text-center"  >MOVIES INFORMATION</label>
                            </div>
                            <div class="form-group col-md-12"  >
                                <label for="published-date-user">Movie Title</label>
                                <input type="text" class="form-control" name = "movie_title" id="video_title" placeholder="Enter Title" value="<?php echo $video['movie_title']; ?>">
                                <span class="custom-error"><?php echo form_error('movie_title'); ?></span>
                                <br>
                            </div>
                            <div class="form-group col-md-12" >
                                <label class="col-sm-12 control-label col-sm-2">Description</label>
                                <div class="col-sm-12" >
                                    <textarea placeholder="Enter Description" class="form-control" name="movie_desc" id="video_desc" rows="6" valie="<?php echo $video['movie_description']; ?>"><?php echo $video['movie_description']; ?></textarea>
                                    <br>
                                </div>
                                <span class="custom-error"><?php echo form_error('video_desc'); ?></span>
                            </div>

                          <div class="form-group col-md-12" >
                                <label for="published-date-user">Release Date</label>
                                <div data-date-format="yyyy-mm-dd" data-date="2013/12/1">
                            <input class="form-control published-date-user" type="date" name="movie_release"  placeholder=" Select Published Date From" value="<?php  if ($video['movie_release']!="") {echo date('Y-m-d',strtotime($video['movie_release']));}
                             else {
                             }?>" required>
                        </div>
                                
                                <span class="custom-error"><?php echo form_error('published_date'); ?></span>
                            </div>

                          

                             <div class="form-group col-md-12">
                            <label for="thumbnail_file">Movie Poster</label>
                            <div class="form-group col-md-12" ><small> Image Size -: 2408X1152px</small>
                                <input type="file" accept=".jpg,.png,.jpeg" name = "poster_url" id="poster_file" onchange="loadFile1(event)">
                                <span class="custom-error"><?php echo form_error('thumbnail_url'); ?></span>   
                                <img id="output" width="100%" height="250px" style="background: #ffff;"  src="<?php echo $video['movie_poster_url']; ?>"  >
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

                          
                        </div> 

                    </div><!--DIV COL-->
                    <div class="form-group col-md-1" ></div>
                    <div class="form-group col-md-5" >
                        <div class="row">
                            <input name="custom_movie_url" hidden="">
                            <div class="form-group col-md-12">
                                <label for="addvideo">Add Movie</label>
                                <input type="text" class="form-control" name = "movie_url" id="movie_url" value="<?php echo $video['movie_url']; ?>">
                                <!-- <small>Format supported -: mp4 </small> <button type="button" class="instant_upload_movie btn btn-xs btn-info">Instant Upload</button> -->
                                <span class="custom-error"><?php echo form_error('movie_url'); ?></span>
                            </div>
                             <div class="form-group col-md-12">
                                <label for="addvideo" >Add Movie TYPE</label>
                                <select class="form-control input-sm m-bot15 selectpicker" name="url_type" id="url_type"  data-live-search="true" required>
                                    <option <?= (($video['url_type']=='1') ? 'selected="selected"': '') ?> value="1" >Youtube</option>
                                     <option <?= (($video['url_type']=='2') ? 'selected="selected"': '') ?> value="2" >AWS Account</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="movie_trailer">Movie Trailer</label>
                                <input type="text" class="form-control" name = "movie_trail" id="movie_trail" value="<?php echo $video['movie_trailer_url']; ?>">
                                <!-- <small>Format supported -: mp4 </small> -->
                                <span class="custom-error"><?php echo form_error('movie_trail'); ?></span>
                            </div>
                                <div class="form-group col-md-12">
                                <label for="addvideo" >Add Movie Trailer TYPE</label>
                                <select class="form-control input-sm m-bot15 selectpicker" name="url_t_type" id="url_t_type"  data-live-search="true" required>
                                    <option <?= (($video['url_t_type']=='2') ? 'selected="selected"': '') ?> value="1">Youtube</option>
                                     <option  <?= (($video['url_t_type']=='2') ? 'selected="selected"': '') ?> value="2">AWS Account</option>
                                </select>
                            </div>


                        </div> 
                        <div class="row">
                            <label for="thumbnail_file">Movie Thumbnail</label>
                            <div class="form-group col-md-12" >
                                <input type="file" accept=".jpg,.png,.jpeg" name = "thumbnail_url" id="thumbnail_file" onchange="loadFile(event)">
                                <small> Image Size -: 500X750</small>
                                <span class="custom-error"><?php echo form_error('thumbnail_url'); ?></span>   

                                <img id="output1" width="100%" height="250px"   src="<?php echo $video['movie_thumbnail_url']; ?>"  style=" background: #ffff;">
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

                       
                        <div class="row">
                             <div class="form-group radio col-md-12">
                                
                                <label class="control-label" ><strong>Download</strong></label>
                                <label>
                                    <input type="radio" name="movie_download" value="1"<?= ( $video['movie_download']=='1'?  "checked" : "") ?>>
                                    Yes
                                </label>
                                <label>
                                    <input type="radio" name="movie_download"  value="2" <?= ( $video['movie_download']=='2'?  "checked" : "") ?>>
                                    No
                                </label> 
                            </div>

                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                            <script type="text/javascript">
                                 
                        function yesnoCheck() {
                            
                            if (document.getElementById('yesCheck').checked) {
                                document.getElementById('ifYes').style.display = 'block';
                            }
                            else{ document.getElementById('ifYes').style.display = 'none';}

                        }

                        </script>
                            <div class="form-group radio col-sm-12">
                                <label class="control-label" ><strong>View Mode</strong></label>
                                <input type="radio" name="movie_view" value="1" onclick="javascript:yesnoCheck();" <?= ( $video['movie_view_type']=='1'?  "checked" : "") ?>>FREE
                                <input type="radio" name="movie_view"  value="0" onclick="javascript:yesnoCheck();" <?= ( $video['movie_view_type']=='0'?  "checked" : "") ?>>PAID
                            </div>
                          

                            
                        </div>
                        <!-- <div  class="form-group  col-md-12" id="time_frame">
                            <center>  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">ADD TIME FRAME</button></center>
                        </div> -->

                    </div>
                    <div class="clearfix"></div>
                    <br>
                   <center> <button type="submit" id="submit" class="btn btn-info btn-sm">Submit</button>
                    <a href="<?= base_url('admin-panel/list-movies') ?>">
                            <button class="btn btn-danger btn-sm" type="button" >Cancel</button>
                        </a>
                   </center>
                </form>
            </div>
       
               <!--  <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Time Frame</h4>
      </div>
      <div class="row">
       <div class="col-sm-12"> 

                    <center>     <table class="table table-bordered" style="width: 90%;">
                     <thead>
                         <tr>
                            
                            <th>Name</th>
                            <th>Hrs</th>
                            <th>Mins</th>
                            <th>Sec</th>
                            <th>Action</th>
                         </tr>
                    </thead>
              <tbody>
                         <?php
                          foreach($frame as $f_data){ 
                     ?>   <tr>
                                <td><?php echo $f_data['frame_type'];?></td>
                                
                                <td><?php echo $f_data['hrs'];?></td>
                                <td><?php echo $f_data['mins'];?></td>
                                <td><?php echo $f_data['sec'];?></td>
                                <td><?php $idf=$f_data['id'];?> <a class='btn-xs bold btn btn-danger' onclick="return confirm('Are you sure you want to delete?')" href='<?= AUTH_PANEL_URL . 'movies/movies/time_frame_delete/'.$idf ?>"'><i class='fa fa-trash-o'></i></a></td>

                           </tr>
                           <?php
                            }
                           ?>
                    </tbody>
            </table></center>
                    </div>
                </div>
      <form autocomplete="off"  role="form" method="POST" action="<?= AUTH_PANEL_URL . 'movies/movies/time_frame' ?>" enctype="multipart/form-data" >
      <div class="modal-body">
        <div class="row">

             <?php if (isset($video['id'])) { ?>
                    <input type="hidden" value="<?php echo $video['id']; ?>" name="id">
                <?php } ?>
            <div class="col-sm-4">

                <select class="form-control selectpicker" name="frame_type" id="frame_type" data-live-search="true" > 
                    <option value=""> ---Select--- </option>
                            <option value="Advertisement" > Advertisement</option>
                            <option value="Introduction"> Introduction</option>
                            
                        </select>
                    </div>
                  <div class="col-sm-8"  id="add_videos">
                   <div class="row">
                    <div class="col-md-6">
                        <label>Add Video</label>
                <input type="file" accept="video/mp4" name = "add_url" id="addvideo">
                     </div>
                     <div class="col-md-6">
                        <label class="control-label" ><strong>Skip Video</strong></label>
                                <label>
                                    <input type="radio" name="skip_video" value="1"<?= ( $video['skip_video']=='1'?  "checked" : "") ?>>
                                    Yes
                                </label>
                                <label>
                                    <input type="radio" name="skip_video"  value="2" <?= ( $video['skip_video']=='2'?  "checked" : "") ?>>
                                    No
                                </label> 
                     </div>
                </div>
                    </div>

                    <div class="col-sm-12"> 
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputEmail1">Hour</label>
                                        <select class="form-control input-xs" name="hours" class="form-control">
                                            <?php
                                            for ($i = 0; $i < 3; $i++) {
                                                $i = ($i < 10) ? '0' . $i : $i;
                                                ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputEmail1">Minutes</label>
                                        <select class="form-control input-xs" name="minutes" class="form-control" >
                                            <?php
                                            for ($i = 0; $i < 60; $i++) {
                                                $i = ($i < 10) ? '0' . $i : $i;
                                                ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputEmail1">Seconds</label>
                                        <select class="form-control input-xs" name="seconds" class="form-control">
                                            <?php
                                            for ($i = 0; $i < 60; $i++) {
                                                $i = ($i < 10) ? '0' . $i : $i;
                                                ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>  
                               

        </div>                

      </div>
  
      <div class="modal-footer">
        <button type="submit" class="btn btn-info btn-sm">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div> -->
        </section>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script type="text/javascript">
   
</script>
    </div>
    <div class="clearfix"></div>
</div>
<script>
    $(document).ready(function(){
        var view_mode=$('select[name=frame_type]').val();
        if(view_mode== 'Advertisement'){
            $('#add_videos').removeClass('hide');
        }else{
            $('#add_videos').addClass('hide');
        }
    });
    $('select[name=frame_type]').change(function(){
        var view_mode=$(this).val();
        if(view_mode=='Advertisement'){
            $('#add_videos').removeClass('hide');
        }else{
            $('#add_videos').addClass('hide');
        }
    });
</script>


<?php
$adminurl = AUTH_PANEL_URL;
$assetsurl = AUTH_ASSETS;
$custum_js = <<<EOD

   
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<script type="text/javascript" charset="utf8" src="{$assetsurl}/aws/aws-sdk-2.1.12.min.js?1"></script>
<script type="text/javascript" charset="utf8" src="{$assetsurl}/aws/aws-init.js?1"></script>
//          <script type="text/javascript" language="javascript" >

//  $('#published-date-user').datetimepicker({
//                                                     format: 'd-mm-yyyy H:i:ss P',
//                                                    autoclose: true
//                                                   });


        

//               </script>
              <script type="text/javascript" language="javascript">
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
              </script>

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
