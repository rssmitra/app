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
      var hiddenInputName = 'form_147[ttd_' + role + ']';
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
<!-- <p>edited by amelia yahya 25 november 2025</p> -->
<div style="text-align: center; font-size: 18px;"><b>LAPORAN UJI JANTUNG DENGAN TREADMILL</b></div>
<br>

<table width="100%" style="border-collapse:collapse; font-size:14px;">
    <tr>
        <td style="width:20%; padding:5px;">Tanggal</td>
        <td style="padding:5px;">
            <input type="text" class="input_type" style="width: 50%;" 
            name="form_147[tanggal]" id="tanggal" 
            onchange="fillthis('tanggal')">
        </td>
    </tr>

    <tr>
    <td style="padding:5px;">Nama</td>
    <td style="padding:5px;">
      <input type="text" class="input_type" style="width: 100%;" name="form_146[nama_pasien]" id="nama_pasien" onchange="fillthis('nama_pasien')" 
      value="<?php $nama_pasien = isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''; echo isset($value_form['nama_pasien'])?$value_form['nama_pasien']:$nama_pasien?>">
    </td>
    </tr>

    <tr>
    <td style="padding:5px;">Jenis Kelamin</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_146[jk_l]" id="jk_l" onclick="checkthis('jk_l')" 
        <?php $jk = isset($data_pasien->jen_kelamin)?$data_pasien->jen_kelamin:''; echo ($jk=='L')?'checked':'';?>> 
        <span class="lbl"> Laki-laki</span>
      </label>
      &nbsp;&nbsp;
      <label>
        <input type="checkbox" class="ace" name="form_146[jk_p]" id="jk_p" onclick="checkthis('jk_p')" 
        <?php echo ($jk=='P')?'checked':'';?>> 
        <span class="lbl"> Perempuan</span>
      </label>
    </td>
    </tr>

   <tr>
   <td style="padding:5px;">Tempat / Tanggal Lahir</td>
   <td>
    <input 
        type="text" 
        class="input_type" 
        style="width: 120px !important;" 
        name="form_146[tempat_lahir]" 
        id="tempat_lahir"
        value="<?php 
          echo isset($value_form['tempat_lahir']) 
            ? $value_form['tempat_lahir'] 
            : (isset($data_pasien->tempat_lahir) ? $data_pasien->tempat_lahir : '');
        ?>"
      >
      , 
      <input 
        type="text" 
        class="input_type date-picker" 
        data-date-format="yyyy-mm-dd"
        style="width: 120px !important;" 
        name="form_146[tanggal_lahir]" 
        id="tanggal_lahir"
        value="<?php 
          $tgl_lhr = isset($data_pasien->tgl_lhr) ? $data_pasien->tgl_lhr : ''; 
          if (!empty($tgl_lhr)) {
            $tgl_lhr = date('Y-m-d', strtotime($tgl_lhr));
          }
          echo isset($value_form['tanggal_lahir']) ? $value_form['tanggal_lahir'] : $tgl_lhr; 
        ?>"
      >
  </td>
  </tr>

    <tr>
   <td style="padding:5px;">Alamat</td>
    <td colspan="5" style="padding:5px;">
      <input type="text" class="input_type" style="width: 100%;" name="form_146[alamat]" id="alamat" onchange="fillthis('alamat')" 
      value="<?php $almt = isset($data_pasien->almt_ttp_pasien)?$data_pasien->almt_ttp_pasien:''; echo isset($value_form['alamat'])?$value_form['alamat']:$almt?>">
    </td>
    </td>
  </tr>

    <tr>
        <td style="padding:5px;">TB / BB</td>
        <td style="padding:5px;">
            <input type="text" class="input_type" style="width:70px" 
            name="form_147[tb]" id="tb" onchange="fillthis('tb')"> Cm,
            <input type="text" class="input_type" style="width:70px" 
            name="form_147[bb]" id="bb" onchange="fillthis('bb')"> Kg  
            &nbsp; Dikirim oleh :
            <input type="text" class="input_type" style="width:200px" 
            name="form_147[dikirim_oleh]" id="dikirim_oleh" 
            onchange="fillthis('dikirim_oleh')">
        </td>
    </tr>

    <tr>
        <td style="padding:5px;">Indikasi</td>
        <td style="padding:5px;">
            <input type="text" class="input_type" style="width: 90%;" 
            name="form_147[indikasi]" id="indikasi" 
            onchange="fillthis('indikasi')">
        </td>
    </tr>

    <tr>
        <td style="padding:5px;">ECG Istirahat</td>
        <td style="padding:5px;">
            <input type="text" class="input_type" style="width: 90%;" 
            name="form_147[ecg_istirahat]" id="ecg_istirahat" 
            onchange="fillthis('ecg_istirahat')">
        </td>
    </tr>

    <tr>
        <td style="padding:5px;">Obat</td>
        <td style="padding:5px;">
            <input type="text" class="input_type" style="width: 90%;" 
            name="form_147[obat]" id="obat" 
            onchange="fillthis('obat')">
        </td>
    </tr>

    <tr>
        <td style="padding:5px;">Protokol</td>
        <td style="padding:5px;">
            <input type="text" class="input_type" style="width: 90%;" 
            name="form_147[protokol]" id="protokol" 
            onchange="fillthis('protokol')">
        </td>
    </tr>

    <tr>
        <td style="padding:5px;">T.H.R</td>
        <td style="padding:5px;">
            <input type="text" class="input_type" style="width: 90%;" 
            name="form_147[thr]" id="thr" 
            onchange="fillthis('thr')">
        </td>
    </tr>
