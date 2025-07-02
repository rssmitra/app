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
  console.log(disableDates);
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
    var no_mr = $('#no_mr').val();
    /*check selected date */

    $.post('<?php echo site_url('Templates/References/CheckSelectedDate') ?>', {date:selected_date, kode_spesialis:spesialis, kode_dokter:dokter, jadwal_id:jd_id, no_mr : no_mr} , function(data) {
        // Do something with the request

        if(data.range_visit > 0){
          // show informasi
          $('#div_less_then_31_bpjs').show();
          $('#show_notif_less_then_31').html('<div class="alert alert-danger"><strong>Peringatan!</strong><br>Pasien kurang dari 30 hari pelayanan BPJS. Berpotensi Gagal Rekam Obat Farmasi/ Resep PRB<br>Pasien dapat kontrol kembali diatas tanggal <b>'+data.allow_visit_date+'</b></div>');
        }else{
          $('#div_less_then_31_bpjs').hide();
          $('#show_notif_less_then_31').html('');
        }

        if(data.status=='expired' || data.status == 'cuti'){
          if(data.status == 'expired'){
            var message = '<div class="alert alert-danger"><strong>Expired Date !</strong><br>Tanggal yang anda pilih sudah lewat atau sedang berjalan.</div>';
          }else{
            var message = '<div class="alert alert-danger"><strong>Tidak praktek !</strong><br>Dokter sedang cuti pada tanggal tersebut.</div>';
          }
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
    $('#change_modul_view_perjanjian').load('registration/Reg_pasien/show_modul/'+$('#jenis_instalasi').val()+'?kode_dokter='+$('#kode_dokter_hidden').val()+'&kode_bagian='+$('#kode_bagian_hidden').val()+'' );

    $('#form_booking').ajaxForm({      

      beforeSend: function() {        

        achtungShowLoader();    

      },      

      uploadProgress: function(event, position, total, percentComplete) {        

      },      

      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $.achtung({message: jsonResponse.message, timeout:5});          
          
          $('#content-perjanjian-form').load(jsonResponse.redirect);
          $('#content-perjanjian-form').css("padding","25px");
          
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }        

        achtungHideLoader();        

      }      

    });     

      
    $('select[name="jenis_instalasi"]').change(function () {      

        if ($(this).val()) {          

          /*load modul*/

          $('#change_modul_view_perjanjian').load('registration/Reg_pasien/show_modul/'+$(this).val());
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
          var label_item=item.split(':')[1];
          console.log(val_item);
          $('#perusahaan').val(label_item);
          $('#kodePerusahaanHidden').val(val_item);
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
  show_modal('ws_bpjs/ws_index?modWs=CreateSuratKontrol&nosep='+$('#no_sep_lama').val()+'&tglRencanaKontrol='+$('#tgl_kunjungan_perjanjian').val()+'', 'Surat Kontrol Rawat Jalan')
}





</script>

<div class="row">

  <div class="col-xs-12">  

    <!-- div.dataTables_borderWrap -->
    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <div id="content-perjanjian-form" class="user-profile row">
      
      <div class="col-xs-12 col-sm-12">

        <form class="form-horizontal" method="post" id="form_booking" action="<?php echo site_url('registration/Reg_pasien/process_perjanjian')?>" enctype="multipart/form-data" autocomplete="off">   

          <!-- hidden form  -->
          <input type="hidden" name="no_mr" value="<?php echo $value->no_mr?>" id="no_mr">
          <input type="hidden" name="no_ktp" value="<?php echo $value->no_ktp?>" id="no_ktp">
          <input type="hidden" name="no_kartu_bpjs" value="<?php echo $value->no_kartu_bpjs?>" id="no_kartu_bpjs">
          <input type="hidden" name="nama_pasien" value="<?php echo $value->nama_pasien?>" id="nama_pasien">
          <input type="hidden" name="alamat" value="<?php echo $value->almt_ttp_pasien?>" id="alamat">
          <input type="hidden" name="jd_id" id="jd_id">
          <input type="hidden" name="selected_day" id="selected_day">
          <input type="hidden" name="selected_time" id="selected_time">
          <input type="hidden" name="time_start" id="time_start">
          <input type="hidden" name="id_tc_pesanan" id="id_tc_pesanan" value="<?php echo isset($booking_id)?$booking_id:''?>">
          <input type="hidden" name="kode_booking" id="kode_booking_id" value="<?php echo isset($booking->regon_booking_kode)?$booking->regon_booking_kode:''?>">
          <input type="hidden" name="is_no_mr" id="is_no_mr" value="N">
          <input type="hidden" name="print_booking" id="print_booking" value="Y">
          <input type="hidden" name="kode_dokter_hidden" id="kode_dokter_hidden" value="<?php echo isset($_GET['kode_dokter']) ? $_GET['kode_dokter'] : '';?>">
          <input type="hidden" name="kode_bagian_hidden" id="kode_bagian_hidden" value="<?php echo isset($_GET['kode_bagian']) ? $_GET['kode_bagian'] : '';?>">

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
                      <input name="jenis_penjamin" type="radio" class="ace" value="Jaminan Perusahaan" <?php echo isset($_GET['kode_perusahaan']) ? ($_GET['kode_perusahaan'] != 0) ? 'checked' : '' :'';?>/>
                      <span class="lbl"> Jaminan Perusahaan</span>
                    </label>
                    <label>
                      <input name="jenis_penjamin" type="radio" class="ace" value="Umum" <?php echo isset($_GET['kode_perusahaan']) ? ($_GET['kode_perusahaan'] == 0) ? 'checked' : '' :'';?>/>
                      <span class="lbl"> Umum</span>
                    </label>
              </div>
            </div>
          </div>

          <div class="form-group" id="showFormPerusahaan"  <?php echo isset($_GET['kode_perusahaan']) ? ($_GET['kode_perusahaan'] != 0) ? '' : 'style="display:none"' :'style="display:none"';?> >

              <label class="control-label col-sm-2">Perusahaan</label>
              <div class="col-sm-4">
                  <input id="perusahaan" name="perusahaan" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" value="BPJS KESEHATAN"/>
                  <input id="kodePerusahaanHidden" name="kode_perusahaan" class="form-control"  type="hidden" value="120"/>
              </div>

          </div>

          <?php if($_GET['kode_perusahaan'] == 120) :?>
            <div class="form-group">
              <label class="control-label col-sm-2">No SEP Referensi</label>
              <div class="col-sm-2">
                  <input id="no_sep_lama" name="no_sep_lama" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo $_GET['no_sep']?>"/>
              </div>
            </div>
          <?php endif;?>
          <div class="form-group">
              <label class="control-label col-sm-2">Tgl Kontrol Kembali</label>
              <div class="col-sm-2">
                  <input id="no_sep_lama" name="no_sep_lama" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($riwayat->tgl_kontrol_kembali)?$riwayat->tgl_kontrol_kembali:''?>"/>
              </div>
            </div>

          <p style="margin-top:7px"><b><i class="fa fa-ambulance"></i> PILIH INSTALASI </b></p>

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

          <div id="change_modul_view_perjanjian"></div>
          

          <!-- end change modul view -->

          <div id="tgl_kunjungan_form" style="display:none">
            
            <p><b><i class="fa fa-calendar"></i> KUNJUNGAN </b></p>

            <div class="form-group">
              <label class="control-label col-sm-2">Jenis Perjanjian</label>
              <div class="col-sm-10">
                <div class="radio">
                  <?php echo $this->master->custom_selection_radio(['table' => 'global_parameter', 'where' => ['flag' => 'jeniskunjunganbpjs', 'is_active' => 'Y'], 'id' => 'value', 'name' => 'label'], '','jeniskunjungan','jeniskunjungan', 'ace', '', '')?>
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
          
          <div id="div_less_then_31_bpjs" style="display: none">
            <div id="show_notif_less_then_31"></div>
          </div>
          
          <div id="view_last_message" style="margin-top:7px"></div>
          

        </form>

      </div>

    </div>
    

  </div><!-- /.col -->

</div><!-- /.row -->

