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

  $('#19_diagnosa_pra_bedah').typeahead({
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
        $('#19_diagnosa_pra_bedah').val(label_item);
      }

  });

  $('#19_diagnosa_pasca_bedah').typeahead({
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
        $('#19_diagnosa_pasca_bedah').val(label_item);
      }

  });

  $('#19_dokter_bedah_1').typeahead({
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

});
</script>

<?php echo $header; ?>
<hr>
<br>

<div style="text-align: center; font-size: 18px;"><b>LAPORAN TINDAKAN ESWL</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<table>
  <tr>
    <td style="width: 50%">
      <table border="0">
        <tr>
          <td style="width: 100px">Tanggal Operasi</td>
          <td>
            <input type="text" class="input_type date-picker" data-date-format="yyyy-mm-dd" name="form_19[19_tgl_operasi]" id="19_tgl_operasi" onchange="fillthis('19_tgl_operasi')" value="<?php echo isset($value_form['19_tgl_operasi'])?$value_form['19_tgl_operasi']:''?>"></td>
        </tr>
        <tr>
          <td style="width: 100px">Jam Operasi</td>
          <td>
            <input type="text" class="input_type" name="form_19[19_jam_operasi]" id="19_jam_operasi" onchange="fillthis('19_jam_operasi')" value="<?php echo isset($value_form['19_jam_operasi'])?$value_form['19_jam_operasi']:''?>"><br></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
    <td style="width: 50%" valign="top">
      <table border="0" width="100%">
        <tr>
          <td style="width: 150px">Dokter Urologi</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_19[19_dokter_bedah_1]" id="19_dokter_bedah_1" onchange="fillthis('19_dokter_bedah_1')" value="<?php echo isset($value_form['19_dokter_bedah_1'])?$value_form['19_dokter_bedah_1']:''?>"></td>
        </tr>
        <tr>
          <td>Perawat</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_19[19_perawat_sirkuler]" id="19_perawat_sirkuler" onchange="fillthis('19_perawat_sirkuler')" value="<?php echo isset($value_form['19_perawat_sirkuler'])?$value_form['19_perawat_sirkuler']:''?>"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<br>
<span style="text-align: left;">Diagnosa Pra Bedah </span><br>
<input type="text" class="input-type" name="form_19[19_diagnosa_pra_bedah]" id="19_diagnosa_pra_bedah" onchange="fillthis('19_diagnosa_pra_bedah')" value="<?php echo isset($value_form['19_diagnosa_pra_bedah'])?$value_form['19_diagnosa_pra_bedah']:''?>" style="width: 100% !important">
<br>
<br>
<span style="text-align: left;">Diagnosa Pasca Bedah</span>
<input type="text" class="input-type" name="form_19[19_diagnosa_pasca_bedah]" id="19_diagnosa_pasca_bedah" onchange="fillthis('19_diagnosa_pasca_bedah')" value="<?php echo isset($value_form['19_diagnosa_pasca_bedah'])?$value_form['19_diagnosa_pasca_bedah']:''?>" style="width: 100% !important">
<br>
<br>
<span style="text-align: left;">Tindakan</span>
<input type="text" class="input-type" name="form_19[19_tindakan_operasi]" id="19_tindakan_operasi" onchange="fillthis('19_tindakan_operasi')" value="<?php echo isset($value_form['19_tindakan_operasi'])?$value_form['19_tindakan_operasi']:''?>" style="width: 100% !important">
<br>
<br>
<span style="text-align: left;">Komplikasi</span>
<input type="text" class="input-type" name="form_19[19_komplikasi_pasien]" id="19_komplikasi_pasien" onchange="fillthis('19_komplikasi_pasien')" value="<?php echo isset($value_form['19_komplikasi_pasien'])?$value_form['19_komplikasi_pasien']:''?>" style="width: 100% !important">
<br>
<br>
<p style="text-align: center; font-weight: bold">PROSEDUR OPERASI</p>
<textarea class="textarea-type" name="form_19[19_prosedur_operasi]" id="19_prosedur_operasi" onchange="fillthis('19_prosedur_operasi')" style="height: 200px !important;"><?php echo isset($value_form['19_prosedur_operasi'])?$value_form['19_prosedur_operasi']:''?></textarea>
<br>
<br>
<hr>
<?php echo $footer; ?>