<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
    #pageloader
    {
        background: rgba( 255, 255, 255, 0.8 );
        display: none;
        height: 100%;
        position: fixed;
        width: 100%;
        z-index: 9999;
    }

    #pageloader img
    {
        left: 50%;
        margin-left: -32px;
        margin-top: -32px;
        position: absolute;
        top: 50%;
    }
</style>
<link href="<?= base_url('assets/website_assets/css/video-js.css')?>" rel="stylesheet">
<!-- City -->
<link href="<?= base_url('assets/website_assets/css/city.css')?>" rel="stylesheet">

<!-- Fantasy -->
<link href="<?= base_url('assets/website_assets/css/fantasy.css')?>" rel="stylesheet">

<!-- Forest -->
<link href="<?= base_url('assets/website_assets/css/forest.css')?>" rel="stylesheet">

<!-- Sea -->
<link href="<?= base_url('assets/website_assets/css/sea.css')?>" rel="stylesheet">
<?php
$sql = "SELECT count(*) as total
FROM movies where status!=2 ";
$total = $this->db->query($sql)->row()->total;
$total_in_text_format=convert_number_to_text($total);
$this->db->where('status', '0');
$category = $this->db->get('sub_category')->result_array();
?>
<div class="col-sm-12">
    <div class=" state-overview">
        <div class="col-lg-3 col-sm-6">
            <section class="panel">
                <div class="symbol terques">
                    <i class="fa fa-video-camera"></i>
                </div>
                <div class="value">
                    <h1 class="count">
                        <?php echo $total_in_text_format; ?>
                    </h1>
                    <p>Total Movies</p>
                </div>
            </section>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <section class="panel">
        <header class="panel-heading text-white bg-dark">
            <? //php echo strtoupper($page);   ?>MOVIE(s) LIST
            <span class="tools pull-right">
                <form id="download_content_csv" method="post" action=""  >
                    <button class="btn btn-success margin-right btn-xs"> 
                        <i class="fa fa-file" aria-hidden="true"></i>
                        Download CSV 
                    </button>
                    <textarea style="display:none;" name="input_json"></textarea>
                </form>
            </span>
            <button class="btn-danger btn-xs btn delete_all ml-3"><i class="fa fa-trash"> Delete Selected</i></button>
            <!--button class="btn-info btn-xs btn get_all_youtube_likes_views pull-right" style="margin-right: 1%;"  data-toggle="tooltip" data-placement="top" title="Please be patience it will take some time...">Get youtube likes <i class="fa fa-thumbs-up"></i> & views <i class='fa fa-eye'> <?=date("d-m-Y", $updated_at / 1000);?></i></i></button-->
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <!--div class="col-md-6 pull-right">
                    <span>
                        Filter By Publish Date
                    </span>
                    <div data-date-format="dd-mm-yyyy" data-date="13/07/2013" class="input-group ">
                        <div  class="input-group-addon">From</div>
                        <input type="text" id="min-date-video-list" class="form-control date-range-filter input-sm course_start_date"  placeholder="">

                        <div class="input-group-addon">to</div>

                        <input type="text" id="max-date-video-list" class="form-control date-range-filter input-sm course_end_date"  placeholder="">

                    </div>
                </div-->

                <table  class="display table table-bordered table-striped" id="all-video-grid">
                    <thead>
                        <tr>
                            <th style="width:5%;">#</th>
                            <th>Movie Id </th>
                            <th>Title </th>
                            <th>Actors</th>
                            <th>Description</th>
                            <th>Thumbnail</th>
                            <th>Poster</th>
                            <th>Category</th>
                            <th style="width:50px;">Plan</th>
                            <th style="width:80px;">Published Date</th>
                            <th>Status</th>
                            <th style="width:120px;">Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th><input type="text" data-column="1"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="2"  class="search-input-text form-control"></th>
                            <th><input type="text" data-column="3"  class="search-input-text form-control"></th>
                            <!-- <th></th> -->
                            <th></th>
                            <th></th>
                            <th><select data-column="6"  class="search-input-select form-control">
                                   <option value="" >All</option>
                                   <?php
                                if (isset($sub_caegories)) {

                                    foreach ($sub_caegories as $sub_caegory) {

                                        foreach ($categories as $category) {
                                             $cats = explode(',', $category['genres']);

                                        if (in_array($sub_caegory['id'], $cats)) {
                                        ?>
                                        <option value="<?php echo $sub_caegory['id']; ?>" >

                                        <?php 

                                        

                                           
                                             echo $sub_caegory['sub_category_name'];

                                            
                                     
                                    
                                                    ?>
                                            </option>

                                    <?php 
                                }
                            }
                                  }  } ?>
                                    <!--                                    <option value="1">Sankirtan</option>
                                                                        <option value="2">Normal Video</option>-->
                                </select></th>
                            
                            <th><select data-column="7" class="search-input-select form-control">
                                <option value="" >All</option>
                                <option value="0">Paid</option>
                                <option value="1">Free</option>
                            </th>
                            <th></th>
                             <th><select data-column="8"  class="search-input-select form-control">
                                    <option value="">All</option>
                                    <option value="0">Active</option>
                                    <option value="1">Disable</option>
                                </select></th>
                            <th></th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>

