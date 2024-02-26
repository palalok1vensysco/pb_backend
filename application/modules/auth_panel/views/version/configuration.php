<?php // pre($socials)?>

<div class="col-lg-12 px-0 " >
   
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            S3 Account
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data"  id="ss3"action="<?= AUTH_PANEL_URL . "version_control/version/set_s3_bucket" ?>" >
                
                <div class="form-group col-md-4">
                    <label>Secret Key<span style="color:#ff0000">*</span> </label>
                    <input  class="form-control input-xs"  name="secret_key"   value="<?= isset($info->s3bucket_detail['secret_key']) ? $info->s3bucket_detail['secret_key'] : ""; ?>">
                    
                </div>
                <div class="form-group col-md-4">
                    <label>Access Key<span style="color:#ff0000">*</span> </label>
                    <input  class="form-control input-xs"  name="access_key"  value="<?= isset($info->s3bucket_detail['access_key']) ? $info->s3bucket_detail['access_key'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Bucket Name <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="bucket_key"  value="<?= isset($info->s3bucket_detail['bucket_key']) ? $info->s3bucket_detail['bucket_key'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>CloudFront <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="cloudfront"   value="<?= isset($info->s3bucket_detail['cloudfront']) ? $info->s3bucket_detail['cloudfront'] : ""; ?>">
                    <span class="error bold"></span>
                </div>

                <div class="form-group col-md-4">
                    <label>Region <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="region"   value="<?= isset($info->s3bucket_detail['region']) ? $info->s3bucket_detail['region'] : ""; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Cognito ID <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="congnito_id" value="<?= isset($info->s3bucket_detail['congnito_id']) ? $info->s3bucket_detail['congnito_id'] : ""; ?>">
                </div>

                <div class="form-group col-md-12">
                    <button class="btn btn-sm display_color text-white f-600" type="submit">save</button>
                    <button class="btn btn-sm display_color text-white f-600" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
        </div>
    </section>
  
    
 
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Email Credentials
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data"  id="email" action="<?= AUTH_PANEL_URL . "version_control/version/set_rzp_detail" ?>" >
                <input type="hidden"  name="meta_name" value="EMAIL_DETAIL">
                <div class="form-group col-md-4">
                    <label>Select Mode <span style="color:#ff0000">*</span></label>
                    <select class="form-control input-xs "  name="mode">
                        <option <?= (isset($info->email_detail['mode']) && $info->email_detail['mode'] == "prod") ? "selected" : "" ?> value="prod">Production</option>
                        <option <?= (isset($info->email_detail['mode']) && $info->email_detail['mode'] == "dev") ? "selected" : "" ?> value="dev">Development</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Host <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="host" value="<?= isset($info->email_detail) ? $info->email_detail['host'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Port <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="port" value="<?= isset($info->email_detail) ? $info->email_detail['port'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Email <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="email" value="<?= isset($info->email_detail) ? $info->email_detail['email'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Name <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="name" value="<?= isset($info->email_detail) ? $info->email_detail['name'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Username <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="username" value="<?= isset($info->email_detail) ? $info->email_detail['username'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Password <span style="color:#ff0000">*</span></label>
                    <input  class="form-control input-xs"  name="password" value="<?= isset($info->email_detail) ? $info->email_detail['password'] : ""; ?>">
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-sm display_color text-white f-600" type="submit">save</button>
                    <button class="btn btn-sm display_color text-white f-600" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
        </div>
    </section>
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Firebase Credentials
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="<?= AUTH_PANEL_URL . "version_control/version/set_rzp_detail" ?>" >
                <input type="hidden" name="meta_name" value="FIREBASE_DETAIL">               
                <div class="form-group col-md-6">
                    <label>Select Mode</label>
                    <select class="form-control input-xs "  name="mode">
                        <option <?= (isset($info->firebase_detail['mode']) && $info->firebase_detail['mode'] == "prod") ? "selected" : "" ?> value="prod">Production</option>
                        <option <?= (isset($info->firebase_detail['mode']) && $info->firebase_detail['mode'] == "dev") ? "selected" : "" ?> value="dev">Development</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>GSM KEY <span style="color:#ff0000">*</span></label>
                    <input type="text" name="gsm_key" class="form-control input-xs" placeholder="Enter GSM Key Here" value="<?= isset($info->gsm_key)?$info->gsm_key['GSM_KEY']:"";?>">
                </div>
                <div class="form-group col-md-6">
                    <label>FIREBASE API KEY</label>
                    <input type="text" name="FIREBASE_API_KEY" class="form-control input-xs" placeholder="Enter FIREBASE API KEY Key Here" value="<?= isset($info->gsm_key['FIREBASE_API_KEY'])?$info->gsm_key['FIREBASE_API_KEY']:"";?>">
                </div>


                
                <div class="form-group col-md-6">
                    <label>Database url</label> 
                    <input class="form-control input-xs editor " id="database" name="database" value="<?= isset($info->firebase_detail) ? $info->firebase_detail['database'] : ""; ?>">

                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-6">
                    <label>Json</label>
                    <textarea rows="10" cols="50" class="form-control input-xs editor " id="json" name="json" ><?= isset($info->firebase_detail) ? $info->firebase_detail['json'] : ""; ?></textarea>
                    <span class="error bold"></span>
                </div>
                <div class="form-group col-md-6">
                    <label>Service Account <?php echo isset($info->firebase_detail['service_account_file']) ? "<a href='".base_url().$info->firebase_detail['service_account_file']."' class='fa fa-download'></a>":"" ?></label>
                    <input type="file" class="form-control input-xs " id="service_account_file" name="service_account_file">
                    <span class="error bold"></span>
                </div>

                <div class="form-group col-md-12">
                    <button class="btn btn-sm display_color text-white f-600" type="submit">save</button>
                    <button class="btn btn-sm display_color text-white f-600" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
        </div>
    </section>
    
     <section class="panel">
        <header class="panel-heading bg-dark text-white">
            VideoCrypt Credentials
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data"  id="video" action="<?= AUTH_PANEL_URL . "version_control/version/set_rzp_detail" ?>" >
                <input type="hidden" name="meta_name" value="vc_key">               
                <div class="form-group col-md-6">
                    <label>VideoCrypt Secret Key <span style="color:#ff0000">*</span></label>
                    <input type="text" name="vc_secret_key" class="form-control input-xs" placeholder="Enter Secret Key Here" value="<?= isset($info->vc_key)?$info->vc_key['vc_secret_key']:"";?>">
                </div>
                <div class="form-group col-md-6">
                    <label>VideoCrypt Access Key <span style="color:#ff0000">*</span></label>
                    <input type="text" name="vc_access_key" class="form-control input-xs" placeholder="Enter FIREBASE API KEY Key Here" value="<?= isset($info->vc_key['vc_access_key'])?$info->vc_key['vc_access_key']:"";?>">
                </div>

                <div class="form-group col-md-12">
                    <button class="btn btn-sm display_color text-white f-600" type="submit">save</button>
                    <button class="btn btn-sm display_color text-white f-600" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
        </div>
    </section>
    <!-- shafre -->
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Social Media Link <?php print_r($info->SOCIAL_MEDIA);  ?>
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data"  id="video" action="<?= AUTH_PANEL_URL . "version_control/version/social_link" ?>" >                                
                <div class="form-group col-md-6">
                    <label>Facebook<span style="color:#ff0000">*</span></label>
                    <input type="text" name="facebook_link" class="form-control input-xs" placeholder="Enter Facebook Link" value="<?= isset($info->SOCIAL_MEDIA['facebook_link'])?$info->SOCIAL_MEDIA['facebook_link']:"";?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Instagram<span style="color:#ff0000">*</span></label>
                    <input type="text" name="instagram_link" class="form-control input-xs" placeholder="Enter Instagram Link" value="<?= isset($info->SOCIAL_MEDIA['instagram_link'])?$info->SOCIAL_MEDIA['instagram_link']:"";?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Youtube<span style="color:#ff0000">*</span></label>
                    <input type="text" name="youtube_link" class="form-control input-xs" placeholder="Enter Youtube Link" value="<?= isset($info->SOCIAL_MEDIA['youtube_link'])?$info->SOCIAL_MEDIA['youtube_link']:"";?>">
                </div> 
                <div class="form-group col-md-6">
                    <label>Twitter<span style="color:#ff0000">*</span></label>
                    <input type="text" name="twitter_link" class="form-control input-xs" placeholder="Enter Twitter Link" value="<?= isset($info->SOCIAL_MEDIA['twitter_link'])?$info->SOCIAL_MEDIA['twitter_link']:"";?>">
                </div>               
                <div class="form-group col-md-6">
                    <label>Telegram<span style="color:#ff0000">*</span></label>
                    <input type="text" name="telegram_link" class="form-control input-xs" placeholder="Enter Telegram link Here" value="<?= isset($info->SOCIAL_MEDIA['telegram_link'])?$info->SOCIAL_MEDIA['telegram_link']:"";?>">
                </div>             
                <div class="form-group col-md-12">
                    <button class="btn btn-sm display_color text-white f-600" type="submit">save</button>
                    <button class="btn btn-sm display_color text-white f-600" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
        </div>
    </section>
    <section class="panel hide">
        <header class="panel-heading bg-dark text-white">
            Deep Linking 
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="<?= AUTH_PANEL_URL . "version_control/version/set_rzp_detail" ?>" >
                <input type="hidden" name="meta_name" value="DEEPLINKING_DETAIL">   
                
                <div class="form-group col-md-6">
                    <label>Domain URI Prefix</label>
                    <input type="text" name="domainUriPrefix" class="form-control input-xs" placeholder="Enter DomainUriPrefix Here" value="<?= isset($info->deep['domainUriPrefix'])?$info->deep['domainUriPrefix']:"";?>">
                </div>            
                <div class="form-group col-md-6">
                    <label>Android package name</label>
                    <input type="text" name="android_package" class="form-control input-xs" placeholder="Enter Android Package Here" value="<?= isset($info->deep['android_package'])?$info->deep['android_package']:"";?>">
                </div>
                <div class="form-group col-md-6">
                    <label>iOS Bundle ID</label>
                    <input type="text" name="bundel_id" class="form-control input-xs" placeholder="Enter Bundle ID Here" value="<?= isset($info->deep['bundel_id'])?$info->deep['bundel_id']:"";?>">
                </div>

                <div class="form-group col-md-12">
                    <button class="btn btn-sm display_color text-white f-600" type="submit">save</button>
                    <button class="btn btn-sm display_color text-white f-600" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
        </div>
    </section>
    <section class="panel hide"> 
        <header class="panel-heading bg-dark text-white">
            Google 
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="<?= AUTH_PANEL_URL . "version_control/version/set_rzp_detail" ?>" >
                <input type="hidden" name="meta_name" value="GOOGLE_DETAIL">   
                
                <div class="form-group col-md-6">
                    <label>Google Client Id</label>
                    <input type="text" name="client_id" class="form-control input-xs" placeholder="Enter Client Id" value="<?= isset($info->GOOGLE_DETAIL['client_id'])?$info->GOOGLE_DETAIL['client_id']:"";?>">
                </div>            
                
                <div class="form-group col-md-12">
                    <button class="btn btn-sm display_color text-white f-600" type="submit">save</button>
                    <button class="btn btn-sm display_color text-white f-600" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
        </div>
    </section>
   
    <section class="panel hide">
        <header class="panel-heading bg-dark text-white">
            Maintenance Break
        </header>
        <div class="panel-body">
            <form method="POST" action="<?= AUTH_PANEL_URL . "version_control/version/set_maintenance_break" ?>" role="form">
                <div class="form-group col-md-12">
                    <label>Maintenance Break</label>
                    <label for="exampleInputPassword1">Date Range</label>
                    <div data-date-format="mm/dd/yyyy" data-date="" class="input-group input-large">
                        <input type="text" name="break_from" autocomplete="off" class="form-control dpd1 input-xs " value="<?= $info->break_from ? date("Y-m-d H:i", $info->break_from) : "" ?>">
                        <span style="color:red"></span>
                        <span class="input-group-addon">To</span>
                        <input type="text" name="break_to" autocomplete="off" class="form-control dpd2 input-xs " value="<?= $info->break_to ? date("Y-m-d H:i", $info->break_to) : "" ?>">
                        <span style="color:red"></span>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-sm display_color text-white f-600" type="submit">save</button>
                    <button class="btn btn-sm display_color text-white f-600" type="reset">Cancel</button>
                </div>
            </form>
        </div>
    </section>


    <section class="panel" hidden>
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
                <button class="btn btn-sm display_color text-white f-600" type="button" onclick="window.location.reload();">Cancel</button>
                <button class="btn btn-sm display_color text-white f-600" name="faq_submit"  type="submit"  >Submit</button>
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
                                                <a href="<?php echo AUTH_PANEL_URL . "course_product/course/delete_faq/" . $fq['id'] . "/" . $fq['course_id']; ?>" onclick="return confirm('Are you sure you want to delete?');" class="pull-right"><i style="position: unset;color:red;" class="fa fa-times edit_faq" ></i></a>
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
                        <button id="faq_pos" class="btn btn-sm display_color text-white f-600" type="button">Update</button>
                        <button class="btn btn-sm display_color text-white f-600" type="button" onclick="window.location.reload();">Cancel</button>
                    </div>
                </form>

            </div>
        <?php } ?>   
    </section>
</div>

<script src="<?= AUTH_ASSETS . "assets/ckeditor/ckeditor.js" ?>"></script>
<script src="<?= AUTH_ASSETS ?>js/jquery.validate.min.js" type="text/javascript"></script>
<script>

    var form = $("#ss3");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            secret_key: {
                required: true
            },
            access_key: {
                required: true               
            },
            bucket_key: {
                required: true               
            },
            cloudfront: {
                required: true
                              
            },
            region: {
                required: true
                              
            },
            congnito_id: {
                required: true
                              
            }
             
        },
    
    });
    var form = $("#zoom");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            secret_key: {
                required: true
            },
            access_key: {
                required: true               
            },
            zoom_email_id: {
                required: true               
            },
        },
    });
    var form = $("#rozar");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            mode: {
                required: true
            },
            is_split: {
                required: true               
            },
            key: {
                required: true               
            },
            secret: {
                required: true               
            },
        },
    });
    var form = $("#payu");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            mode: {
                required: true
            },
            key: {
                required: true               
            },
            secret: {
                required: true               
            },
        },
    });
    var form = $("#cca");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            mode: {
                required: true
            },
            key: {
                required: true               
            },
            secret: {
                required: true               
            },
        },
    });
    var form = $("#pay");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            mode: {
                required: true
            },
            key: {
                required: true               
            },
            secret: {
                required: true               
            },
        },
    });
    var form = $("#insta");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            mode: {
                required: true
            },
            key: {
                required: true               
            },
            secret: {
                required: true               
            },
        },
    });
    var form = $("#paytm");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            mode: {
                required: true
            },
            key: {
                required: true               
            },
            secret: {
                required: true               
            },
        },
    });
    var form = $("#cash");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            mode: {
                required: true
            },
            api_id: {
                required: true               
            },
            secret_key: {
                required: true               
            },
        },
    });
    var form = $("#email");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            mode: {
                required: true
            },
            host: {
                required: true               
            },
            port: {
                required: true               
            },
            email: {
                required: true               
            },
            name: {
                required: true               
            },
            username: {
                required: true               
            },
            password: {
                required: true               
            },
        },
    });
    // var form = $("#video");
    // form.validate({
    //     errorPlacement: function errorPlacement(error, element) {
    //         element.after(error);
    //     },
    //     rules: { 
    //         vc_secret_key: {
    //             required: true
    //         },
    //         vc_access_key: {
    //             required: true               
    //         },
          
          
    //     },
    // });
    </script>
    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
    <script>
    var form = $("#ceo_message_form");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            ceo_message_english  : {
                required: true
            },
            ceo_message_english:{
                ckrequired: true

            }
           
        },
    });
    </script>

    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
<script>
        CKEDITOR.replace( 'ceo_message_english' );
        $("#ceo_message_form").submit( function(e) {
            var messageLength = CKEDITOR.instances['ceo_message_english'].getData().replace(/<[^>]*>/gi, '').length;
            if( !messageLength ) {
               // alert( 'Please Enter  Ceo Message' );
               show_toast('error', 'Ceo Message are required !!', 'Please Add Ceo Message ');
                e.preventDefault();
            }
        });
    </script>
<script>
    $(function () {
        CKEDITOR.replace("ceo_message_english");
        CKEDITOR.replace("ceo_message_hindi");
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

