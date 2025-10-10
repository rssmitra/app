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
      var hiddenInputName = 'form_110[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 10 oktober 2025</p> -->

<div style="text-align: center; font-size: 18px;">
  <b>DIAGNOSIS KEPERAWATAN: GANGGUAN POLA TIDUR</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Gangguan kualitas dan kuantitas waktu tidur akibat faktor eksternal.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[penyebab][]" id="gp_tidur1" onclick="checkthis('gp_tidur1')" value="Hambatan lingkungan (mis: kelembaban, suhu, pencahayaan, kebisingan, bau tidak sedap)"><span class="lbl"> Hambatan lingkungan (mis: kelembaban, suhu, pencahayaan, kebisingan, bau tidak sedap)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[penyebab][]" id="gp_tidur2" onclick="checkthis('gp_tidur2')" value="Kurangnya kontrol tidur"><span class="lbl"> Kurangnya kontrol tidur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[penyebab][]" id="gp_tidur3" onclick="checkthis('gp_tidur3')" value="Kurangnya privasi"><span class="lbl"> Kurangnya privasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[penyebab][]" id="gp_tidur4" onclick="checkthis('gp_tidur4')" value="Restrain fisik"><span class="lbl"> Restrain fisik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[penyebab][]" id="gp_tidur5" onclick="checkthis('gp_tidur5')" value="Ketiadaan teman tidur"><span class="lbl"> Ketiadaan teman tidur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[penyebab][]" id="gp_tidur6" onclick="checkthis('gp_tidur6')" value="Tidak familiar dengan peralatan tidur"><span class="lbl"> Tidak familiar dengan peralatan tidur</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_110[gp_tidur_intervensi_selama]" id="gp_tidur_intervensi_selama" onchange="fillthis('gp_tidur_intervensi_selama')" style="width:10%;">,
          maka pola tidur membaik (L.05045), dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[kriteria_hasil][]" id="gp_tidur_krit1" onclick="checkthis('gp_tidur_krit1')" value="Pemahaman/memahami kalimat meningkat"><span class="lbl"> Pemahaman/memahami kalimat meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[kriteria_hasil][]" id="gp_tidur_krit2" onclick="checkthis('gp_tidur_krit2')" value="Memahami cerita meningkat"><span class="lbl"> Memahami cerita meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[kriteria_hasil][]" id="gp_tidur_krit3" onclick="checkthis('gp_tidur_krit3')" value="Menyampaikan pesan yang koheren meningkat"><span class="lbl"> Menyampaikan pesan yang koheren meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[kriteria_hasil][]" id="gp_tidur_krit4" onclick="checkthis('gp_tidur_krit4')" value="Proses pikir teratur meningkat"><span class="lbl"> Proses pikir teratur meningkat</span></label></div>
      </td>
    </tr>

    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Dibuktikan dengan:</b><br>

        <p><b>Tanda dan Gejala Mayor:</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif :</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[mayor_subjektif][]" id="gp_tidur_mayor_sub1" onclick="checkthis('gp_tidur_mayor_sub1')" value="Mengeluh sulit tidur"><span class="lbl"> Mengeluh sulit tidur</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[mayor_subjektif][]" id="gp_tidur_mayor_sub2" onclick="checkthis('gp_tidur_mayor_sub2')" value="Mengeluh sering terjaga"><span class="lbl"> Mengeluh sering terjaga</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[mayor_subjektif][]" id="gp_tidur_mayor_sub3" onclick="checkthis('gp_tidur_mayor_sub3')" value="Mengeluh tidak puas tidur"><span class="lbl"> Mengeluh tidak puas tidur</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[mayor_subjektif][]" id="gp_tidur_mayor_sub4" onclick="checkthis('gp_tidur_mayor_sub4')" value="Mengeluh pola tidur berubah"><span class="lbl"> Mengeluh pola tidur berubah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[mayor_subjektif][]" id="gp_tidur_mayor_sub5" onclick="checkthis('gp_tidur_mayor_sub5')" value="Mengeluh istirahat tidak cukup"><span class="lbl"> Mengeluh istirahat tidak cukup</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif :</b><br>
            (Tidak ada)
          </div>
        </div>

        <hr>
        <p><b>Tanda dan Gejala Minor:</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif :</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[minor_subjektif][]" id="gp_tidur_minor_sub1" onclick="checkthis('gp_tidur_minor_sub1')" value="Mengeluh kemampuan beraktivitas menurun"><span class="lbl"> Mengeluh kemampuan beraktivitas menurun</span></label></div>
          </div>
          <div class="col-md-6">
            <b>Objektif :</b><br>
            (Tidak ada)
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->




