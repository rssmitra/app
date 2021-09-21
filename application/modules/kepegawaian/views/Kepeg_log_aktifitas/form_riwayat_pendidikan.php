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

          <p><b>RIWAYAT PEKERJAAN PEGAWAI</b></p>

          <!-- <div> -->
          <!-- Data Umum Pegawai -->
            <div id="user-profile-1" class="user-profile row">
              <div class="col-xs-12 col-sm-3 center">
                <div>
                  <span class="profile-picture">
                    <img id="" class="" alt="Foto Pegawai" src="<?php echo isset($value->ktp_foto)?$value->ktp_foto:'' ?>" />
                  </span>
                </div>
              </div>
              <div class='col-xs-12 col-sm-9'>
                <div class="form-group" id="status_kepegawaian">
                  <label class="control-label col-md-2">NIP</label>
                  <div class="col-md-2">
                    <input type="text" name="kepeg_nip" value="<?php echo isset($value->kepeg_nip)?$value->kepeg_nip:''?>" class="form-control">
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
                  <div class="col-md-3">
                    <input type="text" name="kepeg_no_telp" value="<?php echo isset($value->kepeg_no_telp)?$value->kepeg_no_telp:''?>" class="form-control">
                  </div>
                </div>
                <div class="form-group" id="status_kepegawaian">
                  <label class="control-label col-md-2">Email</label>
                  <div class="col-md-3">
                    <input type="text" name="kepeg_email" value="<?php echo isset($value->kepeg_email)?$value->kepeg_email:''?>" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          <!-- </div> -->
          <hr>

          <p style="padding-top: 10px"><b>FORM RIWAYAT PENDIDIKAN PEGAWAI</b></p>
          <div class="form-group">
            <label class="control-label col-md-2">Nama Lembaga Pendidikan</label>
            <div class="col-md-5">
              <input name="lembaga_pendidikan" id="lembaga_pendidikan" value="<?php echo isset($value->lembaga_pendidikan)?$value->lembaga_pendidikan:''?>" class="form-control" type="text">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2">Jenjang Pendidikan</label>
              <div class="col-md-3">
                <input name="jenjang_pendidikan" id="jenjang_pendidikan" value="<?php echo isset($value->jenjang_pendidikan)?$value->jenjang_pendidikan:''?>" class="form-control" type="text">
              </div>
            <label class="control-label col-md-1">Tahun Lulus</label>
              <div class="col-md-1">
                <div class="input-group">
                    <input name="tahun_lulus" id="tahun_lulus" value="<?php echo isset($value->tahun_lulus)?$this->tanggal->formatDateForm($value->tahun_lulus):''?>"  data-date-format="yyyy-mm-dd"  class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                    <i class="ace-icon fa fa-calendar"></i>
                    </span>
                    <!-- <input placeholder="Tahun Keluar" name="masa_kerja" id="masa_kerja" value="<?php // echo isset($value->masa_kerja)?$this->tanggal->formatDateForm($value->masa_kerja):''?>"  data-date-format="yyyy-mm-dd"  class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                    <i class="ace-icon fa fa-calendar"></i>
                    </span> -->
                </div>
              </div>
          </div>
          <div class="form-group" ID="masa_kerja">
          </div>

          <br>

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

        <hr class="separator">
        
        <div style="margin-top:-27px">
          <table id="dynamic-table" base-url="kepegawaian/Kepeg_dt_pegawai" data-id="flag=" url-detail="kepegawaian/Kepeg_dt_pegawai/show_detail" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th width="30px" class="center">
                  <div class="center">
                    <label class="pos-rel">
                        <input type="checkbox" class="ace" name="" onClick="checkAll(this);" value="0"/>
                        <span class="lbl"></span>
                    </label>
                  </div>
                </th>
                <th width="30px" class="center"></th>
                <th width="30px" class="center"></th>
                <th width="30px" class="center">No</th>
                <th>Lembaga Pendidikan</th>
                <th width="200px" class="center">Jenjang Pendidikan</th>
                <th width="150px" class="center">Tahun Lulus</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <!-- PAGE CONTENT ENDS -->

  </div><!-- /.col -->
</div><!-- /.row -->


