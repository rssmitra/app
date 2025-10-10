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
      var hiddenInputName = 'form_82[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 07 oktober 2025</p> -->

<div style="text-align: center; font-size: 18px;">
  <b>DIAGNOSIS KEPERAWATAN:<br>GANGGUAN PENYAPIHAN VENTILATOR</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Definisi:</b> Ketidakmampuan beradaptasi dengan pengurangan bantuan ventilator mekanik yang dapat menghambat dan memperlama proses penyapihan.
      </td>
    </tr>
  </thead>

  <tbody>
    <!-- PENYEBAB -->
    <tr>
      <td width="50%" style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif;">
        <b>PENYEBAB / Berhubungan dengan:</b><br><br>

        <i>Fisiologis</i><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[penyebab_fisiologis][]" id="penyebab_hipersekresi" onclick="checkthis('penyebab_hipersekresi')" value="Hipersekresi jalan nafas"><span class="lbl"> Hipersekresi jalan nafas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[penyebab_fisiologis][]" id="penyebab_energi" onclick="checkthis('penyebab_energi')" value="Ketidakcukupan energi"><span class="lbl"> Ketidakcukupan energi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[penyebab_fisiologis][]" id="penyebab_hambatan_nafas" onclick="checkthis('penyebab_hambatan_nafas')" value="Hambatan upaya nafas (mis: nyeri saat bernafas, kelemahan otot pernafasan, efek radiasi)"><span class="lbl"> Hambatan upaya nafas (mis: nyeri saat bernafas, kelemahan otot pernafasan, efek radiasi)</span></label></div>
        <br>

        <i>Psikologis</i><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[penyebab_psikologis][]" id="penyebab_kecemasan" onclick="checkthis('penyebab_kecemasan')" value="Kecemasan"><span class="lbl"> Kecemasan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[penyebab_psikologis][]" id="penyebab_tidak_berdaya" onclick="checkthis('penyebab_tidak_berdaya')" value="Perasaan tidak berdaya"><span class="lbl"> Perasaan tidak berdaya</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[penyebab_psikologis][]" id="penyebab_kurang_informasi" onclick="checkthis('penyebab_kurang_informasi')" value="Kurang informasi tentang proses penyapihan"><span class="lbl"> Kurang informasi tentang proses penyapihan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[penyebab_psikologis][]" id="penyebab_motivasi" onclick="checkthis('penyebab_motivasi')" value="Penurunan motivasi"><span class="lbl"> Penurunan motivasi</span></label></div>
        <br>

        <i>Situasional</i><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[penyebab_situasional][]" id="penyebab_dukungan" onclick="checkthis('penyebab_dukungan')" value="Ketidakadekuatan dukungan sosial"><span class="lbl"> Ketidakadekuatan dukungan sosial</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[penyebab_situasional][]" id="penyebab_kecepatan" onclick="checkthis('penyebab_kecepatan')" value="Ketidaktepatan kecepatan proses penyapihan"><span class="lbl"> Ketidaktepatan kecepatan proses penyapihan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[penyebab_situasional][]" id="penyebab_kegagalan" onclick="checkthis('penyebab_kegagalan')" value="Riwayat kegagalan berulang dalam upaya penyapihan"><span class="lbl"> Riwayat kegagalan berulang dalam upaya penyapihan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[penyebab_situasional][]" id="penyebab_ketergantungan" onclick="checkthis('penyebab_ketergantungan')" value="Riwayat ketergantungan ventilator >4 hari"><span class="lbl"> Riwayat ketergantungan ventilator >4 hari</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_82[intervensi_selama]" id="intervensi_selama" onchange="fillthis('intervensi_selama')" style="width:10%;">
          , Penyapihan Ventilator meningkat (L.01002), dengan kriteria hasil:</b><br><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_sinkron" onclick="checkthis('hasil_sinkron')" value="Kesinkronan bantuan ventilator meningkat"><span class="lbl"> Kesinkronan bantuan ventilator meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_otot_bantu" onclick="checkthis('hasil_otot_bantu')" value="Penggunaan otot bantu nafas menurun"><span class="lbl"> Penggunaan otot bantu nafas menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_gasping" onclick="checkthis('hasil_gasping')" value="Nafas gasping menurun"><span class="lbl"> Nafas gasping menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_dangkal" onclick="checkthis('hasil_dangkal')" value="Nafas dangkal menurun"><span class="lbl"> Nafas dangkal menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_agitasi" onclick="checkthis('hasil_agitasi')" value="Agitasi menurun"><span class="lbl"> Agitasi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_lelah" onclick="checkthis('hasil_lelah')" value="Lelah menurun"><span class="lbl"> Lelah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_khawatir" onclick="checkthis('hasil_khawatir')" value="Perasaan kuatir mesin rusak"><span class="lbl"> Perasaan kuatir mesin rusak</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_fokus" onclick="checkthis('hasil_fokus')" value="Fokus pada pernafasan menurun"><span class="lbl"> Fokus pada pernafasan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_paradoks" onclick="checkthis('hasil_paradoks')" value="Nafas paradoks abdominal menurun"><span class="lbl"> Nafas paradoks abdominal menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_diaforesis" onclick="checkthis('hasil_diaforesis')" value="Diaforesis menurun"><span class="lbl"> Diaforesis menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_frekuensi" onclick="checkthis('hasil_frekuensi')" value="Frekuensi nafas membaik"><span class="lbl"> Frekuensi nafas membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_agd" onclick="checkthis('hasil_agd')" value="Nilai AGD membaik"><span class="lbl"> Nilai AGD membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_upaya" onclick="checkthis('hasil_upaya')" value="Upaya nafas membaik"><span class="lbl"> Upaya nafas membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_auskultasi" onclick="checkthis('hasil_auskultasi')" value="Auskultasi suara inspirasi membaik"><span class="lbl"> Auskultasi suara inspirasi membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[kriteria_hasil][]" id="hasil_warna" onclick="checkthis('hasil_warna')" value="Warna kulit membaik"><span class="lbl"> Warna kulit membaik</span></label></div>
      </td>
    </tr>

    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dibuktikan dengan:</b><br>

        <p><b>Tanda dan Gejala Mayor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <i>(Tidak tersedia)</i>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[mayor_objektif][]" id="mayor_frekuensi" onclick="checkthis('mayor_frekuensi')" value="Frekuensi nafas meningkat"><span class="lbl"> Frekuensi nafas meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[mayor_objektif][]" id="mayor_alat_bantu" onclick="checkthis('mayor_alat_bantu')" value="Penggunaan alat bantu nafas"><span class="lbl"> Penggunaan alat bantu nafas</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[mayor_objektif][]" id="mayor_gasping" onclick="checkthis('mayor_gasping')" value="Nafas megap-megap (gasping)"><span class="lbl"> Nafas megap-megap (gasping)</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[mayor_objektif][]" id="mayor_sinkron" onclick="checkthis('mayor_sinkron')" value="Upaya nafas dan bantuan ventilator tidak sinkron"><span class="lbl"> Upaya nafas dan bantuan ventilator tidak sinkron</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[mayor_objektif][]" id="mayor_dangkal" onclick="checkthis('mayor_dangkal')" value="Nafas dangkal"><span class="lbl"> Nafas dangkal</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[mayor_objektif][]" id="mayor_agitasi" onclick="checkthis('mayor_agitasi')" value="Agitasi"><span class="lbl"> Agitasi</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[mayor_objektif][]" id="mayor_agd" onclick="checkthis('mayor_agd')" value="Nilai gas darah arteri abnormal"><span class="lbl"> Nilai gas darah arteri abnormal</span></label></div>
          </div>
        </div>

        <hr>

        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[minor_subjektif][]" id="minor_lelah" onclick="checkthis('minor_lelah')" value="Lelah"><span class="lbl"> Lelah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[minor_subjektif][]" id="minor_khawatir" onclick="checkthis('minor_khawatir')" value="Khawatir mesin rusak"><span class="lbl"> Khawatir mesin rusak</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[minor_subjektif][]" id="minor_fokus" onclick="checkthis('minor_fokus')" value="Fokus meningkat pada pernafasan"><span class="lbl"> Fokus meningkat pada pernafasan</span></label></div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[minor_objektif][]" id="minor_auskultasi" onclick="checkthis('minor_auskultasi')" value="Auskultasi suara inspirasi menurun"><span class="lbl"> Auskultasi suara inspirasi menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[minor_objektif][]" id="minor_warna" onclick="checkthis('minor_warna')" value="Warna kulit abnormal (pucat, sianosis)"><span class="lbl"> Warna kulit abnormal (pucat, sianosis)</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[minor_objektif][]" id="minor_paradoks" onclick="checkthis('minor_paradoks')" value="Nafas paradoks abdominal"><span class="lbl"> Nafas paradoks abdominal</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[minor_objektif][]" id="minor_diaforesis" onclick="checkthis('minor_diaforesis')" value="Diaforesis"><span class="lbl"> Diaforesis</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[minor_objektif][]" id="minor_takut" onclick="checkthis('minor_takut')" value="Ekspresi wajah takut"><span class="lbl"> Ekspresi wajah takut</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[minor_objektif][]" id="minor_td" onclick="checkthis('minor_td')" value="Tekanan darah meningkat"><span class="lbl"> TD meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[minor_objektif][]" id="minor_nadi" onclick="checkthis('minor_nadi')" value="Frekuensi nadi meningkat"><span class="lbl"> Frekuensi nadi meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[minor_objektif][]" id="minor_kesadaran" onclick="checkthis('minor_kesadaran')" value="Kesadaran menurun"><span class="lbl"> Kesadaran menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[minor_objektif][]" id="minor_gelisah" onclick="checkthis('minor_gelisah')" value="Gelisah"><span class="lbl"> Gelisah</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->


