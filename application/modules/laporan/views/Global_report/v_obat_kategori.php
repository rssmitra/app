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
// switch($_POST['obat_alkes']){
//      case "A":
//        $nama_jenis= "ALL";
//      break;
//      case "D":
//        $nama_jenis= "OBAT";
//      break;
//      case "E":
//        $nama_jenis= "ALKES";
//      break;
//    }

//    switch($_POST['kode_profit']){
    
//     case "1111":
//      $nama_nasabah="ALL";
//     break;
//      case "666":
//      $nama_nasabah="Karyawan RS";
//     break;
//     case "4000":
//      $nama_nasabah="Pembelian Bebas";
//     break;
//     case "3000":
//      $nama_nasabah="Resep Luar";
//     break;
//     case "2000":
//      $nama_nasabah="Rawat Jalan";
//     break;
//     case "1000":
//      $nama_nasabah="Rawat Inap";
//     break;
//     }
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
              <td colspan="3">Laporan Penjualan Obat/Alkes  </td>
            </tr>
           <!--  <tr class="subTitle">
              <td width="15%">OBAT/ALKES </td>
              <td>:</td>
              <td><?php //echo $nama_jenis ?></td>
            </tr>
            <tr class="subTitle">
              <td width="15%">KATEGORI </td>
              <td>:</td>
              <td><?php //echo $nama_nasabah?></td>
            </tr> -->
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
              <td class="border-rb" width="" rowspan="1" colspan="1">Kode Brg</td>
              <td class="border-rb" width="" rowspan="1" colspan="1">Nama Barang</td>
              <td class="border-rb" width="" rowspan="1" colspan="1">Satuan Kecil</td>
              <td class="border-rb" width="" rowspan="1" colspan="1">Qty</td>
              <td class="border-rb" width="" rowspan="1">Harga</td>
              <td class="border-rb" width="" rowspan="1" colspan="1">Jumlah</td>
              
            </tr>
            
          <?php
           $no = 0; 
             
         // echo '<pre>';print_r($result['data']) or die;
           $tot_jumlah_akhir=0;
           $tot_harga_akhir=0;
          foreach ($result['data'] as $row_data){
            $no ++;
               
               $no++;
                //$tgl_trans = $tampil["tgl_trans"];
                $kode_brg       = $row_data->kode_brg;
                $nama_brg       = $row_data->nama_brg;
                $jumlah_tebus     = $row_data->jml_tebus;
                $jumlah_retur     = $row_data->jml_retur;
                $net_qty      = $row_data->net_qty;
                // $harga_jual     = $row_data->harga_jual;
                $net_rp         = $row_data->net_rp;
                if($net_rp==0) {
                    $harga_rata = 0;
                } else {
                    $harga_rata = $net_rp/$net_qty;
                }

                /*//$harga_jual_akhir = $harga_jual * $jumlah_akhir;
                $harga_r_akhir = $harga_r - $harga_r_retur;
                //$harga_rata2=($harga_jual*$jumlah_akhir)/$jumlah_akhir;*/
                $tot_jumlah_akhir  = $tot_jumlah_akhir + $net_qty;
                $tot_harga_akhir  = $tot_harga_akhir + $net_rp;
                ?>
            <tr class="contentTable">
              <td align="right" width="25"><?php echo   $no  ?>.</td>
              <td align="left" width="">&nbsp;<?php echo $kode_brg?>&nbsp;</td>
              <td align="left" width="">&nbsp;<?php echo $nama_brg?>&nbsp;</td>
              <td align="left" width=""><?php echo $row_data->satuan_kecil?>&nbsp;</td>
              <td align="right" width=""><?php echo number_format($net_qty)?></td>
              <td align="right" width=""><?php echo number_format($harga_rata)?></td>
              <td align="right" width=""><?php echo number_format($net_rp)?></td>
            </tr>
            <?php
            }
            ?>
            <tr class="headTable">
              <td align="right" width="25">&nbsp;</td>
              <td align="right" colspan="3"><b>T O T A L</b>&nbsp;&nbsp;&nbsp;</td>
              <td align="right" width=""><?php echo number_format($tot_jumlah_akhir)?></td>
              <td align="right" width="">&nbsp;</td>
              <td align="right" width=""><?php echo number_format($tot_harga_akhir)?></td>
              
            </tr>
          
          </tbody>
        </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






