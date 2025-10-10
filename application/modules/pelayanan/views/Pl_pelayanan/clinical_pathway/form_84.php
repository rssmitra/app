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
      var hiddenInputName = 'form_84[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN:<br>RETENSI URINE</b>
</div>
<br>

<!-- DEFINISI -->
<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Definisi:</b> Pengosongan kandung kemih yang tidak lengkap
      </td>
    </tr>
  </thead>

  <tbody>
    <!-- PENYEBAB / BERHUBUNGAN DENGAN -->
    <tr>
      <td width="50%" style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>
        <b>FISIOLOGIS</b><br>

        <!-- <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[penyebab][]" id="fisiologis" onclick="checkthis('fisiologis')" value="Fisiologis"><span class="lbl"> FISIOLOGIS</span></label></div> -->

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[penyebab][]" id="peningkatan_uretra" onclick="checkthis('peningkatan_uretra')" value="Peningkatan tekanan uretra"><span class="lbl"> Peningkatan tekanan uretra</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[penyebab][]" id="kerusakan_arkus" onclick="checkthis('kerusakan_arkus')" value="Kerusakan arkus refleks"><span class="lbl"> Kerusakan arkus refleks</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[penyebab][]" id="blok_sfingter" onclick="checkthis('blok_sfingter')" value="Blok sfingter"><span class="lbl"> Blok sfingter</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[penyebab][]" id="disfungsi_neurologis" onclick="checkthis('disfungsi_neurologis')" value="Disfungsi neurologis"><span class="lbl"> Disfungsi neurologis (mis: trauma, penyakit saraf)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[penyebab][]" id="efek_farmako" onclick="checkthis('efek_farmako')" value="Efek agen farmakologis"><span class="lbl"> Efek agen farmakologis (mis: atropine, belladonna, psikotropik, antihistamin, opiate)</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_84[intervensi_selama]" id="intervensi_selama" onchange="fillthis('intervensi_selama')" style="width:10%;">,
          maka Eliminasi Urine (L.04034) membaik dengan kriteria hasil:</b><br><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kriteria_hasil][]" id="sensasi_berkemih" onclick="checkthis('sensasi_berkemih')" value="Sensasi berkemih meningkat"><span class="lbl"> Sensasi berkemih meningkat</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kriteria_hasil][]" id="urgensi_menurun" onclick="checkthis('urgensi_menurun')" value="Desakan berkemih (urgensi) menurun"><span class="lbl"> Desakan berkemih (urgensi) menurun*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kriteria_hasil][]" id="distensi_menurun" onclick="checkthis('distensi_menurun')" value="Distensi kandung kemih menurun"><span class="lbl"> Distensi kandung kemih menurun*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kriteria_hasil][]" id="berkemih_tuntas" onclick="checkthis('berkemih_tuntas')" value="Berkemih tidak tuntas (hesitancy) menurun"><span class="lbl"> Berkemih tidak tuntas <i>(hesitancy)</i> menurun*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kriteria_hasil][]" id="volume_residu" onclick="checkthis('volume_residu')" value="Volume residu urine menurun"><span class="lbl"> Volume residu urine menurun*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kriteria_hasil][]" id="urin_menetes" onclick="checkthis('urin_menetes')" value="Urin menetes (dribbling) menurun"><span class="lbl"> Urin menetes <i>(dribbling)</i> menurun*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kriteria_hasil][]" id="nokturia" onclick="checkthis('nokturia')" value="Nokturia menurun"><span class="lbl"> Nokturia menurun*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kriteria_hasil][]" id="mengompol" onclick="checkthis('mengompol')" value="Mengompol menurun"><span class="lbl"> Mengompol menurun*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kriteria_hasil][]" id="disuria" onclick="checkthis('disuria')" value="Disuria menurun"><span class="lbl"> Disuria menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kriteria_hasil][]" id="anuria" onclick="checkthis('anuria')" value="Anuria menurun"><span class="lbl"> Anuria menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kriteria_hasil][]" id="frekuensi_bak" onclick="checkthis('frekuensi_bak')" value="Frekuensi BAK membaik"><span class="lbl"> Frekuensi BAK membaik</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kriteria_hasil][]" id="karakteristik_urine" onclick="checkthis('karakteristik_urine')" value="Karakteristik urine membaik"><span class="lbl"> Karakteristik urine membaik</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<br>

<!-- DIBUKTIKAN DENGAN -->
<table style="width:100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <tbody>
    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dibuktikan dengan:</b><br>

        <p><b>Tanda dan Gejala Mayor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
              <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[mayor_subjektif][]" id="sensasi_penuh" onclick="checkthis('sensasi_penuh')" value="Sensasi penuh pada kandung kemih"><span class="lbl"> Sensasi penuh pada kandung kemih</span></label></div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
              <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[mayor_objektif][]" id="dysuria_anuria" onclick="checkthis('dysuria_anuria')" value="Dysuria/anuria"><span class="lbl"> Dysuria / anuria</span></label></div>
              <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[mayor_objektif][]" id="distensi_kandung" onclick="checkthis('distensi_kandung')" value="Distensi kandung kemih"><span class="lbl"> Distensi kandung kemih</span></label></div>
          </div>
        </div>

        <hr>

        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[minor_subjektif][]" id="dribbling" onclick="checkthis('dribbling')" value="Dribbling"><span class="lbl"> Dribbling</span></label></div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
             <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[minor_objektif][]" id="inkontinensia" onclick="checkthis('inkontinensia')" value="Inkontinensia berlebih"><span class="lbl"> Inkontinensia berlebih</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[minor_objektif][]" id="residu_urine" onclick="checkthis('residu_urine')" value="Residu urine 150 ml atau lebih"><span class="lbl"> Residu urine 150 ml atau lebih</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<!-- END -->

