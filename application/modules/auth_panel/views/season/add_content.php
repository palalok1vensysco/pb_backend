
<?php
    $ams_region = 'ap-south-1';
$ams_bucket_name = 'vc-10000097-97';
$cognito = 'ap-south-1:01cbc427-d41d-4cbc-baa6-012c6ff2f540';
?>
<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>aws/aws-sdk-2.1.12.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>aws/aws-init.php?r=<?php echo $ams_region; ?>&b=<?php echo $ams_bucket_name; ?>&i=<?php echo $cognito; ?>"></script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<link href="<?= AUTH_ASSETS ?>shaka_player/css/video-js.css" rel="stylesheet">
<script src="<?= AUTH_ASSETS ?>shaka_player/js/video.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/videojs-shaka.min.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/shaka-player.ui.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/videojs-seek-buttons.min.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/mux.min.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/custom-videojs.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/Youtube.min.js"></script>
<div class="col-md-3 no-padding">
    <section class="panel">
        <div class="panel-body">
           
            <ul class="nav prod-cat couponBox">
                <?php
                    if(empty($id)){
                        $id = "";
                    }
                ?>
                <li class="<?php if((empty($_GET['season_id']) && empty($_GET['season'])) || (!empty($shows['skip_season']))) { echo "active";}?>"><a href="<?php echo site_url('admin-panel/add-content/' . $id);?>"  data-div="1"><i class=" fa fa-angle-right"></i> Content</a></li>   
                <?php
                    if(!empty($id) && empty($shows['skip_season'])) {
                ?>                              
                <li class="<?php if(empty($id)) { echo 'disabled'; } ?> <?php if(!empty($_GET['season_id']) || !empty($_GET['season'])){ echo "active";}?>"><a href="<?php echo site_url('admin-panel/add-content/' . $id . "?season=true");?>"  <?php if(isset($id)) { echo 'data-div="2"'; }?>><i class=" fa fa-angle-right"></i> Season</a></li>                   
                <?php 
                    }
                    if(isset($seasons) && empty($shows['skip_season'])) {
                ?>
                    <ul class="ml-3">
                <?php
                        foreach($seasons as $season)
                        {   
                ?>
                        <li class="<?php if(!empty($_GET['season_id']) && $_GET['season_id'] == $season['id']){ echo "active";}?>"><a class="subSection" href="<?php echo site_url('admin-panel/add-content/' . $id .'?season_id='. $season['id']);?>" data-id="<?php echo $season['id'];?>" data-div="2"><i class=" fa fa-angle-right"></i> <?= $season['title']; ?></a></li>
                    <?php
                        }
                ?>
                    </ul>
                <?php
                    }if(!empty($id)){
                ?>
                <li class=""><a href="#" data-div="4"><i class=" fa fa-angle-right"></i> Artist</a></li>
                <?php } ?> 
            </ul>
        </div>
    </section>
