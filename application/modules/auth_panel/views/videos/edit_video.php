<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
</style>
<?php
$mobile_menu_ids = (isset($video['mobile_menu_ids']) && !empty($video['mobile_menu_ids'])) ? explode(',', $video['mobile_menu_ids']) : [];
$android_tv_ids = (isset($video['android_tv_ids']) && !empty($video['android_tv_ids'])) ? explode(',', $video['android_tv_ids']) : [];
?>
<div>
    <div class="col-lg-8">
        <section class="panel">
            <header class="panel-heading custom-panel-heading">
                EDIT VIDEO
                <a href="<?= base_url('admin-panel/video-list'); ?>"><button class="pull-right btn btn-info btn-xs bold">Back to video list</button></a>
            </header>
            <?php if (isset($video)) {
                ?>
                <div class="panel-body custom-panel-body">
                    <form autocomplete="off"  role="form" method= "POST" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $video['id']; ?>" name="id">
                        <input type="hidden" value="<?php echo $video['video_url']; ?>" name="pre_video_url">
                        <input type="hidden" value="<?php echo $video['thumbnail_url']; ?>" name="pre_thumbnail_url">
                        <input type="hidden" value="<?php echo $video['thumbnail_url1']; ?>" name="pre_thumbnail_url1">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <video width="250" height="160" controls>
                                    <source src="<?php echo $video['video_url']; ?>" type="video/mp4">
                                </video>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="mobile_menu_ids">Select Mobile Application Category</label>
                                <select class="form-control selectpicker" name="mobile_menu_ids[]" id="category_ids" multiple="" data-live-search="true">  
                                    <?php
                                    foreach ($mobile_menu_category as $mobile_menu) {
                                        ?>
                                        <option value="<?= $mobile_menu['id'] ?>" <?= (in_array($mobile_menu['id'], $mobile_menu_ids)) ? 'selected' : '' ?> ><?= $mobile_menu['menu_title'] ?></option>
                                    <?php } ?>
                                </select>
                                <span class="custom-error"><?php echo form_error('mobile_menu_ids[]'); ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="android_tv_ids">Select Android TV Category</label>
                                <select class="form-control selectpicker" name="android_tv_ids[]" id="android_tv_ids" multiple="" data-live-search="true">  
                                    <?php
                                    foreach ($android_tv_category as $android_tv) {
                                        ?>
                                        <option value="<?= $android_tv['id'] ?>" <?= (in_array($android_tv['id'], $android_tv_ids)) ? 'selected' : '' ?> ><?= $android_tv['menu_title'] ?></option>
                                    <?php } ?>
                                </select>
                                <span class="custom-error"><?php echo form_error('android_tv_ids[]'); ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6" id="add_video">
                                <label for="addvideo">Change Video</label>
                                <input type="file" accept="video/mp4" name = "video_url" id="addvideo">
                                <small> Video format supported -: mp4</small>
                                <span class="custom-error"><?php echo form_error('video_url'); ?></span>
                            </div>
                        </div>

                        <div class="form-group" id="add_video">
                            <label for="addvideo">Youtube Link</label>
                            <input type="text" class="form-control" value="<?php echo $video['youtube_url']; ?>" name = "youtube_url" id="youtube_url">
                            <small> Enter Only Youtube video Id</small>

                        </div>

                        <div class="row">
                            <div class="form-group radio col-sm-6">
                                <label class="control-label" ><strong>Add to Sankirtan</strong></label>
                                <label>
                                    <input type="radio" name="is_sankirtan" value="1" <?php
                                    if (isset($video)) {
                                        if ($video['is_sankirtan'] == '1') {
                                            echo 'checked';
                                        }
                                    }
                                    ?>>
                                    Yes
                                </label>
                                <label>
                                    <input type="radio" name="is_sankirtan"  value="0" <?php
                                    if (isset($video)) {
                                        if ($video['is_sankirtan'] == '0') {
                                            echo 'checked';
                                        }
                                    }
                                    ?>>
                                    No
                                </label>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="control-label" ><strong>Most Popular</strong></label>
                                <label>
                                    <input type="checkbox"name="is_popular" value="1"  <?php
                                    if (isset($video)) {
                                        if ($video['is_popular'] == '1') {
                                            echo 'checked';
                                        }
                                    }
                                    ?>>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="video_title">Title</label>
                            <input type="text" class="form-control" value="<?php echo $video['video_title']; ?>" name = "video_title" id="video_title" placeholder="Enter Title">
                            <span class="custom-error"><?php echo form_error('video_title'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="author_name">Author Name</label>
                            <input type="text" class="form-control" value="<?php echo $video['author_name']; ?>" name = "author_name" id="author_name" placeholder="Enter Author Name">
                            <span class="custom-error"><?php echo form_error('author_name'); ?></span>
                        </div>

                        <div class="form-group col-sm-6" id="add_thumbnail" >
                            <img src="<?php echo $video['thumbnail_url']; ?>" class="img-thumbnail" style="width:150px;height:120px;">
                            <label for="thumbnail_file">Home Thumbnail</label>
                            <input type="file" accept=".jpg,.png,.jpeg" name = "thumbnail_url" id="thumbnail_file">
                            <small> Image Size -: 280X150px</small>
                            <span class="custom-error"><?php echo form_error('thumbnail_url'); ?></span>
                        </div>

                        <div class="form-group col-sm-6" id="add_thumbnail1" >
                            <img src="<?php echo $video['thumbnail_url1']; ?>" class="img-thumbnail" style="width:150px;height:120px;">
                            <label for="thumbnail_file">View Page Thumbnail</label>
                            <input type="file" accept=".jpg,.png,.jpeg" name = "thumbnail_url1" id="thumbnail_file1">
                            <small> Image Size -: 170X90px</small>
                            <span class="custom-error"><?php echo form_error('thumbnail_url1'); ?></span>
                        </div>
                        <!--  <div class="form-group">
                            <label for="video_desc">Description</label>
                            <textarea class="form-control" id="video_desc" name = "video_desc" rows="3" placeholder="Enetr Description"><?php //echo $video['video_desc'];      ?></textarea>
                            <span class="custom-error"><?php //echo form_error('video_desc');     ?></span>
                         </div> -->

                        <div class="form-group">
                            <label class="col-sm-12 control-label col-sm-2">Description</label>
                            <div class="col-sm-12">
                                <textarea placeholder="Enter Description" class="form-control ckeditor" name="video_desc" rows="6"><?php echo $video['video_desc']; ?></textarea>
                            </div>
                            <span class="custom-error"><?php echo form_error('video_desc'); ?></span>
                        </div>
                        <div class="clearfix"></div>
                        <br>

                        <div class="form-group">
                            <label for="published-date-user">Published Date</label>
                            <div data-date-format="yyyy-mm-dd" data-date="2013/12/1">
                                <input class="form-control" type="text" name="published_date" id="published-date-user" placeholder=" Select Published Date" value="<?= date("d-m-Y h:i:s A", $video['published_date'] / 1000);?>">
                            </div>
                            <span class="custom-error"><?php echo form_error('published_date'); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="maincategory">Video Category</label>
                            <select class="form-control input-sm m-bot15 selectpicker" name = "category[]" id="maincategory" multiple="" data-live-search="true">
                                <?php
                                if (isset($category)) {
                                    foreach ($category as $cat) {
                                        ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php
                                        $cats = explode(',', $video['category']);
                                        if (in_array($cat['id'], $cats)) {
                                            echo "selected";
                                        }
                                        ?>>
                                                    <?php echo $cat['category_name']; ?>

                                        </option>
                                        <?php
                                    }
                                }
                                ?>

                            </select>
                            <span class="custom-error"><?php echo form_error('category'); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="rlguru">Select Related Guru</label>
                            <select class="form-control input-sm m-bot15 selectpicker" name = "related_guru[]" id="rlguru" multiple=""  data-live-search="true">
                                <?php
                                if (isset($guru)) {
                                    foreach ($guru as $guru_name) {
                                        ?>
                                        <option value="<?php echo $guru_name['id']; ?>" <?php
                                        $gurus = explode(',', $video['related_guru']);
                                        if (in_array($guru_name['id'], $gurus)) {
                                            echo "selected";
                                        }
                                        ?>><?php echo $guru_name['name']; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>

                            </select>
                            <span class="custom-error"><?php echo form_error('related_guru'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="tags">Add Tags</label>
                            <textarea class="form-control" id="tags" name = "tags" rows="2" placeholder="Enter Video Tags for Related videos"><?php echo $video['tags']; ?></textarea>
                            <span class="custom-error"><?php echo form_error('tags'); ?></span>
                        </div>

                        <!--<div class="form-group hide ">
                           <label for="startdate">Start Date</label>
                           <input type="text" class="form-control dpd1" name = "start_date" id="startdate" placeholder="Enter Start Date">
                           <span class="custom-error"><?php //echo form_error('start_date');    ?></span>
                        </div>
                        <div class="form-group hide ">
                           <label for="enddate">End Date</label>
                           <input type="text" class="form-control dpd2" name = "end_date" id="enddate" placeholder="Enter End Date">
                           <span class="custom-error"><?php //echo form_error('end_date');    ?></span>
                        </div> -->


                        <button type="submit" class="btn btn-info btn-sm">Update</button>
                        <a href="<?= base_url('admin-panel/video-list') ?>">
                            <button class="btn btn-danger btn-sm" type="button" >Cancel</button>
                        </a>
                    </form>
                </div>
            <?php } ?>
        </section>
    </div>
    <div class="clearfix"></div>
</div>
<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
         <script type="text/javascript" language="javascript" >
 $('#published-date-user').datetimepicker({
                                                    format: 'd-mm-yyyy H:i:ss P',
                                                   autoclose: true
                                                  });
              </script>

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
