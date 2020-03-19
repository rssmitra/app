	<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<style>

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
  <div style="float: right">
    <button class="tular" onClick="window.close()">Tutup</button>
    <button class="tular" onClick="print()">Cetak</button>
  </div>
  
  	<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
			<tr >
				<td width="10%" style="padding:5px"><IMG SRC="<?php echo base_url()?>uploaded/bakti_husada.gif"  BORDER="0" ALT=""></td>
				<td class="header" style="font: 11px/normal tahoma,verdana;padding:5px;font-weight:bold;">
					<div class="judul1">Formulir RL 1.2<BR>INDIKATOR PELAYANAN RUMAH SAKIT</div>
				</td>
				<td width="30%" style="padding:5px">
					<IMG SRC="<?php echo base_url()?>uploaded/ditjen_kes.gif"  BORDER="0" ALT="">
				</td>
			</tr>

			<tr >
				<td style="font: 11px/normal tahoma,verdana;padding:5px;font-weight:bold;">Kode RS</td>
				<td style="font: 11px/normal tahoma,verdana;padding:5px;font-weight:bold;">:&nbsp;<?php echo $konf->kode_rs ?></td>
				<td >&nbsp;</td>
			</tr>
			<tr >
				<td style="font: 11px/normal tahoma,verdana;padding:5px;font-weight:bold;">Nama RS</td>
				<td style="font: 11px/normal tahoma,verdana;padding:5px;font-weight:bold;">:&nbsp;<?php echo $konf->nama_perusahaan ?></td>
				<td >&nbsp;</td>
			</tr>
			
			<tr >
				<td style="font: 11px/normal tahoma,verdana;padding:5px;font-weight:bold;">Tahun</td>
				<td style="font: 11px/normal tahoma,verdana;padding:5px;font-weight:bold;">:&nbsp;<?php echo $_POST['year'] ?></td>
				<td >&nbsp;</td>
			</tr>
			
		</table>

	