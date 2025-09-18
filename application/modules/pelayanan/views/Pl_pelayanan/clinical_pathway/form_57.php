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
      ttdCtx.clearRect(0, 0, ttdCanvas.width, ttdCanvas.height);
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
      var hiddenInputName = 'form_57[ttd_' + role + ']';
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

<div style="text-align: center; font-size: 18px;"><b>DIAGNOSA KEPERAWATAN: NYERI AKUT</b></div>
<br>

<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
    <thead>
     <tr>
        <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">Definisi : Pengalaman sensorik atau emosional yang berkaitan dengan kerusakan jaringan aktual atau fungsional, dengan onset mendadak atau lambat dan berintensitas ringan hingga berat yang berlangsung kurang dari 3 bulan
        </td>
     </tr>
        <tr>
            <th style="border: 1px solid black; padding: 5px;">PENYEBAB/Berhubungan dengan:</th>
            <th style="border: 1px solid black; padding: 5px;">Setelah dilakukan intervensi selama <input type="text" class="input_type" name="form_57[ket_intervensi_selama]" id="ket_intervensi_selama" onchange="fillthis('ket_intervensi_selama')" style="width:10%;">, maka tingkat nyeri menurun (L.08066), dengan kriteria hasil:</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[penyebab][]" id="penyebab_fisiologis" onclick="checkthis('penyebab_fisiologis')" value="Agen pencedera fisiologis">
                        <span class="lbl"> Agen pencedera fisiologis (misal inflamasi, iskemia, neoplasma)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[penyebab][]" id="penyebab_kimiawi" onclick="checkthis('penyebab_kimiawi')" value="Agen pencedera kimiawi">
                        <span class="lbl"> Agen pencedera kimiawi (misal terbakar, bahan kimia iritan)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[penyebab][]" id="penyebab_fisik" onclick="checkthis('penyebab_fisik')" value="Agen pencedera fisik">
                        <span class="lbl"> Agen pencedera fisik (misal abses, amputasi, terbakar terpotong, mengangkat berat, prosedur operasi trauma, latihan fisik berlebihan)</span>
                    </label>
                </div>
            </td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <div style="display: flex; flex-wrap: wrap;">
                    <div style="width: 50%;">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_meningkat" onclick="checkthis('kriteria_meningkat')" value="Kemampuan menuntaskan aktivitas meningkat">
                                <span class="lbl"> Kemampuan menuntaskan aktivitas meningkat</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_nyeri_menurun" onclick="checkthis('kriteria_nyeri_menurun')" value="Keluhan nyeri menurun">
                                <span class="lbl"> Keluhan nyeri menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_meringis_menurun" onclick="checkthis('kriteria_meringis_menurun')" value="Meringis menurun">
                                <span class="lbl"> Meringis menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_sikap_menurun" onclick="checkthis('kriteria_sikap_menurun')" value="Sikap protektif menurun">
                                <span class="lbl"> Sikap protektif menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_gelisah_menurun" onclick="checkthis('kriteria_gelisah_menurun')" value="Gelisah menurun">
                                <span class="lbl"> Gelisah menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_sulit_tidur" onclick="checkthis('kriteria_sulit_tidur')" value="Kesulitan tidur menurun">
                                <span class="lbl"> Kesulitan tidur menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_berfokus_diri" onclick="checkthis('kriteria_berfokus_diri')" value="Berfokus pada diri sendiri menurun">
                                <span class="lbl"> Berfokus pada diri sendiri menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_menarik_diri" onclick="checkthis('kriteria_menarik_diri')" value="Menarik diri menurun">
                                <span class="lbl"> Menarik diri menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_diaforesis" onclick="checkthis('kriteria_diaforesis')" value="Diaforesis menurun">
                                <span class="lbl"> Diaforesis menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_depresi" onclick="checkthis('kriteria_depresi')" value="Perasaan depresi (tertekan) menurun">
                                <span class="lbl"> Perasaan depresi (tertekan) menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_takut" onclick="checkthis('kriteria_takut')" value="Perasaan takut mengalami cedera berulang menurun">
                                <span class="lbl"> Perasaan takut mengalami cedera berulang menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_anoreksia" onclick="checkthis('kriteria_anoreksia')" value="Anoreksia menurun">
                                <span class="lbl"> Anoreksia menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_perineum" onclick="checkthis('kriteria_perineum')" value="Perineum terasa tertekan menurun">
                                <span class="lbl"> Perineum terasa tertekan menurun</span>
                            </label>
                        </div>
                    </div>
                    <div style="width: 50%;">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_uterus" onclick="checkthis('kriteria_uterus')" value="Uterus teraba membulat menurun">
                                <span class="lbl"> Uterus teraba membulat menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_ketegangan" onclick="checkthis('kriteria_ketegangan')" value="Ketegangan otot menurun">
                                <span class="lbl"> Ketegangan otot menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_pupil" onclick="checkthis('kriteria_pupil')" value="Pupil dilatasi menurun">
                                <span class="lbl"> Pupil dilatasi menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_muntah" onclick="checkthis('kriteria_muntah')" value="Muntah menurun">
                                <span class="lbl"> Muntah menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_mual" onclick="checkthis('kriteria_mual')" value="Mual menurun">
                                <span class="lbl"> Mual menurun</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_frekuensi_nadi" onclick="checkthis('kriteria_frekuensi_nadi')" value="Frekuensi nadi membaik">
                                <span class="lbl"> Frekuensi nadi membaik</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_pola_nafas" onclick="checkthis('kriteria_pola_nafas')" value="Pola nafas membaik">
                                <span class="lbl"> Pola nafas membaik</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_tekanan_darah" onclick="checkthis('kriteria_tekanan_darah')" value="Tekanan darah membaik">
                                <span class="lbl"> Tekanan darah membaik</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_proses_berpikir" onclick="checkthis('kriteria_proses_berpikir')" value="Proses berpikir membaik">
                                <span class="lbl"> Proses berpikir membaik</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_fokus" onclick="checkthis('kriteria_fokus')" value="Fokus membaik">
                                <span class="lbl"> Fokus membaik</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_berkemih" onclick="checkthis('kriteria_berkemih')" value="Fungsi berkemih membaik">
                                <span class="lbl"> Fungsi berkemih membaik</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_prilaku" onclick="checkthis('kriteria_prilaku')" value="Prilaku membaik">
                                <span class="lbl"> Prilaku membaik</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_nafsu_makan" onclick="checkthis('kriteria_nafsu_makan')" value="Nafsu makan membaik">
                                <span class="lbl"> Nafsu makan membaik</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[kriteria_hasil][]" id="kriteria_pola_tidur" onclick="checkthis('kriteria_pola_tidur')" value="Pola tidur membaik">
                                <span class="lbl"> Pola tidur membaik</span>
                            </label>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
            <p><b>Dibuktikan dengan: </b></p>    
            <p><b>Tanda dan Gejala Mayor</b></p>
                <div style="display: flex; flex-wrap: wrap;">
                    <div style="width: 50%;">
                        <p><b><i>Subjektif:</i></b></p>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_mayor_subjektif][]" id="gejala_mayor_mengeluh" onclick="checkthis('gejala_mayor_mengeluh')" value="Mengeluh nyeri">
                                <span class="lbl"> Mengeluh nyeri</span>
                            </label>
                        </div>
                    </div>
                    <div style="width: 50%;">
                        <p><b><i>Objektif:</i></b></p>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_mayor_objektif][]" id="gejala_mayor_meringis" onclick="checkthis('gejala_mayor_meringis')" value="Tampak meringis">
                                <span class="lbl"> Tampak meringis</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_mayor_objektif][]" id="gejala_mayor_protektif" onclick="checkthis('gejala_mayor_protektif')" value="Bersikap protektif">
                                <span class="lbl"> Bersikap protektif (mis. Waspada, posisi menghindari nyeri)</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_mayor_objektif][]" id="gejala_mayor_gelisah" onclick="checkthis('gejala_mayor_gelisah')" value="Gelisah">
                                <span class="lbl"> Gelisah</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_mayor_objektif][]" id="gejala_mayor_sulit_tidur" onclick="checkthis('gejala_mayor_sulit_tidur')" value="Sulit tidur">
                                <span class="lbl"> Sulit tidur</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_mayor_objektif][]" id="gejala_mayor_frekuensi" onclick="checkthis('gejala_mayor_frekuensi')" value="Frekuensi nadi menurun">
                                <span class="lbl"> Frekuensi nadi menurun</span>
                            </label>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;" colspan="2">
                <p><b>Tanda dan Gejala Minor</b></p>
                <div style="display: flex; flex-wrap: wrap;">
                    <div style="width: 50%;">
                        <p><b><i>Subjektif:</i></b></p>
                        <p>(Tidak tersedia)</p>
                    </div>
                    <div style="width: 50%;">
                        <p><b><i>Objektif:</i></b></p>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_minor_objektif][]" id="gejala_minor_tekanan_darah" onclick="checkthis('gejala_minor_tekanan_darah')" value="Tekanan darah meningkat">
                                <span class="lbl"> Tekanan darah meningkat</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_minor_objektif][]" id="gejala_minor_pola_nafas" onclick="checkthis('gejala_minor_pola_nafas')" value="Pola nafas berubah">
                                <span class="lbl"> Pola nafas berubah</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_minor_objektif][]" id="gejala_minor_nafsu_makan" onclick="checkthis('gejala_minor_nafsu_makan')" value="Nafsu makan berubah">
                                <span class="lbl"> Nafsu makan berubah</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_minor_objektif][]" id="gejala_minor_proses_berpikir" onclick="checkthis('gejala_minor_proses_berpikir')" value="Proses berpikir terganggu">
                                <span class="lbl"> Proses berpikir terganggu</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_minor_objektif][]" id="gejala_minor_menarik_diri" onclick="checkthis('gejala_minor_menarik_diri')" value="Menarik diri">
                                <span class="lbl"> Menarik diri</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_minor_objektif][]" id="gejala_minor_berfokus" onclick="checkthis('gejala_minor_berfokus')" value="Berfokus pada diri sendiri">
                                <span class="lbl"> Berfokus pada diri sendiri</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="ace" name="form_57[gejala_minor_objektif][]" id="gejala_minor_diaforesis" onclick="checkthis('gejala_minor_diaforesis')" value="Diaforesis">
                                <span class="lbl"> Diaforesis</span>
                            </label>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<br>
