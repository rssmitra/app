<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
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
      var hiddenInputName = 'form_65[ttd_' + role + ']';
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

<div style="text-align: center; font-size: 18px;"><b>RIWAYAT PENYAKIT PASIEN KASUS BAYI BARU LAHIR 2</b></div>
<!-- <p>edited by amelia yahya 25 september 2025</p> -->
<br>
<style>
.form-container {
  display: flex;
  justify-content: space-between;
}
.form-column {
  width: 50%;
}
.form-section {
  margin-bottom: 2px; /* bisa dikecilkan */
}
.form-row {
  display: flex;
  margin-bottom: 0; /* rapat */
  align-items: center;
}
.form-row label {
  flex: 0 0 28%;
  padding-right: 0; /* lebih mepet */
  white-space: nowrap;
}
.form-row input, .form-row textarea {
  flex: 1;
  padding: 3px; /* lebih kecil supaya tinggi input lebih rendah */
  width: 250px;
  box-sizing: border-box;
}
</style>
<br>
<div style="text-align: center; font-size: 14px;"><b>DIISI OLEH DOKTER YANG MERAWAT</b></div>
<br><br>
<div class="form-container">
  <!-- Kolom Kiri -->
  <div class="form-column">
    <p><b>PEMERIKSAAN FISIK</b></p>
    <div class="form-section">
      <div class="form-row">
        <label>Tanggal Lahir</label>
        <label>:
          <input type="text" class="input_type date-picker" name="form_65[tanggal_lahir]" id="tanggal_lahir" onchange="fillthis('tanggal_lahir')">
        </label>
      </div>
      <div class="form-row">
        <label>Jam</label>
        <label>:
          <input type="text" class="input_type" name="form_65[jam_lahir]" id="jam_lahir" onchange="fillthis('jam_lahir')">
        </label>
      </div>

      <div class="form-row">
        <label>Berat Badan Lahir</label>
        : <input type="text" class="input_type" name="form_65[berat_badan_lahir]" id="berat_badan_lahir" onchange="fillthis('berat_badan_lahir')" style="width: 30px;">
        gr, <label style="margin-left: 0px;"></label>
        Panjang Badan <input type="text" class="input_type" name="form_65[panjang_badan]" id="panjang_badan" onchange="fillthis('panjang_badan')" style="width: 35px;"> cm
        <label style="margin-left: 0px;"></label>
      </div>
      <div class="form-row">
        <label>Nilai apgar</label>
        <label>:
          <input type="text" class="input_type" name="form_65[nilai_apgar]" id="nilai_apgar" onchange="fillthis('nilai_apgar')">
        </label>
      </div>
      <div class="form-row">
  <label>Menangis</label>
  <label>:
    <label>
      <input type="checkbox" class="ace" name="form_65[menangis][]" id="menangis_kuat" onclick="checkthis('menangis_kuat')" value="Kuat">
      <span class="lbl"> Kuat</span>
    </label>
    <label>
      <input type="checkbox" class="ace" name="form_65[menangis][]" id="menangis_lemah" onclick="checkthis('menangis_lemah')" value="Lemah">
      <span class="lbl"> Lemah</span>
    </label>
    <label>
      <input type="checkbox" class="ace" name="form_65[menangis][]" id="menangis_merintih" onclick="checkthis('menangis_merintih')" value="Merintih">
      <span class="lbl"> Merintih</span>
    </label>
</div>
      <div class="form-row">
        <label>Turgor</label>
        <label>:
          <input type="text" class="input_type" name="form_65[turgor]" id="turgor" onchange="fillthis('turgor')">
        </label>
      </div>
      <div class="form-row">
        <label>Tonus</label>
        <label>:
          <input type="text" class="input_type" name="form_65[tonus]" id="tonus" onchange="fillthis('tonus')">
        </label>
      </div>
      <div class="form-row">
        <label>Dispnoe</label>
        <label>:
          <input type="text" class="input_type" name="form_65[dispnoe]" id="dispnoe" onchange="fillthis('dispnoe')">
        </label>
      </div>
      <div class="form-row">
        <label>Sianosis</label>
        <label>:
          <input type="text" class="input_type" name="form_65[sianosis]" id="sianosis" onchange="fillthis('sianosis')">
        </label>
      </div>
      <div class="form-row">
        <label>Kesadaran</label>
        <label>:
          <input type="text" class="input_type" name="form_65[kesadaran]" id="kesadaran" onchange="fillthis('kesadaran')">
        </label>
      </div>
    </div>
  </div>

  <!-- =========================
     KOLOM KANAN – PEMERIKSAAN FISIK BAYI
