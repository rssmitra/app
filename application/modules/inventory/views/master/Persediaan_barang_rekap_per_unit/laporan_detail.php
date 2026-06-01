<?php
$items       = isset($items)       ? $items       : array();
$kode_bagian = isset($kode_bagian) ? $kode_bagian : '';
$nama_bagian = isset($nama_bagian) ? $nama_bagian : $kode_bagian;
$flag_str    = isset($flag_string) ? $flag_string : 'medis';
$tgl_dari    = isset($tgl_dari)    ? $tgl_dari    : date('Y-m-01');
$tgl_sampai  = isset($tgl_sampai)  ? $tgl_sampai  : date('Y-m-d');
$title       = isset($title)       ? $title       : 'Laporan Persediaan Per Unit';

// Format tanggal untuk tampilan
function _ld_fmt($d) {
    if (!$d) return '-';
    $p = explode('-', $d);
    return count($p) === 3 ? $p[2].'/'.$p[1].'/'.$p[0] : $d;
}

// Saldo awal = stok akhir kartu terbaru sebelum tgl_dari
// Hitung grand total
$grand = array(
    'saldo_awal_qty'  => 0, 'saldo_awal_nilai'  => 0,
    'pembelian_qty'   => 0, 'pembelian_nilai'   => 0,
    'penerimaan_qty'  => 0, 'penerimaan_nilai'  => 0,
    'penjualan_qty'   => 0, 'penjualan_nilai'   => 0,
    'saldo_akhir_qty' => 0, 'saldo_akhir_nilai' => 0,
);

$rows = array();
foreach ($items as $item) {
    $rasio       = (!empty($item->rasio) && (int)$item->rasio > 0) ? (int)$item->rasio : 1;
    $wa_besar    = (float)$item->wa_harga_modal;
    $harga_kecil = ($wa_besar > 0) ? $wa_besar / $rasio : 0;

    $saldo_awal_qty  = (float)$item->saldo_awal;
    $pembelian_qty   = (float)$item->qty_pembelian;
    $pembelian_nilai = (float)$item->nilai_pembelian;  // WA-based (qty × WA / rasio)
    $penerimaan_qty  = (float)$item->qty_penerimaan;
    $penjualan_qty   = (float)$item->qty_penjualan;

    // Saldo akhir diambil dari stok_akhir aktual kartu (bukan dihitung dari mutasi)
    // agar konsisten dengan detail row pada tabel
    $saldo_akhir_qty  = (float)$item->saldo_akhir;
    $saldo_awal_nilai = $saldo_awal_qty  * $harga_kecil;
    $penerimaan_nilai = $penerimaan_qty  * $harga_kecil;
    $penjualan_nilai  = $penjualan_qty   * $harga_kecil;
    $saldo_akhir_nilai= $saldo_akhir_qty * $harga_kecil;

    $grand['saldo_awal_qty']   += $saldo_awal_qty;
    $grand['saldo_awal_nilai'] += $saldo_awal_nilai;
    $grand['pembelian_qty']    += $pembelian_qty;
    $grand['pembelian_nilai']  += $pembelian_nilai;
    $grand['penerimaan_qty']   += $penerimaan_qty;
    $grand['penerimaan_nilai'] += $penerimaan_nilai;
    $grand['penjualan_qty']    += $penjualan_qty;
    $grand['penjualan_nilai']  += $penjualan_nilai;
    $grand['saldo_akhir_qty']  += $saldo_akhir_qty;
    $grand['saldo_akhir_nilai']+= $saldo_akhir_nilai;

    $rows[] = compact(
        'item', 'rasio', 'harga_kecil',
        'saldo_awal_qty', 'saldo_awal_nilai',
        'pembelian_qty', 'pembelian_nilai',
        'penerimaan_qty', 'penerimaan_nilai',
        'penjualan_qty', 'penjualan_nilai',
        'saldo_akhir_qty', 'saldo_akhir_nilai'
    );
}

