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
      var hiddenInputName = 'form_61[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 25 september 2025</p> -->
<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN:<br>RISIKO PENDARAHAN</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
    <thead>
     <tr>
        <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">Definisi : Berisiko mengalami kehilangan darah baik internal (terjadi didalam tubuh) maupun eksternal (terjadi hingga keluar tubuh)
        </td>
     </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
  <b>FAKTOR RISIKO (Dibuktikan dengan):</b>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[faktor_risiko][]" id="faktor_aneurisma" onclick="checkthis('faktor_aneurisma')" value="Aneurisma">
      <span class="lbl"> Aneurisma</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[faktor_risiko][]" id="faktor_gangguan_gi" onclick="checkthis('faktor_gangguan_gi')" value="Gangguan GI">
      <span class="lbl"> Gangguan GI (Mis: Ulkus lambung, polip, avarises)</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[faktor_risiko][]" id="faktor_gangguan_hati" onclick="checkthis('faktor_gangguan_hati')" value="Gangguan fungsi hati">
      <span class="lbl"> Gangguan fungsi hati (Mis: Sirosis hepatis)</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[faktor_risiko][]" id="faktor_pasca_partum" onclick="checkthis('faktor_pasca_partum')" value="Komplikasi pasca partum">
      <span class="lbl"> Komplikasi pasca partum (mis: atoni uterus, retensi plasenta)</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[faktor_risiko][]" id="faktor_kehamilan" onclick="checkthis('faktor_kehamilan')" value="Komplikasi kehamilan">
      <span class="lbl"> Komplikasi kehamilan (Mis: ketuban pecah sebelum waktunya, plasenta previa/abrupsio, kehamilan kembar)</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[faktor_risiko][]" id="faktor_koagulasi" onclick="checkthis('faktor_koagulasi')" value="Gangguan koagulasi">
      <span class="lbl"> Gangguan koagulasi (mis: trombositopenia)</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[faktor_risiko][]" id="faktor_agen_farmakologis" onclick="checkthis('faktor_agen_farmakologis')" value="Efek agen farmakologis">
      <span class="lbl"> Efek agen farmakologis</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[faktor_risiko][]" id="faktor_pembedahan" onclick="checkthis('faktor_pembedahan')" value="Tindakan pembedahan">
      <span class="lbl"> Tindakan pembedahan</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[faktor_risiko][]" id="faktor_trauma" onclick="checkthis('faktor_trauma')" value="Trauma">
      <span class="lbl"> Trauma</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[faktor_risiko][]" id="faktor_kurang_informasi" onclick="checkthis('faktor_kurang_informasi')" value="Kurang informasi">
      <span class="lbl"> Kurang terpapar informasi tentang pencegahan perdarahan</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[faktor_risiko][]" id="faktor_keganasan" onclick="checkthis('faktor_keganasan')" value="Proses keganasan">
      <span class="lbl"> Proses keganasan</span>
    </label>
  </div>
</td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Setelah dilakukan intervensi selama <input type="text" class="input_type" name="form_61[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;"> Tingkat perdarhan menurun (L.02017), dengan kriteria hasil :</b>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_membran" onclick="checkthis('kriteria_membran')" value="Kelembaban membran">
      <span class="lbl"> Kelembaban membran mukosa meningkat</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_kulit" onclick="checkthis('kriteria_kulit')" value="Kelembaban kulit">
      <span class="lbl"> Kelembaban kulit meningkat</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_kognitif" onclick="checkthis('kriteria_kognitif')" value="Kognitif">
      <span class="lbl"> Kognitif meningkat</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_hemoptisis" onclick="checkthis('kriteria_hemoptisis')" value="Hemoptisis menurun">
      <span class="lbl"> Hemoptisis menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_hematemesis" onclick="checkthis('kriteria_hematemesis')" value="Hematemesis menurun">
      <span class="lbl"> Hematemesis menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_hematuria" onclick="checkthis('kriteria_hematuria')" value="Hematuria menurun">
      <span class="lbl"> Hematuria menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_anus" onclick="checkthis('kriteria_anus')" value="Perdarahan anus menurun">
      <span class="lbl"> Perdarahan anus menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_abdomen" onclick="checkthis('kriteria_abdomen')" value="Distensi abdomen menurun">
      <span class="lbl"> Distensi abdomen menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_vagina" onclick="checkthis('kriteria_vagina')" value="Perdarahan vagina menurun">
      <span class="lbl"> Perdarahan vagina menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_pasca_operasi" onclick="checkthis('kriteria_pasca_operasi')" value="Perdarahan pasca operasi menurun">
      <span class="lbl"> Perdarahan pasca operasi menurun</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_hb" onclick="checkthis('kriteria_hb')" value="HB membaik">
      <span class="lbl"> HB membaik</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_ht" onclick="checkthis('kriteria_ht')" value="HT membaik">
      <span class="lbl"> HT membaik</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_td" onclick="checkthis('kriteria_td')" value="TD membaik">
      <span class="lbl"> TD membaik</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_nadi" onclick="checkthis('kriteria_nadi')" value="Denyut nadi apikal membaik">
      <span class="lbl"> Denyut nadi apikal membaik</span>
    </label>
  </div>

  <div class="checkbox">
    <label>
      <input type="checkbox" class="ace" name="form_61[kriteria][]" id="kriteria_suhu" onclick="checkthis('kriteria_suhu')" value="Suhu tubuh membaik">
      <span class="lbl"> Suhu tubuh membaik</span>
    </label>
  </div>
