<?php
$items       = isset($items)       ? $items       : array();
$kode_bagian = isset($kode_bagian) ? $kode_bagian : '-';
$nama_bagian = isset($nama_bagian) ? $nama_bagian : $kode_bagian;
$flag_string = isset($flag_string) ? $flag_string : 'medis';
$tgl_filter  = isset($tgl_filter)  ? $tgl_filter  : '';

// Format tanggal untuk tampilan: YYYY-MM-DD → DD/MM/YYYY
$tgl_filter_display = '';
if ($tgl_filter && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $tgl_filter, $m)) {
    $tgl_filter_display = $m[3] . '/' . $m[2] . '/' . $m[1];
}

// Pre-komputasi nilai per item untuk mendapatkan total (agar bisa hitung %)
$rows            = array();
$total_nilai_all = 0;
$total_stok_all  = 0;

foreach ($items as $item) {
    $rasio       = (!empty($item->rasio) && (int)$item->rasio > 0) ? (int)$item->rasio : 1;
    $wa_besar    = (float)$item->wa_harga_modal;
    $harga_kecil = ($wa_besar > 0) ? $wa_besar / $rasio : 0;
    $stok        = (float)$item->jml_sat_kcl;
    $nilai       = $stok * $harga_kecil;

    $total_nilai_all += $nilai;
    $total_stok_all  += $stok;

    $rows[] = array(
        'item'        => $item,
        'rasio'       => $rasio,
        'wa_besar'    => $wa_besar,
        'harga_kecil' => $harga_kecil,
        'stok'        => $stok,
        'nilai'       => $nilai,
    );
}
?>

