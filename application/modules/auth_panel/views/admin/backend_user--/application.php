<script src="https://devadmin.ott.videocrypt.in/auth_panel_assets/js/jquery.js"></script>


<section class="panel add_section" style="display: <?= $result ? "block" : "none" ?>">
    <header class="panel-heading">
        Application Manager
    </header>
    <div class="panel-body">
        <form method="POST" action="<?= AUTH_PANEL_URL . 'admin/application' ?>" id="candidateForm" name="appform" enctype="multipart/form-data"  autocomplete="off">
            <?php if(!empty($result)){?>
            <input type="hidden" name="id" value="<?= $result ? $result['id'] : "" ?>" >
            <?php } ?>
            <div class="col-md-12 error bold alert-box" >
                <?php echo validation_errors(); ?>
            </div>

            <div class="col-md-4 form-group">
                <label>App Name <span style="color:#ff0000">*</span></label>
                <input type="text" name="title" value="<?= $result ? $result['title'] : "" ?>" class="form-control input-xs" placeholder="Enter client Name">
            </div>
             <div class="col-md-4 form-group">
                <label>Email <span style="color:#ff0000">*</span></label>
                <input type="email" name="owner_email" value="<?= $result ? $result['owner_email'] : "" ?>" class="form-control input-xs" placeholder="Enter email ID"  required autocorrect="off" spellcheck="false" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" style="background-color:#ffffff" id="emailid">
            </div>
            <div class="col-md-4 form-group">
                <label>Mobile <span style="color:#ff0000">*</span></label>
                <input type="text" name="owner_mobile" value="<?= $result ? $result['owner_mobile'] : "" ?>" class="form-control input-xs" placeholder="Enter Mobile no" pattern="[789][0-9]{9}">
            </div>
            <div class="col-md-4 form-group ">
                <label>Choose State <span style="color:#ff0000">*</span></label>
                <select class="form-control input-xs" name="state">
                    <option value="">--Select State--</option>
                    <?php
                    $states=$this->db->get_where("states",array("country_id"=>101))->result_array();
                    foreach($states as $state){ ?>
                     <option value="<?php echo $state['name']?>" <?php echo $result?($result['state']==$state['name']?"selected":""):"" ?>><?php echo $state['name']?></option>
                     <?php } ?>                   
                </select>
            </div>

         
            <div class="col-md-4 form-group">
                <label for="exampleInputFile" class="file_title">Upload app logo as URL or image- </label><br/>
                <!-- <button style="margin-bottom: 4px;" class="btn-info btn-xs cover_video" type="button">Url</button> -->
                <label for="utl">URL</label>
                <input type="radio" class="cover_video" name="coverfilename" value="1" >
                <label for="file">Logo</label>
                <?php
                        if (isset($result['logo']) && $result['logo'] != "") {
                            echo '<img  class="col-md-12"   src="' . $result['logo'] . '" width="70px;" height="60px" >';
                        }
                        ?>
                <input type="radio" class="cover_video" name="coverfilename" value="2" checked >
                <input class="form-control input-xs" type="file" accept="image/*" name="owner_logo" id="owner_logo">
            </div>
            <div class="col-md-4 form-group">
                <label for="exampleInputFile" class="web_title">Upload Web logo as URL or image- </label><br/>
                <!-- <button style="margin-bottom: 4px;" class="btn-info btn-xs cover_video" type="button">Url</button> -->
                <label for="utl">URL</label>
                <input type="radio" class="cover_video" name="webfilename" value="1" >
                <label for="file">Logo</label>
                <?php
                        if (isset($result['web_logo']) && $result['web_logo'] != "") {
                            echo '<img  class="col-md-12"   src="' . $result['web_logo'] . '" width="70px;" height="60px" >';
                        }
                        ?>
                <input type="radio" class="cover_video" name="webfilename" value="2" checked >
                <input class="form-control input-xs" type="file" accept="image/*" name="web_logo" id="web_logo">
            </div>
            <div class="col-md-4 form-group ">
                <label>Choose Theme <span style="color:#ff0000">*</span></label>
                <select class="form-control input-xs" name="theme_id">
                    <option value="">--Select Theme--</option>
                     <option <?php 
                        echo (isset($result['selected_theme']) == 'theme1' && $result['selected_theme']=='theme1' )?"selected":"" ?> value="theme1">theme1</option> 
                     <option <?php echo (isset($result['selected_theme']) == 'theme2' && $result['selected_theme']=='theme2')?"selected":"" ?> value="theme2" >theme2</option>                   
                </select>
            </div>
            <div class="col-md-4 form-group">
                    <label>Project status <span style="color:#ff0000">*</span></label>
                    <select class="form-control input-xs" name="project_status">
                          <option value="">--Select Project Status--</option>
                     <option <?php 
                        echo (isset($result['project_status']) == 'Development' && $result['project_status']=='Development' )?"selected":"" ?> value="Development">Development</option> 
                     <option <?php echo (isset($result['project_status']) == 'Live' && $result['project_status']=='Live')?"selected":"" ?> value="Live" >Live</option>
                    </select>
                  
            </div>
            <div class="col-md-4 form-group">
                    <label>Website Domain <span style="color:#ff0000">*</span></label>
                    <input type="text" name="domain" value="<?= $result ? $result['domain'] : "" ?>" class="form-control input-xs" placeholder="Enter Domain">
            </div>
            <!-- <div class="col-md-4 form-group">
                    <label>Admin Domain <span style="color:#ff0000">*</span></label>
                    <input type="text" name="admin_domain" value="<?= $result ? $result['admin_domain'] : "" ?>" class="form-control input-xs" placeholder="Enter Admin Domain">
            </div>
             <div class="col-sm-4 form-group">
                <label>Font Color</label>
                    <input type="color" class="form-control input-xs" value="" name="font_color" required >
            </div>
            <div class="col-sm-4 form-group">
                <label>Backgroud Color</label>
                <input type="color" class="form-control input-xs" value="" name="bg_color" required >
            </div>
             <div class="col-sm-4 form-group">
                <label>Bg one Color</label>
                <input type="color" class="form-control input-xs" value="" name="bgone_color" required >
            </div> -->
              <div class="col-md-4 form-group">
                    <label>Admin Domain <span style="color:#ff0000">*</span></label>
                    <input type="text" name="admin_domain" value="<?= $result ? $result['admin_domain'] : "" ?>" class="form-control input-xs" placeholder="Enter Admin Domain">
            </div>
             <div class="col-md-4 form-group">
                <label>Font Color</label>
                    <input type="color" class="form-control input-xs" value="<?= $result ? $result['font_color'] : ""?>" name="font_color" required >
            </div>
            <div class="col-md-4 form-group">
                <label>Backgroud Color</label>
                <input type="color" class="form-control input-xs" value="<?= $result ?  $result['bg_color']: "" ?>" name="bg_color" required >
            </div>
             <div class="col-md-4 form-group">
                <label>Bg one Color</label>
                <input type="color" class="form-control input-xs" value="<?= $result ?  $result['bgone_color']: "" ?>" name="bgone_color" required >
            </div> 
            
            
            <div class="clearfix"></div>         
            <div class="row">
                <?php //if(!array_key_exists("id", $result)){?>
                <div class="col-md-6 form-group">
                    <label>Password <span style="color:#ff0000">*</span></label>
                    <input type="password" name="owner_pass" value="<?= $result ? $result['owner_password'] : "" ?>" class="form-control input-xs" placeholder="Enter Password">
                </div>
                <div class="col-md-6 form-group">
                    <label>Confirm Password <span style="color:#ff0000">*</span></label>
                    <input type="password" name="owner_confpass" value="<?= $result ? $result['owner_password'] : "" ?>" class="form-control input-xs" placeholder="Enter Conf Password">
                </div>
                <?php //} ?>
            </div>

            <div class="col-md-6 form-group">
                <label>Privacy Policy</label>              
                <textarea rows="1" cols="50" class="form-control input-xs editor editor_opt ckeditor " id="textarea_ppid" name="privacy_policy" ><?= !empty($result['privacy_policy'])? $result['privacy_policy'] : "" ?></textarea>
            </div>
            <div class="col-md-6 form-group">
                <label>Term & Conditions</label>
               
                <textarea rows="1" cols="50" class="form-control input-xs editor editor_opt ckeditor " name="terms_condition" ><?= !empty($result['term_and_policy'])? $result['term_and_policy'] : "" ?></textarea>
            </div>
            
            <div class="col-md-12 form-group">
                <label>Refund Policy</label>
               
                <textarea rows="1" cols="50" class="form-control input-xs editor editor_opt ckeditor " name="payment_privacy" ><?= !empty($result['payment_privacy'])? $result['payment_privacy'] : "" ?></textarea>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-xs btn-primary">Submit</button>
                <!-- <button type="button" class="btn btn-xs btn-warning" onclick="$('.add_section').hide('slow')">Cancel</button> -->
                <button type="button" class="btn btn-xs btn-warning" onclick="location.href='<?php echo AUTH_PANEL_URL;?>admin/application'">Cancel</button>
                <button type="button" class="btn btn-xs btn-success reset">Clear</button>
            </div>
        </form>
    </div>
