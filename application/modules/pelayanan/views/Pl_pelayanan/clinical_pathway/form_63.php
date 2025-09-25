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
      var hiddenInputName = 'form_63[ttd_' + role + ']';
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
<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN:<br>BERSIHAN JALAN NAFAS TIDAK EFEKTIF</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
        Definisi : Ketidakmampuan membersihkan sekret atau obstruksi jalan napas untuk mempertahankan jalan napas tetap paten
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB/Berhubungan dengan:</b><br>
        <b>Fisiologis</b>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_spasme_jalan_nafas" onclick="checkthis('penyebab_spasme_jalan_nafas')" value="Spasme jalan napas"><span class="lbl"> Spasme jalan napas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_hipersekresi_jalan_napas" onclick="checkthis('penyebab_hipersekresi_jalan_napas')" value="Hipersekresi jalan napas"><span class="lbl"> Hipersekresi jalan napas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_disfungsi_neuro_muskuler" onclick="checkthis('penyebab_disfungsi_neuro_muskuler')" value="Disfungsi neuro muskuler"><span class="lbl"> Disfungsi neuro muskuler</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_benda_asing" onclick="checkthis('penyebab_benda_asing')" value="Benda asing dalam jalan napas"><span class="lbl"> Benda asing dalam jalan napas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_jalan_nafas_buatan" onclick="checkthis('penyebab_jalan_nafas_buatan')" value="Adanya jalan napas buatan"><span class="lbl"> Adanya jalan napas buatan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_sekresi_tertahan" onclick="checkthis('penyebab_sekresi_tertahan')" value="Sekresi yang tertahan"><span class="lbl"> Sekresi yang tertahan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_hiperplasia_dinding" onclick="checkthis('penyebab_hiperplasia_dinding')" value="Hiperplasia dinding jalan napas"><span class="lbl"> Hiperplasia dinding jalan napas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_infeksi" onclick="checkthis('penyebab_infeksi')" value="Proses infeksi"><span class="lbl"> Proses infeksi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_alergi" onclick="checkthis('penyebab_alergi')" value="Respon alergi"><span class="lbl"> Respon alergi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_efek_farmakologis" onclick="checkthis('penyebab_efek_farmakologis')" value="Efek agen farmakologis (mis anastesi)"><span class="lbl"> Efek agen farmakologis (mis anastesi)</span></label></div>
        <br>
        <b>Situasional</b>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_merokok_aktif" onclick="checkthis('penyebab_merokok_aktif')" value="Merokok aktif"><span class="lbl"> Merokok aktif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_merokok_pasiif" onclick="checkthis('penyebab_merokok_pasiif')" value="Merokok pasif"><span class="lbl"> Merokok pasif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[penyebab][]" id="penyebab_polutan" onclick="checkthis('penyebab_polutan')" value="Terpajan polutan"><span class="lbl"> Terpajan polutan</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_63[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;"> 
          maka bersihan jalan nafas meningkat (L.01001), dengan kriteria hasil :</b>
        <div style="display: flex; flex-wrap: wrap;">
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[kriteria_hasil][]" id="hasil_batuk" onclick="checkthis('hasil_batuk')" value="Batuk efektif meningkat"><span class="lbl"> Batuk efektif meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[kriteria_hasil][]" id="hasil_sputum" onclick="checkthis('hasil_sputum')" value="Produksi sputum menurun"><span class="lbl"> Produksi sputum menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[kriteria_hasil][]" id="hasil_mengi" onclick="checkthis('hasil_mengi')" value="Mengi menurun"><span class="lbl"> Mengi menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[kriteria_hasil][]" id="hasil_wheezing" onclick="checkthis('hasil_wheezing')" value="Wheezing menurun"><span class="lbl"> Wheezing menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[kriteria_hasil][]" id="hasil_mekonium" onclick="checkthis('hasil_mekonium')" value="Mekonium (pada neonatus) menurun"><span class="lbl"> Mekonium (pada neonatus) menurun</span></label></div>
          </div>
          <div style="width: 45%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[kriteria_hasil][]" id="hasil_dispnea" onclick="checkthis('hasil_dispnea')" value="Dispnea menurun"><span class="lbl"> Dispnea menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[kriteria_hasil][]" id="hasil_orthopnea" onclick="checkthis('hasil_orthopnea')" value="Orthopnea menurun"><span class="lbl"> Orthopnea menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[kriteria_hasil][]" id="hasil_sulit_bicara" onclick="checkthis('hasil_sulit_bicara')" value="Sulit bicara menurun"><span class="lbl"> Sulit bicara menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[kriteria_hasil][]" id="hasil_sianosis" onclick="checkthis('hasil_sianosis')" value="Sianosis menurun"><span class="lbl"> Sianosis menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[kriteria_hasil][]" id="hasil_gelisah" onclick="checkthis('hasil_gelisah')" value="Gelisah menurun"><span class="lbl"> Gelisah menurun</span></label></div>
          </div>
        </div>
      </td>
    </tr>

    <!-- TANDA DAN GEJALA -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dibuktikan dengan:</b>

        <!-- TANDA DAN GEJALA MAYOR -->
        <p><b>Tanda dan Gejala Mayor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b>Subjektif:</b>
            <p>(tidak tersedia)</p>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[gejala_mayor_objektif][]" id="mayor_batuk" onclick="checkthis('mayor_batuk')" value="Batuk tidak efektif atau tidak mampu batuk"><span class="lbl"> Batuk tidak efektif atau tidak mampu batuk</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[gejala_mayor_objektif][]" id="mayor_sputum" onclick="checkthis('mayor_sputum')" value="Sputum berlebih / obstruksi jalan nafas / mekonium dijalan nafas (pada neonatus)"><span class="lbl"> Sputum berlebih / obstruksi jalan nafas / mekonium dijalan nafas (pada neonatus)</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[gejala_mayor_objektif][]" id="mayor_mengi" onclick="checkthis('mayor_mengi')" value="Mengi, whizzing dan/atau ronkhi kering"><span class="lbl"> Mengi, whizzing dan/atau ronkhi kering</span></label></div>
          </div>
        </div>

        <hr>

        <!-- TANDA DAN GEJALA MINOR -->
        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b>Subjektif:</b>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[gejala_minor_subjektif][]" id="minor_subjektif_dispnea" onclick="checkthis('minor_subjektif_dispnea')" value="Dispnea"><span class="lbl"> Dispnea</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[gejala_minor_subjektif][]" id="minor_subjektif_sulit_bicara" onclick="checkthis('minor_subjektif_sulit_bicara')" value="Sulit bicara"><span class="lbl"> Sulit bicara</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[gejala_minor_subjektif][]" id="minor_subjektif_orthopnea" onclick="checkthis('minor_subjektif_orthopnea')" value="Ortopnea"><span class="lbl"> Ortopnea</span></label></div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[gejala_minor_objektif][]" id="minor_objektif_gelisah" onclick="checkthis('minor_objektif_gelisah')" value="Gelisah"><span class="lbl"> Gelisah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[gejala_minor_objektif][]" id="minor_objektif_sianosis" onclick="checkthis('minor_objektif_sianosis')" value="Sianosis"><span class="lbl"> Sianosis</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[gejala_minor_objektif][]" id="minor_objektif_bunyi_nafas" onclick="checkthis('minor_objektif_bunyi_nafas')" value="Bunyi nafas menurun"><span class="lbl"> Bunyi nafas menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[gejala_minor_objektif][]" id="minor_objektif_frekuensi" onclick="checkthis('minor_objektif_frekuensi')" value="Frekuensi nafas berubah"><span class="lbl"> Frekuensi nafas berubah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_63[gejala_minor_objektif][]" id="minor_objektif_pola" onclick="checkthis('minor_objektif_pola')" value="Pola nafas berubah"><span class="lbl"> Pola nafas berubah</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>

