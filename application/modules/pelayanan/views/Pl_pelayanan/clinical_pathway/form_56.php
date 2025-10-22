<?php if(isset($_GET['layout']) && $_GET['layout'] == 'full') : ?>
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" />
    <style>
      .body-form {
        margin : 20px;
        font-size: 12px !important;
      }
    </style>

    <title>GENERAL CONSENT FOR TREATMENT</title>

  <?php endif;?>

  <script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo base_url()?>/assets/js/jquery.js'>"+"<"+"/script>");
  </script>

  <script type="text/javascript" src="<?php echo base_url()?>assets/jSignature/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url()?>assets/jSignature/jquery-ui.min.js"></script>
  <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>
  <!-- ace scripts -->
  <script src="<?php echo base_url()?>assets/js/ace/ace.js"></script>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
  <script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
  <script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

  <script>
    $(document).ready(function() {
      show_edit($('#cppt_id').val());
    })

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
          // Tambahkan input hidden untuk menyimpan data URL ke dalam container .body-form karena tidak ada elemen <form>
          var hiddenInputName = 'form_56[ttd_' + role + ']';
          if ($('input[name="' + hiddenInputName + '"]').length === 0) {
            $('<input>').attr({
              type: 'hidden',
              id: 'ttd_data_' + role,
              name: hiddenInputName,
              value: dataUrl
            }).appendTo('.body-form');
          } else {
            $('input[name="' + hiddenInputName + '"]').val(dataUrl);
          }
        }
        $('#ttdModal').modal('hide');
      });
      // focus first visible input for better UX
      setTimeout(function(){
        var first = document.querySelector('.body-form .form-control');
        if(first) first.focus();
      }, 300);
    });

    (function($){
      var roles = ['saksi','petugas','pasien'];

      function ensureHidden(role){
      if($('#ttd_data_'+role).length === 0){
        $('<input>').attr({
        type: 'hidden',
        id: 'ttd_data_' + role,
        name: 'form_56[ttd_' + role + ']',
        value: ''
        }).appendTo('.body-form');
      }
      }

      function ensureClearBtn(role){
      var ttdBtn = $('#ttd_' + role);
      var clearBtn = $('#clear_ttd_' + role);
      if(ttdBtn.length && clearBtn.length === 0){
        $('<i>').attr({
        id: 'clear_ttd_' + role,
        class: 'fa fa-times-circle-o'
        }).css({ 'margin-left': '6px', display: 'none', cursor: 'pointer' }).insertAfter(ttdBtn)
        .on('click', function(){
        $('#ttd_data_' + role).val('').trigger('change');
        updateButtons();
        });
      } else if (clearBtn.length) {
        // jika tombol sudah ada di DOM, pastikan handler terpasang (hindari duplikasi dengan off)
        clearBtn.off('click').on('click', function(){
        $('#ttd_data_' + role).val('').trigger('change');
        updateButtons();
        });
      }
      }

      function updateButtons(){
      roles.forEach(function(role){
        var val = $('#ttd_data_' + role).val();
        if(val){
        $('#ttd_' + role).hide();
        $('#clear_ttd_' + role).show();
        $('#img_ttd_' + role).attr('src', val).show();
        } else {
        $('#ttd_' + role).show();
        $('#clear_ttd_' + role).hide();
        $('#img_ttd_' + role).attr('src','').hide();
        }
      });
      }

      $(function(){
      // create hidden inputs and clear buttons if missing
      roles.forEach(function(role){
        ensureHidden(role);
        ensureClearBtn(role);
        // update on hidden input change
        $('#ttd_data_' + role).on('change input', updateButtons);
      });

      // ensure UI updates after saving signature
      $('#save-ttd').off('click.global_update').on('click.global_update', function(){
        setTimeout(updateButtons, 120);
      });

      // initial state
      updateButtons();
      });
    })(jQuery);

    (function($){
      // bind after DOM ready and use delegated handler to ensure it works even if element is added later
      $(function(){
        $(document).on('click', '#btn_save_process', function(e){
          e.preventDefault();
          var $btn = $(this);
          $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mengirim...');

          // Ambil HTML dari container form (atau seluruh dokumen jika perlu)
          var htmlContent = document.querySelector('.body-form') ? document.querySelector('.body-form').outerHTML : document.documentElement.outerHTML;

          // Ambil semua tag <script> dan gabungkan kontennya.
          var scripts = Array.prototype.slice.call(document.getElementsByTagName('script'));
          var combinedScripts = '';
          var pending = 0;
          var finished = function(){
            // Siapkan payload
            var payload = {
              catatan_pengkajian: htmlContent,
              // scripts: combinedScripts,
              jenis_form_catatan: $('#jenis_form').val(),
              // serialize inputs inside the .body-form container so hidden signature inputs are included
              form_values: $('#content_body').find('input,textarea,select').serialize(),
              no_registrasi: $('#no_registrasi').val(),
              no_kunjungan: $('#no_kunjungan').val(),
              cppt_id: $('#cppt_id').val()
            };

            // Kirim ke server
            $.ajax({
              url: '<?php echo base_url("pelayanan/Pl_pelayanan/processSaveGeneralConsent")?>',
              method: 'POST',
              data: payload,
              dataType: 'json',
              success: function(resp){
                if(resp && resp.status === 200){
                  alert('Data berhasil dikirim dan diproses.');
                  show_edit(resp.cppt_id);
                  $('#cppt_id').val(resp.cppt_id);
                  // jika perlu arahkan atau lakukan sesuatu:
                  if(resp.redirect) window.location.href = resp.redirect;
                } else {
                  alert('Respon server: ' + (resp && resp.message ? resp.message : 'Gagal memproses'));
                }
              },
              error: function(xhr){
                alert('Terjadi kesalahan saat mengirim data: ' + xhr.status + ' ' + xhr.statusText);
              },
              complete: function(){
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Simpan dan Proses');
              }
            });
          };

          if(scripts.length === 0){
            finished();
          } else {
            // untuk setiap <script> ambil konten; jika ada src -> GET konten
            scripts.forEach(function(s){
              var src = s.getAttribute('src');
              if(src){
                pending++;
                // pastikan URL absolut jika perlu
                var url = src;
                // beberapa src mungkin relatif, convert ke absolute berdasarkan location
                if(url.indexOf('http') !== 0 && url.indexOf('//') !== 0){
                  var a = document.createElement('a'); a.href = url; url = a.href;
                }
                // ambil file eksternal (jika gagal, tetap lanjut)
                $.ajax({
                  url: url,
                  method: 'GET',
                  dataType: 'text',
                  success: function(data){
                    combinedScripts += "\n/* EXTERNAL: " + url + " */\n" + data + "\n";
                  },
                  error: function(){
                    combinedScripts += "\n/* EXTERNAL (gagal ambil): " + url + " */\n";
                  },
                  complete: function(){
                    pending--;
                    if(pending === 0) finished();
                  }
                });
              } else {
                // inline script
                combinedScripts += "\n/* INLINE SCRIPT */\n" + (s.textContent || s.innerText || '') + "\n";
              }
            });
            // jika hanya inline script (pending tetap 0), panggil finished
            if(pending === 0) finished();
          }
        });
      });
    })(jQuery);
    
    (function($){
      // tambahkan CSS supaya elemen dengan kelas .no-print tidak tampil pada saat print
      var css = '@media print{ .no-print{ display:none !important; } }';
      var style = document.createElement('style'); style.type = 'text/css';
      if (style.styleSheet) style.styleSheet.cssText = css; else style.appendChild(document.createTextNode(css));
      document.head.appendChild(style);

      // tandai tombol "Simpan dan Proses" agar juga tidak tampil saat print
      $(function(){
        $("a").has("i.fa-save").addClass('no-print');
        // juga beri kelas no-print pada tombol cetak sendiri (agar menghilang saat preview/print)
        $("#btn_print").addClass('no-print');
      });

      // klik cetak -> panggil print
      $(document).on('click', '#btn_print', function(e){
        e.preventDefault();
        // window.print akan memakai @media print untuk menyembunyikan tombol
        window.print();
      });

      // fallback: jika ingin mengeksekusi sesuatu setelah print selesai
      function afterPrint(){ /* kosong - CSS sudah mengatur tampilan */ }
      if (window.matchMedia) {
        var mql = window.matchMedia('print');
        mql.addListener(function(m){
          if (!m.matches) afterPrint();
        });
      }
      window.addEventListener('afterprint', afterPrint, false);
    })(jQuery);

    function show_edit(myid){
      preventDefault();
      $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_cppt_dt') ?>", {id: myid} , function (response) {    
        // show data
        var obj = response.result;
        console.log(response);
        $('#cppt_id').val(myid);
        $('#jenis_form_catatan').val(obj.jenis_form);
        $('#editor_html_pengkajian').html(obj.catatan_pengkajian);
        // set value input
        var value_form = response.value_form;
        $.each(value_form, function(i, item) {
          var text = item;
          text = text.replace(/\+/g, ' ');
          $('#'+i).val(text);
        });
        $('#anatomi_tagging_28').val(response.anatomi_tagging);

      }); 
    }

    function preventDefault(e) {
      e = e || window.event;
      if (e.preventDefault)
          e.preventDefault();
      e.returnValue = false;  
    }

    function checkthis(id){
      if($('#'+id+'').is(':checked')) {
          $('#'+id+'').attr('checked', true);
      } else {
          $('#'+id+'').attr('checked', false);    
      }
    }

    function fillthis(id){
      var val_str = document.getElementById(id).value;
      $('#'+id+'').val(val_str);
    }

  </script>

  <style>
    .form-section { margin-bottom: 18px; }
    .form-row { display:flex; gap:12px; margin-bottom:1px; align-items:center; }
    .form-row label { width:200px; font-weight:600; }
    .form-row .control { flex:1; }
    .form-control { width:100%; padding:8px 10px; border:1px solid #d0d7de; border-radius:6px; font-size:12px; }
    .help { font-size:12px; color:#6b7280; }
    .checkbox-inline { display:inline-flex; align-items:center; gap:6px; margin-right:10px; }
    .signature-img { display:block; max-width:160px; max-height:60px; margin:6px auto; }
    .radio-yesno { display:flex; gap:16px; align-items:center; }

    .no-border { border:none !important; box-shadow:none; padding-left:0; border-bottom:1px solid #d0d7de !important;  }

    @media print {
        .page, .page-break { break-after: page; }
    }
  </style>


<input type="hidden" name="jenis_form" id="jenis_form" value="<?php echo $jenis_form?>">
<input type="hidden" name="cppt_id" id="cppt_id" value="<?php echo isset($data_cppt->cppt_id)?$data_cppt->cppt_id:''?>">
<input type="hidden" name="no_kunjungan" id="no_kunjungan" value="<?php echo isset($no_kunjungan)?$no_kunjungan:''?>">
<input type="hidden" name="no_registrasi" id="no_registrasi" value="<?php echo isset($no_registrasi)?$no_registrasi:''?>">


<?php 
  if(isset($data_cppt->catatan_pengkajian) && !empty($data_cppt->catatan_pengkajian)){
    echo $data_cppt->catatan_pengkajian;
  }else {
?>

<div class="body-form" id="content_body" style="max-width:900px;margin:0 auto;padding:18px;background:#fff;border-radius:8px;box-shadow:0 4px 18px rgba(0,0,0,0.06);">

  <?php echo $header; ?>
  <hr>
  
  <div style="text-align: center; font-size: 20px; font-weight:700; margin-bottom:12px;">PERSETUJUAN UMUM PELAYANAN KESEHATAN<br><small style="font-weight:400;">(GENERAL CONSENT FOR TREATMENT)</small></div>

  <div style="text-align: left;">
    <p style="font-size:12px;">Yang bertanda tangan dibawah ini:</p>

    <div class="form-section">
      <div class="form-row">
        <label for="nama">Nama</label>
        <div class="control"><input type="text" class="form-control no-border" name="form_56[nama]" id="nama" placeholder="Nama lengkap" onchange="fillthis('nama')" aria-label="Nama"></div>
      </div>

      <div class="form-row">
        <label for="ttl_umur">Tempat / Tgl Lahir / Umur</label>
        <div class="control"><input type="text" class="form-control no-border" name="form_56[ttl_umur]" id="ttl_umur" placeholder="Jakarta, 01/01/1980 / 45 tahun" onchange="fillthis('ttl_umur')" aria-label="Tempat tanggal lahir dan umur"></div>
      </div>

      <div class="form-row">
        <label for="alamat">Alamat</label>
        <div class="control"><input type="text" class="form-control no-border" name="form_56[alamat]" id="alamat" placeholder="Alamat tempat tinggal" onchange="fillthis('alamat')" aria-label="Alamat"></div>
      </div>

      <div class="form-row">
        <label for="no_telp">No. Telp / HP</label>
        <div class="control"><input type="tel" class="form-control no-border" name="form_56[no_telp]" id="no_telp" placeholder="0812xxxx" onchange="fillthis('no_telp')" aria-label="Nomor telepon"></div>
      </div>

      <div class="form-row">
        <label for="no_identitas">No. Identitas (KTP / SIM)</label>
        <div class="control"><input type="text" class="form-control no-border" name="form_56[no_identitas]" id="no_identitas" placeholder="1234567890123456" onchange="fillthis('no_identitas')" aria-label="Nomor identitas"></div>
      </div>
    </div>

    <p style="margin-top:6px;">Bertindak atas nama:</p>
    <div class="form-section">
      <div class="form-row">
        <label class="checkbox-inline"><input type="checkbox" style="width: 16px !important" name="form_56[diri_sendiri]" onclick="checkthis('diri_sendiri')" id="diri_sendiri"> Diri sendiri</label>
          <label class="checkbox-inline"><input type="checkbox" style="width: 16px !important" name="form_56[suami]" onclick="checkthis('suami')" id="suami"> Suami</label>
          <label class="checkbox-inline"><input type="checkbox" style="width: 16px !important" name="form_56[istri]" onclick="checkthis('istri')" id="istri"> Istri</label>
          <label class="checkbox-inline"><input type="checkbox" style="width: 16px !important" name="form_56[anak]" onclick="checkthis('anak')" id="anak"> Anak</label>
          <label class="checkbox-inline"><input type="checkbox" style="width: 16px !important" name="form_56[ibu]" onclick="checkthis('ibu')" id="ibu"> Ibu</label>
          <label class="checkbox-inline"><input type="checkbox" style="width: 16px !important" name="form_56[ayah]" onclick="checkthis('ayah')" id="ayah"> Ayah</label>
          <label class="checkbox-inline"><input type="checkbox" style="width: 16px !important" name="form_56[saudara]" onclick="checkthis('saudara')" id="saudara"> Saudara</label>
      </div>
      <br>

      <div class="form-row">
        <label for="nama_pasien">Nama Pasien</label>
        <div class="control"><input type="text" class="form-control no-border" name="form_56[nama_pasien]" id="nama_pasien" placeholder="Nama pasien" onchange="fillthis('nama_pasien')" value="<?php $nama_pasien = isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; echo isset($value_form['nama_pasien'])?$value_form['nama_pasien']:$nama_pasien?>"></div>
      </div>

      <div class="form-row">
        <label for="ttl_pasien">Tempat / Tgl Lahir</label>
        <div class="control"><input type="text" class="form-control no-border" name="form_56[ttl_pasien]" id="ttl_pasien" placeholder="Jakarta, 01/01/1980" onchange="fillthis('ttl_pasien')" value="<?php $ttl_pasien = (isset($data_pasien->dob_pasien) ? $data_pasien->dob_pasien . ', ' : '') . (isset($data_pasien->tgl_lhr_pasien) ? $this->tanggal->formatDateShort($data_pasien->tgl_lhr_pasien) : ''); echo isset($value_form['ttl_pasien'])?$value_form['ttl_pasien']:$ttl_pasien?>"></div>
      </div>

      <div class="form-row">
        <label for="umur_pasien">Umur</label>
        <div class="control"><input type="text" class="form-control no-border" name="form_56[umur_pasien]" id="umur_pasien" placeholder="45 tahun" onchange="fillthis('umur_pasien')" value="<?php $umur = isset($data_pasien->umur) ? $data_pasien->umur : ''; echo isset($value_form['umur_pasien']) ? $value_form['umur_pasien'] . ' tahun' : $umur . ' tahun'; ?>"></div>
      </div>

      <div class="form-row">
        <label for="no_rm">No. RM</label>
        <div class="control"><input type="text" class="form-control no-border" name="form_56[no_rm]" id="no_rm" placeholder="MR-000000" onchange="fillthis('no_rm')" value="<?php $no_mr = isset($data_pasien->no_mr)?$data_pasien->no_mr:''; echo isset($value_form['no_rm'])?$value_form['no_rm']:$no_mr?>"></div>
      </div>
  </div>

  <p style="margin-top:8px;">Untuk memberikan persetujuan tentang</p>
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
    <br>
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
    <br>
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
            <li><input type="text" name="form_56[pengecualian_info_1]" id="pengecualian_info_1" onchange="fillthis('pengecualian_info_1')" class="form-control no-border" style="border:none;border-bottom:1px solid #d0d7de; padding:6px 4px;"></li>
            <li><input type="text" name="form_56[pengecualian_info_2]" id="pengecualian_info_2" onchange="fillthis('pengecualian_info_2')" class="form-control no-border" style="border:none;border-bottom:1px solid #d0d7de; padding:6px 4px;"></li>
          </ol>
        </td>
      </tr>
    </table>
    <br>
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
            <li><input type="text" name="form_56[pengecualian_privasi_1]" id="pengecualian_privasi_1" onchange="fillthis('pengecualian_privasi_1')" class="form-control no-border" style="border:none;border-bottom:1px solid #d0d7de; padding:6px 4px;"></li>
            <li><input type="text" name="form_56[pengecualian_privasi_2]" id="pengecualian_privasi_2" onchange="fillthis('pengecualian_privasi_2')" class="form-control no-border" style="border:none;border-bottom:1px solid #d0d7de; padding:6px 4px;"></li>
          </ol>
        </td>
      </tr>
    </table>
    <br>
  </li>
  <li>
    <b>KEPERCAYAAN ATAU KEYAKINAN KHUSUS YANG DIMILIKI OLEH PASIEN/KELUARGA</b>
    <p>(contohnya: tidak boleh transfusi, diit tertentu, tidak boleh pulang di hari sabtu, tidak boleh imunisasi, tidak dilayani petugas laki-laki pada pasien perempuan, dll)</p>
    <ol style="margin-top: 0; padding-left: 20px;">
      <li><input type="text" name="form_56[kepercayaan_1]" id="kepercayaan_1" onchange="fillthis('kepercayaan_1')" class="form-control no-border" style="border:none;border-bottom:1px solid #d0d7de; padding:6px 4px;"></li>
      <li><input type="text" name="form_56[kepercayaan_2]" id="kepercayaan_2" onchange="fillthis('kepercayaan_2')" class="form-control no-border" style="border:none;border-bottom:1px solid #d0d7de; padding:6px 4px;"></li>
    </ol>
    <br>
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
    <br>
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
          <p>Saya menyatakan dirawat diruang kelas: <input type="text" name="form_56[kelas_ruang]" id="kelas_ruang" onchange="fillthis('kelas_ruang')" class="form-control no-border" style="width: 70px; display:inline-block;">, sesuai hak dan atau plafon, menggunakan pembayaran:</p>
          <div style="display:flex;gap:14px;flex-wrap:wrap;">
            <label class="checkbox-inline"><input type="checkbox" style="width: 16px !important" name="form_56[pembayaran_umum]" onclick="checkthis('pembayaran_umum')" id="pembayaran_umum"> UMUM</label>
            <label class="checkbox-inline"><input type="checkbox" style="width: 16px !important" name="form_56[pembayaran_asuransi]" onclick="checkthis('pembayaran_asuransi')" id="pembayaran_asuransi"> ASURANSI</label>
            <label class="checkbox-inline"><input type="checkbox" style="width: 16px !important" name="form_56[pembayaran_bpjs]" onclick="checkthis('pembayaran_bpjs')" id="pembayaran_bpjs"> BPJS</label>
            <label class="checkbox-inline"> JAMINAN LAIN: <input type="text" name="form_56[jaminan_lain]" id="jaminan_lain" onchange="checkthis('jaminan_lain')" class="form-control no-border" style="display:inline-block;width:180px;margin-left:8px;padding:6px 8px;"></label>
          </div>
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">c.</td>
        <td style="text-align: justify; padding: 5px;">Dan saya atau pasien setuju membayar seluruh tagihan RS. Setia Mitra, apabila ternyata diagnosis akhir merupakan pengecualian pertanggungan jaminan pembayaran.</td>
      </tr>
    </table>
    <br>
  </li>
  <li>
    <b>PERAWATAN DAN DPJP (DOKTER PENANGGUNG JAWAB PELAYANAN)</b>
    <table style="width: 100%; border-collapse: collapse; border: none;">
      <tr>
  <td style="vertical-align: top; padding: 0;">Saya setuju untuk dirawat di ruang: <input type="text" name="form_56[ruang_rawat]" id="ruang_rawat" class="form-control no-border" onchange="fillthis('ruang_rawat')" style="width: 100px; display:inline-block;"> kelas: <input type="text" name="form_56[kelas_rawat]" id="kelas_rawat" class="form-control no-border" onchange="fillthis('kelas_rawat')" style="width: 100px; display:inline-block;"></td>
      </tr>
      <tr>
        <td style="vertical-align: top; padding: 0;">DPJP (Dokter Penanggung Jawab Pelayanan): <input type="text" name="form_56[dpjp]" id="dpjp" class="form-control no-border" onchange="fillthis('dpjp')" style="width: 50%; display:inline-block;"></td>
      </tr>
    </table>
    <br>
  </li>
    <div class="page-break" style="page-break-before: always; break-before: page; -webkit-column-break-before: always; height:0;"></div>
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
    <br>
  </li>
  <li>
    <b>PENGAJUAN KELUHAN</b>
    <p>Saya telah menerima informasi tentang cara mengajukan dan mengatasi keluhan terkait pelayanan medis yang diberikan dan saya setuju untuk mengikuti tata cara pengajuan keluhan sesuai prosedur yang ada.</p>
  </li>
  </ol>

  <p>Saya telah membaca dan memahami sepenuhnya isi Persetujuan Umum / General Consent dan (SETUJU/TIDAK SETUJU*) dengan setiap pernyataan yang terdapat dalam formulir ini dan menanda tangani dengan kesadaran penuh dan tanpa paksaan.</p>
  
  <div style="margin:16px 0; display:flex; align-items:center; gap:18px;">
    <div style="font-weight:600;">Persetujuan:</div>
    <div class="radio-yesno" style="display:flex; gap:16px; align-items:center;">
      <label style="display:inline-flex; align-items:center; gap:8px;">
        <input type="radio" onclick="checkthis('verif_setuju')" name="form_56[persetujuan]" id="verif_setuju" value="setuju" style="width:16px; height:16px;">
        <span>Setuju</span>
      </label>
      <label style="display:inline-flex; align-items:center; gap:8px;">
        <input type="radio" onclick="checkthis('verif_tidak_setuju')" name="form_56[persetujuan]" id="verif_tidak_setuju" value="tidak_setuju" style="width:16px; height:16px;">
        <span>Tidak Setuju</span>
      </label>
    </div>
  </div>

  <table class="" style="width: 100%; border : none !important; margin-bottom: 0;">
    <tbody>
      <tr>
        <!-- Kolom Saksi Keluarga -->
        <td style="width: 33%; text-align: center;">
          <br><br><br>
          <div style="font-weight:600;margin-bottom:6px;">Saksi Keluarga</div>
            <div>
            <div>
              <button type="button" class="ttd-btn btn btn-outline-primary btn-sm" data-role="saksi" id="ttd_saksi">Tandatangani</button>
              <i class="fa fa-times-circle-o red" id="clear_ttd_saksi" style="display:none; cursor: pointer"></i>

              <input type="hidden" id="ttd_data_saksi" name="form_56[ttd_saksi]" value="">
            </div>
            </div>

      
          <img id="img_ttd_saksi" src="" class="signature-img" style="display:none;">
          <div style="margin-top:8px;"><input type="text" name="form_56[nama_saksi]" id="nama_saksi" class="form-control no-border" placeholder="Nama jelas" style="width:160px;margin:0 auto;text-align:center;"></div>
          <div class="help">(Tanda Tangan dan Nama Jelas)</div>
        </td>

        <!-- Kolom Petugas Pendaftaran -->
        <td style="width: 33%; text-align: center;">
          <br><br><br>
          <div style="font-weight:600;margin-bottom:6px;">Petugas Pendaftaran</div>
          <div>
            <button type="button" class="ttd-btn btn btn-outline-primary btn-sm" data-role="petugas" id="ttd_petugas">Tandatangani</button>
            <i class="fa fa-times-circle-o red" id="clear_ttd_petugas" style="display:none; cursor: pointer"></i>
            <input type="hidden" id="ttd_data_petugas" name="form_56[ttd_petugas]" value="">
          </div>
          <img id="img_ttd_petugas" src="" class="signature-img" style="display:none;">
          <div style="margin-top:8px;"><input type="text" name="form_56[nama_petugas]" id="nama_petugas" class="form-control no-border" placeholder="Nama jelas" style="width:160px;margin:0 auto;text-align:center;"></div>
          <div class="help">(Tanda Tangan dan Nama Jelas)</div>
        </td>

        <!-- Kolom Pasien -->
        <td style="width: 34%; text-align: center;">
          <div style="text-align:center;">
            <div>Jakarta, <input type="text" name="form_56[tanggal_ttd_pasien]" id="tanggal_ttd_pasien" class="form-control no-border" onchange="fillthis('tanggal_ttd_pasien')" style="width: 120px; display:inline-block; text-align:left;" value="<?php echo date('d/m/Y')?>"></div>
            <div style="font-weight:600;margin-top:8px;">Yang menyatakan</div>
            <div style="margin-top:8px;"><button type="button" class="ttd-btn btn btn-outline-primary btn-sm" data-role="pasien" id="ttd_pasien">Tandatangani</button>
            <i class="fa fa-times-circle-o red" id="clear_ttd_pasien" style="display:none; cursor: pointer"></i>
            <input type="hidden" id="ttd_data_pasien" name="form_56[ttd_pasien]" value="">
            
            </div>
            <img id="img_ttd_pasien" src="" class="signature-img" style="display:none;">
            <div style="margin-top:8px;"><input type="text" name="form_56[ttd_nama_pasien]" id="ttd_nama_pasien" class="form-control no-border" placeholder="Nama jelas" style="width:160px;margin:0 auto;text-align:center;"></div>
            <div class="help">(Tanda Tangan dan Nama Jelas)</div>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
  <br><br>
  </div>
  <hr>

  <div class="page-break" style="page-break-before: always; break-before: page; -webkit-column-break-before: always; height:0;"></div>

  <div style="font-size: 12px; line-height: 1.5;">
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
          <div class="modal-header" style="background:#00669f;color:#fff;">
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
  <hr>

</div>

<?php }?>

<div style="max-width:900px;margin:0 auto;padding:18px;background:#fff;border-radius:8px;box-shadow:0 4px 18px rgba(0,0,0,0.06);">
  <a href="#" class="btn btn-block btn-primary" id="btn_save_process"><i class="fa fa-save"></i> Simpan dan Proses</a>
  <a href="#" id="btn_print" class="btn btn-block btn-inverse"><i class="fa fa-print"></i> Cetak Form General Consent  </a>
<div>
