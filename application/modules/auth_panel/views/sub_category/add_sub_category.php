<style>
    
</style>
<?php
$error = validation_errors();
$display = "display:none;";
if (!empty($error)) {
    $display = "";
}
?>
<div class="col-lg-12 add_file_element" style="<?php echo $display; ?>">
    <section class="panel">
        <header class="panel-heading custom-panel-heading text-white" style="background: var(--color1)!important">
            Add Genres
        </header>
        <div class="panel-body bg-white p-2">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data" id="category_form">
                <div class="col-md-12">
                    <div class="form-group col-md-6">
                        <label for="title">Genres <span style="color:#ff0000">*</span></label>
                        <p><strong>Note :</strong> Please Enter Appropriate Genre Name</p>
                        <input type="text"    title="Please Enter alphabetic value" maxlength="100" oninput="checkpricee(this)" class="form-control" name = "title" id="title" placeholder="Enter Genres Name">
                        <span class="custom-error"><?php echo form_error('title'); ?></span>
                       
                    </div>
                    <div class="form-group col-md-6"> 
                        <label for="cat_name">Genre Background Image<span style="color:#ff0000">*</span></label>
                        <p>               
                    <strong>Image Type :</strong> jpg, jpeg, gif, png
                    ,<strong>Width  :</strong> 720 pixels
                    , <strong>Height :</strong> 420 pixels
                    </p>
                        <input type="file" accept="image/*"  class="form-control" name = "bg_img" id="thumbnailInputFile1" placeholder="Select Background Image">
                        <span class="custom-error"><?php echo form_error('thumbnail'); ?></span>
                        <span id="thumbnailmsg" style="color: red;"></span>
                       
                    </div>
                </div>
                 <div class="col-md-12">
                    
                    <div class="form-group col-md-6">
                        <label for="cat_name">Status<span style="color:#ff0000">*</span></label>
                        <select class="form-control input-sm m-bot15 status" name="status" >
                            <option value="">Select Status</option>
                            <option value="0">Enable</option>
                            <option value="1">Disable</option>
                        </select>
                        <span class="custom-error"><?php echo form_error('status'); ?></span>
                    </div>

                    <div class="form-group col-md-6 popularCheckBox">
                        <input type="checkbox"  id="is_popular" name="is_popular" value="1"  class="" >
                        <label for="cat_name">Is Popular</label>
                    </div>
                </div>
                  <div class="form-group col-md-12 text-right">
                    <button type="submit" class="btn btn-sm display_color text-white f-600">Submit</button>
                    <button class="btn btn-sm display_color text-white f-600" onclick="cancel()" type="button" >Cancel</button>
              </div>
            </form>
        </div>
    </section>
</div>

<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading bg-dark text-white d-flex justify-content-between align-items-center flex-direction-reverse">
          <div class="d-flex flex-direction-reverse align-items-baseline">
            <button onclick="$('.add_file_element').show('slow');" class="display_color btn-xs btn pull-right clr_green dropdown_ttgl text-white"><i class="fa fa-plus"></i> Add</button>
            <!-- <span class="tools pull-right" style="margin-right: 5px;">
                    <form id="download_content_csv" method="post" action="<?= AUTH_PANEL_URL . "sub_category/sub_category/get_request_for_csv_download" ?>">
                        <input type="hidden" name="genre" id="genre">
                         <button class="btn btn-xs display_color margin-right bold clr_green dropdown_ttgl text-white"><i class="fa fa-file mr-1" aria-hidden="true"></i>Download CSV</button> 
                         <textarea style="display:none;" name="input_json"></textarea>
                    </form>                   

            </span> -->
          </div>
            <?php // echo strtoupper($page);?> 
            <span>Genres List</span>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th id="sortColumn">Sr. No</th>
                            <th> Genres Name</th>
                            <th> Image</th>
                            <th> Is Popular</th>
                            <th>Status</th>
                            <th>Creation Date</th> 
                            <th>Modified Date</th> 
                            <th>Action </th>     
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control" id="genre_field"></th>
                            <th></th>  
                            <th></th>
                             <th>
                                <select data-column="2" class="search-input-select form-control">
                                    <option value="" >All</option>
                                    <option value="0">Enable</option>
                                    <option value="1">Disable</option>
                                </select>
                            </th>
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
            <button class="btn btn-xs pull-right manage_pos">Load Trending </button>
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

