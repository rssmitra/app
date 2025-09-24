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
      var hiddenInputName = 'form_56[ttd_' + role + ']';
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

<div style="text-align: center; font-size: 18px;"><b>RIWAYAT PENYAKIT PASIEN KASUS BAYI BARU LAHIR 1</b></div>

<br>
<style>
  .form-container {
    display: flex;
    justify-content: space-between;
  }
  .form-column {
    width: 48%; /* Adjust width as needed */
  }
  .form-section {
    margin-bottom: 16px;
  }
  .form-row {
    display: flex;
    margin-bottom: 8px;
    align-items: center;
  }
  .form-row label {
    flex: 0 0 10%; /* Adjust label width as needed */
    padding-right: 1px;
    white-space: nowrap;
  }
  .form-row input {
    flex: 1;
    padding: 4px;
    box-sizing: border-box;
  }
  .checkbox-group {
    display: flex;
    gap: 1px;
  }
</style>
<!-- PERTAMA --- -->
<div class="form-container">
  <div class="form-column">
    <p><b><u>ANAMNESIS</u></b></p>
    <div class="form-section">
      <div class="form-row">
        <label>Umur Ibu :</label>
        <input type="text" class="input_type" name="form_64[umur_ibu]" id="umur_ibu" onchange="fillthis('umur_ibu')">
      </div>
      <div class="form-row">
        <label>G :</label>
        <input type="text" class="input_type" name="form_64[g]" id="g" onchange="fillthis('g')" style="width: 20px;">
        <label style="margin-left: 5px;">A :</label>
        <input type="text" class="input_type" name="form_64[a]" id="a" onchange="fillthis('a')" style="width: 20px;">
        <label style="margin-left: 5px;">P :</label>
        <input type="text" class="input_type" name="form_64[p]" id="p" onchange="fillthis('p')" style="width: 20px;">
      </div>
    </div>
  </div>

  <div class="form-column" style="text-align: right;">
    <div class="form-section"><br><br>
      <div class="form-row">
        <label>Golongan Darah Ibu :</label>
        <input type="text" class="input_type" name="form_64[gol_darah_ibu]" id="gol_darah_ibu" onchange="fillthis('gol_darah_ibu')">
      </div>
      <div class="form-row">
        <label>Golongan Darah Bayi :</label>
        <input type="text" class="input_type" name="form_64[gol_darah_bayi]" id="gol_darah_bayi" onchange="fillthis('gol_darah_bayi')">
      </div>
    </div>
  </div>
</div>

<!-- KEDUA --- -->
<b><p>RIWAYAT PERSALINAN</p></b>
<p>(Diisi oleh dokter / bidan yang menolong persalinan)</p>
<div class="form-container" style="display: flex; justify-content: space-between; gap: 20px;">
  <div class="form-column" style="flex: 1;">
    <p><b>Kelahiran :</b></p>
    <div class="form-section">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_64[kelahiran][]" id="kelahiran_tunggal" onclick="checkthis('kelahiran_tunggal')">
          <span class="lbl"> Tunggal</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_64[kelahiran][]" id="kelahiran_gemelli" onclick="checkthis('kelahiran_gemelli')">
          <span class="lbl"> Gemelli</span>
        </label>
      </div>
    </div>
  </div>
  
  <div class="form-column" style="flex: 1;">
    <!-- <p><b>Usia Gestasi :</b></p> -->
     <p><br></p>
    <div class="form-section">
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_64[usia_gestasi][]" id="usia_gestasi_prematur" onclick="checkthis('usia_gestasi_prematur')">
          <span class="lbl"> Prematur</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_64[usia_gestasi][]" id="usia_gestasi_aterm" onclick="checkthis('usia_gestasi_aterm')">
          <span class="lbl"> Aterm</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_64[usia_gestasi][]" id="usia_gestasi_postmatur" onclick="checkthis('usia_gestasi_postmatur')">
          <span class="lbl"> Postmatur</span>
        </label>
      </div>
    </div>
  </div>

  <div class="form-column" style="text-align: right;">
    <div class="form-section"><br>
      <div class="form-row">
        <label>Ketuban</label>
        <input type="text" class="input_type" name="form_64[ketuban]" id="ketuban" onchange="fillthis('ketuban')">
      </div>
      <div class="form-row">
        <label>Lain-lain</label>
        <input type="text" class="input_type" name="form_64[riwayat_lain]" id="riwayat_lain" onchange="fillthis('riwayat_lain')">
      </div>
    </div>
  </div>
