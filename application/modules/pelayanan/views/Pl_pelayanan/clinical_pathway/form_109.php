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
      var hiddenInputName = 'form_109[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 10 oktober 2025</p> -->

<div style="text-align: center; font-size: 18px;">
  <b>DIAGNOSIS KEPERAWATAN: BERAT BADAN LEBIH</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px;" colspan="2">
        <b>Definisi:</b> Akumulasi lemak berlebih atau abnormal yang tidak sesuai dengan usia dan jenis kelamin.
      </td>
    </tr>
  </thead>

  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="width:50%; border:1px solid black; padding:5px; vertical-align:top;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab1" onclick="checkthis('bb_penyebab1')" value="Kurang aktifitas fisik harian"><span class="lbl"> Kurang aktifitas fisik harian</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab2" onclick="checkthis('bb_penyebab2')" value="Kelebihan konsumsi gula"><span class="lbl"> Kelebihan konsumsi gula</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab3" onclick="checkthis('bb_penyebab3')" value="Gangguan kebiasaan makan"><span class="lbl"> Gangguan kebiasaan makan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab4" onclick="checkthis('bb_penyebab4')" value="Gangguan persepsi makan"><span class="lbl"> Gangguan persepsi makan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab5" onclick="checkthis('bb_penyebab5')" value="Kelebihan konsumsi alkohol"><span class="lbl"> Kelebihan konsumsi alkohol</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab6" onclick="checkthis('bb_penyebab6')" value="Penggunaan energi kurang dari asupan"><span class="lbl"> Penggunaan energi kurang dari asupan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab7" onclick="checkthis('bb_penyebab7')" value="Sering mengemil"><span class="lbl"> Sering mengemil</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab8" onclick="checkthis('bb_penyebab8')" value="Sering memakan makanan berminyak/berlemak"><span class="lbl"> Sering memakan makanan berminyak/berlemak</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab9" onclick="checkthis('bb_penyebab9')" value="Faktor keturunan (misal distribusi jaringan adiposa, pengeluaran energi, aktifitas lipase lipoprotein, sintesis lipit, lipolisis)"><span class="lbl"> Faktor keturunan (misal distribusi jaringan adiposa, pengeluaran energi, aktifitas lipase lipoprotein, sintesis lipit, lipolisis)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab10" onclick="checkthis('bb_penyebab10')" value="Penggunaan makanan formula atau makanan campuran (pada bayi)"><span class="lbl"> Penggunaan makanan formula atau makanan campuran (pada bayi)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab11" onclick="checkthis('bb_penyebab11')" value="Asupan kalsium rendah (pada anak-anak)"><span class="lbl"> Asupan kalsium rendah (pada anak-anak)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab12" onclick="checkthis('bb_penyebab12')" value="Berat badan bertambah cepat (selama masa anak-anak, masa bayi, termasuk minggu pertama, 4 bulan pertama & tahun pertama)"><span class="lbl"> Berat badan bertambah cepat (selama masa anak-anak, masa bayi, termasuk minggu pertama, 4 bulan pertama & tahun pertama)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[penyebab][]" id="bb_penyebab13" onclick="checkthis('bb_penyebab13')" value="Makanan padat sebagai sumber makanan utama pada usia kurang dari 5 bulan"><span class="lbl"> Makanan padat sebagai sumber makanan utama pada usia kurang dari 5 bulan</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border:1px solid black; padding:5px; vertical-align:top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_109[bb_intervensi_selama]" id="bb_intervensi_selama" onchange="fillthis('bb_intervensi_selama')" style="width:10%;">,
          maka berat badan membaik (L.03018), dengan kriteria hasil:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[kriteria_hasil][]" id="bb_krit1" onclick="checkthis('bb_krit1')" value="Berat badan membaik"><span class="lbl"> Berat badan membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[kriteria_hasil][]" id="bb_krit2" onclick="checkthis('bb_krit2')" value="Tebal lipatan kulit membaik"><span class="lbl"> Tebal lipatan kulit membaik</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[kriteria_hasil][]" id="bb_krit3" onclick="checkthis('bb_krit3')" value="Indeks massa tubuh membaik"><span class="lbl"> Indeks massa tubuh membaik</span></label></div>
      </td>
    </tr>

    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>Dibuktikan dengan:</b><br>

        <p><b>Tanda dan Gejala Mayor:</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif :</i></b><br>
            (Tidak tersedia)
          </div>
          <div class="col-md-6">
            <b>Objektif :</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[mayor_objektif][]" id="bb_mayor_obj1" onclick="checkthis('bb_mayor_obj1')" value="IMT 25-27 kg/m² (pada dewasa) atau berat dan panjang badan lebih dari presentil 95 (pada anak <2 tahun) atau IMT pada presentil ke 85-95 (pada anak 2-18 tahun)"><span class="lbl"> IMT 25-27 kg/m² (pada dewasa) atau berat dan panjang badan lebih dari presentil 95 (pada anak &lt;2 tahun) atau IMT pada presentil ke 85-95 (pada anak 2-18 tahun)</span></label></div>
          </div>
        </div>

        <hr>
        <p><b>Tanda dan Gejala Minor:</b></p>
        <div class="row">
          <div class="col-md-6">
            <b><i>Subjektif :</i></b><br>
            (Tidak tersedia)
          </div>
          <div class="col-md-6">
            <b>Objektif :</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_109[minor_objektif][]" id="bb_minor_obj1" onclick="checkthis('bb_minor_obj1')" value="Tebal lipatan kulit trisep >25 mm"><span class="lbl"> Tebal lipatan kulit trisep &gt;25 mm</span></label></div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<br>
