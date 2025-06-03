<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

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

    $('#form_rm_pasien').ajaxForm({
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
          getMenu('rekam_medis/Rm_pasien/form/'+$('#no_registrasi_hidden').val()+'/'+$('#form_type').val()+'');
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 

    $('#pl_diagnosa').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getICD10",
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
        var label_item=item.split(':')[1];
        var val_item=item.split(':')[0];
        console.log(val_item);
        $('#pl_diagnosa').val(label_item);
        $('#pl_diagnosa_hidden').val(val_item);
        }

    });
    
    $('#btn_barcode_pasien').click(function (e) {   
      var no_mr = $('#no_mr').val();
      if( no_mr == '' ){
        alert('Silahkan cari pasien terlebih dahulu !'); return false;
      }else{
        url = 'registration/Reg_pasien/barcode_pasien/'+no_mr+'/1';
        title = 'Cetak Barcode';
        width = 600;
        height = 450;
        PopupCenter(url, title, width, height);
      }
    });


})

function get_riwayat_medis(){

noMr = $('#no_mr').val();
if (noMr == '') {
  alert('Silahkan cari pasien terlebih dahulu !'); return false;
}else{
  getMenuTabs('registration/Reg_pasien/get_riwayat_medis/'+noMr, 'tabs_detail_pasien');
}

}

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

<p><b><i class="fa fa-edit"></i> ASSESMENT PASIEN </b></p>
<div class="form-group">
    <label class="control-label col-sm-2" for="">Tinggi Badan (cm)</label>
    <div class="col-sm-1">
        <input type="text" class="form-control" name="pl_tb" value="<?php echo isset($riwayat->tinggi_badan)?$riwayat->tinggi_badan:''?>">
    </div>
    <label class="control-label col-sm-2" for="">Berat Badan (Kg)</label>
    <div class="col-sm-1">
        <input type="text" class="form-control" name="pl_bb" value="<?php echo isset($riwayat->berat_badan)?$riwayat->berat_badan:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Tekanan Darah</label>
    <div class="col-sm-1">
        <input type="text" class="form-control" name="pl_td" value="<?php echo isset($riwayat->tekanan_darah)?$riwayat->tekanan_darah:''?>">
    </div>
    <label class="control-label col-sm-2" for="">Suhu Tubuh</label>
    <div class="col-sm-1">
        <input type="text" class="form-control" name="pl_suhu" value="<?php echo isset($riwayat->suhu)?$riwayat->suhu:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Nadi</label>
    <div class="col-sm-1">
        <input type="text" class="form-control" name="pl_nadi" value="<?php echo isset($riwayat->nadi)?$riwayat->nadi:''?>">
    </div>
</div>

<br>
<p><b><i class="fa fa-stethoscope bigger-120"></i>  DIAGNOSA DAN PEMERIKSAAN </b></p>

<div>
    <label for="form-field-8">Diagnosa (ICD10) <span style="color:red">* </span></label>
    <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
    <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
</div>

<div style="margin-top: 6px">
    <label for="form-field-8">Anamnesa <span style="color:red">* </span> <small>(minimal 8 karakter)</small> </label>
    <textarea class="form-control" name="pl_anamnesa" style="height: 100px !important"><?php echo isset($riwayat->anamnesa)?$this->master->br2nl($riwayat->anamnesa):''?></textarea>
    <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">
</div>

<div class="row">
    <div class="col-md-6" style="margin-top: 6px">
        <label for="form-field-8">Pemeriksaan </label>
        <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pemeriksaan)?$this->master->br2nl($riwayat->pemeriksaan):''?></textarea>
    </div>

    <div class="col-md-6" style="margin-top: 6px">
        <label for="form-field-8">Anjuran Dokter </label>
        <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pengobatan)?$this->master->br2nl($riwayat->pengobatan):''?></textarea>
    </div>
</div>

<hr>
<p><b><i class="fa fa-upload bigger-120"></i>  DOKUMEN PENUNJANG MEDIS PASIEN LAINNYA </b></p>
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
<br>
<p><b><i class="fa fa-file bigger-120"></i>  FILE REKAM MEDIS PASIEN </b></p>
<?php echo $attachment; ?>

<br><br>
<!-- <div class="form-actions center">

    <a onclick="getMenu('rekam_medis/Rm_pasien')" href="#" class="btn btn-sm btn-success">
    <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
    Kembali ke daftar
    </a>
    <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info" value="submit">
    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
    Submit
    </button>

    <button type="submit" id="btnUpdateDokKlaim" name="submit" class="btn btn-sm btn-warning" value="update_dok_klaim">
    <i class="ace-icon fa fa-files-o icon-on-right bigger-110"></i>
    Update Dokumen Rekam Medis
    </button>

    <a href="<?php echo base_url()?>casemix/Csm_billing_pasien/mergePDFFiles/<?php echo isset($reg->no_registrasi)?$reg->no_registrasi:''?>/RJ" target="_blank"  class="btn btn-sm btn-danger">
    <i class="ace-icon fa fa-pdf-file icon-on-right bigger-110"></i>
    Merge PDF Files
    </a>
</div> -->
