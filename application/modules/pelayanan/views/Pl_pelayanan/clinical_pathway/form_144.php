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
      var hiddenInputName = 'form_144[ttd_' + role + ']';
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
<table width="100%" border="1" style="border-collapse:collapse; font-size:13px;">
  <tr>
    <td style="width:15%; padding:5px;">Jam Masuk</td>
    <td style="width:35%; padding:5px;">:
      <div contenteditable="true" class="input_type" name="form_144[jam_masuk]" id="jam_masuk" onchange="fillthis('jam_masuk')" style="display:inline-block; min-width:100px;"></div>
    </td>
    <td style="width:15%; padding:5px;">Jam Keluar</td>
    <td style="width:35%; padding:5px;">:
      <div contenteditable="true" class="input_type" name="form_144[jam_keluar]" id="jam_keluar" onchange="fillthis('jam_keluar')" style="display:inline-block; min-width:100px;"></div>
    </td>
  </tr>
  <tr>
    <td style="padding:5px;">Tindakan Bedah</td>
    <td style="padding:5px;">:
      <div contenteditable="true" class="input_type" name="form_144[tindakan_bedah]" id="tindakan_bedah" onchange="fillthis('tindakan_bedah')" style="display:inline-block; min-width:200px; width:95%;"></div>
    </td>

    <td style="padding:5px;">Jenis Anestesi</td>
    <td style="padding:5px;">:
      <div contenteditable="true" class="input_type" name="form_144[jenis_anestesi]" id="jenis_anestesi" onchange="fillthis('jenis_anestesi')" style="display:inline-block; min-width:200px; width:95%;"></div>
    </td>
  </tr>
</table>


<!-- Bagian Monitoring -->
<table width="100%" border="1" style="border-collapse:separate; border-spacing:0; font-size:13px; margin-top:10px; border:1px solid #000;">
  <thead style="background:#e8e8e8;">
    <tr>
      <th style="text-align:center;padding:5px;border:1px solid #000;">TVS</th>
      <th style="text-align:center;padding:5px;border:1px solid #000;">R</th>
      <th style="text-align:center;padding:5px;border:1px solid #000;">N</th>
      <th style="text-align:center;padding:5px;border:1px solid #000;">TD</th>
      <th style="text-align:center;padding:5px;border:1px solid #000;">Keterangan</th>
    </tr>
  </thead>
  <tbody>
    <?php for ($i = 1; $i <= 12; $i++): ?>
    <tr>
      <td contenteditable="true" class="input_type" name="form_144[tvs_<?php echo $i; ?>]" id="tvs_<?php echo $i; ?>" onchange="fillthis('tvs_<?php echo $i; ?>')" style="text-align:center;"><?php echo isset($value_form['tvs_'.$i]) ? nl2br($value_form['tvs_'.$i]) : '' ?></td>
      <td contenteditable="true" class="input_type" name="form_144[r_<?php echo $i; ?>]" id="r_<?php echo $i; ?>" onchange="fillthis('r_<?php echo $i; ?>')" style="text-align:center;"><?php echo isset($value_form['r_'.$i]) ? nl2br($value_form['r_'.$i]) : '' ?></td>
      <td contenteditable="true" class="input_type" name="form_144[n_<?php echo $i; ?>]" id="n_<?php echo $i; ?>" onchange="fillthis('n_<?php echo $i; ?>')" style="text-align:center;"><?php echo isset($value_form['n_'.$i]) ? nl2br($value_form['n_'.$i]) : '' ?></td>
      <td contenteditable="true" class="input_type" name="form_144[td_<?php echo $i; ?>]" id="td_<?php echo $i; ?>" onchange="fillthis('td_<?php echo $i; ?>')" style="text-align:center;"><?php echo isset($value_form['td_'.$i]) ? nl2br($value_form['td_'.$i]) : '' ?></td>
      <td contenteditable="true" class="input_type" name="form_144[ket_<?php echo $i; ?>]" id="ket_<?php echo $i; ?>" onchange="fillthis('ket_<?php echo $i; ?>')" style="text-align:center;"><?php echo isset($value_form['ket_'.$i]) ? nl2br($value_form['ket_'.$i]) : '' ?></td>
    </tr>
    <?php endfor; ?>
  </tbody>
