<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php if(isset($pageTitle)){ echo $pageTitle; } else { "API Doc | DAPS";} ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">	
            <!-- Logo -->
				<a href="<?php echo base_url(); ?>index.php/admin_panel/Admin/dashboard" class="logo">
					<!-- mini logo for sidebar mini 50x50 pixels -->

					<!-- logo for regular state and mobile devices -->
					<span class="logo-lg"><b style="font-size:100%;"><?=CONFIG_PROJECT_FULL_NAME?></b></span>
				</a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>  
                <div class="navbar-custom-menu">
                    <!-- Control Sidebar Toggle Button -->
                    <ul class="nav navbar-nav">
                        <!-- Notifications: style can be found in dropdown.less -->

                        <?php /* ?>
                          <li class="dropdown notifications-menu">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                          <i class="fa fa-bell-o"></i>
                          <span class="label label-warning">10</span>
                          </a>
                          <ul class="dropdown-menu">
                          <li class="header">You have 10 notifications</li>
                          <li>
                          <!-- inner menu: contains the actual data -->
                          <ul class="menu">
                          <li>
                          <a href="#">
                          <i class="fa fa-users text-aqua"></i> 5 new members joined today
                          </a>
                          </li>
                          <li>
                          <a href="#">
                          <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the page and may cause design problems
                          </a>
                          </li>
                          <li>
                          <a href="#">
                          <i class="fa fa-users text-red"></i> 5 new members joined
                          </a>
                          </li>

                          <li>
                          <a href="#">
                          <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                          </a>
                          </li>
                          <li>
                          <a href="#">
                          <i class="fa fa-user text-red"></i> You changed your username
                          </a>
                          </li>
                          </ul>
                          </li>
                          <li class="footer"><a href="#">View all</a></li>
                          </ul>
                          </li>
                          <?php */ ?>
                        <!-- User Account: style can be found in dropdown.less -->
                        <!--        <li class="dropdown user user-menu">
                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                     <span class="hidden-xs">Admin - FoodCourt</span>
                                  </a>
                                  <ul class="dropdown-menu">
                                     User image 
                                    <li class="user-header">
                                      <p>
                                        Admin - FoodCourt
                                        <small>Member since Dec. 2016</small>
                                      </p>
                                    </li>           
                                     Menu Footer
                                    <li class="user-footer">
                                      <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                      </div>
                                      <div class="pull-right">
                                        <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                      </div>
                                    </li>
                                  </ul>
                                </li>-->


                        <li>
                            <a href="javascript:void(0)" > 
                                <?php
                                /*
                                if ($this->session->userdata('user')) {
                                    //pre($this->session->userdata('user'));
                                    $user_type = $this->session->userdata('user')['user_type'];
                                    if ($user_type == 1) {
                                        $userType = "SuperAdmin";
                                    } else if ($user_type == 2) {
                                        $userType = $this->session->userdata('user')['name'];
                                    } else {
                                        redirect('admin_panel/Login');
                                        $userType = "User";
                                    }
                                    echo $userType;
                                }
                                 
                                 */
                                ?> 
                            </a>          
                        </li> 
<!--                        <li class="dropdown user user-menu">
                            <a href="<?php //echo base_url(""); ?>index.php/admin_panel/Admin/logout" >
                                <span class="hidden-xs">Logout</span>
                            </a>          
                        </li> -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
