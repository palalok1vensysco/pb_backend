
<div class="col-md-9 no-padding">
<div id="tabContent1" class="col-lg-12 tabu">
      <section class="panel">
          <header class="panel-heading">
              Edit Instructor Review
          </header>
          <div class="panel-body">
              <form role="form" method="post" action="<?php echo AUTH_PANEL_URL.'web_user/edit_instructor_rating/'.$instructor_rating_detail['id']; ?>"  >
				  <input type="hidden"  name = "instructor_id" id="instructor_id" value="<?php echo $instructor_rating_detail['instructor_id']; ?>" class="form-control input-sm">
				  <input type="hidden"  name = "id" id="id" value="<?php echo $instructor_rating_detail['id']; ?>" class="form-control input-sm">
				 <div class="form-group col-md-6">
					  <label >User name</label>
					  <input type="text" readonly placeholder="Enter user name" name = "name" id="name" value="<?php echo $instructor_rating_detail['name']; ?>" class="form-control input-sm">
					   
				  </div>
				   <div class="form-group col-md-6 ">
					  <label >Rating</label>
					  <input type="text" placeholder="Add rating" name = "rating" id="rating" value="<?php echo $instructor_rating_detail['rating']; ?>" class="form-control input-sm">
					   <span class="error bold"><?php echo form_error('rating');?></span>
				  </div>
				   <div class="form-group col-md-6 ">
					  <label>Review</label>
					  <input type="text" placeholder="Review" name = "text" id="text" value="<?php echo $instructor_rating_detail['text']; ?>" class="form-control input-sm">
					   <span class="error bold"><?php echo form_error('text');?></span>
				  </div>
				  <div class="form-group col-md-6 ">
					  <label>Created at</label>
					  <input type="text" readonly placeholder="Creation time" name = "creation_time" id="creation_time" value="<?php echo $instructor_rating_detail['creation_time']; ?>" class="form-control input-sm">					   
				  </div>
				  
          <div class="form-group col-md-12">    
				    <input class="btn btn-info btn-sm" name="update_instructor_review" value="Update"  type="submit" >
          </div>
			  </form>

          </div>
      </section>
  </div>
	</div>