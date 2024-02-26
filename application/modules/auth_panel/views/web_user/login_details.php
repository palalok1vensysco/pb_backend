<div class="col-sm-12 px-0">
    <section class="panel">
        <header class="panel-heading">
            LOGIN(s) Details
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Ip Address</th>
                            <th>Device Type</th>
                            <th>OS Version</th>
                            <th>Device ID</th>
                            <th>Manufacturer</th>
                            <th>App Version</th>
                            <th>Create Date</th>
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
               var table = 'all-user-grid';
                var dataTable = jQuery("#" + table).DataTable({
                        "processing": true,
                        "pageLength": 15,
                        "lengthMenu": [[15, 25, 50], [15, 25, 50]],
                        "serverSide": true,
                        "ordering": false,
                        "ajax": {
                            url: "$adminurl" + "web_user/ajax_login_details/" + "$user_id", // json datasource
                            type: "post", // method  , by default get
                            error: function () {  // error handling
                                jQuery("." + table + "-error").html("");
                                jQuery("#" + table + "_processing").css("display", "none");
                            }
                        }
                    });
                    jQuery("#" + table + "_filter").css("display", "none");
                    $('.search-input-text').on('keyup click', function () {   // for text boxes
                        var i = $(this).attr('data-column');  // getting column index
                        var v = $(this).val();  // getting search input value
                        dataTable.columns(i).search(v).draw();
                    });
    </script>

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>