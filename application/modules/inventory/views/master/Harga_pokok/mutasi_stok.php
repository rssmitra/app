<?php
$rows        = isset($rows)        ? $rows        : array();
$kode_brg    = isset($kode_brg)    ? $kode_brg    : '-';
$kode_bagian = isset($kode_bagian) ? $kode_bagian : '-';
$nama_bagian = isset($nama_bagian) ? $nama_bagian : $kode_bagian;

// ── Hitung Summary ──
$total_rec   = count($rows);
$total_masuk = 0;
$total_keluar = 0;
$stok_akhir  = 0;   // dari record terbaru (index 0 karena ORDER BY DESC)

foreach ($rows as $i => $r) {
    $total_masuk  += (int)$r->pemasukan;
    $total_keluar += (int)$r->pengeluaran;
    if ($i === 0) $stok_akhir = (int)$r->stok_akhir;
}

// Mapping jenis_transaksi → label
$jenis_label = array(
    1  => array('label' => 'Penerimaan PO',      'color' => '#16a34a', 'bg' => '#dcfce7'),
    2  => array('label' => 'Distribusi Keluar',   'color' => '#dc2626', 'bg' => '#fee2e2'),
    3  => array('label' => 'Retur ke Gudang',     'color' => '#0891b2', 'bg' => '#e0f2fe'),
    4  => array('label' => 'Mutasi Masuk',        'color' => '#16a34a', 'bg' => '#dcfce7'),
    5  => array('label' => 'Pemakaian',           'color' => '#dc2626', 'bg' => '#fee2e2'),
    6  => array('label' => 'Koreksi',             'color' => '#d97706', 'bg' => '#fef3c7'),
    7  => array('label' => 'Pemakaian BHP',       'color' => '#7c3aed', 'bg' => '#ede9fe'),
    10 => array('label' => 'Stok Opname',         'color' => '#0369a1', 'bg' => '#dbeafe'),
    23 => array('label' => 'Rollback / Batal',    'color' => '#9f1239', 'bg' => '#ffe4e6'),
);
?>

<!-- ── Summary Cards ── -->
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px">

    <div style="flex:1;min-width:110px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:12px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#3b82f6;letter-spacing:.4px">Total Record</div>
        <div style="font-size:26px;font-weight:900;color:#1d4ed8;line-height:1.1;margin-top:4px"><?php echo $total_rec ?></div>
        <div style="font-size:10px;color:#64748b">mutasi (90 hari)</div>
    </div>

    <div style="flex:1;min-width:130px;background:#f0fdf4;border:2px solid #86efac;border-radius:8px;padding:12px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#16a34a;letter-spacing:.4px">
            <i class="fa fa-arrow-down"></i> Total Masuk
        </div>
        <div style="font-size:22px;font-weight:900;color:#15803d;line-height:1.1;margin-top:4px">
            <?php echo number_format($total_masuk, 0, ',', '.') ?>
        </div>
        <div style="font-size:10px;color:#64748b">unit diterima</div>
    </div>

    <div style="flex:1;min-width:130px;background:#fef2f2;border:2px solid #fca5a5;border-radius:8px;padding:12px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#dc2626;letter-spacing:.4px">
            <i class="fa fa-arrow-up"></i> Total Keluar
        </div>
        <div style="font-size:22px;font-weight:900;color:#dc2626;line-height:1.1;margin-top:4px">
            <?php echo number_format($total_keluar, 0, ',', '.') ?>
        </div>
        <div style="font-size:10px;color:#64748b">unit dikeluarkan</div>
    </div>

    <div style="flex:2;min-width:160px;background:<?php echo ($stok_akhir <= 0) ? 'linear-gradient(135deg,#dc2626,#b91c1c)' : 'linear-gradient(135deg,#0891b2,#0e7490)' ?>;
                border-radius:8px;padding:12px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:rgba(255,255,255,.85);letter-spacing:.4px">
            <i class="fa fa-cubes"></i> Stok Akhir Saat Ini
        </div>
        <div style="font-size:28px;font-weight:900;color:#fff;line-height:1.1;margin-top:4px">
            <?php echo number_format($stok_akhir, 0, ',', '.') ?>
        </div>
        <?php if ($stok_akhir <= 0) : ?>
        <div style="margin-top:4px;display:inline-block;background:rgba(255,255,255,.2);color:#fff;
                    border-radius:10px;padding:2px 8px;font-size:10px;font-weight:700">HABIS</div>
        <?php else : ?>
        <div style="font-size:10px;color:rgba(255,255,255,.75);margin-top:2px">dari record terakhir</div>
        <?php endif ?>
    </div>

</div>

