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
      var hiddenInputName = 'form_81[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: PERFUSI PERIFER TIDAK EFEKTIF</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Definisi:</b> Penurunan sirkulasi darah pada level kapiler yang dapat mengganggu metabolisme tubuh.
      </td>
    </tr>
  </thead>

  <tbody>
    <!-- PENYEBAB -->
    <tr>
      <td width="50%" style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[penyebab][]" id="penyebab_hiperglikemia" onclick="checkthis('penyebab_hiperglikemia')" value="Hiperglikemia"><span class="lbl"> Hiperglikemia</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[penyebab][]" id="penyebab_hemoglobin" onclick="checkthis('penyebab_hemoglobin')" value="Penurunan konsentrasi hemoglobin"><span class="lbl"> Penurunan konsentrasi hemoglobin</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[penyebab][]" id="penyebab_tekanan_darah" onclick="checkthis('penyebab_tekanan_darah')" value="Peningkatan tekanan darah"><span class="lbl"> Peningkatan tekanan darah</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[penyebab][]" id="penyebab_aliran" onclick="checkthis('penyebab_aliran')" value="Penurunan aliran arteri atau vena"><span class="lbl"> Penurunan aliran arteri atau vena</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[penyebab][]" id="penyebab_faktor" onclick="checkthis('penyebab_faktor')" value="Kurang terpapar informasi tentang faktor pemberat (mis: merokok, gaya hidup monoton, trauma, obesitas, asupan garam dan imobilitas)"><span class="lbl"> Kurang terpapar informasi tentang faktor pemberat (mis: merokok, gaya hidup monoton, trauma, obesitas, asupan garam dan imobilitas)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[penyebab][]" id="penyebab_penyakit" onclick="checkthis('penyebab_penyakit')" value="Kurang terpapar informasi tentang proses penyakit (mis: diabet melitus, hiperlipidemia)"><span class="lbl"> Kurang terpapar informasi tentang proses penyakit (mis: diabet melitus, hiperlipidemia)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[penyebab][]" id="penyebab_aktivitas" onclick="checkthis('penyebab_aktivitas')" value="Kurang aktivitas fisik"><span class="lbl"> Kurang aktivitas fisik</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[penyebab][]" id="penyebab_cairan" onclick="checkthis('penyebab_cairan')" value="Kekurangan volume cairan"><span class="lbl"> Kekurangan volume cairan</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_81[intervensi_selama]" id="intervensi_selama" onchange="fillthis('intervensi_selama')" style="width:10%;">
          Perfusi perifer meningkat (L.02011) dengan kriteria hasil:</b><br><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_nadi" onclick="checkthis('hasil_nadi')" value="Denyut nadi perifer meningkat"><span class="lbl"> Denyut nadi perifer meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_luka" onclick="checkthis('hasil_luka')" value="Penyembuhan luka meningkat"><span class="lbl"> Penyembuhan luka meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_sensasi" onclick="checkthis('hasil_sensasi')" value="Sensasi meningkat"><span class="lbl"> Sensasi meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_warna" onclick="checkthis('hasil_warna')" value="Warna kulit pucat menurun"><span class="lbl"> Warna kulit pucat menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_edema" onclick="checkthis('hasil_edema')" value="Edema perifer menurun"><span class="lbl"> Edema perifer menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_nyeri" onclick="checkthis('hasil_nyeri')" value="Nyeri ekstremitas menurun"><span class="lbl"> Nyeri ekstremitas menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_parestesia" onclick="checkthis('hasil_parestesia')" value="Parestesia menurun"><span class="lbl"> Parestesia menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_kelemahan" onclick="checkthis('hasil_kelemahan')" value="Kelemahan otot menurun"><span class="lbl"> Kelemahan otot menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_kram" onclick="checkthis('hasil_kram')" value="Kram otot menurun"><span class="lbl"> Kram otot menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_bruit" onclick="checkthis('hasil_bruit')" value="Bruit femoralis menurun"><span class="lbl"> Bruit femoralis menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_nekrosis" onclick="checkthis('hasil_nekrosis')" value="Nekrosis menurun"><span class="lbl"> Nekrosis menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_kapiler" onclick="checkthis('hasil_kapiler')" value="Pengisian kapiler membaik"><span class="lbl"> Pengisian kapiler membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_akral" onclick="checkthis('hasil_akral')" value="Akral membaik"><span class="lbl"> Akral membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_turgor" onclick="checkthis('hasil_turgor')" value="Turgor kulit membaik"><span class="lbl"> Turgor kulit membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_td_sistolik" onclick="checkthis('hasil_td_sistolik')" value="Tekanan darah sistolik membaik"><span class="lbl"> Tekanan darah sistolik membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_td_diastolik" onclick="checkthis('hasil_td_diastolik')" value="Tekanan darah diastolik membaik"><span class="lbl"> Tekanan darah diastolik membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_tar" onclick="checkthis('hasil_tar')" value="Tekanan arteri rata-rata membaik"><span class="lbl"> Tekanan arteri rata-rata membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kriteria_hasil][]" id="hasil_indeks_ab" onclick="checkthis('hasil_indeks_ab')" value="Indeks ankle-brachial membaik"><span class="lbl"> Indeks ankle-brachial membaik</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[mayor_objektif][]" id="mayor_kapiler" onclick="checkthis('mayor_kapiler')" value="Pengisian kapiler >3 detik"><span class="lbl"> Pengisian kapiler >3 detik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[mayor_objektif][]" id="mayor_nadi" onclick="checkthis('mayor_nadi')" value="Nadi perifer menurun / tidak teraba"><span class="lbl"> Nadi perifer menurun / tidak teraba</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[mayor_objektif][]" id="mayor_akral" onclick="checkthis('mayor_akral')" value="Akral teraba dingin"><span class="lbl"> Akral teraba dingin</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[mayor_objektif][]" id="mayor_warna" onclick="checkthis('mayor_warna')" value="Warna kulit pucat"><span class="lbl"> Warna kulit pucat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[mayor_objektif][]" id="mayor_turgor" onclick="checkthis('mayor_turgor')" value="Turgor kulit menurun"><span class="lbl"> Turgor kulit menurun</span></label></div>
          </div>
        </div>

        <hr>

        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[minor_subjektif][]" id="minor_parestesia" onclick="checkthis('minor_parestesia')" value="Parestesia"><span class="lbl"> Parestesia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[minor_subjektif][]" id="minor_nyeri" onclick="checkthis('minor_nyeri')" value="Nyeri ekstremitas"><span class="lbl"> Nyeri ekstremitas</span></label></div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[minor_objektif][]" id="minor_edema" onclick="checkthis('minor_edema')" value="Edema"><span class="lbl"> Edema</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[minor_objektif][]" id="minor_luka" onclick="checkthis('minor_luka')" value="Penyembuhan luka lambat"><span class="lbl"> Penyembuhan luka lambat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[minor_objektif][]" id="minor_ab" onclick="checkthis('minor_ab')" value="Indeks ankle-brachial < 0.90"><span class="lbl"> Indeks ankle-brachial &lt; 0.90</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[minor_objektif][]" id="minor_bruit" onclick="checkthis('minor_bruit')" value="Bruit femoralis"><span class="lbl"> Bruit femoralis</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->