</td>

</tr>
        
        <!-- next -->

            <tr>
                <td colspan="2" style="border: 1px solid black; padding: 5px;">
                    <table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="background-color: #d3d3d3;">
                                <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
                                <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" style="border: 1px solid black; padding: 5px;">
                                    <b>Pencegahan Perdarahan  </b><i>(Mengidentifikasi dan menurunkan risiko/komplikasi stimulus yang menyebabkan perdarahan/risiko perdarahan)</i><b>(I.02067)</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid black; padding: 5px;">
                                    <b>TINDAKAN</b>
                                </td>
                            </tr>
                            <tr>
  <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;">
    <b>1</b>
  </td>
  <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
    <label><b>Observasi</b></label><br>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_observasi_perdarahan][]" id="observasi_tanda_gejala_perdarahan" onclick="checkthis('observasi_tanda_gejala_perdarahan')" value="Monitor tanda dan gejala perdarahan">
        <span class="lbl"> Monitor tanda dan gejala perdarahan</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_observasi_perdarahan][]" id="observasi_ht_hb" onclick="checkthis('observasi_ht_hb')" value="Monitor nilai HT/HB sebelum dan setelah kehilangan darah">
        <span class="lbl"> Monitor nilai HT/HB sebelum dan setelah kehilangan darah</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_observasi_perdarahan][]" id="observasi_ttv" onclick="checkthis('observasi_ttv')" value="Monitor TTV">
        <span class="lbl"> Monitor TTV</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_observasi_perdarahan][]" id="observasi_koagulasi" onclick="checkthis('observasi_koagulasi')" value="Monitor Koagulasi (mis, PT, APTT, Fibrinogen, degradasi fibrin dan/platelet)">
        <span class="lbl"> Monitor Koagulasi (mis, PT, APTT, Fibrinogen, degradasi fibrin dan/platelet)</span>
      </label>
    </div>

    <div style="margin-top: 5px;">
      <input type="text" class="input_type" name="form_61[ket_tambahan_manajemen_nyeri_observasi]" id="ket_tambahan_manajemen_nyeri_observasi" onchange="fillthis('ket_tambahan_manajemen_nyeri_observasi')" style="width:100%;"> 
    </div>
  </td>
