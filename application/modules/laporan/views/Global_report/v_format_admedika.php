<?php 

  if($_POST['submit']=='excel') {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$flag.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }

?>

<html>
<head>
  <title>Laporan Umum</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
</head>
<body>
  <div class="row">
    <div class="col-xs-12">

      <center><h4>MIGRASI STOK ADMEDIKA</h4></center>

      <table class="table">
        <thead>
          <tr>
            <th>KODE GUDANG</th>
            <th>KODE BARANG</th>
            <th>NAMA BARANG</th>
            <th>VOLUME</th>
            <th>KODE SATUAN</th>
            <th>TGL KADALUARSA</th>
            <th>NILAI SATUAN</th>
            <th>TOTAL NILAI</th>
            <th>KODE LAMA</th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach ($result['data'] as $key => $value) {
              echo '<tr>';
              echo '<td></td>';
              echo '<td>'.$value->KODE_BARANG.'</td>';
              echo '<td>'.$value->NAMA_BARANG.'</td>';
              echo '<td>'.$value->VOLUME.'</td>';
              echo '<td>'.$value->KODE_SATUAN.'</td>';
              echo '<td>'.$value->TGL_KADALUARSA.'</td>';
              echo '<td>'.$value->HARGA_SATUAN.'</td>';
              echo '<td>'.$value->TOTAL.'</td>';
              echo '<td>'.$value->KODE_BARANG_LAMA.'</td>';
              echo '</tr>';
            }
          ?>
        </tbody>
      </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






