<style>
    .panel-heading {
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    padding: 10px;
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<div class="col-lg-6 ">
    <section class="panel">
        <header class="panel-heading">
            EDIT MENU TYPE
            <a href="<?= base_url('admin-panel/menu-item'); ?>"><button class="pull-right btn btn-info btn-xs bold">Back to menu menu list</button></a>
        </header>
        <div class="panel-body">
            <form autocomplete="off" role="form" method= "POST" action="<?= AUTH_PANEL_URL . 'menu_item/update_menu_item'; ?>" enctype="multipart/form-data">
                <input type="hidden" class="form-control" name = "id"value="<?=$menu_item['id']?>">
                 <div class=" col-md-12">
                    <div class="form-group col-md-6">
                        <label>Menu Type</label>
                        <select class="form-control selectpicker" name="menu_type_id" id="menu_type_id" data-live-search="true" required=""> 
<!--                            <option value="" >---select---</option>-->
                            <?php
                            foreach ($menu_type as $menu) {
                                ?>
                                <option value="<?= $menu['id'] ?>" <?= ($menu['id']==$menu_item['menu_type_id']) ? 'selected' : '' ?> ><?= $menu['type'] ?></option>
                            <?php } ?>
                        </select> 
                    </div>
                    <div class="form-group col-md-6">
                        <label for="menu_title">Title</label>
                        <input type="text" class="form-control" name = "menu_title" id="type" value="<?=$menu_item['menu_title']?>">
                        <span class="text-danger"><?php echo form_error('menu_title'); ?></span>
                    </div>
                </div>
                <button type="submit" class="btn btn-info btn-sm">Update</button>
                <a href="<?= base_url('admin-panel/mobile-menu') ?>">
                    <button class="btn btn-danger btn-sm" type="button" >Cancel</button>
                </a>
            </form>
        </div>
    </section>
</div>
<?php
$custum_js = <<<EOD
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script> 

EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>


