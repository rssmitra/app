<?php 
  $filename = 'Export_Data_CPPT';
    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment; filename=\"$filename\".pdf");
    header('Content-Length: ' . filesize($filename));
    readfile($filename);

?>
<h3>CATATAN PERKEMBANGAN PASIEN TERINTEGRASI</h3>
<table id="table-cppt" class="table table-bordered table-hover">
  <thead>
    <tr>  
      <th width="30px">No</th>
      <th width="70px">Tanggal/Jam/PPA</th>
      <th>SOAP</th>
      <th width="120px">Verifikasi DPJP</th>
      <th width="100px">Action</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>