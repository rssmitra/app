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
      var hiddenInputName = 'form_106[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 10 oktober 2025</p> -->

<div style="text-align: center; font-size: 18px;">
  <b>DIAGNOSIS KEPERAWATAN: GANGGUAN MOBILITAS FISIK</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Keterbatasan dalam gerakan fisik dari satu atau lebih ekstremitas secara mandiri.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
  <b>PENYEBAB / Berhubungan dengan:</b><br>

  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab1" onclick="checkthis('mobilitas_penyebab1')" value="Kerusakan integritas struktur tulang"><span class="lbl"> Kerusakan integritas struktur tulang</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab2" onclick="checkthis('mobilitas_penyebab2')" value="Perubahan metabolisme"><span class="lbl"> Perubahan metabolisme</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab3" onclick="checkthis('mobilitas_penyebab3')" value="Ketidakbugaran fisik"><span class="lbl"> Ketidakbugaran fisik</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab4" onclick="checkthis('mobilitas_penyebab4')" value="Penurunan kendali otot"><span class="lbl"> Penurunan kendali otot</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab5" onclick="checkthis('mobilitas_penyebab5')" value="Penurunan massa otot"><span class="lbl"> Penurunan massa otot</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab6" onclick="checkthis('mobilitas_penyebab6')" value="Penurunan kekuatan otot"><span class="lbl"> Penurunan kekuatan otot</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab7" onclick="checkthis('mobilitas_penyebab7')" value="Keterlambatan perkembangan"><span class="lbl"> Keterlambatan perkembangan</span></label></div>

  <!-- Dipisah: Kekakuan sendi dan Kontraktur -->
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab8a" onclick="checkthis('mobilitas_penyebab8a')" value="Kekakuan sendi"><span class="lbl"> Kekakuan sendi</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab8b" onclick="checkthis('mobilitas_penyebab8b')" value="Kontraktur"><span class="lbl"> Kontraktur</span></label></div>

  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab9" onclick="checkthis('mobilitas_penyebab9')" value="Malnutrisi"><span class="lbl"> Malnutrisi</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab10" onclick="checkthis('mobilitas_penyebab10')" value="Gangguan muskuloskeletal"><span class="lbl"> Gangguan muskuloskeletal</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab11" onclick="checkthis('mobilitas_penyebab11')" value="Gangguan neuromuskular"><span class="lbl"> Gangguan neuromuskular</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab12" onclick="checkthis('mobilitas_penyebab12')" value="Indeks massa tubuh di atas persentil ke-75 sesuai usia"><span class="lbl"> Indeks massa tubuh di atas persentil ke-75 sesuai usia</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab13" onclick="checkthis('mobilitas_penyebab13')" value="Efek agen farmakologis"><span class="lbl"> Efek agen farmakologis</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab14" onclick="checkthis('mobilitas_penyebab14')" value="Program pembatasan"><span class="lbl"> Program pembatasan</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab15" onclick="checkthis('mobilitas_penyebab15')" value="Nyeri"><span class="lbl"> Nyeri</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab16" onclick="checkthis('mobilitas_penyebab16')" value="Kurang terpapar informasi tentang aktivitas fisik"><span class="lbl"> Kurang terpapar informasi tentang aktivitas fisik</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab17" onclick="checkthis('mobilitas_penyebab17')" value="Kecemasan"><span class="lbl"> Kecemasan</span></label></div>

  <!-- Dipisah: Gangguan kognitif dan Keengganan melakukan pergerakan -->
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab18a" onclick="checkthis('mobilitas_penyebab18a')" value="Gangguan kognitif"><span class="lbl"> Gangguan kognitif</span></label></div>
  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab18b" onclick="checkthis('mobilitas_penyebab18b')" value="Keengganan melakukan pergerakan"><span class="lbl"> Keengganan melakukan pergerakan</span></label></div>

  <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[penyebab][]" id="mobilitas_penyebab19" onclick="checkthis('mobilitas_penyebab19')" value="Gangguan sensoripersepsi"><span class="lbl"> Gangguan sensoripersepsi</span></label></div>
