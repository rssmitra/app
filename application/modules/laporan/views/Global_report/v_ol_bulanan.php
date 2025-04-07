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
  <?php
switch($jenis){
     case "Obat":
       $nama_jenis= "OBAT";
     break;
     case "Alkes":
       $nama_jenis= "ALKES";
     break;
     case "All":
       $nama_jenis= "SELURUHNYA";
     break;
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
  ?>
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
              <td colspan="3">Laporan Pemesanan Resep </td>
            </tr>
            <tr class="subTitle">
              <td width="15%">OBAT/ALKES </td>
              <td><?php echo $nama_jenis ?></td>
            </tr>
           
            <tr class="subTitle">
              <td width="15%">PERIODE </td>
              <td><?php echo $nmbln ?> - <?php echo $tahun ?></td>
            </tr>
            
          </tbody>
        </table>
      <table class="table">
          <tbody>
            <tr>
              <td class="border-rb" width="25" rowspan="1" colspan="1">No.</td>
              <td width="86" rowspan="1" align="center"><b>No PO</b></td>
              <td width="86" rowspan="1" align="center"><b>Tgl PO</b></td>
              <td width="86" rowspan="1" align="center"><b>Kode</b></td>
              <td width="239" colspan="1" rowspan="1" align="center"><b>Nama Obat</b></td>
              <td width="253" colspan="1" rowspan="1" align="center"><b>Pabrik</b></td>
              <td width="134" colspan="1" rowspan="1" align="center"><b>Rasio</b></td>
              <!-- <td width="86" rowspan="1" align="center"><b>Rasio</b></td> -->
              <td width="86" rowspan="1" align="center"><b>Diskon (%)</b></td>
              <td width="99" colspan="1" rowspan="1" align="center"><b>Jml Diterima</b></td>
              <td width="99" colspan="1" rowspan="1" align="center"><b>Satuan</b></td>
              <td width="165" colspan="1" rowspan="1" align="center"><b>Harga Satuan</b></td>
              <td width="166" colspan="1" rowspan="1" align="center"><b>Total Biaya</b></td>
              
            </tr>
            
          <?php
            $no = 0; 
            $sub_harga_beli=0;
            foreach($result as $row_data){
            $no ++;
            $total = $row_data->jumlah_kirim_decimal * $row_data->harga;
            $disc = $total * $row_data->disc/100;
            $fix_total = $total - $disc;
            $arr_fix_total[] = $fix_total;
          ?>
            <tr class="contentTable">
              <td align="right" width="25"><?php echo   $no?>.</td>
              <td align="left" width=""><?php echo $row_data->no_po?></td>
              <td align="left" width=""><?php echo $this->tanggal->formatDateDmy($row_data->tgl_po)?></td>
              <td align="left" width=""><?php echo $row_data->kode_brg?></td>
              <td align="left" width=""><?php echo $row_data->nama_brg?></td>
              <td width=""><?php echo $row_data->nama_pabrik?></td>
              <td align="left" width=""><?php echo $row_data->content.'&nbsp;'.$row_data->satuan_kecil.'/'.$row_data->satuan_besar?></td>
              <!-- <td align="left" width=""><?php echo $row_data->content?></td> -->
              <td align="left" width=""><?php echo $row_data->disc?></td>
              <td align="right" width=""><?php echo $row_data->jumlah_kirim_decimal?></td>
              <td align="left" width=""><?php echo $row_data->satuan_besar?></td>
              <td align="right" width=""><?php echo $row_data->harga?></td>
              <td align="right" width=""><?php echo $fix_total?></td>
            </tr>
            <?php } ?>
           <tr class="contentTable">
              <td align="right" width="25" colspan="11">Total Pembelian</td>
              <td align="right" width="25"><?php echo number_format(array_sum($arr_fix_total))?></td>
            </tr>
          </tbody>
        </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






