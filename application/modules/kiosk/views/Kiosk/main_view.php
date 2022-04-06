<style>
  .widget-title{
    font-size: medium !important;
    font-weight: bold;
  }
  .widget-color-dark {
    border-color: #dfdcdc;
    border: 0px !important
  }
</style>

<div class="row" style="top:50%; left: 50%">

  <p style="text-align: center; font-size: 2.5em; font-weight: bold">KIOSK PELAYANAN PASIEN</p>
  <div style="padding-top: 20px">

    <div class="col-sm-4 widget-container-col ui-sortable" id="widget-container-col-11">
      <div class="widget-box widget-color-dark ui-sortable-handle" id="widget-box-11">
        <!-- <div class="widget-header">
          <h6 class="widget-title">BPJS KESEHATAN</h6>
        </div> -->
        <div class="widget-body">
          <div class="widget-main padding-4" data-size="125" style="position: relative;">
            <div style="min-height: 350px;">
              <div class="content">
                <div class="center" style="padding-top: 10px;">
                  <img src="<?php echo base_url()?>assets/kiosk/bpjs-2.png" height="100" alt="">
                  <br>
                  <br>
                  <span style="font-size: 18px;font-weight: bold; padding-top: 10px">BPJS Kesehatan</span>
                  <p style="align: justify; padding-top: 10px; min-height: 100px">Pendaftaran Mandiri Pasien BPJS Kesehatan.<br>Persiapkan Kode Booking, Rujukan FKTP dan KTP anda untuk melakukan pendaftaran.</p>
                </div>
                <div class="center">
                  <a href="#" class="btn btn-lg" onclick="getMenu('kiosk/Kiosk/bpjs')" style="background: green !important; border-color: #b7d9b74f">Klik Disini</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-4 widget-container-col ui-sortable" id="widget-container-col-11">
      <div class="widget-box widget-color-dark ui-sortable-handle" id="widget-box-11">
        <!-- <div class="widget-header">
          <h6 class="widget-title">UMUM & ASURANSI</h6>
        </div> -->
        <div class="widget-body">
          <div class="widget-main padding-4" data-size="125" style="position: relative;">
            <div style="min-height: 350px;">
              <div class="content">
                <div class="center" style="padding-top: 10px;">
                <img src="<?php echo base_url()?>assets/kiosk/icon-ass-2.png" height="100" alt="">
                  <br>
                  <br>
                  <span style="font-size: 18px;font-weight: bold; padding-top: 10px">Umum & Asuransi</span>
                  <p style="align: justify; padding-top: 10px; min-height: 100px">Pendaftaran Mandiri Pasien Umum & Asuransi.<br>Persiapkan KTP anda untuk melakukan pendaftaran.</p>
                </div>
                <div class="center">
                  <a href="#" class="btn btn-lg" onclick="getMenu('kiosk/Kiosk/umum_asuransi')" style="background: green !important; border-color: #b7d9b74f">Klik Disini</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- <div class="col-sm-4 widget-container-col ui-sortable" id="widget-container-col-11">
      <div class="widget-box widget-color-dark ui-sortable-handle" id="widget-box-11">
        <div class="widget-header">
          <h6 class="widget-title">ANTRIAN PASIEN</h6>
        </div>
        <div class="widget-body">
          <div class="widget-main padding-4" data-size="125" style="position: relative;">
            <div style="min-height: 350px;">
              <div class="content">
                <div class="center" style="padding-top: 10px;">
                  <img src="<?php echo base_url()?>assets/kiosk/icon-que-2.png" height="100" alt="">
                  <br>
                  <br>
                  <span style="font-size: 18px;font-weight: bold; padding-top: 10px">Nomor Antrian</span>
                  <p style="align: justify; padding-top: 10px; min-height: 100px">Pengambilan Nomor Antrian Pendaftaran Pasien. Untuk Pasien BPJS harus memiliki kode booking untuk dapat mengambil Nomor Antrian</p>
                </div>
                <div class="center">
                  <a href="#" onclick="getMenu('Kiosk/antrian')" class="btn btn-lg" style="background: green !important; border-color: #b7d9b74f">Klik Disini</a>
                </div>
              </div>
              <p></p>
            </div>
          </div>
        </div>
      </div>
    </div> -->

    <div class="col-sm-4 widget-container-col ui-sortable" id="widget-container-col-11">
      <div class="widget-box widget-color-dark ui-sortable-handle" id="widget-box-11">
        <!-- <div class="widget-header">
          <h6 class="widget-title">PERJANJIAN PASIEN</h6>
        </div> -->
        <div class="widget-body">
          <div class="widget-main padding-4" data-size="125" style="position: relative;">
            <div style="min-height: 350px;">
              <div class="content">
                <div class="center" style="padding-top: 10px;">
                  <img src="<?php echo base_url()?>assets/kiosk/icon-appointment.png" height="100" alt="">
                  <br>
                  <br>
                  <span style="font-size: 18px;font-weight: bold; padding-top: 10px">Perjanjian Pasien</span>
                  <p style="align: justify; padding-top: 10px; min-height: 100px">Informasi Jadwal Dokter, Perjanjian Pasien, Reschedule Perjanjian.</p>
                </div>
                <div class="center">
                  <a href="#" onclick="getMenu('Kiosk/spesialis')" class="btn btn-lg" style="background: green !important; border-color: #b7d9b74f">Klik Disini</a>
                </div>
              </div>
              <p></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
  
</div>
