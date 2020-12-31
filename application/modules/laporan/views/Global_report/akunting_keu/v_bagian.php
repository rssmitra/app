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

      <center><span style="font-size: 14px; font-weight: bold">Rekapitulasi Saldo Akhir Obat per Unit<br>Bulan <?php echo $this->tanggal->getBulan($month)?> Bagian </span> </center>

      <br>

      <?php 
        foreach($v_saldo as $key_bag => $val_bag){
          foreach ($val_bag as $row_brg) {
            $stok_akhir[$key_bag] = ($row_brg['stok_akhir'] > 0) ? $row_brg['stok_akhir']:0;
            $harga_beli[$key_bag] = isset($harga_beli[trim($row_brg['kode_brg'])])?$harga_beli[trim($row_brg['kode_brg'])]:0;
            $saldo = $stok_akhir[$key_bag] * $harga_beli[$key_bag];
            $saldo_akhir[$key_bag][] = $saldo;
          }
        }
          // echo '<pre>';print_r($saldo_akhir);die;
        
        
      ?>
      <table class="table table-bordered">
        <thead>
          <tr style="text-align: center">
            <th width="50">No</th>
            <th width="105">Nama Bagian</th>
            <!-- <th width="304">Saldo Akhir</th> -->
          </tr>
         
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            
          foreach($v_saldo as $k_bag => $v_bag){
            $no++; 
            $saldo_bagian = array_sum($saldo_akhir[$k_bag]);
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                echo '<td><a href="'.base_url().'laporan/Global_report/show_data_bmhp?kode_bagian='.$v_bag[0]['kode_bagian'].'&month='.$month.'&year='.$year.'&flag=akunting_mod_3&submit=form" target="_blank">'.ucwords($k_bag).'</td>';
                // echo '<td>'.$saldo_bagian.'</td>';
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






