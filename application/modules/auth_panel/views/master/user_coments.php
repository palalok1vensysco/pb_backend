<?php $display = !empty($this->input->get("id"))?"block":"none"; ?>
<section class="panel" id="add_batch" style="display:<?=$display;?>">
    <header class="panel-heading">
  Comment List 
    </header>
    <div class="panel-body">
        <form role="form"   autocomplete="off" method="POST" enctype="multipart/form-data" id="addbanner">
            <div class="col-md-12 error bold alert-box">
                <?php echo validation_errors(); ?>

            </div>
            <div class="form-group col-md-4">
                <label for="name">Banner Type <span style="color:#ff0000">*</span></label>
                <select name="banner_type" class="form-control input-xs" id="btypeid">
                    <option value="">select type</option>
                    <option value="1" <?=(isset($banner['banner_type']) && $banner['banner_type'] == "1")?"selected":"";?>>Both app & web</option>
                     <option value="0" <?=(isset($banner['banner_type']) && $banner['banner_type'] == "0")?"selected":"";?> >Web</option>
                    <option value="2" <?=(isset($banner['banner_type']) && $banner['banner_type'] == "2")?"selected":"";?> >App</option>
                    <!-- <option value="3" <?=(isset($banner['banner_type']) && $banner['banner_type'] == "3")?"selected":"";?> >Login Banner</option>  -->
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Link Type <span style="color:#ff0000">*</span></label>
                <select class="form-control input-xs" name ="link_type" id="link_type" required>
                    <option value="">----select----</option>

                    <option value="1" <?=(isset($banner['link_type']) && $banner['link_type'] == "1")?"selected":"";?>>Video</option>
                    <option value="0" >No Link</option>

                    <!-- <option value="2" <?=(isset($banner['link_type']) && $banner['link_type'] == "2")?"selected":"";?>>Hyperlink</option> -->

                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Category Type<span style="color:#ff0000">*</span></label> 


            <?php if(!empty($banner['video_id'])) {?>

                <select class="form-control selectpicker" name="cate_type" id="cate_type" data-live-search="true" required=""> 
                        <option value="" >---select---</option>
                        <?php
                        foreach ($categories as $categories) {
                            //print_r($categories);
                            ?>
                           

                            <option value="<?= $categories['id'] ?>" <?= ($categories['id'] == $banner['cate_type']) ? 'selected' : '' ?> ><?= $categories['cat_name'] ?></option>
                        <?php } ?>
                </select> 
                 
        <?php }else {
         ?>

                <select class="form-control input-xs" name ="cate_type" id="">
                    <option value="">----select----</option>
                    <?php foreach ($categories as $cate) { ?>
                        <option value="<?= $cate['id'] ?>" <?=(isset($banner['link_type']) && $banner['link_type'] == "1")?"selected":"";?>><?= $cate['cat_name'] ?></option>
                   <?php } ?>
                </select>        

        <?php } ?>

            </div>


            
            <div class="clearfix"></div>
            <div class="form-group col-md-6">
                <label for="name">Banner Title<span style="color:#ff0000">*</span> </label>
                <input type="text" class="form-control input-xs" id="banner_title" name="banner_title" placeholder="Enter Batch Title"  maxlength="100"  value="<?=!empty($banner['banner_title'])?$banner['banner_title']:"";?>" required>
            </div>
            <div class="form-group col-md-6" id ="video"  <?php echo (isset($banner['link_type']) && $banner['link_type'] == '1') ? 'style="display:block;"' : 'style="display:none;"'?>>
                <label for="name">Video Name</label>
               
                <?php  if(!empty($banner['video_id'])){
        
                        $hideclass = "";
                        $hideclass1 = "show";
                         }
             else{ 
                $hideclass = "show";
                $hideclass1 = "hide";
             }?>
                 
                <!-- <select class="form-control input-xs videoid  <?php if(isset($hideclass)){echo $hideclass;}?>" id="video_id"   name="video_id" style="display: none;"> -->
                     <select class="form-control input-xs videoid " id="video_id"   name="video_id" style="display: block;">
                     <option value="">Select video</option>
                </select>
                <?php  if(!empty($banner['video_id'])){
                    ?>
                <input type="text" name="video_id_old" id="ss" class="form-control input-xs videoid1<?php if(isset($hideclass1)){echo $hideclass1;}?> hide" value="<?=!empty($banner['video_id'])?$banner['video_id']:"";?>"> 
            <?php }?>
            <?php //}?>
            </div>

            <div class="form-group col-md-6" id="link" <?php echo (isset($banner['link_type']) && $banner['link_type'] == '2') ? 'style="display:block;"' : 'style="display:none;"'?>>
                <label>Hyperlink</label>
                <input name="link" class="form-control input-xs" value="<?=!empty($banner['link'])?$banner['link']:"";?>">
            </div>
    
            <div class="form-group col-md-6 hide">
                <label for="name">Banner Description</label>
                <textarea type="text" class="form-control input-xs" id="banner_description" name="banner_description" required value=""><?=!empty($banner['banner_description'])?$banner['banner_description']:"";?></textarea>
            </div>

             <div class="form-group col-md-6">
                <label for="exampleInputFile">Banner Status<span style="color:#ff0000">*</span></label>
                <select name="status" class="form-control input-xs" id="statusid">
                    <!-- <option value="">select status</option> -->
                    <option value="1" <?=(isset($banner['status']) && $banner['status'] == "1")?"selected":"";?> >Active</option>
                    <option value="0" <?=(isset($banner['status']) && $banner['status'] == "0")?"selected":"";?> >In-Active</option>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="exampleInputFile">Banner Location <span style="color:#ff0000">*</span></label>
                <select class="form-control" name="banner_location" required>
                    <option value="">Select Banner Location</option>
                    <!-- <option <?php echo (isset($banner['banner_location']) && $banner['banner_location'] == 0) ? 'selected' : '' ?> value="0">Top Banner</option>
                    <option <?php echo (isset($banner['banner_location']) && $banner['banner_location'] == 1) ? 'selected' : '' ?> value="1">Bottom Banner</option>
                    <option <?php echo (isset($banner['banner_location']) && $banner['banner_location'] == 2) ? 'selected' : '' ?> value="2">Popup Banner</option> -->
                    <option <?php echo (isset($banner['banner_location']) && $banner['banner_location'] == 3) ? 'selected' : '' ?> value="3">Topper video</option>
                    <!-- <option <?php echo (isset($banner['banner_location']) && $banner['banner_location'] == 4) ? 'selected' : '' ?> value="4">Live video</option> -->
                </select>
            </div>

            <div class="form-group col-md-6 banner_file banner_img">
                <label for="exampleInputFile" class="banner_label">Banner Image <span style="color:#ff0000">*</span></label>
                 <p>               
                <strong>Image Type :</strong> jpg, jpeg, gif, png
                ,<strong>Width  :</strong> 1417 pixels
                , <strong>Height :</strong> 525 pixels
                </p>
                <input class="form-control input-xs" type="file" accept="image/*" name = "image" id="exampleInputFile" <?php if(empty($banner['banner_url'])){ echo "Required";}?>>
                <span id="msg" style="color: red;"></span>
                <?php if(!empty($banner)){ 
                    $banner_url = ($banner['banner_url'])?$banner['banner_url']:AUTH_ASSETS."img/no-image.png";
                    echo '<input type="hidden" name="id" value="'.$banner['id'].'">';
                    ?>
                <img src="<?= $banner_url;?>" width="200" height="150" class="img-responsive">
                <?php } ?>
                
            </div>
            <div class="form-group col-md-6 banner_file mobile_banner">
                <label for="exampleInputFile1" class="banner_label">Mobile Banner <span style="color:#ff0000">*</span></label>
                 <p>               
                <strong>Image Type :</strong> jpg, jpeg, gif, png
                ,<strong>Width  :</strong> 1417 pixels
                , <strong>Height :</strong> 748 pixels
                </p>
                <input class="form-control input-xs" type="file" accept="image/*" name = "image_mobile" id="exampleInputFile1" <?php if(empty($banner['banner_mobile'])){ echo "Required";}?>>
                <span id="exampleInputFile1_msg" style="color: red;"></span>
                <?php if(!empty($banner)){ 
                    $banner_mob = ($banner['banner_mobile'])?$banner['banner_mobile']:AUTH_ASSETS."img/no-image.png";
                    echo '<input type="hidden" name="id" value="'.$banner['id'].'">';
                    ?>
                <img src="<?= $banner_mob;?>" width="200" height="150" class="img-responsive">
                <?php } ?>
                
            </div>
            <div class="form-group col-md-6 banner_file banner_thumbnail">
                <label for="mobile_thumbnail" class="banner_label">Banner Thumbnail </label>
                 <p>               
                <strong>Image Type :</strong> jpg, jpeg, gif, png
                ,<strong>Width  :</strong> 288 pixels
                , <strong>Height :</strong> 320 pixels
                </p>
                <input class="form-control input-xs" type="file" accept="image/*" name = "mobile_thumbnail" id="mobile_thumbnail" <?php if(empty($banner['banner_thumbnail']))?>>
                <span id="msg" style="color: red;"></span>
                <?php if(!empty($banner)){ 
                    $banner_thumb = ($banner['banner_thumbnail'])?$banner['banner_thumbnail']:AUTH_ASSETS."img/no-image.png";
                    echo '<input type="hidden" name="id" value="'.$banner['id'].'">';
                    ?>
                <img src="<?= $banner_thumb;?>" width="200" height="150" class="img-responsive">
                <?php } ?>
                
            </div>
           <!--  <div class="form-group col-md-4" id ="video" <?php echo (isset($banner['banner_location']) && ($banner['banner_location'] == '3' || $banner['banner_location'] == '4')) ? 'style="display:block;"' : 'style="display:none;"'?>>
                <label>Video Id</label>
                <input name="video_id" class="form-control input-xs" value="<?=!empty($banner['video_id'])?$banner['video_id']:"";?>">
            </div> -->
           
            <div class="form-group col-md-12" >
                <button type="submit" class="btn btn-sm bold display_color text-white f-600 " id="update"><?=!empty($banner['id'])?"Update":"Add";?> Banner</button>
                 <!-- <button type="reset" class="btn btn-xs bold display_color" onClick="location.href = '<?php echo base_url("auth_panel/master/banner_management")?>'">Cancel</button> -->
                <!-- <button type="button" class="btn btn-xs display_color reset8">Clear</button> -->
            </div>
        </form>
    </div>
