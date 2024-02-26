<style>
    .panel-heading {
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    padding: 10px;
    background: #e9e9e9 none repeat scroll 0 0;
}
</style> 
<?php
$error = validation_errors();
$display = "display:none;";
if (!empty($error)) {
    $display = "";
}
?>
<div class="col-lg-12 add_file_element" style="<?php echo $display; ?>">
    <section class="panel">
        <header class="panel-heading">
            ADD PREMIUM COUPON
        </header>
        <div class="panel-body">
            <form role="form" method="POST">
                <div class="col-md-6">
                    <div class="form-group col-md-12">
                        <label for="couponname">Coupon Name</label>
                        <input type="text" class="form-control input-sm " id="couponname" name="couponname" placeholder="Enter Coupon Name" value="<?= set_value('couponname'); ?>" autocomplete="off">
                        <span class="text-danger"><?php echo form_error('couponname'); ?></span>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label for="exampleInputPassword1">Date Range</label>
                        <div data-date-format="mm/dd/yyyy" data-date="13/07/2013" class="input-group input-large">
                            <input type="text" name="validfrom" class="form-control dpd1 input-sm " value="<?php echo set_value('validfrom'); ?>" autocomplete="off">
                            <span style="color:red"><?php echo form_error('validfrom'); ?></span>
                            <span class="input-group-addon">To</span>
                            <input type="text" name="validto" class="form-control dpd2 input-sm " value="<?php echo set_value('validto'); ?>" autocomplete="off">
                            <span style="color:red"><?php echo form_error('validto'); ?></span>
                        </div>
                        <span class="help-block text-center">Select date range</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12">
                        <label for="coupontype">Coupon Type</label>
                        <select class="form-control input-sm m-bot15" id="coupontype" name="coupontype">                     
                            <option value ="2" <?= (set_value('coupontype') == 2 ? 'selected' : '') ?>>In Percentage(%)</option>
                            <option value ="1" <?= (set_value('coupontype') == 1 ? 'selected' : '') ?>>In Value</option>                      
                        </select>
                        <span class="text-danger"><?php echo form_error('coupontype'); ?></span>
                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="coupovalue">Coupon value</label>
                        <input type="text" class="form-control input-sm " id="coupovalue" name="coupovalue" placeholder="Enter Coupon value" value="<?= set_value('coupovalue'); ?>">
                        <span class="text-danger"><?php echo form_error('coupovalue'); ?></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="coupon_for">Coupon Type</label>
                        <select class="form-control input-sm m-bot15" id="coupon_for" name="coupon_for">                     
                            <option value ="0">Season Dependent</option>
                            <option value ="1" <?= (set_value('coupon_for') == 1 ? 'selected' : '') ?>>User Dependent</option>                      
                        </select>
                        <span class="text-danger"><?php echo form_error('coupon_for'); ?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-info btn-sm">Submit</button>
                     <button class="btn btn-danger btn-sm" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>
                </div>
            </form>

        </div>
    </section>
</div>
<div class="clearfix"></div>


<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading">
            <button onclick="$('.add_file_element').show('slow');" class="btn-success btn-xs btn pull-right"><i class="fa fa-plus"></i> Add</button>
            <?php // echo strtoupper($page); ?> PREMIUM COUPON(s) LIST
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title Name </th>
                            <th>Start Date </th>
                            <th>End Date </th>
                            <th>Coupon Type</th>
                            <th>Coupon Value</th>
                            <th>Status </th> 
                            <th>Action </th> 	
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th><input type="text" data-column="0"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="3"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="4"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="5"  class="search-input-text form-control"></th>
                            <th><select data-column="6"  class="form-control search-input-select">
                                    <option value="">All</option>
<!--                                    <option value="1">Deactive</option>-->
<!--                                    <option value="0">Active</option>-->
                                </select></th> 
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>

<?php
$adminurl = AUTH_PANEL_URL;
//if($page == 'android') { $device_type = 1; } elseif ($page == 'ios') { $device_type = 2; } elseif ($page == 'all') { $device_type = '0'; }
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                   jQuery(document).ready(function() {
                       var table = 'all-user-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                            "pageLength": 15,
                            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"coupon/ajax_coupon_list/", // json datasource
                               type: "post",  // method  , by default get
                               error: function(){  // error handling
                                   jQuery("."+table+"-error").html("");
                                   jQuery("#"+table+"_processing").css("display","none");
                               }
                           }
                       } );
                       jQuery("#"+table+"_filter").css("display","none");
                       $('.search-input-text').on( 'keyup click', function () {   // for text boxes
                           var i =$(this).attr('data-column');  // getting column index
                           var v =$(this).val();  // getting search input value
                           dataTable.columns(i).search(v).draw();
                       } );
                        $('.search-input-select').on( 'change', function () {   // for select box
                            var i =$(this).attr('data-column');
                            var v =$(this).val();
                            dataTable.columns(i).search(v).draw();
                        } );
						
						$( function() {
					$( ".dpd1" ).datepicker({autoclose: true});
					$( ".dpd2" ).datepicker({autoclose: true});
					 }); // datepicker closed
                   } );
               </script>

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>

