<?php 
  $filename = 'Export_Data_Tarif';
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$filename.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);

?>
<h3>MASTER DATA TARIF</h3>
<table id="dynamic-table" base-url="" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>  
      <th rowspan="2">No</th>      
      <th rowspan="2">Kode Tarif</th>      
      <th rowspan="2">Nama Tarif</th>      
      <th rowspan="2">Unit/Bagian</th>      
      <?php foreach($klas as $row_klas) :?>      
      <th width="100px" colspan="4"><?php echo $row_klas->nama_klas; ?></th>         
      <?php endforeach; ?>              
    </tr>

    <tr>       
      <?php foreach($klas as $row_klas) :?>      
      <th width="100px">Bill RS</th>         
      <th width="100px">Bill Dr1</th>         
      <th width="100px">Bill Dr2</th>         
      <th width="100px">Total</th>         
      <?php endforeach; ?>              
    </tr>

    </thead>
    <tbody>

    <?php $no=0; foreach ($data as $key => $value) : $no++; ?>
      <tr>
        <td><?php echo $no?></td>
        <td><?php echo $key?></td>
        <td><?php echo $value['nama_tarif']?></td>
        <td><?php echo $value['nama_bagian']?></td>
        <?php
          foreach ($klas as $key_klas => $row_klas) {
              # code...
              $total = isset($value['klas'][$row_klas->kode_klas]) ? $value['klas'][$row_klas->kode_klas]->total : 0;
              $bill_rs = isset($value['klas'][$row_klas->kode_klas]) ? $value['klas'][$row_klas->kode_klas]->bill_rs : 0;
              $bill_dr1 = isset($value['klas'][$row_klas->kode_klas]) ? $value['klas'][$row_klas->kode_klas]->bill_dr1 : 0;
              $bill_dr2 = isset($value['klas'][$row_klas->kode_klas]) ? $value['klas'][$row_klas->kode_klas]->bill_dr2 : 0;

              echo '<td align="right">'.$bill_rs.'</td>';
              echo '<td align="right">'.$bill_dr1.'</td>';
              echo '<td align="right">'.$bill_dr2.'</td>';
              echo '<td align="right">'.$total.'</td>';
          }
        ?>
      </tr>
    <?php endforeach; ?>
    </tbody>
</table>