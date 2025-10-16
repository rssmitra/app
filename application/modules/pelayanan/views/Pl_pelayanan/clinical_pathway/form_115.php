<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script type="text/javascript">
  $('#assesmen_diagnosa_primer').typeahead({
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
      $('#assesmen_diagnosa_primer').val(label_item);
      $('#assesmen_diagnosa_primer_hidden').val(val_item);
      }

  });

  $('#pl_diagnosa_sekunder').typeahead({
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
      $('#pl_diagnosa_sekunder').val('');
      $('<span class="multi-typeahead" id="txt_icd_'+val_item.trim().replace('.', '_')+'"><a href="#" onclick="remove_icd('+"'"+val_item.trim().replace('.', '_')+"'"+')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span><span class="text_icd_10"> '+item+' </span> </span>').appendTo('#pl_diagnosa_sekunder_hidden_txt');
      }

  });

  $( "#pl_diagnosa_sekunder" )    
    .keypress(function(event) {        
      var keycode =(event.keyCode?event.keyCode:event.which);         
      if(keycode ==13){          
        event.preventDefault();         
        if($(this).valid()){            
          var val_item = 1 + Math.floor(Math.random() * 100);
          console.log(val_item);
          var item = $('#pl_diagnosa_sekunder').val();
          $('<span class="multi-typeahead" id="txt_icd_'+val_item+'"><a href="#" onclick="remove_icd('+"'"+val_item+"'"+')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span><span class="text_icd_10"> '+item+' </span> </span>').appendTo('#pl_diagnosa_sekunder_hidden_txt'); 
        }          
        return $('#pl_diagnosa_sekunder').val('');                 
      }    
  });

  function remove_icd(icd){
      preventDefault();
      $('#txt_icd_'+icd+'').html('');
      $('#txt_icd_'+icd+'').hide();
  }
  
</script>

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
      var hiddenInputName = 'form_115[ttd_' + role + ']';
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

<div style="text-align: center; font-size: 14px"><b>PENGKAJIAN AWAL KEPERAWATAN RAWAT INAP (ANAK)</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<table class="table">
  <tr>
    <td width="30%" valign="middle" >Masuk ruang rawat </td>
    <td width="70%"> <input class="input_type" type="text" style="width: 100px" name="form_115[masuk_ruang]" id="masuk_ruang" onchange="fillthis('masuk_ruang')"> 
    Kelas : <input class="input_type" type="text" style="width: 50px" name="form_115[3o_kelas_rawat]" id="3o_kelas_rawat" onchange="fillthis('3o_kelas_rawat')"> 
    Tanggal : <input class="input_type" type="text" style="width: 70px" name="form_115[tanggal]" id="tanggal" onchange="fillthis('tanggal')"> 
    Jam : <input class="input_type" type="text" style="width: 50px" name="form_115[jam]" id="jam" onchange="fillthis('jam')"></td>
  </tr>
  <tr>
    <td valign="center">Cara Masuk</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_115[irj]" id="irj"  onclick="checkthis('irj')">
          <span class="lbl" > &nbsp; IRJ</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_115[igd]" id="igd"  onclick="checkthis('igd')">
          <span class="lbl" > &nbsp; IGD</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_115[icu]" id="icu"  onclick="checkthis('icu')">
          <span class="lbl" > &nbsp; ICU</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td valign="center">Tiba diruang rawat dengan cara</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_115[jalan]" id="jalan"  onclick="checkthis('jalan')">
          <span class="lbl" > &nbsp; Jalan</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_115[kursi_roda]" id="kursi_roda"  onclick="checkthis('kursi_roda')">
          <span class="lbl" > &nbsp; Kursi Roda</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_115[pakai_brankar]" id="pakai_brankar"  onclick="checkthis('pakai_brankar')">
          <span class="lbl" > &nbsp; Brankar</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td valign="center">Informasi didapat dari</td>
    <td>
      <div class="checkbox">
        <label>
          <input type="checkbox" class="ace" name="form_115[informasi_autoanamnesis]" id="informasi_autoanamnesis"  onclick="checkthis('informasi_autoanamnesis')">
          <span class="lbl" > &nbsp; Autoanamnesis</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_115[informasi_alloanamnesis]" id="informasi_alloanamnesis"  onclick="checkthis('informasi_alloanamnesis')">
          <span class="lbl" > &nbsp; Alloanamnesis</span>
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td width="30%" valign="middle" >Diagnosis Masuk </td>
    <td width="70%"> <input class="input_type" type="text" style="width: 300px" name="form_115[diagnosis_masuk]" id="diagnosis_masuk" onchange="fillthis('diagnosis_masuk')"></td>
  </tr>
  <tr>
    <td width="30%" valign="middle" >Dokter yang merawat </td>
    <td width="70%"> <input class="input_type" type="text" style="width: 300px" name="form_115[dokter_instruksi_rawat]" id="dokter_instruksi_rawat" onchange="fillthis('dokter_instruksi_rawat')"></td>
  </tr>
  <tr>
    <td width="30%" valign="middle" >Rencana Terapi </td>
    <td width="70%"> <input class="input_type" type="text" style="width: 300px" name="form_115[rencana_terapi]" id="rencana_terapi" onchange="fillthis('rencana_terapi')"></td>
  </tr>
  
  
</table>

<!---- END --->
<hr>
<!-- AWAL BIODATA ANAK -->
<table class="table">
  <tr>
  <td valign="center" style="text-align: center; font-weight: bold; font-size: 14px; background-color: #f0f0f0;" colspan="2">BIODATA PASIEN</td>
  </tr>  
  
  <tr>
  <td valign="center" style="text-align: left; font-weight: bold; font-size: 12px" colspan="2">A. IDENTITAS PASIEN</td>
  </tr>  

  <tr>
    <td valign="center" width="30%">Nama / Nama Panggilan</td>
    <td width="70%">
     <input type="text" class="input_type" style="width: 250px !important" name="form_115[nama_pasien_istirahat]" id="nama_pasien_istirahat" onchange="fillthis('nama_pasien_istirahat')" value="<?php echo isset($value_form['nama_pasien_istirahat'])?$value_form['nama_pasien_istirahat']:$data_pasien->nama_pasien?>">
    </td>
  </tr>

  <!-- <tr>
  <td>Tempat / Tanggal Lahir</td>
  <td colspan="2">
    : <input 
        type="text" 
        class="input_type date-picker" 
        data-date-format="yyyy-mm-dd"
        style="width: 250px !important;" 
        name="form_115[ttl_pasien]" 
        id="ttl_pasien"
        value="<?php 
          // Ambil tempat & tanggal lahir dari database
          $tempat_lahir = isset($data_pasien->tempat_lahir) ? trim($data_pasien->tempat_lahir) : '';
          $tgl_lhr = isset($data_pasien->tgl_lhr) ? $data_pasien->tgl_lhr : ''; 

          // Format tanggal jika tidak kosong
          if (!empty($tgl_lhr)) {
            $tgl_lhr = date('Y-m-d', strtotime($tgl_lhr));
          }

          // Gabungkan, contoh: 'Jakarta, 1990-12-31'
          $ttl = trim($tempat_lahir . (empty($tgl_lhr) ? '' : ', ' . $tgl_lhr));

          // Jika ada value form sebelumnya, pakai itu; kalau tidak, pakai hasil gabungan
          echo isset($value_form['ttl_pasien']) ? $value_form['ttl_pasien'] : $ttl; 
        ?>"
      >
  </td>
</tr> -->


<tr>
  <td>Tempat / Tanggal Lahir</td>
  <td colspan="2">
    : <input 
        type="text" 
        class="input_type" 
        style="width: 120px !important;" 
        name="form_115[tempat_lahir]" 
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
        name="form_115[tanggal_lahir]" 
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


  <!-- <tr>
    <td valign="center" width="30%">Usia</td>
    <td width="70%">
     <input type="text" class="input_type" style="width: 250px !important" name="form_115[umur_pasien]" id="umur_pasien" onchange="fillthis('umur_pasien')" 
     value="<?php $umur = isset($data_pasien->umur) ? $data_pasien->umur : ''; echo isset($value_form['umur_pasien']) ? $value_form['umur_pasien'] . ' tahun' : $umur . ' tahun'; ?>">
    </td>
  </tr> -->

  <tr>
    <td valign="center" width="30%">Jenis Kelamin</td>
    <td>
        <label>
          <input type="checkbox" class="ace" name="form_115[jk_l]" id="jk_l"  onclick="checkthis('jk_l')" <?php echo ($data_pasien->jen_kelamin == 'L')?"checked":"";?>>
          <span class="lbl" > Laki-laki</span>
        </label>
        
        <label>
          <input type="checkbox" class="ace" name="form_115[jk_p]" id="jk_p"  onclick="checkthis('jk_p')" <?php echo ($data_pasien->jen_kelamin == 'P')?"checked":"";?>>
          <span class="lbl" > Perempuan</span>
        </label>
      </td>
  </tr>

  <tr>
    <td valign="center" width="30%">Agama</td>
    <td width="70%">
       <input type="text" class="input_type" style="width: 250px !important" name="form_115[agama_pasien]" id="agama_pasien" onchange="fillthis('agama_pasien')">
    </td>
  </tr>

  <tr>
    <td valign="center" width="30%">Pendidikan</td>
    <td width="70%">
       <input type="text" class="input_type" style="width: 250px !important" name="form_115[pendidikan_pasien]" id="pendidikan_pasien" onchange="fillthis('pendidikan_pasien')">
    </td>
  </tr>

  <tr>
    <td valign="center" width="30%">Alamat</td>
    <td width="70%">
     <input type="text" class="input_type" style="width: 250px !important" name="form_115[alamt_ttp_pasien]" id="alamt_ttp_pasien" onchange="fillthis('alamt_ttp_pasien')" 
     value="<?php echo $data_pasien->almt_ttp_pasien?>">
    </td>
</tr>
</table>
<!---- END DATA ANAK --->

<!-- DATA AYAH DAN IBU -->
<table class="table" width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 12px;">
  <tr>
    <td colspan="4" style="text-align: left; font-weight: bold; font-size: 12px;">B. IDENTITAS ORANG TUA</td>
  </tr>

  <tr style="background-color: #f0f0f0; text-align: center; font-weight: bold;">
    <td width="25%">Keterangan</td>
    <td width="35%">DATA AYAH</td>
    <td width="5%"></td>
    <td width="35%">DATA IBU</td>
  </tr>

  <tr>
    <td><b>Nama</b></td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
        name="form_115[nama_ayah]" id="nama_ayah" onchange="fillthis('nama_ayah')">
    </td>
    <td></td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
        name="form_115[nama_ibu]" id="nama_ibu" onchange="fillthis('nama_ibu')">
    </td>
  </tr>

  <tr>
    <td><b>Usia</b></td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
        name="form_115[usia_ayah]" id="usia_ayah" onchange="fillthis('usia_ayah')">
    </td>
    <td></td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
        name="form_115[usia_ibu]" id="usia_ibu" onchange="fillthis('usia_ibu')">
    </td>
  </tr>

  <tr>
    <td><b>Pendidikan</b></td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
        name="form_115[pendidikan_ayah]" id="pendidikan_ayah" onchange="fillthis('pendidikan_ayah')">
    </td>
    <td></td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
        name="form_115[pendidikan_ibu]" id="pendidikan_ibu" onchange="fillthis('pendidikan_ibu')">
    </td>
  </tr>

  <tr>
    <td><b>Pekerjaan</b></td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
        name="form_115[pekerjaan_ayah]" id="pekerjaan_ayah" onchange="fillthis('pekerjaan_ayah')">
    </td>
    <td></td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
        name="form_115[pekerjaan_ibu]" id="pekerjaan_ibu" onchange="fillthis('pekerjaan_ibu')">
    </td>
  </tr>

  <tr>
    <td><b>Agama</b></td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
        name="form_115[agama_ayah]" id="agama_ayah" onchange="fillthis('agama_ayah')">
    </td>
    <td></td>
    <td>
      <input type="text" class="input_type" style="width: 100%;" 
        name="form_115[agama_ibu]" id="agama_ibu" onchange="fillthis('agama_ibu')">
    </td>
  </tr>

  <tr>
    <td><b>Alamat</b></td>
    <td>
      <textarea class="input_type" style="width: 100%; height: 60px;" 
        name="form_115[alamat_ayah]" id="alamat_ayah" onchange="fillthis('alamat_ayah')"></textarea>
    </td>
    <td></td>
    <td>
      <textarea class="input_type" style="width: 100%; height: 60px;" 
        name="form_115[alamat_ibu]" id="alamat_ibu" onchange="fillthis('alamat_ibu')"></textarea>
    </td>
  </tr>
</table>
<!---- END DATA AYAH IBU --->

<!-- C. IDENTITAS SAUDARA KANDUNG -->
<table class="table" width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 12px;">
  <tr>
    <td colspan="5" style="text-align: left; font-weight: bold; font-size: 12px;">C. IDENTITAS SAUDARA KANDUNG</td>
  </tr>

  <tr style="background-color: #f0f0f0; text-align: center; font-weight: bold;">
    <td width="5%">No</td>
    <td width="30%">Nama</td>
    <td width="15%">Usia</td>
    <td width="25%">Hubungan</td>
    <td width="25%">Status Kesehatan</td>
  </tr>

  <!-- Baris 1 -->
  <tr>
    <td style="text-align: center;">1</td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[nama_saudara_1]" id="nama_saudara_1" onchange="fillthis('nama_saudara_1')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[usia_saudara_1]" id="usia_saudara_1" onchange="fillthis('usia_saudara_1')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[hubungan_saudara_1]" id="hubungan_saudara_1" onchange="fillthis('hubungan_saudara_1')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[status_kesehatan_saudara_1]" id="status_kesehatan_saudara_1" onchange="fillthis('status_kesehatan_saudara_1')"></td>
  </tr>

  <!-- Baris 2 -->
  <tr>
    <td style="text-align: center;">2</td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[nama_saudara_2]" id="nama_saudara_2" onchange="fillthis('nama_saudara_2')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[usia_saudara_2]" id="usia_saudara_2" onchange="fillthis('usia_saudara_2')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[hubungan_saudara_2]" id="hubungan_saudara_2" onchange="fillthis('hubungan_saudara_2')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[status_kesehatan_saudara_2]" id="status_kesehatan_saudara_2" onchange="fillthis('status_kesehatan_saudara_2')"></td>
  </tr>

  <!-- Baris 3 -->
  <tr>
    <td style="text-align: center;">3</td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[nama_saudara_3]" id="nama_saudara_3" onchange="fillthis('nama_saudara_3')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[usia_saudara_3]" id="usia_saudara_3" onchange="fillthis('usia_saudara_3')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[hubungan_saudara_3]" id="hubungan_saudara_3" onchange="fillthis('hubungan_saudara_3')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[status_kesehatan_saudara_3]" id="status_kesehatan_saudara_3" onchange="fillthis('status_kesehatan_saudara_3')"></td>
  </tr>

  <!-- Baris 4 -->
  <tr>
    <td style="text-align: center;">4</td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[nama_saudara_4]" id="nama_saudara_4" onchange="fillthis('nama_saudara_4')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[usia_saudara_4]" id="usia_saudara_4" onchange="fillthis('usia_saudara_4')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[hubungan_saudara_4]" id="hubungan_saudara_4" onchange="fillthis('hubungan_saudara_4')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[status_kesehatan_saudara_4]" id="status_kesehatan_saudara_4" onchange="fillthis('status_kesehatan_saudara_4')"></td>
  </tr>

  <!-- Tambahan baris jika diperlukan -->
  <tr>
    <td style="text-align: center;">5</td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[nama_saudara_5]" id="nama_saudara_5" onchange="fillthis('nama_saudara_5')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[usia_saudara_5]" id="usia_saudara_5" onchange="fillthis('usia_saudara_5')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[hubungan_saudara_5]" id="hubungan_saudara_5" onchange="fillthis('hubungan_saudara_5')"></td>
    <td><input type="text" class="input_type" style="width: 100%;" name="form_115[status_kesehatan_saudara_5]" id="status_kesehatan_saudara_5" onchange="fillthis('status_kesehatan_saudara_5')"></td>
  </tr>
</table>
<!---- END --->


<!-- ========================================================= -->
<!-- RIWAYAT KESEHATAN -->
<!-- ========================================================= -->

