<?php echo $header; ?>
<hr>
<br>
<div style="text-align: center; font-size: 14px"><b>PENGKAJIAN AWAL<br>KEPERAWATAN RAWAT JALAN DEWASA</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<table border="0" width="100%">
  <tr>
    <td style="vertical-align: middle">Keluhan Utama</td>
    <td colspan="3">: <input type="text" style="width: 80%" class="input_type" name="form_25[keluhan_utama_f25]" id="keluhan_utama_f25" onchange="fillthis('keluhan_utama_f25')"></td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Riwayat Penyakit</td>
    <td colspan="3">: <input type="text" style="width: 80%" class="input_type" name="form_25[riwayat_penyakit_f25]" id="riwayat_penyakit_f25" onchange="fillthis('riwayat_penyakit_f25')"></td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Riwayat Alergi</td>
    <td colspan="3">: 
      <label>
          <input type="checkbox" class="ace" name="form_25[ra_25_1]" id="ra_25_1"  onclick="checkthis('ra_25_1')">
          <span class="lbl"> Tidak ada</span>
      </label>
      <label>
          <input type="checkbox" class="ace" name="form_25[ra_25_2]" id="ra_25_2"  onclick="checkthis('ra_25_2')">
          <span class="lbl"> Ada, sebutkan</span>
        </label>
        <input type="text" style="width: 40%" class="input_type" name="form_25[desc_riwayat_alergi_25_2]" id="desc_riwayat_alergi_25_2" onchange="fillthis('desc_riwayat_alergi_25_2')">
    </td>
  </tr>
</table>

<br>
<table width="100%">
  <tr>
    <td colspan="2" align="center"><b>RIWAYAT PSIKOSOSIAL DAN EKONOMI</b></td>
  </tr>
  <tr>
    <td colspan="2"><b><u>Status Psikologis :</u></b></td>
  </tr>
  <tr>
    <td width="30%" valign="top">
      <label>
          <input type="checkbox" class="ace" name="form_25[status_psikologi_25_1]" id="status_psikologi_25_1"  onclick="checkthis('status_psikologi_25_1')">
          <span class="lbl"> Tenang</span>
      </label><br>
      <label>
          <input type="checkbox" class="ace" name="form_25[status_psikologi_25_2]" id="status_psikologi_25_2"  onclick="checkthis('status_psikologi_25_2')">
          <span class="lbl"> Cemas</span>
      </label><br>
      <label>
          <input type="checkbox" class="ace" name="form_25[status_psikologi_25_3]" id="status_psikologi_25_3"  onclick="checkthis('status_psikologi_25_3')">
          <span class="lbl"> Takut</span>
      </label><br>
      <label>
          <input type="checkbox" class="ace" name="form_25[status_psikologi_25_4]" id="status_psikologi_25_4"  onclick="checkthis('status_psikologi_25_4')">
          <span class="lbl"> Marah</span>
      </label>
      </td>
      <td valign="top">
      <label>
          <input type="checkbox" class="ace" name="form_25[status_psikologi_25_5]" id="status_psikologi_25_5"  onclick="checkthis('status_psikologi_25_5')">
          <span class="lbl"> Sedih</span>
      </label><br>
      <label>
          <input type="checkbox" class="ace" name="form_25[status_psikologi_25_6]" id="status_psikologi_25_6"  onclick="checkthis('status_psikologi_25_6')">
          <span class="lbl"> Kecenderungan bunuh diri dilaporkan ke</span>
      </label>
      <input type="text" style="width: 40%" class="input_type" name="form_25[status_psikologi_bd_25_6]" id="status_psikologi_bd_25_6" onchange="fillthis('status_psikologi_bd_25_6')">
      <label>
          <input type="checkbox" class="ace" name="form_25[status_psikologi_25_7]" id="status_psikologi_25_7"  onclick="checkthis('status_psikologi_25_7')">
          <span class="lbl"> Lain-lain</span>
      </label><br>
    </td>
  </tr>
  <tr>
    <td colspan="2"><br><b><u>Hubungan dengan anggota keluarga :</u></b></td>
  </tr>
  <tr>
    <td width="30%" valign="top">
      <label>
          <input type="checkbox" class="ace" name="form_25[hub_keluarga_25_1]" id="hub_keluarga_25_1"  onclick="checkthis('hub_keluarga_25_1')">
          <span class="lbl"> Baik</span>
      </label>
      <label>
          <input type="checkbox" class="ace" name="form_25[hub_keluarga_25_2]" id="hub_keluarga_25_2"  onclick="checkthis('hub_keluarga_25_2')">
          <span class="lbl"> Tidak Baik</span>
      </label>
      </td>
  </tr>

  <tr>
    <td colspan="2"><br><b><u>Status Pekerjaan :</u></b></td>
  </tr>
  <tr>
    <td colspan="2">
      <label>
          <input type="checkbox" class="ace" name="form_25[status_pekerjaan_25_1]" id="status_pekerjaan_25_1"  onclick="checkthis('status_pekerjaan_25_1')">
          <span class="lbl"> Belum Bekerja</span>
      </label>
      <label>
          <input type="checkbox" class="ace" name="form_25[status_pekerjaan_25_2]" id="status_pekerjaan_25_2"  onclick="checkthis('status_pekerjaan_25_2')">
          <span class="lbl"> Bekerja</span>
      </label>
      <label>
          <input type="checkbox" class="ace" name="form_25[status_pekerjaan_25_3]" id="status_pekerjaan_25_3"  onclick="checkthis('status_pekerjaan_25_3')">
          <span class="lbl"> Tidak Bekerja, jelaskan </span>
      </label>
      <input type="text" style="width: 40%" class="input_type" name="form_25[desc_tidak_bekerja_f25]" id="desc_tidak_bekerja_f25" onchange="fillthis('desc_tidak_bekerja_f25')">
    </td>
  </tr>
