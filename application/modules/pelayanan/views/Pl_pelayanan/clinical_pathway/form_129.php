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
      var hiddenInputName = 'form_129[ttd_' + role + ']';
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

<div style="text-align:center; font-size:18px; font-weight:bold; margin-bottom:10px;">
HEMODIALISIS
</div>

<div style="font-size:13px; line-height:1.6;">

  <br>

  <table style="width:100%; border-collapse:collapse; border:1px solid black; font-size:13px;">
    <tr>
      <td style="width:30%; border:1px solid black; padding:5px;"><b>DIAGNOSA ETIOLOGI GGT</b></td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_129[etiologi_ggt]" id="etiologi_ggt" onchange="fillthis('etiologi_ggt')" style="width:100%;">
      </td>
    </tr>
    <tr style="background-color:#f2f2f2;">
      <td colspan="2" style="padding:5px; border:1px solid black;"><b>MULAI HD</b></td>
    </tr>
    <tr>
      <td style="width:150px; padding:5px; border:1px solid black;">Tanggal</td>
      <td style="padding:5px; border:1px solid black;">
        <input type="text" class="input_type" name="form_129[tanggal_mulai]" id="tanggal_mulai" onchange="fillthis('tanggal_mulai')" style="width:95%;">
      </td>
    </tr>
    <tr>
      <td style="padding:5px; border:1px solid black;">Inisiasi</td>
      <td style="padding:5px; border:1px solid black;">
        <input type="text" class="input_type" name="form_129[inisiasi]" id="inisiasi" onchange="fillthis('inisiasi')" style="width:95%;">
      </td>
    </tr>
    <tr>
      <td style="padding:5px; border:1px solid black;">Indikasi</td>
      <td style="padding:5px; border:1px solid black;">
        <input type="text" class="input_type" name="form_129[indikasi]" id="indikasi" onchange="fillthis('indikasi')" style="width:95%;">
      </td>
    </tr>
  </table>

  <br>

  <table style="width:100%; border-collapse:collapse; border:1px solid black; font-size:13px;">
    <tr style="background-color:#f2f2f2;">
      <td colspan="2" style="padding:5px; border:1px solid black;"><b>AKSES VASKULER</b></td>
    </tr>
    <tr>
      <td style="width:30%; border:1px solid black; padding:5px;"><b>1. CIMINO SHUNT</b></td>
      <td style="border:1px solid black; padding:5px;">
        Tgl. Operasi:
        <ol style="margin-left:20px;">
          <li><input type="text" class="input_type" name="form_129[cimino_1]" id="cimino_1" onchange="fillthis('cimino_1')" style="width:80%;"></li>
          <li><input type="text" class="input_type" name="form_129[cimino_2]" id="cimino_2" onchange="fillthis('cimino_2')" style="width:80%;"></li>
          <li><input type="text" class="input_type" name="form_129[cimino_3]" id="cimino_3" onchange="fillthis('cimino_3')" style="width:80%;"></li>
        </ol>
      </td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;"><b>2. DOUBLE LUMEN / MAHURKAR</b></td>
      <td style="border:1px solid black; padding:5px;">
        Tgl. Pemasangan:
        <ol style="margin-left:20px;">
          <li><input type="text" class="input_type" name="form_129[dl_1]" id="dl_1" onchange="fillthis('dl_1')" style="width:80%;"></li>
          <li><input type="text" class="input_type" name="form_129[dl_2]" id="dl_2" onchange="fillthis('dl_2')" style="width:80%;"></li>
          <li><input type="text" class="input_type" name="form_129[dl_3]" id="dl_3" onchange="fillthis('dl_3')" style="width:80%;"></li>
        </ol>
      </td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;"><b>3. AKSES FEMORAL</b></td>
      <td style="border:1px solid black; padding:5px;"><input type="text" class="input_type" name="form_129[akses_femoral]" id="akses_femoral" onchange="fillthis('akses_femoral')" style="width:80%;"></td>
    </tr>
  </table>

  <br>

  <table style="width:100%; border-collapse:collapse; border:1px solid black; font-size:13px;">
    <tr style="background-color:#f2f2f2;">
      <td colspan="4" style="padding:5px; border:1px solid black;"><b>DIAGNOSA PENYAKIT LAIN (KOMORBIDITAS)</b></td>
    </tr>
    <?php for ($i=1; $i<=5; $i++): ?>
    <tr>
      <td style="width:5%; border:1px solid black; text-align:center;"><?php echo $i; ?>.</td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_129[komorbid_<?php echo $i; ?>]" id="komorbid_<?php echo $i; ?>" onchange="fillthis('komorbid_<?php echo $i; ?>')" style="width:95%;">
      </td>
      <td style="width:7%; text-align:center; border:1px solid black;">Tgl.</td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_129[tgl_komorbid_<?php echo $i; ?>]" id="tgl_komorbid_<?php echo $i; ?>" onchange="fillthis('tgl_komorbid_<?php echo $i; ?>')" style="width:95%;">
      </td>
    </tr>
    <?php endfor; ?>
  </table>

  <br>

  <table style="width:100%; border-collapse:collapse; border:1px solid black; font-size:13px;">
    <tr style="background-color:#f2f2f2;">
      <td colspan="4" style="padding:5px; border:1px solid black;"><b>KOMPLIKASI KRONIK</b></td>
    </tr>
    <?php for ($i=1; $i<=5; $i++): ?>
    <tr>
      <td style="width:5%; border:1px solid black; text-align:center;"><?php echo $i; ?>.</td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_129[komplikasi_<?php echo $i; ?>]" id="komplikasi_<?php echo $i; ?>" onchange="fillthis('komplikasi_<?php echo $i; ?>')" style="width:95%;">
      </td>
      <td style="width:7%; text-align:center; border:1px solid black;">Tgl.</td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_129[tgl_komplikasi_<?php echo $i; ?>]" id="tgl_komplikasi_<?php echo $i; ?>" onchange="fillthis('tgl_komplikasi_<?php echo $i; ?>')" style="width:95%;">
      </td>
    </tr>
    <?php endfor; ?>
  </table>

  <br><br>

  <!-- Footer signature -->
  <div style="text-align:left;">
    <?php echo $footer; ?>
  </div>

</div>


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

