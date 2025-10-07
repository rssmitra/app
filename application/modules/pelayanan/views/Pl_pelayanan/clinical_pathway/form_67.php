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
      var hiddenInputName = 'form_67[ttd_' + role + ']';
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

<div style="text-align: center; font-size: 18px; line-height: 1.5;"><b>PEMBERIAN INFORMASI<br>SEDASI / ANESTESI UMUM</b></div>
<br>
<style>
/* Custom styles to mimic the form's structure */
.info-row {
  display: flex;
  margin-bottom: 5px;
  align-items: center;
}
.info-row label:first-child {
  flex: 0 0 20%; /* Lebar label pertama */
}
.info-row input[type="text"] {
  flex: 1;
}
.table-info-spinal th, .table-info-spinal td {
  padding: 8px;
  border: 1px solid #000;
  vertical-align: top;
}
</style>

<div class="info-row" style="margin-bottom: 10px;">
        <label>Dokter Pelaksana Tindakan</label>
        : <input type="text" class="input_type" name="form_67[dokter_infosedasi]" id="dokter_infosedasi" onchange="fillthis('dokter_infosedasi')" style="flex: 1;">
    </div>
    <div class="info-row" style="margin-bottom: 10px;">
        <label>Pemberi Informasi</label>
        : <input type="text" class="input_type" name="form_67[pemberi_informasi_sedasi]" id="pemberi_informasi_sedasi" onchange="fillthis('pemberi_informasi_sedasi')" style="flex: 1;">
    </div>
    <div class="info-row" style="margin-bottom: 10px;">
        <label>Penerima Informasi / Pemberi Persetujuan</label>
        : <input type="text" class="input_type" name="form_67[pemberi_persetujuan_sedasi]" id="pemberi_persetujuan_sedasi" onchange="fillthis('pemberi_persetujuan_sedasi')" style="flex: 1;">
    </div>
</div>

