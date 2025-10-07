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
      var hiddenInputName = 'form_74[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 01 oktober 2025</p> -->

<div style="text-align: center; font-size: 18px;">
  <b>DIAGNOSIS KEPERAWATAN: POLA NAFAS TIDAK EFEKTIF</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
        Definisi : Inspirasi dan/ atau ekspirasi yang tidak memberikan ventilasi adekuat
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_depresi" onclick="checkthis('penyebab_depresi')" value="Depresi pusat pernafasan"><span class="lbl"> Depresi pusat pernafasan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_hambatan" onclick="checkthis('penyebab_hambatan')" value="Hambatan upaya nafas"><span class="lbl"> Hambatan upaya nafas (misal Nyeri saat bernafas, kelemahan otot pernafasan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_dinding" onclick="checkthis('penyebab_dinding')" value="Deformitas dinding dada"><span class="lbl"> Deformitas dinding dada</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_tulang" onclick="checkthis('penyebab_tulang')" value="Deformitas tulang dada"><span class="lbl"> Deformitas tulang dada</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_neuromuskular" onclick="checkthis('penyebab_neuromuskular')" value="Gangguan neuromuskular"><span class="lbl"> Gangguan neuromuskular</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_neurologis" onclick="checkthis('penyebab_neurologis')" value="Gangguan neurologis"><span class="lbl"> Gangguan neurologis (misal EEG positif, cedera kepala, gangguan kejang)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_imaturitas" onclick="checkthis('penyebab_imaturitas')" value="Imaturitas neurologis"><span class="lbl"> Imaturitas neurologis</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_energi" onclick="checkthis('penyebab_energi')" value="Penurunan energi"><span class="lbl"> Penurunan energi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_obesitas" onclick="checkthis('penyebab_obesitas')" value="Obesitas"><span class="lbl"> Obesitas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_posisi" onclick="checkthis('penyebab_posisi')" value="Posisi tubuh yang menghambat ekspansi paru"><span class="lbl"> Posisi tubuh yang menghambat ekspansi paru</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_hypoventilasi" onclick="checkthis('penyebab_hypoventilasi')" value="Sindrom hypoventilasi"><span class="lbl"> Sindrom hypoventilasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_diafragma" onclick="checkthis('penyebab_diafragma')" value="Kerusakan inervasi diafragma"><span class="lbl"> Kerusakan inervasi diafragma (kerusakan saraf C5 keatas)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_spinal" onclick="checkthis('penyebab_spinal')" value="Cedera pada medulaspinalis"><span class="lbl"> Cedera pada medulaspinalis</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_farmakologis" onclick="checkthis('penyebab_farmakologis')" value="Efek agen farmakologis"><span class="lbl"> Efek agen farmakologis</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[penyebab][]" id="penyebab_kecemasan" onclick="checkthis('penyebab_kecemasan')" value="Kecemasan"><span class="lbl"> Kecemasan</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama
          <input type="text" class="input_type" name="form_74[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Pola Nafas tidak efektif membaik (L.01004), dengan kriteria hasil:</b>
        
        <div style="display: flex; flex-wrap: wrap; margin-top: 5px;">
          <!-- KIRI -->
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_ventilasi" onclick="checkthis('hasil_ventilasi')" value="Ventilasi semenit meningkat"><span class="lbl"> Ventilasi semenit meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_vital" onclick="checkthis('hasil_vital')" value="Kapasitas vital meningkat"><span class="lbl"> Kapasitas vital meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_thorax" onclick="checkthis('hasil_thorax')" value="Diameter thorax anterior-posterior meningkat"><span class="lbl"> Diameter thorax anterior-posterior meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_ekspirasi" onclick="checkthis('hasil_ekspirasi')" value="Tekanan ekspirasi meningkat"><span class="lbl"> Tekanan ekspirasi meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_inspirasi" onclick="checkthis('hasil_inspirasi')" value="Tekanan inspirasi meningkat"><span class="lbl"> Tekanan inspirasi meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_dispnea" onclick="checkthis('hasil_dispnea')" value="Dispnea menurun"><span class="lbl"> Dispnea menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_otot_bantu" onclick="checkthis('hasil_otot_bantu')" value="Penggunaan otot bantu nafas menurun"><span class="lbl"> Penggunaan otot bantu nafas menurun</span></label></div>
          </div>

          <!-- KANAN -->
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_ekspirasi_fase" onclick="checkthis('hasil_ekspirasi_fase')" value="Pemanjangan fase ekspirasi menurun"><span class="lbl"> Pemanjangan fase ekspirasi menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_ortopnea" onclick="checkthis('hasil_ortopnea')" value="Ortopnea menurun"><span class="lbl"> Ortopnea menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_pursedlip" onclick="checkthis('hasil_pursedlip')" value="Pernafasan pursed-lip menurun"><span class="lbl"> Pernafasan pursed-lip menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_cuping" onclick="checkthis('hasil_cuping')" value="Pernafasan cuping hidung menurun"><span class="lbl"> Pernafasan cuping hidung menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_frekuensi" onclick="checkthis('hasil_frekuensi')" value="Frekuensi nafas membaik"><span class="lbl"> Frekuensi nafas membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_kedalaman" onclick="checkthis('hasil_kedalaman')" value="Kedalaman nafas membaik"><span class="lbl"> Kedalaman nafas membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[kriteria_hasil][]" id="hasil_ekskursi" onclick="checkthis('hasil_ekskursi')" value="Ekskursi dada membaik"><span class="lbl"> Ekskursi dada membaik</span></label></div>
          </div>
        </div>
      </td>
    </tr>

    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dibuktikan dengan:</b><br>
        <p><b>Tanda dan Gejala Mayor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mayor_subjektif][]" id="mayor_dispnea" onclick="checkthis('mayor_dispnea')" value="Dispnea"><span class="lbl"> Dispnea</span></label></div>
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mayor_objektif][]" id="mayor_otot_bantu" onclick="checkthis('mayor_otot_bantu')" value="Penggunaan otot bantu pernafasan"><span class="lbl"> Penggunaan otot bantu pernafasan</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mayor_objektif][]" id="mayor_fase" onclick="checkthis('mayor_fase')" value="Fase ekspansi memanjang"><span class="lbl"> Fase ekspansi memanjang</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mayor_objektif][]" id="mayor_pola" onclick="checkthis('mayor_pola')" value="Pola nafas abnormal"><span class="lbl"> Pola nafas abnormal (mis. Takipnea, bradipnea, hiperventilasi, kussmaul, cheyn-stokes)</span></label></div>
          </div>
        </div>

        <hr>
        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[minor_subjektif][]" id="minor_ortopnea" onclick="checkthis('minor_ortopnea')" value="Ortopnea"><span class="lbl"> Ortopnea</span></label></div>
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[minor_objektif][]" id="minor_pursedlip" onclick="checkthis('minor_pursedlip')" value="Pernafasan pursed-lip"><span class="lbl"> Pernafasan pursed-lip</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[minor_objektif][]" id="minor_cuping" onclick="checkthis('minor_cuping')" value="Pernafasan cuping hidung"><span class="lbl"> Pernafasan cuping hidung</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[minor_objektif][]" id="minor_thorax" onclick="checkthis('minor_thorax')" value="Diameter thoraxs anterior-posterior meningkat"><span class="lbl"> Diameter thoraxs anterior-posterior meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[minor_objektif][]" id="minor_ventilasi" onclick="checkthis('minor_ventilasi')" value="Ventilasi semenit menurun"><span class="lbl"> Ventilasi semenit menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[minor_objektif][]" id="minor_vital" onclick="checkthis('minor_vital')" value="Kapasitas vital menurun"><span class="lbl"> Kapasitas vital menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[minor_objektif][]" id="minor_ekspirasi" onclick="checkthis('minor_ekspirasi')" value="Tekanan ekspirasi menurun"><span class="lbl"> Tekanan ekspirasi menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[minor_objektif][]" id="minor_inspirasi" onclick="checkthis('minor_inspirasi')" value="Tekanan inspirasi menurun"><span class="lbl"> Tekanan inspirasi menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[minor_objektif][]" id="minor_ekskursi" onclick="checkthis('minor_ekskursi')" value="Ekskursi dada berubah"><span class="lbl"> Ekskursi dada berubah</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->


