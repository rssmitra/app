<?php echo $header; ?>
<hr><br>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<body>
<br>
<div style="text-align: center; font-size: 16px;">
  <b>LAPORAN PEMAKAIAN OBAT / ALKES DI KAMAR BEDAH</b>
</div>

<br>

<table border="1" width="100%" style="border-collapse: collapse; font-size: 13px; text-align: center;">
  <thead style="font-weight:bold; background-color:#d9dbda;">
    <tr>
      <th rowspan="2" style="width:150px; vertical-align:middle; text-align: center;">HITUNGAN</th>
      <th colspan="5" style="vertical-align:middle; text-align: center;">JUMLAH</th>
      <th rowspan="2" style="width:200px; vertical-align:middle; text-align: center;">KETERANGAN</th>
    </tr>
    <tr>
      <th style="width:80px; text-align: center;">KASA</th>
      <th style="width:80px; text-align: center;">TUFFER</th>
      <th style="width:80px; text-align: center;">SEGI 4</th>
      <th style="width:80px; text-align: center;">ROL</th>
      <th style="width:80px; text-align: center;">TAMPON</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="text-align:left; padding:5px;">Sebelum Operasi</td>
      <td><input type="text" class="input_type" name="form_121[kasa_sbl]" id="kasa_sbl" style="width:100%;"></td>
      <td><input type="text" class="input_type" name="form_121[tuffer_sbl]" id="tuffer_sbl" style="width:100%;"></td>
      <td><input type="text" class="input_type" name="form_121[segi4_sbl]" id="segi4_sbl" style="width:100%;"></td>
      <td><input type="text" class="input_type" name="form_121[rol_sbl]" id="rol_sbl" style="width:100%;"></td>
      <td><input type="text" class="input_type" name="form_121[tampon_sbl]" id="tampon_sbl" style="width:100%;"></td>
      <td rowspan="2" style="vertical-align:top;">
        <table style="width:100%; border-collapse:collapse; font-size:13px; text-align:left;">
          <tr><td style="width:120px; padding:5px;">Jam Mulai</td><td style="width:10px;">:</td><td><input type="text" class="input_type" name="form_121[jam_mulai]" id="jam_mulai" style="width:100%;"></td></tr>
          <tr><td style="padding:5px;">Jam Berakhir</td><td>:</td><td><input type="text" class="input_type" name="form_121[jam_berakhir]" id="jam_berakhir" style="width:100%;"></td></tr>
          <tr><td style="padding:5px;">Suhu Ruangan</td><td>:</td><td><input type="text" class="input_type" name="form_121[suhu]" id="suhu" style="width:100%;"></td></tr>
          <tr><td style="padding:5px;">Kelembaban</td><td>:</td><td><input type="text" class="input_type" name="form_121[kelembaban]" id="kelembaban" style="width:100%;"></td></tr>
        </table>
      </td>
    </tr>
    <tr>
      <td style="text-align:left; padding:5px;">Sesudah Operasi</td>
      <td><input type="text" class="input_type" name="form_121[kasa_ssd]" id="kasa_ssd" style="width:100%;"></td>
      <td><input type="text" class="input_type" name="form_121[tuffer_ssd]" id="tuffer_ssd" style="width:100%;"></td>
      <td><input type="text" class="input_type" name="form_121[segi4_ssd]" id="segi4_ssd" style="width:100%;"></td>
      <td><input type="text" class="input_type" name="form_121[rol_ssd]" id="rol_ssd" style="width:100%;"></td>
      <td><input type="text" class="input_type" name="form_121[tampon_ssd]" id="tampon_ssd" style="width:100%;"></td>
    </tr>
  </tbody>
</table>

<br>

