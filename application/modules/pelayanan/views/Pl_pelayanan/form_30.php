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
    <td width="25%">Pernafasan</td>
    <td width="25%">
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
    <td width="25%">
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
    <td width="25%">
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
          <input type="checkbox" class="ace" name="form_30[30_Diplopia]" id="30_Diplopia"  onclick="checkthis('30_Diplopia')">
          <span class="lbl" > &nbsp; Diplopia</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_Ikterik]" id="30_Ikterik"  onclick="checkthis('30_Ikterik')">
          <span class="lbl" > &nbsp; Ikterik</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_An_Isokor]" id="30_An_Isokor"  onclick="checkthis('30_An_Isokor')">
          <span class="lbl" > &nbsp; An isokor</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_alat_bantu_lihat]" id="30_alat_bantu_lihat"  onclick="checkthis('30_alat_bantu_lihat')">
          <span class="lbl" > &nbsp; Alat Bantu</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_konjungtivitis]" id="30_konjungtivitis"  onclick="checkthis('30_konjungtivitis')">
          <span class="lbl" > &nbsp; Konjungtivitis</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_miosis]" id="30_miosis"  onclick="checkthis('30_miosis')">
          <span class="lbl" > &nbsp; Miosis</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_midriasis]" id="30_midriasis"  onclick="checkthis('30_midriasis')">
          <span class="lbl" > &nbsp; Midriasis</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tdk_melihat]" id="30_tdk_melihat"  onclick="checkthis('30_tdk_melihat')">
          <span class="lbl" > &nbsp; Tidak dapat melihat</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_reflek_cahaya]" id="30_reflek_cahaya"  onclick="checkthis('30_reflek_cahaya')">
          <span class="lbl" > &nbsp; Reflek Cahaya</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td>Pendengaran</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_Pendengaran_baik]" id="30_Pendengaran_baik"  onclick="checkthis('30_Pendengaran_baik')">
          <span class="lbl" > &nbsp; Pendengaran Baik</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_berdengung]" id="30_berdengung"  onclick="checkthis('30_berdengung')">
          <span class="lbl" > &nbsp; Berdengung</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_dengar_kurang_jelas]" id="30_dengar_kurang_jelas"  onclick="checkthis('30_dengar_kurang_jelas')">
          <span class="lbl" > &nbsp; Kurang Jelas</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_berair]" id="30_berair"  onclick="checkthis('30_berair')">
          <span class="lbl" > &nbsp; Berair</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_Serumen]" id="30_Serumen"  onclick="checkthis('30_Serumen')">
          <span class="lbl" > &nbsp; Serumen</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tdk_bisa_dgr]" id="30_tdk_bisa_dgr"  onclick="checkthis('30_tdk_bisa_dgr')">
          <span class="lbl" > &nbsp; Tidak bisa dengar</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_alat_bantu_dengar]" id="30_alat_bantu_dengar"  onclick="checkthis('30_alat_bantu_dengar')">
          <span class="lbl" > &nbsp; Alat Bantu</span>
        </label>
      </div>
    </td>
    <td>
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
    <td>Pengecapan</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_Pengecapan_baik]" id="30_Pengecapan_baik"  onclick="checkthis('30_Pengecapan_baik')">
          <span class="lbl" > &nbsp; Baik</span>
        </label>
      </div>
    </td>
    <td colspan="2">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pengecapan_gangguan]" id="30_pengecapan_gangguan"  onclick="checkthis('30_pengecapan_gangguan')">
          <span class="lbl" > &nbsp; Ada Gangguan</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td>Penciuman</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_Penciuman_baik]" id="30_Penciuman_baik"  onclick="checkthis('30_Penciuman_baik')">
          <span class="lbl" > &nbsp; Baik</span>
        </label>
      </div>
    </td>
    <td colspan="2">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_Penciuman_gangguan]" id="30_Penciuman_gangguan"  onclick="checkthis('30_Penciuman_gangguan')">
          <span class="lbl" > &nbsp; Ada Gangguan</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td>Bicara</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_Bicara_baik]" id="30_Bicara_baik"  onclick="checkthis('30_Bicara_baik')">
          <span class="lbl" > &nbsp; Normal</span>
        </label>
      </div>
    </td>
    <td colspan="2">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_Bicara_gangguan]" id="30_Bicara_gangguan"  onclick="checkthis('30_Bicara_gangguan')">
          <span class="lbl" > &nbsp; Ada Gangguan</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td>Peningkatan tekanan intra kranial</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tik_tdk_ada]" id="30_tik_tdk_ada"  onclick="checkthis('30_tik_tdk_ada')">
          <span class="lbl" > &nbsp; Tidak ada</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tik_muntah]" id="30_tik_muntah"  onclick="checkthis('30_tik_muntah')">
          <span class="lbl" > &nbsp; Muntah</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tik_sakit_kepala]" id="30_tik_sakit_kepala"  onclick="checkthis('30_tik_sakit_kepala')">
          <span class="lbl" > &nbsp; Sakit Kepala</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tik_pusing]" id="30_tik_pusing"  onclick="checkthis('30_tik_pusing')">
          <span class="lbl" > &nbsp; Pusing</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td>Persarafan</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_saraf_tremor]" id="30_saraf_tremor"  onclick="checkthis('30_saraf_tremor')">
          <span class="lbl" > &nbsp; Tremor</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_saraf_kejang]" id="30_saraf_kejang"  onclick="checkthis('30_saraf_kejang')">
          <span class="lbl" > &nbsp; Kejang</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_reflek_fisiologis]" id="30_reflek_fisiologis"  onclick="checkthis('30_reflek_fisiologis')">
          <span class="lbl" > &nbsp; Reflek Fisiologis</span>
        </label>
      </div>
      
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_saraf_paralisis]" id="30_saraf_paralisis"  onclick="checkthis('30_saraf_paralisis')">
          <span class="lbl" > &nbsp; Paralisis</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_parese]" id="30_parese"  onclick="checkthis('30_parese')">
          <span class="lbl" > &nbsp; Parese</span>
        </label>
      </div>
    </td>
    <td>
      
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kekuatan_otot]" id="30_kekuatan_otot"  onclick="checkthis('30_kekuatan_otot')">
          <span class="lbl" > &nbsp; Kekuatan Otot</span>
        </label>
        <input class="input_type" type="text" style="width: 100px" name="form_30[30_txt_kekuatan_otot]" id="30_txt_kekuatan_otot" onchange="fillthis('30_txt_kekuatan_otot')">
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_persarafan_lainnya]" id="30_persarafan_lainnya"  onclick="checkthis('30_persarafan_lainnya')">
          <span class="lbl" > &nbsp; Lainnya</span>
        </label>
        <input class="input_type" type="text" style="width: 100px" name="form_30[30_txt_persarafan_lainnya]" id="30_txt_persarafan_lainnya" onchange="fillthis('30_txt_persarafan_lainnya')">
      </div>
    </td>
  </tr>
  <tr>
    <td>Integritasi</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kondisi_bersih]" id="30_kondisi_bersih"  onclick="checkthis('30_kondisi_bersih')">
          <span class="lbl" > &nbsp; Kondisi Bersih</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_bulae]" id="30_bulae"  onclick="checkthis('30_bulae')">
          <span class="lbl" > &nbsp; Bulae</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kondisi_kotor]" id="30_kondisi_kotor"  onclick="checkthis('30_kondisi_kotor')">
          <span class="lbl" > &nbsp; Kondisi Kotor</span>
        </label>
      </div>
    </td>

    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kemerahan]" id="30_kemerahan"  onclick="checkthis('30_kemerahan')">
          <span class="lbl" > &nbsp; Kemerahan</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_laserasi]" id="30_laserasi"  onclick="checkthis('30_laserasi')">
          <span class="lbl" > &nbsp; Laserasi</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ulcerasi]" id="30_ulcerasi"  onclick="checkthis('30_ulcerasi')">
          <span class="lbl" > &nbsp; Ulcerasi</span>
        </label>
      </div>
      
    </td>

    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kekuatan_otot]" id="30_kekuatan_otot"  onclick="checkthis('30_kekuatan_otot')">
          <span class="lbl" > &nbsp; Jaringan Perut</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_memar]" id="30_memar"  onclick="checkthis('30_memar')">
          <span class="lbl" > &nbsp; Memar</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_luka_area]" id="30_luka_area"  onclick="checkthis('30_luka_area')">
          <span class="lbl" > &nbsp; Luka di Area</span>
        </label>
        <input class="input_type" type="text" style="width: 100px" name="form_30[30_txt_luka_area]" id="30_txt_luka_area" onchange="fillthis('30_txt_luka_area')">
      </div>
    </td>

  </tr>
  <tr>
    <td>Pakai alat bantu</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tdk_pakai_alat_bantu]" id="30_tdk_pakai_alat_bantu"  onclick="checkthis('30_tdk_pakai_alat_bantu')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tongkat]" id="30_tongkat"  onclick="checkthis('30_tongkat')">
          <span class="lbl" > &nbsp; Tongkat</span>
        </label>
      </div>
      
    </td>
    
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_walker]" id="30_walker"  onclick="checkthis('30_walker')">
          <span class="lbl" > &nbsp; Walker</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kursi_roda]" id="30_kursi_roda"  onclick="checkthis('30_kursi_roda')">
          <span class="lbl" > &nbsp; Kursi Roda</span>
        </label>
      </div>
    </td>

    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_alat_bantu_lainnya]" id="30_alat_bantu_lainnya"  onclick="checkthis('30_alat_bantu_lainnya')">
          <span class="lbl" > &nbsp; Lainnya</span>
        </label>
        <input class="input_type" type="text" style="width: 100px" name="form_30[30_txt_alat_bantu_lainnya]" id="30_txt_alat_bantu_lainnya" onchange="fillthis('30_txt_alat_bantu_lainnya')">
      </div>
    </td>

  </tr>
  <tr>
    <td>Terapsang retraksi</td>
    <td>
      Beban : <input class="input_type" type="text" style="width: 100px" name="form_30[30_beban_retraksi]" id="30_beban_retraksi" onchange="fillthis('30_beban_retraksi')"> kg
    </td>
    
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_terpasang_gips]" id="30_terpasang_gips"  onclick="checkthis('30_terpasang_gips')">
          <span class="lbl" > &nbsp; Terpasang Gips</span>
        </label>
      </div>
    </td>

    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_terpasang_internal]" id="30_terpasang_internal"  onclick="checkthis('30_terpasang_internal')">
          <span class="lbl" > &nbsp; Terpasang internal / eksternal fikasi</span>
        </label>
      </div>
    </td>

  </tr>
  <tr>
    <td>Nafsu Makan</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pencernaan_baik]" id="30_pencernaan_baik"  onclick="checkthis('30_pencernaan_baik')">
          <span class="lbl" > &nbsp; Baik</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pencernaan_kurang]" id="30_pencernaan_kurang"  onclick="checkthis('30_pencernaan_kurang')">
          <span class="lbl" > &nbsp; Kurang</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pencernaan_tdk_ada]" id="30_pencernaan_tdk_ada"  onclick="checkthis('30_pencernaan_tdk_ada')">
          <span class="lbl" > &nbsp; Tidak Ada</span>
        </label>
      </div>
    </td>

  </tr>
  <tr>
    <td>Keluhan</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pencernaan_mual]" id="30_pencernaan_mual"  onclick="checkthis('30_pencernaan_mual')">
          <span class="lbl" > &nbsp; Mual</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pencernaan_muntah]" id="30_pencernaan_muntah"  onclick="checkthis('30_pencernaan_muntah')">
          <span class="lbl" > &nbsp; Muntah</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pencernaan_sukar_menelan]" id="30_pencernaan_sukar_menelan"  onclick="checkthis('30_pencernaan_sukar_menelan')">
          <span class="lbl" > &nbsp; Sukar Menelan</span>
        </label>
      </div>
    </td>

  </tr>
  <tr>
    <td>Pola BAB</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_konstipasi]" id="30_konstipasi"  onclick="checkthis('30_konstipasi')">
          <span class="lbl" > &nbsp; Konstipasi</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_eliminasi_diare]" id="30_eliminasi_diare"  onclick="checkthis('30_eliminasi_diare')">
          <span class="lbl" > &nbsp; Diare</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_eliminasi_melena]" id="30_eliminasi_melena"  onclick="checkthis('30_eliminasi_melena')">
          <span class="lbl" > &nbsp; Melena</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tdk_ada_kelainan]" id="30_tdk_ada_kelainan"  onclick="checkthis('30_tdk_ada_kelainan')">
          <span class="lbl" > &nbsp; Tidak ada kelainan</span>
        </label>
      </div>
    </td>
    <td>
      Konsistensi :  <input class="input_type" type="text" style="width: 30px" name="form_30[30_txt_konsistensi_bab]" id="30_txt_konsistensi_bab" onchange="fillthis('30_txt_konsistensi_bab')"><br>
      Warna : <input class="input_type" type="text" style="width: 100px" name="form_30[30_warna_bab]" id="30_warna_bab" onchange="fillthis('30_warna_bab')"><br>
      Bising usus :  <input class="input_type" type="text" style="width: 100px" name="form_30[30_bisis_usus_bab]" id="30_bisis_usus_bab" onchange="fillthis('30_bisis_usus_bab')">
    </td>

  </tr>
  <tr>
    <td>Pola BAK</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_hematuri]" id="30_hematuri"  onclick="checkthis('30_hematuri')">
          <span class="lbl" > &nbsp; Hematuri</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_inkontinesi]" id="30_inkontinesi"  onclick="checkthis('30_inkontinesi')">
          <span class="lbl" > &nbsp; Inkontinesi</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_oiliguria]" id="30_oiliguria"  onclick="checkthis('30_oiliguria')">
          <span class="lbl" > &nbsp; Oliguria</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_poliuri]" id="30_poliuri"  onclick="checkthis('30_poliuri')">
          <span class="lbl" > &nbsp; Poliuri</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kateter]" id="30_kateter"  onclick="checkthis('30_kateter')">
          <span class="lbl" > &nbsp; Kateter</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_retensi]" id="30_retensi"  onclick="checkthis('30_retensi')">
          <span class="lbl" > &nbsp; Retensi</span>
        </label>
      </div>
    </td>
    <td>
      Frekuensi :  <input class="input_type" type="text" style="width: 30px" name="form_30[30_frekuensi_bak]" id="30_frekuensi_bak" onchange="fillthis('30_frekuensi_bak')"> x/hari<br>
      Warna : <input class="input_type" type="text" style="width: 100px" name="form_30[30_warna_bak]" id="30_warna_bak" onchange="fillthis('30_warna_bak')"><br>
      Genital :  <input class="input_type" type="text" style="width: 100px" name="form_30[30_genital_bak]" id="30_genital_bak" onchange="fillthis('30_genital_bak')">
    </td>

  </tr>
  <tr>
    <td>Higiene</td>
    <td>
      Mulut :  <input class="input_type" type="text" style="width: 100px" name="form_30[30_higiene_mulut]" id="30_higiene_mulut" onchange="fillthis('30_higiene_mulut')"><br>
      Kuku :  <input class="input_type" type="text" style="width: 100px" name="form_30[30_higiene_kuku]" id="30_higiene_kuku" onchange="fillthis('30_higiene_kuku')">
    </td>

    <td>
      Kulit :  <input class="input_type" type="text" style="width: 100px" name="form_30[30_higiene_kulit]" id="30_higiene_kulit" onchange="fillthis('30_higiene_kulit')"><br>
      Telinga :  <input class="input_type" type="text" style="width: 100px" name="form_30[30_higiene_telinga]" id="30_higiene_telinga" onchange="fillthis('30_higiene_telinga')">
    </td>

    <td>
      Rambut kepala :  <input class="input_type" type="text" style="width: 100px" name="form_30[30_higiene_kepala]" id="30_higiene_kepala" onchange="fillthis('30_higiene_kepala')">
    </td>

  </tr>
  <tr>
    <td>Seksualitas</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_impoten]" id="30_impoten"  onclick="checkthis('30_impoten')">
          <span class="lbl" > &nbsp; Impoten</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_frigiditas]" id="30_frigiditas"  onclick="checkthis('30_frigiditas')">
          <span class="lbl" > &nbsp; Frigiditas</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_perubahan_seksualitas]" id="30_perubahan_seksualitas"  onclick="checkthis('30_perubahan_seksualitas')">
          <span class="lbl" > &nbsp; Perubahan Seksualitas</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_seksualitas_lainnya]" id="30_seksualitas_lainnya"  onclick="checkthis('30_seksualitas_lainnya')">
          <span class="lbl" > &nbsp; Lainnya</span>
        </label>
        <input class="input_type" type="text" style="width: 80px" name="form_30[30_txt_seksualitas_lainnya]" id="30_txt_seksualitas_lainnya" onchange="fillthis('30_txt_seksualitas_lainnya')">
      </div>
    </td>

  </tr>
  <tr>
    <td>Aktifitas Istirahat</td>
    <td>
      Lama tidur : <input class="input_type" type="text" style="width: 80px" name="form_30[30_lama_tidur]" id="30_lama_tidur" onchange="fillthis('30_lama_tidur')"> jam<br>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_insomnia]" id="30_insomnia"  onclick="checkthis('30_insomnia')">
          <span class="lbl" > &nbsp; Insomnia</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tonus_otot]" id="30_tonus_otot"  onclick="checkthis('30_tonus_otot')">
          <span class="lbl" > &nbsp; Tonus otot</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tremor]" id="30_tremor"  onclick="checkthis('30_tremor')">
          <span class="lbl" > &nbsp; Tremor</span>
        </label>
      </div>
      
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_aktifitas_dgn_bantuan]" id="30_aktifitas_dgn_bantuan"  onclick="checkthis('30_aktifitas_dgn_bantuan')">
          <span class="lbl" > &nbsp; Aktifitas dengan bantuan</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_malaise_fatigue]" id="30_malaise_fatigue"  onclick="checkthis('30_malaise_fatigue')">
          <span class="lbl" > &nbsp; Malaise I Fatigue</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kontraktur]" id="30_kontraktur"  onclick="checkthis('30_kontraktur')">
          <span class="lbl" > &nbsp; Kontraktur</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mobilitas_dibatasi]" id="30_mobilitas_dibatasi"  onclick="checkthis('30_mobilitas_dibatasi')">
          <span class="lbl" > &nbsp; Mobilitas dibatasi</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_aktifitas_alat_bantu]" id="30_aktifitas_alat_bantu"  onclick="checkthis('30_aktifitas_alat_bantu')">
          <span class="lbl" > &nbsp; Alat Bantu</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_fraktur]" id="30_fraktur"  onclick="checkthis('30_fraktur')">
          <span class="lbl" > &nbsp; Fraktur</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_rom_menurun]" id="30_rom_menurun"  onclick="checkthis('30_rom_menurun')">
          <span class="lbl" > &nbsp; ROM Menurun</span>
        </label>
      </div>
    </td>

  </tr>
  <tr>
    <td>Muskulo Skeletal<br>Bentuk Tubuh</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tubuh_tegap]" id="30_tubuh_tegap"  onclick="checkthis('30_tubuh_tegap')">
          <span class="lbl" > &nbsp; Tegap</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kiposis]" id="30_kiposis"  onclick="checkthis('30_kiposis')">
          <span class="lbl" > &nbsp; Kiposis</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_nyeri_tulang]" id="30_nyeri_tulang"  onclick="checkthis('30_nyeri_tulang')">
          <span class="lbl" > &nbsp; Nyeri Tulang</span>
        </label>
      </div>
      
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tubuh_tdk_tegap]" id="30_tubuh_tdk_tegap"  onclick="checkthis('30_tubuh_tdk_tegap')">
          <span class="lbl" > &nbsp; Tidak tegap</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_lordosis]" id="30_lordosis"  onclick="checkthis('30_lordosis')">
          <span class="lbl" > &nbsp; Lordosis</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tumor_tulang]" id="30_tumor_tulang"  onclick="checkthis('30_tumor_tulang')">
          <span class="lbl" > &nbsp; Tumor Tulang</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_gibus]" id="30_gibus"  onclick="checkthis('30_gibus')">
          <span class="lbl" > &nbsp; Gibus</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_skoliosis]" id="30_skoliosis"  onclick="checkthis('30_skoliosis')">
          <span class="lbl" > &nbsp; Skoliosis</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_amputasi]" id="30_amputasi"  onclick="checkthis('30_amputasi')">
          <span class="lbl" > &nbsp; Amputasi</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td>Muskulo Skeletal<br>Tulang</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_fraktur_terbuka]" id="30_fraktur_terbuka"  onclick="checkthis('30_fraktur_terbuka')">
          <span class="lbl" > &nbsp; Fraktur terbuka</span>
        </label>
      </div>
      Grade : <input class="input_type" type="text" style="width: 80px" name="form_30[30_grade_fraktur]" id="30_grade_fraktur" onchange="fillthis('30_grade_fraktur')">
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_fraktur_patologis]" id="30_fraktur_patologis"  onclick="checkthis('30_fraktur_patologis')">
          <span class="lbl" > &nbsp; Fraktur Patologis</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_burs_fraktur]" id="30_burs_fraktur"  onclick="checkthis('30_burs_fraktur')">
          <span class="lbl" > &nbsp; Burs Fraktur</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_fraktur_tertutup]" id="30_fraktur_tertutup"  onclick="checkthis('30_fraktur_tertutup')">
          <span class="lbl" > &nbsp; Fraktur Tertutup</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_fraktur_kompresi]" id="30_fraktur_kompresi"  onclick="checkthis('30_fraktur_kompresi')">
          <span class="lbl" > &nbsp; Fraktur Kompresi</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td>Muskulo Skeletal<br>Sendi</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_sendi_nyeri]" id="30_sendi_nyeri"  onclick="checkthis('30_sendi_nyeri')">
          <span class="lbl" > &nbsp; Nyeri</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_sendi_infeksi]" id="30_sendi_infeksi"  onclick="checkthis('30_sendi_infeksi')">
          <span class="lbl" > &nbsp; Infeksi</span>
        </label>
      </div>
    </td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_odema]" id="30_odema"  onclick="checkthis('30_odema')">
          <span class="lbl" > &nbsp; Odema</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_sendi_dislokasi]" id="30_sendi_dislokasi"  onclick="checkthis('30_sendi_dislokasi')">
          <span class="lbl" > &nbsp; Dislokasi</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_sendi_rom]" id="30_sendi_rom"  onclick="checkthis('30_sendi_rom')">
          <span class="lbl" > &nbsp; ROM</span>
        </label>
        <input class="input_type" type="text" style="width: 30px" name="form_30[30_rom_size]" id="30_rom_size" onchange="fillthis('30_rom_size')"> derajat
      </div>
    </td>

    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kontraktur_area]" id="30_kontraktur_area"  onclick="checkthis('30_kontraktur_area')">
          <span class="lbl" > &nbsp; Kontraktur area</span>
        </label>
      </div>
      <input class="input_type" type="text" style="width: 80px" name="form_30[30_txt_kontraktur_area]" id="30_txt_kontraktur_area" onchange="fillthis('30_txt_kontraktur_area')">
    </td>

  </tr>

