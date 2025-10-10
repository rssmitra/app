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
      var hiddenInputName = 'form_93[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: MENYUSUI TIDAK EFEKTIF</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Kondisi dimana ibu dan bayi mengalami ketidakpuasan atau kesukaran pada proses menyusui
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>
        <b>Fisiologis:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[penyebab][]" id="menyusui_penyebab1" onclick="checkthis('menyusui_penyebab1')" value="Ketidakadekuatan suplai ASI"><span class="lbl"> Ketidakadekuatan suplai ASI</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[penyebab][]" id="menyusui_penyebab2" onclick="checkthis('menyusui_penyebab2')" value="Hambatan pada neonatus"><span class="lbl"> Hambatan pada neonatus (mis. Prematuritas, sumbing)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[penyebab][]" id="menyusui_penyebab3" onclick="checkthis('menyusui_penyebab3')" value="Anomali payudara ibu"><span class="lbl"> Anomali payudara ibu (mis. puting yang masuk ke dalam)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[penyebab][]" id="menyusui_penyebab4" onclick="checkthis('menyusui_penyebab4')" value="Ketidakadekuatan refleks oksitosin"><span class="lbl"> Ketidakadekuatan refleks oksitosin</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[penyebab][]" id="menyusui_penyebab5" onclick="checkthis('menyusui_penyebab5')" value="Ketidakadekuatan refleks menghisap bayi"><span class="lbl"> Ketidakadekuatan refleks menghisap bayi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[penyebab][]" id="menyusui_penyebab6" onclick="checkthis('menyusui_penyebab6')" value="Payudara bengkak"><span class="lbl"> Payudara bengkak</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[penyebab][]" id="menyusui_penyebab7" onclick="checkthis('menyusui_penyebab7')" value="Riwayat operasi payudara"><span class="lbl"> Riwayat operasi payudara</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[penyebab][]" id="menyusui_penyebab8" onclick="checkthis('menyusui_penyebab8')" value="Kelahiran kembar"><span class="lbl"> Kelahiran kembar</span></label></div>
        <hr>
        <b>Situasional:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[penyebab][]" id="menyusui_penyebab9" onclick="checkthis('menyusui_penyebab9')" value="Tidak rawat gabung"><span class="lbl"> Tidak rawat gabung</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[penyebab][]" id="menyusui_penyebab10" onclick="checkthis('menyusui_penyebab10')" value="Kurang terpapar informasi menyusui"><span class="lbl"> Kurang terpapar informasi tentang pentingnya menyusui dan/atau metode menyusui</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[penyebab][]" id="menyusui_penyebab11" onclick="checkthis('menyusui_penyebab11')" value="Kurangnya dukungan keluarga"><span class="lbl"> Kurangnya dukungan keluarga</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[penyebab][]" id="menyusui_penyebab12" onclick="checkthis('menyusui_penyebab12')" value="Faktor budaya"><span class="lbl"> Faktor budaya</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_93[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Status Menyusui (L.03029) membaik dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit1" onclick="checkthis('menyusui_krit1')" value="Perlekatan bayi meningkat"><span class="lbl"> Perlekatan bayi pada payudara ibu meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit2" onclick="checkthis('menyusui_krit2')" value="Kemampuan ibu memposisikan bayi meningkat"><span class="lbl"> Kemampuan ibu memposisikan bayi dengan benar meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit3" onclick="checkthis('menyusui_krit3')" value="Miksi bayi meningkat"><span class="lbl"> Miksi bayi lebih dari 8 kali/24 jam meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit4" onclick="checkthis('menyusui_krit4')" value="Berat badan bayi meningkat"><span class="lbl"> Berat badan bayi meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit5" onclick="checkthis('menyusui_krit5')" value="Pancaran ASI meningkat"><span class="lbl"> Tetesan/pancaran ASI meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit6" onclick="checkthis('menyusui_krit6')" value="Suplai ASI meningkat"><span class="lbl"> Suplai ASI adekuat meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit7" onclick="checkthis('menyusui_krit7')" value="Putting tidak lecet meningkat"><span class="lbl"> Putting tidak lecet setelah 2 minggu melahirkan meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit8" onclick="checkthis('menyusui_krit8')" value="Kepercayaan diri meningkat"><span class="lbl"> Kepercayaan diri ibu meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit9" onclick="checkthis('menyusui_krit9')" value="Bayi tidur setelah menyusu meningkat"><span class="lbl"> Bayi tidur setelah menyusu meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit10" onclick="checkthis('menyusui_krit10')" value="Payudara kosong setelah menyusui meningkat"><span class="lbl"> Payudara ibu kosong setelah menyusui meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit11" onclick="checkthis('menyusui_krit11')" value="Intake bayi meningkat"><span class="lbl"> Intake bayi meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit12" onclick="checkthis('menyusui_krit12')" value="Hisapan bayi meningkat"><span class="lbl"> Hisapan bayi meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit13" onclick="checkthis('menyusui_krit13')" value="Lecet putting menurun"><span class="lbl"> Lecet pada putting menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit14" onclick="checkthis('menyusui_krit14')" value="Kelelahan maternal menurun"><span class="lbl"> Kelelahan maternal menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit15" onclick="checkthis('menyusui_krit15')" value="Kecemasan maternal menurun"><span class="lbl"> Kecemasan maternal menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit16" onclick="checkthis('menyusui_krit16')" value="Bayi rewel menurun"><span class="lbl"> Bayi rewel menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[kriteria_hasil][]" id="menyusui_krit17" onclick="checkthis('menyusui_krit17')" value="Bayi menangis setelah menyusu menurun"><span class="lbl"> Bayi menangis setelah menyusu menurun</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[mayor_subjektif][]" id="menyusui_mayor_sub1" onclick="checkthis('menyusui_mayor_sub1')" value="Kelelahan maternal"><span class="lbl"> Kelelahan maternal</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[mayor_subjektif][]" id="menyusui_mayor_sub2" onclick="checkthis('menyusui_mayor_sub2')" value="Kecemasan maternal"><span class="lbl"> Kecemasan maternal</span></label></div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[mayor_objektif][]" id="menyusui_mayor_obj1" onclick="checkthis('menyusui_mayor_obj1')" value="Bayi tidak mampu melekat"><span class="lbl"> Bayi tidak mampu melekat pada payudara ibu</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[mayor_objektif][]" id="menyusui_mayor_obj2" onclick="checkthis('menyusui_mayor_obj2')" value="ASI tidak menetes"><span class="lbl"> ASI tidak menetes/memancar</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[mayor_objektif][]" id="menyusui_mayor_obj3" onclick="checkthis('menyusui_mayor_obj3')" value="BAK bayi kurang"><span class="lbl"> BAK bayi kurang dari 8 kali dalam 24 jam</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[mayor_objektif][]" id="menyusui_mayor_obj4" onclick="checkthis('menyusui_mayor_obj4')" value="Nyeri lecet payudara"><span class="lbl"> Nyeri dan/atau lecet terus menerus setelah minggu kedua</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[minor_objektif][]" id="menyusui_minor1" onclick="checkthis('menyusui_minor1')" value="Intake bayi tidak adekuat"><span class="lbl"> Intake bayi tidak adekuat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[minor_objektif][]" id="menyusui_minor2" onclick="checkthis('menyusui_minor2')" value="Bayi menghisap tidak terus menerus"><span class="lbl"> Bayi menghisap tidak terus menerus</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[minor_objektif][]" id="menyusui_minor3" onclick="checkthis('menyusui_minor3')" value="Bayi menangis saat disusui"><span class="lbl"> Bayi menangis saat disusui</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[minor_objektif][]" id="menyusui_minor4" onclick="checkthis('menyusui_minor4')" value="Bayi rewel setelah menyusu"><span class="lbl"> Bayi rewel dan menangis terus dalam jam-jam pertama setelah menyusui</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_93[minor_objektif][]" id="menyusui_minor5" onclick="checkthis('menyusui_minor5')" value="Menolak menghisap"><span class="lbl"> Menolak untuk menghisap</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->



<!-- EDUKASI MENYUSUI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Edukasi Menyusui -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Edukasi Menyusui</b><br>
        <i>(Memberikan informasi dan saran tentang menyusui yang dimulai dari antepartum, intrapartum dan postpartum)</i><br>
        <b>(I.12393)</b>
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
            <input type="checkbox" class="ace" name="form_93[em_observasi][]" id="em_observasi_1" onclick="checkthis('em_observasi_1')" value="Identifikasi kesiapan dan kemampuan menerima informasi">
            <span class="lbl"> Identifikasi kesiapan dan kemampuan menerima informasi</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_93[em_observasi][]" id="em_observasi_2" onclick="checkthis('em_observasi_2')" value="Identifikasi tujuan dan keinginan menyusui">
            <span class="lbl"> Identifikasi tujuan dan keinginan menyusui</span>
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
            <input type="checkbox" class="ace" name="form_93[em_terapeutik][]" id="em_terapeutik_1" onclick="checkthis('em_terapeutik_1')" value="Sediakan materi dan media pendidikan kesehatan">
            <span class="lbl"> Sediakan materi dan media pendidikan kesehatan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_93[em_terapeutik][]" id="em_terapeutik_2" onclick="checkthis('em_terapeutik_2')" value="Jadwalkan pendidikan kesehatan sesuai kesepakatan">
            <span class="lbl"> Jadwalkan pendidikan kesehatan sesuai kesepakatan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_93[em_terapeutik][]" id="em_terapeutik_3" onclick="checkthis('em_terapeutik_3')" value="Berikan kesempatan untuk bertanya">
            <span class="lbl"> Berikan kesempatan untuk bertanya</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_93[em_terapeutik][]" id="em_terapeutik_4" onclick="checkthis('em_terapeutik_4')" value="Dukung Ibu meningkatkan kepercayaan diri dalam menyusui">
            <span class="lbl"> Dukung Ibu meningkatkan kepercayaan diri dalam menyusui</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_93[em_terapeutik][]" id="em_terapeutik_5" onclick="checkthis('em_terapeutik_5')" value="Libatkan sistem pendukung: suami, keluarga, tenaga kesehatan dan masyarakat">
            <span class="lbl"> Libatkan sistem pendukung: suami, keluarga, tenaga kesehatan dan masyarakat</span>
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
            <input type="checkbox" class="ace" name="form_93[em_edukasi][]" id="em_edukasi_1" onclick="checkthis('em_edukasi_1')" value="Berikan konseling menyusui">
            <span class="lbl"> Berikan konseling menyusui</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_93[em_edukasi][]" id="em_edukasi_2" onclick="checkthis('em_edukasi_2')" value="Jelaskan manfaat menyusui bagi ibu dan bayi">
            <span class="lbl"> Jelaskan manfaat menyusui bagi ibu dan bayi</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_93[em_edukasi][]" id="em_edukasi_3" onclick="checkthis('em_edukasi_3')" value="Ajarkan 4 (empat) posisi menyusui dan perlekatan (latch on) dengan benar">
            <span class="lbl"> Ajarkan 4 (empat) posisi menyusui dan perlekatan (latch on) dengan benar</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_93[em_edukasi][]" id="em_edukasi_4" onclick="checkthis('em_edukasi_4')" value="Ajarkan perawatan payudara antepartum dengan mengkompres dengan kasa yang telah diberi minyak kelapa">
            <span class="lbl"> Ajarkan perawatan payudara antepartum dengan mengkompres dengan kasa yang telah diberi minyak kelapa</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_93[em_edukasi][]" id="em_edukasi_5" onclick="checkthis('em_edukasi_5')" value="Ajarkan perawatan payudara postpartum (misalnya memerah ASI, pijat payudara, pijat oksitosin)">
            <span class="lbl"> Ajarkan perawatan payudara postpartum (misalnya memerah ASI, pijat payudara, pijat oksitosin)</span>
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
        <input type="text" class="input_type" name="form_93[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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