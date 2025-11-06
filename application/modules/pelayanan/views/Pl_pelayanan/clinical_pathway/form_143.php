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
      var hiddenInputName = 'form_143[ttd_' + role + ']';
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
  <b>MONITORING INTRA SEDASI / ANESTESI</b><br>
</div>

<style>
  .obat-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    border: 1px solid #8ab6d6;
    margin-top: 10px;
  }
  .obat-table th {
    background-color: #e0f0ff;
    border: 1px solid #8ab6d6;
    padding: 4px;
    text-align: center;
  }
  .obat-table td {
    border: 1px solid #8ab6d6;
    padding: 3px;
    text-align: center;
  }
  .obat-input {
    width: 100%;
    border: none;
    text-align: center;
    background-color: #f8fcff;
  }
  .obat-input:focus {
    background-color: #eaf6ff;
    outline: none;
  }
  .checklist-row td {
    text-align: left;
    padding-left: 15px;
  }
</style>

<br>

<table width="100%" class="obat-table" border="1" style="font-size:12px; text-align:center; border-collapse:collapse;">
  <thead>
  <tr>
    <th rowspan="2" style="width:30px; text-align:center;">NO</th>
    <th rowspan="2" style="width:150px; text-align:center;">OBAT</th>
    <th rowspan="2" style="width:60px; text-align:center;">UNIT</th>
    <th colspan="16" style="text-align:center;">DOSIS</th>
    <th rowspan="2" style="width:60px; text-align:center;">TOTAL</th>
    <th rowspan="2" style="width:100px; text-align:center;">KET</th>
  </tr>
  <tr>
    <?php for($d=1;$d<=16;$d++): ?>
      <th style="width:35px; text-align:center;">D<?php echo $d;?></th>
    <?php endfor; ?>
  </tr>
</thead>

  <tbody>
    <?php for($i=1;$i<=12;$i++): ?>
    <tr>
      <td><?php echo $i; ?></td>

      <!-- OBAT -->
      <td style="padding:3px;">
        <div contenteditable="true" class="input_type"
          name="form_143[obat_<?php echo $i;?>]" id="obat_<?php echo $i;?>"
          onchange="fillthis('obat_<?php echo $i;?>')"
          style="min-height:20px; border:1px solid #ccc;"></div>
      </td>

      <!-- UNIT -->
      <td style="padding:3px;">
        <div contenteditable="true" class="input_type"
          name="form_143[unit_<?php echo $i;?>]" id="unit_<?php echo $i;?>"
          onchange="fillthis('unit_<?php echo $i;?>')"
          style="min-height:20px; border:1px solid #ccc;"></div>
      </td>

      <!-- 16 KOLOM DOSIS -->
      <?php for($d=1;$d<=16;$d++): ?>
        <td style="padding:3px;">
          <div contenteditable="true" class="input_type"
            name="form_143[dosis_<?php echo $i;?>_<?php echo $d;?>]"
            id="dosis_<?php echo $i;?>_<?php echo $d;?>"
            onchange="fillthis('dosis_<?php echo $i;?>_<?php echo $d;?>')"
            style="min-height:20px; border:1px solid #ccc;"></div>
        </td>
      <?php endfor; ?>

      <!-- TOTAL -->
      <td style="padding:3px;">
        <div contenteditable="true" class="input_type"
          name="form_143[total_<?php echo $i;?>]" id="total_<?php echo $i;?>"
          onchange="fillthis('total_<?php echo $i;?>')"
          style="min-height:20px; border:1px solid #ccc;"></div>
      </td>

      <!-- KETERANGAN -->
      <td style="padding:3px;">
        <div contenteditable="true" class="input_type"
          name="form_143[ket_<?php echo $i;?>]" id="ket_<?php echo $i;?>"
          onchange="fillthis('ket_<?php echo $i;?>')"
          style="min-height:20px; border:1px solid #ccc;"></div>
      </td>
    </tr>
    <?php endfor; ?>
    <!-- Baris tambahan untuk Ceklis Obat -->
    <?php 
      $extra_obat = [
        "Sevoflurane",
        "Halothane",
        "Isoflurane",
        "O₂",
        "N₂O"
      ];
      $no = 13;
      foreach($extra_obat as $nama):
    ?>
      <tr>
        <td style="text-align:center;"><?php echo $no++; ?></td>
        <td>
  <?php 
    $key = strtolower(str_replace([' ', '₂'], ['_', '2'], $nama)); 
    $field_name = "form_143[".$key."]";
  ?>
  <label>
    <input 
      type="checkbox" 
      class="ace"
      name="<?php echo $field_name; ?>"
      id="<?php echo $key; ?>"
      value="1"
      <?php echo isset($data_form[$key]) && $data_form[$key] == '1' ? 'checked' : ''; ?>
      onclick="checkthis('<?php echo $key; ?>')"
    >
    <span class="lbl"><?php echo $nama; ?></span>
  </label>