</div>
<div class="col-md-9 pr-0">
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header" style="background: #aeb2b7;">
                    <span class="title_chk_prev"></span>
                    <button aria-hidden="true" class="close" onclick="player_stop_trai()" type="button" style="color: white;">×</button>
                </div>
                <div class="modal-body">
                    <div class="panel-body add_file_element_trail" id="add_file_element_trail">
                        <video width="520" autoplay  controls preload="auto" height="400" id="video_player_aws_new_trail" class="video-js vjs-default-skin" ></video>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

    <div id="tabContent1" class="tabu" <?php if( ((!empty($_GET['season_id']) && empty($shows['skip_season'])) || !empty($_GET['season']))) { ?> style="display: none;" <?php } ?> >
        <section class="panel" >
            <header class="panel-heading displa_flex align-items-center common_collapse bg-dark" data-id="section_content" >
                <?php if(empty($id)) {  echo "Add"; }else{ echo "Edit";} ?> Content
            </header>
            <div class="panel-body section_content"  <?php if(!empty($edit_video)){ echo 'style="display:none;"'; }?>>
                <form   form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                    <div class="">
                        <div class="form-group col-md-6">
                            <label for="cat_name">Media Type <span style="color:#ff0000">*</span></label>
                            <select name="type" id="type" class="form-control selectpicker" required>
                                <option value="">select Media Type</option>
                                <option <?php if(!empty($id)) { if($shows['type'] == 0) { echo 'selected'; } }?> value="0">Video</option>
                                <option <?php if(!empty($id)) { if($shows['type'] == 1) { echo 'selected'; } }?> value="1">Audio</option>
                               
                            </select>                    
                        </div>    
                        <div class="form-group col-md-6">
                            <label for="cat_name">Category <span style="color:#ff0000">*</span></label>
                            <select name="category_id" id="category_id" class="form-control selectpicker" required>
                                <option value="">select category</option>
                                <?php foreach($categories as $category){?>
                                    <option <?php if(!empty($id)) { if($shows['category_id'] == $category['id']) { echo 'selected'; } }?> value="<?= $category['id'] ?>"><?= $category['title']; ?></option>
                                <?php } ?>
                            </select>                    
                        </div>                 
                        <div class="form-group col-md-6">
                            <label for="cat_name">Aggregator <span style="color:#ff0000">*</span></label>
                            <select name="aggregator_id" id="" class="form-control selectpicker" required>
                                <option value="">select aggregator</option>
                                <?php foreach($aggregators as $aggregator){?>
                                    <option <?php if(!empty($id)) { if($shows['aggregator_id'] == $aggregator['id']) { echo 'selected'; } }?>  value="<?= $aggregator['id'] ?>"><?= $aggregator['title']; ?></option>
                                <?php } ?> 
                            </select>                    
                        </div>                 
                        <div class="form-group col-md-6">
                            <label for="cat_name">Title <span style="color:#ff0000">*</span></label>
                            <input type="text" name="title"   id = 'cate' required class="form-control input-md " maxlength='100' oninput="checkpricee(this)" placeholder="Enter Title Name" value="<?php if(!empty($id)) { echo $shows['title']; } ?>">                 
                        </div> 
                          
                        <div class="form-group col-md-6">
                            <label for="cat_name">Release Year<span style="color:#ff0000">*</span></label>
                                <input type="text" value="<?php if(!empty($shows['released_on'])) { echo $shows['released_on']; } ?>" name="released_on" id="released_on" class="form-control date-own">
                        </div>
                        <div class="form-group col-md-6 ">
                            <label class="genres" for="genres_id">Genres <span style="color:#ff0000">*</span></label>
                            <select name="genres_id[]" required id="genres_id" class="form-control selectpicker2" multiple>
                                <!-- <option value="">----select----</option> -->
                                <?php
                                    if(!empty($genres)){
                                        foreach($genres as $genre){
                                ?>
                                    <option <?php if(!empty($shows['genres_id']) && in_array($genre['id'], explode(',', $shows['genres_id']))){ echo "selected"; } ?> value="<?php echo $genre['id'];?>"><?php echo $genre['title'];?></option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                        </div> 
                       
                        <div class="form-group col-md-6">   
                        <label for="name">Video Time<span style="color:#ff0000">*</span></label>    
                            <input required type="number" value="<?php if(isset($shows['video_time'])) { echo $shows['video_time']; }?>" class="form-control " id="video_time" name="video_time" placeholder="Enter video time">
                        </div> 
                        <div class="clearfix" ></div>
                        

                        <div class="form-group col-md-6" >                
                            <div class="col-md-6">
                            <label for="cat_name">Thumbnail <span style="color:#ff0000">*</span></label>
                            <small>
                                <p>               
                                <strong>Image Type :</strong> jpg, jpeg, gif, png
                                ,<strong>Width  :</strong>540 pixels
                                , <strong>Height :</strong>720 pixels
                                </p>
                            </small>
                                <input accept="image/*" type="file" id="thumbnail_content" <?php if(empty($id)) { echo "required"; } ?> name="image" class="form-control-file border check_image_aspect_ratio" data-ratio="0" data-height="720" data-preview="profile-image" data-width="540">   
                                <span class="custom-error"><?php echo form_error('thumbnail_content'); ?></span>                         
                            </div>
                            <div class="col-md-6 contentManagementAddImg">
                                <img  height="100" width="100" src="<?php if(!empty($id)) { echo $shows['thumbnail']; } ?>" id="profile-image" style=""alt="Please Chose Imgage">
                            </div>                
                        </div>
                        <div class="form-group col-md-6" >                
                            <div class="col-md-6">
                            <label for="cat_name">Poster <span style="color:#ff0000">*</span></label>
                            <small>
                                <p>               
                                <strong>Image Type :</strong> jpg, jpeg, gif, png
                                ,<strong>Width  :</strong>720 pixels
                                , <strong>Height :</strong>420 pixels
                                </p>
                            </small>
                                <input accept="image/*" type="file" <?php if(empty($id)) { echo "required"; } ?> id="" name="poster_url" class="form-control-file border d-none check_image_aspect_ratio" data-ratio="0" data-height="420" data-preview="poster-image" data-width="720">                            
                            </div>
                            <div class="col-md-6 contentManagementAddImg">
                                <img  height="100" width="100" src="<?php if(!empty($id)) { echo $shows['poster_url']; } ?>" id="poster-image" style=""alt="Please Chose Imgage">
                            </div>      
                            <span class="custom-error"><?php echo form_error('poster_url'); ?></span>
                        </div>
                        <div class="form-group col-md-6" >                
                            <div class="col-md-6">
                            <label for="cat_name">Title Logo<span style="color:#ff0000">*</span></label>
                            <small>
                                <p>               
                                <strong>Image Type :</strong> jpg, jpeg, gif, png
                                ,<strong>Width  :</strong>540 pixels
                                , <strong>Height :</strong>720 pixels
                                </p>
                            </small>
                                <input accept="image/*" type="file" id="banner_icon_content" <?php if(empty($id)) { echo "required"; } ?> name="banner_icon" class="form-control-file border">                            
                            </div>
                            <div class="col-md-6 contentManagementAddImg">
                                <img  height="100" width="100" src="<?php if(!empty($id)) { echo $shows['banner_icon']; } ?>" id="banner-icon-image" style=""alt="Please Chose Imgage">
                            </div>   
                            <span class="custom-error"><?php echo form_error('banner_icon'); ?></span>             
                        </div>

                        <div class="form-group col-md-6" >                
                            <div class="col-md-6">
                            <label for="cat_name">Detail Banner<span style="color:#ff0000">*</span></label>
                            <small>
                                <p>               
                                <strong>Image Type :</strong> jpg, jpeg, gif, png
                                ,<strong>Width  :</strong>1920 pixels
                                , <strong>Height :</strong>1080 pixels
                                </p>
                            </small>
                                <input accept="image/*" type="file" id="detail_banner_content" <?php if(empty($id)) { echo "required"; } ?> name="detail_banner" class="form-control-file border check_image_aspect_ratio" data-ratio="0" data-height="1080" data-preview="detail-banner" data-width="1920">                            
                            </div>
                            <div class="col-md-6 contentManagementAddImg">
                                <img  height="100" width="100" src="<?php if(!empty($id)) { echo $shows['detail_banner']; } ?>" id="detail-banner" style=" "alt="Please Chose Imgage">
                            </div>  
                            <span class="custom-error"><?php echo form_error('detail_banner'); ?></span>              
                        </div>

                        <div class="form-group col-md-6 skip_season"  style="height:58px;" >                    
                            <input type="checkbox" <?php if(!empty($shows['skip_season'])){ echo "checked"; } ?> id="skip_season" name="skip_season" value="1">
                            <label>Skip Season</label>
                        </div>
                        <div class="form-group col-md-6 still_live"  style="height:58px;" >
                            
                            <input type="checkbox" id="still_live" <?php if(!empty($shows['still_live'])) { echo "checked"; }?> name="still_live" value="1">
                            <label for="still_live">Still Live</label>
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label for="cat_name">Description <span style="color:#ff0000">*</span></label>
                            <textarea name="description"   id = 'description'  class="form-control" placeholder="Description"><?php if(!empty($id)) { echo $shows['description']; } ?></textarea>
                        </div>              
                    </div>
                    
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-sm display_color text-white f-600 mt-2">Submit</button>  
                    </div>
                </form>
            </div>
        </section>
    </div>
<?php
    if(!empty($id)){
        if((!empty($_GET['season']) || !empty($_GET['season_id'])) && empty($shows['skip_season'])) {
?>

    <div id="tabContent2" class="tabu active">
        <section class="panel add_user_sec common_user_section" >
            <header class="panel-heading displa_flex align-items-center common_collapse bg-dark" data-id="section_season">
                <?php if(empty($specific_season)) {  echo "Add"; }else{ echo "Edit";} ?> Season
                <?php
                    if(!empty($_GET['season_id'])){
                ?>
                 <a href="<?php echo site_url('auth_panel/contentManagement/ContentManagementController/delete_season/' . $id . '?season_id=' . $_GET['season_id']); ?>" class="btn btn-info delete_data_chk" onclick="return confirm('Are you sure you want to delete?')" ><i class="fa fa-trash-o"></i></a>
                <?php
                    }
                ?>
            </header>
            <div class="panel-body section_season">
                <div class="row">
                    <?php
                        if(!empty($specific_season)) {
                            $url = base_url('admin-panel/add-season/' . $id . "?season_id=" . $specific_season['id']);
                        }else{
                            $url = base_url('admin-panel/add-season/' . $id);
                        }
                    ?>
                    <form role="form" method="POST" enctype="multipart/form-data" id="episode_form" action="<?= $url; ?>">

                    <div class="form-group col-md-6">   
                    <label for="name">Title<span style="color:#ff0000">*</span></label>    
                        <input type="text" required value="<?php if(isset($specific_season['title'])) { echo $specific_season['title']; }?>" class="form-control " id="title" name="title" placeholder="Enter Season Title" value="">
                        <input type="hidden" id="show_id" name="show_id"/>                        
                    </div> 
                    <div class="form-group col-md-6">   
                        <?php 
                            if(isset($specific_season['thumbnail'])) { 
                        ?>
                            <img src="<?php echo $specific_season['thumbnail'];?>" style="height: 100px;">
                        <?php
                            }
                        ?>
                    <label for="thumbnail_season">Thumbnail<span style="color:#ff0000">*</span></label>    
                        <input <?php if(!isset($specific_season['title'])) { echo "required"; }?> type="file" class="form-control" accept="image/*" value="" id="thumbnail_season"
                        name="thumbnail_season">
                    </div>
                     <div class="form-group col-md-6">
                        <label for="cat_name">Status<span style="color:#ff0000">*</span></label>
                        <select required class="form-control input-md m-bot15 status" name="status" >
                        <option value="0">Enable</option>
                          <option <?php if(!empty($specific_season['status'])) { echo "selected"; }?> value="1">Disable</option>
                          
                        </select>
                        <span class="custom-error"><?php echo form_error('status'); ?></span>
                    </div>
                    <div class="form-group col-md-12">
                        <button class="btn btn-info btn-sm add_user">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </section>
       
    </div>
<?php } ?>
    <div id="tabContent3" class="tabu ">
        <?php
            if(!empty($specific_season['id'])){
        ?>
       <section class="panel">
        <header class="panel-heading displa_flex align-items-center common_collapse bg-dark" data-id="edt_season">
            <?php if(empty($edit_video)) { echo "Add"; }else{ echo "Edit";}?> Media 
        </header>
        <div class="panel-body edt_season" <?php if(empty($edit_video)){ echo 'style="display:none;"'; }?>>
            <?php
                if(!empty($edit_video)) {
                    $action_url = site_url('auth_panel/file_manager/library/add_video/'. $id .'/' . $edit_video['id'] .'/?season_id=' . $specific_season['id'] );
                }else{
                    $action_url = site_url('auth_panel/file_manager/library/add_video/'. $id .'/?season_id=' . $specific_season['id'] );
                }
            ?>
            <form role="form"  method="post" enctype="multipart/form-data" action="<?php echo $action_url;?>" id="add_video" autocomplete="off" >
                <div class="form-group h-64 col-md-6 hide">
                    <label for="media_type">Video Type <span style="color:#ff0000">*</span></label>
                    <select class="form-control input-xs" name="media_type" required>
                        <option value="">Select Video Type</option>
                        <option <?php if(!empty($id)) { if($shows['type'] == 0) { echo 'selected'; } }?>  value="0">Video</option>
                        <option <?php if(!empty($id)) { if($shows['type'] == 1) { echo 'selected'; } }?>  value="1">Audio</option>
                    </select>
                </div>

                                 
             
                    <div class="form-group h-64 col-md-6 by_method hide">
                        <label>Upload Methods<span style="color:#ff0000">*</span></label>
                        <select name="by_method" required class="form-control input-xs" id="by_method">
                            <option value="">Choose Video Upload Methods</option>
                            <option <?php if(!empty($edit_video['by_method']) && $edit_video['by_method'] == 1){ echo "selected"; }?> value="1">By Mp4</option>
                            <option <?php if(!empty($edit_video['by_method']) && $edit_video['by_method'] == 2){ echo "selected"; }?> selected value="2">By Vdc Id</option>
                        </select>
                    </div>
                       
                <div class="form-group col-md-6 videocript video-nput-2 <?php if(!empty($id) && $shows['type'] == 1){ echo "hide";} ?>" >    
                    <label >Content Type <span style="color:#ff0000">*</span></label>
                   <select class="form-control content_type" required name="content_type">
                       <option value="">-- select content type --</option>
                       <option <?php if(isset($edit_video['is_live']) && $edit_video['is_live'] == 0) { echo "selected"; }else{ echo "selected"; } ?> value="0">VOD</option>
                       <option <?php if(isset($edit_video['is_live']) && $edit_video['is_live'] == 1) { echo "selected"; } ?> value="1">Live</option>
                   </select>
                </div> 
                <div class="form-group col-md-6 videocript channel_id_select video-nput-2 <?php if((isset($edit_video['is_live']) && $edit_video['is_live'] == 0) || empty($edit_video)) { echo 'hide'; }?>" >    
                    <label >Select Channel <span style="color:#ff0000">*</span></label>
                   <select class="form-control channel_id" name="channel_id" id="channel_id">
                       <option value="">-- select channel --</option>
                       <?php
                            if(!empty(($channels))){
                                foreach($channels as $channel){
                        ?>
                            <option <?php if(!empty($edit_video['channel_id'])) { if($edit_video['channel_id'] == $channel['id']) { echo 'selected'; } }?> value="<?php echo $channel['id'];?>"><?php echo $channel['channel_name'];?></option>
                        <?php
                                 }
                            }
                       ?>
                   </select>
                </div>
                <div class="form-group col-md-6 videocript vdc_id_input video-nput-2 <?php if((isset($edit_video['is_live']) && $edit_video['is_live'] == 0 && $shows['type'] == 1) || empty($edit_video)) { echo 'hide'; }?>" >    
                    <label >VideoCrypt ID <span style="color:#ff0000">*</span></label>
                    <input type="text" placeholder="Enter ID Here" <?php if(!empty($id)) { if($shows['type'] == 0) { echo 'required'; } }?> value="<?php if(!empty($edit_video['vdc_id'])){ echo $edit_video['vdc_id']; }?>" name = "videocript_id" id="videocript_id" class="form-control input-xs vid-wid" >                    
                    <button class="btn btn-success btn-xs" id="video_search" type="button" onclick="player_stop()"><i class="fa fa-search"></i></button>
                </div>     
                <div class="form-group col-md-6 videocript video-nput-2 <?php if(!empty($id)) { if($shows['type'] == 0) { echo 'hide'; } }?>" >    
                    <label >File Url <span style="color:#ff0000">*</span></label>
                    <input type="text" <?php if(!empty($id)) { if($shows['type'] == 1) { echo 'required'; } }?> placeholder="Enter Url Here" value="<?php if(!empty($edit_video['file_url'])){ echo $edit_video['file_url']; }?>" name = "file_url" id="file_url" class="form-control input-xs vid-wid" >                    
                </div>
                         <div class="form-group col-md-6 video_upload" style="display:none">
                        <label>Choose video<span style="color:#ff0000">*</span>
                            <button class="btn btn-danger btn-xs instant_upload" type="button" id="video_upload_button">Instant upload</button></label>
                        <input type="file" accept="video/mp4" name="video_upload" class="form-control input-xs" id="video_upload">
                        <span class="error bold"><?php echo form_error('video_upload'); ?></span>
                    </div>
                 <div class="form-group h-64 col-md-6 bitrat" style="display:none">
                        <label>Bitrates<span style="color:#ff0000">*</span></label>


                        <select class="selectpicker bitratSe" name="bitrat[]" id="bitrat" multiple aria-label="Default select example" placeholder="Choose Bitrate" data-live-search="false">
                            <option value="240p30">240 px</option>
                            <option value="360p30">360 px</option>
                            <option value="480p30">480 px</option>
                            <option value="720p30">720 px</option>
                            <option value="1080p30">1080 px</option>
                        </select>

                    </div>
                 

               

                <div class="form-group col-md-6 ">
                    <label >Media Title <span style="color:#ff0000">*</span></label>
                    <input type="text" value="<?php if(!empty($edit_video['title'])){ echo $edit_video['title']; }?>" placeholder="Enter Title Here" name = "title" id="title"  class="form-control input-xs" >
                    <span class="error bold"><?php echo form_error('title'); ?></span>
                </div>
                
                    <div class="form-group col-md-6 <?php if(!empty($shows['skip_season'])){ echo "hide"; }?>">
                        <label >Episode <span style="color:#ff0000">*</span></label>
                        <input type="number" value="<?php if(!empty($edit_video['episode'])){ echo $edit_video['episode']; }?>" placeholder="Enter episode Here" name = "episode" id="episode"  class="form-control input-xs"  required>
                        <span class="error bold"><?php echo form_error('episode'); ?></span>
                    </div>

                <div class="form-group col-md-6 is_trailer trailer_chk"  style="height:58px;" >
                    <input type="checkbox" <?php if(!empty($edit_video['is_trailer'])){ echo "checked"; } ?> id="is_trailer" name="is_trailer" value="1" >
                    <label>Trailer</label>
                </div>
                <div class="form-group col-md-6 hide  ">
                    <label >Video Title transcribe <span style="color:#ff0000">*</span></label>
                    <input type="" placeholder="Enter Title Here" name = "transcribe"  id="transcribe"  class="form-control input-xs" >
                    <span class="error bold"><?php echo form_error('title'); ?></span>
                </div>
                <div class="form-group col-md-6 video_tailer_title">
                    <label >Video Trailer Title</label>
                    <input type="text" placeholder="Enter Title Here" name = "title_tail" id="title_tail" class="form-control input-xs" >
                    <span class="error bold"><?php echo form_error('title_tail'); ?></span>
                </div>
                <div class="form-group col-md-6 live_start_date" style="display:none">
                    <label>Start Date</label>
                    <input name="start_date" id="start_date" value="" placeholder="Select live start date Time" class="form-control input-xs ">
                </div>
                <div class="form-group col-md-6 upload_video_cls vod_url" style="display:none">
                    <label class="file_title">Upload Video </label><button class="btn btn-info btn-xs cover_video" type="button">Url</button>&nbsp;&nbsp;
                    <input type="input" accept="video/mp4" name ="video_file" id="video_file" placeholder="Enter Video URL" class="form-control input-xs" >
                 </div>
                 <div class="form-group col-md-6 video_file_dash hide"  style="display:none">
                    <label >DASH Url</label>
                    <input type="input" accept="video/mp4" name ="video_file_dash" id="video_file_dash" placeholder="Enter dash Video URL" class="form-control input-xs" >
                </div>
                <div class="form-group col-md-6 play_via hide" style="display:none">
                    <label>Play Via</label>
                    <select name="play_via" class="form-control input-xs">
                        <option value="0">HLS</option>
                        <option value="1">DASH</option>
                    </select>
                </div>
                <div class="form-group col-md-6 upload_video_cls_teil vod_url hide" style="display:none">
                    <label class="file_title">Upload Video Trailer </label><button class="btn btn-info btn-xs cover_video" type="button">Url</button>&nbsp;&nbsp;
                    <input type="input" accept="video/mp4" name ="video_file_tail" id="video_file_tail" placeholder="Enter Video URL" class="form-control input-xs" >
                 </div>
                 <div class="form-group col-md-6 video_file_dash hide"  style="display:none">
                    <label >DASH Url</label>
                    <input type="input" accept="video/mp4" name ="video_file_dash" id="video_file_dash" placeholder="Enter dash Video URL" class="form-control input-xs" >
                </div>
                <div class="form-group col-md-6 playtime enter_class hide">
                    <label >Enter Video Duration(in Minutes)</label>
                    <input type="text" placeholder="Enter playtime in min." maxlength="5" name = "playtime" class="form-control input-xs">
                    <span class="error bold"><?php echo form_error('playtime'); ?></span>
                </div>
                <div class="form-group col-md-6 playtime_tail hide" style="display:none">
                    <label >Enter Video Trailer Duration(in Minutes)</label>
                    <input type="text" placeholder="Enter playtime in min." maxlength="5" name = "playtime_tail" class="form-control input-xs">
                    <span class="error bold"><?php echo form_error('playtime'); ?></span>
                </div>
                          
                <div class="form-group col-md-6 season_number">
                    <label for="exampleInputEmail1">WebSeries Season Number</label>
                <input class="form-control" name="season_type" placeholder="Enter Season" oninput="checkprice(this)" onkeydown="return (event.ctrlKey || event.altKey
                            || (47 < event.keyCode && event.keyCode < 58 && event.shiftKey == false)
                            || (95 < event.keyCode && event.keyCode < 106)
                            || (event.keyCode == 8) || (event.keyCode == 9)
                            || (event.keyCode > 34 && event.keyCode < 40)
                            || (event.keyCode == 46))" maxlength="5" >
                </div>
         
                <div class="form-group col-md-6" style="display: none;">
                    <label>Is VOD <span class="error">*M3U8</span></label>
                    <select class="form-control input-xs" name="is_vod">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="form-group col-md-6 is_drm" style="display:none" >
                    <label>Enable DRM Protection</label>
                    <select class="form-control input-xs" name="is_drm_protected">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

                <div class="form-group col-md-6 drm_hls_url hide" style="display:none">
                    <label >DRM Hls Url</label>
                    <input type="input" accept="video/mp4" name ="drm_hls_url" id="drm_hls_url" placeholder="Enter DRM DASH Video URL" class="form-control input-xs" >
                </div>
                <div class="form-group col-md-6 drm_dash_url hide"  style="display:none">
                    <label >DRM Dash Url</label>
                    <input type="input" accept="video/mp4" name ="drm_dash_url" id="drm_dash_url" placeholder="Enter DRM HLS Video URL" class="form-control input-xs" >
                </div>

                <div class="form-group col-md-6 vod_vtt hide">
                    <label >Vod vtt Url</label>
                    <input type="input" accept="video/mp4" name ="vod_vtt" id="vod_vtt" placeholder="Enter vod vtt URL" class="form-control input-xs" >
                </div>
                <div class="form-group col-md-6 vod_srt hide">
                    <label >Vod srt Url</label>
                    <input type="input" accept="video/mp4" name ="vod_srt" id="vod_srt" placeholder="Enter vod srt URL" class="form-control input-xs" >
                </div>

                <div class="form-group col-md-6 video_thumbnail">
                    <label>Video Portrait <span style="color:#ff0000">*</span>
                        <?php
                            if(!empty($edit_video['thumbnail_url'])){
                        ?>
                        <img src="<?php echo $edit_video['thumbnail_url'];?>" style="height: 100px;">
                        <?php
                            }
                        ?>
                    </label>
                    <small>
                        <p>               
                        <strong>Image Type :</strong> jpg, jpeg, gif, png
                        ,<strong>Width  :</strong>540 pixels
                        , <strong>Height :</strong>720 pixels
                        </p>
                    </small>               
                     
                    <input type="file" accept="image/*" name = "thumbnail" id="thumbnailInputFile1" class="form-control input-xs" placeholder="Enter Thumbnail Url" <?php if(empty($edit_video)){ 'required=""'; }?>>
                    <span class="error bold"><?php echo form_error('thumbnail'); ?></span>
                    <?php if(empty($edit_video)){ ?>
                        <span id="thumbnailmsg" style="color: red;"></span>
                    <?php } ?>
                </div>
                <div class="form-group col-md-6 video">
                    <label>Video Landscape <span style="color:#ff0000">*</span> 
                         <?php
                            if(!empty($edit_video['poster_url'])){
                          ?>
                            <img src="<?php echo $edit_video['poster_url'];?>" style="height: 100px;">
                            <?php
                                }
                            ?>
                    </label>
                       <p> 
                        <small>              
                        <strong>Image Type :</strong> jpg, jpeg, gif, png
                        ,<strong>Width  :</strong> 720 pixels
                        , <strong>Height :</strong> 420 pixels
                        </small> 
                        </p>
                    <input type="file" accept="image/*" name = "poster" class="form-control input-xs" id="posterInputFile1" placeholder="Enter Poster Url" <?php if(empty($edit_video)){ 'required=""'; }?>>
                    <span class="error bold"><?php echo form_error('poster'); ?></span>
                    <?php if(!empty($edit_video)){ ?>
                        <span id="postermsg" style="color: red;"></span>
                    <?php } ?>
                </div>
              
                   <div class="form-group col-sm-6 view_mode hide"  >
                    <label class="control-label  pl-0" ><strong>View Mode</strong></label>
                    <select name="movie_view" class="form-control input-xs">
                        <option value="0">FREE</option>
                        <option value="1">PAID</option> 
                        <?php if(isset($f_lists->paid) && $f_lists->paid == 1 ){ ?>
                        <option value="1">PAID</option>
                        <?php } ?>
                    </select>
                    
                </div>
                
                <div class="form-group col-md-6 live_end_date" style="display:none">
                    <label>End Date</label>
                    <input name="end_date" id="end_date" value="" placeholder="Select live end date Time" class="form-control input-xs ">
                </div>
                
                
                    <div class="form-group col-md-6 skip_intro <?php if(!empty($shows['skip_season'])){ echo "hide"; }?>"  style="height:58px;" >
                        
                        <input type="checkbox" <?php if(!empty($edit_video['skip_intro'])){ echo "checked"; } ?> id="skip_intro" name="skip_intro" value="1" onclick="skipIntro()">
                        <label>Skip Intro</label>
                    </div>
                    
                    
                     <div class="form-group col-md-6 seconds <?php if(!empty($shows['skip_season'])){ echo "hide"; }?>" style="<?php if(empty($edit_video['skip_intro'])){ echo "display:none"; } ?>" >
                    <label>Seconds</label> 
                        <input type="text" id="skip_time" class="form-control" maxlength="3" name="skip_time" min="1" value="<?php if(!empty($edit_video['skip_intro'])){ echo $edit_video['skip_time']; } ?>" id='skip' oninput="checkpriceee(this)" onkeydown="return (event.ctrlKey || event.altKey
                                || (47 < event.keyCode && event.keyCode < 58 && event.shiftKey == false)
                                || (95 < event.keyCode && event.keyCode < 106)
                                || (event.keyCode == 8) || (event.keyCode == 9)
                                || (event.keyCode > 34 && event.keyCode < 40)
                                || (event.keyCode == 46))">
                       
                    </div>
             
                <div class="form-group col-md-6 released_date">
                    <label for="published-date-user">Release Year <span style="color:#ff0000">*</span></label>
                    <div data-date-format="yyyy-mm-dd" data-date="2013/12/1">
                        <input class="form-control published-date-user date-own" type="text"  name="published_date" value="<?php if(!empty($edit_video['published_date'])){ echo $edit_video['published_date']; }else{ echo set_value('published_date'); }?>" id="published-date-user" placeholder=" Release Year" >
                    </div>
                    <span class="text-danger"><?php echo form_error('published_date'); ?></span>
                </div>

                

                <div class="form-group col-md-12" >
                    
                    <label class="col-sm-12 control-label col-sm-2">Description<span style="color:#ff0000">*</span></label>
                    <div class="col-sm-12">
                        <textarea placeholder=" Please Enter Description only 300 words" class="form-control " name="description" rows="6" maxlength='300' required><?php if(!empty($edit_video['description'])){ echo $edit_video['description']; }?></textarea>
                        <br>
                    </div>
                    <span class="custom-error"><?php echo form_error('video_desc'); ?></span>
                </div>

                  

                <div class="form-group col-md-6 multiplier" style="display:none">
                    <label>Multiplier</label>
                    <input type="text"  name = "multiplayer" class="form-control input-xs" >
                    <span class="error bold"><?php echo form_error('multiplayer'); ?></span>
                </div>
                <?php if(isset($f_lists->feedback_video) && $f_lists->feedback_video == 1) {?>
                    <div class="form-group col-md-6 feedback">
                        <label>Feedback</label>
                        <select name="feedback" class="form-control input-xs">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                <?php }?>
                
                <?php if(isset($f_lists->Demo_Percentage) && $f_lists->Demo_Percentage == 1) {?>
                <div class="form-group col-md-6 demo_percent">
                    <label >Demo video Percentage</label>
                    <input type="text" placeholder="Enter demo percentage" name = "demo_percent" id="demo_percent" class="form-control input-xs">
                    <span class="error bold"><?php echo form_error('demo_percent'); ?></span>
                </div>
            <?php } ?>
                <div class="form-group col-md-6 floating_number hide">
                    <label >Floating Thumbnail</label>                  
                    <select name="floating_number" id="floating_number" class="form-control input-xs">
                        <option value="0">none</option>
                        <option value="1">email</option>
                        <option value="2">mobile</option>

                    </select>
                </div>
                <div class="form-group col-md-6 video_type_file hide">
                    <label>Play mode</label>
                    <select name="video_type_file" id="video_type_file" class="form-control input-xs">
                        <option value="">----select----</option>
                        <option value="Online">Online</option>
                        <option value="Offline">Offline</option>
                        <option value="Both">Both</option>

                    </select>
                </div>

               

              <?php if(isset($f_lists->vod_token) && $f_lists->vod_token == 1){ ?>
                <div class="form-group col-md-6 videotoken" style="display:none">
                    <label class="check_material">Video token</label>
                    <input type="checkbox"  value="1" name = "videotoken" id="videotoken">
                    <span class="error bold"><?php echo form_error('videotoken'); ?></span>
                </div>
                <?php }?>
                    <?php if(isset($f_lists->vod_chat) && $f_lists->vod_chat == 1) {?>
                   <div class="form-group col-md-6 vod_chat">
                    <label class="check_material">vod chat</label>
                   <!--  <input type="checkbox" placeholder="Enter vod chat" name = "vod_chat" id="vod_chat" class="form-control input-xs"> -->

                    <input type="checkbox" name="vod_chat" value="1">
                    <span class="error bold"><?php echo form_error('vod_chat'); ?></span>
                </div>

                <?php }?>
                <div class="form-group col-md-6">
                    <button class="btn btn-xs display_color f-600 text-white"  type="submit" >Upload</button>
                    <button class="btn btn-xs display_color f-600 text-white"  type="button" onclick="$('.add_file_element').hide('slow');" >Cancel</button>
                    <!-- <button type="button" class="btn btn-xs resetvideo display_color">Clear</button> -->
                </div>
            </form>

        </div>
    </section>

        <section class="panel">
             <header class="panel-heading displa_flex align-items-center common_collapse bg-dark" data-id="video_list_chk">
                Media List
            </header>
            
            <div class="panel-body video_list_chk">
                <div class="col-md-12">
                    <table class="display table table-bordered table-striped" id="all-user-grid">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Title </th>
                                <th>Status </th>
                                <th>Creation Date </th>
                                <th>Action </th>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <th></th>
                                <th><input type="text" data-column="1" class="search-input-text form-control input-xs"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    <?php } ?>
    </div>
<?php
    } if(!empty($id)){
?>
   
    <div id="tabContent4" class="tabu" style="display: none;">
        <section class="panel add_user_sec common_user_section" >
            <header class="panel-heading displa_flex align-items-center common_collapse bg-dark" data-id="artist_section_data">
                Artist
            </header>
            <div class="panel-body artist_section_data">
                
                    <div class="">
                        <form action="<?php echo site_url('auth_panel/contentManagement/ContentManagementController/addArtistWithRelation/' . $id);?>" class="add_artist_in_shows">
                            <?php
                                if(!empty($show_artists_relation)){
                                    foreach($show_artists_relation as $key_data => $show_artists){
                            ?>
                                <div class="artist_section">
                                <div class="section_artist">
                                     <div class="form-group col-md-5 artists_type_chk">
                                        <label for="artists_type_id">Artist Type<span style="color:#ff0000">*</span></label>
                                        <select name="artists_type_id" data-sequense='<?php echo $key_data+1;?>' id="artists_type_id" class="form-control">
                                            <option value="">----select----</option>
                                            <?php
                                                if(!empty($artists_types)){
                                                    foreach($artists_types as $artists_type){
                                            ?>
                                                <option <?php if($artists_type['id'] == $show_artists['artists_type_id']) { echo "selected"; }?> value="<?php echo $artists_type['id'];?>"><?php echo $artists_type['title'];?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>  
                                    <div class="form-group col-md-6 artists_chk">
                                        <label for="artist_id">Artist <span style="color:#ff0000">*</span></label>
                                        <select name="artist_id" id="artist_id" class="form-control select_2_chk selectpicker2" multiple>
                                            <option value="">----select----</option>
                                            <?php
                                                if(!empty($artists)){
                                                    foreach($artists as $artist){
                                                        if($artist['artists_type_id'] == $show_artists['artists_type_id']){
                                            ?>
                                                <option <?php if(in_array($artist['id'], explode(',', $show_artists['artists_id']))){ echo "selected";}?> value="<?php echo $artist['id'];?>"><?php echo $artist['name'];?></option>
                                            <?php
                                                        }
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>  
                                    <div class="col-md-1">
                                         <label for="cat_name">&nbsp;</span></label>
                                         <?php
                                            if($key_data == 0){
                                        ?>
                                            <p class="plusBtn add_artist">+</p>
                                        <?php
                                            }else{
                                        ?>
                                            <p class="minusBtn remove_artist">-</p>
                                        <?php  
                                            }
                                         ?>

                                    </div>
                                </div>
                            </div> 
                            <?php
                                    }
                                }else{
                            ?>
                                                            <div class="artist_section">
                                <div class="section_artist">
                                     <div class="form-group col-md-5 artists_type_chk">
                                        <label for="artists_type_id">Artist Type<span style="color:#ff0000">*</span></label>
                                        <select name="artists_type_id" data-sequense='1' id="artists_type_id" class="form-control">
                                            <option value="">----select----</option>
                                            <?php
                                                if(!empty($artists_types)){
                                                    foreach($artists_types as $artists_type){
                                            ?>
                                                <option value="<?php echo $artists_type['id'];?>"><?php echo $artists_type['title'];?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>  
                                    <div class="form-group col-md-6 artists_chk">
                                        <label for="artist_id">Artist <span style="color:#ff0000">*</span></label>
                                        <select name="artist_id" id="artist_id" class="form-control select_2_chk selectpicker2" multiple>
                                            <option value="">----select----</option>
                                            <?php
                                                if(!empty($artists)){
                                                    foreach($artists as $artist){
                                            ?>
                                                <option value="<?php echo $artist['id'];?>"><?php echo $artist['name'];?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>  
                                    <div class="col-md-1">
                                         <label for="cat_name">&nbsp;</span></label>
                                        <p class="plusBtn add_artist">+</p>
                                    </div>
                                </div>
                            </div>  

                            <?php
                                }
                            ?>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-sm display_color text-white f-600 mt-2 mb-3">Submit</button>      
                            </div>  
                        </form>


                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Actor Type</th>
                                    <th>Actor Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="artists_tbl_section">
                                <?php 
                                    if(!empty($showaActors)){ 
                                        foreach($showaActors as $key => $showaActor){
                                ?>
                                <tr>
                                    <td><?php echo ++$key;?></td>
                                    <td><?php echo $showaActor['artists_type_name'];?></td>
                                    <td><?php echo $showaActor['artists_name'];?></td>
                                    <td><a class="delete_show_artist" href="<?php echo site_url('auth_panel/contentManagement/ContentManagementController/delete_show_artist/' . $showaActor['id']);?>"><i class="fa fa-trash-o"></i></a></td>
                                </tr>
                                <?php
                                        }

                                    }else{
                                        echo "<tr ><td colspan='4'>Data Not found!!..</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                        
                    </div>
            </div>
        </section>
       
    </div>
 <?php } ?>   

</div>

  
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<script>
    /* show hide magic */
    $('.prod-cat a').click(function(e) {
        div = $(this).data('div');
        // alert(div)
        $('.tabu').hide();
        $(this).tab('show');
        var tabContent = '#tabContent' + div;
        $(tabContent).show();
        document.cookie = "activediv=" + div;
    });

</script>

<script>
    $('#container').addClass('sidebar-closed');
    $('#main-content').css('margin-left', "220px");
    $('#sidebar').css('margin-left', "0");
</script>


<script>
    // $(document).on('ready', function(e){        
    //     $("#tabContent1").css("display", "block")
        
    // })
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?= AUTH_ASSETS ?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>js/jquery.dataTables.js"></script>
<?php
    $adminurl = AUTH_PANEL_URL;
?>
 <?php 
    if(!empty($specific_season['id'])) { 
?>
  <script type="text/javascript" language="javascript" >    
           var table = 'all-user-grid';
           var dataTable = jQuery("#"+table).DataTable( {
               "processing": true,
                "pageLength": 15,
                "lengthMenu": [[15, 25, 50], [15, 25, 50]],
               "serverSide": true,
               "order": [[ 0, "desc" ]],
               "ajax":{
                   url :"<?php echo $adminurl;?>"+"contentManagement/ContentManagementController/ajax_season_list/<?php echo $id;?>/?season_id=" + '<?php echo $specific_season['id']; ?>', // json datasource
                   type: "post",  // method  , by default get
                   error: function(){  // error handling
                       jQuery("."+table+"-error").html("");
                       jQuery("#"+table+"_processing").css("display","none");
                   }
               }
           } );
           jQuery("#"+table+"_filter").css("display","none");
           $('.search-input-text').on( 'keyup click', function () {   // for text boxes
               var i =$(this).attr('data-column');  // getting column index
               var v =$(this).val();  // getting search input value
               dataTable.columns(i).search(v).draw();
           } );
            $('.search-input-select').on( 'change', function () {   // for select box
                var i =$(this).attr('data-column');
                var v =$(this).val();
                dataTable.columns(i).search(v).draw();
            } );    
  
                                   
   </script>
   <?php } ?>  

<script>


    //Searching Course to attach file
    if ($('#selected_course').length > 0) {
        $('#selected_course').select2({
            placeholder: 'Select an Course',
            theme: "classic",
            width: 'resolve',
            allowClear: true,
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/course_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }
    
    $(document).on("click", ".remove_file_from_topic", function () {
        var selector = $(this);
        let file_id = selector.attr("file_id");
        let courseId = selector.attr("course_id");
        $.ajax({
            url: '<?= AUTH_PANEL_URL ?>course_product/course/ajax_remove_file_from_topic/' + file_id + '?course_id=' + courseId,
            type: "GET",
            dataType: 'json',
            success: function (data) {
                if (data.data == 1) {
                    selector = selector.parent().remove();
                    if (data.topic_type == "video") {
                        show_toast("success", "Video Removed Successfully", "Video Remove");
                    } else {
                        show_toast("success", "Topic Removed Successfully", "Topic Remove");
                    }
                    $('.refresher').show();
                } else {
                    show_toast("warning", data.title, data.message);
                }
            }
        });
    });
    
    $(document).on('click', '.virtual-name-changer', function () {
        var m = $(this).parent().parent('.set_virtual_name');

        var info = {
            id: m.children("input[name=for_name_id]").val(),
            v_name: m.find("input[name=v_name]").val(),
            v_name_2: m.find("input[name=v_name_2]").val()
        }
        $.ajax({
            url: "<?= AUTH_PANEL_URL ?>course_product/course/add_virtual_name",
            type: "POST",
            dataType: 'json',
            data: info,
            success: function (data) {
                if (data.status == true) {
                    show_toast('success', 'Virtual name set successfully !!', 'Name Updated ');
                }
            },
            error: function (data) {
                show_toast('error', 'Please try after some time ', 'Name not Updated ');
            }
        });
    });
</script>


<script>

    var form = $("#add_video");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            media_type: {
                required: true
            },
            genres_type_general: {
                required: true
            },
            cate_type: {
                required: true
            },
            published_date : {
                required: true
            },
            title : {
                required: true
            },
            
             
        },
    
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
    $("#").change(function(){
        var view_mode = $(this).val();
        // alert(v_limit);
        // return false;
        if(view_mode == 1){
            $(".multiplier").show();
        }else{
            $(".multiplier").hide();
        }
    })
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
            player = new YT.Player('video_player_aws_new', {
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
    

    function skipIntro() {
     // Get the checkbox
          var skip_int = document.getElementById("skip_intro");
         
         if (skip_int.checked == true){
            $(".seconds").show();
          } else{
            $(".seconds").hide();


          }
        }
        function skipIntr() {
     // Get the checkbox
          var skip_int = document.getElementById("skip_intr");
         
         if (skip_int.checked == true){
            $(".sec").show();
          } else{
            $(".sec").hide();


          }
        }

        function checkpriceee(input) {
           if (input.value == 0) {
             input.setCustomValidity('The value must not be zero.');
           } else {
             // input is fine -- reset the error message
             input.setCustomValidity('');
           }
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
        console.log("working");
        console.log(player);
        if (player != null) {
            stopVideo();
        }
        let media_type = $('select[name=media_type]').val();
        if (media_type == 0 || media_type == 5)
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
</script>

<script type="text/javascript" language="javascript" >
        
    $('select[name=movie_view]').change(function () {
        var movie_view = $('select[name=movie_view]').val();
        if(movie_view == '1'){
            $(".price_per_video").show();
            $('#price_per_video').prop('required', true);
        }else{
            $(".price_per_video").hide();
            $('#price_per_video').prop('required', false);        
        }
        
    });


    jQuery(document).ready(function () { 
        // $(".artists").hide();
        // $(".price_per_video").hide();
        $(".web_season").hide();
    //    $(".web_genres").hide();
        $(".season_number").hide();
      //  $(".web_artists").hide();
        $(".tv_category").hide();
     //   $(".tv_artists").hide();
        //$(".video_genres").hide();
        //$(".video_artists").hide();
        $(".title_tail").hide(); 
        $(".playtime_tail").hide();
        $(".upload_video_cls_teil").hide();
        $(".video_tailer_title").hide();
    });

    
    $('select[name=cate_type]').change(function () { 
        $(".video_tailer_title").hide();
        $(".videocript_movie").hide();
        //$(".add_file_element_trail").hide();
        var cate_Type = $('select[name=cate_type]').val();
        if (cate_Type == '1') { 
          //  $(".artists").show();
          
            $(".title_tail").show(); 
           // $(".price_per_video").show();
            $(".playtime_tail").show();
           
            $(".upload_video_cls_teil").show();
            $(".web_season").hide();
         //   $(".web_genres").hide();
            $(".season_number").hide();
          //  $(".web_artists").hide();
            $(".tv_category").hide();
        //    $(".tv_artists").hide();
        //    $(".video_genres").hide();
         //   $(".video_artists").hide();
            $(".add_file_element_trail").show();
            
        } else if (cate_Type == '2') {//youtube
            $(".add_file_element_trail").hide();
            $(".web_season").show();
            $(".video_tailer_title").hide();
          // $(".price_per_video").show();
          //  $(".season_number").show();
      //      $(".web_artists").show();
            $(".title_tail").hide(); 
            $(".upload_video_cls_teil").hide();
            $(".playtime_tail").hide();
            $(".videocript_movie").hide();
            
     //       $(".artists").hide();
          // $(".price_per_video").show();
            $(".tv_category").hide();
       //     $(".tv_artists").hide();
      //      $(".video_genres").hide();
        //    $(".video_artists").hide();
        } else if (cate_Type == '3') {//youtube live
            $(".add_file_element_trail").hide();
            $(".web_season").hide();
            $(".title_tail").hide(); 
          //  $(".price_per_video").hide();
            $(".video_tailer_title").hide();
            $(".playtime_tail").hide();
            $(".upload_video_cls_teil").hide();
            $(".season_number").hide();
            $(".videocript_movie").hide();
         //  $(".price_per_video").show();
         //   $(".artists").hide();
            $(".tv_category").show();
        //    $(".tv_artists").show();
        //    $(".video_genres").hide();
          //  $(".video_artists").hide();
        } else if (cate_Type == '4') {//aws live
            $(".add_file_element_trail").hide();
         //   $(".video_genres").show();
          //  $(".video_artists").show();
            $(".playtime_tail").hide();
            $(".title_tail").hide(); 
            $(".video_tailer_title").hide();
            $(".web_season").hide();
            $(".upload_video_cls_teil").hide();
         // $(".price_per_video").hide();
            $(".videocript_movie").hide();
            $(".season_number").hide();
        //   $(".price_per_video").show();
           // $(".artists").hide();
           // $(".tv_artists").hide();
           // $(".genres").hide();
           // $(".artists").hide();
        }
    });

    $(".instant_upload").click(async function () {
        var size = upload_file_size($("#video_file")[0]);
        let set_url = $(this).data("set_url");
        var size = size.split(" ");
        if (parseFloat(size[0]) == 0) {
            show_toast("error", 'Choose Valid File', "Please Select Valid File");
            $("input[name=" + set_url + "]").val("");
            return false;
        }
        let json = await s_s3_file_upload("admin_v1/file_manager/videos/original/", $("#video_file")[0]);
        alert(json);
        $("#video_file").attr('type', 'text');
        $("#video_file").val(json.Location);
    });

    $(document).on("click", ".download_offline,.download_vod_offline,.mediaconvert_track_list", function () {
        let selector = $(this);
        if (selector.hasClass("download_offline")) {
            $("#encrypted_video_modal").modal("show");
            $(".fetch_list,.mediaconvert_track_list").data("id", $(this).data("id"));
            $(".offline_vod_auto").find("tbody").html("");
        } else if (selector.hasClass("download_vod_offline")) {
            if (!confirm("Are you sure want to available this video in download mode.")) {
                return false;
            }
            jQuery.ajax({
                url: "<?= AUTH_PANEL_URL ?>file_manager/library/available_video_in_vod",
                method: 'Post',
                dataType: 'json',
                data: {
                    id: selector.data("id"),
                    link: selector.data("link"),
                    file_name: selector.data("file_name")
                },
                success: function (data) {
                    show_toast(data.type, data.title, data.message);
                    if (data.type == "success") {
                        selector.find("i").removeClass("fa-download").addClass("fa-check");
                        selector.removeClass("btn-warning").addClass("btn-info");
                        selector.prop("disabled", true);
                        selector.parent().prev().html(data.data.size);
                    }
                }
            });
        } else if (selector.hasClass("mediaconvert_track_list")) {
            jQuery.ajax({
                url: "<?= AUTH_PANEL_URL ?>file_manager/library/mediaconvert_tracking",
                method: 'Post',
                dataType: 'json',
                data: {
                    id: selector.data("id"),
                    job_id: "",
                },
                success: function (data) {
                    show_toast(data.type, data.title, data.message);
                    if (data.type == "success") {
                        let table = $(".track_media_convert").find("tbody");
                        table.html("");
                        $.each(data.data, function (key, value) {
                            table.append("<tr><td>" + (key + 1) + "</td><td>" + value.id + "</td><td>" + value.percent + "</td><td>" + value.status + "</td><td><button data-job_id='" + value.id + "' " + (value.status == "Completed" ? "disabled" : "") + " data-id='" + selector.data("id") + "' class='btn btn-" + (value.status == "Completed" ? "warning" : "info") + " btn-xs track_mediaconvert_job'><i class='fa fa-refresh'></i></button></td></tr>");
                        });
                    }
                }
            });
        }
    });

    $(document).on("click", '.track_mediaconvert_job', function () {
        let selector = $(this);
        jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>file_manager/library/mediaconvert_tracking",
            method: 'Post',
            dataType: 'json',
            data: {
                id: selector.data("id"),
                job_id: selector.data("job_id"),
            },
            success: function (data) {
                show_toast(data.type, data.message, data.title);
                if (data.type == "success") {
                    let table = $(".track_media_convert").find("tbody");
                    table.html("");
                    $.each(data.data, function (key, value) {
                        table.append("<tr><td>" + (key + 1) + "</td><td>" + value.id + "</td><td>" + value.percent + "</td><td>" + value.status + "</td><td><button data-job_id='" + value.id + "' " + (value.status == "COMPLETE" ? "disabled" : "") + " data-id='" + selector.data("id") + "' class='btn btn-" + (value.status == "Completed" ? "warning" : "info") + " btn-xs track_mediaconvert_job'><i class='fa fa-refresh'></i></button></td></tr>");
                    });
                }
            }
        });
    })

    $(".fetch_list").click(function () {
        let id = $(this).data("id");
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
    

    $(".content_thumb").click(function () { 
        if ($(this).text() == "File") {//file
            $(this).text("Url");
            $("input[name=thumbnail]").attr('type', 'file')
        } else {//url
            $(this).text("File");
            $("input[name=thumbnail]").attr('type', 'text')
        }
    });

    $(".video_poster").click(function () { 
        if ($(this).text() == "File") {//file
            $(this).text("Url");
            $("input[name=poster]").attr('type', 'file')
        } else {//url
            $(this).text("File");
            $("input[name=poster]").attr('type', 'text')
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

    function load_video_play_time() {
        var video_duration = document.getElementById("videoid").duration;
        $("input[name=playtime]").val(Math.floor(video_duration));
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
        $("#add_file_col").toggleClass("col-lg-6 , col-lg-6");
        $(".add_file_element_trailer").show();
        $(".is_drm").hide();
        $('.video_file_dash').hide();
        $('.play_via').hide();
        var vid=$('#videocript_id').val();
        if(vid==""){
            alert( "Please Enter videocrypt Id ");
        }
        if(vid!=""){
            jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>file_manager/library/fetch_videocrypt_playlist",
            method: 'Post',
            dataType: 'json',
            data: {
                v_id: vid
            },
            success: function (data) {
                // clip();
                console.log(data.data.transcripts_data);
                const data_trans = data.data.transcripts_data;
               // const data_trick_play = data.data.trick-play-settings;
               
                const jsonStringArray = data_trans.map(obj => JSON.stringify(obj));
                    console.log(jsonStringArray);
                if (data.type == "success") {
                    sr++; 
                   // alert(data.data.drm_hls_url);
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

                    // init_shaka_player("video_player_aws_new", data.data.file_url_hls, 'm3u8', 'm3u8');          
                }
                show_toast(data.type, data.title, data.message);
            }
        });
        }
    });
    function clip(){
        var vid = $('#videocript_id').val();
       // alert()
        if(vid==""){
            alert("Couldn't created clip as Videocrypt ID is not available");
        }
        if(vid != ""){
            jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>file_manager/library/create_clip",
            method: 'Post',
            dataType: 'json',
            data: {
                v_id: vid
            },
            success: function (data) {
                console.log(data.data.video_id);
                const trailer_id = data.data.video_id;
                 $('input[name=videocript_tail_id]').val(trailer_id);

             

                    //init_shaka_player("video_player_aws_new", data.data.file_url_hls, 'm3u8', 'm3u8');          
           
            }
        });
        }
        }

    

    function player_stop() { 
        if(sr > 0){
            var widevineToken = '';
            let player = document.getElementById('video_player_aws_new');;

            videojs(player).dispose();
            $("#test_player").html('<video width="450" autoplay  controls preload="auto" height="300" id="video_player_aws_new" class="video-js vjs-default-skin" ></video>');
            
        }
    }


     var sr1 = 0;
     $(document).on("click", ".video_search_tail", function(){ 
        var vid = $(this).data('id');
        var title = $(this).data('title');
        if(vid!=""){
            jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>contentManagement/ContentManagementController/create_cloudfront_url",
            method: 'Post',
            dataType: 'json',
            data: {
                v_id: vid
            },
            success: function (data) {
                if(data.status == false){
                    show_toast("error", 'Error', "File Id Invalid");
                }else{
                    $("#myModal").modal("show");
                    $(".title_chk_prev").text(title);
                    init_shaka_player("video_player_aws_new_trail", data.url, data.type, data.token);
                    sr1++;
                }
            }
        });
        }
    });

    function player_stop_trai() { 
        if(sr1 > 0){
            var widevineToken = '';
            $("#myModal").modal('hide');
            let player = document.getElementById('video_player_aws_new_trail');;
            videojs(player).dispose();
            $(".add_file_element_trail").html('<video width="520" autoplay  controls preload="auto" height="400" id="video_player_aws_new_trail" class="video-js vjs-default-skin" ></video>');
            
        }
    }

       $(".resetvideo").click(function() {
                $('#cpdfForm').find('input:text, input:password, select')
                    .each(function () {
                        $(this).val('');
                    });   
                    $('#courseid').empty(); 
                    $('#selected_course').empty(); 
                    $('#subject_id').empty();
                    $('#topic_id').empty(); 
                    $('#title').val('');
                    $('#video_file').val('');  
                    $('#start_date').val('');
                    $("input[name=thumbnail]").val(''); 
                    $("select[name=open_with]").val('');
                    // $('select[name=video_type]').val('');
                    $("input[name=playtime]").val(''); 
                    $('#videocript_id').val('');        
            });

    $("#video_limit").change(function(){
        var v_limit = $(this).val();
        // alert(v_limit);
        // return false;
        if(v_limit == 1){
            $(".multiplier").show();
        }else{
            $(".multiplier").hide();
        }
    })
    //for videotoken
    $("#video_type_file").change(function(){
        var v_limit = $(this).val();
        if(v_limit == "Both" || v_limit == "Offline"){
            $(".videotoken").show();
        }
        else{
            $(".videotoken").hide();
        }
    })

</script>


<script type="text/javascript">
    $( document ).ready(function() {

        $("#by_method").change(function() {
        $(".live_start_date,.live_end_date,.studio_div,.channel_div,.videocript,.video_upload,.bitrat").hide();
        var method = $(this).val();
        if (method == 1) {
            $(".video_upload").show();
            $(".bitrat").show();
            $(".videocript").hide();
            $drm = $("#is_drm_protected").val();
            if ($drm == 1) {
                $(".pltform").show();
            }
        } else if (method == 2) {
            $(".videocript").show();
            $(".bitrat").hide();
            $(".video_upload").hide();
            $(".pltform").hide();
        } else if (method == 3) {
            $(".live_start_date,.live_end_date,.studio_div,.channel_div").show();
            $(".video_limit").hide();
        } else {
            $(".video_upload").hide();
            $(".bitrat").hide();
            $(".videocript").hide();
            $(".pltform").hide();

        }
    })
    $("#is_drm_protected").change(function() {
        var vdc = $("#by_method").val();
        var method = $(this).val();
        if (method == 1) {
            if (vdc != 2)
                $(".pltform").show();
        } else {
            $(".pltform").hide();

        }

    })

      $("#video_upload_button").click(async function() {
        var size = upload_file_size($("#video_upload")[0]);
        let set_url = $(this).data("set_url");
        var size = size.split(" ");
        if (parseFloat(size[0]) == 0) {
            show_toast("error", 'Choose Valid File', "Please Select Valid File");
            $("input[name=" + set_url + "]").val("");
            return false;
        }
        let json = await s_s3_file_upload(<?= APP_ID; ?> + "/admin_v1/file_manager/videos/original/", $("#video_upload")[0]);
        // var cld = '<?= "https://" . S3_CLOUDFRONT_DOMAIN; ?>'
        //  var url = json.Location.split("amazonaws.com");
        //  cld = cld+url[1];
        $("#video_upload").attr('type', 'text');
        // $('#video_upload').prop('readonly', true);

        $("#video_upload").val(json.Location);
    });

         });


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

<script>
$('#skip_time').on("keydown", function(event){
    var keyCode = event.which;
    if(!((keyCode > 47 && keyCode < 58) ||  (keyCode > 95 && keyCode < 106) ||  keyCode == 08)){
        event.preventDefault();
    }
});

$(document).on('click', ".common_collapse", function(){
    if (!$(event.target).closest('.delete_data_chk').length) {
      $("." + $(this).data("id")).toggle("300");
    }
})
var _URL = window.URL || window.webkitURL;
  $(document).on('change', ".check_image_aspect_ratio", function(e) {
        var file, img;
        var this_data = $(this);
        var n_width = $(this).data('width');
        var preview = $(this).data('preview');
        var id = $(this).data('id');
        var ratio = $(this).data('ratio');
        $(this).siblings(".error_cheker_image_validation").remove();
        var n_height= $(this).data('height');
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function() {
                var aspectRatio = this.width/this.height;
                // if(ratio == 0){
                //     if (this.width != n_width || this.height != n_height) {
                //         $("#" + preview).attr("src", "");
                //         $(this_data).after("<span class='error_cheker_image_validation' >Please Enter aspect ratio size "+ n_width +":"+ n_height +"</span>");
                //         $(this_data).val('');
                //     }
                // }else{
                    if (Math.abs(aspectRatio - (n_width / n_height)) > 0.01) {
                        $("#" + preview).attr("src", "");
                        $(this_data).after("<span class='error_cheker_image_validation' >Please Enter aspect ratio size "+ n_width +":"+ n_height +"</span>");
                        $(this_data).val('');
                    }
                // }
            }
            img.onerror = function() {
                $("#" + preview).attr("src", "");
                $(this_data).after("<span class='error_cheker_image_validation' >not a valid file: " + file.type + "</span>")
                $(this_data).val('');
            }
            img.src = _URL.createObjectURL(file);
            $("#" + preview).attr("src", img.src);
        }
    });
  $(document).on("change", "#category_id", function(){
    $.ajax({
         url: "<?= AUTH_PANEL_URL ?>contentManagement/ContentManagementController/getGenres/" + $(this).val(),
            type: "post",
            cache: false,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response)
                // $('#genres_id').find('option').not(':first').remove();
               $.each(response, function(index, data) {
                  $('#genres_id').append('<option value="' + data['id'] + '">' + data['title'] + '</option>');
               });

            } ,
            error: function (data) {
                console.log("error");
            //console.log(data);
            }
    });
  })
  $(document).on("change", "#artists_type_id", function(){
    var this_data = $(this);
    var chk_data = []
    $(".artists_type_chk").each(function(){
        if($(this).find("select").val() && $(this).find('select').attr('data-sequense') != $(this_data).attr('data-sequense')){
            chk_data.push($(this).find("select").val());
        }
    })
    
    if(chk_data && chk_data.includes($(this).val())){
        $(this_data).val("")
        $(this_data).parents('.artists_type_chk').siblings('.artists_chk').find("#artist_id").find('option').not(':first').remove();
        show_toast("error", "Validation Error", "Can't allowed duplicate select artist type");
        return false;
    }
    $.ajax({
         url: "<?= AUTH_PANEL_URL ?>contentManagement/ContentManagementController/getArtists/" + $(this).val(),
            type: "post",
            cache: false,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response)
                $(this_data).parents('.artists_type_chk').siblings('.artists_chk').find("#artist_id").find('option').not(':first').remove();
               $.each(response, function(index, data) {
                  $(this_data).parents('.artists_type_chk').siblings('.artists_chk').find("#artist_id").append('<option value="' + data['id'] + '">' + data['name'] + '</option>');
               });

            } ,
            error: function (data) {
                console.log("error");
            //console.log(data);
            }
    });
  })
   $(document).on("click", ".add_artist", function(){
        var html_data =  '<label for="artist_id">Artist <span style="color:#ff0000">*</span></label><select name="artist_id" id="artist_id" class="form-control select_2_chk selectpicker2" multiple><option value="">----select----</option>';
        $(".artist_section:last").after("<div class='artist_section'>" + $(".artist_section").eq(0).html() + "</div>")
        $(".artist_section:last").children(".section_artist").find(".artists_chk").html("");
        $(".artist_section:last").children(".section_artist").find(".artists_chk").append(html_data);
        $(".artist_section:last").children(".section_artist").find(".artists_chk").find("select").select2();
        $(".artist_section").each(function(sr){
            $(this).children(".section_artist").find(".artists_type_chk").find("select").attr('data-sequense', sr);
        })
        $(".artist_section:last").children(".section_artist").find(".artists_type_chk").find("select").val("");
        $(".artist_section:last").children(".section_artist").find(".plusBtn").text("-").removeClass("plusBtn add_artist").addClass("minusBtn remove_artist")
        // $(".artist_section").children(".section_artist:last").find(".select_2_chk").attr("name", "test");
        // $(".artist_section").children(".section_artist:last").find(".select_2_chk").attr("id", "test");
   })
   $(document).on("click", ".remove_artist", function(){
        $(this).parents(".section_artist").remove();
   })
   $(".selectpicker2").select2();
   // if($(".selectpicker2").lengtlengthh){
   //    $(".selectpicker2").each(function(){
   //      $(this).select2();
   //    })
   // }
   $(document).on("submit", ".add_artist_in_shows", function(e){
    e.preventDefault();
    var json_data = {};
    var output = [];
    var hasNonUniqueArtist = false; // Flag to track if non-unique artists are found
    var season_id = "";
    <?php
        if(!empty($_GET['season_id'])){
    ?>
        season_id = "<?php echo $_GET['season_id']; ?>"
    <?php
        }
    ?>
    if(season_id == ""){
        show_toast("error", "Validation Error", "Season Id Missing!!..");
        return;
    }
    $(".section_artist").find(".artists_type_chk").each(function(){
        if($(this).find("select").val() != ""){
            var artist_data = [];
            var artist_type = $(this).find("select").val();
            $(this).siblings('.artists_chk').find("select").find(":selected").each(function(){
                if($(this).val() != ""){
                    if (json_data[artist_type] && json_data[artist_type].includes($(this).val())) {
                        hasNonUniqueArtist = true; // Set flag to true
                    }
                    artist_data.push($(this).val());
                }
            });
            json_data[artist_type] = artist_data; 
            var artistType = {
                artist_type_id: $(this).find("select").val(),
                artist_type_name: $(this).find("select option:selected").text()
            };
            console.log(json_data)
            var artists = [];
            $(this).siblings('.artists_chk').find("select").find(":selected").each(function(){
                if($(this).val() != ""){
                    var artist = {
                        artist_id: $(this).val(),
                        artist_name: $(this).text()
                    };
                    artists.push(artist);
                }
            });
            if(artists.length){
                output.push({
                    artist_type: artistType,
                    artists: artists
                });
            }
        }
    });
    // Check if any non-unique artists were found
    if (output.length == 0) {
        show_toast("error", "Validation Error", "Please add atleast one artist.");
        return;
    }
    if (hasNonUniqueArtist) {
        show_toast("error", "Validation Error", "Please add unique artists for each type.");
        return;
    }

    var data = {
        "data" : output,
        "season_id" : season_id,
        "json_data" : json_data
    };
    $.ajax({
        url: $(this).attr("action"),
        cache: false,
        dataType: 'json',
        type: 'POST',
        'processData': false,
        'contentType': 'application/json',
        'data' : JSON.stringify(data),
        success: function(response) {
            // console.log(response);
            $('.artists_tbl_section').html('');
              if(response){
                show_toast("success", "Success", "Data Added successfully!!..");
              }
           $.each(response, function(index, data) {
              var th_data = "";
              var action_url = "<?php echo AUTH_PANEL_URL;?>" + "contentManagement/ContentManagementController/delete_show_artist/" + data['id'];
              th_data += "<td> " + ++index + "</td>";
              th_data += "<td> " + data['artists_type_name'] + "</td>";
              th_data += "<td> " + data['artists_name'] + "</td>";
              th_data += "<td> <a class='delete_show_artist' onclick='return confirm(`Are you sure you want to delete?`)' href='"+ action_url + "'><i class='fa fa-trash-o'></i></a></td>";
              $('.artists_tbl_section').append("<tr>" + th_data + "</tr>");
           });
        },
        error: function (data) {
            console.log("error");
            //console.log(data);
        }
    });
});

$(document).on("click", ".delete_show_artist", function(e){
      e.preventDefault();
      var this_data = $(this);
      if(confirm("Are you sure you want to delete")){
        $.ajax({
            url: $(this).attr("href"),
            cache: false,
            dataType: 'json',
            success: function(response) {
                if(response.status == true){
                    show_toast("error", "Validation Error", "Data deleteed successfully!!..");
                    $(this_data).parents("tr").remove();
                    return;
                }
            } 
      })
    }
})

      $('.date-own').datepicker({
         minViewMode: 2,
         format: 'yyyy'
       });

      $(document).on("change", "select[name='content_type']", function(){
        $(".channel_id_select").addClass("hide");
        $(".vdc_id_input").addClass("hide")
        $("input[name='videocript_id']").removeAttr("required");
        $("select[name='channel_id']").removeAttr("required");
         if($(this).val() == "0"){
            $("input[name='videocript_id']").attr("required", true);
            $(".vdc_id_input").removeClass("hide")
         }else if($(this).val() == "1"){
            $("select[name='channel_id']").attr("required", true);
            $(".channel_id_select").removeClass("hide")
         }
      })

</script>