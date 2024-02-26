<style>
    /*dont remove this line it help for identidy font*/
    @import url(https://fonts.googleapis.com/css?family=Material+Icons);

    #video_player_aws{
        /*width:640px;*/
        height:275px;
        width:100%;
        /*height:30vh;*/  
    }
</style>

<div class="col-sm-12 px-0">
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Harvest Jobs List
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Video Name </th>
                            <th>Image</th>
                            <th>Channel</th>
                            <th>Harvest ID</th>
                            <th>Record From</th>
                            <th>Record To</th>
                            <th>VOD Available</th>
                            <th>Is Approved</th>
                            <th>Created on</th>
                            <th>Status </th>
                            <th>Action </th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>
<div class="modal" id="encrypted_video_modal" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Encrypted Video Management</h4>
            </div>
            <div class="modal-body">
                <div class="panel with-nav-tabs panel-primary">
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-info btn-xs pull-right fetch_list">Fetch Play List</button>
                                </div>
                                <div class="col-md-12 offline_vod_auto">
                                    <table class="display table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Name</th>
                                                <th>Size</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div role="dialog"  id="myModal4" class="modal fade" >
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title "> End Session <span class="badge badge-primary pull-right timer"></span></h4>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <input type="hidden" name="harvest_id" value="">
                    <div class="form-group col-md-6">
                        <label>Record From</label>
                        <input class="input-sm form-control" id="record_from" name="record_from">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Record To</label>
                        <input class="input-sm form-control" id="record_to" name="record_to">
                    </div>
                    <div class="form-group col-md-12">
                        <button class="btn btn-success btn-sm re_record_harvest" type="button">Re-Record Video</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div role="dialog"  id="preview_content" class="modal fade" >
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title "> Approved Content <span class="badge badge-primary pull-right timer"></span></h4>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <div class="form-group col-md-12">
                        <video id="video_player_aws" class="video-js vjs-default-skin" autoplay controls preload="auto"></video>
                    </div>
                    <div class="form-group col-md-12">
                        <button class="btn btn-success btn-sm approve_content" data-id='' type="button">Approve Content</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="<?= AUTH_ASSETS ?>new/mdtimepicker.css" rel="stylesheet">
<script src="<?= AUTH_ASSETS ?>new/mdtimepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?=AUTH_ASSETS?>js/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/shaka-player.compiled.debug.js"></script>
<script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/video.js"></script>
<script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/videojs-shaka.min.js"></script>
<script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/shaka-player.ui.js"></script>
<script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/videojs-seek-buttons.min.js"></script>
<script src="<?= base_url() ?>/auth_panel_assets/shaka_player/js/custom-videojs.js"></script>
<link href="<?= base_url() ?>/auth_panel_assets/shaka_player/css/video-js.css" rel="stylesheet">
<script type="text/javascript" language="javascript" >
    jQuery(document).ready(function () {
        var table = 'all-user-grid';
        var dataTable = jQuery("#" + table).DataTable({
            "processing": true,
            "pageLength": 15,
            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
            "serverSide": true,
            "order":[[0,"desc"]],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [-1,-2,-4,-5,-10]},
            ],
            "ajax": {
                url: "<?= AUTH_PANEL_URL ?>live_module/media_package/ajax_harvest_jobs_list", // json datasource
                type: "post", // method  , by default get
                error: function () {  // error handling
                    jQuery("." + table + "-error").html("");
                    jQuery("#" + table + "_processing").css("display", "none");
                }
            }
        });
        jQuery("#" + table + "_filter").css("display", "none");
        $('.search-input-text').on('keyup click', function () {   // for text boxes
            var i = $(this).attr('data-column'); // getting column index
            var v = $(this).val(); // getting search input value
            dataTable.columns(i).search(v).draw();
        });
        $('.search-input-select').on('change', function () {   // for select box
            var i = $(this).attr('data-column');
            var v = $(this).val();
            dataTable.columns(i).search(v).draw();
        });
        $(document).on('click', '.harvest_job_refresh', function () {
            let selector = $(this);
            selector.find("i").addClass("fa-spin");
            jQuery.ajax({
                url: "<?= AUTH_PANEL_URL ?>live_module/media_package/harvest_tracking",
                method: 'Post',
                dataType: 'json',
                data: {
                    id: selector.data("id")
                },
                success: function (data) {
                    show_toast(data.type, data.message, data.title);
                    selector.find("i").removeClass("fa-spin");
                    dataTable.draw();
                }
            });
        });
        $(document).on('click', '.trigger_media_convert', function () {
            let selector = $(this);
            let message = selector.data("is_vod") == 1 ? "This Video Is Already Available in VOD mode. Do you want to re-package video? This action will be chargable." : "Do you want to package video? This action will be chargable.";
            if (!confirm(message)) {
                return false;
            }

            jQuery.ajax({
                url: "<?= AUTH_PANEL_URL ?>live_module/media_package/harvest_job_to_vod",
                method: 'Post',
                dataType: 'json',
                data: {
                    id: selector.data("id")
                },
                success: function (data) {
                    show_toast(data.type, data.message, data.title);
                    dataTable.draw();
                }
            });
        });
    });
    $(document).on("click", ".download_offline,.download_vod_offline", function () {
        let selector = $(this);
        if (selector.hasClass("download_offline")) {
            $("#encrypted_video_modal").modal("show");
            $(".fetch_list").data("id", $(this).data("id"));
            $(".fetch_list").data("video_id", $(this).data("video_id"));
            $(".offline_vod_auto").find("tbody").html("");
        }
    });

    $(".fetch_list").click(function () {
        let id = $(this).data("id");
        let video_id = $(this).data("video_id");
        jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>live_module/media_package/fetch_video_playlist",
            method: 'Post',
            dataType: 'json',
            data: {
                id: id,
                video_id: video_id
            },
            success: function (data) {
                if (data.type == "success") {
                    let table = $(".offline_vod_auto").find("tbody");
                    table.html("");
                    $.each(data.data, function (key, value) {
                        table.append("<tr><td>" + (key + 1) + "</td><td>" + value.name + "</td><td>" + (value.size == "" ? "<i class='fa fa-times'></i>" : value.size) + "</td><td><button data-file_name='" + value.name + "' " + (value.size == "" ? "" : "disabled") + " data-id='" + video_id + "' data-link='" + value.link + "' class='btn btn-" + (value.size == "" ? "warning" : "info") + " btn-xs download_vod_offline'>" + (value.size == "" ? "<i class='fa fa-download'></i>" : "<i class='fa fa-check'></i>") + "</button></td></tr>");
                    });
                }
                show_toast(data.type, data.title, data.message);
            }
        });
    });
    $(document).on("click", ".download_vod_offline", function () {
        if (!confirm("Are you sure want to available this video in download mode.")) {
            return false;
        }
        let selector = $(this);
        jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>file_manager/library/available_video_in_vod",
            method: 'Post',
            dataType: 'json',
            data: {
                id: selector.data("id"),
                link: selector.data("link"),
                file_name: selector.data("file_name")
            },
            success: function (data) {
                show_toast(data.type, data.title, data.message);
                if (data.type == "success") {
                    selector.find("i").removeClass("fa-download").addClass("fa-check");
                    selector.removeClass("btn-warning").addClass("btn-info");
                    selector.prop("disabled", true);
                    selector.parent().prev().html(data.data.size);
                }
            }
        });
    });

    var preview_btn = null;
    $(document).on("click", ".preview_content", function () {
        let selector = $(this);
        preview_btn = selector;
        $("#preview_content").modal("show");
        $(".approve_content").data("id", selector.data("id"));
        $.ajax({
            type: 'POST',
            url: "<?= AUTH_PANEL_URL ?>live_module/channels/create_cloudfront_url",
            data: {
                url: selector.data("url"),
                name: selector.data("url"),
                flag: 1,
                course_id: "0",
                type: "video"
            },
            dataType: 'json',
            success: function (data) {
                if (data.data == 1) {
                    init_shaka_player("video_player_aws", data.url, 'm3u8', data.token);
                } else {
                    show_toast('error', 'Internal Error', 'Aws Url');
                }
            },
            error: function (data) {
                show_toast('error', 'Not able to generate url. Please try after sometime', 'Error');
            }
        });
    });

    $('#preview_content').on('hidden.bs.modal', function () {
        pause_shaka_player("video_player_aws");
//        window.location.reload();
    });

    $(".approve_content").click(function () {
        if (!confirm("Please cross verify the content have valid recording before approval.")) {
            return false;
        }
        let selector = $(this);
        jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>live_module/media_package/approve_harvest_content",
            method: 'Post',
            dataType: 'json',
            data: {
                id: selector.data("id")
            },
            success: function (data) {
                show_toast(data.type, data.title, data.message);
                if (data.type == "success") {
                    $('#preview_content').modal("hide");
                    preview_btn.parent().html("<span class='badge badge-success disabled'>Approved</span>");
                }
            }
        });
    });
