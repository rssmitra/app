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
      var hiddenInputName = 'form_98[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: RISIKO JATUH</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Berisiko mengalami kerusakan fisik dan gangguan kesehatan akibat terjatuh
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- FAKTOR RISIKO -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>FAKTOR RISIKO (Dibuktikan dengan):</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko1" onclick="checkthis('rj_risiko1')" value="Usia ≥65th (pada dewasa) ≤2th (pada anak)"><span class="lbl"> Usia ≥65th (pada dewasa) ≤2th (pada anak)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko2" onclick="checkthis('rj_risiko2')" value="Riwayat jatuh"><span class="lbl"> Riwayat jatuh</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko3" onclick="checkthis('rj_risiko3')" value="Anggota gerak bawah buatan"><span class="lbl"> Anggota gerak bawah buatan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko4" onclick="checkthis('rj_risiko4')" value="Penggunaan alat bantu berjalan"><span class="lbl"> Penggunaan alat bantu berjalan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko5" onclick="checkthis('rj_risiko5')" value="Penurunan tingkat kesadaran"><span class="lbl"> Penurunan tingkat kesadaran</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko6" onclick="checkthis('rj_risiko6')" value="Perubahan fungsi kognitif"><span class="lbl"> Perubahan fungsi kognitif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko7" onclick="checkthis('rj_risiko7')" value="Lingkungan tidak aman"><span class="lbl"> Lingkungan tidak aman</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko8" onclick="checkthis('rj_risiko8')" value="Kondisi pasca operasi"><span class="lbl"> Kondisi pasca operasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko9" onclick="checkthis('rj_risiko9')" value="Hipotensi"><span class="lbl"> Hipotensi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko10" onclick="checkthis('rj_risiko10')" value="Perubahan kadar GD"><span class="lbl"> Perubahan kadar GD</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko11" onclick="checkthis('rj_risiko11')" value="Anemia"><span class="lbl"> Anemia</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko12" onclick="checkthis('rj_risiko12')" value="Kekuatan otot menurun"><span class="lbl"> Kekuatan otot menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko13" onclick="checkthis('rj_risiko13')" value="Gangguan keseimbangan"><span class="lbl"> Gangguan keseimbangan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko14" onclick="checkthis('rj_risiko14')" value="Gangguan penglihatan"><span class="lbl"> Gangguan penglihatan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko15" onclick="checkthis('rj_risiko15')" value="Neuropati"><span class="lbl"> Neuropati</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[faktor_risiko][]" id="rj_risiko16" onclick="checkthis('rj_risiko16')" value="Efek agen farmakologis (sedasi, alkohol, narkotika)"><span class="lbl"> Efek agen farmakologis (sedasi, alkohol, narkotika)</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_98[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Tingkat jatuh menurun (L.14138) dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[kriteria_hasil][]" id="rj_krit1" onclick="checkthis('rj_krit1')" value="Jatuh dari TT menurun"><span class="lbl"> Jatuh dari TT menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[kriteria_hasil][]" id="rj_krit2" onclick="checkthis('rj_krit2')" value="Jatuh saat berdiri menurun"><span class="lbl"> Jatuh saat berdiri menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[kriteria_hasil][]" id="rj_krit3" onclick="checkthis('rj_krit3')" value="Jatuh saat duduk menurun"><span class="lbl"> Jatuh saat duduk menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[kriteria_hasil][]" id="rj_krit4" onclick="checkthis('rj_krit4')" value="Jatuh saat berjalan menurun"><span class="lbl"> Jatuh saat berjalan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[kriteria_hasil][]" id="rj_krit5" onclick="checkthis('rj_krit5')" value="Jatuh saat di kamar mandi menurun"><span class="lbl"> Jatuh saat di kamar mandi menurun</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->





<!-- PENCEGAHAN JATUH -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Pencegahan Jatuh -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Pencegahan Jatuh</b><br>
        <i>(Mengidentifikasi dan menurunkan risiko terjatuh akibat perubahan kondisi fisik atau psikologis)</i><br>
        <b>(I.14540)</b>
      </td>
    </tr>

    <!-- Tindakan -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Tindakan</b><br>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>1</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_observasi][]" id="pj_observasi1" onclick="checkthis('pj_observasi1')" value="Identifikasi faktor risiko jatuh"><span class="lbl"> Identifikasi faktor risiko jatuh</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_observasi][]" id="pj_observasi2" onclick="checkthis('pj_observasi2')" value="Identifikasi risiko jatuh tiap shift atau sesuai dengan kebijakan RS"><span class="lbl"> Identifikasi risiko jatuh tiap shift atau sesuai dengan kebijakan RS</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_observasi][]" id="pj_observasi3" onclick="checkthis('pj_observasi3')" value="Identifikasi faktor lingkungan yang meningkatkan risiko jatuh"><span class="lbl"> Identifikasi faktor lingkungan yang meningkatkan risiko jatuh</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_observasi][]" id="pj_observasi4" onclick="checkthis('pj_observasi4')" value="Hitung risiko jatuh dengan menggunakan skala"><span class="lbl"> Hitung risiko jatuh dengan menggunakan skala</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>2</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_terapeutik][]" id="pj_terapeutik1" onclick="checkthis('pj_terapeutik1')" value="Orientasikan ruangan pada pasien dan keluarga"><span class="lbl"> Orientasikan ruangan pada pasien dan keluarga</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_terapeutik][]" id="pj_terapeutik2" onclick="checkthis('pj_terapeutik2')" value="Pastikan roda TT dan kursi roda selalu dalam keadaan terkunci"><span class="lbl"> Pastikan roda TT dan kursi roda selalu dalam keadaan terkunci</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_terapeutik][]" id="pj_terapeutik3" onclick="checkthis('pj_terapeutik3')" value="Pasang penjaga TT"><span class="lbl"> Pasang penjaga TT</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_terapeutik][]" id="pj_terapeutik4" onclick="checkthis('pj_terapeutik4')" value="Atur TT pada posisi terendah"><span class="lbl"> Atur TT pada posisi terendah</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_terapeutik][]" id="pj_terapeutik5" onclick="checkthis('pj_terapeutik5')" value="Tempatkan pasien berisiko tinggi jatuh dekat dengan pantauan perawat"><span class="lbl"> Tempatkan pasien berisiko tinggi jatuh dekat dengan pantauan perawat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_terapeutik][]" id="pj_terapeutik6" onclick="checkthis('pj_terapeutik6')" value="Gunakan alat bantu berjalan"><span class="lbl"> Gunakan alat bantu berjalan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_terapeutik][]" id="pj_terapeutik7" onclick="checkthis('pj_terapeutik7')" value="Dekatkan bel dalam jangkauan pasien"><span class="lbl"> Dekatkan bel dalam jangkauan pasien</span></label></div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align: center; border: 1px solid black; padding: 5px; vertical-align: top;"><b>3</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_edukasi][]" id="pj_edukasi1" onclick="checkthis('pj_edukasi1')" value="Anjurkan memanggil perawat jika membutuhkan bantuan"><span class="lbl"> Anjurkan memanggil perawat jika membutuhkan bantuan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_edukasi][]" id="pj_edukasi2" onclick="checkthis('pj_edukasi2')" value="Anjurkan menggunakan alas kaki yang tidak licin"><span class="lbl"> Anjurkan menggunakan alas kaki yang tidak licin</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_edukasi][]" id="pj_edukasi3" onclick="checkthis('pj_edukasi3')" value="Anjurkan berkonsentrasi untuk menjaga keseimbangan tubuh"><span class="lbl"> Anjurkan berkonsentrasi untuk menjaga keseimbangan tubuh</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_98[pj_edukasi][]" id="pj_edukasi4" onclick="checkthis('pj_edukasi4')" value="Ajarkan cara menggunakan bel"><span class="lbl"> Ajarkan cara menggunakan bel</span></label></div>
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
        <input type="text" class="input_type" name="form_98[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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