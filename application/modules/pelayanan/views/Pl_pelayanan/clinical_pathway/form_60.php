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
      var hiddenInputName = 'form_60[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 25 september 2025</p> -->
<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN:<br>GANGGUAN PERTUKARAN GAS</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
    <thead>
     <tr>
        <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">Definisi: Kelebihan atau kekurangan oksigenasi dan/atau eliminasi karbondioksida pada membran alveolus kapiler
        </td>
     </tr>
        <!-- <tr>
            <th style="border: 1px solid black; padding: 5px;">PENYEBAB/Berhubungan dengan:</th>
            <th style="border: 1px solid black; padding: 5px;">Setelah dilakukan intervensi selama <input type="text" class="input_type" name="form_60[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;"> maka Pola nafas tidak efektif membaik (L.01004), dengan kriteria hasil : </th>
        </tr> -->
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
                <b>PENYEBAB/Berhubungan dengan:</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_60[penyebab][]" id="penyebab_ventilasi_perfusi" onclick="checkthis('penyebab_ventilasi_perfusi')" value="Ketidakseimbangan ventilasi-perfusi">
                        <span class="lbl"> Ketidakseimbangan ventilasi-perfusi</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_60[penyebab][]" id="penyebab_membran_alveolus" onclick="checkthis('penyebab_membran_alveolus')" value="Perubahan membran alveolus-kapiler">
                        <span class="lbl"> Perubahan membran alveolus-kapiler</span>
                    </label>
                </div>

            </td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Setelah dilakukan intervensi selama <input type="text" class="input_type" name="form_60[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;"> maka Pola nafas tidak efektif membaik (L.01004), dengan kriteria hasil :</b>
                <div style="display: flex; flex-wrap: wrap;">
                    <div style="width: 50%;">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_tingkat_kesadaran_meningkat" onclick="checkthis('kriteria_tingkat_kesadaran_meningkat')" value="Tingkat kesadaran meningkat">
                                <span class="lbl"> Tingkat kesadaran meningkat</span>
                            </label>
                        </div>
                        <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_dispnea_menurun" onclick="checkthis('kriteria_dispnea_menurun')" value="Dispnea menurun">
                            <span class="lbl"> Dispnea menurun</span>
                        </label>
                        </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_bunyi_nafas_tambahan_menurun" onclick="checkthis('kriteria_bunyi_nafas_tambahan_menurun')" value="Bunyi nafas tambahan menurun">
                            <span class="lbl"> Bunyi nafas tambahan menurun</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_pusing_menurun" onclick="checkthis('kriteria_pusing_menurun')" value="Pusing menurun">
                            <span class="lbl"> Pusing menurun</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_penglihatan_kabur_menurun" onclick="checkthis('kriteria_penglihatan_kabur_menurun')" value="Penglihatan kabur menurun">
                            <span class="lbl"> Penglihatan kabur menurun</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_diaforesis_menurun" onclick="checkthis('kriteria_diaforesis_menurun')" value="Diaforesis menurun">
                            <span class="lbl"> Diaforesis menurun</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_gelisah_menurun" onclick="checkthis('kriteria_gelisah_menurun')" value="Gelisah menurun">
                            <span class="lbl"> Gelisah menurun</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_nafas_cuping_hidung" onclick="checkthis('kriteria_nafas_cuping_hidung')" value="Nafas cuping hidung menurun">
                            <span class="lbl"> Nafas cuping hidung menurun</span>
                        </label>
                    </div>
                </div>
                <div style="width: 45%;">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_pco2_membaik" onclick="checkthis('kriteria_pco2_membaik')" value="PCO2 membaik">
                            <span class="lbl"> PCO2 membaik</span>
                        </label>
                    </div>
                    
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_po2_membaik" onclick="checkthis('kriteria_po2_membaik')" value="PO2 Membaik">
                            <span class="lbl"> PO2 Membaik</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_takikardia_membaik" onclick="checkthis('kriteria_takikardia_membaik')" value="Takikardia membaik">
                            <span class="lbl"> Takikardia membaik</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_ph_arteri_membaik" onclick="checkthis('kriteria_ph_arteri_membaik')" value="pH arteri membaik">
                            <span class="lbl"> pH arteri membaik</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_sianosis_membaik" onclick="checkthis('kriteria_sianosis_membaik')" value="Sianosis membaik">
                            <span class="lbl"> Sianosis membaik</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_pola_nafas_membaik" onclick="checkthis('kriteria_pola_nafas_membaik')" value="Pola nafas membaik">
                            <span class="lbl"> Pola nafas membaik</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="ace" name="form_60[kriteria_hasil][]" id="kriteria_warna_kulit_membaik" onclick="checkthis('kriteria_warna_kulit_membaik')" value="Warna kulit membaik">
                            <span class="lbl"> Warna kulit membaik</span>
                        </label>
                    </div>
                </div>
            </td>
        </tr>
        
        <!-- next -->

        <tr>
                <td colspan="2" style="border: 1px solid black; padding: 5px;">
                    <b>Dibuktikan dengan:</b>
                    <p><b>Tanda dan Gejala Mayor</b></p>
                    <div class="row">
                        <div class="col-md-6">
                            <b>Subjektif:</b>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_mayor_subjektif][]" id="gejala_mayor_subjektif" onclick="checkthis('gejala_mayor_subjektif')" value="Dispnea"><span class="lbl"> Dispnea</span></label></div>
                        </div>
                        <div class="col-md-6">
                            <b>Objektif:</b>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_mayor_objektif][]" id="gejala_mayor_pco2" onclick="checkthis('gejala_mayor_pco2')" value="PCO2 meningkat/menurun"><span class="lbl"> PCO2 meningkat/menurun</span></label></div>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_mayor_objektif][]" id="gejala_mayor_po2" onclick="checkthis('gejala_mayor_po2')" value="PO2 menurun"><span class="lbl"> PO2 menurun</span></label></div>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_mayor_objektif][]" id="gejala_mayor_takikardi" onclick="checkthis('gejala_mayor_takikardi')" value="Takikardi"><span class="lbl"> Takikardi</span></label></div>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_mayor_objektif][]" id="gejala_mayor_ph_arteri" onclick="checkthis('gejala_mayor_ph_arteri')" value="pH arteri meningkat/menurun"><span class="lbl"> pH arteri meningkat/menurun</span></label></div>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_mayor_objektif][]" id="gejala_mayor_bunyi_nafas" onclick="checkthis('gejala_mayor_bunyi_nafas')" value="Bunyi nafas tambahan"><span class="lbl"> Bunyi nafas tambahan</span></label></div>
                        </div>
                    </div>
                    <hr>
                    <p><b>Tanda dan Gejala Minor</b></p>
                    <div class="row">
                        <div class="col-md-6">
                            <b>Subjektif:</b>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_minor_subjektif][]" id="gejala_minor_pusing" onclick="checkthis('gejala_minor_pusing')" value="Pusing"><span class="lbl"> Pusing</span></label></div>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_minor_subjektif][]" id="gejala_minor_penglihatan_kabur" onclick="checkthis('gejala_minor_penglihatan_kabur')" value="Penglihatan kabur"><span class="lbl"> Penglihatan kabur</span></label></div>
                        </div>
                        <div class="col-md-6">
                            <b>Objektif:</b>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_minor_objektif][]" id="gejala_minor_sianosis" onclick="checkthis('gejala_minor_sianosis')" value="Sianosis"><span class="lbl"> Sianosis</span></label></div>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_minor_objektif][]" id="gejala_minor_diaforesis" onclick="checkthis('gejala_minor_diaforesis')" value="Diaforesis"><span class="lbl"> Diaforesis</span></label></div>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_minor_objektif][]" id="gejala_minor_gelisah" onclick="checkthis('gejala_minor_gelisah')" value="Gelisah"><span class="lbl"> Gelisah</span></label></div>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_minor_objektif][]" id="gejala_minor_nafas_cuping_hidung" onclick="checkthis('gejala_minor_nafas_cuping_hidung')" value="Nafas cuping hidung"><span class="lbl"> Nafas cuping hidung</span></label></div>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_minor_objektif][]" id="gejala_minor_pola_nafas_abnormal" onclick="checkthis('gejala_minor_pola_nafas_abnormal')" value="Pola nafas abnormal"><span class="lbl"> Pola nafas abnormal (cepat/lambat, reguler/ireguler, dalam/dangkal)</span></label></div>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_minor_objektif][]" id="gejala_minor_warna_kulit" onclick="checkthis('gejala_minor_warna_kulit')" value="Warna kulit abnormal"><span class="lbl"> Warna kulit abnormal (mis. Pucat, kebiruan)</span></label></div>
                            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[gejala_minor_objektif][]" id="gejala_minor_kesadaran_menurun" onclick="checkthis('gejala_minor_kesadaran_menurun')" value="Kesadaran menurun"><span class="lbl"> Kesadaran menurun</span></label></div>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="border: 1px solid black; padding: 5px;">
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
                                    <b>Pertukaran gas <i>(Oksigenasi dan/atau eliminasi karbondioksida pada membran alveolus-kapiler dalam batas normal)</i> L.01003</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid black; padding: 5px;">
                                    <b>TINDAKAN</b>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>1</b></td>
                                <td style="border: 1px solid black; padding: 5px;">
                                    <label><b>Observasi</b></label><br>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_observasi_respirasi][]" id="observasi_frekuensi_nafas" onclick="checkthis('observasi_frekuensi_nafas')" value="Monitor frekuensi, irama, kedalaman, dan upaya nafas"><span class="lbl"> Monitor frekuensi, irama, kedalaman, dan upaya nafas</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_observasi_respirasi][]" id="observasi_pola_nafas" onclick="checkthis('observasi_pola_nafas')" value="Monitor pola nafas"><span class="lbl"> Monitor pola nafas (seperti bradipnea, takipnea, hiperventilasi, kussmaul, cheyne-stokes, biot, ataksis)</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_observasi_respirasi][]" id="observasi_kemampuan_batuk" onclick="checkthis('observasi_kemampuan_batuk')" value="Monitor kemampuan batuk efektif"><span class="lbl"> Monitor kemampuan batuk efektif</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_observasi_respirasi][]" id="observasi_sputum" onclick="checkthis('observasi_sputum')" value="Monitor adanya produksi sputum"><span class="lbl"> Monitor adanya produksi sputum</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_observasi_respirasi][]" id="observasi_sumbatan_jalan_nafas" onclick="checkthis('observasi_sumbatan_jalan_nafas')" value="Monitor adanya sumbatan jalan nafas"><span class="lbl"> Monitor adanya sumbatan jalan nafas</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_observasi_respirasi][]" id="observasi_ekspansi_paru" onclick="checkthis('observasi_ekspansi_paru')" value="Palpasi kesimetrisan ekspansi paru"><span class="lbl"> Palpasi kesimetrisan ekspansi paru</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_observasi_respirasi][]" id="observasi_bunyi_nafas" onclick="checkthis('observasi_bunyi_nafas')" value="Auskultasi bunyi nafas"><span class="lbl"> Auskultasi bunyi nafas</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_observasi_respirasi][]" id="observasi_saturasi_oksigen" onclick="checkthis('observasi_saturasi_oksigen')" value="Monitor saturasi oksigen"><span class="lbl"> Monitor saturasi oksigen</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_observasi_respirasi][]" id="observasi_agd" onclick="checkthis('observasi_agd')" value="Monitor nilai AGD"><span class="lbl"> Monitor nilai AGD</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_observasi_respirasi][]" id="observasi_xray_thorax" onclick="checkthis('observasi_xray_thorax')" value="Monitor hasil x-ray thorax"><span class="lbl"> Monitor hasil x-ray thorax</span></label></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>2</b></td>
                                <td style="border: 1px solid black; padding: 5px;">
                                    <label><b>Terapeutik</b></label><br>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_terapeutik_respirasi][]" id="terapeutik_interval_pemantauan" onclick="checkthis('terapeutik_interval_pemantauan')" value="Atur interval pemantauan respirasi sesuai kondisi pasien"><span class="lbl"> Atur interval pemantauan respirasi sesuai kondisi pasien</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_terapeutik_respirasi][]" id="terapeutik_dokumentasi" onclick="checkthis('terapeutik_dokumentasi')" value="Dokumentasikan hasil pemantauan"><span class="lbl"> Dokumentasikan hasil pemantauan</span></label></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>3</b></td>
                                <td style="border: 1px solid black; padding: 5px;">
                                    <label><b>Edukasi</b></label><br>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_edukasi_respirasi][]" id="edukasi_tujuan_prosedur" onclick="checkthis('edukasi_tujuan_prosedur')" value="Jelaskan tujuan dan prosedur pemantauan"><span class="lbl"> Jelaskan tujuan dan prosedur pemantauan</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[intervensi_edukasi_respirasi][]" id="edukasi_informasi_hasil" onclick="checkthis('edukasi_informasi_hasil')" value="Informasikan hasil pemantauan jika perlu"><span class="lbl"> Informasikan hasil pemantauan jika perlu</span></label></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="background-color: #d3d3d3;">
                                <th colspan="2" style="border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" style="border: 1px solid black; padding: 5px;">
                                    <b>TERAPI OKSIGEN <i>(Memberikan tambahan oksigen untuk mencegah dan mengatasi kondisi kekurangan oksigen jaringan)</i> (I.01026)</b>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 5%; border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>1</b></td>
                                <td style="border: 1px solid black; padding: 5px;">
                                    <label><b>Observasi</b></label><br>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_observasi][]" id="oksigen_observasi_kecepatan_aliran" onclick="checkthis('oksigen_observasi_kecepatan_aliran')" value="Monitor kecepatan aliran oksigen"><span class="lbl"> Monitor kecepatan aliran oksigen</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_observasi][]" id="oksigen_observasi_posisi_alat" onclick="checkthis('oksigen_observasi_posisi_alat')" value="Monitor posisi alat terapi oksigen"><span class="lbl"> Monitor posisi alat terapi oksigen</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_observasi][]" id="oksigen_observasi_aliran_periodik" onclick="checkthis('oksigen_observasi_aliran_periodik')" value="Monitor aliran oksigen secara periodik dan pastikan fraksi yang diberikan cukup"><span class="lbl"> Monitor aliran oksigen secara periodik dan pastikan fraksi yang diberikan cukup</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_observasi][]" id="oksigen_observasi_efektivitas" onclick="checkthis('oksigen_observasi_efektivitas')" value="Monitor efektivitas terapi oksigen (misal oksimetri, analisa gas darah) jika perlu"><span class="lbl"> Monitor efektivitas terapi oksigen (misal oksimetri, analisa gas darah) jika perlu</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_observasi][]" id="oksigen_observasi_kemampuan_makan" onclick="checkthis('oksigen_observasi_kemampuan_makan')" value="Monitor kemampuan melepaskan oksigen saat makan"><span class="lbl"> Monitor kemampuan melepaskan oksigen saat makan</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_observasi][]" id="oksigen_observasi_hipoventilasi" onclick="checkthis('oksigen_observasi_hipoventilasi')" value="Monitor tanda-tanda hipoventilasi"><span class="lbl"> Monitor tanda-tanda hipoventilasi</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_observasi][]" id="oksigen_observasi_toksikasi" onclick="checkthis('oksigen_observasi_toksikasi')" value="Monitor tanda dan gejala toksikasi oksigen dan atelektasis"><span class="lbl"> Monitor tanda dan gejala toksikasi oksigen dan atelektasis</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_observasi][]" id="oksigen_observasi_kecemasan" onclick="checkthis('oksigen_observasi_kecemasan')" value="Monitor tingkat kecemasan akibat terapi oksigen"><span class="lbl"> Monitor tingkat kecemasan akibat terapi oksigen</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_observasi][]" id="oksigen_observasi_mukosa_hidung" onclick="checkthis('oksigen_observasi_mukosa_hidung')" value="Monitor integritas mukosa hidung akibat pemasangan oksigen"><span class="lbl"> Monitor integritas mukosa hidung akibat pemasangan oksigen</span></label></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>2</b></td>
                                <td style="border: 1px solid black; padding: 5px;">
                                    <label><b>Terapeutik</b></label><br>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_terapeutik][]" id="oksigen_terapeutik_bersihkan_secret" onclick="checkthis('oksigen_terapeutik_bersihkan_secret')" value="Bersihkan secret pada mulut, hidung dan trachea, jika perlu"><span class="lbl"> Bersihkan secret pada mulut, hidung dan trachea, jika perlu</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_terapeutik][]" id="oksigen_terapeutik_patenkan_jalan_nafas" onclick="checkthis('oksigen_terapeutik_patenkan_jalan_nafas')" value="Pertahankan kepatenan jalan nafas"><span class="lbl"> Pertahankan kepatenan jalan nafas</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_terapeutik][]" id="oksigen_terapeutik_siapkan_peralatan" onclick="checkthis('oksigen_terapeutik_siapkan_peralatan')" value="Siapkan dan atur peralatan pemberian oksigen"><span class="lbl"> Siapkan dan atur peralatan pemberian oksigen</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_terapeutik][]" id="oksigen_terapeutik_berikan_tambahan" onclick="checkthis('oksigen_terapeutik_berikan_tambahan')" value="Berikan oksigen tambahan jika perlu"><span class="lbl"> Berikan oksigen tambahan jika perlu</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_terapeutik][]" id="oksigen_terapeutik_saat_transportasi" onclick="checkthis('oksigen_terapeutik_saat_transportasi')" value="Tetap berikan oksigen saat pasien di transportasi"><span class="lbl"> Tetap berikan oksigen saat pasien di transportasi</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_terapeutik][]" id="oksigen_terapeutik_perangkat_sesuai" onclick="checkthis('oksigen_terapeutik_perangkat_sesuai')" value="Gunakan perangkat oksigen yang sesuai dengan tingkat mobilitas pasien"><span class="lbl"> Gunakan perangkat oksigen yang sesuai dengan tingkat mobilitas pasien</span></label></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>3</b></td>
                                <td style="border: 1px solid black; padding: 5px;">
                                    <label><b>Edukasi</b></label><br>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_edukasi][]" id="oksigen_edukasi_cara_menggunakan" onclick="checkthis('oksigen_edukasi_cara_menggunakan')" value="Ajarkan pasien dan keluarga cara menggunakan oksigen di rumah"><span class="lbl"> Ajarkan pasien dan keluarga cara menggunakan oksigen di rumah</span></label></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; padding: 5px; text-align: center;"><b>4</b></td>
                                <td style="border: 1px solid black; padding: 5px;">
                                    <label><b>Kolaborasi</b></label><br>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_kolaborasi][]" id="oksigen_kolaborasi_dosis" onclick="checkthis('oksigen_kolaborasi_dosis')" value="Kolaborasi penentuan dosis oksigen"><span class="lbl"> Kolaborasi penentuan dosis oksigen</span></label></div>
                                    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_60[terapi_oksigen_kolaborasi][]" id="oksigen_kolaborasi_saat_aktivitas" onclick="checkthis('oksigen_kolaborasi_saat_aktivitas')" value="Kolaborasi penggunaan oksigen saat aktivitas dan/atau tidur"><span class="lbl"> Kolaborasi penggunaan oksigen saat aktivitas dan/atau tidur</span></label></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
        <input type="text" class="input_type" name="form_60[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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