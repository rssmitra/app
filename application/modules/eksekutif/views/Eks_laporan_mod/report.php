<style>
  /* Report styles */
  .mod-report { max-width:900px; margin:0 auto; font-family:'Segoe UI',Arial,sans-serif; font-size:13px; color:#1a1a1a; }
  .mod-report-header { text-align:center; margin-bottom:20px; }
  .mod-report-header h2 { font-size:16px; font-weight:700; margin:0 0 2px; }
  .mod-report-header p  { margin:2px 0; font-size:13px; }
  .mod-section-title {
    background: linear-gradient(135deg,#0369a1,#0ea5e9);
    color:#fff; padding:6px 14px; font-size:13px; font-weight:700;
    border-radius:4px; margin:16px 0 8px;
  }
  .mod-kv { display:flex; flex-wrap:wrap; gap:4px 0; }
  .mod-kv-item { width:50%; display:flex; padding:3px 0; }
  .mod-kv-item.full { width:100%; }
  .mod-kv-label { color:#555; min-width:200px; font-size:12.5px; }
  .mod-kv-val { font-weight:600; font-size:12.5px; }
  .mod-badge { display:inline-block; border-radius:10px; padding:1px 10px; font-size:11px; font-weight:700; }
  .mod-badge-bpjs { background:#dbeafe; color:#1d4ed8; }
  .mod-badge-umum { background:#fef9c3; color:#854d0e; }
  .mod-badge-asuransi { background:#d1fae5; color:#065f46; }
  .mod-badge-naker { background:#ede9fe; color:#4c1d95; }
  .mod-badge-rssm { background:#fee2e2; color:#991b1b; }
  .mod-table { width:100%; border-collapse:collapse; font-size:12.5px; margin-top:6px; }
  .mod-table th { background:#f1f5f9; border:1px solid #d1d5db; padding:5px 8px; text-align:left; font-size:12px; }
  .mod-table td { border:1px solid #e5e7eb; padding:5px 8px; vertical-align:top; }
  .mod-table tr:nth-child(even) td { background:#f8fafc; }
  .tt-grid { display:flex; flex-wrap:wrap; gap:8px; margin:6px 0; }
  .tt-card { background:#f1f5f9; border:1px solid #d1d5db; border-radius:6px; padding:8px 14px; text-align:center; min-width:90px; }
  .tt-card .tt-num { font-size:22px; font-weight:700; color:#0369a1; }
  .tt-card .tt-label { font-size:11px; color:#64748b; }
  .tt-total { background:#0369a1; color:#fff; }
  .tt-total .tt-num { color:#fff; }
  .tt-total .tt-label { color:#bae6fd; }
  .mod-closing { text-align:center; margin-top:24px; font-style:italic; color:#555; font-size:13px; padding:12px; border-top:1px solid #e2e8f0; }
  .action-bar { margin-bottom:16px; display:flex; gap:8px; }
  @media print {
    .action-bar, .page-header { display:none !important; }
    .mod-report { max-width:100%; }
  }
  /* ── Foto block ─────────────────────────────────────────── */
  .rpt-foto-block {
    margin: 10px 0 4px; padding: 8px 10px;
    background: #f8fafc; border: 1px solid #e2e8f0;
    border-left: 3px solid #0ea5e9; border-radius: 0 5px 5px 0;
  }
  .rpt-foto-label {
    font-size: 11px; font-weight: 700; color: #0369a1;
    text-transform: uppercase; letter-spacing: .4px; margin-bottom: 7px;
  }
  .rpt-foto-grid { display: flex; flex-wrap: wrap; gap: 8px; }
  .rpt-foto-item {
    width: 110px; border: 1px solid #e2e8f0; border-radius: 5px;
    overflow: hidden; background: #fff; text-align: center;
  }
  .rpt-foto-img {
    width: 110px; height: 88px; object-fit: cover; display: block; cursor: zoom-in;
    transition: opacity .15s;
  }
  .rpt-foto-img:hover { opacity: .85; }
  .rpt-foto-ket { font-size: 9.5px; color: #374151; padding: 3px 4px; line-height: 1.3; word-break: break-word; }
  /* Lightbox */
  #rpt-lb-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.85); z-index: 99999;
    align-items: center; justify-content: center; cursor: zoom-out;
  }
  #rpt-lb-overlay.active { display: flex; }
  #rpt-lb-img { max-width: 90vw; max-height: 90vh; border-radius: 6px; box-shadow: 0 8px 32px rgba(0,0,0,.5); }
  @media print {
    .rpt-foto-block { border: 1px solid #ccc; }
    .rpt-foto-img { cursor: default; }
    #rpt-lb-overlay { display: none !important; }
  }
</style>

<div class="page-header">
  <h1>
    <?php echo $title ?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs ?> &raquo; Detail Laporan MOD
    </small>
  </h1>
</div>

<?php
  $v = function($obj, $key, $default='-') {
    if (!$obj) return $default;
    $val = isset($obj->$key) ? $obj->$key : null;
    return ($val !== null && $val !== '') ? $val : $default;
  };
  $n = function($obj, $key) { return $obj ? intval(isset($obj->$key) ? $obj->$key : 0) : 0; };

  // group kamar operasi by shift
  $ok = ['pagi'=>[],'sore'=>[],'malam'=>[]];
  if (!empty($kamar_op)) foreach ($kamar_op as $r) $ok[$r->shift][] = $r;

  // icu/picu/nicu detail
  $icu_list = []; $picu_list = []; $nicu_list = [];
  if (!empty($icu_detail)) foreach ($icu_detail as $r) {
    if ($r->unit=='ICU') $icu_list[] = $r;
    elseif ($r->unit=='PICU') $picu_list[] = $r;
    else $nicu_list[] = $r;
  }

  $days_id = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
              'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
  $tgl_str  = $laporan->tanggal;
  $day_key  = date('l', strtotime($tgl_str));
  $day_name = isset($days_id[$day_key]) ? $days_id[$day_key] : '';
  $bulan_arr = array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
  $tgl_fmt  = $day_name . ', ' . date('j', strtotime($tgl_str)) . ' ' .
              $bulan_arr[intval(date('m', strtotime($tgl_str)))] . ' ' .
              date('Y', strtotime($tgl_str));

  $fotos     = isset($fotos) ? $fotos : [];
  $foto_base = base_url('uploaded/mod_laporan/');

  function rpt_foto_block_page($sec, $fotos, $foto_base) {
    if (empty($fotos[$sec])) return;
    ?>
    <div class="rpt-foto-block">
      <div class="rpt-foto-label"><i class="fa fa-camera" style="margin-right:4px"></i>Foto Kondisi Lapangan</div>
      <div class="rpt-foto-grid">
        <?php foreach ($fotos[$sec] as $f): ?>
        <div class="rpt-foto-item">
          <img src="<?php echo $foto_base . htmlspecialchars($f->foto_path) ?>"
               class="rpt-foto-img" onclick="rptOpenLb(this.src)"
               title="<?php echo htmlspecialchars($f->keterangan) ?>">
          <?php if (!empty($f->keterangan)): ?>
          <div class="rpt-foto-ket"><?php echo htmlspecialchars($f->keterangan) ?></div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php
  }
?>

<div class="action-bar">
  <a href="<?php echo base_url()?>eksekutif/Eks_laporan_mod" class="btn btn-sm btn-default">
    <i class="fa fa-arrow-left"></i> Kembali ke Daftar
  </a>
  <a href="<?php echo base_url()?>eksekutif/Eks_laporan_mod/form/<?php echo $id ?>" class="btn btn-sm btn-warning">
    <i class="fa fa-pencil"></i> Edit
  </a>
  <button onclick="window.print()" class="btn btn-sm btn-info">
    <i class="fa fa-print"></i> Cetak
  </button>
</div>

<div class="mod-report">

  <!-- HEADER -->
  <div class="mod-report-header">
    <p>Assalamualaikum... Mohon izin mengirimkan Laporan MOD</p>
    <h2>LAPORAN PASIEN</h2>
    <p><strong>Hari / Tanggal :</strong> <?php echo $tgl_fmt ?></p>
    <p><strong>Nama MOD :</strong> <?php echo strtoupper(htmlspecialchars($laporan->nama_mod)) ?></p>
    <p><strong>Shift MOD :</strong> <?php echo htmlspecialchars($laporan->shift_mod) ?></p>
  </div>

  <!-- 1. IGD -->
  <div class="mod-section-title">1. IGD (Instalasi Gawat Darurat)</div>
  <div class="mod-kv">
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jumlah Pasien</span>
      <span class="mod-kv-val">
        <?php echo $n($igd,'jml_pasien') ?>
        (<span class="mod-badge mod-badge-bpjs">BPJS: <?php echo $n($igd,'bpjs') ?></span>
         <span class="mod-badge mod-badge-umum">Umum: <?php echo $n($igd,'umum') ?></span>
         <span class="mod-badge mod-badge-asuransi">Asuransi: <?php echo $n($igd,'asuransi') ?></span>
         <span class="mod-badge mod-badge-naker">Naker: <?php echo $n($igd,'naker') ?></span>
         <span class="mod-badge mod-badge-rssm">RSSM: <?php echo $n($igd,'rssm') ?></span>)
      </span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Ranap / DOA / DOE</span>
      <span class="mod-kv-val"><?php echo $n($igd,'ranap') ?> / <?php echo $n($igd,'doa') ?> / <?php echo $n($igd,'doe') ?></span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jumlah Rujukan Ditolak</span>
      <span class="mod-kv-val"><?php echo $n($igd,'jml_rujukan_ditolak') ?></span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jml Pasien Menolak Ranap</span>
      <span class="mod-kv-val"><?php echo $n($igd,'jml_menolak_ranap') ?></span>
    </div>
    <?php if ($igd && $igd->alasan_ditolak): ?>
    <div class="mod-kv-item full">
      <span class="mod-kv-label">Alasan Rujukan Ditolak</span>
      <span class="mod-kv-val"><?php echo nl2br(htmlspecialchars($igd->alasan_ditolak)) ?></span>
    </div>
    <?php endif; ?>
    <?php if ($igd && $igd->alasan_menolak_ranap): ?>
    <div class="mod-kv-item full">
      <span class="mod-kv-label">Alasan Menolak Ranap</span>
      <span class="mod-kv-val"><?php echo nl2br(htmlspecialchars($igd->alasan_menolak_ranap)) ?></span>
    </div>
    <?php endif; ?>
  </div>
  <?php rpt_foto_block_page('igd', $fotos, $foto_base); ?>

  <!-- 2. RAWAT JALAN -->
  <div class="mod-section-title">2. Rawat Jalan</div>
  <div class="mod-kv">
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jumlah Pasien</span>
      <span class="mod-kv-val">
        <?php echo $n($rawat_jalan,'jml_pasien') ?>
        (<span class="mod-badge mod-badge-bpjs">BPJS: <?php echo $n($rawat_jalan,'bpjs') ?></span>
         <span class="mod-badge mod-badge-umum">Umum: <?php echo $n($rawat_jalan,'umum') ?></span>
         <span class="mod-badge mod-badge-asuransi">Asuransi: <?php echo $n($rawat_jalan,'asuransi') ?></span>
         <span class="mod-badge mod-badge-naker">Naker: <?php echo $n($rawat_jalan,'naker') ?></span>
         <span class="mod-badge mod-badge-rssm">RSSM: <?php echo $n($rawat_jalan,'rssm') ?></span>)
      </span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Ranap</span>
      <span class="mod-kv-val"><?php echo $n($rawat_jalan,'ranap') ?></span>
    </div>
  </div>
  <?php rpt_foto_block_page('rawat_jalan', $fotos, $foto_base); ?>

  <!-- 3. HEMODIALISA -->
  <div class="mod-section-title">3. Hemodialisa</div>
  <div class="mod-kv">
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jumlah Pasien</span>
      <span class="mod-kv-val">
        <?php echo $n($hemodialisa,'jml_pasien') ?>
        (<span class="mod-badge mod-badge-bpjs">BPJS: <?php echo $n($hemodialisa,'bpjs') ?></span>
         <span class="mod-badge mod-badge-umum">Umum: <?php echo $n($hemodialisa,'umum') ?></span>
         <span class="mod-badge mod-badge-asuransi">Asuransi: <?php echo $n($hemodialisa,'asuransi') ?></span>)
      </span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Pasien HD Ranap</span>
      <span class="mod-kv-val"><?php echo $n($hemodialisa,'hd_ranap') ?></span>
    </div>
  </div>
  <?php rpt_foto_block_page('hemodialisa', $fotos, $foto_base); ?>

  <!-- 4. RAWAT INAP -->
  <div class="mod-section-title">4. Rawat Inap</div>
  <div class="mod-kv">
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jumlah Pasien</span>
      <span class="mod-kv-val">
        <?php echo $n($rawat_inap,'jml_pasien') ?>
        (<span class="mod-badge mod-badge-bpjs">BPJS: <?php echo $n($rawat_inap,'bpjs') ?></span>
         <span class="mod-badge mod-badge-umum">Umum: <?php echo $n($rawat_inap,'umum') ?></span>
         <span class="mod-badge mod-badge-asuransi">Asuransi: <?php echo $n($rawat_inap,'asuransi') ?></span>
         <span class="mod-badge mod-badge-naker">Naker: <?php echo $n($rawat_inap,'naker') ?></span>)
      </span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Rencana Operasi</span>
      <span class="mod-kv-val"><?php echo $n($rawat_inap,'rencana_operasi') ?></span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Pasien Pengawasan Khusus</span>
      <span class="mod-kv-val"><?php echo $n($rawat_inap,'jml_pengawasan') ?></span>
    </div>
  </div>

  <?php if (!empty($ranap_detail)): ?>
  <table class="mod-table">
    <thead><tr><th>#</th><th>Nama / Umur</th><th>Jaminan</th><th>Hari Rawat ke-</th><th>Diagnosa</th><th>DPJP</th></tr></thead>
    <tbody>
    <?php foreach ($ranap_detail as $i => $r): ?>
    <tr>
      <td><?php echo $i+1 ?></td>
      <td><?php echo htmlspecialchars($r->nama_umur) ?></td>
      <td><?php echo htmlspecialchars($r->jaminan) ?></td>
      <td><?php echo htmlspecialchars($r->hari_rawat) ?></td>
      <td><?php echo htmlspecialchars($r->diagnosa) ?></td>
      <td><?php echo htmlspecialchars($r->dpjp) ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>

  <!-- Ketersediaan TT -->
  <p style="margin:10px 0 4px;font-weight:600;font-size:12.5px">Ketersediaan Tempat Tidur Rawat Inap</p>
  <?php
    $tt_total = $n($rawat_inap,'tt_vvip') + $n($rawat_inap,'tt_vip1') + $n($rawat_inap,'tt_vip2')
              + $n($rawat_inap,'tt_kelas1') + $n($rawat_inap,'tt_kelas2') + $n($rawat_inap,'tt_kelas3');
  ?>
  <div class="tt-grid">
    <div class="tt-card"><div class="tt-num"><?php echo $n($rawat_inap,'tt_vvip') ?></div><div class="tt-label">VVIP (Deluxe)</div></div>
    <div class="tt-card"><div class="tt-num"><?php echo $n($rawat_inap,'tt_vip1') ?></div><div class="tt-label">VIP 1</div></div>
    <div class="tt-card"><div class="tt-num"><?php echo $n($rawat_inap,'tt_vip2') ?></div><div class="tt-label">VIP 2</div></div>
    <div class="tt-card"><div class="tt-num"><?php echo $n($rawat_inap,'tt_kelas1') ?></div><div class="tt-label">Kelas 1</div></div>
    <div class="tt-card"><div class="tt-num"><?php echo $n($rawat_inap,'tt_kelas2') ?></div><div class="tt-label">Kelas 2</div></div>
    <div class="tt-card"><div class="tt-num"><?php echo $n($rawat_inap,'tt_kelas3') ?></div><div class="tt-label">Kelas 3</div></div>
    <div class="tt-card tt-total"><div class="tt-num"><?php echo $tt_total ?></div><div class="tt-label">TOTAL TT</div></div>
  </div>
  <?php rpt_foto_block_page('rawat_inap', $fotos, $foto_base); ?>

  <!-- 5. INTENSIVE UNIT -->
  <div class="mod-section-title">5. Intensive Unit</div>
  <?php foreach (['ICU'=>'icu','PICU'=>'picu','NICU'=>'nicu'] as $unit_lbl => $pfx): ?>
  <div class="mod-kv">
    <div class="mod-kv-item full">
      <span class="mod-kv-label" style="font-weight:700"><?php echo $unit_lbl ?></span>
      <span class="mod-kv-val">
        Total: <?php echo $n($intensive,$pfx.'_total') ?>
        (<span class="mod-badge mod-badge-bpjs">BPJS: <?php echo $n($intensive,$pfx.'_bpjs') ?></span>
         <span class="mod-badge mod-badge-umum">Umum: <?php echo $n($intensive,$pfx.'_umum') ?></span>
         <span class="mod-badge mod-badge-asuransi">Asuransi: <?php echo $n($intensive,$pfx.'_asuransi') ?></span>)
      </span>
    </div>
  </div>
  <?php endforeach; ?>

  <?php foreach (['ICU'=>$icu_list,'PICU'=>$picu_list,'NICU'=>$nicu_list] as $unit_lbl => $detail_list): ?>
  <?php if (!empty($detail_list)): ?>
  <p style="margin:8px 0 4px;font-weight:600;font-size:12.5px">Data Pasien <?php echo $unit_lbl ?> = <?php echo count($detail_list) ?></p>
  <table class="mod-table">
    <thead><tr><th>#</th><th>Nama / Umur</th><th>Jaminan</th><th>Hari Rawat ke-</th><th>Diagnosa</th><th>DPJP</th></tr></thead>
    <tbody>
    <?php foreach ($detail_list as $i => $r): ?>
    <tr>
      <td><?php echo $i+1 ?></td>
      <td><?php echo htmlspecialchars($r->nama_umur) ?></td>
      <td><?php echo htmlspecialchars($r->jaminan) ?></td>
      <td><?php echo htmlspecialchars($r->hari_rawat) ?></td>
      <td><?php echo htmlspecialchars($r->diagnosa) ?></td>
      <td><?php echo htmlspecialchars($r->dpjp) ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
  <?php endforeach; ?>
  <?php rpt_foto_block_page('intensive', $fotos, $foto_base); ?>

  <!-- 6. VK -->
  <div class="mod-section-title">6. Ruang Bersalin (VK)</div>
  <div class="mod-kv">
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jumlah Pasien</span>
      <span class="mod-kv-val">
        <?php echo $n($vk,'jml_pasien') ?>
        (<span class="mod-badge mod-badge-bpjs">BPJS: <?php echo $n($vk,'bpjs') ?></span>
         <span class="mod-badge mod-badge-umum">Umum: <?php echo $n($vk,'umum') ?></span>
         <span class="mod-badge mod-badge-asuransi">Asuransi: <?php echo $n($vk,'asuransi') ?></span>)
      </span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jml Pasien Rujukan</span>
      <span class="mod-kv-val"><?php echo $n($vk,'jml_rujukan') ?></span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Rujukan Ditolak</span>
      <span class="mod-kv-val"><?php echo $n($vk,'jml_rujukan_ditolak') ?></span>
    </div>
  </div>
  <?php if (!empty($vk_detail)): ?>
  <p style="margin:8px 0 4px;font-weight:600;font-size:12.5px">Data Pasien VK = <?php echo count($vk_detail) ?></p>
  <table class="mod-table">
    <thead><tr><th>#</th><th>Nama / Umur</th><th>Jaminan</th><th>Diagnosa</th><th>DPJP</th></tr></thead>
    <tbody>
    <?php foreach ($vk_detail as $i => $r): ?>
    <tr>
      <td><?php echo $i+1 ?></td>
      <td><?php echo htmlspecialchars($r->nama_umur) ?></td>
      <td><?php echo htmlspecialchars($r->jaminan) ?></td>
      <td><?php echo htmlspecialchars($r->diagnosa) ?></td>
      <td><?php echo htmlspecialchars($r->dpjp) ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
  <?php rpt_foto_block_page('vk', $fotos, $foto_base); ?>

  <!-- 7. PERINA -->
  <div class="mod-section-title">7. Perina</div>
  <div class="mod-kv">
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jumlah Pasien</span>
      <span class="mod-kv-val">
        <?php echo $n($perina,'jml_pasien') ?>
        (<span class="mod-badge mod-badge-bpjs">BPJS: <?php echo $n($perina,'bpjs') ?></span>
         <span class="mod-badge mod-badge-umum">Umum: <?php echo $n($perina,'umum') ?></span>
         <span class="mod-badge mod-badge-asuransi">Asuransi: <?php echo $n($perina,'asuransi') ?></span>)
      </span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jumlah Bayi Sakit</span>
      <span class="mod-kv-val"><?php echo $n($perina,'jml_bayi_sakit') ?></span>
    </div>
  </div>
  <?php if (!empty($perina_detail)): ?>
  <p style="margin:8px 0 4px;font-weight:600;font-size:12.5px">Data Pasien Perina (Bayi Sakit) = <?php echo count($perina_detail) ?></p>
  <table class="mod-table">
    <thead><tr><th>#</th><th>Nama Bayi / Umur</th><th>Jaminan</th><th>Diagnosa</th><th>DPJP</th></tr></thead>
    <tbody>
    <?php foreach ($perina_detail as $i => $r): ?>
    <tr>
      <td><?php echo $i+1 ?></td>
      <td><?php echo htmlspecialchars($r->nama_umur) ?></td>
      <td><?php echo htmlspecialchars($r->jaminan) ?></td>
      <td><?php echo htmlspecialchars($r->diagnosa) ?></td>
      <td><?php echo htmlspecialchars($r->dpjp) ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
  <?php rpt_foto_block_page('perina', $fotos, $foto_base); ?>

  <!-- 8. KAMAR OPERASI -->
  <div class="mod-section-title">8. Kamar Operasi</div>
  <?php foreach (['pagi'=>'Pagi','sore'=>'Sore','malam'=>'Malam'] as $shift => $shift_lbl): ?>
  <p style="margin:8px 0 4px;font-weight:600;font-size:12.5px">
    Shift <?php echo $shift_lbl ?> = <?php echo count($ok[$shift]) ?>
  </p>
  <?php if (!empty($ok[$shift])): ?>
  <table class="mod-table">
    <thead><tr><th>#</th><th>Nama / Umur</th><th>Jaminan</th><th>Diagnosa / DPJP</th><th>Jam</th></tr></thead>
    <tbody>
    <?php foreach ($ok[$shift] as $i => $r): ?>
    <tr>
      <td><?php echo $i+1 ?></td>
      <td><?php echo htmlspecialchars($r->nama_umur) ?></td>
      <td><?php echo htmlspecialchars($r->jaminan) ?></td>
      <td><?php echo htmlspecialchars($r->diagnosa) ?><?php if($r->dpjp): ?> / <?php echo htmlspecialchars($r->dpjp) ?><?php endif; ?></td>
      <td><?php echo htmlspecialchars($r->jam) ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
  <p style="color:#888;font-size:12px;padding-left:4px">Tidak ada tindakan operasi</p>
  <?php endif; ?>
  <?php endforeach; ?>
  <?php rpt_foto_block_page('kamar_op', $fotos, $foto_base); ?>

  <!-- 9. LABORATORIUM -->
  <div class="mod-section-title">9. Laboratorium</div>
  <div class="mod-kv">
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jumlah Pasien</span>
      <span class="mod-kv-val">
        <?php echo $n($lab,'jml_pasien') ?>
        (<span class="mod-badge mod-badge-bpjs">BPJS: <?php echo $n($lab,'bpjs') ?></span>
         <span class="mod-badge mod-badge-umum">Umum: <?php echo $n($lab,'umum') ?></span>
         <span class="mod-badge mod-badge-asuransi">Asuransi: <?php echo $n($lab,'asuransi') ?></span>
         <span class="mod-badge mod-badge-naker">Naker: <?php echo $n($lab,'naker') ?></span>
         <span class="mod-badge mod-badge-rssm">RSSM: <?php echo $n($lab,'rssm') ?></span>)
      </span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Pemeriksaan Patologi Klinis</span>
      <span class="mod-kv-val"><?php echo $n($lab,'patologi_klinis') ?></span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Pemeriksaan Patologi Anatomi</span>
      <span class="mod-kv-val"><?php echo $n($lab,'patologi_anatomi') ?></span>
    </div>
  </div>
  <?php rpt_foto_block_page('lab', $fotos, $foto_base); ?>

  <!-- 10. FARMASI -->
  <div class="mod-section-title">10. Farmasi</div>
  <div class="mod-kv">
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jumlah Resep</span>
      <span class="mod-kv-val">
        <?php echo $n($farmasi,'jml_resep') ?>
        (<span class="mod-badge mod-badge-bpjs">BPJS: <?php echo $n($farmasi,'bpjs') ?></span>
         <span class="mod-badge mod-badge-umum">Umum: <?php echo $n($farmasi,'umum') ?></span>
         <span class="mod-badge mod-badge-asuransi">Asuransi: <?php echo $n($farmasi,'asuransi') ?></span>
         <span class="mod-badge mod-badge-naker">Naker: <?php echo $n($farmasi,'naker') ?></span>
         <span class="mod-badge mod-badge-rssm">RSSM: <?php echo $n($farmasi,'rssm') ?></span>)
      </span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Obat Bebas</span>
      <span class="mod-kv-val"><?php echo $n($farmasi,'obat_bebas') ?></span>
    </div>
  </div>
  <?php rpt_foto_block_page('farmasi', $fotos, $foto_base); ?>

  <!-- 11. RADIOLOGI -->
  <div class="mod-section-title">11. Radiologi</div>
  <div class="mod-kv">
    <div class="mod-kv-item">
      <span class="mod-kv-label">Jumlah Pasien</span>
      <span class="mod-kv-val">
        <?php echo $n($radiologi,'jml_pasien') ?>
        (<span class="mod-badge mod-badge-bpjs">BPJS: <?php echo $n($radiologi,'bpjs') ?></span>
         <span class="mod-badge mod-badge-umum">Umum: <?php echo $n($radiologi,'umum') ?></span>
         <span class="mod-badge mod-badge-asuransi">Asuransi: <?php echo $n($radiologi,'asuransi') ?></span>
         <span class="mod-badge mod-badge-naker">Naker: <?php echo $n($radiologi,'naker') ?></span>)
      </span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">X-Ray</span>
      <span class="mod-kv-val"><?php echo $n($radiologi,'xray') ?></span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">USG</span>
      <span class="mod-kv-val"><?php echo $n($radiologi,'usg') ?></span>
    </div>
  </div>
  <?php rpt_foto_block_page('radiologi', $fotos, $foto_base); ?>

  <!-- 12. DPJP -->
  <div class="mod-section-title">12. DPJP Tidak Visite 24 Jam</div>
  <p style="padding:4px 2px;font-size:12.5px"><?php echo nl2br(htmlspecialchars($v($lainnya,'dpjp_visite','tidak ada'))) ?></p>
  <?php rpt_foto_block_page('dpjp', $fotos, $foto_base); ?>

  <!-- 13. AMBULANS -->
  <div class="mod-section-title">13. Utilisasi Ambulans</div>
  <div class="mod-kv">
    <div class="mod-kv-item full">
      <span class="mod-kv-label">Pagi</span>
      <span class="mod-kv-val"><?php echo nl2br(htmlspecialchars($v($lainnya,'ambulans_pagi','tidak ada'))) ?></span>
    </div>
    <div class="mod-kv-item full">
      <span class="mod-kv-label">Sore</span>
      <span class="mod-kv-val"><?php echo nl2br(htmlspecialchars($v($lainnya,'ambulans_sore','tidak ada'))) ?></span>
    </div>
    <div class="mod-kv-item full">
      <span class="mod-kv-label">Malam</span>
      <span class="mod-kv-val"><?php echo nl2br(htmlspecialchars($v($lainnya,'ambulans_malam','tidak ada'))) ?></span>
    </div>
  </div>
  <?php rpt_foto_block_page('ambulans', $fotos, $foto_base); ?>

  <!-- 14. KENDALA -->
  <div class="mod-section-title">14. Kendala / Insiden / Komplain Pelayanan</div>
  <div class="mod-kv">
    <div class="mod-kv-item full">
      <span class="mod-kv-label">Kendala / Insiden / Komplain</span>
      <span class="mod-kv-val"><?php echo nl2br(htmlspecialchars($v($lainnya,'kendala','tidak ada'))) ?></span>
    </div>
    <div class="mod-kv-item full">
      <span class="mod-kv-label">Tindak Lanjut yang Dilakukan</span>
      <span class="mod-kv-val"><?php echo nl2br(htmlspecialchars($v($lainnya,'kendala_tindak','-'))) ?></span>
    </div>
  </div>
  <?php rpt_foto_block_page('kendala', $fotos, $foto_base); ?>

  <!-- 15. SARPRAS -->
  <div class="mod-section-title">15. Kerusakan Sarana &amp; Prasarana</div>
  <div class="mod-kv">
    <div class="mod-kv-item full">
      <span class="mod-kv-label">Kerusakan Sarpras</span>
      <span class="mod-kv-val"><?php echo nl2br(htmlspecialchars($v($lainnya,'sarpras','tidak ada'))) ?></span>
    </div>
    <div class="mod-kv-item full">
      <span class="mod-kv-label">Tindak Lanjut yang Dilakukan</span>
      <span class="mod-kv-val"><?php echo nl2br(htmlspecialchars($v($lainnya,'sarpras_tindak','-'))) ?></span>
    </div>
  </div>
  <?php rpt_foto_block_page('sarpras', $fotos, $foto_base); ?>

  <!-- 16. KEBERSIHAN -->
  <div class="mod-section-title">16. Kebersihan</div>
  <div class="mod-kv">
    <div class="mod-kv-item">
      <span class="mod-kv-label">Area Tunggu</span>
      <span class="mod-kv-val"><?php echo htmlspecialchars($v($lainnya,'kebersihan_tunggu','-')) ?></span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Kran / Toilet (Rajal &amp; Ranap)</span>
      <span class="mod-kv-val"><?php echo htmlspecialchars($v($lainnya,'kebersihan_toilet','-')) ?></span>
    </div>
    <div class="mod-kv-item">
      <span class="mod-kv-label">Area Lobby</span>
      <span class="mod-kv-val"><?php echo htmlspecialchars($v($lainnya,'kebersihan_lobby','-')) ?></span>
    </div>
  </div>
  <?php rpt_foto_block_page('kebersihan', $fotos, $foto_base); ?>

  <!-- 17. KETERANGAN LAINNYA -->
  <?php
    $keterangan_lain = $v($lainnya, 'keterangan_lain', '');
    $has_foto_lain   = !empty($fotos['keterangan_lain']);
    if ($keterangan_lain !== '-' || $has_foto_lain):
  ?>
  <div class="mod-section-title">17. Keterangan Lainnya</div>
  <?php if ($keterangan_lain !== '-'): ?>
  <p style="padding:4px 2px;font-size:12.5px"><?php echo nl2br(htmlspecialchars($keterangan_lain)) ?></p>
  <?php endif; ?>
  <?php rpt_foto_block_page('keterangan_lain', $fotos, $foto_base); ?>
  <?php endif; ?>

  <div class="mod-closing">
    Demikian Laporan MOD. Terimakasih.
  </div>

</div>

<!-- Lightbox untuk preview foto -->
<div id="rpt-lb-overlay">
  <img id="rpt-lb-img" src="" alt="Preview Foto">
</div>
<script>
function rptOpenLb(src) {
  document.getElementById('rpt-lb-img').src = src;
  document.getElementById('rpt-lb-overlay').classList.add('active');
}
document.getElementById('rpt-lb-overlay').addEventListener('click', function() {
  this.classList.remove('active');
  document.getElementById('rpt-lb-img').src = '';
});
</script>
