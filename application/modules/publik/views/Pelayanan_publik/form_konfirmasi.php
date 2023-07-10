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
                    <!-- <tr><td>No. Kartu BPJS</td><td>: <?php echo $_GET['noKartu']; ?></td></tr> -->
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
                
                <?php if($result->status_batal == 1) :?>

                  <span class="red" style="font-weight: bold">Batal kunjungan</span>

                <?php else: ?>

                  <?php if( $result->status_checkin == 1 ) : ?>

                    <?php if($result->konfirm_fp != 1) :?>
                        <address style="margin-left: 0px;">
                          <div class="alert alert-warning"><span><i class="fa fa-check"></i> <b>Silahkan Finger Print !</b><br>Silahkan scan sidik jari anda pada kiosk !</span>
                          </div>
                        </address>
                      <?php else: ?>
                        <address style="margin-left: 0px;">
                            <div class="alert alert-success"><span><i class="fa fa-check"></i> <b>Anda sudah berhasil checkin !</b><br>silahkan konfirmasi finger print pada kiosk !</span>
                            </div>
                        </address>
                      <?php endif; ?>

                  <?php else: ?>
                    
                    <address style="margin-left: -7px" id="btn-action" class="center">
                      <a href="#" class="btn btn-sm btn-success" style="background : green !important; border-color: green" onclick="checkin(<?php echo $result->no_registrasi?>, '<?php echo $result->no_mr?>', 'checkin')"><i class="fa fa-check"></i> Check In</a>
                      <a href="#" onclick="checkin(<?php echo $result->no_registrasi?>, '<?php echo $result->no_mr?>', 'cancel')" class="btn btn-sm btn-danger" style="background : red !important; border-color: red"><i class="fa fa-times"></i> Batal Berobat</a>
                    </address>
                      
                  <?php endif; ?>
                <?php endif; ?>

                

              </div>
            </div>
          </div>
      </div>
    </div>
</form>






