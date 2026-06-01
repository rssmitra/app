<style>
.hp-detail-info {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 10px 14px;
    margin-bottom: 14px;
}
.hp-detail-info .hp-kode {
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.hp-detail-info .hp-nama {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin-top: 2px;
}
.hp-detail-info .hp-harga-now {
    font-size: 18px;
    font-weight: 800;
    color: #0891b2;
    margin-top: 4px;
}
.hp-section-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #475569;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 6px;
    margin: 16px 0 10px;
}
.hp-section-title .fa {
    margin-right: 5px;
    color: #0891b2;
}
.hp-table-detail {
    width: 100%;
    font-size: 12px;
    border-collapse: collapse;
}
.hp-table-detail th {
    background: #f1f5f9;
    padding: 6px 8px;
    border: 1px solid #e2e8f0;
    font-weight: 700;
    color: #374151;
    text-align: center;
}
.hp-table-detail td {
    padding: 5px 8px;
    border: 1px solid #e2e8f0;
    vertical-align: middle;
}
.hp-table-detail tr:nth-child(even) td {
    background: #f8fafc;
}
.hp-badge-po {
    display: inline-block;
    background: #0891b2;
    color: #fff;
    border-radius: 10px;
    padding: 2px 8px;
    font-size: 10px;
    font-weight: 700;
}
.hp-empty {
    text-align: center;
    color: #94a3b8;
    padding: 20px;
    font-size: 12px;
    font-style: italic;
}
.hp-trend-up   { color: #16a34a; font-weight: 700; }
.hp-trend-down { color: #dc2626; font-weight: 700; }
.hp-trend-eq   { color: #64748b; }
</style>

<?php
// Info ringkasan barang
$kode_brg     = isset($kode_brg)     ? $kode_brg     : '-';
$nama_brg     = isset($nama_brg)     ? $nama_brg     : '-';
$flag         = isset($flag)         ? $flag         : 'medis';
$po_hist      = isset($po_history)   ? $po_history   : array();
$sales_hist   = isset($sales_history)? $sales_history: array();
$rasio        = (isset($rasio) && (int)$rasio > 0) ? (int)$rasio : 1;
$satuan_kecil = isset($satuan_kecil) ? $satuan_kecil : '';
$stok_list    = isset($stok_depo)    ? $stok_depo    : array();

// Ambil harga PO terakhir untuk perbandingan trend
$harga_po_terakhir = (count($po_hist) > 0) ? (float)$po_hist[0]->harga_satuan_netto : 0;
?>

<!-- Info Header -->
<div class="hp-detail-info">
    <div class="hp-kode"><?php echo htmlspecialchars($kode_brg) ?></div>
    <div class="hp-nama"><?php echo htmlspecialchars($nama_brg) ?></div>
    <div style="margin-top:6px;font-size:11px;color:#64748b">
        <span class="label <?php echo ($flag === 'non_medis') ? 'label-warning' : 'label-info' ?>" style="font-size:10px">
            <?php echo ($flag === 'non_medis') ? 'Non Medis' : 'Medis' ?>
        </span>
    </div>
</div>

<!-- ── BAGIAN 1: 3 PO TERAKHIR ── -->
<div class="hp-section-title">
    <i class="fa fa-shopping-cart"></i>
    Riwayat 3 PO (Purchase Order) Terakhir
</div>

<?php if (count($po_hist) > 0) : ?>
<div class="table-responsive">
    <table class="hp-table-detail">
        <thead>
            <tr>
                <th width="30" rowspan="2">#</th>
                <th rowspan="2">No PO / Tgl PO</th>
                <th rowspan="2" class="text-right">Harga Awal</th>
                <th rowspan="2" class="text-right">PPN</th>
                <th colspan="3" class="center" style="background:#e0f2fe">Harga per Sat. Besar</th>
                <th colspan="2" class="center" style="background:#d1fae5">Per Sat. Kecil<?php if ($satuan_kecil) echo ' (' . htmlspecialchars($satuan_kecil) . ')' ?></th>
                
                <th rowspan="2" class="text-right">Qty</th>
                <th rowspan="2" class="text-right">Total PO Netto</th>
            </tr>
            <tr>
                <th class="text-right" style="background:#e0f2fe">Sebelum Diskon</th>
                <th class="center"     style="background:#e0f2fe">Diskon (%)</th>
                <th class="text-right" style="background:#e0f2fe">Setelah Diskon</th>
                <th class="text-right" style="background:#d1fae5">Sebelum Diskon</th>
                <th class="text-right" style="background:#d1fae5">Setelah Diskon</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($po_hist as $i => $po) :

            // echo "<pre>"; print_r($po); echo "</pre>"; // DEBUG
            $ppn = $po->harga_satuan * (1 + (float)$po->ppn / 100) - $po->harga_satuan;
            // harga sebelum diskon
            $harga_sebelum_diskon = (float)$po->harga_satuan + $ppn;
            $harga_setelah_diskon = ($po->discount > 0) ? (float)$harga_sebelum_diskon * (1 - (float)$po->discount / 100) : (float)$harga_sebelum_diskon;
            $harga_netto          = $harga_setelah_diskon;
            $jumlah_harga_netto  = $harga_netto * (float)$po->jumlah_besar;
            
            // Trend vs PO sebelumnya
            $trend_html = '';
            if ($i === 0) {
                $trend_html = ' <span class="hp-badge-po">Terbaru</span>';
            } else {
                $prev_po          = $po_hist[$i - 1];
                $prev_ppn_val     = (float)$prev_po->harga_satuan * (float)$prev_po->ppn / 100;
                $prev_sblm_diskon = (float)$prev_po->harga_satuan + $prev_ppn_val;
                $prev_netto       = ($prev_po->discount > 0)
                    ? $prev_sblm_diskon * (1 - (float)$prev_po->discount / 100)
                    : (float)$prev_po->harga_satuan;
                if ($harga_netto < $prev_netto) {
                    $trend_html = ' <i class="fa fa-arrow-down hp-trend-down" title="Lebih murah dari PO sebelumnya"></i>';
                } elseif ($harga_netto > $prev_netto) {
                    $trend_html = ' <i class="fa fa-arrow-up hp-trend-up" title="Lebih mahal dari PO sebelumnya"></i>';
                } else {
                    $trend_html = ' <i class="fa fa-minus hp-trend-eq" title="Sama dengan PO sebelumnya"></i>';
                }
            }
        ?>
            <tr>
                <td class="center"><?php echo $i + 1 ?></td>
                <td>
                    <a href="#" class="btn-po-perm"
                       data-id="<?php echo (int)$po->id_tc_po_det ?>"
                       data-flag="<?php echo htmlspecialchars($flag) ?>"
                       data-nopo="<?php echo htmlspecialchars($po->no_po, ENT_QUOTES) ?>"
                       style="font-weight:700;color:#0369a1;text-decoration:underline dotted"
                       title="Klik: lihat usulan/permohonan dasar PO ini">
                        <?php echo htmlspecialchars($po->no_po) ?>
                    </a>
                    <?php echo $trend_html ?>
                    <br><small class="text-muted"><?php echo $po->tgl_po ? date('d/m/Y', strtotime($po->tgl_po)) : '-' ?></small>
                </td>
                <td class="text-right">
                    Rp <?php echo number_format((float)$po->harga_satuan, 0, ',', '.') ?>
                </td>
                <td class="text-right">
                    Rp <?php echo number_format((float)$ppn, 0, ',', '.') ?>
                </td>
                <td class="text-right">
                    Rp <?php echo number_format((float)$harga_sebelum_diskon, 0, ',', '.') ?>
                </td>
                <td class="center">
                    <?php echo number_format((float)$po->discount, 2, ',', '.') ?>%
                </td>
                <td class="text-right">
                    <strong>Rp <?php echo number_format($harga_netto, 0, ',', '.') ?></strong>
                </td>
                <td class="text-right" style="background:#f0fdf4">
                    Rp <?php echo number_format((int)round((float)$harga_sebelum_diskon / $rasio), 0, ',', '.') ?>
                </td>
                <td class="text-right" style="background:#f0fdf4">
                    <strong>Rp <?php echo number_format((int)round($harga_netto / $rasio), 0, ',', '.') ?></strong>
                </td>
                <td class="text-right">
                    <?php echo number_format((float)$po->jumlah_besar, 0, ',', '.') ?>
                </td>
                <td class="text-right">
                    Rp <?php echo number_format((float)$jumlah_harga_netto, 0, ',', '.') ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>

<?php if (count($po_hist) > 1) :
    $po_tua  = $po_hist[count($po_hist) - 1];
    $po_baru = $po_hist[0];

    // Rumus sama dengan baris tabel: PPN dulu → sebelum_diskon, lalu kurangi diskon → netto
    $oldest_ppn_val     = (float)$po_tua->harga_satuan * (float)$po_tua->ppn / 100;
    $oldest_sblm_diskon = (float)$po_tua->harga_satuan + $oldest_ppn_val;
    $oldest             = ($po_tua->discount > 0)
        ? $oldest_sblm_diskon * (1 - (float)$po_tua->discount / 100)
        : (float)$po_tua->harga_satuan;

    $newest_ppn_val     = (float)$po_baru->harga_satuan * (float)$po_baru->ppn / 100;
    $newest_sblm_diskon = (float)$po_baru->harga_satuan + $newest_ppn_val;
    $newest             = ($po_baru->discount > 0)
        ? $newest_sblm_diskon * (1 - (float)$po_baru->discount / 100)
        : (float)$po_baru->harga_satuan;

    $selisih = $newest - $oldest;
    $pct     = ($oldest > 0) ? round(($selisih / $oldest) * 100, 2) : 0;
    $naik    = $selisih > 0;
?>
    <div style="margin-top:8px;font-size:11px;color:#64748b;text-align:right">
        Perubahan harga dari PO tertua ke terbaru:
        <strong class="<?php echo $naik ? 'hp-trend-up' : 'hp-trend-down' ?>">
            <?php echo $naik ? '+' : '' ?><?php echo number_format($pct, 2) ?>%
            (<?php echo $naik ? '+' : '' ?>Rp <?php echo number_format(abs($selisih), 0, ',', '.') ?>)
        </strong>
    </div>
<?php endif ?>

<?php
// ── Weighted Average Harga Modal ──
// Harga modal = harga setelah diskon (rumus: harga_satuan + PPN → dikurangi diskon)
// Estimasi harga jual = nilai tertinggi harga_sebelum_diskon / rasio dari 3 PO
$wa_sum_qty     = 0;
$wa_sum_modal   = 0;   // WA dari harga netto (setelah diskon) → harga modal
$hpp_max_satuan = 0;   // Nilai tertinggi: harga_satuan + PPN 11% (fixed), sebelum diskon

foreach ($po_hist as $po) {
    $wa_ppn_val      = (float)$po->harga_satuan * (float)$po->ppn / 100;
    $wa_sblm_diskon  = (float)$po->harga_satuan + $wa_ppn_val;        // harga + PPN (untuk WA modal)
    $wa_h_modal      = ($po->discount > 0)
        ? $wa_sblm_diskon * (1 - (float)$po->discount / 100)          // setelah diskon
        : (float)$po->harga_satuan;                                    // tanpa diskon
    $wa_qty          = (float)$po->jumlah_besar;
    $wa_sum_qty     += $wa_qty;
    $wa_sum_modal   += $wa_qty * $wa_h_modal;
    // HPP dasar: harga po sebelum diskon + PPN 11% (tetap)
    $hpp_satuan = (float)$po->harga_satuan * 1.11;
    if ($hpp_satuan > $hpp_max_satuan) {
        $hpp_max_satuan = $hpp_satuan;
    }
}

$wa_modal_besar  = ($wa_sum_qty > 0) ? $wa_sum_modal / $wa_sum_qty : 0;
$wa_modal_kecil  = ($rasio > 0 && $wa_modal_besar > 0) ? $wa_modal_besar / $rasio : $wa_modal_besar;
// HPP dasar per sat kecil = max(harga_satuan + PPN 11%) / rasio
$hpp_dasar_kecil      = ($rasio > 0 && $hpp_max_satuan > 0) ? $hpp_max_satuan / $rasio : 0;
// Est. harga jual = HPP dasar × (1 + margin 33.33%)
$est_harga_jual_kecil = $hpp_dasar_kecil * (1 + 33.33 / 100);
?>

<div style="margin-top:14px;border:2px solid #0891b2;border-radius:8px;padding:12px 16px;background:#f0f9ff">
    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#0369a1;margin-bottom:10px">
        <i class="fa fa-calculator"></i>&nbsp; Weighted Average Harga Pokok (dari <?php echo count($po_hist) ?> PO terakhir)
    </div>
    <table style="width:100%;font-size:12px;border-collapse:collapse">

        <tr>
            <td style="padding:4px 8px;color:#475569;width:60%">
                Harga Modal WA per Satuan Besar
                <small style="color:#94a3b8">(WA harga setelah diskon)</small>
            </td>
            <td style="padding:4px 8px;font-weight:700;color:#0f172a;text-align:right">
                Rp <?php echo number_format($wa_modal_besar, 2, ',', '.') ?>
            </td>
        </tr>

        <tr style="background:#e0f2fe">
            <td style="padding:6px 8px;color:#0369a1;font-weight:700">
                Harga Modal WA per Satuan Kecil
                <?php if ($satuan_kecil) echo '<small>(' . htmlspecialchars($satuan_kecil) . ')</small>' ?>
                <small style="font-weight:400;color:#64748b"> &mdash; rasio 1&nbsp;:&nbsp;<?php echo $rasio ?></small>
            </td>
            <td style="padding:6px 8px;font-weight:800;color:#0369a1;font-size:14px;text-align:right">
                Rp <?php echo number_format($wa_modal_kecil, 2, ',', '.') ?>
            </td>
        </tr>

        <tr><td colspan="2"><hr style="margin:8px 0;border-color:#bae6fd"></td></tr>

        <tr style="background:#fefce8">
            <td style="padding:4px 8px;color:#92400e;font-weight:600">
                HPP Dasar per Satuan Kecil
                <?php if ($satuan_kecil) echo '<small>(' . htmlspecialchars($satuan_kecil) . ')</small>' ?>
                <small style="font-weight:400;color:#a16207">
                    &mdash; max(harga satuan PO + PPN 11%) &divide; rasio
                </small>
            </td>
            <td style="padding:4px 8px;font-weight:700;color:#92400e;text-align:right">
                Rp <?php echo number_format($hpp_dasar_kecil, 2, ',', '.') ?>
            </td>
        </tr>

        <tr>
            <td style="padding:4px 8px;color:#475569">
                Estimasi Harga Jual per Satuan Kecil
                <small style="color:#94a3b8">
                    (HPP Dasar &times; margin 33.33% &mdash; PPN sudah termasuk di HPP)
                </small>
            </td>
            <td style="padding:4px 8px;text-align:right">
                <span style="background:#0891b2;color:#fff;border-radius:4px;padding:3px 10px;font-weight:800;font-size:13px">
                    Rp <?php echo number_format($est_harga_jual_kecil, 0, ',', '.') ?>
                </span>
            </td>
        </tr>

    </table>
    <div style="margin-top:8px;font-size:10px;color:#94a3b8">
        * <strong>Harga Modal WA</strong> = &Sigma;(Qty &times; Harga Setelah Diskon) &divide; &Sigma;Qty &bull;
        <strong>Harga Setelah Diskon</strong> = (Harga + PPN) &times; (1 &minus; Diskon%) &bull;
        <strong>HPP Dasar</strong> = max(Harga Satuan &times; 1.11) &divide; Rasio &bull;
        <strong>Est. Harga Jual</strong> = HPP Dasar &times; 1.3333
    </div>
</div>

<?php else : ?>
    <div class="hp-empty"><i class="fa fa-info-circle"></i> Belum ada riwayat PO untuk barang ini.</div>
<?php endif ?>


<!-- ── BAGIAN 2: RIWAYAT HARGA PENJUALAN ── -->
<div class="hp-section-title" style="margin-top:20px">
    <i class="fa fa-line-chart"></i>
    Riwayat Harga Penjualan per Tanggal
    <small style="font-size:10px;font-weight:400;color:#94a3b8;margin-left:6px">(Sumber: Data Transaksi)</small>
</div>

<?php if (count($sales_hist) > 0) :
    // ── Pre-hitung grand total untuk tfoot ──
    $gt_qty        = 0;
    $gt_total_modal = 0;
    $gt_total_jual  = 0;
    foreach ($sales_hist as $tr) {
        $gt_qty         += (int)$tr->total_qty;
        $gt_total_modal += (int)$tr->total_qty * (float)$tr->harga_satuan_avg;
        $gt_total_jual  += (float)$tr->total_nilai;
    }
    $gt_margin     = $gt_total_jual - $gt_total_modal;
    $gt_margin_pct = ($gt_total_modal > 0) ? round($gt_margin / $gt_total_modal * 100, 1) : 0;
?>
<div class="table-responsive" style="max-height:300px;overflow-y:auto">
    <table class="hp-table-detail">
        <thead>
            <tr>
                <th width="25" rowspan="2">#</th>
                <th rowspan="2" width="80">Tanggal</th>
                <th rowspan="2" class="text-right" width="50">Qty</th>
                <th colspan="2" class="center" style="background:#e0f2fe">Harga Satuan</th>
                <th colspan="2" class="center" style="background:#d1fae5">Total Penjualan</th>
                <th rowspan="2" class="text-right" width="100" style="background:#fef9c3">Margin</th>
                <th rowspan="2" class="text-right" width="60" style="background:#fef9c3">%</th>
            </tr>
            <tr>
                <th class="text-right" style="background:#e0f2fe;width:110px">Avg HPP (Modal)</th>
                <th class="text-right" style="background:#e0f2fe;width:110px">Avg Harga Jual</th>
                <th class="text-right" style="background:#d1fae5;width:110px">Berdasarkan HPP</th>
                <th class="text-right" style="background:#d1fae5;width:110px">Berdasarkan Harga Jual</th>
            </tr>
        </thead>
        <tbody>
        <?php $prev_hjual = null; foreach ($sales_hist as $j => $tr) :
            $hs_avg      = (float)$tr->harga_satuan_avg;
            $h_jual      = (float)$tr->harga_satuan;
            $qty         = (int)$tr->total_qty;
            $total_modal = $qty * $hs_avg;
            $total_jual  = (float)$tr->total_nilai;
            $margin      = $total_jual - $total_modal;
            $margin_pct  = ($total_modal > 0) ? round($margin / $total_modal * 100, 1) : 0;
            $m_color     = ($margin >= 0) ? '#15803d' : '#dc2626';
            $m_bg        = ($margin >= 0) ? '#f0fdf4' : '#fef2f2';

            if ($prev_hjual !== null) {
                if ($h_jual < $prev_hjual)      $arrow = ' <i class="fa fa-arrow-down hp-trend-down" title="Turun"></i>';
                elseif ($h_jual > $prev_hjual)  $arrow = ' <i class="fa fa-arrow-up hp-trend-up" title="Naik"></i>';
                else                             $arrow = ' <i class="fa fa-minus hp-trend-eq"></i>';
            } else {
                $arrow = '';
            }
            $prev_hjual = $h_jual;
        ?>
            <tr>
                <td class="center"><?php echo $j + 1 ?></td>
                <td>
                    <a href="#" class="btn-trans-detail"
                       data-kode="<?php echo htmlspecialchars($kode_brg, ENT_QUOTES) ?>"
                       data-tgl="<?php echo htmlspecialchars($tr->tgl, ENT_QUOTES) ?>"
                       data-tglfmt="<?php echo date('d/m/Y', strtotime($tr->tgl)) ?>"
                       style="font-weight:600;color:#0369a1;text-decoration:underline dotted"
                       title="Klik: lihat detail transaksi hari ini">
                        <?php echo date('d/m/Y', strtotime($tr->tgl)) ?>
                    </a>
                </td>
                <td class="text-right" style="font-weight:700">
                    <?php echo number_format($qty, 0, ',', '.') ?>
                </td>
                <td class="text-right" style="background:#f0f9ff">
                    Rp <?php echo number_format($hs_avg, 0, ',', '.') ?>
                </td>
                <td class="text-right" style="background:#f0f9ff">
                    <strong>Rp <?php echo number_format($h_jual, 0, ',', '.') ?></strong><?php echo $arrow ?>
                </td>
                <td class="text-right" style="background:#f0fdf4">
                    Rp <?php echo number_format($total_modal, 0, ',', '.') ?>
                </td>
                <td class="text-right" style="background:#f0fdf4;font-weight:700">
                    Rp <?php echo number_format($total_jual, 0, ',', '.') ?>
                </td>
                <td class="text-right" style="background:<?php echo $m_bg ?>;font-weight:700;color:<?php echo $m_color ?>">
                    <?php echo ($margin >= 0) ? '' : '&minus;' ?>Rp <?php echo number_format(abs($margin), 0, ',', '.') ?>
                </td>
                <td class="text-right" style="background:<?php echo $m_bg ?>;font-weight:700;color:<?php echo $m_color ?>">
                    <?php echo $margin_pct ?>%
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
        <tfoot>
            <?php
            $gm_color = ($gt_margin >= 0) ? '#15803d' : '#dc2626';
            $gm_bg    = ($gt_margin >= 0) ? '#dcfce7'  : '#fee2e2';
            ?>
            <tr style="background:#f1f5f9;font-weight:700;border-top:2px solid #cbd5e1">
                <td colspan="2" class="text-right" style="padding:6px 8px;border:1px solid #e2e8f0">
                    Total
                </td>
                <td class="text-right" style="padding:6px 8px;border:1px solid #e2e8f0">
                    <?php echo number_format($gt_qty, 0, ',', '.') ?>
                </td>
                <td colspan="2" style="padding:6px 8px;border:1px solid #e2e8f0;background:#e0f2fe"></td>
                <td class="text-right" style="padding:6px 8px;border:1px solid #e2e8f0;background:#d1fae5;color:#0369a1">
                    Rp <?php echo number_format($gt_total_modal, 0, ',', '.') ?>
                </td>
                <td class="text-right" style="padding:6px 8px;border:1px solid #e2e8f0;background:#d1fae5;color:#15803d;font-size:13px">
                    Rp <?php echo number_format($gt_total_jual, 0, ',', '.') ?>
                </td>
                <td class="text-right" style="padding:6px 8px;border:1px solid #e2e8f0;background:<?php echo $gm_bg ?>;color:<?php echo $gm_color ?>;font-size:13px">
                    <?php echo ($gt_margin >= 0) ? '' : '&minus;' ?>Rp <?php echo number_format(abs($gt_margin), 0, ',', '.') ?>
                </td>
                <td class="text-right" style="padding:6px 8px;border:1px solid #e2e8f0;background:<?php echo $gm_bg ?>;color:<?php echo $gm_color ?>;font-size:13px">
                    <?php echo $gt_margin_pct ?>%
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<div style="margin-top:6px;font-size:10px;color:#94a3b8">
    * <strong>Total HPP</strong> = Qty &times; Avg HPP &bull;
    <strong>Total Harga Jual</strong> = &Sigma;(bill_rs &minus; 500) &bull;
    <strong>Margin</strong> = Total Jual &minus; Total HPP
</div>
<?php else : ?>
    <div class="hp-empty"><i class="fa fa-info-circle"></i> Belum ada riwayat penjualan untuk barang ini.</div>
<?php endif ?>


<!-- ── BAGIAN 3: STOK PERSEDIAAN PER DEPO ── -->
<div class="hp-section-title" style="margin-top:20px">
    <i class="fa fa-cubes"></i>
    Stok Persediaan per Depo Hari Ini
</div>

<?php
// Harga satuan referensi = Harga Modal WA per Satuan Kecil (dari WA 3 PO terakhir)
// Fallback ke 0 jika tidak ada riwayat PO
$harga_satuan_ref = (isset($wa_modal_kecil) && $wa_modal_kecil > 0)
    ? $wa_modal_kecil
    : 0;

if (count($stok_list) > 0) :
    $total_stok  = 0;
    $total_nilai = 0;
    foreach ($stok_list as $s) {
        $total_stok  += (int)$s->jml_sat_kcl;
        $total_nilai += (int)$s->jml_sat_kcl * $harga_satuan_ref;
    }
?>
<div class="table-responsive">
    <table class="hp-table-detail">
        <thead>
            <tr>
                <th width="30">#</th>
                <th>Depo / Bagian</th>
                <th class="text-right">Stok</th>
                <th>Satuan</th>
                <th class="text-right">Harga Modal WA / Sat. Kecil</th>
                <th class="text-right">Nilai Persediaan</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($stok_list as $k => $s) :
            $nilai = (int)$s->jml_sat_kcl * $harga_satuan_ref;
        ?>
            <tr>
                <td class="center"><?php echo $k + 1 ?></td>
                <td><?php echo htmlspecialchars($s->nama_bagian) ?></td>
                <td class="text-right">
                    <a href="#" class="btn-mutasi-stok"
                       data-kode="<?php echo htmlspecialchars($kode_brg, ENT_QUOTES) ?>"
                       data-bagian="<?php echo htmlspecialchars($s->kode_bagian, ENT_QUOTES) ?>"
                       data-nama="<?php echo htmlspecialchars($s->nama_bagian, ENT_QUOTES) ?>"
                       data-flag="<?php echo htmlspecialchars($flag, ENT_QUOTES) ?>"
                       style="font-weight:700;color:#0369a1;text-decoration:underline dotted"
                       title="Klik: lihat riwayat mutasi stok depo ini">
                        <?php echo number_format((int)$s->jml_sat_kcl, 0, ',', '.') ?>
                    </a>
                </td>
                <td><?php echo htmlspecialchars($s->satuan_kecil) ?></td>
                <td class="text-right" style="background:#f0f9ff">
                    <?php echo $harga_satuan_ref > 0
                        ? 'Rp ' . number_format($harga_satuan_ref, 2, ',', '.')
                        : '<span class="text-muted">-</span>' ?>
                </td>
                <td class="text-right">
                    <?php echo $harga_satuan_ref > 0
                        ? '<strong>Rp ' . number_format($nilai, 0, ',', '.') . '</strong>'
                        : '<span class="text-muted">-</span>' ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr style="background:#f1f5f9;font-weight:700">
                <td colspan="2" class="text-right">Total</td>
                <td class="text-right"><?php echo number_format($total_stok, 0, ',', '.') ?></td>
                <td><?php echo htmlspecialchars($satuan_kecil) ?></td>
                <td class="text-right" style="background:#e0f2fe">
                    <?php echo $harga_satuan_ref > 0
                        ? 'Rp ' . number_format($harga_satuan_ref, 2, ',', '.')
                        : '-' ?>
                </td>
                <td class="text-right">
                    <?php echo $harga_satuan_ref > 0
                        ? '<strong>Rp ' . number_format($total_nilai, 0, ',', '.') . '</strong>'
                        : '-' ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<?php else : ?>
    <div class="hp-empty"><i class="fa fa-info-circle"></i> Tidak ada data stok untuk barang ini.</div>
<?php endif ?>

<?php /* ── Modal Usulan Permohonan (dibuat 1x, dipakai ulang) ── */ ?>
<script>
(function () {
    // Buat modal hanya sekali walau banyak baris yang di-expand
    if ($('#modal-po-perm-hp').length === 0) {
        $('body').append(
            '<div class="modal fade" id="modal-po-perm-hp" tabindex="-1" role="dialog" aria-hidden="true">' +
            '  <div class="modal-dialog modal-lg" role="document">' +
            '    <div class="modal-content">' +
            '      <div class="modal-header" style="background:linear-gradient(135deg,#0f172a,#1e3a5f);color:#fff;border-radius:4px 4px 0 0">' +
            '        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:.8">&times;</button>' +
            '        <h4 class="modal-title" id="modal-po-perm-title">' +
            '          <i class="fa fa-file-text-o"></i> Dasar Usulan / Permohonan PO' +
            '        </h4>' +
            '      </div>' +
            '      <div class="modal-body" id="modal-po-perm-body">' +
            '        <div class="text-center" style="padding:30px">' +
            '          <i class="fa fa-spinner fa-spin fa-2x" style="color:#0891b2"></i>' +
            '          <p style="margin-top:8px;color:#64748b;font-size:12px">Memuat data...</p>' +
            '        </div>' +
            '      </div>' +
            '      <div class="modal-footer">' +
            '        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">' +
            '          <i class="fa fa-times"></i> Tutup' +
            '        </button>' +
            '      </div>' +
            '    </div>' +
            '  </div>' +
            '</div>'
        );
    }

    // Handler klik no_po — pakai delegasi pada body agar tidak dobel-bind
    $(document).off('click.poperm').on('click.poperm', '.btn-po-perm', function (e) {
        e.preventDefault();

        var id   = $(this).data('id');
        var flag = $(this).data('flag');
        var nopo = $(this).data('nopo');
        var base = (typeof _hpBaseUrl !== 'undefined') ? _hpBaseUrl : '';

        $('#modal-po-perm-title').html(
            '<i class="fa fa-file-text-o"></i> Usulan / Permohonan &mdash; <strong>' + nopo + '</strong>'
        );
        $('#modal-po-perm-body').html(
            '<div class="text-center" style="padding:30px">' +
            '<i class="fa fa-spinner fa-spin fa-2x" style="color:#0891b2"></i>' +
            '<p style="margin-top:8px;color:#64748b;font-size:12px">Memuat data...</p></div>'
        );
        $('#modal-po-perm-hp').modal('show');

        $.ajax({
            url:      base + '/get_po_permohonan',
            type:     'GET',
            dataType: 'json',
            data:     { id_tc_po_det: id, flag: flag },
            success: function (res) {
                if (res.status === 200) {
                    $('#modal-po-perm-body').html(res.html);
                } else {
                    $('#modal-po-perm-body').html(
                        '<div class="alert alert-warning" style="margin:14px">' +
                        '<i class="fa fa-info-circle"></i> ' + (res.message || 'Data permohonan tidak ditemukan.') +
                        '</div>'
                    );
                }
            },
            error: function () {
                $('#modal-po-perm-body').html(
                    '<div class="alert alert-danger" style="margin:14px">' +
                    '<i class="fa fa-exclamation-triangle"></i> Koneksi gagal. Silahkan coba lagi.' +
                    '</div>'
                );
            }
        });
    });
    // ── Modal & handler: Detail Transaksi per Tanggal ──
    if ($('#modal-trans-detail-hp').length === 0) {
        $('body').append(
            '<div class="modal fade" id="modal-trans-detail-hp" tabindex="-1" role="dialog" aria-hidden="true">' +
            '  <div class="modal-dialog modal-lg" role="document">' +
            '    <div class="modal-content">' +
            '      <div class="modal-header" style="background:linear-gradient(135deg,#0e7490,#0891b2);color:#fff;border-radius:4px 4px 0 0">' +
            '        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:.8">&times;</button>' +
            '        <h4 class="modal-title" id="modal-trans-detail-title">' +
            '          <i class="fa fa-list-alt"></i> Detail Transaksi Penjualan' +
            '        </h4>' +
            '      </div>' +
            '      <div class="modal-body" id="modal-trans-detail-body" style="padding:16px 20px">' +
            '        <div class="text-center" style="padding:30px">' +
            '          <i class="fa fa-spinner fa-spin fa-2x" style="color:#0891b2"></i>' +
            '          <p style="margin-top:8px;color:#64748b;font-size:12px">Memuat data...</p>' +
            '        </div>' +
            '      </div>' +
            '      <div class="modal-footer">' +
            '        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">' +
            '          <i class="fa fa-times"></i> Tutup' +
            '        </button>' +
            '      </div>' +
            '    </div>' +
            '  </div>' +
            '</div>'
        );
    }

    // ── Modal & handler: Mutasi Stok per Depo ──
    if ($('#modal-mutasi-stok-hp').length === 0) {
        $('body').append(
            '<div class="modal fade" id="modal-mutasi-stok-hp" tabindex="-1" role="dialog" aria-hidden="true">' +
            '  <div class="modal-dialog modal-lg" role="document">' +
            '    <div class="modal-content">' +
            '      <div class="modal-header" style="background:linear-gradient(135deg,#0f172a,#1e3a5f);color:#fff;border-radius:4px 4px 0 0">' +
            '        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:.8">&times;</button>' +
            '        <h4 class="modal-title" id="modal-mutasi-stok-title">' +
            '          <i class="fa fa-exchange"></i> Riwayat Mutasi Stok' +
            '        </h4>' +
            '      </div>' +
            '      <div class="modal-body" id="modal-mutasi-stok-body" style="padding:16px 20px">' +
            '        <div class="text-center" style="padding:30px">' +
            '          <i class="fa fa-spinner fa-spin fa-2x" style="color:#0891b2"></i>' +
            '          <p style="margin-top:8px;color:#64748b;font-size:12px">Memuat data...</p>' +
            '        </div>' +
            '      </div>' +
            '      <div class="modal-footer">' +
            '        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">' +
            '          <i class="fa fa-times"></i> Tutup' +
            '        </button>' +
            '      </div>' +
            '    </div>' +
            '  </div>' +
            '</div>'
        );
    }

    $(document).off('click.mutasistok').on('click.mutasistok', '.btn-mutasi-stok', function (e) {
        e.preventDefault();

        var kode  = $(this).data('kode');
        var bagian = $(this).data('bagian');
        var nama  = $(this).data('nama');
        var flag  = $(this).data('flag');
        var base  = (typeof _hpBaseUrl !== 'undefined') ? _hpBaseUrl : '';

        $('#modal-mutasi-stok-title').html(
            '<i class="fa fa-exchange"></i> Riwayat Mutasi Stok &mdash; <strong>' + nama + '</strong>' +
            ' <small style="font-size:12px;font-weight:400;color:#bae6fd">(' + kode + ')</small>'
        );
        $('#modal-mutasi-stok-body').html(
            '<div class="text-center" style="padding:30px">' +
            '<i class="fa fa-spinner fa-spin fa-2x" style="color:#0891b2"></i>' +
            '<p style="margin-top:8px;color:#64748b;font-size:12px">Memuat data...</p></div>'
        );
        $('#modal-mutasi-stok-hp').modal('show');

        $.ajax({
            url:      base + '/get_mutasi_stok',
            type:     'GET',
            dataType: 'json',
            data:     { kode_brg: kode, kode_bagian: bagian, nama_bagian: nama, flag: flag },
            success: function (res) {
                if (res.status === 200) {
                    $('#modal-mutasi-stok-body').html(res.html);
                } else {
                    $('#modal-mutasi-stok-body').html(
                        '<div class="alert alert-warning" style="margin:14px">' +
                        '<i class="fa fa-info-circle"></i> ' + (res.message || 'Data tidak ditemukan.') +
                        '</div>'
                    );
                }
            },
            error: function () {
                $('#modal-mutasi-stok-body').html(
                    '<div class="alert alert-danger" style="margin:14px">' +
                    '<i class="fa fa-exclamation-triangle"></i> Koneksi gagal. Silahkan coba lagi.' +
                    '</div>'
                );
            }
        });
    });

    $(document).off('click.transdetail').on('click.transdetail', '.btn-trans-detail', function (e) {
        e.preventDefault();

        var kode   = $(this).data('kode');
        var tgl    = $(this).data('tgl');
        var tglfmt = $(this).data('tglfmt');
        var base   = (typeof _hpBaseUrl !== 'undefined') ? _hpBaseUrl : '';

        $('#modal-trans-detail-title').html(
            '<i class="fa fa-list-alt"></i> Detail Transaksi &mdash; <strong>' + tglfmt + '</strong>'
        );
        $('#modal-trans-detail-body').html(
            '<div class="text-center" style="padding:30px">' +
            '<i class="fa fa-spinner fa-spin fa-2x" style="color:#0891b2"></i>' +
            '<p style="margin-top:8px;color:#64748b;font-size:12px">Memuat data...</p></div>'
        );
        $('#modal-trans-detail-hp').modal('show');

        $.ajax({
            url:      base + '/get_trans_detail',
            type:     'GET',
            dataType: 'json',
            data:     { kode_brg: kode, tgl: tgl },
            success: function (res) {
                if (res.status === 200) {
                    $('#modal-trans-detail-body').html(res.html);
                } else {
                    $('#modal-trans-detail-body').html(
                        '<div class="alert alert-warning" style="margin:14px">' +
                        '<i class="fa fa-info-circle"></i> ' + (res.message || 'Data tidak ditemukan.') +
                        '</div>'
                    );
                }
            },
            error: function () {
                $('#modal-trans-detail-body').html(
                    '<div class="alert alert-danger" style="margin:14px">' +
                    '<i class="fa fa-exclamation-triangle"></i> Koneksi gagal. Silahkan coba lagi.' +
                    '</div>'
                );
            }
        });
    });
}());
</script>
