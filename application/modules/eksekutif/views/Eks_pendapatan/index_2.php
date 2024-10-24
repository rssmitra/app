

<?php 
  foreach($data_pasien as $key=>$row) : 
    foreach($fields as $k=>$r) {
      $total = isset($data_trans[$key][$k])?array_sum(array_column($data_trans[$key][$k], 'total_billing')) : 0;
      $arr_sub_total[$k][] = $total;
      $getData[$k][] = $total;
      $getDataPerPasien[$key][$k][] = $total;
    }
  endforeach; 
  // echo "<pre>";print_r($getData);die;
  
?>


<button type="button" name="btn-export" value="2" onclick="export_excel(2)" class="btn btn-xs btn-success">
  <i class="ace-icon fa fa-file-excel-o icon-on-right bigger-110"></i>
  Export Excel
</button>

<table class="table">
  <?php 
    echo "<tr style='background: #e5f4f7'>";
    $width = 100 / count($fields);
    // print_r($fields);
    foreach($fields as $k=>$r) {
      if($k != ''){
        switch ($k) {
          case '2': $title = 'Adm'; break;
          case '10': $title = 'Tindakan Luar'; break;
          case '13': $title = 'Sarana RS'; break;
          case '8': $title = 'Lainnya'; break;
          case '4': $title = 'Visit dr'; break;
          case '11': $title = 'Farmasi'; break;
          default: $title = $r;break;
        }
        echo "<td align='center' width='".$width." px'>[".$k."] <br> ".$title."</td>";
      }
    }
    echo "<td align='center' width='".$width." px'>Total</td>";
    echo "</tr>";
    echo "<tr>";
    $width = 100 / count($fields);
    foreach($fields as $k=>$r) {
      if($k != ''){
        $total = array_sum($getData[$k]);
        $arr_total[] = $total;
        echo "<td align='right' width='".$width." px'><span style='font-size: 14px; font-weight: bold'>".number_format($total)."</span></td>";
      }
    }
    echo "<td align='right' width='".$width." px'><span style='font-size: 14px; font-weight: bold'>".number_format(array_sum($arr_total))."</span></td>";
    echo "</tr>";
  ?>
<table>

<table class="table table-bordered table-hover">
  <thead>
    <tr style="background-color:#428bca">
      <th class="center">No</th>
      <th width="90px">No. Kuitansi</th>
      <th width="90px">No. Registrasi</th>
      <th>Pasien</th>
      <th>Penjamin</th>
      <th width="120px">Tgl Kunjungan</th>
      <?php 
        foreach($fields as $k=>$r) {
          if($k != ''){
            echo "<th class='center'>".$k."</th>";
          }
        }
      ?>
      <th>Total</th>
    </tr>
  </thead>
  <?php $no=0; foreach($data_pasien as $key=>$row) : $no++;?>
    <tr>
      <td align="center"><?php echo $no;?></td>
      <td><?php echo $row['seri_kuitansi'].'-'.$row['no_kuitansi'];?><br><?php echo $row['tgl_jam'];?></td>
      <td><?php echo $row['no_registrasi']?></td>
      <td><?php echo '['.$row['no_mr'].']<br>'.$row['nama_pasien'];?></td>
      <td><?php echo $row['nama_perusahaan']; echo ($row['kode_perusahaan'] == 120) ? "<br>(".$row['no_sep'].")" : ""?><br><?php echo $row['nama_bagian'];?></td>
      <td><?php echo 'in: '.$row['tgl_masuk'].'<br>out: '.$row['tgl_keluar'];?></td>
      <?php 
        foreach($fields as $k=>$r) {
          if($k != ''){
            $total = array_sum($getDataPerPasien[$key][$k]);
            $arr_sub_total[$key][] = $total;
            echo '<td align="right"><a href="#" style="font-weight: bold; color: blue; font-size:11px" onclick="show_modal_medium_return_json('."'eksekutif/Eks_pendapatan/getDetailTransaksi/".$row['kode_tc_trans_kasir']."/".$row['no_registrasi']."/".$k."'".', '."'Detail Transaksi [".$r."]'".')">'.number_format($total).'</a></td>';
          }
        }
      ?>
      <td align="right"><a href="#" style="font-weight: bold; color: blue; font-size:11px"><?php echo number_format(array_sum($arr_sub_total[$key]))?></a></td>
    </tr>
  <?php endforeach; ?>
</table>