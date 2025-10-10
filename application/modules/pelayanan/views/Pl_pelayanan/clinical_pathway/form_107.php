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
      var hiddenInputName = 'form_107[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: KONSTIPASI</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Penurunan defekasi normal yang disertai pengeluaran feses sulit dan tidak tuntas, serta feses kering dan banyak.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>
        <b>Fisiologis:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab1" onclick="checkthis('konstipasi_penyebab1')" value="Penurunan motilitas gastrointestinal"><span class="lbl"> Penurunan motilitas gastrointestinal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab2" onclick="checkthis('konstipasi_penyebab2')" value="Ketidakcukupan diet"><span class="lbl"> Ketidakcukupan diet</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab3" onclick="checkthis('konstipasi_penyebab3')" value="Ketidakcukupan asupan diet"><span class="lbl"> Ketidakcukupan asupan diet</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab4" onclick="checkthis('konstipasi_penyebab4')" value="Ketidakcukupan asupan cairan"><span class="lbl"> Ketidakcukupan asupan cairan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab5" onclick="checkthis('konstipasi_penyebab5')" value="Kelemahan otot abdomen"><span class="lbl"> Kelemahan otot abdomen</span></label></div>

        <b>Psikologis:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab6" onclick="checkthis('konstipasi_penyebab6')" value="Konfusi"><span class="lbl"> Konfusi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab7" onclick="checkthis('konstipasi_penyebab7')" value="Depresi"><span class="lbl"> Depresi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab8" onclick="checkthis('konstipasi_penyebab8')" value="Gangguan emosional"><span class="lbl"> Gangguan emosional</span></label></div>

        <b>Situasional:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab9" onclick="checkthis('konstipasi_penyebab9')" value="Perubahan kebiasaan makan (jenis makanan, jadwal makan)"><span class="lbl"> Perubahan kebiasaan makan (jenis makanan, jadwal makan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab10" onclick="checkthis('konstipasi_penyebab10')" value="Ketidakadekuatan toileting"><span class="lbl"> Ketidakadekuatan toileting</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab11" onclick="checkthis('konstipasi_penyebab11')" value="Aktivitas fisik harian kurang dari yang dianjurkan"><span class="lbl"> Aktivitas fisik harian kurang dari yang dianjurkan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab12" onclick="checkthis('konstipasi_penyebab12')" value="Penyalahgunaan laksatif"><span class="lbl"> Penyalahgunaan laksatif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab13" onclick="checkthis('konstipasi_penyebab13')" value="Efek agen farmakologis"><span class="lbl"> Efek agen farmakologis</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab14" onclick="checkthis('konstipasi_penyebab14')" value="Ketidakteraturan kebiasaan defekasi"><span class="lbl"> Ketidakteraturan kebiasaan defekasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab15" onclick="checkthis('konstipasi_penyebab15')" value="Kebiasaan menahan dorongan defekasi"><span class="lbl"> Kebiasaan menahan dorongan defekasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[penyebab][]" id="konstipasi_penyebab16" onclick="checkthis('konstipasi_penyebab16')" value="Perubahan lingkungan"><span class="lbl"> Perubahan lingkungan</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_107[konstipasi_intervensi_selama]" id="konstipasi_intervensi_selama" onchange="fillthis('konstipasi_intervensi_selama')" style="width:10%;">,
          konstipasi membaik (L.04033), dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[kriteria_hasil][]" id="konstipasi_krit1" onclick="checkthis('konstipasi_krit1')" value="Kontrol pengeluaran feses meningkat"><span class="lbl"> Kontrol pengeluaran feses meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[kriteria_hasil][]" id="konstipasi_krit2" onclick="checkthis('konstipasi_krit2')" value="Keluhan defekasi lama dan sulit menurun"><span class="lbl"> Keluhan defekasi lama dan sulit menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[kriteria_hasil][]" id="konstipasi_krit3" onclick="checkthis('konstipasi_krit3')" value="Mengejan saat defekasi menurun"><span class="lbl"> Mengejan saat defekasi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[kriteria_hasil][]" id="konstipasi_krit4" onclick="checkthis('konstipasi_krit4')" value="Distensi abdomen menurun"><span class="lbl"> Distensi abdomen menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[kriteria_hasil][]" id="konstipasi_krit5" onclick="checkthis('konstipasi_krit5')" value="Teraba massa pada rektal menurun"><span class="lbl"> Teraba massa pada rektal menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[kriteria_hasil][]" id="konstipasi_krit6" onclick="checkthis('konstipasi_krit6')" value="Nyeri abdomen menurun"><span class="lbl"> Nyeri abdomen menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[kriteria_hasil][]" id="konstipasi_krit7" onclick="checkthis('konstipasi_krit7')" value="Keram abdomen menurun"><span class="lbl"> Keram abdomen menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[kriteria_hasil][]" id="konstipasi_krit8" onclick="checkthis('konstipasi_krit8')" value="Konsistensi feses membaik"><span class="lbl"> Konsistensi feses membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[kriteria_hasil][]" id="konstipasi_krit9" onclick="checkthis('konstipasi_krit9')" value="Frekuensi defekasi membaik"><span class="lbl"> Frekuensi defekasi membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[kriteria_hasil][]" id="konstipasi_krit10" onclick="checkthis('konstipasi_krit10')" value="Peristaltik usus membaik"><span class="lbl"> Peristaltik usus membaik</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[mayor_subjektif][]" id="konstipasi_mayor_sub1" onclick="checkthis('konstipasi_mayor_sub1')" value="Defekasi kurang dari 2x seminggu"><span class="lbl"> Defekasi kurang dari 2x seminggu</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[mayor_subjektif][]" id="konstipasi_mayor_sub2" onclick="checkthis('konstipasi_mayor_sub2')" value="Pengeluaran feses lama dan sulit"><span class="lbl"> Pengeluaran feses lama dan sulit</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif :</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[mayor_objektif][]" id="konstipasi_mayor_obj1" onclick="checkthis('konstipasi_mayor_obj1')" value="Feses keras"><span class="lbl"> Feses keras</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[mayor_objektif][]" id="konstipasi_mayor_obj2" onclick="checkthis('konstipasi_mayor_obj2')" value="Peristaltik usus menurun"><span class="lbl"> Peristaltik usus menurun</span></label></div>
          </div>
        </div>

        <hr>
        <p><b>Tanda dan Gejala Minor:</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif :</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[minor_subjektif][]" id="konstipasi_minor_sub1" onclick="checkthis('konstipasi_minor_sub1')" value="Mengejan saat defekasi"><span class="lbl"> Mengejan saat defekasi</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif :</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[minor_objektif][]" id="konstipasi_minor_obj1" onclick="checkthis('konstipasi_minor_obj1')" value="Distensi abdomen"><span class="lbl"> Distensi abdomen</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[minor_objektif][]" id="konstipasi_minor_obj2" onclick="checkthis('konstipasi_minor_obj2')" value="Kelemahan umum"><span class="lbl"> Kelemahan umum</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[minor_objektif][]" id="konstipasi_minor_obj3" onclick="checkthis('konstipasi_minor_obj3')" value="Teraba massa pada rektal"><span class="lbl"> Teraba massa pada rektal</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->



