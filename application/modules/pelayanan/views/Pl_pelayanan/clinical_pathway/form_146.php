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
      var hiddenInputName = 'form_146[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 25 november 2025</p> -->
<div style="text-align: center; font-size: 18px;"><b>INFORMED CONSENT</b></div>
<br>

<!-- ==================================== -->
<table width="100%" style="border-collapse:collapse; font-size:14px;">
    <tr>
    <td style="width:10%; padding:5px;">Nama</td>
    <td style="padding:5px;">
      <!-- <div contenteditable="true" class="input_type" 
           name="form_146[nama]" id="nama" 
           onchange="fillthis('nama')">
        <?php echo isset($value_form['nama']) ? nl2br($value_form['nama']) : '' ?>
      </div> -->
      <input type="text" class="input_type" style="width: 100%;" name="form_146[nama_pasien]" id="nama_pasien" onchange="fillthis('nama_pasien')" 
      value="<?php $nama_pasien = isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; echo isset($value_form['nama_pasien'])?$value_form['nama_pasien']:$nama_pasien?>">
    </td>
    </tr>
    
    <tr>
    <td style="width:10%; padding:5px;">Jenis Kelamin</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_146[jk_l]" id="jk_l" onclick="checkthis('jk_l')" 
        <?php $jk = isset($data_pasien->jen_kelamin)?$data_pasien->jen_kelamin:''; echo ($jk=='L')?'checked':'';?>> 
        <span class="lbl"> Laki-laki</span>
      </label>
      &nbsp;&nbsp;
      <label>
        <input type="checkbox" class="ace" name="form_146[jk_p]" id="jk_p" onclick="checkthis('jk_p')" 
        <?php echo ($jk=='P')?'checked':'';?>> 
        <span class="lbl"> Perempuan</span>
      </label>
    </td>
    </tr>

    <!-- <tr>
    <td style="width:10%; padding:5px;">Umur</td>
    <td style="padding:5px;">
      <div contenteditable="true" class="input_type" 
           name="form_146[umur]" id="umur" 
           onchange="fillthis('umur')">
        <?php echo isset($value_form['umur']) ? nl2br($value_form['umur']) : '' ?>
      </div>
    </td>
    </tr> -->

  <tr>
   <td style="width:10%; padding:5px;">Tempat / Tanggal Lahir</td>
   <td>
    <input 
        type="text" 
        class="input_type" 
        style="width: 120px !important;" 
        name="form_146[tempat_lahir]" 
        id="tempat_lahir"
        value="<?php 
          echo isset($value_form['tempat_lahir']) 
            ? $value_form['tempat_lahir'] 
            : (isset($data_pasien->tempat_lahir) ? $data_pasien->tempat_lahir : '');
        ?>"
      >
      , 
      <input 
        type="text" 
        class="input_type date-picker" 
        data-date-format="yyyy-mm-dd"
        style="width: 120px !important;" 
        name="form_146[tanggal_lahir]" 
        id="tanggal_lahir"
        value="<?php 
          $tgl_lhr = isset($data_pasien->tgl_lhr) ? $data_pasien->tgl_lhr : ''; 
          if (!empty($tgl_lhr)) {
            $tgl_lhr = date('Y-m-d', strtotime($tgl_lhr));
          }
          echo isset($value_form['tanggal_lahir']) ? $value_form['tanggal_lahir'] : $tgl_lhr; 
        ?>"
      >
  </td>
  </tr>

  <tr>
   <td style="width:10%; padding:5px;">Alamat</td>
    <td colspan="5" style="padding:5px;">
      <!-- <div contenteditable="true" class="input_type" 
           name="form_146[alamat]" id="alamat" 
           onchange="fillthis('alamat')">
        <?php echo isset($value_form['alamat']) ? nl2br($value_form['alamat']) : '' ?>
      </div> -->
      <input type="text" class="input_type" style="width: 100%;" name="form_146[alamat]" id="alamat" onchange="fillthis('alamat')" 
      value="<?php $almt = isset($data_pasien->almt_ttp_pasien)?$data_pasien->almt_ttp_pasien:''; echo isset($value_form['alamat'])?$value_form['alamat']:$almt?>">
    </td>
    </td>
  </tr>
</table>

<br>

<div style="font-size:14px; line-height:1.6; text-align:justify;">

  Sebelum mengikuti test saya sudah menjalani evaluasi klinis, pemeriksaan dokter dan pemeriksaan penunjang lainnya dengan maksud supaya dokter mengetahui status kesehatan saya.
  <br><br>

  Evaluasi dengan stress test akan saya jalani dengan dosis latihan yang ditetapkan berdasar toleransi yang saya capai.
  <br><br>

  Berdasar pembebanan fisik selama test menurut suatu petunjuk pelaksanaan protocol Bruce atau modifikasinya yang dipakai secara internasional.
  <br><br>

  Berbagai kemungkinan bisa terjadi selama test tersebut seperti: risiko tekanan darah dan denyut jantung abnormal, mungkin serangan jantung meskipun jarang terjadi. Apapun ada risikonya :
  untuk menghapuskan atau memperkecil risiko tersebut.
  <br><br>

  Saya mentaati petunjuk yang diberikan.
  <br><br>

  Dokter telah mempelajari status kesehatan saya, sehingga mengambil kesimpulan bahwa stress test dapat dilakukan.
  <br><br>

  Stress test dilakukan oleh seorang DSJP (Dokter Spesialis Jantung dan Pembuluh Darah) / Dokter yang berkompeten melakukan stress test dan dibantu seorang perawat yang sudah berpengalaman.
  <br><br>

  Tersedia obat-obatan dan defibrilator yang selalu siap pakai bila ada keadaan darurat jantung.
  <br><br>

  Data dan hasil Stress Test saya dapat digunakan untuk bahan pendidikan, penelitian, makalah, slides, dan sebagainya serta dapat dipublikasikan secara nasional maupun internasional dengan merahasiakan identitas saya.
  <br><br>

  Saya sudah membaca, memahami, menyetujui semua yang tersebut diatas dan menerima semua itu dengan penuh kesadaran.
  <br><br><br>

</div>
<!-- END -->


<!-- ----- -->
<!-- TANDA TANGAN -->
<table class="table" style="width: 100%; border-collapse:collapse;">
  <tbody>
    <tr>
      <!-- Kolom TTD -->
      <td style="width:50%; text-align:center;">
        Mengetahui
        <br><br>
        <span class="ttd-btn" data-role="menyerahkan" id="ttd_menyerahkan" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_menyerahkan" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" name="form_146[nama_menyerahkan]" id="nama_menyerahkan" class="input_type" placeholder="Nama" style="width:150px; text-align:center;">
        <br>
        (Tanda Tangan dan Nama Jelas)
      </td>

      <!-- Kolom Perawat Sirkuler -->
      <td style="width:50%; text-align:center;">
        Yang menyatakan
        <br><br>
        <span class="ttd-btn" data-role="menerima" id="ttd_menerima" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_menerima" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" name="form_146[nama_menerima]" id="nama_menerima" class="input_type" placeholder="Nama" style="width:150px; text-align:center;">
        <br>
        (Tanda Tangan dan Nama Jelas)
      </td>
    </tr>
  </tbody>
</table>

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