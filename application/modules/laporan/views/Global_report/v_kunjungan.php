<?php 

  if($_POST['submit']=='excel') {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$flag.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }
 switch($bulan){
     case "1":
       $nmbln= "JANUARI";
     break;
      case "2":
       $nmbln= "FEBRUARI";
     break;
      case "3":
       $nmbln= "MARET";
     break;
      case "4":
       $nmbln= "APRIL";
     break;
      case "5":
       $nmbln= "MEI";
     break;
      case "6":
       $nmbln= "JUNI";
     break;
      case "7":
       $nmbln= "JULI";
     break;
      case "8":
       $nmbln= "AGUSTUS";
     break;
      case "9":
       $nmbln= "SEPTEMBER";
     break;
      case "10":
       $nmbln= "OKTOBER";
     break;
      case "11":
       $nmbln= "NOVEMBER";
     break;
      case "12":
       $nmbln= "DESEMBER";
     break;
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

      <center><h4><?php echo $title?></h4></center>
      <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>

      <table class="table">
    <tbody>
     
      <tr class="mainTitleLeft"> 
        <td colspan="3" align="center"><b>LAPORAN KUNJUNGAN RAWAT <?php echo $np; ?> JALAN RS SETIA MITRA<br></td>
      </tr>
      <tr class="mainTitleLeft">
        <td width="15%"><b>TAHUN</td>
        <td>: <?php echo $tahun; ?>&nbsp;</td>
      </tr>
      <tr class="mainTitleLeft">
        <td width="15%"><b>BULAN</td>
        <td>: <?php echo $nmbln; ?>&nbsp;</td>
      </tr>
    </tbody>
  </table>
       <table class="table" border="0">
  <tbody>
  <tr class="greyGridTable">
    <td width="125" rowspan="3">&nbsp;</td>
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
  <tr class="headTable">
    <td align="center"><b>1</b></td>
    <td align="center"><b>2</b></td>
    <td align="center"><b>3</b></td>
    <td align="center"><b>4</b></td>
    <td align="center"><b>5</b></td>
    <td align="center"><b>6</b></td>
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






