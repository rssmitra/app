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
  <b><u>SURAT KETERANGAN</u></b><br>
</div>

<div style="text-align: center; font-size: 13px; margin-top: -5px">
  Nomor. <?php echo isset($result->reg_data->no_registrasi)?$result->reg_data->no_registrasi:''?>.SKM/<?php echo date('d')?>-<?php echo date('m')?>/<?php echo date('Y')?>
</div>

<!-- Hidden form -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>

<p>Yang bertanda tangan di bawah ini:</p>

<table width="100%" style="font-size: 13px;">
  <tr>
    <td width="150px">Nama Dokter</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
      name="form_132[nama_dokter]" id="nama_dokter" onchange="fillthis('nama_dokter')" 
      value="<?php echo isset($value_form['nama_dokter'])?$value_form['nama_dokter']:''?>">
    </td>
  </tr>
  <tr>
    <td>Jabatan</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" name="form_132[jabatan_petugas]" id="jabatan_petugas" onchange="fillthis('jabatan_petugas')" value="<?php echo isset($value_form['jabatan_petugas'])?$value_form['jabatan_petugas']:''?>">
    </td>
  </tr>
  <!-- <tr>
    <td>No. SIP</td>
    <td>
      <input type="text" class="input_type" style="width: 50%;" 
      name="form_132[no_sip]" id="no_sip" onchange="fillthis('no_sip')" 
      value="<?php echo isset($value_form['no_sip'])?$value_form['no_sip']:''?>">
    </td>
  </tr> -->
</table>

<br>

<p>Menerangkan bahwa:</p>

<table width="100%" style="font-size: 13px;">
  <tr>
    <td width="150px">Nama</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
      name="form_132[nama_pasien]" id="nama_pasien" onchange="fillthis('nama_pasien')" 
      value="<?php $nama_pasien = isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; echo isset($value_form['nama_pasien'])?$value_form['nama_pasien']:$nama_pasien?>">
    </td>
  </tr>
  <tr>
    <td>Tempat / Tgl. Lahir</td>
    <td>
      <input type="text" class="input_type" style="width: 120px;" 
      name="form_132[tempat_lahir]" id="tempat_lahir" onchange="fillthis('tempat_lahir')" 
      value="<?php echo isset($value_form['tempat_lahir'])?$value_form['tempat_lahir']:(isset($data_pasien->tempat_lahir)?$data_pasien->tempat_lahir:'')?>">, 
      <input type="text" class="input_type date-picker" data-date-format="dd/mm/yyyy" 
      style="width: 120px; text-align:center;" 
      name="form_132[tanggal_lahir]" id="tanggal_lahir" onchange="fillthis('tanggal_lahir')" 
      value="<?php 
        $tgl_lhr = isset($data_pasien->tgl_lhr)?date('d/m/Y', strtotime($data_pasien->tgl_lhr)):'';
        echo isset($value_form['tanggal_lahir'])?$value_form['tanggal_lahir']:$tgl_lhr;
      ?>">
    </td>
  </tr>
  <tr>
    <td>Jenis Kelamin</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_132[jk_l]" id="jk_l" onclick="checkthis('jk_l')" 
        <?php $jk = isset($data_pasien->jen_kelamin)?$data_pasien->jen_kelamin:''; echo ($jk=='L')?'checked':'';?>> 
        <span class="lbl"> Laki-laki</span>
      </label>
      &nbsp;&nbsp;
      <label>
        <input type="checkbox" class="ace" name="form_132[jk_p]" id="jk_p" onclick="checkthis('jk_p')" 
        <?php echo ($jk=='P')?'checked':'';?>> 
        <span class="lbl"> Perempuan</span>
      </label>
    </td>
  </tr>
  <tr>
    <td>Alamat</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
      name="form_132[alamat]" id="alamat" onchange="fillthis('alamat')" 
      value="<?php $almt = isset($data_pasien->almt_ttp_pasien)?$data_pasien->almt_ttp_pasien:''; echo isset($value_form['alamat'])?$value_form['alamat']:$almt?>">
    </td>
  </tr>
</table>

<br>

<p style="text-align: justify; margin-left: 10px;">Pada tanggal 
<input type="text" class="input_type" name="form_132[tanggal_periksa]" id="tanggal_periksa" onchange="fillthis('tanggal_periksa')" style="width: 130px; text-align:center;">

<!-- <input type="text" class="input_type date-picker" data-date-format="dd/mm/yyyy" 
  style="width: 130px; text-align:center;" 
  name="form_132[tanggal_periksa]" id="tanggal_periksa" onchange="fillthis('tanggal_periksa')" 
  value="<?php // echo isset($value_form['tanggal_periksa'])?$value_form['tanggal_periksa']:'30/09/2025'?>"> -->
  telah menjalani pemeriksaan medis di <b>Rumah Sakit Setia Mitra</b> :

</p>

<p style="text-align: justify; margin-left: 10px;">
  Dari pemeriksaan didapatkan keluhan paru saat ini tidak ada, dari pemeriksaan fisis tidak ditemukan kelainan yang bermakna.  
  Dari hasil pemeriksaan penunjang ditemukan <b>(IGRA POSITIF)</b>.  
  Dengan demikian, yang bersangkutan dinyatakan <b>TB LATEN</b> dan tidak memiliki risiko penularan pada pemeriksaan saat ini.
</p>

<br>

<p>
Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat digunakan sebagaimana mestinya.
</p>

<?php  echo $footer; ?>