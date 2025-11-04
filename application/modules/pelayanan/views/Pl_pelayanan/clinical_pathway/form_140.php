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
  <b><u>RUJUKAN PASIEN KLINIK SPESIALIS</u></b><br>
</div>

<!-- Hidden form -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>

<p>Kepada Yth,</p>
<input type="text" class="input_type" style="width: 100px;" name="form_140[kepada]" id="kepada" onchange="fillthis('kepada')">
<p>Di RS/Klinik/Spesialis</p>

<br>

<p>Bersama ini kami kirimkan penderita:</p>

<table width="100%" style="font-size:12px;">
  <tr>
    <td width="150px">Nama</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
      name="form_140[nama_pasien]" id="nama_pasien" onchange="fillthis('nama_pasien')" 
      value="<?php 
        $nama_pasien = isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; 
        echo isset($value_form['nama_pasien'])?$value_form['nama_pasien']:$nama_pasien;
      ?>">
    </td>
  </tr>

  <tr>
    <td>Tanggal Lahir</td>
    <td>
      <input 
        type="text" 
        class="input_type date-picker" 
        data-date-format="yyyy-mm-dd"
        style="width: 150px !important;" 
        name="form_140[tanggal_lahir]" 
        id="tanggal_lahir"
        value="<?php 
          $tgl_lhr = isset($data_pasien->tgl_lhr) ? $data_pasien->tgl_lhr : ''; 
          if (!empty($tgl_lhr)) {
            $tgl_lhr = date('Y-m-d', strtotime($tgl_lhr));
          }
          echo isset($value_form['tanggal_lahir']) ? $value_form['tanggal_lahir'] : $tgl_lhr; 
        ?>"
      >
    </td>
  </tr>

    <tr>
    <td>Alamat</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
      name="form_140[alamat]" id="alamat" onchange="fillthis('alamat')" 
      value="<?php $almt = isset($data_pasien->almt_ttp_pasien)?$data_pasien->almt_ttp_pasien:''; echo isset($value_form['alamat'])?$value_form['alamat']:$almt?>">
    </td>
  </tr>
</table>


  <tr>
    <td>Diagnosa</td>
    <td>
      <input type="text" class="input_type" style="width: 100px;" name="form_140[diagnosa]" id="diagnosa" onchange="fillthis('diagnosa')">
    </td>
  </tr>
</table>

<br>

<p style="font-size:13px;">Mohon untuk dilakukan:</p>

<table width="100%" style="font-size:13px; border-collapse: collapse; margin-left:10px;">
  <tr>
    <td colspan="2" style="text-align: justify;">
      Mohon untuk dilakukan:
    </td>
  </tr>
  <tr>
    <td style="width:30px; text-align:center;">
      <label>
        <input type="checkbox" class="ace" name="form_135[pemeriksaan]" id="pemeriksaan" onclick="checkthis('pemeriksaan')" 
          <?php echo isset($value_form['pemeriksaan']) && $value_form['pemeriksaan'] == 1 ? 'checked' : ''; ?>>
        <span class="lbl"></span>
      </label>
    </td>
    <td>Pemeriksaan</td>
  </tr>
  <tr>
    <td style="text-align:center;">
      <label>
        <input type="checkbox" class="ace" name="form_135[tindakan]" id="tindakan" onclick="checkthis('tindakan')" 
          <?php echo isset($value_form['tindakan']) && $value_form['tindakan'] == 1 ? 'checked' : ''; ?>>
        <span class="lbl"></span>
      </label>
    </td>
    <td>Tindakan</td>
  </tr>
  <tr>
    <td style="text-align:center;">
      <label>
        <input type="checkbox" class="ace" name="form_135[pengobatan]" id="pengobatan" onclick="checkthis('pengobatan')" 
          <?php echo isset($value_form['pengobatan']) && $value_form['pengobatan'] == 1 ? 'checked' : ''; ?>>
        <span class="lbl"></span>
      </label>
    </td>
    <td>Pengobatan</td> 
  </tr>
  <tr>
    <td></td>
    <td><input type="text" class="input_type" style="width:90%" name="form_140[isi_pemeriksaan]" id="isi_pemeriksaan" onchange="fillthis('isi_pemeriksaan')"></td>
  </tr>
</table>
<br>
<p style="font-size:13px;">
  Atas bantuannya kami ucapkan terima kasih.
</p>


<?php  echo $footer; ?>