<table border="1" width="100%" style="border-collapse: collapse; font-size: 13px;">
  <tbody>
    <tr>
      <td style="width:30px; padding:5px;">Operator</td>
      <td style="width:40%; padding:5px;"><input type="text" class="input_type" name="form_121[operator_1]" id="operator_1" style="width:100%;" placeholder="Isi Operator 1"></td>
      <td style="width:40%; padding:5px;"><input type="text" class="input_type" name="form_121[operator_2]" id="operator_2" style="width:100%;" placeholder="Isi Operator 2"></td>
    </tr>
    <tr>
      <td style="padding:5px;">Asisten Operator</td>
      <td style="padding:5px;" colspan="2"><input type="text" class="input_type" name="form_121[asisten]" id="asisten" style="width:100%;"></td>
    </tr>
    <tr>
      <td style="padding:5px;">Instrumentator</td>
      <td  style="padding:5px;"colspan="2"><input type="text" class="input_type" name="form_121[instrumentator]" id="instrumentator" style="width:100%;"></td>
    </tr>
    <tr>
      <td style="padding:5px;">Perawat Sirkuler</td>
      <td style="padding:5px;">
        <input type="text" class="input_type" name="form_121[perawat_sirkuler]" id="perawat_sirkuler" style="width:100%;">
      </td>
      <td style="padding:5px;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <tr>
            <td style="width:120px;">Kategori Operasi</td>
            <td style="width:10px;">:</td>
            <td>
            <label><input type="checkbox" class="ace" name="form_121[kategori_operasi][]" id="operasi_k" onclick="checkthis('operasi_k')" value="K"> 
              <span class="lbl">K</span></label>

              <label><input type="checkbox" class="ace" name="form_121[kategori_operasi][]" id="operasi_s" onclick="checkthis('operasi_s')" value="S"> 
              <span class="lbl">S</span></label>

            <label><input type="checkbox" class="ace" name="form_121[kategori_operasi][]" id="operasi_b" onclick="checkthis('operasi_b')" value="B"> 
              <span class="lbl">B</span></label>

              <label><input type="checkbox" class="ace" name="form_121[kategori_operasi][]" id="operasi_kh" onclick="checkthis('operasi_kh')" value="KH"> 
              <span class="lbl">KH</span></label>
            <br>  
              <label><input type="checkbox" class="ace" name="form_121[kategori_operasi][]" id="operasi_cito" onclick="checkthis('operasi_cito')" value="CITO"> 
              <span class="lbl">CITO</span></label>
              <label><input type="checkbox" class="ace" name="form_121[kategori_operasi][]" id="operasi_elektif" onclick="checkthis('operasi_elektif')" value="ELEKTIF"> 
              <span class="lbl">ELEKTIF</span></label>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td style="padding:5px;">Dokter Anestesi</td>
      <td style="padding:5px;">
        <input type="text" class="input_type" name="form_121[dokter_anestesi]" id="dokter_anestesi" style="width:100%;">
      </td>
      <td style="padding:5px;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
          <tr>
            <td style="width:120px;">Jaringan PA / VC</td>
            <td style="width:10px;">:</td>
            <td><input type="text" class="input_type" name="form_121[jaringan_pa]" id="jaringan_pa" style="width:100%;"></td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td style="padding:5px;">Penata Anestesi</td>
      <td style="padding:5px;"><input type="text" class="input_type" name="form_121[penata_anestesi]" id="penata_anestesi" style="width:100%;"></td> 
    </tr>
    <tr>
      <td style="padding:5px;">Jenis Narkose</td>
      <td style="padding:5px;"><input type="text" class="input_type" name="form_121[jenis_narkose]" id="jenis_narkose" style="width:100%;"></td>
    </tr>
  </tbody>
</table>

<br>

<!-- ========================== -->
<!--   PEMAKAIAN MEDICAL SUPPLY -->
<!-- ========================== -->

<table border="1" width="100%" style="border-collapse: collapse; font-size: 13px;">
  <thead style="font-weight:bold; background-color:#d9dbda; text-align:center;">
    <tr>
      <th style="text-align:center;" colspan="4">PEMAKAIAN MEDICAL SUPPLY</th>
    </tr>
  </thead>
  <tbody>

<!-- OBAT BIUS -->
 <tr>
  <td colspan="6" style="font-weight:bold; text-align:left; border-top:1px solid #000;padding:4px;">1. OBAT</td>
