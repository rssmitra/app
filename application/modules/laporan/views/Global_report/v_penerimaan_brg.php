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

      <center><h4><?php echo $title?> <br><?php echo $jenis ?></h4></center>
      <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>

      <table class="greyGridTable">
        <thead>
          <tr>
            <th>NO</th>
            <th>Kode Penerimaan</th>
            <th>Tgl Penerimaan</th>
            <th>No PO</th>
            <th>Nama Supplier</th>
            <th>No Surat Jalan</th>
            <th>Item Barang</th>
            <th>Total Barang</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          foreach($result['data'] as $row_data){
            $kode_penerimaan = $row_data->kode_penerimaan;
            $no_po = $row_data->no_po;
            $tgl_penerimaan = $row_data->tgl_penerimaan;
            $namasupplier = $row_data->namasupplier;
            $no_faktur = $row_data->no_faktur;
            $jml_brg = $row_data->jml_brg;
            $jml_krm = $row_data->jml_krm;
            $no++; 
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
               echo '<td>'.$kode_penerimaan.'</td>';
                  echo '<td>'.$tgl_penerimaan.'</td>';
                  echo '<td>'.$no_po.'</td>';
                  echo '<td>'.$namasupplier.'</td>';
                  echo '<td>'.$no_faktur.'</td>';
                  echo '<td>'.$jml_brg.'</td>';
                  echo '<td>'.$jml_krm.'</td>';
              ?>
            </tr>
          <?php 
        // endforeach; 
      }?>
        </tbody>
      </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






