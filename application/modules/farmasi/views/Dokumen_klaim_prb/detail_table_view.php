<div class="row" style="padding-left: 20px">
  <?php if( count($resep) > 0 ) :?>
    
    <div class="col-md-7">
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

    <div class="col-md-5">
      <span style="font-size: 12px;"><strong><u>LOG DOKUMEN KLAIM</u></strong><br>
      No. SEP <?php echo $value->no_sep?>
      </span>
      <br>
      <div class="no-padding" style="margin-left: -10px">
          <?php 
            echo '<ol>';
            $not_merge = array();
            foreach($dokumen as $row_dok) : 
              if ($row_dok->dok_prb_file_type == null) {
                $not_merge[] = 1;
              }
          ?>
            <li>
              <a href="<?php echo base_url().$row_dok->dok_prb_fullpath; ?>" target="_blank" >
                <?php echo strtolower($row_dok->dok_prb_file_type); ?>
              </a>
            </li>
          <?php 
            endforeach; 
            echo (count($not_merge) == 0) ? '<li><a href="'.base_url().$path_dok_klaim.'" target="_blank" >File E-Klaim</a></li>' : '' ;
            echo '</ol>';
            echo (count($not_merge) > 0) ? 'Terdpat '.count($not_merge).' files yang belum dimerge' : '' ;
          ?>
      </div>

    </div>
    
  <?php 
    else:
      echo '<span style="color: red">Belum diverifikasi !</span>';
    endif;
  ?>
</div>

