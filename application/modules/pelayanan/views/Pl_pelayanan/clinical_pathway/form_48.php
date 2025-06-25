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

  $('#diagnosa_pra_bedah').typeahead({
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
        $('#diagnosa_pra_bedah').val(label_item);
      }

  });

  $('#diagnosa_pasca_bedah').typeahead({
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
        $('#diagnosa_pasca_bedah').val(label_item);
      }

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

  $('#dokter_bedah_2').typeahead({
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
        $('#dokter_bedah_2').val(label_item);
      }

  });

  $('#dokter_anestesi').typeahead({
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
        $('#dokter_anestesi').val(label_item);
      }

  });

});
</script>

<?php echo $header; ?>
<hr>
<br>

<div style="text-align: center; font-size: 18px;"><b>INFORMED CONSENT OPERASI KATARAK</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<style>
.table-consent {
  border-collapse: collapse;
  width: 100%;
  font-size: 13px;
}
.table-consent th, .table-consent td {
  border: 1px solid #333;
  padding: 4px 6px;
  vertical-align: top;
}
.table-consent th {
  background: #f2f2f2;
  text-align: center;
}
.table-consent .nowrap { white-space: nowrap; }
</style>

<table style="width:100%; margin-bottom:8px;">
  <tr>
    <td style="width:40%">Dokter Pelaksana Tindakan</td>
    <td><input type="text" name="consent[dokter]" class="input_type" style="width:98%"></td>
  </tr>
  <tr>
    <td>Pemberi Informasi</td>
    <td><input type="text" name="consent[pemberi_info]" class="input_type" style="width:98%"></td>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td>Penerima Informasi / Pemberi Persetujuan</td>
    <td><input type="text" name="consent[penerima_info]" class="input_type" style="width:98%"></td>
    <td colspan="2"></td>
  </tr>
</table>