<?php
$adminurl = AUTH_PANEL_URL;

$custum_js = <<<EOD
                
                 <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >                   
        jQuery(document).ready(function() {
                       var table = 'all-user-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                           "pageLength": 15,
                           "lengthMenu": [[15, 25, 50], [15, 25, 50]],
                           "serverSide": true,
                           "ordering": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"sub_category/sub_category/ajax_sub_category_list/", // json datasource
                               type: "post",  // method  , by default get
                               error: function(){  // error handling
                                   jQuery("."+table+"-error").html("");
                                   jQuery("#"+table+"_processing").css("display","none");
                               }
                           }
                       } );
                       jQuery("#"+table+"_filter").css("display","none");
                       $('.search-input-text').on( 'keyup', function () {   // for text boxes
                           var i =$(this).attr('data-column');  // getting column index
                           var v =$(this).val();  // getting search input value
                           dataTable.columns(i).search(v).draw();
                       } );
                        $('.search-input-select').on( 'change', function () {   // for select box
                            var i =$(this).attr('data-column');
                            var v =$(this).val();
                            dataTable.columns(i).search(v).draw();
                        } );    

            $("body").on("click", ".copy_url", function(event){
                var tmpInput = $('<input>');
                  tmpInput.val($(this).data('url'));
                  $('body').append(tmpInput);
                  tmpInput.select();
                  document.execCommand('copy');
                  tmpInput.remove();
                  alert("Url copied paste it anywhere to use image url")
            });                                     
                   } );
                   function checkpricee(input) {
                    if (input.value == 0) {
                      input.setCustomValidity('The  Genres value must not be zero.');
                    } else {
                      // input is fine -- reset the error message
                      input.setCustomValidity('');
                    }
                  }

            $('#genre_field').keyup(function(){
                var genre = $('#genre_field').val();
                $('#genre').val(genre);
            });                  
               </script>   
               
                   

EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
<script src="<?= AUTH_ASSETS ?>js/draggable-portlet.js"></script>
<script src="https://unpkg.com/mathjs/lib/browser/math.js"></script>
<script>
     var _URL = window.URL || window.webkitURL;
          $("#thumbnailInputFile1").change(function(e) {
                var file, img;
                    var n_width=720,n_height=420;
                if ((file = this.files[0])) {
                    img = new Image();
                    img.onload = function() {
                        var ratio = this.width/this.height;
                    var ratio1 = ratio.toFixed(1);
                    // alert(ratio1);
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

            function cancel() {
    document.getElementById("category_form").reset();
    $('.add_file_element').hide('slow');  
}
</script>




<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~Dragable ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<script>
  $(document).ready(function () {
    DraggablePortlet.init();
        var categoryHtml = $(".sortable").html().trim();
        $(".manage_pos").click(function() {
            $('#load_category').show('slow');
            let selector = $(this);

            if (categoryHtml.length === 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?= AUTH_PANEL_URL ?>sub_category/sub_category/all_popular",
                    dataType: 'json',
                    success: function(data) {
                        if (data.length > 0) {
                            $(".total_count").html(data.length);
                            var html = "";
                            $.each(data, function(index, value) {
                                categoryHtml += `<div data-catid=` + value.id + ` class="panel ui-sortable-handle">
                                    <div  class="bg-primary sortable-list margin-bottom "> ` + value.title + `</div>
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
            url: "<?= AUTH_PANEL_URL ?>sub_category/sub_category/save_position",
            data: {
                'id': position
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
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~Dragable ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->