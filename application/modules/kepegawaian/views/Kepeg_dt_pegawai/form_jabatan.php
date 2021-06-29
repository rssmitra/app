<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    dateFormat: 'yyyy-mm-dd',
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

});

$(document).ready(function(){
  
  $('#form_kepeg_dt_pegawai').ajaxForm({
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
        $('#page-area-content').load('kepegawaian/Kepeg_dt_pegawai?_=' + (new Date()).getTime());
      }else{
        $.achtung({message: jsonResponse.message, timeout:5});
      }
      achtungHideLoader();
    }
  }); 

  $('select[name="kepeg_status_kerja"]').change(function(){

    if ($(this).val() == 212) {
      $('#div_status_kerja').show('fast');
    } else {
      $('#div_status_kerja').hide('fast');
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
        <form class="form-horizontal" method="post" id="form_kepeg_dt_pegawai" action="<?php echo site_url('kepegawaian/Kepeg_dt_pegawai/process_update_kepegawaian')?>" enctype="multipart/form-data" autocomplete="off">
          <br>
          <!-- hidden form -->
          <input type="hidden" name="kepeg_id" value="<?php echo $value->kepeg_id?>">

          <p><b>DATA PEGAWAI</b></p>

          <div class="form-group" id="status_kepegawaian">
            <label class="control-label col-md-2">NIP</label>
            <div class="col-md-1">
              <input type="text" name="kepeg_nip" value="<?php echo isset($value->kepeg_nip)?$value->kepeg_nip:''?>" class="form-control">
            </div>
          </div>
          <div class="form-group" id="status_kepegawaian">
            <label class="control-label col-md-2">NIK</label>
            <div class="col-md-2">
              <input type="text" name="kepeg_nik" value="<?php echo isset($value->kepeg_nik)?$value->kepeg_nik:''?>" class="form-control">
            </div>
          </div>

          <div class="form-group" id="status_kepegawaian">
            <label class="control-label col-md-2">Nama Pegawai</label>
            <div class="col-md-3">
              <input type="text" name="nama_pegawai" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:''?>" class="form-control">
            </div>
          </div>

          <div class="form-group" id="status_kepegawaian">
            <label class="control-label col-md-2">No. Telp/HP</label>
            <div class="col-md-2">
              <input type="text" name="kepeg_no_telp" value="<?php echo isset($value->kepeg_no_telp)?$value->kepeg_no_telp:''?>" class="form-control">
            </div>
            <label class="control-label col-md-1">Email</label>
            <div class="col-md-2">
              <input type="text" name="kepeg_email" value="<?php echo isset($value->kepeg_email)?$value->kepeg_email:''?>" class="form-control">
            </div>
          </div>

          <div class="form-group" id="pendidikan_terakhir">
            <label class="control-label col-md-2">Pendidikan Terakhir</label>
            <div class="col-md-2">
              <?php echo $this->master->custom_selection($params = array('table' => 'mst_education', 'id' => 'education_id', 'name' => 'education_name', 'where' => array('is_active' => 'Y')), isset($value)?$value->kepeg_pendidikan_terakhir:'' , 'kepeg_pendidikan_terakhir', 'kepeg_pendidikan_terakhir', 'form-control', '', '') ?> 
            </div>
          </div>

          <div class="form-group" id="pas_foto">
            <label class="control-label col-md-2">Pas Foto</label>
            <div class="col-md-2">
              <input type="file" name="pas_foto" value="<?php echo isset($value->ktp_foto)?$value->ktp_foto:''?>" class="form-control">
            </div>
          </div>

          <?php if(isset($value->pas_foto)) :?>

          <div class="form-group">
            <label class="control-label col-md-2">&nbsp;</label>
            <div class="col-md-4">
              <img style="max-width:150px" class="editable img-responsive" alt="" id="avatar2" src="<?php echo base_url().PATH_PHOTO_PEGAWAI.$value->pas_foto?>" />
            </div>
          </div>

          <?php endif;?>

          <p style="padding-top: 10px"><b>PANGKAT/GOLONGAN DAN JABATAN PEGAWAI</b></p>
          <div class="form-group" id="unit">
            <label class="control-label col-md-2">Unit/Bagian</label>
            <div class="col-md-2">
              <?php echo $this->master->custom_selection($params = array('table' => 'kepeg_mt_unit', 'id' => 'kepeg_unit_id', 'name' => 'kepeg_unit_nama', 'where' => array()), isset($value)?$value->kepeg_unit:'' , 'kepeg_unit', 'kepeg_unit', 'form-control', '', '') ?> 
            </div>
          </div>
          <div class="form-group" id="level">
            <label class="control-label col-md-2">Level Jabatan</label>
            <div class="col-md-2">
              <?php echo $this->master->custom_selection($params = array('table' => 'kepeg_mt_level', 'id' => 'kepeg_level_id', 'name' => 'kepeg_level_nama', 'where' => array()), isset($value)?$value->kepeg_level:'' , 'kepeg_level', 'kepeg_level', 'form-control', '', '') ?> 
            </div>
          </div>
          <div class="form-group" id="level">
            <label class="control-label col-md-2">Golongan</label>
            <div class="col-md-2">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'gol_pegawai')), isset($value)?$value->kepeg_gol:'' , 'kepeg_gol', 'kepeg_gol', 'form-control', '', '') ?> 
            </div>
          </div>
          <div class="form-group" id="hak_keperawatan">
            <label class="control-label col-md-2">Hak Keperawatan</label>
            <div class="col-md-2">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_klas', 'id' => 'kode_klas', 'name' => 'nama_klas', 'where' => array()), isset($value)?$value->kepeg_hak_perawatan:'' , 'kepeg_hak_perawatan', 'kepeg_hak_perawatan', 'form-control', '', '') ?> 
            </div>
          </div>

          <p style="padding-top: 10px"><b>STATUS DAN MASA KERJA PEGAWAI</b></p>
          <div class="form-group" id="jenis_pegawai">
            <label class="control-label col-md-2">Jenis Pegawai</label>
            <div class="col-md-8">
              <div class="radio">
                    <label>
                      <input name="kepeg_tenaga_medis" type="radio" class="ace" value="medis" <?php echo isset($value) ? ($value->kepeg_tenaga_medis == 'medis') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                      <span class="lbl"> Tenaga Medis</span>
                    </label>
                    <label>
                      <input name="kepeg_tenaga_medis" type="radio" class="ace" value="non medis" <?php echo isset($value) ? ($value->kepeg_tenaga_medis == 'non medis') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                      <span class="lbl"> Tenaga Non Medis</span>
                    </label>
              </div>
            </div>
          </div>

          <div class="form-group" id="status_kepegawaian">
            <label class="control-label col-md-2">Status Kepegawaian</label>
            <div class="col-md-2">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'status_kepegawaian')), isset($value)?$value->kepeg_status_kerja:'' , 'kepeg_status_kerja', 'kepeg_status_kerja', 'form-control', '', '') ?> 
            </div>
          </div>

          <div class="form-group" ID="tgl_aktif_kerja">
            <label class="control-label col-md-2">Tanggal Aktif Kerja</label>
            <div class="col-md-2">
              <div class="input-group">
                  <input name="kepeg_tgl_aktif" id="kepeg_tgl_aktif" value="<?php echo isset($value)?$this->tanggal->formatDateForm($value->kepeg_tgl_aktif):''?>"  data-date-format="yyyy-mm-dd"  class="form-control date-picker" type="text">
                  <span class="input-group-addon">
                  <i class="ace-icon fa fa-calendar"></i>
                  </span>
              </div>
            </div>
          </div>
          
          <!-- not-shown for karyawan tetap -->

          <div id="div_status_kerja" style="<?php echo isset($value->kepeg_status_kerja) ? ($value->kepeg_status_kerja == 212) ? '' : 'display : none' : 'display : none' ;?>">
            <div class="form-group" id="masa_kontrak">
              <label class="control-label col-md-2">Masa Kontrak (bulan)</label>
              <div class="col-md-2">
                <input type="text" name="kepeg_masa_kontrak" value="<?php echo isset($value->kepeg_masa_kontrak)?$value->kepeg_masa_kontrak:''?>" class="form-control">
              </div>
            </div>

            <div class="form-group" id="tgl_akhir_kerja">
              <label class="control-label col-md-2">Tanggal Berakhir Kerja</label>
              <div class="col-md-2">
                <div class="input-group">
                    <input name="kepeg_tgl_selesai" id="kepeg_tgl_selesai" value="<?php echo isset($value)?$this->tanggal->formatDateForm($value->kepeg_tgl_selesai):''?>" data-date-format="yyyy-mm-dd"  class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                    <i class="ace-icon fa fa-calendar"></i>
                    </span>
                </div>
              </div>
            </div>
          </div>
          <!-- end of not-shown -->

          <div class="form-group" id="status_aktif">
            <label class="control-label col-md-2">Status Aktif Pegawai</label>
            <div class="col-md-2">
              <div class="radio">
                    <label>
                      <input name="kepeg_status_aktif" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->kepeg_status_aktif == 'Y') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                      <span class="lbl"> Aktif</span>
                    </label>
                    <label>
                      <input name="kepeg_status_aktif" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->kepeg_status_aktif == 'N') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                      <span class="lbl">Tidak Aktif</span>
                    </label>
              </div>
            </div>
          </div>
          
          <hr>

          <div class="form-actions center">
            <a onclick="getMenu('kepegawaian/Kepeg_dt_pegawai')" href="#" class="btn btn-sm btn-success">
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