<div class="pbr-detail-container">

  <!-- Header unit + badge historis -->
  <div style="margin-bottom:10px;display:flex;align-items:center;gap:10px;flex-wrap:wrap">
    <div style="font-size:13px;font-weight:700;color:#0369a1">
      <i class="fa fa-building" style="color:#0891b2"></i>
      <?php echo htmlspecialchars($nama_bagian) ?>
      <span style="font-weight:400;color:#64748b;font-size:11px">(<?php echo htmlspecialchars($kode_bagian) ?>)</span>
    </div>
    <?php if ($tgl_filter_display): ?>
    <div style="padding:4px 10px;background:#fef3c7;border:1px solid #fde68a;border-radius:6px;
                font-size:11px;color:#92400e;display:inline-flex;align-items:center;gap:5px">
      <i class="fa fa-calendar" style="color:#d97706"></i>
      <strong>Data historis per tanggal: <?php echo $tgl_filter_display ?></strong>
    </div>
    <?php endif ?>
    <?php if (empty($tgl_filter)): // hanya tampilkan tombol kosongkan semua untuk data real-time ?>
    <div style="margin-left:auto">
      <span style="font-size:10px;color:#94a3b8;font-style:italic">
        <i class="fa fa-info-circle"></i> Kosongkan stok hanya tersedia untuk data real-time
      </span>
    </div>
    <?php endif ?>
  </div>

  <?php if (empty($rows)): ?>
    <div style="text-align:center;color:#94a3b8;padding:24px;font-size:12px;font-style:italic">
      <i class="fa fa-info-circle"></i> Tidak ada data stok barang untuk unit ini.
    </div>
  <?php else: ?>

  <!-- Tabel item barang -->
  <table style="width:100%;font-size:12px;border-collapse:collapse">
    <thead>
      <tr style="background:#e0f2fe">
        <th style="padding:5px 8px;border:1px solid #bae6fd;text-align:center;width:28px">#</th>
        <th style="padding:5px 8px;border:1px solid #bae6fd;width:110px">Kode Barang</th>
        <th style="padding:5px 8px;border:1px solid #bae6fd">Nama Barang / Satuan</th>
        <th style="padding:5px 8px;border:1px solid #bae6fd;text-align:center;width:80px">Satuan</th>
        <th style="padding:5px 8px;border:1px solid #bae6fd;text-align:right;width:100px">Jumlah Stok</th>
        <th style="padding:5px 8px;border:1px solid #bae6fd;text-align:right;width:140px;background:#fffbeb">
          Harga WA / Sat (Rp)
        </th>
        <th style="padding:5px 8px;border:1px solid #bae6fd;text-align:right;width:160px;background:#fef9c3">
          Total Nilai (Rp)
        </th>
        <th style="padding:5px 8px;border:1px solid #bae6fd;text-align:center;width:70px">%</th>
        <th style="padding:5px 8px;border:1px solid #bae6fd;text-align:center;width:100px;background:#fff1f2">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $i => $r):
          $item         = $r['item'];
          $satuan_kecil = !empty($item->satuan_kecil) ? strtolower($item->satuan_kecil) : '';
          $satuan_besar = !empty($item->satuan_besar) ? strtoupper($item->satuan_besar) : '';
          $pct          = ($total_nilai_all > 0) ? round($r['nilai'] / $total_nilai_all * 100, 1) : 0;
          $bar_w        = min($pct, 100);
          $row_bg       = ($i % 2 === 1) ? '#f0f9ff' : '';
          // Data untuk button kosongkan (JSON-safe)
          $btn_kode_brg  = htmlspecialchars($item->kode_brg,  ENT_QUOTES);
          $btn_nama_brg  = htmlspecialchars($item->nama_brg,  ENT_QUOTES);
          $btn_satuan    = htmlspecialchars($satuan_kecil,    ENT_QUOTES);
          $btn_kode_bag  = htmlspecialchars($kode_bagian,     ENT_QUOTES);
          $btn_nama_bag  = htmlspecialchars($nama_bagian,     ENT_QUOTES);
      ?>
      <tr style="background:<?php echo $row_bg ?>;transition:background .15s">
        <td style="padding:4px 8px;border:1px solid #e2e8f0;text-align:center;color:#94a3b8;font-size:10px">
          <?php echo $i + 1 ?>
        </td>
        <td style="padding:4px 8px;border:1px solid #e2e8f0;font-family:monospace;font-size:11px;color:#475569">
          <?php echo htmlspecialchars($item->kode_brg) ?>
        </td>
        <td style="padding:4px 8px;border:1px solid #e2e8f0">
          <strong><?php echo htmlspecialchars($item->nama_brg) ?></strong>
          <?php if ($satuan_besar && $satuan_kecil): ?>
          <br><small class="text-muted">
            <?php echo $satuan_besar ?> / <?php echo $satuan_kecil ?>
            <?php if ($r['rasio'] > 1): ?>
              <span style="color:#94a3b8">(1:<?php echo $r['rasio'] ?>)</span>
            <?php endif ?>
          </small>
          <?php endif ?>
        </td>
        <td style="padding:4px 8px;border:1px solid #e2e8f0;text-align:center;color:#475569;font-size:11px">
          <?php echo strtoupper($satuan_kecil) ?: '-' ?>
        </td>
        <td style="padding:4px 8px;border:1px solid #e2e8f0;text-align:right;font-weight:700;color:#1e40af">
          <?php echo number_format((int)round($r['stok']), 0, ',', '.') ?>
          <span style="font-weight:400;color:#94a3b8;font-size:10px"><?php echo $satuan_kecil ?></span>
        </td>
        <td style="padding:4px 8px;border:1px solid #e2e8f0;text-align:right;background:#fffbeb;color:#92400e">
          <?php if ($r['harga_kecil'] > 0): ?>
            Rp <?php echo number_format((int)round($r['harga_kecil']), 0, ',', '.') ?>
          <?php else: ?>
            <span style="color:#94a3b8">-</span>
          <?php endif ?>
        </td>
        <td style="padding:4px 8px;border:1px solid #e2e8f0;text-align:right;background:#fef9c3;font-weight:700;color:#713f12">
          <?php if ($r['nilai'] > 0): ?>
            Rp <?php echo number_format((int)round($r['nilai']), 0, ',', '.') ?>
          <?php else: ?>
            <span style="font-weight:400;color:#94a3b8">-</span>
          <?php endif ?>
        </td>
        <td style="padding:4px 8px;border:1px solid #e2e8f0">
          <div style="display:flex;align-items:center;gap:4px;justify-content:flex-end">
            <div style="width:36px;height:6px;background:#e2e8f0;border-radius:3px;overflow:hidden">
              <div style="width:<?php echo $bar_w ?>%;height:100%;background:#0891b2;border-radius:3px"></div>
            </div>
            <span style="font-size:10px;color:#475569;white-space:nowrap"><?php echo $pct ?>%</span>
          </div>
        </td>
        <td style="padding:4px 8px;border:1px solid #e2e8f0;text-align:center;background:#fff1f2">
          <button type="button"
            class="pbr-btn-kosongkan"
            data-kode-brg="<?php echo $btn_kode_brg ?>"
            data-nama-brg="<?php echo $btn_nama_brg ?>"
            data-satuan="<?php echo $btn_satuan ?>"
            data-kode-bagian="<?php echo $btn_kode_bag ?>"
            data-nama-bagian="<?php echo $btn_nama_bag ?>"
            data-stok="<?php echo $r['stok'] ?>"
            data-harga="<?php echo round($r['harga_kecil']) ?>"
            data-nilai="<?php echo round($r['nilai']) ?>"
            title="Kosongkan stok <?php echo $btn_nama_brg ?>"
            style="padding:2px 8px;font-size:10px;background:#dc2626;color:#fff;
                   border:none;border-radius:4px;cursor:pointer;line-height:1.5;
                   white-space:nowrap">
            <i class="fa fa-times-circle"></i> Kosongkan
          </button>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
    <tfoot>
      <tr style="background:#f1f5f9;font-weight:700;border-top:2px solid #cbd5e1">
        <td colspan="4" style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;color:#374151">
          Total &mdash; <?php echo count($rows) ?> item
        </td>
        <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;color:#1e40af">
          <?php echo number_format((int)round($total_stok_all), 0, ',', '.') ?>
        </td>
        <td style="padding:5px 8px;border:1px solid #e2e8f0;background:#fffbeb"></td>
        <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;background:#fef9c3;color:#713f12">
          <?php if ($total_nilai_all > 0): ?>
            Rp <?php echo number_format((int)round($total_nilai_all), 0, ',', '.') ?>
          <?php else: ?>
            <span style="font-weight:400;color:#94a3b8">-</span>
          <?php endif ?>
        </td>
        <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:center;font-size:10px;color:#475569">
          100%
        </td>
        <td style="padding:5px 8px;border:1px solid #e2e8f0;background:#fff1f2"></td>
      </tr>
    </tfoot>
  </table>

  <?php endif ?>

</div>
