<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>

<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

jQuery(function($) {  

  $('.date-picker').datepicker({    

    autoclose: true,    

    todayHighlight: true    

  })  

  //show datepicker when clicking on the icon

  .next().on(ace.click_event, function(){    

    $(this).prev().focus();    

  });  

});

$(document).ready(function(){


    table_booking = $('#riwayat-booking-table').DataTable({ 
      
      "processing": true, //Feature control the processing indicator.
      
      "serverSide": true, //Feature control DataTables' server-side processing mode.
            
      "ordering": false,

      "paging": false,

      "searching": false,

      "info": false,
      
      // Load data for the table's content from an Ajax source
      
      "ajax": {
          
          "url": "booking/Regon_booking/get_data_booking?kode=0",
          
          "type": "POST"
      
      },

    });


    $('#form_cari_pasien').focus();    

    $('#form_registration').ajaxForm({      

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

          /*show action after success submit form*/
          $("#tabs_detail_pasien").load("registration/reg_pasien/riwayat_kunjungan/"+jsonResponse.no_mr);

          /*hide form rajal*/

          $('#btn_submit').hide('fast');

          $('#change_modul_view').hide('fast');

          $('select[name="jenis_pendaftaran"]').val('');


        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }        

        //achtungHideLoader();        

      }      

    });     

      
    $( "#form_cari_pasien" )    

      .keypress(function(event) {        

        var keycode =(event.keyCode?event.keyCode:event.which);         

        if(keycode ==13){          

          event.preventDefault();          

          if($(this).valid()){            

            $('#btn_search_pasien').focus();            

          }          

          return false;                 

        }        

    });

    $( "#form_cari_pasien_by_kode_booking_id" )    

      .keypress(function(event) {        

        var keycode =(event.keyCode?event.keyCode:event.which);         

        if(keycode ==13){          

          event.preventDefault();          

          if($(this).valid()){            

            $('#btn_search_kode_booking').focus();            

          }          

          return false;                 

        }        

    });      

    $('select[name="jenis_pendaftaran"]').change(function () {      

        $('#change_modul_view').show('fast');

        if ($(this).val()) {          

          /*load modul*/

          $('#change_modul_view').load('registration/Reg_klinik/show_modul/'+$(this).val());

          $('#btn_submit').show('fast');

        } else {          

          /*Eksekusi jika salah*/
          $('#btn_submit').hide('fast');
        }        

    });

    $('select[name="jenis_pendaftaran"]').change(function () {      


        /*change action*/
        if ($(this).val()==1) {          
          $('#form_registration').attr('action', 'registration/Reg_klinik/process');
        } else if ($(this).val()==2) {          
          $('#form_registration').attr('action', 'registration/Reg_ranap/process');
        } else if ($(this).val()==3) {          
          $('#form_registration').attr('action', 'registration/Reg_pm/process');
        } else if ($(this).val()==4) {          
          $('#form_registration').attr('action', 'registration/Reg_igd/process');
        } else if ($(this).val()==5) {          
          $('#form_registration').attr('action', 'registration/Reg_mcu/process');
        } else if ($(this).val()==6) {          
          $('#form_registration').attr('action', 'registration/Reg_odc/process');
        } else if ($(this).val()==7) {          
          $('#form_registration').attr('action', 'registration/Reg_bedah/process');
        }else {   
          /*Eksekusi jika salah*/
          $('#form_registration').attr('action', '#');

        }        

    });

    $('#btn_search_pasien').click(function (e) {      

      e.preventDefault();      

      /*reset modul has selected by other*/

      $('#change_modul_view').hide('fast');

      $('select[name="jenis_pendaftaran"]').val('');

      if( $("#form_cari_pasien").val() == "" ){

        alert('Masukan keyword minimal 3 Karakter !');

        return $("#form_cari_pasien").focus();

      }else{

        achtungShowLoader();

        find_pasien_by_keyword( $("#form_cari_pasien").val() );

      }    

    });   

    $('#btn_search_kode_booking').click(function (e) {      

      e.preventDefault();      

      if( $("#form_cari_pasien_by_kode_booking_id").val() == "" ){

        alert('Masukan Kode Booking !');

        return $("#form_cari_pasien_by_kode_booking_id").focus();

      }else{

        achtungShowLoader();

        $.getJSON("<?php echo site_url('booking/regon_booking/search_booking') ?>?kode=" + $("#form_cari_pasien_by_kode_booking_id").val(), '', function (data) {              

          achtungHideLoader();

          if( data.count == 0){
            $('#booking_result_view_div').hide('fast');
            alert('Kode Booking tidak ditemukan'); return $("#form_cari_pasien_by_kode_booking_id").focus();

          }else{

            var obj_data = data.result;

            $('#booking_result_view_div').show('fast');

            /*put data in form*/
            $('#noMrHidden').val(obj_data.regon_booking_no_mr);
            $('#nama_pasien_hidden').val(data.nama_pasien);

            find_data_booking(obj_data.regon_booking_kode);
            $('#booking_result_view_div').html(data.html);

          }

        });             
        
      }    

    }); 


    $('input[name="tipe_registrasi"]').click(function (e) {
      var value = $(this).val();
      if (value=='onsite') {
        $('#search_mr_form').show('fast');
        $('#search_kode_booking_form').hide('fast');
        $('#form_cari_pasien').focus();
        $('#div_form_onsite').show('fast');
        $('#div_riwayat_pasien').hide('fast');
        $('#div_penangguhan_pasien').hide('fast');
        $('#table_profile_pasien_id').show('fast');
        $('#div_load_after_selected_pasien').hide('fast');
        /*reset all field data*/
        $('#no_mr').text('-');$('#noMrHidden').val('');$('#no_ktp').text('-');$('#nama_pasien').text('-');$('#jk').text('-');$('#umur').text('-');$('#alamat').text('-');$('#noKartuBpjs').val('-');$('#kode_perusahaan').text('-');$('#total_kunjungan').text('-');

      }

      if (value=='online') {
        $('#search_kode_booking_form').show('fast');
        $('#search_mr_form').hide('fast');
        $('#form_cari_pasien_by_kode_booking_id').focus();
        $('#div_form_onsite').hide('fast');
        $('#div_riwayat_pasien').hide('fast');
        $('#booking_result_view_div').hide('fast');
      }

    }); 

    $('#decline_warning').click(function (e) {   
      if (($(this).is(':checked'))) {
        $('#div_load_after_selected_pasien').show('fast');
      }  else{
        $('#div_load_after_selected_pasien').hide('fast');
      }
    });

    /*declare*/
    var kode_booking_val = $("#form_cari_pasien_by_kode_booking_id").val();

    $('#register_now_btn_id').click(function (e) {     
      $('#div_load_after_selected_pasien').show('fast');
      $('#div_riwayat_pasien').show('fast');
    });

    $('#InputKeyPenjamin').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "templates/references/getPerusahaan",
                  data: { keyword:query },            
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
            $('#kode_perusahaan_hidden').val(val_item);
            if( val_item == 120 ){
              $('#form_sep').show();
            }else{
              $('#form_sep').hide();
            }
          }
      });

    /*btn print*/
    $('#btn_barcode_pasien').click(function (e) {   
      var no_mr = $('#noMrHidden').val();
      if( no_mr == '' ){
        alert('Silahkan cari pasien terlebih dahulu !'); return false;
      }else{
        url = 'registration/Reg_pasien/barcode_pasien/'+no_mr+'';
        title = 'Cetak Barcode';
        width = 600;
        height = 450;
        PopupCenter(url, title, width, height);
      }

    });

    $('#btn_gelang_pasien').click(function (e) {   
      var no_mr = $('#noMrHidden').val();
      if( no_mr == '' ){
        alert('Silahkan cari pasien terlebih dahulu !'); return false;
      }else{
        url = 'registration/Reg_pasien/gelang_pasien/'+no_mr+'';
        title = 'Cetak Gelang Pasien';
        width = 800;
        height = 450;
        PopupCenter(url, title, width, height);
      }

    });

    $('#btn_card_member_temp').click(function (e) {   
      var no_mr = $('#noMrHidden').val();
      if( no_mr == '' ){
        alert('Silahkan cari pasien terlebih dahulu !'); return false;
      }else{
        url = 'registration/Reg_pasien/card_member/'+no_mr+'/temp';
        title = 'Cetak Kartu Sementara Pasien';
        width = 400;
        height = 450;
        PopupCenter(url, title, width, height);
      }

    });

    $('#btn_identitas_berobat_pasien').click(function (e) {   
      var no_mr = $('#noMrHidden').val();
      if( no_mr == '' ){
        alert('Silahkan cari pasien terlebih dahulu !'); return false;
      }else{
        url = 'registration/Reg_pasien/identitas_berobat_pasien/'+no_mr;
        title = 'Cetak Identitas Berobat Pasien';
        width = 650;
        height = 550;
        PopupCenter(url, title, width, height);
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


function find_data_booking(kode_booking){
  
    table_booking.ajax.url('booking/Regon_booking/get_data_booking?kode='+kode_booking).load();

}

function select_item_from_modal_pasien(mr){
    
    $("#modalSearchPasien").modal('hide');

    $('#div_load_after_selected_pasien').show('fast');

    $('#div_riwayat_pasien').show('fast');

    find_pasien_by_keyword( mr );


}

function format ( data ) {

    return data.html;

}

function showModal()

{  

  $("#result_text").text('Result for "'+$('#form_cari_pasien').val()+'"');  

  $("#modalSearchPasien").modal();  

}


function showModalFormSep()

{  

  noMr = $('#noMrHidden').val();

  noKartu = $('#noKartuBpjs').val();

  $('#result_text_create_sep').text('PEMBUATAN SURAT ELIGIBILITAS PASIEN (SEP) NOMOR KARTU ('+noKartu+')');

  $('#form_create_sep_content').load('registration/reg_klinik/form_sep/'+noMr+''); 

  $("#modalCreateSep").modal();  

}

function showModalEditPasien()

{  

  noMr = $('#noMrHidden').val();

  if (noMr == '') {

    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  
  }else{

    $('#result_text_edit_pasien').text('UBAH DATA PASIEN NO MR ('+noMr+')');

    $('#form_edit_pasien_modal').load('registration/reg_pasien/form_modal_/'+noMr+''); 

    $("#modalEditPasien").modal();

  }
    
}

function showModalDaftarPerjanjian(booking_id)

{  

  noMr = $('#noMrHidden').val();

  if (noMr == '') {

    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  
  }else{

    $('#result_text_daftar_perjanjian').text('DAFTAR PERJANJIAN PASIEN NO MR ('+noMr+')');

    $('#form_daftar_perjanjian_pasien_modal').load('registration/reg_pasien/form_perjanjian_modal/'+noMr+'?ID='+booking_id); 

    $("#modalDaftarPerjanjian").modal();

  }
    
}

function view_resume_medis(no_registrasi)

{  

    $('#result_text_riwayat_medis').text('RIWAYAT MEDIS ');

    $('#modal_content_view_detail').load('registration/reg_pasien/view_detail_resume_medis/'+no_registrasi+''); 

    $("#modalContentViewDetail").modal();
    
}

function registerNow(no_mr)

{  

    $('#div_form_onsite').show('fast');
    $('#div_load_after_selected_pasien').show('fast');
    $('#table_profile_pasien_id').hide('fast');
    $('#div_photo_profile').hide('fast');
    $('#div_riwayat_pasien').show('fast');
    $('#decline_warning').prop('checked', false);

    /*remove first*/
    $('#result_penangguhan_pasien tbody').remove();

    find_pasien_by_keyword( no_mr );

    
    
}

function find_pasien_by_keyword(keyword){

  $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien') ?>?keyword=" + keyword, '', function (data) {              
          
          achtungHideLoader();

          if( data.count == 0){

            $('#div_load_after_selected_pasien').hide('fast');

            $('#div_riwayat_pasien').hide('fast');
            
            $('#div_penangguhan_pasien').hide('fast');

            /*reset all field data*/
            $('#no_mr').text('-');$('#noMrHidden').val('');$('#no_ktp').text('-');$('#nama_pasien').text('-');$('#jk').text('-');$('#umur').text('-');$('#alamat').text('-');$('#noKartuBpjs').val('-');$('#kode_perusahaan').text('-');$('#total_kunjungan').text('-');

            alert('Data tidak ditemukan'); return $("#form_cari_pasien").focus();

          }

          if( data.count == 1 )     {

            var obj = data.result[0];

            var pending_data_pasien = data.pending; console.log(pending_data_pasien);

            $('#no_mr').text(obj.no_mr);

            $('#noMrHidden').val(obj.no_mr);

            $('#no_ktp').text(obj.no_ktp);

            $('#nama_pasien').text(obj.nama_pasien);

            $('#nama_pasien_hidden').val(obj.nama_pasien);

            $('#jk').text(obj.jen_kelamin);

            $('#umur').text(obj.umur);
            
            $('#umur_saat_pelayanan_hidden').val(obj.umur);

            $('#alamat').text(obj.almt_ttp_pasien);

            $('#noKartuBpjs').val(obj.no_kartu_bpjs);

            if( obj.jen_kelamin == 'L' ){
            
              $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
            
            }else{
              
              $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');

            }
            
            if( obj.kode_perusahaan==120){

              $('#form_sep').show('fast'); 

              showModalFormSep(obj.no_kartu_bpjs,obj.no_mr);

            }else{

              $('#form_sep').hide('fast'); 

            }

            penjamin = (obj.nama_perusahaan==null)?'-':obj.nama_perusahaan;

            $('#kode_perusahaan').text(obj.nama_perusahaan);
            
            $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);
            /*penjamin pasien*/
            $('#kode_kelompok_hidden').val(obj.kode_kelompok);
            $('#InputKeyPenjamin').val(obj.nama_perusahaan);

            $('#total_kunjungan').text(obj.total_kunjungan);

            /*for tabs riwayat*/
            $('#tabs_riwayat_kunjungan_id').attr('data-id', obj.no_mr);
            $('#tabs_riwayat_transaksi_id').attr('data-id', obj.no_mr);
            $("#myTab li").removeClass("active");
            $("#tabs_detail_pasien").html("<div class='alert alert-block alert-success'><p><strong><i class='ace-icon fa fa-check'></i>Selamat Datang!</strong> Untuk melihat Riwayat Kunjungan Pasien dan Transaksi Pasien, Silahkan cari pasien terlebih dahulu !</p></div>");

            if(data.count_pending > 0){

              /*show pending data pasien*/
              
              $('#div_penangguhan_pasien').show('fast');

              $('#div_load_after_selected_pasien').hide('fast');

              $('#div_riwayat_pasien').show('fast');

              $('#result_penangguhan_pasien tbody').remove();

              $.each(pending_data_pasien, function (x, y) {                  

                  dt = new Date(y.tgl_masuk);
                  
                  formatDt = formatDate(dt);
                  
                  if(y.total_ditangguhkan > 0){
                    status = 'Total Ditangguhkan '+y.total_ditangguhkan+'';
                  }else{
                    status = '<label class="label label-danger">Belum dipulangkan</label>';
                  }
                  $('<tr><td>'+y.no_kunjungan+'</td><td>'+y.no_registrasi+'</td><td>'+formatDt+'<td>'+y.poli+'</td><td>'+y.dokter+'</td><td>'+y.penjamin+'</td><td>'+status+'</td></tr>').appendTo($('#result_penangguhan_pasien'));                    

              }); 


            }else{

              $('#div_penangguhan_pasien').hide('fast');

              $('#result_penangguhan_pasien tbody').remove();

              /*show detail form */

              $('#div_load_after_selected_pasien').show('fast');

              $('#div_riwayat_pasien').show('fast');

            }


          }else{              

            $("#result_pasien_data tr").remove();

            $.each(data.result, function (i, o) {                  

                d = new Date(o.tgl_lhr);
                
                e = formatDate(d);
                
                penjamin = (o.nama_perusahaan==null)?'-':o.nama_perusahaan;
                
                umur = (o.umur=='undefined')?'-':o.umur;

                $('<tr><td>'+o.no_mr+'</td><td>'+o.nama_pasien+'</td><td>'+o.tempat_lahir+', '+e+'</td><td>'+umur+'</td><td>'+o.almt_ttp_pasien+'</td><td>'+penjamin+'</td><td align="center"><a href="#" class="btn btn-xs btn-pink" onclick="select_item_from_modal_pasien('+"'"+o.no_mr+"'"+')"><i class="fa fa-arrow-down"></i></a></td></tr>').appendTo($('#result_pasien_data'));                    

            }); 

            showModal();  

          }             

        }); 

}


