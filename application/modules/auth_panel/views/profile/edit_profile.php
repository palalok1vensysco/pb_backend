
<div>
   <div class="col-lg-4">
      <section class="panel">
         <header class="panel-heading bg-dark text-white d-flex justify-content-between align-items-center ">
            Update Password
         </header>
         <div class="panel-body">
            <form autocomplete="off" role="form" novalidate="novalidate" id="Update_password" method= "POST" enctype="multipart/form-data">
              <div class="form-group">
                <label for="">Current password  </label>
                <input type="password" class="form-control  input-sm" name="current_password" id="currentpass" value="" maxlength="30" placeholder="Enter current password">
                <span class="error bold"><?php echo form_error('current_password'); ?></span>
              </div>
              <div class="form-group">
                <label for="">New password  </label>
                <input type="password" id="new_password" class="form-control  input-sm" name="new_password"  maxlength="20"   value=""  placeholder="Enter new password">
                <span class="error bold"><?php echo form_error('new_password'); ?></span>
              </div>
              <div class="form-group" style="    position: relative;">
                <label for="">Re-enter new  password  </label>
                <input type="password" class="form-control  input-sm" name="renew_password"  maxlength="20" value="" id="cpass"  placeholder="Enter new password again">
                 <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                <span class="error bold"><?php echo form_error('renew_password'); ?></span>
              </div>
               <input type="submit" name="change_password" value="Update" class="btn btn-sm display_color text-white f-600">
            </form>
         </div>
      </section>
   </div>
   <?php
   /* user_data*/
   $userd = $this->db->where('id',$this->session->userdata('active_backend_user_id'))->get('backend_user')->row();
   ?>
   <div class="col-lg-4">
      <section class="panel">
         <header class="panel-heading bg-dark text-white d-flex justify-content-between align-items-center">
            Update Profile
         </header>
         <div class="panel-body">
            <form autocomplete="off" role="form" novalidate="novalidate" id="Update_profile" method= "POST" enctype="multipart/form-data">
              <div class="form-group">
                <label for="">Name  </label>
                <input type="text" class="form-control  input-sm" name="name" value="<?php echo $userd->username ;?>"  maxlength='50' placeholder="Enter name" oninput="this.value = this.value.replace(/[^a-z,A-Z, ]/, '')">
                <span class="error bold"><?php echo form_error('name'); ?></span>
              </div>
              <div class="form-group">
                <label for="">Email  </label>
                <input type="email"  class="form-control  input-sm" name="email" value="<?php echo $userd->email ;?>"   placeholder="Enter valid email">
                <span class="error bold"><?php echo form_error('email'); ?></span>
              </div>
              <div class="form-group">
                <label for="">Mobile Number   </label>
                <input type="" class="form-control  input-sm" name="mobile"  id='mobile'   maxlength="10" minlength="10"    value="<?php echo $userd->mobile ;?>"   placeholder="Enter 10 digit mobile number ">
                <span class="error bold"><?php echo form_error('mobile'); ?></span>
              </div>
               <input type="submit" name="change_profile" value="Update" class="btn btn-sm display_color text-white f-600">
            </form>
         </div>
      </section>
   </div>


	<div class="col-lg-4">
      <section class="panel">
         <header class="panel-heading bg-dark text-white d-flex justify-content-between align-items-center">
            Image
         </header>
         <div class="panel-body">
            <form autocomplete="off" role="form" novalidate="novalidate" id="Update_picture" method= "POST" enctype="multipart/form-data">
                <div class="form-group">
                  <div class="col-md-12">
                    <?php
                      if($userd->profile_picture != "" ){
                        echo '<img class="proImg" src="'.$userd->profile_picture.'">';
                      }
                    ?>
                  </div>
					          <label for="exampleInputFile">Upload Image</label>
					               <input type="file" accept="image/*" name = "profile_picture" value="<?php if(!empty($userd->profile_picture)){ echo $userd->profile_picture;                              
                                       }?>">
					            <span class="error bold"><?php echo form_error('profile_picture');?></span>
		             </div>
               <input type="submit" name="change_image" value="Update" class="btn btn-sm display_color text-white f-600">
            </form>
         </div>
      </section>
   </div>
   <div class="clearfix"></div>

</div>
<?php
$adminurl = AUTH_PANEL_URL;
$validation_js = AUTH_ASSETS."js/jquery.validate.min.js";
$custum_js = <<<EOD
              <script src="$validation_js" type="text/javascript"></script>
                <script>
                $("#mobile").keypress(function(event) {
                  var keycode = event.which;
                  if (!(keycode >= 48 && keycode <= 57)) {
                      event.preventDefault();
                  }
              });

				 var form = $("#Update_password");
                        form.validate({
                            errorPlacement: function errorPlacement(error, element) {
                                element.after(error);
                            },
                            rules: {
                                current_password: {
                                required: true
                              },
                                new_password: {
                                required: true,
                                maxlength:20,
                                minlength:8
                              },
                                renew_password: {
                                required: true,
                                maxlength:20,
                                minlength:8,
                                equalTo:"#new_password"
                                }
                            }
                        });
                        var form = $("#Update_picture");
                             form.validate({
                                 errorPlacement: function errorPlacement(error, element) {
                                     element.after(error);
                                 },
                                 rules: {
                                     profile_picture: {
                                     required: true
                                   }
                                 }
                             });
                             
                             var form = $("#Update_profile");
                                  form.validate({
                                      errorPlacement: function errorPlacement(error, element) {
                                          element.after(error);
                                      },
                                      rules: {
                                          name: {
                                          required: true
                                        },
                                          email: {
                                          required: true,
                                          maxlength:50
                                        },
                                          mobile: {
                                          required: true,
                                          maxlength:10,
                                          minlength:10
                                          }
                                      }
                                  });

                                     $(".toggle-password").click(function() {
                                              $(this).toggleClass("fa-eye fa-eye-slash");
                                              var input = $($(this).attr("toggle"));
                                              if ($("#cpass").attr("type") == "password") {
                                                 $("#cpass").attr("type", "text");
                                                 $("#currentpass").attr("type", "text");
                                                 $("#new_password").attr("type", "text");
                                              } else {
                                                $("#cpass").attr("type", "password");
                                                $("#currentpass").attr("type", "password");
                                                   $("#new_password").attr("type", "password");
                                              }
                                            });
              </script>
EOD;
echo modules::run('auth_panel/template/add_custum_js',$custum_js );
