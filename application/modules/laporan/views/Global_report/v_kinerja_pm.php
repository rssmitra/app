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
     <table border="0" cellpadding="0" cellspacing="0" class="reportTitle">
	

<table class="table">
<tbody>
<tr class="headTable">
	<td >No.</td>
	<td >Nama Tindakan</td>
	<td width="100px">Jumlah Tindakan</td>
	<td width="100px">Biaya</td>
</tr>
<?php
 $no = 0; 
          foreach($result as $value){
			
			$nama_tindakan = $value->nama_tindakan;
			$jumlah_tindakan = $value->jumlah;
			$biaya_tindakan = $value->biaya;
			$no++; 
      $arr_biaya[] = $biaya_tindakan;
      $arr_ttl[] = $jumlah_tindakan;
?>
<tr class="contentTable">
	<td align="right" width="25"><?php echo $no;  ?>.&nbsp;</td>
	<td align="left"><?php echo $nama_tindakan; ?>&nbsp;</td>
	<td align="center"><?php echo $jumlah_tindakan; ?>&nbsp; </td>
	<td align="right"><?php echo number_format($biaya_tindakan);?>&nbsp; </td>
</tr>
<?php
	}
?>
<tr>
  <td colspan="2" align="right">TOTAL</td>
  <td align="center"><?php echo number_format(array_sum($arr_ttl))?></td>
  <td align="right"><?php echo number_format(array_sum($arr_biaya))?></td>
</tr>
</tbody>
</table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






