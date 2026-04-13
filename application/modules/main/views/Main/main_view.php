<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta charset="utf-8" />
  <title><?php echo strip_tags($app->app_name)?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery-ui.custom.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery.gritter.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/select2.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
  <link rel="shortcut icon" href="<?php echo base_url().COMP_ICON; ?>">

  <style>
    :root {
      --primary:      #00669F;
      --primary-dk:   #0a2d5a;
      --bg:           #eef2f7;
      --surface:      #ffffff;
      --border:       #dde3ec;
      --text-1:       #1a202c;
      --text-2:       #4a5568;
      --text-3:       #94a3b8;
      --radius-card:  16px;
      --shadow-sm:    0 1px 4px rgba(0,0,0,0.07);
      --shadow-md:    0 6px 24px rgba(0,0,0,0.11);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background: var(--bg);
      font-family: 'Segoe UI', system-ui, Arial, sans-serif;
      color: var(--text-1);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ─── TOPBAR ────────────────────────────────────────── */
    #topbar {
      background: linear-gradient(135deg, var(--primary-dk) 0%, var(--primary) 100%);
      height: 62px;
      padding: 0 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 999;
      box-shadow: 0 3px 16px rgba(0,0,0,0.3);
      flex-shrink: 0;
    }
    .tb-brand {
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .tb-brand img { height: 36px; object-fit: contain; }
    .tb-sep { width: 1px; height: 28px; background: rgba(255,255,255,0.2); }
    .tb-appname { color: rgba(255,255,255,0.88); font-size: 14px; font-weight: 600; }
    .tb-right {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .tb-chip {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 5px 14px;
      border-radius: 20px;
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.15);
      color: rgba(255,255,255,0.88);
      font-size: 13px;
      white-space: nowrap;
    }
    .tb-chip i { font-size: 12px; opacity: 0.75; }
    .tb-logout {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 5px 15px;
      border-radius: 20px;
      background: rgba(239,68,68,0.2);
      border: 1px solid rgba(239,68,68,0.35);
      color: #fca5a5;
      font-size: 13px;
      text-decoration: none;
      transition: background .18s, color .18s;
    }
    .tb-logout:hover { background: rgba(239,68,68,0.38); color: #fff; text-decoration: none; }

    /* ─── HERO / WELCOME ────────────────────────────────── */
    #hero {
      background: linear-gradient(135deg, var(--primary-dk) 0%, #005080 55%, #007ab8 100%);
      padding: 24px 28px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-shrink: 0;
      position: relative;
      overflow: hidden;
    }
    #hero::after {
      content: '';
      position: absolute;
      right: -60px; top: -60px;
      width: 260px; height: 260px;
      border-radius: 50%;
      background: rgba(255,255,255,0.04);
      pointer-events: none;
    }
    #hero::before {
      content: '';
      position: absolute;
      right: 60px; bottom: -80px;
      width: 180px; height: 180px;
      border-radius: 50%;
      background: rgba(255,255,255,0.03);
      pointer-events: none;
    }
    .hero-left { display: flex; align-items: center; gap: 16px; z-index: 1; }
    .hero-avatar {
      width: 54px; height: 54px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid rgba(255,255,255,0.3);
      flex-shrink: 0;
    }
    .hero-greet { font-size: 13px; color: rgba(255,255,255,0.65); margin-bottom: 2px; }
    .hero-name  { font-size: 20px; font-weight: 700; color: #fff; }
    .hero-right { text-align: right; z-index: 1; }
    .hero-time  { font-size: 32px; font-weight: 700; color: #fff; line-height: 1; }
    .hero-time span { font-size: 14px; font-weight: 400; opacity: 0.65; margin-left: 4px; }
    .hero-date  { font-size: 13px; color: rgba(255,255,255,0.65); margin-top: 3px; }

    /* ─── SEARCH BAR ────────────────────────────────────── */
    #search-wrap {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 12px 28px;
      flex-shrink: 0;
    }
    #mod-search {
      width: 100%;
      max-width: 100%;
      padding: 9px 14px 9px 40px;
      border: 1.5px solid var(--border);
      border-radius: 24px;
      font-size: 14px;
      color: var(--text-1);
      background: var(--bg) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") 12px center / 16px no-repeat;
      outline: none;
      transition: border-color .18s, box-shadow .18s;
    }
    #mod-search:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(0,102,159,0.12);
    }
    #mod-search::placeholder { color: var(--text-3); }

    /* ─── PAGE BODY ─────────────────────────────────────── */
    #page-body {
      flex: 1;
      padding: 24px 28px 48px;
      max-width: 1440px;
      width: 100%;
      margin: 0 auto;
    }

    /* ─── GROUP SECTION ─────────────────────────────────── */
    .group-section { margin-bottom: 32px; }
    .group-section.hidden { display: none; }
    .group-hdr {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 16px;
    }
    .group-bar { width: 4px; height: 22px; border-radius: 3px; flex-shrink: 0; }
    .group-label {
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: var(--text-2);
    }
    .group-count {
      margin-left: 4px;
      background: var(--border);
      color: var(--text-3);
      font-size: 11px;
      font-weight: 600;
      padding: 1px 8px;
      border-radius: 10px;
    }

    /* ─── MODULE GRID ───────────────────────────────────── */
    .mod-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
      gap: 14px;
    }

    /* ─── MODULE CARD ───────────────────────────────────── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(14px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .mod-card {
      background: var(--surface);
      border-radius: var(--radius-card);
      border: 1.5px solid var(--border);
      box-shadow: var(--shadow-sm);
      padding: 22px 12px 16px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: var(--text-2);
      cursor: pointer;
      position: relative;
      overflow: hidden;
      animation: fadeUp .35s ease both;
      transition: transform .22s, box-shadow .22s, border-color .22s, color .22s;
    }
    .mod-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.13);
      border-color: var(--card-accent, var(--primary));
      color: var(--card-accent, var(--primary));
      text-decoration: none;
    }
    .mod-card:hover .mod-icon-wrap {
      transform: scale(1.1) rotate(-4deg);
      box-shadow: 0 6px 18px var(--card-glow, rgba(0,102,159,0.35));
    }
    /* Accent bar at bottom on hover */
    .mod-card::after {
      content: '';
      position: absolute;
      bottom: 0; left: 0; right: 0;
      height: 3px;
      background: var(--card-accent, var(--primary));
      transform: scaleX(0);
      transition: transform .22s;
      transform-origin: center;
    }
    .mod-card:hover::after { transform: scaleX(1); }

    .mod-icon-wrap {
      width: 58px; height: 58px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: #fff;
      flex-shrink: 0;
      transition: transform .22s, box-shadow .22s;
      background: linear-gradient(145deg, var(--card-accent, var(--primary)), var(--card-accent-dk, #0a2d5a));
    }
    .mod-name {
      font-size: 11px;
      font-weight: 700;
      text-align: center;
      text-transform: uppercase;
      letter-spacing: 0.7px;
      line-height: 1.4;
      word-break: break-word;
      color: inherit;
    }
    .mod-ext {
      position: absolute;
      top: 8px; right: 9px;
      font-size: 10px;
      color: var(--text-3);
    }
    /* No-result notice */
    #no-result {
      display: none;
      text-align: center;
      padding: 48px 24px;
      color: var(--text-3);
      font-size: 15px;
    }
    #no-result i { font-size: 36px; display: block; margin-bottom: 12px; }

    /* ─── FOOTER ─────────────────────────────────────────── */
    #page-footer {
      background: var(--surface);
      border-top: 1px solid var(--border);
      text-align: center;
      padding: 14px;
      font-size: 12px;
      color: var(--text-3);
      flex-shrink: 0;
    }

    /* ─── RESPONSIVE ─────────────────────────────────────── */
    @media (max-width: 992px) {
      #page-body { padding: 18px 18px 36px; }
      .mod-grid  { grid-template-columns: repeat(auto-fill, minmax(114px, 1fr)); }
    }
    @media (max-width: 768px) {
      #topbar  { padding: 0 14px; height: 54px; }
      .tb-chip.hide-sm, .tb-sep, .tb-appname { display: none; }
      #hero    { padding: 18px 16px 22px; }
      .hero-time { font-size: 26px; }
      #search-wrap { padding: 10px 14px; }
      #page-body   { padding: 14px 12px 32px; }
      .mod-grid    { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; }
      .mod-icon-wrap { width: 50px; height: 50px; font-size: 20px; }
    }
    @media (max-width: 480px) {
      .mod-grid { grid-template-columns: repeat(3, 1fr); }
    }
  </style>
