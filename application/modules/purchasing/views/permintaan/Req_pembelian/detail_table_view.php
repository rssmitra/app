<style>
  .det-wrap { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; margin: 6px 0 10px; }
  .det-hdr { background: #1a4f8a; color: #fff; padding: 8px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  .det-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
  .det-tbl thead tr { background: #2c6fad; color: #fff; }
  .det-tbl thead th { padding: 8px 10px; text-align: center; font-weight: 600; border: 1px solid #1e5590; }
  .det-tbl tbody tr:nth-child(even) { background: #f5f9fd; }
  .det-tbl tbody tr:hover { background: #e8f0f9; }
  .det-tbl tbody td { padding: 7px 10px; border: 1px solid #d0dce8; vertical-align: middle; }
  .hist-box { background: #f8fafd; border: 1px solid #d0dce8; border-radius: 4px; padding: 10px 14px; margin-top: 8px; font-size: 11px; line-height: 1.8; }
</style>

<div class="det-wrap">
  <div class="det-hdr">
    <i class="fa fa-list"></i> Rincian Permintaan Barang
    <span style="margin-left:auto;font-weight:400;opacity:.85">No. <?php echo isset($dt_detail_brg[0]['kode_permohonan']) ? $dt_detail_brg[0]['kode_permohonan'] : '-'; ?></span>
  </div>
  <table class="det-tbl">
    <thead>
      <tr>
        <th width="35px">No</th>
        <th width="120px">Kode Barang</th>
        <th>Nama Barang</th>
        <th width="80px">Permohonan</th>
        <th width="80px">Disetujui</th>
        <th width="80px">PO</th>
        <th width="80px">Satuan Besar</th>
        <th width="80px">Rasio</th>
        <th width="100px">Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $no=0;
        if( count($dt_detail_brg) > 0 ) :
        foreach($dt_detail_brg as $row_dt) : $no++
      ?>
        <tr>
          <td class="center"><?php echo $no?></td>
          <td><?php echo isset($row_dt['kode_brg'])?$row_dt['kode_brg']:'<span class="red">[free text]</span>'?></td>
          <td><?php echo $row_dt['nama_brg']?></td>
          <td class="center"><?php echo number_format($row_dt['jml_besar'], 2)?></td>
          <td class="center">
            <?php
              $span_class = ( $row_dt['jml_acc_penyetuju'] == $row_dt['jml_besar_acc'] ) ? 'color: green' : 'color: red';
              echo '<span style="'.$span_class.'">'.number_format($row_dt['jml_acc_penyetuju'], 2).'</span>'?>
          </td>
          <td class="center">
            <?php
              if(isset($row_dt['id_tc_permohonan_det']) && $row_dt['id_tc_permohonan_det'] != 0){
                $label = ( $row_dt['jml_besar_acc'] == $row_dt['total_po'] ) ? 'badge-success' : 'badge-danger' ;
                $val_td = '<span class="badge '.$label.' "><a href="#" style="color: white" onclick="show_modal('."'purchasing/permintaan/Req_pembelian/log_brg?id=".$row_dt['id_tc_permohonan_det']."&kode_brg=".$row_dt['kode_brg']."&flag=".$flag."'".', '."'LOG DETAIL BARANG'".')">'.number_format($row_dt['total_po'], 2).'</span>';
                $text = ($row_dt['total_po']==0) ? '-' : $val_td ;
              }else{
                $text = '';
              }
              echo $text;
            ?>
          </td>
          <td class="center"><?php echo $row_dt['satuan_besar']?></td>
          <td class="center"><?php echo isset($row_dt['rasio'])?$row_dt['rasio']:'1'?></td>
          <td class="center"><?php echo $row_dt['keterangan']?></td>
        </tr>
      <?php endforeach; else: echo '<tr><td colspan="9" class="center" style="color:#888;font-style:italic;padding:12px">Tidak ada barang ditemukan</td></tr>'; endif; ?>
    </tbody>
  </table>
</div>

<?php
  if( count($dt_detail_brg) > 0 ){
    $explode_str = explode('/',$dt_detail_brg[0]['ket_acc']);
    if(is_array($explode_str) && count($explode_str) > 1){
      echo '<div class="hist-box"><strong><i class="fa fa-history"></i> Riwayat Persetujuan:</strong><br>';
      foreach($explode_str as $row_str){
        echo htmlspecialchars($row_str).'<br>';
      }
      echo '</div>';
    }
  }
?>
