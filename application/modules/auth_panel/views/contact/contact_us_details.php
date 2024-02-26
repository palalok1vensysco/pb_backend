<aside class="profile-info col-lg-12">
<a href="<?= base_url('admin-panel/contact-us'); ?>"><button class="pull-right btn btn-info btn-xs bold">Back to request list</button></a>
    <section class="panel">
        <div class="panel-body bio-graph-info">
            <h1>User Details</h1>
            <div class="row">
                <div class="bio-row">
                    <p><span>Name </span>: <?= ($user_details['full_name']?$user_details['full_name']:'N/A')?></p>
                </div>
                <div class="bio-row">
                    <p><span>Email </span>: <?= ($user_details['email']?$user_details['email']:'N/A')?></p>
                </div>
                <div class="bio-row">
                    <p><span>Mobile</span>: <?= ($user_details['mobile']?$user_details['mobile']:'N/A')?></p>
                </div>
                <div class="bio-row">
                    <p><span>Date/Time</span>: <?php echo date("d-m-Y H:i:s", $user_details['creation_time'] / 1000) ?></p>
                </div>
                <div class="bio-row">
                    <p><span>Message</span>: <?= ($user_details['message']?$user_details['message']:'N/A')?></p>
                </div>
            </div>
        </div>		  
    </section>
</aside>

<?php
$custum_js = <<<EOD
				<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
              	<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
EOD;
echo modules::run('auth_panel/template/add_custum_js', $custum_js);





