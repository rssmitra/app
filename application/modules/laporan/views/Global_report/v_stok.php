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

      <table class="table">
        <thead>
          <tr>
            <th>NO</th>
            <th width="105">Kode Barang<br/></th>
            <th width="150">Nama Barang</th>
            <th width="304">Harga Satuan Kecil</th>
            <th width="100">Satuan</th>
            <th width="100">Rasio</th>
            <th width="100">Stok Akhir</th>
            <th width="150">Total Persediaan</th>
            <th width="150">Nama Golongan</th>            
            <th width="150">Nama Kategori</th>            
            <th width="150">Nama Sub Golongan</th>           
            <th width="150">Tgl Input</th>   
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          $totalhasil=0;
          foreach($result['data'] as $row_data){
            $content = $row_data->content;
            $stokakhir = $row_data->stok_akhir;
            $harga_satuan_besar = ($row_data->harga_satuan_po == 0 ) ? 0 : $row_data->harga_satuan_po;
            $harga_satuan_kecil = ($harga_satuan_besar == 0) ? 0 : $harga_satuan_besar / $content;
            $total_persediaan = ($harga_satuan_kecil == 0) ? 0 : $harga_satuan_kecil * $stokakhir;
            $arr_total_persediaan[]   = round($total_persediaan);
            $no++; 
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
               echo '<td>'.$row_data->kode_brg.'</td>';
                  echo '<td>'.$row_data->nama_brg.'</td>';
                  echo '<td>'.$harga_satuan_besar.'</td>';
                  echo '<td>'.$row_data->satuan_besar.' / '.$row_data->satuan_kecil.'</td>';
                  echo '<td>'.$row_data->content.'</td>';
                  echo '<td>'.$row_data->stok_akhir.'</td>';
                  echo '<td>'.round($total_persediaan).'</td>';
                  echo '<td>'.$row_data->nama_golongan.'</td>';
                  echo '<td>'.$row_data->nama_kategori.'</td>';
                  echo '<td>'.$row_data->nama_sub_golongan.'</td>';
                  echo '<td>'.$row_data->tgl_input.'</td>';
              ?>
            </tr>
          <?php 
        // endforeach; 
      }?>
      <tr><td align="right" colspan="7">Total</td>
      <td align="center"><?php echo array_sum($arr_total_persediaan);?> </td></tr>
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






