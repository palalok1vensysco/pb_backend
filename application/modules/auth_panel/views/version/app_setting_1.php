<style type="text/css">
	.sbu_btn1 {
	display: flex;
	margin-top: 20px;
	column-gap: 1px;
	justify-content: end;
	padding-right: 40px;
}
</style>

<div class="col-lg-12 px-0" id="add_bottom_bar" style="display:none">
	<section class="panel">
		<header class="panel-heading">
			App Setting
		</header>

		<div class="panel-body" >
			   <div class="row">
				   <div class="col-lg-12 ">
					  <section class="card">
						  <header class="card-header">
							  Bottom Bar
						  </header>
						  <div class="card-body">
							<form method="POST" action="<?= AUTH_PANEL_URL . 'version_control/version/bottom_bar' ?>" id="candidateForm" name="appform" enctype="multipart/form-data"  autocomplete="off">
								<input type="hidden" name="menu_side" value="0">
								<div class="form-group col-md-4">
					                <label for="name">Menu Title</label>
					                <input type="text" class="form-control input-xs" id="title" name="title" placeholder="Enter Menu Title" required="">
					            </div>
					            <div class="form-group col-md-4">  
					                <label for="name">&nbsp; &nbsp; Icon </label>    
					                <input type="file" accept="image/*" class="form-control input-xs" name="icon" id="exampleInputFile" style="padding: 3px 10px;">
					            </div>
					            <div class="form-group col-md-4">
					                <label for="name">Type</label>
					                <select class="form-control input-xs" name="type" required>
					                	<option value="">Select Option</option>
					                	<option value="1">Home</option>
					                	<option value="2">Course (Single)</option>
					                	<option value="3">My Library</option>
					                	<option value="4">Courses (All)</option>
					                	<option value="5">Notification</option>
					                	<option value="6">Downloads</option>
					                	<option value="7">Create test</option>
					                	<option value="8">Live test</option>
					                	<option value="9">Live classes</option>
					                	<option value="10">Feeds</option>
					                	<option value="11">Profile</option>
					                	<option value="12">Contact us</option>
					                	<option value="13">Course type master</option>
					                	<option value="14">Book Store</option>
					                	<option value="15">Settings</option>
					                	<option value="16">Doubt Section</option>
					                	<option value="17">Current Affairs</option>
					                	<option value="18">Faq</option>
					                </select>


					            </div>
					            <div class="form-group col-md-4">
					                <label for="name">Parameter value</label>
					                <input type="text" class="form-control input-xs" value="0" id="parameter" name="parameter" placeholder="Enter value">
					            </div>
					          
					            <div class="row sbu_btn1">
									<button type="submit" class="btn btn-xs btn-primary">Submit</button>     
								</div>

							</form>
						</div>
					</section>
			   </div>
			</div>
		</div>
	</section>
</div>		

		<div class="col-sm-12  px-0">
    <section class="panel">
        <header class="panel-heading ban-head-new">
            Bottom Bar(s) LIST
            <div class="tools-right-1">
            <button class="btn btn-xs btn-success pull-right" onclick="$('#add_bottom_bar').toggle();">
                <i class="fa fa-plus"></i>&nbsp;Add Menu
            </button>
            </div>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Icon</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="row_position">
                    	<?php  $i=0;
                    	foreach ($bottom_bar as $bm) {?>
                        <tr id="<?php echo $bm['id'] ?>"><td><?php echo ++$i;?></th>
                            <td><?php echo $bm['title'];?></td>
                            <td><img src="<?php echo $bm['icon'];?>" width="30px" height="20px"></td>
                            <td><?php echo $bm['type'];?></td>
                            <td><?php echo $bm['param_value'];?></td>
                            <td><a class="btn-xs bold btn-warning" href="<?php echo AUTH_PANEL_URL . 'version_control/version/delete_version_review?id='. $bm['id'] ?>"><i class="fa fa-trash"></i></a> 
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

		<div class="panel-body">
			   <div class="row">
				  <div class="col-lg-4 ">
					  <section class="card">
						  <header class="card-header">
							  Left Menu
						  </header>
						  <div class="card-body">
							<form method="POST" action="<?= AUTH_PANEL_URL . 'version_control/version/app_configuration' ?>" id="candidateForm" name="appform" enctype="multipart/form-data"  autocomplete="off">
								<input type="hidden" name="menu_type" value="left">
										<?php     
										  $left = json_decode($result['left_menu'],true); 
												foreach($left_array as $lefth){
										 ?>
											<li class="dd-item">
												<input type="checkbox" name="<?php echo $lefth;?>" value="1"  <?php if(isset($left[$lefth])&& $left[$lefth]=="1"){echo "checked";}?>> <?php echo $lefth;?>
											</li>
										<?php } ?>

			  <div class="row sbu_btn1">
				<button type="submit" class="btn btn-xs btn-primary">Submit</button>     
			</div>
										</form>                             
						  </div>
					  </section>
				  </div>
				  <div class="col-lg-4 ">
					  <section class="card">
						  <header class="card-header">Bottom Menu</header>
						  <div class="card-body">
							
							<form method="POST" action="<?= AUTH_PANEL_URL . 'version_control/version/app_configuration' ?>" id="candidateForm" name="appform" enctype="multipart/form-data"  autocomplete="off">
								<input type="hidden" name="menu_type" value="bottom">
								<?php 
				 
								$bottom_menu = json_decode($result['bottom'],true); 
								  foreach($bottom_array as $bottom_arrayh){?>
											<li class="dd-item">
												<input type="checkbox" name="<?php echo $bottom_arrayh;?>" value="1" <?php if(isset($bottom_menu[$bottom_arrayh])&& $bottom_menu[$bottom_arrayh]=="1"){echo "checked";}?>> <?php echo $bottom_arrayh;?>
											</li>
										<?php } ?>

			  <div class="row sbu_btn1">
				<button type="submit" class="btn btn-xs btn-primary">Submit</button>         
			</div>
										</form>
							 
						  </div>
					  </section>
				  </div>
					 
					  <div class="col-lg-4 ">
					  <section class="card">
						  <header class="card-header">Configuration Menu</header>
						  <div class="card-body">
							
							<form method="POST" action="<?= AUTH_PANEL_URL . 'version_control/version/app_configuration' ?>" id="candidateForm" name="appform" enctype="multipart/form-data"  autocomplete="off">
								<input type="hidden" name="menu_type" value="extra">
								<?php 
				 
								$extra_menu = json_decode($result['extra_json'],true); 
								 // print_r($extra_array);die;
								foreach ($extra_array as $key => $value) {?>

											<li class="dd-item">
												<input type="<?php echo $value?>" name="<?php echo $key;?>" value="<?php echo ($value=='checkbox'?"1":$extra_menu[$key]??0);?>" <?php if(isset($extra_menu[$key])&& $extra_menu[$key]=="1"){echo "checked";}?> <?php if($value=='text'){echo "placeholder='".$key."'";}?>> <?php echo ($value=='checkbox'?$key:"");?>
											</li>
										<?php } ?>

			  <div class="row sbu_btn1">
				<button type="submit" class="btn btn-xs btn-primary">Submit</button>         
			</div>
										</form>
							 
						  </div>
					  </section>
				  </div>
			  </div> 
		</div>
	</section>

   
</div>

