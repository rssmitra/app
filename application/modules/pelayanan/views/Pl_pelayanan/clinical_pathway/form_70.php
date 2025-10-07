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
      var hiddenInputName = 'form_70[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 29 september 2025</p> -->
<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN:<br> GANGGUAN SIRKULASI SPONTAN</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
        Definisi : Ketidakmampuan untuk mempertahankan sirkulasi yang adekuat untuk menunjang kehidupan
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_70[penyebab][]" id="penyebab_kelistrikan" onclick="checkthis('penyebab_kelistrikan')" value="Abnormalitas kelistrikan jantung"><span class="lbl"> Abnormalitas kelistrikan jantung</span></label>
        </div>

        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_70[penyebab][]" id="penyebab_struktur" onclick="checkthis('penyebab_struktur')" value="Abnormalitas struktur jantung"><span class="lbl"> Abnormalitas struktur jantung</span></label>
        </div>

        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_70[penyebab][]" id="penyebab_ventrikel" onclick="checkthis('penyebab_ventrikel')" value="Penurunan fungsi ventrikel"><span class="lbl"> Penurunan fungsi ventrikel</span></label>
        </div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_70[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;"> ,
          sirkulasi spontan meningkat (L.02015) dengan kriteria hasil:</b>

        <div style="display: flex; flex-wrap: wrap; margin-top: 5px;">
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[kriteria_hasil][]" id="hasil_kesadaran" onclick="checkthis('hasil_kesadaran')" value="Tingkat kesadaran meningkat"><span class="lbl"> Tingkat kesadaran meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[kriteria_hasil][]" id="hasil_td" onclick="checkthis('hasil_td')" value="TD membaik"><span class="lbl"> TD membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[kriteria_hasil][]" id="hasil_saturasi" onclick="checkthis('hasil_saturasi')" value="Saturasi oksigen meningkat"><span class="lbl"> Saturasi oksigen meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[kriteria_hasil][]" id="hasil_ekg" onclick="checkthis('hasil_ekg')" value="Gambaran EKG aritmia menurun"><span class="lbl"> Gambaran EKG aritmia menurun</span></label></div>
          </div>

          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[kriteria_hasil][]" id="hasil_nadi" onclick="checkthis('hasil_nadi')" value="Frekuensi nadi membaik"><span class="lbl"> Frekuensi nadi membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[kriteria_hasil][]" id="hasil_nafas" onclick="checkthis('hasil_nafas')" value="Frekuensi nafas membaik"><span class="lbl"> Frekuensi nafas membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[kriteria_hasil][]" id="hasil_suhu" onclick="checkthis('hasil_suhu')" value="Suhu tubuh membaik"><span class="lbl"> Suhu tubuh membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[kriteria_hasil][]" id="hasil_urine" onclick="checkthis('hasil_urine')" value="Produksi urine membaik"><span class="lbl"> Produksi urine membaik</span></label></div>
          </div>
        </div>
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
            Tidak berespon
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[mayor_objektif][]" id="mayor_nadi" onclick="checkthis('mayor_nadi')" value="Frekuensi nadi <50x/mnt atau >150x/mnt"><span class="lbl"> Frekuensi nadi &lt;50x/mnt atau &gt;150x/mnt</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[mayor_objektif][]" id="mayor_td" onclick="checkthis('mayor_td')" value="TD Sistolik <60 mmHg atau >200 mmHg"><span class="lbl"> TD Sistolik &lt;60 mmHg atau &gt;200 mmHg</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[mayor_objektif][]" id="mayor_nafas" onclick="checkthis('mayor_nafas')" value="Frekuensi nafas <6x/mnt atau >30x/mnt"><span class="lbl"> Frekuensi nafas &lt;6x/mnt atau &gt;30x/mnt</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[mayor_objektif][]" id="mayor_kesadaran" onclick="checkthis('mayor_kesadaran')" value="Kesadaran menurun atau tidak sadar"><span class="lbl"> Kesadaran menurun atau tidak sadar</span></label></div>
          </div>
        </div>

        <hr>

        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            (Tidak ada)
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[minor_objektif][]" id="minor_suhu" onclick="checkthis('minor_suhu')" value="Suhu tubuh <34.5oC"><span class="lbl"> Suhu tubuh &lt;34.5°C</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[minor_objektif][]" id="minor_urine" onclick="checkthis('minor_urine')" value="Tidak ada produksi urine dalam 6 jam"><span class="lbl"> Tidak ada produksi urine dalam 6 jam</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[minor_objektif][]" id="minor_saturasi" onclick="checkthis('minor_saturasi')" value="Saturasi oksigen <85%"><span class="lbl"> Saturasi oksigen &lt;85%</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[minor_objektif][]" id="minor_ekg_letal" onclick="checkthis('minor_ekg_letal')" value="Gambaran EKG menunjukkan aritmia letal (VT,VF, asistole, PEA)"><span class="lbl"> Gambaran EKG menunjukkan aritmia letal (VT,VF, asistole, PEA)</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[minor_objektif][]" id="minor_ekg_mayor" onclick="checkthis('minor_ekg_mayor')" value="Gambaran EKG menunjukkan aritmia mayor (Avblok derajat 2 tipe 2, Avblok total, takiaritmia/bradiaritmia, SVT, VES simptomatik)"><span class="lbl"> Gambaran EKG menunjukkan aritmia mayor (Avblok derajat 2 tipe 2, Avblok total, takiaritmia/bradiaritmia, SVT, VES simptomatik)</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[minor_objektif][]" id="minor_etco2" onclick="checkthis('minor_etco2')" value="ETCO2 <35 mmHg"><span class="lbl"> ETCO2 &lt;35 mmHg</span></label></div>
          </div>
        </div>
      </td>
    </tr>
    </tbody>
</table>
<br>
<!-- END -->

<!-- MANAJEMEN DEFIBRILASI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <tbody>

  <tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <!-- <table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;"> -->
      <thead>
        <tr style="background-color: #d3d3d3;">
          <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
          <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
        </tr>
      </thead>
      <tbody>

  <tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Manajemen Defibrilasi</b>
        <i>(Mengidentifikasi dan mengelola aliran listrik kuat dengan metode asinkrone ke jantung melalui elektroda yang ditempatkan pada permukaan dada)</i>
        <b>(I.02038)</b>
  </td>
  </tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>TINDAKAN</b>
      </td>
    </tr>

    <!-- OBSERVASI -->
    <tr>
      <td style="width:5%; text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_70[defibrilasi_observasi][]" id="defibrilasi_observasi_1" onclick="checkthis('defibrilasi_observasi_1')" value="Periksa irama pada monitor setelah RJP 2 menit">
          <span class="lbl"> Periksa irama pada monitor setelah RJP 2 menit</span>
        </label></div>
      </td>
    </tr>

    <!-- TERAPEUTIK -->
    <tr>
      <td style="width:5%; text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_1" onclick="checkthis('defibrilasi_terapeutik_1')" value="Lakukan RJP hingga mesin defibrilator siap"><span class="lbl"> Lakukan RJP hingga mesin defibrilator siap</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_2" onclick="checkthis('defibrilasi_terapeutik_2')" value="Siapkan dan hidupkan mesin defibrilator"><span class="lbl"> Siapkan dan hidupkan mesin defibrilator</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_3" onclick="checkthis('defibrilasi_terapeutik_3')" value="Pasang monitor EKG"><span class="lbl"> Pasang monitor EKG</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_4" onclick="checkthis('defibrilasi_terapeutik_4')" value="Pastikan irama EKG henti jantung"><span class="lbl"> Pastikan irama EKG henti jantung (VF atau VT tanpa nadi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_5" onclick="checkthis('defibrilasi_terapeutik_5')" value="Atur jumlah energi defibrilasi"><span class="lbl"> Atur jumlah energi dengan mode asyncronized (360 Joule monophasic / 120-200 Joule biphasic)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_6" onclick="checkthis('defibrilasi_terapeutik_6')" value="Angkat paddle dan oleskan jelly"><span class="lbl"> Angkat paddle dari mesin dan oleskan jelly pada paddle</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_7" onclick="checkthis('defibrilasi_terapeutik_7')" value="Tempelkan paddle sternum dan apeks"><span class="lbl"> Tempelkan paddle sternum (kanan) dan apeks (kiri) sesuai posisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_8" onclick="checkthis('defibrilasi_terapeutik_8')" value="Isi energi defibrilator"><span class="lbl"> Isi energi dengan menekan tombol charge pada paddle hingga energi tercapai</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_9" onclick="checkthis('defibrilasi_terapeutik_9')" value="Hentikan RJP saat defibrilator siap"><span class="lbl"> Hentikan RJP saat defibrilator siap</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_10" onclick="checkthis('defibrilasi_terapeutik_10')" value="Teriak defibrilator siap"><span class="lbl"> Teriak bahwa defibrilator siap ("I'm clear, you're clear, everybody's clear")</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_11" onclick="checkthis('defibrilasi_terapeutik_11')" value="Berikan shock defibrilasi"><span class="lbl"> Berikan shock dengan menekan tombol pada kedua paddle bersamaan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_12" onclick="checkthis('defibrilasi_terapeutik_12')" value="Lanjutkan RJP setelah defibrilasi"><span class="lbl"> Angkat paddle dan lanjutkan RJP tanpa menunggu hasil monitor</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[defibrilasi_terapeutik][]" id="defibrilasi_terapeutik_13" onclick="checkthis('defibrilasi_terapeutik_13')" value="Lanjutkan RJP 2 menit"><span class="lbl"> Lanjutkan RJP sampai 2 menit</span></label></div>
      </td>
    </tr>

    <tr>
  <td colspan="2" style="border:1px solid black; padding:5px;">
    <b>Resusitasi Jantung Paru</b>
    <i>(Memberikan pertolongan pertama pada kondisi henti nafas dan henti jantung dengan teknik kombinasi kompresi pada dada dan bantuan nafas)</i>
    <b>(I.02083)</b>
  </td>
</tr>
<!-- <tr>
  <td colspan="2" style="border:1px solid black; padding:5px;"><b>TINDAKAN</b></td>
</tr> -->

<!-- Observasi -->
<tr>
  <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
  <td style="border:1px solid black; padding:5px; vertical-align:top;">
    <b>Observasi</b><br>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_observasi][]" id="rjp_observasi_1" onclick="checkthis('rjp_observasi_1')" value="Identifikasi keamanan penolong, lingkungan dan pasien"><span class="lbl"> Identifikasi keamanan penolong, lingkungan dan pasien</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_observasi][]" id="rjp_observasi_2" onclick="checkthis('rjp_observasi_2')" value="Identifikasi respon pasien"><span class="lbl"> Identifikasi respon pasien (mis: memanggil, menepuk bahu)</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_observasi][]" id="rjp_observasi_3" onclick="checkthis('rjp_observasi_3')" value="Monitor nadi karotis dan nafas setiap 2 menit"><span class="lbl"> Monitor nadi karotis dan nafas setiap 2 menit atau 5 siklus RJP</span></label></div>
  </td>
