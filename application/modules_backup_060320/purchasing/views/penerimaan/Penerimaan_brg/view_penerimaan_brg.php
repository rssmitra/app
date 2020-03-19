<span style="font-size: 16px; font-weight: bold"><a href="#" onclick="updatePenerimaan()"><?php echo isset($value->kode_penerimaan)?$value->kode_penerimaan:''?></a></span>

<table>

  <tr>
    <td width="100px">Nomor PO</td>
    <td width="200px">: <?php echo isset($value->no_po)?$value->no_po:''?></td>
  </tr>
  <tr>
  <td>No.Faktur</td>
    <td>: <?php echo isset($value->no_faktur)?$value->no_faktur:''?></td>
    <td>Tgl Penerimaan</td>
    <td width="100px">: <?php echo isset($value->tgl_penerimaan)? $this->tanggal->formatDatedmY($this->tanggal->formatDateTimeToSqlDate($value->tgl_penerimaan)): date('Y-m-d') ?></td>
  </tr>
  <tr>
    <td>Pengirim</td>
    <td>: <?php echo isset($value->dikirim)?$value->dikirim:''?></td>
    <td width="100px">Penerima</td>
    <td>: <?php echo isset($value->petugas)?$value->petugas:''?></td>
  </tr>
  <tr>
    <td>Keterangan</td>
    <td colspan="3">: <?php echo isset($value->keterangan)?$value->keterangan:''?></td>
  </tr>

</table>

<hr>