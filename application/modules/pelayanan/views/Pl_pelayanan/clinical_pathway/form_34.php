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

  $('#dokter_bedah_1').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getAllDokter",
              data: { keyword:query },            
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
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#dokter_bedah_1').val(label_item);
      }

  });

});

</script>

<?php echo $header; ?>
<hr>
<br>

<div style="text-align: center; font-size: 18px;"><b>PEMBERIAN INFORMASI TINDAKAN KEDOKTERAN</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<table class="table">
<tr>
  <td width="300px">Dokter Pelaksana Tindakan</td>
  <td><input type="text" class="input_type" style="width: 100% !important" name="form_34[dokter_pelaksana_tindakan]" id="dokter_pelaksana_tindakan" onchange="fillthis('dokter_pelaksana_tindakan')"></td>
</tr>
<tr>
  <td width="300px">Pemberi Informasi</td>
  <td><input type="text" class="input_type" style="width: 100% !important" name="form_34[pemberi_informasi]" id="pemberi_informasi" onchange="fillthis('pemberi_informasi')"></td>
</tr>
<tr>
  <td width="300px">Penerima Informasi / Pemberi Persetujuan</td>
  <td><input type="text" class="input_type" style="width: 100% !important" name="form_34[penerima_informasi]" id="penerima_informasi" onchange="fillthis('penerima_informasi')"></td>
