<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SOAP Pasien &mdash; <?php echo htmlspecialchars(strtoupper($pasien->nama_pasien)); ?></title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', system-ui, sans-serif; font-size: 13px; background: #f1f5f9; color: #1e293b; }

  /* ── Top bar ── */
  .top-bar {
    position: sticky; top: 0; z-index: 100;
    background: #1a4f8a; color: #fff;
    padding: 8px 16px; display: flex; align-items: center; gap: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,.25);
  }
  .top-bar .patient-name { font-weight: 700; font-size: 14px; }
  .top-bar .patient-meta { font-size: 11px; color: #bfdbfe; }
  .top-bar .spacer       { flex: 1; }
  .session-badge {
    font-size: 11px; background: rgba(255,255,255,.15);
    border-radius: 4px; padding: 3px 8px; display: flex; align-items: center; gap: 5px;
  }
  .session-badge.expiring { background: rgba(239,68,68,.4); }
  .session-badge.expired  { background: rgba(239,68,68,.7); }
  .session-dot { width: 7px; height: 7px; border-radius: 50%; background: #4ade80; display: inline-block; }
  .session-dot.expiring   { background: #fbbf24; }
  .session-dot.expired    { background: #f87171; }

  /* ── Patient card ── */
  .patient-card {
    margin: 12px 12px 0;
    background: #fff; border-radius: 6px;
    border-left: 4px solid #2c6fad;
    padding: 10px 14px;
    display: flex; gap: 20px; flex-wrap: wrap;
    box-shadow: 0 1px 3px rgba(0,0,0,.07);
  }
  .patient-card .field { min-width: 140px; }
  .patient-card .field label { font-size: 10px; text-transform: uppercase; color: #64748b; display: block; margin-bottom: 2px; }
  .patient-card .field span  { font-weight: 600; font-size: 13px; }

  /* ── Stat bar ── */
  .stat-bar {
    margin: 8px 12px;
    background: #e8f0fb; border-radius: 5px;
    padding: 6px 14px; font-size: 11px; color: #475569;
    display: flex; gap: 16px; align-items: center; flex-wrap: wrap;
  }
  .stat-bar strong { color: #1a4f8a; }

  /* ── Timeline ── */
  .timeline { padding: 8px 12px 20px; }

  /* ── SOAP Card ── */
  .soap-card {
    background: #fff; border-radius: 7px;
    border: 1px solid #e2e8f0;
    margin-bottom: 10px;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
    overflow: hidden;
  }
  .soap-card-header {
    background: #f8fafc; border-bottom: 1px solid #e2e8f0;
    padding: 7px 12px; display: flex; align-items: center; gap: 8px;
    cursor: pointer; user-select: none;
  }
  .soap-card-header:hover { background: #f1f5f9; }
  .soap-card-header .date   { font-weight: 700; font-size: 12px; color: #1a4f8a; }
  .soap-card-header .ppa    { font-size: 12px; color: #475569; }
  .soap-card-header .chevron { margin-left: auto; color: #94a3b8; font-size: 12px; transition: transform .2s; }
  .soap-card-header.open .chevron { transform: rotate(180deg); }
  .badge-tipe { display: inline-block; font-size: 10px; font-weight: 700; padding: 1px 7px; border-radius: 3px; }
  .badge-rj   { background: #d1fae5; color: #065f46; }
  .badge-ri   { background: #dbeafe; color: #1e40af; }

  .soap-card-body { padding: 10px 12px; display: none; }
  .soap-card-body.open { display: block; }

  /* ── SOAP grid ── */
  .soap-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
  @media (max-width: 640px) { .soap-grid { grid-template-columns: 1fr; } }

  .soap-section { background: #f8fafc; border-radius: 5px; padding: 8px 10px; border-left: 3px solid; }
  .soap-section.s  { border-color: #0ea5e9; }
  .soap-section.o  { border-color: #16a34a; }
  .soap-section.a  { border-color: #d97706; }
  .soap-section.p  { border-color: #7c3aed; }
  .soap-section h4 { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
  .soap-section.s h4 { color: #0ea5e9; }
  .soap-section.o h4 { color: #16a34a; }
  .soap-section.a h4 { color: #d97706; }
  .soap-section.p h4 { color: #7c3aed; }
  .soap-section p { font-size: 12px; line-height: 1.6; color: #374151; }

  .vital-row { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 5px; }
  .vital-chip { background: #e8f5e9; color: #1b5e20; font-size: 11px; padding: 1px 7px; border-radius: 10px; }

  /* ── Sub-sections (eresep, penunjang, emr) ── */
  .sub-section { margin-top: 8px; }
  .sub-section-title {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    color: #64748b; letter-spacing: .4px; margin-bottom: 4px;
    padding-bottom: 3px; border-bottom: 1px solid #e2e8f0;
  }

  .trans-group { margin-bottom: 8px; }
  .trans-label { font-size: 10px; font-weight: 700; color: #0ea5e9; }
  .trans-date  { font-size: 10px; color: #94a3b8; margin-left: 5px; }

  ol.drug-list, ul.exam-list { padding-left: 16px; margin: 3px 0; }
  ol.drug-list li, ul.exam-list li { font-size: 11px; margin-bottom: 3px; line-height: 1.5; }
  .drug-name { font-weight: 600; }
  .drug-dose { color: #475569; }
  .drug-note { color: #94a3b8; font-size: 10px; }

  .dept-label { font-size: 10px; font-weight: 700; color: #7c3aed; }
  .exam-status-done { color: #16a34a; }
  .exam-status-pend { color: #d97706; }

  .emr-link { color: #0ea5e9; text-decoration: none; font-size: 11px; }
  .emr-link:hover { text-decoration: underline; }

  .dash { color: #94a3b8; font-size: 11px; }

  /* ── Riwayat table ── */
  .riwayat-table { font-size: 11px; width: 100%; margin-top: 5px; border-collapse: collapse; }
  .riwayat-table td { padding: 1px 4px; vertical-align: top; }
  .riwayat-table td:first-child { color: #64748b; white-space: nowrap; padding-right: 8px; }

  /* ── Diagnosa chips ── */
  .diagnosa-chip {
    display: inline-block; font-size: 11px; background: #fff7ed;
    color: #92400e; border: 1px solid #fde68a;
    border-radius: 4px; padding: 1px 6px; margin: 1px 2px;
  }
  .diagnosa-sek-chip {
    display: inline-block; font-size: 11px; background: #f1f5f9;
    color: #475569; border: 1px solid #e2e8f0;
    border-radius: 4px; padding: 1px 6px; margin: 1px 2px;
  }

  /* ── Empty state ── */
  .empty-state { text-align: center; padding: 40px 16px; color: #94a3b8; }
  .empty-state .icon { font-size: 36px; margin-bottom: 8px; }

  /* ── Expired overlay ── */
  #expired-overlay {
    display: none; position: fixed; inset: 0; z-index: 999;
    background: rgba(15,23,42,.7); align-items: center; justify-content: center;
  }
  #expired-overlay.show { display: flex; }
  .expired-box {
    background: #fff; border-radius: 10px; padding: 30px 36px;
    text-align: center; max-width: 360px; box-shadow: 0 20px 60px rgba(0,0,0,.3);
  }
  .expired-box .icon { font-size: 40px; margin-bottom: 10px; color: #ef4444; }
  .expired-box h3 { font-size: 16px; color: #1e293b; margin-bottom: 8px; }
  .expired-box p  { font-size: 12px; color: #64748b; line-height: 1.6; }
</style>
</head>
<body>

<!-- ── TOP BAR ── -->
<div class="top-bar">
  <div>
    <div class="patient-name"><?php echo htmlspecialchars(strtoupper($pasien->nama_pasien)); ?></div>
    <div class="patient-meta">
      No. MR: <?php echo htmlspecialchars($nomr); ?>
      &nbsp;&bull;&nbsp;
      <?php echo ($pasien->jen_kelamin == 'L') ? 'Laki-laki' : 'Perempuan'; ?>
      &nbsp;&bull;&nbsp;
      <?php echo $pasien->tgl_lhr ? date('d/m/Y', strtotime($pasien->tgl_lhr)) : '-'; ?>
    </div>
  </div>
  <div class="spacer"></div>
  <div class="session-badge" id="sessionBadge">
    <span class="session-dot" id="sessionDot"></span>
    <span id="sessionTimer">Memuat...</span>
  </div>
</div>

<!-- ── PATIENT DETAIL CARD ── -->
<div class="patient-card">
  <?php
    $fields = [
      'No. MR'       => htmlspecialchars($pasien->no_mr),
      'Nama Pasien'  => htmlspecialchars(strtoupper($pasien->nama_pasien)),
      'Jenis Kelamin'=> ($pasien->jen_kelamin == 'L') ? 'Laki-laki' : 'Perempuan',
      'Tgl Lahir'    => ($pasien->tgl_lhr ? date('d/m/Y', strtotime($pasien->tgl_lhr)) : '-'),
      'No. KTP'      => htmlspecialchars(isset($pasien->no_ktp) ? $pasien->no_ktp : '-'),
      'No. BPJS'     => htmlspecialchars(isset($pasien->no_kartu_bpjs) ? $pasien->no_kartu_bpjs : '-'),
    ];
    foreach ($fields as $label => $val):
  ?>
    <div class="field">
      <label><?php echo $label; ?></label>
      <span><?php echo $val; ?></span>
    </div>
  <?php endforeach; ?>
</div>

<!-- ── STAT BAR ── -->
<div class="stat-bar">
  <span>Total kunjungan tercatat: <strong><?php echo count($records); ?></strong></span>
  <?php if (!empty($records)): ?>
    <span>Kunjungan terakhir: <strong><?php echo $this->tanggal->formatDateTime($records[0]->tanggal); ?></strong></span>
  <?php endif; ?>
  <span style="margin-left:auto;font-size:10px;color:#94a3b8;">Data SOAP / CPPT &mdash; <?php echo date('d/m/Y H:i'); ?></span>
</div>

<!-- ── TIMELINE ── -->
<div class="timeline">

<?php if (empty($records)): ?>
  <div class="empty-state">
    <div class="icon">&#128196;</div>
    <p>Belum ada data SOAP / CPPT untuk pasien ini.</p>
  </div>
<?php else: ?>

  <?php foreach ($records as $idx => $row):
    $kunjungan_key  = $row->no_kunjungan;
    $registrasi_key = $row->no_registrasi;
    $card_id = 'card_' . $idx;

    // Badge tipe
    $badge = ($row->tipe == 'RJ')
      ? '<span class="badge-tipe badge-rj">RJ</span>'
      : '<span class="badge-tipe badge-ri">RI</span>';

    // Vital signs chips
    $vitals = [];
    if ($row->tinggi_badan)  $vitals[] = 'TB: ' . $row->tinggi_badan . ' cm';
    if ($row->berat_badan)   $vitals[] = 'BB: '  . $row->berat_badan . ' kg';
    if ($row->tekanan_darah) $vitals[] = 'TD: '  . $row->tekanan_darah . ' mmHg';
    if ($row->nadi)          $vitals[] = 'Nadi: ' . $row->nadi . ' bpm';
    if ($row->suhu)          $vitals[] = 'Suhu: ' . $row->suhu . ' °C';

    // Diagnosa sekunder
    $ds_list = array_filter(array_map('trim', explode('|', (string) $row->diagnosa_sekunder)));

    // ICD-9 / tindakan
    $icd9_codes = array_filter(array_map('trim', explode('|', (string) $row->kode_icd9)));
    $icd9_texts = array_values(array_filter(array_map('trim', explode('|', (string) $row->text_icd9))));

    // Riwayat rows
    $riwayat = [];
    if ($row->riwayat_penyakit_dahulu) {
      $v = ($row->riwayat_penyakit_dahulu == 'ada') ? '<span style="color:#16a34a">Ya</span>' : '<span style="color:#94a3b8">Tidak</span>';
      if ($row->riwayat_penyakit_dahulu_ket) $v .= ': ' . htmlspecialchars($row->riwayat_penyakit_dahulu_ket);
      $riwayat[] = ['Peny. Dahulu', $v];
    }
    if ($row->riwayat_operasi) {
      $v = ($row->riwayat_operasi == 'ada') ? '<span style="color:#16a34a">Ya</span>' : '<span style="color:#94a3b8">Tidak</span>';
      if ($row->riwayat_operasi_ket) $v .= ': ' . htmlspecialchars($row->riwayat_operasi_ket);
      $riwayat[] = ['Operasi', $v];
    }
    if ($row->riwayat_alergi) {
      $v = ($row->riwayat_alergi == 'ada') ? '<span style="color:#dc2626;font-weight:bold">Ya</span>' : '<span style="color:#94a3b8">Tidak</span>';
      if ($row->riwayat_alergi_ket) $v .= ': ' . htmlspecialchars($row->riwayat_alergi_ket);
      $riwayat[] = ['Alergi', $v];
    }
    if ($row->catatan_assesmen)  $riwayat[] = ['Catatan', nl2br(htmlspecialchars($row->catatan_assesmen))];
    if ($row->resep_iter) {
      $it = htmlspecialchars($row->resep_iter) . ($row->jumlah_iter ? ' &times; ' . $row->jumlah_iter : '');
      $riwayat[] = ['Resep Iter', $it];
    }
  ?>

  <div class="soap-card">
    <!-- HEADER (clickable) -->
    <div class="soap-card-header" onclick="toggleCard('<?php echo $card_id; ?>')" id="hdr_<?php echo $card_id; ?>">
      <?php echo $badge; ?>
      <span class="date"><?php echo $this->tanggal->formatDateTime($row->tanggal); ?></span>
      <span class="ppa">
        <small style="color:#94a3b8;"><?php echo strtoupper(htmlspecialchars($row->ppa)); ?></small>
        &nbsp;<?php echo htmlspecialchars($row->nama_ppa); ?>
      </span>
      <?php if ($row->kode_icd_diagnosa): ?>
        <span class="diagnosa-chip"><?php echo htmlspecialchars($row->kode_icd_diagnosa); ?></span>
      <?php endif; ?>
      <span class="chevron">&#9660;</span>
    </div>

    <!-- BODY (collapsible) -->
    <div class="soap-card-body<?php echo ($idx === 0) ? ' open' : ''; ?>" id="<?php echo $card_id; ?>">

      <!-- SOAP Grid -->
      <div class="soap-grid">

        <!-- S — Subjective -->
        <div class="soap-section s">
          <h4>S &mdash; Subjective</h4>
          <?php if ($row->subjective): ?>
            <p><?php echo nl2br(htmlspecialchars($row->subjective)); ?></p>
          <?php else: ?>
            <span class="dash">—</span>
          <?php endif; ?>
          <?php if ($riwayat): ?>
            <table class="riwayat-table" style="margin-top:6px;border-top:1px dashed #e2e8f0;padding-top:5px;">
              <?php foreach ($riwayat as $r): ?>
                <tr>
                  <td><?php echo $r[0]; ?></td>
                  <td><?php echo $r[1]; ?></td>
                </tr>
              <?php endforeach; ?>
            </table>
          <?php endif; ?>
        </div>

        <!-- O — Objective -->
        <div class="soap-section o">
          <h4>O &mdash; Objective</h4>
          <?php if ($vitals): ?>
            <div class="vital-row">
              <?php foreach ($vitals as $v): ?>
                <span class="vital-chip"><?php echo $v; ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <?php if ($row->objective): ?>
            <p><?php echo nl2br(htmlspecialchars($row->objective)); ?></p>
          <?php else: ?>
            <span class="dash">—</span>
          <?php endif; ?>
        </div>

        <!-- A — Assessment -->
        <div class="soap-section a">
          <h4>A &mdash; Assessment</h4>
          <?php if ($row->kode_icd_diagnosa): ?>
            <span class="diagnosa-chip"><?php echo htmlspecialchars($row->kode_icd_diagnosa); ?></span>
            <?php if ($row->assesment): ?>
              <p style="margin-top:3px;"><?php echo nl2br(htmlspecialchars($row->assesment)); ?></p>
            <?php endif; ?>
          <?php elseif ($row->assesment): ?>
            <p><?php echo nl2br(htmlspecialchars($row->assesment)); ?></p>
          <?php else: ?>
            <span class="dash">—</span>
          <?php endif; ?>

          <?php if ($ds_list): ?>
            <div style="margin-top:4px;">
              <small style="color:#64748b;font-size:10px;">Diagnosa Sekunder:</small><br>
              <?php foreach ($ds_list as $ds): ?>
                <span class="diagnosa-sek-chip"><?php echo htmlspecialchars($ds); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if ($icd9_codes): ?>
            <div style="margin-top:5px;">
              <small style="color:#7c3aed;font-size:10px;font-weight:700;">TINDAKAN (ICD-9):</small>
              <?php foreach ($icd9_codes as $k => $kode): ?>
                <div style="font-size:11px;">
                  <strong><?php echo htmlspecialchars($kode); ?></strong>
                  <?php if (isset($icd9_texts[$k])): ?> &mdash; <?php echo htmlspecialchars($icd9_texts[$k]); ?><?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- P — Planning -->
        <div class="soap-section p">
          <h4>P &mdash; Planning</h4>
          <?php if ($row->planning): ?>
            <p><?php echo nl2br(htmlspecialchars(str_replace('null', '', $row->planning))); ?></p>
          <?php else: ?>
            <span class="dash">—</span>
          <?php endif; ?>
          <?php if ($row->tgl_kontrol_kembali): ?>
            <p style="margin-top:4px;font-size:11px;color:#64748b;">
              &#128197; Kontrol: <?php echo $this->tanggal->formatDate($row->tgl_kontrol_kembali); ?>
              <?php if ($row->catatan_kontrol_kembali): ?>
                &mdash; <?php echo htmlspecialchars($row->catatan_kontrol_kembali); ?>
              <?php endif; ?>
            </p>
          <?php endif; ?>
        </div>

      </div><!-- /.soap-grid -->

      <!-- ── ERESEP ── -->
      <div class="sub-section">
        <div class="sub-section-title">&#128138; E-Resep Dokter</div>
        <?php if (!empty($map_eresep[$kunjungan_key])): ?>
          <?php
            $er_by_trans = [];
            foreach ($map_eresep[$kunjungan_key] as $er) {
              $er_by_trans[$er->kode_trans_far ?: 'LAINNYA'][] = $er;
            }
          ?>
          <?php foreach ($er_by_trans as $trans_key => $ers): ?>
            <div class="trans-group">
              <span class="trans-label"><?php echo htmlspecialchars($trans_key); ?></span>
              <?php if (!empty($ers[0]->tgl_trans)): ?>
                <span class="trans-date">&mdash; <?php echo $this->tanggal->formatDate($ers[0]->tgl_trans); ?></span>
              <?php endif; ?>
              <ol class="drug-list">
                <?php foreach ($ers as $er): ?>
                  <li>
                    <span class="drug-name"><?php echo strtoupper(htmlspecialchars($er->nama_brg)); ?></span><br>
                    <span class="drug-dose">
                      <?php if ($er->jml_dosis && $er->jml_dosis_obat): ?>
                        <?php echo $er->jml_dosis; ?> &times; <?php echo $er->jml_dosis_obat; ?> <?php echo htmlspecialchars($er->satuan_obat); ?>
                        &mdash;
                      <?php endif; ?>
                      <?php echo htmlspecialchars($er->aturan_pakai); ?>
                      <?php if ($er->jml_pesan): ?>
                        (Qty: <?php echo $er->jml_pesan; ?> <?php echo htmlspecialchars($er->satuan_obat); ?>)
                      <?php endif; ?>
                    </span>
                    <?php if ($er->keterangan): ?>
                      <br><span class="drug-note"><?php echo htmlspecialchars($er->keterangan); ?></span>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>
              </ol>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <span class="dash">Tidak ada data e-resep.</span>
        <?php endif; ?>
      </div>

      <!-- ── PENUNJANG ── -->
      <div class="sub-section">
        <div class="sub-section-title">&#128203; Pemeriksaan Penunjang</div>
        <?php if (!empty($map_penunjang[$registrasi_key])): ?>
          <?php
            $pnj_by_dept = [];
            foreach ($map_penunjang[$registrasi_key] as $pnj) {
              $dept = $pnj->tujuan_bagian ? strtoupper($pnj->tujuan_bagian) : 'LAINNYA';
              $pnj_by_dept[$dept][] = $pnj;
            }
          ?>
          <?php foreach ($pnj_by_dept as $dept => $items): ?>
            <div class="trans-group">
              <span class="dept-label"><?php echo htmlspecialchars($dept); ?></span>
              <ul class="exam-list">
                <?php foreach ($items as $pnj):
                  $tarif_parts = array_filter(array_map('trim', explode('|', (string) $pnj->nama_tarif)));
                  $tarif_text  = $tarif_parts ? implode(', ', array_map('htmlspecialchars', $tarif_parts)) : htmlspecialchars($pnj->kode_penunjang);
                  $status_cls  = ($pnj->status_isihasil == 'selesai') ? 'exam-status-done' : 'exam-status-pend';
                ?>
                  <li>
                    <?php echo $tarif_text; ?>
                    <br><small>
                      <?php if ($pnj->tgl_isihasil): ?>
                        <?php echo $this->tanggal->formatDate($pnj->tgl_isihasil); ?>
                        &mdash;
                      <?php endif; ?>
                      <?php if ($pnj->status_isihasil): ?>
                        <span class="<?php echo $status_cls; ?>"><?php echo htmlspecialchars($pnj->status_isihasil); ?></span>
                      <?php endif; ?>
                      <?php if ($pnj->dokter): ?>
                        &mdash; <?php echo htmlspecialchars($pnj->dokter); ?>
                      <?php endif; ?>
                    </small>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <span class="dash">Tidak ada data penunjang.</span>
        <?php endif; ?>
      </div>

      <!-- ── FILE EMR ── -->
      <?php if (!empty($map_emr[$registrasi_key])): ?>
        <div class="sub-section">
          <div class="sub-section-title">&#128206; Lampiran Dokumen EMR</div>
          <ol class="drug-list">
            <?php foreach ($map_emr[$registrasi_key] as $emr):
              $label = $emr->csm_dex_nama_dok;
              if (strpos($label, '-') !== false) $label = trim(explode('-', $label)[0]);
              $url = rtrim($emr->base_url_dok, '/') . '/' . ltrim($emr->csm_dex_fullpath, '/');
            ?>
              <li>
                <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="emr-link">
                  <?php echo htmlspecialchars(strtoupper($label)); ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ol>
        </div>
      <?php endif; ?>

    </div><!-- /.soap-card-body -->
  </div><!-- /.soap-card -->

  <?php endforeach; ?>
<?php endif; ?>
</div><!-- /.timeline -->

<!-- ── EXPIRED OVERLAY ── -->
<div id="expired-overlay">
  <div class="expired-box">
    <div class="icon">&#9203;</div>
    <h3>Sesi Telah Habis</h3>
    <p>Link tampilan SOAP ini sudah tidak berlaku.<br>
    Silakan minta link baru melalui aplikasi Anda.</p>
  </div>
</div>

<script>
(function() {
  var expiresAt = <?php echo (int) $expires_at; ?>;
  var badge     = document.getElementById('sessionBadge');
  var dot       = document.getElementById('sessionDot');
  var timer     = document.getElementById('sessionTimer');
  var overlay   = document.getElementById('expired-overlay');

  function formatSecs(s) {
    var h = Math.floor(s / 3600);
    var m = Math.floor((s % 3600) / 60);
    var sc = s % 60;
    if (h > 0) return h + 'j ' + m + 'm';
    if (m > 0) return m + 'm ' + sc + 'd';
    return sc + 'd';
  }

  function tick() {
    var now  = Math.floor(Date.now() / 1000);
    var left = expiresAt - now;

    if (left <= 0) {
      timer.textContent = 'Sesi habis';
      badge.classList.add('expired');
      dot.classList.add('expired');
      overlay.classList.add('show');
      return; // stop ticking
    }

    timer.textContent = 'Sesi: ' + formatSecs(left);

    if (left <= 300) { // last 5 min
      badge.classList.add('expiring');
      dot.classList.add('expiring');
    }

    setTimeout(tick, 1000);
  }

  tick();
})();

function toggleCard(id) {
  var body = document.getElementById(id);
  var hdr  = document.getElementById('hdr_' + id);
  body.classList.toggle('open');
  hdr.classList.toggle('open');
}
</script>
</body>
</html>
