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
<center><p><b>FORM DAFTAR PERMINTAAN UNIT BARANG <?php echo strtoupper($subtitle)?> <br> <?php echo strtoupper($title)?></b></p></center>

<table id="no-border" style="width: 100% !important;">
  <tr>
    <td width="150px"><b>Nomor Permintaan</b></td>
    <td>: <?php echo $permintaan->nomor_permintaan?></td>
  </tr>
  <tr>
    <td><b>Tanggal</b></td>
    <td>: <?php echo $this->tanggal->formatDateForm($permintaan->tgl_permintaan); ?></td>
    <td width="150px" style="text-align: right"><b>Jenis Permintaan</b></td>
    <td style="text-align: left; width: 200px">: <?php echo ($permintaan->jenis_permintaan==1)?'Cito':'Rutin'?></td>
  </tr>
</table>
<br>
<table class="table-utama" style="width: 100% !important;">
  <thead>
      <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
        <td style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</td>
        <td style="border: 1px solid black; border-collapse: collapse">Kode & Nama Barang</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Stok Akhir</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Jumlah<br>Permintaan</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Rasio</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Konversi<br>Satuan Besar</td>
      </tr>
  </thead>
  <tbody>
      <?php $no=0; foreach($dt_detail_brg as $row_dt) : $no++?>
          <tr>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $no?></td>
          <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->kode_brg.' - '.$row_dt->nama_brg?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo ($row_dt->jumlah_stok_sebelumnya)?$row_dt->jumlah_stok_sebelumnya:0; echo ' '.$row_dt->satuan_kecil; ?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->jumlah_permintaan.' '.$row_dt->satuan_kecil?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->rasio?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php $konversi = $row_dt->jumlah_permintaan / $row_dt->rasio; 
          $modulus = $row_dt->jumlah_permintaan % $row_dt->rasio;
          $txt_modulus = ( ($row_dt->jumlah_permintaan % $row_dt->rasio) <= 0 ) ? '' : ' + '.$modulus.' '.$row_dt->satuan_kecil ;

          echo ( $konversi == 0 ) ? '' : number_format($konversi).' '.$row_dt->satuan_besar ;
          echo $txt_modulus; 
          ?>
          </td>
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
