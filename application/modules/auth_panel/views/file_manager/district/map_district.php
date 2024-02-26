<style>
    .panel-heading {
    background: #1a1a19 none repeat scroll 0 0;
}
.text-white{

    color:white !important;
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
<div class="col-lg-12 add_file_element" style="<?php echo $display; ?>">
    <section class="panel">
        <header class="panel-heading custom-panel-heading text-white" style="background: var(--color1)!important ;color:white">
            MAP STATE ADN DISTRICT
        </header>
        <div class="panel-body bg-white p-2">
            <form id="form_campaign" data-submit="false" action="" method="post" enctype="multipart/form-data">
                   <div class="row">
                    <div class="form-group col-md-6">
                    <label for="cat_name">Category <span style="color:#ff0000">*</span></label>
                    <select class="form-control input-sm m-bot15 category_type" name = "cat_name" >
                    <option value=""> Select Category </option>
                     <?php if($categories){
                        foreach ($categories as $categorie) { ?>
                          <option value="<?= $categorie['id'] ?>"><?= $categorie['state_name']?> </option>
                     <?php }} ?>
                    </select>
                    <span class="custom-error"><?php echo form_error('cat_name'); ?></span>
                    </div>
                            <div class="form-group col-sm-6">
                                <label for="rlguruu">Select Related Genres <span style="color:#ff0000">*</span></label>
                                <select class="form-control input-sm m-bot15 selectpicker" name="related_genres[]" id="rlguru111"  required=""  data-live-search="true">
                                <!-- <option value="">Select</option> -->
                                <?php if($genres){
                        foreach ($genres as $genres) { ?>
                          <option value="<?= $genres['id'] ?>"><?= $genres['district_name']?> </option>
                     <?php }} ?>
                                </select>

                                <span class="custom-error"><?php echo form_error('related_guru'); ?></span>
                            </div>
                        </div><!--DIV ROW--> 
                <button type="submit" id="btnSubmit" class="btn btn-primary">Submit</button>
                <button class="btn  btn-sm display_color" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>
            </form>
        </div>
    </section>
</div>
<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            <button onclick="$('.add_file_element').show('slow');" class=" btn-xs btn pull-right display_color"><i class="fa fa-plus"></i> MAP</button>
            <span class="tools pull-right" style="margin-right: 5px;">
                    <form id="download_content_csv" method="post" action="<?= AUTH_PANEL_URL . "category/category/get_request_csv_download" ?>">
                         <button class="btn btn-xs btn-danger margin-right bold display_color"><i class="fa fa-file" aria-hidden="true"></i>Download CSV</button> 
                         <textarea style="display:none;" name="input_json"></textarea>
                    </form>                   

                </span>
            <?php // echo strtoupper($page);?>state & District list
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>Serial No</th>
                            <th>State Name</th>
                            
                            <th>District</th>
                            <th>Creation Date</th> 
                            <th>Modified Date</th> 
                               
                            <!-- <th>Action </th>      -->
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                              <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
                            <!-- <th></th> -->
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

$this->db->where("status", "0");
$all = $this->db->get('sub_category')->result();
?>

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
                           // "lengthMenu": [[15, 25, 50,100], [15, 25, 50]],
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"district/Add_district/ajax_district_map_list/", // json datasource
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
               <script>
             


               </script>
              
                   

EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
<script>
    $('.category_type_stop').change(function () { 
        var catType = $('select[name=cat_name]').val();
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
                                    $('#rlguru').append('<option value="' + data['id'] + '">' + data['sub_category_name'] + '</option>');
                                });
                            } ,
                            error: function (data) {
                                            console.log("error");
                                            //console.log(data);
                             }
                                    
                });
     });
$(document).ready(function () {

        $("#form_campaign").submit(function (e) {

            //stop submitting the form to see the disabled button effect
            //e.preventDefault();

            //disable the submit button
            $("#btnSubmit").attr("disabled", true);

            // //disable a normal button
            // $("#btnTest").attr("disabled", true);

            return true;

        });
    });

</script>
