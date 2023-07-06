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

    $('#form_default_pelayanan').load('pelayanan/Pl_pelayanan_ruang_pemeriksaan/form_input_hasil?mr=<?php echo $no_mr?>&id='+$('#id_tc_pesanan').val()+'&no_kunjungan='+$('#no_kunjungan').val()+'&bag=<?php echo $kode_bagian; ?>'); 

    // get data antrian pasien
    setInterval("getDataAntrianPasien();",30000); 

    /*focus on form input pasien*/
    $('#form_cari_pasien').focus();    

    $('#form_pelayanan').on('submit', function(){
               
        // $('#konten1').val($('#editor_konten_1').html());
        // $('#konten2').val($('#editor_konten_2').html());
        // $('#konten3').val($('#editor_konten_3').html());
        // $('#konten4').val($('#editor_konten_4').html());
        // $('#konten5').val($('#editor_konten_5').html());
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
              if( $('#form_pelayanan').attr('action')=='pelayanan/Pl_pelayanan_ruang_pemeriksaan/processPelayananSelesai' ){
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

                if(jsonResponse.type_pelayanan == 'Penunjang Medis' ){

                  getMenuTabs('registration/reg_pasien/riwayat_kunjungan/'+jsonResponse.no_mr+'/'+$('#kode_bagian_val').val()+'', 'tabs_riwayat_kunjungan');

                }

                if( jsonResponse.type_pelayanan == 'Pasien Selesai' ){
                  // back after process
                  if( jsonResponse.next_id_tc_pesanan != '' ){
                    getMenu('pelayanan/Pl_pelayanan_ruang_pemeriksaan/form/'+jsonResponse.next_id_tc_pesanan+'/'+jsonResponse.next_no_kunjungan+'?no_mr='+jsonResponse.next_pasien+'');
                  }else{
                    getMenu('pelayanan/Pl_pelayanan');
                  }

                }

                if( jsonResponse.type_pelayanan == 'Expertise' ){
                  // back after process
                  $('#kode_expertise').val(jsonResponse.ID);

                }

                
              }else{          

                $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
                //focus tabs diagnosa
                getMenuTabs('pelayanan/Pl_pelayanan_ruang_pemeriksaan/diagnosa/<?php echo $id?>/<?php echo $kunjungan->no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($kode_bagian)?$kode_bagian:''?>', 'tabs_form_pelayanan'); 

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
            url: "pelayanan/Pl_pelayanan_ruang_pemeriksaan/destroy_session_kode_bagian",
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
    $('#no_mr_selected').change(function (e) {  
      e.preventDefault();  
      var element = $(this).find('option:selected'); 
      var params_id = element.attr("data-id");
      getMenu('pelayanan/Pl_pelayanan_ruang_pemeriksaan/form/'+params_id+'?no_mr='+$(this).val()+'');
    });

})

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

    $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien') ?>?keyword=" + keyword, '', function (data) {      
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
  $.getJSON("pelayanan/Pl_pelayanan_ruang_pemeriksaan/get_data_antrian_pasien?bag=" + $('#kode_bagian_val').val(), '', function (data) {   
        $('#no_mr_selected option').remove();         
        $('#antrian_pasien_tbl_done tbody').remove();         
        $('<option value="">-Pilih Pasien-</option>').appendTo($('#no_mr_selected'));  
        var arr = [];
        var arr_cancel = [];
        var no = 0;
        $.each(data, function (i, o) { 
          no++; 
            var selected = (o.no_mr==$('#noMrHidden').val())?'selected':'';
            var penjamin = (o.kode_perusahaan==120)? '('+o.nama_perusahaan+')' : '' ;
            var style = ( o.status_batal == 1 ) ? 'style="background-color: red; color: white"' : (o.tgl_keluar_poli == null) ? (o.kode_perusahaan == 120) ? '' : 'style="background-color: #6fb3e0; color: black"' : 'style="background-color: #f998878c; color: black"';

            $('<tr><td align="center" style="font-weight: bold; font-size: 14px">'+no+' </td><td><span value="'+o.no_mr+'" data-id="'+o.id_tc_pesanan+'/'+o.referensi_no_kunjungan+'"><a href="#" onclick="click_selected_patient('+o.id_tc_pesanan+','+o.referensi_no_kunjungan+','+"'"+o.no_mr+"'"+', '+no+')">'+o.no_mr+' - ' + o.nama + '</a><br><span style="font-size: 9px">'+penjamin+'</span></span></td></tr>').appendTo($('#antrian_pasien_tbl_done')); 


            $('<option value="'+o.no_mr+'" data-id="'+o.id_tc_pesanan+'/'+o.referensi_no_kunjungan+'" '+selected+' '+style+'>'+no+'. '+o.no_mr+' - ' + o.nama + ' '+penjamin+' </option>').appendTo($('#no_mr_selected'));  
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

function click_selected_patient(id_tc_pesanan, no_kunjungan, no_mr, no){
  preventDefault();  
  $('#no_antrian_tbl').text(no);
  getMenu('pelayanan/Pl_pelayanan_ruang_pemeriksaan/form/'+id_tc_pesanan+'/'+no_kunjungan+'?no_mr='+no_mr+'&bag='+$('#kode_bagian_val').val()+'');
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
            <!-- #section:basics/navbar.user_menu -->
            

            <!-- /section:basics/navbar.user_menu -->
      </ul>
  </div>  
<div>   

<form class="form-horizontal" method="post" id="form_pelayanan" action="pelayanan/Pl_pelayanan_ruang_pemeriksaan/process" enctype="multipart/form-data" autocomplete="off" >      
  
    <!-- hidden form -->
    <input type="hidden" name="noMrHidden" id="noMrHidden">
    <input type="hidden" name="id_tc_pesanan" id="id_tc_pesanan" value="<?php echo ($id)?$id:''?>">
    <input type="hidden" name="nama_pasien_hidden" id="nama_pasien_hidden">
    <input type="hidden" name="no_kunjungan" class="form-control" value="<?php echo isset($kunjungan->no_kunjungan)?$kunjungan->no_kunjungan:''?>" id="no_kunjungan" readonly>
    <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>  
    <!-- hidden form -->
    <input type="hidden" class="form-control" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
    <input type="hidden" class="form-control" name="kode_bagian" value="<?php echo isset($kode_bagian)?$kode_bagian:''?>" id="kode_bagian_val">


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

        <!-- <p><b><i class="fa fa-edit"></i> DATA REGISTRASI DAN KUNJUNGAN </b></p> -->
        <table class="table table-bordered">
          <tr style="background-color:#f4ae11">
            <td rowspan="2" width="100px" class="center" style="background-color: darkturquoise;">
            <span style="font-size: 11px">No. Antrian </span> <br> <span style="font-size: 30px; font-weight: bold" id="no_antrian_tbl"></span>
            </td>
            <th>Kode</th>
            <th>No Reg</th>
            <th>Tanggal Daftar</th>
            <th>Dokter</th>
            <th>Penjamin</th>
          </tr>

          <tr>
            <td><?php echo $value->nama;?></td>
            <td><?php echo $kunjungan->no_registrasi?></td>
            <td><?php echo $kunjungan->tgl_masuk?></td>
            <td><?php echo $nama_dokter?></td>
          </tr>

        </table>

        <!-- <p><b><i class="fa fa-edit"></i> FORM PELAYANAN PASIEN </b></p> -->

        <!-- form default pelayanan pasien -->
        <div id="form_default_pelayanan"></div>
        
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
                  <span style="font-weight: bold"><i class="fa fa-check-square-o green"></i> Daftar Antrian Pasien</span>
                  <table class="table" id="antrian_pasien_tbl_done" style="background: linear-gradient(77deg, #fee951cc, transparent)">
                      <tbody>
                          <tr><td><span style="font-weight: bold; color: red; font-style: italic">Silahkan tunggu...</span></td></tr>
                      </tbody>
                  </table>

              </div>

              <div id="rm_tabs" class="tab-pane fade">
                  <div id="cppt_data_on_tabs"></div>
              </div>
          </div>
        </div>
      </div>

</form>


<!-- ace scripts -->
<script src="<?php echo base_url()?>assets/js/ace/ace.settings.js"></script>

