<?php 

  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=".'lhk_exp_date_type_1_'.date('Ymd').".xls");  //File name extension was wrong
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false);

?>
<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th class="center">No</th>
      <th>Tipe</th>
      <th>No. Kuitansi</th>
      <th>Tgl Submit</th>
      <th>No.MR</th>
      <th>Pasien</th>
      <th>Penjamin</th>
      <th>No. SEP</th>
      <th>Bagian Masuk</th>
      <th>Tunai</th>
      <th>Non-Tunai</th>
      <th>Potongan</th>
      <th>Perusahaan</th>
      <th>Karyawan</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $no = 0;
      foreach($data as $key=>$row_list){
        $no++;
        echo "<tr>";
        echo "<td>".$no."</td>";
        echo "<td>".$row_list->seri_kuitansi."</td>";
        echo "<td>".$row_list->no_kuitansi."</td>";
        echo "<td>".$this->tanggal->formatDateTime($row_list->tgl_jam)."</td>";
        echo "<td>".$row_list->no_mr."</td>";
        echo "<td>".$row_list->nama_pasien."</td>";
        echo "<td>".$row_list->nama_perusahaan."</td>";
        echo "<td>".$row_list->no_sep."</td>";
        echo "<td>".$row_list->nama_bagian."</td>";
        $nontunai = (int)$row_list->debet + (int)$row_list->kredit;
        echo "<td>".(int)$row_list->tunai."</td>";
        echo "<td>".(int)$nontunai."</td>";
        echo "<td>".(int)$row_list->potongan."</td>";
        echo "<td>".(int)$row_list->piutang."</td>";
        echo "<td>".(int)$row_list->nk_karyawan."</td>";
        echo "<td>".(int)$row_list->billing."</td>";
        echo "</tr>";
      }?>
  </tbody>
</table>