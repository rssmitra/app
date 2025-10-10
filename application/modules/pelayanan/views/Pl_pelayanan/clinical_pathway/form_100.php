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
      var hiddenInputName = 'form_100[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: GANGGUAN INTEGRITAS KULIT</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Kerusakan kulit (dermis dan atau epidermis) atau jaringan (membran mukosa, kornea, fasia, otot, tendon, tulang, kartilago, kapsul sendi dan atau ligamen)
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab1" onclick="checkthis('kulit_penyebab1')" value="Perubahan sirkulasi"><span class="lbl"> Perubahan sirkulasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab2" onclick="checkthis('kulit_penyebab2')" value="Perubahan status nutrisi"><span class="lbl"> Perubahan status nutrisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab3" onclick="checkthis('kulit_penyebab3')" value="Kekurangan/kelebihan volume cairan"><span class="lbl"> Kekurangan/kelebihan volume cairan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab4" onclick="checkthis('kulit_penyebab4')" value="Penurunan mobilitas"><span class="lbl"> Penurunan mobilitas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab5" onclick="checkthis('kulit_penyebab5')" value="Bahan kimia iritatif"><span class="lbl"> Bahan kimia iritatif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab6" onclick="checkthis('kulit_penyebab6')" value="Suhu lingkungan yang ekstrem"><span class="lbl"> Suhu lingkungan yang ekstrem</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab7" onclick="checkthis('kulit_penyebab7')" value="Faktor mekanis atau elektris"><span class="lbl"> Faktor mekanis (mis. penekanan pada tonjolan tulang, gesekan) atau faktor elektris (energi listrik tegangan tinggi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab8" onclick="checkthis('kulit_penyebab8')" value="Efek samping terapi radiasi"><span class="lbl"> Efek samping terapi radiasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab9" onclick="checkthis('kulit_penyebab9')" value="Kelembaban"><span class="lbl"> Kelembaban</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab10" onclick="checkthis('kulit_penyebab10')" value="Proses penuaan"><span class="lbl"> Proses penuaan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab11" onclick="checkthis('kulit_penyebab11')" value="Neurotif perifer"><span class="lbl"> Neurotif perifer</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab12" onclick="checkthis('kulit_penyebab12')" value="Perubahan pigmentasi"><span class="lbl"> Perubahan pigmentasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab13" onclick="checkthis('kulit_penyebab13')" value="Perubahan hormonal"><span class="lbl"> Perubahan hormonal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[penyebab][]" id="kulit_penyebab14" onclick="checkthis('kulit_penyebab14')" value="Kurang terpapar informasi tentang upaya mempertahankan integritas jaringan"><span class="lbl"> Kurang terpapar informasi tentang upaya mempertahankan integritas jaringan</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_100[kulit_intervensi_selama]" id="kulit_intervensi_selama" onchange="fillthis('kulit_intervensi_selama')" style="width:10%;">
          , integritas kulit dan jaringan meningkat (L.14125), dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit1" onclick="checkthis('kulit_krit1')" value="Elastisitas meningkat"><span class="lbl"> Elastisitas meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit2" onclick="checkthis('kulit_krit2')" value="Hidrasi meningkat"><span class="lbl"> Hidrasi meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit3" onclick="checkthis('kulit_krit3')" value="Perfusi jaringan meningkat"><span class="lbl"> Perfusi jaringan meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit4" onclick="checkthis('kulit_krit4')" value="Kerusakan jaringan menurun"><span class="lbl"> Kerusakan jaringan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit5" onclick="checkthis('kulit_krit5')" value="Kerusakan lapisan kulit menurun"><span class="lbl"> Kerusakan lapisan kulit menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit6" onclick="checkthis('kulit_krit6')" value="Nyeri menurun"><span class="lbl"> Nyeri menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit7" onclick="checkthis('kulit_krit7')" value="Perdarahan menurun"><span class="lbl"> Perdarahan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit8" onclick="checkthis('kulit_krit8')" value="Kemerahan menurun"><span class="lbl"> Kemerahan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit9" onclick="checkthis('kulit_krit9')" value="Hematoma menurun"><span class="lbl"> Hematoma menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit10" onclick="checkthis('kulit_krit10')" value="Pigmentasi upnormal menurun"><span class="lbl"> Pigmentasi upnormal menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit11" onclick="checkthis('kulit_krit11')" value="Jaringan parut menurun"><span class="lbl"> Jaringan parut menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit12" onclick="checkthis('kulit_krit12')" value="Nekrosis menurun"><span class="lbl"> Nekrosis menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit13" onclick="checkthis('kulit_krit13')" value="Abrasi kornea menurun"><span class="lbl"> Abrasi kornea menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit14" onclick="checkthis('kulit_krit14')" value="Suhu kulit membaik"><span class="lbl"> Suhu kulit membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit15" onclick="checkthis('kulit_krit15')" value="Tekstur membaik"><span class="lbl"> Tekstur membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kriteria_hasil][]" id="kulit_krit16" onclick="checkthis('kulit_krit16')" value="Pertumbuhan rambut membaik"><span class="lbl"> Pertumbuhan rambut membaik</span></label></div>
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
            <i>(Tidak ada)</i>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[mayor_objektif][]" id="kulit_mayor_obj1" onclick="checkthis('kulit_mayor_obj1')" value="Kerusakan jaringan dan/lapisan kulit"><span class="lbl"> Kerusakan jaringan dan/lapisan kulit</span></label></div>
          </div>
        </div>

        <hr>
        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <i>(Tidak ada)</i>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[minor_objektif][]" id="kulit_minor1" onclick="checkthis('kulit_minor1')" value="Nyeri"><span class="lbl"> Nyeri</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[minor_objektif][]" id="kulit_minor2" onclick="checkthis('kulit_minor2')" value="Perdarahan"><span class="lbl"> Perdarahan</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[minor_objektif][]" id="kulit_minor3" onclick="checkthis('kulit_minor3')" value="Kemerahan"><span class="lbl"> Kemerahan</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[minor_objektif][]" id="kulit_minor4" onclick="checkthis('kulit_minor4')" value="Hematoma"><span class="lbl"> Hematoma</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->


