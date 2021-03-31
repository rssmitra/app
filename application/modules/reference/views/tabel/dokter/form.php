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
  
    $('#form_karyawan').ajaxForm({
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
          $('#page-area-content').load('reference/tabel/dokter?_=' + (new Date()).getTime());
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
              <form class="form-horizontal" method="post" id="form_karyawan" action="<?php echo site_url('reference/tabel/dokter/process')?>" enctype="multipart/form-data">
                <br>

                <div class="form-group">
                  <label class="control-label col-md-2">ID</label>
                  <div class="col-md-1">
                    <input name="id" id="id" value="<?php echo isset($value)?$value->kode_dokter:''?>" placeholder="Auto" class="form-control" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">No MR</label>
                  <div class="col-md-2">
                    <input name="no_mr" id="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Nama Dokter</label>
                  <div class="col-md-3">
                    <input name="nama_pegawai" id="nama_pegawai" value="<?php echo isset($value)?$value->nama_pegawai:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">No. SIP</label>
                  <div class="col-md-3">
                    <input name="no_sip" id="no_sip" value="<?php echo isset($value)?$value->no_sip:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Spesialisasi</label>
                  <div class="col-md-3">
                    <?php echo $this->master->custom_selection($params = array('table' => 'mt_spesialisasi_dokter', 'id' => 'kode_spesialisasi', 'name' => 'nama_spesialisasi', 'where' => array()), isset($value)?$value->kode_spesialisasi:'' , 'kode_spesialisasi', 'kode_spesialisasi', 'form-control', '', '') ?>
                  </div>
                </div>
                <br>
                <!-- tambahkan unit -->
                <p><b>UNIT TUGAS</b></p>
                <div class="form-group">
                  <label class="control-label col-md-2">Bagian/Unit</label>
                  <div class="col-md-4">
                    <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), isset($value)?$value->kode_bagian:'' , 'kodebagian', 'kodebagian', 'form-control', '', '') ?>
                  </div>
                </div>                
                <br>
                <!-- ttd dan stamp -->
                <p><b>TANDA TANGAN DOKTER DAN STEMPEL</b></p>
                <div class="form-group">
                  <label class="control-label col-md-2">Signature</label>
                  <div class="col-md-3">
                    <input name="ttd" id="ttd" value="" type="file" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Stamp</label>
                  <div class="col-md-3">
                    <input name="stamp" id="stamp" value="" type="file" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Status Aktif</label>
                  <div class="col-md-2">
                    <div class="radio">
                          <label>
                            <input name="status" type="radio" class="ace" value="1" <?php echo isset($value) ? ($value->status_dr == '1') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl"> Aktif</span>
                          </label>
                          <label>
                            <input name="status" type="radio" class="ace" value="0" <?php echo isset($value) ? ($value->status_dr == '0') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl">Tidak Aktif</span>
                          </label>
                    </div>
                  </div>
                </div>
                <div class="form-actions center">

                  <a onclick="getMenu('reference/tabel/dokter')" href="#" class="btn btn-sm btn-success">
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


