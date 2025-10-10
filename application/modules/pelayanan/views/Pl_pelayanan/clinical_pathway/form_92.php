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
      var hiddenInputName = 'form_92[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 09 oktober 2025</p> -->

<div style="text-align: center; font-size: 18px;">
  <b>DIAGNOSIS KEPERAWATAN: NYERI MELAHIRKAN</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
        <b>Definisi:</b> Pengalaman sensorik dan emosional yang bervariasi dari menyenangkan sampai tidak menyenangkan yang berhubungan dengan persalinan
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[penyebab][]" id="nyeri_penyebab1" onclick="checkthis('nyeri_penyebab1')" value="Dilatasi Serviks"><span class="lbl"> Dilatasi Serviks</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[penyebab][]" id="nyeri_penyebab2" onclick="checkthis('nyeri_penyebab2')" value="Pengeluaran janin"><span class="lbl"> Pengeluaran janin</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_92[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Tingkat Nyeri (L.08066) menurun dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit1" onclick="checkthis('nyeri_krit1')" value="Kemampuan menuntaskan aktivitas meningkat"><span class="lbl"> Kemampuan menuntaskan aktivitas meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit2" onclick="checkthis('nyeri_krit2')" value="Keluhan nyeri menurun"><span class="lbl"> Keluhan nyeri menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit3" onclick="checkthis('nyeri_krit3')" value="Meringis menurun"><span class="lbl"> Meringis menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit4" onclick="checkthis('nyeri_krit4')" value="Sikap protektif menurun"><span class="lbl"> Sikap protektif menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit5" onclick="checkthis('nyeri_krit5')" value="Gelisah menurun"><span class="lbl"> Gelisah menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit6" onclick="checkthis('nyeri_krit6')" value="Kesulitan tidur menurun"><span class="lbl"> Kesulitan tidur menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit7" onclick="checkthis('nyeri_krit7')" value="Menarik diri menurun"><span class="lbl"> Menarik diri menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit8" onclick="checkthis('nyeri_krit8')" value="Berfokus pada diri sendiri menurun"><span class="lbl"> Berfokus pada diri sendiri menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit9" onclick="checkthis('nyeri_krit9')" value="Diaforesis menurun"><span class="lbl"> Diaforesis menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit10" onclick="checkthis('nyeri_krit10')" value="Perasaan depresi menurun"><span class="lbl"> Perasaan depresi (tertekan) menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit11" onclick="checkthis('nyeri_krit11')" value="Perasaan takut mengalami cedera berulang menurun"><span class="lbl"> Perasaan takut mengalami cedera berulang menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit12" onclick="checkthis('nyeri_krit12')" value="Anoreksia menurun"><span class="lbl"> Anoreksia menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit13" onclick="checkthis('nyeri_krit13')" value="Perineum terasa tertekan menurun"><span class="lbl"> Perineum terasa tertekan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit14" onclick="checkthis('nyeri_krit14')" value="Uterus teraba membulat menurun"><span class="lbl"> Uterus teraba membulat menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit15" onclick="checkthis('nyeri_krit15')" value="Ketegangan otot menurun"><span class="lbl"> Ketegangan otot menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit16" onclick="checkthis('nyeri_krit16')" value="Pupil dilatasi menurun"><span class="lbl"> Pupil dilatasi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit17" onclick="checkthis('nyeri_krit17')" value="Muntah menurun"><span class="lbl"> Muntah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit18" onclick="checkthis('nyeri_krit18')" value="Mual menurun"><span class="lbl"> Mual menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit19" onclick="checkthis('nyeri_krit19')" value="Frekuensi nadi membaik"><span class="lbl"> Frekuensi nadi membaik*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit20" onclick="checkthis('nyeri_krit20')" value="Pola napas membaik"><span class="lbl"> Pola napas membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit21" onclick="checkthis('nyeri_krit21')" value="Tekanan darah membaik"><span class="lbl"> Tekanan darah membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit22" onclick="checkthis('nyeri_krit22')" value="Proses berpikir membaik"><span class="lbl"> Proses berpikir membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit23" onclick="checkthis('nyeri_krit23')" value="Fokus membaik"><span class="lbl"> Fokus membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit24" onclick="checkthis('nyeri_krit24')" value="Fungsi berkemih membaik"><span class="lbl"> Fungsi berkemih membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit25" onclick="checkthis('nyeri_krit25')" value="Perilaku membaik"><span class="lbl"> Perilaku membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit26" onclick="checkthis('nyeri_krit26')" value="Nafsu makan membaik"><span class="lbl"> Nafsu makan membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[kriteria_hasil][]" id="nyeri_krit27" onclick="checkthis('nyeri_krit27')" value="Pola tidur membaik"><span class="lbl"> Pola tidur membaik</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mayor_subjektif][]" id="nyeri_mayor_sub1" onclick="checkthis('nyeri_mayor_sub1')" value="Mengeluh nyeri"><span class="lbl"> Mengeluh nyeri</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mayor_subjektif][]" id="nyeri_mayor_sub2" onclick="checkthis('nyeri_mayor_sub2')" value="Perineum merasa tertekan"><span class="lbl"> Perineum merasa tertekan</span></label></div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mayor_objektif][]" id="nyeri_mayor_obj1" onclick="checkthis('nyeri_mayor_obj1')" value="Ekspresi wajah meringis"><span class="lbl"> Ekspresi wajah meringis</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mayor_objektif][]" id="nyeri_mayor_obj2" onclick="checkthis('nyeri_mayor_obj2')" value="Berposisi meringankan nyeri"><span class="lbl"> Berposisi meringankan nyeri</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mayor_objektif][]" id="nyeri_mayor_obj3" onclick="checkthis('nyeri_mayor_obj3')" value="Uterus teraba membulat"><span class="lbl"> Uterus teraba membulat</span></label></div>
          </div>
        </div>
        <hr>
        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_92[minor_subjektif][]" id="minor_mual" onclick="checkthis('minor_mual')" value="Mual">
                <span class="lbl"> Mual</span>
              </label>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_92[minor_subjektif][]" id="minor_nafsu_makan" onclick="checkthis('minor_nafsu_makan')" value="Nafsu makan menurun / meningkat">
                <span class="lbl"> Nafsu makan menurun / meningkat</span>
              </label>
            </div>
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[minor_objektif][]" id="minor_td" onclick="checkthis('minor_td')" value="Tekanan darah meningkat"><span class="lbl"> Tekanan darah meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[minor_objektif][]" id="minor_nadi" onclick="checkthis('minor_nadi')" value="Frekuensi nadi meningkat"><span class="lbl"> Frekuensi nadi meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[minor_objektif][]" id="minor_otot" onclick="checkthis('minor_otot')" value="Ketegangan otot meningkat"><span class="lbl"> Ketegangan otot meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[minor_objektif][]" id="minor_tidur" onclick="checkthis('minor_tidur')" value="Pola tidur berubah"><span class="lbl"> Pola tidur berubah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[minor_objektif][]" id="minor_berkemih" onclick="checkthis('minor_berkemih')" value="Fungsi berkemih berubah"><span class="lbl"> Fungsi berkemih berubah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[minor_objektif][]" id="minor_diaphoresis" onclick="checkthis('minor_diaphoresis')" value="Diaphoresis"><span class="lbl"> Diaphoresis</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[minor_objektif][]" id="minor_gangguan_perilaku" onclick="checkthis('minor_gangguan_perilaku')" value="Gangguan perilaku"><span class="lbl"> Gangguan perilaku</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[minor_objektif][]" id="minor_ekspresif" onclick="checkthis('minor_ekspresif')" value="Perilaku ekspresif"><span class="lbl"> Perilaku ekspresif</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[minor_objektif][]" id="minor_pupil" onclick="checkthis('minor_pupil')" value="Pupil dilatasi"><span class="lbl"> Pupil dilatasi</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[minor_objektif][]" id="minor_muntah" onclick="checkthis('minor_muntah')" value="Muntah"><span class="lbl"> Muntah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[minor_objektif][]" id="minor_focus" onclick="checkthis('minor_focus')" value="Focus pada diri sendiri"><span class="lbl"> Focus pada diri sendiri</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->