</td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_106[mobilitas_intervensi_selama]" id="mobilitas_intervensi_selama" onchange="fillthis('mobilitas_intervensi_selama')" style="width:10%;">
          , Mobilitas fisik meningkat (L.05042) dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[kriteria_hasil][]" id="mobilitas_krit1" onclick="checkthis('mobilitas_krit1')" value="Pergerakan ekstremitas meningkat"><span class="lbl"> Pergerakan ekstremitas meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[kriteria_hasil][]" id="mobilitas_krit2" onclick="checkthis('mobilitas_krit2')" value="Kekuatan otot meningkat"><span class="lbl"> Kekuatan otot meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[kriteria_hasil][]" id="mobilitas_krit3" onclick="checkthis('mobilitas_krit3')" value="Rentang gerak (ROM) meningkat"><span class="lbl"> Rentang gerak (ROM) meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[kriteria_hasil][]" id="mobilitas_krit4" onclick="checkthis('mobilitas_krit4')" value="Nyeri menurun"><span class="lbl"> Nyeri menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[kriteria_hasil][]" id="mobilitas_krit5" onclick="checkthis('mobilitas_krit5')" value="Kecemasan menurun"><span class="lbl"> Kecemasan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[kriteria_hasil][]" id="mobilitas_krit6" onclick="checkthis('mobilitas_krit6')" value="Kaku sendi menurun"><span class="lbl"> Kaku sendi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[kriteria_hasil][]" id="mobilitas_krit7" onclick="checkthis('mobilitas_krit7')" value="Gerakan tidak terkoordinasi menurun"><span class="lbl"> Gerakan tidak terkoordinasi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[kriteria_hasil][]" id="mobilitas_krit8" onclick="checkthis('mobilitas_krit8')" value="Gerakan terbatas menurun"><span class="lbl"> Gerakan terbatas menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[kriteria_hasil][]" id="mobilitas_krit9" onclick="checkthis('mobilitas_krit9')" value="Kelemahan fisik menurun"><span class="lbl"> Kelemahan fisik menurun</span></label></div>
      </td>
    </tr>

    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Dibuktikan dengan:</b><br>

        <p><b>Gejala dan Tanda Mayor:</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif :</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mayor_subjektif][]" id="mobilitas_mayor_sub1" onclick="checkthis('mobilitas_mayor_sub1')" value="Mengeluh sulit menggerakan ekstremitas"><span class="lbl"> Mengeluh sulit menggerakan ekstremitas</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif :</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mayor_objektif][]" id="mobilitas_mayor_obj1" onclick="checkthis('mobilitas_mayor_obj1')" value="Kekuatan otot menurun"><span class="lbl"> Kekuatan otot menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mayor_objektif][]" id="mobilitas_mayor_obj2" onclick="checkthis('mobilitas_mayor_obj2')" value="Rentang gerak (ROM) menurun"><span class="lbl"> Rentang gerak (ROM) menurun</span></label></div>
          </div>
        </div>

        <hr>
        <p><b>Gejala dan Tanda Minor:</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif :</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[minor_subjektif][]" id="mobilitas_minor_sub1" onclick="checkthis('mobilitas_minor_sub1')" value="Nyeri saat bergerak"><span class="lbl"> Nyeri saat bergerak</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[minor_subjektif][]" id="mobilitas_minor_sub2" onclick="checkthis('mobilitas_minor_sub2')" value="Enggan melakukan pergerakan"><span class="lbl"> Enggan melakukan pergerakan</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[minor_subjektif][]" id="mobilitas_minor_sub3" onclick="checkthis('mobilitas_minor_sub3')" value="Merasa cemas saat bergerak"><span class="lbl"> Merasa cemas saat bergerak</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif :</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[minor_objektif][]" id="mobilitas_minor_obj1" onclick="checkthis('mobilitas_minor_obj1')" value="Sendi kaku"><span class="lbl"> Sendi kaku</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[minor_objektif][]" id="mobilitas_minor_obj2" onclick="checkthis('mobilitas_minor_obj2')" value="Gerakan tidak terkoordinasi"><span class="lbl"> Gerakan tidak terkoordinasi</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[minor_objektif][]" id="mobilitas_minor_obj3" onclick="checkthis('mobilitas_minor_obj3')" value="Gerakan terbatas"><span class="lbl"> Gerakan terbatas</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[minor_objektif][]" id="mobilitas_minor_obj4" onclick="checkthis('mobilitas_minor_obj4')" value="Fisik lemah"><span class="lbl"> Fisik lemah</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->


