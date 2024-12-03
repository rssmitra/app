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

<div style="text-align: center; font-size: 14px"><b>PENGKAJIAN AWAL KEPERAWATAN RAWAT INAP DEWASA</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<table class="table">
  <tr>
    <td width="30%" valign="middle" >Masuk ruang rawat </td>
    <td width="70%"> <input class="input_type" type="text" style="width: 100px" name="form_30[30_masuk_ruang]" id="30_masuk_ruang" onchange="fillthis('30_masuk_ruang')"> 
    Kelas : <input class="input_type" type="text" style="width: 50px" name="form_30[3o_kelas_rawat]" id="3o_kelas_rawat" onchange="fillthis('3o_kelas_rawat')"> 
    Tanggal : <input class="input_type" type="text" style="width: 70px" name="form_30[30_tanggal]" id="30_tanggal" onchange="fillthis('30_tanggal')"> 
    Jam : <input class="input_type" type="text" style="width: 50px" name="form_30[30_jam]" id="30_jam" onchange="fillthis('30_jam')"></td>
  </tr>
  <tr>
    <td valign="center">Cara Masuk</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_irj]" id="30_irj"  onclick="checkthis('30_irj')">
          <span class="lbl" > &nbsp; IRJ</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_igd]" id="30_igd"  onclick="checkthis('30_igd')">
          <span class="lbl" > &nbsp; IGD</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_icu]" id="30_icu"  onclick="checkthis('30_icu')">
          <span class="lbl" > &nbsp; ICU</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="middle" >Dokter yang memberi instruksi rawat </td>
    <td width="70%"> <input class="input_type" type="text" style="width: 300px" name="form_30[30_dokter_instruksi_rawat]" id="30_dokter_instruksi_rawat" onchange="fillthis('30_dokter_instruksi_rawat')"></td>
  </tr>
  <tr>
    <td width="30%" valign="middle" >Diagnosis Masuk </td>
    <td width="70%"> <input class="input_type" type="text" style="width: 300px" name="form_30[30_diagnosis_masuk]" id="30_diagnosis_masuk" onchange="fillthis('30_diagnosis_masuk')"></td>
  </tr>
  <tr>
    <td valign="center">Tiba diruang rawat dengan cara</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_jalan]" id="30_jalan"  onclick="checkthis('30_jalan')">
          <span class="lbl" > &nbsp; Jalan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kursi_roda]" id="30_kursi_roda"  onclick="checkthis('30_kursi_roda')">
          <span class="lbl" > &nbsp; Pakai Kursi Roda</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pakai_brankar]" id="30_pakai_brankar"  onclick="checkthis('30_pakai_brankar')">
          <span class="lbl" > &nbsp; Pakai Brankar</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td valign="center">Kasus Trauma (bila ada)</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kecelakaan_lalin]" id="30_kecelakaan_lalin"  onclick="checkthis('30_kecelakaan_lalin')">
          <span class="lbl" > &nbsp; Kecelakan Lalu Lintas</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kdrt]" id="30_kdrt"  onclick="checkthis('30_kdrt')">
          <span class="lbl" > &nbsp; Kekerasan dalam rumah tangga</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kecelakaan_kerja]" id="30_kecelakaan_kerja"  onclick="checkthis('30_kecelakaan_kerja')">
          <span class="lbl" > &nbsp; Kecelakaan Kerja</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kekerasan_anak]" id="30_kekerasan_anak"  onclick="checkthis('30_kekerasan_anak')">
          <span class="lbl" > &nbsp; Kekerasan Anak <i>(Child Abuse)</i></span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kasus_taruma_lainnya]" id="30_kasus_taruma_lainnya"  onclick="checkthis('30_kasus_taruma_lainnya')">
          <span class="lbl" > &nbsp; Lainnya</span>
        </label>
        <input class="input_type" type="text" style="width: 200px" name="form_30[30_txt_kasus_trauma_lainnya]" id="30_txt_kasus_trauma_lainnya" onchange="fillthis('30_txt_kasus_trauma_lainnya')">
      </div>
    </td>
  </tr>
