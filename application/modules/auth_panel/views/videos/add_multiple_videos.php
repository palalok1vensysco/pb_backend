<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<?php
//echo "<pre>";print_r($videos);die; 
if (isset($validation_error)) {
    $validation = $validation_error;
} else {
    $validation = 0;
}
?>

<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            ADD MULTIPLE VIDEOS
            <span class="tools pull-right">
                <a href="javascript:;" id="add_permission_group_down" class=""><b class="btn-sm bold  btn btn-success"> <span class="button_text"></span></b></a>
            </span>
        </header>
        <?php
        if (form_error('id[]') != '' || form_error('video_title') != '') {
            $css_value = 'display:block';
        } else {
            $css_value = 'display:none';
        }
        ?>
        <div class="panel-body add_permission_body" style="<?php echo $css_value; ?>">
            <form role="form" method="post" action=""  enctype="multipart/form-data">
<!--                <div class="form-group col-md-6 btncolorchange">
                    <label>Select Category</label>
                    <select class="form-control selectpicker" name="video_id" data-live-search="true" >
                        <option value="">Select</option>
                        <?php
                        foreach ($categories as $category) {
                            $sel = '';
                            echo '<option ' . $sel . '  value="' . $category['id'] . '" >' . $category['category_name'] . '</option>';
                        }
                        ?>
                    </select>  
                    <span class="text-danger"><?php echo form_error('video_id'); ?></span>
                </div>-->
                <div class="form-group col-md-6 btncolorchange">
                    <label>Select Video</label>
                    <select class="form-control selectpicker" name="video_id" data-live-search="true" >
                        <option value="">Select</option>
                        <?php
                        foreach ($videos as $video) {
                            $sel = '';
                            echo '<option ' . $sel . '  value="' . $video['id'] . '" >' . $video['video_title'] . '</option>';
                        }
                        ?>
                    </select>  
                    <span class="text-danger"><?php echo form_error('video_id'); ?></span>
                </div>
                <div class="form-group col-md-6 btncolorchange">
                    <label>Select Days</label>
                    <select class="form-control days" name="days" data-live-search="true">
                        <option value="">Select</option>
                        <?php for ($i = 1; $i <= 10; $i++) { ?>
                            <option value="<?= $i ?>"><?= $i ?></option> 
                        <?php } ?>
                    </select>
                    <span class="text-danger"><?php echo form_error('days'); ?></span>
                </div>

                <div id="video_section"></div>

                <div class="form-group col-md-9">
                    <button class="btn btn-info btn-sm "  type="submit" name="save" >Save</button>
                    <!--                    <button class="btn btn-danger btn-sm button_text"type="button">Cancel</button>-->
                </div>

            </form>
        </div>
    </section>
</div>




<div class="col-sm-12 data_table">
    <section class="panel">
        <header class="panel-heading">
            MULTIPLE VIDEO(s) LIST
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
            </span>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="backend-user-grid">
                    <thead>
                        <tr>
                            <th># </th>
                            <th>Title</th>
                            <th>Author Name</th>
                            <th>Day's</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="form-control search-input-text"></th>
                            <th><input type="text" data-column="2"  class="form-control search-input-text"></th>
                            <th><input type="text" data-column="3"  class="form-control search-input-text"></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>

<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
             
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                jQuery(document).ready(function() {
                         $(".button_text").text("+ Add");
                		$('form')[0].reset();
                	    var table = 'backend-user-grid';
                        var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                           "pageLength": 50,
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"videos/Video_control/ajax_get_videos_by_category", // json datasource
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

                    $('.group_permision_all').click(function(event) {
                        var ids =  $(this).parent().next('div').attr('id'); 
                        if(this.checked) {
                                // Iterate each checkbox
                               $('#'+ids+' :checkbox').each(function() {
                                    this.checked = true;                        
                                });
                        } else {
                              // Iterate each checkbox
                             $('#'+ids+' :checkbox').each(function() {
                                  this.checked = false;                        
                              });
                        }
                    });

                    $('a').click(function() {
                    	var id = $(this).attr('id');
                    	if(id == 'add_permission_group_down') {
                                $(".button_text").text("Hide");
                    		$('.add_permission_body').css('display','block');
                                $('.data_table').css('display','none');
                    		$(this).attr('id','add_permission_group_up');
                    	} else if(id == 'add_permission_group_up') {
                                $(".button_text").text("+ Add");
                    		$('.add_permission_body').css('display','none');
                    		$(this).attr('id','add_permission_group_down');
                                $('.data_table').css('display','block');
                    	}
                    })

                });
               </script>
        <script>
         $('.days').change(function(){
             var days = $(".days option:selected").val();
             var video_div='';
            for(var i=1;i<=days;i++){
                    video_div +='<div class="form-group col-sm-12"> <fieldset><legend>Day-'+i+'</legend>';
                    video_div +=' <div class="form-group col-sm-12"><label for="video_title_'+i+'">Title</label><input type="hidden" class="form-control" name = "day_'+i+'" value="'+i+'"><input type="text" class="form-control" name = "video_title_'+i+'" id="video_title_'+i+'" placeholder="Enter Title"></div>';
                    video_div +='<div class="form-group col-sm-6" id="add_thumbnail_'+i+'" ><label for="thumbnail_file_'+i+'">Thumbnail</label><input type="file" accept=".jpg,.png,.jpeg" name = "thumbnail_url_'+i+'" id="thumbnail_file_'+i+'" ><small> Image Size -: 280X150px</small></div>';
                    video_div +='<div class="form-group col-sm-6" id="add_video_'+i+'"><label for="addvideo_'+i+'">Add Video</label><input type="file" accept="video/mp4" name = "video_url_'+i+'" id="addvideo_'+i+'"><small> Video format supported -: mp4</small></div>';
                    video_div +='<div class="form-group col-sm-12" id="add_video_'+i+'"><label for="addvideo_'+i+'">Youtube Link</label><input type="text" class="form-control"  name = "youtube_url_'+i+'" id="youtube_url_'+i+'"><small> Enter Only Youtube video Id</small></div>';
                    video_div +=' <fieldset></div>';
                    $('#video_section').html(video_div);
            }
           });
        
        
        
        </script>
        <script>
        $(document).ready(function() {
                var validation_error="$validation";
                if(validation_error==1){
                    $(".button_text").text("Hide");
                    $('.data_table').css('display','none');
                    $('.add_permission_body').css('display','block');
                }
          });
        </script>
        

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
