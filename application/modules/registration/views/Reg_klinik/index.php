<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>


<script>

setInterval("update_antrian();",15000); 

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
        // achtungShowFadeIn();          
        $('#divLoadSEP').html('Loading..');
        $('#div_form_onsite').hide();
      },      

      uploadProgress: function(event, position, total, percentComplete) {},      

      complete: function(xhr) {    
        var data=xhr.responseText;    
        var jsonResponse = JSON.parse(data);   
        if(jsonResponse.status === 200){      
          // scroll down
          // $('body,html').animate({ scrollTop: 0 }, 800);
          $.achtung({message: jsonResponse.message, timeout:5});    
          /*jika tujuan pendaftaran MCU maka tampil print out MCU*/
          if( $('select[name="jenis_pendaftaran"]').val() == 5){
            PopupCenter('registration/Reg_mcu/print_checklist_mcu?kode_tarif='+jsonResponse.kode_tarif_mcu+'&nama='+jsonResponse.nama_pasien+'&no_mr='+jsonResponse.no_mr+'&no_reg='+jsonResponse.no_registrasi+'', 'FORM CHEKLIST MCU', 850, 500);
          }

          // bikin sep
          if( jsonResponse.type_pelayanan == 'create_sep' ){
            $('#noSep').val(jsonResponse.no_sep);
            $('#noSepManual').val(jsonResponse.no_sep);
            $('#noKartuBpjs').val(jsonResponse.no_kartu);
            $('#div_form_onsite').hide();
            $('#jenis_pendaftaran').val('1');
            $('#form_registration').attr('action', 'registration/Reg_klinik/processRegisterNSEP');
            $('#divLoadSEP').load('ws_bpjs/Ws_index/view_sep/'+jsonResponse.no_sep);
            // show riwayat kunjungan
            $('#tabs_riwayat_kunjungan_id').click();

            // show_modal('ws_bpjs/Ws_index/view_sep/'+jsonResponse.no_sep+'?no_antrian='+jsonResponse.no_antrian+'');
            // $('#divLoadSEP').load('registration/Reg_klinik/process_sep_success/'+jsonResponse.no_sep+'');
            // $('#change_modul_view_perjanjian').hide('fast') ;
            // $('#change_modul_view_perjanjian').load('registration/Reg_klinik/show_modul/1?no_sep='+jsonResponse.no_sep+'') ;
          }


          // jika tujuan rawat jalan
          // if( $('select[name="jenis_pendaftaran"]').val() == 1 || $('select[name="jenis_pendaftaran"]').val() == 4){
          //   // PopupCenter('registration/Reg_klinik/print_bukti_pendaftaran_pasien?nama='+jsonResponse.nama_pasien+'&no_mr='+jsonResponse.no_mr+'&no_reg='+jsonResponse.no_registrasi+'&poli='+jsonResponse.poli+'&dokter='+jsonResponse.dokter+'&nasabah='+jsonResponse.nasabah+'', 'FORM BUKTI PENDAFTARAN PASIEN', 950, 550);
          //   if( $('#kode_perusahaan_hidden').val() == 120 ){
          //     if($('#noSep').val().length == 19){ // karakter SEP harus 19
          //       show_modal('ws_bpjs/Ws_index/view_sep/'+$('#noSep').val()+'');
          //     }
          //   }
          // }
          console.log(jsonResponse.type_pelayanan);

          if( jsonResponse.type_pelayanan == 'igd' ){
            $('#divLoadSEP').html('<div class="alert alert-success"><b><i class="fa fa-check green bigger-150"></i> Berhasil..!</b> Proses pendaftaran berhasil dilakukan.</div>');
            getMenuTabs('registration/reg_pasien/riwayat_kunjungan/'+jsonResponse.no_mr, 'tabs_detail_pasien');
          }
          if( jsonResponse.type_pelayanan == 'rawat_jalan' ){
            $('#change_modul_view_perjanjian').hide('fast');
            // show bukti registrasi atau SEP
            if( jsonResponse.kode_perusahaan == 120 ){
              // show sep
              $('#divLoadSEP').load('ws_bpjs/Ws_index/view_sep/'+jsonResponse.no_sep+'?no_antrian='+jsonResponse.no_antrian+'');
            }else{
              // show bukti registrasi
              // $('#divLoadSEP').load('registration/Reg_klinik/print_bukti_pendaftaran_pasien_small?no_reg='+jsonResponse.no_registrasi);
              $('#divLoadSEP').html('<div class="alert alert-success"><b><i class="fa fa-check green bigger-150"></i> Berhasil..!</b> Proses pendaftaran berhasil dilakukan.</div>');

              PopupCenter('registration/Reg_klinik/print_bukti_pendaftaran_pasien_small?nama='+jsonResponse.nama_pasien+'&no_mr='+jsonResponse.no_mr+'&no_reg='+jsonResponse.no_registrasi+'&poli='+jsonResponse.poli+'&dokter='+jsonResponse.dokter+'&nasabah='+jsonResponse.nasabah+'', 'FORM BUKTI PENDAFTARAN PASIEN', 950, 550);

              getMenuTabs('registration/reg_pasien/riwayat_kunjungan/'+jsonResponse.no_mr, 'tabs_detail_pasien');

            }
          }

          /*show action after success submit form*/
          // $("#tabs_detail_pasien").load("registration/reg_pasien/riwayat_kunjungan/"+jsonResponse.no_mr);
          /*hide form rajal*/
          // $('#btn_submit').hide('fast');
          // $('#change_modul_view').hide('fast');
          $('#is_new').val('');
          // $('select[name="jenis_pendaftaran"]').val('');
          $('#label_info_pasien_baru').hide('fast');
          $('#pasien_dengan_perjanjian').hide('fast');
          $('#kode_rujukan_hidden').val(0); 
          $('#no_registrasi_hidden').val('');  
          $('#label_info_rujukan').hide('fast');

        }else{
          // bikin sep
          if( jsonResponse.type_pelayanan == 'create_sep' ){
            $('#noSep').val(jsonResponse.no_sep);
            $('#noSepManual').val(jsonResponse.no_sep);
            $('#noKartuBpjs').val(jsonResponse.no_kartu);
            $('#jenis_pendaftaran').val('1');
            $('#form_registration').attr('action', 'registration/Reg_klinik/processRegisterNSEP');
            $('#divLoadSEP').html('<div class="alert alert-success"><b>'+jsonResponse.message+'</b><br>Pasien berhasil didaftarkan namun Nomor SEP belum berhasil diterbitkan, silahkan lakukan update data kunjungan pasien kembali.</div>');
            $('#div_form_onsite').hide();
            $('#tabs_riwayat_kunjungan_id').click();
          }
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }   
      }      
    });     

    $('#form_merge_pasien').ajaxForm({      

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

          $('#modalMergePasien').modal('hide');

          find_pasien_by_keyword( jsonResponse.no_mr );

        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }        

        //achtungHideLoader();        

      }      

    }); 

    
    $('#form_edit_pasien').ajaxForm({      

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

          $('#modalEditPasien').modal('hide');
          console.log(jsonResponse);
          $('#nikPasien').val(jsonResponse.no_ktp);
          find_pasien_by_keyword( jsonResponse.no_mr );

        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }        

        //achtungHideLoader();        

      }      

    }); 

    $('#form_ttd_pasien').ajaxForm({      

      beforeSend: function() {        

        // achtungShowLoader();          

      },      

      uploadProgress: function(event, position, total, percentComplete) {        

      },      

      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          // $.achtung({message: jsonResponse.message, timeout:5});          

          $('#modalTTDPasien').modal('hide');
          find_pasien_by_keyword( jsonResponse.no_mr );


        }else{          

          // $.achtung({message: jsonResponse.message, timeout:5});          

        }        

        // achtungHideLoader();        

      }      

    }); 

      
    $( "#form_cari_pasien" )    

      .keypress(function(event) {        

        var keycode =(event.keyCode?event.keyCode:event.which);         

        if(keycode ==13){          

          event.preventDefault();          

          if($(this).valid()){            

            $('#btn_search_pasien').click();            

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

            $('#btn_search_kode_booking').click();            

          }          

          return false;                 

        }        

    }); 

    $( "#form_cari_pasien_by_kode_perjanjian_id" ).keypress(function(event) {        

        var keycode =(event.keyCode?event.keyCode:event.which);         

        if(keycode ==13){          

          event.preventDefault();          

          if($(this).valid()){            

            $('#btn_search_kode_perjanjian').click();            

          }          

          return false;                 

        }    

    });  
    
    $( "#noRujukan" ).keypress(function(event) {        

        var keycode =(event.keyCode?event.keyCode:event.which);         

        if(keycode ==13){          

          event.preventDefault();          

          if($(this).valid()){            

            $('#btnSearchNoRujukan').click();            

          }          

          return false;                 

        }        
      
    });

    $('select[name="jenis_pendaftaran"]').change(function () {      
        showChangeModul( $(this).val() );        
    });

    $('#btn_search_pasien').click(function (e) {      

      e.preventDefault();      
      $('#divLoadSEP').html('');
      $('#form_sep').hide();
      $('#div_form_onsite').show();
      $('#div_load_perjanjian_form').hide('fast');
      $('#pasien_dengan_perjanjian').hide('fast');
      $('#id_tc_pesanan').val('');
      $('#noSep').val('');

      $('#label_info_rujukan').hide('fast');
      $('#kode_rujukan_hidden').val(0); 
      $('#no_registrasi_hidden').val('');     

      /*reset modul has selected by other*/

      $('#change_modul_view').hide('fast');

      <?php if(!isset($pm)):?>
      $('select[name="jenis_pendaftaran"]').val('');
      <?php endif ?>

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
            find_pasien_by_keyword(obj_data.regon_booking_no_mr);
            $('#nama_pasien_hidden').val(data.nama_pasien);

            find_data_booking(obj_data.regon_booking_kode);
            $('#booking_result_view_div').html(data.html);

          }

        });             
        
      }    

    });

    $('#btn_search_kode_perjanjian').click(function (e) {      

      e.preventDefault();      
      $('#div_load_page_perjanjian').hide('fast');

      if( $("#form_cari_pasien_by_kode_perjanjian_id").val() == "" ){

        alert('Masukan Kode Perjanjian !');

        return $("#form_cari_pasien_by_kode_perjanjian_id").focus();

      }else{

        achtungShowLoader();
        $('#divLoadSEP').html('');
        $.getJSON("<?php echo site_url('templates/References/findKodeBooking') ?>?kode=" + $("#form_cari_pasien_by_kode_perjanjian_id").val(), '', function (response) {              

          achtungHideLoader();
          $('#change_modul_view_perjanjian').html('');

          if( response.status != 200){
            
            $('#perjanjian_result_view_div').hide('fast');
            $('#search_kode_perjanjian_result').hide('fast');
            $('#div_load_after_selected_pasien_perjanjian').hide('fast');
            alert('Kode Perjanjian tidak ditemukan'); return $("#form_cari_pasien_by_kode_perjanjian_id").focus();

          }else{

            var obj_data = response.data;

            $('#perjanjian_result_view_div').show('fast');
            $('#search_kode_perjanjian_result').show('fast');
            $('#div_load_after_selected_pasien_perjanjian').show('fast');
            $('#noRujukan').val(obj_data.norujukan);
            /*put data in form*/
            $('#noMrHidden').val(obj_data.no_mr);
            find_pasien_by_keyword(obj_data.no_mr);
            $('#id_tc_pesanan').val(obj_data.id_tc_pesanan);
            $('#nama_pasien_hidden').val(obj_data.nama);
            $('#kodeDokterDPJPPerjanjianBPJS').val(obj_data.kode_dokter_bpjs);
            $('#kodeDokterDPJPPerjanjian').val(obj_data.kode_dokter);
            $('#kodePoliPerjanjian').val(obj_data.kode_poli);
            $('#namaDokterDPJPPerjanjianBPJS').val(obj_data.nama_dr);
            $('#perjanjian_result_view_div').html(response.html);
            $('#btnSearchNoRujukan').click();

          }

        });             
        
      }    

    }); 


    $('input[name="tipe_registrasi"]').click(function (e) {
      var value = $(this).val();

      if (value=='onsite') {
        $('#search_mr_form').show('fast');
        $('#form_cari_pasien').focus();
        $('#div_form_onsite').show('fast');
        $('#table_profile_pasien_id').show('fast');

        $('#search_kode_booking_form').hide('fast');
        $('#search_kode_booking_result').hide('fast');
        $('#div_riwayat_pasien').hide('fast');
        $('#div_penangguhan_pasien').hide('fast');
        $('#div_load_after_selected_pasien').hide('fast');
        $('#search_kode_perjanjian_form').hide('fast');
        $('#search_kode_perjanjian_result').hide('fast');
        $('#div_load_after_selected_pasien_perjanjian').hide('fast');
        $('#search_kode_perjanjian_result').hide('fast');
        $('#perjanjian_result_view_div').hide('fast');

        /*reset all field data*/
        $('#no_mr').text('-');$('#noMrHidden').val('');$('#no_ktp').text('-');$('#nama_pasien').text('-');$('#jk').text('-');$('#umur').text('-');$('#alamat').text('-');$('#noKartuBpjs').val('-');$('#kode_perusahaan').text('-');$('#total_kunjungan').text('-');$('#noRujukan').val('-');
      }

      if (value=='online') {
        $('#search_kode_booking_form').show('fast');
        $('#search_kode_booking_result').show('fast');
        $('#form_cari_pasien_by_kode_booking_id').focus();

        $('#search_mr_form').hide('fast');
        $('#div_form_onsite').hide('fast');
        $('#div_riwayat_pasien').hide('fast');
        $('#booking_result_view_div').hide('fast');
      }

      if (value=='perjanjian') {
        $('#search_kode_perjanjian_form').show('fast');
        $('#search_kode_perjanjian_result').show('fast');
        $('#form_cari_pasien_by_kode_perjanjian_id').focus();

        $('#search_mr_form').hide('fast');
        $('#div_form_onsite').hide('fast');
        $('#div_riwayat_pasien').hide('fast');
        $('#booking_result_view_div').hide('fast');
      }

      $('#div_load_perjanjian_form').hide();

    }); 

    $('#decline_warning').click(function (e) {   
      if (($(this).is(':checked'))) {
        $('#div_load_after_selected_pasien').show('fast');
      }  else{
        $('#div_load_after_selected_pasien').hide('fast');
      }
    });

    $('#post_ranap').click(function (e) {   
      if (($(this).is(':checked'))) {
        $('#form_sep').hide(); 
      }  else{
        $('#form_sep').show(); 
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
          var label_item=item.split(':')[1];
          console.log(val_item);
          $('#InputKeyPenjamin').val(label_item);
          $('#kode_perusahaan_hidden').val(val_item);
          if(val_item == 120 && $('#jenis_pendaftaran').val() == 1){
            showChangeModul(1);
          }else{
            $('#jenis_pendaftaran').val('');
            $('#form_sep').hide(); 
          }
        }
    });

    $('#InputKeyFaskes').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getFaskes",
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
          var label_item=item.split(':')[1];
          $('#inputKeyFaskes').val(label_item);
          $('#kode_faskes_hidden').val(val_item);
        }
    });

    $('#InputKeyNasabah').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "templates/references/getKelompokNasabah",
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
            var label_item=item.split(':')[1];
            console.log(val_item);
            $('#InputKeyNasabah').val(label_item);
            $('#kode_kelompok_hidden').val(val_item);
            $('#kode_perusahaan_hidden').val('');
            $('#InputKeyPenjamin').focus();
            $('#InputKeyPenjamin').val('');
            $('#form_sep').hide('fast');
            
            
            // if(val_item !== 3){
            //   $('#kode_perusahaan_hidden').val('');
            //   $('#InputKeyPenjamin').val('');
            // }  
          }
    });

    /*btn print*/
    $('#btn_barcode_pasien').click(function (e) {   
      var no_mr = $('#noMrHidden').val();
      if( no_mr == '' ){
        alert('Silahkan cari pasien terlebih dahulu !'); return false;
      }else{
        url = 'registration/Reg_pasien/barcode_pasien/'+no_mr+'/1';
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
        width = 600;
        height = 550;
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

    $('#btn_hide_rujukan_label').click(function (e) {  
      e.preventDefault(); 
      $('#label_info_rujukan').hide('fast');
      $('#kode_rujukan_hidden').val(0);
    });

     $('#btn_hide_pasien_baru_label').click(function (e) {  
      e.preventDefault(); 
      $('#label_info_pasien_baru').hide('fast');
      $('#is_new').val('');
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

// $('select[name="jeniskunjunganbpjs"]').change(function () {      

//   if ( $(this).val() == 1 ) {
//       $('#norujukanbpjs').attr('disabled', false);
//   }else{
//       $('#norujukanbpjs').attr('disabled', true);
//   }

// });

function hideLabelPerjanjian(){
  preventDefault();
  $('#pasien_dengan_perjanjian').hide('fast');
  $('#id_tc_pesanan').val('');
}

function showChangeModul(modul_id, id_tc_pesanan=''){

    $('#div_load_after_selected_pasien').show('fast');
    $('#change_modul_view').show('fast');

    if ( modul_id )  {          

      /*load modul*/

      $('#change_modul_view').load('registration/Reg_klinik/show_modul/'+ modul_id +'/' + id_tc_pesanan) ;

      $('#btn_submit').show('fast');

    } else {          

      /*Eksekusi jika salah*/
      $('#btn_submit').hide('fast');
    }

    /*change action*/
    if ( modul_id ==1 ) {     
      // if bpjs show rujukan
      if( $('#kode_perusahaan_hidden').val() == 120){
          $('#form_sep').show();
          $('#change_modul_view_perjanjian').show();
          $('#change_modul_view_perjanjian').load('registration/Reg_klinik/show_modul/8/'+id_tc_pesanan+'') ;
          $('#form_registration').attr('action', 'registration/Reg_klinik/processRegisterNSEP');
      }else{
         $('#form_sep').hide(); 
         $('#form_registration').attr('action', 'registration/Reg_klinik/process');
      }
      $('#reg_klinik_rajal option').remove();     
    } else if ( modul_id ==2 ) {          
      $('#form_sep').hide(); 
      $('#form_registration').attr('action', 'registration/Reg_ranap/process');
    } else if ( modul_id ==3 ) {          
      $('#form_sep').hide(); 
      $('#form_registration').attr('action', 'registration/Reg_pm/process');
    } else if ( modul_id ==4 ) {          
      $('#form_sep').hide(); 
      $('#form_registration').attr('action', 'registration/Reg_igd/process');
    } else if ( modul_id ==5 ) {       
      $('#form_sep').hide();    
      $('#form_registration').attr('action', 'registration/Reg_mcu/process');
    } else if ( modul_id ==6 ) {          
      $('#form_sep').hide(); 
      $('#form_registration').attr('action', 'registration/Reg_odc/process');
    } else if ( modul_id ==7 ) {          
      $('#form_sep').hide(); 
      $('#form_registration').attr('action', 'registration/Reg_bedah/process');
    }else if ( modul_id ==8 ) {    
      $('#form_sep').hide();       
      $('#form_registration').attr('action', 'registration/Reg_klinik/processRegisterNSEP');
    }else {   
      /*Eksekusi jika salah*/
      $('#form_sep').hide(); 
      $('#form_registration').attr('action', '#');

    } 

}

function click_pasien(no_mr){
  $('#form_cari_pasien').val(no_mr);
  $('#btn_search_pasien').click();
}

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

    find_pasien_by_mr( mr );


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
  //alert(noMr); return false;
  if (noMr == '') {

    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  
  }else{

    $('#result_text_edit_pasien').text('UBAH DATA PASIEN NO MR ('+noMr+')');

    $('#form_edit_pasien_modal').load('registration/reg_pasien/form_modal_/'+noMr+''); 

    $("#modalEditPasien").modal();

  }
    
}

function showModalTTD()
{  

  noMr = $('#noMrHidden').val();
  //alert(noMr); return false;
  if (noMr == '') {

    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  
  }else{

    // PopupCenter('registration/reg_pasien/form_modal_ttd/'+noMr+'', 'TANDA TANGAN PASIEN (DIGITAL SIGNATURE)', 900, 500);
    $('#result_text_edit_pasien').text('TANDA TANGAN PASIEN');

    $('#form_pasien_modal_ttd').load('registration/reg_pasien/form_modal_ttd/'+noMr+''); 

    $("#modalTTDPasien").modal();

  }
    
}

function showModalMergePasien()

{  

  noMr = $('#noMrHidden').val();
  //alert(noMr); return false;
  if (noMr == '') {

    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  
  }else{

    $('#result_text_merge_pasien').text('MERGE DATA PASIEN NO MR ('+noMr+')');

    $('#form_merge_pasien_modal').load('registration/reg_pasien/form_modal_merge_pasien/'+noMr+''); 

    $("#modalMergePasien").modal();

  }
    
}

function showModalDaftarReschedule(booking_id)

{  

  noMr = $('#noMrHidden').val();

  if (noMr == '') {

    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  
  }else{

    $('#result_text_daftar_perjanjian').text('DAFTAR PERJANJIAN PASIEN NO MR ('+noMr+')');

    $('#form_daftar_perjanjian_pasien_modal').load('registration/reg_pasien/form_reschedule_modal/'+noMr+'?ID='+booking_id); 

    $("#modalDaftarPerjanjian").modal();

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
    preventDefault();
    
    $('#result_text_riwayat_medis').text('RIWAYAT MEDIS ');

    $('#modal_content_view_detail').load('registration/reg_pasien/view_detail_resume_medis/'+no_registrasi+''); 

    $("#modalContentViewDetail").modal();
    
}

function changeModulRjFromPerjanjian(id_tc_pesanan, kode_dokter, kode_klinik, no_kontrol){

    preventDefault();
    $("input[name=tipe_registrasi][value=onsite]").prop('checked', true);
  
    $('#search_mr_form').show('fast');
    $('#form_cari_pasien').focus();
    $('#div_form_onsite').show('fast');
    $('#table_profile_pasien_id').show('fast');

    $('#search_kode_booking_form').hide('fast');
    $('#search_kode_booking_result').hide('fast');
    $('#div_riwayat_pasien').hide('fast');
    $('#div_penangguhan_pasien').hide('fast');
    $('#div_load_after_selected_pasien').hide('fast');
    $('#search_kode_perjanjian_form').hide('fast');
    $('#search_kode_perjanjian_result').hide('fast');
    $('#div_load_after_selected_pasien_perjanjian').hide('fast');
    $('#search_kode_perjanjian_result').hide('fast');
    $('#perjanjian_result_view_div').hide('fast');

    $('#jenis_pendaftaran option[value=1]').attr('selected','selected');

    showChangeModul(1, id_tc_pesanan);

    $('#reg_klinik_rajal').focus();

    $.getJSON("<?php echo site_url('Templates/References/getKlinikById') ?>/" + kode_klinik, '', function (response) {
        $('#reg_klinik_rajal option').remove(); 
        $.each(response, function (i, o) {                  
            $('<option value="' + o.kode_bagian + '" selected>' + o.nama_bagian.toUpperCase() + '</option>').appendTo($('#reg_klinik_rajal'));
        });               
    });

    $.getJSON("<?php echo site_url('Templates/References/getDokterById') ?>/" + kode_dokter, '', function (data) {    
        $('#reg_dokter_rajal option').remove(); 
        $.each(data, function (i, o) {                  
            $('<option value="' + o.kode_dokter + '" selected>' + o.nama_pegawai + '</option>').appendTo($('#reg_dokter_rajal'));
        });               
    });
            
    $('#id_tc_pesanan').val(id_tc_pesanan);  
    $('#pasien_dengan_perjanjian').show('fast');          
    $('#pasien_dengan_perjanjian').html('<div style="margin-top:3px"><a href="#" onclick="hideLabelPerjanjian()"><i class="fa fa-times-circle bigger-150 red"></i></a> <label class="label label-warning"><i class="fa fa-exchange"></i> <b> PASIEN DENGAN PERJANJIAN NOMOR '+no_kontrol+' </b> </label> </div>');            

}

function changeModulRjFromPerjanjianBPJS(id_tc_pesanan, kode_dokter, kode_klinik, no_kontrol){

    preventDefault();
    $("input[name=tipe_registrasi][value=perjanjian]").prop('checked', true);
    $("input[name=tipe_registrasi]").click();
    $("#form_cari_pasien_by_kode_perjanjian_id").val(no_kontrol);
    $("#btn_search_kode_perjanjian").click();
            
    $('#id_tc_pesanan').val(id_tc_pesanan);  
    $('#pasien_dengan_perjanjian').show('fast');          
    $('#pasien_dengan_perjanjian').html('<div style="margin-top:3px"><a href="#" onclick="hideLabelPerjanjian()"><i class="fa fa-times-circle bigger-150 red"></i></a> <label class="label label-warning"><i class="fa fa-exchange"></i> <b> PASIEN DENGAN PERJANJIAN NOMOR '+no_kontrol+' </b> </label> </div>');            

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

  var search_by = $('input[name="search_by"]:checked').val();
  // before send
  if(search_by == 'no_mr'){
    if(keyword.length < 8){
      alert('Pencarian dengan Nomor Rekam Medis minimal 8 karakter');
      reset_all_profile();
      achtungHideLoader();     
      return false; 
    }
  }

  if(search_by == 'no_ktp'){
    if(keyword.length < 16){
      alert('Pencarian dengan NIK minimal 16 karakter');
      reset_all_profile();
      achtungHideLoader();     
      return false; 
    }
  }

  if(search_by == 'no_kartu_bpjs'){
    if(keyword.length < 10){
      alert('Pencarian dengan Nomor Kartu BPJS minimal 10 karakter');
      reset_all_profile();
      achtungHideLoader();     
      return false; 
    }
  }

  if(search_by == 'nama_pasien'){
    if(keyword.length < 3){
      alert('Pencarian dengan Nama Pasien minimal 3 karakter');
      reset_all_profile();
      achtungHideLoader();     
      return false; 
    }
  }

  $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien') ?>?keyword=" + keyword + "&search_by="+search_by+"&tgl_kunjungan="+$('#tgl_registrasi').val()+"" , '', function (data) {      
          achtungHideLoader();          

          if( data.count == 0){

            $('#div_load_after_selected_pasien').hide('fast');

            $('#div_riwayat_pasien').hide('fast');
            
            $('#div_penangguhan_pasien').hide('fast');

            /*reset all field data*/
            // $('#no_mr').text('-');$('#noMrHidden').val('');$('#no_ktp').text('-');$('#nama_pasien').text('-');$('#jk').text('-');$('#umur').text('-');$('#alamat').text('-');$('#noKartuBpjs').val('-');$('#kode_perusahaan').text('-');$('#total_kunjungan').text('-');

            reset_all_profile();

            alert('Data tidak ditemukan'); return $("#form_cari_pasien").focus();

          }

          if( data.count == 1 )     {

            var obj = data.result[0];

            var pending_data_pasien = data.pending; 
            
            var umur_pasien = hitung_usia(obj.tgl_lhr);
            // console.log(pending_data_pasien);
            // console.log(hitung_usia(obj.tgl_lhr));

            $('#no_mr').text(obj.no_mr);
            $('#noMrHidden').val(obj.no_mr);
            $('#no_ktp').text(obj.no_ktp);
            $('#nikPasien').val(obj.no_ktp);
            $('#nama_pasien').text(obj.nama_pasien+' ('+obj.jen_kelamin+')');
            $('#nama_pasien_hidden').val(obj.nama_pasien);
            $('#jk').text(obj.jen_kelamin);
            $('#umur').text(umur_pasien+' Tahun');
            $('#tgl_lhr').text(getFormattedDate(obj.tgl_lhr));
            $('#umur_saat_pelayanan_hidden').val(umur_pasien);
            $('#alamat').text(obj.almt_ttp_pasien);
            $('#hp').text(obj.no_hp);
            $('#no_telp').text(obj.tlp_almt_ttp);
            $('#hpPasien').val(obj.no_hp);
            $('#noTelpPasien').val(obj.tlp_almt_ttp);
            $('#catatan_pasien').text(obj.keterangan);
            $('#ttd_pasien').attr('src', obj.ttd);
            $('#noKartuBpjs').val(obj.no_kartu_bpjs);

            if( obj.url_foto_pasien ){

              $('#avatar').attr('src', '<?php echo base_url()?>uploaded/images/photo/'+obj.url_foto_pasien+'');

            }else{

              if( obj.jen_kelamin == 'L' ){
            
                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
              
              }else{
                
                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');

              }

            }

            penjamin = (obj.nama_perusahaan==null)?obj.nama_kelompok:obj.nama_perusahaan;
            kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;

            $('#kode_perusahaan').text(penjamin);
            
            $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);
            /*penjamin pasien*/
            $('#kode_kelompok_hidden').val(obj.kode_kelompok);

            $('#InputKeyPenjamin').val(obj.nama_perusahaan);
            $('#InputKeyNasabah').val(obj.nama_kelompok.toUpperCase());

            $('#total_kunjungan').text(obj.total_kunjungan);

            /*for tabs riwayat*/
            $('#tabs_riwayat_kunjungan_id').attr('data-id', obj.no_mr);
            $('#tabs_riwayat_transaksi_id').attr('data-id', obj.no_mr);
            $('#tabs_riwayat_perjanjian_id').attr('data-id', obj.no_mr);
            $('#tabs_riwayat_booking_online_id').attr('data-id', obj.no_mr);
            $('#tabs_konfirm_fp_id').attr('data-id', obj.no_kartu_bpjs);

            $("#myTabRegistrationMenu li").removeClass("active");
            // show riwayat perjanjian as default
            getMenuTabs('registration/reg_pasien/riwayat_kunjungan/'+obj.no_mr, 'tabs_detail_pasien');

            // $("#tabs_detail_pasien").html("<div class='alert alert-block alert-success center'><p><strong><i class='ace-icon fa fa-glass bigger-150'></i><br>Selamat Datang!</strong><br>Untuk melihat Riwayat Kunjungan Pasien dan Transaksi Pasien, Silahkan cari pasien terlebih dahulu !</p></div>");

            if(data.count_pending > 0){

              /*show pending data pasien*/
              
              $('#div_penangguhan_pasien').show('fast');

              $('#div_load_after_selected_pasien').hide('fast');

              $('#div_riwayat_pasien').show('fast');

              $('#result_penangguhan_pasien tbody').remove();

              var firstObj = pending_data_pasien[0];
              tipe_daftar = (firstObj.tipe_daftar == null) ? 'on the spot' : firstObj.tipe_daftar;
              $('#tipe_daftar_txt').html('<b>['+tipe_daftar+']</b>');
              $('#tgl_daftar_txt').html('<b>['+firstObj.tgl_masuk+']</b>');

              $.each(pending_data_pasien, function (x, y) {                  

                  dt = new Date(y.tgl_masuk);
                  
                  formatDt = formatDate(dt);
                  
                  if(y.total_ditangguhkan > 0){
                    status = '<span style="font-weight:bold; color: red">Belum bayar '+y.total_ditangguhkan+'</span>';
                  }else{
                    status = '<label class="label label-danger">Belum dilayani</label>';
                  }
                  $('<tr><td>'+formatDt+'</td><td>'+y.poli+'</td><td>'+y.dokter+'</td><td>'+y.penjamin+'</td><td width="100px">'+status+'</td></tr>').appendTo($('#result_penangguhan_pasien'));                    

              }); 


            }else{

              $('#div_penangguhan_pasien').hide('fast');

              $('#result_penangguhan_pasien tbody').remove();

              /*show detail form */

              $('#div_load_after_selected_pasien').show('fast');

              $('#div_riwayat_pasien').show('fast');

            }

            // info pasien bpjs kurang dari 31 hari
            if(obj.kode_perusahaan == 120){
              $('#no_kartu_bpjs_txt').text('('+obj.no_kartu_bpjs+')');

              obj_visit = data.last_visit;
              if(obj_visit.range > 0){
                // show notif
                $('#div_less_then_31_bpjs').show();
                $('#show_notif_less_then_31').html('<div class="alert alert-danger"><strong>Peringatan!</strong><br>Pasien kurang dari 30 hari pelayanan BPJS. Berpotensi Gagal Rekam Obat Farmasi/ Resep PRB dan tidak bisa diberikan obat</div>');
                $('#min_30_hari_bpjs').val(1);
              }else{
                $('#div_less_then_31_bpjs').hide();
                $('#show_notif_less_then_31').html('');
                $('#min_30_hari_bpjs').val('');
              }

                var konsul_internal = data.konsul_internal;
                // console.log(konsul_internal);
                // Jika data konsul_internal ditemukan dan ada isinya
                if (konsul_internal && konsul_internal.length > 0) {
                  $('#div_konsul_internal').show();
                  $('#show_notif_konsul_internal').html('<div class="alert alert-danger"><strong>Peringatan!</strong><br>Pasien memiliki riwayat Konsultasi Internal. Silahkan hubungi dokter yang bersangkutan untuk mendapatkan informasi lebih lanjut</div>');
                  $('#konsul_internal').val(1);
                } else {
                  $('#div_konsul_internal').hide();
                  $('#show_notif_konsul_internal').html('');
                  $('#konsul_internal').val('');
                }

            }else{
              $('#no_kartu_bpjs_txt').text('');
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


    <?php if(isset($pm)):?>
      showChangeModul( 3 );
    <?php endif ?>

}

function reset_all_profile(){
  /*reset all field data*/
  $('#no_mr').text('-');$('#noMrHidden').val('');$('#no_ktp').text('-');$('#nama_pasien').text('-');$('#jk').text('-');$('#umur').text('-');$('#alamat').text('-');$('#noKartuBpjs').val('-');$('#kode_perusahaan').text('-');$('#total_kunjungan').text('-');
  $('#total_kunjungan, #tgl_lhr, #hp, #no_telp, #no_kartu_bpjs_txt, #catatan_pasien').text('-');
  $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
  $('#ttd_pasien').attr('src', '');
  return true;
}

function find_pasien_by_mr(keyword){  

    $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien_by_mr') ?>?keyword=" + keyword, '', function (data) {      
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

              var pending_data_pasien = data.pending; 
              var umur_pasien = hitung_usia(obj.tgl_lhr);
              console.log(pending_data_pasien);
              console.log(hitung_usia(obj.tgl_lhr));
              $('#no_mr').text(obj.no_mr);
              $('#noMrHidden').val(obj.no_mr);
              $('#no_ktp').text(obj.no_ktp);
              $('#nikPasien').val(obj.no_ktp);
              $('#hpPasien').val(obj.no_hp);
              $('#noTelpPasien').val(obj.tlp_almt_ttp);
              $('#nama_pasien').text(obj.nama_pasien+' ('+obj.jen_kelamin+')');
              $('#nama_pasien_hidden').val(obj.nama_pasien);
              $('#jk').text(obj.jen_kelamin);
              $('#umur').text(umur_pasien+' Tahun');
              $('#tgl_lhr').text(getFormattedDate(obj.tgl_lhr));              
              $('#umur_saat_pelayanan_hidden').val(umur_pasien);
              $('#alamat').text(obj.almt_ttp_pasien);
              $('#hp').text(obj.no_hp);
              $('#no_telp').text(obj.tlp_almt_ttp);
              $('#catatan_pasien').text(obj.keterangan);
              $('#ttd_pasien').attr('src', obj.ttd);
              $('#noKartuBpjs').val(obj.no_kartu_bpjs);

              if( obj.url_foto_pasien ){
                $('#avatar').attr('src', '<?php echo base_url()?>uploaded/images/photo/'+obj.url_foto_pasien+'');
              }else{
                if( obj.jen_kelamin == 'L' ){
                  $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
                }else{
                  $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');
                }
              }

              if( obj.kode_perusahaan==120){

                $('#form_sep').show('fast'); 
                $('#no_kartu_bpjs_txt').text('('+obj.no_kartu_bpjs+')');
                
                //showModalFormSep(obj.no_kartu_bpjs,obj.no_mr);
                
              }else{
                
                $('#form_sep').hide('fast'); 
                $('#no_kartu_bpjs_txt').text('');

              }

              penjamin = (obj.nama_perusahaan==null)?obj.nama_kelompok:obj.nama_perusahaan;
              kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;

              $('#kode_perusahaan').text(penjamin);
              
              $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);
              /*penjamin pasien*/
              $('#kode_kelompok_hidden').val(obj.kode_kelompok);

              $('#InputKeyPenjamin').val(obj.nama_perusahaan);
              $('#InputKeyNasabah').val(obj.nama_kelompok.toUpperCase());

              $('#total_kunjungan').text(obj.total_kunjungan);

              /*for tabs riwayat*/
              $('#tabs_riwayat_kunjungan_id').attr('data-id', obj.no_mr);
              $('#tabs_riwayat_transaksi_id').attr('data-id', obj.no_mr);
              $('#tabs_riwayat_perjanjian_id').attr('data-id', obj.no_mr);
              $('#tabs_riwayat_booking_online_id').attr('data-id', obj.no_mr);

              $("#myTabRegistrationMenu li").removeClass("active");
              // show riwayat perjanjian as default
              getMenuTabs('registration/reg_pasien/riwayat_perjanjian/'+obj.no_mr, 'tabs_detail_pasien');

              // $("#tabs_detail_pasien").html("<div class='alert alert-block alert-success center'><p><strong><i class='ace-icon fa fa-glass bigger-150'></i><br>Selamat Datang!</strong><br>Untuk melihat Riwayat Kunjungan Pasien dan Transaksi Pasien, Silahkan cari pasien terlebih dahulu !</p></div>");

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

    <?php if(isset($pm)):?>
      showChangeModul( 3 );
    <?php endif ?>

}

$('#btnSearchNoRujukan').click(function (e) {
    e.preventDefault();

    var field = $('input[name=find_member_by]:checked').val();
    var jenis_faskes_pasien = $('#jenis_faskes_pasien').val();
    var flag = $('input[name=find_member_by]:checked').val();
    var noRujukan = $('#noRujukan').val();
    var idTcPesanan = $('#id_tc_pesanan').val();

    $('#form_registration').attr('action', 'registration/Reg_klinik/processRegisterNSEP');

    e.preventDefault();
    $.ajax({
      url: 'ws_bpjs/ws_index/searchRujukan',
      type: "post",
      data: {flag:flag, keyvalue:noRujukan, jenis_faskes:jenis_faskes_pasien, noKartuBPJS: $('#noKartuBpjs').val() },
      dataType: "json",
      beforeSend: function() {
        achtungShowLoader();  
      },
      success: function(data) {
        achtungHideLoader();
        if(data.status==200){

            var rujukan = data.result.rujukan;
            var peserta = data.result.peserta;
            var diagnosa = data.result.diagnosa;
            var pelayanan = data.result.pelayanan;
            var poliRujukan = data.result.poliRujukan;
            var provPerujuk = data.result.provPerujuk;
            console.log(provPerujuk.kode);

            /*show hidden*/
            $('#result-dt-rujukan').show('fast');
            $('#showFormPenjaminKLL').hide('fast');
            $('#showResultData').show('fast');

            // jika no_mr tidak sama dengan no mr rujukan show warning
            // if(peserta.mr.noMR != $('#noMrHidden').val() ){
            //     find_pasien_by_keyword(peserta.mr.noMR);
            //     $('#perjanjian_result_view_div').html('<div class="center red" style="font-weight: bold; font-style: italic; padding-top: 25px; font-size: 16px">Silahkan pilih kode booking kembali!</div>');
            //     // $('#search_kode_perjanjian_result').hide();
            // }
            /*text*/
            $('#noSuratSKDP').val($('#noSuratKontrol').val());
            $('#noKartuFromNik').text(peserta.noKartu);
            $('#nama').text(peserta.nama);
            $('#user').val(peserta.nama);
            $('#nik').text(peserta.nik);
            $('#tglLahir').text(peserta.tglLahir);
            $('#umur_p_bpjs').text(peserta.umur.umurSekarang);
            $('#jenisPeserta').text(peserta.jenisPeserta.keterangan);
            $('#hakKelas').text(peserta.hakKelas.keterangan);
            $('#statusPeserta').text(peserta.statusPeserta.keterangan);

            /*form*/
            $('#noKartuHidden').val(peserta.noKartu);
            $('#noMR').val(peserta.mr.noMR);
            $('#noKartuReadonly').val(peserta.noKartu);
            $('#namaPasienReadonly').val(peserta.nama);
            $('#inputKeyPoliTujuan').val(poliRujukan.nama);
            $('#kodePoliHiddenTujuan').val(poliRujukan.kode);
            $('#inputKeyFaskes').val(provPerujuk.nama);
            $('#kodeFaskesHidden').val(provPerujuk.kode);
            $('#noRujukanView').val(rujukan.noKunjungan);
            $('#tglKunjungan').val(rujukan.tglKunjungan);
            $('#inputKeyDiagnosa').val(diagnosa.nama);
            $('#kodeDiagnosaHidden').val(diagnosa.kode);
            $('#noTelp').val(peserta.mr.noTelepon);
            $('#catatan').val(rujukan.keluhan);

            /*show dokter DPJP*/
            // $.getJSON("ws_bpjs/Ws_index/getRef?ref=GetRefDokterDPJPRandom", { spesialis:$('#kodePoliHidden').val(),jp:$('input[name=jnsPelayanan]:checked').val(),tgl:$('#tglSEP').val(), dokterDPJP:$().val() }, function (row) {
            //       $('#KodedokterDPJP').val(row.kode);
            //       $('#InputKeydokterDPJP').val(row.nama.toUpperCase());    
            //       $('#show_dpjp').val(row.nama.toUpperCase());    
            // });

            // default from perjanjian
            $('#KodedokterDPJP').val($('#kodeDokterDPJPPerjanjianBPJS').val());
            $('#InputKeydokterDPJP').val($('#namaDokterDPJPPerjanjianBPJS').val());    
            $('#show_dpjp').val($('#namaDokterDPJPPerjanjianBPJS').val());
            // set value default
            $('#kode_perusahaan_hidden').val(120);
            $('#kode_kelompok_hidden').val(3);
            $('#noKartuBpjs').val(peserta.noKartu);

            $("input[name=jnsPelayanan][value="+pelayanan.kode+"]").attr('checked', true);
            
            getKlinikByJadwalDefault(poliRujukan.kode);

        }else{
            $.achtung({message: data.message, timeout:5, className: 'achtungFail'});
        }
        
      }
    });

});

$('#tgl_registrasi').click(function (e) {
  $('#change_modul_view').hide();
  $('#jenis_pendaftaran').val('');
  $('#div_load_after_selected_pasien').hide('fast');
  $('#div_riwayat_pasien').hide('fast');
  $('#div_penangguhan_pasien').hide('fast');
})

function getKlinikByJadwalDefault(kode_poli_bpjs){
    date = $('#tgl_registrasi').val();
    days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    var d = new Date(date);
    current_day = days[d.getDay()]; 

    $.getJSON("<?php echo site_url('Templates/References/getKlinikFromJadwal') ?>/" +current_day+'/'+date, '', function (data) {              
        $('#reg_klinik_rajal option').remove();  
        $('<option value="">-Pilih Klinik-</option>').appendTo($('#reg_klinik_rajal'));
        $.each(data, function (i, o) {   
          var selected = (o.kode_poli_bpjs == 'PAR') ? 'selected' : '';
            $('<option value="' + o.kode_bagian + '" '+selected+'>' + o.nama_bagian + '</option>').appendTo($('#reg_klinik_rajal'));                    
        });     
    });  
}

function show_list_rujukan(){
    preventDefault();
    show_modal('registration/Reg_klinik/search_rujukan_by_kartu/'+$('#noKartuBpjs').val()+'', 'DATA RUJUKAN PASIEN BPJS');
}

function get_riwayat_medis(){

  noMr = $('#noMrHidden').val();
  if (noMr == '') {
    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  }else{
    getMenuTabsHtml('templates/References/get_riwayat_medis/'+noMr, 'tabs_detail_pasien');
  }

}

function get_riwayat_pm(){

  noMr = $('#noMrHidden').val();
  if (noMr == '') {
    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  }else{
    getMenuTabsHtml('templates/References/get_riwayat_pm/'+noMr, 'tabs_detail_pasien');
  }

}

function form_perjanjian(){

  $('#div_load_perjanjian_form').show();
  $('#div_load_after_selected_pasien').hide();
  $('#perjanjian_result_view_div').hide();
  $('#search_kode_perjanjian_result').hide();
  noMr = $('#noMrHidden').val();
  if (noMr == '') {
    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  }else{
    getMenuTabs('registration/reg_pasien/form_perjanjian_ontabs/'+noMr, 'div_load_perjanjian_form');
  }

}

function copyNoRujukan(no_rujukan){
  preventDefault();
  $('#norujukanbpjs').val(no_rujukan);
  $('#noRujukan').val(no_rujukan);
  $('#globalModalView').modal('hide');
  // click btn rujukan
  $('#btnSearchNoRujukan').click();
}


// --- Webcam Modal UI & Logic ---

var webcamStream = null;

function openWebcamModal() {
  $('#webcamModal').modal('show');
  var video = document.getElementById('webcamVideo');
  // Reset video/canvas/button states
  document.getElementById('webcamCanvas').style.display = 'none';
  document.getElementById('webcamVideo').style.display = 'block';
  document.getElementById('btnUploadSnapshot').style.display = 'none';
  document.getElementById('btnTakeSnapshot').style.display = 'inline-block';

  // Stop previous stream if any
  if (webcamStream) {
    webcamStream.getTracks().forEach(function(track) { track.stop(); });
    webcamStream = null;
  }

  // Check for camera permission first (Permissions API)
  if (navigator.permissions && navigator.permissions.query) {
    navigator.permissions.query({ name: 'camera' }).then(function(permissionStatus) {
      if (permissionStatus.state === 'denied') {
        $('#webcamModal').modal('hide');
        $.achtung({message: 'Akses kamera ditolak oleh browser. Silakan izinkan akses kamera pada pengaturan browser.', timeout:7, className: 'achtungFail'});
        return;
      }
      // If prompt or granted, try to open camera
      requestCameraStream();
    }).catch(function() {
      // Fallback if Permissions API not available or fails
      requestCameraStream();
    });
  } else {
    // Fallback for browsers without Permissions API
    requestCameraStream();
  }
}

function requestCameraStream() {
  var video = document.getElementById('webcamVideo');
  if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(function(stream) {
        webcamStream = stream;
        video.srcObject = stream;
        video.play();
      })
      .catch(function(err) {
        $('#webcamModal').modal('hide');
        if (err && (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError')) {
          $.achtung({message: 'Akses kamera ditolak. Silakan izinkan akses kamera pada browser Anda.', timeout:7, className: 'achtungFail'});
        } else if (err && (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError')) {
          $.achtung({message: 'Tidak ada perangkat kamera yang ditemukan.', timeout:7, className: 'achtungFail'});
        } else {
          $.achtung({message: 'Tidak dapat mengakses kamera: ' + (err.message || err.name), timeout:7, className: 'achtungFail'});
        }
      });
  } else {
    $('#webcamModal').modal('hide');
    $.achtung({message: 'Browser tidak mendukung akses kamera.', timeout:7, className: 'achtungFail'});
  }
}

$('#webcamModal').on('hidden.bs.modal', function () {
  if (webcamStream) {
    webcamStream.getTracks().forEach(function(track) { track.stop(); });
    webcamStream = null;
  }
  // Reset UI
  var canvas = document.getElementById('webcamCanvas');
  var video = document.getElementById('webcamVideo');
  if (canvas && video) {
    canvas.style.display = 'none';
    video.style.display = 'block';
  }
  var btnUpload = document.getElementById('btnUploadSnapshot');
  var btnTake = document.getElementById('btnTakeSnapshot');
  if (btnUpload && btnTake) {
    btnUpload.style.display = 'none';
    btnTake.style.display = 'inline-block';
  }
});

function takeSnapshot() {
  var video = document.getElementById('webcamVideo');
  var canvas = document.getElementById('webcamCanvas');
  if (!video || !canvas) return;
  var context = canvas.getContext('2d');
  context.drawImage(video, 0, 0, canvas.width, canvas.height);
  canvas.style.display = 'block';
  video.style.display = 'none';
  document.getElementById('btnUploadSnapshot').style.display = 'inline-block';
  document.getElementById('btnTakeSnapshot').style.display = 'none';
}

function uploadSnapshot() {
  var canvas = document.getElementById('webcamCanvas');
  if (!canvas) return;
  var dataURL = canvas.toDataURL('image/png');

  if(!$('#noMrHidden').val()){
    alert('Tidak ada data pasien'); return false;
  }

  $.ajax({
    url: '<?php echo site_url('registration/Reg_klinik/upload_foto_pasien') ?>',
    type: 'POST',
    data: { image: dataURL, no_mr: $('#noMrHidden').val() },
    dataType: 'json',
    success: function(response) {
      if(response.status === 200) {
        $('#avatar').attr('src', response.url_foto);
        $('#webcamModal').modal('hide');
        $.achtung({message: response.message, timeout:5});
      } else {
        $.achtung({message: response.message, timeout:5, className: 'achtungFail'});
      }
    },
    error: function() {
      $.achtung({message: 'Gagal upload foto', timeout:5, className: 'achtungFail'});
    }
  });
}


</script>


<style type="text/css">
  .pagination{
    margin: 0px 0px !important;
  }
  .well{
    padding: 5px !important;
  }
  .list-group-item {
    padding: 3px 6px 15px 5px !important;
  }
  /* Webcam Modal Styles */
  #webcamModal .modal-dialog {
    max-width: 420px;
    margin: 40px auto;
  }
  #webcamModal .modal-content {
    border-radius: 10px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.12);
    padding: 0;
  }
  #webcamModal .modal-header {
    background: #0073b7;
    color: #fff;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    padding: 12px 20px;
    text-align: center;
  }
  #webcamModal .modal-title {
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 1px;
  }
  #webcamModal .modal-body {
    padding: 20px 24px 16px 24px;
    text-align: center;
    background: #f8f9fa;
  }
  #webcamModal video, #webcamModal canvas {
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    width: 320px;
    height: 240px;
    background: #222;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }
  #webcamModal .modal-footer {
    background: #f1f1f1;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    padding: 12px 20px;
    display: flex;
    justify-content: center;
    gap: 10px;
  }
  #webcamModal .btn {
    min-width: 110px;
    font-size: 15px;
    border-radius: 5px;
    font-weight: 500;
    box-shadow: none;
    transition: background 0.2s;
  }
  #webcamModal .btn-primary {
    background: #0073b7;
    border: none;
  }
  #webcamModal .btn-primary:hover {
    background: #005a8c;
  }
  #webcamModal .btn-secondary {
    background: #e0e0e0;
    color: #333;
    border: none;
  }
  #webcamModal .btn-secondary:hover {
    background: #c7c7c7;
  }
