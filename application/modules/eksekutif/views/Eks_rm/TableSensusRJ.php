<div class="row">
  <div class="col-md-12">

    <div class="tabbable">
      <ul class="nav nav-tabs" id="myTab">
        <li class="active">
          <a data-toggle="tab" href="#rekap1">
            Poliklinik Spesialis
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#rekap2">
            Dokter DPJP
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#rekap3">
            Diagnosa Penyakit
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

        <li>
          <a data-toggle="tab" href="#rekap6">
            Tindakan Rawat Jalan
          </a>
        </li>

      </ul>

      <div class="tab-content">
        <div id="rekap1" class="tab-pane fade in active">
          
          <div>
            <p style="font-weight: bold"> REKAPITULASI KUNJUNGAN PASIEN POLIKLINIK SPESIALIS RAWAT JALAN</p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th rowspan="3" style="vertical-align: middle !important" class="center">No</th>
                <th rowspan="3" style="vertical-align: middle !important">Poliklinik/Spesialis</th>
                <th class="center" colspan="6">Kategori Pasien</th>
                <th class="center" rowspan="3" style="vertical-align: middle !important">Jumlah<br>Pasien</th>
                <th class="center" rowspan="3" style="vertical-align: middle !important">Pasien<br>Batal</th>
                <th class="center" rowspan="3" style="vertical-align: middle !important">%</th>
                <th class="center" rowspan="3" style="vertical-align: middle !important">Chart</th>
              </tr>
              <tr style="background: #e9e6e6">
                <th class="center" style="width: 160px" colspan="2">BPJS</th>
                <th class="center" style="width: 160px" colspan="2">Umum</th>
                <th class="center" style="width: 160px" colspan="2">Asuransi</th>
              </tr>
              <tr style="background: #e9e6e6">
                <th class="center" style="width: 80px">Lama</th>
                <th class="center" style="width: 80px">Baru</th>
                <th class="center" style="width: 80px">Lama</th>
                <th class="center" style="width: 80px">Baru</th>
                <th class="center" style="width: 80px">Lama</th>
                <th class="center" style="width: 80px">Baru</th>
              </tr>

              <?php 
                $no=0; 
                foreach($poli as $key_poli => $val_poli) : $no++;
                $total_poli = isset($poli[$key_poli]) ? count($poli[$key_poli]) : 0;
                $arr_total_poli[] = $total_poli;

                // BPJS
                $pasien_lama_bpjs = isset($penjamin[$key_poli][120]['lama']) ? count($penjamin[$key_poli][120]['lama']) : 0;
                $arr_pasien_lama_bpjs[] = $pasien_lama_bpjs;
                $pasien_baru_bpjs = isset($penjamin[$key_poli][120]['baru']) ? count($penjamin[$key_poli][120]['baru']) : 0;
                $arr_pasien_baru_bpjs[] = $pasien_baru_bpjs;

                // UMUM
                $pasien_lama_umum = isset($penjamin[$key_poli][0]['lama']) ? count($penjamin[$key_poli][0]['lama']) : 0;
                $arr_pasien_lama_umum[] = $pasien_lama_umum;
                $pasien_baru_umum = isset($penjamin[$key_poli][0]['baru']) ? count($penjamin[$key_poli][0]['baru']) : 0;
                $arr_pasien_baru_umum[] = $pasien_baru_umum;

                // ASURANSI
                $pasien_lama_asuransi = isset($penjamin[$key_poli][1]['lama']) ? count($penjamin[$key_poli][1]['lama']) : 0;
                $arr_pasien_lama_asuransi[] = $pasien_lama_asuransi;
                $pasien_baru_asuransi = isset($penjamin[$key_poli][1]['baru']) ? count($penjamin[$key_poli][1]['baru']) : 0;
                $arr_pasien_baru_asuransi[] = $pasien_baru_asuransi;

                // PASIEN BATAL
                $pasien_batal = isset($batal[$key_poli][1]) ? count($batal[$key_poli][1]) : 0;
                $arr_pasien_batal[] = $pasien_batal;
                // TOTAL POLI
                // PERSENTASE
                $percent = ($total_poli / $total) * 100;
                $arr_percent[] = $percent;
              ?>
                <tr>
                  <td align="center"><?php echo $no; ?></td>
                  <td><?php echo strtoupper($key_poli);?></td>
                  <td align="center"><?php echo $pasien_lama_bpjs;?></td>
                  <td align="center"><?php echo $pasien_baru_bpjs;?></td>
                  <td align="center"><?php echo $pasien_lama_umum;?></td>
                  <td align="center"><?php echo $pasien_baru_umum;?></td>
                  <td align="center"><?php echo $pasien_lama_asuransi;?></td>
                  <td align="center"><?php echo $pasien_baru_asuransi;?></td>
                  <td align="center"><?php echo $total_poli;?></td>
                  <td align="center"><?php echo $pasien_batal;?></td>
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
                <td align="center"><?php echo number_format(array_sum($arr_pasien_lama_bpjs))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_baru_bpjs))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_lama_umum))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_baru_umum))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_lama_asuransi))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_baru_asuransi))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_total_poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_batal))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_percent))?> %</td>
              </tr>

              <tr style="font-weight: bold">
                <td colspan="2" align="right">RATA-RATA KUNJUNGAN PASIEN PER POLI</td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_lama_bpjs) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_baru_bpjs) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_lama_umum) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_baru_umum) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_lama_asuransi) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_baru_asuransi) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_total_poli) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_pasien_batal) / count($poli))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_percent) / count($poli))?> %</td>
              </tr>
            </table>
          </div>
          
        </div>

        <div id="rekap2" class="tab-pane fade">
          
          <div>
            <p style="font-weight: bold"> REKAPITULASI KUNJUNGAN PASIEN PER DOKTER SPESIALIS</p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th class="center">No</th>
                <th colspan="2">Poliklinik/Spesialis</th>
                <th class="center" width="120px">Jumlah<br>Pasien</th>
                <th class="center" width="120px">Batal<br>Kunjungan</th>
                <th class="center" width="120px">Persentase<br>Kunjungan (%)</th>
                <th class="center" width="200px">Chart</th>
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
                    $jml_batal = isset($dokter_batal[$key_poli][$key_dok][1])?$dokter_batal[$key_poli][$key_dok][1]:[];
                    $arr_jml_batal[] = count($jml_batal);
              ?>
                <tr>
                  <td align="center" width="30px"><?php echo $nob; ?></td>
                  <td><?php echo strtoupper($key_dok);?></td>
                  <td align="center"><?php echo count($dokter[$key_poli][$key_dok]);?></td>
                  <td align="center"><span class="red bold" style="font-weight: bold"><?php echo count($jml_batal);?></span></td>
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
                <td align="center"><?php echo number_format(array_sum($arr_jml_batal))?></td>
                <td align="center"><?php echo number_format(array_sum($arr_percent_dok))?></td>
              </tr>
            </table>
          </div>

        </div>

        <div id="rekap3" class="tab-pane fade">
          
          <div>
          <p style="font-weight: bold"> REKAPITULASI 10 DIAGNOSA PENYAKIT PASIEN TERBANYAK </p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th class="center">No</th>
                <th>Diagnosa/Penyakit</th>
                <th class="center">Jumlah</th>
              </tr>

              <?php 
                $noc = 0;
                $lainnya = [];
                $arr_total = [];
                foreach($diagnosa as $key_diagnosa => $val_diagnosa) : 
                  // if($val_diagnosa->total <= 10) {
                  //   $lainnya[] = $val_diagnosa->total;
                  // }
                  
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
              <!-- <tr>
                <td align="center" width="30px"><?php echo $noc+1; ?></td>
                <td>DIAGNOSA ATAU PENYAKIT LAINNYA</td>
                <td align="center"><?php echo number_format(array_sum($lainnya));?></td>
              </tr> -->
              <tr style="font-weight: bold">
                <td align="right" colspan="2">TOTAL</td>
                <td align="center"><?php echo number_format(array_sum($lainnya) + array_sum($arr_total));?></td>
              </tr>
            </table>
          </div>

        </div>

        <div id="rekap4" class="tab-pane fade">
          
          <div>
          <p style="font-weight: bold"> REKAPITULASI JUMLAH KUNJUNGAN PASIEN BERDASARKAN FASKES PERUJUK</p>
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
          <p style="font-weight: bold"> REKAPITULASI KUNJUNGAN PASIEN BERDASARKAN PENJAMIN</p>
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

        <div id="rekap6" class="tab-pane fade">
          
          <div>
          <p style="font-weight: bold"> REKAPITULASI BERDASARKAN TINDAKAN </p>
            <table class="table">
              <tr style="background: #e9e6e6">
                <th class="center">No</th>
                <th>Nama Tindakan</th>
                <th class="center" style="width: 100px">Jumlah</th>
              </tr>

              <?php 
                $noe = 0;
                foreach ($tindakan as $key_p => $val_p) :
                  $noe++;
                  $arr_total_t[] = $val_p->total;
              ?>
                <tr>
                  <td align="center" width="30px"><?php echo $noe; ?></td>
                  <td><?php echo ($val_p->nama_tindakan) ? strtoupper($val_p->nama_tindakan) : "-";?></td>
                  <td align="center"><?php echo number_format($val_p->total);?></td>
                </tr>
              <?php 
                endforeach; 
              ?>
              <tr style="font-weight: bold">
                <td align="right" colspan="2">TOTAL</td>
                <td align="center"><?php echo number_format(array_sum($arr_total_t));?></td>
              </tr>
            </table>
          </div>

        </div>

      </div>
    </div>


  </div>
</div>