<!-- <tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;"> -->

<!-- LATIHAN BATUK EFEKTIF -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- JUDUL -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>LATIHAN BATUK EFEKTIF </b>
        <i>(Melatih pasien yang tidak memiliki kemampuan batuk secara efektif untuk membersihkan laring, trakea, dan bronkiolus dari sekret atau benda asing di jalan napas)</i>
        <b>(I.01006)</b>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>TINDAKAN</b>
      </td>
    </tr>

    <!-- OBSERVASI -->
    <tr>
      <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>1</b></td>
      <td style="border: 1px solid black; padding: 5px;">
        <b>Observasi</b><br>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_63[latihan_batuk_observasi][]" id="latihan_batuk_observasi_identifikasi" onclick="checkthis('latihan_batuk_observasi_identifikasi')" value="Identifikasi kemampuan batuk">
          <span class="lbl"> Identifikasi kemampuan batuk</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_63[latihan_batuk_observasi][]" id="latihan_batuk_observasi_retensi" onclick="checkthis('latihan_batuk_observasi_retensi')" value="Monitor adanya retensi sputum">
          <span class="lbl"> Monitor adanya retensi sputum</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_63[latihan_batuk_observasi][]" id="latihan_batuk_observasi_io" onclick="checkthis('latihan_batuk_observasi_io')" value="Monitor intake dan output cairan">
          <span class="lbl"> Monitor intake dan output cairan (misal jumlah dan karakteristik)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_63[latihan_batuk_observasi][]" id="latihan_batuk_observasi_tanda" onclick="checkthis('latihan_batuk_observasi_tanda')" value="Monitor tanda dan gejala saluran napas">
          <span class="lbl"> Monitor tanda dan gejala saluran napas</span>
        </label></div>
      </td>
    </tr>

    <!-- TERAPEUTIK -->
    <tr>
      <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>2</b></td>
      <td style="border: 1px solid black; padding: 5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_63[latihan_batuk_terapeutik][]" id="latihan_batuk_terapeutik_posisi" onclick="checkthis('latihan_batuk_terapeutik_posisi')" value="Atur posisi semifowler atau fowler">
          <span class="lbl"> Atur posisi semifowler atau fowler</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_63[latihan_batuk_terapeutik][]" id="latihan_batuk_terapeutik_perlak" onclick="checkthis('latihan_batuk_terapeutik_perlak')" value="Pasang perlak dan bengkok di pangkuan pasien">
          <span class="lbl"> Pasang perlak dan bengkok di pangkuan pasien</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_63[latihan_batuk_terapeutik][]" id="latihan_batuk_terapeutik_buang" onclick="checkthis('latihan_batuk_terapeutik_buang')" value="Buang sekret pada tempat sampah">
          <span class="lbl"> Buang sekret pada tempat sampah</span>
        </label></div>
      </td>
    </tr>

    <!-- EDUKASI -->
    <tr>
      <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>3</b></td>
      <td style="border: 1px solid black; padding: 5px;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_63[latihan_batuk_edukasi][]" id="latihan_batuk_edukasi_tujuan" onclick="checkthis('latihan_batuk_edukasi_tujuan')" value="Jelaskan tujuan dan prosedur batuk efektif">
          <span class="lbl"> Jelaskan tujuan dan prosedur batuk efektif</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_63[latihan_batuk_edukasi][]" id="latihan_batuk_edukasi_tarikan" onclick="checkthis('latihan_batuk_edukasi_tarikan')" value="Anjurkan tarik napas dalam">
          <span class="lbl"> Anjurkan tarik napas dalam melalui hidung 4 detik, tahan 2 detik, keluarkan melalui mulut dengan bibir mencucu 8 detik</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_63[latihan_batuk_edukasi][]" id="latihan_batuk_edukasi_ulangi" onclick="checkthis('latihan_batuk_edukasi_ulangi')" value="Anjurkan mengulangi tarik napas dalam hingga 3 kali">
          <span class="lbl"> Anjurkan mengulangi tarik napas dalam hingga 3 kali</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_63[latihan_batuk_edukasi][]" id="latihan_batuk_edukasi_batuk" onclick="checkthis('latihan_batuk_edukasi_batuk')" value="Anjurkan batuk dengan kuat setelah napas dalam ke-3">
          <span class="lbl"> Anjurkan batuk dengan kuat langsung setelah tarik napas dalam ke-3</span>
        </label></div>
      </td>
    </tr>

    <!-- KOLABORASI -->
    <tr>
      <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;"><b>4</b></td>
      <td style="border: 1px solid black; padding: 5px;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_63[latihan_batuk_kolaborasi][]" id="latihan_batuk_kolaborasi_obat" onclick="checkthis('latihan_batuk_kolaborasi_obat')" value="Kolaborasi pemberian mukolitik atau ekspektoran jika perlu">
          <span class="lbl"> Kolaborasi pemberian mukolitik atau ekspektoran jika perlu</span>
        </label></div>
      </td>
    </tr>
  </tbody>
