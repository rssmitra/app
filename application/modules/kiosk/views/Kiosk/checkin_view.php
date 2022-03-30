<script>

  function submitForm(){

    var post_data = {
        no_mr : $('#no_mr_val').val(),
        nama_pasien : $('#nama_pasien').val(),
        umur_saat_pelayanan_hidden : $('#umur_saat_pelayanan_hidden').val(),
        kode_kelompok_hidden : $('#kode_kelompok_hidden').val(),
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
          $('#konfirmasi_kunjungan').html('<div class="center" style="padding-top: 20px"><span style="font-size: 36px; font-weight: bold; color: green"><i class="fa fa-check-circle green bigger-250"></i><br>PENDAFTARAN BERHASIL DILAKUKAN!</span><br><span style="font-size: 20px">Silahkan menunggu diruang tunggu pelayanan untuk dipanggil.</span></div><br><div class="center"><a href="#" class="btn btn-lg" style="background : green !important; border-color: green;" onclick="getMenu('+"'kiosk/Kiosk/bpjs'"+')"> <i class="fa fa-arrow-left"></i> Kembali ke Menu Sebelumnya</a> <a href="#" class="btn btn-lg" style="background : green !important; border-color: green"><i class="fa fa-print"></i> Cetak Ulang Bukti Pendaftaran</a></div>');

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

  $('#btnSearchKodeBooking').click(function (e) {
      e.preventDefault();
      findKodeBooking();
  });

  function findKodeBooking(){
      var kodeBooking = $('#kodeBooking').val();
      

      $.ajax({
          url: 'Templates/References/findKodeBooking',
          type: "post",
          data: {kode:kodeBooking},
          dataType: "json",
          beforeSend: function() {
          //   achtungShowLoader();  
          },
          success: function(response) {
          //   achtungHideLoader();
              if(response.status==200){
                var obj = response.data;
                var no_mr = obj.no_mr;
                $('#result-find-kode-booking').show('fast');
                /*text*/
                $('#no_mr_txt').text(obj.no_mr);
                $('#nama_pasien_txt').text(obj.nama);
                $('#tgl_kunjungan_txt').text(obj.tgl_kunjungan);
                $('#tujuan_poli_txt').text(obj.poli);
                $('#dokter_txt').text(obj.nama_dr);
                $('#jam_praktek_txt').text(obj.jam_praktek);

                $('#pnomr').val(obj.no_mr);
                $('#noSuratSKDP').val(kodeBooking);
                $('#kode_poli_bpjs').val(obj.kode_poli_bpjs);

                tgl_kunj = obj.tgl_kunjungan_mdy;
                today = new Date();
                today_mdy = today.toLocaleDateString();
                console.log(today.toLocaleDateString());

                if(today_mdy == tgl_kunj){
                  if(obj.tgl_masuk == '-'){
                    $('#status_txt').html('<span class="label label-success arrowed-in-right">available</span>');
                    $('#btnCheckin').attr('disabled', false);
                  }else{
                    $('#status_txt').html('Sudah terdaftar pada Tanggal '+obj.tgl_masuk);
                    $('#btnCheckin').attr('disabled', true);
                  }
                    
                }else{
                    $('#status_txt').html('<span class="label label-danger arrowed-in-right">not available</span>');
                    $('#btnCheckin').attr('disabled', true);
                }
                //   $('#noMR').val(response.data.no_mr);
                $('#message_result_kode_booking').html('');

              }else{
                // $.achtung({message: data.message, timeout:5});
                $('#result-find-kode-booking').hide('fast');
                $('#btnCheckin').attr('disabled', true);

                $('#message_result_kode_booking').html('<div class="center"><img src="<?php echo base_url()?>assets/kiosk/alert.jpeg" style="width: 100px "><strong><h3>Pemberitahuan !</h3> </strong><span style="font-size: 14px">'+response.message+'</span></div>');

              }
                
          }
      });

  }

  function nextProcess(link){
      $('#load-content-page').load(link+'?kode='+$('#kodeBooking').val());
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
  .profile-info-value{
    text-align: left;
  }
  
</style>

<div class="row" style="top:50%; left: 50%" id="asum_main_view">

  <div class="col-md-2">&nbsp;</div>
  <div class="col-md-8" style="padding-top: 5%">
    <?php if(isset($registrasi->no_registrasi)) :?>
    <div class="alert alert-info">
      <strong>Pemberitahuan!</strong><br>
      Anda sudah pernah terdaftar untuk kunjungan hari ini!
    </div>
    <br>
    <p style="font-weight: bold; font-size: 14px"><i class="fa fa-history"></i> Riwayat Pendafataran Terakhir</p>
    <table class="table table-bordered" style="font-size: 14px">
      <thead>
        <tr>
          <th>No</th>
          <th>No Registrasi</th>
          <th>No MR & Nama Pasien</th>
          <th>Tujuan Poli & Dokter</th>
          <th>Status</th>
          <th>Cetak Ulang</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="center">1</td>
          <td><b><?php echo $registrasi->no_registrasi?></b><br><?php echo $registrasi->tgl_jam_masuk?></td>
          <td><?php echo $registrasi->no_mr?><br><?php echo $registrasi->nama_pasien?></td>
          <td><?php echo ucwords($registrasi->nama_bagian)?><br><?php echo $registrasi->nama_dokter?></td>
          <td class="center">Available</td>
          <td class="center" style="font-size: 14px"><a href="" class="btn btn-lg" style="background : green !important; border-color: green; "><i class="fa fa-print"></i></td>
        </tr>
      </tbody>
    </table>
    <?php else: ?>
      
      <div id="konfirmasi_kunjungan_rj">
        <div class="center" style="padding-top: 30px;">
                  
          <div>
              <label class="" style="font-size: 20px; font-weight: bold">MASUKAN KODE BOOKING</label><br>         
              <div class="input-group input-group-lg">
              <span class="input-group-addon">
                  <i class="ace-icon fa fa-check"></i>
              </span>
              <input type="text" class="form-control" id="kodeBooking" placeholder="" style="height: 55px !important;font-size: 24px !important; padding-left: 12%; text-transform: uppercase; text-align: center" autocomplete="off">
              <span class="input-group-btn">
                  <button type="button" class="btn btn-lg" id="btnSearchKodeBooking" style="height: 55px !important; background: green !important; border-color: green">
                  <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                  Search
                  </button>
              </span>
              </div>
          </div>
          <br>

          <div class="row" id="result-find-kode-booking" style="display: none;padding-left: 25px;">
              
              <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                      <div class="profile-info-name"> Tanggal Kunjungan </div>
                      <div class="profile-info-value">
                          <span class="editable editable-click" id="tgl_kunjungan_txt"></span>
                      </div>
                  </div>
                  <div class="profile-info-row">
                      <div class="profile-info-name"> No MR </div>
                      <div class="profile-info-value">
                          <span class="editable editable-click" id="no_mr_txt"></span>
                      </div>
                  </div>
                  <div class="profile-info-row">
                      <div class="profile-info-name"> Nama Pasien </div>
                      <div class="profile-info-value">
                          <span class="editable editable-click" id="nama_pasien_txt"></span>
                      </div>
                  </div>
                  <div class="profile-info-row">
                      <div class="profile-info-name"> Tujuan Poli/Klinik </div>
                      <div class="profile-info-value">
                          <span class="editable editable-click" id="tujuan_poli_txt"></span>
                      </div>
                  </div>
                  <div class="profile-info-row">
                      <div class="profile-info-name"> Dokter </div>
                      <div class="profile-info-value">
                          <span class="editable editable-click" id="dokter_txt"></span>
                      </div>
                  </div>
                  <div class="profile-info-row">
                      <div class="profile-info-name"> Jam Praktek </div>
                      <div class="profile-info-value">
                          <span class="editable editable-click" id="jam_praktek_txt"></span>
                      </div>
                  </div>
                  <div class="profile-info-row">
                      <div class="profile-info-name"> Status </div>
                      <div class="profile-info-value">
                          <span class="editable editable-click" id="status_txt"></span>
                      </div>
                  </div>
              </div>
          </div>

          <div id="message_result_kode_booking"></div>
          
        </div>
      </div>

    <?php endif; ?>
    <br><br>
    <div class="center">
      <button type="button" class="btn btn-xs btn-primary" style="height: 45px!important; font-size: 20px;min-width: 320px; background: green !important; border-color: green" onclick="getMenu('kiosk/Kiosk/bpjs')"><i class="fa fa-arrow-left bigger-150"></i> Kembali ke Menu Sebelumnya</button> 

      <button type="button" style="height: 45px!important; font-size: 20px;min-width: 320px; background: green !important; border-color: green;" onclick="submitForm()" id="btnCheckin" class="btn btn-xs btn-primary"><i class="fa fa-print bigger-150"></i> Check In</button>
    </div>
    
  </div>
  <div class="col-md-2">&nbsp;</div>

  
  
</div>

