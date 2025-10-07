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
      var hiddenInputName = 'form_79[ttd_' + role + ']';
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
 <b>DIAGNOSIS KEPERAWATAN: ANSIETAS</b>
</div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-family: tahoma, sans-serif; font-size: 13px;">
  <thead>
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Definisi:</b> Kondisi emosi dan pengalaman subyektif individu terhadap objek yang tidak jelas dan spesifik akibat antisipasi bahaya yang memungkinkan individu melakukan tindakan untuk menghadapi ancaman.
      </td>
    </tr>
  </thead>

  <tbody>
    <!-- PENYEBAB -->
    <tr>
      <td style="border: 1px solid black; padding: 5px; vertical-align: top; width: 50%;">
        <b>PENYEBAB / Berhubungan dengan:</b><br>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_79[penyebab][]" id="penyebab_krisis_situasional" onclick="checkthis('penyebab_krisis_situasional')" value="Krisis situasional">
            <span class="lbl"> Krisis situasional</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_79[penyebab][]" id="penyebab_kebutuhan_tidak_terpenuhi" onclick="checkthis('penyebab_kebutuhan_tidak_terpenuhi')" value="Kebutuhan tidak terpenuhi">
            <span class="lbl"> Kebutuhan tidak terpenuhi</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_79[penyebab][]" id="penyebab_krisis_maturasional" onclick="checkthis('penyebab_krisis_maturasional')" value="Krisis maturasional">
            <span class="lbl"> Krisis maturasional</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_79[penyebab][]" id="penyebab_ancaman_konsep_diri" onclick="checkthis('penyebab_ancaman_konsep_diri')" value="Ancaman terhadap konsep diri">
            <span class="lbl"> Ancaman terhadap konsep diri</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_79[penyebab][]" id="penyebab_ancaman_kematian" onclick="checkthis('penyebab_ancaman_kematian')" value="Ancaman terhadap kematian">
            <span class="lbl"> Ancaman terhadap kematian</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_79[penyebab][]" id="penyebab_kekhawatiran_kegagalan" onclick="checkthis('penyebab_kekhawatiran_kegagalan')" value="Kekhawatiran mengalami kegagalan">
            <span class="lbl"> Kekhawatiran mengalami kegagalan</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_79[penyebab][]" id="penyebab_disfungsi_keluarga" onclick="checkthis('penyebab_disfungsi_keluarga')" value="Disfungsi sistem keluarga">
            <span class="lbl"> Disfungsi sistem keluarga</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_79[penyebab][]" id="penyebab_hubungan_orangtua_anak" onclick="checkthis('penyebab_hubungan_orangtua_anak')" value="Hubungan orang tua-anak tidak memuaskan">
            <span class="lbl"> Hubungan orang tua-anak tidak memuaskan</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_79[penyebab][]" id="penyebab_faktor_keturunan" onclick="checkthis('penyebab_faktor_keturunan')" value="Faktor keturunan (temperamen)">
            <span class="lbl"> Faktor keturunan (temperamen)</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_79[penyebab][]" id="penyebab_penyalahgunaan_zat" onclick="checkthis('penyebab_penyalahgunaan_zat')" value="Penyalahgunaan zat">
            <span class="lbl"> Penyalahgunaan zat</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_79[penyebab][]" id="penyebab_terpapar_bahaya_lingkungan" onclick="checkthis('penyebab_terpapar_bahaya_lingkungan')" value="Terpapar bahaya lingkungan">
            <span class="lbl"> Terpapar bahaya lingkungan (mis: toksin, polutan, lingkungan, dll)</span>
          </label>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" class="ace" name="form_79[penyebab][]" id="penyebab_kurang_terpapar_informasi" onclick="checkthis('penyebab_kurang_terpapar_informasi')" value="Kurang terpapar informasi">
            <span class="lbl"> Kurang terpapar informasi</span>
          </label>
        </div>
      </td>

      <!-- KRITERIA HASIL -->
      <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
        <b>Setelah dilakukan intervensi selama 
          <input type="text" class="input_type" name="form_79[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">
          , maka Toleransi aktivitas meningkat (L.05047) dengan kriteria hasil:</b>
        <br><br>

        <div class="row">
          <div class="col-md-6">
            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kiri][]" id="hasil_verbalisasi_kebingungan" onclick="checkthis('hasil_verbalisasi_kebingungan')" value="Verbalisasi kebingungan menurun">
                <span class="lbl"> Verbalisasi kebingungan menurun</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kiri][]" id="hasil_verbalisasi_khawatir" onclick="checkthis('hasil_verbalisasi_khawatir')" value="Verbalisasi khawatir akibat kondisi menurun">
                <span class="lbl"> Verbalisasi khawatir akibat kondisi yang dihadapi menurun</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kiri][]" id="hasil_perilaku_gelisah" onclick="checkthis('hasil_perilaku_gelisah')" value="Perilaku gelisah menurun">
                <span class="lbl"> Perilaku gelisah menurun</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kiri][]" id="hasil_perilaku_tegang" onclick="checkthis('hasil_perilaku_tegang')" value="Perilaku tegang menurun">
                <span class="lbl"> Perilaku tegang menurun</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kiri][]" id="hasil_keluhan_pusing" onclick="checkthis('hasil_keluhan_pusing')" value="Keluhan pusing menurun">
                <span class="lbl"> Keluhan pusing menurun</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kiri][]" id="hasil_anoreksia" onclick="checkthis('hasil_anoreksia')" value="Anoreksia menurun">
                <span class="lbl"> Anoreksia menurun</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kiri][]" id="hasil_palpitasi" onclick="checkthis('hasil_palpitasi')" value="Palpitasi menurun">
                <span class="lbl"> Palpitasi menurun</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kiri][]" id="hasil_freq_pernafasan" onclick="checkthis('hasil_freq_pernafasan')" value="Frekuensi pernafasan menurun">
                <span class="lbl"> Frekuensi pernafasan menurun</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kiri][]" id="hasil_freq_nadi" onclick="checkthis('hasil_freq_nadi')" value="Frekuensi nadi menurun">
                <span class="lbl"> Frekuensi nadi menurun</span>
              </label>
            </div>
          </div>  

          <div class="col-md-6">
           <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kanan][]" id="hasil_td" onclick="checkthis('hasil_td')" value="TD menurun">
                <span class="lbl"> TD menurun</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kanan][]" id="hasil_diaforesis" onclick="checkthis('hasil_diaforesis')" value="Diaforesis menurun">
                <span class="lbl"> Diaforesis menurun</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kanan][]" id="hasil_tremor" onclick="checkthis('hasil_tremor')" value="Tremor menurun">
                <span class="lbl"> Tremor menurun</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kanan][]" id="hasil_pucat" onclick="checkthis('hasil_pucat')" value="Pucat menurun">
                <span class="lbl"> Pucat menurun</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kanan][]" id="hasil_konsentrasi" onclick="checkthis('hasil_konsentrasi')" value="Konsentrasi membaik">
                <span class="lbl"> Konsentrasi membaik</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kanan][]" id="hasil_pola_tidur" onclick="checkthis('hasil_pola_tidur')" value="Pola tidur membaik">
                <span class="lbl"> Pola tidur membaik</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kanan][]" id="hasil_kontak_mata" onclick="checkthis('hasil_kontak_mata')" value="Kontak mata membaik">
                <span class="lbl"> Kontak mata membaik</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kanan][]" id="hasil_pola_berkemih" onclick="checkthis('hasil_pola_berkemih')" value="Pola berkemih membaik">
                <span class="lbl"> Pola berkemih membaik</span>
              </label>
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" class="ace" name="form_79[hasil_kanan][]" id="hasil_orientasi" onclick="checkthis('hasil_orientasi')" value="Orientasi membaik">
                <span class="lbl"> Orientasi membaik</span>
              </label>
            </div>
          </div>
        </div>
      </td>
    </tr>

    <!-- DIBUKTIKAN DENGAN -->
    <!-- TANDA & GEJALA -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Dibuktikan dengan:</b><br>
        <p><b>Tanda dan Gejala Mayor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>
           <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[mayor_subjektif][]" id="mayor_sub_bingung" onclick="checkthis('mayor_sub_bingung')" value="Merasa bingung">
        <span class="lbl"> Merasa bingung</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[mayor_subjektif][]" id="mayor_sub_khawatir" onclick="checkthis('mayor_sub_khawatir')" value="Merasa khawatir dengan akibat kondisi">
        <span class="lbl"> Merasa khawatir dengan akibat dari kondisi yang dihadapi</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[mayor_subjektif][]" id="mayor_sub_sulit_konsentrasi" onclick="checkthis('mayor_sub_sulit_konsentrasi')" value="Sulit berkonsentrasi">
        <span class="lbl"> Sulit berkonsentrasi</span>
      </label>
    </div>
          </div>

          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>
           <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[mayor_objektif][]" id="mayor_obj_gelisah" onclick="checkthis('mayor_obj_gelisah')" value="Tampak gelisah">
        <span class="lbl"> Tampak gelisah</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[mayor_objektif][]" id="mayor_obj_tegang" onclick="checkthis('mayor_obj_tegang')" value="Tampak tegang">
        <span class="lbl"> Tampak tegang</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[mayor_objektif][]" id="mayor_obj_sulit_tidur" onclick="checkthis('mayor_obj_sulit_tidur')" value="Sulit tidur">
        <span class="lbl"> Sulit tidur</span>
      </label>
    </div>
          </div>
        </div>

        <hr>
        <p><b>Tanda dan Gejala Minor</b></p>
        <div class="row">
          <!-- Subjektif -->
          <div class="col-md-6">
            <b><i>Subjektif:</i></b><br>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_subjektif][]" id="minor_sub_pusing" onclick="checkthis('minor_sub_pusing')" value="Mengeluh pusing">
        <span class="lbl"> Mengeluh pusing</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_subjektif][]" id="minor_sub_anoreksia" onclick="checkthis('minor_sub_anoreksia')" value="Anoreksia">
        <span class="lbl"> Anoreksia</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_subjektif][]" id="minor_sub_palpitasi" onclick="checkthis('minor_sub_palpitasi')" value="Palpitasi">
        <span class="lbl"> Palpitasi</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_subjektif][]" id="minor_sub_tidak_berdaya" onclick="checkthis('minor_sub_tidak_berdaya')" value="Merasa tidak berdaya">
        <span class="lbl"> Merasa tidak berdaya</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_subjektif][]" id="minor_sub_tremor" onclick="checkthis('minor_sub_tremor')" value="Tremor">
        <span class="lbl"> Tremor</span>
      </label>
    </div>

    </div>
          <!-- Objektif -->
          <div class="col-md-6">
            <b>Objektif:</b><br>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_objektif][]" id="minor_obj_frek_nafas" onclick="checkthis('minor_obj_frek_nafas')" value="Frekuensi nafas meningkat">
        <span class="lbl"> Frekuensi nafas meningkat</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_objektif][]" id="minor_obj_frek_nadi" onclick="checkthis('minor_obj_frek_nadi')" value="Frekuensi nadi meningkat">
        <span class="lbl"> Frekuensi nadi meningkat</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_objektif][]" id="minor_obj_td" onclick="checkthis('minor_obj_td')" value="TD meningkat">
        <span class="lbl"> TD meningkat</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_objektif][]" id="minor_obj_diaforesis" onclick="checkthis('minor_obj_diaforesis')" value="Diaforesis">
        <span class="lbl"> Diaforesis</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_objektif][]" id="minor_obj_pucat" onclick="checkthis('minor_obj_pucat')" value="Muka tampak pucat">
        <span class="lbl"> Muka tampak pucat</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_objektif][]" id="minor_obj_suara_bergetar" onclick="checkthis('minor_obj_suara_bergetar')" value="Suara bergetar">
        <span class="lbl"> Suara bergetar</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_objektif][]" id="minor_obj_kontak_mata_buruk" onclick="checkthis('minor_obj_kontak_mata_buruk')" value="Kontak mata buruk">
        <span class="lbl"> Kontak mata buruk</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_objektif][]" id="minor_obj_sering_berkemih" onclick="checkthis('minor_obj_sering_berkemih')" value="Sering berkemih">
        <span class="lbl"> Sering berkemih</span>
      </label>
    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" class="ace" name="form_79[minor_objektif][]" id="minor_obj_orientasi_masa_lalu" onclick="checkthis('minor_obj_orientasi_masa_lalu')" value="Berorientasi pada masa lalu">
        <span class="lbl"> Berorientasi pada masa lalu</span>
      </label>
    </div>
          </div>
        </div>
      </td>
    </tr>
    
  </td>
