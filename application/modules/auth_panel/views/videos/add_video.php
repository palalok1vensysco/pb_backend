<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
</style>

<?php
$mobile_menu_ids = set_value('mobile_menu_ids');
$android_tv_ids = set_value('android_tv_ids');
$form_cat_ids = set_value('category');
$form_guru_ids = set_value('related_guru');
$form_day_ids = set_value('days');
?>
<div>
    <div class="col-lg-8">
        <section class="panel">
            <header class="panel-heading custom-panel-heading">
                ADD VIDEO
            </header>
            <div class="panel-body custom-panel-body">
                <form autocomplete="off"  role="form" method= "POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-md-4" id="add_video">
                            <label for="addvideo">Add Video</label>
                            <input type="file" accept="video/mp4" name = "video_url" id="addvideo">
                            <small> Video format supported -: mp4</small>
                            <span class="custom-error"><?php echo form_error('video_url'); ?></span>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="mobile_menu_ids">Select Mobile Application Category</label>
                            <select class="form-control selectpicker" name="mobile_menu_ids[]" id="category_ids" multiple="" data-live-search="true"> 
                                <?php
                                if (isset($mobile_menu_ids) && !empty($mobile_menu_ids)) {
                                    foreach ($mobile_menu_category as $mobile_menu) {
                                        ?>
                                        <option value="<?= $mobile_menu['id'] ?>" <?= (in_array($mobile_menu['id'], $mobile_menu_ids)) ? 'selected' : '' ?>><?= $mobile_menu['menu_title'] ?></option>
                                        <?php
                                    }
                                } else {
                                    foreach ($mobile_menu_category as $mobile_menu) {
                                        ?>
                                        <option value="<?= $mobile_menu['id'] ?>" ><?= $mobile_menu['menu_title'] ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select> 
                            <span class="custom-error"><?php echo form_error('mobile_menu_ids[]'); ?></span>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="android_tv_ids">Select Android TV Category</label>
                            <select class="form-control selectpicker" name="android_tv_ids[]" id="android_tv_ids" multiple="" data-live-search="true"> 
                                <?php
                                if (isset($android_tv_ids) && !empty($android_tv_ids)) {
                                    foreach ($android_tv_category as $android_tv) {
                                        ?>
                                        <option value="<?= $android_tv['id'] ?>" <?= (in_array($android_tv['id'], $android_tv_ids)) ? 'selected' : '' ?>><?= $android_tv['menu_title'] ?></option>
                                        <?php
                                    }
                                } else {
                                    foreach ($android_tv_category as $android_tv) {
                                        ?>
                                        <option value="<?= $android_tv['id'] ?>" ><?= $android_tv['menu_title'] ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select> 
                            <span class="custom-error"><?php echo form_error('android_tv_ids[]'); ?></span>
                        </div>
                    </div>
                    <div class="form-group" id="add_video">
                        <label for="addvideo">Youtube Link</label>
                        <input type="text" class="form-control"  name = "youtube_url" id="youtube_url"  value="<?php echo set_value('youtube_url'); ?>">
                        <small> Enter Only Youtube video Id</small>

                    </div>
                    <div class="row">
                        <div class="form-group radio col-sm-6">
                            <label class="control-label" ><strong>Add to Sankirtan</strong></label>
                            <label>
                                <input type="radio" name="is_sankirtan" value="1" <?php
                                if (set_value('is_sankirtan') == 1) {
                                    echo 'checked';
                                }
                                ?>>
                                Yes
                            </label>
                            <label>
                                <input type="radio" name="is_sankirtan"  value="0" <?php
                                if (set_value('is_sankirtan') == 0) {
                                    echo 'checked';
                                }
                                ?>>
                                No
                            </label>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="control-label" ><strong>Most Popular</strong></label>
                            <label>
                                <input type="checkbox"name="is_popular" value="1" <?php
                                if (set_value('is_popular') == 1) {
                                    echo 'checked';
                                }
                                ?>>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="video_title">Title</label>
                        <input type="text" class="form-control" name = "video_title" id="video_title" placeholder="Enter Title" value="<?php echo set_value('video_title'); ?>">
                        <span class="custom-error"><?php echo form_error('video_title'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="author_name">Author Name</label>
                        <input type="text" class="form-control" name = "author_name" id="author_name" placeholder="Enter Author Name" value="<?php echo set_value('author_name'); ?>">
                        <span class="custom-error"><?php echo form_error('author_name'); ?></span>
                    </div>
                    <div class="form-group col-sm-6" id="add_thumbnail" >
                        <label for="thumbnail_file">Home Thumbnail</label>
                        <input type="file" accept=".jpg,.png,.jpeg" name = "thumbnail_url" id="thumbnail_file">
                        <small> Image Size -: 280X150px</small>
                        <span class="custom-error"><?php echo form_error('thumbnail_url'); ?></span>
                    </div>
                    <!--  <div class="form-group col-sm-6" id="add_thumbnail1" >
                       <label for="thumbnail_file">View Page Thumbnail</label>
                       <input type="file" accept=".jpg,.png,.jpeg" name = "thumbnail_url1" id="thumbnail_file1">
                        <small> Image Size -: 170X90px</small>
                       <span class="custom-error"><?php echo form_error('thumbnail_url1'); ?></span>
                    </div> -->
                    <!-- <div class="form-group">
                       <label for="video_desc">Description</label>
                       <textarea class="form-control" id="video_desc" name = "video_desc" rows="3" placeholder="Enter Description"><?php //echo set_value('video_desc');   ?></textarea>
                       <span class="custom-error"><?php //echo form_error('video_desc');   ?></span>
                    </div> -->
                    <div class="form-group">
                        <label class="col-sm-12 control-label col-sm-2">Description</label>
                        <div class="col-sm-12">
                            <textarea placeholder="Enter Description" class="form-control ckeditor" name="video_desc" rows="6"><?php echo set_value('video_desc'); ?></textarea>
                        </div>
                        <span class="custom-error"><?php echo form_error('video_desc'); ?></span>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="form-group  col-sm-6">
                        <label for="published-date-user">Published Date</label>
                        <div data-date-format="yyyy-mm-dd" data-date="2013/12/1">
                            <input class="form-control" type="text" name="published_date" id="published-date-user" placeholder=" Select Published Date" value="<?php echo set_value('published_date'); ?>">
                        </div>
                        <span class="custom-error"><?php echo form_error('published_date'); ?></span>
                    </div>
                    <div class="form-group  col-sm-6">
                        <label for="days">Published Day</label>
                        <select class="form-control input-sm m-bot15 selectpicker" name = "days[]" id="days" multiple="" data-live-search="true">
                            <?php
                            if (isset($form_day_ids) && !empty($form_day_ids)) {
                                foreach ($week_days as $day) {
                                    ?>
                                    <option value="<?= $day['id'] ?>" <?= (in_array($day['id'], $form_day_ids)) ? 'selected' : '' ?>><?= $day['day_name'] ?></option>
                                    <?php
                                }
                            } else {
                                foreach ($week_days as $day) {
                                    ?>
                                    <option value="<?= $day['id'] ?>" ><?= $day['day_name']; ?></option>
                                    <?php
                                }
                            }
                            ?>

                        </select>
                        <span class="custom-error"><?php echo form_error('days'); ?></span>
                    </div>
                    
                    <!--multi slecet-->
                    <div class="form-group">
                        <label for="maincategory">Video Category</label>
                        <select class="form-control input-sm m-bot15 selectpicker" name = "category[]" id="maincategory" multiple="" data-live-search="true">
                            <?php
                            if (isset($form_cat_ids) && !empty($form_cat_ids)) {
                                foreach ($category as $cat) {
                                    ?>
                                    <option value="<?= $cat['id'] ?>" <?= (in_array($cat['id'], $form_cat_ids)) ? 'selected' : '' ?>><?= $cat['category_name'] ?></option>
                                    <?php
                                }
                            } else {
                                foreach ($category as $cat) {
                                    ?>
                                    <option value="<?= $cat['id'] ?>" ><?= $cat['category_name']; ?></option>
                                    <?php
                                }
                            }
                            ?>

                        </select>

                        <span class="custom-error"><?php echo form_error('category'); ?></span>
                    </div>

                    <div class="form-group">
                        <label for="rlguru">Select Related Guru</label>
                        <select class="form-control input-sm m-bot15 selectpicker" name = "related_guru[]" id="rlguru" multiple="" data-live-search="true">
                            <?php
//                            if (isset($guru)) {
//                                foreach ($guru as $guru_name) {
//                                    
                            ?>
<!--                                    <option value="//<?php //echo $guru_name['id']; ?>" <?php //echo ($guru_name['id'] == 35 ? 'selected' : '') ?>><?php //echo $guru_name['name']; ?></option>-->
                            <?php
//                                }
//                            }
                            ?>

                            <?php
                            if (isset($form_guru_ids) && !empty($form_guru_ids)) {
                                foreach ($guru as $guru_name) {
                                    ?>
                                    <option value="<?= $guru_name['id'] ?>" <?= (in_array($guru_name['id'], $form_guru_ids)) ? 'selected' : '' ?>><?= $guru_name['name'] ?></option>
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
                    <div class="form-group">
                        <label for="tags">Add Tags</label>
                        <textarea class="form-control" id="tags" name = "tags" rows="2" placeholder="Enter Video Tags for Related videos"><?php echo set_value('tags'); ?></textarea>
                        <span class="custom-error"><?php echo form_error('tags'); ?></span>
                    </div>

                    <!--<div class="form-group hide ">
                       <label for="startdate">Start Date</label>
                       <input type="text" class="form-control dpd1" name = "start_date" id="startdate" placeholder="Enter Start Date">
                       <span class="custom-error"><?php //echo form_error('start_date');   ?></span>
                    </div>
                    <div class="form-group hide ">
                       <label for="enddate">End Date</label>
                       <input type="text" class="form-control dpd2" name = "end_date" id="enddate" placeholder="Enter End Date">
                       <span class="custom-error"><?php //echo form_error('end_date');   ?></span>
                    </div> -->


                    <button type="submit" class="btn btn-info btn-sm">Submit</button>
                </form>
            </div>
        </section>
    </div>
    <div class="clearfix"></div>
</div>
<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
              <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                        $( function() {
                    $( ".dpd1" ).datetimepicker();
                    $( ".dpd2" ).datetimepicker();
                     }); // datepicker closed
               </script>
              <script type="text/javascript" charset="utf8">

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
