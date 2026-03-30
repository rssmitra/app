<style>
  .det-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
  .det-tbl thead tr { background: #2c6fad; color: #fff; }
  .det-tbl thead th { padding: 8px 10px; text-align: center; font-weight: 600; border: 1px solid #1e5590; white-space: nowrap; }
  .det-tbl thead th.text-left { text-align: left; }
  .det-tbl tbody tr:nth-child(even) { background: #f5f9fd; }
  .det-tbl tbody tr:hover { background: #e8f0f9; }
  .det-tbl tbody td { padding: 7px 10px; border: 1px solid #d0dce8; vertical-align: middle; }
  .det-tbl tbody td.center { text-align: center; }
  .det-tbl tbody td.right { text-align: right; }
  .det-tbl tfoot td { padding: 7px 10px; border: 1px solid #c0cfe0; background: #eaf1fa; font-weight: 700; font-size: 12px; text-align: right; }

  .det-wrap { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; margin: 6px 0; }
  .det-hdr { background: #1a4f8a; color: #fff; padding: 8px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; }

  .badge-ok  { display:inline-flex;align-items:center;gap:4px;background:#e8f5e9;color:#2e7d32;border:1px solid #a5d6a7;border-radius:10px;padding:2px 8px;font-size:11px;font-weight:600; }
  .badge-warn{ display:inline-flex;align-items:center;gap:4px;background:#fff3e0;color:#e65100;border:1px solid #ffcc80;border-radius:10px;padding:2px 8px;font-size:11px;font-weight:600; }
  .badge-batch{ display:inline-block;background:#e3f2fd;color:#1565c0;border:1px solid #90caf9;border-radius:3px;padding:1px 6px;font-size:11px; }
  .badge-exp  { display:inline-block;background:#fff3e0;color:#e65100;border:1px solid #ffcc80;border-radius:3px;padding:1px 6px;font-size:11px; }
</style>

<div class="det-wrap">
  <div class="det-hdr">
    <i class="fa fa-list-alt"></i> Rincian Barang Penerimaan
    <span style="font-weight:400;font-size:11px;opacity:.85;margin-left:4px">&mdash; No. PO: <?php echo isset($po_data[0])?$po_data[0]->no_po:'-'; ?></span>
  </div>

  <table class="det-tbl">
    <thead>
      <tr>
        <th width="32px">No</th>
        <th class="text-left">Kode &amp; Nama Barang</th>
        <th width="55px">Isi Kemasan</th>
        <th width="65px">Satuan</th>
        <th width="75px">Jml Pesan</th>
        <th width="80px">Jml Kirim</th>
        <th width="85px">Harga Netto<br><small style="font-weight:400">(Rp)</small></th>
        <th width="100px">Total Netto<br><small style="font-weight:400">(Rp)</small></th>
        <th width="80px">Diskon</th>
        <th width="120px">No. Batch</th>
        <th width="90px">Exp. Date</th>
      </tr>
    </thead>
    <tbody>
    <?php
      $no = 0;
      $grand_total = 0;
      foreach($po_data as $row_dt) :
        $no++;
        $is_lengkap = !($row_dt->jumlah_besar_acc > $row_dt->jumlah_kirim);
        $grand_total += $row_dt->total_harga_netto;
    ?>
      <tr>
        <td class="center"><?php echo $no?></td>
        <td>
          <span style="font-size:10px;color:#999"><?php echo $row_dt->kode_brg?></span><br>
          <strong><?php echo $row_dt->nama_brg?></strong>
        </td>
        <td class="center"><?php echo $row_dt->content?></td>
        <td class="center"><?php echo $row_dt->satuan_besar?></td>
        <td class="center"><?php echo number_format($row_dt->jumlah_besar_acc, 2)?></td>
        <td class="center">
          <?php if($is_lengkap): ?>
            <span class="badge-ok"><i class="fa fa-check"></i> <?php echo number_format($row_dt->jumlah_kirim, 2)?></span>
          <?php else: ?>
            <span class="badge-warn"><i class="fa fa-exclamation-triangle"></i> <?php echo ($row_dt->jumlah_kirim)? number_format($row_dt->jumlah_kirim, 2):0; ?></span>
          <?php endif; ?>
        </td>
        <td class="right"><?php echo number_format($row_dt->harga_satuan_netto)?></td>
        <td class="right"><strong><?php echo number_format($row_dt->total_harga_netto)?></strong></td>
        <td class="center">
          <?php if(!empty($row_dt->discount)): ?>
            <span style="font-size:11px"><?php echo number_format($row_dt->discount, 2)?>%</span>
          <?php else: ?>
            <span style="color:#ccc">—</span>
          <?php endif; ?>
        </td>
        <td class="center">
          <?php if(!empty($row_dt->no_batch)): ?>
            <span class="badge-batch"><i class="fa fa-barcode"></i> <?php echo $row_dt->no_batch?></span><br>
            <small style="color:#999;font-size:10px"><?php echo $row_dt->kode_box?> / <?php echo $row_dt->kode_pcs?></small>
          <?php else: ?>
            <span style="color:#ccc">—</span>
          <?php endif; ?>
        </td>
        <td class="center">
          <?php if(!empty($row_dt->tgl_expired)): ?>
            <?php if($row_dt->is_expired == 'N'): ?>
              <span style="font-size:11px;color:#888">Tdk Exp</span>
            <?php else: ?>
              <span class="badge-exp"><?php echo $row_dt->tgl_expired?></span>
            <?php endif; ?>
          <?php else: ?>
            <span style="color:#ccc">—</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="7" style="text-align:right;color:#555;font-weight:400;font-size:11px;border:1px solid #c0cfe0;background:#eaf1fa">Total Harga Netto</td>
        <td style="border:1px solid #c0cfe0;background:#eaf1fa">Rp <?php echo number_format($grand_total)?></td>
        <td colspan="3" style="border:1px solid #c0cfe0;background:#eaf1fa"></td>
      </tr>
    </tfoot>
  </table>
</div>
