<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://thevectorlab.net/flatlab/assets/select2/css/select2.min.css"/>
<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading">
            <?php echo strtoupper($page); ?> USER(s) LIST
            <span class="tools pull-right">
                <form id="download_content_csv" method="post" action=""  >
                    <button class="btn btn-danger margin-right btn-xs"> 
                        <i class="fa fa-file" aria-hidden="true"></i>
                        Download CSV 
                    </button>
                    <!--input name="download_pdf" class="btn btn-info margin-right btn-xs" value="Download PDF" type="submit"-->
                    <textarea style="display:none;" name="input_json"></textarea>
                </form>
            </span>
        </header>
        <div class="panel-body">
            <div class="adv-table">
<!--                <div class="col-md-6 pull-right">
                    <div data-date-format="dd-mm-yyyy" data-date="13/07/2013" class="input-group ">
                        <div  class="input-group-addon">From</div>
                        <input type="text" id="min-date-user" class="form-control date-range-filter input-sm course_start_date"  placeholder="">

                        <div class="input-group-addon">to</div>

                        <input type="text" id="max-date-user" class="form-control date-range-filter input-sm course_end_date"  placeholder="">

                    </div>
                </div>-->
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User name </th>
                            <th>Email </th>
                            <th>Country</th>
                            <th>Mobile </th>
                             <th>Subscription </th>
                            <th>Status </th>
                            <!--th>Whatsapp Status</th-->
                            <th>Creation time </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="3"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="4"  class="search-input-text form-control"></th>
                            <th><select data-column="5"  class="form-control search-input-select">
                                    <option value="">All</option>
                                    <option value="1">Paid</option>
                                    <option value="0">Free</option>
                                </select></th>
                            <th></th>
                            <!--th><select data-column="6"  class="form-control search-input-select">
                                    <option value="">All</option>
                                    <option value="1">success</option>
                                    <option value="0">failed</option>

                                </select></th-->
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
$query_string = "";
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
              
               <script type="text/javascript" language="javascript" >
               
             
               
               
                var all_user_all = "$adminurl"+"web_user/ajax_all_user_login_recoard/";
                //var all_user_csv = "$adminurl"+"web_user/get_request_for_csv_download/$device_type";

                   jQuery(document).ready(function() {
                       var table = 'all-user-grid';
                       var dataTable_user = jQuery("#"+table).DataTable( {
                           "processing": true,
                            "pageLength": 15,
                            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :all_user_all, // json datasource
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
                           dataTable_user.columns(i).search(v).draw();
                       } );
                        $('.search-input-select').on( 'change', function () {   // for select box
                            var i =$(this).attr('data-column');
                            var v =$(this).val();
                            dataTable_user.columns(i).search(v).draw();
                        } );
						// Re-draw the table when the a date range filter changes
                        $('.date-range-filter').change(function() {
                            if($('#min-date-user').val() !="" && $('#max-date-user').val() != "" ){
                                var dates = $('#min-date-user').val()+','+$('#max-date-user').val();
                                dataTable_user.columns(5).search(dates).draw();
                            }    
                        }); 
                        $( document ).ajaxComplete(function( event, xhr, settings ) {
                            if ( settings.url === all_user_all ) {
                               var obj = jQuery.parseJSON(xhr.responseText);
                               var read =  obj.posted_data;

                              $('#download_content_csv').attr('action',all_user_csv);
                              $('textarea[name=input_json]').val(JSON.stringify(read));
                              
                            }
                        });
                   } );
				   
				   $('#min-date-user').datepicker({
				  		format: 'dd-mm-yyyy',
						autoclose: true
						
					});
					$('#max-date-user').datepicker({
						format: 'dd-mm-yyyy',
						autoclose: true
						
					});
               </script>

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
