<?php echo $header; ?>
<hr>
<br>
<div style="text-align: center; font-size: 18px;"><b>FORMULIR KLAIM RAWAT JALAN<br>INSTALASI FISIOTERAPI</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<!--MAIN CONTENT -->

<p>
    
    Anamnesa : <br>
    <textarea class="textarea-type" rows="3" style="min-height: 50px !important" name="form_44[anamnesa]" id="anamnesa" onchange="fillthis('anamnesa')"></textarea>
    <br>
    Pemeriksaan Fisik dan Uji Fungsi : <br>
    <textarea class="textarea-type" rows="3" style="min-height: 50px !important" name="form_44[pemeriksaan_fisik_fungsi]" id="pemeriksaan_fisik_fungsi" onchange="fillthis('pemeriksaan_fisik_fungsi')"></textarea>
    <br>
    Diagnosis Medis (ICD-10) : <br>
    <textarea class="textarea-type" rows="3" style="min-height: 50px !important" name="form_44[diag_medis_icd10]" id="diag_medis_icd10" onchange="fillthis('diag_medis_icd10')"></textarea>
    <br>
    Diagnosis Fungsi (ICD-10) : <br>
    <textarea class="textarea-type" rows="3" style="min-height: 50px !important" name="form_44[diag_fgs_icd10]" id="diag_fgs_icd10" onchange="fillthis('diag_fgs_icd10')"></textarea>
    <br>
    Pemeriksaan Penunjang : <br>
    <textarea class="textarea-type" rows="3" style="min-height: 50px !important" name="form_44[pemeriksaan_penunjang]" id="pemeriksaan_penunjang" onchange="fillthis('pemeriksaan_penunjang')"></textarea>
    <br>
    Tatalaksana Fisioterapis (ICD-9) : <br>
    <textarea class="textarea-type" rows="3" style="min-height: 50px !important" name="form_44[ttlksn_fso_icd9]" id="ttlksn_fso_icd9" onchange="fillthis('ttlksn_fso_icd9')"></textarea>
    <br>
    Anjuran : <br>
    <textarea class="textarea-type" rows="3" style="min-height: 50px !important" name="form_44[anjuran]" id="anjuran" onchange="fillthis('anjuran')"></textarea>
    <br>
    Evaluasi : <br>
    <textarea class="textarea-type" rows="3" style="min-height: 50px !important" name="form_44[evaluasi]" id="evaluasi" onchange="fillthis('evaluasi')"></textarea>


</p>

<!--END MAIN CONTENT -->

<?php echo $footer; ?>
