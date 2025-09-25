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
  
  var ttdCanvas = null, ttdCtx = null, drawing = false, lastPos = {x:0, y:0};
  var currentTtdTarget = null;
  
  function getPos(canvas, evt) {
    var rect = canvas.getBoundingClientRect();
    if (evt.touches && evt.touches.length > 0) {
      return {
        x: evt.touches[0].clientX - rect.left,
        y: evt.touches[0].clientY - rect.top
      };
    } else {
      return {
        x: evt.clientX - rect.left,
        y: evt.clientY - rect.top
      };
    }
  }

  function initTtdCanvas() {
    ttdCanvas = document.getElementById('ttd-canvas');
    ttdCtx = ttdCanvas.getContext('2d');
    ttdCtx.clearRect(0, 0, ttdCanvas.width, ttdCanvas.height);
    drawing = false;
    lastPos = {x:0, y:0};

    ttdCanvas.onmousedown = function(e) {
      drawing = true;
      lastPos = getPos(ttdCanvas, e);
    };
    ttdCanvas.onmouseup = function(e) {
      drawing = false;
    };
    ttdCanvas.onmousemove = function(e) {
      if (!drawing) return;
      var pos = getPos(ttdCanvas, e);
      ttdCtx.beginPath();
      ttdCtx.moveTo(lastPos.x, lastPos.y);
      ttdCtx.lineTo(pos.x, pos.y);
      ttdCtx.stroke();
      lastPos = pos;
    };
    // Touch events
    ttdCanvas.addEventListener('touchstart', function(e) {
      drawing = true;
      lastPos = getPos(ttdCanvas, e);
    });
    ttdCanvas.addEventListener('touchend', function(e) {
      drawing = false;
    });
    ttdCanvas.addEventListener('touchmove', function(e) {
      if (!drawing) return;
      var pos = getPos(ttdCanvas, e);
      ttdCtx.beginPath();
      ttdCtx.moveTo(lastPos.x, lastPos.y);
      ttdCtx.lineTo(pos.x, pos.y);
      ttdCtx.stroke();
      lastPos = pos;
      e.preventDefault();
    });
    // Clear button
    $('#clear-ttd').off('click').on('click', function() {
      ttdCtx.clearRect(0, 0, ttdCanvas.width, ttdCtx.height);
    });
  }

  // Open modal on click
  $('.ttd-btn').off('click').on('click', function() {
    currentTtdTarget = $(this);
    $('#ttdModal').modal('show');
    setTimeout(initTtdCanvas, 300);
  });

  // Save signature
  $('#save-ttd').off('click').on('click', function() {
    if (!ttdCanvas) return;
    var dataUrl = ttdCanvas.toDataURL('image/png');
    if (currentTtdTarget) {
      var role = currentTtdTarget.data('role');
      var imgId = '#img_ttd_' + role;
      $(imgId).attr('src', dataUrl).show();
      // Tambahkan input hidden untuk menyimpan data URL
      var hiddenInputName = 'form_58[ttd_' + role + ']';
      if ($('input[name="' + hiddenInputName + '"]').length === 0) {
        $('<input>').attr({
          type: 'hidden',
          id: 'ttd_data_' + role,
          name: hiddenInputName,
          value: dataUrl
        }).appendTo('form');
      } else {
        $('input[name="' + hiddenInputName + '"]').val(dataUrl);
      }
    }
    $('#ttdModal').modal('hide');
  });
});
</script>

<?php echo $header; ?>
<hr>
<br>
<!-- <p>edited by amelia yahya 25 september 2025</p> -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<div style="text-align: center; font-size: 18px;">
    <b>DIAGNOSIS KEPERAWATAN</b><br>
    <b>RISIKO PERFUSI SEREBRAL TIDAK EFEKTIF</b>
</div>
<br>
<br>

<table width="100%" style="width: 100%; border-collapse: collapse; border: 1px solid black;">
    <thead>
     <tr>
        <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">Definisi : Berisiko mengalami penurunan sirkulasi darah ke otak
        </td>
     </tr>
    </thead>