</table>

<br>

<!-- ================================================== -->
<div style="font-size:14px;">

<b>Treadmill Exercise Test :</b><br><br>

<table width="100%" style="border-collapse:collapse; font-size:15px;">
  <tr>
    <td style="border:none; width:10%;">Protocol :</td>
    <td style="border:none; ">
      
      <label>
        <input type="checkbox" class="ace" 
               name="form_147[protocol_bruce]" 
               id="protocol_bruce" 
               onclick="checkthis('protocol_bruce')">
        <span class="lbl">BRUCE</span>
      </label>

      &nbsp;&nbsp;

      <label>
        <input type="checkbox" class="ace" 
               name="form_147[protocol_mod_bruce]" 
               id="protocol_mod_bruce" 
               onclick="checkthis('protocol_mod_bruce')">
        <span class="lbl">Modified Bruce</span>
      </label>

      &nbsp;&nbsp;

      <label>
        <input type="checkbox" class="ace" 
               name="form_147[protocol_ergocycle]" 
               id="protocol_ergocycle" 
               onclick="checkthis('protocol_ergocycle')">
        <span class="lbl">Ergocycle</span>
      </label>

    </td>
  </tr>
</table>

Lama test 
<input type="text" class="input_type" style="width:60px" name="form_147[lama_test]" id="lama_test" onchange="fillthis('lama_test')">
 minutes, dihentikan karena 
<input type="text" class="input_type" style="width:300px" name="form_147[alasan_stop]" id="alasan_stop" onchange="fillthis('alasan_stop')">
<br><br>

Maximal heart rate yang dicapai 
<input type="text" class="input_type" style="width:80px" name="form_147[max_hr]" id="max_hr" onchange="fillthis('max_hr')"> 
 (target denyut jantung 
<input type="text" class="input_type" style="width:60px" name="form_147[target_hr]" id="target_hr" onchange="fillthis('target_hr')"> 
 x/menit = 
<input type="text" class="input_type" style="width:50px" name="form_147[persen_max]" id="persen_max" onchange="fillthis('persen_max')"> 
 % maximal HR). 
<br><br>

<table width="100%" style="border-collapse:collapse; font-size:15px;">
  <tr>
    <td style="border:none; width:25%;">Perubahan segment ST</td>
    <td>:
      
      <label>
        <input type="checkbox" class="ace" 
               name="form_147[st_depresi]" 
               id="st_depresi" 
               onclick="checkthis('st_depresi')">
        <span class="lbl">Depresi</span>
      </label>

      &nbsp;&nbsp;

      <label>
        <input type="checkbox" class="ace" 
               name="form_147[st_elevasi]" 
               id="st_elevasi" 
               onclick="checkthis('st_elevasi')">
        <span class="lbl">Elevasi</span>
      </label>

    </td>
  </tr>
</table>

<table width="100%" style="border-collapse:collapse; font-size:15px;">
  <tr>
    <td style="width:25%; vertical-align: top;">Selama Exercise</td>
    <td>
      : <input type="text" class="input_type" style="width:80px" 
             name="form_147[st_exercise]" id="st_exercise" onchange="fillthis('st_exercise')"> 
      mm pada leads 
      : <input type="text" class="input_type" style="width:80px" 
             name="form_147[st_exercise_lead]" id="st_exercise_lead" onchange="fillthis('st_exercise_lead')"> ,
      pada stage 
      : <input type="text" class="input_type" style="width:60px" 
             name="form_147[stage_exercise]" id="stage_exercise" onchange="fillthis('stage_exercise')">
    </td>
  </tr>

  <tr>
    <td style="vertical-align: top;">Post Exercise</td>
    <td>
      : <input type="text" class="input_type" style="width:80px" 
             name="form_147[st_post]" id="st_post" onchange="fillthis('st_post')"> 
      mm pada leads 
      : <input type="text" class="input_type" style="width:80px" 
             name="form_147[st_post_lead]" id="st_post_lead" onchange="fillthis('st_post_lead')"> ,
      minutes pada saat istirahat 
      : <input type="text" class="input_type" style="width:60px" 
             name="form_147[post_rest_min]" id="post_rest_min" onchange="fillthis('post_rest_min')">
    </td>
  </tr>

  <tr>
    <td style="vertical-align: top;">Aritmia</td>
    <td>
      : <input type="text" class="input_type" style="width:300px" 
             name="form_147[aritmia]" id="aritmia" onchange="fillthis('aritmia')">
    </td>
  </tr>

  <tr>
    <td style="vertical-align: top;">Respon Tekanan Darah</td>
    <td>
      : <input type="text" class="input_type" style="width:300px" 
             name="form_147[respon_td]" id="respon_td" onchange="fillthis('respon_td')">
    </td>
  </tr>

  <tr>
    <td style="vertical-align: top;">Keluhan Angina</td>
    <td>
      : <input type="text" class="input_type" style="width:300px" 
             name="form_147[keluhan_angina]" id="keluhan_angina" onchange="fillthis('keluhan_angina')">
    </td>
  </tr>