</section>
<section class="panel">
    <header class="panel-heading">
        Application LIST(s)
        <a class="btn btn-xs btn-info pull-right" onclick="$('.add_section').show('slow')">Add New</a>
    </header>
    <div class="panel-body">
        <div class="adv-table">
            <table  class="display table table-bordered table-striped" id="backend-user-grid">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>APP ID </th>
                        <th>Name </th>
                        <th>Email </th>
                        <th>Mobile </th>
                       <!--  <th>Title</th> -->
                        <th>Domain</th>
                        <th>Plan</th>
                        <th>Status </th>
                        <th>Project Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                        <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
                        <th><input type="text" data-column="3"  class="search-input-text form-control"></th>
                        <!-- <th>
                            <select data-column="8"  class="form-control search-input-select">
                                <option value="">All</option>
                            </select>
                        </th> -->
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

<!-- <link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?=AUTH_ASSETS?>js/jquery.dataTables.js"></script> -->
<script src="<?= AUTH_ASSETS ?>assets/ckeditor/ckeditor.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
         <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" defer></script>

        <script src="https://devadmin.ott.videocrypt.in/auth_panel_assets/js/jquery.js"></script>
        <script src="https://devadmin.videocrypt.in/auth_panel_assets/js/jquery.min.js"></script>
        
        <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<!-- <script type="text/javascript">