</tr>
</table>
<br>
<table class="table" border="1" width="100%">
  <thead>
      <tr>
          <th align="center" width="2%" colspan="1">NO</th>
          <th width="30%">Jenis Operasi / Tindakan</th>
          <th width="60%">Isi Informasi</th>
          <th width="3%" class="center">Konfirmasi</th>
      </tr>
  </thead>
  <tbody>
    <tr>
      <td align="center">1</td>
      <td> Diagnosis (WD & DD) </td>
      <td><input type="text" class="input_type" name="form_34[info_1]" id="info_1" onchange="fillthis('info_1')" style="width: 100% !important"></td>
      <td align="center">
         <label>
            <input type="checkbox" class="ace" name="form_34[konfirm_1]" id="konfirm_1"  onclick="checkthis('konfirm_1')">
            <span class="lbl">&nbsp;</span>
        </label>
      </td>
    </tr>
    <tr>
      <td align="center">2</td>
      <td> Dasar Diagnosis </td>
      <td><input type="text" class="input_type" name="form_34[info_2]" id="info_2" onchange="fillthis('info_2')" style="width: 100% !important"></td>
      <td align="center">
         <label>
            <input type="checkbox" class="ace" name="form_34[konfirm_2]" id="konfirm_2"  onclick="checkthis('konfirm_2')">
            <span class="lbl">&nbsp;</span>
        </label>
      </td>
    </tr>
    <tr>
      <td align="center">3</td>
      <td> Tindakan Kedokteran </td>
      <td><input type="text" class="input_type" name="form_34[info_3]" id="info_3" onchange="fillthis('info_3')" style="width: 100% !important"></td>
      <td align="center">
         <label>
            <input type="checkbox" class="ace" name="form_34[konfirm_3]" id="konfirm_3"  onclick="checkthis('konfirm_3')">
            <span class="lbl">&nbsp;</span>
        </label>
      </td>
    </tr>
    <tr>
      <td align="center">4</td>
      <td> Indikasi Tindakan </td>
      <td><input type="text" class="input_type" name="form_34[info_4]" id="info_4" onchange="fillthis('info_4')" style="width: 100% !important"></td>
      <td align="center">
         <label>
            <input type="checkbox" class="ace" name="form_34[konfirm_4]" id="konfirm_4"  onclick="checkthis('konfirm_4')">
            <span class="lbl">&nbsp;</span>
        </label>
      </td>
    </tr>
    <tr>
      <td align="center">5</td>
      <td> Tata Cara </td>
      <td><input type="text" class="input_type" name="form_34[info_5]" id="info_5" onchange="fillthis('info_5')" style="width: 100% !important"></td>
      <td align="center">
         <label>
            <input type="checkbox" class="ace" name="form_34[konfirm_5]" id="konfirm_5"  onclick="checkthis('konfirm_5')">
            <span class="lbl">&nbsp;</span>
        </label>
      </td>
    </tr>
    <tr>
      <td align="center">6</td>
      <td> Tujuan </td>
      <td><input type="text" class="input_type" name="form_34[info_6]" id="info_6" onchange="fillthis('info_6')" style="width: 100% !important"></td>
      <td align="center">
         <label>
            <input type="checkbox" class="ace" name="form_34[konfirm_6]" id="konfirm_6"  onclick="checkthis('konfirm_6')">
            <span class="lbl">&nbsp;</span>
        </label>
      </td>
    </tr>
    <tr>
      <td align="center">7</td>
      <td> Risiko </td>
      <td><input type="text" class="input_type" name="form_34[info_7]" id="info_7" onchange="fillthis('info_7')" style="width: 100% !important"></td>
      <td align="center">
         <label>
            <input type="checkbox" class="ace" name="form_34[konfirm_7]" id="konfirm_7"  onclick="checkthis('konfirm_7')">
            <span class="lbl">&nbsp;</span>
        </label>
      </td>
    </tr>
    <tr>
      <td align="center">8</td>
      <td> Komplikasi </td>
      <td><input type="text" class="input_type" name="form_34[info_8]" id="info_8" onchange="fillthis('info_8')" style="width: 100% !important"></td>
      <td align="center">
         <label>
            <input type="checkbox" class="ace" name="form_34[konfirm_8]" id="konfirm_8"  onclick="checkthis('konfirm_8')">
            <span class="lbl">&nbsp;</span>
        </label>
      </td>
    </tr>
    <tr>
      <td align="center">9</td>
      <td> Prognosis </td>
      <td><input type="text" class="input_type" name="form_34[info_9]" id="info_9" onchange="fillthis('info_9')" style="width: 100% !important"></td>
      <td align="center">
         <label>
            <input type="checkbox" class="ace" name="form_34[konfirm_9]" id="konfirm_9"  onclick="checkthis('konfirm_9')">
            <span class="lbl">&nbsp;</span>
        </label>
      </td>
    </tr>
    <tr>
      <td align="center">10</td>
      <td> Alternatif & Risiko </td>
      <td><input type="text" class="input_type" name="form_34[info_10]" id="info_10" onchange="fillthis('info_10')" style="width: 100% !important"></td>
      <td align="center">
         <label>
            <input type="checkbox" class="ace" name="form_34[konfirm_10]" id="konfirm_10"  onclick="checkthis('konfirm_10')">
            <span class="lbl">&nbsp;</span>
        </label>
      </td>
    </tr>
    <tr>
      <td align="center">11</td>
      <td> Lain-Lain </td>
      <td><input type="text" class="input_type" name="form_34[info_11]" id="info_11" onchange="fillthis('info_11')" style="width: 100% !important"></td>
      <td align="center">
         <label>
            <input type="checkbox" class="ace" name="form_34[konfirm_11]" id="konfirm_11"  onclick="checkthis('konfirm_11')">
            <span class="lbl">&nbsp;</span>
        </label>
      </td>
    </tr>
    <tr>
      <td colspan="3">
          Dengan ini saya menyatakan bahwa saya Dokter <input type="text" class="input_type" name="form_34[nama_dokter]" id="nama_dokter" onchange="fillthis('nama_dokter')"> telah menerangkan
          hal-hal di atas secara benar dan jelas serta memberikan kesempatan untuk bertanya dan / atau diskusi.
      </td>
      <td valign="top" align="center">
          <label>
            <input type="checkbox" class="ace" name="form_34[konfirm_12]" id="konfirm_12"  onclick="checkthis('konfirm_12')">
            <span class="lbl">&nbsp;</span>
        </label>
      </td>
    </tr>
    <tr>
        <td colspan="3">
            <p style="text-align: justify">Dengan ini saya menyatakan bahwa Keluarga Pasien <input type="text" class="input_type" name="form_34[keluarga_pasien_an]" id="keluarga_pasien_an" onchange="fillthis('keluarga_pasien_an')" value="<?php echo $data_pasien->nama_pasien?>" style="width: 200px"> telah menerima
            informasi sebagaimana di atas yang saya beri tanda / paraf di kolom, dan telah memahaminya serta telah diberikan kesempatan bertanya,
            dan pertanyaan saya telah diberikan jawaban yang memuaskan saya.</p>
        </td>
        <td valign="top" align="center">
            <label>
              <input type="checkbox" class="ace" name="form_34[konfirm_13]" id="konfirm_13"  onclick="checkthis('konfirm_13')">
              <span class="lbl">&nbsp;</span>
          </label>
        </td>
    </tr>
    <tr>
        <td colspan="4">* Bila pasien tidak kompeten atau tidak mau menerima informasi, maka penerima informasi adalah wali atau keluarga terdekat.</td>
    </tr>
  </tbody>
