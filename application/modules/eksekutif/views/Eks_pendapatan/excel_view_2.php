<?php 
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=".'lhk_exp_date_'.date('Ymd').".xls");  //File name extension was wrong
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false);
?>

<html>
<head>
  <title>Export Data to Excel</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
</head>
<body>
  <div class="row">
    <div class="col-xs-12">

      <center><h4><?php echo $title?></h4></center>
      <b>Parameter :</b> <i><?php echo print_r($_GET);?></i>

      <?php 
  foreach($data_pasien as $key=>$row) : 
        foreach($fields as $k=>$r) {
          $total = isset($data_trans[$key][$k]->total_billing)?$data_trans[$key][$k]->total_billing:0;
          $arr_sub_total[$k][] = $total;
          $getData[$k][] = $total;
        }
  endforeach; 
?>

<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th class="center">No</th>
      <th width="90px">No. Kuitansi</th>
      <th width="120px">Tanggal</th>
      <th>No RM</th>
      <th>Pasien</th>
      <th>Penjamin</th>
      <th>No SEP</th>
      <th>Bagian Masuk</th>
      <?php 
        foreach($fields as $k=>$r) {
          echo "<th class='center'>[".$k.']<br>'.$r."</th>";
        }
      ?>
      <th>Total</th>
    </tr>
  </thead>
  <?php $no=0; foreach($data_pasien as $key=>$row) : $no++;?>
    <tr>
      <td align="center"><?php echo $no;?></td>
      <td><?php echo $row['seri_kuitansi'].'-'.$row['no_kuitansi'];?></td>
      <td><?php echo $row['tgl_jam'];?></td>
      <td><?php echo $row['no_mr'] ?></td>
      <td><?php echo $row['nama_pasien'];?></td>
      <td><?php echo $row['nama_perusahaan'];?></td>
      <td><?php echo ($row['kode_perusahaan'] == 120) ? "".$row['no_sep']."" : ""?></td>
      <td><?php echo $row['nama_bagian'];?></td>
      <?php 
        foreach($fields as $k=>$r) {
          $total = isset($data_trans[$key][$k]->total_billing)?$data_trans[$key][$k]->total_billing:0;
          $arr_sub_total[$key][] = $total;
          $arr_total[$k][] = $total;
          echo '<td align="right">'.$total.'</td>';
        }
      ?>
      <td align="right"><b><?php $arr_total_all[] = array_sum($arr_sub_total[$key]); echo array_sum($arr_sub_total[$key])?></b></td>
    </tr>
  <?php endforeach; ?>
  <tr>
    <td colspan="8" align="right"><b>TOTAL PER JENIS TINDAKAN</b></td>
    <?php 
      foreach($fields as $k=>$r) {
        $total_jt = array_sum($arr_total[$k]);
        echo '<td align="right"><b>'.$total_jt.'</b></td>';
      }
      $total_all = array_sum($arr_total_all);
      echo '<td align="right"><b>'.$total_all.'</b></td>';
    ?>
    
  </tr>
</table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






