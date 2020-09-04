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
      <center><h4><?php echo $title?></h4></center>
      <?php
      foreach($result as $k_result => $v_result) : ?>
      Unit/Bagian : <?php echo isset ($k_result)?(ucwords($k_result)):'-';?>
      <br><br>
      <table class="table">
        <thead>
          <tr>
            <th width="12px">No</th>
            <th width="100px">Kode</th>
            <th align="left">Nama Barang</th>
            <th width="120px">Kategori</th>
            <th width="100px" align="center">Jumlah</th>
            <th width="150px">Harga Satuan (Rp. )</th>    
            <th width="150px">Total Biaya (Rp. )</th>    
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          $total =0;
          foreach($v_result as $row_data){
            $jmlp         = $row_data->total;
            $harga        = $row_data->harga_beli;
            $ttl          = $jmlp * $harga;
            $total=$total + $ttl;
            $no++; 
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                  // echo '<td>'.$row_data->tgl_input.'</td>';
                  echo '<td align="left">'.$row_data->kode_brg.' </td>';
                  echo '<td align="left">'.$row_data->nama_brg.' </td>';
                  echo '<td align="left">'.$row_data->nama_golongan.' </td>';
                  echo '<td align="center">'.$row_data->total.'</td>';
                  echo '<td align="right">'.$harga.'</td>';
                  echo '<td align="right">'.$ttl.'</td>';
              ?>
            </tr>
          <?php }?>
          <tr>
            <td align="right" colspan="6">Total</td>
            <td align="right"><?php echo $total;?></td>
          </tr>
        </tbody>
      </table>
      <br><br>
      <?php endforeach; ?>
      <!-- <table border="0" width="100%">
        <tr>
        <td colspan="2" valign="bottom" style="padding-top:25px" align="right"> Jakarta, ..........................</td>
          <tr><td valign="bottom" style="padding-top:25px" align="right">
          <b>Mengetahui<br><br><br><br><br><br>_________________________
        </td>
        <td valign="bottom" style="padding-top:25px" align="right">
          <b>Petugas<br><br><br><br><br><br>_________________________
        </td>
      </tr>
      </table> -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






