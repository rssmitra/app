<?php if(isset($_GET['print'])) :?>
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
              Umur : <?php echo $umur?>&nbsp; Tahun
              <br>
              <?php echo $result['registrasi']->almt_ttp_pasien?>                     
            </address>
          </div>
        </div>
        <?php if(!isset($_GET['print'])) :?>
        <div class="col-md-6" align="right">
          <div class="widget-main">

            <a href="#" onclick="selesaikanKunjungan(<?php echo $no_registrasi; ?>, <?php echo $result['registrasi']->no_kunjungan; ?>)" class="btn btn-xs btn-danger" target="_blank"><i class="fa fa-refresh"></i> Selesaikan Kunjungan</a>
            
            <a href="<?php echo base_url().'registration/reg_pasien/view_detail_resume_medis/'.$result['registrasi']->no_registrasi.'?print=true'?>" class="btn btn-xs btn-inverse" target="_blank"><i class="fa fa-print"></i> Resume Medis</a>
          </div>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>

  <div class="widget-box transparent ui-sortable-handle" style="padding: 25px">
    <div class="widget-body">
      <b>Resume Medis </b>
      <table class="table table-bordered table-hover">

          <thead>
            <th style="color:black">Jam Masuk Poli</th>
            <th class="center" style="color: black" >TB (cm)</th>
            <th class="center" style="color: black" >TD</th>
            <th class="center" style="color: black" >Nadi</th>
            <th class="center" style="color: black" >BB (Kg)</th>
            <th class="center" style="color: black" >Suhu (C)</th>
            <th style="color:black">Diagnosa Awal</th>
            <th style="color:black">Anamnesa</th>
            <th style="color:black">Tindakan/Pemeriksaan</th>
            <th style="color:black">Diagnosa Akhir</th>

          </thead>

          <tbody>

          <?php foreach($result['riwayat_medis'] as $row_rm) :?>
            <tr>
              <td><?php echo $this->tanggal->formatDateTime($row_rm->tgl_masuk)?></td>
              <td class="center"><?php echo ($row_rm->tinggi_badan != '')?$row_rm->tinggi_badan:'-';?></td>
              <td class="center"><?php echo ($row_rm->tekanan_darah != '')?$row_rm->tekanan_darah:'-';?></td>
              <td class="center"><?php echo ($row_rm->nadi != '')?$row_rm->nadi:'-';?></td>
              <td class="center"><?php echo ($row_rm->berat_badan != '')?$row_rm->berat_badan:'-';?></td>
              <td class="center"><?php echo ($row_rm->suhu != '')?$row_rm->suhu:'-';?></td>
              <td><?php echo ucfirst($row_rm->diagnosa_awal)?></td>
              <td><?php echo ucfirst($row_rm->anamnesa)?></td>
              <td><?php echo ucfirst($row_rm->pemeriksaan)?></td>
              <td><?php echo ucfirst($row_rm->diagnosa_akhir)?></td>
            </tr>
          <?php endforeach;?>

          </tbody>

      </table>
      <?php if(isset($penunjang[$no_registrasi]) AND count($penunjang[$no_registrasi]) > 0) :?>
      <br>
      <b>Penunjang Medis :</b>
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
      <b>Billing Pasien</b>
      <table class="table table-bordered table-hover" style="width:80%">

          <thead>

            <th style="color:black">Kode</th>

            <th style="color:black">Tanggal</th>

            <th style="color:black">Dokter</th>

            <th style="color:black">Deskripsi Item</th>

            <th style="color:black">Jenis</th>

          </thead>

          <tbody>

          <?php foreach($result['tindakan'] as $row_t) : 
              if(in_array($row_t->kode_jenis_tindakan, array(3,10,12) )) :?>
            <tr>
              <td><?php echo $row_t->kode_trans_pelayanan?></td>
              <td><?php echo $this->tanggal->formatDateTime($row_t->tgl_transaksi)?></td>
              <td><?php echo $row_t->nama_pegawai?></td>
              <td><?php echo $row_t->nama_tindakan?></td>
              <td><?php echo $row_t->jenis_tindakan?></td>
              <td align="center"><?php echo ($row_t->kode_tc_trans_kasir>0)?'<label class="label label-success">Lunas</label>':'<label class="label label-danger">Belum Dibayar</label>'?></td>
            </tr>
          <?php endif; endforeach;?>

          </tbody>

      </table>
    <p><i>Generated by SIRS Setia Mitra <?php echo $this->tanggal->formatDateTime(date('Y-m-d H:i:s')); ?></i></p>

    </div>
  </div>

</div>