<table class="table" width="100%" border="1" cellspacing="0" cellpadding="4" 
       style="border-collapse: collapse; font-size: 12px;">
  
  <!-- HEADER -->
  <tr>
    <td colspan="2" style="text-align: center; font-weight: bold; font-size: 14px; background-color: #f0f0f0;">RIWAYAT KESEHATAN</td>
  </tr>

  <!-- A. RIWAYAT KESEHATAN SEKARANG -->
  <tr>
    <td colspan="2" style="font-weight: bold;">A. RIWAYAT KESEHATAN SEKARANG</td>
  </tr>

  <tr>
    <td width="40%">Keluhan Utama</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
             name="form_115[keluhan_utama]" id="keluhan_utama" 
             onchange="fillthis('keluhan_utama')">
    </td>
  </tr>

  <tr>
    <td>Riwayat Keluhan Utama</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
             name="form_115[riwayat_keluhan]" id="riwayat_keluhan" 
             onchange="fillthis('riwayat_keluhan')">
    </td>
  </tr>

  <tr>
    <td>Keluhan Pada Saat Pengkajian</td>
    <td>
      <input type="text" class="input_type" style="width:100%;" 
             name="form_115[keluhan_saat_ini]" id="keluhan_saat_ini" 
             onchange="fillthis('keluhan_saat_ini')">
    </td>
  </tr>

  <!-- B. RIWAYAT KESEHATAN LALU -->
  <tr>
    <td colspan="2" style="font-weight: bold;">B. RIWAYAT KESEHATAN LALU (khusus untuk anak 0 - 5 tahun)</td>
  </tr>

  <!-- 1. PRENATAL CARE -->
  <tr>
    <td colspan="2" style="font-weight: bold;">1. PRENATAL CARE</td>
  </tr>

  <!-- a. Pemeriksaan Kehamilan -->
  <tr>
    <td valign="top" width="50%">
      a. Dimanakah Ibu memeriksakan kehamilan setiap minggu:
    </td>
    <td>
      <label><input type="checkbox" class="ace" name="form_115[periksa_kehamilan][]" id="periksa_puskesmas" value="Puskesmas" onclick="checkthis('periksa_puskesmas')"> <span class="lbl">Puskesmas</span></label>
      <label><input type="checkbox" class="ace" name="form_115[periksa_kehamilan][]" id="periksa_rs" value="Rumah Sakit" onclick="checkthis('periksa_rs')"> <span class="lbl">Rumah Sakit</span></label>
      <label><input type="checkbox" class="ace" name="form_115[periksa_kehamilan][]" id="periksa_lainnya" value="Lainnya" onclick="checkthis('periksa_lainnya')"> <span class="lbl">Lainnya</span></label><br>
      <input type="text" class="input_type" style="width:100%;" 
             name="form_115[periksa_lainnya_ket]" id="periksa_lainnya_ket" 
             placeholder="Lainnya..." onchange="fillthis('periksa_lainnya_ket')">
    </td>
  </tr>

  <!-- a. Pemeriksaan Kehamilan -->
  <tr>
    <td valign="top" width="50%">
         Keluhan selama hamil yang dirasakan oleh Ibu
    </td>
    <td>
      <label><input type="checkbox" class="ace" name="form_115[periksa_kehamilan][]" id="keluhan_mual" value="Mual" onclick="checkthis('keluhan_mual')"> <span class="lbl">Mual</span></label>
      <label><input type="checkbox" class="ace" name="form_115[periksa_kehamilan][]" id="keluhan_pendarahan" value="Pendarahan" onclick="checkthis('keluhan_pendarahan')"> <span class="lbl">Pendarahan</span></label>
      <label><input type="checkbox" class="ace" name="form_115[periksa_kehamilan][]" id="keluhan_lemas" value="Lemas" onclick="checkthis('keluhan_lemas')"> <span class="lbl">Lemas</span></label>
      <label><input type="checkbox" class="ace" name="form_115[periksa_kehamilan][]" id="keluhan_lainnya" value="Keluhan lainnya" onclick="checkthis('keluhan_lainnya')"> <span class="lbl">Lainnya</span></label><br>
      <input type="text" class="input_type" style="width:100%;" 
             name="form_115[keluhan_lainnya_ket]" id="keluhan_lainnya_ket" 
             placeholder="Keluhan lainnya..." onchange="fillthis('keluhan_lainnya_ket')">
    </td>
  </tr>

  <!-- b. Radiasi -->
  <tr>
    <td>b. Riwayat Terkena Radiasi</td>
    <td>
      <label><input type="checkbox" class="ace" name="form_115[radiasi]" id="radiasi_ya" value="Ya" onclick="checkthis('radiasi_ya')"> <span class="lbl">Ya</span></label>
      <label><input type="checkbox" class="ace" name="form_115[radiasi]" id="radiasi_tidak" value="Tidak" onclick="checkthis('radiasi_tidak')"> <span class="lbl">Tidak</span></label>
    </td>
  </tr>

  <!-- c. Berat Badan -->
  <tr>
    <td>c. Riwayat berat badan selama hamil</td>
    <td>
      <label><input type="checkbox" class="ace" name="form_115[bb_hamil]" id="bb_stabil" value="Stabil" onclick="checkthis('bb_stabil')"> <span class="lbl">Stabil</span></label>
      <label><input type="checkbox" class="ace" name="form_115[bb_hamil]" id="bb_tidak_stabil" value="Tidak Stabil" onclick="checkthis('bb_tidak_stabil')"> <span class="lbl">Tidak Stabil</span></label><br>
      Keterangan: <input type="text" class="input_type" style="width:70%;" 
                         name="form_115[bb_ket]" id="bb_ket" onchange="fillthis('bb_ket')">
    </td>
  </tr>

  <!-- d. Imunisasi TT -->
  <tr>
    <td>d. Riwayat Imunisasi TT</td>
    <td>
      <label><input type="checkbox" class="ace" name="form_115[tt]" id="tt_ya" value="Ya" onclick="checkthis('tt_ya')"> <span class="lbl">Ya</span></label>
      <label><input type="checkbox" class="ace" name="form_115[tt]" id="tt_tidak" value="Tidak" onclick="checkthis('tt_tidak')"> <span class="lbl">Tidak</span></label>
    </td>
  </tr>

  <!-- e. Golongan darah ibu -->
  <tr>
    <td>e. Golongan darah ibu</td>
    <td>
      <label><input type="checkbox" class="ace" name="form_115[gol_darah_ibu]" id="gol_a_ibu" value="A" onclick="checkthis('gol_a_ibu')"> <span class="lbl">A</span></label>
      <label><input type="checkbox" class="ace" name="form_115[gol_darah_ibu]" id="gol_b_ibu" value="B" onclick="checkthis('gol_b_ibu')"> <span class="lbl">B</span></label>
      <label><input type="checkbox" class="ace" name="form_115[gol_darah_ibu]" id="gol_o_ibu" value="O" onclick="checkthis('gol_o_ibu')"> <span class="lbl">O</span></label>
      <label><input type="checkbox" class="ace" name="form_115[gol_darah_ibu]" id="gol_ab_ibu" value="AB" onclick="checkthis('gol_ab_ibu')"> <span class="lbl">AB</span></label>
      <label><input type="checkbox" class="ace" name="form_115[gol_darah_ibu]" id="gol_tidak_tahu_ibu" value="Tidak tahu" onclick="checkthis('gol_tidak_tahu_ibu')"> <span class="lbl">Tidak tahu</span></label>
    </td>
  </tr>

  <!-- f. Golongan darah ayah -->
  <tr>
    <td>f. Golongan darah ayah</td>
    <td>
      <label><input type="checkbox" class="ace" name="form_115[gol_darah_ayah]" id="gol_a_ayah" value="A" onclick="checkthis('gol_a_ayah')"> <span class="lbl">A</span></label>
      <label><input type="checkbox" class="ace" name="form_115[gol_darah_ayah]" id="gol_b_ayah" value="B" onclick="checkthis('gol_b_ayah')"> <span class="lbl">B</span></label>
      <label><input type="checkbox" class="ace" name="form_115[gol_darah_ayah]" id="gol_o_ayah" value="O" onclick="checkthis('gol_o_ayah')"> <span class="lbl">O</span></label>
      <label><input type="checkbox" class="ace" name="form_115[gol_darah_ayah]" id="gol_ab_ayah" value="AB" onclick="checkthis('gol_ab_ayah')"> <span class="lbl">AB</span></label>
      <label><input type="checkbox" class="ace" name="form_115[gol_darah_ayah]" id="gol_tidak_tahu_ayah" value="Tidak tahu" onclick="checkthis('gol_tidak_tahu_ayah')"> <span class="lbl">Tidak tahu</span></label>
    </td>
  </tr>

  <!-- B.2. NATAL -->
   <tr>
        <td colspan="2" style="font-weight: bold;">2. NATAL</td>
   </tr>

   <tr>
      <td width="40%">a. Tempat melahirkan</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_115[tempat_melahirkan][]" id="tempat_melahirkan_puskesmas" onclick="checkthis('tempat_melahirkan_puskesmas')" value="Puskesmas"> <span class="lbl"> Puskesmas</span></label>
        <label><input type="checkbox" class="ace" name="form_115[tempat_melahirkan][]" id="tempat_melahirkan_rs" onclick="checkthis('tempat_melahirkan_rs')" value="Rumah Sakit"> <span class="lbl"> Rumah Sakit</span></label>
        <label><input type="checkbox" class="ace" name="form_115[tempat_melahirkan][]" id="tempat_melahirkan_lainnya" onclick="checkthis('tempat_melahirkan_lainnya')" value="Lainnya"> <span class="lbl"> Lainnya</span></label>
        <input type="text" class="input_type" style="width: 200px;" name="form_115[tempat_melahirkan_lain]" id="tempat_melahirkan_lain" onchange="fillthis('tempat_melahirkan_lain')">
      </td>
    </tr>

    <tr>
      <td>b. Jenis persalinan</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_115[jenis_persalinan][]" id="jenis_persalinan_normal" onclick="checkthis('jenis_persalinan_normal')" value="Normal"> <span class="lbl"> Normal</span></label>
        <label><input type="checkbox" class="ace" name="form_115[jenis_persalinan][]" id="jenis_persalinan_sc" onclick="checkthis('jenis_persalinan_sc')" value="Section Cesarea"> <span class="lbl"> Section Cesarea</span></label>
      </td>
    </tr>

    <tr>
      <td>c. Penolong persalinan</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_115[penolong_persalinan][]" id="penolong_bidan" onclick="checkthis('penolong_bidan')" value="Bidan"> <span class="lbl"> Bidan</span></label>
        <label><input type="checkbox" class="ace" name="form_115[penolong_persalinan][]" id="penolong_dokter" onclick="checkthis('penolong_dokter')" value="Dokter"> <span class="lbl"> Dokter</span></label>
        <label><input type="checkbox" class="ace" name="form_115[penolong_persalinan][]" id="penolong_lainnya" onclick="checkthis('penolong_lainnya')" value="Lainnya"> <span class="lbl"> Lainnya</span></label>
        <input type="text" class="input_type" style="width: 200px;" name="form_115[penolong_persalinan_lain]" id="penolong_persalinan_lain" onchange="fillthis('penolong_persalinan_lain')">
      </td>
    </tr>

    <tr>
      <td>d. Komplikasi yang dialami oleh ibu</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_115[komplikasi_ibu][]" id="komplikasi_tidak" onclick="checkthis('komplikasi_tidak')" value="Tidak ada"> <span class="lbl"> Tidak ada</span></label>
        <label><input type="checkbox" class="ace" name="form_115[komplikasi_ibu][]" id="komplikasi_ya" onclick="checkthis('komplikasi_ya')" value="Ya"> <span class="lbl"> Ya, sebutkan</span></label>
        <input type="text" class="input_type" style="width: 300px;" name="form_115[komplikasi_ibu_ket]" id="komplikasi_ibu_ket" onchange="fillthis('komplikasi_ibu_ket')">
      </td>
    </tr>


    <!-- 3. POST NATAL -->
    <tr>
        <td colspan="2" style="font-weight: bold;">3. POST NATAL</td>
    </tr>

     <tr>
      <td width="40%">a. Kondisi bayi</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_115[kondisi_bayi][]" id="kondisi_bayi_sehat" onclick="checkthis('kondisi_bayi_sehat')" value="Sehat"> <span class="lbl"> Sehat</span></label>
        <label><input type="checkbox" class="ace" name="form_115[kondisi_bayi][]" id="kondisi_bayi_tidak" onclick="checkthis('kondisi_bayi_tidak')" value="Tidak sehat"> <span class="lbl"> Tidak sehat, sebutkan</span></label>
        <input type="text" class="input_type" name="form_115[kondisi_bayi_ket]" id="kondisi_bayi_ket" style="width: 200px;" onchange="fillthis('kondisi_bayi_ket')">
        <br>BB: <input type="text" class="input_type" style="width: 70px;" name="form_115[bb_lahir]" id="bb_lahir" onchange="fillthis('bb_lahir')"> gr, 
        PB: <input type="text" class="input_type" style="width: 70px;" name="form_115[pb_lahir]" id="pb_lahir" onchange="fillthis('pb_lahir')"> cm
      </td>
    </tr>

    <tr>
      <td>b. Anak pada saat lahir mengalami permasalahan</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_115[permasalahan_lahir][]" id="permasalahan_tidak" onclick="checkthis('permasalahan_tidak')" value="Tidak ada"> <span class="lbl"> Tidak ada</span></label>
        <label><input type="checkbox" class="ace" name="form_115[permasalahan_lahir][]" id="permasalahan_ada" onclick="checkthis('permasalahan_ada')" value="Ada"> <span class="lbl"> Ada, sebutkan</span></label>
        <input type="text" class="input_type" name="form_115[permasalahan_lahir_ket]" id="permasalahan_lahir_ket" style="width: 300px;" onchange="fillthis('permasalahan_lahir_ket')">
      </td>
    </tr>

    <!-- (UNTUK SEMUA USIA) -->
    <tr>
        <td colspan="2" style="font-weight: bold;">(UNTUK SEMUA USIA)</td>
    </tr>

     <tr>
      <td width="40%">a. Pasien pernah mengalami penyakit</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_115[penyakit][]" id="penyakit_tidak" onclick="checkthis('penyakit_tidak')" value="Tidak ada"> <span class="lbl"> Tidak ada</span></label>
        <label><input type="checkbox" class="ace" name="form_115[penyakit][]" id="penyakit_ada" onclick="checkthis('penyakit_ada')" value="Ada"> <span class="lbl"> Ada, sebutkan</span></label>
        <input type="text" class="input_type" style="width: 300px;" name="form_115[penyakit_ket]" id="penyakit_ket" onchange="fillthis('penyakit_ket')">
      </td>
    </tr>

    <tr>
      <td>b. Riwayat kecelakaan</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_115[kecelakaan][]" id="kecelakaan_tidak" onclick="checkthis('kecelakaan_tidak')" value="Tidak ada"> <span class="lbl"> Tidak ada</span></label>
        <label><input type="checkbox" class="ace" name="form_115[kecelakaan][]" id="kecelakaan_ada" onclick="checkthis('kecelakaan_ada')" value="Ada"> <span class="lbl"> Ada, sebutkan</span></label>
        <input type="text" class="input_type" style="width: 300px;" name="form_115[kecelakaan_ket]" id="kecelakaan_ket" onchange="fillthis('kecelakaan_ket')">
      </td>
    </tr>

    <tr>
      <td>c. Riwayat mengkonsumsi obat-obatan tanpa anjuran dokter</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_115[obat_tanpa_dokter][]" id="obat_tidak" onclick="checkthis('obat_tidak')" value="Tidak ada"> <span class="lbl"> Tidak ada</span></label>
        <label><input type="checkbox" class="ace" name="form_115[obat_tanpa_dokter][]" id="obat_ada" onclick="checkthis('obat_ada')" value="Ada"> <span class="lbl"> Ada, sebutkan</span></label>
        <input type="text" class="input_type" style="width: 300px;" name="form_115[obat_ket]" id="obat_ket" onchange="fillthis('obat_ket')">
      </td>
    </tr>

    <tr>
      <td>d. Perkembangan anak dibandingkan saudara-saudaranya</td>
      <td>
        <label><input type="checkbox" class="ace" name="form_115[perkembangan_anak][]" id="perkembangan_sama" onclick="checkthis('perkembangan_sama')" value="Tidak berbeda"> <span class="lbl"> Tidak berbeda</span></label>
        <label><input type="checkbox" class="ace" name="form_115[perkembangan_anak][]" id="perkembangan_berbeda" onclick="checkthis('perkembangan_berbeda')" value="Berbeda"> <span class="lbl"> Berbeda, sebutkan</span></label>
        <input type="text" class="input_type" style="width: 300px;" name="form_115[perkembangan_ket]" id="perkembangan_ket" onchange="fillthis('perkembangan_ket')">
      </td>
    </tr>

    <!-- C. RIWAYAT KESEHATAN KELUARGA -->
    <tr>
        <td colspan="2" style="font-weight: bold;">C. RIWAYAT KESEHATAN KELUARGA</td>
    </tr>
    
    <tr>
  <!-- Area Genogram -->
  <td colspan="2" style="border: 1px solid black; padding: 5px; vertical-align: top;">
  <b>Genogram</b><br>
  <div contenteditable="true"
       class="input_type"
       name="form_115[genogram]"
       id="genogram"
       onchange="fillthis('genogram')"
       style="width: 100%; min-height: 100px; white-space: pre-wrap; word-wrap: break-word; overflow: visible; border: 1px solid #ccc; padding: 2px;"><?php echo isset($value_form['genogram']) ? $value_form['genogram'] : '' ?></div>
    </td>
    </div>
    </tr>

    <td colspan="2" style="border: 1px solid black; padding: 5px; vertical-align: top;">
  <b>Keterangan</b><br>
  <input type="text"
           class="input_type"
           name="form_115[ket_genogram]"
           id="ket_genogram"
           onchange="fillthis('ket_genogram')"
           style="width: 100%; border: 1px solid #ccc; padding: 3px;"
           value="<?php echo isset($value_form['ket_genogram']) ? $value_form['ket_genogram'] : '' ?>">
    </td>
    </tr>


</table>
<!---- END --->