<tbody> 
<tr>
    <td width="50%" valign="top" style="border: 1px solid black; padding: 5px;">
        <b>FAKTOR RISIKO (Dibuktikan dengan):</b><br><br>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_keabnormalan_masa_pt" onclick="checkthis('faktor_risiko_keabnormalan_masa_pt')" value="Keabnormalan masa PT/APTT">
                <span class="lbl"> Keabnormalan masa PT/APTT</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_gangguan_jantung" onclick="checkthis('faktor_risiko_gangguan_jantung')" value="Gangguan jantung">
                <span class="lbl"> Gangguan jantung</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_aterosklerosis_aorta" onclick="checkthis('faktor_risiko_aterosklerosis_aorta')" value="Aterosklerosis aorta">
                <span class="lbl"> Aterosklerosis aorta</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_diseksi_arteri" onclick="checkthis('faktor_risiko_diseksi_arteri')" value="Diseksi arteri">
                <span class="lbl"> Diseksi arteri</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_fibrilasi_atrium" onclick="checkthis('faktor_risiko_fibrilasi_atrium')" value="Fibrilasi atrium">
                <span class="lbl"> Fibrilasi atrium</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_tumor_otak" onclick="checkthis('faktor_risiko_tumor_otak')" value="Tumor otak">
                <span class="lbl"> Tumor otak</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_stenosis_karotis" onclick="checkthis('faktor_risiko_stenosis_karotis')" value="Stenosis karotis">
                <span class="lbl"> Stenosis karotis</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_miksoma_atrium" onclick="checkthis('faktor_risiko_miksoma_atrium')" value="Miksoma atrium">
                <span class="lbl"> Miksoma atrium</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_aneurisma_serebri" onclick="checkthis('faktor_risiko_aneurisma_serebri')" value="Aneurisma serebri">
                <span class="lbl"> Aneurisma serebri</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_koagulopati" onclick="checkthis('faktor_risiko_koagulopati')" value="Koagulopati">
                <span class="lbl"> Koagulopati (mis: anemia sel sabit)</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_dilatasi_kardiomiopati" onclick="checkthis('faktor_risiko_dilatasi_kardiomiopati')" value="Dilatasi Kardiomiopati">
                <span class="lbl"> Dilatasi Kardiomiopati</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_koagulasi_diseminata" onclick="checkthis('faktor_risiko_koagulasi_diseminata')" value="Koagulasi intravaskuler diseminata">
                <span class="lbl"> Koagulasi intravaskuler diseminata</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_embolisme" onclick="checkthis('faktor_risiko_embolisme')" value="Embolisme">
                <span class="lbl"> Embolisme</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_cedera_kepala" onclick="checkthis('faktor_risiko_cedera_kepala')" value="Cedera kepala">
                <span class="lbl"> Cedera kepala</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_hiperkolesteronemia" onclick="checkthis('faktor_risiko_hiperkolesteronemia')" value="Hiperkolesteronemia">
                <span class="lbl"> Hiperkolesteronemia</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_hipertensi" onclick="checkthis('faktor_risiko_hipertensi')" value="Hipertensi">
                <span class="lbl"> Hipertensi</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_endokarditis_infektif" onclick="checkthis('faktor_risiko_endokarditis_infektif')" value="Endokarditis Infektif">
                <span class="lbl"> Endokarditis Infektif</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_katup_prostetik" onclick="checkthis('faktor_risiko_katup_prostetik')" value="Katup prostetik mekanis">
                <span class="lbl"> Katup prostetik mekanis</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_stenosis_mitral" onclick="checkthis('faktor_risiko_stenosis_mitral')" value="Stenosis Mitral">
                <span class="lbl"> Stenosis Mitral</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_neoplasma_otak" onclick="checkthis('faktor_risiko_neoplasma_otak')" value="Neoplasma otak">
                <span class="lbl"> Neoplasma otak</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_infark_miokard" onclick="checkthis('faktor_risiko_infark_miokard')" value="Infark miokard akut">
                <span class="lbl"> Infark miokard akut</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_sindrom_sick_sinus" onclick="checkthis('faktor_risiko_sindrom_sick_sinus')" value="Sindrom sick sinus">
                <span class="lbl"> Sindrom sick sinus</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_penyalahgunaan_zat" onclick="checkthis('faktor_risiko_penyalahgunaan_zat')" value="Penyalahgunaan zat">
                <span class="lbl"> Penyalahgunaan zat</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_terapi_trombolitik" onclick="checkthis('faktor_risiko_terapi_trombolitik')" value="Terapi trombolitik">
                <span class="lbl"> Terapi trombolitik</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[faktor_risiko][]" id="faktor_risiko_efek_samping" onclick="checkthis('faktor_risiko_efek_samping')" value="Efek samping tindakan">
                <span class="lbl"> Efek samping tindakan (mis. Tidakan operasi bypass)</span>
            </label>
        </div>
    </td>
    <td width="50%" valign="top" style="border: 1px solid black; padding: 5px;">
        <b>Setelah dilakukan intervensi selama <input type="text" class="input_type" name="form_59[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;"> Perfusi serebral meningkat (L.02014), dengan kriteria hasil:</b><br><br>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_sakit_kepala_menurun" onclick="checkthis('kriteria_hasil_sakit_kepala_menurun')" value="Sakit kepala menurun">
                <span class="lbl"> Sakit kepala menurun</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_tingkat_kesadaran_meningkat" onclick="checkthis('kriteria_hasil_tingkat_kesadaran_meningkat')" value="Tingkat kesadaran meningkat">
                <span class="lbl"> Tingkat kesadaran meningkat</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_gelisah_menurun" onclick="checkthis('kriteria_hasil_gelisah_menurun')" value="Gelisah menurun">
                <span class="lbl"> Gelisah/agitasi menurun</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_kecemasan_menurun" onclick="checkthis('kriteria_hasil_kecemasan_menurun')" value="Kecemasan menurun">
                <span class="lbl"> Kecemasan menurun</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_td" onclick="checkthis('kriteria_hasil_td')" value="TD">
                <span class="lbl"> TD <input type="text" class="input_type" name="form_59[ket_kriteria_td]" id="ket_kriteria_td" onchange="fillthis('ket_kriteria_td')" style="width:10%;"> mmHg</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_map" onclick="checkthis('kriteria_hasil_map')" value="MAP">
                <span class="lbl"> MAP <input type="text" class="input_type" name="form_59[ket_kriteria_map]" id="ket_kriteria_map" onchange="fillthis('ket_kriteria_map')" style="width:10%;"> mmHg</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_frekuensi_nadi" onclick="checkthis('kriteria_hasil_frekuensi_nadi')" value="Frekwensi nadi">
                <span class="lbl"> Frekwensi nadi <input type="text" class="input_type" name="form_59[ket_kriteria_nadi]" id="ket_kriteria_nadi" onchange="fillthis('ket_kriteria_nadi')" style="width:10%;"> x/mnt</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_frekuensi_napas" onclick="checkthis('kriteria_hasil_frekuensi_napas')" value="Frekwensi napas">
                <span class="lbl"> Frekwensi napas <input type="text" class="input_type" name="form_59[ket_kriteria_nafas]" id="ket_kriteria_nafas" onchange="fillthis('ket_kriteria_nafas')" style="width:10%;">x/mnt</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_fungsi_kognitif" onclick="checkthis('kriteria_hasil_fungsi_kognitif')" value="Fungsi kognitif meningkat">
                <span class="lbl"> Fungsi kognitif meningkat</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_tekanan_intrakranial" onclick="checkthis('kriteria_hasil_tekanan_intrakranial')" value="Tekanan intra kranial membaik">
                <span class="lbl"> Tekanan Intra kranial membaik</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_kecemasan_menurun_2" onclick="checkthis('kriteria_hasil_kecemasan_menurun_2')" value="Kecemasan menurun 2">
                <span class="lbl"> Kecemasan menurun</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_demam_menurun" onclick="checkthis('kriteria_hasil_demam_menurun')" value="Demam menurun">
                <span class="lbl"> Demam menurun</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" class="ace" name="form_59[kriteria_hasil][]" id="kriteria_hasil_refleks_saraf" onclick="checkthis('kriteria_hasil_refleks_saraf')" value="Refleks saraf membaik">
                <span class="lbl"> Refleks saraf membaik</span>
            </label>
        </div>
    </td>
