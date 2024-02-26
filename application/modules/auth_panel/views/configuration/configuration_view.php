<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }
</style>
<?php
$this->db->where('id', 1);
$info = $this->db->get('configuration')->row();
$this->db->where('id', 1);
$android_social_login = $this->db->get('social_login')->row();
$this->db->where('id', 2);
$ios_social_login = $this->db->get('social_login')->row();
?>
<div class="col-lg-6">
    <section class="panel">
        <header class="panel-heading custom-panel-heading">
            SOCIAL LOGIN
        </header>
        <div class="panel-body custom-panel-body">
            <form method="POST" action="<?php echo BASE_URL('admin-panel/social-login'); ?>" role="form">
                <input type="hidden" name="id" value="<?= $ios_social_login->id?>">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">IOS</legend>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label><i class="fa fa-apple fa-spin"></i> Apple</label>
                            <select class="form-control input-sm" name="is_apple">
                                <option value="1" <?= $ios_social_login->is_apple == 1 ? "selected" : ""; ?>>Enabled</option>
                                <option value="2" <?= $ios_social_login->is_apple == 2 ? "selected" : ""; ?>>Disabled</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label><i class="fa fa-google-plus fa-spin"></i> Google</label>
                            <select class="form-control input-sm" name="is_google">
                                <option value="1" <?= $ios_social_login->is_google == 1 ? "selected" : ""; ?>>Enabled</option>
                                <option value="2" <?= $ios_social_login->is_google == 2 ? "selected" : ""; ?>>Disabled</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label><i class="fa fa-facebook fa-spin"></i> Facebook</label>
                            <select class="form-control input-sm" name="is_facebook">
                                <option value="1" <?= $ios_social_login->is_facebook == 1 ? "selected" : ""; ?>>Enabled</option>
                                <option value="2" <?= $ios_social_login->is_facebook == 2 ? "selected" : ""; ?>>Disabled</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
                <button class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to change?')" type="submit">save</button>
            </form>
            <form method="POST" action="<?php echo BASE_URL('admin-panel/social-login'); ?>" role="form">
                <input type="hidden" name="id" value="<?= $android_social_login->id?>">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Android</legend>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label><i class="fa fa-google-plus fa-spin"></i> Google</label>
                            <select class="form-control input-sm" name="is_google">
                                <option value="1" <?= $android_social_login->is_google == 1 ? "selected" : ""; ?>>Enabled</option>
                                <option value="2" <?= $android_social_login->is_google == 2 ? "selected" : ""; ?>>Disabled</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label><i class="fa fa-facebook fa-spin"></i> Facebook</label>
                            <select class="form-control input-sm" name="is_facebook">
                                <option value="1" <?= $android_social_login->is_facebook == 1 ? "selected" : ""; ?>>Enabled</option>
                                <option value="2" <?= $android_social_login->is_facebook == 2 ? "selected" : ""; ?>>Disabled</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
                <button class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to change?')" type="submit">save</button>
            </form>
        </div>
    </section>
</div>


<div class="col-lg-6">
    <section class="panel">
        <header class="panel-heading custom-panel-heading">
            DEVICE LIMIT
        </header>
        <div class="panel-body custom-panel-body">
            <form method="POST" action="" role="form">
                <div class="form-group col-md-12">
                    <label><i class="fa fa-mobile fa-spin"></i> Device Limit</label>
                    <input required="" type="text" value="<?= $info->device_limit; ?>" name="device_limit" placeholder="Device Limit" class="form-control input-sm">
                </div>
                <button class="btn btn-success btn-sm" type="submit">save</button>
            </form>
        </div>
    </section>
</div>