</tr>
<tr>
  <td colspan="6" style="font-weight:bold; text-align:left; border-top:1px solid #000;padding:4px; font-size: 12px;">* OBAT BIUS / GAS</td>
</tr>
<tr>
  <!-- Tabel Kiri (1–4) -->
  <td colspan="4" style="vertical-align:top; border-right:1px solid #000; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr>
        <td style="width:30px; padding:4px;">N₂O</td>
        <td><input type="text" class="input_type" name="form_121[n2o]" id="n2o" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="padding:4px;">O₂</td>
        <td><input type="text" class="input_type" name="form_121[o2]" id="o2" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="padding:4px;">HALOTHANE</td>
        <td><input type="text" class="input_type" name="form_121[halothane]" id="halothane" style="width:100%;"></td>
      </tr>
    </table>
  </td>

</tr>

<!-- OBAT INJEKSI -->
<tr>
  <td colspan="6" style="font-weight:bold; text-align:left; border-top:1px solid #000;padding:4px; font-size: 12px;">* OBAT INJEKSI</td>
</tr>
<tr>
  <!-- Tabel Kiri (1–4) -->
  <td colspan="3" style="vertical-align:top; border-right:1px solid #000; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr>
        <td style="width:30px; padding:4px;">1.</td>
        <td><input type="text" class="input_type" name="form_121[obat_injeksi_1]" id="obat_injeksi_1" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="padding:4px;">2.</td>
        <td><input type="text" class="input_type" name="form_121[obat_injeksi_2]" id="obat_injeksi_2" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="padding:4px;">3.</td>
        <td><input type="text" class="input_type" name="form_121[obat_injeksi_3]" id="obat_injeksi_3" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="padding:4px;">4.</td>
        <td><input type="text" class="input_type" name="form_121[obat_injeksi_4]" id="obat_injeksi_4" style="width:100%;"></td>
      </tr>
    </table>
  </td>

  <!-- Tabel Kanan (5–8) -->
  <td colspan="3" style="vertical-align:top; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr>
        <td style="width:30px; padding:4px;">5.</td>
        <td><input type="text" class="input_type" name="form_121[obat_injeksi_5]" id="obat_injeksi_5" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="padding:4px;">6.</td>
        <td><input type="text" class="input_type" name="form_121[obat_injeksi_6]" id="obat_injeksi_6" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="padding:4px;">7.</td>
        <td><input type="text" class="input_type" name="form_121[obat_injeksi_7]" id="obat_injeksi_7" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="padding:4px;">8.</td>
        <td><input type="text" class="input_type" name="form_121[obat_injeksi_8]" id="obat_injeksi_8" style="width:100%;"></td>
      </tr>
    </table>
  </td>
</tr>

<!-- OBAT SUPP -->
<tr>
  <td colspan="6" style="font-weight:bold; text-align:left; border-top:1px solid #000;padding:4px; font-size: 12px;">* OBAT SUPP</td>
</tr>
<tr>
  <!-- Kolom Kiri (1) -->
  <td colspan="3" style="vertical-align:top; border-right:1px solid #000; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr>
        <td style="width:30px; padding:4px;">1.</td>
        <td><input type="text" class="input_type" name="form_121[obat_supp_1]" id="obat_supp_1" style="width:100%;"></td>
      </tr>
    </table>
  </td>

  <!-- Kolom Kanan (2) -->
  <td colspan="3" style="vertical-align:top; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr>
        <td style="width:30px; padding:4px;">2.</td>
        <td><input type="text" class="input_type" name="form_121[obat_supp_2]" id="obat_supp_2" style="width:100%;"></td>
      </tr>
    </table>
  </td>
</tr>

<!-- 2. CAIRAN -->
<tr>
  <td colspan="6" style="font-weight:bold; text-align:left; border-top:1px solid #000;padding:4px;">2. CAIRAN :</td>