<hr>
<!-- AWAL RIWAYAT IMUNISASI -->
<table class="table" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px; width:100%;">
  <tr>
    <td colspan="6" style="text-align: center; font-weight: bold; font-size: 14px; background-color: #f0f0f0;">
      RIWAYAT IMUNISASI (IMUNISASI LENGKAP)
    </td>
  </tr>

  <tr style="text-align: center; font-weight: bold;">
    <td style="width: 5%;">No</td>
    <td style="width: 25%;">Jenis Imunisasi</td>
    <td style="width: 20%;">Waktu Pemberian</td>
    <td style="width: 15%;">Frekuensi</td>
    <td style="width: 25%;">Reaksi Setelah Pemberian</td>
    <td style="width: 10%;">Frekuensi Reaksi</td>
  </tr>

  <tr>
    <td style="text-align: center;">1</td>
    <td>BCG</td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_bcg_waktu]" id="imunisasi_bcg_waktu" style="width: 150px;" onchange="fillthis('imunisasi_bcg_waktu')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_bcg_frek]" id="imunisasi_bcg_frek" style="width: 100px;" onchange="fillthis('imunisasi_bcg_frek')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_bcg_reaksi]" id="imunisasi_bcg_reaksi" style="width: 200px;" onchange="fillthis('imunisasi_bcg_reaksi')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_bcg_frek_reaksi]" id="imunisasi_bcg_frek_reaksi" style="width: 100px;" onchange="fillthis('imunisasi_bcg_frek_reaksi')"></td>
  </tr>

  <tr>
    <td style="text-align: center;">2</td>
    <td>DPT (I, II, III)</td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_dpt_waktu]" id="imunisasi_dpt_waktu" style="width: 150px;" onchange="fillthis('imunisasi_dpt_waktu')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_dpt_frek]" id="imunisasi_dpt_frek" style="width: 100px;" onchange="fillthis('imunisasi_dpt_frek')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_dpt_reaksi]" id="imunisasi_dpt_reaksi" style="width: 200px;" onchange="fillthis('imunisasi_dpt_reaksi')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_dpt_frek_reaksi]" id="imunisasi_dpt_frek_reaksi" style="width: 100px;" onchange="fillthis('imunisasi_dpt_frek_reaksi')"></td>
  </tr>

  <tr>
    <td style="text-align: center;">3</td>
    <td>Polio (I, II, III, IV)</td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_polio_waktu]" id="imunisasi_polio_waktu" style="width: 150px;" onchange="fillthis('imunisasi_polio_waktu')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_polio_frek]" id="imunisasi_polio_frek" style="width: 100px;" onchange="fillthis('imunisasi_polio_frek')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_polio_reaksi]" id="imunisasi_polio_reaksi" style="width: 200px;" onchange="fillthis('imunisasi_polio_reaksi')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_polio_frek_reaksi]" id="imunisasi_polio_frek_reaksi" style="width: 100px;" onchange="fillthis('imunisasi_polio_frek_reaksi')"></td>
  </tr>

  <tr>
    <td style="text-align: center;">4</td>
    <td>Campak</td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_campak_waktu]" id="imunisasi_campak_waktu" style="width: 150px;" onchange="fillthis('imunisasi_campak_waktu')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_campak_frek]" id="imunisasi_campak_frek" style="width: 100px;" onchange="fillthis('imunisasi_campak_frek')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_campak_reaksi]" id="imunisasi_campak_reaksi" style="width: 200px;" onchange="fillthis('imunisasi_campak_reaksi')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_campak_frek_reaksi]" id="imunisasi_campak_frek_reaksi" style="width: 100px;" onchange="fillthis('imunisasi_campak_frek_reaksi')"></td>
  </tr>

  <tr>
    <td style="text-align: center;">5</td>
    <td>Hepatitis</td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_hepatitis_waktu]" id="imunisasi_hepatitis_waktu" style="width: 150px;" onchange="fillthis('imunisasi_hepatitis_waktu')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_hepatitis_frek]" id="imunisasi_hepatitis_frek" style="width: 100px;" onchange="fillthis('imunisasi_hepatitis_frek')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_hepatitis_reaksi]" id="imunisasi_hepatitis_reaksi" style="width: 200px;" onchange="fillthis('imunisasi_hepatitis_reaksi')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_hepatitis_frek_reaksi]" id="imunisasi_hepatitis_frek_reaksi" style="width: 100px;" onchange="fillthis('imunisasi_hepatitis_frek_reaksi')"></td>
  </tr>

  <tr>
    <td style="text-align: center;">6</td>
    <td>Lainnya...</td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_lainnya_waktu]" id="imunisasi_lainnya_waktu" style="width: 150px;" onchange="fillthis('imunisasi_lainnya_waktu')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_lainnya_frek]" id="imunisasi_lainnya_frek" style="width: 100px;" onchange="fillthis('imunisasi_lainnya_frek')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_lainnya_reaksi]" id="imunisasi_lainnya_reaksi" style="width: 200px;" onchange="fillthis('imunisasi_lainnya_reaksi')"></td>
    <td><input type="text" class="input_type" name="form_115[imunisasi_lainnya_frek_reaksi]" id="imunisasi_lainnya_frek_reaksi" style="width: 100px;" onchange="fillthis('imunisasi_lainnya_frek_reaksi')"></td>
  </tr>
</table>
<!-- END RIWAYAT IMUNISASI -->

<hr>
<!-- AWAL RIWAYAT TUMBUH KEMBANG -->
<table class="table" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px; width:100%;">
  <tr>
    <td colspan="3" style="text-align: center; font-weight: bold; font-size: 14px; background-color: #f0f0f0;">
      RIWAYAT TUMBUH KEMBANG
    </td>
  </tr>

  <!-- Bagian A -->
  <tr>
    <td colspan="3" style="font-weight: bold; background-color: #fafafa;">A. PERTUMBUHAN FISIK</td>
  </tr>

  <tr>
    <td style="width: 5%; text-align: center; font-weight: bold;">No</td>
    <td style="width: 35%; font-weight: bold;">Aspek Pertumbuhan</td>
    <td style="width: 60%; font-weight: bold;">Keterangan</td>
  </tr>

  <tr>
    <td style="text-align: center;">1</td>
    <td>Berat Badan</td>
    <td><input type="text" class="input_type" name="form_115[berat_badan]" id="berat_badan" style="width: 100px;" onchange="fillthis('berat_badan')"> kg</td>
  </tr>

  <tr>
    <td style="text-align: center;">2</td>
    <td>Tinggi Badan</td>
    <td><input type="text" class="input_type" name="form_115[tinggi_badan]" id="tinggi_badan" style="width: 100px;" onchange="fillthis('tinggi_badan')"> cm</td>
  </tr>

  <tr>
    <td style="text-align: center;">3</td>
    <td>Waktu Tumbuh Gigi</td>
    <td><input type="text" class="input_type" name="form_115[waktu_tumbuh_gigi]" id="waktu_tumbuh_gigi" style="width: 250px;" onchange="fillthis('waktu_tumbuh_gigi')"></td>
  </tr>

  <tr>
    <td style="text-align: center;">4</td>
    <td>Gigi Tanggal</td>
    <td><input type="text" class="input_type" name="form_115[gigi_tanggal]" id="gigi_tanggal" style="width: 250px;" onchange="fillthis('gigi_tanggal')"></td>
  </tr>

  <tr>
    <td style="text-align: center;">5</td>
    <td>Jumlah Gigi</td>
    <td><input type="text" class="input_type" name="form_115[jumlah_gigi]" id="jumlah_gigi" style="width: 100px;" onchange="fillthis('jumlah_gigi')"> buah</td>
  </tr>

  <!-- Bagian B -->
  <tr>
    <td colspan="3" style="font-weight: bold; background-color: #fafafa;">B. PERKEMBANGAN TIAP TAHAP</td>
  </tr>

  <tr>
    <td colspan="3">Usia anak saat : </td>
  </tr>

  <tr>
    <td style="text-align: center;">1</td>
    <td>Berguling</td>
    <td><input type="text" class="input_type" name="form_115[berguling]" id="berguling" style="width: 100px;" onchange="fillthis('berguling')"> bulan</td>
  </tr>

  <tr>
    <td style="text-align: center;">2</td>
    <td>Duduk</td>
    <td><input type="text" class="input_type" name="form_115[duduk]" id="duduk" style="width: 100px;" onchange="fillthis('duduk')"> bulan</td>
  </tr>

  <tr>
    <td style="text-align: center;">3</td>
    <td>Merangkak</td>
    <td><input type="text" class="input_type" name="form_115[merangkak]" id="merangkak" style="width: 100px;" onchange="fillthis('merangkak')"> bulan</td>
  </tr>

  <tr>
    <td style="text-align: center;">4</td>
    <td>Berdiri</td>
    <td><input type="text" class="input_type" name="form_115[berdiri]" id="berdiri" style="width: 100px;" onchange="fillthis('berdiri')"> bulan</td>
  </tr>

  <tr>
    <td style="text-align: center;">5</td>
    <td>Berjalan</td>
    <td><input type="text" class="input_type" name="form_115[berjalan]" id="berjalan" style="width: 100px;" onchange="fillthis('berjalan')"> bulan</td>
  </tr>

  <tr>
    <td style="text-align: center;">6</td>
    <td>Senyum kepada orang lain pertama kali</td>
    <td><input type="text" class="input_type" name="form_115[senyum_pertama]" id="senyum_pertama" style="width: 100px;" onchange="fillthis('senyum_pertama')"> tahun</td>
  </tr>

  <tr>
    <td style="text-align: center;">7</td>
    <td>Bicara pertama kali</td>
    <td>
      <input type="text" class="input_type" name="form_115[bicara_pertama]" id="bicara_pertama" style="width: 100px;" onchange="fillthis('bicara_pertama')"> tahun, 
      dengan menyebutkan kata: 
      <input type="text" class="input_type" name="form_115[kata_pertama]" id="kata_pertama" style="width: 150px;" onchange="fillthis('kata_pertama')">
    </td>
  </tr>

  <tr>
    <td style="text-align: center;">8</td>
    <td>Berpakaian tanpa bantuan</td>
    <td><input type="text" class="input_type" name="form_115[berpakaian_sendiri]" id="berpakaian_sendiri" style="width: 100px;" onchange="fillthis('berpakaian_sendiri')"> tahun</td>
  </tr>
</table>
<!-- END RIWAYAT TUMBUH KEMBANG -->


<hr>
<!-- AWAL RIWAYAT NUTRISI -->
<table class="table" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px; width:100%;">
  <tr>
    <td colspan="3" style="text-align: center; font-weight: bold; font-size: 14px; background-color: #f0f0f0;">
      RIWAYAT NUTRISI
    </td>
  </tr>

  <!-- Bagian A -->
  <tr>
    <td colspan="3" style="font-weight: bold; background-color: #fafafa;">A. PEMBERIAN ASI</td>
  </tr>

  <tr>
    <td style="width: 5%; text-align: center; font-weight: bold;">No</td>
    <td style="width: 35%; font-weight: bold;">Aspek</td>
    <td style="width: 60%; font-weight: bold;">Keterangan</td>
  </tr>

  <tr>
    <td style="text-align: center;">1</td>
    <td>ASI Eksklusif diberikan</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_115[asi_ya]" id="asi_ya" onclick="checkthis('asi_ya')"> <span class="lbl">Ya</span>
      </label>
      &nbsp;&nbsp;
      <label>
        <input type="checkbox" class="ace" name="form_115[asi_tidak]" id="asi_tidak" onclick="checkthis('asi_tidak')"> <span class="lbl">Tidak</span>
      </label>
      &nbsp;&nbsp;Alasannya:
      <input type="text" class="input_type" name="form_115[asi_alasan]" id="asi_alasan" style="width: 250px;" onchange="fillthis('asi_alasan')">
    </td>
  </tr>

  <!-- Bagian B -->
  <tr>
    <td colspan="3" style="font-weight: bold; background-color: #fafafa;">B. PEMBERIAN SUSU FORMULA</td>
  </tr>

  <tr>
    <td style="text-align: center;">1</td>
    <td>Alasan Pemberian</td>
    <td><input type="text" class="input_type" name="form_115[alasan_susu_formula]" id="alasan_susu_formula" style="width: 300px;" onchange="fillthis('alasan_susu_formula')"></td>
  </tr>

  <tr>
    <td style="text-align: center;">2</td>
    <td>Jumlah Pemberian</td>
    <td><input type="text" class="input_type" name="form_115[jumlah_susu_formula]" id="jumlah_susu_formula" style="width: 150px;" onchange="fillthis('jumlah_susu_formula')"></td>
  </tr>

  <tr>
    <td style="text-align: center;">3</td>
    <td>Cara Pemberian</td>
    <td><input type="text" class="input_type" name="form_115[cara_susu_formula]" id="cara_susu_formula" style="width: 250px;" onchange="fillthis('cara_susu_formula')"></td>
  </tr>

  <!-- Bagian Pola Perubahan Nutrisi -->
  <tr>
    <td colspan="3" style="font-weight: bold; background-color: #fafafa;">Pola Perubahan Nutrisi Tahap Usia sampai Nutrisi Saat Ini</td>
  </tr>

  <tr style="background-color: #d3d3d3; text-align: center; font-weight: bold;">
    <td style="width: 10%;">Usia</td>
    <td style="width: 45%;">Jenis Nutrisi</td>
    <td style="width: 45%;">Lama Pemberian</td>
  </tr>

  <tr>
    <td><input type="text" class="input_type" name="form_115[nutrisi_usia_1]" id="nutrisi_usia_1" style="width: 80px;" onchange="fillthis('nutrisi_usia_1')"></td>
    <td><input type="text" class="input_type" name="form_115[nutrisi_jenis_1]" id="nutrisi_jenis_1" style="width: 95%;" onchange="fillthis('nutrisi_jenis_1')"></td>
    <td><input type="text" class="input_type" name="form_115[nutrisi_lama_1]" id="nutrisi_lama_1" style="width: 95%;" onchange="fillthis('nutrisi_lama_1')"></td>
  </tr>

  <tr>
    <td><input type="text" class="input_type" name="form_115[nutrisi_usia_2]" id="nutrisi_usia_2" style="width: 80px;" onchange="fillthis('nutrisi_usia_2')"></td>
    <td><input type="text" class="input_type" name="form_115[nutrisi_jenis_2]" id="nutrisi_jenis_2" style="width: 95%;" onchange="fillthis('nutrisi_jenis_2')"></td>
    <td><input type="text" class="input_type" name="form_115[nutrisi_lama_2]" id="nutrisi_lama_2" style="width: 95%;" onchange="fillthis('nutrisi_lama_2')"></td>
  </tr>

  <tr>
    <td><input type="text" class="input_type" name="form_115[nutrisi_usia_3]" id="nutrisi_usia_3" style="width: 80px;" onchange="fillthis('nutrisi_usia_3')"></td>
    <td><input type="text" class="input_type" name="form_115[nutrisi_jenis_3]" id="nutrisi_jenis_3" style="width: 95%;" onchange="fillthis('nutrisi_jenis_3')"></td>
    <td><input type="text" class="input_type" name="form_115[nutrisi_lama_3]" id="nutrisi_lama_3" style="width: 95%;" onchange="fillthis('nutrisi_lama_3')"></td>
  </tr>

  <tr>
    <td><input type="text" class="input_type" name="form_115[nutrisi_usia_4]" id="nutrisi_usia_4" style="width: 80px;" onchange="fillthis('nutrisi_usia_4')"></td>
    <td><input type="text" class="input_type" name="form_115[nutrisi_jenis_4]" id="nutrisi_jenis_4" style="width: 95%;" onchange="fillthis('nutrisi_jenis_4')"></td>
    <td><input type="text" class="input_type" name="form_115[nutrisi_lama_4]" id="nutrisi_lama_4" style="width: 95%;" onchange="fillthis('nutrisi_lama_4')"></td>
  </tr>

  <tr>
    <td><input type="text" class="input_type" name="form_115[nutrisi_usia_5]" id="nutrisi_usia_5" style="width: 80px;" onchange="fillthis('nutrisi_usia_5')"></td>
    <td><input type="text" class="input_type" name="form_115[nutrisi_jenis_5]" id="nutrisi_jenis_5" style="width: 95%;" onchange="fillthis('nutrisi_jenis_5')"></td>
    <td><input type="text" class="input_type" name="form_115[nutrisi_lama_5]" id="nutrisi_lama_5" style="width: 95%;" onchange="fillthis('nutrisi_lama_5')"></td>
  </tr>
</table>
<!-- END RIWAYAT NUTRISI -->


