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
      var hiddenInputName = 'form_72[ttd_' + role + ']';
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
<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN: DIARE</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
        Definisi : Pengeluaran feses yang sering, lunak dan tidak berbentuk.
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <p><b>Fisiologis</b></p>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_inflamasi" onclick="checkthis('penyebab_inflamasi')" value="Inflamasi gastrointestinal"><span class="lbl"> Inflamasi gastrointestinal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_iritasi" onclick="checkthis('penyebab_iritasi')" value="Iritasi gastrointestinal"><span class="lbl"> Iritasi gastrointestinal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_infeksi" onclick="checkthis('penyebab_infeksi')" value="Proses infeksi"><span class="lbl"> Proses infeksi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_malabsorbsi" onclick="checkthis('penyebab_malabsorbsi')" value="Malabsorbsi"><span class="lbl"> Malabsorbsi</span></label></div>

        <p><b>Psikologis</b></p>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_kecemasan" onclick="checkthis('penyebab_kecemasan')" value="Kecemasan"><span class="lbl"> Kecemasan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_stress" onclick="checkthis('penyebab_stress')" value="Tingkat stress tinggi"><span class="lbl"> Tingkat stress tinggi</span></label></div>

        <p><b>Situasional</b></p>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_kontaminan" onclick="checkthis('penyebab_kontaminan')" value="Terpapar kontaminan"><span class="lbl"> Terpapar kontaminan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_toksin" onclick="checkthis('penyebab_toksin')" value="Terpapar toksin"><span class="lbl"> Terpapar toksin</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_laksatif" onclick="checkthis('penyebab_laksatif')" value="Penyalahgunaan laksatif"><span class="lbl"> Penyalahgunaan laksatif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_zat" onclick="checkthis('penyebab_zat')" value="Penyalahgunaan zat"><span class="lbl"> Penyalahgunaan zat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_obat" onclick="checkthis('penyebab_obat')" value="Program pengobatan (mis: agen tiroid, analgesic, pelunak feses, ferosulfat, antasida, cimetidine, antibiotik)"><span class="lbl"> Program pengobatan (mis: agen tiroid, analgesic, pelunak feses, ferosulfat, antasida, cimetidine, antibiotik)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_air_makanan" onclick="checkthis('penyebab_air_makanan')" value="Perubahan air dan makanan"><span class="lbl"> Perubahan air dan makanan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[penyebab][]" id="penyebab_bakteri" onclick="checkthis('penyebab_bakteri')" value="Bakteri pada air"><span class="lbl"> Bakteri pada air</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_72[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;"> ,
          maka eliminasi fekal membaik (L.04033) dengan kriteria hasil:</b>

        <div style="display: flex; flex-wrap: wrap; margin-top: 5px;">
          <!-- KIRI -->
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[kriteria_hasil][]" id="hasil_kontrol" onclick="checkthis('hasil_kontrol')" value="Kontrol pengeluaran feses meningkat"><span class="lbl"> Kontrol pengeluaran feses meningkat*</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[kriteria_hasil][]" id="hasil_defekasi_sulit" onclick="checkthis('hasil_defekasi_sulit')" value="Keluhan defekasi lama dan sulit menurun"><span class="lbl"> Keluhan defekasi lama dan sulit menurun*</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[kriteria_hasil][]" id="hasil_mengejan" onclick="checkthis('hasil_mengejan')" value="Mengejan saat defekasi menurun"><span class="lbl"> Mengejan saat defekasi menurun*</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[kriteria_hasil][]" id="hasil_distensi" onclick="checkthis('hasil_distensi')" value="Distensi abdomen menurun"><span class="lbl"> Distensi abdomen menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[kriteria_hasil][]" id="hasil_rectal" onclick="checkthis('hasil_rectal')" value="Teraba massa pada rectal menurun"><span class="lbl"> Teraba massa pada rectal menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[kriteria_hasil][]" id="hasil_urgency" onclick="checkthis('hasil_urgency')" value="Urgency menurun"><span class="lbl"> Urgency menurun</span></label></div>
          </div>

          <!-- KANAN -->
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[kriteria_hasil][]" id="hasil_nyeri_abdomen" onclick="checkthis('hasil_nyeri_abdomen')" value="Nyeri abdomen menurun"><span class="lbl"> Nyeri abdomen menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[kriteria_hasil][]" id="hasil_kram" onclick="checkthis('hasil_kram')" value="Kram abdomen menurun"><span class="lbl"> Kram abdomen menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[kriteria_hasil][]" id="hasil_konsistensi" onclick="checkthis('hasil_konsistensi')" value="Konsistensi feses membaik"><span class="lbl"> Konsistensi feses membaik*</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[kriteria_hasil][]" id="hasil_frekuensi" onclick="checkthis('hasil_frekuensi')" value="Frekuensi defekasi membaik"><span class="lbl"> Frekuensi defekasi membaik*</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[kriteria_hasil][]" id="hasil_peristaltik" onclick="checkthis('hasil_peristaltik')" value="Peristaltik usus meningkat"><span class="lbl"> Peristaltik usus meningkat*</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[mayor_objektif][]" id="mayor_defekasi" onclick="checkthis('mayor_defekasi')" value="Defekasi lebih dari 3 kali dalam 24 jam"><span class="lbl"> Defekasi lebih dari 3 kali dalam 24 jam</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[mayor_objektif][]" id="mayor_feses" onclick="checkthis('mayor_feses')" value="Feses lembek atau cair"><span class="lbl"> Feses lembek atau cair</span></label></div>
          </div>
        </div>

        <hr>

        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[minor_subjektif][]" id="minor_urgency" onclick="checkthis('minor_urgency')" value="Urgency"><span class="lbl"> Urgency</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[minor_subjektif][]" id="minor_kram" onclick="checkthis('minor_kram')" value="Nyeri/kram abdomen"><span class="lbl"> Nyeri/kram abdomen</span></label></div>
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[minor_objektif][]" id="minor_peristaltik" onclick="checkthis('minor_peristaltik')" value="Frekuensi peristaltik meningkat"><span class="lbl"> Frekuensi peristaltik meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[minor_objektif][]" id="minor_bising" onclick="checkthis('minor_bising')" value="Bising usus hiperaktif"><span class="lbl"> Bising usus hiperaktif</span></label></div>
          </div>
        </div>
      </td>
    </tr>
    </tbody>
