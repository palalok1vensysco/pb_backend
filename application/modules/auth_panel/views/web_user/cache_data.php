<section class="panel">
    <header class="panel-heading">Cache Version</header>
    <div class="panel-body">
        <div class="adv-table">
            <table class="display table table-bordered table-striped" id="cache-data">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Master Hit</th>
                        <th>Get Course</th>
                        <th>Get My Course</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $start = 0;
                    foreach($cache_data as $cd){ ?>
                    <tr>
                        <td><?=++$start;?></td>
                        <td><?=$cd->ut_009;?></td>
                        <td><?=$cd->ut_010;?></td>
                        <td><?=$cd->ut_012;?></td>
                    </tr>      
                    <?php } ?>
                
                </tbody>
            </table>
        </div>
    </div>    
</section>
<section class="panel">
    <header class="panel-heading">User Wise Cache Version</header>
    <div class="panel-body">
        <div class="adv-table">
            <table class="display table table-bordered table-striped" id="user-wise-cache-data">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Meta Id</th>
                        <th>Code</th>
                        <th>Version</th>
                        <th>Expire On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $start = 0;
                    foreach($user_wise_cache_data as $uwcd){ ?>
                    <tr>
                        <td><?=++$start;?></td>
                        <td><?=$uwcd->meta_id;?></td>
                        <td><?=$uwcd->code;?></td>
                        <td><?=$uwcd->version;?></td>
                        <td><?= get_time_format($uwcd->exp);?></td>
                    </tr>      
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>    
</section>