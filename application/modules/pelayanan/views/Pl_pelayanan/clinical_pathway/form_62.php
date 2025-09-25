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
      var hiddenInputName = 'form_62[ttd_' + role + ']';
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
<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN:<br>KETIDAKSTABILAN KADAR GLUKOSA DARAH</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
    <thead>
     <tr>
        <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">Definisi : Variasi kadar glukosa darah naik/turun dari rentang normal.
        </td>
     </tr>
    </thead>
    <tbody>
       <tr>
  <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
    <b>PENYEBAB / Berhubungan dengan :</b><br><br>

    <i><b>Hiperglikemia</b></i>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_62[faktor_risiko][]" id="faktor_disfungsi_pancreas" onclick="checkthis('faktor_disfungsi_pancreas')" value="Disfungsi pancreas">
        <span class="lbl"> Disfungsi pancreas</span>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_62[faktor_risiko][]" id="faktor_resistensi_insulin" onclick="checkthis('faktor_resistensi_insulin')" value="Resistensi insulin">
        <span class="lbl"> Resistensi insulin</span>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_62[faktor_risiko][]" id="faktor_gangguan_toleransi" onclick="checkthis('faktor_gangguan_toleransi')" value="Gangguan toleransi glukosa darah">
        <span class="lbl"> Gangguan toleransi glukosa darah</span>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_62[faktor_risiko][]" id="faktor_gangguan_puasa" onclick="checkthis('faktor_gangguan_puasa')" value="Gangguan glukosa darah puasa">
        <span class="lbl"> Gangguan glukosa darah puasa</span>
      </label>
    </div>

    <br>
    <i><b>Hipoglikemia</b></i>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_62[faktor_risiko][]" id="faktor_obat_glikemik" onclick="checkthis('faktor_obat_glikemik')" value="Penggunaan insulin atau obat glikemik oral">
        <span class="lbl"> Penggunaan insulin atau obat glikemik oral</span>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_62[faktor_risiko][]" id="faktor_hiperinsulinemia" onclick="checkthis('faktor_hiperinsulinemia')" value="Hiperinsulinemia">
        <span class="lbl"> Hiperinsulinemia</span>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_62[faktor_risiko][]" id="faktor_endokrinopati" onclick="checkthis('faktor_endokrinopati')" value="Endokrinopati">
        <span class="lbl"> Endokrinopati</span>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_62[faktor_risiko][]" id="faktor_gangguan_hati" onclick="checkthis('faktor_gangguan_hati')" value="Disfungsi hati">
        <span class="lbl"> Disfungsi hati</span>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_62[faktor_risiko][]" id="faktor_gangguan_ginjal" onclick="checkthis('faktor_gangguan_ginjal')" value="Disfungsi ginjal kronis">
        <span class="lbl"> Disfungsi ginjal kronis</span>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_62[faktor_risiko][]" id="faktor_agen_farmakologis" onclick="checkthis('faktor_agen_farmakologis')" value="Efek agen farmakologis">
        <span class="lbl"> Efek agen farmakologis</span>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_62[faktor_risiko][]" id="faktor_pembedahan" onclick="checkthis('faktor_pembedahan')" value="Tindakan pembedahan / neoplasma">
        <span class="lbl"> Tindakan pembedahan / neoplasma</span>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_62[faktor_risiko][]" id="faktor_metabolik" onclick="checkthis('faktor_metabolik')" value="Gangguan metabolik bawaan">
        <span class="lbl"> Gangguan metabolik bawaan</span>
      </label>
    </div>

  </td>