</table>
<br>
<span style="font-weight: bold; font-size: 14px">PSIKOSOSIAL</span>
<br>
<table class="table">
  <tr>
    <td width="25%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_psikososial_denial]" id="30_psikososial_denial"  onclick="checkthis('30_psikososial_denial')">
          <span class="lbl" > &nbsp; Menolak</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_psikososial_menerima]" id="30_psikososial_menerima"  onclick="checkthis('30_psikososial_menerima')">
          <span class="lbl" > &nbsp; Menerima </span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_psikososial_bargaining]" id="30_psikososial_bargaining"  onclick="checkthis('30_psikososial_bargaining')">
          <span class="lbl" > &nbsp; Bargaining  </span>
        </label>
      </div>
    </td>
    <td width="25%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_psikososial_marah]" id="30_psikososial_marah"  onclick="checkthis('30_psikososial_marah')">
          <span class="lbl" > &nbsp; Angry (Marah) </span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tidak_semangat]" id="30_tidak_semangat"  onclick="checkthis('30_tidak_semangat')">
          <span class="lbl" > &nbsp; Tidak semangat</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_sulit_tidur]" id="30_sulit_tidur"  onclick="checkthis('30_sulit_tidur')">
          <span class="lbl" > &nbsp; Sulit tidur</span>
        </label>
        
      </div>
    </td>
    <td width="25%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_rasa_tertekan]" id="30_rasa_tertekan"  onclick="checkthis('30_rasa_tertekan')">
          <span class="lbl" > &nbsp; Rasa tertekan</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_depresi]" id="30_depresi"  onclick="checkthis('30_depresi')">
          <span class="lbl" > &nbsp; Depresi</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_cepat_lelah]" id="30_cepat_lelah"  onclick="checkthis('30_cepat_lelah')">
          <span class="lbl" > &nbsp; Cepat Lelah</span>
        </label>
      </div>
    </td>
    <td width="25%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_sulit_bicara]" id="30_sulit_bicara"  onclick="checkthis('30_sulit_bicara')">
          <span class="lbl" > &nbsp; Sulit berbicara</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_merasa_bersalah]" id="30_merasa_bersalah"  onclick="checkthis('30_merasa_bersalah')">
          <span class="lbl" > &nbsp; Merasa Bersalah</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_sulit_konsentrasi]" id="30_sulit_konsentrasi"  onclick="checkthis('30_sulit_konsentrasi')">
          <span class="lbl" > &nbsp; Sulit Konsentrasi</span>
        </label>
      </div>
    </td>
  </tr>
