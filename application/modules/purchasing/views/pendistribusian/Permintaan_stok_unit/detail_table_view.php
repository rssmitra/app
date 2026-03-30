<style>
  .det-wrap { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; margin: 6px 0 10px; }
  .det-hdr { background: #1a4f8a; color: #fff; padding: 8px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  .det-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
  .det-tbl thead tr { background: #2c6fad; color: #fff; }
  .det-tbl thead th { padding: 8px 10px; text-align: center; font-weight: 600; border: 1px solid #1e5590; vertical-align: middle; }
  .det-tbl tbody tr:nth-child(even) { background: #f5f9fd; }
  .det-tbl tbody tr:hover { background: #e8f0f9; }
  .det-tbl tbody td { padding: 7px 10px; border: 1px solid #d0dce8; vertical-align: middle; }
  .det-tbl tbody tr.row-unverif { background-color: #efdad3 !important; }
  .info-block { padding: 10px 14px; font-size: 12px; background: #f8fafd; border-bottom: 1px solid #d0dce8; }
  .info-block table { width: 100%; }
  .info-block table td:first-child { width: 180px; color: #555; font-weight: 600; padding: 3px 0; }
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
        <th rowspan="2" width="30px">No</th>
        <th rowspan="2" width="100px">Kode Barang</th>
        <th rowspan="2">Nama Barang</th>
        <th rowspan="2" width="40px">BHP?</th>
        <th rowspan="2" width="90px">Stok Akhir Unit</th>
        <th colspan="2">Permintaan</th>
        <th colspan="2">Verifikasi</th>
        <th colspan="2">Distribusi</th>
        <th colspan="2">Penerimaan</th>
      </tr>
      <tr>
        <th width="60px">Qty</th>
        <th width="110px">Note</th>
        <th width="60px">Qty</th>
        <th width="110px">Note</th>
        <th width="60px">Qty</th>
        <th width="110px">Petugas</th>
        <th width="60px">Qty</th>
        <th width="110px">Penerima</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $no=0;
        foreach($dt_detail_brg as $row_dt) : $no++;
        $is_bhp = ($row_dt->is_bhp == 1)?'<i class="fa fa-check green bigger-120"></i>':'';
        $kode_brg = ($row_dt->rev_kode_brg != null && $row_dt->rev_kode_brg != '') ? '<s style="color: red">'.$row_dt->kode_brg.'</s> &nbsp; '.$row_dt->rev_kode_brg : $row_dt->kode_brg;
        $nama_brg = ($row_dt->rev_kode_brg != null && $row_dt->rev_kode_brg != '') ? '<s style="color: red">'.$row_dt->nama_brg.'</s> &nbsp; '.$row_dt->revisi_nama_brg : $row_dt->nama_brg;
        $qty = ($row_dt->rev_kode_brg != null && $row_dt->rev_kode_brg != '') ? '<s style="color: red">'.$row_dt->jumlah_permintaan.'</s> &nbsp; '.$row_dt->rev_qty : $row_dt->jumlah_permintaan;
        if($row_dt->status_verif == null){
          $txt_verif = '<span style="color: red">[Belum diverifikasi]</span><br>';
        }else{
          $txt_verif = ($row_dt->status_verif == 1) ? '' : '<span style="color: red">[Ditolak]</span><br>';
        }
      ?>
      <tr <?php echo ($row_dt->status_verif != 1) ? 'class="row-unverif"' : '' ?>>
        <td class="center"><?php echo $no?></td>
        <td><?php echo $kode_brg?></td>
        <td><?php echo $nama_brg?></td>
        <td class="center"><?php echo $is_bhp?></td>
        <td class="center"><?php echo $row_dt->jumlah_stok_sebelumnya;?> <?php echo $row_dt->satuan_kecil?></td>
        <td class="center"><?php echo $qty;?> <?php echo $row_dt->satuan_kecil?></td>
        <td><?php echo $row_dt->keterangan_permintaan?></td>
        <td class="center"><?php echo $row_dt->jml_acc_atasan?></td>
        <td><?php echo $txt_verif.$row_dt->keterangan_verif?></td>
        <td class="center"><?php echo $row_dt->jumlah_kirim?></td>
        <td><?php echo $row_dt->petugas_kirim?></td>
        <td class="center"><?php echo $row_dt->jumlah_penerimaan?></td>
        <td><?php echo $row_dt->petugas_terima?></td>
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
        <td>:
          <?php
            if(isset($dt_detail_brg[0])){
              if($dt_detail_brg[0]->tgl_acc == null){
                echo '<span style="color:red;font-weight:600">Belum diverifikasi</span>';
              }else{
                echo ($dt_detail_brg[0]->status_acc == 1) ? '<span style="color:green;font-weight:600">Disetujui</span>' : '<span style="color:red;font-weight:600">Ditolak</span>';
              }
            }
          ?>
        </td>
      </tr>
      <tr>
        <td>Catatan Verifikator</td>
        <td>: <em><?php echo isset($dt_detail_brg[0])?ucfirst($dt_detail_brg[0]->acc_note):'-'?></em></td>
      </tr>
    </table>
  </div>
</div>

<div class="det-wrap">
  <div class="det-hdr"><i class="fa fa-truck"></i> Distribusi &amp; Penerimaan Barang</div>
  <div class="info-block">
    <table>
      <tr>
        <td>Tgl. Kirim</td>
        <td>: <?php echo isset($dt_detail_brg[0]) ? (($dt_detail_brg[0]->tgl_pengiriman != '0000-00-00 00:00:00') ? $this->tanggal->formatDateDmy($dt_detail_brg[0]->tgl_pengiriman) : '-') : '-'?></td>
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
