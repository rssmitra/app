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
      var hiddenInputName = 'form_76[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 07 oktober 2025</p> -->

<div style="text-align: center; font-size: 18px;">
  <b>DIAGNOSIS KEPERAWATAN: GANGGUAN VENTILASI SPONTAN</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
        <b>Definisi:</b> Penurunan cadangan energi yang mengakibatkan individu tidak mampu bernapas secara adekuat
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[penyebab][]" id="penyebab_metabolisme" onclick="checkthis('penyebab_metabolisme')" value="Gangguan Metabolisme">
            <span class="lbl"> Gangguan Metabolisme</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[penyebab][]" id="penyebab_kelemahan" onclick="checkthis('penyebab_kelemahan')" value="Kelemahan otot pernapasan">
            <span class="lbl"> Kelemahan otot pernapasan</span>
          </label>
        </div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama
          <input type="text" class="input_type" name="form_76[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Ventilasi Spontan (L.02015) meningkat dengan kriteria hasil:</b>
        
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[kriteria_hasil][]" id="hasil_tidal" onclick="checkthis('hasil_tidal')" value="Volume tidal meningkat"><span class="lbl"> Volume tidal meningkat*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[kriteria_hasil][]" id="hasil_dispnea" onclick="checkthis('hasil_dispnea')" value="Dispnea menurun"><span class="lbl"> Dispnea menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[kriteria_hasil][]" id="hasil_otot_bantu" onclick="checkthis('hasil_otot_bantu')" value="Penggunaan otot bantu napas menurun"><span class="lbl"> Penggunaan otot bantu napas menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[kriteria_hasil][]" id="hasil_gelisah" onclick="checkthis('hasil_gelisah')" value="Gelisah menurun"><span class="lbl"> Gelisah menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[kriteria_hasil][]" id="hasil_pco2" onclick="checkthis('hasil_pco2')" value="PCO2 membaik"><span class="lbl"> PCO2 membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[kriteria_hasil][]" id="hasil_po2" onclick="checkthis('hasil_po2')" value="PO2 membaik"><span class="lbl"> PO2 membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[kriteria_hasil][]" id="hasil_takikardia" onclick="checkthis('hasil_takikardia')" value="Takikardia membaik"><span class="lbl"> Takikardia membaik</span></label></div>
      </td>
    </tr>

    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dibuktikan dengan:</b><br>
        <p><b>Tanda dan Gejala Mayor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[mayor_subjektif][]" id="mayor_dispnea" onclick="checkthis('mayor_dispnea')" value="Dispnea"><span class="lbl"> Dispnea</span></label></div>
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[mayor_objektif][]" id="mayor_otot_bantu" onclick="checkthis('mayor_otot_bantu')" value="Penggunaan otot bantu napas meningkat"><span class="lbl"> Penggunaan otot bantu napas meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[mayor_objektif][]" id="mayor_tidal" onclick="checkthis('mayor_tidal')" value="Volume tidal menurun"><span class="lbl"> Volume tidal menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[mayor_objektif][]" id="mayor_pco2" onclick="checkthis('mayor_pco2')" value="PCO2 meningkat"><span class="lbl"> PCO2 meningkat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[mayor_objektif][]" id="mayor_po2" onclick="checkthis('mayor_po2')" value="PO2 menurun"><span class="lbl"> PO2 menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[mayor_objektif][]" id="mayor_sao2" onclick="checkthis('mayor_sao2')" value="SaO2 menurun"><span class="lbl"> SaO2 menurun</span></label></div>
          </div>
        </div>

        <hr>
        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <p>(Tidak tersedia)</p>
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[minor_objektif][]" id="minor_gelisah" onclick="checkthis('minor_gelisah')" value="Gelisah"><span class="lbl"> Gelisah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_76[minor_objektif][]" id="minor_takikardi" onclick="checkthis('minor_takikardi')" value="Takikardi"><span class="lbl"> Takikardi</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->



<!-- DUKUNGAN VENTILASI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Dukungan Ventilasi -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dukungan Ventilasi</b><br>
        <i>(Memfasilitasi dalam mempertahankan pernapasan spontan untuk memaksimalkan pertukaran gas di paru)</i><br>
        <b>(I.01002)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[dv_observasi][]" id="dv_observasi_1" onclick="checkthis('dv_observasi_1')" value="Identifikasi kelelahan otot bantu napas">
            <span class="lbl"> Identifikasi adanya kelelahan otot bantu napas</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[dv_observasi][]" id="dv_observasi_2" onclick="checkthis('dv_observasi_2')" value="Identifikasi efek perubahan posisi">
            <span class="lbl"> Identifikasi efek perubahan posisi terhadap status pernapasan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[dv_observasi][]" id="dv_observasi_3" onclick="checkthis('dv_observasi_3')" value="Monitor status respirasi dan oksigenasi">
            <span class="lbl"> Monitor status respirasi dan oksigenasi (frekuensi, kedalaman napas, penggunaan otot bantu napas, bunyi napas tambahan, saturasi oksigen)</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[dv_terapeutik][]" id="dv_terapeutik_1" onclick="checkthis('dv_terapeutik_1')" value="Pertahankan kepatenan jalan napas">
            <span class="lbl"> Pertahankan kepatenan jalan napas</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[dv_terapeutik][]" id="dv_terapeutik_2" onclick="checkthis('dv_terapeutik_2')" value="Posisi semi fowler atau fowler">
            <span class="lbl"> Berikan posisi semi fowler atau fowler</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[dv_terapeutik][]" id="dv_terapeutik_3" onclick="checkthis('dv_terapeutik_3')" value="Fasilitasi ubah posisi senyaman mungkin">
            <span class="lbl"> Fasilitasi mengubah posisi senyaman mungkin</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[dv_terapeutik][]" id="dv_terapeutik_4" onclick="checkthis('dv_terapeutik_4')" value="Berikan oksigen sesuai kebutuhan">
            <span class="lbl"> Berikan oksigen sesuai kebutuhan (mis. nasal kanul, masker wajah, masker rebreathing atau non-rebreathing)</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[dv_terapeutik][]" id="dv_terapeutik_5" onclick="checkthis('dv_terapeutik_5')" value="Gunakan bag-valve mask jika perlu">
            <span class="lbl"> Gunakan bag-valve mask, jika perlu</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[dv_edukasi][]" id="dv_edukasi_1" onclick="checkthis('dv_edukasi_1')" value="Ajarkan teknik relaksasi">
            <span class="lbl"> Ajarkan melakukan teknik relaksasi</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[dv_edukasi][]" id="dv_edukasi_2" onclick="checkthis('dv_edukasi_2')" value="Ajarkan ubah posisi mandiri">
            <span class="lbl"> Ajarkan mengubah posisi secara mandiri</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[dv_edukasi][]" id="dv_edukasi_3" onclick="checkthis('dv_edukasi_3')" value="Ajarkan teknik batuk efektif">
            <span class="lbl"> Ajarkan teknik batuk efektif</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_76[dv_kolaborasi][]" id="dv_kolaborasi_1" onclick="checkthis('dv_kolaborasi_1')" value="Kolaborasi pemberian bronkodilator">
            <span class="lbl"> Kolaborasi pemberian bronkodilator, jika perlu</span>
          </label>
        </div>
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
        <input type="text" class="input_type" name="form_76[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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