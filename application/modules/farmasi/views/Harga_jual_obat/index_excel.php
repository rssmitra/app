
<?php 
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=".'HARGA_JUAL_OBAT_'.date('Ymd').".xls");  //File name extension was wrong
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false);
?>

<table id="dynamic-table" base-url="farmasi/Harga_jual_obat" url-detail="farmasi/Harga_jual_obat/show_detail" class="table table-bordered table-hover">
  <thead>
    <tr>  
      <th width="50px">No</th>
      <th width="150px">Kode Barang</th>
      <th width="150px">Nama Barang</th>
      <th>Gol & Sub Golongan</th>
      <th>Satuan<br>Besar/Kecil</th>
      <th>Rasio</th>
      <th>Harga Beli</th>
      <th>Harga Jual</th>
      <!-- <th width="180px">Spesifikasi</th> -->
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $no=0; foreach($data as $row): 
      $no++;
      $status_aktif = ($row->is_active == 1) ? 'Active' : 'Not active';
    ?>
    <tr>  
      <td><?php echo $no; ?></td>
      <td><?php echo $row->kode_brg; ?></td>
      <td><?php echo $row->nama_brg; ?></td>
      <td><?php echo $row->nama_golongan.' / '.$row->nama_sub_golongan; ?></td>
      <td><?php echo $row->satuan_besar.' / '.$row->satuan_kecil; ?></td>
      <td><?php echo $row->content; ?></td>
      <td><?php echo (int)$row->harga_beli; ?></td>
      <td><?php $harga_jual = $row->harga_beli + ($row->harga_beli * (33.3/100)); echo (int)$harga_jual; ?></td>
      <td><?php echo $status_aktif; ?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>



