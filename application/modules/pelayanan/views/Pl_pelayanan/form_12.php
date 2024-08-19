<div style="text-align: center; font-size: 18px;"><b>LAPORAN OPERASI</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<table>
  <tr>
    <td style="width: 100px">Tanggal Operasi</td>
    <td>: <input type="text" class="input_type" name="form_12[tgl_operasi]" id="tgl_operasi" onchange="fillthis('tgl_operasi')"></td>
  </tr>
  <tr>
    <td style="width: 100px">Jam Operasi</td>
    <td>: <input type="text" class="input_type" name="form_12[jam_operasi]" id="jam_operasi" onchange="fillthis('jam_operasi')"></td>
  </tr>
  <tr>
    <td style="width: 100px">Jenis Operasi</td>
    <td></td>
  </tr>
  <tr>
    <td style="width: 100px">&nbsp;</td>
    <td>
      <div class="checkbox">
        <label>
            <input type="checkbox" class="ace" value="1" name="form_12[jenis_op_kecil]" id="jenis_op_kecil" onclick="checkthis('jenis_op_kecil')" >
            <span class="lbl"> Kecil</span>
        </label>

        <label>
            <input type="checkbox" class="ace" value="1" name="form_12[jenis_op_sedang]" id="jenis_op_sedang" onclick="checkthis('jenis_op_sedang')" >
            <span class="lbl"> Sedang</span>
        </label>

        <label>
            <input type="checkbox" class="ace" value="1" name="form_12[jenis_op_besar]" id="jenis_op_besar" onclick="checkthis('jenis_op_besar')" >
            <span class="lbl"> Besar</span>
        </label>

        <label>
            <input type="checkbox" class="ace" value="1" name="form_12[jenis_op_khusus]" id="jenis_op_khusus" onclick="checkthis('jenis_op_khusus')" >
            <span class="lbl"> Khusus</span>
        </label>
      </div>
      <div class="checkbox">
        <label>
            <input type="checkbox" class="ace" value="1" name="form_12[jenis_op_elektif]" id="jenis_op_elektif" onclick="checkthis('jenis_op_elektif')" >
            <span class="lbl"> Elektif</span>
        </label>
        <label>
            <input type="checkbox" class="ace" value="1" name="form_12[jenis_op_cito]" id="jenis_op_cito" onclick="checkthis('jenis_op_cito')" >
            <span class="lbl"> Cito</span>
        </label>
      </div>
    </td>
  </tr>

  <tr>
    <td style="width: 100px">Pemeriksaan PA</td>
    <td></td>
  </tr>
  <tr>
    <td style="width: 100px">&nbsp;</td>
    <td>
      <div class="checkbox">
        <label>
            <input type="checkbox" class="ace" value="1" name="form_12[pa_y]" id="pa_y" onclick="checkthis('pa_y')" >
            <span class="lbl"> Ya</span>
        </label>
        <label>
            <input type="checkbox" class="ace" value="1" name="form_12[pa_n]" id="pa_n" onclick="checkthis('pa_n')" >
            <span class="lbl"> Tidak</span>
        </label>
      </div>
    </td>
  </tr>

</table>
<br>
<p style="text-align: left;"><b>Diagnosa Pra Bedah </b>:&nbsp;</p>
<textarea class="textarea-type" name="form_12[diagnosa_pra_bedah]" id="diagnosa_pra_bedah" onchange="fillthis('diagnosa_pra_bedah')"></textarea>
<br>
<p style="text-align: left;"><b>Diagnosa Pasca Bedah </b>:&nbsp;</p>
<textarea class="textarea-type" name="form_12[diagnosa_pasca_bedah]" id="diagnosa_pasca_bedah" onchange="fillthis('diagnosa_pasca_bedah')"></textarea>
<br>
<p style="text-align: left;"><b>Tindakan </b>:&nbsp;</p>
<textarea class="textarea-type" name="form_12[tindakan]" id="tindakan" onchange="fillthis('tindakan')"></textarea>
<br>
<p style="text-align: left;"><b>Komplikasi </b>:&nbsp;</p>
<textarea class="textarea-type" name="form_12[komplikasi]" id="komplikasi" onchange="fillthis('komplikasi')"></textarea>
<br>
<p style="text-align: left;"><b>Prosedur Operasi</b> : </p>
<textarea class="textarea-type" name="form_12[prosedur_operasi]" id="prosedur_operasi" onchange="fillthis('prosedur_operasi')"></textarea>
<br>