<!-- DUKUNGAN TIDUR & EDUKASI AKTIVITAS/ISTIRAHAT -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>

  <tbody>
    <!-- DUKUNGAN TIDUR -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Dukungan Tidur</b><br>
        <i>(Memfasilitasi siklus tidur dan terjaga yang teratur)</i><br>
        <b>(I.05174)</b>
      </td>
    </tr>

    <!-- Observasi Dukungan Tidur -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_observasi][]" id="tidur_observasi1" onclick="checkthis('tidur_observasi1')" value="Identifikasi pola aktivitas dan tidur"><span class="lbl"> Identifikasi pola aktivitas dan tidur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_observasi][]" id="tidur_observasi2" onclick="checkthis('tidur_observasi2')" value="Identifikasi faktor pengganggu tidur (fisik dan psikologis)"><span class="lbl"> Identifikasi faktor pengganggu tidur (fisik dan psikologis)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_observasi][]" id="tidur_observasi3" onclick="checkthis('tidur_observasi3')" value="Identifikasi makanan dan minuman yang mengganggu tidur"><span class="lbl"> Identifikasi makanan dan minuman yang mengganggu tidur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_observasi][]" id="tidur_observasi4" onclick="checkthis('tidur_observasi4')" value="Identifikasi obat tidur yang dikonsumsi"><span class="lbl"> Identifikasi obat tidur yang dikonsumsi</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik Dukungan Tidur -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_terapeutik][]" id="tidur_terapeutik1" onclick="checkthis('tidur_terapeutik1')" value="Modifikasi lingkungan (pencahayaan, suhu, kebisingan, dll)"><span class="lbl"> Modifikasi lingkungan (pencahayaan, suhu, kebisingan, dll)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_terapeutik][]" id="tidur_terapeutik2" onclick="checkthis('tidur_terapeutik2')" value="Batasi waktu tidur siang"><span class="lbl"> Batasi waktu tidur siang</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_terapeutik][]" id="tidur_terapeutik3" onclick="checkthis('tidur_terapeutik3')" value="Fasilitasi menghilangkan stress sebelum tidur"><span class="lbl"> Fasilitasi menghilangkan stress sebelum tidur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_terapeutik][]" id="tidur_terapeutik4" onclick="checkthis('tidur_terapeutik4')" value="Tetapkan jadwal tidur rutin"><span class="lbl"> Tetapkan jadwal tidur rutin</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_terapeutik][]" id="tidur_terapeutik5" onclick="checkthis('tidur_terapeutik5')" value="Sesuaikan jadwal pemberian obat/tindakan"><span class="lbl"> Sesuaikan jadwal pemberian obat/tindakan</span></label></div>
      </td>
    </tr>

    <!-- Edukasi Dukungan Tidur -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_edukasi][]" id="tidur_edukasi1" onclick="checkthis('tidur_edukasi1')" value="Jelaskan pentingnya tidur cukup"><span class="lbl"> Jelaskan pentingnya tidur cukup</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_edukasi][]" id="tidur_edukasi2" onclick="checkthis('tidur_edukasi2')" value="Anjurkan menepati kebiasaan waktu tidur"><span class="lbl"> Anjurkan menepati kebiasaan waktu tidur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_edukasi][]" id="tidur_edukasi3" onclick="checkthis('tidur_edukasi3')" value="Anjurkan menghindari makanan/minuman yang mengganggu tidur"><span class="lbl"> Anjurkan menghindari makanan/minuman yang mengganggu tidur</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_edukasi][]" id="tidur_edukasi4" onclick="checkthis('tidur_edukasi4')" value="Anjurkan penggunaan obat tidur yang tidak mengandung supressor"><span class="lbl"> Anjurkan penggunaan obat tidur yang tidak mengandung supressor</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[tidur_edukasi][]" id="tidur_edukasi5" onclick="checkthis('tidur_edukasi5')" value="Ajarkan relaksasi otot autogenik atau cara nonfarmakologi lainnya"><span class="lbl"> Ajarkan relaksasi otot autogenik atau cara nonfarmakologi lainnya</span></label></div>
      </td>
    </tr>

    <!-- EDUKASI AKTIVITAS/ISTIRAHAT -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Edukasi Aktivitas/Istirahat</b><br>
        <i>(Mengajarkan pengaturan aktivitas dan istirahat)</i><br>
        <b>(I.12362)</b>
      </td>
    </tr>

    <!-- Observasi Aktivitas/Istirahat -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[aktivitas_observasi][]" id="aktivitas_observasi1" onclick="checkthis('aktivitas_observasi1')" value="Identifikasi kesiapan dan kemampuan menerima informasi"><span class="lbl"> Identifikasi kesiapan dan kemampuan menerima informasi</span></label></div>
      </td>
    </tr>

    <!-- Terapeutik Aktivitas/Istirahat -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[aktivitas_terapeutik][]" id="aktivitas_terapeutik1" onclick="checkthis('aktivitas_terapeutik1')" value="Sediakan materi dan media pengaturan aktivitas dan istirahat"><span class="lbl"> Sediakan materi dan media pengaturan aktivitas dan istirahat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[aktivitas_terapeutik][]" id="aktivitas_terapeutik2" onclick="checkthis('aktivitas_terapeutik2')" value="Jadwalkan pemberian pendidikan kesehatan sesuai kesepakatan"><span class="lbl"> Jadwalkan pemberian pendidikan kesehatan sesuai kesepakatan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[aktivitas_terapeutik][]" id="aktivitas_terapeutik3" onclick="checkthis('aktivitas_terapeutik3')" value="Berikan kesempatan kepada pasien dan keluarga untuk bertanya"><span class="lbl"> Berikan kesempatan kepada pasien dan keluarga untuk bertanya</span></label></div>
      </td>
    </tr>

    <!-- Edukasi Aktivitas/Istirahat -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[aktivitas_edukasi][]" id="aktivitas_edukasi1" onclick="checkthis('aktivitas_edukasi1')" value="Jelaskan pentingnya melakukan aktivitas fisik/olahraga secara rutin"><span class="lbl"> Jelaskan pentingnya melakukan aktivitas fisik/olahraga secara rutin</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[aktivitas_edukasi][]" id="aktivitas_edukasi2" onclick="checkthis('aktivitas_edukasi2')" value="Anjurkan terlibat dalam aktivitas kelompok"><span class="lbl"> Anjurkan terlibat dalam aktivitas kelompok</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[aktivitas_edukasi][]" id="aktivitas_edukasi3" onclick="checkthis('aktivitas_edukasi3')" value="Ajarkan menyusun jadwal aktivitas dan istirahat"><span class="lbl"> Ajarkan menyusun jadwal aktivitas dan istirahat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_110[aktivitas_edukasi][]" id="aktivitas_edukasi4" onclick="checkthis('aktivitas_edukasi4')" value="Ajarkan cara mengidentifikasi kebutuhan istirahat"><span class="lbl"> Ajarkan cara mengidentifikasi kebutuhan istirahat</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<!-- END -->








<!-- ----- -->
<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <td style="width:33%; text-align:center;">
        Nama/Paraf
        <br><br>
        <span class="ttd-btn" data-role="petugas" id="ttd_petugas" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_petugas" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_110[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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