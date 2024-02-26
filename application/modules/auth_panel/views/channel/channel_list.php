<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<?php
$sql = "SELECT count(*) as total  
FROM live_channel where status=1 ";
$total = $this->db->query($sql)->row()->total;
?>
<div class="col-sm-12 ">
    <div class=" state-overview">
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol terques">
                    <i class="fa fa-video-camera"></i>
                </div>
                <div class="value">
                    <h1 class="count">
                        <?php echo $total; ?>                
                    </h1>
                    <p>Total Channel</p>
                </div>
            </section>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading">
            <?//php echo strtoupper($page); ?> CHANNEL(s) LIST
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <!--<div class="col-md-6 pull-right">
                        <div data-date-format="dd-mm-yyyy" data-date="13/07/2013" class="input-group ">
                         <div  class="input-group-addon">From</div>
                        <input type="text" id="min-date-video-list" class="form-control date-range-filter input-sm course_start_date"  placeholder="">

                        <div class="input-group-addon">to</div>

                        <input type="text" id="max-date-video-list" class="form-control date-range-filter input-sm course_end_date"  placeholder="">

                        </div>		
                </div>-->
                <table  class="display table table-bordered table-striped" id="all-video-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Channel</th>
                            <th>Url </th>
                            <th>Thumbnail</th>
                            <th>Creation time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th><input type="text" data-column="0"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th></th>
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


<?php
$this->db->order_by("position", "asc");
$this->db->where("status", "1");
$all = $this->db->get('live_channel')->result();
?>
<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            Manage position
        </header>
        <div class="panel-body">
            <div class="row ui-sortable category-position"  id="draggable_portlets" style="cursor: all-scroll;">
                <div class="col-md-12 column sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <?php
                    foreach ($all as $a) {
                        ?>
                        <div data-catid="<?php echo $a->id; ?>" class="panel ui-sortable-handle">
                            <div  class="card-header bg-primary alert margin-bottom "> <?php echo $a->name; ?> </div>
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
              <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
               <script type="text/javascript" language="javascript" >

                   jQuery(document).ready(function() {
                       var table = 'all-video-grid';
                       var dataTable = jQuery("#"+table).DataTable( {
                           "processing": true,
                            "pageLength": 15,
                            "lengthMenu": [[15, 25, 50], [15, 25, 50]],
                           "serverSide": true,
                           "order": [[ 0, "desc" ]],
                           "ajax":{
                               url :"$adminurl"+"channel/Channel_control/ajax_channel_list/", // json datasource
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
						// Re-draw the table when the a date range filter changes
                        $('.date-range-filter').change(function() {
                            if($('#min-date-video-list').val() !="" && $('#max-date-video-list').val() != "" ){
                                var dates = $('#min-date-video-list').val()+','+$('#max-date-video-list').val();
                                dataTable.columns(8).search(dates).draw();
                            } 
                            if($('#min-date-video-list').val() =="" || $('#max-date-video-list').val() == "" ){
                                var dates = "";
                                dataTable.columns(8).search(dates).draw();
                            }  
                        }); 
                   } );
				   
				   $('#min-date-video-list').datepicker({
				  		format: 'dd-mm-yyyy',
						autoclose: true
						
					});
					$('#max-date-video-list').datepicker({
						format: 'dd-mm-yyyy',
						autoclose: true
						
					});
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
                            url :"$adminurl"+"channel/channel_control/save_position_channels",
                            data:{'ids':position},
                            dataType:'json',
                            success:function(data){
                              //console.log(data.errors);
                              show_toast('success', 'Position saved successfully','Updated');
                            jQuery("#all-user-grid").DataTable().ajax.reload();
                               
                            },
                            error: function(data){

                            }
                        });
                    }
                </script>

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
