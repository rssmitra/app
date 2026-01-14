<!-- <script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script> -->

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
    find_pasien_by_mr('<?php echo $no_mr?>');

    getMenuTabsHtml("templates/References/get_riwayat_medis/<?php echo $no_mr?>", 'tabs_riwayat_medis_pasien');
    getMenuTabsHtml("templates/References/get_riwayat_pm/<?php echo $no_mr?>", 'tabs_riwayat_pm_pasien');

    /*focus on form input pasien*/
    $('#form_cari_pasien').focus();    

    /*submit form*/
    $('#form_pelayanan').ajaxForm({      

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

        if(jsonResponse.status === 200){          

          $.achtung({message: jsonResponse.message, timeout:5});     

          // $('#table-pesan-resep').DataTable().ajax.reload(null, false);

          $('#jumlah_r').val('')

          $("#modalEditPesan").modal('hide');  

          if(jsonResponse.type_pelayanan == 'Penunjang Medis' )
          {

            getMenuTabs('registration/reg_pasien/riwayat_kunjungan/'+jsonResponse.no_mr+'/'+$('#kode_bagian_val').val()+'', 'tabs_riwayat_kunjungan');

          }

          if(jsonResponse.type_pelayanan == 'pasien_selesai' )
          {

            $('#btn_cetak_hasil').show('fast');
            $('#btn_selesai_mcu').hide('fast');
            $('#btn_batal_mcu').hide('fast');
            $("html, body").animate({ scrollTop: "0" });
            
          }
          
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }        

        achtungHideLoader();        

      }      

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
     
    $('#btn_search_pasien').click(function (e) {      

      e.preventDefault();  

      if( $("#form_cari_pasien").val() == "" ){

        alert('Masukan keyword minimal 3 Karakter !');

        return $("#form_cari_pasien").focus();

      }else{

        achtungShowLoader();

        find_pasien_by_mr( $("#form_cari_pasien").val() );

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
          find_pasien_by_mr( jsonResponse.no_mr );


        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }        

        //achtungHideLoader();        

      }      

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
// function find_pasien_by_keyword(keyword){  

//     $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien') ?>?keyword=" + keyword, '', function (data) {      
//           achtungHideLoader();          

//           /*if cannot find data show alert*/
//           if( data.count == 0){

//             $('#div_load_after_selected_pasien').hide('fast');

//             $('#div_riwayat_pasien').hide('fast');
            
//             $('#div_penangguhan_pasien').hide('fast');

//             /*reset all field data*/
//             $('#no_mr').text('-');$('#noMrHidden').val('');$('#no_ktp').text('-');$('#nama_pasien').text('-');$('#jk').text('-');$('#umur').text('-');$('#alamat').text('-');$('#noKartuBpjs').val('-');$('#kode_perusahaan').text('-');$('#total_kunjungan').text('-');

//             alert('Data tidak ditemukan'); return $("#form_cari_pasien").focus();

//           }

//           /*if count data = 1*/
//           if( data.count == 1 )     {

//             var obj = data.result[0];

//             var pending_data_pasien = data.pending; 
//             var umur_pasien = hitung_usia(obj.tgl_lhr);
//             console.log(pending_data_pasien);
//             console.log(hitung_usia(obj.tgl_lhr));

//             $('#no_mr').text(obj.no_mr);
//             $('#noMrHidden').val(obj.no_mr);
//             $('#no_ktp').text(obj.no_ktp);
//             $('#nama_pasien').text(obj.nama_pasien);
//             $('#nama_pasien_hidden').val(obj.nama_pasien);
//             $('#jk').text(obj.jen_kelamin);
//             $('#umur').text(umur_pasien);          
//             $('#umur_saat_pelayanan_hidden').val(umur_pasien);
//             $('#alamat').text(obj.almt_ttp_pasien);
//             $('#noKartuBpjs').val(obj.no_kartu_bpjs);

//             penjamin = (obj.nama_perusahaan==null)?'-':obj.nama_perusahaan;
//             kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;
//             $('#kode_perusahaan').text(kelompok+' '+obj.nama_perusahaan);



//             if( obj.url_foto_pasien ){

//               $('#avatar').attr('src', '<?php echo base_url()?>uploaded/images/photo/'+obj.url_foto_pasien+'');

//             }else{

//               if( obj.jen_kelamin == 'L' ){

//                 $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');

//               }else{

//                 $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');

//               }

//             }

//             /*default show tabs*/
//             $("#tabs_form_pelayanan").load('pelayanan/Pl_pelayanan_mcu/anamnesa/<?php echo $value->kode_gcu?>/<?php echo isset($id_tc_pemeriksaan_fisik_mcu)?$id_tc_pemeriksaan_fisik_mcu:0?>');

//           }            

//     }); 

// }

function find_pasien_by_mr(keyword){  

$.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien_by_mr') ?>?keyword=" + keyword, '', function (data) {      
      achtungHideLoader();          

      /*if cannot find data show alert*/
      if( data.count == 0){

        $('#div_load_after_selected_pasien').hide('fast');

        $('#div_riwayat_pasien').hide('fast');
        
        $('#div_penangguhan_pasien').hide('fast');

        /*reset all field data*/
        $('#no_mr').text('-');$('#noMrHidden').val('');$('#no_ktp').text('-');$('#nama_pasien').text('-');$('#jk').text('-');$('#umur').text('-');$('#alamat').text('-');$('#noKartuBpjs').val('-');$('#kode_perusahaan').text('-');$('#total_kunjungan').text('-');

        alert('Data tidak ditemukan'); return $("#form_cari_pasien").focus();

      }

      /*if count data = 1*/
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
        $('#umur').text(umur_pasien);
        $('#tgl_lhr').text(getFormattedDate(obj.tgl_lhr));              
        $('#umur_saat_pelayanan_hidden').val(umur_pasien);
        $('#alamat').text(obj.almt_ttp_pasien);
        $('#hp').text(obj.no_hp);
        $('#no_telp').text(obj.tlp_almt_ttp);
        $('#catatan_pasien').text(obj.keterangan);
        $('#ttd_pasien').attr('src', obj.ttd);
        $('#noKartuBpjs').val(obj.no_kartu_bpjs);

        penjamin = (obj.nama_perusahaan==null)?'-':obj.nama_perusahaan;
        kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;
        $('#kode_perusahaan').text(kelompok+' '+obj.nama_perusahaan);



        if( obj.url_foto_pasien ){

          $('#avatar').attr('src', '<?php echo base_url()?>uploaded/images/photo/'+obj.url_foto_pasien+'');

        }else{

          if( obj.jen_kelamin == 'L' ){

            $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');

          }else{

            $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');

          }

        }

        /*default show tabs*/
        $("#tabs_form_pelayanan").load('pelayanan/Pl_pelayanan_mcu/anamnesa/<?php echo $value->kode_gcu?>/<?php echo isset($id_tc_pemeriksaan_fisik_mcu)?$id_tc_pemeriksaan_fisik_mcu:0?>');

      }            

}); 

}


