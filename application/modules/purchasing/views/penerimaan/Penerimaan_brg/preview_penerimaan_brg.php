<style>
  .doc-wrap { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; color: #2c2c2c; background: #fff; padding: 24px; }
  .doc-header { display: flex; align-items: center; gap: 18px; padding-bottom: 14px; border-bottom: 3px solid #2c6fad; margin-bottom: 16px; }
  .doc-header img { width: 70px; }
  .doc-header-company { flex: 1; }
  .doc-header-company .comp-name { font-size: 17px; font-weight: 700; color: #1a4f8a; }
  .doc-header-company .comp-addr { font-size: 11px; color: #666; margin-top: 3px; }
  .doc-header-barcode { text-align: right; }

  .doc-title-wrap { text-align: center; margin: 10px 0 18px; }
  .doc-title-wrap .doc-badge { display: inline-block; background: #2c6fad; color: #fff; font-size: 14px; font-weight: 700; letter-spacing: 1px; padding: 6px 30px; border-radius: 3px; }
  .doc-title-wrap .doc-subtitle { font-size: 11px; color: #888; margin-top: 4px; }

  .doc-info-wrap { display: flex; gap: 16px; margin-bottom: 18px; }
  .doc-info-box { flex: 1; border: 1px solid #d8e4ef; border-radius: 4px; padding: 12px 14px; background: #f7fafd; }
  .doc-info-box .box-title { font-size: 10px; font-weight: 700; color: #2c6fad; text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid #d8e4ef; padding-bottom: 6px; margin-bottom: 8px; }
  .doc-info-row { display: flex; gap: 6px; margin-bottom: 5px; font-size: 12px; }
  .doc-info-label { color: #666; min-width: 135px; }
  .doc-info-value { font-weight: 600; color: #222; }

  .doc-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; font-size: 12px; }
  .doc-table thead tr { background: #2c6fad; color: #fff; }
  .doc-table thead th { padding: 8px 10px; text-align: center; font-weight: 600; border: 1px solid #1e5590; white-space: nowrap; }
  .doc-table thead th.text-left { text-align: left; }
  .doc-table tbody tr:nth-child(even) { background: #f0f5fb; }
  .doc-table tbody tr:hover { background: #e2edf9; }
  .doc-table tbody td { padding: 7px 10px; border: 1px solid #d0dce8; vertical-align: middle; }
  .doc-table tbody td.center { text-align: center; }
  .doc-table tbody td.right { text-align: right; }
  .doc-table tfoot tr { background: #eaf1fa; font-weight: 700; }
  .doc-table tfoot td { padding: 7px 10px; border: 1px solid #c0cfe0; }

  .badge-batch { display: inline-block; background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; border-radius: 3px; padding: 1px 6px; font-size: 10px; }
  .badge-exp { display: inline-block; background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; border-radius: 3px; padding: 1px 6px; font-size: 10px; }
  .badge-exp-no { display: inline-block; background: #f3e5f5; color: #6a1b9a; border: 1px solid #ce93d8; border-radius: 3px; padding: 1px 6px; font-size: 10px; }

  .doc-summary { display: flex; justify-content: flex-end; margin-top: 4px; margin-bottom: 20px; }
  .doc-summary-box { border: 1px solid #d0dce8; border-radius: 4px; min-width: 280px; overflow: hidden; }
  .doc-summary-row { display: flex; justify-content: space-between; padding: 6px 14px; border-bottom: 1px solid #e8eef5; font-size: 12px; }
  .doc-summary-row:last-child { border-bottom: none; background: #2c6fad; color: #fff; font-weight: 700; font-size: 13px; }
  .doc-summary-row.last { background: #2c6fad; color: #fff; }

  .doc-sign-wrap { display: flex; gap: 20px; margin-top: 20px; margin-bottom: 24px; }
  .doc-sign-box { flex: 1; text-align: center; border: 1px solid #d8e4ef; border-radius: 4px; padding: 10px 12px; background: #fafcff; }
  .doc-sign-box .sign-title { font-size: 11px; color: #555; margin-bottom: 55px; }
  .doc-sign-box .sign-name { font-weight: 700; border-top: 1px solid #999; padding-top: 5px; font-size: 12px; }

  .doc-actions { display: flex; gap: 10px; justify-content: center; padding-top: 10px; border-top: 1px solid #e0e8f0; }
</style>

<div class="doc-wrap">

  <!-- ===== HEADER ===== -->
  <div class="doc-header">
    <img src="<?php echo base_url().COMP_ICON; ?>" alt="Logo">
    <div class="doc-header-company">
      <div class="comp-name"><?php echo COMP_FULL; ?></div>
      <div class="comp-addr"><?php echo COMP_ADDRESS; ?></div>
    </div>
    <div class="doc-header-barcode">
      <div id="barcodeTarget" class="barcodeTarget"></div>
    </div>
  </div>

  <!-- ===== TITLE ===== -->
  <div class="doc-title-wrap">
    <div class="doc-badge">BUKTI PENERIMAAN BARANG</div>
    <div class="doc-subtitle"><?php echo ($flag=='medis') ? 'Gudang Medis' : 'Gudang Non Medis'; ?></div>
  </div>

  <!-- ===== INFO SECTION ===== -->
  <div class="doc-info-wrap">

    <div class="doc-info-box">
      <div class="box-title"><i class="fa fa-file-text-o"></i> &nbsp;Informasi Penerimaan</div>
      <div class="doc-info-row"><span class="doc-info-label">No. Penerimaan</span><span class="doc-info-value"><?php echo $penerimaan->kode_penerimaan; ?></span></div>
      <div class="doc-info-row"><span class="doc-info-label">Tanggal Penerimaan</span><span class="doc-info-value"><?php echo $this->tanggal->formatDatedmY($penerimaan->tgl_penerimaan); ?></span></div>
      <div class="doc-info-row"><span class="doc-info-label">Nomor PO</span><span class="doc-info-value"><?php echo $penerimaan->no_po; ?></span></div>
      <div class="doc-info-row"><span class="doc-info-label">Penerima</span><span class="doc-info-value"><?php echo $penerimaan->petugas; ?></span></div>
      <div class="doc-info-row"><span class="doc-info-label">Dikirim Oleh</span><span class="doc-info-value"><?php echo $penerimaan->dikirim; ?></span></div>
    </div>

    <div class="doc-info-box">
      <div class="box-title"><i class="fa fa-truck"></i> &nbsp;Informasi Supplier</div>
      <div class="doc-info-row"><span class="doc-info-label">Nama Supplier</span><span class="doc-info-value"><?php echo $penerimaan->namasupplier; ?></span></div>
      <div class="doc-info-row"><span class="doc-info-label">Alamat</span><span class="doc-info-value"><?php echo $penerimaan->alamat; ?></span></div>
      <div class="doc-info-row"><span class="doc-info-label">Telepon</span><span class="doc-info-value"><?php echo $penerimaan->telpon1; ?></span></div>
      <div class="doc-info-row"><span class="doc-info-label">Jenis Permintaan</span><span class="doc-info-value">Rutin</span></div>
    </div>

  </div>

  <!-- ===== TABLE ===== -->
  <table class="doc-table">
    <thead>
      <tr>
        <th rowspan="2" style="width:30px">No</th>
        <th rowspan="2" class="text-left">Kode &amp; Nama Barang</th>
        <th rowspan="2" style="width:50px">Rasio</th>
        <th rowspan="2" style="width:65px">Satuan</th>
        <th colspan="3" style="background:#1e5590">Jumlah</th>
        <th rowspan="2" style="width:90px">Harga Satuan<br><small style="font-weight:400">(Rp)</small></th>
        <th colspan="2" style="background:#1e5590">Diskon</th>
        <th rowspan="2" style="width:90px">Total Harga<br><small style="font-weight:400">(Rp)</small></th>
        <th rowspan="2">No. Batch / Kemasan</th>
        <th rowspan="2">Exp. Date</th>
      </tr>
      <tr>
        <th style="background:#1e5590;width:55px">Pesan</th>
        <th style="background:#1e5590;width:55px">Sblm</th>
        <th style="background:#1e5590;width:55px">Skrg</th>
        <th style="background:#1e5590;width:45px">%</th>
        <th style="background:#1e5590;width:75px">Rp</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $no = 0;
        $grand_total = 0;
        $grand_dpp   = 0;
        $grand_ppn   = 0;
        $total_diterima = [];

        foreach ($penerimaan_data as $key_dt => $row_dt) :
          $no++;
          $total_diterima = [];
          if (count($row_dt) > 0) {
            foreach ($row_dt as $key_row => $row_sub_data) {
              if ($key_row != 0) {
                $total_diterima[] = $row_sub_data->jumlah_kirim_decimal;
              }
            }
          }
          $item      = $row_dt[0];
          $total_harga_item = $item->harga_satuan * $item->jumlah_kirim_decimal;
          $grand_total += $total_harga_item;
      ?>
      <tr>
        <td class="center"><?php echo $no; ?></td>
        <td>
          <span style="color:#888;font-size:11px"><?php echo $item->kode_brg; ?></span><br>
          <strong><?php echo $item->nama_brg; ?></strong>
        </td>
        <td class="center"><?php echo $item->content; ?></td>
        <td class="center"><?php echo $item->satuan_besar; ?></td>
        <td class="center"><?php echo number_format($item->jumlah_pesan_decimal); ?></td>
        <td class="center"><?php echo number_format(array_sum($total_diterima)); ?></td>
        <td class="center"><strong><?php echo number_format($item->jumlah_kirim_decimal); ?></strong></td>
        <td class="right"><?php echo number_format($item->harga_satuan); ?></td>
        <td class="center"><?php echo number_format($item->discount, 2); ?>%</td>
        <td class="right"><?php echo number_format($item->discount_rp); ?></td>
        <td class="right"><strong><?php echo number_format($total_harga_item); ?></strong></td>
        <td class="center">
          <?php if (!empty($item->no_batch)): ?>
            <span class="badge-batch"><?php echo $item->no_batch; ?></span><br>
            <small style="color:#999;font-size:10px"><?php echo $item->kode_box; ?> / <?php echo $item->kode_pcs; ?></small>
          <?php else: ?>
            <span style="color:#ccc">—</span>
          <?php endif; ?>
        </td>
        <td class="center">
          <?php if (!empty($item->tgl_expired)): ?>
            <?php if ($item->is_expired == 'N'): ?>
              <span class="badge-exp-no">Tdk Exp</span>
            <?php else: ?>
              <span class="badge-exp"><?php echo $this->tanggal->formatDatedmY($item->tgl_expired); ?></span>
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
        <td colspan="10" style="text-align:right;font-size:11px;color:#555;border:1px solid #c0cfe0">Total Harga Barang</td>
        <td class="right" style="border:1px solid #c0cfe0"><?php echo number_format($grand_total); ?></td>
        <td colspan="2" style="border:1px solid #c0cfe0"></td>
      </tr>
    </tfoot>
  </table>

  <!-- ===== SUMMARY ===== -->
  <div class="doc-summary">
    <div class="doc-summary-box">
      <?php
        $dpp  = isset($penerimaan->total_sbl_ppn) ? $penerimaan->total_sbl_ppn : $grand_total;
        $ppn_rate = isset($penerimaan->ppn) ? $penerimaan->ppn : 11;
        $ppn_val  = $dpp * ($ppn_rate / 100);
        $total_inc_ppn = isset($penerimaan->total_stl_ppn) ? $penerimaan->total_stl_ppn : ($dpp + $ppn_val);
      ?>
      <div class="doc-summary-row">
        <span>DPP (Dasar Pengenaan Pajak)</span>
        <span>Rp <?php echo number_format($dpp); ?></span>
      </div>
      <div class="doc-summary-row">
        <span>PPN (<?php echo $ppn_rate; ?>%)</span>
        <span>Rp <?php echo number_format($ppn_val); ?></span>
      </div>
      <div class="doc-summary-row last">
        <span>Total Faktur</span>
        <span>Rp <?php echo number_format($total_inc_ppn); ?></span>
      </div>
    </div>
  </div>

  <!-- ===== SIGNATURE ===== -->
  <div class="doc-sign-wrap">
    <div class="doc-sign-box">
      <div class="sign-title">Dikirim Oleh</div>
      <div class="sign-name"><?php echo $penerimaan->dikirim; ?></div>
    </div>
    <div class="doc-sign-box">
      <div class="sign-title">Diterima Oleh</div>
      <div class="sign-name"><?php echo $penerimaan->petugas; ?></div>
    </div>
    <div class="doc-sign-box">
      <div class="sign-title">Mengetahui</div>
      <div class="sign-name">&nbsp;</div>
    </div>
    <div class="doc-sign-box">
      <div class="sign-title">Disetujui</div>
      <div class="sign-name">&nbsp;</div>
    </div>
  </div>

  <!-- ===== ACTIONS ===== -->
  <div class="doc-actions">
    <button class="btn btn-sm btn-default" onclick="getMenu('purchasing/penerimaan/Riwayat_penerimaan_brg/view_data?flag=<?php echo $flag?>')">
      <i class="fa fa-arrow-left"></i> Kembali ke Riwayat
    </button>
    <button class="btn btn-sm btn-warning" onclick="PopupCenter('purchasing/penerimaan/Riwayat_penerimaan_brg/print_preview_penerimaan?ID=<?php echo $id_penerimaan; ?>&flag=<?php echo $flag?>', 'Cetak BAST', 900, 650)">
      <i class="fa fa-print"></i> Cetak BAST
    </button>
    <button class="btn btn-sm btn-primary" onclick="getMenu('purchasing/pendistribusian/Pengiriman_unit/form_pengiriman_unit?ID=<?php echo $id_penerimaan; ?>&flag=<?php echo $flag?>')">
      <i class="fa fa-share-square-o"></i> Distribusikan ke Unit/Depo
    </button>
  </div>

</div>
