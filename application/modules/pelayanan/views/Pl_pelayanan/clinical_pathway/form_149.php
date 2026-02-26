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

<style>
.input_type{
  width: 60%;
  border: none;
  border-bottom: 1px solid #ffffffff;
  box-sizing: border-box;
}

.input-sm{
  width: 40px;
}

</style>

<?php echo $header; ?>
<hr>
<br>

<div style="text-align:center; font-size:18px;">
  <b><u>SURAT KETERANGAN BEBAS NARKOBA</u></b>
</div>

<div style="text-align:center; font-size:13px; margin-top:-5px;">
  Nomor. <?php echo isset($result->reg_data->no_registrasi)?$result->reg_data->no_registrasi:'';?>
  /SKBN/<?php echo date('m')?>/<?php echo date('Y')?>
</div>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>

<p>Dengan ini menerangkan bahwa:</p>

<table width="100%" style="font-size:13px;">
  <tr>
    <td width="120px">Nama</td>
    <td>
      <input type="text" class="input_type"
      name="form_149[nama_pasien]"
      id="nama_pasien"
      onchange="fillthis('nama_pasien')"
      value="<?php echo isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; ?>">
    </td>
  </tr>

  <tr>
  <td>Umur</td>
  <td>
    <div style="display:inline-flex; align-items:center;">
      <input type="text" class="input_type input-sm" style="width: 150px"
        name="form_149[umur]"
        id="umur"
        onchange="fillthis('umur')"
        value="<?php echo isset($data_pasien->umur)?$data_pasien->umur:''; ?>">
      
    </div>
  </td>
</tr>

  <tr>
    <td>Alamat</td>
    <td>
      <input type="text" class="input_type" 
      name="form_149[alamat]"
      id="alamat"
      onchange="fillthis('alamat')"
      value="<?php echo isset($data_pasien->almt_ttp_pasien)?$data_pasien->almt_ttp_pasien:''; ?>">
    </td>
  </tr>

  <tr>
    <td>Pekerjaan</td>
    <td>
      <input type="text" class="input_type" 
      name="form_149[pekerjaan]"
      id="pekerjaan"
      onchange="fillthis('pekerjaan')"
      placeholder="Pelajar / Mahasiswa / Karyawan">
    </td>
  </tr>
</table>

<br>

<p>
Setelah melalui pemeriksaan beberapa <b>Zat Adiktif / Narkoba</b> pada urine,
dengan hasil sebagai berikut:
</p>

<table width="50%" border="0" cellspacing="10" cellpadding="10"
       style="font-size:13px; margin-left:0;">
  <tr>
    <th align="left"></th>
    <th align="left">Jenis Zat</th>
    <th align="center">Hasil</th>
  </tr>
  <tr>
    <td style="padding-left:10px;"></td>
    <td style="padding-left:10px;">A. Amphetamine</td>
    <td align="left">Negative</td>
  </tr>
  <tr>
    <td style="padding-left:10px;"></td>
    <td style="padding-left:10px;">B. Opiate / Morphine</td>
    <td align="left">Negative</td>
  </tr>
  <tr>
    <td style="padding-left:10px;"></td>
    <td style="padding-left:10px;">C. THC</td>
    <td align="left">Negative</td>
  </tr>
</table>

<br>
<p>
<b>Kesimpulan:</b>
Yang bersangkutan <b>bebas dari Zat Adiktif / Narkoba</b> tersebut.
</p>

<br>
  Demikian surat keterangan sehat ini dibuat dengan benar, agar dapat dipergunakan sebagaimana mestinya.
</p>

<?php  echo $footer; ?>