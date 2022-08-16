<?php if(isset($_GET['print'])) :?>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<?php endif; ?>
<div class="row">

  <div class="col-sm-12 widget-container-col ui-sortable">
    <div class="widget-box transparent ui-sortable-handle">
      <div class="widget-header">
        <h4 class="widget-title lighter" style="padding-left: 12px">Data Riwayat Kunjungan Pasien</h4>
      </div>
      <div class="widget-body">
        <div class="col-md-6">
          <div class="widget-main">
            <address class="no-padding">
              No. Registrasi : <?php echo $result['registrasi']->no_registrasi?> &nbsp;&nbsp;&nbsp; Tanggal : <?php echo $this->tanggal->formatDateTime($result['registrasi']->tgl_jam_masuk)?><br>
              <?php echo ucfirst($result['registrasi']->nama_bagian)?>&nbsp; | &nbsp;<?php echo ucwords($result['registrasi']->nama_pegawai)?>
              <br><br>
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
            <a href="<?php echo base_url().'registration/reg_pasien/view_detail_resume_medis/'.$result['registrasi']->no_registrasi.'?print=true'?>" class="btn btn-xs btn-inverse" target="_blank">Cetak Resume Medis</a>
          </div>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>

  <div class="col-sm-12 widget-container-col ui-sortable" style="padding: 25px">
    <div class="widget-box transparent ui-sortable-handle">
      <div class="widget-body">
        <b>Resume Medis </b>
        <table class="table table-bordered table-hover">

            <thead>

              <th style="color:black">No</th>

              <th style="color:black">Jam Masuk Poli</th>

              <!-- <th style="color:black">Poli Asal</th> -->

              <th style="color:black">Poli Tujuan</th>

              <th style="color:black">Diagnosa Awal</th>

              <th style="color:black">Anamnesa</th>

              <th style="color:black">Tindakan/Pemeriksaan</th>

              <th style="color:black">Diagnosa Akhir</th>

            </thead>

            <tbody>

            <?php foreach($result['riwayat_medis'] as $row_rm) :?>
              <tr>
                <td><?php echo $row_rm->no_kunjungan?></td>
                <td><?php echo $this->tanggal->formatDateTime($row_rm->tgl_masuk)?></td>
                <!-- <td><?php echo $row_rm->poli_asal_kunjungan?></td> -->
                <td><?php echo $row_rm->poli_tujuan_kunjungan?></td>
                <td><?php echo ucfirst($row_rm->diagnosa_awal)?></td>
                <td><?php echo ucfirst($row_rm->anamnesa)?></td>
                <td><?php echo ucfirst($row_rm->pemeriksaan)?></td>
                <td><?php echo ucfirst($row_rm->diagnosa_akhir)?></td>
              </tr>
            <?php endforeach;?>

            </tbody>

        </table>
        <br>
        <b>Tindakan/Pemeriksaan</b>
        <table class="table table-bordered table-hover" style="width:80%">

            <thead>

              <th style="color:black">Kode</th>

              <th style="color:black">Tanggal</th>

              <th style="color:black">Dokter</th>

              <th style="color:black">Deskripsi Item</th>

              <th style="color:black">Jenis</th>

            </thead>

            <tbody>

            <?php foreach($result['tindakan'] as $row_t) : if(in_array($row_t->kode_jenis_tindakan, array(3,10,12) )) :?>
              <tr>
                <td><?php echo $row_t->kode_trans_pelayanan?></td>
                <td><?php echo $this->tanggal->formatDateTime($row_t->tgl_transaksi)?></td>
                <td><?php echo $row_t->nama_pegawai?></td>
                <td><?php echo $row_t->nama_tindakan?></td>
                <td><?php echo $row_t->jenis_tindakan?></td>
                <!-- <td align="center"><?php echo ($row_t->kode_tc_trans_kasir>0)?'<label class="label label-success">Lunas</label>':'<label class="label label-danger">Belum Dibayar</label>'?></td> -->
              </tr>
            <?php endif; endforeach;?>

            </tbody>

        </table>
        <br>
        <b>Obat Yang Diberikan</b>
        <table class="table table-bordered table-hover" style="width:60% !important">

            <thead>

              <th style="color:black">Kode</th>

              <th style="color:black">Nama Obat</th>
              <th style="color:black">Jenis</th>
              <th style="color:black">Jumlah</th>

            </thead>

            <tbody>

            <?php 
              foreach($result['tindakan'] as $row_obt) :
                if(in_array($row_obt->kode_jenis_tindakan, array(11) )) :
            ?>
            <tr>
              <td width="15%"><?php echo $row_obt->kode_trans_pelayanan?></td>
              <td><?php echo $row_obt->nama_tindakan?></td>
              <td><?php echo $row_obt->jenis_tindakan?></td>
              <td><?php echo $row_obt->jumlah_tebus?></td>
            </tr>
            <?php endif; endforeach?>

            </tbody>
            <span style="color:red;margin-top:-5%;display:none" id="alert_complate_data_pasien"><i>Silahkan lengkapi data pasien terlebih dahulu</i></span>

        </table>
        <br>
        <b>Kasir</b>
        <table class="table table-bordered table-hover" style="width:60% !important">

            <thead>

              <th style="color:black">Kode</th>
              <th style="color:black">No Kuitansi</th>
              <th style="color:black">Tanggal</th>
              <th style="color:black">Total Billing</th>

            </thead>

            <tbody>

            <?php 
              foreach($result['trans_kasir'] as $row_tk) :
            ?>
            <tr>
              <td width="15%"><?php echo $row_tk->kode_tc_trans_kasir?></td>
              <td><?php echo $row_tk->seri_kuitansi.' - '.$row_tk->no_kuitansi?></td>
              <td><?php echo $this->tanggal->formatDateTime($row_tk->tgl_jam)?></td>
              <td><?php echo number_format($row_tk->bill); ?></td>
            </tr>
            <?php endforeach?>

            </tbody>
            <span style="color:red;margin-top:-5%;display:none" id="alert_complate_data_pasien"><i>Silahkan lengkapi data pasien terlebih dahulu</i></span>

        </table>
        <br>
      <p><i>Generated by SIRS <?php echo COMP_LONG; ?> <?php echo $this->tanggal->formatDateTime(date('Y-m-d H:i:s')); ?></i></p>

      </div>
    </div>
  </div>

</div>

