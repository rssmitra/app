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



    $('#tabs_pesan_resep').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'farmasi/Resep_ri/process');

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
            penjamin = (obj.nama_perusahaan==null)?obj.nama_kelompok:obj.nama_perusahaan;
            kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;

            $('#kode_perusahaan').text(penjamin);
            
            $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);

            /*default show tabs*/
            $("#tabs_form_pelayanan").load('farmasi/Resep_ri/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>');

          }            

    }); 

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
          getMenu('pelayanan/Pl_pelayanan_ri');
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
          <input type="hidden" class="form-control" name="no_kunjungan" value="<?php echo isset($value)?$value->no_kunjungan:''?>">
          <input type="hidden" class="form-control" name="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
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
          <input type="hidden" name="no_registrasi" class="form-control" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" readonly>
          <input type="hidden" name="no_kunjungan" class="form-control" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan" readonly>
          <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:0?>">
          
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
                <a href="#" class="btn btn-primary btn-block center" onclick="showModalEditPasien()"><b>Selengkapnya</b></a>
              <!-- /.box-body -->
            </div>
          </div>

          <!-- form pelayanan -->
          <div class="col-md-10">

            <p><b><i class="fa fa-file"></i> DATA PASIEN RAWAT INAP </b></p>
            <!-- informasi pendaftaran pasien -->
            <table class="table table-bordered">
              <tr>
                <td>No Registrasi</td><td><?php echo isset($value->no_registrasi)?$value->no_registrasi:''?></td>
                <td>Kode Kunjungan</td><td><?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?> | <?php echo isset($value->tgl_masuk)?$this->tanggal->formatDateTime($value->tgl_masuk):''?></td>
              </tr>
              <tr>
                <td>Ruangan</td><td><?php echo isset($value->nama_bagian)?$value->nama_bagian:'';?> <?php echo isset($ruangan)?'Kamar: '.$ruangan->no_kamar.'  Bed: '.$ruangan->no_bed:'';?></td>
                <td>Dokter Merawat</td><td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
              </tr>
              <tr>
                <td>Penjamin</td><td><?php echo isset($value->nama_kelompok)?ucwords($value->nama_kelompok).' / ':'';?>
                <?php echo isset($value->nama_perusahaan)?$value->nama_perusahaan:'';?></td>
                <td>Kelas</td><td><?php echo isset($value->klas)?$value->klas:'';?></td>
              </tr>
              <tr>
                <td>Status Titipan</td><td><?php echo isset($value->pasien_titipan)?($value->pasien_titipan==1)?'<label class="label label-danger">Titipan</label>':'':''?></td>
                <td>Petugas</td><td><?php echo $this->session->userdata('user')->fullname?></td>
              </tr>

            </table>            

            <p><b><i class="fa fa-flask"></i> RESEP RAWAT INAP </b></p>

            <div class="tabbable">  

              <ul class="nav nav-tabs" id="myTab">

                <li class="active">
                  <a data-toggle="tab" data-id="<?php echo $id?>" data-url="farmasi/Resep_ri/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>" id="tabs_pesan_resep" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
                    <i class="red ace-icon fa fa-list bigger-120"></i>
                    PEMESANAN RESEP
                  </a>
                </li>

                <!-- <li>
                  <a data-toggle="tab" data-id="<?php echo $id?>" data-url="farmasi/Resep_ri/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>" id="tabs_pesan_resep" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
                    <i class="red ace-icon fa fa-history bigger-120"></i>
                    RIWAYAT RETUR
                  </a>
                </li> -->

                <li>
                  <a data-toggle="tab" data-id="<?php echo $id?>" data-url="billing/Billing/getDetail/<?php echo $value->no_registrasi?>/RI" id="tabs_billing_pasien" href="#" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
                    <i class="orange ace-icon fa fa-money bigger-120"></i>
                    BILLING PASIEN
                  </a>
                </li>

              </ul>

              <div class="tab-content">

                <div class="row">
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




