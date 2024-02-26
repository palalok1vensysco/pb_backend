<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<?php 
  $movies_categories = set_value('category');
?>
<div>
   <div class="col-lg-12">
      <section class="panel">
         <header class="panel-heading bg-dark text-white">
            ADD BANNER
         </header>
         
         <div class="panel-body bg-white">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
               <div class="form-group col-md-3 " >
                    <label for="addvideo">Select Type</label>
                    <select class="form-control selectpicker" name="category" id="category" data-live-search="true" required=""> 
                        <option value="" >---select---</option>
                        <?php
                        foreach ($category as $categories) {
                            ?>
                            <option value="<?= $categories['id'] ?>" <?= (set_value('movies_categories') == $categories['id'] ? 'selected' : '') ?>><?= $categories['category_name'] ?></option>
                        <?php } ?>
                    </select> 
                </div>
               <div class="form-group col-md-3">
                  <label for="title"> Add Id</label>
                  <input type="text" class="form-control" name = "movie_id" id="movie_id" placeholder="Enter Video ID" >
                  <span class="custom-error"><?php echo form_error('movie_id'); ?></span>
               </div>
               <div class="form-group col-md-3">
                  <label for="title">Title</label>
                  <input type="text" class="form-control" name = "title" id="title" placeholder="Enter Title" value="<?= set_value('title'); ?>">
                  <span class="custom-error"><?php echo form_error('title'); ?></span>
               </div>
                <div class="form-group col-md-3">
                 <label for="published-date-user">Published Date</label>
                 <div data-date-format="yyyy-mm-dd" data-date="2013/12/1">
                   <input class="form-control" type="text" name="published_date" id="published-date-user" placeholder=" Select Published Date" value="<?= set_value('published_date'); ?>">
                 </div>
                 <span class="custom-error"><?php echo form_error('published_date'); ?></span>
               </div>
               <div class="form-group">
                  <label for="description">Description</label>
                  <div class="col-sm-12">
                            <textarea placeholder="Enter Description" class="form-control" name="description" rows="4"><?php echo set_value('description'); ?></textarea>
                        </div>
                  <span class="custom-error"><?php echo form_error('description'); ?></span>
               </div>
             <!--   <div class="form-group">
                  <label for="position">Banner Position</label>
                  <input type="text" class="form-control" name = "position" id="position" placeholder="Enter Position" value="<?= set_value('position'); ?>">
                  <span class="custom-error"><?php echo form_error('position'); ?></span>
               </div> -->
                
               <div class="form-group col-md-12 mt-3 " >
                  <label for="addimage3">Add Banner Image</label>
                  <input type="file"  name = "image" id="addimage3" accept=".jpg,.png,.jpeg">
                  <small> Image Size -: 400X240</small>
                  <span class="custom-error"><?php echo form_error('image'); ?></span>
               </div>

               <button type="submit" class="btn btn-info btn-sm">Submit</button>
            </form>
         </div>
      </section>
   </div>
   <div class="clearfix"></div>
</div>
<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

						$( function() {
					$( ".dpd1" ).datetimepicker();
					$( ".dpd2" ).datetimepicker();
					 }); // datepicker closed

           $('#published-date-user').datepicker({
              format: 'yyyy-mm-dd',
            autoclose: true

          });
               </script>
EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