<td style="border: 1px solid black; padding: 5px; vertical-align: top;">
  <b>
    Setelah dilakukan intervensi selama 
    <input type="text" class="input_type" name="form_62[ket_intervensi_selama]" 
           id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
    , Kestabilan kadar glukosa darah membaik (L.03022) dengan kriteria hasil :
  </b>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_kesadaran" onclick="checkthis('kriteria_kesadaran')" value="Tingkat kesadaran meningkat">
      <span class="lbl"> Tingkat kesadaran meningkat</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_mengantuk" onclick="checkthis('kriteria_mengantuk')" value="Mengantuk menurun">
      <span class="lbl"> Mengantuk menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_pusing" onclick="checkthis('kriteria_pusing')" value="Pusing menurun">
      <span class="lbl"> Pusing menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_lesu" onclick="checkthis('kriteria_lesu')" value="Lelah/lesu menurun">
      <span class="lbl"> Lelah/lesu menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_lapar" onclick="checkthis('kriteria_lapar')" value="Rasa lapar menurun">
      <span class="lbl"> Rasa lapar menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_gemetar" onclick="checkthis('kriteria_gemetar')" value="Gemetar menurun">
      <span class="lbl"> Gemetar menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_berkeringat" onclick="checkthis('kriteria_berkeringat')" value="Berkeringat menurun">
      <span class="lbl"> Berkeringat menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_mulut_kering" onclick="checkthis('kriteria_mulut_kering')" value="Mulut kering menurun">
      <span class="lbl"> Mulut kering menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_haus" onclick="checkthis('kriteria_haus')" value="Rasa haus menurun">
      <span class="lbl"> Rasa haus menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_perilaku_aneh" onclick="checkthis('kriteria_perilaku_aneh')" value="Perilaku aneh menurun">
      <span class="lbl"> Perilaku aneh menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_bicara" onclick="checkthis('kriteria_bicara')" value="Kesulitan bicara menurun">
      <span class="lbl"> Kesulitan bicara menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_gd_darah" onclick="checkthis('kriteria_gd_darah')" value="Kadar glukosa dalam darah membaik">
      <span class="lbl"> Kadar glukosa dalam darah membaik</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_gd_urine" onclick="checkthis('kriteria_gd_urine')" value="Kadar glukosa dalam urine membaik">
      <span class="lbl"> Kadar glukosa dalam urine membaik</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_palpitasi" onclick="checkthis('kriteria_palpitasi')" value="Palpitasi membaik">
      <span class="lbl"> Palpitasi membaik</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_perilaku" onclick="checkthis('kriteria_perilaku')" value="Perilaku membaik">
      <span class="lbl"> Perilaku membaik</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_62[kriteria][]" id="kriteria_urine" onclick="checkthis('kriteria_urine')" value="Jumlah urine membaik">
      <span class="lbl"> Jumlah urine membaik</span>
    </label>
  </div>
</td>

</tr>

