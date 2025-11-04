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
      var hiddenInputName = 'form_130[ttd_' + role + ']';
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


<!-- FORM PENGKAJIAN RISIKO JATUH GERIATRI -->
<?php echo $header; ?>
<hr>
<br>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<div style="text-align:center; font-size:15px; font-weight:bold; margin-bottom:10px;">
  FORM PENGKAJIAN RISIKO JATUH GERIATRI<br>
</div>

<table style="width:100%; border-collapse:collapse; font-size:13px; border:1px solid black;">
  <thead style="background-color:#e8e8e8; text-align:center; font-weight:bold;">
    <tr>
      <td colspan=9" style="border:1px solid black;">ONTARIO MODIFIED STRATIFY - SIDNEY SCORING</td>
    </tr>
    <tr>
      <td style="border:1px solid black; width:15%;">FAKTOR RESIKO</td>
      <td style="border:1px solid black; width:35%;">SKRINING</td>
      <td style="border:1px solid black; width:10%;">JAWABAN</td>
      <td style="border:1px solid black; width:10%;">KET. NILAI</td>
      <td colspan="5" style="border:1px solid black; width:30%;">SKOR</td>
    </tr>
  </thead>

  <tbody>
    <!-- Riwayat Jatuh -->
    <tr>
      <td rowspan="2" style="border:1px solid black; padding:5px;">Riwayat Jatuh</td>
      <td style="border:1px solid black; padding:5px;">Apakah pasien datang ke rumah sakit karena jatuh?</td>
      <td style="border:1px solid black; padding:5px;">
        <label><input type="checkbox" class="ace" name="form_130[jatuh_rs_ya]" id="jatuh_rs_ya" onclick="checkthis('jatuh_rs_ya')"> <span class="lbl">Ya</span></label><br>
        <label><input type="checkbox" class="ace" name="form_130[jatuh_rs_tidak]" id="jatuh_rs_tidak" onclick="checkthis('jatuh_rs_tidak')"> <span class="lbl">Tidak</span></label>
      </td>
      <td rowspan="2" style="border:1px solid black; text-align:center;padding:5px;">Salah satu jawaban Ya = 6</td>
      <td rowspan="2" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_mobilitas]" id="skor1_mobilitas" onchange="fillthis('skor1_mobilitas')" style="width:100%; text-align:center;"></td>
      <td rowspan="2" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_mobilitas]" id="skor2_mobilitas" onchange="fillthis('skor2_mobilitas')" style="width:100%; text-align:center;"></td>
      <td rowspan="2" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_mobilitas]" id="skor3_mobilitas" onchange="fillthis('skor3_mobilitas')" style="width:100%; text-align:center;"></td>
      <td rowspan="2" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_mobilitas]" id="skor4_mobilitas" onchange="fillthis('skor4_mobilitas')" style="width:100%; text-align:center;"></td>
      <td rowspan="2" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_mobilitas]" id="skor5_mobilitas" onchange="fillthis('skor5_mobilitas')" style="width:100%; text-align:center;"></td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">Jika tidak, apakah pasien mengalami jatuh dalam 2 bulan terakhir ini?</td>
      <td style="border:1px solid black; padding:5px;">
        <label><input type="checkbox" class="ace" name="form_130[jatuh_2bln_ya]" id="jatuh_2bln_ya" onclick="checkthis('jatuh_2bln_ya')"> <span class="lbl">Ya</span></label><br>
        <label><input type="checkbox" class="ace" name="form_130[jatuh_2bln_tidak]" id="jatuh_2bln_tidak" onclick="checkthis('jatuh_2bln_tidak')"> <span class="lbl">Tidak</span></label>
      </td>
    </tr>

    <!-- Status Mental -->
    <tr>
      <td rowspan="3" style="border:1px solid black; padding:5px;">Status Mental</td>
      <td style="border:1px solid black; padding:5px;">Apakah pasien delirium? (pola pikir tidak terorganisir, gangguan daya ingat)</td>
      <td style="border:1px solid black; padding:5px;">
        <label><input type="checkbox" class="ace" name="form_130[delirium_ya]" id="delirium_ya" onclick="checkthis('delirium_ya')"> <span class="lbl">Ya</span></label><br>
        <label><input type="checkbox" class="ace" name="form_130[delirium_tidak]" id="delirium_tidak" onclick="checkthis('delirium_tidak')"> <span class="lbl">Tidak</span></label>
      </td>
      <td rowspan="3" style="border:1px solid black; text-align:center;padding:5px;">Salah satu jawaban Ya = 14</td>
      <td rowspan="3" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_mental]" id="skor1_mental" onchange="fillthis('skor1_mental')" style="width:100%; text-align:center;"></td>
      <td rowspan="3" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_mental]" id="skor2_mental" onchange="fillthis('skor2_mental')" style="width:100%; text-align:center;"></td>
      <td rowspan="3" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_mental]" id="skor3_mental" onchange="fillthis('skor3_mental')" style="width:100%; text-align:center;"></td>
      <td rowspan="3" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_mental]" id="skor4_mental" onchange="fillthis('skor4_mental')" style="width:100%; text-align:center;"></td>
      <td rowspan="3" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_mental]" id="skor5_mental" onchange="fillthis('skor5_mental')" style="width:100%; text-align:center;"></td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">Apakah pasien disorientasi? (salah menyebutkan waktu/tempat/orang lain)</td>
      <td style="border:1px solid black; padding:5px;">
        <label><input type="checkbox" class="ace" name="form_130[disorientasi_ya]" id="disorientasi_ya" onclick="checkthis('disorientasi_ya')"> <span class="lbl">Ya</span></label><br>
        <label><input type="checkbox" class="ace" name="form_130[disorientasi_tidak]" id="disorientasi_tidak" onclick="checkthis('disorientasi_tidak')"> <span class="lbl">Tidak</span></label>
      </td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">Apakah pasien mengalami agitasi? (ketakutan, gelisah, cemas)</td>
      <td style="border:1px solid black; padding:5px;">
        <label><input type="checkbox" class="ace" name="form_130[agitasi_ya]" id="agitasi_ya" onclick="checkthis('agitasi_ya')"> <span class="lbl">Ya</span></label><br>
        <label><input type="checkbox" class="ace" name="form_130[agitasi_tidak]" id="agitasi_tidak" onclick="checkthis('agitasi_tidak')"> <span class="lbl">Tidak</span></label>
      </td>
    </tr>

    <!-- Penglihatan -->
    <tr>
      <td rowspan="3" style="border:1px solid black; padding:5px;">Penglihatan</td>
      <td style="border:1px solid black; padding:5px;">Apakah pasien memakai kacamata?</td>
      <td style="border:1px solid black; padding:5px;">
        <label><input type="checkbox" class="ace" name="form_130[kacamata_ya]" id="kacamata_ya" onclick="checkthis('kacamata_ya')"> <span class="lbl">Ya</span></label><br>
        <label><input type="checkbox" class="ace" name="form_130[kacamata_tidak]" id="kacamata_tidak" onclick="checkthis('kacamata_tidak')"> <span class="lbl">Tidak</span></label>
      </td>
      <td rowspan="3" style="border:1px solid black; text-align:center;padding:5px;">Salah satu jawaban Ya = 1</td>
      <td rowspan="3" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_penglihatan]" id="skor1_penglihatan" onchange="fillthis('skor1_penglihatan')" style="width:100%; text-align:center;"></td>
      <td rowspan="3" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_penglihatan]" id="skor2_penglihatan" onchange="fillthis('skor2_penglihatan')" style="width:100%; text-align:center;"></td>
      <td rowspan="3" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_penglihatan]" id="skor3_penglihatan" onchange="fillthis('skor3_penglihatan')" style="width:100%; text-align:center;"></td>
      <td rowspan="3" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_penglihatan]" id="skor4_penglihatan" onchange="fillthis('skor4_penglihatan')" style="width:100%; text-align:center;"></td>
      <td rowspan="3" style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_penglihatan]" id="skor5_penglihatan" onchange="fillthis('skor5_penglihatan')" style="width:100%; text-align:center;"></td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">Apakah pasien mengeluh penglihatan buram?</td>
      <td style="border:1px solid black; padding:5px;">
        <label><input type="checkbox" class="ace" name="form_130[buram_ya]" id="buram_ya" onclick="checkthis('buram_ya')"> <span class="lbl">Ya</span></label><br>
        <label><input type="checkbox" class="ace" name="form_130[buram_tidak]" id="buram_tidak" onclick="checkthis('buram_tidak')"> <span class="lbl">Tidak</span></label>
      </td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">Apakah pasien memiliki glaukoma / katarak / degenerasi makula?</td>
      <td style="border:1px solid black; padding:5px;">
        <label><input type="checkbox" class="ace" name="form_130[glaukoma_ya]" id="glaukoma_ya" onclick="checkthis('glaukoma_ya')"> <span class="lbl">Ya</span></label><br>
        <label><input type="checkbox" class="ace" name="form_130[glaukoma_tidak]" id="glaukoma_tidak" onclick="checkthis('glaukoma_tidak')"> <span class="lbl">Tidak</span></label>
      </td>
    </tr>

    <!-- Kebiasaan Berkemih -->
    <tr>
      <td style="border:1px solid black; padding:5px;">Kebiasaan Berkemih</td>
      <td style="border:1px solid black; padding:5px;">Apakah terdapat perubahan perilaku berkemih? (frekuensi, urgensi, inkontinensia, nokturia)</td>
      <td style="border:1px solid black; padding:5px;">
        <label><input type="checkbox" class="ace" name="form_130[berkemih_ya]" id="berkemih_ya" onclick="checkthis('berkemih_ya')"> <span class="lbl">Ya</span></label><br>
        <label><input type="checkbox" class="ace" name="form_130[berkemih_tidak]" id="berkemih_tidak" onclick="checkthis('berkemih_tidak')"> <span class="lbl">Tidak</span></label>
      </td>
      <td style="border:1px solid black; text-align:center;padding:5px;">Ya = 2</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_berkemih]" style="width:100%; text-align:center;" id="skor1_berkemih" onchange="fillthis('skor1_berkemih')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_berkemih]" style="width:100%; text-align:center;" id="skor2_berkemih" onchange="fillthis('skor2_berkemih')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_berkemih]" style="width:100%; text-align:center;" id="skor3_berkemih" onchange="fillthis('skor3_berkemih')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_berkemih]" style="width:100%; text-align:center;" id="skor4_berkemih" onchange="fillthis('skor4_berkemih')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_berkemih]" style="width:100%; text-align:center;" id="skor5_berkemih" onchange="fillthis('skor5_berkemih')"></td>
    </tr>

    <!-- Transfer dan Mobilitas -->
    <tr>
      <td rowspan="4" style="border:1px solid black; padding:5px;">Transfer (tempat tidur ke kursi dan kembali)</td>
      <td style="border:1px solid black; padding:5px;">Mandiri (boleh menggunakan alat bantu jalan)</td>
      <td style="border:1px solid black; text-align:center;">0</td>
      <td rowspan="8" style="border:1px solid black; text-align:center;padding:5px;">Jumlah nilai transfer dan mobilitas. Jika nilai 0 - 3, maka skor 0.
        <br>Jika nilai total 4 - 6, maka skor 7
    </td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_mandiri]" style="width:100%; text-align:center;" id="skor1_mandiri" onchange="fillthis('skor1_mandiri')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_mandiri]" style="width:100%; text-align:center;" id="skor2_mandiri" onchange="fillthis('skor2_mandiri')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_mandiri]" style="width:100%; text-align:center;" id="skor3_mandiri" onchange="fillthis('skor3_mandiri')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_mandiri]" style="width:100%; text-align:center;" id="skor4_mandiri" onchange="fillthis('skor4_mandiri')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_mandiri]" style="width:100%; text-align:center;" id="skor5_mandiri" onchange="fillthis('skor5_mandiri')"></td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">Memerlukan sedikit bantuan (1 orang / dalam pengawasan)</td>
      <td style="border:1px solid black; text-align:center;">1</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_sedikitbantuan]" style="width:100%; text-align:center;" id="skor1_sedikitbantuan" onchange="fillthis('skor1_sedikitbantuan')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_sedikitbantuan]" style="width:100%; text-align:center;" id="skor2_sedikitbantuan" onchange="fillthis('skor2_sedikitbantuan')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_sedikitbantuan]" style="width:100%; text-align:center;" id="skor3_sedikitbantuan" onchange="fillthis('skor3_sedikitbantuan')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_sedikitbantuan]" style="width:100%; text-align:center;" id="skor4_sedikitbantuan" onchange="fillthis('skor4_sedikitbantuan')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_sedikitbantuan]" style="width:100%; text-align:center;" id="skor5_sedikitbantuan" onchange="fillthis('skor5_sedikitbantuan')"></td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">Memerlukan bantuan nyata (2 orang)</td>
      <td style="border:1px solid black; text-align:center;">2</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_bantuannyata]" style="width:100%; text-align:center;" id="skor1_bantuannyata" onchange="fillthis('skor1_bantuannyata')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_bantuannyata]" style="width:100%; text-align:center;" id="skor2_bantuannyata" onchange="fillthis('skor2_bantuannyata')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_bantuannyata]" style="width:100%; text-align:center;" id="skor3_bantuannyata" onchange="fillthis('skor3_bantuannyata')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_bantuannyata]" style="width:100%; text-align:center;" id="skor4_bantuannyata" onchange="fillthis('skor4_bantuannyata')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_bantuannyata]" style="width:100%; text-align:center;" id="skor5_bantuannyata" onchange="fillthis('skor5_bantuannyata')"></td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">Tidak dapat duduk seimbang, perlu bantuan total</td>
      <td style="border:1px solid black; text-align:center;">3</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_bantuantotal]" style="width:100%; text-align:center;" id="skor1_bantuantotal" onchange="fillthis('skor1_bantuantotal')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_bantuantotal]" style="width:100%; text-align:center;" id="skor2_bantuantotal" onchange="fillthis('skor2_bantuantotal')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_bantuantotal]" style="width:100%; text-align:center;" id="skor3_bantuantotal" onchange="fillthis('skor3_bantuantotal')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_bantuantotal]" style="width:100%; text-align:center;" id="skor4_bantuantotal" onchange="fillthis('skor4_bantuantotal')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_bantuantotal]" style="width:100%; text-align:center;" id="skor5_bantuantotal" onchange="fillthis('skor5_bantuantotal')"></td>
    </tr>

    <tr>
      <td rowspan="4" style="border:1px solid black; padding:5px;">Mobilitas</td>
      <td style="border:1px solid black; padding:5px;">Mandiri (boleh menggunakan alat bantu jalan)</td>
      <td style="border:1px solid black; text-align:center;">0</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_mbantujalan]" style="width:100%; text-align:center;" id="skor1_mbantujalan" onchange="fillthis('skor1_mbantujalan')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_mbantujalan]" style="width:100%; text-align:center;" id="skor2_mbantujalan" onchange="fillthis('skor2_mbantujalan')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_mbantujalan]" style="width:100%; text-align:center;" id="skor3_mbantujalan" onchange="fillthis('skor3_mbantujalan')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_mbantujalan]" style="width:100%; text-align:center;" id="skor4_mbantujalan" onchange="fillthis('skor4_mbantujalan')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_mbantujalan]" style="width:100%; text-align:center;" id="skor5_mbantujalan" onchange="fillthis('skor5_mbantujalan')"></td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">Berjalan dengan bantuan 1 orang (verbal/fisik)</td>
      <td style="border:1px solid black; text-align:center;">1</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_berjalanbantuan]" style="width:100%; text-align:center;" id="skor1_berjalanbantuan" onchange="fillthis('skor1_berjalanbantuan')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_berjalanbantuan]" style="width:100%; text-align:center;" id="skor2_berjalanbantuan" onchange="fillthis('skor2_berjalanbantuan')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_berjalanbantuan]" style="width:100%; text-align:center;" id="skor3_berjalanbantuan" onchange="fillthis('skor3_berjalanbantuan')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_berjalanbantuan]" style="width:100%; text-align:center;" id="skor4_berjalanbantuan" onchange="fillthis('skor4_berjalanbantuan')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_berjalanbantuan]" style="width:100%; text-align:center;" id="skor5_berjalanbantuan" onchange="fillthis('skor5_berjalanbantuan')"></td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">Menggunakan kursi roda</td>
      <td style="border:1px solid black; text-align:center;">2</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_mkursiroda]" style="width:100%; text-align:center;" id="skor1_mkursiroda" onchange="fillthis('skor1_mkursiroda')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_mkursiroda]" style="width:100%; text-align:center;" id="skor2_mkursiroda" onchange="fillthis('skor2_mkursiroda')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_mkursiroda]" style="width:100%; text-align:center;" id="skor3_mkursiroda" onchange="fillthis('skor3_mkursiroda')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_mkursiroda]" style="width:100%; text-align:center;" id="skor4_mkursiroda" onchange="fillthis('skor4_mkursiroda')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_mkursiroda]" style="width:100%; text-align:center;" id="skor5_mkursiroda" onchange="fillthis('skor5_mkursiroda')"></td>
    </tr>
    <tr>
      <td style="border:1px solid black; padding:5px;">Imobilisasi</td>
      <td style="border:1px solid black; text-align:center;">3</td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_imobilisasi]" style="width:100%; text-align:center;" id="skor1_imobilisasi" onchange="fillthis('skor1_imobilisasi')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_imobilisasi]" style="width:100%; text-align:center;" id="skor2_imobilisasi" onchange="fillthis('skor2_imobilisasi')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_imobilisasi]" style="width:100%; text-align:center;" id="skor3_imobilisasi" onchange="fillthis('skor3_imobilisasi')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_imobilisasi]" style="width:100%; text-align:center;" id="skor4_imobilisasi" onchange="fillthis('skor4_imobilisasi')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_imobilisasi]" style="width:100%; text-align:center;" id="skor5_imobilisasi" onchange="fillthis('skor5_imobilisasi')"></td>
    </tr>

    <tr>
      <td colspan="4" style="border:1px solid black; text-align:right; padding:5px;">
        <b>TOTAL SKOR :</b> 
      </td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor1_total_skor]" style="width:100%; text-align:center;" id="skor1_total_skor" onchange="fillthis('skor1_total_skor')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor2_total_skor]" style="width:100%; text-align:center;" id="skor2_total_skor" onchange="fillthis('skor2_total_skor')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor3_total_skor]" style="width:100%; text-align:center;" id="skor3_total_skor" onchange="fillthis('skor3_total_skor')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor4_total_skor]" style="width:100%; text-align:center;" id="skor4_total_skor" onchange="fillthis('skor4_total_skor')"></td>
      <td style="border:1px solid black; text-align:center;"><input type="text" class="input_type"  name="form_130[skor5_total_skor]" style="width:100%; text-align:center;" id="skor5_total_skor" onchange="fillthis('skor5_total_skor')"></td>
    </tr>

    <!-- Baris Paraf Pengkajian -->