// Nama base URL untuk form filter
$base_url = site_url('inventory/master/Persediaan_barang_rekap_per_unit/laporan_detail');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Detail Persediaan – <?php echo htmlspecialchars($nama_bagian) ?></title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body { font-family: Arial, sans-serif; font-size: 11px; color: #111; background: #fff; padding: 14px 18px; }

    /* ── Header ── */
    .ldd-header { text-align: center; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #0891b2; }
    .ldd-header h2 { font-size: 14px; font-weight: 700; color: #0369a1; }
    .ldd-header .ldd-unit { font-size: 13px; font-weight: 700; color: #1e3a5f; margin-top: 3px; }
    .ldd-header p  { font-size: 10px; color: #555; margin-top: 3px; }

    /* ── Filter ── */
    .ldd-filter {
        display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
        margin-bottom: 12px; padding: 8px 12px;
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;
    }
    .ldd-filter label { font-size: 11px; font-weight: 600; color: #374151; }
    .ldd-filter input[type=date] {
        font-size: 11px; padding: 3px 6px; border: 1px solid #cbd5e1; border-radius: 4px;
    }
    .ldd-filter button {
        padding: 4px 12px; font-size: 11px; border: none; border-radius: 4px;
        cursor: pointer; font-weight: 600;
    }
    .btn-filter { background: #0891b2; color: #fff; }
    .btn-print  { background: #16a34a; color: #fff; }
    .btn-close  { background: #e2e8f0; color: #374151; }

    /* ── Tabel ── */
    table.ldd-tbl { width: 100%; border-collapse: collapse; font-size: 10px; }
    table.ldd-tbl th, table.ldd-tbl td {
        border: 1px solid #bae6fd; padding: 3px 5px;
    }
    table.ldd-tbl thead tr:first-child { background: #0891b2; color: #fff; }
    table.ldd-tbl thead tr:first-child th { border-color: #0369a1; text-align: center; }
    table.ldd-tbl thead tr.ldd-subhead { background: #e0f2fe; }
    table.ldd-tbl thead tr.ldd-subhead th { text-align: center; font-size: 9px; color: #0369a1; font-weight: 700; }
    table.ldd-tbl tbody tr:nth-child(even) { background: #f0f9ff; }
    table.ldd-tbl tbody tr:hover { background: #dbeafe; }
    table.ldd-tbl td.r { text-align: right; }
    table.ldd-tbl td.c { text-align: center; }

    /* Kolom warna per section */
    .col-sa   { background: #eff6ff !important; } /* saldo awal */
    .col-pb   { background: #fef9c3 !important; } /* pembelian */
    .col-pn   { background: #f0fdf4 !important; } /* penerimaan */
    .col-pj   { background: #fff7ed !important; } /* penjualan */
    .col-sakh { background: #ecfeff !important; } /* saldo akhir */
    .col-sakh-total { background: #cffafe !important; font-weight: 700; }

    /* tfoot grand total */
    table.ldd-tbl tfoot tr { background: #1e40af !important; color: #fff !important; font-weight: 700; border-top: 2px solid #1d4ed8; }
    table.ldd-tbl tfoot td { border-color: #1d4ed8 !important; }
    table.ldd-tbl tfoot td.r { text-align: right; }
    table.ldd-tbl tfoot td.c { text-align: center; }

    /* ── Footer ── */
    .ldd-footer { margin-top: 12px; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 5px; }

    @media print {
        .ldd-filter .no-print, .btn-print, .btn-close { display: none !important; }
        body { padding: 4px; font-size: 9px; }
        @page { size: landscape; margin: 10mm; }
        table.ldd-tbl th, table.ldd-tbl td { padding: 2px 3px; font-size: 8px; }
    }
  </style>
</head>
<body>

  <!-- Filter + kontrol cetak -->
  <div class="ldd-filter">
    <form method="get" action="<?php echo $base_url ?>" style="display:contents">
      <input type="hidden" name="kode_bagian" value="<?php echo htmlspecialchars($kode_bagian) ?>">
      <input type="hidden" name="flag"        value="<?php echo htmlspecialchars($flag_str) ?>">
      <label>Dari</label>
      <input type="date" name="tgl_dari"   value="<?php echo $tgl_dari ?>">
      <label>s.d.</label>
      <input type="date" name="tgl_sampai" value="<?php echo $tgl_sampai ?>">
      <button type="submit" class="btn-filter">&#128269; Tampilkan</button>
    </form>
    <button class="btn-print no-print" onclick="window.print()">&#128438; Cetak / PDF</button>
    <button class="btn-close  no-print" onclick="window.close()">&#10005; Tutup</button>
  </div>

  <!-- Header laporan -->
  <div class="ldd-header">
    <h2><?php echo htmlspecialchars($title) ?></h2>
    <div class="ldd-unit">
      <?php echo htmlspecialchars($nama_bagian) ?>
      <span style="font-weight:400;color:#64748b;font-size:11px">(<?php echo htmlspecialchars($kode_bagian) ?>)</span>
    </div>
    <p>
      Periode: <strong><?php echo _ld_fmt($tgl_dari) ?></strong>
      s.d. <strong><?php echo _ld_fmt($tgl_sampai) ?></strong>
      &nbsp;&bull;&nbsp;
      Harga berdasarkan WA harga modal 3 PO terakhir
      &nbsp;&bull;&nbsp;
      Dicetak: <?php echo date('d/m/Y H:i') ?>
    </p>
  </div>

  <?php if (empty($rows)): ?>
    <p style="text-align:center;color:#94a3b8;padding:24px;font-style:italic">
      Tidak ada data mutasi stok untuk unit ini pada periode yang dipilih.
    </p>
  <?php else: ?>

  <table class="ldd-tbl">
    <thead>
      <!-- Baris 1: judul section -->
      <tr>
        <th rowspan="2" style="width:22px">#</th>
        <th rowspan="2" style="width:90px">Kode Barang</th>
        <th rowspan="2">Nama Barang / Satuan</th>
        <th colspan="3" style="background:#1d4ed8">Saldo Awal</th>
        <th colspan="2" style="background:#b45309">Pembelian</th>
        <th colspan="2" style="background:#15803d">Penerimaan/Distribusi</th>
        <th colspan="2" style="background:#b45309">Penjualan</th>
        <th colspan="3" style="background:#0e7490">Saldo Akhir</th>
      </tr>
      <!-- Baris 2: sub-judul -->
      <tr class="ldd-subhead">
        <th class="col-sa" style="width:72px">Qty</th>
        <th class="col-sa" style="width:100px">Harga/Sat (Rp)</th>
        <th class="col-sa" style="width:100px">Total (Rp)</th>
        <th class="col-pb" style="width:72px">Qty</th>
        <th class="col-pb" style="width:110px">Nilai (Rp)</th>
        <th class="col-pn" style="width:72px">Qty</th>
        <th class="col-pn" style="width:110px">Nilai (Rp)</th>
        <th class="col-pj" style="width:72px">Qty</th>
        <th class="col-pj" style="width:110px">Nilai (Rp)</th>
        <th class="col-sakh" style="width:80px">Qty</th>
        <th class="col-sakh" style="width:100px">Harga/Sat (Rp)</th>
        <th class="col-sakh-total" style="width:120px">Total Nilai (Rp)</th>
      </tr>
    </thead>
    <tbody>
    <?php $no = 0; foreach ($rows as $i => $r):
        $item     = $r['item'];
        $sat_kecil = !empty($item->satuan_kecil) ? strtoupper($item->satuan_kecil) : '';
        $sat_besar = !empty($item->satuan_besar) ? strtoupper($item->satuan_besar) : '';
        $rasio     = $r['rasio'];
        // total saldo awal
        $total_saldo_awal = $r['saldo_awal_qty'] * $r['harga_kecil'];

        if( $r['saldo_akhir_nilai'] > 0) : 
          $no++;
    ?>
      <tr>
        <td class="c" style="color:#94a3b8;font-size:9px"><?php echo $no ?></td>
        <td style="font-family:monospace;font-size:9px;color:#475569"><?php echo htmlspecialchars($item->kode_brg) ?></td>
        <td>
          <strong><?php echo htmlspecialchars($item->nama_brg) ?></strong>
          <?php if ($sat_besar || $sat_kecil): ?>
          <br><span style="font-size:9px;color:#94a3b8">
            <?php echo $sat_besar ?: $sat_kecil ?>
            <?php if ($sat_besar && $sat_kecil && $sat_besar !== $sat_kecil): ?>
              / <?php echo $sat_kecil ?>
              <?php if ($rasio > 1): ?><span style="color:#cbd5e1">(1:<?php echo $rasio ?>)</span><?php endif ?>
            <?php endif ?>
          </span>
          <?php endif ?>
        </td>

        <!-- Saldo Awal -->
        <td class="r col-sa" style="font-weight:700;color:#1e40af">
          <?php echo $r['saldo_awal_qty'] > 0
            ? number_format((float)$r['saldo_awal_qty'], 2, ',', '.')
            : '<span style="color:#cbd5e1">-</span>' ?>
          <?php if ($r['saldo_awal_qty'] > 0 && $sat_kecil): ?>
            <span style="font-weight:400;color:#94a3b8;font-size:8px"><?php echo $sat_kecil ?></span>
          <?php endif ?>
        </td>
        <td class="r col-sa" style="color:#1e3a8a;font-size:9px">
          <?php echo $r['harga_kecil'] > 0
            ? number_format((int)round($r['harga_kecil']), 0, ',', '.')
            : '<span style="color:#cbd5e1">-</span>' ?>
        </td>

        <td class="r col-sa" style="color:#1e3a8a;font-size:9px">
          <?php echo $total_saldo_awal > 0
            ? number_format((int)round($total_saldo_awal), 0, ',', '.')
            : '<span style="color:#cbd5e1">-</span>' ?>
        </td>

        <!-- Pembelian -->
        <td class="r col-pb" style="color:#92400e">
          <?php echo $r['pembelian_qty'] != 0
            ? number_format((float)$r['pembelian_qty'], 2, ',', '.')
            : '<span style="color:#cbd5e1">-</span>' ?>
        </td>
        <td class="r col-pb" style="color:#713f12">
          <?php echo $r['pembelian_nilai'] > 0
            ? number_format((int)round($r['pembelian_nilai']), 0, ',', '.')
            : '<span style="color:#cbd5e1">-</span>' ?>
        </td>

        <!-- Penerimaan -->
        <td class="r col-pn" style="color:#14532d">
          <?php echo $r['penerimaan_qty'] != 0
            ? number_format((float)$r['penerimaan_qty'], 2, ',', '.')
            : '<span style="color:#cbd5e1">-</span>' ?>
        </td>
        <td class="r col-pn" style="color:#166534">
          <?php echo number_format((int)round($r['penerimaan_nilai']), 0, ',', '.') ?>
        </td>

        <!-- Penjualan -->
        <td class="r col-pj" style="color:#9a3412">
          <?php echo $r['penjualan_qty'] != 0
            ? number_format((float)$r['penjualan_qty'], 2, ',', '.')
            : '<span style="color:#cbd5e1">-</span>' ?>
        </td>
        <td class="r col-pj" style="color:#7c2d12">
          <?php echo $r['penjualan_nilai'] > 0
            ? number_format((int)round($r['penjualan_nilai']), 0, ',', '.')
            : '<span style="color:#cbd5e1">-</span>' ?>
        </td>

        <!-- Saldo Akhir -->
        <td class="r col-sakh" style="font-weight:700;color:#0c4a6e">
          <?php echo number_format((float)$r['saldo_akhir_qty'], 2, ',', '.') ?>
          <?php if ($sat_kecil): ?>
            <span style="font-weight:400;color:#94a3b8;font-size:8px"><?php echo $sat_kecil ?></span>
          <?php endif ?>
        </td>
        <td class="r col-sakh" style="color:#0369a1;font-size:9px">
          <?php echo $r['harga_kecil'] > 0
            ? number_format((int)round($r['harga_kecil']), 0, ',', '.')
            : '<span style="color:#cbd5e1">-</span>' ?>
        </td>
        <td class="r col-sakh-total" style="color:#0c4a6e">
          <?php echo $r['saldo_akhir_nilai'] != 0
            ? 'Rp ' . number_format((int)round($r['saldo_akhir_nilai']), 0, ',', '.')
            : '<span style="font-weight:400;color:#94a3b8">-</span>' ?>
        </td>
      </tr>
    <?php 
      endif; 
    endforeach;
    $saldo_akhir_nilai = $grand['saldo_awal_nilai'] + $grand['pembelian_nilai'] + $grand['penerimaan_nilai'] - $grand['penjualan_nilai'];
   ?>
    </tbody>

    <!-- Grand Total -->
    <tfoot>
      <tr>
        <td colspan="3" class="r">TOTAL &mdash; <?php echo count($rows) ?> item</td>
        <!-- Saldo Awal -->
        <td class="r"></td>
        <td></td>
        <td class="r">Rp <?php echo number_format((int)round($grand['saldo_awal_nilai']), 0, ',', '.') ?></td>
        <!-- Pembelian -->
        <td class="r"></td>
        <td class="r"><?php echo number_format((int)round($grand['pembelian_nilai']), 0, ',', '.') ?></td>
        <!-- Penerimaan -->
        <td class="r"></td>
        <td class="r"><?php echo number_format((int)round($grand['penerimaan_nilai']), 0, ',', '.') ?></td>
        <!-- Penjualan -->
        <td class="r"></td>
        <td class="r"><?php echo number_format((int)round($grand['penjualan_nilai']), 0, ',', '.') ?></td>
        <!-- Saldo Akhir -->
        <td class="r"></td>
        <td></td>
        <td class="r">Rp <?php echo number_format((int)round($saldo_akhir_nilai), 0, ',', '.') ?></td>
      </tr>
    </tfoot>
  </table>

  <?php endif ?>

  <div class="ldd-footer">
    Laporan detail persediaan per unit &bull;
    Unit: <strong><?php echo htmlspecialchars($nama_bagian) ?></strong> &bull;
    Periode: <?php echo _ld_fmt($tgl_dari) ?> s.d. <?php echo _ld_fmt($tgl_sampai) ?> &bull;
    Data harga dari WA 3 PO terakhir &bull; Nilai pembelian dari data PO
  </div>

</body>
</html>
