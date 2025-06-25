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

  $('#diagnosa_pra_bedah').typeahead({
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
        $('#diagnosa_pra_bedah').val(label_item);
      }

  });

  $('#diagnosa_pasca_bedah').typeahead({
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
        $('#diagnosa_pasca_bedah').val(label_item);
      }

  });

  $('#dokter_bedah_1').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getAllDokter",
              data: { keyword:query },            
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
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#dokter_bedah_1').val(label_item);
      }

  });

  $('#dokter_bedah_2').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getAllDokter",
              data: { keyword:query },            
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
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#dokter_bedah_2').val(label_item);
      }

  });

  $('#dokter_anestesi').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getAllDokter",
              data: { keyword:query },            
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
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#dokter_anestesi').val(label_item);
      }

  });

});
</script>

<?php echo $header; ?>
<hr>
<br>

<div style="text-align: center; font-size: 18px;"><b>LAPORAN OPERASI KATARAK</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<table style="width: 100%">
  <tr>
    <td style="width: 33%" valign="top">
      <table border="0" width="100%">
        <tr>
          <td style="width: 150px">Dokter Bedah 1</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_47[dokter_bedah_1]" id="dokter_bedah_1" onchange="fillthis('dokter_bedah_1')" value="<?php echo isset($value_form['dokter_bedah_1'])?$value_form['dokter_bedah_1']:''?>"></td>
        </tr>
        <tr>
          <td>Dokter Bedah 2</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_47[dokter_bedah_2]" id="dokter_bedah_2" onchange="fillthis('dokter_bedah_2')" value="<?php echo isset($value_form['dokter_bedah_2'])?$value_form['dokter_bedah_2']:''?>"></td>
        </tr>
      </table>
    </td>
    <td style="width: 33%" valign="top">
      <table border="0" width="100%">
        <tr>
          <td>Dokter Anestesi</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_47[dokter_anestesi]" id="dokter_anestesi" onchange="fillthis('dokter_anestesi')" value="<?php echo isset($value_form['dokter_anestesi'])?$value_form['dokter_anestesi']:''?>"></td>
        </tr>
        <tr>
          <td>Perawat Asisten</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_47[perawat_asisten]" id="perawat_asisten" onchange="fillthis('perawat_asisten')" value="<?php echo isset($value_form['perawat_asisten'])?$value_form['perawat_asisten']:''?>"></td>
        </tr>
      </table>
    </td>
    <td style="width: 40%" valign="top">
      <table border="0" width="100%">
        <tr>
          <td>Perawat Instrumen</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_47[perawat_instrumen]" id="perawat_instrumen" onchange="fillthis('perawat_instrumen')" value="<?php echo isset($value_form['perawat_instrumen'])?$value_form['perawat_instrumen']:''?>"></td>
        </tr>
        <tr>
          <td>Perawat Sirkuler</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_47[perawat_sirkuler]" id="perawat_sirkuler" onchange="fillthis('perawat_sirkuler')" value="<?php echo isset($value_form['perawat_sirkuler'])?$value_form['perawat_sirkuler']:''?>"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
<hr>
<span style="text-align: left;">Diagnosa pre- oepratif </span><br>
<input type="text" class="input-type" name="form_47[diagnosa_pre_operatif]" id="diagnosa_pre_operatif" onchange="fillthis('diagnosa_pre_operatif')" value="<?php echo isset($value_form['diagnosa_pre_operatif'])?$value_form['diagnosa_pre_operatif']:''?>" style="width: 100% !important">
<br>
<br>
<span style="text-align: left;">Diagnosa post- operatif</span>
<input type="text" class="input-type" name="form_47[diagnosa_pasca_bedah]" id="diagnosa_pasca_bedah" onchange="fillthis('diagnosa_pasca_bedah')" value="<?php echo isset($value_form['diagnosa_pasca_bedah'])?$value_form['diagnosa_pasca_bedah']:''?>" style="width: 100% !important">
<br>
<br>