</table>
<hr>
<span style="font-weight: bold; font-size: 14px">DATA KESEHATAN PASIEN</span>
<table class="table">
  <tr>
    <td valign="center" width="30%">Riwayat penyakit dahulu</td>
    <td width="70%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_no_riwayat_penyakit]" id="30_no_riwayat_penyakit"  onclick="checkthis('30_no_riwayat_penyakit')">
          <span class="lbl" > &nbsp; Tidak ada</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_diabetes]" id="30_diabetes"  onclick="checkthis('30_diabetes')">
          <span class="lbl" > &nbsp; Diabetes Melitus</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_hepatitis_stroke]" id="30_hepatitis_stroke"  onclick="checkthis('30_hepatitis_stroke')">
          <span class="lbl" > &nbsp; Hepatitis Stroke</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_hipertensi]" id="30_hipertensi"  onclick="checkthis('30_hipertensi')">
          <span class="lbl" > &nbsp; Hipertensi</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_jantung]" id="30_jantung"  onclick="checkthis('30_jantung')">
          <span class="lbl" > &nbsp; Jantung</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tbc]" id="30_tbc"  onclick="checkthis('30_tbc')">
          <span class="lbl" > &nbsp; TBC</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_riwayat_penyakit_lainnya]" id="30_riwayat_penyakit_lainnya"  onclick="checkthis('30_riwayat_penyakit_lainnya')">
          <span class="lbl" > &nbsp; Lainnya</span>
        </label>
        <input class="input_type" type="text" style="width: 200px" name="form_30[30_txt_riwayat_penyakit_lainnya]" id="30_txt_riwayat_penyakit_lainnya" onchange="fillthis('30_txt_riwayat_penyakit_lainnya')">
      </div>
    </td>
  </tr>

  <tr>
    <td valign="center">Pernah dirawat</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_no_pernah_dirawat]" id="30_no_pernah_dirawat"  onclick="checkthis('30_no_pernah_dirawat')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_pernah_dirawat]" id="30_ya_pernah_dirawat"  onclick="checkthis('30_ya_pernah_dirawat')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
        Kapan, <input class="input_type" type="text" style="width: 100px" name="form_30[30_kpn_dirawat]" id="30_kpn_dirawat" onchange="fillthis('30_kpn_dirawat')">
        Dimana, <input class="input_type" type="text" style="width: 100px" name="form_30[30_tempat_dirawat]" id="30_tempat_dirawat" onchange="fillthis('30_tempat_dirawat')">
      </div>
    </td>
  </tr>

  <tr>
    <td valign="center">Riwayat Operasi</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_no_riwayat_operasi]" id="30_no_riwayat_operasi"  onclick="checkthis('30_no_riwayat_operasi')">
          <span class="lbl" > &nbsp; Tidak ada</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_pernah_dioperasi]" id="30_ya_pernah_dioperasi"  onclick="checkthis('30_ya_pernah_dioperasi')">
          <span class="lbl" > &nbsp; Ada</span>
        </label>
        Sebutkan, <input class="input_type" type="text" style="width: 100px" name="form_30[30_tindakan_operasi]" id="30_tindakan_operasi" onchange="fillthis('30_tindakan_operasi')">
      </div>
    </td>
  </tr>

  <tr>
    <td valign="center">Obat yang saat ini digunakan</td>
    <td>
      <input class="input_type" type="text" style="width: 300px" name="form_30[30_obat_yang_digunakan_skrg]" id="30_obat_yang_digunakan_skrg" onchange="fillthis('30_obat_yang_digunakan_skrg')">
    </td>
  </tr>

  <tr>
    <td valign="center">Apakah ada terapi komplimentari</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_akupuntur]" id="30_akupuntur"  onclick="checkthis('30_akupuntur')">
          <span class="lbl" > &nbsp; Akupuntur</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pijat]" id="30_pijat"  onclick="checkthis('30_pijat')">
          <span class="lbl" > &nbsp; Pijat</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_herbal]" id="30_herbal"  onclick="checkthis('30_herbal')">
          <span class="lbl" > &nbsp; Herbal</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_komplimentari_lainnya]" id="30_komplimentari_lainnya"  onclick="checkthis('30_komplimentari_lainnya')">
          <span class="lbl" > &nbsp; Lain-lain</span>
        </label>
        ,sebutkan <input class="input_type" type="text" style="width: 100px" name="form_30[30_jenis_komplimentari_lainnya]" id="30_jenis_komplimentari_lainnya" onchange="fillthis('30_jenis_komplimentari_lainnya')">
      </div>
    </td>
  </tr>

  <tr>
    <td valign="center">Riwayat Alergi</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_no_riwayat_alergi]" id="30_no_riwayat_alergi"  onclick="checkthis('30_no_riwayat_alergi')">
          <span class="lbl" > &nbsp; Tidak Ada</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_riwayat_alergi]" id="30_ya_riwayat_alergi"  onclick="checkthis('30_ya_riwayat_alergi')">
          <span class="lbl" > &nbsp; Ada</span>
        </label>
        ,sebutkan <input class="input_type" type="text" style="width: 100px" name="form_30[30_txt_ya_riwayat_alergi]" id="30_txt_ya_riwayat_alergi" onchange="fillthis('30_txt_ya_riwayat_alergi')">
      </div>
    </td>
  </tr>

  <tr>
    <td valign="center">Apakah ada kebiasan</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_merokok]" id="30_merokok"  onclick="checkthis('30_merokok')">
          <span class="lbl" > &nbsp; Merokok</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_no_merokok]" id="30_no_merokok"  onclick="checkthis('30_no_merokok')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_merokok]" id="30_ya_merokok"  onclick="checkthis('30_ya_merokok')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
        ,<input class="input_type" type="text" style="width: 100px" name="form_30[30_btg_perhari]" id="30_btg_perhari" onchange="fillthis('30_btg_perhari')"> batang/hari
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_alkohol]" id="30_alkohol"  onclick="checkthis('30_alkohol')">
          <span class="lbl" > &nbsp; Alkohol</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_no_alkohol]" id="30_no_alkohol"  onclick="checkthis('30_no_alkohol')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_alkohol]" id="30_ya_alkohol"  onclick="checkthis('30_ya_alkohol')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
        ,<input class="input_type" type="text" style="width: 100px" name="form_30[30_gelas_perhari]" id="30_gelas_perhari" onchange="fillthis('30_gelas_perhari')"> gelas/hari
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_narkoba]" id="30_narkoba"  onclick="checkthis('30_narkoba')">
          <span class="lbl" > &nbsp; Obat tidur/ Narkoba</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_no_narkoba]" id="30_no_narkoba"  onclick="checkthis('30_no_narkoba')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_narkoba]" id="30_ya_narkoba"  onclick="checkthis('30_ya_narkoba')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_olahraga]" id="30_olahraga"  onclick="checkthis('30_olahraga')">
          <span class="lbl" > &nbsp; Olahraga</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_no_olahraga]" id="30_no_olahraga"  onclick="checkthis('30_no_olahraga')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_olahraga]" id="30_ya_olahraga"  onclick="checkthis('30_ya_olahraga')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
      </div>
    </td>
  </tr>

  <tr>
    <td valign="center">Riwayat Imunisasi</td>
    <td>
      <div class="col-md-4">
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_no_imunisasi]" id="30_no_imunisasi"  onclick="checkthis('30_no_imunisasi')">
            <span class="lbl" > &nbsp; Tidak pernah</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_tidak_tahu_imunisasi]" id="30_tidak_tahu_imunisasi"  onclick="checkthis('30_tidak_tahu_imunisasi')">
            <span class="lbl" > &nbsp; Tidak tahu</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_bcg]" id="30_imun_bcg"  onclick="checkthis('30_imun_bcg')">
            <span class="lbl" > &nbsp; BCG</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_dpt]" id="30_imun_dpt"  onclick="checkthis('30_imun_dpt')">
            <span class="lbl" > &nbsp; DPT</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_polio]" id="30_imun_polio"  onclick="checkthis('30_imun_polio')">
            <span class="lbl" > &nbsp; Polio</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_campak]" id="30_imun_campak"  onclick="checkthis('30_imun_campak')">
            <span class="lbl" > &nbsp; Campak</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_hep_b]" id="30_imun_hep_b"  onclick="checkthis('30_imun_hep_b')">
            <span class="lbl" > &nbsp; Hepatitis B</span>
          </label>
        </div>
      </div>
      <div class="col-md-4">
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_varicela]" id="30_imun_varicela"  onclick="checkthis('30_imun_varicela')">
            <span class="lbl" > &nbsp; Varicela</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_typoid]" id="30_imun_typoid"  onclick="checkthis('30_imun_typoid')">
            <span class="lbl" > &nbsp; Typoid</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_pneumokokus]" id="30_imun_pneumokokus"  onclick="checkthis('30_imun_pneumokokus')">
            <span class="lbl" > &nbsp; Pneumokokus</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_mmr]" id="30_imun_mmr"  onclick="checkthis('30_imun_mmr')">
            <span class="lbl" > &nbsp; MMR</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_hpv]" id="30_imun_hpv"  onclick="checkthis('30_imun_hpv')">
            <span class="lbl" > &nbsp; HPV</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_influenza]" id="30_imun_influenza"  onclick="checkthis('30_imun_influenza')">
            <span class="lbl" > &nbsp; Influenza</span>
          </label>
        </div>
      </div>
      <div class="col-md-4">
        
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_tetanus]" id="30_imun_tetanus"  onclick="checkthis('30_imun_tetanus')">
            <span class="lbl" > &nbsp; Tetanus</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_zooster]" id="30_imun_zooster"  onclick="checkthis('30_imun_zooster')">
            <span class="lbl" > &nbsp; Zooster</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_hepatitis]" id="30_imun_hepatitis"  onclick="checkthis('30_imun_hepatitis')">
            <span class="lbl" > &nbsp; Hepatitis</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_rotavius]" id="30_imun_rotavius"  onclick="checkthis('30_imun_rotavius')">
            <span class="lbl" > &nbsp; Rotavius</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_hib]" id="30_imun_hib"  onclick="checkthis('30_imun_hib')">
            <span class="lbl" > &nbsp; HIB</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_30[30_imun_yellow_fever]" id="30_imun_yellow_fever"  onclick="checkthis('30_imun_yellow_fever')">
            <span class="lbl" > &nbsp; Yellow Fever</span>
          </label>
        </div>
      </div>
    </td>
  </tr>

  <tr>
    <td valign="center">Riwayat penyakit keluarga</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_no_peyakit_keluarga]" id="30_no_peyakit_keluarga"  onclick="checkthis('30_no_peyakit_keluarga')">
          <span class="lbl" > &nbsp; Tidak ada</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tidak_tahu_penyakit_keluarga]" id="30_tidak_tahu_penyakit_keluarga"  onclick="checkthis('30_tidak_tahu_penyakit_keluarga')">
          <span class="lbl" > &nbsp; Tidak tahu</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_asma]" id="30_asma"  onclick="checkthis('30_asma')">
          <span class="lbl" > &nbsp; Asma</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_riwayat_penyakit_dm]" id="30_riwayat_penyakit_dm"  onclick="checkthis('30_riwayat_penyakit_dm')">
          <span class="lbl" > &nbsp; Diabetes Melitus</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_riwayat_penyakit_keluarga_hipertensi]" id="30_riwayat_penyakit_keluarga_hipertensi"  onclick="checkthis('30_riwayat_penyakit_keluarga_hipertensi')">
          <span class="lbl" > &nbsp; Hipertensi</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_riwayat_penyakit_keluarga_jantung]" id="30_riwayat_penyakit_keluarga_jantung"  onclick="checkthis('30_riwayat_penyakit_keluarga_jantung')">
          <span class="lbl" > &nbsp; Jantung</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_riwayat_penyakit_keluarga_lainnya]" id="30_riwayat_penyakit_keluarga_lainnya"  onclick="checkthis('30_riwayat_penyakit_keluarga_lainnya')">
          <span class="lbl" > &nbsp; Lain-lain</span>
        </label>
        ,sebutkan <input class="input_type" type="text" style="width: 100px" name="form_30[30_txt_riwayat_penyakit_keluarga_lainnya]" id="30_txt_riwayat_penyakit_keluarga_lainnya" onchange="fillthis('30_txt_riwayat_penyakit_keluarga_lainnya')">
      </div>
    </td>
  </tr>

  <tr>
    <td colspan="2"><i><br>Riwayat kehamilan (diisi hanya untuk perempuan)</i></td>
  </tr>
  <tr>
    <td valign="center">Apakah dalam keadaan hamil</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_no_hamil]" id="30_no_hamil"  onclick="checkthis('30_no_hamil')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_hamil]" id="30_ya_hamil"  onclick="checkthis('30_ya_hamil')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
        , perkriaan kelahiran <input class="input_type" type="text" style="width: 100px" name="form_30[30_perkiraan_lahir]" id="30_perkiraan_lahir" onchange="fillthis('30_perkiraan_lahir')">
      </div>
    </td>
  </tr>
  <tr>
    <td valign="center">Apakah sedang menyusul</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_no_hamil]" id="30_no_hamil"  onclick="checkthis('30_no_hamil')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_hamil]" id="30_ya_hamil"  onclick="checkthis('30_ya_hamil')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
      </div>
    </td>
  </tr>