</td>
        <td><div contenteditable="true" style="text-align:center;" class="input_type" name="form_143[unit_<?php echo strtolower($nama); ?>]" style="min-height:20px;"></div></td>
        <?php for($d=1;$d<=16;$d++): ?>
          <td><div contenteditable="true" class="input_type" name="form_143[dosis_<?php echo strtolower($nama); ?>_<?php echo $d; ?>]" style="min-height:20px;"></div></td>
        <?php endfor; ?>
        <td><div contenteditable="true" class="input_type" name="form_143[total_<?php echo strtolower($nama); ?>]" style="min-height:20px;"></div></td>
        <td><div contenteditable="true" class="input_type" name="form_143[ket_<?php echo strtolower($nama); ?>]" style="min-height:20px;"></div></td>
      </tr>
    <?php endforeach; ?>

  </tbody>
</table>

<br>

<!-- END -->

<style>
  .monitor-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    border: 1px solid #8ab6d6;
  }
  .monitor-table th {
    background-color: #e0f0ff;
    border: 1px solid #8ab6d6;
    padding: 4px;
    text-align: center;
  }
  .monitor-table td {
    border: 1px solid #8ab6d6;
    padding: 4px;
  }
  .monitor-input {
    width: 100%;
    border: none;
    text-align: center;
    background-color: #f8fcff;
  }
  .monitor-input:focus {
    background-color: #eaf6ff;
    outline: none;
  }
  .section-title {
    background:#d9ecff;
    font-weight:bold;
    text-align:center;
  }
</style>

<table class="monitor-table">
  <tr>
    <th colspan="6">PRA <br>INDUKSI & SEDASI/ANESTESI</th>
  </tr>
  <tr>
    <th>No</th>
    <th>Jam</th>
    <th>Nadi (HR)</th>
    <th>TD (mmHg)</th>
    <th>Obat / Dosis</th>
    <th>Keterangan</th>
  </tr>

<?php 
$sections = ['PREMEDIKASI', 'MEDIKASI', 'OBAT EMERGENSI'];
foreach($sections as $section){
  // buat key aman: lowercase, spasi -> underscore, non-alnum -> remove
  $key = strtolower($section);
  $key = preg_replace('/[^a-z0-9]+/','_',$key); // hasil: premedikasi, medikasi, obat_emergensi
?>
  <tr><td colspan="6" class="section-title"><?= $section ?></td></tr>

  <?php for($i=1;$i<=6;$i++){ ?>
  <tr>
    <td style="text-align:center;"><?= $i ?></td>

    <td>
      <input type="text" class="monitor-input" 
         name="form_143[<?= $key ?>_jam<?= $i ?>]" 
         id="<?= $key ?>_jam<?= $i ?>"
         onchange="fillthis('<?= $key ?>_jam<?= $i ?>')">
    </td>

    <td>
      <input type="text" class="monitor-input" 
         name="form_143[<?= $key ?>_hr<?= $i ?>]" 
         id="<?= $key ?>_hr<?= $i ?>"
         onchange="fillthis('<?= $key ?>_hr<?= $i ?>')">
    </td>

    <td>
      <input type="text" class="monitor-input" 
         name="form_143[<?= $key ?>_td<?= $i ?>]" 
         id="<?= $key ?>_td<?= $i ?>"
         onchange="fillthis('<?= $key ?>_td<?= $i ?>')">
    </td>

    <td>
      <input type="text" class="monitor-input" 
         name="form_143[<?= $key ?>_obat<?= $i ?>]" 
         id="<?= $key ?>_obat<?= $i ?>"
         onchange="fillthis('<?= $key ?>_obat<?= $i ?>')">
    </td>

    <td>
      <input type="text" class="monitor-input" 
         name="form_143[<?= $key ?>_ket<?= $i ?>]" 
         id="<?= $key ?>_ket<?= $i ?>"
         onchange="fillthis('<?= $key ?>_ket<?= $i ?>')">
    </td>
  </tr>
  <?php } // end for i ?>
<?php } // end foreach ?>

</table>

<br>

<style>
.small-box {
  width: 50px;
  height: 22px;
  border: 1px solid #8ab6d6;
  display: inline-block;
  margin: 1px;
}
.section-title {
  background:#d9ecff;
  font-weight:bold;
  text-align:center;
}
</style>