</div>
<!-- KETIGA --- -->
<div class="form-container" style="display: flex; justify-content: space-between; gap: 20px;">

  <div class="form-column" style="flex: 1;">
    <p><b>Cara Persalinan :</b></p>
    <div class="form-section">
      <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_64[cara_persalinan][]" id="cara_persalinan_normal" onclick="checkthis('cara_persalinan_normal')">
            <span class="lbl"> Normal</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_64[cara_persalinan][]" id="cara_persalinan_tindakan" onclick="checkthis('cara_persalinan_tindakan')">
            <span class="lbl"> Tindakan</span><input type="text" class="input_type" name="form_64[ket_persalinan_tindakan]" id="ket_persalinan_tindakan" style="width: 300px;" onchange="fillthis('ket_persalinan_tindakan')">
            <span class="lbl"> Indikasi</span><input type="text" class="input_type" name="form_64[indikasi]" id="indikasi" style="width: 300px;" onchange="fillthis('indikasi')">
          </label>
        </div>
    </div>
  </div>
</div>

<!-- KEEMPAT --- -->
<p><center><b>SKOR APGAR</b></center></p>
<table style="width:100%; border-collapse: collapse; border: 1px solid black;">
  <thead style="background-color: #f2f2f2;">
    <tr>
      <th rowspan="2" style="border: 1px solid black; padding: 8px; text-align: center; width: 25%;">Tanda</th>
      <th rowspan="2" style="border: 1px solid black; padding: 8px; text-align: center; width: 15%;">Nilai 0</th>
      <th rowspan="2" style="border: 1px solid black; padding: 8px; text-align: center; width: 15%;">Nilai 1</th>
      <th rowspan="2" style="border: 1px solid black; padding: 8px; text-align: center; width: 15%;">Nilai 2</th>
      <th style="border: 1px solid black; padding: 8px; text-align: center;" colspan="2">Skor</th>
    </tr>
    <tr>
      <th style="border: 1px solid black; padding: 8px; text-align: center;">1</th>
      <th style="border: 1px solid black; padding: 8px; text-align: center;">5</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="border: 1px solid black; padding: 8px;">Warna Kulit</td>
      <td style="border: 1px solid black; padding: 8px;">Seluruh tubuh biru/pucat</td>
      <td style="border: 1px solid black; padding: 8px;">Badan merah kaki tangan biru</td>
      <td style="border: 1px solid black; padding: 8px;">Seluruh tubuh kemerahan</td>
      <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[warna_kulit_1]" id="warna_kulit_1" onchange="fillthis('warna_kulit_1')" style="width: 100%; box-sizing: border-box; text-align:center;"></td>
      <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[warna_kulit_5]" id="warna_kulit_5" onchange="fillthis('warna_kulit_5')" style="width: 100%; box-sizing: border-box; text-align:center;"></td>
    </tr>
    <tr>
      <td style="border: 1px solid black; padding: 8px;">Nadi</td>
      <td style="border: 1px solid black; padding: 8px;">Tidak ada</td>
      <td style="border: 1px solid black; padding: 8px;">&lt;100 x/mnt</td>
      <td style="border: 1px solid black; padding: 8px;">&gt;100 x/mnt</td>
      <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[nadi_1]" id="nadi_1" onchange="fillthis('nadi_1')" style="width: 100%; box-sizing: border-box; text-align:center;"></td>
      <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[nadi_5]" id="nadi_5" onchange="fillthis('nadi_5')" style="width: 100%; box-sizing: border-box; text-align:center;"></td>
    </tr>
    <tr>
      <td style="border: 1px solid black; padding: 8px;">Reflek</td>
      <td style="border: 1px solid black; padding: 8px;">Tidak ada</td>
      <td style="border: 1px solid black; padding: 8px;">Pergerakan sedikit</td>
      <td style="border: 1px solid black; padding: 8px;">Bersin/menangis</td>
      <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[reflek_1]" id="reflek_1" onchange="fillthis('reflek_1')" style="width: 100%; box-sizing: border-box; text-align:center;"></td>
      <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[reflek_5]" id="reflek_5" onchange="fillthis('reflek_5')" style="width: 100%; box-sizing: border-box; text-align:center;"></td>
    </tr>
    <tr>
      <td style="border: 1px solid black; padding: 8px;">Tonus Otot</td>
      <td style="border: 1px solid black; padding: 8px;">Lumpuh</td>
      <td style="border: 1px solid black; padding: 8px;">Ekstremitas sedikit fleksi</td>
      <td style="border: 1px solid black; padding: 8px;">Gerakan aktif ekstremitas</td>
      <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[tonus_otot_1]" id="tonus_otot_1" onchange="fillthis('tonus_otot_1')" style="width: 100%; box-sizing: border-box; text-align:center;"></td>
      <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[tonus_otot_5]" id="tonus_otot_5" onchange="fillthis('tonus_otot_5')" style="width: 100%; box-sizing: border-box; text-align:center;"></td>
    </tr>
    <tr>
      <td style="border: 1px solid black; padding: 8px;">Usaha Bernafas</td>
      <td style="border: 1px solid black; padding: 8px;">Tidak ada</td>
      <td style="border: 1px solid black; padding: 8px;">Lambat/menangis</td>
      <td style="border: 1px solid black; padding: 8px;">Menangis keras/kuat</td>
      <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[usaha_bernafas_1]" id="usaha_bernafas_1" onchange="fillthis('usaha_bernafas_1')" style="width: 100%; box-sizing: border-box; text-align:center;"></td>
      <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[usaha_bernafas_5]" id="usaha_bernafas_5" onchange="fillthis('usaha_bernafas_5')" style="width: 100%; box-sizing: border-box; text-align:center;"></td>
    </tr>
    <tr>
      <th colspan="4" style="border: 1px solid black; padding: 8px; text-align: center; width: 15%;">Total</th>
      <th style="border: 1px solid black; padding: 8px; text-align: center;"><input type="text" class="input_type" name="form_64[total_skor_1]" id="total_skor_1" onchange="fillthis('total_skor_1')" style="width: 100%; box-sizing: border-box; text-align:center;"></th>
      <th style="border: 1px solid black; padding: 8px; text-align: center;"><input type="text" class="input_type" name="form_64[total_skor_2]" id="total_skor_2" onchange="fillthis('total_skor_2')" style="width: 100%; box-sizing: border-box; text-align:center;"></th>
    </tr>
  </tbody>