</table>

    <br>

<!-- MANAJEMEN JALAN NAPAS -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <tbody>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>MANAJEMEN JALAN NAPAS</b> <i>(Mengidentifikasi dan mengelola kepatenan jalan nafas)</i> <b>(I.01011)</b>
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
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_observasi][]" id="jalan_observasi_1" onclick="checkthis('jalan_observasi_1')" value="Monitoring pola napas"><span class="lbl"> Monitoring pola napas (frekuensi, kedalaman, usaha napas)</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_observasi][]" id="jalan_observasi_2" onclick="checkthis('jalan_observasi_2')" value="Monitor bunyi napas tambahan"><span class="lbl"> Monitor bunyi napas tambahan (mis gurgling, mengi, wheezing, ronkhi kering)</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_observasi][]" id="jalan_observasi_3" onclick="checkthis('jalan_observasi_3')" value="Monitor sputum"><span class="lbl"> Monitor sputum (jumlah, warna, aroma)</span></label>
        </div>
      </td>
    </tr>

    <!-- TERAPEUTIK -->
    <tr>
      <td style="text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>2</b></td>
      <td style="border: 1px solid black; padding: 5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_terapeutik][]" id="jalan_terapeutik_1" onclick="checkthis('jalan_terapeutik_1')" value="Pertahankan kepatenan jalan napas"><span class="lbl"> Pertahankan kepatenan jalan napas dengan head-tilt dan chin-lift (jaw-thrust jika curiga trauma cervical)</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_terapeutik][]" id="jalan_terapeutik_2" onclick="checkthis('jalan_terapeutik_2')" value="Posisikan semi-fowler atau fowler"><span class="lbl"> Posisikan semi-fowler atau fowler</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_terapeutik][]" id="jalan_terapeutik_3" onclick="checkthis('jalan_terapeutik_3')" value="Berikan minum hangat"><span class="lbl"> Berikan minum hangat</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_terapeutik][]" id="jalan_terapeutik_4" onclick="checkthis('jalan_terapeutik_4')" value="Fisioterapi dada"><span class="lbl"> Lakukan fisioterapi dada jika perlu</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_terapeutik][]" id="jalan_terapeutik_5" onclick="checkthis('jalan_terapeutik_5')" value="Penghisapan lendir"><span class="lbl"> Lakukan penghisapan lendir kurang dari 15 detik</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_terapeutik][]" id="jalan_terapeutik_6" onclick="checkthis('jalan_terapeutik_6')" value="Keluarkan sumbatan benda padat"><span class="lbl"> Keluarkan sumbatan benda padat dengan forsep McGill</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_terapeutik][]" id="jalan_terapeutik_7" onclick="checkthis('jalan_terapeutik_7')" value="Berikan oksigen"><span class="lbl"> Berikan oksigen jika perlu</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_terapeutik][]" id="jalan_terapeutik_8" onclick="checkthis('jalan_terapeutik_8')" value="Hiperoksigenasi sebelum penghisapan"><span class="lbl"> Lakukan hiperoksigenasi sebelum penghisapan endotrakeal</span></label>
        </div>
      </td>
    </tr>

    <!-- EDUKASI -->
    <tr>
      <td style="text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>3</b></td>
      <td style="border: 1px solid black; padding: 5px;">
        <b>Edukasi</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_edukasi][]" id="jalan_edukasi_1" onclick="checkthis('jalan_edukasi_1')" value="Ajarkan pencegahan kerusakan jaringan"><span class="lbl"> Ajarkan cara mencegah kerusakan jaringan</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_edukasi][]" id="jalan_edukasi_2" onclick="checkthis('jalan_edukasi_2')" value="Ajarkan penyesuaian suhu"><span class="lbl"> Ajarkan cara menyesuaikan suhu secara mandiri</span></label>
        </div>
      </td>
    </tr>

    <!-- KOLABORASI -->
    <tr>
      <td style="text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>4</b></td>
      <td style="border: 1px solid black; padding: 5px;">
        <b>Kolaborasi</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_63[jalan_kolaborasi][]" id="jalan_kolaborasi_1" onclick="checkthis('jalan_kolaborasi_1')" value="Kolaborasi pemberian obat"><span class="lbl"> Kolaborasi pemberian bronkodilator, ekspektoran, mukolitik, jika perlu</span></label>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>