<!-- ── Tabel Mutasi ── -->
<?php if ($total_rec > 0) : ?>
<div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#475569;
            border-bottom:2px solid #e2e8f0;padding-bottom:6px;margin-bottom:10px">
    <i class="fa fa-exchange" style="color:#0891b2"></i>&nbsp; Detail Mutasi Stok
    <small style="font-size:10px;font-weight:400;color:#94a3b8;text-transform:none">
        &mdash; 90 hari terakhir, maks. 200 baris
    </small>
</div>
<div class="table-responsive" style="max-height:380px;overflow-y:auto">
    <table style="width:100%;font-size:12px;border-collapse:collapse">
        <thead>
            <tr style="background:#f1f5f9;position:sticky;top:0;z-index:1">
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:center;width:30px">#</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;width:100px">Tanggal</th>
                
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#f8fafc;width:80px">Stok Awal</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#dcfce7;width:100px">Masuk (+)</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#fee2e2;width:100px">Keluar (-)</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#e0f2fe;width:90px">Stok Akhir</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;width:130px;text-align:center">Jenis Transaksi</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0">Keterangan</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;width:120px">Petugas</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $i => $r) :
            $jenis  = (int)$r->jenis_transaksi;
            $jmap   = isset($jenis_label[$jenis]) ? $jenis_label[$jenis] : array('label' => 'Mutasi', 'color' => '#475569', 'bg' => '#f1f5f9');
            $is_in  = (int)$r->pemasukan  > 0;
            $is_out = (int)$r->pengeluaran > 0;
            $row_bg = '';
            if ($i % 2 === 1) $row_bg = 'background:#f8fafc';
        ?>
            <tr style="<?php echo $row_bg ?>">
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:center;color:#94a3b8">
                    <?php echo $i + 1 ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;font-size:11px">
                    <?php echo date('d/m/Y', strtotime($r->tgl_input)) ?>
                    <br><small style="color:#94a3b8"><?php echo date('H:i', strtotime($r->tgl_input)) ?></small>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;color:#475569">
                    <?php echo number_format((int)$r->stok_awal, 0, ',', '.') ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;
                            background:<?php echo $is_in ? '#f0fdf4' : '' ?>;
                            font-weight:<?php echo $is_in ? '700' : '400' ?>;
                            color:<?php echo $is_in ? '#15803d' : '#94a3b8' ?>">
                    <?php echo $is_in ? number_format((int)$r->pemasukan, 0, ',', '.') : '-' ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;
                            background:<?php echo $is_out ? '#fff7f7' : '' ?>;
                            font-weight:<?php echo $is_out ? '700' : '400' ?>;
                            color:<?php echo $is_out ? '#dc2626' : '#94a3b8' ?>">
                    <?php echo $is_out ? number_format((int)$r->pengeluaran, 0, ',', '.') : '-' ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;font-weight:700;
                            background:#f0f9ff;color:#0369a1">
                    <?php echo number_format((int)$r->stok_akhir, 0, ',', '.') ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:center">
                    <span style="display:inline-block;border-radius:4px;padding:2px 7px;font-size:10px;font-weight:700;
                                 background:<?php echo $jmap['bg'] ?>;color:<?php echo $jmap['color'] ?>">
                        <?php echo $jmap['label'] ?>
                    </span>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;font-size:11px;color:#374151">
                    <?php echo $r->keterangan ? htmlspecialchars($r->keterangan) : '<span style="color:#cbd5e1">-</span>' ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;font-size:11px;color:#475569">
                    <?php echo htmlspecialchars($r->nama_lengkap ?: $r->nama_petugas ?: '-') ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr style="background:#f1f5f9;font-weight:700;border-top:2px solid #cbd5e1">
                <td colspan="2" style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;color:#374151">
                    Total
                </td>
                <td style="padding:6px 8px;border:1px solid #e2e8f0"></td>
                <td style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#dcfce7;color:#15803d">
                    <?php echo number_format($total_masuk, 0, ',', '.') ?>
                </td>
                <td style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#fee2e2;color:#dc2626">
                    <?php echo number_format($total_keluar, 0, ',', '.') ?>
                </td>
                <td style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#dbeafe;color:#0369a1;font-size:13px">
                    <?php echo number_format($stok_akhir, 0, ',', '.') ?>
                </td>
                <td style="padding:6px 8px;border:1px solid #e2e8f0"></td>
            </tr>
        </tfoot>
    </table>
</div>
<div style="margin-top:8px;font-size:10px;color:#94a3b8">
    * Data ditampilkan 90 hari terakhir, maksimal 200 baris terbaru &bull;
    <strong>Stok Akhir</strong> = saldo stok setelah transaksi berjalan
</div>

<?php else : ?>
<div style="text-align:center;color:#94a3b8;padding:30px;font-size:12px;font-style:italic">
    <i class="fa fa-info-circle"></i> Tidak ada data mutasi stok ditemukan untuk depo ini dalam 90 hari terakhir.
</div>
<?php endif ?>
