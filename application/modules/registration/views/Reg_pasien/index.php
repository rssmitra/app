<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>

<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

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

    
    table_riwayat = $('#riwayat-table').DataTable({ 
      
      "processing": true, //Feature control the processing indicator.
      
      "serverSide": true, //Feature control DataTables' server-side processing mode.
            
      "ordering": false,
      
      // Load data for the table's content from an Ajax source
      
      "ajax": {
          
          "url": "registration/Reg_pasien/get_riwayat_pasien?mr=0",
          
          "type": "POST"
      
      },
    
    });

    $('#form_cari_pasien').focus();    

    $('#form_reg_klinik').ajaxForm({      

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

          $('#page-area-content').load('sipepp_pengaduan/adm_pengaduan/registrasi_adm?_=' + (new Date()).getTime());          
        }else{          

          $.achtung({message: jsonResponse.message, timeout:5});          

        }        

        achtungHideLoader();        

      }      

    });     

    $('select[name="klinikId"]').change(function () {      

        if ($(this).val()) {          

            $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val(), '', function (data) {              

                $('#dokterId option').remove();                

                $('<option value="">-Silahkan Pilih-</option>').appendTo($('#dokterId'));                

                $.each(data, function (i, o) {                  

                    $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#dokterId'));                    

                });                

            });            

        } else {          

            $('#dokterId option').remove()            

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

        $.getJSON("<?php echo site_url('registrasi/reg_klinik/search_pasien') ?>?keyword=" + $("#form_cari_pasien").val(), '', function (data) {              
          
          achtungHideLoader();

          if( data.count == 0){

            alert('Data tidak ditemukan'); return $("#form_cari_pasien").focus();

          }

          if( data.count == 1 )     {

            var obj = data.result[0];

            $('#no_mr').text(obj.no_mr);

            $('#noMrHidden').val(obj.no_mr);

            $('#no_ktp').text(obj.no_ktp);

            $('#nama_pasien').text(obj.nama_pasien);

            $('#jk').text(obj.jen_kelamin);

            $('#noKartuBpjs').val(obj.no_kartu_bpjs);

            if( obj.jen_kelamin == 'L' ){
            
              $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
            
            }else{
              
              $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');

            }
            
            $('#umur').text(obj.umur);

            $('#alamat').text(obj.almt_ttp_pasien);
            
            if( obj.kode_perusahaan==120){

              $('#form_sep').show('fast'); 

              showModalFormSep(obj.no_kartu_bpjs,obj.noMr);

            }else{

              $('#form_sep').hide('fast'); 

            }

            penjamin = (obj.nama_perusahaan==null)?'-':obj.nama_perusahaan;

            $('#kode_perusahaan').text(penjamin);

            $('#total_kunjungan').text(obj.total_kunjungan);

            /*get data riwayat pasien*/

            find_data_reload(obj.no_mr);

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

function find_data_reload(mr){
  
    table_riwayat.ajax.url('registration/Reg_pasien/get_riwayat_pasien?mr='+mr).load();

}

function select_item_from_modal_pasien(mr){
    
    $("#modalSearchPasien").modal('hide');

    $.getJSON("<?php echo site_url('registrasi/reg_klinik/search_pasien') ?>?keyword=" + mr, '', function (data) { 

        var obj = data.result[0];

            $('#no_mr').text(obj.no_mr);

            $('#noMrHidden').val(obj.no_mr);

            $('#no_ktp').text(obj.no_ktp);

            $('#nama_pasien').text(obj.nama_pasien);

            $('#jk').text(obj.jen_kelamin);

            if( obj.jen_kelamin == 'L' ){
            
              $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
            
            }else{
              
              $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');

            }
            
            $('#umur').text(obj.umur);

            $('#alamat').text(obj.almt_ttp_pasien);
            
            if( obj.kode_perusahaan==120){

              $('#form_sep').show('fast');

              $('#noKartuBpjs').val(obj.no_kartu_bpjs);

              $("#modalSearchPasien").modal('hide');  

              showModalFormSep(obj.no_kartu_bpjs, o.noMr);

            }else{

              $('#form_sep').hide('fast'); 

            }

            penjamin = (obj.nama_perusahaan==null)?'-':obj.nama_perusahaan;

            $('#kode_perusahaan').text(penjamin);

            $('#total_kunjungan').text(obj.total_kunjungan);

            /*get data riwayat pasien*/

      find_data_reload(obj.no_mr);

    });

}

function showModal()

{  

  $("#result_text").text('Result for "'+$('#form_cari_pasien').val()+'"');  

  $("#modalSearchPasien").modal();  

}

function showModalFormSep()

{  

  noMr = $('#noMrHidden').val();

  noKartu = $('#noKartuBpjs').val();

  $('#result_text_create_sep').text('PEMBUATAN SURAT ELIGIBILITAS PASIEN (SEP) NOMOR KARTU ('+noKartu+')');

  $('#form_create_sep_content').load('registrasi/reg_klinik/form_sep/'+noMr+''); 

  $("#modalCreateSep").modal();  

}

function showModalEditPasien()

{  

  noMr = $('#noMrHidden').val();

  noKartu = $('#noKartuBpjs').val();

  $('#result_text_create_sep').text('PEMBUATAN SURAT ELIGIBILITAS PASIEN (SEP) NOMOR KARTU ('+noKartu+')');

  $('#form_create_sep_content').load('registrasi/reg_pasien/form/'+noMr+''); 

  $("#modalCreateSep").modal();  

}

</script>

<div class="row">

  <div class="col-xs-12">  

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

      <form class="form-horizontal" method="post" id="form_reg_klinik" action="<?php echo site_url('registration/reg_klinik/process')?>" enctype="multipart/form-data">      

          <br>          

          <div class="form-group">

            <label class="control-label col-md-2"><b>CARI DATA PASIEN</b></label>            

            <div class="col-md-4">            

              <div class="input-group">

                <input type="text" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien">

                <input type="hidden" name="flag" value="noKartu">

                <span class="input-group-btn">

                  <button type="button" id="btn_search_pasien" class="btn btn-default btn-sm">

                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>

                    Search

                  </button>

                </span>

              </div>

            </div>

          </div>

          <div class="col-md-10">

            <table class="table table-bordered table-hover">

              <thead>

                <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">No MR</th>

                <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">NIK</th>

                <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Nama Pasien</th>

                <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">JK</th>

                <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Umur</th>

                <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Alamat</th>

                <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Penjamin Pasien</th>

                <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Total Kunjungan</th>

              </thead>

              <tbody>

                <td><div id="no_mr">-</div></td>

                <input type="hidden" value="" name="noMrHidden" id="noMrHidden">

                <td><div id="no_ktp">-</div></td>

                <td><div id="nama_pasien">-</div></td>

                <td align="center"><div id="jk">-</div></td>

                <td><div id="umur">-</div></td>

                <td><div id="alamat">-</div></td>

                <td><div id="kode_perusahaan">-</div></td>

                <td><div id="total_kunjungan"></div></td>

              </tbody>
              <span style="color:red;margin-top:-5%;display:none" id="alert_complate_data_pasien"><i>Silahkan lengkapi data pasien terlebih dahulu</i></span>

            </table>

            <a href="#" name="submit" class="btn btn-xs btn-purple">

                <i class="ace-icon fa fa-edit icon-on-right bigger-110"></i>

                Daftarkan Perjanjian Pasien

              </a>

              <a href="#" name="submit" class="btn btn-xs btn-success">

                <i class="ace-icon fa fa-barcode icon-on-right bigger-110"></i>

                Cetak Barcode

              </a>

              <a href="#" name="submit" class="btn btn-xs btn-primary">

                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>

                Proses

              </a>

              <a href="#" name="submit" class="btn btn-xs btn-danger">

                <i class="ace-icon fa fa-user icon-on-right bigger-110"></i>

                Ubah Data Pasien

              </a> 
              
            <div class="form-group">
              
              <label class="control-label col-sm-2">Tanggal</label>
              
              <div class="col-md-4">
                
                <div class="input-group">
                    
                    
                    <input name="tgl_registrasi" id="tgl_registrasi" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                      
                      <i class="ace-icon fa fa-calendar"></i>
                    
                    </span>
                  </div>
              
              </div>

              <label class="control-label col-sm-2">Cetak Kartu</label>

              <div class="col-md-2">

                <div class="radio">

                    <label>

                      <input name="cetak_kartu" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->is_active == 'Y') ? 'checked="checked"' : '' : ''; ?>  />

                      <span class="lbl"> Ya</span>

                    </label>

                    <label>

                      <input name="cetak_kartu" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->is_active == 'N') ? 'checked="checked"' : '' : 'checked="checked"'; ?> />

                      <span class="lbl">Tidak</span>

                    </label>

                </div>

              </div>
            
            </div>

            <div class="form-group" id="form_sep" style="display:none">

            <label class="control-label col-sm-2">No Kartu BPJS</label>

              <div class="col-md-2">
                <input name="noKartuBpjs" id="noKartuBpjs" class="form-control" type="text" value="">
              </div>

              <label class="control-label col-md-2">Nomor SEP</label>            

               <div class="col-md-4">            

                 <div class="input-group">

                   <input name="noSep" id="noSep" class="form-control" type="text" placeholder="Masukan No SEP" readonly>

                   <span class="input-group-btn">

                     <button type="button" class="btn btn-primary btn-sm" onclick="showModalFormSep()">

                       <span class="ace-icon fa fa-file icon-on-right bigger-110"></span>

                       Buat SEP

                     </button>

                   </span>

                 </div>

               </div>   

            </div>

            <hr class="separator">

          </div>

          <div class="col-md-2">

            <div class="col-xs-12 col-sm-12">

              <div>

                <span class="profile-picture">

                  <img id="avatar" class="editable img-responsive editable-click editable-empty" style="width:300px" alt="" src="<?php echo base_url()?>assets/avatars/nopic.jpg">

                </span>

              </div>

            </div>

          </div>

      </form>

      <br>

      <p><b>RIWAYAT KUNJUNGAN PASIEN</b></p>

      <table id="riwayat-table" class="table table-bordered table-hover">

        <thead>

          <tr>  
            
            <th width="120px">No Registrasi</th>
            
            <th width="120px">Tanggal Masuk</th>
            
            <th width="120px">Tanggal Keluar</th>
            
            <th>Poli</th>
            
            <th width="150px">Dokter</th>
            
            <th width="180px">Penjamin</th>

            <th width="120px">Status pasien</th>

          </tr>

        </thead>

        <tbody id="table_riwayat_pasien">

        </tbody>

      </table>

    </div>

  </div><!-- /.col -->

</div><!-- /.row -->

<!-- modal -->

<div id="modalSearchPasien" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;width:80%">

    <div class="modal-content">

      <div class="modal-header no-padding">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text">Results for ""</span>

        </div>

      </div>

      <div class="modal-body no-padding">

        <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">

          <thead>

            <tr>

              <th>MR</th>

              <th>Nama Pasien</th>

              <th>TTL</th>

              <th>Umur</th>

              <th>Alamat</th>

              <th>Penjamin</th>

              <th>Action</th>

            </tr>

          </thead>

          <tbody id="result_pasien_data">


          </tbody>

        </table>

      </div>

      <div class="modal-footer no-margin-top">

        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

          <i class="ace-icon fa fa-times"></i>

          Close

        </button>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>

<!-- MODAL CREATE SEP -->

<div id="modalCreateSep" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:85%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_create_sep">Pembuatan SEP (Surat Eligibilatas Peserta)</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_create_sep_content"></div>

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
