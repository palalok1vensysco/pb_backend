<div class="col-lg-12 px-0">
    <section class="panel">
        <header class="panel-heading">
            Edit Email Template 
        </header>
        <div class="panel-body">
            <form role="form" method="post" action="<?php echo AUTH_PANEL_URL . 'mailer/update_edited_template'; ?>">
                <div class="form-group">
                    <label for="exampleInputPassword1">Template Name</label>
                    <input type="text" value="<?php echo $template['template_name']; ?>" placeholder="Enter template name" id="template_name"  name="template_name" class="form-control" readonly="readonly">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Template</label>
                    <textarea name="template_html" class="form-control">
                        <?php echo $template['template_html']; ?>
                    </textarea>
                </div>
                <input type="hidden" name="id" value="<?php echo $template['id']; ?>">
                <button class="btn btn-info  clr_green" type="submit">Submit</button>
            </form>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?=AUTH_ASSETS ?>assets/ckeditor/ckeditor.js"></script>     
<script>
    CKEDITOR.replace('template_html');
</script>
