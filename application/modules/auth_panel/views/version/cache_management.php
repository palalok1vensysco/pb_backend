<?php
$this->db->where('id', 1);
$info = $this->db->get('version_control')->row();
?>
 <section class="panel">
        <header class="panel-heading">
           Cache Management
        </header>
        <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="<?= AUTH_PANEL_URL . "version_control/version/cache_management" ?>" >
                <div class="form-group col-md-4">
                    <label>ES_UT_009</label> 
                   <select name="ES_UT_009" class="form-control input-xs" >
                            <option value="0" <?=(!$versions)?"selected":""?>>NO</option>
                            <option value="1"  <?=($versions)?"selected":""?>>YES</option>
                    </select>
                </div>
                 
                <div class="form-group col-md-12">
                    <button class="btn btn-success btn-xs" type="submit">save</button>
                    <button class="btn btn-warning btn-xs" type="button" onclick="window.location.reload();">Cancel</button>
                </div>
            </form>
        </div>
    </section>

