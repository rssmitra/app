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
      var hiddenInputName = 'form_94[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: MENYUSUI EFEKTIF</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Pemberian ASI secara langsung dari payudara kepada ibu dan anak yang dapat memenuhi kebutuhan nutrisi
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>
        <b>Fisiologis:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[penyebab][]" id="mef_penyebab1" onclick="checkthis('mef_penyebab1')" value="Hormon oksitosin dan prolactin adekuat"><span class="lbl"> Hormon oksitosin dan prolactin adekuat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[penyebab][]" id="mef_penyebab2" onclick="checkthis('mef_penyebab2')" value="Payudara membesar, alveoli mulai terisi ASI"><span class="lbl"> Payudara membesar, alveoli mulai terisi ASI</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[penyebab][]" id="mef_penyebab3" onclick="checkthis('mef_penyebab3')" value="Tidak ada kelainan pada struktur payudara"><span class="lbl"> Tidak ada kelainan pada struktur payudara</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[penyebab][]" id="mef_penyebab4" onclick="checkthis('mef_penyebab4')" value="Puting menonjol"><span class="lbl"> Puting menonjol</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[penyebab][]" id="mef_penyebab5" onclick="checkthis('mef_penyebab5')" value="Bayi aterm"><span class="lbl"> Bayi aterm</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[penyebab][]" id="mef_penyebab6" onclick="checkthis('mef_penyebab6')" value="Tidak ada kelainan bentuk pada mulut bayi"><span class="lbl"> Tidak ada kelainan bentuk pada mulut bayi</span></label></div>
        <hr>
        <b>Situasional:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[penyebab][]" id="mef_penyebab7" onclick="checkthis('mef_penyebab7')" value="Rawat gabung"><span class="lbl"> Rawat gabung</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[penyebab][]" id="mef_penyebab8" onclick="checkthis('mef_penyebab8')" value="Dukungan keluarga dan tenaga kesehatan yang adekuat"><span class="lbl"> Dukungan keluarga dan tenaga kesehatan yang adekuat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[penyebab][]" id="mef_penyebab9" onclick="checkthis('mef_penyebab9')" value="Faktor budaya"><span class="lbl"> Faktor budaya</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_94[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Status Menyusui (L.03029) membaik dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit1" onclick="checkthis('mef_krit1')" value="Perlekatan bayi meningkat"><span class="lbl"> Perlekatan bayi pada payudara ibu meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit2" onclick="checkthis('mef_krit2')" value="Kemampuan ibu memposisikan bayi meningkat"><span class="lbl"> Kemampuan ibu memposisikan bayi dengan benar meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit3" onclick="checkthis('mef_krit3')" value="Miksi bayi meningkat"><span class="lbl"> Miksi bayi lebih dari 8 kali/24 jam meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit4" onclick="checkthis('mef_krit4')" value="Berat badan bayi meningkat"><span class="lbl"> Berat badan bayi meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit5" onclick="checkthis('mef_krit5')" value="Pancaran ASI meningkat"><span class="lbl"> Tetesan/pancaran ASI meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit6" onclick="checkthis('mef_krit6')" value="Suplai ASI meningkat"><span class="lbl"> Suplai ASI adekuat meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit7" onclick="checkthis('mef_krit7')" value="Putting tidak lecet meningkat"><span class="lbl"> Putting tidak lecet setelah 2 minggu melahirkan meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit8" onclick="checkthis('mef_krit8')" value="Kepercayaan diri meningkat"><span class="lbl"> Kepercayaan diri ibu meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit9" onclick="checkthis('mef_krit9')" value="Bayi tidur setelah menyusu meningkat"><span class="lbl"> Bayi tidur setelah menyusu meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit10" onclick="checkthis('mef_krit10')" value="Payudara kosong setelah menyusui meningkat"><span class="lbl"> Payudara ibu kosong setelah menyusui meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit11" onclick="checkthis('mef_krit11')" value="Intake bayi meningkat"><span class="lbl"> Intake bayi meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit12" onclick="checkthis('mef_krit12')" value="Hisapan bayi meningkat"><span class="lbl"> Hisapan bayi meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit13" onclick="checkthis('mef_krit13')" value="Lecet putting menurun"><span class="lbl"> Lecet pada putting menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit14" onclick="checkthis('mef_krit14')" value="Kelelahan maternal menurun"><span class="lbl"> Kelelahan maternal menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit15" onclick="checkthis('mef_krit15')" value="Kecemasan maternal menurun"><span class="lbl"> Kecemasan maternal menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit16" onclick="checkthis('mef_krit16')" value="Bayi rewel menurun"><span class="lbl"> Bayi rewel menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[kriteria_hasil][]" id="mef_krit17" onclick="checkthis('mef_krit17')" value="Bayi menangis setelah menyusu menurun"><span class="lbl"> Bayi menangis setelah menyusu menurun</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[mayor_subjektif][]" id="mef_mayor_sub1" onclick="checkthis('mef_mayor_sub1')" value="Ibu merasa percaya selama proses menyusui"><span class="lbl"> Ibu merasa percaya selama proses menyusui</span></label></div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[mayor_objektif][]" id="mef_mayor_obj1" onclick="checkthis('mef_mayor_obj1')" value="Bayi melekat dengan benar"><span class="lbl"> Bayi melekat pada payudara ibu dengan benar</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[mayor_objektif][]" id="mef_mayor_obj2" onclick="checkthis('mef_mayor_obj2')" value="Ibu mampu memposisikan bayi dengan benar"><span class="lbl"> Ibu mampu memposisikan bayi dengan benar</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[mayor_objektif][]" id="mef_mayor_obj3" onclick="checkthis('mef_mayor_obj3')" value="Miksi bayi lebih dari 8 kali"><span class="lbl"> Miksi bayi lebih dari 8 kali dalam 24 jam</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[mayor_objektif][]" id="mef_mayor_obj4" onclick="checkthis('mef_mayor_obj4')" value="Berat badan bayi meningkat"><span class="lbl"> Berat badan bayi meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[mayor_objektif][]" id="mef_mayor_obj5" onclick="checkthis('mef_mayor_obj5')" value="ASI menetes"><span class="lbl"> ASI menetes atau memancar</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[mayor_objektif][]" id="mef_mayor_obj6" onclick="checkthis('mef_mayor_obj6')" value="Suplai ASI adekuat"><span class="lbl"> Suplai ASI adekuat, putting tidak lecet setelah minggu kedua</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[minor_objektif][]" id="mef_minor1" onclick="checkthis('mef_minor1')" value="Bayi tidur setelah menyusui"><span class="lbl"> Bayi tidur setelah menyusui</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[minor_objektif][]" id="mef_minor2" onclick="checkthis('mef_minor2')" value="Payudara kosong setelah menyusui"><span class="lbl"> Payudara ibu kosong setelah menyusui</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_94[minor_objektif][]" id="mef_minor3" onclick="checkthis('mef_minor3')" value="Bayi tidak rewel setelah menyusui"><span class="lbl"> Bayi tidak rewel dan menangis setelah menyusui</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->




