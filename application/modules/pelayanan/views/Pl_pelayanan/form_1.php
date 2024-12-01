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

  $('#1_diagnosis_kerja').typeahead({
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
        $('#1_diagnosis_kerja').val(label_item);
      }

  });

  $('#1_diagnosis_banding').typeahead({
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
        $('#1_diagnosis_banding').val(label_item);
      }

  });

});
</script>
<?php echo $header; ?>
<hr>
<br>
<p style="text-align: center"><strong>PENGKAJIAN PRA BEDAH<br>KASUS BEDAH ORTHOPEDI</strong></p>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<p><strong>ANAMNESIS</strong> :</p>
<textarea class="textarea-type" name="form_1[1_anamnesis]" id="1_anamnesis" onchange="fillthis('1_anamnesis')" style="height: 100px !important"><?php echo isset($value_form['1_anamnesis'])?$value_form['1_anamnesis']:''?></textarea>
<br>
<br>
<p><strong>PEMERIKSAAN FISIK</strong> :</p>
<table class="table">
	<tr>
		<td width="100px" rowspan="3" valign="top">Status Generalis</td>
		<td width="100px">Keadaan Umum</td>
		<td><input type="text" class="input_type" style="width: 90% !important" name="form_1[1_status_generalis_ku]" id="1_status_generalis_ku" onchange="fillthis('1_status_generalis_ku')" value="<?php echo isset($value_form['status_generalis_ku'])?$value_form['status_generalis_ku']:''?>"> </td>
	</tr>
	<tr>
		<td>Kesadaran</td>
		<td><input type="text" class="input_type" style="width: 90% !important" name="form_1[1_status_generalis_kesadaran]" id="1_status_generalis_kesadaran" onchange="fillthis('1_status_generalis_kesadaran')" value="<?php echo isset($value_form['status_generalis_kesadaran'])?$value_form['status_generalis_kesadaran']:''?>"> </td>
	</tr>
	<tr>
		<td colspan="2">
			TD : <input type="text" class="input_type" style="width: 50px !important" name="form_1[1_status_generalis_td]" id="1_status_generalis_td" onchange="fillthis('1_status_generalis_td')" value="<?php echo isset($value_form['status_generalis_td'])?$value_form['status_generalis_td']:''?>">mmHg.  
			FN : <input type="text" class="input_type" style="width: 50px !important" name="form_1[1_status_generalis_fn]" id="1_status_generalis_fn" onchange="fillthis('1_status_generalis_fn')" value="<?php echo isset($value_form['status_generalis_fn'])?$value_form['status_generalis_fn']:''?>">x/m. 
			FP : <input type="text" class="input_type" style="width: 50px !important" name="form_1[1_status_generalis_fp]" id="1_status_generalis_fp" onchange="fillthis('1_status_generalis_fp')" value="<?php echo isset($value_form['status_generalis_fp'])?$value_form['status_generalis_fp']:''?>">x/m. 
			S : <input type="text" class="input_type" style="width: 50px !important" name="form_1[1_status_generalis_s]" id="1_status_generalis_s" onchange="fillthis('1_status_generalis_s')" value="<?php echo isset($value_form['status_generalis_s'])?$value_form['status_generalis_s']:''?>"> &deg;C. 
			TB/BB : <input type="text" class="input_type" style="width: 50px !important" name="form_1[1_status_generalis_tbbb]" id="1_status_generalis_tbbb" onchange="fillthis('1_status_generalis_tbbb')" value="<?php echo isset($value_form['status_generalis_tbbb'])?$value_form['status_generalis_tbbb']:''?>">cm/kg. 
		</td>
	</tr>
</table>
<br>
<p><strong>STATUS LOKALIS</strong> :</p>
<textarea class="textarea-type" name="form_1[1_status_lokalis]" id="1_status_lokalis" onchange="fillthis('1_status_lokalis')" style="height: 100px !important"><?php echo isset($value_form['1_status_lokalis'])?$value_form['1_status_lokalis']:''?></textarea>

<br>
<br>
<span><strong>DIAGNOSIS KERJA</strong> :</span>
<input type="text" class="input-type" placeholder="Masukan ICD X" name="form_1[1_diagnosis_kerja]" id="1_diagnosis_kerja" onchange="fillthis('1_diagnosis_kerja')" value="<?php echo isset($value_form['1_diagnosis_kerja'])?$value_form['1_diagnosis_kerja']:''?>" style="width: 100% !important">
<br>
<br>
<span><strong>DIAGNOSIS BANDING</strong> :</span>
<input type="text" class="input-type" name="form_1[1_diagnosis_banding]" id="1_diagnosis_banding" onchange="fillthis('1_diagnosis_banding')" value="<?php echo isset($value_form['1_diagnosis_banding'])?$value_form['1_diagnosis_banding']:''?>" style="width: 100% !important">
<br>
<br>
<span><strong>PENATALAKSANAAN</strong> :</span><br>
1. Rencana Pemeriksaan<br>
<textarea class="textarea-type" name="form_1[1_rencana_pemeriksaan]" id="1_rencana_pemeriksaan" onchange="fillthis('1_rencana_pemeriksaan')" style="height: 100px !important"><?php echo isset($value_form['1_rencana_pemeriksaan'])?$value_form['1_rencana_pemeriksaan']:''?></textarea>
<br>
2. Rencana Terapi<br>
<textarea class="textarea-type" name="form_1[1_rencana_terapi]" id="1_rencana_terapi" onchange="fillthis('1_rencana_terapi')" style="height: 100px !important"><?php echo isset($value_form['1_rencana_terapi'])?$value_form['1_rencana_terapi']:''?></textarea>
<hr>
<?php echo $footer; ?>