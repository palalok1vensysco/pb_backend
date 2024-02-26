<div class="row">
    <div class="col-md-4">
 <section class="panel">
        <header class="panel-heading">
           USD Conversion
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="<?= AUTH_PANEL_URL . "version_control/version/usd_conversion" ?>" >
                <div class="form-group">
                   <label>USD Conversion Rate</label> 
                   <input type="text" name="usd_conversion" placeholder="USD Conversion Rate" class="form-control" value="<?= $result['meta_value'] ?>" required>
                </div>
                 
                <div class="form-group">
                    <button class="btn btn-success btn-xs" type="submit">save</button>
                    <button class="btn btn-warning btn-xs" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
        </div>
    </section>
    </div>
</div>
