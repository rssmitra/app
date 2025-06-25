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

<div style="text-align: center; font-size: 18px;"><b>LAPORAN OPERASI<br> EPILASI/ CORPUS ALINEUM </b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<table style="width: 100%">
  <tr>
    <td style="width: 50%">
      <table border="0">
        <tr>
          <td style="width: 150px">Tanggal Operasi</td>
          <td>
            <input type="text" class="input_type date-picker" data-date-format="yyyy-mm-dd" name="form_45[tgl_operasi]" id="tgl_operasi" onchange="fillthis('tgl_operasi')" value="<?php echo isset($value_form['tgl_operasi'])?$value_form['tgl_operasi']:date('Y-m-d')?>"></td>
        </tr>
        <tr>
          <td style="width: 150px">Jam Operasi Mulai</td>
          <td>
            <input type="text" class="input_type" name="form_45[jam_operasi]" id="jam_operasi" onchange="fillthis('jam_operasi')" value="<?php echo isset($value_form['jam_operasi'])?$value_form['jam_operasi']:date('H:i')?>"><br></td>
        </tr>
        <tr>
          <td style="width: 150px">Jam Operasi Selesai</td>
          <td>
            <input type="text" class="input_type" name="form_45[jam_operasi_selesai]" id="jam_operasi_selesai" onchange="fillthis('jam_operasi_selesai')" value="<?php echo isset($value_form['jam_operasi_selesai'])?$value_form['jam_operasi_selesai']:date('H:i')?>"><br></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
    <td style="width: 45%" valign="top">
      <table border="0" width="100%">
        <tr>
          <td style="width: 150px">Dokter Bedah 1</td>
          <td><input type="text" class="input_type" style="width: 100% !important" name="form_45[dokter_bedah_1]" id="dokter_bedah_1" onchange="fillthis('dokter_bedah_1')" value="<?php echo isset($value_form['dokter_bedah_1'])?$value_form['dokter_bedah_1']:''?>"></td>
        </tr>
        <tr>
          <td>Dokter Bedah 2</td>
          <td><input type="text" class="input_type" style="width: 100% !important" name="form_45[dokter_bedah_2]" id="dokter_bedah_2" onchange="fillthis('dokter_bedah_2')" value="<?php echo isset($value_form['dokter_bedah_2'])?$value_form['dokter_bedah_2']:''?>"></td>
        </tr>
        <tr>
          <td>Dokter Anestesi</td>
          <td><input type="text" class="input_type" style="width: 100% !important" name="form_45[dokter_anestesi]" id="dokter_anestesi" onchange="fillthis('dokter_anestesi')" value="<?php echo isset($value_form['dokter_anestesi'])?$value_form['dokter_anestesi']:''?>"></td>
        </tr>
        <tr>
          <td>Perawat Asisten</td>
          <td><input type="text" class="input_type" style="width: 100% !important" name="form_45[perawat_asisten]" id="perawat_asisten" onchange="fillthis('perawat_asisten')" value="<?php echo isset($value_form['perawat_asisten'])?$value_form['perawat_asisten']:''?>"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<br>
<span style="text-align: left;">Diagnosa </span><br>
<input type="text" class="input-type" name="form_45[diagnosa_pra_bedah]" id="diagnosa_pra_bedah" onchange="fillthis('diagnosa_pra_bedah')" value="<?php echo isset($value_form['diagnosa_pra_bedah'])?$value_form['diagnosa_pra_bedah']:''?>" style="width: 100% !important">
<br>
<br>
<span style="text-align: left;">Tindakan Operasi</span>
<input type="text" class="input-type" name="form_45[tindakan_operasi]" id="tindakan_operasi" onchange="fillthis('tindakan_operasi')" value="<?php echo isset($value_form['tindakan_operasi'])?$value_form['tindakan_operasi']:''?>" style="width: 100% !important">
<br>
<br>
<p style="text-align: center; font-weight: bold">PROSEDUR OPERASI</p>
<textarea class="textarea-type" name="form_45[prosedur_operasi]" id="prosedur_operasi" onchange="fillthis('prosedur_operasi')" style="height: 200px !important"><?php echo isset($value_form['prosedur_operasi'])?$value_form['prosedur_operasi']:''?>
1. Anestesi topical dengan pantocain 0.5%
2. Pasien duduk didepan slit lamp
3. Epilasi
4. Selesai
</textarea>
<br>
<br>
<hr>
<?php echo $footer; ?>