</table>

<br><br>
<!-- KELIMA NEW --- -->
<div class="form-container">
  <div class="form-column">
    <p>RESUSITASI</p>
    <div class="form-section">
      <div class="form-row">
        <label>
            <input type="checkbox" class="ace" name="form_64[resusitasi][]" id="resusitasi_jalan_nafas" onclick="checkthis('resusitasi_jalan_nafas')">
            <span class="lbl"> Pembersihan jalan nafas</span>
          </label>
      </div>
      <div class="form-row">
        <label>
            <input type="checkbox" class="ace" name="form_64[resusitasi][]" id="resusitasi_o2" onclick="checkthis('resusitasi_o2')">
            <span class="lbl"> Pemberian O2</span>
          </label>
      </div>
    </div>
  </div>

  <div class="form-column" style="text-align: right;">
    <div class="form-section"><br><br>
      <div class="form-row">
        <label>
            <input type="checkbox" class="ace" name="form_64[resusitasi][]" id="resusitasi_massage" onclick="checkthis('resusitasi_massage')">
            <span class="lbl"> External cardiac massage</span>
        </label>
      </div>
      <div class="form-row">
        <label>
            <input type="checkbox" class="ace" name="form_64[resusitasi][]" id="resusitasi_obat" onclick="checkthis('resusitasi_obat')">
            <span class="lbl"> Obat-obatan :</span>
        </label>
        <input type="text" class="input_type" name="form_64[resusitasi_obat_ket1]" id="resusitasi_obat_ket1" onchange="fillthis('resusitasi_obat_ket1')">
      </div>
      <div class="form-row">
        <br><input type="text" class="input_type" name="form_64[resusitasi_obat_ket2]" id="resusitasi_obat_ket2" onchange="fillthis('resusitasi_obat_ket2')">
      </div>
      <div class="form-row">
        <br><input type="text" class="input_type" name="form_64[resusitasi_obat_ket3]" id="resusitasi_obat_ket3" onchange="fillthis('resusitasi_obat_ket3')">
      </div>
      </div>
    </div>
  </div>