<table class="monitor-table">
  <tr>
    <th style="width:150px;">Ventilasi</th>
    <?php for($i=1;$i<=16;$i++): ?>
      <th style="width:25px; text-align:center;"><?php echo $i; ?></th>
    <?php endfor; ?>
  </tr>

<tr>
  <td>Mode Ventilasi<br><small>(S / B / K)</small></td>

  <!-- Kolom 1 -->
  <td style="text-align:center;">
    <label>
      <input type="checkbox" class="ace" name="form_143[vent_mode_1_s]" id="vent_mode_1_s" value="S" onclick="checkthis('vent_mode_1_s')">
      <span class="lbl">S</span>
    </label><br>
    <label>
      <input type="checkbox" class="ace" name="form_143[vent_mode_1_b]" id="vent_mode_1_b" value="B" onclick="checkthis('vent_mode_1_b')">
      <span class="lbl">B</span>
    </label><br>
    <label>
      <input type="checkbox" class="ace" name="form_143[vent_mode_1_k]" id="vent_mode_1_k" value="K" onclick="checkthis('vent_mode_1_k')">
      <span class="lbl">K</span>
    </label>
  </td>

  <!-- Kolom 2 -->
  <td style="text-align:center;">
    <label>
      <input type="checkbox" class="ace" name="form_143[vent_mode_2_s]" id="vent_mode_2_s" value="S" onclick="checkthis('vent_mode_2_s')">
      <span class="lbl">S</span>
    </label><br>
    <label>
      <input type="checkbox" class="ace" name="form_143[vent_mode_2_b]" id="vent_mode_2_b" value="B" onclick="checkthis('vent_mode_2_b')">
      <span class="lbl">B</span>
    </label><br>
    <label>
      <input type="checkbox" class="ace" name="form_143[vent_mode_2_k]" id="vent_mode_2_k" value="K" onclick="checkthis('vent_mode_2_k')">
      <span class="lbl">K</span>
    </label>
  </td>

  <!-- Kolom 3 -->
  <td style="text-align:center;">
    <label><input type="checkbox" class="ace" name="form_143[vent_mode_3_s]" id="vent_mode_3_s" value="S" onclick="checkthis('vent_mode_3_s')"><span class="lbl">S</span></label><br>
    <label><input type="checkbox" class="ace" name="form_143[vent_mode_3_b]" id="vent_mode_3_b" value="B" onclick="checkthis('vent_mode_3_b')"><span class="lbl">B</span></label><br>
    <label><input type="checkbox" class="ace" name="form_143[vent_mode_3_k]" id="vent_mode_3_k" value="K" onclick="checkthis('vent_mode_3_k')"><span class="lbl">K</span></label>
  </td>

  <!-- Kolom 4 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_4_s]" id="vent_mode_4_s" value="S" onclick="checkthis('vent_mode_4_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_4_b]" id="vent_mode_4_b" value="B" onclick="checkthis('vent_mode_4_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_4_k]" id="vent_mode_4_k" value="K" onclick="checkthis('vent_mode_4_k')"><span class="lbl">K</span></label>
</td>

<!-- Kolom 5 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_5_s]" id="vent_mode_5_s" value="S" onclick="checkthis('vent_mode_5_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_5_b]" id="vent_mode_5_b" value="B" onclick="checkthis('vent_mode_5_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_5_k]" id="vent_mode_5_k" value="K" onclick="checkthis('vent_mode_5_k')"><span class="lbl">K</span></label>
</td>

<!-- Kolom 6 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_6_s]" id="vent_mode_6_s" value="S" onclick="checkthis('vent_mode_6_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_6_b]" id="vent_mode_6_b" value="B" onclick="checkthis('vent_mode_6_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_6_k]" id="vent_mode_6_k" value="K" onclick="checkthis('vent_mode_6_k')"><span class="lbl">K</span></label>
</td>

<!-- Kolom 7 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_7_s]" id="vent_mode_7_s" value="S" onclick="checkthis('vent_mode_7_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_7_b]" id="vent_mode_7_b" value="B" onclick="checkthis('vent_mode_7_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_7_k]" id="vent_mode_7_k" value="K" onclick="checkthis('vent_mode_7_k')"><span class="lbl">K</span></label>
</td>

<!-- Kolom 8 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_8_s]" id="vent_mode_8_s" value="S" onclick="checkthis('vent_mode_8_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_8_b]" id="vent_mode_8_b" value="B" onclick="checkthis('vent_mode_8_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_8_k]" id="vent_mode_8_k" value="K" onclick="checkthis('vent_mode_8_k')"><span class="lbl">K</span></label>
</td>

<!-- Kolom 9 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_9_s]" id="vent_mode_9_s" value="S" onclick="checkthis('vent_mode_9_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_9_b]" id="vent_mode_9_b" value="B" onclick="checkthis('vent_mode_9_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_9_k]" id="vent_mode_9_k" value="K" onclick="checkthis('vent_mode_9_k')"><span class="lbl">K</span></label>
</td>

