<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<?php
$form_genres_ids = set_value('genres');
$error = validation_errors();
$display = "display:none;";
if (!empty($error)) {
    $display = "";
}
?>

<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

<div class="col-lg-12 add_file_element" style="<?php echo $display; ?>">
    <section class="panel">
        <header class="panel-heading custom-panel-heading text-white" style="background: var(--color1)!important">
           Add Page
        </header>
        <div class="panel-body bg-white p-2">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data" id="page_data">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="title">Page Name<span style="color:#ff0000">*</span></label>
                    <input type="text" name="title"   id = 'cate' class="form-control input-sm m-bot15" placeholder="Enter Page Name">
                    <span class="custom-error"><?php echo form_error('title'); ?></span>
                </div>                 
                <div class="form-group col-md-6">
                    <label for="cat_name">Status<span style="color:#ff0000">*</span></label>
                    <select class="form-control input-sm m-bot15 status" name="status" >
                    <option value="">Select Status</option>
                    <option value="0">Enable</option>
                      <option value="1">Disable</option>
                      
                    </select>
                    <span class="custom-error"><?php echo form_error('status'); ?></span>
                </div>
                <div class="form-group col-md-12">
                    <label for="cat_name">Description<span style="color:#ff0000">*</span></label>
                    <textarea rows="10" cols="50" class="form-control input-xs editor " name="description" ></textarea>
                    <span class="custom-error"><?php echo form_error('description'); ?></span>
                </div>
            </div>
                <button type="submit" class="btn btn-sm display_color text-white f-600">Submit</button>
                <button class="btn  btn-sm display_color text-white f-600" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>
            </form>
        </div>
    </section>
</div>
<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading bg-dark text-white d-flex justify-content-between align-items-center flex-direction-reverse">
            <div class="d-flex flex-direction-reverse align-items-baseline">
              <button onclick="$('.add_file_element').show('slow');" class=" btn-xs btn pull-right display_color f-600"><i class="fa fa-plus"></i> Add</button>          
            </div>
            <?php // echo strtoupper($page);?>
            <span>
              Page List
            </span>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>Serial No</th>
                            <th>Page Name</th>
                            <th>Status</th>
                            <th>Creation Date</th> 
                            <th>Modified Date</th> 
                            <th>Action </th>     
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th>
                                <select data-column="2" class="search-input-select form-control">
                                    <option value="">All</option>
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
</div>

<?php

$adminurl = AUTH_PANEL_URL;

$custum_js = <<<EOD
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
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
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"version_control/version/ajax_page_list/", // json datasource
                               type: "post",  // method  , by default get
                               error: function(){  // error handling
                                   jQuery("."+table+"-error").html("");
                                   jQuery("#"+table+"_processing").css("display","none");
                               }
                           }
                       } );
                       jQuery("#"+table+"_filter").css("display","none");
                       $('.search-input-text').on( 'keyup click', function () {   // for text boxes
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
               </script>  
EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>

<script src="<?= AUTH_ASSETS . "assets/ckeditor/ckeditor.js" ?>"></script>
<script src="<?= AUTH_ASSETS ?>js/jquery.validate.min.js" type="text/javascript"></script>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description');
    $("#page_data").submit( function(e) {
        var messageLength = CKEDITOR.instances['terms'].getData().replace(/<[^>]*>/gi, '').length;
        if( !messageLength ) {
            // alert( 'Please Enter  Ceo Message' );
            show_toast('error', 'Description  are required !!', 'Please Add  Description');
            e.preventDefault();
        }
    });
    $(function () {
        CKEDITOR.replace("description");       
    });

    // $('select').select2({
    //     placeholder: 'This is my placeholder',
    //     allowClear: true
    // });

    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });


    </script>
    
