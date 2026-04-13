<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta charset="utf-8" />
  <title>ANTRIAN RESEP OBAT - RS Setia Mitra</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
  <link rel="shortcut icon" href="<?php echo base_url().'assets/insani/favicon_rssm.png'; ?>">
  <style>
    @font-face {
      font-family: 'MyriadPro';
      src: url('<?php echo base_url()?>assets/fonts/MyriadPro-Bold.otf');
    }

    :root {
      --bg:          #0d1b2a;
      --bg2:         #0f2339;
      --card-bg:     rgba(255,255,255,0.05);
      --card-border: rgba(255,255,255,0.10);
      --hdr-bg:      linear-gradient(135deg, #0a2d5a 0%, #00669F 100%);
      --ftr-bg:      linear-gradient(90deg,  #0a2d5a 0%, #00669F 100%);
      --text:        #e8f4f8;
      --row-odd:     rgba(255,255,255,0.03);
      --row-even:    rgba(255,255,255,0.07);
      --c1: #2196F3;   /* Diterima  – biru     */
      --c2: #FF9800;   /* Racikan   – amber    */
      --c3: #9C27B0;   /* Etiket    – ungu     */
      --c4: #4CAF50;   /* Siap Ambil – hijau   */
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
      height: 100%;
      font-family: 'MyriadPro', 'Segoe UI', Arial, sans-serif;
      background: var(--bg);
      color: var(--text);
      overflow: hidden;
    }

    /* ─── LAYOUT UTAMA ─────────────────────────────── */
    #app {
      display: flex;
      flex-direction: column;
      height: 100vh;
      overflow: hidden;
    }

    /* ─── HEADER ───────────────────────────────────── */
    #hdr {
      background: var(--hdr-bg);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2vw;
      height: 68px;
      flex-shrink: 0;
      box-shadow: 0 3px 20px rgba(0,0,0,0.45);
      border-bottom: 2px solid rgba(255,255,255,0.12);
    }
    .hdr-title {
      font-size: 2.2vw;
      font-weight: 900;
      letter-spacing: 3px;
      text-transform: uppercase;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .hdr-title i { font-size: 1.9vw; color: rgba(255,255,255,0.7); }
    .hdr-clock { text-align: right; line-height: 1.35; }
    .hdr-date  { font-size: 1.05vw; color: rgba(255,255,255,0.7); }
    .hdr-time  { font-size: 1.9vw;  font-weight: 700; }

    /* ─── PROGRESS FLOW ────────────────────────────── */
    #progress-bar {
      background: rgba(0,0,0,0.28);
      display: flex;
      align-items: stretch;
      height: 40px;
      flex-shrink: 0;
      border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    .progress-step {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      font-size: 0.9vw;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      position: relative;
    }
    .progress-step::after {
      content: '❯';
      position: absolute;
      right: -6px;
      font-size: 1.1vw;
      color: rgba(255,255,255,0.22);
      z-index: 1;
    }
    .progress-step:last-child::after { display: none; }
    .ps1 { border-top: 3px solid var(--c1); color: #64b5f6; }
    .ps2 { border-top: 3px solid var(--c2); color: #ffb74d; }
    .ps3 { border-top: 3px solid var(--c3); color: #ce93d8; }
    .ps4 { border-top: 3px solid var(--c4); color: #81c784; }

    /* ─── GRID KONTEN ──────────────────────────────── */
    #content {
      flex: 1 1 auto;
      display: flex;
      gap: 10px;
      padding: 10px;
      overflow: hidden;
      min-height: 0;
    }

    /* ─── KOLOM ANTRIAN ────────────────────────────── */
    .q-col {
      flex: 1;
      display: flex;
      flex-direction: column;
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: 12px;
      overflow: hidden;
      min-width: 0;
    }
    .q-col-header {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 11px 10px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      flex-shrink: 0;
    }
    .q-col-header i    { font-size: 1.5vw; }
    .q-col-header span { font-size: 1.5vw; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; }

    /* Aksen warna per kolom */
    .qc1 { border-top: 4px solid var(--c1); }
    .qc1 .q-col-header { background: rgba(33,150,243,0.14);  color: #64b5f6; }
    .qc2 { border-top: 4px solid var(--c2); }
    .qc2 .q-col-header { background: rgba(255,152,0,0.14);   color: #ffb74d; }
    .qc3 { border-top: 4px solid var(--c3); }
    .qc3 .q-col-header { background: rgba(156,39,176,0.14);  color: #ce93d8; }
    .qc4 { border-top: 4px solid var(--c4); }
    .qc4 .q-col-header { background: rgba(76,175,80,0.14);   color: #81c784; }

    /* ─── AREA SCROLL TABEL ────────────────────────── */
    .q-scroll {
      flex: 1 1 auto;
      overflow-y: auto;
      overflow-x: hidden;
      min-height: 0;
    }
    .q-scroll::-webkit-scrollbar       { width: 4px; }
    .q-scroll::-webkit-scrollbar-track { background: rgba(255,255,255,0.03); }
    .q-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 2px; }

    /* ─── TABEL ANTRIAN ────────────────────────────── */
    .q-table { width: 100%; border-collapse: collapse; }
    .q-table thead th {
      position: sticky;
      top: 0;
      z-index: 2;
      padding: 7px 10px;
      font-size: 1.05vw;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: rgba(255,255,255,0.55);
      background: var(--bg2);
      border-bottom: 1px solid rgba(255,255,255,0.08);
      text-align: left;
    }
    .q-table thead th.center { text-align: center; }
    .q-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.05); }
    .q-table tbody tr:nth-child(odd)  { background: var(--row-odd); }
    .q-table tbody tr:nth-child(even) { background: var(--row-even); }
    .q-table tbody td {
      padding: 9px 10px;
      font-size: 1.4vw;
      color: var(--text);
      vertical-align: middle;
    }
    .q-table tbody td.center { text-align: center; }

    /* Badge nomor urut */
    .row-no {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 2.1vw;
      height: 2.1vw;
      border-radius: 50%;
      font-size: 0.88vw;
      font-weight: 700;
      color: #fff;
    }
    .qc1 .row-no { background: rgba(33,150,243,0.35); }
    .qc2 .row-no { background: rgba(255,152,0,0.38); }
    .qc3 .row-no { background: rgba(156,39,176,0.38); }
    .qc4 .row-no { background: rgba(76,175,80,0.4); animation: pulse-green 2s ease-in-out infinite; }

    @keyframes pulse-green {
      0%,100% { box-shadow: 0 0 0 0   rgba(76,175,80,0.45); }
      50%      { box-shadow: 0 0 0 7px rgba(76,175,80,0);    }
    }

    /* Chip waktu */
    .time-chip {
      display: inline-block;
      background: rgba(255,255,255,0.10);
      border-radius: 4px;
      padding: 2px 8px;
      font-size: 1.05vw;
      font-weight: 600;
      color: rgba(255,255,255,0.7);
      white-space: nowrap;
    }

    /* ─── TOTAL PER KOLOM ──────────────────────────── */
    .q-total {
      flex-shrink: 0;
      text-align: center;
      padding: 10px 8px 12px;
      border-top: 1px solid rgba(255,255,255,0.08);
    }
    .q-total .total-label {
      font-size: 0.8vw;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: rgba(255,255,255,0.45);
      font-weight: 600;
    }
    .q-total .total-num {
      font-size: 3.4vw;
      font-weight: 900;
      line-height: 1.05;
      margin-top: 2px;
    }
    .qc1 .total-num { color: #64b5f6; }
    .qc2 .total-num { color: #ffb74d; }
    .qc3 .total-num { color: #ce93d8; }
    .qc4 .total-num { color: #81c784; }

    /* ─── FOOTER ───────────────────────────────────── */
    #ftr {
      background: var(--ftr-bg);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2vw;
      height: 68px;
      flex-shrink: 0;
      box-shadow: 0 -3px 20px rgba(0,0,0,0.38);
      border-top: 2px solid rgba(255,255,255,0.10);
    }
    .ftr-brand {
      font-size: 1.45vw;
      font-weight: 800;
      letter-spacing: 1px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .ftr-brand i { font-size: 1.7vw; color: rgba(255,255,255,0.7); }
    .ftr-sub     { font-size: 0.88vw; color: rgba(255,255,255,0.55); font-style: italic; margin-top: 2px; }
    .ftr-tat     { display: flex; align-items: center; gap: 14px; }
    .tat-label {
      text-align: right;
      font-size: 0.82vw;
      color: white !important;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-style: italic;
      line-height: 1.4;
    }
    .tat-value {
      background: rgb(255, 255, 255);
      border: 2px solid rgba(255,255,255,0.28);
      border-radius: 8px;
      padding: 5px 16px;
      font-size: 2.0vw;
      font-weight: 900;
      color: #fff !important;
      text-align: center;
      min-width: 8vw;
      letter-spacing: 2px;
    }

    /* ─── STAMP UJI COBA (jika aktif) ─────────────── */
    .uji-coba-stamp {
      position: fixed; top: 24px; right: 24px; z-index: 20000;
      background: rgba(255,0,0,0.85); color: #fff;
      font-size: 2.2em; font-weight: bold;
      padding: 12px 32px; border-radius: 12px;
      box-shadow: 0 2px 12px rgba(255,0,0,0.18);
      letter-spacing: 2px; transform: rotate(8deg);
      opacity: .92; pointer-events: none; user-select: none;
    }
  </style>
</head>
<body>
  <!-- <div class="uji-coba-stamp">Sedang Uji Coba</div> -->

  <div id="app">

    <!-- ═══════════════════ HEADER ═══════════════════ -->
    <div id="hdr">
      <div class="hdr-title">
        <i class="fa fa-flask"></i>
        ANTRIAN RESEP OBAT
      </div>
      <div class="hdr-clock">
        <div class="hdr-date">
          <i class="fa fa-calendar"></i>
          <?php date_default_timezone_set("Asia/Jakarta"); echo date('l, d F Y'); ?>
        </div>
        <div class="hdr-time">
          <i class="fa fa-clock-o"></i>
          <span id="time"><?php echo date('H:i'); ?></span> WIB
        </div>
      </div>
    </div>

    <!-- ═══════════════════ PROGRESS FLOW ════════════ -->
    <div id="progress-bar">
      <div class="progress-step ps1"><i class="fa fa-clipboard"></i> Resep Diterima</div>
      <div class="progress-step ps2"><i class="fa fa-flask"></i> Obat Racikan</div>
      <div class="progress-step ps3"><i class="fa fa-tag"></i> Proses Etiket</div>
      <div class="progress-step ps4"><i class="fa fa-check-circle"></i> Siap Diambil</div>
    </div>

    <!-- ═══════════════════ KONTEN UTAMA ══════════════ -->
    <div id="content">

      <!-- ── KOLOM 1: RESEP DITERIMA ── -->
      <div class="q-col qc1">
        <div class="q-col-header">
          <i class="fa fa-clipboard"></i>
          <span>Resep Diterima</span>
        </div>
        <div class="q-scroll" id="scroll-diterima">
          <table class="q-table">
            <thead>
              <tr>
                <th style="width:3.5vw">#</th>
                <th>Nama Pasien</th>
                <th class="center" style="width:6vw"><i class="fa fa-clock-o"></i></th>
              </tr>
            </thead>
            <tbody id="resep-diterima-tbody">
              <?php
                $no = 0;
                $arr_resep_diterima = [];
                foreach ($resep_diterima as $row) :
                  $no++;
                  $arr_resep_diterima[] = $row;
              ?>
              <tr>
                <td><span class="row-no"><?php echo $no; ?></span></td>
                <td>
                  <?php
                    $nama = str_replace($text_hide, '', $row->nama_pasien);
                    $nama = trim(preg_replace('/\s+/', ' ', $nama));
                    $parts = explode(' ', $nama);
                    if (count($parts) <= 2) {
                      echo strtoupper(implode(' ', $parts));
                    } else {
                      $output = array_slice($parts, 0, 2);
                      for ($i = 2; $i < count($parts); $i++) {
                        $output[] = strtoupper(substr($parts[$i], 0, 1)) . '';
                      }
                      echo strtoupper(implode(' ', $output));
                    }
                  ?>
                </td>
                <td class="center">
                  <span class="time-chip"><?php echo date('H:i', strtotime($row->tgl_trans)); ?></span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="q-total">
          <div class="total-label">Total Resep Diterima</div>
          <div class="total-num"><?php echo count($arr_resep_diterima); ?></div>
        </div>
      </div>

      <!-- ── KOLOM 2: OBAT RACIKAN ── -->
      <div class="q-col qc2">
        <div class="q-col-header">
          <i class="fa fa-flask"></i>
          <span>Obat Racikan</span>
        </div>
        <div class="q-scroll" id="scroll-racikan">
          <table class="q-table">
            <thead>
              <tr>
                <th style="width:3.5vw">#</th>
                <th>Nama Pasien</th>
                <th class="center" style="width:6vw"><i class="fa fa-clock-o"></i></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $no = 0;
                $arr_racikan = [];
                foreach ($resep as $row) :
                  if ($row->log_time_3 != null && $row->log_time_4 == null) :
                    $no++;
                    $arr_racikan[] = $row;
              ?>
              <tr>
                <td><span class="row-no"><?php echo $no; ?></span></td>
                <td>
                  <?php
                    $nama = str_replace($text_hide, '', $row->nama_pasien);
                    $nama = trim(preg_replace('/\s+/', ' ', $nama));
                    $parts = explode(' ', $nama);
                    if (count($parts) <= 2) {
                      echo strtoupper(implode(' ', $parts));
                    } else {
                      $output = array_slice($parts, 0, 2);
                      for ($i = 2; $i < count($parts); $i++) {
                        $output[] = strtoupper(substr($parts[$i], 0, 1)) . '';
                      }
                      echo strtoupper(implode(' ', $output));
                    }
                  ?>
                </td>
                <td class="center">
                  <span class="time-chip"><?php echo date('H:i', strtotime($row->tgl_trans)); ?></span>
                </td>
              </tr>
              <?php endif; endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="q-total">
          <div class="total-label">Total Resep Racikan</div>
          <div class="total-num"><?php echo count($arr_racikan); ?></div>
        </div>
      </div>

      <!-- ── KOLOM 3: ETIKET ── -->
      <div class="q-col qc3">
        <div class="q-col-header">
          <i class="fa fa-tag"></i>
          <span>Proses Etiket</span>
        </div>
        <div class="q-scroll" id="scroll-etiket">
          <table class="q-table">
            <thead>
              <tr>
                <th style="width:3.5vw">#</th>
                <th>Nama Pasien</th>
                <th class="center" style="width:6vw"><i class="fa fa-clock-o"></i></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $no = 0;
                $arr_etiket = [];
                foreach ($resep as $row) :
                  if ($row->log_time_4 != null && $row->log_time_5 == null) :
                    $no++;
                    $arr_etiket[] = $row;
              ?>
              <tr>
                <td><span class="row-no"><?php echo $no; ?></span></td>
                <td>
                  <?php
                    $nama = str_replace($text_hide, '', $row->nama_pasien);
                    $nama = trim(preg_replace('/\s+/', ' ', $nama));
                    $parts = explode(' ', $nama);
                    if (count($parts) <= 2) {
                      echo strtoupper(implode(' ', $parts));
                    } else {
                      $output = array_slice($parts, 0, 2);
                      for ($i = 2; $i < count($parts); $i++) {
                        $output[] = strtoupper(substr($parts[$i], 0, 1)) . '';
                      }
                      echo strtoupper(implode(' ', $output));
                    }
                  ?>
                </td>
                <td class="center">
                  <span class="time-chip"><?php echo date('H:i', strtotime($row->tgl_trans)); ?></span>
                </td>
              </tr>
              <?php endif; endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="q-total">
          <div class="total-label">Total Proses Etiket</div>
          <div class="total-num"><?php echo count($arr_etiket); ?></div>
        </div>
      </div>

      <!-- ── KOLOM 4: SIAP DIAMBIL ── -->
      <div class="q-col qc4">
        <div class="q-col-header">
          <i class="fa fa-check-circle"></i>
          <span>Siap Diambil</span>
        </div>
        <div class="q-scroll" id="scroll-siapdiambil">
          <table class="q-table">
            <thead>
              <tr>
                <th style="width:3.5vw">#</th>
                <th>Nama Pasien</th>
                <th class="center" style="width:6vw"><i class="fa fa-clock-o"></i></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $no = 0;
                $arr_siap_diambil = [];
                foreach ($resep as $row) :
                  if ($row->log_time_5 != null && $row->log_time_6 == null) :
                    if ($no <= 30) :
                      $no++;
                      $arr_siap_diambil[] = $row;
              ?>
              <tr>
                <td><span class="row-no"><?php echo $no; ?></span></td>
                <td>
                  <?php
                    $nama = str_replace($text_hide, '', $row->nama_pasien);
                    $nama = trim(preg_replace('/\s+/', ' ', $nama));
                    $parts = explode(' ', $nama);
                    if (count($parts) <= 2) {
                      echo strtoupper(implode(' ', $parts));
                    } else {
                      $output = array_slice($parts, 0, 2);
                      for ($i = 2; $i < count($parts); $i++) {
                        $output[] = strtoupper(substr($parts[$i], 0, 1)) . '';
                      }
                      echo strtoupper(implode(' ', $output));
                    }
                  ?>
                </td>
                <td class="center">
                  <span class="time-chip"><?php echo date('H:i', strtotime($row->tgl_trans)); ?></span>
                </td>
              </tr>
              <?php endif; endif; endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="q-total">
          <div class="total-label">Total Siap Diambil</div>
          <div class="total-num"><?php echo count($arr_siap_diambil); ?></div>
        </div>
      </div>

    </div><!-- /#content -->

    <!-- ═══════════════════ FOOTER ════════════════════ -->
    <div id="ftr">
      <div>
        <div class="ftr-brand">
          <i class="fa fa-hospital-o"></i>
          RS Setia Mitra
        </div>
        <div class="ftr-sub">Smart Hospital System 4.0 &copy; 2018-<?php echo date('Y'); ?></div>
      </div>
      <div class="ftr-tat">
        <div class="tat-label">Rata-rata<br>Waktu Tunggu Obat</div>
        <div class="tat-value" id="avg-waktu-tunggu"><?php echo isset($avg_tat) ? $avg_tat : '00:00'; ?></div>
      </div>
    </div>

  </div><!-- /#app -->

  <!-- ═══════════════════ SCRIPTS ═══════════════════ -->
  <script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo base_url()?>/assets/js/jquery.js'>"+"<"+"/script>");
  </script>
  <script type="text/javascript" src="<?php echo base_url()?>assets/jSignature/jquery.min.js"></script>
  <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>

  <script>
    /* ── Live clock ── */
    function updateClock() {
      var now = new Date();
      document.getElementById('time').textContent =
        String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
    }
    setInterval(updateClock, 1000);

    $(document).ready(function () {

      /* ── Auto-scroll vertikal ── */
      function autoScrollTable(id, onFullCycle) {
        var el = document.getElementById(id);
        if (!el) return false;
        var direction = 1, scrollStep = 1, scrollDelay = 30;
        var scrollInterval, hasCycled = false;
        var hasScrollable = (el.scrollHeight > el.clientHeight + 1);

        function scrollFn() {
          if (!hasScrollable) return;
          if (direction === 1) {
            if (el.scrollTop + el.clientHeight < el.scrollHeight - 1) {
              el.scrollTop += scrollStep;
            } else {
              direction = -1;
              setTimeout(scrollFn, 1000);
              return;
            }
          } else {
            if (el.scrollTop > 0) {
              el.scrollTop -= scrollStep;
            } else {
              direction = 1;
              if (!hasCycled) {
                hasCycled = true;
                if (typeof onFullCycle === 'function') onFullCycle();
              }
              setTimeout(scrollFn, 1000);
              return;
            }
          }
          scrollInterval = setTimeout(scrollFn, scrollDelay);
        }
        scrollFn();
        return hasScrollable ? function () { clearTimeout(scrollInterval); } : false;
      }

      var stopScrollers = [], hasReloaded = false, reloadTimeout = null;

      function onAnyTableFullCycle() {
        if (!hasReloaded) { hasReloaded = true; reload_page(); }
      }

      function startAllScrollers() {
        stopAllScrollers();
        hasReloaded = false;
        if (reloadTimeout) { clearTimeout(reloadTimeout); reloadTimeout = null; }
        stopScrollers = [
          autoScrollTable('scroll-diterima',    onAnyTableFullCycle),
          autoScrollTable('scroll-racikan',     onAnyTableFullCycle),
          autoScrollTable('scroll-etiket',      onAnyTableFullCycle),
          autoScrollTable('scroll-siapdiambil', onAnyTableFullCycle)
        ];
        if (stopScrollers.every(function (s) { return s === false; })) {
          reloadTimeout = setTimeout(reload_page, 30000);
        }
      }

      function stopAllScrollers() {
        stopScrollers.forEach(function (stop) { if (typeof stop === 'function') stop(); });
        stopScrollers = [];
      }

      startAllScrollers();

      /* ── AJAX reload antrian farmasi ── */
      $.getJSON("<?php echo site_url('display_antrian/reload_antrian_farmasi') ?>", '', function (data) {
        $.each(data.result, function (key, val) {
          $.each(val, function (keys, vals) {
            $('#table_' + key + '_' + keys + ' tbody').remove();
            var length = vals.length;
            $.each(vals, function (k, v) {
              if (k < 2) {
                var prefix = (v.kode_perusahaan == 120) ? 'B' : 'A';
                var no_str = v.no_antrian.toString();
                var no_antrian = (no_str.length == 1) ? '0' + v.no_antrian : v.no_antrian;
                var icon = (k == 0) ? '<span style="float:right"><i class="fa fa-circle green"></i></span>' : '';
                $('<tr style="background:#00669F"><td align="center"><span style="border-right:1px solid white">' + prefix + ' ' + no_antrian + '&nbsp;</span></td><td><span>' + v.nama_pasien.substr(0, 15) + '</span>' + icon + '</td></tr>')
                  .appendTo($('#table_' + v.kode_farmasi_bpjs + '_' + v.kode_dokter));
              }
              if (length == 1) {
                $('<tr style="background:#00669F"><td align="center"><span style="border-right:1px solid white">X 00&nbsp;</span></td><td>-</td></tr>')
                  .appendTo($('#table_' + v.kode_farmasi_bpjs + '_' + v.kode_dokter));
              }
            });
          });
        });
        setTimeout(startAllScrollers, 300);
      });

    });

    function reload_page() { location.reload(location.href); }
  </script>

</body>
</html>
