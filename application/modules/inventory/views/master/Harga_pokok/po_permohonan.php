<?php
$p    = $perm; // alias pendek
$rasio = (isset($p->rasio) && (int)$p->rasio > 0) ? (int)$p->rasio : 1;

// jumlah_stok_sebelumnya tersimpan dalam satuan kecil → konversi ke satuan besar
$stok_kecil = (float)$p->jumlah_stok_sebelumnya;
$stok_besar = $stok_kecil / $rasio;
?>

<!-- ── Header Permohonan ── -->
<div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:6px;padding:12px 16px;margin-bottom:14px">
    <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">
        <div style="flex:1;min-width:200px">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b">No. Permohonan</div>
            <div style="font-size:15px;font-weight:800;color:#0369a1;margin-top:2px">
                <?php echo htmlspecialchars($p->kode_permohonan) ?>
            </div>
        </div>
        <div style="flex:1;min-width:150px">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b">Tanggal Usulan</div>
            <div style="font-size:13px;font-weight:600;color:#0f172a;margin-top:2px">
                <?php echo $p->tgl_usulan ? date('d/m/Y', strtotime($p->tgl_usulan)) : '-' ?>
            </div>
        </div>
        <div style="flex:1;min-width:150px">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b">Tgl Permohonan</div>
            <div style="font-size:13px;font-weight:600;color:#0f172a;margin-top:2px">
                <?php echo $p->tgl_permohonan ? date('d/m/Y', strtotime($p->tgl_permohonan)) : '-' ?>
            </div>
        </div>
        <div style="flex:2;min-width:200px">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b">Bagian Pemohon</div>
            <div style="font-size:13px;font-weight:600;color:#0f172a;margin-top:2px">
                <?php echo $p->nama_bagian ? htmlspecialchars($p->nama_bagian) : '<span style="color:#94a3b8">-</span>' ?>
            </div>
        </div>
    </div>
    <?php if ($p->keterangan_permohonan) : ?>
    <div style="margin-top:10px;font-size:12px;color:#475569;border-top:1px solid #bae6fd;padding-top:8px">
        <i class="fa fa-comment-o" style="color:#0891b2"></i>
        <em><?php echo htmlspecialchars($p->keterangan_permohonan) ?></em>
    </div>
    <?php endif ?>
</div>

