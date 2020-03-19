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

      <center><h4><?php echo $title?>
                <br> <?php echo $resultt->nama_bagian ?></h4></center>
        <table class="table">
          <tbody>
            <tr class="mainTitleLeft">
              <td colspan="3" style="white-space: nowrap; color: #000099;">
              </td>
            </tr>
            <tr class="mainTitle">
              <td colspan="3">Laporan Pemesanan Resep </td>
            </tr>
            
            <tr class="subTitle">
              <td width="15%">PERIODE </td>
              <td>:</td>
              <td><?php echo $tgl1 ?> s/d <?php echo $tgl2 ?></td>
            </tr>
            
          </tbody>
        </table>
      <table class="table">
          <tbody>
            <tr>
              <td class="border-rb" width="25" rowspan="1" colspan="1">No.</td>
              <td class="border-rb" rowspan="1" colspan="1">No Pesan</td>
              <td class="border-rb" rowspan="1" colspan="1">Nama Dokter</td>
              <td class="border-rb" rowspan="1" colspan="1">Tgl Pesan</td>
              <?php 
              if($lokasi==1){?>
              <td rowspan="1" colspan="1">Status</td>
              <?php
              }
              ?>
              <?php
              if ($bagian==""){?>
              <td rowspan="1" colspan="1">Bagian</td>
              <?php
              }
              ?>
              
            </tr>
            
          <?php
           $no = 0; 
             
          foreach ($result['data'] as $row_data){
            $no ++;
               
            ?>
            
             <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                  echo '<td>'.$row_data->kode_pesan_resep.'</td>';
                  echo '<td>'.$row_data->nama_pegawai.'</td>';
                  echo '<td>'.$row_data->tgl_pesan.'</td>';
                  if($lokasi==1){?>
                  <td align="left"><?php echo ($row_data->status_tebus==1)?"Sudah Di Tebus":"Belum Di Tebus" ?>&nbsp; </td>
                  <?php
                  }
                  if ($bagian==""){
                  ?>
                  <td align="left"><?php echo $nama_bagian ?>&nbsp; </td>
                  <?php
                  }
                  ?>
              
              </tr>
            
            <?php
              }
            ?>
          
          </tbody>
        </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






