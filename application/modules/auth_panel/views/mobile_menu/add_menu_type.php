<style>
    .panel-heading {
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    padding: 10px;
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<?php
$error = validation_errors();
$display = "display:none;";
if (!empty($error)) {
    $display = "";
}
?>
<div class="col-lg-6 add_file_element" style="<?php echo $display; ?>">
    <section class="panel">
        <header class="panel-heading">
            ADD MENU TYPE
        </header>
        <div class="panel-body">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="type">Type</label>
                    <input type="text" class="form-control" name = "type" id="type" placeholder="Enter Type">
                    <span class="text-danger"><?php echo form_error('type'); ?></span>
                </div>
                <button type="submit" class="btn btn-info btn-sm">Submit</button>
                <button class="btn btn-danger btn-sm" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>
            </form>
        </div>
    </section>
</div>

<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading">
<!--            <button onclick="$('.add_file_element').show('slow');" class="btn-success btn-xs btn pull-right"><i class="fa fa-plus"></i> Add</button>-->
            <?php // echo strtoupper($page);  ?> MENU TYPE LIST
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>	 
<!--                            <th>Action </th> 	 -->
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
<!--                            <th></th> -->
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
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"mobile_menu/ajax_menu_type_list/", // json datasource
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