<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Dibuktikan dengan :</b>

    <p><b>Tanda dan Gejala Mayor</b></p>
    <div class="row">
      <!-- Subjektif -->
      <div class="col-md-6">
        <b><i>Subjektif:</i></b><br>
        <i>Hipoglikemia</i>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_mayor_subjektif][]" id="gejala_mayor_mengantuk" onclick="checkthis('gejala_mayor_mengantuk')" value="Mengantuk"><span class="lbl"> Mengantuk</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_mayor_subjektif][]" id="gejala_mayor_pusing" onclick="checkthis('gejala_mayor_pusing')" value="Pusing"><span class="lbl"> Pusing</span></label></div>

        <br>
        <i>Hiperglikemia</i>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_mayor_subjektif][]" id="gejala_mayor_lesu" onclick="checkthis('gejala_mayor_lesu')" value="Lelah/lesu"><span class="lbl"> Lelah/lesu</span></label></div>
      </div>

      <!-- Objektif -->
      <div class="col-md-6">
        <b>Objektif:</b><br>
        <i>Hipoglikemia</i>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_mayor_objektif][]" id="gejala_mayor_koordinasi" onclick="checkthis('gejala_mayor_koordinasi')" value="Gangguan koordinasi"><span class="lbl"> Gangguan koordinasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_mayor_objektif][]" id="gejala_mayor_gd_rendah" onclick="checkthis('gejala_mayor_gd_rendah')" value="Kadar GD dalam darah/urine rendah"><span class="lbl"> Kadar GD dalam darah/urine rendah</span></label></div>

        <br>
        <i>Hiperglikemia</i>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_mayor_objektif][]" id="gejala_mayor_gd_tinggi" onclick="checkthis('gejala_mayor_gd_tinggi')" value="Kadar GD dalam darah/urine tinggi"><span class="lbl"> Kadar GD dalam darah/urine tinggi</span></label></div>
      </div>
    </div>

    <hr>

    <p><b>Tanda dan Gejala Minor</b></p>
    <div class="row">
      <!-- Subjektif -->
      <div class="col-md-6">
        <b><i>Subjektif:</i></b><br>
        <i>Hipoglikemia</i>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_minor_subjektif][]" id="gejala_minor_palpitasi" onclick="checkthis('gejala_minor_palpitasi')" value="Palpitasi"><span class="lbl"> Palpitasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_minor_subjektif][]" id="gejala_minor_lapar" onclick="checkthis('gejala_minor_lapar')" value="Mengeluh lapar"><span class="lbl"> Mengeluh lapar</span></label></div>

        <br>
        <i>Hiperglikemia</i>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_minor_subjektif][]" id="gejala_minor_mulut_kering" onclick="checkthis('gejala_minor_mulut_kering')" value="Mulut kering"><span class="lbl"> Mulut kering</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_minor_subjektif][]" id="gejala_minor_haus" onclick="checkthis('gejala_minor_haus')" value="Haus meningkat"><span class="lbl"> Haus meningkat</span></label></div>
      </div>

      <!-- Objektif -->
      <div class="col-md-6">
        <b>Objektif:</b><br>
        <i>Hipoglikemia</i>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_minor_objektif][]" id="gejala_minor_gemetar" onclick="checkthis('gejala_minor_gemetar')" value="Gemetar"><span class="lbl"> Gemetar</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_minor_objektif][]" id="gejala_minor_kesadaran" onclick="checkthis('gejala_minor_kesadaran')" value="Kesadaran menurun"><span class="lbl"> Kesadaran menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_minor_objektif][]" id="gejala_minor_perilaku" onclick="checkthis('gejala_minor_perilaku')" value="Perilaku aneh"><span class="lbl"> Perilaku aneh</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_minor_objektif][]" id="gejala_minor_sulit_bicara" onclick="checkthis('gejala_minor_sulit_bicara')" value="Sulit bicara"><span class="lbl"> Sulit bicara</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_minor_objektif][]" id="gejala_minor_berkeringat" onclick="checkthis('gejala_minor_berkeringat')" value="Berkeringat"><span class="lbl"> Berkeringat</span></label></div>

        <br>
        <i>Hiperglikemia</i>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_62[gejala_minor_objektif][]" id="gejala_minor_urine" onclick="checkthis('gejala_minor_urine')" value="Jumlah urine meningkat"><span class="lbl"> Jumlah urine meningkat</span></label></div>
      </div>
    </div>
  </td>
</tr>
        
        <!-- next -->

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

<!-- Manajemen Hiperglikemia -->
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Manajemen Hiperglikemia</b> 
    <i>(Mengidentifikasi dan mengelola kadar glukosa dalam darah diatas normal)</i> <b>I.03115</b>
  </td>
</tr>

<!-- Observasi Hiperglikemia -->
<tr>
  <td style="border: 1px solid black; padding: 5px; text-align:center; vertical-align:top;"><b>1</b></td>
  <td style="border: 1px solid black; padding: 5px; vertical-align:top;">
    <label><b>Observasi</b></label><br>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_observasi][]" id="hiperglikemia_observasi_1" onclick="checkthis(this.id)" value="Identifikasi kemungkinan penyebab hiperglikemia">
      <span class="lbl"> Identifikasi kemungkinan penyebab hiperglikemia</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_observasi][]" id="hiperglikemia_observasi_2" onclick="checkthis(this.id)" value="Identifikasi situasi yang menyebabkan kebutuhan insulin meningkat (mis: penyakit kambuhan)">
      <span class="lbl"> Identifikasi situasi yang menyebabkan kebutuhan insulin meningkat (mis: penyakit kambuhan)</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_observasi][]" id="hiperglikemia_observasi_3" onclick="checkthis(this.id)" value="Monitor kadar glukosa darah">
      <span class="lbl"> Monitor kadar glukosa darah</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_observasi][]" id="hiperglikemia_observasi_4" onclick="checkthis(this.id)" value="Monitor tanda dan gejala hiperglikemia (poliuria, polidipsi, polifagia, kelemahan, malaise, pandangan kabur, sakit kepala)">
      <span class="lbl"> Monitor tanda dan gejala hiperglikemia (poliuria, polidipsi, polifagia, kelemahan, malaise, pandangan kabur, sakit kepala)</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_observasi][]" id="hiperglikemia_observasi_5" onclick="checkthis(this.id)" value="Monitor intake dan output cairan">
      <span class="lbl"> Monitor intake dan output cairan</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_observasi][]" id="hiperglikemia_observasi_6" onclick="checkthis(this.id)" value="Monitor keton urine, AGD, elektrolit, TD, dan nadi">
      <span class="lbl"> Monitor keton urine, AGD, elektrolit, TD, dan nadi</span>
    </label></div>
  </td>
