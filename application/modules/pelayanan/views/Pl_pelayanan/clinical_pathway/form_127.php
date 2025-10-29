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
      var hiddenInputName = 'form_127[ttd_' + role + ']';
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

<!-- FORM OBSERVASI PASCA ANASTESI -->
<div style="text-align:center; font-size:18px; font-weight:bold; margin-bottom:10px;">
  FORM OBSERVASI PASCA ANASTESI
</div>

<b>1. ALDRETE SCORING</b>

<table style="width:100%; border-collapse:collapse; font-size:13px; border:1px solid black; text-align:center;">
  <thead style="background-color:#e8e8e8; font-weight:bold;">
    <tr>
      <td style="border:1px solid black; width:5%; padding:5px;">No</td>
      <td style="border:1px solid black; width:20%; padding:5px;">Tanda</td>
      <td style="border:1px solid black; padding:5px;">Kriteria</td>
      <td style="border:1px solid black; width:10%; padding:5px;">Score</td>
    </tr>
  </thead>
  <tbody>

    <!-- 1. Aktivitas -->
    <tr>
      <td style="border:1px solid black; padding:5px;">1</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">Aktivitas</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <tr><td style="width:90%;">Mampu gerak 4 ekstremitas atas perintah</td><td style="text-align:center;">2</td></tr>
          <tr><td>Mampu gerak 2 ekstremitas atas perintah</td><td style="text-align:center;">1</td></tr>
          <tr><td>Tidak mampu gerak ekstremitas</td><td style="text-align:center;">0</td></tr>
        </table>
      </td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_127[skor_aktivitas]" id="skor_aktivitas"
               value="<?php echo isset($value_form['skor_aktivitas'])?$value_form['skor_aktivitas']:'';?>"
               style="width:40px; text-align:center;">
      </td>
    </tr>

    <!-- 2. Respirasi -->
    <tr>
      <td style="border:1px solid black; padding:5px;">2</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">Respirasi</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <tr><td style="width:90%;">Mampu nafas dalam dan batuk</td><td style="text-align:center;">2</td></tr>
          <tr><td>Dispneu / nafas terbatas</td><td style="text-align:center;">1</td></tr>
          <tr><td>Apneu</td><td style="text-align:center;">0</td></tr>
        </table>
      </td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_127[skor_respirasi]" id="skor_respirasi"
               value="<?php echo isset($value_form['skor_respirasi'])?$value_form['skor_respirasi']:'';?>"
               style="width:40px; text-align:center;">
      </td>
    </tr>

    <!-- 3. Sirkulasi -->
    <tr>
      <td style="border:1px solid black; padding:5px;">3</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">Sirkulasi</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <tr><td style="width:90%;">TD berkurang ≤ 20% dari nilai pra anastesi</td><td style="text-align:center;">2</td></tr>
          <tr><td>TD berkurang 20–50% dari nilai pra anastesi</td><td style="text-align:center;">1</td></tr>
          <tr><td>TD berkurang ≥ 50% dari nilai pra anastesi</td><td style="text-align:center;">0</td></tr>
        </table>
      </td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_127[skor_sirkulasi]" id="skor_sirkulasi"
               value="<?php echo isset($value_form['skor_sirkulasi'])?$value_form['skor_sirkulasi']:'';?>"
               style="width:40px; text-align:center;">
      </td>
    </tr>

    <!-- 4. Kesadaran -->
    <tr>
      <td style="border:1px solid black; padding:5px;">4</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">Kesadaran</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <tr><td style="width:90%;">Sadar penuh</td><td style="text-align:center;">2</td></tr>
          <tr><td>Bangun jika dipanggil</td><td style="text-align:center;">1</td></tr>
          <tr><td>Tidak ada respon</td><td style="text-align:center;">0</td></tr>
        </table>
      </td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_127[skor_kesadaran]" id="skor_kesadaran"
               value="<?php echo isset($value_form['skor_kesadaran'])?$value_form['skor_kesadaran']:'';?>"
               style="width:40px; text-align:center;">
      </td>
    </tr>

    <!-- 5. Warna Kulit -->
    <tr>
      <td style="border:1px solid black; padding:5px;">5</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">Warna Kulit</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <tr><td style="width:90%;">Kemerahan</td><td style="text-align:center;">2</td></tr>
          <tr><td>Pucat / Kuning</td><td style="text-align:center;">1</td></tr>
          <tr><td>Sianosis</td><td style="text-align:center;">0</td></tr>
        </table>
      </td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_127[skor_warna_kulit]" id="skor_warna_kulit"
               value="<?php echo isset($value_form['skor_warna_kulit'])?$value_form['skor_warna_kulit']:'';?>"
               style="width:40px; text-align:center;">
      </td>
    </tr>

    <!-- TOTAL SKOR -->
    <tr style="font-weight:bold;">
      <td colspan="3" style="border:1px solid black; text-align:right; padding:5px;">TOTAL SCORE</td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_127[total_skor]" id="total_skor"
               value="<?php echo isset($value_form['total_skor'])?$value_form['total_skor']:'';?>"
               style="width:50px; text-align:center; font-weight:bold;">
      </td>
    </tr>

  </tbody>
