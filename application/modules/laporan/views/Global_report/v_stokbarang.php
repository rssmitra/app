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

      <table class="greyGridTable">
        <thead>
          <tr>
            <th>NO</th>
            <th width="105">Kode Barang<br/></th>
            <th width="95">Nama Barang</th>
            <th width="304">Harga Barang</th>
            <th width="184">Satuan Besar</th>
            <th width="176">Content</th>
            <th width="231">Stok Akhir</th>
            <th width="176">Harga Hasil</th>
            <th width="72">Satuan Kecil</th>            
            <th width="78">Nama Golongan</th>            
            <th width="78">Nama Kategori</th>            
            <th width="78">Nama Sub Golongan</th>           
            <th width="78">Tgl Input</th>   
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          $ttlhasil=0;
          foreach($result['data'] as $row_data){
            if($row_data->harga_update==0 || $row_data->harga_update==''){
              $harga_beli = $row_data->harga_beli;
            }
            else{
              $harga_beli = $row_data->harga_update;
            }
            $content      = $row_data->content;
            $stokakhir    = $row_data->stok_akhir;
            $harga        = ($harga_beli==0) ? 0 : $harga_beli;
            $hasil        = ($harga==0) ? 0 : $harga;
            $hasill        = $harga * $stokakhir;
            
            $ttlhasil=$ttlhasil+$hasill;
            $no++; 
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                  echo '<td>'.$row_data->kode_brg.'</td>';
                  echo '<td>'.$row_data->nama_brg.'</td>';
                  echo '<td>'.number_format($harga).'</td>';
                  echo '<td>'.$row_data->satuan_besar.'</td>';
                  echo '<td>'.$row_data->content.'</td>';
                  echo '<td>'.$row_data->stok_akhir.'</td>';
                  echo '<td>'.number_format($hasill).'</td>';
                  echo '<td>'.$row_data->satuan_kecil.'</td>';
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
      <td align="center"><?php echo number_format($ttlhasil);?></td></tr>
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






