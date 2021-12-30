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
  
    $('#form-receipt-obat').ajaxForm({
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
          $('#page-area-content').load('farmasi/Ao_receipt?_=' + (new Date()).getTime());
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
              <form class="form-horizontal" method="post" id="form-receipt-obat" action="<?php echo site_url('farmasi/Ao_receipt/process')?>" enctype="multipart/form-data">
                <br>
                <input type="hidden" name="kode" value="<?php echo isset($value)?$value->kode_trans_far:''?>">
                <!-- Data Pickup -->
                <p><b>PICKUP</b></p>
                <div class="form-group">
                  <label class="control-label col-md-2">Kode Transaksi</label>
                  <div class="col-md-1" style="margin-left: 10px; font-size: 14px">
                    <b><?php echo isset($value)?$value->kode_trans_far:''?></b>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">No MR</label>
                  <div class="col-md-2" style="margin-left: 10px; font-size: 14px">
                    <b><?php echo isset($value)?$value->no_mr:''?></b>
                  </div>
                  <label class="control-label col-md-2">Nama Pasien</label>
                  <div class="col-md-3" style="margin-left: 10px; font-size: 14px">
                    <b><?php echo isset($value)?$value->nama_pasien:''?></b>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Pickup</label>
                  <div class="col-md-10" style="margin-left: 10px; font-size: 14px">
                    <b><?php echo isset($value)?$this->tanggal->formatDateTimeFormDmy($value->pickup_time):''?> - <?php echo isset($value)?$value->pickup_by:''?></b>
                  </div>
                </div>

                <hr>
                <p><b>PENERIMAAN OBAT</b></p>
                <div class="form-group">
                  <label class="control-label col-md-2">Jarak Tempuh</label>
                  <div class="col-md-8">
                    <div class="radio">
                          <label>
                            <input name="distance" type="radio" class="ace" value="10" <?php echo isset($value) ? ($value->distance == '10') ? 'checked="checked"' : '' : 'checked="checked"'; ?> />
                            <span class="lbl"> 1 - 3 Km (10rb)</span>
                          </label>
                          <label>
                            <input name="distance" type="radio" class="ace" value="15" <?php echo isset($value) ? ($value->distance == '15') ? 'checked="checked"' : '' : ''; ?> />
                            <span class="lbl"> 3 - 5 Km (15rb)</span>
                          </label>
                          <label>
                            <input name="distance" type="radio" class="ace" value="20" <?php echo isset($value) ? ($value->distance == '20') ? 'checked="checked"' : '' : ''; ?> />
                            <span class="lbl"> > 5 Km (20rb)</span>
                          </label>
                    </div>
                  </div>
                </div>  

                <div class="form-group">
                  <label class="control-label col-md-2">Penerima</label>
                  <div class="col-md-3">
                    <input name="received_by" id="received_by" value="<?php echo isset($value->received_by)?$value->received_by:$value->nama_pasien?>" placeholder="" class="form-control" type="text" >
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Keterangan</label>
                  <div class="col-md-5">
                    <textarea name="note" id="note" class="form-control" style="height: 50px !important"><?php echo isset($value)?$value->note:''?></textarea>
                  </div>
                </div>

                <div class="form-actions center" style="margin-top: 10px">

                  <a onclick="getMenu('farmasi/Ao_receipt')" href="#" class="btn btn-sm btn-success">
                    <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                    Kembali ke daftar
                  </a>
                  <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Submit
                  </button>
                </div>
              </form>
            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


