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
      foreach($result['data'] as $r_data);?>
      <center><h4><?php echo $title?></h4></center>
      <b>Tanggal :</b> <b><i><?php echo $tanggal?></i></b>
      <br>
      <br>
      <table class="table" border="1">
        <thead>
          <tr>
             <td width="55" align="center">NO</td>
             <td width="60" align="center">No MR</td>
             <td width="100" align="center">Nama Pasien</td>
             <td width="100" align="center">Nama Dokter</td>
             <td width="100" align="center">Tujuan</td>
             <td width="100" align="center">Nasabah</td>
             <td width="100" align="center">No SEP</td>
            
         
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
                  echo '<td>'.$row_data->no_mr.'</td>';
                  echo '<td>'.$row_data->nama_pasien.'</td>';
                  echo '<td>'.$row_data->nama_pegawai.' </td>';
                  echo '<td>'.$row_data->nama_bagian.' </td>';
                  echo '<td>'.$row_data->nama_perusahaan.' </td>';
                  echo '<td>'.$row_data->no_sep.'</td>';
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






