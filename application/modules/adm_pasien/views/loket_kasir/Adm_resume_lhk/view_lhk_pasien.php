<style type="text/css">
  
    #static-table {
      display: block;
      overflow-x: auto;
      white-space: nowrap;
  }

</style>
<h3 class="center">DATA PEMBAYARAN PASIEN <?php echo strtoupper($method)?></h3>
<div class="row">
  
  <div class="col-sm-12">
    <button class="btn btn-success btn-xs">
      <i class="ace-icon fa fa-file-excel-o bigger-160"></i>
      Export Excel
    </button><br>
    <span style="font-size: 15px;">Rekapitulasi Pendapatan Kasir Berdasarkan Pasien </span><br>
    Tanggal <?php echo $this->tanggal->formatDate($date)?>
    <table id="static-table" class="table" style="width: 100%;" border="0">
      <thead>
        <tr >
          <th width="50px" class="center">No</th>
          <th>Nama Pasien</th>
          <th>Tanggal</th>
          <?php 
            if(count($column) > 0 ){
              foreach($column as $key_col=>$col){
                echo '<th class="center">'.ucwords(strtolower($col)).'</th>';
              }
            }else{
              echo '<th class="center">Total</th>';
            }
          ?>
          <th><?php echo ($method=='bill')?'Billing':ucfirst($method)?></th>
          <th>Piutang</th>
          <th>Potongan</th>
          <th>Total Billing</th>
        </tr>
      </thead>
      <tbody>
      <?php 
        $no = 0; 
        foreach($rowData as $key_row=>$row) : $no++; 
          $colspan = count($column) + 1;
          echo '<tr>';
          echo '<td align="center" >'.$no.'</td>';
          echo '<td align="left">'.$row['no_mr'].' - '.ucwords(strtolower($row['nama_pasien'])).'</td>';
          echo '<td align="left">'.$this->tanggal->formatDateTime($row['tgl_jam']).'</td>';
          foreach($column as $key_col=>$col){
              // search data
            // 
              $tunai[$key_row][$key_col] = $this->Adm_resume_lhk->sumByResumeBill($row['detail_bill'], array( array('field' => $key_col)  ), 'subtotal' );
              $arr_tunai[$key_col][] = $tunai[$key_row][$key_col];
              echo '<td align="right" width="100px">'.number_format($tunai[$key_row][$key_col]).'</td>';
            }
          
          $arr_potongan[] = $row['potongan'];
          $total_bill = $row['bill'] + $row['potongan'];
          $arr_bill[] = $total_bill;
          $arr_method[] = $row[$method];
          $piutang = $row['nk_karyawan'] + $row['nk_perusahaan'];
          $arr_piutang[] = $piutang;
          // cek kesesuaian
          $total_all = $piutang + $row['potongan'] + $row[$method];
          $color_style = ($total_bill == $total_all) ? '': '#ff0d015c';
          echo '<td align="right" width="100px" style="background-color: '.$color_style.'">'.number_format($row[$method]).'</td>';
          echo '<td align="right" width="100px" style="background-color: '.$color_style.'">'.number_format($piutang).'</td>';
          echo '<td align="right" width="100px" style="background-color: '.$color_style.'">'.number_format($row['potongan']).'</td>';
          echo '<td align="right" width="100px" style="background-color: '.$color_style.'">'.number_format($total_bill).'</td>';
          echo '<tr>';
        endforeach;
      ?>
      <tr style="font-weight: bold">
        <td colspan="<?php echo (count($column) > 0) ? 3 : 3?>" align="left">Sub Total</td>
        <?php 
            foreach($column as $key_col=>$col){
              $arr_total[] = array_sum($arr_tunai[$key_col]);
              echo '<td align="right">'.number_format(array_sum($arr_tunai[$key_col])).'</td>';
            }
            echo '<td align="right" width="100px" style="font-size: 14px">'.number_format(array_sum($arr_method)).'</td>';
            echo '<td align="right" width="100px">'.number_format(array_sum($arr_piutang)).'</td>';
            echo '<td align="right" width="100px">'.number_format(array_sum($arr_potongan)).'</td>';
            echo '<td align="right" width="100px">'.number_format(array_sum($arr_bill)).'</td>';
        ?>
      </tr>
      <tr style="font-weight: bold">
        <td colspan="3" align="left">Total Pemasukan Kasir</td>
        <td colspan="<?php echo (count($column) > 0) ? count($column) : 0?>" align="right"><?php echo isset($arr_total) ? number_format(array_sum($arr_total)) : 0;?></td>
        <td colspan="3" align="right">Total Pemasukan Kasir</td>
        <td colspan="<?php echo (count($column) > 0) ? count($column) : 0?>" align="right"><?php echo isset($arr_total) ? number_format(array_sum($arr_bill)) : 0;?></td>
      </tr>
      </tbody>
    </table>
  </div>

</div>