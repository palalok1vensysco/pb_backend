<style type="text/css">
    .active {color:orange;}
</style>
<div class="col-sm-12">
	<section class="panel">
		<header class="panel-heading  text-white bg-dark">
		All Feedback List
		</header>
		<div class="panel-body">
		<div class="adv-table">		   

		<table  class="display table table-bordered table-striped" id="all-post-grid">
  		<thead>
    		<tr>
				<th>#</th>
                <th>User Name</th>
                <th>Star Rating</th>
                <th>Message</th>
                <th>Creation</th>
                <th>Action</th>
    		</tr>
  		</thead>
        <thead>
            <tr>
                <th></th>
                <th><input type="text" data-column="0"  class="form-control input-xs search-input-text"></th>
                <th>
                    <select data-column="1"  class="form-control input-xs search-input-select">
                        <option value="">(All)</option>
                        <option value="1">1 Star</option>
                        <option value="2">2 Star</option>
                        <option value="3">3 Star</option>
                        <option value="4">4 Star</option>
                        <option value="5">5 Star</option>
                    </select>
                </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
		</table>
		</div>
		</div>
	</section>
</div>
<script type="text/javascript">
    function changestatus(a) 
    {
        $.ajax({
            type: 'POST',
            url: "<?= AUTH_PANEL_URL ?>user_support/change_status",
            data: {user_id: a},
            dataType: 'json',
            success: function (data) 
            {   
                if (data.status) 
                {
                    show_toast('success', 'Status Changed Successfully', 'Feedback Status');
                }
            }
        });
    }
</script>
<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD

               <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                   jQuery(document).ready(function() {
                       var table = 'all-post-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"user_support/ajax_all_feedback_list/", // json datasource
                               type: "post",  // method  , by default get
                               error: function(){  // error handling
                                   jQuery("."+table+"-error").html("");
                                   jQuery("#"+table+"_processing").css("display","none");
                               }
                           }
                       }); //data table closed

                       jQuery("#"+table+"_filter").css("display","none");
                       $('.search-input-text').on( 'keyup click', function () {   // for text boxes
                           var i =$(this).attr('data-column');  // getting column index
                           var v =$(this).val();  // getting search input value
                           dataTable.columns(i).search(v).draw();
                       });
              				 $('.search-input-select').on( 'change', function () {   // for select box
              					    var i =$(this).attr('data-column');
              					    var v =$(this).val();
              					    dataTable.columns(i).search(v).draw();
              					});


          }); //Main document closed
               </script>     
                    

EOD;
echo modules::run('auth_panel/template/add_custum_js',$custum_js );
