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
      var hiddenInputName = 'form_108[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: GANGGUAN ELIMINASI URIN</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Disfungsi eliminasi urin.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[penyebab][]" id="urin_penyebab1" onclick="checkthis('urin_penyebab1')" value="Penurunan kapasitas kandung kemih"><span class="lbl"> Penurunan kapasitas kandung kemih</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[penyebab][]" id="urin_penyebab2" onclick="checkthis('urin_penyebab2')" value="Iritasi kandung kemih"><span class="lbl"> Iritasi kandung kemih</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[penyebab][]" id="urin_penyebab3" onclick="checkthis('urin_penyebab3')" value="Penurunan kemampuan menyadari tanda-tanda gangguan kandung kemih"><span class="lbl"> Penurunan kemampuan menyadari tanda-tanda gangguan kandung kemih</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[penyebab][]" id="urin_penyebab4" onclick="checkthis('urin_penyebab4')" value="Efek tindakan medis dan diagnostik"><span class="lbl"> Efek tindakan medis dan diagnostik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[penyebab][]" id="urin_penyebab5" onclick="checkthis('urin_penyebab5')" value="Kelemahan otot pelvis"><span class="lbl"> Kelemahan otot pelvis</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[penyebab][]" id="urin_penyebab6" onclick="checkthis('urin_penyebab6')" value="Ketidakmampuan mengakses toilet (mis: Imobilisasi)"><span class="lbl"> Ketidakmampuan mengakses toilet (mis: Imobilisasi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[penyebab][]" id="urin_penyebab7" onclick="checkthis('urin_penyebab7')" value="Hambatan lingkungan"><span class="lbl"> Hambatan lingkungan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[penyebab][]" id="urin_penyebab8" onclick="checkthis('urin_penyebab8')" value="Ketidakmampuan mengkomunikasikan kebutuhan eliminasi"><span class="lbl"> Ketidakmampuan mengkomunikasikan kebutuhan eliminasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[penyebab][]" id="urin_penyebab9" onclick="checkthis('urin_penyebab9')" value="Outlet kandung kemih tidak lengkap"><span class="lbl"> Outlet kandung kemih tidak lengkap</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[penyebab][]" id="urin_penyebab10" onclick="checkthis('urin_penyebab10')" value="Imaturitas (pada anak usia < 3 tahun)"><span class="lbl"> Imaturitas (pada anak usia < 3 tahun)</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_108[urin_intervensi_selama]" id="urin_intervensi_selama" onchange="fillthis('urin_intervensi_selama')" style="width:10%;">,
          eliminasi urine membaik (L.04034), dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit1" onclick="checkthis('urin_krit1')" value="Sensasi berkemih meningkat"><span class="lbl"> Sensasi berkemih meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit2" onclick="checkthis('urin_krit2')" value="Desakan berkemih menurun"><span class="lbl"> Desakan berkemih menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit3" onclick="checkthis('urin_krit3')" value="Distensi kandung kemih menurun"><span class="lbl"> Distensi kandung kemih menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit4" onclick="checkthis('urin_krit4')" value="Berkemih tidak tuntas menurun"><span class="lbl"> Berkemih tidak tuntas menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit5" onclick="checkthis('urin_krit5')" value="Volume residu urin menurun"><span class="lbl"> Volume residu urin menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit6" onclick="checkthis('urin_krit6')" value="Urin menetes menurun"><span class="lbl"> Urin menetes menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit7" onclick="checkthis('urin_krit7')" value="Nocturia menurun"><span class="lbl"> Nocturia menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit8" onclick="checkthis('urin_krit8')" value="Mengompol menurun"><span class="lbl"> Mengompol menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit9" onclick="checkthis('urin_krit9')" value="Enuresis menurun"><span class="lbl"> Enuresis menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit10" onclick="checkthis('urin_krit10')" value="Disuria menurun"><span class="lbl"> Disuria menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit11" onclick="checkthis('urin_krit11')" value="Anuria menurun"><span class="lbl"> Anuria menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit12" onclick="checkthis('urin_krit12')" value="Frekuensi BAK membaik"><span class="lbl"> Frekuensi BAK membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[kriteria_hasil][]" id="urin_krit13" onclick="checkthis('urin_krit13')" value="Karakteristik urin membaik"><span class="lbl"> Karakteristik urin membaik</span></label></div>
      </td>
    </tr>

    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Dibuktikan dengan:</b><br>

        <p><b>Tanda dan Gejala Mayor:</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif :</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[mayor_subjektif][]" id="urin_mayor_sub1" onclick="checkthis('urin_mayor_sub1')" value="Desakan berkemih (urgensi)"><span class="lbl"> Desakan berkemih (urgensi)</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[mayor_subjektif][]" id="urin_mayor_sub2" onclick="checkthis('urin_mayor_sub2')" value="Urine menetes (dribbling)"><span class="lbl"> Urine menetes (dribbling)</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[mayor_subjektif][]" id="urin_mayor_sub3" onclick="checkthis('urin_mayor_sub3')" value="Sering BAK"><span class="lbl"> Sering BAK</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[mayor_subjektif][]" id="urin_mayor_sub4" onclick="checkthis('urin_mayor_sub4')" value="Nokturia"><span class="lbl"> Nokturia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[mayor_subjektif][]" id="urin_mayor_sub5" onclick="checkthis('urin_mayor_sub5')" value="Mengompol"><span class="lbl"> Mengompol</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[mayor_subjektif][]" id="urin_mayor_sub6" onclick="checkthis('urin_mayor_sub6')" value="Enuresis"><span class="lbl"> Enuresis</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif :</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[mayor_objektif][]" id="urin_mayor_obj1" onclick="checkthis('urin_mayor_obj1')" value="Distensi kandung kemih"><span class="lbl"> Distensi kandung kemih</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[mayor_objektif][]" id="urin_mayor_obj2" onclick="checkthis('urin_mayor_obj2')" value="Berkemih tidak tuntas (hesitancy)"><span class="lbl"> Berkemih tidak tuntas (hesitancy)</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[mayor_objektif][]" id="urin_mayor_obj3" onclick="checkthis('urin_mayor_obj3')" value="Volume residu urine meningkat"><span class="lbl"> Volume residu urine meningkat</span></label></div>
          </div>
        </div>

        <hr>
        <p><b>Tanda dan Gejala Minor:</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif :</i></b><br>
            (Tidak tersedia)
          </div>
          <div class="col-md-6">
            <b>Objektif :</b><br>
            (Tidak tersedia)
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->



