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

	
	
<table class="table" border="0">
	<tbody>
		
		<tr>
			<td><b>Tahun</td>
			<td><b>BOR</td>
			<td><b>ALOS</td>
			<td><b>BTO</td>
			<td><b>TOI</td>
			<td><b>NDR</td>
			<td><b>GDR</td>
		</tr>
		
		<?php foreach ($result as $key => $value) : ?>
			<tr class="contentTable">
				<td class="border-rbl">Tahun <?php echo $key?></td>
				<?php 
					$key_bor = array_search('BOR', array_column($result[$key], 'nama_lap'));
					$key_alos = array_search('ALOS', array_column($result[$key], 'nama_lap'));
					$key_bto = array_search('BTO', array_column($result[$key], 'nama_lap'));
					$key_toi = array_search('TOI', array_column($result[$key], 'nama_lap'));
					$key_ndr = array_search('NDR', array_column($result[$key], 'nama_lap'));
					$key_gdr = array_search('GDR', array_column($result[$key], 'nama_lap'));
						
				?>
				<td class="border-rb">
					<?php echo isset($value[$key_bor]) ? ($value[$key_bor]['nama_lap'] == 'BOR') ? $value[$key_bor]['total'] : 0 : 0 ?>
				</td>
				<td class="border-rb">
					<?php echo isset($value[$key_alos]) ? ($value[$key_alos]['nama_lap'] == 'ALOS') ? $value[$key_alos]['total'] : 0 : 0 ?>
				</td>
				<td class="border-rb">
					<?php echo isset($value[$key_bto]) ? ($value[$key_bto]['nama_lap'] == 'BTO') ? $value[$key_bto]['total'] : 0 : 0 ?>
				</td>
				<td class="border-rb">
					<?php echo isset($value[$key_toi]) ? ($value[$key_toi]['nama_lap'] == 'TOI') ? $value[$key_toi]['total'] : 0 : 0 ?>
				</td>
				<td class="border-rb">
					<?php echo isset($value[$key_ndr]) ? ($value[$key_ndr]['nama_lap'] == 'TOI') ? $value[$key_ndr]['total'] : 0 : 0 ?>
				</td>
				<td class="border-rb">
					<?php echo isset($value[$key_gdr]) ? ($value[$key_gdr]['nama_lap'] == 'TOI') ? $value[$key_gdr]['total'] : 0 : 0 ?>
				</td>


			</tr>
		<?php endforeach;?>

	</tbody>

</table>
   
</body>
</html>