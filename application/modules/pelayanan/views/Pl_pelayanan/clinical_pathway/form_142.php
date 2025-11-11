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
      var hiddenInputName = 'form_142[ttd_' + role + ']';
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
  <b>STATUS SEDASI / ANESTESI</b><br>
</div>
<br>
<!-- ================== PENGKAJIAN PRA SEDASI / ANESTESI ================== -->
<table width="100%" style="border-collapse:collapse; font-size:13px;" border="1">
  <tr>
    <td style="width:25%; padding:5px;">DPJP</td>
    <td style="width:25%; padding:5px;">
      <div contenteditable="true" class="input_type" name="form_142[dpjp]" id="dpjp" onchange="fillthis('dpjp')">
        <?php echo isset($value_form['dpjp']) ? nl2br($value_form['dpjp']) : '' ?>
      </div>
    </td>
    <td style="width:25%; padding:5px;">Dr Anestesi</td>
    <td style="width:25%; padding:5px;">
      <div contenteditable="true" class="input_type" name="form_142[dr_anestesi]" id="dr_anestesi" onchange="fillthis('dr_anestesi')">
        <?php echo isset($value_form['dr_anestesi']) ? nl2br($value_form['dr_anestesi']) : '' ?>
      </div>
    </td>
  </tr>
  <tr>
    <td style="padding:5px;">Asisten Anestesi</td>
    <td style="padding:5px;">
      <div contenteditable="true" class="input_type" name="form_142[asisten_anestesi]" id="asisten_anestesi" onchange="fillthis('asisten_anestesi')">
        <?php echo isset($value_form['asisten_anestesi']) ? nl2br($value_form['asisten_anestesi']) : '' ?>
      </div>
    </td>
    <td style="padding:5px;">Operator I</td>
    <td style="padding:5px;">
      <div contenteditable="true" class="input_type" name="form_142[operator_i]" id="operator_i" onchange="fillthis('operator_i')">
        <?php echo isset($value_form['operator_i']) ? nl2br($value_form['operator_i']) : '' ?>
      </div>
    </td>
  </tr>
  <tr>
    <td style="padding:5px;">Operator II</td>
    <td style="padding:5px;">
      <div contenteditable="true" class="input_type" name="form_142[operator_ii]" id="operator_ii" onchange="fillthis('operator_ii')">
        <?php echo isset($value_form['operator_ii']) ? nl2br($value_form['operator_ii']) : '' ?>
      </div>
    </td>
    <td style="padding:5px;">Tgl. Operasi</td>
    <td style="padding:5px;">
      <div contenteditable="true" class="input_type" name="form_142[tgl_operasi]" id="tgl_operasi" onchange="fillthis('tgl_operasi')">
        <?php echo isset($value_form['tgl_operasi']) ? nl2br($value_form['tgl_operasi']) : '' ?>
      </div>
    </td>
  </tr>
  <tr>
    <td style="padding:5px;">Diagnosis Pra Bedah</td>
    <td style="padding:5px;">
      <div contenteditable="true" class="input_type" name="form_142[diagnosis_pra]" id="diagnosis_pra" onchange="fillthis('diagnosis_pra')">
        <?php echo isset($value_form['diagnosis_pra']) ? nl2br($value_form['diagnosis_pra']) : '' ?>
      </div>
    </td>
    <td style="padding:5px;">Diagnosis Pasca Bedah</td>
    <td style="padding:5px;">
      <div contenteditable="true" class="input_type" name="form_142[diagnosis_pasca]" id="diagnosis_pasca" onchange="fillthis('diagnosis_pasca')">
        <?php echo isset($value_form['diagnosis_pasca']) ? nl2br($value_form['diagnosis_pasca']) : '' ?>
      </div>
    </td>
  </tr>
  <tr>
    <td style="padding:5px;">Tindakan</td>
    <td colspan="3" style="padding:5px;">
      <div contenteditable="true" class="input_type" name="form_142[tindakan]" id="tindakan" onchange="fillthis('tindakan')">
        <?php echo isset($value_form['tindakan']) ? nl2br($value_form['tindakan']) : '' ?>
      </div>
    </td>
  </tr>
