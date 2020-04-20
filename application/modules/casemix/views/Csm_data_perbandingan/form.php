<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
    <span style="font-size: 15px;">TOTAL DATA DITEMUKAN</span>
    <table class="table" style="width: 45% !important">
      <tr>
        <td width="65%">Total data SIRS</td>
        <td><?php echo $count_sirs; ?></td>
      </tr>

      <tr>
        <td width="65%">Total data hasil verifikasi BPJS</td>
        <td><?php echo $count_verif; ?></td>
      </tr>

      <tr>
        <td width="65%">Total data costing</td>
        <td><?php echo $count_costing; ?></td>
      </tr>

      <tr>
        <td width="65%">Duplikasi data SEP pada SIRS</td>
        <td><?php echo count($duplicate_sep); ?></td>
      </tr>
    </table>
    <br>
    <!-- <span style="font-size: 15px;">DUPLIKASI DATA SEP PADA SIRS</span>
    <table class="table" style="width: 80% !important">
      <tr>
        <th>No</th>
        <th>No Registrasi</th>
        <th>No MR</th>
        <th>Nama Pasien</th>
        <th>Tanggal</th>
        <th>No SEP SIRS</th>
        <th>No SEP Hasil Verif</th>
      </tr>
      <?php 
        $no=0; 
          foreach($duplicate_sep as $row_ds) : 
            foreach ($row_ds as $k_ds => $v_ds) :
              $no++; 

        ?>
        <tr>
          <td><?php echo $no; ?></td>
          <td><?php echo $v_ds->no_registrasi; ?></td>
          <td><?php echo $v_ds->no_mr; ?></td>
          <td><?php echo $v_ds->nama_pasien; ?></td>
          <td><?php echo $this->tanggal->formatDateTime($v_ds->tgl_jam_masuk); ?></td>
          <td><?php echo $v_ds->no_sep; ?></td>
          <td><?php echo $v_ds->csm_uhvd_no_sep; ?></td>
        </tr>
      <?php 
          endforeach;
        endforeach;
        ?>
    </table> -->

    <br>

    <span style="font-size: 15px;">DATA PERBANDINGAN</span>

    <table class="table" style="width: 75% !important">
      <tr>
        <td width="65%">Jumlah data pendaftaran yang sudah di klaim berdasarkan Nomor SEP</td>
        <td><?php echo $count_claimed; ?></td>
      </tr>

      <tr>
        <td width="65%">Selisih </td>
        <td><?php echo count($dt_not_claimed); ?></td>
      </tr>
    </table>

    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