<!-- Kolom 10 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_10_s]" id="vent_mode_10_s" value="S" onclick="checkthis('vent_mode_10_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_10_b]" id="vent_mode_10_b" value="B" onclick="checkthis('vent_mode_10_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_10_k]" id="vent_mode_10_k" value="K" onclick="checkthis('vent_mode_10_k')"><span class="lbl">K</span></label>
</td>

<!-- Kolom 11 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_11_s]" id="vent_mode_11_s" value="S" onclick="checkthis('vent_mode_11_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_11_b]" id="vent_mode_11_b" value="B" onclick="checkthis('vent_mode_11_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_11_k]" id="vent_mode_11_k" value="K" onclick="checkthis('vent_mode_11_k')"><span class="lbl">K</span></label>
</td>

<!-- Kolom 12 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_12_s]" id="vent_mode_12_s" value="S" onclick="checkthis('vent_mode_12_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_12_b]" id="vent_mode_12_b" value="B" onclick="checkthis('vent_mode_12_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_12_k]" id="vent_mode_12_k" value="K" onclick="checkthis('vent_mode_12_k')"><span class="lbl">K</span></label>
</td>

<!-- Kolom 13 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_13_s]" id="vent_mode_13_s" value="S" onclick="checkthis('vent_mode_13_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_13_b]" id="vent_mode_13_b" value="B" onclick="checkthis('vent_mode_13_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_13_k]" id="vent_mode_13_k" value="K" onclick="checkthis('vent_mode_13_k')"><span class="lbl">K</span></label>
</td>

<!-- Kolom 14 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_14_s]" id="vent_mode_14_s" value="S" onclick="checkthis('vent_mode_14_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_14_b]" id="vent_mode_14_b" value="B" onclick="checkthis('vent_mode_14_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_14_k]" id="vent_mode_14_k" value="K" onclick="checkthis('vent_mode_14_k')"><span class="lbl">K</span></label>
</td>

<!-- Kolom 15 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_15_s]" id="vent_mode_15_s" value="S" onclick="checkthis('vent_mode_15_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_15_b]" id="vent_mode_15_b" value="B" onclick="checkthis('vent_mode_15_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_15_k]" id="vent_mode_15_k" value="K" onclick="checkthis('vent_mode_15_k')"><span class="lbl">K</span></label>
</td>

<!-- Kolom 16 -->
<td style="text-align:center;">
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_16_s]" id="vent_mode_16_s" value="S" onclick="checkthis('vent_mode_16_s')"><span class="lbl">S</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_16_b]" id="vent_mode_16_b" value="B" onclick="checkthis('vent_mode_16_b')"><span class="lbl">B</span></label><br>
  <label><input type="checkbox" class="ace" name="form_143[vent_mode_16_k]" id="vent_mode_16_k" value="K" onclick="checkthis('vent_mode_16_k')"><span class="lbl">K</span></label>
</td>

</tr>



  <?php 
    $vent_params = [
      'Sp O2' => 'spo2',
      'ET CO2' => 'etco2',
      'Vt (Tidal Volume)' => 'vt',
      'F (Frekuensi Napas)' => 'f'
    ];

    foreach($vent_params as $label => $key):
  ?>
    <tr>
      <td><?php echo $label; ?></td>

      <?php for($d=1;$d<=16;$d++): ?>
        <td style="padding:2px;">
          <div contenteditable="true"
            class="input_type"
            name="form_143[vent_<?php echo $key;?>_<?php echo $d;?>]"
            id="vent_<?php echo $key;?>_<?php echo $d;?>"
            onchange="fillthis('vent_<?php echo $key;?>_<?php echo $d;?>')"
            style="min-height:20px; border:1px solid #ccc; text-align:center;"></div>
        </td>
      <?php endfor; ?>
    </tr>
  <?php endforeach; ?>
</table>
<br>
<!-- ================== FORM 143 : RESUME ANESTESI (BARIS 1) ================== -->
<table width="100%" border="1" style="border-collapse:collapse; font-size:13px;">
  <tr>
    <td style="width:50%; padding:10px; vertical-align:top;">
      <b>MASALAH</b><br>
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[masalah]" 
           id="masalah" 
           onchange="fillthis('masalah')"
           style="min-height:80px;">
        <?php echo isset($value_form['masalah']) ? nl2br($value_form['masalah']) : '' ?>
      </div>
    </td>

    <td style="width:50%; padding:10px; vertical-align:top;">
      <b>RESUME ANESTESI</b><br>
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[resume_anestesi]" 
           id="resume_anestesi" 
           onchange="fillthis('resume_anestesi')"
           style="min-height:80px;">
        <?php echo isset($value_form['resume_anestesi']) ? nl2br($value_form['resume_anestesi']) : '' ?>
      </div>
    </td>
  </tr>
