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
      var hiddenInputName = 'form_99[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: HIPOTERMIA</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Suhu tubuh berada di bawah rentang normal tubuh
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab1" onclick="checkthis('hipo_penyebab1')" value="Kerusakan hipotalamus"><span class="lbl"> Kerusakan hipotalamus</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab2" onclick="checkthis('hipo_penyebab2')" value="Konsumsi alkohol"><span class="lbl"> Konsumsi alkohol</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab3" onclick="checkthis('hipo_penyebab3')" value="Berat badan ekstrem"><span class="lbl"> Berat badan ekstrem</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab4" onclick="checkthis('hipo_penyebab4')" value="Kekurangan lemak subkutan"><span class="lbl"> Kekurangan lemak subkutan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab5" onclick="checkthis('hipo_penyebab5')" value="Terpapar suhu lingkungan rendah"><span class="lbl"> Terpapar suhu lingkungan rendah</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab6" onclick="checkthis('hipo_penyebab6')" value="Malnutrisi"><span class="lbl"> Malnutrisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab7" onclick="checkthis('hipo_penyebab7')" value="Pemakaian pakaian tipis"><span class="lbl"> Pemakaian pakaian tipis</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab8" onclick="checkthis('hipo_penyebab8')" value="Penurunan laju metabolisme"><span class="lbl"> Penurunan laju metabolisme</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab9" onclick="checkthis('hipo_penyebab9')" value="Tidak beraktivitas"><span class="lbl"> Tidak beraktivitas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab10" onclick="checkthis('hipo_penyebab10')" value="Transfer panas (konduksi, konveksi, evaporasi, radiasi)"><span class="lbl"> Transfer panas (mis. konduksi, konveksi, evaporasi, radiasi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab11" onclick="checkthis('hipo_penyebab11')" value="Trauma"><span class="lbl"> Trauma</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab12" onclick="checkthis('hipo_penyebab12')" value="Proses penuaan"><span class="lbl"> Proses penuaan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab13" onclick="checkthis('hipo_penyebab13')" value="Efek agen farmakologis"><span class="lbl"> Efek agen farmakologis</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[penyebab][]" id="hipo_penyebab14" onclick="checkthis('hipo_penyebab14')" value="Kurang terpapar informasi tentang pencegahan hipotermia"><span class="lbl"> Kurang terpapar informasi tentang pencegahan hipotermia</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_99[hipo_intervensi_selama]" id="hipo_intervensi_selama" onchange="fillthis('hipo_intervensi_selama')" style="width:10%;">
          , maka suhu tubuh membaik (L.14134), dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit1" onclick="checkthis('hipo_krit1')" value="Kulit merah menurun"><span class="lbl"> Kulit merah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit2" onclick="checkthis('hipo_krit2')" value="Takipnea menurun"><span class="lbl"> Takipnea menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit3" onclick="checkthis('hipo_krit3')" value="Menggigil menurun"><span class="lbl"> Menggigil menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit4" onclick="checkthis('hipo_krit4')" value="Kejang menurun"><span class="lbl"> Kejang menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit5" onclick="checkthis('hipo_krit5')" value="Akrosianosis menurun"><span class="lbl"> Akrosianosis menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit6" onclick="checkthis('hipo_krit6')" value="Konsumsi oksigen menurun"><span class="lbl"> Konsumsi oksigen menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit7" onclick="checkthis('hipo_krit7')" value="Piloereksi menurun"><span class="lbl"> Piloereksi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit8" onclick="checkthis('hipo_krit8')" value="Vasokontriksi perifer menurun"><span class="lbl"> Vasokontriksi perifer menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit9" onclick="checkthis('hipo_krit9')" value="Kutis memorata menurun"><span class="lbl"> Kutis memorata menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit10" onclick="checkthis('hipo_krit10')" value="Pucat menurun"><span class="lbl"> Pucat menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit11" onclick="checkthis('hipo_krit11')" value="Takikardi menurun"><span class="lbl"> Takikardi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit12" onclick="checkthis('hipo_krit12')" value="Bradikardi menurun"><span class="lbl"> Bradikardi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit13" onclick="checkthis('hipo_krit13')" value="Dasar kuku sianotik menurun"><span class="lbl"> Dasar kuku sianotik menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit14" onclick="checkthis('hipo_krit14')" value="Hipoksia menurun"><span class="lbl"> Hipoksia menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit15" onclick="checkthis('hipo_krit15')" value="Suhu tubuh membaik"><span class="lbl"> Suhu tubuh membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit16" onclick="checkthis('hipo_krit16')" value="Suhu kulit membaik"><span class="lbl"> Suhu kulit membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit17" onclick="checkthis('hipo_krit17')" value="Kadar glukosa darah membaik"><span class="lbl"> Kadar glukosa darah membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit18" onclick="checkthis('hipo_krit18')" value="Pengisian kapiler membaik"><span class="lbl"> Pengisian kapiler membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit19" onclick="checkthis('hipo_krit19')" value="Ventilasi membaik"><span class="lbl"> Ventilasi membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[kriteria_hasil][]" id="hipo_krit20" onclick="checkthis('hipo_krit20')" value="Tekanan darah membaik"><span class="lbl"> Tekanan darah membaik</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[mayor_objektif][]" id="hipo_mayor_obj1" onclick="checkthis('hipo_mayor_obj1')" value="Kulit teraba dingin"><span class="lbl"> Kulit teraba dingin</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[mayor_objektif][]" id="hipo_mayor_obj2" onclick="checkthis('hipo_mayor_obj2')" value="Menggigil"><span class="lbl"> Menggigil</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[mayor_objektif][]" id="hipo_mayor_obj3" onclick="checkthis('hipo_mayor_obj3')" value="Suhu tubuh di bawah nilai normal"><span class="lbl"> Suhu tubuh di bawah nilai normal</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[minor_objektif][]" id="hipo_minor1" onclick="checkthis('hipo_minor1')" value="Akrosianosis"><span class="lbl"> Akrosianosis</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[minor_objektif][]" id="hipo_minor2" onclick="checkthis('hipo_minor2')" value="Bradikardi"><span class="lbl"> Bradikardi</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[minor_objektif][]" id="hipo_minor3" onclick="checkthis('hipo_minor3')" value="Dasar kuku sianotik"><span class="lbl"> Dasar kuku sianotik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[minor_objektif][]" id="hipo_minor4" onclick="checkthis('hipo_minor4')" value="Hipoglikemia"><span class="lbl"> Hipoglikemia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[minor_objektif][]" id="hipo_minor5" onclick="checkthis('hipo_minor5')" value="Hipoksia"><span class="lbl"> Hipoksia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[minor_objektif][]" id="hipo_minor6" onclick="checkthis('hipo_minor6')" value="Pengisian kapiler > 3 detik"><span class="lbl"> Pengisian kapiler > 3 detik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[minor_objektif][]" id="hipo_minor7" onclick="checkthis('hipo_minor7')" value="Konsumsi oksigen meningkat"><span class="lbl"> Konsumsi oksigen meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[minor_objektif][]" id="hipo_minor8" onclick="checkthis('hipo_minor8')" value="Ventilasi menurun"><span class="lbl"> Ventilasi menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[minor_objektif][]" id="hipo_minor9" onclick="checkthis('hipo_minor9')" value="Piloereksi"><span class="lbl"> Piloereksi</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[minor_objektif][]" id="hipo_minor10" onclick="checkthis('hipo_minor10')" value="Vasokontriksi perifer"><span class="lbl"> Vasokontriksi perifer</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[minor_objektif][]" id="hipo_minor11" onclick="checkthis('hipo_minor11')" value="Takikardi"><span class="lbl"> Takikardi</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[minor_objektif][]" id="hipo_minor12" onclick="checkthis('hipo_minor12')" value="Kutis memorata (pada neonatus)"><span class="lbl"> Kutis memorata (pada neonatus)</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->


<!-- MANAJEMEN HIPOTERMIA -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Manajemen Hipotermia -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Manajemen Hipotermia</b><br>
        <i>(Mengidentifikasi dan mengelola suhu tubuh dibawah rentang normal)</i><br>
        <b>(I.14507)</b>
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
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[hipo_observasi][]" id="hipo_observasi1" onclick="checkthis('hipo_observasi1')" value="Monitor suhu tubuh"><span class="lbl"> Monitor suhu tubuh</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[hipo_observasi][]" id="hipo_observasi2" onclick="checkthis('hipo_observasi2')" value="Identifikasi penyebab hipotermia (mis. terpapar suhu lingkungan rendah, pakaian tipis, kerusakan hipotalamus, penurunan laju metabolisme, kekurangan lemak subkutan)"><span class="lbl"> Identifikasi penyebab hipotermia (mis. terpapar suhu lingkungan rendah, pakaian tipis, kerusakan hipotalamus, penurunan laju metabolisme, kekurangan lemak subkutan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[hipo_observasi][]" id="hipo_observasi3" onclick="checkthis('hipo_observasi3')" value="Monitor tanda dan gejala akibat hipotermia (hipotermia ringan: takipnea, disartria, menggigil, hipertensi, diuresis; hipotermia sedang: aritmia, hipotensi, apatis, koagulasi, refleks menurun; hipotermia berat: oliguria, refleks menghilang, edema paru, asam-basa normal)"><span class="lbl"> Monitor tanda dan gejala akibat hipotermia (hipotermia ringan: takipnea, disartria, menggigil, hipertensi, diuresis; hipotermia sedang: aritmia, hipotensi, apatis, koagulasi, refleks menurun; hipotermia berat: oliguria, refleks menghilang, edema paru, asam-basa normal)</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[hipo_terapeutik][]" id="hipo_terapeutik1" onclick="checkthis('hipo_terapeutik1')" value="Sediakan lingkungan yang hangat (mis. atur suhu ruangan, inkubator)"><span class="lbl"> Sediakan lingkungan yang hangat (mis. atur suhu ruangan, inkubator)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[hipo_terapeutik][]" id="hipo_terapeutik2" onclick="checkthis('hipo_terapeutik2')" value="Ganti pakaian dan/atau linen yang basah"><span class="lbl"> Ganti pakaian dan/atau linen yang basah</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[hipo_terapeutik][]" id="hipo_terapeutik3" onclick="checkthis('hipo_terapeutik3')" value="Lakukan penghangatan pasif (mis. selimut, penutup kepala, pakaian tebal)"><span class="lbl"> Lakukan penghangatan pasif (mis. selimut, penutup kepala, pakaian tebal)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[hipo_terapeutik][]" id="hipo_terapeutik4" onclick="checkthis('hipo_terapeutik4')" value="Lakukan penghangatan pasif eksternal (mis. kompres hangat, botol hangat, selimut hangat, perawatan metode kanguru)"><span class="lbl"> Lakukan penghangatan pasif eksternal (mis. kompres hangat, botol hangat, selimut hangat, perawatan metode kanguru)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[hipo_terapeutik][]" id="hipo_terapeutik5" onclick="checkthis('hipo_terapeutik5')" value="Lakukan penghangatan aktif internal (mis. infus cairan hangat, oksigen hangat, lavase peritoneal dengan cairan hangat)"><span class="lbl"> Lakukan penghangatan aktif internal (mis. infus cairan hangat, oksigen hangat, lavase peritoneal dengan cairan hangat)</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[hipo_edukasi][]" id="hipo_edukasi1" onclick="checkthis('hipo_edukasi1')" value="Anjurkan makan/minum hangat"><span class="lbl"> Anjurkan makan/minum hangat</span></label></div>
      </td>
    </tr>

    <!-- Terapi Paparan Panas -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Terapi Paparan Panas</b><br>
        <i>(Menstimulasi kulit dan jaringan dibawahnya dengan panas untuk mengurangi rasa nyeri dan ketidaknyamanan lainnya)</i><br>
        <b>(I.14586)</b>
      </td>
    </tr>

    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Tindakan</b><br>
      </td>
    </tr>

    <!-- Observasi Terapi Panas -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_observasi][]" id="panas_observasi1" onclick="checkthis('panas_observasi1')" value="Identifikasi kontraindikasi penggunaan terapi (mis. penurunan atau tidak adanya sensasi, penurunan sirkulasi)"><span class="lbl"> Identifikasi kontraindikasi penggunaan terapi (mis. penurunan atau tidak adanya sensasi, penurunan sirkulasi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_observasi][]" id="panas_observasi2" onclick="checkthis('panas_observasi2')" value="Monitor suhu alat terapi"><span class="lbl"> Monitor suhu alat terapi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_observasi][]" id="panas_observasi3" onclick="checkthis('panas_observasi3')" value="Monitor kondisi kulit selama terapi"><span class="lbl"> Monitor kondisi kulit selama terapi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_observasi][]" id="panas_observasi4" onclick="checkthis('panas_observasi4')" value="Monitor kondisi umum, keamanan, dan kenyamanan selama terapi"><span class="lbl"> Monitor kondisi umum, keamanan, dan kenyamanan selama terapi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_observasi][]" id="panas_observasi5" onclick="checkthis('panas_observasi5')" value="Monitor respon pasien terhadap terapi"><span class="lbl"> Monitor respon pasien terhadap terapi</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik Terapi Panas -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_terapeutik][]" id="panas_terapeutik1" onclick="checkthis('panas_terapeutik1')" value="Pilih metode stimulasi yang nyaman dan mudah didapatkan (mis. bantal panas listrik, botol air panas, lilin parafin, lampu)"><span class="lbl"> Pilih metode stimulasi yang nyaman dan mudah didapatkan (mis. bantal panas listrik, botol air panas, lilin parafin, lampu)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_terapeutik][]" id="panas_terapeutik2" onclick="checkthis('panas_terapeutik2')" value="Pilih lokasi stimulasi yang sesuai"><span class="lbl"> Pilih lokasi stimulasi yang sesuai</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_terapeutik][]" id="panas_terapeutik3" onclick="checkthis('panas_terapeutik3')" value="Bungkus alat terapi dengan menggunakan kain"><span class="lbl"> Bungkus alat terapi dengan menggunakan kain</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_terapeutik][]" id="panas_terapeutik4" onclick="checkthis('panas_terapeutik4')" value="Gunakan kain lembab disekitar area terapi"><span class="lbl"> Gunakan kain lembab disekitar area terapi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_terapeutik][]" id="panas_terapeutik5" onclick="checkthis('panas_terapeutik5')" value="Tentukan durasi terapi sesuai dengan respon pasien"><span class="lbl"> Tentukan durasi terapi sesuai dengan respon pasien</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_terapeutik][]" id="panas_terapeutik6" onclick="checkthis('panas_terapeutik6')" value="Hindari melakukan terapi pada daerah yang mendapatkan terapi radiasi"><span class="lbl"> Hindari melakukan terapi pada daerah yang mendapatkan terapi radiasi</span></label></div>
      </td>
    </tr>

    <!-- Edukasi Terapi Panas -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_edukasi][]" id="panas_edukasi1" onclick="checkthis('panas_edukasi1')" value="Ajarkan cara mencegah kerusakan jaringan"><span class="lbl"> Ajarkan cara mencegah kerusakan jaringan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_99[panas_edukasi][]" id="panas_edukasi2" onclick="checkthis('panas_edukasi2')" value="Ajarkan cara menyesuaikan suhu secara mandiri"><span class="lbl"> Ajarkan cara menyesuaikan suhu secara mandiri</span></label></div>
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
        <input type="text" class="input_type" name="form_99[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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