</table>
<br>
<span style="font-weight: bold; font-size: 14px">KULTURAL</span>
<br>
<table class="table">
  <tr>
    <td width="100%">
      Nilai budaya yang dimiliki terkait dengan "Penyebab Penyakit/ Masalah Kesehatan" sakit adalah :<br>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_hukuman]" id="30_hukuman"  onclick="checkthis('30_hukuman')">
          <span class="lbl" > &nbsp; Hukuman</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ujian]" id="30_ujian"  onclick="checkthis('30_ujian')">
          <span class="lbl" > &nbsp; Ujian </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_kesalahan]" id="30_kesalahan"  onclick="checkthis('30_kesalahan')">
          <span class="lbl" > &nbsp; Kesalahan  </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_takdir]" id="30_takdir"  onclick="checkthis('30_takdir')">
          <span class="lbl" > &nbsp; Takdir  </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_bantuan_org_lain]" id="30_bantuan_org_lain"  onclick="checkthis('30_bantuan_org_lain')">
          <span class="lbl" > &nbsp; Bantuan orang lain  </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_keturunan]" id="30_keturunan"  onclick="checkthis('30_keturunan')">
          <span class="lbl" > &nbsp; Keturunan  </span>
        </label>
      </div>
      <br>
      Kebiasaan pasien saat sakit (pola aktifitas dan istirahat) : 
      <input class="input_type" type="text" style="width: 100px" name="form_30[30_kebiasaan_pasien_saat_sakit]" id="30_kebiasaan_pasien_saat_sakit" onchange="fillthis('30_kebiasaan_pasien_saat_sakit')">
      <br>
      Pola Makan <br>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pola_makan_sehat]" id="30_pola_makan_sehat"  onclick="checkthis('30_pola_makan_sehat')">
          <span class="lbl" > &nbsp; Sehat</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pola_makan_tdk_sehat]" id="30_pola_makan_tdk_sehat"  onclick="checkthis('30_pola_makan_tdk_sehat')">
          <span class="lbl" > &nbsp; Tidak Sehat </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_makanan_pokok]" id="30_makanan_pokok"  onclick="checkthis('30_makanan_pokok')">
          <span class="lbl" > &nbsp; Makanan Pokok  </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_makanan_pokok_nasi]" id="30_makanan_pokok_nasi"  onclick="checkthis('30_makanan_pokok_nasi')">
          <span class="lbl" > &nbsp; Nasi  </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_selain_nasi]" id="30_selain_nasi"  onclick="checkthis('30_selain_nasi')">
          <span class="lbl" > &nbsp; Selain Nasi,   </span>
        </label>
        <input class="input_type" type="text" style="width: 50px" name="form_30[30_txt_selain_nasi]" id="30_txt_selain_nasi" onchange="fillthis('30_txt_selain_nasi')">
      </div>
      <br>
      Pantangan Makan <br>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tdk_pantang_makan]" id="30_tdk_pantang_makan"  onclick="checkthis('30_tdk_pantang_makan')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_pantang_makan]" id="30_ya_pantang_makan"  onclick="checkthis('30_ya_pantang_makan')">
          <span class="lbl" > &nbsp; Ya, sebutkan </span>
        </label>
        <input class="input_type" type="text" style="width: 100px" name="form_30[30_txt_pantang_makan]" id="30_txt_pantang_makan" onchange="fillthis('30_txt_pantang_makan')">
      </div>
      <br>
      Mempunyai pengaruh kepercayaan yang dianut terhadap penyakit : <br>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tdk_kepercayaan]" id="30_tdk_kepercayaan"  onclick="checkthis('30_tdk_kepercayaan')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_kepercayaan]" id="30_ya_kepercayaan"  onclick="checkthis('30_ya_kepercayaan')">
          <span class="lbl" > &nbsp; Ya, sebutkan </span>
        </label>
        <input class="input_type" type="text" style="width: 100px" name="form_30[30_txt_ya_kepercayaan]" id="30_txt_ya_kepercayaan" onchange="fillthis('30_txt_ya_kepercayaan')">
      </div>

    </td>
  </tr>
