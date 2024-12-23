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
<div style="text-align: center; font-size: 16px;"><b>RIWAYAT PENYAKIT PASIEN KASUS OBSTETRI<br>PEMERIKSAAN FISIK </b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<span style="font-style: italic; font-weight: bold"> Diisi oleh Dokter yang merawat</span>
<br>
<span style="font-weight: bold;">STATUS OBSTETRI :</span>
<br>
<span>Pemeriksaan Luar : </span>
<textarea class="textarea-type" name="form_6[pemeriksaan_luar]" id="pemeriksaan_luar" onchange="fillthis('pemeriksaan_luar')" style="height: 100px !important"><?php echo isset($value_form['anamnesis'])?$value_form['anamnesis']:''?></textarea>
<br>
<span>Pemeriksaan Dalam : </span>
<textarea class="textarea-type" name="form_6[pemeriksaan_dalam]" id="pemeriksaan_dalam" onchange="fillthis('pemeriksaan_dalam')" style="height: 100px !important"><?php echo isset($value_form['anamnesis'])?$value_form['anamnesis']:''?></textarea>
<br>
<br>
<span><strong>DIAGNOSIS KERJA :</strong></span>
<textarea class="textarea-type" name="form_6[diagnosa_kerja]" id="diagnosa_kerja" onchange="fillthis('diagnosa_kerja')" style="height: 100px !important"><?php echo isset($value_form['diagnosa_kerja'])?$value_form['diagnosa_kerja']:''?></textarea>
<br>
<br>

<span><strong>DIAGNOSIS BANDING :</strong></span>
<textarea class="textarea-type" name="form_6[diagnosa_banding]" id="diagnosa_banding" onchange="fillthis('diagnosa_banding')" style="height: 100px !important"><?php echo isset($value_form['diagnosa_banding'])?$value_form['diagnosa_banding']:''?></textarea>
<br>
<br>

<span><strong>PENATALAKSANAAN :</strong></span><br>
Rencana Pemeriksaan<br>
<textarea class="textarea-type" name="form_6[rencana_pemeriksaan]" id="rencana_pemeriksaan" onchange="fillthis('rencana_pemeriksaan')" style="height: 70px !important"><?php echo isset($value_form['rencana_pemeriksaan'])?$value_form['rencana_pemeriksaan']:''?></textarea>
<br>
Rencana Terapi<br>
<textarea class="textarea-type" name="form_6[rencana_terapi]" id="rencana_terapi" onchange="fillthis('rencana_terapi')" style="height: 70px !important"><?php echo isset($value_form['rencana_terapi'])?$value_form['rencana_terapi']:''?></textarea>


<span>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>
