<?php echo $header; ?>
<hr><br>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<br>
<body>
<div style="text-align: center; font-size: 16px;">
  <b>CURB-65 Score</b>
  <br><b>Pneumonia</b>
</div>
<br>

<table border="1" width="100%" style="border-collapse: collapse; font-size:13px; text-align:center;">
  <thead style="font-weight:bold; background-color:#c7cccb;">
    <tr>
      <th style="width:40px;text-align:center;">No</th>
      <th style="text-align:center;">Gejala</th>
      <th style="width:80px;text-align:center;">Poin</th>
      <th style="width:100px;text-align:center;">Nilai</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $gejala = [
        'Confusion',
        'Urea : BUN ≥19 mg/dl (≥7mmol/L)',
        'Respiratory Rate >30x/min',
        'Blood pressure, Systolic <90 mmHg atau Diastolic <60 mmHg',
        'Age ≥ 65 years'
      ];
      $i = 1;
      foreach($gejala as $g) :
    ?>
    <tr>
      <td><?= $i ?></td>
      <td style="text-align:left; padding:5px;"><?= $g ?></td>
      <td>1</td>
      <td>
        <input 
          type="text" 
          class="input_type" 
          name="form_125[nilai_<?= $i ?>]" 
          id="nilai_<?= $i ?>" 
          onchange="fillthis('nilai_<?= $i ?>')" 
          style="width:60px; text-align:center;"
        >
      </td>
    </tr>
    <?php $i++; endforeach; ?>
    <tr style="font-weight:bold;">
      <td colspan="3" style="text-align:right;">TOTAL</td>
      <td>
        <input 
          type="text" 
          name="form_125[total]" 
          id="total" 
          class="input_type" 
          style="width:60px; text-align:center;"
          onchange="fillthis('total')"
        >
      </td>
    </tr>
  </tbody>
</table>


<br>
<table style="width:50%; font-size:12px; border-collapse:collapse; margin-top:10px;" border="1">
  <thead style="background:#eaeaea;">
    <tr>
      <th style="padding:5px;">Skor</th>
      <th style="padding:5px;">Risiko</th>
      <th style="padding:5px;">Instruksi</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="padding:5px;">0 atau 1</td>
    <td style="padding:5px;">1.5% mortalitas</td>
    <td style="padding:5px;">Rawat jalan</td></tr>
    <tr style="padding:5px;">
    <td style="padding:5px;">2</td>
    <td style="padding:5px;">9.2% mortalitas</td>
    <td style="padding:5px;">Pengawasan rawat inap</td></tr>
    <tr style="padding:5px;"><td style="padding:5px;">3 atau lebih</td>
    <td style="padding:5px;">22% mortalitas</td>
    <td style="padding:5px;">Rawat inap, pertimbangkan ICU (skor 4–5)</td></tr>
  </tbody>
</table>

<br>
<!-- TANDA TANGAN -->
<table class="table" style="width: 100%; border: none !important;">
  <tbody>
    <tr>
       <tr style="border: none !important;">
      <td style="width:50%; text-align:center; border: none !important;"></td>
      <td style="width:50%; text-align:center; border: none !important;">
        DPJP
        <br><br>
        <span class="ttd-btn" data-role="dpjp" id="ttd_dpjp" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_dpjp" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" name="form_125[nama_dpjp]" id="nama_dpjp" class="input_type" placeholder="Nama Dokter" style="width:150px; text-align:center;">
        <br>
        (Tanda Tangan dan Nama Jelas)
      </td>
    </tr>
  </tbody>
</table>


<br>

<!-- Modal Tanda Tangan Digital -->
<div class="modal fade" id="ttdModal" tabindex="-1" role="dialog" aria-labelledby="ttdModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="ttdModalLabel" style="color: white;">Tanda Tangan Digital</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="text-align:center;">
        <canvas id="ttd-canvas" style="border:1px solid #ccc; touch-action:none;" width="350" height="120"></canvas>
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
      var hiddenInputName = 'form_125[ttd_' + role + ']';
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

<?php //echo $footer; ?>