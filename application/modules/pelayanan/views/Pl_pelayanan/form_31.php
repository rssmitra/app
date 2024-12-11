<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script type="text/javascript">
  $('#31_diagnosa_medis').typeahead({
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
      $('#31_diagnosa_medis').val(label_item);
      $('#31_diagnosa_medis_hidden').val(val_item);
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

<div style="text-align: center; font-size: 14px"><b>RENCANA PEMULANGAN PASIEN</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<label>Diagnosa Medis :</label><br>
<input class="input_type" type="text" style="width: 100% !important" name="form_31[31_diagnosa_masuk]" id="31_diagnosa_masuk" onchange="fillthis('31_diagnosa_masuk')"> 
<input class="input_type" type="hidden" style="width: 100% !important" name="form_31[31_diagnosa_masuk_hidden]" id="31_diagnosa_masuk_hidden" onchange="fillthis('31_diagnosa_masuk_hidden')"> 
<br>
<br>
<span style="font-weight: bold; font-size: 14px">KETERANGAN SAAT MASUK RUMAH SAKIT</span>

<table class="table">
  <tr>
    <td width="300px">Tanggal/Jam Masuk Rumah Sakit</td>
    <td><input class="input_type" type="text" style="width: 100%" name="form_31[31_tgl_jam_masuk_ri]" id="31_tgl_jam_masuk_ri" onchange="fillthis('31_tgl_jam_masuk_ri')"> </td>
  </tr>

  <tr>
    <td>Alasan masuk rumah sakit</td>
    <td><input class="input_type" type="text" style="width: 100%" name="form_31[31_alasan_masuk]" id="31_alasan_masuk" onchange="fillthis('31_alasan_masuk')"> </td>
  </tr>

  <tr>
    <td>Tanggal/Jam perencanaan pemulangan pasien</td>
    <td><input class="input_type" type="text" style="width: 100%" name="form_31[31_rencana_pulang]" id="31_rencana_pulang" onchange="fillthis('31_rencana_pulang')"> </td>
  </tr>

  <tr>
    <td>Estimasi tanggal pemulangan pasien</td>
    <td><input class="input_type" type="text" style="width: 100%" name="form_31[31_estimasi_pulang]" id="31_estimasi_pulang" onchange="fillthis('31_estimasi_pulang')"> </td>
  </tr>

  <tr>
    <td>Nama Perawat</td>
    <td><input class="input_type" type="text" style="width: 100%" name="form_31[31_nm_perawat]" id="31_nm_perawat" onchange="fillthis('31_nm_perawat')"> </td>
  </tr>

</table>
<br>
<span style="font-weight: bold; font-size: 14px">KETERANGAN RENCANA PEMULANGAN PASIEN</span>

<table class="table">
  <tr>
    <td valign="top">1</td>
    <td valign="top" colspan="3">
      Pengaruh rawat inap terhadap
      <ul>
        <li>
          Pasien dan keluarga pasien
          <label>
            <input type="checkbox" class="ace" name="form_31[31_1_1_ya]" id="31_1_1_ya"  onclick="checkthis('31_1_1_ya')">
            <span class="lbl" > &nbsp; Ya</span>
          </label>
          <label>
            <input type="checkbox" class="ace" name="form_31[31_1_1_no]" id="31_1_1_no"  onclick="checkthis('31_1_1_no')">
            <span class="lbl" > &nbsp; Tidak</span>
          </label>
        </li>
        <li>
          Pekerjaan
          <label>
            <input type="checkbox" class="ace" name="form_31[31_1_2_ya]" id="31_1_2_ya"  onclick="checkthis('31_1_2_ya')">
            <span class="lbl" > &nbsp; Ya</span>
          </label>
          <label>
            <input type="checkbox" class="ace" name="form_31[31_1_2_no]" id="31_1_2_no"  onclick="checkthis('31_1_2_no')">
            <span class="lbl" > &nbsp; Tidak</span>
          </label>
        </li>
        <li>
          Keuangan
          <label>
            <input type="checkbox" class="ace" name="form_31[31_1_3_ya]" id="31_1_3_ya"  onclick="checkthis('31_1_3_ya')">
            <span class="lbl" > &nbsp; Ya</span>
          </label>
          <label>
            <input type="checkbox" class="ace" name="form_31[31_1_3_no]" id="31_1_3_no"  onclick="checkthis('31_1_3_no')">
            <span class="lbl" > &nbsp; Tidak</span>
          </label>
        </li>
      </ul>
    </td>
  </tr>
  <tr>
    <td valign="top">2</td>
    <td valign="top" width="400px">Antisipasi terhadap masalah saat pulang</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_2_1_ya]" id="31_2_1_ya"  onclick="checkthis('31_2_1_ya')">
        <span class="lbl" > &nbsp; Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_2_1_no]" id="31_2_1_no"  onclick="checkthis('31_2_1_no')">
        <span class="lbl" > &nbsp; Tidak</span>
      </label>
    </td>
    <td>
      Penjelasan <input class="input_type" type="text" style="width: 90%" name="form_31[31_2_1_description]" id="31_2_1_description" onchange="fillthis('31_2_1_description')"> 
    </td>
  </tr>
  <tr>
    <td valign="top">3</td>
    <td valign="top" colspan="3">
      Bantuan diperlukan dalam hal<br>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_3_1_makan]" id="31_3_1_makan"  onclick="checkthis('31_3_1_makan')">
        <span class="lbl" > &nbsp; Makan</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_3_1_siap_makan]" id="31_3_1_siap_makan"  onclick="checkthis('31_3_1_siap_makan')">
        <span class="lbl" > &nbsp; Menyiapkan Makanan</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_3_1_mnm_obt]" id="31_3_1_mnm_obt"  onclick="checkthis('31_3_1_mnm_obt')">
        <span class="lbl" > &nbsp; Minum Obat</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_3_1_diet]" id="31_3_1_diet"  onclick="checkthis('31_3_1_diet')">
        <span class="lbl" > &nbsp; Diet</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_3_1_mandi]" id="31_3_1_mandi"  onclick="checkthis('31_3_1_mandi')">
        <span class="lbl" > &nbsp; Mandi</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_3_1_berpakaian]" id="31_3_1_berpakaian"  onclick="checkthis('31_3_1_berpakaian')">
        <span class="lbl" > &nbsp; Berpakaian</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_3_1_transport]" id="31_3_1_transport"  onclick="checkthis('31_3_1_transport')">
        <span class="lbl" > &nbsp; Transportasi</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_3_1_edukes]" id="31_3_1_edukes"  onclick="checkthis('31_3_1_edukes')">
        <span class="lbl" > &nbsp; Edukasi Kesehatan</span>
      </label>
    </td>
  </tr>
  <tr>
    <td valign="top">4</td>
    <td valign="top">Adakah yang membantu keperluan tersebut diatas ?</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_4_1_ya]" id="31_4_1_ya"  onclick="checkthis('31_4_1_ya')">
        <span class="lbl" > &nbsp; Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_4_1_no]" id="31_4_1_no"  onclick="checkthis('31_4_1_no')">
        <span class="lbl" > &nbsp; Tidak</span>
      </label>
    </td>
    <td>
      Penjelasan <input class="input_type" type="text" style="width: 90%" name="form_31[31_4_1_description]" id="31_4_1_description" onchange="fillthis('31_4_1_description')"> 
    </td>
  </tr>
  <tr>
    <td valign="top">4</td>
    <td valign="top">Adakah yang membantu keperluan tersebut diatas ?</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_4_1_ya]" id="31_4_1_ya"  onclick="checkthis('31_4_1_ya')">
        <span class="lbl" > &nbsp; Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_4_1_no]" id="31_4_1_no"  onclick="checkthis('31_4_1_no')">
        <span class="lbl" > &nbsp; Tidak</span>
      </label>
    </td>
    <td>
      Penjelasan <input class="input_type" type="text" style="width: 90%" name="form_31[31_4_1_description]" id="31_4_1_description" onchange="fillthis('31_4_1_description')"> 
    </td>
  </tr>
  <tr>
    <td valign="top">5</td>
    <td valign="top">Adakah pasien hidup / tinggal sendiri setelah keluar dari rumah sakit?</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_5_1_ya]" id="31_5_1_ya"  onclick="checkthis('31_5_1_ya')">
        <span class="lbl" > &nbsp; Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_5_1_no]" id="31_5_1_no"  onclick="checkthis('31_5_1_no')">
        <span class="lbl" > &nbsp; Tidak</span>
      </label>
    </td>
    <td>
      Penjelasan <input class="input_type" type="text" style="width: 90%" name="form_31[31_5_1_description]" id="31_5_1_description" onchange="fillthis('31_5_1_description')"> 
    </td>
  </tr>
  <tr>
    <td valign="top">6</td>
    <td valign="top">Apakah pasien menggunakan peralatan medis dirumah setelah keluar dari rumah sakit (cateter, NGT, double lumen, oksigen) ?</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_6_1_ya]" id="31_6_1_ya"  onclick="checkthis('31_6_1_ya')">
        <span class="lbl" > &nbsp; Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_6_1_no]" id="31_6_1_no"  onclick="checkthis('31_6_1_no')">
        <span class="lbl" > &nbsp; Tidak</span>
      </label>
    </td>
    <td>
      Penjelasan <input class="input_type" type="text" style="width: 90%" name="form_31[31_6_1_description]" id="31_6_1_description" onchange="fillthis('31_6_1_description')"> 
    </td>
  </tr>
  <tr>
    <td valign="top">7</td>
    <td valign="top">Apakah pasien memerlukan alat bantu setelah keluar dari rumah sakit (tongkat, kursi roda, walker) ?</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_7_1_ya]" id="31_7_1_ya"  onclick="checkthis('31_7_1_ya')">
        <span class="lbl" > &nbsp; Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_7_1_no]" id="31_7_1_no"  onclick="checkthis('31_7_1_no')">
        <span class="lbl" > &nbsp; Tidak</span>
      </label>
    </td>
    <td>
      Penjelasan <input class="input_type" type="text" style="width: 90%" name="form_31[31_7_1_description]" id="31_7_1_description" onchange="fillthis('31_7_1_description')"> 
    </td>
  </tr>
  <tr>
    <td valign="top">8</td>
    <td valign="top">Apakah memerlukan bantuan / perawatan khusus di rumah setelah keluar dari rumah sakit (homecare, home visit) ?</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_8_1_ya]" id="31_8_1_ya"  onclick="checkthis('31_8_1_ya')">
        <span class="lbl" > &nbsp; Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_8_1_no]" id="31_8_1_no"  onclick="checkthis('31_8_1_no')">
        <span class="lbl" > &nbsp; Tidak</span>
      </label>
    </td>
    <td>
      Penjelasan <input class="input_type" type="text" style="width: 90%" name="form_31[31_8_1_description]" id="31_8_1_description" onchange="fillthis('31_8_1_description')"> 
    </td>
  </tr>
  <tr>
    <td valign="top">9</td>
    <td valign="top">Apakah pasien bermasalah dalam memenuhi kebutuhan pribadinya setelah keluar dari rumah sakit (makan, minum toiletting, dll) ?</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_9_1_ya]" id="31_9_1_ya"  onclick="checkthis('31_9_1_ya')">
        <span class="lbl" > &nbsp; Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_9_1_no]" id="31_9_1_no"  onclick="checkthis('31_9_1_no')">
        <span class="lbl" > &nbsp; Tidak</span>
      </label>
    </td>
    <td>
      Penjelasan <input class="input_type" type="text" style="width: 90%" name="form_31[31_9_1_description]" id="31_9_1_description" onchange="fillthis('31_9_1_description')"> 
    </td>
  </tr>
  <tr>
    <td valign="top">10</td>
    <td valign="top">Apakah pasien memiliki nyeri kronis dan kelelahan setelah keluar dari rumah sakit ?</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_10_1_ya]" id="31_10_1_ya"  onclick="checkthis('31_10_1_ya')">
        <span class="lbl" > &nbsp; Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_10_1_no]" id="31_10_1_no"  onclick="checkthis('31_10_1_no')">
        <span class="lbl" > &nbsp; Tidak</span>
      </label>
    </td>
    <td>
      Penjelasan <input class="input_type" type="text" style="width: 90%" name="form_31[31_10_1_description]" id="31_10_1_description" onchange="fillthis('31_10_1_description')"> 
    </td>
  </tr>
  <tr>
    <td valign="top">11</td>
    <td valign="top">Aoakah pasien dan keluarga memerlukan edukasi kesehatan setelah keluar dari rumah sakit (obat-obatan, nyeri, diet, mencari pertolongan, followup, dll) ?</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_11_1_ya]" id="31_11_1_ya"  onclick="checkthis('31_11_1_ya')">
        <span class="lbl" > &nbsp; Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_11_1_no]" id="31_11_1_no"  onclick="checkthis('31_11_1_no')">
        <span class="lbl" > &nbsp; Tidak</span>
      </label>
    </td>
    <td>
      Penjelasan <input class="input_type" type="text" style="width: 90%" name="form_31[31_11_1_description]" id="31_11_1_description" onchange="fillthis('31_11_1_description')"> 
    </td>
  </tr>
  <tr>
    <td valign="top">12</td>
    <td valign="top">Apakah pasien dan keluarga memerlukan keterampilan khusu setelah keluar dari rumah sakit (perawatan luka, injeksi, perawatan bayi, dll) ?</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_12_1_ya]" id="31_12_1_ya"  onclick="checkthis('31_12_1_ya')">
        <span class="lbl" > &nbsp; Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_31[31_12_1_no]" id="31_12_1_no"  onclick="checkthis('31_12_1_no')">
        <span class="lbl" > &nbsp; Tidak</span>
      </label>
    </td>
    <td>
      Penjelasan <input class="input_type" type="text" style="width: 90%" name="form_31[31_12_1_description]" id="31_12_1_description" onchange="fillthis('31_12_1_description')"> 
    </td>
  </tr>