</table>

<br>
<table width="100%" border="1" style="border-collapse:collapse; font-size:13px;">
  <tr style="background:#e8e8e8; text-align:center;">
    <th colspan="3" style="padding:5px;text-align:center;">PENGKAJIAN PRA SEDASI / ANESTESI</th>
  </tr>
  <tr>
    <td style="width:25%; padding:5px;"><b>Tanggal :</b>
      <input type="text" class="input_type" name="form_142[tanggal_prasedasi]" id="tanggal_prasedasi" onchange="fillthis('tanggal_prasedasi')" style="width:80%;">
    </td>
    <td style="width:25%; padding:5px;"><b>Jam :</b>
      <input type="text" class="input_type" name="form_142[jam_prasedasi]" id="jam_prasedasi" onchange="fillthis('jam_prasedasi')" style="width:80%;">
    </td>
  </tr>

  <tr style="text-align:center;">
    <th style="width:30%;text-align:center;padding:5px;">ANAMNESA</th>
    <th style="width:40%;text-align:center;padding:5px;" colspan="2">PEMERIKSAAN FISIK & PENUNJANG</th>
  </tr>
  <tr>
    <td style="padding:5px; vertical-align:top;">
      <b>Riwayat Penyakit :</b><br>
        <label><input type="checkbox" class="ace" name="form_142[dm]" id="dm" onclick="checkthis('dm')"> <span class="lbl">DM</span></label><br>
        <label><input type="checkbox" class="ace" name="form_142[hipertensi]" id="hipertensi" onclick="checkthis('hipertensi')"> <span class="lbl">Hipertensi</span></label><br>
        <label><input type="checkbox" class="ace" name="form_142[ginjal]" id="ginjal" onclick="checkthis('ginjal')"> <span class="lbl">Ginjal</span></label><br>
        <label><input type="checkbox" class="ace" name="form_142[tbc]" id="tbc" onclick="checkthis('tbc')"> <span class="lbl">TBC</span></label><br>
        <label><input type="checkbox" class="ace" name="form_142[lain]" id="lain" onclick="checkthis('lain')"> <span class="lbl">Lain-lain</span>
        <input type="text" class="input_type" name="form_142[input_lain_penyakit]" id="input_lain_penyakit" onchange="fillthis('input_lain_penyakit')" style="width: 60%;"></label><br>

        <b>Riwayat Anestesi :</b><br>
        <label><input type="checkbox" class="ace" name="form_142[anestesi_ya]" id="anestesi_ya" onclick="checkthis('anestesi_ya')"> <span class="lbl">Ya,</span>
        Kapan : <input type="text" class="input_type" name="form_142[anestesi_kapan]" id="anestesi_kapan" onchange="fillthis('anestesi_kapan')" style="width:50%;"></label><br>
        <label><input type="checkbox" class="ace" name="form_142[anestesi_tidak]" id="anestesi_tidak" onclick="checkthis('anestesi_tidak')"> <span class="lbl">Tidak</span></label><br>

        <b>Kebiasaan :</b><br>
        <label><input type="checkbox" class="ace" name="form_142[keb_merokok]" id="keb_merokok" onclick="checkthis('keb_merokok')"> <span class="lbl">Merokok</span></label><br>
        <label><input type="checkbox" class="ace" name="form_142[keb_obatlain]" id="keb_obatlain" onclick="checkthis('keb_obatlain')"> <span class="lbl">Obat-obatan</span>
        <input type="text" class="input_type" name="form_142[ket_obatlain]" id="ket_obatlain" onchange="fillthis('ket_obatlain')" style="width:50%;"></label><br>
        <b>Narkoba :</b><br>
        <label><input type="checkbox" class="ace" name="form_130[keb_narkoba_ya]" id="keb_narkoba_ya" onclick="checkthis('keb_narkoba_ya')"> <span class="lbl">Ya</span></label><br>
        <label><input type="checkbox" class="ace" name="form_130[keb_narkoba_tidak]" id="keb_narkoba_tidak" onclick="checkthis('keb_narkoba_tidak')"> <span class="lbl">Tidak</span></label>
        <br>
        <label>Lain-lain : <input type="text" class="input_type" name="form_142[keb_lain]" id="keb_lain" onchange="fillthis('keb_lain')" style="width:60%;"></label>
        <br>

        <b>Riwayat Alergi :</b><br>
        <label><input type="checkbox" class="ace" name="form_142[alergi_ya]" id="alergi_ya" onclick="checkthis('alergi_ya')"> <span class="lbl">Ya,</span></label>
        <label>Jenis : <input type="text" class="input_type" name="form_142[alergi_jenis]" id="alergi_jenis" onchange="fillthis('alergi_jenis')" style="width:50%;"></label><br>
        <label><input type="checkbox" class="ace" name="form_142[alergi_tidak]" id="alergi_tidak" onclick="checkthis('alergi_tidak')"> <span class="lbl">Tidak</span></label>
    </td>

    <td style="padding:5px; vertical-align:top;">


    <b>Keadaan Umum :</b><br>