<!-- BARIS PARAF / TANDA TANGAN -->
<tr>
  <td colspan="4" style="border:1px solid black; text-align:right; padding:5px;">
    <b>PARAF PENGKAJI :</b>
  </td>

  <!-- Paraf 1 -->
  <td style="border:1px solid black; text-align:center;">
    <span class="ttd-btn" data-role="paraf1" id="ttd_paraf1" style="cursor:pointer;">
      <i class="fa fa-pencil blue"></i>
    </span><br>
    <img id="img_ttd_paraf1" src="<?php echo isset($value_form['img_ttd_paraf1']) ? $value_form['img_ttd_paraf1'] : ''; ?>"
         style="display:<?php echo isset($value_form['img_ttd_paraf1']) ? 'block' : 'none'; ?>; max-width:100px; max-height:40px; margin-top:5px;">
    <input type="hidden" name="form_130[img_ttd_paraf1]" id="input_ttd_paraf1">
    <br>
    <input type="text" class="input_type" name="form_130[nama_paraf1]" id="nama_paraf1"
           placeholder="Nama" style="width:90%; text-align:center;" onchange="fillthis('nama_paraf1')">
  </td>

  <!-- Paraf 2 -->
  <td style="border:1px solid black; text-align:center;">
    <span class="ttd-btn" data-role="paraf2" id="ttd_paraf2" style="cursor:pointer;">
      <i class="fa fa-pencil blue"></i>
    </span><br>
    <img id="img_ttd_paraf2" src="<?php echo isset($value_form['img_ttd_paraf2']) ? $value_form['img_ttd_paraf2'] : ''; ?>"
         style="display:<?php echo isset($value_form['img_ttd_paraf2']) ? 'block' : 'none'; ?>; max-width:100px; max-height:40px; margin-top:5px;">
    <input type="hidden" name="form_130[img_ttd_paraf2]" id="input_ttd_paraf2">
    <br>
    <input type="text" class="input_type" name="form_130[nama_paraf2]" id="nama_paraf2"
           placeholder="Nama" style="width:90%; text-align:center;" onchange="fillthis('nama_paraf2')">
  </td>

  <!-- Paraf 3 -->
  <td style="border:1px solid black; text-align:center;">
    <span class="ttd-btn" data-role="paraf3" id="ttd_paraf3" style="cursor:pointer;">
      <i class="fa fa-pencil blue"></i>
    </span><br>
    <img id="img_ttd_paraf3" src="<?php echo isset($value_form['img_ttd_paraf3']) ? $value_form['img_ttd_paraf3'] : ''; ?>"
         style="display:<?php echo isset($value_form['img_ttd_paraf3']) ? 'block' : 'none'; ?>; max-width:100px; max-height:40px; margin-top:5px;">
    <input type="hidden" name="form_130[img_ttd_paraf3]" id="input_ttd_paraf3">
    <br>
    <input type="text" class="input_type" name="form_130[nama_paraf3]" id="nama_paraf3"
           placeholder="Nama" style="width:90%; text-align:center;" onchange="fillthis('nama_paraf3')">
  </td>

  <!-- Paraf 4 -->
  <td style="border:1px solid black; text-align:center;">
    <span class="ttd-btn" data-role="paraf4" id="ttd_paraf4" style="cursor:pointer;">
      <i class="fa fa-pencil blue"></i>
    </span><br>
    <img id="img_ttd_paraf4" src="<?php echo isset($value_form['img_ttd_paraf4']) ? $value_form['img_ttd_paraf4'] : ''; ?>"
         style="display:<?php echo isset($value_form['img_ttd_paraf4']) ? 'block' : 'none'; ?>; max-width:100px; max-height:40px; margin-top:5px;">
    <input type="hidden" name="form_130[img_ttd_paraf4]" id="input_ttd_paraf4">
    <br>
    <input type="text" class="input_type" name="form_130[nama_paraf4]" id="nama_paraf4"
           placeholder="Nama" style="width:90%; text-align:center;" onchange="fillthis('nama_paraf4')">
  </td>

  <!-- Paraf 5 -->
  <td style="border:1px solid black; text-align:center;">
    <span class="ttd-btn" data-role="paraf5" id="ttd_paraf5" style="cursor:pointer;">
      <i class="fa fa-pencil blue"></i>
    </span><br>
    <img id="img_ttd_paraf5" src="<?php echo isset($value_form['img_ttd_paraf5']) ? $value_form['img_ttd_paraf5'] : ''; ?>"
         style="display:<?php echo isset($value_form['img_ttd_paraf5']) ? 'block' : 'none'; ?>; max-width:100px; max-height:40px; margin-top:5px;">
    <input type="hidden" name="form_130[img_ttd_paraf5]" id="input_ttd_paraf5">
    <br>
    <input type="text" class="input_type" name="form_130[nama_paraf5]" id="nama_paraf5"
           placeholder="Nama" style="width:90%; text-align:center;" onchange="fillthis('nama_paraf5')">
  </td>
