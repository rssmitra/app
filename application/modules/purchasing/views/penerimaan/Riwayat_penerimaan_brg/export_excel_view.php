<?php 
    $filename = "LAPORAN_PENERIMAAN_BARANG_EXPORT_AT";
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$filename.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  
?>
<h2>LAPORAN PENERIMAAN BARANG</h2>
Parameter : <br>
<?php print_r($parameter);?>
<table style="font-size:11px;" width="100%">
<thead>
  <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid #cecaca; border-collapse: collapse">
    <td>No</td>
    <td>No PO</td>
    <td>Kode Penerimaan</td>
    <td>Tanggal Penerimaan</td>
    <td>Nama Supplier</td>
    <td>No Faktur</td>
    <td>Kode & Nama Barang</td>
    <td>Rasio</td>
    <td>Satuan</td>
    <td>Jumlah Kirim</td>
    <td>Keterangan</td>
  </tr>
</thead>
  <tbody>
  <?php 
    $no=0; 
    foreach($result as $key_dt=>$row_dt) : $no++; 
  ?>
      <tr>
        <td><?php echo $no?></td>
        <td><?php echo $row_dt->no_po?></td>
        <td><?php echo $row_dt->kode_penerimaan?></td>
        <td> <?php echo $this->tanggal->formatDateTime($row_dt->tgl_penerimaan); ?></td>
        <td><?php echo $row_dt->namasupplier?></td>
        <td><?php echo $row_dt->no_faktur?></td>
        <td><?php echo $row_dt->kode_brg.' - '.$row_dt->nama_brg?></td>
        <td><?php echo $row_dt->content?></td>
        <td><?php echo $row_dt->satuan_besar?></td>
        <td><?php echo ($row_dt->jumlah_kirim)?$row_dt->jumlah_kirim:0; ?></td>
        <td> <?php echo ($row_dt->keterangan)?$row_dt->keterangan:'-'; ?></td>
      </tr>
      <?php endforeach;?>

</tbody>
</table>