</table>
<br>
<span style="font-weight: bold; font-size: 14px">EKONOMI</span>
<br>
<table class="table">
  <tr>
    <td width="25%">
      Pendidikan
    </td>
    <td width="75%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pend_sd]" id="30_pend_sd"  onclick="checkthis('30_pend_sd')">
          <span class="lbl" > &nbsp; SD</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pend_smp]" id="30_pend_smp"  onclick="checkthis('30_pend_smp')">
          <span class="lbl" > &nbsp; SMP </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pend_sma]" id="30_pend_sma"  onclick="checkthis('30_pend_sma')">
          <span class="lbl" > &nbsp; SMA  </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pend_akademi]" id="30_pend_akademi"  onclick="checkthis('30_pend_akademi')">
          <span class="lbl" > &nbsp; Akademi  </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pend_sarjana]" id="30_pend_sarjana"  onclick="checkthis('30_pend_sarjana')">
          <span class="lbl" > &nbsp; Sarjana </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_pend_lainnya]" id="30_pend_lainnya"  onclick="checkthis('30_pend_lainnya')">
          <span class="lbl" > &nbsp; Lainnya  </span>
        </label>
        <input class="input_type" type="text" style="width: 50px" name="form_30[30_txt_pend_lainnya]" id="30_txt_pend_lainnya" onchange="fillthis('30_txt_pend_lainnya')">
      </div>
    </td>
  </tr>
  <tr>
    <td width="25%">
      Pekerjaan
    </td>
    <td width="75%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_job_pns]" id="30_job_pns"  onclick="checkthis('30_job_pns')">
          <span class="lbl" > &nbsp; PNS</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_job_swasta]" id="30_job_swasta"  onclick="checkthis('30_job_swasta')">
          <span class="lbl" > &nbsp; Swasta </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_job_wiraswasta]" id="30_job_wiraswasta"  onclick="checkthis('30_job_wiraswasta')">
          <span class="lbl" > &nbsp; Wiraswasta  </span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_job_lainnya]" id="30_job_lainnya"  onclick="checkthis('30_job_lainnya')">
          <span class="lbl" > &nbsp; Lainnya  </span>
        </label>
        <input class="input_type" type="text" style="width: 50px" name="form_30[30_txt_job_lainnya]" id="30_txt_job_lainnya" onchange="fillthis('30_txt_job_lainnya')">
      </div>
    </td>
  </tr>
</table>
<br>
<span style="font-weight: bold; font-size: 14px">SPIRITUAL</span>
<br>
<table class="table">
  <tr>
    <td width="25%">
      Agama
    </td>
    <td width="75%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_agama_islam]" id="30_agama_islam"  onclick="checkthis('30_agama_islam')">
          <span class="lbl" > &nbsp; Islam</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_agama_hindu]" id="30_agama_hindu"  onclick="checkthis('30_agama_hindu')">
          <span class="lbl" > &nbsp; Hindu</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_agama_kristen]" id="30_agama_kristen"  onclick="checkthis('30_agama_kristen')">
          <span class="lbl" > &nbsp; Kristen</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_agama_Katolik]" id="30_agama_Katolik"  onclick="checkthis('30_agama_Katolik')">
          <span class="lbl" > &nbsp; Katolik</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_agama_budha]" id="30_agama_budha"  onclick="checkthis('30_agama_budha')">
          <span class="lbl" > &nbsp; Budha</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_agama_lainnya]" id="30_agama_lainnya"  onclick="checkthis('30_agama_lainnya')">
          <span class="lbl" > &nbsp; Lainnya  </span>
        </label>
        <input class="input_type" type="text" style="width: 50px" name="form_30[30_txt_agama_lainnya]" id="30_txt_agama_lainnya" onchange="fillthis('30_txt_agama_lainnya')">
      </div>
    </td>
  </tr>
