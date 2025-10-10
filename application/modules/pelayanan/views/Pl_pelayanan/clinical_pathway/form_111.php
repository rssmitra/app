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
      var hiddenInputName = 'form_111[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: RISIKO DISFUNGSI NEUROVASKULER PERIFER</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Berisiko mengalami gangguan sirkulasi, sensasi, dan pergerakan pada ekstremitas.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- FAKTOR RISIKO -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>FAKTOR RISIKO (Dibuktikan dengan):</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[penyebab][]" id="nv_risk1" onclick="checkthis('nv_risk1')" value="Hiperglikemia"><span class="lbl"> Hiperglikemia</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[penyebab][]" id="nv_risk2" onclick="checkthis('nv_risk2')" value="Obstruksi vaskuler"><span class="lbl"> Obstruksi vaskuler</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[penyebab][]" id="nv_risk3" onclick="checkthis('nv_risk3')" value="Fraktur"><span class="lbl"> Fraktur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[penyebab][]" id="nv_risk4" onclick="checkthis('nv_risk4')" value="Imobilisasi"><span class="lbl"> Imobilisasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[penyebab][]" id="nv_risk5" onclick="checkthis('nv_risk5')" value="Penekanan mekanis (mis. torniket, gips, balutan, restraint)"><span class="lbl"> Penekanan mekanis (mis. torniket, gips, balutan, restraint)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[penyebab][]" id="nv_risk6" onclick="checkthis('nv_risk6')" value="Pembedahan ortopedi"><span class="lbl"> Pembedahan ortopedi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[penyebab][]" id="nv_risk7" onclick="checkthis('nv_risk7')" value="Trauma"><span class="lbl"> Trauma</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[penyebab][]" id="nv_risk8" onclick="checkthis('nv_risk8')" value="Luka bakar"><span class="lbl"> Luka bakar</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_111[nv_intervensi_selama]" id="nv_intervensi_selama" onchange="fillthis('nv_intervensi_selama')" style="width:10%;">,
          Neurovaskuler Perifer meningkat (L.06051), dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[kriteria_hasil][]" id="nv_krit1" onclick="checkthis('nv_krit1')" value="Sirkulasi arteri meningkat"><span class="lbl"> Sirkulasi arteri meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[kriteria_hasil][]" id="nv_krit2" onclick="checkthis('nv_krit2')" value="Sirkulasi vena meningkat"><span class="lbl"> Sirkulasi vena meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[kriteria_hasil][]" id="nv_krit3" onclick="checkthis('nv_krit3')" value="Pergerakan sendi meningkat"><span class="lbl"> Pergerakan sendi meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[kriteria_hasil][]" id="nv_krit4" onclick="checkthis('nv_krit4')" value="Pergerakan ekstremitas meningkat"><span class="lbl"> Pergerakan ekstremitas meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[kriteria_hasil][]" id="nv_krit5" onclick="checkthis('nv_krit5')" value="Nyeri menurun"><span class="lbl"> Nyeri menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[kriteria_hasil][]" id="nv_krit6" onclick="checkthis('nv_krit6')" value="Perdarahan menurun"><span class="lbl"> Perdarahan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[kriteria_hasil][]" id="nv_krit7" onclick="checkthis('nv_krit7')" value="TTV membaik"><span class="lbl"> TTV membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[kriteria_hasil][]" id="nv_krit8" onclick="checkthis('nv_krit8')" value="Warna kulit membaik"><span class="lbl"> Warna kulit membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[kriteria_hasil][]" id="nv_krit9" onclick="checkthis('nv_krit9')" value="Luka tekan membaik"><span class="lbl"> Luka tekan membaik</span></label></div>
      </td>
    </tr>

  </tbody>
</table>
<br>
<!-- END -->





