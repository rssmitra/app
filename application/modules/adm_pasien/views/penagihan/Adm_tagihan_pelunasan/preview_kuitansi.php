<?php
$tgl = date("d");
$bln = date("m");
$thn = date("Y");
$tglsekarang = date("d-m-Y");

?>
<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/print.css" class="ace-main-stylesheet" id="main-ace-style" />

<style>

.stamp {
      margin-top: -96px;
      margin-left: 600px;
      position: absolute;
      display: inline-block;
      color: black;
      padding: 1px;
      padding-left: 10px;
      padding-right: 10px;
      background-color: white;
      box-shadow:inset 0px 0px 0px 0px;
      /*opacity: 0.5;*/
      -webkit-transform: rotate(25deg);
      -moz-transform: rotate(25deg);
      -ms-transform: rotate(25deg);
      -o-transform: rotate(25deg);
      transform: rotate(0deg);
     
}
   
</style>
<body>

	<div style="float: right">
		<button class="tular" onClick="window.close()">Tutup</button>
		<button class="tular" onClick="print()">Cetak</button>
	</div>

	<div class="row"> 
		<div class="col-xs-8">
			<table style="font-size:12px" border="0">
				<tr> 
					<td width="20%" style="font-size:12px">Telah Terima dari </td> 
					<td width="67%"><font size="2">: <?php echo $name; ?>&nbsp;</font></td>
				</tr>
				<tr>
					<td width="20%" style="font-size:12px">Uang Sejumlah</td> 
					<td width="67%" bgcolor="#EBEBEB" nowrap style="font-size:12px">: <b>Rp. <?php echo number_format($total)?></b></td> 
				</tr>
				<tr>
					<td width="20%" valign="top" style="font-size:12px">Terbilang </td> 
					<td style="67%" bgcolor="#EBEBEB" nowrap style="font-size:12px">: 
					<b>
						<i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang($total))?> Rupiah"</i></b>
					</td>
				</tr> 
				<tr>
					<td width="20%">&nbsp;</td> 
					<td width="67%" bgcolor="#EBEBEB">&nbsp;</td> 
				</tr>
				<tr> 
					<td width="20%" style="font-size:12px">Untuk Pembayaran</td> 
					<!-- Update Pengembalian Uang Muka 131011-->
					<td width="67%" bgcolor="#EBEBEB">: Pembayaran Tagihan Invoice No. <?php echo $inv; ?></td> 
				</tr>
			</table>
			<br>
			<table width="90%" border="0" cellspacing="0" cellpadding="2" align="center"> 
				<tr> 			
					<td width="10%">&nbsp;</td> 
					<td valign="top" width="42%">&nbsp; 
					</td> 
					<?
						//$nm_perusahaan=baca_tabel("dd_konfigurasi","nama_perusahaan");

					?>
					<td valign="top" width="2%">&nbsp;</td>
					<td valign="top" width="55%" align="center" style="font-size:12px">
						<!--Jakarta,<?=$tgl_now_full?><br>Petugas Kasir<?//=trim($total_nd)=="0" || $bill!="0" ? $nm_perusahaan : ""?>-->
						<!-- Update Kwitansi Pengembalian Uang Muka 131011-->
						Jakarta, <?php echo $tgl ?><br>Penerima
						<br/><br/><br/><br/> 
						<br/><BR><BR> 
						( ..................... )<br/><br/>
						</td>
				</tr>
				<tr>
					<td colspan="4">
						Kuitansi ini menjadi SAH bila telah diberi cap & tanda tangan petugas
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<br/><br/><br/><br/><br/>
					</td>
				</tr>
			</table>
		</div>
	</div>
</body>

   
