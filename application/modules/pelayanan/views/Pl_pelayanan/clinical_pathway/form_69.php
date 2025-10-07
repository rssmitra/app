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
      var hiddenInputName = 'form_69[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 29 september 2025</p> -->
<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN:<br> NAUSEA</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
        Definisi: Perasaan tidak nyaman pada bagian belakang tenggorok atau lambung yang dapat mengakibatkan muntah
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_biokimiawi" onclick="checkthis('penyebab_biokimiawi')" value="Gangguan biokimiawi (mis. Uremia, ketoasidosis, diabetic)"><span class="lbl"> Gangguan biokimiawi (mis. Uremia, ketoasidosis, diabetic)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_esophagus" onclick="checkthis('penyebab_esophagus')" value="Gangguan pada esophagus"><span class="lbl"> Gangguan pada esophagus</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_distensi" onclick="checkthis('penyebab_distensi')" value="Distensi lambung"><span class="lbl"> Distensi lambung</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_iritasi" onclick="checkthis('penyebab_iritasi')" value="Iritasi lambung"><span class="lbl"> Iritasi lambung</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_pancreas" onclick="checkthis('penyebab_pancreas')" value="Gangguan pancreas"><span class="lbl"> Gangguan pancreas</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_limpa" onclick="checkthis('penyebab_limpa')" value="Peregangan kapsul limpa"><span class="lbl"> Peregangan kapsul limpa</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_tumor" onclick="checkthis('penyebab_tumor')" value="Tumor terlokalisasi (mis. Neuroma akustik, tumor otak primer/sekunder, metastasis tulang dasar tengkorak)"><span class="lbl"> Tumor terlokalisasi (mis. Neuroma akustik, tumor otak primer/sekunder, metastasis tulang dasar tengkorak)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_intraabdomen" onclick="checkthis('penyebab_intraabdomen')" value="Peningkatan tekanan intraabdominal (mis. Keganasan intraabdomen)"><span class="lbl"> Peningkatan tekanan intraabdominal (mis. Keganasan intraabdomen)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_intracranial" onclick="checkthis('penyebab_intracranial')" value="Peningkatan tekanan intracranial"><span class="lbl"> Peningkatan tekanan intracranial</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_intraorbital" onclick="checkthis('penyebab_intraorbital')" value="Peningkatan tekanan intraorbital (mis. Glaukoma)"><span class="lbl"> Peningkatan tekanan intraorbital (mis. Glaukoma)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_mabuk" onclick="checkthis('penyebab_mabuk')" value="Mabuk perjalanan"><span class="lbl"> Mabuk perjalanan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_kehamilan" onclick="checkthis('penyebab_kehamilan')" value="Kehamilan"><span class="lbl"> Kehamilan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_aroma" onclick="checkthis('penyebab_aroma')" value="Aroma tidak sedap"><span class="lbl"> Aroma tidak sedap</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_rasa_makanan" onclick="checkthis('penyebab_rasa_makanan')" value="Rasa makanan/minuman yang tidak enak"><span class="lbl"> Rasa makanan/minuman yang tidak enak</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_visual" onclick="checkthis('penyebab_visual')" value="Stimulus penglihatan tidak menyenangkan"><span class="lbl"> Stimulus penglihatan tidak menyenangkan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_psikologis" onclick="checkthis('penyebab_psikologis')" value="Faktor psikologis (mis. Kecemasan, ketakutan, stress)"><span class="lbl"> Faktor psikologis (mis. Kecemasan, ketakutan, stress)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_farmako" onclick="checkthis('penyebab_farmako')" value="Efek agen farmakologis"><span class="lbl"> Efek agen farmakologis</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[penyebab][]" id="penyebab_toksin" onclick="checkthis('penyebab_toksin')" value="Efek toksin"><span class="lbl"> Efek toksin</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_69[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;"> 
          maka Tingkat Nausea (L.08065) menurun, dengan kriteria hasil :</b>

        <div style="display: flex; flex-wrap: wrap;">
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[kriteria_hasil][]" id="hasil_nafsu_makan" onclick="checkthis('hasil_nafsu_makan')" value="Nafsu makan meningkat"><span class="lbl"> Nafsu makan meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[kriteria_hasil][]" id="hasil_keluhan_mual" onclick="checkthis('hasil_keluhan_mual')" value="Keluhan mual menurun"><span class="lbl"> Keluhan mual menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[kriteria_hasil][]" id="hasil_ingin_muntah" onclick="checkthis('hasil_ingin_muntah')" value="Perasaan ingin muntah menurun"><span class="lbl"> Perasaan ingin muntah menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[kriteria_hasil][]" id="hasil_asam_mulut" onclick="checkthis('hasil_asam_mulut')" value="Perasaan asam di mulut menurun"><span class="lbl"> Perasaan asam di mulut menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[kriteria_hasil][]" id="hasil_sensasi_panas" onclick="checkthis('hasil_sensasi_panas')" value="Sensasi panas menurun"><span class="lbl"> Sensasi panas menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[kriteria_hasil][]" id="hasil_sensasi_dingin" onclick="checkthis('hasil_sensasi_dingin')" value="Sensasi dingin menurun"><span class="lbl"> Sensasi dingin menurun</span></label></div>
          </div>

          <div style="width: 45%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[kriteria_hasil][]" id="hasil_menelan" onclick="checkthis('hasil_menelan')" value="Frekuensi menelan menurun"><span class="lbl"> Frekuensi menelan menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[kriteria_hasil][]" id="hasil_diaforesis" onclick="checkthis('hasil_diaforesis')" value="Diaforesis menurun"><span class="lbl"> Diaforesis menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[kriteria_hasil][]" id="hasil_saliva" onclick="checkthis('hasil_saliva')" value="Jumlah saliva menurun"><span class="lbl"> Jumlah saliva menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[kriteria_hasil][]" id="hasil_pucat" onclick="checkthis('hasil_pucat')" value="Pucat membaik"><span class="lbl"> Pucat membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[kriteria_hasil][]" id="hasil_takikardia" onclick="checkthis('hasil_takikardia')" value="Takikardia membaik"><span class="lbl"> Takikardia membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[kriteria_hasil][]" id="hasil_pupil" onclick="checkthis('hasil_pupil')" value="Dilatasi pupil membaik"><span class="lbl"> Dilatasi pupil membaik</span></label></div>
          </div>
        </div>
      </td>
    </tr>

    <!-- TANDA & GEJALA MAYOR -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dibuktikan dengan :</b>
        <p><b>Tanda dan Gejala Mayor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[mayor_subjektif][]" id="mayor_mual" onclick="checkthis('mayor_mual')" value="Mengeluh mual"><span class="lbl"> Mengeluh mual</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[mayor_subjektif][]" id="mayor_ingin_muntah" onclick="checkthis('mayor_ingin_muntah')" value="Merasa ingin muntah"><span class="lbl"> Merasa ingin muntah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[mayor_subjektif][]" id="mayor_tidak_makan" onclick="checkthis('mayor_tidak_makan')" value="Tidak berminat makan"><span class="lbl"> Tidak berminat makan</span></label></div>
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <i>(Tidak tersedia)</i>
          </div>
        </div>

        <hr>

        <!-- TANDA & GEJALA MINOR -->
        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[minor_subjektif][]" id="minor_asam_mulut" onclick="checkthis('minor_asam_mulut')" value="Merasa asam di mulut"><span class="lbl"> Merasa asam di mulut</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[minor_subjektif][]" id="minor_sensasi" onclick="checkthis('minor_sensasi')" value="Sensasi panas/dingin"><span class="lbl"> Sensasi panas/dingin</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[minor_subjektif][]" id="minor_menelan" onclick="checkthis('minor_menelan')" value="Sering menelan"><span class="lbl"> Sering menelan</span></label></div>
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[minor_objektif][]" id="minor_saliva" onclick="checkthis('minor_saliva')" value="Saliva meningkat"><span class="lbl"> Saliva meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[minor_objektif][]" id="minor_pucat" onclick="checkthis('minor_pucat')" value="Pucat"><span class="lbl"> Pucat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[minor_objektif][]" id="minor_diaforesis" onclick="checkthis('minor_diaforesis')" value="Diaforesis"><span class="lbl"> Diaforesis</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[minor_objektif][]" id="minor_takikardia" onclick="checkthis('minor_takikardia')" value="Takikardia"><span class="lbl"> Takikardia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_69[minor_objektif][]" id="minor_pupil" onclick="checkthis('minor_pupil')" value="Pupil dilatasi"><span class="lbl"> Pupil dilatasi</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->

<!-- MANAJEMEN MUAL -->
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
    <b>Manajemen Mual </b>
        <i>(Mengidentifikasi dan mengelola perasaan tidak enak pada bagian tenggorok atau lambung yang dapat menyebabkan muntah)</i> 
        <b>(I.03117)</b><br>
  </td>
</tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>TINDAKAN</b>
      </td>
    </tr>

    <!-- OBSERVASI -->
    <tr>
      <td style="width:5%; text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_observasi][]" id="mual_observasi_1" onclick="checkthis('mual_observasi_1')" value="Identifikasi pengalaman mual">
          <span class="lbl"> Identifikasi pengalaman mual</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_observasi][]" id="mual_observasi_2" onclick="checkthis('mual_observasi_2')" value="Identifikasi isyarat nonverbal ketidaknyamanan">
          <span class="lbl"> Identifikasi isyarat nonverbal ketidaknyamanan (mis. bayi, anak-anak, dan mereka yang tidak dapat berkomunikasi secara efektif)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_observasi][]" id="mual_observasi_3" onclick="checkthis('mual_observasi_3')" value="Identifikasi dampak mual">
          <span class="lbl"> Identifikasi dampak mual terhadap kualitas hidup (mis. nafsu makan, aktivitas, kinerja tanggung jawab peran dan tidur)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_observasi][]" id="mual_observasi_4" onclick="checkthis('mual_observasi_4')" value="Identifikasi faktor penyebab mual">
          <span class="lbl"> Identifikasi faktor penyebab mual (mis. pengobatan dan prosedur)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_observasi][]" id="mual_observasi_5" onclick="checkthis('mual_observasi_5')" value="Identifikasi antiemetik">
          <span class="lbl"> Identifikasi antiemetic untuk mencegah mual (kecuali mual pada kehamilan)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_observasi][]" id="mual_observasi_6" onclick="checkthis('mual_observasi_6')" value="Monitor mual">
          <span class="lbl"> Monitor mual (mis. frekuensi, durasi, dan tingkat keparahan)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_observasi][]" id="mual_observasi_7" onclick="checkthis('mual_observasi_7')" value="Monitor nutrisi">
          <span class="lbl"> Monitor asupan nutrisi dan kalori</span>
        </label></div>
      </td>
    </tr>

    <!-- TERAPEUTIK -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_terapeutik][]" id="mual_terapeutik_1" onclick="checkthis('mual_terapeutik_1')" value="Kendalikan faktor lingkungan">
          <span class="lbl"> Kendalikan faktor lingkungan penyebab mual (mis. bau tak sedap, suara, rangsangan visual yang tidak menyenangkan)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_terapeutik][]" id="mual_terapeutik_2" onclick="checkthis('mual_terapeutik_2')" value="Kurangi keadaan penyebab mual">
          <span class="lbl"> Kurangi atau hilangkan keadaan penyebab mual (mis. kecemasan, ketakutan, kelelahan)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_terapeutik][]" id="mual_terapeutik_3" onclick="checkthis('mual_terapeutik_3')" value="Berikan makanan kecil">
          <span class="lbl"> Berikan makanan dalam jumlah kecil dan menarik</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_terapeutik][]" id="mual_terapeutik_4" onclick="checkthis('mual_terapeutik_4')" value="Berikan makanan dingin">
          <span class="lbl"> Berikan makanan dingin, cairan bening, tidak berbau, dan tidak berwarna, jika perlu</span>
        </label></div>
      </td>
    </tr>

    <!-- EDUKASI -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_edukasi][]" id="mual_edukasi_1" onclick="checkthis('mual_edukasi_1')" value="Istirahat cukup">
          <span class="lbl"> Anjurkan istirahat dan tidur yang cukup</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_edukasi][]" id="mual_edukasi_2" onclick="checkthis('mual_edukasi_2')" value="Membersihkan mulut">
          <span class="lbl"> Anjurkan sering membersihkan mulut, kecuali jika merangsang mual</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_edukasi][]" id="mual_edukasi_3" onclick="checkthis('mual_edukasi_3')" value="Makanan tinggi karbohidrat">
          <span class="lbl"> Anjurkan makanan tinggi karbohidrat dan rendah lemak</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_edukasi][]" id="mual_edukasi_4" onclick="checkthis('mual_edukasi_4')" value="Teknik nonfarmakologis">
          <span class="lbl"> Ajarkan penggunaan teknik nonfarmakologis untuk mengatasi mual (mis. biofeedback, hipnosis, relaksasi, terapi musik, akupresur)</span>
        </label></div>
      </td>
    </tr>

    <!-- KOLABORASI -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_69[mual_kolaborasi][]" id="mual_kolaborasi_1" onclick="checkthis('mual_kolaborasi_1')" value="Kolaborasi pemberian antiemetik">
          <span class="lbl"> Kolaborasi pemberian antiemetik .... </span>
        </label></div>
        <input type="text" class="input_type" name="form_69[mual_kolaborasi_ket]" id="mual_kolaborasi_ket" onchange="fillthis('mual_kolaborasi_ket')" style="width:70%;">
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
        <input type="text" class="input_type" name="form_69[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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