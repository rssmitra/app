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
      var hiddenInputName = 'form_78[ttd_' + role + ']';
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

<div style="text-align: center; font-size: 18px;">
  <b>DIAGNOSIS KEPERAWATAN: INTOLERANSI AKTIVITAS</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-family: tahoma, sans-serif; font-size: 13px;">
  <thead>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Definisi:</b> Ketidakcukupan energi untuk melakukan aktivitas sehari–hari
      </td>
    </tr>
  </thead>

  <tbody>
    <!-- PENYEBAB -->
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[penyebab][]" id="penyebab_suplai" onclick="checkthis('penyebab_suplai')" value="Ketidakseimbangan antara suplai"><span class="lbl"> Ketidakseimbangan antara suplai</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[penyebab][]" id="penyebab_tirah" onclick="checkthis('penyebab_tirah')" value="Tirah baring"><span class="lbl"> Tirah baring</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[penyebab][]" id="penyebab_kelemahan" onclick="checkthis('penyebab_kelemahan')" value="Kelemahan"><span class="lbl"> Kelemahan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[penyebab][]" id="penyebab_imobilitas" onclick="checkthis('penyebab_imobilitas')" value="Imobilitas"><span class="lbl"> Imobilitas</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[penyebab][]" id="penyebab_gaya_hidup" onclick="checkthis('penyebab_gaya_hidup')" value="Gaya hidup monoton"><span class="lbl"> Gaya hidup monoton</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_78[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Toleransi aktivitas meningkat (L.05047) dengan kriteria hasil:</b>
        <br><br>

        <div class="row">
          <div class="col-md-6">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kiri][]" id="hasil_nadi" onclick="checkthis('hasil_nadi')" value="Frekuensi nadi meningkat"><span class="lbl"> Frekuensi nadi meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kiri][]" id="hasil_saturasi" onclick="checkthis('hasil_saturasi')" value="Saturasi oksigen meningkat"><span class="lbl"> Saturasi oksigen meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kiri][]" id="hasil_aktivitas" onclick="checkthis('hasil_aktivitas')" value="Kemudahan melakukan aktivitas sehari-hari meningkat"><span class="lbl"> Kemudahan melakukan aktivitas sehari-hari meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kiri][]" id="hasil_kecepatan" onclick="checkthis('hasil_kecepatan')" value="Kecepatan berjalan meningkat"><span class="lbl"> Kecepatan berjalan meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kiri][]" id="hasil_jarak" onclick="checkthis('hasil_jarak')" value="Jarak berjalan meningkat"><span class="lbl"> Jarak berjalan meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kiri][]" id="hasil_kekuatan_atas" onclick="checkthis('hasil_kekuatan_atas')" value="Kekuatan tubuh bagian atas meningkat"><span class="lbl"> Kekuatan tubuh bagian atas meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kiri][]" id="hasil_kekuatan_bawah" onclick="checkthis('hasil_kekuatan_bawah')" value="Kekuatan tubuh bagian bawah meningkat"><span class="lbl"> Kekuatan tubuh bagian bawah meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kiri][]" id="hasil_tangga" onclick="checkthis('hasil_tangga')" value="Toleransi dalam menaiki tangga meningkat"><span class="lbl"> Toleransi dalam menaiki tangga meningkat</span></label></div>
          </div>

          <div class="col-md-6">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kanan][]" id="hasil_lelah" onclick="checkthis('hasil_lelah')" value="Keluhan lelah menurun"><span class="lbl"> Keluhan lelah menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kanan][]" id="hasil_dispnea_aktivitas" onclick="checkthis('hasil_dispnea_aktivitas')" value="Dispnea saat aktivitas menurun"><span class="lbl"> Dispnea saat aktivitas menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kanan][]" id="hasil_dispnea_setelah" onclick="checkthis('hasil_dispnea_setelah')" value="Dispnea setelah aktivitas menurun"><span class="lbl"> Dispnea setelah aktivitas menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kanan][]" id="hasil_lemah" onclick="checkthis('hasil_lemah')" value="Perasaan lemah menurun"><span class="lbl"> Perasaan lemah menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kanan][]" id="hasil_aritmia_aktivitas" onclick="checkthis('hasil_aritmia_aktivitas')" value="Aritmia saat aktivitas menurun"><span class="lbl"> Aritmia saat aktivitas menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kanan][]" id="hasil_aritmia_setelah" onclick="checkthis('hasil_aritmia_setelah')" value="Aritmia setelah aktivitas menurun"><span class="lbl"> Aritmia setelah aktivitas menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kanan][]" id="hasil_sianosis" onclick="checkthis('hasil_sianosis')" value="Sianosis menurun"><span class="lbl"> Sianosis menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kanan][]" id="hasil_warna_kulit" onclick="checkthis('hasil_warna_kulit')" value="Warna kulit membaik"><span class="lbl"> Warna kulit membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kanan][]" id="hasil_td" onclick="checkthis('hasil_td')" value="Tekanan darah membaik"><span class="lbl"> Tekanan darah membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kanan][]" id="hasil_nafas" onclick="checkthis('hasil_nafas')" value="Frekuensi napas membaik"><span class="lbl"> Frekuensi napas membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[kriteria_kanan][]" id="hasil_ekg" onclick="checkthis('hasil_ekg')" value="EKG Iskemia membaik"><span class="lbl"> EKG Iskemia membaik</span></label></div>
          </div>
        </div>
      </td>
    </tr>

    <!-- TANDA DAN GEJALA -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Dibuktikan dengan:</b><br><br>
        <b>Tanda dan Gejala Mayor</b>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[mayor_subjektif][]" id="mayor_lelah" onclick="checkthis('mayor_lelah')" value="Mengeluh lelah"><span class="lbl"> Mengeluh lelah</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[mayor_objektif][]" id="mayor_nadi" onclick="checkthis('mayor_nadi')" value="Frekuensi jantung meningkat >20% dari kondisi istirahat"><span class="lbl"> Frekuensi jantung meningkat >20% dari kondisi istirahat</span></label></div>
          </div>
        </div>

        <hr>
        <b>Tanda dan Gejala Minor</b>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[minor_subjektif][]" id="minor_dispnoe" onclick="checkthis('minor_dispnoe')" value="Dispnoe saat/setelah aktivitas"><span class="lbl"> Dispnoe saat/setelah aktivitas</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[minor_subjektif][]" id="minor_tidak_nyaman" onclick="checkthis('minor_tidak_nyaman')" value="Merasa tidak nyaman setelah beraktivitas"><span class="lbl"> Merasa tidak nyaman setelah beraktivitas</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[minor_subjektif][]" id="minor_lemah" onclick="checkthis('minor_lemah')" value="Merasa lemah"><span class="lbl"> Merasa lemah</span></label></div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[minor_objektif][]" id="minor_td" onclick="checkthis('minor_td')" value="TD berubah >20% dari kondisi istirahat"><span class="lbl"> TD berubah >20% dari kondisi istirahat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[minor_objektif][]" id="minor_ekg_aritmia" onclick="checkthis('minor_ekg_aritmia')" value="Gambaran EKG menunjukkan aritmia saat/setelah aktivitas"><span class="lbl"> Gambaran EKG menunjukkan aritmia saat/setelah aktivitas</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[minor_objektif][]" id="minor_ekg_iskemia" onclick="checkthis('minor_ekg_iskemia')" value="Gambaran EKG menunjukkan iskemia"><span class="lbl"> Gambaran EKG menunjukkan iskemia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[minor_objektif][]" id="minor_sianosis" onclick="checkthis('minor_sianosis')" value="Sianosis"><span class="lbl"> Sianosis</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->
 

