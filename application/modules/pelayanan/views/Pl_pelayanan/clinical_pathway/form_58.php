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

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN: PENURUNAN CURAH JANTUNG</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
    <thead>
     <tr>
        <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">Definisi : Ketidakadekuatan jantung memompa darah untuk memenuhi kebutuhan metabolisme tubuh.
        </td>
     </tr>
        <tr>
            <th style="border: 1px solid black; padding: 5px;">PENYEBAB/Berhubungan dengan:</th>
            <th style="border: 1px solid black; padding: 5px;">Setelah dilakukan intervensi selama <input type="text" class="input_type" name="form_58[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;"> maka Curah jantung meningkat (L. 02008), dengan kriteria hasil:</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[penyebab][]" id="penyebab_perubahan_irama_jantung" onclick="checkthis('penyebab_perubahan_irama_jantung')" value="Perubahan irama jantung">
                        <span class="lbl"> Perubahan irama jantung</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[penyebab][]" id="penyebab_perubahan_frekuensi_jantung" onclick="checkthis('penyebab_perubahan_frekuensi_jantung')" value="Perubahan frekuensi jantung">
                        <span class="lbl"> Perubahan frekuensi jantung</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[penyebab][]" id="penyebab_perubahan_kontraktilitas" onclick="checkthis('penyebab_perubahan_kontraktilitas')" value="Perubahan Kontraktilitas">
                        <span class="lbl"> Perubahan Kontraktilitas</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[penyebab][]" id="penyebab_perubahan_preload" onclick="checkthis('penyebab_perubahan_preload')" value="Perubahan preload">
                        <span class="lbl"> Perubahan preload</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[penyebab][]" id="penyebab_perubahan_afterload" onclick="checkthis('penyebab_perubahan_afterload')" value="Perubahan afterload">
                        <span class="lbl"> Perubahan afterload</span>
                    </label>
                </div>
            </td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <div style="display: flex; flex-wrap: wrap;">
                    <div style="width: 50%;">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_ejection_fraction" onclick="checkthis('kriteria_ejection_fraction')" value="Ejection fraction meningkat">
                                <span class="lbl"> Ejection fraction meningkat</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_palpitasi" onclick="checkthis('kriteria_palpitasi')" value="Palpitasi menurun">
                                <span class="lbl"> Palpitasi menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_bradikardia" onclick="checkthis('kriteria_bradikardia')" value="Bradikardia menurun">
                                <span class="lbl"> Bradikardia menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_takikardia" onclick="checkthis('kriteria_takikardia')" value="Takikardia menurun">
                                <span class="lbl"> Takikardia menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_gambaran_ekg_aritmia" onclick="checkthis('kriteria_gambaran_ekg_aritmia')" value="Gambaran EKG aritmia menurun">
                                <span class="lbl"> Gambaran EKG aritmia menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_lelah" onclick="checkthis('kriteria_lelah')" value="Lelah menurun">
                                <span class="lbl"> Lelah menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_edema" onclick="checkthis('kriteria_edema')" value="Edema menurun">
                                <span class="lbl"> Edema menurun</span>
                            </label>
                        </div>
                    </div>
                    <div style="width: 50%;">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_dispnea" onclick="checkthis('kriteria_dispnea')" value="Dispnea menurun">
                                <span class="lbl"> Dispnea menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_oliguria" onclick="checkthis('kriteria_oliguria')" value="Oliguria menurun">
                                <span class="lbl"> Oliguria menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_pucat_sianosis" onclick="checkthis('kriteria_pucat_sianosis')" value="Pucat/sianosis menurun">
                                <span class="lbl"> Pucat/sianosis menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_ortopnea" onclick="checkthis('kriteria_ortopnea')" value="Ortopnea menurun">
                                <span class="lbl"> Ortopnea menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_batuk" onclick="checkthis('kriteria_batuk')" value="Batuk menurun">
                                <span class="lbl"> Batuk menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[kriteria_hasil][]" id="kriteria_paroxysmal_nocturnal_dypnea" onclick="checkthis('kriteria_paroxysmal_nocturnal_dypnea')" value="Paroxysmalnoctunal dypnea menurun">
                                <span class="lbl"> Paroxysmalnoctunal dypnea menurun</span>
                            </label>
                        </div>
                        
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
            <p><b>Dibuktikan dengan: </b></p>
            <p><b>Tanda dan Gejala Mayor</b></p>
                <div style="display: flex; flex-wrap: wrap;">
                    <div style="width: 50%;">
                        <p><b><i>Subjektif:</i></b></p>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_mayor_subjektif][]" id="gejala_mayor_palpitasi" onclick="checkthis('gejala_mayor_palpitasi')" value="Perubahan irama jantung, 1) Palpitasi">
                                <span class="lbl"> Perubahan irama jantung</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 1) Palpitasi</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_mayor_subjektif][]" id="gejala_mayor_lelah" onclick="checkthis('gejala_mayor_lelah')" value="Perubahan preload, 1) Lelah">
                                <span class="lbl"> Perubahan <i>preload</i></span><br>
                                <span class="lbl" style="margin-left: 18px;"> 1) Lelah</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_mayor_subjektif][]" id="gejala_mayor_dispnea" onclick="checkthis('gejala_mayor_dispnea')" value="Perubahan afterload, 1) Dispnea">
                                <span class="lbl"> Perubahan <i>afterload</i></span><br>
                                <span class="lbl" style="margin-left: 18px;"> 1) Dispnea</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_mayor_subjektif][]" id="gejala_mayor_kontraktilitas" onclick="checkthis('gejala_mayor_kontraktilitas')" value="Perubahan kontraktilitas, 1) Paroxymal Nocturnal dtspnoe (PND), 2) Ortopnoe, 3) Batuk">
                                <span class="lbl"> Perubahan kontraktilitas</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 1) Paroxymal Nocturnal dtspnoe (PND)</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 2) Ortopnoe</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 3) Batuk</span>
                            </label>
                        </div>
                    </div>
                    <div style="width: 50%;">
                        <p><b><i>Objektif:</i></b></p>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_mayor_objektif][]" id="gejala_mayor_bradikardia_takikardia" onclick="checkthis('gejala_mayor_bradikardia_takikardia')" value="Perubahan irama jantung, 1) Bradikardia/takikardia, 2) Gambaran EKG Aritmia/gangguan konduksi">
                                <span class="lbl"> Perubahan irama jantung</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 1) Bradikardia/takikardia</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 2) Gambaran EKG Aritmia/gangguan konduksi</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_mayor_objektif][]" id="gejala_mayor_edema" onclick="checkthis('gejala_mayor_edema')" value="Perubahan preload, 1) Edema, 2) Distensi vena jugularis, 3) CVP meningkat/menurun">
                                <span class="lbl"> Perubahan <i>preload</i></span><br>
                                <span class="lbl" style="margin-left: 18px;"> 1) Edema</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 2) Distensi vena jugularis</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 3) CVP meningkat/menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_mayor_objektif][]" id="gejala_mayor_td" onclick="checkthis('gejala_mayor_td')" value="Perubahan afterload, 1) TD meningkat/menurun, 2) Nadi perifer teraba lemah, 3) CRT >3 detik, 4) Oliguria, 5) Warna kulit pucat, dan/sianosis">
                                <span class="lbl"> Perubahan <i>afterload</i></span><br>
                                <span class="lbl" style="margin-left: 18px;"> 1) TD meningkat/menurun</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 2) Nadi perifer teraba lemah</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 3) CRT >3 detik</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 4) Oliguria</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 5) Warna kulit pucat, dan/sianosis</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_mayor_objektif][]" id="gejala_mayor_perubahan_kontraktilitas" onclick="checkthis('gejala_mayor_perubahan_kontraktilitas')" value="Perubahan kontraktilitas, 1) Terdengar suara jantung S3 dan/S4, 2) Ejection Fraction  (EF)  menurun (EF)">
                                <span class="lbl"> Perubahan kontraktilitas</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 1) Terdengar suara jantung S3 dan/S4</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 2) Ejection Fraction  (EF)  menurun (EF) </span>
                            </label>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
                <p><b>Tanda dan Gejala Minor</b></p>
                <div style="display: flex; flex-wrap: wrap;">
                    <div style="width: 50%;">
                        <p><b><i>Subjektif:</i></b></p>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_minor_subjektif][]" id="gejala_minor_preload" onclick="checkthis('gejala_minor_preload')" value="Perubahan preload">
                                <span class="lbl"> Perubahan <i>preload</i></span><br>
                                <span class="lbl" style="margin-left: 18px;"> (tidak tersedia)</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_minor_subjektif][]" id="gejala_minor_afterload" onclick="checkthis('gejala_minor_afterload')" value="Perubahan afterload">
                                <span class="lbl"> Perubahan <i>afterload</i></span><br>
                                <span class="lbl" style="margin-left: 18px;"> (tidak tersedia)</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_minor_subjektif][]" id="gejala_minor_kontraktilitas" onclick="checkthis('gejala_minor_kontraktilitas')" value="Perubahan kontraktilitas">
                                <span class="lbl"> Perubahan kontraktilitas</span><br>
                                <span class="lbl" style="margin-left: 18px;"> (tidak tersedia)</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_minor_subjektif][]" id="gejala_minor_perilaku" onclick="checkthis('gejala_minor_perilaku')" value="Perubahan perilaku">
                                <span class="lbl"> Perilaku/emosional</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 1) Cemas</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 2) Gelisah</span>
                            </label>
                        </div>
                    </div>
                    <div style="width: 50%;">
                        <p><b><i>Objektif:</i></b></p>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_minor_objektif][]" id="gejala_minor_murmur_jantung" onclick="checkthis('gejala_minor_murmur_jantung')" value="Perubahan irama jantung, 1) Murmur jantung, 2) BB bertambah, 3) Pulmonary artery wadge pressure (PAWP) menurun">
                                <span class="lbl"> Perubahan <i>preload</i></span><br>
                                <span class="lbl" style="margin-left: 18px;"> 1) Murmur jantung</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 2) BB bertambah</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 3) Pulmonary artery wadge pressure (PAWP) menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_minor_objektif][]" id="gejala_minor_pvr" onclick="checkthis('gejala_minor_pvr')" value="Perubahan afterload, 1) Pulmonary vascular resistance (PVR) meningkat/menurun, 2) Systemic Vascular Resistance (SVR) meningkat/menurun, 3) Hepatomegali">
                                <span class="lbl"> Perubahan <i>afterload</i></span><br>
                                <span class="lbl" style="margin-left: 18px;"> 1) Pulmonary vascular resistance (PVR) meningkat/menurun</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 2) Systemic Vascular Resistance (SVR) meningkat/menurun</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 3) Hepatomegali</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_minor_objektif][]" id="gejala_minor_cardiac_index_menurun" onclick="checkthis('gejala_minor_cardiac_index_menurun')" value="Perubahan kontraktilitas, 1) Cardiac Index menurun, 2) Stroke volume Index (SVI) menurun">
                                <span class="lbl"> Perubahan kontraktilitas</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 1) Cardiac Index menurun</span><br>
                                <span class="lbl" style="margin-left: 18px;"> 2) Stroke volume Index (SVI) menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_58[gejala_minor_objektif][]" id="gejala_minor_cemas" onclick="checkthis('gejala_minor_cemas')" value="Perubahan perilaku">
                                <span class="lbl"> Perilaku/emosional</span><br>
                                <span class="lbl" style="margin-left: 18px;"> (tidak tersedia)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        
    </tbody>
