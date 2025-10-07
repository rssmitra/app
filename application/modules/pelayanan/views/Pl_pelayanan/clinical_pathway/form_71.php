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
      var hiddenInputName = 'form_71[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 30 september 2025</p> -->
<div style="text-align: center; font-size: 18px;"><b>DIAGNOSIS KEPERAWATAN:<br> HIPERTERMIA</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size: 13px;">
  <thead>
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
        Definisi : Suhu tubuh meningkat di atas rentang normal karena ketidakseimbangan antara produksi dan kehilangan panas
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <!-- PENYEBAB -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[penyebab][]" id="penyebab_dehidrasi" onclick="checkthis('penyebab_dehidrasi')" value="Dehidrasi"><span class="lbl"> Dehidrasi</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[penyebab][]" id="penyebab_panas" onclick="checkthis('penyebab_panas')" value="Terpapar lingkungan panas"><span class="lbl"> Terpapar lingkungan panas</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[penyebab][]" id="penyebab_penyakit" onclick="checkthis('penyebab_penyakit')" value="Proses penyakit (misal infeksi, kanker)"><span class="lbl"> Proses penyakit (misal infeksi, kanker)</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[penyebab][]" id="penyebab_pakaian" onclick="checkthis('penyebab_pakaian')" value="Ketidaksesuaian pakaian dengan suhu lingkungan"><span class="lbl"> Ketidaksesuaian pakaian dengan suhu lingkungan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[penyebab][]" id="penyebab_metabolisme" onclick="checkthis('penyebab_metabolisme')" value="Peningkatan laju metabolisme"><span class="lbl"> Peningkatan laju metabolisme</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[penyebab][]" id="penyebab_trauma" onclick="checkthis('penyebab_trauma')" value="Respon trauma"><span class="lbl"> Respon trauma</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[penyebab][]" id="penyebab_aktivitas" onclick="checkthis('penyebab_aktivitas')" value="Aktivitas berlebihan"><span class="lbl"> Aktivitas berlebihan</span></label></div>
        <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[penyebab][]" id="penyebab_incubator" onclick="checkthis('penyebab_incubator')" value="Penggunaan incubator"><span class="lbl"> Penggunaan incubator</span></label></div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_71[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;"> ,
          maka suhu tubuh membaik (L.14134) dengan kriteria hasil:</b>

        <div style="display: flex; flex-wrap: wrap; margin-top: 5px;">
          <!-- KIRI -->
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_kulit_merah" onclick="checkthis('hasil_kulit_merah')" value="Kulit merah menurun"><span class="lbl"> Kulit merah menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_menggigil" onclick="checkthis('hasil_menggigil')" value="Menggigil menurun"><span class="lbl"> Menggigil menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_kejang" onclick="checkthis('hasil_kejang')" value="Kejang menurun"><span class="lbl"> Kejang menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_akrosianosis" onclick="checkthis('hasil_akrosianosis')" value="Akrosianosis menurun"><span class="lbl"> Akrosianosis menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_o2" onclick="checkthis('hasil_o2')" value="Konsumsi oksigen menurun"><span class="lbl"> Konsumsi oksigen menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_piloereksi" onclick="checkthis('hasil_piloereksi')" value="Piloereksi menurun"><span class="lbl"> Piloereksi menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_vasokontriksi" onclick="checkthis('hasil_vasokontriksi')" value="Vasokontriksi perifer menurun"><span class="lbl"> Vasokontriksi perifer menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_kutis" onclick="checkthis('hasil_kutis')" value="Kutis memorata menurun"><span class="lbl"> Kutis memorata menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_pucat" onclick="checkthis('hasil_pucat')" value="Pucat menurun"><span class="lbl"> Pucat menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_takikardi" onclick="checkthis('hasil_takikardi')" value="Takikardi menurun"><span class="lbl"> Takikardi menurun</span></label></div>
          </div>

          <!-- KANAN -->
          <div style="width: 50%;">
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_takipnea" onclick="checkthis('hasil_takipnea')" value="Takipnea menurun"><span class="lbl"> Takipnea menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_bradikardi" onclick="checkthis('hasil_bradikardi')" value="Bradikardi menurun"><span class="lbl"> Bradikardi menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_kuku" onclick="checkthis('hasil_kuku')" value="Dasar kuku sianotik menurun"><span class="lbl"> Dasar kuku sianotik menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_hypoksia" onclick="checkthis('hasil_hypoksia')" value="Hypoksia menurun"><span class="lbl"> Hypoksia menurun</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_suhu" onclick="checkthis('hasil_suhu')" value="Suhu tubuh membaik"><span class="lbl"> Suhu tubuh membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_suhu_kulit" onclick="checkthis('hasil_suhu_kulit')" value="Suhu kulit membaik"><span class="lbl"> Suhu kulit membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_glukosa" onclick="checkthis('hasil_glukosa')" value="Kadar glukosa darah membaik"><span class="lbl"> Kadar glukosa darah membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_kapiler" onclick="checkthis('hasil_kapiler')" value="Pengisian kapiler membaik"><span class="lbl"> Pengisian kapiler membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_ventilasi" onclick="checkthis('hasil_ventilasi')" value="Ventilasi membaik"><span class="lbl"> Ventilasi membaik</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[kriteria_hasil][]" id="hasil_td" onclick="checkthis('hasil_td')" value="Tekanan darah membaik"><span class="lbl"> Tekanan darah membaik</span></label></div>
          </div>
        </div>
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
            (Tidak tersedia)
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[mayor_objektif][]" id="mayor_suhu" onclick="checkthis('mayor_suhu')" value="Suhu tubuh diatas nilai normal"><span class="lbl"> Suhu tubuh diatas nilai normal</span></label></div>
          </div>
        </div>

        <hr>

        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
            (Tidak tersedia)
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[minor_objektif][]" id="minor_kulit_merah" onclick="checkthis('minor_kulit_merah')" value="Kulit merah"><span class="lbl"> Kulit merah</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[minor_objektif][]" id="minor_takipnea" onclick="checkthis('minor_takipnea')" value="Takipnea"><span class="lbl"> Takipnea</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[minor_objektif][]" id="minor_kejang" onclick="checkthis('minor_kejang')" value="Kejang"><span class="lbl"> Kejang</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[minor_objektif][]" id="minor_kulit_hangat" onclick="checkthis('minor_kulit_hangat')" value="Kulit terasa hangat"><span class="lbl"> Kulit terasa hangat</span></label></div>
            <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[minor_objektif][]" id="minor_takikardi" onclick="checkthis('minor_takikardi')" value="Takikardi"><span class="lbl"> Takikardi</span></label></div>
          </div>
        </div>
      </td>
    </tr>
    </tbody>
