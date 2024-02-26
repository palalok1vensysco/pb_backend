<div class="col-lg-12 px-0" id="change_password_section" style="display:none;">
    <section class="panel">
        <div class="panel-body">
            <p class="panel-heading  bg-dark text-white">
                Change Password
            </p>
            <form action="<?php echo base_url('index.php/auth_panel/admin/change_password_backend_user'); ?>" name="change_password" method="post" autocomplete="off">
                <div class="form-group">
                    <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">
                    <label for="exampleInputEmail1">New Password</label>
                    <input type="password" class="form-control input-xs" name="new_password" id="new_password" placeholder="Enter new password" value="">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Confirm Password</label>
                    <input type="password" class="form-control input-xs"  name="conform_password" id="conform_password" placeholder="Enter confirm password" value="">
                </div>
                <button type="submit" id="change_password" class="btn-xs btn-primary">Submit</button>
                <button type="button"  class="btn-xs btn-warning" onclick="$('#change_password_section').hide('slow');" >Cancel</button>
            </form>
        </div>
    </section>
</div>
<div class="col-lg-12 px-0">
    <section class="panel">
        <header class="panel-heading  bg-dark text-white">
            Update Backend User <button type="button" class="btn-xs btn-success pull-right" onclick="$('#change_password_section').show('slow');"><i class="fa fa-key"></i> Change Password</button>
        </header>
        <div class="panel-body">
            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">
                <div class="form-group">
                    <label for="exampleInputEmail1">User Name</label>
                    <input type="text" class="form-control input-xs" id="exampleInputEmail1" name="username" placeholder="Enter User Name" value="<?php echo $user_data['username']; ?>" >
                    <span class="text-danger"><?php echo form_error('username'); ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control input-xs" id="exampleInputEmail1" name="email" placeholder="Enter email" value="<?php echo $user_data['email']; ?>">
                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputmobile1">Phone</label>
                    <input type="text" class="form-control input-xs" id="exampleInputmobile1" name="mobile" placeholder="Enter Mobile Number" value="<?php echo $user_data['mobile']; ?>">
                    <span class="text-danger"><?php echo form_error('mobile'); ?></span>
                </div>
                <div class="form-group">
                    <label>Studio List</label>
                    <select class="form-control input-xs" name="studio_id">
                        <option value="">--Select Studio--</option>
                        <?php
                            foreach($studio_list as $studio){
                                $selected = ($studio->id == $user_data['channel_ids'])?"selected":"";
                                echo '<option '.$selected.' value="'.$studio->id.'">'.$studio->name.'</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    
                    <label for="exampleInputPassword1">Role Permissions</label>
                    <select class="form-control input-xs" id="permission_group" name="permission_group">
                        <?php
                       $query_permission_group = $this->db->query("SELECT * FROM permission_group where 1 ".  app_permission("app_id"));
                        $result_permission_group = $query_permission_group->result_array();

                        echo '<option value="">Select Permission</option>';
                        foreach ($result_permission_group as $value_permission_group) {
                            // if ((isset($user_data['permission_group_id'])) && $user_data['permission_group_id'] != '' && $user_data['permission_group_id'] == $value_permission_group['id']) {
                            if ($value_permission_group['id']) {
                                echo '<option selected="selected" value="' . $value_permission_group['id'] . '">' . $value_permission_group['permission_group_name'] . '</option>';
                            } else {

                                echo "<option value=''>No data found</option>";
                              ////  echo '<option value="' . $value_permission_group['id'] . '">' . $value_permission_group['permission_group_name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <span class="text-danger"><?php echo form_error('permission_group'); ?></span>
                </div>
                <button type="submit" class="btn-xs btn-primary">Submit</button>
                <button type="button" onclick="window.location.reload();"  class="btn-xs btn-warning">Cancel</button>
            </form>
        </div>
    </section>
</div>
<script src="<?= AUTH_ASSETS ?>js/jquery.validate.min.js"></script>
<script>
    $(function () {
        $("form[name='change_password']").validate({
            // Specify validation rules
            rules: {
                new_password: "required",
                conform_password: {required: true, equalTo: "#new_password"}
            },
            // Specify validation error messages
            messages: {
                new_password: "Please enter new password.",
                conform_password: {required: "Please enter confirm password.", equalTo: "Password not match."}
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });


    $(function () {
        $('#new_password,#conform_password').on('keypress', function (e) {
            if (e.which == 32)
                return false;
        });
    });



    jQuery(document).ready(function () {
        $('#select_all').click(function (event) {
            if (this.checked) {
                // Iterate each checkbox
                $('.permission_checkboxes :checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                // Iterate each checkbox
                $('.permission_checkboxes :checkbox').each(function () {
                    this.checked = false;
                });
            }
        });


        $('.group_permision_all').click(function (event) {
            var ids = $(this).parent().parent().children('div').attr('id');
            if (this.checked) {
                // Iterate each checkbox
                $('#' + ids + ' :checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                // Iterate each checkbox
                $('#' + ids + ' :checkbox').each(function () {
                    this.checked = false;
                });
            }
        });
    });
</script>