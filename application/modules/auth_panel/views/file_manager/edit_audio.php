<div class="row">
    <div class="col-md-6">
        <section class="panel">
            <header class="panel-heading">
                Edit Audio 
            </header>
            <div class="panel-body">
                <form role="form" method="post" enctype="multipart/form-data">
                    <input type="hidden"  name = "id" id="id" value="<?php echo $video_detail['id']; ?>" class="form-control input-sm">
                    <div class="form-group">
                        <label>Select Courses</label>
                        <select data-tags="true"  name="course_id[]" class="form-control input-xs course_id select2-selection--multiple" multiple="multiple">
                            <?php
                            if ($course_list) {
                                foreach ($course_list as $course) {
                                    echo '<option value="' . $course->id . '" selected >' . $course->title . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Subject</label>
                        <select class="form-control input-xs" name="subject_id" id="subject_id">
                            <option value="<?=$video_detail['subject_id'];?>"><?=$video_detail['subject'];?></option>      	 
                        </select>
                        <span class="error bold"><?php echo form_error('subject_id'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Topic</label>
                        <select class="form-control input-xs" name="topic_id" id="topic_id">
                            <option value="<?=$video_detail['topic_id'];?>"><?=$video_detail['topic'];?></option>
                        </select>
                        <span class="error bold"><?php echo form_error('topic_id'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Description</label>
                        <textarea rows="4" cols="50" class="form-control input-xs" name="description"  class="form-control"><?php echo $video_detail['description'] ?></textarea><span class="error bold"><?php echo form_error('description'); ?></span>
                    </div>
                    <div class="form-group">
                        <label >Image Title</label>
                        <input type="test" placeholder="Enter title" name = "title" id="title" value = "<?php echo $video_detail['title'] ?>" class="form-control input-xs">
                        <span class="error bold"><?php echo form_error('title'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Upload Audio</label>
                        <input type="file" accept="image/*" name = "image_file" id="exampleInputFile" class="form-control input-xs">
                        <span class="error bold"><?php echo form_error('image_file'); ?></span>
                        <?php 
                            if(@file_get_contents($video_detail['file_url'])){
                                echo '<img src="'.$video_detail['file_url'].'" width="100px" height="100px">';
                            }
                        ?>
                    </div>
                    <button class="btn btn-info btn-xs"  type="submit" >Update</button>
                    <a href="<?= AUTH_PANEL_URL . "file_manager/library/add_image" ?>" class="btn btn-warning btn-xs"  >Cancel</a>
                </form>
            </div>
        </section>
    </div>

    <div class="col-md-6">
        <section class="panel">
            <header class="panel-heading">Attached In Course Detail</header>
            <div class="panel-body">
                <?php
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
                }
                ?>
                <div class="col-md-12">
                <h3 class="panel-heading">Attach Audio in course</h3>
                <form method="post" action="<?= AUTH_PANEL_URL ?>file_manager/library/attach_video_to_course">
                    <input type="hidden" name="file_id" value="<?= $video_detail['id']; ?>">
                    <div class="form-group col-md-12">
                        <label>Select Courses</label>
                        <select data-tags="false" id="selected_course"  name="attach_course_id[]" class="form-control input-xs select2-selection--multiple" multiple="multiple" required="">
                        </select>
                    </div>
                    <button type="submit" class="btn btn-xs btn-success">Attach Image</button>
                </form>
            </div>
            </div>
        </section>
    </div>
</div>
<?php
$subject_id = $video_detail['subject_id'];
$topic_id = $video_detail['topic_id'];
//$adminurl = AUTH_PANEL_URL;
//$custum_js = <<<EOD
        ?>

<script type="text/javascript" language="javascript" > 
			   
    jQuery(document).ready(function(){
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
        changeTopicView($("#subject_id").val());

    });
    
    //Topic Search
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
   

</script>
<?php $this->load->view("file_manager/common_script");?>