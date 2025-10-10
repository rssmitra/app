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
      var hiddenInputName = 'form_88[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: RISIKO INTOLERANSI AKTIVITAS</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 6px;" colspan="2">
        <b>Definisi :</b><br>
        Berisiko mengalami ketidakcukupan energi untuk melakukan aktivitas sehari-hari.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- FAKTOR RISIKO -->
      <td style="border: 1px solid black; padding: 6px; vertical-align: top; width: 50%;">
        <b>FAKTOR RISIKO (Dibuktikan dengan):</b><br>

        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_88[faktor_risiko][]" id="faktor_sirkulasi" onclick="checkthis('faktor_sirkulasi')" value="Gangguan sirkulasi"><span class="lbl"> Gangguan sirkulasi</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_88[faktor_risiko][]" id="faktor_ketidakbugaran" onclick="checkthis('faktor_ketidakbugaran')" value="Ketidakbugaran status fisik"><span class="lbl"> Ketidakbugaran status fisik</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_88[faktor_risiko][]" id="faktor_riwayat" onclick="checkthis('faktor_riwayat')" value="Riwayat intoleransi aktivitas sebelumnya"><span class="lbl"> Riwayat intoleransi aktivitas sebelumnya</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_88[faktor_risiko][]" id="faktor_pengalaman" onclick="checkthis('faktor_pengalaman')" value="Tidak berpengalaman dengan suatu aktivitas"><span class="lbl"> Tidak berpengalaman dengan suatu aktivitas</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_88[faktor_risiko][]" id="faktor_pernapasan" onclick="checkthis('faktor_pernapasan')" value="Gangguan pernapasan"><span class="lbl"> Gangguan pernapasan</span></label>
        </div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 6px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_88[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Risiko Intoleransi Aktivitas (L.05047) meningkat dengan kriteria hasil:</b>
        
        <div style="display: flex; flex-wrap: wrap; margin-top: 5px;">
          <!-- KIRI -->
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_nadi" onclick="checkthis('hasil_nadi')" value="Frekuensi nadi meningkat"><span class="lbl"> Frekuensi nadi meningkat*</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_saturasi" onclick="checkthis('hasil_saturasi')" value="Saturasi oksigen meningkat"><span class="lbl"> Saturasi oksigen meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_kemudahan" onclick="checkthis('hasil_kemudahan')" value="Kemudahan dalam meningkat"><span class="lbl"> Kemudahan dalam meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_aktivitas" onclick="checkthis('hasil_aktivitas')" value="Melakukan aktivitas sehari-hari meningkat"><span class="lbl"> Melakukan aktivitas sehari-hari meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_kecepatan" onclick="checkthis('hasil_kecepatan')" value="Kecepatan berjalan meningkat"><span class="lbl"> Kecepatan berjalan meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_jarak" onclick="checkthis('hasil_jarak')" value="Jarak berjalan meningkat"><span class="lbl"> Jarak berjalan meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_kekuatan_atas" onclick="checkthis('hasil_kekuatan_atas')" value="Kekuatan tubuh bagian atas meningkat"><span class="lbl"> Kekuatan tubuh bagian atas meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_kekuatan_bawah" onclick="checkthis('hasil_kekuatan_bawah')" value="Kekuatan tubuh bagian bawah meningkat"><span class="lbl"> Kekuatan tubuh bagian bawah meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_toleransi_tangga" onclick="checkthis('hasil_toleransi_tangga')" value="Toleransi dalam menaiki tangga meningkat"><span class="lbl"> Toleransi dalam menaiki tangga meningkat</span></label></div>
          </div>

          <!-- KANAN -->
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_lelah" onclick="checkthis('hasil_lelah')" value="Keluhan lelah menurun"><span class="lbl"> Keluhan lelah menurun*</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_dispnea_aktivitas" onclick="checkthis('hasil_dispnea_aktivitas')" value="Dispnea saat aktivitas menurun"><span class="lbl"> Dispnea saat aktivitas menurun*</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_dispnea_setelah" onclick="checkthis('hasil_dispnea_setelah')" value="Dispnea setelah aktivitas menurun"><span class="lbl"> Dispnea setelah aktivitas menurun*</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_lemah" onclick="checkthis('hasil_lemah')" value="Perasaan lemah menurun"><span class="lbl"> Perasaan lemah menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_aritmia_aktivitas" onclick="checkthis('hasil_aritmia_aktivitas')" value="Aritmia saat aktivitas menurun"><span class="lbl"> Aritmia saat aktivitas menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_aritmia_setelah" onclick="checkthis('hasil_aritmia_setelah')" value="Aritmia setelah aktivitas menurun"><span class="lbl"> Aritmia setelah aktivitas menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_sianosis" onclick="checkthis('hasil_sianosis')" value="Sianosis menurun"><span class="lbl"> Sianosis menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_warna_kulit" onclick="checkthis('hasil_warna_kulit')" value="Warna kulit membaik"><span class="lbl"> Warna kulit membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_tekanan_darah" onclick="checkthis('hasil_tekanan_darah')" value="Tekanan darah membaik"><span class="lbl"> Tekanan darah membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_napas" onclick="checkthis('hasil_napas')" value="Frekuensi napas membaik"><span class="lbl"> Frekuensi napas membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[kriteria_hasil][]" id="hasil_ekg" onclick="checkthis('hasil_ekg')" value="EKG iskemia membaik"><span class="lbl"> EKG iskemia membaik</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->


<!-- MANAJEMEN ENERGI -->
<table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; font-size:13px;">
  <thead>
    <tr style="background-color:#d3d3d3;">
      <th style="width:5%; text-align:center; border:1px solid black;">NO.</th>
      <th style="width:95%; text-align:center; border:1px solid black;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td colspan="2" style="border:1px solid black; padding:6px;">
        <b>Manajemen Energi</b><br>
        <i>(Mengidentifikasi dan mengelola penggunaan energi untuk mengatasi atau mencegah kelelahan dan mengoptimalkan proses pemulihan)</i><br>
        <b>(I.05178)</b>
      </td>
    </tr>

    <!-- Tindakan 1: Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_observasi][]" id="manajemen_energi_observasi1" onclick="checkthis('manajemen_energi_observasi1')" value="Identifikasi gangguan fungsi tubuh"><span class="lbl"> Identifikasi gangguan fungsi tubuh yang mengakibatkan kelelahan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_observasi][]" id="manajemen_energi_observasi2" onclick="checkthis('manajemen_energi_observasi2')" value="Monitor kelelahan fisik dan emosional"><span class="lbl"> Monitor kelelahan fisik dan emosional</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_observasi][]" id="manajemen_energi_observasi3" onclick="checkthis('manajemen_energi_observasi3')" value="Monitor pola tidur"><span class="lbl"> Monitor pola dan jam tidur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_observasi][]" id="manajemen_energi_observasi4" onclick="checkthis('manajemen_energi_observasi4')" value="Monitor ketidaknyamanan aktivitas"><span class="lbl"> Monitor lokasi ketidaknyamanan selama melakukan aktivitas</span></label></div>
      </td>
    </tr>

    <!-- Tindakan 2: Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_terapeutik][]" id="manajemen_energi_terapeutik1" onclick="checkthis('manajemen_energi_terapeutik1')" value="Sediakan lingkungan nyaman"><span class="lbl"> Sediakan lingkungan nyaman dan rendah stimulus (mis: cahaya, suara, kunjungan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_terapeutik][]" id="manajemen_energi_terapeutik2" onclick="checkthis('manajemen_energi_terapeutik2')" value="Latihan rentang gerak"><span class="lbl"> Lakukan latihan rentang gerak pasif dan/atau aktif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_terapeutik][]" id="manajemen_energi_terapeutik3" onclick="checkthis('manajemen_energi_terapeutik3')" value="Aktivitas distraksi"><span class="lbl"> Berikan aktivitas distraksi yang menenangkan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_terapeutik][]" id="manajemen_energi_terapeutik4" onclick="checkthis('manajemen_energi_terapeutik4')" value="Fasilitasi duduk di tempat tidur"><span class="lbl"> Fasilitasi duduk di tempat tidur, jika tidak dapat berpindah atau berjalan</span></label></div>
      </td>
    </tr>

    <!-- Tindakan 3: Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_edukasi][]" id="manajemen_energi_edukasi1" onclick="checkthis('manajemen_energi_edukasi1')" value="Anjurkan tirah baring"><span class="lbl"> Anjurkan tirah baring</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_edukasi][]" id="manajemen_energi_edukasi2" onclick="checkthis('manajemen_energi_edukasi2')" value="Anjurkan aktivitas bertahap"><span class="lbl"> Anjurkan melakukan aktivitas secara bertahap</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_edukasi][]" id="manajemen_energi_edukasi3" onclick="checkthis('manajemen_energi_edukasi3')" value="Anjurkan hubungi perawat jika kelelahan tidak berkurang"><span class="lbl"> Anjurkan menghubungi perawat jika tanda dan gejala kelelahan tidak berkurang</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_edukasi][]" id="manajemen_energi_edukasi4" onclick="checkthis('manajemen_energi_edukasi4')" value="Ajarkan strategi koping"><span class="lbl"> Ajarkan strategi koping untuk mengurangi kelelahan</span></label></div>
      </td>
    </tr>

    <!-- Tindakan 4: Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_88[manajemen_energi_kolaborasi][]" id="manajemen_energi_kolaborasi1" onclick="checkthis('manajemen_energi_kolaborasi1')" value="Kolaborasi dengan ahli gizi"><span class="lbl"> Kolaborasi dengan ahli gizi tentang cara meningkatkan asupan makanan</span></label></div>
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
        <input type="text" class="input_type" name="form_88[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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