<!-- PENYAPIHAN VENTILASI MEKANIK -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size:13px; line-height:1.3;">
  <thead>
    <tr style="background-color:#d3d3d3;">
      <th style="width:5%; border:1px solid black; text-align:center;">NO.</th>
      <th style="width:95%; border:1px solid black; text-align:center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Judul -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Penyapihan Ventilasi Mekanik</b><br>
        <i>(Memfasilitasi pasien bernafas tanpa bantuan ventilasi mekanik)</i><br>
        <b>(I.01021)</b>
      </td>
    </tr>

    <!-- 1. Observasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>1</b></td>
      <td style="vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_observasi][]" id="weaning_observasi_1" onclick="checkthis('weaning_observasi_1')" value="Periksa kemampuan untuk disapih (hemodinamik stabil, kondisi optimal, bebas infeksi)"><span class="lbl"> Periksa kemampuan untuk disapih (hemodinamik stabil, kondisi optimal, bebas infeksi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_observasi][]" id="weaning_observasi_2" onclick="checkthis('weaning_observasi_2')" value="Monitor prediktor kemampuan untuk mentolelir penyapihan (tingkat kemampuan bernafas, kapasitas vital, Vd/Vt, MVV, kekuatan inspirasi, tekan inspirasi negatif)"><span class="lbl"> Monitor prediktor kemampuan untuk mentolelir penyapihan (tingkat kemampuan bernafas, kapasitas vital, Vd/Vt, MVV, kekuatan inspirasi, tekan inspirasi negatif)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_observasi][]" id="weaning_observasi_3" onclick="checkthis('weaning_observasi_3')" value="Monitor tanda-tanda kelelahan otot pernafasan (kenaikan PaCO2 mendadak, nafas cepat dan dangkal, gerakan dinding abdomen paradoks), hipoksemia, dan hipoksia jaringan saat penyapihan"><span class="lbl"> Monitor tanda-tanda kelelahan otot pernafasan (kenaikan PaCO2 mendadak, nafas cepat dan dangkal, gerakan dinding abdomen paradoks), hipoksemia, dan hipoksia jaringan saat penyapihan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_observasi][]" id="weaning_observasi_4" onclick="checkthis('weaning_observasi_4')" value="Monitor status cairan dan elektrolit"><span class="lbl"> Monitor status cairan dan elektrolit</span></label></div>
      </td>
    </tr>

    <!-- 2. Terapeutik -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>2</b></td>
      <td style="vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_terapeutik][]" id="weaning_terapeutik_1" onclick="checkthis('weaning_terapeutik_1')" value="Posisikan pasien semi fowler (30-45 derajat)"><span class="lbl"> Posisikan pasien semi fowler (30-45 derajat)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_terapeutik][]" id="weaning_terapeutik_2" onclick="checkthis('weaning_terapeutik_2')" value="Lakukan penghisapan jalan nafas"><span class="lbl"> Lakukan penghisapan jalan nafas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_terapeutik][]" id="weaning_terapeutik_3" onclick="checkthis('weaning_terapeutik_3')" value="Berikan fisioterapi dada"><span class="lbl"> Berikan fisioterapi dada</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_terapeutik][]" id="weaning_terapeutik_4" onclick="checkthis('weaning_terapeutik_4')" value="Lakukan uji coba penyapihan (30–120 menit dengan nafas spontan yang dibantu ventilator)"><span class="lbl"> Lakukan uji coba penyapihan (30–120 menit dengan nafas spontan yang dibantu ventilator)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_terapeutik][]" id="weaning_terapeutik_5" onclick="checkthis('weaning_terapeutik_5')" value="Gunakan teknik relaksasi"><span class="lbl"> Gunakan teknik relaksasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_terapeutik][]" id="weaning_terapeutik_6" onclick="checkthis('weaning_terapeutik_6')" value="Hindari pemberian sedasi farmakologis selama percobaan penyapihan"><span class="lbl"> Hindari pemberian sedasi farmakologis selama percobaan penyapihan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_terapeutik][]" id="weaning_terapeutik_7" onclick="checkthis('weaning_terapeutik_7')" value="Berikan dukungan psikologis"><span class="lbl"> Berikan dukungan psikologis</span></label></div>
      </td>
    </tr>

    <!-- 3. Edukasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>3</b></td>
      <td style="vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_edukasi][]" id="weaning_edukasi_1" onclick="checkthis('weaning_edukasi_1')" value="Ajarkan cara pengontrolan nafas saat penyapihan"><span class="lbl"> Ajarkan cara pengontrolan nafas saat penyapihan</span></label></div>
      </td>
    </tr>

    <!-- 4. Kolaborasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>4</b></td>
      <td style="vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[weaning_kolaborasi][]" id="weaning_kolaborasi_1" onclick="checkthis('weaning_kolaborasi_1')" value="Kolaborasi pemberian obat yang meningkatkan kepatenan jalan nafas dan pertukaran gas"><span class="lbl"> Kolaborasi pemberian obat yang meningkatkan kepatenan jalan nafas dan pertukaran gas</span></label></div>
      </td>
    </tr>
  </tbody>
