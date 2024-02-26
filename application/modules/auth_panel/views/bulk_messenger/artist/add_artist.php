<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
</style>
<div>
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading bg-dark text-white">
                Add Artist
                <a href="<?= AUTH_PANEL_URL . 'Artist/artist/artist_list'; ?>"><button class="btn bold btn-xs pull-right display_color text-white f-600">Back</button></a>
                <!-- <button class="btn bold btn-xs pull-right display_color" onclick="">Back</button> -->
            </header>
            <div class="panel-body bg-white">
                <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="artists_type_id">Atist Type<span style="color:#ff0000">*</span></label>
                        <select required class="form-control" name="artists_type_id" id="artists_type_id">
                            <option value="">select artist type</option>
                            <?php
                                if(!empty($artists_types)){
                                    foreach ($artists_types as $key => $artists_type) {
                            ?>
                            <option value="<?php echo $artists_type['id'];?>"><?php echo $artists_type['title'];?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                        <span class="custom-error"><?php echo form_error('artists_type_id'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Name<span style="color:#ff0000">*</span></label>
                        <input type="name" class="form-control" name = "name" id="name" placeholder="Enter Name" value="<?php echo set_value('name'); ?>"   maxlength="25" required="">
                        <span class="custom-error"><?php echo form_error('name'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="cat_name">Status<span style="color:#ff0000">*</span></label>
                        <select class="form-control input-sm m-bot15 status" name="status" >
                        <option value="">Select Status</option>
                        <option value="0">Enable</option>
                          <option value="1">Disable</option>
                          
                        </select>
                        <span class="custom-error"><?php echo form_error('status'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="addimage">Add Profile Image <span style="color:#ff0000">*</span></label>
                          <p>               
                            <strong>Image Type :</strong> jpg, jpeg, gif, png
                            ,<strong>Width  :</strong> 540 pixels
                            , <strong>Height :</strong> 720 pixels
                            </p>
                        <input type="file"  name = "profile_image" id="addimage2" accept=".jpg,.png,.jpeg" required>
                        <!-- <small> Image Size Pixel Ratio (540:720)</small><br> -->
                        <span id="msg" style="color: red;"></span>
                        <span class="custom-error"><?php echo form_error('profile_image'); ?></span>
                        <span id="msg" style="color: red;"></span>
                    </div>
                   
                    <button type="submit" class="btn btn-sm display_color text-white f-600">Submit</button>
                </form>
            </div>
        </section>
    </div>
    <div class="clearfix"></div>
</div>


<script src="https://unpkg.com/mathjs/lib/browser/math.js"></script>
               <script>
                    var _URL = window.URL || window.webkitURL;
                        $("#addimage2").change(function(e) {
                            var file, img;
                                var n_width=540,n_height=720;
                            if ((file = this.files[0])) {
                                img = new Image();
                                img.onload = function() {                        
                                    var ratio = this.width/this.height;
                                var ratio1 = ratio.toFixed(1);
                                // alert(ratio1);
                                 if(ratio1 != '0.8')
                                 {
                                     document.getElementById("msg").textContent="Please Enter aspect ratio size 540:720";
                                     $("#addimage2").val('');
                                 }
                                 else
                                 {
                                     document.getElementById("msg").textContent=" ";
                                 }
                                 
                                };
                                img.onerror = function() {
                                    alert( "not a valid file: " + file.type);
                                };
                                img.src = _URL.createObjectURL(file);
                            }
                        });
               </script> 

<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charcssset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >
               $(function(){
                $("#name").keypress(function (e) {
                   if((e.charCode > 64 && e.charCode < 91) || (e.charCode > 96 && e.charCode < 123) || e.charCode == 32)
                      {
                         return true;
                      }
                      else{
                         return false;
                  
                      }
                   
                });
             });

						$( function() {
					$( ".dpd1" ).datetimepicker();
					$( ".dpd2" ).datetimepicker();
					 }); // datepicker closed
               </script>
               <script src="https://unpkg.com/mathjs/lib/browser/math.js"></script>
               <script>
                    var _URL = window.URL || window.webkitURL;

                        $("#addimage1").change(function(e) {
                            var file, img;
                                var n_width=1920,n_height=822.86;

                            if ((file = this.files[0])) {
                                img = new Image();

                                img.onload = function() {
                                 //   alert(this.width + " " + this.height);
                        
                                    var ratio = this.width/this.height;
                               
                                var ratio1 = ratio.toFixed(3);
                                // alert(ratio1);
                                 if(ratio1 != '0.667')
                                 {
                                    //alert("Please Enter aspect ratio size");
                                     document.getElementById("msg").textContent="Please Enter aspect ratio size 9:16";
                                 }
                                 else
                                 {
                                     document.getElementById("msg").textContent=" ";
                                 }
                                 
                                };
                                img.onerror = function() {
                                    alert( "not a valid file: " + file.type);
                                };
                                img.src = _URL.createObjectURL(file);


                            }

                        });
               </script>
              

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
