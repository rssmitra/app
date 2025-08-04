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

    /*when page load find pasien by mr*/
    find_pasien_by_keyword('<?php echo $no_mr?>');
    get_riwayat_medis('<?php echo $no_mr?>');

    if($('#kode_perusahaan_val').val() == 120){
      show_icare();
    }

    $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveDiagnosaDr');

    // get data antrian pasien
    getDataAntrianPasien();

    /*focus on form input pasien*/
    $('#form_cari_pasien').focus();    

    $('#form_pelayanan').on('submit', function(){
               
        $('#konten').val($('#editor_konten').html());
        $('input[name=catatan_pengkajian]' , this).val($('#editor').html());
        $('#konten_diagnosa_sekunder').val($('#pl_diagnosa_sekunder_hidden_txt').html());


        var formData = new FormData($('#form_pelayanan')[0]);        
        i=0;
        url = $('#form_pelayanan').attr('action');

        // ajax adding data to database
        $.ajax({
            url : url,
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,            
            beforeSend: function() {
              // achtungShowLoader();   
              $(this).find("button[type='submit']").prop('disabled',true);
            },
            uploadProgress: function(event, position, total, percentComplete) {
            },
            complete: function(xhr) {     
              var data=xhr.responseText;    
              var jsonResponse = JSON.parse(data);  

              if( jsonResponse.status === 200 ){   

                if(jsonResponse.type_pelayanan == 'penunjang_medis')
                {
                  $('#table_order_penunjang').DataTable().ajax.reload(null, false);
                }

                if(jsonResponse.type_pelayanan == 'eresep')
                {
                  $('#table-pesan-resep').DataTable().ajax.reload(null, false);
                  $('#kode_pesan_resep').val('');
                  $('#keterangan_pesan_resep').val('');
                  $('#notif_status').html('');
        
                }

                if(jsonResponse.type_pelayanan == 'pasien_selesai')
                {
                  $('#tabs_form_pelayanan').html('<div class="alert alert-success"><strong>Terima Kasih..!</strong> Pasien sudah dilayani. </div>');
                  get_riwayat_medis(jsonResponse.no_mr);
                  pauseStopWatch();
                  resetStopWatch();
                }
                
                if(jsonResponse.type_pelayanan == 'catatan_pengkajian'){
                  oTableCppt.ajax.reload();
                }

                if(jsonResponse.type_pelayanan == 'update_soap'){
                  $('#tab_menu_erm_dokter li.active').removeClass('active');
                  $('#li_cppt').addClass('active');
                  $('#tabs_cppt').click();
                }

                $.achtung({message: jsonResponse.message, timeout:5});

              }else{
                $.achtung({message: jsonResponse.message, timeout:5, className:'achtungFail'});

              }    
              // achtungHideLoader();        
              
              $(this).find("button[type='submit']").prop('disabled',false);

            }   
        });
        return false;
    });
 
    
    /*on keypress or press enter = search pasien*/
    
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
    
    $('#btn_update_session_poli').click(function (e) {  
      if(confirm('Are you sure?')){
        $.ajax({
            url: "pelayanan/Pl_pelayanan/destroy_session_kode_bagian",
            data: { kode: $('#sess_kode_bagian').val()},            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
              var data=xhr.responseText;  
              var jsonResponse = JSON.parse(data);  
              if(jsonResponse.status === 200){  
                $.achtung({message: jsonResponse.message, timeout:5}); 
                getMenu('pelayanan/Pl_pelayanan');
              }else{          
                $.achtung({message: jsonResponse.message, timeout:5});  
              } 
              achtungHideLoader();
            }
        });
      }else{
        return false;
      }
    });


    $('#btn_search_pasien').click(function (e) {      

      e.preventDefault();  

      if( $("#form_cari_pasien").val() == "" ){

        alert('Masukan keyword minimal 3 Karakter !');

        return $("#form_cari_pasien").focus();

      }else{

        achtungShowLoader();

        find_pasien_by_keyword( $("#form_cari_pasien").val() );

      }    

    }); 
    
    $('#tabs_diagnosa_dr').click(function (e) {   
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveDiagnosaDr');
    });

    $('#tabs_tindakan').click(function (e) {    
      e.preventDefault();  
      $('#form_kelas_tarif').show();
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/process');
      // backToDefaultForm();
    });

    // $('#tabs_pm_lab').click(function (e) {   
    //   e.preventDefault();  
    //   $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveOrderLab');
    // });

    // $('#tabs_pm_rad').click(function (e) {   
    //   e.preventDefault();  
    //   $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveOrderRad');
    // });

    // $('#tabs_pm_fisio').click(function (e) {   
    //   e.preventDefault();  
    //   $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveOrderFisio');
    // });

    $('#tabs_penunjang_medis').click(function (e) {     
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'registration/Reg_pm/process');
    });

    $('#tabs_resep').click(function (e) {   
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'farmasi/Farmasi_pesan_resep/process');
    });

    $('#tabs_cppt').click(function (e) {   
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_cppt');
    });

    $('#tabs_catatan').click(function (e) {   
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveCatatanPengkajian');
    });

    
    $('select[name="status_antrian_rj"]').change(function () {      

      if ($(this).val() == 1) {        
          $('#div_antrian_existing').show('fast');
          $('#div_antrian_done').hide('fast');
          $('#div_antrian_cancel').hide('fast');
      }

      if ($(this).val() == 2) {        
          $('#div_antrian_existing').hide('fast');
          $('#div_antrian_done').show('fast');
          $('#div_antrian_cancel').hide('fast');
      }

      if ($(this).val() == 3) {        
          $('#div_antrian_existing').hide('fast');
          $('#div_antrian_done').hide('fast');
          $('#div_antrian_cancel').show('fast');
      }

    }); 

    $('#tgl_kunjungan, #status_pelayanan').change(function(){ 
      getDataAntrianPasien();
    })

})


