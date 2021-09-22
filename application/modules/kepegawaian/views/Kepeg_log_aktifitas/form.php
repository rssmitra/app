<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    dateFormat: 'yy-mm-dd',
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

$(document).ready(function(){
  
    $('#form_kepeg_log_aktifitas').ajaxForm({
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
          $('#page-area-content').load('kepegawaian/Kepeg_log_aktifitas?_=' + (new Date()).getTime());
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
          <form class="form-horizontal" method="post" id="form_kepeg_log_aktifitas" action="<?php echo site_url('kepegawaian/Kepeg_log_aktifitas/process')?>" enctype="multipart/form-data" autocomplete="off">
            <br>

            <!-- hidden form -->
            <input type="hidden" name="id" value="<?php echo isset($value->id)?$value->id:0?>">

            <div class="form-group">
              <label class="control-label col-md-2">Nama Pegawai</label>
              <div class="col-md-3">
                <input name="nama_pegawai" id="nama_pegawai" <?php echo ($flag=='read')?'readonly':''?> value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:$this->session->userdata('user')->fullname?>" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Tgl Pekerjaan</label>
              <div class="col-md-2">
                <div class="input-group">
                    <input name="tanggal" id="tanggal" <?php echo ($flag=='read')?'readonly':''?> value="<?php echo isset($value->tanggal)?$this->tanggal->formatDateForm($value->tanggal):''?>"  class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                    <i class="ace-icon fa fa-calendar"></i>
                    </span>
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-bottom: 3px">
              <label class="control-label col-md-2">Deskripsi Pekerjaan</label>
              <div class="col-md-8">
              <textarea name="deskripsi_pekerjaan" class="form-control" <?php echo ($flag=='read')?'readonly':''?> style="height:100px !important"><?php echo isset($value->deskripsi_pekerjaan)?$this->master->br2nl($value->deskripsi_pekerjaan):''?></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Jenis Pekerjaan</label>
              <div class="col-md-2">
                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_pekerjaan')), isset($value->jenis_pekerjaan)?$value->jenis_pekerjaan:'' , 'jenis_pekerjaan', 'jenis_pekerjaan', 'form-control', '', '') ?> 
              </div>

              <label class="control-label col-md-1">Status</label>
              <div class="col-md-2">
                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'status_pekerjaan')), isset($value->status_pekerjaan)?$value->status_pekerjaan:'' , 'status_pekerjaan', 'status_pekerjaan', 'form-control', '', '') ?>
              </div>
            </div>

            <div class="form-group" style="padding-bottom: 3px">
              <label class="control-label col-md-2">Catatan</label>
              <div class="col-md-6">
                <textarea name="catatan" class="form-control" <?php echo ($flag=='read')?'readonly':''?> style="height:70px !important"><?php echo isset($value->catatan)?$value->catatan:''?></textarea>
              </div>
            </div>

          <div class="form-group">
            <label class="control-label col-md-2">Is Active?</label>
            <div class="col-md-2">
              <div class="radio">
                    <label>
                      <input name="is_active" type="radio" class="ace" value="Y" <?php echo isset($value->is_active) ? ($value->is_active == 'Y') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                      <span class="lbl"> Ya</span>
                    </label>
                    <label>
                      <input name="is_active" type="radio" class="ace" value="N" <?php echo isset($value->is_active) ? ($value->is_active == 'N') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                      <span class="lbl"> Tidak</span>
                    </label>
              </div>
            </div>
          </div>


          <div class="form-actions center">
            <a onclick="getMenu('kepegawaian/Kepeg_log_aktifitas')" href="#" class="btn btn-sm btn-success">
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


