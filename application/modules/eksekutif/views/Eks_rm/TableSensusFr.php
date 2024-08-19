<div class="row">
            
    <div class="col-md-12" style="padding-top: 10px">
      <table class="table">
        <tr style="background: #e9e6e6">
          <th rowspan="3" style="vertical-align: middle !important" class="center" width="50px">No</th>
          <th rowspan="3" style="vertical-align: middle !important">Jenis Resep</th>
          <th class="center" colspan="6">Penjamin Pasien</th>
          <th class="center" colspan="2" style="vertical-align: middle !important; width: 100px">Jenis Resep</th>
          <th class="center" rowspan="3" style="vertical-align: middle !important; width: 100px">Total<br>Resep</th>
          <th class="center" rowspan="3" style="vertical-align: middle !important; width: 100px">(%)</th>
          <th class="center" rowspan="3" style="vertical-align: middle !important; max-width: 120px">Chart</th>
        </tr>
        <tr style="background: #e9e6e6">
          <th class="center" style="width: 160px" colspan="2">BPJS</th>
          <th class="center" style="width: 160px" colspan="2">Umum</th>
          <th class="center" style="width: 160px" colspan="2">Asuransi</th>
          <th class="center" rowspan="2" style="width: 80px">Racikan</th>
          <th class="center" rowspan="2" style="width: 80px">Non Racikan</th>
        </tr>
        <tr style="background: #e9e6e6">
          <th class="center" style="width: 80px">Diantar</th>
          <th class="center" style="width: 80px">Ditunggu</th>
          <th class="center" style="width: 80px">Diantar</th>
          <th class="center" style="width: 80px">Ditunggu</th>
          <th class="center" style="width: 80px">Diantar</th>
          <th class="center" style="width: 80px">Ditunggu</th>
        </tr>

        <?php 
          $no=0; 
          foreach($resep as $key_resep => $val_resep) : $no++;
          $total_resep = isset($resep[$key_resep]) ? count($resep[$key_resep]) : 0;
          $arr_total_resep[] = $total_resep;
          
          // TOTAL RESEP RACIKAN
          $ttl_resep_racikan = isset($resep_racikan[trim($key_resep)]) ? count($resep_racikan[trim($key_resep)]) : 0;
          $arr_resep_racikan[] = $ttl_resep_racikan;
          $resep_non_racikan = $total_resep - $ttl_resep_racikan;
          $arr_resep_non_racikan[] = $resep_non_racikan;

          switch ($key_resep) {
            case 'RJ':
              $txt_resep = 'Resep Rawat Jalan';
              break;
              case 'RI':
                $txt_resep = 'Resep Rawat Inap';
                break;
                case 'RL':
                  $txt_resep = 'Resep Luar';
                  break;
                  case 'RK':
                    $txt_resep = 'Resep Karyawan';
                    break;
                    case 'PB':
                      $txt_resep = 'Pembelian Bebas';
                      break;
          }

          // BPJS
          $pasien_bpjs = isset($penjamin[$key_resep][120]) ? count($penjamin[$key_resep][120]) : 0;
          $pasien_bpjs_diantar = isset($resep_diantar[$key_resep][120][1]) ? count($resep_diantar[$key_resep][120][1]) : 0;
          $pasien_bpjs_ditunggu = isset($resep_diantar[$key_resep][120][0]) ? count($resep_diantar[$key_resep][120][0]) : 0;
          $arr_pasien_bpjs_diantar[] = $pasien_bpjs_diantar;
          $arr_pasien_bpjs_ditunggu[] = $pasien_bpjs_ditunggu;

          // UMUM
          $pasien_umum = isset($penjamin[$key_resep][0]) ? count($penjamin[$key_resep][0]) : 0;
          $pasien_umum_diantar = isset($resep_diantar[$key_resep][0][1]) ? count($resep_diantar[$key_resep][0][1]) : 0;
          $pasien_umum_ditunggu = isset($resep_diantar[$key_resep][0][0]) ? count($resep_diantar[$key_resep][0][0]) : 0;
          $arr_pasien_umum_diantar[] = $pasien_umum_diantar;
          $arr_pasien_umum_ditunggu[] = $pasien_umum_ditunggu;

          // ASURANSI
          $pasien_asuransi = isset($penjamin[$key_resep][1]) ? count($penjamin[$key_resep][1]) : 0;
          $pasien_asuransi_diantar = isset($resep_diantar[$key_resep][1][1]) ? count($resep_diantar[$key_resep][1][1]) : 0;
          $pasien_asuransi_ditunggu = isset($resep_diantar[$key_resep][1][0]) ? count($resep_diantar[$key_resep][1][0]) : 0;
          $arr_pasien_asuransi_diantar[] = $pasien_asuransi_diantar;
          $arr_pasien_asuransi_ditunggu[] = $pasien_asuransi_ditunggu;

          
          // PERSENTASE
          $percent = ($total_resep / $total) * 100;
          $arr_percent[] = $percent;
        ?>
          <tr>
            <td align="center"><?php echo $no; ?></td>
            <td><?php echo strtoupper($txt_resep);?></td>
            <td align="center"><?php echo number_format($pasien_bpjs_diantar);?></td>
            <td align="center"><?php echo number_format($pasien_bpjs_ditunggu);?></td>
            <td align="center"><?php echo number_format($pasien_umum_diantar);?></td>
            <td align="center"><?php echo number_format($pasien_umum_ditunggu);?></td>
            <td align="center"><?php echo number_format($pasien_asuransi_diantar);?></td>
            <td align="center"><?php echo number_format($pasien_asuransi_ditunggu);?></td>
            <td align="center"><?php echo number_format($ttl_resep_racikan);?></td>
            <td align="center"><?php echo number_format($resep_non_racikan);?></td>
            <td align="center"><?php echo number_format($total_resep);?></td>
            <td align="center"><?php echo number_format($percent, 2);?></td>
            <td align="center">
              <div class="progress progress-striped active" style="margin-bottom: 0px !important">
                <div class="progress-bar progress-bar-green" style="width: <?php echo $percent * 3;?>px"></div>
              </div>
            </td>
          </tr>
        <?php endforeach; 
          
        ?>

        <tr style="font-weight: bold">
          <td colspan="2" align="right">TOTAL</td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_bpjs_diantar))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_bpjs_ditunggu))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_umum_diantar))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_umum_ditunggu))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_asuransi_diantar))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_asuransi_ditunggu))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_resep_racikan))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_resep_non_racikan))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_total_resep))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_percent), 2)?></td>
        </tr>

        <tr style="font-weight: bold">
          <td colspan="2" align="right">RATA-RATA JUMLAH RESEP</td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_bpjs_diantar) / count($resep))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_bpjs_ditunggu) / count($resep))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_umum_diantar) / count($resep))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_umum_ditunggu) / count($resep))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_asuransi_diantar) / count($resep))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_asuransi_ditunggu) / count($resep))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_total_resep) / count($resep), 2)?></td>
          <td align="center"><?php echo number_format(array_sum($arr_total_resep) / count($resep), 2)?></td>
          <td align="center"><?php echo number_format(array_sum($arr_total_resep) / count($resep), 2)?></td>
          <td align="center"><?php echo number_format(array_sum($arr_percent) / count($arr_percent), 2)?></td>
        </tr>
      </table>
    </div>
    <!-- asal bagian -->
    <div class="col-md-12" style="padding-top: 10px">
      <table class="table">
        <tr style="background: #e9e6e6">
          <th style="vertical-align: middle !important" class="center" width="50px">No</th>
          <th style="vertical-align: middle !important">Nama Unit/Bagian Asal Resep</th>
          <th class="center" colspan="2" style="vertical-align: middle !important; width: 100px">Jenis Resep</th>
          <th class="center" style="vertical-align: middle !important; width: 100px">Total<br>Resep</th>
        </tr>
        <tr style="background: #e9e6e6">
          <th class="center" style="width: 80px">Racikan</th>
          <th class="center" style="width: 80px">Non Racikan</th>
        </tr>

        <?php 
          $no=0; 
          foreach($bagian_asal as $key_bagian_asal => $val_bagian_asal) : $no++;
          // total resep
          $total_resep_bagian_asal = $val_bagian_asal['total_resep'];
          $arr_total_resep_bagian_asal[] = $total_resep_bagian_asal;
          // non racikan
          $total_resp_nonracikan = $total_resep_bagian_asal - $val_bagian_asal['total_racikan'];
          $arr_total_resp_nonracikan[] = $$total_resp_nonracikan;
          // racikan
          $total_resp_racikan = $val_bagian_asal['total_racikan'];
          $arr_total_resp_racikan []= $total_resp_racikan;
        ?>
          <tr>
            <td align="center"><?php echo $no; ?></td>
            <td><?php echo strtoupper($key_bagian_asal);?></td>
            <td align="center"><?php echo number_format($total_resp_racikan);?></td>
            <td align="center"><?php echo number_format($total_resp_nonracikan);?></td>
            <td align="center"><?php echo number_format($total_resep_bagian_asal);?></td>
          </tr>
        <?php endforeach; 
          
        ?>

        <tr style="font-weight: bold">
          <td colspan="2" align="right">TOTAL</td>
          <td align="center"><?php echo number_format(array_sum($arr_total_resp_racikan))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_total_resp_nonracikan))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_total_resep_bagian_asal))?></td>
        </tr>

      </table>
    </div>

</div>