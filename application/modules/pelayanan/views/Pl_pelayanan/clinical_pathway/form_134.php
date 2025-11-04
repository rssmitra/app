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
      var hiddenInputName = 'form_134[ttd_' + role + ']';
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

<p class="title" style="font-size: 16px; text-align: center">
    <b>FORMULIR EDUKASI PASIEN DAN KELUARGA</b>
</p>

<p>
    Beri ceklis	(&#x2714;) untuk pengisian formulir dibawah ini
</p>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<span class="title_data"><b>A. ASSESMENT KEBUTUHAN EDUKASI </b></span><br>
<br>
<table border="0" width="100%">

    <tr valign="top">
        <td width="20%">Pendidikan</td>
        <td width="20%">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[p_sd]" id="p_sd" onclick="checkthis('p_sd')" >
                <span class="lbl"> SD</span>
            </label>
        </td>
        <td width="20%">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[p_smp]" id="p_smp" onclick="checkthis('p_smp')"  >
                <span class="lbl"> SMP </span>
            </label>
        </td>
        <td width="20%">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[p_s1]" id="p_s1" onclick="checkthis('p_s1')"  >
                <span class="lbl"> S1 </span>
            </label>
        </td>
        <td width="20%">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[p_dll]" id="p_dll" onclick="checkthis('p_dll')" >
                <span class="lbl"> DLL </span>
            </label>
        </td>
    <tr valign="top">
        <td>Tinggal Bersama</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[tb_anak]" id="tb_anak" onclick="checkthis('tb_anak')">
                <span class="lbl"> Anak </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[tb_ot]" id="tb_ot" onclick="checkthis('tb_ot')">
                <span class="lbl"> Orang Tua </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[tb_s]" id="tb_s" onclick="checkthis('tb_s')">
                <span class="lbl"> Sendiri </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[tb_si]" id="tb_si" onclick="checkthis('tb_si')">
                <span class="lbl"> Suami/Istri </span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>Hambatan Edukasi</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[he_ada]" id="he_ada" onclick="checkthis('he_ada')">
                <span class="lbl"> Ada </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[he_Tidak1]" id="he_Tidak1" onclick="checkthis('he_Tidak1')">
                <span class="lbl"> Tidak </span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[he_gp]" id="he_gp" onclick="checkthis('he_gp')">
                <span class="lbl"> Gangguan Pendengaran </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[he_ge]" id="he_ge" onclick="checkthis('he_ge')">
                <span class="lbl"> Gangguan Emosi </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[he_gp2]" id="he_gp2" onclick="checkthis('he_gp2')">
                <span class="lbl"> Gangguan Penglihatan </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[he_gb]" id="he_gb" onclick="checkthis('he_gb')">
                <span class="lbl"> Gangguan Bicara </span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[he_mkb]" id="he_mkb" onclick="checkthis('he_mkb')">
                <span class="lbl"> Motivasi Kurang/Buruk </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[he_mh]" id="he_mh" onclick="checkthis('he_mh')">
                <span class="lbl"> Memori Hilang </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[he_fl]" id="he_fl" onclick="checkthis('he_fl')">
                <span class="lbl"> Fisik Lemah </span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>Merokok</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[m_pasif]" id="m_pasif" onclick="checkthis('m_pasif')">
                <span class="lbl"> Pasif </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[m_aktif]" id="m_aktif" onclick="checkthis('m_aktif')">
                <span class="lbl"> aktif </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[m_tidak2]" id="m_tidak2" onclick="checkthis('m_tidak2')">
                <span class="lbl"> tidak </span>
            </label>
        </td>
        <td colspan="1"></td>
    </tr>
    <tr valign="top">
        <td>Minum Alkohol</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ma_ya]" id="ma_ya" onclick="checkthis('ma_ya')">
                <span class="lbl"> Ya</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ma_tidak3]" id="ma_tidak3" onclick="checkthis('ma_tidak3')">
                <span class="lbl"> Tidak</span>
            </label>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr valign="top">
        <td>Edukasi Diberikan Kepada</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[edk_pasien]" id="edk_pasien" onclick="checkthis('edk_pasien')">
                <span class="lbl"> Pasien </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[edk_ot] ab" id="edk_ot ab" onclick="checkthis('edk_ot')">
                <span class="lbl"> Orang Tua (Ayah/Ibu) </span>
            </label>
        </td>
        <td colspan="2">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[edk_k]" id="edk_k" onclick="checkthis('edk_k')">
                <span class="lbl"> Keluarga (Suami/Istri/Kakak/Adik) </span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>Kemampuan Bahasa</td>
        <td>
            <label>
                <input type="checkbox"  class="ace" name="form_134[kb_ind]" id="kb_ind" onclick="checkthis('kb_ind')" value="1">
                <span class="lbl"> Indonesia </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox"  class="ace" name="form_134[kb_daerah]" id="kb_daerah" onclick="checkthis('kb_daerah')" value="1">
                <span class="lbl"> Daerah <input type="text" placeholder="Masukan bahasa daerah" name="form_134[masukan_daerah]" id="masukan_daerah" onchange="fillthis('masukan_daerah')" class="input_type" value=""></span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox"  class="ace" name="form_134[kb_asing]" id="kb_asing" onclick="checkthis('kb_asing')" value="1">
                <span class="lbl"> Asing <input type="text" placeholder="Masukan bahasa asing" name="form_134[masukan_bahasa]" id="masukan_bahasa" onchange="fillthis('masukan_bahasa')" class="input_type" value=""></span>
            </label>
        </td>
        <td colspan="1"></td>
    </tr>
    <tr valign="top">
        <td>Perlu Penterjemah</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[pp_ya1]" id="pp_ya1" onclick="checkthis('pp_ya1')">
                <span class="lbl"> Ya</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[pp_tidak4]" id="pp_tidak4" onclick="checkthis('pp_tidak4')">
                <span class="lbl"> Tidak</span>
            </label>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr valign="top">
        <td>Baca & Tulis</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[bt_bisa]" id="bt_bisa" onclick="checkthis('bt_bisa')">
                <span class="lbl"> Bisa</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[bt_tidak5]" id="bt_tidak5" onclick="checkthis('bt_tidak5')">
                <span class="lbl"> Tidak</span>
            </label>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr valign="top">
        <td>Kepercayaan Lainnya</td>
        <td colspan="2">
            <label>
                <input type="checkbox"  class="ace" name="form_134[kl_ada1]" id="kl_ada1" onclick="checkthis('kl_ada1')" value="1">
                <span class="lbl"> Ada <input type="text" placeholder="masukkan jawaban anda" name="form_134[masukan_kepercayaan]" id="masukan_kepercayaan" onchange="fillthis('masukan_kepercayaan')" class="input_type"
                value=""></span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[kl_ta]" id="kl_ta" onclick="checkthis('kl_ta')">
                <span class="lbl"> Tidak Ada</span>
            </label>
        </td>
    </tr>
    
    <tr valign="top">
        <td>Kesediaan Menerima Edukasi</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[kme_ya2]" id="kme_ya2" onclick="checkthis('kme_ya2')">
                <span class="lbl"> Ya</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[kme_tidak6]" id="kme_tidak6" onclick="checkthis('kme_tidak6')">
                <span class="lbl"> Tidak </span>
            </label>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr valign="top">
        <td>Cara Edukasi</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ce_lisan]" id="ce_lisan" onclick="checkthis('ce_lisan')">
                <span class="lbl"> Lisan</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ce_tulisan]" id="ce_tulisan" onclick="checkthis('ce_tulisan')">
                <span class="lbl"> Tulisan</span>
            </label>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr valign="top">
        <td>Kebutuhan Edukasi</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ke_hubppp]" id="ke_hubppp" onclick="checkthis('ke_hubppp')">
                <span class="lbl"> Hak Untuk Berpastisipasi Pada Proses Pela2nan </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ke_ppp]" id="ke_ppp" onclick="checkthis('ke_ppp')">
                <span class="lbl"> Prosedur Pemeriksaan Penunjang </span>
            </label>
        </td>
        <td colspan="2">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ke_kkdpp]" id="ke_kkdpp" onclick="checkthis('ke_kkdpp')">
                <span class="lbl"> Kondisi Kesehatan, Diagnosa Pasti dan penatalaksanaannya </span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ke_ppic]" id="ke_ppic" onclick="checkthis('ke_ppic')">
                <span class="lbl"> Proses Pemberian Informed Consent </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ke_dn]" id="ke_dn" onclick="checkthis('ke_dn')">
                <span class="lbl"> Diet dan Nutrisi </span>
            </label>
        </td>
        <td colspan="2">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ke_posea]" id="ke_posea" onclick="checkthis('ke_posea')">
                <span class="lbl"> Pengguna Obat Secara Efektif dan Aman, Efek Samping Serta Interaksi</span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ke_pamya]" id="ke_pamya" onclick="checkthis('ke_pamya')">
                <span class="lbl"> Pengunaan Alat Medis Yang Aman</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ke_mn]" id="ke_mn" onclick="checkthis('ke_mn')">
                <span class="lbl"> Manajemen Nyeri</span>
            </label>
        </td>
        <td colspan="2">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ke_tr]" id="ke_tr" onclick="checkthis('ke_tr')">
                <span class="lbl"> Teknik Rehabilitasi</span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ke_ctyb]" id="ke_ctyb" onclick="checkthis('ke_ctyb')">
                <span class="lbl"> Cuci Tangan Yang Benar</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ke_bm]" id="ke_bm" onclick="checkthis('ke_bm')">
                <span class="lbl">Bahaya Merokok</span>
            </label>
        </td>
        <td colspan="2">
            <label>
                <input type="checkbox"  class="ace" name="form_134[ke_ll]" id="ke_ll" onclick="checkthis('ke_ll')" value="1">
                <span class="lbl"> Lain-lain <input type="text" placeholder="masukkan jawaban anda" name="form_134[masukan_lain]" id="masukan_lain" onchange="fillthis('masukan_lain')" class="input_type" value=""></span>
            </label>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td colspan="3">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_134[ke_re]" id="ke_re" onclick="checkthis('ke_re')">
                <span class="lbl">Rujukan Edukasi</span>
            </label>
        </td>
    </tr>
