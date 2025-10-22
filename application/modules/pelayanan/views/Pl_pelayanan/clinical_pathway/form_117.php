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
      var hiddenInputName = 'form_114[ttd_' + role + ']';
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
<div style="text-align: center; font-size: 18px;">
  <b>CHECKLIST PATIENT SAFETY INSTALASI KAMAR BEDAH</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
    <thead>
  <tr>
    <th style="width: 50%; border: 1px solid black; text-align: left; padding: 6px;">
      <label><span class="lbl">Prosedur Operasi:</span></label><br>
      <input 
        type="text" 
        class="input_type" 
        style="width: 90%;" 
        name="form_117[ket_prosedur_operasi]" 
        id="ket_prosedur_operasi" 
        onchange="fillthis('ket_prosedur_operasi')" 
        placeholder="......................">
    </th>

    <th style="width: 50%; border: 1px solid black; text-align: left; padding: 6px;">
      <label><span class="lbl">Indikasi Operasi:</span></label><br>
      <input 
        type="text" 
        class="input_type" 
        style="width: 90%;" 
        name="form_117[ket_indikasi_operasi]" 
        id="ket_indikasi_operasi" 
        onchange="fillthis('ket_indikasi_operasi')" 
        placeholder="......................">
    </th>
  </tr>

  <tr>
    <th style="width: 50%; border: 1px solid black; text-align: left; padding: 6px;">
      <label><span class="lbl">Kamar Operasi:</span></label><br>
      <input 
        type="text" 
        class="input_type" 
        style="width: 90%;" 
        name="form_117[ket_kamar_operasi]" 
        id="ket_kamar_operasi" 
        onchange="fillthis('ket_kamar_operasi')" 
        placeholder="......................">
    </th>

    <th style="width: 50%; border: 1px solid black; text-align: left; padding: 6px;">
      <label><span class="lbl">Tanggal Operasi:</span></label><br>
      <input 
        type="date" style="width: 150px"
        class="input_type date-picker" data-date-format="yyyy-mm-dd"
        style="width: 90%;" 
        name="form_117[ket_tanggal_operasi]" 
        id="ket_tanggal_operasi" 
        onchange="fillthis('ket_tanggal_operasi')" 
        value="<?php echo isset($value_form['ket_tanggal_operasi'])?$value_form['ket_tanggal_operasi']:date('Y-m-d')?>">
    </th>
  </tr>

    <tr style="background-color: #d3d3d3;">
      <th colspan="2" style="border: 1px solid black; text-align: center;">SIGN IN (Sebelum Induksi Anestesi)</th>
    </tr>
  </thead>
  <tbody>

