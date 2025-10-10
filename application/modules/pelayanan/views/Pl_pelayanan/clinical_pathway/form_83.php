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
      var hiddenInputName = 'form_83[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN:<br>RISIKO ASPIRASI</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Definisi:</b> Beresiko mengalami masuknya sekresi gastrointestinal, sekresi orofaring, benda cair atau padat ke dalam saluran trakheobronkial akibat disfungsi mekanisme protektif saluran nafas.
      </td>
    </tr>
  </thead>

  <tbody>
    <!-- FAKTOR RISIKO -->
    <tr>
      <td width="50%" style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif;">
        <b>FAKTOR RISIKO (Dibuktikan dengan):</b><br><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="penurunan_kesadaran" onclick="checkthis('penurunan_kesadaran')" value="Penurunan tingkat kesadaran"><span class="lbl"> Penurunan tingkat kesadaran</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="penurunan_refleks" onclick="checkthis('penurunan_refleks')" value="Penurunan refleks muntah dan/atau batuk"><span class="lbl"> Penurunan refleks muntah dan/atau batuk</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="gangguan_menelan" onclick="checkthis('gangguan_menelan')" value="Gangguan menelan"><span class="lbl"> Gangguan menelan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="disfagia" onclick="checkthis('disfagia')" value="Disfagia"><span class="lbl"> Disfagia</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="kerusakan_mobilitas" onclick="checkthis('kerusakan_mobilitas')" value="Kerusakan mobilitas fisik"><span class="lbl"> Kerusakan mobilitas fisik</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="residu_lambung" onclick="checkthis('residu_lambung')" value="Peningkatan residu lambung"><span class="lbl"> Peningkatan residu lambung</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="tekanan_intragastik" onclick="checkthis('tekanan_intragastik')" value="Peningkatan tekanan intragastik"><span class="lbl"> Peningkatan tekanan intragastik</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="motilitas_gi" onclick="checkthis('motilitas_gi')" value="Penurunan motilitas gastrointestinal"><span class="lbl"> Penurunan motilitas gastrointestinal</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="sf_inkompeten" onclick="checkthis('sf_inkompeten')" value="Sfingter esofagus bawah inkompeten"><span class="lbl"> Sfingter esofagus bawah inkompeten</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="pengosongan_lambung" onclick="checkthis('pengosongan_lambung')" value="Perlambatan pengosongan lambung"><span class="lbl"> Perlambatan pengosongan lambung</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="nasogastrik" onclick="checkthis('nasogastrik')" value="Terpasang selang nasogastrik"><span class="lbl"> Terpasang selang nasogastrik</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="tracheostomi" onclick="checkthis('tracheostomi')" value="Terpasang tracheostomi/endotrachea tube"><span class="lbl"> Terpasang tracheostomi/endotrachea tube</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="trauma_leher" onclick="checkthis('trauma_leher')" value="Trauma/pembedahan leher, mulut, dan atau wajah"><span class="lbl"> Trauma/pembedahan leher, mulut, dan atau wajah</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="efek_farmako" onclick="checkthis('efek_farmako')" value="Efek agen farmakologis"><span class="lbl"> Efek agen farmakologis</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[faktor_risiko][]" id="ketidakmatangan_koordinasi" onclick="checkthis('ketidakmatangan_koordinasi')" value="Ketidakmatangan koordinasi menghisap, menelan dan bernafas"><span class="lbl"> Ketidakmatangan koordinasi menghisap, menelan dan bernafas</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_83[intervensi_selama]" id="intervensi_selama" onchange="fillthis('intervensi_selama')" style="width:10%;">,
          Tingkat Aspirasi (L.01006) menurun dengan kriteria hasil:</b><br><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[kriteria_hasil][]" id="hasil_kesadaran" onclick="checkthis('hasil_kesadaran')" value="Tingkat kesadaran meningkat"><span class="lbl"> Tingkat kesadaran meningkat</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[kriteria_hasil][]" id="hasil_menelan" onclick="checkthis('hasil_menelan')" value="Kemampuan menelan meningkat"><span class="lbl"> Kemampuan menelan meningkat</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[kriteria_hasil][]" id="hasil_mulut" onclick="checkthis('hasil_mulut')" value="Kebersihan mulut meningkat"><span class="lbl"> Kebersihan mulut meningkat</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[kriteria_hasil][]" id="hasil_dispnoe" onclick="checkthis('hasil_dispnoe')" value="Dispnoe menurun"><span class="lbl"> Dispnoe menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[kriteria_hasil][]" id="hasil_kelemahan" onclick="checkthis('hasil_kelemahan')" value="Kelemahan otot menurun"><span class="lbl"> Kelemahan otot menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[kriteria_hasil][]" id="hasil_sekret" onclick="checkthis('hasil_sekret')" value="Akumulasi sekret menurun"><span class="lbl"> Akumulasi sekret menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[kriteria_hasil][]" id="hasil_wheezing" onclick="checkthis('hasil_wheezing')" value="Wheezing menurun"><span class="lbl"> Wheezing menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[kriteria_hasil][]" id="hasil_batuk" onclick="checkthis('hasil_batuk')" value="Batuk menurun"><span class="lbl"> Batuk menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[kriteria_hasil][]" id="hasil_otot_aksesori" onclick="checkthis('hasil_otot_aksesori')" value="Penggunaan otot aksesoris menurun"><span class="lbl"> Penggunaan otot aksesoris menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[kriteria_hasil][]" id="hasil_sianosis" onclick="checkthis('hasil_sianosis')" value="Sianosis menurun"><span class="lbl"> Sianosis menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[kriteria_hasil][]" id="hasil_gelisah" onclick="checkthis('hasil_gelisah')" value="Gelisah menurun"><span class="lbl"> Gelisah menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[kriteria_hasil][]" id="hasil_frekuensi" onclick="checkthis('hasil_frekuensi')" value="Frekuensi nafas membaik"><span class="lbl"> Frekuensi nafas membaik</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->



