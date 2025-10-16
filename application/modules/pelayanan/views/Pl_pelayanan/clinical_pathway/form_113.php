<script>

jQuery(function($) {  
   // Unbind event lama (penting!)
  $('.date-picker').datepicker('destroy');

  // Inisialisasi ulang dengan opsi yang sama
  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: 'dd/mm/yyyy'
  }).on('show', function(e) {
    // Pastikan hanya satu instance tampil
    $('.datepicker').not($(this).data('datepicker').picker).remove();
  });

  $('#diagnosis').typeahead({
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
        $('#diagnosis').val(label_item);
      }

  });

});
</script>

<?php echo $header; ?>
<hr>
<br>
<div style="text-align: center; font-size: 18px;">
  <b><u>SURAT KETERANGAN RAWAT INAP</u></b><br>
</div>
<div style="text-align: center; font-size: 13px; margin-top: -5px">Nomor. <?php echo isset($result->reg_data->no_registrasi)?$result->reg_data->no_registrasi:''?>.SKS/<?php echo date('d')?>-<?php echo date('m')?>/<?php echo date('Y')?></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<p>
  Yang bertanda tangan dibawah ini menerangkan bahwa :<br>
  <table width="100%">
    <tr>
      <td width="100px">No. RM</td>
      <td colspan="2" width="200px">
        <input type="text" class="input_type" style="width: 100px !important" name="form_113[no_mr_pasien_istirahat]" id="no_mr_pasien_istirahat" onchange="fillthis('no_mr_pasien_istirahat')" value="<?php $no_mr = isset($data_pasien->no_mr)?$data_pasien->no_mr:''; echo isset($value_form['no_mr_pasien_istirahat'])?$value_form['no_mr_pasien_istirahat']:$no_mr?>">
      </td>
    </tr>
    <tr>
      <td>Nama Pasien</td>
      <td>
        <input type="text" class="input_type" style="width: 250px !important" name="form_113[nama_pasien_istirahat]" id="nama_pasien_istirahat" onchange="fillthis('nama_pasien_istirahat')" value="<?php $nama_pasien = isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; echo isset($value_form['nama_pasien_istirahat'])?$value_form['nama_pasien_istirahat']:$nama_pasien?>">
      </td>
      <td>
        <label>
          <input type="checkbox" class="ace" name="form_113[jk_l]" id="jk_l"  onclick="checkthis('jk_l')" <?php $jen_kelamin = isset($data_pasien->jen_kelamin)?$data_pasien->jen_kelamin:''; echo ($jen_kelamin == 'L')?"checked":"";?>>
          <span class="lbl" > Laki-laki</span>
        </label>
        
        <label>
          <input type="checkbox" class="ace" name="form_113[jk_p]" id="jk_p"  onclick="checkthis('jk_p')" <?php $jen_kelamin = isset($data_pasien->jen_kelamin)?$data_pasien->jen_kelamin:''; echo ($data_pasien->jen_kelamin == 'P')?"checked":"";?>>
          <span class="lbl" > Perempuan</span>
        </label>

      </td>
    </tr>
    <tr>
      <td>Umur</td>
      <td colspan="2">
        <input type="text" class="input_type" style="width: 50px !important" name="form_113[umur_pasien]" id="umur_pasien" onchange="fillthis('umur_pasien')" value="<?php $umur = isset($data_pasien->umur)?$data_pasien->umur:''; echo isset($value_form['umur_pasien'])?$value_form['umur_pasien']:$data_pasien->umur?>">
      </td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td colspan="2">
        <input type="text" class="input_type" style="width: 100% !important" name="form_113[alamat_pasien]" id="alamat_pasien" onchange="fillthis('alamat_pasien')" value="<?php $almt_ttp_pasien = isset($data_pasien->almt_ttp_pasien)?$data_pasien->almt_ttp_pasien:''; echo isset($value_form['alamat_pasien'])?$value_form['alamat_pasien']:$almt_ttp_pasien?>">
      </td>
    </tr>
  </table>
  <br>
  Dirawat di <b>Rumah Sakit Setia Mitra</b> di Jakarta<br>
  
  <br>
<table width="100%" style="font-size: 13px;">
  <tr>
    <td width="150px">Tanggal</td>
    <td>
      <input type="text" class="input_type date-picker" 
      data-date-format="dd/mm/yyyy" 
      style="width: 100px !important; text-align: center" 
      name="form_113[tgl_periksa_awal]" id="tgl_periksa_awal" 
      onchange="fillthis('tgl_periksa_awal')" 
      value="<?php echo isset($value_form['tgl_periksa_awal'])?$value_form['tgl_periksa_awal']:''?>">

      &nbsp; sampai &nbsp;
      
      <input type="text" class="input_type date-picker" 
      data-date-format="dd/mm/yyyy" 
      style="width: 100px !important; text-align: center" 
      name="form_113[tgl_keluar]" id="tgl_keluar" 
      onchange="fillthis('tgl_keluar')" 
      value="<?php echo isset($value_form['tgl_keluar'])?$value_form['tgl_keluar']:''?>">

    </td>
  </tr>

  <tr>
    <td>Kamar / Kelas</td>
    <td>

    <input type="text" class="input_type" name="form_113[kamar_kelas]" id="kamar_kelas" onchange="fillthis('kamar_kelas')" style="width:100%;"> 

    </td>
  </tr>

  <tr>
    <td>Dokter yang Merawat</td>
    <td>

    <input type="text" class="input_type" name="form_113[dokter_merawat]" id="dokter_merawat" onchange="fillthis('dokter_merawat')" style="width:100%;"> 

    </td>
  </tr>
</table>

  <br>

    
  Demikian surat keterangan sehat ini dibuat dengan benar, agar dapat dipergunakan sebagaimana mestinya.
</p>
<hr>

<?php  echo $footer; ?>