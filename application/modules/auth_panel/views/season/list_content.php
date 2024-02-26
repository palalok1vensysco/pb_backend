<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<div class="col-sm-12">
	<section class="panel">
		<header class="panel-heading text-white bg-dark">
		<?//php echo strtoupper($page); ?>Content List
		</header>
		<div class="panel-body">
		<div class="adv-table">
		<table  class="display table table-bordered table-striped" id="all-video-grid">
  		<thead>
    		<tr>
          <th>#</th>
          <th>Category</th>
          <th>Media Type</th>
          <th>Title</th>
          <th>Thumbnail</th>
          <th>Status</th>
          <th>Creation time</th>
          <th>Action</th>
    		</tr>
  		</thead>
      <thead>
          <tr>
              <th></th>
              <th></th>
              <th><select class="search-input-select form-control input-xs" data-column="0">
                <option value="">All</option>
                <option value="0">Video</option>
                <option value="1">Audio</option>
            </select></th>
              <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
              <th></th>
              <th></th>
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
              <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                   jQuery(document).ready(function() {
                       var table = 'all-video-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                            "pageLength": 15,
                            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
                           "serverSide": true,
                           'ordering' : false,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"season/SeasonController/ajax_list_content/", // json datasource
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
						// Re-draw the table when the a date range filter changes
                        $('.date-range-filter').change(function() {
                            if($('#min-date-video-list').val() !="" && $('#max-date-video-list').val() != "" ){
                                var dates = $('#min-date-video-list').val()+','+$('#max-date-video-list').val();
                                dataTable.columns(8).search(dates).draw();
                            } 
                            if($('#min-date-video-list').val() =="" || $('#max-date-video-list').val() == "" ){
                                var dates = "";
                                dataTable.columns(8).search(dates).draw();
                            }  
                        }); 
                   } );
				   
				   $('#min-date-video-list').datepicker({
				  		format: 'dd-mm-yyyy',
						autoclose: true
						
					});
					$('#max-date-video-list').datepicker({
						format: 'dd-mm-yyyy',
						autoclose: true
						
					});
               </script>

EOD;

	echo modules::run('auth_panel/template/add_custum_js',$custum_js );
?>
