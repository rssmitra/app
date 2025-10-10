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
      var hiddenInputName = 'form_102[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: DEFISIT PENGETAHUAN</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Ketiadaan atau kurangnya informasi kognitif yang berkaitan dengan topik tertentu.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[penyebab][]" id="pengetahuan_penyebab1" onclick="checkthis('pengetahuan_penyebab1')" value="Keterbatasan kognitif"><span class="lbl"> Keterbatasan kognitif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[penyebab][]" id="pengetahuan_penyebab2" onclick="checkthis('pengetahuan_penyebab2')" value="Gangguan fungsi kognitif"><span class="lbl"> Gangguan fungsi kognitif</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[penyebab][]" id="pengetahuan_penyebab3" onclick="checkthis('pengetahuan_penyebab3')" value="Kekeliruan mengikuti anjuran"><span class="lbl"> Kekeliruan mengikuti anjuran</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[penyebab][]" id="pengetahuan_penyebab4" onclick="checkthis('pengetahuan_penyebab4')" value="Kurang terpapar informasi"><span class="lbl"> Kurang terpapar informasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[penyebab][]" id="pengetahuan_penyebab5" onclick="checkthis('pengetahuan_penyebab5')" value="Kurang minat dalam belajar"><span class="lbl"> Kurang minat dalam belajar</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[penyebab][]" id="pengetahuan_penyebab6" onclick="checkthis('pengetahuan_penyebab6')" value="Kurang mampu mengingat"><span class="lbl"> Kurang mampu mengingat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[penyebab][]" id="pengetahuan_penyebab7" onclick="checkthis('pengetahuan_penyebab7')" value="Ketidaktahuan menemukan sumber informasi"><span class="lbl"> Ketidaktahuan menemukan sumber informasi</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_102[pengetahuan_intervensi_selama]" id="pengetahuan_intervensi_selama" onchange="fillthis('pengetahuan_intervensi_selama')" style="width:10%;">
          , tingkat pengetahuan meningkat (L.12111) dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[kriteria_hasil][]" id="pengetahuan_krit1" onclick="checkthis('pengetahuan_krit1')" value="Perilaku sesuai anjuran meningkat"><span class="lbl"> Perilaku sesuai anjuran meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[kriteria_hasil][]" id="pengetahuan_krit2" onclick="checkthis('pengetahuan_krit2')" value="Verbalisasi minat dalam belajar meningkat"><span class="lbl"> Verbalisasi minat dalam belajar meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[kriteria_hasil][]" id="pengetahuan_krit3" onclick="checkthis('pengetahuan_krit3')" value="Kemampuan menjelaskan pengetahuan tentang suatu topik meningkat"><span class="lbl"> Kemampuan menjelaskan pengetahuan tentang suatu topik meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[kriteria_hasil][]" id="pengetahuan_krit4" onclick="checkthis('pengetahuan_krit4')" value="Perilaku sesuai dengan pengetahuan meningkat"><span class="lbl"> Perilaku sesuai dengan pengetahuan meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[kriteria_hasil][]" id="pengetahuan_krit5" onclick="checkthis('pengetahuan_krit5')" value="Pertanyaan tentang masalah yang dihadapi menurun"><span class="lbl"> Pertanyaan tentang masalah yang dihadapi menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[kriteria_hasil][]" id="pengetahuan_krit6" onclick="checkthis('pengetahuan_krit6')" value="Persepsi yang keliru terhadap masalah menurun"><span class="lbl"> Persepsi yang keliru terhadap masalah menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[kriteria_hasil][]" id="pengetahuan_krit7" onclick="checkthis('pengetahuan_krit7')" value="Perilaku membaik"><span class="lbl"> Perilaku membaik</span></label></div>
      </td>
    </tr>

    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Dibuktikan dengan:</b><br>

        <p><b>Gejala dan Tanda Mayor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[mayor_subjektif][]" id="pengetahuan_mayor_sub1" onclick="checkthis('pengetahuan_mayor_sub1')" value="Menanyakan masalah yang dihadapi"><span class="lbl"> Menanyakan masalah yang dihadapi</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[mayor_objektif][]" id="pengetahuan_mayor_obj1" onclick="checkthis('pengetahuan_mayor_obj1')" value="Menunjukkan perilaku tidak sesuai anjuran"><span class="lbl"> Menunjukkan perilaku tidak sesuai anjuran</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[mayor_objektif][]" id="pengetahuan_mayor_obj2" onclick="checkthis('pengetahuan_mayor_obj2')" value="Menunjukkan persepsi yang keliru terhadap masalah"><span class="lbl"> Menunjukkan persepsi yang keliru terhadap masalah</span></label></div>
          </div>
        </div>

        <hr>
        <p><b>Gejala dan Tanda Minor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <i>(Tidak tersedia)</i>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[minor_objektif][]" id="pengetahuan_minor_obj1" onclick="checkthis('pengetahuan_minor_obj1')" value="Menjalani pemeriksaan yang tidak tepat"><span class="lbl"> Menjalani pemeriksaan yang tidak tepat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_102[minor_objektif][]" id="pengetahuan_minor_obj2" onclick="checkthis('pengetahuan_minor_obj2')" value="Menunjukkan perilaku berlebihan (mis: apatis, bermusuhan, agitasi, histeria)"><span class="lbl"> Menunjukkan perilaku berlebihan (mis: apatis, bermusuhan, agitasi, histeria)</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->



