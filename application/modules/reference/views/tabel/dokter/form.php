<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: 'dd-mm-yyyy'   // TAMBAHAN
  })
  // show datepicker when clicking on the icon
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
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
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
                
        <?php
        $masa_sip = '';
        if (!empty($value->masa_berlaku_sip) && $value->masa_berlaku_sip != '0000-00-00') {
            $masa_sip = date('d-m-Y', strtotime($value->masa_berlaku_sip));
        }
        ?>
        <div class="form-group">
          <label class="control-label col-md-2">Masa Berlaku SIP</label>
          <div class="col-md-3">
            <input name="masa_berlaku_sip" id="masa_berlaku_sip" value="<?php echo ($flag == 'read' && empty($masa_sip)) ? 'Belum diisi' : $masa_sip; ?>" class="form-control date-picker" type="text" placeholder="dd-mm-yyyy"<?php echo ($flag == 'read') ? 'readonly' : ''; ?>
            >
          </div>
        </div>
        
                <div class="form-group">
                  <label class="control-label col-md-2">Spesialisasi</label>
                  <div class="col-md-3">
                    <?php echo $this->master->custom_selection($params = array('table' => 'mt_spesialisasi_dokter', 'id' => 'kode_spesialisasi', 'name' => 'nama_spesialisasi', 'where' => array()), isset($value)?$value->kode_spesialisasi:'' , 'kode_spesialisasi', 'kode_spesialisasi', 'form-control', '', '') ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Tipe Dokter</label>
                  <div class="col-md-8">
                    <div class="radio">
                          <label>
                            <input name="status_dr" type="radio" class="ace" value="0" <?php echo isset($value) ? ($value->status_dr == '0') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl"> Junior</span>
                          </label>
                          <label>
                            <input name="status_dr" type="radio" class="ace" value="1" <?php echo isset($value) ? ($value->status_dr == '1') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl">Senior</span>
                          </label>
                          <label>
                            <input name="status_dr" type="radio" class="ace" value="2" <?php echo isset($value) ? ($value->status_dr == '2') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl">Professor</span>
                          </label>
                    </div>
                  </div>
                </div>

                <br>
                <!-- tambahkan unit (close) -->
                <!-- <p><b>UNIT TUGAS</b></p>
                <div class="form-group">
                  <label class="control-label col-md-2">Bagian/Unit</label>
                  <div class="col-md-4">
                    <?php //echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), isset($value)?$value->kode_bagian:'' , 'kodebagian', 'kodebagian', 'form-control', '', '') ?>
                  </div>
                </div>   -->

                  <!-- tambahkan unit update -->
                  <p><b>UNIT TUGAS</b></p>
                  <div class="form-group">
                      <label class="control-label col-md-2">Bagian/Unit</label>
                      <div class="col-md-8">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalUnit">
                              Pilih Unit
                            </button>
                            <span id="unit_text" style="margin-left:10px;color:#555"></span>
                      </div>
                  </div>

                 <!-- status kedinasan -->
                <div class="form-group">
                  <label class="control-label col-md-2">Status Kedinasan</label>
                  <div class="col-md-8">
                    <div class="radio">
                          <label>
                            <input name="is_active" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->is_active == 'Y') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl"> Aktif</span>
                          </label>
                          <label>
                            <input name="is_active" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->is_active == 'N') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl">Tidak Aktif</span>
                          </label>
                    </div>
                  </div>
                </div>              
                <br>


                <!-- ttd dan stamp -->
                <p><b>TANDA TANGAN DOKTER DAN STEMPEL</b></p>
                <div class="form-group">
                  <label class="control-label col-md-2">Foto Profil</label>
                  <div class="col-md-3">
                    <input name="foto" id="foto" value="" type="file" class="form-control">
                  </div>
                </div>
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


<div class="modal fade" id="modalUnit">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
  
  <button type="button" class="close" data-dismiss="modal">
    &times;
  </button>

      <div class="modal-header">
        <h4 class="modal-title">Pilih Unit Dokter</h4>
      </div>

      <div class="modal-body" style="max-height:400px;overflow:auto">

        <?php 
          $unit_selected = [];
          if(isset($value)){
            $unit_selected = $this->dokter->get_unit_by_dokter($value->kode_dokter);
          }

          $units = $this->db->get('mt_bagian')->result();
          foreach($units as $u):
        ?>
          <label class="pos-rel" style="width:32%;display:inline-block">
            <input type="checkbox" class="ace" name="kodebagian[]" value="<?= $u->kode_bagian ?>"
              <?= in_array($u->kode_bagian, $unit_selected) ? 'checked' : '' ?>>
            <span class="lbl"><?= $u->nama_bagian ?></span>
          </label>
        <?php endforeach; ?>

      </div>

      <div class="modal-footer">
    <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">
      Simpan 
    </button>
    </div>

<script>
function refreshUnitText(){
  let arr = [];
  $('input[name="kodebagian[]"]:checked').each(function(){
    arr.push($(this).parent().text().trim());
  });
  $('#unit_text').text(arr.join(' | '));
}

$('input[name="kodebagian[]"]').change(function(){
  refreshUnitText();
});

$(document).ready(function(){
  refreshUnitText();
});
</script>

    </div>
  </div>
</div>

              </form>
            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


