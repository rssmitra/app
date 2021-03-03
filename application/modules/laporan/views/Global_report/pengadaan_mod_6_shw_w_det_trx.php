<?php 
  
  // if($_POST['submit']=='excel') {
  //   header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  //   header("Content-Disposition: attachment; filename=".$flag.'_'.date('Ymd').".xls");  //File name extension was wrong
  //   header("Expires: 0");
  //   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  //   header("Cache-Control: private",false);
  // }

?>

<html>
<head>
  <title>Laporan Umum</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/blue.css"/>
</head>
<body>
  <div class="row">
    <div class="col-xs-12">

      <center><span style="font-size: 14px; font-weight: bold">Rekap Pembelian Barang Berdasarkan Supplier<br>Bulan <?php echo $this->tanggal->getBulan($month)?> Tahun <?php echo $year; ?></center>

      <br>
      <table class="table table-bordered">
        <thead>
          <tr style="text-align: center">
            <th width="50">No</th>
            <th width="105">Tgl Diterima</th>
            <th width="105">No Faktur</th>
            <th width="105">Jumlah Kirim</th>
            <th width="105">Satuan Besar</th>
            <th width="105">Harga Satuan</th>
            <th width="105">Diskon(%)</th>
            <th width="100">Total Pembelian</th>
          </tr>
         
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            foreach ($result as $key => $value) {
              echo '<tr>';
              echo '<td colspan="8"><b>'.strtoupper($key).'</b></td>';
              echo '</tr>';
              $key_trim = trim($key);
              foreach ($value as $k => $v) {
                $no++; 
                // subtotal
                $subtotal = $v->jml_kirim * $v->harga;
                // diskon
                $diskon = ($v->disc > 0) ? ($subtotal * $v->disc) : 0;
                $total_pembelian = $subtotal - $diskon;
                $total[$key_trim][] = $total_pembelian;
                
                echo '<tr>';
                  echo '<td align="center">'.$no.'</td>';
                  echo '<td>'.$this->tanggal->formatDatedmY($v->tgl_terima).'</td>';
                  echo '<td>'.$v->no_faktur.'</td>';
                  echo '<td align="center">'.$v->jml_kirim.'</td>';
                  echo '<td align="center">'.$v->satuan_besar.'</td>';
                  echo '<td align="right">'.number_format($v->harga).'</td>';
                  echo '<td align="center">'.$v->disc.'</td>';
                  echo '<td align="right">'.number_format($total_pembelian).'</td>';
                echo '</tr>';
              }
              echo '<tr>';
                echo '<td colspan="7" align="right"><b>TOTAL PEMBELIAN BARANG</b></td>';
                echo '<td align="right"><b>'.number_format(array_sum($total[$key_trim])).'</b></td>';
              echo '</tr>';
              
              echo '<tr><td colspan="8">&nbsp;</td></tr>';
            }
          ?>
          
        </tbody>
      </table>
      <br>
    </div>
  </div>
</body>
</html>






