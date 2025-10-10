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
      var hiddenInputName = 'form_101[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: GANGGUAN KOMUNIKASI VERBAL</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Penurunan, perlambatan, atau ketiadaan kemampuan untuk menerima, memproses, mengirim, dan/atau menggunakan sistem simbol.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[penyebab][]" id="kom_penyebab1" onclick="checkthis('kom_penyebab1')" value="Penurunan sirkulasi serebral"><span class="lbl"> Penurunan sirkulasi serebral</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[penyebab][]" id="kom_penyebab2" onclick="checkthis('kom_penyebab2')" value="Gangguan neuromuskuler"><span class="lbl"> Gangguan neuromuskuler</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[penyebab][]" id="kom_penyebab3" onclick="checkthis('kom_penyebab3')" value="Gangguan pendengaran"><span class="lbl"> Gangguan pendengaran</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[penyebab][]" id="kom_penyebab4" onclick="checkthis('kom_penyebab4')" value="Gangguan muskuloskeletal"><span class="lbl"> Gangguan muskuloskeletal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[penyebab][]" id="kom_penyebab5" onclick="checkthis('kom_penyebab5')" value="Kelainan palatum"><span class="lbl"> Kelainan palatum</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[penyebab][]" id="kom_penyebab6" onclick="checkthis('kom_penyebab6')" value="Hambatan fisik (trakheostomi, intubasi, krikotiroidektomi)"><span class="lbl"> Hambatan fisik (mis. terpasang trakheostomi, intubasi, krikotiroidektomi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[penyebab][]" id="kom_penyebab7" onclick="checkthis('kom_penyebab7')" value="Hambatan individu (ketakutan, kecemasan, malu, emosional, kurang privasi)"><span class="lbl"> Hambatan individu (mis. ketakutan, kecemasan, merasa malu, emosional, kurang privasi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[penyebab][]" id="kom_penyebab8" onclick="checkthis('kom_penyebab8')" value="Hambatan psikologis (gangguan psikotik, konsep diri, harga diri, emosi)"><span class="lbl"> Hambatan psikologis (mis. gangguan psikotik, konsep diri, harga diri, gangguan emosi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[penyebab][]" id="kom_penyebab9" onclick="checkthis('kom_penyebab9')" value="Hambatan lingkungan (informasi tidak cukup, tidak ada orang terdekat, perbedaan budaya/bahasa)"><span class="lbl"> Hambatan lingkungan (mis. ketidakcukupan informasi, ketiadaan orang terdekat, ketidaksesuaian budaya, bahasa asing)</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_101[kom_intervensi_selama]" id="kom_intervensi_selama" onchange="fillthis('kom_intervensi_selama')" style="width:10%;">
          , komunikasi verbal meningkat (L.13118) dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[kriteria_hasil][]" id="kom_krit1" onclick="checkthis('kom_krit1')" value="Kemampuan berbicara meningkat"><span class="lbl"> Kemampuan berbicara meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[kriteria_hasil][]" id="kom_krit2" onclick="checkthis('kom_krit2')" value="Kemampuan mendengar meningkat"><span class="lbl"> Kemampuan mendengar meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[kriteria_hasil][]" id="kom_krit3" onclick="checkthis('kom_krit3')" value="Kontak mata meningkat"><span class="lbl"> Kontak mata meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[kriteria_hasil][]" id="kom_krit4" onclick="checkthis('kom_krit4')" value="Afasia menurun"><span class="lbl"> Afasia menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[kriteria_hasil][]" id="kom_krit5" onclick="checkthis('kom_krit5')" value="Disfasia menurun"><span class="lbl"> Disfasia menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[kriteria_hasil][]" id="kom_krit6" onclick="checkthis('kom_krit6')" value="Disatria menurun"><span class="lbl"> Disatria menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[kriteria_hasil][]" id="kom_krit7" onclick="checkthis('kom_krit7')" value="Pelo menurun"><span class="lbl"> Pelo menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[kriteria_hasil][]" id="kom_krit8" onclick="checkthis('kom_krit8')" value="Gagap menurun"><span class="lbl"> Gagap menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[kriteria_hasil][]" id="kom_krit9" onclick="checkthis('kom_krit9')" value="Respon perilaku membaik"><span class="lbl"> Respon perilaku membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[kriteria_hasil][]" id="kom_krit10" onclick="checkthis('kom_krit10')" value="Pemahaman komunikasi membaik"><span class="lbl"> Pemahaman komunikasi membaik</span></label></div>
      </td>
    </tr>

    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Dibuktikan dengan:</b><br>

        <p><b>Tanda dan Gejala Mayor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <i>(Tidak tersedia)</i>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[mayor_objektif][]" id="kom_mayor1" onclick="checkthis('kom_mayor1')" value="Tidak mampu berbicara atau mendengar"><span class="lbl"> Tidak mampu berbicara atau mendengar</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[mayor_objektif][]" id="kom_mayor2" onclick="checkthis('kom_mayor2')" value="Menunjukkan respon tidak sesuai"><span class="lbl"> Menunjukkan respon tidak sesuai</span></label></div>
          </div>
        </div>

        <hr>
        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <i>(Tidak tersedia)</i>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor1" onclick="checkthis('kom_minor1')" value="Afasia"><span class="lbl"> Afasia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor2" onclick="checkthis('kom_minor2')" value="Disfasia"><span class="lbl"> Disfasia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor3" onclick="checkthis('kom_minor3')" value="Apraksia"><span class="lbl"> Apraksia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor4" onclick="checkthis('kom_minor4')" value="Disleksia"><span class="lbl"> Disleksia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor5" onclick="checkthis('kom_minor5')" value="Disatria"><span class="lbl"> Disatria</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor6" onclick="checkthis('kom_minor6')" value="Afonia"><span class="lbl"> Afonia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor7" onclick="checkthis('kom_minor7')" value="Dislalia"><span class="lbl"> Dislalia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor8" onclick="checkthis('kom_minor8')" value="Pelo"><span class="lbl"> Pelo</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor9" onclick="checkthis('kom_minor9')" value="Gagap"><span class="lbl"> Gagap</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor10" onclick="checkthis('kom_minor10')" value="Tidak ada kontak mata"><span class="lbl"> Tidak ada kontak mata</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor11" onclick="checkthis('kom_minor11')" value="Sulit memahami komunikasi"><span class="lbl"> Sulit memahami komunikasi</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor12" onclick="checkthis('kom_minor12')" value="Sulit mempertahankan komunikasi"><span class="lbl"> Sulit mempertahankan komunikasi</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor13" onclick="checkthis('kom_minor13')" value="Sulit menggunakan ekspresi wajah atau tubuh"><span class="lbl"> Sulit menggunakan ekspresi wajah/tubuh</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor14" onclick="checkthis('kom_minor14')" value="Tidak mampu menggunakan ekspresi wajah atau tubuh"><span class="lbl"> Tidak mampu menggunakan ekspresi wajah/tubuh</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor15" onclick="checkthis('kom_minor15')" value="Sulit menyusun kalimat"><span class="lbl"> Sulit menyusun kalimat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor16" onclick="checkthis('kom_minor16')" value="Verbalisasi tidak tepat"><span class="lbl"> Verbalisasi tidak tepat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor17" onclick="checkthis('kom_minor17')" value="Sulit mengungkapkan kata-kata"><span class="lbl"> Sulit mengungkapkan kata-kata</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor18" onclick="checkthis('kom_minor18')" value="Disorientasi orang, ruang, waktu"><span class="lbl"> Disorientasi orang, ruang, waktu</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor19" onclick="checkthis('kom_minor19')" value="Defisit penglihatan"><span class="lbl"> Defisit penglihatan</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_101[minor_objektif][]" id="kom_minor20" onclick="checkthis('kom_minor20')" value="Delusi"><span class="lbl"> Delusi</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->


