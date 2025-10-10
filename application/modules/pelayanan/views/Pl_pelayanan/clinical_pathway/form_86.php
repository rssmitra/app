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
      var hiddenInputName = 'form_86[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN:<br>RISIKO HIPOTERMIA</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Definisi:</b> Berisiko mengalami kegagalan termoregulasi yang dapat mengakibatkan suhu tubuh berada di bawah rentang normal.
      </td>
    </tr>
  </thead>

  <tbody>
    <!-- FAKTOR RISIKO -->
    <tr>
      <td width="50%" style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif;">
        <b>FAKTOR RISIKO (Dibuktikan dengan):</b><br><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="kerusakan_hipotalamus" onclick="checkthis('kerusakan_hipotalamus')" value="Kerusakan hipotalamus"><span class="lbl"> Kerusakan hipotalamus</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="konsumsi_alkohol" onclick="checkthis('konsumsi_alkohol')" value="Konsumsi alkohol"><span class="lbl"> Konsumsi alkohol</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="berat_badan_ekstrem" onclick="checkthis('berat_badan_ekstrem')" value="Berat badan ekstrem"><span class="lbl"> Berat badan ekstrem</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="kekurangan_lemak_subkutan" onclick="checkthis('kekurangan_lemak_subkutan')" value="Kekurangan lemak subkutan"><span class="lbl"> Kekurangan lemak subkutan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="terpapar_suhu_lingkungan_rendah" onclick="checkthis('terpapar_suhu_lingkungan_rendah')" value="Terpapar suhu lingkungan rendah"><span class="lbl"> Terpapar suhu lingkungan rendah</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="malnutrisi" onclick="checkthis('malnutrisi')" value="Malnutrisi"><span class="lbl"> Malnutrisi</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="pakaian_tipis" onclick="checkthis('pakaian_tipis')" value="Pemakaian pakaian tipis"><span class="lbl"> Pemakaian pakaian tipis</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="penurunan_metabolisme" onclick="checkthis('penurunan_metabolisme')" value="Penurunan laju metabolisme"><span class="lbl"> Penurunan laju metabolisme</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="tidak_beraktivitas" onclick="checkthis('tidak_beraktivitas')" value="Tidak beraktivitas"><span class="lbl"> Tidak beraktivitas</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="transfer_panas" onclick="checkthis('transfer_panas')" value="Transfer panas (mis. konduksi, konveksi, evaporasi, radiasi)"><span class="lbl"> Transfer panas (mis. konduksi, konveksi, evaporasi, radiasi)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="trauma" onclick="checkthis('trauma')" value="Trauma"><span class="lbl"> Trauma</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="proses_penuaan" onclick="checkthis('proses_penuaan')" value="Proses penuaan"><span class="lbl"> Proses penuaan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="efek_agen_farmakologis" onclick="checkthis('efek_agen_farmakologis')" value="Efek agen farmakologis"><span class="lbl"> Efek agen farmakologis</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="kurang_informasi" onclick="checkthis('kurang_informasi')" value="Kurang terpapar informasi tentang pencegahan hipotermia"><span class="lbl"> Kurang terpapar informasi tentang pencegahan hipotermia</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[faktor_risiko][]" id="berat_badan_lahir_rendah" onclick="checkthis('berat_badan_lahir_rendah')" value="Berat badan lahir rendah"><span class="lbl"> Berat badan lahir rendah</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; font-family: tahoma, sans-serif;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_86[intervensi_selama]" id="intervensi_selama" onchange="fillthis('intervensi_selama')" style="width:10%;">
          maka Termoregulasi (L.14134) membaik dengan kriteria hasil:</b><br><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[kriteria_hasil][]" id="suhu_tubuh_membaik" onclick="checkthis('suhu_tubuh_membaik')" value="Suhu tubuh membaik"><span class="lbl"> Suhu tubuh membaik*</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[kriteria_hasil][]" id="kulit_merah_menurun" onclick="checkthis('kulit_merah_menurun')" value="Kulit merah menurun"><span class="lbl"> Kulit merah menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[kriteria_hasil][]" id="akrosianosis_menurun" onclick="checkthis('akrosianosis_menurun')" value="Akrosianosis menurun"><span class="lbl"> Akrosianosis (warna kebiruan pada kedua tangan dan kaki tanpa rasa sakit) menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[kriteria_hasil][]" id="takikardia_menurun" onclick="checkthis('takikardia_menurun')" value="Takikardia menurun"><span class="lbl"> Takikardia menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[kriteria_hasil][]" id="takipnea_menurun" onclick="checkthis('takipnea_menurun')" value="Takipnea menurun"><span class="lbl"> Takipnea menurun</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[kriteria_hasil][]" id="bradikardia_membaik" onclick="checkthis('bradikardia_membaik')" value="Bradikardia membaik"><span class="lbl"> Bradikardia membaik</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_86[kriteria_hasil][]" id="suhu_kulit_membaik" onclick="checkthis('suhu_kulit_membaik')" value="Suhu kulit membaik"><span class="lbl"> Suhu kulit membaik</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->


