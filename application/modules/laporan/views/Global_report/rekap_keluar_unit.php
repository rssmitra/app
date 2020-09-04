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
      <table class="table">
        <thead>
          <tr>
            <th width="50px">NO</th>
            <th>Nama Unit/Bagian</th>
            <th width="150px">Total Biaya (Rp. )</th>    
          </tr>
        </thead>
        <tbody>
          <?php 
            foreach ($result as $key => $value) {
              # code...
              foreach ($value as $k => $r) {
                # code...
                $sumTotal = $r->total * $r->harga_beli;
                $dataTotal[$key][] = $sumTotal;
              } 
            }
            // echo '<pre>';print_r($dataTotal);die;
            $no = 0; 
            $sum = 0;
            foreach($dataTotal as $key_dt => $row_data) : $no++;
            $sum += array_sum($dataTotal[$key_dt]);
          ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                  echo '<td align="left">'.$key_dt.'</td>';
                  echo '<td align="right">'.array_sum($dataTotal[$key_dt]).'</td>';
              ?>
            </tr>
          <?php endforeach; ?>
      <tr>
        <td colspan="2" align="right">TOTAL</td>
      <?php echo '<td align="right">'.$sum.'</td>';
      ?>
    </table>
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