<!-- MANAJEMEN JALAN NAFAS & PEMANTAUAN RESPIRASI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Manajemen Jalan Nafas -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Manajemen jalan nafas</b><br>
        <i>(Mengidentifikasi dan mengelola kepatenan jalan nafas)</i><br>
        <b>(I.01011)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_observasi][]" id="mjn_observasi_1" onclick="checkthis('mjn_observasi_1')" value="Monitor pola nafas"><span class="lbl"> Monitor pola nafas (frekuensi, kedalaman, usaha nafas)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_observasi][]" id="mjn_observasi_2" onclick="checkthis('mjn_observasi_2')" value="Monitor bunyi nafas tambahan"><span class="lbl"> Monitor bunyi nafas tambahan (gurgling, mengi, wheezing, ronkhi kering)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_observasi][]" id="mjn_observasi_3" onclick="checkthis('mjn_observasi_3')" value="Monitor sputum"><span class="lbl"> Monitor sputum (jumlah, warna, aroma)</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_terapeutik][]" id="mjn_terapeutik_1" onclick="checkthis('mjn_terapeutik_1')" value="Head tilt chin lift"><span class="lbl"> Pertahankan kepatenan jalan nafas dengan head-tilt dan chin-lift (jaw-thrust jika curiga trauma servikal)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_terapeutik][]" id="mjn_terapeutik_2" onclick="checkthis('mjn_terapeutik_2')" value="Posisi semi fowler"><span class="lbl"> Posisikan semi fowler atau fowler</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_terapeutik][]" id="mjn_terapeutik_3" onclick="checkthis('mjn_terapeutik_3')" value="Berikan minum hangat"><span class="lbl"> Berikan minum hangat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_terapeutik][]" id="mjn_terapeutik_4" onclick="checkthis('mjn_terapeutik_4')" value="Fisioterapi dada"><span class="lbl"> Lakukan fisioterapi dada jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_terapeutik][]" id="mjn_terapeutik_5" onclick="checkthis('mjn_terapeutik_5')" value="Penghisapan lendir"><span class="lbl"> Lakukan penghisapan lendir kurang dari 15 detik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_terapeutik][]" id="mjn_terapeutik_6" onclick="checkthis('mjn_terapeutik_6')" value="Hiperoksigenasi ET"><span class="lbl"> Lakukan hiperoksigenasi sebelum penghisapan endotracheal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_terapeutik][]" id="mjn_terapeutik_7" onclick="checkthis('mjn_terapeutik_7')" value="Forsep Mcgill"><span class="lbl"> Keluarkan sumbatan benda padat dengan forsep Mcgill</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_terapeutik][]" id="mjn_terapeutik_8" onclick="checkthis('mjn_terapeutik_8')" value="Oksigen bila perlu"><span class="lbl"> Berikan oksigen jika perlu</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_edukasi][]" id="mjn_edukasi_1" onclick="checkthis('mjn_edukasi_1')" value="Cairan 2000ml"><span class="lbl"> Anjurkan asupan cairan 2000ml/hari, jika tidak kontraindikasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_edukasi][]" id="mjn_edukasi_2" onclick="checkthis('mjn_edukasi_2')" value="Batuk efektif"><span class="lbl"> Ajarkan teknik batuk efektif</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[mjn_kolaborasi][]" id="mjn_kolaborasi_1" onclick="checkthis('mjn_kolaborasi_1')" value="Obat bronkodilator"><span class="lbl"> Kolaborasi pemberian bronkodilator, ekpektoran, mukolitik</span></label></div>
      </td>
    </tr>

    <!-- PEMANTAUAN RESPIRASI -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Pemantauan Respirasi</b><br>
        <i>(Mengumpulkan dan menganalisis data untuk memastikan kepatenan jalan nafas dan keefektifan pertukaran gas)</i><br>
        <b>(I.01014)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_observasi][]" id="pr_observasi_1" onclick="checkthis('pr_observasi_1')" value="Monitor frekuensi"><span class="lbl"> Monitor frekuensi, irama, kedalaman, dan upaya napas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_observasi][]" id="pr_observasi_2" onclick="checkthis('pr_observasi_2')" value="Monitor pola nafas"><span class="lbl"> Monitor pola nafas (bradipnea, takipnea, hiperventilasi, kussmaul, cheyne-stokes, biot, ataksis)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_observasi][]" id="pr_observasi_3" onclick="checkthis('pr_observasi_3')" value="Batuk efektif"><span class="lbl"> Monitor kemampuan batuk efektif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_observasi][]" id="pr_observasi_4" onclick="checkthis('pr_observasi_4')" value="Produksi sputum"><span class="lbl"> Monitor adanya produksi sputum</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_observasi][]" id="pr_observasi_5" onclick="checkthis('pr_observasi_5')" value="Palpasi ekspansi"><span class="lbl"> Palpasi kesimetrisan ekspansi paru</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_observasi][]" id="pr_observasi_6" onclick="checkthis('pr_observasi_6')" value="Auskultasi"><span class="lbl"> Auskultasi bunyi napas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_observasi][]" id="pr_observasi_7" onclick="checkthis('pr_observasi_7')" value="Saturasi oksigen"><span class="lbl"> Monitor saturasi oksigen</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_observasi][]" id="pr_observasi_8" onclick="checkthis('pr_observasi_8')" value="AGD"><span class="lbl"> Monitor nilai AGD</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_observasi][]" id="pr_observasi_9" onclick="checkthis('pr_observasi_9')" value="Sumbatan jalan nafas"><span class="lbl"> Monitor adanya sumbatan jalan nafas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_observasi][]" id="pr_observasi_10" onclick="checkthis('pr_observasi_10')" value="Xray thorax"><span class="lbl"> Monitor hasil x-ray thorak</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_terapeutik][]" id="pr_terapeutik_1" onclick="checkthis('pr_terapeutik_1')" value="Atur interval"><span class="lbl"> Atur interval pemantauan respirasi sesuai kondisi pasien</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_terapeutik][]" id="pr_terapeutik_2" onclick="checkthis('pr_terapeutik_2')" value="Dokumentasi"><span class="lbl"> Dokumentasikan hasil pemantauan</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_edukasi][]" id="pr_edukasi_1" onclick="checkthis('pr_edukasi_1')" value="Tujuan prosedur"><span class="lbl"> Jelaskan tujuan dan prosedur pemantauan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_74[pr_edukasi][]" id="pr_edukasi_2" onclick="checkthis('pr_edukasi_2')" value="Informasikan hasil"><span class="lbl"> Informasikan hasil pemantauan jika perlu</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<!-- END -->



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
        <input type="text" class="input_type" name="form_74[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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