</table>
<hr>
<span style="font-weight: bold; font-size: 14px">RIWAYAT PENYAKIT SEKARANG</span>
<table class="table">
  <tr>
    <td valign="center" width="30%">Keluhan Utama</td>
    <td width="70%">
      <input class="input_type" type="text" style="width: 200px" name="form_30[30_keluhan_utama]" id="30_keluhan_utama" onchange="fillthis('30_keluhan_utama')">
      Lama keluhan, <input class="input_type" type="text" style="width: 100px" name="form_30[30_lama_keluhan]" id="30_lama_keluhan" onchange="fillthis('30_lama_keluhan')">
    </td>
  </tr>
  <tr>
    <td valign="center" width="30%">Mulai timbul keluhan</td>
    <td width="70%">
      <input class="input_type" type="text" style="width: 200px" name="form_30[30_mulai_timbul_keluhan]" id="30_mulai_timbul_keluhan" onchange="fillthis('30_mulai_timbul_keluhan')">
      Sifat keluhan, <input class="input_type" type="text" style="width: 100px" name="form_30[30_sifat_keluhan]" id="30_sifat_keluhan" onchange="fillthis('30_sifat_keluhan')">
      <br>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_hilang_fungsi]" id="30_hilang_fungsi"  onclick="checkthis('30_hilang_fungsi')">
          <span class="lbl" > &nbsp; Hilang fungsi</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kekakuan_sendi]" id="30_kekakuan_sendi"  onclick="checkthis('30_kekakuan_sendi')">
          <span class="lbl" > &nbsp; Kekakuan sendi</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_keterbatasan_gerak]" id="30_keterbatasan_gerak"  onclick="checkthis('30_keterbatasan_gerak')">
          <span class="lbl" > &nbsp; Keterbatasan Gerak</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_nyeri]" id="30_nyeri"  onclick="checkthis('30_nyeri')">
          <span class="lbl" > &nbsp; Nyeri</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_deformitas]" id="30_deformitas"  onclick="checkthis('30_deformitas')">
          <span class="lbl" > &nbsp; Deformitas</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_hematoma]" id="30_hematoma"  onclick="checkthis('30_hematoma')">
          <span class="lbl" > &nbsp; Bengkak/ Hematoma</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_bengkak_tumor]" id="30_bengkak_tumor"  onclick="checkthis('30_bengkak_tumor')">
          <span class="lbl" > &nbsp; Bengkak tumor</span>
        </label>
      </div>
    </td>
  </tr>

  <tr>
    <td valign="center" width="30%">Faktor Pencetus</td>
    <td width="70%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_trauma]" id="30_trauma"  onclick="checkthis('30_trauma')">
          <span class="lbl" > &nbsp; Trauma</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_infeksi]" id="30_infeksi"  onclick="checkthis('30_infeksi')">
          <span class="lbl" > &nbsp; Infeksi</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_faktor_pencetus_lainnya]" id="30_faktor_pencetus_lainnya"  onclick="checkthis('30_faktor_pencetus_lainnya')">
          <span class="lbl" > &nbsp; Lain-lain</span>
        </label>
        sebutkan, <input class="input_type" type="text" style="width: 100px" name="form_30[30_txt_faktor_pencetus_lainnya]" id="30_txt_faktor_pencetus_lainnya" onchange="fillthis('30_txt_faktor_pencetus_lainnya')">
      </div>
    </td>
  </tr>

  <tr>
    <td valign="center" width="30%">Perjalanan penyakit</td>
    <td width="70%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_akut]" id="30_akut"  onclick="checkthis('30_akut')">
          <span class="lbl" > &nbsp; Akut</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kronis]" id="30_kronis"  onclick="checkthis('30_kronis')">
          <span class="lbl" > &nbsp; Kronis</span>
        </label>
      </div>
    </td>
  </tr>

