<?php
$parent_id = $this->input->get("parent_id");
$parent_id = $parent_id?$parent_id:0;
$menu_side = $this->input->get("menu_side");
$display = !empty($menus) ? 'block' : 'none';
$form_display = !empty($menu_detail) ? 'block' : 'none';
$meta_display = !empty($menu_detail) ? 'block' : 'none';
$id = !empty($menus) ? $menus['id'] : "";
$platform = !empty($menu_detail['platform']) ? $menu_detail['platform'] : "0";
?>
<link rel="stylesheet" type="text/css" href="<?= AUTH_ASSETS ?>css/jquery.dataTables.min.css">
<style type="text/css">
    .tools-right-1 {
        justify-content: end;
    }

    .sbu_btn1 {
        display: flex;
        margin-top: 20px;
        column-gap: 1px;
        justify-content: end;
        padding-right: 40px;
    }

    .td-list input[type="text"] {
        margin-bottom: 10px;
    }

    .td-list input[type="checkbox"] {
        margin-bottom: 10px;
    }

    /*Toggle Button Css */

    input.meta_info {
        opacity: 0;
        position: absolute;
        left: -9999px;
    }

    input.meta_info+label {
        user-select: none;
        transition: 0.2s ease;
        display: inline-block;
        height: 20px;
        width: 50px;
        position: relative;
        box-shadow: inset 0 0 0px 2px #e4e4e4;
        border-radius: 60px;
    }

    input.meta_info+label:before {
        content: "";
        position: absolute;
        display: block;
        height: 16px;
        width: 14px;
        top: 2px;
        left: 2px;
        border-radius: 15px;
        background: rgba(255, 151, 0, 1);
        transition: 0.2s cubic-bezier(0.24, 0, 0.5, 1);
    }

    input.meta_info+label:after {
        content: "";
        position: absolute;
        display: block;
        height: 15px;
        width: 15px;
        top: 50%;
        margin-top: -8px;
        left: 2px;
        border-radius: 60px;
        background: #fff;
        box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1), 0 4px 0px 0 rgba(0, 0, 0, .04), 0 4px 9px rgba(0, 0, 0, .13), 0 3px 3px rgba(0, 0, 0, .05);
        transition: 0.35s cubic-bezier(0.54, 1.6, 0.5, 1);
    }

    input.meta_info+label span {
        white-space: nowrap;
        height: 30px;
        line-height: 30px;
        margin-left: 51px;
        padding-left: 16px;
    }

    input.meta_info:checked+label:before {
        width: 45px;
        background: rgba(255, 151, 0, 1);
        transition: width 0.2s cubic-bezier(0, 0, 0, 0.1) !important;
    }

    input.meta_info:checked+label:after {
        left: 30px;
    }

    input.meta_info:checked+label {
        box-shadow: inset 0 0 0px 25px #e4e4e4;
        transition: box-shadow 2.5s cubic-bezier(0, 1.2, 0.94, 0.95);
    }

    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1em 1em 1em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow: 0px 0px 0px 0px #000;
        box-shadow: 0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width: auto;
        padding: 0 10px;
        border-bottom: none;
        margin-top: -15px;
        background-color: white;
        color: black;
    }

    .meta_color {
        color: #d58716 !important
    }

    /* Hide the "Remove All" button in Select2 dropdown */
    .select2-selection__clear {
        display: none !important;
    }

    .menuSectin {
        position: relative;
    }

    div#cke_description {
        border: 1px solid #ccc !important;
    }

    .adv-table table.display thead th {
        text-wrap: nowrap;
    }

    .custom-swal-popup {
        background-color: #f4f4f4;
    }

    .custom-swal-title {
        color: #2a2a2a;
    }
</style>