<!-- PERAWATAN SIRKULASI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size:13px; line-height:1.3;">
  <thead>
    <tr style="background-color:#d3d3d3;">
      <th style="width:5%; border:1px solid black; text-align:center;">NO.</th>
      <th style="width:95%; border:1px solid black; text-align:center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Perawatan Sirkulasi</b><br>
        <i>(Mengidentifikasi dan merawat area lokal dengan keterbatasan sirkulasi perifer)</i><br>
        <b>(I.02079)</b>
      </td>
    </tr>

    <!-- 1. Observasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>1</b></td>
      <td style="vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_observasi][]" id="sirkulasi_observasi_1" onclick="checkthis('sirkulasi_observasi_1')" value="Periksa sirkulasi perifer (mis.nadi perifer, edema, pengisian kapiler, warna, suhu, anklebrachial index)"><span class="lbl"> Periksa sirkulasi perifer (mis.nadi perifer, edema, pengisian kapiler, warna, suhu, anklebrachial index)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_observasi][]" id="sirkulasi_observasi_2" onclick="checkthis('sirkulasi_observasi_2')" value="Identifikasi faktor risiko gangguan sirkulasi (mis.diabetes, perokok, orangtua, hipertensi, dan kadar kolesterol tinggi)"><span class="lbl"> Identifikasi faktor risiko gangguan sirkulasi (mis.diabetes, perokok, orangtua, hipertensi, dan kadar kolesterol tinggi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_observasi][]" id="sirkulasi_observasi_3" onclick="checkthis('sirkulasi_observasi_3')" value="Monitor panas, kemerahan, nyeri, atau bengkak pada ekstremitas"><span class="lbl"> Monitor panas, kemerahan, nyeri, atau bengkak pada ekstremitas</span></label></div>
      </td>
    </tr>

    <!-- 2. Terapeutik -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>2</b></td>
      <td style="vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_terapeutik][]" id="sirkulasi_terapeutik_1" onclick="checkthis('sirkulasi_terapeutik_1')" value="Hindari pemasangan infus atau pengambilan darah di area keterbatasan perfusi"><span class="lbl"> Hindari pemasangan infus atau pengambilan darah di area keterbatasan perfusi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_terapeutik][]" id="sirkulasi_terapeutik_2" onclick="checkthis('sirkulasi_terapeutik_2')" value="Hindari pengukuran tekanan darah pada ekstremitas dengan keterbatasan perfusi"><span class="lbl"> Hindari pengukuran tekanan darah pada ekstremitas dengan keterbatasan perfusi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_terapeutik][]" id="sirkulasi_terapeutik_3" onclick="checkthis('sirkulasi_terapeutik_3')" value="Hindari penekanan dan pemasangan tourniquet pada area yang cedera"><span class="lbl"> Hindari penekanan dan pemasangan tourniquet pada area yang cedera</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_terapeutik][]" id="sirkulasi_terapeutik_4" onclick="checkthis('sirkulasi_terapeutik_4')" value="Lakukan pencegahan infeksi"><span class="lbl"> Lakukan pencegahan infeksi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_terapeutik][]" id="sirkulasi_terapeutik_5" onclick="checkthis('sirkulasi_terapeutik_5')" value="Lakukan perawatan kaki dan kuku"><span class="lbl"> Lakukan perawatan kaki dan kuku</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_terapeutik][]" id="sirkulasi_terapeutik_6" onclick="checkthis('sirkulasi_terapeutik_6')" value="Lakukan hidrasi"><span class="lbl"> Lakukan hidrasi</span></label></div>
      </td>
    </tr>

    <!-- 3. Edukasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>3</b></td>
      <td style="vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_edukasi][]" id="sirkulasi_edukasi_1" onclick="checkthis('sirkulasi_edukasi_1')" value="Anjurkan berhenti merokok"><span class="lbl"> Anjurkan berhenti merokok</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_edukasi][]" id="sirkulasi_edukasi_2" onclick="checkthis('sirkulasi_edukasi_2')" value="Anjurkan berolahraga rutin"><span class="lbl"> Anjurkan berolahraga rutin</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_edukasi][]" id="sirkulasi_edukasi_3" onclick="checkthis('sirkulasi_edukasi_3')" value="Anjurkan mengecek air mandi untuk menghindari kulit terbakar"><span class="lbl"> Anjurkan mengecek air mandi untuk menghindari kulit terbakar</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_edukasi][]" id="sirkulasi_edukasi_4" onclick="checkthis('sirkulasi_edukasi_4')" value="Anjurkan menggunakan obat penurunan tekanan darah, antikoagulan, dan penurunan kolesterol jika perlu"><span class="lbl"> Anjurkan menggunakan obat penurunan tekanan darah, antikoagulan, dan penurunan kolesterol jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_edukasi][]" id="sirkulasi_edukasi_5" onclick="checkthis('sirkulasi_edukasi_5')" value="Anjurkan minum obat pengontrol tekanan darah secara teratur"><span class="lbl"> Anjurkan minum obat pengontrol tekanan darah secara teratur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_edukasi][]" id="sirkulasi_edukasi_6" onclick="checkthis('sirkulasi_edukasi_6')" value="Anjurkan menghindari penggunaan obat penyekat beta"><span class="lbl"> Anjurkan menghindari penggunaan obat penyekat beta</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_edukasi][]" id="sirkulasi_edukasi_7" onclick="checkthis('sirkulasi_edukasi_7')" value="Anjurkan melakukan perawatan kulit yang tepat"><span class="lbl"> Anjurkan melakukan perawatan kulit yang tepat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_edukasi][]" id="sirkulasi_edukasi_8" onclick="checkthis('sirkulasi_edukasi_8')" value="Anjurkan program rehabilitasi vaskular"><span class="lbl"> Anjurkan program rehabilitasi vaskular</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_edukasi][]" id="sirkulasi_edukasi_9" onclick="checkthis('sirkulasi_edukasi_9')" value="Ajarkan program diet untuk memperbaiki sirkulasi (mis. rendah lemak jenuh, minyak ikan omega 3)"><span class="lbl"> Ajarkan program diet untuk memperbaiki sirkulasi (mis. rendah lemak jenuh, minyak ikan omega 3)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[sirkulasi_edukasi][]" id="sirkulasi_edukasi_10" onclick="checkthis('sirkulasi_edukasi_10')" value="Informasikan tanda dan gejala darurat yang harus dilaporkan (mis. rasa sakit yang tidak hilang saat istirahat, luka tidak sembuh, hilangnya rasa)"><span class="lbl"> Informasikan tanda dan gejala darurat yang harus dilaporkan (mis. rasa sakit yang tidak hilang saat istirahat, luka tidak sembuh, hilangnya rasa)</span></label></div>
      </td>
    </tr>
  </tbody>
