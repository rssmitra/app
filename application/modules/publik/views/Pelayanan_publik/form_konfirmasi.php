<script type="text/javascript">
  function checkin(){
    $('#btn-action').hide();
    $('#checkin-msg').show();
  }
</script>
<form class="form-search" autocomplete="off">
    <div class="pull-left">
      <a href="<?php echo base_url().'public'?>" class="btn btn-sm" style="background : green !important; border-color: green"> <i class="fa fa-home"></i> Home</a>
    </div>
    <br>
    <div class="row">
      <div class="col-xs-12 col-sm-12">
        <h3 class="header smaller lighter green">Konfirmasi Kunjungan</h3>
          <div class="widget-box">
            <div class="widget-body">
              <div class="widget-main">
                <address>
                  <table class="table">
                    <tr><td>No. Rekam Medis</td><td>: 00211762</td></tr>
                    <tr><td>No. Kartu BPJS</td><td>: 00036031223</td></tr>
                    <tr><td>Nama pasien</td><td>: Muhammad Amin Lubis</td></tr>
                    <tr><td>Umur</td><td>: 32 thn</td></tr>
                  </table>
                  <div class="center">
                  <small>Nomor Urut.</small><br>
                  <span style="font-size:5em; font-weight: bold; color: green; width: 100%" onclick="getMenu('publik/Pelayanan_publik/antrian_poli')">5</span><br>
                  </div>
                  <span style="font-size: 14px"><strong>9826387</strong><br>
                  <span style="font-size: 14px; font-weight: bold; text-transform: uppercase">Klinik Spesialis Anestesi</span>
                  <br>
                  dr. RR. Pramada Resvita Nugrahati Putranto, Sp.An
                  <br>
                  Kamis, 6/07/2023, 08.00 s/d 12.00
                </address>

                <address style="margin-left: -7px" id="btn-action">
                  <a href="#" class="btn btn-sm btn-success" style="background : green !important; border-color: green" onclick="checkin()"><i class="fa fa-check"></i> Check In</a>
                  <a href="#" class="btn btn-sm btn-danger" style="background : red !important; border-color: red"><i class="fa fa-times"></i> Batal Berobat</a>
                </address>

                <address style="margin-left: 0px; display: none" id="checkin-msg">
                    <div class="alert alert-success"><span><i class="fa fa-check"></i> <b>Anda sudah berhasil checkin !</b><br>silahkan konfirmasi finger print pada kiosk !</span>
                    </div>
                </address>
              </div>
            </div>
          </div>
      </div>
    </div>
</form>






