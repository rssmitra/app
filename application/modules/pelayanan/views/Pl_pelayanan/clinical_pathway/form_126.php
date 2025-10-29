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
      var hiddenInputName = 'form_126[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 29 oktober 2025</p> -->

<!-- PERMINTAAN PEMERIKSAAN HISTOPATOLOGI -->
<div style="text-align:center; font-size:18px; font-weight:bold; margin-bottom:10px;">
  PERMINTAAN PEMERIKSAAN HISTOPATOLOGI
</div>

<table style="width:100%; border-collapse:collapse; font-size:13px; border:1px solid black;">
  <tr>
    <td colspan="2" style="border:1px solid black; padding:5px; width:100%;text-align:right;">
      No. Laboratorium : <input type="text" class="input_type" name="form_126[isi_no_lab]" id="isi_no_lab" onchange="fillthis('isi_no_lab')" style="width:30%;">
    </td>
  </tr>
  <tr>
    <td style="border:1px solid black; padding:5px;">
      Tanggal Permintaan :  <input type="text" class="input_type date-picker" style="width: 100px !important; text-align: center" data-date-format="yyyy-mm-dd" name="form_126[tglpermintaan]" id="tglpermintaan" onchange="fillthis('tglpermintaan')" value="<?php echo isset($value_form['tglpermintaan'])?$value_form['tglpermintaan']:date('Y-m-d')?>">
    </td>
    <td style="border:1px solid black; padding:5px;">
      Tgl. Pengambilan Sediaan :<input type="text" class="input_type date-picker" style="width: 100px !important; text-align: center" data-date-format="yyyy-mm-dd" name="form_126[tglpengambilan]" id="tglpengambilan" onchange="fillthis('tglpermintaan')" value="<?php echo isset($value_form['tglpengambilan'])?$value_form['tglpengambilan']:date('Y-m-d')?>">
    </td>
  </tr>
  <tr>
    <td style="border:1px solid black; padding:5px; vertical-align:top;">
  <b>Jenis Sediaan* :</b><br>

  <label>
    <input type="checkbox" class="ace" name="form_126[jenis_sediaan][]" id="biopsi" onclick="checkthis('biopsi')" value="Biopsi">
    <span class="lbl"> Biopsi</span>
  </label><br>

  <label>
    <input type="checkbox" class="ace" name="form_126[jenis_sediaan][]" id="operasi" onclick="checkthis('operasi')" value="Operasi">
    <span class="lbl"> Operasi</span>
  </label><br>

  <label>
    <input type="checkbox" class="ace" name="form_126[jenis_sediaan][]" id="kerokan" onclick="checkthis('kerokan')" value="Kerokan">
    <span class="lbl"> Kerokan</span>
  </label><br>

  <label>
    <input type="checkbox" class="ace" name="form_126[jenis_sediaan][]" id="lain" onclick="checkthis('lain')" value="Lain-lain">
    <span class="lbl"> Lain-lain :</span>
  </label>
  <input type="text" class="input_type" name="form_126[jenis_sediaan_lain]" id="jenis_sediaan_lain" style="width:70%;" onchange="fillthis('jenis_sediaan_lain')">
</td>
    <td style="border:1px solid black; padding:5px; vertical-align:top;">
  <table style="width:100%; border-collapse:collapse; font-size:13px;">
    <tr>
      <td style="width:40%; vertical-align:middle;">Lokalisasi Jaringan :</td>
      <td>
        <input type="text" class="input_type" name="form_126[lokalisasi]" id="lokalisasi"
               onchange="fillthis('lokalisasi')" style="width:90%;">
      </td>
    </tr>
    <tr>
      <td>Nama Dokter :</td>
      <td>
        <input type="text" class="input_type" name="form_126[nama_dokter]" id="nama_dokter"
               onchange="fillthis('nama_dokter')" style="width:90%;">
      </td>
    </tr>
    <tr>
      <td style="vertical-align:top;">Tanda Tangan :</td>
      <td>
        <div style="text-align:center;">
          <span class="ttd-btn" data-role="dokter" id="ttd_dokter" style="cursor:pointer;">
            <i class="fa fa-pencil blue"></i>
          </span>
          <br>
          <img id="img_ttd_dokter" src="" 
               style="display:none; max-width:150px; max-height:40px; margin-top:4px;">
          <br>
        </div>
      </td>
    </tr>
  </table>
