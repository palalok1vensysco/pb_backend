<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<div>
   <div class="col-lg-6">
      <section class="panel">
         <header class="panel-heading">
           ADD VIDEO
         </header>
         <div class="panel-body">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
               <div class="form-group" >
                  <label for="addimage3">Add Home video</label>
                  <input type="file"  name = "video" id="video">
                  
                  <span class="text-danger"><?php echo form_error('video');?></span>
               </div>
              
               <button type="submit" class="btn btn-info btn-sm">Submit</button>
            </form>
         </div>
      </section>
   </div>
   <div class="clearfix"></div>
</div>