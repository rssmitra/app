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
      var hiddenInputName = 'form_103[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: DEFISIT PERAWATAN DIRI</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Tidak mampu melakukan atau menyelesaikan aktivitas perawatan diri.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[penyebab][]" id="perawatan_penyebab1" onclick="checkthis('perawatan_penyebab1')" value="Gangguan Muskuloskeletal"><span class="lbl"> Gangguan Muskuloskeletal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[penyebab][]" id="perawatan_penyebab2" onclick="checkthis('perawatan_penyebab2')" value="Gangguan Neuromuskuler"><span class="lbl"> Gangguan Neuromuskuler</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[penyebab][]" id="perawatan_penyebab3" onclick="checkthis('perawatan_penyebab3')" value="Kelemahan"><span class="lbl"> Kelemahan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[penyebab][]" id="perawatan_penyebab4" onclick="checkthis('perawatan_penyebab4')" value="Gangguan Psikologis/psikotik"><span class="lbl"> Gangguan Psikologis dsn/atau psikotik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[penyebab][]" id="perawatan_penyebab5" onclick="checkthis('perawatan_penyebab5')" value="Penurunan motivasi/minat"><span class="lbl"> Penurunan motivasi/minat</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_103[perawatan_intervensi_selama]" id="perawatan_intervensi_selama" onchange="fillthis('perawatan_intervensi_selama')" style="width:10%;">
          , maka Perawatan diri meningkat (L.11103) dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[kriteria_hasil][]" id="perawatan_krit1" onclick="checkthis('perawatan_krit1')" value="Kemampuan mandi meningkat"><span class="lbl"> Kemampuan mandi meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[kriteria_hasil][]" id="perawatan_krit2" onclick="checkthis('perawatan_krit2')" value="Kemampuan mengenakan pakaian meningkat"><span class="lbl"> Kemampuan mengenakan pakaian meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[kriteria_hasil][]" id="perawatan_krit3" onclick="checkthis('perawatan_krit3')" value="Kemampuan makan meningkat"><span class="lbl"> Kemampuan makan meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[kriteria_hasil][]" id="perawatan_krit4" onclick="checkthis('perawatan_krit4')" value="Kemampuan ke toilet meningkat"><span class="lbl"> Kemampuan ke toilet (BAB/BAK) meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[kriteria_hasil][]" id="perawatan_krit5" onclick="checkthis('perawatan_krit5')" value="Minat melakukan perawatan diri meningkat"><span class="lbl"> Minat melakukan perawatan diri meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[kriteria_hasil][]" id="perawatan_krit6" onclick="checkthis('perawatan_krit6')" value="Mempertahankan kebersihan diri meningkat"><span class="lbl"> Mempertahankan kebersihan diri meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[kriteria_hasil][]" id="perawatan_krit7" onclick="checkthis('perawatan_krit7')" value="Mempertahankan kebersihan mulut meningkat"><span class="lbl"> Mempertahankan kebersihan mulut meningkat</span></label></div>
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
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[mayor_subjektif][]" id="perawatan_mayor_sub1" onclick="checkthis('perawatan_mayor_sub1')" value="Menolak melakukan perawatan diri"><span class="lbl"> Menolak melakukan perawatan diri</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[mayor_objektif][]" id="perawatan_mayor_obj1" onclick="checkthis('perawatan_mayor_obj1')" value="Tidak mampu mandi/mengenakan pakaian/makan/ke toilet/berhias secara mandiri"><span class="lbl"> Tidak mampu mandi/mengenakan pakaian/makan/ke toilet/berhias secara mandiri</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_103[mayor_objektif][]" id="perawatan_mayor_obj2" onclick="checkthis('perawatan_mayor_obj2')" value="Minat melakukan perawatan diri kurang"><span class="lbl"> Minat melakukan perawatan diri kurang</span></label></div>
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
            <i>(Tidak tersedia)</i>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->




<!-- DUKUNGAN PERAWATAN DIRI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>

    <!-- Dukungan Perawatan Diri -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dukungan Perawatan Diri</b><br>
        <i>(Memfasilitasi pemenuhan kebutuhan perawatan diri)</i><br>
        <b>(I.11345)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_103[perawatan_observasi][]" id="perawatan_observasi1" onclick="checkthis('perawatan_observasi1')" value="Identifikasi kebiasaan aktifitas perawatan diri sesuai usia"><span class="lbl"> Identifikasi kebiasaan aktifitas perawatan diri sesuai usia</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_103[perawatan_observasi][]" id="perawatan_observasi2" onclick="checkthis('perawatan_observasi2')" value="Monitor tingkat kemandirian"><span class="lbl"> Monitor tingkat kemandirian</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_103[perawatan_observasi][]" id="perawatan_observasi3" onclick="checkthis('perawatan_observasi3')" value="Identifikasi kebutuhan alat bantu kebersihan diri, berpakaian, berhias dan makan"><span class="lbl"> Identifikasi kebutuhan alat bantu kebersihan diri, berpakaian, berhias dan makan</span></label>
        </div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_103[perawatan_observasi_lain]" id="perawatan_observasi_lain" style="width: 98%;">
        </div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_103[perawatan_terapeutik][]" id="perawatan_terapeutik1" onclick="checkthis('perawatan_terapeutik1')" value="Siapkan keperluan pribadi (sikat gigi dan sabun mandi)"><span class="lbl"> Siapkan keperluan pribadi (sikat gigi dan sabun mandi)</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_103[perawatan_terapeutik][]" id="perawatan_terapeutik2" onclick="checkthis('perawatan_terapeutik2')" value="Dampingi dalam melakukan perawatan diri sampai mandiri"><span class="lbl"> Dampingi dalam melakukan perawatan diri sampai mandiri</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_103[perawatan_terapeutik][]" id="perawatan_terapeutik3" onclick="checkthis('perawatan_terapeutik3')" value="Fasilitasi kemandirian, bantu jika tidak mampu melakukan perawatan diri"><span class="lbl"> Fasilitasi kemandirian, bantu jika tidak mampu melakukan perawatan diri</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_103[perawatan_terapeutik][]" id="perawatan_terapeutik4" onclick="checkthis('perawatan_terapeutik4')" value="Jadwalkan rutinitas perawatan diri berjalan"><span class="lbl"> Jadwalkan rutinitas perawatan diri berjalan</span></label>
        </div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_103[perawatan_terapeutik_lain]" id="perawatan_terapeutik_lain" style="width: 98%;">
        </div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_103[perawatan_edukasi][]" id="perawatan_edukasi1" onclick="checkthis('perawatan_edukasi1')" value="Anjurkan melakukan perawatan diri secara konsisten sesuai kemampuan"><span class="lbl"> Anjurkan melakukan perawatan diri secara konsisten sesuai kemampuan</span></label>
        </div>
        <div style="margin-top:5px;">
          <label>Lainnya:</label>
          <input type="text" class="input_type" name="form_103[perawatan_edukasi_lain]" id="perawatan_edukasi_lain" style="width: 98%;">
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
        <input type="text" class="input_type" name="form_103[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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