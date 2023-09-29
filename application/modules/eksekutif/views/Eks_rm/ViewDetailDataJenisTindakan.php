<div class="row">
  <div class="col-md-12">
    <center style="font-weight: bold">REKAPITULASI TRANSAKSI BERDASARKAN JENIS TINDAKAN <br> <?php echo $value['title']; ?></center>
    <div style="height:550px; overflow:auto;">
      <table class="table">
        <tr style="background: chartreuse">
          <th class="center">No</th>
          <th>Jenis Tindakan</th>
          <th>Jasa Dokter 1</th>
          <th>Jasa Dokter 2</th>
          <th>Kamar Tindakan</th>
          <th>BHP</th>
          <th>Obat</th>
          <th>Alkes</th>
          <th>Alat RS</th>
          <th>Administrasi</th>
          <th>Pendapatan RS</th>
          <th>Total (Rp.)</th>
        </tr>
        <?php 
          $no = 0;
          foreach($value['result'] as $ky3=>$row_k3) :
            $arr_bill_dr1[] = $row_k3->bill_dr1;
            $arr_bill_dr2[] = $row_k3->bill_dr2;
            $arr_kamar_tindakan[] = $row_k3->kamar_tindakan;
            $arr_bhp[] = $row_k3->bhp;
            $arr_obat[] = $row_k3->obat;
            $arr_alkes[] = $row_k3->alkes;
            $arr_alat_rs[] = $row_k3->alat_rs;
            $arr_adm[] = (in_array($row_k3->jenis_tindakan, array(2))) ? ($row_k3->bill_rs) : ($row_k3->adm);
            $arr_pendapatan_rs[] = (in_array($row_k3->jenis_tindakan, array(2))) ? ($row_k3->bill_rs) : ($row_k3->pendapatan_rs);
            $arr_total[] = $row_k3->total;
            $no++;
        ?>
        <tr>
          <td align="center"><?php echo ucwords($no)?></td>
          <td><?php echo ucwords($row_k3->nama_jenis_tindakan)?></td>
          <td align="right"><?php echo number_format($row_k3->bill_dr1)?></td>
          <td align="right"><?php echo number_format($row_k3->bill_dr2)?></td>
          <td align="right"><?php echo number_format($row_k3->kamar_tindakan)?></td>
          <td align="right"><?php echo number_format($row_k3->bhp)?></td>
          <td align="right"><?php echo number_format($row_k3->obat)?></td>
          <td align="right"><?php echo number_format($row_k3->alkes)?></td>
          <td align="right"><?php echo number_format($row_k3->alat_rs)?></td>
          <td align="right">
            <?php echo (in_array($row_k3->jenis_tindakan, array(2))) ? number_format($row_k3->bill_rs) : number_format($row_k3->adm)?>
          </td>
          <td align="right">
            <?php echo (in_array($row_k3->jenis_tindakan, array(1,9,11))) ? number_format($row_k3->bill_rs) : number_format($row_k3->pendapatan_rs)?>
          </td>
          <td align="right" style="font-weight: bold"><?php echo number_format($row_k3->total)?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <td align="right" colspan="2"><b>Total</b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_bill_dr1))?></b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_bill_dr2))?></b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_kamar_tindakan))?></b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_bhp))?></b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_obat))?></b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_alkes))?></b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_alat_rs))?></b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_adm))?></b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_pendapatan_rs))?></b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_total))?></b></td>
        </tr>
      </table>
    </div>
  </div>
  <div class="col-md-6">
    <div id="show_detail_level_2"></div>
  </div>
</div>