<!-- MANAJEMEN NYERI -->
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
        <i>(Mengidentifikasi dan mengelola sensorik atau emosional yang terkait dengan kerusakan jaringan atau fungsional 
        dengan onset mendadak atau lambat dan berintensitas ringan hingga berat dan konstan)</i><br>
        <b>(I.08238)</b>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Tindakan</b><br>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_observasi][]" id="mn_observasi_1" onclick="checkthis('mn_observasi_1')" value="Identifikasi lokasi, karakteristik, durasi, frekuensi, kualitas, intensitas nyeri"><span class="lbl"> Identifikasi lokasi, karakteristik, durasi, frekuensi, kualitas, intensitas nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_observasi][]" id="mn_observasi_2" onclick="checkthis('mn_observasi_2')" value="Identifikasi skala nyeri"><span class="lbl"> Identifikasi skala nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_observasi][]" id="mn_observasi_3" onclick="checkthis('mn_observasi_3')" value="Identifikasi respons nyeri non verbal"><span class="lbl"> Identifikasi respons nyeri non verbal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_observasi][]" id="mn_observasi_4" onclick="checkthis('mn_observasi_4')" value="Identifikasi faktor yang memperberat dan memperingan nyeri"><span class="lbl"> Identifikasi faktor yang memperberat dan memperingan nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_observasi][]" id="mn_observasi_5" onclick="checkthis('mn_observasi_5')" value="Identifikasi pengetahuan dan keyakinan tentang nyeri"><span class="lbl"> Identifikasi pengetahuan dan keyakinan tentang nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_observasi][]" id="mn_observasi_6" onclick="checkthis('mn_observasi_6')" value="Identifikasi pengaruh budaya terhadap respon nyeri"><span class="lbl"> Identifikasi pengaruh budaya terhadap respon nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_observasi][]" id="mn_observasi_7" onclick="checkthis('mn_observasi_7')" value="Identifikasi pengaruh nyeri pada kualitas hidup"><span class="lbl"> Identifikasi pengaruh nyeri pada kualitas hidup</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_observasi][]" id="mn_observasi_8" onclick="checkthis('mn_observasi_8')" value="Monitor keberhasilan terapi komplementer yang sudah diberikan"><span class="lbl"> Monitor keberhasilan terapi komplementer yang sudah diberikan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_observasi][]" id="mn_observasi_9" onclick="checkthis('mn_observasi_9')" value="Monitor efek samping penggunaan analgetik"><span class="lbl"> Monitor efek samping penggunaan analgetik</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_terapeutik][]" id="mn_terapeutik_1" onclick="checkthis('mn_terapeutik_1')" value="Berikan teknik nonfarmakologis untuk mengurangi rasa nyeri"><span class="lbl"> Berikan teknik nonfarmakologis untuk mengurangi rasa nyeri (mis. TENS, hipnosis, akupresur, terapi musik, biofeedback, terapi pijat, aromaterapi, teknik imajinasi terbimbing, kompres hangat/dingin, terapi bermain)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_terapeutik][]" id="mn_terapeutik_2" onclick="checkthis('mn_terapeutik_2')" value="Kontrol lingkungan yang memperberat rasa nyeri"><span class="lbl"> Kontrol lingkungan yang memperberat rasa nyeri (mis. suhu ruangan, pencahayaan, kebisingan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_terapeutik][]" id="mn_terapeutik_3" onclick="checkthis('mn_terapeutik_3')" value="Fasilitasi istirahat dan tidur"><span class="lbl"> Fasilitasi istirahat dan tidur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_terapeutik][]" id="mn_terapeutik_4" onclick="checkthis('mn_terapeutik_4')" value="Pertimbangkan jenis dan sumber nyeri dalam pemilihan strategi meredakan nyeri"><span class="lbl"> Pertimbangkan jenis dan sumber nyeri dalam pemilihan strategi meredakan nyeri</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_edukasi][]" id="mn_edukasi_1" onclick="checkthis('mn_edukasi_1')" value="Jelaskan penyebab, periode, dan pemicu nyeri"><span class="lbl"> Jelaskan penyebab, periode, dan pemicu nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_edukasi][]" id="mn_edukasi_2" onclick="checkthis('mn_edukasi_2')" value="Jelaskan strategi meredakan nyeri"><span class="lbl"> Jelaskan strategi meredakan nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_edukasi][]" id="mn_edukasi_3" onclick="checkthis('mn_edukasi_3')" value="Anjurkan memonitor nyeri secara mandiri"><span class="lbl"> Anjurkan memonitor nyeri secara mandiri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_edukasi][]" id="mn_edukasi_4" onclick="checkthis('mn_edukasi_4')" value="Anjurkan menggunakan analgetik secara tepat"><span class="lbl"> Anjurkan menggunakan analgetik secara tepat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_edukasi][]" id="mn_edukasi_5" onclick="checkthis('mn_edukasi_5')" value="Ajarkan teknik nonfarmakologis untuk mengurangi rasa nyeri"><span class="lbl"> Ajarkan teknik nonfarmakologis untuk mengurangi rasa nyeri</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_92[mn_kolaborasi][]" id="mn_kolaborasi_1" onclick="checkthis('mn_kolaborasi_1')" value="Kolaborasi pemberian analgetik, jika perlu"><span class="lbl"> Kolaborasi pemberian analgetik, jika perlu</span></label></div>
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
        <input type="text" class="input_type" name="form_92[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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