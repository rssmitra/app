<div class="row">
  <div class="col-md-12">

    <div class="tabbable">
      <ul class="nav nav-tabs" id="myTab">
        <li class="active">
          <a data-toggle="tab" href="#rekap1">
            Asal Pasien Rawat Inap
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#rekap2">
            Dokter Perawat Pasien Rawat Inap
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#rekap3">
            Diagnosa Penyakit Pasien Rawat Inap
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#rekap4">
            Dokter Perujuk Pasien Rawat Inap
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#rekap5">
            Jaminan Perusahaan
          </a>
        </li>
      </ul>

      <div class="tab-content">
        <div id="rekap1" class="tab-pane fade in active">
          
          <div>
            <p style="font-weight: bold">Rekap Pasien Rawat Inap Berdasarkan Asal Unit nya</p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th rowspan="2" class="center">No</th>
                <th rowspan="2" style="width: 350px">Unit Perujuk</th>
                <th class="center" colspan="4">Kategori Pasien</th>
                <th class="center" rowspan="2">Jumlah</th>
                <th class="center" rowspan="2">%</th>
                <th class="center" rowspan="2">Chart</th>
              </tr>
              <tr style="background: #e9e6e6">
                <th class="center" style="width: 120px">Lama</th>
                <th class="center" style="width: 120px">Baru</th>
                <th class="center" style="width: 120px">BPJS</th>
                <th class="center" style="width: 120px">Non BPJS</th>
              </tr>

              <?php 
                $no=0; 
                foreach($bagian_asal as $key_bagian_asal => $val_bagian_asal) : $no++;
                $total_bagian_asal = isset($bagian_asal[$key_bagian_asal]) ? count($bagian_asal[$key_bagian_asal]) : 0;
                $arr_total_bagian_asal[] = $total_bagian_asal;
                $pasien_lama = isset($status_pasien[$key_bagian_asal]['lama']) ? count($status_pasien[$key_bagian_asal]['lama']) : 0;
                $arr_pasien_lama[] = $pasien_lama;
                $pasien_baru = isset($status_pasien[$key_bagian_asal]['baru']) ? count($status_pasien[$key_bagian_asal]['baru']) : 0;
                $arr_pasien_baru[] = $pasien_baru;
                $pasien_bpjs = isset($penjamin[$key_bagian_asal][120]) ? count($penjamin[$key_bagian_asal][120]) : 0;
                $arr_pasien_bpjs[] = $pasien_bpjs;
                $pasien_nonbpjs = $total_bagian_asal - $pasien_bpjs;
                $arr_pasien_nonbpjs[] = $pasien_nonbpjs;
                $percent = ($total_bagian_asal / $total) * 100;
                $arr_percent[] = $percent;
              ?>
                <tr>
                  <td align="center"><?php echo $no; ?></td>
                  <td><?php echo strtoupper($key_bagian_asal);?></td>
                  <td align="center"><?php echo $pasien_lama;?></td>
                  <td align="center"><?php echo $pasien_baru;?></td>
                  <td align="center"><?php echo $pasien_bpjs;?></td>
                  <td align="center"><?php echo $pasien_nonbpjs;?></td>
                  <td align="center"><?php echo $total_bagian_asal;?></td>
                  <td align="center"><?php echo number_format($percent, 2);?></td>
                  <td align="center">
                    <div class="progress progress-striped active" style="margin-bottom: 0px !important">
                      <div class="progress-bar progress-bar-green" style="width: <?php echo $percent * 10;?>px"></div>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
              <tr style="font-weight: bold">
                <td colspan="2" align="right">TOTAL</td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_lama))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_baru))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_bpjs))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_nonbpjs))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_total_bagian_asal))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_percent))?></td>
              </tr>

              <tr style="font-weight: bold">
                <td colspan="2" align="right">RATA-RATA</td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_lama) / count($bagian_asal))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_baru) / count($bagian_asal))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_bpjs) / count($bagian_asal))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_nonbpjs) / count($bagian_asal))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_total_bagian_asal) / count($bagian_asal))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_percent) / count($bagian_asal))?></td>
              </tr>
            </table>
          </div>
          
        </div>

        <div id="rekap2" class="tab-pane fade">
          
          <div>
            <p style="font-weight: bold">Rekap Dokter Yang Merawat Pasien Rawat Inap</p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th class="center">No</th>
                <th>Dokter yang merawat </th>
                <th class="center">Jumlah</th>
                <th class="center">%</th>
                <th class="center">Chart</th>
              </tr>

              <?php 
                $nox=0; 
                $nob = 1;
                foreach($dokter as $key_dokter => $val_dokter) : 
                  $nox++;
                  $countrow     = isset($dokter[$key_dokter]) ? count($dokter[$key_dokter]) : 0;
                  $arr_countrow[] = isset($dokter[$key_dokter]) ? count($dokter[$key_dokter]) : 0;
                  $percent = ($countrow / $total) * 100;
                  $arr_percent[] = $percent;
                  $chart = $percent * 10;
                  echo "<tr>";
                  echo "<td align='center'>".$nox."</td>";
                  echo "<td>".strtoupper($key_dokter)."</td>";
                  echo "<td align='center'>".$countrow."</td>";
                  echo "<td align='center'>".number_format($percent,2)."</td>";
                  echo '<td><div class="progress progress-striped active" style="margin-bottom: 0px !important">
                  <div class="progress-bar progress-bar-red" style="width: '.$chart.'px"></div>
                </div></td>';
                  echo "</tr>";
              ?>
              <?php endforeach; ?>
              <tr style="font-weight: bold">
                <td colspan="2" align="right">TOTAL</td>
                <td align="center" colspan="2"><?php echo number_format(array_sum($arr_countrow))?></td>
              </tr>
            </table>
          </div>

        </div>

        <div id="rekap3" class="tab-pane fade">
          
          <div>
          <p style="font-weight: bold">Rekap Diagnosa Penyakit Pasien Rawat Inap Terbanyak</p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th class="center">No</th>
                <th>Diagnosa/Penyakit</th>
                <th class="center" width="50px">Jumlah</th>
              </tr>

              <?php 
                $noc = 0;
                // echo "<pre>"; print_r($diagnosa);die;
                foreach($diagnosa as $key_diagnosa => $val_diagnosa) : 
                  $noc++;
                  $arr_total[] = $val_diagnosa->total;
                  $nama_diagnosa = ($val_diagnosa->diagnosa == null)? 'Diagnosa Lainnya' : $val_diagnosa->diagnosa;
              ?>
                <tr>
                  <td align="center" width="30px"><?php echo $noc; ?></td>
                  <td><?php echo strtoupper($nama_diagnosa);?></td>
                  <td align="center"><?php echo number_format($val_diagnosa->total);?></td>
                </tr>
              <?php 
                endforeach; 
              ?>
              <tr style="font-weight: bold">
                <td align="right" colspan="2">TOTAL</td>
                <td align="center"><?php echo number_format(array_sum($arr_total));?></td>
              </tr>
            </table>
          </div>

        </div>

        <div id="rekap4" class="tab-pane fade">
          
          <div>
          <p style="font-weight: bold">Rekap Dokter Perujuk Pasien Rawat Inap</p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th class="center">No</th>
                <th>Nama Dokter Perujuk</th>
                <th class="center" style="width: 100px">Jumlah</th>
                <th class="center" style="width: 100px">%</th>
              </tr>

              <?php 
                $nod = 0;
                foreach ($dokter_pengirim as $key_f => $val_f) {
                  $arr_dp[$key_f] = count($val_f);
                }
                arsort($arr_dp);
                // echo "<pre>";print_r($arr_dp);die;
                foreach($arr_dp as $key_dp => $val_dp) : $nod++; 
                $arr_total_dp[] = count($dokter_pengirim[$key_dp]);
                $percent_dp = (count($dokter_pengirim[$key_dp])/$total) * 100;
                $arr_percent_dp[] = $percent_dp;
              ?>
                <tr>
                  <td align="center" width="30px"><?php echo $nod; ?></td>
                  <td><?php echo ($key_dp) ? strtoupper($key_dp) : "DOKTER PERUJUK LAINNYA";?></td>
                  <td align="center"><?php echo number_format(count($dokter_pengirim[$key_dp]));?></td>
                  <td align="center"><?php echo number_format($percent_dp, 2);?></td>
                </tr>
              <?php 
                endforeach; 
              ?>
              <tr style="font-weight: bold">
                <td align="right" colspan="2">TOTAL</td>
                <td align="center"><?php echo number_format(array_sum($arr_total_dp));?></td>
                <td align="center"><?php echo number_format(array_sum($arr_percent_dp));?></td>
              </tr>
            </table>
          </div>

        </div>

        <div id="rekap5" class="tab-pane fade">
          
          <div>
          <p style="font-weight: bold">Rekap Jumlah Pasien Rawat Inap Berdasarkan Perusahaan Penjamin</p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th class="center">No</th>
                <th>Nama Perusahaan</th>
                <th class="center" style="width: 100px">Jumlah</th>
                <th class="center" style="width: 100px">%</th>
              </tr>

              <?php 
                $nod = 0;
                foreach ($perusahaan as $key_p => $val_p) {
                  foreach ($val_p as $key_row_p => $val_row_p) {
                    $arr_perusahaan[$val_row_p->nama_perusahaan] = count($perusahaan[$key_p]);
                  }
                  $arr_total_perusahaan[] = count($perusahaan[$key_p]);
                }
                arsort($arr_perusahaan);
                // echo "<pre>"; print_r($arr_perusahaan);
                foreach($arr_perusahaan as $key_perusahaan => $val_perusahaan) : $nod++; 
                
                $percent_perusahaan = (count($perusahaan[$key_perusahaan])/array_sum($arr_total_perusahaan)) * 100;
                $arr_percent_perusahaan[] = $percent_perusahaan;
              ?>
                <tr>
                  <td align="center" width="30px"><?php echo $nod; ?></td>
                  <td><?php echo ($key_perusahaan) ? strtoupper($key_perusahaan) : "PRIBADI/UMUM";?></td>
                  <td align="center"><?php echo number_format(count($perusahaan[$key_perusahaan]));?></td>
                  <td align="center"><?php echo number_format($percent_perusahaan, 2);?></td>
                </tr>
              <?php 
                endforeach; 
              ?>
              <tr style="font-weight: bold">
                <td align="right" colspan="2">TOTAL</td>
                <td align="center"><?php echo number_format(array_sum($arr_total_perusahaan));?></td>
                <td align="center"><?php echo number_format(array_sum($arr_percent_perusahaan));?></td>
              </tr>
            </table>
          </div>

        </div>

      </div>
    </div>


  </div>
</div>