</tr>

<!-- Terapeutik Hiperglikemia -->
<tr>
  <td style="border: 1px solid black; padding: 5px; text-align:center; vertical-align:top;"><b>2</b></td>
  <td style="border: 1px solid black; padding: 5px; vertical-align:top;">
    <label><b>Terapeutik</b></label><br>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_terapeutik][]" id="hiperglikemia_terapeutik_1" onclick="checkthis(this.id)" value="Berikan asupan cairan oral (mis: tongkat, kruk)">
      <span class="lbl"> Berikan asupan cairan oral (mis: tongkat, kruk)</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_terapeutik][]" id="hiperglikemia_terapeutik_2" onclick="checkthis(this.id)" value="Konsultasi dengan medis jika tanda dan gejala hiperglikemia tetap ada atau memburuk">
      <span class="lbl"> Konsultasi dengan medis jika tanda dan gejala hiperglikemia tetap ada atau memburuk</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_terapeutik][]" id="hiperglikemia_terapeutik_3" onclick="checkthis(this.id)" value="Fasilitasi ambulasi jika ada hipotensi ortostatik">
      <span class="lbl"> Fasilitasi ambulasi jika ada hipotensi ortostatik</span>
    </label></div>
  </td>
</tr>

<!-- Edukasi Hiperglikemia -->
<tr>
  <td style="border: 1px solid black; padding: 5px; text-align:center; vertical-align:top;"><b>3</b></td>
  <td style="border: 1px solid black; padding: 5px; vertical-align:top;">
    <label><b>Edukasi</b></label><br>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_edukasi][]" id="hiperglikemia_edukasi_1" onclick="checkthis(this.id)" value="Anjurkan menghindari olahraga saat kadar GD > 250 mg/dl">
      <span class="lbl"> Anjurkan menghindari olahraga saat kadar GD > 250 mg/dl</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_edukasi][]" id="hiperglikemia_edukasi_2" onclick="checkthis(this.id)" value="Anjurkan memonitor kadar GD secara mandiri">
      <span class="lbl"> Anjurkan memonitor kadar GD secara mandiri</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_edukasi][]" id="hiperglikemia_edukasi_3" onclick="checkthis(this.id)" value="Ajarkan kepatuhan terhadap diet dan olahraga (mis: berjalan dari tempat tidur ke kursi roda, berjalan dari tempat tidur ke kamar mandi, berjalan sesuai toleransi)">
      <span class="lbl"> Ajarkan kepatuhan terhadap diet dan olahraga (mis: berjalan dari tempat tidur ke kursi roda, berjalan dari tempat tidur ke kamar mandi, berjalan sesuai toleransi)</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_edukasi][]" id="hiperglikemia_edukasi_4" onclick="checkthis(this.id)" value="Ajarkan pengelolaan diabetes (mis: berjalan dari tempat tidur ke kursi roda, berjalan dari tempat tidur ke kamar mandi, berjalan sesuai toleransi)">
      <span class="lbl"> Ajarkan pengelolaan diabetes (mis: berjalan dari tempat tidur ke kursi roda, berjalan dari tempat tidur ke kamar mandi, berjalan sesuai toleransi)</span>
    </label></div>
  </td>
</tr>