</td>



<!-- KETERANGAN KLINIK DENGAN PENEMUAN OPERASI -->
<tr>
  <td colspan="2" style="border:1px solid black; padding:5px; vertical-align:top;">
    <b>Keterangan klinik dengan penemuan operasi :</b><br>
    <div contenteditable="true"
         class="input_type"
         name="form_126[keterangan_klinik]"
         id="keterangan_klinik"
         onchange="fillthis('keterangan_klinik')"
         style="width:100%; min-height:50px; border:1px solid #ccc; padding:5px;">
      <?php echo isset($value_form['keterangan_klinik']) ? nl2br($value_form['keterangan_klinik']) : '' ?>
    </div>
  </td>
</tr>

<!-- DIAGNOSIS KERJA -->
<tr>
  <td colspan="2" style="border:1px solid black; padding:5px; vertical-align:top;">
    <b>Diagnosis Kerja :</b><br>
    <div contenteditable="true"
         class="input_type"
         name="form_126[diagnosis_kerja]"
         id="diagnosis_kerja"
         onchange="fillthis('diagnosis_kerja')"
         style="width:20%; min-height:40px; border:1px solid #ccc; padding:5px;">
      <?php echo isset($value_form['diagnosis_kerja']) ? nl2br($value_form['diagnosis_kerja']) : '' ?>
    </div>
  </td>
</tr>

<!-- PEMERIKSAAN HISTOPATOLOGI SEBELUMNYA -->
<tr>
  <td colspan="2" style="border:1px solid black; padding:5px; vertical-align:top;">
    <b>Pemeriksaan histopatologi sebelumnya (bila ada) :</b><br>
    <div contenteditable="true"
         class="input_type"
         name="form_126[pemeriksaan_sebelumnya]"
         id="pemeriksaan_sebelumnya"
         onchange="fillthis('pemeriksaan_sebelumnya')"
         style="width:100%; min-height:40px; border:1px solid #ccc; padding:5px;">
      <?php echo isset($value_form['pemeriksaan_sebelumnya']) ? nl2br($value_form['pemeriksaan_sebelumnya']) : '' ?>
    </div>
  </td>
</tr>

<!-- TANGGAL PENERIMAAN SEDIAAN -->
<tr>
  <td colspan="2" style="border:1px solid black; padding:5px; vertical-align:top;">
    <b>Tanggal Penerimaan Sediaan :</b>
    <input type="text" class="input_type" name="form_126[tgl_penerimaan]" id="tgl_penerimaan" onchange="fillthis('tgl_penerimaan')" style="width:20%;">
  </td>
</tr>

<!-- DIAGNOSTIK MIKROSKOPIK -->
<tr>
  <td colspan="2" style="border:1px solid black; padding:5px; vertical-align:top;">
    <b>Diagnostik Mikroskopik :</b><br>
    <div contenteditable="true"
         class="input_type"
         name="form_126[diagnostik_mikroskopik]"
         id="diagnostik_mikroskopik"
         onchange="fillthis('diagnostik_mikroskopik')"
         style="width:100%; min-height:50px; border:1px solid #ccc; padding:5px;">
      <?php echo isset($value_form['diagnostik_mikroskopik']) ? nl2br($value_form['diagnostik_mikroskopik']) : '' ?>
    </div>
  </td>
</tr>

  </tr>
</table>
<!-- END -->



<!-- ----- -->

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