</table>

<p style="font-size:12px; margin-top:5px;">
  <i>Aldrete Score = Standar score untuk general anestesi dewasa, score minimal keluar dari RR ≥ 8</i>
</p>

<!-- ----- -->

<!-- 2. BROMAGE SCORE -->
<br>
<b>2. BROMAGE SCORE</b>

<table style="width:100%; border-collapse:collapse; font-size:13px; border:1px solid black; text-align:center; margin-top:5px;">
  <thead style="background-color:#e8e8e8; font-weight:bold;">
    <tr>
      <td style="border:1px solid black; width:5%; padding:5px;">No</td>
      <td style="border:1px solid black; padding:5px;">Kriteria</td>
      <td style="border:1px solid black; width:10%; padding:5px;">Score</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="border:1px solid black; padding:5px;">1</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">Tidak mampu fleksi pergelangan kaki</td>
      <td style="border:1px solid black; padding:5px;">3</td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">2</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">Tidak mampu fleksi lutut</td>
      <td style="border:1px solid black; padding:5px;">2</td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">3</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">Tidak mampu ekstensi tungkai</td>
      <td style="border:1px solid black; padding:5px;">1</td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">4</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">Gerak penuh dari tungkai</td>
      <td style="border:1px solid black; padding:5px;">0</td>
    </tr>
    <tr style="font-weight:bold;">
      <td colspan="2" style="border:1px solid black; text-align:right; padding:5px;">TOTAL SCORE</td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_127[total_bromage]" id="total_bromage"
               value="<?php echo isset($value_form['total_bromage'])?$value_form['total_bromage']:'';?>"
               style="width:50px; text-align:center;">
      </td>
    </tr>
  </tbody>
</table>

<p style="font-size:12px; margin-top:5px;">
  <i>Bromage score (standar score untuk regional anestesi) – score minimal keluar dari RR ≤ 2</i>
</p>


<!-- 3. STEWARD SCORE UNTUK PASCA ANASTESI ANAK -->
<br>
<b>3. STEWARD SCORE UNTUK PASCA ANASTESI ANAK</b>

<table style="width:100%; border-collapse:collapse; font-size:13px; border:1px solid black; text-align:center; margin-top:5px;">
  <thead style="background-color:#e8e8e8; font-weight:bold;">
    <tr>
      <td style="border:1px solid black; width:5%; padding:5px;">No</td>
      <td style="border:1px solid black; width:20%; padding:5px;">Tanda</td>
      <td style="border:1px solid black; padding:5px;">Kriteria</td>
      <td style="border:1px solid black; width:10%; padding:5px;">Score</td>
    </tr>
  </thead>
  <tbody>

    <!-- 1. Kesadaran -->
    <tr>
      <td style="border:1px solid black; padding:5px;">1</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">Kesadaran</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <tr><td style="width:90%;">Bangun</td><td style="text-align:center;">2</td></tr>
          <tr><td>Respon terhadap rangsangan</td><td style="text-align:center;">1</td></tr>
          <tr><td>Tak ada respon</td><td style="text-align:center;">0</td></tr>
        </table>
      </td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_127[skor_kesadaran_anak]" id="skor_kesadaran_anak"
               value="<?php echo isset($value_form['skor_kesadaran_anak'])?$value_form['skor_kesadaran_anak']:'';?>"
               style="width:40px; text-align:center;">
      </td>
    </tr>

    <!-- 2. Respirasi -->
    <tr>
      <td style="border:1px solid black; padding:5px;">2</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">Respirasi</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <tr><td style="width:90%;">Batuk / Menangis</td><td style="text-align:center;">2</td></tr>
          <tr><td>Pertahankan jalan nafas</td><td style="text-align:center;">1</td></tr>
          <tr><td>Perlu bantuan nafas</td><td style="text-align:center;">0</td></tr>
        </table>
      </td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_127[skor_respirasi_anak]" id="skor_respirasi_anak"
               value="<?php echo isset($value_form['skor_respirasi_anak'])?$value_form['skor_respirasi_anak']:'';?>"
               style="width:40px; text-align:center;">
      </td>
    </tr>

    <!-- 3. Motorik -->
    <tr>
      <td style="border:1px solid black; padding:5px;">3</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">Motorik</td>
      <td style="border:1px solid black; text-align:left; padding:5px;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <tr><td style="width:90%;">Gerak bertujuan</td><td style="text-align:center;">2</td></tr>
          <tr><td>Gerak tanpa tujuan</td><td style="text-align:center;">1</td></tr>
          <tr><td>Tidak bergerak</td><td style="text-align:center;">0</td></tr>
        </table>
      </td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_127[skor_motorik_anak]" id="skor_motorik_anak"
               value="<?php echo isset($value_form['skor_motorik_anak'])?$value_form['skor_motorik_anak']:'';?>"
               style="width:40px; text-align:center;">
      </td>
    </tr>

    <!-- TOTAL -->
    <tr style="font-weight:bold;">
      <td colspan="3" style="border:1px solid black; text-align:right; padding:5px;">TOTAL SCORE</td>
      <td style="border:1px solid black; padding:5px;">
        <input type="text" class="input_type" name="form_127[total_steward]" id="total_steward"
               value="<?php echo isset($value_form['total_steward'])?$value_form['total_steward']:'';?>"
               style="width:50px; text-align:center;">
      </td>
    </tr>
  </tbody>
