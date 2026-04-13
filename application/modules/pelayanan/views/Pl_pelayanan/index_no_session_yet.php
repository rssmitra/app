<style>
  /* ── Scoped styles ────────────────────────────────────── */
  #session-wrap {
    padding: 20px 4px 40px;
    font-family: 'Segoe UI', system-ui, Arial, sans-serif;
  }

  #session-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.08);
    border: 1px solid #e8edf4;
    overflow: hidden;
  }

  /* ── Header ───────────────────────────────────────────── */
  .sc-header {
    background: linear-gradient(135deg, #0a2d5a 0%, #00669F 100%);
    padding: 24px 28px 20px;
    position: relative;
    overflow: hidden;
  }
  .sc-header::before {
    content: '';
    position: absolute;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    right: -60px; top: -70px;
    pointer-events: none;
  }
  .sc-header-top {
    display: flex;
    align-items: center;
    gap: 14px;
    position: relative; z-index: 1;
    margin-bottom: 16px;
  }
  .sc-header-icon {
    width: 44px; height: 44px;
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #fff;
    flex-shrink: 0;
  }
  .sc-header h2 {
    font-size: 18px; font-weight: 700;
    color: #fff; margin: 0 0 2px;
  }
  .sc-header p {
    font-size: 12.5px;
    color: rgba(255,255,255,0.6);
    margin: 0;
  }
  .sc-user-bar {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px 12px;
    padding: 9px 14px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 8px;
    position: relative; z-index: 1;
  }
  .sc-user-bar i    { color: rgba(255,255,255,0.65); font-size: 12px; }
  .sc-user-bar span { font-size: 12.5px; color: rgba(255,255,255,0.88); }
  .sc-dot { color: rgba(255,255,255,0.25) !important; font-size: 10px !important; }

  /* ── Body ─────────────────────────────────────────────── */
  .sc-body { padding: 24px 28px 28px; }

  /* Step row — 2 kolom pada layar >= 480px */
  .sc-fields {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 0;
  }
  .sc-step { width: 100%; }
  .sc-step-label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #4a5568;
    margin-bottom: 7px;
  }
  .sc-step-num {
    width: 18px; height: 18px;
    background: #00669F;
    color: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700;
    flex-shrink: 0;
  }

  /* Select overrides */
  #session-wrap .form-control,
  #session-wrap select.form-control {
    border-radius: 9px !important;
    border: 1.5px solid #dde3ec !important;
    background: #f8fafc !important;
    height: 42px !important;
    font-size: 13.5px !important;
    padding: 0 10px !important;
    transition: border-color .18s, box-shadow .18s !important;
    color: #1a202c !important;
  }
  #session-wrap .form-control:focus,
  #session-wrap select.form-control:focus {
    border-color: #00669F !important;
    background: #fff !important;
    box-shadow: 0 0 0 3px rgba(0,102,159,0.11) !important;
    outline: none !important;
  }
  /* Chosen overrides */
  #session-wrap .chosen-container         { width: 100% !important; }
  #session-wrap .chosen-container .chosen-single {
    border-radius: 9px !important;
    border: 1.5px solid #dde3ec !important;
    background: #f8fafc !important;
    height: 42px !important;
    line-height: 40px !important;
    font-size: 13.5px !important;
    box-shadow: none !important;
    padding: 0 12px !important;
    color: #1a202c !important;
  }
  #session-wrap .chosen-container-active .chosen-single,
  #session-wrap .chosen-container-active.chosen-with-drop .chosen-single {
    border-color: #00669F !important;
    background: #fff !important;
    box-shadow: 0 0 0 3px rgba(0,102,159,0.11) !important;
  }
  #session-wrap .chosen-drop {
    border-radius: 0 0 9px 9px !important;
    border-color: #dde3ec !important;
    box-shadow: 0 6px 16px rgba(0,0,0,0.1) !important;
  }

  /* ── Divider ──────────────────────────────────────────── */
  .sc-divider {
    border: none;
    border-top: 1.5px solid #eef2f7;
    margin: 22px 0;
  }

  /* ── Role section ─────────────────────────────────────── */
  .sc-role-hdr {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #4a5568;
    margin-bottom: 12px;
  }
  .sc-roles {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }
  .sc-role-btn {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    background: #f8fafc;
    cursor: pointer;
    transition: border-color .2s, background .2s, transform .18s, box-shadow .2s;
    text-align: left;
    font-family: inherit;
    width: 100%;
  }
  .sc-role-btn:hover {
    border-color: var(--rc, #00669F);
    background: var(--rc-bg, rgba(0,102,159,0.04));
    transform: translateY(-2px);
    box-shadow: 0 5px 16px rgba(0,0,0,0.09);
  }
  .sc-role-btn:active { transform: translateY(0); }
  .sc-role-icon {
    width: 52px; height: 52px;
    border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #fff;
    flex-shrink: 0;
    background: linear-gradient(135deg, var(--rc, #00669F), var(--rc-dk, #0a2d5a));
    transition: transform .2s;
  }
  .sc-role-btn:hover .sc-role-icon { transform: scale(1.08) rotate(-4deg); }
  .sc-role-text { flex: 1; min-width: 0; }
  .sc-role-title { font-size: 14px; font-weight: 700; color: #1a202c; margin-bottom: 2px; }
  .sc-role-desc  { font-size: 11.5px; color: #94a3b8; line-height: 1.4; }
  .sc-role-arrow { color: #cbd5e0; font-size: 14px; flex-shrink: 0; transition: color .2s, transform .2s; }
  .sc-role-btn:hover .sc-role-arrow { color: var(--rc, #00669F); transform: translateX(3px); }

  /* ── Hint ─────────────────────────────────────────────── */
  .sc-hint {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 14px;
    font-size: 12px;
    color: #b0bec5;
  }

  /* ── Responsive ───────────────────────────────────────── */
  @media (max-width: 520px) {
    .sc-fields { grid-template-columns: 1fr; gap: 12px; }
    .sc-roles  { grid-template-columns: 1fr; }
    .sc-body   { padding: 20px 18px 24px; }
    .sc-header { padding: 20px 18px 16px; }
  }
</style>

<div id="session-wrap">
  <div id="session-card">

    <!-- HEADER -->
    <div class="sc-header">
      <div class="sc-header-top">
        <div class="sc-header-icon">
          <i class="fa fa-stethoscope"></i>
        </div>
        <div>
          <h2>Buka Sesi Pelayanan</h2>
          <p>Pilih poli dan dokter untuk memulai sesi rawat jalan</p>
        </div>
      </div>
      <div class="sc-user-bar">
        <i class="fa fa-user-circle-o"></i>
        <span><?php echo $this->session->userdata('user')->fullname; ?></span>
        <span class="sc-dot">&bull;</span>
        <i class="fa fa-calendar-o"></i>
        <span>
          <?php
            date_default_timezone_set('Asia/Jakarta');
            $hari = array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu');
            echo $hari[date('w')] . ', ' . date('d F Y');
          ?>
        </span>
        <span class="sc-dot">&bull;</span>
        <i class="fa fa-clock-o"></i>
        <span id="sc-clock"><?php echo date('H:i'); ?> WIB</span>
      </div>
    </div>

    <!-- BODY -->
    <div class="sc-body">
      <form method="post" id="form_save_session" action="pelayanan/Pl_pelayanan/saveSessionPoli">
        <input name="current_day" type="hidden" value="<?php echo $this->tanggal->gethari(date('D'))?>">

        <!-- Step 1 & 2 — 2 kolom -->
        <div class="sc-fields">
          <div class="sc-step">
            <div class="sc-step-label">
              <div class="sc-step-num">1</div>
              Poli / Klinik
            </div>
            <?php echo $this->master->custom_selection(
              array('table'=>'mt_bagian','id'=>'kode_bagian','name'=>'nama_bagian','where'=>array('validasi'=>100,'status_aktif'=>1)),
              '', 'poliklinik', 'poliklinik', 'chosen-select form-control', '', ''
            ); ?>
          </div>

          <div class="sc-step">
            <div class="sc-step-label">
              <div class="sc-step-num">2</div>
              Dokter
            </div>
            <?php echo $this->master->get_change(
              array('table'=>'mt_dokter','id'=>'kode_dokter','name'=>'nama_pegawai','where'=>array()),
              '', 'select_dokter', 'select_dokter', 'chosen-select form-control', '', ''
            ); ?>
          </div>
        </div>

        <hr class="sc-divider">

        <!-- Step 3 — Role -->
        <div class="sc-role-hdr">
          <div class="sc-step-num">3</div>
          Pilih Peran Anda
        </div>
        <div class="sc-roles">
          <button type="submit" name="submit" value="perawat" class="sc-role-btn"
                  style="--rc:#0891b2;--rc-dk:#164e63;--rc-bg:rgba(8,145,178,0.04)">
            <div class="sc-role-icon" style="--rc:#0891b2;--rc-dk:#164e63">
              <i class="fa fa-user-md"></i>
            </div>
            <div class="sc-role-text">
              <div class="sc-role-title">Perawat</div>
              <div class="sc-role-desc">Tanda vital &amp; keperawatan</div>
            </div>
            <i class="fa fa-chevron-right sc-role-arrow"></i>
          </button>

          <button type="submit" name="submit" value="dokter" class="sc-role-btn"
                  style="--rc:#00669F;--rc-dk:#0a2d5a;--rc-bg:rgba(0,102,159,0.04)">
            <div class="sc-role-icon" style="--rc:#00669F;--rc-dk:#0a2d5a">
              <i class="fa fa-stethoscope"></i>
            </div>
            <div class="sc-role-text">
              <div class="sc-role-title">Dokter</div>
              <div class="sc-role-desc">Diagnosa, SOAP &amp; resep</div>
            </div>
            <i class="fa fa-chevron-right sc-role-arrow"></i>
          </button>
        </div>

        <div class="sc-hint">
          <i class="fa fa-info-circle"></i>
          Klik kartu sesuai peran Anda untuk membuka sesi
        </div>

      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {

  /* Live clock */
  setInterval(function () {
    var n = new Date();
    var pad = function(v){ return String(v).padStart(2,'0'); };
    var el = document.getElementById('sc-clock');
    if (el) el.textContent = pad(n.getHours()) + ':' + pad(n.getMinutes()) + ' WIB';
  }, 1000);

  function initForm() {
    var useSwal = (typeof Swal !== 'undefined');

    function notifyLoading(msg) {
      if (useSwal) {
        Swal.fire({
          title: msg || 'Memproses...',
          html: '<span style="color:#64748b;font-size:14px">Mohon tunggu sebentar</span>',
          allowOutsideClick: false, allowEscapeKey: false,
          showConfirmButton: false, confirmButtonColor: '#00669F',
          didOpen: function () { Swal.showLoading(); }
        });
      } else { achtungShowLoader(); }
    }

    function notifySuccess(msg) {
      if (useSwal) {
        Swal.fire({
          icon: 'success', title: 'Sesi Dibuka',
          html: '<span style="color:#64748b;font-size:14px">' + (msg || 'Sesi berhasil dibuka.') + '</span>',
          timer: 1400, timerProgressBar: true,
          showConfirmButton: false, allowOutsideClick: false,
          confirmButtonColor: '#00669F'
        }).then(function () { getMenu('pelayanan/Pl_pelayanan'); });
      } else {
        $.achtung({ message: msg, timeout: 3 });
        achtungHideLoader();
        getMenu('pelayanan/Pl_pelayanan');
      }
    }

    function notifyError(msg) {
      if (useSwal) {
        Swal.fire({
          icon: 'error', title: 'Gagal',
          text: msg || 'Terjadi kesalahan. Silakan coba lagi.',
          confirmButtonColor: '#00669F', confirmButtonText: 'OK'
        });
      } else {
        $.achtung({ message: msg, timeout: 5, className: 'achtungFail' });
        achtungHideLoader();
      }
    }

    /* AJAX submit */
    $('#form_save_session').ajaxForm({
      beforeSend: function () { notifyLoading('Membuka Sesi...'); },
      complete: function (xhr) {
        var res;
        try { res = JSON.parse(xhr.responseText); }
        catch(e) { res = { status: 500, message: 'Respons tidak valid.' }; }
        res.status === 200 ? notifySuccess(res.message) : notifyError(res.message);
      }
    });

    /* Load dokter berdasarkan poli */
    $('select[name="poliklinik"]').change(function () {
      var $dok = $('#select_dokter');
      $dok.prop('disabled', true).html('<option value="">Memuat data dokter...</option>');
      if (typeof $.fn.chosen !== 'undefined' && !ace.vars['touch']) {
        $dok.trigger('chosen:updated');
      }
      $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val(), function (data) {
        $dok.html('<option value="">— Pilih Dokter —</option>');
        $.each(data, function (i, o) {
          $('<option>').val(o.kode_dokter).text(o.nama_pegawai).appendTo($dok);
        });
        $dok.prop('disabled', false);
        if (typeof $.fn.chosen !== 'undefined' && !ace.vars['touch']) {
          $dok.trigger('chosen:updated');
        }
      });
    });

    /* Chosen init */
    if (typeof $.fn.chosen !== 'undefined' && !ace.vars['touch']) {
      $('select[name="poliklinik"]').chosen({ allow_single_deselect: true, width: '100%' });
      $('#select_dokter').chosen({ allow_single_deselect: true, width: '100%', search_contains: true });
    }
  }

  /* Load SweetAlert2 secara aman */
  if (typeof Swal === 'undefined') {
    var lnk = document.createElement('link');
    lnk.rel = 'stylesheet';
    lnk.href = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css';
    document.head.appendChild(lnk);

    var scr = document.createElement('script');
    scr.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    scr.onload = initForm;
    document.head.appendChild(scr);
  } else {
    initForm();
  }

});
</script>
