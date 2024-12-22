<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script type="text/javascript">
  $('#assesmen_diagnosa_primer').typeahead({
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
      $('#assesmen_diagnosa_primer').val(label_item);
      $('#assesmen_diagnosa_primer_hidden').val(val_item);
      }

  });

  $('#pl_diagnosa_sekunder').typeahead({
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
      $('#pl_diagnosa_sekunder').val('');
      $('<span class="multi-typeahead" id="txt_icd_'+val_item.trim().replace('.', '_')+'"><a href="#" onclick="remove_icd('+"'"+val_item.trim().replace('.', '_')+"'"+')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span><span class="text_icd_10"> '+item+' </span> </span>').appendTo('#pl_diagnosa_sekunder_hidden_txt');
      }

  });

  $( "#pl_diagnosa_sekunder" )    
    .keypress(function(event) {        
      var keycode =(event.keyCode?event.keyCode:event.which);         
      if(keycode ==13){          
        event.preventDefault();         
        if($(this).valid()){            
          var val_item = 1 + Math.floor(Math.random() * 100);
          console.log(val_item);
          var item = $('#pl_diagnosa_sekunder').val();
          $('<span class="multi-typeahead" id="txt_icd_'+val_item+'"><a href="#" onclick="remove_icd('+"'"+val_item+"'"+')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span><span class="text_icd_10"> '+item+' </span> </span>').appendTo('#pl_diagnosa_sekunder_hidden_txt'); 
        }          
        return $('#pl_diagnosa_sekunder').val('');                 
      }    
  });

  function remove_icd(icd){
      preventDefault();
      $('#txt_icd_'+icd+'').html('');
      $('#txt_icd_'+icd+'').hide();
  }
  
</script>

<?php echo $header; ?>
<hr>
<br>

<div style="text-align: center; font-size: 14px"><b>PENGKAJIAN KEPERAWATAN INSTALASI GAWAT DARURAT</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">