function click_selected_patient(id_pl_tc_poli, no_kunjungan, no_mr){
  preventDefault();  
  getMenu('pelayanan/Pl_pelayanan/form/'+id_pl_tc_poli+'/'+no_kunjungan+'?no_mr='+no_mr+'&form=<?php echo $form_type?>');
}
/*format date to m/d/Y*/
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

/*function find pasien*/
function find_pasien_by_keyword(keyword){  

    $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien_by_mr') ?>?keyword=" + keyword, '', function (data) {      
          achtungHideLoader();          

          /*if cannot find data show alert*/
          if( data.count == 0){

            $('#div_load_after_selected_pasien').hide('fast');

            $('#div_riwayat_pasien').hide('fast');
            
            // $('#div_penangguhan_pasien').hide('fast');

            /*reset all field data*/
            $('#no_mr').text('-');$('#noMrHidden').val('');$('#no_ktp').text('-');$('#nama_pasien').text('-');$('#jk').text('-');$('#umur').text('-');$('#alamat').text('-');$('#noKartuBpjs').val('-');$('#kode_perusahaan').text('-');$('#total_kunjungan').text('-');

            alert('Data tidak ditemukan'); return $("#form_cari_pasien").focus();

          }

          // }      
          if( data.count == 1 )     {

            var obj = data.result[0];

            var pending_data_pasien = data.pending; 
            var umur_pasien = hitung_usia(obj.tgl_lhr);
            console.log(pending_data_pasien);
            console.log(hitung_usia(obj.tgl_lhr));

            $('#no_mr').text(obj.no_mr);

            $('#noMrHidden').val(obj.no_mr);

            $('#no_ktp').text(obj.no_ktp);

            $('#nama_pasien').text(obj.nama_pasien+' ('+obj.jen_kelamin+')');

            $('#nama_pasien_hidden').val(obj.nama_pasien);

            $('#jk').text(obj.jen_kelamin);

            $('#umur').text(umur_pasien+' Thn');

            $('#tgl_lhr').text(getFormattedDate(obj.tgl_lhr));
            
            $('#umur_saat_pelayanan_hidden').val(umur_pasien);

            $('#alamat').text(obj.almt_ttp_pasien);

            $('#hp').text(obj.no_hp);

            $('#no_telp').text(obj.tlp_almt_ttp);

            $('#catatan_pasien').text(obj.keterangan);

            $('#noKartuBpjs').val(obj.no_kartu_bpjs);

            if( obj.url_foto_pasien ){

              $('#avatar').attr('src', '<?php echo base_url()?>uploaded/images/photo/'+obj.url_foto_pasien+'');

            }else{

              if( obj.jen_kelamin == 'L' ){
            
                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
                var txt_jk = 'bapak';
              
              }else{
                
                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');
                var txt_jk = 'ibu';

              }

            }

            
            
            if( obj.kode_perusahaan==120){

              $('#form_sep').show('fast'); 

              //showModalFormSep(obj.no_kartu_bpjs,obj.no_mr);

            }else{

              $('#form_sep').hide('fast'); 

            }

            penjamin = (obj.nama_perusahaan==null)?obj.nama_kelompok:obj.nama_perusahaan;
            kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;

            $('#kode_perusahaan').text(penjamin);
            
            $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);
            /*penjamin pasien*/
            $('#kode_kelompok_hidden').val(obj.kode_kelompok);

            $('#InputKeyPenjamin').val(obj.nama_perusahaan);
            $('#InputKeyNasabah').val(obj.nama_kelompok);

            $('#total_kunjungan').text(obj.total_kunjungan);

            $("#myTab li").removeClass("active");
            
            // // txt_call_patient
            // txt_call = ''; 
            // txt_call += txt_jk +' '+ obj.nama_pasien; 
            // txt_call += ' silahkan masuk ke '; 
            // txt_call += '<?php echo ucwords($nama_bagian); ?>'; 
            // $('#txt_call_patient').val(txt_call);

          }      

    }); 

}