</table>

<!-- ================== FORM 143 : RESUME ANESTESI (BARIS 2) ================== -->
<table width="100%" border="1" style="border-collapse:collapse; font-size:13px; text-align:center;">
  <tr style="background:#e8e8e8;">
    <td rowspan="2" style="width:25%; padding:5px;"><b>JENIS / TEKNIK ANESTESI</b></td>
    <td rowspan="2" style="width:25%; padding:5px;"><b>ALAT ANESTESI</b></td>
    <td rowspan="2" style="width:25%; padding:5px;"><b>PROSEDUR</b></td>
    <td rowspan="2" style="width:25%; padding:5px;"><b>HASIL</b></td>
  </tr>
  <tr></tr>

  <tr>
    <!-- Jenis / Teknik Anestesi -->
    <td style="padding:5px; text-align:left;">
      <b>UMUM</b><br>
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[jenis_anestesi_umum]" 
           id="jenis_anestesi_umum" 
           onchange="fillthis('jenis_anestesi_umum')"
           style="min-height:20px;">
        <?php echo isset($value_form['jenis_anestesi_umum']) ? nl2br($value_form['jenis_anestesi_umum']) : '' ?>
      </div>
      <b>REGIONAL</b><br>
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[jenis_anestesi_regional]" 
           id="jenis_anestesi_regional" 
           onchange="fillthis('jenis_anestesi_regional')"
           style="min-height:20px;">
        <?php echo isset($value_form['jenis_anestesi_regional']) ? nl2br($value_form['jenis_anestesi_regional']) : '' ?>
      </div>
    </td>

    <!-- Alat Anestesi -->
    <td style="padding:5px;">
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[alat_anestesi]" 
           id="alat_anestesi" 
           onchange="fillthis('alat_anestesi')"
           style="min-height:80px;">
        <?php echo isset($value_form['alat_anestesi']) ? nl2br($value_form['alat_anestesi']) : '' ?>
      </div>
    </td>

    <!-- Prosedur -->
    <td style="padding:5px;">
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[prosedur]" 
           id="prosedur" 
           onchange="fillthis('prosedur')"
           style="min-height:80px;">
        <?php echo isset($value_form['prosedur']) ? nl2br($value_form['prosedur']) : '' ?>
      </div>
    </td>

    <!-- Hasil -->
    <td style="padding:5px;">
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[hasil]" 
           id="hasil" 
           onchange="fillthis('hasil')"
           style="min-height:80px;">
        <?php echo isset($value_form['hasil']) ? nl2br($value_form['hasil']) : '' ?>
      </div>
    </td>
  </tr>
</table>

