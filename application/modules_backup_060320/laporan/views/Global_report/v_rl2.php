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
					<div class="judul1">Formulir RL2<BR>KETENAGAAN</div>
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
		
		<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" >
			<TR >
				<TD  rowspan="2" colspan="1" style="vertical-align: middle;font-size: 9pt;text-align: center;font-weight: bold;font-family: arial;padding:5px;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 2px solid #000000;border-bottom: 2px solid #000000;" >NO. KODE</TD>
				<TD rowspan="2" colspan="1" style="vertical-align: middle;font-size: 9pt;text-align: center;font-weight: bold;font-family: arial;padding:5px;border-right: 1px solid #000000;border-top: 2px solid #000000;border-bottom: 2px solid #000000;" >KUALIFIKASI PENDIDIKAN</TD>
				<TD rowspan="1" colspan="2" style="vertical-align: middle;font-size: 9pt;text-align: center;font-weight: bold;font-family: arial;padding:5px;border-right: 1px solid #000000;border-top: 2px solid #000000;border-bottom: 2px solid #000000;">KEADAAN</TD>
				<TD rowspan="1" colspan="2" style="vertical-align: middle;font-size: 9pt;text-align: center;font-weight: bold;font-family: arial;padding:5px;border-right: 1px solid #000000;border-top: 2px solid #000000;border-bottom: 2px solid #000000;">KEBUTUHAN</TD>
				<TD rowspan="1" colspan="2" style="vertical-align: middle;font-size: 9pt;text-align: center;font-weight: bold;font-family: arial;padding:5px;border-right: 1px solid #000000;border-top: 2px solid #000000;border-bottom: 2px solid #000000;">KEKURANGAN</TD>
			</TR>
			<tr>
				<TD style="vertical-align: middle;font-size: 9pt;text-align: center;font-weight: bold;font-family: arial;padding:5px;border-right: 1px solid #000000;border-bottom: 2px solid #000000;">Laki-laki</TD>
				<TD style="vertical-align: middle;font-size: 9pt;text-align: center;font-weight: bold;font-family: arial;padding:5px;border-right: 1px solid #000000;border-bottom: 2px solid #000000;">Perempuan</TD>
				<TD style="vertical-align: middle;font-size: 9pt;text-align: center;font-weight: bold;font-family: arial;padding:5px;border-right: 1px solid #000000;border-bottom: 2px solid #000000;">Laki-laki</TD>
				<TD style="vertical-align: middle;font-size: 9pt;text-align: center;font-weight: bold;font-family: arial;padding:5px;border-right: 1px solid #000000;border-bottom: 2px solid #000000;">Perempuan</TD>
				<TD style="vertical-align: middle;font-size: 9pt;text-align: center;font-weight: bold;font-family: arial;padding:5px;border-right: 1px solid #000000;border-bottom: 2px solid #000000;">Laki-laki</TD>
				<TD style="vertical-align: middle;font-size: 9pt;text-align: center;font-weight: bold;font-family: arial;padding:5px;border-right: 1px solid #000000;border-bottom: 2px solid #000000;">Perempuan</TD>
			<tr>
		
		<?php $no = 0; 
          foreach($result as $row_data => $value):
    //       	foreach ($resultt as $keyy => $valuee) :

    //       		$keadaan_pria = $tampil["keadaan_pria"];
				// $keadaan_wanita = $tampil["keadaan_wanita"];
				// $kebutuhan_pria = $tampil["kebutuhan_pria"];
				// $kebutuhan_wanita = $tampil["kebutuhan_wanita"];
				// $kekurangan_pria = $tampil["kekurangan_pria"];
				// $kekurangan_wanita = $tampil["kekurangan_wanita"];
          ?>
           <tr>
				<td style="border-left: 1px solid #000000;border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding: 2px 2px 2px 2px;font-family: arial;font-size: 8pt;vertical-align: top;"><?php echo $value->kode_rl2 ?>&nbsp;</td>
				<?php 
				if($value -> level ==3){
					?>
				<td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding: 2px 2px 2px 2px;font-family: arial;font-size: 8pt;vertical-align: top;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value->nama_rl2 ?>&nbsp;</td>
				<td align="right" style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding: 2px 2px 2px 2px;font-family: arial;font-size: 8pt;vertical-align: top;"></td>
				<td align="right" style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding: 2px 2px 2px 2px;font-family: arial;font-size: 8pt;vertical-align: top;"></td>
				<td align="right" style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding: 2px 2px 2px 2px;font-family: arial;font-size: 8pt;vertical-align: top;">&nbsp;</td>
				<td align="right" style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding: 2px 2px 2px 2px;font-family: arial;font-size: 8pt;vertical-align: top;">&nbsp;</td>
				<td align="right" style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding: 2px 2px 2px 2px;font-family: arial;font-size: 8pt;vertical-align: top;">&nbsp;</td>
				<td align="right" style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding: 2px 2px 2px 2px;font-family: arial;font-size: 8pt;vertical-align: top;">&nbsp;</td>
				<?php 
				}
				else if($value->level==2){
					?>
				<td align="left" style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding: 2px 2px 2px 2px;font-family: arial;font-size: 8pt;vertical-align: top;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value->nama_rl2 ?>&nbsp;</td>
				<td colspan="7" align="left" style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding: 2px 2px 2px 2px;font-family: arial;font-size: 8pt;vertical-align: top;">&nbsp;</td>
				<?php
				}
				else
				{
					?>
				<td align="left" style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding: 2px 2px 2px 2px;font-family: arial;font-size: 8pt;vertical-align: top;"><?php echo $value->nama_rl2 ?>&nbsp;</td>
				<td colspan="7" align="left" style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;padding: 2px 2px 2px 2px;font-family: arial;font-size: 8pt;vertical-align: top;">&nbsp;</td>
				<?php
				}
				?>
				
				</td>
			</tr>
          <?php 
         endforeach; 
      
      ?>
       
	</tbody>

</table>
   
</body>
</html>