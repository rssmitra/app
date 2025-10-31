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
      var hiddenInputName = 'form_128[ttd_' + role + ']';
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

<!-- FORM 128 - VISA MEDICAL CERTIFICATE -->
<?php echo $header; ?>
<hr>
<br>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<div style="text-align:center; font-size:18px; font-weight:bold; margin-bottom:10px;">
  VISA MEDICAL CERTIFICATE
</div>

<div style="font-size:13px; line-height:1.6;">

  <p>
    The undersigned Doctor in medicine (full name)
    <input type="text" class="input_type" name="form_128[nama_dokter]" id="nama_dokter" onchange="fillthis('nama_dokter')" style="width:80%;">,
    <br>Certifies that he/she has examined this day Mr./Mrs./Ms./Miss (full name)
    <input type="text" class="input_type" name="form_128[nama_pasien]" id="nama_pasien" onchange="fillthis('nama_pasien')" style="width:80%;">
  </p>

  <p>
    Nationality:
    <input type="text" class="input_type" name="form_128[kewarganegaraan]" id="kewarganegaraan" onchange="fillthis('kewarganegaraan')" style="width:73%;">
  </p>

  <p>
    Date and place of birth:
    <input type="text" class="input_type" name="form_128[tgl_tempat_lahir]" id="tgl_tempat_lahir" onchange="fillthis('tgl_tempat_lahir')" style="width:66%;">
  </p>

  <p>
    Residing at:
    <input type="text" class="input_type" name="form_128[alamat]" id="alamat" onchange="fillthis('alamat')" style="width:73%;">
  </p>

  <p>
    And has found him/her free of one of the following illnesses for public health:
  </p>

  <ol style="margin-left:20px;">
    <li>
      Illnesses requiring quarantine:
      <input type="text" class="input_type" name="form_128[penyakit_karantina]" id="penyakit_karantina" onchange="fillthis('penyakit_karantina')" style="width:61%;">
    </li>
    <li>
      Pulmonary tuberculosis, active or progressive:
      <input type="text" class="input_type" name="form_128[tbc]" id="tbc" onchange="fillthis('tbc')" style="width:51%;">
    </li>
    <li>
      Other contagious or transmittable diseases by infection or parasites:
      <input type="text" class="input_type" name="form_128[penyakit_lain]" id="penyakit_lain" onchange="fillthis('penyakit_lain')" style="width:38%;">
    </li>
  </ol>

  <br>
 <!--
  <p>
    Issued at
    <input type="text" class="input_type" name="form_128[tempat_terbit]" id="tempat_terbit" onchange="fillthis('tempat_terbit')" style="width:40%;">
    on
    <input type="text" class="date-picker input_type" name="form_128[tgl_terbit]" id="tgl_terbit" onchange="fillthis('tgl_terbit')" style="width:30%;">
  </p>

  <p>
    Signature of doctor:
    <br>
    <span class="ttd-btn" data-role="dokter" id="ttd_dokter" style="cursor:pointer;">
      <i class="fa fa-pencil blue"></i>
    </span><br>
    <img id="img_ttd_dokter" src="" style="display:none; max-width:200px; max-height:50px; margin-top:2px;">
  </p>

  <p>
    Stamp of doctor's office:
    <input type="text" class="input_type" name="form_128[stempel]" id="stempel" onchange="fillthis('stempel')" style="width:60%;">
  </p> -->

  <!-- Footer signature section (posisi kiri) -->
<div style="text-align:left;">
  <?php echo $footer; ?>
</div>

  <br><br>

  <p style="font-weight:bold;">If applicable,</p>
  <p>
    Visa of the Embassy, Consulate general or Consulate (Seal)
  </p>

  <p>
    At
    <input type="text" class="input_type" name="form_128[tempat_kedutaan]" id="tempat_kedutaan" onchange="fillthis('tempat_kedutaan')" style="width:20%;">
    on
    <!-- <input type="text" class="date-picker input_type" name="form_128[tgl_kedutaan]" id="tgl_kedutaan" onchange="fillthis('tgl_kedutaan')" style="width:15%;"> -->
    <input class="input_type" type="text" style="width: 15%" name="form_128[tgl_kedutaan]" id="tgl_kedutaan" onchange="fillthis('tgl_kedutaan')"> 
  </p>

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

