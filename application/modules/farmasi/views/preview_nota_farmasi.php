<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){

    $("barcodeTarget").update();
    var value = "<?php echo $penerimaan->id_tc_po.'-'.$penerimaan->no_urut_periodik; ?>";
    var btype = "code128";
    
    var settings = {
      output:"css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: 2,
      barHeight: 35,
      moduleSize: 20,
      fontSize: 12,
      posX: 20,
      posY: 20,
      addQuietZone: false
    };
    $("barcodeTarget").update().show().barcode(value, btype, settings);


  }
    
</script> 

<style>

.barcodeTarget{
  font-weight: bold;margin-top: 5px;letter-spacing: 11px; float: right;
}

body, table, p{
  font-family: calibri;
  font-size: 12px;
  background-color: white;
  width: 500px;
  align: center
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

<body>
  <table width="100%" border="0">
    <tr>
      <td width="70px"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="60px"></td>
      <td valign="bottom" width="320px"><b><span style="font-size: 18px"><?php echo COMP_LONG; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
      <td align="right"><div id="barcodeTarget" class="barcodeTarget"></div></td>
    </tr>
  </table>
  <hr>
  <center><span style="font-size: 12px;"><strong>NOTA FARMASI<br><?php echo $this->tanggal->formatDateTime($resep[0]->tgl_trans) ?></strong></span></center>
  
  <table id="no-border" style="width: 100% !important;">
    <tr>
      <td width="50%">
        <table>
          <tr style="font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td width="100px"><b>No. Transaksi</b></td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse"><?php echo $resep[0]->kode_trans_far?></td>
          </tr>
          <tr style="font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td width="100px"><b>No. Resep</b></td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse"><?php echo $resep[0]->no_resep?></td>
          </tr>
          <tr style="font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td width="100px"><b>Nama Pasien</b></td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse"><?php echo $resep[0]->nama_pasien?></td>
          </tr>
          <tr style="font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td width="100px"><b>No. MR</b></td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse"><?php echo $resep[0]->no_mr?></td>
          </tr>
          <tr style="font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td width="100px"><b>Dokter</b></td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse"><?php echo $resep[0]->dokter_pengirim?></td>
          </tr>
          <tr style="font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td width="100px"><b>Asal</b></td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse"><?php echo $resep[0]->nama_bagian?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
    <thead>
        <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
          <td style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</td>
          <td style="border: 1px solid black; width: 50px; border-collapse: collapse">Kode</td>
          <td style="border: 1px solid black; border-collapse: collapse">Nama Obat/Alkes</td>
          <td style="text-align:center; width: 50px; border: 1px solid black; border-collapse: collapse">Jumlah</td>
          <td style="text-align:center; width: 50px; border: 1px solid black; border-collapse: collapse">Satuan</td>
          <td style="text-align:center; width: 70px; border: 1px solid black; border-collapse: collapse">Subtotal</td>
        </tr>
    </thead>
    <tbody>
        <?php 
          $no=0; 
          foreach($resep as $key_dt=>$row_dt) : $no++; 
            $arr_total[] = $row_dt->harga_jual;
            $desc = ($row_dt->nama_brg != null) ? $row_dt->nama_brg : $row_dt->nama_racikan;
            $satuan = ($row_dt->satuan_kecil != null) ? $row_dt->satuan_kecil : $row_dt->satuan_brg;
        ?>
            <tr>
              <td style="text-align:center; border-collapse: collapse"><?php echo $no?></td>
              <td style="border-collapse: collapse"><?php echo $row_dt->kode_brg?></td>
              <td style="border-collapse: collapse"><?php echo $desc?></td>
              <td style="text-align:center; border-collapse: collapse"><?php echo (int)$row_dt->jumlah_pesan?></td>
              <td style="text-align:center; border-collapse: collapse"><?php echo $satuan?></td>
              <td style="text-align:right; border-collapse: collapse"><?php echo number_format($row_dt->harga_jual, 2)?></td>
            </tr>

            <?php endforeach;?>
            <tr>
              <td colspan="5" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Total </td>
              <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_total), 2)?></td>
            </tr>
            <tr>
            <td colspan="6" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
            <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_total)))?> Rupiah"</i></b>
            </td>
            </tr>

    </tbody>
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
        <?php $decode = json_decode($resep[0]->created_by); echo isset($decode->fullname)?$decode->fullname:$this->session->userdata('user')->fullname;?>
      </td>
    </tr>
    
  </table>

</body>