<!-- MANAJEMEN KONSTIPASI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>

  <tbody>
    <!-- JUDUL UTAMA -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Manajemen Konstipasi</b><br>
        <i>(Mengidentifikasi dan mengelola pencegahan serta mengatasi sembelit)</i><br>
        <b>(I.04155)</b>
      </td>
    </tr>

    <!-- OBSERVASI -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_observasi][]" id="konstipasi_observasi1" onclick="checkthis('konstipasi_observasi1')" value="Periksa tanda dan gejala konstipasi"><span class="lbl"> Periksa tanda dan gejala konstipasi</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_observasi][]" id="konstipasi_observasi2" onclick="checkthis('konstipasi_observasi2')" value="Periksa pergerakan usus, karakteristik feses (konsisten, bentuk, volume dan warna)"><span class="lbl"> Periksa pergerakan usus, karakteristik feses (konsisten, bentuk, volume dan warna)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_observasi][]" id="konstipasi_observasi3" onclick="checkthis('konstipasi_observasi3')" value="Identifikasi faktor risiko konstipasi, misal: obat-obatan, tirah baring, diit rendah serat"><span class="lbl"> Identifikasi faktor risiko konstipasi, misal: obat-obatan, tirah baring, diit rendah serat</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_observasi][]" id="konstipasi_observasi4" onclick="checkthis('konstipasi_observasi4')" value="Monitor tanda dan gejala rupture usus atau peritonitis"><span class="lbl"> Monitor tanda dan gejala rupture usus atau peritonitis</span></label></div>
      </td>
    </tr>

    <!-- TERAPEUTIK -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_terapeutik][]" id="konstipasi_terapeutik1" onclick="checkthis('konstipasi_terapeutik1')" value="Anjurkan diit tinggi serat"><span class="lbl"> Anjurkan diit tinggi serat</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_terapeutik][]" id="konstipasi_terapeutik2" onclick="checkthis('konstipasi_terapeutik2')" value="Lakukan evakuasi feses secara manual"><span class="lbl"> Lakukan evakuasi feses secara manual</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_terapeutik][]" id="konstipasi_terapeutik3" onclick="checkthis('konstipasi_terapeutik3')" value="Lakukan massage abdomen"><span class="lbl"> Lakukan massage abdomen</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_terapeutik][]" id="konstipasi_terapeutik4" onclick="checkthis('konstipasi_terapeutik4')" value="Berikan enema atau irigasi"><span class="lbl"> Berikan enema atau irigasi</span></label></div>
      </td>
    </tr>

    <!-- EDUKASI -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_edukasi][]" id="konstipasi_edukasi1" onclick="checkthis('konstipasi_edukasi1')" value="Jelaskan etiologi masalah dan alasan tindakan"><span class="lbl"> Jelaskan etiologi masalah dan alasan tindakan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_edukasi][]" id="konstipasi_edukasi2" onclick="checkthis('konstipasi_edukasi2')" value="Latih buang air besar secara teratur"><span class="lbl"> Latih buang air besar secara teratur</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_edukasi][]" id="konstipasi_edukasi3" onclick="checkthis('konstipasi_edukasi3')" value="Anjurkan peningkatan asupan cairan"><span class="lbl"> Anjurkan peningkatan asupan cairan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_edukasi][]" id="konstipasi_edukasi4" onclick="checkthis('konstipasi_edukasi4')" value="Ajarkan cara mengatasi konstipasi"><span class="lbl"> Ajarkan cara mengatasi konstipasi</span></label></div>
      </td>
    </tr>

    <!-- KOLABORASI -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_kolaborasi][]" id="konstipasi_kolaborasi1" onclick="checkthis('konstipasi_kolaborasi1')" value="Konsultasi dengan tim medis, tentang penurunan dan peningkatan frekuensi suara usus"><span class="lbl"> Konsultasi dengan tim medis, tentang penurunan dan peningkatan frekuensi suara usus</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_107[konstipasi_kolaborasi][]" id="konstipasi_kolaborasi2" onclick="checkthis('konstipasi_kolaborasi2')" value="Kolaborasi penggunaan obat pencahar"><span class="lbl"> Kolaborasi penggunaan obat pencahar</span></label></div>
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
        <input type="text" class="input_type" name="form_107[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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