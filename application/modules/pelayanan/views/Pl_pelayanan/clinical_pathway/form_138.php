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
      var hiddenInputName = 'form_138[ttd_' + role + ']';
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
<div style="text-align:center; font-size:18px;">
  <b><u>MONITORING TRANSFUSI DARAH</u></b><br>
</div>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br><br>

<!-- ==================== MONITORING TRANSFUSI DARAH ==================== -->

<table border="1" width="100%" style="border-collapse: collapse; font-size:13px; text-align:center;">
  <thead style="background-color:#e8e8e8; font-weight:bold;">
    <tr>
      <th style="width:100px; text-align:center;">Tanggal / Jam</th>
      <th style="width:120px; text-align:center;">Jam Produk Darah</th>
      <th style="width:160px; text-align:center;">Reaksi Transfusi</th>
      <th style="width:180px; text-align:center;">Tindakan Medis</th>
      <th style="width:120px; text-align:center;">Paraf Perawat</th>
      <th style="width:180px; text-align:center;">Keterangan</th>
    </tr>
  </thead>
  <tbody>
    <?php for($i=1; $i<=20; $i++): ?>
    <tr valign="top">
      <!-- Tanggal / Jam -->
      <td style="padding:5px;">
        <div contenteditable="true"
          class="input_type datetime-picker"
          name="form_138[tanggal_<?php echo $i; ?>]"
          id="tanggal_<?php echo $i; ?>"
          onchange="fillthis('tanggal_<?php echo $i; ?>')"
          style="width:100%; min-height:50px; border:1px solid #ccc; overflow:visible;">
          <?php echo isset($value_form['tanggal_'.$i]) ? nl2br($value_form['tanggal_'.$i]) : '' ?>
        </div>
      </td>

      <!-- Jam Produk Darah -->
      <td style="padding:5px;">
        <div contenteditable="true"
          class="input_type"
          name="form_138[jam_produk_<?php echo $i; ?>]"
          id="jam_produk_<?php echo $i; ?>"
          onchange="fillthis('jam_produk_<?php echo $i; ?>')"
          style="width:100%; min-height:50px; border:1px solid #ccc; overflow:visible;">
          <?php echo isset($value_form['jam_produk_'.$i]) ? nl2br($value_form['jam_produk_'.$i]) : '' ?>
        </div>
      </td>

      <!-- Reaksi Transfusi -->
      <td style="padding:5px;">
        <div contenteditable="true"
          class="input_type"
          name="form_138[reaksi_<?php echo $i; ?>]"
          id="reaksi_<?php echo $i; ?>"
          onchange="fillthis('reaksi_<?php echo $i; ?>')"
          style="width:100%; min-height:50px; border:1px solid #ccc; overflow:visible;">
          <?php echo isset($value_form['reaksi_'.$i]) ? nl2br($value_form['reaksi_'.$i]) : '' ?>
        </div>
      </td>

      <!-- Tindakan Medis -->
      <td style="padding:5px;">
        <div contenteditable="true"
          class="input_type"
          name="form_138[tindakan_<?php echo $i; ?>]"
          id="tindakan_<?php echo $i; ?>"
          onchange="fillthis('tindakan_<?php echo $i; ?>')"
          style="width:100%; min-height:50px; border:1px solid #ccc; overflow:visible;">
          <?php echo isset($value_form['tindakan_'.$i]) ? nl2br($value_form['tindakan_'.$i]) : '' ?>
        </div>
      </td>

      <!-- Paraf Perawat -->
      <td style="text-align:center;">
                <span class="ttd-btn" data-role="pemberi_<?php echo $i; ?>" id="ttd_pemberi_btn_<?php echo $i; ?>" style="cursor:pointer;">
                    <i class="fa fa-pencil blue"></i>
                </span>
                <br>
                <img id="img_ttd_pemberi_<?php echo $i; ?>" src="<?php echo isset($value_form['img_ttd_pemberi_'.$i]) ? $value_form['img_ttd_pemberi_'.$i] : ''; ?>" 
                     style="display:<?php echo isset($value_form['img_ttd_pemberi_'.$i]) ? 'block' : 'none'; ?>; max-width:150px; max-height:40px; margin-top:5px;">
                <input type="hidden" name="form_138[img_ttd_pemberi_<?php echo $i; ?>]" id="input_ttd_pemberi_<?php echo $i; ?>">
                <br>
                <input type="text" class="input_type" name="form_138[nama_pemberi_<?php echo $i; ?>]" id="nama_pemberi_<?php echo $i; ?>" placeholder="Nama" style="width:90%; text-align:center;">
            </td>

      <!-- Keterangan -->
      <td style="padding:5px;">
        <div contenteditable="true"
          class="input_type"
          name="form_138[keterangan_<?php echo $i; ?>]"
          id="keterangan_<?php echo $i; ?>"
          onchange="fillthis('keterangan_<?php echo $i; ?>')"
          style="width:100%; min-height:50px; border:1px solid #ccc; overflow:visible;">
          <?php echo isset($value_form['keterangan_'.$i]) ? nl2br($value_form['keterangan_'.$i]) : '' ?>
        </div>
      </td>
    </tr>
    <?php endfor; ?>
  </tbody>
</table>



<?php echo $footer; ?>

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