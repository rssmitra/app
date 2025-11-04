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
  <b><u>TRAVELLING DIALYSIS</u></b><br>
</div>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>

<table width="100%" style="font-size:13px;">
  <tr>
    <td width="150px">Nama</td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
      name="form_135[nama_pasien]" id="nama_pasien" onchange="fillthis('nama_pasien')" 
      value="<?php $nama_pasien = isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; echo isset($value_form['nama_pasien'])?$value_form['nama_pasien']:$nama_pasien?>">
    </td>
  </tr>
  <tr>
    <td>Diagnosa dan Etiologi</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[diagnosa]" id="diagnosa" onchange="fillthis('diagnosa')" 
      value="<?php echo isset($value_form['diagnosa'])?$value_form['diagnosa']:''?>">
    </td>
  </tr>
  <tr>
    <td>Dialysis Pertama Kali</td>
    <td>
      <input type="text" class="input_type date-picker" data-date-format="yyyy-mm-dd" 
      style="width:150px;" name="form_136[dialysis_pertama]" id="dialysis_pertama" 
      onchange="fillthis('dialysis_pertama')" 
      value="<?php echo isset($value_form['dialysis_pertama'])?$value_form['dialysis_pertama']:''?>">
    </td>
  </tr>
  <tr>
    <td>Lama Dialysis</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[lama_dialysis]" id="lama_dialysis" onchange="fillthis('lama_dialysis')" 
      value="<?php echo isset($value_form['lama_dialysis'])?$value_form['lama_dialysis']:''?>">
    </td>
  </tr>
  <tr>
    <td>HD Perminggu</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[hd_perminggu]" id="hd_perminggu" onchange="fillthis('hd_perminggu')" 
      value="<?php echo isset($value_form['hd_perminggu'])?$value_form['hd_perminggu']:''?>">
    </td>
  </tr>
  <tr>
    <td>Type Dializer</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[type_dializer]" id="type_dializer" onchange="fillthis('type_dializer')" 
      value="<?php echo isset($value_form['type_dializer'])?$value_form['type_dializer']:''?>">
    </td>
  </tr>
  <tr>
    <td>Type Mesin</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[type_mesin]" id="type_mesin" onchange="fillthis('type_mesin')" 
      value="<?php echo isset($value_form['type_mesin'])?$value_form['type_mesin']:''?>">
    </td>
  </tr>
  <tr>
    <td>Akses Sirkulasi</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[akses_sirkulasi]" id="akses_sirkulasi" onchange="fillthis('akses_sirkulasi')" 
      value="<?php echo isset($value_form['akses_sirkulasi'])?$value_form['akses_sirkulasi']:''?>">
    </td>
  </tr>
  <tr>
    <td>Tekanan Arteri</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[tekanan_arteri]" id="tekanan_arteri" onchange="fillthis('tekanan_arteri')" 
      value="<?php echo isset($value_form['tekanan_arteri'])?$value_form['tekanan_arteri']:''?>">
    </td>
  </tr>
  <tr>
    <td>Aliran Darah</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[aliran_darah]" id="aliran_darah" onchange="fillthis('aliran_darah')" 
      value="<?php echo isset($value_form['aliran_darah'])?$value_form['aliran_darah']:''?>">
    </td>
  </tr>
  <tr>
    <td>Heparinisasi</td>
    <td>
      Dosis Awal: <input type="text" class="input_type" style="width:100px;" 
      name="form_136[dosis_awal]" id="dosis_awal" onchange="fillthis('dosis_awal')" 
      value="<?php echo isset($value_form['dosis_awal'])?$value_form['dosis_awal']:''?>">
      &nbsp;&nbsp; Dosis Perjam: <input type="text" class="input_type" style="width:100px;" 
      name="form_136[dosis_perjam]" id="dosis_perjam" onchange="fillthis('dosis_perjam')" 
      value="<?php echo isset($value_form['dosis_perjam'])?$value_form['dosis_perjam']:''?>">
    </td>
  </tr>
  <tr>
    <td>Tekanan Darah</td>
    <td>
      Pre HD: <input type="text" class="input_type" style="width:100px;" 
      name="form_136[pre_hd]" id="pre_hd" onchange="fillthis('pre_hd')" 
      value="<?php echo isset($value_form['pre_hd'])?$value_form['pre_hd']:''?>">
      &nbsp;&nbsp; Post HD: <input type="text" class="input_type" style="width:100px;" 
      name="form_136[post_hd]" id="post_hd" onchange="fillthis('post_hd')" 
      value="<?php echo isset($value_form['post_hd'])?$value_form['post_hd']:''?>">
    </td>
  </tr>
  <tr>
    <td>Berat Badan Kering</td>
    <td>
      <input type="text" class="input_type" style="width:80px;" 
      name="form_136[berat_kering]" id="berat_kering" onchange="fillthis('berat_kering')" 
      value="<?php echo isset($value_form['berat_kering'])?$value_form['berat_kering']:''?>"> Kg,
      &nbsp;&nbsp; Rata-rata Kenaikan BB:
      <input type="text" class="input_type" style="width:80px;" 
      name="form_136[kenaikan_bb]" id="kenaikan_bb" onchange="fillthis('kenaikan_bb')" 
      value="<?php echo isset($value_form['kenaikan_bb'])?$value_form['kenaikan_bb']:''?>"> Kg
    </td>
  </tr>
  <tr>
    <td>Dialisat</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[dialisat]" id="dialisat" onchange="fillthis('dialisat')" 
      value="<?php echo isset($value_form['dialisat'])?$value_form['dialisat']:''?>">
    </td>
  </tr>
  <tr>
    <td>Riwayat Hepatitis</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[riwayat_hepatitis]" id="riwayat_hepatitis" onchange="fillthis('riwayat_hepatitis')" 
      value="<?php echo isset($value_form['riwayat_hepatitis'])?$value_form['riwayat_hepatitis']:''?>">
    </td>
  </tr>
  <tr>
    <td>Penyulit</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[penyulit]" id="penyulit" onchange="fillthis('penyulit')" 
      value="<?php echo isset($value_form['penyulit'])?$value_form['penyulit']:''?>">
    </td>
  </tr>
  <tr>
    <td>Diet</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[diet]" id="diet" onchange="fillthis('diet')" 
      value="<?php echo isset($value_form['diet'])?$value_form['diet']:''?>">
    </td>
  </tr>
  <tr>
    <td>Obat-obatan</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
      name="form_136[obat]" id="obat" onchange="fillthis('obat')" 
      value="<?php echo isset($value_form['obat'])?$value_form['obat']:''?>">
    </td>
  </tr>
