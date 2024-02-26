<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
</style>
<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading custom-panel-heading text-white" style="background: var(--color1)!important">
            Edit Page
            <!-- <button class="btn display_color pull-right" type="button"><a href = "javascript:history.back()">Back to previous page</a></button> -->
            <button class=" btn-xs btn pull-right display_color f-600" type="button"><a href = "javascript:history.back()" class="text-white">Back To Previous Page</a></button>
        </header>

        
        <div class="panel-body bg-white p-2">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data" id="page_data">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="title">Page Name<span style="color:#ff0000">*</span></label>
                        <input type="text" name="title"   id = 'cate' class="form-control input-sm m-bot15" maxlength='100' oninput="checkpricee(this)" placeholder="Enter Page Name" value="<?=$page_detail['title'];?>">
                        <span class="custom-error"><?php echo form_error('title'); ?></span>
                    </div>                 
                    <div class="form-group col-md-6">
                        <label for="cat_name">Status<span style="color:#ff0000">*</span></label>
                        <select class="form-control input-sm m-bot15 status" name="status" >
                            <option value="">Select Status</option>
                            <option <?php if($page_detail['status'] == 0) echo 'selected'; ?> value="0">Enable</option>
                            <option <?php if($page_detail['status'] == 1) echo 'selected'; ?> value="1">Disable</option>                        
                        </select>
                        <span class="custom-error"><?php echo form_error('status'); ?></span>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="cat_name">Description<span style="color:#ff0000">*</span></label>
                        <textarea rows="10" cols="50" class="form-control input-xs editor " name="description" ><?= $page_detail['description'];?></textarea>
                        <span class="custom-error"><?php echo form_error('description'); ?></span>
                    </div>
                </div>
                <button type="submit" class="btn btn-sm display_color text-white f-600">Submit</button>
                <button class="btn  btn-sm display_color text-white f-600" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>
            </form>
        </div>
</section>
</div>

<script src="<?= AUTH_ASSETS . "assets/ckeditor/ckeditor.js" ?>"></script>
<script src="<?= AUTH_ASSETS ?>js/jquery.validate.min.js" type="text/javascript"></script>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description');
    $("#page_data").submit( function(e) {
        var messageLength = CKEDITOR.instances['terms'].getData().replace(/<[^>]*>/gi, '').length;
        if( !messageLength ) {
            // alert( 'Please Enter  Ceo Message' );
            show_toast('error', 'Description  are required !!', 'Please Add  Description');
            e.preventDefault();
        }
    });
    $(function () {
        CKEDITOR.replace("description");       
    });
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