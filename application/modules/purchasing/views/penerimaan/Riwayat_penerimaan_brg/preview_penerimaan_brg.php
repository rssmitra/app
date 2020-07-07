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
      <td width="70px"><img src="<?php echo base_url().COMP_ICON; ?>" alt="" width="60px"></td>
      <td valign="bottom" width="320px"><b><span style="font-size: 18px"><?php echo COMP_FULL; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
      <td align="right"><div id="barcodeTarget" class="barcodeTarget"></div></td>
    </tr>
  </table>

  <hr>

  <center><span style="font-size: 16px"><strong>BERITA ACARA PENERIMAAN BARANG</strong></span></center><br>

  <table id="no-border" style="width: 100% !important;">
    <tr>
      <td width="50%" valign="top">
        Barang sudah diterima dengan rincian terlampir dibawah ini dari : <br>
        <b><?php echo $penerimaan->namasupplier?></b><br>
        <?php echo $penerimaan->alamat?><br>
        <?php echo $penerimaan->telpon1?><br>
      </td>
      <td width="50%">
        <table>
          <tr style="padding: 1px !important; background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td style="padding: 1px !important" width="100px"><b>Nomor Penerimaan</b></td>
            <td style="padding: 1px !important; background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse">: <?php echo $penerimaan->kode_penerimaan?></td>
          </tr>
          <tr style="padding: 1px !important; background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td style="padding: 1px !important"><b>Tanggal Terima</b></td>
            <td style="padding: 1px !important; background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse">: <?php echo $this->tanggal->formatDatedmY($penerimaan->tgl_penerimaan); ?></td>
          </tr>
          <tr style="padding: 1px !important; background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td style="padding: 1px !important"><b>Tanggal Cetak</b></td>
            <td style="padding: 1px !important; background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse">: <?php echo date('d/m/Y H:i:s'); ?></td>
          </tr>
        </table>
      </td>
      
    </tr>
  </table>

  <br>
  
  <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
    <thead>
        <tr style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
          <td rowspan="2" style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</td>
          <td rowspan="2" style="border: 1px solid black; border-collapse: collapse">Kode & Nama Barang</td>
          <td rowspan="2" style="text-align:center; width: 50px; border: 1px solid black; border-collapse: collapse">Rasio</td>
          <td rowspan="2" style="text-align:center; width: 70px; border: 1px solid black; border-collapse: collapse">Satuan</td>
          <td rowspan="2" style="text-align:center; width: 60px; border: 1px solid black; border-collapse: collapse">Jumlah<br>Pesan</td>
          <td rowspan="2" style="text-align:center; width: 60px; border: 1px solid black; border-collapse: collapse">Jumlah<br>Terima</td>
          <!-- <td rowspan="2" style="text-align:center; width: 75px; border: 1px solid black; border-collapse: collapse">Harga Satuan</td>
          <td colspan="2" style="text-align:center; width: 70px; border: 1px solid black; border-collapse: collapse">Diskon</td>
          <td rowspan="2" style="text-align:center; width: 75px; border: 1px solid black; border-collapse: collapse">Total Harga</td> -->
        </tr>
        <!-- <tr style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
          <td style="text-align:center; width: 60px; border: 1px solid black; border-collapse: collapse">%</td>
          <td rowspan="2" style="text-align:center; width: 60px; border: 1px solid black; border-collapse: collapse">Rp</td>
        </tr> -->
    </thead>
    <tbody>
        <?php 
          $no=0; 
          // print_r($penerimaan_data);die;
          foreach($penerimaan_data as $key_dt=>$row_dt) : $no++; 
            // sum jumlah kirim
            foreach($row_dt as $key_row=>$val_row){
              $jumlah_kirim[] = $val_row->jumlah_kirim;
            }
            $discount_rp = $row_dt[0]->harga * ($row_dt[0]->disc/100);
            $arr_dpp[] = $row_dt[0]->dpp;
            $arr_ppn[] = $row_dt[0]->ppn;
        ?>
            <tr>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $no?></td>
              <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->kode_brg.' - '.$row_dt[0]->nama_brg?></td>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->content?></td>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->satuan_besar?></td>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt[0]->jumlah_pesan)?></td>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($jumlah_kirim))?></td>
              <!-- <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt[0]->harga_net).',-'; ?></td>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->disc; ?></td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt[0]->discount_rp).',-'; ?></td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt[0]->dpp).',-';?></td> -->
            </tr>
            <?php 
              endforeach;
              $total_akhir = array_sum($arr_dpp) - array_sum($arr_ppn);
            ?>

            <!-- <tr>
              <td colspan="9" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">DPP </td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_dpp))?></td>
            </tr>
            <tr>
              <td colspan="9" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">PPN </td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_ppn))?></td>
            </tr>

            <tr>
              <td colspan="9" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Total </td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($total_akhir)?></td>
            </tr>
            <tr>
              <td colspan="10" style="text-align:right; border: 1px solid black; border-collapse: collapse">Terbilang : 
              <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang($total_akhir))?> Rupiah"</i></b>
              </td>
            </tr> -->

    </tbody>
  </table>
  
  <table style="width: 100% !important; text-align: center">
  <tr>
    <td style="text-align: center; width: 25">
      <b>Diketahui oleh,</b><br><br><br><br>
      <?php echo ( $flag == 'non_medis' ) ? $this->master->get_ttd('verifikator_nm_2') : $this->master->get_ttd('verifikator_m_2') ; ?>
    </td>
    <td style="text-align: center; width: 25">
      <b>Diperiksa oleh,</b><br><br><br><br>
      <?php echo ( $flag == 'non_medis' ) ? $this->master->get_ttd('ttd_ka_gdg_nm') : $this->master->get_ttd('ttd_ka_gdg_m') ; ?>
    </td>
    <td style="text-align: center; width: 25%;" valign="top" >
      <b>Diterima oleh,</b><br><br><br><br>
      <?php echo $penerimaan->petugas?>
    </td>
    <td style="text-align: center; width: 25" valign="top">
      <b>Supplier</b><br><br><br><br>
      <?php echo $penerimaan->namasupplier?>
    </td>
  </tr>
</table>

</body>