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
            <th rowspan="2" width="95">Nama Bagian</th>
            <th rowspan="2" width="304">HPP Satuan</th>
            <th rowspan="2" width="304">Harga Jual Satuan</th>
            <th width="304" colspan="2">Saldo Awal</th>
            <th width="304" colspan="2">Penerimaan/Pembelian</th>
            <th width="304" colspan="2">Penjualan ke Pasien BPJS</th>
            <th width="304" colspan="2">Penjualan Umum</th>
            <th width="304" colspan="2">Penggunaan Internal</th>
            <th width="304" colspan="2">Distribusi & ALokasi Unit</th>
            <th width="304" colspan="2">Saldo Akhir</th>
            <th rowspan="2" width="105">Qty <br/></th>
            <th rowspan="2" width="105">Keterangan<br/></th>
            
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
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
            <th width="304">Quantity</th>
            <th width="304">Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
            $jmlpenerimaan=0;
            $jmlpenjualanbpjs=0;
            $jmlpenjualanumum=0;
            $penjualanintrnal=0;
            $jmldistribusi=0;
            $jmlakhir=0;
            $jmlsaldoakhir=0;

          foreach($result['data'] as $row_data){
            // $saldopenerimaan=$row_data->jumlah_kirim * $row_data->harga_beli;
            $no++; 
            // data bpjs search arrray
            $key_saldo = $this->master->searchArray($row_data->kode_brg, 'kode_brg', $v_saldo);
            if($row_data->kode_brg == $v_saldo[$key_saldo]['kode_brg']){
              $qtys = isset($v_saldo[$key_saldo])?$v_saldo[$key_saldo]['stok_akhir']:0;
              // $saldoa = isset($v_saldo[$key_saldo])?$v_saldo[$key_saldo]['harga_jual']:0;
            }else{
              $qtys = 0;
              // $saldoa = 0;
            }

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
              $qty_p = isset($v_penerimaan[$key_penerimaan])?$v_penerimaan[$key_penerimaan]['jumlah_kirim'] * $v_penerimaan[$key_penerimaan]['content']:0;
              // $hjual = isset($v_penerimaan[$key_penerimaan])?$v_penerimaan[$key_penerimaan]['hargajual']:0;
            }else{
              $qty_p = 0;
              // $hjual = 0;
            }
            $saldopenerimaan=$qty_p * $row_data->harga_beli;
            $j_bpjs=$qty * $hbpjs;
            
            //umum
            $key_umum = $this->master->searchArray($row_data->kode_brg, 'kode_brg', $dt_pjl_umum);
            if($row_data->kode_brg == $dt_pjl_umum[$key_umum]['kode_brg']){
              $qty_u = isset($dt_pjl_umum[$key_umum])?$dt_pjl_umum[$key_umum]['jumlah_tebus']:0;
              $humum = isset($dt_pjl_umum[$key_umum])?$dt_pjl_umum[$key_umum]['harga_jual']:0;
            }else{
              $qty_u = 0;
              $humum = 0;
            }
            $j_umum=$qty_u * $humum;

            //internal
            $key_internal = $this->master->searchArray($row_data->kode_brg, 'kode_brg', $dt_pjl_internal);
            if($row_data->kode_brg == $dt_pjl_internal[$key_internal]['kode_brg']){
              $qty_i = isset($dt_pjl_internal[$key_internal])?$dt_pjl_internal[$key_internal]['jumlah_tebus']:0;
              $hinternal = isset($dt_pjl_internal[$key_internal])?$dt_pjl_internal[$key_internal]['harga_jual']:0;
            }else{
              $qty_i = 0;
              $hinternal = 0;
            }
            $j_internal=$qty_i * $hinternal;

             //distribusi
            $key_distribusiU = $this->master->searchArray($row_data->kode_brg, 'kode_brg', $dt_distribusiU);
            if($row_data->kode_brg == $dt_distribusiU[$key_distribusiU]['kode_brg']){
              $qty_d = isset($dt_distribusiU[$key_distribusiU])?$dt_distribusiU[$key_distribusiU]['jumlah_permintaan']:0;
              $hdistribusiU = isset($dt_distribusiU[$key_distribusiU])?$dt_distribusiU[$key_distribusiU]['harga_beli']:0;
            }else{
              $qty_d = 0;
              $hdistribusiU = 0;
            }
            $j_distribusiU=$qty_d * $hdistribusiU;

            $saldoawal=$qtys * $row_data->hargajual;
            $saldo_akhir= $qtys + $qty_p - $qty - $qty_u - $qty_i - $qty_d;
            $saldoakhir=$saldo_akhir * $row_data->hargajual;

            $jmlpenerimaan=$jmlpenerimaan+$saldopenerimaan;
            $jmlpenjualanbpjs=$jmlpenjualanbpjs+$j_bpjs;
            $jmlpenjualanumum=$jmlpenjualanumum+$j_umum;
            $penjualanintrnal=$penjualanintrnal+$j_internal;
            $jmldistribusi=$jmldistribusi+$j_distribusiU;

            $jmlakhir=$jmlakhir+$saldo_akhir;
            $jmlsaldoakhir=$jmlsaldoakhir+$saldoakhir;

            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                echo '<td>'.$row_data->kode_brg.'</td>';
                echo '<td>'.$row_data->nama_brg.'</td>';
                echo '<td>'.$dt_distribusiU->nama_bagian.'</td>';
                echo '<td>'.number_format($row_data->harga_beli).'</td>';
                echo '<td>'.number_format($row_data->hargajual).'</td>';
                echo '<td>'.$qtys.'</td>';
                echo '<td>'.number_format($saldoawal).'</td>';
                echo '<td>'.$qty_p.'</td>';
                echo '<td>'.number_format($saldopenerimaan).'</td>';
                echo '<td>'.$qty.'</td>';
                echo '<td>'.number_format($j_bpjs).'</td>';
                echo '<td>'.$qty_u.'</td>';
                echo '<td>'.number_format($j_umum).'</td>';
                echo '<td>'.$qty_i.'</td>';
                echo '<td>'.number_format($j_internal).'</td>';
                echo '<td>'.$qty_d.'</td>';
                echo '<td>'.number_format($j_distribusiU).'</td>';
                echo '<td>'.$saldo_akhir.'</td>';
                echo '<td>'.number_format($saldoakhir).'</td>';
              ?>
            </tr>
          <?php } ?>
        </tbody>
        <tr><td colspan="8"><b>TOTAL </b></td>
          <td><?php echo number_format($jmlpenerimaan);?></td> 
          <td></td>
          <td><?php echo number_format($jmlpenjualanbpjs);?></td> 
          <td></td>
          <td><?php echo number_format($jmlpenjualanumum);?></td> 
          <td></td>
          <td><?php echo number_format($penjualanintrnal);?></td> 
          <td></td>
          <td></td> 
          <td></td> 
          <td><?php echo number_format($jmlsaldoakhir);?></td> 
            
      </table>
      <table border="0" width="100%">
  <tr>
  <td colspan="2" valign="bottom" style="padding-top:25px" align="right"> Jakarta, ..........................</td>
    <tr><td valign="bottom" style="padding-top:25px" align="right">
    <b>Mengetahui<br><br><br><br><br><br>_________________________
  </td>
  <td valign="bottom" style="padding-top:25px" align="right">
    <b>Petugas<br><br><br><br><br><br>_________________________
  </td>
</tr>
</table>
    </div>
  </div>
</body>
</html>






