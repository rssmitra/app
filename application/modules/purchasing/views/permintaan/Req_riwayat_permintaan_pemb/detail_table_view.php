<style>
  .det-wrap { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; margin: 6px 0 10px; }
  .det-hdr { background: #1a4f8a; color: #fff; padding: 8px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  .det-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
  .det-tbl thead tr { background: #2c6fad; color: #fff; }
  .det-tbl thead th { padding: 8px 10px; text-align: center; font-weight: 600; border: 1px solid #1e5590; }
  .det-tbl tbody tr:nth-child(even) { background: #f5f9fd; }
  .det-tbl tbody tr:hover { background: #e8f0f9; }
  .det-tbl tbody td { padding: 7px 10px; border: 1px solid #d0dce8; vertical-align: middle; }
</style>

<div class="det-wrap">
  <div class="det-hdr">
    <i class="fa fa-list"></i> Rincian Permohonan Barang
  </div>
  <table class="det-tbl">
    <thead>
      <tr>
        <th width="35px">No</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th width="100px">Jumlah<br>Permohonan</th>
        <th width="100px">Jumlah Brg<br>yang di ACC</th>
        <th width="80px">Satuan Besar</th>
        <th width="60px">Rasio</th>
      </tr>
    </thead>
    <tbody>
      <?php $no=0; foreach($dt_detail_brg as $row_dt) : $no++?>
        <tr>
          <td class="center"><?php echo $no?></td>
          <td><?php echo $row_dt->kode_brg?></td>
          <td><?php echo $row_dt->nama_brg?></td>
          <td class="center"><?php echo $row_dt->jumlah_besar?></td>
          <td class="center"><?php echo $row_dt->jumlah_besar_acc?></td>
          <td class="center"><?php echo $row_dt->satuan_besar?></td>
          <td class="center"><?php echo $row_dt->rasio?></td>
        </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
