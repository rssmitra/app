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

      <center><h4><?php echo $title?> <br><?php echo $jenis ?></h4></center>
      <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>

      <table width="100%">
        <thead>
          <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">Kode Penerimaan</th>
            <th rowspan="2">Tgl Penerimaan</th>
            <th rowspan="2">No Surat Jalan</th>
            <th rowspan="2">Kode Barang</th>
            <th rowspan="2">Nama Barang</th>
            <th rowspan="2">Supplier</th>
            <th colspan="2">Jumlah Besar</th>
            <th rowspan="2">Satuan Besar</th>
            <th rowspan="2">Rasio</th>
            <th rowspan="2">Harga Satuan</th>
            <th rowspan="2">Harga Satuan Netto</th>
            <th rowspan="2">Jumlah Harga Satuan</th>
            <th rowspan="2">Jumlah Harga Satuan Netto</th>
          </tr>
          <tr>
          <th>Pesan</th>
          <th>Diterima</th>
        </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          foreach($result['data'] as $row_data){
            $kode_penerimaan = $row_data->kode_penerimaan;
            $tgl_penerimaan = $row_data->tgl_penerimaan;
            $no_faktur = $row_data->no_faktur;
            $kode_brg = $row_data->kode_brg;
            $kode_penerimaan = $row_data->kode_penerimaan;
            $jumlah_pesan = $row_data->jumlah_pesan;
            $jumlah_kirim = $row_data->jumlah_kirim;
            $content = $row_data->content;
            $no++; 
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
               echo '<td>'.$kode_penerimaan.'</td>';
                  echo '<td>'.$tgl_penerimaan.'</td>';
                  echo '<td>'.$no_faktur.'</td>';
                  echo '<td>'.$kode_penerimaan.'</td>';
                  echo '<td>'.$row_data->nama_brg.'</td>';
                  echo '<td>'.$row_data->namasupplier.'</td>';
                  echo '<td>'.$jumlah_pesan.'</td>';
                  echo '<td>'.$jumlah_kirim.'</td>';
                  echo '<td>'.$row_data->satuan_besar.'</td>';
                  echo '<td>'.$content.'</td>';
                  echo '<td>'.number_format($row_data->harga_satuan).'</td>';
                  echo '<td>'.number_format($row_data->harga_satuan_netto).'</td>';
                  echo '<td>'.number_format($row_data->jumlah_harga).'</td>';
                  echo '<td>'.number_format($row_data->jumlah_harga_netto).'</td>';
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






