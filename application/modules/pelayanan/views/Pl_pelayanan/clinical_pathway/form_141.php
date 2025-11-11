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
      ttdCtx.clearRect(0, 0, ttdCanvas.width, ttdCanvas.height);
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
      var hiddenInputName = 'form_141[ttd_' + role + ']';
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

<div style="text-align:center; font-size:18px;">
  <b>PENILAIAN TINGKAT NYERI</b><br>
</div>

<h3 class="bold"></h3>
<p><b>PENILAIAN TINGKAT NYERI UNTUK ANAK-ANAK DAN DEWASA</b></p>
<p><b>1. Numeric Rating Scale</b>, untuk pasien dewasa dan anak berusia > 9 tahun</p>

<div style="max-width: 300px;">
<table class="table table-bordered table-sm">
    <thead class="table-light">
        <tr>
            <th class="text-center">Skor</th>
            <th class="text-center">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">0</td>
            <td>Tidak nyeri</td>
        </tr>
        <tr>
            <td class="text-center">1–3</td>
            <td>Nyeri ringan</td>
        </tr>
        <tr>
            <td class="text-center">4–6</td>
            <td>Nyeri sedang</td>
        </tr>
        <tr>
            <td class="text-center">7–10</td>
            <td>Nyeri berat</td>
        </tr>
    </tbody>
</table>
</div>


<!-- Numeric Scale -->
<div style="margin-bottom:10px;">
</div>

<div style="text-align:center; font-size:12px;">
  <b>OBSERVASI PENILAIAN TINGKAT NYERI SELANJUTNYA</b>
</div>
<br>
<table width="100%" class="table" border="1" style="font-size:12px; text-align:center;">
<thead>
<tr>
    <th style="width:80px;text-align:center;">Tanggal</th>
    <th style="width:60px;text-align:center;">Jam</th>
    <th style="width:80px;text-align:center;">Skala Nyeri</th>

    <th style="width:80px;text-align:center;">Tanggal</th>
    <th style="width:60px;text-align:center;">Jam</th>
    <th style="width:80px;text-align:center;">Skala Nyeri</th>

    <th style="width:80px;text-align:center;">Tanggal</th>
    <th style="width:60px;text-align:center;">Jam</th>
    <th style="width:80px;text-align:center;">Skala Nyeri</th>
</tr>
</thead>

<tbody>
<?php for($i=1;$i<=5;$i++): ?>
<tr valign="top">

    <!-- Kolom Set 1 -->
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[tanggal_<?php echo $i;?>]"
            id="tanggal_<?php echo $i;?>"
            onchange="fillthis('tanggal_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
            <?php echo isset($value_form['tanggal_'.$i]) ? nl2br($value_form['tanggal_'.$i]) : ''; ?>
        </div>
    </td>
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[jam_<?php echo $i;?>]"
            id="jam_<?php echo $i;?>"
            onchange="fillthis('jam_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
            <?php echo isset($value_form['jam_'.$i]) ? nl2br($value_form['jam_'.$i]) : ''; ?>
        </div>
    </td>
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[skala_<?php echo $i;?>]"
            id="skala_<?php echo $i;?>"
            onchange="fillthis('skala_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
            <?php echo isset($value_form['skala_'.$i]) ? nl2br($value_form['skala_'.$i]) : ''; ?>
        </div>
    </td>

    <!-- Kolom Set 2 -->
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[tanggal2_<?php echo $i;?>]"
            id="tanggal2_<?php echo $i;?>"
            onchange="fillthis('tanggal2_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
            <?php echo isset($value_form['tanggal2_'.$i]) ? nl2br($value_form['tanggal2_'.$i]) : ''; ?>
        </div>
    </td>
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[jam2_<?php echo $i;?>]"
            id="jam2_<?php echo $i;?>"
            onchange="fillthis('jam2_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
            <?php echo isset($value_form['jam2_'.$i]) ? nl2br($value_form['jam2_'.$i]) : ''; ?>
        </div>
    </td>
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[skala2_<?php echo $i;?>]"
            id="skala2_<?php echo $i;?>"
            onchange="fillthis('skala2_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
            <?php echo isset($value_form['skala2_'.$i]) ? nl2br($value_form['skala2_'.$i]) : ''; ?>
        </div>
    </td>

    <!-- Kolom Set 3 -->
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[tanggal3_<?php echo $i;?>]"
            id="tanggal3_<?php echo $i;?>"
            onchange="fillthis('tanggal3_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
            <?php echo isset($value_form['tanggal3_'.$i]) ? nl2br($value_form['tanggal3_'.$i]) : ''; ?>
        </div>
    </td>
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[jam3_<?php echo $i;?>]"
            id="jam3_<?php echo $i;?>"
            onchange="fillthis('jam3_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
            <?php echo isset($value_form['jam3_'.$i]) ? nl2br($value_form['jam3_'.$i]) : ''; ?>
        </div>
    </td>
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[skala3_<?php echo $i;?>]"
            id="skala3_<?php echo $i;?>"
            onchange="fillthis('skala3_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
            <?php echo isset($value_form['skala3_'.$i]) ? nl2br($value_form['skala3_'.$i]) : ''; ?>
        </div>
    </td>

</tr>
<?php endfor; ?>
</tbody>
</table>


<br>

<!-- Wong Baker Scale -->
<p><b>2. Wong Baker Faces Pain Scale</b>, untuk pasien (dewasa dan anak > 3 tahun)</p>

