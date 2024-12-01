<?php echo $header; ?>
<hr>
<br>
<div style="text-align: center;"><b>HASIL PEMERIKSAAN USG</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<br>
<b>Hasil Pemeriksaan :</b>
<br>
<textarea class="textarea-type" name="form_14[hasil_pemeriksaan_usg]" id="hasil_pemeriksaan_usg" onchange="fillthis('hasil_pemeriksaan_usg')" style="height: 100px !important; width: 100%"></textarea>
<br>
<br>
<b>Kesan :</b>
<br>
<textarea class="textarea-type" name="form_14[kesan_usg]" id="kesan_usg" onchange="fillthis('kesan_usg')" style="height: 100px !important; width: 100%"></textarea>
<br>
<br>
<b>Catatan Pemeriksaan :</b>
<br>
<textarea class="textarea-type" name="form_14[catatan_usg]" id="catatan_usg" onchange="fillthis('catatan_usg')" style="height: 100px !important; width: 100%"></textarea>
<br>
<br>
<hr>
<?php echo $footer; ?>

