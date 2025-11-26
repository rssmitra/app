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
    getMenuTabsHtml('billing/Billing/getDetail/<?php echo $value->no_registrasi?>/RI', 'tabs_form_pelayanan');

    /*focus on form input pasien*/
    $('#form_cari_pasien').focus();    

    /*submit form*/
    $('#form_pelayanan').ajaxForm({      

      beforeSend: function() {        

        achtungShowLoader();          

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

          if(jsonResponse.type_pelayanan == 'Penunjang Medis' || jsonResponse.type_pelayanan == 'Rawat Jalan')
          {

            $('#riwayat-table').DataTable().ajax.reload(null, false);

          }

          if(jsonResponse.type_pelayanan == 'pasien_selesai' )
          {

            getMenu('adm_pasien/loket_kasir/Adm_kasir_ri');

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

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/process');

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

            // $("#myTab li").removeClass("active");

          }             

    }); 

}

function selesaikanKunjungan(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  //$('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/processPelayananSelesai');
  $('#form_default_pelayanan').show('fast');
  $('#form_default_pelayanan').load('adm_pasien/loket_kasir/Adm_kasir_ri/form_end_visit?mr='+noMr+'&id='+$('#kode_ri').val()+'&no_kunjungan='+$('#no_kunjungan').val()+''); 

}

function backToDefaultForm(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  $('#form_pelayanan').attr('action', 'adm_pasien/loket_kasir/Adm_kasir_ri/processPelayananSelesai');
  $('#form_default_pelayanan').hide('fast');
  $('#form_default_pelayanan').html(''); 

}

