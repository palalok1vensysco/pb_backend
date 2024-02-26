<div class="col-sm-12 add_channel" style="display: none">
    <section class="panel">
        <header class="panel-heading">
            Add Channel End Point(Media Package)
        </header>
        <div class="panel-body">
            <div class="panel-body">
                <form method="POST" action="">
                    <input name="channel_id" hidden="" value="<?= $channel_id ?>">
                    <div class="form-group col-sm-6">
                        <label>Select Endpoint ID</label>
                        <select name="endpoint_id" class="form-control input-sm">
                            <option type="1" value="<?= $channel_id ?>"><?= $channel_id ?></option>
                            <option type="2" value="<?= str_replace("NI-Live-", "", $channel_id) . "-DASH-DRM" ?>"><?= str_replace("NI-Live-", "", $channel_id) . "-DASH-DRM" ?></option>
                            <option type="3" value="<?= str_replace("NI-Live-", "", $channel_id) . "-HLS-DRM" ?>"><?= str_replace("NI-Live-", "", $channel_id) . "-HLS-DRM" ?></option>
                        </select>
                        <input name="type" value="1" hidden="">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Start Over Window (Video Recorder)</label>
                        <input type="text" placeholder="Enter Start Over Window" name="start_over_window" value="172800" class="form-control input-sm" required="">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Segment Length</label>
                        <select name="segment_duration"  class="form-control input-sm" required="">
                            <option value="">Select</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5" selected="">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Stream Order</label>
                        <select name="stream_order"  class="form-control input-sm" required="">
                            <option value="ORIGINAL">ORIGINAL</option>
                            <option value="VIDEO_BITRATE_ASCENDING">Video Bitrate Ascending</option>
                            <option value="VIDEO_BITRATE_DESCENDING" selected="">Video Bitrate Descending</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Type Remark</label>
                        <textarea class="form-control" name="remark" required=""></textarea>
                    </div>
                    <div class="form-group col-sm-12">
                        <button class="btn btn-info" type="submit">Submit</button>
                        <button class="btn btn-danger" type="button"  onclick="$('.add_channel').hide('slow')">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </section>
</div>
<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading">
            Channel Endpoint(s) LIST
            <span class="pull-right">
                <a class='btn-sm btn btn-success' onclick="$('.add_channel').show('slow')">Add</a>
            </span>
        </header>
        <div class="panel-body">
            <div class="card-body" style="">
                <div class="timeline-messages">
                    <?php
                    if ($endpoints) {
                        foreach ($endpoints as $ch) {
                            ?>
                            <!-- Comment -->
                            <div class="msg-time-chat">
                                <div class="message-body msg-in">
                                    <span class="arrow"></span>
                                    <div class="text">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <a class="pull-right btn-xs" onclick="return confirm('Are You Sure Want To Delete This Endpoint');" href="<?= AUTH_PANEL_URL . "live_module/media_package/delete_media_package_endpoint/" . $ch['id'] . "/" . $ch['channel_id'] . "/" . $ch['endpoint_id'] ?>"><i class="fa fa-times"></i></a>
                                                <?php if (strpos($ch['endpoint_id'], 'DASH-DRM') === false && strpos($ch['endpoint_id'], 'HLS-DRM') === false) {?>
                                                <a class="pull-right btn-xs" onclick="return confirm('Are You Sure Want To Update This Endpoint');" href="<?= AUTH_PANEL_URL . "live_module/media_package/update_media_package_endpoint/" . $ch['id'] . "/" . $ch['channel_id'] . "/" . $ch['endpoint_id'] ?>"><i class="fa fa-refresh"></i></a>
                                                <?php }?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="attribution"><a href="#">Channel Id</a> <?= $ch['channel_id'] ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="attribution"><a href="#">Endpoint Id</a> <?= $ch['endpoint_id'] ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="attribution"><a href="#">Description</a><?= $ch['description'] ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="attribution"><a href="#">Start Over Window</a><?= $ch['start_over_window'] ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="attribution"><a href="#">Segment Duration</a><?= $ch['segment_duration'] ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="attribution"><a href="#">Stream Order</a><?= $ch['stream_order'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">ARN </a> <?= $ch['arn'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">URL </a> <?= $ch['url'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">Remark </a> <?= $ch['remark'] ?></p>
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
<script>
    $("select[name=endpoint_id]").change(function () {
        let type = $('option:selected', this).attr('type');
        $("input[name=type]").val(type);
        if (type == "1" || type == "3") {
            $("select[name=segment_duration]").val(6);
        } else {
            $("select[name=segment_duration]").val(2);
        }
    });
</script>