<table class="table">
  <tr><td style="background: #f4ae11; color: black;" colspan="4" align="center"><span style="font-size: 16px; font-weight: bold">T R I A S E</span></td></tr>
  <tr>
    <td colspan="4">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[trauma]" id="trauma"  onclick="checkthis('trauma')">
          <span class="lbl" > &nbsp; Trauma</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[non_trauma]" id="non_trauma"  onclick="checkthis('non_trauma')">
          <span class="lbl" > &nbsp; Non Trauma</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kebidanan]" id="kebidanan"  onclick="checkthis('kebidanan')">
          <span class="lbl" > &nbsp; Kebidanan</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td colspan="4">
      Keterangan : <br>
      <textarea style="width: 100% !important; height: 50px !important" name="form_27[keterangan_triase]" id="keterangan_triase" onchange="fillthis('keterangan_triase')"></textarea>
    </td>
  </tr>
  <tr>
    <td colspan="4">
      Keluhan Utama Pasien: <br>
      <textarea style="width: 100% !important; height: 70px !important" name="form_27[keluhan_utama_pasien]" id="keluhan_utama_pasien" onchange="fillthis('keluhan_utama_pasien')"></textarea>
    </td>
  </tr>
  <tr>
    <td colspan="4">
      <table class="table">
        <tr style="background: #f1f1f1; font-weight: bold">
          <td class="center" style="width: 50px" rowspan="2">Pernafasan</td>
          <td class="center" style="width: 50px" rowspan="2">Nadi</td>
          <td class="center" style="width: 50px" rowspan="2">Suhu</td>
          <td class="center" style="width: 50px" rowspan="2">SpO2</td>
          <td class="center" style="width: 50px" rowspan="2">Tekanan Darah</td>
          <td class="center" style="width: 50px" colspan="3">GCS</td>
          <td class="center" style="width: 50px" rowspan="2">Tinggi Badan</td>
          <td class="center" style="width: 50px" rowspan="2">Berat Badan</td>
        </tr>
        <tr style="background: #f1f1f1; font-weight: bold">
          <td align="center" style="width: 50px">E</td>
          <td align="center" style="width: 50px">M</td>
          <td align="center" style="width: 50px">V</td>
        </tr>
        <tr>
          <td align="center"><input type="text" style="width: 50px" name="form_27[pernafasan]" id="pernafasan" onchange="fillthis('pernafasan')"></td>
          <td align="center"><input type="text" style="width: 50px" name="form_27[nadi]" id="nadi" onchange="fillthis('nadi')"></td>
          <td align="center"><input type="text" style="width: 50px" name="form_27[suhu]" id="suhu" onchange="fillthis('suhu')"></td>
          <td align="center"><input type="text" style="width: 50px" name="form_27[spo2]" id="spo2" onchange="fillthis('spo2')"></td>
          <td align="center"><input type="text" style="width: 50px" name="form_27[tekanan_darah]" id="tekanan_darah" onchange="fillthis('tekanan_darah')"></td>
          <td align="center"><input type="text" style="width: 50px" name="form_27[triase_gcs]" id="triase_gcs" onchange="fillthis('triase_gcs')"></td>
          <td align="center"><input type="text" style="width: 50px" name="form_27[bb_e]" id="bb_e" onchange="fillthis('bb_e')"></td>
          <td align="center"><input type="text" style="width: 50px" name="form_27[bb_m]" id="bb_m" onchange="fillthis('bb_m')"></td>
          <td align="center"><input type="text" style="width: 50px" name="form_27[tinggi_badan]" id="tinggi_badan" onchange="fillthis('tinggi_badan')"></td>
          <td align="center"><input type="text" style="width: 50px" name="form_27[bb_v]" id="bb_v" onchange="fillthis('bb_v')"></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="4">
      <div class="checkbox">
        &nbsp; <b>Riwayat Alergi</b>
        <label>
          <input type="checkbox" class="ace" name="form_27[alergi_makanan]" id="alergi_makanan"  onclick="checkthis('alergi_makanan')">
          <span class="lbl" > &nbsp; Makanan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[alergi_minuman]" id="alergi_minuman"  onclick="checkthis('alergi_minuman')">
          <span class="lbl" > &nbsp; Minuman</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[alergi_lainnya]" id="alergi_lainnya"  onclick="checkthis('alergi_lainnya')">
          <span class="lbl" > &nbsp; Lainnya</span>
        </label>
        <label>
          <input type="text" class="ace" style="width: 200px" placeholder="diisi jika pilih lainnya" name="form_27[keterangan_alergi_lainnya]" id="keterangan_alergi_lainnya"  onclick="checkthis('keterangan_alergi_lainnya')">
        </label>
      </div>
    </td>
  <tr>

  <tr>
    <td align="center" style="background: red; color: black; font-weight: bold">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[triase_merah]" id="triase_merah"  onclick="checkthis('triase_merah')">
          <span class="lbl" style="color: white; font-weight: bold"> &nbsp; MERAH</span>
        </label>
      </div>
    </td>
    <td align="center" style="background: yellow; color: black; font-weight: bold">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[triase_kuning]" id="triase_kuning"  onclick="checkthis('triase_kuning')">
          <span class="lbl" style="color: black; font-weight: bold"> &nbsp; KUNING</span>
        </label>
      </div>
    </td>
    <td align="center" style="background: green; color: black; font-weight: bold">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[triase_hijau]" id="triase_hijau"  onclick="checkthis('triase_hijau')">
          <span class="lbl" style="color: white; font-weight: bold"> &nbsp; HIJAU</span>
        </label>
      </div>
    </td>
    <td align="center" style="background: black; color: black; font-weight: bold">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[triase_hitam]" id="triase_hitam"  onclick="checkthis('triase_hitam')">
          <span class="lbl" style="color: white; font-weight: bold"> &nbsp; HITAM</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td colspan="4" style="vertical-align: middle !important; font-weight: bold">Jalan Nafas</td>
  </tr>
  <tr>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[jln_nfs_sumbatan]" id="jln_nfs_sumbatan" onclick="checkthis('jln_nfs_sumbatan')">
          <span class="lbl" > &nbsp; Sumbatan</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[jln_nfs_bebas]" id="jln_nfs_bebas"  onclick="checkthis('jln_nfs_bebas')">
          <span class="lbl" > &nbsp; Bebas</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[jln_nfs_sumbatan_hijau]" id="jln_nfs_sumbatan_hijau"  onclick="checkthis('jln_nfs_sumbatan_hijau')">
          <span class="lbl" > &nbsp; Bebas</span>
        </label>
      </div>
    </td>
    <td>
      -
    </td>
  </tr>
  <tr>
    <td colspan="4" style="vertical-align: middle !important; font-weight: bold">Pernafasan</td>
  </tr>
  <tr>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[henti_nafas]" id="henti_nafas"  onclick="checkthis('henti_nafas')">
          <span class="lbl" > &nbsp; Henti Nafas</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[nafas_10]" id="nafas_10"  onclick="checkthis('nafas_10')">
          <span class="lbl" > &nbsp; < 10 x/mnt</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[nafas_32]" id="nafas_32"  onclick="checkthis('nafas_32')">
          <span class="lbl" > &nbsp; > 32 x/mnt</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[nafas_24]" id="nafas_24"  onclick="checkthis('nafas_24')">
          <span class="lbl" > &nbsp; 24-32 x/mnt</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[nafas_10_24]" id="nafas_10_24"  onclick="checkthis('nafas_10_24')">
          <span class="lbl" > &nbsp; 10-24 x/mnt</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[hitam_henti_nafas]" id="hitam_henti_nafas"  onclick="checkthis('hitam_henti_nafas')">
          <span class="lbl" > &nbsp; Henti Nafas</span>
        </label>
      </div>
    </td>
    
  </tr>
  <tr>
    <td colspan="4" style="vertical-align: middle !important; font-weight: bold">Sirkulasi</td>
  </tr>
  <tr>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[sirkulasi_henti_jantung]" id="sirkulasi_henti_jantung"  onclick="checkthis('sirkulasi_henti_jantung')">
          <span class="lbl" > &nbsp; Henti Jantung</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[sirkuasi_nadi_teraba]" id="sirkuasi_nadi_teraba"  onclick="checkthis('sirkuasi_nadi_teraba')">
          <span class="lbl" > &nbsp; Nadi Teraba Lemah</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[sirkulasi_nadi_kurang_50]" id="sirkulasi_nadi_kurang_50"  onclick="checkthis('sirkulasi_nadi_kurang_50')">
          <span class="lbl" > &nbsp; Nadi < 50 x/mnt</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[sirkulasi_nadi_lebih_50]" id="sirkulasi_nadi_lebih_50"  onclick="checkthis('sirkulasi_nadi_lebih_50')">
          <span class="lbl" > &nbsp; Nadi > 150 x/mnt</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[sirkulasi_akal_dingin]" id="sirkulasi_akal_dingin"  onclick="checkthis('sirkulasi_akal_dingin')">
          <span class="lbl" > &nbsp; Akal Dingin</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[sirkulasi_crt]" id="sirkulasi_crt"  onclick="checkthis('sirkulasi_crt')">
          <span class="lbl" > &nbsp; CRT > 2 Detik</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[sirkulasi_nadi_120]" id="sirkulasi_nadi_120"  onclick="checkthis('sirkulasi_nadi_120')">
          <span class="lbl" > &nbsp; Nadi 120-150 x/mnt</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[sirkulasi_nadi_60]" id="sirkulasi_nadi_60"  onclick="checkthis('sirkulasi_nadi_60')">
          <span class="lbl" > &nbsp; Nadi 60-100 x/mnt</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[hitam_sirkulasi_henti_jantung]" id="hitam_sirkulasi_henti_jantung"  onclick="checkthis('hitam_sirkulasi_henti_jantung')">
          <span class="lbl" > &nbsp; Henti Jantung</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[sirkulasi_ekg_flat]" id="sirkulasi_ekg_flat"  onclick="checkthis('sirkulasi_ekg_flat')">
          <span class="lbl" > &nbsp; EKG Flat</span>
        </label>
      </div>
    </td>
    
  </tr>
  <tr>
    <td colspan="4" style="vertical-align: middle !important; font-weight: bold">Kesadaran</td>
  </tr>
  <tr>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kesadaran_gcs]" id="kesadaran_gcs"  onclick="checkthis('kesadaran_gcs')">
          <span class="lbl" > &nbsp; GCS < 12</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kesadaran_kejang]" id="kesadaran_kejang"  onclick="checkthis('kesadaran_kejang')">
          <span class="lbl" > &nbsp; Kejang</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kesadaran_no_response]" id="kesadaran_no_response"  onclick="checkthis('kesadaran_no_response')">
          <span class="lbl" > &nbsp; Tidak ada respon</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kuning_gcs]" id="kuning_gcs"  onclick="checkthis('kuning_gcs')">
          <span class="lbl" > &nbsp; GCS > 12</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[nyeri_dada]" id="nyeri_dada"  onclick="checkthis('nyeri_dada')">
          <span class="lbl" > &nbsp; Nyeri Dada</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[gcs_15]" id="gcs_15"  onclick="checkthis('gcs_15')">
          <span class="lbl" > &nbsp; GCS 15</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[gcs_3]" id="gcs_3"  onclick="checkthis('gcs_3')">
          <span class="lbl" > &nbsp; GCS 3</span>
        </label>
      </div>
    </td>
    
  </tr>
