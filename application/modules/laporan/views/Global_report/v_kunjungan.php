<?php 

  if($_POST['submit']=='excel') {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$flag.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }
   switch($penunjang){
     case "Lab":
       $np= "LABORATORIUM";
     break;
      case "Rad":
       $np= "RADIOLOGI";
     break;
      case "Fisio":
       $np= "FISIOTERAPI";
     break;
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
      <table class="table">
    <tbody>
     
      <tr class="mainTitleLeft"> 
      <td colspan="3" align="center"><b><?php echo strtoupper($title)?><br>LAPORAN KUNJUNGAN <?php echo $np; ?> RS SETIA MITRA<br></td>
      </tr>
      <tr class="mainTitleLeft">
      <td width="15%"><b>PERIODE</td>
      <td>: <?php echo $_POST['from_tgl'].' s.d '.$_POST['to_tgl'] ?>&nbsp;</td>
      </tr>
    </tbody>
  </table>
       <table class="table" border="0">
  <tbody>
  <tr class="greyGridTable">
    <td width="125" rowspan="2">&nbsp;</td>
    <td colspan="4"><center><b>RAWAT JALAN</center></b></td>
    <td rowspan="2" align="center"><b>RAWAT INAP</b></td>
    <td rowspan="2" align="center"><b>TOTAL</b></td>
  </tr>
  <tr class="headTable">
    <td align="center"><b>UGD / PU</b></td>
    <td align="center"><b>Poli Spesialis</b></td>
    <td align="center"><b>Luar</b></td>
    <td align="center"><b>Subtotal</b></td>
  </tr>
  <tr class="contentTable">
    <td align="right" width="125"><b>Jumlah Kunjungan</b></td>
    <td align="center"><?php echo $dt_sql_ugd->total; ?>&nbsp;</td>
    <td align="center"><?php echo $dt_sql_spesialis->total; ?>&nbsp;</td>
    <td align="center"><?php echo $dt_sql_luar; ?>&nbsp;</td>
    <td align="center"><?php 
    $sub_total = $dt_sql_ugd->total+$dt_sql_spesialis->total+$dt_sql_luar; 
    echo $sub_total; 
    ?>&nbsp;</td>
    
    <td align="center"><?php echo $dt_sql_inap->total;?>&nbsp;</td>
    <td align="center"><?php echo $sub_total + $dt_sql_inap->total; ?></td>
    
  </tr>
  </tbody>
  </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






