<?php echo $header; ?>
<?php
if (!isset($result['registrasi']->no_registrasi)) {
    echo '<div class="alert alert-danger"><strong>Data tidak ditemukan!</strong> Data registrasi tidak tersedia atau telah dihapus.</div>';
    exit;
}
$reg   = $result['registrasi'];
$_sess = $this->session->userdata('user');
$_uname = isset($_sess->fullname) ? $_sess->fullname : 'Sistem';
?>

<style>
/* ================================================================
   RESUME MEDIS — STYLES (Screen + Print)
   ================================================================ */

/* --- Layout wrapper --- */
.rm-doc { max-width: 860px; margin: 0 auto; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #1a1a1a; }

/* --- Visibility helpers --- */
.rm-print-only  { display: none; }
.rm-screen-only { display: block; }

/* --- Section card --- */
.rm-section        { border: 1px solid #b0bfd6; border-radius: 3px; margin-bottom: 9px; overflow: hidden; }
.rm-section-hdr    { background: #1a3a6e; color: #fff; font-weight: bold; font-size: 12px; padding: 5px 12px; letter-spacing: 0.3px; }
.rm-section-hdr i  { margin-right: 5px; }
.rm-section-body   { padding: 0; }

/* --- Data table (info rows) --- */
.rm-tbl            { width: 100%; border-collapse: collapse; font-size: 12px; }
.rm-tbl td, .rm-tbl th { border: 1px solid #d0d9e8; padding: 5px 8px; vertical-align: top; }
.rm-tbl th         { background: #eef2fa; font-weight: bold; text-align: left; }
.rm-lbl            { color: #444; width: 155px; }
.rm-lbl2           { color: #444; width: 155px; }
.rm-val            { font-weight: bold; }

/* --- Vital signs boxes --- */
.rm-vitals-tbl     { width: 100%; border-collapse: collapse; padding: 8px; }
.rm-vitals-tbl td  { text-align: center; padding: 8px 4px; border: none; }
.rm-vital-box      { border: 1px solid #c3cfdf; border-radius: 5px; padding: 7px 10px; background: #f5f8ff; display: inline-block; min-width: 110px; }
.rm-vital-num      { font-size: 15px; font-weight: bold; color: #1a3a6e; line-height: 1.2; }
.rm-vital-lbl      { font-size: 10px; color: #666; margin-top: 2px; }

/* --- SOAP --- */
.rm-soap-row        { display: table; width: 100%; border-bottom: 1px solid #e5eaf2; }
.rm-soap-row:last-child { border-bottom: none; }
.rm-soap-key        { display: table-cell; width: 38px; min-height: 44px; font-weight: bold; font-size: 15px; text-align: center; vertical-align: top; padding: 10px 0; color: #fff; }
.rm-soap-key.s      { background: #1d4ed8; }
.rm-soap-key.o      { background: #0369a1; }
.rm-soap-key.a      { background: #047857; }
.rm-soap-key.p      { background: #7c3aed; }
.rm-soap-content    { display: table-cell; padding: 8px 12px; vertical-align: top; }
.rm-soap-fl         { font-weight: bold; color: #444; font-size: 11px; margin-top: 4px; margin-bottom: 2px; }
.rm-soap-fv         { margin-bottom: 6px; line-height: 1.5; }

/* --- Badges --- */
.rm-badge           { display: inline-block; padding: 2px 9px; border-radius: 10px; font-size: 11px; font-weight: bold; }
.rm-badge-ada       { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
.rm-badge-tidak     { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }

/* --- ICD chips --- */
.rm-chip            { display: inline-block; padding: 1px 7px; border-radius: 10px; font-size: 11px; margin: 1px 2px; border: 1px solid #c7d2fe; background: #eef2ff; }
.rm-chip-code       { font-weight: bold; color: #3730a3; }
.rm-chip-9          { background: #f0fdf4; border-color: #a7f3d0; }
.rm-chip-9 .rm-chip-code { color: #065f46; }

/* --- Document title --- */
.rm-doc-title       { text-align: center; font-size: 17px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; margin: 8px 0 2px; }
.rm-doc-sub         { text-align: center; font-size: 11px; color: #555; border-bottom: 2px solid #1a3a6e; padding-bottom: 8px; margin-bottom: 10px; }

/* --- Print header --- */
.rm-ph-tbl          { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
.rm-ph-tbl td       { padding: 3px; vertical-align: middle; }
.rm-ph-divider      { border: none; border-top: 2px solid #1a3a6e; margin: 6px 0 10px; }

/* --- Signature --- */
.rm-sign-tbl        { width: 100%; border-collapse: collapse; }
.rm-sign-tbl td     { width: 50%; text-align: center; padding: 10px 20px; vertical-align: top; }
.rm-sign-line       { border-top: 1px solid #333; width: 210px; margin: 0 auto; padding-top: 4px; font-size: 11px; }

/* --- Footer note --- */
.rm-footnote        { font-size: 10px; color: #888; text-align: right; border-top: 1px solid #ddd; padding-top: 5px; margin-top: 8px; }

/* ================================================================
   PRINT OVERRIDES
   ================================================================ */
@media print {
  body               { margin: 0 !important; padding: 0 !important; }
  #header_form       { display: none !important; }
  #footer_form       { display: none !important; }
  .rm-print-only     { display: block !important; }
  .rm-screen-only    { display: none !important; }
  .rm-doc            { max-width: 100%; }
  .rm-section        { page-break-inside: avoid; }
  .rm-page-break     { page-break-before: always; }
  @page              { size: A4 portrait; margin: 1.5cm 1.5cm 2cm 1.5cm; }
  *                  { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
}
</style>

<div class="rm-doc">

  <!-- ── Screen: action buttons ── -->
  <div class="rm-screen-only" style="margin: 8px 0; text-align: right;">
    <a href="<?php echo base_url().'registration/reg_pasien/view_detail_resume_medis/'.$reg->no_registrasi.'?print=true&tipe_layan='.$tipe_layan?>"
       class="btn btn-sm btn-primary" target="_blank">
      <i class="fa fa-print"></i> Cetak Resume Medis
    </a>
  </div>

  <!-- ── Print-only: custom document header ── -->
  <div class="rm-print-only">
    <table class="rm-ph-tbl">
      <tr>
        <td style="width:60%; vertical-align:middle;">
          <img src="<?php echo base_url().COMP_ICON?>" height="46" style="vertical-align:middle; margin-right:10px;">
          <strong style="font-size:13px; vertical-align:middle;"><?php echo COMP_LONG?></strong><br>
          <span style="font-size:10px; margin-left:56px; color:#555;"><?php echo COMP_ADDRESS_SORT?></span><br>
          <span style="font-size:10px; margin-left:56px; color:#555;">Telp: <?php echo COMP_TELP?> &nbsp; Fax: <?php echo COMP_FAX?></span>
        </td>
        <td style="width:40%; text-align:right; font-size:10px; vertical-align:top; line-height:1.8;">
          No. RM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <strong><?php echo $reg->no_mr?></strong><br>
          No. Registrasi : <strong><?php echo $reg->no_registrasi?></strong><br>
          Tgl. Cetak &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $this->tanggal->formatDateTime(date('Y-m-d H:i:s'))?>
        </td>
      </tr>
    </table>
    <hr class="rm-ph-divider">
  </div>

  <!-- ── Document title ── -->
  <div class="rm-doc-title">Resume Medis Pasien</div>
  <div class="rm-doc-sub"><?php echo ($tipe_layan == 'RI') ? 'Rawat Inap' : 'Rawat Jalan' ?></div>


  <!-- ==============================================================
       1. IDENTITAS PASIEN
       ============================================================== -->
  <div class="rm-section">
    <div class="rm-section-hdr"><i class="fa fa-user"></i>Identitas Pasien</div>
    <div class="rm-section-body">
      <table class="rm-tbl" style="border:none;">
        <tr>
          <td class="rm-lbl">Nama Pasien</td>
          <td style="width:5px">:</td>
          <td class="rm-val" style="width:250px"><?php echo ucwords(strtolower($reg->nama_pasien))?></td>
          <td class="rm-lbl2">No. Rekam Medis</td>
          <td style="width:5px">:</td>
          <td class="rm-val"><?php echo $reg->no_mr?></td>
        </tr>
        <tr>
          <td class="rm-lbl">Tempat / Tgl. Lahir</td>
          <td>:</td>
          <td><?php echo ucwords(strtolower($reg->tempat_lahir)).', '.($reg->tgl_lhr ? $this->tanggal->formatDate($reg->tgl_lhr) : '-')?></td>
          <td class="rm-lbl2">Umur</td>
          <td>:</td>
          <td><?php echo $umur?> Tahun</td>
        </tr>
        <tr>
          <td class="rm-lbl">Jenis Kelamin</td>
          <td>:</td>
          <td><?php echo ($reg->jen_kelamin == 'L') ? 'Laki-laki' : (($reg->jen_kelamin == 'P') ? 'Perempuan' : ($reg->jen_kelamin ?: '-'))?></td>
          <td class="rm-lbl2">No. KTP / NIK</td>
          <td>:</td>
          <td><?php echo $reg->no_ktp ?: '-'?></td>
        </tr>
        <tr>
          <td class="rm-lbl">No. Kartu BPJS</td>
          <td>:</td>
          <td><?php echo $reg->no_kartu_bpjs ?: '-'?></td>
          <td class="rm-lbl2">No. Telepon</td>
          <td>:</td>
          <td><?php echo $reg->no_hp ?: ($reg->tlp_almt_ttp ?: '-')?></td>
        </tr>
        <tr>
          <td class="rm-lbl">Alamat</td>
          <td>:</td>
          <td colspan="4"><?php echo $reg->almt_ttp_pasien ?: '-'?></td>
        </tr>
      </table>
    </div>
  </div>


  <!-- ==============================================================
       2. INFORMASI KUNJUNGAN
       ============================================================== -->
  <div class="rm-section">
    <div class="rm-section-hdr"><i class="fa fa-calendar-check-o"></i>Informasi Kunjungan</div>
    <div class="rm-section-body">
      <table class="rm-tbl" style="border:none;">
        <tr>
          <td class="rm-lbl">No. Registrasi</td>
          <td style="width:5px">:</td>
          <td class="rm-val" style="width:250px"><?php echo $reg->no_registrasi?></td>
          <td class="rm-lbl2">Tanggal Masuk</td>
          <td style="width:5px">:</td>
          <td><?php echo $this->tanggal->formatDateTime($reg->tgl_jam_masuk)?></td>
        </tr>
        <tr>
          <td class="rm-lbl">Dokter / DPJP</td>
          <td>:</td>
          <td class="rm-val"><?php echo $reg->nama_pegawai?></td>
          <td class="rm-lbl2">Poli / Unit Masuk</td>
          <td>:</td>
          <td><?php echo $reg->nama_bagian?></td>
        </tr>
        <tr>
          <td class="rm-lbl">Cara Pembayaran</td>
          <td>:</td>
          <td><?php echo $reg->nama_perusahaan ?: '-'?></td>
          <td class="rm-lbl2">Jenis Kunjungan BPJS</td>
          <td>:</td>
          <td><?php echo $reg->jeniskunjunganbpjs ?: '-'?></td>
        </tr>
        <?php if ($reg->no_sep || $reg->norujukan): ?>
        <tr>
          <td class="rm-lbl">No. SEP</td>
          <td>:</td>
          <td><?php echo $reg->no_sep ?: '-'?></td>
          <td class="rm-lbl2">No. Rujukan</td>
          <td>:</td>
          <td><?php echo $reg->norujukan ?: '-'?></td>
        </tr>
        <?php endif; ?>
        <tr>
          <td class="rm-lbl">No. Antrian</td>
          <td>:</td>
          <td><?php echo isset($result['no_antrian']->no_antrian) ? $result['no_antrian']->no_antrian : '-'?></td>
          <td class="rm-lbl2">Petugas Pendaftaran</td>
          <td>:</td>
          <td><?php echo isset($result['petugas']->fullname) ? $result['petugas']->fullname : '-'?></td>
        </tr>
      </table>
    </div>
  </div>


  <!-- ==============================================================
       3. TANDA-TANDA VITAL
       ============================================================== -->
  <div class="rm-section">
    <div class="rm-section-hdr"><i class="fa fa-heartbeat"></i>Tanda-Tanda Vital</div>
    <div class="rm-section-body">
      <table class="rm-vitals-tbl">
        <tr>
          <td>
            <div class="rm-vital-box">
              <div class="rm-vital-num"><?php echo $reg->tekanan_darah ?: '&mdash;'?></div>
              <div class="rm-vital-lbl">Tekanan Darah (mmHg)</div>
            </div>
          </td>
          <td>
            <div class="rm-vital-box">
              <div class="rm-vital-num"><?php echo $reg->nadi ? $reg->nadi.' <small style="font-size:11px">x/m</small>' : '&mdash;'?></div>
              <div class="rm-vital-lbl">Nadi</div>
            </div>
          </td>
          <td>
            <div class="rm-vital-box">
              <div class="rm-vital-num"><?php echo $reg->suhu ? $reg->suhu.' <small style="font-size:11px">&deg;C</small>' : '&mdash;'?></div>
              <div class="rm-vital-lbl">Suhu</div>
            </div>
          </td>
          <td>
            <div class="rm-vital-box">
              <div class="rm-vital-num"><?php echo $reg->berat_badan ? $reg->berat_badan.' <small style="font-size:11px">kg</small>' : '&mdash;'?></div>
              <div class="rm-vital-lbl">Berat Badan</div>
            </div>
          </td>
          <td>
            <div class="rm-vital-box">
              <div class="rm-vital-num"><?php echo $reg->tinggi_badan ? $reg->tinggi_badan.' <small style="font-size:11px">cm</small>' : '&mdash;'?></div>
              <div class="rm-vital-lbl">Tinggi Badan</div>
            </div>
          </td>
          <td>
            <div class="rm-vital-box">
              <?php
                if ($reg->berat_badan && $reg->tinggi_badan && $reg->tinggi_badan > 0) {
                  $bmi = round($reg->berat_badan / pow($reg->tinggi_badan / 100, 2), 1);
                } else {
                  $bmi = null;
                }
              ?>
              <div class="rm-vital-num"><?php echo $bmi !== null ? $bmi : '&mdash;'?></div>
              <div class="rm-vital-lbl">IMT (kg/m&sup2;)</div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>


  <!-- ==============================================================
       4. RIWAYAT PASIEN
       ============================================================== -->
  <div class="rm-section">
    <div class="rm-section-hdr"><i class="fa fa-history"></i>Riwayat Pasien</div>
    <div class="rm-section-body">
      <table class="rm-tbl" style="border:none;">
        <?php
          // helper inline function for badge
          $_rw_badge = function($val, $ket) {
            if ($val === '1' || $val === 1) {
              echo '<span class="rm-badge rm-badge-ada">Ada</span>';
              if ($ket) echo ' &mdash; <span style="color:#444">'.$ket.'</span>';
            } elseif ($val === '0' || $val === 0) {
              echo '<span class="rm-badge rm-badge-tidak">Tidak Ada</span>';
            } else {
              echo '<span style="color:#aaa;">Tidak Diisi</span>';
            }
          };
        ?>
        <tr>
          <td style="width:22%; font-weight:bold; background:#f4f6fb; color:#333;">Riwayat Penyakit Dahulu</td>
          <td style="width:28%;">
            <?php $_rw_badge(
              isset($reg->riwayat_penyakit_dahulu) ? $reg->riwayat_penyakit_dahulu : '',
              isset($reg->riwayat_penyakit_dahulu_ket) ? $reg->riwayat_penyakit_dahulu_ket : ''
            ); ?>
          </td>
          <td style="width:22%; font-weight:bold; background:#f4f6fb; color:#333;">Riwayat Alergi</td>
          <td>
            <?php $_rw_badge(
              isset($reg->riwayat_alergi) ? $reg->riwayat_alergi : '',
              isset($reg->riwayat_alergi_ket) ? $reg->riwayat_alergi_ket : ''
            ); ?>
          </td>
        </tr>
        <tr>
          <td style="font-weight:bold; background:#f4f6fb; color:#333;">Riwayat Operasi / Tindakan</td>
          <td colspan="3">
            <?php $_rw_badge(
              isset($reg->riwayat_operasi) ? $reg->riwayat_operasi : '',
              isset($reg->riwayat_operasi_ket) ? $reg->riwayat_operasi_ket : ''
            ); ?>
          </td>
        </tr>
      </table>
    </div>
  </div>


  <!-- ==============================================================
       5A. CATATAN KLINIS (SOAP) — Rawat Jalan
       ============================================================== -->
  <?php if ($tipe_layan == 'RJ') : ?>
  <?php
    $poli_rows = array();
    foreach ($result['riwayat_medis'] as $row_rm) {
      if (substr($row_rm->kode_bagian_tujuan, 0, 2) == '01') $poli_rows[] = $row_rm;
    }
  ?>
  <?php foreach ($poli_rows as $row_rm) : ?>
  <div class="rm-section">
    <div class="rm-section-hdr"><i class="fa fa-stethoscope"></i>Catatan Klinis &mdash; <?php echo htmlspecialchars($row_rm->poli_tujuan_kunjungan)?></div>
    <div class="rm-section-body">

      <!-- S: Subjective -->
      <div class="rm-soap-row">
        <div class="rm-soap-key s">S</div>
        <div class="rm-soap-content">
          <div class="rm-soap-fl">Keluhan Utama / Anamnesa</div>
          <div class="rm-soap-fv"><?php echo $row_rm->anamnesa ? nl2br(htmlspecialchars($row_rm->anamnesa)) : '<span style="color:#aaa">—</span>'?></div>
          <?php if ($row_rm->diagnosa_awal): ?>
          <div class="rm-soap-fl">Diagnosa Awal</div>
          <div class="rm-soap-fv"><?php echo htmlspecialchars($row_rm->diagnosa_awal)?></div>
          <?php endif; ?>
        </div>
      </div>

      <!-- O: Objective -->
      <div class="rm-soap-row">
        <div class="rm-soap-key o">O</div>
        <div class="rm-soap-content">
          <div class="rm-soap-fl">Pemeriksaan Fisik</div>
          <div class="rm-soap-fv"><?php echo $row_rm->pemeriksaan ? nl2br(htmlspecialchars($row_rm->pemeriksaan)) : '<span style="color:#aaa">—</span>'?></div>
        </div>
      </div>

      <!-- A: Assessment -->
      <div class="rm-soap-row">
        <div class="rm-soap-key a">A</div>
        <div class="rm-soap-content">
          <div class="rm-soap-fl">Diagnosa Primer (ICD-10)</div>
          <div class="rm-soap-fv">
            <?php if ($row_rm->kode_icd_diagnosa): ?><span class="rm-chip"><span class="rm-chip-code"><?php echo htmlspecialchars($row_rm->kode_icd_diagnosa)?></span></span><?php endif; ?>
            <?php echo $row_rm->diagnosa_akhir ? htmlspecialchars($row_rm->diagnosa_akhir) : '<span style="color:#aaa">—</span>'?>
          </div>
          <?php if ($row_rm->diagnosa_sekunder): ?>
          <div class="rm-soap-fl">Diagnosa Sekunder</div>
          <div class="rm-soap-fv">
            <?php foreach (array_filter(array_map('trim', explode('|', $row_rm->diagnosa_sekunder))) as $ds) echo '<span class="rm-chip">'.htmlspecialchars($ds).'</span> '; ?>
          </div>
          <?php endif; ?>
          <?php if ($row_rm->text_icd9): ?>
          <div class="rm-soap-fl">Prosedur ICD-9-CM</div>
          <div class="rm-soap-fv">
            <?php foreach (array_filter(array_map('trim', explode('|', $row_rm->text_icd9))) as $icd9) echo '<span class="rm-chip rm-chip-9">'.htmlspecialchars($icd9).'</span> '; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- P: Plan -->
      <div class="rm-soap-row">
        <div class="rm-soap-key p">P</div>
        <div class="rm-soap-content">
          <div class="rm-soap-fl">Tatalaksana / Pengobatan</div>
          <div class="rm-soap-fv"><?php echo $row_rm->pengobatan ? nl2br(htmlspecialchars($row_rm->pengobatan)) : '<span style="color:#aaa">—</span>'?></div>
          <?php if (isset($row_rm->tgl_kontrol_kembali) && $row_rm->tgl_kontrol_kembali && $row_rm->tgl_kontrol_kembali != '0000-00-00'): ?>
          <div class="rm-soap-fl">Kontrol Kembali</div>
          <div class="rm-soap-fv"><strong><?php echo $this->tanggal->formatDate($row_rm->tgl_kontrol_kembali)?></strong></div>
          <?php endif; ?>
          <?php if (isset($row_rm->cara_keluar_pasien) && $row_rm->cara_keluar_pasien): ?>
          <div class="rm-soap-fl">Cara Keluar</div>
          <div class="rm-soap-fv"><?php echo htmlspecialchars($row_rm->cara_keluar_pasien)?></div>
          <?php endif; ?>
          <?php if (isset($row_rm->pasca_pulang) && $row_rm->pasca_pulang): ?>
          <div class="rm-soap-fl">Anjuran Pasca Pulang</div>
          <div class="rm-soap-fv"><?php echo nl2br(htmlspecialchars($row_rm->pasca_pulang))?></div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
  <?php endforeach; ?>


  <!-- ==============================================================
       5B. RIWAYAT KUNJUNGAN — Rawat Inap
       ============================================================== -->
  <?php elseif ($tipe_layan == 'RI') : ?>
  <div class="rm-section rm-page-break">
    <div class="rm-section-hdr"><i class="fa fa-list-alt"></i>Riwayat Kunjungan &mdash; Rawat Inap</div>
    <div class="rm-section-body">
      <table class="rm-tbl">
        <thead>
          <tr>
            <th style="width:4%; text-align:center;">No</th>
            <th style="width:13%;">Tanggal</th>
            <th style="width:14%;">Unit Asal</th>
            <th style="width:14%;">Unit Tujuan</th>
            <th style="width:14%;">Diagnosa Awal</th>
            <th>Anamnesa / Pemeriksaan Fisik</th>
            <th style="width:15%;">Diagnosa Akhir</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $no = 0;
            foreach ($result['riwayat_medis'] as $row_rm):
              if (in_array($row_rm->kode_bagian_tujuan, array('050101','050201'))) continue;
              $no++;
          ?>
          <tr>
            <td align="center"><?php echo $no?></td>
            <td><?php echo $this->tanggal->formatDateTime($row_rm->tgl_masuk)?></td>
            <td><?php echo htmlspecialchars($row_rm->poli_asal_kunjungan)?></td>
            <td><?php echo htmlspecialchars($row_rm->poli_tujuan_kunjungan)?></td>
            <td><?php echo htmlspecialchars($row_rm->diagnosa_awal)?></td>
            <td>
              <?php if ($row_rm->anamnesa): ?><strong>Keluhan:</strong> <?php echo nl2br(htmlspecialchars($row_rm->anamnesa))?><br><?php endif; ?>
              <?php if ($row_rm->pemeriksaan): ?><strong>Fisik:</strong> <?php echo nl2br(htmlspecialchars($row_rm->pemeriksaan))?><?php endif; ?>
            </td>
            <td>
              <?php if ($row_rm->kode_icd_diagnosa): ?><span class="rm-chip"><span class="rm-chip-code"><?php echo htmlspecialchars($row_rm->kode_icd_diagnosa)?></span></span><br><?php endif; ?>
              <?php echo htmlspecialchars($row_rm->diagnosa_akhir)?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>


  <!-- ==============================================================
       6. PEMERIKSAAN PENUNJANG MEDIS
       ============================================================== -->
  <?php
    $penunjang_grp = array();
    foreach ($result['tindakan'] as $row_p) {
      if (substr($row_p->kode_bagian, 0, 2) == '05')
        $penunjang_grp[$row_p->kode_bagian][] = $row_p;
    }
  ?>
  <div class="rm-section">
    <div class="rm-section-hdr"><i class="fa fa-flask"></i>Pemeriksaan Penunjang Medis</div>
    <div class="rm-section-body">
      <?php if (empty($penunjang_grp)): ?>
      <p style="padding:10px; color:#aaa; margin:0; font-style:italic; text-align:center;">Tidak ada data pemeriksaan penunjang.</p>
      <?php else: ?>
      <table class="rm-tbl">
        <thead>
          <tr>
            <th style="width:12%;">Tanggal</th>
            <th style="width:22%;">Dokter / Pengirim</th>
            <th style="width:20%;">Jenis Penunjang</th>
            <th>Item Pemeriksaan</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($penunjang_grp as $kode_bg => $row_t): ?>
          <tr>
            <td><?php echo $this->tanggal->formatDate($row_t[0]->tgl_transaksi)?></td>
            <td><?php echo htmlspecialchars($row_t[0]->nama_pegawai)?></td>
            <td><?php echo $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $row_t[0]->kode_bagian))?></td>
            <td>
              <ol style="margin:2px 0; padding-left:16px;">
                <?php foreach ($row_t as $row_dt): ?><li><?php echo htmlspecialchars($row_dt->nama_tindakan)?></li><?php endforeach; ?>
              </ol>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
  </div>


  <!-- ==============================================================
       7. OBAT YANG DIBERIKAN
       ============================================================== -->
  <?php
    $obat_rows = array();
    foreach ($result['tindakan'] as $row_obt) {
      if (in_array($row_obt->kode_jenis_tindakan, array(11))) $obat_rows[] = $row_obt;
    }
  ?>
  <div class="rm-section">
    <div class="rm-section-hdr"><i class="fa fa-medkit"></i>Obat yang Diberikan</div>
    <div class="rm-section-body">
      <?php if (empty($obat_rows)): ?>
      <p style="padding:10px; color:#aaa; margin:0; font-style:italic; text-align:center;">Tidak ada data obat yang diberikan.</p>
      <?php else: ?>
      <table class="rm-tbl">
        <thead>
          <tr>
            <th style="width:4%; text-align:center;">No</th>
            <th style="width:36%;">Nama Obat</th>
            <th style="width:24%;">Dosis / Aturan Pakai</th>
            <th style="width:14%;">Jumlah</th>
            <th>Catatan</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($obat_rows as $i => $row_obt): ?>
          <tr>
            <td align="center"><?php echo ($i + 1)?></td>
            <td><strong><?php echo htmlspecialchars($row_obt->nama_tindakan)?></strong></td>
            <td><?php echo htmlspecialchars($row_obt->dosis_per_hari.' x '.$row_obt->dosis_obat.' '.$row_obt->satuan_obat)?>
              <?php if ($row_obt->anjuran_pakai) echo '<br><small style="color:#555">'.htmlspecialchars($row_obt->anjuran_pakai).'</small>'?></td>
            <td>
              <?php
                $jml = ($row_obt->jumlah_tebus == 0) ? (int)$row_obt->jumlah_obat_23 : (int)$row_obt->jumlah_tebus + (int)$row_obt->jumlah_obat_23;
                echo $jml.' '.htmlspecialchars($row_obt->satuan_kecil);
              ?>
            </td>
            <td><?php echo $row_obt->catatan_lainnya ? htmlspecialchars($row_obt->catatan_lainnya) : '-'?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
  </div>


  <!-- ==============================================================
       8. TINDAK LANJUT
       ============================================================== -->
  <?php
    $tgl_ctrl    = isset($reg->tgl_kontrol_kembali) ? $reg->tgl_kontrol_kembali : '';
    $cara_keluar = isset($reg->cara_keluar_pasien)  ? $reg->cara_keluar_pasien  : '';
    $pasca_pulang = isset($reg->pasca_pulang)       ? $reg->pasca_pulang        : '';
  ?>
  <div class="rm-section">
    <div class="rm-section-hdr"><i class="fa fa-share-square-o"></i>Tindak Lanjut</div>
    <div class="rm-section-body">
      <table class="rm-tbl" style="border:none;">
        <tr>
          <td class="rm-lbl">Kontrol Kembali</td>
          <td style="width:5px">:</td>
          <td style="width:250px"><?php echo ($tgl_ctrl && $tgl_ctrl != '0000-00-00') ? '<strong>'.$this->tanggal->formatDate($tgl_ctrl).'</strong>' : '<span style="color:#aaa">—</span>'?></td>
          <td class="rm-lbl2">Cara Keluar Pasien</td>
          <td style="width:5px">:</td>
          <td><?php echo $cara_keluar ? htmlspecialchars($cara_keluar) : '<span style="color:#aaa">—</span>'?></td>
        </tr>
        <?php if ($pasca_pulang): ?>
        <tr>
          <td class="rm-lbl">Anjuran Pasca Pulang</td>
          <td>:</td>
          <td colspan="4"><?php echo nl2br(htmlspecialchars($pasca_pulang))?></td>
        </tr>
        <?php endif; ?>
      </table>
    </div>
  </div>


  <!-- ==============================================================
       9. PERSETUJUAN & TANDA TANGAN
       ============================================================== -->
  <div class="rm-section" style="margin-top:12px;">
    <div class="rm-section-hdr"><i class="fa fa-pencil-square-o"></i>Persetujuan &amp; Tanda Tangan</div>
    <div class="rm-section-body" style="padding:16px 20px;">
      <table class="rm-sign-tbl">
        <tr>
          <td>
            <p style="margin:0 0 4px; font-size:11px;">Pasien / Keluarga Pasien,</p>
            <br><br><br><br>
            <div class="rm-sign-line">
              ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
            </div>
          </td>
          <td>
            <?php echo $footer; ?>
          </td>
        </tr>
      </table>
    </div>
  </div>

  <!-- Footer note -->
  <div class="rm-footnote">
    Dicetak oleh: <strong><?php echo htmlspecialchars($_uname)?></strong> &nbsp;|&nbsp;
    <?php echo $this->tanggal->formatDateTime(date('Y-m-d H:i:s'))?> &nbsp;|&nbsp;
    <?php echo APPS_NAME_SORT.' &mdash; '.COMP_LONG?>
  </div>

</div><!-- /.rm-doc -->

<?php if (isset($_GET['print'])): ?>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<script>window.onload = function(){ window.print(); };</script>
<?php endif; ?>
