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
      var hiddenInputName = 'form_91[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN:<br>RISIKO SYOK</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Definisi:</b> Berisiko mengalami ketidakcukupan aliran darah ke jaringan tubuh yang dapat mengakibatkan disfungsi seluler yang mengancam jiwa.
      </td>
    </tr>
  </thead>

  <tbody>
    <!-- FAKTOR RISIKO -->
    <tr>
      <td width="50%" style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif;">
        <b>FAKTOR RISIKO (Dibuktikan dengan):</b><br><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[faktor_risiko][]" id="hipoksemia" onclick="checkthis('hipoksemia')" value="Hipoksemia"><span class="lbl"> Hipoksemia</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[faktor_risiko][]" id="hipoksia" onclick="checkthis('hipoksia')" value="Hipoksia"><span class="lbl"> Hipoksia</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[faktor_risiko][]" id="hipotensi" onclick="checkthis('hipotensi')" value="Hipotensi"><span class="lbl"> Hipotensi</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[faktor_risiko][]" id="kekurangan_volume_cairan" onclick="checkthis('kekurangan_volume_cairan')" value="Kekurangan volume cairan"><span class="lbl"> Kekurangan volume cairan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[faktor_risiko][]" id="sepsis" onclick="checkthis('sepsis')" value="Sepsis"><span class="lbl"> Sepsis</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[faktor_risiko][]" id="sirs" onclick="checkthis('sirs')" value="Sindrom respons inflamasi sistemik (SIRS)"><span class="lbl"> Sindrom respons inflamasi sistemik (systemic inflammatory response syndrome) (SIRS)</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_91[intervensi_selama]" id="intervensi_selama" onchange="fillthis('intervensi_selama')" style="width:10%;">,
          maka Tingkat Syok (L.03032) menurun dengan kriteria hasil:</b><br><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="kekuatan_nadi_meningkat" onclick="checkthis('kekuatan_nadi_meningkat')" value="Kekuatan nadi meningkat"><span class="lbl"> Kekuatan nadi meningkat*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="output_urin_meningkat" onclick="checkthis('output_urin_meningkat')" value="Output urin meningkat"><span class="lbl"> Output urin meningkat*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="tingkat_kesadaran_meningkat" onclick="checkthis('tingkat_kesadaran_meningkat')" value="Tingkat kesadaran meningkat"><span class="lbl"> Tingkat kesadaran meningkat*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="saturasi_oksigen_meningkat" onclick="checkthis('saturasi_oksigen_meningkat')" value="Saturasi oksigen meningkat"><span class="lbl"> Saturasi oksigen meningkat</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="akral_dingin_menurun" onclick="checkthis('akral_dingin_menurun')" value="Akral dingin menurun"><span class="lbl"> Akral dingin menurun*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="pucat_menurun" onclick="checkthis('pucat_menurun')" value="Pucat menurun"><span class="lbl"> Pucat menurun*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="haus_menurun" onclick="checkthis('haus_menurun')" value="Haus menurun"><span class="lbl"> Haus menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="konfusi_menurun" onclick="checkthis('konfusi_menurun')" value="Konfusi menurun"><span class="lbl"> Konfusi menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="letargi_menurun" onclick="checkthis('letargi_menurun')" value="Letargi menurun"><span class="lbl"> Letargi menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="asidosis_menurun" onclick="checkthis('asidosis_menurun')" value="Asidosis metabolik menurun"><span class="lbl"> Asidosis metabolik menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="map_membaik" onclick="checkthis('map_membaik')" value="Mean arterial pressure membaik"><span class="lbl"> Mean arterial pressure membaik*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="td_sistolik_membaik" onclick="checkthis('td_sistolik_membaik')" value="Tekanan darah sistolik membaik"><span class="lbl"> Tekanan darah sistolik membaik*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="td_diastolik_membaik" onclick="checkthis('td_diastolik_membaik')" value="Tekanan darah diastolik membaik"><span class="lbl"> Tekanan darah diastolik membaik*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="tekanan_nadi_membaik" onclick="checkthis('tekanan_nadi_membaik')" value="Tekanan nadi membaik"><span class="lbl"> Tekanan nadi membaik*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="pengisian_kapiler_membaik" onclick="checkthis('pengisian_kapiler_membaik')" value="Pengisian kapiler membaik"><span class="lbl"> Pengisian kapiler membaik*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="frekuensi_nadi_membaik" onclick="checkthis('frekuensi_nadi_membaik')" value="Frekuensi nadi membaik"><span class="lbl"> Frekuensi nadi membaik*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_91[kriteria_hasil][]" id="frekuensi_napas_membaik" onclick="checkthis('frekuensi_napas_membaik')" value="Frekuensi napas membaik"><span class="lbl"> Frekuensi napas membaik*</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->



