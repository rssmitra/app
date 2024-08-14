<div class="row">
            
    <div class="col-md-12" style="padding-top: 10px">
      <p style="font-weight: bold"> JUMLAH KUNJUNGAN INSTALASI GAWAT DARURAT</p>
      <table class="table">
        <tr style="background: #e9e6e6">
          <th rowspan="3" style="vertical-align: middle !important" class="center" width="50px">No</th>
          <th rowspan="3" style="vertical-align: middle !important">Nama Unit Instalasi</th>
          <th class="center" colspan="6">Kategori Pasien</th>
          <th class="center" rowspan="3" style="vertical-align: middle !important">Jumlah<br>Pasien</th>
          <th class="center" rowspan="3" style="vertical-align: middle !important">Pasien<br>Batal</th>
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
          </tr>
        <?php endforeach; ?>
        <!-- <tr style="font-weight: bold">
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
        </tr> -->
      </table>
    </div>
    <hr>
    <div class="col-md-12" style="padding-top: 10px">
      <p style="font-weight: bold"> DOKTER IGD</p>
      <table class="table">
        <tr style="background: #e9e6e6">
          <th class="center" width="50px">No</th>
          <th colspan="2">Unit Instalasi / Nama Dokter Jaga</th>
          <th class="center" width="120px">Jumlah<br>Pasien</th>
          <th class="center" width="120px">Pasien<br>Batal</th>
          <th class="center" width="120px">Konversi<br>ke RI</th>
          <th class="center" width="120px">%</th>
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
              $batal = isset($dokter_batal[$key_poli][$key_dok][1]) ? count($dokter_batal[$key_poli][$key_dok][1]) : 0;
              $konversi_ri = isset($dokter_konv_ri[$key_poli][$key_dok]['Rujuk ke Rawat Inap']) ? count($dokter_konv_ri[$key_poli][$key_dok]['Rujuk ke Rawat Inap']) : 0;
              $arr_batal[] = $batal;
              $arr_konversi_ri[] = $konversi_ri;

        ?>
          <tr>
            <td align="center" width="30px"><?php echo $nob; ?></td>
            <td><?php echo strtoupper($key_dok);?></td>
            <td align="center"><?php echo count($dokter[$key_poli][$key_dok]);?></td>
            <td align="center"><?php echo number_format($batal);?></td>
            <td align="center"><?php echo number_format($konversi_ri);?></td>
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
          <td align="center"><?php echo number_format(array_sum($arr_batal))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_konversi_ri))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_percent_dok))?></td>
        </tr>
      </table>
    </div>
    <hr>
    <div class="col-md-6" style="padding-top: 10px">
      <p style="font-weight: bold"> 10 DIAGNOSA PENYAKIT PASIEN TERBANYAK </p>
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
              if($val_diagnosa->total > 0 ) :
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
    <div class="col-md-6" style="padding-top: 10px">
      <p style="font-weight: bold"> 10 PENJAMIN TERBANYAK</p>
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
            if($nod < 11) :
          
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
            endif;
          endforeach; 
        ?>
        <tr style="font-weight: bold">
          <td align="right" colspan="2">TOTAL</td>
          <td align="center"><?php echo number_format(array_sum($arr_total_perusahaan));?></td>
          <td align="center"><?php echo number_format(array_sum($arr_percent_perusahaan));?></td>
        </tr>
      </table>
    </div>
    <div class="col-md-6" style="padding-top: 10px">
      <p style="font-weight: bold"> CARA KELUAR PASIEN</p>
      <table class="table">
        <tr style="background: #e9e6e6">
          <th class="center">No</th>
          <th>Cara Keluar Pasien</th>
          <th class="center" style="width: 100px">Jumlah</th>
        </tr>

        <?php 
          $nod = 0;
          foreach ($cara_keluar as $key_p => $val_p) :
            $nod++;
            if($key_p != '') {
              $total = count($cara_keluar[$key_p]);
            }else{
              $total = count($cara_keluar[0]);
            }
            $arr_total_ckp[] = $total;
        ?>
          <tr>
            <td align="center" width="30px"><?php echo $nod; ?></td>
            <td><?php echo $key_p;?></td>
            <td align="center"><?php echo number_format($total);?></td>
          </tr>
        <?php 
          endforeach; 
        ?>
        <tr style="font-weight: bold">
          <td align="right" colspan="2">TOTAL</td>
          <td align="center"><?php echo number_format(array_sum($arr_total_ckp));?></td>
        </tr>
      </table>
    </div>

    <div class="col-md-6" style="padding-top: 10px">
      <p style="font-weight: bold"> UMUR SAAT PELAYANAN</p>
      <table class="table">
        <tr style="background: #e9e6e6">
          <th class="center">No</th>
          <th>Kategori Usia</th>
          <th class="center" style="width: 100px">Jumlah</th>
        </tr>

        <?php 
          $nod = 0;
          foreach ($umur as $key_u => $val_u) :
            $nod++;
            $arr_u[] = array_sum($val_u);
            $txt_kategori_usia = $this->master->getKategoriUsiaName($key_u);
        ?>
          <tr>
            <td align="center" width="30px"><?php echo $nod; ?></td>
            <td><?php echo $txt_kategori_usia;?></td>
            <td align="center"><?php echo number_format(array_sum($val_u));?></td>
          </tr>
        <?php 
          endforeach; 
        ?>
        <tr style="font-weight: bold">
          <td align="right" colspan="2">TOTAL</td>
          <td align="center"><?php echo number_format(array_sum($arr_u));?></td>
        </tr>
      </table>
    </div>

</div>