<table class="table-consent">
  <tr>
    <th style="width:30px;">No</th>
    <th style="width:180px;">Jenis Operasi / Tindakan</th>
    <th>Isi Informasi</th>
    <th style="width:50px;">Tanda (V)</th>
  </tr>
  <tr>
    <td>1</td>
    <td>Diagnosis (WD & DD)</td>
    <td>Katarak</td>
    <td align="center">
      <label>
          <input type="checkbox" class="ace" name="form_48[konfirm_1]" id="konfirm_1"  onclick="checkthis('konfirm_1')">
          <span class="lbl">&nbsp;</span>
      </label>
    </td>
  </tr>
  <tr>
    <td>2</td>
    <td>Dasar Diagnosis</td>
    <td>Anamnesis, Pemeriksaan Fisik dan Penunjang lainnya</td>
    <td align="center">
      <label>
          <input type="checkbox" class="ace" name="form_48[konfirm_2]" id="konfirm_2"  onclick="checkthis('konfirm_2')">
          <span class="lbl">&nbsp;</span>
      </label>
    </td>
  </tr>
  <tr>
    <td>3</td>
    <td>Tindakan Kedokteran</td>
    <td>Operasi Katarak</td>
    <td align="center">
      <label>
          <input type="checkbox" class="ace" name="form_48[konfirm_3]" id="konfirm_3"  onclick="checkthis('konfirm_3')">
          <span class="lbl">&nbsp;</span>
      </label>
    </td>
  </tr>
  <tr>
    <td>4</td>
    <td>Indikasi Tindakan</td>
    <td>Fungsi penglihatan telah terganggu atau pertimbangan medis yang lain seperti, glaukoma akut dan peradangan didalam bola mata / uveitis fakoilitik</td>
    <td align="center">
      <label>
          <input type="checkbox" class="ace" name="form_48[konfirm_4]" id="konfirm_4"  onclick="checkthis('konfirm_4')">
          <span class="lbl">&nbsp;</span>
      </label>
    </td>
  </tr>
  <tr>
    <td>5</td>
    <td>Tata Cara</td>
    <td>Mengangkat lensa yang keruh dengan meninggalkan kapsulnya. Pemulihan penglihatan pasca operasi dapat dicapai dengan penanaman lensa intra okuler, memakai kaca mata atau lensa kontak. Penanaman lensa intra okuler umumnya dilakukan bersamaan / pada saat operasi katarak berlangsung. Namun pada keadaan dan status pasien medis penanaman lensa dilakukan beberapa minggu setelah pengangkatan katarak.</td>
    <td align="center">
      <label>
          <input type="checkbox" class="ace" name="form_48[konfirm_5]" id="konfirm_5"  onclick="checkthis('konfirm_5')">
          <span class="lbl">&nbsp;</span>
      </label>
    </td>
  </tr>
  <tr>
    <td>6</td>
    <td>Tujuan</td>
    <td>Menghilangkan lensa yang keruh dan menggantinya dengan lensa buatan untuk mengembalikan visus yang jelas.</td>
    <td align="center">
      <label>
          <input type="checkbox" class="ace" name="form_48[konfirm_6]" id="konfirm_6"  onclick="checkthis('konfirm_6')">
          <span class="lbl">&nbsp;</span>
      </label>
    </td>
  </tr>
  <tr>
    <td>7</td>
    <td>Risiko</td>
    <td>
      Penelitian meta-Analisa hasil operasi katarak pada 90 studi:<br>
      &lt;0,5% mata yang dioperasi mengalami infeksi berat atau kerusakan kornea yang permanen<br>
      &lt;1% mengalami lepasnya lapisan saraf mata / ablasio retina<br>
      &lt;2% mengalami dislokasi / malposisi lensa, pendarahan dan pembengkakan bola mata atau pembentukan membran di dalam mata/kistoid.
    </td>
    <td align="center">
      <label>
          <input type="checkbox" class="ace" name="form_48[konfirm_7]" id="konfirm_7"  onclick="checkthis('konfirm_7')">
          <span class="lbl">&nbsp;</span>
      </label>
    </td>
  </tr>
  <tr>
    <td>8</td>
    <td>Komplikasi</td>
    <td>
      Komplikasi berat terjadi pada saat operasi berlangsung, beberapa minggu, bulan, bahkan beberapa tahun setelah operasi.<br>
      Beberapa komplikasi: infeksi berat, pendarahan (minimal 1 cc), semua komplikasi ini dapat mengakibatkan penglihatan yang kurang baik/buram, kehilangan penglihatan secara total, bahkan kehilangan mata itu sendiri. Pengobatan, perawatan dan pembedahan tambahan mungkin diperlukan untuk menangani komplikasi yang timbul.
    </td>
    <td align="center">
      <label>
          <input type="checkbox" class="ace" name="form_48[konfirm_8]" id="konfirm_8"  onclick="checkthis('konfirm_8')">
          <span class="lbl">&nbsp;</span>
      </label>
    </td>
  </tr>
  <tr>
    <td>9</td>
    <td>Prognosis</td>
    <td>Ad bonam</td>
    <td align="center">
      <label>
          <input type="checkbox" class="ace" name="form_48[konfirm_9]" id="konfirm_9"  onclick="checkthis('konfirm_9')">
          <span class="lbl">&nbsp;</span>
      </label>
    </td>
  </tr>
  <tr>
    <td>10</td>
    <td>Alternatif & Risiko</td>
    <td>-</td>
    <td align="center">
      <label>
          <input type="checkbox" class="ace" name="form_48[konfirm_10]" id="konfirm_10"  onclick="checkthis('konfirm_10')">
          <span class="lbl">&nbsp;</span>
      </label>
    </td>
  </tr>
  <tr>
    <td>11</td>
    <td>Lain-lain</td>
    <td>-</td>
    <td align="center">
      <label>
          <input type="checkbox" class="ace" name="form_48[konfirm_11]" id="konfirm_11"  onclick="checkthis('konfirm_11')">
          <span class="lbl">&nbsp;</span>
      </label>
    </td>
  </tr>
</table>

