
<?php
$form_genres_ids = set_value('genres');
$error = validation_errors();
$display = "display:none;";
if (!empty($error)) {
    $display = "";
}

?>
<div class="col-lg-12 add_file_element" style="<?php echo $display; ?>">
    <section class="panel">
        <header class="panel-heading custom-panel-heading text-white" style="background: var(--color1)!important">
           Add Artist Type
        </header>
            <div class="panel-body bg-white p-2">
                <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                <div class="">
                    <div class="form-group col-md-6">
                        <label for="title">Artist Type <span style="color:#ff0000">*</span></label>
                        <input type="text" name="title"   id = 'cate' required class="form-control input-sm m-bot15" maxlength='100' oninput="checkpricee(this)" placeholder="Enter Artist type Name">
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
                </div>
                <div class="formSubmitBtn">
                    <button type="submit" class="btn btn-sm display_color text-white f-600">Submit</button>
                    <button class="btn  btn-sm display_color text-white f-600" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>
                </div>
            </form>
        </div>
    </section>
</div>
<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading bg-dark text-white d-flex justify-content-between align-items-center flex-direction-reverse">
            <div class="d-flex flex-direction-reverse align-items-baseline">
              <button onclick="$('.add_file_element').show('slow');" class="btn-xs btn pull-right display_color dropdown_ttgl text-white"><i class="fa fa-plus"></i> Add</button>            
            </div>
            <?php // echo strtoupper($page);?>
            <span>Artist Type List</span>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Artist Type Name</th>
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
                               url :"$adminurl"+"Artist/artist/ajax_artists_type/", // json datasource
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
                   
     function checkpricee(input) {
    if (input.value == 0) {
      input.setCustomValidity('The Artist type value must not be blank.');
    } else {
      // input is fine -- reset the error message
      input.setCustomValidity('');
    }
  }
  
                                   
               </script>  
EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
