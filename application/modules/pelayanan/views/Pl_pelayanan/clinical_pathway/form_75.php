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
      var hiddenInputName = 'form_75[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 06 oktober 2025</p> -->
<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN: <br>HIPOVOLEMIA</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
        Definisi : Penurunan volume cairan intravaskuler, interstisial, dan/atau intraseluler yang disebabkan oleh kehilangan cairan tubuh atau penurunan asupan cairan.
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[penyebab][]" id="penyebab_kehilangan_cairan" onclick="checkthis('penyebab_kehilangan_cairan')" value="Kehilangan cairan aktif"><span class="lbl"> Kehilangan cairan aktif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[penyebab][]" id="penyebab_regulasi" onclick="checkthis('penyebab_regulasi')" value="Kegagalan mekanisme regulasi"><span class="lbl"> Kegagalan mekanisme regulasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[penyebab][]" id="penyebab_permeabilitas" onclick="checkthis('penyebab_permeabilitas')" value="Peningkatan permeabilitas kapiler"><span class="lbl"> Peningkatan permeabilitas kapiler</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[penyebab][]" id="penyebab_intake" onclick="checkthis('penyebab_intake')" value="Kekurangan intake cairan"><span class="lbl"> Kekurangan intake cairan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[penyebab][]" id="penyebab_evaporasi" onclick="checkthis('penyebab_evaporasi')" value="Evaporasi"><span class="lbl"> Evaporasi</span></label></div>
      </td>

      <!-- LUARAN -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>LUARAN:</b><br>
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_75[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Hipovolemi membaik (L.03028) dengan kriteria hasil:</b>

        <div style="display: flex; flex-wrap: wrap; margin-top: 5px;">
          <!-- KIRI -->
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_kekuatan_nadi" onclick="checkthis('hasil_kekuatan_nadi')" value="Kekuatan nadi meningkat"><span class="lbl"> Kekuatan nadi meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_turgor_kulit" onclick="checkthis('hasil_turgor_kulit')" value="Turgor kulit meningkat"><span class="lbl"> Turgor kulit meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_output_urine" onclick="checkthis('hasil_output_urine')" value="Output urine meningkat"><span class="lbl"> Output urine meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_pengisian_vena" onclick="checkthis('hasil_pengisian_vena')" value="Pengisian vena meningkat"><span class="lbl"> Pengisian vena meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_ortopnea" onclick="checkthis('hasil_ortopnea')" value="Ortopnea menurun"><span class="lbl"> Ortopnea menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_dispnea" onclick="checkthis('hasil_dispnea')" value="Dispnea menurun"><span class="lbl"> Dispnea menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_pnd" onclick="checkthis('hasil_pnd')" value="Paroxysmal nocturnal dyspnea menurun"><span class="lbl"> Paroxysmal nocturnal dyspnea (PND) menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_edema_anasarka" onclick="checkthis('hasil_edema_anasarka')" value="Edema anasarka menurun"><span class="lbl"> Edema anasarka menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_edema_perifer" onclick="checkthis('hasil_edema_perifer')" value="Edema perifer menurun"><span class="lbl"> Edema perifer menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_berat_badan" onclick="checkthis('hasil_berat_badan')" value="Berat badan menurun"><span class="lbl"> Berat badan menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_distensi_vena" onclick="checkthis('hasil_distensi_vena')" value="Distensi vena jugularis menurun"><span class="lbl"> Distensi vena jugularis menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_suara_nafas" onclick="checkthis('hasil_suara_nafas')" value="Suara nafas tambahan menurun"><span class="lbl"> Suara nafas tambahan menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_kongesti_paru" onclick="checkthis('hasil_kongesti_paru')" value="Kongesti paru menurun"><span class="lbl"> Kongesti paru menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_lemah" onclick="checkthis('hasil_lemah')" value="Perasaan lemah menurun"><span class="lbl"> Perasaan lemah menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_haus" onclick="checkthis('hasil_haus')" value="Keluhan haus menurun"><span class="lbl"> Keluhan haus menurun</span></label></div>
          </div>

          <!-- KANAN -->
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_konsentrasi_urin" onclick="checkthis('hasil_konsentrasi_urin')" value="Konsentrasi urin menurun"><span class="lbl"> Konsentrasi urin menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_frekuensi_nadi" onclick="checkthis('hasil_frekuensi_nadi')" value="Frekuensi nadi membaik"><span class="lbl"> Frekuensi nadi membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_tekanan_darah" onclick="checkthis('hasil_tekanan_darah')" value="Tekanan darah membaik"><span class="lbl"> Tekanan darah membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_tekanan_nadi" onclick="checkthis('hasil_tekanan_nadi')" value="Tekanan nadi membaik"><span class="lbl"> Tekanan nadi membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_membran_mukosa" onclick="checkthis('hasil_membran_mukosa')" value="Membran mukosa membaik"><span class="lbl"> Membran mukosa membaik</span></label></div>
            <!-- Tambahan baru -->
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_jvp" onclick="checkthis('hasil_jvp')" value="JVP membaik"><span class="lbl"> JVP membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_kadar_hb" onclick="checkthis('hasil_kadar_hb')" value="Kadar Hb membaik"><span class="lbl"> Kadar Hb membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_kadar_ht" onclick="checkthis('hasil_kadar_ht')" value="Kadar Ht membaik"><span class="lbl"> Kadar Ht membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_cvp" onclick="checkthis('hasil_cvp')" value="CVP membaik"><span class="lbl"> CVP membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_refluks_hepatojugular" onclick="checkthis('hasil_refluks_hepatojugular')" value="Refluks hepatojugular membaik"><span class="lbl"> Refluks hepatojugular membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_berat_badan_membaik" onclick="checkthis('hasil_berat_badan_membaik')" value="Berat badan membaik"><span class="lbl"> Berat badan membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_hepatomegali" onclick="checkthis('hasil_hepatomegali')" value="Hepatomegali membaik"><span class="lbl"> Hepatomegali membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_oliguria" onclick="checkthis('hasil_oliguria')" value="Oliguria membaik"><span class="lbl"> Oliguria membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_intake_cairan" onclick="checkthis('hasil_intake_cairan')" value="Intake cairan membaik"><span class="lbl"> Intake cairan membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_status_mental" onclick="checkthis('hasil_status_mental')" value="Status mental membaik"><span class="lbl"> Status mental membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[kriteria_hasil][]" id="hasil_suhu_tubuh" onclick="checkthis('hasil_suhu_tubuh')" value="Suhu tubuh membaik"><span class="lbl"> Suhu tubuh membaik</span></label></div>
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
            (Tidak tersedia)
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[mayor_objektif][]" id="mayor_frekuensi_nadi" onclick="checkthis('mayor_frekuensi_nadi')" value="Frekuensi nadi meningkat"><span class="lbl"> Frekuensi nadi meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[mayor_objektif][]" id="mayor_nadi_lemah" onclick="checkthis('mayor_nadi_lemah')" value="Nadi teraba lemah"><span class="lbl"> Nadi teraba lemah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[mayor_objektif][]" id="mayor_tekanan_darah" onclick="checkthis('mayor_tekanan_darah')" value="Tekanan darah menurun"><span class="lbl"> Tekanan darah menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[mayor_objektif][]" id="mayor_tekanan_nadi" onclick="checkthis('mayor_tekanan_nadi')" value="Tekanan nadi menyempit"><span class="lbl"> Tekanan nadi menyempit</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[mayor_objektif][]" id="mayor_turgor_kulit" onclick="checkthis('mayor_turgor_kulit')" value="Turgor kulit menurun"><span class="lbl"> Turgor kulit menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[mayor_objektif][]" id="mayor_membran_mukosa" onclick="checkthis('mayor_membran_mukosa')" value="Membran mukosa kering"><span class="lbl"> Membran mukosa kering</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[mayor_objektif][]" id="mayor_volume_urin" onclick="checkthis('mayor_volume_urin')" value="Volume urin menurun"><span class="lbl"> Volume urin menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[mayor_objektif][]" id="mayor_hematokrit" onclick="checkthis('mayor_hematokrit')" value="Hematokrit meningkat"><span class="lbl"> Hematokrit meningkat</span></label></div>
          </div>
        </div>

        <hr>

        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[minor_subjektif][]" id="minor_lemah" onclick="checkthis('minor_lemah')" value="Merasa lemah"><span class="lbl"> Merasa lemah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[minor_subjektif][]" id="minor_haus" onclick="checkthis('minor_haus')" value="Mengeluh haus"><span class="lbl"> Mengeluh haus</span></label></div>
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[minor_objektif][]" id="minor_pengisian_vena" onclick="checkthis('minor_pengisian_vena')" value="Pengisian vena menurun"><span class="lbl"> Pengisian vena menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[minor_objektif][]" id="minor_status_mental" onclick="checkthis('minor_status_mental')" value="Status mental berubah"><span class="lbl"> Status mental berubah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[minor_objektif][]" id="minor_suhu_tubuh" onclick="checkthis('minor_suhu_tubuh')" value="Suhu tubuh meningkat"><span class="lbl"> Suhu tubuh meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[minor_objektif][]" id="minor_konsentrasi_urin" onclick="checkthis('minor_konsentrasi_urin')" value="Konsentrasi urin meningkat"><span class="lbl"> Konsentrasi urin meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_75[minor_objektif][]" id="minor_berat_badan" onclick="checkthis('minor_berat_badan')" value="Berat badan turun tiba-tiba"><span class="lbl"> Berat badan turun tiba-tiba</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->


<!-- MANAJEMEN HIPOVOLEMIA -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Manajemen Hipovolemia</b><br>
        <i>(Mengidentifikasi dan mengelola penurunan volume cairan intravaskuler)</i><br>
        <b>(I.03116)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[hipovolemia_observasi][]" id="hipovolemia_observasi1" onclick="checkthis('hipovolemia_observasi1')" value="hipovolemia_observasi1"><span class="lbl"> Periksa tanda dan gejala hipovolemia (frekuensi nadi meningkat, nadi teraba lemah, tekanan darah menurun, tekanan nadi menyempit, turgor kulit menurun, membran mukosa kering, volume urine menurun, hematokrit meningkat, haus, lemah)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[hipovolemia_observasi][]" id="hipovolemia_observasi2" onclick="checkthis('hipovolemia_observasi2')" value="hipovolemia_observasi2"><span class="lbl"> Monitor intake dan output cairan</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[hipovolemia_terapeutik][]" id="hipovolemia_terapeutik1" onclick="checkthis('hipovolemia_terapeutik1')" value="hipovolemia_terapeutik1"><span class="lbl"> Hitung kebutuhan cairan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[hipovolemia_terapeutik][]" id="hipovolemia_terapeutik2" onclick="checkthis('hipovolemia_terapeutik2')" value="hipovolemia_terapeutik2"><span class="lbl"> Berikan posisi modified Trendelenburg</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[hipovolemia_terapeutik][]" id="hipovolemia_terapeutik3" onclick="checkthis('hipovolemia_terapeutik3')" value="hipovolemia_terapeutik3"><span class="lbl"> Berikan asupan cairan oral</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[hipovolemia_edukasi][]" id="hipovolemia_edukasi1" onclick="checkthis('hipovolemia_edukasi1')" value="hipovolemia_edukasi1"><span class="lbl"> Anjurkan memperbanyak asupan cairan oral</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[hipovolemia_edukasi][]" id="hipovolemia_edukasi2" onclick="checkthis('hipovolemia_edukasi2')" value="hipovolemia_edukasi2"><span class="lbl"> Anjurkan menghindari perubahan posisi mendadak</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[hipovolemia_kolaborasi][]" id="hipovolemia_kolaborasi1" onclick="checkthis('hipovolemia_kolaborasi1')" value="hipovolemia_kolaborasi1"><span class="lbl"> Kolaborasi pemberian cairan IV isotonis (NaCl, RL)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[hipovolemia_kolaborasi][]" id="hipovolemia_kolaborasi2" onclick="checkthis('hipovolemia_kolaborasi2')" value="hipovolemia_kolaborasi2"><span class="lbl"> Kolaborasi pemberian cairan IV hipotonis (Glukosa 2,5%, NaCl 0,45%)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[hipovolemia_kolaborasi][]" id="hipovolemia_kolaborasi3" onclick="checkthis('hipovolemia_kolaborasi3')" value="hipovolemia_kolaborasi3"><span class="lbl"> Kolaborasi pemberian cairan koloid (Albumin, Plasmanate)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[hipovolemia_kolaborasi][]" id="hipovolemia_kolaborasi4" onclick="checkthis('hipovolemia_kolaborasi4')" value="hipovolemia_kolaborasi4"><span class="lbl"> Kolaborasi pemberian produk darah</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<br><br>

<!-- MANAJEMEN SYOK HIPOVOLEMIA -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Manajemen Syok Hipovolemik</b><br>
        <i>(Mengidentifikasi dan mengelola ketidakmampuan tubuh menyediakan oksigen dan nutrien akibat kehilangan cairan/darah berlebih)</i><br>
        <b>(I.02050)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_observasi][]" id="syok_observasi1" onclick="checkthis('syok_observasi1')" value="syok_observasi1"><span class="lbl"> Monitor status kardiopulmonal (nadi: frekuensi & kekuatan, frekuensi napas, tekanan darah, MAP; juga nyeri - kualitas/lokasi/durasi/frekuensi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_observasi][]" id="syok_observasi2" onclick="checkthis('syok_observasi2')" value="syok_observasi2"><span class="lbl"> Monitor status oksigenasi (oksimetri nadi, AGD)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_observasi][]" id="syok_observasi3" onclick="checkthis('syok_observasi3')" value="syok_observasi3"><span class="lbl"> Monitor status cairan (intake & output, turgor kulit, CRT)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_observasi][]" id="syok_observasi4" onclick="checkthis('syok_observasi4')" value="syok_observasi4"><span class="lbl"> Periksa tingkat kesadaran dan respon pupil</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_observasi][]" id="syok_observasi5" onclick="checkthis('syok_observasi5')" value="syok_observasi5"><span class="lbl"> Periksa adanya DOTS (deformitas, luka terbuka, nyeri tekan, bengkak)</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_terapeutik][]" id="syok_terapeutik1" onclick="checkthis('syok_terapeutik1')" value="syok_terapeutik1"><span class="lbl"> Pertahankan jalan napas paten</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_terapeutik][]" id="syok_terapeutik2" onclick="checkthis('syok_terapeutik2')" value="syok_terapeutik2"><span class="lbl"> Berikan oksigen (saturasi &gt;94%)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_terapeutik][]" id="syok_terapeutik3" onclick="checkthis('syok_terapeutik3')" value="syok_terapeutik3"><span class="lbl"> Persiapkan intubasi/ventilasi mekanik bila perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_terapeutik][]" id="syok_terapeutik4" onclick="checkthis('syok_terapeutik4')" value="syok_terapeutik4"><span class="lbl"> Tekan langsung perdarahan eksternal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_terapeutik][]" id="syok_terapeutik5" onclick="checkthis('syok_terapeutik5')" value="syok_terapeutik5"><span class="lbl"> Berikan posisi syok (modified Trendelenburg)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_terapeutik][]" id="syok_terapeutik6" onclick="checkthis('syok_terapeutik6')" value="syok_terapeutik6"><span class="lbl"> Pasang jalur IV besar (no.14–16)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_terapeutik][]" id="syok_terapeutik7" onclick="checkthis('syok_terapeutik7')" value="syok_terapeutik7"><span class="lbl"> Pasang kateter urine untuk pantau output</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_terapeutik][]" id="syok_terapeutik8" onclick="checkthis('syok_terapeutik8')" value="syok_terapeutik8"><span class="lbl"> Pasang NGT untuk dekompresi lambung</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_terapeutik][]" id="syok_terapeutik9" onclick="checkthis('syok_terapeutik9')" value="syok_terapeutik9"><span class="lbl"> Ambil sampel darah (Darah lengkap & elektrolit)</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_kolaborasi][]" id="syok_kolaborasi1" onclick="checkthis('syok_kolaborasi1')" value="syok_kolaborasi1"><span class="lbl"> Kolaborasi pemberian cairan kristaloid 1–2L pada dewasa</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_kolaborasi][]" id="syok_kolaborasi2" onclick="checkthis('syok_kolaborasi2')" value="syok_kolaborasi2"><span class="lbl"> Kolaborasi pemberian cairan kristaloid 20ml/kgBB pada anak</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_xx[syok_kolaborasi][]" id="syok_kolaborasi3" onclick="checkthis('syok_kolaborasi3')" value="syok_kolaborasi3"><span class="lbl"> Kolaborasi pemberian transfusi darah bila perlu</span></label></div>
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
        <input type="text" class="input_type" name="form_75[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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