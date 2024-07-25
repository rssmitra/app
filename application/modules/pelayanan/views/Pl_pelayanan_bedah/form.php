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
    
  // by default show rekam medis pasien
    getMenuTabsHtml("templates/References/get_riwayat_medis/<?php echo $value->no_mr?>", 'tabs_form_pelayanan_rm')

    /*when page load find pasien by mr*/
    find_pasien_by_keyword('<?php echo $no_mr?>');

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

          $('#table-pesan-resep').DataTable().ajax.reload(null, false);

          $('#jumlah_r').val('')

          $("#modalEditPesan").modal('hide');  

          if(jsonResponse.type_pelayanan == 'penunjang_medis' ){

            $('#table_order_penunjang').DataTable().ajax.reload(null, false);

          }

          if(jsonResponse.type_pelayanan == 'pasien_selesai' )
          {

            getMenu('pelayanan/Pl_pelayanan');

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

        find_pasien_by_keyword( $("#form_cari_pasien").val() );

      }    

    });   

    /*onchange form module when click tabs*/
    $('#tabs_tindakan').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_bedah/process');

    });

    $('#tab_obat_alkes').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/process_add_obat');

    });

    $('#tabs_pesan_resep').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'farmasi/Farmasi_pesan_resep/process');

    });   

    $('#tabs_penunjang_medis').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'registration/Reg_pm/process');

    });

    $('#tabs_riwayat_medis').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_bedah/process_add_diagnosa');

    });

    $('#tabs_data_kelahiran').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_bedah/process_add_kelahiran');

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
            $('#nama_pasien').text(obj.nama_pasien);
            $('#nama_pasien_hidden').val(obj.nama_pasien);
            $('#tgl_lhr').text(getFormattedDate(obj.tgl_lhr));
            $('#jk').text(obj.jen_kelamin);
            $('#umur').text(umur_pasien+' Tahun');          
            $('#umur_saat_pelayanan_hidden').val(umur_pasien);
            $('#alamat').text(obj.almt_ttp_pasien);
            $('#noKartuBpjs').val(obj.no_kartu_bpjs);
            $('#hp').text(obj.no_hp);
            $('#no_telp').text(obj.tlp_almt_ttp);
            $('#catatan_pasien').text(obj.keterangan);

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
            $("#tabs_form_pelayanan").load('pelayanan/Pl_pelayanan_bedah/tindakan/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Rajal&kode_bag='+$('#kode_bagian_val').val()+'');

          }            

    }); 

}

function selesaikanKunjungan(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_bedah/processPelayananSelesai');
  $('#form_default_pelayanan').show('fast');
  $('#form_default_pelayanan').load('pelayanan/Pl_pelayanan_bedah/form_end_visit?mr='+noMr+'&id='+$('#id_pesan_bedah').val()+'&no_kunjungan='+$('#no_kunjungan').val()+''); 

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
    $('#form_modal').load('registration/reg_pasien/form_perjanjian_modal/'+noMr); 
    $("#GlobalModal").modal();
  }
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
          getMenu('pelayanan/Pl_pelayanan');
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

}

