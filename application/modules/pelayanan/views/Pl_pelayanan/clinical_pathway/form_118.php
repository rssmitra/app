<?php echo $header; ?>
<hr>
<br>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<br>
<body>

<br>
<div style="text-align: center; font-size: 16px;">
  <b>ASUHAN KEPERAWATAN DIAGNOSIS KEPERAWATAN / RENCANA TINDAKAN</b>
</div>
<br>

<table border="1" width="100%" class="table" style="border-collapse: collapse; font-size: 13px; text-align: center;">
  <thead style="font-weight: bold;">
    <tr>
      <th style="width:40px; vertical-align: middle; text-align: center;">No</th>
      <th style="width:80px; vertical-align: middle; text-align: center;">Jam</th>
      <th style="width:180px; vertical-align: middle; text-align: center;">Data Fokus</th>
      <th style="width:50px; vertical-align: middle; text-align: center;">No Dx</th>
      <th style="width:200px; vertical-align: middle; text-align: center;">Diagnosis Keperawatan</th>
      <th style="width:150px; vertical-align: middle; text-align: center;">Tujuan</th>
      <th style="width:350px; vertical-align: middle; text-align: center;">Rencana Tindakan</th>
      <th style="width:100px; vertical-align: middle; text-align: center;">Paraf</th>
    </tr>
  </thead>
  <tbody>
    <!-- Baris pertama (Rencana tindakan digabung 10 baris) -->
    <tr>
      <td>1</td>
      <td><input type="text" name="form_118[jam_1]" id="jam_1" class="input_type" style="width:100%;"></td>
      <td><input type="text" name="form_118[data_fokus_1]" id="data_fokus_1" class="input_type" style="width:100%;"></td>
      <td><input type="text" name="form_118[no_dx_1]" id="no_dx_1" class="input_type" style="width:100%;"></td>
      <td><textarea name="form_118[diagnosis_1]" id="diagnosis_1" class="input_type" style="width:100%; height:50px;"></textarea></td>
      <td><textarea name="form_118[tujuan_1]" id="tujuan_1" class="input_type" style="width:100%; height:50px;"></textarea></td>

      <!-- Rencana tindakan rowspan 10 -->
      <td rowspan="10" style="text-align:left; vertical-align:top; padding:5px;">
        <b>RENCANA TINDAKAN:</b><br>
        1. Keringkan bayi<br>
        2. Nilai apgar 1 dan 5 menit pertama setelah lahir<br>
        3. Hisap lendir bayi dari hidung dan mulut<br>
        4. Observasi k/u bayi<br>
        5. Rawat tali pusat dengan teknik aseptic<br>
        6. Kolaborasi dengan dokter anak untuk pemberian th/<br>
        &nbsp;&nbsp;&bull; Neo K 1 mg (IM)<br>
        &nbsp;&nbsp;&bull; Cendofenicol tetes mata 0,25%<br>
        7. Timbang bb dan ukur pb<br>
        8. Ukur antropometri<br>
        9. Periksa anus<br>
        10. Kenakan pakaian bayi<br>
        11. Lakukan IMD<br>
        12. Monitoring TTV bayi<br>
        13. Rawat bayi 2 jam pertama dalam inkubator<br>
        14. Jaga kehangatan bayi dengan:<br>
        &nbsp;&nbsp;&bull; Tidak menyentuh bayi dengan tangan yang basah<br>
        &nbsp;&nbsp;&bull; Hindarkan bayi dari lingkungan yang dingin<br>
        &nbsp;&nbsp;&bull; Monitor suhu bayi tiap shift<br>
        15. Observasi eliminasi meko dan miksi<br>
        16. Pemberian vaksin HBO 12 jam pertama<br>
        17. Lakukan rawat gabung (Rooming In)<br>
        18. Ciptakan lingkungan yang tenang dan nyaman<br>
        19. Beri edukasi pada orang tua bayi:<br>
        &nbsp;&nbsp;&bull; Tentang ASI Eksklusif<br>
        &nbsp;&nbsp;&bull; Beri tahu cara menyusui<br>
        &nbsp;&nbsp;&bull; Cara perawatan tali pusat<br>
        &nbsp;&nbsp;&bull; Pemeriksaan laboratorium bilirubin total, TSH dan golongan darah hari ketiga<br>
        20. Pemberian tetes polio sebelum pulang
      </td>

      <td style="text-align:center;">
        <span class="ttd-btn" data-role="perawat_1" id="ttd_perawat_1" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span><br>
        <img id="img_ttd_perawat_1" src="" style="display:none; max-width:100px; max-height:30px; margin-top:2px;"><br>
        <input type="text" class="input_type" name="form_118[nama_perawat_1]" id="nama_perawat_1" placeholder="Nama" style="width:90%; text-align:center;">
        <input type="hidden" name="form_118[ttd_perawat_1]" id="ttd_input_perawat_1">
      </td>
    </tr>

    <!-- Baris 2–10 tanpa kolom rencana tindakan -->
    <?php for($i=2; $i<=10; $i++) : ?>
    <tr>
      <td><?php echo $i; ?></td>
      <td><input type="text" name="form_118[jam_<?php echo $i?>]" id="jam_<?php echo $i?>" class="input_type" style="width:100%;"></td>
      <td><input type="text" name="form_118[data_fokus_<?php echo $i?>]" id="data_fokus_<?php echo $i?>" class="input_type" style="width:100%;"></td>
      <td><input type="text" name="form_118[no_dx_<?php echo $i?>]" id="no_dx_<?php echo $i?>" class="input_type" style="width:100%;"></td>
      <td><textarea name="form_118[diagnosis_<?php echo $i?>]" id="diagnosis_<?php echo $i?>" class="input_type" style="width:100%; height:50px;"></textarea></td>
      <td><textarea name="form_118[tujuan_<?php echo $i?>]" id="tujuan_<?php echo $i?>" class="input_type" style="width:100%; height:50px;"></textarea></td>
      <td style="text-align:center;">
        <span class="ttd-btn" data-role="perawat_<?php echo $i?>" id="ttd_perawat_<?php echo $i?>" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span><br>
        <img id="img_ttd_perawat_<?php echo $i?>" src="" style="display:none; max-width:120px; max-height:40px; margin-top:2px;"><br>
        <input type="text" class="input_type" name="form_118[nama_perawat_<?php echo $i?>]" id="nama_perawat_<?php echo $i?>" placeholder="Nama" style="width:90%; text-align:center;">
        <input type="hidden" name="form_118[ttd_perawat_<?php echo $i?>]" id="ttd_input_perawat_<?php echo $i?>">
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

<br>
<?php echo $footer; ?>
