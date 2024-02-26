<div class="col-sm-12 edit_input_section" style="display: none">
    <section class="panel">
        <header class="panel-heading">
            Add Input
        </header>
        <div class="panel-body">
            <div class="panel-body">
                <form method="POST" action="<?= AUTH_PANEL_URL . "live_module/inputs/update_input" ?>">
                    <input name="input_id" hidden="">
                    <input name="id" hidden="">
                    <div class="form-group col-sm-6">
                        <label>Destination-A</label>
                        <div class="col-sm-12" style="padding: 0px !important">
                            <div class="col-sm-8" style="padding: 0px !important">
                                <input type="text" placeholder="Enter Input Name" name="destination_a_name" value="" class="form-control input-sm" required="">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Enter Input Name" name="destination_a_key" value="" class="form-control input-sm" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Destination-B</label>
                        <div class="col-sm-12" style="padding: 0px !important">
                            <div class="col-sm-8" style="padding: 0px !important">
                                <input type="text" placeholder="Enter Input Name" name="destination_b_name" value="" class="form-control input-sm" required="">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Enter Input Name" name="destination_b_key" value="" class="form-control input-sm" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <button class="btn btn-info" type="submit">Update</button>
                        <button class="btn btn-danger" type="button"  onclick="$('.edit_input_section').hide('slow')">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </section>
</div>
<div class="col-sm-12 add_input" style="display: none">
    <section class="panel">
        <header class="panel-heading">
            Add Input
        </header>
        <div class="panel-body">
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group col-sm-6">
                        <label>Name</label>
                        <input type="text" placeholder="Enter Input Name" name="name"  class="form-control input-sm" required="">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Security Group Id</label>
                        <input type="text" placeholder="Enter Security Group" name="security_group_id" value="<?= SECURITY_GROUP_ID ?>" class="form-control input-sm" required="">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Select Input</label>
                        <select class="form-control input-sm" name="type" required="">
                            <option value="">Select</option>
                            <option value="UDP_PUSH">UDP_PUSH</option>
                            <option value="RTP_PUSH">RTP_PUSH</option>
                            <option value="RTMP_PUSH" selected="">RTMP_PUSH</option>
                            <option value="RTMP_PULL">RTMP_PULL</option>
                            <option value="URL_PULL">URL_PULL</option>
                            <option value="MP4_FILE">MP4_FILE</option>
                            <option value="MEDIACONNECT">MEDIACONNECT</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Destination-A</label>
                        <div class="col-sm-12" style="padding: 0px !important">
                            <div class="col-sm-8" style="padding: 0px !important">
                                <input type="text" placeholder="Enter Input Name" name="destination_a_name" value="live_<?= time() ?>" class="form-control input-sm" required="">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Enter Input Name" name="destination_a_key" value="<?= rand(100, 999) ?>" class="form-control input-sm" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Destination-B</label>
                        <div class="col-sm-12" style="padding: 0px !important">
                            <div class="col-sm-8" style="padding: 0px !important">
                                <input type="text" placeholder="Enter Input Name" name="destination_b_name" value="live_<?= time() + 1 ?>" class="form-control input-sm" required="">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" placeholder="Enter Input Name" name="destination_b_key" value="<?= rand(100, 999) ?>" class="form-control input-sm" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Type Remark</label>
                        <textarea class="form-control" name="remark"  required=""></textarea>
                    </div>
                    <div class="form-group col-sm-12">
                        <button class="btn btn-info" type="submit">Submit</button>
                        <button class="btn btn-danger" type="button"  onclick="$('.add_input').hide('slow')">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </section>