</table>

<br>
<table class="table">
  <tr><td style="background: #f4ae11; color: black;" colspan="2" align="center"><span style="font-size: 16px; font-weight: bold">P E N G K A J I A N</span></td></tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Tekanan Intrakranial</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[interakranial_1]" id="interakranial_1"  onclick="checkthis('interakranial_1')">
          <span class="lbl" > &nbsp; Tidak ada kelainan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[interakranial_sakit_kepala]" id="interakranial_sakit_kepala"  onclick="checkthis('interakranial_sakit_kepala')">
          <span class="lbl" > &nbsp; Sakit Kepala</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[interakranial_muntah]" id="interakranial_muntah"  onclick="checkthis('interakranial_muntah')">
          <span class="lbl" > &nbsp; Muntah</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[interakranial_pusing]" id="interakranial_pusing"  onclick="checkthis('interakranial_pusing')">
          <span class="lbl" > &nbsp; Pusing</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[interakranial_bingung]" id="interakranial_bingung"  onclick="checkthis('interakranial_bingung')">
          <span class="lbl" > &nbsp; Bingung</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Pupil</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[pupil_normal]" id="pupil_normal"  onclick="checkthis('pupil_normal')">
          <span class="lbl" > &nbsp; Normal</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[pupil_miosis]" id="pupil_miosis"  onclick="checkthis('pupil_miosis')">
          <span class="lbl" > &nbsp; Miosis</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[pupil_midriasis]" id="pupil_midriasis"  onclick="checkthis('pupil_midriasis')">
          <span class="lbl" > &nbsp; Midriasis</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[pupil_isokor]" id="pupil_isokor"  onclick="checkthis('pupil_isokor')">
          <span class="lbl" > &nbsp; Isokor</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[pupil_anisokor]" id="pupil_anisokor"  onclick="checkthis('pupil_anisokor')">
          <span class="lbl" > &nbsp; Anisokor</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Mukosa Mulut</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[mm_lembab]" id="mm_lembab"  onclick="checkthis('mm_lembab')">
          <span class="lbl" > &nbsp; Lembab</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[mm_kering]" id="mm_kering"  onclick="checkthis('mm_kering')">
          <span class="lbl" > &nbsp; Kering</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Gastrointestinal</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[gastrointestinal_mual]" id="gastrointestinal_mual"  onclick="checkthis('gastrointestinal_mual')">
          <span class="lbl" > &nbsp; Mual</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[gastrointestinal_muntah]" id="gastrointestinal_muntah"  onclick="checkthis('gastrointestinal_muntah')">
          <span class="lbl" > &nbsp; Muntah</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[gastrointestinal_nyeri_perut]" id="gastrointestinal_nyeri_perut"  onclick="checkthis('gastrointestinal_nyeri_perut')">
          <span class="lbl" > &nbsp; Nyeri Perut</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[gastrointestinal_lain]" id="gastrointestinal_lain"  onclick="checkthis('gastrointestinal_lain')">
          <span class="lbl" > &nbsp; Lain-lain</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Neuro Sensorik</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[ns_no]" id="ns_no"  onclick="checkthis('ns_no')">
          <span class="lbl" > &nbsp; Tidak ada kelainan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[ns_berubah]" id="ns_berubah"  onclick="checkthis('ns_berubah')">
          <span class="lbl" > &nbsp; Perubahan Sensorik</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[ns_motorik]" id="ns_motorik"  onclick="checkthis('ns_motorik')">
          <span class="lbl" > &nbsp; Perubahan Motorik</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[ns_spasme_otot]" id="ns_spasme_otot"  onclick="checkthis('ns_spasme_otot')">
          <span class="lbl" > &nbsp; Spasme Otot</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Muskulo Skeletal</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[ms_berubah_bentuk]" id="ms_berubah_bentuk"  onclick="checkthis('ms_berubah_bentuk')">
          <span class="lbl" > &nbsp; Perubahan Bentuk</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[ms_ektrimitas]" id="ms_ektrimitas"  onclick="checkthis('ms_ektrimitas')">
          <span class="lbl" > &nbsp; Ekstremitas</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[ms_fraktur]" id="ms_fraktur"  onclick="checkthis('ms_fraktur')">
          <span class="lbl" > &nbsp; Fraktur</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[ms_dislokasi]" id="ms_dislokasi"  onclick="checkthis('ms_dislokasi')">
          <span class="lbl" > &nbsp; Dislokasi</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[ms_luksasio]" id="ms_luksasio"  onclick="checkthis('ms_luksasio')">
          <span class="lbl" > &nbsp; Luksasio</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Integumen</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[integumen_no]" id="integumen_no"  onclick="checkthis('integumen_no')">
          <span class="lbl" > &nbsp; Tidak ada kelainan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[integumen_luka_bakar]" id="integumen_luka_bakar"  onclick="checkthis('integumen_luka_bakar')">
          <span class="lbl" > &nbsp; Luka Bakar</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[integumen_luka_robek]" id="integumen_luka_robek"  onclick="checkthis('integumen_luka_robek')">
          <span class="lbl" > &nbsp; Luka Robek</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[integumen_lecet]" id="integumen_lecet"  onclick="checkthis('integumen_lecet')">
          <span class="lbl" > &nbsp; Lecet</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[integumen_decubitus]" id="integumen_decubitus"  onclick="checkthis('integumen_decubitus')">
          <span class="lbl" > &nbsp; Decubitus</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[integumen_gangren]" id="integumen_gangren"  onclick="checkthis('integumen_gangren')">
          <span class="lbl" > &nbsp; Gangren</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Turgor Kulit</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[turgor_kulit_baik]" id="turgor_kulit_baik"  onclick="checkthis('turgor_kulit_baik')">
          <span class="lbl" > &nbsp; Baik</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[turgor_kulit_menurun]" id="turgor_kulit_menurun"  onclick="checkthis('turgor_kulit_menurun')">
          <span class="lbl" > &nbsp; Menurun</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Edema</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[edema_no]" id="edema_no"  onclick="checkthis('edema_no')">
          <span class="lbl" > &nbsp; Tidak ada </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[edema_yes]" id="edema_yes"  onclick="checkthis('edema_yes')">
          <span class="lbl" > &nbsp; Ada</span>
        </label>
        <label>Keterangan : </label>
        <input type="text" style="width: 300px" name="form_27[ket_edema_yes]" id="ket_edema_yes"  onchange="fillthis('ket_edema_yes')">
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Perdarahan</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[perdarahan_no]" id="perdarahan_no"  onclick="checkthis('perdarahan_no')">
          <span class="lbl" > &nbsp; Tidak ada </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[perdarahan_yes]" id="perdarahan_yes"  onclick="checkthis('perdarahan_yes')">
          <span class="lbl" > &nbsp; Ada</span>
        </label>
        <label>Keterangan : </label>
        <input type="text" style="width: 300px" name="form_27[ket_perdarahan_yes]" id="ket_perdarahan_yes"  onchange="fillthis('ket_perdarahan_yes')">
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Intoksikasi</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[intoksikasi_no]" id="intoksikasi_no"  onclick="checkthis('intoksikasi_no')">
          <span class="lbl" > &nbsp; Tidak ada </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[intoksikasi_yes]" id="intoksikasi_yes"  onclick="checkthis('intoksikasi_yes')">
          <span class="lbl" > &nbsp; Ada</span>
        </label>
        <label>Keterangan : </label>
        <input type="text" style="width: 300px" name="form_27[ket_intoksikasi_yes]" id="ket_intoksikasi_yes"  onchange="fillthis('ket_intoksikasi_yes')">
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Eliminasi BAB</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[eliminasi_bab_no]" id="eliminasi_bab_no"  onclick="checkthis('eliminasi_bab_no')">
          <span class="lbl" > &nbsp; Tidak ada keluhan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[eliminasi_bab_yes]" id="eliminasi_bab_yes"  onclick="checkthis('eliminasi_bab_yes')">
          <span class="lbl" > &nbsp; Ada</span>
        </label>
        <label>Frekuensi : </label>
        <input type="text" style="width: 50px" name="form_27[frekuensi_eliminasi_bab_yes]" id="frekuensi_eliminasi_bab_yes" onchange="fillthis('frekuensi_eliminasi_bab_yes')"> &nbsp; x 
        <label>Konsistensi : </label>
        <input type="text" style="width: 50px" name="form_27[konsistensi_eliminasi_bab_yes]" id="konsistensi_eliminasi_bab_yes" onchange="fillthis('konsistensi_eliminasi_bab_yes')"> 
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Eliminasi BAK</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[eliminasi_bak_no]" id="eliminasi_bak_no"  onclick="checkthis('eliminasi_bak_no')">
          <span class="lbl" > &nbsp; Tidak ada keluhan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[eliminasi_bak_yes]" id="eliminasi_bak_yes"  onclick="checkthis('eliminasi_bak_yes')">
          <span class="lbl" > &nbsp; Ada</span>
        </label>
        <label>Frekuensi : </label>
        <input type="text" style="width: 50px" name="form_27[frekuensi_eliminasi_bak_yes]" id="frekuensi_eliminasi_bak_yes" onchange="fillthis('frekuensi_eliminasi_bak_yes')"> &nbsp; x 
        <label>Konsistensi : </label>
        <input type="text" style="width: 50px" name="form_27[konsistensi_eliminasi_bak_yes]" id="konsistensi_eliminasi_bak_yes" onchange="fillthis('konsistensi_eliminasi_bak_yes')"> 
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Keterangan Lainnya</td>
    <td>
      <textarea style="width: 100%; height: 50px !important" name="form_27[keterangan_lainnya]" id="keterangan_lainnya" onchange="fillthis('keterangan_lainnya')"></textarea>
    </td>
  </tr>