</table>
<br>
<!-- Keluar Kamar Pulih -->
<table width="100%" border="1" style="border-collapse:collapse; font-size:13px; margin-top:10px;">
  <tr>
    <td style="padding:5px;">Keluar kamar pulih : <br> Ke
      <label><input type="checkbox" class="ace" name="form_144[ruang_rawat]" id="ruang_rawat" value="Ruang Rawat" onclick="checkthis('ruang_rawat')"> <span class="lbl">Ruang Rawat</span></label>
      <label><input type="checkbox" class="ace" name="form_144[icu]" id="icu" value="ICU" onclick="checkthis('icu')"> <span class="lbl">ICU</span></label>
      <label><input type="checkbox" class="ace" name="form_144[langsung_pulang]" id="langsung_pulang" value="Langsung Pulang" onclick="checkthis('langsung_pulang')"> <span class="lbl">Langsung Pulang</span></label>
    </td>
  </tr>
</table>

<br>
<!-- Bagian Aldrette Score -->
<div style="font-size:14px;">
  <b>ALDRETTE SCORE</b><br>
</div>
<table class="table table-bordered" style="width:100%">
  <thead>
    <tr>
      <th class="text-center" style="width:5%">No</th>
      <th class="text-center" style="width:45%">Kriteria</th>
      <th class="text-center" style="width:20%">Nilai</th>
      <th class="text-center" style="width:30%">Keterangan</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="text-center">1</td>
      <td>Kesadaran (respon terhadap panggilan) <br>
        2 : Sadar, oritentasi baik <br>
        1 : Dapat dibangunkan <br>
        0 : Tak dapat dibangunkan
      </td>
      <td><input type="text" class="input_type" style="min-height:60px; width:99%; text-align:center;" name="form_144[nilai_kesadaran]" id="nilai_kesadaran" onchange="fillthis('nilai_kesadaran')"></td>
      <td><input type="text" class="input_type" style="min-height:60px; width:99%" name="form_144[keterangan_kesadaran]" id="keterangan_kesadaran" onchange="fillthis('keterangan_kesadaran')"></td>
    </tr>
    <tr>
      <td class="text-center">2</td>
      <td>Warna kulit (saturasi oksigen / sianosis) <br>
      2 : Pink tanpa O2 Sat O2 > 92% <br>
      1 : Pucat perlu O2 Sat O2 > 90% <br>
      0 : Sianosis : Sat O2 > 90%
      </td>
      <td><input type="text" class="input_type" style="min-height:60px; width:99%; text-align:center;" name="form_144[nilai_warnakulit]" id="nilai_warnakulit" onchange="fillthis('nilai_warnakulit')"></td>
      <td><input type="text" class="input_type" style="min-height:60px; width:99%" name="form_144[keterangan_warnakulit]" id="keterangan_warnakulit" onchange="fillthis('keterangan_warnakulit')"></td>
    </tr>
    <tr>
      <td class="text-center">3</td>
      <td>Aktivitas (kemampuan menggerakkan ekstremitas)<br>
      2 : 4 Ekstremitas bergerak<br>
      1 : 2 Ekstremitas bergerak<br>
      0 : Tidak ada gerak Ekstremitas
      </td>
      <td><input type="text" class="input_type" style="min-height:60px; width:99%; text-align:center;" name="form_144[nilai_aktivitas]" id="nilai_aktivitas" onchange="fillthis('nilai_aktivitas')"></td>
      <td><input type="text" class="input_type" style="min-height:60px; width:99%" name="form_144[keterangan_aktivitas]" id="keterangan_aktivitas" onchange="fillthis('keterangan_aktivitas')"></td>
    </tr>
    <tr>
      <td class="text-center">4</td>
      <td>Respirasi (kemampuan bernapas)<br>
      2 : Dapat bernafas dalam dan batuk<br>
      1 : Nafas dangkal dan sesak<br>
      0 : Opnoe dan Obstruksi
      </td>
      <td><input type="text" class="input_type" style="min-height:60px; width:99%; text-align:center;" name="form_144[nilai_respirasi]" id="nilai_respirasi" onchange="fillthis('nilai_respirasi')"></td>
      <td><input type="text" class="input_type" style="min-height:60px; width:99%" name="form_144[keterangan_respirasi]" id="keterangan_respirasi" onchange="fillthis('keterangan_respirasi')"></td>
    </tr>
    <tr>
      <td class="text-center">5</td>
      <td>Kardiovaskuler <br>
      2 : TD < 20% dari TD pre op<br>
      1 : TD 20% - 50% dari TD pre op<br>
      0 : TD > 50% TD hilang dari nilai TD pre op
      </td>
      <td><input type="text" class="input_type" style="min-height:60px; width:99%; text-align:center;" name="form_144[nilai_kardio]" id="nilai_kardio" onchange="fillthis('nilai_kardio')"></td>
      <td><input type="text" class="input_type" style="min-height:60px; width:99%" name="form_144[keterangan_kardio]" id="keterangan_kardio" onchange="fillthis('keterangan_kardio')"></td>
    </tr>
    <tr>
      <td class="text-center">6</td>
      <td style="text-align:center; vertical-align:middle;"><b>Jumlah Nilai Total</b></td>
      <td><input type="text" class="input_type" style="min-height:20px; width:99%; text-align:center; vertical-align:middle;" name="form_144[nilai_total]" id="nilai_total" onchange="fillthis('nilai_total')"></td>
      <td><input type="text" class="input_type" style="width:99%; vertical-align:middle;" name="form_144[keterangan_total]" id="keterangan_total" onchange="fillthis('keterangan_total')"></td>
    </tr>
  </tbody>