Gizi : 
<label><input type="checkbox" class="ace" name="form_142[gizi_baik]" id="gizi_baik" onclick="checkthis('gizi_baik')"> <span class="lbl">Baik</span></label>
<label><input type="checkbox" class="ace" name="form_142[gizi_buruk]" id="gizi_buruk" onclick="checkthis('gizi_buruk')"> <span class="lbl">Buruk</span></label><br><br>

<table style="border-collapse: collapse;">
  <tr>
    <td>1. Kesadaran</td>
    <td>: <input type="text" class="input_type" name="form_142[kesadaran]" id="kesadaran" onchange="fillthis('kesadaran')" style="width:150px;"></td>
  </tr>
  <tr>
    <td>2. TD</td>
    <td>: <input type="text" class="input_type" name="form_142[td]" id="td" onchange="fillthis('td')" style="width:80px;"> mmHg</td>
  </tr>
  <tr>
    <td>3. Nadi</td>
    <td>: <input type="text" class="input_type" name="form_142[nadi]" id="nadi" onchange="fillthis('nadi')" style="width:80px;"> x/menit</td>
  </tr>
  <tr>
    <td>4. Suhu</td>
    <td>: <input type="text" class="input_type" name="form_142[suhu]" id="suhu" onchange="fillthis('suhu')" style="width:80px;"> °C</td>
  </tr>
  <tr>
    <td>5. TB </td>
    <td>: <input type="text" class="input_type" name="form_142[tb]" id="tb" onchange="fillthis('tb')" style="width:80px;"> cm</td>
  </tr>
  <tr>
    <td>6. BB</td>
    <td>: <input type="text" class="input_type" name="form_142[bb]" id="bb" onchange="fillthis('bb')" style="width:80px;"> kg</td>
  </tr>
</table>
</td>
    
<td style="width:25%; padding:5px; vertical-align:top;">
  <table width="100%" border="1" style="border-collapse:collapse; font-size:12px;">
    <tr>
      <td style="padding:5px; background:#f7f7f7;"><b>Hasil Laboratorium Tgl :</b></td>
    </tr>
    <tr>
      <td style="padding:5px;">
        <div contenteditable="true"
             class="input_type"
             name="form_142[hasil_lab]"
             id="hasil_lab"
             onchange="fillthis('hasil_lab')"
             style="width:100%; min-height:40px; border:1px solid #ccc; padding:4px;">
          <?php echo isset($value_form['hasil_lab']) ? nl2br($value_form['hasil_lab']) : '' ?>
        </div>
      </td>
    </tr>
    <tr>
      <td style="padding:5px; background:#f7f7f7;"><b>Radiologi / Lain-lain :</b></td>
    </tr>
    <tr>
      <td style="padding:5px;">
        <div contenteditable="true"
             class="input_type"
             name="form_142[radiologi_lain]"
             id="radiologi_lain"
             onchange="fillthis('radiologi_lain')"
             style="width:100%; min-height:40px; border:1px solid #ccc; padding:4px;">
          <?php echo isset($value_form['radiologi_lain']) ? nl2br($value_form['radiologi_lain']) : '' ?>
        </div>
      </td>
    </tr>

    <tr>
  <td style="padding:5px; background:#f7f7f7;"><b>Pernapasan :</b></td>
