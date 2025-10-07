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
      var hiddenInputName = 'form_68[ttd_' + role + ']';
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
<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN:<br> HIPERVOLEMIA</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
        Definisi: Peningkatan volume cairan intravaskuler, interstisial dan atau intraseluler
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
<!-- PENYEBAB -->
<td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
  <b>PENYEBAB / Berhubungan dengan:</b><br>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[penyebab][]" id="penyebab_gangguan_regulasi" onclick="checkthis('penyebab_gangguan_regulasi')" value="Gangguan Mekanisme regulasi"><span class="lbl"> Gangguan Mekanisme regulasi</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[penyebab][]" id="penyebab_asupan_cairan" onclick="checkthis('penyebab_asupan_cairan')" value="Kelebihan asupan cairan"><span class="lbl"> Kelebihan asupan cairan</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[penyebab][]" id="penyebab_asupan_natrium" onclick="checkthis('penyebab_asupan_natrium')" value="Kelebihan asupan natrium"><span class="lbl"> Kelebihan asupan natrium</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[penyebab][]" id="penyebab_aliran_vena" onclick="checkthis('penyebab_aliran_vena')" value="Gangguan aliran balik vena"><span class="lbl"> Gangguan aliran balik vena</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[penyebab][]" id="penyebab_efek_farmakologis" onclick="checkthis('penyebab_efek_farmakologis')" value="Efek agen farmakologis (misal kortikosteroid, chlorpropamide, tolbutamide, vincristine, tryptilines, carbamazepin)"><span class="lbl"> Efek agen farmakologis (misal kortikosteroid, chlorpropamide, tolbutamide, vincristine, tryptilines, carbamazepin)</span></label></div>
</td>

<!-- KRITERIA HASIL -->
<td style="border: 1px solid black; padding: 5px; vertical-align: top;">
  <b>Setelah dilakukan intervensi selama 
    <input type="text" class="input_type" name="form_68[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;"> 
    maka keseimbangan cairan meningkat (L.03020), dengan kriteria hasil :</b>
  <div style="display: flex; flex-wrap: wrap;">
    <div style="width: 50%;">
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_asupan_cairan" onclick="checkthis('hasil_asupan_cairan')" value="Asupan cairan meningkat"><span class="lbl"> Asupan cairan meningkat</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_haluaran_urine" onclick="checkthis('hasil_haluaran_urine')" value="Haluaran urine meningkat"><span class="lbl"> Haluaran urine meningkat</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_membran_mukosa" onclick="checkthis('hasil_membran_mukosa')" value="Kelembaban membran mukosa meningkat"><span class="lbl"> Kelembaban membran mukosa meningkat</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_asupan_makanan" onclick="checkthis('hasil_asupan_makanan')" value="Asupan makanan meningkat"><span class="lbl"> Asupan makanan meningkat</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_edema" onclick="checkthis('hasil_edema')" value="Edema menurun"><span class="lbl"> Edema menurun</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_dehidrasi" onclick="checkthis('hasil_dehidrasi')" value="Dehidrasi menurun"><span class="lbl"> Dehidrasi menurun</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_asites" onclick="checkthis('hasil_asites')" value="Asites menurun"><span class="lbl"> Asites menurun</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_konfulsi" onclick="checkthis('hasil_konfulsi')" value="Konfulsi menurun"><span class="lbl"> Konfulsi menurun</span></label></div>
    </div>
    <div style="width: 45%;">
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_tekanan_darah" onclick="checkthis('hasil_tekanan_darah')" value="Tekanan darah membaik"><span class="lbl"> Tekanan darah membaik</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_nadi" onclick="checkthis('hasil_nadi')" value="Denyut nadi radial membaik"><span class="lbl"> Denyut nadi radial membaik</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_tekanan_arteri" onclick="checkthis('hasil_tekanan_arteri')" value="Tekanan arteri rata-rata membaik"><span class="lbl"> Tekanan arteri rata-rata membaik</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_membran_mukosa_baik" onclick="checkthis('hasil_membran_mukosa_baik')" value="Membran mukosa membaik"><span class="lbl"> Membran mukosa membaik</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_mata_cekung" onclick="checkthis('hasil_mata_cekung')" value="Mata cekung membaik"><span class="lbl"> Mata cekung membaik</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_turgor" onclick="checkthis('hasil_turgor')" value="Turgor kulit membaik"><span class="lbl"> Turgor kulit membaik</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[kriteria_hasil][]" id="hasil_berat_badan" onclick="checkthis('hasil_berat_badan')" value="Berat badan membaik"><span class="lbl"> Berat badan membaik</span></label></div>
    </div>
  </div>