========================= -->
<div class="form-column">

  <!-- ABDOMEN -->
  <div class="form-section">
    <table width="100%" border="0" cellspacing="0" cellpadding="4" style="font-size:12px; line-height:1.4; border-collapse:collapse;">
      <tr>
        <td colspan="3" width="65%" valign="top"><b>ABDOMEN</b></td>
      </tr>

      <!-- Hepar -->
      <tr>
        <td width="25%">Hepar</td>
        <td width="5%" align="right">:</td>
        <td width="70%">
          <label><input type="checkbox" class="ace" name="form_65[hepar][]" id="hepar_normal" onclick="checkthis('hepar_normal')" value="Normal"> <span class="lbl">Normal</span></label>
          <label><input type="checkbox" class="ace" name="form_65[hepar][]" id="herpar_tidak" onclick="checkthis('herpar_tidak')" value="Tidak"> <span class="lbl">Tidak</span></label>
          <label>
            <input type="checkbox" class="ace" name="form_65[hepar][]" id="hepar_lain" onclick="checkthis('hepar_lain')" value="Lain-lain">
            <span class="lbl"> Lain-lain:</span>
            <input type="text" class="input_type" name="form_65[hepar_lainnya]" onchange="fillthis('hepar_lainnya')" id="hepar_lainnya" placeholder="..." style="width:70px;">
          </label>
        </td>
      </tr>

      <!-- Lien -->
      <tr>
        <td>Lien</td>
        <td align="right">:</td>
        <td>
          <label><input type="checkbox" class="ace" name="form_65[lien][]" id="lien_normal" onclick="checkthis('lien_normal')" value="Normal"> <span class="lbl">Normal</span></label>
          <label><input type="checkbox" class="ace" name="form_65[lien][]" id="lien_tidak" onclick="checkthis('lien_tidak')" value="Tidak"> <span class="lbl">Tidak</span></label>
          <label>
            <input type="checkbox" class="ace" name="form_65[lien][]" id="lien_lain" onclick="checkthis('lien_lain')" value="Lain-lain">
            <span class="lbl"> Lain-lain:</span>
            <input type="text" class="input_type" name="form_65[lien_lainnya]" onchange="fillthis('lien_lainnya')" id="lien_lainnya" placeholder="..." style="width:70px;">
          </label>
        </td>
      </tr>

      <!-- Kelainan Umbilicus -->
      <tr>
        <td>Umbilicus</td>
        <td align="right">:</td>
        <td>
          <label><input type="checkbox" class="ace" name="form_65[umbilicus][]" id="umbilicus_normal" onclick="checkthis('umbilicus_normal')" value="Normal"> <span class="lbl">Normal</span></label>
          <label><input type="checkbox" class="ace" name="form_65[umbilicus][]" id="umbilicus_tidak" onclick="checkthis('umbilicus_tidak')" value="Tidak"> <span class="lbl">Tidak</span></label>
          <label>
            <input type="checkbox" class="ace" name="form_65[umbilicus][]" id="umbilicus_lain" onclick="checkthis('umbilicus_lain')" value="Lain-lain">
            <span class="lbl"> Lain-lain:</span>
            <input type="text" class="input_type" name="form_65[umbilicus_lainnya]" onchange="fillthis('umbilicus_lainnya')" id="umbilicus_lainnya" placeholder="..." style="width:70px;">
          </label>
        </td>
      </tr>
    </table>
  </div>

  <br>

  <!-- GENETALIA -->
  <div class="form-section">
    <table width="100%" border="0" cellspacing="0" cellpadding="4" style="font-size:12px; line-height:1.4; border-collapse:collapse;">
      <tr><td colspan="3"><b>GENETALIA</b></td></tr>

      <tr>
  <td width="35%">Desensus Testikulorum</td>
  <td width="5%" align="right">:</td>
  <td style="font-size:12px;">
    <label><input type="checkbox" class="ace" name="form_65[desensus_testikulorum][]" id="desensus_testikulorum_normal" onclick="checkthis('desensus_testikulorum_normal')" value="Normal"> <span class="lbl">Normal</span></label>
    <label><input type="checkbox" class="ace" name="form_65[desensus_testikulorum][]" id="desensus_testikulorum_tidak" onclick="checkthis('desensus_testikulorum_tidak')" value="Tidak"> <span class="lbl">Tidak</span></label>
    <label>
      <input type="checkbox" class="ace" name="form_65[desensus_testikulorum][]" id="desensus_testikulorum_lain" onclick="checkthis('desensus_testikulorum_lain')" value="Lain-lain">
      <span class="lbl"> Lain-lain:</span>
      <input type="text" class="input_type" name="form_65[desensus_testikulorum_lainnya]" onchange="fillthis('desensus_testikulorum_lainnya')" id="desensus_testikulorum_lainnya" placeholder="..." style="width:70px;">
    </label>
  </td>
</tr>


      <tr>
        <td>Labia Minor</td>
        <td align="right">:</td>
        <td><input type="text" class="input_type" name="form_65[labia_minor]" id="labia_minor" style="width:95%;" onchange="fillthis('labia_minor')"></td>
      </tr>

      <tr>
        <td>Kelainan</td>
        <td align="right">:</td>
        <td><input type="text" class="input_type" name="form_65[kelainan_genetalia]" id="kelainan_genetalia" style="width:95%;" onchange="fillthis('kelainan_genetalia')"></td>
      </tr>

      <tr>
        <td>ANUS</td>
        <td align="right">:</td>
        <td>
          <label><input type="checkbox" class="ace" name="form_65[anus][]" id="anus_normal" onclick="checkthis('anus_normal')" value="Normal"> <span class="lbl">Normal</span></label>
          <label><input type="checkbox" class="ace" name="form_65[anus][]" id="anus_kelainan" onclick="checkthis('anus_kelainan')" value="Kelainan"> <span class="lbl">Kelainan</span></label>
          <label>
            <input type="checkbox" class="ace" name="form_65[anus][]" id="anus_lain" onclick="checkthis('anus_lain')" value="Lain-lain">
            <span class="lbl"> Lain-lain:</span>
            <input type="text" class="input_type" name="form_65[anus_lainnya]" onchange="fillthis('anus_lainnya')" id="anus_lainnya" placeholder="..." style="width:70px;">
          </label>
        </td>
      </tr>
    </table>
  </div>

  <br>

