<script>
function toggleSecretCode(el) {
  var input = el.parentNode.querySelector('.secret-code-field');
  var icon = el.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}
</script>
<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <div class="clearfix" style="margin-bottom:-5px">
      <?php echo $this->authuser->show_button('setting/Tmp_approval_process_config','C','',1)?>
      <?php echo $this->authuser->show_button('setting/Tmp_approval_process_config','D','',5)?>
      <?php echo $this->authuser->show_button('setting/Tmp_approval_process_config','EX','',1)?>

    </div>
    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="setting/Tmp_approval_process_config" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center"></th>
          <th width="120px">&nbsp;</th>
          <th width="50px">ID</th>
          <th width="150px">Function</th>
          <th width="150px">User</th>
          <th width="100px">Secret Code</th>
          <th>Description</th>
          <th width="100px">Status</th>
          <th width="100px">Last Update</th>
          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



