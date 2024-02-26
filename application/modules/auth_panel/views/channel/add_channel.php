<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<div>
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading custom-panel-heading">
                ADD/EDIT CHANNEL
                <a href="<?= base_url('admin-panel/channel-list'); ?>"><button class="pull-right btn btn-info btn-xs bold">Back to channel list</button></a>
            </header>
            <div class="panel-body custom-panel-body">
                <form autocomplete="off"  role="form" method= "POST" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="name">Channel Name</label>
                        <input type="text" class="form-control" name = "name" id="name" placeholder="Enter Channel Name" value="<?php echo (isset($result) && !empty($result['name']) ? $result['name'] : ""); ?>">
                        <span class="custom-error"><?php echo form_error('name'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="channel_url">Channel Url</label>
                        <input type="text" class="form-control" name = "channel_url" id="url" placeholder="Enter Channel Url" value="<?php echo (isset($result) && !empty($result['channel_url']) ? $result['channel_url'] : ""); ?>" >
                        <span class="custom-error"><?php echo form_error('channel_url'); ?></span>
                    </div>

                    <div class="form-group col-sm-12" id="add_thumbnail1" >
                        <label for="image">Channel Thumbnail</label>
                        <input type="file" accept="" name = "image" id="image" accept=".jpg,.png,.jpeg">
                        <?php if (isset($result)) {?>
                        <img id="image" src="<?php if (isset($result) && !empty($result['image'])) {echo $result['image'];}?>" width="180px" >
                        <?php }?>
<!--                        <small> Image Size -: 170X90px</small>-->
                        <span class="custom-error"><?php echo form_error('image'); ?></span>
                    </div>

                   <!--  <div class="form-group">
                        <label class="col-sm-12 control-label col-sm-2">Description</label>
                        <div class="col-sm-12">
                            <textarea placeholder="Enter Description" class="form-control ckeditor" name="description" rows="6"><?php echo (isset($result) && !empty($result['description']) ? $result['description'] : ""); ?></textarea>
                        </div>
                        <span class="custom-error"><?php echo form_error('video_desc'); ?></span>
                    </div> -->
                    <div class="clearfix"></div>
                    <br>

                    <button type="submit" class="btn btn-info"><?=(isset($result) ? "Update Channel" : "Add Channel")?></button>
                </form>
            </div>
        </section>
    </div>
    <div class="clearfix"></div>
</div>
<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
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

                     $('#published-date-user').datepicker({
              format: 'yyyy-mm-dd',
            autoclose: true

          });
              </script>
EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
