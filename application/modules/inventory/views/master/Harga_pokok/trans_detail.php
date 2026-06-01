<?php
$rows     = isset($rows)     ? $rows     : array();
$tgl_fmt  = isset($tgl)      ? date('d F Y', strtotime($tgl)) : '-';
$kode_brg = isset($kode_brg) ? $kode_brg : '-';

// ── Hitung Summary ──
$total_trx       = count($rows);
$total_qty       = 0;
$sum_harga_sat   = 0;
$count_harga_sat = 0; // hanya baris yang harga_satuan-nya tidak null
$total_bill      = 0;
$total_bersih    = 0;
$total_hpp       = 0;
$pasien_unik     = array();

foreach ($rows as $r) {
    $total_qty     += (int)$r->jumlah;
    $total_bill    += (int)$r->bill_rs;
    $total_bersih  += (int)$r->nilai_bersih;
    $pasien_unik[$r->no_mr] = true;

    if ($r->harga_satuan !== null) {
        $sum_harga_sat   += (int)$r->harga_satuan;
        $count_harga_sat++;
        $total_hpp       += (int)$r->jumlah * (int)$r->harga_satuan;
    }
}
$avg_harga_sat  = ($count_harga_sat > 0) ? round($sum_harga_sat / $count_harga_sat) : 0;
$avg_harga_jual = ($total_qty  > 0) ? round($total_bersih / $total_qty)  : 0;
$jml_pasien     = count($pasien_unik);
$total_margin   = $total_bersih - $total_hpp;
$margin_pct     = ($total_hpp > 0) ? round($total_margin / $total_hpp * 100, 1) : 0;
?>

<!-- ── Summary Kartu Row 1 ── -->
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:10px">

    <div style="flex:1;min-width:110px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:12px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#3b82f6;letter-spacing:.4px">Total Transaksi</div>
        <div style="font-size:26px;font-weight:900;color:#1d4ed8;line-height:1.1;margin-top:4px"><?php echo $total_trx ?></div>
        <div style="font-size:10px;color:#64748b">baris</div>
    </div>

    <div style="flex:1;min-width:110px;background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:12px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#16a34a;letter-spacing:.4px">Pasien Unik</div>
        <div style="font-size:26px;font-weight:900;color:#15803d;line-height:1.1;margin-top:4px"><?php echo $jml_pasien ?></div>
        <div style="font-size:10px;color:#64748b">pasien</div>
    </div>

    <div style="flex:1;min-width:110px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#475569;letter-spacing:.4px">Total Qty</div>
        <div style="font-size:26px;font-weight:900;color:#0f172a;line-height:1.1;margin-top:4px"><?php echo number_format($total_qty, 0, ',', '.') ?></div>
        <div style="font-size:10px;color:#64748b">unit terjual</div>
    </div>

    <div style="flex:2;min-width:160px;background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:12px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#0369a1;letter-spacing:.4px">Avg Harga Satuan (HPP)</div>
        <div style="font-size:18px;font-weight:800;color:#0369a1;line-height:1.2;margin-top:4px">
            Rp <?php echo number_format($avg_harga_sat, 0, ',', '.') ?>
        </div>
        <div style="font-size:10px;color:#64748b">rata-rata harga pokok / unit</div>
    </div>

    <div style="flex:2;min-width:160px;background:linear-gradient(135deg,#0891b2,#0e7490);border-radius:8px;padding:12px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#e0f2fe;letter-spacing:.4px">Avg Harga Jual / Unit</div>
        <div style="font-size:18px;font-weight:800;color:#fff;line-height:1.2;margin-top:4px">
            Rp <?php echo number_format($avg_harga_jual, 0, ',', '.') ?>
        </div>
        <div style="font-size:10px;color:#bae6fd">total bersih ÷ total qty</div>
    </div>

</div>