function get_riwayat_medis(no_mr){
  $('#cppt_data').html('Loading...'); 
  $('#cppt_data_on_tabs').html('Loading...'); 
  
  $.getJSON("templates/References/get_riwayat_medis/" +no_mr, '', function (data) { 
      $('#cppt_data').html(data.html); 
      $('#cppt_data_on_tabs').html(data.html); 
  });
}

function get_riwayat_pm(no_mr){
  $('#cppt_data').html('Loading...'); 
  $('#cppt_data_on_tabs').html('Loading...'); 
  
  $.getJSON("templates/References/get_riwayat_pm/" +no_mr, '', function (data) { 
      $('#cppt_data').html(data.html); 
      $('#cppt_data_on_tabs').html(data.html); 
  });
}

function getDataAntrianPasien(){

  // getTotalBilling();
  $.getJSON("pelayanan/Pl_pelayanan/get_data_antrian_pasien?bag=" + $('#kode_bagian_val').val()+'&tgl='+$('#tgl_kunjungan').val()+'&status='+$('#status_pelayanan').val()+'', '', function (data) {   
    $('#no_mr_selected option').remove();                
    $('#list_antrian_existing tbody').remove();     
    $('<option value="">-Pilih Pasien-</option>').appendTo($('#no_mr_selected'));  
    var arr = [];
    var arr_cancel = [];
    var no = 0;
    $.each(data, function (i, o) {   
        var selected = (o.no_mr==$('#noMrHidden').val())?'selected':'';
        if(o.kode_perusahaan == 0){
          var penjamin = '<span style="border-radius: 4px; background:rgb(6, 65, 104); padding: 2px; color: white">Umum</span>' ;
        }else{
          var penjamin = (o.kode_perusahaan==120)? '<span style="border-radius: 4px; background:rgb(105, 17, 1); padding: 2px; color: white">'+o.nama_perusahaan+'</span>' : '<span style="border-radius: 4px; background:rgb(4, 78, 14); padding: 2px; color: white">'+o.nama_perusahaan+'</span>' ;

        }
        if(o.kode_perusahaan == 0){
          var txt_color_penjamin = 'background: #6fb3e0' ;
        }else{
          var txt_color_penjamin = (o.kode_perusahaan==120) ? 'background: #f998878c' : 'background:rgb(6, 85, 36)' ;
        }

        var style = ( o.status_batal == 1 ) ? 'style="background-color: red; color: white"' : (o.tgl_keluar_poli == null) ? '' : 'style="background-color: lightgrey; color: black"' ;
        
        no++;
        if(o.status_batal == 1){

            html_cancel = '';
            html_cancel += '<tr style="cursor: pointer" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
            html_cancel += '<td style="'+txt_color_penjamin+'" align="center">'+o.no_antrian+'</td>';
            html_cancel += '<td style="background-color: #f9000059">'+o.nama_pasien+'</td>';
            html_cancel += '<td align="center"><i class="fa fa-times-circle red bigger-120"></i></td>';
            html_cancel += '</tr>';

            html_cancel = '';
            html_cancel += '<tr style="cursor: pointer;" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
            // html_cancel += '<td align="center">'+o.no_antrian+'</td>';
            html_cancel += '<td colspan="3" style="border: 1px solid #e0e0e0;   padding: 5px; width: 100%; min-width: 200px"><b style="font-size: 14px"><span class="antrian-no-kanan">'+o.no_antrian+'</span></b><b>'+o.no_mr+'</b><br>'+o.nama_pasien+'/'+o.jen_kelamin+'/'+o.umur+' Thn <br><small><span style="'+txt_color_penjamin+'; padding: 1px; font-size: 10px !important;">'+penjamin+'</span> <span style="border-radius: 5px; color: white; background: red; font-weight: bold; padding: 2px">Batal</span> </small></td>';
            // html_cancel += '<td align="center"><i class="fa fa-check green bigger-120"></i></td>';
            html_cancel += '</tr>';
            // html_cancel += '<tr>';
            // html_cancel += '<td colspan="3">&nbsp;</td>';
            // html_cancel += '</tr>';

          $(html_cancel).appendTo($('#list_antrian_existing'));
        
        }else{

          if(o.tgl_keluar_poli == null){

            // html_existing = '';
            // html_existing += '<tr style="cursor: pointer" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
            // html_existing += '<td style="'+txt_color_penjamin+'" align="center">'+o.no_antrian+'</td>';
            // html_existing += '<td >'+o.nama_pasien+'</td>';
            // html_existing += '<td align="center"><i class="ace-icon glyphicon glyphicon-time black bigger-120"></i></td>';
            // html_existing += '</tr>';

            html_existing = '';
            html_existing += '<tr style="cursor: pointer;" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
            // html_existing += '<td align="center">'+o.no_antrian+'</td>';
            html_existing += '<td colspan="3" style="border: 1px solid #e0e0e0;   padding: 5px; width: 100%; min-width: 200px"><b style="font-size: 14px"><span class="antrian-no-kanan">'+o.no_antrian+'</span></b><b>'+o.no_mr+'</b><br>'+o.nama_pasien+'/'+o.jen_kelamin+'/'+o.umur+' Thn <br><small><span style="'+txt_color_penjamin+'; padding: 1px; font-size: 10px !important;">'+penjamin+'</span> <span style="border-radius: 5px; color: white; background: darkorange; font-weight: bold; padding: 2px">Belum dilayani</span> </small></td>';
            // html_existing += '<td align="center"><i class="fa fa-check green bigger-120"></i></td>';
            html_existing += '</tr>';
            // html_existing += '<tr>';
            // html_existing += '<td colspan="3">&nbsp;</td>';
            // html_existing += '</tr>';


            $(html_existing).appendTo($('#list_antrian_existing'));

          }

          if(o.tgl_keluar_poli != null){

            html_done = '';
            html_done += '<tr style="cursor: pointer;" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
            // html_done += '<td align="center">'+o.no_antrian+'</td>';
            html_done += '<td colspan="3" style="border: 1px solid #e0e0e0;   padding: 5px; width: 100%; min-width: 200px"><b style="font-size: 14px"><span class="antrian-no-kanan">'+o.no_antrian+'</span></b><b>'+o.no_mr+'</b><br>'+o.nama_pasien+'/'+o.jen_kelamin+'/'+o.umur+' Thn <br><small><span style="'+txt_color_penjamin+'; padding: 1px; font-size: 10px !important;">'+penjamin+'</span> <span style="border-radius: 5px; color: white; background: green; font-weight: bold; padding: 2px">Selesai</span> </small></td>';
            // html_done += '<td align="center"><i class="fa fa-check green bigger-120"></i></td>';
            html_done += '</tr>';
            // html_done += '<tr>';
            // html_done += '<td colspan="3">&nbsp;</td>';
            // html_done += '</tr>';
            $(html_done).appendTo($('#list_antrian_existing'));

          }
        }

        
        
        // sudah dilayani
        if (o.tgl_keluar_poli != null) {
            arr.push(o);
        }
        // batal
        if (o.status_batal == 1) {
          arr_cancel.push(o);
        }
    });   

    // total antrian
    var total_antrian = data.length;
    $('#total_antrian').text(total_antrian);
    // dilayani
    $('#sudah_dilayani').text(arr.length);
    // batal
    $('#pasien_batal').text(arr_cancel.length);

    console.log(arr_cancel.length);
  });

}

