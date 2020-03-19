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

    find_pasien_by_keyword('<?php echo $no_mr?>');

    getMenuTabs('registration/reg_pasien/riwayat_kunjungan/'+'<?php echo $no_mr?>', 'tabs_riwayat_kunjungan')

    table_booking = $('#riwayat-booking-table').DataTable({ 
      
      "processing": true, //Feature control the processing indicator.
      
      "serverSide": true, //Feature control DataTables' server-side processing mode.
            
      "ordering": false,

      "paging": false,

      "searching": false,

      "info": false,
      
      // Load data for the table's content from an Ajax source
      
      "ajax": {
          
          "url": "booking/Regon_booking/get_data_booking?kode=0",
          
          "type": "POST"
      
      },

    });


    $('#form_cari_pasien').focus();    

    $('#form_pelayanan_hd').ajaxForm({      

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

          $('#table-pesan-resep').DataTable().ajax.reload(null, false);

          $('#jumlah_r').val('')

          $("#modalEditPesan").modal('hide');  

          if(jsonResponse.type_pelayanan == 'Penunjang Medis' )
          {
            getMenuTabs('registration/reg_pasien/riwayat_kunjungan/'+jsonResponse.no_mr, 'tabs_riwayat_kunjungan')

          }
          
        }else{          

          $.achtung({message: jsonResponse.message, timeout:5});          

        }        

        //achtungHideLoader();        

      }      

    });     
      
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

    $('#tabs_tindakan').click(function (e) {     
      
      e.preventDefault();  

      $("#tabs_riwayat_kunjungan").hide('fast');  

      $('#form_pelayanan_hd').attr('action', 'hemodialisa/Hd_pelayanan_pasien/process');

    });

    $('#tabs_pesan_resep').click(function (e) {     
      
      e.preventDefault();  

      $("#tabs_riwayat_kunjungan").hide('fast');  

      $('#form_pelayanan_hd').attr('action', 'farmasi/Farmasi_pesan_resep/process');

    });   

    $('#tabs_penunjang_medis').click(function (e) {     
      
      e.preventDefault();  

      $("#tabs_riwayat_kunjungan").show();  

      $('#form_pelayanan_hd').attr('action', 'registration/Reg_pm/process');

    });   


})


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