<table class="table-info-spinal" style="width: 100%; border-collapse: collapse; font-size: 12px;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th width="5%" style="text-align: center;">No</th>
            <th width="20%" style="text-align: center;">Jenis Operasi / Tindakan</th>
            <th width="55%" style="text-align: center;">Isi Informasi</th>
            <th width="20%" style="text-align: center;">Tanda (V)/Paraf</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align: center;">1</td>
            <td>Diagnosis (WD & DD)</td>
            <td><input type="text" class="input_type" name="form_67[isi_diagnosis_sedasi]" id="isi_diagnosis_sedasi" onchange="fillthis('isi_diagnosis_sedasi')" style="width: 90%;"></td>
            <td style="text-align: center;">
                <input type="checkbox" class="ace" name="form_67[info_sedasi][]" id="diag_infosedasi" onclick="checkthis('diag_infosedasi')" value="Diagnosis">
                <span class="lbl"></span>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">2</td>
            <td>Dasar Diagnosis</td>
            <td>Anamnesis, Pemeriksaan Fisik dan Penunjang lainnya <input type="text" class="input_type" name="form_67[dasar_diagnosis]" id="dasar_diagnosis" onchange="fillthis('dasar_diagnosis')" style="width: 90%;"></td>
            <td style="text-align: center;">
                <input type="checkbox" class="ace" name="form_67[info_anestesi][]" id="diag_anamnesis" onclick="checkthis('diag_anamnesis')" value="Anamnesis, Pemeriksaan Fisik dan Penunjang lainnya">
                <span class="lbl"></span>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">3</td>
            <td>Tindakan Kedokteran</td>
            <td>Sedasi / Anestesi Umum</td>
            <td style="text-align: center;">
                <input type="checkbox" class="ace" name="form_67[info_sedasi][]" id="tindakan_kedokteran_sedasi" onclick="checkthis('tindakan_kedokteran_sedasi')" value="Tindakan Kedokteran">
                <span class="lbl"></span>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">4</td>
            <td>Pengertian</td>
            <td>Sedasi adalah Pembiusan umum dimana pasien menjadi tidak sadar & tidak merasakan apa-apa. Kelebihan Anestesi Umum:
            <br>
                1. Pasien sudah tidak sadar dari awal operasi, tidak sakit dan relaksasi.<br>
                2. Lama pembiusan dapat disamakan dengan lamanya operasi.<br>
                3. Kedalaman anestesi hypnosis, analgesia dan relaksasi dapat diatur sesuai kebutuhan,
            <br>Kekurangan Anestesi Umum :<br>
                1. Obat bius yang diberikan berefek ke seluruh tubuh termasuk ke dalam aliran pembuluh janin dalam kandungan.<br>
                2. Pemulihan lebih lama.
            <br></td>
            <td style="text-align: center;">
                <input type="checkbox" class="ace" name="form_67[info_sedasi][]" id="pengertian_sedasi" onclick="checkthis('pengertian_sedasi')" value="Pengertian">
                <span class="lbl"></span>
            </td>
            
        </tr>
        <tr>
            <td style="text-align: center;">5</td>
            <td>Indikasi Tindakan</td>
            <td>Tindakan operasi sedang dan besar / khusus</td>
            <td style="text-align: center;">
                <input type="checkbox" class="ace" name="form_67[info_sedasi][]" id="indikasi_tindakan_sedasi" onclick="checkthis('indikasi_tindakan_sedasi')" value="Indikasi Tindakan">
                <span class="lbl"></span>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">6</td>
            <td>Tata Cara</td>
            <td>
                Suntikan ke dalam pembuluh darah atau diberikan dengan cara dihirup melalui sungkup muka (pada bayi dan anak-anak).<br>
                Pemasangan alat / pipa pernapasan khusus melalui mulut atau hidung ke tenggorokan.
            </td>
            <td style="text-align: center;">
                <input type="checkbox" class="ace" name="form_67[info_sedasi][]" id="tata_cara_sedasi" onclick="checkthis('tata_cara_sedasi')" value="Tata Cara">
                <span class="lbl"></span>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">7</td>
            <td>Tujuan</td>
            <td>Menyebabkan pasien tidak sadar, menghilangkan rasa nyeri dan melemaskan otot-otot yang bersifat sementara (ireversibel).</td>
            <td style="text-align: center;">
                <input type="checkbox" class="ace" name="form_67[info_sedasi][]" id="tujuan_sedasi" onclick="checkthis('tujuan_sedasi')" value="Tujuan">
                <span class="lbl"></span>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">8</td>
            <td>Risiko</td>
            <td>
                1. Pemasangan pipa pernafasan dapat mencederai gusi dan gigi.<br>
                2. Pasien yang tidak puasa, bisa terjadi aspirasi yaitu masuknya isi lambung ke dalam jalan nafas / paru-paru.
            </td>
            <td style="text-align: center;">
                <input type="checkbox" class="ace" name="form_67[info_sedasi][]" id="risiko_sedasi" onclick="checkthis('risiko_sedasi')" value="Risiko">
                <span class="lbl"></span>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">9</td>
            <td>Komplikasi / Efek Samping</td>
            <td>
                1. Mual, muntah, menggigil, pusing, mengantuk, yang bisa diatasi dengan obat-obatan. <br>
                2. Nyeri tenggorokan, batuk-batuk karena pemasangan pipa pernafasan yang bersifat sementara dan bisa diatasi dengan obat-obatan. <br>
                3. Kesulitan pemasangan pipa pernafasan yang tidak dapat diduga sebelumnya karena kelainan anatomi jalan nafas dan kekuatan leher.<br>
                4. Spasme laring (kejang pita suara), spasme bronkus (kejang jalan nafas bawah) dari ringan hingga berat yang bisa menyebabkan henti jantung.
            </td>
            <td style="text-align: center;">
                <input type="checkbox" class="ace" name="form_67[info_sedasi][]" id="komplikasi_sedasi" onclick="checkthis('komplikasi_sedasi')" value="Komplikasi">
                <span class="lbl"></span>
            </td>
        </tr>
        <tr>
            <td colspan="3">Dengan ini menyatakan bahwa saya Dokter <input type="text" class="input_type" name="form_67[nama_dokter_menerangkan]" id="nama_dokter_menerangkan" onchange="fillthis('nama_dokter_menerangkan')" style="width: 250px;"> telah menerangkan hal-hal di atas secara benar dan jelas serta memberikan kesempatan untuk bertanya dan / atau diskusi.</td>
            <td style="width:33%; text-align:center;">
        Tanda Tangan
        <br><br>
        <span class="ttd-btn" data-role="pemberi_info_sedasi" id="ttd_pemberi_info_sedasi" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_pemberi_info_sedasi" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <input type="hidden" name="form_67[ttd_pemberi_info_sedasi]">
                <br><br>
        <input type="text" class="input_type" name="form_67[nama_ttd_pemberi_info_sedasi]" id="nama_ttd_pemberi_info_sedasi" placeholder="Nama" style="width:90%; text-align:center;">
      </td><br><br>
        </tr>
        <tr>
            <td colspan="3">Dengan ini menyatakan bahwa saya Keluarga Pasien <input type="text" class="input_type" name="form_67[nama_keluarga_pasien]" id="nama_keluarga_pasien" onchange="fillthis('nama_keluarga_pasien')" style="width: 250px;"> telah menerima informasi sebagaimana di atas yang saya beri tanda / paraf di kolom kanannya, dan telah memahaminya serta telah diberikan kesempatan bertanya dan pertanyaan saya telah diberikan jawaban yang memuaskan saya.</td>
            <td style="width:33%; text-align:center;">
        Tanda Tangan
        <br><br>
        <span class="ttd-btn" data-role="penerima_info_sedasi" id="ttd_penerima_info_sedasi" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_penerima_info_sedasi" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <input type="hidden" name="form_67[ttd_penerima_info_sedasi]">
        <input type="text" class="input_type" name="form_67[nama_ttd_penerima_info_sedasi]" id="nama_ttd_penerima_info_sedasi" placeholder="Nama" style="width:90%; text-align:center;">
      </td><br><br>
        </tr>
        <tr>
            <td colspan="4" style="font-size: 11px;">* Bila pasien tidak kompeten atau tidak mau menerima informasi, maka penerima informasi adalah wali atau keluarga terdekat.</td>
        </tr>
    </tbody>
