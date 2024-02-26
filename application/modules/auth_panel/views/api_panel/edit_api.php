<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<div class="col-lg-12 add_file_element">
    <section class="panel">
        <header class="panel-heading text-white bg-dark">
            EDIT API
        </header>
        <div class="panel-body">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?=$api['id']; ?>">
                <div class="row">
                    
                    <div class="form-group col-lg-4">
                        <label for="api_name">API Name</label>
                        <input type="text" class="form-control" name = "api_name" id="api_name" placeholder="Enter Api Name" value="<?=$api['api_name']; ?>">
                        <span class="text-danger"><?php echo form_error('api_name'); ?></span>
                    </div>
                </div>
                <div class="row" style="margin-left: 0.5%;">
                    <button type="submit" class="btn btn-info btn-sm">Submit</button>
                    <a href="<?=base_url('admin-panel/add-api')?>">
                        <button class="btn btn-danger btn-sm" type="button" >Cancel</button>
                    </a>
                </div>
            </form>
        </div>
    </section>
</div>

<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>