<!-- Kolaborasi Hiperglikemia -->
<tr>
  <td style="border: 1px solid black; padding: 5px; text-align:center; vertical-align:top;"><b>4</b></td>
  <td style="border: 1px solid black; padding: 5px; vertical-align:top;">
    <label><b>Kolaborasi</b></label><br>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_kolaborasi][]" id="hiperglikemia_kolaborasi_1" onclick="checkthis(this.id)" value="Kolaborasi pemberian Insulin (mis: berjalan dari tempat tidur ke kursi roda, berjalan dari tempat tidur ke kamar mandi, berjalan sesuai toleransi)">
      <span class="lbl"> Kolaborasi pemberian Insulin (mis: berjalan dari tempat tidur ke kursi roda, berjalan dari tempat tidur ke kamar mandi, berjalan sesuai toleransi)</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_kolaborasi][]" id="hiperglikemia_kolaborasi_2" onclick="checkthis(this.id)" value="Kolaborasi pemberian cairan (mis: berjalan dari tempat tidur ke kursi roda, berjalan dari tempat tidur ke kamar mandi, berjalan sesuai toleransi)">
      <span class="lbl"> Kolaborasi pemberian cairan (mis: berjalan dari tempat tidur ke kursi roda, berjalan dari tempat tidur ke kamar mandi, berjalan sesuai toleransi)</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hiperglikemia_kolaborasi][]" id="hiperglikemia_kolaborasi_3" onclick="checkthis(this.id)" value="Kolaborasi pemberian kalium (mis: berjalan dari tempat tidur ke kursi roda, berjalan dari tempat tidur ke kamar mandi, berjalan sesuai toleransi)">
      <span class="lbl"> Kolaborasi pemberian kalium (mis: berjalan dari tempat tidur ke kursi roda, berjalan dari tempat tidur ke kamar mandi, berjalan sesuai toleransi)</span>
    </label></div>
  </td>
</tr>

<!-- Manajemen Hipoglikemia -->
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Manajemen Hipoglikemia</b> 
    <i>(Mengidentifikasi dan mengelola kadar glukosa dalam darah rendah)</i> <b>I.03115</b>
  </td>
</tr>

<!-- Observasi Hipoglikemia -->
<tr>
  <td style="border: 1px solid black; padding: 5px; text-align:center; vertical-align:top;"><b>1</b></td>
  <td style="border: 1px solid black; padding: 5px; vertical-align:top;">
    <label><b>Observasi</b></label><br>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_observasi][]" id="hipoglikemia_observasi_1" onclick="checkthis(this.id)" value="Identifikasi tanda dan gejala hipoglikemia">
      <span class="lbl"> Identifikasi tanda dan gejala hipoglikemia</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_observasi][]" id="hipoglikemia_observasi_2" onclick="checkthis(this.id)" value="Identifikasi kemungkinan penyebab hipoglikemia">
      <span class="lbl"> Identifikasi kemungkinan penyebab hipoglikemia</span>
    </label></div>
  </td>
</tr>

<!-- Terapeutik Hipoglikemia -->
<tr>
  <td style="border: 1px solid black; padding: 5px; text-align:center; vertical-align:top;"><b>2</b></td>
  <td style="border: 1px solid black; padding: 5px; vertical-align:top;">
    <label><b>Terapeutik</b></label><br>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_terapeutik][]" id="hipoglikemia_terapeutik_1" onclick="checkthis(this.id)" value="Berikan karbohidrat sederhana (mis: pagar tempat tidur)">
      <span class="lbl"> Berikan karbohidrat sederhana (mis: pagar tempat tidur)</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_terapeutik][]" id="hipoglikemia_terapeutik_2" onclick="checkthis(this.id)" value="Berikan glukagon (mis: pagar tempat tidur)">
      <span class="lbl"> Berikan glukagon (mis: pagar tempat tidur)</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_terapeutik][]" id="hipoglikemia_terapeutik_3" onclick="checkthis(this.id)" value="Berikan karbohidrat kompleks dan protein sesuai diet (mis: pagar tempat tidur)">
      <span class="lbl"> Berikan karbohidrat kompleks dan protein sesuai diet (mis: pagar tempat tidur)</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_terapeutik][]" id="hipoglikemia_terapeutik_4" onclick="checkthis(this.id)" value="Pertahankan kepatenan jalan nafas (mis: pagar tempat tidur)">
      <span class="lbl"> Pertahankan kepatenan jalan nafas (mis: pagar tempat tidur)</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_terapeutik][]" id="hipoglikemia_terapeutik_5" onclick="checkthis(this.id)" value="Pertahankan akses IV">
      <span class="lbl"> Pertahankan akses IV</span>
    </label></div>
  </td>