function cancel_visit_dr(no_registrasi, no_kunjungan){

  preventDefault();  
  achtungShowLoader();
  if(confirm('Are you sure?')){
    $.ajax({
        url: "pelayanan/Pl_pelayanan/cancel_visit",
        data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#kode_bagian_val').val() },            
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
          var data=xhr.responseText;  
          var jsonResponse = JSON.parse(data);  
          if(jsonResponse.status === 200){  
            $.achtung({message: jsonResponse.message, timeout:5}); 
            getMenu('pelayanan/Pl_pelayanan/form/'+$('#id_pl_tc_poli').val()+'/'+no_kunjungan+'?no_mr='+$('#no_mr_val').val()+'&form=form_rj');
          }else{          
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
          } 
          achtungHideLoader();
        }
    });
  }else{
    return false;
  }

}

function searchTable() {
    var input, filter, found, table, tr, td, i, j;
    input = document.getElementById("seacrh_ul_li");
    filter = input.value.toUpperCase();
    table = document.getElementById("list_antrian_existing");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                found = true;
            }
        }
        if (found) {
            tr[i].style.display = "";
            found = false;
        } else {
            tr[i].style.display = "none";
        }
    }
}

function show_icare() {
    var noka = $('#noKartuBpjs').val();
    var kode_dokter = $('#kode_dokter_bpjs').val();
    if($('#kode_perusahaan_val').val() == 120){
      $.getJSON("ws_bpjs/Ws_index/getIcare/"+noka+"/"+kode_dokter+"", '', function (ress) { 
        var obj = ress.response.metaData;
        console.log(obj);
        if(obj.code != 200){
          $('#tabs_form_pelayanan').html('<div class="alert alert-danger"><strong>'+obj.message+' !</strong><br>Kemungkinan data pada VClaim dan SIMRS tidak sesuai</div>');
        }else{
          $('#tabs_form_pelayanan').html('<iframe src='+ress.url+' style="width: 100%; min-height: 900px" frameborder="no" scrolling="yes"></iframe>');
        }
      });
    }else{
      $('#tabs_form_pelayanan').html('<div class="alert alert-danger"><strong>Pasien Non BPJS !</strong><br>Tidak dapat membuka I-Care JKN</div>');
    }
    
}

