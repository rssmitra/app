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
  
    $('#form_highseason').ajaxForm({
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
          $('#page-area-content').load('mbl_reseller/C_highseason_calendar?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 
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
              <form class="form-horizontal" method="post" id="form_highseason" action="<?php echo site_url('mbl_reseller/C_highseason_calendar/process')?>" enctype="multipart/form-data">
                <br>

                <div class="form-group">
                  <label class="control-label col-md-2">ID</label>
                  <div class="col-md-1">
                    <input name="id" id="id" value="<?php echo isset($value)?$value->id:0?>" placeholder="Auto" class="form-control" type="text" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Category</label>
                  <div class="col-md-3">
                    <select name="category" id="" class="form-control">
                      <option value="1">Public Holiday</option>
                      <option value="0">Highseason</option>
                      <option value="2">Closed</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Principal</label>
                  <div class="col-md-3">
                    <?php echo $this->master->custom_selection_with_db_selected(array('table'=>'m_principals', 'where'=>array('is_active'=>'Y'), 'id'=>'id', 'name' => 'principal_name'),isset($value)?$value->principal_id:'','principal_id','principal_id','chosen-slect form-control',($flag=='read')?'readonly':'','', 'db_mbr');?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Start Date</label>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input class="form-control date-picker" name="date" id="date" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value)?$value->date:''?>"/>
                        <span class="input-group-addon">
                          <i class="fa fa-calendar bigger-110"></i>
                        </span>
                      </div>
                    </div>
                  <label class="control-label col-md-1">End Date</label>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input class="form-control date-picker" name="end_date" id="end_date" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value)?$value->end_date:''?>"/>
                        <span class="input-group-addon">
                          <i class="fa fa-calendar bigger-110"></i>
                        </span>
                      </div>
                    </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Description</label>
                  <div class="col-md-4">
                    <textarea class="form-control" name="title" id="title" style="height: 100px !important" <?php echo ($flag=='read')?'readonly':''?>><?php echo isset($value)?$value->title:''?></textarea>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Is active?</label>
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

                <div class="form-group">
                  <label class="control-label col-md-2">Last update</label>
                  <div class="col-md-8" style="padding-top:8px;font-size:11px">
                      <i class="fa fa-calendar"></i> <?php echo isset($value->updated_date)?$this->tanggal->formatDateTime($value->updated_date):isset($value)?$this->tanggal->formatDateTime($value->created_date):date('d-M-Y H:i:s')?> - 
                      by : <i class="fa fa-user"></i> <?php echo isset($value->updated_by)?$value->updated_by:isset($value->created_by)?$value->created_by:$this->session->userdata('user')->username?>
                  </div>
                </div>

                <div class="form-actions center">
                  <a onclick="getMenu('mbl_reseller/C_highseason_calendar')" href="#" class="btn btn-sm btn-success">
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