<!-- PENGATURAN POSISI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>

  <tbody>
    <!-- TINDAKAN -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Pengaturan Posisi</b><br>
        <i>(Menempatkan bagian tubuh untuk meningkatkan kesehatan fisiologis dan/atau psikologis)</i><br>
        <b>(I.01019)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_observasi][]" id="posisi_observasi1" onclick="checkthis('posisi_observasi1')" value="Monitor status oksigenasi sebelum dan sesudah mengubah posisi"><span class="lbl"> Monitor status oksigenasi sebelum dan sesudah mengubah posisi</span></label></div>
        <div style="margin-top:5px;">
          Lainnya: <input type="text" class="input_type" name="form_111[posisi_observasi_lain]" id="posisi_observasi_lain" style="width:80%;" placeholder="Isi tindakan lain...">
        </div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_terapeutik][]" id="posisi_terapeutik1" onclick="checkthis('posisi_terapeutik1')" value="Tempatkan pada tempat tidur yang tepat"><span class="lbl"> Tempatkan pada tempat tidur yang tepat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_terapeutik][]" id="posisi_terapeutik2" onclick="checkthis('posisi_terapeutik2')" value="Tempatkan bel dalam jangkauan"><span class="lbl"> Tempatkan bel dalam jangkauan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_terapeutik][]" id="posisi_terapeutik3" onclick="checkthis('posisi_terapeutik3')" value="Atur posisi yang mengurangi sesak"><span class="lbl"> Atur posisi yang mengurangi sesak</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_terapeutik][]" id="posisi_terapeutik4" onclick="checkthis('posisi_terapeutik4')" value="Tinggikan tempat tidur bagian kepala berjalan"><span class="lbl"> Tinggikan tempat tidur bagian kepala berjalan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_terapeutik][]" id="posisi_terapeutik5" onclick="checkthis('posisi_terapeutik5')" value="Berikan bantal yang tepat pada leher"><span class="lbl"> Berikan bantal yang tepat pada leher</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_terapeutik][]" id="posisi_terapeutik6" onclick="checkthis('posisi_terapeutik6')" value="Motivasi terlibat dalam perubahan posisi"><span class="lbl"> Motivasi terlibat dalam perubahan posisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_terapeutik][]" id="posisi_terapeutik7" onclick="checkthis('posisi_terapeutik7')" value="Minimalkan gesekan dan tarikan saat mengubah posisi"><span class="lbl"> Minimalkan gesekan dan tarikan saat mengubah posisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_terapeutik][]" id="posisi_terapeutik8" onclick="checkthis('posisi_terapeutik8')" value="Ubah posisi setiap 2 jam"><span class="lbl"> Ubah posisi setiap 2 jam</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_terapeutik][]" id="posisi_terapeutik9" onclick="checkthis('posisi_terapeutik9')" value="Jadwalkan secara tertulis untuk perubahan posisi"><span class="lbl"> Jadwalkan secara tertulis untuk perubahan posisi</span></label></div>
        <div style="margin-top:5px;">
          Lainnya: <input type="text" class="input_type" name="form_111[posisi_terapeutik_lain]" id="posisi_terapeutik_lain" style="width:80%;" placeholder="Isi tindakan lain...">
        </div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_edukasi][]" id="posisi_edukasi1" onclick="checkthis('posisi_edukasi1')" value="Informasikan saat akan dilakukan perubahan posisi"><span class="lbl"> Informasikan saat akan dilakukan perubahan posisi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_edukasi][]" id="posisi_edukasi2" onclick="checkthis('posisi_edukasi2')" value="Ajarkan cara menggunakan postur yang baik dan mekanika tubuh yang baik selama melakukan perubahan posisi"><span class="lbl"> Ajarkan cara menggunakan postur yang baik dan mekanika tubuh yang baik selama melakukan perubahan posisi</span></label></div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Kolaborasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_111[posisi_kolaborasi][]" id="posisi_kolaborasi1" onclick="checkthis('posisi_kolaborasi1')" value="Kolaborasi pemberian premedikasi sebelum mengubah posisi"><span class="lbl"> Kolaborasi pemberian premedikasi sebelum mengubah posisi</span></label></div>
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
        <input type="text" class="input_type" name="form_111[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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