<div style="max-width: 300px;">
<table class="table table-bordered table-sm">
    <thead class="table-light">
        <tr>
            <th class="text-center">Skor</th>
            <th class="text-center">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">0</td>
            <td>Tidak Nyeri</td>
        </tr>
        <tr>
            <td class="text-center">1–2</td>
            <td>Sedikit Nyeri</td>
        </tr>
        <tr>
            <td class="text-center">3–4</td>
            <td>Agak Mengganggu</td>
        </tr>
        <tr>
            <td class="text-center">5–6</td>
            <td>Mengganggu Aktivitas</td>
        </tr>
        <tr>
            <td class="text-center">7–8</td>
            <td>Sangat Mengganggu</td>
        </tr>
        <tr>
            <td class="text-center">9–10</td>
            <td>Tidak Tertahankan</td>
        </tr>
    </tbody>
</table>
</div>
<br>
<div style="text-align:center; font-size:12px;">
  <b>OBSERVASI PENILAIAN TINGKAT NYERI SELANJUTNYA</b>
</div>
<br>
<table width="100%" class="table" border="1" style="font-size:12px; text-align:center;">
<thead>
<tr>
    <th style="width:80px;text-align:center;">Tanggal</th>
    <th style="width:60px;text-align:center;">Jam</th>
    <th style="width:80px;text-align:center;">Skala Nyeri</th>

    <th style="width:80px;text-align:center;">Tanggal</th>
    <th style="width:60px;text-align:center;">Jam</th>
    <th style="width:80px;text-align:center;">Skala Nyeri</th>

    <th style="width:80px;text-align:center;">Tanggal</th>
    <th style="width:60px;text-align:center;">Jam</th>
    <th style="width:80px;text-align:center;">Skala Nyeri</th>
</tr>
</thead>

<tbody>
<?php for($i=1;$i<=5;$i++): ?>
<tr valign="top">

    <!-- Set 4 -->
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[tanggal4_<?php echo $i;?>]"
            id="tanggal4_<?php echo $i;?>"
            onchange="fillthis('tanggal4_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
        </div>
    </td>
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[jam4_<?php echo $i;?>]"
            id="jam4_<?php echo $i;?>"
            onchange="fillthis('jam4_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
        </div>
    </td>
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[skala4_<?php echo $i;?>]"
            id="skala4_<?php echo $i;?>"
            onchange="fillthis('skala4_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
        </div>
    </td>

    <!-- Set 5 -->
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[tanggal5_<?php echo $i;?>]"
            id="tanggal5_<?php echo $i;?>"
            onchange="fillthis('tanggal5_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
        </div>
    </td>
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[jam5_<?php echo $i;?>]"
            id="jam5_<?php echo $i;?>"
            onchange="fillthis('jam5_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
        </div>
    </td>
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[skala5_<?php echo $i;?>]"
            id="skala5_<?php echo $i;?>"
            onchange="fillthis('skala5_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
        </div>
    </td>

    <!-- Set 6 -->
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[tanggal6_<?php echo $i;?>]"
            id="tanggal6_<?php echo $i;?>"
            onchange="fillthis('tanggal6_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
        </div>
    </td>
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[jam6_<?php echo $i;?>]"
            id="jam6_<?php echo $i;?>"
            onchange="fillthis('jam6_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
        </div>
    </td>
    <td style="padding:4px;">
        <div contenteditable="true" class="input_type"
            name="form_141[skala6_<?php echo $i;?>]"
            id="skala6_<?php echo $i;?>"
            onchange="fillthis('skala6_<?php echo $i;?>')"
            style="min-height:20px; border:1px solid #ccc;">
        </div>
    </td>

</tr>
<?php endfor; ?>
</tbody>
</table>


<br>
<p><b>Penatalaksanaan dari manajemen nyeri : </b></p>

<b>SKALA NYERI RINGAN : 1–3</b>
<ol>
<li>Edukasi pasien dan keluarga.</li>
<li>Ajarkan teknik non farmakologi misalnya: relaksasi, biofeedback, hypnosis, guided imagery, terapi musik, distraksi, terapi bermain, acupressure, terapi dingin/panas, maupun terapi pijatan.</li>
<li>Kaji kembali nyeri setelah 1 jam jika tindakan teknik non farmakologi tidak berhasil kolaborasi dengan dokter dalam pemberian terapi farmakologi (terapi NSAID)</li>
<li>Kaji nyeri setelah 8 jam pemberian terapi farmakologi.</li>
</ol>

<b>SKALA NYERI SEDANG : 4–6</b>
<ol>
<li>Edukasi pasien dan keluarga.</li>
<li>Ajarkan teknik non farmakologi misalnya: relaksasi, biofeedback, hypnosis, guided imagery, terapi musik, distraksi, terapi bermain, acupressure, terapi dingin/panas, maupun terapi pijatan.</li>
<li>Kaji nyeri setelah 1 jam teknik non farmakologi tidak berhasil kolaborasi dengan dokter jaga/DPJP dalam pemberian terapi farmakologi (terapi NSAID, Opioid lemah). Kaji nyeri tiap 2 jam dan tiap 8 jam dilaporkan ke dr Jaga/DPJP</li>
</ol>

<b>SKALA NYERI BERAT : 7–10</b>
<ol>
<li>Edukasi pasien dan keluarga.</li>
<li>Ajarkan teknik non farmakologi misalnya: relaksasi, biofeedback, hypnosis, guided imagery, terapi musik, distraksi, terapi bermain, acupressure, terapi dingin/panas, maupun terapi pijatan.</li>
<li>Evaluasi 1 jam; bila tidak berhasil ⇒ terapi opioid & lapor DPJP.</li>
<li>Kaji nyeri setelah 1 jam teknik non farmakologi tidak berhasil kolaborasi dengan DPJP dalam pemberian terapi opioid kuat.</li>
<li>Kaji nyeri tiap 1 jam, dan tiap 8 jam dilaporkan DPJP.</li>
</ol>

<!-- <?php //echo $footer; ?> -->

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