</table>
<hr>
<span style="font-weight: bold; font-size: 14px">KEADAAN UMUM</span>
<br>
<table class="table">
  <tr>
    <td valign="center" width="30%">Kesadaran</td>
    <td width="70%">
      Eye, <input class="input_type" type="text" style="width: 50px" name="form_30[30_kesadaran_eye]" id="30_kesadaran_eye" onchange="fillthis('30_kesadaran_eye')">
      Tekan darah : <input class="input_type" type="text" style="width: 50px" name="form_30[30_tekan_darah]" id="30_tekan_darah" onchange="fillthis('30_tekan_darah')"> mmHg, &nbsp;
      Suhu : <input class="input_type" type="text" style="width: 50px" name="form_30[30_suhu]" id="30_suhu" onchange="fillthis('30_suhu')"> &deg;C
    </td>
  </tr>

  <tr>
    <td valign="center" width="30%">GCS</td>
    <td width="70%">
      Verbal, <input class="input_type" type="text" style="width: 50px" name="form_30[30_gcs_verbal]" id="30_gcs_verbal" onchange="fillthis('30_gcs_verbal')">
      Frekuensi nadi : <input class="input_type" type="text" style="width: 50px" name="form_30[30_frekuensi_nadi]" id="30_frekuensi_nadi" onchange="fillthis('30_frekuensi_nadi')"> x/menit,  &nbsp;
      Berat Badan : <input class="input_type" type="text" style="width: 50px" name="form_30[30_gcs_bb]" id="30_gcs_bb" onchange="fillthis('30_gcs_bb')"> kg
    </td>
  </tr>

  <tr>
    <td valign="center" width="30%">&nbsp;</td>
    <td width="70%">
      Motor, <input class="input_type" type="text" style="width: 50px" name="form_30[30_gcs_motor]" id="30_gcs_motor" onchange="fillthis('30_gcs_motor')">
      Frekuensi nafas : <input class="input_type" type="text" style="width: 50px" name="form_30[30_frekuensi_nafas]" id="30_frekuensi_nafas" onchange="fillthis('30_frekuensi_nafas')"> x/menit,  &nbsp;
      Tinggi Badan : <input class="input_type" type="text" style="width: 50px" name="form_30[30_gcs_tb]" id="30_gcs_tb" onchange="fillthis('30_gcs_tb')"> kg
    </td>
  </tr>