</table>
<br>
<!-- END -->

<!-- MANAJEMEN HIPERTERMI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <tbody>

  <tr>
  <td colspan="2" style="border: 1px solid black; padding: 5px;">
    <!-- <table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;"> -->
      <thead>
        <tr style="background-color: #d3d3d3;">
          <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
          <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
        </tr>
      </thead>
   </tr>  

  <tr>
    <td colspan="2" style="border: 1px solid black; padding: 5px;">
      <b>Manajemen Hipertermi</b>
      <i>(Mengidentifikasi dan mengelola peningkatan suhu tubuh akibat disfungsi termoregulasi)</i>
      <b>(I.15506)</b>
    </td>
  </tr>

  <!-- Observasi -->
  <tr>
    <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
    <td style="border:1px solid black; padding:5px; vertical-align:top;">
      <b>Observasi</b><br>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_observasi][]" id="hipertermi_observasi1" onclick="checkthis('hipertermi_observasi1')" value="Identifikasi penyebab hipertermia"><span class="lbl"> Identifikasi penyebab hipertermia (misal dehidrasi, paparan panas, inkubator)</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_observasi][]" id="hipertermi_observasi2" onclick="checkthis('hipertermi_observasi2')" value="Monitor suhu tubuh"><span class="lbl"> Monitor suhu tubuh</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_observasi][]" id="hipertermi_observasi3" onclick="checkthis('hipertermi_observasi3')" value="Monitor kadar elektrolit"><span class="lbl"> Monitor kadar elektrolit</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_observasi][]" id="hipertermi_observasi4" onclick="checkthis('hipertermi_observasi4')" value="Monitor haluaran urine"><span class="lbl"> Monitor haluaran urine</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_observasi][]" id="hipertermi_observasi5" onclick="checkthis('hipertermi_observasi5')" value="Monitor komplikasi akibat hipertermia"><span class="lbl"> Monitor komplikasi akibat hipertermia</span></label></div>
    </td>
  </tr>

  <!-- Terapeutik -->
  <tr>
    <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
    <td style="border:1px solid black; padding:5px; vertical-align:top;">
      <b>Terapeutik</b><br>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_terapeutik][]" id="hipertermi_terapeutik1" onclick="checkthis('hipertermi_terapeutik1')" value="Sediakan lingkungan yang dingin"><span class="lbl"> Sediakan lingkungan yang dingin</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_terapeutik][]" id="hipertermi_terapeutik2" onclick="checkthis('hipertermi_terapeutik2')" value="Longgarkan atau lepaskan pakaian"><span class="lbl"> Longgarkan atau lepaskan pakaian</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_terapeutik][]" id="hipertermi_terapeutik3" onclick="checkthis('hipertermi_terapeutik3')" value="Basahi dan kipasi permukaan tubuh"><span class="lbl"> Basahi dan kipasi permukaan tubuh</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_terapeutik][]" id="hipertermi_terapeutik4" onclick="checkthis('hipertermi_terapeutik4')" value="Berikan cairan oral"><span class="lbl"> Berikan cairan oral</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_terapeutik][]" id="hipertermi_terapeutik5" onclick="checkthis('hipertermi_terapeutik5')" value="Ganti linen setiap hari"><span class="lbl"> Ganti linen setiap hari atau lebih sering jika hiperhidrosis</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_terapeutik][]" id="hipertermi_terapeutik6" onclick="checkthis('hipertermi_terapeutik6')" value="Lakukan pendinginan eksternal"><span class="lbl"> Lakukan pendinginan eksternal (selimut hipotermia, kompres dingin pada dahi/leher/dada)</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_terapeutik][]" id="hipertermi_terapeutik7" onclick="checkthis('hipertermi_terapeutik7')" value="Hindari antipiretik atau aspirin"><span class="lbl"> Hindari pemberian antipiretik atau aspirin</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_terapeutik][]" id="hipertermi_terapeutik8" onclick="checkthis('hipertermi_terapeutik8')" value="Berikan oksigen jika perlu"><span class="lbl"> Berikan oksigen jika perlu</span></label></div>
    </td>
  </tr>

  <!-- Edukasi -->
  <tr>
    <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
    <td style="border:1px solid black; padding:5px; vertical-align:top;">
      <b>Edukasi</b><br>
      <!-- masih kosong, bisa ditambah sesuai kebutuhan -->
       <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_edukasi][]" id="hipertermi_edukasi1" onclick="checkthis('hipertermi_edukasi1')" value="Anjurkan tirah baring"><span class="lbl"> Anjurkan tirah baring</span></label></div>
    </td>
  </tr>

  <!-- Kolaborasi -->
  <tr>
    <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
    <td style="border:1px solid black; padding:5px; vertical-align:top;">
      <b>Kolaborasi</b><br>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[hipertermi_kolaborasi][]" id="hipertermi_kolaborasi1" onclick="checkthis('hipertermi_kolaborasi1')" value="Kolaborasi pemberian cairan IV"><span class="lbl"> Kolaborasi pemberian cairan dan elektrolit intravena jika perlu</span></label></div>
    </td>
  </tr>

  </tbody>
