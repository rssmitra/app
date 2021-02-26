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
  
    $('#form_kepeg_pengajuan_cuti').ajaxForm({
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
          $('#page-area-content').load('kepegawaian/Kepeg_persetujuan_cuti?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

  $('#inputNamaPegawai').typeahead({
    source: function (query, result) {
        $.ajax({
            url: "Templates/References/getPegawaiAktif",
            data: 'keyword=' + query,             
            dataType: "json",
            type: "POST",
            success: function (response) {
              result($.map(response, function (item) {
                  return item;
              }));
            }
        });
    },
    afterSelect: function (item) {
      // do what is needed with item
      var val_item=item.split(':')[0];
      var label_item=item.split(':')[1];
      $('#kepeg_id').val(val_item);
      $('#inputNamaPegawai').val(label_item);
           
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
          <form class="form-horizontal" method="post" id="form_kepeg_pengajuan_cuti" action="<?php echo site_url('kepegawaian/Kepeg_persetujuan_cuti/process')?>" enctype="multipart/form-data" autocomplete="off">
            <br>

            <input type="hidden" name="pengajuan_cuti_id" value="<?php echo isset($value) ? $value->pengajuan_cuti_id : '' ?>">
            <input type="hidden" name="id" value="<?php echo isset($value) ? $value->log_acc_id : '' ?>">
            
            <p><b>DATA PENGAJUAN CUTI</b></p>

            <div class="profile-user-info profile-user-info-striped">
              <div class="profile-info-row">
                <div class="profile-info-name"> Kode </div>
                <div class="profile-info-value">
                  <?php echo isset($value) ? $this->tanggal->formatDatedmY($value->tgl_pengajuan_cuti) : '' ?> 
                </div>
              </div>
              <div class="profile-info-row">
                <div class="profile-info-name"> Tanggal Pengajuan </div>
                <div class="profile-info-value">
                  <?php echo isset($value) ? $this->tanggal->formatDatedmY($value->tgl_pengajuan_cuti) : '' ?> 
                </div>
              </div>
              <div class="profile-info-row">
                <div class="profile-info-name"> Nama Pegawai </div>

                <div class="profile-info-value">
                  <?php echo isset($value) ? $value->nama_pegawai : '' ?>
                </div>
              </div>

              <div class="profile-info-row">
                <div class="profile-info-name"> Tanggal Cuti </div>

                <div class="profile-info-value">
                  <?php echo isset($value) ? $this->tanggal->formatDatedmY($value->cuti_dari_tgl) : '' ?> s.d <?php echo isset($value) ? $this->tanggal->formatDatedmY($value->cuti_sd_tgl) : '' ?>
                </div>
              </div>

              <div class="profile-info-row">
                <div class="profile-info-name"> Jenis Cuti </div>

                <div class="profile-info-value">
                  <?php echo isset($value) ? $value->jenis_cuti : '' ?>
                </div>
              </div>

              <div class="profile-info-row">
                <div class="profile-info-name"> Alasan Cuti </div>

                <div class="profile-info-value">
                  <?php echo isset($value) ? $value->alasan_cuti : '' ?>
                </div>
              </div>

            </div>

            <hr>

            <div class="col-xs-6">
              <p><b>PERSETUJUAN ATASAN</b></p>

              <div class="form-group">
                <label class="control-label col-md-4">Nama Atasan</label>
                <div class="col-md-6">
                  <input name="nama_pegawai" id="inputNamaPegawai" value="<?php echo isset($value) ? $value->acc_by_name : '' ?>" class="form-control" type="text">
                  <!-- hidden id pegawai -->
                  <input name="acc_by_kepeg_id" id="acc_by_kepeg_id" value="<?php echo isset($value) ? $value->acc_by_kepeg_id : '' ?>" class="form-control" type="hidden">

                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-4">Tanggal Persetujuan</label>
                <div class="col-md-4">
                  <div class="input-group">
                      <input name="tgl_persetujuan" id="tgl_persetujuan" value="<?php echo date('Y-m-d') ?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text">
                      <span class="input-group-addon">
                      <i class="ace-icon fa fa-calendar"></i>
                      </span>
                  </div>
                </div>
              </div>

              <div class="form-group" style="padding-bottom: 3px">
                <label class="control-label col-md-4">Catatan</label>
                <div class="col-md-8">
                <textarea name="catatan" class="form-control" style="height:50px !important"></textarea>
                </div>
              </div>

              <div class="form-group" id="status_aktif">
                <label class="control-label col-md-4">Persetujuan</label>
                <div class="col-md-8">
                  <div class="radio">
                      <label>
                        <input name="acc_status" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->acc_status == 'Y') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                        <span class="lbl"> Setuju</span>
                      </label>
                      <label>
                        <input name="acc_status" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->acc_status == 'N') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                        <span class="lbl">Tidak Setuju</span>
                      </label>
                  </div>
                </div>
              </div>

              <div class="form-actions center">

                <a onclick="getMenu('kepegawaian/Kepeg_persetujuan_cuti')" href="#" class="btn btn-sm btn-success">
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
            </div>

            <div class="col-xs-6">
              <div class="timeline-container timeline-style2">
                <span class="timeline-label">
                  <b>Persetujuan Pengajuan Cuti</b>
                </span>

                <div class="timeline-items">
                  <?php foreach($acc_flow as $row_flow_acc) :?>
                    <div class="timeline-item clearfix">
                      <div class="timeline-info">
                        <span class="timeline-date">
                        <?php echo (empty($row_flow_acc['acc_date']))?'<i class="fa fa-times-circle red bigger-120"></i>':'<i class="fa fa-check-circle green bigger-120"></i> '.$this->tanggal->formatDatedmY($row_flow_acc['acc_date']).'';?>
                        </span>

                        <i class="timeline-indicator btn btn-info no-hover"></i>
                      </div>

                      <div class="widget-box transparent">
                        <div class="widget-body">
                          <div class="widget-main no-padding">
                            <span class="bigger-110">
                              <a href="#" class="purple bolder"><?php echo $row_flow_acc['nama_pegawai']?></a>
                            </span>

                            <br>
                            <?php echo $row_flow_acc['unit']?>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>

                </div><!-- /.timeline-items -->
              </div>
            </div>

          </form>
        </div>
      </div>
    
    <!-- PAGE CONTENT ENDS -->

  </div><!-- /.col -->
</div><!-- /.row -->