</table>


<!-- ==================== BAGIAN B. EDUKASI ==================== -->
<span class="title_data"><b>B. EDUKASI</b></span>
<br><br>

<table border="1" width="100%" style="border-collapse: collapse; font-size:13px; text-align:center;">
    <thead style="background-color:#e8e8e8; font-weight:bold;">
        <tr>
            <th style="width:5%;text-align:center;">No</th>style="
            <th style="width:8%;text-align:center;">Tgl</th>
            <th style="width:10%;text-align:center;">Jam Mulai Edukasi</th>
            <th style="width:10%;text-align:center;">Jam Selesai Edukasi</th>
            <th style="width:8%;text-align:center;">Lama Edukasi (menit)</th>
            <th style="width:15%;text-align:center;">Materi Edukasi Berdasarkan Kebutuhan</th>
            <th style="width:10%;text-align:center;">Metode Edukasi</th>
            <th style="width:10%;text-align:center;">Alat Bantu Edukasi</th>
            <th style="width:15%;text-align:center;">Hasil Verifikasi</th>
            <th style="width:10%;text-align:center;">Tgl Re-edukasi / Re-demonstrasi</th>
            <th style="width:10%;text-align:center;">TTD Pemberi Edukasi</th>
            <th style="width:10%;text-align:center;">TTD Pasien / Keluarga</th>
        </tr>
    </thead>
    <tbody>
        <!-- Ulangi 10 baris -->
        <?php for($i=1; $i<=10; $i++): ?>
        <tr valign="top">
            <td style="padding:5px;"><?php echo $i; ?></td>

            <!-- Tanggal -->
            <td style="padding:5px;">
                <div contenteditable="true"
                    class="input_type"
                    name="form_134[tgl_<?php echo $i; ?>]"
                    id="tgl_<?php echo $i; ?>"
                    onchange="fillthis('tgl_<?php echo $i; ?>')"
                    style="width:100%; min-height:60px; overflow:visible; border:1px solid #ccc;">
                    <?php echo isset($value_form['tgl_'.$i]) ? nl2br($value_form['tgl_'.$i]) : '' ?>
                </div>
            </td>

            <!-- Jam Mulai -->
            <td style="padding:5px;">
                <div contenteditable="true"
                    class="input_type"
                    name="form_134[jam_mulai_<?php echo $i; ?>]"
                    id="jam_mulai_<?php echo $i; ?>"
                    onchange="fillthis('jam_mulai_<?php echo $i; ?>')"
                    style="width:100%; min-height:60px; overflow:visible; border:1px solid #ccc;">
                    <?php echo isset($value_form['jam_mulai_'.$i]) ? nl2br($value_form['jam_mulai_'.$i]) : '' ?>
                </div>
            </td>

            <!-- Jam Selesai -->
            <td style="padding:5px;">
                <div contenteditable="true"
                    class="input_type"
                    name="form_134[jam_selesai_<?php echo $i; ?>]"
                    id="jam_selesai_<?php echo $i; ?>"
                    onchange="fillthis('jam_selesai_<?php echo $i; ?>')"
                    style="width:100%; min-height:60px; overflow:visible; border:1px solid #ccc;">
                    <?php echo isset($value_form['jam_selesai_'.$i]) ? nl2br($value_form['jam_selesai_'.$i]) : '' ?>
                </div>
            </td>

            <!-- Lama Edukasi -->
            <td style="padding:5px;">
                <div contenteditable="true"
                    class="input_type"
                    name="form_134[lama_<?php echo $i; ?>]"
                    id="lama_<?php echo $i; ?>"
                    onchange="fillthis('lama_<?php echo $i; ?>')"
                    style="width:100%; min-height:60px; overflow:visible; border:1px solid #ccc;">
                    <?php echo isset($value_form['lama_'.$i]) ? nl2br($value_form['lama_'.$i]) : '' ?>
                </div>
            </td>

            <!-- Materi Edukasi -->
            <td style="padding:5px;">
                <div contenteditable="true"
                    class="input_type"
                    name="form_134[materi_<?php echo $i; ?>]"
                    id="materi_<?php echo $i; ?>"
                    onchange="fillthis('materi_<?php echo $i; ?>')"
                    style="width:100%; min-height:60px; overflow:visible; border:1px solid #ccc;">
                    <?php echo isset($value_form['materi_'.$i]) ? nl2br($value_form['materi_'.$i]) : '' ?>
                </div>
            </td>

            <!-- Metode Edukasi -->
            <td style="padding:5px;">
                <div contenteditable="true"
                    class="input_type"
                    name="form_134[metode_<?php echo $i; ?>]"
                    id="metode_<?php echo $i; ?>"
                    onchange="fillthis('metode_<?php echo $i; ?>')"
                    style="width:100%; min-height:60px; overflow:visible; border:1px solid #ccc;">
                    <?php echo isset($value_form['metode_'.$i]) ? nl2br($value_form['metode_'.$i]) : '' ?>
                </div>
            </td>

            <!-- Alat Bantu Edukasi -->
            <td style="padding:5px;">
                <div contenteditable="true"
                    class="input_type"
                    name="form_134[alat_<?php echo $i; ?>]"
                    id="alat_<?php echo $i; ?>"
                    onchange="fillthis('alat_<?php echo $i; ?>')"
                    style="width:100%; min-height:60px; overflow:visible; border:1px solid #ccc;">
                    <?php echo isset($value_form['alat_'.$i]) ? nl2br($value_form['alat_'.$i]) : '' ?>
                </div>
            </td>

            <!-- Hasil Verifikasi (Checkbox) -->
            <td style="padding:5px; text-align:left;">
                <label><input type="checkbox" class="ace" value="1"
                        name="form_134[ver_mengerti_<?php echo $i; ?>]"
                        id="ver_mengerti_<?php echo $i; ?>"
                        onclick="checkthis('ver_mengerti_<?php echo $i; ?>')"> <span class="lbl">Sudah Mengerti</span></label><br>
                <label><input type="checkbox" class="ace" value="1"
                        name="form_134[ver_reedukasi_<?php echo $i; ?>]"
                        id="ver_reedukasi_<?php echo $i; ?>"
                        onclick="checkthis('ver_reedukasi_<?php echo $i; ?>')"> <span class="lbl">Re-edukasi</span></label><br>
                <label><input type="checkbox" class="ace" value="1"
                        name="form_134[ver_redemo_<?php echo $i; ?>]"
                        id="ver_redemo_<?php echo $i; ?>"
                        onclick="checkthis('ver_redemo_<?php echo $i; ?>')"> <span class="lbl">Re-demonstrasi</span></label>
            </td>

            <!-- Tgl Re-edukasi -->
            <td style="padding:5px;">
                <div contenteditable="true"
                    class="input_type"
                    name="form_134[tgl_re_<?php echo $i; ?>]"
                    id="tgl_re_<?php echo $i; ?>"
                    onchange="fillthis('tgl_re_<?php echo $i; ?>')"
                    style="width:100%; min-height:60px; overflow:visible; border:1px solid #ccc;">
                    <?php echo isset($value_form['tgl_re_'.$i]) ? nl2br($value_form['tgl_re_'.$i]) : '' ?>
                </div>
            </td>

            <!-- Kolom TTD Pemberi -->
            <td style="text-align:center;">
                <span class="ttd-btn" data-role="pemberi_<?php echo $i; ?>" id="ttd_pemberi_btn_<?php echo $i; ?>" style="cursor:pointer;">
                    <i class="fa fa-pencil blue"></i>
                </span>
                <br>
                <img id="img_ttd_pemberi_<?php echo $i; ?>" src="<?php echo isset($value_form['img_ttd_pemberi_'.$i]) ? $value_form['img_ttd_pemberi_'.$i] : ''; ?>" 
                     style="display:<?php echo isset($value_form['img_ttd_pemberi_'.$i]) ? 'block' : 'none'; ?>; max-width:150px; max-height:40px; margin-top:5px;">
                <input type="hidden" name="form_134[img_ttd_pemberi_<?php echo $i; ?>]" id="input_ttd_pemberi_<?php echo $i; ?>">
                <br>
                <input type="text" class="input_type" name="form_134[nama_pemberi_<?php echo $i; ?>]" id="nama_pemberi_<?php echo $i; ?>" placeholder="Nama" style="width:90%; text-align:center;">
            </td>

            <!-- Kolom TTD Pasien -->
            <td style="text-align:center;">
                <span class="ttd-btn" data-role="pasien_<?php echo $i; ?>" id="ttd_pasien_btn_<?php echo $i; ?>" style="cursor:pointer;">
                    <i class="fa fa-pencil blue"></i>
                </span>
                <br>
                <img id="img_ttd_pasien_<?php echo $i; ?>" src="<?php echo isset($value_form['img_ttd_pasien_'.$i]) ? $value_form['img_ttd_pasien_'.$i] : ''; ?>" 
                     style="display:<?php echo isset($value_form['img_ttd_pasien_'.$i]) ? 'block' : 'none'; ?>; max-width:150px; max-height:40px; margin-top:5px;">
                <input type="hidden" name="form_134[img_ttd_pasien_<?php echo $i; ?>]" id="input_ttd_pasien_<?php echo $i; ?>">
                <br>
                <input type="text" class="input_type" name="form_134[nama_pasien_<?php echo $i; ?>]" id="nama_pasien_<?php echo $i; ?>" placeholder="Nama" style="width:90%; text-align:center;">
            </td>
        </tr>
        <?php endfor; ?>
    </tbody>
</table>



<hr>
<?php echo $footer; ?>

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