<!-- PROMOSI KESEHATAN: DEFISIT BICARA -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>

    <!-- PROMOSI KESEHATAN: DEFISIT BICARA -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Promosi Kesehatan: Defisit Bicara</b><br>
        <i>(Menggunakan teknik komunikasi tambahan pada individu dengan gangguan bicara)</i><br>
        <b>(I.13492)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_101[bicara_observasi][]" id="bicara_observasi1" onclick="checkthis('bicara_observasi1')" value="Monitor kecepatan, tekanan, kuantitas, volume, dan diksi bicara"><span class="lbl"> Monitor kecepatan, tekanan, kuantitas, volume, dan diksi bicara</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_101[bicara_observasi][]" id="bicara_observasi2" onclick="checkthis('bicara_observasi2')" value="Monitor proses kognitif, anatomis, dan fisiologis yang berkaitan dengan bicara (mis: memori, pendengaran, bahasa)"><span class="lbl"> Monitor proses kognitif, anatomis, dan fisiologis yang berkaitan dengan bicara (mis: memori, pendengaran, bahasa)</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_101[bicara_observasi][]" id="bicara_observasi3" onclick="checkthis('bicara_observasi3')" value="Monitor frustasi, marah, depresi, atau hal lain yang mengganggu bicara"><span class="lbl"> Monitor frustasi, marah, depresi, atau hal lain yang mengganggu bicara</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_101[bicara_observasi][]" id="bicara_observasi4" onclick="checkthis('bicara_observasi4')" value="Identifikasi perilaku emosional dan fisik sebagai bentuk komunikasi"><span class="lbl"> Identifikasi perilaku emosional dan fisik sebagai bentuk komunikasi</span></label>
        </div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_101[bicara_observasi_lain]" id="bicara_observasi_lain" style="width: 98%;">
        </div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_101[bicara_terapeutik][]" id="bicara_terapeutik1" onclick="checkthis('bicara_terapeutik1')" value="Gunakan metode komunikasi alternatif (mis: menulis, mata berkedip, papan komunikasi dengan gambar dan huruf, isyarat tangan, atau komputer)"><span class="lbl"> Gunakan metode komunikasi alternatif (mis: menulis, mata berkedip, papan komunikasi dengan gambar dan huruf, isyarat tangan, atau komputer)</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_101[bicara_terapeutik][]" id="bicara_terapeutik2" onclick="checkthis('bicara_terapeutik2')" value="Sesuaikan gaya komunikasi dengan kebutuhan pasien"><span class="lbl"> Sesuaikan gaya komunikasi dengan kebutuhan pasien (mis: berdiri di depan pasien, dengarkan dengan seksama, bicaralah perlahan, gunakan komunikasi tertulis, atau minta bantuan keluarga untuk memahami ucapan pasien)</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_101[bicara_terapeutik][]" id="bicara_terapeutik3" onclick="checkthis('bicara_terapeutik3')" value="Modifikasi lingkungan untuk meminimalkan bantuan"><span class="lbl"> Modifikasi lingkungan untuk meminimalkan bantuan</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_101[bicara_terapeutik][]" id="bicara_terapeutik4" onclick="checkthis('bicara_terapeutik4')" value="Ulangi apa yang disampaikan pasien dengan jelas"><span class="lbl"> Ulangi apa yang disampaikan pasien dengan jelas</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_101[bicara_terapeutik][]" id="bicara_terapeutik5" onclick="checkthis('bicara_terapeutik5')" value="Gunakan juru bicara"><span class="lbl"> Gunakan juru bicara</span></label>
        </div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_101[bicara_terapeutik_lain]" id="bicara_terapeutik_lain" style="width: 98%;">
        </div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_101[bicara_edukasi][]" id="bicara_edukasi1" onclick="checkthis('bicara_edukasi1')" value="Anjurkan bicara perlahan"><span class="lbl"> Anjurkan bicara perlahan</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_101[bicara_edukasi][]" id="bicara_edukasi2" onclick="checkthis('bicara_edukasi2')" value="Ajarkan pasien dan keluarga proses kognitif, anatomis, dan fisiologis yang berhubungan dengan kemampuan bicara"><span class="lbl"> Ajarkan pasien dan keluarga proses kognitif, anatomis, dan fisiologis yang berhubungan dengan kemampuan bicara</span></label>
        </div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_101[bicara_edukasi_lain]" id="bicara_edukasi_lain" style="width: 98%;">
        </div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_101[bicara_kolaborasi][]" id="bicara_kolaborasi1" onclick="checkthis('bicara_kolaborasi1')" value="Rujuk ke ahli patologi bicara atau terapis"><span class="lbl"> Rujuk ke ahli patologi bicara atau terapis</span></label>
        </div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_101[bicara_kolaborasi_lain]" id="bicara_kolaborasi_lain" style="width: 98%;">
        </div>
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
        <input type="text" class="input_type" name="form_101[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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