<!-- MECONIUM & MIKSI -->
<div class="form-section">
  <table width="100%" border="0" cellspacing="0" cellpadding="4" 
         style="font-size:12px; line-height:1.4; border-collapse:collapse; width:100%;">
    
    <!-- MECONIUM -->
    <tr>
      <td valign="top" style="width:100%;">
        <b>MECONIUM</b><br>
        <label>
          <input type="checkbox" class="ace" name="form_65[meconium][]" 
                 id="meconium_24" onclick="checkthis('meconium_24')" value="Sudah < 24 jam">
          <span class="lbl"> Sudah &lt; 24 jam</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_65[meconium][]" 
                 id="meconium_lebih_24" onclick="checkthis('meconium_lebih_24')" value="Belum > 24 jam">
          <span class="lbl"> Belum &gt; 24 jam</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_65[meconium][]" 
                 id="meconium_kelainan" onclick="checkthis('meconium_kelainan')" value="Kelainan">
          <span class="lbl"> Kelainan</span>
        </label>
      </td>
    </tr>

    <!-- MIKSI -->
    <tr>
      <td valign="top" style="width:100%;">
        <b>MIKSI</b><br>
        <label>
          <input type="checkbox" class="ace" name="form_65[miksi][]" 
                 id="miksi_sudah" onclick="checkthis('miksi_sudah')" value="Sudah">
          <span class="lbl"> Sudah</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_65[miksi][]" 
                 id="miksi_belum" onclick="checkthis('miksi_belum')" value="Belum">
          <span class="lbl"> Belum</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_65[miksi][]" 
                 id="miksi_kelainan" onclick="checkthis('miksi_kelainan')" value="Kelainan">
          <span class="lbl"> Kelainan</span>
        </label>
      </td>
    </tr>
  </table>
</div>



</div>
</div>
<br>

<!-- Bagian TANDA-TANDA VITAL -->
<div class="form-container">
  <!-- Kolom Kiri -->
  <div class="form-column">
    <p><b>TANDA-TANDA VITAL</b></p>
    <div class="form-section">
      <div class="form-row">
        <label>Suhu</label>
        <label>:
          <input type="text" class="input_type" name="form_65[suhu]" id="suhu" onchange="fillthis('suhu')"> °C
        </label>
      </div>
      <div class="form-row">
        <label>Frek.Denyut Jantung</label>
        <label>:
          <input type="text" class="input_type" name="form_65[denyut_jantung]" id="denyut_jantung" onchange="fillthis('denyut_jantung')"> x/menit
        </label>
      </div>
      <div class="form-row">
        <label>Frek. Pernafasan</label>
        <label>:
          <input type="text" class="input_type" name="form_65[pernafasan]" id="pernafasan" onchange="fillthis('pernafasan')"> x/menit
        </label>
      </div>
    </div>
<br>
<p><b>KULIT</b></p>
<div class="form-section">

  <!-- Ikterik -->
  <div class="form-row">
    <label>Ikterik</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[ikterik][]" id="ikterik_iya" onclick="checkthis('ikterik_iya')" value="Iya">
        <span class="lbl"> Iya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[ikterik][]" id="ikterik_tidak" onclick="checkthis('ikterik_tidak')" value="Tidak">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[ikterik][]" id="ikterik_lain" onclick="checkthis('ikterik_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[ikterik_lainnya]" onchange="fillthis('ikterik_lainnya')" id="ikterik_lainnya" placeholder="..." style="width: 90px;">
      </label>
    </label>
  </div>

  <!-- Jaringan Sub Cutis -->
  <div class="form-row">
    <label>Jaringan sub cutis</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[sub_cutis][]" id="sub_cutis_iya" onclick="checkthis('sub_cutis_iya')" value="Iya">
        <span class="lbl"> Iya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[sub_cutis][]" id="sub_cutis_tidak" onclick="checkthis('sub_cutis_tidak')" value="Tidak">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[sub_cutis][]" id="sub_cutis_lain" onclick="checkthis('sub_cutis_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[sub_cutis_lainnya]" onchange="fillthis('sub_cutis_lainnya')" id="sub_cutis_lainnya" placeholder="..." style="width: 90px;">
      </label>
    </label>
  </div>

</div>