</tr>

<!-- Edukasi Hipoglikemia -->
<tr>
  <td style="border: 1px solid black; padding: 5px; text-align:center; vertical-align:top;"><b>3</b></td>
  <td style="border: 1px solid black; padding: 5px; vertical-align:top;">
    <label><b>Edukasi</b></label><br>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_edukasi][]" id="hipoglikemia_edukasi_1" onclick="checkthis(this.id)" value="Anjurkan membawa karbohidrat sederhana setiap saat">
      <span class="lbl"> Anjurkan membawa karbohidrat sederhana setiap saat</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_edukasi][]" id="hipoglikemia_edukasi_2" onclick="checkthis(this.id)" value="Anjurkan memakai identitas yang tepat">
      <span class="lbl"> Anjurkan memakai identitas yang tepat</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_edukasi][]" id="hipoglikemia_edukasi_3" onclick="checkthis(this.id)" value="Ajarkan monitor kadar glukosa darah (mis:duduk ditempat tidur, duduk disisi tempat tidur, pindah dari tempat tidur ke kursi)">
      <span class="lbl"> Ajarkan monitor kadar glukosa darah (mis:duduk ditempat tidur, duduk disisi tempat tidur, pindah dari tempat tidur ke kursi)</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_edukasi][]" id="hipoglikemia_edukasi_4" onclick="checkthis(this.id)" value="Anjurkan berdiskusi dengan tim perawatan diabetes tentang penyesuaian program pengobatan (mis:duduk ditempat tidur, duduk disisi tempat tidur, pindah dari tempat tidur ke kursi)">
      <span class="lbl"> Anjurkan berdiskusi dengan tim perawatan diabetes tentang penyesuaian program pengobatan (mis:duduk ditempat tidur, duduk disisi tempat tidur, pindah dari tempat tidur ke kursi)</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_edukasi][]" id="hipoglikemia_edukasi_5" onclick="checkthis(this.id)" value="Ajarkan pengelolaan hipoglikemia">
      <span class="lbl"> Ajarkan pengelolaan hipoglikemia</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_edukasi][]" id="hipoglikemia_edukasi_6" onclick="checkthis(this.id)" value="Jelaskan interaksi antara diet/insulin, dan olahraga">
      <span class="lbl"> Jelaskan interaksi antara diet/insulin, dan olahraga</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_edukasi][]" id="hipoglikemia_edukasi_7" onclick="checkthis(this.id)" value="Ajarkan kepatuhan terhadap diet, olahraga, dan pengobatan">
      <span class="lbl"> Ajarkan kepatuhan terhadap diet, olahraga, dan pengobatan</span>
    </label></div>
  </td>
</tr>

<!-- Kolaborasi Hipoglikemia -->
<tr>
  <td style="border: 1px solid black; padding: 5px; text-align:center; vertical-align:top;"><b>4</b></td>
  <td style="border: 1px solid black; padding: 5px; vertical-align:top;">
    <label><b>Kolaborasi</b></label><br>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_kolaborasi][]" id="hipoglikemia_kolaborasi_1" onclick="checkthis(this.id)" value="Kolaborasi pemberian dextrose">
      <span class="lbl"> Kolaborasi pemberian dextrose</span>
    </label></div>

    <div class="checkbox"><label>
      <input type="checkbox" class="ace" name="form_62[hipoglikemia_kolaborasi][]" id="hipoglikemia_kolaborasi_2" onclick="checkthis(this.id)" value="Kolaborasi pemberian glukagon">
      <span class="lbl"> Kolaborasi pemberian glukagon</span>
    </label></div>

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
        <input type="text" class="input_type" name="form_62[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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