</tr>
  </tbody>
</table>

<!-- <div style="margin-top:10px; font-size:13px;">
  <b>KATEGORI :</b><br>
  □ RISIKO RENDAH (0 - 5) &nbsp;&nbsp;&nbsp; □ RISIKO SEDANG (6 - 16) &nbsp;&nbsp;&nbsp; □ RISIKO TINGGI (17 - 30)
</div> -->

<br>
<table style="width:100%; border-collapse:collapse; font-size:12px; border:none;">
  <tr>
    <td style="border:none; padding:5px;">KATEGORI :</td>
    <td style="border:none; padding:5px;">
      <label><input type="checkbox" class="ace" name="form_130[resiko_rendah]" id="resiko_rendah" onclick="checkthis('resiko_rendah')"> 
        <span class="lbl">RISIKO RENDAH (0 - 5)</span></label>
      <label><input type="checkbox" class="ace" name="form_130[resiko_sedang]" id="resiko_sedang" onclick="checkthis('resiko_sedang')"> 
        <span class="lbl">RISIKO SEDANG (6 - 16)</span></label>
      <label><input type="checkbox" class="ace" name="form_130[resiko_tinggi]" id="resiko_tinggi" onclick="checkthis('resiko_tinggi')"> 
        <span class="lbl">RISIKO TINGGI (17 - 30)</span></label>
    </td>
  </tr>