</table>
<br>
<!-- END -->

<!-- MANAJEMEN DIARE -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <tbody>
    <tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
      <thead>
        <tr style="background-color: #d3d3d3;">
          <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
          <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
        </tr>
      </thead>
   </tr>  

    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Manajemen Diare</b>
        <i>(Mengidentifikasi dan mengelola diare dan dampaknya)</i>
        <b>(I.03101)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_observasi][]" id="diare_observasi1" onclick="checkthis('diare_observasi1')" value="Identifikasi penyebab diare"><span class="lbl"> Identifikasi penyebab diare (mis: inflamasi gastrointestinal, iritasi gastrointestinal, proses infeksi, malabsorbsi, ansietas, stres, efek obat-obatan, pemberian botol susu)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_observasi][]" id="diare_observasi2" onclick="checkthis('diare_observasi2')" value="Identifikasi riwayat pemberian makanan"><span class="lbl"> Identifikasi riwayat pemberian makanan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_observasi][]" id="diare_observasi3" onclick="checkthis('diare_observasi3')" value="Identifikasi gejala invaginasi"><span class="lbl"> Identifikasi gejala invaginasi (mis: tangisan keras, kepucatan pada bayi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_observasi][]" id="diare_observasi4" onclick="checkthis('diare_observasi4')" value="Monitor tinja"><span class="lbl"> Monitor warna, volume, frekuensi dan konsistensi tinja</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_observasi][]" id="diare_observasi5" onclick="checkthis('diare_observasi5')" value="Monitor hypovolemia"><span class="lbl"> Monitor tanda dan gejala hypovolemia (mis: takikardia, nadi teraba lemah, tekanan darah menurun, turgor kulit menurun, mukosa mulut kering, CRT melambat, berat badan menurun)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_observasi][]" id="diare_observasi6" onclick="checkthis('diare_observasi6')" value="Monitor iritasi perianal"><span class="lbl"> Monitor iritasi dan ulserasi kulit di daerah perianal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_observasi][]" id="diare_observasi7" onclick="checkthis('diare_observasi7')" value="Monitor jumlah pengeluaran diare"><span class="lbl"> Monitor jumlah pengeluaran diare</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_observasi][]" id="diare_observasi8" onclick="checkthis('diare_observasi8')" value="Monitor keamanan makanan"><span class="lbl"> Monitor keamanan penyiapan makanan</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_terapeutik][]" id="diare_terapeutik1" onclick="checkthis('diare_terapeutik1')" value="Berikan cairan oral"><span class="lbl"> Berikan asupan cairan oral (mis: larutan garam gula, oralit, pedialyte, renalite)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_terapeutik][]" id="diare_terapeutik2" onclick="checkthis('diare_terapeutik2')" value="Pasang jalur IV"><span class="lbl"> Pasang jalur intravena</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_terapeutik][]" id="diare_terapeutik3" onclick="checkthis('diare_terapeutik3')" value="Berikan cairan IV"><span class="lbl"> Berikan cairan intravena</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_terapeutik][]" id="diare_terapeutik4" onclick="checkthis('diare_terapeutik4')" value="Berikan cairan IV RA RL"><span class="lbl"> Berikan cairan intravena (mis: ringer asetat, ringer laktat) jika perlu</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_terapeutik][]" id="diare_terapeutik5" onclick="checkthis('diare_terapeutik5')" value="Ambil sample darah"><span class="lbl"> Ambil sample darah untuk pemeriksaan darah lengkap dan elektrolit</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_terapeutik][]" id="diare_terapeutik6" onclick="checkthis('diare_terapeutik6')" value="Ambil sample faeses"><span class="lbl"> Ambil sample faeses untuk kultur, jika perlu</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_edukasi][]" id="diare_edukasi1" onclick="checkthis('diare_edukasi1')" value="Makanan porsi kecil"><span class="lbl"> Anjurkan makanan porsi kecil dan sering secara bertahap</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_edukasi][]" id="diare_edukasi2" onclick="checkthis('diare_edukasi2')" value="Hindari makanan tertentu"><span class="lbl"> Anjurkan menghindari makanan pembentuk gas, pedas, dan mengandung laktosa</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_edukasi][]" id="diare_edukasi3" onclick="checkthis('diare_edukasi3')" value="Melanjutkan ASI"><span class="lbl"> Anjurkan melanjutkan pemberian ASI</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_kolaborasi][]" id="diare_kolaborasi1" onclick="checkthis('diare_kolaborasi1')" value="Obat antimotilitas"><span class="lbl"> Kolaborasi pemberian obat antimotilitas (mis: loperamide, difenoksilat)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_kolaborasi][]" id="diare_kolaborasi2" onclick="checkthis('diare_kolaborasi2')" value="Obat antispasmodik"><span class="lbl"> Kolaborasi pemberian obat antispasmodik/spasmolitik (mis: papaverin, ekstrak belladonna, meberverin)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_72[diare_kolaborasi][]" id="diare_kolaborasi3" onclick="checkthis('diare_kolaborasi3')" value="Obat pengeras faeses"><span class="lbl"> Kolaborasi pemberian obat pengeras faeses (mis: atapulgit, smektit, koalin-pektin)</span></label></div>
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
        <input type="text" class="input_type" name="form_72[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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