<!-- DUKUNGAN PERAWATAN DIRI BAK & MANAJEMEN ELIMINASI URINE -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>

  <tbody>
    <!-- JUDUL UTAMA 1 -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Dukungan Perawatan Diri BAK</b><br>
        <i>(Memfasilitasi pemenuhan kebutuhan buang air kecil (BAK))</i><br>
        <b>(I.11349)</b>
      </td>
    </tr>

    <!-- OBSERVASI 1 -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[dukungan_observasi][]" id="dukungan_observasi1" onclick="checkthis('dukungan_observasi1')" value="Identifikasi kebiasaan BAK sesuai usia"><span class="lbl"> Identifikasi kebiasaan BAK sesuai usia</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[dukungan_observasi][]" id="dukungan_observasi2" onclick="checkthis('dukungan_observasi2')" value="Monitor integritas kulit pasien"><span class="lbl"> Monitor integritas kulit pasien</span></label></div>
      </td>
    </tr>

    <!-- TERAPEUTIK 1 -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[dukungan_terapeutik][]" id="dukungan_terapeutik1" onclick="checkthis('dukungan_terapeutik1')" value="Buka pakaian yang diperlukan untuk memudahkan eliminasi"><span class="lbl"> Buka pakaian yang diperlukan untuk memudahkan eliminasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[dukungan_terapeutik][]" id="dukungan_terapeutik2" onclick="checkthis('dukungan_terapeutik2')" value="Dukung penggunaan toilet/commode/pispot/urinal secara konsisten"><span class="lbl"> Dukung penggunaan toilet/commode/pispot/urinal secara konsisten</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[dukungan_terapeutik][]" id="dukungan_terapeutik3" onclick="checkthis('dukungan_terapeutik3')" value="Jaga privasi selama eliminasi"><span class="lbl"> Jaga privasi selama eliminasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[dukungan_terapeutik][]" id="dukungan_terapeutik4" onclick="checkthis('dukungan_terapeutik4')" value="Ganti pakaian setelah eliminasi"><span class="lbl"> Ganti pakaian setelah eliminasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[dukungan_terapeutik][]" id="dukungan_terapeutik5" onclick="checkthis('dukungan_terapeutik5')" value="Latih BAK"><span class="lbl"> Latih BAK</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[dukungan_terapeutik][]" id="dukungan_terapeutik6" onclick="checkthis('dukungan_terapeutik6')" value="Sediakan alat bantu (mis. Kateter, urinal)"><span class="lbl"> Sediakan alat bantu (mis. Kateter, urinal)</span></label></div>
      </td>
    </tr>

    <!-- EDUKASI 1 -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[dukungan_edukasi][]" id="dukungan_edukasi1" onclick="checkthis('dukungan_edukasi1')" value="Anjurkan BAK secara rutin"><span class="lbl"> Anjurkan BAK secara rutin</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[dukungan_edukasi][]" id="dukungan_edukasi2" onclick="checkthis('dukungan_edukasi2')" value="Anjurkan ke kamar mandi/toilette"><span class="lbl"> Anjurkan ke kamar mandi/toilette</span></label></div>
      </td>
    </tr>

    <!-- JUDUL UTAMA 2 -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Manajemen Eliminasi Urine</b><br>
        <i>(Mengidentifikasi dan mengelola gangguan pola eliminasi urine)</i><br>
        <b>(I.04152)</b>
      </td>
    </tr>

    <!-- OBSERVASI 2 -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_observasi][]" id="eliminasi_observasi1" onclick="checkthis('eliminasi_observasi1')" value="Identifikasi tanda/gejala retensi atau inkontinensia urin"><span class="lbl"> Identifikasi tanda/gejala retensi atau inkontinensia urin</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_observasi][]" id="eliminasi_observasi2" onclick="checkthis('eliminasi_observasi2')" value="Identifikasi faktor yang menyebabkan retensi atau inkontinensia urin"><span class="lbl"> Identifikasi faktor yang menyebabkan retensi atau inkontinensia urin</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_observasi][]" id="eliminasi_observasi3" onclick="checkthis('eliminasi_observasi3')" value="Monitor eliminasi urin (frekuensi, warna, volume, aroma, konsistensi)"><span class="lbl"> Monitor eliminasi urin (frekuensi, warna, volume, aroma, konsistensi)</span></label></div>
      </td>
    </tr>

    <!-- TERAPEUTIK 2 -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_terapeutik][]" id="eliminasi_terapeutik1" onclick="checkthis('eliminasi_terapeutik1')" value="Catat waktu dan haluaran berkemih"><span class="lbl"> Catat waktu dan haluaran berkemih</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_terapeutik][]" id="eliminasi_terapeutik2" onclick="checkthis('eliminasi_terapeutik2')" value="Batasi asupan cairan"><span class="lbl"> Batasi asupan cairan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_terapeutik][]" id="eliminasi_terapeutik3" onclick="checkthis('eliminasi_terapeutik3')" value="Ambil sampel urin tengah atau kultur"><span class="lbl"> Ambil sampel urin tengah atau kultur</span></label></div>
      </td>
    </tr>

    <!-- EDUKASI 2 -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_edukasi][]" id="eliminasi_edukasi1" onclick="checkthis('eliminasi_edukasi1')" value="Ajarkan tanda/gejala infeksi saluran kemih"><span class="lbl"> Ajarkan tanda/gejala infeksi saluran kemih</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_edukasi][]" id="eliminasi_edukasi2" onclick="checkthis('eliminasi_edukasi2')" value="Ajarkan mengukur asupan cairan dan haluaran urin"><span class="lbl"> Ajarkan mengukur asupan cairan dan haluaran urin</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_edukasi][]" id="eliminasi_edukasi3" onclick="checkthis('eliminasi_edukasi3')" value="Ajarkan mengambil spesimen urin midstream"><span class="lbl"> Ajarkan mengambil spesimen urin midstream</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_edukasi][]" id="eliminasi_edukasi4" onclick="checkthis('eliminasi_edukasi4')" value="Ajarkan mengenali tanda berkemih"><span class="lbl"> Ajarkan mengenali tanda berkemih</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_edukasi][]" id="eliminasi_edukasi5" onclick="checkthis('eliminasi_edukasi5')" value="Ajarkan terapi modalitas penguatan otot-otot panggul/perkemihan"><span class="lbl"> Ajarkan terapi modalitas penguatan otot-otot panggul/perkemihan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_edukasi][]" id="eliminasi_edukasi6" onclick="checkthis('eliminasi_edukasi6')" value="Anjurkan minum yang cukup"><span class="lbl"> Anjurkan minum yang cukup</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_edukasi][]" id="eliminasi_edukasi7" onclick="checkthis('eliminasi_edukasi7')" value="Anjurkan mengurangi minum menjelang tidur"><span class="lbl"> Anjurkan mengurangi minum menjelang tidur</span></label></div>
      </td>
    </tr>

    <!-- KOLABORASI -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_108[eliminasi_kolaborasi][]" id="eliminasi_kolaborasi1" onclick="checkthis('eliminasi_kolaborasi1')" value="Kolaborasi pemberian obat supositoria uretra"><span class="lbl"> Kolaborasi pemberian obat supositoria uretra</span></label></div>
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
        <input type="text" class="input_type" name="form_108[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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