</table>

<br>

<!-- PEMANTAUAN RESPIRASI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size:13px; line-height:1.3;">
  <thead>
    <tr style="background-color:#d3d3d3;">
      <th style="width:5%; border:1px solid black; text-align:center;">NO.</th>
      <th style="width:95%; border:1px solid black; text-align:center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Judul -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Pemantauan Respirasi</b><br>
        <i>(Mengumpulkan dan menganalisis data untuk memastikan kepatenan jalan nafas dan keefektifan pertukaran gas)</i><br>
        <b>(I.01014)</b>
      </td>
    </tr>

    <!-- 1. Observasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>1</b></td>
      <td style="vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_observasi][]" id="respirasi_observasi_1" onclick="checkthis('respirasi_observasi_1')" value="Monitor frekuensi, irama, kedalaman, dan upaya napas"><span class="lbl"> Monitor frekuensi, irama, kedalaman, dan upaya napas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_observasi][]" id="respirasi_observasi_2" onclick="checkthis('respirasi_observasi_2')" value="Monitor pola napas (bradipnoe, takipnoe, hiperventilasi, kussmaul, chyne-stokes, biot)"><span class="lbl"> Monitor pola napas (bradipnoe, takipnoe, hiperventilasi, kussmaul, chyne-stokes, biot)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_observasi][]" id="respirasi_observasi_3" onclick="checkthis('respirasi_observasi_3')" value="Monitor kemampuan batuk efektif"><span class="lbl"> Monitor kemampuan batuk efektif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_observasi][]" id="respirasi_observasi_4" onclick="checkthis('respirasi_observasi_4')" value="Monitor adanya produksi sputum"><span class="lbl"> Monitor adanya produksi sputum</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_observasi][]" id="respirasi_observasi_5" onclick="checkthis('respirasi_observasi_5')" value="Monitor adanya sumbatan jalan nafas"><span class="lbl"> Monitor adanya sumbatan jalan nafas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_observasi][]" id="respirasi_observasi_6" onclick="checkthis('respirasi_observasi_6')" value="Palpasi kesimetrisan ekspansi paru"><span class="lbl"> Palpasi kesimetrisan ekspansi paru</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_observasi][]" id="respirasi_observasi_7" onclick="checkthis('respirasi_observasi_7')" value="Auskultasi bunyi napas"><span class="lbl"> Auskultasi bunyi napas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_observasi][]" id="respirasi_observasi_8" onclick="checkthis('respirasi_observasi_8')" value="Monitor saturasi oksigen"><span class="lbl"> Monitor saturasi oksigen</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_observasi][]" id="respirasi_observasi_9" onclick="checkthis('respirasi_observasi_9')" value="Monitor nilai AGD"><span class="lbl"> Monitor nilai AGD</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_observasi][]" id="respirasi_observasi_10" onclick="checkthis('respirasi_observasi_10')" value="Monitor hasil X-Ray thorax"><span class="lbl"> Monitor hasil X-Ray thorax</span></label></div>
      </td>
    </tr>

    <!-- 2. Terapeutik -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>2</b></td>
      <td style="vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_terapeutik][]" id="respirasi_terapeutik_1" onclick="checkthis('respirasi_terapeutik_1')" value="Atur interval pemantauan respirasi sesuai kondisi pasien"><span class="lbl"> Atur interval pemantauan respirasi sesuai kondisi pasien</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_terapeutik][]" id="respirasi_terapeutik_2" onclick="checkthis('respirasi_terapeutik_2')" value="Dokumentasikan hasil pemantauan"><span class="lbl"> Dokumentasikan hasil pemantauan</span></label></div>
      </td>
    </tr>

    <!-- 3. Edukasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>3</b></td>
      <td style="vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_edukasi][]" id="respirasi_edukasi_1" onclick="checkthis('respirasi_edukasi_1')" value="Jelaskan tujuan dan prosedur pemantauan"><span class="lbl"> Jelaskan tujuan dan prosedur pemantauan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_82[respirasi_edukasi][]" id="respirasi_edukasi_2" onclick="checkthis('respirasi_edukasi_2')" value="Informasikan hasil pemantauan"><span class="lbl"> Informasikan hasil pemantauan</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<!-- ----- -->



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
        <input type="text" class="input_type" name="form_82[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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