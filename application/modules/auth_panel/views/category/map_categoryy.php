
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
        <header class="panel-heading custom-panel-heading text-white" style="background: var(--color1)!important ;color:white">
           Map Category With Genres
        </header>
        <div class="panel-body bg-white p-2">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                   <div class="">
                    <div class="form-group col-md-6">
                    <label for="category_id">Category <span style="color:#ff0000">*</span></label>
                    <select class="form-control input-sm m-bot15 category_type slect_sm_height selectpicker2"  name = "category_id" required>
                        <option value=""> Select Category </option>
                         <?php if($categories){
                            foreach ($categories as $categorie) { ?>
                              <option value="<?= $categorie['id'] ?>"><?= $categorie['title']?> </option>
                         <?php }} ?>
                    </select>
                    <span class="custom-error"><?php echo form_error('title'); ?></span>
                    </div>
                            <div class="form-group col-sm-6">
                                <label for="genres_id">Select Related Genres <span style="color:#ff0000">*</span></label>
                                <select class="form-control input-sm m-bot15 selectpicker2" name="genres_id[]" id="genres_id"  required="" multiple="" >
                                <?php if($genres){
                        foreach ($genres as $genres) { ?>
                          <option value="<?= $genres['id'] ?>"><?= $genres['title']?> </option>
                     <?php }} ?>
                                </select>

                                <span class="custom-error"><?php echo form_error('related_guru'); ?></span>
                            </div>
                        </div><!--DIV ROW--> 
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
            <button onclick="$('.add_file_element').show('slow');" class=" btn-xs btn pull-right display_color text-white"><i class="fa fa-plus"></i> MAP</button>
          </div>
            <?php // echo strtoupper($page);?>
            <span>Mapped Category List</span>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Category Name</th>
                            <th>Genres</th>
                            <th>Action </th>     
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th></th>
                            <th></th> 
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
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
                               url :"$adminurl"+"category/category/ajax_map_category_list/", // json datasource
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
<script>
    $(".selectpicker2").select2();
</script>
