<script>
jQuery(function($) {  

  // datepicker init (hanya untuk .date-picker)
  $('.date-picker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true
  });

  // timepicker init (contoh memakai plugin timepicker)
  $('.time-picker').timepicker({
    showMeridian: false,
    showSeconds: false,
    minuteStep: 1
  });

  // pastikan klik/fokus pada .time-picker tidak bubble ke datepicker
  $('.time-picker').on('focus click', function(e){
    e.stopPropagation();
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
      var hiddenInputName = 'form_148[ttd_' + role + ']';
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

<style>
  .ace + .lbl {
    font-size: 14px;
    font-weight: 300;
  }
</style>

<?php echo $header; ?>
<hr>
<br>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<!-- <p>edited by amelia yahya 05 Desember 2025</p> -->
<div style="text-align: center; font-size: 18px;"><b>LAPORAN PROSES PERSALINAN</b></div>
<br>

<table width="100%" border="0" style="font-size:14px;">

<!-- =================== TGL / JAM =================== -->
<!-- <tr>
  <td width="25%">Tgl / Jam</td>
  <td>
    <input type="date" class="input_type"
      name="form_148[tanggal]"
      id="tanggal"
      onchange="fillthis('tanggal')">

    <input type="text" class="input_type"
      name="form_148[jam]"
      id="jam"
      onchange="fillthis('jam')">
  </td>
</tr> -->
        <tr>
          <td style="width: 80px">Tanggal</td>
          <td>
            <input type="text" class="input_type date-picker" data-date-format="dd-mm-yyyy" name="form_148[tanggal]" id="tanggal" onchange="fillthis('tanggal');" value="<?php echo isset($value_form['tanggal'])?$value_form['tanggal']:date('d-m-Y')?>">
          </td>
        </tr>

        <tr>
          <td style="width: 80px">Jam</td>
          <td>
            <input type="text" class="input_type" name="form_148[jam_operasi]" id="jam_operasi" onchange="fillthis('jam_operasi')" value="<?php echo isset($value_form['jam_operasi'])?$value_form['jam_operasi']:date('H:i')?>">
          </td>
        </tr>

<tr>
  <td style="width:150px; vertical-align:top;">Selaput Ketuban</td>
  <td>

    <div style="margin-bottom:8px;">
      <label>
        <input type="checkbox" class="ace"
          name="form_148[ketuban_dipecahkan]"
          id="ketuban_dipecahkan"
          onclick="checkthis('ketuban_dipecahkan')">
        <span class="lbl"> dipecahkan</span>
      </label>

      &nbsp;tanggal
      <input style="width: 100px" type="text" class="input_type date-picker"
        data-date-format="dd-mm-yyyy"
        name="form_148[ketuban_dipecahkan_tgl]"
        id="ketuban_dipecahkan_tgl"
        onchange="fillthis('ketuban_dipecahkan_tgl')"
        value="<?= isset($value_form['ketuban_dipecahkan_tgl']) ? $value_form['ketuban_dipecahkan_tgl'] : date('d-m-Y') ?>">

      &nbsp;Jam
      <input type="text" class="input_type"
        name="form_148[ketuban_dipecahkan_jam]"
        id="ketuban_dipecahkan_jam"
        onchange="fillthis('ketuban_dipecahkan_jam')"
        value="<?= isset($value_form['ketuban_dipecahkan_jam']) ? $value_form['ketuban_dipecahkan_jam'] : date('H:i') ?>">
      </div>

    <div>
      <label>
        <input type="checkbox" class="ace"
          name="form_148[ketuban_spontan]"
          id="ketuban_spontan"
          onclick="checkthis('ketuban_spontan')">
        <span class="lbl"> pecah spontan</span>
      </label>

      &nbsp;tanggal
      <input type="text" style="width: 100px" class="input_type date-picker"
        data-date-format="dd-mm-yyyy"
        name="form_148[ketuban_spontan_tgl]"
        id="ketuban_spontan_tgl"
        onchange="fillthis('ketuban_spontan_tgl')"
        value="<?= isset($value_form['ketuban_spontan_tgl']) ? $value_form['ketuban_spontan_tgl'] : date('d-m-Y') ?>">

      &nbsp;jam
      <input type="text" class="input_type"
        name="form_148[ketuban_spontan_jam]"
        id="ketuban_spontan_jam"
        onchange="fillthis('ketuban_spontan_jam')"
        value="<?= isset($value_form['ketuban_spontan_jam']) ? $value_form['ketuban_spontan_jam'] : date('H:i') ?>">
    </div>

  </td>
</tr>

<!--<tr>
  <td>Lain-lain</td>
  <td>
    <textarea class="input_type"
      name="form_148[lain_lain]"
      id="lain_lain"
      onchange="fillthis('lain_lain')"
      style="width:100%;"></textarea>
  </td>
</tr>-->

<tr>
  <td style="vertical-align: top;">Lain-lain</td>
  <td>

    <div
      contenteditable="true"
      class="input_type"
      id="lain_lain"
      oninput="fillthis('lain_lain')"
      style="min-height:20px; padding:5px; border:1px solid #ccc; border-radius:4px;"
    >
      <?php echo isset($value_form['lain_lain']) ? nl2br($value_form['lain_lain']) : '' ?>
    </div>

    <!-- HIDDEN INPUT YANG DIKIRIM KE SERVER -->
    <input
      type="hidden"
      name="form_148[lain_lain]"
      id="lain_lain_hidden"
      value="<?php echo isset($value_form['lain_lain']) ? $value_form['lain_lain'] : '' ?>"
    >

  </td>
</tr>

<!-- =================== BAYI =================== -->
<tr><td colspan="2"><b><u>BAYI</u></b></td></tr>

<tr>
  <td>Lahir tanggal</td>
  <td>
      <input type="text" class="input_type date-picker" style="width: 100px"
        data-date-format="dd-mm-yyyy"
        name="form_148[bayi_lahir_tgl]"
        id="bayi_lahir_tgl"
        onchange="fillthis('bayi_lahir_tgl')"
        value="<?= isset($value_form['bayi_lahir_tgl']) ? $value_form['bayi_lahir_tgl'] : date('d-m-Y') ?>">

      &nbsp;jam
      <input type="text" class="input_type"
        name="form_148[bayi_lahir_jam]"
        id="bayi_lahir_jam"
        onchange="fillthis('bayi_lahir_jam')"
        value="<?= isset($value_form['bayi_lahir_jam']) ? $value_form['bayi_lahir_jam'] : date('H:i') ?>">
    </div>
      
    WIB
  </td>
</tr>

<tr>
  <td>Jenis Kelamin</td>
  <td><label>
      <input type="checkbox" class="ace"
        name="form_148[bayi_l]"
        id="bayi_l"
        onclick="checkthis('bayi_l')">
      <span class="lbl">Laki-laki</span>
    </label>

    <label>
      <input type="checkbox" class="ace"
        name="form_148[bayi_p]"
        id="bayi_p"
        onclick="checkthis('bayi_p')">
      <span class="lbl">Perempuan</span>
    </label>
</td>
</tr>

<tr>
  <td>Berat Badan</td>
  <td>
    <input type="text" class="input_type"
      name="form_148[bb_bayi]"
      id="bb_bayi"
      onchange="fillthis('bb_bayi')"
      style="width:80px"> gr /
      Panjang Badan
    <input type="text" class="input_type"
      name="form_148[pb_bayi]"
      id="pb_bayi"
      onchange="fillthis('pb_bayi')"
      style="width:80px"> cm

    ; Apgar skor:
    <input type="text" class="input_type"
      name="form_148[apgar]"
      id="apgar"
      onchange="fillthis('apgar')"
      style="width:80px">
  </td>
</tr>

<tr>
  <td>Resusitasi</td>
  <td> <input type="text" class="input_type"
    name="form_148[resusitasi]"
    id="resusitasi"
    onchange="fillthis('resusitasi')"
    style="width:100%"></td>
</tr>

<tr>
  <td>Kelainan Kongenital</td>
  <td> <input type="text" class="input_type"
    name="form_148[kelainan_kongenital]"
    id="kelainan_kongenital"
    onchange="fillthis('kelainan_kongenital')"
    style="width:100%"></td>
</tr>

<tr>
  <td style="vertical-align: top;">Bayi keadaan jelek</td>
  <td>

    <div style="margin-bottom:6px; font-size: 14px;">
      <label style="font-size: 14px;">
        lahir hidup kemudian meninggal

      <label style="font-size: 14px;">
        <input
          type="text"
          class="input_type"
          name="form_148[bayi_keadaan_jelek_time]"
          id="bayi_keadaan_jelek_time"
          onchange="fillthis('bayi_keadaan_jelek_time')"
          style="width:80px; margin-left:5px;"
        >
      </label>

      <!-- CHECKBOX MENIT -->
      <label style="margin-left:10px; font-size: 14px;">
        <input
          type="checkbox"
          class="ace"
          name="form_148[bayi_keadaan_jelek_menit]"
          id="bayi_keadaan_jelek_menit"
          onclick="checkthis('bayi_keadaan_jelek_menit')"
        >
        <span class="lbl">Menit</span>
      </label>

      <!-- CHECKBOX JAM -->
      <label style="margin-left:5px; font-size: 14px;">
        <input
          type="checkbox"
          class="ace"
          name="form_148[bayi_keadaan_jelek_jam]"
          id="bayi_keadaan_jelek_jam"
          onclick="checkthis('bayi_keadaan_jelek_jam')"
        >
        <span class="lbl">Jam</span>&nbsp;setelah lahir
      </label>
    </div>

    <div class="form-row">
  <label for="sebab_kematian" style="font-size: 14px;">Kemungkinan sebab kematian</label>
  <input
    type="text"
    class="input_type"
    name="form_148[sebab_kematian]"
    id="sebab_kematian"
    onchange="fillthis('sebab_kematian')"
    style="width:60%;"
  >
</div>

  </td>
</tr>


<tr>
  <td style="vertical-align: top;">Bayi lahir mati</td>
  <td>

    <div style="margin-bottom:6px;">
      <label for="maserasi_tingkat" style="margin-right:5px; font-size: 14px;">
       Maserasi tingkat
      </label>
      <input
        type="text"
        class="input_type"
        name="form_148[maserasi_tingkat]"
        id="maserasi_tingkat"
        onchange="fillthis('maserasi_tingkat')"
        style="width:40%;"
      >
    </div>

    <div>
      <label for="sebab_kematian_lain" style="margin-right:5px; font-size: 14px;">
        Kemungkinan sebab mati
      </label>
      <input
        type="text"
        class="input_type"
        name="form_148[sebab_kematian_lain]"
        id="sebab_kematian_lain"
        onchange="fillthis('sebab_kematian_lain')"
        style="width:60%;"
      >
    </div>

  </td>
</tr>

<!-- =================== PLASENTA =================== -->
<tr><td colspan="2"><b><u>PLASENTA</u></b></td></tr>

<tr>
  <td>Lahir jam</td>
  <td>

    <!-- <input
      type="time"
      class="input_type"
      name="form_148[plasenta_jam]"
      id="plasenta_jam"
      onchange="fillthis('plasenta_jam')"
      style="margin-right:10px;"
    > WIB &nbsp;&nbsp; -->

    <input type="text" class="input_type"
        name="form_148[plasenta_jam]"
        id="plasenta_jam"
        onchange="fillthis('plasenta_jam')"
        value="<?= isset($value_form['plasenta_jam']) ? $value_form['plasenta_jam'] : date('H:i') ?>"> WIB &nbsp;&nbsp;

    <label style="margin-right:8px;">
      <input
        type="checkbox"
        class="ace"
        name="form_148[plasenta_spontan]"
        id="plasenta_spontan"
        onclick="checkthis('plasenta_spontan')"
      >
      <span class="lbl">Spontan</span>
    </label>

    <label style="margin-right:8px;">
      <input
        type="checkbox"
        class="ace"
        name="form_148[plasenta_manual]"
        id="plasenta_manual"
        onclick="checkthis('plasenta_manual')"
      >
      <span class="lbl">Manual</span>
    </label>

    <label style="margin-right:8px;">
      <input
        type="checkbox"
        class="ace"
        name="form_148[plasenta_lengkap]"
        id="plasenta_lengkap"
        onclick="checkthis('plasenta_lengkap')"
      >
      <span class="lbl">Lengkap</span>
    </label>

    <label>
      <input
        type="checkbox"
        class="ace"
        name="form_148[plasenta_tidak_lengkap]"
        id="plasenta_tidak_lengkap"
        onclick="checkthis('plasenta_tidak_lengkap')"
      >
      <span class="lbl">Tidak lengkap</span>
    </label>

  </td>
</tr>


<tr>
  <td>Berat Plasenta</td>
  <td>
      <input
        type="text"
        class="input_type"
        name="form_148[berat_plasenta]"
        id="berat_plasenta"
        onchange="fillthis('berat_plasenta')"
        style="width:80px;">
      gr ;

      ukuran
      <input
        type="text"
        class="input_type"
        name="form_148[ukuran_plasenta_panjang]"
        id="ukuran_plasenta_panjang"
        onchange="fillthis('ukuran_plasenta_panjang')"
        style="width:50px;">
      x
      <input
        type="text"
        class="input_type"
        name="form_148[ukuran_plasenta_lebar]"
        id="ukuran_plasenta_lebar"
        onchange="fillthis('ukuran_plasenta_lebar')"
        style="width:50px;">
      x
      <input
        type="text"
        class="input_type"
        name="form_148[ukuran_plasenta_tebal]"
        id="ukuran_plasenta_tebal"
        onchange="fillthis('ukuran_plasenta_tebal')"
        style="width:50px;">
      cm
  </td>
</tr>

<tr>
  <td style="vertical-align: top;">Insersio</td>
  <td>
      <input
        type="text"
        class="input_type"
        name="form_148[insersio]"
        id="insersio"
        onchange="fillthis('insersio')"
        style="width:120px;">

      ; robekan
      <input
        type="text"
        class="input_type"
        name="form_148[robekan]"
        id="robekan"
        onchange="fillthis('robekan')"
        style="width:120px;">

      ; kelainan plasenta
      <input
        type="text"
        class="input_type"
        name="form_148[kelainan_plasenta]"
        id="kelainan_plasenta"
        onchange="fillthis('kelainan_plasenta')"
        style="width:180px;">
  </td>
</tr>

<tr>
  <td style="vertical-align: top;">Panjang tali pusat</td>
  <td>
    <!-- Tali Pusat -->
      <input
        type="text"
        class="input_type"
        name="form_148[panjang_tali_pusat]"
        id="panjang_tali_pusat"
        onchange="fillthis('panjang_tali_pusat')"
        style="width:80px;">
      cm ;

      kelainan tali pusat
      <input
        type="text"
        class="input_type"
        name="form_148[kelainan_tali_pusat]"
        id="kelainan_tali_pusat"
        onchange="fillthis('kelainan_tali_pusat')"
        style="width:200px;">
  </td>
</tr>

<tr>
  <td style="vertical-align: top;">Persalinan tanggal</td>
  <td>
    <!-- Waktu Persalinan -->
      <!-- <input
        type="date"
        class="input_type"
        name="form_148[persalinan_tanggal]"
        id="persalinan_tanggal"
        onchange="fillthis('persalinan_tanggal')"
        style="width:140px;">

      jam
      <input
        type="time"
        class="input_type"
        name="form_148[persalinan_jam]"
        id="persalinan_jam"
        onchange="fillthis('persalinan_jam')"
        style="width:110px;">
      WIB -->

      <input type="text" class="input_type date-picker" style="width: 100px"
        data-date-format="dd-mm-yyyy"
        name="form_148[persalinan_tanggal]"
        id="persalinan_tanggal"
        onchange="fillthis('persalinan_tanggal')"
        value="<?= isset($value_form['persalinan_tanggal']) ? $value_form['persalinan_tanggal'] : date('d-m-Y') ?>">

      &nbsp;jam
      <input type="text" class="input_type"
        name="form_148[persalinan_jam]"
        id="persalinan_jam"
        onchange="fillthis('persalinan_jam')"
        value="<?= isset($value_form['persalinan_jam']) ? $value_form['persalinan_jam'] : date('H:i') ?>">

  </td>
</tr>

<tr>
  <td style="vertical-align: top;">Jenis persalinan</td>
  <td>
    <!-- Jenis Persalinan -->
      <input
        type="text"
        class="input_type"
        name="form_148[jenis_pesalinan]"
        id="jenis_pesalinan"
        onchange="fillthis('jenis_pesalinan')"
        style="width:600px;">
  </td>
</tr>
<br>
<br>
<!-- =================== INDIKASI =================== -->
<tr><td colspan="2"><b><u>INDIKASI</u></b></td></tr>

<tr>
  <td>Luka Jalan Lahir</td>
  <td>

    <label style="margin-right:8px;">
      <input
        type="checkbox"
        class="ace"
        name="form_148[luka_jalan_lahir][episiotomi]"
        id="episiotomi"
        onclick="checkthis('episiotomi')"
      >
      <span class="lbl">Episiotomi</span>
    </label>
    <br>

    <label style="margin-right:8px;">
      <input
        type="checkbox"
        class="ace"
        name="form_148[luka_jalan_lahir][ruptura]"
        id="ruptura"
        onclick="checkthis('ruptura')"
      >
      <span class="lbl">Ruptura Perineii tingkat</span>
    </label>

    <input
      type="text"
      class="input_type"
      name="form_148[ruptura_tingkat]"
      id="ruptura_tingkat"
      onchange="fillthis('ruptura_tingkat')"
      style="width:80px"
    >
  </td>
</tr>


<tr>
  <td>Jahitan Jalan Lahir</td>
  <td>
    <input type="text" class="input_type"
      name="form_148[jahitan_jalan_lahir]"
      id="jahitan_jalan_lahir"
      onchange="fillthis('jahitan_jalan_lahir')"
      style="width:60%">
  </td>
</tr>

<tr>
  <td>Lama Persalinan</td>
  <td>
    <input type="text" class="input_type"
      name="form_148[lama_persalinan]"
      id="lama_persalinan"
      onchange="fillthis('lama_persalinan')"
      style="width:120px"> jam ;

    Pendarahan kala II + III:
    <input type="text" class="input_type"
      name="form_148[pendarahan]"
      id="pendarahan"
      onchange="fillthis('pendarahan')"
      style="width:100px"> ml
  </td>
</tr>

<tr>
  <td>Pengobatan Selama Proses Persalinan</td>
  <td>
    <input type="text" class="input_type"
      name="form_148[pengobatan]"
      id="pengobatan"
      onchange="fillthis('pengobatan')"
      style="width:90%">
  </td>
</tr>

<tr>
  <td>Transfusi Darah / Cairan</td>
  <td>
    <input type="text" class="input_type"
      name="form_148[transfusi]"
      id="transfusi"
      onchange="fillthis('transfusi')"
      style="width:90%">
  </td>
</tr>


<!-- =================== PASCA =================== -->
<tr><td colspan="2"><b><u>KEADAAN PASCA PERSALINAN</u></b></td></tr>

<tr>
  <td>Keadaan Umum</td>
  <td>
    <input type="text"
      class="input_type"
      name="form_148[keadaan_umum]"
      id="keadaan_umum"
      onchange="fillthis('keadaan_umum')"
      style="width:80%">
  </td>
</tr>

<tr>
  <td>Tensi / Nadi</td>
  <td>
    <input type="text" class="input_type"
      name="form_148[tensi]"
      id="tensi"
      onchange="fillthis('tensi')"
      style="width:80px"> mmHg

    <input type="text" class="input_type"
      name="form_148[nadi]"
      id="nadi"
      onchange="fillthis('nadi')"
      style="width:80px"> x/menit
  </td>
</tr>

<tr>
  <td>Pernapasan</td>
  <td>
    <input type="text" class="input_type"
      name="form_148[pernapasan]"
      id="pernapasan"
      onchange="fillthis('pernapasan')"
      style="width:80px"> x/menit
  </td>
</tr>

<tr>
  <td>Suhu</td>
  <td>
    <input type="text" class="input_type"
      name="form_148[suhu]"
      id="suhu"
      onchange="fillthis('suhu')"
      style="width:80px"> °C
  </td>
</tr>

<tr>
  <td>Tinggi Fundus Uteri</td>
  <td>
    <input type="text" class="input_type"
      name="form_148[TFU]"
      id="TFU"
      onchange="fillthis('TFU')"
      style="width:120px">
  </td>
</tr>

<tr>
  <td>Kontraksi</td>
  <td>
    <input type="text" class="input_type"
      name="form_148[kontraksi]"
      id="kontraksi"
      onchange="fillthis('kontraksi')"
      style="width:50%">
  </td>
</tr>

<tr>
  <td>Pendarahan Kala IV</td>
  <td>
    <input type="text" class="input_type"
      name="form_148[pendarahan_kala4]"
      id="pendarahan_kala4"
      onchange="fillthis('pendarahan_kala4')"
      style="width:120px"> ml
  </td>
</tr>


</table>

<br><br>
<!-- ----- -->
<!-- TANDA TANGAN -->
<!-- ----- -->
<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <td style="width:33%; text-align:center;">
        Penolong Persalinan,
        <br><br>
        <span class="ttd-btn" data-role="petugas" id="ttd_petugas" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_petugas" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_148[nama_petugas]" id="nama_petugas" placeholder="Nama Dokter/Bidan" style="width:33%; text-align:center;">
      </td>

      <td colspan="2">
      </td>
    </tr>
  </tbody>
</table>
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