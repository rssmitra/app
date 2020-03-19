<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <div class="row">
      <div class="col-md-2">
          <img id="avatar" class="editable img-responsive editable-click editable-empty" alt="Alex's Avatar" src="<?php echo base_url()?>assets/avatars/profile-pic.jpg" width="150px">
      </div>

      <div class="col-md-10">
        <table width="60%" style="font-size:12px">
          <tr>
            <td colspan="2"><b>Data Pasien</b></td>
          </tr>
          <tr>
            <td width="150px">No MR (Medical Record)</td>
            <td>: <?php echo $data->reg_data->no_mr?></td>
          </tr>
          <tr>
            <td>Nama Pasien</td>
            <td>: <?php echo $data->reg_data->nama_pasien?></td>
          </tr>
          <tr>
            <td>TTL</td>
            <td>: <?php echo $data->reg_data->tempat_lahir?>, <?php echo $this->tanggal->formatDate($data->reg_data->tgl_lhr)?></td>
          </tr>
          <tr>
            <td colspan="2"><b>Kunjungan Terakhir</b></td>
          </tr>
          <tr>
            <td>Kunjungan Terakhir</td>
            <td>: <?php echo $this->tanggal->formatDateTime($data->reg_data->tgl_jam_masuk)?></td>
          </tr>
          <tr>
            <td>Poli/Klinik</td>
            <td>: <?php echo ucwords($data->reg_data->bagian_masuk_field)?></td>
          </tr>
          <tr>
            <td>Dokter</td>
            <td>: <?php echo $data->reg_data->nama_pegawai?></td>
          </tr>

        </table>
      </div>
    </div>

  </div><!-- /.col -->
</div>

<hr class="separator">