<!-- PROMOSI ASI EKSKLUSIF -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Promosi ASI Eksklusif -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Promosi ASI Eksklusif</b><br>
        <i>(Meningkatkan kemampuan ibu dalam memberikan ASI secara eksklusif (0–6 bulan))</i><br>
        <b>(I.03135)</b>
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
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_observasi][]" id="pae_observasi_1" onclick="checkthis('pae_observasi_1')" value="Identifikasi kebutuhan laktasi bagi ibu pada antenatal, intranatal, dan postnatal">
            <span class="lbl"> Identifikasi kebutuhan laktasi bagi ibu pada antenatal, intranatal, dan postnatal</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_terapeutik][]" id="pae_terapeutik_1" onclick="checkthis('pae_terapeutik_1')" value="Fasilitasi ibu melakukan IMD (inisiasi menyusu dini)">
            <span class="lbl"> Fasilitasi ibu melakukan IMD (inisiasi menyusu dini)</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_terapeutik][]" id="pae_terapeutik_2" onclick="checkthis('pae_terapeutik_2')" value="Fasilitasi ibu untuk rawat gabung atau rooming in">
            <span class="lbl"> Fasilitasi ibu untuk rawat gabung atau rooming in</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_terapeutik][]" id="pae_terapeutik_3" onclick="checkthis('pae_terapeutik_3')" value="Gunakan sendok atau cangkir jika bayi belum bisa menyusu">
            <span class="lbl"> Gunakan sendok atau cangkir jika bayi belum bisa menyusu</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_terapeutik][]" id="pae_terapeutik_4" onclick="checkthis('pae_terapeutik_4')" value="Dukung ibu menyusu dengan mendampingi ibu selama kegiatan menyusui berlangsung">
            <span class="lbl"> Dukung ibu menyusu dengan mendampingi ibu selama kegiatan menyusui berlangsung</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_terapeutik][]" id="pae_terapeutik_5" onclick="checkthis('pae_terapeutik_5')" value="Diskusikan dengan keluarga tentang ASI eksklusif">
            <span class="lbl"> Diskusikan dengan keluarga tentang ASI eksklusif</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_terapeutik][]" id="pae_terapeutik_6" onclick="checkthis('pae_terapeutik_6')" value="Siapkan kelas menyusui pada masa prenatal minimal 2 kali dan periode pascapartum minimal 4 kali">
            <span class="lbl"> Siapkan kelas menyusui pada masa prenatal minimal 2 kali dan periode pascapartum minimal 4 kali</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_edukasi][]" id="pae_edukasi_1" onclick="checkthis('pae_edukasi_1')" value="Jelaskan manfaat menyusui bagi ibu dan bayi">
            <span class="lbl"> Jelaskan manfaat menyusui bagi ibu dan bayi</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_edukasi][]" id="pae_edukasi_2" onclick="checkthis('pae_edukasi_2')" value="Jelaskan pentingnya menyusui di malam hari untuk mempertahankan dan meningkatkan produksi ASI">
            <span class="lbl"> Jelaskan pentingnya menyusui di malam hari untuk mempertahankan dan meningkatkan produksi ASI</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_edukasi][]" id="pae_edukasi_3" onclick="checkthis('pae_edukasi_3')" value="Jelaskan tanda-tanda bayi cukup ASI (mis. berat badan meningkat, BAK >10x/hari, warna urine tidak pekat)">
            <span class="lbl"> Jelaskan tanda-tanda bayi cukup ASI (mis. berat badan meningkat, BAK >10x/hari, warna urine tidak pekat)</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_edukasi][]" id="pae_edukasi_4" onclick="checkthis('pae_edukasi_4')" value="Jelaskan manfaat rawat gabung (rooming in)">
            <span class="lbl"> Jelaskan manfaat rawat gabung (rooming in)</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_edukasi][]" id="pae_edukasi_5" onclick="checkthis('pae_edukasi_5')" value="Anjurkan ibu menyusui segera mungkin setelah melahirkan">
            <span class="lbl"> Anjurkan ibu menyusui segera mungkin setelah melahirkan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_edukasi][]" id="pae_edukasi_6" onclick="checkthis('pae_edukasi_6')" value="Anjurkan ibu memberikan nutrisi kepada bayi hanya dengan ASI">
            <span class="lbl"> Anjurkan ibu memberikan nutrisi kepada bayi hanya dengan ASI</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_edukasi][]" id="pae_edukasi_7" onclick="checkthis('pae_edukasi_7')" value="Anjurkan ibu menyusui sesering mungkin setelah lahir sesuai kebutuhan bayi">
            <span class="lbl"> Anjurkan ibu menyusui sesering mungkin setelah lahir sesuai kebutuhan bayi</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_94[pae_edukasi][]" id="pae_edukasi_8" onclick="checkthis('pae_edukasi_8')" value="Anjurkan ibu menjaga produksi ASI dengan memerah, walaupun kondisi ibu atau bayi terpisah">
            <span class="lbl"> Anjurkan ibu menjaga produksi ASI dengan memerah, walaupun kondisi ibu atau bayi terpisah</span>
          </label>
        </div>
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
        <input type="text" class="input_type" name="form_94[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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