</script>

<style type="text/css">
  .pagination{
    margin: 0px 0px !important;
  }
  .well{
    padding: 5px !important;
  }
  select option, select.form-control option {
      padding: 3px 4px 5px;
      /* font-weight: bold; */
  }
  .blink_me {
    animation: blinker 1s linear infinite;
  }
  @keyframes blinker {
    50% {
      opacity: 0;
    }
  }
  .ace-settings-box{
    max-height: 550px !important;
    overflow-y : scroll;
    background: white;
  }
  #ace-settings-container-rj::-webkit-scrollbar {
    width: 10px;
  }
  .ace-settings-box.open{
    width: 350px !important;
  }
  /* Track */
  #ace-settings-container-rj::-webkit-scrollbar-track {
    box-shadow: inset 0 0 5px grey; 
    border-radius: 10px;
  }
  /* Handle */
  #ace-settings-container-rj::-webkit-scrollbar-thumb {
    background: #8cc229; 
    border-radius: 10px;
  }
  /* Handle on hover */
  #ace-settings-container-rj::-webkit-scrollbar-thumb:hover {
    background: #b30000; 
  }
  .user-info{
    max-width: 500px !important;
    width: 100% !important;
  }
  /* Nomor antrian besar di kanan */
  .antrian-no-kanan {
    position: static;
    top: 10px;
    right: 23px;
    float: right;
    font-size: 36px;
    font-weight: bold;
    color: #1976d2;
    opacity: 0.18;
    z-index: 1;
    pointer-events: none;
    line-height: 1;
    text-shadow: 1px 2px 8px #fff;
  }
</style>

<div class="row">
  <div class="page-header">  
      <ul class="nav ace-nav">
        <li class="light-blue" style="color: black; min-width: 260px; min-height: 60px; display: flex; align-items: center; padding: 5px 10px; background: #edf3f7">
          <div style="width:48px; height:48px; border-radius:50%; overflow:hidden; background:#e0e0e0; margin-right:12px; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
            <img src="<?php echo base_url().'uploaded/images/photo_karyawan/'.$value->kode_dokter.'.png'; ?>" alt="Foto Dokter" style="width:48px; height:48px; object-fit:cover;">
          </div>
          <div style="display:flex; flex-direction:column; justify-content:center;">
            <span class="user-info" style="line-height:1.2; color: black">
              <b><?php echo isset($nama_dokter)?''.$nama_dokter.'':''?></b><br>
              <small><?php echo ucwords($nama_bagian); ?></small>
            </span>
          </div>
        </li>

        <!-- <li class="light-blue" style="background-color: lightgrey !important;color: black">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: lightgrey !important; color: black">
            <span class="user-info">
              <b><?php echo isset($nama_dokter)?''.$nama_dokter.'':''?></b>
              <small><?php echo ucwords($nama_bagian); ?></small></span>
          </a>
        </li> -->

        <li class="light-blue" style="background-color: #428bca !important;color: white">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: #428bca !important;color: white">
            <span class="user-info">
              <b><span style="font-size: 18px;" id="total_antrian"></span></b>
              <small>Total Pasien</small></span>
          </a>
        </li>

        <li class="light-blue" style="background-color: #87b87f  !important;color: white">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: #87b87f  !important;color: white">
            <span class="user-info">
              <b><span style="font-size: 18px;" id="sudah_dilayani"></span> </b>
              <small>Telah Dilayani</small></span>
          </a>
        </li>

        <li class="light-blue" style="background-color: #f4ae11 !important;color: white">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: #f4ae11 !important;color: white">
            <span class="user-info">
              <b><span style="font-size: 18px;" id="pasien_batal"></span></b>
              <small>Pasien Batal</small></span>
          </a>
        </li>

        <li style="color: black">
          <a href="#" style="background-color: red !important; color: white" id="btn_update_session_poli">
            Tutup Session Poli
            <i class="ace-icon fa fa-sign-out"></i>
          </a>
        </li>
      </ul>
  </div>  
