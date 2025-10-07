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
      var hiddenInputName = 'form_77[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 07 oktober 2025</p> -->

<div style="text-align: center; font-size: 18px;">
  <b>DIAGNOSIS KEPERAWATAN: RISIKO INFEKSI</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
        <b>Definisi:</b> Berisiko mengalami terserang organisme patogenik
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td colspan="2" width="50%" style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif; font-size: 13px; color: #000;">
    <b>FAKTOR RISIKO (Dibuktikan dengan):</b><br>

    <div class="checkbox" style="margin: 2px 0;">
      <label style="font-family: tahoma, sans-serif; font-size: 13px; color: #000;">
        <input type="checkbox" class="ace" name="form_77[faktor_risiko][]" id="faktor_penyakit_kronis" onclick="checkthis('faktor_penyakit_kronis')" value="Penyakit kronis (diabetes mellitus)">
        <span class="lbl"> Penyakit kronis (diabetes mellitus)</span>
      </label>
    </div>

    <div class="checkbox" style="margin: 2px 0;">
      <label style="font-family: tahoma, sans-serif; font-size: 13px; color: #000;">
        <input type="checkbox" class="ace" name="form_77[faktor_risiko][]" id="faktor_prosedur_invasif" onclick="checkthis('faktor_prosedur_invasif')" value="Efek prosedur invasif">
        <span class="lbl"> Efek prosedur invasif</span>
      </label>
    </div>

    <div class="checkbox" style="margin: 2px 0;">
      <label style="font-family: tahoma, sans-serif; font-size: 13px; color: #000;">
        <input type="checkbox" class="ace" name="form_77[faktor_risiko][]" id="faktor_malnutrisi" onclick="checkthis('faktor_malnutrisi')" value="Malnutrisi">
        <span class="lbl"> Malnutrisi</span>
      </label>
    </div>

    <div class="checkbox" style="margin: 2px 0;">
      <label style="font-family: tahoma, sans-serif; font-size: 13px; color: #000;">
        <input type="checkbox" class="ace" name="form_77[faktor_risiko][]" id="faktor_paparan" onclick="checkthis('faktor_paparan')" value="Peningkatan paparan mikroorganisme patogen lingkungan">
        <span class="lbl"> Peningkatan paparan mikroorganisme patogen lingkungan</span>
      </label>
    </div>

    <div class="checkbox" style="margin: 2px 0;">
      <label style="font-family: tahoma, sans-serif; font-size: 13px; color: #000;">
        <input type="checkbox" class="ace" name="form_77[faktor_risiko][]" id="faktor_pertahanan_primer" onclick="checkthis('faktor_pertahanan_primer')" value="Ketidak adekuatan pertahanan tubuh primer">
        <span class="lbl"> Ketidak adekuatan pertahanan tubuh primer:</span>
      </label>
    </div>

    <div style="margin-left: 25px;">
      a. Gangguan peristaltik<br>
      b. Kerusakan integritas kulit<br>
      c. Perubahan sekresi PH<br>
      d. Penurunan kerja siliasis<br>
      e. Merokok<br>
      f. Status cairan tubuh
    </div>
    <br>

    <div class="checkbox" style="margin: 2px 0;">
      <label style="font-family: tahoma, sans-serif; font-size: 13px; color: #000;">
        <input type="checkbox" class="ace" name="form_77[faktor_risiko][]" id="faktor_pertahanan_sekunder" onclick="checkthis('faktor_pertahanan_sekunder')" value="Ketidak adekuatan pertahanan tubuh sekunder">
        <span class="lbl"> Ketidak adekuatan pertahanan tubuh sekunder:</span>
      </label>
    </div>

    <div style="margin-left: 25px;">
      a. Penurunan haemoglobin<br>
      b. Penurunan imunosupresi<br>
      c. Leukopenia<br>
      d. Supresi respon inflamasi<br>
      e. Vaksinasi tidak adekuat
    </div>
  </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama
          <input type="text" class="input_type" name="form_77[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka tingkat infeksi menurun (L.14137) dengan kriteria hasil:</b><br><br>
        
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[kriteria_hasil][]" id="hasil_kebersihan_tangan" onclick="checkthis('hasil_kebersihan_tangan')" value="Kebersihan tangan meningkat"><span class="lbl"> Kebersihan tangan meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[kriteria_hasil][]" id="hasil_kebersihan_badan" onclick="checkthis('hasil_kebersihan_badan')" value="Kebersihan badan meningkat"><span class="lbl"> Kebersihan badan meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[kriteria_hasil][]" id="hasil_nafsu_makan" onclick="checkthis('hasil_nafsu_makan')" value="Nafsu makan meningkat"><span class="lbl"> Nafsu makan meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[kriteria_hasil][]" id="hasil_demam" onclick="checkthis('hasil_demam')" value="Demam menurun"><span class="lbl"> Demam menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[kriteria_hasil][]" id="hasil_kemerahan" onclick="checkthis('hasil_kemerahan')" value="Kemerahan menurun"><span class="lbl"> Kemerahan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[kriteria_hasil][]" id="hasil_nyeri" onclick="checkthis('hasil_nyeri')" value="Nyeri menurun"><span class="lbl"> Nyeri menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[kriteria_hasil][]" id="hasil_bengkak" onclick="checkthis('hasil_bengkak')" value="Bengkak menurun"><span class="lbl"> Bengkak menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[kriteria_hasil][]" id="hasil_sel_darah" onclick="checkthis('hasil_sel_darah')" value="Kadar sel darah putih menurun"><span class="lbl"> Kadar sel darah putih menurun</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->



