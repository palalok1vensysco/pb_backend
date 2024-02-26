<?php

$period = $this->input->get('period');
?>
<section class="panel add_section" style="display: none;">
    <div class="panel-body">
    </div>
</section>
<link rel="stylesheet" type="text/css" href="https://thevectorlab.net/flatlab/assets/select2/css/select2.min.css" />

<div class="col-lg-12 trangingHead">
    <section class="panel">
        <header class="panel-heading bg-dark text-white d-flex">
            <div class="addTranding">
                <p class="p-0 m-0">All User List</p>
                <div class="tools-right-1 d-flex align-items-center ">
                    <span class="tools">
                        <form role="form" method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <select name="period" class="form-control input-xs m-bot15 period p-0 m-0" onchange="this.form.submit()">
                                <option <?= ($period == "today") ? "selected" : "" ?> value="today">Today</option>
                                <option <?= ($period == "yesterday") ? "selected" : "" ?> value="yesterday">Yesterday</option>
                                <option <?= ($period == "7days") ? "selected" : "" ?> value="7days">Last 7 Days</option>
                                <option <?= ($period == "current_month") ? "selected" : "" ?> value="current_month">Current Month</option>
                                <!-- <option <?= ($period == "custom") ? "selected" : "" ?>  value="custom">Custom</option> -->
                                <option <?= ($period == "" || $period == "all") ? "selected" : "" ?> value="all">All</option>
                            </select>
                            <?php
                            foreach ($_GET as $key => $value) {
                                if ($key != 'period') {
                                    echo "<input type='hidden' value='" . $value . "' name='" . $key . "'>";
                                }
                            }
                            ?>
                        </form>
                    </span>                  
                </div>
            </div>
        </header>
    </section>
</div>

<!-- <span class="tools pull-right">
                  <form id="download_content_csv" method="post" action=""  >
                      <button class="btn btn-danger margin-right btn-xs"> 
                          <i class="fa fa-file" aria-hidden="true"></i>
                          Download CSV 
                      </button>
                      <textarea style="display:none;" name="input_json"></textarea>
                  </form>
              </span> -->

<div class="panel-body bg-white">
    <div class="adv-table">
        <?php if ($period == "custom") { ?>
            <div class="col-md-6 pull-right custom_search">
                <div data-date-format="dd-mm-yyyy" data-column="8" class="input-group">
                    <div class="input-group-addon">From</div>
                    <input autocomplete="off" type="text" id="min-date-user" class="form-control input-xs" placeholder="">
                    <div class="input-group-addon">To</div>
                    <input autocomplete="off" type="text" id="max-date-user" class="form-control input-xs" placeholder="">
                    <div class="input-group-addon btn date-range-filter-clear">Clear</div><br>

                </div>
                <span id="errorMessage" style="color: red;"></span>
            </div>
        <?php } ?>
        <table class="display table table-bordered table-striped" id="all-user-grid">
            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Status </th>
                    <th>Registered On </th>
                    <th>Action</th>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th></th>
                    <th><input type="text" data-column="0" class="search-input-text input-xs form-control"></th>
                    <th><input type="text" data-column="2" class="search-input-text form-control"></th>
                    <th><input type="text" data-column="1" class="search-input-text input-xs form-control"></th>
                    <th>
                        <select data-column="5" class="form-control input-xs search-input-select">
                            <option value="">All</option>
                            <option value="0">Active</option>
                            <option value="1">Disable</option>
                        </select>
                    </th>
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
if ($page == 'android') {
    $device_type = 1;
} elseif ($page == 'ios') {
    $device_type = 2;
} elseif ($page == 'all') {
    $device_type = '0';
} elseif ($page == 'website') {
    $device_type = '3';
} elseif ($page == 'android tv') {
    $device_type = '4';
}
?>
<?php
$query_string = "";
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >
               var all_user_all = "$adminurl"+"web_user/ajax_all_user_list/$device_type ?period=$period&user=$query_string";
                var all_user_csv = "$adminurl"+"web_user/get_request_for_csv_download/$device_type ?period=$period&user=$query_string";
                
                
                   jQuery(document).ready(function() {
                       var table = 'all-user-grid';
                       var dataTable_user = jQuery("#"+table).DataTable( {
                           "processing": true,
                            "pageLength": 15,
                            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                         "columnDefs": [{ 
                            "orderable": false, 
                            "targets": 1
                        },
                        { 
                "orderable": false, 
                "targets": 6

                        }],
                           "ajax":{
                               url :all_user_all, // json datasource
                               type: "post",  // method  , by default get
                               error: function(){  // error handling
                                   jQuery("."+table+"-error").html("");
                                   jQuery("#"+table+"_processing").css("display","none");
                               }
                           }
                       } );
                       
                       jQuery("#" + table + "_filter").css("display", "none");
                       bind_table_search(dataTable_user, table, 'keyup');
                       bind_table_search(dataTable_user, table, 'change');
                    //    $('.search-input-text').on( 'keyup click', function () {   // for text boxes
                    //        var i =$(this).attr('data-column');  // getting column index
                    //        var v =$(this).val();  // getting search input value
                    //        dataTable_user.columns(i).search(v).draw();
                    //    } );

                        // $('.search-input-select').on( 'change', function () {   // for select box
                        //     var i =$(this).attr('data-column');
                        //     var v =$(this).val();
                        //     dataTable_user.columns(i).search(v).draw();
                        // } );
						// Re-draw the table when the a date range filter changes
                        $('#min-date-user,#max-date-user').change(function() {
                            var fromDate = new Date(document.getElementById('min-date-user').value);
                    var toDate = new Date(document.getElementById('max-date-user').value);
                    var errorMessageSpan = $('#errorMessage');
                  
                    if (fromDate > toDate) {
                        errorMessageSpan.text('From Date should not be greater than To Date');
                      } else {
                        errorMessageSpan.text(''); // Clear previous error message
                        // You can perform other actions here if needed
                      }
                            if ($('#min-date-user').val() != "" && $('#max-date-user').val() != "") {
                                var dates = $('#min-date-user').val() + ',' + $('#max-date-user').val();
                                dataTable_user.columns(8).search(dates).draw();
                            }
                        });
                        $('.date-range-filter-clear').on('click', function() {
                            $('#min-date-user').val('');
                            $('#max-date-user').val("");
                          
                        });
                        $(document).ajaxComplete(function(event, xhr, settings) {
                            if (settings.url === all_user_all) {
                                var obj = jQuery.parseJSON(xhr.responseText);
                                var read = obj.posted_data;
                
                                $('#download_content_csv').attr('action', all_user_csv);
                                $('textarea[name=input_json]').val(JSON.stringify(read));
                
                            }
                        });

                   });
				   
                  $('#min-date-user,#max-date-user').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true
                });

               

                $('.period').on('change', function() {
                    period = $(this).val();
                    if (period == "custom") {
                        $('.custom_search').show();
                    }
                });

                $('#min-date-user,#max-date-user').on('change', function() {
                });

                $(".stream_element_select").change(function() {
                    val = $(this).val();
                    $('.sub_element_select').val('');
                    $('.substream').hide();
                    $('.sub' + val).show();
                });
    </script>

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>