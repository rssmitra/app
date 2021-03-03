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
      <span><?php echo strtoupper($result['data'][0]->supplier);?></span>
      <br>
      <table class="table table-bordered">
        <thead>
          <tr style="text-align: center">
            <th width="50">No</th>
            <th width="105">Nama Barang</th>
            <th width="105">Tgl Diterima</th>
            <th width="105">No Faktur</th>
            <th width="105">Jumlah Kirim</th>
            <th width="105">Satuan Besar</th>
            <th width="105">Harga Satuan</th>
            <th width="100">Total Pembelian</th>
          </tr>
         
        </thead>
        <tbody>
          <?php 
          $no = 0; 
          foreach($result['data'] as $k => $v){
            $no++; 
            // subtotal
            $subtotal = $v->jml_kirim * $v->harga;
            $total_pembelian = $subtotal;
            $total[] = $total_pembelian;
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <td><?php echo $v->nama_brg?></td>
              <td><?php echo $this->tanggal->formatDatedmY($v->tgl_terima); ?></td>
              <td><?php echo $v->no_faktur?></td>
              <td align="center"><?php echo $v->jml_kirim?></td>
              <td align="center"><?php echo $v->satuan_besar?></td>
              <td align="right"><?php echo number_format($v->harga)?></td>
              <td align="right"><?php echo number_format($total_pembelian)?></td>
            </tr>
          <?php 
          } 
          ?>
          <tr>
            <td colspan="7" align="right"><b>TOTAL PEMBELIAN BARANG</b></td>
            <td align="right"><b><?php echo number_format(array_sum($total)) ?></b></td>
          </tr>
        </tbody>
      </table>
      <br>
    </div>
  </div>
</body>
</html>






