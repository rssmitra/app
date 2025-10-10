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
      var hiddenInputName = 'form_97[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: GANGGUAN CITRA TUBUH</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Perubahan persepsi tentang penampilan, struktur dan fungsi fisik individu
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[penyebab][]" id="gt_penyebab1" onclick="checkthis('gt_penyebab1')" value="Perubahan struktur/ bentuk tubuh (mis. Amputasi, trauma, luka bakar, obesitas, jerawat)"><span class="lbl"> Perubahan struktur/ bentuk tubuh (mis. Amputasi, trauma, luka bakar, obesitas, jerawat)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[penyebab][]" id="gt_penyebab2" onclick="checkthis('gt_penyebab2')" value="Perubahan fungsi tubuh (mis. Proses penyakit, kehamilan, kelumpuhan)"><span class="lbl"> Perubahan fungsi tubuh (mis. Proses penyakit, kehamilan, kelumpuhan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[penyebab][]" id="gt_penyebab3" onclick="checkthis('gt_penyebab3')" value="Perubahan fungsi kognitif"><span class="lbl"> Perubahan fungsi kognitif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[penyebab][]" id="gt_penyebab4" onclick="checkthis('gt_penyebab4')" value="Ketidaksesuaian budaya, keyakinan atau sistem nilai"><span class="lbl"> Ketidaksesuaian budaya, keyakinan atau sistem nilai</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[penyebab][]" id="gt_penyebab5" onclick="checkthis('gt_penyebab5')" value="Transisi perkembangan"><span class="lbl"> Transisi perkembangan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[penyebab][]" id="gt_penyebab6" onclick="checkthis('gt_penyebab6')" value="Gangguan psikososial"><span class="lbl"> Gangguan psikososial</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[penyebab][]" id="gt_penyebab7" onclick="checkthis('gt_penyebab7')" value="Efek tindakan/pengobatan (mis. Pembedahan, kemoterapi, terapi radiasi)"><span class="lbl"> Efek tindakan/pengobatan (mis. Pembedahan, kemoterapi, terapi radiasi)</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_97[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Citra Tubuh meningkat (L.09067) dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[kriteria_hasil][]" id="gt_krit1" onclick="checkthis('gt_krit1')" value="Melihat bagian tubuh meningkat"><span class="lbl"> Melihat bagian tubuh meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[kriteria_hasil][]" id="gt_krit2" onclick="checkthis('gt_krit2')" value="Menyentuh bagian tubuh meningkat"><span class="lbl"> Menyentuh bagian tubuh meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[kriteria_hasil][]" id="gt_krit3" onclick="checkthis('gt_krit3')" value="Verbalisasi kecacatan bagian tubuh meningkat"><span class="lbl"> Verbalisasi kecacatan bagian tubuh meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[kriteria_hasil][]" id="gt_krit4" onclick="checkthis('gt_krit4')" value="Verbalisasi kehilangan bagian tubuh meningkat"><span class="lbl"> Verbalisasi kehilangan bagian tubuh meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[kriteria_hasil][]" id="gt_krit5" onclick="checkthis('gt_krit5')" value="Verbalisasi perasaan negatif tentang perubahan tubuh menurun"><span class="lbl"> Verbalisasi perasaan negatif tentang perubahan tubuh menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[kriteria_hasil][]" id="gt_krit6" onclick="checkthis('gt_krit6')" value="Verbalisasi kekhawatiran pada penolakan/reaksi orang lain menurun"><span class="lbl"> Verbalisasi kekhawatiran pada penolakan/reaksi orang lain menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[kriteria_hasil][]" id="gt_krit7" onclick="checkthis('gt_krit7')" value="Verbalisasi perubahan gaya hidup menurun"><span class="lbl"> Verbalisasi perubahan gaya hidup menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[kriteria_hasil][]" id="gt_krit8" onclick="checkthis('gt_krit8')" value="Menyembunyikan bagian tubuh berlebihan menurun"><span class="lbl"> Menyembunyikan bagian tubuh berlebihan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[kriteria_hasil][]" id="gt_krit9" onclick="checkthis('gt_krit9')" value="Menunjukkan bagian tubuh berlebihan menurun"><span class="lbl"> Menunjukkan bagian tubuh berlebihan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[kriteria_hasil][]" id="gt_krit10" onclick="checkthis('gt_krit10')" value="Fokus pada bagian tubuh menurun"><span class="lbl"> Fokus pada bagian tubuh menurun</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[mayor_subjektif][]" id="gt_mayor_sub1" onclick="checkthis('gt_mayor_sub1')" value="Mengungkapkan kecacatan/kehilangan bagian tubuh"><span class="lbl"> Mengungkapkan kecacatan/kehilangan bagian tubuh</span></label></div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[mayor_objektif][]" id="gt_mayor_obj1" onclick="checkthis('gt_mayor_obj1')" value="Kehilangan bagian tubuh"><span class="lbl"> Kehilangan bagian tubuh</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[mayor_objektif][]" id="gt_mayor_obj2" onclick="checkthis('gt_mayor_obj2')" value="Fungsi/struktur tubuh berubah/hilang"><span class="lbl"> Fungsi/struktur tubuh berubah/hilang</span></label></div>
          </div>
        </div>

        <hr>
        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[minor_subjektif][]" id="gt_minor_sub1" onclick="checkthis('gt_minor_sub1')" value="Tidak mau mengungkapkan kecacatan/kehilangan bagian tubuh"><span class="lbl"> Tidak mau mengungkapkan kecacatan/kehilangan bagian tubuh</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[minor_subjektif][]" id="gt_minor_sub2" onclick="checkthis('gt_minor_sub2')" value="Mengungkapkan perasaan negatif tentang perubahan tubuh"><span class="lbl"> Mengungkapkan perasaan negatif tentang perubahan tubuh</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[minor_subjektif][]" id="gt_minor_sub3" onclick="checkthis('gt_minor_sub3')" value="Mengungkapkan kekhawatiran pada penolakan/reaksi orang lain"><span class="lbl"> Mengungkapkan kekhawatiran pada penolakan/reaksi orang lain</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[minor_subjektif][]" id="gt_minor_sub4" onclick="checkthis('gt_minor_sub4')" value="Mengungkapkan perubahan gaya hidup"><span class="lbl"> Mengungkapkan perubahan gaya hidup</span></label></div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[minor_objektif][]" id="gt_minor_obj1" onclick="checkthis('gt_minor_obj1')" value="Menyembunyikan/menunjukkan bagian tubuh secara berlebihan"><span class="lbl"> Menyembunyikan/menunjukkan bagian tubuh secara berlebihan</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[minor_objektif][]" id="gt_minor_obj2" onclick="checkthis('gt_minor_obj2')" value="Menghindari melihat dan/atau menyentuh bagian tubuh"><span class="lbl"> Menghindari melihat dan/atau menyentuh bagian tubuh</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[minor_objektif][]" id="gt_minor_obj3" onclick="checkthis('gt_minor_obj3')" value="Fokus berlebihan pada perubahan tubuh"><span class="lbl"> Fokus berlebihan pada perubahan tubuh</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[minor_objektif][]" id="gt_minor_obj4" onclick="checkthis('gt_minor_obj4')" value="Respon nonverbal pada perubahan dan persepsi tubuh"><span class="lbl"> Respon nonverbal pada perubahan dan persepsi tubuh</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[minor_objektif][]" id="gt_minor_obj5" onclick="checkthis('gt_minor_obj5')" value="Fokus pada penampilan dan kekuatan masa lalu"><span class="lbl"> Fokus pada penampilan dan kekuatan masa lalu</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[minor_objektif][]" id="gt_minor_obj6" onclick="checkthis('gt_minor_obj6')" value="Hubungan sosial berubah"><span class="lbl"> Hubungan sosial berubah</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->




<!-- PROMOSI CITRA TUBUH -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Promosi Citra Tubuh -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Promosi Citra Tubuh</b><br>
        <i>(Meningkatkan perbaikan perubahan persepsi terhadap fisik pasien)</i><br>
        <b>(I.09305)</b>
      </td>
    </tr>

    <!-- Tindakan -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Tindakan</b><br>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>1</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_observasi][]" id="pct_observasi1" onclick="checkthis('pct_observasi1')" value="Identifikasi harapan citra tubuh berdasarkan tahapan perkembangan"><span class="lbl"> Identifikasi harapan citra tubuh berdasarkan tahapan perkembangan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_observasi][]" id="pct_observasi2" onclick="checkthis('pct_observasi2')" value="Identifikasi budaya, agama, jenis kelamin dan umur terkait citra tubuh"><span class="lbl"> Identifikasi budaya, agama, jenis kelamin dan umur terkait citra tubuh</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_observasi][]" id="pct_observasi3" onclick="checkthis('pct_observasi3')" value="Identifikasi perubahan citra tubuh yang mengakibatkan isolasi sosial"><span class="lbl"> Identifikasi perubahan citra tubuh yang mengakibatkan isolasi sosial</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_observasi][]" id="pct_observasi4" onclick="checkthis('pct_observasi4')" value="Monitor frekuensi pernyataan kritik terhadap diri sendiri"><span class="lbl"> Monitor frekuensi pernyataan kritik terhadap diri sendiri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_observasi][]" id="pct_observasi5" onclick="checkthis('pct_observasi5')" value="Monitor apakah pasien bisa melihat bagian tubuh yang berubah"><span class="lbl"> Monitor apakah pasien bisa melihat bagian tubuh yang berubah</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>2</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_terapeutik][]" id="pct_terapeutik1" onclick="checkthis('pct_terapeutik1')" value="Diskusikan perubahan tubuh dan fungsinya"><span class="lbl"> Diskusikan perubahan tubuh dan fungsinya</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_terapeutik][]" id="pct_terapeutik2" onclick="checkthis('pct_terapeutik2')" value="Diskusikan perbedaan penampilan fisik terhadap harga diri"><span class="lbl"> Diskusikan perbedaan penampilan fisik terhadap harga diri</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_terapeutik][]" id="pct_terapeutik3" onclick="checkthis('pct_terapeutik3')" value="Diskusikan perubahan akibat pubertas, kehamilan dan penuaan"><span class="lbl"> Diskusikan perubahan akibat pubertas, kehamilan dan penuaan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_terapeutik][]" id="pct_terapeutik4" onclick="checkthis('pct_terapeutik4')" value="Diskusikan kondisi stres yang mempengaruhi citra tubuh (mis. luka, penyakit, pembedahan)"><span class="lbl"> Diskusikan kondisi stres yang mempengaruhi citra tubuh (mis. luka, penyakit, pembedahan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_terapeutik][]" id="pct_terapeutik5" onclick="checkthis('pct_terapeutik5')" value="Diskusikan cara pengembangan harapan citra tubuh secara realistis"><span class="lbl"> Diskusikan cara pengembangan harapan citra tubuh secara realistis</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_terapeutik][]" id="pct_terapeutik6" onclick="checkthis('pct_terapeutik6')" value="Diskusikan persepsi pasien dan keluarga tentang perubahan citra tubuh"><span class="lbl"> Diskusikan persepsi pasien dan keluarga tentang perubahan citra tubuh</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>3</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_edukasi][]" id="pct_edukasi1" onclick="checkthis('pct_edukasi1')" value="Jelaskan kepada keluarga tentang perawatan perubahan citra tubuh"><span class="lbl"> Jelaskan kepada keluarga tentang perawatan perubahan citra tubuh</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_edukasi][]" id="pct_edukasi2" onclick="checkthis('pct_edukasi2')" value="Anjurkan mengungkapkan gambaran diri terhadap citra tubuh"><span class="lbl"> Anjurkan mengungkapkan gambaran diri terhadap citra tubuh</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_edukasi][]" id="pct_edukasi3" onclick="checkthis('pct_edukasi3')" value="Anjurkan menggunakan alat bantu (mis. pakaian, wig, kosmetik)"><span class="lbl"> Anjurkan menggunakan alat bantu (mis. pakaian, wig, kosmetik)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_edukasi][]" id="pct_edukasi4" onclick="checkthis('pct_edukasi4')" value="Anjurkan mengikuti kelompok pendukung (mis. kelompok sebaya)"><span class="lbl"> Anjurkan mengikuti kelompok pendukung (mis. kelompok sebaya)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_edukasi][]" id="pct_edukasi5" onclick="checkthis('pct_edukasi5')" value="Latih fungsi tubuh yang dimiliki"><span class="lbl"> Latih fungsi tubuh yang dimiliki</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_edukasi][]" id="pct_edukasi6" onclick="checkthis('pct_edukasi6')" value="Latih peningkatan penampilan diri (mis. berdandan)"><span class="lbl"> Latih peningkatan penampilan diri (mis. berdandan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_97[pct_edukasi][]" id="pct_edukasi7" onclick="checkthis('pct_edukasi7')" value="Latih pengungkapan kemampuan diri kepada orang lain maupun kelompok"><span class="lbl"> Latih pengungkapan kemampuan diri kepada orang lain maupun kelompok</span></label></div>
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
        <input type="text" class="input_type" name="form_97[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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