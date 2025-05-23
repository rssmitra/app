<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<style>
.datepicker table tr td.disabled, .datepicker table tr td.disabled:hover {
    color: red !important;
    font-weight: bold;
}

</style>
<script>

jQuery(function($) {  

  
    var disableDates = getLiburNasional(<?php echo date('Y')?>);

    $("#tgl_kunjungan_perjanjian").datepicker({

      autoclose: true,    
      todayHighlight: true,
      daysOfWeekDisabled: [0],
      format: 'yyyy-mm-dd',
      beforeShowDay: function(date){
          dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
          if(disableDates.indexOf(dmy) != -1){
              return false;
          }
          else{
              return true;
          }

      }
      // onSelect: function(dateText) {
      //   $(this).change();
      // }
    }).on("change", function() {

        var str_selected_date = this.value;
        console.log(str_selected_date);
        var selected_date = str_selected_date.split("/").join("-");
        var spesialis = $('#klinik_rajal').val();
        var dokter = $('#dokter_rajal').val();
        var jd_id = $('#jd_id').val();
        /*check selected date */

        $.post('<?php echo site_url('Templates/References/CheckSelectedDate') ?>', {date:selected_date, kode_spesialis:spesialis, kode_dokter:dokter, jadwal_id:jd_id} , function(data) {
            // Do something with the request
            if(data.status=='expired'){
              var message = '<div class="alert alert-danger"><strong>Expired Date !</strong><br>Tanggal yang anda pilih sudah lewat atau sedang berjalan.</div>';
              $('#view_msg_kuota').hide('fast');
            }else{
              if(data.day!=$('#selected_day').val() ){
                    var message = '<div class="alert alert-danger"><strong>Tidak Sesuai !</strong><br>Tanggal Kunjungan tidak sesuai dengan jadwal Praktek Dokter yang anda pilih !</div>';
                    // $('#view_msg_kuota').hide('fast');
              }else{
                

                if(data.sisa > 0 ){
                  var msg_kuota = '<p style="font-size: 12px"> <i class="fa fa-check-circle bigger-120 green"></i> Total Pasien Perjanjian '+data.terisi+' orang, Kuota tersedia pada tanggal ini, '+data.sisa+' pasien</p>';

                  var message = '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><p><strong><i class="ace-icon fa fa-check"></i> Selesai ! </strong>Apakah anda akan melanjutkan ke proses berikutnya ?</p><p><button type="submit" id="btnSave" class="btn btn-sm btn-success">Lanjutkan</button><a href="#" onclick="getMenu('+"'"+'booking/regon_booking'+"'"+')" class="btn btn-sm btn-danger">Batalkan</a></p></div>';

                  $('#div_jadwal_hfis').show('fast');

                }else{
                  var msg_kuota = '<p style="color:red; font-weight: bold; font-style: italic"> -Kuota Dokter Penuh-</p>';
                  
                  var message = '<div class="alert alert-danger"><strong>Kuota Penuh !</strong><br>Mohon maaf kuota dokter sudah penuh, silahkan cari tanggal lain !</div>';
                }

                $('#view_msg_kuota').show('fast');
                $('#view_msg_kuota').html(msg_kuota);

              }

            }

            $('#view_last_message').show('fast');
            $('#view_last_message').html(message);
            $("html, body").animate({ scrollTop: "700px" }, "slow");  


        }, 'json');    

    });
 

});

