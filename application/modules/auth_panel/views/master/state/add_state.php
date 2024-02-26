
<style>
    .stream_css{
        width:17% !important;
    }
</style>

<section class="panel">
    <header class="panel-heading">
        Add State
    </header>
    <div class="panel-body">
        <form role="form" method="POST" enctype="multipart/form-data">
            <div class="col-md-12 error bold alert-box">
                <?php echo validation_errors(); ?>
            </div>
            <div class="form-group col-md-4">
                <label for="name">State Name</label>
                <input type="text" class="form-control input-sm" value="<?php echo set_value('name'); ?>" id="name" name="name" placeholder="Enter State Name">
            </div> 
            <!--                <div class="form-group col-md-3">
                                <label for="name">Upload Image </label> 
                                <input type="file" accept="image/*" name = "image" id="exampleInputFile">
                            </div>     -->
            <div class="form-group col-md-4">
                <button type="submit" class="btn btn-sm bold btn-info" style="margin-top: 22px;">Add</button>
            </div>
        </form>

    </div>
</section>
<div class="clearfix"></div>


<section class="panel">
    <header class="panel-heading">
        <?php // echo strtoupper($page);   ?> State(s) LIST
    </header>
    <div class="col-sm-12" style="margin-top:5px;">
        <button id="delete_school" onclick="delete_division()" class="btn btn-sm btn-primary pull-right"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete State(s)</button>
    </div>
    <div class="clearfix"></div>
    <div class="panel-body">
        <div class="adv-table">
            <table  class="display table table-bordered table-striped" id="all-user-grid">
                <thead>
                    <tr>
                        <th></th>
                        <th>S.no</th>
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
<!--                            <th></th>
                        <th></th>-->
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>
<form id="school_form" action="<?= base_url('index.php/auth_panel/master/bulk_delete_state'); ?>" method="post">
    <input type="hidden" name="school_id_array" id="school_id_array" value="">
</form>


<?php
$adminurl = AUTH_PANEL_URL;
$dragablejs = AUTH_ASSETS . 'js/draggable-portlet.js';
//if($page == 'android') { $device_type = 1; } elseif ($page == 'ios') { $device_type = 2; } elseif ($page == 'all') { $device_type = '0'; }
?>
<link rel="stylesheet" type="text/css" href='<?= AUTH_ASSETS ?>css/jquery.dataTables.css'>
<script type="text/javascript" charset="utf8" src='<?= AUTH_ASSETS ?>js/jquery.dataTables.js'></script>
<script src="<?= $dragablejs ?>"></script>
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
                {"bSortable": false, "aTargets": [0, 3]},
            ],
            "ajax": {
                url: "<?= AUTH_PANEL_URL ?>master/ajax_state_list/", // json datasource
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

    $('#check_all').change(function () {
        if ($('#check_all').is(':checked')) {
            $('.check_id').prop('checked', true);
        } else {
            $('.check_id').prop('checked', false);
        }
    });

    function delete_division(school_id_single = '') {
        var school_id = [];
        if (school_id_single != "") {
            school_id.push(school_id_single);
        }
        //alert(invoice_no); exit();
        $("input:checkbox[name=check_id]:checked").each(function () {
            school_id.push($(this).val());
        });
        if (school_id != '') {
            if (confirm('Warning !!!!  Do you really want to State?')) {
                $('#school_id_array').val(school_id);
                $('#school_form').submit();
            }
        } else {
            alert('Please select atleast 1 State.');
            $('#delete_school').focus();
        }
    }
</script>