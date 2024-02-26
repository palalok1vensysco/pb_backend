<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b></b> 
  </div>
  <strong>Copyright &copy; 2021 <a href="#">API Doc | <?=CONFIG_PROJECT_FULL_NAME?> App</a>.</strong> All rights
  reserved.
</footer>
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url();?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url();?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url();?>assets/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>assets/dist/js/demo.js"></script>


<!-- MODEL START -->
<?php if(isset($show_model)){ ?>
<input type="text" id="myModal_updation_show" value="1">
<input type="text" id="myModal_updation_msg" value="<?php if(isset($myModal_updation_msg)){  echo $myModal_updation_msg; }?>">

<div id="myModal_updation" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
          <h4 class="modal-title" id="mydiv"></h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
      </div>
    </div>
    <!-- MODEL END -->
      
<script>
    $(document).ready(function(){	
        var valu = $("#myModal_updation_show").val();
        var msgg = $("#myModal_updation_msg").val();
                if(valu==1){
            $("#myModal_updation").modal("toggle");
            $("#mydiv").html(msgg);
        }
    });
   // get_table_data
</script>

<?php  } ?>
