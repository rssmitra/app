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
      <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>

      <table border="0" cellpadding="0" cellspacing="0" class="reportTitle">
    <tbody>
      <tr class="mainTitleLeft">
        <td colspan="3" style="white-space: nowrap; color: #000099;">
          <img src="../_images/logo_rs_laporan.gif">
        </td>
      </tr>
      <tr class="mainTitle">
        <td colspan="3">LAPORAN KUNJUNGAN RAWAT JALAN RS SETIA MITRA<br></td>
      </tr>
      <tr class="subTitle">
        <td width="15%">TAHUN</td>
        <td>:</td>
        <td><?php echo $tahun; ?>&nbsp;</td>
      </tr>
      <tr class="subTitle">
        <td width="15%">BULAN</td>
        <td>:</td>
        <td><?php echo $bulan; ?>&nbsp;</td>
      </tr>
    </tbody>
  </table>
       <table border="0" cellpadding="0" cellspacing="0">
  <tbody>
  <tr class="headTable">
    <td width="125" rowspan="3">&nbsp;</td>
    <td colspan="4"><center>RAWAT JALAN</center></td>
    <td rowspan="2">RAWAT INAP</td>
    <td rowspan="2">TOTAL</td>
  </tr>
  <tr class="headTable">
    <td>UGD / PU</td>
    <td>Poli Spesialis</td>
    <td>Luar</td>
    <td>Subtotal</td>
    <td></td>
    <td></td>
  </tr>
  <tr class="headTable">
    <td>1</td>
    <td>2</td>
    <td>3</td>
    <td>4</td>
    <td>5</td>
    <td>6</td>
  </tr>
  <tr class="contentTable">
    <td align="right" width="125">Jumlah Kunjungan</td>
    <td align="center"><?php echo $dt_sql_ugd; ?>&nbsp;</td>
    <td align="center"><?php echo $dt_sql_spesialis; ?>&nbsp;</td>
    <td align="center"><?php echo $dt_sql_luar; ?>&nbsp;</td>
    <td align="center"><?php 
    $sub_total = $dt_sql_ugd+$dt_sql_spesialis+$dt_sql_luar; 
    echo $sub_total; 
    ?>&nbsp;</td>
    
    <td align="center"><?php echo $dt_sql_inap;?>&nbsp;</td>
    <td align="center"><?php echo $sub_total + $dt_sql_inap; ?></td>
    
  </tr>
  </tbody>
  </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






