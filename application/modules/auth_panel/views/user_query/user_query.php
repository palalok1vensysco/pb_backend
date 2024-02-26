

<div class="col-sm-12 no-padding">
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            Help Desk(s) LIST
            <!-- <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
            </span> -->
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="all-user-grid">
                    <thead>
                        <tr>
                           <th>Serial No.</th>
                            <th>User Id</th>
                            <th>User Name </th>
                            <th>Created At </th>
                            
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                           <th></th>
                            <th><input type="text" data-column="0"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
             
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
              <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

 jQuery(document).ready(function () {
        var table = 'all-user-grid';
        var dataTable = jQuery("#" + table).DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[0, "desc"]],
            "columnDefs": [
                {
                    "targets": "_all",
                    "orderable": false
                }
            ],
            "ajax": {
                url: "$adminurl"+"User_query/ajax_all_user_query_list", // json datasource
                type: "post", // method  , by default get
                error: function () {  // error handling
                    jQuery("." + table + "-error").html("");
                    jQuery("#" + table + "_processing").css("display", "none");
                }
            }
        });
        jQuery("#" + table + "_filter").css("display", "none");
        $('.search-input-text').on('keyup', function () {   // for text boxes
            var i = $(this).attr('data-column');  // getting column index
            var v = $(this).val();  // getting search input value
           // alert(i);alert(v);
            dataTable.columns(i).search(v).draw();
        });
        $('.search-input-select').on('change', function () {   // for text boxes
            var i = $(this).attr('data-column');  // getting column index
            var v = $(this).val();  // getting search input value
            dataTable.columns(i).search(v).draw();
        });
    });

                   
                   $('#min-date-video-list').datepicker({
                        format: 'dd-mm-yyyy',
                        autoclose: true
                        
                    });
                    $('#max-date-video-list').datepicker({
                        format: 'dd-mm-yyyy',
                        autoclose: true
                        
                    });
               </script>
               
               
               
              <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase-app.js"></script>    
              <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase-database.js"></script>
              <script>
                        // Your web app's Firebase configuration
                        var firebaseConfig = {
                        apiKey: "AIzaSyDjA0ySDslfiL0532eVUXxA9hlFYPpF0oE",
                        authDomain: "totalbhakti-c4cec.firebaseapp.com",
                        projectId: "totalbhakti-c4cec",
                        databaseURL: "https://totalbhakti-c4cec.firebaseio.com",
                      };
                        // Initialize Firebase
                        firebase.initializeApp(firebaseConfig);
                    </script>
               
               
               <script>
                $(document).on('click','.delete_chat',function(){
                    var selector = $(this);
                    var id = selector.attr('id');
                    var node = selector.attr('node');
                    var channel = selector.attr('channel');
                    var myDataRef = firebase.database().ref().child('sanskarliveChannels/' + channel +'/'+ node);
                    myDataRef.remove(); 
                    jQuery.ajax({
                    url :"$adminurl"+"chat/Chat/delete_chat/", // json datasource
                    type: "post", // method , by default get
                    dataType: "json",
                    data: {
                        id: id
                    },
                    success: function (data) {
                        if(data.data==1){
                                                        show_toast('success', 'Chat has been deleted successfully','Chat deleted');
                            window.location.reload();
                        }
                    }
                });
                });
               </script>

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>