</section>
<section class="panel ">
    <header class="panel-heading ban-head-new displa_flex">
        <?php // echo strtoupper($page_title); ?> Comment List
        <div class="btn-hed-right ">
        <!-- <button class="btn btn-xs display_color dropdown_ttgl text-white" onClick="$('#add_batch').show('slow');" type="button">
            <i class="fa fa-plus mr-1"></i>Add Banner
        </button> -->
        <button class="btn btn-info btn-xs" type="button" title="Refresh App Home Screen" onclick="refreshBanner()">
            <i class="fa fa-refresh"></i>
        </button>
        </div>
    </header>
    <div class="panel-body">
        <div class="adv-table">
            <table  class="display table table-bordered table-striped" id="all-user-grid">
                <thead>
                    <tr>
                        <th>Serial No.</th>
                        <th>User Reaction </th>
                           <th>Movie Name</th>

                        <th>User Coments</th>

                          <th>Status</th>
                        <th>Action </th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th></th>
                        <th><input type="text" data-column="1"  class="search-input-text form-control input-xs"></th>
                        <th><input type="text" data-column="2"  class="search-input-text form-control input-xs"></th>
                        <th></th>
                         <th></th>
                          <th></th>
                          <!-- <th></th>
                         <th></th> -->
                        <!-- <th>
                            <select class="search-input-select form-control input-xs" data-column="2">
                                <option value="">All</option>
                                <option value="1">both app & web</option>
                                <option value="0">Web</option>
                                <option value="2">app</option>
                                
                            </select>
                        </th> -->
                        <!-- <th>
                            <select class="search-input-select form-control input-xs" data-column="3">
                                <option value="">All</option>
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
                            </select>
                        </th> -->
                        <!-- <th></th>
                        <th></th>
                        <th></th> -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>


