<?php 
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=".'lhk_exp_date_type_2_'.date('Ymd').".xls");  //File name extension was wrong
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false);

  foreach($data_pasien as $key=>$row) : 
    foreach($fields as $k=>$r) {
      $total = isset($data_trans[$key][$k])?array_sum(array_column($data_trans[$key][$k], 'total_billing')) : 0;
      $arr_sub_total[$k][] = $total;
      $getData[$k][] = $total;
      $getDataPerPasien[$key][$k][] = $total;
    }
  endforeach; 
?>

<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th class="center">No</th>
      <th width="90px">Tipe</th>
      <th width="90px">No. Kuitansi</th>
      <th width="90px">Tgl Submit</th>
      <th>No MR</th>
      <th>Pasien</th>
      <th>Penjamin</th>
      <th>Bagian Masuk</th>
      <th>No. SEP</th>
      <th width="120px">Tgl Masuk</th>
      <th width="120px">Tgl Keluar</th>
      <?php 
        foreach($fields as $k=>$r) {
          if($k != ''){
            echo "<th class='center'>".$r."</th>";
          }
        }
      ?>
      <th>Total</th>
    </tr>
  </thead>
  <?php $no=0; foreach($data_pasien as $key=>$row) : $no++;?>
    <tr>
      <td align="center"><?php echo $no;?></td>
      <td><?php echo $row['seri_kuitansi'];?></td>
      <td><?php echo $row['no_kuitansi'];?></td>
      <td><?php echo $row['tgl_jam'];?></td>
      <td><?php echo $row['no_mr'];?></td>
      <td><?php echo $row['nama_pasien'];?></td>
      <td><?php echo $row['nama_perusahaan'];?></td>
      <td><?php echo $row['nama_bagian'];?></td>
      <td><?php echo $row['no_sep']; ?></td>
      <td><?php echo $row['tgl_masuk']?></td>
      <td><?php echo $row['tgl_keluar'];?></td>
      <?php 
        foreach($fields as $k=>$r) {
          if($k != ''){
            $total = array_sum($getDataPerPasien[$key][$k]);
            $arr_sub_total[$key][] = $total;
            echo '<td align="right">'.number_format($total).'</td>';
          }
        }
      ?>
      <td align="right"><?php echo number_format(array_sum($arr_sub_total[$key]))?></td>
    </tr>
  <?php endforeach; ?>
</table>