<!-- ── Summary Kartu Row 2: Pendapatan & Margin ── -->
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px">

    <div style="flex:1;min-width:160px;background:#f0f9ff;border:2px solid #bae6fd;border-radius:8px;padding:12px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#0369a1;letter-spacing:.4px">
            <i class="fa fa-minus-circle" style="color:#0369a1"></i> Total Pendapatan HPP
        </div>
        <div style="font-size:18px;font-weight:800;color:#0369a1;line-height:1.2;margin-top:4px">
            Rp <?php echo number_format($total_hpp, 0, ',', '.') ?>
        </div>
        <div style="font-size:10px;color:#64748b">Σ(qty &times; harga satuan)</div>
    </div>

    <div style="flex:1;min-width:160px;background:#f0fdf4;border:2px solid #86efac;border-radius:8px;padding:12px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#15803d;letter-spacing:.4px">
            <i class="fa fa-plus-circle" style="color:#15803d"></i> Total Pendapatan Jual
        </div>
        <div style="font-size:18px;font-weight:800;color:#15803d;line-height:1.2;margin-top:4px">
            Rp <?php echo number_format($total_bersih, 0, ',', '.') ?>
        </div>
        <div style="font-size:10px;color:#64748b">Σ(bill_rs &minus; 500)</div>
    </div>

    <?php
    $margin_bg     = ($total_margin >= 0) ? 'linear-gradient(135deg,#16a34a,#15803d)' : 'linear-gradient(135deg,#dc2626,#b91c1c)';
    $margin_icon   = ($total_margin >= 0) ? 'fa-arrow-up' : 'fa-arrow-down';
    $margin_label  = ($total_margin >= 0) ? 'UNTUNG' : 'RUGI';
    ?>
    <div style="flex:2;min-width:200px;background:<?php echo $margin_bg ?>;border-radius:8px;padding:14px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:rgba(255,255,255,.85);letter-spacing:.4px">
            <i class="fa <?php echo $margin_icon ?>"></i> Margin / Keuntungan
        </div>
        <div style="font-size:22px;font-weight:900;color:#fff;line-height:1.2;margin-top:6px">
            Rp <?php echo number_format(abs($total_margin), 0, ',', '.') ?>
        </div>
        <div style="margin-top:4px">
            <span style="display:inline-block;background:rgba(255,255,255,.2);color:#fff;border-radius:12px;
                         padding:2px 10px;font-size:11px;font-weight:700">
                <?php echo $margin_label ?> &nbsp;<?php echo $margin_pct ?>%
            </span>
        </div>
        <div style="font-size:10px;color:rgba(255,255,255,.7);margin-top:4px">Total Jual &minus; Total HPP</div>
    </div>

</div>

<!-- ── Tabel Detail Transaksi ── -->
<?php if ($total_trx > 0) : ?>
<div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#475569;
            border-bottom:2px solid #e2e8f0;padding-bottom:6px;margin-bottom:10px">
    <i class="fa fa-list" style="color:#0891b2"></i>&nbsp; Detail Per Transaksi