<!-- MANAJEMEN HIPOTERMIA -->
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
        <b>Manajemen Hipotermia</b>
        <i>(Mengidentifikasi dan mengelola suhu tubuh di bawah rentang normal)</i>
        <b>(I.14507)</b>
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
          <input type="checkbox" class="ace" name="form_86[hipotermia_observasi][]" id="hipo_obs1" onclick="checkthis('hipo_obs1')" value="Monitor suhu tubuh">
          <span class="lbl"> Monitor suhu tubuh</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_86[hipotermia_observasi][]" id="hipo_obs2" onclick="checkthis('hipo_obs2')" value="Identifikasi penyebab hipotermia (mis. Terpapar suhu lingkungan rendah, pakaian tipis, kerusakan hipotalamus, penurunan laju metabolisme, kekurangan lemak subkutan)">
          <span class="lbl"> Identifikasi penyebab hipotermia (mis. Terpapar suhu lingkungan rendah, pakaian tipis, kerusakan hipotalamus, penurunan laju metabolisme, kekurangan lemak subkutan)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_86[hipotermia_observasi][]" id="hipo_obs3" onclick="checkthis('hipo_obs3')" value="Monitor tanda dan gejala hipotermia (mis. Hipotermia ringan: takipnea, disartria, menggigil, hipertensi, diuresis; sedang: aritmia, hipotensi, apatis, koagulopati, refleks menurun; berat: oliguria, refleks menghilang, edema paru, asam-basa abnormal)">
          <span class="lbl"> Monitor tanda dan gejala hipotermia (mis. Hipotermia ringan: takipnea, disartria, menggigil, hipertensi, diuresis; sedang: aritmia, hipotensi, apatis, koagulopati, refleks menurun; berat: oliguria, refleks menghilang, edema paru, asam-basa abnormal)</span>
        </label></div>
      </td>
    </tr>

    <!-- 2. Terapeutik -->
    <tr>
      <td style="border:1px solid black; text-align:center; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_86[hipotermia_terapeutik][]" id="hipo_ter1" onclick="checkthis('hipo_ter1')" value="Sediakan lingkungan yang hangat (mis. Atur suhu ruangan, inkubator)">
          <span class="lbl"> Sediakan lingkungan yang hangat (mis. Atur suhu ruangan, inkubator)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_86[hipotermia_terapeutik][]" id="hipo_ter2" onclick="checkthis('hipo_ter2')" value="Ganti pakaian dan/atau linen yang basah">
          <span class="lbl"> Ganti pakaian dan/atau linen yang basah</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_86[hipotermia_terapeutik][]" id="hipo_ter3" onclick="checkthis('hipo_ter3')" value="Lakukan penghangatan pasif (mis. Selimut, menutup kepala, pakaian tebal)">
          <span class="lbl"> Lakukan penghangatan pasif (mis. Selimut, menutup kepala, pakaian tebal)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_86[hipotermia_terapeutik][]" id="hipo_ter4" onclick="checkthis('hipo_ter4')" value="Lakukan penghangatan aktif eksternal (mis. Kompres hangat, botol hangat, selimut hangat, perawatan metode kangguru)">
          <span class="lbl"> Lakukan penghangatan aktif eksternal (mis. Kompres hangat, botol hangat, selimut hangat, perawatan metode kangguru)</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_86[hipotermia_terapeutik][]" id="hipo_ter5" onclick="checkthis('hipo_ter5')" value="Lakukan penghangatan internal (mis. Infus cairan hangat, oksigen hangat, lavase peritoneal dengan cairan hangat)">
          <span class="lbl"> Lakukan penghangatan internal (mis. Infus cairan hangat, oksigen hangat, lavase peritoneal dengan cairan hangat)</span>
        </label></div>
      </td>
    </tr>

    <!-- 3. Edukasi -->
    <tr>
      <td style="border:1px solid black; text-align:center; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_86[hipotermia_edukasi][]" id="hipo_eduk1" onclick="checkthis('hipo_eduk1')" value="Anjurkan makan/minum hangat">
          <span class="lbl"> Anjurkan makan/minum hangat</span>
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
        <input type="text" class="input_type" name="form_86[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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