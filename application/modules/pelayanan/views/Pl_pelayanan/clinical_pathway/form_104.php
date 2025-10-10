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
      var hiddenInputName = 'form_104[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: GANGGUAN RASA NYAMAN</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Perasaan kurang senang, lega, dan sempurna dalam dimensi fisik, psikospiritual, lingkungan, dan sosial.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[penyebab][]" id="nyaman_penyebab1" onclick="checkthis('nyaman_penyebab1')" value="Gejala penyakit"><span class="lbl"> Gejala penyakit</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[penyebab][]" id="nyaman_penyebab2" onclick="checkthis('nyaman_penyebab2')" value="Kurang pengendalian situasional/lingkungan"><span class="lbl"> Kurang pengendalian situasional/lingkungan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[penyebab][]" id="nyaman_penyebab3" onclick="checkthis('nyaman_penyebab3')" value="Ketidakadekuatan sumberdaya"><span class="lbl"> Ketidakadekuatan sumberdaya</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[penyebab][]" id="nyaman_penyebab4" onclick="checkthis('nyaman_penyebab4')" value="Kurangnya privasi"><span class="lbl"> Kurangnya privasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[penyebab][]" id="nyaman_penyebab5" onclick="checkthis('nyaman_penyebab5')" value="Gangguan stimulus lingkungan"><span class="lbl"> Gangguan stimulus lingkungan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[penyebab][]" id="nyaman_penyebab6" onclick="checkthis('nyaman_penyebab6')" value="Efek samping terapis (medikasi, radiasi, kemoterapi)"><span class="lbl"> Efek samping terapis (medikasi, radiasi, kemoterapi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[penyebab][]" id="nyaman_penyebab7" onclick="checkthis('nyaman_penyebab7')" value="Gangguan adaptasi kehamilan"><span class="lbl"> Gangguan adaptasi kehamilan</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_104[nyaman_intervensi_selama]" id="nyaman_intervensi_selama" onchange="fillthis('nyaman_intervensi_selama')" style="width:10%;">
          , Status kenyamanan meningkat (L.08064) dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[kriteria_hasil][]" id="nyaman_krit1" onclick="checkthis('nyaman_krit1')" value="Rileks meningkat"><span class="lbl"> Rileks meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[kriteria_hasil][]" id="nyaman_krit2" onclick="checkthis('nyaman_krit2')" value="Keluhan tidak nyaman menurun"><span class="lbl"> Keluhan tidak nyaman menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[kriteria_hasil][]" id="nyaman_krit3" onclick="checkthis('nyaman_krit3')" value="Gelisah menurun"><span class="lbl"> Gelisah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[kriteria_hasil][]" id="nyaman_krit4" onclick="checkthis('nyaman_krit4')" value="Keluhan sulit tidur menurun"><span class="lbl"> Keluhan sulit tidur menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[kriteria_hasil][]" id="nyaman_krit5" onclick="checkthis('nyaman_krit5')" value="Gatal menurun"><span class="lbl"> Gatal menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[kriteria_hasil][]" id="nyaman_krit6" onclick="checkthis('nyaman_krit6')" value="Mual menurun"><span class="lbl"> Mual menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[kriteria_hasil][]" id="nyaman_krit7" onclick="checkthis('nyaman_krit7')" value="Lelah menurun"><span class="lbl"> Lelah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[kriteria_hasil][]" id="nyaman_krit8" onclick="checkthis('nyaman_krit8')" value="Merintih menurun"><span class="lbl"> Merintih menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[kriteria_hasil][]" id="nyaman_krit9" onclick="checkthis('nyaman_krit9')" value="Menangis menurun"><span class="lbl"> Menangis menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[kriteria_hasil][]" id="nyaman_krit10" onclick="checkthis('nyaman_krit10')" value="Pola tidur membaik"><span class="lbl"> Pola tidur membaik</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[mayor_subjektif][]" id="nyaman_mayor_sub1" onclick="checkthis('nyaman_mayor_sub1')" value="Mengeluh tidak nyaman"><span class="lbl"> Mengeluh tidak nyaman</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[mayor_objektif][]" id="nyaman_mayor_obj1" onclick="checkthis('nyaman_mayor_obj1')" value="Gelisah"><span class="lbl"> Gelisah</span></label></div>
          </div>
        </div>

        <hr>
        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[minor_subjektif][]" id="nyaman_minor_sub1" onclick="checkthis('nyaman_minor_sub1')" value="Mengeluh sulit tidur"><span class="lbl"> Mengeluh sulit tidur</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[minor_subjektif][]" id="nyaman_minor_sub2" onclick="checkthis('nyaman_minor_sub2')" value="Tidak mampu rileks"><span class="lbl"> Tidak mampu rileks</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[minor_subjektif][]" id="nyaman_minor_sub3" onclick="checkthis('nyaman_minor_sub3')" value="Mengeluh kedinginan/kepanasan"><span class="lbl"> Mengeluh kedinginan/kepanasan</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[minor_subjektif][]" id="nyaman_minor_sub4" onclick="checkthis('nyaman_minor_sub4')" value="Merasa gatal"><span class="lbl"> Merasa gatal</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[minor_subjektif][]" id="nyaman_minor_sub5" onclick="checkthis('nyaman_minor_sub5')" value="Mengeluh mual"><span class="lbl"> Mengeluh mual</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[minor_subjektif][]" id="nyaman_minor_sub6" onclick="checkthis('nyaman_minor_sub6')" value="Mengeluh lelah"><span class="lbl"> Mengeluh lelah</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[minor_objektif][]" id="nyaman_minor_obj1" onclick="checkthis('nyaman_minor_obj1')" value="Menunjukan gejala distres"><span class="lbl"> Menunjukan gejala distres</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[minor_objektif][]" id="nyaman_minor_obj2" onclick="checkthis('nyaman_minor_obj2')" value="Tampak merintih/menangis"><span class="lbl"> Tampak merintih/menangis</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[minor_objektif][]" id="nyaman_minor_obj3" onclick="checkthis('nyaman_minor_obj3')" value="Pola eliminasi berubah"><span class="lbl"> Pola eliminasi berubah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[minor_objektif][]" id="nyaman_minor_obj4" onclick="checkthis('nyaman_minor_obj4')" value="Postur tubuh berubah"><span class="lbl"> Postur tubuh berubah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[minor_objektif][]" id="nyaman_minor_obj5" onclick="checkthis('nyaman_minor_obj5')" value="Iritabilitas"><span class="lbl"> Iritabilitas</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->