<hr>
<!-- AWAL RIWAYAT PSIKOSOSIAL DAN EKONOMI -->
<table class="table">
  <tr>
    <td valign="center" style="text-align: center; font-weight: bold; font-size: 14px; background-color: #f0f0f0;" colspan="2">
      RIWAYAT PSIKOSOSIAL DAN EKONOMI
    </td>
  </tr>

  <tr>
    <td width="40%">a. Anak tinggal bersama</td>
    <td width="60%">
      <label><input type="checkbox" class="ace" name="form_115[tinggal_kedua_ortu]" id="tinggal_kedua_ortu" onclick="checkthis('tinggal_kedua_ortu')"> <span class="lbl"> Kedua orang tua</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[tinggal_ibu]" id="tinggal_ibu" onclick="checkthis('tinggal_ibu')"> <span class="lbl"> Ibu</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[tinggal_ayah]" id="tinggal_ayah" onclick="checkthis('tinggal_ayah')"> <span class="lbl"> Ayah</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[tinggal_wali]" id="tinggal_wali" onclick="checkthis('tinggal_wali')"> <span class="lbl"> Wali</span></label><br>
      Sebutkan: <input type="text" class="input_type" style="width: 250px !important" name="form_115[tinggal_sebutkan]" id="tinggal_sebutkan" onchange="fillthis('tinggal_sebutkan')">
    </td>
  </tr>

  <tr>
    <td width="40%">b. Lingkungan berada di</td>
    <td width="60%">
      <label><input type="checkbox" class="ace" name="form_115[lingkungan_perumahan]" id="lingkungan_perumahan" onclick="checkthis('lingkungan_perumahan')"> <span class="lbl"> Komplek perumahan</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[lingkungan_perkampungan]" id="lingkungan_perkampungan" onclick="checkthis('lingkungan_perkampungan')"> <span class="lbl"> Perkampungan</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[lingkungan_lainnya]" id="lingkungan_lainnya" onclick="checkthis('lingkungan_lainnya')"> <span class="lbl"> Lainnya</span></label><br>
      Sebutkan: <input type="text" class="input_type" style="width: 250px !important" name="form_115[lingkungan_sebutkan]" id="lingkungan_sebutkan" onchange="fillthis('lingkungan_sebutkan')">
    </td>
  </tr>

  <tr>
    <td width="40%">c. Tempat bermain anak</td>
    <td width="60%">
      <label><input type="checkbox" class="ace" name="form_115[bermain_dalam_rumah]" id="bermain_dalam_rumah" onclick="checkthis('bermain_dalam_rumah')"> <span class="lbl"> Di dalam rumah</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[bermain_tempat]" id="bermain_tempat" onclick="checkthis('bermain_tempat')"> <span class="lbl"> Tempat bermain</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[bermain_lainnya]" id="bermain_lainnya" onclick="checkthis('bermain_lainnya')"> <span class="lbl"> Lainnya</span></label><br>
      Sebutkan: <input type="text" class="input_type" style="width: 250px !important" name="form_115[bermain_sebutkan]" id="bermain_sebutkan" onchange="fillthis('bermain_sebutkan')">
    </td>
  </tr>

  <tr>
    <td width="40%">d. Rumah ada tangga</td>
    <td width="60%">
      <label><input type="checkbox" class="ace" name="form_115[tangga_ada]" id="tangga_ada" onclick="checkthis('tangga_ada')"> <span class="lbl"> Ada tangga</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[tangga_tidak]" id="tangga_tidak" onclick="checkthis('tangga_tidak')"> <span class="lbl"> Tidak ada tangga</span></label>
    </td>
  </tr>

  <tr>
    <td width="40%">e. Pengasuh anak</td>
    <td width="60%">
      <label><input type="checkbox" class="ace" name="form_115[pengasuh_ortu]" id="pengasuh_ortu" onclick="checkthis('pengasuh_ortu')"> <span class="lbl"> Orang tua kandung</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[pengasuh_babysitter]" id="pengasuh_babysitter" onclick="checkthis('pengasuh_babysitter')"> <span class="lbl"> Baby sister</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[pengasuh_lainnya]" id="pengasuh_lainnya" onclick="checkthis('pengasuh_lainnya')"> <span class="lbl"> Lainnya</span></label><br>
      Sebutkan: <input type="text" class="input_type" style="width: 250px !important" name="form_115[pengasuh_sebutkan]" id="pengasuh_sebutkan" onchange="fillthis('pengasuh_sebutkan')">
    </td>
  </tr>

  <tr>
    <td width="40%">f. Hubungan antar anggota keluarga</td>
    <td width="60%">
      <label><input type="checkbox" class="ace" name="form_115[hubungan_baik]" id="hubungan_baik" onclick="checkthis('hubungan_baik')"> <span class="lbl"> Baik</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[hubungan_bermasalah]" id="hubungan_bermasalah" onclick="checkthis('hubungan_bermasalah')"> <span class="lbl"> Bermasalah</span></label><br>
      Sebutkan: <input type="text" class="input_type" style="width: 250px !important" name="form_115[hubungan_sebutkan]" id="hubungan_sebutkan" onchange="fillthis('hubungan_sebutkan')">
    </td>
  </tr>
</table>
<!-- END RIWAYAT PSIKOSOSIAL DAN EKONOMI -->

<hr>
<!-- AWAL RIWAYAT SPIRITUAL DAN KULTURAL -->
<table class="table">
  <tr>
    <td valign="center" style="text-align: center; font-weight: bold; font-size: 14px; background-color: #f0f0f0;" colspan="2">
      RIWAYAT SPIRITUAL DAN KULTURAL
    </td>
  </tr>

  <tr>
    <td width="40%">a. Agama anak</td>
    <td width="60%">
      <label><input type="checkbox" class="ace" name="form_115[agama_islam]" id="agama_islam" onclick="checkthis('agama_islam')"> <span class="lbl"> Islam</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[agama_kristen]" id="agama_kristen" onclick="checkthis('agama_kristen')"> <span class="lbl"> Kristen</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[agama_katolik]" id="agama_katolik" onclick="checkthis('agama_katolik')"> <span class="lbl"> Katolik</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[agama_hindu]" id="agama_hindu" onclick="checkthis('agama_hindu')"> <span class="lbl"> Hindu</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[agama_budha]" id="agama_budha" onclick="checkthis('agama_budha')"> <span class="lbl"> Budha</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[agama_lainnya]" id="agama_lainnya" onclick="checkthis('agama_lainnya')"> <span class="lbl"> Lainnya</span></label><br>
      Sebutkan: <input type="text" class="input_type" style="width: 250px !important" name="form_115[agama_sebutkan]" id="agama_sebutkan" onchange="fillthis('agama_sebutkan')">
    </td>
  </tr>

  <tr>
    <td width="40%">b. Kegiatan keagamaan</td>
    <td width="60%">
      <label><input type="checkbox" class="ace" name="form_115[ibadah_rutin]" id="ibadah_rutin" onclick="checkthis('ibadah_rutin')"> <span class="lbl"> Rutin beribadah</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[ibadah_tidak_rutin]" id="ibadah_tidak_rutin" onclick="checkthis('ibadah_tidak_rutin')"> <span class="lbl"> Tidak rutin beribadah</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[ibadah_tidak_ada]" id="ibadah_tidak_ada" onclick="checkthis('ibadah_tidak_ada')"> <span class="lbl"> Tidak ada</span></label>
    </td>
  </tr>

  <tr>
    <td width="40%">c. Nilai-nilai yang diyakini</td>
    <td width="60%">
      Pantangan, jelaskan: 
      <input type="text" class="input_type" style="width: 300px !important" name="form_115[nilai_pantangan]" id="nilai_pantangan" onchange="fillthis('nilai_pantangan')"><br><br>
      Tradisi, jelaskan:
      <input type="text" class="input_type" style="width: 300px !important" name="form_115[nilai_tradisi]" id="nilai_tradisi" onchange="fillthis('nilai_tradisi')">
    </td>
  </tr>
</table>
<!-- END RIWAYAT SPIRITUAL DAN KULTURAL -->

<hr>
<!-- AWAL REAKSI HOSPITALISASI -->
<table class="table">
  <tr>
    <td valign="center" style="text-align: center; font-weight: bold; font-size: 14px; background-color: #f0f0f0;" colspan="2">
      REAKSI HOSPITALISASI
    </td>
  </tr>

  <tr>
    <td colspan="2" style="font-weight: bold;">A. Pengalaman keluarga tentang sakit dan rawat inap:</td>
  </tr>

  <tr>
    <td width="45%">1. Apakah alasan ibu membawa anaknya ke RS</td>
    <td width="55%"><input type="text" class="input_type" style="width: 300px !important" name="form_115[alasan_ke_rs]" id="alasan_ke_rs" onchange="fillthis('alasan_ke_rs')"></td>
  </tr>

  <tr>
    <td>2. Apakah dokter menceritakan tentang kondisi anak</td>
    <td><input type="text" class="input_type" style="width: 300px !important" name="form_115[dokter_menjelaskan]" id="dokter_menjelaskan" onchange="fillthis('dokter_menjelaskan')"></td>
  </tr>

  <tr>
    <td>3. Bagaimana perasaan orang tua saat ini</td>
    <td><input type="text" class="input_type" style="width: 300px !important" name="form_115[perasaan_ortu]" id="perasaan_ortu" onchange="fillthis('perasaan_ortu')"></td>
  </tr>

  <tr>
    <td>4. Apakah orang tua selalu berkunjung ke RS</td>
    <td><input type="text" class="input_type" style="width: 300px !important" name="form_115[kunjungan_ortu]" id="kunjungan_ortu" onchange="fillthis('kunjungan_ortu')"></td>
  </tr>

  <tr>
    <td>5. Siapakah yang akan tinggal dengan anak</td>
    <td><input type="text" class="input_type" style="width: 300px !important" name="form_115[tinggal_dengan]" id="tinggal_dengan" onchange="fillthis('tinggal_dengan')"></td>
  </tr>

  <tr>
    <td style="font-weight: bold;">B. Pemahaman anak tentang sakit dan rawat inap</td>
    <td><input type="text" class="input_type" style="width: 350px !important" name="form_115[pemahaman_anak]" id="pemahaman_anak" onchange="fillthis('pemahaman_anak')"></td>
  </tr>
</table>


<!-- END REAKSI HOSPITALISASI -->



<hr>
<!-- AWAL AKTIFITAS SEHARI-HARI -->
<table class="table" border="1" cellspacing="0" cellpadding="4" 
  style="border-collapse: collapse; font-size: 13px; width:100%;">
  <tr>
    <td colspan="4" style="text-align: center; font-weight: bold; font-size: 14px; background-color: #f0f0f0;">
      AKTIFITAS SEHARI - HARI
    </td>
  </tr>
  <tr style="text-align: center; font-weight: bold;">
    <td style="width: 5%;">No</td>
    <td style="width: 35%;">Kondisi</td>
    <td style="width: 30%;">Sebelum Sakit</td>
    <td style="width: 30%;">Saat Sakit</td>
  </tr>

  <!-- A. NUTRISI -->
  <tr style="background-color: #f0f0f0; text-align:center;">
    <th colspan="4" style="font-weight: bold;">A. NUTRISI</th>
  </tr>
  
  <tr>
    <td style="text-align: center;">1</td>
    <td>Selera Makan</td>
    <td><input type="text" class="input_type" name="form_115[nutrisi_selera_sebelum]" id="nutrisi_selera_sebelum" style="width: 100%;" onchange="fillthis('nutrisi_selera_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[nutrisi_selera_sakit]" id="nutrisi_selera_sakit" style="width: 100%;" onchange="fillthis('nutrisi_selera_sakit')"></td>
  </tr>

  <!-- B. CAIRAN -->
  <tr style="background-color: #f0f0f0; text-align:center;">
    <th colspan="4" style="font-weight: bold;">B. CAIRAN</th>
  </tr>

  <tr>
    <td style="text-align: center;">1</td>
    <td>Jenis Minuman</td>
    <td><input type="text" class="input_type" name="form_115[cairan_jenis_sebelum]" id="cairan_jenis_sebelum" style="width: 100%;" onchange="fillthis('cairan_jenis_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[cairan_jenis_sakit]" id="cairan_jenis_sakit" style="width: 100%;" onchange="fillthis('cairan_jenis_sakit')"></td>
  </tr>
  <tr>
    <td style="text-align: center;">2</td>
    <td>Frekuensi Minuman</td>
    <td><input type="text" class="input_type" name="form_115[cairan_frek_sebelum]" id="cairan_frek_sebelum" style="width: 100%;" onchange="fillthis('cairan_frek_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[cairan_frek_sakit]" id="cairan_frek_sakit" style="width: 100%;" onchange="fillthis('cairan_frek_sakit')"></td>
  </tr>
  <tr>
    <td style="text-align: center;">3</td>
    <td>Kebutuhan Cairan</td>
    <td><input type="text" class="input_type" name="form_115[cairan_kebutuhan_sebelum]" id="cairan_kebutuhan_sebelum" style="width: 100%;" onchange="fillthis('cairan_kebutuhan_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[cairan_kebutuhan_sakit]" id="cairan_kebutuhan_sakit" style="width: 100%;" onchange="fillthis('cairan_kebutuhan_sakit')"></td>
  </tr>
  <tr>
    <td style="text-align: center;">4</td>
    <td>Cara Pemenuhan</td>
    <td><input type="text" class="input_type" name="form_115[cairan_pemenuhan_sebelum]" id="cairan_pemenuhan_sebelum" style="width: 100%;" onchange="fillthis('cairan_pemenuhan_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[cairan_pemenuhan_sakit]" id="cairan_pemenuhan_sakit" style="width: 100%;" onchange="fillthis('cairan_pemenuhan_sakit')"></td>
  </tr>

  <!-- C. ELIMINASI (BAB & BAK) -->
  <tr style="background-color: #f0f0f0; text-align:center;">
    <th colspan="4" style="font-weight: bold;">C. ELIMINASI (BAB & BAK)</th>
  </tr>

  <tr>
    <td style="text-align: center;">1</td>
    <td>Tempat Pembuangan</td>
    <td><input type="text" class="input_type" name="form_115[elim_tempat_sebelum]" id="elim_tempat_sebelum" style="width: 100%;" onchange="fillthis('elim_tempat_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[elim_tempat_sakit]" id="elim_tempat_sakit" style="width: 100%;" onchange="fillthis('elim_tempat_sakit')"></td>
  </tr>
  <tr>
    <td style="text-align: center;">2</td>
    <td>Frekuensi (Waktu)</td>
    <td><input type="text" class="input_type" name="form_115[elim_frek_sebelum]" id="elim_frek_sebelum" style="width: 100%;" onchange="fillthis('elim_frek_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[elim_frek_sakit]" id="elim_frek_sakit" style="width: 100%;" onchange="fillthis('elim_frek_sakit')"></td>
  </tr>
  <tr>
    <td style="text-align: center;">3</td>
    <td>Konsistensi</td>
    <td><input type="text" class="input_type" name="form_115[elim_konsistensi_sebelum]" id="elim_konsistensi_sebelum" style="width: 100%;" onchange="fillthis('elim_konsistensi_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[elim_konsistensi_sakit]" id="elim_konsistensi_sakit" style="width: 100%;" onchange="fillthis('elim_konsistensi_sakit')"></td>
  </tr>
  <tr>
    <td style="text-align: center;">4</td>
    <td>Kesulitan</td>
    <td><input type="text" class="input_type" name="form_115[elim_sulit_sebelum]" id="elim_sulit_sebelum" style="width: 100%;" onchange="fillthis('elim_sulit_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[elim_sulit_sakit]" id="elim_sulit_sakit" style="width: 100%;" onchange="fillthis('elim_sulit_sakit')"></td>
  </tr>
  <tr>
    <td style="text-align: center;">5</td>
    <td>Obat Pencahar</td>
    <td><input type="text" class="input_type" name="form_115[elim_pencahar_sebelum]" id="elim_pencahar_sebelum" style="width: 100%;" onchange="fillthis('elim_pencahar_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[elim_pencahar_sakit]" id="elim_pencahar_sakit" style="width: 100%;" onchange="fillthis('elim_pencahar_sakit')"></td>
  </tr>
  
  <!--- D --->
  <tr style="background-color: #f0f0f0; text-align:center;">
    <th colspan="4" style="font-weight: bold;">D. ISTIRAHAT TIDUR</th>
  </tr>

  <tr>
    <td style="text-align: center;">1</td>
    <td>Jam tidur (Siang / Malam)</td>
    <td><input type="text" class="input_type" name="form_115[tidur_jam_sebelum]" id="tidur_jam_sebelum" style="width: 100%;" onchange="fillthis('tidur_jam_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[tidur_jam_sakit]" id="tidur_jam_sakit" style="width: 100%;" onchange="fillthis('tidur_jam_sakit')"></td>
  </tr>

  <tr>
    <td style="text-align: center;">2</td>
    <td>Pola tidur</td>
    <td><input type="text" class="input_type" name="form_115[tidur_pola_sebelum]" id="tidur_pola_sebelum" style="width: 100%;" onchange="fillthis('tidur_pola_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[tidur_pola_sakit]" id="tidur_pola_sakit" style="width: 100%;" onchange="fillthis('tidur_pola_sakit')"></td>
  </tr>

  <tr>
    <td style="text-align: center;">3</td>
    <td>Kebiasaan sebelum tidur</td>
    <td><input type="text" class="input_type" name="form_115[tidur_kebiasaan_sebelum]" id="tidur_kebiasaan_sebelum" style="width: 100%;" onchange="fillthis('tidur_kebiasaan_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[tidur_kebiasaan_sakit]" id="tidur_kebiasaan_sakit" style="width: 100%;" onchange="fillthis('tidur_kebiasaan_sakit')"></td>
  </tr>

  <tr>
    <td style="text-align: center;">4</td>
    <td>Kesulitan tidur</td>
    <td><input type="text" class="input_type" name="form_115[tidur_sulit_sebelum]" id="tidur_sulit_sebelum" style="width: 100%;" onchange="fillthis('tidur_sulit_sebelum')"></td>
    <td><input type="text" class="input_type" name="form_115[tidur_sulit_sakit]" id="tidur_sulit_sakit" style="width: 100%;" onchange="fillthis('tidur_sulit_sakit')"></td>
  </tr>

<!-- E. OLAHRAGA -->
<tr style="background-color: #f0f0f0; text-align:center;">
  <th colspan="4" style="font-weight: bold;">E. OLAHRAGA</th>
</tr>