</table>

<br><br>
<b>Kesimpulan :</b>


<table width="100%" style="border-collapse:collapse; font-size:15px;">

  <!-- Heart Rate -->
  <tr>
    <td style="border:none; padding:5px; width:30%;">Heart rate yang dicapai</td>
    <td style="border:none; padding:5px;">:
      <input type="text" class="input_type" 
             name="form_147[hr_dicapai]" 
             id="hr_dicapai" 
             style="width:80px;" 
             onchange="fillthis('hr_dicapai')"> % max HR
    </td>
  </tr>

  <!-- Iskemia Miokard -->
  <tr>
    <td style="border:none; padding:5px;">Tanda-tanda Iskemia miokard</td>
    <td style="border:none; padding:5px;">:

      <label>
        <input type="checkbox" class="ace" 
               name="form_147[iskemia_ada]" 
               id="iskemia_ada" 
               onclick="checkthis('iskemia_ada')">
        <span class="lbl">Terdapat</span>
      </label>

      &nbsp;&nbsp;

      <label>
        <input type="checkbox" class="ace" 
               name="form_147[iskemia_tidak]" 
               id="iskemia_tidak" 
               onclick="checkthis('iskemia_tidak')">
        <span class="lbl">Tidak terdapat</span>
      </label>

    </td>
  </tr>

  <!-- Tingkat Kesegaran Jasmani -->
  <tr>
    <td style="border:none; padding:5px;">Tingkat kesegaran jasmani</td>
    <td style="border:none; padding:5px;">:

      <label><input type="checkbox" class="ace" name="form_147[kesegaran_baik_sekali]" id="kesegaran_baik_sekali" onclick="checkthis('kesegaran_baik_sekali')"><span class="lbl">Baik Sekali</span></label>
      <label><input type="checkbox" class="ace" name="form_147[kesegaran_baik]" id="kesegaran_baik" onclick="checkthis('kesegaran_baik')"><span class="lbl">Baik</span></label>
      <label><input type="checkbox" class="ace" name="form_147[kesegaran_rata]" id="kesegaran_rata" onclick="checkthis('kesegaran_rata')"><span class="lbl">Rata-rata</span></label>
      <label><input type="checkbox" class="ace" name="form_147[kesegaran_kurang]" id="kesegaran_kurang" onclick="checkthis('kesegaran_kurang')"><span class="lbl">Kurang</span></label>
      <label><input type="checkbox" class="ace" name="form_147[kesegaran_rendah]" id="kesegaran_rendah" onclick="checkthis('kesegaran_rendah')"><span class="lbl">Rendah</span></label>

    </td>
  </tr>

  <!-- Tingkat Fungsional -->
  <tr>
    <td style="border:none; padding:5px;">Tingkat fungsional</td>
    <td style="border:none; padding:5px;">:

      <label><input type="checkbox" class="ace" name="form_147[fungsi_I]" id="fungsi_I" onclick="checkthis('fungsi_I')"><span class="lbl">I</span></label>
      <label><input type="checkbox" class="ace" name="form_147[fungsi_I_II]" id="fungsi_I_II" onclick="checkthis('fungsi_I_II')"><span class="lbl">I - II</span></label>
      <label><input type="checkbox" class="ace" name="form_147[fungsi_II]" id="fungsi_II" onclick="checkthis('fungsi_II')"><span class="lbl">II</span></label>
      <label><input type="checkbox" class="ace" name="form_147[fungsi_II_III]" id="fungsi_II_III" onclick="checkthis('fungsi_II_III')"><span class="lbl">II - III</span></label>
      <label><input type="checkbox" class="ace" name="form_147[fungsi_III]" id="fungsi_III" onclick="checkthis('fungsi_III')"><span class="lbl">III</span></label>
      <label><input type="checkbox" class="ace" name="form_147[fungsi_III_IV]" id="fungsi_III_IV" onclick="checkthis('fungsi_III_IV')"><span class="lbl">III - IV</span></label>

    </td>
  </tr>

  <!-- Kapasitas Aerobik -->
  <tr>
    <td style="border:none; padding:5px;">Kapasitas aerobic</td>
    <td style="border:none; padding:5px;">:
      <input type="text" class="input_type" 
             name="form_147[kapasitas_mets]" 
             id="kapasitas_mets" 
             style="width:80px;" 
             onchange="fillthis('kapasitas_mets')"> Mets
    </td>
  </tr>

</table>


</div>


<!-- ----- -->
<!-- TANDA TANGAN -->
<!-- ----- -->
<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <td style="width:33%; text-align:center;">
        Dokter yang mengerjakan,
        <br><br>
        <span class="ttd-btn" data-role="petugas" id="ttd_petugas" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_petugas" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_147[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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