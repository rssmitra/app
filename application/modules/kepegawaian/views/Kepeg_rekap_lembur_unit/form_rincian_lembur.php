<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>

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

  $('.timepicker').timepicker({
      minuteStep: 1,
      showSeconds: true,
      showMeridian: false,
      disableFocus: true,
      icons: {
        up: 'fa fa-chevron-up',
        down: 'fa fa-chevron-down'
      }
    }).on('focus', function() {
      $('#timepicker1').timepicker('showWidget');
    }).next().on(ace.click_event, function(){
      $(this).prev().focus();
    });
});

$(document).ready(function(){
    
    $('#form_kepeg_pengajuan_lembur').ajaxForm({
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
          $('#page-area-content').load('kepegawaian/Kepeg_persetujuan_lembur/form_rincian_lembur/' + $('#pengajuan_lembur_id').val());
          // reload_table();

        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
       "ajax": {
          "url": "kepegawaian/Kepeg_persetujuan_lembur/rincian_lembur_dt?id="+$('#pengajuan_lembur_id').val()+"",
          "type": "POST"
      },

    });

})


function reload_data(){

  oTable.ajax.url("kepegawaian/Kepeg_persetujuan_lembur/rincian_lembur_dt?id="+$('#pengajuan_lembur_id').val()+"").load();

}

function delete_data(myid){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'kepegawaian/Kepeg_persetujuan_lembur/delete_lembur',
        type: "post",
        data: {ID:myid},
        dataType: "json",
        beforeSend: function() {
          // achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            // $.achtung({message: jsonResponse.message, timeout:5});
            reload_table();
          }else{
            // $.achtung({message: jsonResponse.message, timeout:5});
          }
          // achtungHideLoader();
        }

      });

  }else{
    return false;
  }
  
}

function get_dt_update(myid){
  preventDefault();
  $.ajax({
      url: 'kepegawaian/Kepeg_persetujuan_lembur/get_dt_by_id',
      type: "post",
      data: {ID:myid},
      dataType: "json",
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        $('#unit_tugas').val(jsonResponse.unit_tugas);
        $('#dari_jam').val(jsonResponse.dari_jam);
        $('#sd_jam').val(jsonResponse.sd_jam);
        $('#deskripsi_pekerjaan').val(jsonResponse.deskripsi_pekerjaan);
        $('#tgl_lembur').val(jsonResponse.tgl_lembur);
        $('#lembur_dtl_id').val(jsonResponse.lembur_dtl_id);
        $('#tgl_lembur').val(jsonResponse.tgl_lembur);
        $('#tgl_lembur').val(jsonResponse.tgl_lembur);
      }

  });
  
}

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
          <form class="form-horizontal" method="post" id="form_kepeg_pengajuan_lembur" action="<?php echo site_url('kepegawaian/Kepeg_persetujuan_lembur/process')?>" enctype="multipart/form-data" autocomplete="off">
            <br>

            <input type="hidden" name="pengajuan_lembur_id" id="pengajuan_lembur_id" value="<?php echo isset($value) ? $value->pengajuan_lembur_id : '' ?>">
            <input type="hidden" name="id" value="<?php echo isset($value) ? $value->log_acc_id : '' ?>">
            
            <p><b>DATA PENGAJUAN LEMBUR</b></p>

            <div class="profile-user-info profile-user-info-striped">
              <div class="profile-info-row">
                <div class="profile-info-name"> Kode </div>
                <div class="profile-info-value">
                  <?php echo isset($value) ? $this->tanggal->formatDatedmY($value->tgl_pengajuan_lembur) : '' ?> 
                </div>
              </div>
              <div class="profile-info-row">
                <div class="profile-info-name"> Tanggal Pengajuan </div>
                <div class="profile-info-value">
                  <?php echo isset($value) ? $this->tanggal->formatDatedmY($value->tgl_pengajuan_lembur) : '' ?> 
                </div>
              </div>
              <div class="profile-info-row">
                <div class="profile-info-name"> Nama Pegawai </div>

                <div class="profile-info-value">
                  <?php echo isset($value) ? $value->nama_pegawai : '' ?>
                </div>
              </div>

              <div class="profile-info-row">
                <div class="profile-info-name"> Periode Lembur Bulan </div>

                <div class="profile-info-value">
                  <?php echo isset($value) ? $this->tanggal->getBulan($value->periode_lembur_bln) : '' ?>
                </div>
              </div>

              <div class="profile-info-row">
                <div class="profile-info-name"> Keterangan </div>

                <div class="profile-info-value">
                  <?php echo isset($value) ? $value->keterangan : '' ?>
                </div>
              </div>

            </div>
            <br>
            <div class="col-xs-8">
              <p><b>PERSETUJUAN ATASAN</b></p>

              <div class="form-group">
                <label class="control-label col-md-2">Nama Atasan</label>
                <div class="col-md-4">
                  <input name="nama_pegawai" id="inputNamaPegawai" value="<?php echo isset($value) ? $value->acc_by_name : '' ?>" class="form-control" type="text">
                  <!-- hidden id pegawai -->
                  <input name="acc_by_kepeg_id" id="acc_by_kepeg_id" value="<?php echo isset($value) ? $value->acc_by_kepeg_id : '' ?>" class="form-control" type="hidden">

                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-2">Tgl Persetujuan</label>
                <div class="col-md-3">
                  <div class="input-group">
                      <input name="tgl_persetujuan" id="tgl_persetujuan" value="<?php echo date('Y-m-d') ?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text">
                      <span class="input-group-addon">
                      <i class="ace-icon fa fa-calendar"></i>
                      </span>
                  </div>
                </div>
              </div>

              <div class="form-group" style="padding-bottom: 3px">
                <label class="control-label col-md-2">Catatan</label>
                <div class="col-md-4">
                <textarea name="catatan" class="form-control" style="height:50px !important"></textarea>
                </div>
              </div>

              <div class="form-group" id="status_aktif">
                <label class="control-label col-md-2">Persetujuan</label>
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
              
              <!-- rincian lembur -->
              <table id="dynamic-table" base-url="kepegawaian/Kepeg_persetujuan_lembur" data-id="flag=" url-detail="kepegawaian/Kepeg_persetujuan_lembur/show_detail" class="table table-bordered table-hover">
                  <thead>
                    <tr style="background-color: #edf3f4">  
                      <th width="30px" class="center">
                        <div class="center">
                          <label class="pos-rel">
                              <input type="checkbox" class="ace" name="" onClick="checkAll(this);" value="0"/>
                              <span class="lbl"></span>
                          </label>
                        </div>
                      </th>
                      <th class="center">No</th>
                      <th>Tanggal</th>
                      <th>Dari Jam</th>
                      <th>s.d Jam</th>
                      <th>Jml Jam Lembur</th>
                      <th>Deskripsi Pekerjaan</th>
                      <th width="100px">Acc</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>

              <div class="form-actions center">
                <a onclick="getMenu('kepegawaian/Kepeg_persetujuan_lembur')" href="#" class="btn btn-sm btn-success">
                  <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                  Kembali ke daftar
                </a>
                <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Submit
                </button>
                <button type="button" id="btnReload" onclick="reload_table()" class="btn btn-sm btn-default">
                  <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                  Reload Tabel
                </button>
              </div>
            </div>

            <div class="col-xs-4">
              <div class="timeline-container timeline-style2">
                <span class="timeline-label">
                  <b>ATASAN LANGSUNG PEGAWAI</b>
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
            <br>
          </form>
          
        </div>
      </div>
    
    <!-- PAGE CONTENT ENDS -->

  </div><!-- /.col -->
</div><!-- /.row -->


