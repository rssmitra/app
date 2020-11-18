<?php 
  $submit = isset($_POST['submit'])?$_POST['submit']:$_GET['submit'];
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
  <div class="row">
    <div class="col-xs-12">

      <center><span style="font-size: 14px; font-weight: bold">Rekapitulasi Stok Awal Bulan, Penerimaan/Pembelian, Penjualan, BMHP dan Saldo Akhir <br>Bulan <?php echo $this->tanggal->getBulan($month)?> Bagian <?php echo ucwords($this->master->get_string_data('nama_bagian','mt_bagian',array('kode_bagian' => $bagian)));?> </span> </center>

      <br>

      <table class="table table-bordered">
        <thead>
          <tr style="text-align: center">
            <th rowspan="2">No</th>
            <th rowspan="2" width="105">Kode Barang<br/></th>
            <th rowspan="2" width="95">Nama Barang</th>
            <th rowspan="2" width="304">HPP Satuan</th>
            <th width="304" colspan="2">Saldo Awal <?php echo $this->tanggal->getBulan($month)?></th>
            <th width="304" colspan="2">Penerimaan/Pembelian</th>
            <th width="304" colspan="2">Penjualan BPJS</th>
            <th width="304" colspan="2">Penjualan Umum</th>
            <th width="304" colspan="2">Penggunaan Internal</th>
            <th width="304" colspan="2">Saldo Akhir</th>
            <th width="304" rowspan="2">Quantity</th>
            <th width="304" rowspan="2">Keterangan</th>
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
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            $jmlpenerimaan=0;
            $jmlpenjualanbpjs=0;
            $jmlpenjualanumum=0;
            $penjualanintrnal=0;
            $jmldistribusi=0;
            $jmlakhir=0;
            $jmlsaldoakhir=0;
          foreach($result['data'] as $row_data){
            $no++; 
            $kode_brg = trim($row_data->kode_brg);
            // Saldo Awal
            $qty_saldo_awal = isset($v_saldo[$kode_brg]) ? ($v_saldo[$kode_brg] > 0) ? $v_saldo[$kode_brg] : 0 : 0 ;
            $rp_saldo_awal = $qty_saldo_awal * $row_data->harga_beli;
            $arr_qty_saldo_awal[] = $qty_saldo_awal;
            $arr_rp_saldo_awal[] = $rp_saldo_awal;

            // penerimaan
            $qty_penerimaan = isset($v_penerimaan[$kode_brg])?$v_penerimaan[$kode_brg]:0;
            $rp_penerimaan = $qty_penerimaan * $row_data->harga_beli;
            $arr_qty_penerimaan[] = $qty_penerimaan;
            $arr_rp_penerimaan[] = $rp_penerimaan;

            // penjualan bpjs
            $qty_penjualan_bpjs = isset($v_penjualan_bpjs[$kode_brg]['jumlah'])?$v_penjualan_bpjs[$kode_brg]['jumlah']:0;
            $rp_penjualan_bpjs = isset($v_penjualan_bpjs[$kode_brg]['total'])?$v_penjualan_bpjs[$kode_brg]['total']:0;
            $arr_qty_penjualan_bpjs[] = $qty_penjualan_bpjs;
            $arr_rp_penjualan_bpjs[] = $rp_penjualan_bpjs;

            // penjualan umum
            $qty_penjualan = isset($v_penjualan_umum[$kode_brg]['jumlah'])?$v_penjualan_umum[$kode_brg]['jumlah']:0;
            $rp_penjualan = isset($v_penjualan_umum[$kode_brg]['total'])?$v_penjualan_umum[$kode_brg]['total']:0;
            $arr_qty_penjualan[] = $qty_penjualan;
            $arr_rp_penjualan[] = $rp_penjualan;

            // bmhp
            $qty_bmhp = isset($v_bmhp[$kode_brg])?$v_bmhp[$kode_brg]:0;
            $rp_bmhp = $qty_bmhp * $row_data->harga_beli;
            $arr_qty_bmhp[] = $qty_bmhp;
            $arr_rp_bmhp[] = $rp_bmhp;
            
            // summary
            $qty_saldo_akhir = ($qty_saldo_awal + $qty_penerimaan) - ($qty_penjualan_bpjs + $qty_penjualan + $qty_bmhp);
            $rp_saldo_akhir = ($rp_saldo_awal + $rp_penerimaan ) - ($rp_penjualan_bpjs + $rp_penjualan + $rp_bmhp);
            $arr_qty_saldo_akhir[] = $qty_saldo_akhir;
            $arr_rp_saldo_akhir[] = $rp_saldo_akhir;

            // $jmlpenerimaan = $jmlpenerimaan + $saldopenerimaan;
            // $jmlpenjualanbpjs=$jmlpenjualanbpjs+$j_bpjs;
            // $jmlpenjualanumum=$jmlpenjualanumum+$j_umum;
            // $penjualanintrnal=$penjualanintrnal+$j_internal;
            // $jmldistribusi=$jmldistribusi+$j_distribusiU;

            // $jmlakhir=$jmlakhir+$saldo_akhir;
            // $jmlsaldoakhir=$jmlsaldoakhir+$saldoakhir;
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                echo '<td>'.$kode_brg.'</td>';
                echo '<td>'.$row_data->nama_brg.'</td>';
                echo '<td>'.$row_data->harga_beli.'</td>';
                // saldo awal
                echo '<td align="center">'.$qty_saldo_awal.'</td>';
                echo '<td align="right">'.$rp_saldo_awal.'</td>';
                // penerimaan
                echo '<td align="center">'.$qty_penerimaan.'</td>';
                echo '<td align="right">'.$rp_penerimaan.'</td>';
                // penjualan bpjs
                echo '<td align="center">'.$qty_penjualan_bpjs.'</td>';
                echo '<td align="right">'.$rp_penjualan_bpjs.'</td>';
                // penjualan umum
                echo '<td align="center">'.$qty_penjualan.'</td>';
                echo '<td align="right">'.$rp_penjualan.'</td>';
                // bmhp
                echo '<td align="center">'.$qty_bmhp.'</td>';
                echo '<td align="right">'.$rp_bmhp.'</td>';

                echo '<td align="center">'.$qty_saldo_akhir.'</td>';
                echo '<td align="right">'.$rp_saldo_akhir.'</td>';

                echo '<td></td>';
                echo '<td></td>';
              ?>
            </tr>
          <?php } 
          // echo '<pre>'; print_r($arr_rp_saldo_awal);die;
          ?>
            <tr>
              <td colspan="4"><b>TOTAL </b></td>
              <td></td>
              <td><?php echo array_sum($arr_rp_saldo_awal);?></td> 
              <td></td>
              <td><?php echo array_sum($arr_rp_penerimaan);?></td> 
              <td></td>
              <td><?php echo array_sum($arr_rp_penjualan_bpjs);?></td> 
              <td></td>
              <td><?php echo array_sum($arr_rp_penjualan);?></td> 
              <td></td>
              <td><?php echo array_sum($arr_rp_bmhp);?></td> 
              <td></td>
              <td><?php echo array_sum($arr_rp_saldo_akhir);?></td> 
            </tr>
        </tbody>
      </table>
      <br>
      <!-- <table border="0" width="100%">
        <tr>
        <td colspan="2" valign="bottom" style="padding-top:25px" align="right"> Jakarta, ..........................</td>
          <tr><td valign="bottom" style="padding-top:25px" align="right">
          <b>Mengetahui<br><br><br><br><br><br>_________________________
        </td>
        <td valign="bottom" style="padding-top:25px" align="right">
          <b>Petugas<br><br><br><br><br><br>_________________________
        </td>
      </tr>
      </table> -->
    </div>
  </div>
</body>
</html>






