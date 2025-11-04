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

<!-- Hidden form -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>

<p>Yang bertanda tangan di bawah ini menerangkan bahwa :</p>

<table width="100%" style="font-size:13px;">
  <tr>
    <td width="150px">Nama</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
      name="form_135[nama_pasien]" id="nama_pasien" onchange="fillthis('nama_pasien')" 
      value="<?php $nama_pasien = isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; echo isset($value_form['nama_pasien'])?$value_form['nama_pasien']:$nama_pasien?>">
    </td>
  </tr>
  <!-- <tr>
    <td>Tanggal lahir / Umur</td>
    <td>
      <input type="text" class="input_type date-picker" data-date-format="dd/mm/yyyy" 
      style="width: 100px;" name="form_135[tanggal_lahir]" id="tanggal_lahir" onchange="fillthis('tanggal_lahir')" 
      value="<?php 
        //$tgl_lhr = isset($data_pasien->tgl_lhr)?date('d/m/Y', strtotime($data_pasien->tgl_lhr)):''; 
        //echo isset($value_form['tanggal_lahir'])?$value_form['tanggal_lahir']:$tgl_lhr;
      ?>">
      &nbsp;/&nbsp;
      <input type="text" class="input_type" style="width: 60px; text-align:center;" 
      name="form_135[umur]" id="umur" onchange="fillthis('umur')" 
      value="<?php //echo isset($value_form['umur'])?$value_form['umur']:''?>"> tahun
    </td>
  </tr> -->
  <tr>
  <td>Tempat / Tanggal Lahir</td>
  <td colspan="2">
    <input 
        type="text" 
        class="input_type" 
        style="width: 120px !important;" 
        name="form_135[tempat_lahir]" 
        id="tempat_lahir"
        value="<?php 
          echo isset($value_form['tempat_lahir']) 
            ? $value_form['tempat_lahir'] 
            : (isset($data_pasien->tempat_lahir) ? $data_pasien->tempat_lahir : '');
        ?>"
      >
      , 
      <input 
        type="text" 
        class="input_type date-picker" 
        data-date-format="yyyy-mm-dd"
        style="width: 120px !important;" 
        name="form_135[tanggal_lahir]" 
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
    <td>Diagnosa Utama</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
      name="form_135[diagnosa]" id="diagnosa" onchange="fillthis('diagnosa')" 
      value="<?php echo isset($value_form['diagnosa'])?$value_form['diagnosa']:''?>">
    </td>
  </tr>
  <tr>
    <td>Diagnosa Penyerta</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
      name="form_135[diagnosa_penyerta]" id="diagnosa_penyerta" onchange="fillthis('diagnosa_penyerta')" 
      value="<?php echo isset($value_form['diagnosa_penyerta'])?$value_form['diagnosa_penyerta']:''?>">
    </td>
  </tr>
  <tr>
    <td>Jadwal HD</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
      name="form_135[jadwal_hd]" id="jadwal_hd" onchange="fillthis('jadwal_hd')" 
      value="<?php echo isset($value_form['jadwal_hd'])?$value_form['jadwal_hd']:''?>">
    </td>
  </tr>
</table>

<br>

<p style="text-align: justify; font-size:13px;">
  Adalah benar pasien dengan hemodialisa rutin. Dikarenakan dengan kondisi medis demikian pasien 
  masih membutuhkan tindakan hemodialisa seumur hidup atau sampai pasien sembuh.
</p>

<p style="text-align: justify; font-size:13px;">
  Sehingga yang bersangkutan tidak membutuhkan surat rujukan dari puskesmas setiap bulan untuk hemodialisa rutin.
</p>

<p style="text-align: justify; font-size:13px;">
  Demikian surat keterangan ini dibuat dengan sebenarnya dan agar dipergunakan sebagaimana mestinya.
</p>

<p style="font-size:13px;">
  Atas kerjasamanya kami ucapkan terima kasih.
</p>

<?php  echo $footer; ?>