</tr>
<tr>
  <!-- Kolom Kiri (1,2,3) -->
  <td colspan="3" style="vertical-align:top; border-right:1px solid #000; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr>
        <td style="width:30px; padding:4px;">1.</td>
        <td><input type="text" class="input_type" name="form_121[cairan_1]" id="cairan_1" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="padding:4px;">2.</td>
        <td><input type="text" class="input_type" name="form_121[cairan_2]" id="cairan_2" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="padding:4px;">3.</td>
        <td><input type="text" class="input_type" name="form_121[cairan_3]" id="cairan_3" style="width:100%;"></td>
      </tr>
      <!-- <tr>
        <td colspan="2" style="text-align:right; padding-right:10px;">JUMLAH :</td>
      </tr> -->
    </table>
  </td>

  <!-- Kolom Kanan (4,5,6) -->
  <td colspan="3" style="vertical-align:top; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr>
        <td style="width:30px; padding:4px;">4.</td>
        <td><input type="text" class="input_type" name="form_121[cairan_4]" id="cairan_4" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="padding:4px;">5.</td>
        <td><input type="text" class="input_type" name="form_121[cairan_5]" id="cairan_5" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="padding:4px;">6.</td>
        <td><input type="text" class="input_type" name="form_121[cairan_6]" id="cairan_6" style="width:100%;"></td>
      </tr>
      <tr>
        <td style="width:30px;text-align:left;padding:4px;">JUMLAH:</td>
        <td><input type="text" class="input_type" name="form_121[jumlah_cairan]" id="jumlah_cairan" style="width:100%;"></td>
      </tr>
    </table>
  </td>
</tr>


<!-- 3. ALKES -->
<tr>
  <td colspan="6" style="font-weight:bold; text-align:left; border-top:1px solid #000;padding:4px;">3. ALKES :</td>
</tr>
<tr>
  <!-- Kolom Kiri (1–7) -->
  <td colspan="3" style="vertical-align:top; border-right:1px solid #000; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr><td style="width:30px; padding:4px;">1.</td><td><input type="text" class="input_type" name="form_121[alkes_1]" id="alkes_1" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">2.</td><td><input type="text" class="input_type" name="form_121[alkes_2]" id="alkes_2" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">3.</td><td><input type="text" class="input_type" name="form_121[alkes_3]" id="alkes_3" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">4.</td><td><input type="text" class="input_type" name="form_121[alkes_4]" id="alkes_4" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">5.</td><td><input type="text" class="input_type" name="form_121[alkes_5]" id="alkes_5" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">6.</td><td><input type="text" class="input_type" name="form_121[alkes_6]" id="alkes_6" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">7.</td><td><input type="text" class="input_type" name="form_121[alkes_7]" id="alkes_7" style="width:100%;"></td></tr>
      <!-- <tr><td colspan="2" style="text-align:left;padding:4px;">JUMLAH :</td></tr> -->
    </table>
  </td>

  <!-- Kolom Kanan (8–14) -->
  <td colspan="3" style="vertical-align:top; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr><td style="width:30px; padding:4px;">8.</td><td><input type="text" class="input_type" name="form_121[alkes_8]" id="alkes_8" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">9.</td><td><input type="text" class="input_type" name="form_121[alkes_9]" id="alkes_9" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">10.</td><td><input type="text" class="input_type" name="form_121[alkes_10]" id="alkes_10" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">11.</td><td><input type="text" class="input_type" name="form_121[alkes_11]" id="alkes_11" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">12.</td><td><input type="text" class="input_type" name="form_121[alkes_12]" id="alkes_12" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">13.</td><td><input type="text" class="input_type" name="form_121[alkes_13]" id="alkes_13" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">14.</td><td><input type="text" class="input_type" name="form_121[alkes_14]" id="alkes_14" style="width:100%;"></td></tr>
      <tr>
        <td style="width:30px;text-align:left;padding:4px;">JUMLAH:</td>
        <td><input type="text" class="input_type" name="form_121[jumlah_alkes]" id="jumlah_alkes" style="width:100%;"></td>
    </tr>
    </table>
  </td>
</tr>


<!-- 4. BENANG -->
<tr>
  <td colspan="6" style="font-weight:bold; text-align:left; border-top:1px solid #000;padding:4px;">4. BENANG :</td>