<!-- ================== FORM 143 : RESUME ANESTESI (BARIS 3) ================== -->
<table width="100%" border="1" style="border-collapse:collapse; font-size:13px; text-align:left;">
  <tr>
    <!-- Kolom Bayi Lahir -->
    <td style="width:25%; padding:5px; vertical-align:top;">
      <b>Bayi Lahir</b><br>
      L / P :
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[bayi_lahir_lp]" 
           id="bayi_lahir_lp" 
           onchange="fillthis('bayi_lahir_lp')" 
           style="display:inline-block; width:60px; border-bottom:1px solid #000;">
        <?php echo isset($value_form['bayi_lahir_lp']) ? nl2br($value_form['bayi_lahir_lp']) : '' ?>
      </div><br>
      Jam :
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[bayi_lahir_jam]" 
           id="bayi_lahir_jam" 
           onchange="fillthis('bayi_lahir_jam')" 
           style="display:inline-block; width:80px; border-bottom:1px solid #000;">
        <?php echo isset($value_form['bayi_lahir_jam']) ? nl2br($value_form['bayi_lahir_jam']) : '' ?>
      </div><br>
      PB :
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[bayi_lahir_pb]" 
           id="bayi_lahir_pb" 
           onchange="fillthis('bayi_lahir_pb')" 
           style="display:inline-block; width:50px; border-bottom:1px solid #000;">
        <?php echo isset($value_form['bayi_lahir_pb']) ? nl2br($value_form['bayi_lahir_pb']) : '' ?>
      </div> cm<br>
      BB :
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[bayi_lahir_bb]" 
           id="bayi_lahir_bb" 
           onchange="fillthis('bayi_lahir_bb')" 
           style="display:inline-block; width:50px; border-bottom:1px solid #000;">
        <?php echo isset($value_form['bayi_lahir_bb']) ? nl2br($value_form['bayi_lahir_bb']) : '' ?>
      </div> gr<br>
      AS :
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[bayi_lahir_as]" 
           id="bayi_lahir_as" 
           onchange="fillthis('bayi_lahir_as')" 
           style="display:inline-block; width:50px; border-bottom:1px solid #000;">
        <?php echo isset($value_form['bayi_lahir_as']) ? nl2br($value_form['bayi_lahir_as']) : '' ?>
      </div>
    </td>

    <!-- Kolom Anestesi -->
    <td style="width:25%; padding:5px; vertical-align:top;">
      <b>Anestesi</b><br>
      Awal :
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[anestesi_awal]" 
           id="anestesi_awal" 
           onchange="fillthis('anestesi_awal')" 
           style="display:inline-block; width:80px; border-bottom:1px solid #000;">
        <?php echo isset($value_form['anestesi_awal']) ? nl2br($value_form['anestesi_awal']) : '' ?>
      </div><br>
      Akhir :
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[anestesi_akhir]" 
           id="anestesi_akhir" 
           onchange="fillthis('anestesi_akhir')" 
           style="display:inline-block; width:80px; border-bottom:1px solid #000;">
        <?php echo isset($value_form['anestesi_akhir']) ? nl2br($value_form['anestesi_akhir']) : '' ?>
      </div><br>
      Lama :
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[anestesi_lama]" 
           id="anestesi_lama" 
           onchange="fillthis('anestesi_lama')" 
           style="display:inline-block; width:80px; border-bottom:1px solid #000;">
        <?php echo isset($value_form['anestesi_lama']) ? nl2br($value_form['anestesi_lama']) : '' ?>
      </div>
    </td>

    <!-- Kolom Operasi -->
    <td style="width:25%; padding:5px; vertical-align:top;">
      <b>Operasi</b><br>
      Awal :
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[operasi_awal]" 
           id="operasi_awal" 
           onchange="fillthis('operasi_awal')" 
           style="display:inline-block; width:80px; border-bottom:1px solid #000;">
        <?php echo isset($value_form['operasi_awal']) ? nl2br($value_form['operasi_awal']) : '' ?>
      </div><br>
      Akhir :
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[operasi_akhir]" 
           id="operasi_akhir" 
           onchange="fillthis('operasi_akhir')" 
           style="display:inline-block; width:80px; border-bottom:1px solid #000;">
        <?php echo isset($value_form['operasi_akhir']) ? nl2br($value_form['operasi_akhir']) : '' ?>
      </div><br>
      Lama :
      <div contenteditable="true" 
           class="input_type" 
           name="form_143[operasi_lama]" 
           id="operasi_lama" 
           onchange="fillthis('operasi_lama')" 
           style="display:inline-block; width:80px; border-bottom:1px solid #000;">
        <?php echo isset($value_form['operasi_lama']) ? nl2br($value_form['operasi_lama']) : '' ?>
      </div>
    </td>

    <!-- Kolom Posisi -->
    <td style="width:25%; padding:5px; vertical-align:top;">
      <b>Posisi</b><br>
      <label><input type="checkbox" class="ace" name="form_143[posisi_m]" id="posisi_m" value="M" onclick="checkthis('posisi_m')"> <span class="lbl">M : Miring</span></label><br>
      <label><input type="checkbox" class="ace" name="form_143[posisi_l]" id="posisi_l" value="L" onclick="checkthis('posisi_l')"> <span class="lbl">L : Telentang</span></label><br>
      <label><input type="checkbox" class="ace" name="form_143[posisi_d]" id="posisi_d" value="D" onclick="checkthis('posisi_d')"> <span class="lbl">D : Duduk</span></label><br>
      <label><input type="checkbox" class="ace" name="form_143[posisi_k]" id="posisi_k" value="K" onclick="checkthis('posisi_k')"> <span class="lbl">K : Tengkurap</span></label><br>
      <label><input type="checkbox" class="ace" name="form_143[posisi_og]" id="posisi_og" value="OG" onclick="checkthis('posisi_og')"> <span class="lbl">OG : Obsyn</span></label><br>
      <label><input type="checkbox" class="ace" name="form_143[posisi_tb]" id="posisi_tb" value="TB" onclick="checkthis('posisi_tb')"> <span class="lbl">TB : Trend</span></label><br>
      <label><input type="checkbox" class="ace" name="form_143[posisi_atb]" id="posisi_atb" value="ATB" onclick="checkthis('posisi_atb')"> <span class="lbl">ATB : Anti Trend</span></label>
    </td>
  </tr>
</table>

<!-- ================== FORM 143 : PASCA ANESTESI ================== -->

