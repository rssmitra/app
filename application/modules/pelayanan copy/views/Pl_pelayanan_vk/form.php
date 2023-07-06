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
    getMenuTabs('pelayanan/Pl_pelayanan_vk/tindakan/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Rajal&kode_bag=030501', 'tabs_form_pelayanan');

    /*focus on form input pasien*/
    $('#form_cari_pasien').focus();    

    /*submit form*/
    $('#form_pelayanan').ajaxForm({      

      beforeSend: function() {        

          if( $('#form_pelayanan').attr('action')=='pelayanan/Pl_pelayanan_vk/processPelayananSelesai' ){
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

          $('#table-pesan-resep').DataTable().ajax.reload(null, false);

          $('#jumlah_r').val('')

          $("#modalEditPesan").modal('hide');  

          if(jsonResponse.type_pelayanan == 'Penunjang Medis' )
          {

            getMenuTabs('registration/reg_pasien/riwayat_kunjungan/'+jsonResponse.no_mr+'/'+$('#kode_bagian_val').val()+'', 'tabs_riwayat_kunjungan');
          
          }

          if(jsonResponse.type_pelayanan == 'Pasien Selesai' )
          {

            getMenu('pelayanan/Pl_pelayanan_vk');

          }

          if(jsonResponse.type_pelayanan == 'save_diagnosa' )
          {

            getMenuTabs('pelayanan/Pl_pelayanan_vk/diagnosa/'+$('#id_pasien_vk').val()+'/'+$('#no_kunjungan').val()+'?type=Rajal&kode_bag=030501', 'tabs_form_pelayanan');

          }

          
        }else{          

          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});    

          if(jsonResponse.err=='antrian_pm'){
            $('#form_default_pelayanan').hide('fast');
            $('#form_default_pelayanan').html(''); 
          }

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

        find_pasien_by_keyword( $("#form_cari_pasien").val() );

      }    

    });   

    /*onchange form module when click tabs*/
    $('#tabs_tindakan').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_vk/process');
      $('#form_default_pelayanan').hide('fast');
      $('#form_default_pelayanan').html(''); 

    });

    $('#tabs_diagnosa').click(function (e) {    
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_vk/processSaveDiagnosa');
      // backToDefaultForm();
    });

    $('#tabs_pesan_resep').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'farmasi/Farmasi_pesan_resep/process');
      $('#form_default_pelayanan').hide('fast');
      $('#form_default_pelayanan').html(''); 

    });   

    $('#tabs_penunjang_medis').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'registration/Reg_pm/process');
      $('#form_default_pelayanan').hide('fast');
      $('#form_default_pelayanan').html(''); 

    });

    $('#tabs_bayi').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_vk/process_data_bayi');
      $('#form_default_pelayanan').hide('fast');
      $('#form_default_pelayanan').html(''); 

    });
    /*onchange form module when click tabs*/   

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

            /*for tabs riwayat*/
            // $('#tabs_riwayat_kunjungan_id').attr('data-id', obj.no_mr);
            // $('#tabs_riwayat_transaksi_id').attr('data-id', obj.no_mr);
            // $('#tabs_riwayat_perjanjian_id').attr('data-id', obj.no_mr);
            // $('#tabs_riwayat_booking_online_id').attr('data-id', obj.no_mr);

            $("#myTab li").removeClass("active");
            // $("#tabs_detail_pasien").html("<div class='alert alert-block alert-success center'><p><strong><i class='ace-icon fa fa-glass bigger-150'></i><br>Selamat Datang!</strong><br>Untuk melihat Riwayat Kunjungan Pasien dan Transaksi Pasien, Silahkan cari pasien terlebih dahulu !</p></div>");

            // if(data.count_pending > 0){

            //   /*show pending data pasien*/
              
            //   // $('#div_penangguhan_pasien').show('fast');

            //   $('#div_load_after_selected_pasien').hide('fast');

            //   $('#div_riwayat_pasien').show('fast');

            //   $('#result_penangguhan_pasien tbody').remove();

            //   $.each(pending_data_pasien, function (x, y) {                  

            //       dt = new Date(y.tgl_masuk);
                  
            //       formatDt = formatDate(dt);
                  
            //       if(y.total_ditangguhkan > 0){
            //         status = 'Total Ditangguhkan '+y.total_ditangguhkan+'';
            //       }else{
            //         status = '<label class="label label-danger">Belum dipulangkan</label>';
            //       }
            //       $('<tr><td>'+y.no_kunjungan+'</td><td>'+y.no_registrasi+'</td><td>'+formatDt+'<td>'+y.poli+'</td><td>'+y.dokter+'</td><td>'+y.penjamin+'</td><td>'+status+'</td></tr>').appendTo($('#result_penangguhan_pasien'));                    

            //   }); 


            // }else{

            //   $('#div_penangguhan_pasien').hide('fast');

            //   $('#result_penangguhan_pasien tbody').remove();

            //   /*show detail form */

            //   $('#div_load_after_selected_pasien').show('fast');

            //   $('#div_riwayat_pasien').show('fast');

            // }

          }             

    }); 

}