</tr>
<tr>
  <td style="padding:5px;">
    <div contenteditable="true"
         class="input_type"
         name="form_142[pernapasan]"
         id="pernapasan"
         onchange="fillthis('pernapasan')"
         style="width:100%; min-height:40px; border:1px solid #ccc; padding:4px;">
      <?php echo isset($value_form['pernapasan']) ? nl2br($value_form['pernapasan']) : '' ?>
    </div>
  </td>
</tr>

<tr>
  <td style="padding:5px; background:#f7f7f7;"><b>Jantung :</b></td>
</tr>
<tr>
  <td style="padding:5px;">
    <div contenteditable="true"
         class="input_type"
         name="form_142[jantung]"
         id="jantung"
         onchange="fillthis('jantung')"
         style="width:100%; min-height:40px; border:1px solid #ccc; padding:4px;">
      <?php echo isset($value_form['jantung']) ? nl2br($value_form['jantung']) : '' ?>
    </div>
  </td>
</tr>

<tr>
  <td style="padding:5px; background:#f7f7f7;"><b>Lain-lain :</b></td>
</tr>
<tr>
  <td style="padding:5px;">
    <div contenteditable="true"
         class="input_type"
         name="form_142[lain_lain]"
         id="lain_lain"
         onchange="fillthis('lain_lain')"
         style="width:100%; min-height:40px; border:1px solid #ccc; padding:4px;">
      <?php echo isset($value_form['lain_lain']) ? nl2br($value_form['lain_lain']) : '' ?>
    </div>
  </td>
</tr>

  </table>
</td>

</tr>

<!-- END -->

  <tr>
  </tr>

  <tr>
  <td style="width:25%; padding:5px;"><b>Status Fisik ASA :</b></td>
  <td colspan="3" style="padding:5px;">
    <label><input type="checkbox" class="ace" name="form_142[asa_1]" id="asa_1" onclick="checkthis('asa_1')"> <span class="lbl"> 1</span></label>&nbsp;&nbsp;
    <label><input type="checkbox" class="ace" name="form_142[asa_2]" id="asa_2" onclick="checkthis('asa_2')"> <span class="lbl"> 2</span></label>&nbsp;&nbsp;
    <label><input type="checkbox" class="ace" name="form_142[asa_3]" id="asa_3" onclick="checkthis('asa_3')"> <span class="lbl"> 3</span></label>&nbsp;&nbsp;
    <label><input type="checkbox" class="ace" name="form_142[asa_4]" id="asa_4" onclick="checkthis('asa_4')"> <span class="lbl"> 4</span></label>&nbsp;&nbsp;
    <label><input type="checkbox" class="ace" name="form_142[asa_5]" id="asa_5" onclick="checkthis('asa_5')"> <span class="lbl"> 5</span></label>&nbsp;&nbsp;
    <label><input type="checkbox" class="ace" name="form_142[asa_e]" id="asa_e" onclick="checkthis('asa_e')"> <span class="lbl"> E</span></label>
  </td>
</tr>

  <tr>
    <td style="padding:5px;"><b>Penyakit Pra Anestesi :</b></td>
    <td colspan="3" style="padding:5px;">
      <div contenteditable="true" class="input_type" name="form_142[penyakit_pra]" id="penyakit_pra" onchange="fillthis('penyakit_pra')">
        <?php echo isset($value_form['penyakit_pra']) ? nl2br($value_form['penyakit_pra']) : '' ?>
      </div>
    </td>
  </tr>
  <tr>
    <td style="padding:5px;"><b>Rencana Anestesi :</b></td>
    <td colspan="3" style="padding:5px;">
      <div contenteditable="true" class="input_type" name="form_142[rencana_anestesi]" id="rencana_anestesi" onchange="fillthis('rencana_anestesi')">
        <?php echo isset($value_form['rencana_anestesi']) ? nl2br($value_form['rencana_anestesi']) : '' ?>
      </div>
    </td>
  </tr>
