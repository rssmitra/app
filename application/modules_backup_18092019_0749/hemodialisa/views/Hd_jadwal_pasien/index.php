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


    $('#form_cari_pasien').focus();    

    $('#form_perjanjian_pasien_hd').ajaxForm({      

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

          /*show action after success submit form*/
          $("#tabs_detail_pasien").load("registration/perjanjian_rj/get_by_mr/"+jsonResponse.no_mr+"&flag=HD");

          /*hide form rajal*/

          $('#btn_submit').hide('fast');

          $('#change_modul_view').hide('fast');

          $('select[name="jenis_pendaftaran"]').val('');


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

    $('select[name="jenis_pendaftaran"]').change(function () {      

        showChangeModul( $(this).val() );        

    });

    $('select[name="jenis_pendaftaran"]').change(function () {      

        showChangeModul( $(this).val() );       

    });

    $('#btn_search_pasien').click(function (e) {      

      e.preventDefault();      

      /*reset modul has selected by other*/

      $('#change_modul_view').hide('fast');

      $('select[name="jenis_pendaftaran"]').val('');

      if( $("#form_cari_pasien").val() == "" ){

        alert('Masukan keyword minimal 3 Karakter !');

        return $("#form_cari_pasien").focus();

      }else{

        achtungShowLoader();

        find_pasien_by_keyword( $("#form_cari_pasien").val() );

      }    

    });   


    $('#InputKeyPenjamin').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "templates/references/getPerusahaan",
                  data: { keyword:query },            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                  }
              });
          },
          afterSelect: function (item) {
            // do what is needed with item
            var val_item=item.split(':')[0];
            console.log(val_item);
            $('#kode_perusahaan_hidden').val(val_item);
            if( val_item == 120 ){
              $('#form_sep').show();
            }else{
              $('#form_sep').hide();
            }
          }
      });

    $('#InputKeyNasabah').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "templates/references/getKelompokNasabah",
                  data: { keyword:query },            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                  }
              });
          },
          afterSelect: function (item) {
            // do what is needed with item
            var val_item=item.split(':')[0];
            console.log(val_item);
            $('#kode_kelompok_hidden').val(val_item);
            // if(val_item !== 3){
            //   $('#kode_perusahaan_hidden').val('');
            //   $('#InputKeyPenjamin').val('');
            // }  
          }
      });

})


function showChangeModul(modul_id, id_tc_pesanan=''){

  $('#change_modul_view').show('fast');

    if ( modul_id )  {          

      /*load modul*/

      $('#change_modul_view').load('hemodialisa/Hd_jadwal_pasien/show_modul/'+ modul_id +'/' + id_tc_pesanan) ;

      $('#btn_submit').show('fast');

    } else {          

      /*Eksekusi jika salah*/
      $('#btn_submit').hide('fast');
    }

    /*change action*/
    if ( modul_id ==8 ) {          
      $('#form_perjanjian_pasien_hd').attr('action', 'hemodialisa/hd_jadwal_pasien/process_perjanjian');
    } else {   
      /*Eksekusi jika salah*/
      $('#form_perjanjian_pasien_hd').attr('action', '#');

    } 

}

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


function find_data_booking(kode_booking){
  
    table_booking.ajax.url('booking/Regon_booking/get_data_booking?kode='+kode_booking).load();

}

function format ( data ) {

    return data.html;

}

