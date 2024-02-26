<style>
    .btn-success{
    background-color: #ff9700;
    border-color: #ff9700;
    color: #FFFFFF;
    }
    .btn-info{
background-color: #ff9700;
border-color: #ff9700;
color: #FFFFFF;
    }
    .btn-warning{
background-color: #ff9700;
border-color: #ff9700;
color: #FFFFFF;
    }

</style>
<div class="col-lg-12 px-0">
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Terms And Conditions 
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="<?= AUTH_PANEL_URL . "version_control/version/set_terms" ?>" id="terms_policy">
                <div class="form-group col-md-12">
                    <label>Terms and Conditions <span style="color:#ff0000">*</span></label>
                    <textarea rows="10" cols="50" class="form-control input-xs editor " name="terms" ><?= (array_key_exists("terms", $info)) ? $info['terms'] : ""; ?></textarea>
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success  clr_green" type="submit">save</button>
                </div>
            </form>
        </div>
    </section>

    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Privacy Policy
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="<?= AUTH_PANEL_URL . "version_control/version/set_policy" ?>" id="term_policy">
                <div class="form-group col-md-12">
                    <label>Privacy Policy <span style="color:#ff0000">*</span></label>
                    <textarea rows="10" cols="50" class="form-control input-xs editor " name="policy" ><?= (array_key_exists("policy", $info)) ? $info['policy'] : ""; ?></textarea>
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success clr_green" type="submit">save</button>
                </div>
            </form>
        </div>
    </section>

    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Refund Policy
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="<?= AUTH_PANEL_URL . "version_control/version/set_refund_policy" ?>" id="termss_policy">
                <div class="form-group col-md-12">
                    <label>Refund Policy <span style="color:#ff0000">*</span></label>
                    <textarea rows="10" cols="50" class="form-control input-xs editor " name="refund_policy"><?= (array_key_exists("refund_policy", $info)) ? $info['refund_policy'] : ""; ?></textarea>
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success  clr_green" type="submit">save</button>
                </div>
            </form>
        </div>
    </section>

    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            About Us
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="<?= AUTH_PANEL_URL . "version_control/version/set_about_us" ?>" id="term_polic">
                <div class="form-group col-md-12">
                    <label>About Us <span style="color:#ff0000">*</span></label>
                    <textarea rows="10" cols="50" class="form-control input-xs editor " name="about_us"><?= (array_key_exists("about_us", $info)) ? $info['about_us'] : ""; ?></textarea>
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success  clr_green" type="submit">save</button>
                </div>
            </form>
        </div>
    </section>
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Contact Us
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="<?= AUTH_PANEL_URL . "version_control/version/set_contact_us" ?>" id="terms_policy">
                <div class="form-group col-md-12">
                    <label>Contact Us <span style="color:#ff0000">*</span></label>
                    <textarea rows="10" cols="50" class="form-control input-xs editor " name="contact_us"><?= (array_key_exists("contact_us", $info)) ? $info['contact_us'] : ""; ?></textarea>
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success  clr_green" type="submit">save</button>
                </div>
            </form>
        </div>
    </section>

    <!-- shubham code start -->
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Packages
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="<?= AUTH_PANEL_URL . "version_control/version/set_packages" ?>" id="term_policy">
                <div class="form-group col-md-12">
                    <label>Packages <span style="color:#ff0000">*</span></label>
                    <textarea rows="10" cols="50" class="form-control input-xs editor "  name="packages"><?= (array_key_exists("packages", $info)) ? $info['packages'] : ""; ?></textarea>
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success  clr_green" type="submit">save</button>
                </div>
            </form>
        </div>
    </section>
    <!-- shubham code end -->

    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Footer Details
            <?php //echo "<pre>";print_r(array);die;
              //  $array = explode(',', $info['footer_detail']);
                $data = json_decode($info['footer_detail'], TRUE);
               // echo "<pre>";print_r($data['facebook_detail']);die;
            ?>
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" id="footer" action="<?= AUTH_PANEL_URL . "version_control/version/set_footer" ?>" >
                <input type="hidden"  name="meta_name" value="FOOTER_DETAIL">
               
                <div class="form-group col-md-4">
                    <label>Facebook <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="facebook_detail" value="<?= isset($data['facebook_detail']) ? $data['facebook_detail'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Twitter</label>
                    <input  class="form-control input-xs"  name="twitter_detail" value="<?= isset($data['twitter_detail']) ? $data['twitter_detail'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Instagram</label>
                    <input  class="form-control input-xs"  name="instagram_detail" value="<?= isset($data['instagram_detail']) ? $data['instagram_detail'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Youtube</label>
                    <input  class="form-control input-xs"  name="youtube_detail" value="<?= isset($data['youtube_detail']) ? $data['youtube_detail'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Telegram</label>
                    <input  class="form-control input-xs"  name="telegram_detail" value="<?= isset($data['telegram_detail']) ? $data['telegram_detail'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <?php //print_r($f_lists);
               // if(isset($f_lists->Footer_Android_url) && $f_lists->Footer_Android_url==1){?>
                <div class="form-group col-md-4">
                    <label>Android URL</label>
                    <input  class="form-control input-xs"  name="gplay_detail" value="<?= isset($data['gplay_detail']) ? $data['gplay_detail'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
            <?php// } ?>
                <div class="form-group col-md-4">
                    <label>Support Email</label>
                    <input  class="form-control input-xs" type="email" name="support_email" value="<?= isset($data['support_email']) ? $data['support_email'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Support Number</label>
                    <input  class="form-control input-xs"  id="mobile" name="support_number" value="<?= isset($data['support_number']) ? $data['support_number'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <?php //print_r($f_lists->Footer_App_Link);
               // if(isset($f_lists->Footer_iOS_url) && $f_lists->Footer_iOS_url==1){?>
                <div class="form-group col-md-4">
                    <label>IOS URL</label>
                    <input  class="form-control input-xs"  name="app_store_link" value="<?= isset($data['app_store_link']) ? $data['app_store_link'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
            <?php// }?>
               
                <div class="form-group col-md-4">
                    <label>About Us For Web</label>
                    <input  class="form-control input-xs"  name="about_us" value="<?= isset($data['about_us']) ? $data['about_us'] : ""; ?>">
                    <span class="error bold"></span>
                </div>

                <div class="form-group col-md-4">
                    <label>Client Name</label>
                    <input  class="form-control input-xs"  name="client_name" value="<?= isset($data['client_name']) ? $data['client_name'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Client Address</label>
                    <input  class="form-control input-xs"  name="client_address" value="<?= isset($data['client_address']) ? $data['client_address'] : ""; ?>">
                    <span class="error bold"></span>
                </div>

                <div class="form-group col-md-12">
                    <button class="btn btn-success  clr_green" type="submit">save</button>
                    <button class="btn btn-warning  clr_green" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
        </div>
    </section>

    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            FAQ 
        </header>
        <div class="panel-body"><small class="bold" >Note-: You can set order of global faq(s) by using drag and drop. Click UPDATE button to save. Click UPDATE button to save. </br> To add new global faq(s) to this course.</small></div>
        <div class="panel-body add_faq_div" >
            <form role="form" method="post" id="add_faq_form">
                <div class="error bold faq_error"></div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Question</label>
                    <input type="text" placeholder="Question" name="faq_question"  id="faq_question" class="form-control">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="faq_description" id="faq_description" placeholder="Description" class="form-control"></textarea>
                </div>
                <input  type="hidden"  name="course_id" id="faq_course_id" value="<?php echo $this->input->get('course_id'); ?>" >
                <input  type="hidden"  name="faq_id" id="faq_id" value="" >
                <button class="btn btn-warning pull-right  clr_green" type="button" onclick="window.location.reload();">Cancel</button>
                <button class="btn btn-info  add_faq_class pull-right  clr_green" style="margin-right:10px;" name="faq_submit"  type="submit"  >Submit</button>
            </form>
        </div>
        <?php if ($faq) { ?>
            <div class="panel-body">
                <form role="form" >
                    <div class="tab-content">
                        <ul class="task-list" id="sortable">
                            <?php foreach ($faq as $fq) { ?>

                                <li data-position="" data-faqid="<?php echo $fq['id']; ?>" >
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <?php if(APP_ID == 0 || $fq['app_id'] != 0) {?>
                                            <i style="left: 0px;" class="fa  pull-right fa-pencil edit_faq_data" data-course-id ="<?php echo $fq['course_id']; ?>" data-question="<?php echo $fq['question']; ?>" data-description="<?php echo $fq['description']; ?>" data-faq-id="<?php echo $fq['id']; ?>"   ></i>
                                        <?php } ?>
                                            <h4 class="panel-title">

                                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_<?php echo $fq['id']; ?>" aria-expanded="false">
                                                    <?php echo $fq['question']; ?>
                                                </a>
                                                <?php if(APP_ID == 0 || $fq['app_id'] != 0) {?>
                                                <a href="<?php echo AUTH_PANEL_URL . "course_product/course/delete_gfaq/" . $fq['id'] . "/" . $fq['course_id']; ?>" onclick="return confirm('Are you sure you want to delete?');" class="pull-right"><i style="position: unset;color:red;" class="fa fa-times edit_faq" ></i></a>
                                            <?php }?>
                                            </h4>
                                        </div>
                                        <div id="accordion1_<?php echo $fq['id']; ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                            <div class="panel-body">
                                                <?php echo $fq['description']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="form-group col-md-12 ">
                        <button id="faq_pos" class="btn btn-info btn-sm pull-right btn-xs" type="button">Update</button>
                        <button class="btn btn-warning" type="button" onclick="window.location.reload();">Cancel</button>
                    </div>
                </form>

            </div>
        <?php } ?>   
    </section>

</div>

<script src="<?= AUTH_ASSETS . "assets/ckeditor/ckeditor.js" ?>"></script>
<script src="<?= AUTH_ASSETS ?>js/jquery.validate.min.js" type="text/javascript"></script>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
<script>
        CKEDITOR.replace('terms');
        $("#terms_policy").submit( function(e) {
            var messageLength = CKEDITOR.instances['terms'].getData().replace(/<[^>]*>/gi, '').length;
            if( !messageLength ) {
               // alert( 'Please Enter  Ceo Message' );
               show_toast('error', 'Terms and Condition  are required !!', 'Please Add  Terms and Condition  ');
                e.preventDefault();
            }
        });
    </script>
    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
    
        <script src="$validation_js" type="text/javascript"></script>
        
                <script>
                $("#mobile").keypress(function(event) {
                  var keycode = event.which;
                  if (!(keycode >= 48 && keycode <= 57)) {
                      event.preventDefault();
                  }
              });
              </script>
    
<script>
        CKEDITOR.replace( 'policy ');
        $("#term_policy").submit( function(e) {
            var messageLength = CKEDITOR.instances['policy'].getData().replace(/<[^>]*>/gi, '').length;
            if( !messageLength ) {
               // alert( 'Please Enter  Ceo Message' );
               show_toast('error', 'Privacy and Policy  are required !!', 'Please Add  Privacy and Policy ');
                e.preventDefault();
            }
        });
    </script>
    <script>
        CKEDITOR.replace( 'refund_policy ');
        $("#termss_policy").submit( function(e) {
            var messageLength = CKEDITOR.instances['refund_policy'].getData().replace(/<[^>]*>/gi, '').length;
            if( !messageLength ) {
               // alert( 'Please Enter  Ceo Message' );
               show_toast('error', 'Refund Policy  are required !!', 'Please Add Refund Policy ');
                e.preventDefault();
            }
        });
    </script>
     <script>
        CKEDITOR.replace( 'about_us ');
        $("#term_polic").submit( function(e) {
            var messageLength = CKEDITOR.instances['about_us'].getData().replace(/<[^>]*>/gi, '').length;
            if( !messageLength ) {
               // alert( 'Please Enter  Ceo Message' );
               show_toast('error', 'About Us field is required !!', 'Please Add About Us ');
                e.preventDefault();
            }
        });
    </script>
     <script>
        CKEDITOR.replace( 'packages ');
        $("#packages").submit( function(e) {
            var messageLength = CKEDITOR.instances['packages'].getData().replace(/<[^>]*>/gi, '').length;
            if( !messageLength ) {
               // alert( 'Please Enter  Ceo Message' );
               show_toast('error', 'About Us field is required !!', 'Please Add About Us ');
                e.preventDefault();
            }
        });
    </script>
    

<script>
    $(function () {
        CKEDITOR.replace("terms");
        CKEDITOR.replace("policy");
        CKEDITOR.replace("refund_policy");
        CKEDITOR.replace("about_us");
        CKEDITOR.replace("contact_us");
         CKEDITOR.replace("packages");
    });
    

      $(".add_faq_button").click(function () {
        $('.add_faq_div').show("slow");
    });

    $(".edit_faq_data").click(function () {
        $('#faq_question').val($(this).data('question'));
        $('#faq_description').val($(this).data('description'));
        $('#faq_id').val($(this).data('faq-id'));
        $('.add_faq_div').show("slow");
    });

    $("#add_faq_form").on('submit', (function (e) {
        var question = $('#faq_question').val();
        var description = $('#faq_description').val();
        var course_id = $('#faq_course_id').val();
        var faq_id = $('#faq_id').val();
        e.preventDefault();
        $.ajax({
            url: "<?= AUTH_PANEL_URL ?>course_product/course/add_faq",
            type: "POST",
            dataType: 'json',
            data: {question: question, description: description, course_id: -1, faq_id: faq_id},
            success: function (data) {
                if (data.status == true) {
                    if (data.code == 1) {
                        show_toast('success', 'FAQ added to course successfully !!', 'FAQ Added ');
                    } else if (data.code == 2) {
                        show_toast('success', 'FAQ updated to course successfully !!', 'FAQ Updated ');
                    }

                    location.reload();
                } else if (data.status == false) {
                    //$(".faq_error").html(data.data_value);
                    show_toast('error', 'Both fields are required !!', 'FAQ Not Added ');
                }
            }
        });
    }));

     $("#faq_pos").click(function () {
        var count = 1;
        var array = {};
        $('#sortable li').each(function () {
            id = $(this).data('faqid');
            array[count] = id;
            count++;
        });
        jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>course_product/course/update_faq_order",
            method: 'post',
            dataType: 'json',
            data: array,
            success: function (data) {
                show_toast('success', 'Order of Faq in course saved successfully', 'Faq order');
            }
        });
    });

</script>