</table>
<br>
<span style="font-weight: bold; font-size: 14px">SKRINING NUTRISI <i>(MALNUTRITION SCREENING TOOLS)</i></span>
<br>
<table width="100%">
  <tr>
    <td width="30px" valign="top">1.</td>
    <td width="80%">Apakah pasien mengalami penurunan BB yang tidak diinginkan dalam 6 bulan terakhir ?
      <br>
      <label>
          <input type="checkbox" class="ace" name="form_30[prm_30_1]" id="prm_30_1"  onclick="checkthis('prm_30_1')">
          <span class="lbl"> Tidak ada penurunan berat badan</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_30[prm_30_2]" id="prm_30_2"  onclick="checkthis('prm_30_2')">
        <span class="lbl"> Tidak yakin / tidak tahu / terasa baju lebih longgar</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_30[prm_30_3]" id="prm_30_3"  onclick="checkthis('prm_30_3')">
        <span class="lbl"> Ya, berapa pernurunan berat badan tersebut ?</span>
      </label><br>
        <div style="padding-left: 20px">
          <label>
            <input type="checkbox" class="ace" name="form_30[prm_30_3_1]" id="prm_30_3_1"  onclick="checkthis('prm_30_3_1')">
            <span class="lbl"> 1 - 5 kg</span>
          </label>
          <label>
            <input type="checkbox" class="ace" name="form_30[prm_30_3_2]" id="prm_30_3_2"  onclick="checkthis('prm_30_3_2')">
            <span class="lbl"> 6 - 10 kg</span>
          </label>
          <label>
            <input type="checkbox" class="ace" name="form_30[prm_30_3_3]" id="prm_30_3_3"  onclick="checkthis('prm_30_3_3')">
            <span class="lbl"> 11 - 15 kg</span>
          </label>
          <label>
            <input type="checkbox" class="ace" name="form_30[prm_30_3_4]" id="prm_30_3_4"  onclick="checkthis('prm_30_3_4')">
            <span class="lbl"> > 15 kg</span>
          </label>
        </div>
    </td>
  </tr>

  <tr>
    <td width="30px" valign="top">2.</td>
    <td>Apakah asupan makanan berkurang karena tidak nafsu makan?</td>
    <td>
      <label>
          <input type="checkbox" class="ace" name="form_30[asupan_makanan_30_1]" id="asupan_makanan_30_1"  onclick="checkthis('asupan_makanan_30_1')">
          <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[asupan_makanan_30_2]" id="asupan_makanan_30_2"  onclick="checkthis('asupan_makanan_30_2')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>

</table>
<br>

<br>
<span style="font-weight: bold; font-size: 14px">PENILAIAN RISIKO DEKUBITUS (METODE NORTON)</span>
<br>
<table class="table" width="100%">
  <tr>
    <th class="center">Indikator</th>
    <th class="center">4</th>
    <th class="center">3</th>
    <th class="center">2</th>
    <th class="center">1</th>
  </tr>

  <tr>
    <td>Kondisi Fisik</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_fisik_baik]" id="30_fisik_baik"  onclick="checkthis('30_fisik_baik')">
        <span class="lbl"> Baik</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_fisik_sedang]" id="30_fisik_sedang"  onclick="checkthis('30_fisik_sedang')">
        <span class="lbl"> Sedang</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_fisik_buruk]" id="30_fisik_buruk"  onclick="checkthis('30_fisik_buruk')">
        <span class="lbl"> Buruk</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_sangat_buruk]" id="30_sangat_buruk"  onclick="checkthis('30_sangat_buruk')">
        <span class="lbl"> Sangat Buruk</span>
      </label>
    </td>
  </tr>

  <tr>
    <td>Status Mental</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_stts_mental_sadar]" id="30_stts_mental_sadar"  onclick="checkthis('30_stts_mental_sadar')">
        <span class="lbl"> Sadar</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_stts_mental_apatis]" id="30_stts_mental_apatis"  onclick="checkthis('30_stts_mental_apatis')">
        <span class="lbl"> Apatis</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_stts_mental_bingung]" id="30_stts_mental_bingung"  onclick="checkthis('30_stts_mental_bingung')">
        <span class="lbl"> Bingung</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_stts_mental_stupor]" id="30_stts_mental_stupor"  onclick="checkthis('30_stts_mental_stupor')">
        <span class="lbl"> Stupor</span>
      </label>
    </td>
  </tr>

  <tr>
    <td>Aktifitas</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_aktf_jalan]" id="30_aktf_jalan"  onclick="checkthis('30_aktf_jalan')">
        <span class="lbl"> Jalan Sendiri</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_aktf_jalan_dgn_bantuan]" id="30_aktf_jalan_dgn_bantuan"  onclick="checkthis('30_aktf_jalan_dgn_bantuan')">
        <span class="lbl"> Jalan dengan bantuan</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_aktf_kursi_roda]" id="30_aktf_kursi_roda"  onclick="checkthis('30_aktf_kursi_roda')">
        <span class="lbl"> Kursi Roda</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_aktf_ditempat_tdr]" id="30_aktf_ditempat_tdr"  onclick="checkthis('30_aktf_ditempat_tdr')">
        <span class="lbl"> Di tempat tidur</span>
      </label>
    </td>
  </tr>

  <tr>
    <td>Mobilitas</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_mobilitas_bebas]" id="30_mobilitas_bebas"  onclick="checkthis('30_mobilitas_bebas')">
        <span class="lbl"> Bebas Bergerak</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_mobilitas_terbatas]" id="30_mobilitas_terbatas"  onclick="checkthis('30_mobilitas_terbatas')">
        <span class="lbl"> Gerak Terbatas</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_mobilitas_sangat_terbatas]" id="30_mobilitas_sangat_terbatas"  onclick="checkthis('30_mobilitas_sangat_terbatas')">
        <span class="lbl"> Sangat Terbatas</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_mobilitas_tdk_gerak]" id="30_mobilitas_tdk_gerak"  onclick="checkthis('30_mobilitas_tdk_gerak')">
        <span class="lbl"> Tidak bergerak</span>
      </label>
    </td>
  </tr>

  <tr>
    <td>Inkontinensia</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_kontinen]" id="30_kontinen"  onclick="checkthis('30_kontinen')">
        <span class="lbl"> Kontinen</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_kdg_kontinen]" id="30_kdg_kontinen"  onclick="checkthis('30_kdg_kontinen')">
        <span class="lbl"> Kadang Kontinen</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_selalu_kontinen]" id="30_selalu_kontinen"  onclick="checkthis('30_selalu_kontinen')">
        <span class="lbl"> Selalu Kontinen</span>
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[30_inkontinen]" id="30_inkontinen"  onclick="checkthis('30_inkontinen')">
        <span class="lbl"> Inkontinen urin dan alvi</span>
      </label>
    </td>
  </tr>

  <tr>
    <td><b>Total Skor</b></td>
    <td class="center">
      <input type="text" style="width: 50%" class="input_type" name="form_30[30_skor_4]" id="30_skor_4" onchange="fillthis('30_skor_4')">
    </td>
    <td class="center">
      <input type="text" style="width: 50%" class="input_type" name="form_30[30_skor_3]" id="30_skor_3" onchange="fillthis('30_skor_3')">
    </td>
    <td class="center">
      <input type="text" style="width: 50%" class="input_type" name="form_30[30_skor_2]" id="30_skor_2" onchange="fillthis('30_skor_2')">
    </td>
    <td class="center">
      <input type="text" style="width: 50%" class="input_type" name="form_30[30_skor_1]" id="30_skor_1" onchange="fillthis('30_skor_1')">
    </td>
  </tr>

  <tr>
    <td><b>Kriteria Penilaian</b></td>
    <td class="center" colspan="3">
      16 - 20 : Tidak ada risiko ; 12 - 15 : Rentan Risiko ; &lt; 12 : Risiko tinggi
    </td>
    <td class="center">
      <input type="text" style="width: 50%" class="input_type" name="form_30[30_total_skor]" id="30_total_skor" onchange="fillthis('30_total_skor')">
    </td>
  </tr>

</table>
<br>

