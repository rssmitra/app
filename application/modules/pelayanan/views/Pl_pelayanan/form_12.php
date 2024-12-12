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

<div style="text-align: center; font-size: 18px;"><b>LAPORAN OPERASI</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<table>
  <tr>
    <td style="width: 60%">
      <table border="0">
        <tr>
          <td style="width: 100px">Tanggal Operasi</td>
          <td>
            <input type="text" class="input_type date-picker" data-date-format="yyyy-mm-dd" name="form_12[tgl_operasi]" id="tgl_operasi" onchange="fillthis('tgl_operasi')" value="<?php echo isset($value_form['tgl_operasi'])?$value_form['tgl_operasi']:date('Y-m-d')?>"></td>
        </tr>
        <tr>
          <td style="width: 100px">Jam Operasi</td>
          <td>
            <input type="text" class="input_type" name="form_12[jam_operasi]" id="jam_operasi" onchange="fillthis('jam_operasi')" value="<?php echo isset($value_form['jam_operasi'])?$value_form['jam_operasi']:date('H:i')?>"><br></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">
            <span>Jenis Operasi</span>
            <br>
            <div class="checkbox">
              <label>
                  <input type="checkbox" class="ace" value="1" name="form_12[jenis_op_kecil]" id="jenis_op_kecil" onclick="checkthis('jenis_op_kecil')" <?php echo isset($value_form['jenis_op_kecil'])?'checked':''?>>
                  <span class="lbl"> Kecil</span>
              </label>

              <label>
                  <input type="checkbox" class="ace" value="1" name="form_12[jenis_op_sedang]" id="jenis_op_sedang" onclick="checkthis('jenis_op_sedang')" <?php echo isset($value_form['jenis_op_sedang'])?'checked':''?>>
                  <span class="lbl"> Sedang</span>
              </label>

              <label>
                  <input type="checkbox" class="ace" value="1" name="form_12[jenis_op_besar]" id="jenis_op_besar" onclick="checkthis('jenis_op_besar')" <?php echo isset($value_form['jenis_op_besar'])?'checked':''?>>
                  <span class="lbl"> Besar</span>
              </label>

              <label>
                  <input type="checkbox" class="ace" value="1" name="form_12[jenis_op_khusus]" id="jenis_op_khusus" onclick="checkthis('jenis_op_khusus')" <?php echo isset($value_form['jenis_op_khusus'])?'checked':''?>>
                  <span class="lbl"> Khusus</span>
              </label>

              <label>
                  <input type="checkbox" class="ace" value="1" name="form_12[jenis_op_elektif]" id="jenis_op_elektif" onclick="checkthis('jenis_op_elektif')" <?php echo isset($value_form['jenis_op_elektif'])?'checked':''?>>
                  <span class="lbl"> Elektif</span>
              </label>
              <label>
                  <input type="checkbox" class="ace" value="1" name="form_12[jenis_op_cito]" id="jenis_op_cito" onclick="checkthis('jenis_op_cito')" <?php echo isset($value_form['jenis_op_cito'])?'checked':''?>>
                  <span class="lbl"> Cito</span>
              </label>

            </div>

          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td style="width: 100px" colspan="4">
            Pemeriksaan PA<br>
            <div class="checkbox">
              <label>
                  <input type="checkbox" class="ace" value="1" name="form_12[pa_y]" id="pa_y" onclick="checkthis('pa_y')" <?php echo isset($value_form['pa_y'])?'checked':''?>>
                  <span class="lbl"> Ya</span>
              </label>
              <label>
                  <input type="checkbox" class="ace" value="1" name="form_12[pa_n]" id="pa_n" onclick="checkthis('pa_n')" <?php echo isset($value_form['pa_n'])?'checked':''?>>
                  <span class="lbl"> Tidak</span>
              </label>
            </div>
          </td>
        </tr>
      </table>
    </td>
    <td style="width: 40%" valign="top">
      <table border="0" width="100%">
        <tr>
          <td style="width: 150px">Dokter Bedah 1</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_12[dokter_bedah_1]" id="dokter_bedah_1" onchange="fillthis('dokter_bedah_1')" value="<?php echo isset($value_form['dokter_bedah_1'])?$value_form['dokter_bedah_1']:''?>"></td>
        </tr>
        <tr>
          <td>Dokter Bedah 2</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_12[dokter_bedah_2]" id="dokter_bedah_2" onchange="fillthis('dokter_bedah_2')" value="<?php echo isset($value_form['dokter_bedah_2'])?$value_form['dokter_bedah_2']:''?>"></td>
        </tr>
        <tr>
          <td>Dokter Anestesi</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_12[dokter_anestesi]" id="dokter_anestesi" onchange="fillthis('dokter_anestesi')" value="<?php echo isset($value_form['dokter_anestesi'])?$value_form['dokter_anestesi']:''?>"></td>
        </tr>
        <tr>
          <td>Perawat Instrumen</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_12[perawat_instrumen]" id="perawat_instrumen" onchange="fillthis('perawat_instrumen')" value="<?php echo isset($value_form['perawat_instrumen'])?$value_form['perawat_instrumen']:''?>"></td>
        </tr>
        <tr>
          <td>Perawat Sirkuler</td>
          <td><input type="text" class="input_type" style="width: 90% !important" name="form_12[perawat_sirkuler]" id="perawat_sirkuler" onchange="fillthis('perawat_sirkuler')" value="<?php echo isset($value_form['perawat_sirkuler'])?$value_form['perawat_sirkuler']:''?>"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<br>
<span style="text-align: left;">Diagnosa Pra Bedah </span><br>
<input type="text" class="input-type" name="form_12[diagnosa_pra_bedah]" id="diagnosa_pra_bedah" onchange="fillthis('diagnosa_pra_bedah')" value="<?php echo isset($value_form['diagnosa_pra_bedah'])?$value_form['diagnosa_pra_bedah']:''?>" style="width: 100% !important">
<br>
<br>
<span style="text-align: left;">Diagnosa Pasca Bedah</span>
<input type="text" class="input-type" name="form_12[diagnosa_pasca_bedah]" id="diagnosa_pasca_bedah" onchange="fillthis('diagnosa_pasca_bedah')" value="<?php echo isset($value_form['diagnosa_pasca_bedah'])?$value_form['diagnosa_pasca_bedah']:''?>" style="width: 100% !important">
<br>
<br>
<span style="text-align: left;">Tindakan</span>
<input type="text" class="input-type" name="form_12[tindakan_operasi]" id="tindakan_operasi" onchange="fillthis('tindakan_operasi')" value="<?php echo isset($value_form['tindakan_operasi'])?$value_form['tindakan_operasi']:''?>" style="width: 100% !important">
<br>
<br>
<span style="text-align: left;">Komplikasi</span>
<input type="text" class="input-type" name="form_12[komplikasi_pasien]" id="komplikasi_pasien" onchange="fillthis('komplikasi_pasien')" value="<?php echo isset($value_form['komplikasi_pasien'])?$value_form['komplikasi_pasien']:''?>" style="width: 100% !important">
<br>
<br>
<p style="text-align: center; font-weight: bold">PROSEDUR OPERASI</p>
<textarea class="textarea-type" name="form_12[prosedur_operasi]" id="prosedur_operasi" onchange="fillthis('prosedur_operasi')" style="height: 200px !important"><?php echo isset($value_form['prosedur_operasi'])?$value_form['prosedur_operasi']:''?></textarea>
<br>
<br>
<hr>
<?php echo $footer; ?>