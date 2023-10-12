<div class="row">
  <div class="col-md-12">

    <div class="tabbable">
      <ul class="nav nav-tabs" id="myTab">
        <li class="active">
          <a data-toggle="tab" href="#rekap1">
            Pasien Poliklinik/Spesialis
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#rekap2">
            Pasien Dokter Spesialis
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#rekap3">
            Diagnosa Penyakit Pasien
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#rekap4">
            Faskes Perujuk
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
            <p style="font-weight: bold">Rekap Kunjungan Pasien Berdasarkan Poliklinik/Spesialis</p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th rowspan="2" class="center">No</th>
                <th rowspan="2">Poliklinik/Spesialis</th>
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
                foreach($poli as $key_poli => $val_poli) : $no++;
                $total_poli = isset($poli[$key_poli]) ? count($poli[$key_poli]) : 0;
                $arr_total_poli[] = $total_poli;
                $pasien_lama = isset($status_pasien[$key_poli]['lama']) ? count($status_pasien[$key_poli]['lama']) : 0;
                $arr_pasien_lama[] = $pasien_lama;
                $pasien_baru = isset($status_pasien[$key_poli]['baru']) ? count($status_pasien[$key_poli]['baru']) : 0;
                $arr_pasien_baru[] = $pasien_baru;
                $pasien_bpjs = isset($penjamin[$key_poli][120]) ? count($penjamin[$key_poli][120]) : 0;
                $arr_pasien_bpjs[] = $pasien_bpjs;
                $pasien_nonbpjs = $total_poli - $pasien_bpjs;
                $arr_pasien_nonbpjs[] = $pasien_nonbpjs;
                $percent = ($total_poli / $total) * 100;
                $arr_percent[] = $percent;
              ?>
                <tr>
                  <td align="center"><?php echo $no; ?></td>
                  <td><?php echo strtoupper($key_poli);?></td>
                  <td align="center"><?php echo $pasien_lama;?></td>
                  <td align="center"><?php echo $pasien_baru;?></td>
                  <td align="center"><?php echo $pasien_bpjs;?></td>
                  <td align="center"><?php echo $pasien_nonbpjs;?></td>
                  <td align="center"><?php echo $total_poli;?></td>
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
                <td align="center"><?php echo number_format(array_sum($arr_total_poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_percent))?></td>
              </tr>

              <tr style="font-weight: bold">
                <td colspan="2" align="right">RATA-RATA</td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_lama) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_baru) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_bpjs) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_nonbpjs) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_total_poli) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_percent) / count($poli))?></td>
              </tr>
            </table>
          </div>
          
        </div>

        <div id="rekap2" class="tab-pane fade">
          
          <div>
            <p style="font-weight: bold">Rekap Kunjungan Pasien Berdasarkan Dokter Spesialis</p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th class="center">No</th>
                <th colspan="2">Poliklinik/Spesialis</th>
                <th class="center">Jumlah</th>
                <th class="center">%</th>
                <th class="center">Chart</th>
              </tr>

              <?php 
                $nox=0; 
                $nob = 1;
                foreach($poli as $key_poli => $val_poli) : 
                  $nox++;
                  $countrow = isset($dokter[$key_poli]) ? count($dokter[$key_poli]) : 0;
                  $rowspan = ($countrow > 0) ? $countrow + 1 : 0;
                  echo "<tr>";
                  echo "<td rowspan=".$rowspan." align='center'>".$nox."</td>";
                  echo "<td style='font-weight: bold' colspan='5'>".strtoupper($key_poli)."</td>";
                  echo "</tr>";
                  foreach($dokter[$key_poli] as $key_dok=>$val_dok) :
                    $percent_dok = (count($dokter[$key_poli][$key_dok])/ array_sum($arr_total_poli)) * 100;
                    $arr_percent_dok[] = $percent_dok;
              ?>
                <tr>
                  <td align="center" width="30px"><?php echo $nob; ?></td>
                  <td><?php echo strtoupper($key_dok);?></td>
                  <td align="center"><?php echo count($dokter[$key_poli][$key_dok]);?></td>
                  <td align="center"><?php echo number_format($percent_dok, 2);?></td>
                  <td align="center" style="max-width: 100px">
                    <div class="progress progress-striped active" style="margin-bottom: 0px !important">
                      <div class="progress-bar progress-bar-yellow" style="width: <?php echo $percent_dok * 10;?>px"></div>
                    </div>
                  </td>
                </tr>
              <?php 
                $nob++; 
                $total_pasien_dokter[] = count($dokter[$key_poli][$key_dok]);
                endforeach; 
              ?>
              <?php endforeach; ?>
              <tr style="font-weight: bold">
                <td colspan="3" align="right">TOTAL</td>
                <td align="center"><?php echo number_format(array_sum($total_pasien_dokter))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_percent_dok))?></td>
              </tr>
            </table>
          </div>

        </div>

        <div id="rekap3" class="tab-pane fade">
          
          <div>
          <p style="font-weight: bold">Rekap Diagnosa Penyakit Pasien Terbanyak</p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th class="center">No</th>
                <th>Diagnosa/Penyakit</th>
                <th class="center">Jumlah</th>
              </tr>

              <?php 
                $noc = 0;
                foreach($diagnosa as $key_diagnosa => $val_diagnosa) : 
                  if($val_diagnosa->total <= 10) {
                    $lainnya[] = $val_diagnosa->total;
                  }
                  
                  if($val_diagnosa->total >= 10) :
                    $noc++;
                    $arr_total[] = $val_diagnosa->total;
              ?>
                <tr>
                  <td align="center" width="30px"><?php echo $noc; ?></td>
                  <td><?php echo strtoupper($val_diagnosa->diagnosa);?></td>
                  <td align="center"><?php echo number_format($val_diagnosa->total);?></td>
                </tr>
              <?php 
                endif;
                endforeach; 
              ?>
              <tr>
                <td align="center" width="30px"><?php echo $noc+1; ?></td>
                <td>DIAGNOSA ATAU PENYAKIT LAINNYA</td>
                <td align="center"><?php echo number_format(array_sum($lainnya));?></td>
              </tr>
              <tr style="font-weight: bold">
                <td align="right" colspan="2">TOTAL</td>
                <td align="center"><?php echo number_format(array_sum($lainnya) + array_sum($arr_total));?></td>
              </tr>
            </table>
          </div>

        </div>

        <div id="rekap4" class="tab-pane fade">
          
          <div>
          <p style="font-weight: bold">Rekap Jumlah Pasien Berdasarkan Faskes Perujuk</p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th class="center">No</th>
                <th>Faskes Perujuk</th>
                <th class="center" style="width: 100px">Jumlah</th>
                <th class="center" style="width: 100px">%</th>
              </tr>

              <?php 
                $nod = 0;
                foreach ($faskes as $key_f => $val_f) {
                  foreach ($val_f as $key_row => $val_row) {
                    if($val_row->kode_perusahaan == 120){
                      $arr_faskes[$val_row->nama_faskes] = count($faskes[$key_f]);
                    }
                  }
                  $arr_total_faskes[] = count($faskes[$key_f]);
                }
                arsort($arr_faskes);
                foreach($arr_faskes as $key_faskes => $val_faskes) : $nod++; 
                $percent_faskes = (count($faskes[$key_faskes])/array_sum($arr_total_faskes)) * 100;
                $arr_percent_faskes[] = $percent_faskes;
              ?>
                <tr>
                  <td align="center" width="30px"><?php echo $nod; ?></td>
                  <td><?php echo ($key_faskes) ? strtoupper($key_faskes) : "FASKES PERUJUK LAINNYA";?></td>
                  <td align="center"><?php echo number_format(count($faskes[$key_faskes]));?></td>
                  <td align="center"><?php echo number_format($percent_faskes, 2);?></td>
                </tr>
              <?php 
                endforeach; 
              ?>
              <tr style="font-weight: bold">
                <td align="right" colspan="2">TOTAL</td>
                <td align="center"><?php echo number_format(array_sum($arr_total_faskes));?></td>
                <td align="center"><?php echo number_format(array_sum($arr_percent_faskes));?></td>
              </tr>
            </table>
          </div>

        </div>

        <div id="rekap5" class="tab-pane fade">
          
          <div>
          <p style="font-weight: bold">Rekap Jumlah Pasien Berdasarkan Perusahaan Penjamin</p>
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