</table>

<br>
<div style="text-align: center; font-size: 14px;"><b>PERNYATAAN PERSETUJUAN / PENOLAKAN TINDAKAN KEDOKTERAN</b></div>
<br>

<div class="form-container" style="display: block; width: 100%; font-size: 12px;">
    <p>Yang bertanda tangan di bawah ini, saya :</p>
    
    <div class="info-row" style="margin-bottom: 5px;">
        
    </div>


    <div class="info-row" style="margin-bottom: 5px;">
        <label style="flex: 0 0 10%;">Nama</label>
        : <input type="text" class="input_type" name="form_67[nama_pasien_persetujuan]" id="nama_pasien_persetujuan" onchange="fillthis('nama_pasien_persetujuan')" style="flex: 0 0 35%;">
        <label style="flex: 0 0 15%; text-align: right;">Tanggal Lahir</label>
        : <input type="text" class="input_type" name="form_67[tgl_lahir_persetujuan]" id="tgl_lahir_persetujuan" onchange="fillthis('tgl_lahir_persetujuan')" style="flex: 1;">
    </div>

    <div class="info-row" style="margin-bottom: 5px;">
        <label style="flex: 0 0 10%;">Jenis Kelamin*)</label>
        : 
        <div style="display: flex; flex-wrap: wrap; flex: 1;">
            <label style="margin-right: 10px;">
                <input type="checkbox" class="ace" name="form_67[jk_pasien][]" id="jk_pasien_l" onclick="checkthis('jk_pasien_l')" value="Laki-laki">
                <span class="lbl"> Laki-laki</span>
            </label>
            <label>
                <input type="checkbox" class="ace" name="form_67[jk_pasien][]" id="jk_pasien_p" onclick="checkthis('jk_pasien_p')" value="Perempuan">
                <span class="lbl"> Perempuan</span>
            </label>
        </div>
    </div>


    <div class="info-row" style="margin-bottom: 5px;">
        <label style="flex: 0 0 10%;">Alamat</label>
        : <input type="text" class="input_type" name="form_67[alamat_pasien_persetujuan]" id="alamat_pasien_persetujuan" onchange="fillthis('alamat_pasien_persetujuan')" style="flex: 0 0 35%;">
        <label style="flex: 0 0 15%; text-align: right;">Telepon</label>
        : <input type="text" class="input_type" name="form_67[telepon_pasien]" id="telepon_pasien" onchange="fillthis('telepon_pasien')" style="flex: 1;">
    </div>
    
    <div class="info-row" style="margin-bottom: 10px;">
        <label style="flex: 0 0 10%;">No. KTP/SIM</label>
        : <input type="text" class="input_type" name="form_67[no_ktp_pasien]" id="no_ktp_pasien" onchange="fillthis('no_ktp_pasien')" style="flex: 0 0 35%;">
    </div>

    <div style="margin-bottom: 10px;">
        <label style="display: block;">Dengan ini menyatakan <b>PERSETUJUAN</b> untuk dilakukan tindakan berupa: Sediasi / Anestesi Umum, terhadap:</label>
        
        <div class="info-row" style="margin-top: 5px;">
            <div style="display: flex; flex-wrap: wrap; flex: 1;">
                <label style="margin-right: 15px;">
                    <input type="checkbox" class="ace" name="form_67[hubungan_pasien][]" id="hub_diri_sendiri" onclick="checkthis('hub_diri_sendiri')" value="diri saya sendiri">
                    <span class="lbl"> diri saya sendiri</span>
                </label>
                <label style="margin-right: 15px;">
                    <input type="checkbox" class="ace" name="form_67[hubungan_pasien][]" id="hub_istri" onclick="checkthis('hub_istri')" value="istri">
                    <span class="lbl"> istri</span>
                </label>
                <label style="margin-right: 15px;">
                    <input type="checkbox" class="ace" name="form_67[hubungan_pasien][]" id="hub_suami" onclick="checkthis('hub_suami')" value="suami">
                    <span class="lbl"> suami</span>
                </label>
                <label style="margin-right: 15px;">
                    <input type="checkbox" class="ace" name="form_67[hubungan_pasien][]" id="hub_anak" onclick="checkthis('hub_anak')" value="anak">
                    <span class="lbl"> anak</span>
                </label>
                <label style="margin-right: 15px;">
                    <input type="checkbox" class="ace" name="form_67[hubungan_pasien][]" id="hub_ayah" onclick="checkthis('hub_ayah')" value="ayah">
                    <span class="lbl"> ayah</span>
                </label>
                <label style="margin-right: 15px;">
                    <input type="checkbox" class="ace" name="form_67[hubungan_pasien][]" id="hub_ibu" onclick="checkthis('hub_ibu')" value="ibu">
                    <span class="lbl"> ibu</span>
                </label>
                <!-- <label>
                    <span class="lbl">lainnya:</span>
                    <input type="text" class="input_type" name="form_67[hubungan_pasien_lain]" id="hubungan_pasien_lain" onchange="fillthis('hubungan_pasien_lain')" style="width: 150px;"> saya*) dengan:
                </label> -->
                <label style="margin-right: 15px;">
                    <input type="checkbox" class="ace" name="form_67[hubungan_pasien][]" id="hub_lainnya" onclick="checkthis('hub_lainnya')" value="hub_lainnya">
                    <span class="lbl">lainnya:</span>
                    <input type="text" class="input_type" name="form_67[hubungan_pasien_lain]" id="hubungan_pasien_lain" onchange="fillthis('hubungan_pasien_lain')" style="width: 150px;"> saya*) dengan:
                </label>
                <!-- <label>
                    <span class="lbl">lainnya:</span>
                    <input type="checkbox" class="ace" name="form_67[hubungan_pasien][]" id="hub_pasien_lain" onclick="checkthis('hub_pasien_lain')" value="hub_lain">
                    <input type="text" class="input_type" name="form_67[hubungan_pasien_lain]" id="hubungan_pasien_lain" onchange="fillthis('hubungan_pasien_lain')" style="width: 150px;"> saya*) dengan:
                </label> -->
            </div>
        </div>
    </div>
    <!-- bikin baru-->
    
     <div class="info-row" style="margin-bottom: 5px;">
        <label style="flex: 0 0 10%;">Nama</label>
        : <input type="text" class="input_type" name="form_67[nama_wali]" id="nama_wali" onchange="fillthis('nama_wali')" style="flex: 0 0 35%;">
        <label style="flex: 0 0 15%; text-align: right;">Tanggal Lahir</label>
        : <input type="text" class="input_type" name="form_67[tgl_lahir_wali]" id="tgl_lahir_wali" onchange="fillthis('tgl_lahir_wali')" style="flex: 1;">
    </div>

    <div class="info-row" style="margin-bottom: 5px;">
        <label style="flex: 0 0 10%;">Jenis Kelamin*)</label>
        : 
        <div style="display: flex; flex-wrap: wrap; flex: 1;">
            <label style="margin-right: 10px;">
                <input type="checkbox" class="ace" name="form_67[jk_wali][]" id="jk_wali_l" onclick="checkthis('jk_wali_l')" value="Laki-laki">
                <span class="lbl"> Laki-laki</span>
            </label>
            <label>
                <input type="checkbox" class="ace" name="form_67[jk_wali][]" id="jk_wali_p" onclick="checkthis('jk_wali_p')" value="Perempuan">
                <span class="lbl"> Perempuan</span>
            </label>
        </div>
    </div>

    <div class="info-row" style="margin-bottom: 5px;">
        <label style="flex: 0 0 10%;">Alamat</label>
        : <input type="text" class="input_type" name="form_67[alamat_wali]" id="alamat_wali" onchange="fillthis('alamat_wali')" style="flex: 0 0 35%;">
        <label style="flex: 0 0 15%; text-align: right;">Telepon</label>
        : <input type="text" class="input_type" name="form_67[telepon_wali]" id="telepon_wali" onchange="fillthis('telepon_wali')" style="flex: 1;">
    </div>
    <!-- error -->
    <div class="info-row" style="margin-bottom: 5px;">
        <label style="flex: 0 0 10%;">No. RM</label>
        :  <span class="lbl"> <?php echo $data_pasien->no_mr?></span>
        <label style="flex: 0 0 15%; text-align: right;">Dirawat Kelas / Ruang</label>
        : <input type="text" class="input_type" name="form_67[kelas_dirawat]" id="kelas_dirawat" onchange="fillthis('kelas_dirawat')" style="flex: 1;">
    </div>

 <!-- end bikin baru-->

    <p>Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di atas kepada saya, termasuk risiko dan komplikasi yang mungkin timbul.</p>
    <p>Saya juga menyadari bahwa karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan tindakan kedokteran bukanlah keniscahyaan, melainkan sangat bergantung kepada Izin Tuhan Yang Maha Esa.</p>

    <div class="info-row" style="margin-bottom: 20px;">
        <label style="flex: 0 0 20%;">Jakarta, tanggal</label>
        <input type="text" class="input_type date-picker" name="form_67[tgl_pernyataan]" id="tgl_pernyataan" onchange="fillthis('tgl_pernyataan')" style="flex: 0 0 15%;">
        <label style="flex: 0 0 5%; text-align: right;">pukul</label>
        <input type="text" class="input_type" name="form_67[pukul_pernyataan]" id="pukul_pernyataan" onchange="fillthis('pukul_pernyataan')" style="flex: 0 0 10%;">
    </div>