<span style="font-weight: bold; font-size: 14px">PENILAIAN TINGKAT NYERI</span>
<table width="100%">
  <tr>
    <td width="150px">Keluhan nyeri</td>
    <td width="80%">
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_1]" id="penilaian_tingkat_nyeri_30_1"  onclick="checkthis('penilaian_tingkat_nyeri_30_1')">
        <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_2]" id="penilaian_tingkat_nyeri_30_2"  onclick="checkthis('penilaian_tingkat_nyeri_30_2')">
        <span class="lbl"> Tidak </span>
      </label>
    </td>
  </tr>
  <tr>
    <td width="150px">Pencetus / Provoke</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_provoke_1]" id="penilaian_tingkat_nyeri_30_provoke_1"  onclick="checkthis('penilaian_tingkat_nyeri_30_provoke_1')">
        <span class="lbl"> Benturan</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_provoke_2]" id="penilaian_tingkat_nyeri_30_provoke_2"  onclick="checkthis('penilaian_tingkat_nyeri_30_provoke_2')">
        <span class="lbl"> Tindakan </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_provoke_3]" id="penilaian_tingkat_nyeri_30_provoke_3"  onclick="checkthis('penilaian_tingkat_nyeri_30_provoke_3')">
        <span class="lbl"> Proses penyakit, </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_provoke_4]" id="penilaian_tingkat_nyeri_30_provoke_4"  onclick="checkthis('penilaian_tingkat_nyeri_30_provoke_4')">
        <span class="lbl"> Lain-lain </span>
        <input type="text" style="width: 40%" class="input_type" name="form_30[desc_penilaian_tingkat_nyeri_30_provoke_4]" id="desc_penilaian_tingkat_nyeri_30_provoke_4" onchange="fillthis('desc_penilaian_tingkat_nyeri_30_provoke_4')">
      </label>
    </td>
  </tr>
  <tr>
    <td width="150px" valign="top">Kualitas / Quality</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_qty_1]" id="penilaian_tingkat_nyeri_30_qty_1"  onclick="checkthis('penilaian_tingkat_nyeri_30_qty_1')">
        <span class="lbl"> Seperti tertusuk-tusuk tajam/tumpul</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_qty_2]" id="penilaian_tingkat_nyeri_30_qty_2"  onclick="checkthis('penilaian_tingkat_nyeri_30_qty_2')">
        <span class="lbl"> Berdenyut </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_qty_3]" id="penilaian_tingkat_nyeri_30_qty_3"  onclick="checkthis('penilaian_tingkat_nyeri_30_qty_3')">
        <span class="lbl"> Terbakar </span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_qty_4]" id="penilaian_tingkat_nyeri_30_qty_4"  onclick="checkthis('penilaian_tingkat_nyeri_30_qty_4')">
        <span class="lbl"> Tertindih benda berat </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_qty_5]" id="penilaian_tingkat_nyeri_30_qty_5"  onclick="checkthis('penilaian_tingkat_nyeri_30_qty_5')">
        <span class="lbl"> Diremas </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_qty_6]" id="penilaian_tingkat_nyeri_30_qty_6"  onclick="checkthis('penilaian_tingkat_nyeri_30_qty_6')">
        <span class="lbl"> Terpelintir </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_30_qty_7]" id="penilaian_tingkat_nyeri_30_qty_7"  onclick="checkthis('penilaian_tingkat_nyeri_30_qty_7')">
        <span class="lbl"> Teriris </span>
      </label>

    </td>
  </tr>
  <tr>
    <td width="150px">Radiasi / Region</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_radiasi_30_1]" id="penilaian_tingkat_nyeri_radiasi_30_1"  onclick="checkthis('penilaian_tingkat_nyeri_radiasi_30_1')">
        <span class="lbl"> Lokasi</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_radiasi_30_2]" id="penilaian_tingkat_nyeri_radiasi_30_2"  onclick="checkthis('penilaian_tingkat_nyeri_radiasi_30_2')">
        <span class="lbl"> Menyebar </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_radiasi_30_3]" id="penilaian_tingkat_nyeri_radiasi_30_3"  onclick="checkthis('penilaian_tingkat_nyeri_radiasi_30_3')">
        <span class="lbl"> Tidak </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_radiasi_30_4]" id="penilaian_tingkat_nyeri_radiasi_30_4"  onclick="checkthis('penilaian_tingkat_nyeri_radiasi_30_4')">
        <span class="lbl"> Ya </span>
      </label>
      <input type="text" style="width: 40%" class="input_type" name="form_30[desc_penilaian_tingkat_nyeri_radiasi_30_4]" id="desc_penilaian_tingkat_nyeri_radiasi_30_4" onchange="fillthis('desc_penilaian_tingkat_nyeri_radiasi_30_4')">
    </td>
  </tr>
  <tr>
    <td width="150px" valign="top">Skala / Severity</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_skala_30_1]" id="penilaian_tingkat_nyeri_skala_30_1"  onclick="checkthis('penilaian_tingkat_nyeri_skala_30_1')">
        <span class="lbl"> FLACSS</span>, Score
        <input type="text" style="width: 40%" class="input_type" name="form_30[score_flacss]" id="score_flacss" onchange="fillthis('score_flacss')">
      </label><br>
      <label>
        <span class="lbl" style="padding-left: 25px"> Wong Baker Faces</span>, Score
        <input type="text" style="width: 40%" class="input_type" name="form_30[score_wbf]" id="score_wbf" onchange="fillthis('score_wbf')">
      </label><br>
      <label>
        <span class="lbl" style="padding-left: 25px"> VAS/NRS</span>, Score
        <input type="text" style="width: 40%" class="input_type" name="form_30[score_vas_nrs]" id="score_vas_nrs" onchange="fillthis('score_vas_nrs')">
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_30[penilaian_tingkat_nyeri_skala_30_2]" id="penilaian_tingkat_nyeri_skala_30_2"  onclick="checkthis('penilaian_tingkat_nyeri_skala_30_2')">
        <span class="lbl"> BPS</span>, Score
        <input type="text" style="width: 40%" class="input_type" name="form_30[score_bps]" id="score_bps" onchange="fillthis('score_bps')">
      </label>
    </td>
  </tr>
  <tr>
    <td width="150px" valign="top">Durasi / Times</td>
    <td>
        Kapan mulai dirasa : 
        <input type="text" style="width: 50%" class="input_type" name="form_30[penilaian_tingkat_nyeri_durasi_1]" id="penilaian_tingkat_nyeri_durasi_1" onchange="fillthis('penilaian_tingkat_nyeri_durasi_1')">
        <br>
        Berapa lama dirasa / kekambuhan : 
        <input type="text" style="width: 50%" class="input_type" name="form_30[penilaian_tingkat_nyeri_durasi_2]" id="penilaian_tingkat_nyeri_durasi_2" onchange="fillthis('penilaian_tingkat_nyeri_durasi_2')">
    </td>
  </tr30</table>
<br>

