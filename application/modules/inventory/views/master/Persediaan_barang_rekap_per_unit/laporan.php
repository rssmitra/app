<?php
$items      = isset($items)      ? $items      : array();
$summary    = isset($summary)    ? $summary    : null;
$flag_str   = isset($flag_string) ? $flag_string : 'medis';
$tgl_filter = isset($tgl_filter) ? $tgl_filter : '';
$title      = isset($title)      ? $title      : 'Rekap Persediaan Per Unit';

// Format tanggal cetak
$tgl_cetak_display = date('d/m/Y');
$tgl_filter_display = '';
if ($tgl_filter && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $tgl_filter, $m)) {
    $tgl_filter_display = $m[3] . '/' . $m[2] . '/' . $m[1];
}

// Kelompokkan item per bagian
$grouped = array();
foreach ($items as $row) {
    $kb = $row->kode_bagian;
    if (!isset($grouped[$kb])) {
        $grouped[$kb] = array(
            'kode_bagian' => $kb,
            'nama_bagian' => $row->nama_bagian,
            'items'       => array(),
        );
    }
    $grouped[$kb]['items'][] = $row;
}

// Grand total
$grand_nilai = 0;
$grand_stok_rows = 0;
foreach ($grouped as &$g) {
    $g['total_nilai'] = 0;
    foreach ($g['items'] as $item) {
        $rasio       = (!empty($item->rasio) && (int)$item->rasio > 0) ? (int)$item->rasio : 1;
        $wa_besar    = (float)$item->wa_harga_modal;
        $harga_kecil = ($wa_besar > 0) ? $wa_besar / $rasio : 0;
        $stok        = (float)$item->jml_sat_kcl;
        $nilai       = $stok * $harga_kecil;
        $g['total_nilai'] += $nilai;
        $grand_nilai      += $nilai;
    }
    $grand_stok_rows += count($g['items']);
}
unset($g);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title><?php echo htmlspecialchars($title) ?></title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: Arial, sans-serif;
        font-size: 11px;
        color: #111;
        background: #fff;
        padding: 16px 20px;
    }

    /* ── Header ── */
    .lap-header { text-align: center; margin-bottom: 14px; border-bottom: 2px solid #0891b2; padding-bottom: 10px; }
    .lap-header h2 { font-size: 15px; font-weight: 700; color: #0369a1; }
    .lap-header p  { font-size: 11px; color: #555; margin-top: 3px; }

    /* ── Summary strip ── */
    .lap-summary {
        display: flex; gap: 10px; margin-bottom: 14px;
        background: #f0f9ff; border: 1px solid #bae6fd;
        border-radius: 6px; padding: 8px 12px;
    }
    .lap-summary .ls-item { flex: 1; text-align: center; }
    .lap-summary .ls-label { font-size: 9px; font-weight: 700; text-transform: uppercase;
                              color: #0369a1; letter-spacing: .4px; }
    .lap-summary .ls-value { font-size: 16px; font-weight: 900; color: #0c4a6e; }

    /* ── Per-bagian block ── */
    .lap-bagian { margin-bottom: 16px; page-break-inside: avoid; }
    .lap-bagian-title {
        background: #0891b2; color: #fff;
        padding: 4px 8px; font-size: 11px; font-weight: 700;
        display: flex; justify-content: space-between; align-items: center;
        border-radius: 4px 4px 0 0;
    }
    .lap-bagian-title .lbt-code { font-weight: 400; font-size: 10px; opacity: .85; }
    .lap-bagian-title .lbt-total { font-size: 11px; }

    table.lap-tbl {
        width: 100%; border-collapse: collapse;
        font-size: 10px;
    }
    table.lap-tbl thead tr { background: #e0f2fe; }
    table.lap-tbl th, table.lap-tbl td {
        border: 1px solid #bae6fd;
        padding: 3px 6px;
    }
    table.lap-tbl th { font-weight: 700; text-align: center; }
    table.lap-tbl td.r { text-align: right; }
    table.lap-tbl td.c { text-align: center; }
    table.lap-tbl tbody tr:nth-child(even) { background: #f0f9ff; }
    table.lap-tbl tfoot tr { background: #dbeafe; font-weight: 700; border-top: 2px solid #93c5fd; }

    /* ── Grand total ── */
    .lap-grand {
        margin-top: 10px;
        background: #0369a1; color: #fff;
        padding: 6px 12px; border-radius: 4px;
        display: flex; justify-content: space-between; align-items: center;
        font-size: 12px; font-weight: 700;
    }

    /* ── Footer ── */
    .lap-footer { margin-top: 16px; font-size: 10px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 6px; }

    /* ── Print controls (hanya di layar) ── */
    .lap-controls {
        margin-bottom: 12px; display: flex; gap: 8px;
    }
    .lap-controls button {
        padding: 5px 14px; font-size: 12px; border: none; border-radius: 4px;
        cursor: pointer; font-weight: 600;
    }
    .btn-print { background: #0891b2; color: #fff; }
    .btn-close  { background: #e2e8f0; color: #374151; }

    @media print {
        .lap-controls { display: none !important; }
        body { padding: 0; }
        .lap-bagian { page-break-inside: avoid; }
    }
  </style>
</head>
<body>

  <!-- Tombol cetak (hanya di layar) -->
  <div class="lap-controls">
    <button class="btn-print" onclick="window.print()">&#128438; Cetak / Simpan PDF</button>
    <button class="btn-close"  onclick="window.close()">&#10005; Tutup</button>
  </div>

  <!-- Header laporan -->
  <div class="lap-header">
    <h2><?php echo htmlspecialchars($title) ?></h2>
    <p>
      <?php if ($tgl_filter_display): ?>
        Data historis per tanggal <strong><?php echo $tgl_filter_display ?></strong>
        &nbsp;&bull;&nbsp;
      <?php else: ?>
        Data stok terkini &nbsp;&bull;&nbsp;
      <?php endif ?>
      Dicetak: <?php echo $tgl_cetak_display ?>
      &nbsp;&bull;&nbsp;
      Harga berdasarkan WA harga modal 3 PO terakhir
    </p>
  </div>

  <!-- Summary -->
  <div class="lap-summary">
    <div class="ls-item">
      <div class="ls-label">Total Unit</div>
      <div class="ls-value"><?php echo $summary ? number_format((int)$summary->total_unit, 0, ',', '.') : count($grouped) ?></div>
    </div>
    <div class="ls-item">
      <div class="ls-label">Total Jenis Item</div>
      <div class="ls-value"><?php echo $summary ? number_format((int)$summary->total_jenis, 0, ',', '.') : number_format($grand_stok_rows, 0, ',', '.') ?></div>
    </div>
    <div class="ls-item" style="flex:2">
      <div class="ls-label">Total Nilai Persediaan</div>
      <div class="ls-value">Rp <?php echo number_format((int)round($grand_nilai), 0, ',', '.') ?></div>
    </div>
  </div>

  <?php if (empty($grouped)): ?>
    <p style="text-align:center;color:#94a3b8;padding:24px;font-style:italic">
      Tidak ada data persediaan.
    </p>
  <?php else: ?>

  <?php $no_bagian = 0; foreach ($grouped as $g):
      $no_bagian++;
      $no_item = 0;
  ?>
  <div class="lap-bagian">

    <div class="lap-bagian-title">
      <span>
        <?php echo $no_bagian ?>. <?php echo htmlspecialchars($g['nama_bagian']) ?>
        <span class="lbt-code">(<?php echo htmlspecialchars($g['kode_bagian']) ?>)</span>
      </span>
      <span class="lbt-total">
        <?php echo count($g['items']) ?> item &mdash;
        Rp <?php echo number_format((int)round($g['total_nilai']), 0, ',', '.') ?>
      </span>
    </div>

    <table class="lap-tbl">
      <thead>
        <tr>
          <th width="22">#</th>
          <th width="95">Kode Barang</th>
          <th>Nama Barang</th>
          <th width="55">Satuan</th>
          <th width="80">Jumlah Stok</th>
          <th width="110">Harga WA/Sat (Rp)</th>
          <th width="120">Total Nilai (Rp)</th>
          <th width="44">%</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($g['items'] as $item):
            $no_item++;
            $rasio       = (!empty($item->rasio) && (int)$item->rasio > 0) ? (int)$item->rasio : 1;
            $wa_besar    = (float)$item->wa_harga_modal;
            $harga_kecil = ($wa_besar > 0) ? $wa_besar / $rasio : 0;
            $stok        = (float)$item->jml_sat_kcl;
            $nilai       = $stok * $harga_kecil;
            $pct         = ($g['total_nilai'] > 0) ? round($nilai / $g['total_nilai'] * 100, 1) : 0;
            $sat_kecil   = !empty($item->satuan_kecil) ? strtoupper($item->satuan_kecil) : '';
            $sat_besar   = !empty($item->satuan_besar) ? strtoupper($item->satuan_besar) : '';
            if($stok > 0) : 
        ?>
        <tr>
          <td class="c"><?php echo $no_item ?></td>
          <td style="font-family:monospace;font-size:10px;color:#475569"><?php echo htmlspecialchars($item->kode_brg) ?></td>
          <td>
            <?php echo htmlspecialchars($item->nama_brg) ?>
            <?php if ($sat_besar && $sat_kecil): ?>
              <br><span style="font-size:9px;color:#94a3b8">
                <?php echo $sat_besar ?>/<?php echo $sat_kecil ?>
                <?php if ($rasio > 1): ?>(1:<?php echo $rasio ?>)<?php endif ?>
              </span>
            <?php endif ?>
          </td>
          <td class="c"><?php echo $sat_kecil ?: '-' ?></td>
          <td class="r" style="font-weight:700;color:#1e40af">
            <?php echo number_format((int)round($stok), 0, ',', '.') ?>
          </td>
          <td class="r" style="background:#fffbeb;color:#92400e">
            <?php echo $harga_kecil > 0 ? number_format((int)round($harga_kecil), 0, ',', '.') : 0 ?>
          </td>
          <td class="r" style="background:#fef9c3;font-weight:700;color:#713f12">
            <?php echo $nilai > 0 ? number_format((int)round($nilai), 0, ',', '.') : '-' ?>
          </td>
          <td class="r" style="font-size:9px"><?php echo $pct ?>%</td>
        </tr>
        <?php endif; endforeach ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" class="r">Total &mdash; <?php echo count($g['items']) ?> item</td>
          <td class="r" style="color:#1e40af">-</td>
          <td style="background:#fffbeb"></td>
          <td class="r" style="background:#fef9c3;color:#713f12">
            <?php echo number_format((int)round($g['total_nilai']), 0, ',', '.') ?>
          </td>
          <td class="c">100%</td>
        </tr>
      </tfoot>
    </table>

  </div>
  <?php endforeach ?>

  <!-- Grand Total -->
  <div class="lap-grand">
    <span>GRAND TOTAL &mdash; <?php echo count($grouped) ?> unit, <?php echo number_format($grand_stok_rows, 0, ',', '.') ?> jenis item</span>
    <span>Rp <?php echo number_format((int)round($grand_nilai), 0, ',', '.') ?></span>
  </div>

  <?php endif ?>

  <div class="lap-footer">
    Laporan ini digenerate secara otomatis oleh sistem. Data berdasarkan
    <?php echo $tgl_filter_display ? 'stok historis per ' . $tgl_filter_display : 'stok terkini' ?>.
  </div>

</body>
</html>
