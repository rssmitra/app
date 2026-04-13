<?php
/* ═══════════════════════════════════════════════════════
 * PRE-PROCESSING: Bangun array antrian — logika identik
 * dengan index_farmasi.php (TV display)
 * ═══════════════════════════════════════════════════════ */

// 1. Resep Diterima — sumber: $resep_diterima (dari controller)
$arr_resep_diterima = is_array($resep_diterima) ? $resep_diterima : [];

// 2. Obat Racikan — log_time_3 terisi, log_time_4 belum
$arr_racikan = [];
if (is_array($resep)) {
    foreach ($resep as $r)
        if ($r->log_time_3 != null && $r->log_time_4 == null)
            $arr_racikan[] = $r;
}

// 3. Proses Etiket — log_time_4 terisi, log_time_5 belum
$arr_etiket = [];
if (is_array($resep)) {
    foreach ($resep as $r)
        if ($r->log_time_4 != null && $r->log_time_5 == null)
            $arr_etiket[] = $r;
}

// 4. Siap Diambil — log_time_5 terisi, log_time_6 belum, maks 31 entri (sama dg TV)
$arr_siap_diambil = [];
if (is_array($resep)) {
    foreach ($resep as $r)
        if ($r->log_time_5 != null && $r->log_time_6 == null && count($arr_siap_diambil) < 31)
            $arr_siap_diambil[] = $r;
}