</tr>
</tbody>
</table>
<br>

<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
    <thead>
        <tr style="background-color: #d3d3d3;">
            <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
            <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                <b>Manajemen Peningkatan Tekanan Intrakranial</b> <i>(Mengidentifikasi & mengelola peningkatan tekanan dalam rongga kranial)</i> <b>(I.06194)</b>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                <b>TINDAKAN</b>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align: center;"><b>1</b></td>
            <td style="border: 1px solid black; padding: 5px;">
                <label><b>Observasi</b></label><br>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_observasi][]" id="observasi_penyebab_tik" onclick="checkthis('observasi_penyebab_tik')" value="Identifikasi penyebab peningkatan TIK">
                        <span class="lbl"> Identifikasi penyebab peningkatan TIK (mis: lesi, ggn metabolisme, edema serebral)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_observasi][]" id="observasi_tanda_tik" onclick="checkthis('observasi_tanda_tik')" value="Monitor tanda/gejala peningkatan TIK">
                        <span class="lbl"> Monitor tanda/gejala peningkatan TIK (mis: TD meningkat, bradicardia, pola nafas ireguler, kesadaran menurun)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_observasi][]" id="observasi_map" onclick="checkthis('observasi_map')" value="Monitor MAP">
                        <span class="lbl"> Monitor MAP<i>(Mean Arterial Pressure)</i></span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_observasi][]" id="observasi_pernafasan" onclick="checkthis('observasi_pernafasan')" value="Monitor status pernafasan">
                        <span class="lbl"> Monitor status pernafasan</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_observasi][]" id="observasi_intake_output" onclick="checkthis('observasi_intake_output')" value="Monitor intake output cairan">
                        <span class="lbl"> Monitor intake & output cairan</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_observasi][]" id="observasi_cairan_serebrospinalis" onclick="checkthis('observasi_cairan_serebrospinalis')" value="Monitor cairan serebrospinalis">
                        <span class="lbl"> Monitor cairan serebro-spinalis <i>(mis:warna, konsistensi)</i></span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                <input type="text" class="input_type" name="form_59[ket_tambahan_manajemen_observasi]" id="ket_tambahan_manajemen_observasi" onchange="fillthis('ket_tambahan_manajemen_observasi')" style="width:100%;"> 
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align: center;"><b>2</b></td>
            <td style="border: 1px solid black; padding: 5px;">
                <label><b>Terapeutik</b></label><br>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_terapeutik][]" id="terapeutik_stimulus" onclick="checkthis('terapeutik_stimulus')" value="Minimalkan stimulus">
                        <span class="lbl"> Minimalkan stimulus dengan menyediakan</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_terapeutik][]" id="terapeutik_posisi_semi_fowler" onclick="checkthis('terapeutik_posisi_semi_fowler')" value="Posisi semi fowler">
                        <span class="lbl"> Berikan posisi semi fowler</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_terapeutik][]" id="terapeutik_manuver_valsava" onclick="checkthis('terapeutik_manuver_valsava')" value="Hindari manuver valsava">
                        <span class="lbl"> Hindari manuver <i>Valsava</i></span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_terapeutik][]" id="terapeutik_cegah_kejang" onclick="checkthis('terapeutik_cegah_kejang')" value="Cegah kejang">
                        <span class="lbl"> Cegah terjadinya kejang berjalan</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_terapeutik][]" id="terapeutik_hindari_peep" onclick="checkthis('terapeutik_hindari_peep')" value="Hindari penggunaan PEEP">
                        <span class="lbl"> Hindari penggunaan PEEP</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_terapeutik][]" id="terapeutik_hindari_hipotonik" onclick="checkthis('terapeutik_hindari_hipotonik')" value="Hindari cairan hipotonik">
                        <span class="lbl"> Hindari pemberian cairan IV hipotonik</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_terapeutik][]" id="terapeutik_atur_ventilator" onclick="checkthis('terapeutik_atur_ventilator')" value="Atur ventilator">
                        <span class="lbl"> Atur ventilator agar PaCO2 optimal</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_terapeutik][]" id="terapeutik_suhu_tubuh" onclick="checkthis('terapeutik_suhu_tubuh')" value="Pertahankan suhu tubuh">
                        <span class="lbl"> Pertahankan suhu tubuh normal</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                     <input type="text" class="input_type" name="form_59[ket_tambahan_manajemen_terapeutik]" id="ket_tambahan_manajemen_terapeutik" onchange="fillthis('ket_tambahan_manajemen_terapeutik')" style="width:100%;"> 
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align: center;"><b>3</b></td>
            <td style="border: 1px solid black; padding: 5px;">
                <label><b>Edukasi</b></label><br>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_edukasi][]" id="edukasi_sedasi" onclick="checkthis('edukasi_sedasi')" value="Kolaborasi pemberian sedasi">
                        <span class="lbl"> Kolaborasi pemberian sedasi dan anti konvulsan</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_edukasi][]" id="edukasi_diuretik" onclick="checkthis('edukasi_diuretik')" value="Kolaborasi pemberian diuretik">
                        <span class="lbl"> Kolaborasi pemberian diuretik osmosis</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[intervensi_edukasi][]" id="edukasi_pencahar" onclick="checkthis('edukasi_pencahar')" value="Kolaborasi pemberian pencahar">
                        <span class="lbl"> Kolaborasi pemberian pencahar</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_59[ket_tambahan_manajemen_edukasi]" id="ket_tambahan_manajemen_edukasi" onchange="fillthis('ket_tambahan_manajemen_edukasi')" style="width:100%;"> 
                </div>
            </td>
        </tr>
    </tbody>
