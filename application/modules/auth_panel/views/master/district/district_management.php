
<section class="panel">
    <header class="panel-heading">
        <?= (isset($result)) ? "Update" : "Add"; ?> District
    </header>
    <div class="panel-body">
        <form role="form" method="POST">
            <?php
            if (isset($result)) {
                ?><input name="id" hidden="" value="<?= $result['id'] ?>"><?php
            }
            ?>
            <div class="form-group col-md-4">
                <label for="class_name">Select State</label>
                <select  class="form-control input-sm" name="division_master_id" id="class_id">
                    <option>Select</option>
                    <?php foreach ($data as $class) {
                        ?>

                        <option  value="<?php echo $class['id']; ?>" <?php
                        if (isset($result) && $result['state_id'] == $class['id']) {
                            echo 'SELECTED';
                        }
                        ?>><?php echo $class['name']; ?></option>
                             <?php } ?>
                </select> 
            </div>

            <div class="form-group col-md-4">
                <label>District Name</label>
                <input type="text" required=""  class="form-control input-sm " name="name" placeholder="Enter City Name" value="<?php
                if (isset($result)) {
                    echo $result['name'];
                }
                ?>">
            </div>

            <div class="form-group col-md-12">
                <button type="submit" class="btn btn-sm bold btn-info"><?= (isset($result)) ? "Update" : "Add"; ?></button>
            </div>
        </form>

    </div>
</section>
<div class="clearfix"></div>



<section class="panel">
    <header class="panel-heading">
        District(s) List
    </header>
    <div class="col-sm-12" style="margin-top:5px;">
        <button id="delete_school" onclick="delete_district()" class="btn btn-sm btn-primary pull-right"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete District(s)</button>
    </div>
    <div class="clearfix"></div>
    <div class="panel-body">
        <div class="adv-table">
            <table  class="display table table-bordered table-striped" id="all-user-grid">
                <thead>
                    <tr>
                        <th></th>
                        <th>S.no</th>
                        <th>District</th>
                        <th>State</th>
<!--                            <th>Status </th>
                        <th>Created on</th>-->
                        <th>Action </th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th align="center"><input type="checkbox" style="margin:auto; text-algin:center" id="check_all"></th>
                        <th><input type="text" data-column="0"  class="search-input-text form-control"></th>
                        <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                        <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
<!--                            <th></th>
                        <th></th>-->
                        <th></th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>
<form id="school_form" action="<?= base_url('index.php/auth_panel/master/bulk_delete_district'); ?>" method="post">
    <input type="hidden" name="school_id_array" id="school_id_array" value="">
</form>
<?php
$adminurl = AUTH_PANEL_URL;
$dragablejs = AUTH_ASSETS . 'js/draggable-portlet.js';
?>
<link rel="stylesheet" type="text/css" href='<?= AUTH_ASSETS ?>css/jquery.dataTables.css'>
<script type="text/javascript" charset="utf8" src='<?= AUTH_ASSETS ?>js/jquery.dataTables.js'></script>
<script type="text/javascript" language="javascript" >
    jQuery(document).ready(function () {
        var table = 'all-user-grid';
        var dataTable = jQuery("#" + table).DataTable({
            "processing": true,
            "pageLength": 50,
            "lengthMenu": [[50, 100, 500], [50, 100, 500]],
            "serverSide": true,
            "order": [[0, "desc"]],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0, 4]},
            ],
            "ajax": {
                url: "<?= AUTH_PANEL_URL ?>master/ajax_district_list/", // json datasource
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

    function delete_district(school_id_single = '') {
        var school_id = [];
        if (school_id_single != "") {
            school_id.push(school_id_single);
        }
        //alert(school_id);exit();
        $("input:checkbox[name=check_id]:checked").each(function () {
            school_id.push($(this).val());
        });
        if (school_id != '') {
            if (confirm('Warning !!!!  Do you really want to delete?')) {
                $('#school_id_array').val(school_id);
                $('#school_form').submit();
            }
        } else {
            alert('Please select atleast 1 Dictrict.');
            $('#delete_school').focus();
        }
    }
</script>