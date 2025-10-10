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
      var hiddenInputName = 'form_90[ttd_' + role + ']';
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
<div style="text-align: center; font-size: 18px;">
  <b>DIAGNOSIS KEPERAWATAN: PENURUNAN KAPASITAS ADAPTIF INTRAKRANIAL</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-family: tahoma, sans-serif; font-size: 13px;">
  <thead>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Definisi:</b>
        Gangguan mekanisme dinamika intrakranial dalam melakukan kompensasi terhadap stimulus yang dapat menurunkan kapasitas intrakrania.
      </td>
    </tr>
  </thead>
  <tbody>
    <!-- PENYEBAB -->
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[penyebab][]" id="penyebab_lesi" onclick="checkthis('penyebab_lesi')" value="Lesi menempati ruang (misal. Space occupaying lession - akibat tumor, abses)"><span class="lbl"> Lesi menempati ruang (misal. Space occupaying lession - akibat tumor, abses)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[penyebab][]" id="penyebab_metabolisme" onclick="checkthis('penyebab_metabolisme')" value="Gangguan metabolisme (misal. Akibat hiponatremia, ensefalopati uremikum, ensepalopati hepatikum, ketoasidosis diabetik, septikemia)"><span class="lbl"> Gangguan metabolisme (misal. Akibat hiponatremia, ensefalopati uremikum, ensepalopati hepatikum, ketoasidosis diabetik, septikemia)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[penyebab][]" id="penyebab_edema" onclick="checkthis('penyebab_edema')" value="Edema serebral (misal. Akibat cedera kepala, stroke, hipoksia, ensefalopati iskemik, pasca operasi)"><span class="lbl"> Edema serebral (misal. Akibat cedera kepala [hematoma epidural, subdural, subarachnoid, intraserebral], stroke iskemik/hemoragik, hipoksia, ensefalopati iskemik, pasca operasi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[penyebab][]" id="penyebab_vena" onclick="checkthis('penyebab_vena')" value="Peningkatan tekanan vena (misal. Akibat trombosis sinus vena serebral, gagal jantung, trombosis/obstruksi vena jugularis atau vena kava superior)"><span class="lbl"> Peningkatan tekanan vena (misal. Akibat trombosis sinus vena serebral, gagal jantung, trombosis/obstruksi vena jugularis atau vena kava superior)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[penyebab][]" id="penyebab_obstruksi" onclick="checkthis('penyebab_obstruksi')" value="Obstruksi aliran cairan serebrospinalis (misal. Hidrosefalus)"><span class="lbl"> Obstruksi aliran cairan serebrospinalis (misal. Hidrosefalus)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[penyebab][]" id="penyebab_hipertensi" onclick="checkthis('penyebab_hipertensi')" value="Hipertensi intrakranial idiopatik"><span class="lbl"> Hipertensi intrakranial idiopatik</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
        <input type="text" class="input_type" name="form_90[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
        , maka Kapasitas Adaptif Intrakranial (L.06049) meningkat dengan kriteria hasil:</b><br><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_kesadaran" onclick="checkthis('hasil_kesadaran')" value="Tingkat kesadaran meningkat"><span class="lbl"> Tingkat kesadaran meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_kognitif" onclick="checkthis('hasil_kognitif')" value="Fungsi kognitif meningkat"><span class="lbl"> Fungsi kognitif meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_sakit_kepala" onclick="checkthis('hasil_sakit_kepala')" value="Sakit kepala menurun"><span class="lbl"> Sakit kepala menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_gelisah" onclick="checkthis('hasil_gelisah')" value="Gelisah menurun"><span class="lbl"> Gelisah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_agitasi" onclick="checkthis('hasil_agitasi')" value="Agitasi menurun"><span class="lbl"> Agitasi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_muntah" onclick="checkthis('hasil_muntah')" value="Muntah menurun"><span class="lbl"> Muntah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_postur" onclick="checkthis('hasil_postur')" value="Postur deserebrasi menurun"><span class="lbl"> Postur deserebrasi (ekstensi) menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_papil" onclick="checkthis('hasil_papil')" value="Papil edema menurun"><span class="lbl"> Papil edema menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_td" onclick="checkthis('hasil_td')" value="Tekanan darah membaik"><span class="lbl"> Tekanan darah membaik*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_nadi" onclick="checkthis('hasil_nadi')" value="Tekanan nadi membaik"><span class="lbl"> Tekanan nadi membaik*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_bradikardia" onclick="checkthis('hasil_bradikardia')" value="Bradikardia membaik"><span class="lbl"> Bradikardia membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_pola_nafas" onclick="checkthis('hasil_pola_nafas')" value="Pola napas membaik"><span class="lbl"> Pola napas membaik*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_pupil" onclick="checkthis('hasil_pupil')" value="Respon pupil membaik"><span class="lbl"> Respon pupil membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_refleks" onclick="checkthis('hasil_refleks')" value="Refleks neurologis membaik"><span class="lbl"> Refleks neurologis membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kriteria_hasil][]" id="hasil_tik" onclick="checkthis('hasil_tik')" value="Tekanan intracranial membaik"><span class="lbl"> Tekanan intracranial membaik</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[mayor_subjektif][]" id="mayor_sub_sakit_kepala" onclick="checkthis('mayor_sub_sakit_kepala')" value="Sakit kepala"><span class="lbl"> Sakit kepala</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[mayor_objektif][]" id="mayor_obj_td" onclick="checkthis('mayor_obj_td')" value="Tekanan darah meningkat dengan tekanan nadi melebar"><span class="lbl"> Tekanan darah meningkat dengan tekanan nadi (pulse pressure) melebar</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[mayor_objektif][]" id="mayor_obj_bradikardia" onclick="checkthis('mayor_obj_bradikardia')" value="Bradikardia"><span class="lbl"> Bradikardia</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[mayor_objektif][]" id="mayor_obj_pola_nafas" onclick="checkthis('mayor_obj_pola_nafas')" value="Pola napas ireguler"><span class="lbl"> Pola napas ireguler</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[mayor_objektif][]" id="mayor_obj_kesadaran" onclick="checkthis('mayor_obj_kesadaran')" value="Tingkat kesadaran menurun"><span class="lbl"> Tingkat kesadaran menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[mayor_objektif][]" id="mayor_obj_pupil" onclick="checkthis('mayor_obj_pupil')" value="Respon pupil melambat atau tidak sama"><span class="lbl"> Respon pupil melambat atau tidak sama</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[mayor_objektif][]" id="mayor_obj_refleks" onclick="checkthis('mayor_obj_refleks')" value="Refleks neurologis terganggu"><span class="lbl"> Refleks neurologis terganggu</span></label></div>
          </div>
        </div>
        <hr>

        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <p>(Tidak tersedia)</p>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[minor_objektif][]" id="minor_obj_gelisah" onclick="checkthis('minor_obj_gelisah')" value="Gelisah"><span class="lbl"> Gelisah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[minor_objektif][]" id="minor_obj_agitasi" onclick="checkthis('minor_obj_agitasi')" value="Agitasi"><span class="lbl"> Agitasi</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[minor_objektif][]" id="minor_obj_muntah" onclick="checkthis('minor_obj_muntah')" value="Muntah tanpa disertai mual"><span class="lbl"> Muntah (tanpa disertai mual)</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[minor_objektif][]" id="minor_obj_lesu" onclick="checkthis('minor_obj_lesu')" value="Tampak lesu atau lemah"><span class="lbl"> Tampak lesu/lemah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[minor_objektif][]" id="minor_obj_kognitif" onclick="checkthis('minor_obj_kognitif')" value="Fungsi kognitif terganggu"><span class="lbl"> Fungsi kognitif terganggu</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[minor_objektif][]" id="minor_obj_tik" onclick="checkthis('minor_obj_tik')" value="Tekanan intrakranial lebih dari 20 mmHg"><span class="lbl"> Tekanan intrakranial (TIK) &gt; 20 mmHg</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[minor_objektif][]" id="minor_obj_papil" onclick="checkthis('minor_obj_papil')" value="Papil edema"><span class="lbl"> Papil edema</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[minor_objektif][]" id="minor_obj_deserebrasi" onclick="checkthis('minor_obj_deserebrasi')" value="Postur deserebrasi (ekstensi)"><span class="lbl"> Postur deserebrasi (ekstensi)</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[minor_objektif][]" id="minor_obj_dekortikasi" onclick="checkthis('minor_obj_dekortikasi')" value="Postur dekortikasi (fleksi)"><span class="lbl"> Postur dekortikasi (fleksi)</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->





