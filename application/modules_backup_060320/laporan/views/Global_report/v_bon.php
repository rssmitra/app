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
      <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>

      <table class="table">
        <thead>
          <tr>
            <th>NO</th>
            <th width="105">No Trans<br/></th>
            <th width="95">No. Mr</th>
            <th width="304">Nama Pasien</th>
            <th width="184">Nasabah</th>
            <th width="176">Tgl Transaksi</th>
            <th width="231">Nilai Transaksi (Rp.)</th>
            <th width="72">Potongan</th>            
            <th width="78">Tagihan</th>   
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          foreach($result['data'] as $row_data){
            $bill_rs      = $row_data->bill_rs;
            $disc         = $row_data->fak_kali_obat;
            $lain_lain    = $row_data->lain_lain;
            $bill         = $bill_rs+$lain_lain;
            $disc_rp      = $bill*($disc/100);
            $nil_trans    = $bill-$disc_rp;
            $no++; 
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
               echo '<td>'.$row_data->kode_trans_far.'</td>';
                  echo '<td>'.$row_data->no_induk.'</td>';
                  echo '<td>'.$row_data->penjamin.'</td>';
                  echo '<td>'.$row_data->nama_kelompok.'</td>';
                  echo '<td>'.$row_data->tgl_trans.'</td>';
                  echo '<td>'.$row_data->bill_rs.'</td>';
                  echo '<td>'.$disc_rp.'</td>';
                  echo '<td>'.$nil_trans.'</td>';
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






