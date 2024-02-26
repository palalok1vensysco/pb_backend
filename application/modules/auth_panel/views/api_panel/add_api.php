<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
</style>
<?php
$error = validation_errors();
$display = "display:none;";
$display_validity = "display:none;";
if (!empty($error)) {
    $display = "";
}
if (!empty($error) && isset($add_validity_display)) {
   $display_validity = "";
 
}
?>
<div class="col-lg-12 add_file_element" style="<?php echo $display; ?>">
    <section class="panel">
        <header class="panel-heading  text-white bg-dark">
            ADD API
        </header>
        <div class="panel-body">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label for="api_name">API Name</label>
                        <input type="text" class="form-control" name = "api_name" id="plan_name" placeholder="Enter Api Name" value="<?php echo set_value('api_name'); ?>">
                        <span class="text-danger"><?php echo form_error('api_name'); ?></span>
                    </div>
                    <div class="form-group col-lg-2">
                        <label>Select Method</label>
                        <select class="form-control selectpicker" name="method" id="method" data-live-search="true">
                            <option value="" >---select method---</option>
                            <option value="GET" <?= (set_value('method') == 'GET' ? 'selected' : '') ?> >GET</option>
                            <option value="POST"  <?= (set_value('method') == 'POST' ? 'selected' : '') ?> >POST</option>
                            <option value="PUT" <?= (set_value('method') == 'PUT' ? 'selected' : '') ?> >PUT</option>
                            <option value="PATCH" <?= (set_value('method') == 'PATCH' ? 'selected' : '') ?>  >PATCH</option>
                            <option value="DELETE" <?= (set_value('method') == 'DELETE' ? 'selected' : '') ?>  >DELETE</option>
                            <option value="COPY" <?= (set_value('method') == 'COPY' ? 'selected' : '') ?>  >COPY</option>
                            <option value="HEAD" <?= (set_value('method') == 'HEAD' ? 'selected' : '') ?>  >HEAD</option>
                            <option value="OPTIONS" <?= (set_value('method') == 'OPTIONS' ? 'selected' : '') ?>  >OPTIONS</option>
                            <option value="LINK" <?= (set_value('method') == 'LINK' ? 'selected' : '') ?>  >LINK</option>
                            <option value="UNLINK" <?= (set_value('method') == 'UNLINK' ? 'selected' : '') ?>  >UNLINK</option>
                            <option value="PURGE" <?= (set_value('method') == 'PURGE' ? 'selected' : '') ?>  >PURGE</option>
                            <option value="LOCK" <?= (set_value('method') == 'LOCK' ? 'selected' : '') ?>  >LOCK</option> 
                            <option value="UNLOCK" <?= (set_value('method') == 'UNLOCK' ? 'selected' : '') ?>  >UNLOCK</option> 
                            <option value="PROPFIND" <?= (set_value('method') == 'PROPFIND' ? 'selected' : '') ?>  >PROPFIND</option>
                            <option value="VIEW" <?= (set_value('method') == 'VIEW' ? 'selected' : '') ?>  >VIEW</option> 
                        </select> 
                        <span class="text-danger"><?php echo form_error('method'); ?></span>
                    </div>
                </div>
                <div class="row" style="margin-left: 0.5%;">
                    <button type="submit" class="btn btn-info btn-sm">Submit</button>
                    <button class="btn btn-danger btn-sm" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>
                </div>
            </form>
        </div>
    </section>
</div>

<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading  text-white bg-dark">
            <button onclick="$('.add_file_element').show('slow');" class="btn-success btn-xs btn pull-right"><i class="fa fa-plus"></i> Add Api</button>
            <?php // echo strtoupper($page);   ?> API(s) LIST
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Api Name</th>	 
                            <th>Method</th>
                            <th>Parameters</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="3"  class="search-input-text form-control"></th>
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
                           "order": [[ 0, "asc" ]],
                           "ajax":{
                               url :"$adminurl"+"api_panel/ajax_api_list/", // json datasource
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

												
                   } );
				   				   
               </script>              

EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>