function rollback(no_registrasi, no_kunjungan){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan/rollback",
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
          <input type="hidden" name="id_pesan_bedah" id="id_pesan_bedah" value="<?php echo ($id)?$id:''?>">
          <input type="hidden" name="jenis_layanan" id="jenis_layanan" value="<?php echo ($value->jenis_layanan)?$value->jenis_layanan:''?>">
          <input type="hidden" name="nama_pasien_hidden" value="" id="nama_pasien_hidden">
          <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dokter_pemeriksa">
          <input type="hidden" name="no_registrasi" class="form-control" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" >
          <input type="hidden" name="no_kunjungan" class="form-control" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan" >
          <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>">
          
          <input type="hidden" class="form-control" name="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
          <input type="hidden" class="form-control" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>" id="kode_perusahaan_val">
          <input type="hidden" class="form-control" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
          <input type="hidden" class="form-control" name="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien:''?>">
          <input type="hidden" class="form-control" name="kode_bagian" value="<?php echo isset($kode_bagian)?$kode_bagian:''?>" id="kode_bagian_val">
          <input type="hidden" class="form-control" name="kode_klas" value="<?php echo isset($kode_klas)?$kode_klas:''?>" id="kode_klas_val">
          <input type="hidden" class="form-control" name="dokter1" id="dokter1" value="<?php echo isset($value->dokter1)?$value->dokter1:''?>">
          <input type="hidden" class="form-control" name="kode_tarif_existing" id="kode_tarif_existing" value="<?php echo isset($value->kode_tarif)?$value->kode_tarif:''?>">
          <input type="hidden" class="form-control" name="nama_tarif_existing" id="nama_tarif_existing" value="<?php echo isset($value->nama_tarif)?$value->nama_tarif:'';?>">
          <input type="hidden" class="form-control" name="kode_ri" id="kode_ri" value="<?php echo isset($value->kode_ri)?$value->kode_ri:'';?>">

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
          <div class="col-md-10">

            <p><b> KAMAR BEDAH <i class="fa fa-angle-double-right bigger-120"></i></b></p>
            <table class="table table-bordered pull-left" style="width:100% !important">
                <tr style="background-color:#428bca; color: white">
                  <!-- <th>Kode</th> -->
                  <th style="width: 80px">&nbsp;</th>
                  <th style="width: 140px">Tanggal Pesan</th>
                  <th style="width: 200px">Dokter</th>
                  <th style="width: 100px">No. Ruangan</th>
                  <th>Tindakan</th>
                  <th style="width: 200px">Estimasi Biaya</th>
                </tr>
                <tr>
                  <td align="center">
                    <div class="btn-group">
                        <button class="btn btn-xs btn-yellow">Action</button>
                        <button data-toggle="dropdown" class="btn btn-xs btn-yellow dropdown-toggle" aria-expanded="true">
                          <span class="ace-icon fa fa-caret-down icon-only"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-yellow">
                          <li><a href="#" onclick="selesaikanKunjungan()">Kembali ke Ruangan</a></li>
                          <li><a href="#" onclick="getMenu('kamar_bedah/Ok_acc_jadwal_bedah/form/<?php echo $value->id_pesan_bedah?>/<?php echo $value->no_kunjungan?>?act=edit')">Ubah Jadwal Operasi</a></li>
                          <li class="divider"></li>
                          <li><a href="#" onclick="getMenu('pelayanan/Pl_pelayanan_bedah')">Kembali ke Daftar Utama</a></li>
                        </ul>
                    </div>
                  </td>
                  <!-- <td><?php echo isset($value->id_pesan_bedah)?$value->id_pesan_bedah:''?></td> -->
                  <td><?php echo isset($value->tgl_pesan)?$this->tanggal->formatDateTime($value->tgl_pesan):''?></td>
                  <td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
                  <td align="center"><?php echo isset($value->no_kamar)?$value->no_kamar:'';?></td>
                  <td><?php echo isset($value->nama_tarif)?'<b>'.strtoupper($value->nama_tarif).'</b>':'';?></td>
                  <td align="right"><?php echo isset($value->total)?number_format($value->total):'';?></td>
                  
                  
                </tr>
            </table>  

            <p style="padding-top:20px"><b> RAWAT INAP <i class="fa fa-angle-double-right bigger-120"></i></b></p>
            <table class="table table-bordered">
                <tr style="background-color:#428bca; color: white">
                  <!-- <th>Kode</th>
                  <th>No Reg</th> -->
                  <th width="80px">Status</th>
                  <th style="width: 140px">Tanggal Masuk RI</th>
                  <th style="width: 200px">Dokter Merawat</th>
                  <th>Kelas</th>
                  <th>Ruangan</th>
                  <th>Kamar / Bed</th>
                  <th>Penjamin</th>
                  <!-- <th>Petugas</th> -->
                </tr>
                <tr>
                  <!-- <td><?php echo isset($val_ri->no_kunjungan)?$val_ri->no_kunjungan:''?></td>
                  <td><?php echo isset($val_ri->no_registrasi)?$val_ri->no_registrasi:''?></td> -->
                  <td><?php echo isset($val_ri->pasien_titipan)?($val_ri->pasien_titipan==1)?'<label class="label label-danger">Titipan</label>':'-':''?></td>
                  <td><?php echo isset($val_ri->tgl_masuk)?$this->tanggal->formatDateTime($val_ri->tgl_masuk):''?></td>
                  <td><?php echo isset($val_ri->nama_pegawai)?$val_ri->nama_pegawai:'';?></td>
                  <td><?php echo isset($val_ri->klas)?$val_ri->klas:'';?></td>
                  <td><?php echo isset($val_ri->nama_bagian)?$val_ri->nama_bagian:'';?></td>
                  <td align="center"><?php echo isset($val_ri)?$val_ri->no_kamar.' / '.$val_ri->no_bed:'';?></td>
                  <td><?php echo isset($val_ri->nama_kelompok)?ucwords($val_ri->nama_kelompok).' / ':'';?>
                  <?php echo isset($val_ri->nama_perusahaan)?$val_ri->nama_perusahaan:'';?></td>
                  <!-- <td><?php echo $this->session->userdata('user')->fullname?></td> -->
                  
                </tr>

            </table> 
            
            
            <?php if(isset($value) AND $value->jenis_layanan==1) :?>
              <span style="margin-left:-16%;position:absolute;transform: rotate(-25deg) !important; margin-top: 18%" class="stamp is-nope-2">CITO</span>
            <?php endif;?>

            <div id="form_default_pelayanan"></div>   
            
            <p style="padding-top:5px"><b> FORM PELAYANAN PASIEN KAMAR BEDAH <i class="fa fa-angle-double-right bigger-120"></i></b></p>
            
            <div class="col-md-8 no-padding">
              <div class="tabbable">  

                <ul class="nav nav-tabs" id="myTab">

                  <li>
                    <a data-toggle="tab" data-id="<?php echo $id?>" data-url="pelayanan/Pl_pelayanan_bedah/riwayat_medis/<?php echo $value->no_kunjungan?>" id="tabs_riwayat_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
                      <i class="red ace-icon fa fa-file bigger-120"></i>
                      Diagnosis
                    </a>
                  </li>

                  <li>
                    <a data-toggle="tab" id="tabs_catatan" href="#" data-id="<?php echo $no_kunjungan?>?type=Ranap&no_mr=<?php echo $value->no_mr?>" data-url="pelayanan/Pl_pelayanan/catatan_lainnya/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      <i class="blue ace-icon fa fa-file bigger-120"></i>
                      Pengkajian Pasien
                    </a>
                  </li>

                  

                  <li>
                    <a data-toggle="tab" data-id="<?php echo $id?>" data-url="farmasi/Farmasi_pesan_resep/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>/<?php echo $id?>?kode_bag=<?php echo $kode_bagian?>" id="tabs_pesan_resep" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
                      <i class="red ace-icon fa fa-pencil-square-o bigger-120"></i>
                      Pesan Resep
                    </a>
                  </li>

                  <li>
                    <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/Reg_pm/rujuk_pm/<?php echo $value->no_registrasi?>/<?php echo $kode_bagian?>/<?php echo $kode_klas?>/rajal" id="tabs_penunjang_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
                      <i class="orange ace-icon fa fa-globe bigger-120"></i>
                      Penunjang Medis
                    </a>
                  </li>

                  <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="true">
                    <i class="green ace-icon fa fa-money bigger-120"></i> Billing Pasien &nbsp;
                      <i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-info">
                      <li>
                        <a data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($kode_bagian)?$kode_bagian:''?>" data-url="pelayanan/Pl_pelayanan_bedah/tindakan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                          Input Tarif Tindakan
                        </a>
                      </li>
                      <li>
                        <a data-toggle="tab" id="tab_obat_alkes" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($kode_bagian)?$kode_bagian:''?>" data-url="pelayanan/Pl_pelayanan_bedah/obat_alkes/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                          Input Obat/Alkes
                        </a>
                      </li>
                      <li>
                        <a data-toggle="tab" data-id="<?php echo $id?>" data-url="billing/Billing/getDetail/<?php echo $value->no_registrasi?>/RI" id="tabs_billing_pasien" href="#" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
                          Billing Pasien
                        </a>
                      </li>
                    </ul>
                  </li>
                  


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
            <div class="col-md-4 no-padding">
              <div class="tabbable">  

                <ul class="nav nav-tabs" id="myTab2">

                  <li>
                    <a data-toggle="tab" data-id="<?php echo $id?>" data-url="templates/References/get_riwayat_medis/<?php echo $value->no_mr?>" id="tabs_rekam_medis" href="#" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan_rm')" >
                      <i class="orange ace-icon fa fa-history bigger-120"></i>
                      Rekam Medis Pasien
                    </a>
                  </li>

                </ul>

                <div class="tab-content">

                  <div id="tabs_form_pelayanan_rm">
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

        </form>
    </div>

</div><!-- /.row -->