<table>
  <tr>
    <td width="50%">
      <table class="table">
        <tr><td colspan="3" align="center"><b>SKRINING RISIKO JATUH</b></td></tr>
        <tr>
          <th>No.</th>
          <th>Parameter</th>
          <th>Nilai</th>
        </tr>
        <tr>
          <td>1</td>
          <td>Apakah ada riwayat jatuh dalam waktu 3 bulan sebab apapun</td>
          <td>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_1_y]" id="30_srj_1_y"  onclick="checkthis('30_srj_1_y')">
              <span class="lbl" > &nbsp; Ya</span>
            </label>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_1_n]" id="30_srj_1_n"  onclick="checkthis('30_srj_1_n')">
              <span class="lbl" > &nbsp; Tidak</span>
            </label>
          </td>
        </tr>
        <tr>
          <td>2</td>
          <td>Apakah mempunyai penyakit penyerta (diagnosa sekunder)</td>
          <td>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_2_y]" id="30_srj_2_y"  onclick="checkthis('30_srj_2_y')">
              <span class="lbl" > &nbsp; Ya</span>
            </label>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_2_n]" id="30_srj_2_n"  onclick="checkthis('30_srj_2_n')">
              <span class="lbl" > &nbsp; Tidak</span>
            </label>
          </td>
        </tr>
        <tr>
          <td>3</td>
          <td colspan="2">Alat bantu berjalan</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Dibantu suster / tidak menggunakan alat bantu</td>
          <td>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_3a_y]" id="30_srj_3a_y"  onclick="checkthis('30_srj_3a_y')">
              <span class="lbl" > &nbsp; Ya</span>
            </label>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Menggunakan alat bantu : Kruk/ Tongkat, Kursi Roda</td>
          <td>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_3b_y]" id="30_srj_3b_y"  onclick="checkthis('30_srj_3b_y')">
              <span class="lbl" > &nbsp; Ya</span>
            </label>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Merambat dengan berpegangan pada meja, kursi</td>
          <td>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_3c_y]" id="30_srj_3c_y"  onclick="checkthis('30_srj_3c_y')">
              <span class="lbl" > &nbsp; Ya</span>
            </label>
          </td>
        </tr>
        <tr>
          <td>4</td>
          <td>Apakah terpasang infus / pemberian antikoagulan (Heparin) / obat lain yang mempunyai efek samping risiko jatuh</td>
          <td>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_4_y]" id="30_srj_4_y"  onclick="checkthis('30_srj_4_y')">
              <span class="lbl" > &nbsp; Ya</span>
            </label>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_4_n]" id="30_srj_4_n"  onclick="checkthis('30_srj_4_n')">
              <span class="lbl" > &nbsp; Tidak</span>
            </label>
          </td>
        </tr>
        <tr>
          <td>5</td>
          <td colspan="2">Kondisi untuk melakukan gerakan berpindah / Mobilisasi</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Normal / Bed rest / Imobilisasi</td>
          <td>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_5a_y]" id="30_srj_5a_y"  onclick="checkthis('30_srj_5a_y')">
              <span class="lbl" > &nbsp; Ya</span>
            </label>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Lemah</td>
          <td>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_5b_y]" id="30_srj_5b_y"  onclick="checkthis('30_srj_5b_y')">
              <span class="lbl" > &nbsp; Ya</span>
            </label>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Ada keterbatasan berjalan</td>
          <td>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_5c_y]" id="30_srj_5c_y"  onclick="checkthis('30_srj_5c_y')">
              <span class="lbl" > &nbsp; Ya</span>
            </label>
          </td>
        </tr>
        <tr>
          <td>6</td>
          <td colspan="2">Bagaimana status mental</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Menyadari kelemahannya</td>
          <td>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_6a_y]" id="30_srj_6a_y"  onclick="checkthis('30_srj_6a_y')">
              <span class="lbl" > &nbsp; Ya</span>
            </label>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Tidak menyadari kelemahannya</td>
          <td>
            <label>
              <input type="checkbox" class="ace" name="form_30[30_srj_6b_y]" id="30_srj_6b_y"  onclick="checkthis('30_srj_6b_y')">
              <span class="lbl" > &nbsp; Ya</span>
            </label>
          </td>
        </tr>
      </table>
    </td>
    <td width="50%" valign="top">
      <table class="table">
        <tr>
          <td align="center" colspan="5"><b>SKRINING KEMAMPUAN FUNGSIONAL</b></td>
        </tr>
        <tr>
          <th>No.</th>
          <th>Parameter</th>
          <th align="center" width="30px">0</th>
          <th align="center" width="30px">5</th>
          <th align="center" width="30px">10</th>
        </tr>
        <tr>
          <td align="center">1</td>
          <td>Makan</td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_1_0_y]" id="30_skf_1_0_y"  onclick="checkthis('30_skf_1_0_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_1_5_y]" id="30_skf_1_5_y"  onclick="checkthis('30_skf_1_5_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_1_10_y]" id="30_skf_1_10_y"  onclick="checkthis('30_skf_1_10_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
        </tr>
        <tr>
          <td align="center">2</td>
          <td>Berubah sikap dari berbaring ke duduk atau sebaliknya</td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_2_0_y]" id="30_skf_2_0_y"  onclick="checkthis('30_skf_2_0_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_2_5_y]" id="30_skf_2_5_y"  onclick="checkthis('30_skf_2_5_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_2_10_y]" id="30_skf_2_10_y"  onclick="checkthis('30_skf_2_10_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
        </tr>
        <tr>
          <td align="center">3</td>
          <td>Mandi</td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_3_0_y]" id="30_skf_3_0_y"  onclick="checkthis('30_skf_3_0_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_3_5_y]" id="30_skf_3_5_y"  onclick="checkthis('30_skf_3_5_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_3_10_y]" id="30_skf_3_10_y"  onclick="checkthis('30_skf_3_10_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
        </tr>
        <tr>
          <td align="center">4</td>
          <td>Berpakaian</td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_4_0_y]" id="30_skf_4_0_y"  onclick="checkthis('30_skf_4_0_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_4_5_y]" id="30_skf_4_5_y"  onclick="checkthis('30_skf_4_5_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_4_10_y]" id="30_skf_4_10_y"  onclick="checkthis('30_skf_4_10_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
        </tr>
        <tr>
          <td align="center">5</td>
          <td>Membersihkan diri</td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_5_0_y]" id="30_skf_5_0_y"  onclick="checkthis('30_skf_5_0_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_5_5_y]" id="30_skf_5_5_y"  onclick="checkthis('30_skf_5_5_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_5_10_y]" id="30_skf_5_10_y"  onclick="checkthis('30_skf_5_10_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
        </tr>
        <tr>
          <td align="center">6</td>
          <td>Berpindah / berjalan</td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_6_0_y]" id="30_skf_6_0_y"  onclick="checkthis('30_skf_6_0_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_6_5_y]" id="30_skf_6_5_y"  onclick="checkthis('30_skf_6_5_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_6_10_y]" id="30_skf_6_10_y"  onclick="checkthis('30_skf_6_10_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
        </tr>
        <tr>
          <td align="center">7</td>
          <td>Toiletting (masuk keluar toilet sendiri)</td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_7_0_y]" id="30_skf_7_0_y"  onclick="checkthis('30_skf_7_0_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_7_5_y]" id="30_skf_7_5_y"  onclick="checkthis('30_skf_7_5_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_7_10_y]" id="30_skf_7_10_y"  onclick="checkthis('30_skf_7_10_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
        </tr>
        <tr>
          <td align="center">8</td>
          <td>Naik turun tangga</td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_8_0_y]" id="30_skf_8_0_y"  onclick="checkthis('30_skf_8_0_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_8_5_y]" id="30_skf_8_5_y"  onclick="checkthis('30_skf_8_5_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_8_10_y]" id="30_skf_8_10_y"  onclick="checkthis('30_skf_8_10_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
        </tr>
        <tr>
          <td align="center">9</td>
          <td>Mengendalikan BAK</td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_9_0_y]" id="30_skf_9_0_y"  onclick="checkthis('30_skf_9_0_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_9_5_y]" id="30_skf_9_5_y"  onclick="checkthis('30_skf_9_5_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_9_10_y]" id="30_skf_9_10_y"  onclick="checkthis('30_skf_9_10_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
        </tr>
        <tr>
          <td align="center">10</td>
          <td>Mengendalikan BAB</td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_10_0_y]" id="30_skf_10_0_y"  onclick="checkthis('30_skf_10_0_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_10_5_y]" id="30_skf_10_5_y"  onclick="checkthis('30_skf_10_5_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
          <td align="center">
            <label>
              <input type="checkbox" class="ace" name="form_30[30_skf_10_10_y]" id="30_skf_10_10_y"  onclick="checkthis('30_skf_10_10_y')">
              <span class="lbl" >&nbsp;</span>
            </label>
          </td>
        </tr>
        <tr>
          <td align="center" colspan="5">TOTAL JUMLAH SKOR</td>
        </tr>
        <tr>
          <td colspan="5">
            Keterangan : <br>
            0 = Bila pasien tidak dapat melakukan<br>
            5 = Bila pasien dibantu untuk melakukannya<br>
            10 = Bila pasien mandiri<br>
            <br>
            Interpretasi : <br>
            0 - 20 = Ketergantungan total<br>
            21 - 99 = Ketergantungan sebagian (ringan, sedang, berat)<br>
            100 = Mandiri
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
<span style="font-weight: bold; font-size: 14px">IDENTIFIKASI KEBUTUHAN BELAJAR EDUKASI</span>
<table class="table">
  <tr>
    <td width="25%">
      Pemahaman tentang penyakit
    </td>
    <td width="75%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_paham_penyakit]" id="30_ya_paham_penyakit"  onclick="checkthis('30_ya_paham_penyakit')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tdk_paham_penyakit]" id="30_tdk_paham_penyakit"  onclick="checkthis('30_tdk_paham_penyakit')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td width="25%">
      Pemahaman tentang pengobatan
    </td>
    <td width="75%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_paham_pengobatan]" id="30_ya_paham_pengobatan"  onclick="checkthis('30_ya_paham_pengobatan')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tdk_paham_pengobatan]" id="30_tdk_paham_pengobatan"  onclick="checkthis('30_tdk_paham_pengobatan')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td width="25%">
      Pemahaman tentang perawatan
    </td>
    <td width="75%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_paham_perawatan]" id="30_ya_paham_perawatan"  onclick="checkthis('30_ya_paham_perawatan')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tdk_paham_perawatan]" id="30_tdk_paham_perawatan"  onclick="checkthis('30_tdk_paham_perawatan')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td width="25%">
      Pemahaman tentang nutrisi / diet
    </td>
    <td width="75%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_ya_paham_nutrisi]" id="30_ya_paham_nutrisi"  onclick="checkthis('30_ya_paham_nutrisi')">
          <span class="lbl" > &nbsp; Ya</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tdk_paham_nutrisi]" id="30_tdk_paham_nutrisi"  onclick="checkthis('30_tdk_paham_nutrisi')">
          <span class="lbl" > &nbsp; Tidak</span>
        </label>
      </div>
    </td>
  </tr>
</table>

