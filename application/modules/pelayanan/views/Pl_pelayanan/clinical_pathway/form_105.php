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
      var hiddenInputName = 'form_105[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: GANGGUAN MENELAN</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Fungsi menelan abnormal akibat defisit struktur atau fungsi oral, faring atau esofagus.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab1" onclick="checkthis('menelan_penyebab1')" value="Gangguan serebrovaskular"><span class="lbl"> Gangguan serebrovaskular</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab2" onclick="checkthis('menelan_penyebab2')" value="Gangguan saraf kranialis"><span class="lbl"> Gangguan saraf kranialis</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab3" onclick="checkthis('menelan_penyebab3')" value="Paralisis serebral"><span class="lbl"> Paralisis serebral</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab4" onclick="checkthis('menelan_penyebab4')" value="Akalasia"><span class="lbl"> Akalasia</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab5" onclick="checkthis('menelan_penyebab5')" value="Abnormalitas laring"><span class="lbl"> Abnormalitas laring</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab6" onclick="checkthis('menelan_penyebab6')" value="Abnormalitas orofaring"><span class="lbl"> Abnormalitas orofaring</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab7" onclick="checkthis('menelan_penyebab7')" value="Anomali jalan nafas"><span class="lbl"> Anomali jalan nafas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab8" onclick="checkthis('menelan_penyebab8')" value="Defek anatomik kongenital"><span class="lbl"> Defek anatomik kongenital</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab9" onclick="checkthis('menelan_penyebab9')" value="Defek laring"><span class="lbl"> Defek laring</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab10" onclick="checkthis('menelan_penyebab10')" value="Defek nasal"><span class="lbl"> Defek nasal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab11" onclick="checkthis('menelan_penyebab11')" value="Defek rongga laring"><span class="lbl"> Defek rongga laring</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab12" onclick="checkthis('menelan_penyebab12')" value="Defek trakhea"><span class="lbl"> Defek trakhea</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab13" onclick="checkthis('menelan_penyebab13')" value="Refluk gastroesofagus"><span class="lbl"> Refluk gastroesofagus</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab14" onclick="checkthis('menelan_penyebab14')" value="Obstruksi mekanis"><span class="lbl"> Obstruksi mekanis</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[penyebab][]" id="menelan_penyebab15" onclick="checkthis('menelan_penyebab15')" value="Prematuritas"><span class="lbl"> Prematuritas</span></label></div>
        <div style="margin-top:5px;"><i>NB: Defek = cacat/kerusakan</i></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_105[menelan_intervensi_selama]" id="menelan_intervensi_selama" onchange="fillthis('menelan_intervensi_selama')" style="width:10%;">
          , Status Menelan membaik (L.06052) dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit1" onclick="checkthis('menelan_krit1')" value="Mempertahankan makanan di mulut meningkat"><span class="lbl"> Mempertahankan makanan di mulut meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit2" onclick="checkthis('menelan_krit2')" value="Reflek menelan meningkat"><span class="lbl"> Reflek menelan meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit3" onclick="checkthis('menelan_krit3')" value="Kemampuan mengosongkan mulut meningkat"><span class="lbl"> Kemampuan mengosongkan mulut meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit4" onclick="checkthis('menelan_krit4')" value="Kemampuan mengunyah meningkat"><span class="lbl"> Kemampuan mengunyah meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit5" onclick="checkthis('menelan_krit5')" value="Usaha menelan meningkat"><span class="lbl"> Usaha menelan meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit6" onclick="checkthis('menelan_krit6')" value="Frekwensi tersedak menurun"><span class="lbl"> Frekwensi tersedak menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit7" onclick="checkthis('menelan_krit7')" value="Muntah menurun"><span class="lbl"> Muntah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit8" onclick="checkthis('menelan_krit8')" value="Refluks lambung menurun"><span class="lbl"> Refluks lambung menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit9" onclick="checkthis('menelan_krit9')" value="Gelisah menurun"><span class="lbl"> Gelisah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit10" onclick="checkthis('menelan_krit10')" value="Regurgitasi menurun"><span class="lbl"> Regurgitasi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit11" onclick="checkthis('menelan_krit11')" value="Produksi saliva membaik"><span class="lbl"> Produksi saliva membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit12" onclick="checkthis('menelan_krit12')" value="Penerimaan makanan membaik"><span class="lbl"> Penerimaan makanan membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[kriteria_hasil][]" id="menelan_krit13" onclick="checkthis('menelan_krit13')" value="Kwalitas suara membaik"><span class="lbl"> Kwalitas suara membaik</span></label></div>
      </td>
    </tr>

    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Dibuktikan dengan:</b><br>

        <p><b>Gejala dan Tanda Mayor:</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif :</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[mayor_subjektif][]" id="menelan_mayor_sub1" onclick="checkthis('menelan_mayor_sub1')" value="Mengeluh sulit menelan"><span class="lbl"> Mengeluh sulit menelan</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif :</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[mayor_objektif][]" id="menelan_mayor_obj1" onclick="checkthis('menelan_mayor_obj1')" value="Batuk sebelum menelan"><span class="lbl"> Batuk sebelum menelan</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[mayor_objektif][]" id="menelan_mayor_obj2" onclick="checkthis('menelan_mayor_obj2')" value="Batuk setelah makan atau minum / Tersedak"><span class="lbl"> Batuk setelah makan atau minum / Tersedak</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[mayor_objektif][]" id="menelan_mayor_obj3" onclick="checkthis('menelan_mayor_obj3')" value="Makanan tertinggal di rongga mulut"><span class="lbl"> Makanan tertinggal di rongga mulut</span></label></div>
          </div><br>
          <div class="col-md-6">
            <b>Faring</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[mayor_objektif][]" id="menelan_mayor_obj4" onclick="checkthis('menelan_mayor_obj4')" value="Faring: Menolak makan"><span class="lbl"> 1. Menolak makan</span></label></div><br>
          </div><br>
          <div class="col-md-6">
            <b>Esofagus</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[mayor_objektif][]" id="menelan_mayor_obj5" onclick="checkthis('menelan_mayor_obj5')" value="Esofagus: Mengeluh bangun di malam hari"><span class="lbl"> 1. Mengeluh bangun di malam hari</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[mayor_objektif][]" id="menelan_mayor_obj6" onclick="checkthis('menelan_mayor_obj6')" value="Esofagus: Nyeri epigastric"><span class="lbl"> 2. Nyeri epigastric</span></label></div>
          </div>

        </div>

        <hr>
        <p><b>Gejala dan Tanda Minor:</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif :</i></b><br>
            <i>(Oral: tidak tersedia)</i>
          </div>
          <div class="col-md-6">
            <b>Objektif :</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj1" onclick="checkthis('menelan_minor_obj1')" value="Bolus masuk terlalu cepat"><span class="lbl"> Bolus masuk terlalu cepat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj2" onclick="checkthis('menelan_minor_obj2')" value="Refluks nasal"><span class="lbl"> Refluks nasal</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj3" onclick="checkthis('menelan_minor_obj3')" value="Tidak mampu membersihkan rongga mulut"><span class="lbl"> Tidak mampu membersihkan rongga mulut</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj4" onclick="checkthis('menelan_minor_obj4')" value="Makanan jatuh dari mulut"><span class="lbl"> Makanan jatuh dari mulut</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj5" onclick="checkthis('menelan_minor_obj5')" value="Sulit mengeluh"><span class="lbl"> Sulit mengeluh</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj6" onclick="checkthis('menelan_minor_obj6')" value="Muntah sebelum menelan"><span class="lbl"> Muntah sebelum menelan</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj7" onclick="checkthis('menelan_minor_obj7')" value="Bolus terbentuk lama"><span class="lbl"> Bolus terbentuk lama</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj8" onclick="checkthis('menelan_minor_obj8')" value="Waktu makan lama"><span class="lbl"> Waktu makan lama</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj9" onclick="checkthis('menelan_minor_obj9')" value="Porsi makanan tidak habis"><span class="lbl"> Porsi makanan tidak habis</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj10" onclick="checkthis('menelan_minor_obj10')" value="Fase oral abnormal"><span class="lbl"> Fase oral abnormal</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj11" onclick="checkthis('menelan_minor_obj11')" value="Mengiler"><span class="lbl"> Mengiler</span></label></div>
          </div><br>
          <div class="col-md-6">
            <b>Faring :</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj12" onclick="checkthis('menelan_minor_obj12')" value="Faring: Muntah"><span class="lbl"> Muntah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj13" onclick="checkthis('menelan_minor_obj13')" value="Posisi kepala kurang elevasi"><span class="lbl"> Posisi kepala kurang elevasi</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj14" onclick="checkthis('menelan_minor_obj14')" value="Menelan berulang-ulang"><span class="lbl"> Menelan berulang-ulang</span></label></div>
          </div><br>
          <div class="col-md-6">
            <b>Esofagus :</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj15" onclick="checkthis('menelan_minor_obj15')" value="Hematemesis"><span class="lbl"> Hematemesis</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj16" onclick="checkthis('menelan_minor_obj16')" value="Gelisah"><span class="lbl"> Gelisah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj17" onclick="checkthis('menelan_minor_obj17')" value="Regurgitasi"><span class="lbl"> Regurgitasi</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj18" onclick="checkthis('menelan_minor_obj18')" value="Odinofagia"><span class="lbl"> Odinofagia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[minor_objektif][]" id="menelan_minor_obj19" onclick="checkthis('menelan_minor_obj19')" value="Bruksisme"><span class="lbl"> Bruksisme</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->




