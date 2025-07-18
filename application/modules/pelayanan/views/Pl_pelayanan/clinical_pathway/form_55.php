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

});
</script>

<?php echo $header; ?>
<hr>
<br>

<div style="text-align: center; font-size: 18px;"><b>FORM PENGAWASAN & PENDAMPINGAN FISIOTERAPI</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<span style="text-align: left;">Diagnosis</span><br>
<input type="text" class="input-type" name="form_55[diagnosis]" id="diagnosis" onchange="fillthis('diagnosis')" value="<?php echo isset($value_form['diagnosis'])?$value_form['diagnosis']:''?>" style="width: 100% !important">
<br>

<br>
<span style="text-align: left;">Permintaan Terapi</span><br>
<textarea class="textarea-type" name="form_55[permintaan_terapi]" id="permintaan_terapi" onchange="fillthis('permintaan_terapi')" style="height: 60px !important">
  <?php echo isset($value_form['permintaan_terapi'])?$value_form['permintaan_terapi']:''?>
</textarea>
<br>
<hr>
<table class="table" width="100%">
  <tr>
    <th rowspan="2" width="30px" class="center">NO</th>
    <th rowspan="2" class="center">PROGRAM</th>
    <th rowspan="2" width="150px" class="center">TANGGAL</th>
    <th colspan="3" class="center">TTD</th>
  </tr>
  <tr>
    <th style="width: 130px;text-align: center">PASIEN</th>
    <th style="width: 130px;text-align: center">DOKTER</th>
    <th style="width: 130px;text-align: center">FISIOTERAPIS</th>
  </tr>

  <?php 
    for ($i=0; $i < 7; $i++) { 
      echo '<tr>';
      echo '<td class="center">'.($i+1).'</td>';    
      echo '<td><input type="text" class="input_type" name="form_55[program_'.$i.']" id="program_'.$i.'" style="width: 100% !important"></td>';
      echo '<td><input type="text" class="input_type" name="form_55[tgl_'.$i.']" id="tgl_'.$i.'" style="width: 100% !important"></td>';
      echo '<td align="center"><span class="ttd-btn" data-role="pasien" data-idx="'.$i.'" id="ttd_pasien_'.$i.'" style="cursor: pointer"><i class="fa fa-pencil blue"></i></span><br><img id="img_ttd_pasien_'.$i.'" src="" style="display:none;max-width:200px;max-height:40px;margin-top:2px;"></td>';
      echo '<td align="center"><span class="ttd-btn" data-role="dok" data-idx="'.$i.'" id="ttd_dok_'.$i.'" style="cursor: pointer"><i class="fa fa-pencil blue"></i></span><br><img id="img_ttd_dok_'.$i.'" src="" style="display:none;max-width:200px;max-height:40px;margin-top:2px;"></td>';
      echo '<td align="center"><span class="ttd-btn" data-role="terapis" data-idx="'.$i.'" id="ttd_terapis_'.$i.'" style="cursor: pointer"><i class="fa fa-pencil blue"></i></span><br><img id="img_ttd_terapis_'.$i.'" src="" style="display:none;max-width:200px;max-height:40px;margin-top:2px;"></td>';
      echo '</tr>';
    }
  ?>

</table>
<hr>
<?php echo $footer; ?>

<!-- Modal dan Script Tanda Tangan Digital -->
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
<script>
jQuery(function($) {
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
      var idx = currentTtdTarget.data('idx');
      var imgId = '#img_ttd_' + role + '_' + idx;
      $(imgId).attr('src', dataUrl).show();
    }
    $('#ttdModal').modal('hide');
  });
});
</script>