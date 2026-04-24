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

  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; background: #fff; margin: 10px; }
    h4 { font-size: 14px; font-weight: 700; color: #1e3a5f; text-align: center; margin-bottom: 8px; }
    .table { font-size: 11.5px; border-collapse: collapse; width: 100%; margin-bottom: 12px; }
    .table th { background: linear-gradient(135deg, #0369a1, #0ea5e9); color: #fff; padding: 5px 7px; border: 1px solid #1d4ed8 !important; font-size: 11px; text-align: center; }
    .table td { padding: 4px 7px; border: 1px solid #e2e8f0 !important; vertical-align: middle; }
    .table tbody tr:nth-child(even) { background: #f8fafc; }
    .table tfoot td { background: #f1f5f9; font-weight: 700; border-top: 2px solid #0ea5e9 !important; }
    table.greyGridTable { border: 1px solid #ccc; border-collapse: collapse; width: 100%; font-size: 11.5px; }
    table.greyGridTable thead th { background: linear-gradient(135deg, #0369a1, #0ea5e9); color: #fff; padding: 5px 7px; border: 1px solid #1d4ed8; text-align: center; }
    table.greyGridTable td { border: 1px solid #e2e8f0; padding: 4px 7px; vertical-align: middle; }
    table.greyGridTable tbody tr:nth-child(even) { background: #f8fafc; }
    tr[style*="font-weight: bold"] td, tr[style*="font-weight:bold"] td { background: #f1f5f9 !important; font-weight: 700; border-top: 2px solid #0ea5e9 !important; }
    @media print { body { margin: 0; } }
  </style>
</head>
<body>
  <br>
  
  <div class="row" style="padding-left: 100px; padding-right: 100px; padding-top: 20px">
    <center><span style="font-size: 14px; font-weight: bold">REKAPITULASI SALDO AKHIR BARANG MEDIS<br>BULAN <?php echo strtoupper($this->tanggal->getBulan($month))?> TAHUN <?php echo $year; ?> </span> </center>
    <br>
    <div class="col-xs-12">
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
            <th width="50px">NO</th>
            <th>NAMA UNIT/BAGIAN</th>
            <th width="200px" style="text-align: right">SALDO AKHIR</th>
          </tr>
         
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            
          foreach($v_saldo as $k_bag => $v_bag){
            $no++; 
            $saldo_bagian = array_sum($saldo_akhir[$k_bag]);
            $arr_saldo_total[] = $saldo_bagian;
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                echo '<td><a href="'.base_url().'laporan/Global_report/show_data_bmhp?kode_bagian='.$v_bag[0]['kode_bagian'].'&month='.$month.'&year='.$year.'&flag=akunting_mod_3&submit=form" target="_blank">'.strtoupper($k_bag).'</td>';
                $saldo_txt = ($_POST['submit']=='excel')?$saldo_bagian: number_format($saldo_bagian);
                echo '<td align="right">'.$saldo_txt.'</td>';
              ?>
            </tr>
          <?php 
          }   
          ?>
          <tr style="font-weight: bold">
              <td align="right" colspan="2">TOTAL SALDO</td>
              <?php 
                $total_saldo = array_sum($arr_saldo_total);
                $total_saldo_txt = ($_POST['submit']=='excel')?$total_saldo: number_format($total_saldo);
              ?>
              <td align="right"><?php echo $total_saldo_txt?></td>
            </tr>

        </tbody>
      </table>
      <br>
    </div>
  </div>
</body>
</html>






