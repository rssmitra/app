<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <h4><?php echo isset( $dt_detail_brg[0] ) ? $dt_detail_brg[0]->kode_permohonan : 'Tidak ada data' ;?></h4>
      <table class="table table-bordered table-hovered" style="font-size:11px">
        <tr>
          <th class="center" width="35px">No</th>
          <th  width="120px">Kode Barang</th>
          <th>Nama Barang</th>
          <th class="center" width="80px">Permohonan</th>
          <th class="center" width="80px">Disetujui</th>
          <th class="center" width="80px">PO</th>
          <th class="center" width="80px">Satuan Besar</th>
          <th class="center" width="80px">Rasio</th>
          <th class="center" width="80px">Keterangan</th>
        </tr>
        <?php 
          $no=0; 
          if( count($dt_detail_brg) > 0 ) :
          foreach($dt_detail_brg as $row_dt) : $no++
        ?>
          <tr>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt->kode_brg?></td>
            <td><?php echo $row_dt->nama_brg?></td>
            <td class="center"><?php echo number_format($row_dt->jml_besar, 2)?></td>
            <td class="center">
              <?php 
                $span_class = ( $row_dt->jml_acc_penyetuju == $row_dt->jml_besar_acc ) ? 'color: green' : 'color: red';
                echo '<span style="'.$span_class.'">'.number_format($row_dt->jml_besar_acc, 2).'</span>'?>
            </td>
            <td class="center">
              <?php 
                $label = ( $row_dt->jml_besar_acc == $row_dt->total_po ) ? 'badge-success' : 'badge-danger' ;
                $val_td = '<span class="badge '.$label.' "><a href="#" style="color: white" onclick="show_modal('."'purchasing/permintaan/Req_pembelian/log_brg?id=".$row_dt->id_tc_permohonan_det."&kode_brg=".$row_dt->kode_brg."&flag=".$flag."'".', '."'LOG DETAIL BARANG'".')">'.number_format($row_dt->total_po, 2).'</span>';
                $text = ($row_dt->total_po==0) ? '-' : $val_td ;
                echo $text;
              ?>
            </td>
            <td class="center"><?php echo $row_dt->satuan_besar?></td>
            <td class="center"><?php echo $row_dt->rasio?></td>
            <td class="center"><?php echo $row_dt->keterangan?></td>
          </tr>
          <?php endforeach; else: echo '<tr><td colspan="8">Tidak ada barang ditemukan</td></tr>'; endif; ?>
      </table>
    <!-- PAGE CONTENT ENDS -->

    <?php
      if( count($dt_detail_brg) > 0 ){
        $explode_str = explode('/',$dt_detail_brg[0]->ket_acc);
        if(is_array($explode_str)){
          echo '<pre style="font-size: 11px">';
          echo 'Riwayat Persetujuan: <br>';
          foreach($explode_str as $row_str){
            echo $row_str.'</br>';
          }
          echo '</pre>';
        }
      }
      
    ?>
  </div><!-- /.col -->
</div><!-- /.row -->


