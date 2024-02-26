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
            <a href="<?= base_url('admin-panel/mobile-menu-type'); ?>"><button class="pull-right btn btn-info btn-xs bold">Back to menu type list</button></a>
        </header>
        <div class="panel-body">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                <input type="hidden" class="form-control" name = "id"value="<?= $menu_type['id'] ?>">
                <div class="form-group">
                    <label for="type">Type</label>
                    <input type="text" class="form-control" name = "type" id="type" value="<?= $menu_type['type'] ?>" placeholder="Enter Type">
                    <span class="text-danger"><?php echo form_error('type'); ?></span>
                </div>
                <button type="submit" class="btn btn-info btn-sm">Update</button>
                <a href="<?= base_url('admin-panel/mobile-menu-type') ?>">
                    <button class="btn btn-danger btn-sm" type="button" >Cancel</button>
                </a>
            </form>
        </div>
    </section>
</div>