</div>
<div class="table-responsive" style="max-height:340px;overflow-y:auto">
    <table style="width:100%;font-size:12px;border-collapse:collapse">
        <thead>
            <tr style="background:#f1f5f9;position:sticky;top:0;z-index:1">
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:center;width:30px">#</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:center;width:90px">No. RM</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0">Nama Pasien</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0">Nama Tindakan</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;width:50px">Qty</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#e0f2fe;width:110px">Harga Satuan (HPP)</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#fef9c3;width:110px">Harga Jual / Unit</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#e0f2fe;width:110px">Total HPP</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#d1fae5;width:110px">Total Bersih</th>
                <th style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#f0fdf4;width:100px">Margin</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $i => $r) :
            $nilai_bersih_row = (int)$r->nilai_bersih;
            $jumlah_row       = max((int)$r->jumlah, 1);
            $harga_jual_unit  = round($nilai_bersih_row / $jumlah_row);
            $has_hpp          = ($r->harga_satuan !== null);
            $total_hpp_row    = $has_hpp ? ((int)$r->jumlah * (int)$r->harga_satuan) : null;
            $margin_row       = $has_hpp ? ($nilai_bersih_row - $total_hpp_row) : null;
            $margin_color     = ($margin_row === null || $margin_row >= 0) ? '#15803d' : '#dc2626';
            $margin_bg_row    = ($margin_row === null || $margin_row >= 0) ? '#f0fdf4' : '#fef2f2';

        ?>
            <tr style="<?php echo ($i % 2 === 1) ? 'background:#f8fafc' : '' ?>">
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:center;color:#94a3b8">
                    <?php echo $i + 1 ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;font-weight:600;color:#0369a1;text-align:center">
                    <?php echo htmlspecialchars($r->no_mr) ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;font-weight:600">
                    <?php echo htmlspecialchars($r->nama_pasien) ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;color:#475569;font-size:11px">
                    <?php echo htmlspecialchars($r->nama_tindakan) ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;font-weight:700">
                    <?php echo number_format((int)$r->jumlah, 0, ',', '.') ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;background:#f0f9ff">
                    <?php if ($has_hpp): ?>Rp <?php echo number_format((int)$r->harga_satuan, 0, ',', '.') ?>
                    <?php else: ?><span style="color:#94a3b8">-</span><?php endif ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;background:#fefce8">
                    Rp <?php echo number_format($harga_jual_unit, 0, ',', '.') ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;background:#f0f9ff">
                    <?php if ($has_hpp): ?>Rp <?php echo number_format($total_hpp_row, 0, ',', '.') ?>
                    <?php else: ?><span style="color:#94a3b8">-</span><?php endif ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;background:#f0fdf4;font-weight:700">
                    Rp <?php echo number_format($nilai_bersih_row, 0, ',', '.') ?>
                </td>
                <td style="padding:5px 8px;border:1px solid #e2e8f0;text-align:right;font-weight:700;
                            background:<?php echo $margin_bg_row ?>;color:<?php echo $margin_color ?>">
                    <?php if ($margin_row !== null): ?>
                        <?php echo ($margin_row >= 0) ? '' : '&minus;' ?>Rp <?php echo number_format(abs($margin_row), 0, ',', '.') ?>
                    <?php else: ?><span style="opacity:.5">-</span><?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr style="background:#f1f5f9;font-weight:700;border-top:2px solid #cbd5e1">
                <td colspan="4" style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;color:#374151">
                    Total
                </td>
                <td style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right">
                    <?php echo number_format($total_qty, 0, ',', '.') ?>
                </td>
                <td style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#e0f2fe">
                    <small style="font-weight:400">avg</small> Rp <?php echo number_format($avg_harga_sat, 0, ',', '.') ?>
                    <?php if ($count_harga_sat < $total_trx): ?>
                    <div style="font-size:9px;color:#64748b;font-weight:400"><?php echo $count_harga_sat ?>/<?php echo $total_trx ?> baris</div>
                    <?php endif ?>
                </td>
                <td style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#fef9c3">
                    <small style="font-weight:400">avg</small> Rp <?php echo number_format($avg_harga_jual, 0, ',', '.') ?>
                </td>
                <td style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#e0f2fe;color:#0369a1;font-size:13px">
                    Rp <?php echo number_format($total_hpp, 0, ',', '.') ?>
                </td>
                <td style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;background:#d1fae5;color:#15803d;font-size:13px">
                    Rp <?php echo number_format($total_bersih, 0, ',', '.') ?>
                </td>
                <?php
                $foot_margin_color = ($total_margin >= 0) ? '#15803d' : '#dc2626';
                $foot_margin_bg    = ($total_margin >= 0) ? '#dcfce7' : '#fee2e2';
                ?>
                <td style="padding:6px 8px;border:1px solid #e2e8f0;text-align:right;font-weight:800;font-size:13px;
                            background:<?php echo $foot_margin_bg ?>;color:<?php echo $foot_margin_color ?>">
                    <?php echo ($total_margin >= 0) ? '' : '&minus;' ?>Rp <?php echo number_format(abs($total_margin), 0, ',', '.') ?>
                    <div style="font-size:10px;font-weight:600;opacity:.75"><?php echo $margin_pct ?>%</div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<div style="margin-top:8px;font-size:10px;color:#94a3b8">
    * <strong>Total HPP</strong> = qty &times; harga satuan &bull;
    <strong>Total Bersih</strong> = Bill RS &minus; 500 &bull;
    <strong>Margin</strong> = Total Bersih &minus; Total HPP
</div>

<?php else : ?>
<div style="text-align:center;color:#94a3b8;padding:30px;font-size:12px;font-style:italic">
    <i class="fa fa-info-circle"></i> Tidak ada data transaksi ditemukan untuk tanggal ini.
</div>
<?php endif ?>
