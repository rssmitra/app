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
  <b><u>PELAKSANAAN HEMODIALISIS</u></b><br>
</div>
<br>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<!-- ================== PRE & POST DIALISIS ================== -->
<table width="100%" border="1" style="border-collapse:collapse; font-size:13px;">
  <tr>
    <th width="50%" style="text-align:center;">PRE DIALISIS</th>
    <th width="50%" style="text-align:center;">POS DIALISIS</th>
  </tr>
<tr>
  <td style="vertical-align:top; padding:5px;">
    K/U :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[ku_pre]" 
      id="ku_pre" 
      onchange="fillthis('ku_pre')" 
      style="width:100px;"
    > 
    <br>

    Berat Badan Kering :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[bb_kering]" 
      id="bb_kering" 
      onchange="fillthis('bb_kering')" 
      style="width:80px;"
    > kg
    <br>

    TD :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[td_pre]" 
      id="td_pre" 
      onchange="fillthis('td_pre')" 
      style="width:80px;"
    >
    N :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[n_pre]" 
      id="n_pre" 
      onchange="fillthis('n_pre')" 
      style="width:50px;"
    >
    S :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[s_pre]" 
      id="s_pre" 
      onchange="fillthis('s_pre')" 
      style="width:50px;"
    >
    P :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[p_pre]" 
      id="p_pre" 
      onchange="fillthis('p_pre')" 
      style="width:50px;"
    >
    <br>

    BB :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[bb_pre]" 
      id="bb_pre" 
      onchange="fillthis('bb_pre')" 
      style="width:80px;"
    > kg
    Kenaikan :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[kenaikan]" 
      id="kenaikan" 
      onchange="fillthis('kenaikan')" 
      style="width:80px;"
    > kg
  </td>

  <td style="vertical-align:top; padding:5px;">
    K/U :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[ku_post]" 
      id="ku_post" 
      onchange="fillthis('ku_post')" 
      style="width:100px;"
    > 
    <br><br>

    TD :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[td_post]" 
      id="td_post" 
      onchange="fillthis('td_post')" 
      style="width:80px;"
    >
    N :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[n_post]" 
      id="n_post" 
      onchange="fillthis('n_post')" 
      style="width:50px;"
    >
    S :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[s_post]" 
      id="s_post" 
      onchange="fillthis('s_post')" 
      style="width:50px;"
    >
    P :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[p_post]" 
      id="p_post" 
      onchange="fillthis('p_post')" 
      style="width:50px;"
    >
    <br>

    BB :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[bb_post]" 
      id="bb_post" 
      onchange="fillthis('bb_post')" 
      style="width:80px;"
    > kg
    Penurunan :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[penurunan]" 
      id="penurunan" 
      onchange="fillthis('penurunan')" 
      style="width:80px;"
    > kg
  </td>
</tr>

</table>

<!-- ================== PROGRAM HD ================== -->
<br>
<table width="100%" border="1" style="border-collapse:collapse; font-size:13px;">
  <tr>
    <th width="50%" style="text-align:center;">Program HD</th>
    <th width="50%" style="text-align:center;">Heparinisasi</th>
  </tr>
  <tr>
  <td style="vertical-align:top; padding:5px;">
    Akses Vaskuler :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[akses_vaskuler]" 
      id="akses_vaskuler" 
      onchange="fillthis('akses_vaskuler')" 
      style="width:200px;"
    ><br>

    Jam Mulai :
    <input 
      type="text" 
      class="input_type time-picker" 
      name="form_139[jam_mulai]" 
      id="jam_mulai" 
      onchange="fillthis('jam_mulai')" 
      style="width:100px;"
    ><br>

    Lama HD :
    <input 
      type="text" 
      class="input_type" 
      name="form_139[lama_hd]" 
      id="lama_hd" 
      onchange="fillthis('lama_hd')" 
      style="width:80px;"
    > jam<br>

    Selesai :
    <input 
      type="text" 
      class="input_type time-picker" 
      name="form_139[jam_selesai]" 
      id="jam_selesai" 
      onchange="fillthis('jam_selesai')" 
      style="width:100px;"
    >
  </td>

  <td style="vertical-align:top; padding:5px;">
  Regional :
  <input 
    type="text" 
    class="input_type" 
    name="form_139[heparin_regional]" 
    id="heparin_regional" 
    onchange="fillthis('heparin_regional')" 
    style="width:100px;"
  > u
  <br>

  Continous :
  <input 
    type="text" 
    class="input_type" 
    name="form_139[heparin_cont]" 
    id="heparin_cont" 
    onchange="fillthis('heparin_cont')" 
    style="width:100px;"
  > u
  <br>

  Umum :
  <input 
    type="text" 
    class="input_type" 
    name="form_139[heparin_umum]" 
    id="heparin_umum" 
    onchange="fillthis('heparin_umum')" 
    style="width:100px;"
  > u
  <br>

  Per-jam :
  <input 
    type="text" 
    class="input_type" 
    name="form_139[heparin_perjam]" 
    id="heparin_perjam" 
    onchange="fillthis('heparin_perjam')" 
    style="width:100px;"
  > u