</tr>

<!-- Terapeutik -->
<tr>
  <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
  <td style="border:1px solid black; padding:5px; vertical-align:top;">
    <b>Terapeutik</b><br>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_1" onclick="checkthis('rjp_terapeutik_1')" value="Pakai alat pelindung diri"><span class="lbl"> Pakai alat pelindung diri (mis: sarung tangan)</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_2" onclick="checkthis('rjp_terapeutik_2')" value="Aktifkan Emergensi Medical System"><span class="lbl"> Aktifkan Emergensi Medical System / Code Blue</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_3" onclick="checkthis('rjp_terapeutik_3')" value="Posisikan pasien terlentang"><span class="lbl"> Posisikan pasien terlentang</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_4" onclick="checkthis('rjp_terapeutik_4')" value="Atur posisi penolong"><span class="lbl"> Atur posisi penolong</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_5" onclick="checkthis('rjp_terapeutik_5')" value="Raba nadi karotis <10 detik"><span class="lbl"> Raba nadi karotis dalam waktu &lt;10 detik</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_6" onclick="checkthis('rjp_terapeutik_6')" value="Rescue breathing"><span class="lbl"> Berikan rescue breathing bila ada nadi tetapi tidak ada nafas/gasping</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_7" onclick="checkthis('rjp_terapeutik_7')" value="Kompresi dada 30:2"><span class="lbl"> Kompresi dada 30x + ventilasi 2x bila tidak ada nadi & nafas</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_8" onclick="checkthis('rjp_terapeutik_8')" value="Kompresi dengan tumit telapak tangan"><span class="lbl"> Kompresi dengan tumit telapak tangan, tegak lurus dada</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_9" onclick="checkthis('rjp_terapeutik_9')" value="Kompresi 5-6 cm 100-120x/mnt"><span class="lbl"> Kompresi kedalaman 5–6 cm dengan kecepatan 100–120x/mnt</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_10" onclick="checkthis('rjp_terapeutik_10')" value="Buka jalan nafas"><span class="lbl"> Buka jalan nafas (head tilt–chin lift / jaw thrust bila curiga cervical)</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_11" onclick="checkthis('rjp_terapeutik_11')" value="Ventilasi bag valve mask"><span class="lbl"> Ventilasi dengan bag valve mask teknik EC-Clamp</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_12" onclick="checkthis('rjp_terapeutik_12')" value="Kombinasi kompresi dan ventilasi 2 menit"><span class="lbl"> Kombinasi kompresi + ventilasi selama 2 menit / 5 siklus</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_terapeutik][]" id="rjp_terapeutik_13" onclick="checkthis('rjp_terapeutik_13')" value="Hentikan RJP sesuai indikasi"><span class="lbl"> Hentikan RJP bila ada tanda kehidupan, penolong lebih mahir datang, atau DNR</span></label></div>
  </td>
