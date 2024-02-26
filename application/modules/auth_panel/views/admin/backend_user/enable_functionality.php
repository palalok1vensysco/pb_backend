<div class="col-lg-12 px-0">
    <section class="panel">
        <header class="panel-heading">
            <?= $page_title; ?>
        </header>
        <div class="panel-body">
            
            <div class="row">
                <div class="col-lg-4" data-id="1">
                    <section class="card">
                        <header class="card-header">
                            Modules
                        </header>
                        <div class="card-body">
                         <form method="POST" id="candidateForm" name="appform" enctype="multipart/form-data"  autocomplete="off">
                            <?php    
                            $f_list = functionality_list($this);  
                           //  print_r($f_list);die;
                            $f_list_s =json_decode($f_list_s,true);
                          //  print_r($f_list_s);die;
                                    foreach($f_list as $f){
                             ?>
                                <li class="dd-item">
                                    <label><input type="checkbox" name="<?php echo str_replace(' ','_',$f) ; ?>" value="1" <?php if(isset($f_list_s[str_replace(' ','_',$f)]) && $f_list_s[str_replace(' ','_',$f)]=="1"){
                                        echo "checked";}?>> <?php echo $f;?></label>
                                </li> 
                                <?php } ?>

                                  <div class="sbu_btn1">
                                    <button type="submit" name="test" value="submit" class="btn btn-xs btn-primary">Submit</button>     
                                </div>
                                </form>    
                            </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
</div>