</tr>
<tr>
  <!-- Kolom Kiri (1–4) -->
  <td colspan="3" style="vertical-align:top; border-right:1px solid #000; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr><td style="width:30px;padding:4px;">1.</td><td><input type="text" class="input_type" name="form_121[benang_1]" id="benang_1" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">2.</td><td><input type="text" class="input_type" name="form_121[benang_2]" id="benang_2" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">3.</td><td><input type="text" class="input_type" name="form_121[benang_3]" id="benang_3" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">4.</td><td><input type="text" class="input_type" name="form_121[benang_4]" id="benang_4" style="width:100%;"></td></tr>
      <!-- <tr><td colspan="2" style="text-align:left;padding:4px;">JUMLAH :</td></tr> -->
    </table>
  </td>

  <!-- Kolom Kanan (5–8) -->
  <td colspan="3" style="vertical-align:top; padding:0;">
    <table style="width:100%; border-collapse:collapse;padding:4px;">
      <tr><td style="width:30px;padding:4px;">5.</td><td><input type="text" class="input_type" name="form_121[benang_5]" id="benang_5" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">6.</td style="padding:4px;"><td><input type="text" class="input_type" name="form_121[benang_6]" id="benang_6" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">7.</td style="padding:4px;"><td><input type="text" class="input_type" name="form_121[benang_7]" id="benang_7" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">8.</td style="padding:4px;"><td><input type="text" class="input_type" name="form_121[benang_8]" id="benang_8" style="width:100%;"></td></tr>
      <tr>
        <td style="width:30px;text-align:left;padding:4px;">JUMLAH:</td>
        <td><input type="text" class="input_type" name="form_121[jumlah_benang]" id="jumlah_benang" style="width:100%;"></td>
    </tr>
    </table>
  </td>
</tr>


<!-- 5. IMPLANT -->
<tr>
  <td colspan="6" style="font-weight:bold; text-align:left; border-top:1px solid #000;padding:4px;">5. IMPLANT :</td>
</tr>
<tr>
  <!-- Kolom Kiri (1–4) -->
  <td colspan="3" style="vertical-align:top; border-right:1px solid #000; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr><td style="width:30px;padding:4px;">1.</td><td><input type="text" class="input_type" name="form_121[implant_1]" id="implant_1" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">2.</td><td><input type="text" class="input_type" name="form_121[implant_2]" id="implant_2" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">3.</td><td><input type="text" class="input_type" name="form_121[implant_3]" id="implant_3" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">4.</td><td><input type="text" class="input_type" name="form_121[implant_4]" id="implant_4" style="width:100%;"></td></tr>
      <!-- <tr><td colspan="2" style="text-align:left;padding:4px;">JUMLAH :</td></tr> -->
    </table>
  </td>

  <!-- Kolom Kanan (5–8) -->
  <td colspan="3" style="vertical-align:top; padding:0;">
    <table style="width:100%; border-collapse:collapse;padding:4px;">
      <tr><td style="width:30px;padding:4px;">5.</td><td><input type="text" class="input_type" name="form_121[implant_5]" id="implant_5" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">6.</td><td><input type="text" class="input_type" name="form_121[implant_6]" id="implant_6" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">7.</td><td><input type="text" class="input_type" name="form_121[implant_7]" id="implant_7" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">8.</td><td><input type="text" class="input_type" name="form_121[implant_8]" id="implant_8" style="width:100%;"></td></tr>
      <tr>
        <td style="width:30px;text-align:left;padding:4px;">JUMLAH:</td>
        <td><input type="text" class="input_type" name="form_121[jumlah_implan]" id="jumlah_implan" style="width:100%;"></td>
    </tr>
    </table>
  </td>
</tr>


<!-- 6. LAIN-LAIN -->
<tr>
  <td colspan="6" style="font-weight:bold; text-align:left; border-top:1px solid #000;padding:4px;">6. LAIN-LAIN :</td>