<div>   

<form class="form-horizontal" method="post" id="form_pelayanan" action="#" enctype="multipart/form-data" autocomplete="off" >      
  
      <!-- hidden form -->
      <input type="hidden" name="noMrHidden" id="noMrHidden" value="<?php echo $no_mr?>">
      <input type="hidden" name="id_pl_tc_poli" id="id_pl_tc_poli" value="<?php echo ($id)?$id:''?>">
      <input type="hidden" name="nama_pasien_hidden" id="nama_pasien_hidden">
      <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dokter_pemeriksa">
      <input type="hidden" name="no_registrasi" id="no_registrasi" class="form-control" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" readonly>
      <input type="hidden" name="no_kunjungan" class="form-control" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan" readonly>
      <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>
      <input type="hidden" name="kode_perjanjian" class="form-control" value="<?php echo isset($value->kode_perjanjian)?$value->kode_perjanjian:''?>" id="kode_perjanjian" readonly>
      <input type="hidden" name="kodebookingantrol" class="form-control" value="<?php echo isset($value->kodebookingantrol)?$value->kodebookingantrol:''?>" id="kodebookingantrol" readonly>
      <input type="hidden" name="taskId" class="form-control" value="4" id="taskId" readonly>
      <input type="hidden" name="noKartuBpjs" class="form-control" id="noKartuBpjs" value="<?php echo $value->no_kartu_bpjs?>" readonly>
    
      <!-- profile Pasien -->
      <div class="col-md-2">
        <div id="antrian_tabs">
          <div class="center">
            <label for="" class="pull-left" style="font-weight: bold; text-align: left !important"> Tanggal kunjungan :</label><br>
            <div class="input-group pull-left">
                <input name="tgl_kunjungan" id="tgl_kunjungan" placeholder="<?php echo date('Y-m-d')?>" class="form-control date-picker" data-date-format="yyyy-mm-dd" type="text" value="<?php echo isset($this->cache->get('cache')['tgl'])?$this->cache->get('cache')['tgl']:date('Y-m-d')?>">
                <span class="input-group-addon">
                  <i class="ace-icon fa fa-calendar"></i>
                </span>
            </div>
            <br>
            <label for="" class="pull-left" style="font-weight: bold; text-align: left !important; padding-top: 5px"> Status Pelayanan :</label><br>
            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'status_pelayanan', 'is_active' => 'Y')), 'Tab' , 'status_pelayanan', 'status_pelayanan', 'form-control', '', '');?>

            <span class="pull-left" style="padding-top: 5px"><b>Cari pasien :</b></span> <br>
            <input type="text" id="seacrh_ul_li" value="" placeholder="Masukan keyword..." class="form-control" onkeyup='searchTable()'>
          </div>
          <br>
          <!-- <div class="center">
              <label for="" class="label label-danger" style="background-color: #f998878c; color: black !important"> BPJS Kesehatan</label>
              <label for="" class="label label-info" style="background-color: #6fb3e0; color: black !important"> Umum & Asuransi</label>
          </div> -->
          <div class="" style="max-height: 1000px; overflow: scroll">
            <!-- <div id="list_antrian_existing"></div> -->
            <table id="list_antrian_existing" class="">
              <tbody></tbody>
            </table>
          </div>

        </div>
      </div>

      <!-- form pelayanan -->
      <div class="col-md-7 no-padding">
        
        <!-- end action form  -->
        
        <div class="pull-left" style="margin-bottom:1%; width: 100%">
          <?php if(empty($value->tgl_keluar_poli)) :?>
          <!-- <a href="#" class="btn btn-xs btn-purple" onclick="perjanjian()"><i class="fa fa-calendar"></i> Perjanjian Pasien</a>
          <a href="#" class="btn btn-xs btn-primary" onclick="selesaikanKunjungan()"><i class="fa fa-check-circle"></i> Selesaikan Kunjungan</a> -->
          <a href="#" class="btn btn-xs btn-danger" onclick="cancel_visit_dr(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>,<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>)"><i class="fa fa-times-circle"></i> Batalkan Kunjungan</a>
          <?php else: echo ''; endif;?>
          
        </div>
        
        <!-- <div class="form-group">
          <label class="control-label col-sm-2" for="" ><i class="fa fa-user bigger-120 green"></i> Antrian Pasien</label>
          <div class="col-sm-7">
            <select class="form-control" name="no_mr_selected" id="no_mr_selected" style="font-weight: bold">
                <option value="">-Silahkan Pilih-</option>
              </select>
          </div>
        </div> -->

        <!-- <p><b><i class="fa fa-edit"></i> DATA REGISTRASI DAN KUNJUNGAN </b></p> -->
        <table class="table table-bordered">
          <tr style="background-color:#f4ae11">
            <td rowspan="2" width="100px" class="center" style="background-color: darkturquoise;">
            <span style="font-size: 11px">No. Antrian </span> <br> <span style="font-size: 30px; font-weight: bold"><?php echo isset($value->no_antrian)?$value->no_antrian:0?></span>
            </td>
            <th width="250px">Pasien</th>
            <th width="100px">Tanggal Daftar</th>
            <th>Dokter</th>
            <th>Penjamin</th>
            <?php if($value->flag_ri==1) : echo '<th>Status Pasien</th>'; endif;?>
            <th width="150px">Diagnosa Rujukan</th>
          </tr>
          <tr>
            <td>
              <a href="#" onclick="show_modal('registration/reg_pasien/form_modal/<?php echo isset($value->no_mr)?$value->no_mr:'';?>', 'DATA PASIEN')"><span style="font-size: 14px; font-weight: bold" id="no_mr"><?php echo isset($value->no_mr)?$value->no_mr:'';?></span></a><br>
              <span id="nama_pasien"><?php echo isset($value->nama_pasien)?$value->nama_pasien:'';?> (<?php echo isset($value->jen_kelamin)?$value->jen_kelamin:'';?>)</span><br>
              Tgl Lhr. <span id="tgl_lhr"><?php echo isset($value->tgl_lhr)?$this->tanggal->formatDate($value->tgl_lhr):''?></span> (<span id="umur"><?php echo isset($value->umur)?$value->umur:'';?></span>)
            </td>
            <td><?php echo isset($value->tgl_jam_poli)?$this->tanggal->formatDateTime($value->tgl_jam_poli):''?></td>
            <td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
            <td><?php echo isset($value->nama_kelompok)?ucwords($value->nama_kelompok).' ':'';?>
            <?php echo isset($value->nama_perusahaan)?'<br>['.$value->nama_perusahaan.']':'';?></td>
            <?php if($value->flag_ri==1) : echo '<td class="center"><label class="label label-danger">Pasien Rawat Inap</label></td>'; endif;?>
            <td><?php echo isset($value->diagnosa_rujukan)?$value->diagnosa_rujukan:'';?></td>
          </tr>
        </table>

        <?php if(isset($value) AND $value->less_then_min_visit==1) :?>
          <div class="alert alert-danger"><strong>Peringatan!</strong> Pasien kurang dari 30 hari kunjungan Pelayanan BPJS.</div>
        <?php endif;?>

        <!-- <?php if(isset($value) AND $value->status_batal==1) :?>
          <span style="margin-left:-19%;position:absolute;transform: rotate(-25deg) !important; margin-top: 21%" class="stamp is-nope-2">Batal Berobat</span>
        <?php else: ?>
          <?php if(isset($value) AND $value->status_periksa!=NULL) :?>
          <span style="margin-left:-19%;position:absolute;transform: rotate(-25deg) !important; margin-top: 21%" class="stamp is-approved">Selesai</span>
          <?php endif;?>            
        <?php endif;?>             -->

        <!-- hidden form -->
        <input type="hidden" class="form-control" name="kode_dokter_bpjs" id="kode_dokter_bpjs" value="<?php echo isset($kode_dokter_bpjs)?$kode_dokter_bpjs:''?>">
        <input type="hidden" class="form-control" name="no_kunjungan" value="<?php echo isset($value)?$value->no_kunjungan:''?>">
        <input type="hidden" class="form-control" name="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
        <input type="hidden" class="form-control" name="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
        <input type="hidden" class="form-control" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>" id="kode_perusahaan_val">
        <input type="hidden" class="form-control" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>" id="no_mr_val">
        <input type="hidden" class="form-control" name="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien:''?>">
        <input type="hidden" class="form-control" name="kode_bagian_asal" value="<?php echo isset($value)?$value->kode_bagian_asal:''?>">
        <input type="hidden" class="form-control" name="kode_bagian" value="<?php echo isset($value)?$value->kode_bagian:''?>" id="kode_bagian_val">
        <input type="hidden" class="form-control" name="kode_klas" value="<?php echo isset($kode_klas)?$kode_klas:''?>" id="kode_klas_val">
        <input type="hidden" class="form-control" name="kode_dokter_poli" id="kode_dokter_poli" value="<?php echo isset($value->kode_dokter)?$value->kode_dokter:''?>">
        <input type="hidden" class="form-control" name="flag_mcu" id="flag_mcu" value="<?php echo isset($value->flag_mcu)?$value->flag_mcu:0?>">
        <input type="hidden" class="form-control" name="tgl_jam_poli" id="tgl_jam_poli" value="<?php echo isset($value->tgl_jam_poli)?$value->tgl_jam_poli:''?>">
        <!-- cek tarif existing -->

        <!-- <p><b><i class="fa fa-edit"></i> FORM PELAYANAN PASIEN </b></p> -->

        <!-- form default pelayanan pasien -->
      <div id="form_default_pelayanan" style="background-color:rgba(195, 220, 119, 0.56)"></div>

        <div class="tabbable">  
          <ul class="nav nav-tabs" id="tab_menu_erm_dokter">

            <li class="active">
              <a data-toggle="tab" id="icare_tabs" href="#" onclick="show_icare()">
                I-Care JKN
              </a>
            </li>
            
            <li id="li_soap">
              <a data-toggle="tab" id="tabs_diagnosa_dr" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>" data-url="pelayanan/Pl_pelayanan/diagnosa_dr/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
                S O A P
              </a>
            </li>

            <li class="hover">
              <a href="#" data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>" data-url="pelayanan/Pl_pelayanan/tindakan_dr/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><span class="menu-text"> Pemeriksaan </span></a><b class="arrow"></b>
            </li>

            <li id="li_cppt">
              <a data-toggle="tab" id="tabs_cppt" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&form=cppt" data-url="pelayanan/Pl_pelayanan/cppt/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                Riwayat Medis
              </a>
            </li>
            <li>
              <a href="#" data-toggle="tab" data-id="<?php echo $id?>" data-url="farmasi/Farmasi_pesan_resep/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>" id="tabs_resep" href="#tabs_resep" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><span class="menu-text"> e-Resep </span></a>

            </li>
            
            <li class="hover">
              <a data-toggle="tab" href="#" data-id="<?php echo $id?>" data-url="registration/Reg_pm/rujuk_pm/<?php echo $value->no_registrasi?>/<?php echo $value->kode_bagian?>/<?php echo $kode_klas?>/rajal" id="tabs_penunjang_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')"><span class="menu-text"> Order Penunjang  </span></a><b class="arrow"></b>
            </li>
            <li>
              <a data-toggle="tab" id="tabs_catatan" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&no_mr=<?php echo $no_mr?>" data-url="pelayanan/Pl_pelayanan/catatan_lainnya/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                Form Rekam Medis
              </a>
            </li>
            <li>
              <a data-toggle="tab" id="tabs_catatan" href="#" data-id="" data-url="pelayanan/Pl_pelayanan/info_harga_obat" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')">
                Informasi Harga Obat
              </a>
            </li>
          </ul>
          
          
          <div class="tab-content">                  

            <div id="tabs_form_pelayanan" class="tab-pane fade in active">

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

      <div class="col-md-3">
        <div class="tabbable" id="">
          <ul class="nav nav-tabs " id="myTab">
              <li>
                  <a data-toggle="tab" href="#rm_tabs" onclick="get_riwayat_medis('<?php echo $no_mr?>')" title="Riwayat Medis">
                      <i class="red ace-icon fa fa-book bigger-150"></i>
                  </a>
              </li>

              <li>
                  <a data-toggle="tab" href="#rm_tabs" onclick="get_riwayat_pm('<?php echo $no_mr?>')" title="Riwayat Penunjang Medis">
                      <i class="green ace-icon fa fa-bookmark bigger-150"></i>
                  </a>
              </li>
          </ul>

          <div class="tab-content">

              <div id="rm_tabs" class="tab-pane fade in active">
                  <div id="cppt_data_on_tabs"></div>
              </div>

          </div>
        </div>
      </div>

</form>

<!-- modal -->

<div id="modalIcare" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;width:80%">

    <div class="modal-content">

      <div class="modal-header no-padding">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="">ICARE JKN BPJS Kesehatan</span>

        </div>

      </div>

      <div class="modal-body no-padding">
          <iframe src="" id="frame_icare" style="width: 100%; min-height: 500px"></iframe>

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


