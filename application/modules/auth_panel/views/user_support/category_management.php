<style type="text/css">
    .reply_ul li{list-style:none;padding:6px;background:#eee;margin-bottom:6px;}
    .active {color:orange;}
</style>
<div class="col-md-12 no-padding" id="add_suggestion_category" style="display:none;">
    <section class="panel">
        <header class="panel-heading  text-white bg-dark">Suggestion Category</header>
        <div class="panel-body">
            <form role="form" method="POST">
                <div class="col-md-12">
                    <div class="col-sm-6 form-group">
                        <label>Category Name*</label>
                        <input type="text" class="form-control input-xs" name="name" placeholder="Enter Name" required >
                    </div>
                    <input type="hidden" name="type" value="0">
                    <div class="col-sm-6 form-group">
                        <label>Category Name(Hindi)*</label>
                        <input type="text" class="form-control input-xs" name="name_2" placeholder="Enter Name In Hindi" required >
                    </div>
                    <button type="submit" class="btn-xs btn-info">Add Category</button>
                    <button type="reset" class="btn-xs btn-warning" onclick="$('#add_suggestion_category').hide('slow');" >Cancel</button>
                </div>
            </form>
        </div>
    </section>
</div>
<div class="col-md-12 no-padding" id="add_complaint_category" style="display:none;">
    <section class="panel">
        <header class="panel-heading  text-white bg-dark ">Complaint Category</header>
        <div class="panel-body">
            <form role="form" method="POST">
                <div class="col-md-12">
                    <div class="col-sm-6 form-group">
                        <label>Category Name*</label>
                        <input type="text" class="form-control input-xs" name="name" placeholder="Enter Name" required >
                    </div>
                    <input type="hidden" name="type" value="1">
                    <div class="col-sm-6 form-group">
                        <label>Category Name(Hindi)*</label>
                        <input type="text" class="form-control input-xs" name="name_2" placeholder="Enter Name In Hindi" required >
                    </div>
                    <button type="submit" class="btn-xs btn-info">Add Category</button>
                    <button type="reset" class="btn-xs btn-warning" onclick="$('#add_complaint_category').hide('slow');" >Cancel</button>
                </div>
            </form>
        </div>
    </section>
</div>
<div class="col-sm-6">
	<section class="panel">
		<header class="panel-heading ban-head-new  text-white bg-dark">
		All Suggestion List
            <div class="tools-right-1">
                <button class="btn-xs btn-success pull-right" onclick="$('#add_suggestion_category').show('slow');">
                    <i class="fa fa-plus fa-fw"></i> Add Suggestion Category
                </button>
            </div>
		</header>
		<div class="panel-body">
		<div class="adv-table">		   

		<table  class="display table table-bordered table-striped" id="all-post-grid">
  		<thead>
    		<tr>
				<th>#</th>
                <th>Category Name</th>
                <th>Creation</th>
                <th>Action</th>
    		</tr>
  		</thead>
		</table>
		</div>
		</div>
	</section>
</div>
<div class="col-sm-6">
    <section class="panel">
        <header class="panel-heading ban-head-new  text-white bg-dark">
        All Complaint List
            <div class="tools-right-1">
                <button class="btn-xs btn-success pull-right" onclick="$('#add_complaint_category').show('slow');">
                    <i class="fa fa-plus fa-fw"></i> Add Complaint Category
                </button>
            </div>
        </header>
        <div class="panel-body">
        <div class="adv-table">        

        <table  class="display table table-bordered table-striped" id="all-complaint-grid">
        <thead>
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Creation</th>
                <th>Action</th>
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
                               url :"$adminurl"+"user_support/ajax_all_suggestion_list/", // json datasource
                               type: "post",  // method  , by default get
                               error: function(){  // error handling
                                   jQuery("."+table+"-error").html("");
                                   jQuery("#"+table+"_processing").css("display","none");
                               }
                           }
                       }); //data table closed
                       jQuery("#"+table+"_filter").css("display","none");

                        var table = 'all-complaint-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"user_support/ajax_all_complaint_list/", // json datasource
                               type: "post",  // method  , by default get
                               error: function(){  // error handling
                                   jQuery("."+table+"-error").html("");
                                   jQuery("#"+table+"_processing").css("display","none");
                               }
                           }
                       }); //data table closed
                       jQuery("#"+table+"_filter").css("display","none");


          }); //Main document closed
               </script>     
                    

EOD;
echo modules::run('auth_panel/template/add_custum_js',$custum_js );
