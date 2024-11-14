<div style="text-align: center; font-size: 14px"><b>PENGKAJIAN AWAL<br>KEPERAWATAN RAWAT JALAN (BAYI/ ANAK)</b></div>
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
          <input type="checkbox" class="ace" name="form_24[ra_24_2]" id="ra_24_2"  onclick="checkthis('ra_24_2')">
          <span class="lbl"> Ada, sebutkan</span>
        </label>
        <input type="text" style="width: 40%" class="input_type" name="form_24[desc_ra_24_2]" id="desc_ra_24_2" onchange="fillthis('desc_ra_24_2')">
    </td>
  </tr>
</table>

<table border="0" style="width: 100%">
  <tr>
    <td style="vertical-align: middle; font-weight: bold; text-align: center" colspan="6"><br>PEMERIKSAAN FISIK DAN SKRINING GIZI<br><br></td>
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
<p>
  <ul>
    <li>Pengukuran tekanan darah hanya dilakukan pada kunjungan pertama anak (anak &#8805; 3 tahun), dan atas indikasi antara lain : ganggunan ginjal, hipertensi, syok annafilaktik, Dengue Hemorragic Fever, Diare dan lain- lain sesuai instruksi kerja</li>
    <li>Untuk pasien dibawah usia 2 tahun</li>
    <li>Ukur LILA bila pasien tidak memungkinkan diukur berat badannya</li>
  </ul>
</p>
<b><u>Malnutrition Screening STRONG - kids untuk 1 bulan - 14 tahun</u></b><br>
Parameter
<table>
  <tr>
    <td width="30px" valign="top">1.</td>
    <td width="80%">Apakah pasien tampak kurus ?</td>
    <td>
      <label>
          <input type="checkbox" class="ace" name="form_24[prm_24_1]" id="prm_24_1"  onclick="checkthis('prm_24_1')">
          <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_2]" id="prm_24_2"  onclick="checkthis('prm_24_2')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>

  <tr>
    <td width="30px" valign="top">2.</td>
    <td>Apakah terdapat penurunan berat badanselama satu bulan terakhir ?<br>(Berdasarkan penilaian objektif dan BB bila ada atau penilaian subjektif orang tua pasien atau untuk bayi &gt; 1 tahun : BB tidak naik selama 3 bulan terakhir)</td>
    <td>
      <label>
          <input type="checkbox" class="ace" name="form_24[prm_24_3]" id="prm_24_3"  onclick="checkthis('prm_24_3')">
          <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_4]" id="prm_24_4"  onclick="checkthis('prm_24_4')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>

  <tr>
    <td width="30px" valign="top">3.</td>
    <td>
      Apakah terdapat salah satu kondisi berikut ?<br>
      <li>Diare &#8805; 5 kali perhari dan atau muntah &gt; 3 kali per hari dalam seminggu terakhir</li>
      <li>Asupan makanan berkurang selama 1 minggu terakhir</li>
    </td>
    <td>
      <label>
          <input type="checkbox" class="ace" name="form_24[prm_24_5]" id="prm_24_5"  onclick="checkthis('prm_24_5')">
          <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_6]" id="prm_24_6"  onclick="checkthis('prm_24_6')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>

  <tr>
    <td width="30px" valign="top">4.</td>
    <td>
      Apakah terdapat penyakit atau keadaan yang mengakibatkan pasien berisiko mengalama mainutrisi ? (lihat Tabel 1)
    </td>
    <td>
      <label>
          <input type="checkbox" class="ace" name="form_24[prm_24_7]" id="prm_24_7"  onclick="checkthis('prm_24_7')">
          <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_8]" id="prm_24_8"  onclick="checkthis('prm_24_8')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>

  <tr>
    <td colspan="3">
      <b>Interpretasi Skor</b><br>
      <label>
          <input type="checkbox" class="ace" name="form_24[prm_24_9]" id="prm_24_9"  onclick="checkthis('prm_24_9')">
          <span class="lbl"> 0 (Resiko rendah)</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_10]" id="prm_24_10"  onclick="checkthis('prm_24_10')">
        <span class="lbl"> 1 - 3 (Resiko sedang)</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_11]" id="prm_24_11"  onclick="checkthis('prm_24_11')">
        <span class="lbl"> 4 - 5 (Resiko berat)</span>
      </label>
    </td>
  </tr>