<!-- MANAJEMEN JALAN NAFAS -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse; font-size:13px; line-height:1.3;">
  <thead>
    <tr style="background-color:#d3d3d3;">
      <th style="width:5%; text-align:center; border:1px solid black;">NO.</th>
      <th style="width:95%; text-align:center; border:1px solid black;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Manajemen Jalan Nafas</b><br>
        <i>(Mengidentifikasi & mengelola selang ETT dan TT)</i><br>
        <b>(I.01012)</b>
      </td>
    </tr>

    <!-- 1. Observasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>1</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Observasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_observasi][]" id="nafas_obs1" onclick="checkthis('nafas_obs1')" value="Monitor pola nafas (frekuensi, kedalaman, usaha nafas) tiap"><span class="lbl"> Monitor pola nafas (frekuensi, kedalaman, usaha nafas) tiap
            <input type="text" class="input_type" name="form_83[nafas_obs1_ket]" id="nafas_obs1_ket" onchange="fillthis('nafas_obs1_ket')" placeholder=".........." style="width:120px; margin-left:5px;">
        </span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_observasi][]" id="nafas_obs2" onclick="checkthis('nafas_obs2')" value="Monitor bunyi nafas tambahan (gurgling, mengi, wheezing, ronki kering) tiap"><span class="lbl"> Monitor bunyi nafas tambahan (mis: gurgling, mengi, wheezing, ronki kering) tiap
            <input type="text" class="input_type" name="form_83[nafas_obs2_ket]" id="nafas_obs2_ket" onchange="fillthis('nafas_obs2_ket')" placeholder=".........." style="width:120px; margin-left:5px;">
        </span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_observasi][]" id="nafas_obs3" onclick="checkthis('nafas_obs3')" value="Monitor sputum (jumlah, warna, aroma) tiap"><span class="lbl"> Monitor sputum (jumlah, warna, aroma) tiap
            <input type="text" class="input_type" name="form_83[nafas_obs3_ket]" id="nafas_obs3_ket" onchange="fillthis('nafas_obs3_ket')" placeholder=".........." style="width:120px; margin-left:5px;">
        </span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_observasi][]" id="nafas_obs4" onclick="checkthis('nafas_obs4')" value="Observasi Lainnya"><span class="lbl"> Lainnya...
            <input type="text" class="input_type" name="form_83[nafas_obs4_ket]" id="nafas_obs4_ket" onchange="fillthis('nafas_obs4_ket')" placeholder=".........." style="width:120px; margin-left:5px;">
        </span></label></div>

      </td>
    </tr>

    <!-- 2. Terapeutik -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>2</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Terapeutik</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_terapeutik][]" id="nafas_ter1" onclick="checkthis('nafas_ter1')" value="Pertahankan kepatenan jalan nafas dengan head-tilt dan chin-lift (jaw-thrust jika curiga trauma cervical)"><span class="lbl"> Pertahankan kepatenan jalan nafas dengan head-tilt dan chin-lift (jaw-thrust jika curiga trauma cervical)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_terapeutik][]" id="nafas_ter2" onclick="checkthis('nafas_ter2')" value="Posisikan semi-Fowler atau Fowler"><span class="lbl"> Posisikan semi-Fowler atau Fowler</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_terapeutik][]" id="nafas_ter3" onclick="checkthis('nafas_ter3')" value="Berikan minum hangat berjalan"><span class="lbl"> Berikan minum hangat berjalan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_terapeutik][]" id="nafas_ter4" onclick="checkthis('nafas_ter4')" value="Lakukan fisioterapi dada"><span class="lbl"> Lakukan fisioterapi dada</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_terapeutik][]" id="nafas_ter5" onclick="checkthis('nafas_ter5')" value="Lakukan penghisapan lendir kurang dari 15 detik"><span class="lbl"> Lakukan penghisapan lendir kurang dari 15 detik</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_terapeutik][]" id="nafas_ter6" onclick="checkthis('nafas_ter6')" value="Lakukan hiperoksigenasi sebelum penghisapan endotrakheal"><span class="lbl"> Lakukan hiperoksigenasi sebelum penghisapan endotrakheal</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_terapeutik][]" id="nafas_ter7" onclick="checkthis('nafas_ter7')" value="Keluarkan sumbatan benda padat dengan forsep McGill"><span class="lbl"> Keluarkan sumbatan benda padat dengan forsep McGill</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_terapeutik][]" id="nafas_ter8" onclick="checkthis('nafas_ter8')" value="Berikan oksigen"><span class="lbl"> Berikan oksigen</span></label></div>
        
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_terapeutik][]" id="nafas_ter9" onclick="checkthis('nafas_ter9')" value="Terapeutik Lainnya"><span class="lbl"> Lainnya...
            <input type="text" class="input_type" name="form_83[nafas_ter9_ket]" id="nafas_ter9_ket" onchange="fillthis('nafas_ter9_ket')" placeholder=".........." style="width:120px; margin-left:5px;">
        </span></label></div>

      </td>
    </tr>

    <!-- 3. Edukasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>3</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Edukasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_edukasi][]" id="nafas_eduk1" onclick="checkthis('nafas_eduk1')" value="Anjurkan asupan cairan 2000ml/hari, jika tidak kontraindikasi"><span class="lbl"> Anjurkan asupan cairan 2000ml/hari, jika tidak kontraindikasi</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_edukasi][]" id="nafas_eduk2" onclick="checkthis('nafas_eduk2')" value="Ajarkan teknik batuk efektif"><span class="lbl"> Ajarkan teknik batuk efektif</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_edukasi][]" id="nafas_eduk3" onclick="checkthis('nafas_eduk3')" value="Edukasi Lainnya"><span class="lbl"> Lainnya...
            <input type="text" class="input_type" name="form_83[nafas_eduk3_ket]" id="nafas_eduk3_ket" onchange="fillthis('nafas_eduk3_ket')" placeholder=".........." style="width:120px; margin-left:5px;">
        </span></label></div>

      </td>
    </tr>

    <!-- 4. Kolaborasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>4</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Kolaborasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_kolaborasi][]" id="nafas_kolab1" onclick="checkthis('nafas_kolab1')" value="Kolaborasi pemberian bronkodilator, ekspektoran, mukolitik"><span class="lbl"> Kolaborasi pemberian bronkodilator, ekspektoran, mukolitik</span></label></div>
        
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[jalan_nafas_observasi][]" id="nafas_kolab2" onclick="checkthis('nafas_kolab2')" value="Kolaborasi Lainnya"><span class="lbl"> Lainnya...
            <input type="text" class="input_type" name="form_83[nafas_kolab2_ket]" id="nafas_kolab2_ket" onchange="fillthis('nafas_kolab2_ket')" placeholder=".........." style="width:120px; margin-left:5px;">
        </span></label></div>

      </td>
    </tr>
  </tbody>
