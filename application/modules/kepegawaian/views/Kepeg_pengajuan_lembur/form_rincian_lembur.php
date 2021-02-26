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
        // achtungShowLoader();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          reload_data();
        }else{
          // $.achtung({message: jsonResponse.message, timeout:5});
        }
        // achtungHideLoader();
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
          "url": "kepegawaian/Kepeg_pengajuan_lembur/rincian_lembur_dt?id="+$('#pengajuan_lembur_id').val()+"",
          "type": "POST"
      },

    });

})


function reload_data(){

  oTable.ajax.url("kepegawaian/Kepeg_pengajuan_lembur/rincian_lembur_dt?id="+$('#pengajuan_lembur_id').val()+"").load();

}

function delete_data(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'kepegawaian/Kepeg_pengajuan_lembur/delete_lembur',
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
          <form class="form-horizontal" method="post" id="form_kepeg_pengajuan_lembur" action="<?php echo site_url('kepegawaian/Kepeg_pengajuan_lembur/process_rincian_lembur')?>" enctype="multipart/form-data" autocomplete="off">
            <br>

            <input type="hidden" name="pengajuan_lembur_id" id="pengajuan_lembur_id" value="<?php echo isset($value) ? $value->pengajuan_lembur_id : '' ?>">
            
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
            <p><b>RINCIAN LEMBUR</b></p>

            <div class="form-group">
              <label class="control-label col-md-2">Ditugaskan di Unit/Bagian</label>
              <div class="col-md-3">
                <?php echo $this->master->custom_selection(array('table'=>'kepeg_mt_unit', 'where'=>array(), 'id'=>'kepeg_unit_id', 'name' => 'kepeg_unit_nama'),'','unit_tugas','unit_tugas','chosen-slect form-control','','');?>

              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Tanggal Lembur</label>
              <div class="col-md-2">
                <div class="input-group">
                    <input name="tgl_lembur" id="tgl_lembur" value="<?php echo date('Y-m-d') ?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                    <i class="ace-icon fa fa-calendar"></i>
                    </span>
                </div>
              </div>
              <label class="control-label col-md-1" style="margin-left: -10px">dari jam</label>
              <div class="col-md-2">
                <div class="input-group bootstrap-timepicker">
                  <input id="dari_jam" name="dari_jam" type="text" class="timepicker form-control" value="">
                  <span class="input-group-addon">
                    <i class="fa fa-clock-o bigger-110"></i>
                  </span>
                </div>
              </div>

              <label class="control-label col-md-1" style="margin-left: -10px">s/d jam</label>
              <div class="col-md-2">
                <div class="input-group bootstrap-timepicker">
                  <input id="sd_jam" name="sd_jam" type="text" class="timepicker form-control" value="">
                  <span class="input-group-addon">
                    <i class="fa fa-clock-o bigger-110"></i>
                  </span>
                </div>
              </div>

            </div>

            <div class="form-group" style="padding-bottom: 3px">
              <label class="control-label col-md-2">Deskripsi pekerjaan</label>
              <div class="col-md-8">
              <textarea name="deskripsi_pekerjaan" class="form-control" style="height:50px !important"></textarea>
              </div>
            </div>

            <div class="form-group" style="padding-bottom: 3px">
              <label class="col-md-2">&nbsp;</label>
              <div class="col-md-8">
                <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                  <i class="ace-icon fa fa-plus-circle icon-on-right bigger-110"></i>
                  Tambahkan Lembur
                </button>
              </div>
            </div>
            <br>
          </form>
          <table id="dynamic-table" base-url="kepegawaian/Kepeg_pengajuan_lembur" data-id="flag=" url-detail="kepegawaian/Kepeg_pengajuan_lembur/show_detail" class="table table-bordered table-hover">
              <thead>
                <tr>  
                  <th>No</th>
                  <th>Unit/Bagian Tugas</th>
                  <th>Tanggal</th>
                  <th>Dari Jam</th>
                  <th>s.d Jam</th>
                  <th>Jml Jam Lembur</th>
                  <th>Deskripsi Pekerjaan</th>
                  <th width="100px">Status</th>
                  <th width="150px">Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
        </div>
      </div>
    
    <!-- PAGE CONTENT ENDS -->

  </div><!-- /.col -->
</div><!-- /.row -->


