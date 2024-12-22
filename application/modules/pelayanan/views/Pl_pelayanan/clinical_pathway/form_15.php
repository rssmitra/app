<?php echo $header; ?>
<hr>
<br>
<div style="text-align: center;"><b>HASIL PEMERIKSAAN DIAGNOSTIK NON INVASIF VASKULER</b></div>
<br><b>Pada pemeriksaan duplex sonography femoralis didapatkan :</b>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
Pada pemeriksaan duplex ekstremitas atas didapatkan :
<br>
<b>Arteri : </b>
<textarea class="textarea-type" rows="10" name="form_15[arteri]" id="arteri" onchange="fillthis('arteri')">
- Gambaran anatomi pembuluh darah arteri femoralis communis, arteri poplitea, arteri tibialis anterior-posterior, arteri dorsalis pedis bilateral rata dan tidak menebal
- Arteri femoralis communis, arteri poplitea, arteri tibialis anterior-posterior, arteri dorsalis pedis bilateral morfologi kurva Doppler ..............
- Colour coded mengisi penuh lumen pembuluh darah arteri femoralis communis, arteri poplitea, arteri tibialis anterior-posterior, arteri dorsalis pedis bilateral
</textarea>

<br>
<b>Vena : </b>
<textarea class="textarea-type" rows="10" name="form_15[vena]" id="vena" onchange="fillthis('vena')">
- Compressi Ultrasound (CUS) negative pada vena femoralis, vena poplitea bilateral
- Augmentasi (+) dengan uji squeeze distal pada vena femoralis, vena poplitea bilateral
- Reflux (-) pada kedua tungkai 
- Diameter GSV sinistra proximal mm, mid mm, distal mm
- Diameter GSV dextra proximal mm, mid mm, distal mm
</textarea>

<br>
<b>Kesimpulan : </b>
<textarea class="textarea-type" rows="10" name="form_15[kesimpulan]" id="kesimpulan" onchange="fillthis('kesimpulan')">
- Normal flow arteri pada kedua tungkai
- CVI (-) pada kedua tungkai
- DVT (-) pada kedua tungkai
</textarea>
<br>
<hr>
<?php echo $footer; ?>