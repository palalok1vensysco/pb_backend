
<style type="text/css">
    
   .select2-container{width: 100% !important;
    border: 1px solid #ccc;
    align-items: center;
    border-radius: 4px;}

</style>
<section class="panel" style="display:none" id="activation_section">
    <header class="panel-heading">
        Generate Activation Key
    </header>
    <div class="panel-body">
        <form role="form" method="POST" enctype="multipart/form-data">
            <div class="form-group col-md-6">
                <label >Search user By (Name,Email,Mobile)</label>
                <select class="user_id form-control input-xs" name="user_id">
                </select>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <button type="submit" class="btn btn-xs  btn-success bold">Submit</button>
                <button type="reset" name="" onclick="$('#activation_section').hide('slow');" class="btn btn-xs btn-warning">Cancel</button> 
            </div>
        </form>
    </div>
</section>
<section class="panel">
    <header class="panel-heading">
        <?php echo ($page_title)." List"; ?>
        <button type="button" class="btn btn-xs btn-success pull-right" onclick="$('#activation_section').show('slow');"><i class="fa fa-plus"></i>Generate Activation Key</button>
    </header>
    <div class="panel-body">
        <div class="adv-table">
            <table  class="display table table-bordered table-striped" id="all-user-grid">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Details</th>
                        <th>Activation Key</th>
                        <th>Is verified</th>
                        <th>Created On</th>
                        <th>Activated On</th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th></th>
                        <th><input type="text" data-column="1"  class="search-input-text form-control input-xs"></th>
                        <th><input type="text" data-column="2"  class="search-input-text form-control input-xs"></th>
                        <th>
                            <select class="search-input-select input-xs form-control" data-column="3">
                                <option value="">All</option>
                                <option value='0'>Not Verified</option>
                                <option value='1'>Verified</option>
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
<!-- <link rel="stylesheet" type="text/css" href="<?= AUTH_ASSETS ?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>js/jquery.dataTables.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
 


<script type="text/javascript">
    $(document).ready(function () {
        var courseListUrl = "<?= AUTH_PANEL_URL ?>web_user/ajax_user_activation_list";
        var table = 'all-user-grid';
        var dataTable_user = jQuery("#" + table).DataTable({
            "processing": true,
            "pageLength": 100,
            "order":[[0,"desc"]],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [-1,-3,-4]},
            ],
            "lengthMenu": [[25, 50, 100], [25, 50, 100]],
            "serverSide": true,
            "ajax": {
                url: courseListUrl, // json datasource
                type: "post", // method  , by default get
                error: function () {  // error handling
                    jQuery("." + table + "-error").html("");
                    jQuery("#" + table + "_processing").css("display", "none");
                }
            }
        });
        jQuery("#" + table + "_filter").css("display", "none");
        bind_table_search(dataTable_user, table, 'keyup');
        bind_table_search(dataTable_user, table, 'change');

        //serch course list
        $('.user_id').select2({ 
            placeholder: 'Select an item',
            theme: "material",
            allowClear: true,
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/user_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function (data) {
                    course_data = data;
                    return {
                        results: data
                    };
                },
                cache: true
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