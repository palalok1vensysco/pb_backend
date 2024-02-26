<style>
    .panel-info{
        margin-bottom: 5px;
    }
</style> 
<section class="panel">
    <header class="panel-heading">
        Make roles
        <?php 
            if(!empty($role_group)){
            $perm_app_id = $role_group;
                }else{
                    $perm_app_id = 0;
                }
        ?>

        <!-- <a href="<?php //AUTH_PANEL_URL?>admin/enable_functionality?app_id=<?=$app_id;?>" class="btn-xs btn-success pull-right" >Enable Functionality</a> -->
    </header>
    <div class="panel-body">
        <p class="accordion-expand-holder">
            <a class="accordion-expand-all" href="#">Expand all</a>
        </p>
        <form role="form" method="post" id="role">
            <input type="text" hidden="" value="<?= isset($perm_id) ? $perm_id : "" ?>" name="id">
            <input type="text" hidden="" value="<?= $app_id ?>" name="app_id">
            <input type="text" hidden="" value="<?= $master ?>" name="master">
            <div class="form-group col-md-12">
                <label>Role Name</label>
                <input type="text" placeholder="Enter role group name" name="permission_group_name" class="form-control input-xs " value="<?= ($role_group) ? $role_group['permission_group_name'] : "" ?>" >
                <span class="error bold"><?php echo form_error('permission_group_name'); ?></span>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <ul class="task-list col-md-12 px-0 margin-top">
                        <?php
                        if (isset($result) && $result) {
                            if($role_group){
                                $permissions = ($role_group) ? explode(",", $role_group['permission_fk_id']) : array();
                                // if(APP_ID > 0){
                                //     $master_permission = (isset($role_group['master_perm_ids'])) ? explode(",", $role_group['master_perm_ids']) : array();
                                //     $permissions = array_intersect($permissions, $master_permission);
                                //     foreach ($result as $key => $value) {
                                //         if (!$master && !in_array($value['id'], $master_permission))
                                //             unset($result[$key]);
                                //     }
                                // }
                                $result = array_values($result);
                            }

                            for ($i = 0; $i < count($result); $i) {
                                ?>
                                <li>
                                    <div class="panel panel-info dropdown-toggle" >
                                        <div class="panel-heading ">
                                            <h4 class="panel-title">                  
                                                <a style="cursor: pointer" class="collapsed_list" class="accordion-toggle collapsed" aria-expanded="false" data-toggle="collapse" data-target="#aaccordion1_0<?= $i; ?>">
                                                    <?= $result[$i]['permission_merge'] ?>
                                                    <span style="float: right;margin-top: 10px;   font-size: 21px;" class="caret"></span>
                                                </a>
                                                <input type="checkbox" class="group_permision_all " <?= ($permissions && in_array($result[$i]['id'], $permissions)) ? "CHECKED" : "" ?>> 
                                            </h4>
                                        </div>
                                        <div id="aaccordion1_0<?= $i; ?>" class="panel-collapse collapse <?= ($permissions && in_array($result[$i]['id'], $permissions)) ? "in" : "" ?>" aria-expanded="true" style="">
                                            <div class="panel-body tile-panel-card">
                                                <?php do { ?>
                                                    <label style="font-weight: 200 !important;" class="col-md-3">
                                                        <input type="checkbox" value="<?= $result[$i]['id'] ?>" name="user_permission_fk_id[]" <?= ($permissions && in_array($result[$i]['id'], $permissions)) ? "CHECKED" : "" ?>>
                                                        <?= $result[$i]['permission_name'] ?>
                                                    </label>
                                                    <?php
                                                    $i++;
                                                } while (isset($result[$i]) && $result[$i]['permission_merge'] == $result[$i - 1]['permission_merge']);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-info pull-right btn-sm" type="submit">Submit</button>
                </div>
        </form>
    </div>
</section>
<script src="<?= AUTH_ASSETS ?>js/jquery.validate.min.js" type="text/javascript"></script>
<script>

    var form = $("#role");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: {
            permission_group_name: {
                required: true
            }
            
        },
        messages: {
                    permission_group_name: {
                        required: "Please enter role group name"
                    }
                           
                  },

    }); 
</script>
<script>
    jQuery(document).ready(function () {
        $('.group_permision_all').click(function (event) {
            var group = $(this).parent().parent().next('div');
            if (this.checked) {
                // Iterate each checkbox
                group.find(':checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                // Iterate each checkbox
                group.find(':checkbox').each(function () {
                    this.checked = false;
                });
            }
        });
    });

    $("#add_role_form").submit(function () {
        var length = $("#add_role_form input[type='checkbox']:checked").length;
        if (length == 0) {
//            show_toast('error', "Select Atleast One Role");
            return false;
        }
    });
</script>