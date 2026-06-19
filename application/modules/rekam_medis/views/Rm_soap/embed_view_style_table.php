<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SOAP Pasien &mdash; <?php echo htmlspecialchars(strtoupper($pasien->nama_pasien)); ?></title>

<!-- Bootstrap 3 -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<!-- DataTables Bootstrap 3 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">

<style>
  *, *::before, *::after { box-sizing: border-box; }
  body { font-family: 'Segoe UI', system-ui, sans-serif; font-size: 13px; background: #f1f5f9; color: #1e293b; margin: 0; padding: 0; }

  /* ── Top bar ── */
  .top-bar {
    position: sticky; top: 0; z-index: 100;
    background: #1a4f8a; color: #fff;
    padding: 8px 16px;
    display: -webkit-box; display: -ms-flexbox; display: flex;
    -webkit-box-align: center; -ms-flex-align: center; align-items: center;
    gap: 12px; box-shadow: 0 2px 6px rgba(0,0,0,.25);
  }
  .top-bar .patient-name { font-weight: 700; font-size: 14px; }
  .top-bar .patient-meta { font-size: 11px; color: #bfdbfe; }
  .top-bar .spacer { -webkit-box-flex: 1; -ms-flex: 1; flex: 1; }
  .session-badge {
    font-size: 11px; background: rgba(255,255,255,.15);
    border-radius: 4px; padding: 3px 8px;
    display: -webkit-box; display: -ms-flexbox; display: flex;
    -webkit-box-align: center; -ms-flex-align: center; align-items: center; gap: 5px;
  }
  .session-badge.expiring { background: rgba(239,68,68,.4); }
  .session-badge.expired  { background: rgba(239,68,68,.7); }
  .session-dot { width: 7px; height: 7px; border-radius: 50%; background: #4ade80; display: inline-block; }
  .session-dot.expiring { background: #fbbf24; }
  .session-dot.expired  { background: #f87171; }

  /* ── Patient card ── */
  .patient-card {
    margin: 12px 12px 0;
    background: #fff; border-radius: 6px;
    border-left: 4px solid #2c6fad;
    padding: 10px 14px;
    display: -webkit-box; display: -ms-flexbox; display: flex;
    gap: 20px; -ms-flex-wrap: wrap; flex-wrap: wrap;
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
    display: -webkit-box; display: -ms-flexbox; display: flex;
    gap: 16px; -webkit-box-align: center; -ms-flex-align: center; align-items: center;
    -ms-flex-wrap: wrap; flex-wrap: wrap;
  }
  .stat-bar strong { color: #1a4f8a; }

  /* ── Table wrapper ── */
  .table-wrapper { padding: 10px 12px 24px; }

  /* ── DataTable overrides ── */
  #soap-table td { vertical-align: top; font-size: 12px; }
  #soap-table th { background: #f1f5f9; font-size: 11px; font-weight: 700; white-space: nowrap; }
  .dataTables_wrapper .dataTables_filter { text-align: left; margin-bottom: 6px; }
  .dataTables_wrapper .dataTables_length { margin-bottom: 6px; }
  .dataTables_wrapper .dataTables_info   { font-size: 11px; }

  /* ── Badges ── */
  .badge-tipe { display: inline-block; font-size: 10px; font-weight: 700; padding: 1px 7px; border-radius: 3px; }
  .badge-rj   { background: #d1fae5; color: #065f46; }
  .badge-ri   { background: #dbeafe; color: #1e40af; }

  /* ── Vital chips ── */
  .vital-chip { background: #e8f5e9; color: #1b5e20; font-size: 10px; padding: 1px 6px; border-radius: 10px; display: inline-block; margin: 1px 1px 2px; }

  /* ── Diagnosa chips ── */
  .diagnosa-chip {
    display: inline-block; font-size: 10px; background: #fff7ed;
    color: #92400e; border: 1px solid #fde68a;
    border-radius: 3px; padding: 1px 5px; margin: 1px;
  }
  .diagnosa-sek-chip {
    display: inline-block; font-size: 10px; background: #f1f5f9;
    color: #475569; border: 1px solid #e2e8f0;
    border-radius: 3px; padding: 1px 5px; margin: 1px;
  }

  /* ── Truncated cells ── */
  .cell-expand .cell-text {
    max-height: 72px; overflow: hidden;
    line-height: 1.5; font-size: 12px; color: #374151;
  }
  .cell-expand .cell-text.expanded { max-height: none; }
  .btn-toggle {
    font-size: 10px; color: #0ea5e9;
    background: none; border: none; padding: 1px 0;
    cursor: pointer; display: block; margin-top: 2px;
  }
  .btn-toggle:hover { text-decoration: underline; }

  /* ── Drug / exam lists ── */
  .drug-name { font-weight: 600; }
  .drug-dose { color: #475569; font-size: 11px; }
  .dept-label { font-size: 10px; font-weight: 700; color: #7c3aed; }
  .emr-link { color: #0ea5e9; text-decoration: none; font-size: 11px; }
  .emr-link:hover { text-decoration: underline; }
  .dash { color: #94a3b8; font-size: 11px; }

  /* ── E-Resep accordion ── */
  .er-acc-hdr {
    display: -webkit-box; display: -ms-flexbox; display: flex;
    -webkit-box-align: center; -ms-flex-align: center; align-items: center;
    gap: 4px;
    background: #e0f2fe; border: 1px solid #bae6fd;
    border-radius: 4px; padding: 3px 7px;
    font-size: 10px; font-weight: 700; color: #0369a1;
    cursor: pointer; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;
    margin-bottom: 2px; width: 100%;
  }
  .er-acc-hdr:hover { background: #bae6fd; }
  .er-acc-count {
    margin-left: auto; font-weight: 600;
    background: #0369a1; color: #fff;
    border-radius: 10px; padding: 0 6px; font-size: 9px;
  }
  .er-acc-arrow { font-size: 9px; color: #0369a1; -ms-flex-negative: 0; flex-shrink: 0; }
  .er-acc-body {
    display: none;
    padding: 3px 0 3px 6px;
    border-left: 2px solid #bae6fd;
    margin-bottom: 4px;
  }
  .er-acc-body.open { display: block; }

  /* ── Empty state ── */
  .empty-state { text-align: center; padding: 48px 16px; color: #94a3b8; }
  .empty-state .icon { font-size: 36px; margin-bottom: 8px; }

  /* ── Expired overlay ── */
  #expired-overlay {
    display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999;
    background: rgba(15,23,42,.72);
    -webkit-box-align: center; -ms-flex-align: center; align-items: center;
    -webkit-box-pack: center; -ms-flex-pack: center; justify-content: center;
  }
  #expired-overlay.show { display: -webkit-box; display: -ms-flexbox; display: flex; }
  .expired-box {
    background: #fff; border-radius: 10px; padding: 30px 36px;
    text-align: center; max-width: 360px;
    box-shadow: 0 20px 60px rgba(0,0,0,.3);
  }
  .expired-box .icon { font-size: 42px; margin-bottom: 10px; }
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

<!-- ── PATIENT CARD ── -->
<div class="patient-card">
  <?php
    $fields = array(
      'No. MR'        => htmlspecialchars($pasien->no_mr),
      'Nama Pasien'   => htmlspecialchars(strtoupper($pasien->nama_pasien)),
      'Jenis Kelamin' => ($pasien->jen_kelamin == 'L') ? 'Laki-laki' : 'Perempuan',
      'Tgl Lahir'     => ($pasien->tgl_lhr ? date('d/m/Y', strtotime($pasien->tgl_lhr)) : '-'),
      'No. KTP'       => htmlspecialchars(isset($pasien->no_ktp) ? $pasien->no_ktp : '-'),
      'No. BPJS'      => htmlspecialchars(isset($pasien->no_kartu_bpjs) ? $pasien->no_kartu_bpjs : '-'),
    );
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

<!-- ── TABLE WRAPPER ── -->
<div class="table-wrapper">

<?php if (empty($records)): ?>
  <div class="empty-state">
    <div class="icon">&#128196;</div>
    <p>Belum ada data SOAP / CPPT untuk pasien ini.</p>
  </div>
<?php else: ?>

  <table id="soap-table" class="table table-bordered table-hover table-condensed" style="width:100%;">
    <thead>
      <tr>
        <th style="width:40px;text-align:center;">No</th>
        <th style="width:120px;">Tanggal & PPA</th>
        <th style="min-width:170px;">Subjective</th>
        <th style="min-width:170px;">Objective</th>
        <th style="min-width:170px;">Assesment</th>
        <th style="min-width:170px;">Planning</th>
        <th style="min-width:130px;">File EMR</th>
      </tr>
    </thead>
    <tbody>

    <?php foreach ($records as $idx => $row):
      $kunjungan_key  = $row->no_kunjungan;
      $registrasi_key = $row->no_registrasi;

      // Badge tipe
      $badge = ($row->tipe == 'RJ')
        ? '<span class="badge-tipe badge-rj">RJ</span>'
        : '<span class="badge-tipe badge-ri">RI</span>';

      // Vital signs
      $vitals = array();
      if ($row->tinggi_badan)  $vitals[] = 'TB: ' . $row->tinggi_badan . ' cm';
      if ($row->berat_badan)   $vitals[] = 'BB: '  . $row->berat_badan . ' kg';
      if ($row->tekanan_darah) $vitals[] = 'TD: '  . $row->tekanan_darah . ' mmHg';
      if ($row->nadi)          $vitals[] = 'Nadi: ' . $row->nadi . ' bpm';
      if ($row->suhu)          $vitals[] = 'Suhu: ' . $row->suhu . ' &deg;C';

      // Diagnosa sekunder
      $ds_list = array_filter(array_map('trim', explode('|', (string) $row->diagnosa_sekunder)));

      // ICD-9
      $icd9_codes = array_filter(array_map('trim', explode('|', (string) $row->kode_icd9)));
      $icd9_texts = array_values(array_filter(array_map('trim', explode('|', (string) $row->text_icd9))));
    ?>
    <tr>

      <!-- No -->
      <td style="text-align:center;vertical-align:top;font-weight:600;"><?php echo $idx + 1; ?></td>

      <!-- Tanggal + Tipe -->
      <td>
        <?php echo $badge; ?>
        <div style="font-size:11px;color:#1a4f8a;font-weight:600;margin-top:3px;">
          <?php echo $this->tanggal->formatDateTime($row->tanggal); ?>
        </div>
        
        <small style="color:#94a3b8;display:block;"><?php echo strtoupper(htmlspecialchars($row->ppa)); ?></small>
        <span style="font-weight:600;"><?php echo htmlspecialchars($row->nama_ppa); ?></span>
        <?php if ($row->kode_icd_diagnosa): ?>
          <div style="margin-top:4px;">
            <span class="diagnosa-chip"><?php echo htmlspecialchars($row->kode_icd_diagnosa); ?></span>
            
          </div>
        <?php endif; ?>
      </td>

      <!-- Subjective -->
      <td>
        <?php if ($row->subjective): ?>
          <div class="cell-expand">
            <div class="cell-text"><?php echo nl2br(htmlspecialchars($row->subjective)); ?></div>
            <button class="btn-toggle" onclick="toggleCell(this)">Selengkapnya &#8595;</button>
          </div>
        <?php else: ?>
          <span class="dash">&#8212;</span>
        <?php endif; ?>
      </td>

      <!-- Objective -->
      <td>
        <?php if ($vitals): ?>
          <div style="margin-bottom:4px;">
            <?php foreach ($vitals as $v): ?>
              <span class="vital-chip"><?php echo $v; ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <?php if ($row->objective): ?>
          <div class="cell-expand">
            <div class="cell-text"><?php echo nl2br(htmlspecialchars($row->objective)); ?></div>
            <button class="btn-toggle" onclick="toggleCell(this)">Selengkapnya &#8595;</button>
          </div>
        <?php elseif (!$vitals): ?>
          <span class="dash">&#8212;</span>
        <?php endif; ?>
      </td>

      <!-- Assesment -->
      <td>
        <?php if ($row->assesment): ?>
          <div class="cell-expand">
            <div class="cell-text"><?php echo nl2br(htmlspecialchars($row->assesment)); ?></div>
            <button class="btn-toggle" onclick="toggleCell(this)">Selengkapnya &#8595;</button>
          </div>
        <?php elseif (!$row->kode_icd_diagnosa && !$ds_list && !$icd9_codes): ?>
          <span class="dash">&#8212;</span>
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
          <div style="margin-top:4px;font-size:10px;font-weight:700;color:#7c3aed;">ICD-9 / Tindakan:</div>
          <?php foreach ($icd9_codes as $k => $kode): ?>
            <div style="font-size:10px;">
              <strong><?php echo htmlspecialchars($kode); ?></strong>
              <?php if (isset($icd9_texts[$k])): ?> &mdash; <?php echo htmlspecialchars($icd9_texts[$k]); ?><?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </td>

      <!-- Planning -->
      <td>
        <?php if ($row->planning): ?>
          <div class="cell-expand">
            <div class="cell-text"><?php echo nl2br(htmlspecialchars(str_replace('null', '', $row->planning))); ?></div>
            <button class="btn-toggle" onclick="toggleCell(this)">Selengkapnya &#8595;</button>
          </div>
        <?php else: ?>
          <span class="dash">&#8212;</span>
        <?php endif; ?>
        <?php if ($row->tgl_kontrol_kembali): ?>
          <div style="font-size:10px;color:#64748b;margin-top:4px;">
            &#128197; <?php echo $this->tanggal->formatDate($row->tgl_kontrol_kembali); ?>
            <?php if ($row->catatan_kontrol_kembali): ?>
              &mdash; <?php echo htmlspecialchars($row->catatan_kontrol_kembali); ?>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <?php if ($row->resep_iter): ?>
          <div style="font-size:10px;color:#475569;margin-top:2px;">
            Iter: <?php echo htmlspecialchars($row->resep_iter); ?>
            <?php if ($row->jumlah_iter): ?> &times; <?php echo $row->jumlah_iter; ?><?php endif; ?>
          </div>
        <?php endif; ?>
        <!-- eresep accordion -->
        <?php if (!empty($map_eresep[$kunjungan_key])): ?>
          <?php
            $er_by_trans = array();
            foreach ($map_eresep[$kunjungan_key] as $er) {
              $tk = $er->kode_trans_far ? $er->kode_trans_far : 'LAINNYA';
              $er_by_trans[$tk][] = $er;
            }
          ?>
          <?php foreach ($er_by_trans as $trans_key => $ers):
            $er_total = count($ers);
            $acc_id   = 'eracc_' . $idx . '_' . preg_replace('/[^a-z0-9]/i', '_', $trans_key);
          ?>
            <div class="er-group" style="margin-bottom:3px;">
              <div class="er-acc-hdr" onclick="toggleErAcc(this)" data-target="<?php echo $acc_id; ?>">
                <span><?php echo htmlspecialchars($trans_key); ?></span>
                <?php if (!empty($ers[0]->tgl_trans)): ?>
                  <span style="color:#64748b;font-weight:400;">&mdash; <?php echo $this->tanggal->formatDate($ers[0]->tgl_trans); ?></span>
                <?php endif; ?>
                <span class="er-acc-count"><?php echo $er_total; ?> obat</span>
                <span class="er-acc-arrow">&#9660;</span>
              </div>
              <div class="er-acc-body" id="<?php echo $acc_id; ?>">
                <ol style="padding-left:14px;margin:3px 0 0;">
                  <?php foreach ($ers as $er): ?>
                    <li style="font-size:11px;margin-bottom:3px;">
                      <span class="drug-name"><?php echo strtoupper(htmlspecialchars($er->nama_brg)); ?></span>
                      <?php if ($er->jml_dosis && $er->jml_dosis_obat): ?>
                        <br>
                        <span class="drug-dose">
                          <?php echo $er->jml_dosis; ?> &times; <?php echo $er->jml_dosis_obat; ?>
                          <?php echo htmlspecialchars($er->satuan_obat); ?>
                          &mdash; <?php echo htmlspecialchars($er->aturan_pakai); ?>
                          <?php if ($er->jml_pesan): ?>
                            (Qty: <?php echo $er->jml_pesan; ?> <?php echo htmlspecialchars($er->satuan_obat); ?>)
                          <?php endif; ?>
                        </span>
                      <?php elseif ($er->aturan_pakai): ?>
                        <br><span class="drug-dose"><?php echo htmlspecialchars($er->aturan_pakai); ?></span>
                      <?php endif; ?>
                      <?php if ($er->keterangan): ?>
                        <br><span style="font-size:10px;color:#94a3b8;"><?php echo htmlspecialchars($er->keterangan); ?></span>
                      <?php endif; ?>
                    </li>
                  <?php endforeach; ?>
                </ol>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <span class="dash">&#8212;</span>
        <?php endif; ?>
        <!-- penunjang -->
        <?php if (!empty($map_penunjang[$registrasi_key])): ?>
          <?php
            $pnj_by_dept = array();
            foreach ($map_penunjang[$registrasi_key] as $pnj) {
              $dept = $pnj->tujuan_bagian ? strtoupper($pnj->tujuan_bagian) : 'LAINNYA';
              $pnj_by_dept[$dept][] = $pnj;
            }
          ?>
          <?php foreach ($pnj_by_dept as $dept => $items): ?>
            <div style="margin-bottom:5px;">
              <span class="dept-label"><?php echo htmlspecialchars($dept); ?></span>
              <ul style="padding-left:14px;margin:3px 0 0;">
                <?php foreach ($items as $pnj):
                  $tarif_parts = array_filter(array_map('trim', explode('|', (string) $pnj->nama_tarif)));
                  $tarif_text  = $tarif_parts
                    ? implode(', ', array_map('htmlspecialchars', $tarif_parts))
                    : htmlspecialchars($pnj->kode_penunjang);
                  $status_done = ($pnj->status_isihasil == 'selesai');
                ?>
                  <li style="font-size:11px;margin-bottom:2px;">
                    <?php echo $tarif_text; ?>
                    <?php if ($pnj->tgl_isihasil): ?>
                      <br><small style="color:#94a3b8;"><?php echo $this->tanggal->formatDate($pnj->tgl_isihasil); ?></small>
                    <?php endif; ?>
                    <?php if ($pnj->status_isihasil): ?>
                      &mdash;
                      <small style="color:<?php echo $status_done ? '#16a34a' : '#d97706'; ?>">
                        <?php echo htmlspecialchars($pnj->status_isihasil); ?>
                      </small>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <span class="dash">&#8212;</span>
        <?php endif; ?>
      </td>

      <!-- File EMR -->
      <td>
        <?php if (!empty($map_emr[$registrasi_key])): ?>
          <ul style="padding-left:14px;margin:0;">
            <?php foreach ($map_emr[$registrasi_key] as $emr):
              $label = $emr->csm_dex_nama_dok;
              if (strpos($label, '-') !== false) {
                $parts_label = explode('-', $label);
                $label = trim($parts_label[0]);
              }
              $url = rtrim($emr->base_url_dok, '/') . '/' . ltrim($emr->csm_dex_fullpath, '/');
            ?>
              <li style="margin-bottom:3px;">
                <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="emr-link">
                  <?php echo htmlspecialchars(strtoupper($label)); ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <span class="dash">&#8212;</span>
        <?php endif; ?>
      </td>

    </tr>
    <?php endforeach; ?>

    </tbody>
  </table>

<?php endif; ?>
</div><!-- /.table-wrapper -->

<!-- ── EXPIRED OVERLAY ── -->
<div id="expired-overlay">
  <div class="expired-box">
    <div class="icon">&#9203;</div>
    <h3>Sesi Telah Habis</h3>
    <p>Link tampilan SOAP ini sudah tidak berlaku.<br>
    Silakan minta link baru melalui aplikasi Anda.</p>
  </div>
</div>

<!-- ── SCRIPTS ── -->
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>

<script>
/* ── Session countdown ── */
(function () {
  var expiresAt = <?php echo (int) $expires_at; ?>;
  var badge   = document.getElementById('sessionBadge');
  var dot     = document.getElementById('sessionDot');
  var timer   = document.getElementById('sessionTimer');
  var overlay = document.getElementById('expired-overlay');

  function addClass(el, cls) {
    if ((' ' + el.className + ' ').indexOf(' ' + cls + ' ') === -1) {
      el.className += ' ' + cls;
    }
  }

  function formatSecs(s) {
    var h = Math.floor(s / 3600);
    var m = Math.floor((s % 3600) / 60);
    var sc = s % 60;
    if (h > 0) return h + 'j ' + (m < 10 ? '0' : '') + m + 'm';
    if (m > 0) return m + 'm ' + (sc < 10 ? '0' : '') + sc + 'd';
    return sc + 'd';
  }

  function tick() {
    var now  = Math.floor(Date.now() / 1000);
    var left = expiresAt - now;
    if (left <= 0) {
      timer.textContent = 'Sesi habis';
      addClass(badge,   'expired');
      addClass(dot,     'expired');
      addClass(overlay, 'show');
      return;
    }
    timer.textContent = 'Sesi: ' + formatSecs(left);
    if (left <= 300) {
      addClass(badge, 'expiring');
      addClass(dot,   'expiring');
    }
    setTimeout(tick, 1000);
  }
  tick();
}());

/* ── Cell expand toggle ── */
function toggleCell(btn) {
  var wrap = btn.parentNode;
  var cell = wrap.querySelector('.cell-text');
  if (!cell) return;
  var expanded = (' ' + cell.className + ' ').indexOf(' expanded ') !== -1;
  if (expanded) {
    cell.className = cell.className.replace(/\s*expanded\s*/g, ' ').replace(/^\s+|\s+$/g, '');
    btn.innerHTML  = 'Selengkapnya &#8595;';
  } else {
    cell.className += ' expanded';
    btn.innerHTML   = 'Lebih sedikit &#8593;';
  }
}

/* ── E-Resep accordion toggle ── */
function toggleErAcc(hdr) {
  var body  = document.getElementById(hdr.getAttribute('data-target'));
  var arrow = hdr.querySelector('.er-acc-arrow');
  if (!body) return;
  var isOpen = (' ' + body.className + ' ').indexOf(' open ') !== -1;
  if (isOpen) {
    body.className = body.className.replace(/\s*open\s*/g, ' ').replace(/^\s+|\s+$/g, '');
    if (arrow) arrow.innerHTML = '&#9660;';
  } else {
    body.className += ' open';
    if (arrow) arrow.innerHTML = '&#9650;';
  }
}

/* ── DataTable init ── */
$(document).ready(function () {
  $('#soap-table').DataTable({
    'ordering'    : false,
    'searching'   : true,
    'pageLength'  : 25,
    'lengthMenu'  : [10, 25, 50, 100],
    'autoWidth'   : false,
    'columnDefs'  : [
      { 'orderable': false, 'targets': [2, 3, 4, 5, 6] }
    ],
    'language': {
      'search'       : 'Cari:',
      'lengthMenu'   : 'Tampilkan _MENU_ data',
      'info'         : 'Menampilkan _START_\u2013_END_ dari _TOTAL_ data',
      'infoEmpty'    : 'Tidak ada data',
      'infoFiltered' : '(disaring dari _MAX_ total data)',
      'zeroRecords'  : 'Tidak ada data yang cocok',
      'emptyTable'   : 'Tidak ada data SOAP',
      'paginate': {
        'previous': '&laquo;',
        'next'    : '&raquo;'
      }
    }
  });
});
</script>

</body>
</html>
