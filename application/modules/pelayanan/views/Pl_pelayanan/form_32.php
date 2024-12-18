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

  $('#dignosis_kerja').typeahead({
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
        $('#dignosis_kerja').val(label_item);
      }

  });

  $('#dignosis_banding').typeahead({
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
        $('#dignosis_banding').val(label_item);
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

<div style="text-align: center; font-size: 18px;"><b>PENGKAJIAN DOKTER RAWAT JALAN</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<br>
<span style="text-align: left; font-weight: bold">I. ANAMNESIS</span><br>
<br>
<span style="text-align: left;">Keluhan Utama</span><br>
<textarea class="textarea-type" name="form_32[keluhan_utama]" id="keluhan_utama" onchange="fillthis('keluhan_utama')" style="height: 50px !important"><?php echo isset($value_form['keluhan_utama'])?$value_form['keluhan_utama']:''?></textarea>
<br>
<br>
<span style="text-align: left;">Keluhan Tambahan</span><br>
<textarea class="textarea-type" name="form_32[keluhan_tambahan]" id="keluhan_tambahan" onchange="fillthis('keluhan_tambahan')" style="height: 50px !important"><?php echo isset($value_form['keluhan_tambahan'])?$value_form['keluhan_tambahan']:''?></textarea>
<br>
<br>
<span style="text-align: left;">Riwayat Penyakit Sekarang</span><br>
<textarea class="textarea-type" name="form_32[riwayat_penyakit_skrg]" id="riwayat_penyakit_skrg" onchange="fillthis('riwayat_penyakit_skrg')" style="height: 50px !important"><?php echo isset($value_form['riwayat_penyakit_skrg'])?$value_form['riwayat_penyakit_skrg']:''?></textarea>
<br>
<br>
<span style="text-align: left;">Riwayat Alergi</span><br>
<textarea class="textarea-type" name="form_32[riwayat_alergi]" id="riwayat_alergi" onchange="fillthis('riwayat_alergi')" style="height: 80px !important"><?php echo isset($value_form['riwayat_alergi'])?$value_form['riwayat_alergi']:''?></textarea>
<br>
<br>
<span style="text-align: left;">Riwayat Obat Yang Diminum</span><br>
<textarea class="textarea-type" name="form_32[riwayat_obat_diminum]" id="riwayat_obat_diminum" onchange="fillthis('riwayat_obat_diminum')" style="height: 100px !important"><?php echo isset($value_form['riwayat_obat_diminum'])?$value_form['riwayat_obat_diminum']:''?></textarea>
<br>

<br>
<p style="text-align: left; font-weight: bold">II. PEMERIKSAAN FISIK</p>
<textarea class="textarea-type" name="form_32[pemeriksaan_fisik]" id="pemeriksaan_fisik" onchange="fillthis('pemeriksaan_fisik')" style="height: 200px !important"><?php echo isset($value_form['pemeriksaan_fisik'])?$value_form['pemeriksaan_fisik']:''?></textarea>
<br>


<br>
<span style="text-align: left; font-weight: bold">III. DIAGNOSA KERJA</span><br>
<input type="text" class="input-type" name="form_32[dignosis_kerja]" id="dignosis_kerja" onchange="fillthis('dignosis_kerja')" value="<?php echo isset($value_form['dignosis_kerja'])?$value_form['dignosis_kerja']:''?>" style="width: 100% !important">
<br>
<br>
<span style="text-align: left; font-weight: bold">IV. DIAGNOSA BANDING</span>
<input type="text" class="input-type" name="form_32[dignosis_banding]" id="dignosis_banding" onchange="fillthis('dignosis_banding')" value="<?php echo isset($value_form['dignosis_banding'])?$value_form['dignosis_banding']:''?>" style="width: 100% !important">
<br>
<br>
<span style="text-align: left; font-weight: bold">V. ANJURAN PENGOBATAN</span><br>
<textarea class="textarea-type" name="form_32[anjuran_pengobatan]" id="anjuran_pengobatan" onchange="fillthis('anjuran_pengobatan')" style="height: 100px !important"><?php echo isset($value_form['anjuran_pengobatan'])?$value_form['riwayat_obat_diminum']:''?></textarea>
<br>
<br>
<span style="text-align: left; font-weight: bold">VI. ANJURAN PEMERIKSAAN</span><br>
<textarea class="textarea-type" name="form_32[anjuran_pemeriksaan]" id="anjuran_pemeriksaan" onchange="fillthis('anjuran_pemeriksaan')" style="height: 100px !important"><?php echo isset($value_form['anjuran_pemeriksaan'])?$value_form['anjuran_pemeriksaan']:''?></textarea>
<br>
<br>
<span style="text-align: left; font-weight: bold">VII. DIRUJUK / KONSUL</span><br>
<textarea class="textarea-type" name="form_32[dirujuk_atau_konsul]" id="dirujuk_atau_konsul" onchange="fillthis('dirujuk_atau_konsul')" style="height: 100px !important"><?php echo isset($value_form['dirujuk_atau_konsul'])?$value_form['dirujuk_atau_konsul']:''?></textarea>
<br>

<br>
<hr>
<?php echo $footer; ?>