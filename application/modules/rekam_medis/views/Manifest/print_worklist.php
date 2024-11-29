<html>
<head>
  <title>Print Worklist</title>
</head>
<body>
<style>
body{
  font-family: Arial, Helvetica, sans-serif;
  margin: 20px !important;
}
#patients {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#patients td, #patients th {
  border: 1px solid #ddd;
  padding: 8px;
}

/* #patients tr:nth-child(even){background-color: #f2f2f2;} */

/* #patients tr:hover {background-color: #ddd;} */

#patients th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}

@media print{ #barPrint{
		display:none;
	}
}

.btn_close{
  padding: 5px; background: red; color: white;
}

.btn_print{
  padding: 5px; background: blue; color: white;
}
</style>
<div id="barPrint" style="float: right">
  <button class="btn_close" onClick="window.close()">Tutup</button>
  <button class="btn_print" onClick="print()">Cetak</button>
</div>
<br>
<center><p><b>DAFTAR KUNJUNGAN PASIEN<br><?php echo strtoupper($dokter)?> <br> <?php echo strtoupper($poli)?><br>TANGGAL. <?php echo strtoupper($this->tanggal->formatDate($tgl_kunjungan))?></b></p></center>

<table id="patients">
  <tr>
    <th style="text-align: center !important">NO</th>
    <th>NAMA PASIEN</th>
    <th>STATUS</th>
  </tr>
<?php 
    foreach ($list as $key => $value) {
      # code...
      echo '<tr>';
      echo '<td align="center">'.$value->no_antrian.'</td>';
      echo '<td>'.$value->nama_pasien.'</td>';
      echo '<td align="center">'.strtoupper($value->flag_antrian).'</td>';
      echo '</tr>';
  }
?>
</table>
</body>
</html>