<table width="100%" border="1" style="border-collapse:collapse; font-size:13px;">
  <thead>
    <tr style="background:#e8e8e8;">
      <th class="text-center" colspan="3" style="padding:5px;">PASCA ANESTESI</th>
    </tr>
    <tr>
      <th class="text-center" colspan="2" style="padding:5px;">KELUHAN</th>
      <th class="text-center" style="padding:5px;">KOMPLIKASI</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- Kolom kiri -->
      <td style="padding:5px;">
        <b>Nyeri :</b><br>
        <label><input type="checkbox" class="ace" name="form_143[nyeri_luka_operasi]" id="nyeri_luka_operasi" value="Luka Operasi" onclick="checkthis('nyeri_luka_operasi')"> <span class="lbl">Luka Operasi</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[nyeri_kepala]" id="nyeri_kepala" value="Kepala" onclick="checkthis('nyeri_kepala')"> <span class="lbl">Kepala</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[nyeri_lambung]" id="nyeri_lambung" value="Lambung" onclick="checkthis('nyeri_lambung')"> <span class="lbl">Lambung</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[nyeri_kerongkongan]" id="nyeri_kerongkongan" value="Kerongkongan" onclick="checkthis('nyeri_kerongkongan')"> <span class="lbl">Kerongkongan</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[nyeri_serak]" id="nyeri_serak" value="Serak" onclick="checkthis('nyeri_serak')"> <span class="lbl">Serak</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[nyeri_sesak]" id="nyeri_sesak" value="Sesak" onclick="checkthis('nyeri_sesak')"> <span class="lbl">Sesak</span></label>
      </td>

      <!-- Kolom tengah -->
      <td style="padding:5px;">
        <br>
        <label><input type="checkbox" class="ace" name="form_143[pusing]" id="pusing" value="Pusing" onclick="checkthis('pusing')"> <span class="lbl">Pusing</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[mual]" id="mual" value="Mual" onclick="checkthis('mual')"> <span class="lbl">Mual</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[muntah]" id="muntah" value="Muntah" onclick="checkthis('muntah')"> <span class="lbl">Muntah</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[gatal]" id="gatal" value="Gatal" onclick="checkthis('gatal')"> <span class="lbl">Gatal</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[menggigil]" id="menggigil" value="Menggigil" onclick="checkthis('menggigil')"> <span class="lbl">Menggigil</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[dingin]" id="dingin" value="Dingin" onclick="checkthis('dingin')"> <span class="lbl">Dingin</span></label>
      </td>

      <!-- Kolom kanan -->
      <td style="padding:5px;">
        <label><input type="checkbox" class="ace" name="form_143[kardiovaskuler]" id="kardiovaskuler" value="Kardiovaskuler" onclick="checkthis('kardiovaskuler')"> <span class="lbl">Kardiovaskuler</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[respirasi]" id="respirasi" value="Respirasi" onclick="checkthis('respirasi')"> <span class="lbl">Respirasi</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[pencernaan]" id="pencernaan" value="Pencernaan" onclick="checkthis('pencernaan')"> <span class="lbl">Pencernaan</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[saraf]" id="saraf" value="Saraf" onclick="checkthis('saraf')"> <span class="lbl">Saraf</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[metabolisme]" id="metabolisme" value="Metabolisme" onclick="checkthis('metabolisme')"> <span class="lbl">Metabolisme</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[meninggal]" id="meninggal" value="Meninggal" onclick="checkthis('meninggal')"> <span class="lbl">Meninggal</span></label>
      </td>
    </tr>
  </tbody>
</table>

