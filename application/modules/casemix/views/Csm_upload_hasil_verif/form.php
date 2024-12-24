<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script>
function preventDefault(e) {
  e = e || window.event;
  if (e.preventDefault)
      e.preventDefault();
  e.returnValue = false;  
}

function deactive_form(id){
  preventDefault();
  document.getElementById('subtotal_tindakan_'+id+'').value = 0;
  document.getElementById('subtotal_tindakan_'+id+'').readonly = true;
  document.getElementById('nama_tindakan_'+id+'').disabled = true;
  total_sum_parent('subtotal_tindakan');
  executeChange(id);
}

function actived_form(id){
  preventDefault();
  document.getElementById('subtotal_tindakan_'+id+'').readonly = false;
  document.getElementById('nama_tindakan_'+id+'').disabled = false;
  document.getElementById('subtotal_tindakan_'+id+'').value = document.getElementById('subtotal_ori_'+id+'').value;
  total_sum_parent('subtotal_tindakan');
  executeChange(id);
}

function executeChange(id){
  total_sum_parent('subtotal_tindakan');
  var jenis_tindakan = document.getElementById("jenis_tindakan_"+id+"").value;
  var kode_bagian = document.getElementById("kode_bagian_"+id+"").value;
  var subtotal = document.getElementById("subtotal_tindakan_"+id+"").value;
  resumeBilling(jenis_tindakan, kode_bagian, subtotal);
}

function total_sum_parent(classname){
  var items = document.getElementsByClassName(""+classname+"");
  var itemCount = items.length;
  var total = 0;
  for(var i = 0; i < itemCount; i++)
  {
     total = total +  parseInt(items[i].value);
  }
  document.getElementById(classname).value = total;
  document.getElementById("total_resume").value = total;
}

function total_resume_billing(classname){
  var items = document.getElementsByClassName(""+classname+"");
  var itemCount = items.length;
  var total = 0;
  for(var i = 0; i < itemCount; i++)
  {
     total = total +  parseInt(items[i].value);
  }
  return total;
}

function resumeBilling(jenis_tindakan, kode_bagian, subtotal){
  /*dokter*/
    if (jenis_tindakan==12) {
      bill_dr = total_resume_billing("jenis_tindakan_"+jenis_tindakan+"");
      document.getElementById("bill_dr").value = bill_dr;
    }
  /*obat farmasi*/
    if (jenis_tindakan==11) {
      bill_far = total_resume_billing("jenis_tindakan_"+jenis_tindakan+"");
      document.getElementById("bill_far").value = bill_far;
    }
  /*adm*/
    if (jenis_tindakan==2 || jenis_tindakan==13) {
      bill_adm = total_resume_billing("jenis_tindakan_"+jenis_tindakan+"");
      document.getElementById("bill_adm").value = bill_adm;
    }
  /*pm*/
    str_pm = kode_bagian.substring(0,2);
    if(str_pm == '05'){
        if (jenis_tindakan==3) {
            bill_pm = total_resume_billing("jenis_tindakan_"+jenis_tindakan+"");
            document.getElementById("bill_pm").value = bill_pm;
        }
    }else{
        var bill_pm = 0;
        /*tindakan*/
        if (jenis_tindakan==3) {
            bill_tindakan = total_resume_billing("jenis_tindakan_"+jenis_tindakan+"");
            document.getElementById("bill_tindakan").value = bill_tindakan;
        }
    }
    
}

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
    
    $('#form_upload_hasil_verif').ajaxForm({
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
          getMenu('casemix/Csm_upload_hasil_verif');
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
          <form class="form-horizontal" method="post" id="form_upload_hasil_verif" action="<?php echo site_url('casemix/Csm_upload_hasil_verif/process')?>" enctype="multipart/form-data">

            <div class="form-group">
              <label class="control-label col-md-2">Kode</label>
              <div class="col-md-1">
                <input name="id" id="id" class="form-control" type="text" placeholder="Auto" readonly>
              </div>
              <label class="control-label col-md-1">Petugas</label>
              <div class="col-md-2">
                <input name="" id="" class="form-control" type="text" value="<?php echo $this->session->userdata('user')->fullname?>">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Periode Tanggal</label>
              <div class="col-md-2">
                <input name="start_date" id="start_date" class="form-control date-picker" data-date-format="yyyy-mm-dd" type="text" value="<?php echo date('Y-m-d')?>">
              </div>
              <label class="control-label col-md-1">sd Tanggal</label>
              <div class="col-md-2">
                <input name="to_date" id="to_date" class="form-control date-picker" data-date-format="yyyy-mm-dd" type="text" value="<?php echo date('Y-m-d')?>">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Periode Klaim Bulan</label>
              <div class="col-md-2">
                <select name="csm_uhv_month_periode" id="csm_uhv_month_periode" class="form-control">
                  <option value="">-Silahkan Pilih-</option>
                  <?php
                    for($month=1;$month<13;$month++){
                      $selected = ($month==date('m'))?'selected':'';
                      echo '<option value="'.$month.'" '.$selected.'>'.$this->tanggal->getBulan($month).'</option>';    
                    }
                  ?>
                  
                </select>
              </div>
              <label class="control-label col-md-1">Tahun</label>
              <div class="col-md-2" >
                <select name="csm_uhv_year" id="csm_uhv_year" class="form-control">
                  <option value="">-Silahkan Pilih-</option>
                   <?php
                      for($year=date('Y')-4;$year<=date('Y');$year++){
                         $selected = ($year==date('Y'))?'selected':'';
                        echo '<option value="'.$year.'" '.$selected.'>'.$year.'</option>';    
                      }
                    ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Pilih File Upload</label>
              <div class="col-md-3">
                <input type="file" id="csm_uhv_file" name="csm_uhv_file" class="upload_file form-control"/>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Keterangan</label>
              <div class="col-md-5">
                <textarea class="form-control" style="height: 50px !important">Hasil Klaim NCC BPJS Bulan <?php echo $this->tanggal->getBulan(date('m'))?></textarea>
              </div>
            </div>
            <hr>
            <b><h4>DOKUMEN UPLOAD</h4></b>
            <?php echo $attachment; ?>

            <div class="form-actions center">

              <a onclick="getMenu('casemix/Csm_upload_hasil_verif')" href="#" class="btn btn-sm btn-success">
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


