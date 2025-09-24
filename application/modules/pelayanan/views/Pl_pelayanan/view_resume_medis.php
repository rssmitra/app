<?php 

if(isset($_GET['print'])) :
?>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<?php endif; ?>
<div class="row">

  <div class="col-sm-12 widget-container-col ui-sortable">
    <div class="widget-box transparent ui-sortable-handle">
      <div class="widget-body">
        <div class="col-md-6 no-padding">
          <div class="widget-main">
            <address class="no-padding">
              <strong style="font-size:12px"><?php echo strtoupper($result['registrasi']->nama_pasien)?> (<?php echo $result['registrasi']->no_mr?>) </strong> 
              <br>
              Tanggal, <?php echo $this->tanggal->formatDateTimeFormDmy($result['registrasi']->tgl_jam_masuk)?>
              <span style="padding-left: 30px">No. <?php echo strtoupper($result['registrasi']->no_registrasi)?></span>
              <br>
              Tgl Lahir, <?php echo $this->tanggal->formatDate($result['registrasi']->tgl_lhr)?> (<?php echo $umur?>) Tahun
              <br>
              <?php echo $result['registrasi']->almt_ttp_pasien?>                     
            </address>
          </div>
        </div>
        <?php if(!isset($_GET['print'])) :?>
        <div class="col-md-6" align="right">
          <div class="widget-main">

            <!-- <a href="#" onclick="selesaikanKunjungan(<?php echo $no_registrasi; ?>, <?php echo $result['registrasi']->no_kunjungan; ?>)" class="btn btn-xs btn-danger" target="_blank"><i class="fa fa-refresh"></i> Selesaikan Kunjungan</a> -->
            
            <a href="<?php echo base_url().'registration/reg_pasien/view_detail_resume_medis/'.$result['registrasi']->no_registrasi.'?print=true'?>" class="btn btn-xs btn-inverse" target="_blank"><i class="fa fa-print"></i> Resume Medis</a>
          </div>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>

  <div class="widget-box transparent ui-sortable-handle" style="padding: 25px">
    <div class="widget-body">
      <b>RESUME MEDIS</b>
      <table class="table table-bordered table-hover">

          <thead>
            <th style="color:black; width: 12%">Jam Masuk</th>
            <th style="color:black; width: 22%"><span style="font-weight: bold; font-style: italic; color: blue">(Subjective)</span><br>Anamnesa / Keluhan Pasien</th>
            <th style="color:black; width: 22%"><span style="font-weight: bold; font-style: italic; color: blue">(Objective)</span><br>Pemeriksaan Fisik</th>
            <th style="color:black; width: 22%"><span style="font-weight: bold; font-style: italic; color: blue">(Assesment)</span><br>Diagnosis & Prosedur</th>
            <th style="color:black; width: 22%"><span style="font-weight: bold; font-style: italic; color: blue">(Planning)</span><br>Rencana Asuhan Pasien</th>

          </thead>

          <tbody>

          <?php foreach($result['riwayat_medis'] as $row_rm) :?>
            <tr>
              <td><?php echo $this->tanggal->formatDateTime($row_rm->tgl_masuk)?></td>
              <td><?php echo ucfirst($row_rm->anamnesa)?></td>
              <td class="left">
                <span>Tanda-Tanda Vital</span><br>
                <?php echo ($row_rm->tinggi_badan != '')?'TB : '.$row_rm->tinggi_badan.'<br>':'';?>
                <?php echo ($row_rm->tekanan_darah != '')?'TD : '.$row_rm->tekanan_darah.'<br>':'';?>
                <?php echo ($row_rm->nadi != '')?'Nadi : '.$row_rm->nadi.'<br>':'';?>
                <?php echo ($row_rm->berat_badan != '')?'BB : '.$row_rm->berat_badan.'<br>':'';?>
                <?php echo ($row_rm->suhu != '')?'Suhu : '.$row_rm->suhu.'<br>':'';?>
                <br>
                <span>Pemeriksaan Fisik : </span><br>
                <?php echo ucfirst($row_rm->pemeriksaan)?>
              </td>
              <td>
                <span>Diagnosa Akhir : </span><br><?php echo ucfirst($row_rm->diagnosa_akhir)?>
                <br>
                <br>
                <span>Diagnosa Sekunder : </span><br><?php echo ucfirst(str_replace('|',' ',$row_rm->diagnosa_sekunder))?>
                <br>
                <br>
                <span>Prosedur/Tindakan : </span><br><?php echo ucfirst($row_rm->text_icd9)?>
              </td>
              <td>
                <?php echo nl2br($row_rm->pengobatan)?>
                <br>
                <br>
                Tgl Kontrol Kembali, <?php echo $this->tanggal->formatDate($row_rm->tgl_kontrol_kembali)?>
              </td>
            </tr>
          <?php endforeach;?>

          </tbody>

      </table>
      <br>
      <b>Cara Keluar Pasien : </b> <?php echo ucfirst($result['registrasi']->cara_keluar_pasien)?>, 
      <b>Pasca Pulang : </b> <?php echo ucfirst($result['registrasi']->pasca_pulang)?> <br>
      <?php if(isset($penunjang[$no_registrasi]) AND count($penunjang[$no_registrasi]) > 0) :?>
      <br>
      <b>PEMERIKSAAN PENUNJANG MEDIS :</b>
      <table class="table table-bordered" style="width: 50%">
        <tr>
          <th width="20px"></th>
          <th width="200px">Tanggal Daftar</th>
          <th>Unit Penunjang Medis</th>
        </tr>
        
          <?php 
            $result_pm = isset($penunjang[$no_registrasi])?$penunjang[$no_registrasi]:array();
            foreach($result_pm as $row_pm) : 
              switch ($row_pm->kode_bagian_tujuan) {
                case '050101':
                  $type_pm = 'LAB';
                  $color_pm = '#e8b0b0';
                  break;
                case '050201':
                  $type_pm = 'RAD';
                  $color_pm = '#e2b73e';
                  break;
                case '050201':
                  $type_pm = 'FISIO';
                  $color_pm = '#5ed3f7';
                  break;
                default:
                  $type_pm = '';   
                  $color_pm = '';
                  break;
              }
            ?>
          <tr>  
            <td class="center"><i class="fa fa-check bigger-120 green"></i></td>
            <td><?php echo $this->tanggal->formatDateTime($row_pm->tgl_masuk)?></td>
            <td>
            <a href="#" onclick="PopupCenter('<?php echo base_url()?>Templates/Export_data/export?type=pdf&flag=<?php echo $type_pm; ?>&noreg=<?php echo $row_pm->no_registrasi;?>&pm=<?php echo $row_pm->kode_penunjang?>&kode_pm=<?php echo $row_pm->kode_bagian_tujuan?>&no_kunjungan=<?php echo $row_pm->no_kunjungan?>', 'Hasil Penunjang Medis', 850, 650)" style="font-weight: bold; background: <?php echo $color_pm?>; color: black; padding: 2px"><?php echo $row_pm->nama_bagian?></a></br>
            </td>
          </tr>
          <?php endforeach; ?>
      </table>
      <?php endif; ?>

      <br>
      <b>BILLING PASIEN</b>
      <table class="table table-bordered table-hover" style="width:100%">

          <thead>
            <th style="color:black; width: 30px" class="center">No</th>
            <th style="color:black; width: 100px">Tanggal</th>
            <th style="color:black; width: 200px">Dokter</th>
            <th style="color:black">Deskripsi Item</th>
            <th style="color:black; width: 150px">Jenis</th>
            <th style="color:black; width: 200px">Unit</th>
            <th style="color:black; width: 100px">Jasa Dokter</th>
            <th style="color:black; width: 100px">Biaya Utilitas</th>
            <th style="color:black; width: 100px">Subtotal</th>
          </thead>
          <tbody>
          <?php 
            $arr_jasa_dokter = [];
            $arr_profit = [];
            $arr_total = [];
            $no = 0;
            foreach($result['tindakan'] as $row_t) : $no++;
              // if(in_array($row_t->kode_jenis_tindakan, array(3,10,12) )) :
                $arr_jasa_dokter[] = $row_t->jasa_dokter;
                $arr_profit[] = $row_t->profit;
                $arr_total[] = $row_t->total;
          ?>
            <tr>
              <td align="center"><?php echo $no?></td>
              <td><?php echo $this->tanggal->formatDate($row_t->tgl_transaksi)?></td>
              <td><?php echo $row_t->nama_pegawai?></td>
              <td>
                <?php 
                  echo $row_t->nama_tindakan;
                  echo "<br>";
                  if($row_t->flag_resep == 'racikan') {
                    $child_racikan = $this->master->get_child_racikan_farmasi($row_t->kode_trans_far);
                    $html_racikan = ($child_racikan != '') ? '<div style="padding:10px"><span style="font-size:11px; font-style: italic">Bahan racik :</span><br>'.$child_racikan.'</div>' : '' ;
                    echo $html_racikan;
                  } 
                ?>
                
              </td>
              <td><?php echo $row_t->jenis_tindakan?></td>
              <td><?php echo $row_t->nama_bagian?></td>
              <td align="right"><?php echo number_format($row_t->jasa_dokter)?></td>
              <td align="right"><?php echo number_format($row_t->profit)?></td>
              <td align="right"><?php echo number_format($row_t->total)?></td>
            </tr>
          <?php 
            // endif; 
          endforeach;?>
          <tr>
            <td align="right" colspan="6">Total</td>
            <td align="right"><?php echo number_format(array_sum($arr_jasa_dokter))?></td>
            <td align="right"><?php echo number_format(array_sum($arr_profit))?></td>
            <td align="right"><?php echo number_format(array_sum($arr_total))?></td>
          </tr>

          </tbody>

      </table>

      <br>
      <b>INSTALASI FARMASI</b>
      <table class="table table-bordered table-hover" style="width:80%">

          <thead>
            <tr>
            <th rowspan="2" style="color:black; width: 80px" class="center">No</th>
            <th rowspan="2" style="color:black; width: 150px">Tanggal Input</th>
            <th rowspan="2" style="color:black">Deskripsi Item</th>
            <th rowspan="2" style="color:black">Signa</th>
            <th colspan="2" class="center">Jumlah Obat</th>
            <th rowspan="2" style="color:black; width: 100px">Satuan Obat</th>
            </tr>
            </tr>
              <th class="center" style="color:black; width: 80px">Non Kronis</th>
              <th class="center" style="color:black; width: 80px">Kronis</th>
            </tr>
          </thead>
          <tbody>
          <?php 
            $no = 0;
            $getDtFr = []; foreach($result['farmasi'] as $row_t) : $no++;
                $getDtFr[] = $row_t;
          ?>
            <tr>
              <td align="center"><?php echo $no?></td>
              <td><?php echo $this->tanggal->formatDateTime($row_t->tgl_input)?></td>
              <td>
                <?php echo $row_t->nama_brg?>
                <?php
                  if($row_t->flag_resep == 'racikan') {
                    $child_racikan = $this->master->get_child_racikan_farmasi($row_t->kode_trans_far);
                    $html_racikan = ($child_racikan != '') ? '<br><div style="padding:10px"><span style="font-size:11px; font-style: italic">bahan racik :</span><br>'.$child_racikan.'</div>' : '' ;
                    echo $html_racikan;
                  } 
                ?>
              </td>
              <td><?php echo $row_t->dosis_per_hari.' x 1 (hari) '.$row_t->dosis_obat.' '.$row_t->satuan_obat.' '.$row_t->anjuran_pakai?></td>
              <td align="center"><?php echo ($row_t->jumlah_tebus > 0) ? (int)$row_t->jumlah_tebus : "-"?></td>
              <td align="center"><?php echo ($row_t->jumlah_obat_23 > 0) ? (int)$row_t->jumlah_obat_23 : "-"?></td>
              <td align="center"><?php echo $row_t->satuan_kecil?></td>
            </tr>
          <?php 
          endforeach;
            echo (count($getDtFr) > 0) ? "" : '<tr><td colspan="8" class="center red bold">Tidak ada data farmasi</td></tr>';
          ?>

          </tbody>

      </table>
      

    </div>
  </div>

</div>