<div id="pageloader">
    <img src="<?= AUTH_ASSETS ?>loader.gif" alt="processing..." />
</div>


<!-- Modal -->
<!--<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close close_modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <iframe class="embed-responsive-item" style="width:100%;min-height:300px " src="https://www.youtube.com/embed/-9pJYrFWEl8" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>-->

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="modal fade" id='playerModal' role='dialog' tabindex='-1' data-backdrop="static" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-footer close-popup-model-footer">
                            <button class="close close_modal_window" aria-label='Close' type='button'>
                                <span aria-hidden='true'>×</span></button>
                        </div>
                        <div class="modal-body video-model-popup">
                            <video id='hls-example' preload="auto" class="video-js vjs-theme-forest vjs-16-9" controls="true" controlsList="nodownload">
                                <source type="application/x-mpegURL" src="https://mahua-tv.s3.ap-south-1.amazonaws.com/file_library/videos/vod/1628621061_1920x1280.m3u8">
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$adminurl = AUTH_PANEL_URL;
$custum_js = <<<EOD
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.15.5/sweetalert2.min.js" integrity="sha512-+uGHdpCaEymD6EqvUR4H/PBuwqm3JTZmRh3gT0Lq52VGDAlywdXPBEiLiZUg6D1ViLonuNSUFdbL2tH9djAP8g==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.15.5/sweetalert2.css" integrity="sha512-WfDqlW1EF2lMNxzzSID+Tp1TTEHeZ2DK+IHFzbbCHqLJGf2RyIjNFgQCRNuIa8tzHka19sUJYBO+qyvX8YBYEg==" crossorigin="anonymous" />
    <script src="https://vjs.zencdn.net/7.2.3/video.js"></script>
    <!--<script src="https://vjs.zencdn.net/ie8/ie8-version/videojs-ie8.min.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-youtube/2.5.1/Youtube.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-media-sources/4.7.2/videojs-contrib-media-sources.js" integrity="sha512-Psj/1ia+wemgBMzVP4iKutwau/tFY7GieKRWUEuFJvG7rbL0QZCLbEECjvrUsDNneRrhL7lBe52pAIaI8GBhEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script type="text/javascript" language="javascript" >
        var all_video_list = "$adminurl"+"movies/Movies/ajax_all_video_list/";
        var all_video_csv = "$adminurl"+"movies/Movies/get_request_for_csv_download/";

        jQuery(document).ready(function() {
            $('#playerModal').bind('contextmenu',function() { return false; });
            var table = 'all-video-grid';
            var dataTable = jQuery("#"+table).DataTable( {
                "processing": true,
                 "pageLength": 15,
                 "lengthMenu": [[15, 50, 100, $total], [15, 50, 100, 'All']],
                "serverSide": true,
                "order": [[ 0, "desc" ]],
                "ajax":{
                    url :all_video_list, // json datasource
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
                     dataTable.columns(10).search(dates).draw();
                 }
                 if($('#min-date-video-list').val() =="" || $('#max-date-video-list').val() == "" ){
                     var dates = "";
                     dataTable.columns(10).search(dates).draw();
                 }
             });
                 $( document ).ajaxComplete(function( event, xhr, settings ) {
                     if ( settings.url === all_video_list ) {
                        var obj = jQuery.parseJSON(xhr.responseText);
                        var read =  obj.posted_data;

                       $('#download_content_csv').attr('action',all_video_csv);
                       $('textarea[name=input_json]').val(JSON.stringify(read));

                     }
                 });
        } );
        $(document).delegate('.preview','click',function(){
            var id=$(this).attr('data_id');
            var token=$(this).attr('token');
            var data_type=$(this).attr('data_type');
            var poster=$(this).attr('poster');
            $.ajax({
                url:"$adminurl"+"movies/movies/ajax_generate_video_url/",
                method:'post',
                dataType:'json',
                data:{id:id,token:token,video_type:data_type},
                success:function(res){
                    var player = videojs('hls-example',{
                        techOrder: ["html5", "youtube"],
                        html5: {
                            nativeCaptions: false
                        },
                        playbackRates: [0.5, 1, 1.5, 2]
                    });
                    player.src([{
                        src:res.data.url,
                        type: res.data.type
                    }]);
                    $('#playerModal').modal('show');
                    //player.play();
                    $('.close_modal_window').click(function(){
                        $('#playerModal').modal('hide');
                        player.pause();
                    });
                }
            });
        });

				   $('#min-date-video-list').datepicker({
				  		format: 'yyyy-mm-dd',
						autoclose: true

					});
					$('#max-date-video-list').datepicker({
						format: 'yyyy-mm-dd',
						autoclose: true

					});
               </script>
        
        <script>
        
        $(".delete_all").click(function(){
            var selected_ids = []
                $("input:checkbox[name=selected_id]:checked").each(function(){
                    selected_ids.push($(this).val());
                });
                if (selected_ids.length == 0) {
                    Swal.fire('Select atleast one movie');
                    return false;
                }
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {
                    if (result.isConfirmed) {
                          jQuery.ajax({
                            url :"$adminurl"+"movies/movies/delete_all_selected_data/", // json datasource
                            type: "post", // method , by default get
                            dataType: "json",
                            data: {
                                    selected_ids: selected_ids
                            },
                            beforeSend: function () {
                               $("#pageloader").show();
                            },
                            complete: function () {
                                $("#pageloader").hide();
                            },
                            success: function (data) {
                                    if(data.data==1){
                                        window.location.reload();
                                        }
                            }
                        })
                    
                    }else{
                        return false;
                    }
                  
                  })
            });
        
        
               </script>
         <script>
        
        $(".get_all_youtube_likes_views").click(function(){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to get latest likes and view from youtube. Please be patience it will take some time!!!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, get it!'
                  }).then((result) => {
                    if (result.isConfirmed) {
                          jQuery.ajax({
                            url :"$adminurl"+"videos/Video_control/get_all_youtube_likes_views/", // json datasource
                            type: "post", // method , by default get
                            dataType: "json",
                            data: {
                            },
                        beforeSend: function () {
                           $("#pageloader").show();
                        },
                        complete: function () {
                            $("#pageloader").hide();
                            Swal.fire({
                                title: 'Successfully fetched from youtube!',
                                confirmButtonText: `Ok`,
                              }).then((result) => {
                                 window.location.reload();
                              })
                             
                        },
                            success: function (data) {
                                    if(data.data==1){
                                        window.location.reload();
                                        }
                            }
                        })
                    
                    }else{
                        return false;
                    }
                  
                  })
            });
         </script>
        
        <script>

            $('.close_modal').click(function () {
                $('.set_src').attr('src','');
            });
            
            $("body").on("click", ".copy_url", function(event){
                    var tmpInput = $('<input>');
                    tmpInput.val($(this).data('url'));
                    $('body').append(tmpInput);
                    tmpInput.select();
                    document.execCommand('copy');
                    tmpInput.remove();
                    alert("Url copied paste it on browser to play video")
            });
	 </script>

EOD;

echo modules::run('auth_panel/template/add_custum_js', $custum_js);
?>
