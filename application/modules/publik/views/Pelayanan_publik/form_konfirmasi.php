<script type="text/javascript">
  function checkin(no_registrasi, no_mr, flag){
    $('#btn-action').hide();
    $.getJSON('publik/Pelayanan_publik/checkin/'+no_registrasi+'/'+no_mr+'/'+flag, '', function (data) { 
      $('#btn-reload').html('<a href="#" class="btn btn-block" style="background: green !important; border-color: green" onclick="getMenu('+"'publik/Pelayanan_publik/konfirmasi_kunjungan/"+$('#no_kunjungan').val()+"'"+')">Refresh Halaman</a>');
    })
  }
</script>
<form class="form-search" autocomplete="off">
    <div class="pull-left">
      <a href="<?php echo base_url().'public'?>" class="btn btn-sm" style="background : green !important; border-color: green"> <i class="fa fa-home"></i> Home</a>
    </div>
    <br>
    <!-- hidden form -->
    <input type="hidden" id="no_kunjungan" value="<?php echo $result->no_kunjungan?>">
    <input type="hidden" id="tgl_jam_poli" value="<?php echo $result->tgl_jam_poli?>">

    <div class="row">
      <div class="col-xs-12 col-sm-12">
        <h3 class="header smaller lighter green">Konfirmasi Kunjungan</h3>
          <div class="widget-box">
            <div class="widget-body">
              <div class="widget-main">
                <address>
                  <table>
                    <tr><td style="width: 45%">No. Rekam Medis</td><td>: <?php echo $result->no_mr; ?></td></tr>
                    <?php if($result->kode_perusahaan == 120) :?>
                    <tr><td>No. Kartu BPJS</td><td>: <?php echo $result->no_kartu_bpjs; ?></td></tr>
                    <?php else : ?>
                      <tr><td>Penjamin</td><td>: <?php echo ($result->kode_perusahaan == 0) ? 'Umum' : $result->nama_perusahaan ; ?></td></tr>
                    <?php endif; ?>
                    <tr><td>Nama pasien</td><td>: <?php echo $result->nama_pasien; ?></td></tr>
                    <tr><td>Umur</td><td>: <?php echo $result->umur; ?> thn</td></tr>
                  </table>
                  <hr>
                  <div class="center" style="background: green; color: white; margin-bottom: 20px; padding: 10px">
                    <span style="font-size:5em; font-weight: bold; width: 100%" onclick="getMenu('publik/Pelayanan_publik/antrian_poli')"><?php echo $result->no_antrian; ?></span><br>
                    <small>(Nomor Urut)</small>
                  </div>
                  <span style="font-size: 14px"><strong><?php echo $result->jd_hari.', '.$this->tanggal->formatDate($result->tgl_jam_poli).' '.$this->tanggal->formatTime($result->jd_jam_mulai).' - '.$this->tanggal->formatTime($result->jd_jam_selesai)?></strong><br>
                  <span style="font-size: 14px; font-weight: bold; text-transform: uppercase"><?php echo $result->nama_bagian; ?></span>
                  <br>
                  <?php echo $result->nama_pegawai; ?>
                  <br>
                </address>

                <div id="btn-reload"></div>
                    <address style="margin-left: -7px" id="btn-action" class="center">

                      <?php 
                        if($result->is_reschedule == 1){
                          echo '<span style="color: red; font-weight: bold">'.$result->keterangan_reschedule.'</span><br>';
                        }
                        if($result->status_batal == 1){
                          echo '<span class="red" style="font-weight: bold">Batal kunjungan</span><br>';
                          exit;  
                        }else{
                          if( $result->status_checkin == 1 ){
                            echo '4';
                            echo 'disini';
                            if( $result->konfirm_fp == 1 ){
                              echo '5';
                              echo '<span class="green" style="font-weight: bold">Silahkan langsung menuju meja tensi.</span><br>';
                            }else{
                              echo '6';
                              echo '<span class="red" style="font-weight: bold">Silahkan finger print pada kiosk</span><br>';
                            }
                          }else{
                            $disbaled = ($this->tanggal->formatDateTimeToSqlDate($result->tgl_jam_poli) == $this->tanggal->formatDateTimeToSqlDate(date('Y-m-d H:i:s'))) ? '' : 'disabled';
                            
                            // btn checkin
                            echo '<a href="#" class="btn btn-sm btn-success" style="background : green !important; border-color: green" onclick="checkin('.$result->no_registrasi.', '.$result->no_mr.', '."'checkin'".')" '.$disbaled.'><i class="fa fa-check"></i> Check In</a>';
                          }
                        }
                      ?>

                      <a href="#" onclick="checkin(<?php echo $result->no_registrasi?>, '<?php echo $result->no_mr?>', 'cancel')" class="btn btn-sm btn-danger" style="background : red !important; border-color: red"><i class="fa fa-times"></i> Batal Berobat</a>
                      
                    </address>

                    <address>
                      <div style="background: azure;padding: 6px;text-align: justify;">
                        <ol>
                          <li>Silahkan klik tombol checkin dan scan finger print di kiosk pada hari H atau pada saat sebelum praktek dokter dimulai</li>
                          <li>Setelah berhasil checkin silahkan langsung menuju tensi dan menunggu diruang poli</li>
                        </ol>
                      </div>
                    </address>
                      
                

              </div>
            </div>
          </div>
      </div>
    </div>
</form>






