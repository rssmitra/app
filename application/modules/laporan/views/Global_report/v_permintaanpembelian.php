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
      <b>Status :</b> <b><i><?php echo $status=="0" ? "ACC" : ($status=="1" ? "Tidak ACC" : "Semua")?></i></b>
      <br>
      <br>
       <table class="greyGridTable">
        <thead>
          <tr>
            <th width="55">NO</th>
            <th align="center">Kode Permohonan</th>
            <th align="center">Tgl Permohonan</th>
            <th align="center">Kode Barang</th>
            <th align="center">Nama Barang</th>
            <th align="center">Jumlah Permohonan</th>
            <th align="center">Jml ACC<br>Verifikator I</th>
            <th align="center">Jml ACC<br>Verifikator II</th>
        </thead>
        <tbody>
          <?php $no = 0; 
          foreach($result['data'] as $row_data){
           
            $no++; 
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                  echo '<td>'.$row_data->kode_permohonan.'</td>';
                  echo '<td>'.$row_data->tgl_permohonan.'</td>';
                  echo '<td>'.$row_data->kode_brg.'</td>';
                  echo '<td style="text-align: left">'.$row_data->nama_brg.'</td>';
                  echo '<td>'.number_format($row_data->jml_besar, 2).'</td>';
                  echo '<td>'.number_format($row_data->jml_besar_acc, 2).'</td>';
                  echo '<td>'.number_format($row_data->jml_acc_penyetuju, 2).'</td>';
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