<!-- MANAJEMEN ENERGI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px; font-family: Arial, sans-serif;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Judul -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Manajemen Energi</b><br>
        <i>(Mengidentifikasi dan mengelola penggunaan energi untuk mengatasi atau mencegah kelelahan dan mengoptimalkan proses pemulihan)</i><br>
        <b>(I.05178)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; vertical-align:top;">
        <b>Observasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_observasi][]" id="me_observasi1" onclick="checkthis('me_observasi1')" value="Identifikasi gangguan fungsi tubuh yang mengakibatkan kelelahan"> <span class="lbl">Identifikasi gangguan fungsi tubuh yang mengakibatkan kelelahan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_observasi][]" id="me_observasi2" onclick="checkthis('me_observasi2')" value="Monitor kelelahan fisik dan emosional"> <span class="lbl">Monitor kelelahan fisik dan emosional</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_observasi][]" id="me_observasi3" onclick="checkthis('me_observasi3')" value="Monitor pola dan jam tidur"> <span class="lbl">Monitor pola dan jam tidur</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_observasi][]" id="me_observasi4" onclick="checkthis('me_observasi4')" value="Monitor lokasi dan ketidaknyamanan selama aktivitas"> <span class="lbl">Monitor lokasi dan ketidaknyamanan selama melakukan aktivitas</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; vertical-align:top;">
        <b>Terapeutik</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_terapeutik][]" id="me_terapeutik1" onclick="checkthis('me_terapeutik1')" value="Sediakan lingkungan nyaman dan rendah stimulus"> <span class="lbl">Sediakan lingkungan nyaman dan rendah stimulus</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_terapeutik][]" id="me_terapeutik2" onclick="checkthis('me_terapeutik2')" value="Latihan rentang gerak pasif dan aktif"> <span class="lbl">Lakukan latihan rentang gerak pasif dan atau aktif</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_terapeutik][]" id="me_terapeutik3" onclick="checkthis('me_terapeutik3')" value="Berikan aktivitas distraksi yang menyenangkan"> <span class="lbl">Berikan aktivitas distraksi yang menyenangkan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_terapeutik][]" id="me_terapeutik4" onclick="checkthis('me_terapeutik4')" value="Fasilitasi duduk di sisi tempat tidur"> <span class="lbl">Fasilitasi duduk di sisi tempat tidur, jika dapat berpindah atau berjalan</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; vertical-align:top;">
        <b>Edukasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_edukasi][]" id="me_edukasi1" onclick="checkthis('me_edukasi1')" value="Anjurkan tirah baring"> <span class="lbl">Anjurkan tirah baring</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_edukasi][]" id="me_edukasi2" onclick="checkthis('me_edukasi2')" value="Anjurkan aktivitas bertahap"> <span class="lbl">Anjurkan melakukan aktivitas secara bertahap</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_edukasi][]" id="me_edukasi3" onclick="checkthis('me_edukasi3')" value="Anjurkan menghubungi perawat jika kelelahan tidak berkurang"> <span class="lbl">Anjurkan menghubungi perawat jika tanda/gejala kelelahan tidak berkurang</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_edukasi][]" id="me_edukasi4" onclick="checkthis('me_edukasi4')" value="Ajarkan strategi koping untuk mengurangi kelelahan"> <span class="lbl">Ajarkan strategi koping untuk mengurangi kelelahan</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; vertical-align:top;">
        <b>Kolaborasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[me_kolaborasi][]" id="me_kolaborasi1" onclick="checkthis('me_kolaborasi1')" value="Kolaborasi dengan ahli gizi"> <span class="lbl">Kolaborasi dengan ahli gizi tentang cara meningkatkan asupan makanan</span></label></div>
        <div style="margin-top:5px;">Lainnya...<input type="text" class="input_type" name="form_78[input_tambahan_kolaborasi]" id="input_tambahan_kolaborasi" onchange="fillthis('input_tambahan_kolaborasi')" style="width50%;"></div> 
       </td>
    </tr>
  </tbody>
