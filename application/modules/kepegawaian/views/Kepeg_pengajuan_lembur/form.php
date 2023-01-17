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
          $('#page-area-content').load('kepegawaian/Kepeg_pengajuan_lembur?_=' + (new Date()).getTime());
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
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
          <form class="form-horizontal" method="post" id="form_kepeg_pengajuan_lembur" action="<?php echo site_url('kepegawaian/Kepeg_pengajuan_lembur/process')?>" enctype="multipart/form-data" autocomplete="off">
            <br>

            <input type="hidden" name="id" value="<?php echo isset($value) ? $value->pengajuan_lembur_id : '' ?>">
            
            <p><b>DATA PRIBADI</b></p>
            <div class="form-group">
              <label class="control-label col-md-2">Nama Pegawai</label>
              <div class="col-md-3">
                <input name="nama_pegawai" id="inputNamaPegawai" value="<?php echo isset($value) ? $value->nama_pegawai : '' ?>" class="form-control" type="text">
                <!-- hidden id pegawai -->
                <input name="kepeg_id" id="kepeg_id" value="<?php echo isset($value) ? $value->kepeg_id : '' ?>" class="form-control" type="hidden">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Tahun</label>
              <div class="col-md-2">
                <?php echo $this->master->get_tahun(isset($value->tahun)?$value->tahun:date('Y') , 'tahun', 'tahun', 'form-control', '', '') ?> 
              </div>
              <label class="control-label col-md-1">Periode Bulan</label>
              <div class="col-md-2">
              <?php echo $this->master->get_bulan(isset($value->periode_lembur_bln)?$value->periode_lembur_bln:date('m') , 'periode_lembur_bln', 'periode_lembur_bln', 'form-control', '', '') ?> 
              </div>
            </div>

            <div class="form-group" style="padding-bottom: 3px">
              <label class="control-label col-md-2">Keterangan</label>
              <div class="col-md-4">
              <textarea name="keterangan" class="form-control" <?php echo ($flag=='read')?'readonly':''?> style="height:50px !important"><?php echo isset($value)?$value->keterangan:''?></textarea>
              </div>
            </div>

            <div class="form-actions center">
              <a onclick="getMenu('kepegawaian/Kepeg_pengajuan_lembur')" href="#" class="btn btn-sm btn-success">
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


