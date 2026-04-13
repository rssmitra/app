<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta charset="UTF-8">
  <meta name="description" content="Smart Hospital System 4.0 adalah sistem rumah sakit yang sudah terintegrasi antar modulnya dan juga terintegrasi dengan BPJS seperti VClaim, ICare JKN, Antrian Online, MJKN, Pcare, Satu Sehat Kemenkes, dsb." />
  <meta name="keywords" content="SIMRS, Smart Hospital, Bridging, BPJS">
  <meta name="author" content="<?php echo COMP_LONG; ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="robots" content="noindex, nofollow">
  <title><?php echo COMP_SORT; ?> — Login</title>

  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
  <link rel="shortcut icon" href="<?php echo base_url().COMP_ICON; ?>">

  <style>
    :root {
      --blue:     #00669F;
      --blue-dk:  #0a2d5a;
      --blue-md:  #005080;
      --surface:  #ffffff;
      --bg:       #f0f4f8;
      --border:   #dde3ec;
      --text-1:   #1a202c;
      --text-2:   #4a5568;
      --text-3:   #94a3b8;
      --danger:   #dc2626;
      --warn:     #d97706;
      --success:  #16a34a;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html, body { height: 100%; }

    body {
      font-family: 'Segoe UI', system-ui, Arial, sans-serif;
      background: var(--blue-dk);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      position: relative;
      overflow: hidden;
    }

    /* ─── BACKGROUND ───────────────────────────────────── */
    #bg-img {
      position: fixed;
      inset: 0;
      background: url('<?php echo PATH_IMG_DEFAULT.$profile_form->cover_login?>') center/cover no-repeat;
      filter: blur(4px) brightness(0.35);
      transform: scale(1.06);
      z-index: 0;
    }

    /* Dot grid pattern */
    #bg-pattern {
      position: fixed;
      inset: 0;
      z-index: 1;
      background-image: radial-gradient(circle, rgba(255,255,255,0.13) 1px, transparent 1px);
      background-size: 30px 30px;
      animation: patternDrift 40s linear infinite;
    }
    @keyframes patternDrift {
      0%   { background-position: 0 0; }
      100% { background-position: 300px 300px; }
    }

    /* Floating glow orbs */
    .bg-orb {
      position: fixed;
      border-radius: 50%;
      filter: blur(80px);
      z-index: 1;
      pointer-events: none;
      opacity: 0;
      animation: orbFade .8s ease forwards;
    }
    @keyframes orbFade { to { opacity: 1; } }

    .bg-orb-1 {
      width: 520px; height: 520px;
      background: radial-gradient(circle, rgba(0,102,159,0.5), transparent 70%);
      top: -140px; left: -100px;
      animation: orbFade .8s ease forwards, orb1 22s ease-in-out infinite alternate;
    }
    .bg-orb-2 {
      width: 480px; height: 480px;
      background: radial-gradient(circle, rgba(0,180,200,0.35), transparent 70%);
      bottom: -120px; right: -80px;
      animation: orbFade .8s ease forwards, orb2 28s ease-in-out infinite alternate;
    }
    .bg-orb-3 {
      width: 360px; height: 360px;
      background: radial-gradient(circle, rgba(80,60,180,0.3), transparent 70%);
      top: 40%; left: 50%;
      transform: translate(-50%, -50%);
      animation: orbFade .8s ease forwards, orb3 18s ease-in-out infinite alternate;
    }
    .bg-orb-4 {
      width: 300px; height: 300px;
      background: radial-gradient(circle, rgba(0,102,159,0.3), transparent 70%);
      bottom: 10%; left: 15%;
      animation: orbFade .8s ease forwards, orb4 24s ease-in-out infinite alternate;
    }

    @keyframes orb1 {
      from { transform: translate(0, 0)    scale(1); }
      to   { transform: translate(60px, 50px) scale(1.1); }
    }
    @keyframes orb2 {
      from { transform: translate(0, 0)     scale(1); }
      to   { transform: translate(-50px, -40px) scale(1.08); }
    }
    @keyframes orb3 {
      from { transform: translate(-50%, -50%) scale(1); }
      to   { transform: translate(-50%, -50%) scale(1.2) rotate(20deg); }
    }
    @keyframes orb4 {
      from { transform: translate(0, 0) scale(1); }
      to   { transform: translate(40px, -30px) scale(1.12); }
    }

    /* ─── LOGIN CARD ───────────────────────────────────── */
    #login-card {
      position: relative;
      z-index: 2;
      display: flex;
      width: min(920px, 96vw);
      min-height: 560px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 24px 64px rgba(0,0,0,0.55);
      animation: cardIn .4s cubic-bezier(.22,1,.36,1) both;
    }
    @keyframes cardIn {
      from { opacity: 0; transform: translateY(28px) scale(.97); }
      to   { opacity: 1; transform: none; }
    }

    /* ─── LEFT PANEL ───────────────────────────────────── */
    #panel-left {
      width: 42%;
      background: linear-gradient(160deg, var(--blue-dk) 0%, #005a8a 60%, var(--blue) 100%);
      padding: 36px 28px;
      display: flex;
      flex-direction: column;
      position: relative;
      overflow: hidden;
    }
    /* decorative circles */
    #panel-left::before {
      content: '';
      position: absolute;
      width: 300px; height: 300px;
      border-radius: 50%;
      background: rgba(255,255,255,0.04);
      right: -80px; top: -80px;
    }
    #panel-left::after {
      content: '';
      position: absolute;
      width: 200px; height: 200px;
      border-radius: 50%;
      background: rgba(255,255,255,0.04);
      left: -50px; bottom: 60px;
    }
    .lp-logo { width: 200px; max-width: 80%; object-fit: contain; margin-bottom: 8px; }
    .lp-by   { height: 26px; object-fit: contain; opacity: 0.7; margin-bottom: 24px; }
    .lp-title {
      font-size: 18px;
      font-weight: 700;
      color: #fff;
      line-height: 1.3;
      margin-bottom: 6px;
    }
    .lp-sub {
      font-size: 12px;
      color: rgba(255,255,255,0.55);
      line-height: 1.6;
      margin-bottom: auto;
    }

    /* Bridging badges */
    .lp-bridging {
      margin-top: 28px;
    }
    .lp-bridging-label {
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: rgba(255,255,255,0.4);
      margin-bottom: 10px;
    }
    .lp-bridging-logos {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .lp-bridging-logos img {
      height: 38px;
      object-fit: contain;
      opacity: 0.9;
      transition: opacity .2s, transform .2s;
      border-radius: 4px;
    }
    .lp-bridging-logos img:hover { opacity: 1; transform: scale(1.08); }

    /* PSE info */
    .lp-pse {
      margin-top: 20px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 10px;
      padding: 10px 12px;
    }
    .lp-pse img { height: 34px; flex-shrink: 0; }
    .lp-pse-text { font-size: 10px; color: rgba(255,255,255,0.65); line-height: 1.5; }
    .lp-pse-text strong { color: rgba(255,255,255,0.88); font-size: 11px; }

    /* ─── RIGHT PANEL ──────────────────────────────────── */
    #panel-right {
      flex: 1;
      background: var(--surface);
      padding: 36px 36px 30px;
      display: flex;
      flex-direction: column;
    }
    .rp-header { margin-bottom: 28px; }
    .rp-header .rp-icon {
      width: 48px; height: 48px;
      border-radius: 14px;
      background: linear-gradient(135deg, var(--blue-dk), var(--blue));
      display: flex; align-items: center; justify-content: center;
      color: #fff;
      font-size: 20px;
      margin-bottom: 14px;
    }
    .rp-header h2 {
      font-size: 22px;
      font-weight: 700;
      color: var(--text-1);
      margin-bottom: 4px;
    }
    .rp-header p {
      font-size: 13px;
      color: var(--text-3);
    }

    /* ─── FORM ─────────────────────────────────────────── */
    .f-group { margin-bottom: 16px; }
    .f-label {
      display: block;
      font-size: 12px;
      font-weight: 700;
      color: var(--text-2);
      margin-bottom: 6px;
      letter-spacing: 0.3px;
    }
    .f-input-wrap { position: relative; }
    .f-input {
      width: 100%;
      height: 44px;
      padding: 0 42px 0 14px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      font-size: 14px;
      color: var(--text-1);
      background: var(--bg);
      outline: none;
      transition: border-color .18s, box-shadow .18s, background .18s;
    }
    .f-input:focus {
      border-color: var(--blue);
      background: #fff;
      box-shadow: 0 0 0 3px rgba(0,102,159,0.12);
    }
    .f-input.is-error {
      border-color: var(--danger);
      box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
    }
    .f-input-icon {
      position: absolute;
      right: 13px; top: 50%;
      transform: translateY(-50%);
      color: var(--text-3);
      font-size: 15px;
      pointer-events: none;
    }
    /* Toggle password button */
    .f-eye {
      position: absolute;
      right: 10px; top: 50%;
      transform: translateY(-50%);
      width: 28px; height: 28px;
      display: flex; align-items: center; justify-content: center;
      border: none; background: none;
      cursor: pointer;
      color: var(--text-3);
      font-size: 15px;
      border-radius: 6px;
      transition: color .18s, background .18s;
    }
    .f-eye:hover { color: var(--blue); background: rgba(0,102,159,0.08); }

    /* Error text */
    .f-err {
      font-size: 11.5px;
      color: var(--danger);
      margin-top: 5px;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .f-err i { font-size: 11px; }
    /* Caps lock warning */
    .f-warn {
      font-size: 11.5px;
      color: var(--warn);
      margin-top: 5px;
      display: none;
      align-items: center;
      gap: 4px;
    }
    .f-warn.visible { display: flex; }
    .f-warn i { font-size: 11px; }

    /* ─── EXTRAS ROW ───────────────────────────────────── */
    .f-extras {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .f-check-label {
      display: flex;
      align-items: center;
      gap: 7px;
      font-size: 13px;
      color: var(--text-2);
      cursor: pointer;
      user-select: none;
    }
    .f-check-label input[type=checkbox] {
      width: 16px; height: 16px;
      accent-color: var(--blue);
      cursor: pointer;
    }

    /* ─── SUBMIT BUTTON ────────────────────────────────── */
    #btn-login {
      width: 100%;
      height: 46px;
      background: linear-gradient(135deg, var(--blue-dk), var(--blue));
      border: none;
      border-radius: 10px;
      color: #fff;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: 0.5px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 9px;
      transition: opacity .18s, transform .18s, box-shadow .18s;
      box-shadow: 0 4px 14px rgba(0,102,159,0.35);
    }
    #btn-login:hover:not(:disabled) {
      opacity: 0.92;
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(0,102,159,0.45);
    }
    #btn-login:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
    }
    #btn-login .btn-spinner {
      display: none;
      width: 18px; height: 18px;
      border: 2px solid rgba(255,255,255,0.35);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin .7s linear infinite;
    }
    #btn-login.loading .btn-spinner { display: block; }
    #btn-login.loading .btn-text   { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }


    /* ─── FOOTER (clock) ───────────────────────────────── */
    .rp-footer {
      margin-top: auto;
      padding-top: 20px;
      text-align: center;
      font-size: 11.5px;
      color: var(--text-3);
    }
    .rp-footer strong { color: var(--text-2); }

    /* ─── SWEETALERT2 CUSTOM ───────────────────────────── */
    .swal-login-popup {
      border-radius: 18px !important;
      font-family: 'Segoe UI', system-ui, Arial, sans-serif !important;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3) !important;
    }
    .swal2-title { font-size: 18px !important; font-weight: 700 !important; }
    .swal2-html-container { font-size: 14px !important; }
    .swal2-confirm { border-radius: 8px !important; font-weight: 600 !important; padding: 10px 22px !important; }
    .swal2-timer-progress-bar { background: var(--blue) !important; }
    .swal2-loader { border-color: var(--blue) transparent var(--blue) transparent !important; }

    /* ─── RESPONSIVE ───────────────────────────────────── */
    @media (max-width: 680px) {
      #login-card { flex-direction: column; width: 96vw; min-height: unset; }
      #panel-left {
        width: 100%;
        padding: 24px 20px;
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
      }
      #panel-left::before, #panel-left::after { display: none; }
      .lp-logo { width: 140px; margin-bottom: 0; }
      .lp-by   { display: none; }
      .lp-title, .lp-sub { display: none; }
      .lp-bridging { margin-top: 0; }
      .lp-pse { margin-top: 0; flex: 1 1 100%; }
      #panel-right { padding: 24px 20px 20px; }
    }
  </style>