</table>

<br>
<table class="table">
  <tr><td style="background: #f4ae11; color: black;" colspan="2" align="center"><span style="font-size: 16px; font-weight: bold">PSIKOSOSAL DAN EKONOMI</span></td></tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Kecemasan</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kecemasan_no]" id="kecemasan_no"  onclick="checkthis('kecemasan_no')">
          <span class="lbl" > &nbsp; Tidak ada keluhan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kecemasan_sedang]" id="kecemasan_sedang"  onclick="checkthis('kecemasan_sedang')">
          <span class="lbl" > &nbsp; Sedang</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kecemasan_berat]" id="kecemasan_berat"  onclick="checkthis('kecemasan_berat')">
          <span class="lbl" > &nbsp; Berat</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kecemasan_panik]" id="kecemasan_panik"  onclick="checkthis('kecemasan_panik')">
          <span class="lbl" > &nbsp; Panik</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kecemasan_sulit_dinilai]" id="kecemasan_sulit_dinilai"  onclick="checkthis('kecemasan_sulit_dinilai')">
          <span class="lbl" > &nbsp; Sulit dinilai</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Koping</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[koping_merusak_diri]" id="koping_merusak_diri"  onclick="checkthis('koping_merusak_diri')">
          <span class="lbl" > &nbsp; Merusak Diri</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[koping_menarik_diri]" id="koping_menarik_diri"  onclick="checkthis('koping_menarik_diri')">
          <span class="lbl" > &nbsp; Menarik Diri</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[koping_pelaku_kekerasan]" id="koping_pelaku_kekerasan"  onclick="checkthis('koping_pelaku_kekerasan')">
          <span class="lbl" > &nbsp; Pelaku Kekerasan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[koping_sulit_dinilai]" id="koping_sulit_dinilai"  onclick="checkthis('koping_sulit_dinilai')">
          <span class="lbl" > &nbsp; Sulit dinilai</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Kebiasaan</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kebiasaan_merokok]" id="kebiasaan_merokok"  onclick="checkthis('kebiasaan_merokok')">
          <span class="lbl" > &nbsp; Merokok</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kebiasaan_alkohol]" id="kebiasaan_alkohol"  onclick="checkthis('kebiasaan_alkohol')">
          <span class="lbl" > &nbsp; Alkohol</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kebiasaan_lainnya]" id="kebiasaan_lainnya"  onclick="checkthis('kebiasaan_lainnya')">
          <span class="lbl" > &nbsp; Lainnya</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle !important; font-weight: bold">Keterangan Lainnya</td>
    <td>
      <textarea style="width: 100%; height: 50px !important" name="form_27[keterangan_lainnya_psikososial]" id="keterangan_lainnya_psikososial"  onchange="fillthis('keterangan_lainnya_psikososial')"></textarea>
    </td>
  </tr>
</table>

