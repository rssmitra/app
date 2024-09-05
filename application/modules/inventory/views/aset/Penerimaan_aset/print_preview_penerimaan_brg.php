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
  <div id="barPrint" style="float: right">
    <button class="tular" onClick="window.close()">Tutup</button>
    <button class="tular" onClick="print()">Cetak</button>
  </div>

  <table width="100%" border="0">
    <tr>
      <td width="70px"><img src="<?php echo base_url().COMP_ICON; ?>" alt="" width="60px"></td>
      <td valign="bottom" width="320px"><b><span style="font-size: 18px"><?php echo COMP_FULL; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
      <td align="right"><div id="barcodeTarget" class="barcodeTarget"></div></td>
    </tr>
  </table>
  <hr>
  <table id="no-border" style="width: 100% !important;">
    <tr>
      <td width="50%">
        <table>
          <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td width="100px"><b>Nomor Penerimaan</b></td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse"><?php echo $penerimaan->kode_penerimaan?></td>
          </tr>
          <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td><b>Tanggal</b></td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse"><?php echo $this->tanggal->formatDatedmY($penerimaan->tgl_penerimaan); ?></td>
          </tr>
          <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td><b>Jenis Permintaan</b></td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse">Rutin</td>
          </tr>
          <!-- <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td><b>Total</b></td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse">Rp. <?php echo number_format($penerimaan->total_stl_ppn)?>,-</td>
          </tr> -->
        </table>
      </td>
      <td width="50%" valign="top">
        Kepada Yth :<br>
        <b><?php echo $penerimaan->namasupplier?></b><br>
        <?php echo $penerimaan->alamat?><br>
        <?php echo $penerimaan->telpon1?><br>
      </td>
    </tr>
  </table>

  <br>
  <center><span style="font-size: 16px"><strong>ORDER PEMBELIAN</strong></span></center><br>
  <span>Agar dikirimkan barang-barang terlampir dibawah ini ke Gudang <?php echo COMP_SORT; ?> :</span>
  <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
    <thead>
        <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
          <td rowspan="2" style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</td>
          <td rowspan="2" style="border: 1px solid black; border-collapse: collapse">Kode & Nama Barang</td>
          <td rowspan="2" style="text-align:center; width: 50px; border: 1px solid black; border-collapse: collapse">Rasio</td>
          <td rowspan="2" style="text-align:center; width: 70px; border: 1px solid black; border-collapse: collapse">Satuan</td>
          <td rowspan="2" style="text-align:center; width: 60px; border: 1px solid black; border-collapse: collapse">Jumlah<br>Pesan</td>
          <td rowspan="2" style="text-align:center; width: 75px; border: 1px solid black; border-collapse: collapse">Harga Satuan</td>
          <td colspan="2" style="text-align:center; width: 70px; border: 1px solid black; border-collapse: collapse">Diskon</td>
          <td rowspan="2" style="text-align:center; width: 75px; border: 1px solid black; border-collapse: collapse">Total Harga</td>
        </tr>
        <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
          <td style="text-align:center; width: 60px; border: 1px solid black; border-collapse: collapse">%</td>
          <td rowspan="2" style="text-align:center; width: 60px; border: 1px solid black; border-collapse: collapse">Rp</td>
        </tr>
    </thead>
    <tbody>
        <?php 
          $no=0; 
          foreach($penerimaan_data as $key_dt=>$row_dt) : $no++; 
          if(count($row_dt) > 0){
            foreach($row_dt as $row_sub_data){
              $jumlah_pesan[$key_dt][] = $row_sub_data->jumlah_besar;
              $jumlah_harga_netto[$key_dt][] = $row_sub_data->harga_satuan;
            }
          }else{
            $jumlah_pesan[$key_dt][] = $row_dt[0]->jumlah_besar;
            $jumlah_harga_netto[$key_dt][] = $row_dt[0]->harga_satuan;
          }
          
          $total_harga = array_sum($jumlah_harga_netto[$row_dt[0]->kode_brg]) - $row_dt[0]->discount_harga;
          
            
        ?>
            <tr>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $no?></td>
              <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->kode_brg.' - '.$row_dt[0]->nama_brg?></td>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->content?></td>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->satuan_besar?></td>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo array_sum($jumlah_pesan[$row_dt[0]->kode_brg])?></td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt[0]->harga_satuan).',-'; ?></td>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->discount; ?></td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt[0]->discount_harga).',-'; ?></td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($total_harga).',-';?></td>
            </tr>
            <?php endforeach;?>

            <tr>
              <td colspan="8" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">DPP </td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($penerimaan->total_sbl_ppn)?></td>
            </tr>
            <tr>
              <td colspan="8" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">PPN </td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($penerimaan->ppn)?></td>
            </tr>

            <tr>
              <td colspan="8" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Total </td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($penerimaan->total_stl_ppn)?></td>
            </tr>
            <tr>
              <td colspan="9" style="text-align:right; border: 1px solid black; border-collapse: collapse">Terbilang : 
              <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang($penerimaan->total_stl_ppn))?> Rupiah"</i></b>
              </td>
            </tr>

    </tbody>
  </table>
  Order Pembelian ini berlaku 14 hari kerja. <br>
  Konfirmasi jika PO dan Faktur berbeda sebelum delivery ED dekat atau stok kosong.<br>
  Lampirkan copy PO saat pengiriman dan Tukar Faktur.
  <br>
  <br>
  <table style="width: 100% !important; text-align: center">
    <tr>
      <td style="text-align: left; width: 30%">
        <b>Tembusan :</b><br>
        1. Akuntansi <br>
        2. Gudang <br>
        3. Arsip <br>
      </td>
      <td style="text-align: center; width: 40%">&nbsp;</td>
      <td style="text-align: center; width: 30%">
        <span style="font-size: 14px"><b><?php echo COMP_LONG; ?></b></span>
        <br>
        <br>
        <br>
        <br>
        <?php echo $this->master->get_ttd('ttd_ka_rs');?>
      </td>
    </tr>
    
  </table>
</body>