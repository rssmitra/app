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
      var hiddenInputName = 'form_95[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: KETIDAKNYAMANAN PASCA PARTUM</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Perasaan tidak nyaman yang berhubungan dengan kondisi setelah melahirkan
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[penyebab][]" id="pp_penyebab1" onclick="checkthis('pp_penyebab1')" value="Trauma perineum selama persalinan dan kelahiran"><span class="lbl"> Trauma perineum selama persalinan dan kelahiran</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[penyebab][]" id="pp_penyebab2" onclick="checkthis('pp_penyebab2')" value="Involusi uterus pengembalian ukuran rahim ke ukuran semula"><span class="lbl"> Involusi uterus pengembalian ukuran rahim ke ukuran semula</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[penyebab][]" id="pp_penyebab3" onclick="checkthis('pp_penyebab3')" value="Pembengkakan payudara dimana alveoli mulai terisi ASI"><span class="lbl"> Pembengkakan payudara dimana alveoli mulai terisi ASI</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[penyebab][]" id="pp_penyebab4" onclick="checkthis('pp_penyebab4')" value="Kekurangan dukungan dari keluarga dan tenaga kesehatan"><span class="lbl"> Kekurangan dukungan dari keluarga dan tenaga kesehatan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[penyebab][]" id="pp_penyebab5" onclick="checkthis('pp_penyebab5')" value="Ketidaktepatan posisi duduk"><span class="lbl"> Ketidaktepatan posisi duduk</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[penyebab][]" id="pp_penyebab6" onclick="checkthis('pp_penyebab6')" value="Faktor budaya"><span class="lbl"> Faktor budaya</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_95[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Status kenyamanan pasca partum (L.07061) meningkat dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[kriteria_hasil][]" id="pp_krit1" onclick="checkthis('pp_krit1')" value="Keluhan tidak nyaman meningkat"><span class="lbl"> Keluhan tidak nyaman meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[kriteria_hasil][]" id="pp_krit2" onclick="checkthis('pp_krit2')" value="Meringis meningkat"><span class="lbl"> Meringis meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[kriteria_hasil][]" id="pp_krit3" onclick="checkthis('pp_krit3')" value="Luka episiotomi meningkat"><span class="lbl"> Luka episiotomi meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[kriteria_hasil][]" id="pp_krit4" onclick="checkthis('pp_krit4')" value="Kontraksi uterus meningkat"><span class="lbl"> Kontraksi uterus meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[kriteria_hasil][]" id="pp_krit5" onclick="checkthis('pp_krit5')" value="Berkeringat meningkat"><span class="lbl"> Berkeringat meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[kriteria_hasil][]" id="pp_krit6" onclick="checkthis('pp_krit6')" value="Menangis meningkat"><span class="lbl"> Menangis meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[kriteria_hasil][]" id="pp_krit7" onclick="checkthis('pp_krit7')" value="Merintih meningkat"><span class="lbl"> Merintih meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[kriteria_hasil][]" id="pp_krit8" onclick="checkthis('pp_krit8')" value="Hemoroid meningkat"><span class="lbl"> Hemoroid meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[kriteria_hasil][]" id="pp_krit9" onclick="checkthis('pp_krit9')" value="Kontraksi uterus menurun"><span class="lbl"> Kontraksi uterus menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[kriteria_hasil][]" id="pp_krit10" onclick="checkthis('pp_krit10')" value="Payudara bengkak menurun"><span class="lbl"> Payudara bengkak menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[kriteria_hasil][]" id="pp_krit11" onclick="checkthis('pp_krit11')" value="Tekanan darah menurun"><span class="lbl"> Tekanan darah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[kriteria_hasil][]" id="pp_krit12" onclick="checkthis('pp_krit12')" value="Frekuensi nadi menurun"><span class="lbl"> Frekuensi nadi menurun</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mayor_subjektif][]" id="pp_mayor_sub1" onclick="checkthis('pp_mayor_sub1')" value="Mengeluh tidak nyaman"><span class="lbl"> Mengeluh tidak nyaman</span></label></div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mayor_objektif][]" id="pp_mayor_obj1" onclick="checkthis('pp_mayor_obj1')" value="Tampak meringis"><span class="lbl"> Tampak meringis</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mayor_objektif][]" id="pp_mayor_obj2" onclick="checkthis('pp_mayor_obj2')" value="Terdapat kontraksi uterus"><span class="lbl"> Terdapat kontraksi uterus</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mayor_objektif][]" id="pp_mayor_obj3" onclick="checkthis('pp_mayor_obj3')" value="Luka episiotomi"><span class="lbl"> Luka episiotomi</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mayor_objektif][]" id="pp_mayor_obj4" onclick="checkthis('pp_mayor_obj4')" value="Payudara bengkak"><span class="lbl"> Payudara bengkak</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[minor_objektif][]" id="pp_minor1" onclick="checkthis('pp_minor1')" value="Tekanan darah meningkat"><span class="lbl"> Tekanan darah meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[minor_objektif][]" id="pp_minor2" onclick="checkthis('pp_minor2')" value="Frekuensi nadi meningkat"><span class="lbl"> Frekuensi nadi meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[minor_objektif][]" id="pp_minor3" onclick="checkthis('pp_minor3')" value="Berkeringat berlebihan"><span class="lbl"> Berkeringat berlebihan</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[minor_objektif][]" id="pp_minor4" onclick="checkthis('pp_minor4')" value="Menangis atau merintih"><span class="lbl"> Menangis / merintih</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[minor_objektif][]" id="pp_minor5" onclick="checkthis('pp_minor5')" value="Hemoroid"><span class="lbl"> Hemoroid</span></label></div>
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
        <i>(Mengidentifikasi dan mengelola pengalaman sensorik atau emosional yang berkaitan dengan kerusakan jaringan atau fungsional dengan onset mendadak atau lambat dan berintensitas ringan hingga berat dan konstan)</i><br>
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
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_observasi][]" id="mn_observasi_1" onclick="checkthis('mn_observasi_1')" value="Identifikasi lokasi, karakteristik, durasi, frekuensi, kualitas, intensitas nyeri"><span class="lbl"> Identifikasi lokasi, karakteristik, durasi, frekuensi, kualitas, intensitas nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_observasi][]" id="mn_observasi_2" onclick="checkthis('mn_observasi_2')" value="Identifikasi skala nyeri"><span class="lbl"> Identifikasi skala nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_observasi][]" id="mn_observasi_3" onclick="checkthis('mn_observasi_3')" value="Identifikasi respons nyeri non verbal"><span class="lbl"> Identifikasi respons nyeri non verbal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_observasi][]" id="mn_observasi_4" onclick="checkthis('mn_observasi_4')" value="Identifikasi faktor yang memperberat dan memperingan nyeri"><span class="lbl"> Identifikasi faktor yang memperberat dan memperingan nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_observasi][]" id="mn_observasi_5" onclick="checkthis('mn_observasi_5')" value="Identifikasi pengetahuan dan keyakinan tentang nyeri"><span class="lbl"> Identifikasi pengetahuan dan keyakinan tentang nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_observasi][]" id="mn_observasi_6" onclick="checkthis('mn_observasi_6')" value="Identifikasi pengaruh budaya terhadap respon nyeri"><span class="lbl"> Identifikasi pengaruh budaya terhadap respon nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_observasi][]" id="mn_observasi_7" onclick="checkthis('mn_observasi_7')" value="Identifikasi pengaruh nyeri pada kualitas hidup"><span class="lbl"> Identifikasi pengaruh nyeri pada kualitas hidup</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_observasi][]" id="mn_observasi_8" onclick="checkthis('mn_observasi_8')" value="Monitor keberhasilan terapi komplementer yang sudah diberikan"><span class="lbl"> Monitor keberhasilan terapi komplementer yang sudah diberikan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_observasi][]" id="mn_observasi_9" onclick="checkthis('mn_observasi_9')" value="Monitor efek samping penggunaan analgetik"><span class="lbl"> Monitor efek samping penggunaan analgetik</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_terapeutik][]" id="mn_terapeutik_1" onclick="checkthis('mn_terapeutik_1')" value="Berikan teknik nonfarmakologis untuk mengurangi rasa nyeri (mis. TENS, hipnosis, akupresur, terapi musik, biofeedback, terapi pijat, aromaterapi, teknik imajinasi terbimbing, kompres hangat/dingin, terapi bermain)"><span class="lbl"> Berikan teknik nonfarmakologis untuk mengurangi rasa nyeri (mis. TENS, hipnosis, akupresur, terapi musik, biofeedback, terapi pijat, aromaterapi, teknik imajinasi terbimbing, kompres hangat/dingin, terapi bermain)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_terapeutik][]" id="mn_terapeutik_2" onclick="checkthis('mn_terapeutik_2')" value="Kontrol lingkungan yang memperberat rasa nyeri (mis. suhu ruangan, pencahayaan, kebisingan)"><span class="lbl"> Kontrol lingkungan yang memperberat rasa nyeri (mis. suhu ruangan, pencahayaan, kebisingan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_terapeutik][]" id="mn_terapeutik_3" onclick="checkthis('mn_terapeutik_3')" value="Fasilitasi istirahat dan tidur"><span class="lbl"> Fasilitasi istirahat dan tidur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_terapeutik][]" id="mn_terapeutik_4" onclick="checkthis('mn_terapeutik_4')" value="Pertimbangkan jenis dan sumber nyeri dalam pemilihan strategi meredakan nyeri"><span class="lbl"> Pertimbangkan jenis dan sumber nyeri dalam pemilihan strategi meredakan nyeri</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_edukasi][]" id="mn_edukasi_1" onclick="checkthis('mn_edukasi_1')" value="Jelaskan penyebab, periode, dan pemicu nyeri"><span class="lbl"> Jelaskan penyebab, periode, dan pemicu nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_edukasi][]" id="mn_edukasi_2" onclick="checkthis('mn_edukasi_2')" value="Jelaskan strategi meredakan nyeri"><span class="lbl"> Jelaskan strategi meredakan nyeri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_edukasi][]" id="mn_edukasi_3" onclick="checkthis('mn_edukasi_3')" value="Anjurkan memonitor nyeri secara mandiri"><span class="lbl"> Anjurkan memonitor nyeri secara mandiri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_edukasi][]" id="mn_edukasi_4" onclick="checkthis('mn_edukasi_4')" value="Anjurkan menggunakan analgetik secara tepat"><span class="lbl"> Anjurkan menggunakan analgetik secara tepat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_edukasi][]" id="mn_edukasi_5" onclick="checkthis('mn_edukasi_5')" value="Ajarkan teknik nonfarmakologis untuk mengurangi rasa nyeri"><span class="lbl"> Ajarkan teknik nonfarmakologis untuk mengurangi rasa nyeri</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_95[mn_kolaborasi][]" id="mn_kolaborasi_1" onclick="checkthis('mn_kolaborasi_1')" value="Kolaborasi pemberian analgetik, jika perlu"><span class="lbl"> Kolaborasi pemberian analgetik, jika perlu</span></label></div>
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
        <input type="text" class="input_type" name="form_95[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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