<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
    <thead>
        <tr style="background-color: #d3d3d3;">
            <th style="width: 5%; border: 1px solid black; padding: 5px;">NO.</th>
            <th style="width: 95%; border: 1px solid black; padding: 5px;">INTERVENSI KEPERAWATAN UTAMA</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                <b>Manajemen nyeri</b> <i>(Mengidentifikasi dan mengelola ensorik atau emosional yang terkait dengan kerusakan jaringan atau fungsional dengan onset mendadak atau lambat dan berintensitas ringan hingga berat dan konstan)</i> (1.08238)
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                <b>Tindakan
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;"><b>1</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <div style="margin-top: 5px;"><b>Observasi</b></div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][observasi][]" id="observasi_karakteristik" onclick="checkthis('observasi_karakteristik')" value="Identifikasi lokasi, karakteristik, durasi, frekuensi, kualitas, intensitas nyeri">
                        <span class="lbl"> Identifikasi lokasi, karakteristik, durasi, frekuensi, kualitas, intensitas nyeri</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][observasi][]" id="observasi_skala" onclick="checkthis('observasi_skala')" value="Identifikasi skala nyeri">
                        <span class="lbl"> Identifikasi skala nyeri</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][observasi][]" id="observasi_respon_non_verbal" onclick="checkthis('observasi_respon_non_verbal')" value="Identifikasi respon nyeri non verbal">
                        <span class="lbl"> Identifikasi respon nyeri non verbal</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][observasi][]" id="observasi_faktor" onclick="checkthis('observasi_faktor')" value="Identifikasi faktor yang memperberat dan memperingan nyeri">
                        <span class="lbl"> Identifikasi faktor yang memperberat dan memperingan nyeri</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][observasi][]" id="observasi_pengetahuan" onclick="checkthis('observasi_pengetahuan')" value="Identifikasi pengetahuan dan kenyakinan tentang nyeri">
                        <span class="lbl"> Identifikasi pengetahuan dan kenyakinan tentang nyeri</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][observasi][]" id="observasi_pengaruh_budaya" onclick="checkthis('observasi_pengaruh_budaya')" value="Identifikasi pengaruh budaya terhadap respon nyeri">
                        <span class="lbl"> Identifikasi pengaruh budaya terhadap respon nyeri</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][observasi][]" id="observasi_pengaruh_kualitas" onclick="checkthis('observasi_pengaruh_kualitas')" value="Identifikasi pengaruh nyeri pada kualitas hidup">
                        <span class="lbl"> Identifikasi pengaruh nyeri pada kualitas hidup</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][observasi][]" id="observasi_keberhasilan" onclick="checkthis('observasi_keberhasilan')" value="Monitor keberhasilan terapi komplementer yang sudah diberikan">
                        <span class="lbl"> Monitor keberhasilan terapi komplementer yang sudah diberikan</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][observasi][]" id="observasi_efek_samping" onclick="checkthis('observasi_efek_samping')" value="Monitor efek samping penggunaan analgetik">
                        <span class="lbl"> Monitor efek samping penggunaan analgetik</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                <input type="text" class="input_type" name="form_57[ket_tambahan_manajemen_nyeri_observasi]" id="ket_tambahan_manajemen_nyeri_observasi" onchange="fillthis('ket_tambahan_manajemen_nyeri_observasi')" style="width:100%;"> 
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;"><b>2</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Terapeutik</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][terapeutik][]" id="terapeutik_non_farmakologis" onclick="checkthis('terapeutik_non_farmakologis')" value="Berikan teknik non farmakologis untuk mengurangi rasa nyeri">
                        <span class="lbl"> Berikan teknik non farmakologis untuk mengurangi rasa nyeri (misal TENS, hipnosis, akupresure, terapi musik, biofeedback, terapi pijat, aroma terapi, teknik imajenasi terbimbing, kompres hangat/dingin, terapi bermain)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][terapeutik][]" id="terapeutik_kontrol_lingkungan" onclick="checkthis('terapeutik_kontrol_lingkungan')" value="Kontrol lingkungan yang memperberat rasa nyeri">
                        <span class="lbl"> Kontrol lingkungan yang memperberat rasa nyeri (misal suhu ruangan, pencahayaan, kebisingan)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][terapeutik][]" id="terapeutik_fasilitasi_istirahat" onclick="checkthis('terapeutik_fasilitasi_istirahat')" value="Fasilitasi istirahat dan tidur">
                        <span class="lbl"> Fasilitasi istirahat dan tidur</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][terapeutik][]" id="terapeutik_pertimbangkan_jenis" onclick="checkthis('terapeutik_pertimbangkan_jenis')" value="Pertimbangkan jenis dan sumber nyeri dalam pemilihan strategi meredakan nyeri.">
                        <span class="lbl"> Pertimbangkan jenis dan sumber nyeri dalam pemilihan strategi meredakan nyeri.</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                     <input type="text" class="input_type" name="form_57[ket_tambahan_manajemen_nyeri_terapeutik]" id="ket_tambahan_manajemen_nyeri_terapeutik" onchange="fillthis('ket_tambahan_manajemen_nyeri_terapeutik')" style="width:100%;"> 
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;"><b>3</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Edukasi</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][edukasi][]" id="edukasi_penyebab" onclick="checkthis('edukasi_penyebab')" value="Jelaskan penyebab, periode dan pemicu nyeri">
                        <span class="lbl"> Jelaskan penyebab, periode dan pemicu nyeri</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][edukasi][]" id="edukasi_strategi" onclick="checkthis('edukasi_strategi')" value="Jelaskan strategi meredakan nyeri">
                        <span class="lbl"> Jelaskan strategi meredakan nyeri</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][edukasi][]" id="edukasi_monitor" onclick="checkthis('edukasi_monitor')" value="Anjurkan memonitor nyeri secara mandiri">
                        <span class="lbl"> Anjurkan memonitor nyeri secara mandiri</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][edukasi][]" id="edukasi_analgetik" onclick="checkthis('edukasi_analgetik')" value="Anjurkan menggunkan analgetik secara tepat">
                        <span class="lbl"> Anjurkan menggunkan analgetik secara tepat</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][edukasi][]" id="edukasi_non_farmakologis" onclick="checkthis('edukasi_non_farmakologis')" value="Ajarkan teknik non farmakologis untuk mengurangi rasa nyeri">
                        <span class="lbl"> Ajarkan teknik non farmakologis untuk mengurangi rasa nyeri</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_57[ket_tambahan_manajemen_nyeri_edukasi]" id="ket_tambahan_manajemen_nyeri_edukasi" onchange="fillthis('ket_tambahan_manajemen_nyeri_edukasi')" style="width:100%;"> 
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;"><b>4</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Kolaborasi</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[manajemen_nyeri][kolaborasi][]" id="kolaborasi_analgetik" onclick="checkthis('kolaborasi_analgetik')" value="Kolaborasi pemberian analgetik">
                        <span class="lbl"> Kolaborasi pemberian analgetik</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_57[ket_tambahan_manajemen_nyeri_kolaborasi]" id="ket_tambahan_manajemen_nyeri_kolaborasi" onchange="fillthis('ket_tambahan_manajemen_nyeri_kolaborasi')" style="width:100%;">
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                <b>Pemberian analgesik</b> <i>(Menyiapkan dan memberikan agen farmakologis untuk mengurangi atau menghilangkan rasa sakit)</i> (1.084243)
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                <b>Tindakan
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;"><b>1</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <div style="margin-top: 5px;"><b>Observasi</b></div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[pemberian_analgesik][observasi][]" id="p_analgesik_observasi_karakteristik" onclick="checkthis('p_analgesik_observasi_karakteristik')" value="Identifikasi karakteristik nyeri (misal pencetus, pereda, kualitas lokasi, intensitas, frekuensi, durasi)">
                        <span class="lbl"> Identifikasi karakteristik nyeri (misal pencetus, pereda, kualitas lokasi, intensitas, frekuensi, durasi)</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[pemberian_analgesik][observasi][]" id="p_analgesik_observasi_alergi" onclick="checkthis('p_analgesik_observasi_alergi')" value="Identifikasi riwayat alergi obat">
                        <span class="lbl"> Identifikasi riwayat alergi obat</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[pemberian_analgesik][observasi][]" id="p_analgesik_observasi_kesesuaian" onclick="checkthis('p_analgesik_observasi_kesesuaian')" value="Identifikasi kesesuaian jenis analgesik (misal narkotika, non-narkotik atau NSAID) dengan tingkat keparahan nyeri">
                        <span class="lbl"> Identifikasi kesesuaian jenis analgesik (misal narkotika, non-narkotik atau NSAID) dengan tingkat keparahan nyeri</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[pemberian_analgesik][observasi][]" id="p_analgesik_observasi_tanda_vital" onclick="checkthis('p_analgesik_observasi_tanda_vital')" value="Monitor tanda-tanda vital sebelum dan sesudah pemberian analgesik">
                        <span class="lbl"> Monitor tanda-tanda vital sebelum dan sesudah pemberian analgesik</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[pemberian_analgesik][observasi][]" id="p_analgesik_observasi_efektivitas" onclick="checkthis('p_analgesik_observasi_efektivitas')" value="Monitor efektivitas analgesik">
                        <span class="lbl"> Monitor efektivitas analgesik</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_57[ket_tambahan_pemberian_analgesik_observasi]" id="ket_tambahan_pemberian_analgesik_observasi" onchange="fillthis('ket_tambahan_pemberian_analgesik_observasi')" style="width:100%;">
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;"><b>2</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Terapeutik</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[pemberian_analgesik][terapeutik][]" id="p_analgesik_terapeutik_diskusi" onclick="checkthis('p_analgesik_terapeutik_diskusi')" value="Diskusikan jenis analgesik yang disukai untuk mencapai analgesia optimal, jika perlu">
                        <span class="lbl"> Diskusikan jenis analgesik yang disukai untuk mencapai analgesia optimal, jika perlu</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[pemberian_analgesik][terapeutik][]" id="p_analgesik_terapeutik_infus" onclick="checkthis('p_analgesik_terapeutik_infus')" value="Pertimbangkan penggunaan infus kontinu atau bolus opioid untuk mempertahankan kadar dalam serum">
                        <span class="lbl"> Pertimbangkan penggunaan infus kontinu atau bolus opioid untuk mempertahankan kadar dalam serum</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[pemberian_analgesik][terapeutik][]" id="p_analgesik_terapeutik_target" onclick="checkthis('p_analgesik_terapeutik_target')" value="Tetapkan target efektivitas analgesik untuk mengoptimalkan respon pasien">
                        <span class="lbl"> Tetapkan target efektivitas analgesik untuk mengoptimalkan respon pasien</span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[pemberian_analgesik][terapeutik][]" id="p_analgesik_terapeutik_dokumentasi" onclick="checkthis('p_analgesik_terapeutik_dokumentasi')" value="Dokumentasikan respon terhadap efek analgesik dan efek yang tidak diinginkan">
                        <span class="lbl"> Dokumentasikan respon terhadap efek analgesik dan efek yang tidak diinginkan</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_57[ket_tambahan_pemberian_analgesik_terapeutik]" id="ket_tambahan_pemberian_analgesik_terapeutik" onchange="fillthis('ket_tambahan_pemberian_analgesik_terapeutik')" style="width:100%;">
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;"><b>3</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Edukasi</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[pemberian_analgesik][edukasi][]" id="p_analgesik_edukasi_efek_terapi" onclick="checkthis('p_analgesik_edukasi_efek_terapi')" value="Jelaskan efek terapi dan efek samping obat">
                        <span class="lbl"> Jelaskan efek terapi dan efek samping obat</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_57[ket_tambahan_pemberian_analgesik_edukasi]" id="ket_tambahan_pemberian_analgesik_edukasi" onchange="fillthis('ket_tambahan_pemberian_analgesik_edukasi')" style="width:100%;">
                </div>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;"><b>4</b></td>
            <td style="border: 1px solid black; padding: 5px; vertical-align: top;">
                <b>Kolaborasi</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_57[pemberian_analgesik][kolaborasi][]" id="p_analgesik_kolaborasi_dosis" onclick="checkthis('p_analgesik_kolaborasi_dosis')" value="Kolaborasi pemberian dosis dan jenis analgesik sesuai indikasi">
                        <span class="lbl"> Kolaborasi pemberian dosis dan jenis analgesik sesuai indikasi</span>
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <input type="text" class="input_type" name="form_57[ket_tambahan_pemberian_analgesik_kolaborasi]" id="ket_tambahan_pemberian_analgesik_kolaborasi" onchange="fillthis('ket_tambahan_pemberian_analgesik_kolaborasi')" style="width:100%;">
                </div>
            </td>
        </tr>
    </tbody>
</table>

<br>
<table class="table" style="width: 100%;">
  <tbody>
    <tr>
      <!-- Kolom Perawat -->
      <td style="width: 34%; text-align: center;">
        Nama/Paraf
        <br><br>
        <span class="ttd-btn" data-role="perawat" id="ttd_perawat" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_perawat" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" name="form_57[ttd_paraf_perawat]" id="ttd_paraf_perawat" placeholder="Paraf / Nama jelas" style="width: 150px;">
        <br>
        (Tanda Tangan dan Nama Jelas)
      </td>
    </tr>
  </tbody>
</table>

</div>

<!-- <?php //echo $footer; ?> -->

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