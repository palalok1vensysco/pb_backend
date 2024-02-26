<div class="col-lg-12 px-0">
    <section class="panel">
        <header class="panel-heading   text-white bg-dark">
            Twilio
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="<?= AUTH_PANEL_URL . "mailer/addsmstype" ?>" >
                <input type="hidden" name="meta_name" value="TWILIO_DETAIL">               
                <?php // print_r($info);?>
                <div class="form-group col-md-6">
                    <label>Select status <span style="color:#ff0000">*</span></label>
                    <select class="form-control input-xs "  name="status" required>
                        <option <?= (isset($info->aspire_detail['status']) && $info->aspire_detail['status'] == "1") ? "selected" : "" ?> value="0">Active</option>
                        <option <?= (isset($info->aspire_detail['status']) && $info->aspire_detail['status'] == "2") ? "selected" : "" ?> value="1">Inactive</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Sid <span style="color:#ff0000">*</span></label>
                    <input type="text" name="sid" class="form-control input-xs" placeholder="Enter Sid" value="<?= isset($info->twilio_key['sid'])?$info->twilio_key['sid']:"";?>" >
                </div>
                <div class="form-group col-md-4">
                    <label>Token <span style="color:#ff0000">*</span></label>
                    <input type="text" name="token" class="form-control input-xs" placeholder="Enter token KEY Key Here" value="<?= isset($info->twilio_key['token'])?$info->twilio_key['token']:"";?>">
                </div>


                
                <div class="form-group col-md-4">
                    <label>Client <span style="color:#ff0000">*</span></label> 
                    <input class="form-control input-xs editor " id="database" name="client" value="<?= isset($info->twilio_key['client']) ? $info->twilio_key['client'] : ""; ?>" >

                    <span class="error bold"></span>
                </div>
                
                  <div class="form-group col-md-4">
                    <label>From <span style="color:#ff0000">*</span></label> 
                    <input class="form-control input-xs editor " id="database" name="from" value="<?= isset($info->twilio_key['from']) ? $info->twilio_key['from'] : ""; ?>" >

                    <span class="error bold"></span>
                </div>
             
                <div class="form-group col-md-12">
                    <button class="btn btn-success  clr_green" type="submit">save</button>
                    <button class="btn btn-warning  clr_green" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
             </div>
    </section>

             <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Aspire Massage
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data"  id="rozar" action="<?= AUTH_PANEL_URL . "mailer/addsmstype" ?>" >
                <input type="hidden" name="meta_name"  value="ASPIRE_DETAIL">
                <div class="form-group col-md-6">
                    <label>Select status <span style="color:#ff0000">*</span></label>
                    <select class="form-control input-xs "  name="status" required>
                        <option <?= (isset($info->aspire_detail['status']) && $info->aspire_detail['status'] == "1") ? "selected" : "" ?> value="0">Active</option>
                        <option <?= (isset($info->aspire_detail['status']) && $info->aspire_detail['status'] == "2") ? "selected" : "" ?> value="1">Inactive</option>
                    </select>
                </div>
               
                <div class="form-group col-md-6">
                    <label>Dlt <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="dlt" value="<?= isset($info->aspire_detail['dlt']) ? $info->aspire_detail['dlt'] : ""; ?>" required>
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-6">
                    <label>Key <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="key" value="<?= isset($info->aspire_detail['key']) ? $info->aspire_detail['key'] : ""; ?>" required>
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-6">
                    <label>Sender <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="sender" value="<?= isset($info->aspire_detail['sender']) ? $info->aspire_detail['sender'] : ""; ?>" required>
                    <span class="error bold"></span>
                </div>
               
                <div class="form-group col-md-12">
                    <button class="btn btn-success  clr_green" type="submit">save</button>
                    <button class="btn btn-warning  clr_green" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
        </div>
    </section>

    <section class="panel">
        <header class="panel-heading bg-dark text-white">
          Msg91
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" id="payu" action="<?= AUTH_PANEL_URL . "mailer/addsmstype" ?>" >
                <input type="hidden" name="meta_name"  value="MSG91_DETAIL">
                <div class="form-group col-md-4">
                    <label>Select status  <span style="color:#ff0000">*</span></label>
                    <select class="form-control input-xs "  name="status" required>
                        <option <?= (isset($info->msg91_detail['status']) && $info->msg91_detail['status'] == "1") ? "selected" : "" ?> value="0">Active</option>
                        <option <?= (isset($info->msg91_detail['status']) && $info->msg91_detail['status'] == "2") ? "selected" : "" ?> value="1">Inactive</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Flow id  <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="flow_id" value="<?= isset($info->msg91_detail['flow_id']) ? $info->msg91_detail['flow_id'] : ""; ?>" required>
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Sender  <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="sender" value="<?= isset($info->msg91_detail['sender']) ? $info->msg91_detail['sender'] : ""; ?>" required>
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success  clr_green" type="submit">save</button>
                    <button class="btn btn-warning  clr_green" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
        </div>
    </section>

       
</div>
<script type="text/javascript" src="<?=AUTH_ASSETS ?>assets/ckeditor/ckeditor.js"></script>     
<script>
    CKEDITOR.replace('template_html');
</script>
