<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<?php //pre($banner); die;?>
<div>
   <div class="col-lg-12">
      <section class="panel">
         <header class="panel-heading text-white bg-dark">
            EDIT BANNER
            <a href="<?= base_url('admin-panel/banner-list'); ?>"><button class="pull-right btn btn-info btn-xs bold">Back to banner list</button></a>
         </header>
         <div class="panel-body bg-white">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
               <?php if (isset($banner['id'])) {?>
                  <input type="hidden" name="id" value="<?php echo $banner['id']; ?>">
                  <input type="hidden" name="image_url" value="<?php echo $banner['image']; ?>">
               <?php }?>
               <div class="form-group col-md-3">
                  <label for="title">Title</label>
                  <input type="text" class="form-control" name = "title" id="title" placeholder="Enter Title" value="<?php if (isset($banner['title'])) {echo $banner['title'];}?>">
                  <span class="custom-error"><?php echo form_error('title'); ?></span>
               </div>
               <div class="form-group col-md-3 " >
                    <label for="addvideo">Select Type</label>
                    <select class="form-control selectpicker" name="category" id="category" data-live-search="true" required=""> 
                        <option value="" >---select---</option>
                        <?php
                        foreach ($category as $categories) {
                            ?>
                           <!--  <option value="<?= $categories['id'] ?>" <?= (set_value('movies_categories') == $categories['id'] ? 'selected' : '') ?>><?= $categories['category_name'] ?></option> -->

                            <option value="<?= $categories['id'] ?>" <?= ($categories['id'] == $banner['type_id']) ? 'selected' : '' ?> ><?= $categories['category_name'] ?></option>
                        <?php } ?>
                    </select> 
                </div>
               <div class="form-group col-md-3">
                  <label for="title"> Add Id</label>
                  <input type="text" class="form-control" name = "movie_id" id="movie_id"  Value="<?=  $banner['redirect_id']; ?>" placeholder="Enter Video ID" >
                  <span class="custom-error"><?php echo form_error('movie_id'); ?></span>
               </div>
                <div class="form-group col-md-3">
                 <label for="published-date-user">Published Date</label>
                 
                   <input class="form-control" type="text" name="published_date" id="" placeholder=" Select Published Date" value="<?php echo $banner['published_date']; ?>">
              
                 <span class="custom-error"><?php echo form_error('published_date'); ?></span>
               </div>
                <div class="form-group">
                 <label for="published-date-user">Description
                 </label>
                 <div data-date-format="yyyy-mm-dd" data-date="2013/12/1">
                   <textarea class="form-control" type="text" name="description" id="description" placeholder=" Select Published Date" value="<?php echo $banner['description'];?>"><?php echo $banner['description']; ?></textarea> 
                 </div>
                 <span class="custom-error"><?php echo form_error('published_date'); ?></span>
               </div>
               <div>
                  <?php if (isset($banner['artist_image'])) {?>
                     <img class="img-thumbnail" src="<?php echo $banner['artist_image']; ?>" width="150px" height="180px">
                  <?php }?>
               </div>
               <div>
                  <?php if (isset($banner['image'])) {?>
                     <img class="img-thumbnail" src="<?php echo $banner['image']; ?>" width="400px" height="220px">
                  <?php }?>
               </div>
               <div class="form-group" >
                  <label for="addimage">Change Banner Image</label>
                  <input type="file"  name = "image" id="addimage" accept=".jpg,.png,.jpeg">
                  <small> Image Size -: 400X240</small>
                  <span class="custom-error"><?php echo form_error('image'); ?></span>
               </div>

               <button type="submit" class="btn btn-info btn-sm">Submit</button>
               <a href="<?=base_url('admin-panel/banner-list')?>">
                        <button class="btn btn-danger btn-sm" type="button" >Cancel</button>
                    </a>
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