</table>
<!-- END -->

<!-- REGULASI TEMPERATUR -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <tbody>

  <tr>
    <td colspan="2" style="border: 1px solid black; padding: 5px;">
      <b>Regulasi Temperatur</b>
      <i>(Mempertahankan suhu tubuh dalam rentang normal)</i>
      <b>(I.14578)</b>
    </td>
  </tr>

  <!-- Observasi -->
  <tr>
    <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>1</b></td>
    <td style="border:1px solid black; padding:5px; vertical-align:top;">
      <b>Observasi</b><br>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_observasi][]" id="monitor_suhu_bayi" onclick="checkthis('monitor_suhu_bayi')" value="Monitor suhu bayi"><span class="lbl"> Monitor suhu bayi sampai stabil (36,5 °C sampai 37,5 °C)</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_observasi][]" id="monitor_suhu_anak" onclick="checkthis('monitor_suhu_anak')" value="Monitor suhu anak tiap 2 jam"><span class="lbl"> Monitor suhu tubuh anak tiap 2 jam jika perlu</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_observasi][]" id="monitor_tanda_vital" onclick="checkthis('monitor_tanda_vital')" value="Monitor tanda vital"><span class="lbl"> Monitor tekanan darah, frekuensi pernafasan, dan nadi</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_observasi][]" id="monitor_warna_kulit" onclick="checkthis('monitor_warna_kulit')" value="Monitor warna dan suhu kulit"><span class="lbl"> Monitor warna dan suhu kulit</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_observasi][]" id="monitor_gejala" onclick="checkthis('monitor_gejala')" value="Monitor gejala hypo/hipertermia"><span class="lbl"> Monitor dan catat tanda/gejala hipotermia atau hipertermia</span></label></div>
    </td>
  </tr>

  <!-- Terapeutik -->
  <tr>
    <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>2</b></td>
    <td style="border:1px solid black; padding:5px; vertical-align:top;">
      <b>Terapeutik</b><br>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="alat_pemantau" onclick="checkthis('alat_pemantau')" value="Pasang alat pemantau suhu"><span class="lbl"> Pasang alat pemantau suhu kontinu jika perlu</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="asupan_cairan" onclick="checkthis('asupan_cairan')" value="Tingkatkan asupan cairan"><span class="lbl"> Tingkatkan asupan cairan dan nutrisi yang adekuat</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="bedong_bayi" onclick="checkthis('bedong_bayi')" value="Bedong bayi"><span class="lbl"> Bedong bayi segera setelah lahir untuk mencegah kehilangan panas</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="bayi_bblr_plastik" onclick="checkthis('bayi_bblr_plastik')" value="Masukan bayi BBLR ke plastik"><span class="lbl"> Masukan bayi BBLR kedalam plastik segera setelah lahir</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="gunakan_topi" onclick="checkthis('gunakan_topi')" value="Gunakan topi bayi"><span class="lbl"> Gunakan topi bayi untuk mencegah kehilangan panas pada bayi baru lahir</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="radiant_warmer" onclick="checkthis('radiant_warmer')" value="Tempatkan bayi dibawah radiant warmer"><span class="lbl"> Tempatkan bayi baru lahir dibawah radiant warmer</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="kelembaban_incubator" onclick="checkthis('kelembaban_incubator')" value="Atur kelembaban incubator"><span class="lbl"> Pertahankan kelembaban incubator ≥ 50% untuk kurangi kehilangan panas</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="suhu_incubator" onclick="checkthis('suhu_incubator')" value="Atur suhu incubator"><span class="lbl"> Atur suhu incubator sesuai kebutuhan</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="hangatkan_bahan" onclick="checkthis('hangatkan_bahan')" value="Hangatkan bahan kontak bayi"><span class="lbl"> Hangatkan terlebih dahulu bahan yang akan kontak dengan bayi (selimut, kain, stetoskop)</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="hindari_dingin" onclick="checkthis('hindari_dingin')" value="Hindari paparan dingin"><span class="lbl"> Hindari meletakan bayi didekat jendela terbuka, AC, atau kipas</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="alat_penghangat" onclick="checkthis('alat_penghangat')" value="Gunakan alat penghangat"><span class="lbl"> Gunakan matras, selimut hangat, atau penghangat ruangan bila perlu</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="alat_pendingin" onclick="checkthis('alat_pendingin')" value="Gunakan alat pendingin"><span class="lbl"> Gunakan kasur pendingin, ice pack, atau intravascular cooling catheterization untuk menurunkan suhu</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_terapeutik][]" id="suhu_lingkungan" onclick="checkthis('suhu_lingkungan')" value="Sesuaikan suhu lingkungan"><span class="lbl"> Sesuaikan suhu lingkungan dengan kebutuhan pasien</span></label></div>
    </td>
  </tr>

  <!-- Edukasi -->
  <tr>
    <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>3</b></td>
    <td style="border:1px solid black; padding:5px; vertical-align:top;">
      <b>Edukasi</b><br>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_edukasi][]" id="pencegahan_heat" onclick="checkthis('pencegahan_heat')" value="Pencegahan heat exhaustion"><span class="lbl"> Jelaskan cara pencegahan heat exhaustion dan heat stroke</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_edukasi][]" id="pencegahan_hipotermi" onclick="checkthis('pencegahan_hipotermi')" value="Pencegahan hipotermi"><span class="lbl"> Jelaskan cara pencegahan hipotermi akibat udara dingin</span></label></div>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_edukasi][]" id="metode_kanguru" onclick="checkthis('metode_kanguru')" value="Metode kanguru"><span class="lbl"> Demonstrasikan metode perawatan kanguru (PMK) untuk bayi BBLR</span></label></div>
    </td>
  </tr>

  <!-- Kolaborasi -->
  <tr>
    <td style="text-align:center; border:1px solid black; padding:5px; vertical-align:top;"><b>4</b></td>
    <td style="border:1px solid black; padding:5px; vertical-align:top;">
      <b>Kolaborasi</b><br>
      <div class="checkbox"><label><input type="checkbox" class="ace" name="form_71[temp_kolaborasi][]" id="kolaborasi_temp" onclick="checkthis('kolaborasi_temp')" value="Kolaborasi regulasi temperatur"><span class="lbl"> Kolaborasi pemberian antipiretik jika perlu</span></label></div>
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
        <input type="text" class="input_type" name="form_71[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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