<br>
<table class="table">
  <tr><td style="background: #f4ae11; color: black;" colspan="3" align="center"><span style="font-size: 16px; font-weight: bold">PEMERIKSAAN FISIK DAN SKRINING GIZI</span></td></tr>
  <tr>
    <td>1.</td>
    <td width="150px">Apakah pasien mengalami penurunan BB yang tidak diinginkan dalam 6 bulan terakhir ?</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[no_penurunan_badan]" id="no_penurunan_badan"  onclick="checkthis('no_penurunan_badan')">
          <span class="lbl" > &nbsp; Tidak ada penurunan berat badan (skor 0)</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[tidak_yakin_penurunan_badan]" id="tidak_yakin_penurunan_badan"  onclick="checkthis('tidak_yakin_penurunan_badan')">
          <span class="lbl" > &nbsp; Tidak yakin / tidak tahu / terasa baju lebih longgar (skor 2)</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[ya_penurunan_badan]" id="ya_penurunan_badan"  onclick="checkthis('ya_penurunan_badan')">
          <span class="lbl" > &nbsp; Ya, beberapa penurunan berat badan tersebut</span>
        </label>
        <div style="padding-left: 30px">
          <label>
            <input type="checkbox" class="ace" name="form_27[penurunan_badan_1]" id="penurunan_badan_1"  onclick="checkthis('penurunan_badan_1')">
            <span class="lbl" > &nbsp; 1 - 5 kg (skor 1)</span>
          </label>
          <br>
          <label>
            <input type="checkbox" class="ace" name="form_27[penurunan_badan_2]" id="penurunan_badan_2"  onclick="checkthis('penurunan_badan_2')">
            <span class="lbl" > &nbsp; 6 - 10 kg (skor 2)</span>
          </label>
          <br>
          <label>
            <input type="checkbox" class="ace" name="form_27[penurunan_badan_3]" id="penurunan_badan_3"  onclick="checkthis('penurunan_badan_3')">
            <span class="lbl" > &nbsp; 11 - 15 kg (skor 3)</span>
          </label>
          <br>
          <label>
            <input type="checkbox" class="ace" name="form_27[penurunan_badan_4]" id="penurunan_badan_4"  onclick="checkthis('penurunan_badan_4')">
            <span class="lbl" > &nbsp; > 15 kg (skor 4)</span>
          </label>
        </div>

      </div>
    </td>
  </tr>
  <tr>
    <td>2.</td>
    <td width="150px">Apakah asupan makanan berkurang karena tidak nafsu makan?</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[asupan_makanan_1]" id="asupan_makanan_1"  onclick="checkthis('asupan_makanan_1')">
          <span class="lbl" > &nbsp; Ya (skor 0)</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[asupan_makanan_2]" id="asupan_makanan_2"  onclick="checkthis('asupan_makanan_2')">
          <span class="lbl" > &nbsp; Tidak (skor 1)</span>
        </label>

      </div>
    </td>
  </tr>
  <tr>
    <td>3.</td>
    <td width="150px">Pasien dengan diagnosa khusus</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[diagnosa_khusus_ya]" id="diagnosa_khusus_ya"  onclick="checkthis('diagnosa_khusus_ya')">
          <span class="lbl" > &nbsp; Ya </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[diagnosa_khusus_no]" id="diagnosa_khusus_no"  onclick="checkthis('diagnosa_khusus_no')">
          <span class="lbl" > &nbsp; Tidak </span>
        </label>
        <br>
        <div style="padding: 5px">
          ( DM / Kemoterapi / Hemodialisa / Geriatri / Immunitas menurun )
        </div>
        
        <div style="padding: 5px; width: 100%">
          <input type="text" style="padding: 5px; width: 100%" placeholder="sebutkan diagnosa khususnya"  name="form_27[diagnosa_khusus_lainnya]" id="diagnosa_khusus_lainnya"  onchange="fillthis('diagnosa_khusus_lainnya')">
        </div>

      </div>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">( Bila skor > 2 dan atau pasien dengan diagnosis / kondisi khusus dilaporkan ke dokter pemeriksa )</td>
  </tr>
</table>

<br>
<table class="table">
  <tr><td style="background: #f4ae11; color: black;" colspan="3" align="center"><span style="font-size: 16px; font-weight: bold">SKRINING STATUS FUNGSIONAL</span></td></tr>
  <tr>
    <td style="width: 33%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[ssf_mandiri]" id="ssf_mandiri"  onclick="checkthis('ssf_mandiri')">
          <span class="lbl" > &nbsp; Mandiri</span>
        </label>
      </div>
    </td>
    <td style="width: 33%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[ssf_perlu_bantuan]" id="ssf_perlu_bantuan"  onclick="checkthis('ssf_perlu_bantuan')">
          <span class="lbl" > &nbsp; Perlu bantuan</span>
        </label>
        <br>
        <div style="padding: 5px">
          <textarea style="width: 100% !important; height: 50px !important; padding: 5px" name="form_27[ket_ssf_perlu_bantuan]" id="ket_ssf_perlu_bantuan"  onclick="checkthis('ket_ssf_perlu_bantuan')"></textarea>
        </div>
      </div>
    </td>
    <td style="width: 33%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[ssf_ketergantungan]" id="ssf_ketergantungan"  onclick="checkthis('ssf_ketergantungan')">
          <span class="lbl" > &nbsp; Ketergantungan total</span>
        </label>
      </div>
    </td>
  </tr>
</table>

<br>
<table class="table">
  <tr><td style="background: #f4ae11; color: black;" colspan="3" align="center"><span style="font-size: 16px; font-weight: bold">SKRINING RESIKO JATUH / CEDERA</span></td></tr>
  <tr>
    <td>1.</td>
    <td>Apakah pasien tampak tidak seimbang (sempoyongan / limbung) saat berjalan ?</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[seimbang]" id="seimbang"  onclick="checkthis('seimbang')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[tidak_seimbang]" id="tidak_seimbang"  onclick="checkthis('tidak_seimbang')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td>2.</td>
    <td>Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan duduk ?</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[pasien_menopang_yes]" id="pasien_menopang_yes"  onclick="checkthis('pasien_menopang_yes')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[pasien_menopang_no]" id="pasien_menopang_no"  onclick="checkthis('pasien_menopang_no')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">
      Hasil :<br>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[hasil_tidak_berisiko]" id="hasil_tidak_berisiko"  onclick="checkthis('hasil_tidak_berisiko')">
          <span class="lbl" > &nbsp; Tidak Beresiko</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[hasil_risiko_rendah]" id="hasil_risiko_rendah"  onclick="checkthis('hasil_risiko_rendah')">
          <span class="lbl" > &nbsp; Resiko Rendah (ditemukan a atau b)</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[hasil_risiko_tinggi]" id="hasil_risiko_tinggi"  onclick="checkthis('hasil_risiko_tinggi')">
          <span class="lbl" > &nbsp; Resiko Tinggi  (ditemukan a dan b)</span>
        </label>
      </div>
      <br>
      Dilaporkan ke dokter :
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[dilaporkan_dokter_yes]" id="dilaporkan_dokter_yes"  onclick="checkthis('dilaporkan_dokter_yes')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[dilaporkan_dokter_no]" id="dilaporkan_dokter_no"  onclick="checkthis('dilaporkan_dokter_no')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
        <label>
          Pukul, <input type="text" name="form_27[dilaporkan_dokter_pukul]" id="dilaporkan_dokter_pukul"  onclick="fillthis('dilaporkan_dokter_pukul')">
        </label>
      </div>
    </td>
    <td>
      
    </td>
  </tr>
