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
      <?php
      switch($keterangan){
        case "medis":
           $ket = "Medis";
            break;
        case "nmmedis":
          $ket = "Non Medis";
            break;
      }
      foreach($result['data'] as $r_data);?>
      <center><h4><?php echo $title?>
        <br><?php echo $ket?>
      </h4></center>
      <table class="table" border="0">
        <thead>
          <tr>
            <th width="25">No.</th>
            <th>No PO</th>
            <th>Tgl PO</th>
            <th>Nama Supplier</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Jumlah Harga</td>
         
        </thead>
        <tbody>
          <?php $no  = 0; 
          $sumsatuan =0;
          $sumjumlah =0;
          foreach($result['data'] as $row_data){
           $sumsatuan +=$row_data->harga_satuan_netto;
           $sumjumlah +=$row_data->jumlah_harga_netto;
            $no++; 
            ?>
            <tr>
              <td align="right" width="25"><?php echo $no ?></td>
              <td align="center"><?php echo $row_data->no_po ?>&nbsp;</td>
              <td align="center"><?php echo $row_data->tgl_po ?></td>
              <td align="left"><?php echo $row_data->namasupplier ?>&nbsp;</td>
              <td align="left"><?php echo $row_data->kode_brg ?>&nbsp;</td>
              <td align="left"><?php echo $row_data->nama_brg ?>&nbsp;</td>
              <td align="right"><?php echo $row_data->jumlah_besar ?></td>
              <td align="right"><?php echo number_format($row_data->harga_satuan_netto) ?></td>
              <td align="right"><?php echo number_format($row_data->jumlah_harga_netto) ?></td>
            </tr>
          <?php 
        // endforeach; 
      }?>
      <td colspan="7" align="right">Total</td>
      <td align="right"><?php echo number_format($sumsatuan) ?></td>
      <td align="right"><?php echo number_format($sumjumlah) ?></td>
      </tr>            
        </tbody>
      </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






