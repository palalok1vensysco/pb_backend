<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
</style>
<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading custom-panel-heading text-white d-flex justify-content-between align-items-center" style="background: var(--color1)!important">
            Edit Genres
            <button class="btn btn-sm display_color pull-right f-600 " type="button"><a href = "javascript:history.back()" class="text-white">Back to previous page</a></button>
        </header>
        <div class="panel-body bg-white p-2">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                <?php if (isset($sub_category['id'])) { ?>
                    <input type="hidden" name="id" value="<?php echo $sub_category['id']; ?>">
                <?php } ?>
                <div class="form-group col-md-12">
                    <div class="form-group col-md-6">
                        <label for="title">Genres</label>
                        <input type="text" class="form-control" name = "title" id="title"  maxlength='100'  oninput="checkpricee(this)" placeholder="Enter Category" value="<?php if (isset($sub_category['title'])) {
                        echo $sub_category['title'];
                    } ?>">
                        <span class="custom-error"><?php echo form_error('title'); ?></span>
       <script>
             function checkpricee(input) {
                        if (input.value == 0) {
                          input.setCustomValidity('The  Genres value must not be zero.');
                        } else {
                          // input is fine -- reset the error message
                          input.setCustomValidity('');
                        }
                    }
                      
    </script>
                    </div>
                    <div class="form-group col-md-6">
                    <label>Background Image</label>
                       
                        <input type="file" accept="image/*" name = "bg_img" class="form-control input-xs" id="posterInputFile1" >
                          <?php if (($sub_category['thumbnail'])) { ?>
                            <img width="150" src="<?= $sub_category['thumbnail'] ?>" class="img-responsive" id="post_img">
                        <?php } ?>
                        <span id="postermsg" style="color: red;"></span>
                       
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="form-group col-md-6 popularCheckBox">
                        <?php
                       $checked =  $sub_category['is_popular'] == 1? "checked" : "";
                        ?>
                        <input type="checkbox"  id="is_popular" <?= $checked ?> name="is_popular" value="1"  class="" >
                        <label for="cat_name">Is Popular</label>
                        <span class="custom-error"><?php echo form_error('is_popular'); ?></span>
                       
                    </div>
                    <div class="form-group col-md-6">
                            <label for="cat_name">Status<span style="color:#ff0000">*</span></label>
                            <select class="form-control input-sm m-bot15 status" name="status" >
                            <option value="">Select Status</option>
                            <option <?php echo $sub_category['status'] == 0 ? "selected" : "";?> value="0">Enable</option>
                              <option <?php echo $sub_category['status'] == 1 ? "selected" : "";?> value="1">Disable</option>
                              
                            </select>
                            <span class="custom-error"><?php echo form_error('status'); ?></span>
                        </div>
                </div>
                 <div class="form-group col-md-12">
                <button type="submit" class="btn btn-sm display_color text-white f-600">Submit</button>
                <a href="<?= base_url('admin-panel/add-sub-category') ?>">
                    <button class="btn btn-sm display_color text-white f-600" type="button" >Cancel</button>
                </a>
            </div>
            </form>

        </div>
    </section>
</div>

<script src="https://unpkg.com/mathjs/lib/browser/math.js"></script>
<script>
     var _URL = window.URL || window.webkitURL;
          $("#posterInputFile1").change(function(e) {
                var file, img;
                    var n_width=720,n_height=420;
                if ((file = this.files[0])) {
                    img = new Image();
                    img.onload = function() {
                        var ratio = this.width/this.height;
                    var ratio1 = ratio.toFixed(1);
                    // alert(ratio1);
                     if(ratio1 != '1.7')
                     {
                         document.getElementById("postermsg").textContent="Please Enter aspect ratio size 720:420";
                         $("#posterInputFile1").val('');
                     }
                     else
                     {
                         document.getElementById("postermsg").textContent=" ";
                     }
                     
                    };
                    img.onerror = function() {
                        alert( "not a valid file: " + file.type);
                        $("#posterInputFile1").val('');
                    };
                    img.src = _URL.createObjectURL(file);

                }
            });
</script>