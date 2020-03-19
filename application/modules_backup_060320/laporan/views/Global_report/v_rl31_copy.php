	 <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
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
					<div class="judul1">Formulir RL 3.1<BR>KEGIATAN PELAYANAN RAWAT INAP</div>
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
<table border="0" cellpadding="0" cellspacing="0">
		<tbody>
			
						<TR class="headTable">
							<TD class="border-trbl" style="text-align:center;font-weight:bold;width:10px" rowspan="2" >NO</TD>
							<TD class="border-trb" style="text-align:center;font-weight:bold;width:150px" rowspan="2">JENIS PELAYANAN</TD>
							<TD class="border-trb" style="text-align:center;font-weight:bold;width:100px" rowspan="2">PASIEN AWAL TAHUN</TD>
							<TD class="border-trb" style="text-align:center;font-weight:bold;width:100px" rowspan="2">PASIEN MASUK</TD>
							<TD class="border-trb" style="text-align:center;font-weight:bold;width:100px" rowspan="2">PASIEN KELUAR HIDUP</TD>
							<TD class="border-trb" style="text-align:center;font-weight:bold;width:100px" COLSPAN="2">PASIEN KELUAR MATI</TD>
							<TD class="border-trb" style="text-align:center;font-weight:bold;width:100px" rowspan="2">JUMLAH LAMA DIRAWAT</TD>
							<TD class="border-trb" style="text-align:center;font-weight:bold;width:100px" rowspan="2">PASIEN AKHIR TAHUN</TD>
							<TD class="border-trb" style="text-align:center;font-weight:bold;width:100px" rowspan="2">JUMLAH HARI PERAWATAN</TD>
							<TD class="border-trb" style="text-align:center;font-weight:bold" COLSPAN="6">PERINCIAN TEMPAT TIDUR PER-KELAS</TD>
						</TR>
						<TR class="headTable">
							
							<TD class="border-rb" style="text-align:center;font-weight:bold;width:50px">< 48 jam</TD>
							<TD class="border-rb" style="text-align:center;font-weight:bold;width:50px">VVIP</TD>
							<TD class="border-rb" style="text-align:center;font-weight:bold;width:50px">VVIP</TD>
							<TD class="border-rb" style="text-align:center;font-weight:bold;width:50px">VIP</TD>
							<TD class="border-rb" style="text-align:center;font-weight:bold;width:50px">I</TD>
							<TD class="border-rb" style="text-align:center;font-weight:bold;width:50px">II </TD>
							<TD class="border-rb" style="text-align:center;font-weight:bold;width:50px">III </TD>
							<TD class="border-rb" style="text-align:center;font-weight:bold;width:200px">Kelas Khusus </TD>
						</TR>
						<TR class="headTable">
							<TD class="border-rbl" style="background-color: #C0C0C0;text-align:center;font-weight:bold" >1</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">2</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">3</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">4</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">5</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">6</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">7</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">8</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">9</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">10</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">11</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">12</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">13</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">14</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">15</TD>
							<TD class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">16</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">1</TD>
							<TD class="border-rb">Penyakit Dalam&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">2</TD>
							<TD class="border-rb">Kesehatan Anak&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						
						<TR class="contentTable">
							<TD class="border-rbl">3</TD>
							<TD class="border-rb">Obstetri&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">4</TD>
							<TD class="border-rb">Genekologi&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">5</TD>
							<TD class="border-rb">Bedah&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">6</TD>
							<TD class="border-rb">Bedah Orthopedi&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">7</TD>
							<TD class="border-rb">Bedah Saraf&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">8</TD>
							<TD class="border-rb">Luka Bakar&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">9</TD>
							<TD class="border-rb">S a r a f&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">10</TD>
							<TD class="border-rb">J i w a&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">11</TD>
							<TD class="border-rb">Psikologi&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">12</TD>
							<TD class="border-rb">Penatalaksana Pnyguna. NAPZA&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">13</TD>
							<TD class="border-rb">T H T&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">14</TD>
							<TD class="border-rb">M a t a&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">15</TD>
							<TD class="border-rb">Kulit & Kelamin&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">16</TD>
							<TD class="border-rb">Kardiologi&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">17</TD>
							<TD class="border-rb">Paru-paru&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">18</TD>
							<TD class="border-rb">Geriatri&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">19</TD>
							<TD class="border-rb">Radioterapi&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">20</TD>
							<TD class="border-rb">Kedokteran Nuklir&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">21</TD>
							<TD class="border-rb">K u s t a&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">22</TD>
							<TD class="border-rb">Kedokteran Nuklir&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">23</TD>
							<TD class="border-rb">Rehabilitasi Medik&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">24</TD>
							<TD class="border-rb">Isolasi&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<?php foreach ($result as $key => $value) : 
						 // print_r($value); die;
						$awal_tahun_icu = array_search('031001', array_column($result[$key], 'kode_bagian'));
						// $awal_tahun_icu = array_search('031001', array_column($result, 'kode_bagian'));
						$awal_tahun_bayi = array_search('031201', array_column($result[$key], 'kode_bagian'));
					
						?>
						
						<TR class="contentTable">
							<TD class="border-rbl">25</TD>
							<TD class="border-rb">I C U&nbsp;</TD>
							<TD class="border-rb" align="center"><?php echo $value[$awal_tahun_icu]['awal'] ?>&nbsp;</TD>
							<TD class="border-rb" align="center"><?php echo $value[$awal_tahun_icu]['jml_msk'] ?>&nbsp;</TD>
							<TD class="border-rb" align="center"><?php echo $value[$awal_tahun_icu]['jml_klr'] ?>&nbsp;</TD>
							<TD class="border-rb" align="center"><?php echo $value[$awal_tahun_icu]['jml_mati'] ?>&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
						</TR>
						
						<TR class="contentTable">
							<TD class="border-rbl">26</TD>
							<TD class="border-rb">I C C U&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						
						<TR class="contentTable">
							<TD class="border-rbl">27</TD>
							<TD class="border-rb">NICU / PICU&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">28</TD>
							<TD class="border-rb">Umum&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">29</TD>
							<TD class="border-rb">Gigi & Mulut&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">30</TD>
							<TD class="border-rb">Pelayanan Rawat Darurat&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">31</TD>
							<TD class="border-rb" align="center">Perinatologi/Bayi&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
							<TD class="border-rb" align="center">&nbsp;</TD>
						</TR>
						
						<?php endforeach;?>

					</tbody>	
		</table>
   
</body>
</html>