<!-- SIGN IN (Sebelum Induksi Anestesi) -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Konfirmasi / Verifikasi</b><br>
        <i>Identitas (nama lengkap, tanggal lahir) dan cek gelang pasien</i>
        
        <div class="checkbox">
            <label><input type="checkbox" class="ace" name="form_117[nama_operasi][]" id="nama_operasi" onclick="checkthis('nama_operasi')" value="Nama Operasi">
            <span class="lbl"> Nama Operasi:</span></label>
            <input type="text" class="input_type" style="width:200px;" name="form_117[ket_nama_operasi]" id="ket_nama_operasi" onchange="fillthis('ket_nama_operasi')">
        </div>

        <div class="checkbox">
            <label><input type="checkbox" class="ace" name="form_117[lokasi_operasi][]" id="lokasi_operasi" onclick="checkthis('lokasi_operasi')" value="Lokasi Operasi">
            <span class="lbl"> Lokasi Operasi:</span></label>
            <input type="text" class="input_type" style="width:200px;" name="form_117[ket_lokasi_operasi]" id="ket_lokasi_operasi" onchange="fillthis('ket_lokasi_operasi')">
        </div>

        <div class="checkbox">
            <label><input type="checkbox" class="ace" name="form_117[informed_consent][]" id="informed_consent" onclick="checkthis('informed_consent')" value="Informed Consent">
            <span class="lbl"> Informed Consent:</span></label>
            <input type="text" class="input_type" style="width:200px;" name="form_117[ket_informed_consent]" id="ket_informed_consent" onchange="fillthis('ket_informed_consent')">
        </div>

        <div class="checkbox">
            <label><input type="checkbox" class="ace" name="form_117[nama_operator][]" id="nama_operator" onclick="checkthis('nama_operator')" value="Nama Operator">
            <span class="lbl">  Nama Operator:</span></label>
            <input type="text" class="input_type" style="width:200px;" name="form_117[ket_nama_operator]" id="ket_nama_operator" onchange="fillthis('ket_nama_operator')">
        </div>

        <div class="checkbox">
            <label><input type="checkbox" class="ace" name="form_117[dokter_anestesi][]" id="dokter_anestesi" onclick="checkthis('dokter_anestesi')" value="Dokter Anestesi">
            <span class="lbl"> Dokter Anestesi:</span></label>
            <input type="text" class="input_type" style="width:200px;" name="form_117[ket_dokter_anestesi]" id="ket_dokter_anestesi" onchange="fillthis('ket_dokter_anestesi')">
        </div>
      </td>
    </tr>

 <!-- Menandai Daerah Operasi -->
    <tr>
      <td style="padding: 5px;">
        <b>Menandai Daerah Operasi:</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[daerah_operasi][]" id="daerah_operasi_ya" onclick="checkthis('daerah_operasi_ya')" value="Ya">
            <span class="lbl"> Ya</span>
          </label>

          <label>
            <input type="checkbox" class="ace" name="form_117[daerah_operasi][]" id="daerah_operasi_tidak_perlu" onclick="checkthis('daerah_operasi_tidak_perlu')" value="Tidak Perlu">
            <span class="lbl"> Tidak Perlu</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Mesin Anestesi -->
    <tr>
      <td style="padding: 5px;">
        <b>Apakah mesin Anestesi dan obat-obatan sudah lengkap?</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[anestesi_lengkap][]" id="anestesi_lengkap" onclick="checkthis('anestesi_lengkap')" value="Lengkap">
            <span class="lbl"> Ya</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Kondisi Pasien -->
    <tr>
      <td style="padding: 5px;">
        <b>Apakah pasien memiliki kondisi berikut:</b><br>
        <b>Riwayat Asma:</b>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[asma][]" id="asma_ada" onclick="checkthis('asma_ada')" value="Asma Ada">
            <span class="lbl"> Ada:</span>
          </label>
          <input type="text" class="input_type" style="width:200px;" name="form_117[ket_asma]" id="ket_asma" onchange="fillthis('ket_asma')">

          <label>
            <input type="checkbox" class="ace" name="form_117[asma][]" id="asma_tidak" onclick="checkthis('asma_tidak')" value="Asma Tidak Ada">
            <span class="lbl"> Tidak Ada</span>
          </label>
        </div>

        <b>Risiko kesulitan jalan napas:</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[jalan_napas][]" id="jalan_napas_ada" onclick="checkthis('jalan_napas_ada')" value="Sulit Jalan Napas">
            <span class="lbl"> Ada</span>
          </label>
          <input type="text" class="input_type" style="width:200px;" name="form_117[ket_jalan_napas]" id="ket_jalan_napas" onchange="fillthis('ket_jalan_napas')">
          <label>
            <input type="checkbox" class="ace" name="form_117[jalan_napas][]" id="jalan_napas_tidak" onclick="checkthis('jalan_napas_tidak')" value="Tidak Ada Risiko Napas">
            <span class="lbl"> Tidak Ada</span>
          </label>
        </div>

        <b>Riwayat Alergi:</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[alergi][]" id="alergi_ada" onclick="checkthis('alergi_ada')" value="Alergi Ada">
            <span class="lbl"> Ada</span>
          </label>
          <input type="text" class="input_type" style="width:200px;" name="form_117[ket_alergi]" id="ket_alergi" onchange="fillthis('ket_alergi')">

          <label>
            <input type="checkbox" class="ace" name="form_117[alergi][]" id="alergi_tidak" onclick="checkthis('alergi_tidak')" value="Alergi Tidak Ada">
            <span class="lbl"> Tidak Ada</span>
          </label>
        </div>

        <b>Riwayat Hipertensi:</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[hipertensi][]" id="hipertensi_ada" onclick="checkthis('hipertensi_ada')" value="Hipertensi Ada">
            <span class="lbl"> Ada</span>
          </label>

          <label>
            <input type="checkbox" class="ace" name="form_117[hipertensi][]" id="hipertensi_tidak" onclick="checkthis('hipertensi_tidak')" value="Hipertensi Tidak Ada">
            <span class="lbl"> Tidak Ada</span>
          </label>
        </div>

        <div class="checkbox">
        <label><b>Riwayat Pengobatan:</b></label>
          <input type="text" class="input_type" style="width:200px;" name="form_117[ket_pengobatan]" id="ket_pengobatan" onchange="fillthis('ket_pengobatan')">
        </div>
      </td>
    </tr>

    <!-- Risiko Kehilangan Darah -->
    <tr>
      <td style="padding: 5px;">
        <b>Risiko Kehilangan Darah &gt; 500 cc:</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[darah][]" id="darah_ya" onclick="checkthis('darah_ya')" value="Ya">
            <span class="lbl"> Ya:</span>
          </label>
          <input type="text" class="input_type" style="width:200px;" name="form_117[ket_darah]" id="ket_darah" onchange="fillthis('ket_darah')">
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[darah][]" id="darah_tidak" onclick="checkthis('darah_tidak')" value="Tidak">
            <span class="lbl"> Tidak</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Rencana Implant -->
    <tr>
      <td style="padding: 5px;">
        <b>Rencana Pemasangan Implant:</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[implant][]" id="implant_ada" onclick="checkthis('implant_ada')" value="Implant Ada">
            <span class="lbl"> Ada, keterangan</span>
          </label>
          <input type="text" class="input_type" style="width:200px;" name="form_117[ket_implant]" id="ket_implant" onchange="fillthis('ket_implant')">
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[implant][]" id="implant_tidak" onclick="checkthis('implant_tidak')" value="Implant Tidak Ada">
            <span class="lbl"> Tidak Ada</span>
          </label>
        </div>
      </td>
    </tr>

        <tr>
          <td style="padding: 5px;">Tanggal
            <input type="text" style="width: 100px" class="input_type date-picker" data-date-format="yyyy-mm-dd" name="form_117[tgl_operasi][]" id="tgl_operasi" onchange="fillthis('tgl_operasi');" value="<?php echo isset($value_form['tgl_operasi'])?$value_form['tgl_operasi']:date('Y-m-d')?>">
            Jam Verifikasi
            <input type="text" style="width: 100px" class="input_type" name="form_117[jam_operasi][]" id="jam_operasi" onchange="fillthis('jam_operasi')" value="<?php echo isset($value_form['jam_operasi'])?$value_form['jam_operasi']:date('H:i')?>">
          </td>
        </tr>

  </tbody>