<!-- PROMOSI KESEHATAN: EDUKASI KESEHATAN -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>

    <!-- Edukasi Kesehatan -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Edukasi Kesehatan</b><br>
        <i>(Mengajarkan pengelolaan faktor risiko penyakit dan perilaku hidup bersih serta sehat)</i><br>
        <b>(I.12383)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_102[edukasi_observasi][]" id="edukasi_observasi1" onclick="checkthis('edukasi_observasi1')" value="Identifikasi kesiapan dan kemampuan menerima informasi"><span class="lbl"> Identifikasi kesiapan dan kemampuan menerima informasi</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_102[edukasi_observasi][]" id="edukasi_observasi2" onclick="checkthis('edukasi_observasi2')" value="Identifikasi faktor-faktor yang meningkatkan dan menurunkan motivasi perilaku hidup bersih dan sehat"><span class="lbl"> Identifikasi faktor-faktor yang meningkatkan dan menurunkan motivasi perilaku hidup bersih dan sehat</span></label>
        </div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_102[edukasi_observasi_lain]" id="edukasi_observasi_lain" style="width: 98%;">
        </div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_102[edukasi_terapeutik][]" id="edukasi_terapeutik1" onclick="checkthis('edukasi_terapeutik1')" value="Sediakan materi dan media pendidikan kesehatan"><span class="lbl"> Sediakan materi dan media pendidikan kesehatan</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_102[edukasi_terapeutik][]" id="edukasi_terapeutik2" onclick="checkthis('edukasi_terapeutik2')" value="Jadwalkan pendkes sesuai kesepakatan"><span class="lbl"> Jadwalkan pendkes sesuai kesepakatan</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_102[edukasi_terapeutik][]" id="edukasi_terapeutik3" onclick="checkthis('edukasi_terapeutik3')" value="Berikan kesempatan untuk bertanya"><span class="lbl"> Berikan kesempatan untuk bertanya</span></label>
        </div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_102[edukasi_terapeutik_lain]" id="edukasi_terapeutik_lain" style="width: 98%;">
        </div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_102[edukasi_edukasi][]" id="edukasi_edukasi1" onclick="checkthis('edukasi_edukasi1')" value="Jelaskan faktor risiko yang dapat mempengaruhi kesehatan"><span class="lbl"> Jelaskan faktor risiko yang dapat mempengaruhi kesehatan</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_102[edukasi_edukasi][]" id="edukasi_edukasi2" onclick="checkthis('edukasi_edukasi2')" value="Ajarkan perilaku hidup bersih dan sehat"><span class="lbl"> Ajarkan perilaku hidup bersih dan sehat</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_102[edukasi_edukasi][]" id="edukasi_edukasi3" onclick="checkthis('edukasi_edukasi3')" value="Ajarkan strategi yang dapat digunakan untuk meningkatkan perilaku hidup bersih dan sehat"><span class="lbl"> Ajarkan strategi yang dapat digunakan untuk meningkatkan perilaku hidup bersih dan sehat</span></label>
        </div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_102[edukasi_edukasi_lain]" id="edukasi_edukasi_lain" style="width: 98%;">
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
        <input type="text" class="input_type" name="form_102[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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