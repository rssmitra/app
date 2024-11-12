<div style="text-align: center; font-size: 16px"><b>PENGKAJIAN AWAL<br>KEPERAWATAN RAWAT JALAN (BAYI/ ANAK)</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<table border="0">
  <tr>
    <td style="vertical-align: middle">Keluhan Utama</td>
    <td colspan="3">: <input type="text" style="width: 80%" class="input_type" name="form_24[keluhan_utama_f24]" id="keluhan_utama_f24" onchange="fillthis('keluhan_utama_f24')"></td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Riwayat Penyakit</td>
    <td colspan="3">: <input type="text" style="width: 80%" class="input_type" name="form_24[riwayat_penyakit_f24]" id="riwayat_penyakit_f24" onchange="fillthis('riwayat_penyakit_f24')"></td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Riwayat Alergi</td>
    <td colspan="3">: 
      <label>
          <input type="checkbox" class="ace" name="form_24[ra_24_1]" id="ra_24_1"  onclick="checkthis('ra_24_1')">
          <span class="lbl"> Tidak ada</span>
      </label>
      <label>
          <input type="checkbox" class="ace" name="form_24[ra_24_1]" id="ra_24_1"  onclick="checkthis('ra_24_1')">
          <span class="lbl"> Ada, sebutkan</span>
        </label>
        <input type="text" style="width: 40%" class="input_type" name="form_24[riwayat_penyakit_f24]" id="riwayat_penyakit_f24" onchange="fillthis('riwayat_penyakit_f24')">
    </td>
  </tr>
</table>

<table border="0" style="width: 100%">
  <tr>
    <td style="vertical-align: middle; font-weight: bold; text-align: center" colspan="3"><br>PEMERIKSAAN FISIK DAN SKRINING GIZI<br></td>
  </tr>

  <tr>
    <td style="vertical-align: middle;">Tekanan Darah</td>
    <td style="width: 150px">: <input type="text" class="input_type" name="form_24[td_1]" id="td_1" onchange="fillthis('td_1')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle; width: 100px">(mmHg)</td>
    <td style="vertical-align: middle">Tinggi Badan</td>
    <td style="width: 150px">: <input type="text" class="input_type" name="form_24[tb_1]" id="tb_1" onchange="fillthis('tb_1')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(cm)</td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Berat Badan</td>
    <td>: <input type="text" class="input_type" name="form_24[bb_1]" id="bb_1" onchange="fillthis('bb_1')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(Kg/Gr)</td>
    <td style="vertical-align: middle">Nadi</td>
    <td>: <input type="text" class="input_type" name="form_24[andi_1]" id="andi_1" onchange="fillthis('andi_1')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(x/min)</td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Pernafasan</td>
    <td>: <input type="text" class="input_type" name="form_24[nafas]" id="nafas" onchange="fillthis('nafas')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(x/min)</td>
    <td style="vertical-align: middle">Suhu</td>
    <td>: <input type="text" class="input_type" name="form_24[suhu]" id="suhu" onchange="fillthis('suhu')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(&#8451;)</td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Lingkar Kepala</td>
    <td>: <input type="text" class="input_type" name="form_24[lingkar_kpla]" id="lingkar_kpla" onchange="fillthis('lingkar_kpla')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(Cm)</td>
    <td style="vertical-align: middle">LILA ***</td>
    <td>: <input type="text" class="input_type" name="form_24[lila]" id="lila" onchange="fillthis('lila')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(Cm)</td>
  </tr>
</table>