</table>
<!---- END --->

<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <td style="width:33%; text-align:center;">
        NAMA DAN TANDA TANGAN PERAWAT/dr.ANASTESI,
        <br><br>
        <span class="ttd-btn" data-role="petugas" id="ttd_petugas" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_petugas" src="" style="display:none; max-width:250px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_117[nama_petugas]" id="nama_petugas" placeholder="Perawat/dr.Anastesi" style="width:40%; text-align:center;">
      </td>

      <td colspan="2">
      </td>
    </tr>
  </tbody>
</table>
</div>

<!-- TIME OUT (Sebelum Insisi dimulai) -->
<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
    <thead>
<tr>
  <th colspan="2" style="border: 1px solid black; background-color: #d3d3d3; text-align: center;">
    TIME OUT (Sebelum Insisi dimulai)
  </th>
</tr>

<!-- Kelengkapan Tim Operasi -->
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Kelengkapan Tim Operasi :</b><br>
    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_117[kelengkapan_tim][]" id="kelengkapan_lengkap" onclick="checkthis('kelengkapan_lengkap')" value="Lengkap">
        <span class="lbl"> Lengkap</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_117[kelengkapan_tim][]" id="kelengkapan_tidak" onclick="checkthis('kelengkapan_tidak')" value="Tidak Lengkap">
        <span class="lbl"> Tidak Lengkap</span>
      </label>
      <span style="margin-left:10px;">Keterangan:</span>
      <input type="text" class="input_type" style="width:200px;" name="form_117[ket_kelengkapan]" id="ket_kelengkapan" onchange="fillthis('ket_kelengkapan')">
    </div>
  </td>