</table>

<!-- END---->


<br>
<table width="100%" border="1" style="border-collapse:collapse; font-size:13px;">
  <tr style="background:#e8e8e8; text-align:center;">
    <th colspan="2" style="padding:5px;text-align:center;">PENGKAJIAN PRA INDUKSI</th>
  </tr>
  <tr>
    <td style="width:25%; padding:5px;"><b>Tanggal :</b>
      <input type="text" class="input_type" name="form_142[tanggal]" id="tanggal" onchange="fillthis('tanggal')" style="width:80%;">
    </td>
    <td style="width:25%; padding:5px;"><b>Jam :</b>
      <input type="text" class="input_type" name="form_142[jam]" id="jam" onchange="fillthis('jam')" style="width:80%;">
    </td>
  </tr>

  <!-- Checklist sebelum induksi -->
  <tr>
    <td colspan="2" style="padding:5px;">
      <b><i>Check list</i> sebelum induksi</b><br>
      <label style="margin-right:30px;">
  <input type="checkbox" class="ace" name="form_142[ijin_operasi]" id="ijin_operasi" onclick="checkthis('ijin_operasi')">
  <span class="lbl">Ijin operasi</span>
</label>
<label style="margin-right:30px;">
  <input type="checkbox" class="ace" name="form_142[cek_mesin]" id="cek_mesin" onclick="checkthis('cek_mesin')">
  <span class="lbl">Cek Mesin Anestesi</span>
</label>
<label style="margin-right:30px;">
  <input type="checkbox" class="ace" name="form_142[cek_suction]" id="cek_suction" onclick="checkthis('cek_suction')">
  <span class="lbl">Cek <i>Suction Unit</i></span>
</label>
<label style="margin-right:30px;">
  <input type="checkbox" class="ace" name="form_142[persiapan_obat]" id="persiapan_obat" onclick="checkthis('persiapan_obat')">
  <span class="lbl">Persiapan Obat-obatan & Peralatan</span>
</label>
    </td>
  </tr>

  

<!-- Teknik Anestesi -->
<tr>
  <td colspan="2" style="padding:5px;">
    <b>Teknik Anestesi</b><br>

    <table style="width:100%; border-collapse:collapse; margin-top:5px;">
      <tr>
        <td style="width:100px; vertical-align:top;">1. GA</td>
        <td> :
          <label style="margin-right:20px;">
            <input type="checkbox" class="ace" name="form_142[ga_lm]" id="ga_lm" onclick="checkthis('ga_lm')">
            <span class="lbl">LM</span>
          </label>
          <label style="margin-right:20px;">
            <input type="checkbox" class="ace" name="form_142[ga_fm]" id="ga_fm" onclick="checkthis('ga_fm')">
            <span class="lbl">FM</span>
          </label>
          <label style="margin-right:20px;">
            <input type="checkbox" class="ace" name="form_142[ga_iv]" id="ga_iv" onclick="checkthis('ga_iv')">
            <span class="lbl">IV</span>
          </label>
          <label style="margin-right:20px;">
            <input type="checkbox" class="ace" name="form_142[ga_ett]" id="ga_ett" onclick="checkthis('ga_ett')">
            <span class="lbl">ETT</span>
          </label>
        </td>
      </tr>

      <tr>
        <td style="vertical-align:top;">2. Regional</td>
        <td> :
          <label style="margin-right:20px;">
            <input type="checkbox" class="ace" name="form_142[regional_spinal]" id="regional_spinal" onclick="checkthis('regional_spinal')">
            <span class="lbl">Spinal</span>
          </label>
          <label style="margin-right:20px;">
            <input type="checkbox" class="ace" name="form_142[regional_epidural]" id="regional_epidural" onclick="checkthis('regional_epidural')">
            <span class="lbl">Epidural</span>
          </label>
          <label style="margin-right:20px;">
            <input type="checkbox" class="ace" name="form_142[regional_brachial]" id="regional_brachial" onclick="checkthis('regional_brachial')">
            <span class="lbl">Brachial</span>
          </label>
        </td>
      </tr>

      <tr>
        <td style="vertical-align:top;">3. Kombinasi</td>
        <td> :
          <input type="text" class="input_type" name="form_142[kombinasi]" id="kombinasi" onchange="fillthis('kombinasi')" style="width:60%;">
        </td>
      </tr>
    </table>
  </td>
