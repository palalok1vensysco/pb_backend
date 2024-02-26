<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>

<div class="col-lg-12 add_file_element" class="d-show">
    <section class="panel">
        <header class="panel-heading custom-panel-heading text-white" style="background: var(--color1)!important">
           Add Season
        </header>
        <div class="panel-body bg-white p-2">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-md-5">
                    <label for="cat_name">Title <span style="color:#ff0000">*</span></label>
                    <input type="text" name="title"   id = 'cate' required class="form-control input-sm m-bot15" maxlength='100' oninput="checkpricee(this)" placeholder="Enter Title Name" value="<?= $seasons['title']; ?>">
                    <input type="hidden" name="id" value="<?= $id; ?>">
                    <span class="custom-error"><?php echo form_error('cat_name'); ?></span>
                </div>                 
                <div class="form-group col-md-7" >
                <div class="row">
                    <div class="col-md-8">
                    <label for="cat_name">Thumbnail <span style="color:#ff0000">*</span></label>
                        <input type="file" id="" name="image" onchange="$('#profile-image').attr('src', window.URL.createObjectURL(this.files[0]))" class="form-control-file border d-none">                            
                    </div>
                    <div class="col-md-4">
                        <img src="<?= $seasons['thumbnail']; ?>" id="profile-image" style="width:100px;">
                    </div>
                </div>                                               
               </div>             
            </div>
                <button type="submit" class="btn btn-sm display_color text-white f-600">Submit</button>
                <button class="btn  btn-sm display_color text-white f-600" type="button" >Cancel</button>
            </form>
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
                               url :"$adminurl"+"season/seasonController/ajax_season_list/", // json datasource
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
      input.setCustomValidity('The category value must not be zero.');
    } else {
      // input is fine -- reset the error message
      input.setCustomValidity('');
    }
  }
  
                                   
               </script>  
EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
