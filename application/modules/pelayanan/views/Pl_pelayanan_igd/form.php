
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
    // list pasien
    get_worklist_pasien();
    window.filter = function(element)
    {
      var value = $(element).val().toUpperCase();
      $(".list-group > li").each(function() 
      {
        if ($(this).text().toUpperCase().search(value) > -1){
          $(this).show();
        }
        else {
          $(this).hide();
        }
      });
    }
    $('#tabs_pengkajian').click();
    $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveCatatanPengkajian');

    // getMenuTabs('pelayanan/Pl_pelayanan_igd/tindakan/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo KODE_IGD?>', 'tabs_form_pelayanan');

    getMenuTabsHtml("templates/References/get_riwayat_medis/<?php echo $value->no_mr?>", 'tabs_riwayat_medis_pasien');
    getMenuTabsHtml("templates/References/get_riwayat_pm/<?php echo $value->no_mr?>", 'tabs_riwayat_pm_pasien');

    /*focus on form input pasien*/
    $('#form_cari_pasien').focus();    

    /*submit form*/

    $('#form_pelayanan').on('submit', function(){
               
        $('#konten').val($('#editor_konten').html());
        $('input[name=catatan_pengkajian]' , this).val($('#editor').html());
        $('#konten_diagnosa_sekunder_igd').val($('#pl_diagnosa_sekunder_igd_hidden_txt').html());

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
              if( $('#form_pelayanan').attr('action')=='pelayanan/Pl_pelayanan_igd/processPelayananSelesai' ){
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

                if(jsonResponse.type_pelayanan == 'penunjang_medis' )
                {
                  $('#table_order_penunjang').DataTable().ajax.reload(null, false);
                }

                if(jsonResponse.type_pelayanan == 'pasien_selesai' )
                {
                  // getMenu('pelayanan/Pl_pelayanan_igd');
                  $('#form_default_pelayanan').html('<div class="alert alert-success"><b><i class="fa fa-check"></i> Selesai</b><br>Terima Kasih pasien telah dilayani dengan baik.</div>');
                }

                if(jsonResponse.type_pelayanan == 'pasien_meninggal' )
                {
                  $('#btn_cetak_meninggal').show('fast');
                  $('#btn_selesai_igd').hide('fast');
                  $("html, body").animate({ scrollTop: "0" });
                  $('#kode_meninggal').val(jsonResponse.kode_meninggal);
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

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_igd/process');
      $('#form_default_pelayanan').hide('fast');
      $('#form_default_pelayanan').html(''); 

    });

    $('#tabs_cppt').click(function (e) {   
      e.preventDefault();  
      getMenuTabsHtml("templates/References/get_riwayat_medis/<?php echo $value->no_mr?>", 'tabs_riwayat_medis_pasien');
      getMenuTabsHtml("templates/References/get_riwayat_pm/<?php echo $value->no_mr?>", 'tabs_riwayat_pm_pasien');
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_cppt');
    });


    $('#tabs_diagnosa').click(function (e) {    
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_igd/processSaveDiagnosaDr');
    });

    $('#tabs_pengkajian').click(function (e) {   
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveCatatanPengkajian');
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

function get_worklist_pasien(){  

  $('#box_list_pasien').html('Loading...');
  html = ''; 
  $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_igd/get_list_pasien') ?>?tgl="+$('#tgl_kunjungan_antrian').val()+"", '' , function (response) {    
    
    html += '<ol class="list-group list-group-unbordered" id="list_pasien" style="background-color:lightblue;height: 650px;overflow: scroll;">';

    $.each(response.data, function( i, v ) {
      var obj = v[0];
      html += '<li class="list-group-item">';
      html += '<small style="color: '+obj.color_txt+'; font-weight: bold; font-size: 11px; cursor: pointer;" onclick="getMenu('+"'pelayanan/Pl_pelayanan_igd/form/"+obj.kode_gd+"/"+obj.no_kunjungan+"'"+', '+"'"+obj.no_mr+"'"+')">'+obj.no_mr+' - '+obj.nama_pasien+'</small>';
      html += '</li>';

    });
    html += '</ol>';
    $('#box_list_pasien').html(html);
  }); 

}

/*function find pasien*/
function find_pasien_by_keyword(keyword){  

    $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien_by_mr') ?>?keyword=" + keyword, '', function (data) {      
          // achtungHideLoader();          

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
//   $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_igd/processPelayananSelesai');
//   $('#form_default_pelayanan').show('fast');
//   $('#form_default_pelayanan').load('pelayanan/Pl_pelayanan/form_end_visit?mr='+noMr+'&id='+$('#kode_gd').val()+'&no_kunjungan='+$('#no_kunjungan').val()+''); 

// }

function selesaikanKunjungan(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  // getMenuTabs('pelayanan/Pl_pelayanan_igd/diagnosa/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Rajal&kode_bag=020101', 'tabs_form_pelayanan');
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_igd/processPelayananSelesai?bag='+$('#kode_bagian_val').val()+'');
  $('#form_default_pelayanan').show('fast');
  $('#form_default_pelayanan').load('pelayanan/Pl_pelayanan/form_end_visit?mr='+noMr+'&id='+$('#kode_gd').val()+'&no_kunjungan='+$('#no_kunjungan').val()+'');


}

function backToDefaultForm(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_igd/processPelayananSelesai');
  $('#form_default_pelayanan').hide('fast');
  $('#form_default_pelayanan').html(''); 
  
}

function cetak_surat_kematian(no_registrasi) {
  
  kode_meninggal = $('#kode_meninggal').val();
  url = 'pelayanan/Pl_pelayanan_igd/surat_kematian?kode_meninggal='+kode_meninggal+'&no_kunjungan='+<?php echo $no_kunjungan?>+'&no_registrasi='+no_registrasi+'&umur='+$('#umur_saat_pelayanan_hidden').val();
  title = 'Cetak Surat Kematian';
  width = 850;
  height = 500;
  PopupCenter(url, title, width, height); 

}

function cetak_surat_keracunan() {
  
  noMr = $('#noMrHidden').val();
  url = 'pelayanan/Pl_pelayanan_igd/surat_keracunan?no_kunjungan='+<?php echo $no_kunjungan?>+'&no_mr='+noMr;
  title = 'Cetak Surat Keracunan';
  width = 1200;
  height = 1200;
  PopupCenter(url, title, width, height); 

}

function rollback(no_registrasi, no_kunjungan){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan_igd/rollback",
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
  getMenu('pelayanan/Pl_pelayanan_igd/form/'+$('#kode_gd').val()+'/'+$('#no_kunjungan').val()+'')
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

    <div style="margin-top:-10px">   

      <form class="form-horizontal" method="post" id="form_pelayanan" action="#" enctype="multipart/form-data" autocomplete="off" >      
        
          <br>
          <!-- hidden form -->
          <input type="hidden" value="" name="noMrHidden" id="noMrHidden">
          <input type="hidden" value="" name="kode_perusahaan_hidden" id="kode_perusahaan_hidden">
          <input type="hidden" name="kode_gd" id="kode_gd" value="<?php echo ($id)?$id:''?>">
          <input type="hidden" name="kode_meninggal" id="kode_meninggal" value="<?php echo isset($meninggal->kode_meninggal)?$meninggal->kode_meninggal:''?>">
          <input type="hidden" name="nama_pasien_hidden" value="" id="nama_pasien_hidden">
          <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dokter_pemeriksa">
          <input type="hidden" name="no_registrasi" id="no_registrasi" class="form-control" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" readonly>
          <input type="hidden" name="no_kunjungan" class="form-control" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan" readonly>
          <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>

          <div class="col-md-2">
          <div style="padding-top: 5px; padding-bottom: 10px;">
            <b>Tanggal kunjungan: </b><br>
            <input name="tgl_kunjungan_antrian" id="tgl_kunjungan_antrian" class="form-control date-picker" data-date-format="yyyy-mm-dd" type="text" value="<?php echo date('Y-m-d')?>">
            <b>Cari pasien:</b> <br>
            <input type="text" id="seacrh_ul_li" value="" placeholder="Masukan keyword..." class="form-control" onkeyup="filter(this);"><a style="margin-top:4px" href="#" onclick="get_worklist_pasien()" class="btn btn-block btn-primary">Refresh</a></div>

            <div class="box box-primary" id='box_list_pasien'></div>

            <label class="label label-xs label-inverse">&nbsp;&nbsp;</label> Belum dilayani<br>
            <label class="label label-xs label-success">&nbsp;&nbsp;</label> Selesai (Sudah dilayani)<br>
            <label class="label label-xs label-danger">&nbsp;&nbsp;</label> Batal berkunjung<br>
            <label class="label label-xs label-primary">&nbsp;&nbsp;</label> Pasien Dirujuk<br>
          </div>
          <!-- form pelayanan -->
          <div class="col-md-10 no-padding">
            
            <div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse ace-save-state">
                <div class="center">
                  <ul class="nav nav-list">

                    <!-- <li class="hover">
                      <a data-toggle="tab" href="#" id="tabs_diagnosa" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=020101" data-url="pelayanan/Pl_pelayanan_igd/diagnosa/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-user"></i><span class="menu-text"> Pengkajian </span></a><b class="arrow"></b>
                    </li> -->

                    <li class="hover">
                      <a data-toggle="tab" id="tabs_pengkajian" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&no_mr=<?php echo $no_mr?>&form_no=27" data-url="pelayanan/Pl_pelayanan/catatan_lainnya/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      <i class="menu-icon fa fa-folder"></i><span class="menu-text"> <?php echo FRM_PENGKAJIAN; ?>
                      </span></a><b class="arrow"></b>
                    </li>

                    <li class="hover">
                      <a data-toggle="tab" href="#" id="tabs_diagnosa" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=020101" data-url="pelayanan/Pl_pelayanan_igd/diagnosa/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-edit"></i><span class="menu-text"> <?php echo RESUME_MEDIS; ?> </span></a><b class="arrow"></b>
                    </li>

                    <li class="hover">
                      <a data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=020101" data-url="pelayanan/Pl_pelayanan_igd/tindakan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-list"></i><span class="menu-text"> <?php echo INPUT_BILL; ?> </span></a><b class="arrow"></b>
                    </li>

                    <li class="hover">
                      <a data-toggle="tab" id="tabs_cppt" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&form=cppt" data-url="pelayanan/Pl_pelayanan_igd/cppt/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-history"></i><span class="menu-text"> <?php echo RIWAYAT_MEDIS; ?> </span></a><b class="arrow"></b>
                    </li>

                    <li class="hover">
                      <a data-toggle="tab" data-id="<?php echo $id?>" data-url="farmasi/Farmasi_pesan_resep/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>" id="tabs_pesan_resep" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
                      <i class="menu-icon fa fa-flask"></i><span class="menu-text"> <?php echo ERESEP; ?> </span></a><b class="arrow"></b>
                    </li>
                    <li class="hover">
                      <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/Reg_pm/rujuk_pm/<?php echo $value->no_registrasi?>/020101/<?php echo $kode_klas?>/rajal" id="tabs_penunjang_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')" ><i class="menu-icon fa fa-leaf"></i><span class="menu-text"> <?php echo EORDER; ?> </span></a><b class="arrow"></b>
                    </li>

                    <li class="hover">
                      <a data-toggle="tab" href="#" data-id="<?php echo $id?>" data-url="billing/Billing/getDetail/<?php echo $value->no_registrasi?>/RJ" id="tabs_billing_pasien" href="#" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-money"></i><span class="menu-text"> <?php echo RESUME_BILLING; ?></span></a><b class="arrow"></b>
                    </li>

                    <!-- <li class="hover">
                      <a data-toggle="tab" href="#" data-id="<?php echo $id?>" data-url="pelayanan/Pl_pelayanan_igd/laporan_catatan/<?php echo $value->no_kunjungan?>" id="tabs_penunjang_medis" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
                      <i class="menu-icon fa fa-file"></i><span class="menu-text"> Laporan & Catatan</span></a><b class="arrow"></b>
                    </li> -->

                    <!-- <li class="hover">
                      <a data-toggle="tab" href="#" data-id="<?php echo $id?>" data-url="registration/reg_pasien/riwayat_transaksi/<?php echo $value->no_mr?>" id="tabs_riwayat_transaksi" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-file"></i><span class="menu-text"> Transaksi </span></a><b class="arrow"></b>
                    </li> -->
                    <!-- <li class="hover">
                      <a data-toggle="tab" href="#" data-id="<?php echo $id?>" data-url="rekam_medis/File_rm/index/<?php echo $value->no_mr?>" id="tabs_rekam_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-clipboard"></i><span class="menu-text"> ERM  </span></a><b class="arrow"></b>
                    </li> -->

                    <!-- <li class="hover">
                      <a data-toggle="tab" href="#" data-id="<?php echo $id?>" data-url="templates/References/get_riwayat_medis/<?php echo $value->no_mr?>" id="tabs_rekam_medis" href="#" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_riwayat_medis_pasien')"><i class="menu-icon fa fa-history"></i><span class="menu-text"> Rekam Medis  </span></a><b class="arrow"></b>
                    </li> -->
                    
                    <?php 
                      $trans_kasir = $this->Pl_pelayanan->cek_transaksi_kasir(isset($value->no_registrasi)?$value->no_registrasi:'',isset($value->no_kunjungan)?$value->no_kunjungan:'');
                      $flag_rollback = ($trans_kasir!=true)?'submited':'unsubmit';
                    ?>
                    <li class="hover">
                      <a data-toggle="tab" href="#" data-id="<?php echo $id?>" data-url="registration/reg_pasien/riwayat_transaksi/<?php echo $value->no_mr?>" id="tabs_riwayat_transaksi" href="#" onclick="rollback(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>, <?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>, '<?php echo $flag_rollback?>')"><i class="menu-icon fa fa-undo"></i><span class="menu-text"> Rollback  </span></a><b class="arrow"></b>
                    </li>

                  </ul><!-- /.nav-list -->
                </div>
            </div>
            <table class="table" style="background: #428bca38; margin-top: 5px">
              <tr>
                <td rowspan="2">
                  <span style="font-size: 20px;font-weight: bold" id="no_mr"></span><br>
                  <span style="font-size: 14px;" id="nama_pasien"></span>
                </td>
                <td><span style="font-size: 12px; font-weight: bold;">NIK</td>
                <td><span style="font-size: 12px; font-weight: bold;">Tgl. Lahir</td>
                <td><span style="font-size: 12px; font-weight: bold;">Umur</td>
                <td><span style="font-size: 12px; font-weight: bold;">Alamat</td>
                <td><span style="font-size: 12px; font-weight: bold;">No.Telp / HP</td>
                <td><span style="font-size: 12px; font-weight: bold;">Penjamin</td>
                <td><span style="font-size: 12px; font-weight: bold;">Status </span></td>
              </tr>
              <tr>
                <td><span style="font-size: 13px;" id="no_ktp"></span></td>
                <td><span style="font-size: 13px;" id="tgl_lhr"></span></td>
                <td><span style="font-size: 13px;" id="umur"></span></td>
                <td><span style="font-size: 13px;" id="alamat"></span></td>
                <td><span style="font-size: 13px;" id="no_hp"></span></td>
                <td><span style="font-size: 13px;" id="kode_perusahaan"></span></td>
                <td>
                  <?php if(isset($value) AND $value->status_batal==1) :?>
                  <span style="font-weight: bold" class="label label-danger">Batal</span>
                  <?php else:?>
                    <?php if(isset($value) AND $value->tgl_jam_kel!=NULL) :?>
                    <span style="font-weight: bold" class="label label-success">Selesai</span>
                    <?php endif;?>  
                  <?php endif;?>
                </td>
              </tr>
            </table>

            <!-- end action form  -->
            <div class="pull-right" style="margin-top:3px">
              <?php if(empty($value->tgl_keluar)) :?>
              <a href="#" class="btn btn-xs btn-primary" id="btn_selesai_igd" onclick="selesaikanKunjungan()"><i class="fa fa-check-circle"></i> Selesaikan Kunjungan</a>
              <a href="#" class="btn btn-xs btn-danger" onclick="cancel_visit(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>,<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>)"><i class="fa fa-times-circle"></i> Batalkan Kunjungan</a>
              <?php endif;?>
              <a href="#" class="btn btn-xs btn-danger" id="btn_cetak_meninggal" onclick="cetak_surat_kematian(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>)" <?php echo isset($meninggal)?'':'style="display:none"' ?> ><i class="fa fa-file"></i> Cetak Surat Kematian</a>
              <a href="#" class="btn btn-xs btn-danger" id="cetak_keracunan" onclick="cetak_surat_keracunan()" <?php echo isset($keracunan->id_cetak_racun)?'':'style="display:none"'?>><i class="fa fa-file"></i> Cetak Surat Keracunan </a>
            </div>
            <br>
            

            <div class="pull-left" style="padding: 5px; margin-top: -20px">
              Tanggal Daftar : <span style="font-size: 14px; font-weight: bold"><?php echo isset($value->tanggal_gd)?$this->tanggal->formatDateTime($value->tanggal_gd):''?></span> | Dokter IGD : 
              <span style="font-size: 14px; font-weight: bold">[ <?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?> ]</span>
            </div>


            <!-- hidden form -->
            <input type="hidden" class="form-control" name="no_kunjungan" value="<?php echo isset($value)?$value->no_kunjungan:''?>">
            <input type="hidden" class="form-control" name="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
            <input type="hidden" class="form-control" name="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
            <input type="hidden" class="form-control" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>" id="kode_perusahaan_val">
            <input type="hidden" class="form-control" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
            <input type="hidden" class="form-control" name="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien_igd:''?>">
            <input type="hidden" class="form-control" name="umur_saat_pelayanan_hidden" id="umur_saat_pelayanan_hidden">
            <input type="hidden" class="form-control" name="kode_bagian_asal" value="<?php echo isset($value)?$value->kode_bagian_asal:''?>">
            <input type="hidden" class="form-control" name="kode_bagian" value="020101" id="kode_bagian_val">
            <input type="hidden" class="form-control" name="kode_klas" value="<?php echo isset($kode_klas)?$kode_klas:''?>" id="kode_klas_val">
            <input type="hidden" class="form-control" name="kode_dokter_igd" id="kode_dokter_igd" value="<?php echo isset($value->dokter_jaga)?$value->dokter_jaga:''?>">


            <!-- form default pelayanan pasien -->
            <div class="col-md-12 no-padding">

              <div class="col-md-8 no-padding">
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
                <div class="col-md-4" style="padding-top: 13px">
                  <div class="tabbable">
											<ul class="nav nav-tabs" id="myTab">
												<li class="active">
													<a data-toggle="tab" href="#tabs_rm">
														Riwayat Medis
													</a>
												</li>

												<li>
													<a data-toggle="tab" href="#tabs_pm">
														Hasil Penunjang
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

              

            </div>

          </div>

        </form>
    </div>

</div><!-- /.row -->



