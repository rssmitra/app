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
      <br>
      <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>
      <br>
      <table class="table">
        <thead>
          <tr>
            <th>NO</th>
            <th width="100">Kode Barang</th>
            <th>Nama Barang</th>
            <th width="150px">Jumlah Distribusi</th>
            <th width="150px">Harga Satuan (Rp.)</th>  
            <th width="160px">Total Pengeluaran (Rp.)</th>    
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          $total=0;
          foreach($result as $key_dt=>$rw){
            $no++; 
            echo "<tr style='font-weight: bold'><td align='center'>".$no."</td><td colspan='5'>".strtoupper($key_dt)."</td></tr>";
            foreach($rw as $row_data){
            $hargabeli=$row_data->total * $row_data->harga_beli;
            $total=$total+$hargabeli;
            
            ?>
              <tr>
                <td align="center"></td>
                <?php 
                    echo '<td>'.$row_data->kode_brg.'</td>';
                    echo '<td>'.$row_data->nama_brg.'</td>';
                    echo '<td align="center">'.$row_data->total.'</td>';
                    echo '<td align="right">'.$row_data->harga_beli.'</td>';
                    echo '<td align="right">'.$hargabeli.'</td>';
                ?>
              </tr>
            <?php 
            }
          }?>
          <tr>
            <td colspan="5" align="right">TOTAL</td>
          <?php echo '<td align="right">'.$total.'</td>';?>
          </tr>
        </tbody>
      </table>
<!-- <table border="0" width="100%">
  <tr>
  <td colspan="2" valign="bottom" style="padding-top:25px" align="right"> Jakarta, ..........................</td>
    <tr><td valign="bottom" style="padding-top:25px" align="right">
    <b>Mengetahui<br><br><br><br><br><br>_________________________
  </td>
  <td valign="bottom" style="padding-top:25px" align="right">
    <b>Petugas<br><br><br><br><br><br>_________________________
  </td>
</tr>
</table> -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






