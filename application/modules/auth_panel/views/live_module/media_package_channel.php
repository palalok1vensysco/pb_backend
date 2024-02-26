<div class="col-sm-12 add_channel" style="display: none">
    <section class="panel">
        <header class="panel-heading">
            Add Channel(Media Package)
        </header>
        <div class="panel-body">
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="form-group col-sm-6">
                        <label>Channel ID</label>
                        <input type="text" placeholder="Enter Channel ID" name="channel_id"  class="form-control input-sm" required="">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Channel Description</label>
                        <input type="text" placeholder="Enter Channel Description" name="description"  class="form-control input-sm" required="">
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Type Remark</label>
                        <textarea class="form-control" name="remark"  required=""></textarea>
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
        <header class="panel-heading bg-dark text-white">
            (Media Package) Channel(s) LIST
            <span class="pull-right">
                <a class='btn-sm btn btn-success clr_green' onclick="$('.add_channel').show('slow')">Add</a>
            </span>
        </header>
        <div class="panel-body">
            <div class="card-body" style="">
                <div class="timeline-messages">
                    <?php
                    if ($channels) {
                        foreach ($channels as $ch) {
                            ?>
                            <!-- Comment -->
                            <div class="msg-time-chat">
                                <div class="message-body msg-in">
                                    <span class="arrow"></span>
                                    <div class="text">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a class="pull-right btn-xs hide" <?= ($ch['endpoint_count'] == 0) ? "onclick='confirm(\"Are You Sure Want To Delete This Channel\");'" : "onclick='alert(\"Please Stop Channel First\");return false;'" ?> href="<?= AUTH_PANEL_URL . "live_module/media_package/delete_media_package_channel/" . $ch['id'] . "/" . $ch['channel_id'] ?>"><i class="fa fa-times"></i></a>
                                                <a class="btn-xs btn-info pull-right" href="<?=AUTH_PANEL_URL."live_module/media_package/add_endpoint_to_channel?id=".$ch['channel_id']?>">Add Origin Point</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="attribution"><a href="#">Channel Id</a> <?= $ch['channel_id'] ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="attribution"><a href="#">Description</a><?= $ch['description'] ?></p>
                                            </div>
                                            <div class="col-md-2">
                                                <p class="attribution"><a href="#">Endpoint Count</a><?= $ch['endpoint_count'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">ARN </a> <?= $ch['arn'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">URL-A </a> <?= $ch['url_a'] ?></p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="attribution"><a href="#">URL-B </a> <?= $ch['url_b'] ?></p>
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