</td>
</tr>
    <!-- TANDA & GEJALA MAYOR - OBJEKTIF -->
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Dibuktikan dengan :</b>

    <!-- GEJALA MAYOR -->
    <p><b>Tanda dan Gejala Mayor</b></p>
    <div class="row">
      <!-- Subjektif -->
      <div class="col-md-6">
        <b><i>Subjektif:</i></b><br>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[mayor_subjektif][]" id="mayor_subjektif_ortopnea" onclick="checkthis('mayor_subjektif_ortopnea')" value="Ortopnea">
          <span class="lbl"> Ortopnea</span>
        </label></div>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[mayor_subjektif][]" id="mayor_subjektif_dispnea" onclick="checkthis('mayor_subjektif_dispnea')" value="Dispnea">
          <span class="lbl"> Dispnea</span>
        </label></div>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[mayor_subjektif][]" id="mayor_subjektif_pnd" onclick="checkthis('mayor_subjektif_pnd')" value="Peroxysmal nocturnal dyspnea (PND)">
          <span class="lbl"> Peroxysmal nocturnal dyspnea (PND)</span>
        </label></div>
      </div>

      <!-- Objektif -->
      <div class="col-md-6">
        <b>Objektif:</b><br>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[mayor_objektif][]" id="mayor_objektif_edema" onclick="checkthis('mayor_objektif_edema')" value="Edema anasarka dan/atau edema perifer">
          <span class="lbl"> Edema anasarka dan/atau edema perifer</span>
        </label></div>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[mayor_objektif][]" id="mayor_objektif_bb" onclick="checkthis('mayor_objektif_bb')" value="Berat badan meningkat dalam waktu singkat">
          <span class="lbl"> Berat badan meningkat dalam waktu singkat</span>
        </label></div>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[mayor_objektif][]" id="mayor_objektif_jvp_cvp" onclick="checkthis('mayor_objektif_jvp_cvp')" value="JVP dan/atau CVP meningkat">
          <span class="lbl"> <i>Jugular Venous Pressure</i> (JVP) dan/atau <i>Central Venous Pressure</i> (CVP) meningkat</span>
        </label></div>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[mayor_objektif][]" id="mayor_objektif_refleks" onclick="checkthis('mayor_objektif_refleks')" value="Refleks hepatojugular positif">
          <span class="lbl"> Refleks hepatojugular positif</span>
        </label></div>
      </div>
    </div>

    <hr>

    <!-- GEJALA MINOR -->
    <p><b>Tanda dan Gejala Minor</b></p>
    <div class="row">
      <!-- Subjektif -->
      <div class="col-md-6">
        <b><i>Subjektif:</i></b><br>
        <i>(Tidak tersedia)</i>
      </div>

      <!-- Objektif -->
      <div class="col-md-6">
        <b>Objektif:</b><br>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[minor_objektif][]" id="minor_objektif_distensi" onclick="checkthis('minor_objektif_distensi')" value="Distensi vena jugularis">
          <span class="lbl"> Distensi vena jugularis</span>
        </label></div>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[minor_objektif][]" id="minor_objektif_suara" onclick="checkthis('minor_objektif_suara')" value="Terdengar suara nafas tambahan">
          <span class="lbl"> Terdengar suara nafas tambahan</span>
        </label></div>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[minor_objektif][]" id="minor_objektif_hepatomegali" onclick="checkthis('minor_objektif_hepatomegali')" value="Hepatomegali">
          <span class="lbl"> Hepatomegali</span>
        </label></div>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[minor_objektif][]" id="minor_objektif_hb_ht" onclick="checkthis('minor_objektif_hb_ht')" value="Kadar Hb/Ht turun">
          <span class="lbl"> Kadar Hb/Ht turun</span>
        </label></div>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[minor_objektif][]" id="minor_objektif_oliguria" onclick="checkthis('minor_objektif_oliguria')" value="Oliguria">
          <span class="lbl"> Oliguria</span>
        </label></div>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[minor_objektif][]" id="minor_objektif_intake_output" onclick="checkthis('minor_objektif_intake_output')" value="Intake lebih banyak dari output">
          <span class="lbl"> Intake lebih banyak dari output</span>
        </label></div>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[minor_objektif][]" id="minor_objektif_balans" onclick="checkthis('minor_objektif_balans')" value="Balans cairan positif">
          <span class="lbl"> Balans cairan positif</span>
        </label></div>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_68[minor_objektif][]" id="minor_objektif_kongesti" onclick="checkthis('minor_objektif_kongesti')" value="Kongesti paru">
          <span class="lbl"> Kongesti paru</span>
        </label></div>
      </div>
    </div>
  </td>
