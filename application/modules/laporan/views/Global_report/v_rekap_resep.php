<?php 

  if(isset($_POST['submit']) AND $_POST['submit']=='excel') {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$flag.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }

?>

<html>
<head>
  <title><?php echo $title?></title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/blue.css"/>
</head>
<body>
  <div class="row">
    <div class="col-xs-12">

      <center><h4><?php echo $title?></h4></center>

      <table class="table">
        <thead style="background: grey; color: white">
          <tr class="mainTitleLeft">
            <th rowspan="2" style="width: 30px">NO</th>
            <th rowspan="2">BULAN</th>
            <th colspan="2" style="text-align : center">RJ</th>
            <th colspan="2" style="text-align : center">RI</th>
            <th colspan="2" style="text-align : center">IGD</th>
          </tr>
          <tr>
            <th style="width: 100px; text-align: center">BPJS</th>
            <th style="width: 100px; text-align: center">UMUM</th>
            <th style="width: 100px; text-align: center">BPJS</th>
            <th style="width: 100px; text-align: center">UMUM</th>
            <th style="width: 100px; text-align: center">BPJS</th>
            <th style="width: 100px; text-align: center">UMUM</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            foreach($result as $key => $row) : $no++;
          ?>
            <tr class="mainTitleLeft">
              <td align="center"><?php echo $no; ?></td>
              <td><?php echo strtoupper($this->tanggal->getBulan($key)); ?></td>
              <td style="text-align: center">
                <?php
                  $dt = isset($row['01']['BPJS Kesehatan'])?$row['01']['BPJS Kesehatan']:'';
                  echo ($dt != '') ? $dt->total : 0;
                ?>
              </td>
              <td style="text-align: center">
                <?php
                  $dt = isset($row['01']['Umum'])?$row['01']['Umum']:'';
                  echo ($dt != '') ? $dt->total : 0;
                ?>
              </td>

              <td style="text-align: center">
                <?php
                  $dt = isset($row['03']['BPJS Kesehatan'])?$row['03']['BPJS Kesehatan']:'';
                  echo ($dt != '') ? $dt->total : 0;
                ?>
              </td>
              <td style="text-align: center">
                <?php
                  $dt = isset($row['03']['Umum'])?$row['03']['Umum']:'';
                  echo ($dt != '') ? $dt->total : 0;
                ?>
              </td>

              <td style="text-align: center">
                <?php
                  $dt = isset($row['02']['BPJS Kesehatan'])?$row['02']['BPJS Kesehatan']:'';
                  echo ($dt != '') ? $dt->total : 0;
                ?>
              </td>
              <td style="text-align: center">
                <?php
                  $dt = isset($row['02']['Umum'])?$row['02']['Umum']:'';
                  echo ($dt != '') ? $dt->total : 0;
                ?>
              </td>
            </tr>
          <?php 
        endforeach; ?>
        </tbody>
      </table>
      
    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






