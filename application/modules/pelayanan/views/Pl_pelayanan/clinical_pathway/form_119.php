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
      var hiddenInputName = 'form_119[ttd_' + role + ']';
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
<hr><br>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<div style="text-align:center; font-size:18px; line-height:1.5;">
  <b>PEMBERIAN INFORMASI<br>ANESTESI LOKAL</b>
</div>
<br>

<style>
.info-row {
  display: flex;
  margin-bottom: 8px;
  align-items: center;
}
.info-row label:first-child {
  flex: 0 0 30%;
  font-weight: bold;
}
.info-row input[type="text"] {
  flex: 1;
}
.table-info-lokal {
  width: 100%;
  border-collapse: collapse;
  font-size: 12px;
}
.table-info-lokal th, .table-info-lokal td {
  border: 1px solid #000;
  padding: 8px;
  vertical-align: top;
}
.table-info-lokal th {
  background-color: #f2f2f2;
  text-align: center;
}
</style>

<!-- Tabel Identitas -->
<div class="info-row">
  <label>Dokter Pelaksana Tindakan</label> 
  : <input type="text" class="input_type" name="form_119[dokter_anestesi_lokal]" id="dokter_anestesi_lokal" onchange="fillthis('dokter_anestesi_lokal')">
</div>

<div class="info-row">
  <label>Pemberi Informasi</label> 
  : <input type="text" class="input_type" name="form_119[pemberi_informasi_lokal]" id="pemberi_informasi_lokal" onchange="fillthis('pemberi_informasi_lokal')">
</div>

<div class="info-row">
  <label>Penerima Informasi / Pemberi Persetujuan</label> 
  : <input type="text" class="input_type" name="form_119[pemberi_persetujuan_lokal]" id="pemberi_persetujuan_lokal" onchange="fillthis('pemberi_persetujuan_lokal')">
</div>