</div>
<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Inputs(s) LIST
            <span class="pull-right">
                <!-- <a class='btn-sm btn btn-success' onclick="$('.add_input').show('slow')">Add</a> -->
                <a class='btn-sm btn btn-success clr_green' onclick="fetch_channel()">Fetch Channels</a>
            </span>
        </header>
        <div class="panel-body">
            <div class="card-body" style="">
                <div class="timeline-messages">
                    <?php
                    if ($inputs) {
                        foreach ($inputs as $in) {
                            ?>
                            <!-- Comment -->
                            <div class="msg-time-chat">
                                <div class="message-body msg-in">
                                    <span class="arrow"></span>
                                    <div class="text">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a class="pull-right btn-xs hide" <?= ($in['state'] == "DETACHED") ? "onclick='return confirm(\"Are You Sure Want To Delete Input?\");'" : "onclick='alert(\"Please Detach input first from channel\");return false;'" ?> href="<?= AUTH_PANEL_URL . "live_module/inputs/delete_input/" . $in['id'] . "/" . $in['input_id'] ?>"><i class="fa fa-times"></i></a>
                                                <a class="pull-right btn-xs edit_input" href="javascript:void(0)" dest_a="<?= $in['destination_a_name'] ?>" dest_b="<?= $in['destination_b_name'] ?>" key_a="<?= $in['destination_a_key'] ?>" key_b="<?= $in['destination_b_key'] ?>" input_id="<?= $in['input_id'] ?>" id="<?= $in['id'] ?>"><i class="fa fa-pencil"></i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Name</a><?= $in['name'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Security Group Id</a><?= $in['security_group_id'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Input Id</a><?= $in['input_id'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">State </a><?= $in['state'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Type </a><?= $in['type'] ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">ARN </a><?= $in['arn'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">Input URL-1 </a><?= "rtmp://" . $in['ip_a'] . ":" . $in['port_a'] . "/" . $in['destination_a_name'] . "    ---Stream Key: " . $in['destination_a_key'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">Input URL-2 </a><?= "rtmp://" . $in['ip_b'] . ":" . $in['port_b'] . "/" . $in['destination_b_name'] . "  ---Stream Key: " . $in['destination_b_key'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">Remark </a><?= $in['remark'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /comment -->
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $adminurl = AUTH_PANEL_URL; ?>
<link rel="stylesheet" type="text/css" href='<?= AUTH_ASSETS; ?>css/jquery.dataTables.css'>
<script type="text/javascript" charset="utf8" src='<?= AUTH_ASSETS; ?>js/jquery.dataTables.js'></script>
<script type="text/javascript" language="javascript" >

           jQuery(document).ready(function () {
               var table = 'all-subcategory-grid';
               var dataTable = jQuery("#" + table).DataTable({
                   "processing": true,
                   "serverSide": true,
                   "order": [[0, "desc"]],
                   "ajax": {
                       url: "mailer/ajax_get_all_template", // json datasource
                       type: "post", // method  , by default get
                       error: function () {  // error handling
                           jQuery("." + table + "-error").html("");
                           jQuery("#" + table + "_processing").css("display", "none");
                       }
                   }
               });
               jQuery("#" + table + "_filter").css("display", "none");
               $('.search-input-text').on('keyup click', function () {   // for text boxes
                   var i = $(this).attr('data-column');  // getting column index
                   var v = $(this).val();  // getting search input value
                   dataTable.columns(i).search(v).draw();
               });

           });

           $(".edit_input").click(function () {
               var section = $(".edit_input_section").show("slow");
               var selector = $(this);
               section.show();
               section.find("input[name=destination_a_name]").val(selector.attr("dest_a"));
               section.find("input[name=destination_a_key]").val(selector.attr("key_a"));
               section.find("input[name=destination_b_name]").val(selector.attr("dest_b"));
               section.find("input[name=destination_b_key]").val(selector.attr("key_b"));
               section.find("input[name=id]").val(selector.attr("id"));
               section.find("input[name=input_id]").val(selector.attr("input_id"));
           });

           function fetch_channel() {
               jQuery.ajax({
                   url: "<?=AUTH_PANEL_URL;?>/live_module/inputs/fetch_videocrypt_channels",
                   method: 'Post',
                   dataType: 'json',
                   success: function (data) {
                       if (data.type == "success") {
                           show_toast(data.type, data.title, data.message);
                       } else {
                           show_toast(data.type, data.title, data.message);
                       }
                   }
               });
           }
</script>

