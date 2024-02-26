<section class="panel add_section" style="display: <?= $result ? "block" : "none" ?>">
    <header class="panel-heading bg-dark text-white">
        Application Manager
    </header>
   
    
    <style>
        .dataTables_info{float:left}

        .dataTables_paginate{text-align: right;
                            padding-right: 15px;}

                div#backend-user-grid_paginate{
    cursor: default;
    color: #666 !important;
    border: 1px solid transparent;
    background: transparent;
    box-shadow: none;
}
.btn-info {
    background-color: #ff9700;
    border-color: #ff9700;
    color: #FFFFFF;
}
.passeye{
    position: relative;
}
.passeye i{
    position :absolute;
    right: 5px;
    top: 12px;

}
.passeye2{
    position: relative;
}
.passeye2 i{
    position :absolute;
    right: 5px;
    top: 12px;

}
a#backend-user-grid_previous:hover {    
    color: #333 !important;
    border: 1px solid #979797;
    background: linear-gradient(to bottom, white 0%, #dcdcdc 100%);
}

/* The message box is shown when the user clicks on the password field */
#message {
  display:none;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 20px;
  margin-top: 10px;
}

#message p {
  padding: 10px 35px;
  font-size: 18px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -35px;
/*  content: "✔";*/
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
  color: red;
}