</table>

<table border="0" style="width: 100%">
  <tr>
    <td style="vertical-align: middle; font-weight: bold; text-align: center" colspan="6"><br>PEMERIKSAAN FISIK DAN SKRINING GIZI<br><br></td>
  </tr>

  <tr>
    <td style="vertical-align: middle;">Tekanan Darah</td>
    <td style="width: 150px">: <input type="text" class="input_type" name="form_25[td_1]" id="td_1" onchange="fillthis('td_1')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle; width: 100px">(mmHg)</td>
    <td style="vertical-align: middle">Tinggi Badan</td>
    <td style="width: 150px">: <input type="text" class="input_type" name="form_25[tb_1]" id="tb_1" onchange="fillthis('tb_1')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(cm)</td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Berat Badan</td>
    <td>: <input type="text" class="input_type" name="form_25[bb_1]" id="bb_1" onchange="fillthis('bb_1')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(Kg/Gr)</td>
    <td style="vertical-align: middle">Nadi</td>
    <td>: <input type="text" class="input_type" name="form_25[andi_1]" id="andi_1" onchange="fillthis('andi_1')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(x/min)</td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Pernafasan</td>
    <td>: <input type="text" class="input_type" name="form_25[nafas]" id="nafas" onchange="fillthis('nafas')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(x/min)</td>
    <td style="vertical-align: middle">Suhu</td>
    <td>: <input type="text" class="input_type" name="form_25[suhu]" id="suhu" onchange="fillthis('suhu')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(&#8451;)</td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Lingkar Kepala</td>
    <td>: <input type="text" class="input_type" name="form_25[lingkar_kpla]" id="lingkar_kpla" onchange="fillthis('lingkar_kpla')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(Cm)</td>
    <td style="vertical-align: middle">LILA ***</td>
    <td>: <input type="text" class="input_type" name="form_25[lila]" id="lila" onchange="fillthis('lila')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(Cm)</td>
  </tr>
