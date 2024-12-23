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

});

</script>

<?php echo $header; ?>
<hr>
<br>
<div style="text-align: center; font-size: 18px;"><b>RIWAYAT PENYAKIT PASIEN KASUS GINEKOLOGI </b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<span><div class="datepicker" style="float: left">Tanggal : <input type="text" class="input_type date-picker" name="form_5[tgl_anamnesis]" id="tgl_anamnesis" onchange="fillthis('tgl_anamnesis')" value="<?php echo isset($value_form['tgl_anamnesis'])?$value_form['tgl_anamnesis']:''?>" style="width: 100px !important"></div><div class="pull-left">Jam : <input type="text" class="input_type" name="form_5[jam_anamnesis]" id="jam_anamnesis" onchange="fillthis('jam_anamnesis')" value="<?php echo isset($value_form['jam_anamnesis'])?$value_form['jam_anamnesis']:''?>" style="width: 70px !important"></div></span><br>
<br>
<br>
<span><strong>ANAMNESIS</strong> : </span><br>
<textarea class="textarea-type" name="form_5[anamnesis]" id="anamnesis" onchange="fillthis('anamnesis')" style="height: 100px !important"><?php echo isset($value_form['anamnesis'])?$value_form['anamnesis']:''?></textarea>
<br>
<br>
<span><strong>PEMERIKSAAN FISIK</strong> : </span><br>
<p>Status Generalis :&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>
&nbsp; &nbsp;&nbsp;Kesadaran Umum<br>
&nbsp; &nbsp;&nbsp;Kesadaran<br>
&nbsp; &nbsp; 
TD : <input type="text" class="input_type" name="form_5[td]" id="td" onchange="fillthis('td')" value="<?php echo isset($value_form['td'])?$value_form['td']:''?>" style="width: 50px !important"> mmHg. &nbsp;&nbsp;&nbsp;
FN :  <input type="text" class="input_type" name="form_5[fn]" id="fn" onchange="fillthis('fn')" value="<?php echo isset($value_form['fn'])?$value_form['fn']:''?>" style="width: 50px !important"> x/m &nbsp;&nbsp;&nbsp;
FP : <input type="text" class="input_type" name="form_5[fp]" id="fp" onchange="fillthis('fp')" value="<?php echo isset($value_form['fp'])?$value_form['fp']:''?>" style="width: 50px !important"> x/m. &nbsp;&nbsp;&nbsp;
S : <input type="text" class="input_type" name="form_5[shu]" id="shu" onchange="fillthis('shu')" value="<?php echo isset($value_form['shu'])?$value_form['shu']:''?>" style="width: 50px !important"> &deg;C. &nbsp;&nbsp;&nbsp;
TB/BB : <input type="text" class="input_type" name="form_5[tb_bb]" id="tb_bb" onchange="fillthis('tb_bb')" value="<?php echo isset($value_form['tb_bb'])?$value_form['tb_bb']:''?>" style="width: 50px !important"> cm/kg<br>
<br>
<span>Status Ginekologi :</span><br>
<textarea class="textarea-type" name="form_5[status_ginekologi]" id="status_ginekologi" onchange="fillthis('status_ginekologi')" style="height: 100px !important"><?php echo isset($value_form['status_ginekologi'])?$value_form['status_ginekologi']:''?></textarea>
<br>
<br>
<span><strong>DIAGNOSIS KERJA :</strong></span><br>
<textarea class="textarea-type" name="form_5[diagnosis_kerja]" id="diagnosis_kerja" onchange="fillthis('diagnosis_kerja')" style="height: 50px !important"><?php echo isset($value_form['diagnosis_kerja'])?$value_form['diagnosis_kerja']:''?></textarea>
<br>
<br>
<span><strong>DIAGNOSIS BANDING :</strong></span><br>
<textarea class="textarea-type" name="form_5[diagnosis_banding]" id="diagnosis_banding" onchange="fillthis('diagnosis_banding')" style="height: 80px !important"><?php echo isset($value_form['diagnosis_banding'])?$value_form['diagnosis_banding']:''?></textarea>
<br>
<br>

<span><strong>PENATALAKSANAAN :</strong></span><br>

Rencana Pemeriksaan<br>
<textarea class="textarea-type" name="form_5[rencana_pemeriksaan]" id="rencana_pemeriksaan" onchange="fillthis('rencana_pemeriksaan')" style="height: 70px !important"><?php echo isset($value_form['rencana_pemeriksaan'])?$value_form['rencana_pemeriksaan']:''?></textarea>
<br>
Rencana Terapi<br>
<textarea class="textarea-type" name="form_5[rencana_terapi]" id="rencana_terapi" onchange="fillthis('rencana_terapi')" style="height: 70px !important"><?php echo isset($value_form['rencana_terapi'])?$value_form['rencana_terapi']:''?></textarea>

<br><br>
<hr>
<?php echo $footer; ?>
