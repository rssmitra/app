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
	
<table border="0" cellpadding="0" cellspacing="0">
	<tbody>
		
		<tr class="headTable">
							<TD>Tahun</TD>
							<TD>BOR</TD>
							<TD>LOS</TD>
							<TD >BTO</TD>
							<TD >TOI</TD>
							<TD >NDR</TD>
							<TD >GDR</TD>
							<TD >Rata - rata <br> Kunjungan/Hari </TD>
						</TR>
						<TR class="headTable">
							<TD >1</TD>
							<TD >2</TD>
							<TD >3</TD>
							<TD >4</TD>
							<TD >5</TD>
							<TD >6</TD>
							<TD >7</TD>
							<TD >8</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">Tahun I</TD>
							<TD class="border-rb"><?php echo $result->total3 ?></TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">Tahun II</TD>
							<TD class="border-rb"><?php echo $result2->total3 ?></TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
						<TR class="contentTable">
							<TD class="border-rbl">Tahun III</TD>
							<TD class="border-rb"><?php echo $result3->total3 ?></TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">>&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
							<TD class="border-rb">&nbsp;</TD>
						</TR>
	</tbody>

</table>
   
</body>
</html>