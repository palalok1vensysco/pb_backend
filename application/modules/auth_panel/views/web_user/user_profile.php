<style>
    .mnul-r {
        border: 1px solid #eee;
        padding: 0px 11px;
    }

    .alt.green-bg {
        background: #017cc2;
    }
</style>
<aside class="profile-nav col-lg-3 no-padding">
    <section class="panel">
        <header class="panel-heading">
            Screen Sharing
            <span class="tools pull-right">
                <a class="fa fa-chevron-up" href="javascript:;"></a>
            </span>
        </header>
        <div class="panel-body" style="display: none">
            <form role="form" method="post" action="<?php echo AUTH_PANEL_URL . 'web_user/set_screen_share/' . $user_data['id']; ?>">
                <div class="form-group col-md-4">
                    <label><input type="radio" <?= $user_data['screen_share'] == 1 ? 'checked' : ''; ?> value="1" name="screen_sharing">Yes</label>
                </div>
                <div class="form-group col-md-4">
                    <label><input type="radio" <?= $user_data['screen_share'] == 0 ? 'checked' : ''; ?> value="0" name="screen_sharing">No</label>
                </div>
                <div class="form-group col-md-4">
                    <button class="btn btn-xs bold btn-success pull-right" type="submit">Save</button>
                </div>
            </form>
        </div>
    </section>

    <div>
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="user-heading alt green-bg pb-0 text-center">
                    <div class="wFull">
                        <?php if ($user_data['profile_picture'] != null) { ?>
                            <a href="#">
                                <img alt="" src="<?= $user_data['profile_picture'] ?>">
                            </a>
                        <?php } else { ?>
                            <a href="#">
                                <img alt="" src="<?= AUTH_ASSETS . "images/icon-5359553_1280.webp" ?>">
                            </a>
                        <?php } ?>
                    </div>


                    <div class="user-heading alt green-bg pt-0 radius-none text-center">
                        <h1><?= $user_data['name'] ?></h1>
                        <p><?= $user_data['email'] ?></p>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs pills-main ul_flex">
                <li class="active"><a href="#ClientInfo" data-toggle="tab">User Details</a></li>
                <li class=""><a href="#tab5" data-toggle="tab">Device Information</a></li>
                <li class=""><a href="#tab6" data-toggle="tab">User Profiles</a></li>
            </ul>
        </div>
        <!-- /.col-md-4 -->

    </div>