</table>
<br>
<b><u>Malnutrition Screening Tools (MST)</u></b><br>
Parameter
<table width="100%">
  <tr>
    <td width="30px" valign="top">1.</td>
    <td width="80%">Apakah pasien mengalami penurunan BB yang tidaj diinginkan dalam 6 bulan terakhir ?
      <br>
      <label>
          <input type="checkbox" class="ace" name="form_25[prm_25_1]" id="prm_25_1"  onclick="checkthis('prm_25_1')">
          <span class="lbl"> Tidak ada penurunan berat badan</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[prm_25_2]" id="prm_25_2"  onclick="checkthis('prm_25_2')">
        <span class="lbl"> Tidak yakin / tidak tahu / terasa baju lebih longgar</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[prm_25_3]" id="prm_25_3"  onclick="checkthis('prm_25_3')">
        <span class="lbl"> Ya, berapa pernurunan berat badan tersebut ?</span>
      </label><br>
        <div style="padding-left: 20px">
          <label>
            <input type="checkbox" class="ace" name="form_25[prm_25_3_1]" id="prm_25_3_1"  onclick="checkthis('prm_25_3_1')">
            <span class="lbl"> 1 - 5 kg</span>
          </label>
          <label>
            <input type="checkbox" class="ace" name="form_25[prm_25_3_2]" id="prm_25_3_2"  onclick="checkthis('prm_25_3_2')">
            <span class="lbl"> 6 - 10 kg</span>
          </label>
          <label>
            <input type="checkbox" class="ace" name="form_25[prm_25_3_3]" id="prm_25_3_3"  onclick="checkthis('prm_25_3_3')">
            <span class="lbl"> 11 - 15 kg</span>
          </label>
          <label>
            <input type="checkbox" class="ace" name="form_25[prm_25_3_4]" id="prm_25_3_4"  onclick="checkthis('prm_25_3_4')">
            <span class="lbl"> > 15 kg</span>
          </label>
        </div>
    </td>
  </tr>

  <tr>
    <td width="30px" valign="top">2.</td>
    <td>Apakah asupan makanan berkurang karena tidak nafsu makan?</td>
    <td>
      <label>
          <input type="checkbox" class="ace" name="form_25[asupan_makanan_25_1]" id="asupan_makanan_25_1"  onclick="checkthis('asupan_makanan_25_1')">
          <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[asupan_makanan_25_2]" id="asupan_makanan_25_2"  onclick="checkthis('asupan_makanan_25_2')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>

  <tr>
    <td width="30px" valign="top">3.</td>
    <td>
      Pasien dengan diagnosa khusus ?<br>
      (DM / Kemoterapi / Hemodialisa / Geriatri / Imunitas menurun / lain-lain, sebutkan!)
      <input type="text" style="width: 40%" class="input_type" name="form_25[desc_diagnosa_khusus_25_1]" id="desc_diagnosa_khusus_25_1" onchange="fillthis('desc_diagnosa_khusus_25_1')">
    </td>
    <td>
      <label>
          <input type="checkbox" class="ace" name="form_25[diagnosa_khusus_25_1]" id="diagnosa_khusus_25_1"  onclick="checkthis('diagnosa_khusus_25_1')">
          <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[diagnosa_khusus_25_2]" id="diagnosa_khusus_25_2"  onclick="checkthis('diagnosa_khusus_25_2')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>
  <tr><td colspan="3"><span style="font-weight: bold; color: red;">(Bila skor &#8805; 2 dan atau pasien dengan diagnosis / kondisi khusus dilaporkan ke dokter pemeriksa)</span></td></tr>
  <tr>
    <td colspan="3">
      <br><b><u>Interpretasi Skor : </u></b><br>
      <label>
          <input type="checkbox" class="ace" name="form_25[prm_25_9]" id="prm_25_9"  onclick="checkthis('prm_25_9')">
          <span class="lbl"> 0 (Resiko rendah)</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[prm_25_10]" id="prm_25_10"  onclick="checkthis('prm_25_10')">
        <span class="lbl"> 1 - 3 (Resiko sedang)</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[prm_25_11]" id="prm_25_11"  onclick="checkthis('prm_25_11')">
        <span class="lbl"> 4 - 5 (Resiko berat)</span>
      </label>
    </td>
  </tr>
</table>
<br>
<!-- skrining status fungsional -->
<table width="100%">
  <tr>
    <td align="center"><b>SKRINING STATUS FUNGSIONAL</b></td>
  </tr>
  <tr>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_25[skrining_status_fungsional_25_1]" id="skrining_status_fungsional_25_1"  onclick="checkthis('skrining_status_fungsional_25_1')">
        <span class="lbl"> Mandiri</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[skrining_status_fungsional_25_2]" id="skrining_status_fungsional_25_2"  onclick="checkthis('skrining_status_fungsional_25_2')">
        <span class="lbl"> Ketergantungan total</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[skrining_status_fungsional_25_3]" id="skrining_status_fungsional_25_3"  onclick="checkthis('skrining_status_fungsional_25_3')">
        <span class="lbl"> Perlu bantuan, sebutkan </span>
      </label>
      <input type="text" style="width: 40%" class="input_type" name="form_25[desc_skrining_status_fungsional_25_3]" id="desc_skrining_status_fungsional_25_3" onchange="fillthis('desc_skrining_status_fungsional_25_3')">
      <br>
    </td>
  </tr>
</table>
<br>
<!-- skrining risiko jatuh dan cedera -->
<table width="100%">
  <tr>
    <td colspan="2" align="center"><b>SKRINING RESIKO JATUH / CEDERA</b><br></td>
  </tr>
  <tr>
    <td width="30px" valign="top">A. </td>
    <td>
      Apakah pasien tampak tidak seimbang (sempoyongan atau limbung) saat berjalan?<br>
      <label>
        <input type="checkbox" class="ace" name="form_25[skrining_resiko_jatuh_25_1]" id="skrining_resiko_jatuh_25_1"  onclick="checkthis('skrining_resiko_jatuh_25_1')">
        <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[skrining_resiko_jatuh_25_2]" id="skrining_resiko_jatuh_25_2"  onclick="checkthis('skrining_resiko_jatuh_25_2')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>
  <tr>
    <td width="30px" valign="top">B. </td>
    <td>
      Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang ketika akan duduk?<br>
      <label>
        <input type="checkbox" class="ace" name="form_25[skrining_resiko_jatuh_25_3]" id="skrining_resiko_jatuh_25_3"  onclick="checkthis('skrining_resiko_jatuh_25_3')">
        <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[skrining_resiko_jatuh_25_4]" id="skrining_resiko_jatuh_25_4"  onclick="checkthis('skrining_resiko_jatuh_25_4')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <br><b>HASIL</b><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[skrining_resiko_jatuh_25_5]" id="skrining_resiko_jatuh_25_5"  onclick="checkthis('skrining_resiko_jatuh_25_5')">
        <span class="lbl"> Tidak berisiko</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[skrining_resiko_jatuh_25_6]" id="skrining_resiko_jatuh_25_6"  onclick="checkthis('skrining_resiko_jatuh_25_6')">
        <span class="lbl"> Risiko rendah (ditemukan a atau b)</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[skrining_resiko_jatuh_25_7]" id="skrining_resiko_jatuh_25_7"  onclick="checkthis('skrining_resiko_jatuh_25_7')">
        <span class="lbl"> Risiko tinggi (ditemukan a dan b)</span>
      </label>
      <br>
    </td>
  </tr>

  <tr>
    <td colspan="2">
      <br>Dilaporkan ke dokter<br>
      <label>
        <input type="checkbox" class="ace" name="form_25[skrining_resiko_jatuh_25_8]" id="skrining_resiko_jatuh_25_8"  onclick="checkthis('skrining_resiko_jatuh_25_8')">
        <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[skrining_resiko_jatuh_25_9]" id="skrining_resiko_jatuh_25_9"  onclick="checkthis('skrining_resiko_jatuh_25_9')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>

  <tr>
    <td colspan="2" align="center"><br><b>PENILAIAN TINGKAT NYERI</b><br></td>
  </tr>

  <tr>
    <td colspan="2">
      <label>
          <input type="checkbox" class="ace" name="form_25[prm_25_21]" id="prm_25_21"  onclick="checkthis('prm_25_21')">
          <span class="lbl"> Ya</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_25[prm_25_22]" id="prm_25_22"  onclick="checkthis('prm_25_22')">
          <span class="lbl"> Tidak</span>
        </label>
      <br>
      Bila Ya, lampirkan dan isi penilaian skala nyeri (Formulir penilaian Flacc Scale untuk anak dan formulir penilaian nyeri NIPS untuk neonatus)
    </td>
  </tr>
</table>
<br>
<!-- skrining penilaian tingkat nyeri -->
<table width="100%">
  <tr><td align="center" colspan="2"><b>PENILAIAN TINGKAT NYERI</b></td></tr>
  <tr>
    <td width="150px">Keluhan nyeri</td>
    <td width="80%">
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_1]" id="penilaian_tingkat_nyeri_25_1"  onclick="checkthis('penilaian_tingkat_nyeri_25_1')">
        <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_2]" id="penilaian_tingkat_nyeri_25_2"  onclick="checkthis('penilaian_tingkat_nyeri_25_2')">
        <span class="lbl"> Tidak </span>
      </label>
    </td>
  </tr>
  <tr>
    <td width="150px">Pencetus / Provoke</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_provoke_1]" id="penilaian_tingkat_nyeri_25_provoke_1"  onclick="checkthis('penilaian_tingkat_nyeri_25_provoke_1')">
        <span class="lbl"> Benturan</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_provoke_2]" id="penilaian_tingkat_nyeri_25_provoke_2"  onclick="checkthis('penilaian_tingkat_nyeri_25_provoke_2')">
        <span class="lbl"> Tindakan </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_provoke_3]" id="penilaian_tingkat_nyeri_25_provoke_3"  onclick="checkthis('penilaian_tingkat_nyeri_25_provoke_3')">
        <span class="lbl"> Proses penyakit, </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_provoke_4]" id="penilaian_tingkat_nyeri_25_provoke_4"  onclick="checkthis('penilaian_tingkat_nyeri_25_provoke_4')">
        <span class="lbl"> Lain-lain </span>
        <input type="text" style="width: 40%" class="input_type" name="form_25[desc_penilaian_tingkat_nyeri_25_provoke_4]" id="desc_penilaian_tingkat_nyeri_25_provoke_4" onchange="fillthis('desc_penilaian_tingkat_nyeri_25_provoke_4')">
      </label>
    </td>
  </tr>
  <tr>
    <td width="150px" valign="top">Kualitas / Quality</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_qty_1]" id="penilaian_tingkat_nyeri_25_qty_1"  onclick="checkthis('penilaian_tingkat_nyeri_25_qty_1')">
        <span class="lbl"> Seperti tertusuk-tusuk tajam/tumpul</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_qty_2]" id="penilaian_tingkat_nyeri_25_qty_2"  onclick="checkthis('penilaian_tingkat_nyeri_25_qty_2')">
        <span class="lbl"> Berdenyut </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_qty_3]" id="penilaian_tingkat_nyeri_25_qty_3"  onclick="checkthis('penilaian_tingkat_nyeri_25_qty_3')">
        <span class="lbl"> Terbakar </span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_qty_4]" id="penilaian_tingkat_nyeri_25_qty_4"  onclick="checkthis('penilaian_tingkat_nyeri_25_qty_4')">
        <span class="lbl"> Tertindih benda berat </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_qty_5]" id="penilaian_tingkat_nyeri_25_qty_5"  onclick="checkthis('penilaian_tingkat_nyeri_25_qty_5')">
        <span class="lbl"> Diremas </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_qty_6]" id="penilaian_tingkat_nyeri_25_qty_6"  onclick="checkthis('penilaian_tingkat_nyeri_25_qty_6')">
        <span class="lbl"> Terpelintir </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_25_qty_7]" id="penilaian_tingkat_nyeri_25_qty_7"  onclick="checkthis('penilaian_tingkat_nyeri_25_qty_7')">
        <span class="lbl"> Teriris </span>
      </label>

    </td>
  </tr>
  <tr>
    <td width="150px">Radiasi / Region</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_radiasi_25_1]" id="penilaian_tingkat_nyeri_radiasi_25_1"  onclick="checkthis('penilaian_tingkat_nyeri_radiasi_25_1')">
        <span class="lbl"> Lokasi</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_radiasi_25_2]" id="penilaian_tingkat_nyeri_radiasi_25_2"  onclick="checkthis('penilaian_tingkat_nyeri_radiasi_25_2')">
        <span class="lbl"> Menyebar </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_radiasi_25_3]" id="penilaian_tingkat_nyeri_radiasi_25_3"  onclick="checkthis('penilaian_tingkat_nyeri_radiasi_25_3')">
        <span class="lbl"> Tidak </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_radiasi_25_4]" id="penilaian_tingkat_nyeri_radiasi_25_4"  onclick="checkthis('penilaian_tingkat_nyeri_radiasi_25_4')">
        <span class="lbl"> Ya </span>
      </label>
      <input type="text" style="width: 40%" class="input_type" name="form_25[desc_penilaian_tingkat_nyeri_radiasi_25_4]" id="desc_penilaian_tingkat_nyeri_radiasi_25_4" onchange="fillthis('desc_penilaian_tingkat_nyeri_radiasi_25_4')">
    </td>
  </tr>
  <tr>
    <td width="150px" valign="top">Skala / Severity</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_skala_25_1]" id="penilaian_tingkat_nyeri_skala_25_1"  onclick="checkthis('penilaian_tingkat_nyeri_skala_25_1')">
        <span class="lbl"> FLACSS</span>, Score
        <input type="text" style="width: 40%" class="input_type" name="form_25[score_flacss]" id="score_flacss" onchange="fillthis('score_flacss')">
      </label><br>
      <label>
        <span class="lbl" style="padding-left: 25px"> Wong Baker Faces</span>, Score
        <input type="text" style="width: 40%" class="input_type" name="form_25[score_wbf]" id="score_wbf" onchange="fillthis('score_wbf')">
      </label><br>
      <label>
        <span class="lbl" style="padding-left: 25px"> VAS/NRS</span>, Score
        <input type="text" style="width: 40%" class="input_type" name="form_25[score_vas_nrs]" id="score_vas_nrs" onchange="fillthis('score_vas_nrs')">
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[penilaian_tingkat_nyeri_skala_25_2]" id="penilaian_tingkat_nyeri_skala_25_2"  onclick="checkthis('penilaian_tingkat_nyeri_skala_25_2')">
        <span class="lbl"> BPS</span>, Score
        <input type="text" style="width: 40%" class="input_type" name="form_25[score_bps]" id="score_bps" onchange="fillthis('score_bps')">
      </label>
    </td>
  </tr>
  <tr>
    <td width="150px" valign="top">Durasi / Times</td>
    <td>
        Kapan mulai dirasa : 
        <input type="text" style="width: 50%" class="input_type" name="form_25[penilaian_tingkat_nyeri_durasi_1]" id="penilaian_tingkat_nyeri_durasi_1" onchange="fillthis('penilaian_tingkat_nyeri_durasi_1')">
        <br>
        Berapa lama dirasa / kekambuhan : 
        <input type="text" style="width: 50%" class="input_type" name="form_25[penilaian_tingkat_nyeri_durasi_2]" id="penilaian_tingkat_nyeri_durasi_2" onchange="fillthis('penilaian_tingkat_nyeri_durasi_2')">
    </td>
  </tr>
