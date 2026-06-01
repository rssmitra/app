<?php
$stok_bagian    = isset($stok_bagian)    ? $stok_bagian    : array();
$mutasi         = isset($mutasi)         ? $mutasi         : array();
$kode_brg       = isset($kode_brg)       ? $kode_brg       : '-';
$wa             = isset($wa)             ? $wa             : null;
$total_stok     = isset($total_stok)     ? (float)$total_stok     : 0;
$harga_wa_kecil = isset($harga_wa_kecil) ? (float)$harga_wa_kecil : 0;
$tgl_filter     = isset($tgl_filter)     ? $tgl_filter             : '';

// Format tanggal untuk tampilan: YYYY-MM-DD → DD/MM/YYYY
$tgl_filter_display = '';
if ($tgl_filter && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $tgl_filter, $m)) {
    $tgl_filter_display = $m[3] . '/' . $m[2] . '/' . $m[1];
}

$satuan_kecil = (!empty($stok_bagian)) ? strtolower($stok_bagian[0]->satuan_kecil) : '';
$total_mutasi = count($mutasi);
?>

<div class="pb-detail-container">

  <?php if ($tgl_filter_display): ?>
  <div style="margin-bottom:10px;padding:6px 12px;background:#fef3c7;border:1px solid #fde68a;
              border-radius:6px;font-size:11px;color:#92400e;display:inline-flex;align-items:center;gap:6px">
    <i class="fa fa-calendar" style="color:#d97706"></i>
    <strong>Data historis per tanggal: <?php echo $tgl_filter_display ?></strong>
    <span style="color:#a16207">&mdash; stok, WA harga, dan mutasi ditampilkan sesuai posisi pada tanggal tersebut</span>
  </div>
  <?php endif ?>

  <div style="display:flex;gap:16px;flex-wrap:wrap">

    <!-- KIRI: Rekap Stok per Unit Bagian -->
    <div style="flex:1;min-width:280px">
      <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#0369a1;
                  border-bottom:2px solid #bae6fd;padding-bottom:5px;margin-bottom:8px">
        <i class="fa fa-building" style="color:#0891b2"></i>&nbsp; Stok per Unit / Bagian
        <span style="font-weight:400;font-size:10px;color:#64748b;text-transform:none;letter-spacing:0">
          &mdash; klik nama unit untuk filter mutasi
        </span>
      </div>

      <?php if (empty($stok_bagian)): ?>
        <div style="text-align:center;color:#94a3b8;padding:20px;font-size:11px;font-style:italic">
          <i class="fa fa-info-circle"></i> Tidak ada data stok.
        </div>
      <?php else: ?>
      <?php $total_nilai_all = 0; ?>
      <table style="width:100%;font-size:12px;border-collapse:collapse">
        <thead>
          <tr style="background:#e0f2fe">
            <th style="padding:5px 8px;border:1px solid #bae6fd;text-align:center;width:24px">#</th>
            <th style="padding:5px 8px;border:1px solid #bae6fd">Unit / Bagian</th>
            <th style="padding:5px 8px;border:1px solid #bae6fd;text-align:right;width:90px">Stok</th>
            <th style="padding:5px 8px;border:1px solid #bae6fd;text-align:right;width:110px;background:#fffbeb">Harga WA / Sat</th>
            <th style="padding:5px 8px;border:1px solid #bae6fd;text-align:right;width:130px;background:#fef9c3">Total Nilai (Rp)</th>
            <th style="padding:5px 8px;border:1px solid #bae6fd;text-align:right;width:60px">%</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($stok_bagian as $i => $s):
              $jml         = (float)$s->jml_sat_kcl;
              $pct         = ($total_stok > 0) ? round($jml / $total_stok * 100, 1) : 0;
              $bar_w       = min($pct, 100);
              $nilai_bagian = $jml * $harga_wa_kecil;
              $total_nilai_all += $nilai_bagian;
          ?>
          <tr class="pb-bagian-row" data-kode-bagian="<?php echo htmlspecialchars($s->kode_bagian) ?>"
              style="<?php echo ($i % 2 === 1) ? 'background:#f0f9ff' : '' ?>;transition:background .15s">
            <td style="padding:4px 8px;border:1px solid #e2e8f0;text-align:center;color:#94a3b8;font-size:10px">
              <?php echo $i + 1 ?>
            </td>
            <td class="pb-bagian-filter-btn"
                data-kode-bagian="<?php echo htmlspecialchars($s->kode_bagian) ?>"
                data-nama-bagian="<?php echo htmlspecialchars($s->nama_bagian) ?>"
                style="padding:4px 8px;border:1px solid #e2e8f0;cursor:pointer;color:#0369a1;
                       text-decoration:underline;text-decoration-style:dotted">
              <?php echo htmlspecialchars($s->nama_bagian) ?>
              <i class="fa fa-filter" style="font-size:9px;color:#93c5fd;margin-left:3px"
                 title="Klik untuk filter riwayat mutasi unit ini"></i>
            </td>
            <td style="padding:4px 8px;border:1px solid #e2e8f0;text-align:right;font-weight:700">
              <?php echo number_format((int)round($jml), 0, ',', '.') ?>
              <span style="font-weight:400;color:#64748b;font-size:10px"><?php echo $satuan_kecil ?></span>
            </td>
            <td style="padding:4px 8px;border:1px solid #e2e8f0;text-align:right;background:#fffbeb;color:#92400e">
              <?php if ($harga_wa_kecil > 0): ?>
                Rp <?php echo number_format((int)round($harga_wa_kecil), 0, ',', '.') ?>
              <?php else: ?>
                <span style="color:#94a3b8">-</span>
              <?php endif ?>
            </td>
            <td style="padding:4px 8px;border:1px solid #e2e8f0;text-align:right;background:#fef9c3;font-weight:700;color:#713f12">
              <?php if ($nilai_bagian > 0): ?>
                Rp <?php echo number_format((int)round($nilai_bagian), 0, ',', '.') ?>
              <?php else: ?>
                <span style="font-weight:400;color:#94a3b8">-</span>
              <?php endif ?>
            </td>
            <td style="padding:4px 8px;border:1px solid #e2e8f0;text-align:right">
              <div style="display:flex;align-items:center;gap:4px;justify-content:flex-end">
                <div style="width:40px;height:6px;background:#e2e8f0;border-radius:3px;overflow:hidden">
                  <div style="width:<?php echo $bar_w ?>%;height:100%;background:#0891b2;border-radius:3px"></div>
                </div>
                <span style="font-size:10px;color:#475569"><?php echo $pct ?>%</span>
              </div>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
        <tfoot>
          <tr style="background:#f1f5f9;font-weight:700;border-top:2px solid #cbd5e1">
            <td colspan="2" style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;color:#374151">
              Total
            </td>
            <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;color:#0369a1">
              <?php echo number_format((int)round($total_stok), 0, ',', '.') ?>
              <span style="font-weight:400;color:#64748b;font-size:10px"><?php echo $satuan_kecil ?></span>
            </td>
            <td style="padding:5px 8px;border:1px solid #e2e8f0;background:#fffbeb"></td>
            <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;background:#fef9c3;color:#713f12">
              <?php if ($total_nilai_all > 0): ?>
                Rp <?php echo number_format((int)round($total_nilai_all), 0, ',', '.') ?>
              <?php else: ?>
                <span style="font-weight:400;color:#94a3b8">-</span>
              <?php endif ?>
            </td>
            <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right">
              <span style="font-size:10px;color:#475569">100%</span>
            </td>
          </tr>
        </tfoot>
      </table>
      <?php endif ?>
    </div>

    <!-- KANAN: Info WA Harga -->
    <?php if ($wa): ?>
    <div style="min-width:180px;max-width:240px">
      <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#92400e;
                  border-bottom:2px solid #fde68a;padding-bottom:5px;margin-bottom:8px">
        <i class="fa fa-tag" style="color:#d97706"></i>&nbsp; Harga WA Modal
      </div>
      <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:6px;padding:10px 14px">
        <div style="font-size:10px;color:#78350f;font-weight:600;margin-bottom:2px">Per Satuan Besar (WA 3 PO)</div>
        <div style="font-size:16px;font-weight:800;color:#92400e">
          Rp <?php echo number_format((int)round((float)$wa->wa_harga_modal), 0, ',', '.') ?>
        </div>
        <?php if ($harga_wa_kecil > 0): ?>
        <div style="border-top:1px dashed #fde68a;margin-top:8px;padding-top:8px">
          <div style="font-size:10px;color:#78350f;font-weight:600;margin-bottom:2px">
            Per Satuan Kecil
            <?php if (!empty($satuan_kecil)): ?>
              <span style="font-weight:400;color:#a16207">(/ <?php echo $satuan_kecil ?>)</span>
            <?php endif ?>
          </div>
          <div style="font-size:15px;font-weight:800;color:#92400e">
            Rp <?php echo number_format((int)round($harga_wa_kecil), 0, ',', '.') ?>
          </div>
        </div>
        <?php endif ?>
        <div style="font-size:10px;color:#64748b;margin-top:6px">
          <i class="fa fa-info-circle"></i> Weighted Average harga setelah diskon dari 3 PO terakhir.
        </div>
      </div>
    </div>
    <?php endif ?>

  </div><!-- end flex row -->

  <!-- Riwayat Mutasi Stok -->
  <div class="pb-mutasi-section" style="margin-top:16px">

    <!-- Header mutasi -->
    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#475569;
                border-bottom:2px solid #e2e8f0;padding-bottom:5px;margin-bottom:8px;
                display:flex;align-items:center;justify-content:space-between">
      <span>
        <i class="fa fa-exchange" style="color:#0891b2"></i>&nbsp; Riwayat Mutasi Stok
        <span class="pb-mutasi-subtitle"
              style="font-weight:400;font-style:normal;text-transform:none;letter-spacing:0;
                     color:#94a3b8;font-size:10px">
          &mdash; <span class="pb-mutasi-count"><?php echo $total_mutasi ?></span> mutasi
          <?php if ($tgl_filter_display): ?>
            (1 tahun s.d. <?php echo $tgl_filter_display ?>)
          <?php else: ?>
            (1 tahun terakhir)
          <?php endif ?>
        </span>
      </span>
      <!-- Filter aktif badge (disembunyikan saat tidak ada filter) -->
      <span class="pb-filter-badge" style="display:none;align-items:center;gap:6px">
        <span style="background:#dbeafe;color:#1d4ed8;border-radius:12px;
                     padding:2px 10px;font-size:10px;font-weight:700;text-transform:none;letter-spacing:0">
          <i class="fa fa-filter"></i>
          <span class="pb-filter-label"></span>
        </span>
        <a href="#" class="pb-filter-reset"
           style="font-size:10px;color:#dc2626;font-weight:600;text-transform:none;letter-spacing:0;text-decoration:none"
           title="Tampilkan semua unit">
          <i class="fa fa-times-circle"></i> Semua Unit
        </a>
      </span>
    </div>

    <?php if (empty($mutasi)): ?>
      <div style="text-align:center;color:#94a3b8;padding:20px;font-size:11px;font-style:italic">
        <i class="fa fa-info-circle"></i> Tidak ada riwayat mutasi.
      </div>
    <?php else: ?>
    <div style="max-height:320px;overflow-y:auto">
      <table style="width:100%;font-size:11px;border-collapse:collapse">
        <thead>
          <tr style="background:#f1f5f9;position:sticky;top:0;z-index:1">
            <th style="padding:5px 7px;border:1px solid #e2e8f0;text-align:center;width:24px">#</th>
            <th style="padding:5px 7px;border:1px solid #e2e8f0;width:110px">Tanggal &amp; Jam</th>
            <th style="padding:5px 7px;border:1px solid #e2e8f0;width:140px">Unit / Bagian</th>
            <th style="padding:5px 7px;border:1px solid #e2e8f0;text-align:right;width:70px;background:#f0f9ff">Stok Awal</th>
            <th style="padding:5px 7px;border:1px solid #e2e8f0;text-align:right;width:70px;background:#f0fdf4">Masuk</th>
            <th style="padding:5px 7px;border:1px solid #e2e8f0;text-align:right;width:70px;background:#fef2f2">Keluar</th>
            <th style="padding:5px 7px;border:1px solid #e2e8f0;text-align:right;width:70px;background:#fef2f2">Stok Akhir</th>
            <th style="padding:5px 7px;border:1px solid #e2e8f0;width:120px">Jenis Transaksi</th>
            <th style="padding:5px 7px;border:1px solid #e2e8f0">Keterangan</th>
            <th style="padding:5px 7px;border:1px solid #e2e8f0;width:110px">Petugas</th>
          </tr>
        </thead>
        <tbody class="pb-mutasi-tbody">
          <?php foreach ($mutasi as $i => $m):
              $masuk  = (int)$m->pemasukan;
              $keluar = (int)$m->pengeluaran;
              $row_bg = ($i % 2 === 1) ? '#f8fafc' : '';
          ?>
          <tr class="pb-mutasi-row"
              data-kode-bagian="<?php echo htmlspecialchars($m->kode_bagian) ?>"
              style="background:<?php echo $row_bg ?>">
            <td style="padding:4px 7px;border:1px solid #e2e8f0;text-align:center;color:#94a3b8" class="pb-row-num">
              <?php echo $i + 1 ?>
            </td>
            <td style="padding:4px 7px;border:1px solid #e2e8f0;color:#475569;white-space:nowrap">
              <?php echo htmlspecialchars($m->tgl_input) ?>
              <br><span style="font-size:10px;color:#94a3b8"><?php echo htmlspecialchars($m->jam_input) ?></span>
            </td>
            <td style="padding:4px 7px;border:1px solid #e2e8f0;font-size:10px">
              <?php echo htmlspecialchars($m->nama_bagian) ?>
            </td>
            <td style="padding:4px 7px;border:1px solid #e2e8f0;text-align:right;background:#f0f9ff;color:#475569">
              <?php echo number_format((int)$m->stok_awal, 0, ',', '.') ?>
            </td>
            <td style="padding:4px 7px;border:1px solid #e2e8f0;text-align:right;background:#f0fdf4;
                        font-weight:<?php echo $masuk > 0 ? '700' : '400' ?>;
                        color:<?php echo $masuk > 0 ? '#15803d' : '#94a3b8' ?>">
              <?php echo $masuk > 0 ? number_format($masuk, 0, ',', '.') : '-' ?>
            </td>
            <td style="padding:4px 7px;border:1px solid #e2e8f0;text-align:right;background:#fef2f2;
                        font-weight:<?php echo $keluar > 0 ? '700' : '400' ?>;
                        color:<?php echo $keluar > 0 ? '#dc2626' : '#94a3b8' ?>">
              <?php echo $keluar > 0 ? number_format($keluar, 0, ',', '.') : '-' ?>
            </td>
            <td style="padding:4px 7px;border:1px solid #e2e8f0;text-align:right;background:#fef2f2;color:#475569;font-weight:700">
                <?php echo number_format((int)$m->stok_akhir, 0, ',', '.') ?>
            </td>
            <td style="padding:4px 7px;border:1px solid #e2e8f0">
              <?php if ($m->nama_jenis): ?>
                <span style="display:inline-block;background:#e0f2fe;color:#0369a1;border-radius:10px;
                             padding:1px 7px;font-size:10px;font-weight:600">
                  <?php echo htmlspecialchars($m->nama_jenis) ?>
                </span>
              <?php else: ?>
                <span style="color:#94a3b8">-</span>
              <?php endif ?>
            </td>
            <td style="padding:4px 7px;border:1px solid #e2e8f0;color:#374151;font-size:11px">
              <?php echo $m->keterangan ? htmlspecialchars($m->keterangan) : '<span style="color:#94a3b8">-</span>' ?>
            </td>
            <td style="padding:4px 7px;border:1px solid #e2e8f0;color:#475569;font-size:10px">
              <?php echo htmlspecialchars($m->nama_petugas ?: '-') ?>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>

      <!-- Pesan saat semua row tersembunyi oleh filter -->
      <div class="pb-mutasi-no-result"
           style="display:none;text-align:center;padding:16px;color:#94a3b8;font-size:11px;font-style:italic">
        <i class="fa fa-info-circle"></i>
        Tidak ada mutasi untuk unit ini.
      </div>
    </div>
    <?php endif ?>

  </div><!-- end mutasi section -->

</div><!-- end pb-detail-container -->