</tr>

<!-- Menyebutkan Nama dan Peran Tim -->
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Menyebutkan nama dan peran tim operasi</b><br>
    <div class="checkbox" style="padding: 5px;">
      Operator: <input type="text" class="input_type" style="width:200px;" name="form_117[operator]" id="operator" onchange="fillthis('operator')"><br>
      Instrumentator: <input type="text" class="input_type" style="width:200px;" name="form_117[instrumentator]" id="instrumentator" onchange="fillthis('instrumentator')"><br>
      Asisten Operator: <input type="text" class="input_type" style="width:200px;" name="form_117[asisten]" id="asisten" onchange="fillthis('asisten')"><br>
      Dokter Anestesi: <input type="text" class="input_type" style="width:200px;" name="form_117[dokter_anestesi]" id="dokter_anestesi" onchange="fillthis('dokter_anestesi')"><br>
      Sirkuler: <input type="text" class="input_type" style="width:200px;" name="form_117[sirkuler]" id="sirkuler" onchange="fillthis('sirkuler')">
    </div>
  </td>
</tr>

<!-- Membacakan secara Verbal -->
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Membacakan secara Verbal:</b><br>
    <div class="checkbox">
      <label><input type="checkbox" class="ace" name="form_117[verbal][]" id="verbal_tgl_operasi" onclick="checkthis('verbal_tgl_operasi')" value="Tanggal Operasi"><span class="lbl"> Tanggal Operasi</span></label><br>
      <label><input type="checkbox" class="ace" name="form_117[verbal][]" id="verbal_nama_tgl_lahir" onclick="checkthis('verbal_nama_tgl_lahir')" value="Nama Lengkap dan Tgl Lahir Pasien"><span class="lbl"> Nama Lengkap dan Tgl Lahir Pasien</span></label><br>
      <label><input type="checkbox" class="ace" name="form_117[verbal][]" id="verbal_prosedur" onclick="checkthis('verbal_prosedur')" value="Prosedur Operasi"><span class="lbl"> Prosedur Operasi</span></label><br>
      <label><input type="checkbox" class="ace" name="form_117[verbal][]" id="verbal_consent" onclick="checkthis('verbal_consent')" value="Informed Consent Possie Pasien"><span class="lbl"> Informed Consent Possie Pasien</span></label><br>
      <label><input type="checkbox" class="ace" name="form_117[verbal][]" id="verbal_lokasi" onclick="checkthis('verbal_lokasi')" value="Lokasi Operasi"><span class="lbl"> Lokasi Operasi</span></label>
    </div>
  </td>
</tr>

<!-- Mengantisipasi Peristiwa Kritis -->
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Mengantisipasi peristiwa kritis:</b><br>
    <b>Dokter Bedah</b><br>

    Apakah tindakan yang dilakukan berisiko tinggi?
    <label><input type="checkbox" class="ace" name="form_117[resiko_tinggi][]" id="resiko_tinggi_ya" onclick="checkthis('resiko_tinggi_ya')" value="Ya"><span class="lbl"> Ya</span></label>
    <label><input type="checkbox" class="ace" name="form_117[resiko_tinggi][]" id="resiko_tinggi_tidak" onclick="checkthis('resiko_tinggi_tidak')" value="Tidak"><span class="lbl"> Tidak</span></label><br>

    Berapa lama tindakan ini akan dilakukan?
    <input type="text" class="input_type" style="width:100px;" name="form_117[waktu_tindakan]" id="waktu_tindakan" onchange="fillthis('waktu_tindakan')"> jam<br>

    Apakah sudah diantisipasi perdarahan?
    <label><input type="checkbox" class="ace" name="form_117[perdarahan][]" id="perdarahan_ya" onclick="checkthis('perdarahan_ya')" value="Ya"><span class="lbl"> Ya</span></label>
    <label><input type="checkbox" class="ace" name="form_117[perdarahan][]" id="perdarahan_tidak" onclick="checkthis('perdarahan_tidak')" value="Tidak"><span class="lbl"> Tidak</span></label>
  </td>
