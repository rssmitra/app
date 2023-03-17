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
  <title><?php echo $title?></title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/blue.css"/>
</head>
<body>
  <div class="row">
    <div class="col-xs-12">

      <center><h4><?php echo strtoupper($title)?></h4></center>
      <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>

      <br>
      <table class="table">
      <thead style="background: grey; color: white">
          <tr class="mainTitleLeft">
            <th>NO</th>
            <th>NAMA DOKTER</th>
            <th>NAMA POLI/KLINIK</th>
            <th>TGL KUNJUNGAN</th>
            <th>JAM PRAKTEK</th>
            <th>JAM MULAI</th>
            <th>WAKTU KETERLAMBATAN</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            foreach($result as $row) : 
              if($row['jam'] >= 0) :
            $no++; ?>
            <tr class="mainTitleLeft">
              <td><?php echo $no; ?></td>
              <td><?php echo $row['dokter']; ?></td>
              <td><?php echo $row['bagian']; ?></td>
              <td><a href="<?php echo base_url().'laporan/Global_report/show_detail_keterlambatan?kode_bagian='.$_POST['kode_bagian'].'&kode_dokter='.$_POST['kode_dokter'].'&tgl='.$row['tgl_kunjungan_ori'].'&dokter='.$row['dokter'].'&bagian='.$row['bagian'].''?>" target="_blank"><?php echo $row['tgl_kunjungan']; ?></a></td>
              <td><?php echo $row['jam_praktek']; ?></td>
              <td><?php echo $row['jam_mulai']; ?></td>
              <td><?php echo $row['waktu_keterlambatan']; ?></td>
            </tr>
          <?php 
          endif; 
        endforeach; ?>
        </tbody>
      </table>
      
    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






