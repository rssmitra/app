<style>
.body{
  font-family: Arial, Helvetica, sans-serif;
  color: black;
  text-align: left;
  background-color: white; 
  border-spacing: 5em;
}
</style>

<div class="body">
  <table width="100%" border="0">
    <tr>
      <td width="20%" align="center"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="50px"></td>
      <td valign="bottom" width="80%"><b><span><?php echo COMP_LONG; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
    </tr>
  </table>
  <hr>
  <div style="width: 100%; text-align: center"><span ><strong><u>FORM PENYERAHAN OBAT PASIEN</u></strong><br>
    No. RSK-<?php echo $value->kode_trans_far?> - <?php echo $value->no_resep?>
    </span>
  </div>
  <br>
  <table width="100%">
    <tr>
      <td width="100px">Tanggal</td>
      <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($value->tgl_trans) ?></td>
    </tr>
    <tr>
      <td width="100px">Nama Pasien</td>
      <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($value->nama_pasien)?></td>
    </tr>
    <tr>
      <td width="100px">No. MR</td>
      <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $value->no_mr?></td>
    </tr>
    <tr>
      <td width="100px">Dokter</td>
      <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($value->dokter_pengirim)?></td>
    </tr>
    <tr>
      <td width="100px">Unit/Bagian</td>
      <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $value->kode_bagian_asal) ))?></td>
    </tr>
  </table>
  <br>
  <table style="width: 100% !important;margin-top: 10px; margin-bottom: 10px" >
    <thead>
        <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
          <td style="text-align:center; width: 5%; border-bottom: 1px solid black; border-collapse: collapse">No</td>
          <td style="border-bottom: 1px solid black; width: 75%; border-collapse: collapse">Nama Obat</td>
          <td style="text-align:center; width: 10%; border-bottom: 1px solid black; border-collapse: collapse">Jumlah</td>
          <td style="text-align:center; width: 10%; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
        </tr>
    </thead>
        <?php 
          $no=0; 
          foreach($resep as $key_dt=>$row_dt) : $no++;    
          $arr_total[] = $row_dt->sub_total;  
          $jumlah_bln = $row_dt->jumlah_7 + $row_dt->jumlah_obat_23;     
        ?>

          <tr>
            <td style="text-align:center; width: 5%; border-collapse: collapse"><?php echo $no?>.</td>
            <td style="border-collapse: collapse; width: 75%; "><?php echo $row_dt->nama_brg;?></td>
            <td style="text-align:center; width: 10%; border-collapse: collapse"><?php echo number_format($jumlah_bln);?></td>
            <td style="text-align:left; width: 10%; border-collapse: collapse"><?php echo $row_dt->satuan_kecil?></td>
            <!-- <td style="text-align:right; width: 15%; border-collapse: collapse"><?php echo number_format($row_dt->sub_total)?></td> -->
          </tr>
          
        <?php endforeach;?>

          <!-- <tr>
            <td colspan="4" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Total </td>
            <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_total))?></td>
          </tr>
          <tr>
            <td colspan="5" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
            <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_total)))?> Rupiah"</i></b>
            </td>
          </tr> -->

  </table>
  <br>
  Catatan : Obat yang sudah dibeli tidak bisa dikembalikan
  <br>
  <table style="width: 100% !important; text-align: center">
    <tr>
      <td style="text-align: left; width: 60%">&nbsp;</td>
      <td align="center" style="text-align: center; width: 40%">
        <span><b>Pasien/Keluarga Pasien</b></span>
        <br>
        <?php 
          echo $qr_img;
        ?>
      </td>
    </tr>
    
  </table>
</div>
