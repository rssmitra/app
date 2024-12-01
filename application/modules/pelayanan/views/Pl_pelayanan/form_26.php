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
    <td colspan="3">: <input type="text" style="width: 80%" class="input_type" name="form_26[keluhan_utama_f26]" id="keluhan_utama_f26" onchange="fillthis('keluhan_utama_f26')"></td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Riwayat Menstruasi</td>
    <td colspan="3">: <input type="text" style="width: 80%" class="input_type" name="form_26[riwayat_menstruasi_f26]" id="riwayat_menstruasi_f26" onchange="fillthis('riwayat_menstruasi_f26')"></td>
  </tr>
  <tr>
    <td style="vertical-align: middle">Usia Menarce</td>
    <td colspan="3">: <input type="text" style="width: 40%" class="input_type" name="form_26[usia_menarce_f26]" id="usia_menarce_f26" onchange="fillthis('usia_menarce_f26')"> tahun</td>
  </tr>
  <tr>
    <td style="vertical-align: middle">Lama Haid</td>
    <td colspan="3">: <input type="text" style="width: 40%" class="input_type" name="form_26[lama_haid_f26]" id="lama_haid_f26" onchange="fillthis('lama_haid_f26')"> hari</td>
  </tr>
  <tr>
    <td style="vertical-align: middle">Pasien sedang hamil</td>
    <td colspan="3">: 
      <label>
          <input type="checkbox" class="ace" name="form_26[pasien_hamil_1]" id="pasien_hamil_1"  onclick="checkthis('pasien_hamil_1')">
          <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[pasien_hamil_2]" id="pasien_hamil_2"  onclick="checkthis('pasien_hamil_2')">
        <span class="lbl"> Ya</span>
      </label>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: middle">Riwayat Alergi</td>
    <td colspan="3">: 
      <label>
          <input type="checkbox" class="ace" name="form_26[ra_26_1]" id="ra_26_1"  onclick="checkthis('ra_26_1')">
          <span class="lbl"> Tidak ada</span>
      </label>
      <label>
          <input type="checkbox" class="ace" name="form_26[ra_26_2]" id="ra_26_2"  onclick="checkthis('ra_26_2')">
          <span class="lbl"> Ada, sebutkan</span>
        </label>
        <input type="text" style="width: 40%" class="input_type" name="form_26[desc_riwayat_alergi_26_2]" id="desc_riwayat_alergi_26_2" onchange="fillthis('desc_riwayat_alergi_26_2')">
    </td>
  </tr>
</table>

