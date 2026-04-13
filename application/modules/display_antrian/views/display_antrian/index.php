<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <title>ANTRIAN PENDAFTARAN — RS Setia Mitra</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
  <link rel="shortcut icon" href="<?php echo base_url().'assets/insani/favicon_rssm.png'; ?>">
  <style>
    @font-face {
      font-family: 'MyriadPro';
      src: url('<?php echo base_url()?>assets/fonts/MyriadPro-Bold.otf');
    }

    :root {
      --bg:      #0d1b2a;
      --bg2:     #0f2339;
      --hdr-bg:  linear-gradient(135deg, #0a2d5a 0%, #00669F 100%);
      --ftr-bg:  linear-gradient(90deg,  #0a2d5a 0%, #00669F 100%);
      --card-bg: rgba(255,255,255,0.05);
      --border:  rgba(255,255,255,0.10);
      --text:    #e8f4f8;

      /* Warna tipe antrian */
      --qa: #2196F3;   /* BPJS    – biru   */
      --qb: #4CAF50;   /* Umum    – hijau  */
      --qc: #FF9800;   /* Lainnya – amber  */
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
      height: 100%;
      font-family: 'MyriadPro', 'Segoe UI', Arial, sans-serif;
      background: var(--bg);
      color: var(--text);
      overflow: hidden;
    }

    /* ─── LAYOUT UTAMA ─────────────────────────── */
    #app {
      display: flex;
      flex-direction: column;
      height: 100vh;
      overflow: hidden;
    }

    /* ─── HEADER ───────────────────────────────── */
    #hdr {
      background: var(--hdr-bg);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2vw;
      height: 74px;
      flex-shrink: 0;
      box-shadow: 0 3px 20px rgba(0,0,0,0.45);
      border-bottom: 2px solid rgba(255,255,255,0.12);
      gap: 1vw;
    }
    .hdr-logos {
      display: flex;
      align-items: center;
      gap: 14px;
      flex-shrink: 0;
    }
    .hdr-logos .logo-main { height: 50px; width: auto; object-fit: contain; }
    .hdr-logos .logo-sep  { width: 1px; height: 40px; background: rgba(255,255,255,0.25); }
    .hdr-logos .logo-by   { height: 34px; width: auto; object-fit: contain; opacity: 0.85; }
    .hdr-center {
      flex: 1;
      text-align: center;
    }
    .hdr-title {
      font-size: 2.0vw;
      font-weight: 900;
      letter-spacing: 3px;
      text-transform: uppercase;
      line-height: 1.15;
    }
    .hdr-sub {
      font-size: 0.95vw;
      color: rgba(255,255,255,0.65);
      letter-spacing: 1px;
      margin-top: 2px;
    }
    .hdr-clock {
      text-align: right;
      flex-shrink: 0;
      line-height: 1.3;
    }
    .hdr-clock .clock-time {
      font-size: 2.2vw;
      font-weight: 700;
    }
    .hdr-clock .clock-date {
      font-size: 0.95vw;
      color: rgba(255,255,255,0.68);
    }

    /* ─── KONTEN UTAMA ─────────────────────────── */
    #content {
      flex: 1 1 auto;
      display: flex;
      gap: 10px;
      padding: 10px;
      overflow: hidden;
      min-height: 0;
    }

    /* ─── PANEL KIRI: CAROUSEL BANNER ─────────── */
    #banner {
      flex: 0 0 62%;
      display: flex;
      flex-direction: column;
      border-radius: 12px;
      overflow: hidden;
      background: #000;
      box-shadow: 0 4px 24px rgba(0,0,0,0.4);
      border: 1px solid var(--border);
    }
    #banner .carousel { flex: 1; height: 100%; }
    #banner .carousel-inner { height: 100%; }
    #banner .carousel-inner .item { height: 100%; }
    #banner .carousel-inner .item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    #banner .carousel-control {
      background-image: none !important;
      background: rgba(0,0,0,0.3);
      width: 6%;
    }
    #banner .carousel-control:hover { background: rgba(0,0,0,0.55); }

    /* ─── PANEL KANAN: LOKET ANTRIAN ───────────── */
    #loket {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
      gap: 8px;
      min-width: 0;
    }

    /* Legend tipe di atas loket */
    #type-legend {
      display: flex;
      gap: 8px;
      justify-content: center;
      flex-shrink: 0;
    }
    .legend-item {
      display: flex;
      align-items: center;
      gap: 5px;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.82vw;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .leg-a { background: rgba(33,150,243,0.18); color: #64b5f6; }
    .leg-b { background: rgba(76,175,80,0.18);  color: #81c784; }
    .leg-c { background: rgba(255,152,0,0.18);  color: #ffb74d; }
    .legend-item .badge-letter {
      width: 1.5vw; height: 1.5vw;
      border-radius: 50%;
      display: inline-flex; align-items: center; justify-content: center;
      font-size: 0.7vw; font-weight: 900; color: #fff;
    }
    .leg-a .badge-letter { background: var(--qa); }
    .leg-b .badge-letter { background: var(--qb); }
    .leg-c .badge-letter { background: var(--qc); }

    /* ─── KARTU LOKET ──────────────────────────── */
    .loket-card {
      flex: 1;
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 12px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    .loket-hdr {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 7px 14px;
      background: rgba(0,102,159,0.25);
      border-bottom: 1px solid rgba(255,255,255,0.08);
      flex-shrink: 0;
    }
    .loket-hdr-icon {
      width: 1.8vw; height: 1.8vw;
      background: rgba(255,255,255,0.12);
      border-radius: 6px;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.85vw; color: rgba(255,255,255,0.75);
    }
    .loket-hdr-label {
      font-size: 1.0vw;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: rgba(255,255,255,0.75);
    }
    .loket-hdr-num {
      margin-left: auto;
      width: 2.2vw; height: 2.2vw;
      background: #00669F;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1vw;
      font-weight: 900;
      color: #fff;
    }

    /* body kartu loket */
    .loket-body {
      flex: 1;
      display: flex;
      align-items: stretch;
      min-height: 0;
    }
    .loket-static {
      display: flex;
      align-items: center;
      justify-content: center;
      flex: 0 0 28%;
      font-size: 6vw;
      font-weight: 900;
      color: rgba(255,255,255,0.12);
      border-right: 1px solid rgba(255,255,255,0.07);
      letter-spacing: -2px;
    }
    .loket-queue {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* ── Tampilan nomor antrian ── */
    .q-display { text-align: center; }
    .q-val {
      font-size: 5.0vw;
      font-weight: 900;
      letter-spacing: 4px;
      line-height: 1;
    }
    .q-lbl {
      font-size: 0.9vw;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-top: 4px;
      opacity: 0.8;
    }
    .qt-a .q-val, .qt-a .q-lbl { color: #64b5f6; }
    .qt-b .q-val, .qt-b .q-lbl { color: #81c784; }
    .qt-c .q-val, .qt-c .q-lbl { color: #ffb74d; }

    /* Kosong */
    .q-empty .q-val { color: rgba(255,255,255,0.15); letter-spacing: 0; }

    /* ─── FOOTER: RUNNING TICKER ───────────────── */
    #ftr {
      flex-shrink: 0;
      background: var(--ftr-bg);
      display: flex;
      align-items: center;
      height: 52px;
      box-shadow: 0 -3px 16px rgba(0,0,0,0.35);
      border-top: 2px solid rgba(255,255,255,0.10);
      overflow: hidden;
      gap: 0;
    }
    .ticker-wrap {
      flex: 1;
      overflow: hidden;
      position: relative;
      height: 100%;
      display: flex;
      align-items: center;
    }
    .ticker-inner {
      display: inline-block;
      white-space: nowrap;
      font-size: 1.35vw;
      font-weight: 700;
      color: #fff;
      letter-spacing: 1px;
      animation: ticker-scroll 45s linear infinite;
    }
    @keyframes ticker-scroll {
      0%   { transform: translateX(100vw); }
      100% { transform: translateX(-100%); }
    }
    .ftr-clock {
      flex-shrink: 0;
      background: rgba(0,0,0,0.22);
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 0 2vw;
      border-left: 1px solid rgba(255,255,255,0.12);
      min-width: 10vw;
    }
    .ftr-clock .ck-time {
      font-size: 1.6vw;
      font-weight: 900;
      letter-spacing: 2px;
    }
    .ftr-clock .ck-date {
      font-size: 0.75vw;
      color: rgba(255,255,255,0.65);
      margin-top: 1px;
    }
  </style>
</head>
<body>

  <div id="app">

    <!-- ═══════════════ HEADER ═══════════════════ -->
    <div id="hdr">
      <div class="hdr-logos">
        <img class="logo-main" src="<?php echo COMP_ICON_INSANI?>" alt="Logo RS">
        <div class="logo-sep"></div>
        <img class="logo-by"   src="<?php echo COMP_ICON_BY_INSANI?>" alt="By Insani">
      </div>
      <div class="hdr-center">
        <div class="hdr-title"><i class="fa fa-users" style="margin-right:10px;opacity:.7"></i>Antrian Pendaftaran</div>
        <div class="hdr-sub">RS Setia Mitra — Smart Hospital System 4.0</div>
      </div>
      <div class="hdr-clock">
        <div class="clock-time" id="clock-time">
          <?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i'); ?> <span style="font-size:0.75em;opacity:.7">WIB</span>
        </div>
        <div class="clock-date" id="clock-date">
          <?php echo date('l, d F Y'); ?>
        </div>
      </div>
    </div>

    <!-- ═══════════════ KONTEN UTAMA ═════════════ -->
    <div id="content">

      <!-- ── KIRI: Banner / Carousel ── -->
      <div id="banner">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
          </ol>
          <div class="carousel-inner">
            <div class="item active">
              <img src="<?php echo base_url().'assets/insani/banner/img_reg_online.png'?>" alt="Registrasi Online">
            </div>
            <div class="item">
              <img src="<?php echo base_url().'assets/insani/banner/paket_mcu_haji.jpeg'?>" alt="Paket MCU Haji">
            </div>
            <div class="item">
              <img src="<?php echo base_url().'assets/insani/banner/img_bpjs_naker.png'?>" alt="BPJS Naker">
            </div>
          </div>
          <a class="left  carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
          </a>
          <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
          </a>
        </div>
      </div>

      <!-- ── KANAN: Panel Loket ── -->
      <div id="loket">

        <!-- Legend tipe antrian -->
        <div id="type-legend">
          <div class="legend-item leg-a">
            <span class="badge-letter">A</span> Pasien BPJS
          </div>
          <div class="legend-item leg-b">
            <span class="badge-letter">B</span> Pasien Umum
          </div>
          <div class="legend-item leg-c">
            <span class="badge-letter">C</span> Pasien Lainnya
          </div>
        </div>

        <!-- LOKET 1 -->
        <div class="loket-card">
          <div class="loket-hdr">
            <div class="loket-hdr-icon"><i class="fa fa-desktop"></i></div>
            <span class="loket-hdr-label">Loket Pendaftaran</span>
            <div class="loket-hdr-num">1</div>
          </div>
          <div class="loket-body">
            <div class="loket-static">1</div>
            <div class="loket-queue" id="auto1">
              <div class="q-display q-empty"><div class="q-val">—</div></div>
            </div>
          </div>
        </div>

        <!-- LOKET 2 -->
        <div class="loket-card">
          <div class="loket-hdr">
            <div class="loket-hdr-icon"><i class="fa fa-desktop"></i></div>
            <span class="loket-hdr-label">Loket Pendaftaran</span>
            <div class="loket-hdr-num">2</div>
          </div>
          <div class="loket-body">
            <div class="loket-static">2</div>
            <div class="loket-queue" id="auto2">
              <div class="q-display q-empty"><div class="q-val">—</div></div>
            </div>
          </div>
        </div>

        <!-- LOKET 3 -->
        <div class="loket-card">
          <div class="loket-hdr">
            <div class="loket-hdr-icon"><i class="fa fa-desktop"></i></div>
            <span class="loket-hdr-label">Loket Pendaftaran</span>
            <div class="loket-hdr-num">3</div>
          </div>
          <div class="loket-body">
            <div class="loket-static">3</div>
            <div class="loket-queue" id="auto3">
              <div class="q-display q-empty"><div class="q-val">—</div></div>
            </div>
          </div>
        </div>

        <!-- LOKET 4 (nonaktif – aktifkan bila diperlukan)
        <div class="loket-card">
          <div class="loket-hdr">
            <div class="loket-hdr-icon"><i class="fa fa-desktop"></i></div>
            <span class="loket-hdr-label">Loket Pendaftaran</span>
            <div class="loket-hdr-num">4</div>
          </div>
          <div class="loket-body">
            <div class="loket-static">4</div>
            <div class="loket-queue" id="auto4">
              <div class="q-display q-empty"><div class="q-val">—</div></div>
            </div>
          </div>
        </div>
        -->

      </div><!-- /#loket -->

    </div><!-- /#content -->

    <!-- ═══════════════ FOOTER TICKER ════════════ -->
    <div id="ftr">
      <div class="ticker-wrap">
        <span class="ticker-inner">
          <?php echo strtoupper('Sayangi kesehatan Anda! Mohon jaga jarak, hindari kerumunan, dan selalu gunakan masker selama berada di lingkungan Rumah Sakit.'); ?>
          &nbsp;&nbsp;&nbsp;❖&nbsp;&nbsp;&nbsp;
          <?php echo strtoupper(COMP_MOTTO); ?>
          &nbsp;&nbsp;&nbsp;❖&nbsp;&nbsp;&nbsp;
          <?php echo strtoupper('Terima kasih telah mempercayakan kesehatan Anda kepada RS Setia Mitra.'); ?>
        </span>
      </div>
      <div class="ftr-clock">
        <div class="ck-time" id="ftr-time"><?php echo date('H:i'); ?> WIB</div>
        <div class="ck-date"><?php echo date('d/m/Y'); ?></div>
      </div>
    </div>

  </div><!-- /#app -->

  <!-- ═══════════════ SCRIPTS ══════════════════ -->
  <script src="<?php echo base_url()?>assets/js/jquery.js"></script>
  <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>

  <script>
    /* ── Live clock ── */
    function updateClock() {
      var now = new Date();
      var h = String(now.getHours()).padStart(2, '0');
      var m = String(now.getMinutes()).padStart(2, '0');
      var hm = h + ':' + m;
      document.getElementById('clock-time').innerHTML = hm + ' <span style="font-size:0.75em;opacity:.7">WIB</span>';
      document.getElementById('ftr-time').textContent  = hm + ' WIB';
    }
    setInterval(updateClock, 1000);

    /* ── Pad angka ── */
    function pad(str, max) {
      str = str.toString();
      return str.length < max ? pad('0' + str, max) : str;
    }

    /* ── Update nomor antrian per loket ── */
    $(document).ready(function () {

      function renderQueue() {
        $.getJSON("<?php echo site_url('display_antrian/process') ?>", '', function (data) {

          /* Kosongkan semua loket terlebih dahulu */
          for (var k = 1; k <= 4; k++) { $('#auto' + k).empty(); }

          var filled = {};

          $.each(data, function (i, o) {
            if (o != 0 && o.ant_no != 0) {
              var no   = pad(o.ant_no, 3);
              var type = (o.ant_type === 'bpjs') ? 'A' : (o.ant_type === 'umum') ? 'B' : 'C';
              var cls  = (type === 'A') ? 'qt-a' : (type === 'B') ? 'qt-b' : 'qt-c';
              var lbl  = (type === 'A') ? 'Pasien BPJS' : (type === 'B') ? 'Pasien Umum' : 'Pasien Lainnya';
              $('<div class="q-display ' + cls + '">' +
                  '<div class="q-val">' + type + '&thinsp;' + no + '</div>' +
                  '<div class="q-lbl">' + lbl + '</div>' +
                '</div>').appendTo($('#auto' + i));
              filled[i] = true;
            }
          });

          /* Loket yang tidak ada data → tampilkan dash */
          [1, 2, 3, 4].forEach(function (k) {
            if (!filled[k] && $('#auto' + k).is(':empty')) {
              $('<div class="q-display q-empty"><div class="q-val">—</div></div>').appendTo($('#auto' + k));
            }
          });
        });
      }

      renderQueue();
      setInterval(renderQueue, 2000);

    });
  </script>

</body>
</html>
