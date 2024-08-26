<?php 
  foreach($data_pasien as $key=>$row) : 
        foreach($fields as $k=>$r) {
          $total = isset($data_trans[$key][$k]->total_billing)?$data_trans[$key][$k]->total_billing:0;
          $arr_sub_total[$k][] = $total;
          $getData[$k][] = $total;
        }
  endforeach; 
?>



<table class="table">
  <?php 
    $width = 100 / count($fields);
    foreach($fields as $k=>$r) {
      $total = array_sum($getData[$k]);
      $arr_total[] = $total;
      echo "<td align='right' width='".$width." px'>".$k." - ".$r."<br><span style='font-size: 18px; font-weight: bold'>".number_format($total)."</span></td>";
    }
    echo "<td align='right' width='".$width." px'>Total<br><span style='font-size: 18px; font-weight: bold'>".number_format(array_sum($arr_total))."</span></td>";
  ?>
<table>

<table class="table table-bordered table-hover">
  <thead>
    <tr style="background-color:#428bca">
      <th>No</th>
      <th width="100px">No. Kuitansi</th>
      <th width="120px">Tanggal</th>
      <th>Pasien</th>
      <th>Penjamin</th>
      <th>Bagian Masuk</th>
      <?php 
        foreach($fields as $k=>$r) {
          echo "<th class='center'>".$k."</th>";
        }
      ?>
      <th>Total</th>
    </tr>
  </thead>
  <?php $no=0; foreach($data_pasien as $key=>$row) : $no++;?>
    <tr>
      <td><?php echo $no;?></td>
      <td><?php echo $row['seri_kuitansi'].'-'.$row['no_kuitansi'];?></td>
      <td><?php echo $row['tgl_jam'];?></td>
      <td><?php echo $row['nama_pasien'];?></td>
      <td><?php echo $row['nama_perusahaan'];?></td>
      <td><?php echo $row['nama_bagian'];?></td>
      <?php 
        foreach($fields as $k=>$r) {
          $total = isset($data_trans[$key][$k]->total_billing)?$data_trans[$key][$k]->total_billing:0;
          $arr_sub_total[$key][] = $total;
          echo "<td align='right'>".number_format($total)."</td>";
        }
      ?>
      <td align="right"><?php echo number_format(array_sum($arr_sub_total[$key]))?></td>
    </tr>
  <?php endforeach; ?>
</table>