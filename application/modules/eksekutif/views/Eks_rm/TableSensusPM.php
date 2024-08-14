<div class="row">
            
    <div class="col-md-12" style="padding-top: 10px">
      <p style="font-weight: bold"> REKAPITULASI KUNJUNGAN PASIEN PENUNJANG MEDIS (Laboratorium, Radiologi & Fisioterapi)</p>
      <table class="table">
        <tr style="background: #e9e6e6">
          <th rowspan="3" style="vertical-align: middle !important" class="center" width="50px">No</th>
          <th rowspan="3" style="vertical-align: middle !important">Nama Unit Instalasi</th>
          <th class="center" colspan="6">Kategori Pasien</th>
          <th class="center" rowspan="3" style="vertical-align: middle !important; width: 100px">Jumlah<br>Pasien</th>
          <th class="center" rowspan="3" style="vertical-align: middle !important; width: 100px">Pasien<br>Batal</th>
          <th class="center" rowspan="3" style="vertical-align: middle !important; width: 100px">(%)</th>
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
          foreach($unit as $key_unit => $val_unit) : $no++;
          $total_unit = isset($unit[$key_unit]) ? count($unit[$key_unit]) : 0;
          $arr_total_unit[] = $total_unit;

          // BPJS
          $pasien_lama_bpjs = isset($penjamin[$key_unit][120]['lama']) ? count($penjamin[$key_unit][120]['lama']) : 0;
          $arr_pasien_lama_bpjs[] = $pasien_lama_bpjs;
          $pasien_baru_bpjs = isset($penjamin[$key_unit][120]['baru']) ? count($penjamin[$key_unit][120]['baru']) : 0;
          $arr_pasien_baru_bpjs[] = $pasien_baru_bpjs;

          // UMUM
          $pasien_lama_umum = isset($penjamin[$key_unit][0]['lama']) ? count($penjamin[$key_unit][0]['lama']) : 0;
          $arr_pasien_lama_umum[] = $pasien_lama_umum;
          $pasien_baru_umum = isset($penjamin[$key_unit][0]['baru']) ? count($penjamin[$key_unit][0]['baru']) : 0;
          $arr_pasien_baru_umum[] = $pasien_baru_umum;

          // ASURANSI
          $pasien_lama_asuransi = isset($penjamin[$key_unit][1]['lama']) ? count($penjamin[$key_unit][1]['lama']) : 0;
          $arr_pasien_lama_asuransi[] = $pasien_lama_asuransi;
          $pasien_baru_asuransi = isset($penjamin[$key_unit][1]['baru']) ? count($penjamin[$key_unit][1]['baru']) : 0;
          $arr_pasien_baru_asuransi[] = $pasien_baru_asuransi;

          // PASIEN BATAL
          $pasien_batal = isset($batal[$key_unit][1]) ? count($batal[$key_unit][1]) : 0;
          $arr_pasien_batal[] = $pasien_batal;
          // TOTAL POLI
          // PERSENTASE
          $percent = ($total_unit / $total) * 100;
          $arr_percent[] = $percent;
        ?>
          <tr>
            <td align="center"><?php echo $no; ?></td>
            <td><?php echo strtoupper($key_unit);?></td>
            <td align="center"><?php echo $pasien_lama_bpjs;?></td>
            <td align="center"><?php echo $pasien_baru_bpjs;?></td>
            <td align="center"><?php echo $pasien_lama_umum;?></td>
            <td align="center"><?php echo $pasien_baru_umum;?></td>
            <td align="center"><?php echo $pasien_lama_asuransi;?></td>
            <td align="center"><?php echo $pasien_baru_asuransi;?></td>
            <td align="center"><?php echo $total_unit;?></td>
            <td align="center"><?php echo $pasien_batal;?></td>
            <td align="center"><?php echo number_format($percent,2);?></td>
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
          <td align="center"><?php echo number_format(array_sum($arr_total_unit))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_batal))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_percent))?> %</td>
        </tr>

        <tr style="font-weight: bold">
          <td colspan="2" align="right">RATA-RATA KUNJUNGAN PASIEN PER POLI</td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_lama_bpjs) / count($unit))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_baru_bpjs) / count($unit))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_lama_umum) / count($unit))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_baru_umum) / count($unit))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_lama_asuransi) / count($unit))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_baru_asuransi) / count($unit))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_total_unit) / count($unit))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_pasien_batal) / count($unit))?></td>
          <td align="center"><?php echo number_format(array_sum($arr_percent) / count($unit))?> %</td>
        </tr>
      </table>
    </div>
    <hr>
    <div class="col-md-12" style="padding-top: 10px">
      <p style="font-weight: bold"> UNIT INSTALASI ASAL YANG MERUJUK KE PENUNJANG MEDIS </p>
      <table class="table">
        <tr style="background: #e9e6e6">
          <th rowspan="2" style="vertical-align: middle !important" class="center">No</th>
          <th rowspan="2" style="vertical-align: middle !important">Nama Unit/Bagian</th>
          <th colspan="3" class="center">Tujuan Penunjang Medis</th>
          <th rowspan="2" style="vertical-align: middle !important; width: 100px" class="center">Jumlah</th>
          <th rowspan="2" style="vertical-align: middle !important; width: 100px" class="center">%</th>
        </tr>
        <tr style="background: #e9e6e6">
          <th width="100px" class="center">Lab</th>
          <th width="100px" class="center">Rad</th>
          <th width="100px" class="center">Fisio</th>
        </tr>

        <?php 
          $nod = 0;
          // echo "<pre>"; print_r(($bagian_asal_perpm));die;
          foreach ($bagian_asal_perpm as $key_p => $val_p) :
            $nod++;

            switch ($key_p) {
              case '05':
                # code...
                $txt_bagian_asal = 'Direct';
                break;
              case '03':
                # code...
                $txt_bagian_asal = 'Rawat Inap';
                break;
              case '01':
                # code...
                $txt_bagian_asal = 'Rawat Jalan';
                break;
              case '02':
                # code...
                $txt_bagian_asal = 'IGD';
                break;
            }
          
            $lab = isset($bagian_asal_perpm[$key_p]['050101']) ? count($bagian_asal_perpm[$key_p]['050101']):0;
            $arr_lab[] = $lab;
            $rad = isset($bagian_asal_perpm[$key_p]['050201']) ? count($bagian_asal_perpm[$key_p]['050201']):0;
            $arr_rad[] = $rad;
            $fisio = isset($bagian_asal_perpm[$key_p]['050301']) ? count($bagian_asal_perpm[$key_p]['050301']):0;
            $arr_fisio[] = $fisio;

            $total_row = $lab + $rad + $fisio;
            $arr_total[] = $total_row;
            $percent_bagian_asal = ($total_row/$total) * 100;
            $arr_percent_bagian_asal[] = $percent_bagian_asal;
            
        ?>
          <tr>
            <td align="center" width="30px"><?php echo $nod; ?></td>
            <td><?php echo ($txt_bagian_asal) ? strtoupper($txt_bagian_asal) : "-";?></td>
            <td align="center"><?php echo number_format($lab);?></td>
            <td align="center"><?php echo number_format($rad);?></td>
            <td align="center"><?php echo number_format($fisio);?></td>
            <td align="center"><?php echo number_format($total_row);?></td>
            <td align="center"><?php echo number_format($percent_bagian_asal, 2);?></td>
          </tr>
        <?php 
          endforeach; 
        ?>
        <tr style="font-weight: bold">
          <td align="right" colspan="2">TOTAL</td>
          <td align="center"><?php echo number_format(array_sum($arr_lab));?></td>
          <td align="center"><?php echo number_format(array_sum($arr_rad));?></td>
          <td align="center"><?php echo number_format(array_sum($arr_fisio));?></td>
          <td align="center"><?php echo number_format(array_sum($arr_total));?></td>
          <td align="center"><?php echo number_format(array_sum($arr_percent_bagian_asal));?></td>
        </tr>
      </table>
    </div>

    <div class="col-md-12" style="padding-top: 10px">
      <div class="col-md-4">
        <p style="font-weight: bold"> 10 PEMERIKSAAN LABORATORIUM TERBANYAK </p>
        <table class="table">
          <tr style="background: #e9e6e6">
            <th style="vertical-align: middle !important" class="center">No</th>
            <th style="vertical-align: middle !important">Nama Pemeriksaan</th>
            <th style="vertical-align: middle !important; width: 100px" class="center">Total</th>
          </tr>
          <?php 
            $nod = 0;
            // echo "<pre>"; print_r(($bagian_asal_perpm));die;
            foreach ($pemeriksaan['050101'] as $key_p => $val_p) :
              $nod++;
              if($nod < 11) :
                $arr_total_lab[] = $val_p->total;
              
          ?>
            <tr>
              <td align="center" width="30px"><?php echo $nod; ?></td>
              <td><?php echo $val_p->nama_tindakan;?></td>
              <td align="center"><?php echo number_format($val_p->total);?></td>
            </tr>
          <?php 
              endif;
            endforeach; 
          ?>
          <tr style="font-weight: bold">
            <td align="right" colspan="2">TOTAL</td>
            <td align="center"><?php echo number_format(array_sum($arr_total_lab));?></td>
          </tr>
        </table>
      </div>

      <div class="col-md-4">
        <p style="font-weight: bold"> 10 PEMERIKSAAN RADIOLOGI TERBANYAK </p>
        <table class="table">
          <tr style="background: #e9e6e6">
            <th style="vertical-align: middle !important" class="center">No</th>
            <th style="vertical-align: middle !important">Nama Pemeriksaan</th>
            <th style="vertical-align: middle !important; width: 100px" class="center">Total</th>
          </tr>
          <?php 
            $nod = 0;
            // echo "<pre>"; print_r(($bagian_asal_perpm));die;
            foreach ($pemeriksaan['050201'] as $key_p => $val_p) :
              $nod++;
              if($nod < 11) :
                $arr_total_rad[] = $val_p->total;
              
          ?>
            <tr>
              <td align="center" width="30px"><?php echo $nod; ?></td>
              <td><?php echo $val_p->nama_tindakan;?></td>
              <td align="center"><?php echo number_format($val_p->total);?></td>
            </tr>
          <?php 
              endif;
            endforeach; 
          ?>
          <tr style="font-weight: bold">
            <td align="right" colspan="2">TOTAL</td>
            <td align="center"><?php echo number_format(array_sum($arr_total_rad));?></td>
          </tr>
        </table>
      </div>

      <div class="col-md-4">
        <p style="font-weight: bold"> 10 PEMERIKSAAN FISIOTERAPI TERBANYAK </p>
        <table class="table">
          <tr style="background: #e9e6e6">
            <th style="vertical-align: middle !important" class="center">No</th>
            <th style="vertical-align: middle !important">Nama Pemeriksaan</th>
            <th style="vertical-align: middle !important; width: 100px" class="center">Total</th>
          </tr>
          <?php 
            $nod = 0;
            // echo "<pre>"; print_r(($bagian_asal_perpm));die;
            foreach ($pemeriksaan['050301'] as $key_p => $val_p) :
              $nod++;
              if($nod < 11) :
                $arr_total_fisio[] = $val_p->total;
              
          ?>
            <tr>
              <td align="center" width="30px"><?php echo $nod; ?></td>
              <td><?php echo $val_p->nama_tindakan;?></td>
              <td align="center"><?php echo number_format($val_p->total);?></td>
            </tr>
          <?php 
              endif;
            endforeach; 
          ?>
          <tr style="font-weight: bold">
            <td align="right" colspan="2">TOTAL</td>
            <td align="center"><?php echo number_format(array_sum($arr_total_fisio));?></td>
          </tr>
        </table>
      </div>
    </div>


</div>