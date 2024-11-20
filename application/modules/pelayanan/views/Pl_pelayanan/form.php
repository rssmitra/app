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
    
    // getMenuTabs('pelayanan/Pl_pelayanan/tindakan/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>', 'tabs_form_pelayanan');
    
    <?php if($form_type == 'billing_entry') : ?>
      getMenuTabsHtml('templates/References/get_riwayat_medis/<?php echo $no_mr?>', 'section_rekam_medis');
    <?php else :?>
      // get_riwayat_medis('<?php echo $no_mr?>');
    <?php endif;?>

    // show_icare();

    // get data antrian pasien
    // getDataAntrianPasien();

    /*focus on form input pasien*/
    $('#form_cari_pasien').focus();    

    $('#form_pelayanan').on('submit', function(){
               
        $('#konten').val($('#editor_konten').html());
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
              if( $('#form_pelayanan').attr('action')=='pelayanan/Pl_pelayanan/processPelayananSelesai' ){
                  achtungShowFadeIn();                      
              }  
            },
            uploadProgress: function(event, position, total, percentComplete) {
            },
            complete: function(xhr) {     

              var data=xhr.responseText;    
              var jsonResponse = JSON.parse(data);  

              if( jsonResponse.status === 200 ){    

                $.achtung({message: jsonResponse.message, timeout:5});  
                $('#table-pesan-resep').DataTable().ajax.reload(null, false);
                $('#jumlah_r').val('');
                $("#modalEditPesan").modal('hide');  

                if(jsonResponse.type_pelayanan == 'penunjang_medis' ){
                  $('#table_order_penunjang').DataTable().ajax.reload(null, false);
                }

                if(jsonResponse.type_pelayanan == 'create_perjanjian' ){

                  $('#tabs_form_pelayanan').load(jsonResponse.redirect);
                  $('#tabs_form_pelayanan').css("padding","5px");

                }

                if( jsonResponse.type_pelayanan == 'pasien_selesai' ){
                  // back after process
                  if( jsonResponse.next_id_pl_tc_poli != '' ){
                    getMenu('pelayanan/Pl_pelayanan/form/'+jsonResponse.next_id_pl_tc_poli+'/'+jsonResponse.next_no_kunjungan+'?no_mr='+jsonResponse.next_pasien+'&form=<?php echo $form_type?>');
                  }else{
                    getMenu('pelayanan/Pl_pelayanan');
                  }

                }

                if( jsonResponse.type_pelayanan == 'Expertise' ){
                  // back after process
                  $('#kode_expertise').val(jsonResponse.ID);

                }

                
              }else{          

                $.achtung({message: jsonResponse.message, timeout:5, className:'achtungFail'});  
                //focus tabs diagnosa
                // getMenuTabs('pelayanan/Pl_pelayanan/diagnosa/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>', 'tabs_form_pelayanan'); 

              }        

              achtungHideLoader();        

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

    /*onchange form module when click tabs*/
    $('#tabs_tindakan').click(function (e) {    
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/process');
      // backToDefaultForm();
    });

    $('#tabs_diagnosa').click(function (e) {    
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveDiagnosa');
      // backToDefaultForm();
    });

    $('#tabs_pesan_resep').click(function (e) {  
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'farmasi/Farmasi_pesan_resep/process');
      // backToDefaultForm();
    });   

    $('#tabs_penunjang_medis').click(function (e) {   
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'registration/Reg_pm/process');
      // backToDefaultForm();
    });
    
    $('#tabs_input_usg').click(function (e) {   
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/process_input_expertise');
    });

    $('#btn_perjanjian_rj').click(function (e) {   
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'registration/Reg_pasien/process_perjanjian');
    });

    $('#tabs_riwayat_transaksi').click(function (e) {     
      e.preventDefault();  
      backToDefaultForm();
    });

    $('#tabs_billing_pasien').click(function (e) {     
      e.preventDefault();  
      backToDefaultForm();
    });

    $('#tabs_perjanjian').click(function (e) {     
      e.preventDefault();  
      backToDefaultForm();
    });

    

    /*onchange form module when click tabs*/   

    $('#no_mr_selected').change(function (e) {  
      e.preventDefault();  
      var element = $(this).find('option:selected'); 
      var params_id = element.attr("data-id");
      getMenu('pelayanan/Pl_pelayanan/form/'+params_id+'?no_mr='+$(this).val()+'&form=<?php echo $form_type?>');
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

            $('#txt_no_mr').text(obj.no_mr);

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

  $('#cppt_data').html('Loading...'); 
  $('#cppt_data_on_tabs').html('Loading...'); 
  $.getJSON("templates/References/get_riwayat_medis/" +no_mr, '', function (data) { 
      $('#cppt_data').html(data.html); 
      $('#cppt_data_on_tabs').html(data.html); 
  });

}

function getDataAntrianPasien(){

  // getTotalBilling();
  $.getJSON("pelayanan/Pl_pelayanan/get_data_antrian_pasien?bag=" + $('#kode_bagian_val').val()+'&tgl='+$('#tgl_kunjungan').val()+'', '', function (data) {   
    $('#no_mr_selected option').remove();                
    $('#list_antrian_existing tbody').remove();     
    $('<option value="">-Pilih Pasien-</option>').appendTo($('#no_mr_selected'));  
    var arr = [];
    var arr_cancel = [];
    var no = 0;
    $.each(data, function (i, o) {   
        var selected = (o.no_mr==$('#noMrHidden').val())?'selected':'';
        var penjamin = (o.kode_perusahaan==120)? '<span style="background: #f998878c; padding: 3px">('+o.nama_perusahaan+')</span>' : '<span style="background: #6fb3e0; padding: 3px">(UMUM)</span>' ;
        var txt_color_penjamin = (o.kode_perusahaan==120) ? 'background: #f998878c' : 'background: #6fb3e0' ;

        var style = ( o.status_batal == 1 ) ? 'style="background-color: red; color: white"' : (o.tgl_keluar_poli == null) ? '' : 'style="background-color: lightgrey; color: black"' ;
        
        no++;
        if(o.status_batal == 1){

            html_cancel = '';
            html_cancel += '<tr style="cursor: pointer" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
            html_cancel += '<td style="'+txt_color_penjamin+'" align="center">'+o.no_antrian+'</td>';
            html_cancel += '<td style="background-color: #f9000059">'+o.nama_pasien+'</td>';
            html_cancel += '<td align="center"><i class="fa fa-times-circle red bigger-120"></i></td>';
            html_cancel += '</tr>';
          $(html_cancel).appendTo($('#list_antrian_existing'));
        
        }else{

          if(o.tgl_keluar_poli == null){

            html_existing = '';
            html_existing += '<tr style="cursor: pointer" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
            html_existing += '<td style="'+txt_color_penjamin+'" align="center">'+o.no_antrian+'</td>';
            html_existing += '<td >'+o.nama_pasien+'</td>';
            html_existing += '<td align="center"><i class="ace-icon glyphicon glyphicon-time black bigger-120"></i></td>';
            html_existing += '</tr>';
            $(html_existing).appendTo($('#list_antrian_existing'));

          }

          if(o.tgl_keluar_poli != null){

            html_done = '';
            html_done += '<tr style="cursor: pointer" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
            html_done += '<td style="'+txt_color_penjamin+'" align="center">'+o.no_antrian+'</td>';
            html_done += '<td style="background-color: #00800059">'+o.nama_pasien+'</td>';
            html_done += '<td align="center"><i class="fa fa-check green bigger-120"></i></td>';
            html_done += '</tr>';
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

function getTotalBilling(){

  $.getJSON("adm_pasien/pembayaran_dr/Pembentukan_saldo_dr/get_total_billing_dr_current_day?kode_dokter="+$('#kode_dokter_poli').val()+"&kode_bagian="+$('#kode_bagian_val').val()+"", '', function (data) {  
    $('#total_bill_dr_current').text(formatMoney(data.total_billing));
  });

}

function selesaikanKunjungan(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  getMenuTabs('pelayanan/Pl_pelayanan/diagnosa/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>', 'tabs_form_pelayanan');
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processPelayananSelesai?bag='+$('#kode_bagian_val').val()+'');
  $('#form_default_pelayanan').show('fast');
  $('#form_default_pelayanan').load('pelayanan/Pl_pelayanan/form_end_visit?mr='+noMr+'&id='+$('#id_pl_tc_poli').val()+'&no_kunjungan='+$('#no_kunjungan').val()+''); 

}

function backToDefaultForm(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processPelayananSelesai');
  $('#form_default_pelayanan').hide('fast');
  $('#form_default_pelayanan').html(''); 
  
}

function perjanjian(){
  noMr = $('#noMrHidden').val();
  if (noMr == '') {
    alert('Silahkan cari pasien terlebih dahulu !'); return false;    
  }else{
    $('#form_modal').load('pelayanan/Pl_pelayanan/form_perjanjian_modal/'+noMr+'?kode_bagian='+$('#kode_bagian_val').val()+'&kode_dokter='+$('#kode_dokter_poli').val()+'&kode_perusahaan='+$('#kode_perusahaan_val').val()+'&no_sep='+$('#no_sep').val()+''); 
    // $('#form_modal').load('registration/reg_pasien/form_perjanjian_modal/'+noMr+'?kode_bagian='+$('#kode_bagian_val').val()+'&kode_dokter='+$('#kode_dokter_poli').val()+'&kode_perusahaan='+$('#kode_perusahaan_val').val()+'&no_sep='+$('#no_sep').val()+''); 
    $("#GlobalModal").modal();
  }
}

function cancel_visit(no_registrasi, no_kunjungan){

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
}

function rollback(no_registrasi, no_kunjungan, flag){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan/rollback",
      data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#kode_bagian_val').val(), flag: flag },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          getMenu('pelayanan/Pl_pelayanan/form/'+$('#id_pl_tc_poli').val()+'/'+no_kunjungan+'?no_mr='+$('#noMrHidden').val()+'&form=<?php echo $form_type?>');
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

}

function show_icare() {
    var noka = $('#noKartuBpjs').val();
    var kode_dokter = $('#kode_dokter_bpjs').val();
    preventDefault();
    $('#tabs_form_pelayanan').html('Loading...');
    $.getJSON("ws_bpjs/Ws_index/getIcare/" +noka+"/"+kode_dokter+"", '', function (ress) { 
      var obj = ress.response.metaData;
      console.log(obj);
      if(obj.code != 200){
        $('#tabs_form_pelayanan').html('<div class="alert alert-danger"><strong>'+obj.message+' !</strong><br>Kemungkinan data pada VClaim dan SIMRS tidak sesuai</div>');
      }else{
        $('#tabs_form_pelayanan').html('<iframe src='+ress.url+' style="width: 100%; min-height: 900px" frameborder="no" scrolling="yes"></iframe>');
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
    background: lightblue;
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
  

</style>

<!-- <div class="scrollbar ace-settings-container" id="ace-settings-container-rj" style="position: fixed">
  <div class="btn btn-app btn-xs btn-primary ace-settings-btn" id="ace-settings-btn-rj">
    <i class="ace-icon fa fa-file bigger-130"></i>
  </div>

  <div class="ace-settings-box clearfix" id="ace-settings-box-rj">

    <div class="pull-left">
        <center><b>PENGKAJIAN MEDIS RAWAT JALAN</b><hr></center>
        <div id="cppt_data">Tidak ada data ditemukan</div>
    </div>


  </div>
</div>  -->


<div class="row">

<?php if($form_type != 'billing_entry') : ?>
  <div class="page-header">  
      <ul class="nav ace-nav" style="padding-left: 10px">
        <li class="light-blue" style="background-color: lightgrey !important;color: black">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: lightgrey !important; color: black">
            <span class="user-info" style="max-width: 250px !important;">
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
<?php else: ?>

  <div class="page-header">
    <h1>
      Entry Billing Rawat Jalan
      <small>
        <i class="ace-icon fa fa-angle-double-right"></i>
        Input Billing Tindakan Pasien Rawat Jalan
      </small>
    </h1>
  </div>
<?php endif; ?>

<div>   

  <form class="form-horizontal" method="post" id="form_pelayanan" action="#" enctype="multipart/form-data" autocomplete="off" >      
    
        <!-- hidden form -->
        <input type="hidden" name="noMrHidden" id="noMrHidden" value="<?php echo $no_mr?>">
        <input type="hidden" name="kode_dokter_bpjs" id="kode_dokter_bpjs" value="<?php echo isset($riwayat->kode_dokter_bpjs)?$riwayat->kode_dokter_bpjs:''?>">
        <input type="hidden" name="id_pl_tc_poli" id="id_pl_tc_poli" value="<?php echo ($id)?$id:''?>">
        <input type="hidden" name="nama_pasien_hidden" id="nama_pasien_hidden">
        <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dokter_pemeriksa">
        <input type="hidden" name="no_registrasi" id="no_registrasi" class="form-control" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" readonly>
        <input type="hidden" name="no_kunjungan" class="form-control" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan" readonly>
        <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>
        <input type="hidden" name="noKartuBpjs" class="form-control" id="noKartuBpjs" value="<?php echo $value->no_kartu_bpjs?>" readonly>
        <!-- hidden form -->
        <input type="hidden" class="form-control" name="no_sep" id="no_sep" value="<?php echo isset($value->no_sep)?$value->no_sep:''?>">
        <input type="hidden" class="form-control" name="kode_kelompok" id="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
        <input type="hidden" class="form-control" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>" id="kode_perusahaan_val">
        <input type="hidden" class="form-control" name="no_mr" id="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
        <input type="hidden" class="form-control" name="nama_pasien_layan" id="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien:''?>">
        <input type="hidden" class="form-control" name="kode_bagian_asal" id="kode_bagian_asal" value="<?php echo isset($value)?$value->kode_bagian_asal:''?>">
        <input type="hidden" class="form-control" name="kode_bagian" value="<?php echo isset($value)?$value->kode_bagian:''?>" id="kode_bagian_val">
        <!-- <input type="hidden" class="form-control" name="kode_klas" value="<?php echo isset($kode_klas)?$kode_klas:''?>" id="kode_klas_val"> -->
        <input type="hidden" class="form-control" name="kode_dokter_poli" id="kode_dokter_poli" value="<?php echo isset($value->kode_dokter)?$value->kode_dokter:''?>">
        <input type="hidden" class="form-control" name="flag_mcu"  id="flag_mcu" value="<?php echo isset($value->flag_mcu)?$value->flag_mcu:0?>">
        <input type="hidden" class="form-control" name="kode_profit"  id="kode_profit" value="<?php echo $kode_profit; ?>">
      
        <!-- profile Pasien -->
        <div class="col-md-2">
          <div class="box box-primary" id='box_identity'>
              <img id="avatar" class="profile-user-img img-responsive center" src="<?php echo base_url().'assets/img/avatar.png'?>" alt="User profile picture" style="width:100%">

              <h3 class="profile-username text-center"><div id="txt_no_mr">No. MR</div></h3>

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
        <div class="<?php echo ($form_type != 'billing_entry') ? 'col-md-7':'col-md-10';?> no-padding">

          <div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse ace-save-state">
            <div class="center">
              <ul class="nav nav-list">

                <li class="hover">
                  <a href="#" data-toggle="tab" id="icare_tabs" href="#" onclick="show_icare()"><i class="menu-icon fa fa-leaf"></i><span class="menu-text"> i-Care JKN </span></a><b class="arrow"></b>
                </li>

                <li class="hover">
                  <a href="#" data-toggle="tab" id="tabs_diagnosa" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>&form=billing_entry" data-url="pelayanan/Pl_pelayanan/diagnosa_dr/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-user"></i><span class="menu-text"> S O A P </span></a><b class="arrow"></b>
                </li>

                <li class="hover">
                  <a href="#" data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>" data-url="pelayanan/Pl_pelayanan/tindakan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-stethoscope"></i><span class="menu-text"> Tindakan </span></a><b class="arrow"></b>
                </li>

                <li class="hover" id="li_tabs_farmasi">
                  <a href="#" data-toggle="tab" data-id="<?php echo $id?>" data-url="farmasi/Farmasi_pesan_resep/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>" id="tabs_pesan_resep" href="#tabs_pesan_resep" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-glass"></i><span class="menu-text"> e-Resep </span></a><b class="arrow"></b>
                </li>

                <li class="hover">
                  <a href="#" data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/Reg_pm/rujuk_pm/<?php echo $value->no_registrasi?>/<?php echo $value->kode_bagian?>/<?php echo $kode_klas?>/rajal" id="tabs_penunjang_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-flask"></i><span class="menu-text"> Penunjang </span></a><b class="arrow"></b>
                </li>

                <li class="hover">
                  <a data-toggle="tab" id="tabs_catatan" href="#" data-id="<?php echo $value->no_kunjungan?>?type=Rajal&no_mr=<?php echo $value->no_mr?>" data-url="pelayanan/Pl_pelayanan/catatan_lainnya/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                    <i class="menu-icon fa fa-folder"></i><span class="menu-text"> Form Rekam Medis </span><b class="arrow"></b>
                  </a>
                </li>

                <li class="hover">
                  <a href="#" data-toggle="tab" class="dropdown-toggle">
                    <i class="menu-icon fa fa-edit"></i>
                    <span class="menu-text">
                      Expertise
                    </span>
                    <b class="arrow fa fa-angle-down"></b>
                  </a>

                  <b class="arrow"></b>

                  <ul class="submenu">
                    <li class="hover" style="text-align: left">
                      <a href="#" data-toggle="tab" id="tabs_input_usg" data-id="<?php echo $no_kunjungan?>?type=Rajal&title=USG&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>&kode_bag_input=050201" data-url="pelayanan/Pl_pelayanan/input_hasil_pemeriksaan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                        <i class="menu-icon fa fa-caret-right"></i>
                        USG
                      </a>
                      <b class="arrow"></b>
                    </li>
                    <li class="hover" style="text-align: left">
                      <a href="#" data-toggle="tab" id="tabs_input_fisio" data-id="<?php echo $no_kunjungan?>?type=Rajal&title=Fisioterapi&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>&kode_bag_input=050301" data-url="pelayanan/Pl_pelayanan/input_hasil_pemeriksaan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Fisioterapi
                      </a>
                      <b class="arrow"></b>
                    </li>
                    <li class="hover" style="text-align: left">
                      <a href="#" data-toggle="tab" id="tabs_input_echoradiography" data-id="<?php echo $no_kunjungan?>?type=Rajal&title=Echoradiography&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>&kode_bag_input=050301" data-url="pelayanan/Pl_pelayanan/input_hasil_pemeriksaan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Echoradiography
                      </a>
                      <b class="arrow"></b>
                    </li>
                    <li class="hover" style="text-align: left">
                      <a href="#" data-toggle="tab" id="tabs_input_tonometri" data-id="<?php echo $no_kunjungan?>?type=Rajal&title=Tonometri&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>&kode_bag_input=050301" data-url="pelayanan/Pl_pelayanan/input_hasil_pemeriksaan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Tonometri
                      </a>
                      <b class="arrow"></b>
                    </li>
                    <li class="hover" style="text-align: left">
                      <a href="#" data-toggle="tab" id="tabs_input_audiometri" data-id="<?php echo $no_kunjungan?>?type=Rajal&title=Audiometri&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>&kode_bag_input=050301" data-url="pelayanan/Pl_pelayanan/input_hasil_pemeriksaan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Audiometri
                      </a>
                      <b class="arrow"></b>
                    </li>
                    <li class="hover" style="text-align: left">
                      <a href="#" data-toggle="tab" id="tabs_input_uroflometri" data-id="<?php echo $no_kunjungan?>?type=Rajal&title=Uroflometri&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>&kode_bag_input=050301" data-url="pelayanan/Pl_pelayanan/input_hasil_pemeriksaan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Uroflometri
                      </a>
                      <b class="arrow"></b>
                    </li>
                  </ul>
                </li>

                <li class="hover">
                  <a href="#" data-toggle="tab" class="dropdown-toggle">
                    <i class="menu-icon fa fa-history"></i>
                    <span class="menu-text">
                      Riwayat
                    </span>
                    <b class="arrow fa fa-angle-down"></b>
                  </a>

                  <b class="arrow"></b>
                  <ul class="submenu">

                    <li class="hover" style="text-align: left">
                      <a data-toggle="tab" id="tabs_cppt" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&form=cppt" data-url="pelayanan/Pl_pelayanan/cppt/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      <span class="menu-text"> Riwayat Medis Pasien</span></a><b class="arrow"></b>
                      </a>
                    </li>
                    
                    <li class="hover" style="text-align: left">
                        <a href="#" data-toggle="tab" data-id="<?php echo $id?>" data-url="billing/Billing/getDetail/<?php echo $value->no_registrasi?>/RJ" id="tabs_billing_pasien" href="#" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')"><span class="menu-text"> Billing Pasien</span></a><b class="arrow"></b>
                    </li>
                    <li class="hover" style="text-align: left">
                      <a href="#" data-toggle="tab"  data-id="<?php echo $id?>" data-url="registration/perjanjian_rj/get_by_mr/<?php echo $value->no_mr?>" id="tabs_perjanjian" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><span class="menu-text"> Riwayat Perjanjian </span></a><b class="arrow"></b>
                    </li>

                    <!-- <li class="hover" style="text-align: left">
                        <a href="#" data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/reg_pasien/riwayat_transaksi/<?php echo $value->no_mr?>" id="tabs_riwayat_transaksi" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-file"></i><span class="menu-text"> Transaksi </span></a><b class="arrow"></b>
                    </li> -->
                    <!-- <li class="hover" style="text-align: left">
                        <a href="#" data-toggle="tab" data-id="<?php echo $id?>" data-url="rekam_medis/File_rm/index/<?php echo $value->no_mr?>" id="tabs_rekam_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-clipboard"></i><span class="menu-text"> Rekam Medis  </span></a><b class="arrow"></b>
                    </li> -->
                  </ul>
                </li>

                <?php 
                  $trans_kasir = $this->Pl_pelayanan->cek_transaksi_kasir(isset($value->no_registrasi)?$value->no_registrasi:'',isset($value->no_kunjungan)?$value->no_kunjungan:'');
                  $flag_rollback = ($trans_kasir!=true)?'submited':'unsubmit';
                  if($flag_rollback == 'unsubmit') :
                ?>

                <!-- <li class="hover">
                  <a href="#" data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/reg_pasien/riwayat_transaksi/<?php echo $value->no_mr?>" id="tabs_riwayat_transaksi" href="#" onclick="rollback(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>, <?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>, '<?php echo $flag_rollback?>')"><i class="menu-icon fa fa-undo"></i><span class="menu-text"> Rollback  </span></a><b class="arrow"></b>
                </li> -->

                <li class="hover">
                  <a href="#" data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/reg_pasien/riwayat_transaksi/<?php echo $value->no_mr?>" id="tabs_riwayat_transaksi" href="#" onclick="getMenu('pelayanan/Pl_pelayanan/form/<?php echo $id?>/<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>?no_mr=<?php echo isset($value->no_mr)?$value->no_mr:''?>&form=billing_entry')"><i class="menu-icon fa fa-undo"></i><span class="menu-text"> Refresh  </span></a><b class="arrow"></b>
                </li>

                <?php endif; ?>

              </ul><!-- /.nav-list -->
            </div>
          </div>
          
          <!-- end action form  -->
          
          <div class="pull-left" style="margin-bottom:1%; width: 100%">
            <a href="#" class="btn btn-xs btn-purple" id="btn_perjanjian_rj" onclick="getMenuTabs('pelayanan/Pl_pelayanan/form_perjanjian_view_ontabs/<?php echo $value->no_mr?>?kode_bagian=<?php echo $value->kode_bagian_asal?>&kode_dokter=<?php echo $value->kode_dokter?>&no_kunjungan=<?php echo $value->no_kunjungan?>&kode_perusahaan=<?php echo $value->kode_perusahaan?>&no_sep=<?php echo $value->no_sep?>', 'tabs_form_pelayanan')" ><i class="fa fa-calendar"></i> Form Perjanjian Pasien</a>
            
            <?php if(empty($value->tgl_keluar_poli)) :?>
              <a href="#" class="btn btn-xs btn-primary" onclick="selesaikanKunjungan()"><i class="fa fa-check-circle"></i> Selesaikan Kunjungan</a>
              <a href="#" class="btn btn-xs btn-danger" onclick="cancel_visit(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>,<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>)"><i class="fa fa-times-circle"></i> Batalkan Kunjungan</a>
            <?php else: echo ''; 
            endif;?>

            <a href="#" class="btn btn-xs btn-danger" onclick="rollback(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>, <?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>, '<?php echo $flag_rollback?>')"><i class="fa fa-refresh"></i> Rollback Kunjungan</a>
            
          </div>
          
          <br>
          <!-- <div class="form-group">
            <label class="control-label col-sm-2" for="" ><i class="fa fa-user bigger-120 green"></i> Antrian Pasien</label>
            <div class="col-sm-7">
              <select class="form-control" name="no_mr_selected" id="no_mr_selected" style="font-weight: bold">
                  <option value="">-Silahkan Pilih-</option>
                </select>
            </div>
          </div> -->

          <!-- <p><b><i class="fa fa-edit"></i> DATA REGISTRASI DAN KUNJUNGAN </b></p> -->

          <div class="col-md-12 no-padding" style="padding-bottom: 5px !important">
              <label style="font-weigth: bold !important"><b>Hak Kelas Pasien :</b> </label><br>
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_klas', 'id' => 'kode_klas', 'name' => 'nama_klas', 'where' => array('is_active' => 1)), isset($kode_klas)?$kode_klas:'' , 'kode_klas', 'kode_klas_val', 'form-control', '', '') ?>
          </div>

          <table class="table table-bordered">
            <tr style="background-color:#f4ae11">
              <td rowspan="2" width="100px" class="center" style="background-color: darkturquoise;">
              <span style="font-size: 11px">No. Antrian </span> <br> <span style="font-size: 30px; font-weight: bold"><?php echo isset($value->no_antrian)?$value->no_antrian:0?></span>
              </td>
              <!-- <th>Kode</th>
              <th>No Reg</th> -->
              <th width="150px">Tanggal Daftar</th>
              <th width="200px">Dokter</th>
              <th>Penjamin</th>
              <th>No SEP</th>
              <?php if($value->flag_ri==1) : echo '<th>Status Pasien</th>'; endif;?>
              <!-- <th width="100px">Petugas</th> -->
            </tr>

            <tr>
              <!-- <td><?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?></td>
              <td><?php echo isset($value->no_registrasi)?$value->no_registrasi:''?></td> -->
              <td align="center"><?php echo isset($value->tgl_jam_poli)?$this->tanggal->formatDateTime($value->tgl_jam_poli):''?></td>
              <td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
              <td><?php echo isset($value->nama_kelompok)?ucwords($value->nama_kelompok).' / ':'';?>
              <?php echo isset($value->nama_perusahaan)?$value->nama_perusahaan:'';?>
              </td>
              <td><?php echo isset($value->kode_perusahaan)?($value->kode_perusahaan==120) ? $value->no_sep : '' : '';?></td>
              <?php if($value->flag_ri==1) : echo '<td class="center"><label class="label label-danger">Pasien Rawat Inap</label></td>'; endif;?>

              <!-- <td><?php echo $this->session->userdata('user')->fullname?></td> -->
            </tr>

          </table>

          <?php if(isset($value) AND $value->status_batal==1) :?>
          <span style="margin-left:-19%;position:absolute;transform: rotate(-25deg) !important; margin-top: 21%" class="stamp is-nope-2">Batal Berobat</span>
          <?php endif;?>

          <?php if(isset($value) AND $value->status_periksa!=NULL) :?>
          <span style="margin-left:-19%;position:absolute;transform: rotate(-25deg) !important; margin-top: 21%" class="stamp is-approved">Selesai</span>
          <?php endif;?>            

          <!-- <p><b><i class="fa fa-edit"></i> FORM PELAYANAN PASIEN </b></p> -->
          

          <!-- form default pelayanan pasien -->
          <div id="form_default_pelayanan" style="background-color:rgba(195, 220, 119, 0.56)"></div>
          
          <?php $colspan = ($form_type == 'billing_entry') ? 8 : 12; ?>
          <div class="col-md-<?php echo $colspan?> no-padding">
            <div class="tabbable">  

              <div class="tab-content">                  
                
                <div id="tabs_form_pelayanan">

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
          <?php if($form_type == 'billing_entry') : ?>
          <div class="col-md-4 no-padding" style="padding-left: 5px !important">
            <div id="section_rekam_medis"></div>
          </div>
          <?php endif;?>

        </div>  
        
        <?php if($form_type != 'billing_entry') : ?>
        <div class="col-md-3 no-padding" style="padding-left: 5px !important">
          <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#antrian_tabs">
                        <i class="green ace-icon fa fa-user bigger-120"></i>
                        Antrian Pasien
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#rm_tabs" onclick="get_riwayat_medis('<?php echo $no_mr?>')">
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
                  <span class="pull-left" style="padding-top: 10px"><b>Cari pasien :</b></span> <br>
                  <input type="text" id="seacrh_ul_li" value="" placeholder="Masukan keyword..." class="form-control" onkeyup='searchTable()'>
                </div>
                <br>
                <div class="center">
                    <label for="" class="label label-danger" style="background-color: #f998878c; color: black !important"> BPJS Kesehatan</label>
                    <label for="" class="label label-info" style="background-color: #6fb3e0; color: black !important"> Umum & Asuransi</label>
                </div>
                <div class="comments ace-scroll"  >
                  <!-- <div id="list_antrian_existing"></div> -->
                  <table id="list_antrian_existing" class="table">
                    <tbody></tbody>
                  </table>
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
        <?php endif;?>

  </form>

<div id="GlobalModal" class="modal fade" tabindex="-1">

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

        <div id="form_modal"></div>

      </div>

      <div class="modal-footer no-margin-top">

        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

          <i class="ace-icon fa fa-times"></i>

          Close

        </button>

      </div> 

    </div>

  </div>

</div>

<!-- ace scripts -->
<script src="<?php echo base_url()?>assets/js/ace/ace.settings.js"></script>


