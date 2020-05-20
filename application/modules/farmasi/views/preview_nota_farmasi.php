<style>
body{
  font-size: 11px;
  font-family: arial;
  border-spacing: 5em;
}
</style>
<body>
<table width="100%" border="0">
  <tr>
    <td width="60px"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="60px"></td>
    <td valign="bottom" width="320px"><b><span style="font-size: 18px"><?php echo COMP_LONG; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
  </tr>
</table>
<hr>
<center><span style="font-size: 12px;"><strong><u>NOTA FARMASI</u></strong><br>
No. <?php echo $resep[0]['kode_trans_far']?> - <?php echo $resep[0]['no_resep']?>
</span></center>

<table>
  <tr style="border: 1px solid black; border-collapse: collapse">
    <td width="100px">Tanggal</td>
    <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($resep[0]['tgl_trans']) ?></td>
  </tr>
  <tr style="border: 1px solid black; border-collapse: collapse">
    <td width="100px">Nama Pasien</td>
    <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $resep[0]['nama_pasien']?></td>
  </tr>
  <tr style="border: 1px solid black; border-collapse: collapse">
    <td width="100px">No. MR</td>
    <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $resep[0]['no_mr']?></td>
  </tr>
  <tr style="border: 1px solid black; border-collapse: collapse">
    <td width="100px">Dokter</td>
    <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $resep[0]['dokter_pengirim']?></td>
  </tr>
  <tr style="border: 1px solid black; border-collapse: collapse">
    <td width="100px">Asal</td>
    <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $resep[0]['nama_bagian']?></td>
  </tr>
</table>

<table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
  <thead>
      <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
        <td style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">No</td>
        <td style="border-bottom: 1px solid black; border-collapse: collapse">Uraian</td>
        <td style="text-align:center; width: 50px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah</td>
        <td style="text-align:center; width: 50px; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
        <td style="text-align:center; width: 70px; border-bottom: 1px solid black; border-collapse: collapse">Subtotal</td>
      </tr>
  </thead>
      <?php 
        $no=0; 
        foreach($resep as $key_dt=>$row_dt) : $no++; 
          $arr_total[] = ceil($row_dt['harga_jual']);
          $desc = ($row_dt['flag_resep'] == 'racikan') ? 'Jasa Racik Farmasi' : $row_dt['nama_brg'];
          $satuan = ($row_dt['satuan_kecil'] != null) ? $row_dt['satuan_kecil'] : $row_dt['satuan_brg'];
          $harga_jual = ($row_dt['flag_resep'] == 'racikan') ? $row_dt['jasa_r'] + $row_dt['jasa_produksi'] : $row_dt['harga_jual'];
      ?>

        <tr>
          <td style="text-align:center; border-collapse: collapse"><?php echo $no?></td>
          <td style="border-collapse: collapse"><?php echo $desc?></td>
          <td style="text-align:center; border-collapse: collapse"><?php echo (int)$row_dt['jumlah_pesan']?></td>
          <td style="text-align:left; border-collapse: collapse"><?php echo $satuan?></td>
          <td style="text-align:right; border-collapse: collapse"><?php echo number_format($harga_jual)?></td>
        </tr>
        <?php 
          if($row_dt['flag_resep'] == 'racikan') :
            foreach ($row_dt['racikan'][0] as $key => $value) {
              echo '<tr>
                        <td style="text-align:center; border-collapse: collapse">&nbsp;</td>
                        <td style="border-collapse: collapse"> - '.$value->nama_brg.'</td>
                        <td style="text-align:center; border-collapse: collapse">'.$value->jumlah.'</td>
                        <td style="text-align:left; border-collapse: collapse">'.$value->satuan.'</td>
                        <td style="text-align:right; border-collapse: collapse">'.number_format($value->harga_jual).'</td>
                      </tr>';
            }
          endif; 
        ?>

      <?php endforeach;?>

        <tr>
          <td colspan="4" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Total </td>
          <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_total))?></td>
        </tr>
        <tr>
          <td colspan="5" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
          <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_total)))?> Rupiah"</i></b>
          </td>
        </tr>

</table>

Catatan : Obat yang sudah dibeli tidak bisa dikembalikan
<table style="width: 100% !important; text-align: center">
  <tr>
    <td style="text-align: left; width: 30%">&nbsp;</td>
    <td style="text-align: center; width: 40%">&nbsp;</td>
    <td style="text-align: center; width: 30%">
      <span style="font-size: 14px"><b>Petugas</b></span>
      <br>
      <br>
      <br>
      <br>
      <?php $decode = json_decode($resep[0]['created_by']); echo isset($decode->fullname)?$decode->fullname:$this->session->userdata('user')->fullname;?>
    </td>
  </tr>
  
</table>
</body>
