 <style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
</style>
<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading custom-panel-heading text-white" style="background: var(--color1)!important">
            Edit Category
            <!-- <button class="btn display_color pull-right" type="button"><a href = "javascript:history.back()">Back to previous page</a></button> -->
            <button class="btn btn-sm display_color pull-right " type="button"><a href = "javascript:history.back()" class="text-white">Back To Previous Page</a></button>
        </header>
        <div class="panel-body bg-white p-2">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                <?php if (isset($id)) { ?>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                <?php } ?>
                
                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="cat_name">Category</label>
                        <select class="form-control input-sm m-bot15 selectpicker" name = "cat_name" id="cat_name">
                            <?php 
                            if (isset($categories)) {
                                    foreach ($categories as $cate) { ?>
                                        <option value="<?php echo $cate['id']; ?>" <?php
                                        if ($cate['id'] == $id) {
                                            echo "selected";
                                        } ?>>
                                        <?php echo $cate['cat_name']; ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>       
                        </select>
                        <span class="custom-error"><?php echo form_error('cat_id'); ?></span>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="rlguru">Select Related Genres</label>
                        <select class="form-control input-sm m-bot15 selectpicker" name = "related_genres[]" id="rlguru" multiple="" data-live-search="true">
                            <?php
                            if (isset($genres)) {
                                foreach ($genres as $artists) {
                                    ?>
                                    <option value="<?php echo $artists['id']; ?>" <?php
                                    $cats = explode(',', $category['genres']);
                                    if (in_array($artists['id'], $cats)) {
                                        echo "selected";
                                    }
                                    ?>>
                                    <?php echo $artists['sub_category_name']; ?>

                                </option>
                                <?php
                            }
                        }
                        ?>       

                    </select>

                    <span class="custom-error"><?php echo form_error('related_guru'); ?></span>
                </div>
            </div><!--DIV ROW--> 
            <button type="submit" class="btn btn-sm display_color">Submit</button>
            <a href="<?= base_url('admin-panel/add-category') ?>">
                <button class="btn btn-sm display_color" type="button" >Cancel</button>
            </a>
        </form>

    </div>
</section>
</div>

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