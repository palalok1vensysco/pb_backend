<style>
    .panel-heading {
        border-bottom: 1px solid transparent;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        padding: 10px;
        background: #e9e9e9 none repeat scroll 0 0;
    }
</style>
<?php
$error = validation_errors();
$display = "display:none;";
if (!empty($error)) {
    $display = "";
}
?>
<div class="col-lg-6 add_file_element" style="<?php echo $display; ?>">
    <section class="panel">
        <header class="panel-heading custom-panel-heading">
            ADD MOBILE MENU
        </header>
        <div class="panel-body custom-panel-body">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
                <div class=" col-md-12">
                     <div class="form-group col-md-4">
                        <label>Menu Categorise BY</label>
                        <select class="form-control selectpicker" name="menu_type_category" id="menu_type_category" data-live-search="true" required=""> 
                            <option value="" >---select---</option>
                            <?php
                            foreach ($menu_category as $menu_categories) {
                                ?>
                                <option value="<?= $menu_categories['menu_code'] ?>" <?= (set_value('menu_type_category') == $menu_categories['menu_code'] ? 'selected' : '') ?>><?= $menu_categories['menu_name'] ?></option>
                            <?php } ?>
                        </select> 
                    </div>
                    <div class="form-group col-md-4">
                        <label>Menu Type</label>
                        <select class="form-control selectpicker" name="menu_type_id" id="menu_type_id" data-live-search="true" required=""> 
                            <option value="" >---select---</option>
                            <?php
                            foreach ($menu_type as $menu) {
                                ?>
                                <option value="<?= $menu['id'] ?>" <?= (set_value('menu_type_id') == $menu['id'] ? 'selected' : '') ?>><?= $menu['category_name'] ?></option>
                            <?php } ?>
                        </select> 
                    </div>
                    <div class="form-group col-md-4">
                        <label for="menu_title">Title</label>
                        <input type="text" class="form-control" name = "menu_title" id="type" placeholder="Enter Menu Title">
                        <span class="custom-error"><?php echo form_error('menu_title'); ?></span>
                    </div>
                </div>
                <button type="submit" class="btn btn-info btn-sm">Submit</button>
                <button class="btn btn-danger btn-sm" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>
            </form>
        </div>
    </section>
</div>

<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading">
            <button onclick="$('.add_file_element').show('slow');" class="btn-success btn-xs btn pull-right"><i class="fa fa-plus"></i> Add</button>
            <?php // echo strtoupper($page);  ?> MOBILE MENU LIST
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Menu Type</th>
                            <th>Menu Title</th>
                             <th>Menu Category</th>
                            <th>Status </th>
                            <th>Action </th> 	 
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
                            <th>
                                <select data-column="4"  class="form-control search-input-select">
                                    <option value="">All</option>
                                    <option value="0">Mobile</option>
                                    <option value="1">Android TV</option>
                                    <option value="2">Both</option>

                                </select>
                            </th>
                            <th><select data-column="3"  class="form-control search-input-select">
                                    <option value="">All</option>
                                    <option value="0">Active</option>
                                    <option value="1">Disable</option>

                                </select></th>
                             
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>
<?php
$this->db->select('mobile_menu_id');
$result = $this->db->get('fixed_menu_type_master')->result_array();
$all_fixed = array_map(function($value) {
    return $value['mobile_menu_id'];
}, $result);
$this->db->order_by("position", "asc");
$this->db->where("status", "0");
$this->db->where_in('id', $all_fixed);
$all_fixed_list = $this->db->get('mobile_menu')->result();
$this->db->order_by("position", "asc");
$this->db->where("status", "0");
$this->db->where("category_id", "2");
$this->db->where_not_in('id', $all_fixed);
$all_draggable_list = $this->db->get('mobile_menu')->result();
?>
<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            MOBILE MENU WITH FIXED POSITION
        </header>
        <div class="panel-body">
            <div class="row ui-sortable category-position">
                <div class="col-md-12 column sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <?php
                    foreach ($all_fixed_list as $all_fixed) {
                        ?>
                        <div data-catid="<?php echo $all_fixed->id; ?>" class="panel ui-sortable-handle">
                            <div  class="card-header bg-success alert margin-bottom "> <?php echo $all_fixed->menu_title; ?> </div>
                        </div>                                
                        <?php
                    }
                    ?>
                    <!-- END Portlet PORTLET-->
                </div>
            </div>
        </div>
    </section>
</div>

<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            MOBILE MENU WITH MANAGEABLE POSITION
        </header>
        <div class="panel-body">
            <div class="row ui-sortable category-position"  id="draggable_portlets" style="cursor: all-scroll;">
                <div class="col-md-12 column sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <?php
                    foreach ($all_draggable_list as $all_draggable) {
                        ?>
                        <div data-catid="<?php echo $all_draggable->id; ?>" class="panel ui-sortable-handle">
                            <div  class="card-header bg-primary alert margin-bottom "> <?php echo $all_draggable->menu_title; ?> </div>
                        </div>                                
                        <?php
                    }
                    ?>
                    <!-- END Portlet PORTLET-->
                </div>
            </div>
            <div class="col-md-12"><button class="btn btn-success" onclick="save_position()">Save</button></div>
        </div>
    </section>
</div>
<?php
$adminurl = AUTH_PANEL_URL;
$dragablejs = AUTH_ASSETS . 'js/draggable-portlet.js';
$custum_js = <<<EOD
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"/>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script> 
				 <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" > 					
		jQuery(document).ready(function() {
                       var table = 'all-user-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                            "pageLength": 25,
                            "lengthMenu": [[25, 50, 100], [25, 50, 100]],
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"mobile_menu/ajax_mobile_menu_list/", // json datasource
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
                   } );
				   				   
               </script>   
                   <script src="$dragablejs"></script>
               <script>
                    jQuery(document).ready(function() {
                        DraggablePortlet.init();
                    });
                    function save_position(){
                        var position = [];
                        $('.ui-sortable-handle').each(function() {
                            position.push($(this).data('catid'));
                        });
                        $.ajax({
                            type:'POST',
                            url :"$adminurl"+"mobile_menu/save_position_mobile_menu",
                            data:{'ids':position},
                            dataType:'json',
                            success:function(data){
                              //console.log(data.errors);
                              show_toast('success', 'Position saved successfully','Updated');
                            },
                            error: function(data){

                            }
                        });
                    }
                </script>

EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>