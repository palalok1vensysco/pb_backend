<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<?php //echo "<pre>";print_r($user_data);die; ?>
    <div class="col-lg-12">
        <section class="panel">
         <header class="panel-heading custom-panel-heading">
            EDIT BACKEND USER
        </header>
          <div class="panel-body custom-panel-body">
          <form action="<?php echo base_url('index.php/auth_panel/admin/change_password_backend_user');?>" name="change_password" method="post" autocomplete="off">
              <div class="form-group">
                   <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">
                   <label for="exampleInputEmail1">New Password</label>
                   <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter new password" value="">
              </div>
              <div class="form-group">
                  <label for="exampleInputEmail1">Confirm Password</label>
                  <input type="password" class="form-control"  name="conform_password" id="conform_password" placeholder="Enter conform password" value="">
            </div>
                <button type="submit" id="change_password" class="btn btn-primary">Submit</button>
          </form>
          </div>
        </section>
    </div>
    <div class="col-lg-12">
        <section class="panel">
          <div class="panel-body">
              <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter email" value="<?php echo $user_data['email']; ?>">
                        <span class="custom-error"><?php echo form_error('email');?></span>
                    </div>
                     <div class="form-group">
                       
                        <label for="exampleInputEmail1">Employee ID</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="empid" placeholder="Enter email" value="<?php echo $user_data['empid']; ?>">
                        <span class="custom-error"><?php echo form_error('empid');?></span>
                    </div>
                     <div class="form-group">
                        
                        <label for="exampleInputEmail1">Employee Picture</label>
                         <input type="file" class="form-control"  name="user_image" id="exampleInputEmail1" >
                        <span class="custom-error"><?php echo form_error('user_image');?></span>
                    </div>
                  <div class="form-group">
                        <label for="exampleInputMobile">Mobile</label>
                        <input type="text" class="form-control" id="exampleInputMobile" name="mobile" placeholder="Enter Mobile" value="<?php echo $user_data['mobile']; ?>" >
                        <span class="custom-error"><?php echo form_error('mobile');?></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">User Name</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="username" placeholder="Enter User Name" value="<?php echo $user_data['username']; ?>" >
                        <span class="custom-error"><?php echo form_error('username');?></span>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">Role Permissions</label>
                            <select class="form-control" id="permission_group" name="permission_group">
                            <?php 
                              $query_permission_group = $this->db->query("SELECT * FROM `permission_group` ");
                              $result_permission_group = $query_permission_group->result_array();
                              echo '<option value="">Select Permission</option>';
                              foreach ($result_permission_group as $value_permission_group) {
                                if($user_data['permission_group_id'] != '' && $user_data['permission_group_id'] == $value_permission_group['id']) {
                                  echo '<option selected="selected" value="'.$value_permission_group['id'].'">'.$value_permission_group['permission_group_name'].'</option>';
                                } else {
                                echo '<option value="'.$value_permission_group['id'].'">'.$value_permission_group['permission_group_name'].'</option>';
                                }
                              }
                            ?>
                            </select>
                        <span class="custom-error"><?php echo form_error('permission_group');?></span>
                    </div>
               <button type="submit" class="btn btn-primary">Submit</button>
              </form>
          </div>
      </section>
    </div>
<?php

$custum_js = <<<EOD
               

               <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>

                <script>
                $(function () {
                    $("form[name='change_password']").validate({
                        // Specify validation rules
                        rules: {
                            new_password: "required",
                            conform_password:{required: true, equalTo: "#new_password"}
                        },
                        // Specify validation error messages
                        messages: {
                            new_password: "Please enter new password.",
                            conform_password: {required: "Please enter conform password.", equalTo:"Password not match."}
                        },
                        submitHandler: function (form) {
                            form.submit();
                        }
                    });
                });

               
              $(function() {
                $('#new_password,#conform_password').on('keypress', function(e) {
                  if (e.which == 32)
                      return false;
                  });
              });



              jQuery(document).ready(function() {
                    $('#select_all').click(function(event) {   
                        if(this.checked) {
                                // Iterate each checkbox
                               $('.permission_checkboxes :checkbox').each(function() {
                                    this.checked = true;                        
                                });
                        } else {
                              // Iterate each checkbox
                             $('.permission_checkboxes :checkbox').each(function() {
                                  this.checked = false;                        
                              });
                        }
                    });

                   
                    $('.group_permision_all').click(function(event) {  
                        var ids =  $(this).parent().parent().children('div').attr('id'); 
                        if(this.checked) {
                                // Iterate each checkbox
                               $('#'+ids+' :checkbox').each(function() {
                                    this.checked = true;                        
                                });
                        } else {
                              // Iterate each checkbox
                             $('#'+ids+' :checkbox').each(function() {
                                  this.checked = false;                        
                              });
                        }
                    });
                });
              </script>

EOD;

	echo modules::run('auth_panel/template/add_custum_js',$custum_js );
?>
