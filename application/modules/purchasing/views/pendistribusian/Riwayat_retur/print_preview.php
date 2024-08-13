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
<center><p><b>FORM DAFTAR RETUR BARANG <?php echo strtoupper($subtitle)?> <br> DARI UNIT KE <?php echo strtoupper($title)?></b></p></center>

<table id="no-border" style="width: 100% !important;">
  <tr>
    <td width="150px"><b>Nomor Retur</b></td>
    <td>: <?php echo $dt_detail_brg[0]->kode_retur?></td>
  </tr>
  <tr>
    <td><b>Tanggal Retur</b></td>
    <td>: <?php echo $this->tanggal->formatDateForm($dt_detail_brg[0]->tgl_retur); ?></td>
  </tr>
  <tr>
    <td><b>Dari unit</b></td>
    <td>: <?php echo $dt_detail_brg[0]->nama_bagian?></td>
  </tr>
</table>
<br>
<table class="table-utama" style="width: 100% !important;">
  <thead>
      <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
        <td style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</td>
        <td style="border: 1px solid black; border-collapse: collapse">Kode & Nama Barang</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Stok Sebelum</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Jumlah diretur</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Satuan</td>
      </tr>
  </thead>
  <tbody>
      <?php $no=0; foreach($dt_detail_brg as $row_dt) : $no++?>
          <tr>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $no?></td>
          <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->kode_brg.' - '.$row_dt->nama_brg?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo ($row_dt->jml_sebelum)?$row_dt->jml_sebelum:0; ?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->jumlah?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->satuan_kecil?></td>
          </tr>
      <?php endforeach;?>
  </tbody>
</table>
<br>
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