</table>
<br>
<table border="0" style="background: #fff0f0;">
  <tr><td colspan="3" align="center"><p style="font-weight: bold; padding: 10px">Tabel 1. Daftar Penyakit / Keadaan yang berisiko mengakibatkan mainutrisi</p></td></tr>
  <tr>
    <td width="33%" valign="top">
      <ul>
        <li>Diare Krnoik (&#8805; 2 minggu)</li>
        <li>Penyakit jantung bawaan</li>
        <li>Infeksi HIV</li>
        <li>Kanker</li>
        <li>Penyakit Hati Kronik</li>
        <li>TB Paru</li>
      </ul>
    </td>
    <td width="33%" valign="top">
      <ul>
        <li>Luka bakar luas</li>
        <li>Keterlambatan perkembangan</li>
        <li>Kelainan anatomi daerah mulut yang menyebabkan kesulitan makan </li>
        <li>Kelainan metabolik bawaan </li>
        <li>Retardasi Mental </li>
      </ul>
    </td>
    <td width="33%" valign="top">
      <ul>
        <li>Trauma</li>
        <li>Rencana / paska operasi mayor</li>
        <li>Terpasang stoma</li>
        <li>lain-lain (pertimbangan dokter)</li>
      </ul>
    </td>
  </tr>
</table>
<br>
<table>
  <tr>
    <td colspan="2" align="center"><b>SKRINING RESIKO JATUH / CEDERA</b></td>
  </tr>
  <tr>
    <td width="30px">1.</td>
    <td>Semua pasien anak berisiko tinggi (beri edukasi pencegahan jatuh pada orang tua)</td>
  </tr>
  <tr>
    <td width="30px">2.</td>
    <td>
      Semua pasien anak &#8805; 12 - 17 tahun dilakukan skrining resiko cedera / jatuh dengan menggunakan <span style="font-weight: bold; font-style: italic">Timed Up and Go Test</span>
    </td>
  </tr>
  <tr>
    <td width="10px">&nbsp;</td>
    <td>
      a. Perhatikan cara berjalan pasien saat akan duduk dikursi. Apakah pasien tampak tidak seimbang (sempoyongan atau limbung)?<br>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_12]" id="prm_24_12"  onclick="checkthis('prm_24_12')">
        <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_13]" id="prm_24_13"  onclick="checkthis('prm_24_13')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>
  <tr>
    <td width="30px">&nbsp;</td>
    <td>
      b. Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang ketika akan duduk?<br>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_14]" id="prm_24_14"  onclick="checkthis('prm_24_14')">
        <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_15]" id="prm_24_15"  onclick="checkthis('prm_24_15')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <br><b>HASIL</b><br>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_16]" id="prm_24_16"  onclick="checkthis('prm_24_16')">
        <span class="lbl"> Tidak berisiko</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_17]" id="prm_24_17"  onclick="checkthis('prm_24_17')">
        <span class="lbl"> Risiko rendah (ditemukan a atau b)</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_18]" id="prm_24_18"  onclick="checkthis('prm_24_18')">
        <span class="lbl"> Risiko tinggi (ditemukan a dan b)</span>
      </label>
      <br>
    </td>
  </tr>

  <tr>
    <td colspan="2">
      <br>Dilaporkan ke dokter<br>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_19]" id="prm_24_19"  onclick="checkthis('prm_24_19')">
        <span class="lbl"> Ya</span>
      </label>
      <label>
        <input type="checkbox" class="ace" name="form_24[prm_24_20]" id="prm_24_20"  onclick="checkthis('prm_24_20')">
        <span class="lbl"> Tidak</span>
      </label>
    </td>
  </tr>

  <tr>
    <td colspan="2" align="center"><br><b>SKRINING NYERI</b><br></td>
  </tr>

  <tr>
    <td colspan="2">
      <label>
          <input type="checkbox" class="ace" name="form_24[prm_24_21]" id="prm_24_21"  onclick="checkthis('prm_24_21')">
          <span class="lbl"> Ya</span>
        </label>
        <label>
          <input type="checkbox" class="ace" name="form_24[prm_24_22]" id="prm_24_22"  onclick="checkthis('prm_24_22')">
          <span class="lbl"> Tidak</span>
        </label>
      <br>
      Bila Ya, lampirkan dan isi penilaian skala nyeri (Formulir penilaian Flacc Scale untuk anak dan formulir penilaian nyeri NIPS untuk neonatus)
    </td>
  </tr>
</table>
<br>
<table width="100%">
  <tr>
    <td width="50%" align="center">
      <b>DAFTAR MASALAH TERUKUR</b><br>
      <textarea name="form_24[daftar_masalah_terukur]" id="daftar_masalah_terukur"  onclick="checkthis('daftar_masalah_terukur')" class="" style="height: 50px !important; width: 98%"></textarea>
    </td>
    <td width="50%" align="center">
      <b>TUJUAN / TARGET TERUKUR</b><br>
      <textarea class="" name="form_24[target_terukur]" id="target_terukur"  onclick="checkthis('target_terukur')" style="height: 50px !important; width: 98%"></textarea>
    </td>
  </tr>

</table>
