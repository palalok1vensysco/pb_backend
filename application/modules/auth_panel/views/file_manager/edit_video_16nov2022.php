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
.select2-container{
            border: none !important;
        }
        .select2-container .select2-selection--single .select2-selection__rendered{
            border: 1px solid gray;
            border-radius: 4px;
        }
</style>

<input type="hidden" name="genres_type" id="genres_type" value="<?= $video_detail['genres_type']; ?>">
<div class="col-lg-6 no-padding">
    <section class="panel">
        <header class="panel-heading">
            Edit Video
        </header>
        <div class="panel-body">
            <form role="form"  method="post" enctype="multipart/form-data" autocomplete="off" >
                <div class="form-group col-md-12 ">
                    <label >Video Type</label>
                    <select class="form-control input-xs" id="video_type" name="video_type">
                        <option value="0">Select Video Type</option>
                        <option value="7" <?= ($video_detail['video_type'] == '7') ? 'selected' : '' ?> >Videocrypt VOD</option>
                        <?php if(isset($f_lists->VideocryptLive) && $f_lists->VideocryptLive == 1 || APP_ID == 0){ ?>
                        <option value="8"  <?= ($video_detail['video_type'] == '8') ? 'selected' : '' ?>>Videocrypt Live</option>
                        <?php }?>
                        <?php if(isset($f_lists->Youtube) && $f_lists->Youtube == 1 || APP_ID == 0){ ?>
                        <option value="1"  <?= ($video_detail['video_type'] == '1') ? 'selected' : '' ?>>Youtube</option>
                        <?php }?>
                        <?php if(isset($f_lists->Youtube_Live) && $f_lists->Youtube_Live == 1 || APP_ID == 0){ ?>
                        <option value="4"  <?= ($video_detail['video_type'] == '4') ? 'selected' : '' ?>>Youtube live</option>
                    <?php }?>
                    </select>
                </div>               
                              
                <div class="form-group col-md-12 videocript video-nput-2" style="display:<?php echo ($video_detail['vdc_id']==''?'none':'block')?>">                   
                    <label >Videocript ID</label>
                    <input type="text" placeholder="Enter ID Here" name = "videocript_id" value = "<?php echo (isset($video_detail['vdc_id'])?$video_detail['vdc_id']:''); ?>" id="videocript_id" class="form-control input-xs vid-wid" >                    
                    <button class="btn btn-success btn-xs" id="video_search" type="button"><i class="fa fa-search"></i></button>
                </div>

                <div class="form-group col-md-12">
                    <label >Category</label>
                    <select class="form-control input-xs" name="cate_type" required="">
                        <option value="0">Select</option>
                        <?php 
                        $categoriesId = $video_detail['category'];
                        foreach ($categories as $categorie) { 
                            
                                  $appcategory = explode(',' ,$categorie['app_id']);
                                    $selectedcategoriries = ($categoriesId == $categorie['id']) ? "selected" : "";
                                    if(in_array($video_detail['app_id'],$appcategory) ){
                                    echo '<option value="' . $categorie['id'] . '" ' . $selectedcategoriries . '>' . $categorie['cat_name'] . '</option>';
                                 }
                            
                      
                           }?>
                    </select>
                    <span class="error bold"><?php echo form_error('title'); ?></span>
                </div>
