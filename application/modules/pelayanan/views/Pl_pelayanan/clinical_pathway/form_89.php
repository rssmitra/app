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
      var hiddenInputName = 'form_89[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 09 oktober 2025</p> -->
<div style="text-align: center; font-size: 18px;">
  <b>DIAGNOSIS KEPERAWATAN: RISIKO KETIDAKSEIMBANGAN ELEKTROLIT</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 6px;" colspan="2">
        <b>Definisi :</b><br>
        Berisiko mengalami perubahan kadar serum elektrolit.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- FAKTOR RISIKO -->
      <td style="border: 1px solid black; padding: 6px; vertical-align: top; width: 50%;">
        <b>FAKTOR RISIKO (Dibuktikan dengan):</b><br>

        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_89[faktor_risiko][]" id="faktor_cairan" onclick="checkthis('faktor_cairan')" value="Ketidakseimbangan cairan"><span class="lbl"> Ketidakseimbangan cairan (mis. dehidrasi dan intoksikasi air)</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_89[faktor_risiko][]" id="faktor_kelebihan_cairan" onclick="checkthis('faktor_kelebihan_cairan')" value="Kelebihan volume cairan"><span class="lbl"> Kelebihan volume cairan</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_89[faktor_risiko][]" id="faktor_regulasi" onclick="checkthis('faktor_regulasi')" value="Gangguan mekanisme regulasi"><span class="lbl"> Gangguan mekanisme regulasi (mis. diabetes)</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_89[faktor_risiko][]" id="faktor_prosedur" onclick="checkthis('faktor_prosedur')" value="Efek samping prosedur"><span class="lbl"> Efek samping prosedur (mis. pembedahan)</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_89[faktor_risiko][]" id="faktor_diare" onclick="checkthis('faktor_diare')" value="Diare"><span class="lbl"> Diare</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_89[faktor_risiko][]" id="faktor_muntah" onclick="checkthis('faktor_muntah')" value="Muntah"><span class="lbl"> Muntah</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_89[faktor_risiko][]" id="faktor_ginjal" onclick="checkthis('faktor_ginjal')" value="Disfungsi ginjal"><span class="lbl"> Disfungsi ginjal</span></label>
        </div>
        <div class="checkbox">
          <label><input type="checkbox" class="ace" name="form_89[faktor_risiko][]" id="faktor_endokrin" onclick="checkthis('faktor_endokrin')" value="Disfungsi regulasi endokrin"><span class="lbl"> Disfungsi regulasi endokrin</span></label>
        </div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 6px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_89[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Keseimbangan Elektrolit (L.03021) meningkat dengan kriteria hasil:</b>

        <div style="margin-top: 5px;">
          <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[kriteria_hasil][]" id="hasil_natrium" onclick="checkthis('hasil_natrium')" value="Serum natrium membaik"><span class="lbl"> Serum natrium membaik*</span></label></div>
          <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[kriteria_hasil][]" id="hasil_kalium" onclick="checkthis('hasil_kalium')" value="Serum kalium membaik"><span class="lbl"> Serum kalium membaik*</span></label></div>
          <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[kriteria_hasil][]" id="hasil_klorida" onclick="checkthis('hasil_klorida')" value="Serum klorida membaik"><span class="lbl"> Serum klorida membaik*</span></label></div>
          <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[kriteria_hasil][]" id="hasil_kalsium" onclick="checkthis('hasil_kalsium')" value="Serum kalsium membaik"><span class="lbl"> Serum kalsium membaik</span></label></div>
          <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[kriteria_hasil][]" id="hasil_magnesium" onclick="checkthis('hasil_magnesium')" value="Serum magnesium membaik"><span class="lbl"> Serum magnesium membaik</span></label></div>
          <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[kriteria_hasil][]" id="hasil_fosfor" onclick="checkthis('hasil_fosfor')" value="Serum fosfor membaik"><span class="lbl"> Serum fosfor membaik</span></label></div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->



<!-- PEMANTAUAN ELEKTROLIT -->
<table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; font-size:13px;">
  <thead>
    <tr style="background-color:#d3d3d3;">
      <th style="width:5%; text-align:center; border:1px solid black;">NO.</th>
      <th style="width:95%; text-align:center; border:1px solid black;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td colspan="2" style="border:1px solid black; padding:6px;">
        <b>Pemantauan Elektrolit</b><br>
        <i>(Mengumpulkan dan menganalisis data terkait regulasi keseimbangan elektrolit)</i><br>
        <b>(I.03122)</b>
      </td>
    </tr>

    <!-- Tindakan 1: Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_observasi][]" id="pemantauan_elektrolit_observasi1" onclick="checkthis('pemantauan_elektrolit_observasi1')" value="Identifikasi kemungkinan penyebab ketidakseimbangan elektrolit"><span class="lbl"> Identifikasi kemungkinan penyebab ketidakseimbangan elektrolit</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_observasi][]" id="pemantauan_elektrolit_observasi2" onclick="checkthis('pemantauan_elektrolit_observasi2')" value="Monitor kadar elektrolit serum"><span class="lbl"> Monitor kadar elektrolit serum</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_observasi][]" id="pemantauan_elektrolit_observasi3" onclick="checkthis('pemantauan_elektrolit_observasi3')" value="Monitor mual muntah diare"><span class="lbl"> Monitor mual, muntah dan diare</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_observasi][]" id="pemantauan_elektrolit_observasi4" onclick="checkthis('pemantauan_elektrolit_observasi4')" value="Monitor kehilangan cairan"><span class="lbl"> Monitor kehilangan cairan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_observasi][]" id="pemantauan_elektrolit_observasi5" onclick="checkthis('pemantauan_elektrolit_observasi5')" value="Monitor tanda hipokalemia"><span class="lbl"> Monitor tanda dan gejala hipokalemia (mis. kelemahan otot, interval QT memanjang, gelombang T datar/terbalik, depresi segmen ST, gelombang U, kelelahan, parestesia, penurunan refleks, anoreksia, konstipasi, motilitas usus menurun, pusing, depresi pernapasan)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_observasi][]" id="pemantauan_elektrolit_observasi6" onclick="checkthis('pemantauan_elektrolit_observasi6')" value="Monitor tanda hiperkalemia"><span class="lbl"> Monitor tanda dan gejala hiperkalemia (mis. peka rangsang, gelisah, mual, muntah, takikardia mengarah ke bradikardia, fibrilasi/takikardia ventrikel, gelombang T tinggi, gelombang P datar, kompleks QRS tumpul, blok)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_observasi][]" id="pemantauan_elektrolit_observasi7" onclick="checkthis('pemantauan_elektrolit_observasi7')" value="Monitor tanda hiponatremia"><span class="lbl"> Monitor tanda dan gejala hiponatremia (mis. disorientasi, otot berkedut, sakit kepala, membran mukosa kering, hipotensi postural, kejang, letargi, penurunan kesadaran)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_observasi][]" id="pemantauan_elektrolit_observasi8" onclick="checkthis('pemantauan_elektrolit_observasi8')" value="Monitor tanda hipernatremia"><span class="lbl"> Monitor tanda dan gejala hipernatremia (mis. haus, demam, mual, muntah, gelisah, peka rangsang, membran mukosa kering, takikardia, hipotensi, letargi, konfusi, kejang)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_observasi][]" id="pemantauan_elektrolit_observasi9" onclick="checkthis('pemantauan_elektrolit_observasi9')" value="Monitor tanda hipokalsemia"><span class="lbl"> Monitor tanda dan gejala hipokalsemia (mis. peka rangsang, tanda Chvostek [spasme otot wajah], tanda Trousseau [spasme karpal], kram otot, interval QT memanjang)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_observasi][]" id="pemantauan_elektrolit_observasi10" onclick="checkthis('pemantauan_elektrolit_observasi10')" value="Monitor tanda hiperkalsemia"><span class="lbl"> Monitor tanda dan gejala hiperkalsemia (mis. nyeri tulang, haus, letargi, anoreksia, kelemahan otot, segmen QT memendek, gelombang T lebar, kompleks QRST lebar, interval PR memanjang)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_observasi][]" id="pemantauan_elektrolit_observasi11" onclick="checkthis('pemantauan_elektrolit_observasi11')" value="Monitor tanda hipomagnesemia"><span class="lbl"> Monitor tanda dan gejala hipomagnesemia (mis. depresi pernapasan, apatis, tanda Chvostek, tanda Trousseau, konfusi, disritmia)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_observasi][]" id="pemantauan_elektrolit_observasi12" onclick="checkthis('pemantauan_elektrolit_observasi12')" value="Monitor tanda hipermagnesemia"><span class="lbl"> Monitor tanda dan gejala hipermagnesemia (mis. kelemahan otot, hiporefleks, bradikardia, depresi SSP, letargi, koma, depresi pernapasan)</span></label></div>
      </td>
    </tr>

    <!-- Tindakan 2: Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_terapeutik][]" id="pemantauan_elektrolit_terapeutik1" onclick="checkthis('pemantauan_elektrolit_terapeutik1')" value="Atur interval waktu pemantauan"><span class="lbl"> Atur interval waktu pemantauan sesuai dengan kondisi pasien</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_terapeutik][]" id="pemantauan_elektrolit_terapeutik2" onclick="checkthis('pemantauan_elektrolit_terapeutik2')" value="Dokumentasikan hasil pemantauan"><span class="lbl"> Dokumentasikan hasil pemantauan</span></label></div>
      </td>
    </tr>

    <!-- Tindakan 3: Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_edukasi][]" id="pemantauan_elektrolit_edukasi1" onclick="checkthis('pemantauan_elektrolit_edukasi1')" value="Jelaskan tujuan dan prosedur pemantauan"><span class="lbl"> Jelaskan tujuan dan prosedur pemantauan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_89[pemantauan_elektrolit_edukasi][]" id="pemantauan_elektrolit_edukasi2" onclick="checkthis('pemantauan_elektrolit_edukasi2')" value="Informasikan hasil pemantauan"><span class="lbl"> Informasikan hasil pemantauan</span></label></div>
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
        <input type="text" class="input_type" name="form_89[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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