</table>

<!-- MANAJEMEN SENSASI PERIFER -->
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
        <b>Manajemen Sensasi Perifer</b><br>
        <i>(Mengidentifikasi dan mengelola ketidaknyamanan pada perubahan sensasi perifer)</i><br>
        <b>(I.06195)</b>
      </td>
    </tr>

    <!-- 1. Observasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>1</b></td>
      <td style="vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[observasi][]" id="observasi_1" onclick="checkthis('observasi_1')" value="Identifikasi penyebab perubahan sensasi"><span class="lbl"> Identifikasi penyebab perubahan sensasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[observasi][]" id="observasi_2" onclick="checkthis('observasi_2')" value="Identifikasi penggunaan, alat pengikat, protetis, sepatu dan pakaian"><span class="lbl"> Identifikasi penggunaan, alat pengikat, protetis, sepatu dan pakaian</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[observasi][]" id="observasi_3" onclick="checkthis('observasi_3')" value="Periksa perbedaan sensasi tajam dan tumpul"><span class="lbl"> Periksa perbedaan sensasi tajam dan tumpul</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[observasi][]" id="observasi_4" onclick="checkthis('observasi_4')" value="Periksa perbedaan sensasi panas dan dingin"><span class="lbl"> Periksa perbedaan sensasi panas dan dingin</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[observasi][]" id="observasi_5" onclick="checkthis('observasi_5')" value="Periksa kemampuan mengidentifikasi lokasi dan tekstur benda"><span class="lbl"> Periksa kemampuan mengidentifikasi lokasi dan tekstur benda</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[observasi][]" id="observasi_6" onclick="checkthis('observasi_6')" value="Monitor terjadinya parastesia jika perlu"><span class="lbl"> Monitor terjadinya parastesia jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[observasi][]" id="observasi_7" onclick="checkthis('observasi_7')" value="Monitor perubahan kulit"><span class="lbl"> Monitor perubahan kulit</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[observasi][]" id="observasi_8" onclick="checkthis('observasi_8')" value="Monitor adanya tromboemboli vena"><span class="lbl"> Monitor adanya tromboemboli vena</span></label></div>
      </td>
    </tr>

    <!-- 2. Terapeutik -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>2</b></td>
      <td style="vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[terapeutik][]" id="terapeutik_1" onclick="checkthis('terapeutik_1')" value="Hindari pemakaian benda-benda yang berlebihan suhunya (terlalu panas atau dingin)"><span class="lbl"> Hindari pemakaian benda-benda yang berlebihan suhunya (terlalu panas atau dingin)</span></label></div>
      </td>
    </tr>

    <!-- 3. Edukasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>3</b></td>
      <td style="vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[edukasi][]" id="edukasi_1" onclick="checkthis('edukasi_1')" value="Anjurkan penggunaan termometer untuk menguji suhu air"><span class="lbl"> Anjurkan penggunaan termometer untuk menguji suhu air</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[edukasi][]" id="edukasi_2" onclick="checkthis('edukasi_2')" value="Anjurkan penggunaan sarung tangan termal saat memasak"><span class="lbl"> Anjurkan penggunaan sarung tangan termal saat memasak</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[edukasi][]" id="edukasi_3" onclick="checkthis('edukasi_3')" value="Anjurkan memakai sepatu lembut dan bertumit rendah"><span class="lbl"> Anjurkan memakai sepatu lembut dan bertumit rendah</span></label></div>
      </td>
    </tr>

    <!-- 4. Kolaborasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>4</b></td>
      <td style="vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kolaborasi][]" id="kolaborasi_1" onclick="checkthis('kolaborasi_1')" value="Kolaborasi pemberian analgesik jika perlu"><span class="lbl"> Kolaborasi pemberian analgesik jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_81[kolaborasi][]" id="kolaborasi_2" onclick="checkthis('kolaborasi_2')" value="Kolaborasi pemberian kortikosteroid jika perlu"><span class="lbl"> Kolaborasi pemberian kortikosteroid jika perlu</span></label></div>
      </td>
    </tr>
  </tbody>
</table>

<style>
.checkbox {
  margin-bottom: 4px;
  line-height: 1.4;
}
.lbl {
  font-size: 13px;
}
</style>
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
        <input type="text" class="input_type" name="form_81[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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