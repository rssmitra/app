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

<div style="text-align:center; font-size:14px;">
  <b>PEMBERITAHUAN PENDERITA / TERSANGKA DEMAM BERDARAH DENGUE,</b><br>
  <b>POLIOMYELITIS DAN TETANUS NEONATORUM</u></b><br>
  <b>(Dikirim dalam 24 jam)</b><br><br>
  
  <b>RS SETIA MITRA</u></b><br>
  <b>KOTA JAKARTA SELATAN PROPINSI DKI JAKARTA</b>
  <hr>
</div>

<!---<div style="text-align:center; font-size:13px; margin-top:-5px;">
  Nomor. <?php //echo isset($result->reg_data->no_registrasi)?$result->reg_data->no_registrasi:'';?>
  /SK/<?php //echo date('m')?>/<?php echo date('Y')?>
</div>-->


<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<!-- KOP KANAN ATAS -->
<table width="100%" style="font-size:13px;">
  <tr>
    <td width="65%"></td>
    <td width="40%">
      Kepada Yth,<br>
      Dinas Kesehatan<br>
		<input type="text" class="input_type" name="form_150[kota_tujuan]" placeholder="............................" id="kota_tujuan" onchange="fillthis('kota_tujuan')" style="width:40%;"><br>
      Di Tempat
    </td>
  </tr>
</table>

<br>

<p>Bersama ini kami beritahukan bahawa kami telah merawat / memeriksa pasien:</p>

<table width="100%" style="font-size:12px;">
  <tr>
    <td width="120px">Nama</td>
    <td>
      <input type="text" class="input_type"
      name="form_150[nama_pasien]"
      id="nama_pasien"
      onchange="fillthis('nama_pasien')"
      value="<?php echo isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; ?>">
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
  <td>Umur</td>
  <td>
    <div style="display:inline-flex; align-items:center;">
      <input type="text" class="input_type input-sm"
        name="form_150[umur]"
        id="umur"
        onchange="fillthis('umur')"
        value="<?php echo isset($data_pasien->umur)?$data_pasien->umur:''; ?>">
      <span style="margin-left:6px;">Tahun</span>
    </div>
  </td>
  </tr>

  <tr>
    <td>Nama Orang Tua</td>
    <td>:
      <input type="text" class="input_type" name="form_150[nama_ortu]" id="nama_ortu" onchange="fillthis('nama_ortu')">
    </td>
  </tr>


  <tr>
    <td>Alamat Rumah</td>
    <td>
      <input type="text" class="input_type" 
      name="form_150[alamat]"
      id="alamat"
      onchange="fillthis('alamat')"
      value="<?php echo isset($data_pasien->almt_ttp_pasien)?$data_pasien->almt_ttp_pasien:''; ?>">
    </td>
  </tr>
  
  <tr>
    <td></td>
    <td style="padding-left:15px;">
      RT <input type="text" class="input_type input-sm" name="form_150[rt]" id="rt" onchange="fillthis('rt')">
      RW <input type="text" class="input_type input-sm" name="form_150[rw]" id="rw" onchange="fillthis('rw')">
      Kelurahan <input type="text" class="input_type" name="form_150[kelurahan]" id="kelurahan" onchange="fillthis('kelurahan')" style="width:40%;">
    </td>
  </tr>
  
  <tr>
  <td></td>
  <td style="padding-left:15px;">
    Kecamatan 
    <input type="text" class="input_type"
      name="form_150[kecamatan]"
      id="kecamatan" onchange="fillthis('kecamatan')"
      style="width:25%;">

    Kota 
    <input type="text" class="input_type"
      name="form_150[kota]"
      id="kota" onchange="fillthis('kota')"
      style="width:25%;">
  </td>
</tr>
  
  <tr>
    <td>Tanggal Mulai Sakit</td>
    <td>:	
	<input type="text" class="input_type" name="form_150[tgl_mulai_sakit]" id="tgl_mulai_sakit" onchange="fillthis('tgl_mulai_sakit')" style="width:15%;">
    </td>
  </tr>

  <!--<tr>
    <td>Pekerjaan</td>
    <td>
      <input type="text" class="input_type" 
      name="form_150[pekerjaan]"
      id="pekerjaan"
      onchange="fillthis('pekerjaan')"
      placeholder="Pelajar / Mahasiswa / Karyawan">
    </td>
  </tr>-->
  
<tr>
  <td>Perawatan</td>
  <td>:
    <label>
      <input type="checkbox" class="ace"
        name="form_150[rj]"
        id="rj"
        onclick="checkthis('rj')">
      <span class="lbl"> Rawat Jalan</span>
    </label>
    &nbsp;&nbsp;
    <label>
      <input type="checkbox" class="ace"
        name="form_150[ri]"
        id="ri"
        onclick="checkthis('ri')">
      <span class="lbl"> Rawat Inap</span>
    </label>
  </td>
</tr>
</table>

<br>

