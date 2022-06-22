<?php 
  $filename = 'Export_Data_Penjualan_Obat';
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$filename.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);

?>
<h3>LAPORAN PENJUALAN OBAT</h3>
<table id="dynamic-table" base-url="" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>  
      <th width="50px"></th>
      <th width="100px">Kode Barang</th>
      <th>Nama Barang</th>
      <th width="100px">Satuan Kecil</th>
      <th width="100px">Harga Satuan</th>
      <th class="center" width="100px">Stok Akhir Gudang</th>
      <th class="center" width="100px">Stok Akhir Farmasi</th>
      <th width="100px" class="center">Jumlah Terjual</th>
      <th width="100px" class="center">Total Penjualan</th>
    </tr>
    </thead>
    <tbody>
    <?php $no=0; foreach ($data as $key => $value) : $no++; ?>
      <tr>
        <td><?php echo $no?></td>
        <td><?php echo $value->kode_brg?></td>
        <td><?php echo $value->nama_brg?></td>
        <td><?php echo $value->satuan_kecil?></td>
        <td><?php echo $value->harga_rata_satuan?></td>
        <td><?php echo $value->stok_gdg?></td>
        <td><?php echo $value->stok_dp?></td>
        <td><?php echo $value->jml_terjual?></td>
        <td><?php $total_jual = $value->harga_rata_satuan * $value->jml_terjual; echo $total_jual; ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
</table>