<br>
<span style="font-weight: bold; font-size: 14px">HAMBATAN UNTUK MENERIMA EDUKASI</span>
<table class="table">
  <tr>
    <td width="100%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_tdk_ada_hambatan]" id="30_tdk_ada_hambatan"  onclick="checkthis('30_tdk_ada_hambatan')">
          <span class="lbl" > &nbsp; Tidak ada</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_gangguan_emosi]" id="30_gangguan_emosi"  onclick="checkthis('30_gangguan_emosi')">
          <span class="lbl" > &nbsp; Ada gangguan emosi</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_keterbatasan_budaya]" id="30_keterbatasan_budaya"  onclick="checkthis('30_keterbatasan_budaya')">
          <span class="lbl" > &nbsp; Ada keterbatasan dalam hal budaya / spiritual / agama</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_gangguan_penglihatan]" id="30_gangguan_penglihatan"  onclick="checkthis('30_gangguan_penglihatan')">
          <span class="lbl" > &nbsp; Ada gangguan penglihatan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_gangguan_fisik]" id="30_gangguan_fisik"  onclick="checkthis('30_gangguan_fisik')">
          <span class="lbl" > &nbsp; Ada gangguan fisik</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_gangguan_pendengaran]" id="30_gangguan_pendengaran"  onclick="checkthis('30_gangguan_pendengaran')">
          <span class="lbl" > &nbsp; Ada gangguan pendengaran</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_gangguan_kognitif]" id="30_gangguan_kognitif"  onclick="checkthis('30_gangguan_kognitif')">
          <span class="lbl" > &nbsp; Ada gangguan kognitif</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_keterbatasan_bahasa]" id="30_keterbatasan_bahasa"  onclick="checkthis('30_keterbatasan_bahasa')">
          <span class="lbl" > &nbsp; Ada keterbatasan dalam berbahasa</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_belum_melek_huruf]" id="30_belum_melek_huruf"  onclick="checkthis('30_belum_melek_huruf')">
          <span class="lbl" > &nbsp; Belum melek huruf</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_30[30_keterbatasan_motivasi]" id="30_keterbatasan_motivasi"  onclick="checkthis('30_keterbatasan_motivasi')">
          <span class="lbl" > &nbsp; Keterbatasan Motivasi</span>
        </label>
      </div>
    </td>
  </tr>
</table>
<br>

<span style="font-weight: bold; font-size: 14px">DAFTAR MASALAH KEPERAWATAN</span>
<table class="table">
  <tr>
    <td width="50%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_1]" id="30_mslh_kprwatan_1"  onclick="checkthis('30_mslh_kprwatan_1')">
          <span class="lbl" > &nbsp; Bersihin jalan nafas tidak efektif</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_2]" id="30_mslh_kprwatan_2"  onclick="checkthis('30_mslh_kprwatan_2')">
          <span class="lbl" > &nbsp; Pola nafas tidak efektif</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_3]" id="30_mslh_kprwatan_3"  onclick="checkthis('30_mslh_kprwatan_3')">
          <span class="lbl" > &nbsp; Gangguan pertukaran gas</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_4]" id="30_mslh_kprwatan_4"  onclick="checkthis('30_mslh_kprwatan_4')">
          <span class="lbl" > &nbsp; Gangguan sirkulasi spontan</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_5]" id="30_mslh_kprwatan_5"  onclick="checkthis('30_mslh_kprwatan_5')">
          <span class="lbl" > &nbsp; Risiko perfusi serebral tidak efektif</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_6]" id="30_mslh_kprwatan_6"  onclick="checkthis('30_mslh_kprwatan_6')">
          <span class="lbl" > &nbsp; Risiko ketidakseimbangan cairan</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_7]" id="30_mslh_kprwatan_7"  onclick="checkthis('30_mslh_kprwatan_7')">
          <span class="lbl" > &nbsp; Risiko pendarahan</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_8]" id="30_mslh_kprwatan_8"  onclick="checkthis('30_mslh_kprwatan_8')">
          <span class="lbl" > &nbsp; Ketidakstabilan kadar glukosa darah</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_9]" id="30_mslh_kprwatan_9"  onclick="checkthis('30_mslh_kprwatan_9')">
          <span class="lbl" > &nbsp; Nausea</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_10]" id="30_mslh_kprwatan_10"  onclick="checkthis('30_mslh_kprwatan_10')">
          <span class="lbl" > &nbsp; Hipertermia</span>
        </label>
      </div>
    </td>
    <td width="50%">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_11]" id="30_mslh_kprwatan_11"  onclick="checkthis('30_mslh_kprwatan_11')">
          <span class="lbl" > &nbsp; Penurunan curah jantung</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_12]" id="30_mslh_kprwatan_12"  onclick="checkthis('30_mslh_kprwatan_12')">
          <span class="lbl" > &nbsp; Perfusi perifer tidak efektif</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_13]" id="30_mslh_kprwatan_13"  onclick="checkthis('30_mslh_kprwatan_13')">
          <span class="lbl" > &nbsp; Risiko gangguan sirkulasi spontan</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_14]" id="30_mslh_kprwatan_14"  onclick="checkthis('30_mslh_kprwatan_14')">
          <span class="lbl" > &nbsp; Risiko perfusi miokard tidak efektif</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_15]" id="30_mslh_kprwatan_15"  onclick="checkthis('30_mslh_kprwatan_15')">
          <span class="lbl" > &nbsp; Diare</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_16]" id="30_mslh_kprwatan_16"  onclick="checkthis('30_mslh_kprwatan_16')">
          <span class="lbl" > &nbsp; Hipervolemia</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_17]" id="30_mslh_kprwatan_17"  onclick="checkthis('30_mslh_kprwatan_17')">
          <span class="lbl" > &nbsp; Hipovolemia</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_18]" id="30_mslh_kprwatan_18"  onclick="checkthis('30_mslh_kprwatan_18')">
          <span class="lbl" > &nbsp; Nyeri akut</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_30[30_mslh_kprwatan_19]" id="30_mslh_kprwatan_19"  onclick="checkthis('30_mslh_kprwatan_19')">
          <span class="lbl" > &nbsp; Nyeri kronis</span>
        </label>
      </div>
      <input class="input_type" type="text" style="width: 300px" name="form_30[30_mslh_kprwatan_20]" id="30_mslh_kprwatan_20" onchange="fillthis('30_mslh_kprwatan_20')"><br>
      <input class="input_type" type="text" style="width: 300px" name="form_30[30_mslh_kprwatan_21]" id="30_mslh_kprwatan_21" onchange="fillthis('30_mslh_kprwatan_21')"><br>
      <input class="input_type" type="text" style="width: 300px" name="form_30[30_mslh_kprwatan_22]" id="30_mslh_kprwatan_22" onchange="fillthis('30_mslh_kprwatan_22')"><br>
      <input class="input_type" type="text" style="width: 300px" name="form_30[30_mslh_kprwatan_23]" id="30_mslh_kprwatan_23" onchange="fillthis('30_mslh_kprwatan_23')"><br>
      <input class="input_type" type="text" style="width: 300px" name="form_30[30_mslh_kprwatan_24]" id="30_mslh_kprwatan_24" onchange="fillthis('30_mslh_kprwatan_24')"><br>
    </td>

  </tr>
</table>
<hr>
<table class="table">
  <tr>
    <td colspan="2">Perawat Yang Mengkaji</td>
    <td colspan="2">Verifikasi</td>
  </tr>
  <tr>
    <td>Tanggal : <input class="input_type" type="text" style="width: 100px" name="form_30[30_dikaji_tgl]" id="30_dikaji_tgl" onchange="fillthis('30_dikaji_tgl')" value="<?php echo date('d/m/Y')?>"><br></td>
    <td>Jam : <input class="input_type" type="text" style="width: 100px" name="form_30[30_dikaji_jam]" id="30_dikaji_jam" onchange="fillthis('30_dikaji_jam')" value="<?php echo date('H:i:s')?>"><br></td>
    <td>Tanggal : <input class="input_type" type="text" style="width: 100px" name="form_30[30_diverif_tgl]" id="30_diverif_tgl" onchange="fillthis('30_diverif_tgl')" value="<?php echo date('d/m/Y')?>"><br></td>
    <td>Jam : <input class="input_type" type="text" style="width: 100px" name="form_30[30_diverif_jam]" id="30_diverif_jam" onchange="fillthis('30_diverif_jam')" value="<?php echo date('H:i:s')?>"><br></td>
  </tr>

  <tr>
    <td>Nama Perawat</td>
    <td>Tanda Tangan</td>
    <td>Nama Dokter</td>
    <td>Tanda Tangan</td>
  </tr>
  <tr>
    <td valign="middle" align="center"><br><br><?php echo $this->session->userdata('user')->fullname?></td>
    <td><br><br></td>
    <td><br><br></td>
    <td><br><br></td>
  </tr>
<table>
