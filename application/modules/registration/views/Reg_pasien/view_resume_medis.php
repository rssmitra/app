<?php
  echo $header;
  if(!isset($result['registrasi']->no_registrasi)){
    echo '<div class="alert alert-danger"><strong>Data dihapus !</strong> Data registrasi tidak ditemukan.</div>';
    exit;
  }
?>
<?php if(isset($_GET['print'])) :?>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<?php endif; ?>
<hr>
<div class="row">

  
<h4 style="text-align: center" class="widget-title lighter">RESUME MEDIS PASIEN</h4>

<?php if(!isset($_GET['print'])) :?>
<div class="col-md-12" align="right">
  <div class="widget-main">
    <a href="<?php echo base_url().'registration/reg_pasien/view_detail_resume_medis/'.$result['registrasi']->no_registrasi.'?print=true'?>" class="btn btn-xs btn-inverse" target="_blank">Cetak Resume Medis</a>
  </div>
</div>
<?php endif;?>

  <div class="col-sm-12 widget-container-col ui-sortable">
    <div class="widget-box transparent ui-sortable-handle">
      <div class="widget-body">
        <div class="col-md-12">

          <?php 
            if($tipe_layan == 'RJ') :
            foreach($result['riwayat_medis'] as $row_rm) :
              $str = substr($row_rm->kode_bagian_tujuan, 0,2);
              $poli = [];
              if($str == '01') :
                $poli[] = $row_rm;
          ?>
          <br>
          <p>
            <span style="font-weight: bold;">DIAGNOSA AWAL :</span><br><?php echo $row_rm->diagnosa_awal; ?> <br><br>
            <span style="font-weight: bold;">ANAMNESA :</span><br><?php echo nl2br($row_rm->anamnesa); ?> <br><br>
            <span style="font-weight: bold;">TINDAKAN/ PEMERIKSAAN :</span>
            <br><?php echo nl2br($row_rm->pemeriksaan); ?> <?php echo ($row_rm->pengobatan != '')?', '.nl2br($row_rm->pengobatan):'-'?><br>
            
            
            <br><br>
            <span style="font-weight: bold;">DIAGNOSA AKHIR :</span><br><?php echo $row_rm->diagnosa_akhir; ?> <br><br>
          </p>
          <?php endif; endforeach; endif; ?>
          
          <?php if($tipe_layan == 'RI') :?>
          <b>RIWAYAT MEDIS PASIEN</b>
          <table class="table table-bordered table-hover">

            <thead>

              <th style="color:black">No</th>

              <th style="color:black; width: 150px">Jam Masuk Poli</th>

              <th style="color:black; width: 120px">Unit Asal</th>

              <th style="color:black; width: 120px">Unit Tujuan</th>

              <th style="color:black; width: 150px">Diagnosa Awal</th>

              <th style="color:black">Anamnesa</th>

              <th style="color:black">Tindakan/Pemeriksaan</th>

              <th style="color:black; width: 150px">Diagnosa Akhir</th>

            </thead>

            <tbody>

            <?php 
              $no = 0;
              foreach($result['riwayat_medis'] as $row_rm):
                if(!in_array($row_rm->kode_bagian_tujuan, ['050101','050201'])) :
                // echo "<pre>"; print_r($row_rm);die;
                $no++;
            ?>
              <tr>
                <td align="center"><?php echo $no;?></td>
                <td><?php echo $this->tanggal->formatDateTime($row_rm->tgl_masuk)?></td>
                <td><?php echo $row_rm->poli_asal_kunjungan?></td>
                <td><?php echo $row_rm->poli_tujuan_kunjungan?></td>
                <td><?php echo ucfirst($row_rm->diagnosa_awal)?></td>
                <td><?php echo ucfirst($row_rm->anamnesa)?></td>
                <td><?php echo ucfirst($row_rm->pemeriksaan)?></td>
                <td><?php echo ucfirst($row_rm->diagnosa_akhir)?></td>
              </tr>
            <?php endif; endforeach;?>

            </tbody>

          </table>
          <?php endif;?>
          <hr>
          <b>PEMERIKSAAN PENUNJANG MEDIS</b>
          <table class="table table-bordered table-hover" style="width:100%">
            <thead>
              <th style="color:black; width: 10%">Tanggal</th>
              <th style="color:black; width: 20%">Dokter Pemeriksa</th>
              <th style="color:black; width: 15%">Jenis Penunjang</th>
              <th style="color:black">Item Pemeriksaan</th>
            </thead>
            <tbody>
              <?php 
                $getDataPenunjang = [];
                foreach($result['tindakan'] as $row_p) {
                  $getDataPenunjang[$row_p->kode_bagian][] = $row_p;
                }
                // echo "<pre>"; print_r($getDataPenunjang);die;
                foreach($getDataPenunjang as $key_t=>$row_t) : 
                  $str_tindakan = substr($key_t, 0,2);
                  if($str_tindakan == '05') :
              ?>
                <tr>
                  <td><?php echo $this->tanggal->formatDate($row_t[0]->tgl_transaksi)?></td>
                  <td><?php echo $row_t[0]->nama_pegawai?></td>
                  <td><?php echo $this->master->get_string_data('nama_bagian', 'mt_bagian', ['kode_bagian' => $row_t[0]->kode_bagian])?></td>
                  <td>
                    <ol>
                    <?php foreach($row_t as $row_dt){
                      echo "<li>".$row_dt->nama_tindakan."</li>";
                    }?>
                    </ol>
                  </td>
                </tr>
              <?php endif; endforeach;?>
            </tbody>
          </table>

          <hr>
          <b>OBAT YANG DIBERIKAN</b>
          <table class="table table-bordered table-hover" style="width:100% !important">
              <thead>
                <th style="color:black">No</th>
                <th style="color:black">Nama Obat</th>
                <th style="color:black">Dosis</th>
                <th style="color:black">Jumlah</th>
                <th style="color:black">Catatan</th>
              </thead>

              <tbody>

              <?php 
                $no = 0;
                foreach($result['tindakan'] as $row_obt) :
                  if(in_array($row_obt->kode_jenis_tindakan, array(11) )) :
                    $no++;
              ?>
              <tr>
                <td align="center"><?php echo $no?></td>
                <td><?php echo $row_obt->nama_tindakan?></td>
                <td><?php echo $row_obt->dosis_per_hari.' x '.$row_obt->dosis_obat.' '.$row_obt->satuan_obat?> <?php echo $row_obt->anjuran_pakai?></td>
                <td><?php echo ($row_obt->jumlah_tebus == 0) ? (int)$row_obt->jumlah_obat_23 : (int)$row_obt->jumlah_tebus + (int)$row_obt->jumlah_obat_23?> <?php echo $row_obt->satuan_kecil?></td>
                <td><?php echo $row_obt->catatan_lainnya?></td>
              </tr>
              <?php endif; endforeach?>

              </tbody>
              <span style="color:red;margin-top:-5%;display:none" id="alert_complate_data_pasien"><i>Silahkan lengkapi data pasien terlebih dahulu</i></span>

          </table>
          <br>
          <br>
          <p><i>Generated by <?php echo APPS_NAME_SORT.'&nbsp; '.COMP_LONG; ?> <?php echo $this->tanggal->formatDateTime(date('Y-m-d H:i:s')); ?></i></p>

        </div>
      </div>
    </div>
  </div>

</div>

<?php echo $footer; ?>

