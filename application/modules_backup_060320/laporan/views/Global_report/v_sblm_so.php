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
      <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>

      <table class="table">
        <thead>
          <tr>
            <th>NO</th>
             <th width="100">Nama kategori</th>
            <th width="100">Kode Barang</th>
            <th width="176">Nama Barang</th>
            <th width="100" align="center">Stok </th>
            <th width="100" align="center">Harga</th>
            <th width="100" align="center">Total Harga</th>
            <th width="100" align="center">Status Barang</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          foreach($result['data'] as $row_data){
            $harga_pembelian_terakhir  = ($row_data->harga_pembelian_terakhir==0) ? 0 : $row_data->harga_pembelian_terakhir / $row_data->content ;
            $total = ($row_data->stok_sebelum == 0) ? 0 : ($row_data->stok_sebelum * $row_data->harga_pembelian_terakhir);
            $totalr = ($row_data->stok_sebelum == 0)? 0 : ($row_data->stok_sebelum * $harga_pembelian_terakhir);
            $no++; 
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
               echo '<td>'.$row_data->nama_golongan.'</td>';
                  echo '<td>'.$row_data->kode_brg.'</td>';
                  echo '<td>'.$row_data->nama_brg.'</td>';
                  echo '<td>'.$row_data->stok_sebelum.'</td>';
                  echo '<td>'.number_format($row_data->harga_pembelian_terakhir).'</td>';
                  echo '<td>'.number_format($totalr).'</td>';
                  echo '<td>'.$row_data->set_status_aktif.'</td>';
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






