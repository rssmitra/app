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

      <center><h4><?php echo $title?></h4></center>
      <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>

      <table class="greyGridTable">
        <thead>
          <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2" width="105">Kode Barang<br/></th>
            <th rowspan="2" width="95">Nama Barang</th>
            <th rowspan="2" width="304">HPP Satuan</th>
            <th rowspan="2" width="304">Harga Jual Satuan</th>
            <th width="304" colspan="2">Saldo Awal</th>
            <th width="304" colspan="2">Penerimaan/Pembelian</th>
            <th width="304" colspan="2">Penjualan ke Pasien BPJS</th>
            <th width="304" colspan="2">Penjualan Umum</th>
            <th width="304" colspan="3">Penggunaan Internal</th>
            <th width="304" colspan="3">Distribusi & ALokasi Unit</th>
            <th width="304" colspan="2">Saldo Akhir</th>
          </tr>
          <tr>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Unit</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Unit</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          foreach($result['data'] as $row_data){
            // $saldopenerimaan=$row_data->jumlah_kirim * $row_data->harga_beli;
            $no++; 
            // data bpjs search arrray
            $key_bpjs = $this->master->searchArray($row_data->kode_brg, 'kode_brg', $dt_pjl_bpjs);
            if($row_data->kode_brg == $dt_pjl_bpjs[$key_bpjs]['kode_brg']){
              $qty = isset($dt_pjl_bpjs[$key_bpjs])?$dt_pjl_bpjs[$key_bpjs]['jumlah_tebus']:0;
              $hbpjs = isset($dt_pjl_bpjs[$key_bpjs])?$dt_pjl_bpjs[$key_bpjs]['harga_jual']:0;
            }else{
              $qty = 0;
              $hbpjs = 0;
            }
            //penerimaan
            $key_penerimaan = $this->master->searchArray($row_data->kode_brg, 'kode_brg', $v_penerimaan);
            if($row_data->kode_brg == $v_penerimaan[$key_penerimaan]['kode_brg']){
              $qty_p = isset($v_penerimaan[$key_penerimaan])?$v_penerimaan[$key_penerimaan]['jumlah_kirim']:0;
            }else{
              $qty_p = 0;
            }
            $saldopenerimaan=$qty_p * $row_data->harga_beli;
            $j_bpjs=$qty * $hbpjs;
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                echo '<td>'.$row_data->kode_brg.'</td>';
                echo '<td>'.$row_data->nama_brg.'</td>';
                echo '<td>'.number_format($row_data->harga_beli).'</td>';
                echo '<td>'.number_format($row_data->harga_jual).'</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td>'.$qty_p.'</td>';
                echo '<td>'.number_format($saldopenerimaan).'</td>';
                echo '<td>'.$qty.'</td>';
                echo '<td>'.number_format($j_bpjs).'</td>';
              ?>
            </tr>
          <?php } ?>
        </tbody>
      </table>

    </div>
  </div>
</body>
</html>