</table>
<br>
<table class="table">
  <thead>
    <tr>
        <th colspan="3" class="center">PERNYATAAN PERSETUJUAN / PENOLAKAN TINDAKAN KEDOKTERAN</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="3"> Yang bertanda tangan dibawah ini :</td>
    </tr>
    <tr>
      <td width="30%">Nama : <input type="text" style="width: 50% !important" name="form_34[pj_pasien_name]" id="pj_pasien_name" onchange="fillthis('pj_pasien_name')" class="input_type" value=""></td>
      <td width="40%">
        <label>
            <input type="checkbox" class="ace" name="form_34[jk_l]" id="jk_l"  onclick="checkthis('jk_l')">
            <span class="lbl"> Laki-laki</span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_34[jk_p]" id="jk_p"  onclick="checkthis('jk_p')">
            <span class="lbl"> Perempuan</span>
        </label>
      </td>
      <td width="30%">Tgl Lahir : <input type="text" name="form_34[tgl_lhr_keluarga_pasien]" id="tgl_lhr_keluarga_pasien" onchange="fillthis('tgl_lhr_keluarga_pasien')" class="input_type" value="" style="width: 50% !important"></td>
    </tr>
    <tr>
      <td colspan = "2">Alamat : <input type="text" style="width: 100% !important" name="form_34[alamat_keluarga_pasien]" id="alamat_keluarga_pasien" onchange="fillthis('alamat_keluarga_pasien')" class="input_type" value="" style="width: 100% !important"></td>
      <td>No. Telp :<input type="text" name="form_34[no_telp_kp]" id="no_telp_kp" onchange="fillthis('no_telp_kp')" class="input_type" value="" style="width: 100% !important"></td>
    </tr>
    <tr>
      <td colspan = "3">NIK/SIM : <input type="text" style="width: 80% !important" name="form_34[no_id]" id="no_id" onchange="fillthis('no_id')" class="input_type" value=""></td>
    </tr>
    <tr>
      <td colspan="3">Dengan ini menyatakan 
        <label>
          <input type="checkbox" class="ace" name="form_34[pernyataan_setuju]" id="pernyataan_setuju"  onclick="checkthis('pernyataan_setuju')">
          <span class="lbl"> PERSETUJUAN </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_34[pernyataan_tolak]" id="pernyataan_tolak"  onclick="checkthis('pernyataan_tolak')">
          <span class="lbl"> PENOLAKAN </span>
        </label> &nbsp;<br>
        untuk dilakukan tindakan berupa,  <input type="text" name="form_34[persetujuan_tindakan]" id="persetujuan_tindakan" onchange="fillthis('persetujuan_tindakan')" class="input_type" value="" style="width: 40% !important">, terhadap diri saya sendiri/ istri/ suami/ anak/ ayah/ ibu saya, yang bernama : </td>
    </tr>
    <tr>
      <td width="30%">Nama : 
        <input type="text" name="form_34[txt_nm_pasien]" id="txt_nm_pasien" onchange="fillthis('txt_nm_pasien')" class="input_type" value="">
      </td>
      <td>
        <label>
            <input type="checkbox" class="ace" name="form_34[jk_pasien_lk]" id="jk_pasien_lk"  onclick="checkthis('jk_pasien_lk')">
            <span class="lbl"> Laki-laki</span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_34[jk_pasien_pr]" id="jk_pasien_pr"  onclick="checkthis('jk_pasien_pr')">
            <span class="lbl"> Perempuan</span>
        </label>
      </td>
      <td>Tgl Lahir : 
        <input type="text" name="form_34[tgl_lhr_pasien]" id="tgl_lhr_pasien" onchange="fillthis('tgl_lhr_pasien')" class="input_type" value="" width="30% !important">
      </td>
    </tr>
    <tr>
      <td colspan = "2">Alamat : <input type="text" name="form_34[alamt_ttp_pasien]" id="alamt_ttp_pasien" onchange="fillthis('alamt_ttp_pasien')" class="input_type" value="<?php echo $data_pasien->almt_ttp_pasien?>" style="width: 100% !important"></td>
      <td>No. Telp : <input type="text" name="form_34[no_tlp_ps]" id="no_tlp_ps" onchange="fillthis('no_tlp_ps')" class="input_type" value=""></td>
    </tr>
    <tr>
      <td colspan="3">No Rekam Medis <input type="text" name="form_34[norm_psn]" id="norm_psn" onchange="fillthis('norm_psn')" class="input_type" value=""> Dirawat Kelas / Ruang  <input type="text" name="form_34[rawat_pasien_ruang]" id="rawat_pasien_ruang" onchange="fillthis('rawat_pasien_ruang')" class="input_type" value=""></td>
    </tr>
    <tr>
        <td colspan="3">
          <p style="text-align: justify">
            Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di atas kepada saya, termasuk risiko dan
            komplikasi yang mungkin timbul.
            <br><br>
            Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan tindakan kedokteran bukanlah keniscayaan
            melainkan sangat bergantung kepada izin Tuhan Yang Maha Esa.
          </p>
        </td>
    </tr>
  </tbody>
</table>

<hr>
<?php echo $footer; ?>
