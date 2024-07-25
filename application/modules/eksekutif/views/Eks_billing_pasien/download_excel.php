<?php 
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  $prefix_name = 'Export_Billing';
  header("Content-Disposition: attachment; filename=".$prefix_name.'_'.date('YmdHis').".xls");  //File name extension was wrong
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false);
?>

<h2>Export Data Transaksi Billing Pasien</h2><br>
<i><?php echo 'Penjamin '.$_GET['flag'].' | Type '.$_GET['pelayanan'].' | tgl '. $_GET['from_tgl'].' s/d '.$_GET['to_tgl']?></i>
<table id="dt_pasien_kasir" base-url="eksekutif/Eks_billing_pasien/get_data" class="table table-bordered table-hover">
  <thead>
    <tr>
      <th width="50px" class="center">No</th>
      <th>No. MR</th>
      <th>Nama Pasien</th>
      <th>Penjamin</th>
      <th>Billing Unit</th>
      <th>Poli/Klinik Asal</th>
      <th>Dokter</th>
      <th width="150px">Tanggal</th>
      <th width="150px">Status/Sisa Bayar</th>
      <th width="100px">Total Billing</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $no=0;
      foreach ($result as $row_list) {
        // var_dump($row_list);die;
        $row = array();
        echo "<tr>";
        // sum total
        $total = $this->master->sumArrayByKey($row_list, 'total');
        $no++;
            $total_billing = $this->master->sumArrayByKey($row_list, 'total_billing');
            $arr_total[] = $total;
            $arr_total_billing[] = $total_billing;
            echo '<td>'.$no.'</td>';
            echo '<td>'.$row_list[0]['no_mr'].'</td>';
            echo '<td>'.$row_list[0]['nama_pasien'].'</td>';
            $perusahaan = ($row_list[0]['nama_perusahaan'])?$row_list[0]['nama_perusahaan']:'UMUM';
            echo '<td>'.$perusahaan.'</td>';
            echo '<td>'.ucwords($row_list[0]['nama_bagian']).'</td>';
            echo '<td>'.ucwords($row_list[0]['bagian_asal']).'</td>';
            $nama_dokter = ($row_list[0]['nama_dokter'])?$row_list[0]['nama_dokter']:'-';
            echo '<td>'.$nama_dokter.'</td>';
            echo '<td>'.$this->tanggal->formatDateTime($row_list[0]['tgl_jam_masuk']).'</td>';
            if( $row_list[0]['status_batal'] == 1 ){
                echo '<td style="color: red">Batal</td>';
                $arr_total_cancel[] = $total_billing;
            }else{
                if( $total > 0 ){
                    echo '<td align="right">'.$total.'</td>';
                }else{
                    echo '<td style="color: green">Lunas</td>';
                    $arr_paid[] = $total_billing;
                }
            }
            echo '<td align="right">'.$total_billing.'</td>';
            echo "</tr>";
      }
    ?>
    <tr>
    <td colspan="9" align="right">Total Billing Pasien</td>
      <td align="right"><?php echo array_sum($arr_paid)?></td>
    </tr>
  </tbody>
</table>