<!-- riwayat psikososial dan ekonomi -->
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
          <input type="checkbox" class="ace" name="form_26[status_psikologi_26_1]" id="status_psikologi_26_1"  onclick="checkthis('status_psikologi_26_1')">
          <span class="lbl"> Tenang</span>
      </label><br>
      <label>
          <input type="checkbox" class="ace" name="form_26[status_psikologi_26_2]" id="status_psikologi_26_2"  onclick="checkthis('status_psikologi_26_2')">
          <span class="lbl"> Cemas</span>
      </label><br>
      <label>
          <input type="checkbox" class="ace" name="form_26[status_psikologi_26_3]" id="status_psikologi_26_3"  onclick="checkthis('status_psikologi_26_3')">
          <span class="lbl"> Takut</span>
      </label><br>
      <label>
          <input type="checkbox" class="ace" name="form_26[status_psikologi_26_4]" id="status_psikologi_26_4"  onclick="checkthis('status_psikologi_26_4')">
          <span class="lbl"> Marah</span>
      </label>
      </td>
      <td valign="top">
      <label>
          <input type="checkbox" class="ace" name="form_26[status_psikologi_26_5]" id="status_psikologi_26_5"  onclick="checkthis('status_psikologi_26_5')">
          <span class="lbl"> Sedih</span>
      </label><br>
      <label>
          <input type="checkbox" class="ace" name="form_26[status_psikologi_26_6]" id="status_psikologi_26_6"  onclick="checkthis('status_psikologi_26_6')">
          <span class="lbl"> Kecenderungan bunuh diri dilaporkan ke</span>
      </label>
      <input type="text" style="width: 40%" class="input_type" name="form_26[status_psikologi_bd_26_6]" id="status_psikologi_bd_26_6" onchange="fillthis('status_psikologi_bd_26_6')">
      <label>
          <input type="checkbox" class="ace" name="form_26[status_psikologi_26_7]" id="status_psikologi_26_7"  onclick="checkthis('status_psikologi_26_7')">
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
          <input type="checkbox" class="ace" name="form_26[hub_keluarga_26_1]" id="hub_keluarga_26_1"  onclick="checkthis('hub_keluarga_26_1')">
          <span class="lbl"> Baik</span>
      </label>
      <label>
          <input type="checkbox" class="ace" name="form_26[hub_keluarga_26_2]" id="hub_keluarga_26_2"  onclick="checkthis('hub_keluarga_26_2')">
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
          <input type="checkbox" class="ace" name="form_26[status_pekerjaan_26_1]" id="status_pekerjaan_26_1"  onclick="checkthis('status_pekerjaan_26_1')">
          <span class="lbl"> Belum Bekerja</span>
      </label>
      <label>
          <input type="checkbox" class="ace" name="form_26[status_pekerjaan_26_2]" id="status_pekerjaan_26_2"  onclick="checkthis('status_pekerjaan_26_2')">
          <span class="lbl"> Bekerja</span>
      </label>
      <label>
          <input type="checkbox" class="ace" name="form_26[status_pekerjaan_26_3]" id="status_pekerjaan_26_3"  onclick="checkthis('status_pekerjaan_26_3')">
          <span class="lbl"> Tidak Bekerja, jelaskan </span>
      </label>
      <input type="text" style="width: 40%" class="input_type" name="form_26[desc_tidak_bekerja_f26]" id="desc_tidak_bekerja_f26" onchange="fillthis('desc_tidak_bekerja_f26')">
    </td>
  </tr>
