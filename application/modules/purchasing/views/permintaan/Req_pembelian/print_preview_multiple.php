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
<center><p><b>FORM DAFTAR PERMINTAAN BARANG <?php echo strtoupper($subtitle)?> <br> <?php echo strtoupper($title)?></b></p></center>

<?php 
  foreach( $permohonan as $rows ) :
    //echo '<pre>';print_r($rows);die;
?>

<table id="no-border" style="width: 100% !important;">
  <tr>
    <td width="120px"><b>Nomor Permintaan</b></td>
    <td>: <?php echo isset( $rows[0]['kode_permohonan'] ) ? $rows[0]['kode_permohonan'] : '' ; ?></td>
  </tr>
  <tr>
    <td><b>Tanggal</b></td>
    <td>: 
      <?php echo isset( $rows[0]['tgl_permohonan'] ) ? $this->tanggal->formatDateForm( $rows[0]['tgl_permohonan'] ) : '' ; ?></td>
    <td width="120px" style="text-align: right"><b>Jenis Permintaan</b></td>
    <td style="text-align: left; width: 200px">: 
    <?php echo isset( $rows[0]['flag_jenis'] ) ? ( $rows[0]['flag_jenis'] == 1 ) ? 'Cito' : 'Rutin' : 'Rutin' ; ?>
  </tr>
</table>
<table class="table-utama" style="width: 100% !important;">
  <thead>
      <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
        <td style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</td>
        <td style="border: 1px solid black; border-collapse: collapse">Kode & Nama Barang</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Stok Akhir</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Jumlah<br>Permintaan</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Satuan</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Rasio</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Konversi<br>Satuan Kecil</td>
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
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo ($row_dt['barang']->jumlah_stok_sebelumnya)?$row_dt['barang']->jumlah_stok_sebelumnya:0; echo ' '.$row_dt['barang']->satuan_besar; ?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt['barang']->jml_besar?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt['barang']->satuan_besar?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt['barang']->rasio?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php $konversi = $row_dt['barang']->jml_besar * $row_dt['barang']->rasio; echo number_format($konversi).' '.$row_dt['barang']->satuan_kecil; ?></td>
          </tr>
      <?php endforeach;?>
  </tbody>
</table>
<br>
<?php endforeach;?>

<table border="0" style="width: 100% !important; text-align: center">
  <tr>
    <td style="text-align: center; width: <?php echo ( $flag == 'non_medis' ) ? '30%' : '25%' ; ?>">
      <b>Diajukan Oleh</b>
      <br>
      <br>
      <br>
      <br>
      <?php echo ( $flag == 'non_medis' ) ? $this->master->get_ttd('ttd_ka_gdg_nm') : $this->master->get_ttd('ttd_ka_gdg_m') ; ?>
    </td>
    <?php if( $flag == 'medis' ) : ?>
    <td style="text-align: center; width: <?php echo ( $flag == 'non_medis' ) ? '30%' : '25%' ; ?>">
      <b>Diketahui</b>
      <br>
      <br>
      <br>
      <br>
      <?php echo $this->master->get_ttd('ttd_ka_bid_pm') ; ?>
    </td>
    <?php endif; ?>
    <td style="text-align: center; width: <?php echo ( $flag == 'non_medis' ) ? '30%' : '25%' ; ?>">
      <b>Mengetahui</b>
      <br>
      <br>
      <br>
      <br>
      <?php echo ($flag=='non_medis') ? $this->master->get_ttd('ttd_waka_rs_bid_adm') : $this->master->get_ttd('ttd_waka_rs_bid_pl') ;?>
    </td>
    <td style="text-align: center; width: <?php echo ( $flag == 'non_medis' ) ? '30%' : '25%' ; ?>">
      <b>Verifikator</b>
      <br>
      <br>
      <br>
      <br>
      <?php echo $this->master->get_ttd('ttd_ka_tim_barjas');?>
    </td>
  </tr>
  <tr>
    <td style="text-align: center" colspan="<?php echo ( $flag == 'non_medis' ) ? '3' : '4' ; ?>">
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