</table>

<br>
<table class="table">
  <tr><td style="background: #f4ae11; color: black;" colspan="2" align="center"><span style="font-size: 16px; font-weight: bold">PENILAIAN TINGKAT NYERI</span></td></tr>
  <tr>
    <td><b>Keluhan Nyeri</b></td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[tidak_nyeri]" id="tidak_nyeri"  onclick="checkthis('tidak_nyeri')">
          <span class="lbl" > &nbsp; Tidak ada</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[ada_nyeri]" id="ada_nyeri"  onclick="checkthis('ada_nyeri')">
          <span class="lbl" > &nbsp; Ada</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td><b>Pencetus/Provoke</b></td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[provoke_benturan]" id="provoke_benturan"  onclick="checkthis('provoke_benturan')">
          <span class="lbl" > &nbsp; Benturan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[provoke_tindakan]" id="provoke_tindakan"  onclick="checkthis('provoke_tindakan')">
          <span class="lbl" > &nbsp; Tindakan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[provoke_proses_penyakit]" id="provoke_proses_penyakit"  onclick="checkthis('provoke_proses_penyakit')">
          <span class="lbl" > &nbsp; Proses Penyakit</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[provoke_lainnya]" id="provoke_lainnya"  onclick="checkthis('provoke_lainnya')">
          <span class="lbl" > &nbsp; Lain-lain</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td><b>Kualitas/Quality</b></td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[seperti_tertusuk]" id="seperti_tertusuk"  onclick="checkthis('seperti_tertusuk')">
          <span class="lbl" > &nbsp; Seperti tertusuk tajam/tumpul</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kualitas_berdenyut]" id="kualitas_berdenyut"  onclick="checkthis('kualitas_berdenyut')">
          <span class="lbl" > &nbsp; Berdenyut</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kualitas_terbakar]" id="kualitas_terbakar"  onclick="checkthis('kualitas_terbakar')">
          <span class="lbl" > &nbsp; Terbakar</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kualitas_tertindih]" id="kualitas_tertindih"  onclick="checkthis('kualitas_tertindih')">
          <span class="lbl" > &nbsp; Tertindih benda berat</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kualitas_diremas]" id="kualitas_diremas"  onclick="checkthis('kualitas_diremas')">
          <span class="lbl" > &nbsp; Diremas</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kualitas_terpelintir]" id="kualitas_terpelintir"  onclick="checkthis('kualitas_terpelintir')">
          <span class="lbl" > &nbsp; Terpelintir</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[kualitas_teriris]" id="kualitas_teriris"  onclick="checkthis('kualitas_teriris')">
          <span class="lbl" > &nbsp; Teriris</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td><b>Radiasi/Region</b></td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[radiasi_lokasi]" id="radiasi_lokasi"  onclick="checkthis('radiasi_lokasi')">
          <span class="lbl" > &nbsp; Lokasi</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[radiasi_menyebar]" id="radiasi_menyebar"  onclick="checkthis('radiasi_menyebar')">
          <span class="lbl" > &nbsp; Menyebar</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[radiasi_no]" id="radiasi_no"  onclick="checkthis('radiasi_no')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[radiasi_yes]" id="radiasi_yes"  onclick="checkthis('radiasi_yes')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td><b>Skala/Severity</b></td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[skala_flacss]" id="skala_flacss"  onclick="checkthis('skala_flacss')">
          <span class="lbl" > &nbsp; FLACSS</span>
        </label>
        <label>
          Score <input type="text" style="width: 50px !important" name="form_27[score_skala_flacss]" id="score_skala_flacss"  onchange="fillthis('score_skala_flacss')">
        </label>
        <label>
          Wong Baker Faces, Score <input type="text" style="width: 50px !important" name="form_27[score_wbf]" id="score_wbf"  onchange="fillthis('score_wbf')">
        </label>
        <label>
          VAS/NRS Score <input type="text" style="width: 50px !important" name="form_27[score_vas]" id="score_vas"  onchange="fillthis('score_vas')">
        </label>

      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[skala_bps]" id="skala_bps"  onclick="checkthis('skala_bps')">
          <span class="lbl" > &nbsp; BPS</span>
        </label>
        <label>
          Score <input type="text" style="width: 50px !important" name="form_27[score_bps]" id="score_bps"  onchange="fillthis('score_bps')">
        </label>

      </div>
    </td>
  </tr>
  <tr>
    <td><b>Durasi/Times</b></td>
    <td>
      <div style="width: 100% !important">
          Kapan mulai dirasa : 
          <input type="text" style="width: 100% !important" name="form_27[durasi_dirasa]" id="durasi_dirasa"  onchange="fillthis('durasi_dirasa')">
      </div>
      <div style="width: 100% !important">
          Berapa lama dirasa/ kekambuhan : 
          <input type="text" style="width: 100% !important" name="form_27[berapa_lama_dirasa]" id="berapa_lama_dirasa"  onchange="fillthis('berapa_lama_dirasa')">
      </div>
    </td>
  </tr>

  
  
</table>