<tr>
  <td align="center">1</td>
  <td>Program olahraga</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[olahraga_program_sebelum]" id="olahraga_program_sebelum" onchange="fillthis('olahraga_program_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[olahraga_program_sakit]" id="olahraga_program_sakit" onchange="fillthis('olahraga_program_sakit')">
  </td>
</tr>
<tr>
  <td align="center">2</td>
  <td>Jenis dan frekuensi</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[olahraga_jenis_sebelum]" id="olahraga_jenis_sebelum" onchange="fillthis('olahraga_jenis_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[olahraga_jenis_sakit]" id="olahraga_jenis_sakit" onchange="fillthis('olahraga_jenis_sakit')">
  </td>
</tr>
<tr>
  <td align="center">3</td>
  <td>Kondisi setelah olahraga</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[olahraga_kondisi_sebelum]" id="olahraga_kondisi_sebelum" onchange="fillthis('olahraga_kondisi_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[olahraga_kondisi_sakit]" id="olahraga_kondisi_sakit" onchange="fillthis('olahraga_kondisi_sakit')">
  </td>
</tr>


<!-- F. AKTIFITAS FISIK -->
<tr style="background-color: #f0f0f0; text-align:center;">
  <th colspan="4" style="font-weight: bold;">F. AKTIFITAS FISIK</th>
</tr>

<tr>
  <td align="center">1</td>
  <td>Kegiatan sehari-hari</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[fisik_kegiatan_sebelum]" id="fisik_kegiatan_sebelum" onchange="fillthis('fisik_kegiatan_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[fisik_kegiatan_sakit]" id="fisik_kegiatan_sakit" onchange="fillthis('fisik_kegiatan_sakit')">
  </td>
</tr>
<tr>
  <td align="center">2</td>
  <td>Pengaturan jadwal</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[fisik_jadwal_sebelum]" id="fisik_jadwal_sebelum" onchange="fillthis('fisik_jadwal_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[fisik_jadwal_sakit]" id="fisik_jadwal_sakit" onchange="fillthis('fisik_jadwal_sakit')">
  </td>
</tr>
<tr>
  <td align="center">3</td>
  <td>Penggunaan alat bantu aktifitas</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[fisik_bantu_sebelum]" id="fisik_bantu_sebelum" onchange="fillthis('fisik_bantu_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[fisik_bantu_sakit]" id="fisik_bantu_sakit" onchange="fillthis('fisik_bantu_sakit')">
  </td>
</tr>
<tr>
  <td align="center">4</td>
  <td>Kesulitan pergerakan tubuh</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[fisik_sulit_sebelum]" id="fisik_sulit_sebelum" onchange="fillthis('fisik_sulit_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[fisik_sulit_sakit]" id="fisik_sulit_sakit" onchange="fillthis('fisik_sulit_sakit')">
  </td>
</tr>

<!-- G. PERSONAL HYGIENE -->
<tr style="background-color: #f0f0f0; text-align:center;">
  <th colspan="4" style="font-weight: bold;">G. PERSONAL HYGIENE</th>
</tr>

<tr>
  <td align="center">1</td>
  <td>Mandi (cara / frekuensi / alat mandi)</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[hygiene_mandi_sebelum]" id="hygiene_mandi_sebelum" onchange="fillthis('hygiene_mandi_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[hygiene_mandi_sakit]" id="hygiene_mandi_sakit" onchange="fillthis('hygiene_mandi_sakit')">
  </td>
</tr>
<tr>
  <td align="center">2</td>
  <td>Cuci rambut (cara / frekuensi)</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[hygiene_rambut_sebelum]" id="hygiene_rambut_sebelum" onchange="fillthis('hygiene_rambut_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[hygiene_rambut_sakit]" id="hygiene_rambut_sakit" onchange="fillthis('hygiene_rambut_sakit')">
  </td>
</tr>
<tr>
  <td align="center">3</td>
  <td>Gunting kuku (cara / frekuensi)</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[hygiene_kuku_sebelum]" id="hygiene_kuku_sebelum" onchange="fillthis('hygiene_kuku_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[hygiene_kuku_sakit]" id="hygiene_kuku_sakit" onchange="fillthis('hygiene_kuku_sakit')">
  </td>
</tr>
<tr>
  <td align="center">4</td>
  <td>Gosok gigi (cara / frekuensi)</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[hygiene_gigi_sebelum]" id="hygiene_gigi_sebelum" onchange="fillthis('hygiene_gigi_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[hygiene_gigi_sakit]" id="hygiene_gigi_sakit" onchange="fillthis('hygiene_gigi_sakit')">
  </td>
</tr>

<!-- H. REKREASI -->
<tr style="background-color: #f0f0f0; text-align:center;">
  <th colspan="4" style="font-weight: bold;">H. REKREASI</th>
</tr>

<tr>
  <td align="center">1</td>
  <td>Perasaan saat sekolah</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[rekreasi_perasaan_sekolah_sebelum]" id="rekreasi_perasaan_sekolah_sebelum" onchange="fillthis('rekreasi_perasaan_sekolah_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[rekreasi_perasaan_sekolah_sakit]" id="rekreasi_perasaan_sekolah_sakit" onchange="fillthis('rekreasi_perasaan_sekolah_sakit')">
  </td>
</tr>
<tr>
  <td align="center">2</td>
  <td>Waktu luang</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[rekreasi_waktu_luang_sebelum]" id="rekreasi_waktu_luang_sebelum" onchange="fillthis('rekreasi_waktu_luang_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[rekreasi_waktu_luang_sakit]" id="rekreasi_waktu_luang_sakit" onchange="fillthis('rekreasi_waktu_luang_sakit')">
  </td>
</tr>
<tr>
  <td align="center">3</td>
  <td>Perasaan setelah rekreasi</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[rekreasi_perasaan_setelah_sebelum]" id="rekreasi_perasaan_setelah_sebelum" onchange="fillthis('rekreasi_perasaan_setelah_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[rekreasi_perasaan_setelah_sakit]" id="rekreasi_perasaan_setelah_sakit" onchange="fillthis('rekreasi_perasaan_setelah_sakit')">
  </td>
</tr>
<tr>
  <td align="center">4</td>
  <td>Waktu senggang keluarga</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[rekreasi_keluarga_sebelum]" id="rekreasi_keluarga_sebelum" onchange="fillthis('rekreasi_keluarga_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[rekreasi_keluarga_sakit]" id="rekreasi_keluarga_sakit" onchange="fillthis('rekreasi_keluarga_sakit')">
  </td>
</tr>
<tr>
  <td align="center">5</td>
  <td>Kegiatan hari libur</td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[rekreasi_libur_sebelum]" id="rekreasi_libur_sebelum" onchange="fillthis('rekreasi_libur_sebelum')">
  </td>
  <td>
    <input type="text" class="input_type" style="width:250px !important" name="form_115[rekreasi_libur_sakit]" id="rekreasi_libur_sakit" onchange="fillthis('rekreasi_libur_sakit')">
  </td>
</tr>

</table>
<!-- END AKTIFITAS SEHARI-HARI -->



<!-- PEMERIKSAAN FISIK -->
<table class="table" border="1" cellspacing="0" cellpadding="4" 
  style="border-collapse: collapse; font-size: 13px; width:100%;">

  <!-- Judul -->
  <tr>
    <td colspan="6" style="text-align:center; font-weight:bold; font-size:14px; background-color:#f0f0f0;">
      PEMERIKSAAN FISIK
    </td>
  </tr>

  <!-- KEADAAN UMUM -->
  <tr>
    <td colspan="6" style="font-weight:bold;">Keadaan Umum</td>
  </tr>

  <tr>
    <td style="width:20%;">Kesadaran</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[kesadaran_compos]" id="kesadaran_compos" onclick="checkthis('kesadaran_compos')"> <span class="lbl"> Compos mentis</span>
      <input type="checkbox" class="ace" name="form_115[kesadaran_apatis]" id="kesadaran_apatis" onclick="checkthis('kesadaran_apatis')"> <span class="lbl"> Apatis</span>
      <input type="checkbox" class="ace" name="form_115[kesadaran_somnolen]" id="kesadaran_somnolen" onclick="checkthis('kesadaran_somnolen')"> <span class="lbl"> Somnolen</span>
    </td>
  </tr>

  <tr>
    <td>GCS</td>
    <td colspan="5">
      E <input type="text" class="input_type" style="width: 40px;" name="form_115[gcs_e]" id="gcs_e" onchange="fillthis('gcs_e')">
      M <input type="text" class="input_type" style="width: 40px;" name="form_115[gcs_m]" id="gcs_m" onchange="fillthis('gcs_m')">
      V <input type="text" class="input_type" style="width: 40px;" name="form_115[gcs_v]" id="gcs_v" onchange="fillthis('gcs_v')">
    </td>
  </tr>

  <tr>
    <td>Tekanan darah</td>
    <td><input type="text" class="input_type" style="width: 80px;" name="form_115[td]" id="td" onchange="fillthis('td')"> mmHg</td>
    <td>Berat badan</td>
    <td><input type="text" class="input_type" style="width: 80px;" name="form_115[bb]" id="bb" onchange="fillthis('bb')"> kg</td>
    <td>Tinggi badan</td>
    <td><input type="text" class="input_type" style="width: 80px;" name="form_115[tb]" id="tb" onchange="fillthis('tb')"> cm</td>
  </tr>

  <tr>
    <td>Frekuensi nadi</td>
    <td><input type="text" class="input_type" style="width: 80px;" name="form_115[nadi]" id="nadi" onchange="fillthis('nadi')"> x/menit</td>
    <td>Suhu</td>
    <td><input type="text" class="input_type" style="width: 80px;" name="form_115[suhu]" id="suhu" onchange="fillthis('suhu')"> °C</td>
    <td>Frekuensi nafas</td>
    <td><input type="text" class="input_type" style="width: 80px;" name="form_115[nafas]" id="nafas" onchange="fillthis('nafas')"> x/menit</td>
  </tr>

  <tr>
    <td>Lingkar kepala</td>
    <td colspan="5"><input type="text" class="input_type" style="width: 80px;" name="form_115[lingkar_kepala]" id="lingkar_kepala" onchange="fillthis('lingkar_kepala')"> cm</td>
  </tr>

  <!-- KEPALA -->
  <tr>
    <td colspan="6" style="font-weight:bold;">Kepala</td>
  </tr>

  <tr>
    <td>Gambaran wajah</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[wajah_simetris]" id="wajah_simetris" onclick="checkthis('wajah_simetris')"> <span class="lbl"> Simetris</span>
      <input type="checkbox" class="ace" name="form_115[wajah_asimetris]" id="wajah_asimetris" onclick="checkthis('wajah_asimetris')"> <span class="lbl"> Asimetris</span>
    </td>
  </tr>

  <tr>
    <td>Nyeri tekan</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[nyeri_ada]" id="nyeri_ada" onclick="checkthis('nyeri_ada')"> <span class="lbl"> Ada</span>
      <input type="checkbox" class="ace" name="form_115[nyeri_tidak]" id="nyeri_tidak" onclick="checkthis('nyeri_tidak')"> <span class="lbl"> Tidak ada</span>
    </td>
  </tr>

  <tr>
    <td>Benjolan</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[benjolan_ada]" id="benjolan_ada" onclick="checkthis('benjolan_ada')"> <span class="lbl"> Ada</span>
      <input type="checkbox" class="ace" name="form_115[benjolan_tidak]" id="benjolan_tidak" onclick="checkthis('benjolan_tidak')"> <span class="lbl"> Tidak ada</span>
    </td>
  </tr>

  <tr>
    <td>Mata</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[mata_normal]" id="mata_normal" onclick="checkthis('mata_normal')"> <span class="lbl"> Normal</span>
      <input type="checkbox" class="ace" name="form_115[mata_abnormal]" id="mata_abnormal" onclick="checkthis('mata_abnormal')"> <span class="lbl"> Abnormal</span>
      <input type="checkbox" class="ace" name="form_115[mata_sklera]" id="mata_sklera" onclick="checkthis('mata_sklera')"> <span class="lbl"> Sklera Ikterik</span>
      <input type="checkbox" class="ace" name="form_115[mata_konjungtiva]" id="mata_konjungtiva" onclick="checkthis('mata_konjungtiva')"> <span class="lbl"> Konjungtiva Anemis</span>
      <input type="checkbox" class="ace" name="form_115[mata_pupil]" id="mata_pupil" onclick="checkthis('mata_pupil')"> <span class="lbl"> Pupil Anisokor</span>
      <br><input type="checkbox" class="ace" name="form_115[mata_lainnya]" id="mata_lainnya" onclick="checkthis('mata_lainnya')"> <span class="lbl"> Lainnya,</span> Sebutkan:
      <input type="text" class="input_type" style="width: 200px;" name="form_115[mata_lainnya_text]" id="mata_lainnya_text" onchange="fillthis('mata_lainnya_text')">
    </td>
  </tr>

  <tr>
    <td>Hidung</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[hidung_simetris]" id="hidung_simetris" onclick="checkthis('hidung_simetris')"> <span class="lbl"> Simetris</span>
      <input type="checkbox" class="ace" name="form_115[hidung_asimetris]" id="hidung_asimetris" onclick="checkthis('hidung_asimetris')"> <span class="lbl"> Asimetris</span>
    </td>
  </tr>

  <tr>
    <td>Sekret</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[hidung_sekret_tidak]" id="hidung_sekret_tidak" onclick="checkthis('hidung_sekret_tidak')"> <span class="lbl"> Tidak ada</span>
      <input type="checkbox" class="ace" name="form_115[hidung_sekret_ada]" id="hidung_sekret_ada" onclick="checkthis('hidung_sekret_ada')"> <span class="lbl"> Ada, </span> Sebutkan:
      <input type="text" class="input_type" style="width: 200px;" name="form_115[hidung_sekret_text]" id="hidung_sekret_text" onchange="fillthis('hidung_sekret_text')">
    </td>
  </tr>

  <tr>
    <td>Mulut</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[mulut_normal]" id="mulut_normal" onclick="checkthis('mulut_normal')"> <span class="lbl"> Normal</span>
      <input type="checkbox" class="ace" name="form_115[mulut_abnormal]" id="mulut_abnormal" onclick="checkthis('mulut_abnormal')"> <span class="lbl"> Abnormal</span>
    </td>
  </tr>

  <tr>
    <td>Telinga</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[telinga_normal]" id="telinga_normal" onclick="checkthis('telinga_normal')"> <span class="lbl"> Normal</span>
      <input type="checkbox" class="ace" name="form_115[telinga_abnormal]" id="telinga_abnormal" onclick="checkthis('telinga_abnormal')"> <span class="lbl"> Abnormal</span>
    </td>
  </tr>

    <!-- DADA / PERNAFASAN -->
  <tr>
    <td colspan="6" style="font-weight:bold;">Dada / Pernafasan</td>
  </tr>

  <tr>
    <td>Bentuk dada</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[dada_simetris]" id="dada_simetris" onclick="checkthis('dada_simetris')"> <span class="lbl"> Simetris</span>
      <input type="checkbox" class="ace" name="form_115[dada_asimetris]" id="dada_asimetris" onclick="checkthis('dada_asimetris')"> <span class="lbl"> Asimetris</span>
    </td>
  </tr>

  <tr>
    <td>Sumbatan jalan nafas</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[sumbatan_tidak]" id="sumbatan_tidak" onclick="checkthis('sumbatan_tidak')"> <span class="lbl"> Tidak ada</span>
      <input type="checkbox" class="ace" name="form_115[sumbatan_ada]" id="sumbatan_ada" onclick="checkthis('sumbatan_ada')"> <span class="lbl"> Ada</span>
    </td>
  </tr>

  <tr>
    <td>Nafas cuping hidung</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[cuping_tidak]" id="cuping_tidak" onclick="checkthis('cuping_tidak')"> <span class="lbl"> Tidak</span>
      <input type="checkbox" class="ace" name="form_115[cuping_ya]" id="cuping_ya" onclick="checkthis('cuping_ya')"> <span class="lbl"> Ya, frekuensi</span>
      <input type="text" class="input_type" style="width: 100px;" name="form_115[cuping_frekuensi]" id="cuping_frekuensi" onchange="fillthis('cuping_frekuensi')"> x/menit
    </td>
  </tr>

  <tr>
    <td>Batuk</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[batuk_tidak]" id="batuk_tidak" onclick="checkthis('batuk_tidak')"> <span class="lbl"> Tidak</span>
      <input type="checkbox" class="ace" name="form_115[batuk_ya]" id="batuk_ya" onclick="checkthis('batuk_ya')"> <span class="lbl"> Ya</span>
      <input type="checkbox" class="ace" name="form_115[batuk_produktif]" id="batuk_produktif" onclick="checkthis('batuk_produktif')"> <span class="lbl"> Produktif</span>
      <input type="checkbox" class="ace" name="form_115[batuk_tidak_produktif]" id="batuk_tidak_produktif" onclick="checkthis('batuk_tidak_produktif')"> <span class="lbl"> Tidak Produktif</span>
    </td>
  </tr>

  <tr>
    <td>Sputum</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[sputum_tidak]" id="sputum_tidak" onclick="checkthis('sputum_tidak')"> <span class="lbl"> Tidak ada</span>
      <input type="checkbox" class="ace" name="form_115[sputum_ada]" id="sputum_ada" onclick="checkthis('sputum_ada')"> <span class="lbl"> Ada, warna</span>
      <input type="text" class="input_type" style="width: 150px;" name="form_115[sputum_warna]" id="sputum_warna" onchange="fillthis('sputum_warna')">
    </td>
  </tr>

    <!-- JANTUNG / KARDIOVASKULER -->
  <tr>
    <td colspan="6" style="font-weight:bold;">Jantung / Kardiovaskuler</td>
  </tr>

  <tr>
    <td>Irama nadi</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[irama_teratur]" id="irama_teratur" onclick="checkthis('irama_teratur')"> <span class="lbl"> Teratur</span>
      <input type="checkbox" class="ace" name="form_115[irama_tidak_teratur]" id="irama_tidak_teratur" onclick="checkthis('irama_tidak_teratur')"> <span class="lbl"> Tidak teratur</span>
    </td>
  </tr>

  <tr>
    <td>Denyut nadi</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[denyut_lemah]" id="denyut_lemah" onclick="checkthis('denyut_lemah')"> <span class="lbl"> Lemah</span>
      <input type="checkbox" class="ace" name="form_115[denyut_kuat]" id="denyut_kuat" onclick="checkthis('denyut_kuat')"> <span class="lbl"> Kuat</span>
    </td>
  </tr>

  <tr>
    <td>Akral</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[akral_hangat]" id="akral_hangat" onclick="checkthis('akral_hangat')"> <span class="lbl"> Hangat</span>
      <input type="checkbox" class="ace" name="form_115[akral_dingin]" id="akral_dingin" onclick="checkthis('akral_dingin')"> <span class="lbl"> Dingin</span>
      <input type="checkbox" class="ace" name="form_115[akral_pucat]" id="akral_pucat" onclick="checkthis('akral_pucat')"> <span class="lbl"> Pucat</span>
      <input type="checkbox" class="ace" name="form_115[akral_kemerahan]" id="akral_kemerahan" onclick="checkthis('akral_kemerahan')"> <span class="lbl"> Kemerahan</span>
      <input type="checkbox" class="ace" name="form_115[akral_sianosis]" id="akral_sianosis" onclick="checkthis('akral_sianosis')"> <span class="lbl"> Sianosis</span>
    </td>
  </tr>

  <tr>
    <td>Edema</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[edema_muka]" id="edema_muka" onclick="checkthis('edema_muka')"> <span class="lbl"> Muka</span>
      <input type="checkbox" class="ace" name="form_115[edema_palpebra]" id="edema_palpebra" onclick="checkthis('edema_palpebra')"> <span class="lbl"> Palpebra</span>
      <input type="checkbox" class="ace" name="form_115[edema_tungkai_atas]" id="edema_tungkai_atas" onclick="checkthis('edema_tungkai_atas')"> <span class="lbl"> Tungkai atas</span>
      <input type="checkbox" class="ace" name="form_115[edema_tungkai_bawah]" id="edema_tungkai_bawah" onclick="checkthis('edema_tungkai_bawah')"> <span class="lbl"> Tungkai bawah</span>
      <input type="checkbox" class="ace" name="form_115[edema_seluruh_tubuh]" id="edema_seluruh_tubuh" onclick="checkthis('edema_seluruh_tubuh')"> <span class="lbl"> Seluruh tubuh</span>
    </td>
  </tr>

    <!-- ABDOMEN -->
  <tr>
    <td colspan="6" style="font-weight:bold;">Abdomen</td>
  </tr>

  <tr>
    <td>Nyeri tekan</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[abdomen_nyeri_tidak]" id="abdomen_nyeri_tidak" onclick="checkthis('abdomen_nyeri_tidak')"> <span class="lbl"> Tidak ada</span>
      <input type="checkbox" class="ace" name="form_115[abdomen_nyeri_ada]" id="abdomen_nyeri_ada" onclick="checkthis('abdomen_nyeri_ada')"> <span class="lbl"> Ada</span>
    </td>
  </tr>

  <tr>
    <td>Umbilikus</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[umbilikus_basah]" id="umbilikus_basah" onclick="checkthis('umbilikus_basah')"> <span class="lbl"> Basah</span>
      <input type="checkbox" class="ace" name="form_115[umbilikus_kering]" id="umbilikus_kering" onclick="checkthis('umbilikus_kering')"> <span class="lbl"> Kering</span>
      <input type="checkbox" class="ace" name="form_115[umbilikus_bau]" id="umbilikus_bau" onclick="checkthis('umbilikus_bau')"> <span class="lbl"> Bau</span>
      <input type="checkbox" class="ace" name="form_115[umbilikus_warna]" id="umbilikus_warna" onclick="checkthis('umbilikus_warna')"> <span class="lbl"> Warna, sebutkan</span> :
      <input type="text" class="input_type" style="width: 150px;" name="form_115[umbilikus_warna_text]" id="umbilikus_warna_text" onchange="fillthis('umbilikus_warna_text')">
    </td>
  </tr>

  <!-- KULIT -->
  <tr>
    <td colspan="6" style="font-weight:bold;">Kulit</td>
  </tr>

  <tr>
    <td>Warna kulit</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[kulit_normal]" id="kulit_normal" onclick="checkthis('kulit_normal')"> <span class="lbl"> Normal</span>
      <input type="checkbox" class="ace" name="form_115[kulit_kemerahan]" id="kulit_kemerahan" onclick="checkthis('kulit_kemerahan')"> <span class="lbl"> Kemerahan</span>
      <input type="checkbox" class="ace" name="form_115[kulit_pucat]" id="kulit_pucat" onclick="checkthis('kulit_pucat')"> <span class="lbl"> Pucat</span>
      <input type="checkbox" class="ace" name="form_115[kulit_ikterik]" id="kulit_ikterik" onclick="checkthis('kulit_ikterik')"> <span class="lbl"> Ikterik</span>
      <input type="checkbox" class="ace" name="form_115[kulit_lain]" id="kulit_lain" onclick="checkthis('kulit_lain')"> <span class="lbl"> Lain-lain</span>
    </td>
  </tr>

  <tr>
    <td>Turgor kulit</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[turgor_elastis]" id="turgor_elastis" onclick="checkthis('turgor_elastis')"> <span class="lbl"> Elastis</span>
      <input type="checkbox" class="ace" name="form_115[turgor_tidak_elastis]" id="turgor_tidak_elastis" onclick="checkthis('turgor_tidak_elastis')"> <span class="lbl"> Tidak elastis</span>
    </td>
  </tr>

  <tr>
    <td>Integritas kulit</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[kulit_luka]" id="kulit_luka" onclick="checkthis('kulit_luka')"> <span class="lbl"> Luka / lecet</span>
      <input type="checkbox" class="ace" name="form_115[kulit_kemerahan]" id="kulit_kemerahan2" onclick="checkthis('kulit_kemerahan2')"> <span class="lbl"> Kemerahan</span>
      <input type="checkbox" class="ace" name="form_115[kulit_melepuh]" id="kulit_melepuh" onclick="checkthis('kulit_melepuh')"> <span class="lbl"> Melepuh</span>
      <input type="checkbox" class="ace" name="form_115[kulit_memar]" id="kulit_memar" onclick="checkthis('kulit_memar')"> <span class="lbl"> Memar</span>
      <input type="checkbox" class="ace" name="form_115[kulit_kering]" id="kulit_kering" onclick="checkthis('kulit_kering')"> <span class="lbl"> Kering</span>
      <input type="checkbox" class="ace" name="form_115[kulit_bersisik]" id="kulit_bersisik" onclick="checkthis('kulit_bersisik')"> <span class="lbl"> Bersisik</span>
    </td>
  </tr>

  <tr>
    <td>Lokasi</td>
    <td colspan="5">
      <input type="text" class="input_type" style="width: 300px;" name="form_115[kulit_lokasi]" id="kulit_lokasi" onchange="fillthis('kulit_lokasi')">
    </td>
  </tr>

    <!-- ELIMINASI -->
  <tr>
    <td colspan="6" style="font-weight:bold;">Eliminasi</td>
  </tr>

  <tr>
    <td>Pola BAB</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[bab_konstipasi]" id="bab_konstipasi" onclick="checkthis('bab_konstipasi')"> <span class="lbl"> Konstipasi</span>
      <input type="checkbox" class="ace" name="form_115[bab_diare]" id="bab_diare" onclick="checkthis('bab_diare')"> <span class="lbl"> Diare</span>
      <input type="checkbox" class="ace" name="form_115[bab_melena]" id="bab_melena" onclick="checkthis('bab_melena')"> <span class="lbl"> Melena</span>
      <input type="checkbox" class="ace" name="form_115[bab_normal]" id="bab_normal" onclick="checkthis('bab_normal')"> <span class="lbl"> Tidak ada kelainan</span>
    </td>
  </tr>

  <tr>
    <td>Keterangan BAB</td>
    <td colspan="5">
      Konsistensi: 
      <input type="text" class="input_type" style="width: 120px;" name="form_115[bab_konsistensi]" id="bab_konsistensi" onchange="fillthis('bab_konsistensi')">
      &nbsp;&nbsp;Warna: 
      <input type="text" class="input_type" style="width: 120px;" name="form_115[bab_warna]" id="bab_warna" onchange="fillthis('bab_warna')">
      &nbsp;&nbsp;Bising usus: 
      <input type="text" class="input_type" style="width: 120px;" name="form_115[bising_usus]" id="bising_usus" onchange="fillthis('bising_usus')">
    </td>
  </tr>

  <tr>
    <td>Pola BAK</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[bak_hematuri]" id="bak_hematuri" onclick="checkthis('bak_hematuri')"> <span class="lbl"> Hematuri</span>
      <input type="checkbox" class="ace" name="form_115[bak_poliuri]" id="bak_poliuri" onclick="checkthis('bak_poliuri')"> <span class="lbl"> Poliuri</span>
      <input type="checkbox" class="ace" name="form_115[bak_oliguria]" id="bak_oliguria" onclick="checkthis('bak_oliguria')"> <span class="lbl"> Oliguria</span>
      <input type="checkbox" class="ace" name="form_115[bak_disuria]" id="bak_disuria" onclick="checkthis('bak_disuria')"> <span class="lbl"> Disuria</span>
      <input type="checkbox" class="ace" name="form_115[bak_inkontinen]" id="bak_inkontinen" onclick="checkthis('bak_inkontinen')"> <span class="lbl"> Inkontinen</span>
      <input type="checkbox" class="ace" name="form_115[bak_kateter]" id="bak_kateter" onclick="checkthis('bak_kateter')"> <span class="lbl"> Kateter</span>
      <input type="checkbox" class="ace" name="form_115[bak_retensi]" id="bak_retensi" onclick="checkthis('bak_retensi')"> <span class="lbl"> Retensi</span>
      <input type="checkbox" class="ace" name="form_115[bak_normal]" id="bak_normal" onclick="checkthis('bak_normal')"> <span class="lbl"> Tidak ada kelainan</span>
    </td>
  </tr>

  <tr>
    <td>Frekuensi / Warna BAK</td>
    <td colspan="5">
      Frekuensi: 
      <input type="text" class="input_type" style="width: 80px;" name="form_115[bak_frekuensi]" id="bak_frekuensi" onchange="fillthis('bak_frekuensi')"> x/hari
      &nbsp;&nbsp;Warna: 
      <input type="text" class="input_type" style="width: 120px;" name="form_115[bak_warna]" id="bak_warna" onchange="fillthis('bak_warna')">
    </td>
  </tr>

    <!-- GENITALIA & MUSKULOSKELETAL -->
  <tr>
    <td colspan="6" style="font-weight:bold;">Genitalia & Muskuloskeletal</td>
  </tr>

  <tr>
    <td>Genitalia</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[genitalia_normal]" id="genitalia_normal" onclick="checkthis('genitalia_normal')"> <span class="lbl"> Normal</span>
      <input type="checkbox" class="ace" name="form_115[genitalia_abnormal]" id="genitalia_abnormal" onclick="checkthis('genitalia_abnormal')"> <span class="lbl"> Abnormal, sebutkan</span>
      <input type="text" class="input_type" style="width: 200px;" name="form_115[ket_genitalia]" id="ket_genitalia" onchange="fillthis('ket_genitalia')">
    </td>
  </tr>

  <tr>
    <td>Muskuloskeletal</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[muskuloskeletal_normal]" id="muskuloskeletal_normal" onclick="checkthis('muskuloskeletal_normal')"> <span class="lbl"> Normal</span>
      <input type="checkbox" class="ace" name="form_115[muskuloskeletal_abnormal]" id="muskuloskeletal_abnormal" onclick="checkthis('muskuloskeletal_abnormal')"> <span class="lbl"> Abnormal, sebutkan</span>
      <input type="text" class="input_type" style="width: 200px;" name="form_115[ket_muskuloskeletal]" id="ket_muskuloskeletal" onchange="fillthis('ket_muskuloskeletal')">
    </td>
  </tr>

  <!-- DISABILITY -->
  <tr>
    <td colspan="6" style="font-weight:bold;">Disability</td>
  </tr>

  <tr>
    <td>Tanda trauma</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[trauma_tidak]" id="trauma_tidak" onclick="checkthis('trauma_tidak')"> <span class="lbl"> Tidak</span>
      <input type="checkbox" class="ace" name="form_115[trauma_ya]" id="trauma_ya" onclick="checkthis('trauma_ya')"> <span class="lbl"> Ya, sebutkan</span>
      <input type="text" class="input_type" style="width: 200px;" name="form_115[ket_trauma]" id="ket_trauma" onchange="fillthis('ket_trauma')">
    </td>
  </tr>

  <tr>
    <td>Kemampuan pergerakan</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[gerak_baik]" id="gerak_baik" onclick="checkthis('gerak_baik')"> <span class="lbl"> Baik</span>
      <input type="checkbox" class="ace" name="form_115[gerak_cukup]" id="gerak_cukup" onclick="checkthis('gerak_cukup')"> <span class="lbl"> Cukup</span>
      <input type="checkbox" class="ace" name="form_115[gerak_kurang]" id="gerak_kurang" onclick="checkthis('gerak_kurang')"> <span class="lbl"> Kurang</span>
    </td>
  </tr>

  <tr>
    <td>Tangisan</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[tangis_keras]" id="tangis_keras" onclick="checkthis('tangis_keras')"> <span class="lbl"> Keras</span>
      <input type="checkbox" class="ace" name="form_115[tangis_lemah]" id="tangis_lemah" onclick="checkthis('tangis_lemah')"> <span class="lbl"> Lemah</span>
      <input type="checkbox" class="ace" name="form_115[tangis_tidak]" id="tangis_tidak" onclick="checkthis('tangis_tidak')"> <span class="lbl"> Tidak menangis</span>
    </td>
  </tr>

  <tr>
    <td>Alergi / Reaksi</td>
    <td colspan="5">
      <input type="checkbox" class="ace" name="form_115[alergi_tidak]" id="alergi_tidak" onclick="checkthis('alergi_tidak')"> <span class="lbl"> Tidak ada alergi</span><br>

      <input type="checkbox" class="ace" name="form_115[alergi_obat]" id="alergi_obat" onclick="checkthis('alergi_obat')"> <span class="lbl"> Alergi obat</span>,
      sebutkan <input type="text" class="input_type" style="width: 150px;" name="form_115[ket_alergi_obat]" id="ket_alergi_obat" onchange="fillthis('ket_alergi_obat')">
      &nbsp;Reaksi <input type="text" class="input_type" style="width: 150px;" name="form_115[reaksi_obat]" id="reaksi_obat" onchange="fillthis('reaksi_obat')"><br>

      <input type="checkbox" class="ace" name="form_115[alergi_makanan]" id="alergi_makanan" onclick="checkthis('alergi_makanan')"> <span class="lbl"> Alergi makanan</span>,
      sebutkan <input type="text" class="input_type" style="width: 150px;" name="form_115[ket_alergi_makanan]" id="ket_alergi_makanan" onchange="fillthis('ket_alergi_makanan')">
      &nbsp;Reaksi <input type="text" class="input_type" style="width: 150px;" name="form_115[reaksi_makanan]" id="reaksi_makanan" onchange="fillthis('reaksi_makanan')"><br>

      <input type="checkbox" class="ace" name="form_115[alergi_lain]" id="alergi_lain" onclick="checkthis('alergi_lain')"> <span class="lbl"> Alergi lainnya</span>,
      sebutkan <input type="text" class="input_type" style="width: 150px;" name="form_115[ket_alergi_lain]" id="ket_alergi_lain" onchange="fillthis('ket_alergi_lain')">
      &nbsp;Reaksi <input type="text" class="input_type" style="width: 150px;" name="form_115[reaksi_lain]" id="reaksi_lain" onchange="fillthis('reaksi_lain')"><br>
    
      <input type="checkbox" class="ace" name="form_115[gelang_terpasang]" id="gelang_terpasang" onclick="checkthis('gelang_terpasang')"> <span class="lbl"> Gelang tanda alergi Pasang</span><br>
      <input type="checkbox" class="ace" name="form_115[gelang_tidak_diketahui]" id="gelang_tidak_diketahui" onclick="checkthis('gelang_tidak_diketahui')"> <span class="lbl"> Tidak diketahui</span><br>
    </td>
    </td>
  </tr>
</table>
<!--END PEMERIKSAAN FISIK-->



<!-- PENGKAJIAN KEBUTUHAN INFORMASI DAN EDUKASI-->
<table class="table" border="1" cellspacing="0" cellpadding="4" 
  style="border-collapse: collapse; font-size: 13px; width:100%;">
  
<tr>
  <td colspan="6" style="text-align:center; font-weight:bold; font-size:14px; background-color:#f0f0f0;">PENGKAJIAN KEBUTUHAN INFORMASI DAN EDUKASI</td>
</tr>

<!-- PERSIAPAN -->
<tr>
  <td colspan="6" style="font-weight:bold;">PERSIAPAN</td>
</tr>

<tr>
  <td>Bahasa</td>
  <td colspan="5">
    <input type="checkbox" class="ace" name="form_115[bahasa_indonesia]" id="bahasa_indonesia" onclick="checkthis('bahasa_indonesia')"> <span class="lbl"> Indonesia</span>
    <input type="checkbox" class="ace" name="form_115[bahasa_inggris]" id="bahasa_inggris" onclick="checkthis('bahasa_inggris')"> <span class="lbl"> Inggris</span>
    <input type="checkbox" class="ace" name="form_115[bahasa_daerah]" id="bahasa_daerah" onclick="checkthis('bahasa_daerah')"> <span class="lbl"> Daerah</span>
    <input type="text" class="input_type" style="width: 150px;" name="form_115[ket_bahasa_daerah]" id="ket_bahasa_daerah" onchange="fillthis('ket_bahasa_daerah')">
    &nbsp;&nbsp;Lain-lain:
    <input type="text" class="input_type" style="width: 150px;" name="form_115[bahasa_lain]" id="bahasa_lain" onchange="fillthis('bahasa_lain')">
  </td>
</tr>

<tr>
  <td>Kebutuhan penerjemah</td>
  <td colspan="5">
    <input type="checkbox" class="ace" name="form_115[penerjemah_ya]" id="penerjemah_ya" onclick="checkthis('penerjemah_ya')"> <span class="lbl"> Ya</span>
    <input type="checkbox" class="ace" name="form_115[penerjemah_tidak]" id="penerjemah_tidak" onclick="checkthis('penerjemah_tidak')"> <span class="lbl"> Tidak</span>
  </td>
</tr>

<tr>
  <td>Pendidikan keluarga</td>
  <td colspan="5">
    <input type="checkbox" class="ace" name="form_115[pendidikan_sd]" id="pendidikan_sd" onclick="checkthis('pendidikan_sd')"> <span class="lbl"> SD</span>
    <input type="checkbox" class="ace" name="form_115[pendidikan_smp]" id="pendidikan_smp" onclick="checkthis('pendidikan_smp')"> <span class="lbl"> SMP</span>
    <input type="checkbox" class="ace" name="form_115[pendidikan_slta]" id="pendidikan_slta" onclick="checkthis('pendidikan_slta')"> <span class="lbl"> SLTA</span>
    <input type="checkbox" class="ace" name="form_115[pendidikan_s1]" id="pendidikan_s1" onclick="checkthis('pendidikan_s1')"> <span class="lbl"> S-1</span>
    <input type="checkbox" class="ace" name="form_115[pendidikan_lain]" id="pendidikan_lain" onclick="checkthis('pendidikan_lain')"> <span class="lbl"> Lain-lain</span>
    <input type="text" class="input_type" style="width: 150px;" name="form_115[ket_pendidikan_lain]" id="ket_pendidikan_lain" onchange="fillthis('ket_pendidikan_lain')">
  </td>
</tr>

<tr>
  <td>Baca dan tulis</td>
  <td colspan="5">
    <input type="checkbox" class="ace" name="form_115[bacatulis_baik]" id="bacatulis_baik" onclick="checkthis('bacatulis_baik')"> <span class="lbl"> Baik</span>
    <input type="checkbox" class="ace" name="form_115[bacatulis_kurang]" id="bacatulis_kurang" onclick="checkthis('bacatulis_kurang')"> <span class="lbl"> Kurang</span>
  </td>
</tr>

<tr>
  <td>Pilih cara belajar</td>
  <td colspan="5">
    <input type="checkbox" class="ace" name="form_115[belajar_verbal]" id="belajar_verbal" onclick="checkthis('belajar_verbal')"> <span class="lbl"> Verbal</span>
    <input type="checkbox" class="ace" name="form_115[belajar_tulisan]" id="belajar_tulisan" onclick="checkthis('belajar_tulisan')"> <span class="lbl"> Tulisan</span>
  </td>
</tr>

<tr>
  <td>Budaya / Suku / Etnis</td>
  <td colspan="5">
    <input type="text" class="input_type" style="width: 250px;" name="form_115[budaya_suku]" id="budaya_suku" onchange="fillthis('budaya_suku')">
  </td>
</tr>

<!-- HAMBATAN -->
<tr>
  <td colspan="6" style="font-weight:bold;">HAMBATAN</td>
</tr>

<tr>
  <td>Hambatan belajar</td>
  <td colspan="5">
    <input type="checkbox" class="ace" name="form_115[hambatan_tidak]" id="hambatan_tidak" onclick="checkthis('hambatan_tidak')"> <span class="lbl"> Tidak ada</span>
    <input type="checkbox" class="ace" name="form_115[hambatan_emosional]" id="hambatan_emosional" onclick="checkthis('hambatan_emosional')"> <span class="lbl"> Emosional</span>
    <input type="checkbox" class="ace" name="form_115[hambatan_kognitif]" id="hambatan_kognitif" onclick="checkthis('hambatan_kognitif')"> <span class="lbl"> Kognitif terbatas</span>
    <input type="checkbox" class="ace" name="form_115[hambatan_penglihatan]" id="hambatan_penglihatan" onclick="checkthis('hambatan_penglihatan')"> <span class="lbl"> Penglihatan terganggu</span><br>
    <input type="checkbox" class="ace" name="form_115[hambatan_bahasa]" id="hambatan_bahasa" onclick="checkthis('hambatan_bahasa')"> <span class="lbl"> Bahasa</span>
    <input type="checkbox" class="ace" name="form_115[hambatan_fisik]" id="hambatan_fisik" onclick="checkthis('hambatan_fisik')"> <span class="lbl"> Fisik lemah</span>
    <input type="checkbox" class="ace" name="form_115[hambatan_budaya]" id="hambatan_budaya" onclick="checkthis('hambatan_budaya')"> <span class="lbl"> Budaya / Agama / Spiritual</span>
    <input type="checkbox" class="ace" name="form_115[hambatan_pendengaran]" id="hambatan_pendengaran" onclick="checkthis('hambatan_pendengaran')"> <span class="lbl"> Pendengaran terganggu</span><br>
    <input type="checkbox" class="ace" name="form_115[hambatan_bicara]" id="hambatan_bicara" onclick="checkthis('hambatan_bicara')"> <span class="lbl"> Gangguan bicara</span>
    <input type="checkbox" class="ace" name="form_115[hambatan_motivasi]" id="hambatan_motivasi" onclick="checkthis('hambatan_motivasi')"> <span class="lbl"> Motivasi kurang</span>
    <input type="checkbox" class="ace" name="form_115[hambatan_keyakinan]" id="hambatan_keyakinan" onclick="checkthis('hambatan_keyakinan')"> <span class="lbl"> Keyakinan / Mitos</span>
    <input type="checkbox" class="ace" name="form_115[hambatan_lain]" id="hambatan_lain" onclick="checkthis('hambatan_lain')"> <span class="lbl"> Lain-lain:</span>
    <input type="text" class="input_type" style="width: 150px;" name="form_115[ket_hambatan_lain]" id="ket_hambatan_lain" onchange="fillthis('ket_hambatan_lain')">
  </td>
</tr>

<!-- KEBUTUHAN -->
<tr>
  <td colspan="6" style="font-weight:bold;">KEBUTUHAN</td>
</tr>

<tr>
  <td>Topik pembelajaran</td>
  <td colspan="5">
    <input type="checkbox" class="ace" name="form_115[kebutuhan_penyakit]" id="kebutuhan_penyakit" onclick="checkthis('kebutuhan_penyakit')"> <span class="lbl"> Proses penyakit</span>
    <input type="checkbox" class="ace" name="form_115[kebutuhan_pencegahan]" id="kebutuhan_pencegahan" onclick="checkthis('kebutuhan_pencegahan')"> <span class="lbl"> Pencegahan faktor risiko</span><br>
    <input type="checkbox" class="ace" name="form_115[kebutuhan_prosedur]" id="kebutuhan_prosedur" onclick="checkthis('kebutuhan_prosedur')"> <span class="lbl"> Prosedur (contoh: cara perawatan luka)</span>
    <input type="checkbox" class="ace" name="form_115[kebutuhan_obat]" id="kebutuhan_obat" onclick="checkthis('kebutuhan_obat')"> <span class="lbl"> Obat-obatan</span>
    <input type="checkbox" class="ace" name="form_115[kebutuhan_nyeri]" id="kebutuhan_nyeri" onclick="checkthis('kebutuhan_nyeri')"> <span class="lbl"> Manajemen nyeri</span><br>
    <input type="checkbox" class="ace" name="form_115[kebutuhan_lingkungan]" id="kebutuhan_lingkungan" onclick="checkthis('kebutuhan_lingkungan')"> <span class="lbl"> Lingkungan yang perlu disiapkan pasca rawat</span>
    <input type="checkbox" class="ace" name="form_115[kebutuhan_diet]" id="kebutuhan_diet" onclick="checkthis('kebutuhan_diet')"> <span class="lbl"> Diet dan nutrisi</span>
    <input type="checkbox" class="ace" name="form_115[kebutuhan_rehabilitasi]" id="kebutuhan_rehabilitasi" onclick="checkthis('kebutuhan_rehabilitasi')"> <span class="lbl"> Rehabilitasi</span><br>
    <input type="checkbox" class="ace" name="form_115[kebutuhan_lain]" id="kebutuhan_lain" onclick="checkthis('kebutuhan_lain')"> <span class="lbl"> Lain-lain:</span>
    <input type="text" class="input_type" style="width: 200px;" name="form_115[ket_kebutuhan_lain]" id="ket_kebutuhan_lain" onchange="fillthis('ket_kebutuhan_lain')">
  </td>
</tr>

</table>
<!--END-->

<!-- SKRINING NUTRISI BERDASARKAN STRONG -->
<table class="table" border="1" cellspacing="2" cellpadding="4" 
  style="border-collapse: collapse; font-size: 13px; width:100%;">
  <tr>
    <!-- Kolom kiri -->
    <td style="vertical-align: top; width: 65%;">
    <!-- <td style="vertical-align: top; width: 35%; padding-left:10px;"> -->
      <table border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; width:100%;">

        <tr style="background-color:#f0f0f0; font-weight:bold; text-align:center;">
          <td colspan="3">SKRINING NUTRISI BERDASARKAN STRONG</td>
        </tr>

        <tr style="background-color:#d3d3d3; font-weight:bold; text-align:center;">
          <td style="width:5%;">No</td>
          <td style="width:70%;">PARAMETER</td>
          <td style="width:25%;">SKOR (0,1)</td>
        </tr>

        <!-- 1 -->
        <tr>
          <td style="text-align:center;">1</td>
          <td>Apakah pasien tampak kurus?
            <br>
            <input type="checkbox" class="ace" name="form_115[nutrisi_kurus_tidak]" id="nutrisi_kurus_tidak" onclick="checkthis('nutrisi_kurus_tidak')"> <span class="lbl"> a. Tidak</span>
            <br>
            <input type="checkbox" class="ace" name="form_115[nutrisi_kurus_ya]" id="nutrisi_kurus_ya" onclick="checkthis('nutrisi_kurus_ya')"> <span class="lbl"> b. Ya</span>
          </td>
          <td>
            <input type="text" style="width: 200px; text-align:center;" class="input_type" name="form_115[ket_nutrisi_kurus]" id="ket_nutrisi_kurus" onchange="fillthis('ket_nutrisi_kurus')">
          </td>  
        </tr>

          <!-- <td>
            <label>
              <input type="radio" class="ace" name="form_115[nutrisi_kurus_0]" id="nutrisi_kurus_0" value="0" onclick="hitungTotal('nutrisi_kurus_0')"> 
              <span class="lbl"> Tidak (0)</span>
            </label><br>
            <label>
              <input type="radio" class="ace" name="form_115[nutrisi_kurus_1]" id="nutrisi_kurus_1" value="1" onclick="hitungTotal('nutrisi_kurus_1')"> 
              </span>
              <span class="lbl"> Ya (1)</span>
            </label>
          </td> -->
        

        <!-- 1 -->
        <!-- <tr>
          <td style="text-align:center;">1</td>
          <td>Apakah pasien tampak kurus?</td>
          <td>
            <label>
              <input type="radio" class="ace" name="form_115[nutrisi_kurus_0]" id="nutrisi_kurus_0" value="0" onclick="hitungTotal('nutrisi_kurus_0')"> 
              <span class="lbl"> Tidak (0)</span>
            </label><br>
            <label>
              <input type="radio" class="ace" name="form_115[nutrisi_kurus_1]" id="nutrisi_kurus_1" value="1" onclick="hitungTotal('nutrisi_kurus_1')"> 
              </span>
              <span class="lbl"> Ya (1)</span>
              <input type="radio" class="ace" name="form_115[nutrisi_kurus_2]" id="nutrisi_kurus_2" onclick="checkthis('nutrisi_kurus_2')"> <span class="lbl"> Ya (2)</span>
            </label>
          </td>
        </tr> -->

        <!-- 2 -->
        <tr>
          <td style="text-align:center;">2</td>
          <td>
            Apakah terdapat penurunan BB selama satu bulan terakhir?<br>
            <small>(Berdasarkan data objektif BB bila ada ATAU penilaian subjektif orang tua pasien / bayi &lt;1 tahun: BB tidak naik selama 3 bulan)</small>
            <br>
            <input type="checkbox" class="ace" name="form_115[nutrisi_bb_0]" id="nutrisi_bb_0" onclick="checkthis('nutrisi_bb_0')"> <span class="lbl"> a. Tidak</span>
            <br>
            <input type="checkbox" class="ace" name="form_115[nutrisi_bb_1]" id="nutrisi_bb_1" onclick="checkthis('nutrisi_bb_1')"> <span class="lbl"> b. Ya</span>
          </td>
          <td>
            <input type="text" style="width: 200px; text-align:center;" class="input_type" name="form_115[ket_nutrisi_bb]" id="ket_nutrisi_bb" onchange="fillthis('ket_nutrisi_bb')">
          </td> 
          <!-- <td>
            <label>
              <input type="radio" class="ace" name="form_115[nutrisi_bb]" id="nutrisi_bb_0" value="0" onclick="hitungTotal()"> 
              <span class="lbl"> Tidak (0)</span>
            </label><br>
            <label>
              <input type="radio" class="ace" name="form_115[nutrisi_bb]" id="nutrisi_bb_1" value="1" onclick="hitungTotal()"> 
              <span class="lbl"> Ya (1)</span>
            </label>
          </td> -->
        </tr>

        <!-- 3 -->
        <tr>
          <td style="text-align:center;">3</td>
          <td>
            Apakah terdapat salah satu kondisi berikut?<br>
            - Diare ≥ 5 kali / hari dan atau muntah &lt; 3 kali / hari dalam seminggu terakhir<br>
            - Asupan makanan berkurang selama 1 minggu terakhir
            <br>
            <input type="checkbox" class="ace" name="form_115[nutrisi_kondisi_0]" id="nutrisi_kondisi_0" onclick="checkthis('nutrisi_kondisi_0')"> <span class="lbl"> a. Tidak</span>
            <br>
            <input type="checkbox" class="ace" name="form_115[nutrisi_kondisi_1]" id="nutrisi_kondisi_1" onclick="checkthis('nutrisi_kondisi_1')"> <span class="lbl"> b. Ya</span>
          </td>
          <td>
            <input type="text" style="width: 200px; text-align:center;" class="input_type" name="form_115[ket_nutrisi_kondisi]" id="ket_nutrisi_kondisi" onchange="fillthis('ket_nutrisi_kondisi')">
          </td>
          <!-- <td>
            <label>
              <input type="radio" class="ace" name="form_115[nutrisi_kondisi]" id="nutrisi_kondisi_0" value="0" onclick="hitungTotal()"> 
              <span class="lbl"> Tidak (0)</span>
            </label><br>
            <label>
              <input type="radio" class="ace" name="form_115[nutrisi_kondisi]" id="nutrisi_kondisi_1" value="1" onclick="hitungTotal()"> 
              <span class="lbl"> Ya (1)</span>
            </label>
          </td> -->
        </tr>

        <!-- 4 -->
        <tr>
          <td style="text-align:center;">4</td>
          <td>
            Apakah terdapat penyakit atau keadaan yang mengakibatkan pasien berisiko mengalami malnutrisi?<br>
            <small>(lihat tabel di samping)</small>
            <br>
            <input type="checkbox" class="ace" name="form_115[nutrisi_penyakit_0]" id="nutrisi_penyakit_0" onclick="checkthis('nutrisi_penyakit_0')"> <span class="lbl"> a. Tidak</span>
            <br>
            <input type="checkbox" class="ace" name="form_115[nutrisi_penyakit_1]" id="nutrisi_penyakit_1" onclick="checkthis('nutrisi_penyakit_1')"> <span class="lbl"> b. Ya</span>
          </td>
          <td>
            <input type="text" style="width: 200px; text-align:center;" class="input_type" name="form_115[ket_nutrisi_penyakit]" id="ket_nutrisi_penyakit" onchange="fillthis('ket_nutrisi_penyakit')">
          </td>
          <!-- <td>
            <label>
              <input type="radio" class="ace" name="form_115[nutrisi_penyakit]" id="nutrisi_penyakit_0" value="0" onclick="hitungTotal()"> 
              <span class="lbl"> Tidak (0)</span>
            </label><br>
            <label>
              <input type="radio" class="ace" name="form_115[nutrisi_penyakit]" id="nutrisi_penyakit_1" value="1" onclick="hitungTotal()"> 
              <span class="lbl"> Ya (1)</span>
            </label>
          </td> -->
        </tr>

        <!-- Total -->
        <tr style="font-weight:bold;">
          <td colspan="2" style="text-align:right;">Total Skor</td>
          <td>
            <!-- <input type="text" class="input_type" style="width:60px; text-align:center;" name="form_115[nutrisi_total]" id="nutrisi_total" readonly> -->
            <input type="text" style="width: 200px; text-align:center;" class="input_type" name="form_115[total_skor_skrining]" id="total_skor_skrining" onchange="fillthis('total_skor_skrining')"> 
          </td>
        </tr>

        <!-- Interpretasi -->
        <!-- <tr>
          <td colspan="3">
            <b>Interpretasi:</b><br>
            0 = Risiko rendah &nbsp;&nbsp;&nbsp;
            1–3 = Risiko sedang &nbsp;&nbsp;&nbsp;
            4 = Risiko berat
          </td>
        </tr> -->
      </table>
      </td>

    <!-- Kolom kanan -->
    <td style="vertical-align: top; width: 35%; padding-left:10px;">
      <table border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; width:100%;">
        <tr style="background-color:#f0f0f0; font-weight:bold;">
          <td>Tabel 1. Daftar penyakit atau keadaan yang berisiko mengakibatkan malnutrisi</td>
        </tr>
        <tr>
          <td>
            - Diare kronik (&gt; 2 minggu)<br>
            - (Tersangka) Penyakit jantung bawaan<br>
            - (Tersangka) Kanker<br>
            - (Tersangka) HIV<br>
            - Penyakit hati kronik<br>
            - Penyakit ginjal kronik<br>
            - TB Paru<br>
            - Terpasang stoma<br>
            - Luka bakar luas<br>
            - Kelainan anatomi mulut<br>
            - Rencana / pasca operasi mayor<br>
            - Trauma<br>
            - Kelainan metabolik bawaan<br>
            - Keterlambatan perkembangan<br>
            - Retardasi mental<br>
            - Lain-lain (sesuai diagnosis dokter)
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<!-- <script>
function hitungTotal() {
  let total = 0;
  const fields = ['nutrisi_kurus', 'nutrisi_bb', 'nutrisi_kondisi', 'nutrisi_penyakit'];
  fields.forEach(f => {
    const val = document.querySelector(`input[name='form_115[${f}]']:checked`);
    if (val) total += parseInt(val.value);
  });
  document.getElementById('nutrisi_total').value = total;
}
</script>
 -->

<!-- Script Hitung Otomatis -->
<!--script>
function hitungTotal() {
  const fields = ['nutrisi_kurus', 'nutrisi_bb', 'nutrisi_kondisi', 'nutrisi_penyakit'];
  let total = 0;

  fields.forEach(f => {
    const val = document.querySelector(`input[name="form_115[${f}]"]:checked`);
    if (val) total += parseInt(val.value);
  });

  document.getElementById('nutrisi_total').value = total;

  document.getElementById('nutrisi_rendah').checked = false;
  document.getElementById('nutrisi_sedang').checked = false;
  document.getElementById('nutrisi_berat').checked = false;

  if (total === 0) {
    document.getElementById('nutrisi_rendah').checked = true;
  } else if (total >= 1 && total <= 3) {
    document.getElementById('nutrisi_sedang').checked = true;
  } else if (total >= 4) {
    document.getElementById('nutrisi_berat').checked = true;
  }
}
</script> -->

<!-- END -->


<!-- SKRINING NYERI -->
<div style="font-size:13px; margin-top:10px; text-align: center;">
  <b>SKRINING NYERI</b> 
  <span style="font-style: italic;">(Pilih salah satu penilaian yang sesuai usia pasien)</span>
</div>

<input type="checkbox" class="ace" name="form_115[flacc_sale_anak]" id="flacc_sale_anak" onclick="checkthis('flacc_sale_anak')">
<span class="lbl"><b>FLACC SCALE </b>(Anak-anak < 3 tahun dan / atau belum bisa bicara)</span>

<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse; font-size: 13px;">
  <thead>
    <tr style="background-color: #d3d3d3;">
      <th style="width: 60%; text-align: center;">KATEGORI</th>
      <th style="width: 20%; text-align: center;">SKOR</th>
      <th style="width: 20%; text-align: center;">HASIL</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><b>Wajah</b><br>
        - Tidak ada ekspresi khusus (seperti senyum)<br>
        - Kadang meringis atau mengerutkan dahi, menarik diri<br>
        - Sering / terus-menerus mengerutkan dahi / rahang mengatup, dagu bergetar
      </td>
      <td style="text-align:center;">0 / 1 / 2</td>
      <td style="text-align:center;">
        <input type="text" class="input_type" name="form_115[nyeri_wajah]" id="nyeri_wajah" style="width: 50px; text-align:center;" onchange="fillthis('nyeri_wajah')">
      </td>
    </tr>

    <tr>
      <td><b>Ekstremitas</b><br>
        - Posisi normal / rileks<br>
        - Tidak tenang, gelisah, tegang<br>
        - Menendang / menarik diri
      </td>
      <td style="text-align:center;">0 / 1 / 2</td>
      <td style="text-align:center;">
        <input type="text" class="input_type" name="form_115[nyeri_ekstremitas]" id="nyeri_ekstremitas" style="width: 50px; text-align:center;" onchange="fillthis('nyeri_ekstremitas')">
      </td>
    </tr>

    <tr>
      <td><b>Gerakan</b><br>
        - Berbaring tenang, posisi normal, bergerak mudah<br>
        - Menggeliat-geliat, bolak-balik, berpindah, tegang<br>
        - Posisi tubuh meringkuk, kaku / spasme atau menyentak
      </td>
      <td style="text-align:center;">0 / 1 / 2</td>
      <td style="text-align:center;">
        <input type="text" class="input_type" name="form_115[nyeri_gerakan]" id="nyeri_gerakan" style="width: 50px; text-align:center;" onchange="fillthis('nyeri_gerakan')">
      </td>
    </tr>

    <tr>
      <td><b>Menangis</b><br>
        - Tidak menangis<br>
        - Merengek, merintih, kadang mengeluh<br>
        - Menangis tersedu-sedu, terisak-isak, menjerit
      </td>
      <td style="text-align:center;">0 / 1 / 2</td>
      <td style="text-align:center;">
        <input type="text" class="input_type" name="form_115[nyeri_menangis]" id="nyeri_menangis" style="width: 50px; text-align:center;" onchange="fillthis('nyeri_menangis')">
      </td>
    </tr>

    <tr>
      <td><b>Kemampuan Ditenangkan</b><br>
        - Tidak menangis<br>
        - Merengek, kadang mengeluh<br>
        - Menangis tersedu-sedu, terisak-isak, menjerit
      </td>
      <td style="text-align:center;">0 / 1 / 2</td>
      <td style="text-align:center;">
        <input type="text" class="input_type" name="form_115[nyeri_kemampuan_ditenangkan]" id="nyeri_kemampuan_ditenangkan" style="width: 50px; text-align:center;" onchange="fillthis('nyeri_kemampuan_ditenangkan')">
      </td>
    </tr>

    <tr style="background-color: #f5f5f5;">
      <td style="text-align:right;"><b>Total Skor :</b></td>
      <td colspan="2" style="text-align:center;">
        <input type="text" class="input_type" name="form_115[nyeri_total_skor]" id="nyeri_total_skor" style="width: 50px; text-align:center;" onchange="fillthis('nyeri_total_skor')">
        <!-- <input type="text" class="input_type" name="form_115[total_skor]" id="nyeri_total_skor" style="width:80px; text-align:center;" readonly> -->
      </td>
    </tr>

    <!-- <tr>
      <td colspan="3">
        <b>Interpretasi:</b><br>
        <span id="interpretasi_nyeri">-</span>
      </td>
    </tr> -->
  </tbody>
</table>

<!-- <script>
function hitungTotalNyeri() {
  // Ambil nilai dari semua input
  const wajah = parseInt(document.getElementById('nyeri_wajah').value) || 0;
  const ekstremitas = parseInt(document.getElementById('nyeri_ekstremitas').value) || 0;
  const gerakan = parseInt(document.getElementById('nyeri_gerakan').value) || 0;
  const menangis = parseInt(document.getElementById('nyeri_menangis').value) || 0;
  const kemampuan = parseInt(document.getElementById('nyeri_kemampuan_ditenangkan').value) || 0;

  // Hitung total
  const total = wajah + ekstremitas + gerakan + menangis + kemampuan;
  document.getElementById('nyeri_total_skor').value = total;

  // Interpretasi otomatis
  let interpretasi = '';
  if (total === 0) interpretasi = 'Tidak ada nyeri';
  else if (total >= 1 && total <= 3) interpretasi = 'Nyeri ringan';
  else if (total >= 4 && total <= 6) interpretasi = 'Nyeri sedang';
  else interpretasi = 'Nyeri berat';

  document.getElementById('interpretasi_nyeri').innerText = interpretasi;
}
</script> -->

<br>

<!-- WONG BAKER FACE PAIN SCALE -->
<table width="100%" border="1" cellspacing="0" cellpadding="4" 
       style="border-collapse:collapse; font-size:13px; margin-top:6px;">
  <tr style="background-color:#f2f2f2;">
    <th colspan="3" style="text-align:left;">
      <label>
        <input type="checkbox" class="ace" name="form_115[wong]" id="wong" onclick="checkthis('wong')">
        <span class="lbl"> <b>WONG BAKER FACE PAIN SCALE </b> (untuk pasien anak &gt; 3 tahun)</span>

      </label>
    </th>
  </tr>
  <tr>
    <td style="width:40%;">
      Berapa skala nyeri anda? (0–10)
    </td>
    <td style="width:20%; text-align:center;">
      <input type="text" name="form_115[wong_score]" class="input_type" id="wong_score" onchange="fillthis('wong_score')"
             style="width:80px; text-align:center;">
    </td>
    <td style="width:40%;">
      <span>Skala 0 = Tidak nyeri, 
        <br> Skala 2 = Sedikit nyeri
        <br> Skala 4 = Agak menggangu
        <br> Skala 6 = Menggangu aktifitas
        <br> Skala 8 = Sangat mengganggu
        <br> Skala 10 = Tak tertahankan</span>
    </td>
  </tr>
</table>

<br>

<!-- JENIS NYERI -->
<table width="100%" border="1" cellspacing="0" cellpadding="5" 
       style="border-collapse:collapse; font-size:13px;">
  <tr style="background-color:#f0f0f0; font-weight:bold;">
    <td colspan="4">JENIS NYERI</td>
  </tr>

  <tr>
    <td colspan="4">
      <label><input type="checkbox" class="ace" name="form_115[nyeri_tidak_ada]" onclick="checkthis('nyeri_tidak_ada')"  id="nyeri_tidak_ada"><span class="lbl"> Tidak ada nyeri</span></label>&nbsp;&nbsp;
      <label><input type="checkbox" class="ace" name="form_115[nyeri_kronis]" onclick="checkthis('nyeri_kronis')" id="nyeri_kronis"><span class="lbl"> Nyeri kronis</span></label>&nbsp;&nbsp;
      <label><input type="checkbox" class="ace" name="form_115[nyeri_akut]" onclick="checkthis('nyeri_akut')" id="nyeri_akut"><span class="lbl"> Nyeri akut</span></label>
    </td>
  </tr>

  <tr>
    <td>Skala nyeri : 
      <input type="text" class="input_type" style="width:80px; text-align:center;" onchange="fillthis('nyeri_skala')"
             name="form_115[nyeri_skala]" id="nyeri_skala">
    </td>
    <td>Lokasi : 
      <input type="text" class="input_type" style="width:120px;" onchange="fillthis('nyeri_lokasi')"
             name="form_115[nyeri_lokasi]" id="nyeri_lokasi">
    </td>
    <td>Durasi : 
      <input type="text" class="input_type" style="width:120px;" onchange="fillthis('nyeri_durasi')"
             name="form_115[nyeri_durasi]" id="nyeri_durasi">
    </td>
    <td>Frekuensi : 
      <input type="text" class="input_type" style="width:120px;" onchange="fillthis('nyeri_frekuensi')"
             name="form_115[nyeri_frekuensi]" id="nyeri_frekuensi">
    </td>
  </tr>

  <tr>
    <td colspan="4">
      Karakteristik : 
      <input type="text" class="input_type" style="width:90%;" onchange="fillthis('nyeri_karakteristik')"
             name="form_115[nyeri_karakteristik]" id="nyeri_karakteristik">
    </td>
  </tr>

  <tr>
    <td colspan="4">
      Nyeri hilang bila :<br>
      <label><input type="checkbox" class="ace" name="form_115[nyeri_minum_obat]" onclick="checkthis('nyeri_minum_obat')" id="nyeri_minum_obat"><span class="lbl"> Minum obat</span></label>&nbsp;&nbsp;
      <label><input type="checkbox" class="ace" name="form_115[nyeri_mendengar_musik]" onclick="checkthis('nyeri_mendengar_musik')" id="nyeri_mendengar_musik"><span class="lbl"> Mendengar musik</span></label>&nbsp;&nbsp;
      <label><input type="checkbox" class="ace" name="form_115[nyeri_istirahat]" onclick="checkthis('nyeri_istirahat')" id="nyeri_istirahat"><span class="lbl"> Istirahat</span></label>&nbsp;&nbsp;
      <label><input type="checkbox" class="ace" name="form_115[nyeri_berubah_posisi]" onclick="checkthis('nyeri_berubah_posisi')" id="nyeri_berubah_posisi"><span class="lbl"> Berubah posisi / tidur</span></label><br>
      <label><input type="checkbox" class="ace" name="form_115[nyeri_lain_lain]" onclick="checkthis('nyeri_lain_lain')" id="nyeri_lain_lain"><span class="lbl"> Lain-lain, sebutkan :</span></label>
      <input type="text" class="input_type" style="width:70%;" onchange="fillthis('nyeri_lain_teks')"
             name="form_115[nyeri_lain_teks]" id="nyeri_lain_teks">
    </td>
  </tr>
</table>
<!-- END -->

<script>
function hitungTotalNyeri() {
  // Ambil nilai dari semua input
  const wajah = parseInt(document.getElementById('nyeri_wajah').value) || 0;
  const ekstremitas = parseInt(document.getElementById('nyeri_ekstremitas').value) || 0;
  const gerakan = parseInt(document.getElementById('nyeri_gerakan').value) || 0;
  const menangis = parseInt(document.getElementById('nyeri_menangis').value) || 0;
  const kemampuan = parseInt(document.getElementById('nyeri_kemampuan_ditenangkan').value) || 0;

  // Hitung total
  const total = wajah + ekstremitas + gerakan + menangis + kemampuan;
  document.getElementById('nyeri_total_skor').value = total;

  // Interpretasi otomatis
  let interpretasi = '';
  if (total === 0) interpretasi = 'Tidak ada nyeri';
  else if (total >= 1 && total <= 3) interpretasi = 'Nyeri ringan';
  else if (total >= 4 && total <= 6) interpretasi = 'Nyeri sedang';
  else interpretasi = 'Nyeri berat';

  document.getElementById('interpretasi_nyeri').innerText = interpretasi;
}
</script>
<!-- END -->




<br>

<hr>
<!-- <table class="table">
  <tr>
    <td colspan="2">Perawat Yang Mengkaji</td>
    <td colspan="2">Verifikasi</td>
  </tr>
  <tr>
    <td>Tanggal : <input class="input_type" type="text" style="width: 100px" name="form_115[dikaji_tgl]" id="dikaji_tgl" onchange="fillthis('dikaji_tgl')" value="<?php echo date('d/m/Y')?>"><br></td>
    <td>Jam : <input class="input_type" type="text" style="width: 100px" name="form_115[dikaji_jam]" id="dikaji_jam" onchange="fillthis('dikaji_jam')" value="<?php echo date('H:i:s')?>"><br></td>
    <td>Tanggal : <input class="input_type" type="text" style="width: 100px" name="form_115[diverif_tgl]" id="diverif_tgl" onchange="fillthis('diverif_tgl')" value="<?php echo date('d/m/Y')?>"><br></td>
    <td>Jam : <input class="input_type" type="text" style="width: 100px" name="form_115[diverif_jam]" id="diverif_jam" onchange="fillthis('diverif_jam')" value="<?php echo date('H:i:s')?>"><br></td>
  </tr>

  <tr>
    <td>Nama Perawat</td>
    <td>Tanda Tangan</td>
    <td>Nama Dokter</td>
    <td>Tanda Tangan</td>
  </tr>
  <tr>
    <td valign="middle" align="center"><br><br><?php echo $this->session->userdata('user')->fullname?></td>
    <td><br><br></td>
    <td><br><br></td>
    <td><br><br></td>
  </tr>
<table> -->

<br>

<table class="table" style="width: 100%; border:1px solid #000; border-collapse:collapse;">
  <tbody>
    <tr>
      <td style="width:33%; text-align:center;">
        Perawat yang mengisi,
        <br><br>
        <span class="ttd-btn" data-role="petugas" id="ttd_petugas" style="cursor: pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_petugas" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" class="input_type" name="form_115[nama_petugas]" id="nama_petugas" placeholder="Nama Jelas" style="width:33%; text-align:center;">
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