</table>

<br>

<!-- PENCEGAHAN ASPIRASI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse; font-size:13px; line-height:1.3;">
  <thead>
    <tr style="background-color:#d3d3d3;">
      <th style="width:5%; text-align:center; border:1px solid black;">NO.</th>
      <th style="width:95%; text-align:center; border:1px solid black;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Pencegahan Aspirasi</b><br>
        <i>(Mengidentifikasi & mengurangi risiko masuknya partikel makanan/cairan ke dalam paru-paru)</i><br>
        <b>(I.01018)</b>
      </td>
    </tr>
<!-- 
    <tr>
      <td style="text-align:center;"><b>1</b></td>
      <td>
        <b>Observasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_observasi][]" id="asp_obs1" onclick="checkthis('asp_obs1')" value="Monitor tingkat kesadaran, batuk, muntah dan kemampuan menelan tiap ..."><span class="lbl"> Monitor tingkat kesadaran, batuk, muntah dan kemampuan menelan tiap …...</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_observasi][]" id="asp_obs2" onclick="checkthis('asp_obs2')" value="Monitor status pernafasan"><span class="lbl"> Monitor status pernafasan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_observasi][]" id="asp_obs3" onclick="checkthis('asp_obs3')" value="Monitor bunyi nafas, terutama setelah makan/minum tiap ..."><span class="lbl"> Monitor bunyi nafas, terutama setelah makan/minum tiap …...</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_observasi][]" id="asp_obs4" onclick="checkthis('asp_obs4')" value="Periksa residu gaster sebelum memberi asupan oral"><span class="lbl"> Periksa residu gaster sebelum memberi asupan oral</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_observasi][]" id="asp_obs5" onclick="checkthis('asp_obs5')" value="Periksa kepatenan NGT sebelum memberi asupan oral"><span class="lbl"> Periksa kepatenan NGT sebelum memberi asupan oral</span></label></div>
      </td>
    </tr> -->

    <!-- Observasi -->
