<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>

<div class="col-lg-12 add_file_element" style="display: block;">
    <section class="panel">
        <header class="panel-heading custom-panel-heading text-white" style="background: var(--color1)!important">
           Add Season
        </header>
        <div class="panel-body bg-white p-2">
            <form autocomplete="off" role="form" method= "POST" enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="cat_name">Category <span style="color:#ff0000">*</span></label>
                    <select name="category_id" id="" class="form-control">
                        <?php foreach($categories as $category){?>
                            <option <?php if($shows['category_id'] == $category['id']){ echo 'selected'; } ?> value="<?= $category['id'] ?>"><?= $category['title']; ?></option>
                        <?php } ?>
                    </select>                    
                </div>                 
                <div class="form-group col-md-6">
                    <label for="cat_name">Title <span style="color:#ff0000">*</span></label>
                    <input type="text" name="title"   id = 'cate' required class="form-control input-sm m-bot15" maxlength='100' oninput="checkpricee(this)" placeholder="Enter Category Name" value="<?= $shows['title']; ?>">                 
                </div>                 
                <div class="form-group col-md-12" >                
                    <div class="col-md-4">
                    <label for="cat_name">Thumbnail <span style="color:#ff0000">*</span></label>
                        <input type="file" id="" name="image" onchange="$('#profile-image').attr('src', window.URL.createObjectURL(this.files[0]))" class="form-control-file border d-none">                            
                    </div>
                    <div class="col-md-4">
                        <img src="<?= $shows['thumbnail']; ?>" id="profile-image" style="width:100px;">
                    </div>                
               </div>             
            </div>
                <button type="submit" class="btn btn-sm display_color text-white f-600">Submit</button>
                <button class="btn  btn-sm display_color text-white f-600" onclick="$('.add_file_element').hide('slow');" type="button" >Cancel</button>
            </form>
        </div>
    </section>
</div>


