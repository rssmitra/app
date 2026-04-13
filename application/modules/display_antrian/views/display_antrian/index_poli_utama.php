<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta charset="utf-8" />
  <title>ANTRIAN POLIKLINIK — RS Setia Mitra</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
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
      --card-bg: rgba(255,255,255,0.05);
      --border:  rgba(255,255,255,0.10);
      --hdr-bg:  linear-gradient(135deg, #0a2d5a 0%, #00669F 100%);
      --ftr-bg:  linear-gradient(90deg,  #0a2d5a 0%, #00669F 100%);
      --text:    #e8f4f8;
      --green:   #4CAF50;
      --green-l: rgba(76,175,80,0.18);
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
      height: 68px;
      flex-shrink: 0;
      box-shadow: 0 3px 20px rgba(0,0,0,0.45);
      border-bottom: 2px solid rgba(255,255,255,0.12);
      gap: 1vw;
    }
    .hdr-logos {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-shrink: 0;
    }
    .hdr-logos .logo-main { height: 46px; width: auto; object-fit: contain; }
    .hdr-logos .logo-sep  { width: 1px; height: 36px; background: rgba(255,255,255,0.25); }
    .hdr-logos .logo-by   { height: 30px; width: auto; object-fit: contain; opacity: 0.8; }
    .hdr-center {
      flex: 1;
      text-align: center;
    }
    .hdr-title {
      font-size: 2.0vw;
      font-weight: 900;
      letter-spacing: 3px;
      text-transform: uppercase;
    }
    .hdr-sub {
      font-size: 0.9vw;
      color: rgba(255,255,255,0.6);
      letter-spacing: 1px;
      margin-top: 2px;
    }
    .hdr-clock { text-align: right; flex-shrink: 0; line-height: 1.3; }
    .hdr-clock .ck-time { font-size: 2.0vw; font-weight: 700; }
    .hdr-clock .ck-date { font-size: 0.9vw; color: rgba(255,255,255,0.65); }

    /* ─── AREA KONTEN (scrollable) ─────────────── */
    #content {
      flex: 1 1 auto;
      overflow-y: auto;
      overflow-x: hidden;
      min-height: 0;
      padding: 10px;
    }
    #content::-webkit-scrollbar       { width: 5px; }
    #content::-webkit-scrollbar-track { background: rgba(255,255,255,0.03); }
    #content::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 3px; }

    /* ─── GRID POLI ────────────────────────────── */
    #poli-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 10px;
    }

    /* ─── KARTU POLI ───────────────────────────── */
    .poli-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 12px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    .poli-hdr {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      border-bottom: 1px solid rgba(255,255,255,0.07);
      flex-shrink: 0;
    }
    .poli-icon {
      width: 2.8vw; height: 2.8vw;
      min-width: 28px; min-height: 28px;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1vw;
      color: #fff;
      flex-shrink: 0;
    }
    .poli-info { flex: 1; min-width: 0; }
    .poli-name {
      font-size: 1.15vw;
      font-weight: 800;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: #fff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .poli-dokter {
      font-size: 0.95vw;
      color: rgba(255,255,255,0.65);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-top: 1px;
    }

    /* ─── TABEL ANTRIAN POLI ───────────────────── */
    .poli-table { width: 100%; border-collapse: collapse; }
    .poli-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.05); }
    .poli-table tbody tr:last-child { border-bottom: none; }
    .poli-table tbody td {
      padding: 8px 10px;
      font-size: 1.3vw;
      color: var(--text);
      vertical-align: middle;
    }
    /* Baris 0: Sedang dilayani – hijau */
    .poli-table tbody tr.row-serving {
      background: var(--green-l);
      border-left: 3px solid var(--green);
    }
    .poli-table tbody tr.row-serving td { color: #a5d6a7; }
    /* Baris 1: Berikutnya – normal */
    .poli-table tbody tr.row-next {
      background: rgba(255,255,255,0.03);
    }
    .poli-table tbody tr.row-next td { color: rgba(255,255,255,0.55); }

    /* Badge nomor antrian */
    .q-badge {
      display: inline-block;
      background: rgba(255,255,255,0.12);
      border-radius: 5px;
      padding: 2px 8px;
      font-size: 1.1vw;
      font-weight: 800;
      letter-spacing: 1px;
      white-space: nowrap;
    }
    .row-serving .q-badge {
      background: rgba(76,175,80,0.3);
      color: #a5d6a7;
    }
    .serving-icon { float: right; color: var(--green); font-size: 1.0vw; }

    /* Empty placeholder row */
    .poli-table tbody tr.row-empty td { color: rgba(255,255,255,0.2); font-size: 1.0vw; text-align: center; }

    /* ─── FOOTER ───────────────────────────────── */
    #ftr {
      background: var(--ftr-bg);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2vw;
      height: 52px;
      flex-shrink: 0;
      box-shadow: 0 -3px 16px rgba(0,0,0,0.38);
      border-top: 2px solid rgba(255,255,255,0.10);
    }
    .ftr-brand {
      font-size: 1.2vw;
      font-weight: 800;
      letter-spacing: 1px;
      display: flex; align-items: center; gap: 8px;
    }
    .ftr-brand i { color: rgba(255,255,255,0.7); }
    .ftr-legend {
      display: flex;
      align-items: center;
      gap: 20px;
      font-size: 1.0vw;
      font-weight: 600;
    }
    .leg-chip {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 4px 12px;
      border-radius: 20px;
    }
    .leg-serving { background: rgba(76,175,80,0.22); color: #81c784; }
    .leg-next    { background: rgba(255,255,255,0.10); color: rgba(255,255,255,0.65); }
  </style>
</head>
<body>

  <div id="app">

    <!-- ═══════════════ HEADER ═══════════════════ -->
    <div id="hdr">
      <div class="hdr-logos">
        <img class="logo-main" src="<?php echo base_url().COMP_ICON_INSANI?>" alt="Logo RS">
        <div class="logo-sep"></div>
        <img class="logo-by"   src="<?php echo base_url().COMP_ICON_BY_INSANI?>" alt="By Insani">
      </div>
      <div class="hdr-center">
        <div class="hdr-title"><i class="fa fa-stethoscope" style="margin-right:10px;opacity:.7"></i>Antrian Poliklinik</div>
        <div class="hdr-sub">RS Setia Mitra — Smart Hospital System 4.0</div>
      </div>
      <div class="hdr-clock">
        <div class="ck-time" id="clock-time">
          <?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i'); ?> <span style="font-size:.72em;opacity:.7">WIB</span>
        </div>
        <div class="ck-date">
          <?php echo date('l, d F Y'); ?>
        </div>
      </div>
    </div>

    <!-- ═══════════════ GRID POLI ════════════════ -->
    <div id="content">
      <div id="poli-grid">
        <?php
          $accent = ['#2196F3','#4CAF50','#FF9800','#E91E63','#9C27B0','#00BCD4','#FF5722','#3F51B5','#009688','#FFC107'];
          $ci = 0;
          foreach ($data_loket as $key => $row) :
            if (!in_array($row->jd_kode_spesialis, ['013101','012101'])) :
              $col = $accent[$ci % count($accent)];
              $ci++;
        ?>
        <div class="poli-card" style="border-top: 4px solid <?php echo $col; ?>">
          <div class="poli-hdr">
            <div class="poli-icon" style="background:<?php echo $col; ?>">
              <i class="fa fa-stethoscope"></i>
            </div>
            <div class="poli-info">
              <div class="poli-name"><?php echo trim(strtoupper($row->short_name)); ?></div>
              <div class="poli-dokter"><i class="fa fa-user-md" style="opacity:.6;margin-right:4px"></i><?php echo substr($row->nama_pegawai, 0, 38); ?></div>
            </div>
          </div>
          <table class="poli-table" id="table_<?php echo $row->kode_poli_bpjs; ?>_<?php echo $row->jd_kode_dokter; ?>">
            <tbody>
              <tr class="row-serving">
                <td style="width:5.5vw"><span class="q-badge">– –</span></td>
                <td>Tidak ada data</td>
              </tr>
              <tr class="row-next">
                <td><span class="q-badge">– –</span></td>
                <td>–</td>
              </tr>
            </tbody>
          </table>
        </div>
        <?php endif; endforeach; ?>
      </div>
    </div>

    <!-- ═══════════════ FOOTER ═══════════════════ -->
    <div id="ftr">
      <div class="ftr-brand">
        <i class="fa fa-hospital-o"></i>
        RS Setia Mitra | <span style="font-weight:400;font-style:italic">Smart Hospital System 4.0 &copy; 2018-<?php echo date('Y'); ?></span>
      </div>
      <div class="ftr-legend">
        <div class="leg-chip leg-serving">
          <i class="fa fa-check-circle"></i> Sedang Dilayani
        </div>
        <div class="leg-chip leg-next">
          <i class="fa fa-clock-o"></i> Antrian Berikutnya
        </div>
      </div>
    </div>

  </div><!-- /#app -->

  <!-- ═══════════════ SCRIPTS ═══════════════════ -->
  <script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo base_url()?>/assets/js/jquery.js'>"+"<"+"/script>");
  </script>
  <script type="text/javascript" src="<?php echo base_url()?>assets/jSignature/jquery.min.js"></script>
  <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>

  <script>
    /* ── Live clock ── */
    function updateClock() {
      var now = new Date();
      var h = String(now.getHours()).padStart(2, '0');
      var m = String(now.getMinutes()).padStart(2, '0');
      document.getElementById('clock-time').innerHTML =
        h + ':' + m + ' <span style="font-size:.72em;opacity:.7">WIB</span>';
    }
    setInterval(updateClock, 1000);

    /* ── AJAX load antrian poli ── */
    $(document).ready(function () {

      $.getJSON("<?php echo site_url('display_antrian/reload_antrian_poli') ?>", '', function (data) {

        $.each(data.result, function (key, val) {
          $.each(val, function (keys, vals) {

            $('#table_' + key + '_' + keys + ' tbody').remove();
            var length = vals.length;

            $.each(vals, function (k, v) {
              if (k < 2) {
                var prefix     = (v.kode_perusahaan == 120) ? 'B' : 'A';
                var no_str     = v.no_antrian.toString();
                var no_antrian = (no_str.length == 1) ? '0' + v.no_antrian : v.no_antrian;
                var rowClass   = (k == 0) ? 'row-serving' : 'row-next';
                var icon       = (k == 0) ? '<span class="serving-icon"><i class="fa fa-check-circle"></i></span>' : '';
                $('<tr class="' + rowClass + '">' +
                    '<td style="width:5.5vw"><span class="q-badge">' + prefix + '&thinsp;' + no_antrian + '</span></td>' +
                    '<td><span>' + v.nama_pasien.substr(0, 20) + '</span>' + icon + '</td>' +
                  '</tr>').appendTo($('#table_' + v.kode_poli_bpjs + '_' + v.kode_dokter));
              }
              if (length == 1) {
                $('<tr class="row-next">' +
                    '<td style="width:5.5vw"><span class="q-badge">– –</span></td>' +
                    '<td>–</td>' +
                  '</tr>').appendTo($('#table_' + v.kode_poli_bpjs + '_' + v.kode_dokter));
              }
            });

          });
        });

      });

      /* Reload halaman setiap 3 detik (refresh data antrian) */
      setInterval(reload_page, 3000);

    });

    function reload_page() { location.reload(location.href); }
  </script>

</body>
</html>