</table>

<br>
<table style="width:100%; border-collapse:collapse; font-size:12px; border:1px solid black;">
  <tr>
    <th colspan="2" style="border:1px solid black; text-align:center; background:#f2f2f2; padding:5px;">
      PENCEGAHAN PASIEN JATUH
    </th>
  </tr>
  <tr>
    <th style="border:1px solid black; text-align:center; width:50%; padding:5px;">RESIKO RENDAH</th>
    <th style="border:1px solid black; text-align:center; width:50%; padding:5px;">RESIKO TINGGI</th>
  </tr>
  <tr>
    <td style="border:1px solid black; vertical-align:top; padding:5px;">
      <ol style="margin:0; padding-left:20px;">
        <li>Pastikan bel mudah dijangkau</li>
        <li>Roda tempat tidur pada posisi terkunci</li>
        <li>Pagar pengaman tempat tidur dinaikkan</li>
        <li>Lampu toilet cukup terang</li>
        <li>Lakukan asesmen ulang setiap ada perubahan kondisi pasien</li>
      </ol>
    </td>
    <td style="border:1px solid black; vertical-align:top; padding:5px;">
      <ol style="margin:0; padding-left:20px;">
        <li>Lakukan <b>semua</b> pedoman pencegahan untuk risiko rendah</li>
        <li>Pasangkan tanda risiko jatuh pada pergelangan tangan (<b>stiker kuning</b>)</li>
        <li>Tempatkan tanda risiko jatuh pada daftar nama pasien di nurse station</li>
        <li>Beri tanda risiko jatuh pada tempat tidur pasien (<b>segitiga kuning</b>)</li>
        <li>Posisi tempat tidur pada posisi terendah</li>
        <li>Kunjungi dan monitor pasien per 2 jam</li>
        <li>Tempatkan pasien di kamar yang paling dekat nurse station (jika mungkin)</li>
        <li>Beritahu pasien bila ingin BAK/kencing supaya minta bantuan</li>
        <li>Lakukan asesmen risiko jatuh sebelum di-transfer</li>
      </ol>
    </td>
  </tr>
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

