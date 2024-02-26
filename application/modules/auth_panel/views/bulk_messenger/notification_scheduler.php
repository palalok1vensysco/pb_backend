<div class="col-lg-6 add_section" id="push_main" style="display: none">
    <section class="panel">
        <header class="panel-heading">
            Push Notification Scheduler
        </header>
        <div class="panel-body">
            <form role="form" action="" method="POST" enctype="multipart/form-data" >
                <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Type Of User</label>
                    <select  name="user_type"class="form-control  bulk_user_type">
                        <option value="0">ALL</option>
                        <option value="1" selected>Paid Users</option>
                        <option value="2">Free Users</option> 
                        <option value="3">Batch Users</option> 
                    </select>
                </div>
                <div class="form-group col-md-6 search_div">
                    <label>Select Courses</label>
                    <select data-tags="true"  name="course_ids[]" class="form-control  course_id select2-selection--multiple" multiple="multiple" required="">
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label >Device</label>
                    <div>
                        <select name="device_type" class=" device_type  form-control ">
                            <option value="">ALL</option>
                            <option value="1">ANDROID</option>
                            <option value="2">IOS</option>
                        </select>                 
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="notification_type">Notification Type</label>
                    <select class="form-control  notification_type " name = "notification_type" id="notification_type">
                        <option value="1" selected>General</option>
                        <option value="2">Course Detail</option>
                        <option value="5">Image</option>
                        <option value="6">URL</option>
                    </select>
                </div>
                <div class="form-group col-md-6" id="notification_type_text" >
                    <label for="">Extra (Paste url link for notification type URL )</label>
                    <input type="text" class="form-control notification_content" value="" accept="" name = "notification_content" id="">
                </div>

                <div class="form-group col-md-6">
                    <label>Title</label>
                    <input type="text" class="form-control " placeholder="Enter Title" name="title" required="">
                </div>
                
                 <div class="form-group col-md-6">
                    <label>Schedule Date</label>
                    <input type="text" class="form-control  schedule_time" placeholder="Enter Date" name="schedule_time" required="">
                </div>
                <div class="form-group col-md-12">
                    <label>Message</label>
                    <textarea class="form-control" name="message" required=""><?php
                        if ($this->input->get('text')) {/* echo base64_decode($this->input->get('text')); */
                        }
                        ?></textarea>
                    <span style="color: red"><?php echo ($this->session->flashdata('error') && $this->session->flashdata('error') == 'raw_error') ? form_error('message') : ''; ?></span>
                </div>
                <!--<input type="hidden" value="raw" name="notification_type">-->
                <div class="col-md-12">
                <button class="btn btn-xs " type="submit" >Submit</button>
                <button type="reset" class="btn btn-xs " onclick="$('#push_main').hide('slow');">Cancel</button>
                </div>
                <!--<div id="show_socket_state" class="bold col-md-12"> <i class="fa fa-spinner fa-spin bold " aria-hidden="true"></i>  Please Wait while we connecting you to server. </div>-->
            </form>

        </div>
    </section>
</div>

<div class="col-sm-12 no-padding">
    <section class="panel">
        <header class="panel-heading">
            Push Notification Scheduler List 
            <button type="button" class="btn btn-xs pull-right m-0" onclick="$('.add_section').show('slow')">Add New</button>
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down hide"></a>
                <!--<a href="javascript:;" class="fa fa-times"></a>-->
            </span>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="push-history-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Send by</th>
                            <th>Device</th>
                            <th>Message</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Schedule Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>
<script>
<?php if ($this->input->get('q')) { ?>
        $('#push_main').toggleClass('col-lg-12', 'col-lg-6');
<?php } else { ?>
        $('#push_main').toggleClass('col-lg-6', 'col-lg-12');
<?php } ?>
</script>

<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>js/jquery.dataTables.min.js"></script>
<script src="<?= AUTH_ASSETS ?>new/socket.js"></script>
<script type="text/javascript" src="<?= AUTH_ASSETS ?>assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript" language="javascript" >
    $(document).ready(function(){
        CKEDITOR.replace("message");
    });
   
    

</script>    
<script type="text/javascript" charset="utf8">


    jQuery(document).ready(function () {
        var table = 'push-history-grid';
        var dataTable = jQuery("#" + table).DataTable({
            "processing": true,
            "pageLength": 50,
            "serverSide": true,
            "order":[[0,"desc"]],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [1,2,4,5,7]},
            ],
            "ajax": {
                url: "<?= AUTH_PANEL_URL ?>bulk_messenger/push_notification/ajax_push_scheduler_list", // json datasource
                type: "post", // method  , by default get
                error: function () {  // error handling
                    jQuery("." + table + "-error").html("");
                    jQuery("#" + table + "_processing").css("display", "none");
                }
            }
        });
        jQuery("#" + table + "_filter").css("display", "none");
        bind_table_search(dataTable, table, 'keyup');
    });

    $('#notification_type').change(function () {
        $('#notification_type_text').show();
        if ($(this).val() == '1' || $(this).val() == "") {
            $('#notification_type_text').hide();
        }

        if ($(this).val() == '2') {
            $('#notification_type_text').find("label").text("Extra (Enter Course ID)");
        } else {
            $('#notification_type_text').find("label").text("Extra (Paste url link for notification type URL)");
        }
    }).change();
    
     $(document).ready(function () {
        $('.course_id').select2({
            placeholder: 'Select an Course',
            theme: "classic",
            width: 'resolve',
//            allowClear: true,
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/course_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });
    
     $('.schedule_time').datetimepicker({
                                            startDate: new Date()
                                        });
</script>    