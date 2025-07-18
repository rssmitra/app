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

});
</script>

<?php echo $header; ?>
<hr>
<br>

<div style="text-align: center; font-size: 18px;"><b>FORM REASSESMENT REHABILITASI MEDIS</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<span style="text-align: left;">Keluhan Utama</span><br>
<input type="text" class="input-type" name="form_54[keluhan_utama]" id="keluhan_utama" onchange="fillthis('keluhan_utama')" value="<?php echo isset($value_form['keluhan_utama'])?$value_form['keluhan_utama']:''?>" style="width: 100% !important">
<br>

<br>
<span style="text-align: left;">Riwayat Penyakit Sekarang</span><br>
<input type="text" class="input-type" name="form_54[riwayat_penyakit]" id="riwayat_penyakit" onchange="fillthis('riwayat_penyakit')" value="<?php echo isset($value_form['riwayat_penyakit'])?$value_form['riwayat_penyakit']:''?>" style="width: 100% !important">
<br>

<br>
<span style="text-align: left;">Riwayat Penyakit Dahulu</span><br>
<input type="text" class="input-type" name="form_54[riwayat_penyakit_dulu]" id="riwayat_penyakit_dulu" onchange="fillthis('riwayat_penyakit_dulu')" value="<?php echo isset($value_form['riwayat_penyakit_dulu'])?$value_form['riwayat_penyakit_dulu']:''?>" style="width: 100% !important">
<br>

<br>
<span style="text-align: left;">Pemeriksaan Fisik : </span>
<textarea class="textarea-type" name="form_54[pemeriksaan_fisik]" id="pemeriksaan_fisik" onchange="fillthis('pemeriksaan_fisik')" style="height: 70px !important">
  <?php 
    $default_text = '
      A. Umum<br>
      B. Neuromuskulokletal<br>
          <span style="padding-left: 50px">1. Lingkup Gerak Sendi</span><br>
          <span style="padding-left: 50px">2. Kekuatan Otot</span><br>
      C. Kardiorespirasi<br>
      <br>
      <br>
      <br>

    ';
    echo isset($value_form['pemeriksaan_fisik'])?$value_form['pemeriksaan_fisik']:''?>
</textarea>
<br>

<br>
<span style="text-align: left;">Pemeriksaan Penunjang : </span>
<textarea class="textarea-type" name="form_54[pemeriksaan_pm]" id="pemeriksaan_pm" onchange="fillthis('pemeriksaan_pm')" style="height: 70px !important">
  <?php echo isset($value_form['pemeriksaan_pm'])?$value_form['pemeriksaan_pm']:''?>
</textarea>
<br>

<br>
<span style="text-align: left;">Pemeriksaan Khusus : </span>
<textarea class="textarea-type" name="form_54[pemeriksaan_khusus]" id="pemeriksaan_khusus" onchange="fillthis('pemeriksaan_khusus')" style="height: 70px !important">
  <?php echo isset($value_form['pemeriksaan_khusus'])?$value_form['pemeriksaan_khusus']:''?>
</textarea>
<br>

<br>
<span style="text-align: left;">Kesimpulan : </span>
<textarea class="textarea-type" name="form_54[kesimpulan]" id="kesimpulan" onchange="fillthis('kesimpulan')" style="height: 70px !important">
  <?php echo isset($value_form['kesimpulan'])?$value_form['kesimpulan']:''?>
</textarea>
<br>

<br>
<span style="text-align: left;">Rekomendasi : </span>
<textarea class="textarea-type" name="form_54[rekomendasi]" id="rekomendasi" onchange="fillthis('rekomendasi')" style="height: 70px !important">
  <?php echo isset($value_form['rekomendasi'])?$value_form['rekomendasi']:''?>
</textarea>
<br>
<hr>
<?php echo $footer; ?>