</tr>


<!-- Teknik Khusus -->
<tr>
  <td colspan="2" style="padding:5px;">
    <b>Teknik Khusus</b><br>
    <label style="margin-right:25px;">
      <input type="checkbox" class="ace" name="form_142[hypotensi]" id="hypotensi" onclick="checkthis('hypotensi')">
      <span class="lbl">Hypotensi</span>
    </label>
    <label style="margin-right:25px;">
      <input type="checkbox" class="ace" name="form_142[ventilasi]" id="ventilasi" onclick="checkthis('ventilasi')">
      <span class="lbl">Ventilasi satu paru</span>
    </label>
    <label style="margin-right:10px;">
      <input type="checkbox" class="ace" name="form_142[teknik_lain]" id="teknik_lain" onclick="checkthis('teknik_lain')">
      <span class="lbl">Lain-lain :</span>
    </label>
    <input type="text" class="input_type" name="form_142[teknik_lain_text]" id="teknik_lain_text" onchange="fillthis('teknik_lain_text')" style="width:40%;">
  </td>
</tr>


<!-- Monitoring -->
<tr>
  <td colspan="2" style="padding:5px;">
    <b>Monitoring :</b>
    <table style="width:100%; border-collapse:collapse; margin-top:5px;">
      <!-- Pernapasan -->
      <tr>
        <td style="width:220px;">
          <label>
            <input type="checkbox" class="ace" name="form_142[mon_pernapasan]" id="mon_pernapasan" onclick="checkthis('mon_pernapasan')">
            <span class="lbl">Pernapasan</span>
          </label>
        </td>
        <td style="width:10px;">:</td>
        <td>
          RR <input type="text" class="input_type" name="form_142[rr]" id="rr" onchange="fillthis('rr')" style="width:50px;"> x/menit
        </td>
      </tr>

      <!-- Tekanan Darah -->
      <tr>
        <td>
          <label>
            <input type="checkbox" class="ace" name="form_142[td_chk]" id="td_chk" onclick="checkthis('td_chk')">
            <span class="lbl">Tekanan Darah</span>
          </label>
        </td>
        <td>:</td>
        <td>
          TD <input type="text" class="input_type" name="form_142[td]" id="td" onchange="fillthis('td')" style="width:60px;"> mmHg
        </td>
      </tr>

      <!-- Heart Rate -->
      <tr>
        <td>
          <label>
            <input type="checkbox" class="ace" name="form_142[hr_chk]" id="hr_chk" onclick="checkthis('hr_chk')">
            <span class="lbl">Heart Rate (HR)</span>
          </label>
        </td>
        <td>:</td>
        <td>
          HR <input type="text" class="input_type" name="form_142[hr]" id="hr" onchange="fillthis('hr')" style="width:60px;"> x/menit
        </td>
      </tr>

      <!-- Saturasi O₂ -->
      <tr>
        <td>
          <label>
            <input type="checkbox" class="ace" name="form_142[sat_o2]" id="sat_o2" onclick="checkthis('sat_o2')">
            <span class="lbl">Sat O₂</span>
          </label>
        </td>
        <td>:</td>
        <td>
          <input type="text" class="input_type" name="form_142[sat_o2_val]" id="sat_o2_val" onchange="fillthis('sat_o2_val')" style="width:60px;"> %
        </td>
      </tr>

      <!-- Suhu -->
      <tr>
        <td>
          <label>
            <input type="checkbox" class="ace" name="form_142[suhu_val1]" id="suhu_val1" onclick="checkthis('suhu_val1')">
            <span class="lbl">Suhu</span>
          </label>
        </td>
        <td>:</td>
        <td>
          <input type="text" class="input_type" name="form_142[suhu_val2]" id="suhu_val2" onchange="fillthis('suhu_val2')" style="width:60px;"> °C
        </td>
      </tr>

      <!-- NGT -->
      <tr>
        <td>
          <label>
            <input type="checkbox" class="ace" name="form_142[ngt]" id="ngt" onclick="checkthis('ngt')">
            <span class="lbl">NGT</span>
          </label>
        </td>
        <td>:</td>
        <td>
          <input type="text" class="input_type" name="form_142[ngt_val]" id="ngt_val" onchange="fillthis('ngt_val')" style="width:100px;">
        </td>
      </tr>

      <!-- Urine Kateter -->
      <tr>
        <td>
          <label>
            <input type="checkbox" class="ace" name="form_142[urine_kateter]" id="urine_kateter" onclick="checkthis('urine_kateter')">
            <span class="lbl">Urine Kateter</span>
          </label>
        </td>
        <td>:</td>
        <td>
          <input type="text" class="input_type" name="form_142[urine_val]" id="urine_val" onchange="fillthis('urine_val')" style="width:100px;">
        </td>
      </tr>

      <!-- IVFD -->
      <tr>
        <td>
          <label>
            <input type="checkbox" class="ace" name="form_142[ivfd]" id="ivfd" onclick="checkthis('ivfd')">
            <span class="lbl">IVFD</span>
          </label>
        </td>
        <td>:</td>
        <td>
          <input type="text" class="input_type" name="form_142[ivfd_val]" id="ivfd_val" onchange="fillthis('ivfd_val')" style="width:100px;">
        </td>
      </tr>

      <!-- Capnografi -->
      <tr>
        <td>
          <label>
            <input type="checkbox" class="ace" name="form_142[capnografi]" id="capnografi" onclick="checkthis('capnografi')">
            <span class="lbl">Capnografi</span>
          </label>
        </td>
        <td>:</td>
        <td>
          <input type="text" class="input_type" name="form_142[capnografi_val]" id="capnografi_val" onchange="fillthis('capnografi_val')" style="width:100px;">
        </td>
      </tr>

      <!-- Tekanan Vena Sentral -->
      <tr>
        <td>
          <label>
            <input type="checkbox" class="ace" name="form_142[vena_sentral]" id="vena_sentral" onclick="checkthis('vena_sentral')">
            <span class="lbl">Tekanan Vena Sentral</span>
          </label>
        </td>
        <td>:</td>
        <td>
          <input type="text" class="input_type" name="form_142[vena_sentral_val]" id="vena_sentral_val" onchange="fillthis('vena_sentral_val')" style="width:100px;">
        </td>
      </tr>
    </table>
  </td>
