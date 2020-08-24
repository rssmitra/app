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
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/blue.css"/>
</head>
<body>
  <div class="row">
    <div class="col-xs-12">

      <center><h4>Laporan Stok Barang Non Medis Per-periode</h4></center>
      <!-- <b>Parameter :</b> <i><?php echo print_r($_POST);?></i> -->

      <table class="greyGridTable">
        <thead>
          <tr>
            <th>NO</th>
            <th width="105">Kode Barang<br/></th>
            <th width="95">Nama Barang</th>
            <th width="95">Unit</th>
            <th width="304">HPP Satuan</th>
            <th width="304">Harga Jual Satuan</th>
            <th width="304">Saldo Akhir</th>
            <th width="304">Jumlah</th>
          </tr>
         
        </thead>
        <tbody>
          <?php $no = 0; 
          foreach($result['data'] as $row_data){
            // $saldopenerimaan=$row_data->jumlah_kirim * $row_data->harga_beli;
            $no++; 
           $ttlakhir=0;
            // $saldo_akhir= $qtys + $qty_p - $qty - $qty_u - $qty_i - $qty_d;
            $saldoakhir=$row_data->stok_akhir * $row_data->hargajual;
            $ttlakhir=$ttlakhir+$saldoakhir;

            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                echo '<td>'.$row_data->kode_brg.'</td>';
                echo '<td>'.$row_data->nama_brg.'</td>';
                echo '<td>'.$row_data->nama_bagian.'</td>';
                echo '<td>'.number_format($row_data->harga_beli).'</td>';
                echo '<td>'.number_format($row_data->hargajual).'</td>';
                echo '<td>'.$row_data->stok_akhir.'</td>';
                echo '<td>'.number_format($saldoakhir).'</td>';
              ?>
            </tr>
          <?php } ?>
          <tr><td colspan="7" align="right">Total </td>
            <td><?php echo number_format($ttlakhir);?></td>
          </tr>
        </tbody>
      </table>
<table border="0" width="100%">
  <tr>
  <td colspan="2" valign="bottom" style="padding-top:25px" align="right"> Jakarta, ..........................</td>
    <tr><td valign="bottom" style="padding-top:25px" align="right">
    <b>Mengetahui<br><br><br><br><br><br>_________________________
  </td>
  <td valign="bottom" style="padding-top:25px" align="right">
    <b>Petugas<br><br><br><br><br><br>_________________________
  </td>
</tr>
</table>
    </div>
  </div>
</body>
</html>






