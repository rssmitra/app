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

    getMenuTabs('pelayanan/Pl_pelayanan/diagnosa_dr/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>', 'tabs_form_pelayanan');

    $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveDiagnosaDr');

    // get data antrian pasien
    getDataAntrianPasien();

    window.filter = function(element)
    {
      var value = $(element).val().toUpperCase();

      $(".itemdiv").each(function() 
      {
        if ($(this).text().toUpperCase().search(value) > -1){
          $(this).show();
        }
        else {
          $(this).hide();
        }
      });
    }


    /*focus on form input pasien*/
    $('#form_cari_pasien').focus();    

    $('#form_pelayanan').on('submit', function(){
               
        $('#konten').val($('#editor_konten').html());
        $('input[name=catatan_pengkajian]' , this).val($('#editor').html());
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
              achtungShowLoader();   
              $(this).find("button[type='submit']").prop('disabled',true);
            },
            uploadProgress: function(event, position, total, percentComplete) {
            },
            complete: function(xhr) {     
              var data=xhr.responseText;    
              var jsonResponse = JSON.parse(data);  

              if( jsonResponse.status === 200 ){   

                $.achtung({message: jsonResponse.message, timeout:5});
                
                if(jsonResponse.type_pelayanan == 'catatan_pengkajian'){
                  oTableCppt.ajax.reload();
                }else{
                  achtungShowFadeIn();
                  if(jsonResponse.next_id_pl_tc_poli != 0){
                    getMenu('pelayanan/Pl_pelayanan/form/'+jsonResponse.next_id_pl_tc_poli+'/'+jsonResponse.next_no_kunjungan+'?no_mr='+jsonResponse.next_pasien+'&form=<?php echo $form_type?>');
                  }else{
                    $('#tabs_form_pelayanan').html('<div class="alert alert-success"><strong>Terima Kasih..!</strong> Pasien sudah terlayani semua. </div>');
                  }
                  pauseStopWatch();
                  resetStopWatch();
                }

              }else{          
                pauseStopWatch();
                resetStopWatch();
                $.achtung({message: jsonResponse.message, timeout:5, className:'achtungFail'});

              }        

              achtungHideLoader();        
              
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

    $('#tgl_kunjungan').change(function(){
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

            $('#umur').text(umur_pasien+' Tahun');

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
              
              }else{
                
                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');

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
            

          }      

    }); 

}

function get_riwayat_medis(no_mr){

  $.getJSON("templates/References/get_riwayat_medis/" +no_mr, '', function (data) { 
      $('#cppt_data').html(data.html); 
      $('#cppt_data_on_tabs').html(data.html); 
  });

}

