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
  <b>RINGKASAN ASUHAN KEPERAWATAN BAYI PULANG</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <tbody>

    <!-- BAYI PULANG -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>BAYI PULANG:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[bayi_pulang][]" id="bayi_ijin_dokter" onclick="checkthis('bayi_ijin_dokter')" value="Dengan ijin dokter"><span class="lbl"> Dengan ijin dokter</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[bayi_pulang][]" id="bayi_permintaan_keluarga" onclick="checkthis('bayi_permintaan_keluarga')" value="Permintaan keluarga"><span class="lbl"> Permintaan keluarga</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[bayi_pulang][]" id="bayi_pindah_rs" onclick="checkthis('bayi_pindah_rs')" value="Pindah rumah sakit"><span class="lbl"> Pindah rumah sakit</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[bayi_pulang][]" id="bayi_meninggal" onclick="checkthis('bayi_meninggal')" value="Meninggal dunia"><span class="lbl"> Meninggal dunia</span></label></div>
      </td>
    </tr>

    <!-- KEADAAN WAKTU PULANG -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>KEADAAN WAKTU PULANG:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[keadaan_pulang][]" id="keadaan_baik" onclick="checkthis('keadaan_baik')" value="Baik"><span class="lbl"> Baik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[keadaan_pulang][]" id="keadaan_tidak_baik" onclick="checkthis('keadaan_tidak_baik')" value="Tidak baik"><span class="lbl"> Tidak baik</span></label></div>
      </td>
    </tr>

    <!-- JENIS KELAMIN -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>JENIS KELAMIN:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[jenis_kelamin][]" id="jk_l" onclick="checkthis('jk_l')" value="Laki-laki"><span class="lbl"> Laki-laki</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[jenis_kelamin][]" id="jk_p" onclick="checkthis('jk_p')" value="Perempuan"><span class="lbl"> Perempuan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[jenis_kelamin][]" id="jk_ragu" onclick="checkthis('jk_ragu')" value="Diragukan"><span class="lbl"> Diragukan</span></label></div>
      </td>
    </tr>

    <!-- BERAT BADAN -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>BERAT BADAN:</b><br>
        Berat badan lahir : 
        <input type="text" class="input_type" style="width: 80px; text-align: center;" 
               name="form_114[bb_lahir]" id="bb_lahir" onchange="fillthis('bb_lahir')"> gr &nbsp;&nbsp;
        <br>Berat badan minimum : 
        <input type="text" class="input_type" style="width: 80px; text-align: center;" 
               name="form_114[bb_min]" id="bb_min" onchange="fillthis('bb_min')"> gr &nbsp;&nbsp;
        <br>Berat badan pulang : 
        <input type="text" class="input_type" style="width: 80px; text-align: center;" 
               name="form_114[bb_pulang]" id="bb_pulang" onchange="fillthis('bb_pulang')"> gr
      </td>
    </tr>

    <!-- KULIT -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>KULIT:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[kulit][]" id="kulit_ikterik" onclick="checkthis('kulit_ikterik')" value="Ikterik"><span class="lbl"> Ikterik; Bilirubin </span></label>
        <input type="text" class="input_type" style="width: 60px; text-align: center;" name="form_114[kulit_bilirubin]" id="kulit_bilirubin" onchange="fillthis('kulit_bilirubin')"></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[kulit][]" id="kulit_merah" onclick="checkthis('kulit_merah')" value="Merah / rash"><span class="lbl"> Merah / rash</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[kulit][]" id="kulit_terkelupas" onclick="checkthis('kulit_terkelupas')" value="Terkelupas"><span class="lbl"> Terkelupas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[kulit][]" id="kulit_ptekle" onclick="checkthis('kulit_ptekle')" value="Ptekle"><span class="lbl"> Ptekle</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[kulit][]" id="kulit_kering" onclick="checkthis('kulit_kering')" value="Kering"><span class="lbl"> Kering</span></label></div>
      </td>
    </tr>

    <!-- TALI PUSAT -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>TALI PUSAT:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[tali_pusat][]" id="pusat_kering" onclick="checkthis('pusat_kering')" value="Kering"><span class="lbl"> Kering</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[tali_pusat][]" id="pusat_basah" onclick="checkthis('pusat_basah')" value="Basah"><span class="lbl"> Basah</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[tali_pusat][]" id="pusat_bodong" onclick="checkthis('pusat_bodong')" value="Bodong"><span class="lbl"> Bodong</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[tali_pusat][]" id="pusat_radang" onclick="checkthis('pusat_radang')" value="Radang"><span class="lbl"> Radang</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[tali_pusat][]" id="pusat_lepas" onclick="checkthis('pusat_lepas')" value="Sudah lepas"><span class="lbl"> Sudah lepas</span></label></div>
      </td>
    </tr>

    <!-- MINUM -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>MINUM:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[minum][]" id="minum_asi" onclick="checkthis('minum_asi')" value="ASI"><span class="lbl"> ASI</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[minum][]" id="minum_pasi" onclick="checkthis('minum_pasi')" value="PASI"><span class="lbl"> PASI</span></label></div>
      </td>
    </tr>

    <!-- MIKSI -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>MIKSI:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[miksi][]" id="miksi_normal" onclick="checkthis('miksi_normal')" value="Normal"><span class="lbl"> Normal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[miksi][]" id="miksi_tidak_normal" onclick="checkthis('miksi_tidak_normal')" value="Tidak normal"><span class="lbl"> Tidak normal</span></label></div>
      </td>
    </tr>

    <!-- DEFEKASI -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>DEFEKASI:</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[defekasi][]" id="defekasi_normal" onclick="checkthis('defekasi_normal')" value="Normal"><span class="lbl"> Normal</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_114[defekasi][]" id="defekasi_tidak_normal" onclick="checkthis('defekasi_tidak_normal')" value="Tidak normal"><span class="lbl"> Tidak normal</span></label></div>
      </td>
    </tr>

   <!-- KELAINAN
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Kelainan :</b><br>
    <textarea class="textarea-type"
              name="form_114[kelainan]"
              id="kelainan"
              onchange="fillthis('kelainan')"
              style="width: 100%; height: 80px !important;"><?php echo isset($value_form['kelainan']) ? $value_form['kelainan'] : '' ?></textarea>
  </td>
