<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

$(document).ready(function(){
  
    $('#form_user_approval_modul').ajaxForm({
      beforeSend: function() {
        achtungShowLoader();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          $.achtung({message: jsonResponse.message, timeout:5});
          $('#page-area-content').load('setting/Tmp_approval_process_config?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }
        achtungHideLoader();
      }
    }); 

    if(!ace.vars['touch']) {
        $('.chosen-select').chosen({allow_single_deselect:true}); 
        //resize the chosen on window resize

        $(window)
        .off('resize.chosen')
        .on('resize.chosen', function() {
          $('.chosen-select').each(function() {
              var $this = $(this);
              $this.next().css({'width': $this.parent().width()});
          })
        }).trigger('resize.chosen');

  }

})

</script>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">

              <form class="form-horizontal" method="post" id="form_user_approval_modul" action="<?php echo site_url('setting/Tmp_approval_process_config/process') ?>" enctype="multipart/form-data">
                <br>
                <div class="form-group">
                  <label class="control-label col-md-2">ID</label>
                  <div class="col-md-2">
                    <input name="id" id="id" value="<?php echo isset($value)?$value->id:0?>" placeholder="Auto" class="form-control" type="text" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">User ID</label>
                  <div class="col-md-4">
                    <?php echo $this->master->custom_selection(array('table'=>'tmp_user', 'where'=>array('is_active'=>'Y'), 'id'=>'user_id', 'name' => 'fullname'),isset($value)?$value->user_id:'','user_id','user_id','chosen-select form-control',($flag=='read')?'readonly':'','');?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Function</label>
                  <div class="col-md-3">
                    <input name="function" id="function" value="<?php echo isset($value)?$value->function:''?>" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Secret Code</label>
                  <div class="col-md-2">
                    <input name="secret_code" id="secret_code" value="<?php echo isset($value)?$value->secret_code:str_pad(mt_rand(0, pow(10, 6)-1), 6, '0', STR_PAD_LEFT)?>" class="form-control" type="text" readonly >
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Description</label>
                  <div class="col-md-8">
                    <input name="description" id="description" value="<?php echo isset($value)?$value->description:''?>" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Is Active?</label>
                  <div class="col-md-2">
                    <div class="radio">
                      <label>
                        <input name="is_active" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->is_active == 'Y') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                        <span class="lbl"> Ya</span>
                      </label>
                      <label>
                        <input name="is_active" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->is_active == 'N') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                        <span class="lbl">Tidak</span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-actions center">
                  <a onclick="getMenu('setting/Tmp_approval_process_config')" href="#" class="btn btn-sm btn-success">
                    <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                    Kembali ke daftar
                  </a>
                  <?php if($flag != 'read'):?>
                  <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                    <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                    Reset
                  </button>
                  <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Submit
                  </button>
                  <?php endif; ?>
                </div>
              </form>

            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


