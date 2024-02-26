
<div>
    <div class="col-lg-12 trangingHead">
        <section class="panel">       
            <header class="panel-heading bg-dark text-white">
                Add Trending
            </header>
        </section>
    </div>
    <div class="panel-body bg-white formheight">
        <form autocomplete="off" role="form" method="POST" enctype="multipart/form-data">
            <div class="col-lg-12">
                <div class="form-group col-md-6">
                    <label>Category <span style="color:#ff0000">*</span></label>  
                    <br>                  
                    <select name="cate_type" id="category" class="form-control" data-live-search="true">
                        <option value="">Select</option>
                        <?php foreach ($categories as $categorie) { ?>
                            <option value="<?= $categorie['id']; ?>"><?= $categorie['title'] ?></option>
                        <?php
                        } ?>
                    </select>
                </div>
                <div class="form-group col-md-6 genres">
                    <label>Genres <span style="color:#ff0000">*</span></label>
                    <br>
                    <select class="form-control input-xs gener " id="main_geners" name="genres_type_general" data-live-search="true" required>
                        <option value="">Select</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group col-md-6 content">
                    <label>Show <span style="color:#ff0000">*</span></label>
                    <br>
                    <select class="form-control input-xs " id="main_show" name="show_id" data-live-search="true" required>
                        <option value="">Select</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-md-12">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-sm display_color text-white f-600">Submit</button>
                </div>
            </div>
        </form>
    </div> 
</div>

<div class="mt-3">
    <div class="col-sm-12 mt-0 mb-3 trangingHead">               
        <header class="panel-heading bg-dark text-white">
            List Trending
        </header>       
    </div>
    <section class="panel">       
        <div class="panel-body">
            <div class="adv-table">
                <table class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Category</th>
                            <th>Show</th>
                            <th>Created at</th>
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

<div class="clearfix"></div>
</div>

<script src="<?= AUTH_ASSETS ?>js/jquery.validate.min.js" type="text/javascript"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" />

<script src="<?= AUTH_ASSETS ?>js/draggable-portlet.js"></script>


<script type="text/javascript">
    $('#category').change(function() {
        var catType = $('select[name=cate_type]').val();
        $.ajax({
            url: "<?= AUTH_PANEL_URL ?>trending/get_categorywise_geners/" + catType,
            type: "post",
            // data: id:catType,
            cache: false,
            dataType: 'json',
            contentType: false,
            processData: false,

            success: function(response) {               
                $('#main_geners').find('option').not(':first').remove();
                // Add options
                var output = "";
                $.each(response, function(index, data) {
                    $('#main_geners').append('<option value="' + data['id'] + '">' + data['title'] + '</option>');                   
                });
                console.log(output)                
            },
            error: function(data) {
                console.log("error");
            }

        });
    });

    $('#main_geners').change(function() {
        var genre_id = $('select[name=genres_type_general]').val();
        $.ajax({
            url: "<?= AUTH_PANEL_URL ?>trending/get_genreswise_show/" + genre_id,
            type: "post",
            cache: false,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                $('#main_show').find('option').not(':first').remove();
                $.each(response, function(index, data) {
                    $('#main_show').append('<option value="' + data['id'] + '">' + data['title'] + '</option>');
                });
                // $('.selectpicker').selectpicker('refresh');
            },
            error: function(data) {
                console.log("error");
                //console.log(data);
            }

        });
    });
</script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

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
                               url :"$adminurl"+"Trending/ajax_set_trending/", // json datasource
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



<script type="text/javascript" language="javascript" >
  $(document).ready(function () {
 
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
                                categoryHtml += `<div data-catid=` + value.show_id + ` class="panel ui-sortable-handle">
                                    <div  class="bg-primary sortable-list margin-bottom "> ` + value.cate_title + ` (` + value.show_title + `) ` + ` </div>
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

    $('select').select2();
  
</script>