</head>
<body>

  <div id="bg-img"></div>
  <div id="bg-pattern"></div>
  <div class="bg-orb bg-orb-1"></div>
  <div class="bg-orb bg-orb-2"></div>
  <div class="bg-orb bg-orb-3"></div>
  <div class="bg-orb bg-orb-4"></div>

  <div id="login-card">

    <!-- ══════════ LEFT PANEL ══════════════════════════ -->
    <div id="panel-left">
      <img class="lp-logo" src="<?php echo base_url().COMP_ICON_INSANI?>" alt="Logo RS">
      <img class="lp-by"   src="<?php echo base_url().COMP_ICON_BY_INSANI?>" alt="By Insani">

      <div class="lp-title"><?php echo APPS_NAME_LONG; ?></div>
      <div class="lp-sub">
        Versi <?php echo APPS_VERSION; ?><br>
        Sistem informasi rumah sakit terintegrasi dengan BPJS, Satu Sehat Kemenkes, dan ekosistem layanan kesehatan nasional.
      </div>

      <div class="lp-bridging">
        <div class="lp-bridging-label">Bridging System</div>
        <div class="lp-bridging-logos">
          <img src="<?php echo base_url()?>assets/images/bpjs.png"        alt="BPJS">
          <img src="<?php echo base_url()?>assets/images/satu-sehat.png"  alt="Satu Sehat" style="height:34px">
          <img src="<?php echo base_url()?>assets/images/icare.png"        alt="iCare"      style="height:32px">
          <img src="<?php echo base_url()?>assets/images/kominfo.png"      alt="Kominfo"    style="height:32px">
        </div>
      </div>

      <div class="lp-pse">
        <img src="<?php echo base_url()?>assets/images/pse.png" alt="PSE">
        <div class="lp-pse-text">
          <strong><?php echo strtoupper(APPS_NAME_LONG).' '.APPS_VERSION; ?></strong><br>
          No. PSE. <?php echo NO_PSE; ?><br>
          <span style="font-style:italic">Terdaftar pada Direktorat Tata Kelola Aptika KOMINFO RI</span>
        </div>
      </div>
    </div>

    <!-- ══════════ RIGHT PANEL ═════════════════════════ -->
    <div id="panel-right">

      <div class="rp-header">
        <div class="rp-icon"><i class="fa fa-lock"></i></div>
        <h2>Masuk ke Sistem</h2>
        <p>Silakan masukkan kredensial Anda untuk melanjutkan</p>
      </div>

      <form method="post" action="<?php echo base_url().'login/process'?>" id="form-login" autocomplete="off" novalidate>

        <!-- Username -->
        <div class="f-group">
          <label class="f-label" for="username">
            <i class="fa fa-id-card-o" style="margin-right:4px;opacity:.6"></i>NIP / Username
          </label>
          <div class="f-input-wrap">
            <input type="text"
                   id="username"
                   name="username"
                   class="f-input <?php echo form_error('username') ? 'is-error' : ''?>"
                   placeholder="Masukkan username"
                   value="<?php echo set_value('username')?>"
                   autocomplete="username"
                   maxlength="100"
                   spellcheck="false"
                   required>
            <i class="f-input-icon fa fa-user"></i>
          </div>
          <?php if(form_error('username')): ?>
            <div class="f-err"><i class="fa fa-times-circle"></i><?php echo form_error('username')?></div>
          <?php endif; ?>
        </div>

        <!-- Password -->
        <div class="f-group">
          <label class="f-label" for="password">
            <i class="fa fa-key" style="margin-right:4px;opacity:.6"></i>Password
          </label>
          <div class="f-input-wrap">
            <input type="password"
                   id="password"
                   name="password"
                   class="f-input <?php echo form_error('password') ? 'is-error' : ''?>"
                   placeholder="Masukkan password"
                   autocomplete="current-password"
                   maxlength="128"
                   required>
            <button type="button" class="f-eye" id="toggle-pw" aria-label="Tampilkan password" tabindex="-1">
              <i class="fa fa-eye" id="eye-icon"></i>
            </button>
          </div>
          <?php if(form_error('password')): ?>
            <div class="f-err"><i class="fa fa-times-circle"></i><?php echo form_error('password')?></div>
          <?php endif; ?>
          <div class="f-warn" id="caps-warn">
            <i class="fa fa-exclamation-triangle"></i> Caps Lock aktif
          </div>
        </div>

        <!-- Extras -->
        <div class="f-extras">
          <label class="f-check-label" for="show-pw">
            <input type="checkbox" id="show-pw">
            Tampilkan password
          </label>
        </div>

        <!-- Submit -->
        <button type="button" id="btn-login">
          <div class="btn-spinner"></div>
          <span class="btn-text"><i class="fa fa-sign-in"></i>&nbsp; Masuk</span>
        </button>

      </form>

      <div class="rp-footer">
        <span id="live-clock"></span> &nbsp;&middot;&nbsp;
        <strong><?php echo COMP_SORT; ?></strong> &copy; <?php echo date('Y'); ?>
      </div>
    </div>

  </div><!-- /#login-card -->

  <!-- ══════════ SCRIPTS ════════════════════════════════ -->
  <script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo base_url()?>assets/js/jquery.js'>"+"<"+"/script>");
  </script>
  <script type="text/javascript">
    if ('ontouchstart' in document.documentElement)
      document.write("<script src='<?php echo base_url()?>assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
  </script>

  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo base_url()?>assets/js/jquery.form.js"></script>

  <script>
  (function () {

    /* ── Live clock ───────────────────────────────────── */
    function tick() {
      var n = new Date();
      var pad = function(v){ return String(v).padStart(2,'0'); };
      var days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
      var months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
      document.getElementById('live-clock').textContent =
        days[n.getDay()] + ', ' + n.getDate() + ' ' + months[n.getMonth()] + ' ' + n.getFullYear() +
        '  —  ' + pad(n.getHours()) + ':' + pad(n.getMinutes()) + ':' + pad(n.getSeconds()) + ' WIB';
    }
    tick(); setInterval(tick, 1000);

    /* ── Show / hide password ─────────────────────────── */
    var pwInput  = document.getElementById('password');
    var eyeIcon  = document.getElementById('eye-icon');
    var togglePw = document.getElementById('toggle-pw');
    var checkPw  = document.getElementById('show-pw');

    function setVisibility(visible) {
      pwInput.type = visible ? 'text' : 'password';
      eyeIcon.className = visible ? 'fa fa-eye-slash' : 'fa fa-eye';
    }
    togglePw.addEventListener('click', function () {
      setVisibility(pwInput.type === 'password');
      checkPw.checked = (pwInput.type === 'text');
    });
    checkPw.addEventListener('change', function () { setVisibility(this.checked); });

    /* ── Caps lock indicator ──────────────────────────── */
    var capsWarn = document.getElementById('caps-warn');
    pwInput.addEventListener('keydown', function (e) {
      capsWarn.classList.toggle('visible', e.getModifierState && e.getModifierState('CapsLock'));
    });
    pwInput.addEventListener('keyup', function (e) {
      capsWarn.classList.toggle('visible', e.getModifierState && e.getModifierState('CapsLock'));
    });

    /* ── Enter key navigation ────────────────────────── */
    document.getElementById('username').addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); pwInput.focus(); }
    });
    pwInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); document.getElementById('btn-login').click(); }
    });

    /* ── SweetAlert2 theme defaults ─────────────────── */
    var swalBase = {
      confirmButtonColor: '#00669F',
      cancelButtonColor:  '#94a3b8',
      customClass: { popup: 'swal-login-popup' }
    };

    /* ── AJAX login ──────────────────────────────────── */
    jQuery(function ($) {

      var $btn  = $('#btn-login');
      var $form = $('#form-login');

      $btn.on('click', function () {

        /* Trim username */
        var $user = $('#username');
        $user.val($.trim($user.val()));

        /* Front-end validation */
        if (!$user.val()) {
          $user.addClass('is-error').focus();
          Swal.fire(Object.assign({}, swalBase, {
            icon: 'warning',
            title: 'Perhatian',
            text: 'Username tidak boleh kosong.',
            confirmButtonText: 'OK'
          }));
          return;
        }
        if (!$('#password').val()) {
          $('#password').addClass('is-error').focus();
          Swal.fire(Object.assign({}, swalBase, {
            icon: 'warning',
            title: 'Perhatian',
            text: 'Password tidak boleh kosong.',
            confirmButtonText: 'OK'
          }));
          return;
        }

        /* Loading dialog */
        Swal.fire(Object.assign({}, swalBase, {
          title: 'Memverifikasi...',
          html: '<span style="color:#64748b;font-size:14px">Mohon tunggu sebentar</span>',
          allowOutsideClick: false,
          allowEscapeKey: false,
          showConfirmButton: false,
          didOpen: function () { Swal.showLoading(); }
        }));

        $btn.addClass('loading').prop('disabled', true);

        $form.ajaxSubmit({
          complete: function (xhr) {
            var res;
            try { res = JSON.parse(xhr.responseText); }
            catch(e) { res = { status: 500, message: 'Respons server tidak valid.' }; }

            if (res.status === 200) {
              Swal.fire(Object.assign({}, swalBase, {
                icon: 'success',
                title: 'Login Berhasil',
                html: '<span style="color:#64748b;font-size:14px">Mengalihkan ke halaman utama...</span>',
                timer: 1400,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false
              })).then(function () {
                window.location = '<?php echo base_url().'main'?>';
              });
            } else {
              $btn.removeClass('loading').prop('disabled', false);
              Swal.fire(Object.assign({}, swalBase, {
                icon: 'error',
                title: 'Login Gagal',
                text: res.message || 'Username atau password salah. Periksa kembali dan coba lagi.',
                confirmButtonText: '<i class="fa fa-refresh"></i>&nbsp; Coba Lagi',
                footer: '<span style="font-size:12px;color:#94a3b8">Hubungi administrator jika masalah berlanjut</span>'
              })).then(function () {
                $('#password').val('').addClass('is-error').focus();
              });
            }
          }
        });
      });

      /* Clear error styling on re-focus */
      $('#username, #password').on('focus input', function () {
        $(this).removeClass('is-error');
      });

      /* Auto-focus first empty field */
      var $usr = $('#username');
      ($usr.val() ? $('#password') : $usr).focus();

    });

  }());
  </script>

</body>
</html>
