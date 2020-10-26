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
    <div class="col-md-12">
      <left>
        <span style="font-size: 12px;"><strong><u>LOG PENGAMBILAN OBAT</u></strong><br>
        </span>
        <br>
      </left>
      <?php 
        $no_header = 0;
        foreach($log_mutasi as $key_log_mutasi=>$val_log_mutasi) : $no_header++;
          $dt_header = $log_mutasi[$key_log_mutasi][0];
          echo $no_header.'. PBLOG - '.$key_log_mutasi.' | '.$this->tanggal->formatDateTimeFormDmy($dt_header->created_date).' | '.$dt_header->created_by.' | <a href="#" onclick="PopupCenter('."'farmasi/Proses_resep_prb/nota_farmasi/".$value->kode_trans_far."?flag=".$flag."&kode_log_mutasi=".$key_log_mutasi."'".')"><i class="fa fa-print dark bigger-150"></i></a>';
          
      ?>
      <table class="table-utama" style="width: 60% !important;margin-top: 5px; margin-bottom: 10px">
            <?php 
              $no=0; 
              foreach ($val_log_mutasi as $key_vlm => $val_vlm) : $no++; 
            ?>

              <tr>
                <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
                <td style="border-collapse: collapse"><?php echo $val_vlm->nama_brg?></td>
                <td style="text-align:center; border-collapse: collapse"><?php echo number_format($val_vlm->jumlah_mutasi_obat);?></td>
                <td style="text-align: center; border-collapse: collapse"><?php echo $val_vlm->satuan_kecil; ?></td>
              </tr>

            <?php endforeach;?>

      </table>
      <?php endforeach;?>
    </div>
    
  <?php 
    else:
      echo '<span style="color: red">Belum diverifikasi !</span>';
    endif;
  ?>
</div>