<br>
<table class="table">
  <tr><td style="background: #f4ae11; color: black;" colspan="2" align="center"><span style="font-size: 16px; font-weight: bold">MASALAH KEPERAWATAN</span></td></tr>
  <tr>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[bersih_jalan_nafas]" id="bersih_jalan_nafas"  onclick="checkthis('bersih_jalan_nafas')">
          <span class="lbl" > &nbsp; Bersihin jalan nafas tidak efektif</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[pola_nafas_tidak_efektif]" id="pola_nafas_tidak_efektif"  onclick="checkthis('pola_nafas_tidak_efektif')">
          <span class="lbl" > &nbsp; Pola nafas tidak efektif gangguan</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[gangguan_pertukaran_gas]" id="gangguan_pertukaran_gas"  onclick="checkthis('gangguan_pertukaran_gas')">
          <span class="lbl" > &nbsp; Gangguan pertukaran gas</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[sirkulasi]" id="sirkulasi"  onclick="checkthis('sirkulasi')">
          <span class="lbl" > &nbsp; Sirkulasi</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[resiko_keseimbangan_cairan]" id="resiko_keseimbangan_cairan"  onclick="checkthis('resiko_keseimbangan_cairan')">
          <span class="lbl" > &nbsp; Resiko keseimbangan cairan</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[resiko_perfusi]" id="resiko_perfusi"  onclick="checkthis('resiko_perfusi')">
          <span class="lbl" > &nbsp; Resiko perfusi jaringan serebral</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[hipertemi]" id="hipertemi"  onclick="checkthis('hipertemi')">
          <span class="lbl" > &nbsp; Hipertermi</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[masalah_keperawatan_nyeri_akut]" id="masalah_keperawatan_nyeri_akut"  onclick="checkthis('masalah_keperawatan_nyeri_akut')">
          <span class="lbl" > &nbsp; Nyeri akut</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[nyeri_kronik]" id="nyeri_kronik"  onclick="checkthis('nyeri_kronik')">
          <span class="lbl" > &nbsp; Nyeri kronik</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[syok_hipovolemik]" id="syok_hipovolemik"  onclick="checkthis('syok_hipovolemik')">
          <span class="lbl" > &nbsp; Syok hipovolemik</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[syok_kardiogenik]" id="syok_kardiogenik"  onclick="checkthis('syok_kardiogenik')">
          <span class="lbl" > &nbsp; Syok kardiogenik</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[syok_anafilaktik]" id="syok_anafilaktik"  onclick="checkthis('syok_anafilaktik')">
          <span class="lbl" > &nbsp; Syok anafilaktik</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[syok_septic]" id="syok_septic"  onclick="checkthis('syok_septic')">
          <span class="lbl" > &nbsp; Syok septic</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[masalah_keperawatan_lainnya]" id="masalah_keperawatan_lainnya"  onclick="checkthis('masalah_keperawatan_lainnya')">
          <span class="lbl" > &nbsp; Lainnya</span>
        </label>
      </div>
      <input type="text" style="width: 100% !important" name="form_27[txt_masalah_keperawatan_lainnya]" id="txt_masalah_keperawatan_lainnya"  placeholder="Masalah keperawatan lainnya" onchange="fillthis('txt_masalah_keperawatan_lainnya')">
    </td>
  </tr>
</table>

<br>
<table class="table">
  <tr><td style="background: #f4ae11; color: black;" colspan="5" align="center"><span style="font-size: 16px; font-weight: bold">KOLABORASI</span></td></tr>
  <tr>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_oksigenisasi]" id="kolab_oksigenisasi"  onclick="checkthis('kolab_oksigenisasi')">
          <span class="lbl" > &nbsp; Oksigenisasi</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_nebulizer]" id="kolab_nebulizer"  onclick="checkthis('kolab_nebulizer')">
          <span class="lbl" > &nbsp; Nebulizer</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_ivfd]" id="kolab_ivfd"  onclick="checkthis('kolab_ivfd')">
          <span class="lbl" > &nbsp; IVFD</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_ekg]" id="kolab_ekg"  onclick="checkthis('kolab_ekg')">
          <span class="lbl" > &nbsp; EKG</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_transfusi_darah]" id="kolab_transfusi_darah"  onclick="checkthis('kolab_transfusi_darah')">
          <span class="lbl" > &nbsp; Transfusi Darah</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_ngt]" id="kolab_ngt"  onclick="checkthis('kolab_ngt')">
          <span class="lbl" > &nbsp; NGT</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_dc_shock]" id="kolab_dc_shock"  onclick="checkthis('kolab_dc_shock')">
          <span class="lbl" > &nbsp; DC Shock</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_rontgen]" id="kolab_rontgen"  onclick="checkthis('kolab_rontgen')">
          <span class="lbl" > &nbsp; Rontgen</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_obat]" id="kolab_obat"  onclick="checkthis('kolab_obat')">
          <span class="lbl" > &nbsp; Obat</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_bilas_lambung]" id="kolab_bilas_lambung"  onclick="checkthis('kolab_bilas_lambung')">
          <span class="lbl" > &nbsp; Bilas Lambung</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_laboratorium]" id="kolab_laboratorium"  onclick="checkthis('kolab_laboratorium')">
          <span class="lbl" > &nbsp; Laboratorium</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_suction]" id="kolab_suction"  onclick="checkthis('kolab_suction')">
          <span class="lbl" > &nbsp; Suction</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_irigasi_mata]" id="kolab_irigasi_mata"  onclick="checkthis('kolab_irigasi_mata')">
          <span class="lbl" > &nbsp; Irigasi Mata</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[kolab_kateter]" id="kolab_kateter"  onclick="checkthis('kolab_kateter')">
          <span class="lbl" > &nbsp; Kateter</span>
        </label>
      </div>
    </td>
  </tr>
</table>

<br>
<table class="table">
  <tr><td style="background: #f4ae11; color: black;" colspan="5" align="center"><span style="font-size: 16px; font-weight: bold">EVALUASI (SOAP)</span></td></tr>
</table>

<div>

  <span style="font-weight: bold; font-style: italic; color: blue">(Subjective)</span>
  <div style="margin-top: 6px">
      <label for="form-field-8"> Anamnesa / Keluhan Pasien <span style="color:red">* </span> <small>(minimal 8 karakter)</small> </label>
      <textarea class="form-control" style="height: 100px !important" name="form_27[subjective_anamnesa]" id="subjective_anamnesa"  onchange="fillthis('subjective_anamnesa')"></textarea>
  </div>
  <br>

  <span style="font-weight: bold; font-style: italic; color: blue">(Objective)</span>

  <div style="margin-top: 6px">
      <label for="form-field-8"> Pemeriksaan Fisik </label>
      <textarea class="form-control" style="height: 100px !important" name="form_27[objective_pemeriksaan_fisik]" id="objective_pemeriksaan_fisik"  onchange="fillthis('objective_pemeriksaan_fisik')"></textarea>
  </div>

  <span style="font-weight: bold; font-style: italic; color: blue">(Assesment)</span>
  <div style="margin-top: 6px">
      <label for="form-field-8">Diagnosa Keperawatan(ICD10) <span style="color:red">* </span></label>
      <input type="text" class="form-control" placeholder="Masukan keyword ICD 10" value="" name="form_27[assesmen_diagnosa_primer]" id="assesmen_diagnosa_primer"  onchange="fillthis('assesmen_diagnosa_primer')">
      <input type="hidden" class="form-control" value="" name="form_27[assesmen_diagnosa_primer_hidden]" id="assesmen_diagnosa_primer_hidden">
  </div>

  <div style="margin-top: 6px">
      <label for="form-field-8">Diagnosa Sekunder (ICD10)</label>
      <input type="text" class="form-control"  placeholder="Masukan keyword ICD 10" value="" name="form_27[diagnosa_sekunder]" id="diagnosa_sekunder"  onchange="fillthis('diagnosa_sekunder')">
  </div>
  <br>
  <span style="font-weight: bold; font-style: italic; color: blue">(Planning)</span>
  <div style="margin-top: 6px">
      <label for="form-field-8"> Rencana Asuhan / Anjuran Dokter </label>
      <textarea class="form-control" style="height: 100px !important" name="form_27[planning_anjuran_dokter]" id="planning_anjuran_dokter"  onchange="fillthis('planning_anjuran_dokter')"></textarea>
  </div>

