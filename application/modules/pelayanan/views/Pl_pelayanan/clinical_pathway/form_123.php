<?php echo $header; ?>
<hr><br>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<body>

<div style="text-align: center; font-size: 16px;">
  <b>KRITERIA PASIEN MASUK DAN KELUAR NICU</b>
</div>
<br>
<!-- KRITERIA PASIEN MASUK DAN KELUAR NICU -->
<div style="font-size: 12px; font-weight: bold; margin-bottom: 10px;">
  II. KRITERIA PASIEN MASUK DAN KELUAR NICU
</div>

<table border="0" width="100%" style="border-collapse: collapse; font-size: 12px; text-align: left;">
  <tr>
    <!-- Tabel Kiri: Masuk NICU -->
    <td style="vertical-align: top; width: 50%;">
      <table border="1" width="90%" style="font-size: 12px; margin-right: 15px;">
        <thead style="text-align:center; font-weight:bold; background-color:#e9e9e9;">
          <tr>
            <th style="width:30px;vertical-align:middle; text-align: center;">No.</th>
            <th style="vertical-align:middle; text-align: center;">Kriteria</th>
            <th style="width:70px;vertical-align:middle; text-align: center;">Check<br>List</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="text-align:center;padding:5px;">1</td>
            <td style="padding:5px;">Bayi dengan risiko henti napas (apnea)</td>
            <td style="text-align:center;padding:5px;">
              <label><input type="checkbox" class="ace" id="apnea" onclick="checkthis('apnea')" name="form_123[masuk][]" value="1"> <span class="lbl"></span></label>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;padding:5px;">2</td>
            <td style="padding:5px;">Bayi dengan kejang lama atau berulang</td>
            <td style="text-align:center;padding:5px;">
              <label><input type="checkbox" class="ace" id="kejang_berulang" onclick="checkthis('kejang_berulang')" name="form_123[masuk][]" value="2"> <span class="lbl"></span></label>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;padding:5px;">3</td>
            <td style="padding:5px;">Memerlukan bantuan ventilasi mekanik</td>
            <td style="text-align:center;padding:5px;">
              <label><input type="checkbox" class="ace" id="ventilasi" onclick="checkthis('ventilasi')" name="form_123[masuk][]" value="3"> <span class="lbl"></span></label>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;padding:5px;">4</td>
            <td style="padding:5px;">Bayi dengan asfiksia berat</td>
            <td style="text-align:center;padding:5px;">
              <label><input type="checkbox" class="ace" id="asfiksia" onclick="checkthis('asfiksia')" name="form_123[masuk][]" value="4"> <span class="lbl"></span></label>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;padding:5px;">5</td>
            <td style="padding:5px;">Bayi dengan shock</td>
            <td style="text-align:center;padding:5px;">
              <label><input type="checkbox" class="ace" id="shock" onclick="checkthis('shock')" name="form_123[masuk][]" value="5"> <span class="lbl"></span></label>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;padding:5px;">6</td>
            <td style="padding:5px;">Bayi dengan pemakaian obat inotropic</td>
            <td style="text-align:center;padding:5px;">
              <label><input type="checkbox" class="ace" id="inotropik" onclick="checkthis('inotropik')" name="form_123[masuk][]" value="6"> <span class="lbl"></span></label>
            </td>
          </tr>
        </tbody>
      </table>

      <br><br>
      <div style="font-size:13px;">
        Tanggal masuk NICU : 
        <!-- <input type="text" class="input_type" name="form_123[tgl_masuk]" id="tgl_masuk" style="width:20px;"> /
        <input type="text" class="input_type" name="form_123[bln_masuk]" id="bln_masuk" style="width:20px;"> /
        <input type="text" class="input_type" name="form_123[thn_masuk]" id="thn_masuk" style="width:50px;"> -->
        <input type="text" class="input_type date-picker" data-date-format="yyyy-mm-dd" name="form_123[tgl_masuk]" id="tgl_masuk" onchange="fillthis('tgl_masuk')" value="<?php echo isset($value_form['tgl_masuk'])?$value_form['tgl_masuk']:date('Y-m-d')?>"> 
        <br>Pukul : <input type="text" class="input_type" name="form_123[jam_masuk]" id="jam_masuk" style="width:100px;">
        
      </div>
    </td>

    <!-- Tabel Kanan: Keluar NICU -->
    <td style="vertical-align: top; width: 50%;">
      <table border="1" width="90%" style="border-collapse: collapse; font-size: 12px; margin-left: 15px;">
        <thead style="text-align:center; font-weight:bold; background-color:#e9e9e9;">
          <tr>
            <th style="width:30px;vertical-align:middle; text-align: center;">No.</th>
            <th style="vertical-align:middle; text-align: center;">Kriteria</th>
            <th style="width:70px;vertical-align:middle; text-align: center;">Check<br>List</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="text-align:center;padding:5px;">1</td>
            <td style="padding:5px;">Tidak perlu obat vasoaktif</td>
            <td style="text-align:center;padding:5px;">
              <label><input type="checkbox" class="ace" id="tanpa_vasoaktif" onclick="checkthis('tanpa_vasoaktif')" name="form_123[keluar][]" value="1"> <span class="lbl"></span></label>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;padding:5px;">2</td>
            <td style="padding:5px;">Tidak perlu bantuan ventilasi mekanik</td>
            <td style="text-align:center;padding:5px;">
              <label><input type="checkbox" class="ace" id="tanpa_ventilasi" onclick="checkthis('tanpa_ventilasi')" name="form_123[keluar][]" value="2"> <span class="lbl"></span></label>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;padding:5px;">3</td>
            <td style="padding:5px;">Hemodinamik stabil</td>
            <td style="text-align:center;padding:5px;">
              <label><input type="checkbox" class="ace" id="hemodinamik_stabil" onclick="checkthis('hemodinamik_stabil')" name="form_123[keluar][]" value="3"> <span class="lbl"></span></label>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;padding:5px;">4</td>
            <td style="padding:5px;">Perbaikan penyakit</td>
            <td style="text-align:center;padding:5px;">
              <label><input type="checkbox" class="ace" id="perbaikan_penyakit" onclick="checkthis('perbaikan_penyakit')" name="form_123[keluar][]" value="4"> <span class="lbl"></span></label>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;padding:5px;">5</td>
            <td style="padding:5px;">Menolak perawatan lebih lanjut di NICU / DNR</td>
            <td style="text-align:center;padding:5px;">
              <label><input type="checkbox" class="ace" id="menolak_perawatan" onclick="checkthis('menolak_perawatan')" name="form_123[keluar][]" value="5"> <span class="lbl"></span></label>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;padding:5px;">6</td>
            <td style="padding:5px;">Meninggal</td>
            <td style="text-align:center;padding:5px;">
              <label><input type="checkbox" class="ace" id="meninggal" onclick="checkthis('meninggal')" name="form_123[keluar][]" value="6"> <span class="lbl"></span></label>
            </td>
          </tr>
        </tbody>
      </table>

      <br>
      <div style="font-size:13px;">
        Tanggal keluar NICU : 
        <!-- <input type="text" class="input_type" name="form_123[tgl_keluar]" id="tgl_keluar" style="width:20px;"> /
        <input type="text" class="input_type" name="form_123[bln_keluar]" id="bln_keluar" style="width:20px;"> /
        <input type="text" class="input_type" name="form_123[thn_keluar]" id="thn_keluar" style="width:50px;"> -->
        <input type="text" class="input_type date-picker" data-date-format="yyyy-mm-dd" name="form_123[tgl_keluar]" id="tgl_keluar" onchange="fillthis('tgl_keluar')" value="<?php echo isset($value_form['tgl_keluar'])?$value_form['tgl_operasi']:date('Y-m-d')?>"> 
        <br>Pukul : <input type="text" class="input_type" name="form_123[jam_keluar]" id="jam_keluar" style="width:100px;">
      </div>
    </td>
  </tr>