<!-- DUKUNGAN PERAWATAN DIRI: MAKAN/MINUM & PENCEGAHAN ASPIRASI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>

    <!-- Dukungan Perawatan Diri: Makan/Minum -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dukungan Perawatan Diri: Makan/Minum</b><br>
        <i>(Memfasilitasi pemenuhan kebutuhan makan/minum)</i><br>
        <b>(I.11351)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_observasi][]" id="makan_observasi1" onclick="checkthis('makan_observasi1')" value="Identifikasi diet yang dianjurkan"><span class="lbl"> Identifikasi diet yang dianjurkan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_observasi][]" id="makan_observasi2" onclick="checkthis('makan_observasi2')" value="Monitor kemampuan menelan"><span class="lbl"> Monitor kemampuan menelan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_observasi][]" id="makan_observasi3" onclick="checkthis('makan_observasi3')" value="Monitor status hidrasi pasien"><span class="lbl"> Monitor status hidrasi pasien</span></label></div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_105[makan_observasi_lain]" id="makan_observasi_lain" onchange="fillthis('makan_observasi_lain')" style="width: 98%;">
        </div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_terapeutik][]" id="makan_terapeutik1" onclick="checkthis('makan_terapeutik1')" value="Ciptakan lingkungan yang menyenangkan selama makan"><span class="lbl"> Ciptakan lingkungan yang menyenangkan selama makan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_terapeutik][]" id="makan_terapeutik2" onclick="checkthis('makan_terapeutik2')" value="Atur posisi yang nyaman untuk makan/minum"><span class="lbl"> Atur posisi yang nyaman untuk makan/minum</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_terapeutik][]" id="makan_terapeutik3" onclick="checkthis('makan_terapeutik3')" value="Lakukan oral hygiene sebelum makan"><span class="lbl"> Lakukan oral hygiene sebelum makan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_terapeutik][]" id="makan_terapeutik4" onclick="checkthis('makan_terapeutik4')" value="Letakkan makanan di sisi mata yang sehat berjalan"><span class="lbl"> Letakkan makanan di sisi mata yang sehat berjalan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_terapeutik][]" id="makan_terapeutik5" onclick="checkthis('makan_terapeutik5')" value="Sediakan sedotan untuk minum"><span class="lbl"> Sediakan sedotan untuk minum</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_terapeutik][]" id="makan_terapeutik6" onclick="checkthis('makan_terapeutik6')" value="Siapkan makanan dengan suhu yang meningkatkan nafsu makan"><span class="lbl"> Siapkan makanan dengan suhu yang meningkatkan nafsu makan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_terapeutik][]" id="makan_terapeutik7" onclick="checkthis('makan_terapeutik7')" value="Sediakan makanan dan minuman yang disukai"><span class="lbl"> Sediakan makanan dan minuman yang disukai</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_terapeutik][]" id="makan_terapeutik8" onclick="checkthis('makan_terapeutik8')" value="Berikan bantuan saat makan/minum sesuai tingkat kemandirian"><span class="lbl"> Berikan bantuan saat makan/minum sesuai tingkat kemandirian</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_terapeutik][]" id="makan_terapeutik9" onclick="checkthis('makan_terapeutik9')" value="Motivasi untuk makan di ruang makan"><span class="lbl"> Motivasi untuk makan di ruang makan</span></label></div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_105[makan_terapeutik_lain]" id="makan_terapeutik_lain" onchange="fillthis('makan_terapeutik_lain')" style="width: 98%;">
        </div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_edukasi][]" id="makan_edukasi1" onclick="checkthis('makan_edukasi1')" value="Jelaskan posisi makanan pada pasien gangguan penglihatan dengan arah jarum jam (mis: sayur di jam 12, rendang di jam 3)"><span class="lbl"> Jelaskan posisi makanan pada pasien gangguan penglihatan dengan arah jarum jam (mis: sayur di jam 12, rendang di jam 3)</span></label></div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_105[makan_edukasi_lain]" id="makan_edukasi_lain"  onchange="fillthis('makan_edukasi_lain')" style="width: 98%;">
        </div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[makan_kolaborasi][]" id="makan_kolaborasi1" onclick="checkthis('makan_kolaborasi1')" value="Kolaborasi pemberian obat (misal: analgesik, antiemetik)"><span class="lbl"> Kolaborasi pemberian obat (misal: analgesik, antiemetik)</span></label></div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_105[makan_kolaborasi_lain]" id="makan_kolaborasi_lain" onchange="fillthis('makan_kolaborasi_lain')" style="width: 98%;">
        </div>
      </td>
    </tr>

    <!-- PENCEGAHAN ASPIRASI -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Pencegahan Aspirasi</b><br>
        <i>(Mengidentifikasi dan mengurangi risiko masuknya partikel makanan/cairan ke dalam paru-paru)</i><br>
        <b>(I.01018)</b>
      </td>
    </tr>

    <!-- Observasi Pencegahan Aspirasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <!-- <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_observasi][]" id="aspirasi_observasi1" onclick="checkthis('aspirasi_observasi1')" value="Monitor tingkat kesadaran, batuk, muntah dan kemampuan menelan tiap..."><span class="lbl"> Monitor tingkat kesadaran, batuk, muntah dan kemampuan menelan tiap...</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_observasi][]" id="aspirasi_observasi2" onclick="checkthis('aspirasi_observasi2')" value="Monitor status pernafasan tiap..."><span class="lbl"> Monitor status pernafasan tiap...</span></label></div> -->
        <div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_105[aspirasi_observasi][]" id="aspirasi_observasi1" onclick="checkthis('aspirasi_observasi1')" value="Monitor tingkat kesadaran, batuk, muntah dan kemampuan menelan tiap">
                <span class="lbl">
                    Monitor tingkat kesadaran, batuk, muntah dan kemampuan menelan tiap 
                    <input type="text" class="input_type" name="form_105[aspirasi_observasi_tiap2]" placeholder="......" id="aspirasi_observasi_tiap2" onchange="fillthis('aspirasi_observasi_tiap2')" style="width: 80px; border: 1px solid #ccc; border-radius: 4px; padding: 2px;"> 
                </span>
            </label>
        </div>

        <div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_105[aspirasi_observasi][]" id="aspirasi_observasi2" onclick="checkthis('aspirasi_observasi2')" value="Monitor status pernafasan tiap">
                <span class="lbl">
                    Monitor status pernafasan tiap 
                    <input type="text" class="input_type" name="form_105[aspirasi_observasi_tiap3]" placeholder="......" id="aspirasi_observasi_tiap3" onchange="fillthis('aspirasi_observasi_tiap3')" style="width: 80px; border: 1px solid #ccc; border-radius: 4px; padding: 2px;"> 
                </span>
            </label>
        </div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_observasi][]" id="aspirasi_observasi3" onclick="checkthis('aspirasi_observasi3')" value="Monitor bunyi napas, terutama setelah makan/minum"><span class="lbl"> Monitor bunyi napas, terutama setelah makan/minum</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_observasi][]" id="aspirasi_observasi4" onclick="checkthis('aspirasi_observasi4')" value="Periksa residu gaster sebelum memberi asupan oral"><span class="lbl"> Periksa residu gaster sebelum memberi asupan oral</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_observasi][]" id="aspirasi_observasi5" onclick="checkthis('aspirasi_observasi5')" value="Periksa kepatenan selang nasogastrik sebelum memberi asupan oral"><span class="lbl"> Periksa kepatenan selang nasogastrik sebelum memberi asupan oral</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik Pencegahan Aspirasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_terapeutik][]" id="aspirasi_terapeutik1" onclick="checkthis('aspirasi_terapeutik1')" value="Posisikan semi fowler (30-45 derajat) 30 menit sebelum memberi asupan oral"><span class="lbl"> Posisikan semi fowler (30-45 derajat) 30 menit sebelum memberi asupan oral</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_terapeutik][]" id="aspirasi_terapeutik2" onclick="checkthis('aspirasi_terapeutik2')" value="Pertahankan posisi semi fowler (30-45 derajat) pada pasien tidak sadar"><span class="lbl"> Pertahankan posisi semi fowler (30-45 derajat) pada pasien tidak sadar</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_terapeutik][]" id="aspirasi_terapeutik3" onclick="checkthis('aspirasi_terapeutik3')" value="Pertahankan kepatenan jalan nafas (mis. Teknik head tilt chin lift, jaw thrust, in line)"><span class="lbl"> Pertahankan kepatenan jalan nafas (mis. Teknik head tilt chin lift, jaw thrust, in line)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_terapeutik][]" id="aspirasi_terapeutik4" onclick="checkthis('aspirasi_terapeutik4')" value="Pertahankan pengembangan balon endotracheal tube (ETT)"><span class="lbl"> Pertahankan pengembangan balon endotracheal tube (ETT)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_terapeutik][]" id="aspirasi_terapeutik5" onclick="checkthis('aspirasi_terapeutik5')" value="Lakukan penghisapan jalan nafas, jika produksi sekret meningkat"><span class="lbl"> Lakukan penghisapan jalan nafas, jika produksi sekret meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_terapeutik][]" id="aspirasi_terapeutik6" onclick="checkthis('aspirasi_terapeutik6')" value="Sediakan suction diruangan"><span class="lbl"> Sediakan suction diruangan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_terapeutik][]" id="aspirasi_terapeutik7" onclick="checkthis('aspirasi_terapeutik7')" value="Hindari memberi makan melalui selang gastrointestina, jika residu banyak"><span class="lbl"> Hindari memberi makan melalui selang gastrointestina, jika residu banyak</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_terapeutik][]" id="aspirasi_terapeutik8" onclick="checkthis('aspirasi_terapeutik8')" value="Berikan makanan dengan ukuran kecil atau lunak"><span class="lbl"> Berikan makanan dengan ukuran kecil atau lunak</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_terapeutik][]" id="aspirasi_terapeutik9" onclick="checkthis('aspirasi_terapeutik9')" value="Berikan obat oral dalam bentuk cair"><span class="lbl"> Berikan obat oral dalam bentuk cair</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_terapeutik][]" id="aspirasi_terapeutik10" onclick="checkthis('aspirasi_terapeutik10')" value="Anjurkan makan secara perlahan"><span class="lbl"> Anjurkan makan secara perlahan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_terapeutik][]" id="aspirasi_terapeutik11" onclick="checkthis('aspirasi_terapeutik11')" value="Ajarkan strategi mencegah aspirasi"><span class="lbl"> Ajarkan strategi mencegah aspirasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_105[aspirasi_terapeutik][]" id="aspirasi_terapeutik12" onclick="checkthis('aspirasi_terapeutik12')" value="Ajarkan teknik mengunyah atau menelan, jika perlu"><span class="lbl"> Ajarkan teknik mengunyah atau menelan, jika perlu</span></label></div>
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
        <input type="text" class="input_type" name="form_105[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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