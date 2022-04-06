<script>

  function checkInForm(){
    var no_mr = $('#no_mr_val').val();
    getMenu('kiosk/Kiosk/checkIn?no_mr='+no_mr+'');

  }

  function select_penunjang(kode_bagian){

      // hide
      $('#asum_main_view').hide();
      $('#konfirmasi_kunjungan').show();
      $('#pm_tujuan').val(kode_bagian);
      $('#asal_pasien_pm').val(kode_bagian);

      $.getJSON("Templates/References/getRefPm/"+kode_bagian+"", '', function (response) {
          console.log(response.data);
          var poli = response.nama_bag;
          $('#txt-nama-poli').text(poli.toUpperCase());
      });

  }

  function submitForm(){

    var post_data = {
        no_mr : $('#no_mr_val').val(),
        nama_pasien : $('#nama_pasien').val(),
        umur_saat_pelayanan_hidden : $('#umur_saat_pelayanan_hidden').val(),
        kode_kelompok_hidden : $('#kode_kelompok_hidden').val(),
        kode_perusahaan_hidden : $('#kode_perusahaan_hidden').val(),
        jenis_pendaftaran : $('#jenis_pendaftaran').val(),
        pm_tujuan : $('#pm_tujuan').val(),
        asal_pasien_pm : $('#asal_pasien_pm').val(),
    }

    $.ajax({
      url: 'kiosk/Kiosk/process_register_penunjang',
      type: "post",
      data: post_data,
      dataType: "json",
      beforeSend: function() {
        achtungShowFadeIn();  
      },
      success: function(response) {

        if(response.status==200){
          // show success
          $('#konfirmasi_kunjungan').html('<div class="center" style="padding-top: 20px"><span style="font-size: 36px; font-weight: bold; color: green"><i class="fa fa-check-circle green bigger-250"></i><br>PENDAFTARAN BERHASIL DILAKUKAN!</span><br><span style="font-size: 20px">Silahkan menunggu diruang tunggu pelayanan untuk dipanggil.</span></div><br><div class="center"><a href="#" class="btn btn-lg" style="background : green !important; border-color: green;" onclick="getMenu('+"'kiosk/Kiosk/bpjs'"+')"> <i class="fa fa-arrow-left"></i> Kembali ke Menu Sebelumnya</a> <a href="#" onclick="reprint('+"'kiosk/Kiosk/print_bukti_registrasi/"+response.no_registrasi+"/"+response.no_antrian+"/"+response.tipe+"'"+')" class="btn btn-lg" style="background : green !important; border-color: green"><i class="fa fa-print"></i> Cetak Ulang Bukti Pendaftaran</a></div>');

          // setTimeout(function () {
          //         location.reload();
          // }, 5000);
              
        }else{
            return false;
        }
        // hide loader
        achtungHideLoader();
        
      }
    });

  }

  function rePrint(){

  }

</script>

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