</aside>
<aside class="profile-info col-lg-9">


    <section class="panel">
        <div class=" ">
            <div class="col-lg-12">
                <div class="tab-content">
                    <div class="tab-pane bio-graph-info active" id="ClientInfo">
                        <section class="panel">
                            <header class="panel-heading bg-dark text-white">
                                User Details
                            </header>
                            <div class="panel-body bio-graph-info">
                                <div class="row">
                                    <div class="bio-row">
                                        <p>
                                            <label>User ID: </label><?= $user_data['id']; ?>
                                        </p>
                                    </div>
                                    <div class="bio-row">
                                        <p>
                                            <label>Name: </label>
                                            <span id='lblName' class="editable"><?= $user_data['name']; ?></span>
                                            <!--   <a href="javascript:void(0)"><i id="" data-edit_id="#lblName" class=" editable_input fa btn btn-xs btn-success">Edit</i></a> -->
                                        </p>
                                    </div>

                                    <div class="bio-row">
                                        <p>
                                            <label>Email: </label><span id="lblemail" class="editabl"><?= $user_data['email']; ?></span><a href="javascript:void(0)"><i id="" data-edit_id="#lblemail" class=" editable_input_email fa btn btn-xs btn-info">Edit</i></a>
                                        </p>
                                    </div>
                                    <div class="bio-row">
                                        <p>
                                            <label>Mobile: </label><span id="lblmobile" class="editabl"><?= $user_data['mobile']; ?></span><a href="javascript:void(0)"><i id="" data-edit_id="#lblmobile" class=" editable_input_mobile fa btn btn-xs btn-info">Edit</i></a>
                                        </p>
                                    </div>
                                    <?php if (isset($f_lists->Dob_On_User_Profile) && $f_lists->Dob_On_User_Profile == 1) { ?>
                                        <div class="bio-row">
                                            <p>
                                                <label>DOB: </label><?= $user_data['date_of_birth'] != '' ? $user_data['date_of_birth'] : '-' ?>
                                            </p>
                                        </div>
                                    <?php } ?>
                                    <div class="bio-row">
                                        <p>
                                            <label>Registration: </label><?= get_time_format($user_data['created_at']) ?>
                                        </p>
                                    </div>
                                    <div class="bio-row">
                                        <p>
                                            <label>Last Login: </label>
                                            <?php
                                            if ($redis_session) {
                                                foreach ($redis_session as $keys => $value) {
                                                    if ($keys == 'iat') {
                                                        echo get_time_format($value);
                                                    }
                                                }
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <div class="bio-row">
                                        <p>
                                            <label>Password:&nbsp;</label><span id="lblpassword" class="editabl">*********</span><a href="javascript:void(0)"><i id="" data-edit_id="#lblpassword" class=" editable_input_password fa btn btn-xs btn-info">Edit</i></a>
                                        </p>
                                    </div>


                                    <?php if (isset($f_lists->Country_On_User_Profile) && $f_lists->Country_On_User_Profile == 1) { ?>
                                        <div class="bio-row">
                                            <p>
                                                <label>Country: </label><?= ($user_data['country'] != "" ? $user_data['country'] : "India"); ?>
                                            </p>
                                        </div>
                                    <?php } ?>
                                </div>
                                <hr class="col-md-12">
                                <div class="col-md-12 ">

                                    <?php if ($user_data['status'] != '2') { ?>
                                        <a href="<?= AUTH_PANEL_URL . 'web_user/delete_user/delete/' . $user_data['id']; ?>" onclick="return confirm('Are you sure to delete this user');"><button class="btn btn-danger btn-xs btn_user-space hide">Delete User</button></a>

                                        <?php if ($user_data['status'] == '1') { ?>
                                            <a href="<?= AUTH_PANEL_URL . 'web_user/enable_user/enable/' . $user_data['id']; ?>"><button class=" btn btn-warning btn-xs btn_user-space btn_user-space">Enable login</button></a>
                                        <?php } else { ?>
                                            <a href="<?= AUTH_PANEL_URL . 'web_user/disable_user/disable/' . $user_data['id']; ?>" onclick="return confirm('Are you sure to disable this user');"><button class="btn btn-info btn_user-space btn-xs btn_user-space">Disable login</button></a>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <a href="#"><button class=" btn btn-info btn-xs  btn_user-space">User Deleted</button></a>
                                        <a href="<?php echo AUTH_PANEL_URL . 'web_user/active_user/active/' . $user_data['id']; ?>" onclick="return confirm('Are you sure to active this user');"><button class="btn btn-info btn-xs btn_user-spacee">Active User</button></a>
                                    <?php } ?>

                                    <!-- <a href="<?php
                                                    $notification_arr = array(
                                                        'id' => $user_data['id'],
                                                        'name' => $user_data['name'],
                                                        'device_type' => isset($user_data['device_type']) ? $user_data['device_type'] : "",
                                                        'device_token' => isset($user_data['device_token']) ? $user_data['device_token'] : ""
                                                    );
                                                    echo AUTH_PANEL_URL . 'bulk_messenger/push_notification/send_push_notification?q=' . base64_encode(json_encode($notification_arr));
                                                    ?>">
                <button class="btn btn-info btn-xs  btn_user-space">Push Notification</button></a> -->

                                    <a onclick="return confirm('You are going to refresh session of logged in user. User session will be destroyed.')" href="<?php echo AUTH_PANEL_URL . 'web_user/reset_session/' . $user_data['id']; ?>"><button class="btn btn-danger btn-xs  hide btn_user-space">Refresh session</button></a>
                                    <!--a onclick="return confirm('Do you really want to send email to user.')" href="<?php echo AUTH_PANEL_URL . 'bulk_messenger/bulk_email/send_bulk_email?email=' . urlencode($user_data['email']); ?>"><button class="btn btn-info btn-xs btn_user-space">Send Email</button></a-->

                                    <!-- <a target="" href="<?php echo AUTH_PANEL_URL . 'web_user/login_details/' . $user_data['id']; ?>"><button class="btn btn-success btn-xs  btn_user-space">Login Details</button></a> -->

                                    <button onclick="<?= ($redis_session) ? "" : "alert('User Is Not Login Currenctly.')"; ?>" class="btn btn-info btn-xs  btn_user-space" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Session Info</button>
                                    <a target="_blank" href="<?php echo site_url('auth_panel/web_user/login_details/' . $user_data['id']); ?>" class="btn btn-info btn-xs btn_user-space margin-top" type="button" data-toggle="collapse" data-target="#deviceToken" aria-expanded="false" aria-controls="deviceToken">Login History</a>
                                    </p>
                                    <div class="collapse" id="collapseExample">
                                        <div class="card card-body">
                                            <?php
                                            if ($redis_session) {
                                                foreach ($redis_session as $keys => $value) {
                                                    if ($keys === 'iat' || $keys == 'exp') {
                                                        $value = date("d-m-Y H:i:s", $value);
                                                    }
                                                    echo "<div class='col-md-12 margin-bottom bold'><div class='btn btn-default  btn-xs'>$keys</div> <div style='max-width:100%;overflow:hidden;overflow-x: auto;' class='small'>$value</div></div>";
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="collapse" id="deviceToken">
                                        <div class="card card-body">
                                            <div class="col-md-12">
                                                <span><?php //$user_data['device_token']?$user_data['device_token']:"--NA--";
                                                        ?></span>
                                            </div>
                                        </div>

                                        <?php //} 
                                        ?>



                                    </div>



                                    <div class="collapse " id="manualtransaction">
                                        <h4 style="color: #2a3542;">Manual Transaction</h4>
                                        <div class="col-sm-12 ">
                                            <form method="POST" enctype="multipart/form-data" id="manual_form" action="<?= AUTH_PANEL_URL . "course_product/course_transactions/add_manual_course" ?>" autocomplete="off">
                                                <input type='hidden' name="user_id" value='<?= $user_data['id']; ?>'>
                                                <div class="form-group  col-md-6">
                                                    <label for="pay_via">Payment Mode</label>
                                                    <select class="form-control input-xs" id="pay_via" name='pay_via' required="">
                                                        <option value=''>Select Mode</option>                                                        
                                                        <option value='RAZOR_PAY'>Razor Pay</option>
                                                        <option value='CASH'>Cash</option>
                                                        <option value='BANK'>Bank</option>
                                                        <option value='FREE'>Free</option>
                                                        <option value='EMI'>EMI</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="text">SELECT course</label>
                                                    <select class="form-control input-xs course_id" name='course_id' id="select_course" required="">
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="text">Transaction Via</label>
                                                    <select class="form-control input-xs" id="transection_via" name='transection_via' required="">
                                                        <option value='4'>Manual</option>
                                                        <option value='1'>Android</option>
                                                        <option value='2'>iOS</option>
                                                        <option value='3'>Website</option>
                                                    </select>
                                                </div>
                                                <input type="email" name="user_id" id="userid" value="<?= $user_data['id']; ?>" hidden>
                                                <div class="form-group  col-md-6">
                                                    <label for="text">Price</label>
                                                    <select class="form-control input-sm m-bot15 price_id" disabled="">
                                                        <option value=''>Select Amount</option>
                                                    </select>
                                                </div>
                                                <div class="form-group  col-md-6">
                                                    <label for="text">Offer Price</label>
                                                    <input type="text" id="price_offered" class=" form-control input-sm m-bot15 offer_price_id" name="course_price">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Validity </label>
                                                    <select class="form-control input-xs" name='validity_from' id="validity_from">
                                                        <option value='1'>Fetch from Course</option>
                                                        <option value='2'>Custom</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6" id="txn_id">
                                                    <label>Post Transaciton Id</label>
                                                    <input type="text" class="form-control input-xs" id="post_transaction_id" name="post_transaction_id" required="">
                                                </div>
                                                <div class="form-group col-md-6" id="validity_wise" style="display:none">
                                                    <label>Validity By </label>
                                                    <select class="form-control input-xs" name='validity_wise'>
                                                        <option value='1'>Date wise (today to till date)</option>
                                                        <option value='2'>Day wise (number of days)</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6" id="date_wise" style="display:none">
                                                    <label>Date</label>
                                                    <input type="text" class="form-control input-xs" name="date" autocomplete="off">
                                                </div>
                                                <div class="form-group col-md-6" id="day_wise" style="display:none">
                                                    <label>Days</label>
                                                    <input type="number" min="1" class="form-control input-xs" name="days" autocomplete="off">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label>Remark</label>
                                                    <textarea name="remark" class="form-control input-xs" placeholder="Write your remark here" required=""></textarea>
                                                </div>
                                                <div class="row" id="add_installment_id" style="display:none;">
                                                    <div id="tabContent4" class="tabu">
                                                        <section class="panel">
                                                            <header class="panel-heading" style="background:none;">
                                                                <!-- Price Manager -->
                                                                <a href="javascript:void(0)" id="add_row" class="btn btn-success btn-xs pull-right">Add Section</a>
                                                            </header>
                                                            <div class="panel-body get_emi_data">

                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <?php
                                                    if (isset($f_lists->Manual_Transaction_password) && $f_lists->Manual_Transaction_password == 1) {
                                                    ?>
                                                        <button type="submit" class="btn btn-info btn-xs">Submit</button>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <a href="javascript:void(0)" onclick="checkvalidation()" class="btn btn-info btn-xs">Submit</a>
                                                    <?php
                                                    }
                                                    ?>
                                                    <button type="button" class="btn btn-warning btn-xs" onclick="$('#man_pan').hide('slow')">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        </section>
                    </div>
                    <!--tab-end-->
                    <!--tab-scnd-->
                    <div class="tab-pane bio-graph-info" id="tab5">

                        <?php // echo "hi device";
                        // echo $user_data['device_id'];
                        // echo "<br>";
                        // echo 'user id'.$user_data['id'];
                        if ($user_data['id'] != 0) { ?>
                            <section class="panel">
                                <header class="panel-heading bg-dark text-white">
                                    Device Information
                                </header>
                                <div class="panel-body">
                                    <?php
                                    if (!empty($user_devices)) {
                                        foreach ($user_devices as $key => $user_device) {
                                    ?>
                                            <h4> Device <?php echo ++$key; ?></h4>
                                            <hr>
                                            <div class="form-group col-md-12">
                                                <label>Device Type:</label>
                                                <span><?= device_type($user_device['device_type']); ?></span>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>Device Token:</label>
                                                <span><?= $user_device['device_token']; ?></span>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>Device Id:</label>
                                                <span><?= $user_device['device_id']; ?></span>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>Device Model:</label>
                                                <span><?= $user_device['device_model']; ?></span>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>Current Status:</label>
                                                <span><?php if (!empty($user_device['current_status'])) {
                                                            echo "Login";
                                                        } else {
                                                            echo "Logout";
                                                        } ?></span>
                                            </div>
                                            <?php
                                            if (!empty($user_device['current_status'])) {
                                            ?>
                                                <div class="form-group col-md-12">
                                                    <button class="btn btn-success btn-xs bold pull-right logout_btn" data-url="<?php echo site_url('auth_panel/web_user/user_logout/' . $user_data['id'] . "/" . $user_device['id']); ?>">Logout</button>
                                                </div>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </section>
                        <?php } ?>
                    </div>

                    <div class="tab-pane bio-graph-info" id="tab6">
                        <section class="panel">
                            <header class="panel-heading bg-dark text-white">
                                Profiles
                            </header>
                            <table class="table table-striped p-4">
                                <tbody>
                                    <?php foreach ($user_profile as $profile) : ?>
                                        <tr>
                                            <?php if ($profile['is_kid'] == 1) {  ?>
                                                <td width="10%;"><img src="<?= base_url('assets/kid.png'); ?>" style="width: 40px;height: 40px;border-radius: 50%;background: #818488;padding: 3px;"></td>
                                                <td width="10%;">
                                                    <span><?= $profile['username']; ?></span><br>
                                                    <span class="badge badge-primary" style="font-size: 10px;">Kid</span>
                                                </td>
                                            <?php } else { ?>
                                                <td width="10%;"><img src="<?= base_url('assets/userprofile.png'); ?>" style="width: 40px;height: 40px;border-radius: 50%;background: #818488;padding: 3px;"></td>
                                                <td width="10%;">
                                                    <span><?= $profile['username']; ?></span><br>
                                                    <span class="badge badge-primary" style="font-size: 10px;">Adult</span>
                                                </td>
                                            <?php } ?>
                                            <td></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </section>
                    </div>
                </div><!--tab content end-->

            </div>
        </div>
    </section>


    <?php
    //if ($this->session->userdata('active_user_data')->manual_txn) {
    ?>
    <section class="panel" id="man_pan" style="display:none">
        <header class="panel-heading">Add Manual</header>
        <div class="panel-body">
            <div class="col-sm-12 no-padding">
                <form action="<?= AUTH_PANEL_URL . "course_product/course/add_manual_entry" ?>" method="POST">
                    <input type='hidden' name="user_id" value='<?= $user_data['id']; ?>'>
                    <div class="form-group col-md-12">
                        <label for="text">SELECT Course</label>
                        <select class="form-control input-xs" id="add_course_ids" data-live-search="true" name='add_course_ids[]' multiple="">
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-md-6">
                        <label for="text">Payment Mode</label>
                        <select class="form-control input-xs" name='pay_via'>
                            <option value=''>Select Mode</option>
                            <!--<option value='RAZOR_PAY'>Razor Pay</option>-->
                            <option value='CASH'>Cash</option>
                            <option value='BANK'>Bank</option>
                            <option value='FREE'>Free</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="text">Transaction Via</label>
                        <select class="form-control input-xs" name='transection_via'>
                            <option value=''>Select Type</option>
                            <option value='1'>Android</option>
                            <option value='2'>IOS</option>
                            <option value='3'>Website</option>
                            <option value='4'>Manual</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6" id="post_transaction_id">
                        <label for="text">Post Transaction ID</label>
                        <input class="form-control input-xs" name='post_transaction_id' placeholder="Enter Post Transaction ID">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="text">Remark</label>
                        <textarea name="remark" class="form-control input-xs" placeholder="Write your remark here"></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary btn-xs pull-right">submit</button>
                        <button type="reset" class="btn btn-red btn-xs pull-right" onclick="$('#man_pan').hide('slow');">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php //} 
    ?>
    <style>
        .course_desc {

            max-height: 40px;
            min-height: 40px;
            overflow: hidden;
        }

        .course_tags {
            max-height: 40px;
            min-height: 40px;
            overflow: hidden;
        }

        .c-title {
            height: 30px;
            overflow-y: scroll;
        }

        .course_lock {
            position: absolute;
            top: 5px;
            right: 5px;
            display: none;
        }
    </style>
    <section class="panel" id="course_transfer" style="display: none;">
        <div class="panel-heading">
            Transfer Courses
        </div>
        <div class="panel-body  bio-graph-info">
            <form role="form" method="POST" id="transfer_course_form">
                <div class="form-group col-md-12">
                    <label>Search User By (Id,Name,Mobile,Email)</label>
                    <select class="user_id form-control input-xs" name="user_id">
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID.</th>
                                <th>Title</th>
                                <th>Valid From</th>
                                <th>Valid To</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="transfer_course">

                        </tbody>
                    </table>
                </div>
                <div class="form-group" style="margin-top: 13px">
                    <button type="submit" class="btn btn-xs  btn-info bold">Submit</button>
                    <button type="button" class="btn btn-xs btn-danger bold " onclick="$('#course_transfer').hide('slow');">Cancel </button>
                </div>
            </form>
        </div>
    </section>

</aside>
<div id="confirmpassword" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirm Password</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password" id="confirm_password" placeholder="Confirm Password" class="form-control">
                </div>
                <div class="form-group text-center">
                    <a href="javascript:void(0)" onclick="confirm_password()" class="btn btn-success btn-sm">Confirm</a>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    function checkvalidation() {
        if ($('#pay_via').val().length == 0) {
            show_toast('error', 'Payment Mode Field Required', 'Payment Mode');
            return false;
        }

        if ($('#transection_via').val().length == 0) {
            show_toast('error', 'Transaction Field Required', 'Transaction');
            return false;
        }

        if ($('#price_offered').val().length == 0) {
            show_toast('error', 'Price Offered Field Required', 'Price Offered');
            return false;
        }

        if ($('#validity_from').val().length == 0) {
            show_toast('error', 'Validity Field Required', 'Validity');
            return false;
        }


        $('#confirmpassword').modal('show');
    }

    function confirm_password() {
        var password = $('#confirm_password').val();

        if (password.length == 0) {
            show_toast('error', 'Confirm Password Field Required', 'Confirm Password');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: "<?= AUTH_PANEL_URL ?>" + "course_product/course_transactions/confirm_password",
            data: {
                "password": password
            },
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $('#manual_form').submit();
                } else {
                    show_toast('error', 'Invalid Password', 'Confirm Password');
                    return false;
                }
            }
        });

    }


    $(document).ready(function() {
        $('#select_course').change(function() {
            var id = $(this).val();
            if (!id) {
                show_toast('error', 'Please Select Course', 'InValid Course');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: "<?= AUTH_PANEL_URL ?>" + "course_product/course/get_price_from_course/" + id + "?return=json",
                dataType: 'json',
                success: function(data) {
                    if (data.data == 1) {
                        var html = "<input value=''>--select--</option>";
                        var html1 = "";
                        $.each(data.result, function(key, value) {
                            html += "<option value='" + value.id + "'>" + value.mrp + "</option>";
                            html1 = value.course_sp;
                            $('.offer_price_id').val(html1);
                        });
                        $(".price_id").html(html);
                        show_toast('success', 'Course Price', 'Successful');
                    } else {
                        show_toast('error', 'Course Price Not Found', 'Error');
                    }
                },
                error: function(data) {

                }
            });
        });
        $('#select_payment_type').change(function() {
            var payment_type = $(this).val();
            if (payment_type == '1') {
                $("#payment_type_id").css('display', 'block');
            }
        });
    });


    $(document).ready(function() {
        $('#select_course').change(function() {
            var course_id = $("#select_course").val();
            var user_id = $('.user_id').val();
            jQuery.ajax({
                url: "<?= AUTH_PANEL_URL ?>course_product/course/get_pricing_emi",
                method: 'POST',
                // dataType: 'json',
                async: false,
                data: {
                    "course_id": course_id,
                    "user_id": user_id
                },
                success: function(data) {
                    $('#overlay').hide();
                    $('.get_emi_data').html(data);
                }
            });
        });
    });

    $('#add_row').click(function() {
        let length = $("#edit_pricing_table tbody").children().length;
        let limit = $("#edit_pricing_table tbody tr:first select").children().length;
        if (length < limit) {
            var row_html = $("#edit_pricing_table tbody tr:eq(0)").html();
            $('#edit_pricing_table tbody').append('<tr>' + row_html + '</tr>');
            $("#edit_pricing_table tbody tr:last input").val("");
            $("#edit_pricing_table tbody tr:last select").val(length + 1);
            $("#edit_pricing_table tbody tr:last select").find("option").prop("enable", true);
            $("#edit_pricing_table tbody tr:last select").find("option:eq(" + length + ")").prop("disabled", false);
            $("#edit_pricing_table tbody tr:last a.update_pricing_element").text("Save EMI");
            $("#edit_pricing_table tbody tr:last a.update_pricing_element").attr("data-id", "");
            $("#edit_pricing_table tbody tr:last a.delete_pricing_element").text("Remove");
        } else {
            show_toast('warning', "5 Emi Allowed Only", "Limited AMI Avallable");
        }
    });
    $(document).on('click', '.delete_pricing_element', function(event) {
        if ($(this).text() == "Remove") {
            $(event.target).closest("tr").remove();
            return false;
        }
        if (!confirm("Are you sure want to remove this row.")) {
            return false;
        }

        var section_id = $(this).data('id');
        jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>course_product/course/delete_emi_pricing",
            method: 'POST',
            dataType: 'json',
            data: {
                "id": section_id
            },
            success: function(data) {
                if (data.data == '1') {
                    $(event.target).closest("tr").remove();
                    show_toast('success', "Pricing Row Removed From Course.", "EMI Pricing Deleted");
                } else if (data.data == '2') {
                    show_toast('error', "EMI Row Already in use,Cannot delete it", "Emi Pricing Row");
                }
            },
        });
    });

    $('#pay_via').change(function() {
        var value = $(this).val();
        $("#add_installment_id").css('display', 'none');
        if (value == 'EMI') {
            $("#add_installment_id").css('display', 'block');
            $("#payment_type_id").css('display', 'block');
        }

        $('.course_id').removeAttr("multiple");
        $(".user_id").removeAttr("multiple");
        if (value == 'FREE') {
            $('.course_id').attr("multiple", true);
            $(".user_id").attr("multiple", true);
            $('.course_id').select2('destroy');
            $('.user_id').select2('destroy');
        } else {
            $('.course_id').select2('destroy');
            $('.user_id').select2('destroy');
        }
        course_select();
        user_select();
        backend_user_select();

    });

    $('.user-form').click(function() {
        var pay_via = $('#pay_via').val();
        var emi_mrp = $('.emi_mrp').val();
        var paid_price = $('.paid_price').val();
        var offer_price_id = $('.offer_price_id').val();
        var pay_via = $('#pay_via').val();
        var sum = 0;
        $("input[class *= 'emi_mrp']").each(function() {
            sum += +$(this).val();
        });

        if (pay_via == 'EMI' && (offer_price_id != sum)) {
            show_toast("warning", "Course price and Emi Price not matched", 'Course price and Emi Price not matched');
            return false;
        }
        //    } else if (paid_price == '') {
        //     show_toast("warning", "Please Amount", 'Please Enter Amount');
        //     return false;
        // }
    });


    $("input[name='date']").datepicker({
        "format": 'dd-mm-yyyy',
        "startDate": new Date()
    });


    $('#validity_from').change(function() {
        var value = $(this).val();
        $("input[name='date']").removeAttr("required");
        $("input[name='days']").removeAttr("required");
        if (value == 2) {
            $('#validity_wise').show();
            $('#date_wise').show();
            $("input[name='date']").attr("required", true);
        } else {
            $('#validity_wise').hide();
            $('#date_wise').hide();
            $('#day_wise').hide();
        }
    });
    $("select[name='validity_wise']").change(function() {
        var value = $(this).val();
        $("input[name='date']").removeAttr("required");
        $("input[name='days']").removeAttr("required");
        $("input[name='days']").val("");
        $("input[name='date']").val("");
        if (value == 2) {
            $('#date_wise').hide();
            $('#day_wise').show();
            $("input[name='days']").attr("required", true);
        } else {
            $('#date_wise').show();
            $('#day_wise').hide();
            $("input[name='date']").attr("required", true);
        }
    });

    function course_select() {
        $('.course_id').select2({
            placeholder: 'Select an item',
            theme: "material",
            allowClear: true,
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/course_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function user_select() {
        $('.user_id').select2({
            placeholder: 'Select an item',
            theme: "material",
            allowClear: true,
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/user_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function(data) {
                    if (data) {
                        return {
                            results: data
                        };
                    }
                },
                cache: true
            }
        });
    }
    //function for backend manual course
    function backend_user_select() {
        $('.user_id').select2({
            placeholder: 'Select an item',
            theme: "material",
            allowClear: true,
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/backend_user_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function(data) {
                    if (data) {
                        return {
                            results: data
                        };
                    }
                },
                cache: true
            }
        });
    }

    $(document).ready(function() {
        course_select();
        user_select();
        $("body").on("change", "select[name=pay_via]", function() {
            var payVia = $(this).val();
            if (payVia == 'FREE')
                $("#txn_id").hide('slow');
            else
                $("#txn_id").show('slow');

        });
    });
</script>
<script type="text/javascript" language="javascript">
    jQuery(document).ready(function() {

        $('#add_course_ids').select2({
            placeholder: 'Select Courses',
            theme: "classic",
            allowClear: true,
            width: 'resolve',
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/course_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function(data) {
                    course_data = data;
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });


        $("#add_course_ids").change(function() {
            var selectedCourse = $(this).val();
            if (selectedCourse.length > 1) {
                $("#post_transaction_id").hide();
                $("select[name=pay_via]").val("FREE");
            } else {
                $("#post_transaction_id").show();
                $("select[name=pay_via]").val("");
            }
        });
        //user list search
        $('.user_id').select2({
            placeholder: 'Select an item',
            theme: "material",
            allowClear: true,
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/user_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });


        // $("#paid_course_section").hide();
        // $("#free_course_section").hide();
        // $("#batch_course_section").hide();
    });
    var courseIds = new Array();
    $("body").on("click", ".course_ids:checked", function() {
        if ($(this).is(":checked")) {
            courseIds.push($(this).val());
        }
    });

    $("body").on("change", "select[name='pay_via']", function() {
        var payVia = $(this).val();
        if (payVia == "FREE") {
            $("#post_transaction_id").hide();
        } else {
            $("#post_transaction_id").show();
        }
    });

    $("#transfer_course_form").submit(function(event) {
        event.preventDefault();
        var formData = {};
        var validate = true;
        var userId = $("select[name=user_id]").val();
        if (courseIds.length == 0) {
            show_toast("error", "Course Transfer", "Atleast select one course to transfer!");
            validate = false;
        }
        if (!userId) {
            show_toast('error', "Course Transfer", "Select a user to transfer course!");
            validate = false;
        }
        if (validate) {
            formData.course_id = courseIds;
            formData.transfer_to = userId;
            formData.transfer_from = "<?= $user_data['id']; ?>";
            $.ajax({
                data: formData,
                url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/course_transfer",
                type: "POST",
                async: false,
                dataType: "JSON",
                success: function(transferResponse) {
                    window.location.reload();
                }
            });
        }

    });

    function updatEmailMobile(valtype, updateValue) {
        if (updateValue.length > 0) {
            var updateData = {}
            updateData.user_id = "<?= $user_data['id']; ?>";
            switch (valtype) {
                case 'email':
                    updateData.email = updateValue;
                    break;
                case 'mobile':
                    updateData.mobile = updateValue;
                    break;
            }
            $.ajax({
                data: updateData,
                type: "POST",
                url: "<?= AUTH_PANEL_URL ?>web_user/update_email_mobile",
                async: false,
                dataType: "json",
                success: function(resHtml) {
                    if (resHtml) {
                        window.location.reload();
                    }
                }
            });
        }
    }

    $("body").on("click", "#update_pass", function() {
        var pass = $("input[name=user_pass]").val();
        var confirmPass = $("input[name=re_user_pass]").val();
        var userId = "<?= $user_data['id']; ?>";
        if (pass == confirmPass) {
            $.ajax({
                data: {
                    "user_pass": pass,
                    "user_id": userId
                },
                type: "POST",
                url: "<?= AUTH_PANEL_URL; ?>web_user/update_user_pass",
                async: false,
                dataType: "JSON",
                success: function(responseHtml) {
                    window.location.reload();
                }
            });
        } else {
            show_toast("warning", "Please enter same password.", "User Profile");
        }
    });
    $(".editable_input_email").click(function() {
        var thiss = $(this);
        var get = $(this).data('edit_id');
        if ($(this).html() == 'Edit') {
            $(this).html('save');
            inner = $(get).html();
            var input = "<input id='edit_email' type='text' value='" + inner + "'  />";
            $(get).html(input);
        } else {
            var inp = $.trim($('#edit_email').val())
            $(this).html("<i class='fa fa-refresh fa-spin'></i>");
            $.ajax({
                url: "<?= AUTH_PANEL_URL ?>web_user/update_email_mobile",
                data: {
                    "user_id": "<?= $user_data['id']; ?>",
                    "email": inp
                },
                method: 'POST',
                success: function(response) {
                    location.reload();
                }
            });
        }
    });
    $(".editable_input_state").click(function() {
        var thiss = $(this);
        var get = $(this).data('edit_id');
        if ($(this).html() == 'Edit') {
            $(this).html('save');
            inner = $(get).html();
            var input = "<input id='edit_state' type='text' value='" + inner + "'  />";
            $(get).html(input);
        } else {
            var inp = $.trim($('#edit_state').val())
            $(this).html("<i class='fa fa-refresh fa-spin'></i>");
            $.ajax({
                url: "<?= AUTH_PANEL_URL ?>web_user/update_state_mobile",
                data: {
                    "user_id": "<?= $user_data['id']; ?>",
                    "state": inp
                },
                method: 'POST',
                success: function(response) {
                    location.reload();
                }
            });
        }
    });
    $(".editable_input_city").click(function() {
        var thiss = $(this);
        var get = $(this).data('edit_id');
        if ($(this).html() == 'Edit') {
            $(this).html('save');
            inner = $(get).html();
            var input = "<input id='edit_city' type='text' value='" + inner + "'  />";
            $(get).html(input);
        } else {
            var inp = $.trim($('#edit_city').val())
            $(this).html("<i class='fa fa-refresh fa-spin'></i>");
            $.ajax({
                url: "<?= AUTH_PANEL_URL ?>web_user/update_city_mobile",
                data: {
                    "user_id": "<?= $user_data['id']; ?>",
                    "city": inp
                },
                method: 'POST',
                success: function(response) {
                    location.reload();
                }
            });
        }
    });
    $(".editable_input_mobile").click(function() {
        var thiss = $(this);
        var get = $(this).data('edit_id');
        if ($(this).html() == 'Edit') {
            $(this).html('save');
            inner = $(get).html();
            var input = "<input id='edit_mobile' type='text' onkeydown='return (event.ctrlKey || event.altKey || (47 < event.keyCode && event.keyCode < 58 && event.shiftKey == false) || (95 < event.keyCode && event.keyCode < 106) || (event.keyCode == 8) || (event.keyCode == 9) || (event.keyCode > 34 && event.keyCode < 40) || (event.keyCode == 46))' maxlength='10' value='" + inner + "'  />";
            $(get).html(input);
        } else {
            var inp = $.trim($('#edit_mobile').val())
            $(this).html("<i class='fa fa-refresh fa-spin'></i>");
            $.ajax({
                url: "<?= AUTH_PANEL_URL ?>web_user/update_email_mobile",
                data: {
                    "user_id": "<?= $user_data['id']; ?>",
                    "mobile": inp
                },
                method: 'POST',
                success: function(response) {
                    location.reload();
                }
            });
        }
    });

    $(".editable_input_password").click(function() {

        var thiss = $(this);
        var get = $(this).data('edit_id');
        if ($(this).html() == 'Edit') {
            $(this).html('save');
            inner = $(get).html();
            var input = "<input id='edit_password' type='password' minimum='8' maxlength='20' placeholder='Enter New Password Here'  />";
            $(get).html(input);
        } else {
            if ($('#edit_password').val().length > 0) {
                if ($('#edit_password').val().length < 9) {
                    //check min length of 9
                    show_toast("warning", "Kindly Fill new password Min 9 length to update", "User Profile");
                } else if ($('#edit_password').val().length > 9) {
                    //check max length of 9
                    show_toast("warning", "Kindly Fill new password Max 9 length to update", "User Profile");
                } else {
                    var inp = $.trim($('#edit_password').val())
                    $(this).html("<i class='fa fa-refresh fa-spin'></i>");
                    $.ajax({
                        url: "<?= AUTH_PANEL_URL ?>web_user/update_user_pass",
                        data: {
                            "user_pass": inp,
                            "user_id": "<?= $user_data['id']; ?>"
                        },
                        method: 'POST',
                        success: function(response) {
                            location.reload();
                        }
                    });
                }
            } else {
                show_toast("warning", "Kindly Fill new password to update", "User Profile");
            }
        }
    });

    let user_id = "<?= $user_data['id'] ?>";
    $(".editable_input").click(function() {
        var thiss = $(this);
        var get = $(this).data('edit_id');
        if ($(this).html() == 'edit') {
            $(this).html('save');

            inner = $(get).html();
            var input = "<input id='edit_name_in' type = 'text' value='" + inner + "'  />";
            $(get).html(input);
        } else {
            var inp = $.trim($('#edit_name_in').val())
            $(this).html("<i class='fa fa-refresh fa-spin'></i>");
            $.ajax({
                url: "<?= AUTH_PANEL_URL ?>web_user/update_name/" + user_id,
                data: {
                    a: inp
                },
                dataType: 'json',
                async: false,
                method: 'POST',
                success: function(response) {
                    if (response.status) {
                        thiss.html('edit');
                        show_toast("success", "User Profile", response.message);
                    } else {
                        show_toast("warning", "User Profile", response.message);
                    }
                }
            });
            $(get).html(inp);
        }
    });


    //for designation editable
    $(".editable_input_desg").click(function() {
        var thiss = $(this);
        var get = $(this).data('edit_id');
        if ($(this).html() == 'edit') {
            $(this).html('save');
            inner = $(get).html();
            var input = "<input id='edit_name_in' type = 'text' value='" + inner + "'  />";
            $(get).html(input);
        } else {
            var inp = $.trim($('#edit_name_in').val())
            $(this).html("<i class='fa fa-refresh fa-spin'></i>");
            $.ajax({
                url: "<?= AUTH_PANEL_URL ?>web_user/update_desg/" + user_id,
                data: {
                    a: inp
                },
                method: 'POST',
                success: function(response) {
                    console.log("success");
                    $(thiss).html('edit');
                }
            });
            $(get).html(inp);
        }
    });


    //for speciality
    $(".editable_input_spec").click(function() {
        var thiss = $(this);
        var get = $(this).data('edit_id');
        if ($(this).html() == 'edit') {
            $(this).html('save');

            inner = $(get).html();
            var input = "<input id='edit_name_in' type = 'text' value='" + inner + "'  />";
            $(get).html(input);
        } else {
            var inp = $.trim($('#edit_name_in').val())
            $(this).html("<i class='fa fa-refresh fa-spin'></i>");
            $.ajax({
                url: "<?= AUTH_PANEL_URL ?>web_user/update_spec/" + user_id,
                data: {
                    a: inp
                },
                method: 'POST',
                success: function(response) {
                    $(thiss).html('edit');
                }
            });
            $(get).html(inp);
        }
    });
    $(".course_lock").click(function() {
        let selector = $(this);
        let text = "";
        if (selector.hasClass("btn-success")) {
            text = prompt("Please Enter Message");
            if (text == "" || text == null) {
                show_toast("warning", "Please enter valid message", "Message error!");
                return false;
            }
        }
        if (!confirm("Are you sure want to " + (text == "" ? "unlock" : "lock") + " this course?")) {
            return false;
        }

        $.ajax({
            url: "<?= AUTH_PANEL_URL ?>web_user/lock_unlock_txn",
            data: {
                text: text,
                id: $(this).data("id")
            },
            method: 'POST',
            success: function(data) {
                if (text != "") {
                    selector.removeClass("btn-success");
                    selector.addClass("btn-danger");
                    selector.find("i").removeClass("fa-unlock");
                    selector.find("i").addClass("fa-lock");
                } else {
                    selector.addClass("btn-success");
                    selector.removeClass("btn-danger");
                    selector.find("i").addClass("fa-unlock");
                    selector.find("i").removeClass("fa-lock");
                }
            }
        });
    });

    function isUrlValid(url) {
        return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
    }

    let loaded_content = {};

    function format_timestamp(timestamp) {
        var dt = new Date(timestamp * 1000);
        const month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        return dt.getDate() + " " + (month[dt.getMonth()]) + " " + dt.getFullYear() + " " + dt.getHours() + ":" + dt.getMinutes();
    }


    function no_data_found(section_type) {
        let date = new Date();
        let html = '<ul class="thumbnails">';
        html += '<li>';
        html += '<p>No data found</p>';
        html += '</li>';
        return html;
    }

    function generate_html(object) {
        let date = new Date();
        let html = '<ul class="thumbnails">';
        let valid_from = "";
        let valid_to = "";
        $.each(object, function(index, value) {
            valid_from = value.created_at == null ? "Free ID's" : format_timestamp(value.created_at);
            valid_to = value.valid_to == null ? "Free ID's" : format_timestamp(value.valid_to);
            html += `<li class="col-md-4" style="padding-right: 0px;">
                                                <div class="thumbnail" style="padding: 0px">
                                                    <img style="height:141px;width: 100%" alt="` + value.title + `" src="` + (isUrlValid(value.desc_header_image) ? value.desc_header_image : "<?= AUTH_ASSETS . "img/course_default.jpg" ?>") + `">`;
            if (value.valid_to && value.valid_from && (value.valid_to < (date.getTime() / 1000) || value.valid_from > (date.getTime() / 1000))) {
                if (value.cat_type == 1) {
                    html += '';
                } else {
                    html += '<img style="position: absolute;opacity: 0.3;height: 50%;width: 92%;" title="Valid From: ' + valid_from + '&#013;Valid To: ' + valid_to + '" src="<?= AUTH_ASSETS . 'expired.png'; ?>">';
                }
            } else if (value.transaction_status == 6) {
                html += '<img style="position: absolute;opacity: 0.3;height: 50%;width: 92%;" title="Valid From: ' + valid_from + '&#013;Valid To: ' + valid_to + '" src="<?= AUTH_ASSETS . 'transfer.png' ?>">';
            }
            html += `<div class="caption">
                        <h5 class="c-title">` + value.title + " (" + value.id + ")" + `</h5>
                    </div>
                    <table class="table table-hover personal-task">
                        <tbody>
                            <tr>
                                <td>Purchase Date</td>
                                <td>
                                    <span class="badge badge-pill badge-success pull-right" title="` + valid_from + `">` + valid_from + `</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Valid Thru</td>
                                <td>
                                    <span class="badge badge-pill badge-warning pull-right" title="` + valid_to + `">` + valid_to + `</span>
                                </td>
                            </tr>
                            </tbody>
                    </table>
                    <div class="panel-heading free-flexc">
                        <a class="btn btn-info btn-xs " href="<?= AUTH_PANEL_URL . 'course_product/course/edit_course_page?course_id=' ?>` + value.id + `">View Course</a>
                        <a class="btn btn-info btn-xs" href="<?= AUTH_PANEL_URL . 'course_product/course_transactions/course_transaction_details?transaction_id=' ?>` + value.transaction_id + `" title="View Transaction" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                        <a class="btn btn-info btn-xs" href="<?= AUTH_PANEL_URL . 'course_product/course_transactions/user_course_data/' . $user_data['id'] . '/' ?>` + value.id + `">View Videos History</a>
                    </div>
                </div>
            </li>`;
        });


        html += '</ul>';
        return html;
    }

    function generate_transfer_course(courses) {
        let html = "";
        if (Object.keys(courses).length > 0) {
            $.each(courses, function(index, value) {
                if (value.transaction_status == "1") {
                    var validFrom = new Date(value.valid_from);
                    html += `<tr>
                                <td>` + value.id + `</td>
                                <td>` + value.title + `</td>
                                <td>` + (value.valid_from == null ? "Free ID's" : format_timestamp(value.valid_from)) + `</td>
                                <td>` + (value.valid_to == null ? "Free ID's" : format_timestamp(value.valid_to)) + `</td>
                                <td><img width="30" src="` + (isUrlValid(value.cover_image) ? value.cover_image : "<?= AUTH_ASSETS . "img/course_default.jpg" ?>") + `"></td>
                                <td><img width="30" src="` + (isUrlValid(value.desc_header_image) ? value.desc_header_image : "<?= AUTH_ASSETS . "img/course_default.jpg" ?>") + `"></td>
                                <td><input type="checkbox" class="course_ids" name="course_id[]" value="` + value.id + `"></td>
                            </tr>`;
                }
            });
            $(".transfer_course").html(html);
        } else {
            html += '<tr><td colspan="6"><div class="alert alert-danger">Course are not available for transfer.</div></td></tr>';
            $(".transfer_course").html(html);
        }
    }

    function getCourseList(iconObj, section_type) {

        if (loaded_content[section_type] == undefined || loaded_content[section_type] == null) {

            //if ($(iconObj).hasClass("fa-chevron-up")) {

            $.ajax({
                data: {
                    "user_id": "<?= $user_data['id'] ?>",
                    "section": section_type
                },
                type: "POST",
                url: "<?= AUTH_PANEL_URL ?>web_user/get_user_courses",
                dataType: "json",
                success: function(response) {
                    ///alert(JSON.stringify(response.data));return false;
                    if (response.status) {
                        loaded_content[section_type] = response.data;
                        if (section_type == "paid_course_section") {
                            var transferCourseList = {};
                            $(response.data).each(function(idx, courses) {
                                transferCourseList[idx] = courses;
                            });
                            //filter course for transfer
                            generate_transfer_course(transferCourseList);
                            $(".btn_course_transfer").removeClass("hide");
                        }
                        if (response.data.length !== 0) {
                            $("#" + section_type).html(generate_html(response.data));
                        } else {
                            $("#" + section_type).html(no_data_found(section_type));
                        }
                    } else {
                        // $("#" + section_type).html(no_data_found(section_type));
                        show_toast("error", "User Courses", response.error);
                    }
                }
            });
            //}
        }

    }

    $(document).on('click', '.logout_btn', function() {
        var this_data = $(this);
        $.get($(this).data('url'), function(data, status) {
            if (data.status == true) {
                show_toast("success", "User Device", data.message);
                $(this_data).parent(".form-group").prev(".form-group").children("span").text("Logout");
                $(this_data).parent(".form-group").remove();
            } else {
                show_toast("error", "User Device", data.message);
            }
        });
    })
</script>