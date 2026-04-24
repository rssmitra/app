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
  <div class="row">
    <div class="col-xs-12">

      <center><h4>Laporan Stok Barang Medis Per-periode</h4></center>
      <!-- <b>Parameter :</b> <i><?php echo print_r($_POST);?></i> -->

      <table class="greyGridTable">
        <thead>
          <tr>
            <th>NO</th>
            <th width="105">Kode Barang<br/></th>
            <th width="95">Nama Barang</th>
            <th width="95">Unit</th>
            <th width="304">HPP Satuan</th>
            <th width="304">Harga Jual Satuan</th>
            <th width="304">Saldo Akhir</th>
            <th width="304">Jumlah</th>
          </tr>
         
        </thead>
        <tbody>
          <?php $no = 0; 
           $ttlakhir=0;
          foreach($result['data'] as $row_data){
            // $saldopenerimaan=$row_data->jumlah_kirim * $row_data->harga_beli;
            $no++; 
            // $saldo_akhir= $qtys + $qty_p - $qty - $qty_u - $qty_i - $qty_d;
            $saldoakhir=$row_data->stok_akhir * $row_data->hargajual;
            $ttlakhir=$ttlakhir+$saldoakhir;

            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
                echo '<td>'.$row_data->kode_brg.'</td>';
                echo '<td>'.$row_data->nama_brg.'</td>';
                echo '<td>'.$row_data->nama_bagian.'</td>';
                echo '<td>'.number_format($row_data->harga_beli).'</td>';
                echo '<td>'.number_format($row_data->hargajual).'</td>';
                echo '<td>'.$row_data->stok_akhir.'</td>';
                echo '<td>'.number_format($saldoakhir).'</td>';
              ?>
            </tr>
          <?php } ?>
          <tr><td colspan="7" align="right">Total </td>
            <td><?php echo number_format($ttlakhir);?></td>
          </tr>
        </tbody>
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