<!-- MANAJEMEN NYERI & PENGATURAN POSISI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>

    <!-- Manajemen Nyeri -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Manajemen Nyeri</b><br>
        <i>(Mengidentifikasi dan mengelola sensorik atau emosional terkait kerusakan jaringan atau fungsional dengan onset mendadak atau lambat dan berintensitas ringan hingga berat dan konstan)</i><br>
        <b>(I.08238)</b>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Tindakan</b><br>
      </td>
    </tr>

    <!-- Observasi Nyeri -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_observasi][]" id="nyeri_observasi1" onclick="checkthis('nyeri_observasi1')" value="Identifikasi lokasi, karakteristik, durasi, frekuensi, kualitas, intensitas nyeri"><span class="lbl"> Identifikasi lokasi, karakteristik, durasi, frekuensi, kualitas, intensitas nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_observasi][]" id="nyeri_observasi2" onclick="checkthis('nyeri_observasi2')" value="Identifikasi skala nyeri"><span class="lbl"> Identifikasi skala nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_observasi][]" id="nyeri_observasi3" onclick="checkthis('nyeri_observasi3')" value="Identifikasi respon nyeri non verbal"><span class="lbl"> Identifikasi respon nyeri non verbal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_observasi][]" id="nyeri_observasi4" onclick="checkthis('nyeri_observasi4')" value="Identifikasi faktor yang memperberat dan memperingan nyeri"><span class="lbl"> Identifikasi faktor yang memperberat dan memperingan nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_observasi][]" id="nyeri_observasi5" onclick="checkthis('nyeri_observasi5')" value="Identifikasi pengetahuan dan keyakinan tentang nyeri"><span class="lbl"> Identifikasi pengetahuan dan keyakinan tentang nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_observasi][]" id="nyeri_observasi6" onclick="checkthis('nyeri_observasi6')" value="Identifikasi pengaruh budaya terhadap respon nyeri"><span class="lbl"> Identifikasi pengaruh budaya terhadap respon nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_observasi][]" id="nyeri_observasi7" onclick="checkthis('nyeri_observasi7')" value="Identifikasi pengaruh nyeri pada kualitas hidup"><span class="lbl"> Identifikasi pengaruh nyeri pada kualitas hidup</span></label></div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_104[nyeri_observasi_lain]" id="nyeri_observasi_lain" onchange="fillthis('nyeri_observasi_lain')">
        </div>
      </td>
    </tr>

    <!-- Terapeutik Nyeri -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_terapeutik][]" id="nyeri_terapeutik1" onclick="checkthis('nyeri_terapeutik1')" value="Berikan teknik non farmakologis untuk mengurangi rasa nyeri"><span class="lbl"> Berikan teknik non farmakologis untuk mengurangi rasa nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_terapeutik][]" id="nyeri_terapeutik2" onclick="checkthis('nyeri_terapeutik2')" value="Kontrol lingkungan yang memperberat rasa nyeri"><span class="lbl"> Kontrol lingkungan yang memperberat rasa nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_terapeutik][]" id="nyeri_terapeutik3" onclick="checkthis('nyeri_terapeutik3')" value="Fasilitasi istirahat dan tidur"><span class="lbl"> Fasilitasi istirahat dan tidur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_terapeutik][]" id="nyeri_terapeutik4" onclick="checkthis('nyeri_terapeutik4')" value="Pertimbangkan jenis dan sumber nyeri dalam pemilihan strategi meredakan nyeri"><span class="lbl"> Pertimbangkan jenis dan sumber nyeri dalam pemilihan strategi meredakan nyeri</span></label></div>
      </td>
    </tr>

    <!-- Edukasi Nyeri -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_edukasi][]" id="nyeri_edukasi1" onclick="checkthis('nyeri_edukasi1')" value="Jelaskan penyebab, periode, dan pemicu nyeri"><span class="lbl"> Jelaskan penyebab, periode, dan pemicu nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_edukasi][]" id="nyeri_edukasi2" onclick="checkthis('nyeri_edukasi2')" value="Jelaskan strategi meredakan nyeri (ruangan, pencahayaan, kebisingan)"><span class="lbl"> Jelaskan strategi meredakan nyeri (ruangan, pencahayaan, kebisingan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_edukasi][]" id="nyeri_edukasi3" onclick="checkthis('nyeri_edukasi3')" value="Anjurkan memonitor nyeri secara mandiri"><span class="lbl"> Anjurkan memonitor nyeri secara mandiri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_edukasi][]" id="nyeri_edukasi4" onclick="checkthis('nyeri_edukasi4')" value="Anjurkan menggunakan analgetik secara tepat"><span class="lbl"> Anjurkan menggunakan analgetik secara tepat</span></label></div>
        <!-- <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="inputFORM_104e" name="form_104[nyeri_edukasi_lain]" id="nyeri_edukasi_lain" onchange="fillthis('nyeri_edukasi_lain')">
        </div> -->
      </td>
    </tr>

    <!-- Kolaborasi Nyeri -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[nyeri_kolaborasi][]" id="nyeri_kolaborasi1" onclick="checkthis('nyeri_kolaborasi1')" value="Kolaborasi pemberian analgetik"><span class="lbl"> Kolaborasi pemberian analgetik</span></label></div>
      </td>
    </tr>

    <!-- Pengaturan Posisi -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Pengaturan Posisi</b><br>
        <i>(Menempatkan bagian tubuh untuk meningkatkan kesehatan fisiologis dan/atau psikologis)</i><br>
        <b>(I.01019)</b>
      </td>
    </tr>

    <!-- Observasi Posisi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_observasi][]" id="posisi_observasi1" onclick="checkthis('posisi_observasi1')" value="Monitor status oksigenasi sebelum dan sesudah mengubah posisi"><span class="lbl"> Monitor status oksigenasi sebelum dan sesudah mengubah posisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_observasi][]" id="posisi_observasi2" onclick="checkthis('posisi_observasi2')" value="Monitor alat traksi agar selalu tepat"><span class="lbl"> Monitor alat traksi agar selalu tepat</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik Posisi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_terapeutik][]" id="posisi_terapeutik1" onclick="checkthis('posisi_terapeutik1')" value="Tempatkan pada posisi tempat tidur yang tepat"><span class="lbl"> Tempatkan pada posisi tempat tidur yang tepat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_terapeutik][]" id="posisi_terapeutik2" onclick="checkthis('posisi_terapeutik2')" value="Tempatkan pada posisi yang terapeutik"><span class="lbl"> Tempatkan pada posisi yang terapeutik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_terapeutik][]" id="posisi_terapeutik3" onclick="checkthis('posisi_terapeutik3')" value="Atur posisi tidur yang disukai"><span class="lbl"> Atur posisi tidur yang disukai</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_terapeutik][]" id="posisi_terapeutik4" onclick="checkthis('posisi_terapeutik4')" value="Motivasi terlibat dalam perubahan posisi"><span class="lbl"> Motivasi terlibat dalam perubahan posisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_terapeutik][]" id="posisi_terapeutik5" onclick="checkthis('posisi_terapeutik5')" value="Motivasi melakukan ROM aktif/pasif"><span class="lbl"> Motivasi melakukan ROM aktif/pasif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_terapeutik][]" id="posisi_terapeutik6" onclick="checkthis('posisi_terapeutik6')" value="Hindari menempatkan pada posisi yg dapat meningkatkan nyeri"><span class="lbl"> Hindari menempatkan pada posisi yg dapat meningkatkan nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_terapeutik][]" id="posisi_terapeutik7" onclick="checkthis('posisi_terapeutik7')" value="Minimalkan gesekan dan tarikan saat mengubah posisi"><span class="lbl"> Minimalkan gesekan dan tarikan saat mengubah posisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_terapeutik][]" id="posisi_terapeutik8" onclick="checkthis('posisi_terapeutik8')" value="Ubah posisi setiap 2 jam"><span class="lbl"> Ubah posisi setiap 2 jam</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_terapeutik][]" id="posisi_terapeutik9" onclick="checkthis('posisi_terapeutik9')" value="Jadwalkan secara tertulis untuk perubahan posisi"><span class="lbl"> Jadwalkan secara tertulis untuk perubahan posisi</span></label></div>
      </td>
    </tr>

    <!-- Edukasi Posisi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_edukasi][]" id="posisi_edukasi1" onclick="checkthis('posisi_edukasi1')" value="Informasikan saat akan dilakukan perubahan posisi"><span class="lbl"> Informasikan saat akan dilakukan perubahan posisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_edukasi][]" id="posisi_edukasi2" onclick="checkthis('posisi_edukasi2')" value="Ajarkan cara menggunakan postur yang baik dan mekanika tubuh yang baik selama melakukan perubahan posisi"><span class="lbl"> Ajarkan cara menggunakan postur yang baik dan mekanika tubuh yang baik selama melakukan perubahan posisi</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi Posisi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_104[posisi_kolaborasi][]" id="posisi_kolaborasi1" onclick="checkthis('posisi_kolaborasi1')" value="Kolaborasi pemberian premidikasi sebelum mengubah posisi"><span class="lbl"> Kolaborasi pemberian premidikasi sebelum mengubah posisi</span></label></div>
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
        <input type="text" class="input_type" name="form_104[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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