</tr>



  <!-- Rencana Rawat Pasca Anestesi -->
  <tr>
    <td colspan="2" style="padding:5px;">
      <b>RENCANA RAWAT PASCA ANESTESI</b><br>
      <label><input type="checkbox" class="ace" name="form_142[rawat_ruangan]" id="rawat_ruangan" onclick="checkthis('rawat_ruangan')"> <span class="lbl">Ruang Perawatan</span></label>&nbsp;&nbsp;<br>
      <label><input type="checkbox" class="ace" name="form_142[rawat_icu]" id="rawat_icu" onclick="checkthis('rawat_icu')"> <span class="lbl">ICU</span></label>&nbsp;&nbsp;<br>
      <label><input type="checkbox" class="ace" name="form_142[rawat_pulang]" id="rawat_pulang" onclick="checkthis('rawat_pulang')"> <span class="lbl">Pulang</span></label>
    </td>
  </tr>
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
        <input type="text" class="input_type" name="form_142[nama_dokter_anestesi]" id="nama_dokter_anestesi" placeholder="Nama Dokter" style="width:90%; text-align:center;">
        <input type="hidden" name="form_142[ttd_dokter_anestesi]">
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
        <input type="text" class="input_type" name="form_142[nama_perawat_anestesi]" id="nama_perawat_anestesi" placeholder="Nama Perawat" style="width:90%; text-align:center;">
        <input type="hidden" name="form_142[ttd_perawat_anestesi]">
      </td>
    </tr>
  </tbody>
</table>


<!-- END -->

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