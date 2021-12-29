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

          if(jsonResponse.type_pelayanan == 'Penunjang Medis' )
          {

            getMenuTabs('registration/reg_pasien/riwayat_kunjungan/'+jsonResponse.no_mr+'/'+$('#kode_bagian_val').val()+'', 'tabs_riwayat_kunjungan');

          }

          if(jsonResponse.type_pelayanan == 'Pasien Selesai' )
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

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/process');

    });

    $('#tabs_pesan_resep').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'farmasi/Farmasi_pesan_resep/process');

    });   

    $('#tabs_penunjang_medis').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'registration/Reg_pm/process');

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
            $('#nama_pasien').text(obj.nama_pasien);
            $('#nama_pasien_hidden').val(obj.nama_pasien);
            $('#jk').text(obj.jen_kelamin);
            $('#umur').text(umur_pasien+' Tahun');          
            $('#umur_saat_pelayanan_hidden').val(umur_pasien);
            $('#alamat').text(obj.almt_ttp_pasien);
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
            $("#tabs_form_pelayanan").load('pelayanan/Pl_pelayanan/tindakan/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Rajal&kode_bag='+$('#kode_bagian_val').val()+'');

          }            

    }); 

}

function selesaikanKunjungan(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processPelayananSelesai');
  $('#form_default_pelayanan').show('fast');
  $('#form_default_pelayanan').load('pelayanan/Pl_pelayanan/form_end_visit?mr='+noMr+'&id='+$('#id_pl_tc_poli').val()+'&no_kunjungan='+$('#no_kunjungan').val()+''); 

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
          $.achtung({message: jsonResponse.message, timeout:5});  
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
          $.achtung({message: jsonResponse.message, timeout:5});  
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
          <input type="hidden" value="" name="id_pl_tc_poli" id="id_pl_tc_poli" value="<?php echo ($id)?$id:''?>">
          <input type="hidden" name="nama_pasien_hidden" value="" id="nama_pasien_hidden">
          <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dokter_pemeriksa">
          <input type="hidden" name="no_registrasi" class="form-control" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" readonly>
          <input type="hidden" name="no_kunjungan" class="form-control" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan" readonly>
          <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>
          
          <!-- profile Pasien -->
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
                 <!--  <li class="list-group-item">
                    <div id="kode_perusahaan">Penjamin</div>
                  </li> -->
                </ul>

                <a href="#" id="btn_search_pasien" class="btn btn-inverse btn-block">Tampilkan Pasien</a>
              <!-- /.box-body -->
            </div>
          </div>

          <!-- form pelayanan -->
          <div class="col-md-10">

          <!-- end action form  -->
            <div class="pull-right" style="margin-bottom:1%">
              <?php if(empty($value->status_keluar)) :?>
              <a href="#" class="btn btn-xs btn-purple" onclick="perjanjian()"><i class="fa fa-calendar"></i> Perjanjian Pasien</a>
              <a href="#" class="btn btn-xs btn-primary" onclick="selesaikanKunjungan()"><i class="fa fa-check-circle"></i> Selesaikan Kunjungan</a>
              <a href="#" class="btn btn-xs btn-danger" onclick="cancel_visit(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>,<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>)"><i class="fa fa-times-circle"></i> Batalkan Kunjungan</a>
            <?php else: echo '<a href="#" class="btn btn-xs btn-success" onclick="getMenu('."'pelayanan/Pl_pelayanan'".')"><i class="fa fa-angle-double-left"></i> Kembali ke Daftar Pasien</a>'; endif;?>
            </div>
            <br>
          <!-- <p><b><i class="fa fa-edit"></i> DATA REGISTRASI DAN KUNJUNGAN </b></p> -->
            <table class="table table-bordered">
              <tr style="background-color:#f4ae11">
                <th>Kode</th>
                <th>No Reg</th>
                <th>Tanggal Daftar</th>
                <th>Dokter</th>
                <th>Penjamin</th>
                <th>Petugas</th>
              </tr>

              <tr>
                <td><?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?></td>
                <td><?php echo isset($value->no_registrasi)?$value->no_registrasi:''?></td>
                <td><?php echo isset($value->tgl_jam_poli)?$this->tanggal->formatDateTime($value->tgl_jam_poli):''?></td>
                <td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
                <td><?php echo isset($value->nama_kelompok)?ucwords($value->nama_kelompok).' / ':'';?>
                <?php echo isset($value->nama_perusahaan)?$value->nama_perusahaan:'';?></td>
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
            <input type="hidden" class="form-control" name="kode_dokter_poli" value="<?php echo isset($value->kode_dokter)?$value->kode_dokter:''?>">


            <!-- form default pelayanan pasien -->

            <p><b><i class="fa fa-edit"></i> DIAGNOSA DAN PEMERIKSAAN </b></p>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Anamnesa</label>
                <div class="col-sm-4">
                   <input type="text" class="form-control" name="pl_anamnesa" value="<?php echo isset($riwayat->anamnesa)?$riwayat->anamnesa:''?>">
                   <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Diagnosa <span style="color:red">(*)</span></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
                  <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Pemeriksaan</label>
                <div class="col-sm-4">
                   <input type="text" class="form-control" name="pl_pemeriksaan" value="<?php echo isset($riwayat->pemeriksaan)?$riwayat->pemeriksaan:''?>">
                </div>
                <label class="control-label col-sm-2" for="">Pengobatan</label>
                <div class="col-sm-4">
                   <input type="text" class="form-control" name="pl_pengobtan" value="<?php echo isset($riwayat->pengobatan)?$riwayat->pengobatan:''?>">
                </div>
            </div>

            <p><b><i class="fa fa-edit"></i> FORM PELAYANAN PASIEN </b></p>

            <div class="tabbable">  

              <ul class="nav nav-tabs" id="myTab">

                <li class="active">
                  <a data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>" data-url="pelayanan/Pl_pelayanan/tindakan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                    <i class="green ace-icon fa fa-history bigger-120"></i>
                    TINDAKAN & OBAT
                  </a>
                </li>

                <li>
                  <a data-toggle="tab" data-id="<?php echo $id?>" data-url="farmasi/Farmasi_pesan_resep/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>" id="tabs_pesan_resep" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
                    <i class="red ace-icon fa fa-list bigger-120"></i>
                    PEMESANAN RESEP
                  </a>
                </li>

                <li>
                  <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/Reg_pm/rujuk_pm/<?php echo $value->no_registrasi?>/<?php echo $value->kode_bagian?>" id="tabs_penunjang_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
                    <i class="orange ace-icon fa fa-globe bigger-120"></i>
                    PENUNJANG MEDIS
                  </a>
                </li>

                <li>
                  <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/perjanjian_rj/get_by_mr/<?php echo $value->no_mr?>" id="tabs_penunjang_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
                    <i class="purple ace-icon fa fa-history bigger-120"></i>
                    RIWAYAT PERJANJIAN
                  </a>
                </li>

                <li>
                  <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/reg_pasien/riwayat_transaksi/<?php echo $value->no_mr?>" id="tabs_billing_pasien" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
                    <i class="purple ace-icon fa fa-money bigger-120"></i>
                    BILLING PASIEN
                  </a>
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

        </form>
    </div>

</div><!-- /.row -->

<div id="GlobalModal" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:70%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_riwayat_medis">PERJANJIAN PASIEN</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_modal"></div>

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