</table>

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
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_gejala_primer" onclick="checkthis('pj_observasi_gejala_primer')" value="Identifikasi tanda/gejala primer penurunan curah jantung">
                        <span class="lbl"> Identifikasi tanda/gejala primer penurunan curah jantung (dispnea, kelelahan, edema, ortopnea, Paroxysmal nocturnal dypsnea, peningkatan CVP)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_gejala_sekunder" onclick="checkthis('pj_observasi_gejala_sekunder')" value="Identifikasi tanda/gejala sekunder penurunan curah jantung">
                        <span class="lbl"> Identifikasi tanda/gejala sekunder penurunan curah jantung (peningkatan berat badan, hepatomegaly, distensi vena jugularis, palpitasi, ronkhi basah, oliguria, batuk, kulit pucat)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_tekanan_darah" onclick="checkthis('pj_observasi_tekanan_darah')" value="Monitor tekanan darah">
                        <span class="lbl"> Monitor tekanan darah</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_intake_output" onclick="checkthis('pj_observasi_intake_output')" value="Monitor intake dan output cairan">
                        <span class="lbl"> Monitor intake dan output cairan</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_berat_badan" onclick="checkthis('pj_observasi_berat_badan')" value="Monitor berat badan tiap hari">
                        <span class="lbl"> Monitor berat badan tiap hari</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_saturasi_o2" onclick="checkthis('pj_observasi_saturasi_o2')" value="Monitor saturasi 02">
                        <span class="lbl"> Monitor saturasi 02</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_nyeri_dada" onclick="checkthis('pj_observasi_nyeri_dada')" value="Monitor keluhan nyeri dada">
                        <span class="lbl"> Monitor keluhan nyeri dada</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_ekg_12_lead" onclick="checkthis('pj_observasi_ekg_12_lead')" value="Monitor EKG 12 Lead">
                        <span class="lbl"> Monitor EKG 12 Lead</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_aritmia" onclick="checkthis('pj_observasi_aritmia')" value="Monitor aritmia (kelainan irama dan frekuensi)">
                        <span class="lbl"> Monitor aritmia (kelainan irama dan frekuensi)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_laboratorium" onclick="checkthis('pj_observasi_laboratorium')" value="Monitor nilai laboratorium jantung (elektrolit enzim jantung)">
                        <span class="lbl"> Monitor nilai laboratorium jantung (elektrolit enzim jantung)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_alat_pacu_jantung" onclick="checkthis('pj_observasi_alat_pacu_jantung')" value="Monitor fungsi alat pacu jantung">
                        <span class="lbl"> Monitor fungsi alat pacu jantung</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_td_nadi_aktivitas" onclick="checkthis('pj_observasi_td_nadi_aktivitas')" value="Periksa tekanan darah dan frekuensi nadi sebelum dan sesudah aktivitas">
                        <span class="lbl"> Periksa tekanan darah dan frekuensi nadi sebelum dan sesudah aktivitas</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][observasi][]" id="pj_observasi_td_nadi_obat" onclick="checkthis('pj_observasi_td_nadi_obat')" value="Periksa tekanan darah dan frekuensi nadi sebelum pemberian obat">
                        <span class="lbl"> Periksa tekanan darah dan frekuensi nadi sebelum pemberian obat (Mis. Betabloker, digoksin dll)</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                <input type="text" class="input_type" name="form_58[ket_tambahan_perawatan_jantung_observasi]" id="ket_tambahan_perawatan_jantung_observasi" onchange="fillthis('ket_tambahan_perawatan_jantung_observasi')" style="width:100%;"> 
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align:center;"><b>2</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Terapeutik</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][terapeutik][]" id="pj_terapeutik_posisi" onclick="checkthis('pj_terapeutik_posisi')" value="Posisikan pasien semi Fowler atau Fowler dengan kaki kebawah atau posisi nyaman">
                        <span class="lbl"> Posisikan pasien semi Fowler atau Fowler dengan kaki kebawah atau posisi nyaman</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][terapeutik][]" id="pj_terapeutik_diet" onclick="checkthis('pj_terapeutik_diet')" value="Berikan diet jantung yang sesuai">
                        <span class="lbl"> Berikan diet jantung yang sesuai</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][terapeutik][]" id="pj_terapeutik_modifikasi_gaya_hidup" onclick="checkthis('pj_terapeutik_modifikasi_gaya_hidup')" value="Fasilitasi pasien dan keluarga untuk modifikasi gaya hidup sehat">
                        <span class="lbl"> Fasilitasi pasien dan keluarga untuk modifikasi gaya hidup sehat</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][terapeutik][]" id="pj_terapeutik_relaksasi" onclick="checkthis('pj_terapeutik_relaksasi')" value="Berikan terapi relaksasi untuk mengurangi stres">
                        <span class="lbl"> Berikan terapi relaksasi untuk mengurangi stres</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][terapeutik][]" id="pj_terapeutik_dukungan" onclick="checkthis('pj_terapeutik_dukungan')" value="Berikan dukungan emosional dan spritual">
                        <span class="lbl"> Berikan dukungan emosional dan spritual</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][terapeutik][]" id="pj_terapeutik_oksigen" onclick="checkthis('pj_terapeutik_oksigen')" value="Berikan oksigen untuk mempertahankan saturasi 02≤ 94%">
                        <span class="lbl"> Berikan oksigen untuk mempertahankan saturasi 02≤ 94%</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                     <input type="text" class="input_type" name="form_58[ket_tambahan_perawatan_jantung_terapeutik]" id="ket_tambahan_perawatan_jantung_terapeutik" onchange="fillthis('ket_tambahan_perawatan_jantung_terapeutik')" style="width:100%;"> 
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align:center;"><b>3</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Edukasi</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][edukasi][]" id="pj_edukasi_aktivitas_fisik" onclick="checkthis('pj_edukasi_aktivitas_fisik')" value="Anjurkan beraktivitas fisik sesuai toleransi">
                        <span class="lbl"> Anjurkan beraktivitas fisik sesuai toleransi</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][edukasi][]" id="pj_edukasi_aktivitas_bertahap" onclick="checkthis('pj_edukasi_aktivitas_bertahap')" value="Anjurkan beraktivitas fisik secara bertahap">
                        <span class="lbl"> Anjurkan beraktivitas fisik secara bertahap</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][edukasi][]" id="pj_edukasi_berhenti_merokok" onclick="checkthis('pj_edukasi_berhenti_merokok')" value="Anjurkan berhenti merokok">
                        <span class="lbl"> Anjurkan berhenti merokok</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][edukasi][]" id="pj_edukasi_ukur_bb" onclick="checkthis('pj_edukasi_ukur_bb')" value="Anjurkan pasien mengukur berat badan harian">
                        <span class="lbl"> Anjurkan pasien mengukur berat badan harian</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][edukasi][]" id="pj_edukasi_ukur_intake_output" onclick="checkthis('pj_edukasi_ukur_intake_output')" value="Anjurkan pasien dan keluarga mengukur Intake dan output cairan harian">
                        <span class="lbl"> Anjurkan pasien dan keluarga mengukur Intake dan output cairan harian</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_58[ket_tambahan_perawatan_jantung_edukasi]" id="ket_tambahan_perawatan_jantung_edukasi" onchange="fillthis('ket_tambahan_perawatan_jantung_edukasi')" style="width:100%;"> 
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align:center;"><b>4</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Kolaborasi</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][kolaborasi][]" id="pj_kolaborasi_anti_aritmia" onclick="checkthis('pj_kolaborasi_anti_aritmia')" value="Kolaborasi pemberian anti aritmia jika perlu">
                        <span class="lbl"> Kolaborasi pemberian anti aritmia jika perlu</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung][kolaborasi][]" id="pj_kolaborasi_rehabilitas" onclick="checkthis('pj_kolaborasi_rehabilitas')" value="Rusuk ke program rehabilitas jantung">
                        <span class="lbl"> Rusuk ke program rehabilitas jantung</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_58[ket_tambahan_perawatan_jantung_kolaborasi]" id="ket_tambahan_perawatan_jantung_kolaborasi" onchange="fillthis('ket_tambahan_perawatan_jantung_kolaborasi')" style="width:100%;">
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                <b>Perawatan Jantung Akut</b> <i>(Mengidentifikasi dan mengelola pasien yang baru mengalami episode ketidakseimbangan antara ketersediaan dan kebutuhan oksigen miokard)</i> (I.02076)
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align:center;"><b>1</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <div style="margin-top: 5px;"><b>Observasi</b></div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][observasi][]" id="pja_observasi_nyeri_dada" onclick="checkthis('pja_observasi_nyeri_dada')" value="Identifikasi karakteristik nyeri dada">
                        <span class="lbl"> Identifikasi karakteristik nyeri dada (meliputi faktor pemicu dan pereda, kualitas, lokasi, radiasi, skala, durasi, dan frekuensi)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][observasi][]" id="pja_observasi_ekg" onclick="checkthis('pja_observasi_ekg')" value="Monitor EKG 12 sadapan untuk perubahan ST dan T">
                        <span class="lbl"> Monitor EKG 12 sadapan untuk perubahan ST dan T</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][observasi][]" id="pja_observasi_aritmia" onclick="checkthis('pja_observasi_aritmia')" value="Monitor aritmia (kelainan irama dan frekuensi)">
                        <span class="lbl"> Monitor aritmia (kelainan irama dan frekuensi)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][observasi][]" id="pja_observasi_elektrolit" onclick="checkthis('pja_observasi_elektrolit')" value="Monitor elektrolit yang dapat meningkatkan resiko aritmia">
                        <span class="lbl"> Monitor elektrolit yang dapat meningkatkan resiko aritmia (mis: kalium, magnesium serum)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][observasi][]" id="pja_observasi_enzim_jantung" onclick="checkthis('pja_observasi_enzim_jantung')" value="Monitor enzim jantung (misal: CK, CK-MB, Troponin T, Troponin I)">
                        <span class="lbl"> Monitor enzim jantung (misal: CK, CK-MB, Troponin T, Troponin I)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][observasi][]" id="pja_observasi_saturasi" onclick="checkthis('pja_observasi_saturasi')" value="Monitor saturasi oksigen">
                        <span class="lbl"> Monitor saturasi oksigen</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][observasi][]" id="pja_observasi_stratifikasi" onclick="checkthis('pja_observasi_stratifikasi')" value="Indentifikasi stratifikasi pada sindrom koroner akut">
                        <span class="lbl"> Indentifikasi stratifikasi pada sindrom koroner akut (mis: skor TIMI, Killip, Crusade)</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_58[ket_tambahan_perawatan_jantung_akut_observasi]" id="ket_tambahan_perawatan_jantung_akut_observasi" onchange="fillthis('ket_tambahan_perawatan_jantung_akut_observasi')" style="width:100%;">
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align:center;"><b>2</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Terapeutik</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][terapeutik][]" id="pja_terapeutik_tirah_baring" onclick="checkthis('pja_terapeutik_tirah_baring')" value="Pertahankan tirah baring minimal 12 jam">
                        <span class="lbl"> Pertahankan tirah baring minimal 12 jam</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][terapeutik][]" id="pja_terapeutik_intravena" onclick="checkthis('pja_terapeutik_intravena')" value="Pasang akses intravena">
                        <span class="lbl"> Pasang akses intravena</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][terapeutik][]" id="pja_terapeutik_puasakan" onclick="checkthis('pja_terapeutik_puasakan')" value="Puasakan hingga bebas nyeri">
                        <span class="lbl"> Puasakan hingga bebas nyeri</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][terapeutik][]" id="pja_terapeutik_relaksasi" onclick="checkthis('pja_terapeutik_relaksasi')" value="Berikan terapi relaksasi untuk mengurangi ansietas dan stres">
                        <span class="lbl"> Berikan terapi relaksasi untuk mengurangi ansietas dan stres</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][terapeutik][]" id="pja_terapeutik_lingkungan" onclick="checkthis('pja_terapeutik_lingkungan')" value="Sediakan lingkungan yang kondusif untuk beristirahat dan pemulihan">
                        <span class="lbl"> Sediakan lingkungan yang kondusif untuk beristirahat dan pemulihan</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][terapeutik][]" id="pja_terapeutik_intervensi_koroner" onclick="checkthis('pja_terapeutik_intervensi_koroner')" value="Siapkan menjalani intervensi koroner perkutan jika perlu">
                        <span class="lbl"> Siapkan menjalani intervensi koroner perkutan jika perlu</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][terapeutik][]" id="pja_terapeutik_dukungan" onclick="checkthis('pja_terapeutik_dukungan')" value="Berikan dukungan emosional dan spritual">
                        <span class="lbl"> Berikan dukungan emosional dan spritual</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_58[ket_tambahan_perawatan_jantung_akut_terapeutik]" id="ket_tambahan_perawatan_jantung_akut_terapeutik" onchange="fillthis('ket_tambahan_perawatan_jantung_akut_terapeutik')" style="width:100%;">
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align:center;"><b>3</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Edukasi</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][edukasi][]" id="pja_edukasi_laporkan_nyeri" onclick="checkthis('pja_edukasi_laporkan_nyeri')" value="Anjurkan segera melaporkan nyeri dada">
                        <span class="lbl"> Anjurkan segera melaporkan nyeri dada</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][edukasi][]" id="pja_edukasi_hindari_valsava" onclick="checkthis('pja_edukasi_hindari_valsava')" value="Anjurkan menghindari manuver valsava (mis: mengedan saat BAB atau batuk)">
                        <span class="lbl"> Anjurkan menghindari manuver valsava (mis: mengedan saat BAB atau batuk)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][edukasi][]" id="pja_edukasi_jelaskan_tindakan" onclick="checkthis('pja_edukasi_jelaskan_tindakan')" value="Jelaskan tindakan yang dijalani pasien">
                        <span class="lbl"> Jelaskan tindakan yang dijalani pasien</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][edukasi][]" id="pja_edukasi_teknik_menurunkan_kecemasan" onclick="checkthis('pja_edukasi_teknik_menurunkan_kecemasan')" value="Ajarkan teknik menurunkan kecemasan dan ketakutan">
                        <span class="lbl"> Ajarkan teknik menurunkan kecemasan dan ketakutan</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_58[ket_tambahan_perawatan_jantung_akut_edukasi]" id="ket_tambahan_perawatan_jantung_akut_edukasi" onchange="fillthis('ket_tambahan_perawatan_jantung_akut_edukasi')" style="width:100%;">
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; text-align:center;"><b>4</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Kolaborasi</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][kolaborasi][]" id="pja_kolaborasi_antiplatelet" onclick="checkthis('pja_kolaborasi_antiplatelet')" value="Kolaborasi pemberian antiplatelet jika perlu">
                        <span class="lbl"> Kolaborasi pemberian antiplatelet jika perlu</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][kolaborasi][]" id="pja_kolaborasi_antiangina" onclick="checkthis('pja_kolaborasi_antiangina')" value="Kolaborasi pemberian antiangina">
                        <span class="lbl"> Kolaborasi pemberian antiangina (mis. Nitrogliserin, beta blocker, calcium channel blocker)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][kolaborasi][]" id="pja_kolaborasi_morfin" onclick="checkthis('pja_kolaborasi_morfin')" value="Kolaborasi pemberian morfin jika perlu">
                        <span class="lbl"> Kolaborasi pemberian morfin jika perlu</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_58[perawatan_jantung_akut][kolaborasi][]" id="pja_kolaborasi_inotropik" onclick="checkthis('pja_kolaborasi_inotropik')" value="Kolaborasi pemberian inotropik jika perlu">
                        <span class="lbl"> Kolaborasi pemberian inotropik jika perlu</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_58[ket_tambahan_perawatan_jantung_akut_kolaborasi]" id="ket_tambahan_perawatan_jantung_akut_kolaborasi" onchange="fillthis('ket_tambahan_perawatan_jantung_akut_kolaborasi')" style="width:100%;">
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
        <input type="text" class="input_type" name="form_58[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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