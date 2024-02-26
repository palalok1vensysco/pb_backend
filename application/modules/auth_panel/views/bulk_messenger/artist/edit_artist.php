<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
.text-black{
    color:#444;
}
</style>
<div>
   <div class="col-lg-12">
      <section class="panel">
         <header class="panel-heading  text-white bg-dark" >
            Edit Artist
         </header>
         <div class="panel-body bg-white">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
               <?php if (isset($artist['id'])) {?>
                  <input type="hidden" name="id" value="<?php echo $artist['id']; ?>">
                  <input type="hidden" name="profile_image" value="<?php echo $artist['profile_image']; ?>">
               <?php }?>
               <div class="row">
                <div class="form-group col-md-12">
                        <label for="artists_type_id">Atist Type<span style="color:#ff0000">*</span></label>
                        <select required class="form-control" name="artists_type_id" id="artists_type_id">
                            <option value="">select artist type</option>
                            <?php
                                if(!empty($artists_types)){
                                    foreach ($artists_types as $key => $artists_type) {
                            ?>
                            <option <?php if($artist['artists_type_id'] == $artists_type['id']){ echo "selected"; }?> value="<?php echo $artists_type['id'];?>"><?php echo $artists_type['title'];?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                        <span class="custom-error"><?php echo form_error('artists_type_id'); ?></span>
                    </div>
               <div class="form-group col-md-12">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" name = "name" id="name" placeholder="Enter Name"   value="<?php if (isset($artist['name'])) {echo $artist['name'];}?>" maxlength="25">
                  <span class="custom-error"><?php echo form_error('name'); ?></span>
               </div>
               <div class="form-group col-md-12">
                        <label for="cat_name">Status<span style="color:#ff0000">*</span></label>
                        <select class="form-control input-sm m-bot15 status" name="status" >
                        <option value="">Select Status</option>
                        <option <?php if($artist['status'] == 0){ echo "selected"; }?> value="0">Enable</option>
                          <option <?php if($artist['status'] == 1){ echo "selected"; }?> value="1">Disable</option>
                          
                        </select>
                        <span class="custom-error"><?php echo form_error('status'); ?></span>
               </div>

                
                   <div class="form-group col-md-12">
                      <?php if (isset($artist['profile_image'])) {?>
                         <img class="img-thumbnail" src="<?php echo $artist['profile_image']; ?>" width="150px" height="180px">
                      <?php }?>
                   </div>
                  <div class="form-group col-md-12" >
                      <label for="addimage">Add Profile Image</label>
                      <input type="file" class="form-control"  name = "profile_image" id="addimage" accept=".jpg,.png,.jpeg">
                      <small> 
                           <p>               
                                       
                    <strong>Image Type :</strong> jpg, jpeg, gif, png
                    ,<strong>Width  :</strong> 540 pixels
                    , <strong>Height :</strong> 720 pixels
                       </p>
                    </small>
                      <span class="custom-error"><?php echo form_error('profile_image'); ?></span>
                      <span id="msg" style="color: red;"></span>
                   </div>
                   
            </div>
            

               <button type="submit" class="btn btn-sm display_color text-white f-600">Submit</button>
               <a href="<?=base_url('admin-panel/artist-list')?>" class="text-black">
                        <button class="btn btn-sm display_color clr_green text-white f-600"  type="button" >Cancel</button>
                    </a>
            </form>
         </div>
      </section>
   </div>
   <div class="clearfix"></div>
</div>
<script src="https://unpkg.com/mathjs/lib/browser/math.js"></script>
               <script>
                    var _URL = window.URL || window.webkitURL;
                        $("#addimage").change(function(e) {
                            var file, img;
                                var n_width=540,n_height=720;
                            if ((file = this.files[0])) {
                                img = new Image();
                                img.onload = function() {                        
                                    var ratio = this.width/this.height;
                                var ratio1 = ratio.toFixed(1);
                               //  alert(ratio1);
                                 if(ratio1 != '0.8')
                                 {
                                     document.getElementById("msg").textContent="Please Enter aspect ratio size 540:720";
                                     $("#addimage").val('');
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
<script>
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
                                 </script>
                                 <script>
                                     $(function(){
                                       $("#title").keypress(function (e) {
                                          if((e.charCode > 64 && e.charCode < 91) || (e.charCode > 96 && e.charCode < 123) || e.charCode == 32)
                                             {
                                                return true;
                                             }
                                             else{
                                                return false;
                                         
                                             }
                                          
                                       });
                                    });
                                 </script>

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
