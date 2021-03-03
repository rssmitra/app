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

      <center><span style="font-size: 14px; font-weight: bold">Rekap Pembelian Barang Berdasarkan Supplier<br>Bulan <?php echo $this->tanggal->getBulan($month)?> Tahun <?php echo $year; ?></center>
      <a href="#" class="btn btn-xs btn-primary">Export Excel</a>  <a href="<?php echo base_url().'laporan/Global_report/pengadaan_mod_6_shw_w_det_trx?month='.$month.'&year='.$year.'&flag=pengadaan_mod_6_shw_w_d_trx&submit=form'?>" class="btn btn-xs btn-primary">Tampilkan Detail Transaksi</a>
      <br>
      <br>
      <table class="table table-bordered">
        <thead>
          <tr style="text-align: center">
            <th width="50">No</th>
            <th width="105">Nama Supplier</th>
            <th width="100">Total Pembelian</th>
          </tr>
         
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            
          foreach($result['data'] as $k => $v){
            $no++; 
            $total[] = $v->total_format_money;
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                echo '<td><a target="_blank" href="'.base_url().'laporan/Global_report/show_rekap_supplier_detail_transaksi?kode_supplier='.$v->kodesupplier.'&month='.$month.'&year='.$year.'&flag=pengadaan_mod_6_detail_transaksi&submit=form">'.ucwords(strtolower($v->supplier)).'</td>';
                echo '<td align="right">'.number_format($v->total_format_money).'</td>';
              ?>
            </tr>
          <?php 
          } 
          ?>
          <tr>
            <td colspan="2" align="right"><b>TOTAL PEMBELIAN BARANG</b></td>
            <td align="right"><b><?php echo number_format(array_sum($total)) ?></b></td>
          </tr>
        </tbody>
      </table>
      <br>
    </div>
  </div>
</body>
</html>