</head>
<body>

  <!-- ══════════════ TOPBAR ═════════════════════════════ -->
  <div id="topbar">
    <div class="tb-brand">
      <img src="<?php echo base_url().HEADER_LOGO?>" alt="Logo RS">
      <div class="tb-sep"></div>
      <span class="tb-appname"><?php echo strip_tags($app->app_name)?></span>
    </div>
    <div class="tb-right">
      <div class="tb-chip hide-sm">
        <i class="fa fa-calendar"></i>
        <?php date_default_timezone_set('Asia/Jakarta'); echo date('l, d F Y'); ?>
      </div>
      <div class="tb-chip">
        <i class="fa fa-user-circle-o"></i>
        <?php echo strip_tags($this->session->userdata('user')->fullname); ?>
      </div>
      <a href="<?php echo base_url().'login/logout'?>" class="tb-logout">
        <i class="fa fa-power-off"></i>
        <span>Keluar</span>
      </a>
    </div>
  </div>

  <!-- ══════════════ HERO ════════════════════════════════ -->
  <div id="hero">
    <?php
      $foto_src = base_url().'assets/avatars/user.jpg';
      $path_check = PATH_PHOTO_PROFILE_DEFAULT.$this->session->userdata('user')->path_foto;
      if (!empty($this->session->userdata('user')->path_foto) && file_exists($path_check)) {
        $foto_src = base_url().PATH_PHOTO_PROFILE_DEFAULT.$this->session->userdata('user')->path_foto;
      }
    ?>
    <div class="hero-left">
      <img class="hero-avatar" src="<?php echo $foto_src?>" alt="Foto">
      <div>
        <div class="hero-greet">Selamat datang kembali,</div>
        <div class="hero-name"><?php echo strip_tags($this->session->userdata('user')->fullname); ?></div>
      </div>
    </div>
    <div class="hero-right">
      <div class="hero-time" id="live-clock">
        <?php echo date('H:i'); ?><span>WIB</span>
      </div>
      <div class="hero-date"><?php echo date('l, d F Y'); ?></div>
    </div>
  </div>

  <!-- ══════════════ SEARCH ══════════════════════════════ -->
  <div id="search-wrap">
    <input type="text" id="mod-search" placeholder="Cari modul..." autocomplete="off">
  </div>

  <!-- ══════════════ PAGE BODY ══════════════════════════= -->
  <div id="page-body">

    <?php
      /* Gradient pairs: [light, dark] for linear-gradient */
      $palettes = [
        ['#2196F3','#0D47A1'], ['#43A047','#1B5E20'], ['#FB8C00','#BF360C'],
        ['#E91E63','#880E4F'], ['#8E24AA','#4A148C'], ['#00ACC1','#006064'],
        ['#F4511E','#BF360C'], ['#3949AB','#1A237E'], ['#00897B','#004D40'],
        ['#FDD835','#F57F17'], ['#039BE5','#01579B'], ['#6D4C41','#3E2723'],
        ['#546E7A','#263238'], ['#E53935','#B71C1C'], ['#7CB342','#33691E'],
        ['#00ACC1','#006064'],
      ];
      $g_idx = 0;
      foreach ($modul as $key_row => $rows_m) :
        $p = $palettes[$g_idx % count($palettes)];
        $g_color  = $p[0];
        $g_idx++;
        $m_idx = 0;
        $mod_count = count($rows_m['modul']);
    ?>
    <div class="group-section" data-group>
      <div class="group-hdr">
        <div class="group-bar" style="background:<?php echo $g_color?>"></div>
        <span class="group-label"><?php echo strip_tags($rows_m['group_modul_name'])?></span>
        <span class="group-count"><?php echo $mod_count?></span>
      </div>
      <div class="mod-grid">
        <?php foreach ($rows_m['modul'] as $row_modul) :
          $mp = $palettes[$m_idx % count($palettes)];
          $c1 = $mp[0]; $c2 = $mp[1];
          /* glow = c1 at 35% opacity for box-shadow */
          list($r,$g,$b) = sscanf($c1, '#%02x%02x%02x');
          $glow = "rgba($r,$g,$b,0.35)";
          $m_idx++;
          if ($row_modul->is_new_tab == 'N') {
            $href   = base_url().'dashboard?mod='.$row_modul->modul_id;
            $target = '';
          } else {
            $href   = $row_modul->link_on_new_tab;
            $target = 'target="_blank" rel="noopener"';
          }
        ?>
        <a class="mod-card"
           href="<?php echo $href?>" <?php echo $target?>
           style="--card-accent:<?php echo $c1?>;--card-accent-dk:<?php echo $c2?>;--card-glow:<?php echo $glow?>"
           data-name="<?php echo strtolower(strip_tags($row_modul->name))?>">
          <?php if ($row_modul->is_new_tab == 'Y'): ?>
            <i class="mod-ext fa fa-external-link"></i>
          <?php endif; ?>
          <div class="mod-icon-wrap">
            <i class="<?php echo $row_modul->icon?>"></i>
          </div>
          <div class="mod-name"><?php echo strip_tags(strtoupper($row_modul->name))?></div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <div id="no-result">
      <i class="fa fa-search"></i>
      Tidak ditemukan modul yang cocok.
    </div>
  </div>

  <!-- ══════════════ FOOTER ═════════════════════════════= -->
  <div id="page-footer">
    <?php echo strip_tags($app->footer, '<a><strong><em><span>')?>
  </div>

  <!-- ══════════════ SCRIPTS ════════════════════════════= -->
  <script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo base_url()?>assets/js/jquery.js'>"+"<"+"/script>");
  </script>
  <script type="text/javascript">
    if ('ontouchstart' in document.documentElement)
      document.write("<script src='<?php echo base_url()?>assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
  </script>
  <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>
  <script src="<?php echo base_url()?>assets/js/jquery-ui.custom.js"></script>
  <script src="<?php echo base_url()?>assets/js/jquery.ui.touch-punch.js"></script>
  <script src="<?php echo base_url()?>assets/js/jquery.gritter.js"></script>

  <link href="<?php echo base_url()?>assets/achtung/ui.achtung-mins.css" rel="stylesheet" />
  <script src="<?php echo base_url()?>assets/achtung/ui.achtung-min.js"></script>
  <script src="<?php echo base_url()?>assets/achtung/achtung.js"></script>

  <script src="<?php echo base_url()?>assets/js/jquery.form.js"></script>
  <script src="<?php echo base_url()?>assets/js/jquery-validation/dist/jquery.validate.js"></script>
  <script src="<?php echo base_url()?>assets/js/custom/menu_load_page.js"></script>
  <script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

  <script>
    /* ── Live clock ─────────────────────────────────────── */
    setInterval(function () {
      var n = new Date();
      var el = document.getElementById('live-clock');
      if (!el) return;
      el.innerHTML =
        String(n.getHours()).padStart(2,'0') + ':' +
        String(n.getMinutes()).padStart(2,'0') +
        '<span>WIB</span>';
    }, 1000);

    /* ── Module search filter ────────────────────────────── */
    document.getElementById('mod-search').addEventListener('input', function () {
      var q = this.value.trim().toLowerCase();
      var cards   = document.querySelectorAll('.mod-card');
      var groups  = document.querySelectorAll('[data-group]');
      var anyVisible = false;

      cards.forEach(function (c) {
        var match = !q || (c.dataset.name || '').indexOf(q) !== -1;
        c.style.display = match ? '' : 'none';
      });

      groups.forEach(function (g) {
        var visible = g.querySelectorAll('.mod-card:not([style*="display: none"])').length > 0;
        g.classList.toggle('hidden', !visible);
        if (visible) anyVisible = true;
      });

      document.getElementById('no-result').style.display = anyVisible || !q ? 'none' : 'block';
    });

    /* ── Stagger entrance animation delay ───────────────── */
    document.querySelectorAll('.mod-card').forEach(function (el, i) {
      el.style.animationDelay = (i * 0.03) + 's';
    });

    /* ── Datepicker ─────────────────────────────────────── */
    jQuery(function ($) {
      $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true
      }).next().on('click', function () {
        $(this).prev().focus();
      });

      $('#form_tmp_user').ajaxForm({
        beforeSend: function () { achtungShowLoader(); },
        uploadProgress: function () {},
        complete: function (xhr) {
          var res = JSON.parse(xhr.responseText);
          $.achtung({ message: res.message, timeout: res.status === 200 ? 3 : 5 });
          if (res.status === 200) $('#message_success').show({ speed: 'slow' });
          achtungHideLoader();
        }
      });

      $('#form_update_profile').ajaxForm({
        beforeSend: function () { achtungShowLoader(); },
        uploadProgress: function () {},
        complete: function (xhr) {
          var res = JSON.parse(xhr.responseText);
          $.achtung({ message: res.message, timeout: res.status === 200 ? 3 : 5 });
          achtungHideLoader();
        }
      });
    });

    function exc_my_account()     { jQuery('#form_tmp_user').submit();      return false; }
    function exc_update_profile() { jQuery('#form_update_profile').submit(); return false; }
  </script>

</body>
</html>
