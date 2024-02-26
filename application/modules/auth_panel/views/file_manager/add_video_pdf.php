<?php
/*$error = validation_errors();
$display = "display:none;";
if (!empty($error)) {
    $display = "";
}*/
?>
<div class="col-lg-6 add_file_element">
    <section class="panel">
        <header class="panel-heading">
            Add PDF 
        </header>
        <div class="panel-body">
            <form method="post" enctype="multipart/form-data">   
                <div class="form-group">
                    <label>Select PDF</label>
                    <select name="resource_ids[]" class="form-control input-xs selectpicker" data-live-search="true" multiple="">
                        <?php 
                        $i=0;
                            foreach($resource_list as $key =>$resource){
                                  /// echo '<option value="'.$resource->id.'">'.$resource->title.'</option>';
                              ?>
                              <option value="<?= $resource->id ?>" <?php if($key==0){ echo "selected"; } ?> >  <?= $resource->title?> </option>
                          <?php  $i++; }
                        ?>
                    </select>
                    <span class="error bold"></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile">Pdf Type</label>
                    <input type="radio" name = "is_downloadable" value="0" checked=""> Should Open
                    <input type="radio" name = "is_downloadable" value="1"> Should Download
                </div>  
                <button class="btn btn-info btn-sm"  type="submit" >Upload</button>
                <button class="btn btn-danger btn-sm" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>
            </form>
        </div>
    </section>
</div>
<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading">
            <button onclick="$('.add_file_element').show('slow');" class="btn-success btn-xs btn pull-right"><i class="fa fa-plus"></i> Add</button>
            <?php // echo strtoupper($page);  ?> PDF(s) LIST
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title </th>
                            <th>Courses</th>
                            <th>Thumbnail</th>
                            <th>Pdf </th>
                            <th>Type</th>
                            <th>Page Count</th> 
                            <th>Created By</th> 
                            <th>Date</th>
                            <th>Action </th> 	 
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th><input type="text" data-column="0"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><input type="text" data-column="7"  class="search-input-text form-control"></th>
                            <th></th>
                            <th></th> 
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>
<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?=AUTH_ASSETS?>js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" > 
    jQuery(document).ready(function() {
        <?php  if(isset($video_id) && !empty($video_id)){  ?>
        $(".add_file_element").show();
        <?php } else { ?>
       $(".add_file_element").hide();
        <?php } ?>
            
        var video_id = "<?=$video_id;?>";
        var table = 'all-user-grid';
        if(video_id > 0){
            var dataTable = jQuery("#"+table).DataTable( {
                "processing": true,
                "pageLength": 15,
                "lengthMenu": [[15, 25, 50], [15, 25, 50]],
                "serverSide": true,
                "order": [[ 0, "desc" ]],
                "ajax":{
                    url :"<?=AUTH_PANEL_URL?>file_manager/library/add_video_library_pdf_list/?video_id="+video_id, // json datasource
                    type: "post",  // method  , by default get
                    error: function(){  // error handling
                        jQuery("."+table+"-error").html("");
                        jQuery("#"+table+"_processing").css("display","none");
                    }
                }
            });
            jQuery("#"+table+"_filter").css("display","none");
            bind_table_search(dataTable, table, 'keyup');
            bind_table_search(dataTable, table, 'change');											
        }
    });

</script>