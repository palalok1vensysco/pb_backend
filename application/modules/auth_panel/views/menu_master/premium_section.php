<style>
    .panel-heading {
        background: #e9e9e9 none repeat scroll 0 0;
    }
</style>
<?php
$this->db->where('id', 1);
$info = $this->db->get('premium_section')->row();
?>

<div class="col-lg-4">
    <section class="panel">
        <header class="panel-heading custom-panel-heading">
            MENU ITEM
        </header>
        <div class="panel-body custom-panel-body">
            <form method="POST" action="" role="form">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label> Premium Section</label>
                        <select class="form-control input-sm" name="status">
                            <option value="1" <?= $info->status == 1 ? "selected" : ""; ?>>Enabled</option>
                            <option value="2" <?= $info->status == 2 ? "selected" : ""; ?>>Disabled</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to change?')" type="submit">save</button>
            </form>
        </div>
    </section>
</div>