</table>
<br>
<!-- spiritual dan kultural -->
<table width="100%">
  <tr>
    <td align="center" colspan="2"><b>SPIRITUAL DAN KULTURAL</b></td>
  </tr>
  <tr>
    <td width="150px">Agama</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_25[agama_25_1]" id="agama_25_1"  onclick="checkthis('agama_25_1')">
        <span class="lbl"> Islam</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[agama_25_2]" id="agama_25_2"  onclick="checkthis('agama_25_2')">
        <span class="lbl"> Hindu</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[agama_25_3]" id="agama_25_3"  onclick="checkthis('agama_25_3')">
        <span class="lbl"> Kristen</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[agama_25_4]" id="agama_25_4"  onclick="checkthis('agama_25_4')">
        <span class="lbl"> Katolik</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[agama_25_5]" id="agama_25_5"  onclick="checkthis('agama_25_5')">
        <span class="lbl"> Budha</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_25[agama_25_6]" id="agama_25_6"  onclick="checkthis('agama_25_6')">
        <span class="lbl"> Lainnya</span>
        <input type="text" style="width: 40%" class="input_type" name="form_25[desc_agama_25_6]" id="desc_agama_25_6" onchange="fillthis('desc_agama_25_6')">
      </label>
    </td>
  </tr>
  <tr>
    <td width="150px" rowspan="2" valign="top">Nilai- nilai yang diyakini</td>
    <td width="80%">
      <label>
        <input type="checkbox" class="ace" name="form_25[nilai_diyakini_25_1]" id="nilai_diyakini_25_1"  onclick="checkthis('nilai_diyakini_25_1')">
        <span class="lbl"> Pantangan</span>
        <input type="text" style="width: 50%" class="input_type" name="form_25[desc_nilai_diyakini_25_1]" id="desc_nilai_diyakini_25_1" onchange="fillthis('desc_nilai_diyakini_25_1')">
      </label>
    </td>
  </tr>
  <tr>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_25[nilai_diyakini_25_2]" id="nilai_diyakini_25_2"  onclick="checkthis('nilai_diyakini_25_2')">
        <span class="lbl"> Tradisi</span>
        <input type="text" style="width: 50%" class="input_type" name="form_25[desc_nilai_diyakini_25_2]" id="desc_nilai_diyakini_25_2" onchange="fillthis('desc_nilai_diyakini_25_2')">
      </label>
    </td>
  </tr>
