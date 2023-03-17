<?php 

  if(isset($_GET['submit']) AND $_GET['submit']=='excel') {
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

      <center><h4><?php echo $title?></h4></center>

      <table class="table">
        <thead style="background: grey; color: white">
          <tr class="mainTitleLeft">
            <th>NO</th>
            <th>NAMA DOKTER</th>
            <th>NAMA POLI/KLINIK</th>
            <th>NO MR</th>
            <th>NAMA PASIEN</th>
            <th>WAKTU DAFTAR</th>
            <th>WAKTU SELESAI</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            foreach($result as $row) : 
            $no++; ?>
            <tr class="mainTitleLeft">
              <td><?php echo $no; ?></td>
              <td><?php echo $_GET['dokter']; ?></td>
              <td><?php echo $_GET['bagian']; ?></td>
              <td><?php echo $row->no_mr; ?></td>
              <td><?php echo $row->nama_pasien; ?></td>
              <td><?php echo $row->tgl_jam_poli; ?></td>
              <td><?php echo $row->tgl_keluar_poli; ?></td>
            </tr>
          <?php 
        endforeach; ?>
        </tbody>
      </table>
      
    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






