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
<center><p><b>FORM DAFTAR PERMINTAAN PEMBELIAN BARANG <?php echo strtoupper($subtitle)?> <br> <?php echo strtoupper($title)?></b></p></center>

<table id="no-border" style="width: 100% !important;">
  <tr>
    <td width="150px"><b>Nomor Permintaan</b></td>
    <td>: <?php echo $permohonan->kode_permohonan?></td>
  </tr>
  <tr>
    <td><b>Tanggal</b></td>
    <td>: <?php echo $this->tanggal->formatDatedmY($permohonan->tgl_permohonan); ?></td>
    <td width="150px" style="text-align: right"><b>Jenis Permintaan</b></td>
    <td style="text-align: left; width: 200px">: <?php echo ($permohonan->flag_jenis==1)?'CITO':'RUTIN'?></td>
  </tr>
</table>
<br>
<table class="table-utama" style="width: 100% !important;">
  <thead>
      <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
        <td style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</td>
        <td style="border: 1px solid black; border-collapse: collapse">Kode & Nama Barang</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Sisa Stok</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Jumlah<br>Permintaan</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Satuan</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Rasio</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Konversi<br>Satuan Kecil</td>
      </tr>
  </thead>
  <tbody>
      <?php $no=0; foreach($dt_detail_brg as $row_dt) : $no++?>
          <tr>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $no?></td>
          <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->kode_brg.' - '.$row_dt->nama_brg?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo ($row_dt->jumlah_stok_sebelumnya)?$row_dt->jumlah_stok_sebelumnya:0; echo ' '.$row_dt->satuan_kecil; ?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt->jml_besar, 2)?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->satuan_besar?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->rasio?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php $konversi = $row_dt->jml_besar * $row_dt->rasio; echo number_format($konversi).' '.$row_dt->satuan_kecil; ?></td>
          </tr>
      <?php endforeach;?>
  </tbody>
</table>
<br>
<table style="width: 100% !important; text-align: center">
  <tr>
    <td style="text-align: center; width: 30%">
      <b>Diajukan Oleh</b>
      <br>
      <br>
      <br>
      <br>
      <?php echo ( $flag == 'non_medis' ) ? $this->master->get_ttd('ttd_ka_gdg_nm') : $this->master->get_ttd('ttd_ka_gdg_m') ; ?>
    </td>
    <td style="text-align: center; width: 40%">
      <b>Mengetahui</b>
      <br>
      <br>
      <br>
      <br>
      <?php echo ($flag=='non_medis') ? $this->master->get_ttd('ttd_waka_rs_bid_adm') : $this->master->get_ttd('ttd_waka_rs_bid_pl') ;?>
    </td>
    <td style="text-align: center; width: 30%">
      <b>Verifikator</b>
      <br>
      <br>
      <br>
      <br>
      <?php echo $this->master->get_ttd('ttd_ka_tim_barjas');?>
    </td>
  </tr>
  <tr>
    <td style="text-align: center"></td>
    <td style="text-align: center">
      <br>
      <b>Menyetujui</b>
      <br>
      <br>
      <br>
      <br>
      <?php echo $this->master->get_ttd('ttd_ka_rs');?>
    </td>
    <td style="text-align: center"></td>
  </tr>
</table>