function find_pasien_by_keyword(keyword){

  $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien') ?>?keyword=" + keyword, '', function (data) {           
          achtungHideLoader();

          $('#pasien_dengan_perjanjian').hide('fast');
          $('#label_info_rujukan').hide('fast');
          $('#kode_rujukan_hidden').val(0);
          $('#id_tc_pesanan').val('');

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

            $('#alamat_pasien_hidden').val(obj.almt_ttp_pasien);

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

            $('#div_riwayat_pasien').show();
            
            if( obj.kode_perusahaan==120){

              $('#form_sep').show('fast'); 

              //showModalFormSep(obj.no_kartu_bpjs,obj.no_mr);

            }else{

              $('#form_sep').hide('fast'); 

            }

            penjamin = (obj.nama_perusahaan==null)?'-':obj.nama_perusahaan;
            kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;

            $('#kode_perusahaan').text(obj.nama_perusahaan);
            
            $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);
            /*penjamin pasien*/
            $('#kode_kelompok_hidden').val(obj.kode_kelompok);

            $('#InputKeyPenjamin').val(obj.nama_perusahaan);
            $('#InputKeyNasabah').val(obj.nama_kelompok);

            $('#total_kunjungan').text(obj.total_kunjungan);

            /*for tabs riwayat*/
            $('#tabs_riwayat_perjanjian_id').attr('data-id', obj.no_mr);

             $("#tabs_detail_pasien").load("registration/perjanjian_rj/get_by_mr/"+obj.no_mr);

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

      <form class="form-horizontal" method="post" id="form_perjanjian_pasien_hd" action="#" enctype="multipart/form-data" autocomplete="off" >  
          <br>
          <!-- hidden form -->
          <input type="hidden" name="umur_saat_pelayanan_hidden" value="" id="umur_saat_pelayanan_hidden">
          <input type="hidden" name="no_mr" value="" id="noMrHidden">
          <input type="hidden" name="nama_pasien" value="" id="nama_pasien_hidden">
          <input type="hidden" name="alamat" value="" id="alamat_pasien_hidden">
          
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

            </div>
          </div>

          <div class="col-md-10">

              <!-- tanggal pelayanan -->
              <div class="form-group">
                      
                <label class="control-label col-sm-2">Tanggal</label>
                
                <div class="col-md-3">
                  
                  <div class="input-group">
                      
                      <input name="tgl_registrasi" id="tgl_registrasi" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
                      <span class="input-group-addon">
                        
                        <i class="ace-icon fa fa-calendar"></i>
                      
                      </span>
                    </div>
                
                </div>

                <label class="control-label col-sm-1">Petugas</label>
                
                <div class="col-md-2">
                  
                  <input type="text" name="petugas" class="form-control" value="<?php echo $this->session->userdata('user')->fullname?>" readonly>
                
                </div>

              </div>

              <!-- cari data pasien -->
              <div class="form-group" id="search_mr_form">

                <label class="control-label col-sm-2"><b>CARI PASIEN</b></label>            

                <div class="col-md-6">            

                  <div class="input-group">

                    <input type="text" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>">

                    <span class="input-group-btn">

                      <button type="button" id="btn_search_pasien" class="btn btn-default btn-sm">

                        <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>

                        Search

                      </button>

                    </span>

                  </div>

                </div>

              </div>

              <!-- Jenis Perjanjian -->
              <div class="form-group">
                      
                <label class="control-label col-sm-2">Jenis Perjanjian</label>
                
                <div class="col-md-3">
                  
                  <select name="jenis_pendaftaran" class="form-control" id="jenis_pendaftaran">

                    <option value="">-Silahkan Pilih-</option>

                    <option value="8">Perjanjian HD</option>


                  </select>
                
                </div>
              
              </div>

              <!-- change modul view -->
              <div id="change_modul_view" style="margin-top:10px"></div>

              <!-- riweayat perjanjian -->
              <div id="div_riwayat_pasien" style="display:none">

                <hr>

                <p><b><i class="fa fa-history"></i> RIWAYAT PERJANJIAN PASIEN HD </b></p>

                <div class="tabbable">  

                  <ul class="nav nav-tabs" id="myTab">

                    <li class="active">
                      <a data-toggle="tab" data-id="0" data-url="registration/perjanjian_rj/get_by_mr" id="tabs_riwayat_perjanjian_id" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_detail_pasien')" aria-expanded="true">
                        <i class="purple ace-icon fa fa-file bigger-120"></i>
                        PERJANJIAN HEMODIALISA
                      </a>
                    </li>

                  </ul>

                  <div class="tab-content">

                    <div id="tabs_detail_pasien">

                    </div>

                  </div>

                </div>

              </div>

          </div>

      </form>

      <hr>
      <!-- TABS  -->

      <!--  -->

    </div>

</div><!-- /.row -->


