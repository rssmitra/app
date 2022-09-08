<div class="row" style="padding-left: 20px">
  <?php if( count($resep) > 0 ) :?>
    
    <div class="col-md-12">
      <left>
        <span style="font-size: 12px;"><strong><u>TRANSAKSI FARMASI</u></strong><br>
        No. PRB-<?php echo $value->kode_trans_far?> - <?php echo $value->no_resep?>
        </span>
      </left>
      <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
          <thead>
              <tr style="background-color: #c7cccb;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse; font-weight: bold">
                <td style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">No</td>
                <td style="border-bottom: 1px solid black; border-collapse: collapse">Nama Obat</td>
                <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah Tebus</td>
                <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
                <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Harga Satuan</td>
                <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Sub Total</td>
              </tr>
          </thead>
            <?php 
              $no=0; 
              foreach($resep as $key_dt=>$row_dt) : $no++; 
              $arr_total[] = $row_dt->sub_total;
            ?>

            <tr>
              <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
              <td style="border-collapse: collapse"><?php echo $row_dt->nama_brg?></td>
              <td style="text-align:center; border-collapse: collapse"><?php echo number_format($row_dt->jumlah);?></td>
              <td style="text-align: center; border-collapse: collapse"><?php echo $row_dt->satuan_kecil; ?></td>
              <td style="text-align:right; border-collapse: collapse"><?php echo number_format($row_dt->harga_satuan);?></td>
              <td style="text-align:right; border-collapse: collapse"><?php echo number_format((int)$row_dt->sub_total)?></td>
            </tr>

            <?php endforeach;?>

                <tr>
                  <td colspan="5" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse"><b>Total</b> </td>
                  <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse; font-weight: bold"><?php echo number_format(array_sum($arr_total))?></td>
                </tr>
                <tr>
                  <td colspan="7" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
                  <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_total)))?> Rupiah"</i></b>
                  </td>
                </tr>

      </table>
    </div>
    <hr>

    <div class="col-md-12" style="margin-top: 20px">
      <?php if( count($log_mutasi) > 0 ) :?>
      <left>
        <span style="font-size: 12px;"><strong><u>LOG PENGAMBILAN OBAT</u></strong><br>
        PBLOG-<?php echo $value->kode_trans_far?> - <?php echo $value->no_resep?>
        </span>
      </left>

      <table style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
      <thead>
          <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
            <th style="text-align:center; width: 5%; border-bottom: 1px solid black; border-collapse: collapse">No</th>
            <th style="border-bottom: 1px solid black; width: 40%; border-collapse: collapse">Nama Obat</th>
            <th style="text-align:center; width: 10%; border-bottom: 1px solid black; border-collapse: collapse">Jumlah</th>
            <th style="text-align:center; width: 10%; border-bottom: 1px solid black; border-collapse: collapse">Satuan</th>
            <th style="text-align:center; width: 15%; border-bottom: 1px solid black; border-collapse: collapse">Subtotal</th>
            <th style="text-align:center; width: 20%; border-bottom: 1px solid black; border-collapse: collapse">Log</th>
          </tr>
      </thead>
          <?php 
            $no=0; 
            $arr_total_log = [];
            foreach($log_mutasi as $key_dt=>$row_dt) : 
            $dt_header = $log_mutasi[$key_dt][0]; 
              foreach ($row_dt as $key_rd => $value_rd) :
                 $no++;  
            $sub_total = $value_rd->harga_satuan * $value_rd->jumlah_mutasi_obat;
            $nama_brg = ($value_rd->nama_brg == $value_rd->nama_brg_update) ? $value_rd->nama_brg : '<i><s>'.$value_rd->nama_brg.'</s></i> &nbsp; '.$value_rd->nama_brg_update.'';
            $arr_total_log[] = $sub_total;
          ?>

            <tr>
              <td style="text-align:center; width: 5%; border-collapse: collapse"><?php echo $no?>.</td>
              <td style="border-collapse: collapse; width: 40%; "><?php echo $nama_brg;?></td>
              <td style="text-align:center; width: 10%; border-collapse: collapse"><?php echo number_format($value_rd->jumlah_mutasi_obat);?></td>
              <td style="text-align:center; width: 10%; border-collapse: collapse"><?php echo $value_rd->satuan_kecil?></td>
              <td style="text-align:right; width: 15%; border-collapse: collapse"><?php echo number_format($sub_total)?></td>
              <td style="text-align: right"><?php echo $this->tanggal->formatDateTime($value_rd->created_date).' - '.$value_rd->created_by?></td>
            </tr>
            
          <?php endforeach; endforeach;?>

            <tr style="font-weight: bold">
              <td colspan="5" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Total </td>
              <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_total_log))?></td>
            </tr>
            <tr>
              <td colspan="6" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
              <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_total_log)))?> Rupiah"</i></b>
              </td>
            </tr>

    </table>
    <?php else: echo '<span style="color: red; font-weight: bold">Belum ada mutasi copy lunas</span>'; endif; ?>
    </div>
    
  <?php 
    else:
      echo '<span style="color: red">Belum diverifikasi !</span>';
    endif;
  ?>
</div>

