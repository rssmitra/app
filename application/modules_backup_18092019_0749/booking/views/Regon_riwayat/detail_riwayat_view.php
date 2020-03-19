<div class="widget-box widget-color-green">
  <div class="widget-header widget-header-small">
    <h5 class="widget-title smaller">KODE (<?php echo $kode_booking?>)</h5>
    <span class="widget-toolbar">
      <a href="#" data-action="collapse">
        <i class="ace-icon fa fa-chevron-up"></i>
      </a>
    </span>
  </div>
  <div class="widget-body">
    <div class="widget-main">
      <div class="col-md-8">
        <?php 
          $log_pasien = json_decode($booking->log_detail_pasien);
          $log_transaksi = json_decode($booking->log_transaksi);
          echo $log_pasien->nama_pasien.' ('.$booking->regon_booking_no_mr.')<br>';
          echo $log_pasien->tgl_lahir.' ('.$log_pasien->jk.')<br>';
          echo 'Tujuan Poli : '.$log_transaksi->klinik->nama_bagian.' <br>'.$log_transaksi->dokter->nama_pegawai.'<br>';
          echo 'Jadwal praktek dokter : '.$booking->regon_booking_hari.', '.$this->tanggal->formatDate($booking->regon_booking_tanggal_perjanjian).' '.$booking->regon_booking_jam.'<br>';
          echo 'Waktu kedatangan pasien '.$this->tanggal->formatDateTime($booking->regon_booking_waktu_datang).'<br>';
          echo 'Status : '.$booking->regon_booking_status.'<br>';
          $qr_code = $booking->regon_booking_kode.'-'.$booking->regon_booking_no_mr.''.$booking->regon_booking_klinik.''.$booking->regon_booking_kode_dokter.''.$booking->regon_booking_instalasi;
        ?>
      </div>
      <div class="col-md-4">
        <img class="img-responsive center" src="<?php echo base_url().'assets/barcode.php?s=qrh&d='.$qr_code.''?>" style="min-width:250px"><br>
      </div>
      
    </div>
  </div>
</div>