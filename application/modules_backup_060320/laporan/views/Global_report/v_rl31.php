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
<table border="1" cellpadding="0" cellspacing="0">
		<tbody>
			
						<tr class="headTable">
							<td class="border-trbl" style="text-align:center;font-weight:bold;width:20px" rowspan="2" >NO</td>
							<td class="border-trb" style="text-align:center;font-weight:bold;width:150px" rowspan="2">JENIS PELAYANAN</td>
							<td class="border-trb" style="text-align:center;font-weight:bold;width:100px" rowspan="2">PASIEN AWAL TAHUN</td>
							<td class="border-trb" style="text-align:center;font-weight:bold;width:100px" rowspan="2">PASIEN MASUK</td>
							<td class="border-trb" style="text-align:center;font-weight:bold;width:100px" rowspan="2">PASIEN KELUAR HIDUP</td>
							<td class="border-trb" style="text-align:center;font-weight:bold;width:100px" COLSPAN="2">PASIEN KELUAR MATI</td>
							<td class="border-trb" style="text-align:center;font-weight:bold;width:100px" rowspan="2">JUMLAH LAMA DIRAWAT</td>
							<td class="border-trb" style="text-align:center;font-weight:bold;width:100px" rowspan="2">PASIEN AKHIR TAHUN</td>
							<td class="border-trb" style="text-align:center;font-weight:bold;width:100px" rowspan="2">JUMLAH HARI PERAWATAN</td>
							<td class="border-trb" style="text-align:center;font-weight:bold" COLSPAN="6">PERINCIAN TEMPAT TIDUR PER-KELAS</td>
						</tr>
						<tr class="headTable">
							
							<td class="border-rb" style="text-align:center;font-weight:bold;width:50px">< 48 jam</td>
							<td class="border-rb" style="text-align:center;font-weight:bold;width:50px">VVIP</td>
							<td class="border-rb" style="text-align:center;font-weight:bold;width:50px">VVIP</td>
							<td class="border-rb" style="text-align:center;font-weight:bold;width:50px">VIP</td>
							<td class="border-rb" style="text-align:center;font-weight:bold;width:50px">I</td>
							<td class="border-rb" style="text-align:center;font-weight:bold;width:50px">II </td>
							<td class="border-rb" style="text-align:center;font-weight:bold;width:50px">III </td>
							<td class="border-rb" style="text-align:center;font-weight:bold;width:200px">Kelas Khusus </td>
						</tr>
						<tr class="headTable">
							<td class="border-rbl" style="background-color: #C0C0C0;text-align:center;font-weight:bold" >1</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">2</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">3</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">4</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">5</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">6</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">7</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">8</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">9</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">10</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">11</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">12</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">13</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">14</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">15</td>
							<td class="border-rb" style="background-color: #C0C0C0;text-align:center;font-weight:bold">16</td>
						</tr>
						<?php
							$no =0;
							foreach ($bagian as $key => $value) :
								foreach ($result as $keyy => $valuee) :
							$no++;
							 print_r($valuee); die;
							// $awal_tahun_icu = array_search('031001', array_column($result[$key], 'kode_bagian'));
							// // $awal_tahun_icu = array_search('031001', array_column($result, 'kode_bagian'));
							// $awal_tahun_bayi = array_search('031201', array_column($result[$key], 'kode_bagian'));
							?>

						<tr class="contentTable">
							<td class="border-rbl"><?php echo $no ?></td>
							<td class="border-rb"><?php echo $value->nama_bagian ?> &nbsp;</td>
							<td class="border-rb">&nbsp;</td>
							<td class="border-rb"><?php echo $valuee->jml_msk ?> &nbsp;</td>
							<td class="border-rb">&nbsp;</td>
							<td class="border-rb">&nbsp;</td>
							<td class="border-rb">&nbsp;</td>
							<td class="border-rb">&nbsp;</td>
							<td class="border-rb">&nbsp;</td>
							<td class="border-rb">&nbsp;</td>
							<td class="border-rb">&nbsp;</td>
							<td class="border-rb">&nbsp;</td>
							<td class="border-rb">&nbsp;</td>
							<td class="border-rb">&nbsp;</td>
							<td class="border-rb">&nbsp;</td>
							<td class="border-rb">&nbsp;</td>
						</tr>
					<?php endforeach; ?>
					<?php endforeach; ?>

					</tbody>	
		</table>
   
</body>
</html>