</table>
<br>
<span style="font-weight: bold; font-size: 14px">PERSIAPAN RENCANA PEMULANGAN PASIEN</span>
<table class="table">
  <tr>
    <td valign="top">Tanggal Rencana Pemulangan</td>
    <td valign="top"><input class="input_type datepicker" type="text" style="width: 100px" name="form_31[31_tgl_rencana_plg]" id="31_tgl_rencana_plg" onchange="fillthis('31_tgl_rencana_plg')"> </td>
    <td valign="top">Estimasi Tanggal Pemulangan</td>
    <td valign="top"><input class="input_type datepicker" type="text" style="width: 100px" name="form_31[31_est_tgl_rencana_plg]" id="31_est_tgl_rencana_plg" onchange="fillthis('31_est_tgl_rencana_plg')"> </td>
  </tr>
  <tr>
    <td align="center">A</td>
    <td>Edukasi Selama Perawatan</td>
    <td>Petugas</td>
    <td>PJ Pasien</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_31[31_irj]" id="31_irj"  onclick="checkthis('31_irj')">
          <span class="lbl" > &nbsp; Edukasi mengenai DPJO, Rencana Medis, Hasil Pemeriksaan Penunjang, Terapi, Rencana Pemulangan Pasien (dilakukan oleh dokter)</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_31[31_irj]" id="31_irj"  onclick="checkthis('31_irj')">
          <span class="lbl" > &nbsp; Edukasi mengenai diet, pola makan, pembatasan makanan, persiapan dan pemberian makan (dilakukan oleh ahli gizi)</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_31[31_irj]" id="31_irj"  onclick="checkthis('31_irj')">
          <span class="lbl" > &nbsp; Edukasi Fisioterapi (dilakukan oleh fisioterapis)</span>
        </label>
      </div>
    </td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<br>
