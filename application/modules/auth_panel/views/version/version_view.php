<style>
    .btn-success{
        background-color: #ff9700;
        border-color: #ff9700;
        color: #FFFFFF;
    } 
    .deviceCenter{
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>


<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>aws/aws-sdk-2.1.12.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>aws/aws-init.js"></script>
<?php
if ($this->session->userdata('active_user_data')) {
    ?>
    <div class="col-lg-12 px-0">
        <section class="panel">
            <header class="panel-heading bg-dark text-white">
                <div class="deviceCenter">
                    <p class="m-0">Device Latest Version</p>
                    <div class="tools-right-1">
                        <button class="btn-xs btn pull-right display_color dropdown_ttgl text-white" onclick="$('#upload_release').show('slow');">+Add Release</button>
                    </div>
                </div>
            </header>
            <div class="panel-body">
                <div class="adv-table ">
                    <div class="alert hide"></div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"  >
                        <table class="table display table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Device Type</th>
                                    <th>Version</th>
                                    <th>Min Version</th>
                                    <th>Force Update</th>
                                    <th>Free Version</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $device_type = array("1" => "Android", "2" => "iOS", "3" => "Website", "4" => "Android TV");
                                foreach ($versions as $v) {
                                                                      
                                    ?>
                                    <tr>
                                        <td><?= $device_type[$v->platform]; ?></td>
                                        
                                        <td><input type="text" name="version_<?= $v->id; ?>" class="form-control input-xs" value="<?= $v->version; ?>"></td>
                                        <td><input type="text" name="min_version_<?= $v->id; ?>" class="form-control input-xs" value="<?= $v->min_version; ?>"></td>
                                     
                                        <td>
                                            <select name="force_update_<?= $v->id; ?>" class="form-control input-xs" >
                                                <option value="0" <?= ($v->force_update == 0) ? "selected" : "" ?> >NO</option>
                                                <option value="1" <?= ($v->force_update == 1) ? "selected" : "" ?> >YES</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="free_v_<?= $v->id; ?>" class="form-control input-xs" value="<?= $v->free_v; ?>"></td>
                                        <td>
                                            <button type="button" onClick="update_version(<?= $v->id; ?>);" class="btn btn-sm display_color text-white f-600">Update</button>
                                            <button type="button" onClick="window.location.reload();" class="btn btn-sm display_color text-white f-600">Cancel</button>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>        
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php } ?>
<div class="col-lg-12 px-0">
    <section class="panel" id="upload_release" style="display:none;">
        <header class="panel-heading ">
            <div class="deviceCenter">
                <p class="m-0">Add Release</p>
            </div>
        </header>
        <div class="panel-body">
            <form method="post" id="version_form" enctype="multipart/form-data">
                <div class="form-group col-md-6">
                    <label>Platform</label>
                    <select name="device_type" class="form-control input-xs">
                        <option value="1">Android</option>
                        <option value="2">iOS</option>
                        <option value="3">Website</option>
                        <option value="4">Android TV</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleInputFile">Version</label>
                    <input type="text" name = "version" class="form-control input-xs" autocomplete="off" id="version" required="" placeholder="Enter Version  Code">
                    <span class="error bold"><?php echo form_error('version'); ?></span>
                </div>
                <div class="clearfix"></div>
                <button class="btn btn-sm display_color text-white f-600" type="submit">Submit</button>
                <button class="btn btn-sm display_color text-white f-600" onclick="$('#upload_release').hide('slow');" type="reset">Cancel</button>
            </form>
        </div>
    </section>
    <section class="panel">
        <header class="panel-heading bg-dark text-white ban-head-new">
            <div class="deviceCenter">
                <p class="m-0">Release Management</p>
                <div class="tools-right-1">
                    <button class="btn-xs btn pull-right display_color dropdown_ttgl text-white" onclick="$('#upload_release').show('slow');">+Add Release</button>
                </div>
            </div>
        </header>
        <div class="panel-body">
            <div class="adv-table ">
                <div class="alert hide"></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"  >
                    <table class="table display table-bordered table-striped" id="all-user-grid">
                        <thead>
                            <tr>
                                <th>Device Type</th>
                                <!-- <th>URL</th> -->
                                <th>Version</th>
                                <th>Note</th>
                                <th>Created By</th>
                                <th>Status</th>
                                <th>Created On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <th>
                                    <select data-column="0" class="search-input-select form-control input-xs">
                                        <option value="">All</option>
                                        <option value="1">Android</option>
                                        <option value="2">iOS</option>
                                        <option value="3">Website</option>
                                        <option value="4">Android TV</option>
                                    </select>    
                                </th>
                                <!-- <th></th> -->
                                <th><input type="text" class="form-control input-xs search-input-text" data-column="2"></th>
                                <th></th>
                                <th><input type="text" class="form-control input-xs search-input-text" data-column="4"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js" defer></script>
<script type="text/javascript">

                jQuery(document).ready(function () {
                    $("#64x").hide();
                    $("select[name=device_type]").change(function () {
                        var deviceType = $(this).val();
                        if (deviceType == "3") {
                            $("#64x").show("slow");
                            $("#86x_section").removeClass("hide").show("slow");
                        } else {
                            $("#64x").hide("slow");
                            $("#86x_section").hide("slow");
                        }
                    });
                    //Data table load
                    var table = 'all-user-grid';
                    var dataTable = jQuery("#" + table).DataTable({
                        "processing": true,
                        "pageLength": 15,
                        "lengthMenu": [[15, 25, 50], [15, 25, 50]],
                        "serverSide": true,
                        "order":[[0,"desc"]],
                        "aoColumnDefs": [
                            // {"bSortable": false, "aTargets": [0,1,3,5,7]},
                        ],
                        "bPaginate": false
                        "columnDefs": [{ 
                        "orderable": false, 
                        "targets": 1 
                           },{ 
                        "orderable": false, 
                        "targets": 2
                                  }],
                        "ajax": {
                            url: "<?= AUTH_PANEL_URL ?>" + "version_control/version/ajax_release_list", // json datasource
                            type: "post", // method  , by default get
                            error: function () {  // error handling
                                jQuery("." + table + "-error").html("");
                                jQuery("#" + table + "_processing").css("display", "none");
                            }
                        }
                    });
                    jQuery("#" + table + "_filter").css("display", "none");
                    bind_table_search(dataTable, table, 'keyup');
                    bind_table_search(dataTable, table, 'change');
                });

                function update_version(id) {
                    var v = $("input[name=version_" + id + "]").val();
                    var mv = $("input[name=min_version_" + id + "]").val();
                    var fu = $("select[name=force_update_" + id + "]").val();
                    var fv = $("input[name=free_v_" + id + "]").val();
                    var versionData = {id: id, version: v, min_version: mv, force_update: fu, free_v: fv}
                  // console.log(versionData);        return false;
                    $.ajax({
                        data: versionData,
                        url: "<?= AUTH_PANEL_URL . 'version_control/version/update_version'; ?>",
                        type: "POST",
                        async: false,
                        dataType: "json",
                        success: function (res) {
                            if (res.status) {
                                window.location.reload();
                            } else {
                                $(".alert").removeClass('hide').addClass('alert-danger').html(res.message).delay(5000).fadeOut();
                            }
                        }

                    });
                }

                var META_ID = "";
                var deviceDir = {1: "android", 2: "ios", 3: "website", 4: "android tv"};
                var metaDevice = {1: "apk", 2: "ipa", 3: "exe"};
                $("#version_form").submit(async function (e) {
                    e.preventDefault();
                    var form = $(this);
                    var deviceType = $("select[name=device_type]").val();
                    META_ID = "_" + metaDevice[deviceType];
                        $.ajax({
                            type: "POST",
                            url: "<?= AUTH_PANEL_URL; ?>version_control/version/versioning",
                            data: form.serializeArray(),
                            dataType: "json",
                            success: function (data) {
                                if (data.status) {
                                    window.location.reload();
                                } else {
                                    show_toast("error", "Error!", data.error);
                                }
                            }
                        });
                    
                   

//        setTimeout(function () {
//            
//        },1000);

                });

                async function uploadFileAWS(fileId, deviceType, form) {

                    var size = upload_file_size($("#" + fileId)[0]);
                    let set_url = $(this).data("set_url");
                    var size = size.split(" ");
                    if (parseFloat(size[0]) == 0) {
                        show_toast("error", 'Choose Valid File', "Please Select Valid File");
                        $("input[name=" + set_url + "]").val("");
                        return false;
                    }
                    if (parseFloat(size[0]) > 102400) {
                        show_toast("error", 'Choose Valid File', "File size should be less than 100 MB.");
                        $("input[name=" + set_url + "]").val("");
                        return false;
                    }
                    let json = await s_s3_file_upload("<?= ADMIN_VERSION; ?>/release_management/" + deviceType + "/", $("#" + fileId)[0]);
                    form.find("input[name=" + fileId + "]").attr('type', 'text');
                    form.find("input[name=" + fileId + "]").val(json.Location);
                }
</script>
