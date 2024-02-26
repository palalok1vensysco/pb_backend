
<div class="col-sm-12 px-0">
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Role List
            <a class="btn-xs btn pull-right display_color dropdown_ttgl text-white" href="<?= AUTH_PANEL_URL . "admin/manage_permission_group" ?>">Add New</a>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="backend-user-grid">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Backend id </th>
                            <th>Role Name </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                        <th></th>
                            <th><input type="text" data-column="0"  class="form-control search-input-text"></th>
                            
                            <th><input type="text" data-column="1"  class="form-control search-input-text"></th>
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
        $('form')[0].reset();
        var table = 'backend-user-grid';
        var dataTable = jQuery("#" + table).DataTable({
            "processing": true,
            "pageLength": 50,
            "serverSide": true,
            "order":[[0,"desc"]],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [-1,-2]},
            ],
            "ajax": {
                url: "<?= AUTH_PANEL_URL ?>admin/ajax_get_permission_group_list", // json datasource
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

<?php 
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
EOD;

    echo modules::run('auth_panel/template/add_custum_js',$custum_js );
?>