</tr>
<br>

<!-- MANAJEMEN HIPERVOLEMIA -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <tbody>
    <!-- JUDUL -->
 <tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <!-- <table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;"> -->
      <thead>
        <tr style="background-color: #d3d3d3;">
          <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
          <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
        </tr>
      </thead>
      <tbody>
  </tr>
<!-- Manajemen Hiperglikemia -->
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
       <b>MANAJEMEN HIPERVOLEMIA</b>
        <i>(Mengidentifikasi dan mengelola kelebihan volume cairan intravaskler dan ekstravaskuler serta mencegah terjadinya komplikasi)</i>
        <b>(I.03114)</b>
  </td>
</tr>
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>TINDAKAN</b>
      </td>
    </tr>

    <!-- OBSERVASI -->
    <tr>
      <td style="width: 5%; text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>1</b></td>
      <td style="border: 1px solid black; padding: 5px;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_observasi][]" id="hipervolemia_observasi_1" onclick="checkthis('hipervolemia_observasi_1')" value="Periksa tanda dan gejala hipervolemia"><span class="lbl"> Periksa tanda dan gejala hipervolemia (misal ortopnea, dispnea, edema, JVP/CVP meningkat, refleks hepatojugular positif, suara napas tambahan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_observasi][]" id="hipervolemia_observasi_2" onclick="checkthis('hipervolemia_observasi_2')" value="Identifikasi penyebab hipervolemia"><span class="lbl"> Identifikasi penyebab hipervolemia</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_observasi][]" id="hipervolemia_observasi_3" onclick="checkthis('hipervolemia_observasi_3')" value="Monitor status hemodinamik"><span class="lbl"> Monitor status hemodinamik (misal frekuensi jantung, tekanan darah, MAP, CVP, JVP, PAP, PCWP, CO, CI) jika tersedia</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_observasi][]" id="hipervolemia_observasi_4" onclick="checkthis('hipervolemia_observasi_4')" value="Monitor intake dan output cairan"><span class="lbl"> Monitor intake dan output cairan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_observasi][]" id="hipervolemia_observasi_5" onclick="checkthis('hipervolemia_observasi_5')" value="Monitor tanda hemokonsentrasi"><span class="lbl"> Monitor tanda hemokonsentrasi (misal kadar natrium, BUN, hematokrit, berat jenis urin)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_observasi][]" id="hipervolemia_observasi_6" onclick="checkthis('hipervolemia_observasi_6')" value="Monitor tanda peningkatan tekanan onkotik"><span class="lbl"> Monitor tanda peningkatan tekanan onkotik plasma (misal kadar protein dan albumin meningkat)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_observasi][]" id="hipervolemia_observasi_7" onclick="checkthis('hipervolemia_observasi_7')" value="Monitor kecepatan infus"><span class="lbl"> Monitor kecepatan infus secara ketat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_observasi][]" id="hipervolemia_observasi_8" onclick="checkthis('hipervolemia_observasi_8')" value="Monitor efek samping diuretik"><span class="lbl"> Monitor efek samping diuretik (misal hipotensi ortostatik, hipovolemia, hipokalemia, hiponatremia)</span></label></div>
      </td>
    </tr>

    <!-- TERAPEUTIK -->
    <tr>
      <td style="text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>2</b></td>
      <td style="border: 1px solid black; padding: 5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_terapeutik][]" id="hipervolemia_terapeutik_1" onclick="checkthis('hipervolemia_terapeutik_1')" value="Timbang berat badan"><span class="lbl"> Timbang berat badan setiap hari pada waktu yang sama</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_terapeutik][]" id="hipervolemia_terapeutik_2" onclick="checkthis('hipervolemia_terapeutik_2')" value="Batasi asupan cairan dan garam"><span class="lbl"> Batasi asupan cairan dan garam</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_terapeutik][]" id="hipervolemia_terapeutik_3" onclick="checkthis('hipervolemia_terapeutik_3')" value="Tinggikan kepala tempat tidur"><span class="lbl"> Tinggikan kepala tempat tidur 30–40°</span></label></div>
      </td>
    </tr>

    <!-- EDUKASI -->
    <tr>
      <td style="text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>3</b></td>
      <td style="border: 1px solid black; padding: 5px;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_edukasi][]" id="hipervolemia_edukasi_1" onclick="checkthis('hipervolemia_edukasi_1')" value="Anjurkan melapor jika urine < 0.5 ml/kg/jam"><span class="lbl"> Anjurkan melapor jika haluaran urine kurang dari 0,5 ml/kg/jam dalam 6 jam</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_edukasi][]" id="hipervolemia_edukasi_2" onclick="checkthis('hipervolemia_edukasi_2')" value="Anjurkan melapor jika BB naik >1kg"><span class="lbl"> Anjurkan melapor jika berat badan bertambah lebih dari 1 kg dalam sehari</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_edukasi][]" id="hipervolemia_edukasi_3" onclick="checkthis('hipervolemia_edukasi_3')" value="Ajarkan catat intake-output"><span class="lbl"> Ajarkan cara mengukur dan mencatat asupan dan haluaran cairan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_edukasi][]" id="hipervolemia_edukasi_4" onclick="checkthis('hipervolemia_edukasi_4')" value="Ajarkan cara membatasi cairan"><span class="lbl"> Ajarkan cara membatasi cairan</span></label></div>
      </td>
    </tr>

    <!-- KOLABORASI -->
    <tr>
      <td style="text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>4</b></td>
      <td style="border: 1px solid black; padding: 5px;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_kolaborasi][]" id="hipervolemia_kolaborasi_1" onclick="checkthis('hipervolemia_kolaborasi_1')" value="Kolaborasi pemberian diuretik"><span class="lbl"> Kolaborasi pemberian diuretik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_kolaborasi][]" id="hipervolemia_kolaborasi_2" onclick="checkthis('hipervolemia_kolaborasi_2')" value="Kolaborasi penggantian kalium"><span class="lbl"> Kolaborasi penggantian kehilangan kalium akibat diuretik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[hipervolemia_kolaborasi][]" id="hipervolemia_kolaborasi_3" onclick="checkthis('hipervolemia_kolaborasi_3')" value="Kolaborasi pemberian CRRT"><span class="lbl"> Kolaborasi pemberian continuous renal replacement therapy (CRRT), jika perlu</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<br>