</table>

<br><br>

<!-- TERAPI AKTIVITAS -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px; font-family: Arial, sans-serif;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Judul -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Terapi Aktivitas</b><br>
        <i>(Menggunakan aktivitas fisik, kognitif, sosial, dan spiritual tertentu untuk memulihkan keterlibatan, frekuensi, atau durasi aktivitas individu/kelompok)</i><br>
        <b>(I.05186)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; vertical-align:top;">
        <b>Observasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_observasi][]" id="ta_observasi1" onclick="checkthis('ta_observasi1')" value="Identifikasi deviasi tingkat aktivitas"> <span class="lbl">Identifikasi deviasi tingkat aktivitas</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_observasi][]" id="ta_observasi2" onclick="checkthis('ta_observasi2')" value="Identifikasi kemampuan beraktivitas"> <span class="lbl">Identifikasi kemampuan beraktivitas dalam aktivitas tertentu</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_observasi][]" id="ta_observasi3" onclick="checkthis('ta_observasi3')" value="Identifikasi sumber daya untuk aktivitas yang diinginkan"> <span class="lbl">Identifikasi sumber daya untuk aktivitas yang diinginkan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_observasi][]" id="ta_observasi4" onclick="checkthis('ta_observasi4')" value="Identifikasi makna aktivitas rutin dan waktu luang"> <span class="lbl">Identifikasi makna aktivitas rutin dan waktu luang</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_observasi][]" id="ta_observasi5" onclick="checkthis('ta_observasi5')" value="Monitor respon emosional fisik sosial spiritual"> <span class="lbl">Monitor respon emosional, fisik, sosial, dan spiritual</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; vertical-align:top;">
        <b>Terapeutik</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_terapeutik][]" id="ta_terapeutik1" onclick="checkthis('ta_terapeutik1')" value="Fasilitasi fokus pada kemampuan"> <span class="lbl">Fasilitasi fokus pada kemampuan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_terapeutik][]" id="ta_terapeutik2" onclick="checkthis('ta_terapeutik2')" value="Fasilitasi memilih aktivitas sesuai kemampuan fisik"> <span class="lbl">Fasilitasi memilih aktivitas sesuai kemampuan fisik</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_terapeutik][]" id="ta_terapeutik3" onclick="checkthis('ta_terapeutik3')" value="Fasilitasi transportasi untuk aktivitas"> <span class="lbl">Fasilitasi transportasi untuk menghadiri aktivitas</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_terapeutik][]" id="ta_terapeutik4" onclick="checkthis('ta_terapeutik4')" value="Fasilitasi aktivitas rutin"> <span class="lbl">Fasilitasi aktivitas rutin</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_terapeutik][]" id="ta_terapeutik5" onclick="checkthis('ta_terapeutik5')" value="Fasilitasi aktivitas motorik untuk relaksasi otot"> <span class="lbl">Fasilitasi aktivitas motorik untuk relaksasi otot</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_terapeutik][]" id="ta_terapeutik6" onclick="checkthis('ta_terapeutik6')" value="Libatkan keluarga dalam aktivitas"> <span class="lbl">Libatkan keluarga dalam aktivitas</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; vertical-align:top;">
        <b>Edukasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_edukasi][]" id="ta_edukasi1" onclick="checkthis('ta_edukasi1')" value="Jelaskan metode aktivitas fisik sehari-hari"> <span class="lbl">Jelaskan metode aktivitas fisik sehari-hari</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_edukasi][]" id="ta_edukasi2" onclick="checkthis('ta_edukasi2')" value="Ajarkan cara melakukan aktivitas yang dipilih"> <span class="lbl">Ajarkan cara melakukan aktivitas yang dipilih</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_edukasi][]" id="ta_edukasi3" onclick="checkthis('ta_edukasi3')" value="Anjurkan aktivitas fisik sosial spiritual kognitif"> <span class="lbl">Anjurkan melakukan aktivitas fisik, sosial, spiritual, dan kognitif</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_edukasi][]" id="ta_edukasi4" onclick="checkthis('ta_edukasi4')" value="Anjurkan terlibat dalam aktivitas kelompok"> <span class="lbl">Anjurkan terlibat dalam aktivitas kelompok</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_edukasi][]" id="ta_edukasi5" onclick="checkthis('ta_edukasi5')" value="Anjurkan keluarga memberi penguatan positif"> <span class="lbl">Anjurkan keluarga untuk memberi penguatan positif dalam aktivitas</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; vertical-align:top;">
        <b>Kolaborasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_78[ta_kolaborasi][]" id="ta_kolaborasi1" onclick="checkthis('ta_kolaborasi1')" value="Kolaborasi dengan terapis okupasi"> <span class="lbl">Kolaborasi dengan terapis okupasi</span></label></div>
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
        <input type="text" class="input_type" name="form_78[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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