</tr>

<!-- Dokter Anestesi -->
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Dokter Anestesi</b><br>
    Apakah ada kekhawatiran pada pasien ini?
    <label><input type="checkbox" class="ace" name="form_117[kekhawatiran][]" id="kekhawatiran_ya" onclick="checkthis('kekhawatiran_ya')" value="Ya"><span class="lbl"> Ya</span></label>
    <label><input type="checkbox" class="ace" name="form_117[kekhawatiran][]" id="kekhawatiran_tidak" onclick="checkthis('kekhawatiran_tidak')" value="Tidak"><span class="lbl"> Tidak</span></label><br>
    ASA pasien <input type="text" class="input_type" style="width:250px;" name="form_117[asa_pasien]" id="asa_pasien" onchange="fillthis('asa_pasien')"><br>

    Perlengkapan tambahan yang perlu disediakan (darah)?
    <label><input type="checkbox" class="ace" name="form_117[perlengkapan_tambahan][]" id="perlengkapan_ya" onclick="checkthis('perlengkapan_ya')" value="Ya"><span class="lbl"> Ya</span></label>
    <label><input type="checkbox" class="ace" name="form_117[perlengkapan_tambahan][]" id="perlengkapan_tidak" onclick="checkthis('perlengkapan_tidak')" value="Tidak"><span class="lbl"> Tidak</span></label>
  </td>
</tr>

<!-- Perawat -->
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Perawat</b><br>

    Apakah sudah mengecek sterilitas alat?
    <label><input type="checkbox" class="ace" name="form_117[sterilitas][]" id="steril_ya" onclick="checkthis('steril_ya')" value="Ya"><span class="lbl"> Ya</span></label>
    <label><input type="checkbox" class="ace" name="form_117[sterilitas][]" id="steril_tidak" onclick="checkthis('steril_tidak')" value="Tidak"><span class="lbl"> Tidak</span></label><br>

    Kesiapan peralatan yang perlu diperhatikan?
    <label><input type="checkbox" class="ace" name="form_117[peralatan][]" id="peralatan_ya" onclick="checkthis('peralatan_ya')" value="Ya"><span class="lbl"> Ya</span></label>
    <label><input type="checkbox" class="ace" name="form_117[peralatan][]" id="peralatan_tidak" onclick="checkthis('peralatan_tidak')" value="Tidak"><span class="lbl"> Tidak</span></label>
    <input type="text" class="input_type" style="width:200px;" name="form_117[kesiapan_alat]" id="kesiapan_alat" onchange="fillthis('kesiapan_alat')"><br>

    <b>Foto Radiologi yang penting sudah dipasang?</b>
    <label><input type="checkbox" class="ace" name="form_117[radiologi][]" id="radiologi_ya" onclick="checkthis('radiologi_ya')" value="Ya"><span class="lbl"> Ya</span></label>
    <label><input type="checkbox" class="ace" name="form_117[radiologi][]" id="radiologi_tidak" onclick="checkthis('radiologi_tidak')" value="Tidak"><span class="lbl"> Tidak</span></label><br>

    <b>Antibiotik profilaksis sudah diberikan?</b>
    <label><input type="checkbox" class="ace" name="form_117[antibiotik][]" id="antibiotik_ya" onclick="checkthis('antibiotik_ya')" value="Ya"><span class="lbl"> Ya</span></label>
    <label><input type="checkbox" class="ace" name="form_117[antibiotik][]" id="antibiotik_tidak" onclick="checkthis('antibiotik_tidak')" value="Tidak"><span class="lbl"> Tidak</span></label><br>

    Jika Ya, Jenis: <input type="text" class="input_type" style="width:150px;" name="form_117[jenis_antibiotik]" id="jenis_antibiotik" onchange="fillthis('jenis_antibiotik')">
    Waktu pemberian: <input type="text" class="input_type" style="width:150px;" name="form_117[waktu_antibiotik]" id="waktu_antibiotik" onchange="fillthis('waktu_antibiotik')"><br>
    
    <br>Tanggal/Jam: <input type="text" class="input_type date-picker" data-date-format="yyyy-mm-dd" style="width:120px;" name="form_117[tgl_antibiotik]" id="tgl_antibiotik" onchange="fillthis('tgl_antibiotik')" value="<?php echo date('Y-m-d');?>">
    <input type="text" class="input_type" style="width:100px;" name="form_117[jam_antibiotik]" id="jam_antibiotik" onchange="fillthis('jam_antibiotik')" value="<?php echo date('H:i');?>">
  </td>
