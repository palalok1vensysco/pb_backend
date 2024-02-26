<style>
    .btn-success {
    background-color: #ff9700;
    border-color: #ff9700;
    color: #FFFFFF;
}
.btn-danger {
    background-color: #ff9700;
    border-color: #ff9700;
    color: #FFFFFF;
}

</style>
<div class="col-sm-12 px-0">
    <section class="panel">
        <header class="panel-heading  map_edit_categori text-white bg-dark">
            Email template(s) LIST
            <span class="pull-right">
                <a class='btn-sm btn btn-success clr_green' href='<?php echo AUTH_PANEL_URL . "mailer/add_email_template" ?>'>Add</a>
            </span>
        </header>
        <div class="panel-body">
            <div class="adv-table">

                <table  class="display table table-bordered table-striped" id="all-subcategory-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Template Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th><input type="text" data-column="0"  class="search-input-text form-control clr_green"></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control clr_green"></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" >

    jQuery(document).ready(function () {
        var table = 'all-subcategory-grid';
        var dataTable = jQuery("#" + table).DataTable({
            "processing": true,
            "serverSide": true,
            "order":[[0,"desc"]],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [-1]},
            ],
            "ajax": {
                url: "<?= AUTH_PANEL_URL ?>mailer/ajax_get_all_template", // json datasource
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

