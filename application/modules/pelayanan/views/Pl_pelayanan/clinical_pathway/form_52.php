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
  <b><u>SURAT KETERANGAN SEHAT</u></b><br>
</div>
<div style="text-align: center; font-size: 13px; margin-top: -5px">Nomor. <?php echo isset($result->reg_data->no_registrasi)?$result->reg_data->no_registrasi:''?>.SKS/<?php echo date('d')?>-<?php echo date('m')?>/<?php echo date('Y')?></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<p>
  Yang bertanda tangan dibawah ini, dokter pemeriksa <b>Rumah Sakit Setia Mitra</b> di Jakarta, menerangkan bahwa :<br>
  <table width="100%">
    <tr>
      <td width="100px">No. RM</td>
      <td colspan="2" width="200px">
        <input type="text" class="input_type" style="width: 100px !important" name="form_52[no_mr_pasien_istirahat]" id="no_mr_pasien_istirahat" onchange="fillthis('no_mr_pasien_istirahat')" value="<?php $no_mr = isset($data_pasien->no_mr)?$data_pasien->no_mr:''; echo isset($value_form['no_mr_pasien_istirahat'])?$value_form['no_mr_pasien_istirahat']:$no_mr?>">
      </td>
    </tr>
    <tr>
      <td>Nama Pasien</td>
      <td>
        <input type="text" class="input_type" style="width: 250px !important" name="form_52[nama_pasien_istirahat]" id="nama_pasien_istirahat" onchange="fillthis('nama_pasien_istirahat')" value="<?php $nama_pasien = isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; echo isset($value_form['nama_pasien_istirahat'])?$value_form['nama_pasien_istirahat']:$nama_pasien?>">
      </td>
      <td>
        <label>
          <input type="checkbox" class="ace" name="form_52[jk_l]" id="jk_l"  onclick="checkthis('jk_l')" <?php $jen_kelamin = isset($data_pasien->jen_kelamin)?$data_pasien->jen_kelamin:''; echo ($jen_kelamin == 'L')?"checked":"";?>>
          <span class="lbl" > Laki-laki</span>
        </label>
        
        <label>
          <input type="checkbox" class="ace" name="form_52[jk_p]" id="jk_p"  onclick="checkthis('jk_p')" <?php $jen_kelamin = isset($data_pasien->jen_kelamin)?$data_pasien->jen_kelamin:''; echo ($data_pasien->jen_kelamin == 'P')?"checked":"";?>>
          <span class="lbl" > Perempuan</span>
        </label>

      </td>
    </tr>
    <tr>
      <td>Umur</td>
      <td colspan="2">
        <input type="text" class="input_type" style="width: 50px !important" name="form_52[umur_pasien]" id="umur_pasien" onchange="fillthis('umur_pasien')" value="<?php $umur = isset($data_pasien->umur)?$data_pasien->umur:''; echo isset($value_form['umur_pasien'])?$value_form['umur_pasien']:$data_pasien->umur?>">
      </td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td colspan="2">
        <input type="text" class="input_type" style="width: 100% !important" name="form_52[alamat_pasien]" id="alamat_pasien" onchange="fillthis('alamat_pasien')" value="<?php $almt_ttp_pasien = isset($data_pasien->almt_ttp_pasien)?$data_pasien->almt_ttp_pasien:''; echo isset($value_form['alamat_pasien'])?$value_form['alamat_pasien']:$almt_ttp_pasien?>">
      </td>
    </tr>
  </table>
  <br>
  Berdasarkan hasil pemeriksaan fisik pada tanggal <input type="text" class="input_type date-picker" data-date-format="dd/mm/yyyy" style="width: 100px !important; text-align: center" name="form_52[tgl_periksa]" id="tgl_periksa" onchange="fillthis('tgl_periksa')" value="<?php echo isset($value_form['tgl_periksa'])?$value_form['tgl_periksa']:''?>">  di Rumah Sakit Setia Mitra, menerangkan bahwa pasien tersebut dinyatakan dalam keadaan <b>SEHAT</b> dengan hasil pemeriksaan sebagai berikut :<br>
  <br>
  <table width="100%">
    <tr>
      <td width="50px">Berat Badan</td>
      <td width="200px">
        <input type="text" class="input_type" style="width: 100px !important; text-align: center" name="form_52[bb_periksa]" id="bb_periksa" onchange="fillthis('bb_periksa')" value="<?php echo isset($value_form['bb_periksa'])?$value_form['bb_periksa']:""?>"> kg
      </td>
    </tr>
    <tr>
      <td width="50px">Tinggi Badan</td>
      <td width="200px">
        <input type="text" class="input_type" style="width: 100px !important; text-align: center" name="form_52[tb_periksa]" id="tb_periksa" onchange="fillthis('tb_periksa')" value="<?php echo isset($value_form['tb_periksa'])?$value_form['tb_periksa']:""?>"> cm
      </td>
    </tr>
    <tr>
      <td width="50px">Tekanan Darah</td>
      <td width="200px">
        <input type="text" class="input_type" style="width: 100px !important; text-align: center" name="form_52[td_periksa]" id="td_periksa" onchange="fillthis('td_periksa')" value="<?php echo isset($value_form['td_periksa'])?$value_form['td_periksa']:""?>"> mmHg
      </td>
    </tr>
    <tr>
      <td width="50px">Nadi</td>
      <td width="200px">
        <input type="text" class="input_type" style="width: 100px !important; text-align: center" name="form_52[nadi_periksa]" id="nadi_periksa" onchange="fillthis('nadi_periksa')" value="<?php echo isset($value_form['nadi_periksa'])?$value_form['nadi_periksa']:""?>"> bpm
      </td>
    </tr>
    <tr>
      <td width="50px">Suhu</td>
      <td width="200px">
        <input type="text" class="input_type" style="width: 100px !important; text-align: center" name="form_52[suhu_periksa]" id="suhu_periksa" onchange="fillthis('suhu_periksa')" value="<?php echo isset($value_form['suhu_periksa'])?$value_form['suhu_periksa']:""?>"> &deg;C
      </td>
    </tr>
    <tr>
      <td width="50px">Saturasi Oksigen</td>
      <td width="200px">
        <input type="text" class="input_type" style="width: 100px !important; text-align: center" name="form_52[saturasi_oksigen]" id="saturasi_oksigen" onchange="fillthis('saturasi_oksigen')" value="<?php echo isset($value_form['saturasi_oksigen'])?$value_form['saturasi_oksigen']:""?>"> %
      </td>
    </tr>
    <tr>
      <td width="50px">Golongan Darah</td>
      <td width="200px">
        <input type="text" class="input_type" style="width: 100px !important; text-align: center" name="form_52[gol_dar_periksa]" id="gol_dar_periksa" onchange="fillthis('gol_dar_periksa')" value="<?php echo isset($value_form['gol_dar_periksa'])?$value_form['gol_dar_periksa']:""?>">
      </td>
    </tr>
    <tr>
      <td width="50px">Keterangan Buta Warna</td>
      <td width="200px">
        <input type="text" class="input_type" style="width: 200px !important;" name="form_52[ket_buta_warna]" id="ket_buta_warna" onchange="fillthis('ket_buta_warna')" value="<?php echo isset($value_form['ket_buta_warna'])?$value_form['ket_buta_warna']:""?>">
      </td>
    </tr>
    <tr>
      <td width="50px">Riwayat Penyakit</td>
      <td>
        <input type="text" class="input_type" style="width: 100% !important" name="form_52[riwayat_sakit]" id="riwayat_sakit" onchange="fillthis('riwayat_sakit')" value="<?php echo isset($value_form['riwayat_sakit'])?$value_form['riwayat_sakit']:"Tidak ada riwayat penyakit"?>">
      </td>
    </tr>
  </table>
  <br>

    
  Demikian surat keterangan sehat ini dibuat, agar dapat dipergunakan sebagaimana mestinya.
</p>
<hr>

<?php  echo $footer; ?>