<!-- ── Stok Sebelumnya (highlight utama) ── -->
<div style="display:flex;gap:12px;margin-bottom:14px;flex-wrap:wrap">

    <div style="flex:1;min-width:160px;background:<?php echo ($stok_besar <= 0) ? '#fef2f2' : '#f0fdf4' ?>;
                border:2px solid <?php echo ($stok_besar <= 0) ? '#fca5a5' : '#86efac' ?>;
                border-radius:8px;padding:14px 18px;text-align:center">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;
                    color:<?php echo ($stok_besar <= 0) ? '#dc2626' : '#16a34a' ?>">
            <i class="fa fa-cubes"></i> Stok Saat Usulan Dibuat
        </div>
        <div style="font-size:28px;font-weight:900;
                    color:<?php echo ($stok_besar <= 0) ? '#dc2626' : '#16a34a' ?>;
                    line-height:1.1;margin-top:6px">
            <?php echo number_format($stok_besar, 0, ',', '.') ?>
        </div>
        <div style="font-size:12px;color:#64748b;margin-top:2px">
            <?php echo strtolower(htmlspecialchars($p->satuan_besar)) ?>
            <?php if ($rasio > 1) : ?>
            <span style="font-size:10px;color:#94a3b8">(<?php echo number_format($stok_kecil, 0, ',', '.') ?> sat. kecil ÷ <?php echo $rasio ?>)</span>
            <?php endif ?>
        </div>
        <?php if ($stok_besar <= 0) : ?>
        <div style="margin-top:6px;font-size:10px;background:#dc2626;color:#fff;border-radius:10px;padding:2px 8px;display:inline-block;font-weight:700">
            STOK HABIS / NOL
        </div>
        <?php else : ?>
        <div style="margin-top:6px;font-size:10px;background:#16a34a;color:#fff;border-radius:10px;padding:2px 8px;display:inline-block;font-weight:700">
            STOK MENIPIS
        </div>
        <?php endif ?>
    </div>

    <!-- ── Alur Persetujuan Usulan ── -->
    <div style="flex:3;min-width:260px">
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#475569;
                    border-bottom:2px solid #e2e8f0;padding-bottom:6px;margin-bottom:10px">
            <i class="fa fa-check-square-o" style="color:#0891b2"></i> Alur Persetujuan Usulan
        </div>
        <table style="width:100%;font-size:12px;border-collapse:collapse">
            <tr>
                <td style="padding:5px 8px;color:#64748b;width:55%">Jumlah Usulan Awal dari Unit</td>
                <td style="padding:5px 8px;font-weight:700;text-align:right">
                    <?php echo number_format((float)$p->jumlah_usulan, 0, ',', '.') ?>
                    <small style="font-weight:400;color:#94a3b8"><?php echo strtolower(htmlspecialchars($p->satuan_besar)) ?></small>
                </td>
            </tr>
            <tr style="background:#f8fafc">
                <td style="padding:5px 8px;color:#64748b">Jumlah Disetujui oleh Pemeriksa</td>
                <td style="padding:5px 8px;font-weight:700;text-align:right">
                    <?php
                    $acc_pemeriksa = (float)$p->jml_acc_pemeriksa;
                    echo $acc_pemeriksa > 0
                        ? number_format($acc_pemeriksa, 0, ',', '.') . ' <small style="font-weight:400;color:#94a3b8">' . strtolower(htmlspecialchars($p->satuan_besar)) . '</small>'
                        : '<span style="color:#94a3b8">-</span>';
                    ?>
                </td>
            </tr>
            <tr>
                <td style="padding:5px 8px;color:#64748b">Jumlah Disetujui oleh Verifikator</td>
                <td style="padding:5px 8px;font-weight:700;text-align:right">
                    <?php
                    $acc_penyetuju = (float)$p->jml_acc_penyetuju;
                    echo $acc_penyetuju > 0
                        ? number_format($acc_penyetuju, 0, ',', '.') . ' <small style="font-weight:400;color:#94a3b8">' . strtolower(htmlspecialchars($p->satuan_besar)) . '</small>'
                        : '<span style="color:#94a3b8">-</span>';
                    ?>
                </td>
            </tr>
            <tr style="background:#eff6ff;border-top:2px solid #bfdbfe">
                <td style="padding:6px 8px;color:#1d4ed8;font-weight:700">Jumlah ACC Final (untuk PO)</td>
                <td style="padding:6px 8px;font-weight:800;color:#1d4ed8;text-align:right;font-size:13px">
                    <?php
                    $acc_penyetuju = (float)$p->jml_acc_penyetuju;
                    echo $acc_penyetuju > 0
                        ? number_format($acc_penyetuju, 0, ',', '.') . ' <small style="font-weight:400;color:#94a3b8">' . strtolower(htmlspecialchars($p->satuan_besar)) . '</small>'
                        : '<span style="color:#94a3b8">-</span>';
                    ?>
                </td>
            </tr>
        </table>
    </div>

</div>

<!-- ── Status & Keterangan Item ── -->
<div style="display:flex;gap:12px;flex-wrap:wrap">
    <div style="flex:1;min-width:160px">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;margin-bottom:4px">
            Status PO Item
        </div>
        <?php
        $status = strtolower(trim($p->status_po));
        $badge_style = 'display:inline-block;border-radius:4px;padding:3px 10px;font-size:11px;font-weight:700;';
        if ($status === 'selesai' || $status === 'done' || $status === '1') {
            echo '<span style="' . $badge_style . 'background:#16a34a;color:#fff">SELESAI</span>';
        } elseif ($status === 'batal' || $status === 'cancel') {
            echo '<span style="' . $badge_style . 'background:#dc2626;color:#fff">BATAL</span>';
        } elseif ($p->status_po) {
            echo '<span style="' . $badge_style . 'background:#0891b2;color:#fff">' . htmlspecialchars(strtoupper($p->status_po)) . '</span>';
        } else {
            echo '<span style="color:#94a3b8;font-size:12px">-</span>';
        }
        ?>
    </div>
    <?php if ($p->keterangan_item) : ?>
    <div style="flex:3;min-width:200px">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;margin-bottom:4px">
            Keterangan Item
        </div>
        <div style="font-size:12px;color:#374151;background:#f8fafc;border:1px solid #e2e8f0;border-radius:4px;padding:6px 10px">
            <?php echo htmlspecialchars($p->keterangan_item) ?>
        </div>
    </div>
    <?php endif ?>
</div>
