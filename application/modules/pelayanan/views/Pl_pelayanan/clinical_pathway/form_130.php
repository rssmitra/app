<script>
jQuery(function($) {  

  // pastikan tidak ada duplikasi datepicker
  $('.date-picker').datepicker('destroy'); 

  // aktifkan hanya sekali
  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: 'yyyy-mm-dd'
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
      var hiddenInputName = 'form_130[ttd_' + role + ']';
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


<!--- HEMODIALISIS -->
<?php echo $header; ?>
<hr>
<br>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<div style="text-align:center; font-size:15px; font-weight:bold; margin-bottom:10px;">
  FORM PENGKAJIAN RISIKO JATUH GERIATRI<br>
</div>

<table style="width:100%; border-collapse:collapse; font-size:13px; border:1px solid black;">
  <thead style="background-color:#e8e8e8; text-align:center; font-weight:bold;">
    <tr>
      <td colspan="9" style="border:1px solid black;">ONTARIO MODIFIED STRATIFY - SIDNEY SCORING</td>
    </tr>
    <tr>
      <td style="border:1px solid black; width:5%;">NO</td>
      <td style="border:1px solid black; width:35%;">FAKTOR RISIKO</td>
      <td style="border:1px solid black; width:20%;">YA / TIDAK</td>
      <td style="border:1px solid black; width:10%;">NILAI</td>
      <td colspan="5" style="border:1px solid black; width:30%;">SKOR</td>
    </tr>
  </thead>

  <tbody>
    <!-- 1 -->
    <tr>
      <td style="border:1px solid black; text-align:center;">1</td>
      <td style="border:1px solid black;">Riwayat jatuh dalam 3 bulan terakhir</td>
      <td style="border:1px solid black; text-align:center;">
        <label><input type="checkbox" name="form_130[jatuh_ya]" id="jatuh_ya" onchange="fillthis('jatuh_ya')"> Ya</label><br>
        <label><input type="checkbox" name="form_130[jatuh_tidak]" id="jatuh_tidak" onchange="fillthis('jatuh_tidak')"> Tidak</label>
      </td>
      <td style="border:1px solid black; text-align:center;">14</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor1a]" id="skor1a" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor1b]" id="skor1b" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor1c]" id="skor1c" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor1d]" id="skor1d" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor1e]" id="skor1e" style="width:100%; text-align:center;"></td>
    </tr>

    <!-- 2 -->
    <tr>
      <td style="border:1px solid black; text-align:center;">2</td>
      <td style="border:1px solid black;">Diagnosis sekunder ≥ 1</td>
      <td style="border:1px solid black; text-align:center;">
        <label><input type="checkbox" name="form_130[diag_ya]" id="" onchange="fillthis('diag_ya')"> Ya</label><br>
        <label><input type="checkbox" name="form_130[diag_tidak]" id="" onchange="fillthis('diag_tidak')"> Tidak</label>
      </td>
      <td style="border:1px solid black; text-align:center;">3</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor2a]" id="skor2a" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor2b]" id="skor2b" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor2c]" id="skor2c" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor2d]" id="skor2d" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor2e]" id="skor2e" style="width:100%; text-align:center;"></td>
    </tr>

    <!-- 3 -->
    <tr>
      <td style="border:1px solid black; text-align:center;">3</td>
      <td style="border:1px solid black;">Alat bantu jalan (walker, tongkat, dll)</td>
      <td style="border:1px solid black; text-align:center;">
        <label><input type="checkbox" name="form_130[alat_ya]" onchange="fillthis('alat_ya')"> Ya</label><br>
        <label><input type="checkbox" name="form_130[alat_tidak]" onchange="fillthis('alat_tidak')"> Tidak</label>
      </td>
      <td style="border:1px solid black; text-align:center;">6</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor3a]" id="skor3a" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor3b]" id="skor3b" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor3c]" id="skor3c" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor3d]" id="skor3d" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor3e]" id="skor3e" style="width:100%; text-align:center;"></td>
    </tr>

    <!-- 4 -->
    <tr>
      <td style="border:1px solid black; text-align:center;">4</td>
      <td style="border:1px solid black;">Terpasang infus / terapi intravena</td>
      <td style="border:1px solid black; text-align:center;">
        <label><input type="checkbox" name="form_130[infus_ya]" onchange="fillthis('infus_ya')"> Ya</label><br>
        <label><input type="checkbox" name="form_130[infus_tidak]" onchange="fillthis('infus_tidak')"> Tidak</label>
      </td>
      <td style="border:1px solid black; text-align:center;">20</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor4a]" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor4b]" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor4c]" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor4d]" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor4e]" style="width:100%; text-align:center;"></td>
    </tr>

    <!-- 5 -->
    <tr>
      <td style="border:1px solid black; text-align:center;">5</td>
      <td style="border:1px solid black;">Status mental (bingung, lupa, disorientasi)</td>
      <td style="border:1px solid black; text-align:center;">
        <label><input type="checkbox" name="form_130[mental_ya]" onchange="fillthis('mental_ya')"> Ya</label><br>
        <label><input type="checkbox" name="form_130[mental_tidak]" onchange="fillthis('mental_tidak')"> Tidak</label>
      </td>
      <td style="border:1px solid black; text-align:center;">15</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor5a]" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor5b]" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor5c]" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor5d]" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor5e]" style="width:100%; text-align:center;"></td>
    </tr>

    <!-- 6 -->
    <tr>
      <td style="border:1px solid black; text-align:center;">6</td>
      <td style="border:1px solid black;">Mobilitas terbatas / gangguan berjalan</td>
      <td style="border:1px solid black; text-align:center;">
        <label><input type="checkbox" name="form_130[mobilitas_ya]" onchange="fillthis('mobilitas_ya')"> Ya</label><br>
        <label><input type="checkbox" name="form_130[mobilitas_tidak]" onchange="fillthis('mobilitas_tidak')"> Tidak</label>
      </td>
      <td style="border:1px solid black; text-align:center;">8</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor6a]" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor6b]" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor6c]" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor6d]" style="width:100%; text-align:center;"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type" name="form_130[skor6e]" style="width:100%; text-align:center;"></td>
    </tr>
  </tbody>
</table>




<!-- MODAL TTD -->
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

