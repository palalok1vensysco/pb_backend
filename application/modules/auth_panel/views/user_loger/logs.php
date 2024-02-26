<div class="col-sm-12 no-padding">
    <section class="panel">

        
        <header class="panel-heading bg-dark text-white">
            User Activity logs
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <!--<a href="javascript:;" class="fa fa-times"></a>-->
            </span>
        </header>
        <div class="panel-body">
            <div class="adv-table" id="adminBackendTab">
                <table  class="display table table-bordered table-striped" id="activity-list-grid">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>User </th>
                            <th>Activity</th>
                            <th>Area</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="0"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>
<div role="dialog" id="view_json_detail" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg" style="width: 75%;">
        <div class="modal-content">
            <div class="modal-header panel-heading bg-dark text-white">
                <button aria-hidden="true" data-dismiss="modal" class="close text-white" type="button">×</button>
                <h4 class="modal-title file-modal-element-head"><strong>Detail</strong> </h4>
            </div>
            <div class="modal-body">
                <div class="panel-body" style="overflow: scroll;max-height: 400px">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td>
                                    <strong>User</strong>
                                </td>
                                <td id="user_detail"></td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Activity</strong>
                                </td>
                                <td id="activity_detail"></td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Area</strong>
                                </td>
                                <td id="area_detail"></td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Time</strong>
                                </td>
                                <td id="time_detail"></td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Json</strong>
                                </td>
                                <td><pre id="json_data"></pre></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?= AUTH_ASSETS ?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript">
    jQuery(document).ready(function () {
        var table = 'activity-list-grid';
        var dataTable = jQuery("#" + table).DataTable({
            "processing": true,
            "pageLength": 100,
            "serverSide": true,
            "order": [[0, "desc"]],
            "aoColumnDefs": [
                {"bSortable": false,"aTargets": [-6,-1]},
            ],
            "ajax": {
                url: "<?= AUTH_PANEL_URL ?>user_loger/ajax_user_loger_list?bu_id=" + "<?= $bu_id ?>", // json datasource
                type: "post", // method  , by default get
                error: function () {  // error handling
                    jQuery("." + table + "-error").html("");
                    jQuery("#" + table + "_processing").css("display", "none");
                }
            }
        });
        jQuery("#" + table + "_filter").css("display", "none");
        bind_table_search(dataTable, table, 'keyup');
        bind_table_search(dataTable, table, 'change');
    });

    $(document).on('click', '.view_json', function () {
        var id = $(this).attr('id');

        $("#user_detail,#activity_detail,#area_detail,#time_detail,#json_data").html('');
        jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>user_loger/ajax_json",
            method: 'POST',
            dataType: 'json',
            async: false,
            data: {
                "id": id
            },
            success: function (data) {
                if (data.data != undefined) {
                    $("#view_json_detail").modal("show");
                    $("#user_detail").html(data.username);
                    $("#activity_detail").html(data.comment);
                    $("#area_detail").html(data.segment);
                    $("#time_detail").html(data.creation_time);
                    $('pre').text(JSON.stringify(JSON.parse(data.json), null, '\t'));
                    $("#view_json_detail").modal('show');
                }
            }
        });
    });

    
</script>


<?php 
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
EOD;

    echo modules::run('auth_panel/template/add_custum_js',$custum_js );
?>
