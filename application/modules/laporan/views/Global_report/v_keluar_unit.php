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
      <?php
      foreach($result['data'] as $r_data);?>
      <center><h4><?php echo $title?></h4></center>
      <b>Nama Bagian :</b> <b><i><?php echo isset ($r_data->nama_bagian)?($r_data->nama_bagian):'-';?></i></b>
      <br>
      <br>
      <table class="greyGridTable">
        <thead>
          <tr>
            <th width="10">NO</th>
           <th width="120">Kategori Barang</th>
           <th width="120">Kode Barang</th>
            <th width="176">Nama Barang</th>
            <th width="100" align="center">Qty Keluar</th>
            <th width="100">Harga Beli (Rp. )</th>    
            <th width="100">Total Harga Beli (Rp. )</th>    
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          $total =0;
          foreach($result['data'] as $row_data){
            $jmlp         = $row_data->jml_pemasukan;
            $harga        = $row_data->harga_beli;
            $ttl          = $jmlp*$harga;
            $total=$total + $ttl;
            $no++; 
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                  // echo '<td>'.$row_data->tgl_input.'</td>';
                  echo '<td>'.$row_data->nama_golongan.' </td>';
                  echo '<td>'.$row_data->kode_brg.' </td>';
                  echo '<td>'.$row_data->nama_brg.' </td>';
                  echo '<td>'.$row_data->jml_pemasukan.'</td>';
                  echo '<td>'.number_format($harga).'</td>';
                  echo '<td>'.number_format($ttl).'</td>';
              ?>
            </tr>
          <?php 
        // endforeach; 
      }?>
       <tr><td align="right" colspan="6">Total</td>
      <td align="center"><?php echo number_format($total);?></td></tr>
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
    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