</script>

<div class="row">

  <div class="col-xs-12">  

    <div class="page-header">    

      <h1>      

        <?php echo $title?>        

        <small>        

          <i class="ace-icon fa fa-angle-double-right"></i>          

          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>          

        </small>        

      </h1>      

    </div>  

    <!-- div.dataTables_borderWrap -->

    <div style="margin-top:-10px">    
      <form class="form-horizontal" method="post" id="form_registration" action="#" enctype="multipart/form-data">      
        
          <br>

          <!-- hidden form -->
          <input type="hidden" value="" name="noMrHidden" id="noMrHidden">
          <input type="hidden" name="flag" value="noKartu">
          <!-- <input type="hidden" name="kode_perusahaan_hidden" value="" id="kode_perusahaan_hidden"> -->
          <input type="hidden" name="kode_kelompok_hidden" value="" id="kode_kelompok_hidden">
          <input type="hidden" name="umur_saat_pelayanan_hidden" value="" id="umur_saat_pelayanan_hidden">
          <input type="hidden" name="nama_pasien_hidden" value="" id="nama_pasien_hidden">
          <input name="noKartuBpjs" id="noKartuBpjs" class="form-control" type="hidden" value="">
          

          <div class="form-group">

            <label class="control-label col-md-2">Tipe Registrasi</label>

            <div class="col-md-5">

              <div class="radio">

                  <label>

                    <input name="tipe_registrasi" type="radio" class="ace" value="onsite" <?php echo isset($value) ? ($value->is_active == 'onsite') ? 'checked="checked"' : '' : 'checked="checked"'; ?>  />

                    <span class="lbl"> Onsite (Datang Langsung)</span>

                  </label>

                  <label>

                    <input name="tipe_registrasi" type="radio" class="ace" value="online" <?php echo isset($value) ? ($value->is_active == 'online') ? 'checked="checked"' : '' : ''; ?> />

                    <span class="lbl"> Online (Via Web atau Mobile)</span>

                  </label>

              </div>

            </div>

            <div class="col-md-5" align="right">
              <a href="#" class="btn btn-xs btn-success" onclick="getMenu('registration/reg_loket')"><i class="ace-icon fa fa-angle-double-left"></i> Kembali ke Loket Utama</a>
              <a href="#" class="btn btn-xs btn-warning" onclick="getMenu('registration/reg_klinik'); return $('#form_cari_pasien').focus();"><i class="fa fa-refresh"></i> Refresh Form</a>
            </div>

          </div>

          <div class="form-group" id="search_mr_form">

            <label class="control-label col-md-2"><b>CARI DATA PASIEN</b></label>            

            <div class="col-md-4">            

              <div class="input-group">

                <input type="text" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien">

                <span class="input-group-btn">

                  <button type="button" id="btn_search_pasien" class="btn btn-default btn-sm">

                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>

                    Search

                  </button>

                </span>

              </div>

            </div>

          </div>

          <div id="search_kode_booking_form" style="display:none">

            <div class="form-group" >

              <label class="control-label col-md-2"><b>KODE BOOKING</b></label>            

              <div class="col-md-4">            

                <div class="input-group">

                  <input type="text" name="kode_booking" id="form_cari_pasien_by_kode_booking_id" class="form-control search-query" placeholder="Kode Booking">

                  <span class="input-group-btn">

                    <button type="button" id="btn_search_kode_booking" class="btn btn-default btn-sm">

                      <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>

                      Search

                    </button>

                  </span>

                </div>

              </div>

            </div>

            <div class="form-group" style="display:none">

              <table id="riwayat-booking-table" class="table table-bordered table-hover">

                <thead>

                  <th width="50px">Kode Booking</th>

                  <th>Data Pasien</th>

                  <th>Tanggal Kunjungan</th>

                  <th>Tujuan Klinik</th>

                  <th>Penjamin</th>

                  <th>Keterangan</th>

                </thead>

                <tbody></tbody>

              </table>

            </div>

            <div class="form-group" id="booking_result_view_div" style="padding-top:10px"></div>

          </div>

          <div id="div_form_onsite">

            <div class="form-group">

              <div class="col-md-10" style="margin-left:-1%">

                <div class="form-group">

                    <table class="table table-bordered table-hover" id="table_profile_pasien_id">

                      <thead>

                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">No MR</th>

                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">NIK</th>

                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Nama Pasien</th>

                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">JK</th>

                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Umur</th>

                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Alamat</th>

                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Penjamin Pasien</th>

                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Total Kunjungan</th>

                      </thead>

                      <tbody>

                        <td><div id="no_mr">-</div></td>

                        <td><div id="no_ktp">-</div></td>

                        <td><div id="nama_pasien">-</div></td>

                        <td align="center"><div id="jk">-</div></td>

                        <td><div id="umur">-</div></td>

                        <td><div id="alamat">-</div></td>

                        <td><div id="kode_perusahaan">-</div></td>

                        <td><div id="total_kunjungan"></div></td>

                      </tbody>
                      <span style="color:red;margin-top:-5%;display:none" id="alert_complate_data_pasien"><i>Silahkan lengkapi data pasien terlebih dahulu</i></span>

                    </table>

                    <a href="#" name="submit" class="btn btn-xs btn-purple" onclick="showModalDaftarPerjanjian(0)">

                      Daftarkan Perjanjian Pasien

                    </a>

                    <a href="#" id="btn_barcode_pasien" name="submit" class="btn btn-xs btn-success">

                      Label Barcode

                    </a>

                    <a href="#" id="btn_gelang_pasien" name="submit" class="btn btn-xs btn-primary">

                      Gelang Pasien RI

                    </a>

                    <a href="#" name="submit" id="btn_card_member_temp" class="btn btn-xs btn-inverse">

                      Kartu Pasien Sementara 

                    </a>

                    <a href="#" name="submit" id="btn_identitas_berobat_pasien" class="btn btn-xs btn-pink">

                      Ringkasan Keluar Masuk Pasien

                    </a>

                    <a href="#" name="submit" class="btn btn-xs btn-danger" onclick="showModalEditPasien()" >

                      Ubah Data Pasien

                    </a>
                    
                </div>

                <br>

                <div id="div_penangguhan_pasien" style="display:none">

                  <div id="accordion" class="accordion-style1 panel-group accordion-style2">
                      <div class="panel panel-default">
                        <div class="panel-heading">
                          <h4 class="panel-title">
                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false">
                              <i class="bigger-110 ace-icon fa fa-angle-right" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                              <b style="color:red">&nbsp;Pasien dalam penangguhan !</b>
                            </a>
                          </h4>
                        </div>

                        <div class="panel-collapse collapse" id="collapseOne" aria-expanded="false" style="height: 0px;">
                          <div class="panel-body">
                            <table class="table table-bordered table-hover" id="result_penangguhan_pasien">
                              <thead>
                              <tr>
                                <th>No Kunjungan</th>
                                <th>No Registrasi</th>
                                <th>Tanggal</th>
                                <th>Poli/Klinik</th>
                                <th>Dokter</th>
                                <th>Penjamin</th>
                                <th>Status</th>
                              </tr>
                              </thead>

                              <tbody>
                                
                              </tbody>
                            </table>
                            <span style="margin-top:-30px !important; font-size:11px">NB : Silahkan hubungi bagian terkait, jika terdapat penangguhan administrasi maka pasien tidak dapat didaftarkan untuk berobat sebelum menyelesaikan tangguhan nya. Jika tidak ada penangguhan silahkan abaikan peringatan ini. </span><br>
                            <div class="checkbox">
                              <label>
                                <input name="form-field-checkbox" type="checkbox" class="ace" value="Y" id="decline_warning">
                                <span class="lbl"> Abaikan peringatan ini.</span>
                              </label>
                            </div>

                          </div>
                        </div>
                      </div>
                  </div>

                </div>

                <div id="div_load_after_selected_pasien" style="display:none">

                  <p><b><i class="fa fa-edit"></i> SILAHKAN ISI FORM DIBAWAH INI</b></p>

                  <div class="form-group">
                    
                    <label class="control-label col-sm-2">Tanggal</label>
                    
                    <div class="col-md-3">
                      
                      <div class="input-group">
                          
                          <input name="tgl_registrasi" id="tgl_registrasi" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
                          <span class="input-group-addon">
                            
                            <i class="ace-icon fa fa-calendar"></i>
                          
                          </span>
                        </div>
                    
                    </div>

                    <label class="control-label col-sm-2">Cetak Kartu</label>

                    <div class="col-md-2">

                      <div class="radio">

                          <label>

                            <input name="cetak_kartu" type="radio" class="ace" value="Baru" <?php echo isset($value) ? ($value->is_active == 'Baru') ? 'checked="checked"' : '' : ''; ?>  />

                            <span class="lbl"> Ya</span>

                          </label>

                          <label>

                            <input name="cetak_kartu" type="radio" class="ace" value="Lama" <?php echo isset($value) ? ($value->is_active == 'Lama') ? 'checked="checked"' : '' : 'checked="checked"'; ?> />

                            <span class="lbl"> Tidak</span>

                          </label>

                      </div>

                    </div>

                  </div>

                  <div class="form-group">

                    <label class="control-label col-sm-2">Penjamin Pasien</label>

                    <div class="col-md-5">

                        <input id="InputKeyPenjamin" class="form-control" name="penjamin" type="text" placeholder="Masukan keyword minimal 3 karakter" />

                        <input type="hidden" name="kode_perusahaan_hidden" value="" id="kode_perusahaan_hidden">

                    </div>

                  </div>

                  <div class="form-group" id="form_sep" style="display:none">

                    <label class="control-label col-sm-2">Nomor SEP</label>            

                     <div class="col-md-4">            

                       <div class="input-group">

                         <input name="noSep" id="noSep" class="form-control" type="text" placeholder="Masukan No SEP">

                         <span class="input-group-btn">

                           <button type="button" class="btn btn-primary btn-sm" onclick="showModalFormSep()">

                             <span class="ace-icon fa fa-file icon-on-right bigger-110"></span>

                             Buat SEP

                           </button>

                         </span>

                       </div>

                     </div>   


                  </div>

                  <div class="form-group">
                    
                    <label class="control-label col-sm-2">Tujuan Pendaftaran</label>
                    
                    <div class="col-md-3">
                      
                      <select name="jenis_pendaftaran" class="form-control">

                        <option value="">-Silahkan Pilih-</option>
                        
                        <option value="1">Rawat Jalan</option>

                        <option value="2">Rawat Inap</option>

                        <option value="3">Penunjang Medis</option>

                        <option value="4">IGD</option>

                        <option value="5">MCU</option>

                        <option value="6">ODC</option>

                        <option value="7">Paket Bedah</option>


                      </select>
                    
                    </div>
                  
                  </div>

                  <!-- change modul view -->

                  <div id="change_modul_view" style="margin-top:10px"> </div>
                  

                  <!-- end change modul view -->

                  <div class="form-group" id="btn_submit" style="display:none">

                      <label class="control-label col-sm-2" for="Province">&nbsp;</label>

                      <div class="col-sm-4" style="margin-left:7px">

                          <button type="submit" name="submit" class="btn btn-xs btn-primary">

                            <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>

                            Submit

                          </button>

                      </div>

                  </div>

                  <div id="message_pasien_pending"></div>

                </div>

              </div>

              <div class="col-md-2" id="div_photo_profile">

                <div class="col-xs-12 col-sm-12">

                  <div>

                    <span class="profile-picture">

                      <img id="avatar" class="editable img-responsive editable-click editable-empty" style="width:300px" alt="" src="<?php echo base_url()?>assets/avatars/nopic.jpg">

                    </span>

                  </div>

                </div>

              </div>

            </div>
          </div>

      </form>

      <hr>
      <!-- TABS  -->

      <div id="div_riwayat_pasien" style="display:none">

          <div class="tabbable">

            <ul class="nav nav-tabs" id="myTab">
              <li>
                <a data-toggle="tab" id="tabs_riwayat_kunjungan_id" href="#" data-id="0" data-url="registration/reg_pasien/riwayat_kunjungan" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_detail_pasien')">
                  <i class="green ace-icon fa fa-history bigger-120"></i>
                  RIWAYAT KUNJUNGAN
                </a>
              </li>

              <li>
                <a data-toggle="tab" data-id="0" data-url="registration/reg_pasien/riwayat_transaksi" id="tabs_riwayat_transaksi_id" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_detail_pasien')" >
                  <i class="red ace-icon fa fa-money bigger-120"></i>
                  TRANSAKSI
                </a>
              </li>
            </ul>

            <div class="tab-content">

              <div id="tabs_detail_pasien">
                <div class="alert alert-block alert-success">
                    <p>
                      <strong>
                        <i class="ace-icon fa fa-check"></i>
                        Selamat Datang!
                      </strong> 
                      Untuk melihat Riwayat Kunjungan Pasien dan Transaksi Pasien, Silahkan cari pasien terlebih dahulu !
                    </p>
                  </div>
              </div>

            </div>

          </div>

      </div>

    </div>

  </div><!-- /.col -->

