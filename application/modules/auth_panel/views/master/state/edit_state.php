<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            Edit State
        </header>
        <div class="panel-body">
            <form role="form" method="POST" enctype="multipart/form-data">
                <?php if ($subject['id']) { ?>
                    <input type="hidden" id="id" value="<?php echo $subject['id']; ?>">
                <?php } ?>
                
            
                <div class="form-group">
                    <label for="name">Subject Name</label>
                    <input type="text" class="form-control input-sm" id="name" name="name" value="<?php echo $subject['name']; ?>" placeholder="Enter State Name">
                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                </div>
               
                <button type="submit" class="btn bold btn-sm btn-info">Submit</button>
                <a href="<?php echo AUTH_PANEL_URL . 'master/state_management'; ?>"><button type="button" class="btn btn-sm  btn-danger bold ">Cancel </button></a>
            </form>

        </div>
    </section>
</div>
<div class="clearfix"></div>