<!-- PEMANTAUAN CAIRAN -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <tbody>
    <!-- JUDUL UTAMA -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>PEMANTAUAN CAIRAN</b>
        <i>(Mengumpulkan dan menganalisis data terkait pengaturan keseimbangan cairan)</i>
        <b>(I.03121)</b>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>TINDAKAN</b>
      </td>
    </tr>

    <!-- OBSERVASI -->
    <tr>
      <td style="width: 5%; border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>1</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_1" onclick="checkthis('cairan_observasi_1')" value="Monitor nadi"><span class="lbl"> Monitor frekuensi dan kekuatan nadi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_2" onclick="checkthis('cairan_observasi_2')" value="Monitor frekuensi napas"><span class="lbl"> Monitor frekuensi napas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_3" onclick="checkthis('cairan_observasi_3')" value="Monitor tekanan darah"><span class="lbl"> Monitor tekanan darah</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_4" onclick="checkthis('cairan_observasi_4')" value="Monitor berat badan"><span class="lbl"> Monitor berat badan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_5" onclick="checkthis('cairan_observasi_5')" value="Monitor pengisian kapiler"><span class="lbl"> Monitor waktu pengisian kapiler</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_6" onclick="checkthis('cairan_observasi_6')" value="Monitor turgor kulit"><span class="lbl"> Monitor elastisitas turgor kulit</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_7" onclick="checkthis('cairan_observasi_7')" value="Monitor urine"><span class="lbl"> Monitor jumlah, warna, dan berat jenis urine</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_8" onclick="checkthis('cairan_observasi_8')" value="Monitor albumin"><span class="lbl"> Monitor kadar albumin dan protein total</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_9" onclick="checkthis('cairan_observasi_9')" value="Monitor hasil serum"><span class="lbl"> Monitor hasil pemeriksaan serum (misal osmolaritas serum, hematokrit, natrium, kalium, BUN)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_10" onclick="checkthis('cairan_observasi_10')" value="Identifikasi tanda hipovolemia"><span class="lbl"> Identifikasi tanda-tanda hipovolemia (misal nadi meningkat/lemah, tekanan darah menurun, turgor kulit menurun, mukosa kering, volume urine menurun, hematokrit meningkat, haus, lemah, konsentrasi urine meningkat, berat badan menurun cepat)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_11" onclick="checkthis('cairan_observasi_11')" value="Identifikasi tanda hipervolemia"><span class="lbl"> Identifikasi tanda-tanda hipervolemia (misal dispnea, edema perifer, edema anasarka, JVP meningkat, CVP meningkat, refleks hepatojugular positif, berat badan naik cepat)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_12" onclick="checkthis('cairan_observasi_12')" value="Identifikasi faktor risiko"><span class="lbl"> Identifikasi faktor risiko ketidakseimbangan cairan (misal pembedahan mayor, trauma/perdarahan, luka bakar, aferesis, obstruksi intestinal, pankreatitis, penyakit ginjal/kelenjar, disfungsi intestinal)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_observasi][]" id="cairan_observasi_13" onclick="checkthis('cairan_observasi_13')" value="Monitor intake-output"><span class="lbl"> Monitor intake dan output cairan</span></label></div>
      </td>
    </tr>

    <!-- TERAPEUTIK -->
    <tr>
      <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>2</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_terapeutik][]" id="cairan_terapeutik_1" onclick="checkthis('cairan_terapeutik_1')" value="Atur interval"><span class="lbl"> Atur interval waktu pemantauan sesuai dengan kondisi pasien</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_terapeutik][]" id="cairan_terapeutik_2" onclick="checkthis('cairan_terapeutik_2')" value="Dokumentasikan hasil"><span class="lbl"> Dokumentasikan hasil pemantauan</span></label></div>
      </td>
    </tr>

    <!-- EDUKASI -->
    <tr>
      <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>3</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_edukasi][]" id="cairan_edukasi_1" onclick="checkthis('cairan_edukasi_1')" value="Jelaskan tujuan"><span class="lbl"> Jelaskan tujuan dan prosedur pemantauan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_68[cairan_edukasi][]" id="cairan_edukasi_2" onclick="checkthis('cairan_edukasi_2')" value="Informasikan hasil"><span class="lbl"> Informasikan hasil pemantauan jika perlu</span></label></div>
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
        <input type="text" class="input_type" name="form_68[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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