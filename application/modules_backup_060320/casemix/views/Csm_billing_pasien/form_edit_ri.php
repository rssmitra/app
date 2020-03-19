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
  document.getElementById('subtotal_tindakan_'+id+'').disabled = true;
  document.getElementById('nama_tindakan_'+id+'').disabled = true;
  total_sum_parent('subtotal_tindakan');
  executeChange(id);
}

function actived_form(id){
  preventDefault();
  document.getElementById('subtotal_tindakan_'+id+'').disabled = false;
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
    
    $('#form_Csm_billing_pasien').ajaxForm({
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
          //$('#page-area-content').load('casemix/Csm_billing_pasien');
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 
})

function hapus_file(a, b)

{

  if(b != 0){
    $.getJSON("<?php echo base_url('posting/delete_file') ?>/" + b, '', function(data) {
        document.getElementById("file"+a).innerHTML = "";
        greatComplate(data);
    });
  }else{
    y = a ;
    document.getElementById("file"+a).innerHTML = "";
  }

}

counterfile = <?php $j=1;echo $j.";";?>

function tambah_file()

{

counternextfile = counterfile + 1;

counterIdfile = counterfile + 1;

document.getElementById("input_file"+counterfile).innerHTML = "<div id=\"file"+counternextfile+"\"><div class='form-group'><label class='control-label col-md-2'>&nbsp;</label><div class='col-md-2'><input type='text' name='pf_file_name[]' id='pf_file_name' class='form-control'></div><label class='control-label col-md-1'>File</label><div class='col-md-2'><input type='file' id='pf_file' name='pf_file[]' class='upload_file form-control' /></div><div class='col-md-1'><input type='button' onclick='hapus_file("+counternextfile+",0)' value='x' class='btn btn-sm btn-danger'/></div></div></div><div id=\"input_file"+counternextfile+"\"></div>";

counterfile++;

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
          <form class="form-horizontal" method="post" id="form_Csm_billing_pasien" action="<?php echo site_url('casemix/Csm_billing_pasien/process')?>" enctype="multipart/form-data">
            <br>
            <?php if( count($reg) > 0 ) : ?>
            <div class="form-group">
              <label class="control-label col-md-2">No.SEP</label>
              <div class="col-md-3">
                <input name="csm_rp_no_sep" id="csm_rp_no_sep" value="<?php echo $reg->csm_rp_no_sep?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-1">No.Reg</label>
              <div class="col-md-1">
                <input name="no_registrasi" id="no_registrasi" value="<?php echo $reg->no_registrasi?>" class="form-control" type="text" disabled>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Tgl Masuk</label>
                <div class="col-md-3">
                  <div class="input-group">
                    <input class="form-control date-picker" name="csm_rp_tgl_masuk" id="csm_rp_tgl_masuk" type="text" data-date-format="yyyy-mm-dd" value="<?php echo $reg->csm_rp_tgl_masuk?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>

                <label class="control-label col-md-1">Tgl Keluar</label>
                <div class="col-md-3">
                  <div class="input-group">
                    <input class="form-control date-picker" name="csm_rp_tgl_keluar" id="csm_rp_tgl_keluar" type="text" data-date-format="yyyy-mm-dd" value="<?php echo $reg->csm_rp_tgl_keluar?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Nama Pasien</label>
              <div class="col-md-3">
                <input name="csm_rp_nama_pasien" id="csm_rp_nama_pasien" value="<?php echo $reg->csm_rp_nama_pasien?>" placeholder="" class="form-control" type="text" disabled>
              </div>
              <label class="control-label col-md-1">No.MR</label>
              <div class="col-md-1">
                <input name="csm_rp_no_mr" id="csm_rp_no_mr" value="<?php echo $reg->csm_rp_no_mr?>" class="form-control" type="text" disabled>
              </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-2">Nama Dokter</label>
              <div class="col-md-3">
                <input name="csm_rp_nama_dokter" id="csm_rp_nama_dokter" value="<?php echo $reg->csm_rp_nama_dokter?>" placeholder="" class="form-control" type="text" disabled>
              </div>
              <label class="control-label col-md-1">Klinik</label>
              <div class="col-md-3">
                <input name="csm_rp_bagian" id="csm_rp_bagian" value="<?php echo $reg->csm_rp_bagian?>" class="form-control" type="text" disabled>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">&nbsp;</label>
              <div class="col-md-6">
                <h3>Dokumen Tambahan</h3>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Nama Dokumen</label>
              <div class="col-md-2">
                <input name="pf_file_name[]" id="pf_file_name" class="form-control" type="text">
              </div>
              <label class="control-label col-md-1">File</label>
              <div class="col-md-2">
                <input type="file" id="pf_file" name="pf_file[]" class="upload_file form-control"/>
              </div>
              <div class ="col-md-1">
                <input onClick="tambah_file()" value="+" type="button" class="btn btn-sm btn-info" />
              </div>
            </div>
            <div id="input_file<?php echo $j;?>"></div>

            <!-- hidden form -->
            <input name="no_registrasi_hidden" id="no_registrasi_hidden" value="<?php echo $reg->no_registrasi?>" class="form-control" type="hidden">
            <input name="form_type" id="form_type" value="RI" class="form-control" type="hidden">
            <?php
              endif;
            ?>
            <!-- Content View Load From COntroller -->
            <?php echo $content_view?>
            <br>
            <br>
            
            <div class="center">
              <button onclick="getMenu('casemix/Csm_billing_pasien')" class="btn btn-sm btn-success">
                <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                Kembali ke daftar
              </button>
              <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
              <a href="http://localhost:88/rssm/sirs-v2/casemix/Csm_billing_pasien/mergePDFFiles/<?php echo isset($reg->no_registrasi)?$reg->no_registrasi:''?>/RI" target="_blank"  class="btn btn-sm btn-danger">
                <i class="ace-icon fa fa-pdf-file icon-on-right bigger-110"></i>
                Merge PDF Files
              </a>

            </div>
          </form>
        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