<link rel="stylesheet" type="text/css" href="<?=AUTH_ASSETS?>css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="<?=AUTH_ASSETS?>js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js" defer></script>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   
    <script src="https://unpkg.com/mathjs/lib/browser/math.js"></script>
               <script>
                    var _URL = window.URL || window.webkitURL;
                        $("#exampleInputFile").change(function(e) {
                            var file, img;
                                var n_width=1417 ,n_height=525;
                              //  var n_width=1067,n_height=480;
                            if ((file = this.files[0])) {
                                img = new Image();
                                img.onload = function() {
                                    var ratio = this.width/this.height;
                                var ratio1 = ratio.toFixed(1);
                                // alert(ratio1);
                                 // if(ratio1 != '1.5')
                                if(ratio1 == '2.7')
                                 {
                                    document.getElementById("msg").textContent=" ";
                                     
                                 }
                                 else
                                 {
                                    document.getElementById("msg").textContent="Please Enter aspect ratio size 525:1417";
                                     $("#exampleInputFile").val('');
                                     
                                 }
                                 
                                };
                                img.onerror = function() {
                                    alert( "not a valid file: " + file.type);
                                };
                                img.src = _URL.createObjectURL(file);

                            }
                        });
                        $("#exampleInputFile1").change(function(e) {
                            var file, img;
                                var n_width=1417 ,n_height=748;
                              //  var n_width=1067,n_height=480;
                            if ((file = this.files[0])) {
                                img = new Image();
                                img.onload = function() {
                                    var ratio = this.width/this.height;
                                var ratio1 = ratio.toFixed(1);
                                //alert(ratio1);
                                 // if(ratio1 != '1.5')
                                if(ratio1 == '1.9')
                                 {
                                    document.getElementById("exampleInputFile1_msg").textContent=" ";
                                     
                                 }
                                 else
                                 {
                                    document.getElementById("exampleInputFile1_msg").textContent="Please Enter aspect ratio size 1417:748";
                                     $("#exampleInputFile1").val('');
                                     
                                 }
                                 
                                };
                                img.onerror = function() {
                                    alert( "not a valid file: " + file.type);
                                };
                                img.src = _URL.createObjectURL(file);

                            }
                        });

                    function get_videos_bycategoryid(){
                           var cate_type = $('select[name=cate_type]').val();
                           var video_id = $('#ss').val();
                           // alert(cate_type);
                           // alert(video_id);
                         var type_id = $('option:selected', this).val();
                         jQuery.ajax({
                            url: '<?= AUTH_PANEL_URL ?>master/fetch_video_id',
                             type: 'post',
                                data: {
                                   type_id: cate_type
                                },
                                dataType: 'json',
                            success: function(data) {
                               $('#video_id').find('option').not(':first').remove();
                               $.each(data, function(index, data) {
                                var selected ="";
                                if(data['id'] == video_id)
                                {
                                    selected = "selected";
                                }
                              $('#video_id').append('<option value="' + data['id'] + '"' + selected +'>' + data['title'] + '</option>');
                               });
                                 if(data != ''){         
                                 $('#video_id').prop('required', true);
                                   $(".videoid").show();
                                }else{
                                    $('#video_id').prop('required', false);
                                }
                            }
                         });
                       }
               </script> 

