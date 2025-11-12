<?php echo $header; ?>
<hr><br>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<body>
  
<div style="text-align: center; font-size: 16px;">
  <b>CATATAN OBSTETRI</b>
</div>
<br>

<table style="width:100%; font-size:13px; border-collapse:collapse;">
  <tr>
    <!-- Kolom kiri: G P A -->
    <td style="width:50%; vertical-align: middle; font-weight: bold;">
      <span style="font-size: 16px">G</span> <input type="text" class="input_type" name="form_120[g]" id="g" style="width:50px; text-align:center;">
      &nbsp;<span style="font-size: 16px">P</span> <input type="text" class="input_type" name="form_120[p]" id="p" style="width:50px; text-align:center;">
      &nbsp;<span style="font-size: 16px">A</span> <input type="text" class="input_type" name="form_120[a]" id="a" style="width:50px; text-align:center;">
    </td>

    <!-- Kolom kanan: Dokter / Bidan -->
    <td style="width:50%; vertical-align: middle;">
      <table style="width:100%; border-collapse:collapse;">
        <tr>
          <td style="width:140px;">Dokter / Bidan</td>
          <td style="width:10px; text-align:right;">:</td>
          <td><input type="text" class="input_type" name="form_120[dokter_bidan]" id="dokter_bidan" style="width:80%; text-align: left"></td>
        </tr>
        <tr>
          <td>Tiba di KB jam</td>
          <td style="text-align:right;">:</td>
          <td><input type="text" class="input_type" name="form_120[tiba_jam]" id="tiba_jam" style="width:80%; text-align: left"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<br>

<table border="1" width="100%" style="border-collapse: collapse; border-color: #8080804d; font-size: 12px; text-align: center;">
  
  <thead style="font-weight: bold;">
    <tr>
      <th rowspan="2" style="width:100px; vertical-align: middle; text-align: center;">Tgl & Jam</th>
      <th rowspan="2" style="width:80px; vertical-align: middle; text-align: center;">TD</th>
      <th rowspan="2" style="width:80px; vertical-align: middle; text-align: center;">FN</th>
      <th rowspan="2" style="width:80px; vertical-align: middle; text-align: center;">S</th>
      <th colspan="4" style="vertical-align: middle; text-align: center;">HIS</th>
      <th rowspan="2" style="width:100px; vertical-align: middle; text-align: center;">Frek. DJA</th>
      <th rowspan="2" style="width:200px; vertical-align: middle; text-align: center;">Catatan</th>
      <th rowspan="2" style="width:100px; vertical-align: middle; text-align: center;">Paraf</th>
    </tr>
    <tr>
      <th style="width:80px; vertical-align: middle; text-align: center;">Frek.</th>
      <th style="width:80px; vertical-align: middle; text-align: center;">Lamanya</th>
      <th style="width:80px; vertical-align: middle; text-align: center;">Kekuatan</th>
      <th style="width:80px; vertical-align: middle; text-align: center;">Relaksasi</th>
    </tr>
  </thead>

  <tbody>
    <?php for($i=1; $i<=10; $i++) : ?>
    <tr>
      <td><input type="text" name="form_120[tgl_jam_<?php echo $i?>]" id="tgl_jam_<?php echo $i?>" class="input_type" style="width:80%; text-align: center"></td>
      <td><input type="text" name="form_120[td_<?php echo $i?>]" id="td_<?php echo $i?>" class="input_type" style="width:80%; text-align: center"></td>
      <td><input type="text" name="form_120[fn_<?php echo $i?>]" id="fn_<?php echo $i?>" class="input_type" style="width:80%; text-align: center"></td>
      <td><input type="text" name="form_120[s_<?php echo $i?>]" id="s_<?php echo $i?>" class="input_type" style="width:80%; text-align: center"></td>

      <!-- Kolom HIS -->
      <td><input type="text" name="form_120[his_frek_<?php echo $i?>]" id="his_frek_<?php echo $i?>" class="input_type" style="width:80%; text-align: center"></td>
      <td><input type="text" name="form_120[his_lamanya_<?php echo $i?>]" id="his_lamanya_<?php echo $i?>" class="input_type" style="width:80%; text-align: center"></td>
      <td><input type="text" name="form_120[his_kekuatan_<?php echo $i?>]" id="his_kekuatan_<?php echo $i?>" class="input_type" style="width:80%; text-align: center"></td>
      <td><input type="text" name="form_120[his_relaksasi_<?php echo $i?>]" id="his_relaksasi_<?php echo $i?>" class="input_type" style="width:80%; text-align: center"></td>

      <td><input type="text" name="form_120[frek_dja_<?php echo $i?>]" id="frek_dja_<?php echo $i?>" class="input_type" style="width:80%; text-align: center"></td>

      <td><textarea name="form_120[catatan_<?php echo $i?>]" id="catatan_<?php echo $i?>" class="input_type" style="width:80%; height:50px !important"></textarea></td>

      <td style="text-align:center;">
        <span class="ttd-btn" data-role="perawat_<?php echo $i?>" id="ttd_perawat_<?php echo $i?>" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span><br>
        <img id="img_ttd_perawat_<?php echo $i?>" src="" style="display:none; max-width:100px; max-height:30px; margin-top:2px;"><br>
        <input type="text" class="input_type" name="form_120[nama_perawat_<?php echo $i?>]" id="nama_perawat_<?php echo $i?>" placeholder="Nama" style="width:90%; text-align:center;">
        <input type="hidden" name="form_120[ttd_perawat_<?php echo $i?>]" id="ttd_input_perawat_<?php echo $i?>">
      </td>
    </tr>
    <?php endfor; ?>
  </tbody>
</table>

<br>
<hr>

<!-- Modal Tanda Tangan -->
<div class="modal fade" id="ttdModal" tabindex="-1" role="dialog" aria-labelledby="ttdModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#007bff; color:white;">
        <h4 class="modal-title" id="ttdModalLabel">Tanda Tangan Digital</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" style="text-align:center;">
        <canvas id="ttd-canvas" style="border:1px solid #ccc; touch-action:none;" width="350" height="120"></canvas><br>
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

  $('#dokter_bidan').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getAllDokter",
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
        $('#dokter_bidan').val(label_item);
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
      var hiddenInputName = 'form_114[ttd_' + role + ']';
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