</tr>
  </tbody>
</table>

<br>
<!-- END -->


    <!-- TERAPI RELAKSASI -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px; font-family: Arial, sans-serif;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 5%; border: 1px solid black; padding: 5px; text-align: center;">NO.</th>
      <th style="width: 95%; border: 1px solid black; padding: 5px; text-align: center;">INTERVENSI KEPERAWATAN UTAMA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Judul -->
    <tr>
      <td colspan="2" style="border: 1px solid black; padding: 5px;">
        <b>Terapi Relaksasi</b><br>
        <i>(Menggunakan teknik peregangan untuk mengurangi tanda dan gejala ketidaknyamanan seperti nyeri, ketegangan otot atau kecemasan)</i><br>
        <b>(I.09326)</b>
      </td>
    </tr>

    <!-- Observasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>1</b></td>
      <td style="border:1px solid black; vertical-align:top;">
        <b>Observasi</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_79[tr_observasi][]" id="tr_observasi1" onclick="checkthis('tr_observasi1')" value="Identifikasi teknik relaksasi yang pernah efektif digunakan">
          <span class="lbl"> Identifikasi teknik relaksasi yang pernah efektif digunakan</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_79[tr_observasi][]" id="tr_observasi2" onclick="checkthis('tr_observasi2')" value="Identifikasi kesediaan, kemampuan, dan penggunaan teknik sebelumnya">
          <span class="lbl"> Identifikasi kesediaan, kemampuan, dan penggunaan teknik sebelumnya</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_79[tr_observasi][]" id="tr_observasi3" onclick="checkthis('tr_observasi3')" value="Periksa ketegangan otot, frekuensi nadi, napas, TD, suhu, sebelum dan sesudah latihan">
          <span class="lbl"> Periksa ketegangan otot, frekuensi nadi, napas, TD, suhu, sebelum dan sesudah latihan</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_79[tr_observasi][]" id="tr_observasi4" onclick="checkthis('tr_observasi4')" value="Monitor respons terhadap terapi relaksasi">
          <span class="lbl"> Monitor respons terhadap terapi relaksasi</span>
        </label></div>

        <div style="margin-top:5px;">Lainnya...
          <input type="text" class="input_type" name="form_79[input_tambahan_observasi]" id="input_tambahan_observasi" onchange="fillthis('input_tambahan_observasi')" style="width:50%;">
        </div>
      </td>
    </tr>

    <!-- Terapeutik -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>2</b></td>
      <td style="border:1px solid black; vertical-align:top;">
        <b>Terapeutik</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_79[tr_terapeutik][]" id="tr_terapeutik1" onclick="checkthis('tr_terapeutik1')" value="Gunakan pakaian longgar">
          <span class="lbl"> Gunakan pakaian longgar</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_79[tr_terapeutik][]" id="tr_terapeutik2" onclick="checkthis('tr_terapeutik2')" value="Ciptakan lingkungan tenang dan nyaman">
          <span class="lbl"> Ciptakan lingkungan tenang dan nyaman</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_79[tr_terapeutik][]" id="tr_terapeutik3" onclick="checkthis('tr_terapeutik3')" value="Berikan informasi tentang persiapan dan prosedur teknik relaksasi">
          <span class="lbl"> Berikan informasi tentang persiapan dan prosedur teknik relaksasi</span>
        </label></div>

        <div style="margin-top:5px;">Lainnya...
          <input type="text" class="input_type" name="form_79[input_tambahan_terapeutik]" id="input_tambahan_terapeutik" onchange="fillthis('input_tambahan_terapeutik')" style="width:50%;">
        </div>
      </td>
    </tr>

    <!-- Edukasi -->
    <tr>
      <td style="text-align:center; border:1px solid black; vertical-align:top;"><b>3</b></td>
      <td style="border:1px solid black; vertical-align:top;">
        <b>Edukasi</b><br>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_79[tr_edukasi][]" id="tr_edukasi1" onclick="checkthis('tr_edukasi1')" value="Jelaskan tujuan dan manfaat teknik relaksasi tarik napas dalam">
          <span class="lbl"> Jelaskan tujuan dan manfaat teknik relaksasi tarik napas dalam</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_79[tr_edukasi][]" id="tr_edukasi2" onclick="checkthis('tr_edukasi2')" value="Anjurkan sering mengulangi teknik relaksasi">
          <span class="lbl"> Anjurkan sering mengulangi teknik relaksasi</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_79[tr_edukasi][]" id="tr_edukasi3" onclick="checkthis('tr_edukasi3')" value="Anjurkan mengambil posisi nyaman">
          <span class="lbl"> Anjurkan mengambil posisi nyaman</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_79[tr_edukasi][]" id="tr_edukasi4" onclick="checkthis('tr_edukasi4')" value="Demonstrasikan dan latih teknik relaksasi tarik napas">
          <span class="lbl"> Demonstrasikan dan latih teknik relaksasi tarik napas</span>
        </label></div>

        <div class="checkbox"><label>
          <input type="checkbox" class="ace" name="form_79[tr_edukasi][]" id="tr_edukasi5" onclick="checkthis('tr_edukasi5')" value="Anjurkan rileks dan merasakan sensasi relaksasi dalam">
          <span class="lbl"> Anjurkan rileks dan merasakan sensasi relaksasi dalam</span>
        </label></div>

        <div style="margin-top:5px;">Lainnya...
          <input type="text" class="input_type" name="form_79[input_tambahan_edukasi]" id="input_tambahan_edukasi" onchange="fillthis('input_tambahan_edukasi')" style="width:50%;">
        </div>
      </td>
    </tr>
  </tbody>
</table>
<!-- END TERAPI RELAKSASI -->


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
        <input type="text" class="input_type" name="form_79[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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