</table>

<p style="font-size:12px; margin-top:5px;">
  <i>Steward score (standar score untuk general anestesi anak) – score minimal keluar dari RR ≥ 5</i>
</p>

<!-- ----- -->

<br>

<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse; padding:5px;">
  <tbody>
    <tr>
      <!-- Kolom 1: Perawat -->
      <td style="width:50%; text-align:center; padding:5px;">
        <b>Perawat yang mengkaji</b><br><br>

        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <tr>
            <td style="text-align:right; width:30%;">Tanggal :</td>
            <td>
              <!-- <input type="date" class="input_type"
                     name="form_127[tgl_perawat]"
                     id="tgl_perawat"
                     value="<?php echo isset($value_form['tgl_perawat']) && $value_form['tgl_perawat'] ? date('Y-m-d', strtotime($value_form['tgl_perawat'])) : date('Y-m-d'); ?>"
                     style="width:70%;"> -->
            <input class="input_type" type="text" style="width: 70%" name="form_127[tgl_perawat]" id="tgl_perawat" onchange="fillthis('tgl_perawat')"> 
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Jam :</td>
            <td>
              <!-- <input type="time" class="input_type"
                     name="form_127[jam_perawat]"
                     id="jam_perawat"
                     value="<?php echo isset($value_form['jam_perawat']) && $value_form['jam_perawat'] ? date('H:i', strtotime($value_form['jam_perawat'])) : date('H:i'); ?>"
                     style="width:70%;"> -->
            <input class="input_type" type="text" style="width: 70%" name="form_127[jam_perawat]" id="jam_perawat" onchange="fillthis('jam_perawat')"></td>
            </td>
          </tr>
        </table>

        <br>
        <span class="ttd-btn" data-role="perawat" id="ttd_perawat" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_perawat" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_127[nama_perawat]" id="nama_perawat" placeholder="Nama Perawat" style="width:80%; text-align:center;">
      </td>

      <!-- Kolom 2: Dokter -->
      <td style="width:50%; text-align:center; padding:5px;">
        <b>Verifikasi (Dokter)</b><br><br>

        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <tr>
            <td style="text-align:right; width:30%;">Tanggal :</td>
            <td>
              <!-- <input type="date" class="input_type"
                     name="form_127[tgl_dokter]"
                     id="tgl_dokter"
                     value="<?php echo isset($value_form['tgl_dokter']) && $value_form['tgl_dokter'] ? date('Y-m-d', strtotime($value_form['tgl_dokter'])) : date('Y-m-d'); ?>"
                     style="width:70%;"> -->
                <input class="input_type" type="text" style="width: 70%" name="form_127[tgl_dokter]" id="tgl_dokter" onchange="fillthis('tgl_dokter')"> 
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Jam :</td>
            <td>
              <!-- <input type="time" class="input_type"
                     name="form_127[jam_dokter]"
                     id="jam_dokter"
                     value="<?php echo isset($value_form['jam_dokter']) && $value_form['jam_dokter'] ? date('H:i', strtotime($value_form['jam_dokter'])) : date('H:i'); ?>"
                     style="width:70%;"> -->
                <input class="input_type" type="text" style="width: 70%" name="form_127[jam_dokter]" id="jam_dokter" onchange="fillthis('jam_dokter')"></td>
            </td>
          </tr>
        </table>

        <br>
        <span class="ttd-btn" data-role="dokter" id="ttd_dokter" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_dokter" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_127[nama_dokter]" id="nama_dokter" placeholder="Nama Dokter" style="width:80%; text-align:center;">
      </td>
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