</table>
<br>
<!-- masalah keperawatan -->
<table width="100%">
  <tr>
    <td align="center" colspan="2"><b>MASALAH KEPERAWATAN</b></td>
  </tr>
  <tr>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_25[masalah_keperawatan_1]" id="masalah_keperawatan_1"  onclick="checkthis('masalah_keperawatan_1')">
        <span class="lbl"> Bersihin jalan nafas tidak efektif</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[masalah_keperawatan_2]" id="masalah_keperawatan_2"  onclick="checkthis('masalah_keperawatan_2')">
        <span class="lbl"> Pola nafas tidak efektif</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[masalah_keperawatan_3]" id="masalah_keperawatan_3"  onclick="checkthis('masalah_keperawatan_3')">
        <span class="lbl"> Gangguan sirkulasi spontan</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[masalah_keperawatan_4]" id="masalah_keperawatan_4"  onclick="checkthis('masalah_keperawatan_4')">
        <span class="lbl"> Risiko perfusi Cerebnal tidak efektif</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[masalah_keperawatan_5]" id="masalah_keperawatan_5"  onclick="checkthis('masalah_keperawatan_5')">
        <span class="lbl"> Nausea</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[masalah_keperawatan_6]" id="masalah_keperawatan_6"  onclick="checkthis('masalah_keperawatan_6')">
        <span class="lbl"> Ketidakstabilan kadar gula darah</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[masalah_keperawatan_7]" id="masalah_keperawatan_7"  onclick="checkthis('masalah_keperawatan_7')">
        <span class="lbl"> Diare</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[masalah_keperawatan_8]" id="masalah_keperawatan_8"  onclick="checkthis('masalah_keperawatan_8')">
        <span class="lbl"> Hypertemia</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[masalah_keperawatan_9]" id="masalah_keperawatan_9"  onclick="checkthis('masalah_keperawatan_9')">
        <span class="lbl"> Perfusi perifer tidak efektif</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[masalah_keperawatan_10]" id="masalah_keperawatan_10"  onclick="checkthis('masalah_keperawatan_10')">
        <span class="lbl"> &nbsp;</span>
        <input type="text" style="width: 60%" class="input_type" name="form_25[desc_masalah_keperawatan_10]" id="desc_masalah_keperawatan_10" onchange="fillthis('desc_masalah_keperawatan_10')">
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[masalah_keperawatan_11]" id="masalah_keperawatan_11"  onclick="checkthis('masalah_keperawatan_11')">
        <span class="lbl"> &nbsp;</span>
        <input type="text" style="width: 60%" class="input_type" name="form_25[desc_masalah_keperawatan_11]" id="desc_masalah_keperawatan_11" onchange="fillthis('desc_masalah_keperawatan_11')">
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_25[masalah_keperawatan_12]" id="masalah_keperawatan_12"  onclick="checkthis('masalah_keperawatan_12')">
        <span class="lbl"> &nbsp;</span>
        <input type="text" style="width: 60%" class="input_type" name="form_25[desc_masalah_keperawatan_12]" id="desc_masalah_keperawatan_12" onchange="fillthis('desc_masalah_keperawatan_12')">
      </label>
    </td>
  </tr>
</table>

<br>
<hr>
<?php echo $footer; ?>

