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

      <center><h4>Rekap Stok Awal, Penerimaan, Pemakaian Obat </h4></center>
      <!-- <b>Parameter :</b> <i><?php echo print_r($_POST);?></i> -->

      <table class="table table-bordered">
        <thead>
          <tr style="text-align: center">
            <th width="50">No</th>
            <th width="90">Kode Bagian<br/></th>
            <th width="105">Nama Bagian</th>
            <th width="304">Keterangan</th>
          </tr>
         
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            
          foreach($result as $row_data){
// echo '<pre>';print_r($row_data);die;
            $no++; 
            
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                echo '<td>'.$row_data->kode_bagian.'</td>';
                echo '<td>'.$row_data->nama_bagian.'</td>';
                echo '<td></td>';
              ?>
            </tr>
          <?php 
          } 
          ?>
        </tbody>
      </table>
      <br>
    </div>
  </div>
</body>
</html>






