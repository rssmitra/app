<?php 
  $submit = isset($_POST['submit'])?$_POST['submit']:$_GET['submit'];
  if($submit=='excel') {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$flag.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }

  // array_multisort(array_map(function($element) {
  //   return $element->harga_beli;
  // }, $result['data']), SORT_DESC, $result['data']);

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

      <center><span style="font-size: 14px; font-weight: bold">Rekapitulasi Stok Awal Bulan, Penerimaan/Pembelian, Penjualan, BMHP dan Saldo Akhir<br>Berdasarkan Waktu Pemesanan Resep<br>Bulan <?php echo $this->tanggal->getBulan($month)?> Tahun <?php echo $year?> <br>Unit/Bagian <?php echo ucwords($this->master->get_string_data('nama_bagian','mt_bagian',array('kode_bagian' => $bagian)));?> </span> </center>

      <br>

      <table class="table table-bordered" >
        <thead>
          <tr style="text-align: center">
            <th rowspan="2">No</th>
            <th rowspan="2" width="105">Kode Barang<br/></th>
            <th rowspan="2" width="95">Nama Barang</th>
            <th rowspan="2" width="304">HPP Satuan</th>
            <th width="304" style="text-align: center" colspan="2">Saldo Awal <?php echo $this->tanggal->getBulan($month)?></th>
            <th width="304" style="text-align: center" colspan="2">Penerimaan/Pembelian</th>
            <th width="304" style="text-align: center" colspan="2">Penjualan BPJS</th>
            <th width="304" style="text-align: center" colspan="2">Penjualan Umum</th>
            <th width="304" style="text-align: center" colspan="2">Penggunaan Internal</th>
            <th width="304" style="text-align: center" colspan="2">Saldo Akhir</th>
          </tr>
          <tr>
            <th width="304" style="text-align: center">Quantity</th>
            <th width="304" style="text-align: center">Jumlah</th>
            <th width="304" style="text-align: center">Quantity</th>
            <th width="304" style="text-align: center">Jumlah</th>
            <th width="304" style="text-align: center">Quantity</th>
            <th width="304" style="text-align: center">Jumlah</th>
            <th width="304" style="text-align: center">Quantity</th>
            <th width="304" style="text-align: center">Jumlah</th>
            <th width="304" style="text-align: center">Quantity</th>
            <th width="304" style="text-align: center">Jumlah</th>
            <th width="304" style="text-align: center">Quantity</th>
            <th width="304" style="text-align: center">Jumlah</th>
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
            // $rp_penjualan_bpjs = isset($v_penjualan_bpjs[$kode_brg]['total'])?$v_penjualan_bpjs[$kode_brg]['total']:0;
            $rp_penjualan_bpjs = isset($v_penjualan_bpjs[$kode_brg]['jumlah'])?$v_penjualan_bpjs[$kode_brg]['jumlah']*$row_data->harga_beli:0;
            $arr_qty_penjualan_bpjs[] = $qty_penjualan_bpjs;
            $arr_rp_penjualan_bpjs[] = $rp_penjualan_bpjs;

            // penjualan umum
            $qty_penjualan = isset($v_penjualan_umum[$kode_brg]['jumlah'])?$v_penjualan_umum[$kode_brg]['jumlah']:0;
            // $rp_penjualan = isset($v_penjualan_umum[$kode_brg]['total'])?$v_penjualan_umum[$kode_brg]['total']:0;
            $rp_penjualan = isset($v_penjualan_umum[$kode_brg]['jumlah'])?$v_penjualan_umum[$kode_brg]['jumlah']*$row_data->harga_beli:0;
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

            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                echo '<td>'.$kode_brg.'</td>';
                echo '<td>'.$row_data->nama_brg.'</td>';
                $txt_harga_beli = ($submit == 'excel')?$row_data->harga_beli:number_format($row_data->harga_beli);
                echo '<td style="text-align: right">'.$txt_harga_beli.'</td>';
                // saldo awal
                echo '<td align="center">'.$qty_saldo_awal.'</td>';
                $txt_rp_saldo_awal = ($submit == 'excel')?$rp_saldo_awal:number_format($rp_saldo_awal);
                echo '<td align="right">'.$txt_rp_saldo_awal.'</td>';
                // penerimaan
                echo '<td align="center">'.$qty_penerimaan.'</td>';
                $txt_rp_penerimaan = ($submit == 'excel')?$rp_penerimaan:number_format($rp_penerimaan);
                echo '<td align="right">'.$txt_rp_penerimaan.'</td>';
                // penjualan bpjs
                echo '<td align="center">'.$qty_penjualan_bpjs.'</td>';
                $txt_rp_penjualan_bpjs = ($submit == 'excel')?$rp_penjualan_bpjs:number_format($rp_penjualan_bpjs);
                echo '<td align="right">'.$txt_rp_penjualan_bpjs.'</td>';
                // penjualan umum
                echo '<td align="center">'.$qty_penjualan.'</td>';
                $txt_rp_penjualan = ($submit == 'excel')?$rp_penjualan:number_format($rp_penjualan);
                echo '<td align="right">'.$txt_rp_penjualan.'</td>';
                // bmhp
                echo '<td align="center">'.$qty_bmhp.'</td>';
                $txt_rp_bmhp = ($submit == 'excel')?$rp_bmhp:number_format($rp_bmhp);
                echo '<td align="right">'.$txt_rp_bmhp.'</td>';

                echo '<td align="center">'.$qty_saldo_akhir.'</td>';
                $txt_rp_saldo_akhir = ($submit == 'excel')?$rp_saldo_akhir:number_format($rp_saldo_akhir);
                echo '<td align="right"><b>'.$txt_rp_saldo_akhir.'</b></td>';

              ?>
            </tr>
          <?php } 
          // echo '<pre>'; print_r($arr_rp_saldo_awal);die;
          ?>
            <tr style="font-weight: bold">
              <td colspan="4" style="text-align: right"><b>TOTAL </b></td>
              <td></td>
              <td style="text-align: right">
                <?php 
                    $txt_arr_rp_saldo_awal = array_sum($arr_rp_saldo_awal); 
                    echo ($submit == 'excel') ? $txt_arr_rp_saldo_awal : number_format($txt_arr_rp_saldo_awal);?></td> 
              <td></td>
              <td style="text-align: right">
                <?php 
                    $txt_arr_rp_penerimaan = array_sum($arr_rp_penerimaan); 
                    echo ($submit == 'excel') ? $txt_arr_rp_penerimaan : number_format($txt_arr_rp_penerimaan);?></td> 
              <td></td>
              <td style="text-align: right">
                <?php 
                    $txt_arr_rp_penjualan_bpjs = array_sum($arr_rp_penjualan_bpjs); 
                    echo ($submit == 'excel') ? $txt_arr_rp_penjualan_bpjs : number_format($txt_arr_rp_penjualan_bpjs);?></td> 
              <td></td>
              <td style="text-align: right">
                <?php 
                    $txt_arr_rp_penjualan = array_sum($arr_rp_penjualan); 
                    echo ($submit == 'excel') ? $txt_arr_rp_penjualan : number_format($txt_arr_rp_penjualan);?></td> 
              <td></td>
              <td style="text-align: right">
                <?php 
                    $txt_arr_rp_bmhp = array_sum($arr_rp_bmhp); 
                    echo ($submit == 'excel') ? $txt_arr_rp_bmhp : number_format($txt_arr_rp_bmhp);?></td> 
              <td></td>
              <td style="text-align: right">
                <?php 
                    $txt_arr_rp_saldo_akhir = array_sum($arr_rp_saldo_akhir); 
                    echo ($submit == 'excel') ? $txt_arr_rp_saldo_akhir : number_format($txt_arr_rp_saldo_akhir);?></td> 
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






