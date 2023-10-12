<?php 
  $submit = isset($_POST['submit'])?$_POST['submit']:$_GET['submit'];
  foreach ($result['data'] as $key => $value) {
    $getBrg[$value->kode_brg] = $value->nama_brg;
    $getData[$value->bulan][$value->kode_brg] = $value;
  }
  ksort($getData);

  // echo '<pre>';print_r($getData);die;
  // echo '<pre>';print_r($getBrg);die;
  // echo '<pre>';print_r($getData[1]['E03J0102']->sisa_hutang);die;
  if($submit=='excel') {
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
  <div class="row" style="padding-left: 5px; padding-right: 5px">
    <div class="col-xs-12">

      <center><span style="font-size: 14px; font-weight: bold">Rekapitulasi Hutang Obat Pasien<br>Bulan <?php echo $this->tanggal->getBulan($_POST['from_month'])?> Tahun <?php echo $_POST['year']?> <br> </span> </center>

      <br>

      <table class="table table-bordered" >
        <thead>
          <tr style="text-align: center">
            <th rowspan="2">No</th>
            <th rowspan="2" >Kode Barang<br/></th>
            <th rowspan="2" >Nama Barang</th>
            <th colspan="<?php echo count($getData)*2?>" style="text-align: center" >Bulan</th>
            <th rowspan="2" colspan="2" style="text-align: center">Total</th>
          </tr>
          <tr>
            <?php foreach($getData as $key_bln=>$row_bln) :?>
              <td colspan="2" style="text-align: center"><?php echo $key_bln; ?></td>
            <?php endforeach;?>
          </tr>
        </thead>
        <tbody>
            <?php $no=0; foreach($getBrg as $key_brg=>$row_brg) : $no++; ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <td align="center"><?php echo $key_brg;?></td>
              <td><?php echo $row_brg;?></td>
              <?php 
                foreach($getData as $key_bln=>$row_bln) :
                  $dt = isset($getData[$key_bln][(string)$key_brg])?$getData[$key_bln][(string)$key_brg]->sisa_hutang:0;
                  $hj = isset($getData[$key_bln][(string)$key_brg])?$getData[$key_bln][(string)$key_brg]->rata_harga_jual:0;
                  $sisa_hutang = ($dt == 0) ? 0 : ( $dt <= 0 ) ? 0 : $dt ;
                  $harga = ($dt == 0) ? 0 : ($dt <= 0) ? 0 : (float)$dt * (float)$hj;
                  $arr_hutang[$key_brg][] = $sisa_hutang;
                  $arr_harga[$key_brg][] = $harga;
              ?>
              <td align="center"><?php echo $sisa_hutang;?></td>
              <td align="right"><?php echo $harga;?></td>
              <?php endforeach; ?>
              <td align="right"><?php echo array_sum($arr_hutang[$key_brg])?></td>
              <td align="right"><?php echo array_sum($arr_harga[$key_brg])?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
      <br>
    </div>
  </div>
</body>
</html>