$(document).ready(function(){

    $('#jenis_instalasi').focus();    
    $('#change_modul_view_perjanjian_form').load('registration/Reg_pasien/show_modul/RJ');

    $('#form_booking').ajaxForm({      

      beforeSend: function() {        

        achtungShowFadeIn();          

      },      

      uploadProgress: function(event, position, total, percentComplete) {        

      },      

      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $.achtung({message: jsonResponse.message, timeout:5});          

          setTimeout(function(){
            PopupCenter(jsonResponse.redirect, 'SURAT KONTROL PASIEN', 850, 500);
            //window.open(jsonResponse.redirect, '_blank');
            $("#modalDaftarPerjanjian").modal('hide');
            //$('#page-area-content').load(jsonResponse.redirect);

          },1800);


        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }        

        achtungHideLoader();        

      }      

    });     
  
    $('select[name="jenis_instalasi"]').change(function () {      

        if ($(this).val()) {          
          /*load modul*/
          $('#change_modul_view_perjanjian_form').load('registration/Reg_pasien/show_modul/'+$(this).val());
          $('#tgl_kunjungan_form').hide('fast');
          //$("html, body").animate({ scrollTop: "700px" }, "slow");  
        } else {          
          /*Eksekusi jika salah*/
          $('#tgl_kunjungan_form').hide('fast');
        }        

    });

    $('#perusahaan').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/References/getPerusahaan",
                data: 'keyword=' + query,            
                dataType: "json",
                type: "POST",
                success: function (response) {
                  result($.map(response, function (item) {
                      return item;
                  }));
                }
            });
        },
        afterSelect: function (item) {
          // do what is needed with item
          var val_item=item.split(':')[0];
          console.log(val_item);
          $('#kodePerusahaanHidden').val(val_item);
          if(val_item == 120){
            $('#div_no_sep_lama').show();
          }else{
            $('#div_no_sep_lama').hide();
          }
        }

    });

    $('input[name=jenis_penjamin]').click(function(e){
        var field = $('input[name=jenis_penjamin]:checked').val();
        if ( field == 'Jaminan Perusahaan' ) {
          $('#showFormPerusahaan').show('fast');
        }else if (field == 'Umum') {
          $('#showFormPerusahaan').hide('fast');
        }
      });





})

function formatDate(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  return date.getMonth()+1 + "/" + date.getDate() + "/" + date.getFullYear();
}

function createSuratKontrol(){
  show_modal('ws_bpjs/ws_index?modWs=CreateSuratKontrol&nosep='+$('#no_sep_lama').val()+'&tglRencanaKontrol='+$('#tgl_kunjungan_perjanjian').val()+'', 'Surat Kontrol Rawat Jalan');
}


</script>