</style>

<!-- <div class="page-header">    

    <h1>      

      <?php echo $title?>        

      <small>        

        <i class="ace-icon fa fa-angle-double-right"></i>          

        <?php echo isset($breadcrumbs)?$breadcrumbs:''?>          

      </small>        

    </h1>      

</div> -->

<!-- Webcam Modal -->
<div class="modal fade" id="webcamModal" tabindex="-1" role="dialog" aria-labelledby="webcamModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="webcamModalLabel"><i class="fa fa-camera"></i> Ambil Foto Pasien</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:absolute;right:18px;top:12px;font-size:22px;background:none;border:none;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <video id="webcamVideo" autoplay playsinline style="display:block;"></video>
        <canvas id="webcamCanvas" width="320" height="240" style="display:none;"></canvas>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnTakeSnapshot" onclick="takeSnapshot()" style="height: 50px !important"><i class="fa fa-camera"></i> Ambil Foto</button>
        <button type="button" class="btn btn-success" id="btnUploadSnapshot" onclick="uploadSnapshot()" style="display:none; height: 50px !important"><i class="fa fa-upload"></i> Upload</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="height: 50px !important">Batal</button>
      </div>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-xs-12 no-padding">
      <!-- PAGE CONTENT BEGINS -->
      <div class="invisible">
        <button data-target="#sidebar2" data-toggle="collapse" type="button" class="pull-left navbar-toggle collapsed">
          <span class="sr-only">Toggle sidebar</span>
          <i class="ace-icon fa fa-dashboard white bigger-125"></i>
        </button>

        <div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse ace-save-state">
          <div class="center">
            <ul class="nav nav-list">
              <li class="hover">
                <a data-toggle="tab" data-id="0" id="tabs_perjanjian_form" href="#" onclick="form_perjanjian()"><i class="menu-icon fa fa-calendar"></i><span class="menu-text"> Perjanjian </span></a><b class="arrow"></b>
              </li>

              <!-- <li class="hover">
                <a href="#" onclick="showModalDaftarPerjanjian(0)"><i class="menu-icon fa fa-calendar"></i><span class="menu-text"> Perjanjian </span></a><b class="arrow"></b>
              </li> -->

              <li class="hover">
                <a href="#" id="btn_barcode_pasien"><i class="menu-icon fa fa-barcode"></i><span class="menu-text"> Barcode </span></a><b class="arrow"></b>
              </li>
              <li class="hover">
                <a href="#" id="btn_gelang_pasien"><i class="menu-icon fa fa-lemon-o"></i><span class="menu-text"> Gelang Pasien </span></a><b class="arrow"></b>
              </li>
              <!-- <li class="hover">
                <a href="#"  id="btn_card_member_temp"><i class="menu-icon fa fa-credit-card"></i><span class="menu-text"> Kartu Pasien </span></a><b class="arrow"></b>
              </li> -->
              <li class="hover">
                <a href="#" id="btn_identitas_berobat_pasien"><i class="menu-icon fa fa-file"></i><span class="menu-text"> Ringkasan </span></a><b class="arrow"></b>
              </li>
              <!-- <li class="hover">
                <a href="#" onclick="showModalEditPasien()"><i class="menu-icon fa fa-user"></i><span class="menu-text"> Update Pasien </span></a><b class="arrow"></b>
              </li> -->
              <li class="hover">
                <a data-toggle="tab" id="tabs_rekam_medis_id" href="#" data-id="0" data-url="registration/reg_pasien/get_riwayat_medis" onclick="get_riwayat_medis()"><i class="menu-icon fa fa-stethoscope"></i><span class="menu-text"> Rekam Medis </span></a><b class="arrow"></b>
              </li>

              <li class="hover">
                <a data-toggle="tab" id="tabs_hasil_penunjang_id" href="#" data-id="0" data-url="registration/reg_pasien/get_riwayat_pm" onclick="get_riwayat_pm()"><i class="menu-icon fa fa-flask"></i><span class="menu-text"> Hasil Penunjang </span></a><b class="arrow"></b>
              </li>

              <li class="hover">
                <a data-toggle="tab" id="tabs_riwayat_kunjungan_id" href="#" data-id="0" data-url="registration/reg_pasien/riwayat_kunjungan" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_detail_pasien')"><i class="menu-icon fa fa-leaf"></i><span class="menu-text"> Kunjungan </span></a><b class="arrow"></b>
              </li>

              <!-- <li class="hover">
                <a data-toggle="tab" data-id="1" data-url="registration/reg_pasien/riwayat_transaksi" id="tabs_riwayat_transaksi_id" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_detail_pasien')"><i class="menu-icon fa fa-money"></i><span class="menu-text"> Transaksi </span></a><b class="arrow"></b>
              </li> -->
              
              <li class="hover">
                <a data-toggle="tab" data-id="0" data-url="registration/reg_pasien/riwayat_perjanjian" id="tabs_riwayat_perjanjian_id" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_detail_pasien')"><i class="menu-icon fa fa-history"></i><span class="menu-text"> Riwayat Perjanjian </span></a><b class="arrow"></b>
              </li>

              <!-- <li class="hover">
                <a data-toggle="tab" data-id="0" data-url="registration/Reg_pasien/konfirmasi_fp" id="tabs_konfirm_fp_id" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_detail_pasien')"><i class="menu-icon fa fa-thumbs-up"></i><span class="menu-text"> Finger Print </span></a><b class="arrow"></b>
              </li> -->
              <!-- <li class="hover">
                <a href="#" onclick="show_antrian_poli()"><i class="menu-icon fa fa-exclamation-circle"></i><span class="menu-text"> Antrian Poli/klinik </span></a><b class="arrow"></b>
              </li> -->

              <li class="hover">
                <a data-toggle="tab" id="tabs_riwayat_kunjungan_online_id" href="#" data-id="0" data-url="registration/reg_pasien/riwayat_kunjungan_online" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_detail_pasien')"><i class="menu-icon fa fa-globe"></i><span class="menu-text"> Update SEP </span></a><b class="arrow"></b>
              </li>

            </ul>
          </div>
        </div>
      </div>

      <div style="margin-top:-10px">  

        <form class="form-horizontal" method="post" id="form_registration" action="#" enctype="multipart/form-data" autocomplete="off" >      
          
            <br>
            <!-- hidden form -->
            <input type="hidden" value="" name="noMrHidden" id="noMrHidden">
            <input type="hidden" value="" name="nikPasien" id="nikPasien">
            <input type="hidden" value="" name="hpPasien" id="hpPasien">
            <input type="hidden" value="" name="noTelpPasien" id="noTelpPasien">
            <input type="hidden" name="flag" value="noKartu">
            <input type="hidden" name="umur_saat_pelayanan_hidden" value="" id="umur_saat_pelayanan_hidden">
            <input type="hidden" name="nama_pasien_hidden" value="" id="nama_pasien_hidden">
            <input name="is_new" id="is_new" class="form-control" type="hidden" value="<?php echo isset($is_new)?$is_new:'';?>">          
            <input type="hidden" name="kodeDokterDPJPPerjanjianBPJS" value="" id="kodeDokterDPJPPerjanjianBPJS">
            <input type="hidden" name="kodeDokterDPJPPerjanjian" value="" id="kodeDokterDPJPPerjanjian">
            <input type="hidden" name="kodePoliPerjanjian" value="" id="kodePoliPerjanjian">
            <input type="hidden" name="less_then_min_visit" value="" id="less_then_min_visit">
            <input type="hidden" name="namaDokterDPJPPerjanjianBPJS" value="" id="namaDokterDPJPPerjanjianBPJS">
            <input type="hidden" name="id_tc_pesanan" value="<?php echo isset($id_tc_pesanan)?$id_tc_pesanan:''?>" id="id_tc_pesanan">
            
            <div class="col-md-2">
              <div class="box box-primary" id='box_identity'>
                  <div style="position:relative;">
                    <img id="avatar" class="profile-user-img img-responsive center" src="<?php echo base_url().'assets/img/avatar.png'?>" alt="User profile picture" style="width:100%">
                    <button type="button" class="btn btn-xs btn-primary" style="position:absolute;top:10px;right:10px;z-index:2;" onclick="openWebcamModal()"><i class="fa fa-camera"></i> Ambil Foto</button>
                  </div>

                  <!-- <img id="avatar" class="profile-user-img img-responsive center" src="<?php echo base_url().'assets/img/avatar.png'?>" alt="User profile picture" style="width:100%"> -->

                  <h3 class="profile-username text-center"><div id="no_mr" style="font-size: 16px !important">-No. Rekam Medis-</div></h3>

                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <small style="color: blue; font-weight: bold; font-size: 11px">Nama Pasien: </small><div id="nama_pasien"></div>
                    </li>
                    <li class="list-group-item">
                      <small style="color: blue; font-weight: bold; font-size: 11px">NIK: </small><div id="no_ktp"></div>
                    </li>
                    <li class="list-group-item">
                      <small style="color: blue; font-weight: bold; font-size: 11px">Tgl Lahir: </small><div id="tgl_lhr"></div>
                    </li>
                    <li class="list-group-item">
                      <small style="color: blue; font-weight: bold; font-size: 11px">Umur: </small><div id="umur"></div>
                    </li>
                    <li class="list-group-item">
                      <small style="color: blue; font-weight: bold; font-size: 11px">Alamat: </small><div id="alamat"></div>
                    </li>
                    <li class="list-group-item">
                      <small style="color: blue; font-weight: bold; font-size: 11px">No Telp/HP: </small>
                      <div id="hp"></div>
                      <div id="no_telp"></div>
                    </li>
                    <li class="list-group-item">
                      <small style="color: blue; font-weight: bold; font-size: 11px">Penjamin: </small><div id="kode_perusahaan"></div><div id="no_kartu_bpjs_txt"></div>
                    </li>
                    <li class="list-group-item">
                      <small style="color: blue; font-weight: bold; font-size: 11px">Catatan: </small><div id="catatan_pasien"></div>
                    </li>
                    <li class="list-group-item">
                      <small style="color: blue; font-weight: bold; font-size: 11px">TTD: </small><div><img id="ttd_pasien" class="profile-user-img img-responsive center" src="<?php echo base_url().'assets/images/ttd-no-found.png'?>" alt="User profile picture" style="width:100%"></div>
                    </li>
                  </ul>

                  <a href="#" class="btn btn-primary btn-block center" onclick="showModalEditPasien()" style="margin-top:10px"><b>Update Data</b></a>
                  <a href="#" class="btn btn-danger btn-block center" onclick="showModalMergePasien()"><b>Merge Pasien</b></a>
                  <a href="#" class="btn btn-success btn-block center" onclick="showModalTTD()"><b>Tanda Tangan</b></a>
                  
                <!-- /.box-body -->
              </div>
            </div>

            <div class="col-md-10 no-padding">

              <!-- div main form -->
              <div class="col-md-8 no-padding">

                <p><b>DATA REGISTRASI </b></p>

                <!-- tanggal pelayanan -->
                <div class="form-group">
                        
                  <label class="control-label col-sm-3">Tanggal</label>
                  
                  <div class="col-md-4">
                    
                    <div class="input-group">
                        
                        <input name="tgl_registrasi" id="tgl_registrasi" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
                        <span class="input-group-addon">
                          
                          <i class="ace-icon fa fa-calendar"></i>
                        
                        </span>
                      </div>
                  
                  </div>

                </div>

                <!-- tipe registrasi -->
                <!-- <div class="form-group">

                  <label class="control-label col-md-3">Tipe Registrasi</label>

                  <div class="col-md-9">

                    <div class="radio">

                        <label>

                          <input name="tipe_registrasi" type="radio" class="ace" value="onsite" <?php echo isset($value) ? ($value->is_active == 'onsite') ? 'checked="checked"' : '' : 'checked="checked"'; ?>  />

                          <span class="lbl"> Datang Langsung (BPJS/Umum)</span>

                        </label>

                        <label>

                          <input name="tipe_registrasi" type="radio" class="ace" value="perjanjian" <?php echo isset($value) ? ($value->is_active == 'perjanjian') ? 'checked="checked"' : '' : ''; ?> />

                          <span class="lbl"> Pasien Dengan Perjanjian (BPJS)</span>

                        </label>

                        <label>

                          <input name="tipe_registrasi" type="radio" class="ace" value="online" <?php echo isset($value) ? ($value->is_active == 'online') ? 'checked="checked"' : '' : ''; ?> />

                          <span class="lbl"> Via Online / Mobile JKN</span>

                        </label>


                    </div>

                  </div>

                </div> -->

                <!-- search pasien by -->
                <div class="form-group">
                  <label class="control-label col-md-3">Pencarian dengan</label>
                  <div class="col-md-9">
                    <div class="radio">
                        <label>
                          <input name="search_by" type="radio" class="ace" value="no_mr" checked/>
                          <span class="lbl"> No. MR</span>
                        </label>
                        <label>
                          <input name="search_by" type="radio" class="ace" value="nama_pasien" />
                          <span class="lbl"> Nama Pasien</span>
                        </label>
                        <label>
                          <input name="search_by" type="radio" class="ace" value="no_kartu_bpjs" />
                          <span class="lbl"> Nomor Kartu BPJS</span>
                        </label>
                        <label>
                          <input name="search_by" type="radio" class="ace" value="no_ktp" />
                          <span class="lbl"> NIK</span>
                        </label>
                    </div>
                  </div>
                </div>

                <!-- cari data pasien -->
                <div class="form-group" id="search_mr_form" <?php echo isset($kode_booking)?'style="display:none"':''?>>
                  <label class="control-label col-md-3"><b>CARI PASIEN</b></label>            
                  <div class="col-md-8">            
                    <div class="input-group">
                      <input type="text" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>">
                      <span class="input-group-btn">
                        <button type="button" id="btn_search_pasien" class="btn btn-inverse btn-sm">
                          <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                          Search
                        </button>
                      </span>
                    </div>
                  </div>
                </div>

                <div id="divLoadSEP"></div>
                
                <div id="div_form_onsite" <?php echo isset($kode_booking)?'style="display:none"':''?> >

                  <div class="form-group">
                    <div class="col-md-12 no-padding">

                      <!-- untuk pasien baru -->
                      <?php if( isset($is_new) ) :?>
                        <script type="text/javascript">
                          $(document).ready(function(){
                            find_pasien_by_keyword('<?php echo $no_mr?>');
                            showChangeModul(1);
                            $('#div_load_after_selected_pasien').show('fast');
                          })
                        </script>
                        <div style="margin-top:3px" id="label_info_pasien_baru"><a href="#"><i class="fa fa-times-circle bigger-150 red" id="btn_hide_pasien_baru_label"></i></a> <label class="label label-primary"><i class="fa fa-exchange"></i> PENDAFTARAN PASIEN BARU</label></div>
                      <?php endif; ?>

                      <!-- untuk pasien rujukan -->
                      <?php if(isset($kode_rujukan)) :?>
                        <script type="text/javascript">
                          $(document).ready(function(){
                            find_pasien_by_keyword('<?php echo $no_mr?>');
                            <?php $modul = ($data_rujukan->rujukan_tujuan=='030001') ? 2 : 1 ;?>
                            showChangeModul(<?php echo $modul?>);
                            $('#div_load_after_selected_pasien').show('fast');
                            //$('#collapseOne').attr('aria-expanded', true);
                            $('#decline_warning').prop('checked', true);
                          })
                        </script>
                        <div style="margin-top:3px" id="label_info_rujukan"><a href="#"><i class="fa fa-times-circle bigger-150 red" id="btn_hide_rujukan_label"></i></a> <label class="label label-danger"><i class="fa fa-exchange"></i> PASIEN RUJUKAN DARI <?php echo strtoupper($data_rujukan->nama_rujukan_dari)?></label></div>
                        <input type="hidden" name="kode_rujukan_hidden" value="<?php echo $kode_rujukan?>" id="kode_rujukan_hidden">
                        <input type="hidden" name="no_registrasi_hidden" value="<?php echo $no_registrasi?>" id="no_registrasi_hidden">
                        <input type="hidden" name="no_kunjungan_hidden" value="<?php echo $data_rujukan->no_kunjungan_lama?>" id="no_registrasi_hidden">
                        <input type="hidden" name="kode_bagian_asal_hidden" value="<?php echo $data_rujukan->rujukan_dari?>" id="no_registrasi_hidden">
                      <?php endif;?>

                      <!-- untuk pasien perjanjian -->
                      <?php if(isset($id_tc_pesanan)) :?>
                        <script type="text/javascript">
                          $(document).ready(function(){
                            find_pasien_by_keyword('<?php echo $no_mr?>');
                            showChangeModul(1);
                            changeModulRjFromPerjanjian(<?php echo $id_tc_pesanan?>, <?php echo $kode_dokter?>, <?php echo $poli?>, '<?php echo $kode_perjanjian?>');
                            $('#pasien_dengan_perjanjian').show('fast');
                          })
                        </script>
                      <?php  endif;?>

                      <!-- untuk petugas pm-->
                      <?php if(isset($pm)) :?>
                        <script type="text/javascript">
                          $(document).ready(function(){
                            //$('#jenis_pendaftaran option[value=3]').attr('selected','selected');
                            $("#jenis_pendaftaran option[value=1]").remove();
                            $("#jenis_pendaftaran option[value=2]").remove();
                            $("#jenis_pendaftaran option[value=4]").remove();
                            $("#jenis_pendaftaran option[value=5]").remove();
                            $("#jenis_pendaftaran option[value=6]").remove();
                            $("#jenis_pendaftaran option[value=7]").remove();
                          })
                        </script>
                      <?php  endif;?> 

                      <!-- jika sumber data berasal dari pesanan pasien sebelumnya maka -->
                      <div id="pasien_dengan_perjanjian" <?php echo isset($id_tc_pesanan)?'':'style="display:none"'?> >
                        <?php echo isset($id_tc_pesanan)?'<div style="margin-top:3px"><a href="#" onclick="hideLabelPerjanjian()"><i class="fa fa-times-circle bigger-150 red"></i></a> <label class="label label-warning"><i class="fa fa-exchange"></i> <b> PASIEN DENGAN PERJANJIAN NOMOR '.$kode_perjanjian.' </b> </label> </div>':''?>
                      </div>

                      <div id="div_penangguhan_pasien" style="display:none">
                        
                        <div class="alert alert-success" style="border-radius: 3px"><b>Pemberitahuan !</b> <br>Pasien sudah terdaftar pada hari ini <span id="tgl_daftar_txt"></span> <span id="tipe_daftar_txt"></span></div>
                        <div class="col-md-12 no-padding">
                          <table class="table table-bordered table-hover" id="result_penangguhan_pasien">
                            <thead>
                            <tr>
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
                          <!-- <span style="margin-top:-30px !important; font-size:11px">NB : Silahkan hubungi bagian terkait, jika terdapat penangguhan administrasi maka pasien tidak dapat didaftarkan untuk berobat sebelum menyelesaikan tangguhan nya. Jika tidak ada penangguhan silahkan abaikan peringatan ini. </span><br> -->
                          <div class="checkbox">
                            <label>
                              <input name="form-field-checkbox" type="checkbox" class="ace" value="Y" id="decline_warning">
                              <span class="lbl" style="font-weight: bold;"> UPDATE DATA KUNJUNGAN PASIEN</span>
                            </label>
                          </div>
                          
                        </div>
                      </div>
                      
                      <div id="div_less_then_31_bpjs" style="display: none">
                        <div id="show_notif_less_then_31"></div>
                      </div>

                      <div id="div_konsul_internal" style="display: none">
                        <div id="show_notif_konsul_internal"></div>
                      </div>

                      <div id="div_load_after_selected_pasien" style="display:none">
                        <!-- nasabah -->
                        <div class="form-group">
                          <label class="control-label col-sm-3">Nasabah</label>
                          <div class="col-md-6">
                              <input id="InputKeyNasabah" class="form-control" name="kelompok_nasabah" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                              <input type="hidden" name="kode_kelompok_hidden" value="" id="kode_kelompok_hidden">
                          </div>
                        </div>
                        <!-- penjamin -->
                        <div class="form-group">
                          <label class="control-label col-sm-3">Penjamin</label>
                          <div class="col-md-6">
                              <input id="InputKeyPenjamin" class="form-control" name="penjamin" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                              <input type="hidden" name="kode_perusahaan_hidden" value="" id="kode_perusahaan_hidden">
                          </div>
                        </div>
                        <!-- tujuan pendaftaran -->
                        <div class="form-group">
                          <label class="control-label col-sm-3">Tujuan Pendaftaran</label>
                          <div class="col-md-6">
                            <select name="jenis_pendaftaran" class="form-control" id="jenis_pendaftaran">
                              <option value="">-Pilih-</option>
                              <option value="1">RAWAT JALAN</option>  
                              <option value="2" <?php echo isset($kode_rujukan)?'selected':'' ?> >RAWAT INAP</option> 
                              <option value="3" <?php echo isset($pm)?'selected':'' ?>>PENUNJANG MEDIS</option> 
                              <option value="4">IGD</option>  
                              <option value="5">MCU</option>  
                              <option value="6">ODC</option>  
                              <option value="7">PAKET BEDAH</option>
                            </select>
                          </div>
                        </div>
                        <!-- tujuan pendaftaran -->
                        <br>
                        <!-- Post Ranap -->
                        <div class="checkbox">
                          <label>
                            <input name="post_ranap" type="checkbox" class="ace" value="Y" id="post_ranap">
                            <span class="lbl" style="font-style: italic"> Pasien Kunjungan Pasca Rawat Inap</span>
                          </label>
                        </div>

                        <!-- untuk pasien bpjs -->
                        <div id="form_sep" style="display:none">

                          <!-- get data rujukan -->
                          <hr>
                          <p><b>MASUKAN NOMOR RUJUKAN</b></p>
                          
                          <div class="form-group">
                            <label class="control-label col-sm-3">No Kartu BPJS</label>            
                            <div class="col-md-3">            
                                  <input name="noKartuBpjs" id="noKartuBpjs" class="form-control" type="text" value="">
                            </div>   
                            <!-- <label class="control-label col-sm-3">Nomor SEP</label>            
                            <div class="col-md-4">            
                                  <input name="noSep" id="noSep" class="form-control" type="text" placeholder="Masukan No SEP">
                            </div>  -->
                          </div>
                          <div class="form-group" id="form_rujukan">
                            <label class="control-label col-sm-3">Nomor Rujukan</label>            
                            <div class="col-md-6">            
                              <div class="input-group">
                                <!-- for hidden for searching nomor rujukan -->
                                <input name="find_member_by" type="radio" class="ace" value="noRujukan" checked>
                                <input name="tglSEP" id="tglSEP" value="<?php echo date('Y-m-d')?>" placeholder="mm/dd/YYYY" class="form-control date-picker" type="hidden">
                                <input name="jenis_faskes_pasien" id="jenis_faskes_pasien" type="hidden" class="ace" value="pcare"/>
                                <input type="hidden" class="form-control" id="noKartuHidden" name="noKartuHidden" readonly>
                                <input name="jnsPelayanan" type="radio" class="ace" value="2" checked/>
                                <input name="lakalantas" type="radio" class="ace" value="0" checked/>
                                <input name="penjaminKLL" type="radio" class="ace" value="0" checked/>
                                <input type="hidden" class="form-control" name="catatan" id="catatan" value="">
                                <!-- <input type="hidden" class="form-control" id="noSuratSKDP" name="noSuratSKDP" value=""> -->
                                <input type="hidden" class="form-control" id="user" name="user" value="<?php echo $this->session->userdata('user')->fullname?>" readonly>
                                <input id="InputKeydokterDPJP" class="form-control" name="dokterDPJP" type="hidden"/>
                                <input type="hidden" name="KodedokterDPJP" value="" id="KodedokterDPJP">

                                <input name="noRujukan" id="noRujukan" class="form-control" type="text" placeholder="Masukan No Rujukan" style="width: 250px;" onclick="show_list_rujukan()">

                                <span class="input-group-btn">
                                  <button type="button" id="btnSearchNoRujukan" class="btn btn-primary btn-sm">
                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                    Tampilkan data
                                  </button>
                                </span>
                              </div>
                            </div>   
                          </div>
                          <!-- hidden form -->
                          <!-- nasabah -->
                          <input id="InputKeyNasabahBPJS" class="form-control" name="kelompok_nasabah" type="hidden"/>
                          <input type="hidden" name="kode_kelompok_hidden_bpjs" value="" id="kode_kelompok_hidden_bpjs">

                          <!-- penjamin -->
                          <input id="InputKeyPenjaminBPJS" class="form-control" name="penjamin" type="hidden"  />
                          <input type="hidden" name="kode_perusahaan_hidden_bpjs" value="" id="kode_perusahaan_hidden_bpjs">

                          <div id="warning_no_mr" style="margin-top:10px;"></div>

                          <!-- change modul view -->
                          <div id="change_modul_view_perjanjian" style="margin-top:10px"></div>
                          
                          <!-- <div class="form-group">
                            <label class="control-label col-sm-3">Nomor SEP</label>            
                            <div class="col-md-4">            
                                  <input name="noSep" id="noSep" class="form-control" type="text" placeholder="Masukan No SEP">
                            </div>  
                          </div> -->

                          <!-- 

                          <div class="form-group">
                            <label class="control-label col-sm-3">Nama Faskes</label>
                            <div class="col-md-9">
                                <input id="InputKeyFaskes" class="form-control" name="faskes" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                                <input type="hidden" name="kode_faskes_hidden" value="" id="kode_faskes_hidden">
                            </div>
                          </div> -->

                        </div>
                        <!-- end untuk pasien bpjs -->

                        <!-- change modul from global form view -->
                        <div id="change_modul_view" style="margin-top:10px"></div>
                        <!-- end change modul view -->
                        
                        <hr>
                        <!-- btn proses pendaftaran -->
                        <div class="form-group" id="btn_submit" style="display: none">
                          <div class="col-sm-12 no-padding" style="padding-top: 10px">
                              <button type="submit" name="submit" class="btn btn-xs btn-primary" style="height: 35px !important; font-size: 16px">
                                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                                Proses Pendaftaran Pasien
                              </button>
                          </div>
                        </div>

                      </div>

                      

                    </div>

                  </div>

                </div>


                <!-- PASIEN BPJS DENGAN PERJANJIAN -->
                <div class="form-group" id="search_kode_perjanjian_form" <?php echo isset($kode_perjanjian)?'':'style="display:none"'?> >
                  <label class="control-label col-md-3"><b>KODE PERJANJIAN</b></label>            
                  <div class="col-md-5">            
                    <div class="input-group">
                      <input type="text" name="kode_perjanjian" id="form_cari_pasien_by_kode_perjanjian_id" class="form-control search-query" placeholder="Kode Booking" value="<?php echo isset($kode_perjanjian)?$kode_perjanjian:''?>">
                      <span class="input-group-btn">
                        <button type="button" id="btn_search_kode_perjanjian" class="btn btn-default btn-sm">
                          <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                          Search
                        </button>
                      </span>
                    </div>
                  </div>
                </div>

                <!-- rujukan -->
                <div class="form-group" id="perjanjian_result_view_div"></div>

                  <div id="search_kode_perjanjian_result" style="display:block;margin-top:10px">
                  
                    <div id="div_load_after_selected_pasien_perjanjian" style="display: block"></div>

                  </div>

                  <!-- END FORM PASIEN BPJS DENGAN PERJANJIAN -->

                  <div class="form-group" id="search_kode_booking_form" <?php echo isset($kode_booking)?'':'style="display:none"'?> >

                    <label class="control-label col-md-3"><b>KODE BOOKING</b></label>            

                    <div class="col-md-5">            

                      <div class="input-group">

                        <input type="text" name="kode_booking" id="form_cari_pasien_by_kode_booking_id" class="form-control search-query" placeholder="Kode Booking" value="<?php echo isset($kode_booking)?$kode_booking:''?>">

                        <span class="input-group-btn">

                          <button type="button" id="btn_search_kode_booking" class="btn btn-default btn-sm">

                            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>

                            Search

                          </button>

                        </span>

                      </div>

                    </div>

                  </div>

                  <!-- pencarian kode booking -->
                  <div id="search_kode_booking_result" <?php echo isset($kode_booking)?'':'style="display:none;margin-top:10px"'?> >

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

                    <br>

                    <div class="form-group" id="booking_result_view_div"></div>

                  </div>

                  <div id="div_load_perjanjian_form" style="display: none"></div>

                </div>
              
                <!-- tabs on the right content -->
                <div class="col-md-4">

                  <div class="tabbable">
                    <ul class="nav nav-tabs no-padding tab-color-blue background-blue" id="myTabRegistrationMenu4">
                      <li class="active">
                        <a data-toggle="tab" href="#home4">Aktifitas</a>
                      </li>

                      <li>
                        <a data-toggle="tab" href="#tab-antrian-pasien">Antrian Pasien</a>
                      </li>
                    </ul>

                    <div class="tab-content" style="background-color: #edf3f4">
                      <div id="home4" class="tab-pane in active">
                        <p>
                          <div id="tabs_detail_pasien">
                            <div class="alert alert-warning center">
                              <button type="button" class="close" data-dismiss="alert">
                                <i class="ace-icon fa fa-times"></i>
                              </button>
                              <h2><i class="fa fa-exclamation-circle red"></i></h2>
                              <strong>Tidak ada data yang dapat ditampilkan!</strong>
                              <br>
                              Silahkan melakukan pencarian pasien dahulu dengan mencari <b>No MR</b> atau <b>Nama pasien</b>
                              <br>
                            </div>
                          </div>
                        </p>
                      </div>
                      
                      <!-- tab antrian pasien -->
                      <div id="tab-antrian-pasien" class="tab-pane no-padding">
                        <form action="" class="form-horizontal">
                          <div class="form-group">
                              <label class="control-label col-sm-4">*Tipe</label>
                              <div class="col-sm-8">
                                <select name="select_tipe" id="select_tipe" class="form-control">
                                  <option value="#">-Pilih-</option>
                                  <option value="bpjs" >BPJS</option>
                                  <option value="umum">Non BPJS</option>
                                  <option value="online">Online</option>
                                </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="control-label col-sm-4">*Loket</label>
                              <div class="col-sm-6">
                                <select name="select_loket" id="select_loket" class="form-control">
                                  <option value="#">-Pilih-</option>
                                  <?php for($i=1;$i<5;$i++) :?>
                                  <option value="<?php echo $i?>"> <?php echo $i?> </option>
                                  <?php endfor;?>
                                </select>
                              </div>
                              <div class="col-sm-2" style="margin-left:-5%;">
                                <button class="btn btn-xs btn-success" id="btn_submit_loket" onclick="update_antrian()"  type="button" title="Pilih Loket"> <i class="fa fa-check"></i> </button>
                              </div>
                          </div>

                          <div id="message_loket"></div>

                          <div class="row">
                            <center id="counter_apps">
                              <br>
                              <label style="font-size:16px; font-weight: bold"> LOKET <span id="label_loket"> 0 </span> - <span id="label_tipe"> 0 </span>
                              </label>
                              <br>
                              <input type="hidden" name="" id="loket_hidden" value="0">
                              <input type="hidden" name="" id="tipe_hidden" value="0">
                              <ul class="pagination">
                                <!-- <li>
                                  <a href="#" id="prev_count">
                                    <i class="ace-icon fa fa-angle-double-left"></i> Prev
                                  </a>
                                </li> -->

                                <li class="active">
                                  <input type="hidden" name="counter_number_curr" id="counter_number_value" value="0">
                                  <a href="#" style="font-size:18px" id="counter_number"> 
                                    0
                                  </a>
                                </li>

                                <!-- <li>
                                  <a href="#" id="next_count">
                                    Next <i class="ace-icon fa fa-angle-double-right"></i>
                                  </a>
                                </li> -->

                              </ul>

                              <audio id="container" autoplay=""></audio>
                              <br>
                              <button class="btn btn-xs btn-success" id="btn_finish" alt="Finish">Finish <i class="fa fa-check"></i></button>
                              <button class="btn btn-xs btn-danger" id="btn_play" alt="Call">&nbsp;&nbsp;Call <i class="fa fa-volume-up"></i>&nbsp;&nbsp;</button><br>
                              <!-- <button class="btn btn-xs btn-danger" id="btn_skip" alt="Skip">Skip <i class="fa fa-step-forward"></i></button><br> -->
                              Antrian ke - <label id="from_num">0</label> dari <label id="to_num">0</label>
                            
                            </center>
                            <center>
                              <div width="100%">
                                <b style="font-size:16px">BPJS</b><br>
                                <div width="500%" style="float:left;margin-left:5px">
                                Total Antrian <h2 style="margin-top:5px" id="total_bpjs"> 0 </h2>
                                </div>
                                <div width="500%" style="float:right;margin-right:5px">
                                Sisa Antrian <h2 style="margin-top:5px" id="sisa_antrian_bpjs"> 0 </h2>
                                </div>
                              </div>
                              <br>
                              <hr>
                              <div width="100%">
                                <b style="font-size:16px">NON BPJS</b><br>
                                <div width="500%" style="float:left;margin-left:5px">
                                Total Antrian <h2 style="margin-top:5px" id="total_non_bpjs"> 0 </h2>
                                </div>
                                <div width="500%" style="float:right; margin-right:5px">
                                Sisa Antrian <h2 style="margin-top:5px" id="sisa_antrian_non_bpjs"> 0 </h2>
                                </div>
                              </div>
                              <br>
                              <hr>
                              <div width="100%">
                                <b style="font-size:16px">ONLINE MOBILE</b><br>
                                <div width="500%" style="float:left;margin-left:5px">
                                Total Antrian <h2 style="margin-top:5px" id="total_online"> 0 </h2>
                                </div>
                                <div width="500%" style="float:right; margin-right:5px">
                                Sisa Antrian <h2 style="margin-top:5px" id="sisa_antrian_online"> 0 </h2>
                                </div>
                              </div>
                            </center>

                          </div>

                        </form>
                    </div>
                  </div>
                  
                </div>
                <!-- end tabs on the right content -->

            </div>

        </form>

      <hr>

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

      <form class="form-horizontal" method="post" id="form_edit_pasien" action="registration/Input_pasien_baru/process" enctype="multipart/form-data" autocomplete="off">                                    
        
        <div id="form_edit_pasien_modal"></div>

        <button type="submit" name="submit" class="btn btn-xs btn-primary">

          <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>

          Submit

        </button>

      </form>

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

<div id="modalTTDPasien" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:95%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span>TANDA TANGAN PASIEN (DIGITAL SIGNATURE)</span>

        </div>

      </div>

      <div class="modal-body">

      <form class="form-horizontal" method="post" id="form_ttd_pasien" action="registration/Reg_pasien/process_ttd" enctype="multipart/form-data" autocomplete="off">                                    
        
        <div id="form_pasien_modal_ttd"></div>

        <button type="submit" name="submit" class="btn btn-xs btn-primary">

          <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>

          Submit

        </button>

      </form>

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

<div id="modalCetakTracer" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:70%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_riwayat_medis">CETAK TRACER</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="result_after_submit_for_print_tracer"></div>

      </div>

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

<!-- modal merge pasien -->
<div id="modalMergePasien" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:55%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_merge_pasien">MERGE DATA PASIEN</span>

        </div>

      </div>

      <div class="modal-body">

      <form class="form-horizontal" method="post" id="form_merge_pasien" action="registration/Reg_pasien/process_merge_pasien" enctype="multipart/form-data" autocomplete="off">                                    
        
        <div id="form_merge_pasien_modal"></div>

      </form>

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


<!-- javascript counter -->
<script src="<?php echo base_url()?>assets/js/custom/counter.js"></script>