</table>
<br>

<span style="font-weight: bold; font-size: 14px">PENILAIAN FISIK</span>
<br>
<table class="table">
  <tr>
    <td align="center" style="font-weight: bold" width="20%">YANG DINILAI</td>
    <td align="center" style="font-weight: bold" width="80%" colspan="3">HASIL PENILAIAN</td>
  </tr>
  <tr>
    <td>Pernafasan</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_vesikuler]" id="30_vesikuler"  onclick="checkthis('30_vesikuler')">
          <span class="lbl" > &nbsp; Vesikuler</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_cuping_hidung]" id="30_cuping_hidung"  onclick="checkthis('30_cuping_hidung')">
          <span class="lbl" > &nbsp; Cuping Hidung</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_dispnea]" id="30_dispnea"  onclick="checkthis('30_dispnea')">
          <span class="lbl" > &nbsp; Dispnea</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_krepitasi]" id="30_krepitasi"  onclick="checkthis('30_krepitasi')">
          <span class="lbl" > &nbsp; Krepitasi</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_wheezing]" id="30_wheezing"  onclick="checkthis('30_wheezing')">
          <span class="lbl" > &nbsp; Wheezing</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_batuk]" id="30_batuk"  onclick="checkthis('30_batuk')">
          <span class="lbl" > &nbsp; Batuk</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_stridor]" id="30_stridor"  onclick="checkthis('30_stridor')">
          <span class="lbl" > &nbsp; Stridor</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_sianosis]" id="30_sianosis"  onclick="checkthis('30_sianosis')">
          <span class="lbl" > &nbsp; Sianosis</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ronki]" id="30_ronki"  onclick="checkthis('30_ronki')">
          <span class="lbl" > &nbsp; Ronki</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_sekret]" id="30_sekret"  onclick="checkthis('30_sekret')">
          <span class="lbl" > &nbsp; Sekret</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_obat_bantu_nafas]" id="30_obat_bantu_nafas"  onclick="checkthis('30_obat_bantu_nafas')">
          <span class="lbl" > &nbsp; Obat bantu nafas</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pernafasan_lainnya]" id="30_pernafasan_lainnya"  onclick="checkthis('30_pernafasan_lainnya')">
          <span class="lbl" > &nbsp; Lainnya</span>
        </label>
        <input class="input_type" type="text" style="width: 100px" name="form_30[30_txt_pernafasan_lainnya]" id="30_txt_pernafasan_lainnya" onchange="fillthis('30_txt_pernafasan_lainnya')">
      </div>
    </td>
  </tr>
  <tr>
    <td>Sirkulasi / Cairan</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_Diaforesis]" id="30_Diaforesis"  onclick="checkthis('30_Diaforesis')">
          <span class="lbl" > &nbsp; Diaforesis</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_epistaksis]" id="30_epistaksis"  onclick="checkthis('30_epistaksis')">
          <span class="lbl" > &nbsp; Epistaksis</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_hematemesis]" id="30_hematemesis"  onclick="checkthis('30_hematemesis')">
          <span class="lbl" > &nbsp; Hematemesis</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_distrimia]" id="30_distrimia"  onclick="checkthis('30_distrimia')">
          <span class="lbl" > &nbsp; Distrimea</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_Melena]" id="30_Melena"  onclick="checkthis('30_Melena')">
          <span class="lbl" > &nbsp; Melena</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_akral_dingin]" id="30_akral_dingin"  onclick="checkthis('30_akral_dingin')">
          <span class="lbl" > &nbsp; Akral Dingin</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pucat]" id="30_pucat"  onclick="checkthis('30_pucat')">
          <span class="lbl" > &nbsp; Pucat</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mukosa]" id="30_mukosa"  onclick="checkthis('30_mukosa')">
          <span class="lbl" > &nbsp; Mukosa Mulut Kering</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ascities]" id="30_ascities"  onclick="checkthis('30_ascities')">
          <span class="lbl" > &nbsp; Ascities</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_sirkulasi_lainnya]" id="30_sirkulasi_lainnya"  onclick="checkthis('30_sirkulasi_lainnya')">
          <span class="lbl" > &nbsp; Lainnya</span>
        </label>
        <input class="input_type" type="text" style="width: 100px" name="form_30[30_txt_sirkulasi_lainnya]" id="30_txt_sirkulasi_lainnya" onchange="fillthis('30_txt_sirkulasi_lainnya')">
      </div>
    </td>
  </tr>
  <tr>
    <td>Penglihatan</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_Penglihatan_baik]" id="30_Penglihatan_baik"  onclick="checkthis('30_Penglihatan_baik')">
          <span class="lbl" > &nbsp; Penglihatan Baik</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_sklera]" id="30_sklera"  onclick="checkthis('30_sklera')">
          <span class="lbl" > &nbsp; Sklera Pupil</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_buram]" id="30_buram"  onclick="checkthis('30_buram')">
          <span class="lbl" > &nbsp; Buram</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_isokor]" id="30_isokor"  onclick="checkthis('30_isokor')">
          <span class="lbl" > &nbsp; Isokor</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_Melena]" id="30_Melena"  onclick="checkthis('30_Melena')">
          <span class="lbl" > &nbsp; Melena</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_akral_dingin]" id="30_akral_dingin"  onclick="checkthis('30_akral_dingin')">
          <span class="lbl" > &nbsp; Akral Dingin</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pucat]" id="30_pucat"  onclick="checkthis('30_pucat')">
          <span class="lbl" > &nbsp; Pucat</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mukosa]" id="30_mukosa"  onclick="checkthis('30_mukosa')">
          <span class="lbl" > &nbsp; Mukosa Mulut Kering</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ascities]" id="30_ascities"  onclick="checkthis('30_ascities')">
          <span class="lbl" > &nbsp; Ascities</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_sirkulasi_lainnya]" id="30_sirkulasi_lainnya"  onclick="checkthis('30_sirkulasi_lainnya')">
          <span class="lbl" > &nbsp; Lainnya</span>
        </label>
        <input class="input_type" type="text" style="width: 100px" name="form_30[30_txt_sirkulasi_lainnya]" id="30_txt_sirkulasi_lainnya" onchange="fillthis('30_txt_sirkulasi_lainnya')">
      </div>
    </td>
  </tr>
</table>
<hr>
<?php echo $footer; ?>

