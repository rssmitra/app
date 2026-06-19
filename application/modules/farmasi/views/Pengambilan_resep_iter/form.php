<script src="<?php echo base_url()?>assets/js/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>

<style>
  /* ===== Card / Wrap ===== */
  .pu-wrap {
    border: 1px solid #b8d0e8; border-radius: 7px; overflow: hidden;
    margin-bottom: 16px; box-shadow: 0 2px 8px rgba(26,79,138,.09); background: #fff;
  }
  .pu-hdr {
    background: linear-gradient(135deg, #1a4f8a 0%, #2c6fad 100%);
    color: #fff; padding: 10px 16px; font-size: 13px; font-weight: 700;
    display: flex; align-items: center; gap: 9px;
  }
  .pu-body { padding: 14px 18px; }

  /* ===== Info grid ===== */
  .pu-info-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(175px, 1fr));
    gap: 10px; margin-bottom: 14px;
  }
  .pu-info-item { display: flex; flex-direction: column; gap: 2px; }
  .pu-info-label {
    font-size: 11px; color: #6b8cae; font-weight: 600;
    text-transform: uppercase; letter-spacing: .4px;
  }
  .pu-info-label i { color: #2c6fad; margin-right: 3px; }
  .pu-info-val { font-size: 13px; font-weight: 700; color: #1a4f8a; }

  /* ===== Reference badge ===== */
  .ref-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #dbeafe; border: 1px solid #93c5fd;
    border-radius: 5px; padding: 4px 12px;
    font-size: 12px; font-weight: 700; color: #1e40af;
  }

  /* ===== Table ===== */
  .pu-tbl-wrap { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; margin-bottom: 12px; }
  .pu-tbl-hdr  { background: #1a4f8a; color: #fff; padding: 7px 13px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 7px; }
  .pu-tbl-note { background: #f0f5fb; border-bottom: 1px solid #c0d4e8; padding: 5px 13px; font-size: 11px; color: #6b8cae; display: flex; align-items: center; gap: 5px; }

  #verifikasi-resep-obat { width: 100% !important; border-collapse: collapse; font-size: 12px; }
  #verifikasi-resep-obat thead tr { background: #2c6fad; color: #fff; }
  #verifikasi-resep-obat thead th {
    padding: 7px 8px; text-align: center; font-weight: 600;
    border: 1px solid #1e5590; vertical-align: middle; font-size: 11px; line-height: 1.3;
  }
  #verifikasi-resep-obat tbody tr:nth-child(even) { background: #f5f9fd; }
  #verifikasi-resep-obat tbody tr:hover           { background: #e8f0f9; }
  #verifikasi-resep-obat tbody td { padding: 6px 8px; border: 1px solid #d0dce8; vertical-align: middle; }

  /* ===== Qty input ===== */
  .qty-input {
    width: 72px; text-align: center; padding: 2px 4px; height: 26px;
    font-size: 12px; font-weight: 700; color: #1a4f8a;
    border: 1px solid #c0d4e8; border-radius: 4px; background: #f0f5fb;
    transition: border-color .15s, background .15s;
  }
  .qty-input:focus { outline: none; border-color: #2c6fad; background: #fff; box-shadow: 0 0 0 2px rgba(44,111,173,.18); }

  /* ===== Buttons ===== */
  .pu-btn-submit {
    background: linear-gradient(135deg, #1a4f8a 0%, #2c6fad 100%);
    color: #fff !important; border: none; border-radius: 4px; padding: 6px 18px;
    font-size: 12px; font-weight: 700; display: inline-flex; align-items: center;
    gap: 6px; cursor: pointer; text-decoration: none; transition: opacity .15s;
  }
  .pu-btn-submit:hover { opacity: .88; }
  .pu-btn-back {
    background: #f0f5fb; color: #2c4a6e !important; border: 1px solid #b8d0e8;
    border-radius: 4px; padding: 4px 12px; font-size: 12px; font-weight: 600;
    display: inline-flex; align-items: center; gap: 5px;
    text-decoration: none; transition: background .15s; cursor: pointer;
  }
  .pu-btn-back:hover { background: #dbeafe; color: #1a4f8a !important; }
  .pu-btn-save {
    background: #f0f5fb; color: #1a4f8a; border: 1px solid #c0d4e8;
    border-radius: 3px; padding: 2px 7px; font-size: 11px; cursor: pointer;
  }
  .pu-btn-save:hover { background: #dbeafe; }
  .pu-btn-edit {
    background: #fef3c7; color: #92400e; border: 1px solid #fcd34d;
    border-radius: 3px; padding: 2px 7px; font-size: 11px; cursor: pointer;
  }
  .pu-btn-edit:hover { background: #fde68a; }

  /* ===== Legend ===== */
  .pu-legend {
    background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 5px; padding: 8px 14px; font-size: 11px; color: #475569;
  }
  .pu-legend .leg-title { font-weight: 700; color: #2c4a6e; margin-bottom: 4px; }

  /* ===== Status ===== */
  .badge-lunas { color: #15803d; font-weight: 700; }
  .badge-na    { color: #c0392b; font-weight: 700; font-style: italic; }

  /* ===== Patient Info Card ===== */
  .pi-hdr-badge {
    margin-left: 6px;
    background: rgba(255,255,255,.18); color: #fff;
    border: 1px solid rgba(255,255,255,.38);
    border-radius: 4px; padding: 1px 9px; font-size: 11px; font-weight: 700; letter-spacing: .6px;
  }
  .pi-profile-strip {
    display: flex; align-items: center; gap: 16px;
    padding: 16px 20px;
    background: linear-gradient(135deg, #f0f6ff 0%, #e8f0fa 100%);
    border-bottom: 1px solid #c0d4e8;
    flex-wrap: wrap;
  }
  .pi-avatar {
    width: 52px; height: 52px; border-radius: 50%;
    background: linear-gradient(135deg, #1a4f8a 0%, #2c6fad 100%);
    color: #fff; font-size: 22px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; box-shadow: 0 2px 10px rgba(26,79,138,.3);
    text-transform: uppercase;
  }
  .pi-ident { flex: 1; min-width: 0; }
  .pi-name {
    font-size: 15px; font-weight: 800; color: #1a3a5c;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-bottom: 6px; text-transform: uppercase;
  }
  .pi-tags { display: flex; flex-wrap: wrap; gap: 6px; }
  .pi-tag {
    display: inline-flex; align-items: center; gap: 4px;
    border-radius: 50px; padding: 2px 10px;
    font-size: 11px; font-weight: 600;
  }
  .pi-tag-mr  { background: #eff6ff; border: 1px solid #93c5fd; color: #1e40af; }
  .pi-tag-sep { background: #f0fdf4; border: 1px solid #6ee7b7; color: #065f46; }
  .pi-ref-box { text-align: right; flex-shrink: 0; }
  .pi-ref-label {
    font-size: 10px; color: #6b8cae; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px;
  }
  .pi-ref-code {
    display: inline-block;
    background: linear-gradient(135deg, #1a4f8a 0%, #2c6fad 100%); color: #fff;
    border-radius: 5px; padding: 5px 14px;
    font-size: 13px; font-weight: 700; letter-spacing: .5px;
    box-shadow: 0 2px 6px rgba(26,79,138,.25); margin-bottom: 4px;
  }
  .pi-ref-noResep { font-size: 11px; color: #6b8cae; font-weight: 600; }
  .pi-chips-row {
    display: flex; flex-wrap: wrap;
    border-bottom: 1px solid #e2eaf4;
  }
  .pi-chip {
    flex: 1; min-width: 140px;
    display: flex; align-items: center; gap: 10px;
    padding: 10px 20px;
    border-right: 1px solid #e2eaf4;
  }
  .pi-chip:last-child { border-right: none; }
  .pi-chip-icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: #dbeafe; color: #1a4f8a;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0;
  }
  .pi-chip-label {
    font-size: 10px; color: #6b8cae; font-weight: 600;
    text-transform: uppercase; letter-spacing: .3px;
  }
  .pi-chip-val { font-size: 12px; font-weight: 700; color: #1a3a5c; margin-top: 2px; }
  .pi-actions {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 8px;
    padding: 10px 20px; background: #f8fafc;
  }

  /* ===== Taken (sudah diambil) banner ===== */
  .pi-taken-banner {
    display: flex; align-items: center; gap: 14px;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-top: 1px solid #86efac; border-bottom: 1px solid #86efac;
    padding: 12px 20px;
  }
  .pi-taken-icon {
    width: 42px; height: 42px; border-radius: 50%;
    background: #16a34a; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(22,163,74,.3);
  }
  .pi-taken-title  { font-size: 13px; font-weight: 800; color: #15803d; margin-bottom: 3px; }
  .pi-taken-detail { font-size: 12px; color: #166534; }
  .pi-taken-detail strong { font-weight: 700; }
  .pi-taken-note   { font-size: 11px; color: #4b8d5e; font-style: italic; margin-top: 3px; }
  .pi-btn-disabled {
    display: inline-flex; align-items: center; gap: 6px;
    background: #e2e8f0; color: #94a3b8; border: none; border-radius: 4px;
    padding: 6px 18px; font-size: 12px; font-weight: 700; cursor: not-allowed;
  }
  /* Table locked overlay when already taken */
  .tbl-locked td, .tbl-locked th { opacity: .72; }
  .tbl-locked input[type=checkbox], .tbl-locked input[type=number] { pointer-events: none; opacity: .5; }
</style>

<!-- ── Page Header ── -->
<div class="page-header" style="margin-bottom: 14px">
  <h1 style="font-size: 18px; color: #1a4f8a; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;">
    <i class="fa fa-medkit" style="font-size: 16px"></i>
    <?php echo $title?>
    <small style="font-size: 12px; color: #6b8cae; font-weight: 400">
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo isset($breadcrumbs) ? $breadcrumbs : ''?>
    </small>
  </h1>
</div>

<div class="row">
<div class="col-xs-12">

<form class="form-horizontal" method="post" id="form_proses_resep"
      enctype="multipart/form-data" autocomplete="off"
      action="farmasi/Pengambilan_resep_iter/process">

  <!-- Hidden inputs -->
  <input type="hidden" name="kode_trans_far"  id="kode_trans_far"  value="<?php echo isset($value) ? ucwords($value->kode_trans_far)  : ''?>">
  <input type="hidden" name="no_sep"          id="no_sep"          value="<?php echo isset($value) ? ucwords($value->no_sep)          : ''?>">
  <input type="hidden" name="no_mr"           id="no_mr"           value="<?php echo isset($value) ? ucwords($value->no_mr)           : ''?>">
  <input type="hidden" name="no_registrasi"   id="no_registrasi"   value="<?php echo isset($value) ? ucwords($value->no_registrasi)   : ''?>">
  <input type="hidden" name="no_kunjungan"    id="no_kunjungan"    value="<?php echo isset($value) ? ucwords($value->no_kunjungan)    : ''?>">
  <input type="hidden" name="kode_dokter"     id="kode_dokter"     value="<?php echo isset($value) ? ucwords($value->kode_dokter)     : ''?>">
  <input type="hidden" name="dokter_pengirim" id="dokter_pengirim" value="<?php echo isset($value) ? ucwords($value->dokter_pengirim) : ''?>">
  <input type="hidden" name="nama_pasien"     id="nama_pasien"     value="<?php echo isset($value) ? ucwords($value->nama_pasien)     : ''?>">
  <input type="hidden" name="tlp_pasien"      id="tlp_pasien"      value="<?php echo isset($value) ? ucwords($value->telpon_pasien)   : ''?>">
  <input type="hidden" name="last_iter"       id="last_iter"       value="<?php echo isset($value) ? ucwords($value->iter)            : ''?>">
  <input type="hidden" name="flag_trans"      id="flag_trans"      value="ITR">
  <input type="hidden" name="id_iter"         id="id_iter"         value="<?php echo isset($value) ? ucwords($value->id_iter)         : ''?>">

  <?php $is_taken = isset($value->status_iter) && $value->status_iter == '1'; ?>

  <!-- ===== Card: Info Pasien ===== -->
  <div class="pu-wrap">
    <div class="pu-hdr">
      <i class="fa fa-id-card"></i> Informasi Pasien &amp; Resep
      <span class="pi-hdr-badge">ITER</span>
    </div>
    <div class="pu-body" style="padding:0">

      <!-- Section 1: Patient Profile -->
      <div class="pi-profile-strip">
        <div class="pi-avatar">
          <?php echo isset($value->nama_pasien) ? strtoupper(mb_substr(trim($value->nama_pasien), 0, 1)) : '?'?>
        </div>
        <div class="pi-ident">
          <div class="pi-name"><?php echo isset($value->nama_pasien) ? htmlspecialchars($value->nama_pasien) : '—'?></div>
          <div class="pi-tags">
            <span class="pi-tag pi-tag-mr"><i class="fa fa-bookmark-o"></i>&nbsp; No. MR: <?php echo isset($value->no_mr) ? htmlspecialchars($value->no_mr) : '—'?></span>
            <span class="pi-tag pi-tag-sep"><i class="fa fa-id-card-o"></i>&nbsp; No. SEP: <?php echo isset($value->no_sep) ? htmlspecialchars($value->no_sep) : '—'?></span>
          </div>
        </div>
        <div class="pi-ref-box">
          <div class="pi-ref-label"><i class="fa fa-file-text-o"></i> Referensi Resep</div>
          <div class="pi-ref-code"><?php echo isset($value->kode_trans_far) ? htmlspecialchars($value->kode_trans_far) : '—'?></div>
          <div class="pi-ref-noResep">No. Resep: <?php echo isset($value->no_resep) ? htmlspecialchars($value->no_resep) : '—'?></div>
        </div>
      </div>

      <!-- Section 2: Medical Info Chips -->
      <div class="pi-chips-row">
        <div class="pi-chip">
          <div class="pi-chip-icon"><i class="fa fa-calendar"></i></div>
          <div class="pi-chip-content">
            <div class="pi-chip-label">Tanggal Resep</div>
            <div class="pi-chip-val"><?php echo isset($value->tgl_trans) ? $this->tanggal->formatDateTime($value->tgl_trans) : '—'?></div>
          </div>
        </div>
        <div class="pi-chip">
          <div class="pi-chip-icon"><i class="fa fa-stethoscope"></i></div>
          <div class="pi-chip-content">
            <div class="pi-chip-label">Dokter</div>
            <div class="pi-chip-val"><?php echo isset($value->dokter_pengirim) ? htmlspecialchars($value->dokter_pengirim) : '—'?></div>
          </div>
        </div>
        <div class="pi-chip">
          <div class="pi-chip-icon"><i class="fa fa-hospital-o"></i></div>
          <div class="pi-chip-content">
            <div class="pi-chip-label">Poli Asal</div>
            <div class="pi-chip-val"><?php echo $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $value->kode_bagian_asal))?></div>
          </div>
        </div>
      </div>

      <?php if($is_taken): ?>
      <!-- Section: Taken Info Banner -->
      <div class="pi-taken-banner">
        <div class="pi-taken-icon"><i class="fa fa-check"></i></div>
        <div>
          <div class="pi-taken-title"><i class="fa fa-check-circle"></i>&nbsp; Resep Iter Sudah Diambil</div>
          <div class="pi-taken-detail">
            Tanggal Pengambilan:&nbsp;
            <strong><?php echo (isset($value->tgl_pengambilan_resep) && $value->tgl_pengambilan_resep) ? $this->tanggal->formatDateTime($value->tgl_pengambilan_resep) : '—'?></strong>
          </div>
          <div class="pi-taken-note">Pengambilan resep ini sudah diproses dan tidak dapat diajukan kembali.</div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Section 3: Action Buttons -->
      <div class="pi-actions">
        <button type="button" onclick="getMenu('farmasi/Pengambilan_resep_iter')" class="pu-btn-back">
          <i class="fa fa-arrow-left"></i> Kembali
        </button>
        <?php if($is_taken): ?>
        <div class="pi-btn-disabled">
          <i class="fa fa-check-circle"></i> Sudah Diproses
        </div>
        <?php else: ?>
        <button type="button" id="btn_submit_pengambilan_obat" class="pu-btn-submit">
          <i class="fa fa-check-circle"></i> Proses Pengambilan Obat
        </button>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <!-- ===== Card: Tabel Resep ===== -->
  <div class="pu-tbl-wrap">
    <div class="pu-tbl-hdr">
      <i class="fa fa-list-ul"></i> Daftar Obat Resep Iter
    </div>
    <div class="pu-tbl-note" <?php if($is_taken) echo 'style="background:#fff8f0; border-bottom-color:#fcd34d; color:#92400e"'?>>
      <?php if($is_taken): ?>
      <i class="fa fa-lock"></i>
      Data di bawah bersifat <b>hanya baca</b> — resep iter ini sudah diproses dan tidak dapat diubah.
      <?php else: ?>
      <i class="fa fa-info-circle"></i>
      Centang obat yang akan diambil. Jumlah pengambilan <b>tidak boleh melebihi stok depo</b> yang tersedia.
      <?php endif; ?>
    </div>
    <table id="verifikasi-resep-obat" <?php if($is_taken) echo 'class="tbl-locked"'?>>
      <thead>
        <tr>
          <th width="32px">
            <?php if(!$is_taken): ?>
            <label class="pos-rel" style="margin:0">
              <input type="checkbox" class="ace" name="checked_all" value="" onclick="checkAll(this)"/>
              <span class="lbl"></span>
            </label>
            <?php else: ?>
            <i class="fa fa-lock" style="color:#94a3b8; font-size:12px"></i>
            <?php endif; ?>
          </th>
          <th width="28px">No</th>
          <th width="80px">Kode</th>
          <th>Nama Obat</th>
          <th width="90px">Stok Depo</th>
          <th width="90px">Sisa<br>Hutang</th>
          <th width="90px">Jml<br>Diambil</th>
          <th width="55px">#</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $no = 0;
          foreach ($resep as $row) {
            $no++;
            $id          = $row->id_fr_tc_far_detail_log;
            $readonly    = (empty($id)) ? '' : 'readonly';
            $sisa        = ($row->jumlah_obat_23 + $row->jumlah_tebus) - $row->jumlah_mutasi_obat;
            $hidden_save = (empty($id)) ? '' : 'style="display:none"';

            echo '<tr id="row_kd_brg_' . $id . '">';

            // Checkbox
            echo '<td style="text-align:center" id="checked_id_' . $id . '">';
            if ($is_taken) {
              echo '<i class="fa fa-minus" style="color:#cbd5e1"></i>';
            } elseif ($sisa > 0) {
              if ($row->stok_akhir_depo > 0) {
                echo '<label class="pos-rel" style="margin:0">
                  <input type="checkbox" class="ace checkbox_resep" name="selected_id[]" value="' . $id . '" id="checkbox_id_' . $id . '"/>
                  <span class="lbl"></span>
                </label>';
              } else {
                echo '<span class="badge-na">n/a</span>';
              }
            } else {
              echo '<span style="color:#94a3b8">—</span>';
            }
            echo '</td>';

            // No
            echo '<td style="text-align:center;font-weight:600">' . $no . '</td>';

            // Kode
            echo '<td style="font-size:11px"><span id="kode_brg_td_' . $id . '">' . htmlspecialchars($row->kode_brg) . '</span></td>';

            // Nama Obat
            $nama_readonly = $is_taken ? 'readonly style="height:26px;font-size:12px;padding:2px 6px;background:#f8fafc;cursor:default"' : 'style="height:26px;font-size:12px;padding:2px 6px"';
            $nama_onclick  = $is_taken ? '' : 'onclick="searchObat(' . $id . ')"';
            echo '<td><input type="text" class="form-control" ' . $nama_readonly . ' '
               . 'value="' . htmlspecialchars($row->nama_brg) . '" '
               . 'name="nama_brg_' . $id . '" id="inputKeyObat_' . $id . '" '
               . $nama_onclick . '></td>';

            // Stok depo
            $stok_style = ($row->stok_akhir_depo <= 0)
              ? 'color:#c0392b;font-weight:700'
              : 'color:#15803d;font-weight:700';
            echo '<td style="text-align:center" id="td_stok_akhir_depo_' . $id . '">'
               . '<span style="' . $stok_style . '">' . number_format($row->stok_akhir_depo) . '</span>'
               . '</td>';

            // Sisa hutang
            echo '<td style="text-align:center" id="sisa_hutang_' . $id . '">';
            echo ($sisa == 0)
              ? '<span class="badge-lunas">Lunas</span>'
              : '<span style="font-weight:600">' . number_format($sisa) . '</span>';
            echo '</td>';

            // Jumlah diambil
            $qty_readonly = ($is_taken || !empty($readonly)) ? 'readonly' : '';
            echo '<td style="text-align:center">';
            echo '<input class="qty-input" type="number" '
               . 'name="jumlah_' . $id . '" '
               . 'id="jumlah_' . $id . '" '
               . 'max="' . $sisa . '" '
               . 'value="' . $sisa . '" '
               . $qty_readonly . ' '
               . ($is_taken ? '' : 'onkeypress="pressEnter(' . $id . ')" onchange="saveRow(' . $id . ')"')
               . '>';
            echo '</td>';

            // Aksi + hidden inputs
            echo '<td style="text-align:center">';
            echo '<input type="hidden" name="id_fr_tc_far_detail_log[]" value="' . $id . '">';
            echo '<input type="hidden" name="kode_brg_' . $id . '" id="kode_brg_' . $id . '" value="' . htmlspecialchars($row->kode_brg) . '">';
            echo '<input type="hidden" name="stok_brg_' . $id . '" id="stok_brg_' . $id . '" value="' . $row->stok_akhir_depo . '">';
            echo '<input type="hidden" name="kd_tr_resep_' . $id . '" value="' . htmlspecialchars($row->kd_tr_resep) . '">';
            if ($is_taken) {
              echo '<i class="fa fa-lock" style="color:#cbd5e1; font-size:12px" title="Tidak dapat diubah"></i>';
            } elseif ($sisa > 0) {
              echo '<button type="button" class="pu-btn-save" id="btn_submit_' . $id . '" onclick="saveRow(\'' . $id . '\')" ' . $hidden_save . '><i class="fa fa-check"></i></button> ';
              echo '<button type="button" class="pu-btn-edit" id="btn_edit_'   . $id . '" onclick="click_edit(\'' . $id . '\')"><i class="fa fa-pencil"></i></button>';
            } else {
              echo '<span style="color:#94a3b8">—</span>';
            }
            echo '</td>';

            echo '</tr>';
          }
        ?>
      </tbody>
    </table>
  </div>

  <!-- ===== Legend ===== -->
  <div class="pu-legend">
    <div class="leg-title"><i class="fa fa-info-circle"></i> Keterangan</div>
    <div>
      <span class="badge-na">n/a</span> &mdash; (Not Available) Stok depo kosong, obat tidak dapat dipilih untuk dilanjutkan transaksi.
    </div>
    <div style="margin-top: 3px">
      Jumlah obat yang akan diambil <b>tidak dapat melebihi stok depo</b> yang tersedia saat ini.
    </div>
  </div>

</form>

</div><!-- /.col -->
</div><!-- /.row -->


<script>
$(function(){
  $('.format_number').number(true, 0);
});

function checkAll(elm) {
  var checked = $(elm).prop('checked');
  $('.checkbox_resep').each(function(){
    $(this).prop('checked', checked);
  });
}

function pressEnter(kode_brg) {
  var keycode = (event.keyCode ? event.keyCode : event.which);
  if (keycode == 13) {
    event.preventDefault();
    saveRow(kode_brg);
    return false;
  }
}

function searchObat(num) {
  $('#inputKeyObat_' + num).typeahead({
    source: function(query, result) {
      $.ajax({
        url: 'templates/references/getObatByBagianAutoComplete',
        data: { keyword: query, bag: '060101' },
        dataType: 'json',
        type: 'POST',
        success: function(response) {
          result($.map(response, function(item) { return item; }));
        }
      });
    },
    afterSelect: function(item) {
      var val_item   = item.split(':')[0];
      var label_item = item.split(':')[1];
      $('#inputKeyObat_' + num).val(label_item);
      $('#kode_brg_' + num).val(val_item);
      $('#kode_brg_td_' + num).text(val_item);
      getDetailObatByKodeBrg(val_item, '060101', num);
    }
  });
}

function getDetailObatByKodeBrg(kode_brg, kode_bag, num) {
  $.getJSON(
    "<?php echo site_url('templates/references/getDetailObat') ?>?kode=" + kode_brg
    + "&kode_kelompok=" + $('#kode_kelompok').val()
    + "&kode_perusahaan=" + $('#kode_perusahaan').val()
    + "&bag=" + kode_bag + "&type=html&type_layan=Rajal",
    '',
    function(response) {
      $('#stok_brg_' + num).val(response.sisa_stok);
      $('#td_stok_akhir_depo_' + num).text(response.sisa_stok);
      $('#jumlah_' + num).attr('max', response.sisa_stok);
      if (response.sisa_stok <= 0) {
        $('#checked_id_' + num).html('<span class="badge-na">n/a</span>');
      } else {
        $('#checked_id_' + num).html(
          '<label class="pos-rel" style="margin:0">'
          + '<input type="checkbox" class="ace checkbox_resep" name="selected_id[]" value="' + num + '" id="checkbox_id_' + num + '"/>'
          + '<span class="lbl"></span></label>'
        );
        if (response.sisa_stok < parseInt($('#jumlah_' + num).val())) {
          $('#jumlah_' + num).val(response.sisa_stok);
        } else {
          $('#jumlah_' + num).val($('#sisa_hutang_' + num).text());
        }
      }
    }
  );
}

$('#btn_submit_pengambilan_obat').click(function(event) {
  event.preventDefault();
  var searchIDs = $('#verifikasi-resep-obat tbody input:checkbox:checked').map(function(){
    return $(this).val();
  }).toArray();
  if (searchIDs.length === 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Perhatian',
      html: 'Tidak ada obat yang dipilih untuk diproses.',
      confirmButtonColor: '#1a4f8a'
    });
    return false;
  }
  submit_form(searchIDs);
});

function submit_form(arrDataChecklist) {
  $.ajax({
    url: $('#form_proses_resep').attr('action'),
    type: 'post',
    data: $('#form_proses_resep').serialize(),
    dataType: 'json',
    beforeSend: function() { achtungShowLoader(); },
    complete: function(xhr) {
      var jsonResponse = JSON.parse(xhr.responseText);
      $('#page-area-content').load(
        'farmasi/Process_entry_resep/preview_entry/' + jsonResponse.kode_trans_far + '?flag=ITR&status_lunas=0'
      );
      achtungHideLoader();
    }
  });
}

function click_edit(num) {
  $('#row_kd_brg_' + num + ' input[type=number]').attr('readonly', false);
  $('#btn_submit_' + num).show();
  $('#btn_edit_' + num).hide();
}

function saveRow(num) {
  $('#row_kd_brg_' + num + ' input[type=number]').attr('readonly', true);
  var entry = parseInt($('#jumlah_' + num).val()) || 0;
  var stok  = parseInt($('#stok_brg_' + num).val()) || 0;
  if (entry > stok) {
    $('#jumlah_' + num).val(stok < 0 ? 0 : stok);
  }
  $('#btn_submit_' + num).hide();
  $('#btn_edit_' + num).show();
  return false;
}
</script>