//    $(document).on('click', '.delete_vod', function () {
//        if (!confirm("Are you sure want to delete original file. you will no longer for re-package or download mode this video.")) {
//            return false;
//        }
//        let selector = $(this);
//        jQuery.ajax({
//            url: "<?= AUTH_PANEL_URL ?>live_module/media_package/ajax_delete_video",
//            method: 'Post',
//            dataType: 'json',
//            data: {
//                id: selector.data("id")
//            },
//            success: function (data) {
//                show_toast(data.type, data.title, data.message);
//            }
//        });
//    });

    $("#record_from,#record_to").mdtimepicker({theme: 'dark', clearBtn: true, minTime: '3:00 PM', maxTime: '11:00 PM'});

    $(document).on("click", ".re_record_video", function () {
        $("#myModal4").modal("show");
        $(".re_record_harvest").data("id", $(this).data("id"))
    });

    $(document).on("click", ".re_record_harvest", function () {
        let selector = $(this);
        let session = {};
        session = {
            record_from: $("#myModal4").find("input[name=record_from]").val(),
            record_to: $("#myModal4").find("input[name=record_to]").val(),
        }
        if (session.record_from == "" || session.record_to == "") {
            show_toast("error", "Please select valid from/to record time.", "Error");
            return false;
        }

        let c_date = "<?= date("m/d/Y") ?>";
        let start_date = new Date(c_date + " " + session.record_from);
        if (start_date < new Date(new Date().getTime() - (window.totalSeconds * 1000))) {
            show_toast("error", "Please select valid from record time.", "Error");
            return false;
        }

        let end_date = new Date(c_date + " " + session.record_to);
        if (end_date > new Date()) {
            show_toast("error", "Please select valid to record time.", "Error");
            return false;
        }

        if (!confirm("Do you really want to Re-Record This Video?")) {
            return false;
        }
        $.ajax({
            url: "<?= AUTH_PANEL_URL ?>live_module/media_package/ajax_re_record_harvest",
            type: "POST",
            dataType: 'json',
            data: {
                harvest_id: selector.data('id'),
                session: session
            },
            success: function (data) {
                window.location.reload();
            },
            error: function (data) {
                show_toast('error', 'Please try after some time ', 'Internal Server Error');
            }
        });
    });
</script>