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
      var hiddenInputName = 'form_73[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 01 oktober 2025</p> -->
<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN: IKTERIK NEONATUS</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
        Definisi : Kulit dan membran mukosa neonatus menguning setelah 24 jam kelahiran akibat bilirubin tidak terkonjugasi masuk ke dalam sirkulasi.
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[penyebab][]" id="penyebab_berat_badan" onclick="checkthis('penyebab_berat_badan')" value="Penurunan berat badan abnormal"><span class="lbl"> Penurunan berat badan abnormal (>7-8% pada bayi baru lahir yang menyusui ASI, >15% pada bayi cukup bulan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[penyebab][]" id="penyebab_pola_makan" onclick="checkthis('penyebab_pola_makan')" value="Pola makan tidak ditetapkan dengan baik"><span class="lbl"> Pola makan tidak ditetapkan dengan baik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[penyebab][]" id="penyebab_transisi" onclick="checkthis('penyebab_transisi')" value="Kesulitan transisi ke kehidupan ekstra uterin"><span class="lbl"> Kesulitan transisi ke kehidupan ekstra uterin</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[penyebab][]" id="penyebab_usia" onclick="checkthis('penyebab_usia')" value="Usia kurang dari 7 hari"><span class="lbl"> Usia kurang dari 7 hari</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[penyebab][]" id="penyebab_feses" onclick="checkthis('penyebab_feses')" value="Keterlambatan pengeluaran feses"><span class="lbl"> Keterlambatan pengeluaran feses</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_73[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;"> ,
          maka Integritas Kulit dan Jaringan (L.14125) meningkat dengan kriteria hasil:</b>

        <div style="display: flex; flex-wrap: wrap; margin-top: 5px;">
          <!-- KIRI -->
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_elastisitas" onclick="checkthis('hasil_elastisitas')" value="Elastisitas meningkat"><span class="lbl"> Elastisitas meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_hidrasi" onclick="checkthis('hasil_hidrasi')" value="Hidrasi meningkat"><span class="lbl"> Hidrasi meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_perfusi" onclick="checkthis('hasil_perfusi')" value="Perfusi jaringan meningkat"><span class="lbl"> Perfusi jaringan meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_kerusakan_jaringan" onclick="checkthis('hasil_kerusakan_jaringan')" value="Kerusakan jaringan menurun"><span class="lbl"> Kerusakan jaringan menurun*</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_kerusakan_kulit" onclick="checkthis('hasil_kerusakan_kulit')" value="Kerusakan lapisan kulit menurun"><span class="lbl"> Kerusakan lapisan kulit menurun*</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_nyeri" onclick="checkthis('hasil_nyeri')" value="Nyeri menurun"><span class="lbl"> Nyeri menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_perdarahan" onclick="checkthis('hasil_perdarahan')" value="Perdarahan menurun"><span class="lbl"> Perdarahan menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_kemerahan" onclick="checkthis('hasil_kemerahan')" value="Kemerahan menurun"><span class="lbl"> Kemerahan menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_hematoma" onclick="checkthis('hasil_hematoma')" value="Hematoma menurun"><span class="lbl"> Hematoma menurun</span></label></div>
          </div>

          <!-- KANAN -->
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_pigmentasi" onclick="checkthis('hasil_pigmentasi')" value="Pigmentasi abnormal menurun"><span class="lbl"> Pigmentasi abnormal menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_parut" onclick="checkthis('hasil_parut')" value="Jaringan parut menurun"><span class="lbl"> Jaringan parut menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_nekrosis" onclick="checkthis('hasil_nekrosis')" value="Nekrosis menurun"><span class="lbl"> Nekrosis menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_kornea" onclick="checkthis('hasil_kornea')" value="Abrasi kornea menurun"><span class="lbl"> Abrasi kornea menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_suhu" onclick="checkthis('hasil_suhu')" value="Suhu kulit membaik"><span class="lbl"> Suhu kulit membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_sensasi" onclick="checkthis('hasil_sensasi')" value="Sensasi membaik"><span class="lbl"> Sensasi membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_tekstur" onclick="checkthis('hasil_tekstur')" value="Tekstur membaik"><span class="lbl"> Tekstur membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[kriteria_hasil][]" id="hasil_rambut" onclick="checkthis('hasil_rambut')" value="Pertumbuhan rambut membaik"><span class="lbl"> Pertumbuhan rambut membaik</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[mayor_objektif][]" id="mayor_profil_darah" onclick="checkthis('mayor_profil_darah')" value="Profil darah abnormal"><span class="lbl"> Profil darah abnormal (hemolysis bilirubin serum total > 2mg/dl. Bilirubin serum total pada rentang risiko tinggi menurut usia)</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[mayor_objektif][]" id="mayor_mukosa" onclick="checkthis('mayor_mukosa')" value="Membrane mukosa kuning"><span class="lbl"> Membrane mukosa kuning</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[mayor_objektif][]" id="mayor_kulit" onclick="checkthis('mayor_kulit')" value="Kulit kuning"><span class="lbl"> Kulit kuning</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[mayor_objektif][]" id="mayor_sclera" onclick="checkthis('mayor_sclera')" value="Sclera kuning"><span class="lbl"> Sclera kuning</span></label></div>
          </div>
        </div>

        <hr>

        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            (Tidak tersedia)
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            (Tidak tersedia)
          </div>
        </div>
      </td>
    </tr>
    </tbody>
