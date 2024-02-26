<div class="col-sm-12 no-padding">
    <section class="panel">
        <header class="panel-heading">
            Video Logs(s) LIST
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
            </span>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Video</th>
                            <th>Course </th>
                            <th>Total Views </th>
                            <th>Unique Views</th>
                            <th>Avg Watching </th>
                            <th>Users watched(%)</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th><input type="text" data-column="0"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="3"  class="search-input-text form-control"></th>
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


<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?=AUTH_ASSETS?>js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" >

    jQuery(document).ready(function () {
        var table = 'all-user-grid';
        var dataTable = jQuery("#" + table).DataTable({
            "processing": true,
            "serverSide": true,
            "order":[[0,"desc"]],
            "aoColumnDefs": [
                // {"bSortable": false, "aTargets": [-1,-3,-5,-7]},
            ],
            "ajax": {
                url: "<?= AUTH_PANEL_URL ?>file_manager/library/ajax_video_logs", // json datasource
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
</script>