<!-- Tabel Isi Informasi -->
<table class="table-info-lokal">
  <thead>
    <tr>
      <th width="5%">No</th>
      <th width="20%">Jenis Operasi / Tindakan</th>
      <th width="55%">Isi Informasi</th>
      <th width="20%">Tanda (V)/Paraf</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="text-align:center;">1</td>
      <td>Diagnosis (WD & DD)</td>
      <td><input type="text" class="input_type" name="form_119[isi_diagnosis_lokal]" id="isi_diagnosis_lokal" onchange="fillthis('isi_diagnosis_lokal')" style="width:90%;"></td>
      <td style="text-align:center;">
        <input type="checkbox" class="ace" name="form_119[info_lokal][]" id="diagnosis_lokal" onclick="checkthis('diagnosis_lokal')" value="Diagnosis"><span class="lbl"></span>
      </td>
    </tr>
    <tr>
      <td style="text-align:center;">2</td>
      <td>Dasar Diagnosis</td>
      <td>Anamnesis, Pemeriksaan Fisik dan Penunjang lainnya</td>
      <td style="text-align:center;">
        <input type="checkbox" class="ace" name="form_119[info_lokal][]" id="dasar_diagnosis_lokal" onclick="checkthis('dasar_diagnosis_lokal')" value="Dasar Diagnosis"><span class="lbl"></span>
      </td>
    </tr>
    <tr>
      <td style="text-align:center;">3</td>
      <td>Tindakan Kedokteran</td>
      <td>Anestesi Lokal atau Pembiusan Lokal</td>
      <td style="text-align:center;">
        <input type="checkbox" class="ace" name="form_119[info_lokal][]" id="tindakan_lokal" onclick="checkthis('tindakan_lokal')" value="Tindakan Kedokteran"><span class="lbl"></span>
      </td>
    </tr>
    <tr>
      <td style="text-align:center;">4</td>
      <td>Pengertian</td>
      <td>Anestesi lokal akan membuat pasien terjaga sepanjang operasi, tapi akan mengalami mati rasa di sekitar daerah yang dioperasi.</td>
      <td style="text-align:center;">
        <input type="checkbox" class="ace" name="form_119[info_lokal][]" id="pengertian_lokal" onclick="checkthis('pengertian_lokal')" value="Pengertian"><span class="lbl"></span>
      </td>
    </tr>
    <tr>
      <td style="text-align:center;">5</td>
      <td>Indikasi Tindakan</td>
      <td>
        - Operasi kecil / minor pada bagian tertentu tubuh<br>
        - Berbagai prosedur berkaitan dengan gigi
      </td>
      <td style="text-align:center;">
        <input type="checkbox" class="ace" name="form_119[info_lokal][]" id="indikasi_lokal" onclick="checkthis('indikasi_lokal')" value="Indikasi Tindakan"><span class="lbl"></span>
      </td>
    </tr>
    <tr>
      <td style="text-align:center;">6</td>
      <td>Tata Cara</td>
      <td>
        Dengan suntikan anestesi pada ujung saraf di lokasi yang akan dioperasi, atau Anestesi juga dapat diberikan dalam bentuk salep atau semprotan.
      </td>
      <td style="text-align:center;">
        <input type="checkbox" class="ace" name="form_119[info_lokal][]" id="tatacara_lokal" onclick="checkthis('tatacara_lokal')" value="Tata Cara"><span class="lbl"></span>
      </td>
    </tr>
    <tr>
      <td style="text-align:center;">7</td>
      <td>Tujuan</td>
      <td>Menghilangkan atau mengurangi sensasi nyeri di bagian tubuh tertentu.</td>
      <td style="text-align:center;">
        <input type="checkbox" class="ace" name="form_119[info_lokal][]" id="tujuan_lokal" onclick="checkthis('tujuan_lokal')" value="Tujuan"><span class="lbl"></span>
      </td>
    </tr>
    <tr>
      <td style="text-align:center;">8</td>
      <td>Risiko</td>
      <td>
        - Terasa tebal atau mati rasa sementara<br>
        - Anestesi gagal karena masih nyeri
      </td>
      <td style="text-align:center;">
        <input type="checkbox" class="ace" name="form_119[info_lokal][]" id="risiko_lokal" onclick="checkthis('risiko_lokal')" value="Risiko"><span class="lbl"></span>
      </td>
    </tr>
    <tr>
      <td style="text-align:center;">9</td>
      <td>Komplikasi</td>
      <td>Alergi</td>
      <td style="text-align:center;">
        <input type="checkbox" class="ace" name="form_119[info_lokal][]" id="komplikasi_lokal" onclick="checkthis('komplikasi_lokal')" value="Komplikasi"><span class="lbl"></span>
      </td>
    </tr>
    <tr>
      <td style="text-align:center;">10</td>
      <td>Prognosis</td>
      <td>Baik</td>
      <td style="text-align:center;">
        <input type="checkbox" class="ace" name="form_119[info_lokal][]" id="prognosis_lokal" onclick="checkthis('prognosis_lokal')" value="Prognosis"><span class="lbl"></span>
      </td>
    </tr>
    <tr>
      <td style="text-align:center;">11</td>
      <td>Alternatif & Risiko</td>
      <td>Konsul dokter spesialis anestesi</td>
      <td style="text-align:center;">
        <input type="checkbox" class="ace" name="form_119[info_lokal][]" id="alternatif_lokal" onclick="checkthis('alternatif_lokal')" value="Alternatif & Risiko"><span class="lbl"></span>
      </td>
    </tr>

    <!-- Bagian Pernyataan Dokter -->
    <tr>
      <td colspan="3">
        Dengan ini menyatakan bahwa saya Dokter 
        <input type="text" class="input_type" name="form_119[nama_dokter_menerangkan_lokal]" id="nama_dokter_menerangkan_lokal" onchange="fillthis('nama_dokter_menerangkan_lokal')" style="width:250px;">
        telah menerangkan hal-hal di atas secara benar dan jelas serta memberikan kesempatan untuk bertanya dan / atau diskusi.
      </td>
      <td style="text-align:center;">
        Tanda Tangan<br><br>
        <span class="ttd-btn" data-role="pemberi_info_lokal" id="ttd_pemberi_info_lokal" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span><br>
        <img id="img_ttd_pemberi_info_lokal" src="" style="display:none; max-width:150px; max-height:40px;">
        <input type="hidden" name="form_119[ttd_pemberi_info_lokal]"><br>
        <input type="text" class="input_type" name="form_119[nama_ttd_pemberi_info_lokal]" id="nama_ttd_pemberi_info_lokal" placeholder="Nama" style="width:90%; text-align:center;">
      </td>
    </tr>

    <!-- Bagian Pernyataan Pasien -->
    <tr>
      <td colspan="3">
        Dengan ini menyatakan bahwa saya Pasien / Keluarga Pasien 
        <input type="text" class="input_type" name="form_119[nama_keluarga_lokal]" id="nama_keluarga_lokal" onchange="fillthis('nama_keluarga_lokal')" style="width:250px;">
        telah menerima informasi sebagaimana di atas yang saya beri tanda / paraf di kolom kanannya, dan telah memahaminya serta telah diberikan kesempatan bertanya dan pertanyaan saya telah diberikan jawaban yang memuaskan saya.
      </td>
      <td style="text-align:center;">
        Tanda Tangan<br><br>
        <span class="ttd-btn" data-role="penerima_info_lokal" id="ttd_penerima_info_lokal" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span><br>
        <img id="img_ttd_penerima_info_lokal" src="" style="display:none; max-width:150px; max-height:40px;">
        <input type="hidden" name="form_119[ttd_penerima_info_lokal]"><br>
        <input type="text" class="input_type" name="form_119[nama_ttd_penerima_info_lokal]" id="nama_ttd_penerima_info_lokal" placeholder="Nama" style="width:90%; text-align:center;">
      </td>
    </tr>

    <tr>
      <td colspan="4" style="font-size:11px;text-align:center;">
        * Bila pasien tidak kompeten atau tidak mau menerima informasi, maka penerima informasi adalah wali atau keluarga terdekat.
      </td>
    </tr>
  </tbody>