</table>


<br>
<!-- TANDA TANGAN -->
<table class="table" style="width: 100%; border-collapse:collapse;">
  <tbody>
    <tr>
      <!-- Kolom DPJP / Dokter IGD / Dokter Jaga -->
      <td style="width:50%; text-align:center;">
        DPJP / Dokter IGD / Dokter Jaga
        <br><br>
        <span class="ttd-btn" data-role="dokter_igd" id="ttd_dokter_igd" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_dokter_igd" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" name="form_123[nama_dokter_igd]" id="nama_dokter_igd" class="input_type" placeholder="Nama Dokter" style="width:150px; text-align:center;">
        <br>
        (Tanda Tangan dan Nama Jelas)
      </td>

      <!-- Kolom DPJP -->
      <td style="width:50%; text-align:center;">
        DPJP
        <br><br>
        <span class="ttd-btn" data-role="dpjp" id="ttd_dpjp" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_dpjp" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" name="form_123[nama_dpjp]" id="nama_dpjp" class="input_type" placeholder="Nama Dokter" style="width:150px; text-align:center;">
        <br>
        (Tanda Tangan dan Nama Jelas)
      </td>
    </tr>
  </tbody>
</table>


<br><br>

<!-- Modal Tanda Tangan Digital -->
<div class="modal fade" id="ttdModal" tabindex="-1" role="dialog" aria-labelledby="ttdModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="ttdModalLabel" style="color: white;">Tanda Tangan Digital</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="text-align:center;">
        <canvas id="ttd-canvas" style="border:1px solid #ccc; touch-action:none;" width="350" height="120"></canvas>
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
      var hiddenInputName = 'form_123[ttd_' + role + ']';
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

<?php //echo $footer; ?>