</table>

<div style="display: flex; flex-direction: column; margin-bottom: 10px;">
  <label for="catatan_ruang_pemulihan" style="margin-bottom: 5px; font-weight: bold;">
    Catatan Khusus Ruang Pemulihan
  </label>
  <div 
    contenteditable="true" 
    class="input_type" 
    id="catatan_ruang_pemulihan" 
    name="form_142[catatan_ruang_pemulihan]" 
    onchange="fillthis('catatan_ruang_pemulihan')"
    style="min-height: 60px; padding: 5px; border: 1px solid #ccc; border-radius: 4px;"
  >
    <?php echo isset($value_form['dpjp']) ? nl2br($value_form['dpjp']) : '' ?>
  </div>
</div>







<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <!-- Dokter Spesialis Anestesi -->
      <td style="width: 50%; text-align:center; padding:10px;">
        Dokter Spesialis Anestesi
        <br><br>
        <span class="ttd-btn" data-role="dokter_anestesi" id="ttd_dokter_anestesi" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_dokter_anestesi" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_144[nama_dokter_anestesi]" id="nama_dokter_anestesi" placeholder="Nama Dokter" style="width:90%; text-align:center;">
        <input type="hidden" name="form_144[ttd_dokter_anestesi]">
      </td>

      <!-- Perawat Anestesi / Perawat -->
      <td style="width: 50%; text-align:center; padding:10px;">
        Perawat Anestesi / Perawat
        <br><br>
        <span class="ttd-btn" data-role="perawat_anestesi" id="ttd_perawat_anestesi" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_perawat_anestesi" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_144[nama_perawat_anestesi]" id="nama_perawat_anestesi" placeholder="Nama Perawat" style="width:90%; text-align:center;">
        <input type="hidden" name="form_144[ttd_perawat_anestesi]">
      </td>
    </tr>
  </tbody>
</table>


<!-- <?php //echo $footer; ?> -->

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