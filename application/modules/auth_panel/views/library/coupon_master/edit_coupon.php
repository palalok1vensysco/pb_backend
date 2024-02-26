<style>
    .panel-heading {
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    padding: 10px;
    background: #e9e9e9 none repeat scroll 0 0;
}
</style> 
<div class="">
    <div class="col-lg-12 add_file_element">
        <section class="panel">
            <header class="panel-heading">
                EDIT PREMIUM COUPON
<!--                <a href="<?php echo AUTH_PANEL_URL . 'coupon/add_coupon'; ?>"><button class="pull-right btn btn-info btn-xs bold">Back to coupon list </button></a>-->
            </header>
            <div class="panel-body">
                <form role="form" method="POST">
                    <input type="hidden" name="id" value="<?=$coupon['id'];?>">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label for="couponname">Coupon Name</label>
                            <input type="text" class="form-control input-sm" id="couponname" name="couponname" value="<?php echo $coupon['coupon_tilte']; ?>" placeholder="Enter Coupon Name">
                            <span class="text-danger"><?php echo form_error('couponname'); ?></span>
                        </div>
                        <div class="form-group col-md-12 ">
                            <label for="exampleInputPassword1">Date Range</label>
                            <div data-date-format="mm/dd/yyyy" data-date="13/07/2013" class="input-group input-large">
                                <input type="text" name="validfrom" class="form-control dpd1 input-sm " value="<?php echo $coupon['start']; ?>">
                                <span style="color:red"><?php echo form_error('validfrom'); ?></span>
                                <span class="input-group-addon">To</span>
                                <input type="text" name="validto" class="form-control dpd2 input-sm " value="<?php echo $coupon['end']; ?>">
                                <span style="color:red"><?php echo form_error('validto'); ?></span>
                            </div>
                            <span class="help-block text-center">Select date range</span>
                        </div>                               
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12 ">
                            <label for="coupontype">Coupon Type</label>
                            <select class="form-control input-sm m-bot15" id="coupontype" name="coupontype" value="<?php echo $coupon['coupon_type']; ?>" >                     
                                <option value ="2" <?php if ($coupon['coupon_type'] == 2) {
    echo "SELECTED";
} ?> >In Percentage(%)</option>
                                <option value ="1" <?php if ($coupon['coupon_type'] == 1) {
    echo "SELECTED";
} ?>>In Value</option>                      
                            </select>
                            <span class="text-danger"><?php echo form_error('coupontype'); ?></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="coupovalue">Coupon value</label>
                            <input type="text" class="form-control input-sm " id="coupovalue" name="coupovalue" placeholder="Enter Coupon value" value="<?php echo $coupon['coupon_value']; ?>">
                            <span class="text-danger"><?php echo form_error('coupovalue'); ?></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="coupon_for">Coupon Type</label>
                            <select class="form-control input-sm m-bot15" id="coupon_for" name="coupon_for">                     
                                <option <?php echo ($coupon['coupon_for'] == 0 ) ? 'selected=selected' : ''; ?> value ="0">Season Dependent</option>
                                <option  <?php echo ($coupon['coupon_for'] == 1 ) ? 'selected=selected' : ''; ?> value ="1">User Dependent</option>                      
                            </select>
                            <span class="text-danger"><?php echo form_error('coupon_for'); ?></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-info btn-sm">Submit</button>
                        <a href="<?=base_url('admin-panel/premium-add-coupon')?>">
                        <button class="btn btn-danger btn-sm" type="button" >Cancel</button>
                    </a>
<!--                        <button class="btn btn-danger btn-sm" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>-->
                    </div>
                </form>

            </div>
        </section>
    </div>
    <div class="clearfix"></div>
</div>
<!--<section class="panel">
    <header class="panel-heading">
        Users added to this coupon
    </header>
    <div class="panel-body">
        <div class="row">

            <div class="col-md-12">
                <div class="input-group">
                    <input onkeypress="show_users($(this).val())"  placeholder="Search Here to add users" class="input-sm form-control pull-left" type="text"> 
                    <span class="small pull-left">Type email , mobile no. , user name </span></div>
            </div>
        </div>
    </div>
    <table class="table table-hover p-table">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="new_user">
        </tbody>
        <tbody class="added_user">
            <?php
            foreach ($added_user as $value) {
                echo "<tr>";
                echo '<td class="p-name" > <a href="#"> ' . $value['name'] . '</a><br><small>' . $value['email'] . '</small></td>';
                echo '<td>' . $value['mobile'] . '</td>';
                echo '<td>' . $value['email'] . '</td>';
                echo '<td><a href="' . AUTH_PANEL_URL . 'coupon/remove_user?user_id=' . $value['id'] . '&coupon_id=' . $coupon['id'] . '"  class="btn btn-danger btn-xs"><i class="fa fa-folder"></i> Remove </a></td>';
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</section>-->
<?php
$adminurl = AUTH_PANEL_URL;
$c_id = $coupon['id'];

$custum_js = <<<EOD
              <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                   jQuery(document).ready(function() {					
          					$( function() {
          					$( ".dpd1" ).datepicker({autoclose: true});
          					$( ".dpd2" ).datepicker({autoclose: true});
          					 }); // datepicker closed
                   } );

                  function show_users(str) {
                    $('.new_user').html('');
                    str = encodeURI(str);
                      jQuery.ajax({
                        url: "$adminurl"+"coupon/user_list/"+str,
                        method: 'GET',
                        dataType: 'json',
                        success: function (data) {
                          html = "";
                          $.each( data , function( key, value ) {
                            html += "<tr>";
                            html += '<td class="p-name"> <a href="#">'+value.name+'</a><br><small>'+value.email+'</small>';
                            html += "</td>";
                            html += '<td class="p-team"> <a href="#">'+value.mobile+'</a>';
                            html += "</td>";
                            html += ' <td class="p-progress"><small>'+value.email+'</small></td>';
                            html += ' <td><a href="$adminurl/coupon/add_user?user_id='+value.id+'&coupon_id=$c_id"  class="btn btn-success btn-xs"><i class="fa fa-folder"></i> Add </a></td>';

                            html += "</tr>";

                          });
                          $('.new_user').html(html);
                        }
                      });
                    }
               </script>
EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
<?php
