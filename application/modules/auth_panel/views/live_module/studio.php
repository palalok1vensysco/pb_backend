<section class="panel add_section" style="display: <?= !empty($studio_detail) ? "block" : "none" ?>">
    <header class="panel-heading">
        <?= !empty($studio_detail) ? "Edit" : "Add" ?> Studio
    </header>
    <div class="panel-body">
        <?php if (!empty($studio_detail)) { ?>
            <form role="form" method="POST" action="<?= AUTH_PANEL_URL ?>live_module/studio/edit_studio" >
                <input type="hidden" name="studio_id" value="<?= $studio_detail->id; ?>">
                <div class="form-group col-md-6">
                    <label for="name">Studio Name</label>
                    <input type="text" class="form-control input-xs" id="name" name="name" value="<?= isset($studio_detail->name) ? $studio_detail->name : ""; ?>" placeholder="Enter Studio Name" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="name">Select Status</label>
                    <select class="form-control input-xs"name="status" required>
                        <option value="1" <?= $studio_detail->status == 1 ? "selected" : "" ?>>Active</option>
                        <option value="0" <?= $studio_detail->status == 0 ? "selected" : "" ?>>Inactive</option>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label for="name">Select Channels</label>
                    <select class="form-control input-xs selectpicker" id="channel_ids" data-live-search="true" name="channel_ids[]" multiple="multiple">
                        <?php
                        $channel_id = explode(",", $studio_detail->channel_ids);
                        foreach ($edit_channel_list as $channel) {
                            $selected = in_array($channel->id, $channel_id) ? "selected" : "";
                            echo '<option value="' . $channel->id . '" ' . $selected . '>' . $channel->channel_name . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-12" >
                    <button type="submit" class="btn btn-xs bold btn-info">Update</button>
                </div>
            </form>
        <?php } else { ?>
            <form role="form" method="POST" >
                <div class="col-md-12 error bold alert-box">
                    <?php echo validation_errors(); ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="name">Studio Name</label>
                    <input type="text" class="form-control input-xs" value="" id="name" name="name" placeholder="Enter Studio Name" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="name">Select Channels</label>
                    <select class="form-control input-xs selectpicker" id="channel_ids" data-live-search="true" name="channel_ids[]" multiple="multiple" required>
                        <?php
                        foreach ($channel_list as $channel) {
                            echo '<option value="' . $channel->id . '">' . $channel->channel_name . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-12" >
                    <button type="submit" class="btn btn-xs bold btn-info">Add</button>
                    <button type="reset" class="btn btn-xs bold btn-warning" onClick="$('.add_section').hide('slow')">Cancel</button>
                </div>
            </form>
        <?php } ?>
    </div>

</section>

<section class="panel">
    <header class="panel-heading bg-dark text-white">
        Studio LIST
        <?php if (empty($studio_detail)) { ?>
            <button type="button" class="btn btn-info btn-xs pull-right" onclick="$('.add_section').show('slow')">Add Studio</button>
        <?php } else { ?>
            <a type="button" class="btn btn-info btn-xs pull-right" href="<?= AUTH_PANEL_URL . "live_module/studio/index" ?>">Add Studio</a>
        <?php } ?>
    </header>
    <div class="panel-body">
        <div class="adv-table">
            <table  class="display table table-bordered table-striped" id="all-user-grid">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name </th>
                        <th>Channels</th>
                        <th>Status </th>
                        <th>Created on</th>
                        <th>Action </th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th><input type="text" data-column="0"  class="search-input-text form-control input-xs"></th>
                        <th><input type="text" data-column="1"  class="search-input-text form-control input-xs"></th>
                        <th><input type="text" data-column="2"  class="search-input-text form-control input-xs"></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>
<script src="<?= AUTH_ASSETS ?>js/draggable-portlet.js"></script>
<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?=AUTH_ASSETS?>js/jquery.dataTables.js"></script>
</script>
<script type="text/javascript" language="javascript" >
    var table = 'all-user-grid';
    var dataTable = jQuery("#" + table).DataTable({
        "processing": true,
        "pageLength": 15,
        "lengthMenu": [[15, 25, 50], [15, 25, 50]],
        "serverSide": true,
        "order":[[0,"desc"]],
        "aoColumnDefs": [
            {"bSortable": false, "aTargets": [-1,-3,-4]},
        ],
        "ajax": {
            url: "<?= AUTH_PANEL_URL ?>live_module/studio/ajax_studio_list", // json datasource
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

    $(document).on("click", '.action_element', function () {
        let selector = $(this);
        let status = selector.data("status");
        if (!confirm("Are you sure want to " + (status == "enable" ? "Disable" : "Enable") + "?")) {
            return false;
        }
        $.ajax({
            url: "<?= AUTH_PANEL_URL ?>live_module/studio/ajax_update_studio_status",
            type: "POST",
            dataType: 'json',
            data: {
                studio_id: selector.data('id'),
                status: selector.data('status')
            },
            success: function (data) {
                dataTable.draw();
                show_toast(data.type, data.message, data.title);
            },
            error: function (data) {
                show_toast('error', 'Please try after some time ', 'Error');
            }
        });
    });
</script>