<!-- PERAWATAN INTEGRITAS KULIT & PERAWATAN LUKA -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>

    <!-- PERAWATAN INTEGRITAS KULIT -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Perawatan Integritas Kulit</b><br>
        <i>(Mengidentifikasi dan merawat kulit untuk menjaga keutuhan, kelembaban, dan mencegah perkembangan mikroorganisme)</i><br>
        <b>(I.11353)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_observasi][]" id="kulit_observasi1" onclick="checkthis('kulit_observasi1')" value="Identifikasi penyebab gangguan integritas kulit (mis: perubahan sirkulasi, perubahan status nutrisi, penurunan kelembaban, suhu lingkungan ekstrim, penurunan mobilitas)"><span class="lbl"> Identifikasi penyebab gangguan integritas kulit (mis: perubahan sirkulasi, perubahan status nutrisi, penurunan kelembaban, suhu lingkungan ekstrim, penurunan mobilitas)</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_terapeutik][]" id="kulit_terapeutik1" onclick="checkthis('kulit_terapeutik1')" value="Ubah posisi tiap dua jam jika tirah baring"><span class="lbl"> Ubah posisi tiap dua jam jika tirah baring</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_terapeutik][]" id="kulit_terapeutik2" onclick="checkthis('kulit_terapeutik2')" value="Lakukan pemijatan pada area penonjolan tulang"><span class="lbl"> Lakukan pemijatan pada area penonjolan tulang</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_terapeutik][]" id="kulit_terapeutik3" onclick="checkthis('kulit_terapeutik3')" value="Bersihkan perineal dengan air hangat selama periode diare"><span class="lbl"> Bersihkan perineal dengan air hangat selama periode diare</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_terapeutik][]" id="kulit_terapeutik4" onclick="checkthis('kulit_terapeutik4')" value="Gunakan produk berbahan petrolium atau minyak pada kulit kering"><span class="lbl"> Gunakan produk berbahan petrolium atau minyak pada kulit kering</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_terapeutik][]" id="kulit_terapeutik5" onclick="checkthis('kulit_terapeutik5')" value="Gunakan produk berbahan ringan"><span class="lbl"> Gunakan produk berbahan ringan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_terapeutik][]" id="kulit_terapeutik6" onclick="checkthis('kulit_terapeutik6')" value="Hindari produk berbahan dasar alkohol pada kulit kering"><span class="lbl"> Hindari produk berbahan dasar alkohol pada kulit kering</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_edukasi][]" id="kulit_edukasi1" onclick="checkthis('kulit_edukasi1')" value="Anjurkan menggunakan pelembab"><span class="lbl"> Anjurkan menggunakan pelembab</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_edukasi][]" id="kulit_edukasi2" onclick="checkthis('kulit_edukasi2')" value="Anjurkan minum air yang cukup"><span class="lbl"> Anjurkan minum air yang cukup</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_edukasi][]" id="kulit_edukasi3" onclick="checkthis('kulit_edukasi3')" value="Anjurkan meningkatkan nutrisi"><span class="lbl"> Anjurkan meningkatkan nutrisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_edukasi][]" id="kulit_edukasi4" onclick="checkthis('kulit_edukasi4')" value="Anjurkan meningkatkan asupan buah dan sayur"><span class="lbl"> Anjurkan meningkatkan asupan buah dan sayur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_edukasi][]" id="kulit_edukasi5" onclick="checkthis('kulit_edukasi5')" value="Anjurkan menghindari terpapar suhu ekstrim"><span class="lbl"> Anjurkan menghindari terpapar suhu ekstrim</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[kulit_edukasi][]" id="kulit_edukasi6" onclick="checkthis('kulit_edukasi6')" value="Anjurkan mandi dan menggunakan sabun"><span class="lbl"> Anjurkan mandi dan menggunakan sabun</span></label></div>
      </td>
    </tr>

    <!-- PERAWATAN LUKA -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Perawatan Luka</b><br>
        <i>(Mengidentifikasi dan meningkatkan penyembuhan luka serta mencegah terjadinya komplikasi luka)</i><br>
        <b>(I.14564)</b>
      </td>
    </tr>

    <!-- Observasi Luka -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_observasi][]" id="luka_observasi1" onclick="checkthis('luka_observasi1')" value="Monitor karakteristik luka (drainase, warna, ukuran, bau)"><span class="lbl"> Monitor karakteristik luka (drainase, warna, ukuran, bau)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_observasi][]" id="luka_observasi2" onclick="checkthis('luka_observasi2')" value="Monitor tanda-tanda infeksi"><span class="lbl"> Monitor tanda-tanda infeksi</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik Luka -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_terapeutik][]" id="luka_terapeutik1" onclick="checkthis('luka_terapeutik1')" value="Lepaskan balutan secara perlahan"><span class="lbl"> Lepaskan balutan secara perlahan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_terapeutik][]" id="luka_terapeutik2" onclick="checkthis('luka_terapeutik2')" value="Cukur rambut di sekitar luka"><span class="lbl"> Cukur rambut di sekitar luka</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_terapeutik][]" id="luka_terapeutik3" onclick="checkthis('luka_terapeutik3')" value="Bersihkan dengan cairan NaCl"><span class="lbl"> Bersihkan dengan cairan NaCl</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_terapeutik][]" id="luka_terapeutik4" onclick="checkthis('luka_terapeutik4')" value="Bersihkan jaringan nekrotik"><span class="lbl"> Bersihkan jaringan nekrotik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_terapeutik][]" id="luka_terapeutik5" onclick="checkthis('luka_terapeutik5')" value="Berikan salep yang sesuai ke kulit"><span class="lbl"> Berikan salep yang sesuai ke kulit</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_terapeutik][]" id="luka_terapeutik6" onclick="checkthis('luka_terapeutik6')" value="Pertahankan teknik steril saat melakukan perawatan luka"><span class="lbl"> Pertahankan teknik steril saat melakukan perawatan luka</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_terapeutik][]" id="luka_terapeutik7" onclick="checkthis('luka_terapeutik7')" value="Ganti balutan sesuai eksudat dan drainase"><span class="lbl"> Ganti balutan sesuai eksudat dan drainase</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_terapeutik][]" id="luka_terapeutik8" onclick="checkthis('luka_terapeutik8')" value="Ubah posisi setiap dua jam sesuai kondisi pasien"><span class="lbl"> Ubah posisi setiap dua jam sesuai kondisi pasien</span></label></div>
      </td>
    </tr>

    <!-- Edukasi Luka -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_edukasi][]" id="luka_edukasi1" onclick="checkthis('luka_edukasi1')" value="Jelaskan tanda dan gejala infeksi"><span class="lbl"> Jelaskan tanda dan gejala infeksi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_edukasi][]" id="luka_edukasi2" onclick="checkthis('luka_edukasi2')" value="Anjurkan mengkonsumsi makanan tinggi kalori dan protein"><span class="lbl"> Anjurkan mengkonsumsi makanan tinggi kalori dan protein</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_edukasi][]" id="luka_edukasi3" onclick="checkthis('luka_edukasi3')" value="Anjurkan prosedur perawatan luka secara mandiri"><span class="lbl"> Anjurkan prosedur perawatan luka secara mandiri</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi Luka -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_kolaborasi][]" id="luka_kolaborasi1" onclick="checkthis('luka_kolaborasi1')" value="Kolaborasi pemberian antibiotik"><span class="lbl"> Kolaborasi pemberian antibiotik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_100[luka_kolaborasi][]" id="luka_kolaborasi2" onclick="checkthis('luka_kolaborasi2')" value="Kolaborasi prosedur debridement"><span class="lbl"> Kolaborasi prosedur debridement</span></label></div>
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
        <input type="text" class="input_type" name="form_100[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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