.invalid:before {
  position: relative;
  left: -35px;
  content: "✖";
}

    </style>
    <div class="panel-body">
        <form method="POST" id="app" action="<?= AUTH_PANEL_URL . 'admin/application' ?>" id="candidateForm" name="appform" enctype="multipart/form-data"  autocomplete="off">
            <?php if(!empty($result)){?>
            <input type="hidden" name="id" value="<?= $result ? $result['id'] : "" ?>" >
            <?php } ?>
            <div class="col-md-12 error bold alert-box" >
                <?php echo validation_errors(); ?>
            </div>

            <div class="col-md-4 form-group">
                <label>App Name  <span style="color:#ff0000">*</span></label>
                <input type="text" name="title" value="<?= $result ? $result['title'] : "" ?>" class="form-control input-xs" maxlength="100" placeholder="Enter client Name "oninput="this.value = this.value.replace(/[^a-z,A-Z,0-9, ]/, '')">
            </div>
             <div class="col-md-4 form-group">
                <label>Email <span style="color:#ff0000">*</span></label>
                <input type="email" name="owner_email" value="<?= $result ? $result['owner_email'] : "" ?>" class="form-control input-xs" placeholder="Enter email ID"  required autocorrect="off" spellcheck="false" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" style="background-color:#ffffff" id="emailid">
            </div>
            <div class="col-md-4 form-group">
                <label>Mobile <span style="color:#ff0000">*</span></label>
                <input type="text" name="owner_mobile" value="<?= $result ? $result['owner_mobile'] : "" ?>" class="form-control input-xs" maxlength="12" placeholder="Enter Mobile no" pattern="[789][0-9]{9}"  oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" >
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
                <label for="utl">URL</label>
                <input type="radio" class="cover_video" name="webfilename" value="1" >
                <label for="file">Logo</label>
                <?php
                        if (isset($result['Web_logo']) && $result['Web_logo'] != "") {
                            echo '<img  class="col-md-12"   src="' . $result['Web_logo'] . '" width="70px;" height="60px" >';
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
                     //print_r($result);
                        echo (isset($result['project_status']) && $result['project_status']=='0' )?"selected":"";
                         ?>
                         value="0">Development</option> 
                       
                     <option <?php echo (isset($result['project_status']) == '1' && $result['project_status']=='1')?"selected":"" ?> value="1" >Live</option>
                    </select>
                  
            </div>
            <div class="col-md-4 form-group">
                    <label>Website Domain <span style="color:#ff0000">*</span></label>
                    <input type="text" name="domain" value="<?= $result ? $result['domain'] : "" ?>" class="form-control input-xs" placeholder="Enter Domain">
            </div>
            
              <div class="col-md-4 form-group">
                    <label>Admin Domain <span style="color:#ff0000">*</span></label>
                    <input type="text" name="admin_domain" value="<?= $result ? $result['admin_domain'] : "" ?>" class="form-control input-xs" maxlength="50" placeholder="Enter Admin Domain">
            </div>
             <div class="col-md-4 form-group">
                <label>Font Color</label>
                    <input type="color" class="form-control input-xs" value="<?= $result ? $result['font_color'] : ""?>" name="font_color" required >
            </div>
            <div class="col-md-4 form-group">
                <label>Background Color</label>
                <input type="color" class="form-control input-xs" value="<?= $result ?  $result['bg_color']: "" ?>" name="bg_color" required >
            </div>
             <div class="col-md-4 form-group">
                <label>Bg one Color</label>
                <input type="color" class="form-control input-xs" value="<?= $result ?  $result['bgone_color']: "" ?>" name="bgone_color" required >
            </div> 

             <div class="col-md-4 form-group">
                 <label>Currency<span style="color:#ff0000">*</span></label>
                   <select class="form-control input-xs" name="currency" >
                   <option value="INR">INR</option>
                    <?php
                    $currency=$this->db->get("currency")->result_array();
                    foreach($currency as $currency){ ?>
                     <option value="<?php echo $currency['code']?>" <?php echo $result?($result['currency']==$currency['code']?"selected":""):"" ?>><?php echo $currency['code']?></option>
                     <?php } ?>                   
                </select>
            </div> 

             <div class="col-md-4 form-group">
                 <label>Country Code <span style="color:#ff0000">*</span></label>
                <select class="form-control input-xs" name="countryCode" required>
                    <option value="99">IN (+91)</option>
                    <?php
                    //print($result['countryCode']);
                    $country=$this->db->get("country")->result_array();
                    foreach($country as $country){ ?>
                     <option value="<?php echo $country['id']?>" <?php echo $result?($result['countryCode']==$country['id']?"selected":""):"" ?>><?php echo $country['iso'].' (+'.$country['phonecode'].')';?></option>
                     <?php } ?>                   
                </select>
            </div> 
            
            
            <div class="clearfix"></div>         
            <div class="row">
      
            <div class="col-md-4 form-group">
              
                <label for="file">Login/register banner</label>             
               
                <input class="form-control input-xs" type="file" accept="image/*" name="login_banner" id="login_banner">
                  <?php
                        if (isset($result['login_banner']) && $result['login_banner'] != "") {
                            echo '<img  class="col-md-12"   src="' . $result['login_banner'] . '" width="70px;" height="60px" >';
                        }
                        ?>
            </div>

                <div class="col-md-4 form-group">
                    <label>Password <span style="color:#ff0000">*</span></label>
                    <div class="passeye">
                    <input type="password" name="owner_pass" id="owner_pass"  maxlength="16" value="<?= $result ? $result['owner_password'] : "" ?>" class="form-control input-xs" placeholder="Enter Password">
                    <i class="fa fa-eye" aria-hidden="true" id="togglePassword" ></i>
                   <div id="message">
                    <small>
                      <h4>Password must contain the following:</h4>
                      <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                      <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                      <p id="number" class="invalid">A <b>number</b></p>
                      <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                      </small>
                    </div>
                            
                    </div>
                      
                </div>
                <div class="col-md-4 form-group">
                    <label>Confirm Password <span style="color:#ff0000">*</span></label>
                    <div class="passeye2">
                    <input type="password" name="owner_confpass" id="owner_confpass"  maxlength="16" value="<?= $result ? $result['owner_password'] : "" ?>" class="form-control input-xs" placeholder="Enter Conf Password">
                    <i class="fa fa-eye" aria-hidden="true" id="togglePasswordd" style="margin-left: -30px; cursor: pointer;"></i> 
           <!-- <i class="fa fa-eye" aria-hidden="true" id="togglePassword" ></i> -->
                     
                    </div>
                </div>
                <?php //} ?>
            </div>

            <div class="col-md-6 form-group">
                <label>Privacy Policy</label>              
                <textarea rows="1" cols="50" class="form-control input-xs editor editor_opt ckeditor " id="textarea_ppid" name="privacy_policy" ><?= !empty($result['privacy_policy'])? $result['privacy_policy'] : "" ?></textarea>
            </div>
            <div class="col-md-6 form-group">
                <label>Terms & Conditions</label>
               
                <textarea rows="1" cols="50" class="form-control input-xs editor editor_opt ckeditor " name="terms_condition" ><?= !empty($result['term_and_policy'])? $result['term_and_policy'] : "" ?></textarea>
            </div>
            
            <div class="col-md-12 form-group">
                <label>Refund Policy</label>
               
                <textarea rows="1" cols="50" class="form-control input-xs editor editor_opt ckeditor " name="payment_privacy" ><?= !empty($result['payment_privacy'])? $result['payment_privacy'] : "" ?></textarea>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-xs btn-primary dropdown_ttgl text-white">Submit</button>
                <!-- <button type="button" class="btn btn-xs btn-warning" onclick="$('.add_section').hide('slow')">Cancel</button> -->
                <button type="button" class="btn btn-xs dropdown_ttgl text-white" onclick="location.href='<?php echo AUTH_PANEL_URL;?>admin/application'">Cancel</button>
                <!-- <button type="button" class="btn btn-xs dropdown_ttgl text-white">Clear</button> -->
            </div>
        </form>
    </div>
</section>
<section class="panel">
    <header class="panel-heading bg-dark text-white">
        Application LIST(s)
        <a class="btn btn-xs btn-info pull-right" onclick="$('.add_section').show('slow')">Add New</a>
    </header>
    <div class="panel-body">
        <div class="adv-table">
            <table  class="display table table-bordered table-striped" id="backend-user-grid">
                <thead>
                    <tr>
                        <th>Serial No</th>
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
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js" defer></script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>css/jquery.dataTables.css">

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
<script src="<?= AUTH_ASSETS ?>js/jquery.validate.min.js" type="text/javascript"></script>

<script>

    var value = $("#owner_pass").val();
        $.validator.addMethod("checklower", function(value) {
          return /[a-z]/.test(value);
        });
        $.validator.addMethod("checkupper", function(value) {
          return /[A-Z]/.test(value);
        });
        $.validator.addMethod("checkdigit", function(value) {
          return /[0-9]/.test(value);
        });
        $.validator.addMethod("pwcheck", function(value) {
          return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value);
        });

    var form = $("#app");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            title: {
                required: true
            },
            owner_email: {
                required: true               
            },
            owner_mobile: {
                required: true               
            },
            state: {
                required: true
                              
            },
            theme_id: {
                required: true
                              
            },
            project_status: {
                required: true
                              
            },
            domain: {
                required: true
                              
            },
            admin_domain: {
                required: true
                              
            },
            currency: {
                required: true
            },
            countryCode: {
                required: true
                              
            },
            owner_pass: {
                    required: true,
                     minlength: 8,
                     maxlength:15,
                     checklower: true,
                      checkupper: true,
                      checkdigit: true
                },
                owner_confpass: {
                    required: true,
                    minlength: 8,
                    maxlength:15,
                    equalTo: '[name="owner_pass"]'
                }
             
        },
          messages: {
    owner_pass: {
      pwcheck: "Password is not strong enough",
      checklower: "Need atleast 1 lowercase alphabet",
      checkupper: "Need atleast 1 uppercase alphabet",
      checkdigit: "Need atleast 1 digit"
    }
  },
    
    });


</script>

<script>
var myInput = document.getElementById("owner_pass");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
  document.getElementById("message").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
  document.getElementById("message").style.display = "none";
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
        $('#owner_pass').prop('required', false);
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
        $('#owner_pass').prop('required', true);
  }
  
  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
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
const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#owner_pass');

            togglePassword.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });
            const togglePasswordd = document.querySelector('#togglePasswordd');
            const passwordd = document.querySelector('#owner_confpass');

            togglePasswordd.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = passwordd.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordd.setAttribute('type', type);
                // toggle the eye slash icon
                this.classList.toggle('fa-eye-slash');
            });

</script>

