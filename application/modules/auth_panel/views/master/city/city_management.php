
    <section class="panel">
        <header class="panel-heading">
            Add City
        </header>
        <div class="panel-body">
            <form role="form" method="POST">
                <?php
                if (isset($result)) {
                    ?><input name="id" hidden="" value="<?= $result['id'] ?>"><?php
                }
                ?>
                <div class="form-group col-md-4">
                    <label for="class_name">Select Division</label>
                    <select  class="form-control input-sm" name="division_master_id" id="class_id">
                        <option>Select</option>
                        <?php foreach ($data as $class) {
                            ?>

                            <option  value="<?php echo $class['id']; ?>" <?php
                            if (isset($result) && $result['division_master_id'] == $class['id']) {
                                echo 'SELECTED';
                            }
                            ?>><?php echo $class['name']; ?></option>
                                 <?php } ?>
                    </select> 
                </div>
                <div class="form-group col-md-4">
                    <label for="class_name">Select District</label>
                    <select  class="form-control input-sm" name="district_master_id" id="district_master_id">
                        <option>Select</option>
                        <?php if($district){ foreach ($district as $class) {
                            ?>

                            <option  value="<?php echo $class['id']; ?>" <?php
                            if (isset($result) && $result['district_master_id'] == $class['id']) {
                                echo 'SELECTED';
                            }
                            ?>><?php echo $class['name']; ?></option>
                        <?php }} ?>
                    </select> 
                </div>

                <div class="form-group col-md-4">
                    <label>City Name</label>
                    <input type="text" required=""  class="form-control input-sm " name="name" placeholder="Enter City Name" value="<?php
                    if (isset($result)) {
                        echo $result['name'];
                    }
                    ?>">
                </div>

                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-sm bold btn-info">Add City</button>
                </div>
            </form>

        </div>
    </section>

<div class="clearfix"></div>



    <section class="panel">
        <header class="panel-heading">
            City(s) LIST
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>City</th>
                            <th>District</th>
                            <th>Division</th>
                            <th>Status </th>
                            <th>Created on</th>
                            <th>Action </th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th><input type="text" data-column="0"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="3"  class="search-input-text form-control"></th>
                            <th></th>
                            <th></th>
                            <th></th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>


<?php
$adminurl = AUTH_PANEL_URL;
$dragablejs = AUTH_ASSETS . 'js/draggable-portlet.js';
//if($page == 'android') { $device_type = 1; } elseif ($page == 'ios') { $device_type = 2; } elseif ($page == 'all') { $device_type = '0'; }
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/css" href='"AUTH_ASSETS"css/jquery.dataTables.css'>
<script type="text/javascript" charset="utf8" src='"AUTH_ASSETS"js/jquery.dataTables.js'></script>
               <script type="text/javascript" language="javascript" >

                   jQuery(document).ready(function() {
                       var table = 'all-user-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                            "pageLength": 15,
                            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
                            "serverSide": true,
                            "order":[[0,"desc"]],
                            "aoColumnDefs": [
                                {"bSortable": false, "aTargets": [0,4]},
                            ],
                           "ajax":{
                               url :"$adminurl"+"school/ajax_city_list/", // json datasource
                               type: "post",  // method  , by default get
                               error: function(){  // error handling
                                   jQuery("."+table+"-error").html("");
                                   jQuery("#"+table+"_processing").css("display","none");
                               }
                           }
                       } );
                       jQuery("#"+table+"_filter").css("display","none");
                       $('.search-input-text').on( 'keyup click', function () {   // for text boxes
                           var i =$(this).attr('data-column');  // getting column index
                           var v =$(this).val();  // getting search input value
                           dataTable.columns(i).search(v).draw();
                       } );
                        $('.search-input-select').on( 'change', function () {   // for select box
                            var i =$(this).attr('data-column');
                            var v =$(this).val();
                            dataTable.columns(i).search(v).draw();
                        } );

						$( function() {
					$( ".dpd1" ).datetimepicker();
					$( ".dpd2" ).datetimepicker();
					 }); // datepicker closed
                   } );
                   </script>
                  
                   
                    
                   <script>
                   jQuery(document).ready(function() {
                   $("#subject_id_new").on('change',function(){
                    var getValue=$(this).val();
                    var name = $('#subject_id_new :selected').text();
                    //alert(name);
                    $.ajax({
                      
                        url :"$adminurl"+"course_product/subject_topics/topic_list/?id="+getValue,
                        type: 'POST',
                        data: {id:getValue,name:name},
                        success: function(data)
                             {
                            $( "#display_topic" ).html( data );
                             
                                
                            }
                            
                        });
                  });
                } );
            
        function ajax_call(task_id,class_id,attr){
            $.ajax({
                type:'POST',
                url :"$adminurl"+"course_product/subject_topics/ajax_get_unit",
                data:{id:task_id,class_id:class_id},
                dataType:'json',
                success:function(data){
                    if(data.data==1){
                        $("#"+attr).html(data.result);
                        show_toast('success', 'Subjects Synced','Successful');
                    }else{
                        show_toast('error', 'Subjects Not Found','Error');
                    }
                },
                error: function(data){

                }
            });
        }
        
        $("#subject_ids").change(function(){
            var subject_id = $(this).val();
            var class_id = $('#class_id').val();
            if(!subject_id){
                show_toast('error', 'Please Select Valid Subject','InValid Subject');
                return false;
            }
            ajax_call(subject_id,class_id,'unit_id');
        });
        
        $("#unit_id").change(function(){
            var unit_id = $(this).val();
             var class_id = $('#class_id').val();
            if(!unit_id){
                show_toast('error', 'Please Select Valid Unit','InValid Unit');
                return false;
            }
            ajax_call(unit_id,class_id,'chapter_id');
        });
        
        $("#subject_id").change(function(){
            var chapter_id = $(this).val();
            if(!chapter_id){
                show_toast('error', 'Please Select Valid Chapter','InValid Chapter');
                return false;
            }
            $.ajax({
                type:'POST',
                url :"$adminurl"+"course_product/subject_topics/get_topic_from_subject/"+chapter_id+"?return=ajax_json",
                dataType:'json',
                success:function(data){
                    if(data.data==1){
                        $("#topic_id").html(data.result);
                        show_toast('success', 'Subject Synced','Successful');
                    }else{
                        show_toast('error', 'Subject Not Found','Error');
                    }
                },
                error: function(data){

                }
            });
        });

         $("#class_id").change(function(){
            $("#district_master_id").html("<option value=''>Select</option>");
            var class_id = $(this).val();
            if(!class_id){
                show_toast('error', 'Please Select Valid Class','InValid Class');
                return false;
            }
            $.ajax({
                type:'POST',
                url :"$adminurl"+"school/ajax_get_district",
                data:{id:class_id},
                dataType:'json',
                success:function(data){
                    if(data.data==1){
                        $("#district_master_id").html(data.result);
                        show_toast('success', 'District Synced','Successful');
                    }else{
                        show_toast('error', 'District Not Found','Error');
                    }
                },
                error: function(data){

                }
            });
        });     
        
        
         
               </script>
               

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
