<style>
.lhs-log-header {
    margin-bottom: 16px;
}
.lhs-log-header h4 {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 2px;
}
.lhs-log-header small {
    color: #64748b;
    font-size: 11px;
}

/* ── Timeline cards ── */
.lhs-timeline {
    position: relative;
    padding-left: 32px;
}
.lhs-timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 6px;
    bottom: 6px;
    width: 2px;
    background: #e2e8f0;
}
.lhs-tl-item {
    position: relative;
    margin-bottom: 18px;
}
.lhs-tl-dot {
    position: absolute;
    left: -27px;
    top: 12px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #0891b2;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #0891b2;
}
.lhs-tl-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-left: 3px solid #0891b2;
    border-radius: 6px;
    padding: 12px 16px;
}
.lhs-tl-date {
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
    margin-bottom: 4px;
}
.lhs-tl-bagian {
    font-size: 13px;
    font-weight: 700;
    color: #0891b2;
    margin-bottom: 8px;
}
.lhs-tl-table {
    width: 100%;
    max-width: 480px;
    border-collapse: collapse;
    font-size: 12px;
    margin-bottom: 8px;
}
.lhs-tl-table thead th {
    background: #f1f5f9;
    color: #475569;
    font-weight: 600;
    padding: 5px 10px;
    border: 1px solid #e2e8f0;
    text-align: left;
}
.lhs-tl-table tbody td {
    padding: 5px 10px;
    border: 1px solid #e2e8f0;
    color: #1e293b;
}
.lhs-tl-meta {
    font-size: 11px;
    color: #64748b;
    margin-top: 4px;
}
.lhs-tl-meta b {
    color: #1e293b;
}
.lhs-badge-aktif    { color: #16a34a; font-weight: 700; }
.lhs-badge-nonaktif { color: #dc2626; font-weight: 700; }
</style>

<div class="lhs-log-header">
  <h4><i class="fa fa-history" style="color:#0891b2"></i> Log Rincian Barang — Hasil Stok Opname</h4>
  <small><?php echo $title?></small>
</div>

<div class="lhs-timeline">
  <?php if (empty($log_barang)): ?>
    <div class="alert alert-info" style="font-size:12px">
      <i class="fa fa-info-circle"></i> Belum ada log untuk barang ini.
    </div>
  <?php else: ?>
    <?php foreach($log_barang as $row):
        $harga_satuan_kecil = ($row->content > 0) ? ($row->harga_pembelian_terakhir / $row->content) : 0;
        $total = $harga_satuan_kecil * $row->stok_sekarang;
    ?>
    <div class="lhs-tl-item">
      <div class="lhs-tl-dot"></div>
      <div class="lhs-tl-card">
        <div class="lhs-tl-date">
          <i class="fa fa-calendar-o"></i>
          <?php echo $this->tanggal->formatDateTime($row->tgl_stok_opname) ?>
        </div>
        <div class="lhs-tl-bagian">
          <i class="fa fa-building-o"></i> <?php echo ucwords($row->nama_bagian) ?>
        </div>
        <table class="lhs-tl-table">
          <thead>
            <tr>
              <th>Stok Sebelum</th>
              <th>Hasil SO</th>
              <th class="text-right">Harga Satuan</th>
              <th class="text-right">Total (Rp)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo number_format($row->stok_sebelum) ?></td>
              <td><?php echo number_format($row->stok_sekarang) ?></td>
              <td align="right"><?php echo number_format($harga_satuan_kecil, 0, ',', '.') ?></td>
              <td align="right"><?php echo number_format($total, 0, ',', '.') ?></td>
            </tr>
          </tbody>
        </table>
        <div class="lhs-tl-meta">
          Diinput oleh <b><?php echo ucwords($row->nama_petugas) ?></b> &mdash;
          Status barang:
          <?php if ($row->set_status_aktif == 0): ?>
            <span class="lhs-badge-nonaktif">Tidak Aktif</span>
          <?php else: ?>
            <span class="lhs-badge-aktif">Aktif</span>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
