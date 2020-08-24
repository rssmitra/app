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
              <td colspan="3">Laporan Distribusi Obat & Alkes Unit</td>
            </tr>
            
            <tr class="subTitle">
              <td width="15%">Periode Tanggal </td>
              <td>:</td>
              <td><?php echo $this->tanggal->formatDate($tgl1) ?> s/d <?php echo $this->tanggal->formatDate($tgl2) ?></td>
            </tr>
            
          </tbody>
        </table>
      <table class="table">
          <tbody>
            <tr>
              <td class="border-rb" width="25" rowspan="1" colspan="1">No.</td>
              <td>Tgl Kirim</td>
              <td>Nama Bagian</td>
              <td>Kode Brg</td>
              <td>Nama Obat/Alkes</td>
              <td>Satuan</td>
              <td>Harga Satuan</td>
              <td>Jumlah</td> 
              <td>Harga Jumlah</td>
            </tr>
            
          <?php
          $no = 0; 
            foreach($result['data'] as $row_data){
             $kode_bagian_minta=$row_data->kode_bagian;
              $tgl_input=$row_data->tgl_permintaan;
              $jumlah_penerimaan=$row_data->jumlah_penerimaan;
              $kode_brg=$row_data->kode_brg;
              $satuan_kecil=$row_data->satuan_kecil;
              $harga_beli=$row_data->harga_beli;
              // $nama_brg=$row_data->nama_brg;
              $harga_jumlah=$harga_beli * $row_data->jumlah_penerimaan;
             $nama_penerima=$row_data->nama_bagian;
             $arr_total[] = $harga_jumlah;
                $no++; 
            ?>
            
            <tr class="contentTable">
              <td align="right" width="25"><?php echo $no; ?></td>
              <td align="left"><?php echo $this->tanggal->formatDate($tgl_input);?>&nbsp;</td>
              <td align="left"><?php echo  $nama_penerima;?>&nbsp;</td>
              <td align="center"><?php echo $kode_brg;?>&nbsp;</td>
              <td align="left"><?php echo $row_data->nama_brg;?>&nbsp;</td>
              <td align="left"><?php echo $satuan_kecil;?>&nbsp;</td>
              <td align="right"><?php echo number_format($harga_beli); ?>&nbsp;</td>
              <td align="right"><?php echo number_format($jumlah_penerimaan);?>&nbsp;</td>
              <td align="right"><?php echo number_format($harga_jumlah);?>&nbsp;</td>
            </tr>
          <?php 
        // endforeach; 
      }?>
          <tr>
            <td colspan="8" align="right">TOTAL</td>
            <td align="right"><?php echo number_format(array_sum($arr_total)) ?></td>
          </tr>
           
          </tbody>
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