function find_pasien_by_keyword(keyword){  

  $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien') ?>?keyword=" + keyword, '', function (data) {      
          achtungHideLoader();          

          if( data.count == 0){

            $('#div_load_after_selected_pasien').hide('fast');

            $('#div_riwayat_pasien').hide('fast');
            
            $('#div_penangguhan_pasien').hide('fast');

            /*reset all field data*/
            $('#no_mr').text('-');$('#noMrHidden').val('');$('#no_ktp').text('-');$('#nama_pasien').text('-');$('#jk').text('-');$('#umur').text('-');$('#alamat').text('-');$('#noKartuBpjs').val('-');$('#kode_perusahaan').text('-');$('#total_kunjungan').text('-');

            alert('Data tidak ditemukan'); return $("#form_cari_pasien").focus();

          }

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

            $('#jk').text(obj.jen_kelamin);

            $('#umur').text(umur_pasien+' Tahun');
            
            $('#umur_saat_pelayanan_hidden').val(umur_pasien);

            $('#alamat').text(obj.almt_ttp_pasien);

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

            penjamin = (obj.nama_perusahaan==null)?'-':obj.nama_perusahaan;
            kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;

            $('#kode_perusahaan').text(kelompok+' '+obj.nama_perusahaan);
            
            $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);
            /*penjamin pasien*/
            $('#kode_kelompok_hidden').val(obj.kode_kelompok);

            $('#InputKeyPenjamin').val(obj.nama_perusahaan);
            $('#InputKeyNasabah').val(obj.nama_kelompok);

            $('#total_kunjungan').text(obj.total_kunjungan);

            /*for tabs riwayat*/
            $('#tabs_riwayat_kunjungan_id').attr('data-id', obj.no_mr);
            $('#tabs_riwayat_transaksi_id').attr('data-id', obj.no_mr);
            $('#tabs_riwayat_perjanjian_id').attr('data-id', obj.no_mr);
            $('#tabs_riwayat_booking_online_id').attr('data-id', obj.no_mr);

            /*$("#myTab li").removeClass("active");*/
            $("#tabs_form_pelayanan_hd").load('hemodialisa/Hd_pelayanan_pasien/tindakan/<?php echo $id?>/'+obj.no_mr+'');

            if(data.count_pending > 0){

              /*show pending data pasien*/
              
              $('#div_penangguhan_pasien').show('fast');

              $('#div_load_after_selected_pasien').hide('fast');

              $('#div_riwayat_pasien').show('fast');

              $('#result_penangguhan_pasien tbody').remove();

              $.each(pending_data_pasien, function (x, y) {                  

                  dt = new Date(y.tgl_masuk);
                  
                  formatDt = formatDate(dt);
                  
                  if(y.total_ditangguhkan > 0){
                    status = 'Total Ditangguhkan '+y.total_ditangguhkan+'';
                  }else{
                    status = '<label class="label label-danger">Belum dipulangkan</label>';
                  }
                  $('<tr><td>'+y.no_kunjungan+'</td><td>'+y.no_registrasi+'</td><td>'+formatDt+'<td>'+y.poli+'</td><td>'+y.dokter+'</td><td>'+y.penjamin+'</td><td>'+status+'</td></tr>').appendTo($('#result_penangguhan_pasien'));                    

              }); 


            }else{

              $('#div_penangguhan_pasien').hide('fast');

              $('#result_penangguhan_pasien tbody').remove();

              /*show detail form */

              $('#div_load_after_selected_pasien').show('fast');

              $('#div_riwayat_pasien').show('fast');

            }


          }else{              

            $("#result_pasien_data tr").remove();

            $.each(data.result, function (i, o) {                  

                d = new Date(o.tgl_lhr);
                
                e = formatDate(d);
                
                penjamin = (o.nama_perusahaan==null)?'-':o.nama_perusahaan;
                
                umur = (o.umur=='undefined')?'-':o.umur;

                $('<tr><td>'+o.no_mr+'</td><td>'+o.nama_pasien+'</td><td>'+o.tempat_lahir+', '+e+'</td><td>'+umur+'</td><td>'+o.almt_ttp_pasien+'</td><td>'+penjamin+'</td><td align="center"><a href="#" class="btn btn-xs btn-pink" onclick="select_item_from_modal_pasien('+"'"+o.no_mr+"'"+')"><i class="fa fa-arrow-down"></i></a></td></tr>').appendTo($('#result_pasien_data'));                    

            }); 

            showModal();  

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
      <form class="form-horizontal" method="post" id="form_pelayanan_hd" action="#" enctype="multipart/form-data" autocomplete="off" >      
        
          <br>

          <!-- hidden form -->
          <input type="hidden" value="" name="noMrHidden" id="noMrHidden">
          <input type="hidden" name="flag" value="noKartu">
          <input type="hidden" name="umur_saat_pelayanan_hidden" value="" id="umur_saat_pelayanan_hidden">
          <input type="hidden" name="nama_pasien_hidden" value="" id="nama_pasien_hidden">
          <input name="noKartuBpjs" id="noKartuBpjs" class="form-control" type="hidden" value="">
          <input name="is_new" id="is_new" class="form-control" type="hidden" value="<?php echo isset($is_new)?$is_new:'';?>">          
          
          <div class="col-md-2">
            <div class="box box-primary" id='box_identity'>
                <img id="avatar" class="profile-user-img img-responsive center" src="<?php echo base_url().'assets/img/avatar.png'?>" alt="User profile picture" style="width:100%">

                <h3 class="profile-username text-center"><div id="no_mr">No. MR</div></h3>

                <ul class="list-group list-group-unbordered">
                  <li class="list-group-item">
                    <div id="nama_pasien">Nama Pasien</div>
                  </li>
                  <li class="list-group-item">
                    <div id="no_ktp">NIK</div>
                  </li>
                  <li class="list-group-item">
                    <div id="jk">Jenis Kelamin</div>
                  </li>
                  <li class="list-group-item">
                    <div id="umur">Umur</div>
                  </li>
                  <li class="list-group-item">
                    <div id="alamat">Alamat</div>
                  </li>
                  <li class="list-group-item">
                    <div id="kode_perusahaan">Penjamin</div>
                  </li>
                </ul>
              <!-- /.box-body -->
            </div>
          </div>

          <div class="col-md-10">

            <!-- tanggal pelayanan -->
            <div class="form-group">
                    
              <label class="control-label col-sm-2">Tanggal</label>
              
              <div class="col-md-2">
                
                <div class="input-group">
                    
                    <input name="tgl_registrasi" id="tgl_registrasi" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
                    <span class="input-group-addon">
                      
                      <i class="ace-icon fa fa-calendar"></i>
                    
                    </span>
                  </div>
              
              </div>

              <label class="control-label col-sm-1">Petugas</label>
              
              <div class="col-md-3">
                
                <input type="text" class="form-control" name="petugas" value="<?php echo $this->session->userdata('user')->fullname?>" readonly>
              
              </div>

            </div>

            <!-- cari data pasien -->
            <div class="form-group" id="search_mr_form">

              <label class="control-label col-sm-2"><b>CARI PASIEN</b></label>            

              <div class="col-md-4">            

                <div class="input-group">

                  <input type="text" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>">

                  <span class="input-group-btn">

                    <button type="button" id="btn_search_pasien" class="btn btn-inverse btn-sm">

                      <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>

                      Search

                    </button>

                  </span>

                </div>

              </div>

            </div>
            
            <br>
            
            <div class="pull-right">
            <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-home" ></i> Pulangkan Pasien</a>
            <a href="#" class="btn btn-xs btn-success"><i class="fa fa-bolt"></i> Rujuk ke Rawat Inap</a>
            <a href="#" class="btn btn-xs btn-purple"><i class="fa fa-stethoscope"></i> Rujuk ke Poli Lain</a>
            </div>
            <p><b><i class="fa fa-edit"></i> FORM PELAYANAN PASIEN </b></p>

            <div class="tabbable">  

              <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                  <a data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo $id?>" data-url="hemodialisa/Hd_pelayanan_pasien/tindakan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan_hd')">
                    <i class="green ace-icon fa fa-history bigger-120"></i>
                    TINDAKAN
                  </a>
                </li>

                <li>
                  <a data-toggle="tab" data-id="<?php echo $id?>" data-url="farmasi/Farmasi_pesan_resep/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>" id="tabs_pesan_resep" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan_hd')" >
                    <i class="red ace-icon fa fa-money bigger-120"></i>
                    PEMESANAN RESEP
                  </a>
                </li>

                <li>
                  <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/Reg_pm/rujuk_pm/<?php echo $value->no_registrasi?>/<?php echo $value->kode_bagian?>" id="tabs_penunjang_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan_hd')" >
                    <i class="orange ace-icon fa fa-globe bigger-120"></i>
                    PENUNJANG MEDIS
                  </a>
                </li>

              </ul>

              <div class="tab-content">

                <div id="tabs_form_pelayanan_hd">
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

                <div id="tabs_riwayat_kunjungan" style="display:none;width:100%;">
                  <div class="alert alert-block alert-success">
                    
                  </div>
                </div>

              </div>

            </div>

          </div>

        </form>
    </div>

</div><!-- /.row -->


