<?php echo $header; ?>
<hr>
<br>

<div style="text-align: center;"><b>HASIL PEMERIKSAAN DIAGNOSTIK NON INVASIF VASKULER</b></div>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<br>
Pada pemeriksaan duplex ekstremitas atas didapatkan :
<br>
<br>
<b>Arteri : </b>
<textarea class="textarea-type" rows="10" name="form_16[arteri]" id="arteri" onchange="fillthis('arteri')">
- Gambaran anatomi pembuluh darah arteri subclavia, arteri axilaris, arteri brachialis, arteri radialis, arteri ulnaris bilateral rata dan tidak menebal
- Arteri subclavia, arteri axilaris, arteri brachialis, arteri radialis, arteri ulnaris bilateral morfologi kurva Doppler triphasic
</textarea>

<br>
<b>Vena : </b>
<textarea class="textarea-type" rows="10" name="form_16[vena]" id="vena" onchange="fillthis('vena')">
- Compressi Ultrasound (CUS) negatif pada vena brachialis bilateral
- Augmentasi (+) dengan uji squeeze distal pada vena brachialis bilateral
</textarea>

<br>
<b>Kesimpulan : </b>
<textarea class="textarea-type" rows="10" name="form_16[kesimpulan]" id="kesimpulan" onchange="fillthis('kesimpulan')">
- Normal flow arteri dan vena pada kedua ekstremitas atas
- Tidak ditemukan thrombosis (DVT) pada vena dalam di kedua ekstremitas atas
</textarea>
<br>
<hr>
<?php echo $footer; ?>
