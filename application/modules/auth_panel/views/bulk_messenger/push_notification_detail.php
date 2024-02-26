<section class="panel">
    <header class="panel-heading">
        Push Notification to Users
    </header>
    <div class="panel-body">
        <div class="adv-table">
            <table  class="display table table-bordered table-striped" id="push-history-grid">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Send by</th> 
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Device</th>
                        <th>View Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>
<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>js/jquery.dataTables.min.js"></script>

<script type="text/javascript" charset="utf8">
    jQuery(document).ready(function () {
        var table = 'push-history-grid';
        var dataTable = jQuery("#" + table).DataTable({
            "processing": true,
            "pageLength": 50,
            "serverSide": true,
            "order": [[0, "desc"]],
             "aoColumnDefs": [
                {"bSortable": false, "aTargets": [-1,-2]},
            ],
            "ajax": {
                url: "<?= AUTH_PANEL_URL ?>bulk_messenger/push_notification/ajax_push_to_user/" + "<?php echo $id ?>", // json datasource
                type: "post", // method  , by default get
                error: function () {  // error handling
                    jQuery("." + table + "-error").html("");
                    jQuery("#" + table + "_processing").css("display", "none");
                }
            }
        });
        jQuery("#" + table + "_filter").css("display", "none");
        bind_table_search(dataTable, table, 'keyup');
    });

</script>