<div class="row" style="top:50%; left: 50%" id="asum_main_view">

  <p style="text-align: center; font-size: 2em; font-weight: bold; color: green">KIOSK PELAYANAN PASIEN<br>BPJS KESEHATAN</p>
  <div style="padding-top: 20px">

    <div class="col-sm-4 widget-container-col ui-sortable" id="widget-container-col-11">
      <div class="widget-box widget-color-dark ui-sortable-handle" id="widget-box-11">
        <div class="widget-body">
          <div class="widget-main padding-4" data-size="125" style="position: relative;">
            <div style="min-height: 350px;">
              <div class="content">
                <div class="center" style="padding-top: 10px;">
                <img src="<?php echo base_url()?>assets/kiosk/icon-checkin.png" height="100" alt="">
                  <br>
                  <br>
                  <span style="font-size: 18px;font-weight: bold; padding-top: 10px">Check In</span>
                  <p style="align: justify; padding-top: 10px; min-height: 100px">Untuk Pasien BPJS yang sudah melakukan Perjanjian via Mobile JKN atau dari Nurse Station.</p>
                </div>
                <div class="center">
                  <a href="#" class="btn btn-lg" onclick="checkInForm()"  style="background: green !important; border-color: #b7d9b74f">Klik Disini</a>
                  <!-- <a href="#" class="btn btn-lg" disabled style="background: grey !important; border-color: grey">On Progress</a> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-4 widget-container-col ui-sortable" id="widget-container-col-11">
      <div class="widget-box widget-color-dark ui-sortable-handle" id="widget-box-11">
        <div class="widget-body">
          <div class="widget-main padding-4" data-size="125" style="position: relative;">
            <div style="min-height: 350px;">
              <div class="content">
                <div class="center" style="padding-top: 10px;">
                  <img src="<?php echo base_url()?>assets/kiosk/icon-rj.png" height="100" alt="">
                  <br>
                  <br>
                  <span style="font-size: 18px;font-weight: bold; padding-top: 10px">Poli/Klinik Rawat Jalan</span>
                  <p style="align: justify; padding-top: 10px; min-height: 100px; text-align: center">Pendaftaran Pasien BPJS untuk Pasien Baru atau Pasien Lama  <br> dengan Nomor Rujukan Baru tujuan ke Poli/Klinik/Spesialis.</p>
                </div>
                <div class="center">
                  <a href="#" onclick="getMenu('kiosk/Kiosk/bpjs_mod_poli')" class="btn btn-lg" style="background: green !important; border-color: #b7d9b74f">Klik Disini</a>
                  <!-- <a href="#" class="btn btn-lg" disabled style="background: grey !important; border-color: grey">On Progress</a> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- <div class="col-sm-3 widget-container-col ui-sortable" id="widget-container-col-11">
      <div class="widget-box widget-color-dark ui-sortable-handle" id="widget-box-11">
        <div class="widget-body">
          <div class="widget-main padding-4" data-size="125" style="position: relative;">
            <div style="min-height: 350px;">
              <div class="content">
                <div class="center" style="padding-top: 10px;">
                <img src="<?php echo base_url()?>assets/kiosk/icon-lab.png" height="100" alt="">
                  <br>
                  <br>
                  <span style="font-size: 18px;font-weight: bold; padding-top: 10px">Laboratorium</span>
                  <p style="align: justify; padding-top: 10px; min-height: 100px">Pendaftaran Mandiri Pasien Umum tujuan ke Laboratorium untuk Antigen/PCR/Pemeriksaan Lainnya.</p>
                </div>
                <div class="center">
                  <a href="#"  onclick="select_penunjang('050101')" class="btn btn-lg" style="background: green !important; border-color: #b7d9b74f">Klik Disini</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>  -->

    <div class="col-sm-4 widget-container-col ui-sortable" id="widget-container-col-11">
      <div class="widget-box widget-color-dark ui-sortable-handle" id="widget-box-11">
        <div class="widget-body">
          <div class="widget-main padding-4" data-size="125" style="position: relative;">
            <div style="min-height: 350px;">
              <div class="content">
                <div class="center" style="padding-top: 10px;">
                <img src="<?php echo base_url()?>assets/kiosk/icon-fisio.png" height="100" alt="">
                  <br>
                  <br>
                  <span style="font-size: 18px;font-weight: bold; padding-top: 10px">Fisioterapi</span>
                  <p style="align: justify; padding-top: 10px; min-height: 100px">Pendaftaran Mandiri Pasien BPJS yang sudah terjadwal rutin tujuan ke Fisioterapi.</p>
                </div>
                <div class="center">
                  <a href="#" class="btn btn-lg" onclick="select_penunjang('050301')"  style="background: green !important; border-color: #b7d9b74f">Klik Disini</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>

  <div class="center" style="left: 50%; top:70%" >
    <a href="#" class="btn btn-lg" style="background : green !important; border-color: green; margin-top: 7%" onclick="getMenu('kiosk/Kiosk/main')"> <i class="fa fa-home"></i> Kembali ke Menu Utama</a>
  </div>
  
</div>

<div id="konfirmasi_kunjungan" style="display: none">
  <div class="center" style="padding-top: 100px">
    <i class="fa fa-question-circle bigger-250 green" style="font-size: 80px !important"></i><br>
    <h2 class="green" style="font-weight: bold; font-size: 36px"><span id="txt-nama-poli">-</span></h2>
    <span style="font-size: 24px">Apakah anda yakin akan berkunjung ? </span>
    <br><br><br><br>
    <button type="button" class="btn btn-xs btn-primary" style="height: 45px !important;font-size: 20px;min-width: 320px; background: green !important; border-color: green" onclick="getMenu('kiosk/Kiosk/bpjs')"><i class="fa fa-arrow-left bigger-150"></i> Ganti Tujuan Kunjungan </button>
    <button type="button" onclick="submitForm()" class="btn btn-xs btn-primary" style="height: 45px !important;font-size: 20px;min-width: 320px; background: green !important; border-color: green"><i class="fa fa-print bigger-150"></i> Proses dan Cetak Bukti Pendaftaran</button>
  </div>
  <!-- hidden -->
  <input type="hidden" name="pm_tujuan" value="" id="pm_tujuan">
  <input type="hidden" name="asal_pasien_pm" value="" id="asal_pasien_pm">
  <input type="hidden" class="form-control" id="kode_kelompok_hidden" name="kode_kelompok_hidden" value="3">
  <input type="hidden" name="kode_perusahaan_hidden" value="120" id="kode_perusahaan_hidden">
  

</div>