</tr>

<!-- Edukasi -->
<tr>
  <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
  <td style="border:1px solid black; padding:5px; vertical-align:top;">
    <b>Edukasi</b><br>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_edukasi][]" id="rjp_edukasi_1" onclick="checkthis('rjp_edukasi_1')" value="Jelaskan prosedur kepada keluarga"><span class="lbl"> Jelaskan tujuan & prosedur kepada keluarga/pengantar pasien</span></label></div>
  </td>
</tr>

<!-- Kolaborasi -->
<tr>
  <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
  <td style="border:1px solid black; padding:5px; vertical-align:top;">
    <b>Kolaborasi</b><br>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[rjp_kolaborasi][]" id="rjp_kolaborasi_1" onclick="checkthis('rjp_kolaborasi_1')" value="Kolaborasi tim medis"><span class="lbl"> Kolaborasi tim medis untuk bantuan hidup lanjutan</span></label></div>
  </td>
</tr>

<tr>
  <td colspan="2" style="border:1px solid black; padding:5px;">
    <b>Resusitasi Cairan</b>
    <i>(Memberikan cairan intra vena dengan cepat sesuai indikasi)</i>
    <b>(I.03139)</b>
  </td>
</tr>
<!-- <tr>
  <td colspan="2" style="border:1px solid black; padding:5px;"><b>TINDAKAN</b></td>
