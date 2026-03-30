<style>
  .det-wrap { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; margin: 6px 0 10px; }
  .det-hdr { background: #1a4f8a; color: #fff; padding: 8px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  .det-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
  .det-tbl thead tr { background: #2c6fad; color: #fff; }
  .det-tbl thead th { padding: 8px 10px; text-align: center; font-weight: 600; border: 1px solid #1e5590; }
  .det-tbl tbody tr:nth-child(even) { background: #f5f9fd; }
  .det-tbl tbody tr:hover { background: #e8f0f9; }
  .det-tbl tbody td { padding: 7px 10px; border: 1px solid #d0dce8; vertical-align: middle; }
  .info-block { padding: 10px 14px; font-size: 12px; background: #f8fafd; border-bottom: 1px solid #d0dce8; }
  .info-block table { width: 100%; }
  .info-block table td:first-child { width: 160px; color: #555; font-weight: 600; padding: 3px 0; }
  .catatan-box { padding: 8px 14px; font-size: 12px; background: #f8fafd; border-top: 1px solid #d0dce8; font-style: italic; }
</style>

<div class="det-wrap">
  <div class="det-hdr">
    <i class="fa fa-cube"></i> Permintaan Stok Unit
    <span style="margin-left:auto; font-weight:400; opacity:.85">No. <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->nomor_permintaan:'-'?></span>
  </div>
  <div class="info-block">
    <table>
      <tr>
        <td>No Permintaan</td>
        <td>: <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->nomor_permintaan:'-'?></td>
      </tr>
      <tr>
        <td>Tanggal</td>
        <td>: <?php echo isset($dt_detail_brg[0])?$this->tanggal->formatDateDmy($dt_detail_brg[0]->tgl_permintaan):'-'?></td>
      </tr>
      <tr>
        <td>Bagian / Unit</td>
        <td>: <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->nama_bagian:'-'?></td>
      </tr>
    </table>
  </div>
  <table class="det-tbl">
    <thead>
      <tr>
        <th width="35px">No</th>
        <th width="120px">Kode Barang</th>
        <th>Nama Barang</th>
        <th width="160px">Jumlah Permintaan</th>
      </tr>
    </thead>
    <tbody>
      <?php $no=0; foreach($dt_detail_brg as $row_dt) : $no++; ?>
      <tr>
        <td class="center"><?php echo $no?></td>
        <td><?php echo $row_dt->kode_brg?></td>
        <td><?php echo $row_dt->nama_brg?></td>
        <td class="center"><?php echo $row_dt->jumlah_permintaan; ?> <?php echo $row_dt->satuan_kecil?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php if(isset($dt_detail_brg[0]) && $dt_detail_brg[0]->catatan): ?>
  <div class="catatan-box">
    <strong>Keterangan:</strong> <?php echo ucfirst($dt_detail_brg[0]->catatan)?>
  </div>
  <?php endif; ?>
</div>

<div class="det-wrap">
  <div class="det-hdr"><i class="fa fa-check-circle-o"></i> Verifikasi Permintaan</div>
  <div class="info-block">
    <table>
      <tr>
        <td>No Verifikasi</td>
        <td>: <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->no_acc:'-'?></td>
      </tr>
      <tr>
        <td>Tanggal Verifikasi</td>
        <td>: <?php echo isset($dt_detail_brg[0]) ? (($dt_detail_brg[0]->tgl_acc != '0000-00-00 00:00:00') ? $this->tanggal->formatDateDmy($dt_detail_brg[0]->tgl_acc) : '-') : '-'?></td>
      </tr>
      <tr>
        <td>Disetujui Oleh</td>
        <td>: <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->acc_by:'-'?></td>
      </tr>
      <tr>
        <td>Status Verifikasi</td>
        <td>: <?php echo isset($dt_detail_brg[0]) ? (($dt_detail_brg[0]->status_acc == 1) ? '<span style="color:green;font-weight:600">Disetujui</span>' : '<span style="color:red;font-weight:600">Ditolak</span>') : '-'?></td>
      </tr>
    </table>
  </div>
</div>

<div class="det-wrap">
  <div class="det-hdr"><i class="fa fa-truck"></i> Distribusi &amp; Penerimaan Barang</div>
  <div class="info-block">
    <table>
      <tr>
        <td>No. Pengiriman</td>
        <td>: <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->nomor_pengiriman:'-'?></td>
      </tr>
      <tr>
        <td>Yang Menyerahkan</td>
        <td>: <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->yg_serah:'-'?></td>
      </tr>
      <tr>
        <td>Tanggal Diterima</td>
        <td>: <?php echo isset($dt_detail_brg[0]) ? (($dt_detail_brg[0]->tgl_input_terima != '0000-00-00 00:00:00') ? $this->tanggal->formatDateDmy($dt_detail_brg[0]->tgl_input_terima) : '-') : '-'?></td>
      </tr>
      <tr>
        <td>Diterima Oleh</td>
        <td>: <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->yg_terima:'-'?></td>
      </tr>
    </table>
  </div>
</div>
