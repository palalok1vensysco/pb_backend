<div class="col-md-12 no-padding" id="add_suggestion_category">
    <section class="panel">
        <header class="panel-headingtext-white bg-dark">Edit Category</header>
        <div class="panel-body">
            <form role="form" method="POST">
                <div class="col-md-12">
                    <div class="col-sm-6 form-group">
                        <label>Category Name*</label>
                        <input type="text" class="form-control input-xs" value="<?= $data['name']; ?>" name="name" placeholder="Enter Name" required >
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>Category Name(Hindi)*</label>
                        <input type="text" class="form-control input-xs" value="<?= $data['name_2']; ?>" name="name_2" placeholder="Enter Name In Hindi" required >
                    </div>
                    <button type="submit" class="btn-xs btn-info">Update Category</button>
                </div>
            </form>
        </div>
    </section>
</div>
