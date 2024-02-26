<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
</style>
<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading custom-panel-heading text-white d-flex justify-content-between align-items-center" style="background: var(--color1)!important">
            <span>Edit Category </span>
            <button class="btn-xs btn pull-right display_color dropdown_ttgl text-white" type="button"><a href = "javascript:history.back()" class="text-white">Back To Previous Page</a></button>
        </header>
        <div class="panel-body bg-white p-2">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                
                <div class="form-group col-md-6">
                    <label for="title">Category<span style="color:#ff0000">*</span></label>
                   
                     <input type="text" class="form-control input-sm m-bot15 category_type" value="<?php if(isset($category)){ echo $category['title'];}?>" name="title"  maxlength='100'  oninput="checkpricee(this)" required>
                    <span class="custom-error"><?php echo form_error('title'); ?></span>
                </div>
               
                 <div class="form-group col-md-6">
                    <label for="status">Status<span style="color:#ff0000">*</span></label>
                    <select class="form-control input-sm m-bot15 status" id="status" name="status" >
                    <option value="">Select Status</option>
                    <option <?php if(isset($category) && $category['status'] == 0){ echo "selected";}?> value="0">Enable</option>
                      <option <?php if(isset($category) && $category['status'] == 1){ echo "selected";}?> value="1">Disable</option>
                      
                    </select>
                    <span class="custom-error"><?php echo form_error('status'); ?></span>
                </div>
                
            
             <div class="col-md-12">
              <div class="formSubmitBtn">
                <button type="submit" class="btn btn-sm display_color text-white f-600">Update</button>
                <button class="btn btn-sm display_color text-white f-600" type="button" onclick="window.location.href='<?php echo site_url('auth_panel/category/category/add_category');?>'">Cancel</button>
              </div>
                

             </div>
        </form>

    </div>
</section>
</div>
<script>
    function checkpricee(input) {
    if (input.value == 0) {
      input.setCustomValidity('The  category value must not be zero.');
    } else {
      // input is fine -- reset the error message
      input.setCustomValidity('');
    }
  }
  </script>

<?php
$adminurl = AUTH_PANEL_URL;

$custum_js = <<<EOD
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>



EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>