</div>

<br>
<table class="table">
  <tr><td style="background: #f4ae11; color: black;" colspan="5" align="center"><span style="font-size: 16px; font-weight: bold">HASIL PENANGANAN</span></td></tr>
  <tr>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[penanganan_ri]" id="penanganan_ri"  onclick="checkthis('penanganan_ri')">
          <span class="lbl" > &nbsp; Rawat Inap</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[penanganan_icu]" id="penanganan_icu"  onclick="checkthis('penanganan_icu')">
          <span class="lbl" > &nbsp; ICU</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[penanganan_kamar_besalin]" id="penanganan_kamar_besalin"  onclick="checkthis('penanganan_kamar_besalin')">
          <span class="lbl" > &nbsp; Kamar Bersalin</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[penanganan_kamar_bedah]" id="penanganan_kamar_bedah"  onclick="checkthis('penanganan_kamar_bedah')">
          <span class="lbl" > &nbsp; Kamar Bedah</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[penanganan_hd]" id="penanganan_hd"  onclick="checkthis('penanganan_hd')">
          <span class="lbl" > &nbsp; HD</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[penanganan_perina]" id="penanganan_perina"  onclick="checkthis('penanganan_perina')">
          <span class="lbl" > &nbsp; Perinatologi</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[penanganan_menolak_ri]" id="penanganan_menolak_ri"  onclick="checkthis('penanganan_menolak_ri')">
          <span class="lbl" > &nbsp; Menolak Rawat Inap</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[penanganan_dirujuk]" id="penanganan_dirujuk"  onclick="checkthis('penanganan_dirujuk')">
          <span class="lbl" > &nbsp; Dirujuk</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[penanganan_pulang]" id="penanganan_pulang"  onclick="checkthis('penanganan_pulang')">
          <span class="lbl" > &nbsp; Pulang</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[penanganan_meninggal]" id="penanganan_meninggal"  onclick="checkthis('penanganan_meninggal')">
          <span class="lbl" > &nbsp; Meninggal</span>
        </label>
      </div>
    </td>
  </tr>
</table>

<br>
<table class="table">
  <tr><td style="background: #f4ae11; color: black;" colspan="5" align="center"><span style="font-size: 16px; font-weight: bold">DISCHARGE PLANNING</span></td></tr>
  <tr>
    <td width="50px" align="center">1.</td>
    <td>Umur > 65</td>
    <td width="200px">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[umur_65_ya]" id="umur_65_ya"  onclick="checkthis('umur_65_ya')">
          <span class="lbl" > &nbsp; Ya </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[umur_65_no]" id="umur_65_no"  onclick="checkthis('umur_65_no')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td width="50px" align="center">2.</td>
    <td>Keterbatasan Mobilitas</td>
    <td width="200px">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[mobilitas_no]" id="mobilitas_no"  onclick="checkthis('mobilitas_no')">
          <span class="lbl" > &nbsp; Ya </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[mobilitas_yes]" id="mobilitas_yes"  onclick="checkthis('mobilitas_yes')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td width="50px" align="center">3.</td>
    <td>Perawatan atau pengobatan lanjutan</td>
    <td width="200px">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[pengobatan_lanjutan_yes]" id="pengobatan_lanjutan_yes"  onclick="checkthis('pengobatan_lanjutan_yes')">
          <span class="lbl" > &nbsp; Ya </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[pengobatan_lanjutan_no]" id="pengobatan_lanjutan_no"  onclick="checkthis('pengobatan_lanjutan_no')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td width="50px" align="center">4.</td>
    <td>Bantuan untuk melakukan aktifitas sehari-hari</td>
    <td width="200px">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[bantuan_aktifitas_yes]" id="bantuan_aktifitas_yes"  onclick="checkthis('bantuan_aktifitas_yes')">
          <span class="lbl" > &nbsp; Ya </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_27[bantuan_aktifitas_no]" id="bantuan_aktifitas_no"  onclick="checkthis('bantuan_aktifitas_no')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
      </div>
    </td>
  </tr>
</table>

<br>
<table class="table">
  <tr>
    <td colspan="2">Bila salah satu jawaban "ya" dari kriteria perencanaan pulang diatas, maka akan dilanjutkan dengan perencanaan pulang sebagai berikut : </td>
  </tr>
  <tr>
    <td width="200px">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[perawatan_diri]" id="perawatan_diri"  onclick="checkthis('perawatan_diri')">
          <span class="lbl" > &nbsp; Perawatan diri (mandi, BAB, BAK) </span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[pemantauan_obat]" id="pemantauan_obat"  onclick="checkthis('pemantauan_obat')">
          <span class="lbl" > &nbsp; Pemantauan pemberian obat</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[pemantauan_diet]" id="pemantauan_diet"  onclick="checkthis('pemantauan_diet')">
          <span class="lbl" > &nbsp; Pemantauan diet</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[perawatan_luka]" id="perawatan_luka"  onclick="checkthis('perawatan_luka')">
          <span class="lbl" > &nbsp; Perawatan Luka</span>
        </label>
      </div>
    </td>
    <td width="300px">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[latihan_fisik_lanjutan]" id="latihan_fisik_lanjutan"  onclick="checkthis('latihan_fisik_lanjutan')">
          <span class="lbl" > &nbsp; Latihan fisik lanjutan </span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[pendampingan_tenaga_khusus]" id="pendampingan_tenaga_khusus"  onclick="checkthis('pendampingan_tenaga_khusus')">
          <span class="lbl" > &nbsp; Pendampingan tenaga khusus di rumah</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[bantuan_medis]" id="bantuan_medis"  onclick="checkthis('bantuan_medis')">
          <span class="lbl" > &nbsp; Bantuan medis/ perawatan dirumah (home care)</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_27[bantuan_aktifitas_fisik]" id="bantuan_aktifitas_fisik"  onclick="checkthis('bantuan_aktifitas_fisik')">
          <span class="lbl" > &nbsp; Bantuan untuk melakukan aktifitas fisik (kursi roda, alat bantu jalan)</span>
        </label>
      </div>
    </td>
  </tr>
</table>

<br>
<hr>
<?php echo $footer; ?>