<!-- PENCEGAHAN INFEKSI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Pencegahan Infeksi -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Pencegahan Infeksi</b><br>
        <i>(Mengidentifikasi dan menurunkan risiko terserang organisme patogenik)</i><br>
        <b>(I.14539)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_77[pi_observasi][]" id="pi_observasi_1" onclick="checkthis('pi_observasi_1')" value="Monitor tanda dan gejala infeksi lokal dan sistemik">
            <span class="lbl"> Monitor tanda dan gejala infeksi lokal dan sistemik</span>
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
            <input type="checkbox" class="ace" name="form_77[pi_terapeutik][]" id="pi_terapeutik_1" onclick="checkthis('pi_terapeutik_1')" value="Batasi jumlah pengunjung">
            <span class="lbl"> Batasi jumlah pengunjung</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_77[pi_terapeutik][]" id="pi_terapeutik_2" onclick="checkthis('pi_terapeutik_2')" value="Berikan perawatan kulit pada area edema">
            <span class="lbl"> Berikan perawatan kulit pada area edema</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_77[pi_terapeutik][]" id="pi_terapeutik_3" onclick="checkthis('pi_terapeutik_3')" value="Cuci tangan (5 moment)">
            <span class="lbl"> Cuci tangan (5 moment)</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_77[pi_terapeutik][]" id="pi_terapeutik_4" onclick="checkthis('pi_terapeutik_4')" value="Pertahankan teknik aseptik pada pasien berisiko tinggi">
            <span class="lbl"> Pertahankan teknik aseptik pada pasien berisiko tinggi</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[pi_edukasi][]" id="pi_edukasi_1" onclick="checkthis('pi_edukasi_1')" value="Jelaskan tanda dan gejala infeksi"><span class="lbl"> Jelaskan tanda dan gejala infeksi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[pi_edukasi][]" id="pi_edukasi_2" onclick="checkthis('pi_edukasi_2')" value="Ajarkan cara mencuci tangan dengan benar"><span class="lbl"> Ajarkan cara mencuci tangan dengan benar</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[pi_edukasi][]" id="pi_edukasi_3" onclick="checkthis('pi_edukasi_3')" value="Ajarkan etika batuk"><span class="lbl"> Ajarkan etika batuk</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[pi_edukasi][]" id="pi_edukasi_4" onclick="checkthis('pi_edukasi_4')" value="Ajarkan cara memeriksa kondisi luka atau luka operasi"><span class="lbl"> Ajarkan cara memeriksa kondisi luka atau luka operasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[pi_edukasi][]" id="pi_edukasi_5" onclick="checkthis('pi_edukasi_5')" value="Anjurkan meningkatkan asupan nutrisi"><span class="lbl"> Anjurkan meningkatkan asupan nutrisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_77[pi_edukasi][]" id="pi_edukasi_6" onclick="checkthis('pi_edukasi_6')" value="Anjurkan meningkatkan asupan cairan"><span class="lbl"> Anjurkan meningkatkan asupan cairan</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_77[pi_kolaborasi][]" id="pi_kolaborasi_1" onclick="checkthis('pi_kolaborasi_1')" value="Ajarkan cara mencuci tangan dengan benar">
            <span class="lbl"> Ajarkan cara mencuci tangan dengan benar</span>
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
        <input type="text" class="input_type" name="form_77[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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