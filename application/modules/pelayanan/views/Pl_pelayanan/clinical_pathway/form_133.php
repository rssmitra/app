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

<p>Dengan ini menerangkan bahwa:</p>

<table width="100%" style="font-size: 13px;">
  <tr>
    <td width="150px">Nama</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
      name="form_133[nama_pasien]" id="nama_pasien" onchange="fillthis('nama_pasien')" 
      value="<?php $nama_pasien = isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; echo isset($value_form['nama_pasien'])?$value_form['nama_pasien']:$nama_pasien?>">
    </td>
  </tr>
  <tr>
    <td>Tempat / Tgl. Lahir</td>
    <td>
      <input type="text" class="input_type" style="width: 120px;" 
      name="form_133[tempat_lahir]" id="tempat_lahir" onchange="fillthis('tempat_lahir')" 
      value="<?php echo isset($value_form['tempat_lahir'])?$value_form['tempat_lahir']:(isset($data_pasien->tempat_lahir)?$data_pasien->tempat_lahir:'')?>">, 
      <input type="text" class="input_type date-picker" data-date-format="dd/mm/yyyy" 
      style="width: 90px; text-align:left;" 
      name="form_133[tanggal_lahir]" id="tanggal_lahir" onchange="fillthis('tanggal_lahir')" 
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
        <input type="checkbox" class="ace" name="form_133[jk_l]" id="jk_l" onclick="checkthis('jk_l')" 
        <?php $jk = isset($data_pasien->jen_kelamin)?$data_pasien->jen_kelamin:''; echo ($jk=='L')?'checked':'';?>> 
        <span class="lbl"> Laki-laki</span>
      </label>
      &nbsp;&nbsp;
      <label>
        <input type="checkbox" class="ace" name="form_133[jk_p]" id="jk_p" onclick="checkthis('jk_p')" 
        <?php echo ($jk=='P')?'checked':'';?>> 
        <span class="lbl"> Perempuan</span>
      </label>
    </td>
  </tr>
    <tr>
    <td>No. RM</td>
    <td>
      <input type="text" class="input_type" style="width: 150px;" name="form_133[no_rm]" id="no_rm" onchange="fillthis('no_rm')" 
      value="<?php $no_mr = isset($data_pasien->no_mr)?$data_pasien->no_mr:''; echo isset($value_form['no_rm'])?$value_form['no_rm']:$no_mr?>">
    </td>
  </tr>
  <tr>
    <td>Alamat</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
      name="form_133[alamat]" id="alamat" onchange="fillthis('alamat')" 
      value="<?php $almt = isset($data_pasien->almt_ttp_pasien)?$data_pasien->almt_ttp_pasien:''; echo isset($value_form['alamat'])?$value_form['alamat']:$almt?>">
    </td>
  </tr>
</table>

<br>

<table width="100%" style="font-size:13px; border-collapse: collapse; margin-left:10px;">
  <tr>
    <td colspan="2" style="text-align: justify;">
      Telah menjalani pemeriksaan buta warna Ishihara dengan hasil:
    </td>
  </tr>
  <tr>
    <td style="width:30px; text-align:center;">
      <label><input type="checkbox" class="ace" name="form_133[buta_parsial]" id="buta_parsial" onclick="checkthis('buta_parsial')"> <span class="lbl"></span></label>
    </td>
    <td>Buta warna parsial</td>
  </tr>
  <tr>
    <td style="text-align:center;">
      <label><input type="checkbox" class="ace" name="form_133[buta_total]" id="buta_total" onclick="checkthis('buta_total')"> <span class="lbl"></span></label>
    </td>
    <td>Buta warna total</td>
  </tr>
  <tr>
    <td style="text-align:center;">
      <label><input type="checkbox" class="ace" name="form_133[buta_normal]" id="buta_normal" onclick="checkthis('buta_normal')"> <span class="lbl"></span></label>
    </td>
    <td>Tidak menderita buta warna</td>
  </tr>
</table>


<p>
Demikian surat keterangan ini dibuat, agar dapat digunakan sebagaimana mestinya.
</p>

<?php  echo $footer; ?>