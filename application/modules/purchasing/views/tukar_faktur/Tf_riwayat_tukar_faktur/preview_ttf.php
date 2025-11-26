<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){

    $("barcodeTarget").update();
    var value = "<?php echo ''.$result[0]->no_terima_faktur; ?>";
    var btype = "code128";
    
    var settings = {
      output:"css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: 1.5,
      barHeight: 35,
      moduleSize: 20,
      fontSize: 11,
      posX: 30,
      posY: 30,
      addQuietZone: false
    };
    $("barcodeTarget").update().show().barcode(value, btype, settings);


  }
    
</script> 

<style>

.barcodeTarget{
  font-weight: bold;margin-top: 5px;letter-spacing: 2px; float: right;
}

body, table, p{
  font-family: calibri;
  font-size: 14px;
  background-color: white;
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
  <div id="barPrint" style="float: right">
    <button class="tular" onClick="window.close()">Tutup</button>
    <button class="tular" onClick="print()">Cetak</button>
  </div>

  <table width="100%" border="0">
    <tr>
      <td width="70px"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="60px"></td>
      <td valign="bottom" width="320px"><b><span style="font-size: 18px"><?php echo COMP_FULL; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
      <td align="right"><div id="barcodeTarget" class="barcodeTarget"></div></td>
    </tr>
  </table>
  <hr>
  <center><span style="font-size: 16px"><strong>BUKTI TANDA TERIMA FAKTUR</strong></span></center> 
  <table id="no-border" style="width: 100% !important;">
    <tr>
      <td width="50%">
        <span>No. TTF :</span><br>
        <span style="font-size: 18px"><b><?php echo $result[0]->no_terima_faktur?></b></span><br>
        <span>Tanggal. <?php echo $this->tanggal->formatDatedmY($result[0]->tgl_faktur); ?></span><br>
        Tanggal jatuh tempo pembayaran.<br><?php echo $this->tanggal->formatDatedmY($result[0]->tgl_rencana_bayar); ?>
        <br>
        No. Seri Faktur Pajak :<br>
        <b><?php echo $result[0]->no_seri_pajak?></
      </td>
      <td width="50%" valign="top">
        Supplier :<br>
        <b><?php echo $result[0]->namasupplier?></b><br>
        <?php echo $result[0]->alamat?><br>
        <?php echo $result[0]->telpon1?><br>
      </td>
    </tr>
  </table>

  <br>
  <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
    <thead>
        <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
          <td style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</td>
          <td style="width:70px; border: 1px solid black; border-collapse: collapse">No.Faktur</td>
          <td style="border: 1px solid black; border-collapse: collapse">Kode Penerimaan</td>
          <!-- <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Tanggal</td> -->
          <td style="text-align:center; width: 80px; border: 1px solid black; border-collapse: collapse">Jumlah (Rp)</td>
        </tr>
    </thead>
    <tbody>
        <?php 
          $no=0; 
          foreach($result as $key_dt=>$row_dt) : $no++; 
          $arr_tagihan[] = $row_dt->total_hutang;
        ?>
            <tr>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $no?></td>
              <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->no_faktur; ?></td>
              <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->kode_penerimaan; ?></td>
              <!-- <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $this->tanggal->formatDateDmy($row_dt->tgl_jam); ?></td> -->
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt->total_hutang)?></td>
            </tr>
            <?php endforeach;?>

            <tr>
              <td colspan="3" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Subtotal </td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse">
                <?php 
                  $subtotal = array_sum($arr_tagihan);
                  echo number_format($subtotal);
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="3" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">PPN </td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse">
                <?php
                  $total_ppn = $result[0]->total_ppn; 
                  echo number_format($total_ppn);
                ?>
              </td>
            </tr>

            <tr>
              <td colspan="3" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Materai </td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse">
                <?php
                 $biaya_materai = $result[0]->biaya_materai; echo number_format($biaya_materai);
                ?>
              </td>
            </tr>

            <tr>
              <td colspan="3" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Total </td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse">
                <?php
                  $total = $subtotal + $total_ppn + $biaya_materai;
                  echo number_format($total);
                ?>
              </td>
            </tr>

            <tr>
            <td colspan="4" style="text-align:right; border: 1px solid black; border-collapse: collapse">Terbilang : 
            <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang($total))?> Rupiah"</i></b>
            </td>
            </tr>

    </tbody>
  </table>
  <br>
  <table style="width: 100% !important; text-align: center">
    <tr>
      <td style="text-align: left; width: 30%">
        &nbsp;
      </td>
      <td style="text-align: center; width: 40%">&nbsp;</td>
      <td style="text-align: center; width: 30%">
        <span style="font-size: 14px"><b>Jakarta, <?php echo date('d/M/Y'); ?></b></span>
        <br>
        <br>
        <br>
        <br>
        <?php echo $this->session->userdata('user')->fullname;?><br>
        Petugas
      </td>
    </tr>
    
  </table>
</body>