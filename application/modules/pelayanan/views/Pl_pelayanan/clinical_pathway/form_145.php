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
      var hiddenInputName = 'form_145[ttd_' + role + ']';
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
<div style="text-align: center; font-size: 18px;"><b>PETUNJUK ECHO TREADMILL</b></div>
<br>

<!-- ================== PILIHAN JENIS TES ================== -->
<table width="100%" style="border-collapse:collapse; font-size:14px;" border="0">
  <tr>
    <td style="padding:5px;">
      <label>
        <input type="checkbox" class="ace" name="form_145[tes_treadmill]" id="tes_treadmill" onclick="checkthis('tes_treadmill')">
        <span class="lbl"> Treadmill Test</span>
      </label>
    </td>
  </tr>

  <tr>
    <td style="padding:5px;">
      <label>
        <input type="checkbox" class="ace" name="form_145[ergocycle]" id="ergocycle" onclick="checkthis('ergocycle')">
        <span class="lbl"> Ergocycle Stress Test</span>
      </label>
    </td>
  </tr>

  <tr>
    <td style="padding:5px;">
      <label>
        <input type="checkbox" class="ace" name="form_145[dobutamin]" id="dobutamin" onclick="checkthis('dobutamin')">
        <span class="lbl"> Dobutamin Echo</span>
      </label>
    </td>
  </tr>

  <tr>
    <td style="padding:5px;">
      <label>
        <input type="checkbox" class="ace" name="form_145[exercise]" id="exercise" onclick="checkthis('exercise')">
        <span class="lbl"> Exercise Stress Echo</span>
      </label>
    </td>
  </tr>
</table>

<br>

<!-- ================== PETUNJUK ================== -->
<div style="font-size:14px;">
  <b>Ikutilah petunjuk - petunjuk sebagai berikut :</b><br><br>

  <ol style="margin-left:15px; line-height:1.5;">
    <li>Satu hari sebelum test, tidak boleh melakukan olahraga atau kerja berat.</li>
    <li>Obat tidak usah dihentikan / obat dihentikan sebelum stress test atas petunjuk dokter.</li>
    <li>Tidur cukup pada malam hari sebelum test.</li>
    <li>Memakai pakaian yang sesuai (training pack) boleh dipakai sejak dari rumah.</li>
    <li>Memakai sepatu olah raga yang alasnya bersih menghindari pengotoran alat.</li>
    <li>Kurang lebih setengah jam sebelumnya sudah ada di kamar tunggu.</li>
    <li>Sarapan ringan, tidak merokok, tidak minum kopi pada hari test.</li>
    <li>Setelah dibaca dan menyetujui isi Informed Consent membubuhkan tanda tangan.</li>
    <li>
      Data medis seperti : Kuisioner yang telah diisi; catatan pemeriksaan fisik, foto torax,
      EKG, Laboratorium, Echo Jantung (bila ada) disediakan oleh petugas untuk dipelajari
      dokter jantung yang akan melakukan test tersebut.
    </li>
    <li>Training dan sepatu olah raga tidak perlu untuk dobutamine Stress Echo.</li>
  </ol>
</div>
<!-- END -->


<!-- ----- -->
<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <td style="width:33%; text-align:center;">
        Dokter Jantung & Pemb. Darah
        <br><br>
        <span class="ttd-btn" data-role="petugas" id="ttd_petugas" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_petugas" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_145[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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