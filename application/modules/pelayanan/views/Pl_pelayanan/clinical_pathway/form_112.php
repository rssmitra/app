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
      var hiddenInputName = 'form_112[ttd_' + role + ']';
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

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<!-- <p>edited by amelia yahya 14 oktober 2025</p> -->
<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN: RISIKO PENURUNAN CURAH JANTUNG</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
    <thead>
        <tr>
            <td style="border: 1px solid black; padding: 5px;" colspan="2">
                Definisi : Berisiko mengalami pemompaan jantung yang tidak adekuat untuk memenuhi kebutuhan metabolisme tubuh.
            </td>
        </tr>
    </thead>
    <tbody>
        <!-- FAKTOR RISIKO -->
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
                <b>FAKTOR RISIKO (Dibuktikan dengan):</b><br>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[penyebab][]" id="penyebab_perubahan_afterload" onclick="checkthis('penyebab_perubahan_afterload')" value="Perubahan afterload">
                        <span class="lbl"> Perubahan afterload</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[penyebab][]" id="penyebab_perubahan_frekuensi_jantung" onclick="checkthis('penyebab_perubahan_frekuensi_jantung')" value="Perubahan frekuensi jantung">
                        <span class="lbl"> Perubahan frekuensi jantung</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[penyebab][]" id="penyebab_perubahan_irama_jantung" onclick="checkthis('penyebab_perubahan_irama_jantung')" value="Perubahan irama jantung">
                        <span class="lbl"> Perubahan irama jantung</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[penyebab][]" id="penyebab_perubahan_kontraktilitas" onclick="checkthis('penyebab_perubahan_kontraktilitas')" value="Perubahan kontraktilitas">
                        <span class="lbl"> Perubahan kontraktilitas</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[penyebab][]" id="penyebab_perubahan_preload" onclick="checkthis('penyebab_perubahan_preload')" value="Perubahan preload">
                        <span class="lbl"> Perubahan preload</span>
                    </label>
                </div>
            </td>
        </tr>

        <!-- KRITERIA HASIL -->
        <tr>
            <td style="border: 1px solid black; padding: 5px;" colspan="2">
                <b>Setelah dilakukan intervensi selama 
                    <input type="text" class="input_type" name="form_112[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
                    maka Curah Jantung (L.02008) meningkat dengan kriteria hasil:</b><br>

                <div style="display: flex; flex-wrap: wrap;">
                    <div style="width: 50%;">
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_kekuatan_nadi" onclick="checkthis('hasil_kekuatan_nadi')" value="Kekuatan nadi perifer meningkat"><span class="lbl"> Kekuatan nadi perifer meningkat *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_ef" onclick="checkthis('hasil_ef')" value="Ejection fraction (EF) meningkat"><span class="lbl"> Ejection fraction (EF) meningkat *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_ci" onclick="checkthis('hasil_ci')" value="Cardiac index (CI) meningkat"><span class="lbl"> Cardiac index (CI) meningkat</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_lvswi" onclick="checkthis('hasil_lvswi')" value="Left ventricular stroke work index (LVSWI) meningkat"><span class="lbl"> Left ventricular stroke work index (LVSWI) meningkat</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_svi" onclick="checkthis('hasil_svi')" value="Stroke volume index (SVI) meningkat"><span class="lbl"> Stroke volume index (SVI) meningkat</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_palpitasi" onclick="checkthis('hasil_palpitasi')" value="Palpitasi menurun"><span class="lbl"> Palpitasi menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_bradikardia" onclick="checkthis('hasil_bradikardia')" value="Bradikardia menurun"><span class="lbl"> Bradikardia menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_takikardia" onclick="checkthis('hasil_takikardia')" value="Takikardia menurun"><span class="lbl"> Takikardia menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_ekg" onclick="checkthis('hasil_ekg')" value="Gambaran EKG aritmia menurun"><span class="lbl"> Gambaran EKG aritmia menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_lelah" onclick="checkthis('hasil_lelah')" value="Lelah menurun"><span class="lbl"> Lelah menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_edema" onclick="checkthis('hasil_edema')" value="Edema menurun"><span class="lbl"> Edema menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_distensi" onclick="checkthis('hasil_distensi')" value="Distensi vena jugularis menurun"><span class="lbl"> Distensi vena jugularis menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_dispnea" onclick="checkthis('hasil_dispnea')" value="Dispnea menurun"><span class="lbl"> Dispnea menurun *</span></label></div>
                    </div>
                    <div style="width: 50%;">
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_oliguria" onclick="checkthis('hasil_oliguria')" value="Oliguria menurun"><span class="lbl"> Oliguria menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_pucat" onclick="checkthis('hasil_pucat')" value="Pucat/sianosis menurun"><span class="lbl"> Pucat/sianosis menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_pnd" onclick="checkthis('hasil_pnd')" value="Paroxysmal nocturnal dyspnea menurun"><span class="lbl"> Paroxysmal nocturnal dyspnea (PND) menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_ortopnea" onclick="checkthis('hasil_ortopnea')" value="Ortopnea menurun"><span class="lbl"> Ortopnea menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_batuk" onclick="checkthis('hasil_batuk')" value="Batuk menurun"><span class="lbl"> Batuk menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_s3" onclick="checkthis('hasil_s3')" value="Suara jantung S3 menurun"><span class="lbl"> Suara jantung S3 menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_s4" onclick="checkthis('hasil_s4')" value="Suara jantung S4 menurun"><span class="lbl"> Suara jantung S4 menurun *</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_murmur" onclick="checkthis('hasil_murmur')" value="Murmur jantung menurun"><span class="lbl"> Murmur jantung menurun</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_bb" onclick="checkthis('hasil_bb')" value="Berat badan menurun"><span class="lbl"> Berat badan menurun</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_hepatomegali" onclick="checkthis('hasil_hepatomegali')" value="Hepatomegali menurun"><span class="lbl"> Hepatomegali menurun</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_pvr" onclick="checkthis('hasil_pvr')" value="Pulmonary vascular resistance (PVR) menurun"><span class="lbl"> Pulmonary vascular resistance (PVR) menurun</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_svr" onclick="checkthis('hasil_svr')" value="Systemic vascular resistance menurun"><span class="lbl"> Systemic vascular resistance menurun</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_crt" onclick="checkthis('hasil_crt')" value="Capillary refill time (CRT) membaik"><span class="lbl"> Capillary refill time (CRT) membaik</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_pawp" onclick="checkthis('hasil_pawp')" value="Pulmonary artery wedge pressure (PAWP) membaik"><span class="lbl"> Pulmonary artery wedge pressure (PAWP) membaik</span></label></div>
                        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_112[kriteria_hasil][]" id="hasil_cvp" onclick="checkthis('hasil_cvp')" value="Central venous pressure (CVP) membaik"><span class="lbl"> Central venous pressure (CVP) membaik</span></label></div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<!---- END --->


