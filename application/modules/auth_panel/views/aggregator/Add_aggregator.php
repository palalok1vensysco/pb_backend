
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
<?php

$error = validation_errors();
$form_cat_ids = set_value('category_ids');
$form_name_ids = set_value('season_name');
$form_artist_ids = set_value('artist'); 
$display = "display:none;";
if (!empty($error)) {
    $display = "";
}
if (isset($validation_error)) {
    $validation = $validation_error;
} else {
    $validation = 0;
}

?>

<div class="col-lg-12 no-padding add_file_element" style="display:none;">
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Add Aggregator
            <button class="btn-xs btn pull-right display_color dropdown_ttgl text-white"  type="button" onclick="$('.add_file_element').hide('slow');" >Back</button>
        </header>
        <div class="panel-body">
            <form role="form"  method="post" enctype="multipart/form-data" id="add_video" autocomplete="off" >
                <div class="form-group h-64 col-md-6 ">
                    <label >Title <span style="color:#ff0000">*</span></label>
                    <input type="text" placeholder="Enter Aggregator Title" name = "title" id="title" class="form-control input-xs vid-wid" >                    

                </div>
                <div class="form-group col-md-6 ">
                    <label class="file_title">Upload Background Video</label>
                    <input type="file" accept="video/gif" name ="bg_video" id="bg_video" placeholder="choose your file" class="form-control input-xs" >
                 </div>
                 <div class="form-group col-md-12 video">
                    <label>Aggregator Image <span style="color:#ff0000">*</span> 
                        
                    </label>
                       <p>               
                <strong>Image Type :</strong> jpg, jpeg, gif, png
                ,<strong>Width  :</strong> 720 pixels
                , <strong>Height :</strong> 420 pixels
                </p>
                    <input type="file" accept="image/*" name = "thumbnail" class="form-control input-xs" id="thumbnailInputFile1" placeholder="Enter Thumbnail Url" required="">
                    <span class="error bold"><?php echo form_error('thumbnail'); ?></span>
                    <span id="thumbnailmsg" style="color: red;"></span>
                </div> 

                <div class="form-group col-md-12">
                    <button class="btn btn-sm display_color text-white f-600"  type="submit" >Upload</button>
                    <button class="btn btn-sm display_color text-white f-600"  type="button" onclick="$('.add_file_element').hide('slow');" >Cancel</button>
                </div>
            </form>

        </div>
    </section>
</div>



<div class="col-sm-12 no-padding">
    <section class="panel">
        <header class="panel-heading ban-head-new displa_flex bg-dark">
            <span>
            Aggregator List
            </span>
            <div class="tools-right-1">
            <button onclick="$('.add_file_element').show('slow');" class="btn-xs btn pull-right display_color dropdown_ttgl text-white"><i class="fa fa-plus"></i> Add</button>
            </div>
           <form class="search-bar pull-right margin-right hidden" onsubmit="return false;" >
                <input type="search" id="search_element_title" class="search-input-text" data-type="3" list="elementList" placeholder="Search"> 
            </form>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Aggregator Name</th>
                            <th>Poster</th>
                            <th>Created By </th>
                            <th>Action</th>
                           
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control input-xs"></th>
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

  var table = 'all-user-grid';


    var dataTable = $("#all-user-grid").DataTable({
        "processing": true,
         "serverSide": true,
          "pageLength": 100, 
         "lengthMenu": [50, 100,200],
         "order": [[ 1, "asc" ]],
         "columnDefs":[{
            "targets":[2,3,4],
            "orderable":false
         }],
         "ajax": {
            url: "<?=AUTH_PANEL_URL?>aggregator/Aggregator/ajax_aggregator_list",
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


});
  
</script>
<script src="https://unpkg.com/mathjs/lib/browser/math.js"></script>
<script>
     var _URL = window.URL || window.webkitURL;
          $("#posterInputFile1").change(function(e) {
                var file, img;
                    var n_width=720,n_height=420;
                if ((file = this.files[0])) {
                    img = new Image();
                    img.onload = function() {
                        var ratio = this.width/this.height;
                    var ratio1 = ratio.toFixed(1);
                     if(ratio1 != '1.7')
                     {
                         document.getElementById("thumbnailmsg").textContent="Please Enter aspect ratio size 720:420";
                         $("#thumbnailInputFile1").val('');
                     }
                     else
                     {
                         document.getElementById("thumbnailmsg").textContent=" ";
                     }
                     
                    };
                    img.onerror = function() {
                        alert( "not a valid file: " + file.type);
                        $("#thumbnailInputFile1").val('');
                    };
                    img.src = _URL.createObjectURL(file);

                }
            });
</script>


<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charcssset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >
               $(function(){
                $("#name").keypress(function (e) {
                   if((e.charCode > 64 && e.charCode < 91) || (e.charCode > 96 && e.charCode < 123) || e.charCode == 32)
                      {
                         return true;
                      }
                      else{
                         return false;
                  
                      }
                   
                });
             });

						$( function() {
					$( ".dpd1" ).datetimepicker();
					$( ".dpd2" ).datetimepicker();
					 }); // datepicker closed
               </script>
               <script src="https://unpkg.com/mathjs/lib/browser/math.js"></script>
               <script>
                    var _URL = window.URL || window.webkitURL;

                        $("#addimage1").change(function(e) {
                            var file, img;
                                var n_width=1920,n_height=822.86;

                            if ((file = this.files[0])) {
                                img = new Image();
                                img.onload = function() {                                                        
                                var ratio = this.width/this.height;                               
                                var ratio1 = ratio.toFixed(3);                                
                                 if(ratio1 != '0.667')
                                 {                                    
                                     document.getElementById("msg").textContent="Please Enter aspect ratio size 9:16";
                                 }
                                 else
                                 {
                                     document.getElementById("msg").textContent=" ";
                                 }                                 
                                };
                                img.onerror = function() {
                                    alert( "not a valid file: " + file.type);
                                };
                                img.src = _URL.createObjectURL(file);
                            }

                        });
                                      
     function checkpricee(input) {
        if (input.value == 0) {
          input.setCustomValidity('The Artist value must not be blank.');
        } else {
          // input is fine -- reset the error message
          input.setCustomValidity('');
        }
      }
               </script>
              

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