</table>


<hr><br>
<div style="text-align:center; font-size:14px;">
  <b>PERNYATAAN PERSETUJUAN / PENOLAKAN TINDAKAN KEDOKTERAN</b>
</div>
<br>

<div class="form-container" style="width:100%; font-size:12px;">

  <p>Yang bertanda tangan di bawah ini, saya :</p>

  <!-- Data Pasien -->
  <div class="info-row" style="margin-bottom:5px;">
    <label style="flex:0 0 10%;">Nama</label> :
    <input type="text" class="input_type" name="form_119[nama_pasien_persetujuan]" id="nama_pasien_persetujuan" onchange="fillthis('nama_pasien_persetujuan')" style="flex:0 0 35%;">
    <label style="flex:0 0 15%; text-align:right;">Tanggal Lahir</label> :
    <input type="text" class="input_type" name="form_119[tgl_lahir_persetujuan]" id="tgl_lahir_persetujuan" onchange="fillthis('tgl_lahir_persetujuan')" style="flex:1;">
  </div>

  <div class="info-row" style="margin-bottom:5px;">
    <label style="flex:0 0 10%;">Jenis Kelamin*)</label> :
    <div style="display:flex; flex-wrap:wrap; flex:1;">
      <label style="margin-right:10px;">
        <input type="checkbox" class="ace" name="form_119[jk_pasien][]" id="jk_pasien_l" onclick="checkthis('jk_pasien_l')" value="Laki-laki">
        <span class="lbl"> Laki-laki</span>
        </label>
        <label>
        <input type="checkbox" class="ace" name="form_119[jk_pasien][]" id="jk_pasien_p" onclick="checkthis('jk_pasien_p')" value="Perempuan">
        <span class="lbl"> Perempuan</span>
      </label>
    </div>
  </div>

  <div class="info-row" style="margin-bottom:5px;">
    <label style="flex:0 0 10%;">Alamat</label> :
    <input type="text" class="input_type" name="form_119[alamat_pasien_persetujuan]" id="alamat_pasien_persetujuan" onchange="fillthis('alamat_pasien_persetujuan')" style="flex:0 0 35%;">
    <label style="flex:0 0 15%; text-align:right;">Telepon</label> :
    <input type="text" class="input_type" name="form_119[telepon_pasien]" id="telepon_pasien" onchange="fillthis('telepon_pasien')" style="flex:1;">
  </div>

  <div class="info-row" style="margin-bottom:10px;">
    <label style="flex:0 0 10%;">No. KTP/SIM</label> :
    <input type="text" class="input_type" name="form_119[no_ktp_pasien]" id="no_ktp_pasien" onchange="fillthis('no_ktp_pasien')" style="flex:0 0 35%;">
  </div>

  <!-- Hubungan dengan pasien -->
  <div style="margin-bottom:10px;">
    <label style="display:block;">
      Dengan ini menyatakan
      <label>
          <input type="checkbox" class="ace" name="form_119[persetujuan_pasien][]" id="pasien_setuju" onclick="checkthis('pasien_setuju')" value="Persetujuan">
          <span class="lbl"> <b>PERSETUJUAN / </b></span>
          </label>
          <label>
          <input type="checkbox" class="ace" name="form_119[persetujuan_pasien][]" id="pasien_penolakan" onclick="checkthis('pasien_penolakan')" value="Penolakan">
          <span class="lbl"> <b>PENOLAKAN</b></span>
        </label>untuk dilakukan tindakan berupa: Anestesi Lokal, terhadap:
    </label>

    <div class="info-row" style="margin-top:5px;">
      <div>
        <label>
          <input type="checkbox" class="ace" name="form_119[hubungan_pasien][]" id="hub_diri_sendiri" onclick="checkthis('hub_diri_sendiri')" value="diri saya sendiri">
          <span class="lbl"> diri saya sendiri</span>
        </label>
       <label>
          <input type="checkbox" class="ace" name="form_119[hubungan_pasien][]" id="hub_istri" onclick="checkthis('hub_istri')" value="istri">
          <span class="lbl"> istri</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_119[hubungan_pasien][]" id="hub_suami" onclick="checkthis('hub_suami')" value="suami">
          <span class="lbl"> suami</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_119[hubungan_pasien][]" id="hub_anak" onclick="checkthis('hub_anak')" value="anak">
          <span class="lbl"> anak</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_119[hubungan_pasien][]" id="hub_ayah" onclick="checkthis('hub_ayah')" value="ayah">
          <span class="lbl"> ayah</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_119[hubungan_pasien][]" id="hub_ibu" onclick="checkthis('hub_ibu')" value="ibu">
          <span class="lbl"> ibu</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_119[hubungan_pasien][]" id="hub_lainnya" onclick="checkthis('hub_lainnya')" value="hub_lainnya">
          <span class="lbl"> lainnya:</span>
          <input type="text" class="input_type" name="form_119[hubungan_pasien_lain]" id="hubungan_pasien_lain" onchange="fillthis('hubungan_pasien_lain')" style="width:150px;">
          saya*) dengan:
        </label>
      </div>
    </div>
  </div>

  <!-- Data Wali -->
  <div class="info-row" style="margin-bottom:5px;">
    <label style="flex:0 0 10%;">Nama</label> :
    <input type="text" class="input_type" name="form_119[nama_wali]" id="nama_wali" onchange="fillthis('nama_wali')" style="flex:0 0 35%;">
    <label style="flex:0 0 15%; text-align:right;">Tanggal Lahir</label> :
    <input type="text" class="input_type" name="form_119[tgl_lahir_wali]" id="tgl_lahir_wali" onchange="fillthis('tgl_lahir_wali')" style="flex:1;">
  </div>

  <div class="info-row" style="margin-bottom:5px;">
    <label style="flex:0 0 10%;">Jenis Kelamin*)</label> :
    <div style="display:flex; flex-wrap:wrap; flex:1;">
      <label style="margin-right:10px;">
        <input type="checkbox" class="ace" name="form_119[jk_wali][]" id="jk_wali_l" onclick="checkthis('jk_wali_l')" value="Laki-laki">
        <span class="lbl"> Laki-laki</span>
        </label>
        <label>
        <input type="checkbox" class="ace" name="form_119[jk_wali][]" id="jk_wali_p" onclick="checkthis('jk_wali_p')" value="Perempuan">
        <span class="lbl"> Perempuan</span>
      </label>
    </div>
  </div>

  <div class="info-row" style="margin-bottom:5px;">
    <label style="flex:0 0 10%;">Alamat</label> :
    <input type="text" class="input_type" name="form_119[alamat_wali]" id="alamat_wali" onchange="fillthis('alamat_wali')" style="flex:0 0 35%;">
    <label style="flex:0 0 15%; text-align:right;">Telepon</label> :
    <input type="text" class="input_type" name="form_119[telepon_wali]" id="telepon_wali" onchange="fillthis('telepon_wali')" style="flex:1;">
  </div>

  <div class="info-row" style="margin-bottom:5px;">
    <label style="flex:0 0 10%;">No. RM</label> :
    <span class="lbl"> <?php echo $data_pasien->no_mr ?> </span>
    <label style="flex:0 0 15%; text-align:right;">Dirawat Kelas / Ruang</label> :
    <input type="text" class="input_type" name="form_119[kelas_dirawat]" id="kelas_dirawat" onchange="fillthis('kelas_dirawat')" style="flex:1;">
  </div>

  <!-- Pernyataan -->
  <p>
    Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di atas kepada saya,
    termasuk risiko dan komplikasi yang mungkin timbul.
  </p>
  <p>
    Saya juga menyadari bahwa karena ilmu kedokteran bukanlah ilmu pasti,
    maka keberhasilan tindakan kedokteran bukanlah keniscayaan, melainkan sangat bergantung kepada Izin Tuhan Yang Maha Esa.
  </p>

  <div class="info-row" style="margin-bottom:20px;">
    <label style="flex:0 0 20%;">Jakarta, tanggal</label>
    <input type="text" class="input_type date-picker" name="form_119[tgl_pernyataan]" id="tgl_pernyataan" onchange="fillthis('tgl_pernyataan')" style="flex:0 0 15%;">
    <label style="flex:0 0 5%; text-align:right;">pukul</label>
    <input type="text" class="input_type" name="form_119[pukul_pernyataan]" id="pukul_pernyataan" onchange="fillthis('pukul_pernyataan')" style="flex:0 0 10%;">
  </div>