// 5. TAT — gunakan $avg_tat dari controller (sama dg TV) jika tersedia;
//          fallback: hitung inline dari log_time_1 → log_time_5
if (isset($avg_tat) && $avg_tat !== '' && $avg_tat !== '–') {
    $avg_display = $avg_tat;
} else {
    $tat_selesai = 0; $tat_detik = 0;
    if (is_array($resep)) {
        foreach ($resep as $r) {
            if ($r->log_time_5 != null && $r->log_time_1 != null) {
                $tat_selesai++;
                $diff = strtotime($r->log_time_5) - strtotime($r->log_time_1);
                $tat_detik += ($diff > 3600) ? 3600 : $diff;
            }
        }
    }
    if ($tat_selesai > 0 && $tat_detik > 0) {
        $avg = $tat_detik / $tat_selesai;
        $avg_display = sprintf('%02d:%02d:%02d',
            floor($avg / 3600), floor(($avg % 3600) / 60), $avg % 60);
    } else {
        $avg_display = '–';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <title>Antrian Farmasi — RS Setia Mitra</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" />
  <style>
    :root {
      --bg:       #f0f4f8;
      --surface:  #ffffff;
      --border:   #e2e8f0;
      --text:     #1a202c;
      --muted:    #718096;
      --hdr:      #0f4c81;
      --hdr2:     #1565C0;

      /* Warna per tahap */
      --c1: #1565C0; --c1l: #dbeafe; --c1m: #93c5fd;
      --c2: #c2410c; --c2l: #ffedd5; --c2m: #fdba74;
      --c3: #7e22ce; --c3l: #f3e8ff; --c3m: #d8b4fe;
      --c4: #15803d; --c4l: #dcfce7; --c4m: #86efac;

      --radius: 14px;
      --shadow: 0 2px 16px rgba(0,0,0,0.08);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      padding-bottom: 90px;
    }

    /* ─── HEADER ─────────────────────────────────── */
    #hdr {
      background: linear-gradient(135deg, var(--hdr) 0%, var(--hdr2) 100%);
      color: #fff;
      padding: 14px 16px 12px;
      position: sticky;
      top: 0;
      z-index: 200;
      box-shadow: 0 2px 12px rgba(15,76,129,0.25);
    }
    .hdr-top {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 4px;
    }
    .hdr-icon {
      width: 36px; height: 36px;
      background: rgba(255,255,255,0.18);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem; flex-shrink: 0;
    }
    .hdr-title {
      font-size: 1.15rem;
      font-weight: 800;
      letter-spacing: 0.5px;
      line-height: 1.2;
    }
    .hdr-subtitle { font-size: 0.78rem; color: rgba(255,255,255,0.7); font-weight: 400; }
    .hdr-datetime {
      display: flex;
      align-items: center;
      gap: 14px;
      font-size: 0.82rem;
      color: rgba(255,255,255,0.82);
      padding-top: 2px;
    }
    .hdr-datetime i { margin-right: 4px; }

    /* ─── SEARCH BAR ─────────────────────────────── */
    #search-wrap {
      position: sticky;
      top: 82px;
      z-index: 190;
      background: var(--bg);
      padding: 10px 12px 8px;
      border-bottom: 1px solid var(--border);
    }
    .search-inner {
      position: relative;
      max-width: 600px;
      margin: 0 auto;
    }
    .search-inner i {
      position: absolute;
      left: 14px; top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      font-size: 0.95rem;
      pointer-events: none;
    }
    #searchInput {
      width: 100%;
      height: 44px;
      padding: 0 16px 0 40px;
      border-radius: 22px;
      border: 1.5px solid var(--border);
      background: var(--surface);
      font-family: inherit;
      font-size: 0.95rem;
      color: var(--text);
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      box-shadow: 0 1px 6px rgba(0,0,0,0.05);
    }
    #searchInput:focus {
      border-color: var(--hdr2);
      box-shadow: 0 0 0 3px rgba(21,101,192,0.12);
    }
    #searchInput::placeholder { color: #a0aec0; }
    #search-hint {
      text-align: center;
      font-size: 0.75rem;
      color: var(--muted);
      margin-top: 5px;
      display: none;
    }
    #search-hint.visible { display: block; }

    /* ─── PROGRESS PILLS ─────────────────────────── */
    #progress-pills {
      padding: 10px 12px 6px;
      overflow-x: auto;
      white-space: nowrap;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
    }
    #progress-pills::-webkit-scrollbar { display: none; }
    .pill-row { display: inline-flex; align-items: center; gap: 4px; }
    .pill {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 600;
      white-space: nowrap;
      cursor: pointer;
      border: none;
      outline: none;
      transition: opacity 0.15s, transform 0.1s;
    }
    .pill:active { transform: scale(0.96); }
    .pill-1 { background: var(--c1l); color: var(--c1); }
    .pill-2 { background: var(--c2l); color: var(--c2); }
    .pill-3 { background: var(--c3l); color: var(--c3); }
    .pill-4 { background: var(--c4l); color: var(--c4); }
    .pill-sep { color: #cbd5e0; font-size: 0.8rem; padding: 0 2px; }
    .pill .pill-count {
      background: rgba(0,0,0,0.12);
      border-radius: 10px;
      padding: 1px 6px;
      font-size: 0.72rem;
    }

    /* ─── MAIN GRID ──────────────────────────────── */
    #main {
      padding: 8px 12px 16px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      max-width: 900px;
      margin: 0 auto;
    }

    /* Siap Diambil spans full width */
    .q-card.full-width { grid-column: 1 / -1; }

    /* ─── QUEUE CARD ─────────────────────────────── */
    .q-card {
      background: var(--surface);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      border: 1px solid var(--border);
    }
    .q-card-header {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 11px 14px;
      font-size: 0.88rem;
      font-weight: 700;
      letter-spacing: 0.4px;
      text-transform: uppercase;
      border-bottom: 1px solid transparent;
    }
    .q-card-header .icon-box {
      width: 28px; height: 28px;
      border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.85rem; flex-shrink: 0;
    }
    .q-card-header .badge-total {
      margin-left: auto;
      font-size: 0.72rem;
      padding: 2px 9px;
      border-radius: 12px;
      font-weight: 700;
    }

    /* per-column header colors */
    .qh1 { background: var(--c1l); color: var(--c1); border-color: #bfdbfe; }
    .qh1 .icon-box { background: var(--c1); color: #fff; }
    .qh1 .badge-total { background: var(--c1); color: #fff; }

    .qh2 { background: var(--c2l); color: var(--c2); border-color: #fed7aa; }
    .qh2 .icon-box { background: var(--c2); color: #fff; }
    .qh2 .badge-total { background: var(--c2); color: #fff; }

    .qh3 { background: var(--c3l); color: var(--c3); border-color: #e9d5ff; }
    .qh3 .icon-box { background: var(--c3); color: #fff; }
    .qh3 .badge-total { background: var(--c3); color: #fff; }

    .qh4 {
      background: linear-gradient(90deg, #15803d 0%, #16a34a 100%);
      color: #fff;
      border-color: #bbf7d0;
    }
    .qh4 .icon-box { background: rgba(255,255,255,0.25); color: #fff; }
    .qh4 .badge-total { background: rgba(255,255,255,0.25); color: #fff; }

    /* ─── TABLE ──────────────────────────────────── */
    .q-scroll {
      overflow-y: auto;
      max-height: 210px;
    }
    .q-table {
      width: 100%;
      border-collapse: collapse;
    }
    .q-table thead th {
      position: sticky;
      top: 0;
      background: #f8fafc;
      color: var(--muted);
      font-size: 0.72rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      padding: 7px 10px;
      border-bottom: 1px solid var(--border);
      text-align: left;
    }
    .q-table thead th.center { text-align: center; }
    .q-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.1s; }
    .q-table tbody tr:last-child { border-bottom: none; }
    .q-table tbody tr.highlighted {
      background: #fef9c3 !important;
    }
    .q-table tbody td {
      padding: 8px 10px;
      font-size: 0.88rem;
      color: var(--text);
      vertical-align: middle;
    }
    .q-table tbody td.center { text-align: center; }

    /* Siap diambil row styling */
    .q-card.full-width .q-table tbody tr { background: #f0fdf4; }
    .q-card.full-width .q-table tbody tr:nth-child(even) { background: #dcfce7; }
    .q-card.full-width .q-table tbody tr.highlighted { background: #fef9c3 !important; }
    .q-card.full-width .q-table tbody td { font-weight: 600; color: #14532d; }

    /* Nomor urut badge */
    .num-badge {
      display: inline-flex; align-items: center; justify-content: center;
      width: 24px; height: 24px;
      border-radius: 50%;
      font-size: 0.72rem;
      font-weight: 700;
      color: #fff;
      background: var(--muted);
    }
    .qc1 .num-badge { background: var(--c1); }
    .qc2 .num-badge { background: var(--c2); }
    .qc3 .num-badge { background: var(--c3); }
    .qc4 .num-badge { background: var(--c4); }

    /* Time chip */
    .time-pill {
      display: inline-block;
      background: #f1f5f9;
      border-radius: 5px;
      padding: 2px 7px;
      font-size: 0.75rem;
      font-weight: 600;
      color: var(--muted);
      white-space: nowrap;
    }
    .qc4 .time-pill { background: #bbf7d0; color: #14532d; }

    /* "Nama Anda" tag */
    .you-tag {
      display: inline-block;
      background: #fbbf24;
      color: #78350f;
      font-size: 0.62rem;
      font-weight: 700;
      padding: 1px 6px;
      border-radius: 8px;
      margin-left: 5px;
      vertical-align: middle;
    }

    /* ─── EMPTY STATE ────────────────────────────── */
    .empty-state {
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      padding: 22px 10px;
      color: #a0aec0;
      gap: 6px;
    }
    .empty-state i { font-size: 1.6rem; }
    .empty-state p { font-size: 0.82rem; text-align: center; line-height: 1.5; }

    /* ─── REFRESH COUNTDOWN ──────────────────────── */
    #refresh-bar {
      text-align: center;
      padding: 6px 12px;
      font-size: 0.75rem;
      color: var(--muted);
      background: var(--bg);
    }
    #refresh-bar i { margin-right: 4px; }
    #countdown { font-weight: 600; }

    /* ─── FOOTER ─────────────────────────────────── */
    #ftr {
      position: fixed;
      left: 0; bottom: 0;
      width: 100%;
      z-index: 200;
      background: linear-gradient(135deg, var(--hdr) 0%, var(--hdr2) 100%);
      color: #fff;
      box-shadow: 0 -2px 16px rgba(15,76,129,0.20);
    }
    .ftr-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 16px;
      gap: 10px;
      max-width: 900px;
      margin: 0 auto;
    }
    .ftr-brand { font-size: 0.8rem; font-weight: 600; line-height: 1.4; }
    .ftr-brand span { font-size: 0.7rem; font-weight: 400; color: rgba(255,255,255,0.65); display: block; }
    .ftr-tat { text-align: right; }
    .ftr-tat-label { font-size: 0.68rem; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 0.5px; }
    .ftr-tat-val {
      font-size: 1.3rem;
      font-weight: 800;
      color: #86efac;
      letter-spacing: 1px;
    }

    /* ─── RESPONSIVE ─────────────────────────────── */
    @media (max-width: 580px) {
      #main { grid-template-columns: 1fr; gap: 10px; padding: 8px 10px 14px; }
      .q-card.full-width { grid-column: 1; }
      .hdr-title { font-size: 1.05rem; }
      .q-card-header { font-size: 0.82rem; }
    }
    @media (min-width: 700px) {
      .q-scroll { max-height: 260px; }
    }
  </style>
</head>
<body>

  <!-- ═══════ HEADER ═══════ -->
  <div id="hdr">
    <div class="hdr-top">
      <div class="hdr-icon"><i class="fa-solid fa-flask-vial"></i></div>
      <div>
        <div class="hdr-title">Antrian Resep Obat Farmasi</div>
        <div class="hdr-subtitle">RS Setia Mitra</div>
      </div>
    </div>
    <div class="hdr-datetime">
      <span><i class="fa fa-calendar-days"></i><?php date_default_timezone_set("Asia/Jakarta"); echo date('l, d F Y'); ?></span>
      <span><i class="fa fa-clock"></i><span id="time"><?php echo date('H:i'); ?></span> WIB</span>
    </div>
  </div>

  <!-- ═══════ SEARCH BAR ═══════ -->
  <div id="search-wrap">
    <div class="search-inner">
      <i class="fa fa-magnifying-glass"></i>
      <input type="text" id="searchInput" placeholder="Cari nama pasien..." autocomplete="off" />
    </div>
    <div id="search-hint"></div>
  </div>

  <!-- ═══════ PROGRESS PILLS ═══════ -->
  <div id="progress-pills">
    <div class="pill-row">
      <button class="pill pill-1" onclick="scrollToCard('card1')">
        <i class="fa fa-clipboard"></i> Resep Diterima
        <span class="pill-count"><?php echo count($arr_resep_diterima); ?></span>
      </button>
      <span class="pill-sep">›</span>
      <button class="pill pill-2" onclick="scrollToCard('card2')">
        <i class="fa fa-flask"></i> Obat Racikan
        <span class="pill-count"><?php echo count($arr_racikan); ?></span>
      </button>
      <span class="pill-sep">›</span>
      <button class="pill pill-3" onclick="scrollToCard('card3')">
        <i class="fa fa-tag"></i> Etiket
        <span class="pill-count"><?php echo count($arr_etiket); ?></span>
      </button>
      <span class="pill-sep">›</span>
      <button class="pill pill-4" onclick="scrollToCard('card4')">
        <i class="fa fa-circle-check"></i> Siap Diambil
        <span class="pill-count"><?php echo count($arr_siap_diambil); ?></span>
      </button>
    </div>
  </div>

  <!-- ═══════ MAIN CARDS ═══════ -->
  <div id="main">

    <!-- ── RESEP DITERIMA ── -->
    <div class="q-card qc1" id="card1">
      <div class="q-card-header qh1">
        <div class="icon-box"><i class="fa fa-clipboard"></i></div>
        Resep Diterima
        <span class="badge-total"><?php echo count($arr_resep_diterima); ?></span>
      </div>
      <?php if (count($arr_resep_diterima) > 0): ?>
      <div class="q-scroll">
        <table class="q-table">
          <thead>
            <tr>
              <th style="width:36px">#</th>
              <th>Nama Pasien</th>
              <th class="center" style="width:60px"><i class="fa fa-clock"></i></th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 0; foreach ($arr_resep_diterima as $row): $no++; ?>
            <tr>
              <td class="center"><span class="num-badge"><?php echo $no; ?></span></td>
              <td class="nama-pasien"><?php
                $nama = str_replace($text_hide, '', $row->nama_pasien);
                $nama = trim(preg_replace('/\s+/', ' ', $nama));
                $parts = explode(' ', $nama);
                if (count($parts) <= 2) { echo strtoupper(implode(' ', $parts)); }
                else {
                  $out = array_slice($parts, 0, 2);
                  for ($i = 2; $i < count($parts); $i++) $out[] = strtoupper(substr($parts[$i], 0, 1)) . '';
                  echo strtoupper(implode(' ', $out));
                }
              ?></td>
              <td class="center"><span class="time-pill"><?php echo date('H:i', strtotime($row->tgl_trans)); ?></span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
      <div class="empty-state">
        <i class="fa fa-inbox"></i>
        <p>Belum ada resep<br>yang diterima</p>
      </div>
      <?php endif; ?>
    </div>

    <!-- ── OBAT RACIKAN ── -->
    <div class="q-card qc2" id="card2">
      <div class="q-card-header qh2">
        <div class="icon-box"><i class="fa fa-flask"></i></div>
        Obat Racikan
        <span class="badge-total"><?php echo count($arr_racikan); ?></span>
      </div>
      <?php if (count($arr_racikan) > 0): ?>
      <div class="q-scroll">
        <table class="q-table">
          <thead>
            <tr>
              <th style="width:36px">#</th>
              <th>Nama Pasien</th>
              <th class="center" style="width:60px"><i class="fa fa-clock"></i></th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 0; foreach ($arr_racikan as $row): $no++; ?>
            <tr>
              <td class="center"><span class="num-badge"><?php echo $no; ?></span></td>
              <td class="nama-pasien"><?php
                $nama = str_replace($text_hide, '', $row->nama_pasien);
                $nama = trim(preg_replace('/\s+/', ' ', $nama));
                $parts = explode(' ', $nama);
                if (count($parts) <= 2) { echo strtoupper(implode(' ', $parts)); }
                else {
                  $out = array_slice($parts, 0, 2);
                  for ($i = 2; $i < count($parts); $i++) $out[] = strtoupper(substr($parts[$i], 0, 1)) . '';
                  echo strtoupper(implode(' ', $out));
                }
              ?></td>
              <td class="center"><span class="time-pill"><?php echo date('H:i', strtotime($row->tgl_trans)); ?></span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
      <div class="empty-state">
        <i class="fa fa-flask"></i>
        <p>Tidak ada obat<br>dalam proses racikan</p>
      </div>
      <?php endif; ?>
    </div>

    <!-- ── PROSES ETIKET ── -->
    <div class="q-card qc3" id="card3">
      <div class="q-card-header qh3">
        <div class="icon-box"><i class="fa fa-tag"></i></div>
        Proses Etiket
        <span class="badge-total"><?php echo count($arr_etiket); ?></span>
      </div>
      <?php if (count($arr_etiket) > 0): ?>
      <div class="q-scroll">
        <table class="q-table">
          <thead>
            <tr>
              <th style="width:36px">#</th>
              <th>Nama Pasien</th>
              <th class="center" style="width:60px"><i class="fa fa-clock"></i></th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 0; foreach ($arr_etiket as $row): $no++; ?>
            <tr>
              <td class="center"><span class="num-badge"><?php echo $no; ?></span></td>
              <td class="nama-pasien"><?php
                $nama = str_replace($text_hide, '', $row->nama_pasien);
                $nama = trim(preg_replace('/\s+/', ' ', $nama));
                $parts = explode(' ', $nama);
                if (count($parts) <= 2) { echo strtoupper(implode(' ', $parts)); }
                else {
                  $out = array_slice($parts, 0, 2);
                  for ($i = 2; $i < count($parts); $i++) $out[] = strtoupper(substr($parts[$i], 0, 1)) . '';
                  echo strtoupper(implode(' ', $out));
                }
              ?></td>
              <td class="center"><span class="time-pill"><?php echo date('H:i', strtotime($row->tgl_trans)); ?></span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
      <div class="empty-state">
        <i class="fa fa-tag"></i>
        <p>Tidak ada resep<br>dalam proses etiket</p>
      </div>
      <?php endif; ?>
    </div>

    <!-- ── SIAP DIAMBIL (full width, paling menonjol) ── -->
    <div class="q-card qc4 full-width" id="card4">
      <div class="q-card-header qh4">
        <div class="icon-box"><i class="fa fa-circle-check"></i></div>
        <span>✦ Obat Siap Diambil</span>
        <span class="badge-total"><?php echo count($arr_siap_diambil); ?></span>
      </div>
      <?php if (count($arr_siap_diambil) > 0): ?>
      <div class="q-scroll" style="max-height: 300px;">
        <table class="q-table">
          <thead>
            <tr>
              <th style="width:36px">#</th>
              <th>Nama Pasien</th>
              <th class="center" style="width:70px"><i class="fa fa-clock"></i></th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 0; foreach ($arr_siap_diambil as $row): $no++; ?>
            <tr>
              <td class="center"><span class="num-badge"><?php echo $no; ?></span></td>
              <td class="nama-pasien"><?php
                $nama = str_replace($text_hide, '', $row->nama_pasien);
                $nama = trim(preg_replace('/\s+/', ' ', $nama));
                $parts = explode(' ', $nama);
                if (count($parts) <= 2) { echo strtoupper(implode(' ', $parts)); }
                else {
                  $out = array_slice($parts, 0, 2);
                  for ($i = 2; $i < count($parts); $i++) $out[] = strtoupper(substr($parts[$i], 0, 1)) . '';
                  echo strtoupper(implode(' ', $out));
                }
              ?></td>
              <td class="center"><span class="time-pill"><?php echo date('H:i', strtotime($row->tgl_trans)); ?></span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
      <div class="empty-state">
        <i class="fa fa-circle-check" style="color:#16a34a"></i>
        <p>Belum ada obat yang<br>siap diambil saat ini</p>
      </div>
      <?php endif; ?>
    </div>

  </div><!-- /#main -->

  <!-- ═══════ REFRESH COUNTDOWN ═══════ -->
  <div id="refresh-bar">
    <i class="fa fa-rotate"></i> Halaman diperbarui otomatis dalam <span id="countdown">60</span> detik
  </div>

  <!-- ═══════ FOOTER ═══════ -->
  <div id="ftr">
    <div class="ftr-inner">
      <div class="ftr-brand">
        <i class="fa fa-hospital" style="margin-right:6px"></i>RS Setia Mitra
        <span>Smart Hospital System 4.0 &copy; 2018–<?php echo date('Y'); ?></span>
      </div>
      <div class="ftr-tat">
        <div class="ftr-tat-label">Rata-rata Waktu Tunggu</div>
        <div class="ftr-tat-val"><?php echo $avg_display; ?></div>
      </div>
    </div>
  </div>

  <script>
    /* ── Live clock ── */
    setInterval(function () {
      var now = new Date();
      document.getElementById('time').textContent =
        String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
    }, 1000);

    /* ── Scroll ke card ── */
    function scrollToCard(id) {
      var el = document.getElementById(id);
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    /* ── Live search & highlight ── */
    var searchInput = document.getElementById('searchInput');
    var searchHint  = document.getElementById('search-hint');

    searchInput.addEventListener('input', function () {
      var filter = this.value.trim().toLowerCase();
      var totalFound = 0;

      document.querySelectorAll('.q-table tbody tr').forEach(function (row) {
        var cell = row.querySelector('.nama-pasien');
        if (!cell) return;

        // Hapus tag "Anda" lama
        var oldTag = cell.querySelector('.you-tag');
        if (oldTag) oldTag.remove();

        var nama = cell.textContent.trim().toLowerCase();
        if (filter === '') {
          row.style.display = '';
          row.classList.remove('highlighted');
        } else if (nama.indexOf(filter) !== -1) {
          row.style.display = '';
          row.classList.add('highlighted');
          // Tambahkan tag "Anda"
          var tag = document.createElement('span');
          tag.className = 'you-tag';
          tag.textContent = '← Anda';
          cell.appendChild(tag);
          totalFound++;
        } else {
          row.style.display = 'none';
          row.classList.remove('highlighted');
        }
      });

      if (filter !== '') {
        searchHint.textContent = totalFound > 0
          ? '✓ Ditemukan ' + totalFound + ' hasil untuk "' + this.value.trim() + '"'
          : '✗ Nama "' + this.value.trim() + '" tidak ditemukan dalam antrian saat ini';
        searchHint.className = 'visible';
      } else {
        searchHint.className = '';
      }
    });

    /* ── Auto-refresh countdown 60 detik ── */
    var countdownEl = document.getElementById('countdown');
    var secs = 60;
    var timer = setInterval(function () {
      secs--;
      if (countdownEl) countdownEl.textContent = secs;
      if (secs <= 0) { clearInterval(timer); location.reload(); }
    }, 1000);
  </script>

</body>
</html>
