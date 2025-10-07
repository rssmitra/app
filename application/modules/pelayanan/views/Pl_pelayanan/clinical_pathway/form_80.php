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
      var hiddenInputName = 'form_80[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: NYERI KRONIS</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Definisi:</b> Pengalaman sensorik atau emosional yang berkaitan dengan kerusakan jaringan aktual atau fungsional, 
        dengan onset mendadak atau lambat dan berintensitas ringan hingga berat dan konstan, yang berlangsung lebih dari 3 bulan.
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td width="50%" style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif; font-size: 13px;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_musculoskeletal" onclick="checkthis('penyebab_musculoskeletal')" value="Kondisi musculoskeletal kronis"><span class="lbl"> Kondisi musculoskeletal kronis</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_saraf" onclick="checkthis('penyebab_saraf')" value="Kerusakan sistem saraf"><span class="lbl"> Kerusakan sistem saraf</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_penekanan" onclick="checkthis('penyebab_penekanan')" value="Penekanan saraf"><span class="lbl"> Penekanan saraf</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_tumor" onclick="checkthis('penyebab_tumor')" value="Infiltrasi tumor"><span class="lbl"> Infiltrasi tumor</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_neurotransmitter" onclick="checkthis('penyebab_neurotransmitter')" value="Ketidakseimbangan neurotransmitter, neuromodulator, dan reseptor"><span class="lbl"> Ketidakseimbangan neurotransmitter, neuromodulator, dan reseptor</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_imunitas" onclick="checkthis('penyebab_imunitas')" value="Gangguan imunitas (mis. Neuropathy terkait HIV, virus varicella–zoster)"><span class="lbl"> Gangguan imunitas (mis. Neuropathy terkait HIV, virus varicella–zoster)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_metabolik" onclick="checkthis('penyebab_metabolik')" value="Gangguan fungsi metabolik"><span class="lbl"> Gangguan fungsi metabolik</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_posisi" onclick="checkthis('penyebab_posisi')" value="Riwayat posisi kerja statis"><span class="lbl"> Riwayat posisi kerja statis</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_bmi" onclick="checkthis('penyebab_bmi')" value="Peningkatan indeks massa tubuh"><span class="lbl"> Peningkatan indeks massa tubuh</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_trauma" onclick="checkthis('penyebab_trauma')" value="Kondisi pasca trauma"><span class="lbl"> Kondisi pasca trauma</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_emosional" onclick="checkthis('penyebab_emosional')" value="Tekanan emosional"><span class="lbl"> Tekanan emosional</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_penganiayaan" onclick="checkthis('penyebab_penganiayaan')" value="Riwayat penganiayaan (fisik, psikologis, seksual)"><span class="lbl"> Riwayat penganiayaan (fisik, psikologis, seksual)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[penyebab][]" id="penyebab_zat" onclick="checkthis('penyebab_zat')" value="Riwayat penyalahgunaan zat/obat"><span class="lbl"> Riwayat penyalahgunaan zat/obat</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif; font-size: 13px;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_80[intervensi_selama]" id="intervensi_selama" onchange="fillthis('intervensi_selama')" style="width:10%;">
          maka tingkat nyeri (L.08066) menurun dengan kriteria hasil:</b><br><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_aktivitas" onclick="checkthis('hasil_aktivitas')" value="Kemampuan menuntaskan aktivitas"><span class="lbl"> Kemampuan menuntaskan aktivitas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_keluhan" onclick="checkthis('hasil_keluhan')" value="Keluhan nyeri menurun"><span class="lbl"> Keluhan nyeri menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_meringis" onclick="checkthis('hasil_meringis')" value="Meringis menurun"><span class="lbl"> Meringis menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_protektif" onclick="checkthis('hasil_protektif')" value="Sikap protektif menurun"><span class="lbl"> Sikap protektif menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_gelisah" onclick="checkthis('hasil_gelisah')" value="Gelisah menurun"><span class="lbl"> Gelisah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_tidur" onclick="checkthis('hasil_tidur')" value="Kesulitan tidur menurun"><span class="lbl"> Kesulitan tidur menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_menarik_diri" onclick="checkthis('hasil_menarik_diri')" value="Menarik diri menurun"><span class="lbl"> Menarik diri menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_fokus" onclick="checkthis('hasil_fokus')" value="Berfokus pada diri sendiri menurun"><span class="lbl"> Berfokus pada diri sendiri menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_diaforesis" onclick="checkthis('hasil_diaforesis')" value="Diaforesis menurun"><span class="lbl"> Diaforesis menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_depresi" onclick="checkthis('hasil_depresi')" value="Perasaan depresi menurun"><span class="lbl"> Perasaan depresi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_takut" onclick="checkthis('hasil_takut')" value="Perasaan takut mengalami cedera berulang menurun"><span class="lbl"> Perasaan takut mengalami cedera berulang menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_anoreksia" onclick="checkthis('hasil_anoreksia')" value="Anoreksia menurun"><span class="lbl"> Anoreksia menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_otot" onclick="checkthis('hasil_otot')" value="Ketegangan otot menurun"><span class="lbl"> Ketegangan otot menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_pupil" onclick="checkthis('hasil_pupil')" value="Pupil dilatasi menurun"><span class="lbl"> Pupil dilatasi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_mual" onclick="checkthis('hasil_mual')" value="Mual menurun"><span class="lbl"> Mual menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_muntah" onclick="checkthis('hasil_muntah')" value="Muntah menurun"><span class="lbl"> Muntah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_nadi" onclick="checkthis('hasil_nadi')" value="Frekuensi nadi membaik"><span class="lbl"> Frekuensi nadi membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_pola_nafas" onclick="checkthis('hasil_pola_nafas')" value="Pola napas membaik"><span class="lbl"> Pola napas membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_tekanan_darah" onclick="checkthis('hasil_tekanan_darah')" value="Tekanan darah membaik"><span class="lbl"> Tekanan darah membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[kriteria_hasil][]" id="hasil_tidur_baik" onclick="checkthis('hasil_tidur_baik')" value="Pola tidur membaik"><span class="lbl"> Pola tidur membaik</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mayor_subjektif][]" id="mayor_sub_nyeri" onclick="checkthis('mayor_sub_nyeri')" value="Mengeluh nyeri"><span class="lbl"> Mengeluh nyeri</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mayor_subjektif][]" id="mayor_sub_depresi" onclick="checkthis('mayor_sub_depresi')" value="Merasa depresi (tertekan)"><span class="lbl"> Merasa depresi (tertekan)</span></label></div>
          </div>
        

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mayor_objektif][]" id="mayor_obj_meringis" onclick="checkthis('mayor_obj_meringis')" value="Tampak meringis"><span class="lbl"> Tampak meringis</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mayor_objektif][]" id="mayor_obj_gelisah" onclick="checkthis('mayor_obj_gelisah')" value="Gelisah"><span class="lbl"> Gelisah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mayor_objektif][]" id="mayor_obj_aktivitas" onclick="checkthis('mayor_obj_aktivitas')" value="Tidak mampu menuntaskan aktivitas"><span class="lbl"> Tidak mampu menuntaskan aktivitas</span></label></div>
            </div>
         </div>

        <hr>

        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[minor_subjektif][]" id="minor_sub_takut" onclick="checkthis('minor_sub_takut')" value="Merasa takut mengalami cedera berulang"><span class="lbl"> Merasa takut mengalami cedera berulang</span></label></div>
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[minor_objektif][]" id="minor_obj_protektif" onclick="checkthis('minor_obj_protektif')" value="Bersikap protektif (mis. posisi menghindari nyeri)"><span class="lbl"> Bersikap protektif (mis. posisi menghindari nyeri)</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[minor_objektif][]" id="minor_obj_waspada" onclick="checkthis('minor_obj_waspada')" value="Waspada"><span class="lbl"> Waspada</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[minor_objektif][]" id="minor_obj_tidur" onclick="checkthis('minor_obj_tidur')" value="Pola tidur berubah"><span class="lbl"> Pola tidur berubah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[minor_objektif][]" id="minor_obj_anoreksia" onclick="checkthis('minor_obj_anoreksia')" value="Anoreksia"><span class="lbl"> Anoreksia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[minor_objektif][]" id="minor_obj_fokus" onclick="checkthis('minor_obj_fokus')" value="Fokus menyempit"><span class="lbl"> Fokus menyempit</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[minor_objektif][]" id="minor_obj_diri" onclick="checkthis('minor_obj_diri')" value="Berfokus pada diri sendiri"><span class="lbl"> Berfokus pada diri sendiri</span></label></div>
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
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Manajemen Nyeri</b><br>
        <i>(Mengidentifikasi dan mengelola sensorik atau emosional yang terkait dengan kerusakan jaringan atau fungsional
        dengan onset mendadak atau lambat dan berintensitas ringan hingga berat dan konstan)</i><br>
        <b>(I.08238)</b>
      </td>
    </tr>

    <!-- 1. Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_observasi][]" id="mn_observasi_1" onclick="checkthis('mn_observasi_1')" value="Identifikasi lokasi, karakteristik, durasi, frekuensi, kualitas, intensitas nyeri"><span class="lbl"> Identifikasi lokasi, karakteristik, durasi, frekuensi, kualitas, intensitas nyeri</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_observasi][]" id="mn_observasi_2" onclick="checkthis('mn_observasi_2')" value="Identifikasi skala nyeri"><span class="lbl"> Identifikasi skala nyeri</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_observasi][]" id="mn_observasi_3" onclick="checkthis('mn_observasi_3')" value="Identifikasi respons nyeri non verbal"><span class="lbl"> Identifikasi respons nyeri non verbal</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_observasi][]" id="mn_observasi_4" onclick="checkthis('mn_observasi_4')" value="Identifikasi faktor yang memperberat dan memperingan nyeri"><span class="lbl"> Identifikasi faktor yang memperberat dan memperingan nyeri</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_observasi][]" id="mn_observasi_5" onclick="checkthis('mn_observasi_5')" value="Identifikasi pengetahuan dan keyakinan tentang nyeri"><span class="lbl"> Identifikasi pengetahuan dan keyakinan tentang nyeri</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_observasi][]" id="mn_observasi_6" onclick="checkthis('mn_observasi_6')" value="Identifikasi pengaruh budaya terhadap respon nyeri"><span class="lbl"> Identifikasi pengaruh budaya terhadap respon nyeri</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_observasi][]" id="mn_observasi_7" onclick="checkthis('mn_observasi_7')" value="Identifikasi pengaruh nyeri pada kualitas hidup"><span class="lbl"> Identifikasi pengaruh nyeri pada kualitas hidup</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_observasi][]" id="mn_observasi_8" onclick="checkthis('mn_observasi_8')" value="Monitor keberhasilan terapi komplementer yang sudah diberikan"><span class="lbl"> Monitor keberhasilan terapi komplementer yang sudah diberikan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_observasi][]" id="mn_observasi_9" onclick="checkthis('mn_observasi_9')" value="Monitor efek samping penggunaan analgetik"><span class="lbl"> Monitor efek samping penggunaan analgetik</span></label></div>
      </td>
    </tr>

    <!-- 2. Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_terapeutik][]" id="mn_terapeutik_1" onclick="checkthis('mn_terapeutik_1')" value="Berikan teknik nonfarmakologis untuk mengurangi rasa nyeri (mis. TENS, hipnosis, akupresur, terapi musik, biofeedback, terapi pijat, aromaterapi, teknik imajinasi terbimbing, kompres hangat/dingin, terapi bermain)"><span class="lbl"> Berikan teknik nonfarmakologis untuk mengurangi rasa nyeri (mis. TENS, hipnosis, akupresur, terapi musik, biofeedback, terapi pijat, aromaterapi, teknik imajinasi terbimbing, kompres hangat/dingin, terapi bermain)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_terapeutik][]" id="mn_terapeutik_2" onclick="checkthis('mn_terapeutik_2')" value="Kontrol lingkungan yang memperberat rasa nyeri (mis. suhu ruangan, pencahayaan, kebisingan)"><span class="lbl"> Kontrol lingkungan yang memperberat rasa nyeri (mis. suhu ruangan, pencahayaan, kebisingan)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_terapeutik][]" id="mn_terapeutik_3" onclick="checkthis('mn_terapeutik_3')" value="Fasilitasi istirahat dan tidur"><span class="lbl"> Fasilitasi istirahat dan tidur</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_terapeutik][]" id="mn_terapeutik_4" onclick="checkthis('mn_terapeutik_4')" value="Pertimbangkan jenis dan sumber nyeri dalam pemilihan strategi meredakan nyeri"><span class="lbl"> Pertimbangkan jenis dan sumber nyeri dalam pemilihan strategi meredakan nyeri</span></label></div>
      </td>
    </tr>

    <!-- 3. Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_edukasi][]" id="mn_edukasi_1" onclick="checkthis('mn_edukasi_1')" value="Jelaskan penyebab, periode, dan pemicu nyeri"><span class="lbl"> Jelaskan penyebab, periode, dan pemicu nyeri</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_edukasi][]" id="mn_edukasi_2" onclick="checkthis('mn_edukasi_2')" value="Jelaskan strategi meredakan nyeri"><span class="lbl"> Jelaskan strategi meredakan nyeri</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_edukasi][]" id="mn_edukasi_3" onclick="checkthis('mn_edukasi_3')" value="Anjurkan memonitor nyeri secara mandiri"><span class="lbl"> Anjurkan memonitor nyeri secara mandiri</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_edukasi][]" id="mn_edukasi_4" onclick="checkthis('mn_edukasi_4')" value="Anjurkan menggunakan analgetik secara tepat"><span class="lbl"> Anjurkan menggunakan analgetik secara tepat</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_edukasi][]" id="mn_edukasi_5" onclick="checkthis('mn_edukasi_5')" value="Ajarkan teknik nonfarmakologis untuk mengurangi rasa nyeri"><span class="lbl"> Ajarkan teknik nonfarmakologis untuk mengurangi rasa nyeri</span></label></div>
      </td>
    </tr>

    <!-- 4. Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[mn_kolaborasi][]" id="mn_kolaborasi_1" onclick="checkthis('mn_kolaborasi_1')" value="Kolaborasi pemberian analgetik, jika perlu"><span class="lbl"> Kolaborasi pemberian analgetik, jika perlu</span></label></div>
      </td>
    </tr>

    <!-- Pemberian Analgesik -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Pemberian Analgesik</b><br>
        <i>(Menyiapkan dan memberikan agen farmakologis untuk mengurangi atau menghilangkan rasa sakit)</i><br>
        <b>(I.084243)</b><br>
        <b>Tindakan:</b>
      </td>
    </tr>

    <!-- 1. Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[analgesik_observasi][]" id="analgesik_observasi_1" onclick="checkthis('analgesik_observasi_1')" value="Identifikasi karakteristik nyeri (misal pencetus, pereda, kualitas lokasi, intensitas, frekuensi, durasi)"><span class="lbl"> Identifikasi karakteristik nyeri (misal pencetus, pereda, kualitas lokasi, intensitas, frekuensi, durasi)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[analgesik_observasi][]" id="analgesik_observasi_2" onclick="checkthis('analgesik_observasi_2')" value="Identifikasi riwayat alergi obat"><span class="lbl"> Identifikasi riwayat alergi obat</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[analgesik_observasi][]" id="analgesik_observasi_3" onclick="checkthis('analgesik_observasi_3')" value="Identifikasi kesesuaian jenis analgesik (misal narkotika, non-narkotik atau NSAID) dengan tingkat keparahan nyeri"><span class="lbl"> Identifikasi kesesuaian jenis analgesik (misal narkotika, non-narkotik atau NSAID) dengan tingkat keparahan nyeri</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[analgesik_observasi][]" id="analgesik_observasi_4" onclick="checkthis('analgesik_observasi_4')" value="Monitor tanda-tanda vital sebelum dan sesudah pemberian analgesik"><span class="lbl"> Monitor tanda-tanda vital sebelum dan sesudah pemberian analgesik</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[analgesik_observasi][]" id="analgesik_observasi_5" onclick="checkthis('analgesik_observasi_5')" value="Monitor efektivitas analgesik"><span class="lbl"> Monitor efektivitas analgesik</span></label></div>
      </td>
    </tr>

    <!-- 2. Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[analgesik_terapeutik][]" id="analgesik_terapeutik_1" onclick="checkthis('analgesik_terapeutik_1')" value="Diskusikan jenis analgesik yang disukai untuk mencapai analgesia optimal, jika perlu"><span class="lbl"> Diskusikan jenis analgesik yang disukai untuk mencapai analgesia optimal, jika perlu</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[analgesik_terapeutik][]" id="analgesik_terapeutik_2" onclick="checkthis('analgesik_terapeutik_2')" value="Pertimbangkan penggunaan infus kontinu atau bolus opioid untuk mempertahankan kadar dalam serum"><span class="lbl"> Pertimbangkan penggunaan infus kontinu atau bolus opioid untuk mempertahankan kadar dalam serum</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[analgesik_terapeutik][]" id="analgesik_terapeutik_3" onclick="checkthis('analgesik_terapeutik_3')" value="Tetapkan target efektivitas analgesik untuk mengoptimalkan respon pasien"><span class="lbl"> Tetapkan target efektivitas analgesik untuk mengoptimalkan respon pasien</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[analgesik_terapeutik][]" id="analgesik_terapeutik_4" onclick="checkthis('analgesik_terapeutik_4')" value="Dokumentasikan respon terhadap efek analgesik dan efek yang tidak diinginkan"><span class="lbl"> Dokumentasikan respon terhadap efek analgesik dan efek yang tidak diinginkan</span></label></div>
      </td>
    </tr>

    <!-- 3. Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[analgesik_edukasi][]" id="analgesik_edukasi_1" onclick="checkthis('analgesik_edukasi_1')" value="Jelaskan efek terapi dan efek samping obat"><span class="lbl"> Jelaskan efek terapi dan efek samping obat</span></label></div>
      </td>
    </tr>

    <!-- 4. Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_80[analgesik_kolaborasi][]" id="analgesik_kolaborasi_1" onclick="checkthis('analgesik_kolaborasi_1')" value="Kolaborasi pemberian dosis dan jenis analgesik sesuai indikasi"><span class="lbl"> Kolaborasi pemberian dosis dan jenis analgesik sesuai indikasi</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<!-- END PEMBERIAN ANALGESIK -->




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
        <input type="text" class="input_type" name="form_80[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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