</table>
<!-- pemeriksaan fisik dan skrining gizi -->
<table border="0" style="width: 100%">
  <tr>
    <td style="vertical-align: middle; font-weight: bold; text-align: center" colspan="6"><br>PEMERIKSAAN FISIK DAN SKRINING GIZI<br><br></td>
  </tr>

  <tr>
    <td style="vertical-align: middle;">Tekanan Darah</td>
    <td style="width: 150px">: <input type="text" class="input_type" name="form_26[td_1]" id="td_1" onchange="fillthis('td_1')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle; width: 100px">(mmHg)</td>
    <td style="vertical-align: middle">Tinggi Badan</td>
    <td style="width: 150px">: <input type="text" class="input_type" name="form_26[tb_1]" id="tb_1" onchange="fillthis('tb_1')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(cm)</td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Berat Badan</td>
    <td>: <input type="text" class="input_type" name="form_26[bb_1]" id="bb_1" onchange="fillthis('bb_1')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(Kg/Gr)</td>
    <td style="vertical-align: middle">Nadi</td>
    <td>: <input type="text" class="input_type" name="form_26[andi_1]" id="andi_1" onchange="fillthis('andi_1')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(x/min)</td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Pernafasan</td>
    <td>: <input type="text" class="input_type" name="form_26[nafas]" id="nafas" onchange="fillthis('nafas')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(x/min)</td>
    <td style="vertical-align: middle">Suhu</td>
    <td>: <input type="text" class="input_type" name="form_26[suhu]" id="suhu" onchange="fillthis('suhu')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(&#8451;)</td>
  </tr>

  <tr>
    <td style="vertical-align: middle">Lingkar Kepala</td>
    <td>: <input type="text" class="input_type" name="form_26[lingkar_kpla]" id="lingkar_kpla" onchange="fillthis('lingkar_kpla')" style="width: 90%"></td>
    <td align="center" style="vertical-align: middle">(Cm)</td>
    <td style="vertical-align: middle">LILA ***</td>
    <td>: <input type="text" class="input_type" name="form_26[lila]" id="lila" onchange="fillthis('lila')" style="width: 90%"></td>
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
          <input type="checkbox" class="ace" name="form_26[prm_26_1]" id="prm_26_1"  onclick="checkthis('prm_26_1')">
          <span class="lbl"> Tidak ada penurunan berat badan</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_26[prm_26_2]" id="prm_26_2"  onclick="checkthis('prm_26_2')">
        <span class="lbl"> Tidak yakin / tidak tahu / terasa baju lebih longgar</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_26[prm_26_3]" id="prm_26_3"  onclick="checkthis('prm_26_3')">
        <span class="lbl"> Ya, berapa pernurunan berat badan tersebut ?</span>
      </label><br>
        <div style="padding-left: 20px">
          <label>
            <input type="checkbox" class="ace" name="form_26[prm_26_3_1]" id="prm_26_3_1"  onclick="checkthis('prm_26_3_1')">
            <span class="lbl"> 1 - 5 kg</span>
          </label>
          <label>
            <input type="checkbox" class="ace" name="form_26[prm_26_3_2]" id="prm_26_3_2"  onclick="checkthis('prm_26_3_2')">
            <span class="lbl"> 6 - 10 kg</span>
          </label>
          <label>
            <input type="checkbox" class="ace" name="form_26[prm_26_3_3]" id="prm_26_3_3"  onclick="checkthis('prm_26_3_3')">
            <span class="lbl"> 11 - 15 kg</span>
          </label>
          <label>
            <input type="checkbox" class="ace" name="form_26[prm_26_3_4]" id="prm_26_3_4"  onclick="checkthis('prm_26_3_4')">
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
          <input type="checkbox" class="ace" name="form_26[asupan_makanan_26_1]" id="asupan_makanan_26_1"  onclick="checkthis('asupan_makanan_26_1')">
          <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[asupan_makanan_26_2]" id="asupan_makanan_26_2"  onclick="checkthis('asupan_makanan_26_2')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>

  <tr>
    <td width="30px" valign="top">3.</td>
    <td>
      Pasien dengan diagnosa khusus ?<br>
      (DM / Kemoterapi / Hemodialisa / Geriatri / Imunitas menurun / lain-lain, sebutkan!)
      <input type="text" style="width: 40%" class="input_type" name="form_26[desc_diagnosa_khusus_26_1]" id="desc_diagnosa_khusus_26_1" onchange="fillthis('desc_diagnosa_khusus_26_1')">
    </td>
    <td>
      <label>
          <input type="checkbox" class="ace" name="form_26[diagnosa_khusus_26_1]" id="diagnosa_khusus_26_1"  onclick="checkthis('diagnosa_khusus_26_1')">
          <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[diagnosa_khusus_26_2]" id="diagnosa_khusus_26_2"  onclick="checkthis('diagnosa_khusus_26_2')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>
  <tr><td colspan="3"><span style="font-weight: bold; color: red;">(Bila skor &#8805; 2 dan atau pasien dengan diagnosis / kondisi khusus dilaporkan ke dokter pemeriksa)</span></td></tr>
  <tr>
    <td colspan="3">
      <br><b><u>Interpretasi Skor : </u></b><br>
      <label>
          <input type="checkbox" class="ace" name="form_26[prm_26_9]" id="prm_26_9"  onclick="checkthis('prm_26_9')">
          <span class="lbl"> 0 (Resiko rendah)</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[prm_26_10]" id="prm_26_10"  onclick="checkthis('prm_26_10')">
        <span class="lbl"> 1 - 3 (Resiko sedang)</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[prm_26_11]" id="prm_26_11"  onclick="checkthis('prm_26_11')">
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
        <input type="checkbox" class="ace" name="form_26[skrining_status_fungsional_26_1]" id="skrining_status_fungsional_26_1"  onclick="checkthis('skrining_status_fungsional_26_1')">
        <span class="lbl"> Mandiri</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_26[skrining_status_fungsional_26_2]" id="skrining_status_fungsional_26_2"  onclick="checkthis('skrining_status_fungsional_26_2')">
        <span class="lbl"> Ketergantungan total</span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_26[skrining_status_fungsional_26_3]" id="skrining_status_fungsional_26_3"  onclick="checkthis('skrining_status_fungsional_26_3')">
        <span class="lbl"> Perlu bantuan, sebutkan </span>
      </label>
      <input type="text" style="width: 40%" class="input_type" name="form_26[desc_skrining_status_fungsional_26_3]" id="desc_skrining_status_fungsional_26_3" onchange="fillthis('desc_skrining_status_fungsional_26_3')">
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
        <input type="checkbox" class="ace" name="form_26[skrining_resiko_jatuh_26_1]" id="skrining_resiko_jatuh_26_1"  onclick="checkthis('skrining_resiko_jatuh_26_1')">
        <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[skrining_resiko_jatuh_26_2]" id="skrining_resiko_jatuh_26_2"  onclick="checkthis('skrining_resiko_jatuh_26_2')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>
  <tr>
    <td width="30px" valign="top">B. </td>
    <td>
      Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang ketika akan duduk?<br>
      <label>
        <input type="checkbox" class="ace" name="form_26[skrining_resiko_jatuh_26_3]" id="skrining_resiko_jatuh_26_3"  onclick="checkthis('skrining_resiko_jatuh_26_3')">
        <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[skrining_resiko_jatuh_26_4]" id="skrining_resiko_jatuh_26_4"  onclick="checkthis('skrining_resiko_jatuh_26_4')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <br><b>HASIL</b><br>
      <label>
        <input type="checkbox" class="ace" name="form_26[skrining_resiko_jatuh_26_5]" id="skrining_resiko_jatuh_26_5"  onclick="checkthis('skrining_resiko_jatuh_26_5')">
        <span class="lbl"> Tidak berisiko</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[skrining_resiko_jatuh_26_6]" id="skrining_resiko_jatuh_26_6"  onclick="checkthis('skrining_resiko_jatuh_26_6')">
        <span class="lbl"> Risiko rendah (ditemukan a atau b)</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[skrining_resiko_jatuh_26_7]" id="skrining_resiko_jatuh_26_7"  onclick="checkthis('skrining_resiko_jatuh_26_7')">
        <span class="lbl"> Risiko tinggi (ditemukan a dan b)</span>
      </label>
      <br>
    </td>
  </tr>

  <tr>
    <td colspan="2">
      <br>Dilaporkan ke dokter<br>
      <label>
        <input type="checkbox" class="ace" name="form_26[skrining_resiko_jatuh_26_8]" id="skrining_resiko_jatuh_26_8"  onclick="checkthis('skrining_resiko_jatuh_26_8')">
        <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[skrining_resiko_jatuh_26_9]" id="skrining_resiko_jatuh_26_9"  onclick="checkthis('skrining_resiko_jatuh_26_9')">
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
          <input type="checkbox" class="ace" name="form_26[prm_26_21]" id="prm_26_21"  onclick="checkthis('prm_26_21')">
          <span class="lbl"> Ya</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_26[prm_26_22]" id="prm_26_22"  onclick="checkthis('prm_26_22')">
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
    <td width="150px">Metode</td>
    <td width="80%">
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_metode_1]" id="ptn_metode_1"  onclick="checkthis('ptn_metode_1')">
        <span class="lbl"> Numerik Rating Scale</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_metode_2]" id="ptn_metode_2"  onclick="checkthis('ptn_metode_2')">
        <span class="lbl"> Wong-Baker Face </span>
      </label>
    </td>
  </tr>
  <tr>
    <td width="150px">Lama Nyeri</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_lama_nyeri_1]" id="ptn_lama_nyeri_1"  onclick="checkthis('ptn_lama_nyeri_1')">
        <span class="lbl"> &lt; 6 minggu (Akut)</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_lama_nyeri_2]" id="ptn_lama_nyeri_2"  onclick="checkthis('ptn_lama_nyeri_2')">
        <span class="lbl"> &#8805; 6 minggu (Kronis) </span>
      </label>
    </td>
  </tr>
  <tr>
    <td width="150px" valign="top">Rasa Nyeri</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_rasa_nyeri_1]" id="ptn_rasa_nyeri_1"  onclick="checkthis('ptn_rasa_nyeri_1')">
        <span class="lbl"> Seperti ditusuk</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_rasa_nyeri_2]" id="ptn_rasa_nyeri_2"  onclick="checkthis('ptn_rasa_nyeri_2')">
        <span class="lbl"> Seperti dipukul </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_rasa_nyeri_3]" id="ptn_rasa_nyeri_3"  onclick="checkthis('ptn_rasa_nyeri_3')">
        <span class="lbl"> Seperti terbakar </span>
      </label><br>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_rasa_nyeri_4]" id="ptn_rasa_nyeri_4"  onclick="checkthis('ptn_rasa_nyeri_4')">
        <span class="lbl"> Seperti kram </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_rasa_nyeri_5]" id="ptn_rasa_nyeri_5"  onclick="checkthis('ptn_rasa_nyeri_5')">
        <span class="lbl"> Seperti diremas </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_rasa_nyeri_6]" id="ptn_rasa_nyeri_6"  onclick="checkthis('ptn_rasa_nyeri_6')">
        <span class="lbl"> Seperti berdenyut </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_rasa_nyeri_7]" id="ptn_rasa_nyeri_7"  onclick="checkthis('ptn_rasa_nyeri_7')">
        <span class="lbl"> Seperti tertimpa </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_rasa_nyeri_8]" id="ptn_rasa_nyeri_8"  onclick="checkthis('ptn_rasa_nyeri_8')">
        <span class="lbl"> Sulit dinilai </span>
      </label>

    </td>
  </tr>
  <tr>
    <td width="150px">Seberapa sering</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_durasi_nyeri_1]" id="ptn_durasi_nyeri_1"  onclick="checkthis('ptn_durasi_nyeri_1')">
        <span class="lbl"> &lt; 30 menit</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_durasi_nyeri_2]" id="ptn_durasi_nyeri_2"  onclick="checkthis('ptn_durasi_nyeri_2')">
        <span class="lbl"> &gt; 30 menit </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_durasi_nyeri_3]" id="ptn_durasi_nyeri_3"  onclick="checkthis('ptn_durasi_nyeri_3')">
        <span class="lbl"> 1 - 2 jam </span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_durasi_nyeri_4]" id="ptn_durasi_nyeri_4"  onclick="checkthis('ptn_durasi_nyeri_4')">
        <span class="lbl"> &gt; 2 jam </span>
      </label>
    </td>
  </tr>
  <tr>
    <td width="150px" valign="top">Apakah nyeri menjalar</td>
    <td>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_nyeri_menjalar_1]" id="ptn_nyeri_menjalar_1"  onclick="checkthis('ptn_nyeri_menjalar_1')">
        <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_nyeri_menjalar_2]" id="ptn_nyeri_menjalar_2"  onclick="checkthis('ptn_nyeri_menjalar_2')">
        <span class="lbl"> Tidak</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_nyeri_menjalar_3]" id="ptn_nyeri_menjalar_3"  onclick="checkthis('ptn_nyeri_menjalar_3')">
        <span class="lbl"> Sulit dinilai</span>
      </label>
    </td>
  </tr>
  <tr>
    <td width="150px" valign="top">Apakah yang membuat nyeri bertambah</td>
    <td valign="top">
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_nyeri_bertambah_1]" id="ptn_nyeri_bertambah_1"  onclick="checkthis('ptn_nyeri_bertambah_1')">
        <span class="lbl"> Aktifitas</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_nyeri_bertambah_2]" id="ptn_nyeri_bertambah_2"  onclick="checkthis('ptn_nyeri_bertambah_2')">
        <span class="lbl"> Istirahat</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_26[ptn_nyeri_bertambah_3]" id="ptn_nyeri_bertambah_3"  onclick="checkthis('ptn_nyeri_bertambah_3')">
        <span class="lbl"> Sulit dinilai</span>
      </label>
    </td>
  </tr>

</table>
<!-- form -->
<table width="100%">
  <tr>
    <td width="48%" align="center">
      <b>DAFTAR MASALAH KEPERAWATAN</b><br>
      <textarea name="form_26[daftar_masalah_terukur]" id="daftar_masalah_terukur"  onclick="checkthis('daftar_masalah_terukur')" class="textarea-type" style="height: 50px !important; width: 98%"></textarea>
    </td>
    <td width="48%" align="center">
      <b>TUJUAN / TARGET TERUKUR</b><br>
      <textarea class="textarea-type" name="form_26[target_terukur]" id="target_terukur"  onclick="checkthis('target_terukur')" style="height: 50px !important; width: 98%"></textarea>
    </td>
  </tr>

</table>
<br>
<hr>
<?php echo $footer; ?>