</div>

<br><br>
<!-- KEENAM NEW --- -->
<div class="form-container">
  <div class="form-column">
    <p><b>Placenta</b></p>
    <div class="form-section">
      <div class="form-row">
        <label>Berat :</label>
      <input type="text" class="input_type" name="form_64[berat_plasenta]" id="berat_plasenta" onchange="fillthis('berat_plasenta')">
      </div>
      <div class="form-row">
        <label>Ukuran :</label>
      <input type="text" class="input_type" name="form_64[ukuran_plasenta]" id="ukuran_plasenta" onchange="fillthis('ukuran_plasenta')">
      </div>
      <div class="form-row">
      <label>Kelamin :</label>
      <input type="text" class="input_type" name="form_64[kelamin_bayi]" id="kelamin_bayi" onchange="fillthis('kelamin_bayi')">
      </div>
    </div>
  </div>

<div class="form-column">
  <div class="form-section"><br><br>

    <div class="form-row">
      <label style="display:inline-block;width:180px;">Tali Pusat :</label>
      <input type="text" class="input_type" name="form_64[tali_pusat]" id="tali_pusat" onchange="fillthis('tali_pusat')">
    </div>

    <div class="form-row">
      <label style="display:inline-block;width:180px;">Panjang :</label>
      <input type="text" class="input_type" name="form_64[panjang_plasenta]" id="panjang_plasenta" onchange="fillthis('panjang_plasenta')">
    </div>

    <div class="form-row">
      <label style="display:inline-block;width:180px;">Jumlah Pembuluh Darah :</label>
      <input type="text" class="input_type" name="form_64[jml_pembuluh_darah]" id="jml_pembuluh_darah" onchange="fillthis('jml_pembuluh_darah')">
    </div>

    <div class="form-row">
      <label style="display:inline-block;width:180px;">Kelainan :</label>
      <input type="text" class="input_type" name="form_64[kelainan_plasenta]" id="kelainan_plasenta" onchange="fillthis('kelainan_plasenta')">
    </div>

  </div>
</div>

</div>

