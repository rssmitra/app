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
<table class="table">
          <tbody>
            <tr class="mainTitleLeft">
              <td colspan="3" style="white-space: nowrap; color: #000099;">
              </td>
            </tr>
            <tr class="mainTitle">
              <td colspan="3">LAPORAN PEMBELIAN OBAT CITO</td>
            </tr>
            
            <tr class="subTitle">
              <td width="15%">PERIODE </td>
              <td>:</td>
              <td><?php echo $tgl1 ?> s/d <?php echo $tgl2 ?></td>
            </tr>
            
          </tbody>
        </table>
      <table class="table">
          <tbody>
            <tr>
              <td class="border-rb" width="25" rowspan="1" colspan="1">No.</td>
              <td class="border-rb" rowspan="1" colspan="1">Tgl Trans</td>
              <td class="border-rb" rowspan="1" colspan="1">Nama Obat</td>
              <td class="border-rb" rowspan="1" colspan="1">Satuan</td> 
              <td class="border-rb" rowspan="1" colspan="1">Jml Sat Kecil</td>
              <td class="border-rb" rowspan="1" colspan="1">Hrg Beli per Sat Kecil</td>
              <td class="border-rb" rowspan="1" colspan="1">Total Hrg Pembelian</td> 
              <td class="border-rb" rowspan="1" colspan="1">Hrg Jual per Sat Kecil</td> 
              <td class="border-rb" rowspan="1" colspan="1">Total Hrg Penjualan</td> 
              <td class="border-rb" rowspan="1" colspan="1">Tempat Pembelian</td> 
              
            </tr>
            
          <?php
           $no = 0; 
           $total_jual=0;
           $total_harganya=0;
           $harga_jualnya=0;
          foreach ($result['data'] as $row_data){
            $no ++;
                $total_harganya = $total_harganya + $row_data->total_harga;
                $harga_jualnya = $harga_jualnya + $row_data->harga_jual;
                $tempat_pembelian = $row_data->tempat_pembelian;
                $tot_harga_jual = $row_data->tot_harga_jual;
                $total_jual = $total_jual + $tot_harga_jual;
            ?>
            
             <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                  echo '<td>'.$row_data->tgl_pembelian.'</td>';
                  echo '<td>'.$row_data->nama_brg.'</td>';
                  echo '<td>'.$row_data->satuan_kecil.'</td>';
                  echo '<td>'.$row_data->jumlah_kcl.'</td>';
                  echo '<td>'.number_format($row_data->harga_beli).'</td>';
                  echo '<td>'.number_format($row_data->total_harga).'</td>';
                  echo '<td>'.number_format($row_data->harga_jual).'</td>';
                  echo '<td>'.number_format($row_data->tot_harga_jual).'</td>';
                  echo '<td>'.$row_data->tempat_pembelian.'</td>';
              ?>
            </tr>
            
            <?php
              }
            ?>
          <tr class="contentTable">
              <td align="center" colspan="6"><b>T O T A L</b></td>
              <td align="right"><b><?php echo number_format($total_harganya) ?></b>&nbsp;</td>
              <td align="right"><b><?php echo number_format($harga_jualnya) ?></b>&nbsp;</td>
              <td align="right"><b><?php echo number_format($total_jual) ?></b>&nbsp;</td>
              <td align="right">&nbsp;&nbsp;</td>
            </tr>
           
          </tbody>
        </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






