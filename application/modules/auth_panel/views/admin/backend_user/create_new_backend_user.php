<div class="col-lg-12 px-0">
    <section class="panel">
        <div class="panel-heading  bg-dark text-white">
            Backend user Management
        </div>
        <div class="panel-body">
            <form action="" method="post" autocomplete="off">
                <div class="form-group col-md-6">
                    <label >Email address</label>
                    <input type="email" class="form-control input-xs" name="email" placeholder="Enter email" value="<?php echo set_value('email') ?>">
                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                </div>
                <div class="form-group col-md-6">
                    <label >Mobile Number</label>
                    <input type="text" class="form-control input-xs number" name="mobile"  maxlength='15' placeholder="Enter Mobile" value="<?php echo set_value('mobile') ?>">
                    <span class="text-danger"><?php echo form_error('mobile'); ?></span>
                </div>
                <div class="form-group col-md-6">
                    <label >User Name</label>
                    <input type="text" class="form-control input-xs" name="username" autocomplete="off"  maxlength='40' oninput="this.value = this.value.replace(/[^a-z,A-Z, ]/, '')" placeholder="Enter User Name" value="<?php echo set_value('username') ?>">
                    <span class="text-danger"><?php echo form_error('username'); ?></span>
                </div>
                <div class="form-group col-md-6">
                    <label >Password</label>
                    <input type="password" class="form-control input-xs" name="password" placeholder="Password"  maxlength='40' value="<?php echo set_value('password') ?>">
                    <span class="text-danger"><?php echo form_error('password'); ?></span>
                </div>

                <div class="form-group col-md-12">
                    <label >Role Permissions</label>
                    <select class="form-control input-xs" id="permission_group" name="permission_group">
                        <option value="">Select Permission</option>
                        <?php
                        foreach ($query_permission_group as $value_permission_group) {
                            echo '<option value="' . $value_permission_group['id'] . '">' . $value_permission_group['permission_group_name'] . '</option>';
                        }
                        ?>
                    </select>
                    <span class="text-danger"><?php echo form_error('permission_group'); ?></span>
                </div>
               <!--  <div class="form-group col-md-12">
                    <label>Studio List</label>
                    <select class="form-control input-xs" name="studio_id">
                        <option value="">--Select Studio--</option>
                        <?php 
                            foreach($studio_list as $studio){
                                echo '<option value="'.$studio->id.'">'.$studio->name.'</option>';
                            }
                        ?>
                    </select>
                </div> -->

                <div  class="form-group col-md-12">
                    <button type="submit" class="btn btn-sm display_color text-white f-600">Submit</button>
                    <button type="reset" class="btn btn-sm display_color text-white f-600">Reset</button>
                </div>
            </form>
        </div>
    </section>
</div>