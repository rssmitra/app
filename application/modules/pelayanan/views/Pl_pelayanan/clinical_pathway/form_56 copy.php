<?php if(isset($_GET['layout']) && $_GET['layout'] == 'full') : ?>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" />
  <style>
    .body-form {
      margin : 20px;
    }
  </style>

  <title>GENERAL CONSENT FOR TREATMENT</title>

<?php endif;?>

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

<div class="body-form">
  
  <?php echo $header; ?>
  <hr>
  <br>

  <input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

  <div style="text-align: center; font-size: 18px;"><b>PERSETUJUAN UMUM PELAYANAN KESEHATAN<br><i>(GENERAL CONSENT FOR TREATMENT)</i></b></div>

  <br>
  <style>
    .form-section {
      margin-bottom: 16px;
    }
    .form-row {
      display: grid;
      grid-template-columns: 25% 75%;
      margin-bottom: 8px;
      align-items: center;
    }
    .form-row label {
      /* font-weight: normal; default */
      padding-right: 10px;
    }
    .form-row input {
      width: 100%;
      padding: 4px;
      box-sizing: border-box;
    }
  </style>

  <div style="text-align: left;">
    <p>Yang bertanda tangan dibawah ini:</p>

    <div class="form-section">
      <div class="form-row">
        <label for="nama">Nama</label>
        <input type="text" class="input_type" name="form_56[nama]" id="nama"
              onchange="fillthis('nama')">
      </div>

      <div class="form-row">
        <label for="ttl_umur">Tempat/Tanggal lahir / Umur</label>
        <input type="text" class="input_type" name="form_56[ttl_umur]" id="ttl_umur"
              onchange="fillthis('ttl_umur')">
      </div>

      <div class="form-row">
        <label for="alamat">Alamat</label>
        <input type="text" class="input_type" name="form_56[alamat]" id="alamat"
              onchange="fillthis('alamat')">
      </div>

      <div class="form-row">
        <label for="no_telp">No. Telp/Hp</label>
        <input type="text" class="input_type" name="form_56[no_telp]" id="no_telp"
              onchange="fillthis('no_telp')">
      </div>

      <div class="form-row">
        <label for="no_identitas">No. Identitas KTP/SIM</label>
        <input type="text" class="input_type" name="form_56[no_identitas]" id="no_identitas"
              onchange="fillthis('no_identitas')">
      </div>
    </div>

    <p>Dengan bertindak atas nama: 
      <label>
        <input type="checkbox" class="ace"
              name="form_56[diri_sendiri]"
              id="diri_sendiri"
              onclick="checkthis('diri_sendiri')">
        <span class="lbl"> Diri Sendiri</span>
      </label>
      
      <label>
        <input type="checkbox" class="ace"
              name="form_56[suami]"
              id="suami"
              onclick="checkthis('suami')">
        <span class="lbl"> Suami</span>
      </label>

      <label>
        <input type="checkbox" class="ace"
              name="form_56[istri]"
              id="istri"
              onclick="checkthis('istri')">
        <span class="lbl"> Istri</span>
      </label>

      <label>
        <input type="checkbox" class="ace"
              name="form_56[anak]"
              id="anak"
              onclick="checkthis('anak')">
        <span class="lbl"> Anak</span>
      </label>

      <label>
        <input type="checkbox" class="ace"
              name="form_56[ibu]"
              id="ibu"
              onclick="checkthis('ibu')">
        <span class="lbl"> Ibu</span>
      </label>

      <label>
        <input type="checkbox" class="ace"
              name="form_56[ayah]"
              id="ayah"
              onclick="checkthis('ayah')">
        <span class="lbl"> Ayah</span>
      </label>

      <label>
        <input type="checkbox" class="ace"
              name="form_56[saudara]"
              id="saudara"
              onclick="checkthis('saudara')">
        <span class="lbl"> Saudara</span>
      </label> dari:</p>

    <div class="form-section">
      <div class="form-row">
        <label for="nama_pasien">Nama</label>
        <input type="text" class="input_type" name="form_56[nama_pasien]" id="nama_pasien"
              onchange="fillthis('nama_pasien')"
              value="<?php $nama_pasien = isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; echo isset($value_form['nama_pasien'])?$value_form['nama_pasien']:$nama_pasien?>">
      </div>

      <div class="form-row">
        <label for="ttl_pasien">Tempat/Tanggal lahir</label>
        <input type="text" class="input_type" name="form_56[ttl_pasien]" id="ttl_pasien"
              onchange="fillthis('ttl_pasien')"
              value="<?php $ttl_pasien = (isset($data_pasien->dob_pasien) ? $data_pasien->dob_pasien . ', ' : '') . (isset($data_pasien->tgl_lhr_pasien) ? $this->tanggal->formatDateShort($data_pasien->tgl_lhr_pasien) : ''); echo isset($value_form['ttl_pasien'])?$value_form['ttl_pasien']:$ttl_pasien?>">
      </div>

      <div class="form-row">
        <label for="umur_pasien">Umur</label>
        <input type="text" class="input_type" name="form_56[umur_pasien]" id="umur_pasien"
              onchange="fillthis('umur_pasien')"
              value="<?php $umur = isset($data_pasien->umur) ? $data_pasien->umur : ''; echo isset($value_form['umur_pasien']) ? $value_form['umur_pasien'] . ' tahun' : $umur . ' tahun'; ?>">
      </div>

      <div class="form-row">
        <label for="no_rm">No. RM</label>
        <input type="text" class="input_type" name="form_56[no_rm]" id="no_rm"
              onchange="fillthis('no_rm')"
              value="<?php $no_mr = isset($data_pasien->no_mr)?$data_pasien->no_mr:''; echo isset($value_form['no_rm'])?$value_form['no_rm']:$no_mr?>">
      </div>
    </div>
  </div>

  <p>Untuk memberikan persetujuan tentang</p>
    <ol>
  <li>
    <b>PERAWATAN DAN PENGOBATAN</b>
    <table style="width: 100%; border-collapse: collapse; border: none;">
      <tr>
        <td style="vertical-align: top; padding: 0;">a.</td>
        <td style="text-align: justify; padding: 5px;"> Saya menyetujui untuk perawatan dan pengobatan di RS. Setia Mitra sebagai pasien rawat jalan atau rawat inap tergantung kepada kebutuhan medis. Pengobatan dapat meliputi pemeriksaan x-ray/radiologi, tes laboratorium, perawatan dan prosedur seperti cairan infus atau suntikan dan evaluasi (contohnya wawancara dan pemeriksaan fisik).</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">b.</td>
        <td style="text-align: justify; padding: 5px;"> Persetujuan yang saya berikan tidak termasuk persetujuan untuk prosedur / tindakan invasif (misalnya, operasi) atau tindakan yang mempunyai resiko tinggi.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">c.</td>
        <td style="text-align: justify; padding: 5px;"> Jika saya memutuskan untuk menghentikan perawatan medis untuk diri saya sendiri, saya memahami dan menyadari bahwa RS. Setia Mitra atau dokter tidak bertanggung jawab atas hasil yang merugikan saya.</td>
      </tr>
    </table>
  </li>
  <li>
    <b>HAK DAN KEWAJIBAN PASIEN DAN KELUARGA</b>
    <table style="width: 100%; border-collapse: collapse; border: none;">
      <tr>
        <td style="vertical-align: top; padding: 0;">a.</td>
        <td style="text-align: justify; padding: 5px;">Saya memiliki hak untuk mengambil bagian dalam keputusan mengenai penyakit saya dan dalam hal perawatan medis dan rencana pengobatan.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">b.</td>
        <td style="text-align: justify; padding: 5px;">Saya telah mendapat informasi tentang "hak dan kewajiban pasien dan keluarga" di RS. Setia Mitra melalui lembar hak dan kewajiban pasien dan keluarga dan banner yang disediakan oleh petugas.</td>
      </tr>
    </table>
  </li>
  <li>
    <b>PELEPASAN INFORMASI</b>
    <table style="width: 100%; border-collapse: collapse; border: none;">
      <tr>
        <td style="vertical-align: top; padding: 0; width: 2%;">a.</td>
        <td style="text-align: justify; padding: 5px;">Saya memahami informasi yang ada di dalam diri saya, termasuk diagnosis hasil laboratorium dan hasil tes diagnostic yang akan digunakan untuk perawatan medis dan RS. Setia Mitra akan menjamin kerahasiaannya.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">b.</td>
        <td style="text-align: justify; padding: 5px;">Saya memberi wewenang kepada RS. Setia Mitra untuk memberikan informasi tentang diagnosis pelayanan dan pengobatan bila diperlukan untuk memproses klaim asuransi / BPJS / perusahaan dan atau lembaga pemerintahan.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">c.</td>
        <td style="text-align: justify; padding: 5px;">Saya memberi wewenang kepada RS. Setia Mitra untuk memberikan informasi yang berkaitan dengan diri saya (termasuk: diagnosis, hasil pelayanan dan pengobatan) kepada anggota keluarga terdekat (suami/istri/ayah/ibu kandung, saudara kandung atau pengampunya), kecuali (sebutkan nama bila permintaan khusus yang tidak di ijinkan) yaitu:</td>
      </tr>
      <tr>
        <td style="text-align: justify; padding: 5px;"></td>
        <td style="text-align: justify; padding: 5px;">
          <ol style="margin-top: 0; padding-left: 20px;">
            <li><input type="text" name="form_56[pengecualian_info_1]" id="pengecualian_info_1" onchange="fillthis('pengecualian_info_1')" style="width: 100%; border-top: none; border-left: none; border-right: none;"></li>
            <li><input type="text" name="form_56[pengecualian_info_2]" id="pengecualian_info_2" onchange="fillthis('pengecualian_info_2')" style="width: 100%; border-top: none; border-left: none; border-right: none;"></li>
          </ol>
        </td>
      </tr>
    </table>
  </li>
  <li>
    <b>PRIVASI</b>
    <table style="width: 100%; border-collapse: collapse; border: none;">
      <tr>
        <td style="vertical-align: top; padding: 0; width: 2%;">a.</td>
        <td style="text-align: justify; padding: 5px;">Saya memahami informasi yang ada di dalam diri saya, termasuk diagnosis hasil laboratorium dan hasil tes diagnostic yang akan digunakan untuk perawatan medis dan RS. Setia Mitra akan menjamin kerahasiaannya.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">b.</td>
        <td style="text-align: justify; padding: 5px;">Saya <b>(menginginkan / tidak menginginkan*)</b> RS. Setia Mitra memberikan akses kepada anggota keluarga terdekat (suami / istri, ayah / ibu kandung, anak kandung, saudara kandung, atau pengampunya), dan handaitauladan serta orang-orang yang akan membesuk saya. Kecuali (sebutkan nama bila ada permintaan khusus yang tidak di ijinkan) yaitu:</td>
      </tr>
      <tr>
        <td style="text-align: justify; padding: 5px;"></td>
        <td style="text-align: justify; padding: 5px;">
          <ol style="margin-top: 0; padding-left: 20px;">
            <li><input type="text" name="form_56[pengecualian_privasi_1]" id="pengecualian_privasi_1" onchange="fillthis('pengecualian_privasi_1')" style="width: 100%; border-top: none; border-left: none; border-right: none;"></li>
            <li><input type="text" name="form_56[pengecualian_privasi_2]" id="pengecualian_privasi_2" onchange="fillthis('pengecualian_privasi_2')" style="width: 100%; border-top: none; border-left: none; border-right: none;"></li>
          </ol>
        </td>
      </tr>
    </table>
  </li>
  <li>
    <b>KEPERCAYAAN ATAU KEYAKINAN KHUSUS YANG DIMILIKI OLEH PASIEN/KELUARGA</b>
    <p>(contohnya: tidak boleh transfusi, diit tertentu, tidak boleh pulang di hari sabtu, tidak boleh imunisasi, tidak dilayani petugas laki-laki pada pasien perempuan, dll)</p>
    <ol style="margin-top: 0; padding-left: 20px;">
      <li><input type="text" name="form_56[kepercayaan_1]" id="kepercayaan_1" onchange="fillthis('kepercayaan_1')" style="width: 100%; border-top: none; border-left: none; border-right: none;"></li>
      <li><input type="text" name="form_56[kepercayaan_2]" id="kepercayaan_2" onchange="fillthis('kepercayaan_2')" style="width: 100%; border-top: none; border-left: none; border-right: none;"></li>
    </ol>
  </li>
  <li>
    <b>BARANG-BARANG MILIK PASIEN</b>
    <p>Saya setuju untuk tidak membawa barang-barang berharga dan saya telah memahami bahwa RS. Setia mitra bertanggung jawab atas semua kehilangan barang-barang milik saya dan saya secara pribadi bertanggung jawab atas barang-barang berhaga yang saya bawa seperti: uang, perhiasan, buku cek, kartu kredit, handphone, dan barang lainnya. Dan apabila saya membutuhkan maka saya dapat menitipkan barang-barang saya kepada RS. Setia Mitra. </p>
  </li>
  <li>
    <b>PERATURAN DAN TATA TERTIB RS. SETIA MITRA</b>
    <table style="width: 100%; border-collapse: collapse; border: none;">
      <tr>
        <td style="vertical-align: top; padding: 0; width: 2%;">a.</td>
        <td style="text-align: justify; padding: 5px;">Saya telah menerima informasi tentang peraturan yang diberlakukan oleh RS. Setia Mitra dan saya beserta keluarga besedia untuk mematuhi termasuk akan mematuhi jam berkunjung pasien sesuai dengan di RS. Setia Mitra.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">b.</td>
        <td style="text-align: justify; padding: 5px;">Anggota keluarga saya yang menunggu saya bersedia untuk selalu memakai tanda pengenal khusus yang diberikan oleh RS, Setia Mitra dan demi keamanan seluruh pasien setiap keluarga dan siapapun yang akan mengunjungi saya di luar jam berkunjung bersedia untuk diminta/diperiksa identitasnya.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">c.</td>
        <td style="text-align: justify; padding: 5px;">Selama di rawat inap boleh ditunggu oleh 1 anggota keluarga.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">d.</td>
        <td style="text-align: justify; padding: 5px;">Kartu tunggu dikembalikan saat pasien akan pulang, telah menyelesaikan administrasi. Bila kartu tunggu hilang bersedia membayar denda Rp. 50.000.</td>
      </tr>
    </table>
  </li>
  <li>
    <b>INFORMASI BIAYA</b>
    <table style="width: 100%; border-collapse: collapse; border: none;">
      <tr>
        <td style="vertical-align: top; padding: 0; width: 2%;">a.</td>
        <td style="text-align: justify; padding: 5px;">Saya telah memahami tentang informasi biaya pengobatan atau biaya tindakan yang dijelaskan oleh petugas RS. Setia Mitra.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">b.</td>
        <td style="text-align: justify; padding: 5px;">
          <p>Saya menyatakan dirawat diruang kelas: <input type="text" name="form_56[kelas_ruang]" id="kelas_ruang" onchange="fillthis('kelas_ruang')" class="input_type" style="width: 50px;">, sesuai hak dan atau plafon, menggunakan pembayaran:</p>
          <div class="checkbox">
            <label>
              <input type="checkbox" class="ace"
                    name="form_56[pembayaran_umum]"
                    id="pembayaran_umum"
                    onclick="checkthis('pembayaran_umum')">
              <span class="lbl"> UMUM</span>
            </label>
          </div>

          <div class="checkbox">
            <label>
              <input type="checkbox" class="ace"
                    name="form_56[pembayaran_asuransi]"
                    id="pembayaran_asuransi"
                    onclick="checkthis('pembayaran_asuransi')">
              <span class="lbl"> ASURANSI</span>
            </label>
          </div>

          <div class="checkbox">
            <label>
              <input type="checkbox" class="ace"
                    name="form_56[pembayaran_bpjs]"
                    id="pembayaran_bpjs"
                    onclick="checkthis('pembayaran_bpjs')">
              <span class="lbl"> BPJS</span>
            </label>
          </div>

          <div class="checkbox">
            <label>
              <input type="checkbox" class="ace"
                    name="form_56[pembayaran_lain]"
                    id="pembayaran_lain"
                    onclick="checkthis('pembayaran_lain')">
              <span class="lbl">
                JAMINAN LAIN,
                <input type="text" name="form_56[jaminan_lain]"
                      id="jaminan_lain"
                      onchange="fillthis('jaminan_lain')"
                      style="border-top:none;border-left:none;border-right:none;width:100px;">
              </span>
            </label>
          </div>
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">c.</td>
        <td style="text-align: justify; padding: 5px;">Dan saya atau pasien setuju membayar seluruh tagihan RS. Setia Mitra, apabila ternyata diagnosis akhir merupakan pengecualian pertanggungan jaminan pembayaran.</td>
      </tr>
    </table>
  </li>
  <li>
    <b>PERAWATAN DAN DPJP (DOKTER PENANGGUNG JAWAB PELAYANAN)</b>
    <table style="width: 100%; border-collapse: collapse; border: none;">
      <tr>
        <td style="vertical-align: top; padding: 0;">Saya setuju untuk dirawat di ruang: <input type="text" name="form_56[ruang_rawat]" id="ruang_rawat" class="input_type" onchange="fillthis('ruang_rawat')" style="width: 70px;"> kelas: <input type="text" name="form_56[kelas_rawat]" id="kelas_rawat" class="input_type" onchange="fillthis('kelas_rawat')" style="width: 70px;"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">DPJP (Dokter Penanggung Jawab Pelayanan): <input type="text" name="form_56[dpjp]" id="dpjp" class="input_type" onchange="fillthis('dpjp')" style="width: 50%;"></td>
      </tr>
    </table>
  </li>
  <li>
    <b>PERSETUJUAN UNTUK PERAWATAN DAN PENGOBATAN SELAMA PANDEMI COVID</b>
    <table style="width: 100%; border-collapse: collapse; border: none;">
      <tr>
        <td style="vertical-align: top; padding: 0; width: 2%;">a.</td>
        <td style="text-align: justify; padding: 5px;">Selama perawatan IGD covid pasien tidak didampingi oleh keluarga dan pasien tidak diperbolehkan membawa barang-barang berharga.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">b.</td>
        <td style="text-align: justify; padding: 5px;">Pasien dalam kondisi baik wajib membawa handphone untuk komunikasi dengan perawat dan keluarga di rumah.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">c.</td>
        <td style="text-align: justify; padding: 5px;">Penanggung jawab pasien wajib memberika nomor telepon dan alamat, minimal dua nomor yang dapat dihubungi oleh petugas Rumah Sakit. Segala bentuk komunikasi terkait kondisi pasien akan di informasikan kepada pihak keluarga (keluarga inti atau penanggung jawab) setiap hari. </td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">d.</td>
        <td style="text-align: justify; padding: 5px;">Diruang perawatan, pasien tidak di perbolehkan keluar ruangan rawat inap.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">e.</td>
        <td style="text-align: justify; padding: 5px;">Waktu jam kunjung atau besuk di tiadakan.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">f.</td>
        <td style="text-align: justify; padding: 5px;">Pasien dewasa tidak dapat di tunggu oleh keluarga.</td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">g.</td>
        <td style="text-align: justify; padding: 5px;">Pasien anak hanya diperbolehkan 1 orang penunggu, yang memahami dan bersedia mendampingi anak selama masa perawatan, serta tidak akan menuntut RS bila tertular penyakit covid. </td>
      </tr>
    </table>
  </li>
  <li>
    <b>PENGAJUAN KELUHAN</b>
    <p>Saya telah menerima informasi tentang cara mengajukan dan mengatasi keluhan terkait pelayanan medis yang diberikan dan saya setuju untuk mengikuti tata cara pengajuan keluhan sesuai prosedur yang ada.</p>
  </li>
  </ol>

  <p>Saya telah membaca dan memahami sepenuhnya isi Persetujuan Umum / General Consent dan (SETUJU/TIDAK SETUJU*) dengan setiap pernyataan yang terdapat dalam formulir ini dan menanda tangani dengan kesadaran penuh dan tanpa paksaan.</p>

    <br>
  <table class="" style="width: 100%; border : none !important; margin-bottom: 0;">
    <tbody>
      <tr>
        <!-- Kolom Saksi Keluarga -->
        <td style="width: 33%; text-align: center;">
          <br><br><br>
          Saksi Keluarga
          <br><br>
          <span class="ttd-btn" data-role="saksi" id="ttd_saksi" style="cursor: pointer;">
            <i class="fa fa-pencil blue"></i>
          </span>
          <br>
          <img id="img_ttd_saksi" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
          <br><br>
          <input type="text" name="form_56[nama_saksi]" id="nama_saksi" class="input_type" placeholder="Nama jelas" style="width:150px; text-align:center;">
          <br>
          (Tanda Tangan dan Nama Jelas)
        </td>

        <!-- Kolom Petugas Pendaftaran -->
        <td style="width: 33%; text-align: center;">
          <br><br><br>
          Petugas Pendaftaran
          <br><br>
          <span class="ttd-btn" data-role="petugas" id="ttd_petugas" style="cursor: pointer;">
            <i class="fa fa-pencil blue"></i>
          </span>
          <br>
          <img id="img_ttd_petugas" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
          <br><br>
          <input type="text" name="form_56[nama_petugas]" id="nama_petugas" class="input_type" placeholder="Nama jelas" style="width: 150px; text-align:center;">
          <br>
          (Tanda Tangan dan Nama Jelas)
        </td>

        <!-- Kolom Pasien -->
        <td style="width: 34%; text-align: center;">
          Jakarta, 
          <input type="text" name="form_56[tanggal_ttd_pasien]" id="tanggal_ttd_pasien" class="input_type" onchange="fillthis('tanggal_ttd_pasien')" style="width: 100px;" value="<?php echo date('d/m/Y')?>">
          <br><br>
          Yang menyatakan
          <br><br>
          <span class="ttd-btn" data-role="pasien" id="ttd_pasien" style="cursor: pointer;">
            <i class="fa fa-pencil blue"></i>
          </span>
          <br>
          <img id="img_ttd_pasien" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
          <br><br>
          <input type="text" name="form_56[ttd_nama_pasien]" id="ttd_nama_pasien" class="input_type" placeholder="Nama jelas" style="width: 150px; text-align:center;">
          <br>
          (Tanda Tangan dan Nama Jelas)
        </td>
      </tr>
    </tbody>
  </table>
  <br><br>
  </div>
  <hr>

  <div style="font-family: Arial, sans-serif; font-size: 12px; line-height: 1.5;">
    <div style="text-align: center; font-weight: bold; font-size: 12px;">HAK DAN TANGGUNG JAWAB PASIEN</div>
    <br>
    <div style="font-weight: bold;">A. Hak Pasien dan Keluarga</div>
    <ol style="padding-left: 20px;">
      <li>Memperoleh informasi mengenai tata tertib dan peraturan yang berlaku di Rumah Sakit</li>
      <li>Memperoleh informasi tentang Hak dan Kewajiban pasien</li>
      <li>Memperoleh layanan yang manusiawi, adil, jujur dan tanpa diskriminasi</li>
      <li>Memperoleh layanan kesehatan yang bermutu sesuai dengan standar profesi dan standar prosedur operasional</li>
      <li>Memperoleh layanan yang efektif dan efisien sehingga pasien terhindar dari kerugian fisik dan materi</li>
      <li>Mengajukan pengaduan atas kualitas pelayanan yang didapatkan</li>
      <li>Memilih dokter dan kelas perawatan sesuai dengan keinginannya dan peraturan yang berlaku di Rumah Sakit</li>
      <li>Meminta konsultasi tentang penyakit yang dideritanya kepada dokter lain yang memiliki Surat Izin Praktek (SIP) baik di dalam maupun di luar Rumah Sakit</li>
      <li>Mendapatkan privasi dan kerahasiaan penyakit yang diderita termasuk data-data medisnya</li>
      <li>Mendapatkan informasi yang meliputi diagnosis dan tata cara tindakan medis, tujuan tindakan medis, alternative tindakan, resiko dan komplikasi yang mungkin terjadi dan prognosis terhadap tindakan yang dilakukan secara perkiraan biaya pengobatan yang dilakukan</li>
      <li>Memberi persetujuan atau menolak atas tindakan yang dilakukan oleh tenaga kesehatan terhadap penyakit yang di derita</li>
      <li>Didampingi keluarga dalam keadaan kritis</li>
      <li>Menjalankan ibadah sesuai agama atau kepercayaan yang dianutnya selama tidak mengganggu pasien lainnya</li>
      <li>Memperoleh keamanan dan keselamatan dirinya selama perawatan di Rumah Sakit</li>
      <li>Mengajukan usul, saran, perbaikan atas perlakuan Rumah Sakit terhadap dirinya</li>
      <li>Menolak bimbingan layanan rohani yang tidak sesuai dengan agama dan kepercayaan yang dianutnya</li>
      <li>Menggugat atau menuntut Rumah Sakit apabila Rumah Sakit diduga diberikan pelayanan yang tidak sesuai dengan standar baik secara perdata ataupun pidana</li>
      <li>Mengeluhkan layanan Rumah Sakit yang tidak sesuai dengan standar pelayanan melalui media cetak dan elektronik sesuai dengan ketentuan peraturan per undang-undangan</li>
    </ol>
    <br>
    <div style="font-weight: bold;">B. Kewajiban Pasien dan keluarga</div>
    <ol style="padding-left: 20px;">
      <li>Mematuhi peraturan dan tata tertib yang berlaku di Rumah Sakit.</li>
      <li>Mematuhi rencana terapi atau pengobatan yang direkomendasikan oleh tim dokter dan perawat sesuai dengan ketentuan</li>
      <li>Memberi informasi dengan jujur, lengkap dan akurat tentang masalah penyakit yang diderita atau kesehatannya.</li>
      <li>Melunasi semua imbalan dan jasa pelayanan Rumah Sakit yang telah diterima.</li>
      <li>Pasien atau keluarga bertanggung jawab jawab memenuhi hal yang telah disepakati dalam perjanjian yang telah dibuat.</li>
    </ol>
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

</div>