// function selesaikanKunjungan(){

//   noMr = $('#noMrHidden').val();
//   preventDefault();  
//   $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_vk/processPelayananSelesai');
//   $('#form_default_pelayanan').show('fast');
//   $('#form_default_pelayanan').load('pelayanan/Pl_pelayanan/form_end_visit?mr='+noMr+'&id='+$('#id_pasien_vk').val()+'&no_kunjungan='+$('#no_kunjungan').val()+''); 

// }

function selesaikanKunjungan(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  getMenuTabs('pelayanan/Pl_pelayanan_vk/diagnosa/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Rajal&kode_bag=030501', 'tabs_form_pelayanan');
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_vk/processPelayananSelesai?bag='+$('#kode_bagian_val').val()+'');
  $('#form_default_pelayanan').show('fast');
  $('#form_default_pelayanan').load('pelayanan/Pl_pelayanan/form_end_visit?mr='+noMr+'&id='+$('#id_pasien_vk').val()+'&no_kunjungan='+$('#no_kunjungan').val()+'');


}

function backToDefaultForm(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_vk/processPelayananSelesai');
  $('#form_default_pelayanan').hide('fast');
  $('#form_default_pelayanan').html(''); 
  
}

function cetak_surat_kematian(no_registrasi) {
  
  kode_meninggal = $('#kode_meninggal').val();
  url = 'pelayanan/Pl_pelayanan_vk/surat_kematian?kode_meninggal='+kode_meninggal+'&no_kunjungan='+<?php echo $no_kunjungan?>+'&no_registrasi='+no_registrasi+'&umur='+$('#umur_saat_pelayanan_hidden').val();
  title = 'Cetak Surat Kematian';
  width = 850;
  height = 500;
  PopupCenter(url, title, width, height); 

}

function cetak_surat_keracunan() {
  
  noMr = $('#noMrHidden').val();
  url = 'pelayanan/Pl_pelayanan_vk/surat_keracunan?no_kunjungan='+<?php echo $no_kunjungan?>+'&no_mr='+noMr;
  title = 'Cetak Surat Keracunan';
  width = 1200;
  height = 1200;
  PopupCenter(url, title, width, height); 

}

function rollback(no_registrasi, no_kunjungan){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan_vk/rollback",
      data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#kode_bagian_val').val() },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          reload_page();
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

}

function cancel_visit(no_registrasi, no_kunjungan){

  preventDefault();  

  achtungShowLoader();

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
          reload_page();
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

}