<br>
<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
    <thead>
        <tr style="background-color: #d3d3d3;">
            <th style="width: 5%; border: 1px solid black; padding: 5px; text-align:center;">NO.</th>
            <th style="width: 95%; border: 1px solid black; padding: 5px; text-align:center;">INTERVENSI KEPERAWATAN UTAMA</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                <b>Perawatan Jantung</b> <i>(Mengidentifikasi, merawat dan membatasi komplikasi akibat ketidakseimbangan antara suplai dan konsumsi oksigen miocard)</i> I.02075
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align:center;"><b>1</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <div style="margin-top: 5px;"><b>Observasi</b></div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_gejala_primer" onclick="checkthis('pj_observasi_gejala_primer')" value="Identifikasi tanda/gejala primer penurunan curah jantung">
                        <span class="lbl"> Identifikasi tanda/gejala primer penurunan curah jantung (dispnea, kelelahan, edema, ortopnea, Paroxysmal nocturnal dypsnea, peningkatan CVP)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_gejala_sekunder" onclick="checkthis('pj_observasi_gejala_sekunder')" value="Identifikasi tanda/gejala sekunder penurunan curah jantung">
                        <span class="lbl"> Identifikasi tanda/gejala sekunder penurunan curah jantung (peningkatan berat badan, hepatomegaly, distensi vena jugularis, palpitasi, ronkhi basah, oliguria, batuk, kulit pucat)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_tekanan_darah" onclick="checkthis('pj_observasi_tekanan_darah')" value="Monitor tekanan darah">
                        <span class="lbl"> Monitor tekanan darah</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_intake_output" onclick="checkthis('pj_observasi_intake_output')" value="Monitor intake dan output cairan">
                        <span class="lbl"> Monitor intake dan output cairan</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_berat_badan" onclick="checkthis('pj_observasi_berat_badan')" value="Monitor berat badan tiap hari">
                        <span class="lbl"> Monitor berat badan tiap hari</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_saturasi_o2" onclick="checkthis('pj_observasi_saturasi_o2')" value="Monitor saturasi 02">
                        <span class="lbl"> Monitor saturasi 02</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_nyeri_dada" onclick="checkthis('pj_observasi_nyeri_dada')" value="Monitor keluhan nyeri dada">
                        <span class="lbl"> Monitor keluhan nyeri dada</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_ekg_12_lead" onclick="checkthis('pj_observasi_ekg_12_lead')" value="Monitor EKG 12 Lead">
                        <span class="lbl"> Monitor EKG 12 Lead</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_aritmia" onclick="checkthis('pj_observasi_aritmia')" value="Monitor aritmia (kelainan irama dan frekuensi)">
                        <span class="lbl"> Monitor aritmia (kelainan irama dan frekuensi)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_laboratorium" onclick="checkthis('pj_observasi_laboratorium')" value="Monitor nilai laboratorium jantung (elektrolit enzim jantung)">
                        <span class="lbl"> Monitor nilai laboratorium jantung (elektrolit enzim jantung)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_alat_pacu_jantung" onclick="checkthis('pj_observasi_alat_pacu_jantung')" value="Monitor fungsi alat pacu jantung">
                        <span class="lbl"> Monitor fungsi alat pacu jantung</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_td_nadi_aktivitas" onclick="checkthis('pj_observasi_td_nadi_aktivitas')" value="Periksa tekanan darah dan frekuensi nadi sebelum dan sesudah aktivitas">
                        <span class="lbl"> Periksa tekanan darah dan frekuensi nadi sebelum dan sesudah aktivitas</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][observasi][]" id="pj_observasi_td_nadi_obat" onclick="checkthis('pj_observasi_td_nadi_obat')" value="Periksa tekanan darah dan frekuensi nadi sebelum pemberian obat">
                        <span class="lbl"> Periksa tekanan darah dan frekuensi nadi sebelum pemberian obat (Mis. Betabloker, digoksin dll)</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                <input type="text" class="input_type" name="form_112[ket_tambahan_perawatan_jantung_observasi]" id="ket_tambahan_perawatan_jantung_observasi" onchange="fillthis('ket_tambahan_perawatan_jantung_observasi')" style="width:100%;"> 
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align:center;"><b>2</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Terapeutik</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][terapeutik][]" id="pj_terapeutik_posisi" onclick="checkthis('pj_terapeutik_posisi')" value="Posisikan pasien semi Fowler atau Fowler dengan kaki kebawah atau posisi nyaman">
                        <span class="lbl"> Posisikan pasien semi Fowler atau Fowler dengan kaki kebawah atau posisi nyaman</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][terapeutik][]" id="pj_terapeutik_diet" onclick="checkthis('pj_terapeutik_diet')" value="Berikan diet jantung yang sesuai">
                        <span class="lbl"> Berikan diet jantung yang sesuai</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][terapeutik][]" id="pj_terapeutik_modifikasi_gaya_hidup" onclick="checkthis('pj_terapeutik_modifikasi_gaya_hidup')" value="Fasilitasi pasien dan keluarga untuk modifikasi gaya hidup sehat">
                        <span class="lbl"> Fasilitasi pasien dan keluarga untuk modifikasi gaya hidup sehat</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][terapeutik][]" id="pj_terapeutik_relaksasi" onclick="checkthis('pj_terapeutik_relaksasi')" value="Berikan terapi relaksasi untuk mengurangi stres">
                        <span class="lbl"> Berikan terapi relaksasi untuk mengurangi stres</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][terapeutik][]" id="pj_terapeutik_dukungan" onclick="checkthis('pj_terapeutik_dukungan')" value="Berikan dukungan emosional dan spritual">
                        <span class="lbl"> Berikan dukungan emosional dan spritual</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][terapeutik][]" id="pj_terapeutik_oksigen" onclick="checkthis('pj_terapeutik_oksigen')" value="Berikan oksigen untuk mempertahankan saturasi oksigen > 94%">
                        <span class="lbl"> Berikan oksigen untuk mempertahankan saturasi 02≤ 94%</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                     <input type="text" class="input_type" name="form_112[ket_tambahan_perawatan_jantung_terapeutik]" id="ket_tambahan_perawatan_jantung_terapeutik" onchange="fillthis('ket_tambahan_perawatan_jantung_terapeutik')" style="width:100%;"> 
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align:center;"><b>3</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Edukasi</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][edukasi][]" id="pj_edukasi_aktivitas_fisik" onclick="checkthis('pj_edukasi_aktivitas_fisik')" value="Anjurkan beraktivitas fisik sesuai toleransi">
                        <span class="lbl"> Anjurkan beraktivitas fisik sesuai toleransi</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][edukasi][]" id="pj_edukasi_aktivitas_bertahap" onclick="checkthis('pj_edukasi_aktivitas_bertahap')" value="Anjurkan beraktivitas fisik secara bertahap">
                        <span class="lbl"> Anjurkan beraktivitas fisik secara bertahap</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][edukasi][]" id="pj_edukasi_berhenti_merokok" onclick="checkthis('pj_edukasi_berhenti_merokok')" value="Anjurkan berhenti merokok">
                        <span class="lbl"> Anjurkan berhenti merokok</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][edukasi][]" id="pj_edukasi_ukur_bb" onclick="checkthis('pj_edukasi_ukur_bb')" value="Anjurkan pasien mengukur berat badan harian">
                        <span class="lbl"> Anjurkan pasien mengukur berat badan harian</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][edukasi][]" id="pj_edukasi_ukur_intake_output" onclick="checkthis('pj_edukasi_ukur_intake_output')" value="Anjurkan pasien dan keluarga mengukur Intake dan output cairan harian">
                        <span class="lbl"> Anjurkan pasien dan keluarga mengukur Intake dan output cairan harian</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_112[ket_tambahan_perawatan_jantung_edukasi]" id="ket_tambahan_perawatan_jantung_edukasi" onchange="fillthis('ket_tambahan_perawatan_jantung_edukasi')" style="width:100%;"> 
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align:center;"><b>4</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Kolaborasi</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][kolaborasi][]" id="pj_kolaborasi_anti_aritmia" onclick="checkthis('pj_kolaborasi_anti_aritmia')" value="Kolaborasi pemberian anti aritmia jika perlu">
                        <span class="lbl"> Kolaborasi pemberian anti aritmia jika perlu</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_112[perawatan_jantung][kolaborasi][]" id="pj_kolaborasi_rehabilitas" onclick="checkthis('pj_kolaborasi_rehabilitas')" value="Rusuk ke program rehabilitas jantung">
                        <span class="lbl"> Rusuk ke program rehabilitas jantung</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_112[ket_tambahan_perawatan_jantung_kolaborasi]" id="ket_tambahan_perawatan_jantung_kolaborasi" onchange="fillthis('ket_tambahan_perawatan_jantung_kolaborasi')" style="width:100%;">
                </div>
            </td>
        </tr>
        
    </tbody>
</table>

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
        <input type="text" class="input_type" name="form_112[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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