<br><br>
<p><b><center>GRAFIK BERAT BADAN</center></b></p>
<div class="form-section">
  <table style="width:100%; border-collapse: collapse; border: 1px solid black; text-align: center;">
    <thead>
      <tr>
        <th style="border: 1px solid black; padding: 8px; width: 15%;"></th>
        <th style="border: 1px solid black; padding: 8px;" colspan="20"></th>
      </tr>
    </thead>
    <tbody>
       <tr>
       <td style="border: 1px solid black; padding: 8px;"><b>Tanggal</b></td>
       <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_tanggal_1]" id="grafik_tanggal_1" onchange="fillthis('grafik_tanggal_1')" style="width: 100%; box-sizing: border-box; border: none;"></td>
       <td colspan="2" style="border: 1px solid black; padding: 8px"><input type="text" class="input_type" name="form_64[grafik_tanggal_2]" id="grafik_tanggal_2" onchange="fillthis('grafik_tanggal_2')" style="width: 100%; box-sizing: border-box; border: none;"></td>
       <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_tanggal_3]" id="grafik_tanggal_3" onchange="fillthis('grafik_tanggal_3')" style="width: 100%; box-sizing: border-box; border: none;"></td>
       <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_tanggal_4]" id="grafik_tanggal_4" onchange="fillthis('grafik_tanggal_4')" style="width: 100%; box-sizing: border-box; border: none;"></td>
       <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_tanggal_5]" id="grafik_tanggal_5" onchange="fillthis('grafik_tanggal_5')" style="width: 100%; box-sizing: border-box; border: none;"></td>
       <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_tanggal_6]" id="grafik_tanggal_6" onchange="fillthis('grafik_tanggal_6')" style="width: 100%; box-sizing: border-box; border: none;"></td>
       <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_tanggal_7]" id="grafik_tanggal_7" onchange="fillthis('grafik_tanggal_7')" style="width: 100%; box-sizing: border-box; border: none;"></td>
       <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_tanggal_8]" id="grafik_tanggal_8" onchange="fillthis('grafik_tanggal_8')" style="width: 100%; box-sizing: border-box; border: none;"></td>
       <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_tanggal_9]" id="grafik_tanggal_9" onchange="fillthis('grafik_tanggal_9')" style="width: 100%; box-sizing: border-box; border: none;"></td>
       <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_tanggal_10]" id="grafik_tanggal_10" onchange="fillthis('grafik_tanggal_10')" style="width: 100%; box-sizing: border-box; border: none;"></td>
      </tr>
      <tr>
        <td style="border: 1px solid black; padding: 8px;"><b>Suhu</b></td>
        <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_suhu_1]" id="grafik_suhu_1" onchange="fillthis('grafik_suhu_1')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_suhu_2]" id="grafik_suhu_2" onchange="fillthis('grafik_suhu_2')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_suhu_3]" id="grafik_suhu_3" onchange="fillthis('grafik_suhu_3')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_suhu_4]" id="grafik_suhu_4" onchange="fillthis('grafik_suhu_4')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_suhu_5]" id="grafik_suhu_5" onchange="fillthis('grafik_suhu_5')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_suhu_6]" id="grafik_suhu_6" onchange="fillthis('grafik_suhu_6')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_suhu_7]" id="grafik_suhu_7" onchange="fillthis('grafik_suhu_7')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_suhu_8]" id="grafik_suhu_8" onchange="fillthis('grafik_suhu_8')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_suhu_9]" id="grafik_suhu_9" onchange="fillthis('grafik_suhu_9')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td colspan="2" style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_suhu_10]" id="grafik_suhu_10" onchange="fillthis('grafik_suhu_10')" style="width: 100%; box-sizing: border-box; border: none;"></td>
      </tr>
      <tr>
        <td style="border: 1px solid black; padding: 8px;"><b>Berat Badan</b></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_1]" id="grafik_bb_1" onchange="fillthis('grafik_bb_1')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_2]" id="grafik_bb_2" onchange="fillthis('grafik_bb_2')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_3]" id="grafik_bb_3" onchange="fillthis('grafik_bb_3')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_4]" id="grafik_bb_4" onchange="fillthis('grafik_bb_4')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_5]" id="grafik_bb_5" onchange="fillthis('grafik_bb_5')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_6]" id="grafik_bb_6" onchange="fillthis('grafik_bb_6')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_7]" id="grafik_bb_7" onchange="fillthis('grafik_bb_7')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_8]" id="grafik_bb_8" onchange="fillthis('grafik_bb_8')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_9]" id="grafik_bb_9" onchange="fillthis('grafik_bb_9')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_10]" id="grafik_bb_10" onchange="fillthis('grafik_bb_10')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_11]" id="grafik_bb_11" onchange="fillthis('grafik_bb_11')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_12]" id="grafik_bb_12" onchange="fillthis('grafik_bb_12')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_13]" id="grafik_bb_13" onchange="fillthis('grafik_bb_13')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_14]" id="grafik_bb_14" onchange="fillthis('grafik_bb_14')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_15]" id="grafik_bb_15" onchange="fillthis('grafik_bb_15')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_16]" id="grafik_bb_16" onchange="fillthis('grafik_bb_16')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_17]" id="grafik_bb_17" onchange="fillthis('grafik_bb_17')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_18]" id="grafik_bb_18" onchange="fillthis('grafik_bb_18')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_19]" id="grafik_bb_19" onchange="fillthis('grafik_bb_19')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_bb_20]" id="grafik_bb_20" onchange="fillthis('grafik_bb_20')" style="width: 100%; box-sizing: border-box; border: none;"></td>
      </tr>
      <tr>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_judul]" id="grafik_data4_judul" onchange="fillthis('grafik_data4_judul')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_1]" id="grafik_data4_1" onchange="fillthis('grafik_data4_1')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_2]" id="grafik_data4_2" onchange="fillthis('grafik_data4_2')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_3]" id="grafik_data4_3" onchange="fillthis('grafik_data4_3')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_4]" id="grafik_data4_4" onchange="fillthis('grafik_data4_4')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_5]" id="grafik_data4_5" onchange="fillthis('grafik_data4_5')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_6]" id="grafik_data4_6" onchange="fillthis('grafik_data4_6')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_7]" id="grafik_data4_7" onchange="fillthis('grafik_data4_7')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_8]" id="grafik_data4_8" onchange="fillthis('grafik_data4_8')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_9]" id="grafik_data4_9" onchange="fillthis('grafik_data4_9')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_10]" id="grafik_data4_10" onchange="fillthis('grafik_data4_10')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_11]" id="grafik_data4_11" onchange="fillthis('grafik_data4_11')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_12]" id="grafik_data4_12" onchange="fillthis('grafik_data4_12')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_13]" id="grafik_data4_13" onchange="fillthis('grafik_data4_13')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_14]" id="grafik_data4_14" onchange="fillthis('grafik_data4_14')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_15]" id="grafik_data4_15" onchange="fillthis('grafik_data4_15')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_16]" id="grafik_data4_16" onchange="fillthis('grafik_data4_16')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_17]" id="grafik_data4_17" onchange="fillthis('grafik_data4_17')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_18]" id="grafik_data4_18" onchange="fillthis('grafik_data4_18')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_19]" id="grafik_data4_19" onchange="fillthis('grafik_data4_19')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data4_20]" id="grafik_data4_20" onchange="fillthis('grafik_data4_20')" style="width: 100%; box-sizing: border-box; border: none;"></td>
      </tr>
      <tr>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_judul]" id="grafik_data5_judul" onchange="fillthis('grafik_data5_judul')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_1]" id="grafik_data5_1" onchange="fillthis('grafik_data5_1')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_2]" id="grafik_data5_2" onchange="fillthis('grafik_data5_2')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_3]" id="grafik_data5_3" onchange="fillthis('grafik_data5_3')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_4]" id="grafik_data5_4" onchange="fillthis('grafik_data5_4')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_5]" id="grafik_data5_5" onchange="fillthis('grafik_data5_5')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_6]" id="grafik_data5_6" onchange="fillthis('grafik_data5_6')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_7]" id="grafik_data5_7" onchange="fillthis('grafik_data5_7')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_8]" id="grafik_data5_8" onchange="fillthis('grafik_data5_8')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_9]" id="grafik_data5_9" onchange="fillthis('grafik_data5_9')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_10]" id="grafik_data5_10" onchange="fillthis('grafik_data5_10')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_11]" id="grafik_data5_11" onchange="fillthis('grafik_data5_11')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_12]" id="grafik_data5_12" onchange="fillthis('grafik_data5_12')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_13]" id="grafik_data5_13" onchange="fillthis('grafik_data5_13')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_14]" id="grafik_data5_14" onchange="fillthis('grafik_data5_14')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_15]" id="grafik_data5_15" onchange="fillthis('grafik_data5_15')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_16]" id="grafik_data5_16" onchange="fillthis('grafik_data5_16')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_17]" id="grafik_data5_17" onchange="fillthis('grafik_data5_17')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_18]" id="grafik_data5_18" onchange="fillthis('grafik_data5_18')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_19]" id="grafik_data5_19" onchange="fillthis('grafik_data5_19')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data5_20]" id="grafik_data5_20" onchange="fillthis('grafik_data5_20')" style="width: 100%; box-sizing: border-box; border: none;"></td>
      </tr>
      <tr>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_judul]" id="grafik_data6_judul" onchange="fillthis('grafik_data6_judul')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_1]" id="grafik_data6_1" onchange="fillthis('grafik_data6_1')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_2]" id="grafik_data6_2" onchange="fillthis('grafik_data6_2')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_3]" id="grafik_data6_3" onchange="fillthis('grafik_data6_3')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_4]" id="grafik_data6_4" onchange="fillthis('grafik_data6_4')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_5]" id="grafik_data6_5" onchange="fillthis('grafik_data6_5')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_6]" id="grafik_data6_6" onchange="fillthis('grafik_data6_6')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_7]" id="grafik_data6_7" onchange="fillthis('grafik_data6_7')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_8]" id="grafik_data6_8" onchange="fillthis('grafik_data6_8')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_9]" id="grafik_data6_9" onchange="fillthis('grafik_data6_9')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_10]" id="grafik_data6_10" onchange="fillthis('grafik_data6_10')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_11]" id="grafik_data6_11" onchange="fillthis('grafik_data6_11')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_12]" id="grafik_data6_12" onchange="fillthis('grafik_data6_12')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_13]" id="grafik_data6_13" onchange="fillthis('grafik_data6_13')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_14]" id="grafik_data6_14" onchange="fillthis('grafik_data6_14')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_15]" id="grafik_data6_15" onchange="fillthis('grafik_data6_15')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_16]" id="grafik_data6_16" onchange="fillthis('grafik_data6_16')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_17]" id="grafik_data6_17" onchange="fillthis('grafik_data6_17')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_18]" id="grafik_data6_18" onchange="fillthis('grafik_data6_18')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_19]" id="grafik_data6_19" onchange="fillthis('grafik_data6_19')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data6_20]" id="grafik_data6_20" onchange="fillthis('grafik_data6_20')" style="width: 100%; box-sizing: border-box; border: none;"></td>
      </tr>
      <tr>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_judul]" id="grafik_data7_judul" onchange="fillthis('grafik_data7_judul')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_1]" id="grafik_data7_1" onchange="fillthis('grafik_data7_1')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_2]" id="grafik_data7_2" onchange="fillthis('grafik_data7_2')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_3]" id="grafik_data7_3" onchange="fillthis('grafik_data7_3')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_4]" id="grafik_data7_4" onchange="fillthis('grafik_data7_4')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_5]" id="grafik_data7_5" onchange="fillthis('grafik_data7_5')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_6]" id="grafik_data7_6" onchange="fillthis('grafik_data7_6')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_7]" id="grafik_data7_7" onchange="fillthis('grafik_data7_7')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_8]" id="grafik_data7_8" onchange="fillthis('grafik_data7_8')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_9]" id="grafik_data7_9" onchange="fillthis('grafik_data7_9')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_10]" id="grafik_data7_10" onchange="fillthis('grafik_data7_10')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_11]" id="grafik_data7_11" onchange="fillthis('grafik_data7_11')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_12]" id="grafik_data7_12" onchange="fillthis('grafik_data7_12')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_13]" id="grafik_data7_13" onchange="fillthis('grafik_data7_13')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_14]" id="grafik_data7_14" onchange="fillthis('grafik_data7_14')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_15]" id="grafik_data7_15" onchange="fillthis('grafik_data7_15')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_16]" id="grafik_data7_16" onchange="fillthis('grafik_data7_16')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_17]" id="grafik_data7_17" onchange="fillthis('grafik_data7_17')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_18]" id="grafik_data7_18" onchange="fillthis('grafik_data7_18')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_19]" id="grafik_data7_19" onchange="fillthis('grafik_data7_19')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data7_20]" id="grafik_data7_20" onchange="fillthis('grafik_data7_20')" style="width: 100%; box-sizing: border-box; border: none;"></td>
      </tr>
      <tr>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_judul]" id="grafik_data8_judul" onchange="fillthis('grafik_data8_judul')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_1]" id="grafik_data8_1" onchange="fillthis('grafik_data8_1')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_2]" id="grafik_data8_2" onchange="fillthis('grafik_data8_2')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_3]" id="grafik_data8_3" onchange="fillthis('grafik_data8_3')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_4]" id="grafik_data8_4" onchange="fillthis('grafik_data8_4')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_5]" id="grafik_data8_5" onchange="fillthis('grafik_data8_5')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_6]" id="grafik_data8_6" onchange="fillthis('grafik_data8_6')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_7]" id="grafik_data8_7" onchange="fillthis('grafik_data8_7')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_8]" id="grafik_data8_8" onchange="fillthis('grafik_data8_8')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_9]" id="grafik_data8_9" onchange="fillthis('grafik_data8_9')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_10]" id="grafik_data8_10" onchange="fillthis('grafik_data8_10')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_11]" id="grafik_data8_11" onchange="fillthis('grafik_data8_11')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_12]" id="grafik_data8_12" onchange="fillthis('grafik_data8_12')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_13]" id="grafik_data8_13" onchange="fillthis('grafik_data8_13')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_14]" id="grafik_data8_14" onchange="fillthis('grafik_data8_14')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_15]" id="grafik_data8_15" onchange="fillthis('grafik_data8_15')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_16]" id="grafik_data8_16" onchange="fillthis('grafik_data8_16')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_17]" id="grafik_data8_17" onchange="fillthis('grafik_data8_17')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_18]" id="grafik_data8_18" onchange="fillthis('grafik_data8_18')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_19]" id="grafik_data8_19" onchange="fillthis('grafik_data8_19')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data8_20]" id="grafik_data8_20" onchange="fillthis('grafik_data8_20')" style="width: 100%; box-sizing: border-box; border: none;"></td>
      </tr>
      <tr>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_judul]" id="grafik_data9_judul" onchange="fillthis('grafik_data9_judul')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_1]" id="grafik_data9_1" onchange="fillthis('grafik_data9_1')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_2]" id="grafik_data9_2" onchange="fillthis('grafik_data9_2')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_3]" id="grafik_data9_3" onchange="fillthis('grafik_data9_3')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_4]" id="grafik_data9_4" onchange="fillthis('grafik_data9_4')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_5]" id="grafik_data9_5" onchange="fillthis('grafik_data9_5')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_6]" id="grafik_data9_6" onchange="fillthis('grafik_data9_6')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_7]" id="grafik_data9_7" onchange="fillthis('grafik_data9_7')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_8]" id="grafik_data9_8" onchange="fillthis('grafik_data9_8')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_9]" id="grafik_data9_9" onchange="fillthis('grafik_data9_9')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_10]" id="grafik_data9_10" onchange="fillthis('grafik_data9_10')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_11]" id="grafik_data9_11" onchange="fillthis('grafik_data9_11')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_12]" id="grafik_data9_12" onchange="fillthis('grafik_data9_12')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_13]" id="grafik_data9_13" onchange="fillthis('grafik_data9_13')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_14]" id="grafik_data9_14" onchange="fillthis('grafik_data9_14')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_15]" id="grafik_data9_15" onchange="fillthis('grafik_data9_15')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_16]" id="grafik_data9_16" onchange="fillthis('grafik_data9_16')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_17]" id="grafik_data9_17" onchange="fillthis('grafik_data9_17')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_18]" id="grafik_data9_18" onchange="fillthis('grafik_data9_18')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_19]" id="grafik_data9_19" onchange="fillthis('grafik_data9_19')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data9_20]" id="grafik_data9_20" onchange="fillthis('grafik_data9_20')" style="width: 100%; box-sizing: border-box; border: none;"></td>
      </tr>
      <tr>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_judul]" id="grafik_data10_judul" onchange="fillthis('grafik_data10_judul')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_1]" id="grafik_data10_1" onchange="fillthis('grafik_data10_1')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_2]" id="grafik_data10_2" onchange="fillthis('grafik_data10_2')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_3]" id="grafik_data10_3" onchange="fillthis('grafik_data10_3')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_4]" id="grafik_data10_4" onchange="fillthis('grafik_data10_4')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_5]" id="grafik_data10_5" onchange="fillthis('grafik_data10_5')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_6]" id="grafik_data10_6" onchange="fillthis('grafik_data10_6')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_7]" id="grafik_data10_7" onchange="fillthis('grafik_data10_7')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_8]" id="grafik_data10_8" onchange="fillthis('grafik_data10_8')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_9]" id="grafik_data10_9" onchange="fillthis('grafik_data10_9')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_10]" id="grafik_data10_10" onchange="fillthis('grafik_data10_10')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_11]" id="grafik_data10_11" onchange="fillthis('grafik_data10_11')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_12]" id="grafik_data10_12" onchange="fillthis('grafik_data10_12')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_13]" id="grafik_data10_13" onchange="fillthis('grafik_data10_13')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_14]" id="grafik_data10_14" onchange="fillthis('grafik_data10_14')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_15]" id="grafik_data10_15" onchange="fillthis('grafik_data10_15')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_16]" id="grafik_data10_16" onchange="fillthis('grafik_data10_16')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_17]" id="grafik_data10_17" onchange="fillthis('grafik_data10_17')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_18]" id="grafik_data10_18" onchange="fillthis('grafik_data10_18')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_19]" id="grafik_data10_19" onchange="fillthis('grafik_data10_19')" style="width: 100%; box-sizing: border-box; border: none;"></td>
        <td style="border: 1px solid black; padding: 8px;"><input type="text" class="input_type" name="form_64[grafik_data10_20]" id="grafik_data10_20" onchange="fillthis('grafik_data10_20')" style="width: 100%; box-sizing: border-box; border: none;"></td>
      </tr>
    </tbody>
  </table>
</div>

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