<!-- END -->



<!-- KONSELING NUTRISI & MANAJEMEN BERAT BADAN & PROMOSI LATIHAN FISIK -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>

  <tbody>
    <!-- KONSELING NUTRISI -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>KONSELING NUTRISI</b><br>
        <i>(Memberikan bimbingan dalam melakukan modifikasi asupan nutrisi)</i><br>
        <b>(I.03094)</b>
      </td>
    </tr>

    <!-- Observasi Konseling Nutrisi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[konseling_observasi][]" id="konseling_observasi1" onclick="checkthis('konseling_observasi1')" value="Identifikasi kebiasaan makan dan perilaku makan yang akan diubah">
            <span class="lbl"> Identifikasi kebiasaan makan dan perilaku makan yang akan diubah</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[konseling_observasi][]" id="konseling_observasi2" onclick="checkthis('konseling_observasi2')" value="Identifikasi kemajuan modifikasi diet secara reguler">
            <span class="lbl"> Identifikasi kemajuan modifikasi diet secara reguler</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[konseling_observasi][]" id="konseling_observasi3" onclick="checkthis('konseling_observasi3')" value="Monitor intake dan output cairan, nilai hemoglobin, tekanan darah, kenaikan berat badan, dan kebiasaan membeli makanan">
            <span class="lbl"> Monitor intake dan output cairan, nilai hemoglobin, tekanan darah, kenaikan berat badan, dan kebiasaan membeli makanan</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Terapeutik Konseling Nutrisi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[konseling_terapeutik][]" id="konseling_terapeutik1" onclick="checkthis('konseling_terapeutik1')" value="Bina hubungan terapeutik">
            <span class="lbl"> Bina hubungan terapeutik</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[konseling_terapeutik][]" id="konseling_terapeutik2" onclick="checkthis('konseling_terapeutik2')" value="Sepakati lama waktu pemberian konseling">
            <span class="lbl"> Sepakati lama waktu pemberian konseling</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[konseling_terapeutik][]" id="konseling_terapeutik3" onclick="checkthis('konseling_terapeutik3')" value="Tetapkan tujuan jangka pendek dan jangka panjang yang realistis">
            <span class="lbl"> Tetapkan tujuan jangka pendek dan jangka panjang yang realistis</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[konseling_terapeutik][]" id="konseling_terapeutik4" onclick="checkthis('konseling_terapeutik4')" value="Gunakan standar nutrisi sesuai program diet dalam mengevaluasi kecukupan asupan makanan">
            <span class="lbl"> Gunakan standar nutrisi sesuai program diet dalam mengevaluasi kecukupan asupan makanan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[konseling_terapeutik][]" id="konseling_terapeutik5" onclick="checkthis('konseling_terapeutik5')" value="Pertimbangkan faktor-faktor yang mempengaruhi pemenuhan kebutuhan gizi (misal usia, tahap pertumbuhan dan perkembangan, penyakit)">
            <span class="lbl"> Pertimbangkan faktor-faktor yang mempengaruhi pemenuhan kebutuhan gizi (misal usia, tahap pertumbuhan dan perkembangan, penyakit)</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Edukasi Konseling Nutrisi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Edukasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[konseling_edukasi][]" id="konseling_edukasi1" onclick="checkthis('konseling_edukasi1')" value="Informasikan perlunya modifikasi diet (misal penurunan atau penambahan berat badan. Pembatasan natrium atau cairan, pengurangan kolestrol)">
            <span class="lbl"> Informasikan perlunya modifikasi diet (misal penurunan atau penambahan berat badan, pembatasan natrium atau cairan, pengurangan kolesterol)</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[konseling_edukasi][]" id="konseling_edukasi2" onclick="checkthis('konseling_edukasi2')" value="Jelaskan program gizi dan persepsi pasien terhadap diet yang diprogramkan">
            <span class="lbl"> Jelaskan program gizi dan persepsi pasien terhadap diet yang diprogramkan</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Kolaborasi Konseling Nutrisi -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Kolaborasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[konseling_kolaborasi][]" id="konseling_kolaborasi1" onclick="checkthis('konseling_kolaborasi1')" value="Rujuk pada ahli gizi jika perlu">
            <span class="lbl"> Rujuk pada ahli gizi jika perlu</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- MANAJEMEN BERAT BADAN -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>MANAJEMEN BERAT BADAN</b><br>
        <i>(Mengidentifikasi dan mengelola berat badan agar dalam rentang optimal)</i><br>
        <b>(I.03097)</b>
      </td>
    </tr>

    <!-- Observasi Manajemen Berat Badan -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[bb_observasi][]" id="bb_observasi1" onclick="checkthis('bb_observasi1')" value="Identifikasi kondisi kesehatan pasien yang dapat mempengaruhi berat badan">
            <span class="lbl"> Identifikasi kondisi kesehatan pasien yang dapat mempengaruhi berat badan</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Terapeutik Manajemen Berat Badan -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[bb_terapeutik][]" id="bb_terapeutik1" onclick="checkthis('bb_terapeutik1')" value="Hitung berat badan ideal pasien">
            <span class="lbl"> Hitung berat badan ideal pasien</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[bb_terapeutik][]" id="bb_terapeutik2" onclick="checkthis('bb_terapeutik2')" value="Hitung presentase lemak dan otot pasien">
            <span class="lbl"> Hitung presentase lemak dan otot pasien</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[bb_terapeutik][]" id="bb_terapeutik3" onclick="checkthis('bb_terapeutik3')" value="Fasilitasi menentukan target berat badan yang realistis">
            <span class="lbl"> Fasilitasi menentukan target berat badan yang realistis</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Edukasi Manajemen Berat Badan -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Edukasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[bb_edukasi][]" id="bb_edukasi1" onclick="checkthis('bb_edukasi1')" value="Jelaskan hubungan antara asupan makanan, aktifitas fisik, penambahan berat badan dan pengurangan berat badan">
            <span class="lbl"> Jelaskan hubungan antara asupan makanan, aktifitas fisik, penambahan berat badan dan pengurangan berat badan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[bb_edukasi][]" id="bb_edukasi2" onclick="checkthis('bb_edukasi2')" value="Jelaskan faktor resiko berat badan lebih dan berat badan kurang">
            <span class="lbl"> Jelaskan faktor resiko berat badan lebih dan berat badan kurang</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[bb_edukasi][]" id="bb_edukasi3" onclick="checkthis('bb_edukasi3')" value="Anjurkan mencatat berat badan setiap minggu jika perlu">
            <span class="lbl"> Anjurkan mencatat berat badan setiap minggu jika perlu</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[bb_edukasi][]" id="bb_edukasi4" onclick="checkthis('bb_edukasi4')" value="Anjurkan melakukan pencatatan asupan makan, aktifitas fisik dan perubahan berat badan">
            <span class="lbl"> Anjurkan melakukan pencatatan asupan makan, aktifitas fisik dan perubahan berat badan</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- PROMOSI LATIHAN FISIK -->
    <tr>
      <td colspan="2" style="border:1px solid black; padding:5px;">
        <b>PROMOSI LATIHAN FISIK</b><br>
        <i>(Memfasilitasi aktivitas fisik reguler untuk mempertahankan atau meningkatkan kebugaran dan kesehatan)</i><br>
        <b>(I.05183)</b>
      </td>
    </tr>

    <!-- Observasi Latihan Fisik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Observasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_observasi][]" id="latihan_observasi1" onclick="checkthis('latihan_observasi1')" value="Identifikasi keyakinan kesehatan tentang latihan fisik">
            <span class="lbl"> Identifikasi keyakinan kesehatan tentang latihan fisik</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_observasi][]" id="latihan_observasi2" onclick="checkthis('latihan_observasi2')" value="Identifikasi pengalaman olahraga sebelumnya">
            <span class="lbl"> Identifikasi pengalaman olahraga sebelumnya</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_observasi][]" id="latihan_observasi3" onclick="checkthis('latihan_observasi3')" value="Identifikasi motivasi individu untuk memulai atau melanjutkan program olahraga">
            <span class="lbl"> Identifikasi motivasi individu untuk memulai atau melanjutkan program olahraga</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_observasi][]" id="latihan_observasi4" onclick="checkthis('latihan_observasi4')" value="Identifikasi hambatan untuk berolahraga">
            <span class="lbl"> Identifikasi hambatan untuk berolahraga</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_observasi][]" id="latihan_observasi5" onclick="checkthis('latihan_observasi5')" value="Monitor kepatuhan menjalankan program latihan">
            <span class="lbl"> Monitor kepatuhan menjalankan program latihan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_observasi][]" id="latihan_observasi6" onclick="checkthis('latihan_observasi6')" value="Monitor respon terhadap program latihan">
            <span class="lbl"> Monitor respon terhadap program latihan</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Terapeutik Latihan Fisik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Terapeutik</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_terapeutik][]" id="latihan_terapeutik1" onclick="checkthis('latihan_terapeutik1')" value="Motivasi mengungkapkan perasaan tentang olahraga atau kebutuhan berolahraga">
            <span class="lbl"> Motivasi mengungkapkan perasaan tentang olahraga atau kebutuhan berolahraga</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_terapeutik][]" id="latihan_terapeutik2" onclick="checkthis('latihan_terapeutik2')" value="Motivasi memulai atau melanjutkan olahraga">
            <span class="lbl"> Motivasi memulai atau melanjutkan olahraga</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_terapeutik][]" id="latihan_terapeutik3" onclick="checkthis('latihan_terapeutik3')" value="Fasilitasi dalam mengidentifikasi model peran positif untuk mempertahankan program latihan">
            <span class="lbl"> Fasilitasi dalam mengidentifikasi model peran positif untuk mempertahankan program latihan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_terapeutik][]" id="latihan_terapeutik4" onclick="checkthis('latihan_terapeutik4')" value="Fasilitasi dalam mengembangkan program latihan yang sesuai untuk memenuhi kebutuhan">
            <span class="lbl"> Fasilitasi dalam mengembangkan program latihan yang sesuai untuk memenuhi kebutuhan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_terapeutik][]" id="latihan_terapeutik5" onclick="checkthis('latihan_terapeutik5')" value="Fasilitasi dalam menetapkan tujuan jangka pendek dan panjang program latihan">
            <span class="lbl"> Fasilitasi dalam menetapkan tujuan jangka pendek dan panjang program latihan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_terapeutik][]" id="latihan_terapeutik6" onclick="checkthis('latihan_terapeutik6')" value="Fasilitasi dalam menjadwalkan periode reguler latihan rutin mingguan">
            <span class="lbl"> Fasilitasi dalam menjadwalkan periode reguler latihan rutin mingguan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_terapeutik][]" id="latihan_terapeutik7" onclick="checkthis('latihan_terapeutik7')" value="Fasilitasi dalam mempertahankan kemajuan program latihan">
            <span class="lbl"> Fasilitasi dalam mempertahankan kemajuan program latihan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_terapeutik][]" id="latihan_terapeutik8" onclick="checkthis('latihan_terapeutik8')" value="Lakukan aktifitas olahraga bersama pasien jika perlu">
            <span class="lbl"> Lakukan aktifitas olahraga bersama pasien jika perlu</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_terapeutik][]" id="latihan_terapeutik9" onclick="checkthis('latihan_terapeutik9')" value="Libatkan keluarga dalam merencanakan dan memelihara program latihan">
            <span class="lbl"> Libatkan keluarga dalam merencanakan dan memelihara program latihan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_terapeutik][]" id="latihan_terapeutik10" onclick="checkthis('latihan_terapeutik10')" value="Berikan umpan balik positif terhadap setiap upaya yang dijalankan pasien">
            <span class="lbl"> Berikan umpan balik positif terhadap setiap upaya yang dijalankan pasien</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Edukasi Latihan Fisik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Edukasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_edukasi][]" id="latihan_edukasi1" onclick="checkthis('latihan_edukasi1')" value="Jelaskan manfaat kesehatan dan efek fisiologis olahraga">
            <span class="lbl"> Jelaskan manfaat kesehatan dan efek fisiologis olahraga</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_edukasi][]" id="latihan_edukasi2" onclick="checkthis('latihan_edukasi2')" value="Jelaskan jenis latihan yang sesuai dengan kondisi kesehatan">
            <span class="lbl"> Jelaskan jenis latihan yang sesuai dengan kondisi kesehatan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_edukasi][]" id="latihan_edukasi3" onclick="checkthis('latihan_edukasi3')" value="Jelaskan frekuensi, durasi, dan intensitas program latihan yang diinginkan">
            <span class="lbl"> Jelaskan frekuensi, durasi, dan intensitas program latihan yang diinginkan</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_edukasi][]" id="latihan_edukasi4" onclick="checkthis('latihan_edukasi4')" value="Ajarkan latihan pemanasan dan pendinginan yang tepat">
            <span class="lbl"> Ajarkan latihan pemanasan dan pendinginan yang tepat</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_edukasi][]" id="latihan_edukasi5" onclick="checkthis('latihan_edukasi5')" value="Ajarkan teknik menghindarkan cedera saat berolahraga">
            <span class="lbl"> Ajarkan teknik menghindarkan cedera saat berolahraga</span>
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_edukasi][]" id="latihan_edukasi6" onclick="checkthis('latihan_edukasi6')" value="Ajarkan teknik pernafasan yang tepat untuk memaksimalkan penyerapan oksigen selama latihan fisik">
            <span class="lbl"> Ajarkan teknik pernafasan yang tepat untuk memaksimalkan penyerapan oksigen selama latihan fisik</span>
          </label>
        </div>
      </td>
    </tr>

    <!-- Kolaborasi Latihan Fisik -->
    <tr>
      <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
      <td style="border:1px solid black; padding:5px;">
        <b>Kolaborasi</b><br>
        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_109[latihan_kolaborasi][]" id="latihan_kolaborasi1" onclick="checkthis('latihan_kolaborasi1')" value="Kolaborasi dengan rehabilitasi medis atau ahli fisiologi olahraga jika perlu">
            <span class="lbl"> Kolaborasi dengan rehabilitasi medis atau ahli fisiologi olahraga jika perlu</span>
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
        <input type="text" class="input_type" name="form_109[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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