<br>
<p><b>KEPALA</b></p>
<div class="form-section">

  <!-- Sefal Hematoma -->
  <div class="form-row">
    <label>Sefal Hematoma</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[sefal_hematoma][]" id="sefal_ada" onclick="checkthis('sefal_ada')" value="Ada">
        <span class="lbl"> Ada</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[sefal_hematoma][]" id="sefal_tidak" onclick="checkthis('sefal_tidak')" value="Tidak Ada">
        <span class="lbl"> Tidak Ada</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[sefal_hematoma][]" id="sefal_lain" onclick="checkthis('sefal_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[sefal_hematoma_lain]" onchange="fillthis('sefal_hematoma_lain')" id="sefal_hematoma_lain" placeholder="..." style="width: 90px;">
      </label>
    </label>
  </div>

  <!-- Kaput Suksedaneum -->
  <div class="form-row">
    <label>Kaput Suksedaneum</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[kaput_suksidaneum][]" id="kaput_ada" onclick="checkthis('kaput_ada')" value="Ada">
        <span class="lbl"> Ada</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kaput_suksidaneum][]" id="kaput_tidak" onclick="checkthis('kaput_tidak')" value="Tidak Ada">
        <span class="lbl"> Tidak Ada</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kaput_suksidaneum][]" id="kaput_lain" onclick="checkthis('kaput_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[kaput_lainnya]" id="kaput_lainnya" onchange="fillthis('kaput_lainnya')" placeholder="..." style="width: 90px;">
      </label>
    </label>
  </div>

  <!-- Kelainan-kelainan -->
  <div class="form-row">
    <label>Kelainan-kelainan</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_kelainan][]" id="kelainan_ada" onclick="checkthis('kelainan_ada')" value="Ada">
        <span class="lbl"> Ada</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_kelainan][]" id="kelainan_tidak" onclick="checkthis('kelainan_tidak')" value="Tidak Ada">
        <span class="lbl"> Tidak Ada</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_kelainan][]" id="kelainan_lain" onclick="checkthis('kelainan_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[kelainan_kelainan_lain]" onchange="fillthis('kelainan_kelainan_lain')" id="kelainan_kelainan_lain" placeholder="..." style="width: 90px;">
      </label>
    </label>
  </div>

  <!-- Rambut -->
  <div class="form-row">
    <label>* Rambut</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_rambut][]" id="rambut_normal" onclick="checkthis('rambut_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_rambut][]" id="rambut_tidak" onclick="checkthis('rambut_tidak')" value="Tidak Normal">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_rambut][]" id="rambut_lain" onclick="checkthis('rambut_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[kelainan_rambut_lainnya]" onchange="fillthis('kelainan_rambut_lainnya')" id="kelainan_rambut_lainnya" placeholder="..." style="width: 90px;">
      </label>
    </label>
  </div>

  <!-- Mata -->
  <div class="form-row">
    <label>* Mata</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_mata][]" id="mata_normal" onclick="checkthis('mata_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_mata][]" id="mata_tidak" onclick="checkthis('mata_tidak')" value="Tidak Normal">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_mata][]" id="mata_lain" onclick="checkthis('mata_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[kelainan_mata_lainnya]" onchange="fillthis('kelainan_mata_lainnya')" id="kelainan_mata_lainnya" placeholder="..." style="width: 90px;">
      </label>
    </label>
  </div>

  <!-- Telinga -->
  <div class="form-row">
    <label>* Telinga</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_telinga][]" id="telinga_normal" onclick="checkthis('telinga_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_telinga][]" id="telinga_tidak" onclick="checkthis('telinga_tidak')" value="Tidak Normal">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_telinga][]" id="telinga_lain" onclick="checkthis('telinga_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[kelainan_telinga_lainnya]" onchange="fillthis('kelainan_telinga_lainnya')" id="kelainan_telinga_lainnya" placeholder="..." style="width: 90px;">
      </label>
    </label>
  </div>

  <!-- Hidung -->
  <div class="form-row">
    <label>* Hidung</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_hidung][]" id="hidung_normal" onclick="checkthis('hidung_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_hidung][]" id="hidung_tidak" onclick="checkthis('hidung_tidak')" value="Tidak Normal">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_hidung][]" id="hidung_lain" onclick="checkthis('hidung_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[kelainan_hidung_lainnya]" onchange="fillthis('kelainan_hidung_lainnya')" id="kelainan_hidung_lainnya" placeholder="..." style="width: 90px;">
      </label>
    </label>
  </div>

  <!-- Mulut -->
  <div class="form-row">
    <label>* Mulut</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_mulut][]" id="mulut_normal" onclick="checkthis('mulut_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_mulut][]" id="mulut_tidak" onclick="checkthis('mulut_tidak')" value="Tidak Normal">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_mulut][]" id="mulut_lain" onclick="checkthis('mulut_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[kelainan_mulut_lainnya]" onchange="fillthis('kelainan_mulut_lainnya')" id="kelainan_mulut_lainnya" placeholder="..." style="width: 90px;">
      </label>
    </label>
  </div>

  <!-- Lidah -->
  <div class="form-row">
    <label>* Lidah</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_lidah][]" id="lidah_normal" onclick="checkthis('lidah_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_lidah][]" id="lidah_tidak" onclick="checkthis('lidah_tidak')" value="Tidak Normal">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_lidah][]" id="lidah_lain" onclick="checkthis('lidah_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[kelainan_lidah_lainnya]" onchange="fillthis('kelainan_lidah_lainnya')" id="kelainan_lidah_lainnya" placeholder="..." style="width: 90px;">
      </label>
    </label>
  </div>

</div>
<br>


<br>
<p><b>TENGGOROK</b></p>
<div class="form-section">

  <!-- Tonsil -->
  <div class="form-row">
    <label>Tonsil</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[tonsil][]" id="tonsil_normal" onclick="checkthis('tonsil_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[tonsil][]" id="tonsil_tidak" onclick="checkthis('tonsil_tidak')" value="Tidak Normal">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[tonsil][]" id="tonsil_lain" onclick="checkthis('tonsil_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[tonsil_lainnya]" onchange="fillthis('tonsil_lainnya')" id="tonsil_lainnya" placeholder="..." style="width:90px;">
      </label>
    </label>
  </div>

  <!-- Faring -->
  <div class="form-row">
    <label>Faring</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[faring][]" id="faring_normal" onclick="checkthis('faring_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[faring][]" id="faring_tidak" onclick="checkthis('faring_tidak')" value="Tidak Normal">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[faring][]" id="faring_lain" onclick="checkthis('faring_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[faring_lainnya]" onchange="fillthis('faring_lainnya')" id="faring_lainnya" placeholder="..." style="width:90px;">
      </label>
    </label>
  </div>

  <!-- Leher -->
  <div class="form-row">
    <label>Leher</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[leher][]" id="leher_normal" onclick="checkthis('leher_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[leher][]" id="leher_tidak" onclick="checkthis('leher_tidak')" value="Tidak Normal">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[leher][]" id="leher_lain" onclick="checkthis('leher_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[leher_lainnya]" onchange="fillthis('leher_lainnya')" id="leher_lainnya" placeholder="..." style="width:90px;">
      </label>
    </label>
  </div>

  <!-- Kelainan -->
  <div class="form-row">
    <label>Kelainan</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_tenggorok][]" id="kelainan_tenggorok_normal" onclick="checkthis('kelainan_tenggorok_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_tenggorok][]" id="kelainan_tenggorok_tidak" onclick="checkthis('kelainan_tenggorok_tidak')" value="Tidak Normal">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[kelainan_tenggorok][]" id="kelainan_tenggorok_lain" onclick="checkthis('kelainan_tenggorok_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[kelainan_tenggorok_lainnya]" onchange="fillthis('kelainan_tenggorok_lainnya')" id="kelainan_tenggorok_lainnya" placeholder="..." style="width:90px;">
      </label>
    </label>
  </div>

</div>


<br>
    <p><b>LEHER</b></p>
    <div class="form-section">
      <div class="form-row">
        <label>Kelainan</label>
        <label>:
          <input type="text" class="input_type" name="form_65[kelainan_leher]" id="kelainan_leher" onchange="fillthis('kelainan_leher')">
        </label>
      </div>
    </div>
<br>
<p><b>TORAKS</b></p>
<div class="form-section">

  <!-- Bentuk -->
  <div class="form-row">
    <label>Bentuk</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[bentuk][]" id="bentuk_normal" onclick="checkthis('bentuk_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[bentuk][]" id="bentuk_tidak" onclick="checkthis('bentuk_tidak')" value="Tidak Normal">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[bentuk][]" id="bentuk_lain" onclick="checkthis('bentuk_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[bentuk_lainnya]" onchange="fillthis('bentuk_lainnya')" id="bentuk_lainnya" placeholder="..." style="width:80px;">
      </label>
    </label>
  </div>

  <!-- Paru -->
  <div class="form-row">
    <label>Paru</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[paru][]" id="paru_normal" onclick="checkthis('paru_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[paru][]" id="paru_tidak" onclick="checkthis('paru_tidak')" value="Tidak Normal">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[paru][]" id="paru_lain" onclick="checkthis('paru_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[paru_lainnya]" onchange="fillthis('paru_lainnya')" id="paru_lainnya" placeholder="..." style="width:80px;">
      </label>
    </label>
  </div>

  <!-- Pergerakan -->
  <div class="form-row">
    <label>Pergerakan Dada</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[pergerakan][]" id="pergerakan_simetris" onclick="checkthis('pergerakan_simetris')" value="Simetris">
        <span class="lbl"> Simetris</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[pergerakan][]" id="pergerakan_asimetris" onclick="checkthis('pergerakan_asimetris')" value="Asimetris">
        <span class="lbl"> Asimetris</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[pergerakan][]" id="pergerakan_lain" onclick="checkthis('pergerakan_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[pergerakan_lainnya]" onchange="fillthis('pergerakan_lainnya')" id="pergerakan_lainnya" placeholder="..." style="width:80px;">
      </label>
    </label>
  </div>

  <!-- Retraksi -->
  <div class="form-row">
    <label>Retraksi</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[retraksi][]" id="retraksi_ada" onclick="checkthis('retraksi_ada')" value="Ada">
        <span class="lbl"> Ada</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[retraksi][]" id="retraksi_tidak" onclick="checkthis('retraksi_tidak')" value="Tidak Ada">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[retraksi][]" id="retraksi_lain" onclick="checkthis('retraksi_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[retraksi_lainnya]" onchange="fillthis('retraksi_lainnya')" id="retraksi_lainnya" placeholder="..." style="width:80px;">
      </label>
    </label>
  </div>

  <!-- Perkusi -->
  <div class="form-row">
    <label>Perkusi</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[perkusi][]" id="perkusi_sonor" onclick="checkthis('perkusi_sonor')" value="Sonor">
        <span class="lbl"> Sonor</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[perkusi][]" id="perkusi_pekat" onclick="checkthis('perkusi_pekat')" value="Pekak">
        <span class="lbl"> Pekak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[perkusi][]" id="perkusi_lain" onclick="checkthis('perkusi_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[perkusi_lainnya]" onchange="fillthis('perkusi_lainnya')" id="perkusi_lainnya" placeholder="..." style="width:80px;">
      </label>
    </label>
  </div>

  <!-- Auskultasi -->
  <div class="form-row">
    <label>Auskultasi</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[auskultasi][]" id="auskultasi_vesikuler" onclick="checkthis('auskultasi_vesikuler')" value="Vesikuler">
        <span class="lbl"> Vesikuler</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[auskultasi][]" id="auskultasi_tidak" onclick="checkthis('auskultasi_tidak')" value="Tidak Vesikuler">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[auskultasi][]" id="auskultasi_lain" onclick="checkthis('auskultasi_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[auskultasi_lainnya]" onchange="fillthis('auskultasi_lainnya')" id="auskultasi_lainnya" placeholder="..." style="width:80px;">
      </label>
    </label>
  </div>

  <!-- Ronkhi -->
  <div class="form-row">
    <label>Ronkhi</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[ronkhi][]" id="ronkhi_ada" onclick="checkthis('ronkhi_ada')" value="Ada">
        <span class="lbl"> Ada</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[ronkhi][]" id="ronkhi_tidak" onclick="checkthis('ronkhi_tidak')" value="Tidak Ada">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[ronkhi][]" id="ronkhi_lain" onclick="checkthis('ronkhi_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[ronkhi_lainnya]" onchange="fillthis('ronkhi_lainnya')" id="ronkhi_lainnya" placeholder="..." style="width:80px;">
      </label>
    </label>
  </div>

</div>

<br>
<p><b>JANTUNG</b></p>
<div class="form-section">

  <!-- Perbesaran -->
  <div class="form-row">
    <label>Perbesaran</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[perbesaran_jantung][]" id="perbesaran_ada" onclick="checkthis('perbesaran_ada')" value="Ada">
        <span class="lbl"> Ada</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[perbesaran_jantung][]" id="perbesaran_tidak" onclick="checkthis('perbesaran_tidak')" value="Tidak Ada">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[perbesaran_jantung][]" id="perbesaran_lain" onclick="checkthis('perbesaran_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[perbesaran_jantung_lainnya]" onchange="fillthis('perbesaran_jantung_lainnya')" id="perbesaran_jantung_lainnya" placeholder="..." style="width:80px;">
      </label>
    </label>
  </div>

  <!-- Frekuensi -->
  <div class="form-row">
    <label>Frekuensi</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[frekuensi_jantung][]" id="frekuensi_normal" onclick="checkthis('frekuensi_normal')" value="Normal">
        <span class="lbl"> Normal</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[frekuensi_jantung][]" id="frekuensi_taki" onclick="checkthis('frekuensi_taki')" value="Takikardi">
        <span class="lbl"> Takikardi</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[frekuensi_jantung][]" id="frekuensi_bradi" onclick="checkthis('frekuensi_bradi')" value="Bradikardi">
        <span class="lbl"> Bradikardi</span>
      </label>
    </label>
  </div>

  <!-- Bising -->
  <div class="form-row">
    <label>Bising</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[bising_jantung][]" id="bising_ada" onclick="checkthis('bising_ada')" value="Ada">
        <span class="lbl"> Ada</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[bising_jantung][]" id="bising_tidak" onclick="checkthis('bising_tidak')" value="Tidak Ada">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[bising_jantung][]" id="bising_lain" onclick="checkthis('bising_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[bising_jantung_lainnya]" onchange="fillthis('bising_jantung_lainnya')" id="bising_jantung_lainnya" placeholder="..." style="width:80px;">
      </label>
    </label>
  </div>

  <!-- Mammae -->
  <div class="form-row">
    <label>Mammae</label>
    <label>:
      <label>
        <input type="checkbox" class="ace" name="form_65[mammae][]" id="mammae_simetris" onclick="checkthis('mammae_simetris')" value="Simetris">
        <span class="lbl"> Simetris</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[mammae][]" id="mammae_asimetris" onclick="checkthis('mammae_asimetris')" value="Asimetris">
        <span class="lbl"> Asimetris</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_65[mammae][]" id="mammae_lain" onclick="checkthis('mammae_lain')" value="Lain-lain">
        <span class="lbl"> Lain-lain:</span>
        <input type="text" class="input_type" name="form_65[mammae_lainnya]" onchange="fillthis('mammae_lainnya')" id="mammae_lainnya" placeholder="..." style="width:80px;">
      </label>
    </label>
  </div>

    </div>
  </div>

  <!-- Kolom Kanan -->
  <div class="form-column">
    <p><b>EKSTREMITAS</b></p>
    <div class="form-section">
      <div class="form-row">
        <label>Kelainan</label>
        <label>: 
          <input type="text" class="input_type" name="form_65[kelainan_ekstremitas]" onchange="fillthis('kelainan_ekstremitas')" id="kelainan_ekstremitas">
        </label>
      </div>
    </div>

<br>
    <p><b>PEMBESARAN KELENJAR</b></p>
    <div class="form-section">
      <div class="form-row">
        <label>Leher</label>
        <label>: 
          <input type="text" class="input_type" name="form_65[pembesaran_leher]" onchange="fillthis('pembesaran_leher')" id="pembesaran_leher">
        </label>
      </div>
      <div class="form-row">
        <label>Submandibulum</label>
        <label>: 
          <input type="text" class="input_type" name="form_65[submandibulum]" onchange="fillthis('submandibulum')" id="submandibulum">
        </label>
      </div>
      <div class="form-row">
        <label>Ketiak</label>
        <label>: 
          <input type="text" class="input_type" name="form_65[ketiak]" onchange="fillthis('ketiak')" id="ketiak">
        </label>
      </div>
      <div class="form-row">
        <label>Selangkangan</label>
        <label>: 
          <input type="text" class="input_type" name="form_65[selangkangan]" onchange="fillthis('selangkangan')" id="selangkangan">
        </label>
      </div>
    </div>

<br>
<p><b>REFLEKS-REFLEKS</b></p>
<div class="form-section">
  <table width="100%" border="0" cellspacing="0" cellpadding="4" 
         style="font-size:12px; line-height:1.4; border-collapse:collapse;">
    <tr>
      <td width="20%">Tendon</td>
      <td width="5%" align="right">:</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_65[tendon][]" id="tendon_normal" onclick="checkthis('tendon_normal')" value="Normal"> <span class="lbl">Normal</span></label>
        <label><input type="checkbox" class="ace" name="form_65[tendon][]" id="tendon_tidak" onclick="checkthis('tendon_tidak')" value="Tidak"> <span class="lbl">Tidak</span></label>
        <label><input type="checkbox" class="ace" name="form_65[tendon][]" id="tendon_lain" onclick="checkthis('tendon_lain')" value="Lain-lain"> <span class="lbl">Lain-lain:</span></label>
        <input type="text" class="input_type" name="form_65[tendon_lainnya]" onchange="fillthis('tendon_lainnya')" id="tendon_lainnya" placeholder="..." style="width:70px;">
      </td>
    </tr>

    <tr>
      <td>Moro</td>
      <td align="right">:</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_65[moro][]" id="moro_normal" onclick="checkthis('moro_normal')" value="Normal"> <span class="lbl">Normal</span></label>
        <label><input type="checkbox" class="ace" name="form_65[moro][]" id="moro_tidak" onclick="checkthis('moro_tidak')" value="Tidak"> <span class="lbl">Tidak</span></label>
        <label><input type="checkbox" class="ace" name="form_65[moro][]" id="moro_lain" onclick="checkthis('moro_lain')" value="Lain-lain"> <span class="lbl">Lain-lain:</span></label>
        <input type="text" class="input_type" name="form_65[moro_lainnya]" onchange="fillthis('moro_lainnya')" id="moro_lainnya" placeholder="..." style="width:70px;">
      </td>
    </tr>

    <tr>
      <td>Hisap</td>
      <td align="right">:</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_65[hisap][]" id="hisap_normal" onclick="checkthis('hisap_normal')" value="Normal"> <span class="lbl">Normal</span></label>
        <label><input type="checkbox" class="ace" name="form_65[hisap][]" id="hisap_tidak" onclick="checkthis('hisap_tidak')" value="Tidak"> <span class="lbl">Tidak</span></label>
        <label><input type="checkbox" class="ace" name="form_65[hisap][]" id="hisap_lain" onclick="checkthis('hisap_lain')" value="Lain-lain"> <span class="lbl">Lain-lain:</span></label>
        <input type="text" class="input_type" name="form_65[hisap_lainnya]" onchange="fillthis('hisap_lainnya')" id="hisap_lainnya" placeholder="..." style="width:70px;">
      </td>
    </tr>

    <tr>
      <td>Pegang</td>
      <td align="right">:</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_65[pegang][]" id="pegang_normal" onclick="checkthis('pegang_normal')" value="Normal"> <span class="lbl">Normal</span></label>
        <label><input type="checkbox" class="ace" name="form_65[pegang][]" id="pegang_tidak" onclick="checkthis('pegang_tidak')" value="Tidak"> <span class="lbl">Tidak</span></label>
        <label><input type="checkbox" class="ace" name="form_65[pegang][]" id="pegang_lain" onclick="checkthis('pegang_lain')" value="Lain-lain"> <span class="lbl">Lain-lain:</span></label>
        <input type="text" class="input_type" name="form_65[pegang_lainnya]" onchange="fillthis('pegang_lainnya')" id="pegang_lainnya" placeholder="..." style="width:70px;">
      </td>
    </tr>

    <tr>
      <td>Rooting</td>
      <td align="right">:</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_65[rooting][]" id="rooting_normal" onclick="checkthis('rooting_normal')" value="Normal"> <span class="lbl">Normal</span></label>
        <label><input type="checkbox" class="ace" name="form_65[rooting][]" id="rooting_tidak" onclick="checkthis('rooting_tidak')" value="Tidak"> <span class="lbl">Tidak</span></label>
        <label><input type="checkbox" class="ace" name="form_65[rooting][]" id="rooting_lain" onclick="checkthis('rooting_lain')" value="Lain-lain"> <span class="lbl">Lain-lain:</span></label>
        <input type="text" class="input_type" name="form_65[rooting_lainnya]" onchange="fillthis('rooting_lainnya')" id="rooting_lainnya" placeholder="..." style="width:70px;">
      </td>
    </tr>

    <tr>
      <td>Babinski</td>
      <td align="right">:</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_65[babinski][]" id="babinski_normal" onclick="checkthis('babinski_normal')" value="Normal"> <span class="lbl">Normal</span></label>
        <label><input type="checkbox" class="ace" name="form_65[babinski][]" id="babinski_tidak" onclick="checkthis('babinski_tidak')" value="Tidak"> <span class="lbl">Tidak</span></label>
        <label><input type="checkbox" class="ace" name="form_65[babinski][]" id="babinski_lain" onclick="checkthis('babinski_lain')" value="Lain-lain"> <span class="lbl">Lain-lain:</span></label>
        <input type="text" class="input_type" name="form_65[babinski_lainnya]" onchange="fillthis('babinski_lainnya')" id="babinski_lainnya" placeholder="..." style="width:70px;">
      </td>
    </tr>
  </table>
</div>

<br>
<p><b>UKURAN ANTROPOMETRI</b></p>
<div class="form-section">
  <table width="100%" border="0" cellspacing="0" cellpadding="4" 
         style="font-size:12px; line-height:1.4; border-collapse:collapse;">
    <tr>
      <td width="35%">Lingkar Kepala</td>
      <td width="5%" align="right">:</td>
      <td><input type="text" class="input_type" name="form_65[lingkar_kepala]" id="lingkar_kepala" style="width:80px;" onchange="fillthis('lingkar_kepala')"> cm</td>
    </tr>
    <tr>
      <td>Lingkar Dada</td>
      <td align="right">:</td>
      <td><input type="text" class="input_type" name="form_65[lingkar_dada]" id="lingkar_dada" style="width:80px;" onchange="fillthis('lingkar_dada')"> cm</td>
    </tr>
    <tr>
      <td>Lingkar Perut</td>
      <td align="right">:</td>
      <td><input type="text" class="input_type" name="form_65[lingkar_perut]" id="lingkar_perut" style="width:80px;" onchange="fillthis('lingkar_perut')"> cm</td>
    </tr>
    <tr>
      <td>Lingkar Lengan</td>
      <td align="right">:</td>
      <td><input type="text" class="input_type" name="form_65[panjang_lengan]" id="panjang_lengan" style="width:80px;" onchange="fillthis('panjang_lengan')"> cm</td>
    </tr>
    <tr>
      <td>Tanda Lahir (bila ada)</td>
      <td align="right">:</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_65[tanda_lahir][]" id="tanda_lahir_ada" onclick="checkthis('tanda_lahir_ada')" value="Ada"> <span class="lbl">Ada</span></label>
        <label><input type="checkbox" class="ace" name="form_65[tanda_lahir][]" id="tanda_lahir_tidak" onclick="checkthis('tanda_lahir_tidak')" value="Tidak"> <span class="lbl">Tidak</span></label>
        <input type="text" class="input_type" name="form_65[tanda_lahir_keterangan]" onchange="fillthis('tanda_lahir_keterangan')" id="tanda_lahir_keterangan" placeholder="Lokasi/jenis..." style="width:120px;">
      </td>
    </tr>
  </table>
</div>

<br>
<p><b>DIAGNOSIS</b></p>
<div class="form-section">
  <div class="form-row">
    <textarea class="input_type" rows="2" name="form_65[diagnosis]" id="diagnosis" onchange="fillthis('diagnosis')"></textarea>
  </div>
</div>

<br>
<p><b>TERAPI</b></p>
<div class="form-section">
  <div class="form-row">
    <textarea class="input_type" rows="2" name="form_65[terapi]" id="terapi" onchange="fillthis('terapi')"></textarea>
  </div>
</div>

<br><br>
<!-- ----- -->
<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <td style="width:50%; text-align:center;">
        Tanda Tangan
        <br><br>
        <span class="ttd-btn" data-role="petugas" id="ttd_petugas" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_petugas" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_65[nama_petugas]" id="nama_petugas" placeholder="Nama Dokter" style="width:33%; text-align:center;">
      </td>
    </tr>
  </tbody>
</table>

</div>
</div>

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

<?php //echo $footer; ?>