function getDataAntrianPasien(){

  // getTotalBilling();
  $.getJSON("pelayanan/Pl_pelayanan/get_data_antrian_pasien?bag=" + $('#kode_bagian_val').val()+'&tgl='+$('#tgl_kunjungan').val()+'', '', function (data) {   
    $('#no_mr_selected option').remove();         
    $('#antrian_pasien_tbl tbody').remove();         
    $('#antrian_pasien_tbl_done tbody').remove();         
    $('#antrian_pasien_tbl_cancel tbody').remove();         
    $('<option value="">-Pilih Pasien-</option>').appendTo($('#no_mr_selected'));  
    var arr = [];
    var arr_cancel = [];
    var no = 0;
    $.each(data, function (i, o) {   
        var selected = (o.no_mr==$('#noMrHidden').val())?'selected':'';
        var penjamin = (o.kode_perusahaan==120)? '<span style="background: #f998878c; padding: 3px">('+o.nama_perusahaan+')</span>' : '<span style="background: #6fb3e0; padding: 3px">(UMUM)</span>' ;

        var style = ( o.status_batal == 1 ) ? 'style="background-color: red; color: white"' : (o.tgl_keluar_poli == null) ? '' : 'style="background-color: lightgrey; color: black"' ;

       no++;
        if(o.status_batal == 1){

          html_cancel = '';
          html_cancel += '<div class="itemdiv commentdiv">';
          html_cancel += '<div class="user">';
          html_cancel += '<h2>'+no+'</h2>';
          html_cancel += '</div>';
          html_cancel += '<div class="body" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
          html_cancel += '<div class="name">';
          html_cancel += '<a href="#">'+o.no_mr+'</a>';
          html_cancel += '</div>';
          html_cancel += '<div class="time">';
          html_cancel += '<i class="ace-icon fa fa-times-circle red"></i>';
          html_cancel += '<span class="red">batal kunjungan</span>';
          html_cancel += '</div>';
          html_cancel += '<div class="text">';
          html_cancel += '<span style="font-size: 14px">'+o.nama_pasien+'</span><br>';
          html_cancel += '<span style="font-size:10px">'+penjamin+'</span>';
          html_cancel += '</div>';
          html_cancel += '</div>';
          html_cancel += '</div>';
          
          // html_cancel += '<li class="list-group-item" style="color: red">';
          // html_cancel += '<b>No. '+o.no_antrian+'</b><br><span value="'+o.no_mr+'" data-id="'+o.id_pl_tc_poli+'/'+o.no_kunjungan+'"><a href="#" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')" style="font-weight: bold">No. '+o.no_antrian+'<br>'+o.no_mr+' - '+o.nama_pasien+'<br><span style="font-size: 9px">'+penjamin+'</span><span class="label label-danger pull-right">Batal Berobat</span></span>';
          // html_cancel += '</li>';
          $(html_cancel).appendTo($('#list_antrian_existing'));
        
        }else{

          if(o.tgl_keluar_poli == null){

            html_existing = '';
            html_existing += '<div class="itemdiv commentdiv" style="box-shadow: inset 0 0 10px #0000002e;">';
            html_existing += '<div class="user">';
            html_existing += '<h2 style="margin-top: 6px !important;">'+no+'</h2>';
            html_existing += '</div>';
            html_existing += '<div class="body" style="cursor: pointer" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
            html_existing += '<div class="name">';
            html_existing += '<span style="font-size: 16px; font-weight: bold">'+o.no_mr+'</span>';
            html_existing += '</div>';
            html_existing += '<div class="text">';
            html_existing += '<span style="font-size: 14px">'+o.nama_pasien+'</span><br>';
            html_existing += '<span style="font-size:10px">'+penjamin+'</span>';
            html_existing += '</div>';
            html_existing += '</div>';
            html_existing += '</div>';

            // html_existing += '<li class="list-group-item">';
            // html_existing += '<b>No. '+o.no_antrian+'</b><br><span value="'+o.no_mr+'" data-id="'+o.id_pl_tc_poli+'/'+o.no_kunjungan+'"><a href="#" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')" style="font-weight: bold">'+o.no_mr+' - '+o.nama_pasien+'<br><span style="font-size: 9px">'+penjamin+'</span></span>';
            // html_existing += '</li>';
            $(html_existing).appendTo($('#list_antrian_existing'));

          }

          if(o.tgl_keluar_poli != null){

            html_done = '';
            html_done += '<div class="itemdiv commentdiv" style="background: linear-gradient(45deg, yellowgreen, transparent)">';
            html_done += '<div class="user" style="background: #a7d353">';
            html_done += '<h2 style="margin-top: 6px !important;">'+no+'</h2>';
            html_done += '</div>';
            html_done += '<div class="body" style="cursor: pointer" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
            html_done += '<div class="name">';
            html_done += '<span style="font-size: 16px; font-weight: bold">'+o.no_mr+'</span>';
            html_done += '</div>';
            html_done += '<div class="time">';
            html_done += '<i class="ace-icon fa fa-check-circle green"></i>';
            html_done += '<span class="green"> sudah diperiksa</span>';
            html_done += '</div>';
            html_done += '<div class="text">';
            html_done += '<span style="font-size: 14px">'+o.nama_pasien+'</span><br>';
            html_done += '<span style="font-size:10px">'+penjamin+'</span>';
            html_done += '</div>';
            html_done += '</div>';
            html_done += '</div>';

            // html_done += '<li class="list-group-item">';
            // html_done += '<b>No. '+o.no_antrian+'</b><br><span value="'+o.no_mr+'" data-id="'+o.id_pl_tc_poli+'/'+o.no_kunjungan+'"><a href="#" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')" style="font-weight: bold">'+o.no_mr+' - '+o.nama_pasien+'<br><span style="font-size: 9px">'+penjamin+'</span><span class="label label-success pull-right">Sudah Diperiksa</span></span>';
            // html_done += '</li>';
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
    max-width: 200px !important;
  }

  .itemdiv > .body > .text {
    padding-left: 0px;
    margin-top: 0px;
  }
  .user h2{
    text-align: center !important;
  }

  .itemdiv > .body > .text:after {
    border-top: 0px solid #E4ECF3;
  }

  .itemdiv > .body > .name {
    display: block;
    color: black !important;
}
  

</style>

<div class="row">
  <div class="page-header">  
      <ul class="nav ace-nav">
        <li class="light-blue" style="background-color: lightgrey !important;color: black">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: lightgrey !important; color: black">
            <span class="user-info">
              <b><?php echo isset($nama_dokter)?''.$nama_dokter.'':''?></b>
              <small><?php echo ucwords($nama_bagian); ?></small></span>
          </a>
        </li>

        <li class="light-blue" style="background-color: lightgrey !important;color: black">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: lightgrey !important; color: black">
            <span class="user-info">
              <b><span style="font-size: 14px;" id="total_antrian"></span></b>
              <small>Total Pasien</small></span>
          </a>
        </li>

        <li class="light-blue" style="background-color: lightgrey !important;color: black">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: lightgrey !important; color: black">
            <span class="user-info">
              <b><span style="font-size: 14px;" id="sudah_dilayani"></span> </b>
              <small>Telah Dilayani</small></span>
          </a>
        </li>

        <li class="light-blue" style="background-color: lightgrey !important;color: black">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: lightgrey !important; color: black">
            <span class="user-info">
              <b><span style="font-size: 14px;" id="pasien_batal"></span></b>
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
        <input type="hidden" name="noMrHidden" id="noMrHidden">
        <input type="hidden" name="id_pl_tc_poli" id="id_pl_tc_poli" value="<?php echo ($id)?$id:''?>">
        <input type="hidden" name="nama_pasien_hidden" id="nama_pasien_hidden">
        <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dokter_pemeriksa">
        <input type="hidden" name="no_registrasi" id="no_registrasi" class="form-control" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" readonly>
        <input type="hidden" name="no_kunjungan" class="form-control" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan" readonly>
        <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>
        <input type="hidden" name="kode_perjanjian" class="form-control" value="<?php echo isset($value->kode_perjanjian)?$value->kode_perjanjian:''?>" id="kode_perjanjian" readonly>
        <input type="hidden" name="kodebookingantrol" class="form-control" value="<?php echo isset($value->kodebookingantrol)?$value->kodebookingantrol:''?>" id="kodebookingantrol" readonly>
        <input type="hidden" name="taskId" class="form-control" value="4" id="taskId" readonly>
      
        <!-- profile Pasien -->
        <div class="col-md-2 no-padding">
          <div class="box box-primary" id='box_identity'>
              <img id="avatar" class="profile-user-img img-responsive center" src="<?php echo base_url().'assets/img/avatar.png'?>" alt="User profile picture" style="width:100%">

              <h3 class="profile-username text-center"><div id="no_mr">No. MR</div></h3>

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
                    <small style="color: blue; font-weight: bold; font-size: 11px">Penjamin: </small><div id="kode_perusahaan"></div>
                  </li>
                  <li class="list-group-item">
                    <small style="color: blue; font-weight: bold; font-size: 11px">Catatan: </small><div id="catatan_pasien"></div>
                  </li>
              </ul>

              <a href="#" id="btn_search_pasien" class="btn btn-inverse btn-block">Tampilkan Pasien</a>
            <!-- /.box-body -->
          </div>
        </div>

        <!-- form pelayanan -->
        <div class="col-md-7">
          
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
              <th>Kode</th>
              <th>No Reg</th>
              <th>Tanggal Daftar</th>
              <th>Dokter</th>
              <th>Penjamin</th>
              <?php if($value->flag_ri==1) : echo '<th>Status Pasien</th>'; endif;?>
              <th>Petugas</th>
            </tr>

            <tr>
              <td><?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?></td>
              <td><?php echo isset($value->no_registrasi)?$value->no_registrasi:''?></td>
              <td><?php echo isset($value->tgl_jam_poli)?$this->tanggal->formatDateTime($value->tgl_jam_poli):''?></td>
              <td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
              <td><?php echo isset($value->nama_kelompok)?ucwords($value->nama_kelompok).' / ':'';?>
              <?php echo isset($value->nama_perusahaan)?$value->nama_perusahaan:'';?></td>
              <?php if($value->flag_ri==1) : echo '<td class="center"><label class="label label-danger">Pasien Rawat Inap</label></td>'; endif;?>

              <td><?php echo $this->session->userdata('user')->fullname?></td>
            </tr>

          </table>

          <?php if(isset($value) AND $value->status_batal==1) :?>
          <span style="margin-left:-19%;position:absolute;transform: rotate(-25deg) !important; margin-top: 21%" class="stamp is-nope-2">Batal Berobat</span>
          <?php endif;?>

          <?php if(isset($value) AND $value->status_periksa!=NULL) :?>
          <span style="margin-left:-19%;position:absolute;transform: rotate(-25deg) !important; margin-top: 21%" class="stamp is-approved">Selesai</span>
          <?php endif;?>            

          <!-- hidden form -->
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
            <ul class="nav nav-tabs" id="myTab">
              <li class="active">
                <a data-toggle="tab" id="tabs_diagnosa_dr" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>" data-url="pelayanan/Pl_pelayanan/diagnosa_dr/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                  Form Resume Medis
                </a>
              </li>
              <li>
                <a data-toggle="tab" id="tabs_cppt" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&form=cppt" data-url="pelayanan/Pl_pelayanan/cppt/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                  Input CPPT
                </a>
              </li>

              <li>
                <a data-toggle="tab" id="tabs_catatan" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&no_mr=<?php echo $no_mr?>" data-url="pelayanan/Pl_pelayanan/catatan_lainnya/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                  Pengkajian Pasien
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

        <div class="col-md-3 no-padding">
          <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#antrian_tabs">
                        <i class="green ace-icon fa fa-user bigger-120"></i>
                        Antrian Pasien
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#rm_tabs">
                        <i class="green ace-icon fa fa-history bigger-120"></i>
                        Riwayat Medis
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="antrian_tabs" class="tab-pane fade in active">
                  <div class="center">
                    <label for="" class="pull-left" style="font-weight: bold; text-align: left !important"> Tanggal kunjungan :</label><br>
                    <div class="input-group pull-left">
                        <input name="tgl_kunjungan" id="tgl_kunjungan" placeholder="<?php echo date('Y-m-d')?>" class="form-control date-picker" data-date-format="yyyy-mm-dd" type="text" value="<?php echo isset($this->cache->get('cache')['tgl'])?$this->cache->get('cache')['tgl']:date('Y-m-d')?>">
                        <span class="input-group-addon">
                          <i class="ace-icon fa fa-calendar"></i>
                        </span>
                    </div>
                    <br>
                    <span class="pull-left" style="padding-top: 10px"><b>Cari pasien :</b></span> <br>
                    <input type="text" id="seacrh_ul_li" value="" placeholder="Masukan keyword..." class="form-control" onkeyup="filter(this);">
                  </div>
                  <br>
                  
                  <div class="center">
                      <label for="" class="label label-danger" style="background-color: #f998878c; color: black !important"> BPJS Kesehatan</label>
                      <label for="" class="label label-info" style="background-color: #6fb3e0; color: black !important"> Umum & Asuransi</label>
                  </div>
                  <br>

                  <div class="comments ace-scroll"  style="position: relative;height: 650px;overflow: scroll;">
                    <div id="list_antrian_existing"></div>
                  </div>
                  
                  <!-- <div id="div_antrian_existing">
                    <center><span style="font-weight: bold; margin-top: 10px; font-size: 14px;">Antrian Pasien Belum Diperiksa</span></center>
                    <ol class="list-group list-group-unbordered" id="list_antrian_existing" style="background-color:white;height: 650px;overflow: scroll;">
                    </ol>

                    <center><span style="font-weight: bold; margin-top: 10px; font-size: 14px;">Sudah Diperiksa</span></center>
                    <ol class="list-group list-group-unbordered" id="list_antrian_done" style="background-color:white;height: 650px;overflow: scroll;">
                    </ol>

                    <center><span style="font-weight: bold; margin-top: 10px; font-size: 14px;">Batal Berobat</span></center>
                    <ol class="list-group list-group-unbordered" id="list_antrian_cancel" style="background-color:white;height: 650px;overflow: scroll;">
                    </ol>
                  </div>

                    <span style="font-weight: bold"><i class="fa fa-list blue"></i> Belum Diperiksa</span>
                    <table class="table" id="antrian_pasien_tbl" style="background: linear-gradient(45deg, #c0ec70, transparent) !important">
                        <tbody>
                            <tr><td><span style="font-weight: bold; color: red; font-style: italic">Silahkan tunggu...</span></td></tr>
                        </tbody>
                    </table>
                    <br>
                    <span style="font-weight: bold"><i class="fa fa-check-square-o green"></i> Sudah Diperiksa</span>
                    <table class="table" id="antrian_pasien_tbl_done" style="background: linear-gradient(77deg, #fee951cc, transparent)">
                        <tbody>
                            <tr><td><span style="font-weight: bold; color: red; font-style: italic">Silahkan tunggu...</span></td></tr>
                        </tbody>
                    </table>
                    <br>
                    <span style="font-weight: bold"><i class="fa fa-times-circle red"></i> Batal Berobat</span>
                    <table class="table" id="antrian_pasien_tbl_cancel" style="background: linear-gradient(45deg, #ef8e8e, transparent)">
                        <tbody>
                            <tr><td><span style="font-weight: bold; color: red; font-style: italic">Silahkan tunggu...</span></td></tr>
                        </tbody>
                    </table> -->

                </div>

                <div id="rm_tabs" class="tab-pane fade">
                    <div id="cppt_data_on_tabs"></div>
                </div>
            </div>
          </div>
        </div>

  </form>