<br>
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
      <td width="30%">Nama : <input type="text" style="width: 50% !important" name="form_48[pj_pasien_name]" id="pj_pasien_name" onchange="fillthis('pj_pasien_name')" class="input_type" value=""></td>
      <td width="40%">
        <label>
            <input type="checkbox" class="ace" name="form_48[jk_l]" id="jk_l"  onclick="checkthis('jk_l')">
            <span class="lbl"> Laki-laki</span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_48[jk_p]" id="jk_p"  onclick="checkthis('jk_p')">
            <span class="lbl"> Perempuan</span>
        </label>
      </td>
      <td width="30%">Tgl Lahir : <input type="text" name="form_48[tgl_lhr_keluarga_pasien]" id="tgl_lhr_keluarga_pasien" onchange="fillthis('tgl_lhr_keluarga_pasien')" class="input_type" value="" style="width: 50% !important"></td>
    </tr>
    <tr>
      <td colspan = "2">Alamat : <input type="text" style="width: 100% !important" name="form_48[alamat_keluarga_pasien]" id="alamat_keluarga_pasien" onchange="fillthis('alamat_keluarga_pasien')" class="input_type" value="" style="width: 100% !important"></td>
      <td>No. Telp :<input type="text" name="form_48[no_telp_kp]" id="no_telp_kp" onchange="fillthis('no_telp_kp')" class="input_type" value="" style="width: 100% !important"></td>
    </tr>
    <tr>
      <td colspan = "3">NIK/SIM : <input type="text" style="width: 80% !important" name="form_48[no_id]" id="no_id" onchange="fillthis('no_id')" class="input_type" value=""></td>
    </tr>
    <tr>
      <td colspan="3">Dengan ini menyatakan 
        <label>
          <input type="checkbox" class="ace" name="form_48[pernyataan_setuju]" id="pernyataan_setuju"  onclick="checkthis('pernyataan_setuju')">
          <span class="lbl"> <b>PERSETUJUAN</b> </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_48[pernyataan_tolak]" id="pernyataan_tolak"  onclick="checkthis('pernyataan_tolak')">
          <span class="lbl"> <b>PENOLAKAN</b> </span>
        </label> &nbsp;<br>
        untuk dilakukan tindakan berupa,  <input type="text" name="form_48[persetujuan_tindakan]" id="persetujuan_tindakan" onchange="fillthis('persetujuan_tindakan')" class="input_type" value="Operasi Katarak" style="width: 40% !important">, terhadap diri saya sendiri/ istri/ suami/ anak/ ayah/ ibu saya, yang bernama : </td>
    </tr>
    <tr>
      <td width="30%">Nama : 
        <input type="text" name="form_48[txt_nm_pasien]" id="txt_nm_pasien" onchange="fillthis('txt_nm_pasien')" class="input_type" value="">
      </td>
      <td>
        <label>
            <input type="checkbox" class="ace" name="form_48[jk_pasien_lk]" id="jk_pasien_lk"  onclick="checkthis('jk_pasien_lk')">
            <span class="lbl"> Laki-laki</span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_48[jk_pasien_pr]" id="jk_pasien_pr"  onclick="checkthis('jk_pasien_pr')">
            <span class="lbl"> Perempuan</span>
        </label>
      </td>
      <td>Tgl Lahir : 
        <input type="text" name="form_48[tgl_lhr_pasien]" id="tgl_lhr_pasien" onchange="fillthis('tgl_lhr_pasien')" class="input_type" value="" width="30% !important">
      </td>
    </tr>
    <tr>
      <td colspan = "2">Alamat : <input type="text" name="form_48[alamt_ttp_pasien]" id="alamt_ttp_pasien" onchange="fillthis('alamt_ttp_pasien')" class="input_type" value="<?php echo $data_pasien->almt_ttp_pasien?>" style="width: 100% !important"></td>
      <td>No. Telp : <input type="text" name="form_48[no_tlp_ps]" id="no_tlp_ps" onchange="fillthis('no_tlp_ps')" class="input_type" value=""></td>
    </tr>
    <tr>
      <td colspan="3">No Rekam Medis <input type="text" name="form_48[norm_psn]" id="norm_psn" onchange="fillthis('norm_psn')" class="input_type" value=""> Dirawat Kelas / Ruang  <input type="text" name="form_48[rawat_pasien_ruang]" id="rawat_pasien_ruang" onchange="fillthis('rawat_pasien_ruang')" class="input_type" value=""></td>
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
<br>
<br>
<hr>
<?php echo $footer; ?>