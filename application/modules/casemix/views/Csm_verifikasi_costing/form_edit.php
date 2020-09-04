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
          getMenu('casemix/Csm_verifikasi_costing/editBilling/'+$('#no_registrasi_hidden').val()+'/'+$('#form_type').val()+'');
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    $('select[name="poliklinik"]').change(function () {      
        var text_label_poli = $('select[name="poliklinik"] option:selected').text();
        $('#csm_rp_bagian').val(text_label_poli);
        $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val(), '', function (data) {  
            $('#select_dokter option').remove();  
            $('<option value="">-Pilih Dokter-</option>').appendTo($('#select_dokter'));
            $.each(data, function (i, o) {        
                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#select_dokter'));       
            });   
        });    

    }); 

    $('select[name="select_dokter"]').change(function () {      
        var text_label_dr = $('select[name="select_dokter"] option:selected').text();
        $('#csm_rp_nama_dokter').val(text_label_dr); 
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

document.getElementById("input_file"+counterfile).innerHTML = "<div id=\"file"+counternextfile+"\"><div class='form-group'><label class='col-md-2'>&nbsp;</label><div class='col-md-2'><input type='text' name='pf_file_name[]' id='pf_file_name' class='form-control'></div><label class='control-label col-md-1'>Pilih File</label><div class='col-md-3'><input type='file' id='pf_file' name='pf_file[]' class='upload_file form-control' /></div><div class='col-md-1' style='margin-left:-2.5%'><input type='button' onclick='hapus_file("+counternextfile+",0)' value='x' class='btn btn-sm btn-danger'/></div></div></div><div id=\"input_file"+counternextfile+"\"></div>";

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
            <b><h4>DATA REGISTRASI PASIEN</h4></b>

            <div class="form-group">
              <label class="control-label col-md-2">No. Registrasi</label>
              <div class="col-md-1">
                <input name="no_registrasi" id="no_registrasi" value="<?php echo $reg->no_registrasi?>" class="form-control" type="text" readonly>
              </div>
              <label class="control-label col-md-1">No. MR</label>
              <div class="col-md-2">
                <input name="csm_rp_no_mr" id="csm_rp_no_mr" value="<?php echo $reg->csm_rp_no_mr?>" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Nama Pasien</label>
              <div class="col-md-4">
                <input name="csm_rp_nama_pasien" id="csm_rp_nama_pasien" value="<?php echo $reg->csm_rp_nama_pasien?>" placeholder="" class="form-control" type="text" >
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">No. SEP</label>
              <div class="col-md-3">
                <input name="csm_rp_no_sep" id="csm_rp_no_sep" value="<?php echo $reg->csm_rp_no_sep?>" class="form-control" type="text">
              </div>
            </div>

            
            <div class="form-group">
              <label class="control-label col-md-2">Tanggal Masuk</label>
                <div class="col-md-2">
                  <div class="input-group">
                    <input class="form-control date-picker" name="csm_rp_tgl_masuk" id="csm_rp_tgl_masuk" type="text" data-date-format="yyyy-mm-dd" value="<?php echo $reg->csm_rp_tgl_masuk?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>

                <label class="control-label col-md-2">Tanggal Keluar</label>
                <div class="col-md-2">
                  <div class="input-group">
                    <input class="form-control date-picker" name="csm_rp_tgl_keluar" id="csm_rp_tgl_keluar" type="text" data-date-format="yyyy-mm-dd" value="<?php echo $reg->csm_rp_tgl_keluar?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Poli/Klinik</label>
              <div class="col-md-4">
                <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1, 'status_aktif' => 1)), isset($reg->csm_rp_kode_bagian)?$reg->csm_rp_kode_bagian:'' , 'poliklinik', 'poliklinik', 'form-control', '', '') ?>
                <input name="csm_rp_bagian" id="csm_rp_bagian" value="<?php echo $reg->csm_rp_bagian?>" class="form-control" type="hidden">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Dokter</label>
              <div class="col-md-4">
                <?php echo $this->master->get_change($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), isset($reg->csm_rp_kode_dokter)?$reg->csm_rp_kode_dokter:'' , 'select_dokter', 'select_dokter', 'form-control', '', '') ?>
                <input name="csm_rp_nama_dokter" id="csm_rp_nama_dokter" value="<?php echo $reg->csm_rp_nama_dokter?>" placeholder="" class="form-control" type="hidden" >
              </div>
            </div>

            <hr>
            <b><h4>DOKUMEN KLAIM TAMBAHAN</h4></b>
            <div class="form-group">
              <label class="control-label col-md-2">Nama Dokumen</label>
              <div class="col-md-2">
                <input name="pf_file_name[]" id="pf_file_name" class="form-control" type="text">
              </div>
              <label class="control-label col-md-1">Pilih File</label>
              <div class="col-md-3">
                <input type="file" id="pf_file" name="pf_file[]" class="upload_file form-control"/>
              </div>
              <div class ="col-md-1" style="margin-left:-2.5%">
                <input onClick="tambah_file()" value="+" type="button" class="btn btn-sm btn-info" />
              </div>
            </div>

            <div id="input_file<?php echo $j;?>"></div>
            
            <b><h4>DOKUMEN UPLOAD</h4></b>
            <?php echo $attachment; ?>

            <input name="no_registrasi_hidden" id="no_registrasi_hidden" value="<?php echo $no_registrasi?>" class="form-control" type="hidden">
            <input name="form_type" id="form_type" value="RJ" class="form-control" type="hidden">

            <br><br>
            <div class="form-actions center">

              <a onclick="getMenu('casemix/Csm_verifikasi_costing')" href="#" class="btn btn-sm btn-success">
                <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                Kembali ke daftar
              </a>
              <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
              <a href="<?php echo base_url()?>casemix/Csm_billing_pasien/mergePDFFiles/<?php echo isset($reg->no_registrasi)?$reg->no_registrasi:''?>/RJ" target="_blank"  class="btn btn-sm btn-danger">
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