<div class="row">

  <div class="col-xs-12">  

    <!-- div.dataTables_borderWrap -->
 
    <div id="user-profile-1" class="user-profile row">
      
      <div class="col-xs-12 col-sm-12">
        

        <form class="form-horizontal" method="post" id="form_booking" action="<?php echo site_url('registration/Reg_pasien/process_perjanjian')?>" enctype="multipart/form-data" autocomplete="off">   

          <!-- hidden form  -->
          <input type="hidden" name="no_mr" value="<?php echo $value->no_mr?>" id="no_mr">
          <input type="hidden" name="nama_pasien" value="<?php echo $value->nama_pasien?>" id="nama_pasien">
          <input type="hidden" name="alamat" value="<?php echo $value->almt_ttp_pasien?>" id="alamat">
          <input type="hidden" name="jd_id" id="jd_id">
          <input type="hidden" name="selected_day" id="selected_day">
          <input type="hidden" name="selected_time" id="selected_time">
          <input type="hidden" name="time_start" id="time_start">
          <input type="hidden" name="id_tc_pesanan" id="id_tc_pesanan" value="<?php echo isset($booking_id)?$booking_id:''?>">
          <input type="hidden" name="kode_booking" id="kode_booking_id" value="<?php echo isset($booking->regon_booking_kode)?$booking->regon_booking_kode:''?>">
          <input type="hidden" name="is_no_mr" id="is_no_mr" value="N">

          <div class="form-group">

              <label class="control-label col-sm-2">*No.MR</label>

              <div class="col-sm-2">

                  <input type="text" name="no_mr_show" class="form-control" id="no_mr_show" value="<?php echo $value->no_mr?>" readonly="">

              </div>

          </div>

          <div class="form-group">
            <label class="control-label col-sm-2">Jenis Penjamin</label>
            <div class="col-md-8">
              <div class="radio">
                    <label>
                      <input name="jenis_penjamin" type="radio" class="ace" value="Jaminan Perusahaan" />
                      <span class="lbl"> Jaminan Perusahaan</span>
                    </label>
                    <label>
                      <input name="jenis_penjamin" type="radio" class="ace" value="Umum" />
                      <span class="lbl"> Umum</span>
                    </label>
              </div>
            </div>
          </div>

          <div class="form-group" id="showFormPerusahaan" style="display:none">
              <label class="control-label col-sm-2">Perusahaan</label>
              <div class="col-sm-4">
                  <input id="perusahaan" name="perusahaan" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />
                  <input id="kodePerusahaanHidden" name="kode_perusahaan" class="form-control"  type="hidden" />
              </div>
          </div>

          <div class="form-group" id="div_no_sep_lama" style="display: none">
            <label class="control-label col-sm-2">No SEP Referensi</label>
            <div class="col-sm-3">
                <input id="no_sep_lama" name="no_sep_lama" class="form-control"  type="text" placeholder="" value=""/>
            </div>
          </div>


          <p style="margin-top:5px"><b><i class="fa fa-ambulance"></i> PILIH INSTALASI </b></p>

          <div class="form-group">
              <label class="control-label col-sm-2">Instalasi</label>
              <div class="col-md-3">
                <select name="jenis_instalasi" id="jenis_instalasi" class="form-control">
                  <option>-Silahkan Pilih-</option>
                  <option value="RJ" selected>Rawat Jalan</option>
                  <option value="PM">Penunjang Medis</option>
                  <option value="BD">Bedah</option>
                </select>
              </div>
            
          </div>

          <div id="change_modul_view_perjanjian_form"></div>
            
          <!-- end change modul view -->

          <div id="tgl_kunjungan_form" style="display:none">
            
            <p><b><i class="fa fa-calendar"></i> KUNJUNGAN </b></p>

            <div class="form-group">
              <label class="control-label col-sm-2">Jenis Perjanjian</label>
              <div class="col-sm-6">
                <div class="checkbox">
                  <label>
                    <input name="jenis_perjanjian" type="checkbox" class="ace" value="1">
                    <span class="lbl"> Rujukan Internal</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              
              <label class="control-label col-sm-2">Tanggal Kunjungan</label>
              
              <div class="col-md-2">                
                <div class="input-group">                    
                    <input name="tanggal_kunjungan" id="tgl_kunjungan_perjanjian" value="" placeholder="yyyy-mm-dd" class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                      <i class="ace-icon fa fa-calendar"></i>
                    </span>
                  </div>
              </div>
              <div id="div_jadwal_hfis" style="display: none" class="no-padding">
                <div class="col-md-2">
                  <button type="button" class="btn btn-primary btn-sm" onclick="createSuratKontrol()">
                      <span class="ace-icon fa fa-calendar icon-on-right bigger-110"></span>
                      Cek Jadwal HFIS
                  </button>
                </div>
                <label class="control-label col-sm-2">No Surat Kontrol</label>
                <div class="col-md-2" style="margin-left: -17px">
                  <input name="no_surat_kontrol" id="noSuratKontrolPerjanjianForm" value="" class="form-control" type="text" readonly>
                </div>
              </div>

            </div>

            <div id="view_msg_kuota" style="margin-top:1px">*) Hari Minggu & Tanggal Merah Libur</div>    
            
            <div class="form-group">
              
              <label class="control-label col-sm-2">Keterangan</label>
              
              <div class="col-md-5">
                
                  <textarea class="form-control" name="keterangan" style="height:50px !important"></textarea>

              </div>

            </div>

          </div>

          <div id="view_last_message" style="margin-top:5px"></div>
          
      </form>

      </div>

    </div>


  </div><!-- /.col -->

</div><!-- /.row -->

