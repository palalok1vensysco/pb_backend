
<div class="col-lg-6">
    <section class="panel">
        <header class="panel-heading  text-white bg-dark">
            Send SMS to Users
        </header>
        <div class="panel-body">
            <form method="POST" action="" role="form" onsubmit="return confirm('Are you sure to send message?')">
                <div class="form-group">
                    <label for="exampleInputEmail1">Type of user</label>
                    <select name="user_type"class="form-control m-bot15">
                        <option value="all" selected="">All</option>
<!--                        <option value="dams"><?=CONFIG_PROJECT_NICK_NAME?> User</option>
                        <option value="non_dams">Non <?=CONFIG_PROJECT_NICK_NAME?> user</option>-->
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Type message</label>
                    <textarea class="form-control" name="message"> </textarea>
                    <span style="color: red"><?php echo ($this->session->flashdata('error') && $this->session->flashdata('error') == 'raw_error') ? form_error('message') : ''; ?></span>
                </div>
                <input type="hidden" value="raw" name="messanger_type">
                <input type="hidden" value="<?php echo $this->session->userdata('active_user_data')->username; ?>" name="send_by">
                <button class="btn btn-info" type="submit" >Submit</button>
            </form>

        </div>
    </section>
</div>


<!-- Custome User Send Message -->

<div class="col-lg-6">
    <section class="panel">
        <header class="panel-heading">
            Send SMS to custom User(s)
        </header>
        <div class="panel-body">
            <form method="POST" action="" role="form" onsubmit="return confirm('Are you sure to send message?')">
                <div class="form-group">
                    <label for="exampleInputEmail1">Mobile number</label>
                    <input type="text" class="form-control" value="<?php echo (set_value('mobile') == false) ? base64_decode($this->input->get('M')) : set_value('mobile'); ?>" placeholder="Enter Number Ex : 8888888888,8888888888,8888888888" name="mobile" >
                    <span style="color: red"><?php echo ($this->session->flashdata('error') && $this->session->flashdata('error') == 'custom_error') ? form_error('mobile') : ''; ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Type message</label>
                    <textarea class="form-control" name="message"> </textarea>
                    <span style="color: red"><?php echo ($this->session->flashdata('error') && $this->session->flashdata('error') == 'custom_error') ? form_error('message') : ''; ?></span>
                </div>
                <input type="hidden" value="<?php echo $this->session->userdata('active_user_data')->username; ?>" name="send_by">
                <input type="hidden" value="custom" name="messanger_type">
                <button class="btn btn-info" type="submit">Submit</button>
            </form>

        </div>
    </section>
</div>


<!-- All sent messages to user or dams/non dams user -->
<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading">
            MESSAGES SENT LIST(s)
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <!--<a href="javascript:;" class="fa fa-times"></a>-->
            </span>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="backend-user-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Send by</th>
                            <th>Send to</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th><input type="text" data-column="0"  class="form-control search-input-text"></th>
                            <th><input type="text" data-column="1"  class="form-control search-input-text"></th>
                            <th></th>
                            <th><input type="text" data-column="3"  class="form-control search-input-text"></th>
                            <th></th>
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

                   jQuery(document).ready(function() {
                       var table = 'backend-user-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                           "pageLength": 50,
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"bulk_messenger/bulk_message/ajax_send_messages", // json datasource
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
                       
                   } );
               </script>

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>