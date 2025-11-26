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

    // list pasien
    get_list_pasien();

    /*when page load find pasien by mr*/
    find_pasien_by_keyword('<?php echo $no_mr?>');

    $('#div_main_form').load('pelayanan/Pl_pelayanan_vk/form_main/<?php echo $id; ?>/<?php echo $no_kunjungan; ?>');

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

    // getMenuTabs('pelayanan/Pl_pelayanan_vk/tindakan/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Rajal&kode_bag=030501', 'tabs_form_pelayanan');

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

          if(jsonResponse.type_pelayanan == 'penunjang_medis' ){

            $('#table_order_penunjang').DataTable().ajax.reload(null, false);

          }

          if(jsonResponse.type_pelayanan == 'pasien_selesai' )
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

    $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien_by_mr') ?>?keyword=" + keyword, '', function (data) {      

          /*if count data = 1*/
          if( data.count == 1 )     {

            var obj = data.result[0];

            var pending_data_pasien = data.pending; 
            var umur_pasien = getAge(obj.tgl_lhr, 1);

            $('#no_mr').text(obj.no_mr);

            $('#noMrHidden').val(obj.no_mr);

            $('#no_ktp').text(obj.no_ktp);
            // tambahan
            $('#nikPasien').val(obj.no_ktp);
            $('#hpPasien').val(obj.no_hp);
            $('#noTelpPasien').val(obj.tlp_almt_ttp);

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
            $('#InputKeyNasabah').val(obj.nama_kelompok);

            $('#total_kunjungan').text(obj.total_kunjungan);

            $('#full_pasien_data').text(obj.no_mr+' - '+obj.nama_pasien+' ('+obj.jen_kelamin+') | TL. '+getFormattedDate(obj.tgl_lhr)+' ('+ umur_pasien+')');


            $("#myTab li").removeClass("active");


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

function get_list_pasien(dokter_filter = ''){  

  $('#box_list_pasien').html('Loading...');
  
  var is_icu = ( $('#bag_pas').val() == '031001' ) ? 'Y' : '';
  $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_vk/get_list_pasien?is_icu=') ?>"+is_icu+"&dokter="+dokter_filter+"", '', function (response) {    
    var html = '';
    html += '<div style="padding-top: 1px; padding-bottom: 10px;">';
    html += '<b>Cari pasien rawat inap:</b> <br>';
    html += '<input type="text" id="seacrh_ul_li" value="" placeholder="Masukan keyword..." class="form-control" onkeyup="filter(this);">';
    html += '<select id="dokter_filter" class="form-control" style="margin-top:4px;margin-bottom:4px;" onchange="get_list_pasien(this.value)">';
    html += '<option value="">-- Semua Dokter --</option>';
    
    // Buat list dokter unik dari response
    var dokterList = {};
    $.each(response.data, function(i, v) {
      var obj = v[0];
      if(obj.dokter) dokterList[obj.kode_dokter] = obj.dokter;
    });

    console.log(dokter_filter);

    $.each(dokterList, function(key, val) {
      html += '<option value="'+key+'"'+(key==dokter_filter?' selected':'')+'>'+val+'</option>';
    });
    html += '</select>';
    html += '<a style="margin-top:4px" href="#" onclick="get_list_pasien()" class="btn btn-block btn-primary">Refresh</a></div>';
    html += '<ol class="list-group list-group-unbordered" id="list_pasien" style="overflow: scroll; max-height: 500px;">';
    $.each(response.data, function( i, v ) {
      var obj = v[0];
      // Filter dokter jika dipilih
      // if(dokter_filter && obj.kode_dokter != dokter_filter) return true; // Use return true to continue $.each
      html += '<li class="list-group-item" id="list_group_'+obj.no_mr+'">';
        html += '<address onclick="form_main('+"'pelayanan/Pl_pelayanan_vk/form_main/"+obj.id_pasien_vk+"/"+obj.no_kunjungan+"'"+', '+"'"+obj.no_mr+"'"+')" style="cursor: pointer;">';
        html += '<b>'+obj.nama_pasien+'</b><br>';
        html += obj.no_mr+'/ '+obj.jk+'<br>'+obj.umur+'<br>';
        html += obj.kelas+'/ Kamar '+obj.kamar+' No. '+obj.no_kamar+'<br>';
        html += obj.dokter+'<br>';
        if(obj.kode_perusahaan==120){
          html += '<span style="color: white; background: green; padding: 2px">'+obj.penjamin+'</span>';
        }else{
          html += '<span style="color: white; background: blue; padding: 2px">'+obj.penjamin+'</span>';
        }
        html += '</address>';
      html += '</li>';
    });
    html += '</ol>';
    $('#box_list_pasien').html(html);
    // Set value dokter_filter jika belum ada
    if(!$('#dokter_filter').length){
      $('#box_list_pasien').prepend('<select id="dokter_filter" class="form-control" style="margin-bottom:4px;" onchange="get_list_pasien()"></select>');
    }
  }); 

}

function form_main(url, no_mr){
  find_pasien_by_keyword(no_mr);
  $('#div_main_form').html('<span style="padding: 100px; float: left; width: 100% !important">Loading...</span>');
  $('#div_main_form').load(url);
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

    <div style="margin-top:0px">   

      <form class="form-horizontal" method="post" id="form_pelayanan" action="#" enctype="multipart/form-data" autocomplete="off" >      
        
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
          <div class="col-md-2">
            <div class="box box-primary" id='box_list_pasien'></div><br>
            <!-- <label class="label label-xs label-success">&nbsp;&nbsp;</label> LA (Lantai Atas)<br>
            <label class="label label-xs label-danger">&nbsp;&nbsp;</label> LB (Lantai Bawah)<br>
            <label class="label label-xs label-primary">&nbsp;&nbsp;</label> VK (Ruang Bersalin dan Nifas)<br>
            <label class="label label-xs label-inverse">&nbsp;&nbsp;</label> Lain-lain<br> -->
          </div>

          <!-- form pelayanan -->
          <div class="col-md-10 no-padding">
            <!-- informasi pendaftaran pasien -->
            <span class="pull-left" style="font-size: 20px; font-weight: bold; color: #0d5280" id="full_pasien_data"></span><br>
            
            <div id="div_main_form"></div>
          </div>


        </form>
    </div>

</div><!-- /.row -->

