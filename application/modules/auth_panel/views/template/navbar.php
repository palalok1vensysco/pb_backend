<style>
    .crypt p {
        margin-bottom: 0px;
        margin-left: 0px;
        margin-right: 12px !important;
    }

    .group-s-r .select2-container {
        margin-top: 15px;
        width: 226px !important;
    }



    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #ff8700 !important;
        color: white;
    }



    .topbar .navbar-search {
        width: 35rem !important;
    }

    .group-s-r span.select2-selection.select2-selection--single {
        height: 35px;
        padding-top: 1px;
    }

    .select2-container--default .select2-results>.select2-results__options {
        max-height: 290px !important;
        overflow-y: auto;
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #ff8700 !important;
        color: white;
    }

    span.select2-dropdown.select2-dropdown--below {
        width: 226px !important;
        font-size: 14px !important;
    }

    .group-s-r .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 26px;
        position: absolute;
        top: 5px;
        right: 1px;
        width: 20px;
    }

    .sidebar .nav-item .nav-link span {
        font-size: 12px;
        display: inline;
    }
    .logo_box {
        text-decoration: none !important;
    }

    @media (min-width: 768px) {
        .sidebar .nav-item .nav-link span {
            font-size: 12px;
            display: inline;
        }

        .btn-success {
            color: #fff;
            background-color: #05007e;
            border-color: #05007e;
        }

        .btn-success:hover {
            color: #fff;
            background-color: #05007e;
            border-color: #05007e;
        }
    }