</tr>
<tr>
  <!-- Kolom Kiri (1–2) -->
  <td colspan="3" style="vertical-align:top; border-right:1px solid #000; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr><td style="width:30px;padding:4px;">1.</td><td><input type="text" class="input_type" name="form_121[lain_1]" id="lain_1" style="width:100%;"></td></tr>
      <tr><td style="width:30px;padding:4px;">2.</td><td><input type="text" class="input_type" name="form_121[lain_2]" id="lain_2" style="width:100%;"></td></tr>
      <!-- <tr><td colspan="2" style="text-align:left;padding:4px;">JUMLAH :</td></tr> -->
    </table>
  </td>

  <!-- Kolom Kanan (3–4) -->
  <td colspan="3" style="vertical-align:top; padding:0;">
    <table style="width:100%; border-collapse:collapse;">
      <tr><td style="width:30px;padding:4px;">3.</td><td><input type="text" class="input_type" name="form_121[lain_3]" id="lain_3" style="width:100%;"></td></tr>
      <tr><td style="padding:4px;">4.</td><td><input type="text" class="input_type" name="form_121[lain_4]" id="lain_4" style="width:100%;"></td></tr>
      <tr>
        <td style="width:30px;text-align:left;padding:4px;">JUMLAH:</td>
        <td><input type="text" class="input_type" name="form_121[jumlah_lain]" id="jumlah_lain" style="width:100%;"></td>
    </tr>
    </table>
  </td>
</tr>

  </tbody>
</table>


<br>
<div style="text-align: left; font-size: 12px; font-weight: bold;">LAPORAN OPERASI LENGKAP</div>
<br>

<!-- Input laporan operasi -->
<table style="width:100%; border-collapse:collapse;">
  <tr>
    <td><input type="text" class="input_type" name="form_121[laporan_op1]" id="laporan_op1" style="width:100%;"></td>
  </tr>
  <tr>
    <td><input type="text" class="input_type" name="form_121[laporan_op2]" id="laporan_op2" style="width:100%;"></td>
  </tr>
  <tr>
    <td><input type="text" class="input_type" name="form_121[laporan_op3]" id="laporan_op3" style="width:100%;"></td>
  </tr>
</table>

<br><br>

<!-- TANDA TANGAN -->
<table class="table" style="width: 100%; border-collapse:collapse;">
  <tbody>
    <tr>
      <!-- Kolom Penanggung Jawab Kamar Bedah -->
      <td style="width:50%; text-align:center;">
        <br><br>
        Mengetahui,<br>
        Penanggung Jawab Kamar Bedah
        <br><br>
        <span class="ttd-btn" data-role="pj_kb" id="ttd_pj_kb" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_pj_kb" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" name="form_121[nama_pj_kb]" id="nama_pj_kb" class="input_type" placeholder="Nama" style="width:150px; text-align:center;">
        <br>
        (Tanda Tangan dan Nama Jelas)
      </td>

      <!-- Kolom Perawat Sirkuler -->
      <td style="width:50%; text-align:center;">
        Jakarta,
        <input type="text" name="form_121[tanggal_ttd_sirkuler]" id="tanggal_ttd_sirkuler" class="input_type" style="width:100px;">
        <br><br>
        Perawat Sirkuler
        <br><br>
        <span class="ttd-btn" data-role="sirkuler" id="ttd_sirkuler" style="cursor:pointer;">
          <i class="fa fa-pencil blue"></i>
        </span>
        <br>
        <img id="img_ttd_sirkuler" src="" style="display:none; max-width:150px; max-height:40px; margin-top:2px;">
        <br><br>
        <input type="text" name="form_121[nama_sirkuler]" id="nama_sirkuler" class="input_type" placeholder="Nama" style="width:150px; text-align:center;">
        <br>
        (Tanda Tangan dan Nama Jelas)
      </td>
    </tr>
  </tbody>
</table>

<br><br>

<!-- Modal Tanda Tangan Digital -->
<div class="modal fade" id="ttdModal" tabindex="-1" role="dialog" aria-labelledby="ttdModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="ttdModalLabel" style="color: white;">Tanda Tangan Digital</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="text-align:center;">
        <canvas id="ttd-canvas" style="border:1px solid #ccc; touch-action:none;" width="350" height="120"></canvas>
        <br>
        <button type="button" class="btn btn-warning btn-sm" id="clear-ttd">Bersihkan</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-xs btn-primary" id="save-ttd">Simpan</button>
      </div>
    </div>
  </div>
