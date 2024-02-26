<div class="row">
    <?php
    if ($course_attached_detail) {
        ?>
        <div style="" class="col-md-6 alert bg-warning ">
            <h4 class="bold">
                <i class="fa fa-ok-sign"></i>
                Note !
            </h4>
            <p>To edit this pdf you need to detach from course first.</p>
        </div>  
        <?php
    }
    ?>
    <script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>aws/aws-sdk-2.1.12.min.js"></script>
    <script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>aws/aws-init.js"></script>

    <div class="clearfix"></div>
    <div class="col-md-6">
        <section class="panel">
            <header class="panel-heading">
                Edit PDF 
            </header>
            <div class="panel-body">
                <form role="form" method="post" enctype="multipart/form-data">
                    <input hidden="" name="file_type" value="<?= $pdf_detail['file_type'] ?>">
                    <input type="hidden"  name = "id" id="id" value="<?php echo $pdf_detail['id']; ?>" class="form-control input-xs">
                    <div class="form-group">
                        <label>Select Courses</label>
                        <select data-tags="true"  name="course_id[]" class="form-control input-xs course_id select2-selection--multiple" multiple="multiple" required>
                            <?php 
                                if($course_list){
                                    foreach($course_list as $course){
                                        echo '<option value="'.$course->id.'" selected >'.$course->title.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Subject</label>
                        <select class="form-control input-xs" name="subject_id" id="subject_id" required >
                            <option value="<?=$pdf_detail['subject_id'];?>"><?=$pdf_detail['subject'];?></option>
                        </select>
                        <span class="error bold"><?php echo form_error('subject_id'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Topic</label>
                        <select class="form-control input-xs" name="topic_id" id="topic_id" required>				
                            <option value="<?=$pdf_detail['topic_id'];?>"><?=$pdf_detail['topic'];?></option>		
                        </select>
                        <span class="error bold"><?php echo form_error('topic_id'); ?></span>
                    </div>
                    <div class="form-group">
                        <label >PDF Title</label>
                        <input type="test" placeholder="Enter title" name = "title" id="title" value = "<?php echo $pdf_detail['title'] ?>" class="form-control input-xs" required>
                        <span class="error bold"><?php echo form_error('title'); ?></span>
                    </div>
                    <div class="form-group">
                    <label>Is Downloadable</label>
                    <select name="is_download" class="form-control input-xs">
                        <option value="">--Select--</option>
                        <option value="1" <?= (isset($pdf_detail['is_download']) && $pdf_detail['is_download'] == 1) ? "selected" : ""; ?> >Yes</option>
                        <option value="0" <?= (isset($pdf_detail['is_download']) && $pdf_detail['is_download'] == 0) ? "selected" : ""; ?> >No</option>
                    </select>
                    <span class="error bold"><?php echo form_error('is_download'); ?></span>
            </div>
            
                    <div class="form-group">
                        <label for="exampleInputFile" class="file_title">Upload PDF</label>
                        <!-- <button class="btn-info btn-xs cover_video" type="button">Url</button>&nbsp;&nbsp;
                        <button class="btn-danger btn-xs instant_upload hide" type="button" >Instant upload</button>-->
                         <?php
                        if (!empty($pdf_detail['file_url'])) {
                            $pdf_urls = explode("/", $pdf_detail['file_url']);
                            $pdf_url_last = end($pdf_urls);
                            $pdf_url_last = urlencode($pdf_url_last);
                            end($pdf_urls);
                            $last_key = key($pdf_urls);
                            $pdf_urls[$last_key] = $pdf_url_last;
                            $final_url = implode('/', $pdf_urls);
                            ?>
                           <!--  <button type="button" class="btn-success btn-xs" onclick="window.open('<?= $final_url; ?>', '_blank')" >Open PDF</button> -->
                        <?php } ?>
                        <input type="text" name = "pdf_file" class="form-control input-xs" id="pdf_file" value="<?= $pdf_detail['file_url'] ?>">
                        <span class="error bold"><?php echo form_error('pdf_file'); ?></span>
                    </div>  
                    <div class="form-group">
                        <label for="exampleInputFile">PDF Thumbnail</label>
                        <input type="file" accept="image/*" name = "thumbnail" id="" class="form-control input-xs">
                        <span class="error bold"><?php echo form_error('thumbnail'); ?></span>
                        <?php $thumbnail = !empty($pdf_detail['thumbnail_url'])?$pdf_detail['thumbnail_url']:AUTH_ASSETS."img/pdf.png"; ?>
                        <img src="<?=$thumbnail;?>" width="100px" heigth="100px" class="img img-fluid">
                    </div> 
                    <div class="form-group col-md-12">
                        <button class="btn btn-info btn-xs"  type="submit" >Update</button>
                        <a href="<?= AUTH_PANEL_URL . "file_manager/library/index" ?>" class="btn btn-warning btn-xs" >Cancel</a>
                    </div>
                </form>

            </div>
        </section>
    </div>
    
    <div class="col-md-6">
    <section class="panel">
        <header class="panel-heading">Attached In Course Detail</header>
        <div class="panel-body">
            <?php
            if($course_attached_detail){
            foreach ($course_attached_detail as $key => $detail) {
                ?>
                <div class="col-md-12" title="<?= $detail['tag'] ? "Paid Content" : "Free Content" ?>" style="border-left:5px solid <?= $detail['tag'] ? "green" : "red" ?>;margin:5px 0;padding-top: 10px;box-shadow:4px 3px 5px #a6daff;">
                    <a><button file_id="<?=$detail['id'];?>" course_id="<?=$detail['course_id'];?>" class="remove_file_from_topic btn btn-xs pull-right btn-danger"><i class="fa fa-times "></i></button></a>
                    <p>Course: <a target="_blank" href="<?= AUTH_PANEL_URL . "course_product/course/edit_course_page?course_id=" . $detail['course_id'] ?>"><?= $detail['course_name'] ?></a><?= ", Tile:" . $detail['tile_name'] ?></p>
                    <div class="set_virtual_name margin-bottom">
                        <input type="hidden" value="<?= $detail['id'] ?>" name="for_name_id" autocomplete="off">
                        
                        <div class="form-group col-md-5" style="padding-left:0px;">
                            <label>Title</label>
                            <input type="text" name="v_name" class="form-control input-xs" value="<?= $detail['v_name'] ?>">
                        </div>
                        <div class="form-group col-md-5" style="padding-left:0px;">
                            <label>Title(Hindi)</label>
                            <input type="text" name="v_name_2" class="form-control input-xs " value="<?= $detail['v_name_2'] ?>">
                        </div>
                        <div class="form-group col-md-2" style="padding-left:0px;">
                            <label style="opacity: 0">a</label>
                            <button type="submit" class="btn btn-default btn-xs virtual-name-changer">Update</button>
                        </div>
                    </div>
                </div>
                <?php
            } }else{
                echo '<div class="col-md-12"><p class="badge badge-warning">Pdf still not assigned in any course.</p></div>'; 
            }
            ?>
            <div class="col-md-12">
                <h3 class="panel-heading">Attach PDF in course</h3>
                <form method="post" action="<?= AUTH_PANEL_URL ?>file_manager/library/attach_video_to_course">
                    <input type="hidden" name="file_id" value="<?= $pdf_detail['id']; ?>">
                    <div class="form-group col-md-12">
                        <label>Select Courses</label>
                        <select data-tags="false" id="selected_course"  name="attach_course_id[]" class="form-control input-xs select2-selection--multiple" multiple="multiple" required="">
                        </select>
                    </div>
                    <button type="submit" class="btn btn-xs btn-success">Attach PDF</button>
                </form>
            </div>
        </div>
    </section>
</div>
</div>
<?php
if ($course_attached_detail) {
    ?>
    <script>
//        $("input").prop("readonly", true);
//        $("select").prop("disabled", true);
        $("textarea").prop("disabled", true);
//        $("button[type=submit]").parent().remove();
    </script>
    <?php
}
?>
<?php
$adminurl = AUTH_PANEL_URL;
$subject_id = $pdf_detail['subject_id'];
$topic_id = $pdf_detail['topic_id'];
?>


<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?=AUTH_ASSETS?>js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" >
    jQuery(document).ready(function () {
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
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        changeTopicView($("#subject_id").val());
        
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

   $("#subject_id").change(function(){
        changeTopic($(this).val());
    });
    
    function changeTopic(subjectId){
        $('#topic_id').empty();
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
    }


      function changeTopicView(subjectId){
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
    }
    
    $(".cover_video").click(function () {
        if ($(this).text() == "Url") {//file
            $(this).text("File");
            $(".instant_upload").removeClass("hide");
            $(".file_title").text("Upload PDF");
            $("#pdf_file").attr('type', 'file');
        } else {//url
            $(this).text("Url");
            $(".instant_upload").addClass("hide");
            $(".file_title").text("Enter PDF URL");
            $("#pdf_file").attr('type', 'text');
        }
    });
        
        var META_ID = "_PDF";
        $(".instant_upload").click(async function () {
            var size = upload_file_size($("#pdf_file")[0]);
            console.log(size);
            let set_url = $(this).data("set_url");
            var size = size.split(" ");
            if (parseFloat(size[0]) == 0) {
                show_toast("error", 'Choose Valid File', "Please Select Valid File");
                $("input[name=" + set_url + "]").val("");
                return false;
            }
            let json = await s_s3_file_upload("admin_v1/file_manager/pdf/", $("#pdf_file")[0]);
            console.log(json);
            $("input[name=pdf_file]").attr('type', 'text');
            $("input[name=pdf_file]").val(json.Location);
            $(".form_submit").show();
            $(".form_submit").click();
        });

</script>
<?php $this->load->view("file_manager/common_script");?>