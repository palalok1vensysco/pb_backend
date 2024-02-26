<?php
$error = validation_errors();
$display = "display:none;";
if (!empty($error)) {
    $display = "";
}
?>
    <section class="panel px-0 col-lg-12 add_file_element" style="<?php echo $display; ?>">
        <header class="panel-heading">
            Add Link
        </header>
        <div class="panel-body">
            <form role="form" method="post" enctype="multipart/form-data" id="linkForm">
                <div class="form-group">
                    <label>Select Courses</label>
                    <select data-tags="false"  name="course_id[]" class="form-control input-xs course_id select2-selection--multiple" multiple="multiple" id="courseid" required>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Subject</label>
                    <select class="form-control input-xs" name="subject_id" id="subject_id" required="required">						
                    </select>
                    <span class="error bold"><?php echo form_error('subject_id'); ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Topic</label>
                    <select class="form-control input-xs" name="topic_id" id="topic_id" required="required">
                        <option value="">--Select Topic--</option>
                    </select>
                    <span class="error bold"><?php echo form_error('topic_id'); ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Description</label>
                    <textarea rows="4" cols="50" class="form-control input-xs" name="description" class="form-control" id="descid"></textarea>						 
                    <span class="error bold"><?php echo form_error('description'); ?></span>
                </div>

                <div class="form-group">
                    <label > Title</label>
                    <input type="text" placeholder="Enter title" name = "title" id="title" class="form-control input-xs" required="">
                    <span class="error bold"><?php echo form_error('title'); ?></span>
                </div>

                <div class="form-group">
                    <label >Link</label>
                    <input type="text" placeholder="Enter link" name = "link" id="link" required class="form-control input-xs" required="">
                    <span class="error bold"><?php echo form_error('link'); ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile"> Thumbnail</label>
                    <input type="file" accept="image/*" name = "thumbnail" class="form-control input-xs" >
                    <span class="error bold"><?php echo form_error('thumbnail'); ?></span>
                </div> 
                <div class="form-group col-md-12">
                    <label>Select Courses To Attach Link</label>
                    <select data-tags="false" id="selected_course"  name="attach_course_id[]" class="form-control input-xs select2-selection--multiple" multiple="multiple"  >
                    </select>
                </div>
                <button class="btn btn-info btn-xs"  type="submit" >Upload</button>
                <button class="btn btn-warning btn-xs" onclick="$('.add_file_element').hide('slow');" type="reset" >Cancel</button>
                <button type="button" class="btn btn-xs btn-success reset2">Clear</button>
            </form>

        </div>
    </section>

    <section class="panel col-sm-12 px-0">
        <header class="panel-heading ban-head-new">    Link(s) LIST
            <div class="tools-right-1">
            <button onclick="$('.add_file_element').show('slow');" class="btn-success btn-xs btn"><i class="fa fa-plus"></i> Add</button>
            <!--<form class="search-bar pull-right margin-right" onsubmit="return false;" >
                <input type="search" id="search_element_title" class="search-input-text" data-type="8" list="elementList" placeholder="Search">
            </form> -->
            </div>
         
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>Link Id</th>
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
                            <th><input type="text" data-column="4"  class="search-input-text form-control input-xs"></th>
                            <th></th> 
                            <th><input type="text" data-column="5"  class="search-input-text form-control input-xs"></th> 
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
    jQuery(document).ready(function(){
        $('.course_id').select2({
            placeholder: 'Select an Course',
            theme: "classic",
            width: 'resolve',
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
                url: "<?= AUTH_PANEL_URL ?>" + "file_manager/library/ajax_link_list/", // json datasource
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
    });
    
    $(document).on('click', ".copy_link", function () {
        var copyText = $(this).next().attr('type', 'text').focus().select();
        document.execCommand("copy");
        $(this).next().attr('type', 'hidden')
        console.log("Copied the text: " + copyText.value);
    });
    //**Akhilesh clear input field start***
    $(".reset2").click(function() {               
                $('#linkForm').find('input:text, input:password, select')
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