</tr> -->

<!-- Observasi -->
<tr>
  <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
  <td style="border:1px solid black; padding:5px; vertical-align:top;">
    <b>Observasi</b><br>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_observasi][]" id="cairan_observasi_1" onclick="checkthis('cairan_observasi_1')" value="Identifikasi kelas syok"><span class="lbl"> Identifikasi kelas syok untuk estimasi kehilangan cairan</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_observasi][]" id="cairan_observasi_2" onclick="checkthis('cairan_observasi_2')" value="Monitor status hemodinamik"><span class="lbl"> Monitor status hemodinamik</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_observasi][]" id="cairan_observasi_3" onclick="checkthis('cairan_observasi_3')" value="Monitor status oksigen"><span class="lbl"> Monitor status oksigen</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_observasi][]" id="cairan_observasi_4" onclick="checkthis('cairan_observasi_4')" value="Monitor kelebihan cairan"><span class="lbl"> Monitor kelebihan cairan</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_observasi][]" id="cairan_observasi_5" onclick="checkthis('cairan_observasi_5')" value="Monitor output cairan tubuh"><span class="lbl"> Monitor output cairan tubuh</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_observasi][]" id="cairan_observasi_6" onclick="checkthis('cairan_observasi_6')" value="Monitor nilai lab cairan"><span class="lbl"> Monitor nilai BUN, kreatinin, protein total, albumin</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_observasi][]" id="cairan_observasi_7" onclick="checkthis('cairan_observasi_7')" value="Monitor edema paru"><span class="lbl"> Monitor tanda & gejala edema paru</span></label></div>
  </td>
</tr>

<!-- Terapeutik -->
<tr>
  <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
  <td style="border:1px solid black; padding:5px; vertical-align:top;">
    <b>Terapeutik</b><br>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_terapeutik][]" id="cairan_terapeutik_1" onclick="checkthis('cairan_terapeutik_1')" value="Pasang jalur IV besar"><span class="lbl"> Pasang jalur IV berukuran besar</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_terapeutik][]" id="cairan_terapeutik_2" onclick="checkthis('cairan_terapeutik_2')" value="Infus kristaloid dewasa"><span class="lbl"> Berikan infus cairan kristaloid 1–2 L pada dewasa</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_terapeutik][]" id="cairan_terapeutik_3" onclick="checkthis('cairan_terapeutik_3')" value="Infus kristaloid anak"><span class="lbl"> Berikan cairan kristaloid 20 ml/kgBB pada anak</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_terapeutik][]" id="cairan_terapeutik_4" onclick="checkthis('cairan_terapeutik_4')" value="Cross matching darah"><span class="lbl"> Lakukan cross matching produk darah</span></label></div>
  </td>
</tr>

<!-- Kolaborasi -->
<tr>
  <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
  <td style="border:1px solid black; padding:5px; vertical-align:top;">
    <b>Kolaborasi</b><br>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_kolaborasi][]" id="cairan_kolaborasi_1" onclick="checkthis('cairan_kolaborasi_1')" value="Kolaborasi jenis & jumlah cairan"><span class="lbl"> Kolaborasi penentuan jenis & jumlah cairan</span></label></div>
    <div class="checkbox"><label><input type="checkbox" class="ace" name="form_70[cairan_kolaborasi][]" id="cairan_kolaborasi_2" onclick="checkthis('cairan_kolaborasi_2')" value="Kolaborasi pemberian produk darah"><span class="lbl"> Kolaborasi pemberian produk darah</span></label></div>
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
        <input type="text" class="input_type" name="form_70[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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