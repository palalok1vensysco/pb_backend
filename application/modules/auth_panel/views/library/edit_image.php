<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<div class="col-lg-6">
	  <section class="panel">
		  <header class="panel-heading">
			  EDIT IMAGE
		  </header>
		  <div class="panel-body">
			  <form role="form" method="post" enctype="multipart/form-data">
				 <input type="hidden"  name = "id" id="id" value="<?php echo $video_detail['id']; ?>" class="form-control input-sm">
				  
		        <div class="form-group">
					  <label for="exampleInputFile">Upload Image</label>
					  <input type="file" accept="image/*" name = "image_file" id="exampleInputFile">
					  <span class="error bold"><?php echo form_error('image_file');?></span>
				  </div> 
				
			  <button class="btn btn-info"  type="submit" >Update</button>
			  </form>

		  </div>
	  </section>
  </div>