</tr> -->

<!-- KELAINAN -->
<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px; vertical-align: top;">
    <b>Kelainan :</b><br>
    <div contenteditable="true"
         class="input_type"
         name="form_114[kelainan]"
         id="kelainan"
         onchange="fillthis('kelainan')"
         style="width: 100%; min-height: 100px; white-space: pre-wrap; word-wrap: break-word; overflow: visible; border: 1px solid #ccc; padding: 5px;">
      <?php echo isset($value_form['kelainan']) ? nl2br($value_form['kelainan']) : '' ?>
    </div>
  </td>
</tr>

<!-- OBAT-OBAT -->
<!-- <tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <b>Obat-obat yang dibawa pulang :</b><br>
    <textarea class="textarea-type"
              name="form_114[obat_pulang]"
              id="obat_pulang"
              onchange="fillthis('obat_pulang')"
              style="width: 100%; height: 80px !important;"><?php echo isset($value_form['obat_pulang']) ? $value_form['obat_pulang'] : '' ?></textarea>
  </td>
</tr> -->

<tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px; vertical-align: top;">
    <b>Obat-obat yang dibawa pulang :</b><br>
    <div contenteditable="true"
         class="input_type"
         name="form_114[obat_pulang]"
         id="obat_pulang"
         onchange="fillthis('obat_pulang')"
         style="width: 100%; min-height: 100px; white-space: pre-wrap; word-wrap: break-word; overflow: visible; border: 1px solid #ccc; padding: 5px;">
      <?php echo isset($value_form['obat_pulang']) ? nl2br($value_form['obat_pulang']) : '' ?>
    </div>
  </td>
</tr>

  </tbody>
</table>
<!---- END --->


<br>

<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <td style="width:33%; text-align:center;">
        Perawat yang mengisi,
        <br><br>
        <span class="ttd-btn" data-role="petugas" id="ttd_petugas" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_petugas" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_114[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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