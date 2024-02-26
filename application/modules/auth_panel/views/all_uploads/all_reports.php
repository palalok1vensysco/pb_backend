<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<?php
$period = $this->input->get('period');
$type = $this->input->get('type');
if (!$is_support) {

    $today = date('d-m-Y');
    $currentWeekDate = date('d-m-Y', strtotime('-7 days', strtotime($today)));
    $currentMonth = date('m');
    $currentYear = date('Y');
    $current_millisecond = strtotime(date('01-m-Y 00:00:00')) * 1000;
    if ($period == "today") {
        $where = " WHERE DATE_FORMAT(FROM_UNIXTIME(SUBSTR(creation_time,1,10)), '%d-%m-%Y')= '$today'";
        $sql = "SELECT count(id) as total FROM `news` $where and status =0";
        $total_news = $this->db->query($sql)->row()->total;

        $sql = "SELECT count(id) as total FROM `bhajan` $where and status =0";
        $total_bhajan = $this->db->query($sql)->row()->total;

         $sql = "SELECT count(id) as total FROM `video_master` $where and status =0";
        $total_video = $this->db->query($sql)->row()->total;
    } elseif ($period == "yesterday") {
        $yesterday = date('d-m-Y', strtotime($today . ' - 1 days'));
        $where = " WHERE DATE_FORMAT(FROM_UNIXTIME(SUBSTR(creation_time,1,10)), '%d-%m-%Y')= '$yesterday'";
        $sql = "SELECT count(id) as total FROM `news` $where and status =0";
        $total_news = $this->db->query($sql)->row()->total;

        $sql = "SELECT count(id) as total FROM `bhajan` $where and status =0";
        $total_bhajan = $this->db->query($sql)->row()->total;

       $sql = "SELECT count(id) as total FROM `video_master` $where and status =0";
        $total_video = $this->db->query($sql)->row()->total;
    } elseif ($period == "7days") {
        $week = strtotime("-1 week") . "000";
        $where = " WHERE creation_time >=  $week";
       $sql = "SELECT count(id) as total FROM `news` $where and status =0";
        $total_news = $this->db->query($sql)->row()->total;

        $sql = "SELECT count(id) as total FROM `bhajan` $where and status =0";
        $total_bhajan = $this->db->query($sql)->row()->total;

       $sql = "SELECT count(id) as total FROM `video_master` $where and status =0";
        $total_video = $this->db->query($sql)->row()->total;
    } elseif ($period == "current_month") {
        $current_month = date('m-Y');
        $where = " WHERE DATE_FORMAT(FROM_UNIXTIME(SUBSTR(creation_time,1,10)), '%m-%Y') = '$current_month'";

       $sql = "SELECT count(id) as total FROM `news` $where and status =0";
        $total_news = $this->db->query($sql)->row()->total;

        $sql = "SELECT count(id) as total FROM `bhajan` $where and status =0";
        $total_bhajan = $this->db->query($sql)->row()->total;

       $sql = "SELECT count(id) as total FROM `video_master` $where and status =0";
        $total_video = $this->db->query($sql)->row()->total;
    } elseif ($period == "all" || $period == "" || $period == "custom") {

        $sql = "SELECT count(id) as total FROM `news` WHERE status =0";
        $total_news = $this->db->query($sql)->row()->total;

        $sql = "SELECT count(id) as total FROM `bhajan` WHERE status =0";
        $total_bhajan = $this->db->query($sql)->row()->total;

       $sql = "SELECT count(id) as total FROM `video_master` WHERE status =0";
        $total_video = $this->db->query($sql)->row()->total;
    }
}
$period = $this->input->get('period');
$type = $this->input->get('type');

  //  if ($period != "custom") {
        ?>
        <div class=" state-overview">
             <a href="<?php echo AUTH_PANEL_URL . 'news/news/news_list'; ?>"">
            <div class="col-lg-4 col-sm-6">
                <section class="panel">
                    <div class="symbol terques">
                        <i class="fa fa-edit"></i>
                    </div>
                    <div class="value">
                        <h1 class="count"><?php echo $total_news; ?><span id="check_mark1" class="hide" style="color: green;float: right;"><i class="fa fa-check"></i></span></h1>
                        <p>Total News</p>
                    </div>
                </section>
            </div>
                  </a>
            <a href="<?php echo AUTH_PANEL_URL . 'bhajan/bhajan/bhajan_list'; ?>"">
            <div class="col-lg-4 col-sm-6">
                <section class="panel">
                    <div class="symbol yellow">
                        <i class="fa fa-music"></i>
                    </div>
                    <div class="value">
                        <h1 class=" count2"><?php echo $total_bhajan; ?><span id="check_mark2" class="hide" style="color: green;float: right;"><i class="fa fa-check"></i></span></h1>
                        <p>Total Bhajan</p>
                    </div>
                </section>
            </div>
                </a>
             <a href="<?php echo AUTH_PANEL_URL . 'videos/video_control/video_list'; ?>"">
            <div class="col-lg-4 col-sm-6">
                <section class="panel">
                    <div class="symbol red">
                        <i class="fa fa-video-camera"></i>
                    </div>
                    <div class="value">
                        <h1 class=" count3"><?php echo $total_video; ?><span id="check_mark3" class="hide" style="color: green;float: right;"><i class="fa fa-check"></i></span></h1>
                        <p>Total Video</p>
                    </div>
                </section>
            </div>
                  </a>
        </div>
        <?php
    //}