<tr>
  <td style="text-align:center; vertical-align:top;"><b>1</b></td>
  <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
    <b>Observasi</b><br>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_83[aspirasi_observasi][]" id="asp_obs1" onclick="checkthis('asp_obs1')" value="Monitor tingkat kesadaran, batuk, muntah dan kemampuan menelan tiap">
        <span class="lbl">
          Monitor tingkat kesadaran, batuk, muntah dan kemampuan menelan tiap
          <input type="text" class="input_type" name="form_83[asp_obs1_input]" id="asp_obs1_input" onchange="fillthis('asp_obs1_input')" placeholder=".........." style="width:120px; margin-left:5px;">
        </span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_83[aspirasi_observasi][]" id="asp_obs2" onclick="checkthis('asp_obs2')" value="Monitor status pernafasan">
        <span class="lbl"> Monitor status pernafasan</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_83[aspirasi_observasi][]" id="asp_obs3" onclick="checkthis('asp_obs3')" value="Monitor bunyi nafas, terutama setelah makan/minum tiap">
        <span class="lbl">
          Monitor bunyi nafas, terutama setelah makan/minum tiap
          <input type="text" class="input_type" name="form_83[asp_obs3_input]" id="asp_obs3_input" placeholder=".........." onchange="fillthis('asp_obs3_input')" style="width:120px; margin-left:5px;">
        </span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_83[aspirasi_observasi][]" id="asp_obs4" onclick="checkthis('asp_obs4')" value="Periksa residu gaster sebelum memberi asupan oral">
        <span class="lbl"> Periksa residu gaster sebelum memberi asupan oral</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_83[aspirasi_observasi][]" id="asp_obs5" onclick="checkthis('asp_obs5')" value="Periksa kepatenan NGT sebelum memberi asupan oral">
        <span class="lbl"> Periksa kepatenan NGT sebelum memberi asupan oral</span>
      </label>
    </div>

  </td>
</tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>2</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_terapeutik][]" id="asp_ter1" onclick="checkthis('asp_ter1')" value="Posisikan semi fowler (30–45°) 30 menit sebelum memberikan asupan oral"><span class="lbl"> Posisikan semi fowler (30–45°) 30 menit sebelum memberikan asupan oral</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_terapeutik][]" id="asp_ter2" onclick="checkthis('asp_ter2')" value="Pertahankan posisi semi fowler (30–45°) pada pasien tidak sadar"><span class="lbl"> Pertahankan posisi semi fowler (30–45°) pada pasien tidak sadar</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_terapeutik][]" id="asp_ter3" onclick="checkthis('asp_ter3')" value="Pertahankan kepatenan jalan nafas"><span class="lbl"> Pertahankan kepatenan jalan nafas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_terapeutik][]" id="asp_ter4" onclick="checkthis('asp_ter4')" value="Pertahankan pengembangan balon ETT"><span class="lbl"> Pertahankan pengembangan balon ETT</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_terapeutik][]" id="asp_ter5" onclick="checkthis('asp_ter5')" value="Sediakan suction diruangan"><span class="lbl"> Sediakan suction diruangan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_terapeutik][]" id="asp_ter6" onclick="checkthis('asp_ter6')" value="Lakukan penghisapan jalan napas jika produksi sekret meningkat"><span class="lbl"> Lakukan penghisapan jalan napas, jika produksi sekret meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_terapeutik][]" id="asp_ter7" onclick="checkthis('asp_ter7')" value="Hindari memberi makan melalui NGT jika residu banyak"><span class="lbl"> Hindari memberi makan melalui NGT jika residu banyak</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_terapeutik][]" id="asp_ter8" onclick="checkthis('asp_ter8')" value="Berikan makanan yang kecil atau lunak"><span class="lbl"> Berikan makanan yang kecil atau lunak</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_terapeutik][]" id="asp_ter9" onclick="checkthis('asp_ter9')" value="Berikan obat oral dalam bentuk cair"><span class="lbl"> Berikan obat oral dalam bentuk cair</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>3</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_edukasi][]" id="asp_eduk1" onclick="checkthis('asp_eduk1')" value="Anjurkan makan secara perlahan"><span class="lbl"> Anjurkan makan secara perlahan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_edukasi][]" id="asp_eduk2" onclick="checkthis('asp_eduk2')" value="Ajarkan strategi mencegah aspirasi"><span class="lbl"> Ajarkan strategi mencegah aspirasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_edukasi][]" id="asp_eduk3" onclick="checkthis('asp_eduk3')" value="Ajarkan teknik mengunyah atau menelan"><span class="lbl"> Ajarkan teknik mengunyah atau menelan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[aspirasi_edukasi][]" id="asp_eduk4" onclick="checkthis('asp_eduk4')" value="Edukasi Lainnya"><span class="lbl"> Lainnya...
            <input type="text" class="input_type" name="form_83[asp_eduk4_ket]" id="asp_eduk4_ket" onchange="fillthis('asp_eduk4_ket')" placeholder=".........." style="width:120px; margin-left:5px;">
        </span></label></div>

      </td>
      
    </tr>
  </tbody>
</table>

<br>

