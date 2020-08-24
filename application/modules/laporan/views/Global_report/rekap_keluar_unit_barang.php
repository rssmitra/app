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
      <center><h4><?php echo $title?></h4></center>
      <br>
      <br>
      <table class="table">
        <thead>
          <tr>
            <th>NO</th>
            <th width="100">Kode Barang</th>
            <th width="100">Nama Barang</th>
            <th width="100">Qty Keluar</th>
            <th width="100">Harga Beli (Rp. )</th>  
            <th width="100">Total Harga Beli (Rp. )</th>    
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          $total=0;
          foreach($result['data'] as $row_data){
            $hargabeli=$row_data->jml_pemasukan * $row_data->harga_beli;
            $total=$total+$hargabeli;
            $no++; 
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                  echo '<td>'.$row_data->kode_brg.'</td>';
                  echo '<td>'.$row_data->nama_brg.'</td>';
                  echo '<td>'.$row_data->jml_pemasukan.'</td>';
                  echo '<td>'.number_format($row_data->harga_beli).'</td>';
                  echo '<td>'.number_format($hargabeli).'</td>';
              ?>
            </tr>
          <?php 
        // endforeach; 
      }?>
      <tr>
        <td colspan="5" align="right">TOTAL</td>
      <?php echo '<td>'.number_format($total).'</td>';?>
</tr>
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
    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






