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
<center><p><b>FORM RETUR BARANG</b></p></center>

<table id="no-border" style="width: 100% !important;">
  <tr>
    <td width="150px"><b>Nomor Retur</b></td>
    <td>: <?php echo $retur->kode_retur?></td>
  </tr>
  <tr>
    <td><b>Tanggal Retur</b></td>
    <td>: <?php echo $this->tanggal->formatDateForm($retur->tgl_retur); ?></td>
  </tr>
  <tr>
    <td><b>Dari Unit</b></td>
    <td>: <?php echo $retur->nama_bagian?></td>
  </tr>
</table>
<br>
<table class="table-utama" style="width: 100% !important;">
  <thead>
      <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
        <td style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</td>
        <td style="border: 1px solid black; border-collapse: collapse">Kode & Nama Barang</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Jumlah Sebelum</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Jumlah di Retur</td>
        <td style="text-align:center; width: 120px; border: 1px solid black; border-collapse: collapse">Jumlah Setelah Retur</td>
      </tr>
  </thead>
  <tbody>
      <?php $no=0; foreach($dt_detail_brg as $row_dt) : $no++?>
          <tr>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $no?></td>
          <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->kode_brg.' - '.$row_dt->nama_brg?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo ($row_dt->jml_sebelum)?$row_dt->jml_sebelum:0; echo ' '.$row_dt->satuan_kecil; ?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->jumlah.' '.$row_dt->satuan_kecil?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse">
            <?php 
              $jml_stl_retur = $row_dt->jml_sebelum - $row_dt->jumlah;
              $jml_stl_retur = ($jml_stl_retur < 0) ? 0 : $jml_stl_retur;
              echo ($jml_stl_retur)?$jml_stl_retur.' '.$row_dt->satuan_kecil:0; 
            ?></td>
          </tr>
      <?php endforeach;?>
  </tbody>
</table>
<br>
<table border="0" style="width: 100% !important; text-align: center">
  <tr>
    <td style="text-align: right; width: 50%">
      <br>
      ............................, <?php echo $this->tanggal->formatDateForm(date('Y-m-d')); ?>
      <br>
      <br>
      <br>
      <br>
      <br>
      (<?php echo $this->session->userdata('user')->fullname?>)
    </td>
  </tr>
</table>
