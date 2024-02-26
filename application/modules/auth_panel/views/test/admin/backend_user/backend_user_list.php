<div class="col-sm-12 px-0">
    <section class="panel">
        <header class="panel-heading  bg-dark text-white">
            BACKEND USER(s) LIST
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
            </span>
            <a href="<?=AUTH_PANEL_URL;?>admin/create_backend_user" class="btn btn-xs btn-success pull-right"><i class="fa fa-plus"></i>Add Backend User</a>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="backend-user-grid">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>User name </th>
                            <th>Email </th>
                            <th>Mobile</th>
                            <th>User state</th>
                            <th>User role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="form-control search-input-text"></th>
                            <th><input type="text" data-column="2"  class="form-control search-input-text"></th>
                            <th><input type="text" data-column="3"  class="form-control search-input-text"></th>
                            <th> <select data-column="4"  class="form-control search-input-select">
                                    <option value="">(All)</option>
                                    <option value="3">Active</option>
                                    <option value="1">Blocked</option>
                                </select>
                            </th>
                            <th>
                                <select data-column="5" class="form-control search-input-select">
                                    <option value="0">All</option>
                                    <?php
                                        foreach($user_roles as $role){
                                            echo '<option value="'.$role['id'].'">'.$role["permission_group_name"].'</option>';
                                        }
                                    ?>
                                </select>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>

<?php 

$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
              <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                   jQuery(document).ready(function() {
                       var table = 'backend-user-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                           "pageLength": 50,
                           "serverSide": true,
                           "order": [[ 2, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"admin/ajax_backend_user_list", // json datasource
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

                        $(document).ajaxComplete(function (event, xhr, settings) {
                            if (settings.url === "<?= AUTH_PANEL_URL ?>admin/ajax_backend_user_list") {
                                $("#" + table).find("input[type=checkbox]").each(function () {
                                    var switchery = new Switchery($(this)[0], {size: 'small'});
                                });
                            }
                        });
                   } );

               </script>

EOD;

    echo modules::run('auth_panel/template/add_custum_js',$custum_js );
?>