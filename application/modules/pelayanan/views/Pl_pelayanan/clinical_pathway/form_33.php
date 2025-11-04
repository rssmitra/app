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

  $('#dignosis_kerja').typeahead({
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
        $('#dignosis_kerja').val(label_item);
      }

  });

  $('#dignosis_banding').typeahead({
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
        $('#dignosis_banding').val(label_item);
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

<div style="text-align: center; font-size: 18px;"><b>RESUME MEDIS PASIEN</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<div style="border: 0px solid black; padding: 5px; margin-bottom: 10px;">
  <b>1. ANAMNESIS</b><br>
  <div contenteditable="true"
       class="input_type"
       name="form_33[keluhan_utama]"
       id="keluhan_utama"
       onchange="fillthis('keluhan_utama')"
       style="width: 100%; min-height: 40px; border: 1px solid #ccc; padding: 5px;">
    <?php echo isset($value_form['keluhan_utama']) ? nl2br($value_form['keluhan_utama']) : '' ?>
  </div>
</div>
<br>
<div style="border: 0px solid black; padding: 5px; margin-bottom: 10px;">
  <b>2. PEMERIKSAAN FISIK</b><br>
  <div contenteditable="true"
       class="input_type"
       name="form_33[pemeriksaan_fisik]"
       id="pemeriksaan_fisik"
       onchange="fillthis('pemeriksaan_fisik')"
       style="width: 100%; min-height: 40px; border: 1px solid #ccc; padding: 5px;">
    <?php echo isset($value_form['pemeriksaan_fisik']) ? nl2br($value_form['pemeriksaan_fisik']) : '' ?>
  </div>
</div>
<br>
<span style="text-align: left; font-weight: bold">3. PEMERIKSAAN PENUNJANG</span><br>

<label>
  <input type="checkbox" class="ace" name="form_33[pp_lab]" id="pp_lab" onclick="checkthis('pp_lab')">
  <span class="lbl"> Laboratorium</span>
</label>
<div contenteditable="true"
     class="input_type"
     name="form_33[pemeriksaan_penunjang_lab]"
     id="pemeriksaan_penunjang_lab"
     onchange="fillthis('pemeriksaan_penunjang_lab')"
     style="width: 100%; min-height: 20px; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;"
     placeholder="Masukan resume hasil penunjang">
  <?php echo isset($value_form['pemeriksaan_penunjang_lab']) ? nl2br($value_form['pemeriksaan_penunjang_lab']) : '' ?>
</div>

<label>
  <input type="checkbox" class="ace" name="form_33[pp_rad]" id="pp_rad" onclick="checkthis('pp_rad')">
  <span class="lbl"> Radiologi</span>
</label>
<div contenteditable="true"
     class="input_type"
     name="form_33[pemeriksaan_penunjang_rad]"
     id="pemeriksaan_penunjang_rad"
     onchange="fillthis('pemeriksaan_penunjang_rad')"
     style="width: 100%; min-height: 20px; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;"
     placeholder="Masukan resume hasil penunjang">
  <?php echo isset($value_form['pemeriksaan_penunjang_rad']) ? nl2br($value_form['pemeriksaan_penunjang_rad']) : '' ?>
</div>

<label>
  <input type="checkbox" class="ace" name="form_33[pp_lainnya]" id="pp_lainnya" onclick="checkthis('pp_lainnya')">
  <span class="lbl"> Lain-Lain</span>
</label>
<div contenteditable="true"
     class="input_type"
     name="form_33[pemeriksaan_penunjang_lainnya]"
     id="pemeriksaan_penunjang_lainnya"
     onchange="fillthis('pemeriksaan_penunjang_lainnya')"
     style="width: 100%; min-height: 20px; border: 1px solid #ccc; padding: 5px;"
     placeholder="Masukan resume hasil penunjang lainnya">
  <?php echo isset($value_form['pemeriksaan_penunjang_lainnya']) ? nl2br($value_form['pemeriksaan_penunjang_lainnya']) : '' ?>
</div>
<br>
<p style="text-align: left; font-weight: bold">4. HASIL KONSULTASI</p>
<div contenteditable="true"
     class="input_type"
     name="form_33[hasil_konsultasi]"
     id="hasil_konsultasi"
     onchange="fillthis('hasil_konsultasi')"
     style="width: 100%; min-height: 60px; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;">
  <?php echo isset($value_form['hasil_konsultasi']) ? nl2br($value_form['hasil_konsultasi']) : '' ?>
</div>

<span style="text-align: left; font-weight: bold">5. DIAGNOSA UTAMA</span><br>
<div contenteditable="true"
     class="input_type"
     name="form_33[dignosis_kerja]"
     id="dignosis_kerja"
     onchange="fillthis('dignosis_kerja')"
     style="width: 100%; min-height: 20px; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;">
  <?php echo isset($value_form['dignosis_kerja']) ? nl2br($value_form['dignosis_kerja']) : '' ?>
</div>

<span style="text-align: left; font-weight: bold">6. DIAGNOSA SEKUNDER</span><br>
<div contenteditable="true"
     class="input_type"
     name="form_33[dignosis_banding]"
     id="dignosis_banding"
     onchange="fillthis('dignosis_banding')"
     style="width: 100%; min-height: 20px; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;">
  <?php echo isset($value_form['dignosis_banding']) ? nl2br($value_form['dignosis_banding']) : '' ?>
</div>

<span style="text-align: left; font-weight: bold">7. TINDAKAN / PROSEDUR</span><br>
<div contenteditable="true"
     class="input_type"
     name="form_33[tindakan_prosedur]"
     id="tindakan_prosedur"
     onchange="fillthis('tindakan_prosedur')"
     style="width: 100%; min-height: 40px; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;">
  <?php echo isset($value_form['tindakan_prosedur']) ? nl2br($value_form['tindakan_prosedur']) : '' ?>
</div>

<span style="text-align: left; font-weight: bold">8. ALERGI (Reaksi Obat)</span><br>
<div contenteditable="true"
     class="input_type"
     name="form_33[riwayat_alergi]"
     id="riwayat_alergi"
     onchange="fillthis('riwayat_alergi')"
     style="width: 100%; min-height: 40px; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;">
  <?php echo isset($value_form['riwayat_alergi']) ? nl2br($value_form['riwayat_alergi']) : '' ?>
</div>

<span style="text-align: left; font-weight: bold">9. DIET</span><br>
<div contenteditable="true"
     class="input_type"
     name="form_33[diet]"
     id="diet"
     onchange="fillthis('diet')"
     style="width: 100%; min-height: 20px; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;">
  <?php echo isset($value_form['diet']) ? nl2br($value_form['diet']) : '' ?>
</div>

<span style="text-align: left; font-weight: bold">10. INSTRUKSI / ANJURAN DAN EDUKASI (FOLLOW UP)</span><br>
<div contenteditable="true"
     class="input_type"
     name="form_33[instruksi_dokter]"
     id="instruksi_dokter"
     onchange="fillthis('instruksi_dokter')"
     style="width: 100%; min-height: 40px; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;">
  <?php echo isset($value_form['instruksi_dokter']) ? nl2br($value_form['instruksi_dokter']) : '' ?>
</div>

<span style="text-align: left; font-weight: bold">11. KONDISI WAKTU KELUAR</span><br>
<div class="checkbox">
  <label>
    <input type="checkbox" class="ace" name="form_33[pasca_plg_sembuh]" id="pasca_plg_sembuh" onclick="checkthis('pasca_plg_sembuh')">
    <span class="lbl"> Sembuh</span>
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" class="ace" name="form_33[pasca_plg_rujuk_rs]" id="pasca_plg_rujuk_rs" onclick="checkthis('pasca_plg_rujuk_rs')">
    <span class="lbl"> Pindah RS Lain</span>
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" class="ace" name="form_33[pasca_plg_meninggal]" id="pasca_plg_meninggal" onclick="checkthis('pasca_plg_meninggal')">
    <span class="lbl"> Meninggal</span>
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" class="ace" name="form_33[pasca_plg_lainnya]" id="pasca_plg_lainnya" onclick="checkthis('pasca_plg_lainnya')">
    <span class="lbl"> Lain-lain</span>
  </label>
</div>
<div contenteditable="true"
     class="input_type"
     name="form_33[txt_pasca_plg_lainnya]"
     id="txt_pasca_plg_lainnya"
     onchange="fillthis('txt_pasca_plg_lainnya')"
     style="width: 100%; min-height: 20px; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;">
  <?php echo isset($value_form['txt_pasca_plg_lainnya']) ? nl2br($value_form['txt_pasca_plg_lainnya']) : '' ?>
</div>

<span style="text-align: left; font-weight: bold">12. PENGOBATAN DILANJUTKAN</span><br>
<div class="checkbox">
  <label>
    <input type="checkbox" class="ace" name="form_33[next_poli]" id="next_poli" onclick="checkthis('next_poli')">
    <span class="lbl"> Poliklinik</span>
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" class="ace" name="form_33[next_rs_lain]" id="next_rs_lain" onclick="checkthis('next_rs_lain')">
    <span class="lbl"> Pindah RS Lain</span>
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" class="ace" name="form_33[next_puskes]" id="next_puskes" onclick="checkthis('next_puskes')">
    <span class="lbl"> Puskesmas</span>
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" class="ace" name="form_33[next_lainnya]" id="next_lainnya" onclick="checkthis('next_lainnya')">
    <span class="lbl"> Lain-lain</span>
  </label>
</div>
<div contenteditable="true"
     class="input_type"
     name="form_33[txt_next_lainnya]"
     id="txt_next_lainnya"
     onchange="fillthis('txt_next_lainnya')"
     style="width: 100%; min-height: 20px; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;">
  <?php echo isset($value_form['txt_next_lainnya']) ? nl2br($value_form['txt_next_lainnya']) : '' ?>
</div>

<span style="text-align: left; font-weight: bold">13. TANGGAL KONTROL POLIKLINIK</span><br>
Pasien dianjurkan untuk kontrol kembali ke Poliklinik tanggal, <input class="input_type date-picker" data-date-format="yyyy-mm-dd" type="text" style="width: 100px" name="form_33[33_tgl_kontrol_kembali]" id="33_tgl_kontrol_kembali" onchange="fillthis('33_tgl_kontrol_kembali')">
<br><br>

<span style="text-align: left; font-weight: bold">14. TERAPI PULANG</span><br>
<div contenteditable="true"
     class="input_type"
     name="form_33[terapi_pulang]"
     id="terapi_pulang"
     onchange="fillthis('terapi_pulang')"
     style="width: 100%; min-height: 40px; border: 1px solid #ccc; padding: 5px;">
  <?php echo isset($value_form['terapi_pulang']) ? nl2br($value_form['terapi_pulang']) : '' ?>
</div>



<br>
<hr>
<?php echo $footer; ?>