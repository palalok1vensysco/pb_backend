
<script src="<?= AUTH_ASSETS ?>assets/ckeditor/ckeditor.js"></script>

<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>aws/aws-sdk-2.1.12.min.js"></script>
<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>aws/aws-init.js"></script>
<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?=AUTH_ASSETS?>js/jquery.dataTables.js"></script>
<link href="<?= AUTH_ASSETS ?>shaka_player/css/video-js.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>assets/bootstrap-datetimepicker/css/datetimepicker.css" />
<script type="text/javascript" src="<?=AUTH_ASSETS?>assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js" defer></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/shaka-player.compiled.debug.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/video.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/videojs-shaka.min.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/shaka-player.ui.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/videojs-seek-buttons.min.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/mux.min.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/custom-videojs.js"></script>
<script src="<?= AUTH_ASSETS ?>shaka_player/js/Youtube.min.js"></script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://www.youtube.com/iframe_api"></script>

        
<style type="text/css">
    .form{
        text-align: center;
    }

    .video-nput-2 label{display: block!important;}


    .video-nput-2 .vid-wid {
    display: inline-block;
    width: 94%;
    /* padding: 0px!important; */
}
.select2-container{
            border: none !important;
        }
        .select2-container .select2-selection--single .select2-selection__rendered{
            border: 1px solid gray;
            border-radius: 4px;
        }
    .btn-info {
        color: #eae7e1 !important;
    }
</style>




<div class="col-sm-12 no-padding">
    <section class="panel">
        <header class="panel-heading ban-head-new displa_flex"><div> Trending(s) LIST</div>
            
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>Serial No</th>
                            <th>Category</th>
                            <th>Geners</th>
                            <th>Content</th>
                            <th>Action</th>
                           
                        </tr>
                    </thead>
                    <thead>
                        <tr>
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
    <section class="panel">
    <header class="panel-heading">
        Manage Trending Position (<span class="total_count">0</span>)
        <button class="btn btn-xs pull-right manage_pos">Load Categories </button>
    </header>
    <div class="panel-body" id="load_category" style="display:none;">
        <div class="row ui-sortable category-position" id="draggable_portlets" style="cursor: all-scroll;">
            <div class="col-md-12 column sortable">

            </div>
        </div>
        <div class="col-md-12">
            <button class="btn-success btn-xs" onclick="save_position()">Save Position</button>
            <button class="btn-warning btn-xs" type="button" onclick="$('#load_category').hide('slow')">Cancel</button>
        </div>

    </div>
</section>



</div>
<script src="<?= AUTH_ASSETS ?>js/draggable-portlet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://devadmin.videocrypt.in/auth_panel_assets/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
<script type="text/javascript" src="https://devadmin.videocrypt.in/auth_panel_assets/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script src="<?= AUTH_ASSETS ?>js/jquery.validate.min.js" type="text/javascript"></script>

<script src="https://unpkg.com/mathjs/lib/browser/math.js"></script>
  





<script type="text/javascript" language="javascript" >
  $(document).ready(function () {
  //alert("hi");
  
  var table = 'all-user-grid';

// Check if the table exists in the DOM

    var dataTable = $("#all-user-grid").DataTable({
        "processing": true,
         "serverSide": true,
          "pageLength": 100, 
         "lengthMenu": [50, 100,200],
         "order": [[ 0, "asc" ]],
         "columnDefs":[{
            "targets":[3,4],
            "orderable":false
         }],
         "ajax": {
            url: "<?=AUTH_PANEL_URL?>trending/ajax_set_trending",
            type: "post",
            error: function () {
                // Remove this error handling if not needed
                jQuery("." + table + "-error").html("");
                jQuery("#" + table + "_processing").css("display", "none");
            }
        }
    });

   $("#" + table + "_filter").css("display", "none");
   $('.search-input-text').on('keyup click', function() { 
            var i = $(this).attr('data-column');
            var v = $(this).val(); 
            dataTable.columns(i).search(v).draw();
          });
 $('.search-input-select').on( 'change', function () {   // for select box
        var i =$(this).attr('data-column');
        var v =$(this).val();
        dataTable.columns(i).search(v).draw();
    } );
    DraggablePortlet.init();
        var categoryHtml = $(".sortable").html().trim();
        $(".manage_pos").click(function() {
            $('#load_category').show('slow');
            let selector = $(this);

            if (categoryHtml.length === 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?= AUTH_PANEL_URL ?>trending/all_trending",
                    dataType: 'json',
                    success: function(data) {
                        if (data.length > 0) {
                            $(".total_count").html(data.length);
                            var html = "";
                            $.each(data, function(index, value) {
                                categoryHtml += `<div data-catid=` + value.id + ` class="panel ui-sortable-handle">
                                                        <div  class="bg-primary alert margin-bottom "> ` + value.category + ` (` + value.title + `) ` + ` </div>
                                                    </div>`;
                            });
                            $(".sortable").html(categoryHtml);
                        }
                    },
                    error: function(data) {
                        selector.removeClass('hide');
                    }
                });
            }

        });
});

function save_position() {
        var position = [];
        $('.ui-sortable-handle').each(function() {
            position.push($(this).data('catid'));
        });
        $.ajax({
            type: 'POST',
            url: "<?= AUTH_PANEL_URL ?>trending/save_position_stream",
            data: {
                'ids': position
            },
            dataType: 'json',
            success: function(data) {
                console.log(data);
                show_toast('success', 'Position saved successfully', 'Updated');
            },
            error: function(data) {

            }
        });
    }
  
</script>



<?php $this->load->view("file_manager/common_script");?>