<script type="text/javascript" language="javascript" >
    jQuery(document).ready(function () {
          get_videos_bycategoryid();
        $('select[name=banner_location]').change(function(){
            var banner_location = $(this).val();
            if(banner_location==3 ||banner_location==4){
                $('#video').show();
            }else{
                $('#video').hide();
            }
        });
        $('#ss').attr('maxlength',4);
        

        $("select[name=cate_type]").change(function() { 
         var type_id = $('option:selected', this).val();
         jQuery.ajax({
            url: '<?= AUTH_PANEL_URL ?>master/fetch_video_id',
            type: 'post',
            data: {
               type_id: type_id
            },
            dataType: 'json',
            success: function(data) {

               // Remove options
               $('#video_id').find('option').not(':first').remove();
               // Add options
               $.each(data, function(index, data) {
                  $('#video_id').append('<option value="' + data['id'] + '">' + data['title'] + '</option>');
               });
               //apend required attribute if data comming
                 if(data != ''){         
                // $('#video_id').prop('required', true);
                  //$(".videoid").remove("show");
                   $(".videoid").show();
                }else{
                  //  $('#video_id').prop('required', false);
                  //  $(".videoid1").hide();
                }
               //apend required attribute if data comming end
            }
         });
      });
        // $('select[name=video_id]').select2({
        //     placeholder: 'Select an Course',
        //     theme: "classic",
        //     width: 'resolve',
        //     allowClear: true,
        //     ajax: {
        //         url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/course_search?filter=yes",
        //         dataType: 'json',
        //         delay: 2000,
        //         processResults: function (data) {
        //             return {
        //                 results: data
        //             };
        //         },
        //         cache: true
        //     }
        // });
        $("#video").keypress(function(event) {
                var keycode = event.which;
                if (!(keycode >= 48 && keycode <= 57)) {
                    event.preventDefault();
                }
            });
            
        

        $("body").on("change","select[name=banner_type]",function(){
            var bannerType = $(this).val();
            (bannerType == "1")?$("#select_course").show("slow"):$("#select_course").hide("slow");
        });
//        $("#add_batch").hide();
        
//        var all_batch_csv = "<?= AUTH_PANEL_URL ?>batch/get_request_for_batch_csv_download";
        var all_batch_all = "<?= AUTH_PANEL_URL ?>master/ajax_comment_list/";
        var table = 'all-user-grid';
        var dataTable = jQuery("#" + table).DataTable({
            "processing": true,
            "pageLength": 15,
            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
            "serverSide": true,
            "order":[[0,"desc"]],
            "columnDefs": [{ 
                                "orderable": false, 
                               
                                "targets":1,
                            },{ 
                "orderable": false, 
                "targets": 2,
            }],
            
            
                        
          
                           
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [-1,-2,-3,-4]},
            ],
            dom: 'Bfrtip',
                        "buttons":{
                        buttons:[

                            {extend:"csv" , text: 'Download CSV' ,className:"btn btn-default btn-xs",filename: "Banner",exportOptions: {
                                    columns: [0,1,2,3,4] // indexes of the columns that should be printed,
                                } 
                            },
                            {extend:"excel",text: 'Download Excel', className:"btn btn-default btn-xs",filename: "Banner",exportOptions: {
                                    columns: [0,1,2,3,4] // indexes of the columns that should be printed,
                                } 
                            },
                        ]
                    },
                    
            "ajax": {
                url: all_batch_all, // json datasource
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
    });

    function refreshBanner(){



        var table = $('#all-user-grid').DataTable();
         table.ajax.reload();
        // $.ajax({
        //     data:{"app_id":"<?=APP_ID?>"},
        //     url:"<?=AUTH_PANEL_URL;?>master/refresh_banner",
        //     type :"POST",
        //     async: false,
        //     dataType:"JSON",
        //     success:function(res){
        //         if(res.status)
        //             show_toast("success","App Home data have been updated successfully.","Refresh App Home Screen");    
        //         else
        //             show_toast("error","Something Went Wrong.","Refresh App Home Screen");
        //     }
        // })
    }
</script>
<script src="<?= AUTH_ASSETS ?>js/jquery.validate.min.js" type="text/javascript"></script>
<script>

    var form = $("#addbanner");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: { 
            banner_title: {
                required: true
            },
            banner_type: {
                required: true               
            },
            status: {
                required: true               
            },
            video_id: {
                //required: true
                              
            }
                 },
       

    });
    
    // $("#addbanner").submit(function() {   
    // if ($("#video_id").val() == "") {
    //            error = "1";
    //            show_toast('error', 'Please select video id', 'Error');
            
       


    //         }
    //          return false;
    //         });
    

     //**Akhilesh clear input field start***
             $(".reset8").click(function() { 
                  //  $('#master_type').empty();
                    $('#name_2').val('');  
                    $('#banner_title').val(''); 
                    $('#btypeid').prop('selectedIndex',0); 
                    $('#statusid').prop('selectedIndex',0);
                    document.getElementById("banner_description").value = "";
            });
      //**Akhilesh clear input field end***

  $("#link_type").change(function(){
    var type = $(this).val();
    if(type == 1){
        $("#video").show();
        $("#link").hide();
    }else if(type == 2){
        $("#link").show();
        $("#video").hide();
    }else if(type == 3){
        $("#link").show();
        $("#video").hide();
        $("#link").hide();
    }else if(type == 0){
        $("#video").hide();
        $("#link").show();
    }
  });
    
</script>
<script>
    
   $(function(){
    $("#banner_title_not").keypress(function (e) {
            if((e.charCode > 64 && e.charCode < 91) || (e.charCode > 96 && e.charCode < 123) || e.charCode == 32)
              {
           return true;
                 }
             else{
         return false;
                                         
              }
                    });
          });

   $('#btypeid').on('change', function() {
   let banner_type = $(this).val()
 //  alert(banner_type);
   $('.banner_img').show();
    $('.banner_thumbnail').show();
    $('.mobile_banner').show();
   if(banner_type == 0){
    $('.banner_img').show();
    $('.banner_thumbnail').show();
    $('.mobile_banner').hide();
   }else if(banner_type == 2){
    $('.banner_img').hide();
    $('.banner_thumbnail').hide();
    $('.mobile_banner').show();
   }else if(banner_type == 1){
    $('.banner_img').show();
    $('.banner_thumbnail').show();
    $('.mobile_banner').show();
   }
});
  </script>



                                 
<script type="text/javascript">
     
</script>