<!--(lanjutan Pasca Anestesi)-->
<table width="100%" border="1" style="border-collapse:collapse; font-size:13px; margin-top:10px;">
  <thead>
    <tr style="background:#e8e8e8;">
      <th class="text-center" style="padding:5px;">INSTRUKSI</th>
      <th class="text-center" style="padding:5px;">OBAT</th>
      <th class="text-center" style="padding:5px;">LAIN-LAIN</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- Kolom KIRI: INSTRUKSI -->
      <td style="width:40%; padding:5px; vertical-align:top;">
        <div style="margin-top:5px;">
          Infus :
          <div contenteditable="true" 
               class="input_type" 
               name="form_143[instruksi_infus]" 
               id="instruksi_infus" 
               onchange="fillthis('instruksi_infus')"
               style="display:inline-block; min-width:120px; border-bottom:1px solid #ccc;"></div>
          / 24 jam
        </div>
        <div contenteditable="true" 
             class="input_type" 
             name="form_143[instruksi_lain]" 
             id="instruksi_lain" 
             onchange="fillthis('instruksi_lain')"
             style="margin-top:5px; min-height:25px; border:1px solid #ddd; padding:3px;">
        </div>

        <table width="100%" border="1" style="border-collapse:collapse; font-size:12px; margin-top:8px;">
          <thead style="background:#f4f4f4;">
            <tr border="1">
              <th class="text-center" border="1" style="padding:3px;">Macam Cairan</th>
              <th class="text-center" border="1" style="padding:3px;">Jumlah</th>
              <th class="text-center" border="1" style="padding:3px;">Tetes / Menit</th>
            </tr>
          </thead>
          <tbody>
            <?php for ($i = 1; $i <= 4; $i++): ?>
            <tr>
              <td contenteditable="true" class="input_type" name="form_143[cairan_<?php echo $i; ?>]" id="cairan_<?php echo $i; ?>" onchange="fillthis('cairan_<?php echo $i; ?>')" style="padding:4px;"><?php echo isset($value_form['cairan_'.$i]) ? nl2br($value_form['cairan_'.$i]) : '' ?></td>
              <td contenteditable="true" class="input_type" name="form_143[jumlah_<?php echo $i; ?>]" id="jumlah_<?php echo $i; ?>" onchange="fillthis('jumlah_<?php echo $i; ?>')" style="padding:4px;"><?php echo isset($value_form['jumlah_'.$i]) ? nl2br($value_form['jumlah_'.$i]) : '' ?></td>
              <td contenteditable="true" class="input_type" name="form_143[tetes_<?php echo $i; ?>]" id="tetes_<?php echo $i; ?>" onchange="fillthis('tetes_<?php echo $i; ?>')" style="padding:4px;"><?php echo isset($value_form['tetes_'.$i]) ? nl2br($value_form['tetes_'.$i]) : '' ?></td>
            </tr>
            <?php endfor; ?>
          </tbody>
        </table>
      </td>

      <!-- Kolom TENGAH: OBAT -->
      <td style="width:30%; padding:5px; vertical-align:top;">
        <?php for ($i = 1; $i <= 3; $i++): ?>
          <div contenteditable="true" 
               class="input_type" 
               name="form_143[obat_<?php echo $i; ?>]" 
               id="obat_<?php echo $i; ?>" 
               onchange="fillthis('obat_<?php echo $i; ?>')"
               style="margin-top:5px; min-height:25px; border:1px solid #ddd; padding:3px;">
            <?php echo isset($value_form['obat_'.$i]) ? nl2br($value_form['obat_'.$i]) : '' ?>
          </div>
        <?php endfor; ?>
      </td>

      <!-- Kolom KANAN: LAIN-LAIN -->
      <td style="width:30%; padding:5px; vertical-align:top;">
        <label><input type="checkbox" class="ace" name="form_143[boleh_minum]" id="boleh_minum" value="Boleh Minum" onclick="checkthis('boleh_minum')"> <span class="lbl">Boleh Minum : kalau tak muntah / peristalltik (+) / Flatus (+)</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[puasa]" id="puasa" value="Puasa" onclick="checkthis('puasa')"> <span class="lbl">Puasa</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[bila_kesakitan]" id="bila_kesakitan" value="Bila Kesakitan" onclick="checkthis('bila_kesakitan')"> <span class="lbl">Bila Kesakitan</span></label><br>
        <label><input type="checkbox" class="ace" name="form_143[bila_muntah]" id="bila_muntah" value="Bila Muntah" onclick="checkthis('bila_muntah')"> <span class="lbl">Bila Muntah</span></label>
      </td>
    </tr>
  </tbody>
</table>

<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <!-- Dokter Spesialis Anestesi -->
      <td style="width: 50%; text-align:center; padding:10px;">
        Dokter Spesialis Anestesi
        <br><br>
        <span class="ttd-btn" data-role="dokter_anestesi" id="ttd_dokter_anestesi" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_dokter_anestesi" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_143[nama_dokter_anestesi]" id="nama_dokter_anestesi" placeholder="Nama Dokter" style="width:90%; text-align:center;">
        <input type="hidden" name="form_143[ttd_dokter_anestesi]">
      </td>

      <!-- Perawat Anestesi / Perawat -->
      <td style="width: 50%; text-align:center; padding:10px;">
        Perawat Anestesi / Perawat
        <br><br>
        <span class="ttd-btn" data-role="perawat_anestesi" id="ttd_perawat_anestesi" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_perawat_anestesi" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_143[nama_perawat_anestesi]" id="nama_perawat_anestesi" placeholder="Nama Perawat" style="width:90%; text-align:center;">
        <input type="hidden" name="form_143[ttd_perawat_anestesi]">
      </td>
    </tr>
  </tbody>
</table>


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