</table>

<br>
<b>Laboratorium:</b><br>
<table width="100%" style="font-size:13px;">
  <tr>
    <td width="150px">1. HB</td>
    <td><input type="text" class="input_type" style="width:150px;" name="form_136[hb]" id="hb" onchange="fillthis('hb')" value="<?php echo isset($value_form['hb'])?$value_form['hb']:''?>"></td>
    <td>6. Clorida</td>
    <td><input type="text" class="input_type" style="width:150px;" name="form_136[clorida]" id="clorida" onchange="fillthis('clorida')" value="<?php echo isset($value_form['clorida'])?$value_form['clorida']:''?>"></td>
  </tr>
  <tr>
    <td>2. Ureum</td>
    <td><input type="text" class="input_type" style="width:150px;" name="form_136[ureum]" id="ureum" onchange="fillthis('ureum')" value="<?php echo isset($value_form['ureum'])?$value_form['ureum']:''?>"></td>
    <td>7. HbsAg</td>
    <td><input type="text" class="input_type" style="width:150px;" name="form_136[hbsag]" id="hbsag" onchange="fillthis('hbsag')" value="<?php echo isset($value_form['hbsag'])?$value_form['hbsag']:''?>"></td>
  </tr>
  <tr>
    <td>3. Creatinin</td>
    <td><input type="text" class="input_type" style="width:150px;" name="form_136[creatinin]" id="creatinin" onchange="fillthis('creatinin')" value="<?php echo isset($value_form['creatinin'])?$value_form['creatinin']:''?>"></td>
    <td>8. Anti HCV</td>
    <td><input type="text" class="input_type" style="width:150px;" name="form_136[anti_hcv]" id="anti_hcv" onchange="fillthis('anti_hcv')" value="<?php echo isset($value_form['anti_hcv'])?$value_form['anti_hcv']:''?>"></td>
  </tr>
  <tr>
    <td>4. Natrium</td>
    <td><input type="text" class="input_type" style="width:150px;" name="form_136[natrium]" id="natrium" onchange="fillthis('natrium')" value="<?php echo isset($value_form['natrium'])?$value_form['natrium']:''?>"></td>
    <td>9. Anti HIV</td>
    <td><input type="text" class="input_type" style="width:150px;" name="form_136[anti_hiv]" id="anti_hiv" onchange="fillthis('anti_hiv')" value="<?php echo isset($value_form['anti_hiv'])?$value_form['anti_hiv']:''?>"></td>
  </tr>
  <tr>
    <td>5. Kalium</td>
    <td><input type="text" class="input_type" style="width:150px;" name="form_136[kalium]" id="kalium" onchange="fillthis('kalium')" value="<?php echo isset($value_form['kalium'])?$value_form['kalium']:''?>"></td>
  </tr>
</table>

<?php echo $footer; ?>
