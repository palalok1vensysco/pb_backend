
<div class="col-sm-12 no-padding">
    <section class="panel">
        <header class="panel-heading ban-head-new ">
            Course Video Feedback List(s)
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>Point</th>
                            <th>Text</th>
                            <th>Created ON</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control input-xs"></th>
                            <th></th>
                            <th></th>
                            <th><input type="text" data-column="4"  class="search-input-text form-control input-xs"></th>
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
            "pageLength": 15,
            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
            "serverSide": true,
            "order": [[0, "desc"]],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [-1, -3]},
            ],
            "ajax": {
                url: "<?= AUTH_PANEL_URL ?>file_manager/library/ajax_review_course_video", // json datasource
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