$(document).ready(function () {
    $('form').attr('autocomplete', 'off');
$('input').attr('autocomplete', 'off');

        $('#candidateForm').validate({
            rules: {
                title: {
                    required: true,
                },
                domain: {
                    required: true,
                },
                 theme_id: {
                    required: true,
                },
                owner_pass: {
                    required: true,
                     minlength: 8,
                     maxlength:15
                },
                owner_confpass: {
                    required: true,
                    minlength: 8,
                    maxlength:15,
                    equalTo: '[name="owner_pass"]'
                },               
                owner_email: {
                    required: true,
                    email: true,
                    maxlength:40
                },

                owner_mobile: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength:12
                },
              
            },

            messages: {
                title: "<span style='color:red'>Enter your app name</span>",
                theme_id:{
                    required: "<span style='color:red'>Theme option is required</span>",
                },
                owner_email: {
                    required: "<span style='color:red'>Enter your Email</span>",
                    email: "<span style='color:red'>Please enter a valid email.</span>",
                },
                owner_pass:{
                    required: "<span style='color:red'>Enter your Password</span>",
                    minlength: "<span style='color:red'>Enter minimum 8 digit number</span>",
                },
                owner_confpass:{
                    required: "<span style='color:red'>your confirm Password is required</span>",
                    minlength: "<span style='color:red'>Enter minimum 8 digit number</span>",
                },
                domain:{
                    required: "<span style='color:red'>Domain is required</span>",
                },
                owner_mobile:  {
                    required: "<span style='color:red'>Enter Enter your contact number</span>",
                    number: "<span style='color:red'>Enter number only</span>",
                    minlength: "<span style='color:red'>Enter minimum 10 digit number</span>",
                    maxlength: "<span style='color:red'>Enter maximum 12 digit number</span>",
                },
                owner_logo1: { 
                    required: "<span style='color:red'>Upload your file</span>",
                    extension:"<span style='color:red'>Please select file with valid extension11(jpg,jpeg,gif,png only)</span>",
                },
            }
        });
        //validate file extension custom  method.
        jQuery.validator.addMethod("extension", function (value, element, param) {
            param = typeof param === "string" ? param.replace(/,/g, '|') : "jpg|jpeg|gif|png";
            return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
        }, jQuery.format("Please enter a value with a valid extension."));

});
</script> -->
<script type="text/javascript" language="javascript" >

            jQuery(document).ready(function () {
                var logo = "<?= array_key_exists("logo", $result)?$result['logo']:"";?>";
                if(logo.length > 0){
                    $(".file_title").text("Enter App Logo URL");
                    $("input[name=owner_logo]").attr('type', 'text');
                    $("input[name=owner_logo]").val(logo);
                }
            
                $('form')[0].reset();
                var table = 'backend-user-grid';
                var dataTable = jQuery("#" + table).DataTable({
                    "processing": true,
                    "pageLength": 10,
                    "serverSide": true,
                    "order": [[0, "desc"]],
                    "ajax": {
                        url: "<?= AUTH_PANEL_URL ?>admin/ajax_applications_list", // json datasource
                        type: "post", // method  , by default get
                        error: function () {  // error handling
                            jQuery("." + table + "-error").html("");
                            jQuery("#" + table + "_processing").css("display", "none");
                        }
                    }
                });

                jQuery("#" + table + "_filter").css("display", "none");
                bind_table_search(dataTable, table, 'keyup');
                bind_table_search(dataTable, table, 'change');
            });
          

            $(document).on('click', '.delete_content', function () {
                if (!confirm('Are you sure you want to delete?')) {
                    return false;
                }
                let selector = $(this);
                $.ajax({
                    type: 'POST',
                    url: "<?= AUTH_PANEL_URL ?>admin/ajax_delete_content",
                    dataType: 'json',
                    data: {
                        id: selector.data("id")
                    },
                    success: function (data) {
                        if (data.data == 1) {
                            selector.parent().parent().remove();
                            show_toast('success', 'Application Deleted', 'Successful');
                        } else {
                            show_toast('error', 'Operation Failed', 'Error');
                        }
                    }
                });
            });
                 
                
          /*  $(".cover_video").click(function () {
                if ($(this).text() == "Url") {//file
                    $(this).text("File");
                    $(".file_title").text("Upload App Logo");
                    $("#owner_logo").attr('type', 'file');
                } else {//url
                    $(this).text("Url");
                    $(".file_title").text("Enter App Logo URL");
                    $("#owner_logo").attr('type', 'text');
                    $("#owner_logo").val("<?= array_key_exists("logo", $result)?$result['logo']:"";?>");
                }
            });*/
          
            var radios = document.forms["appform"].elements["coverfilename"];
                for(var i = 0, max = radios.length; i < max; i++) {
                    radios[i].onclick = function() {
                        //alert(this.value);
                         if (this.value == "2") {//file
                            $(this).text("File");
                            $(".file_title").text("Upload App Logo");
                            $("#owner_logo").attr('type', 'file');
                        } else {//url
                            $(this).text("Url");
                            $(".file_title").text("Enter App Logo URL");
                            $("#owner_logo").attr('type', 'text');
                              $("#owner_logo").val("<?= array_key_exists("logo", $result)?$result['logo']:"";?>");
                        }
                    }
                }

            var radios = document.forms["appform"].elements["webfilename"];
                for(var i = 0, max = radios.length; i < max; i++) {
                    radios[i].onclick = function() {
                        //alert(this.value);
                         if (this.value == "2") {//file
                            $(this).text("File");
                            $(".web_title").text("Upload Web Logo");
                            $("#web_logo").attr('type', 'file');
                        } else {//url
                            $(this).text("Url");
                            $(".web_title").text("Enter Web Logo URL");
                            $("#web_logo").attr('type', 'text');
                              $("#web_logo").val("<?= array_key_exists("web_logo", $result)?$result['web_logo']:"";?>");
                        }
                    }
                }
           $(".reset").click(function() {
                $('#candidateForm').find('input:text, input:password, select')
                    .each(function () {
                        $(this).val('');
                    });   
                    $('#emailid').val('');  
                    $('#owner_logo').val('');   
                    CKEDITOR.instances[instance].setData('');     
            });
                
</script>
 <script>
          jQuery(document).ready(function(){
    jQuery('#owner_logo').each(function () {
        $this = jQuery(this);
        $this.on('change', function() {
            var fsize = $this[0].files[0].size,
                ftype = $this[0].files[0].type,
                fname = $this[0].files[0].name,
                fextension = fname.substring(fname.lastIndexOf('.')+1);
                 validExtensions = ["jpg","jpeg","gif","png"];
               // validExtensions = ["jpg","pdf","jpeg","gif","png","doc","docx","xls","xlsx","ppt","pptx","txt"];
            if ($.inArray(fextension, validExtensions) == -1){
                alert("Please select file with valid extension(jpg jpeg,gif,png only)");
                this.value = "";
                return false;
            }else{
                if(fsize > 20971520){/*1048576-1MB(You can change the size as you want)*/
                   alert("File size too large! Please upload less than 20MB");
                   this.value = "";
                   return false;
                }
                return true;
            }
        
        });
    });
});
      </script>