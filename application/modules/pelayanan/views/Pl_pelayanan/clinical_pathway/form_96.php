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
      var hiddenInputName = 'form_96[ttd_' + role + ']';
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
  <b>DIAGNOSIS KEPERAWATAN: KESIAPAN PENINGKATAN KOPING KELUARGA</b>
</div>
<br>

<table style="width:100%; border-collapse:collapse; border:1px solid black; font-size:13px;">
  <thead>
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Definisi:</b> Pola adaptasi anggota keluarga dalam mengatasi situasi yang dialami klien secara efektif dan menunjukkan keinginan serta kesiapan untuk meningkatkan kesehatan keluarga dan klien.
      </td>
    </tr>
  </thead>

  <tbody>
    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Dibuktikan dengan:</b><br>
        <p><b>Tanda dan Gejala Mayor</b></p>

        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox">
              <label><input type="checkbox" class="ace" name="form_96[mayor_subjektif][]" id="kk_mayor_sub1" onclick="checkthis('kk_mayor_sub1')" value="Anggota keluarga menetapkan tujuan untuk meningkatkan gaya hidup sehat"><span class="lbl"> Anggota keluarga menetapkan tujuan untuk meningkatkan gaya hidup sehat</span></label>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" class="ace" name="form_96[mayor_subjektif][]" id="kk_mayor_sub2" onclick="checkthis('kk_mayor_sub2')" value="Anggota keluarga menetapkan sasaran untuk meningkatkan kesehatan"><span class="lbl"> Anggota keluarga menetapkan sasaran untuk meningkatkan kesehatan</span></label>
            </div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
            <i>(Tidak tersedia)</i>
          </div>
        </div>

        <hr>

        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            <div class="checkbox">
              <label><input type="checkbox" class="ace" name="form_96[minor_subjektif][]" id="kk_minor_sub1" onclick="checkthis('kk_minor_sub1')" value="Anggota keluarga mengidentifikasi pengalaman yang mengoptimalkan kesejahteraan"><span class="lbl"> Anggota keluarga mengidentifikasi pengalaman yang mengoptimalkan kesejahteraan</span></label>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" class="ace" name="form_96[minor_subjektif][]" id="kk_minor_sub2" onclick="checkthis('kk_minor_sub2')" value="Anggota keluarga berupaya menjelaskan dampak krisis terhadap perkembangan"><span class="lbl"> Anggota keluarga berupaya menjelaskan dampak krisis terhadap perkembangan</span></label>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" class="ace" name="form_96[minor_subjektif][]" id="kk_minor_sub3" onclick="checkthis('kk_minor_sub3')" value="Anggota keluarga mengungkapkan minat dalam membuat kontak dengan orang lain yang mengalami situasi yang sama"><span class="lbl"> Anggota keluarga mengungkapkan minat dalam membuat kontak dengan orang lain yang mengalami situasi yang sama</span></label>
            </div>
          </div>

          <div class="col-md-6">
            <b>Objektif:</b><br>
            <i>(Tidak tersedia)</i>
          </div>
        </div>
      </td>
    </tr>

    <!-- KRITERIA HASIL -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_96[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Integritas Kulit dan Jaringan (L.14125) meningkat dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit1" onclick="checkthis('kk_krit1')" value="Kepuasan terhadap perilaku bantuan anggota keluarga lain meningkat"><span class="lbl"> Kepuasan terhadap perilaku bantuan anggota keluarga lain meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit2" onclick="checkthis('kk_krit2')" value="Keterpaparan informasi meningkat"><span class="lbl"> Keterpaparan informasi meningkat</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit3" onclick="checkthis('kk_krit3')" value="Perasaan diabaikan menurun"><span class="lbl"> Perasaan diabaikan menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit4" onclick="checkthis('kk_krit4')" value="Kekhawatiran tentang anggota keluarga menurun"><span class="lbl"> Kekhawatiran tentang anggota keluarga menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit5" onclick="checkthis('kk_krit5')" value="Kemampuan memenuhi kebutuhan anggota keluarga menurun"><span class="lbl"> Kemampuan memenuhi kebutuhan anggota keluarga menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit6" onclick="checkthis('kk_krit6')" value="Komitmen pada perawatan/pengobatan menurun"><span class="lbl"> Komitmen pada perawatan/pengobatan menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit7" onclick="checkthis('kk_krit7')" value="Komunikasi antar anggota keluarga menurun"><span class="lbl"> Komunikasi antar anggota keluarga menurun*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit8" onclick="checkthis('kk_krit8')" value="Perasaan tertekan (depresi) menurun"><span class="lbl"> Perasaan tertekan (depresi) menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit9" onclick="checkthis('kk_krit9')" value="Perilaku menyerang (agresi) menurun"><span class="lbl"> Perilaku menyerang (agresi) menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit10" onclick="checkthis('kk_krit10')" value="Perilaku menghasut menurun"><span class="lbl"> Perilaku menghasut menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit11" onclick="checkthis('kk_krit11')" value="Gejala psikosomatis menurun"><span class="lbl"> Gejala psikosomatis menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit12" onclick="checkthis('kk_krit12')" value="Perilaku menolak perawatan menurun"><span class="lbl"> Perilaku menolak perawatan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit13" onclick="checkthis('kk_krit13')" value="Perilaku bermusuhan menurun"><span class="lbl"> Perilaku bermusuhan menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit14" onclick="checkthis('kk_krit14')" value="Perilaku individualism menurun"><span class="lbl"> Perilaku individualism menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit15" onclick="checkthis('kk_krit15')" value="Ketergantungan pada anggota keluarga lain menurun"><span class="lbl"> Ketergantungan pada anggota keluarga lain menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit16" onclick="checkthis('kk_krit16')" value="Perilaku overprotektif menurun"><span class="lbl"> Perilaku overprotektif menurun</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit17" onclick="checkthis('kk_krit17')" value="Toleransi membaik"><span class="lbl"> Toleransi membaik*</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit18" onclick="checkthis('kk_krit18')" value="Perilaku bertujuan membaik"><span class="lbl"> Perilaku bertujuan membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[kriteria_hasil][]" id="kk_krit19" onclick="checkthis('kk_krit19')" value="Perilaku sehat membaik"><span class="lbl"> Perilaku sehat membaik</span></label></div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->


<!-- DUKUNGAN KOPING KELUARGA -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>

  <tbody>
    <!-- Dukungan Koping Keluarga -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dukungan Koping Keluarga</b><br>
        <i>(Memfasilitasi peningkatan nilai-nilai, minat dan tujuan dalam keluarga)</i><br>
        <b>(I.09260)</b>
      </td>
    </tr>

    <!-- Tindakan -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Tindakan</b><br>
      </td>
    </tr>

    <!-- 1. Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Observasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_observasi][]" id="dk_observasi1" onclick="checkthis('dk_observasi1')" value="Identifikasi respon emosional terhadap kondisi saat ini"><span class="lbl"> Identifikasi respon emosional terhadap kondisi saat ini</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_observasi][]" id="dk_observasi2" onclick="checkthis('dk_observasi2')" value="Identifikasi beban prognosis secara psikologis"><span class="lbl"> Identifikasi beban prognosis secara psikologis</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_observasi][]" id="dk_observasi3" onclick="checkthis('dk_observasi3')" value="Identifikasi pemahaman tentang keputusan perawatan setelah pulang"><span class="lbl"> Identifikasi pemahaman tentang keputusan perawatan setelah pulang</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_observasi][]" id="dk_observasi4" onclick="checkthis('dk_observasi4')" value="Identifikasi kesesuaian antara harapan pasien, keluarga dan tenaga kesehatan"><span class="lbl"> Identifikasi kesesuaian antara harapan pasien, keluarga dan tenaga kesehatan</span></label></div>
      </td>
    </tr>

    <!-- 2. Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Terapeutik</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_terapeutik][]" id="dk_terapeutik1" onclick="checkthis('dk_terapeutik1')" value="Dengarkan masalah, perasaan dan pertanyaan keluarga"><span class="lbl"> Dengarkan masalah, perasaan dan pertanyaan keluarga</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_terapeutik][]" id="dk_terapeutik2" onclick="checkthis('dk_terapeutik2')" value="Terima nilai-nilai keluarga dengan cara yang tidak menghakimi"><span class="lbl"> Terima nilai-nilai keluarga dengan cara yang tidak menghakimi</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_terapeutik][]" id="dk_terapeutik3" onclick="checkthis('dk_terapeutik3')" value="Diskusikan rencana medis dan perawatan"><span class="lbl"> Diskusikan rencana medis dan perawatan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_terapeutik][]" id="dk_terapeutik4" onclick="checkthis('dk_terapeutik4')" value="Fasilitasi pengungkapan perasaan antar pasien dan keluarga atau antar anggota keluarga"><span class="lbl"> Fasilitasi pengungkapan perasaan antar pasien dan keluarga atau antar anggota keluarga</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_terapeutik][]" id="dk_terapeutik5" onclick="checkthis('dk_terapeutik5')" value="Fasilitasi pengambilan keputusan dalam merencanakan perawatan jangka panjang, jika diperlukan"><span class="lbl"> Fasilitasi pengambilan keputusan dalam merencanakan perawatan jangka panjang, jika diperlukan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_terapeutik][]" id="dk_terapeutik6" onclick="checkthis('dk_terapeutik6')" value="Fasilitasi anggota keluarga dalam mengidentifikasi dan menyelesaikan konflik nilai-nilai"><span class="lbl"> Fasilitasi anggota keluarga dalam mengidentifikasi dan menyelesaikan konflik nilai-nilai</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_terapeutik][]" id="dk_terapeutik7" onclick="checkthis('dk_terapeutik7')" value="Fasilitasi pemenuhan kebutuhan dasar keluarga (mis. tempat tinggal, makanan, pakaian)"><span class="lbl"> Fasilitasi pemenuhan kebutuhan dasar keluarga (mis. tempat tinggal, makanan, pakaian)</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_terapeutik][]" id="dk_terapeutik8" onclick="checkthis('dk_terapeutik8')" value="Fasilitasi anggota keluarga melalui proses kematian dan berduka, jika diperlukan"><span class="lbl"> Fasilitasi anggota keluarga melalui proses kematian dan berduka, jika diperlukan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_terapeutik][]" id="dk_terapeutik9" onclick="checkthis('dk_terapeutik9')" value="Fasilitasi memperoleh pengetahuan, keterampilan, dan perawatan yang diperlukan untuk mempertahankan keputusan perawatan pasien"><span class="lbl"> Fasilitasi memperoleh pengetahuan, keterampilan, dan perawatan yang diperlukan untuk mempertahankan keputusan perawatan pasien</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_terapeutik][]" id="dk_terapeutik10" onclick="checkthis('dk_terapeutik10')" value="Bersikap sebagai pengganti keluarga untuk menenangkan pasien dan/atau jika keluarga tidak dapat memberikan perawatan"><span class="lbl"> Bersikap sebagai pengganti keluarga untuk menenangkan pasien dan/atau jika keluarga tidak dapat memberikan perawatan</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_terapeutik][]" id="dk_terapeutik11" onclick="checkthis('dk_terapeutik11')" value="Hargai dan dukung mekanisme koping adaptif yang digunakan keluarga"><span class="lbl"> Hargai dan dukung mekanisme koping adaptif yang digunakan keluarga</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_terapeutik][]" id="dk_terapeutik12" onclick="checkthis('dk_terapeutik12')" value="Berikan kesempatan berkunjung bagi anggota keluarga"><span class="lbl"> Berikan kesempatan berkunjung bagi anggota keluarga</span></label></div>
      </td>
    </tr>

    <!-- 3. Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Edukasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_edukasi][]" id="dk_edukasi1" onclick="checkthis('dk_edukasi1')" value="Informasikan kemajuan pasien secara berkala"><span class="lbl"> Informasikan kemajuan pasien secara berkala</span></label></div>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_edukasi][]" id="dk_edukasi2" onclick="checkthis('dk_edukasi2')" value="Informasikan fasilitas perawatan kesehatan yang tersedia"><span class="lbl"> Informasikan fasilitas perawatan kesehatan yang tersedia</span></label></div>
      </td>
    </tr>

    <!-- 4. Kolaborasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Kolaborasi</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_96[dk_kolaborasi][]" id="dk_kolaborasi1" onclick="checkthis('dk_kolaborasi1')" value="Rujuk untuk terapi keluarga"><span class="lbl"> Rujuk untuk terapi keluarga</span></label></div>
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
        <input type="text" class="input_type" name="form_96[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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