?>

<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading">
            <?php
                    if (isset($_GET['type']) && !empty($_GET['type'])) {
                        if($_GET['type']!='all'){
                            echo 'All '.ucwords($_GET['type']);
                        }else{
                             echo 'All News';
                        }
                    }
                    ?>
            <span class="tools pull-right">
<!--                <form role="form" method="get" action="<?//php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  >-->
                <form role="form" method="get">
                    <select name="period" class="form-control input-xs m-bot15 period" onchange="this.form.submit()">
                        <option <?= ($period == "today") ? "selected" : "" ?> value="today">Today</option>
                        <option <?= ($period == "yesterday") ? "selected" : "" ?>  value="yesterday">Yesterday</option>
                        <option <?= ($period == "7days") ? "selected" : "" ?>  value="7days">Last 7 Days</option>
                        <option <?= ($period == "current_month") ? "selected" : "" ?>  value="current_month">Current Month</option>
                        <option <?= ($period == "custom") ? "selected" : "" ?>  value="custom">Custom</option>
                        <option <?= ($period == "" || $period == "all") ? "selected" : "" ?>  value="all">All</option>
                    </select>
                    <?php
                    foreach ($_GET as $key => $value) {
                        if ($key != 'period') {
                            echo "<input type='hidden' value='" . $value . "' name='" . $key . "'>";
                        }
                    }
                    ?>
                </form>
            </span>
            
            <span class="tools pull-right">
