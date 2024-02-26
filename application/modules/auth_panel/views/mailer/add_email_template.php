<div class="col-lg-12 px-0">
    <section class="panel">
        <header class="panel-heading   text-white bg-dark">
            Add EMAIL Template
        </header>
        <div class="panel-body">
            <form method="POST" action="<?php echo AUTH_PANEL_URL . 'mailer/add_email_template' ?>" role="form">
                <div class="form-group ">
                    <label >Template Name</label>
                    <input type="text" placeholder="Enter template name" name = "template_name"  class="form-control input-xs" required><span class="error bold"><?php echo form_error('template_name'); ?></span>

                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Type message</label>
                    <textarea class="form-control input-xs" name="template_html"> </textarea>
                </div>
                <button class="btn btn-info btn-xs clr_green" type="submit">Submit</button>
                <button class="btn btn-warning btn-xs clr_green" type="reset" onclick="history.back()">Cancel</button>
            </form>

        </div>
    </section>
</div>
<script type="text/javascript" src="<?=AUTH_ASSETS ?>assets/ckeditor/ckeditor.js"></script>     
<script>
    CKEDITOR.replace('template_html');
</script>
