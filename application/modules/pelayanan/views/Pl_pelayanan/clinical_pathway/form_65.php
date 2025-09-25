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
td {
  padding: 6px 0px;  /* atas-bawah 6px, kiri-kanan 4px */
  vertical-align: top;
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
        <label>Gizi</label>
        <label>:
          <input type="text" class="input_type" name="form_65[gizi]" id="gizi" onchange="fillthis('gizi')">
        </label>
      </div>
      <div class="form-row">
        <label>Muntah</label>
        <label>:
          <input type="text" class="input_type" name="form_65[muntah]" id="muntah" onchange="fillthis('muntah')">
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

  <!-- Kolom Kanan -->
  <div class="form-column">



<div class="form-section">

<table width="100%" border="0" cellspacing="0" cellpadding="6" style="font-size: 13px; line-height: 1.6; border-collapse: collapse;">
  <tr>
    <td colspan="3" width="65%" valign="top"><b>ABDOMEN</b></td>
    <td colspan="2" width="35%" valign="top"><b>MECONIUM</b></td>
  </tr>
  <tr>
    <!-- ABDOMEN kiri -->
    <td width="20%">Hepar</td>
    <td width="5%" align="right">:</td>
    <td width="25%">
      <input type="text" class="input_type" name="form_65[hepar]" id="hepar" onchange="fillthis('hepar')">
    </td>
    <!-- MECONIUM kanan -->
    <td width="5%" align="right"></td>
    <td>
      <label><input type="checkbox" class="ace" name="form_65[meconium_24]" id="meconium_24" onclick="checkthis('meconium_24')" value="hepar 24 jam"> <span class="lbl">24 jam</span></label>
    </td>
  </tr>
  <tr>
    <td>Lien</td>
    <td align="right">:</td>
    <td>
      <input type="text" class="input_type" name="form_65[lien]" id="lien" onchange="fillthis('lien')">
    </td>
    <td align="right"></td>
    <td>
      <label><input type="checkbox" class="ace" name="form_65[meconium_lebih_24]" id="meconium_lebih_24" onclick="checkthis('meconium_lebih_24')" value="lien 24 jam"> <span class="lbl">24 jam</span></label>
    </td>
  </tr>
  <tr>
    <td>Kelainan Umbilicus</td>
    <td align="right">:</td>
    <td>
      <input type="text" class="input_type" name="form_65[umbilicus]" id="umbilicus" onchange="fillthis('umbilicus')">
    </td>
    <td align="right"></td>
    <td>
      <label><input type="checkbox" class="ace" name="form_65[meconium_kelainan]" id="meconium_kelainan" onclick="checkthis('meconium_kelainan')" value="Kelainan"> <span class="lbl">Kelainan</span></label>
    </td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="6" style="font-size: 13px; line-height: 1.6; border-collapse: collapse;">
  <tr>
    <td valign="top" width="65%">
      <b>GENETALIA</b>
      <table width="95%" border="0" cellspacing="4" cellpadding="4" style="font-size: 13px; line-height: 1.5; border-collapse: collapse;">
        <tr>
          <td width="35%">Desensus Testikulorum</td>
          <td width="5%" align="right">:</td>
          <td>
            <textarea class="input_type" name="form_65[desensus_testikulorum]" id="desensus_testikulorum" rows="2" style="width: 95%;" onchange="fillthis('desensus_testikulorum')"></textarea>
          </td>
        </tr>
        <tr>
          <td>Keadaan Labia Minor</td>
          <td align="right">:</td>
          <td>
            <textarea class="input_type" name="form_65[labia_minor]" id="labia_minor" rows="2" style="width: 95%;" onchange="fillthis('labia_minor')"></textarea>
          </td>
        </tr>
        <tr>
          <td>Kelainan</td>
          <td align="right">:</td>
          <td>
            <input type="text" class="input_type" name="form_65[kelainan_genetalia]" id="kelainan_genetalia" style="width: 95%;" onchange="fillthis('kelainan_genetalia')">
          </td>
        </tr>
        <tr>
          <td>ANUS (Normal/Kelainan)</td>
          <td align="right">:</td>
          <td>
            <input type="text" class="input_type" name="form_65[anus]" id="anus" style="width: 95%;" onchange="fillthis('anus')">
          </td>
        </tr>
      </table>
    </td>

    <td valign="top" width="35%">
      <b>MIKSI</b><br>
      <label><input type="checkbox" class="ace" name="form_65[miksi_sudah]" id="miksi_sudah" onclick="checkthis('miksi_sudah')" value="sudah"> <span class="lbl">sudah</span></label><br>
      <label><input type="checkbox" class="ace" name="form_65[miksi_belum]" id="miksi_belum" onclick="checkthis('miksi_belum')" value="belum"> <span class="lbl">belum</span></label>
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
      <div class="form-row">
        <label>Ikterik</label>
        <label>:
          <input type="text" class="input_type" name="form_65[ikterik]" id="ikterik" onchange="fillthis('ikterik')">
        </label>
      </div>
      <div class="form-row">
        <label>Jaringan sub cutis</label>
        <label>:
          <input type="text" class="input_type" name="form_65[sub_cutis]" id="sub_cutis" onchange="fillthis('sub_cutis')">
        </label>
      </div>
    </div>
<br>
    <p><b>KEPALA</b></p>
    <div class="form-section">
      <div class="form-row">
        <label>Sefal Hematoma</label>
        <label>:
          <input type="text" class="input_type" name="form_65[sefal_hematoma]" id="sefal_hematoma" onchange="fillthis('sefal_hematoma')">
        </label>
      </div>
      <div class="form-row">
        <label>Kaput Suksidaneum</label>
        <label>:
          <input type="text" class="input_type" name="form_65[kaput_suksidaneum]" id="kaput_suksidaneum" onchange="fillthis('kaput_suksidaneum')">
        </label>
      </div>
      <div class="form-row">
        <label>Kelainan-kelainan</label>
        <label>:
          <input type="text" class="input_type" name="form_65[kelainan_kelainan]" id="kelainan_kelainan" onchange="fillthis('kelainan_kelainan')">
        </label>
      </div>
      <div class="form-row">
        <label>* Rambut</label>
        <label>:
          <input type="text" class="input_type" name="form_65[kelainan_rambut]" id="kelainan_rambut" onchange="fillthis('kelainan_rambut')">
        </label>
      </div>
      <div class="form-row">
        <label>* Mata</label>
        <label>:
          <input type="text" class="input_type" name="form_65[kelainan_mata]" id="kelainan_mata" onchange="fillthis('kelainan_mata')">
        </label>
      </div>
      <div class="form-row">
        <label>* Telinga</label>
        <label>:
          <input type="text" class="input_type" name="form_65[kelainan_telinga]" id="kelainan_telinga" onchange="fillthis('kelainan_telinga')">
        </label>
      </div>
      <div class="form-row">
        <label>* Hidung</label>
        <label>:
          <input type="text" class="input_type" name="form_65[kelainan_hidung]" id="kelainan_hidung" onchange="fillthis('kelainan_hidung')">
        </label>
      </div>
      <div class="form-row">
        <label>* Mulut</label>
        <label>:
          <input type="text" class="input_type" name="form_65[kelainan_mulut]" id="kelainan_mulut" onchange="fillthis('kelainan_mulut')">
        </label>
      </div>
      <div class="form-row">
        <label>* Lidah</label>
        <label>:
          <input type="text" class="input_type" name="form_65[kelainan_lidah]" id="kelainan_lidah" onchange="fillthis('kelainan_lidah')">
        </label>
      </div>
    </div>
<br>
    <p><b>TENGGOROK</b></p>
    <div class="form-section">
      <div class="form-row">
        <label>Tonsil</label>
        <label>:
          <input type="text" class="input_type" name="form_65[tonsil]" id="tonsil" onchange="fillthis('tonsil')">
        </label>
      </div>
      <div class="form-row">
        <label>Faring</label>
        <label>:
          <input type="text" class="input_type" name="form_65[faring]" id="faring" onchange="fillthis('faring')">
        </label>
      </div>
      <div class="form-row">
        <label>Leher</label>
        <label>:
          <input type="text" class="input_type" name="form_65[leher]" id="leher" onchange="fillthis('leher')">
        </label>
      </div>
      <div class="form-row">
        <label>Kelainan</label>
        <label>:
          <input type="text" class="input_type" name="form_65[kelainan_tenggorok]" id="kelainan_tenggorok" onchange="fillthis('kelainan_tenggorok')">
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
      <div class="form-row">
        <label>Bentuk</label>
        <label>:
          <input type="text" class="input_type" name="form_65[bentuk]" id="bentuk" onchange="fillthis('bentuk')">
        </label>
      </div>
      <div class="form-row">
        <label>Paru</label>
        <label>:
          <input type="text" class="input_type" name="form_65[paru]" id="paru" onchange="fillthis('paru')">
        </label>
      </div>
      <div class="form-row">
        <label>* Pergerakan</label>
        <label>:
          <input type="text" class="input_type" name="form_65[pergerakan]" id="pergerakan" onchange="fillthis('pergerakan')">
        </label>
      </div>
      <div class="form-row">
        <label>* Retraksi</label>
        <label>:
          <input type="text" class="input_type" name="form_65[retraksi]" id="retraksi" onchange="fillthis('retraksi')">
        </label>
      </div>
      <div class="form-row">
        <label>* Perkusi</label>
        <label>:
          <input type="text" class="input_type" name="form_65[perkusi]" id="perkusi" onchange="fillthis('perkusi')">
        </label>
      </div>
      <div class="form-row">
        <label>* Auskultasi</label>
        <label>:
          <input type="text" class="input_type" name="form_65[auskultasi]" id="auskultasi" onchange="fillthis('auskultasi')">
        </label>
      </div>
      <div class="form-row">
        <label>* Ronkhi</label>
        <label>:
          <input type="text" class="input_type" name="form_65[ronkhi]" id="ronkhi" onchange="fillthis('ronkhi')">
        </label>
      </div>
    </div>
<br>
    <p><b>JANTUNG</b></p>
    <div class="form-section">
      <div class="form-row">
        <label>* Perbesaran</label>
        <label>:
          <input type="text" class="input_type" name="form_65[perbesaran_jantung]" id="perbesaran_jantung" onchange="fillthis('perbesaran_jantung')">
        </label>
      </div>
      <div class="form-row">
        <label>* Frekuensi</label>
        <label>:
          <input type="text" class="input_type" name="form_65[frekuensi_jantung]" id="frekuensi_jantung" onchange="fillthis('frekuensi_jantung')">
        </label>
      </div>
      <div class="form-row">
        <label>* Bising</label>
        <label>:
          <input type="text" class="input_type" name="form_65[bising_jantung]" id="bising_jantung" onchange="fillthis('bising_jantung')">
        </label>
      </div>
      <div class="form-row">
  <label>Mammae</label>
  <label>:
    <label>
      <input type="checkbox" class="ace" name="form_65[mammae][]" id="mammae_simetris" onclick="checkthis('mammae_simetris')" value="Simetris">
      <span class="lbl"> Simetris</span>
    </label>
    <label>
      <input type="checkbox" class="ace" name="form_65[mammae][]" id="mammae_asimetri" onclick="checkthis('mammae_asimetri')" value="Asimetri">
      <span class="lbl"> Asimetri</span>
    </label>
    <label>
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
          <input type="text" class="input_type" name="form_65[kelainan_ekstremitas]" id="kelainan_ekstremitas">
        </label>
      </div>
    </div>
<br>
    <p><b>PEMBESARAN KELENJAR</b></p>
    <div class="form-section">
      <div class="form-row">
        <label>Leher</label>
        <label>: 
          <input type="text" class="input_type" name="form_65[pembesaran_leher]" id="pembesaran_leher">
        </label>
      </div>
      <div class="form-row">
        <label>Submandibulum</label>
        <label>: 
          <input type="text" class="input_type" name="form_65[submandibulum]" id="submandibulum">
        </label>
      </div>
      <div class="form-row">
        <label>Ketiak</label>
        <label>: 
          <input type="text" class="input_type" name="form_65[ketiak]" id="ketiak">
        </label>
      </div>
      <div class="form-row">
        <label>Selangkangan</label>
        <label>: 
          <input type="text" class="input_type" name="form_65[selangkangan]" id="selangkangan">
        </label>
      </div>
    </div>

<br>
<p><b>REFLEKS-REFLEKS</b></p>
<div class="form-section">
  <div class="form-row">
    <label>Tendon</label>
    <label>: 
      <input type="text" class="input_type" name="form_65[tendon]" id="tendon" onchange="fillthis('tendon')">
    </label>
  </div>
  <div class="form-row">
    <label>Moro</label>
    <label>: 
      <input type="text" class="input_type" name="form_65[moro]" id="moro" onchange="fillthis('moro')">
    </label>
  </div>
  <div class="form-row">
    <label>Hisap</label>
    <label>: 
      <input type="text" class="input_type" name="form_65[hisap]" id="hisap" onchange="fillthis('hisap')">
    </label>
  </div>
  <div class="form-row">
    <label>Pegang</label>
    <label>: 
      <input type="text" class="input_type" name="form_65[pegang]" id="pegang" onchange="fillthis('pegang')">
    </label>
  </div>
  <div class="form-row">
    <label>Rooting</label>
    <label>: 
      <input type="text" class="input_type" name="form_65[rooting]" id="rooting" onchange="fillthis('rooting')">
    </label>
  </div>
  <div class="form-row">
    <label>Babinski</label>
    <label>: 
      <input type="text" class="input_type" name="form_65[babinski]" id="babinski" onchange="fillthis('babinski')">
    </label>
  </div>
</div>

<br>
<p><b>UKURAN ANTROPOMETRI</b></p>
<div class="form-section">
  <div class="form-row">
    <label>Lingkaran kepala</label>
    <label>: 
      <input type="text" class="input_type" name="form_65[lingkar_kepala]" id="lingkar_kepala" onchange="fillthis('lingkar_kepala')">
    </label>
  </div>
  <div class="form-row">
    <label>Lingkaran dada</label>
    <label>: 
      <input type="text" class="input_type" name="form_65[lingkar_dada]" id="lingkar_dada" onchange="fillthis('lingkar_dada')">
    </label>
  </div>
  <div class="form-row">
    <label>Lingkaran perut</label>
    <label>: 
      <input type="text" class="input_type" name="form_65[lingkar_perut]" id="lingkar_perut" onchange="fillthis('lingkar_perut')">
    </label>
  </div>
  <div class="form-row">
    <label>Panjang lengan</label>
    <label>: 
      <input type="text" class="input_type" name="form_65[panjang_lengan]" id="panjang_lengan" onchange="fillthis('panjang_lengan')">
    </label>
  </div>
  <div class="form-row">
    <label>Tanda lahir (bila ada)</label>
    <label>: 
      <input type="text" class="input_type" name="form_65[tanda_lahir]" id="tanda_lahir" onchange="fillthis('tanda_lahir')">
    </label>
  </div>
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