function reload_page(){
  getMenu('pelayanan/Pl_pelayanan_vk/form/'+$('#id_pasien_vk').val()+'/'+$('#no_kunjungan').val()+'')
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

    <!-- div.dataTables_borderWrap -->

    <div style="margin-top:-10px">   

      <form class="form-horizontal" method="post" id="form_pelayanan" action="#" enctype="multipart/form-data" autocomplete="off" >      
        
          <br>

          <!-- hidden form -->
          <input type="hidden" value="" name="noMrHidden" id="noMrHidden">
          <input type="hidden" name="id_pasien_vk" id="id_pasien_vk" value="<?php echo ($id)?$id:''?>">
          <input type="hidden" name="nama_pasien_hidden" value="" id="nama_pasien_hidden">
          <input type="hidden" name="kode_dokter_vk" value="<?php echo isset($value->dr_merawat)?$value->dr_merawat:'';?>" id="kode_dokter_vk">
          <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dokter_pemeriksa">
          <input type="hidden" name="no_registrasi" class="form-control" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" readonly>
          <input type="hidden" name="no_kunjungan" class="form-control" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan" readonly>
          <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>
          
          <!-- profile Pasien -->
          <div class="col-md-2 no-padding" >
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
          <div class="col-md-10">
            <div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse ace-save-state">
                <div class="center">
                  <ul class="nav nav-list">

                    <li class="hover">
                      <a href="#" id="tabs_diagnosa" href="#" data-id="<?php echo $no_kunjungan?>?type=RI&kode_bag=030501" data-url="pelayanan/Pl_pelayanan_vk/diagnosa/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-user"></i><span class="menu-text"> DIAGNOSA </span></a><b class="arrow"></b>
                    </li>

                    <li class="hover">
                      <a id="tabs_tindakan" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=030501" data-url="pelayanan/Pl_pelayanan_vk/tindakan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-stethoscope"></i><span class="menu-text"> TINDAKAN </span></a><b class="arrow"></b>
                    </li>

                    <li class="hover">
                      <a id="tabs_bayi" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=030501&no_mr_ibu=<?php echo $no_mr?>" data-url="pelayanan/Pl_pelayanan_vk/form_bayi/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-edit"></i><span class="menu-text"> DATA KELAHIRAN </span></a><b class="arrow"></b>
                    </li>

                    <li class="hover">
                      <a data-id="<?php echo $id?>" data-url="farmasi/Farmasi_pesan_resep/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>" id="tabs_pesan_resep" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
                      <i class="menu-icon fa fa-leaf"></i><span class="menu-text"> FARMASI </span></a><b class="arrow"></b>
                    </li>

                    <li class="hover">
                      <a data-id="<?php echo $id?>" data-url="registration/Reg_pm/rujuk_pm/<?php echo $value->no_registrasi?>/030501/<?php echo $kode_klas?>/rajal" id="tabs_penunjang_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')" ><i class="menu-icon fa fa-flask"></i><span class="menu-text"> PENUNJANG </span></a><b class="arrow"></b>
                    </li>

                    <li class="hover">
                      <a data-id="<?php echo $value->kode_ri?>" data-url="pelayanan/Pl_pelayanan_ri/pesan/<?php echo $value->kode_ri?>/<?php echo $value->no_registrasi?>" id="tabs_pesan" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" ><i class="menu-icon fa fa-flask"></i><span class="menu-text"> PESAN OK </span></a><b class="arrow"></b>
                    </li>


                    <li class="hover">
                      <a href="#" data-id="<?php echo $id?>" data-url="billing/Billing/getDetail/<?php echo $value->no_registrasi?>/RJ" id="tabs_billing_pasien" href="#" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-money"></i><span class="menu-text"> BILLING </span></a><b class="arrow"></b>
                    </li>
                    
                    <!-- <li class="hover">
                      <a href="#" data-id="<?php echo $id?>" data-url="registration/reg_pasien/riwayat_transaksi/<?php echo $value->no_mr?>" id="tabs_riwayat_transaksi" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-file"></i><span class="menu-text"> TRANSAKSI </span></a><b class="arrow"></b>
                    </li> -->

                    <!-- <li class="hover">
                      <a href="#" data-id="<?php echo $id?>" data-url="rekam_medis/File_rm/index/<?php echo $value->no_mr?>" id="tabs_rekam_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-clipboard"></i><span class="menu-text"> E R M  </span></a><b class="arrow"></b>
                    </li> -->

                    <li class="hover">
                      <a href="#" data-id="<?php echo $id?>" data-url="templates/References/get_riwayat_medis/<?php echo $value->no_mr?>" id="tabs_rekam_medis" href="#" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-history"></i><span class="menu-text"> RIWAYAT MEDIS </span></a><b class="arrow"></b>
                    </li>
                    
                    <?php 
                      $trans_kasir = $this->Pl_pelayanan->cek_transaksi_kasir(isset($value->no_registrasi)?$value->no_registrasi:'',isset($value->no_kunjungan)?$value->no_kunjungan:'');
                      $flag_rollback = ($trans_kasir!=true)?'submited':'unsubmit';
                    ?>
                    <li class="hover">
                      <a href="#" data-id="<?php echo $id?>" data-url="registration/reg_pasien/riwayat_transaksi/<?php echo $value->no_mr?>" id="tabs_riwayat_transaksi" href="#" onclick="rollback(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>, <?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>, '<?php echo $flag_rollback?>')"><i class="menu-icon fa fa-undo"></i><span class="menu-text"> ROLLBACK  </span></a><b class="arrow"></b>
                    </li>

                  </ul><!-- /.nav-list -->
                </div>
            </div>

          <!-- end action form  -->
            <div class="pull-right" style="margin-bottom:1%">
              <?php if(empty($value->tgl_keluar_vk)) :?>
              <a href="#" class="btn btn-xs btn-primary" id="btn_selesai_igd" onclick="selesaikanKunjungan()"><i class="fa fa-check-circle"></i> Selesaikan Kunjungan</a>
              <a href="#" class="btn btn-xs btn-danger" onclick="cancel_visit(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>,<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>)"><i class="fa fa-times-circle"></i> Batalkan Kunjungan</a>
            <?php else: echo '<a href="#" class="btn btn-xs btn-success" onclick="getMenu('."'pelayanan/Pl_pelayanan_vk'".')"><i class="fa fa-angle-double-left"></i> Kembali ke Daftar Pasien</a>'; endif;?>
              <a href="#" class="btn btn-xs btn-danger" id="btn_cetak_meninggal" onclick="cetak_surat_kematian(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>)" <?php echo isset($meninggal)?'':'style="display:none"' ?> ><i class="fa fa-file"></i> Cetak Surat Kematian</a>
              <a href="#" class="btn btn-xs btn-danger" id="cetak_keracunan" onclick="cetak_surat_keracunan()" <?php echo isset($keracunan->id_cetak_racun)?'':'style="display:none"'?>><i class="fa fa-file"></i> Cetak Surat Keracunan </a>
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
                <th>Petugas</th>
                <th></th>
              </tr>

              <tr>
                <td><?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?></td>
                <td><?php echo isset($value->no_registrasi)?$value->no_registrasi:''?></td>
                <td><?php echo isset($value->tgl_masuk)?$this->tanggal->formatDateTime($value->tgl_masuk):''?></td>
                <td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
                <td>
                  <a href="#" onclick="show_modal('registration/reg_pasien/form_modal_edit_penjamin/<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>/<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>', 'Update Penjamin Pasien')">
                      <?php echo isset($value->nama_kelompok)?ucwords($value->nama_kelompok).' / ':'';?>
                      <?php echo isset($value->nama_perusahaan)?$value->nama_perusahaan:'';?>
                  </a>
                </td>
                <td><?php echo isset($value->fullname)?$value->fullname:''?></td>
                <td align="center"><a href="#" onclick="reload_page()"><i class="fa fa-refresh green bigger-120"></i></a></td>
              </tr>

            </table>
            
            <?php if(isset($value) AND $value->status_batal==1) :?>
          <span style="margin-left:-19%;position:absolute;transform: rotate(-25deg) !important; margin-top: 21%" class="stamp is-nope-2">Batal</span>
          <?php else:?>
            <?php if(isset($value) AND $value->tgl_keluar_vk!=NULL) :?>
            <span style="margin-left:-19%;position:absolute;transform: rotate(-25deg) !important; margin-top: 21%" class="stamp is-approved">Selesai</span>
            <?php endif;?>  
          <?php endif;?>

          

            <!-- hidden form -->
            <input type="hidden" class="form-control" name="no_kunjungan" value="<?php echo isset($value)?$value->no_kunjungan:''?>">
            <input type="hidden" class="form-control" name="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
            <input type="hidden" class="form-control" name="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
            <input type="hidden" class="form-control" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>" id="kode_perusahaan_val">
            <input type="hidden" class="form-control" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
            <input type="hidden" class="form-control" name="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien:''?>">
            <input type="hidden" class="form-control" name="umur_saat_pelayanan_hidden" id="umur_saat_pelayanan_hidden">
            <input type="hidden" class="form-control" name="kode_bagian_asal" value="<?php echo isset($value)?$value->bag_pas:''?>">
            <input type="hidden" class="form-control" name="kode_bagian" value="030501" id="kode_bagian_val">
            <input type="hidden" class="form-control" name="kode_klas" value="<?php echo isset($kode_klas)?$kode_klas:''?>" id="kode_klas_val">
            <input type="hidden" class="form-control" name="dr_merawat" id="dr_merawat" value="<?php echo isset($value->dr_merawat)?$value->dr_merawat:''?>">


            <!-- form default pelayanan pasien -->

            <div id="form_default_pelayanan" style="background-color:rgba(195, 220, 119, 0.56)"></div>

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

        </form>
    </div>

</div><!-- /.row -->

