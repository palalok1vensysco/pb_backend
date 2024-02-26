<aside class="profile-nav col-lg-3">
	<section class="panel">
		<div class="user-heading round">
			<a href="#">
				<img src="<?php echo $user_data['user_image']; ?>" alt="">
			</a>
			<h1><?php echo $user_data['firstName'] . ' ' . $user_data['lastName']; ?></h1>
			<p><?php echo $user_data['email']; ?></p>
			<div class="">
				<span class="">
					<?php genrate_star_html($user_data['user_rating'], "fa-lg  "); ?>
				</span>
			</div>
		</div>
		
<?php $admin_data = $this->session->userdata('active_user_data'); ?>
		
		<ul class="nav nav-pills nav-stacked nav-collapse">
			<li ><a href="<?php echo AUTH_PANEL_URL . 'web_user/user_profile/' .$user_data['userId']; ?>"> <i class="fa fa-user"></i> Profile</a></li>
			<?php  if($admin_data->roles == "SUPER_USER" ){ ?>
			<li><a href="<?php echo AUTH_PANEL_URL . 'web_user/web_user_logger/' . $user_data['userId']; ?>" target="_blank"> <i class="fa fa-edit"></i> Edit profile</a></li>
			<?php } ?>			
			<li><a href="<?php echo AUTH_PANEL_URL . 'web_user/user_listed_cars/' .$user_data['userId'];  ?>"> <i class="fa  fa-truck"></i> Listed car(s)</a></li>
			<li><a href="<?php echo AUTH_PANEL_URL . 'web_user/user_document_data/' . $user_data['userId'];  ?>"> <i class="fa  fa-file"></i> Document info</a></li>
			<?php  if($admin_data->roles == "SUPER_USER" ){ ?>
			<li><a href="<?php echo AUTH_PANEL_URL.'web_user/user_block/'.$user_data['userId'];  ?>"> <i class="fa  fa-file"></i> User Blocking</a></li>
			<?php } ?>
			<li><a href="<?php echo AUTH_PANEL_URL.'web_user/user_bookings/'.$user_data['userId'];  ?>"> <i class="fa  fa-file"></i> User Bookings</a></li>
		</ul>
	</section>
</aside>