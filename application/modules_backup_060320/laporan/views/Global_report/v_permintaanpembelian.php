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
      <table class="table" border="1">
        <thead>
          <tr>
            <th width="55" rowspan="2" colspan="1">NO</th>
            <th width="100" colspan="2" align="center">Permohonan</th>
            <?php if ($status!="1"){?>
            <th width="100" colspan="2" align="center">Persetujuan</th>
          <?php }
          ?>
           <th width="120" rowspan="2" colspan="1">Jumlah</th>
           <?php if ($status==""){?>
            <th width="176" rowspan="2" colspan="1">Status</th>
          <?php } ?>
          </tr>
           <tr>
            <th width="100" colspan="1" align="center">Kode</th>
            <th width="100" colspan="1" align="center">Tanggal</th>
            <?php if ($status!="1"){?>
            <th width="100" colspan="1" align="center">Kode</th>
           <th width="120" rowspan="2" colspan="1" align="center">Tanggal</th>
            <?php 
          }
          ?>
          </tr>
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
              if ($status!="1"){
                  echo '<td>'.$row_data->no_acc.' </td>';
                  echo '<td>'.$row_data->tgl_acc.' </td>';
                }
                  echo '<td>'.$row_data->jml_brg.'</td>';
               if ($status==""){  
                  echo '<td>'.$row_data->status_batal=="0" ? "ACC" : ($row_data->status_batal=="1" ? "Tidak ACC" : "Belum Disetujui").'</td>';
                }
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






