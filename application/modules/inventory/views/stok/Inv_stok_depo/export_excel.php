<?php 
  $filename = 'Export_Stok_Unit_'.$_GET['kode_bagian'];
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$filename.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);

?>

<table id="dynamic-table" base-url="inventory/stok/Inv_stok_depo" class="table table-striped table-bordered table-hover">
    <thead>
      <tr>  
        <th width="30px">No</th>
        <th width="100px">Kode</th>
        <th>Nama Barang</th>
        <th>Rasio</th>
        <th>Stok Minimum</th>
        <th>Stok Akhir</th>
        <th>Satuan</th>
        <!-- <th>Harga Beli</th> -->
        <th>Mutasi Terakhir</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
    <?php $no=0; foreach ($data as $key => $value) : $no++; $status_aktif = ($value->is_active == 1) ? '<span class="label label-sm label-success">Active</span>' : '<span class="label label-sm label-danger">Not active</span>'; ?>
      <tr>
        <td><?php echo $no?></td>
        <td><?php echo $value->kode_brg?></td>
        <td><?php echo $value->nama_brg?></td>
        <td><?php echo $value->content?></td>
        <td><?php echo $value->stok_minimum?></td>
        <td><?php echo $value->stok_akhir?></td>
        <td><?php echo $value->satuan_kecil?></td>
        <td><?php echo $this->tanggal->formatDateTime($value->tgl_input)?></td>
        <td><?php echo $status_aktif; ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
</table>