<br>


<!-- KATETERISASI URINE -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse; font-size:13px; line-height:1.3;">
  <thead>
    <tr style="background-color:#d3d3d3;">
      <th style="width:5%; text-align:center; border:1px solid black;">NO.</th>
      <th style="width:95%; text-align:center; border:1px solid black;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Kateterisasi Urine</b><br>
        <i>(Memasukkan selang kateter urine ke dalam kandung kemih)</i><br>
        <b>(I.04148)</b>
      </td>
    </tr>

    <!-- 1. Observasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>1</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Observasi</b><br>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_84[kateter_observasi][]" id="kat_obs1" onclick="checkthis('kat_obs1')" value="Periksa kondisi pasien (mis: kesadaran, tanda-tanda vital, daerah perineal, distensi kandung kemih, inkontinensia urine, refleksi berkemih)">
            <span class="lbl"> Periksa kondisi pasien (mis: kesadaran, tanda-tanda vital, daerah perineal, distensi kandung kemih, inkontinensia urine, refleksi berkemih)</span>
          </label>
        </div>

      </td>
    </tr>

    <!-- 2. Terapeutik -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>2</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Terapeutik</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kateter_terapeutik][]" id="kat_ter1" onclick="checkthis('kat_ter1')" value="Siapkan peralatan dan bahan"><span class="lbl"> Siapkan peralatan dan bahan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kateter_terapeutik][]" id="kat_ter2" onclick="checkthis('kat_ter2')" value="Siapkan pasien: bebaskan pakaian bawah dan posisikan dorsal rekumben (untuk wanita), supine (untuk laki-laki)"><span class="lbl"> Siapkan pasien: bebaskan pakaian bawah dan posisikan dorsal rekumben (untuk wanita), supine (untuk laki-laki)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kateter_terapeutik][]" id="kat_ter3" onclick="checkthis('kat_ter3')" value="Pasang sarung tangan"><span class="lbl"> Pasang sarung tangan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kateter_terapeutik][]" id="kat_ter4" onclick="checkthis('kat_ter4')" value="Bersihkan daerah perineal atau preposium dengan cairan Nacl atau aquades"><span class="lbl"> Bersihkan daerah perineal atau preposium dengan cairan Nacl atau aquades</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kateter_terapeutik][]" id="kat_ter5" onclick="checkthis('kat_ter5')" value="Lakukan insersi kateter urine dengan menerapkan prinsip aseptik"><span class="lbl"> Lakukan insersi kateter urine dengan menerapkan prinsip aseptik</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kateter_terapeutik][]" id="kat_ter6" onclick="checkthis('kat_ter6')" value="Sambungkan kateter urine dengan urine bag"><span class="lbl"> Sambungkan kateter urine dengan urine bag</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kateter_terapeutik][]" id="kat_ter7" onclick="checkthis('kat_ter7')" value="Isi balon dengan Nacl 0,9% sesuai dengan anjuran pabrik"><span class="lbl"> Isi balon dengan Nacl 0,9% sesuai dengan anjuran pabrik</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kateter_terapeutik][]" id="kat_ter8" onclick="checkthis('kat_ter8')" value="Fiksasi selang kateter di atas simpisis atau di paha"><span class="lbl"> Fiksasi selang kateter di atas simpisis atau di paha</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kateter_terapeutik][]" id="kat_ter9" onclick="checkthis('kat_ter9')" value="Pastikan kantung urine ditempatkan lebih rendah dari kandung kemih"><span class="lbl"> Pastikan kantung urine ditempatkan lebih rendah dari kandung kemih</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kateter_terapeutik][]" id="kat_ter10" onclick="checkthis('kat_ter10')" value="Berikan label untuk pemasangan"><span class="lbl"> Berikan label untuk pemasangan</span></label></div>

        <!-- <p style="margin-top:5px; font-size:12px;"><i>MHKN/KEP/124/07/2020-00</i></p> -->
      </td>
    </tr>

    <!-- 3. Edukasi -->
    <tr>
      <td style="text-align:center; vertical-align:top;"><b>3</b></td>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Edukasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kateter_edukasi][]" id="kat_eduk1" onclick="checkthis('kat_eduk1')" value="Jelaskan tujuan dan prosedur pemasangan kateter urine"><span class="lbl"> Jelaskan tujuan dan prosedur pemasangan kateter urine</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_84[kateter_edukasi][]" id="kat_eduk2" onclick="checkthis('kat_eduk2')" value="Anjurkan menarik napas dalam saat insersi selang kateter"><span class="lbl"> Anjurkan menarik napas dalam saat insersi selang kateter</span></label></div>

      </td>
    </tr>
  </tbody>
</table>
<!-- ----- -->



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
        <input type="text" class="input_type" name="form_84[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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