<!-- 
                 <div class="form-group col-md-12 videocript_movie video-nput-2"> 
                    <label >Videocript Trailer ID</label>
                    <input type="text" placeholder="Enter ID Here" name = "videocript_tail_id" value = "<?php echo (isset($video_detail['vdc_tail_id'])?$video_detail['vdc_tail_id']:''); ?>" id="videocript_tail_id" class="form-control input-xs vid-wid" >                    
                    <button class="btn btn-success btn-xs" id="video_search_tail" type="button"><i class="fa fa-search"></i></button>
                </div> -->

               <!--  <div class="form-group col-md-12 artists" style="display:<?php echo ($video_detail['artists_type']==''?'none':'block')?>">
                    <label >Artists</label>
                    <select class="form-control input-xs" name="artists_type[]">
                         <option value="0">Select Artists</option>
                         <?php
                          $artist_typeId = $video_detail['artists_type'];
                          foreach ($artists as $artist) {
                             $artistId= ($artist_typeId == $artist['id']) ? "selected" : "";
                            echo '<option value="' . $artist['id'] . '" ' .$artistId.'>' . $artist['name'] . '</option>';

                          }?>
                    </select>
                    <span class="error bold"><?php echo form_error('artists'); ?></span>
                </div> -->
                
                 <div class="form-group col-md-12 artists">
                        <label for="rlguru">Select Artists</label>
                        <select class="form-control input-sm m-bot15 selectpicker" name = "artists_type[]" id="rlguru" multiple="" data-live-search="true">
                            <?php
                            if (isset($artists)) {
                                foreach ($artists as $artists) {
                                    ?>
                                    <option value="<?php echo $artists['id']; ?>" <?php
                                    $cats = explode(',', $video_detail['artists_type']);
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

                <div class="form-group col-md-12 genres" >
                    <label >Genres</label>
                    <select class="form-control input-xs" id="genres" name="genres_type_general">
                         <option value="0">Select Genres</option>
                        
                         <!-- <?php
                        //      $genres_typeId = $video_detail['genres_type'];
                        //   foreach ($genres as $genre) {                             
                        //       $selectedcategoriries= ($genres_typeId == $genre['id']) ? "selected" : "";
                        //     echo '<option value="' . $genre['id'] . '" ' . $selectedcategoriries . '>' . $genre['sub_category_name'] . '</option>';
                        //     }?> -->

                    </select>
                    <span class="error bold"><?php echo form_error('genres'); ?></span>
                </div>

                <div class="form-group col-md-12">
                    <label >Video Title</label>
                    <input type="text" placeholder="Enter title" name = "title" id="title" value = "<?php echo $video_detail['title'] ?>" class="form-control input-xs">

                    <span class="error bold"><?php echo form_error('title'); ?></span>
                </div>
                <div class="form-group col-md-12 video_tailer_title">
                    <label >Video Trailer Title</label>
                    <input type="text" placeholder="Enter Title Here" name = "title_tail" id="title_tail" value = "<?php echo $video_detail['title_tail'] ?>" class="form-control input-xs" >
                    <span class="error bold"><?php echo form_error('title_tail'); ?></span>
                </div>
                <div class="form-group col-md-12 live_start_date" style="display:none">
                    <label>Start Date</label>
                    <input name="start_date" id="start_date" value="" placeholder="Select live start date Time" class="form-control input-xs ">
                </div>
         

                <div class="form-group col-md-12 upload_video_cls vod_url hide" style="display:<?php echo (strpos($video_detail['file_url'],'.m3u8')==true?'block':'none') ?>">
                    <label class="file_title">Upload Video </label><button class="btn btn-info btn-xs cover_video" type="button">Url</button>&nbsp;&nbsp;
                    <input type="input" accept="video/mp4" name ="video_file" id="video_file" value = "<?php echo $video_detail['file_url'] ?>" placeholder="Enter Video URL" class="form-control input-xs" >
                 </div>
       

                 <div class="form-group col-md-12 video_file_dash hide"  style="display:<?php echo (strpos($video_detail['file_url'],'.mpd')==true?'block':'none') ?>">
                    <label >DASH Url</label>
                    <input type="input" accept="video/mp4" name ="video_file_dash" id="video_file_dash" value = "<?php echo $video_detail['file_url'] ?>" placeholder="Enter dash Video URL" class="form-control input-xs" >
                </div>

              
                <div class="form-group col-md-6 play_via hide" style="display:<?php echo ($video_detail['vdc_id']==''?'none':'block')?>">
                    <label>Play Via</label>
                    <select name="play_via" class="form-control input-xs">
                        <option value="0" <?php echo (strpos($video_detail['file_url'],'.m3u8')==true?'selected':'') ?>>HLS</option>
                        <option value="1" <?php echo (strpos($video_detail['file_url'],'.mpd')==true?'selected':'') ?>>DASH</option>
                    </select>
                </div>

               <!--  <div class="form-group col-md-12 upload_video_cls_teil vod_url" style="display:<?php echo (strpos($video_detail['video_file_tail'],'.m3u8')==true?'block':'none') ?>">
                    <label class="file_title">Upload Video Tailer </label><button class="btn btn-info btn-xs cover_video" type="button">Url</button>&nbsp;&nbsp;
                    <input type="input" accept="video/mp4" name ="video_file_tail" id="video_file_tail"  value = "<?php echo $video_detail['video_file_tail'] ?>" placeholder="Enter Video URL" class="form-control input-xs" >
                 </div> -->

                <div class="form-group col-md-12 upload_video_cls_teil vod_url hide" style="display:block">
                    <label class="file_title">Upload Video Tailer </label><button class="btn btn-info btn-xs cover_video" type="button">Url</button>&nbsp;&nbsp;
                    <input type="input" accept="video/mp4" name ="video_file_tail" id="video_file_tail" value = "<?php echo $video_detail['video_file_tail'] ?>" placeholder="Enter Video URL" class="form-control input-xs" >
                 </div>

                 <div class="form-group col-md-12 video_file_dash hide"  style="display:none">
                    <label >DASH Url</label>
                    <input type="input" accept="video/mp4" name ="video_file_dash" id="video_file_dash" value="<?php if(isset($video_detail['drm_dash_url'])){echo $video_detail['drm_dash_url'];}?>" placeholder="Enter dash Video URL" class="form-control input-xs" >
                </div>

                <div class="form-group col-md-6 playtime enter_class hide">
                    <label >Enter Video Duration(in Minutes)</label>
                    <input type="text" placeholder="Enter playtime in min." maxlength="5" name = "playtime" value="<?php echo $video_detail['playtime']; ?>" class="form-control input-xs">
                    <span class="error bold"><?php echo form_error('playtime'); ?></span>
                </div>
                <div class="form-group col-md-6 playtime_tail" style="display:none">
                    <label >Enter Video Trailer Duration(in Minutes)</label>
                    <input type="text" placeholder="Enter playtime in min." maxlength="5" name = "playtime_tail" value="<?php if(!empty($video_detail['playtime_tail']))echo $video_detail['playtime_tail'] / 60 ?>" class="form-control input-xs">
                    <span class="error bold"><?php echo form_error('playtime_tail'); ?></span>
                </div>
                <div class="form-group col-md-12 web_season">
                    <label for="season_name">WebSeries Season</label>
                     <select class="form-control selectpicker" name="season_id" id="season_name" data-live-search="true"> 
                        <option value="" >select</option>
                        <?php
                        foreach ($seasons as $season) {
                            ?>
                            <option value="<?= $season['id'] ?>" <?= (set_value('id') == $season['id'] ? 'selected' : '') ?>><?= $season['season_name'] ?></option>
                        <?php } ?>
                    </select>   
                    <span class="text-danger"><?php echo form_error('season_name'); ?></span>
                </div>
                <div class="form-group col-md-12 web_genres">
                    <label for="category_ids">WebSeries Genres</label>
                     <select class="form-control selectpicker" name="genres_type_webseries" id="" data-live-search="true" > 
                         <option value="" >select</option>
                               <?php
                            if (isset($web_genres)) {
                                 $genres_typeId = $video_detail['genres_type'];
                                foreach ($web_genres as $web_genre) {
                                    $selectedcategoriries= ($genres_typeId == $web_genre['id']) ? "selected" : "";
                                    foreach ($web_categories as $web_categorie) {
                                         $cats = explode(',', $web_categorie['genres']);
                                    if (in_array($web_genre['id'], $cats)) {
                                        echo '<option value="' . $web_genre['id'] . '" ' . $selectedcategoriries . '>' . $web_genre['sub_category_name'] . '</option>';
                                    ?>

                                <?php 
                            }
                        }
                              }  } ?>
                    </select>
                    <span class="text-danger"><?php echo form_error('category_ids'); ?></span>
                </div>

                <div class="form-group col-md-12 season_number">
                    <label for="exampleInputEmail1">WebSeries Season Number</label>
                <input class="form-control" name="season_type" placeholder="Enter Season" onkeypress="return isNumber(event)" >
                </div>

                <div class="form-group col-md-12 web_artists">
                    <label for="rlguru">WebSeries Artists</label>
                    <select class="form-control input-sm m-bot15 selectpicker" name = "artists_type[]" id="rlguru" multiple="" data-live-search="true">
                        <?php
                        if (isset($form_artist_ids) && !empty($form_artist_ids)) {
                            foreach ($web_authors as $web_author) {
                                ?>
                                <option value="<?= $web_author['id'] ?>" <?= (in_array($web_author['id'], $form_artist_ids)) ? 'selected' : '' ?>><?= $web_author['name'];?></option>
                                <?php
                            }
                        } else {
                            foreach ($web_authors as $guru_name) {
                                ?>
                                <option value="<?= $guru_name['id'] ?>" ><?= $guru_name['name'] ; ?></option>
                                <?php
                            }
                        }
                        ?>        

                    </select>
                    <span class="custom-error"><?php echo form_error('related_guru'); ?></span>
                </div>

                <div class="form-group col-md-12 tv_category">
                    <label for="category_ids">TV Category</label>
                     <select class="form-control selectpicker" name="tv_category_ids" id="category_ids" data-live-search="true" > 
                         <option value="" >---select---</option>
                               <?php
                            if (isset($tv_sub_caegories)) {

                                foreach ($tv_sub_caegories as $sub_caegory) {

                                    foreach ($tv_categories as $category) {
                                         $cats = explode(',', $category['genres']);

                                    if (in_array($sub_caegory['id'], $cats)) {
                                    ?>
                                    <option value="<?php echo $sub_caegory['id']; ?>" >
                                    <?php echo $sub_caegory['sub_category_name'];?>
                                        </option>
                                <?php 
                            }
                        }
                              }  } ?>

                    </select>
                    <span class="text-danger"><?php echo form_error('category_ids'); ?></span>
                </div>

                <div class="form-group col-md-12 tv_artists">
                    <label for="rlguru">TV Artists</label>
                    <select class="form-control input-sm m-bot15 selectpicker" name = "artists_type[]" id="rlguru" multiple="" data-live-search="true">
                        <?php
                        if (isset($form_artist_ids) && !empty($form_artist_ids)) {
                            foreach ($tv_authors as $artist_name) {
                                ?>
                                <option value="<?= $artist_name['id'] ?>" <?= (in_array($artist_name['id'], $form_artist_ids)) ? 'selected' : '' ?>><?= $artist_name['name'];?></option>
                                <?php
                            }
                        } else {
                            foreach ($tv_authors as $guru_name) {
                                ?>
                                <option value="<?= $guru_name['id'] ?>" ><?= $guru_name['name'] ; ?></option>
                                <?php
                            }
                        }
                        ?>        
                    </select>
                    <span class="custom-error"><?php echo form_error('related_guru'); ?></span>
                </div>

                <div class="col-md-12 video_genres">
                    <label for="mobile_menu_ids">Video Genres</label>
                    <select class="form-control selectpicker" name="genres_type_video" id="" data-live-search="true"> 
                         <option value="">select</option>
                               <?php
                            if (isset($sub_caegories)) {
                                foreach ($sub_caegories as $sub_caegory) {
                                    foreach ($categories as $category) {
                                         $cats = explode(',', $category['genres']);
                                    if (in_array($sub_caegory['id'], $cats)) {
                                    ?>
                                    <option value="<?php echo $sub_caegory['id']; ?>" >
                                    <?php echo $sub_caegory['sub_category_name'];?>
                                        </option>
                                <?php 
                            }
                        }
                              }  } ?>    

                    </select> 
                    <span class="custom-error"><?php echo form_error('mobile_menu_ids[]'); ?></span>
                </div>

                <div class="col-md-12 video_artists">
                    <label for="rlguru">Video Artists</label>
                        <select class="form-control input-sm m-bot15 selectpicker" name = "artists_type[]" id="rlguru" multiple="" data-live-search="true" >
                            <?php
                            if (isset($form_artist_ids) && !empty($form_artist_ids)) {
                                foreach ($video_guru as $artist_name) {
                                    ?>
                                    <option value="<?= $artist_name['id'] ?>" <?= (in_array($artist_name['id'], $form_artist_ids)) ? 'selected' : '' ?>><?= $artist_name['name'] ?></option>
                                    <?php
                                }
                            } else {
                                foreach ($video_guru as $guru_name) {
                                    ?>
                                    <option value="<?= $guru_name['id'] ?>" ><?= $guru_name['name']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <span class="custom-error"><?php echo form_error('related_guru'); ?></span>
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

                <div class="form-group col-md-12 drm_hls_url" style="display:none">
                    <label >DRM Hls Url</label>
                    <input type="input" accept="video/mp4" name ="drm_hls_url" id="drm_hls_url" placeholder="Enter DRM DASH Video URL" class="form-control input-xs" >
                </div>
                <div class="form-group col-md-12 drm_dash_url"  style="display:none">
                    <label >DRM Dash Url</label>
                    <input type="input" accept="video/mp4" name ="drm_dash_url" id="drm_dash_url" placeholder="Enter DRM HLS Video URL" class="form-control input-xs" >
                </div>
                
                
                <!-- <div class="form-group col-md-12 studio_div" style="display:none;">
                    <label>Studio List</label>
                    <select name="studio_id" class="form-control input-xs">
                        <option value="">Select Studio</option>
                        <?php
                        foreach ($studio_list as $studio) {
                            echo '<option value="' . $studio['id'] . '">' . $studio['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div> -->
                <?php //print_r($video_detail);?>
               <div class="form-group col-md-12 channel_div" style="<?php if(!empty($video_detail['channel_id'])){echo "display :block";}else{ echo "display: none"; } ?>">
                    <label>Channel List</label>
                    <select name="channels" class="form-control input-sm" id="channels">
                        <option value="">Select Channel</option>
                        <?php
                        foreach ($channels as $channel) {
                                if($channel['id']==$video_detail['channel_id']){
                                    $varselecte = "selected"; 
                                }else{
                                    $varselecte = ""; 
                                }  
                                echo $varselecte;
                            echo '<option value="' . $channel['id'] . '" '.$varselecte.' >' . $channel['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

              <!--   <div class="form-group col-md-12 video_thumbnail">
                    <label>Video Thumbnail <button class="btn btn-info btn-xs content_thumb" type="button">Url</button></label>
                    <input type="file" accept="image/*" name = "thumbnail" class="form-control input-xs" >
                    <span class="error bold"><?php echo form_error('thumbnail'); ?></span>
                </div> -->

                 <div class="form-group col-md-12">
                    <label>Video Thumbnail </label>
                    <input type="file" accept="image/*" name = "thumbnail" class="form-control input-xs" >
                    <?php if (($video_detail['thumbnail_url'])) { ?>
                        <img width="150" src="<?= $video_detail['thumbnail_url'] ?>" class="img-responsive" id="post_img">
                    <?php } ?>
                </div>

                <div class="form-group col-md-12 video">
                    <label>Video Poster</label>
                    <input type="file" accept="image/*" name = "poster" class="form-control input-xs" >
                      <?php if (($video_detail['poster_url'])) { ?>
                        <img width="150" src="<?= $video_detail['poster_url'] ?>" class="img-responsive" id="post_img">
                    <?php } ?>
                </div>

                <div class="form-group col-md-6 Downloadclass">
                    <label>Is Downloadable</label>
                    <select name="is_download" class="form-control input-xs ">
                        
                    <option value="0" <?php if($video_detail['is_download']==0){ echo "selected"; }?>> No</option>
                        <option value="1" <?php if($video_detail['is_download']==1){ echo "selected"; }?>>Yes</option>
                
                        </select>
                </div>
                
               <!--  <div class="form-group col-md-6">
                    <label>Open With</label>
                    <select name="open_with" class="form-control input-xs">
                        <option value="1">In APP</option>
                        <option value="0">Out APP</option>
                    </select>
                </div> -->

                  <div class="form-group col-sm-6"  >
                    <label class="control-label  pl-0" ><strong>View Mode</strong></label>

                    <select name="movie_view" class="form-control input-xs">
                        <option value="0" <?php if($video_detail['view_mode']==0){ echo "selected"; }?>> FREE</option>
                        <option value="1"<?php if($video_detail['view_mode']==1){ echo "selected"; }?>>PAID</option>
                    </select>

                    <!-- <input type="radio" name="movie_view" value="1" <?=($video_detail['view_mode']==1)?'checked':'checked'?>>FREE
                    <input type="radio" name="movie_view" value="0" <?=($video_detail['view_mode']==0)?'checked':''?>>PAID -->

                </div>
                <div class="row">
                <div class="form-group col-md-6 skip_intro" style="<?php if(!empty($video_detail['skip_intro'])){echo "display :block";}else{ echo "display: none"; } ?>">
                    
                    <input type="checkbox" id="skip_intro" name="skip_intro" value="1" onclick="skipIntro()" <?=($video_detail['skip_intro']==1)?'checked':''?>>
                    <label>Skip Intro</label>
                </div>
              
                <div class="form-group col-md-6 seconds" style="display:none" >
                <label>Seconds</label>
                    <input type="number" id="skip_time" name="skip_time" class="form-control" value="<?php echo $video_detail['skip_time'] ?>">
                   
                </div>
                </div>  
                <div class="form-group col-md-12 released_date">
                    <label for="published-date-user">Released Date</label>
                    <div data-date-format="yyyy-mm-dd" data-date="2013/12/1">
                        <input class="form-control published-date-user" type="date" name="published_date" id="published-date-user" placeholder=" Select Published Date From" value="<?php echo $video_detail['published_date']; ?>">
                    </div>
                    <span class="text-danger"><?php echo form_error('published_date'); ?></span>
                </div>     

                 <!-- <div class="form-group col-md-6 price_per_video" style="<?php //if($video_detail['view_mode']==1){ echo "display:block"; }else{echo "display:  none";}?>">
                    <label>Price</label>
                    <input type="input" name ="ppv"  id="price_per_video" value="<?php //echo $video_detail['ppv']; ?>" placeholder="Enter Price Per Video " class="form-control input-xs" oninput="checkprice(this)" onkeydown="return (event.ctrlKey || event.altKey
                            || (47 < event.keyCode && event.keyCode < 58 && event.shiftKey == false)
                            || (95 < event.keyCode && event.keyCode < 106)
                            || (event.keyCode == 8) || (event.keyCode == 9)
                            || (event.keyCode > 34 && event.keyCode < 40)
                            || (event.keyCode == 46))" maxlength="5">
                </div> -->
                                
                <div class="form-group col-md-12 live_end_date" style="display:none">
                    <label>End Date</label>
                    <input name="end_date" id="end_date" value="" placeholder="Select live end date Time" class="form-control input-xs ">
                </div>

              
<!-- 
                <div class="form-group col-md-12 released_date">
                    <label for="published-date-user">Released Date</label>                 
                    <div data-date-format="yyyy-mm-dd" data-date="2013/12/1">
                        <input class="form-control published-date-user" type="date" name="published_date" id="published-date-user" placeholder=" Select Published Date From" value="<?php if(isset($video_detail['published_date'])){echo $video_detail['published_date'];} ?>">
                    </div>
                    <span class="text-danger"><?php echo form_error('published_date'); ?></span>
                </div> -->
                <div class="form-group col-md-12" >
                    <label class="col-sm-12 control-label col-sm-2">Description</label>
                    <div class="col-sm-12">
                        <textarea placeholder="Enter Description (only 300 word)s" class="form-control " name="description" rows="6"  maxlength='300' required><?php echo $video_detail['description'];?> <?php echo set_value('movie_desc'); ?></textarea>
                        <br>
                    </div>
                    <span class="custom-error"><?php echo form_error('video_desc'); ?></span>
                </div>

                <div class="col-md-12 form-group hide" >
                    <label>Select Courses</label>
                    <!-- <select data-tags="false"  name="course_id[]" class="form-control input-xs course_id select2-selection--multiple" multiple="multiple" id="courseid" > -->
                    <select data-tags="false"  name="course_id" class="form-control input-xs course_id select2-selection--multiple" multiple="multiple" id="courseid" >
                    </select>
                </div>
                <div class="form-group col-md-12 hide">
                    <label>Subject</label>
                 <!--    <select class="form-control input-xs" name="subject_id" id="subject_id">
                    </select> -->
                    <span class="error bold"><?php echo form_error('subject_id'); ?></span>
                </div>
                <div class="form-group col-md-12 hide">
                    <label>Topic</label>
                    <select class="form-control input-xs" name="topic_id" id="topic_id" required="">
                        <option>--Select Topic--</option>
                    </select>
                    <span class="error bold"><?php echo form_error('subject_id'); ?></span>
                </div>
                <div class="form-group col-md-12 hide">
                    <label>Select Courses To Attach Video</label>
                    <select data-tags="false" id="selected_course"  name="attach_course_id[]" class="form-control input-xs select2-selection--multiple" multiple="multiple" >
                    </select>
                </div>
               <!--  <?php if(isset($f_lists->Limited) && $f_lists->Limited == 1) {?> -->
                   <!--  <div class="form-group col-md-6 video_limit hide">
                        <label>Video Limit</label>
                        <select name="video_limit" id="video_limit" class="form-control input-xs">
                            <option value="0">----select----</option>
                            <option value="0">Unlimited</option>
                            <option value="1">Limited</option>
                        </select>
                    </div> -->
                <!-- <?php }?> -->

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
                <div class="form-group col-md-12">
                    <button class="btn btn-xs display_color "  type="submit" >Update</button>
                    <button class="btn btn-xs display_color"  type="button" onclick="$('.add_file_element').hide('slow');" >Cancel</button>
                    <button type="button" class="btn btn-xs resetvideo display_color">Clear</button>
                    <!--onclick="$('.add_file_element').hide('slow');"-->
                </div>
            </form>

        </div>
    </section>
</div>
<div class="col-lg-6 hide_data_chk add_file_element">
    <section class="panel">
        <header class="panel-heading">
            Video Preview
        </header>
        <div class="panel-body">
            <video width="450" height="300" id="video_player_aws_new" class="video-js vjs-default-skin" autoplay controls preload="auto"></video>
        </div>
    </section>
</div>

<!-- <div class="col-lg-6 hide_data_chk add_file_element_trail">
    <section class="panel ">
        <header class="panel-heading displa_flex">
            Video Trailer Preview
        </header>
        <div class="panel-body add_file_element_trail" id="add_file_element_trail">
            <video width="450" height="300" id="video_player_aws_new_trail" class="video-js vjs-default-skin" autoplay  controls preload="auto"></video>
        </div>
    </section>
</div> -->

<div class="modal" id="encrypted_video_modal" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Ã—</button>
                <h4 class="modal-title">Encrypted Video Management</h4>
            </div>
            <div class="modal-body">
                <div class="panel with-nav-tabs panel-primary">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#auto_fetch_video" data-toggle="tab">Auto Fetch Video</a></li>
                            <li><a href="#track_media_convert_jobs" data-toggle="tab">Media Convert Tracking</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="auto_fetch_video">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-info btn-xs pull-right fetch_list">Fetch Play List</button>
                                    </div>
                                    <div class="col-md-12 offline_vod_auto">
                                        <table class="display table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Name</th>
                                                    <th>Size</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="track_media_convert_jobs">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-info btn-xs pull-right mediaconvert_track_list">Track List</button>
                                    </div>
                                    <div class="col-md-12 track_media_convert">
                                        <table class="display table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Name</th>
                                                    <th>Percent</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 

if ($video_detail['video_type'] == 6) {//jw
    $video_url = $video_detail['file_url'];
} else if ($video_detail['video_type'] == 1 || $video_detail['video_type'] == 4) {//youtube
    $video_url = $video_detail['file_url'];
} else {
    $video_url = $video_detail['file_url']; //normal
}

if ($video_detail['video_type'] == 6) {//jw
    $video_file_tail = $video_detail['video_file_tail'];
} else if ($video_detail['video_type'] == 1 || $video_detail['video_type'] == 4) {//youtube
    $video_file_tail = $video_detail['video_file_tail'];
} else {
    $video_file_tail = $video_detail['video_file_tail']; //normal
}

//print_r( $video_file_tail = $video_detail['video_file_tail']); die;

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
 function checkprice(input) {
   if (input.value == 0) {
     input.setCustomValidity('The price must not be zero.');
   } else {
     // input is fine -- reset the error message
     input.setCustomValidity('');
   }
 }
</script>


<script type="text/javascript">
    $('select[name=movie_view]').change(function () {
        var movie_view = $('select[name=movie_view]').val();
        if(movie_view == '1'){
            $(".price_per_video").show();
             $('#price_per_video').prop('required', true);
        }else{
            $(".price_per_video").hide();
        }
        
    });
</script>


<script type="text/javascript">
    //Player Script Start
    var video_type = "<?= $video_detail['video_type']; ?>";
    var video_url = "<?= $video_url; ?>";
    
    if (video_type == "1" || video_type == "4") {//youtube
       init_shaka_player("video_player_aws_new", video_url, 'youtube', '');
      // console.log("  -11111111111111111");
    } else if (video_type == "5" || video_type == "7"|| video_type == "8") {//aws
       // console.log("000000000000");
        let cfData = {
                name: <?= $video_detail['id']; ?>,
                url: ((video_type == 5 || video_type == "8")?"<?= $video_detail['file_url']; ?>":"<?=$video_detail['vdc_id']?>"),
                flag: ((video_type == 5 || video_type == "8")?1:0),
                type: 'video',
               
            }
      // course_id: 215
        $.ajax({
            type: 'POST',
            url: "<?= AUTH_PANEL_URL ?>live_module/channels/create_cloudfront_url",
            data: cfData,
            dataType: 'json',
            success: function (data) {
                if (data.data == 1) {
                    init_shaka_player("video_player_aws_new", data.url, 'm3u8', data.token);
                } else {
                    show_toast('error', 'Internal Error', 'Aws Url');
                }
            },
            error: function (data) {
                show_toast('error', 'Not able to generate url. Please try after sometime', 'Error');
            }
        });
    } else if (video_type == "6" || video_type == "0") {//jw
        init_shaka_player("video_player_aws_new", video_url, 'm3u8', '');
    }
    //Player Script End
</script>

<script type="text/javascript">
    //Player Script Start
    var video_type = "<?= $video_detail['video_type']; ?>";
    var video_file_tail = "<?= $video_file_tail; ?>";   
    if (video_type == "1" || video_type == "4") {//youtube
       init_shaka_player("video_player_aws_new_trail", video_file_tail, 'youtube', '');
    } else if (video_type == "5" || video_type == "8") {//aws
        let cfData = {
                name: <?= $video_detail['id']; ?>,
                url: ((video_type == 5 || video_type == "8")?"<?= $video_detail['video_file_tail']; ?>":"<?=$video_detail['vdc_tail_id']?>"),
                flag: ((video_type == 5 || video_type == "8")?1:0),
                type: 'video',
               
            }
            console.log(cfData);
      // course_id: 215
        $.ajax({
            type: 'POST',
            url: "<?= AUTH_PANEL_URL ?>live_module/channels/create_cloudfront_url",
            data: cfData,
            dataType: 'json',
            success: function (data) {
                if (data.data == 1) {
                    init_shaka_player("video_player_aws_new_trail", data.url, 'm3u8', data.token);
                } else {
                    show_toast('error', 'Internal Error', 'Aws Url');
                }
            },
            error: function (data) {
                show_toast('error', 'Not able to generate url. Please try after sometime', 'Error');
            }
        });
    } else if (video_type == "7" || video_type == "0") {//jw
        init_shaka_player("video_player_aws_new_trail", video_file_tail, 'm3u8', '');
    }
    //Player Script End
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
    $(document).ready(function(){
        var skip_int = document.getElementById("skip_intro");
 
 if (skip_int.checked == true){
    $(".seconds").show();
  } else{
    $(".seconds").hide();


  }

    });
    function skipIntro() {
  // Get the checkbox
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
        
        
        $(".Downloadclass").show();

    });

    $('select[name=video_type]').change(function () {
        $(".zoom_id").hide();
        $(".upload_video_cls").show();
        $("select[name=is_drm_protected]").val("0");
        var videoType = $('select[name=video_type]').val();
        $(".is_drm,.channel_div,.studio_div,.live_start_date,.cover_video").hide();
        $("select[name=open_with]").parent().hide();
        $("select[name=open_with]").val("1");
        $("#start_date").val("");
        $(".instant_upload").addClass("hide");
        $(".cover_video").text("File");
        $("#video_file").attr("type", "text");
        $(".videocript").hide();
        $(".Downloadclass").hide();
        $(".upload_video_cls_teil").hide();
        
        if (videoType == '0') {
            $(".is_drm").show();
            $(".cover_video").show();
            $(".file_title").text("Upload Video");
            $(".live_end_date").hide();
            $(".zoom_id").hide();
        } else if (videoType == '1') {//youtube
            $("select[name=open_with]").parent().show();
            $(".file_title").text(" Youtube URL");
            $(".file_title").show();
            $(".live_end_date").hide();
            $(".demo_percent").hide();
            $(".feedback").hide();
            $(".skip_intro").hide();
           $(".released_date").hide();
            $(".vod_chat").hide();
            $(".artists").hide();
            $(".multiplier").hide();
            $(".video_limit").hide();
            $(".video_type_file").hide();
            $(".floating_number").hide();
            $("#video_file").val('');
            $("input[name=thumbnail]").val('');
            $(".zoom_id").hide();
        } else if (videoType == '4') {//youtube live
            $(".live_start_date").show();
            $(".file_title").text("Enter Youtube video URL");
            $("select[name=open_with]").parent().show();
            $(".live_end_date").hide();
            $("#video_file").val('');
            $(".vod_chat").hide();
            $(".zoom_id").hide();
            $(".demo_percent").hide();
            $(".feedback").hide();
            $(".multiplier").hide();
            $(".video_limit").hide();
            $(".video_type_file").hide();
            $(".floating_number").hide();
            $("input[name=thumbnail]").val('');

        } else if (videoType == '5' || videoType == '8') {//aws live
            $(".is_drm").show();            
            $(".vod_url").hide();
            $(".upload_video_cls").hide();
            $(".live_start_date").show();
            $(".studio_div,.channel_div").show();
            $(".live_end_date").hide();
            $("#video_file").val('');
            $("input[name=thumbnail]").val('');
            $(".zoom_id").hide();
            $(".released_date").hide();
        } else if (videoType == '6') { //JW
            $(".file_title").text("Enter JW video URL");
            $("#video_file").val('');
            $("input[name=thumbnail]").val('');
            $(".live_end_date").hide();
            $(".zoom_id").hide();
        } else if (videoType == '7') { //JW         
            $(".vod_url").show();
            $(".videocript").show();
            $(".videocript_id").text("Enter video id");
            $("#video_file").val('');
            $("input[name=thumbnail]").val('');
            $(".Downloadclass").show();
            $(".live_end_date").hide();
            $(".zoom_id").hide();
        } else if(videoType == '9'){
            $(".vod_url").show();
            $(".vod_url").hide();
            $(".live_start_date").show();
            $(".instant_upload").hide();
            $(".input[name=thumbnail]").hide();
            $(".video_limit").hide();
            $("#video_file").val('');
            $(".feedback").hide();
            $(".video_thumbnail").hide();
             // $(".enter_class").hide();
             $(".demo_percent").hide();
            $(".video_type_file").hide();
            $(".live_end_date").show();
            $(".channel_list").show();
            $(".channel_div").hide();
            $(".zoom_id").show();

        }
    });
    function get_genres(){
        var videoType = $('select[name=cate_type]').val();
        var genres_type = $('#genres_type').val();
       
        $.ajax({
                 url: "<?= AUTH_PANEL_URL ?>file_manager/library/get_categorywise_geners/" + videoType,
                            type: "post",
                           // data: id:catType,
                            cache: false,
                            dataType: 'json',
                            contentType: false,
                            processData: false,

                            success: function(response) {
                                //console.log(response);
               if(response){

                $('#genres').find('option').not(':first').remove();
               // Add options
               
               $.each(response, function(index, data) {
                var selected ="";
                    if(data['id'] == genres_type)
                    {
                        selected = "selected";
                    }
                  $('#genres').append('<option value="' + data['id'] + '"' + selected +'>' + data['sub_category_name'] + '</option>');
               });
              

               }
               else{
                 alert("Genres not added")
               }
               
                
        
              
            } ,
            //  error: function (data) {
                            
            //             console.log("error while getting genres");
            //             //console.log(data);
            //             }
                   
            });
        
    }
    jQuery(document).ready(function () {
         if(($('select[name=video_type]').val())==1){
            $(".skip_intro").hide();
           $(".released_date").hide();
           $(".artists").hide();

         }
          
        get_genres();
        //$(".artists").hide();
        //$(".genres").hide();
        $(".web_season").hide();
        $(".web_genres").hide();
        $(".season_number").hide();
        $(".web_artists").hide();
        $(".tv_category").hide();
        $(".tv_artists").hide();
        $(".video_genres").hide();
        $(".video_artists").hide();
        $(".title_tail").hide(); 
        $(".playtime_tail").hide();
       // $(".upload_video_cls_teil").hide();
        $(".video_tailer_title").hide();
    });

    $('select[name=cate_type]').change(function () { 
        get_genres();
        var videoType = $('select[name=cate_type]').val();
        if (videoType == '1') {
            if( ($('select[name=video_type]').val())!= 1) {
                $(".artists").show();
            }
            $(".title_tail").show(); 
            $(".genres").show();
            $(".playtime_tail").show();
            $(".videocript_movie").show();
         //   $(".upload_video_cls_teil").show();
            $(".web_season").hide();
            $(".web_genres").hide();
            $(".season_number").hide();
            $(".web_artists").hide();
            $(".tv_category").hide();
            $(".tv_artists").hide();
            $(".video_genres").hide();
            $(".video_artists").hide();
            $(".video_tailer_title").show();
        } else if (videoType == '2') {//youtube
            $(".web_season").show();
            $(".video_tailer_title").hide();
            $(".web_genres").show();
            $(".season_number").show();
            $(".web_artists").show();
            $(".title_tail").hide(); 
          //  $(".upload_video_cls_teil").hide();
            $(".playtime_tail").hide();
            $(".videocript_movie").hide();
            $(".artists").hide();
            $(".genres").hide();
            $(".tv_category").hide();
            $(".tv_artists").hide();
            $(".video_genres").hide();
            $(".video_artists").hide();
        } else if (videoType == '3') {//youtube live
            $(".web_season").hide();
            $(".title_tail").hide(); 
            $(".web_genres").hide();
            $(".video_tailer_title").hide();
            $(".playtime_tail").hide();
          //  $(".upload_video_cls_teil").hide();
            $(".season_number").hide();
            $(".videocript_movie").hide();
            $(".web_artists").hide();
            $(".artists").hide();
            $(".tv_category").show();
            $(".tv_artists").show();
            $(".video_genres").hide();
            $(".video_artists").hide();
        } else if (videoType == '4') {//aws live
            $(".video_genres").show();
            $(".video_artists").show();
            $(".playtime_tail").hide();
            $(".title_tail").hide(); 
            $(".video_tailer_title").hide();
            $(".web_season").hide();
          //  $(".upload_video_cls_teil").hide();
            $(".web_genres").hide();
            $(".videocript_movie").hide();
            $(".season_number").hide();
            $(".web_artists").hide();
            $(".artists").hide();
            $(".tv_artists").hide();
            $(".genres").hide();
            $(".artists").hide();
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

    //Subject Search
    $('#subject_id').select2({
        placeholder: '--Select Subject--',
        theme: "meterial",
        width: 'resolve',
        ajax: {
            url: "<?= AUTH_PANEL_URL ?>course_product/subject_topics/subject_search?filter=yes",
            dataType: 'json',
            delay: 2000,
            processResults: function (data) {
                if (data.length > 0) {
                    return {
                        results: data
                    };
                }
            },
            cache: true
        }
    });

    $("#subject_id").change(function () {
        $("#topic_id").val('');
        var subjectId = $(this).val();
        $('#topic_id').select2({
            placeholder: 'Search Topic',
            theme: "material",
            width: "resolve",
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/subject_topics/topic_search?subject_id=" + subjectId + "&filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function (data) {
                    if (data.length > 0) {
                        return {
                            results: data
                        };
                    }
                },
                cache: true
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

    $('#start_date').datetimepicker({
        startDate: new Date()
    });

    $(document).ready(function () {
        $('.course_id').select2({
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
    });

    $("select[name=is_drm_protected]").change(function () {
        var video_type = $("select[name=video_type]").val();
            $("select[name=studio_id]").val("");
            $("select[name=channels]").val("");
        
    });

    // $("select[name=studio_id]").change(function () {
    //     var studioId = $(this).val();
    //     var channelList = JSON.parse(`<?php// json_encode($channels) ?>`);
    //     var channelHtml = '<option value="">Select Channel</option>';
    //     var is_drm_protected=$("select[name=is_drm_protected]").val();        
    //     $(channelList).each(function (idx, channel) {
    //             if (studioId == channel.studio_id) {
    //                 console.log(is_drm_protected);
    //                 if(is_drm_protected=="1" && (channel.output_b!='' || channel.output_c!='')){
    //                 channelHtml += '<option value="' + channel.id + '">' + channel.name + '</option>';
    //                 }
    //                 if(is_drm_protected=="0" && channel.output!=''){

    //                 channelHtml += '<option value="' + channel.id + '">' + channel.name + '</option>';
    //                 }
    //             }
    //     });
    //     $("#channels").html(channelHtml);
    // });

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
                //console.log("hello"); return false;
                if (data.type == "success") {
                     $("input[name=playtime]").val(data.data.duration);
                     $("input[name=video_file]").val(data.data.file_url_hls);
                     $("#title").val(data.data.title);
                     $("#videoid").val(data.data.original_url);
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
                     
                     //alert(data.data.file_url_hls);
                   //  init_shaka_player("video_player_aws_new", data.data.file_url_hls, 'm3u8', '');  
                                    
                }
                show_toast(data.type, data.title, data.message);
            }
        });
        }
    });



     $("#video_search_tail").click(function(){ 
        $(".is_drm").hide();
        $('.video_file_dash').hide();
        $('.play_via').hide();
        var vid=$('#videocript_tail_id').val();
        if(vid!=""){
            jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>file_manager/library/fetch_videocrypt_playlist",
            method: 'Post',
            dataType: 'json',
            data: {
                v_id: vid
            },
            success: function (data) {
                if (data.type == "success") {
                     $("input[name=playtime_tail]").val(data.data.duration);
                     $("input[name=video_file_tail]").val(data.data.file_url_hls);
                     $("#title_tail").val(data.data.title);
                     if(data.data.drm_dash_url!=""){
                         $(".is_drm_tail").show();
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
                }
                show_toast(data.type, data.title, data.message);
            }
        });
        }
    });

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
                    $('#video_type').val('');                    
                    $('#video_file').val('');  
                    $('#start_date').val('');
                    $("input[name=thumbnail]").val(''); 
                    $("select[name=open_with]").val('');
                    $('select[name=video_type]').val('');
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