function selesaikanKunjungan(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_mcu/processPelayananSelesai');
  $('#form_default_pelayanan').show('fast');
  $('#form_default_pelayanan').load('pelayanan/Pl_pelayanan_mcu/form_end_visit?mr='+noMr+'&id='+$('#id_pl_tc_poli').val()+'&no_kunjungan='+$('#no_kunjungan').val()+'&no_registrasi='+$('#no_registrasi').val()+''); 

}

function backToDefaultForm(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_mcu/processPelayananSelesai');
  $('#form_default_pelayanan').hide('fast');
  $('#form_default_pelayanan').html(''); 
  
}

function cancel_visit(no_registrasi, no_kunjungan){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan_mcu/cancel_visit",
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
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

}

function cetak_hasil(kode_mcu,id_pl_tc_poli) {
  
  url = 'pelayanan/Pl_pelayanan_mcu/cetak_hasil?kode_mcu='+kode_mcu+'&id_pl_tc_poli='+id_pl_tc_poli;
  title = 'Hasil MCU';
  width = 950;
  height = 650;
  PopupCenter(url, title, width, height); 

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


</script>

<style type="text/css">
  .pagination{
    margin: 0px 0px !important;
  }
  .well{
    padding: 5px !important;
  }
</style>
<div class="row">

    <div class="page-header">    

      <h1>      

        <?php echo $title?>        

        <small>        

          <i class="ace-icon fa fa-angle-double-right"></i>          

          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>          

        </small>        

      </h1>      

    </div>  


    <div style="margin-top:-10px">   

      <form class="form-horizontal" method="post" id="form_pelayanan" action="#" enctype="multipart/form-data" autocomplete="off" >      
        
          <br>

          <!-- hidden form -->
          <input type="hidden" value="" name="noMrHidden" id="noMrHidden">
          <input type="hidden" name="id_pl_tc_poli" id="id_pl_tc_poli" value="<?php echo ($id)?$id:''?>">
          <input type="hidden" name="kode_gcu" id="kode_gcu" value="<?php echo ($value->kode_gcu)?$value->kode_gcu:''?>">
          <input type="hidden" name="nama_pasien_hidden" value="" id="nama_pasien_hidden">
          <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dokter_pemeriksa">
          <input type="hidden" name="no_registrasi" class="form-control" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" id="no_registrasi" readonly>
          <input type="hidden" name="no_kunjungan" class="form-control" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan" readonly>
          <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>
          
          <div class="col-md-2">
              <div class="box box-primary" id='box_identity'>
                  <img id="avatar" class="profile-user-img img-responsive center" src="<?php echo base_url().'assets/img/avatar.png'?>" alt="User profile picture" style="width:100%">

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

                  <a href="#" id="btn_search_pasien" class="btn btn-inverse btn-block">Tampilkan Pasien</a>
                  
                <!-- /.box-body -->
              </div>
            </div>
          <!-- form pelayanan -->
          <div class="col-md-10 no-padding">

          <!-- end action form  -->
            <div class="pull-right" style="margin-bottom:1%">
              <?php if(empty($value->tgl_keluar_poli)) :?>
              <a href="#" class="btn btn-xs btn-primary" id="btn_selesai_mcu" onclick="selesaikanKunjungan()"><i class="fa fa-check-circle"></i> Selesaikan Kunjungan</a>
              <a href="#" class="btn btn-xs btn-danger" id="btn_batal_mcu" onclick="cancel_visit(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>,<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>)"><i class="fa fa-times-circle"></i> Batalkan Kunjungan</a>
            <?php else: echo '<a href="#" class="btn btn-xs btn-success" onclick="getMenu('."'pelayanan/Pl_pelayanan_mcu'".')"><i class="fa fa-angle-double-left"></i> Kembali ke Daftar Pasien</a>'; endif;?>
              <a href="#" class="btn btn-xs btn-warning" id="btn_cetak_hasil" onclick="cetak_hasil(<?php echo isset($value->kode_gcu)?$value->kode_gcu:''?>,<?php echo isset($value->id_pl_tc_poli)?$value->id_pl_tc_poli:''?>)" <?php echo isset($hasil_kesimpulan)?'':'style="display:none"' ?> ><i class="fa fa-file"></i> Cetak Hasil</a>
              <a href="#" name="submit" class="btn btn-xs btn-info" onclick="showModalEditPasien()" >

                <i class="fa fa-user"></i> Ubah Data Pasien

              </a>
            </div>
            <br>
          <!-- <p><b><i class="fa fa-edit"></i> DATA REGISTRASI DAN KUNJUNGAN </b></p> -->
            <table class="table table-bordered">
              <tr style="background-color:#f4ae11">
                <th>Kode Kunjungan</th>
                <th>No Reg</th>
                <th>Tanggal Daftar</th>
                <th>Dokter</th>
                <th>Penjamin</th>
                <th>Paket</th>
                <th>Petugas</th>
              </tr>

              <tr>
                <td><?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?></td>
                <td><?php echo isset($value->no_registrasi)?$value->no_registrasi:''?></td>
                <td><?php echo isset($value->tgl_jam_poli)?$this->tanggal->formatDateTime($value->tgl_jam_poli):''?></td>
                <td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
                <td><?php echo isset($value->nama_kelompok)?ucwords($value->nama_kelompok):'';?>
                <?php echo isset($value->nama_perusahaan)?' / '.$value->nama_perusahaan:'';?></td>
                <td><?php echo isset($value->nama_tarif)?ucwords($value->nama_tarif):'';?>

                <td><?php echo $this->session->userdata('user')->fullname?></td>
              </tr>

            </table>

            <?php if(isset($value) AND $value->status_batal==1) :?>
            <span style="margin-left:-19%;position:absolute;transform: rotate(-25deg) !important; margin-top: 21%" class="stamp is-nope-2">Batal Berobat</span>
            <?php endif;?>

            <?php if(isset($value) AND $value->status_periksa!=NULL) :?>
            <span style="margin-left:-19%;position:absolute;transform: rotate(-25deg) !important; margin-top: 21%" class="stamp is-approved">Selesai</span>
            <?php endif;?>            

            <div id="form_default_pelayanan" style="background-color:#77dcd373"></div>

            <!-- hidden form -->
            <input type="hidden" class="form-control" name="no_kunjungan" value="<?php echo isset($value)?$value->no_kunjungan:''?>">
            <input type="hidden" class="form-control" name="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
            <input type="hidden" class="form-control" name="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
            <input type="hidden" class="form-control" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>" id="kode_perusahaan_val">
            <input type="hidden" class="form-control" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
            <input type="hidden" class="form-control" name="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien:''?>">
            <input type="hidden" class="form-control" name="kode_bagian_asal" value="<?php echo isset($value)?$value->kode_bagian_asal:''?>">
            <input type="hidden" class="form-control" name="kode_bagian" value="<?php echo isset($value)?$value->kode_bagian:''?>" id="kode_bagian_val">
            <input type="hidden" class="form-control" name="kode_klas" value="<?php echo isset($kode_klas)?$kode_klas:''?>" id="kode_klas_val">
            <input type="hidden" class="form-control" name="kode_dokter_poli" id="kode_dokter_poli" value="<?php echo isset($value->kode_dokter)?$value->kode_dokter:''?>">
            <input type="hidden" value="<?php echo isset($id_tc_pemeriksaan_fisik_mcu)?$id_tc_pemeriksaan_fisik_mcu:0 ?>" name="id_tc_pemeriksaan_fisik_mcu" id="id_tc_pemeriksaan_fisik_mcu">
            <input type="hidden" class="form-control" name="kode_tarif" id="kode_tarif" value="<?php echo isset($value->kode_tarif)?$value->kode_tarif:''?>">
            <input type="hidden" class="form-control" name="kode_trans_pelayanan" id="kode_trans_pelayanan" value="<?php echo isset($value->kode_trans_pelayanan)?$value->kode_trans_pelayanan:''?>">


            <!-- form default pelayanan pasien -->

          
            <p><b><i class="fa fa-edit"></i> FORM PELAYANAN PASIEN </b></p>
            
            <div class="col-md-8">
              <div class="tabbable">  

                <ul class="nav nav-tabs" id="myTab">

                  <li class="active">
                    <a data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo isset($id_tc_pemeriksaan_fisik_mcu)?$id_tc_pemeriksaan_fisik_mcu:0?>" data-url="pelayanan/Pl_pelayanan_mcu/anamnesa/<?php echo $value->kode_gcu?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      <i class="green ace-icon fa fa-history bigger-120"></i>
                      ANAMNESA
                    </a>
                  </li>

                  <li>
                    <a data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo isset($id_tc_pemeriksaan_fisik_mcu)?$id_tc_pemeriksaan_fisik_mcu:0?>" data-url="pelayanan/Pl_pelayanan_mcu/pemeriksaan_fisik/<?php echo $value->kode_gcu?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      <i class="red ace-icon fa fa-list bigger-120"></i>
                      PEMERIKSAAN FISIK
                    </a>
                  </li>

                  <li>
                    <a data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo isset($id_tc_pemeriksaan_fisik_mcu)?$id_tc_pemeriksaan_fisik_mcu:0?>" data-url="pelayanan/Pl_pelayanan_mcu/radiologi/<?php echo $value->kode_gcu?>/<?php echo $value->no_registrasi?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      <i class="red ace-icon fa fa-file bigger-120"></i>
                      RADIOLOGI & EKG
                    </a>
                  </li>

                  <li>
                    <a data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo isset($id_tc_pemeriksaan_fisik_mcu)?$id_tc_pemeriksaan_fisik_mcu:0?>" data-url="pelayanan/Pl_pelayanan_mcu/kesimpulan_saran/<?php echo $value->kode_gcu?>/<?php echo $value->no_registrasi?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      <i class="orange ace-icon fa fa-globe bigger-120"></i>
                      KESIMPULAN DAN SARAN
                    </a>
                  </li>

                  <!-- <li>
                    <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/reg_pasien/riwayat_transaksi/<?php echo $value->no_mr?>" id="tabs_billing_pasien" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
                      <i class="purple ace-icon fa fa-money bigger-120"></i>
                      BILLING PASIEN
                    </a>
                  </li> -->

                </ul>

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
            <div class="col-md-4">
            <div class="tabbable">
              <ul class="nav nav-tabs" id="TabsMenu">
                <li class="active">
                  <a data-toggle="tab" href="#tabs_rm">
                    <?php echo TABS_RESUME_MEDIS?>
                  </a>
                </li>

                <li>
                  <a data-toggle="tab" href="#tabs_pm">
                  <?php echo TABS_HASIL_PENUNJANG?>
                  </a>
                </li>
                
              </ul>

              <div class="tab-content">
                <div id="tabs_rm" class="tab-pane fade in active">
                  <div id="tabs_riwayat_medis_pasien"></div>
                </div>

                <div id="tabs_pm" class="tab-pane fade">
                  <div id="tabs_riwayat_pm_pasien"></div>
                </div>
              </div>
            </div>
            </div>

          </div>

        </form>
    </div>

</div><!-- /.row -->

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

<!-- MODAL DAFTAR PERJANJIAN -->