<section class="panel">
    <header class="panel-heading ban-head-new">
        <div>
            <?php echo ucfirst($page); ?> Position (<span class="total_count">0</span>)
        </div>
        <button class="btn btn-xs m-0 manage_position">Set Positioning <i class='fa fa-angle-down'></i></button>
    </header>
    <div class="panel-body" id="load_menu" style="display:none;">
        <div class="row ui-sortable category-position" id="draggable_portlets" style="cursor: all-scroll;">
            <div class="col-md-12 column sortable">

            </div>
        </div>
        <div class="col-md-12 p-0">
            <button class="btn btn-xs" onclick="save_position()">Save Position</button>
            <button class="btn btn-xs refresh" type="button" onclick="$('#load_menu').hide('slow')">Cancel</button>
        </div>

    </div>
</section>
<section class="panel setting_section " id="add_form" style="display:<?= $form_display; ?>;">
    <header class="panel-heading">
        <?= ($menu_detail ? "Update" : "Add") . " " . $page; ?>
    </header>
    <input type="hidden" name="parent_id" value="<?= $parent_id ?>">
    <div class="panel-body">
        <form method="POST" id="menu_form" name="menu_form" enctype="multipart/form-data" `autocomplete="off">
            <input type="hidden" name="menu_side" value="0">
            <div class="col-md-12">
                <div class="form-group col-sm-4 search_div">
                    <label for="lang_id">Language <span style="color:#ff0000"
                            class="error custom-dropdown">*</span></label>
                    <select name="lang_id" id="lang_id" class="form-control select2d">
                        <option value="">--select Option--</option>
                        <?php
                        foreach ($language_list as $language) {
                            echo '<option value="' . $language['id'] . '" ' . (@$menu_detail['lang_id'] == $language['id'] ? "selected" : "") . '>' . $language['title'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="title">Menu Title<span style="color:#ff0000">*</span></label>
                    <input type="text" class="form-control" value='<?= $menu_detail ? $menu_detail['title'] : '' ?>'
                        id="title" name="title" placeholder="Enter Menu Title" maxlength="40">
                </div>
                <div class="form-group col-sm-4">
                    <label for="landing_title">Landing Title</label>
                    <input type="text" class="form-control"
                        value='<?= $menu_detail ? $menu_detail['landing_title'] : '' ?>' id="landing_title"
                        name="landing_title" placeholder="Enter Landing Title" maxlength="40">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group col-sm-4">
                    <label for="is_publish">Publish<span style="color:#ff0000">*</span></label>
                    <select name="is_publish" id="is_publish" class="form-control input-sm custom-dropdown">
                        <option value="">Select</option>
                        <option value="0" <?= (!empty($menu_detail['is_publish']) == '0') ? "selected" : "" ?>>publish
                        </option>
                        <option value="1" <?= (!empty($menu_detail['is_publish']) == '1') ? "selected" : "" ?>>coming soon
                        </option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="is_visible">Visible (Ios)<span style="color:#ff0000">*</span></label>
                    <select name="is_visible" id="is_visible" class="form-control input-sm custom-dropdown">
                        <option value="">Select</option>
                        <option value="0" <?= (!empty($menu_detail['is_visible']) == '0') ? "selected" : "" ?>>Yes
                        </option>
                        <option value="1" <?= (!empty($menu_detail['is_visible']) == '1') ? "selected" : "" ?>>No
                        </option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="is_visible_android">Visible (Android)<span style="color:#ff0000">*</span></label>
                    <select name="is_visible_android" id="is_visible_android"
                        class="form-control input-sm custom-dropdown">
                        <option value="">Select</option>
                        <option value="0" <?= (!empty($menu_detail['is_visible_android']) == '0') ? "selected" : "" ?>>Yes
                        </option>
                        <option value="1" <?= (!empty($menu_detail['is_visible_android']) == '1') ? "selected" : "" ?>>No
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group col-sm-4">
                    <label for="landing_page">Landing Page<span style="color:#ff0000">*</span></label>
                    <select name="landing_page" id="landing_page" class="form-control input-sm custom-dropdown">
                        <option value=""> Select</option>
                        <?php
                        $landing_pages = get_landing_pages($this->db);
                        foreach ($landing_pages as $landing_page) {
                            $selected_lang = ($menu_detail && $menu_detail['landing_page'] == $landing_page['landing_page']) ? "Selected" : "";
                            echo '<option value="' . $landing_page['landing_page'] . '" ' . $selected_lang . '>' . $landing_page['landing_page_title'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="platform">Platform<span style="color:#ff0000">*</span></label>
                    <select name="platform" id="platform" class="form-control input-sm custom-dropdown">
                        <option value="0" <?= ($platform == 0) ? "selected" : "" ?>>All
                        </option>
                        <option value="1" <?= ($platform == 1) ? "selected" : "" ?>>Android
                        </option>
                        <option value="2" <?= ($platform == 2) ? "selected" : "" ?>>iOS
                        </option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="menu_side">Menu Location<span style="color:#ff0000">*</span></label>
                    <select name="menu_side" id="menu_side" class="form-control input-sm  custom-dropdown">
                        <option value=""> Select Menu Location</option>
                        <?php
                        foreach (menu_side() as $key => $type) {
                            $selected = ($menu_detail && $menu_detail['menu_side'] == $key) ? "selected" : "";
                            echo '<option value="' . $key . '" ' . $selected . '>' . $type . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group col-sm-4 is_child_option">
                    <label for="is_child">Is Child<span style="color:#ff0000">*</span></label>
                    <select name="is_child" id="is_child" class="form-control input-sm custom-dropdown">
                        <option value="0" <?= (!empty($menu_detail['parent_id']) == '0') ? "selected" : "" ?>>No
                        </option>
                        <option value="1" <?= (!empty($menu_detail['parent_id']) > '0') ? "selected" : "" ?>>Yes
                        </option>
                    </select>
                </div>
                <div class="form-group col-sm-4 sidemenu">
                    <label for="parent_id">Parent Id<span style="color:#ff0000">*</span></label>
                    <select name="parent_id" id="parent_id" class="form-control input-sm custom-dropdown">
                        <option value=""> Select Parent Id</option>
                    </select>
                </div>
                <div class="form-group col-sm-4 icon">
                    <label for="icon">Icon</label>
                    <input class="form-control " type="file" accept="image/*" name="thumbnail" id="icon">
                    <?php
                    if (!empty($menu_detail['thumbnail'])) {
                        $image_url = ($menu_detail['thumbnail']) ? $menu_detail['thumbnail'] : AUTH_ASSETS . "img/no-image.png";
                        echo '<input type="hidden" name="id" value="' . $menu_detail['id'] . '">';
                        ?>
                        <img src="<?= $image_url; ?>" width="100" height="100" class="img-responsive mt-3">
                    <?php } ?>
                </div>
                <div class="form-group col-md-12">
                    <div class="form-group col-sm-12 p-0">
                        <label for="menu_description">Description</label>
                        <textarea class="form-control input-sm description" name="description" id="description"
                            required="">
                    <?= $menu_detail ? $menu_detail['description'] : '' ?>
                    
                    </textarea>
                        <span class="text-danger">
                            <?php echo form_error('description'); ?>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-md-12">
                    <div class="justify-content-between">
                        <div class="form-group col-md-12 m-0 d-flex">
                            <small>Meta Information (Click on toggle to add meta information.)</small>
                            &nbsp; <div>
                                <input type="checkbox" name="meta_info" class="meta_info"
                                    <?= !empty($menu_detail['meta_information']) ? 'checked' : "" ?> id="toggle">
                                <label for="toggle"></label>
                            </div>
                        </div>

                    </div>
                    <fieldset class="scheduler-border col-md-12 meta_data hide m-0"
                        style="padding-bootom:0px !important;">
                        <legend class="scheduler-border mb-2 meta_color">Meta Information:</legend>
                        <div class="form-group col-md-6">
                            <label for="name">Title</label>
                            <input type="text" class="form-control " id="info" name="info" placeholder="Enter title"
                                value="<?= isset($meta_data['meta']['title']) ? $meta_data['meta']['title'] : ""; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="popup_title">Popup Title</label>
                            <input type="text" class="form-control " id="popup_title" name="popup_title"
                                placeholder="Enter Popup title"
                                value="<?= isset($meta_data['meta']['popup_title']) ? $meta_data['meta']['popup_title'] : ""; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact">Contact Title</label>
                            <input type="text" class="form-control " id="contact_title" name="contact_title"
                                placeholder="Enter Contact title"
                                value="<?= isset($meta_data['meta']['contact_title']) ? $meta_data['meta']['contact_title'] : ""; ?>">
                        </div>
                        <div class="form-group col-md-6 ">
                            <label for="">Contact Number</label>
                            <input type="text" name="mobile" class="form-control " placeholder="Enter Contact Number"
                                value="<?= isset($meta_data['meta']['contact']) ? $meta_data['meta']['contact'] : ""; ?>">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="third_party_url">Third Party URL</label>
                            <input type="text" class="form-control"
                                value='<?= $menu_detail ? $menu_detail['third_party_url'] : '' ?>' id="third_party_url"
                                name="third_party_url" placeholder="Third Party URL" maxlength="255">
                        </div>
                    </fieldset>

                    <div class=" col-md-12">
                        <button type="submit" class="btn btn-xs">
                            <?= ($menu_detail ? "Update" : "Add") . " " . $page; ?>
                        </button>
                        <button type="button" class="btn btn-xs"
                            onclick="location.href='<?php echo AUTH_PANEL_URL; ?>version_control/version/app_configuration'">Cancel</button>
                        <button type="reset" class="btn btn-xs reset">Clear</button>
                    </div>

        </form>
    </div>
</section>
<section class="panel">
    <header class="panel-heading ban-head-new">
        <?= $page ?>(s) LIST
        <?php if (!$parent_id) { ?>
            <a class="btn btn-xs pull-right add_section"><i class="toggle-icon fa fa-plus mr-1"></i><span
                    class="toggle-text">Add
                    New</span></a>
        <?php } ?>
    </header>
    <div class="panel-body">
        <div class="menuSectin">
            <div class="form-group col-md-3" style="position: absolute;right: 0;padding: 0px;z-index: 99;">
                <select name="temp_menu_side" id="filter_menu" class="form-control input-sm  custom-dropdown">
                    <?php
                    $temp_menu_id = $_SESSION['temp_menu_id'] ?? 0;
                    foreach (menu_side() as $key => $type) {
                        ?>
                        <option value="<?= $key ?>" <?= $temp_menu_id == $key ? "selected" : "" ?>>
                            <?= ucfirst(strtolower($type)) ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="adv-table">
            <table class="display table act_tab table-bordered table-striped" id="all-menu-grid">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Language</th>
                        <th>Parent</th>
                        <th>Menu Location</th>
                        <th>Landing Page</th>
                        <th>Publish</th>
                        <th>Visible</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>
                    <tr>
                        <th></th>
                        <th><input type="text" data-column="4" class="search-input-text form-control input-xs"></th>
                        <th>
                            <select class="form-control input-xs search-input-select" data-column="0">
                                <option value="">All</option>
                                <?php
                                foreach ($language_list as $language) {
                                    echo '<option value="' . $language['id'] . '">' . $language['title'] . '</option>';
                                }
                                ?>
                            </select>
                        </th>
                        <th></th>
                        <th></th>
                        <th><input type="text" data-column="1" class="search-input-text form-control input-xs"></th>
                        <th>
                            <select class="form-control input-xs search-input-select" data-column="7">
                                <option value="">All</option>
                                <option value="2">Publish</option>
                                <option value="1">Coming soon</option>
                            </select>
                        </th>
                        <th>
                            <select class="form-control input-xs search-input-select" data-column="8">
                                <option value="">All</option>
                                <option value="2">Yes</option>
                                <option value="1">No</option>
                            </select>
                        </th>
                        <th>
                            <select class="form-control input-xs search-input-select" data-column="2">
                                <option value="">All</option>
                                <option value="2">Enabled</option>
                                <option value="1">Disabled</option>
                            </select>
                        </th>
                        <th></th>
                    </tr>
                </thead>


            </table>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
<script src="<?= AUTH_ASSETS ?>js/draggable-portlet.js"></script>
<!-- <script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script> -->
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/select/1.5.0/js/dataTables.select.min.js"></script>
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/datetime/1.2.0/js/dataTables.dateTime.min.js"></script>

<script type="text/javascript" charset="utf8"
    src="https://editor.datatables.net/extensions/Editor/js/dataTables.editor.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
    <script type="text/javascript" src="<?= AUTH_ASSETS ?>assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>

<script type="text/javascript" language="javascript">
    $(document).ready(function () {
        $('#filter_menu').change(function () {
            $("#load_menu").hide();
            let val = $(this).val();
            let text = $(this).children("option:selected").text();
            let param = val ? 1 : 0;
            $.ajax({
                url: "<?= AUTH_PANEL_URL ?>admin/temp_menu/" + param,
                data: { id: val, text: text },
                dataType: 'json',
                method: 'POST',
                success: function (data) {
                }
            });
        });
    });
</script>
<script>
    CKEDITOR.replace("description");
    $(".add_section").click(function () {
        $("#load_menu").hide('slow');
        $("#add_form").toggle('slow');
        var buttonText = $(this).find('.toggle-text');
        var icon = $(this).find('.toggle-icon');
        if ($("#add_form").is(":visible")) {
            buttonText.text('Hide Form');
            icon.removeClass('fa-plus').addClass('fa-minus');
        } else {
            buttonText.text('Add New');
            icon.removeClass('fa-minus').addClass('fa-plus');
        }
    });
    $(document).ready(function () {
        var menuSide = $('select[name=menu_side]').val();
        $(".icon").toggleClass("hide", menuSide == "" || menuSide == '0');
        $(".is_child_option").toggleClass("hide", menuSide == "");
        var sideMenu = $('select[name=is_child]').val();
        var langId = $('select[name=lang_id]').val();
        if (sideMenu == "" || sideMenu == '0') {
            $(".sidemenu").addClass("hide");
        } else {
            load_parent_menu();
            $(".sidemenu").removeClass("hide");
        }
        $("body").on("change", "select[name=is_child]", function () {
            var lang_id = $('select[name=lang_id]').val(),
                menu_side = $('select[name=menu_side]').val(),
                sideMenu = $(this).val();
            $('.lang_error, .menu_error').text("");
            if (!sideMenu || sideMenu === '0') {
                $(".sidemenu").addClass("hide");
            } else if (!lang_id || !menu_side) {
                $('.lang_error').text(lang_id ? "" : "* Please Select Language");
                $('.menu_error').text(menu_side ? "" : "* Please Select Menu");
            } else {
                load_parent_menu();
                $(".sidemenu").removeClass("hide");
            }
        });
        $("body").on("change", "select[name=lang_id]", function () {
            $("#menu_form").validate().element("#lang_id");
            if ($('select[name=menu_side]').val()) {
                load_parent_menu();
            }
        });
        $("body").on("change", "select[name=menu_side]", function () {
            let menuLocation = $(this).val(),
                lang_id = $('select[name=lang_id]').val();
            $('.lang_error').text(lang_id ? "" : "* Please Select Language");
            $(".icon").toggleClass("hide", menuLocation === '0');
            $(".is_child_option").toggleClass("hide", !menuLocation);
            load_parent_menu();
        });
        jQuery(document).ready(function () {
            const table = 'all-menu-grid';
            const parent_id = <?= json_encode(!empty($parent_id) ? $parent_id : '0') ?>;
            const url_p = parent_id > 0 ? <?= json_encode(AUTH_PANEL_URL . 'version_control/version/ajax_child_list/') ?> + parent_id : <?= json_encode(AUTH_PANEL_URL . 'version_control/version/ajax_menu_list') ?>;

            var dataTable = jQuery("#" + table).DataTable({
                "processing": true,
                "pageLength": 15,
                "lengthMenu": [
                    [15, 25, 50],
                    [15, 25, 50]
                ],
                "serverSide": true,
                "order": [
                    [0, "asc"]
                ],
                "aoColumnDefs": [
                    { "width": "10%", "targets": 0 },
                    { "width": "7%", "targets": 2 },
                    { "width": "7%", "targets": 3 },
                    { "width": "10%", "targets": 4 },
                    { "width": "10%", "targets": 5 },
                    {
                        "bSortable": false,
                        "aTargets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    },],

                "ajax": {
                    url: url_p,
                    type: "post",
                    "data": function (d) {
                        d.menu_side = $('#filter_menu').val();
                    },
                    error: function () {
                        jQuery("." + table + "-error").html("");
                        jQuery("#" + table + "_processing").css("display", "none");
                    }
                }
            });

            $('#filter_menu').on('change', function () {
                let menuLocation = $(this).val();
                $(".total_count").html("");
                $(".sortable").html("");
                dataTable.columns(3).search($(this).val()).draw();
            });
            $('.search-input-select').val("");
            $('.search-input-text').val('');
            jQuery("#" + table + "_filter").css("display", "none");
            bind_table_search(dataTable, table, 'keyup');
            bind_table_search(dataTable, table, 'change');
        });
    });
    function load_menu(menuLocation) {
        DraggablePortlet.init();
        var menuHtml = $(".sortable").html().trim();
        var parent_id = "<?= !empty($parent_id) ? $parent_id : 0 ?>";
        var menu_side = "<?= @$menu_side ?>" ? "<?= @$menu_side ?>" : menuLocation
        var url_p = "<?= AUTH_PANEL_URL ?>version_control/version/all_menus" + (parent_id > 0 ? "_child/" + parent_id : "");
        $.ajax({
            type: 'POST',
            data: { menu_side: menu_side },
            url: url_p,
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    $(".total_count").html(data.result.length);
                    $(".sortable").html(menuHtml + data.result.map(value => `<div data-catid=${value.id} class="panel ui-sortable-handle"><div class="bg-primary alert margin-bottom">${value.title}</div></div>`).join(''));
                } else {
                    show_toast("error", "Menu Position", data.result);
                }
            },
            error: function (data) {
                $(this).removeClass('hide');
            }
        });
    }

    function save_position() {
        var position = $('.ui-sortable-handle').map(function () {
            return $(this).data('catid');
        }).get();

        $.post("<?= AUTH_PANEL_URL ?>version_control/version/save_menu_position", { 'ids': position })
            .done(function () {
                show_toast('success', 'Position saved successfully', 'Updated');
            })
            .fail(function () {
                // Handle error if needed
            });
    }

    function toggleMetaDataVisibility() {
        var isChecked = $('.meta_info').is(":checked");
        $(".meta_data").toggleClass('hide', !isChecked);
    }
    $(document).on('click', '.meta_info', toggleMetaDataVisibility);
    toggleMetaDataVisibility();

    function load_parent_menu() {
        var lang_id = $('select[name=lang_id]').val();
        var menu_side = $('select[name=menu_side]').val();
        $.ajax({
            url: "<?= AUTH_PANEL_URL ?>version_control/version/parent_menu/" + lang_id + '/' + menu_side,
            type: "POST",
            cache: false,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                $('#parent_id').removeAttr('disabled');
                console.log(response);
                var parent_id = <?= !empty($menu_detail['parent_id']) ? $menu_detail['parent_id'] : '0' ?>;
                console.log(parent_id);
                $('#parent_id').find('option').not(':first').remove();
                <?php
                $i = 0;
                ?>
                $.each(response, function (index, data) {
                    if (parent_id == data['id']) {
                        $('#parent_id').append('<option value="' + data['id'] + '" selected >' + data['title'] + '</option>');
                    } else {
                        $('#parent_id').append('<option value="' + data['id'] + '">' + data['title'] + '</option>');
                    }
                });
            },
            error: function (data) {
                console.log("error");
            }
        });
    }
    $(".custom-dropdown").on("change", function () {
        var dropdownValue = $(this).val();
        if (dropdownValue !== "") {
            var errorElementId = $(this).attr("id") + "-error";
            $("#" + errorElementId).text("");
        }
    });

    $(".manage_position").click(function () {
        if ($("#select_language").val() == '') {
            get_languages();
        } else {
            $("#load_menu").toggle('slow');
            $('.sortable').html('');
            load_menu($('#filter_menu').val());
        }
    });

    function get_languages() {
        $.ajax({
            type: 'POST',
            url: "<?= AUTH_PANEL_URL ?>" + "master/get_all_languages",
            dataType: 'json',
            success: function (response) {
                const language_data = response.data.map(value => ({ id: value.id, text: value.title }));
                const dropdownHTML = `
                <div class="form-group">
                    <select id="select_language1" class="form-control select2">
                        <option value="">--select Option--</option>
                        ${language_data.map(option => `<option value="${option.id}">${option.text}</option>`).join('')}
                    </select>
                </div>
            `;
                Swal.fire({
                    title: "<strong>Please select the language first</strong>",
                    html: dropdownHTML,
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'custom-swal-popup',
                        title: 'custom-swal-title',
                    },
                    didOpen: () => $('.custom-swal-popup').css('font-size', '12px'),
                });
                $('#select_language1').select2({
                    placeholder: 'Select a language',
                    allowClear: true,
                    dropdownParent: $('.swal2-popup'),
                }).on('change', function () {
                    const val = $(this).val();
                    const text = $(this).children("option:selected").text();
                    const param = val === "" ? 0 : 1;

                    $.ajax({
                        url: "<?= AUTH_PANEL_URL ?>admin/temp_language/" + param,
                        data: { id: val, text: text },
                        dataType: 'json',
                        method: 'POST',
                        success: function (data) {
                            if (data.data === 1) {
                                $("#load_menu").toggle('slow');
                                $('.sortable').html('');
                                load_menu($('#filter_menu').val());
                                $('.swal2-confirm').trigger('click');
                                $('#select_language option:eq(' + val + ')').prop('selected', true);
                            }
                        },
                    });
                });
            },
        });
        return false;
    }


    function initializeSelect2(selector, placeholderText) {
        $(selector).select2({
            placeholder: placeholderText,
            allowClear: true,
            theme: "classic",
            width: 'resolve',
        });
    }
    initializeSelect2("#lang_id", "Choose Language");
    initializeSelect2("#landing_page", "Choose Landing Page");
    initializeSelect2("#platform", "Choose Platform");
    initializeSelect2("#menu_side", "Choose Menu Location");
    initializeSelect2("#is_publish", "Choose Publish");
    initializeSelect2("#is_visible", "Choose Visible");
    initializeSelect2("#is_visible_android", "Choose Visible");
    initializeSelect2("#is_child", "Choose Parent");
    initializeSelect2("#parent_id", "Choose Parent");
    initializeSelect2("#filter_menu", "Choose Menu");

    $("#menu_form").validate({
        rules: {
            title: "required",
            lang_id: "required",
            landing_page: "required",
            menu_side: "required",
            is_publish: "required",
            is_visible: "required",
            is_visible_android: "required",
            filter_menu: "required"
        },
        messages: {
            title: "Please Enter Menu Title",
            lang_id: "Please Select Language",
            landing_page: "Please Select Landing Page",
            menu_side: "Please Select Menu",
            is_publish: "Please Select Status",
            is_visible: "Please Select Visibility",
            is_visible_android: "Please Select Visibility",
            parent_id: "Please Select Parent",
            filter_menu: "Please Select Menu"
        },
        errorPlacement: function (error, element) {
            if (element.is(":radio")) {
                error.appendTo(element.parents('.form-group'));
            }
            else { // This is the default behavior 
                error.insertBefore(element);
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
</script>