</style>
<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-primary topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-inline rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>


    <!-- Topbar Search -->
    <form class=" form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group group-s-r">
            <?php
            $logo = base_url('auth_assets/img/cloud_logo.png');
            ?>
            <a href="<?php echo base_url()?>admin/admin/index" class="logo_box">
                <img src="<?= $logo ?>" class="logo_width d-none d-sm-inline-block">
                <span>
                    <h6 class="mb-0 text-white">The Institute of Chartered Accountants of India</h6>
                    <p class="mb-0 text-white">(Setup by an Act of Parliament)</p>
                </span>
            </a>
            <!-- <?php
            $sess_data = $this->session->userdata(); //pre($sess_data);die;
            if ($sess_data['global_view'] == 1) {
                $passive_id = $sess_data['passive_id'];
                $this->db->order_by('username', 'ASC');
                $clients = $this->db->get_where('backend_user', ['status' => 0, 'admin_type' => 0, 'client_id' => 0]);
                // echo $this->db->last_query();
                $clients = $clients->result_array();
                // pre($clients);die;
            ?> -->
                <select class="form-control" id="select_admin" style="border-radius: 6px;margin: 5px 0px;">
                    <option <?= ($sess_data['active_backend_user_id'] == $passive_id) ? "selected" : ""; ?> value="global" style="color: #fbfbfb;background-color: #39d626;">Super Admin</option>
                    <?php
                    if ($clients) {
                        foreach ($clients as $each) {
                            // $this->db->where('client_accountid',$each['accountid']);
                            // $user_acc = $this->db->get('aws_account_details')->row_array(); 
                            // $user_acc_type = ($user_acc['own_by_client']==1) ? " (Non Package)":"(Package)";
                            // $selected = "";
                            // if ($sess_data['active_backend_user_id'] == $each['id']) {
                            //     $selected = "selected";
                            // }
                            $this->db->where('client_id', $each['id']);
                            $permission_fk_id = $this->db->get('permission_group')->row('permission_fk_id');
                            $array_data = explode(',', $permission_fk_id);
                            $user_acc_type = "";
                            $selected = "";
                            if ($sess_data['active_backend_user_id'] == $each['id']) {
                                $selected = "selected";
                            }
                    ?>
                            <option <?= $selected; ?> value="<?= $each['id'] ?>"> <?= ucfirst($each['username']) . "&nbsp;" . $user_acc_type; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            <?php } ?>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <div class="ml-auto d-inline-flex align-items-center">
        <?php
        // pre($_SESSION['active_user_data']);die;
        if (!empty($_SESSION['cloud']) || !empty($_SESSION['active_user_data']->cloud_user)) {
            if (!empty($_SESSION['active_user_data']->logo)) {
                $logo_new = $_SESSION['active_user_data']->logo;
            }
            // else{
            //     $logo = base_url('auth_assets/img/user_logo.png');          
            // }
            if (!empty($logo_new)) {
        ?>
                <div style="width: 300px;overflow: hidden;" class="text-right">
                    <img src="<?= $logo_new; ?>" alt="logo" class="img-fluid" style="max-height: 55px;">
                </div>
        <?php }
        } ?>
        <ul class="navbar-nav">
            <?php
            $user_data = $this->session->userdata('active_user_data');
            // print_r($user_data);die;
            $this->db->select("id,channel_id,channel_name,state,remark");
            $this->db->where("state", "Running");
            if ($user_data->admin_type == 0) {
                if ($this->session->userdata('client_id') == 0) {  // it is handle by main client for admin
                    $this->db->where("created_by", $user_data->id);
                } else {
                    $this->db->where("client_user_id", $user_data->id);
                }
            }
            $channel = []; //$this->db->order_by('id',"desc")
            //          ->limit(5)
            //        ->get('aws_channel')
            //      ->result_array();
            // $channel = $this->db->get("aws_channel")->result_array();
            $notification_count = (is_array($channel)) ? count($channel) : 0;
            $notification_status = "hide";
            if ($notification_count > 0) {
                $notification_status = "";
            }
            ?>
            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1 <?= $notification_status; ?>">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <!-- Counter - Alerts -->
                    <span class="badge badge-danger badge-counter"><?= $notification_count; ?></span>
                </a>
                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        Live Channel Information
                    </h6>
                    <?php
                    if ($channel) {
                        foreach ($channel as $ch) {
                    ?>
                            <div class="dropdown-item d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="icon-circle bg-warning">
                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <?php
                                    $this->db->select('start_time');
                                    $this->db->limit(1);
                                    $this->db->order_by('id', 'desc');



                                    $ch_log = $this->db->get_where("aws_channel_logs")->row_array();
                                    ?>
                                    <div class="small text-gray-500"><?= (isset($ch_log['start_time']) ? date('M d, Y', $ch_log['start_time']) : "") ?></div>
                                    <?= ucwords($ch['channel_name']) ?> channel is running.
                                    <!-- <span class="btn btn-danger btn-xs">Stop</span> -->
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                    <div class="p-2 text-center"><a class="p-2 text-center small text-gray-500" style="background: #ff9700 !important;color: #000 !important;
                        " href="<?= base_url() . 'index.php/admin/live_module/channels/' ?>">Show All Alerts</a></div>
                </div>
            </li>



            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php
                    $active_user_data = $sess_data['active_user_data'];
                    $name = $active_user_data->username;
                    $accountid = $active_user_data->accountid;
                    $dp = AUTH_ASSETS . "img/undraw_profile.svg";
                    if (!empty($active_user_data->profile_picture)) {
                        $dp = $active_user_data->profile_picture;
                    }
                    ?>
                    <div class="crypt ellipsis_txt text-gray-600 small">
                        <p style="color:white;"><?= ucwords($name); ?></p>
                        <?php
                        $company_name = ucwords($accountid);
                        if (!empty($_SESSION['cloud']) || !empty($_SESSION['active_user_data']->cloud_user)) {
                            if (!empty($_SESSION['active_user_data']->company_name)) {
                                $company_name = ucwords($_SESSION['active_user_data']->company_name);
                            }
                        }
                        ?>
                        <p style="text-align: right; margin-top: 2px!important; color:white;"><?= ucwords($company_name); ?></p>

                    </div>
                    <!-- <span class="mr-2 d-none d-lg-inline ellipsis_txt text-gray-600 small"><?= ucwords($name); ?></span> -->

                    <img class="img-profile rounded-circle" src="<?= $dp; ?>">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('admin/admin/profile'); ?>">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>




        </ul>
    </div>

</nav>
<!-- End of Topbar -->
<script>
    $(document).ready(function() {
        // Initialize select2
        if($("#select_admin").length){
            $("#select_admin").select2();
        }

    });
</script>