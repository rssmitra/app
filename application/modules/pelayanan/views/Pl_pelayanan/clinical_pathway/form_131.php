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
  Nomor. <?php echo isset($result->reg_data->no_registrasi)?$result->reg_data->no_registrasi:''?>.SKI/<?php echo date('d')?>-<?php echo date('m')?>/<?php echo date('Y')?>
</div>

<!-- hidden form -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>

<p>Saya yang bertanda tangan di bawah ini :</p>

<table width="100%" style="font-size: 13px;">
  <tr>
    <td width="150px">Nama</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" name="form_131[nama_petugas]" id="nama_petugas" onchange="fillthis('nama_petugas')" value="<?php echo isset($value_form['nama_petugas'])?$value_form['nama_petugas']:''?>">
    </td>
  </tr>
  <tr>
    <td>Jabatan</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" name="form_131[jabatan_petugas]" id="jabatan_petugas" onchange="fillthis('jabatan_petugas')" value="<?php echo isset($value_form['jabatan_petugas'])?$value_form['jabatan_petugas']:''?>">
    </td>
  </tr>
</table>

<br>

<p>Dengan ini menyatakan bahwa :</p>

<table width="100%" style="font-size: 13px;">
  <tr>
    <td width="150px">Nama</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" name="form_131[nama_pasien]" id="nama_pasien" onchange="fillthis('nama_pasien')" 
      value="<?php $nama_pasien = isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; echo isset($value_form['nama_pasien'])?$value_form['nama_pasien']:$nama_pasien?>">
    </td>
  </tr>
  <tr>
    <td>No. RM</td>
    <td>
      <input type="text" class="input_type" style="width: 150px;" name="form_131[no_rm]" id="no_rm" onchange="fillthis('no_rm')" 
      value="<?php $no_mr = isset($data_pasien->no_mr)?$data_pasien->no_mr:''; echo isset($value_form['no_rm'])?$value_form['no_rm']:$no_mr?>">
    </td>
  </tr>
  <tr>
    <td>Jenis Kelamin</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_131[jk_l]" id="jk_l" onclick="checkthis('jk_l')" 
        <?php $jk = isset($data_pasien->jen_kelamin)?$data_pasien->jen_kelamin:''; echo ($jk=='L')?'checked':'';?>> 
        <span class="lbl"> Laki-laki</span>
      </label>
      &nbsp;&nbsp;
      <label>
        <input type="checkbox" class="ace" name="form_131[jk_p]" id="jk_p" onclick="checkthis('jk_p')" 
        <?php echo ($jk=='P')?'checked':'';?>> 
        <span class="lbl"> Perempuan</span>
      </label>
    </td>
  </tr>
  <!-- <tr>
    <td>Tempat / Tgl. Lahir</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" name="form_131[tempat_tgl_lahir]" id="tempat_tgl_lahir" onchange="fillthis('tempat_tgl_lahir')" 
      value="<?php //echo isset($value_form['tempat_tgl_lahir'])?$value_form['tempat_tgl_lahir']:''?>">
    </td>
  </tr> -->
  <tr>
  <td>Tempat / Tanggal Lahir</td>
  <td colspan="2">
    <input 
        type="text" 
        class="input_type" 
        style="width: 120px !important;" 
        name="form_131[tempat_lahir]" 
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
        name="form_131[tanggal_lahir]" 
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
      <input type="text" class="input_type" style="width: 100%;" name="form_131[alamat]" id="alamat" onchange="fillthis('alamat')" 
      value="<?php $almt = isset($data_pasien->almt_ttp_pasien)?$data_pasien->almt_ttp_pasien:''; echo isset($value_form['alamat'])?$value_form['alamat']:$almt?>">
    </td>
  </tr>
</table>

<br>

<p>Telah mendapatkan :</p>

<table width="100%" style="font-size: 13px; border-collapse: collapse;">
  <tr>
    <td style="width:30px; text-align:center; vertical-align:top;">
      <label>
        <input type="checkbox" class="ace" name="form_131[imunisasi_dasar]" id="imunisasi_dasar" onclick="checkthis('imunisasi_dasar')">
        <span class="lbl"></span>
      </label>
    </td>
    <td>Imunisasi Dasar Lengkap (HB-0, BCG, Polio, DPT-HB-Hib 1, Polio 2, DPT-HB-Hib 2, Polio 3, DPT-HB-Hib 3, Polio 4, IPV dan Campak)</td>
  </tr>

  <tr>
    <td style="text-align:center; vertical-align:top;">
      <label>
        <input type="checkbox" class="ace" name="form_131[hepatitis_a]" id="hepatitis_a" onclick="checkthis('hepatitis_a')">
        <span class="lbl"></span>
      </label>
    </td>
    <td>Hepatitis A</td>
  </tr>

  <tr>
    <td style="text-align:center; vertical-align:top;">
      <label>
        <input type="checkbox" class="ace" name="form_131[hepatitis_b]" id="hepatitis_b" onclick="checkthis('hepatitis_b')">
        <span class="lbl"></span>
      </label>
    </td>
    <td>Hepatitis B</td>
  </tr>

  <tr>
    <td style="text-align:center; vertical-align:top;">
      <label>
        <input type="checkbox" class="ace" name="form_131[vaksin_mmr1]" id="vaksin_mmr1" onclick="checkthis('vaksin_mmr1')">
        <span class="lbl"></span>
      </label>
    </td>
    <td>Vaksin MMR</td>
  </tr>

  <tr>
    <td style="text-align:center; vertical-align:top;">
      <label>
        <input type="checkbox" class="ace" name="form_131[vaksin_mmr2]" id="vaksin_mmr2" onclick="checkthis('vaksin_mmr2')">
        <span class="lbl"></span>
      </label>
    </td>
    <td>Vaksin MMR</td>
  </tr>
</table>



<br>

<p>
Demikian Surat Keterangan Imunisasi Dasar Lengkap ini dibuat untuk dapat digunakan sebagaimana mestinya.
</p>
<br>

<?php  echo $footer; ?>