</tr>
  </tbody>
</table>

<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <!-- Kolom Operator -->
      <td style="width:50%; text-align:center; border:1px solid #000;">
        NAMA DAN TANDA TANGAN OPERATOR
        <br><br>
        <span class="ttd-btn" data-role="operator" id="ttd_operator" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_operator" src="" style="display:none; max-width:250px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_117[nama_operator_ttd]" id="nama_operator_ttd" placeholder="Nama Operator" style="width:70%; text-align:center;">
      </td>

      <!-- Kolom Perawat -->
      <td style="width:50%; text-align:center; border:1px solid #000;">
        NAMA DAN TANDA TANGAN PERAWAT
        <br><br>
        <span class="ttd-btn" data-role="perawat" id="ttd_perawat" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_perawat" src="" style="display:none; max-width:250px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_117[nama_perawat_ttd]" id="nama_perawat_ttd" placeholder="Nama Perawat" style="width:70%; text-align:center;">
      </td>
    </tr>
  </tbody>
</table>

<!---- END --->

<!-- SIGN OUT (Sebelum Pasien Keluar OK) -->
<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <th colspan="2" style="border: 1px solid black; background-color: #d3d3d3; text-align: center;">
        SIGN OUT (Sebelum Pasien Keluar OK)
      </th>
    </tr>
  </thead>
  <tbody>
    <!-- Verifikasi oleh perawat -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 6px;">
        <b>Secara verbal perawat memastikan:</b><br>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[verifikasi][]" id="tindakan" onclick="checkthis('tindakan')" value="Nama Tindakan">
            <span class="lbl"> Nama Tindakan</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[verifikasi][]" id="kelengkapan" onclick="checkthis('kelengkapan')" value="Kelengkapan Alat">
            <span class="lbl"> Kelengkapan Alat:</span>
          </label><br>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <label><input type="checkbox" class="ace" name="form_117[sub_kelengkapan][]" id="kasa" onclick="checkthis('kasa')" value="Kasa"><span class="lbl"> Kasa</span></label>
          <label><input type="checkbox" class="ace" name="form_117[sub_kelengkapan][]" id="instrumen" onclick="checkthis('instrumen')" value="Instrumen"><span class="lbl"> Instrumen</span></label>
          <label><input type="checkbox" class="ace" name="form_117[sub_kelengkapan][]" id="jarum" onclick="checkthis('jarum')" value="Jarum"><span class="lbl"> Jarum</span></label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[verifikasi][]" id="pelabelan" onclick="checkthis('pelabelan')" value="Pelabelan Specimen">
            <span class="lbl"> Pelabelan specimen (baca specimen dan nama pasien):</span>
          </label><br>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <label><input type="checkbox" class="ace" name="form_117[sub_pelabelan][]" id="pelabelan_ya" onclick="checkthis('pelabelan_ya')" value="Ya"><span class="lbl"> Ya</span></label>
          <label><input type="checkbox" class="ace" name="form_117[sub_pelabelan][]" id="pelabelan_tidak" onclick="checkthis('pelabelan_tidak')" value="Tidak"><span class="lbl"> Tidak</span></label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_117[verifikasi][]" id="masalah_peralatan" onclick="checkthis('masalah_peralatan')" value="Masalah Peralatan">
            <span class="lbl"> Apakah ada masalah peralatan yang perlu disampaikan?</span>
          </label>
          <input type="text" class="input_type" style="width: 300px;" name="form_117[ket_masalah_peralatan]" id="ket_masalah_peralatan" placeholder="Tuliskan keterangan..." onchange="fillthis('ket_masalah_peralatan')">
        </div>
      </td>
    </tr>

    <!-- Dokter Bedah -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 6px;">
        <b>Untuk Dokter Bedah:</b><br>
        <label><input type="checkbox" class="ace" name="form_117[dokter_bedah][]" id="dokter_ya" onclick="checkthis('dokter_ya')" value="Ya"><span class="lbl"> Ya</span></label>
        <label><input type="checkbox" class="ace" name="form_117[dokter_bedah][]" id="dokter_tidak" onclick="checkthis('dokter_tidak')" value="Tidak"><span class="lbl"> Tidak</span></label>
      </td>
    </tr>

    <!-- Jalan napas -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 6px;">
        <b>Jalan napas (Throat Pack):</b><br>
        <label><input type="checkbox" class="ace" name="form_117[jalan_nafas][]" id="nafas_ya" onclick="checkthis('nafas_ya')" value="Ya"><span class="lbl"> Ya</span></label>
        <label><input type="checkbox" class="ace" name="form_117[jalan_nafas][]" id="nafas_tidak" onclick="checkthis('nafas_tidak')" value="Tidak"><span class="lbl"> Tidak</span></label>
      </td>
    </tr>

    <!-- Jaringan atau Cairan Tubuh -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 6px;">
        <b>Jaringan atau Cairan Tubuh:</b><br>
        <label><input type="checkbox" class="ace" name="form_117[jaringan][]" id="jaringan_ada" onclick="checkthis('jaringan_ada')" value="Ada jaringan / cairan tubuh"><span class="lbl"> Ada jaringan / cairan tubuh</span></label><br>
        <label><input type="checkbox" class="ace" name="form_117[jaringan][]" id="jaringan_identitas" onclick="checkthis('jaringan_identitas')" value="Sudah diberi identitas"><span class="lbl"> Sudah diberi identitas</span></label><br>
        <label><input type="checkbox" class="ace" name="form_117[jaringan][]" id="jaringan_tidak" onclick="checkthis('jaringan_tidak')" value="Tidak ada jaringan"><span class="lbl"> Tidak ada jaringan</span></label><br>

        <span>Keterangan:</span><br>
        <textarea class="input_type" name="form_117[ket_jaringan]" id="ket_jaringan" style="width: 98%; height: 50px; resize: none;" placeholder="Tuliskan keterangan tambahan..." onchange="fillthis('ket_jaringan')"></textarea>
      </td>
    </tr>

    <!-- Tanggal dan Jam -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 6px;">
        <b>Tanggal / Jam:</b><br>
        <input type="text" class="input_type date-picker" data-date-format="yyyy-mm-dd"
               style="width:120px;" name="form_117[tgl_signout]" id="tgl_signout"
               onchange="fillthis('tgl_signout')" value="<?php echo date('Y-m-d');?>">
        <input type="text" class="input_type" style="width:100px;"
               name="form_117[jam_signout]" id="jam_signout"
               onchange="fillthis('jam_signout')" value="<?php echo date('H:i');?>">
      </td>
    </tr>
  </tbody>
</table>

<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <!-- Kolom Operator (versi 2) -->
      <td style="width:50%; text-align:center; border:1px solid #000; padding:10px;">
        NAMA DAN TANDA TANGAN OPERATOR
        <br><br>
        <span class="ttd-btn" data-role="operator2" id="ttd_operator2" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_operator2" src="" style="display:none; max-width:250px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_117[nama_operator_ttd2]" id="nama_operator_ttd2" placeholder="Nama Operator" style="width:70%; text-align:center;">
      </td>

      <!-- Kolom Perawat (versi 2) -->
      <td style="width:50%; text-align:center; border:1px solid #000; padding:10px;">
        NAMA DAN TANDA TANGAN PERAWAT
        <br><br>
        <span class="ttd-btn" data-role="perawat2" id="ttd_perawat2" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_perawat2" src="" style="display:none; max-width:250px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_117[nama_perawat_ttd2]" id="nama_perawat_ttd2" placeholder="Nama Perawat" style="width:70%; text-align:center;">
      </td>
    </tr>
  </tbody>
</table>

<!---- END --->


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