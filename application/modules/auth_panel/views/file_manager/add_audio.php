<?php
$error = validation_errors();
$display = "display:none;";
if (!empty($error)) {
    $display = "";
}
?>
<section class="panel col-lg-6 px-0 add_file_element" style="<?php echo $display; ?>">
    <header class="panel-heading">
        Add Audio 
    </header>
    <div class="panel-body">
        <form role="form" method="post" enctype="multipart/form-data" id="imageForm">
            <div class="form-group">
                <label>Select Courses</label>
                <select data-tags="true"  name="course_id[]" class="form-control input-xs course_id select2-selection--multiple" multiple="multiple" id="courseid">
                </select>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Subject</label>
                <select name="subject_id" id="subject_id" class="form-control input-xs">
                </select>
                <span class="error bold"><?php echo form_error('subject_id'); ?></span>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Topic</label>
                <select name="topic_id" id="topic_id" class="form-control input-xs">
                    <option value="">--Select Topic--</option>
                </select>
                <span class="error bold"><?php echo form_error('topic_id'); ?></span>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Description</label>
                <textarea rows="4" cols="50" class="form-control input-xs" name="description" id="descid"></textarea>
                <span class="error bold"><?php echo form_error('description'); ?></span>
            </div>                               
            <div class="form-group">
                <label >Audio Title</label>
                <input type="text" placeholder="Enter title" name = "title" id="title" class="form-control input-xs">
                <span class="error bold"><?php echo form_error('title'); ?></span>
            </div>
             <div class="form-group">
                <label >Audio URl</label>
                <input type="text" placeholder="Enter file url" name = "file_url" id="file_url" class="form-control input-xs">
                <span class="error bold"><?php echo form_error('file_url'); ?></span>
            </div>
           <div class="form-group">
                <label for="exampleInputFile">Thumbnail Image</label>
                <input type="file" accept="image/*" class="form-control input-xs" name = "image_file" id="exampleInputFile">
                <span class="error bold"></span>
            </div>
           <!--   <div class="form-group">
                <label for="exampleInputFile">Thumbnail Image</label>
                <input type="file" accept="image/*" class="form-control input-xs" name = "thumbnail_file" id="exampleInputFileFile">
                <span class="error bold"></span>
            </div>
            <div class="form-group col-md-12">
                <label>Select Courses To Attach Image</label>
                <select data-tags="false" id="selected_course"  name="attach_course_id[]" class="form-control input-xs select2-selection--multiple" multiple="multiple" >
                </select>
            </div> -->
            <button class="btn btn-info btn-xs"  type="submit" >Upload</button>
            <button class="btn btn-warning btn-xs" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>
            <button type="button" class="btn btn-xs btn-success reset3">Clear</button>
        </form>

    </div>
</section>

<section class="panel col-md-12 px-0">
    <header class="panel-heading ban-head-new">      Audio LIST
        <div class="tools-right-1">
        <button onclick="$('.add_file_element').show('slow');" class="btn-success btn-xs btn"><i class="fa fa-plus"></i> Add</button></div>
      <!--  <form class="search-bar pull-right margin-right" onsubmit="return false;" >
            <input type="search" id="search_element_title" class="search-input-text" data-type="6" list="elementList" placeholder="Search">
        </form>-->
  
    </header>
    <div class="panel-body">
        <div class="adv-table">
            <table  class="display table table-bordered table-striped" id="all-user-grid">
                <thead>
                    <tr>
                        <th>Audipo Id</th>
                        <th>Title </th>
                        <th>Courses</th>
                        <th>Subject</th>
                        <th>Topic</th>
                        <th>Thumbnail</th>
                        <th>Created By</th>
                        <th>Created</th>
                        <th>Action </th> 	 
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th><input type="text" data-column="0"  class="search-input-text form-control input-xs"></th>
                        <th><input type="text" data-column="1"  class="search-input-text form-control input-xs"></th>
                        <th><input type="text" data-column="2"  class="search-input-text form-control input-xs"></th>
                        <th><input type="text" data-column="3"  class="search-input-text form-control input-xs"></th>
                        <th><input type="text" data-column="4"  class="search-input-text form-control input-xs"></th>			 		         <th></th>
                        <th><input type="text" data-column="5" class="search-input-text form-control input-xs"></th>
                        <th></th>
                        <th></th> 
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?=AUTH_ASSETS?>js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" >
    jQuery(document).ready(function () {

        //Subject Search
        $('#subject_id').select2({
            placeholder: '--Select Subject--',
            theme: "meterial",
            width: 'resolve',
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/subject_topics/subject_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults:function(data){
                    if(data.length > 0){
                        return {
                            results: data
                        };
                    }
                },
                cache: true
            }
        });

        //Topic Search
        $("#subject_id").change(function(){
            var subjectId = $(this).val();
            $('#topic_id').select2({
                placeholder: 'Search Topic',
                theme: "material",
                width: "resolve",
                ajax: {
                    url: "<?= AUTH_PANEL_URL ?>course_product/subject_topics/topic_search?subject_id="+subjectId+"&filter=yes",
                    dataType: 'json',
                    delay: 2000,
                    processResults: function (data) {
                        if(data.length > 0){
                            return {
                                results: data
                            };
                        }
                    },
                    cache: true
                }
            });
        });

        $('.course_id').select2({
            placeholder: 'Select an Course',
            theme: "classic",
            width: 'resolve',
    //            allowClear: true,
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/course_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function (data) {
                    if(data){
                        return {
                            results: data
                        };
                    }
                },
                cache: true
            }
        });
        var table = 'all-user-grid';
        var dataTable = jQuery("#" + table).DataTable({
            "processing": true,
            "pageLength": 15,
            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
            "serverSide": true,
            "order":[[0,"desc"]],
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [2,5,8]},
            ],
            "ajax": {
                url: "<?= AUTH_PANEL_URL ?>" + "file_manager/library/ajax_audio_file_list/", // json datasource
                type: "post", // method  , by default get
                error: function () {  // error handling
                    jQuery("." + table + "-error").html("");
                    jQuery("#" + table + "_processing").css("display", "none");
                }
            }
        });
        jQuery("#" + table + "_filter").css("display", "none");
        $('.search-input-text').on('keyup click', function () {   // for text boxes
            var i = $(this).attr('data-column');  // getting column index
            var v = $(this).val();  // getting search input value
            dataTable.columns(i).search(v).draw();
        });
        $('.search-input-select').on('change', function () {   // for select box
            var i = $(this).attr('data-column');
            var v = $(this).val();
            dataTable.columns(i).search(v).draw();
        });

        $("body").on("click", ".copy_url", function (event) {
            var tmpInput = $('<input>');
            tmpInput.val($(this).data('url'));
            $('body').append(tmpInput);
            tmpInput.select();
            document.execCommand('copy');
            tmpInput.remove();
            alert("Url copied paste it anywhere to use image url")
        });
    });
//**Akhilesh clear input field start***
    $(".reset3").click(function() {               
                $('#imageForm').find('input:text, input:password, select')
                    .each(function () {
                        $(this).val('');
                    });   
                    $('#courseid').empty(); 
                    $('#selected_course').empty(); 
                    $('#subject_id').empty();
                    $('#topic_id').empty(); 
                    $('#title').empty();
                    $('#link').empty();
                    $('#descid').val('');       
            });
    //**Akhilesh clear input field end***
</script>              
<?php $this->load->view("file_manager/common_script");?>