<!-- DUKUNGAN AMBULASI & MOBILISASI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>

    <!-- Dukungan Ambulasi -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dukungan Ambulasi</b><br>
        <i>(Memfasilitasi pasien untuk meningkatkan aktivitas berpindah)</i><br>
        <b>(I.06171)</b>
      </td>
    </tr>

    <!-- Observasi Ambulasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[ambulasi_observasi][]" id="ambulasi_observasi1" onclick="checkthis('ambulasi_observasi1')" value="Identifikasi adanya nyeri atau keluhan fisik lainnya"><span class="lbl"> Identifikasi adanya nyeri atau keluhan fisik lainnya</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[ambulasi_observasi][]" id="ambulasi_observasi2" onclick="checkthis('ambulasi_observasi2')" value="Identifikasi toleransi fisik melakukan ambulasi"><span class="lbl"> Identifikasi toleransi fisik melakukan ambulasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[ambulasi_observasi][]" id="ambulasi_observasi3" onclick="checkthis('ambulasi_observasi3')" value="Monitor frekuensi jantung dan tekanan darah sebelum memulai ambulasi"><span class="lbl"> Monitor frekuensi jantung dan tekanan darah sebelum memulai ambulasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[ambulasi_observasi][]" id="ambulasi_observasi4" onclick="checkthis('ambulasi_observasi4')" value="Monitor kondisi umum selama melakukan ambulasi"><span class="lbl"> Monitor kondisi umum selama melakukan ambulasi</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik Ambulasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[ambulasi_terapeutik][]" id="ambulasi_terapeutik1" onclick="checkthis('ambulasi_terapeutik1')" value="Fasilitasi aktivitas ambulasi dengan alat bantu (mis: tongkat, kruk)"><span class="lbl"> Fasilitasi aktivitas ambulasi dengan alat bantu (mis: tongkat, kruk)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[ambulasi_terapeutik][]" id="ambulasi_terapeutik2" onclick="checkthis('ambulasi_terapeutik2')" value="Fasilitasi melakukan mobilitas fisik jika perlu"><span class="lbl"> Fasilitasi melakukan mobilitas fisik jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[ambulasi_terapeutik][]" id="ambulasi_terapeutik3" onclick="checkthis('ambulasi_terapeutik3')" value="Libatkan keluarga untuk membantu pasien dalam meningkatkan ambulasi"><span class="lbl"> Libatkan keluarga untuk membantu pasien dalam meningkatkan ambulasi</span></label></div>
      </td>
    </tr>

    <!-- Edukasi Ambulasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[ambulasi_edukasi][]" id="ambulasi_edukasi1" onclick="checkthis('ambulasi_edukasi1')" value="Jelaskan tujuan dan prosedur ambulasi"><span class="lbl"> Jelaskan tujuan dan prosedur ambulasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[ambulasi_edukasi][]" id="ambulasi_edukasi2" onclick="checkthis('ambulasi_edukasi2')" value="Anjurkan melakukan ambulasi dini"><span class="lbl"> Anjurkan melakukan ambulasi dini</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[ambulasi_edukasi][]" id="ambulasi_edukasi3" onclick="checkthis('ambulasi_edukasi3')" value="Anjurkan ambulasi sederhana yang harus dilakukan (mis: berjalan dari tempat tidur ke kursi roda, berjalan dari tempat tidur ke kamar mandi, berjalan sesuai toleransi)"><span class="lbl"> Anjurkan ambulasi sederhana yang harus dilakukan (mis: berjalan dari tempat tidur ke kursi roda, berjalan dari tempat tidur ke kamar mandi, berjalan sesuai toleransi)</span></label></div>
      </td>
    </tr>

    <!-- Dukungan Mobilisasi -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dukungan Mobilisasi</b><br>
        <i>(Memfasilitasi pasien untuk meningkatkan aktivitas pergerakan fisik)</i><br>
        <b>(I.05173)</b>
      </td>
    </tr>

    <!-- Observasi Mobilisasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mobilisasi_observasi][]" id="mobilisasi_observasi1" onclick="checkthis('mobilisasi_observasi1')" value="Identifikasi adanya nyeri atau keluhan fisik lainnya"><span class="lbl"> Identifikasi adanya nyeri atau keluhan fisik lainnya</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mobilisasi_observasi][]" id="mobilisasi_observasi2" onclick="checkthis('mobilisasi_observasi2')" value="Identifikasi toleransi fisik melakukan pergerakan"><span class="lbl"> Identifikasi toleransi fisik melakukan pergerakan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mobilisasi_observasi][]" id="mobilisasi_observasi3" onclick="checkthis('mobilisasi_observasi3')" value="Monitor frekuensi jantung dan tekanan darah sebelum memulai mobilisasi"><span class="lbl"> Monitor frekuensi jantung dan tekanan darah sebelum memulai mobilisasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mobilisasi_observasi][]" id="mobilisasi_observasi4" onclick="checkthis('mobilisasi_observasi4')" value="Monitor kondisi umum selama melakukan mobilisasi"><span class="lbl"> Monitor kondisi umum selama melakukan mobilisasi</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik Mobilisasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mobilisasi_terapeutik][]" id="mobilisasi_terapeutik1" onclick="checkthis('mobilisasi_terapeutik1')" value="Fasilitasi aktivitas mobilisasi dengan alat bantu (mis: pagar tempat tidur)"><span class="lbl"> Fasilitasi aktivitas mobilisasi dengan alat bantu (mis: pagar tempat tidur)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mobilisasi_terapeutik][]" id="mobilisasi_terapeutik2" onclick="checkthis('mobilisasi_terapeutik2')" value="Fasilitasi melakukan pergerakan jika diperlukan"><span class="lbl"> Fasilitasi melakukan pergerakan jika diperlukan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mobilisasi_terapeutik][]" id="mobilisasi_terapeutik3" onclick="checkthis('mobilisasi_terapeutik3')" value="Libatkan keluarga untuk membantu pasien dalam meningkatkan pergerakan"><span class="lbl"> Libatkan keluarga untuk membantu pasien dalam meningkatkan pergerakan</span></label></div>
      </td>
    </tr>

    <!-- Edukasi Mobilisasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mobilisasi_edukasi][]" id="mobilisasi_edukasi1" onclick="checkthis('mobilisasi_edukasi1')" value="Jelaskan tujuan dan prosedur mobilisasi"><span class="lbl"> Jelaskan tujuan dan prosedur mobilisasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mobilisasi_edukasi][]" id="mobilisasi_edukasi2" onclick="checkthis('mobilisasi_edukasi2')" value="Anjurkan melakukan mobilisasi dini"><span class="lbl"> Anjurkan melakukan mobilisasi dini</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_106[mobilisasi_edukasi][]" id="mobilisasi_edukasi3" onclick="checkthis('mobilisasi_edukasi3')" value="Ajarkan mobilisasi sederhana yang harus dilakukan (mis: duduk ditempat tidur, duduk disisi tempat tidur, pindah dari tempat tidur ke kursi)"><span class="lbl"> Ajarkan mobilisasi sederhana yang harus dilakukan (mis: duduk ditempat tidur, duduk disisi tempat tidur, pindah dari tempat tidur ke kursi)</span></label></div>
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
        <input type="text" class="input_type" name="form_106[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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