</div><!-- /.row -->

<!-- MODAL SEARCH PASIEN -->

<div id="modalSearchPasien" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;width:80%">

    <div class="modal-content">

      <div class="modal-header no-padding">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text">Results for ""</span>

        </div>

      </div>

      <div class="modal-body no-padding">

        <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">

          <thead>

            <tr>

              <th>MR</th>

              <th>Nama Pasien</th>

              <th>TTL</th>

              <th>Umur</th>

              <th>Alamat</th>

              <th>Penjamin</th>

              <th>Action</th>

            </tr>

          </thead>

          <tbody id="result_pasien_data">


          </tbody>

        </table>

      </div>

      <div class="modal-footer no-margin-top">

        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

          <i class="ace-icon fa fa-times"></i>

          Close

        </button>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>

<!-- MODAL CREATE SEP -->

<div id="modalCreateSep" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:85%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_create_sep">Pembuatan SEP (Surat Eligibilatas Peserta)</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_create_sep_content"></div>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>

<!-- MODAL CREATE SEP -->

<div id="modalEditPasien" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:75%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_edit_pasien">UBAH DATA PASIEN</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_edit_pasien_modal"></div>

      </div>

      <!-- <div class="modal-footer no-margin-top">

        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

          <i class="ace-icon fa fa-times"></i>

          Close

        </button>

      </div> -->

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>

<!-- MODAL DAFTAR PERJANJIAN -->

<div id="modalDaftarPerjanjian" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:70%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_riwayat_medis">PERJANJIAN PASIEN</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_daftar_perjanjian_pasien_modal"></div>

      </div>

      <!-- <div class="modal-footer no-margin-top">

        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

          <i class="ace-icon fa fa-times"></i>

          Close

        </button>

      </div> -->

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>

<div id="modalContentViewDetail" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:90%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_view_detail">DAFTAR PERJANJIAN PASIEN</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="modal_content_view_detail"></div>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>