<!-- MANAJEMEN JALAN NAFAS BUATAN -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse; font-size:13px; line-height:1.3;">
  <thead>
    <tr style="background-color:#d3d3d3;">
      <th style="width:5%; text-align:center; border:1px solid black;">NO.</th>
      <th style="width:95%; text-align:center; border:1px solid black;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Manajemen Jalan Nafas Buatan</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>1</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_observasi][]" id="nb_obs1" onclick="checkthis('nb_obs1')" value="Monitor posisi selang endotrakeal (ETT), terutama setelah mengubah posisi"><span class="lbl"> Monitor posisi selang endotrakeal (ETT), terutama setelah mengubah posisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_observasi][]" id="nb_obs2" onclick="checkthis('nb_obs2')" value="Monitor tekanan balon ETT setiap 4–8 jam"><span class="lbl"> Monitor tekanan balon ETT setiap 4–8 jam</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_observasi][]" id="nb_obs3" onclick="checkthis('nb_obs3')" value="Monitor kulit area stoma trakeostomi (kemerahan, drainage, perdarahan)"><span class="lbl"> Monitor kulit area stoma trakeostomi (misal: kemerahan, drainage, perdarahan)</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>2</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_terapeutik][]" id="nb_ter1" onclick="checkthis('nb_ter1')" value="Kurangi tekanan balon secara periodik tiap shift"><span class="lbl"> Kurangi tekanan balon secara periodik tiap shift</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_terapeutik][]" id="nb_ter2" onclick="checkthis('nb_ter2')" value="Pasang oropharyngeal airway (OPA) untuk mencegah ETT tergigit"><span class="lbl"> Pasang oropharyngeal airway (OPA) untuk mencegah ETT tergigit</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_terapeutik][]" id="nb_ter3" onclick="checkthis('nb_ter3')" value="Cegah ETT terlipat (kinking)"><span class="lbl"> Cegah ETT terlipat (kinking)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_terapeutik][]" id="nb_ter4" onclick="checkthis('nb_ter4')" value="Berikan preoksigenasi 100% selama 30 detik sebelum dan setelah suction"><span class="lbl"> Berikan preoksigenasi 100% selama 30 detik (3–6 kali ventilasi) sebelum dan setelah suction</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_terapeutik][]" id="nb_ter5" onclick="checkthis('nb_ter5')" value="Lakukan penghisapan lendir kurang dari 15 detik jika diperlukan"><span class="lbl"> Lakukan penghisapan lendir kurang dari 15 detik jika diperlukan (bukan secara berkala/rutin)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_terapeutik][]" id="nb_ter6" onclick="checkthis('nb_ter6')" value="Ganti fiksasi ETT setiap 24 jam"><span class="lbl"> Ganti fiksasi ETT setiap 24 jam</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_terapeutik][]" id="nb_ter7" onclick="checkthis('nb_ter7')" value="Ubah posisi ETT secara bergantian setiap 24 jam"><span class="lbl"> Ubah posisi ETT secara bergantian (kiri dan kanan) setiap 24 jam</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_terapeutik][]" id="nb_ter8" onclick="checkthis('nb_ter8')" value="Lakukan perawatan mulut"><span class="lbl"> Lakukan perawatan mulut (mis. dengan sikat gigi, kassa, dan pelembab bibir)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_terapeutik][]" id="nb_ter9" onclick="checkthis('nb_ter9')" value="Lakukan perawatan stoma trakeostomi"><span class="lbl"> Lakukan perawatan Stoma trakeostomi</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>3</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_edukasi][]" id="nb_eduk1" onclick="checkthis('nb_eduk1')" value="Jelaskan pasien dan/atau keluarga tujuan serta prosedur pemasangan jalan nafas buatan"><span class="lbl"> Jelaskan pasien dan/atau keluarga tujuan dan prosedur pemasangan jalan nafas buatan</span></label></div>
        
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_edukasi][]" id="nb_eduk2" onclick="checkthis('nb_eduk2')" value="Kolaborasi Lainnya"><span class="lbl"> Lainnya...
            <input type="text" class="input_type" name="form_83[nb_eduk2_ket]" id="nb_eduk2_ket" onchange="fillthis('nb_eduk2_ket')" placeholder=".........." style="width:120px; margin-left:5px;">
        </span></label></div>
    
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>4</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_kolaborasi][]" id="nb_kolab1" onclick="checkthis('nb_kolab1')" value="Kolaborasi intubasi ulang jika terbentuk mucous plug yang tidak dapat dilakukan penghisapan"><span class="lbl"> Kolaborasi intubasi ulang jika terbentuk mucous plug yang tidak dapat dilakukan penghisapan</span></label></div>
      
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_83[nafas_buatan_kolaborasi][]" id="nb_kolab2" onclick="checkthis('nb_kolab2')" value="Kolaborasi Lainnya"><span class="lbl"> Lainnya...
            <input type="text" class="input_type" name="form_83[nb_kolab2_ket]" id="nb_kolab2_ket" onchange="fillthis('nb_kolab2_ket')" placeholder=".........." style="width:120px; margin-left:5px;">
        </span></label></div>
        
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
        <input type="text" class="input_type" name="form_83[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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