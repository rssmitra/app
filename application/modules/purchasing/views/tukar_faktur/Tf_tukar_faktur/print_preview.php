<style>
table, p{
  font-family: calibri;
  font-size: 14px;
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
<center><p><b>BUKTI TANDA TERIMA FAKTUR</b></p></center>

<table id="no-border" style="width: 100% !important;">
  <tr>
    <td width="150px"><b>Nomor</b></td>
    <td>: <?php echo $result[0]->no_terima_faktur?></td>
  </tr>
  <tr>
    <td><b>Tanggal Faktur</b></td>
    <td>: <?php echo $this->tanggal->formatDatedmY($result[0]->tgl_faktur); ?></td>
  </tr>
  <tr>
    <td><b>Tanggal Jatuh Tempo</b></td>
    <td>: <?php echo $this->tanggal->formatDatedmY($result[0]->tgl_rencana_bayar); ?></td>
  </tr>
</table>
<br>
<table class="table-utama" style="width: 100% !important;">
  <thead>
      <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
        <td style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</td>
        <td style="border: 1px solid black; border-collapse: collapse">Kode Penerimaan</td>
        <td style="text-align:center; border: 1px solid black; border-collapse: collapse">No Faktur</td>
        <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Total (Rp.)</
      </tr>
  </thead>
  <tbody>
      <?php $no=0; foreach($result as $row_dt) : $no++?>
          <tr>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $no?></td>
          <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->kode_penerimaan?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->no_faktur?></td>
          <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt->total_hutang)?></td>
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
