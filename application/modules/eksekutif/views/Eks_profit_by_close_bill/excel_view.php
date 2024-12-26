<?php 

  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=".'trx_report_cutoff_'.date('YmdHis').".xls");  //File name extension was wrong
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false);

?>
<table class="table table-bordered table-hover">
  <thead>
  <tr>
    <th class="center">No</th>
    <th width="90px" class="center">Tipe</th>
    <th width="120px">Tgl Masuk</th>
    <th width="120px">Tgl Keluar</th>
    <th width="100px">No MR</th>
    <th>Nama Pasien</th>
    <th>Dokter</th>
    <th>Unit/Bagian/Spesialis</th>
    <th>Kategori</th>
    <th>Penjamin</th>
    <th>No SEP</th>
    <th width="100px">Jasa Dr1</th>
    <th width="100px">Jasa Dr2</th>
    <th width="100px">BHP</th>
    <th width="100px">Apotik</th>
    <th width="130px">Kamar Rawat</th>
    <th width="130px">Kamar Operasi</th>
    <th width="100px">Alkes</th>
    <th width="100px">Profit</th>
    <th width="100px">Total Billing</th>
    <th width="100px">Tarif Inacbgs</th>
    <th width="100px">Tarif RS NCC</th>
  </tr>
  </thead>
  <tbody>
    <?php 
      foreach($result as $key=>$row_list){
        echo "<tr>";
        for ($i=0; $i < 22; $i++) { 
          # code...
          echo "<td>".$row_list[$i]."</td>";
        }
        echo "</tr>";
      }?>
  </tbody>
</table>