<!-- MANAJEMEN PENINGKATAN TEKANAN INTRAKRANIAL -->
<table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; font-size:13px;">
  <thead>
    <tr style="background-color:#d3d3d3;">
      <th style="width:5%; text-align:center; border:1px solid black;">NO.</th>
      <th style="width:95%; text-align:center; border:1px solid black;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>

  <tbody>
    <!-- Bagian 1 -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:6px;">
        <b>Manajemen Peningkatan Tekanan Intrakranial</b><br>
        <i>(Mengidentifikasi dan mengelola peningkatan tekanan dalam rongga kranial)</i><br>
        <b>(I.06194)</b>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="border:1px solid black; padding:6px;">
        <b>Tindakan</b>
      </td>
    </tr>

    <!-- Tindakan 1: Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi1][]" id="observasi1_1" onclick="checkthis('observasi1_1')" value="Identifikasi penyebab peningkatan TIK"><span class="lbl"> Identifikasi penyebab peningkatan TIK (mis. lesi, gangguan metabolik, edema serebral)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi1][]" id="observasi1_2" onclick="checkthis('observasi1_2')" value="Monitor tanda gejala peningkatan TIK"><span class="lbl"> Monitor tanda/gejala peningkatan TIK (mis. tekanan darah meningkat, tekanan nadi melebar, bradikardia, pola napas ireguler, kesadaran menurun)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi1][]" id="observasi1_3" onclick="checkthis('observasi1_3')" value="Monitor MAP"><span class="lbl"> Monitor MAP (Mean Arterial Pressure)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi1][]" id="observasi1_4" onclick="checkthis('observasi1_4')" value="Monitor CVP"><span class="lbl"> Monitor CVP (Central Venous Pressure), jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi1][]" id="observasi1_5" onclick="checkthis('observasi1_5')" value="Monitor PAWP"><span class="lbl"> Monitor PAWP (Pulmonary Artery Wedge Pressure), jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi1][]" id="observasi1_6" onclick="checkthis('observasi1_6')" value="Monitor PAP"><span class="lbl"> Monitor PAP (Pulmonary Artery Pressure), jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi1][]" id="observasi1_7" onclick="checkthis('observasi1_7')" value="Monitor ICP"><span class="lbl"> Monitor ICP (Intra Cranial Pressure), jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi1][]" id="observasi1_8" onclick="checkthis('observasi1_8')" value="Monitor CPP"><span class="lbl"> Monitor CPP (Cerebral Perfusion Pressure)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi1][]" id="observasi1_9" onclick="checkthis('observasi1_9')" value="Monitor gelombang ICP"><span class="lbl"> Monitor gelombang ICP</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi1][]" id="observasi1_10" onclick="checkthis('observasi1_10')" value="Monitor status pernapasan"><span class="lbl"> Monitor status pernapasan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi1][]" id="observasi1_11" onclick="checkthis('observasi1_11')" value="Monitor intake output"><span class="lbl"> Monitor intake dan output cairan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi1][]" id="observasi1_12" onclick="checkthis('observasi1_12')" value="Monitor cairan serebrospinal"><span class="lbl"> Monitor cairan serebrospinal (mis. warna, konsistensi)</span></label></div>
      </td>
    </tr>

    <!-- Tindakan 2: Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:6px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik1][]" id="terapeutik1_1" onclick="checkthis('terapeutik1_1')" value="Minimalkan stimulus"><span class="lbl"> Minimalkan stimulus dengan menyediakan lingkungan yang tenang</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik1][]" id="terapeutik1_2" onclick="checkthis('terapeutik1_2')" value="Berikan posisi semi fowler"><span class="lbl"> Berikan posisi semi fowler</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik1][]" id="terapeutik1_3" onclick="checkthis('terapeutik1_3')" value="Hindari manuver Valsava"><span class="lbl"> Hindari manuver Valsava (mis. mengedan saat BAB)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik1][]" id="terapeutik1_4" onclick="checkthis('terapeutik1_4')" value="Cegah kejang"><span class="lbl"> Cegah terjadinya kejang</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik1][]" id="terapeutik1_5" onclick="checkthis('terapeutik1_5')" value="Hindari penggunaan PEEP"><span class="lbl"> Hindari penggunaan PEEP (Positive End Expiratory Pressure)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik1][]" id="terapeutik1_6" onclick="checkthis('terapeutik1_6')" value="Hindari cairan hipotonik"><span class="lbl"> Hindari pemberian cairan IV hipotonik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik1][]" id="terapeutik1_7" onclick="checkthis('terapeutik1_7')" value="Atur ventilator"><span class="lbl"> Atur ventilator agar PaCO₂ optimal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik1][]" id="terapeutik1_8" onclick="checkthis('terapeutik1_8')" value="Pertahankan suhu tubuh normal"><span class="lbl"> Pertahankan suhu tubuh normal</span></label></div>
      </td>
    </tr>

    <!-- Tindakan 3: Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:6px;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kolaborasi1][]" id="kolaborasi1_1" onclick="checkthis('kolaborasi1_1')" value="Kolaborasi sedasi anticonvulsan"><span class="lbl"> Kolaborasi pemberian sedasi dan anti konvulsan, jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kolaborasi1][]" id="kolaborasi1_2" onclick="checkthis('kolaborasi1_2')" value="Kolaborasi diuretik osmosis"><span class="lbl"> Kolaborasi pemberian diuretik osmosis, jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kolaborasi1][]" id="kolaborasi1_3" onclick="checkthis('kolaborasi1_3')" value="Kolaborasi pelunak feses"><span class="lbl"> Kolaborasi pemberian pelunak feses, jika perlu</span></label></div>
      </td>
    </tr>

    <!-- Bagian 2 -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:6px;">
        <b>Pemantauan Tekanan Intrakranial</b><br>
        <i>(Mengumpulkan dan menganalisis data terkait regulasi tekanan di dalam ruang intrakranial)</i><br>
        <b>(I.06198)</b>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="border:1px solid black; padding:6px;">
        <b>Tindakan</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:6px;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi2][]" id="observasi2_1" onclick="checkthis('observasi2_1')" value="Identifikasi penyebab peningkatan TIK"><span class="lbl"> Identifikasi penyebab peningkatan TIK (mis. lesi menempati ruang, gangguan metabolisme, edema serebral, peningkatan tekanan vena, obstruksi aliran cairan serebrospinal, hipertensi intrakranial idiopatik)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi2][]" id="observasi2_2" onclick="checkthis('observasi2_2')" value="Monitor peningkatan tekanan darah"><span class="lbl"> Monitor peningkatan tekanan darah</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi2][]" id="observasi2_3" onclick="checkthis('observasi2_3')" value="Monitor pelebaran tekanan nadi"><span class="lbl"> Monitor pelebaran tekanan nadi (selisih tekanan sistolik dan diastolik)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi2][]" id="observasi2_4" onclick="checkthis('observasi2_4')" value="Monitor penurunan frekuensi jantung"><span class="lbl"> Monitor penurunan frekuensi jantung</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi2][]" id="observasi2_5" onclick="checkthis('observasi2_5')" value="Monitor irama napas"><span class="lbl"> Monitor ireguleritas irama napas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi2][]" id="observasi2_6" onclick="checkthis('observasi2_6')" value="Monitor penurunan kesadaran"><span class="lbl"> Monitor penurunan tingkat kesadaran</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi2][]" id="observasi2_7" onclick="checkthis('observasi2_7')" value="Monitor respon pupil"><span class="lbl"> Monitor perlambatan atau ketidaksimetrisan respon pupil</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi2][]" id="observasi2_8" onclick="checkthis('observasi2_8')" value="Monitor kadar CO2"><span class="lbl"> Monitor kadar CO₂ dan pertahankan dalam rentang yang diindikasikan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi2][]" id="observasi2_9" onclick="checkthis('observasi2_9')" value="Monitor tekanan perfusi serebral"><span class="lbl"> Monitor tekanan perfusi serebral</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi2][]" id="observasi2_10" onclick="checkthis('observasi2_10')" value="Monitor drainase cairan serebrospinal"><span class="lbl"> Monitor jumlah, kecepatan, dan karakteristik drainase cairan serebrospinal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[observasi2][]" id="observasi2_11" onclick="checkthis('observasi2_11')" value="Monitor efek stimulus lingkungan"><span class="lbl"> Monitor efek stimulus lingkungan terhadap TIK</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:6px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik2][]" id="terapeutik2_1" onclick="checkthis('terapeutik2_1')" value="Ambil sampel drainase"><span class="lbl"> Ambil sampel drainase cairan serebrospinal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik2][]" id="terapeutik2_2" onclick="checkthis('terapeutik2_2')" value="Kalibrasi transduser"><span class="lbl"> Kalibrasi transduser</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik2][]" id="terapeutik2_3" onclick="checkthis('terapeutik2_3')" value="Pertahankan sterilitas sistem"><span class="lbl"> Pertahankan sterilitas sistem pemantauan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik2][]" id="terapeutik2_4" onclick="checkthis('terapeutik2_4')" value="Pertahankan posisi kepala netral"><span class="lbl"> Pertahankan posisi kepala dan leher netral</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik2][]" id="terapeutik2_5" onclick="checkthis('terapeutik2_5')" value="Bilas sistem pemantauan"><span class="lbl"> Bilas sistem pemantauan, jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik2][]" id="terapeutik2_6" onclick="checkthis('terapeutik2_6')" value="Atur interval pemantauan"><span class="lbl"> Atur interval pemantauan sesuai kondisi pasien</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[terapeutik2][]" id="terapeutik2_7" onclick="checkthis('terapeutik2_7')" value="Dokumentasikan hasil pemantauan"><span class="lbl"> Dokumentasikan hasil pemantauan</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:6px;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kolaborasi2][]" id="kolaborasi2_1" onclick="checkthis('kolaborasi2_1')" value="Jelaskan tujuan dan prosedur pemantauan"><span class="lbl"> Jelaskan tujuan dan prosedur pemantauan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_90[kolaborasi2][]" id="kolaborasi2_2" onclick="checkthis('kolaborasi2_2')" value="Informasikan hasil pemantauan"><span class="lbl"> Informasikan hasil pemantauan, jika perlu</span></label></div>
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
        <input type="text" class="input_type" name="form_90[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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