<table width="100%" style="font-size:12px;">
<tr>
  <td width="20%"><b>KEADAAN PENDERITA SAAT INI :</b></td>
  <td>
    <label>
      <input type="checkbox" class="ace"
        name="form_150[keadaan_hidup]"
        id="keadaan_hidup"
        onclick="checkthis('keadaan_hidup')">
      <span class="lbl"> HIDUP</span>
    </label>
    &nbsp;&nbsp;
    <label>
      <input type="checkbox" class="ace"
        name="form_150[keadaan_meninggal]"
        id="keadaan_meninggal"
        onclick="checkthis('keadaan_meninggal')">
      <span class="lbl"> MENINGGAL</span>
    </label>
  </td>
</tr>
</table>
<br>
  <td><b>DIAGNOSA :</b></td>

<table width="100%" style="font-size:13px; margin-top:10px;">
  
  <tr>
  <td>
    <label>
      <input type="checkbox" class="ace"
        name="form_150[dx1]"
        id="dx1"
        onclick="checkthis('dx1')">
      <span class="lbl"> Tersangka DBD (Demam Berdarah Dengue)</span>
    </label>
    &nbsp;&nbsp;
  </td>
  </tr>
  
  <tr>
  <td>
    <label>
      <input type="checkbox" class="ace"
        name="form_150[dx2]"
        id="dx2"
        onclick="checkthis('dx2')">
      <span class="lbl"> DBD (Demam Berdarah Dengue) Blot Positif </span>
    </label>
    &nbsp;&nbsp;
  </td>
  </tr>
  
  <tr>
  <td>
    <label>
      <input type="checkbox" class="ace"
        name="form_150[dx3]"
        id="dx3"
        onclick="checkthis('dx3')">
      <span class="lbl"> DBD (Demam Berdarah Dengue) Blot Negatif</span>
    </label>
    &nbsp;&nbsp;
  </td>
  </tr>
  
  <tr>
  <td>
    <label>
      <input type="checkbox" class="ace"
        name="form_150[dx4]"
        id="dx4"
        onclick="checkthis('dx4')">
      <span class="lbl"> DSS (Dengue Syok Sindrom) Blot Positif</span>
    </label>
    &nbsp;&nbsp;
  </td>
  </tr>
  
  <tr>
  <td>
    <label>
      <input type="checkbox" class="ace"
        name="form_150[dx5]"
        id="dx5"
        onclick="checkthis('dx5')">
      <span class="lbl"> DSS (Dengue Syok Sindrom) Blot Negatif</span>
    </label>
    &nbsp;&nbsp;
  </td>
  </tr>
  
  <tr>
  <td>
    <label>
      <input type="checkbox" class="ace"
        name="form_150[dx6]"
        id="dx6"
        onclick="checkthis('dx6')">
      <span class="lbl"> DD (Demam Dengue)</span>
    </label>
    &nbsp;&nbsp;
  </td>
  </tr>
  
  <tr>
  <td>
    <label>
      <input type="checkbox" class="ace"
        name="form_150[dx7]"
        id="dx7"
        onclick="checkthis('dx7')">
      <span class="lbl"> Poliomielitis</span>
    </label>
    &nbsp;&nbsp;
  </td>
  </tr>
  
  <tr>
  <td>
    <label>
      <input type="checkbox" class="ace"
        name="form_150[dx8]"
        id="dx8"
        onclick="checkthis('dx8')">
      <span class="lbl"> Tetanus Neonatorum</span>
    </label>
    &nbsp;&nbsp;
  </td>
  </tr>
</table>

<table width="100%" style="font-size:13px; margin-top:10px;">
  <tr>
    <td width="5%">Lainnya</td>
    <td>:	
	<input type="text" class="input_type" name="form_150[dx_lainnya]" id="dx_lainnya" onchange="fillthis('dx_lainnya')" style="width:30%;">
    </td>
  </tr>
</table>
<br>
  
</table>
<p>
  Demikian surat  ini dibuat dengan benar, agar dapat dipergunakan sebagaimana mestinya.
</p>
</table>

<?php  echo $footer; ?>

<br>
<p>
  <b>Tembusan:</b>
  
<table width="100%" style="font-size:13px; margin-top:10px;">
  <tr>
    <td width="10%">Kepada Yth.</td>
    <td>:	
	<input type="text" class="input_type" name="form_150[kepada_yth]" id="kepada_yth" onchange="fillthis('kepada_yth')" style="width:30%;">
    </td>
  </tr>
  
  <tr>
    <td width="5%">Ka</td>
    <td>:	
	<input type="text" class="input_type" name="form_150[kepada_ka]" id="kepada_ka" onchange="fillthis('kepada_ka')" style="width:30%;">
    </td>
  </tr>
  
  <tr>
    <td width="5%">Puskesmas</td>
    <td>:	
	<input type="text" class="input_type" name="form_150[puskesmas]" id="puskesmas" onchange="fillthis('puskesmas')" style="width:30%;">
    </td>
  </tr>