</table>

<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
    <tbody>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                <b>Pemantauan Tekanan Intrakranial </b> <i>(Mengumpulkan dan menganalisis data terkait regulasi tekanan di dalam ruang intrakranial) </i> <b>(I.06198)</b>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                <b>TINDAKAN</b>
            </td>
        </tr>
        <tr>
            <td style="width: 5%; border: 1px solid black; padding: 5px; vertical-align: top; text-align: center;"><b>1</b></td>
            <td style="border: 1px solid black; padding: 5px;">
                <label><b>Observasi</b></label><br>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_observasi][]" id="pemantauan_observasi_penyebab_tik" onclick="checkthis('pemantauan_observasi_penyebab_tik')" value="Identifikasi penyebab peningkatan TIK">
                        <span class="lbl"> Identifikasi penyebab peningkatan TIK</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_observasi][]" id="pemantauan_observasi_td" onclick="checkthis('pemantauan_observasi_td')" value="Monitor TD">
                        <span class="lbl"> Monitor TD</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_observasi][]" id="pemantauan_observasi_tekanan_nadi" onclick="checkthis('pemantauan_observasi_tekanan_nadi')" value="Monitor tekanan nadi">
                        <span class="lbl"> Monitor pelebaran tekanan nadi</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_observasi][]" id="pemantauan_observasi_frekuensi_jantung" onclick="checkthis('pemantauan_observasi_frekuensi_jantung')" value="Monitor frekwensi jantung">
                        <span class="lbl"> Monitor penurunan frekwensi jantung</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_observasi][]" id="pemantauan_observasi_pola_nafas" onclick="checkthis('pemantauan_observasi_pola_nafas')" value="Monitor pola nafas">
                        <span class="lbl"> Monitor pola nafas ireguler</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_observasi][]" id="pemantauan_observasi_kesadaran" onclick="checkthis('pemantauan_observasi_kesadaran')" value="Monitor kesadaran">
                        <span class="lbl"> Monitor penurunan tingkat kesadaran</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_observasi][]" id="pemantauan_observasi_pupil" onclick="checkthis('pemantauan_observasi_pupil')" value="Monitor pupil">
                        <span class="lbl"> Monitor respon pupil</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_observasi][]" id="pemantauan_observasi_kadar_co2" onclick="checkthis('pemantauan_observasi_kadar_co2')" value="Monitor kadar CO2">
                        <span class="lbl"> Monitor kadar CO2</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_observasi][]" id="pemantauan_observasi_stimulus" onclick="checkthis('pemantauan_observasi_stimulus')" value="Monitor stimulus lingkungan">
                        <span class="lbl"> Monitor efek stimulus lingkungan terhadap TIK</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_59[ket_tambahan_mengumpulkan_observasi]" id="ket_tambahan_mengumpulkan_observasi" onchange="fillthis('ket_tambahan_mengumpulkan_observasi')" style="width:100%;">
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align: center;"><b>2</b></td>
            <td style="border: 1px solid black; padding: 5px;">
                <label><b>Terapeutik</b></label><br>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_terapeutik][]" id="pemantauan_terapeutik_ambil_sample" onclick="checkthis('pemantauan_terapeutik_ambil_sample')" value="Ambil sample cairan">
                        <span class="lbl"> Ambil sample cairan cerebrospinal</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_terapeutik][]" id="pemantauan_terapeutik_posisi_kepala" onclick="checkthis('pemantauan_terapeutik_posisi_kepala')" value="Posisi kepala netral">
                        <span class="lbl"> Pertahankan posisi kepala dan leher netral</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_terapeutik][]" id="pemantauan_terapeutik_dokumentasi" onclick="checkthis('pemantauan_terapeutik_dokumentasi')" value="Dokumentasi">
                        <span class="lbl"> Dokumentasikan hasil pemantauan</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_59[ket_tambahan_mengumpulkan_terapeutik]" id="ket_tambahan_mengumpulkan_terapeutik" onchange="fillthis('ket_tambahan_mengumpulkan_terapeutik')" style="width:100%;">
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align: center;"><b>3</b></td>
            <td style="border: 1px solid black; padding: 5px;">
                <label><b>Edukasi</b></label><br>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_edukasi][]" id="pemantauan_edukasi_tujuan" onclick="checkthis('pemantauan_edukasi_tujuan')" value="Jelaskan tujuan">
                        <span class="lbl"> Jelaskan tujuan dan prosedur pemantauan</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_59[pemantauan_edukasi][]" id="pemantauan_edukasi_informasi_hasil" onclick="checkthis('pemantauan_edukasi_informasi_hasil')" value="Informasikan hasil">
                        <span class="lbl"> Informasikan hasil pemantauan</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_59[ket_tambahan_mengumpulkan_edukasi]" id="ket_tambahan_mengumpulkan_edukasi" onchange="fillthis('ket_tambahan_mengumpulkan_edukasi')" style="width:100%;">
                </div>
            </td>
        </tr>
    </tbody>
</table>

<!-- ----- -->
<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <td style="width:33%; text-align:center;">
        Nama/Paraf
        <br><br>
        <span class="ttd-btn" data-role="petugas" id="ttd_petugas" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_petugas" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_59[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
      </td>

      <td colspan="2">
      </td>
    </tr>
  </tbody>
</table>
</div>

<div class="modal fade" id="ttdModal" tabindex="-1" role="dialog" aria-labelledby="ttdModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="ttdModalLabel" style="color: white">Tanda Tangan Digital</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" style="text-align:center;">
        <canvas id="ttd-canvas" style="border:1px solid #ccc;touch-action:none;" width="350" height="120"></canvas>
        <br>
        <button type="button" class="btn btn-warning btn-sm" id="clear-ttd">Bersihkan</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-xs btn-primary" id="save-ttd">Simpan</button>
      </div>
    </div>
  </div>
</div>