<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
</style>
<div>
    <div class="col-lg-6">
        <section class="panel">
            <header class="panel-heading custom-panel-heading">
                EDIT CATEGORY
                <a href="<?= base_url('admin-panel/video-category-list'); ?>"><button class="pull-right btn btn-info btn-xs bold">Back to category list</button></a>
            </header>
            <div class="panel-body custom-panel-body">
                <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                    <?php if (isset($category['id'])) { ?>
                        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                    <?php } ?>
                    <div class="form-group">
                        <label for="category_name">Category</label>
                        <input type="text" class="form-control" name = "category_name" id="category_name" placeholder="Enter Category" value="<?php if (isset($category['category_name'])) {
                        echo $category['category_name'];
                    } ?>">
                        <span class="custom-error"><?php echo form_error('category_name'); ?></span>
                    </div>
                    <button type="submit" class="btn btn-info btn-sm">Submit</button>
                    <a href="<?= base_url('admin-panel/video-category-list') ?>">
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
               </script>
EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>

