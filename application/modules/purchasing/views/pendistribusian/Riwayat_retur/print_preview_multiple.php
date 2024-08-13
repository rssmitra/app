<style>
table, p{
  font-family: calibri;
  font-size: 12px;
}
.table-utama{
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 2px;
  text-align: left;
}
@media print{ #barPrint{
		display:none;
	}
}
</style>
<div id="barPrint" style="float: right">
  <button class="tular" onClick="window.close()">Tutup</button>
  <button class="tular" onClick="print()">Cetak</button>
</div>
<center><p><b>FORM DAFTAR RETUR BARANG <?php echo strtoupper($subtitle)?> <br> <?php echo strtoupper($title)?></b></p></center>

<?php 
  foreach( $permintaan as $rows ) :
    //echo '<pre>';print_r($rows);die;
?>

<table id="no-border" style="width: 100% !important;">
  <tr>
    <td width="120px"><b>Nomor Retur</b></td>
    <td>: <?php echo isset( $rows[0]['kode_retur'] ) ? $rows[0]['kode_retur'] : '' ; ?></td>
  </tr>
  <tr>
    <td><b>Tanggal</b></td>
    <td>: 
      <?php echo isset( $rows[0]['tgl_retur'] ) ? $this->tanggal->formatDateForm( $rows[0]['tgl_retur'] ) : '' ; ?></td>
  </tr>
  <tr>
    <td width="120px"><b>Unit/Bagian</b></td>
    <td>: <?php echo isset( $rows[0]['nama_bagian'] ) ? $rows[0]['nama_bagian'] : '' ; ?></td>
  </tr>
</table>
<table class="table-utama" style="width: 100% !important;">
  <thead>
      <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
        <td style="text-align:center; width: 4%; border: 1px solid black; border-collapse: collapse">No</td>
        <td style="border: 1px solid black; border-collapse: collapse; width: 50%">Kode & Nama Barang</td>
        <td style="text-align:center; width: 8%; border: 1px solid black; border-collapse: collapse">Stok Sebelum</td>
        <td style="text-align:center; width: 8%; border: 1px solid black; border-collapse: collapse">Jumlah<br>Retur</td>
        <td style="text-align:center; width: 8%; border: 1px solid black; border-collapse: collapse">Satuan Kecil</td>
      </tr>
  </thead>
  <tbody>
      <?php 
        $no=0; 
        foreach($rows as $row_dt) : 
        $no++;
        // echo '<pre>';print_r($row_dt);die;
      ?>
          <tr>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $no?></td>
          <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt['barang']->kode_brg.' - '.$row_dt['barang']->nama_brg?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo ($row_dt['barang']->jml_sebelum)?$row_dt['barang']->jml_sebelum:0; echo ' '.$row_dt['barang']->satuan_kecil; ?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt['barang']->jumlah?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt['barang']->satuan_kecil?></td>
          </tr>
      <?php endforeach;?>
  </tbody>
</table>
<br>
<?php endforeach;?>

<table border="0" style="width: 100% !important; text-align: center">
  <tr>
    <td style="text-align: center; width: 30%">
      <br>
      Disetjui Oleh :
      <br>
      Ka. RS / Kabid. 
      <br>
      <br>
      <br>
      <br>
      ....................................
    </td>
    <td style="text-align: center; width: 40%">
      <br>
      Diketahui Oleh :
      <br>
      Kabid / Kabag.
      <br>
      <br>
      <br>
      <br>
      ....................................
    </td>
    <td style="text-align: center; width: 30%">
      <br>
      Diminta Oleh :
      <br>
      Ka.
      <br>
      <br>
      <br>
      <br>
      ....................................
    </td>
  </tr>
  <tr>
    <td style="text-align: center; width: 30%">
      <br>
      Penyerahan Barang, <br>diketahui oleh :
      <br>
      <br>
      <br>
      <br>
      <br>
      ....................................
    </td>
    <td style="text-align: center; width: 40%">
      <br>
      Barang-barang tersebut, <br>diserahkan oleh :
      <br>
      <br>
      <br>
      <br>
      <br>
      ....................................
    </td>
    <td style="text-align: center; width: 30%">
      <br>
      Barang-barang tersebut, <br>diterima oleh :
      <br>
      <br>
      <br>
      <br>
      <br>
      ....................................
    </td>
  </tr>
</table>
