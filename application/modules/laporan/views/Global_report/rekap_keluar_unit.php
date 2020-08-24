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
      // foreach($result['data'] as $r_data);?>
      <center><h4><?php echo $title?></h4></center>
      <b>Status :</b> <b><i><?php echo $status;?></i></b>
      <br>
      <br>
      <table class="greyGridTable">
        <thead>
          <tr>
            <th>NO</th>
            <th width="100">Nama Unit/Bagian</th>
            <th width="100">Total Harga Beli (Rp. )</th>    
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          $sum = 0;
          foreach($result['data'] as $row_data){
            $sum += $row_data->hargabeli;
            $no++; 
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                  echo '<td align="left">'.$row_data->nama_bagian.'</td>';
                  echo '<td>'.number_format($row_data->hargabeli).'</td>';
              ?>
            </tr>
          <?php 
        // endforeach; 
      }?>
      <tr>
        <td colspan="2" align="right">TOTAL</td>
      <?php echo '<td>'.number_format($sum).'</td>';
      ?>
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