</div>

<!-- Tabel Tanda Tangan -->
<table class="table" style="width:100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <td style="width:25%; text-align:center;">
        Yang Menyatakan,<br><br>
        <span class="ttd-btn" data-role="dokter_pelaksana" id="ttd_dokter_pelaksana" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span><br>
        <img id="img_ttd_dokter_pelaksana" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;"><br><br>
        <input type="text" class="input_type" name="form_119[nama_dokter_pelaksana]" id="nama_dokter_pelaksana" placeholder="Nama Jelas" style="width:90%; text-align:center;">
        <input type="hidden" name="form_119[ttd_dokter_pelaksana]">
      </td>

      <td style="width:25%; text-align:center;"></td>

      <td style="width:25%; text-align:center;">
        Saksi 1<br><br>
        <span class="ttd-btn" data-role="saksi1" id="ttd_saksi1" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span><br>
        <img id="img_ttd_saksi1" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;"><br><br>
        <input type="text" class="input_type" name="form_119[nama_saksi1]" id="nama_saksi1" placeholder="Nama Jelas" style="width:90%; text-align:center;">
        <input type="hidden" name="form_119[ttd_saksi1]">
        <center><span>(Pihak Rumah Sakit)</span></center>
      </td>

      <td style="width:25%; text-align:center;">
        Saksi 2<br><br>
        <span class="ttd-btn" data-role="saksi2" id="ttd_saksi2" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span><br>
        <img id="img_ttd_saksi2" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;"><br><br>
        <input type="text" class="input_type" name="form_119[nama_saksi2]" id="nama_saksi2" placeholder="Nama Jelas" style="width:90%; text-align:center;">
        <input type="hidden" name="form_119[ttd_saksi2]">
        <center><span>(Pihak Pasien)</span></center>
      </td>
    </tr>
  </tbody>
</table>

<!-- Modal Tanda Tangan -->
<div class="modal fade" id="ttdModal" tabindex="-1" role="dialog" aria-labelledby="ttdModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="ttdModalLabel" style="color:white;">Tanda Tangan Digital</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" style="text-align:center;">
        <canvas id="ttd-canvas" style="border:1px solid #ccc; touch-action:none;" width="350" height="120"></canvas><br>
        <button type="button" class="btn btn-warning btn-sm" id="clear-ttd">Bersihkan</button>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-xs btn-primary" id="save-ttd">Simpan</button>
      </div>
    </div>
  </div>
</div>