<!-- PEMANTAUAN RESPIRASI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <tbody>
    <!-- JUDUL UTAMA -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>PEMANTAUAN RESPIRASI</b>
        <i>(Mengumpulkan dan menganalisis data untuk memastikan kepatenan jalan nafas dan keefektifan jalan nafas)</i>
        <b>(I.01014)</b>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>TINDAKAN</b>
      </td>
    </tr>

    <!-- OBSERVASI -->
    <tr>
      <td style="width: 5%; border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;">
        <b>1</b>
      </td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Observasi</b><br>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_observasi][]" id="respirasi_observasi_1" onclick="checkthis('respirasi_observasi_1')" value="Monitor frekuensi napas">
            <span class="lbl"> Monitor frekuensi, irama, kedalaman, dan upaya napas</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_observasi][]" id="respirasi_observasi_2" onclick="checkthis('respirasi_observasi_2')" value="Monitor pola napas">
            <span class="lbl"> Monitor pola napas (bradipnea, takipnea, hiperventilasi, kussmaul, cheyne-stokes, biot, ataksis)</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_observasi][]" id="respirasi_observasi_3" onclick="checkthis('respirasi_observasi_3')" value="Monitor batuk efektif">
            <span class="lbl"> Monitor kemampuan batuk efektif</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_observasi][]" id="respirasi_observasi_4" onclick="checkthis('respirasi_observasi_4')" value="Monitor produksi sputum">
            <span class="lbl"> Monitor adanya produksi sputum</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_observasi][]" id="respirasi_observasi_5" onclick="checkthis('respirasi_observasi_5')" value="Palpasi ekspansi paru">
            <span class="lbl"> Palpasi kesimetrisan ekspansi paru</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_observasi][]" id="respirasi_observasi_6" onclick="checkthis('respirasi_observasi_6')" value="Auskultasi bunyi napas">
            <span class="lbl"> Auskultasi bunyi napas</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_observasi][]" id="respirasi_observasi_7" onclick="checkthis('respirasi_observasi_7')" value="Monitor saturasi oksigen">
            <span class="lbl"> Monitor saturasi oksigen</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_observasi][]" id="respirasi_observasi_8" onclick="checkthis('respirasi_observasi_8')" value="Monitor AGD">
            <span class="lbl"> Monitor nilai AGD</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_observasi][]" id="respirasi_observasi_9" onclick="checkthis('respirasi_observasi_9')" value="Monitor sumbatan jalan napas">
            <span class="lbl"> Monitor adanya sumbatan jalan nafas</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_observasi][]" id="respirasi_observasi_10" onclick="checkthis('respirasi_observasi_10')" value="Monitor x-ray thorak">
            <span class="lbl"> Monitor hasil x-ray thorak</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- TERAPEUTIK -->
    <tr>
      <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;">
        <b>2</b>
      </td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Terapeutik</b><br>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_terapeutik][]" id="respirasi_terapeutik_1" onclick="checkthis('respirasi_terapeutik_1')" value="Atur interval pemantauan">
            <span class="lbl"> Atur interval pemantauan respirasi sesuai kondisi pasien</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_terapeutik][]" id="respirasi_terapeutik_2" onclick="checkthis('respirasi_terapeutik_2')" value="Dokumentasikan hasil">
            <span class="lbl"> Dokumentasikan hasil pemantauan</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- EDUKASI -->
    <tr>
      <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;">
        <b>3</b>
      </td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Edukasi</b><br>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_edukasi][]" id="respirasi_edukasi_1" onclick="checkthis('respirasi_edukasi_1')" value="Jelaskan tujuan pemantauan">
            <span class="lbl"> Jelaskan tujuan dan prosedur pemantauan</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_63[respirasi_edukasi][]" id="respirasi_edukasi_2" onclick="checkthis('respirasi_edukasi_2')" value="Informasikan hasil pemantauan">
            <span class="lbl"> Informasikan hasil pemantauan jika perlu</span>
          </label>
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
        <input type="text" class="input_type" name="form_63[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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