<!-- PENCEGAHAN SYOK -->
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
        <b>Pencegahan Syok</b>
        <i>(Mengidentifikasi dan menurunkan risiko terjadinya ketidakmampuan tubuh menyediakan oksigen dan nutrien untuk mencukupi kebutuhan jaringan)</i>
        <b>(I.02068)</b>
      </td>
    </tr>

    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Tindakan</b>
      </td>
    </tr>

    <!-- 1. Observasi -->
    <tr>
      <td style="border:1px solid black; text-align:center; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_observasi][]" id="syok_obs1" onclick="checkthis('syok_obs1')" value="Monitor status kardiopulmonal (frekuensi dan kekuatan nadi, frekuensi napas, TD, MAP)">
          <span class="lbl"> Monitor status kardiopulmonal (frekuensi dan kekuatan nadi, frekuensi napas, TD, MAP)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_observasi][]" id="syok_obs2" onclick="checkthis('syok_obs2')" value="Monitor status oksigenasi (oksimetri nadi, AGD)">
          <span class="lbl"> Monitor status oksigenasi (oksimetri nadi, AGD)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_observasi][]" id="syok_obs3" onclick="checkthis('syok_obs3')" value="Monitor status cairan (masukan dan haluaran, turgor kulit, CRT)">
          <span class="lbl"> Monitor status cairan (masukan dan haluaran, turgor kulit, CRT)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_observasi][]" id="syok_obs4" onclick="checkthis('syok_obs4')" value="Monitor tingkat kesadaran dan respon pupil">
          <span class="lbl"> Monitor tingkat kesadaran dan respon pupil</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_observasi][]" id="syok_obs5" onclick="checkthis('syok_obs5')" value="Periksa riwayat alergi">
          <span class="lbl"> Periksa riwayat alergi</span>
        </label></div>
      </td>
    </tr>

    <!-- 2. Terapeutik -->
    <tr>
      <td style="border:1px solid black; text-align:center; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_terapeutik][]" id="syok_ter1" onclick="checkthis('syok_ter1')" value="Berikan oksigen untuk mempertahankan saturasi oksigen >94%">
          <span class="lbl"> Berikan oksigen untuk mempertahankan saturasi oksigen >94%</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_terapeutik][]" id="syok_ter2" onclick="checkthis('syok_ter2')" value="Persiapan intubasi dan ventilasi mekanis, jika perlu">
          <span class="lbl"> Persiapan intubasi dan ventilasi mekanis, jika perlu</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_terapeutik][]" id="syok_ter3" onclick="checkthis('syok_ter3')" value="Pasang jalur IV, jika perlu">
          <span class="lbl"> Pasang jalur IV, jika perlu</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_terapeutik][]" id="syok_ter4" onclick="checkthis('syok_ter4')" value="Pasang kateter urine untuk menilai produksi urine">
          <span class="lbl"> Pasang kateter urine untuk menilai produksi urine</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_terapeutik][]" id="syok_ter5" onclick="checkthis('syok_ter5')" value="Lakukan skin test untuk mencegah reaksi alergi">
          <span class="lbl"> Lakukan skin test untuk mencegah reaksi alergi</span>
        </label></div>
      </td>
    </tr>

    <!-- 3. Edukasi -->
    <tr>
      <td style="border:1px solid black; text-align:center; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_edukasi][]" id="syok_eduk1" onclick="checkthis('syok_eduk1')" value="Jelaskan penyebab/faktor risiko syok">
          <span class="lbl"> Jelaskan penyebab/faktor risiko syok</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_edukasi][]" id="syok_eduk2" onclick="checkthis('syok_eduk2')" value="Jelaskan tanda dan gejala awal syok">
          <span class="lbl"> Jelaskan tanda dan gejala awal syok</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_edukasi][]" id="syok_eduk3" onclick="checkthis('syok_eduk3')" value="Anjurkan melapor jika menemukan/merasakan tanda dan gejala awal syok">
          <span class="lbl"> Anjurkan melapor jika menemukan/merasakan tanda dan gejala awal syok</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_edukasi][]" id="syok_eduk4" onclick="checkthis('syok_eduk4')" value="Anjurkan memperbanyak asupan cairan oral">
          <span class="lbl"> Anjurkan memperbanyak asupan cairan oral</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_edukasi][]" id="syok_eduk5" onclick="checkthis('syok_eduk5')" value="Anjurkan menghindari alergen">
          <span class="lbl"> Anjurkan menghindari alergen</span>
        </label></div>
      </td>
    </tr>

    <!-- 4. Kolaborasi -->
    <tr>
      <td style="border:1px solid black; text-align:center; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_kolaborasi][]" id="syok_kol1" onclick="checkthis('syok_kol1')" value="Kolaborasi pemberian cairan intravena (IV)">
          <span class="lbl"> Kolaborasi pemberian cairan intravena (IV)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_kolaborasi][]" id="syok_kol2" onclick="checkthis('syok_kol2')" value="Kolaborasi pemberian transfusi darah">
          <span class="lbl"> Kolaborasi pemberian transfusi darah</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_91[syok_kolaborasi][]" id="syok_kol3" onclick="checkthis('syok_kol3')" value="Kolaborasi pemberian antiinflamasi">
          <span class="lbl"> Kolaborasi pemberian antiinflamasi</span>
        </label></div>
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
        <input type="text" class="input_type" name="form_91[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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