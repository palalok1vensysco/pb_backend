<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
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
            Add District
        </header>
        <div class="panel-body bg-white p-2">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                <div class="form-group col-md-6">
                    <label for="district_name">District <span style="color:#ff0000">*</span></label>
                    <input type="text"    title="Please Enter alphabetic value" maxlength="100" oninput="checkpricee(this)" class="form-control" name = "district_name" id="district_name" placeholder="Enter District">
                    <span class="custom-error"><?php echo form_error('district_name'); ?></span>
                   
                </div>
                  <div class="form-group col-md-12">
                <button type="submit" class="btn btn-sm display_color">Submit</button>
                <button class="btn btn-sm display_color" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>
              </div>
            </form>
        </div>
    </section>
</div>

<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            <button onclick="$('.add_file_element').show('slow');" class="display_color btn-xs btn pull-right clr_green dropdown_ttgl text-white"><i class="fa fa-plus"></i> Add</button>
            <span class="tools pull-right" style="margin-right: 5px;">
                    <form id="download_content_csv" method="post" action="<?= AUTH_PANEL_URL . "sub_category/sub_category/get_request_for_csv_download" ?>">
                         <button class="btn btn-xs display_color margin-right bold clr_green dropdown_ttgl text-white"><i class="fa fa-file" aria-hidden="true"></i>Download CSV</button> 
                         <textarea style="display:none;" name="input_json"></textarea>
                    </form>                   

            </span>
            <?php // echo strtoupper($page);?> District
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>Serial No</th>
                            <th> District Name</th>
                            <th>Creation Date</th> 
                            <th>Modified Date</th> 
                          <!--   <th>Status</th>    -->  
                            <!-- <th>Action </th>      -->
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th></th> 
                            <th></th> 
                            <!-- <th></th>  -->
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>
<?php 

$this->db->where("status");
$all = $this->db->get('sub_category')->result();
?>

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
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"district/Add_district/ajax_district_list/", // json datasource
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
                      input.setCustomValidity('The  Genres value must not be zero.');
                    } else {
                      // input is fine -- reset the error message
                      input.setCustomValidity('');
                    }
                  }
                                   
               </script>   
                   

EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