</div>



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

  $('#diagnosis').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getICD10",
              data: 'keyword=' + query,            
              dataType: "json",
              type: "POST",
              success: function (response) {
                result($.map(response, function (item) {
                      return item;
                  }));
                
              }
          });
      },
      afterSelect: function (item) {
        // do what is needed with item
        var label_item=item.split(':')[1];
        var val_item=item.split(':')[0];
        console.log(val_item);
        $('#diagnosis').val(label_item);
      }

  });
  
  var ttdCanvas = null, ttdCtx = null, drawing = false, lastPos = {x:0, y:0};
  var currentTtdTarget = null;
  
  function getPos(canvas, evt) {
    var rect = canvas.getBoundingClientRect();
    if (evt.touches && evt.touches.length > 0) {
      return {
        x: evt.touches[0].clientX - rect.left,
        y: evt.touches[0].clientY - rect.top
      };
    } else {
      return {
        x: evt.clientX - rect.left,
        y: evt.clientY - rect.top
      };
    }
  }

  function initTtdCanvas() {
    ttdCanvas = document.getElementById('ttd-canvas');
    ttdCtx = ttdCanvas.getContext('2d');
    ttdCtx.clearRect(0, 0, ttdCanvas.width, ttdCanvas.height);
    drawing = false;
    lastPos = {x:0, y:0};

    ttdCanvas.onmousedown = function(e) {
      drawing = true;
      lastPos = getPos(ttdCanvas, e);
    };
    ttdCanvas.onmouseup = function(e) {
      drawing = false;
    };
    ttdCanvas.onmousemove = function(e) {
      if (!drawing) return;
      var pos = getPos(ttdCanvas, e);
      ttdCtx.beginPath();
      ttdCtx.moveTo(lastPos.x, lastPos.y);
      ttdCtx.lineTo(pos.x, pos.y);
      ttdCtx.stroke();
      lastPos = pos;
    };
    // Touch events
    ttdCanvas.addEventListener('touchstart', function(e) {
      drawing = true;
      lastPos = getPos(ttdCanvas, e);
    });
    ttdCanvas.addEventListener('touchend', function(e) {
      drawing = false;
    });
    ttdCanvas.addEventListener('touchmove', function(e) {
      if (!drawing) return;
      var pos = getPos(ttdCanvas, e);
      ttdCtx.beginPath();
      ttdCtx.moveTo(lastPos.x, lastPos.y);
      ttdCtx.lineTo(pos.x, pos.y);
      ttdCtx.stroke();
      lastPos = pos;
      e.preventDefault();
    });
    // Clear button
    $('#clear-ttd').off('click').on('click', function() {
      ttdCtx.clearRect(0, 0, ttdCanvas.width, ttdCtx.height);
    });
  }

  // Open modal on click
  $('.ttd-btn').off('click').on('click', function() {
    currentTtdTarget = $(this);
    $('#ttdModal').modal('show');
    setTimeout(initTtdCanvas, 300);
  });

  // Save signature
  $('#save-ttd').off('click').on('click', function() {
    if (!ttdCanvas) return;
    var dataUrl = ttdCanvas.toDataURL('image/png');
    if (currentTtdTarget) {
      var role = currentTtdTarget.data('role');
      var imgId = '#img_ttd_' + role;
      $(imgId).attr('src', dataUrl).show();
      // Tambahkan input hidden untuk menyimpan data URL
      var hiddenInputName = 'form_114[ttd_' + role + ']';
      if ($('input[name="' + hiddenInputName + '"]').length === 0) {
        $('<input>').attr({
          type: 'hidden',
          id: 'ttd_data_' + role,
          name: hiddenInputName,
          value: dataUrl
        }).appendTo('form');
      } else {
        $('input[name="' + hiddenInputName + '"]').val(dataUrl);
      }
    }
    $('#ttdModal').modal('hide');
  });
});
</script>

<?php //echo $footer; ?>