</tr>
<tr>
  <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;">
    <b>2</b>
  </td>
  <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
    <label><b>Terapeutik</b></label><br>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_terapeutik_perdarahan][]" id="terapeutik_bed_rest" onclick="checkthis('terapeutik_bed_rest')" value="Pertahankan bed rest selama perdarahan">
        <span class="lbl"> Pertahankan bed rest selama perdarahan</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_terapeutik_perdarahan][]" id="terapeutik_tindakan_invasif" onclick="checkthis('terapeutik_tindakan_invasif')" value="Batasi tindakan invasif">
        <span class="lbl"> Batasi tindakan invasif</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_terapeutik_perdarahan][]" id="terapeutik_kasur_decubitus" onclick="checkthis('terapeutik_kasur_decubitus')" value="Gunakan kasur pencegah decubitus">
        <span class="lbl"> Gunakan kasur pencegah decubitus</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_terapeutik_perdarahan][]" id="terapeutik_suhu_rektal" onclick="checkthis('terapeutik_suhu_rektal')" value="Hindari pengukuran suhu rektal berjalan">
        <span class="lbl"> Hindari pengukuran suhu rektal berjalan</span>
      </label>
    </div>
    <div style="margin-top: 5px;">
      <input type="text" class="input_type" name="form_61[ket_tambahan_manajemen_nyeri_terapeutik]" id="ket_tambahan_manajemen_nyeri_terapeutik" onchange="fillthis('ket_tambahan_manajemen_nyeri_terapeutik')" style="width:100%;"> 
    </div>
  </td>
</tr>
<tr>
  <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;">
    <b>3</b>
  </td>
  <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
    <label><b>Edukasi</b></label><br>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_edukasi_perdarahan][]" id="edukasi_tanda_gejala" onclick="checkthis('edukasi_tanda_gejala')" value="Jelaskan tanda dan gejala perdarahan">
        <span class="lbl"> Jelaskan tanda dan gejala perdarahan</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_edukasi_perdarahan][]" id="edukasi_kaos_kaki" onclick="checkthis('edukasi_kaos_kaki')" value="Anjurkan menggunakan kaos kaki saat ambulasi">
        <span class="lbl"> Anjurkan menggunakan kaos kaki saat ambulasi</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_edukasi_perdarahan][]" id="edukasi_cairan" onclick="checkthis('edukasi_cairan')" value="Anjurkan meningkatkan asupan cairan untuk menghindari konstipasi">
        <span class="lbl"> Anjurkan meningkatkan asupan cairan untuk menghindari konstipasi</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_edukasi_perdarahan][]" id="edukasi_aspirin" onclick="checkthis('edukasi_aspirin')" value="Anjurkan menghindari aspirin atau antikoagulan">
        <span class="lbl"> Anjurkan menghindari aspirin atau antikoagulan</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_edukasi_perdarahan][]" id="edukasi_makanan" onclick="checkthis('edukasi_makanan')" value="Anjurkan meningkatkan asupan makanan">
        <span class="lbl"> Anjurkan meningkatkan asupan makanan</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_edukasi_perdarahan][]" id="edukasi_lapor" onclick="checkthis('edukasi_lapor')" value="Anjurkan segera melapor jika terjadi perdarahan">
        <span class="lbl"> Anjurkan segera melapor jika terjadi perdarahan</span>
      </label>
    </div>

    <div style="margin-top: 5px;">
      <input type="text" class="input_type" name="form_61[ket_tambahan_manajemen_nyeri_edukasi]" id="ket_tambahan_manajemen_nyeri_edukasi" onchange="fillthis('ket_tambahan_manajemen_nyeri_edukasi')" style="width:100%;"> 
    </div>
  </td>
</tr>

<tr>
  <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top;">
    <b>4</b>
  </td>
  <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
    <label><b>Kolaborasi</b></label><br>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_kolaborasi_perdarahan][]" id="kolaborasi_obat" onclick="checkthis('kolaborasi_obat')" value="Kolaborasi pemberian obat pengontrol perdarahan">
        <span class="lbl"> Kolaborasi pemberian obat pengontrol perdarahan</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_kolaborasi_perdarahan][]" id="kolaborasi_darah" onclick="checkthis('kolaborasi_darah')" value="Kolaborasi pemberian produk darah">
        <span class="lbl"> Kolaborasi pemberian produk darah</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_61[intervensi_kolaborasi_perdarahan][]" id="kolaborasi_pencahar" onclick="checkthis('kolaborasi_pencahar')" value="Kolaborasi pemberian pencahar">
        <span class="lbl"> Kolaborasi pemberian pencahar</span>
      </label>
    </div>
    <div style="margin-top: 5px;">
      <input type="text" class="input_type" name="form_61[ket_tambahan_kolab]" id="ket_tambahan_kolab" onchange="fillthis('ket_tambahan_kolab')" style="width:100%;"> 
    </div>
  </td>
</tr>

                </td>
            </tr>
    </tbody>
</table>

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
        <input type="text" class="input_type" name="form_61[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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