</table>
<br>
<!-- END -->

<!-- FOTOTERAPI NEONATUS -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Fototerapi Neonatus</b><br>
        <i>(Memberikan terapi sinar fluorescent yang ditujukan kepada kulit neonatus untuk menurunkan kadar bilirubin)</i><br>
        <b>(I.003091)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_observasi][]" id="fototerapi_observasi1" onclick="checkthis('fototerapi_observasi1')" value="Monitor ikterik"><span class="lbl"> Monitor ikterik pada sklera dan kulit bayi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_observasi][]" id="fototerapi_observasi2" onclick="checkthis('fototerapi_observasi2')" value="Identifikasi kebutuhan cairan"><span class="lbl"> Identifikasi kebutuhan cairan sesuai usia gestasi dan berat badan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_observasi][]" id="fototerapi_observasi3" onclick="checkthis('fototerapi_observasi3')" value="Monitor suhu dan tanda vital"><span class="lbl"> Monitor suhu dan tanda vital setiap 4 jam sekali</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_observasi][]" id="fototerapi_observasi4" onclick="checkthis('fototerapi_observasi4')" value="Monitor efek samping"><span class="lbl"> Monitor efek samping fototerapi (mis: hipertermi, diare, rash kulit, penurunan berat badan &gt; 8-10%)</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_terapeutik][]" id="fototerapi_terapeutik1" onclick="checkthis('fototerapi_terapeutik1')" value="Siapkan lampu fototerapi"><span class="lbl"> Siapkan lampu fototerapi dan inkubator/kotak bayi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_terapeutik][]" id="fototerapi_terapeutik2" onclick="checkthis('fototerapi_terapeutik2')" value="Lepaskan pakaian bayi"><span class="lbl"> Lepaskan pakaian bayi kecuali popok</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_terapeutik][]" id="fototerapi_terapeutik3" onclick="checkthis('fototerapi_terapeutik3')" value="Berikan eye protektor"><span class="lbl"> Berikan penutup mata (eye protector/biliband)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_terapeutik][]" id="fototerapi_terapeutik4" onclick="checkthis('fototerapi_terapeutik4')" value="Ukur jarak lampu"><span class="lbl"> Ukur jarak antara lampu dan kulit bayi (±30 cm atau sesuai spesifikasi lampu)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_terapeutik][]" id="fototerapi_terapeutik5" onclick="checkthis('fototerapi_terapeutik5')" value="Biarkan terpapar sinar"><span class="lbl"> Biarkan tubuh bayi terpapar sinar fototerapi berkelanjutan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_terapeutik][]" id="fototerapi_terapeutik6" onclick="checkthis('fototerapi_terapeutik6')" value="Ganti alas popok"><span class="lbl"> Ganti segera alas dan popok bayi jika BAB/BAK</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_terapeutik][]" id="fototerapi_terapeutik7" onclick="checkthis('fototerapi_terapeutik7')" value="Gunakan linen putih"><span class="lbl"> Gunakan linen berwarna putih agar memantulkan cahaya maksimal</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_edukasi][]" id="fototerapi_edukasi1" onclick="checkthis('fototerapi_edukasi1')" value="Menyusui 20-30 menit"><span class="lbl"> Anjurkan ibu menyusui sekitar 20–30 menit</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_edukasi][]" id="fototerapi_edukasi2" onclick="checkthis('fototerapi_edukasi2')" value="Menyusui sesering mungkin"><span class="lbl"> Anjurkan ibu menyusui sesering mungkin</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_73[fototerapi_kolaborasi][]" id="fototerapi_kolaborasi1" onclick="checkthis('fototerapi_kolaborasi1')" value="Pemeriksaan bilirubin"><span class="lbl"> Kolaborasi pemeriksaan darah vena bilirubin direk dan indirek</span></label></div>
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
        <input type="text" class="input_type" name="form_73[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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