</td>

</tr>

</table>

<!-- ================== TABEL PELAKSANAAN HD ================== -->
<br>
<table width="100%" border="1" style="border-collapse:collapse; font-size:12px; text-align:center;">
  <thead style="background:#e8e8e8;">
    <tr>
      <th style="width:50px; text-align:center;">Jam</th>
      <th style="width:50px; text-align:center;">QB</th>
      <th style="width:50px; text-align:center;">QD</th>
      <th style="width:50px; text-align:center;">UFR/Jam</th>
      <th style="width:50px; text-align:center;">UFG</th>
      <th style="width:50px; text-align:center;">TD</th>
      <th style="width:50px; text-align:center;">N</th>
      <th style="width:50px; text-align:center;">Sh</th>
      <th style="width:50px; text-align:center;">P</th>
      <th style="width:200px; text-align:center;">Respon</th>
      <th style="width:100px; text-align:center;">Obat</th>
      <th style="width:150px; text-align:center;">Evaluasi</th>
      <th style="width:130px; text-align:center;">Paraf</th>
    </tr>
  </thead>
  <tbody>
  <?php for($i=1; $i<=20; $i++): ?>
  <tr valign="top">
    <!-- Jam -->
    <td style="padding:5px;">
      <div contenteditable="true"
        class="input_type"
        name="form_139[jam_<?php echo $i;?>]"
        id="jam_<?php echo $i;?>"
        onchange="fillthis('jam_<?php echo $i;?>')"
        style="width:100%; min-height:45px; border:1px solid #ccc;">
        <?php echo isset($value_form['jam_'.$i]) ? nl2br($value_form['jam_'.$i]) : '' ?>
      </div>
    </td>

    <!-- QB -->
    <td style="padding:5px;">
      <div contenteditable="true"
        class="input_type"
        name="form_139[qb_<?php echo $i;?>]"
        id="qb_<?php echo $i;?>"
        onchange="fillthis('qb_<?php echo $i;?>')"
        style="width:100%; min-height:45px; border:1px solid #ccc;">
        <?php echo isset($value_form['qb_'.$i]) ? nl2br($value_form['qb_'.$i]) : '' ?>
      </div>
    </td>

    <!-- QD -->
    <td style="padding:5px;">
      <div contenteditable="true"
        class="input_type"
        name="form_139[qd_<?php echo $i;?>]"
        id="qd_<?php echo $i;?>"
        onchange="fillthis('qd_<?php echo $i;?>')"
        style="width:100%; min-height:45px; border:1px solid #ccc;">
        <?php echo isset($value_form['qd_'.$i]) ? nl2br($value_form['qd_'.$i]) : '' ?>
      </div>
    </td>

    <!-- UFR -->
    <td style="padding:5px;">
      <div contenteditable="true"
        class="input_type"
        name="form_139[ufr_<?php echo $i;?>]"
        id="ufr_<?php echo $i;?>"
        onchange="fillthis('ufr_<?php echo $i;?>')"
        style="width:100%; min-height:45px; border:1px solid #ccc;">
        <?php echo isset($value_form['ufr_'.$i]) ? nl2br($value_form['ufr_'.$i]) : '' ?>
      </div>
    </td>

    <!-- UFG -->
    <td style="padding:5px;">
      <div contenteditable="true"
        class="input_type"
        name="form_139[ufg_<?php echo $i;?>]"
        id="ufg_<?php echo $i;?>"
        onchange="fillthis('ufg_<?php echo $i;?>')"
        style="width:100%; min-height:45px; border:1px solid #ccc;">
        <?php echo isset($value_form['ufg_'.$i]) ? nl2br($value_form['ufg_'.$i]) : '' ?>
      </div>
    </td>

    <!-- TD -->
    <td style="padding:5px;">
      <div contenteditable="true"
        class="input_type"
        name="form_139[td_<?php echo $i;?>]"
        id="td_<?php echo $i;?>"
        onchange="fillthis('td_<?php echo $i;?>')"
        style="width:100%; min-height:45px; border:1px solid #ccc;">
        <?php echo isset($value_form['td_'.$i]) ? nl2br($value_form['td_'.$i]) : '' ?>
      </div>
    </td>

    <!-- N -->
    <td style="padding:5px;">
      <div contenteditable="true"
        class="input_type"
        name="form_139[n_<?php echo $i;?>]"
        id="n_<?php echo $i;?>"
        onchange="fillthis('n_<?php echo $i;?>')"
        style="width:100%; min-height:45px; border:1px solid #ccc;">
        <?php echo isset($value_form['n_'.$i]) ? nl2br($value_form['n_'.$i]) : '' ?>
      </div>
    </td>

    <!-- SH -->
    <td style="padding:5px;">
      <div contenteditable="true"
        class="input_type"
        name="form_139[sh_<?php echo $i;?>]"
        id="sh_<?php echo $i;?>"
        onchange="fillthis('sh_<?php echo $i;?>')"
        style="width:100%; min-height:45px; border:1px solid #ccc;">
        <?php echo isset($value_form['sh_'.$i]) ? nl2br($value_form['sh_'.$i]) : '' ?>
      </div>
    </td>

    <!-- P -->
    <td style="padding:5px;">
      <div contenteditable="true"
        class="input_type"
        name="form_139[p_<?php echo $i;?>]"
        id="p_<?php echo $i;?>"
        onchange="fillthis('p_<?php echo $i;?>')"
        style="width:100%; min-height:45px; border:1px solid #ccc;">
        <?php echo isset($value_form['p_'.$i]) ? nl2br($value_form['p_'.$i]) : '' ?>
      </div>
    </td>

    <!-- Respon -->
    <td style="padding:5px;">
      <div contenteditable="true"
        class="input_type"
        name="form_139[respon_<?php echo $i;?>]"
        id="respon_<?php echo $i;?>"
        onchange="fillthis('respon_<?php echo $i;?>')"
        style="width:100%; min-height:45px; border:1px solid #ccc;">
        <?php echo isset($value_form['respon_'.$i]) ? nl2br($value_form['respon_'.$i]) : '' ?>
      </div>
    </td>

    <!-- Obat -->
    <td style="padding:5px;">
      <div contenteditable="true"
        class="input_type"
        name="form_139[obat_<?php echo $i;?>]"
        id="obat_<?php echo $i;?>"
        onchange="fillthis('obat_<?php echo $i;?>')"
        style="width:100%; min-height:45px; border:1px solid #ccc;">
        <?php echo isset($value_form['obat_'.$i]) ? nl2br($value_form['obat_'.$i]) : '' ?>
      </div>
    </td>

    <!-- Evaluasi -->
    <td style="padding:5px;">
      <div contenteditable="true"
        class="input_type"
        name="form_139[evaluasi_<?php echo $i;?>]"
        id="evaluasi_<?php echo $i;?>"
        onchange="fillthis('evaluasi_<?php echo $i;?>')"
        style="width:100%; min-height:45px; border:1px solid #ccc;">
        <?php echo isset($value_form['evaluasi_'.$i]) ? nl2br($value_form['evaluasi_'.$i]) : '' ?>
      </div>
    </td>

    <!-- Paraf Perawat -->
    <td style="text-align:center;">
      <span class="ttd-btn" data-role="perawat_<?php echo $i;?>" id="ttd_perawat_btn_<?php echo $i;?>" style="cursor:pointer;">
        <i class="fa fa-pencil blue"></i>
      </span>
      <br>
      <img id="img_ttd_perawat_<?php echo $i;?>" 
           src="<?php echo isset($value_form['img_ttd_perawat_'.$i])?$value_form['img_ttd_perawat_'.$i]:'';?>" 
           style="display:<?php echo isset($value_form['img_ttd_perawat_'.$i])?'block':'none';?>; max-width:80px; max-height:30px; margin-top:5px;">
      <input type="hidden" name="form_139[img_ttd_perawat_<?php echo $i;?>]" id="input_ttd_perawat_<?php echo $i;?>">
      <br>
      <input type="text" class="input_type" name="form_139[nama_perawat_<?php echo $i;?>]" id="nama_perawat_<?php echo $i;?>" placeholder="Nama" style="width:90%; text-align:center;">
    </td>
  </tr>
  <?php endfor; ?>
</tbody>

</table>

<tabel>
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px; vertical-align: top;">
    <b>Catatan:</b><br>
    <div contenteditable="true"
         class="input_type"
         name="form_139[catatan]"
         id="catatan"
         onchange="fillthis('catatan')"
         style="width: 100%; min-height: 80px; overflow: visible; border: 1px solid #ccc; padding: 5px;">
      <?php echo isset($value_form['catatan']) ? nl2br($value_form['catatan']) : '' ?>
    </div>
  </td>
</tr>
</tabel>


<!-- <br>
<b>Catatan:</b><br>
<textarea class="input_type" name="form_139[catatan]" id="catatan" style="width:100%; height:80px;"></textarea> -->

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