<!--                <form role="form" method="get" action="<?//php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">-->
                <form role="form" method="get"">
                    <select name="type" class="form-control input-xs m-bot15 type" onchange="this.form.submit()">
                        <option <?= ($type == "news") ? "selected" : "" ?>  value="news">News</option>
                        <option <?= ($type == "bhajan") ? "selected" : "" ?>  value="bhajan">Bhajan</option>
                         <option <?= ($type == "video") ? "selected" : "" ?>  value="video">Video</option>
                    </select>
                </form>
            </span>
            <!-- download csv  -->
            <?php
            if (!$is_support) {
                ?>
            <span class="tools pull-right" style="margin-right: 5px;">
                    <form id="download_content_csv" method="post" action=""  >
                        <button class="btn btn-xs btn-success margin-right bold"><i class="fa fa-file" aria-hidden="true"></i>Download CSV</button>
<!--                        <input name="download_pdf" class="btn btn-info btn-xs  margin-right bold" value="Download PDF" type="submit">-->
                        <textarea style="display:none;" name="input_json"></textarea>
                    </form>
                </span>
                <?php
            }
            ?>

        </header>
        <div class="panel-body">
            <div class="adv-table">
                <?php if ($period == "custom") { ?>
                    <div class="col-md-6 pull-right custom_search" >
                        <div data-date-format="dd-mm-yyyy" data-date="13/07/2013" class="input-group ">
                            <div  class="input-group-addon">From</div>
                            <input type="text" id="min-date-course-transaction" class="form-control date-range-filter input-sm course_start_date"  placeholder="">
                            <div class="input-group-addon">to</div>
                            <input type="text" id="max-date-course-transaction" class="form-control date-range-filter input-sm course_end_date"  placeholder="">
                            <div class="input-group-addon btn date-range-filter-clear">Clear</div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-md-12 custom_filter_label  margin-top  margin-bottom"></div>
                <table  class="display table table-bordered table-striped " id="all-Course-transactions-grid">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Title</th>
                            <th>Uploaded By</th>
                            <th>Creation Date</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
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
$hide_column = $is_support ? -1 : "";
$custum_js = <<<EOD
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" >

    jQuery(document).ready(function() {
        if('$type'=='news' || '$type'=='all'){
            $("#check_mark1").removeClass("hide");
        }
        if('$type'=='bhajan'){
            $("#check_mark2").removeClass("hide");
        }
        if('$type'=='video'){
            $("#check_mark3").removeClass("hide");
        }
        var get_all_record = "$adminurl"+"reports/all_uploads/get_ajax_all_list/?period=$period&is_support=$is_support&type=$type";
        var get_all_record_forcsv = "$adminurl"+"reports/all_uploads/get_ajax_all_download/?period=$period&type=$type";
        

        var table = 'all-Course-transactions-grid';
        var dataTable_transaction = jQuery("#"+table).DataTable( {
            "processing": true,
            "pageLength": 25,
            "serverSide": true,
            "order": [[ 0, "desc" ]],
            "bSortCellsTop": true,
            "columnDefs": [
                {
                    "targets": [$hide_column],
                    "visible": false,
                    "searchable": false
                }
            ],
           "ajax":{
                url : get_all_record , // json datasource
                type: "post",  // method  , by default get
                data:function(d){
                    $(".search-input-text").each(function(){
                        if($(this).val()!=""){    
                            d['columns'][$(this).data("column")]['search']['value'] = $(this).val();
                        }
                    });
                    $(".search-input-select").each(function(){
                        if($(this).val()!=""){    
                            d['columns'][$(this).data("column")]['search']['value'] = $(this).val();
                        }
                    });
                },
                error: function(){  // error handling
                   jQuery("."+table+"-error").html("");
                   jQuery("#"+table+"_processing").css("display","none");
                }
           }
        });
        jQuery("#"+table+"_filter").css("display","none");
        $('.search-input-text').on('keyup', function () {   // for text boxes
            var i =$(this).attr('data-column');  // getting column index
            var v =$(this).val();  // getting search input value
            dataTable_transaction.columns(i).search(v).draw();
        });
        $('.search-input-select').on('change', function () {   // for select box
            var i =$(this).attr('data-column');
            var v =$(this).val();
            dataTable_transaction.columns(i).search(v).draw();
        });
        // Re-draw the table when the a date range filter changes
        $('.date-range-filter').change(function() {
            if($('#min-date-course-transaction').val() !="" && $('#max-date-course-transaction').val() != "" ){
                var dates = $('#min-date-course-transaction').val()+','+$('#max-date-course-transaction').val();
                dataTable_transaction.columns(3).search(dates).draw();
            }
        });

        $('.date-range-filter-clear').on('click', function () {
            // clear date filter
            $('#min-date-course-transaction').val('');
            $('#max-date-course-transaction').val("");
            dataTable_transaction.columns(3).search('').draw();
        });
        /*
        * speacial magic function after getting result from server
        */
        $(document).ajaxComplete(function(event,xhr,settings) {
            if ( settings.url == get_all_record ) {
                var obj = jQuery.parseJSON(xhr.responseText);
                var read =  obj.client_filters;
                $('.custom_filter_label').html('');
                if(read.course_name && read.course_name != "" ){
                    $('.custom_filter_label').append('<span onclick="console.log($(\'.course_name_search\').val(\'\').click())" class="label label-primary margin-right"><i class="fa fa-book"></i> '+read.course_name+' <i class="fa fa-times"></i></span>');
                }

                var read =  obj.posted_data;
                $('#download_content_csv').attr('action',get_all_record_forcsv);
                $('textarea[name=input_json]').val(JSON.stringify(read));
            }
        });
    });


    /* custum date filter */
    $('#min-date-course-transaction').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });
    $('#max-date-course-transaction').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });

    $('.period').on( 'change', function () {
      period = $(this).val();
      if(period == "custom"){
        $('.custom_search').show();
      }
    });



</script>

EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);
