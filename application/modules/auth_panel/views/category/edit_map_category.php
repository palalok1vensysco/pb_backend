<style>
    .panel-heading {
    background: #1a1a19 none repeat scroll 0 0;
}
.text-white{

    color:white !important;
}


</style>

<div class="col-lg-12 add_file_element">
    <section class="panel">
        <header class="panel-heading custom-panel-heading text-white" style="background: var(--color1)!important ;color:white">
           Map Category With Genres
        </header>
        <div class="panel-body bg-white p-2">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                   <div class="row">
                    <div class="form-group col-md-6">
                    <label for="category_id">Category <span style="color:#ff0000">*</span></label>
                    <?php if(!empty($get_category_geners)) { $category_id = array_column($get_category_geners, 'category_id'); }?>
                    <select class="form-control input-sm m-bot15 category_type slect_sm_height selectpicker" data-live-search="true" name = "category_id" >
                        <option value=""> Select Category </option>
                         <?php if($categories){
                            foreach ($categories as $categorie) { ?>
                              <option <?php  if(!empty($category_id) && in_array($categorie['id'], $category_id)) { echo 'selected'; }?> value="<?= $categorie['id'] ?>"><?= $categorie['title']?> </option>
                         <?php }} ?>
                    </select>
                    <span class="custom-error"><?php echo form_error('title'); ?></span>
                    </div>
                    <?php if(!empty($get_category_geners)) { $genres_id = array_column($get_category_geners, 'genres_id'); }?>
                            <div class="form-group col-sm-6">
                                <label for="genres_id">Select Related Genres <span style="color:#ff0000">*</span></label>
                                <select class="form-control input-sm m-bot15 selectpicker" name="genres_id[]" id="genres_id"  required="" multiple data-live-search="true">
                                <?php if($genres){
                        foreach ($genres as $genres) { ?>
                          <option  <?php if(!empty($genres_id) && in_array($genres['id'], $genres_id)) { echo 'selected'; }?>  value="<?= $genres['id'] ?>"><?= $genres['title']?> </option>
                     <?php }} ?>
                                </select>

                                <span class="custom-error"><?php echo form_error('related_guru'); ?></span>
                            </div>
                        </div><!--DIV ROW--> 
                <button type="submit" class="btn btn-sm display_color text-white f-600">Submit</button>
                <!-- <button class="btn  btn-sm display_color text-white f-600" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button> -->
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
                               url :"$adminurl"+"Category/ajax_map_category_list/", // json datasource
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
    $('.category_type_stop').change(function () { 
        var catType = $('select[name=title]').val();
             $.ajax({
                 url: "<?= AUTH_PANEL_URL ?>category/category/get_categorywise_geners/" + catType,
                            type: "post",
                           // data: id:catType,
                            cache: false,
                            dataType: 'json',
                            contentType: false,
                            processData: false,

                            success: function(response) {
                                //console.log(response);
                                // Remove options
                                $('#rlguru').find('option').not(':first').remove();
                                // Add options
                                $.each(response, function(index, data) {
                                    $('#rlguru').append('<option value="' + data['id'] + '">' + data['title'] + '</option>');
                                });
                            } ,
                            error: function (data) {
                                            console.log("error");
                                            //console.log(data);
                             }
                                    
                });
     });

</script>