function rollback(no_registrasi, no_kunjungan){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan_ri/rollback",
      data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          getMenu('adm_pasien/loket_kasir/Adm_kasir_ri');
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
          <input type="hidden" class="form-control" name="no_kunjungan" id="no_kunjungan" value="<?php echo isset($value)?$value->no_kunjungan:''?>">
          
          <input type="hidden" class="form-control" name="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
          <input type="hidden" class="form-control" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>">
          <input type="hidden" class="form-control" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
          <input type="hidden" class="form-control" name="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien:''?>">
          <input type="hidden" class="form-control" name="kode_bagian_asal" value="<?php echo isset($value)?$value->bag_pas:''?>">
          <input type="hidden" class="form-control" name="kode_bagian" value="<?php echo isset($value)?$value->bag_pas:''?>" id="kode_bagian_val">
          <input type="hidden" class="form-control" name="kode_klas" value="<?php echo isset($kode_klas)?$kode_klas:''?>"  id="kode_klas_val">
          <input type="hidden" class="form-control" name="klas_titipan" value="<?php echo $klas_titipan ?>"  id="klas_titipan">
          <input type="hidden" class="form-control" name="kode_dokter_poli" value="<?php echo isset($value->kode_dokter)?$value->kode_dokter:''?>">
          <input type="hidden" class="form-control" name="kode_ruangan" value="<?php echo isset($value->kode_ruangan)?$value->kode_ruangan:''?>">
          <input type="hidden" value="" name="noMrHidden" id="noMrHidden">
          <input type="hidden" name="kode_ri" id="kode_ri" value="<?php echo ($id)?$id:''?>">
          <input type="hidden" name="nama_pasien_hidden" value="" id="nama_pasien_hidden">
          <input type="hidden" name="dr_merawat" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dr_merawat">
          <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>

          <input type="hidden" class="form-control" name="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>" id="no_registrasi">
          
          <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:0?>">
          
          <!-- profile Pasien -->
          <div class="col-md-2">
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
          <div class="col-md-10 no-padding">

            <div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse ace-save-state">
              <div class="center">
                <ul class="nav nav-list">

                  <li class="hover">
                    <a data-toggle="tab" data-id="<?php echo $id?>" data-url="billing/Billing/getDetail/<?php echo $value->no_registrasi?>/RI" id="tabs_billing_pasien" href="#" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')" ><i class="menu-icon fa fa-edit"></i><span class="menu-text"> Resume Billing </span></a><b class="arrow"></b>
                  </li>

                  <li class="hover">
                    <a data-toggle="tab" data-id="<?php echo $id?>" data-url="billing/Billing/payment_um_view/<?php echo $value->no_registrasi?>/RI?flag=&ID=<?php echo $id?>" id="tabs_billing_pasien" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')" ><i class="menu-icon fa fa-money"></i><span class="menu-text"> Uang Muka </span></a><b class="arrow"></b>
                  </li>

                  <li class="hover">
                    <a data-toggle="tab" data-id="<?php echo $id?>" data-url="billing/Billing/viewDetailBillingKasirRI/<?php echo $value->no_registrasi?>/RI?flag=" id="tabs_billing_pasien" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')" ><i class="menu-icon fa fa-credit-card"></i><span class="menu-text"> Pembayaran </span></a><b class="arrow"></b>
                  </li>

                  <li class="hover">
                    <a data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>" data-url="pelayanan/Pl_pelayanan_ri/tindakan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')"><i class="menu-icon fa fa-stethoscope"></i><span class="menu-text"> Tindakan </span></a><b class="arrow"></b>
                  </li>

                  <li class="hover">
                    <a data-toggle="tab" data-id="<?php echo $id?>" data-url="farmasi/Farmasi_pesan_resep/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>" id="tabs_pesan_resep" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" ><i class="menu-icon fa fa-file"></i><span class="menu-text"> Resep Farmasi </span></a><b class="arrow"></b>
                  </li>

                </ul><!-- /.nav-list -->
              </div>
            </div>

            <!-- end action form  -->
            <div class="pull-right" style="margin-bottom:3px">
              <?php if($value->status_pulang==0) :?>
              <!-- <a href="#" class="btn btn-xs btn-purple" onclick="perjanjian()"><i class="fa fa-calendar"></i> Pesan Pindah</a> -->
              <a href="#" class="btn btn-xs btn-primary" onclick="selesaikanKunjungan()" ><i class="fa fa-home"></i> Pulangkan Pasien</a>
            <?php else: ?>
              <a href="#" class="btn btn-xs btn-success" onclick="getMenu('pelayanan/Pl_pelayanan_ri')"><i class="fa fa-angle-double-left"></i> Kembali ke Daftar Pasien</a>
              <?php if($transaksi!=0):?><a href="#" class="btn btn-xs btn-danger" onclick="rollback(<?php echo isset($value)?$value->no_registrasi:'' ?>,<?php echo isset($value)?$value->no_kunjungan:''?>)"><i class="fa fa-times-circle"></i> Rollback</a><?php endif ?>
            <?php endif;?>
            </div>
            <!-- informasi pendaftaran pasien -->
            <table class="table table-bordered">
              <tr style="background-color:#f4ae11">
                <th>Kode</th>
                <th>No Reg</th>
                <th>Status</th>
                <th>Tanggal Daftar</th>
                <th>Dokter</th>
                <th>Kelas</th>
                <th>Ruangan</th>
                <th>Kamar</th>
                <th>Penjamin</th>
                <th>Petugas</th>
              </tr>

              <tr>
                <td><?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?></td>
                <td><?php echo isset($value->no_registrasi)?$value->no_registrasi:''?></td>
                <td><?php echo isset($value->pasien_titipan)?($value->pasien_titipan==1)?'<label class="label label-danger">Titipan</label>':'-':''?></td>
                <td><?php echo isset($value->tgl_masuk)?$this->tanggal->formatDateTime($value->tgl_masuk):''?></td>
                <td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
                <td><?php echo isset($value->klas)?$value->klas:'';?></td>
                <td><?php echo isset($value->nama_bagian)?$value->nama_bagian:'';?></td>
                <td><?php echo isset($ruangan)?'Kamar: '.$ruangan->no_kamar.' / Bed: '.$ruangan->no_bed:'';?></td>
                <td><?php echo isset($value->nama_kelompok)?ucwords($value->nama_kelompok).' / ':'';?>
                <?php echo isset($value->nama_perusahaan)?$value->nama_perusahaan:'';?></td>
                <td><?php echo $this->session->userdata('user')->fullname?></td>
              </tr>

            </table>            

            <div id="form_default_pelayanan" style="background-color:#77dcd373"></div>

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