<table>
  <tr>
    <td colspan="4">
      <span>Jenis Operasi</span>
      <br>
      <div class="checkbox">
        <label>
            <input type="checkbox" class="ace" value="1" name="form_47[jenis_op_kecil]" id="jenis_op_kecil" onclick="checkthis('jenis_op_kecil')" <?php echo isset($value_form['jenis_op_kecil'])?'checked':''?>>
            <span class="lbl"> Kecil</span>
        </label>

        <label>
            <input type="checkbox" class="ace" value="1" name="form_47[jenis_op_sedang]" id="jenis_op_sedang" onclick="checkthis('jenis_op_sedang')" <?php echo isset($value_form['jenis_op_sedang'])?'checked':''?>>
            <span class="lbl"> Sedang</span>
        </label>

        <label>
            <input type="checkbox" class="ace" value="1" name="form_47[jenis_op_besar]" id="jenis_op_besar" onclick="checkthis('jenis_op_besar')" <?php echo isset($value_form['jenis_op_besar'])?'checked':''?>>
            <span class="lbl"> Besar</span>
        </label>

        <label>
            <input type="checkbox" class="ace" value="1" name="form_47[jenis_op_khusus]" id="jenis_op_khusus" onclick="checkthis('jenis_op_khusus')" <?php echo isset($value_form['jenis_op_khusus'])?'checked':''?>>
            <span class="lbl"> Khusus</span>
        </label>

        <label>
            <input type="checkbox" class="ace" value="1" name="form_47[jenis_op_elektif]" id="jenis_op_elektif" onclick="checkthis('jenis_op_elektif')" <?php echo isset($value_form['jenis_op_elektif'])?'checked':''?>>
            <span class="lbl"> Elektif</span>
        </label>
        <label>
            <input type="checkbox" class="ace" value="1" name="form_47[jenis_op_cito]" id="jenis_op_cito" onclick="checkthis('jenis_op_cito')" <?php echo isset($value_form['jenis_op_cito'])?'checked':''?>>
            <span class="lbl"> Cito</span>
        </label>

      </div>

    </td>
  </tr>
</table>
<br>
<span style="text-align: left;">Jaringan yang di eksisi/-insisi</span>
<input type="text" class="input-type" name="form_47[jaringan_eksisi]" id="jaringan_eksisi" onchange="fillthis('jaringan_eksisi')" value="<?php echo isset($value_form['jaringan_eksisi'])?$value_form['jaringan_eksisi']:''?>" style="width: 100% !important">
<br>
<br>
<span style="text-align: left;">Nama / Macam Operasi </span>
<input type="text" class="input-type" name="form_47[nm_mcm_op]" id="nm_mcm_op" onchange="fillthis('nm_mcm_op')" value="<?php echo isset($value_form['nm_mcm_op'])?$value_form['nm_mcm_op']:''?>" style="width: 100% !important">

<br>
<br>
<table style="width: 100%">
  <tr style="border: 1px solid grey; padding: 5px">
    <td style="width: 25%; padding: 10px">
      Tanggal Operasi<br>
      <input type="text" class="input_type date-picker" data-date-format="yyyy-mm-dd" name="form_47[tgl_operasi]" id="tgl_operasi" onchange="fillthis('tgl_operasi')" value="<?php echo isset($value_form['tgl_operasi'])?$value_form['tgl_operasi']:date('Y-m-d')?>">
    </td>
    <td style="width: 25%; padding: 10px">
      Jam Operasi dimulai<br>
      <input type="text" class="input_type" name="form_47[jam_operasi]" id="jam_operasi" onchange="fillthis('jam_operasi')" value="<?php echo isset($value_form['jam_operasi'])?$value_form['jam_operasi']:date('H:i')?>">
    </td>
    <td style="width: 25%; padding: 10px">
      Jam Operasi selesai<br>
      <input type="text" class="input_type" name="form_47[jam_operasi_selesai]" id="jam_operasi_selesai" onchange="fillthis('jam_operasi_selesai')" value="<?php echo isset($value_form['jam_operasi_selesai'])?$value_form['jam_operasi_selesai']:date('H:i')?>">
    </td>

    <td style="width: 25%; padding: 10px">
      Lama operasi berlangsung<br>
      <input type="text" class="input_type" name="form_47[lama_operasi]" id="lama_operasi" onchange="fillthis('lama_operasi')" value="<?php echo isset($value_form['lama_operasi'])?$value_form['lama_operasi']:''?>">
    </td>
  </tr>
</table>

<br>
<p style="text-align: center; font-weight: bold">PROSEDUR OPERASI</p>
<textarea class="textarea-type" name="form_47[prosedur_operasi]" id="prosedur_operasi" onchange="fillthis('prosedur_operasi')" style="height: 300px !important">
  <?php echo isset($value_form['prosedur_operasi'])?$value_form['prosedur_operasi']:'
    1. Pasien tidur terlentang dengan lokal anestesi
    2. Desinfeksi lapangan operasi dengan betadin kemudian tutup dengan eye drape, pasang lid spreader
    3. Irigasi bola mata dengan betadin 0.25% kemudian irigasi dengan BSS
    4. Clear Corneal Incision dilanjutkan dengan pewarnaan capsule anterior dengan trypan blue
    5. Continuous Circumlinear Capsulorhexis dilanjutkan dengan hydrodiseksi
    6. Pembuatan slide port dilanjutkan dengan fakoemulsifikasi
    7. Dilakukan irigasi aspirasi sisa kortek
    8. Pemasangan Intra Oculer Lens (IOL) in the bag / sulcus
    9. Injeksi miostat intracamelar
    10. Irigasi dan aspirasi ualng
    11. Hidrasi corneal
    12. Injeksi antibiotik intrakameral
    13. Test kedap insisi dengan betadin
    14. Antibiotik dan steroid tetes mata
    15. Operasi selesai
  '?>
</textarea>
<br>
<br>
<hr>
<?php echo $footer; ?>