</div>

    <table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
        <tbody>
            <tr>
                <td style="width: 25%; text-align:center;">
                    Yang Menyatakan,
                    <br><br>
                    <span class="ttd-btn" data-role="dokter_pelaksana" id="ttd_dokter_pelaksana" style="cursor: pointer;">
                        <i class="fa fa-pencil blue"></i>
                    </span>
                    <br>
                    <img id="img_ttd_dokter_pelaksana" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
                    <br><br>
                    <input type="text" class="input_type" name="form_67[nama_dokter_pelaksana]" id="nama_dokter_pelaksana" placeholder="Nama Jelas" style="width:90%; text-align:center;">
                    <input type="hidden" name="form_67[ttd_dokter_pelaksana]">
                </td>
                <td style="width: 25%; text-align:center;">
                    <br><br>
                </td>
                <td style="width: 25%; text-align:center;">
                    Saksi 1
                    <br><br>
                    <span class="ttd-btn" data-role="saksi1" id="ttd_saksi1" style="cursor: pointer;">
                        <i class="fa fa-pencil blue"></i>
                    </span>
                    <br>
                    <img id="img_ttd_saksi1" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
                    <br><br>
                    <input type="text" class="input_type" name="form_67[nama_saksi1]" id="nama_saksi1" placeholder="Nama Jelas" style="width:90%; text-align:center;">
                    <input type="hidden" name="form_67[ttd_saksi1]">
                    <center><span>(Pihak Rumah Sakit)</span></center>
                </td>
                <td style="width: 25%; text-align:center;">
                    Saksi 2
                    <br><br>
                    <span class="ttd-btn" data-role="saksi2" id="ttd_saksi2" style="cursor: pointer;">
                        <i class="fa fa-pencil blue"></i>
                    </span>
                    <br>
                    <img id="img_ttd_saksi2" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
                    <br><br>
                    <input type="text" class="input_type" name="form_67[nama_saksi2]" id="nama_saksi2" placeholder="Nama Jelas" style="width:90%; text-align:center;">
                    <input type="hidden" name="form_67[ttd_saksi2]">
                    <center><span>(Pihak Pasien)</span></center>
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