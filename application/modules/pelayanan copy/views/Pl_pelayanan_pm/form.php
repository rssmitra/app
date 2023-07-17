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
    $('#form_pelayanan').on('submit', function(){
      
      var formData = new FormData($('#form_pelayanan')[0]);
        
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
                achtungShowLoader();  
              },
              uploadProgress: function(event, position, total, percentComplete) {
              },
              complete: function(xhr) {     
                var data=xhr.responseText;        

                var jsonResponse = JSON.parse(data);        

                if(jsonResponse.status === 200){          

                  $.achtung({message: jsonResponse.message, timeout:5});     

                  if(jsonResponse.type_pelayanan!='isi_hasil'){

                    $("#page-area-content").load("pelayanan/Pl_pelayanan_pm?type_tujuan=<?php echo isset($value)?$value->kode_bagian_tujuan:0 ?>");
                  
                  }else{
                  
                    $('.hasil_pm').attr('readonly', true);
                    $('.keterangan_pm').attr('readonly', true);
                    $('#cetak_isi_hasil').show('fast');
                    $('#btn_submit_isihasil').hide('fast');
                  }
                  
                }else{          

                  $.achtung({message: jsonResponse.message, timeout:5});          

                }        

                achtungHideLoader(); 
              }
          });

      //but for now we will show it inside a modal box

      /*$('#modal-wysiwyg-editor').modal('show');
      $('#wysiwyg-editor-value').css({'width':'99%', 'height':'200px'}).val($('#editor').html());*/
      
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

    oTablePesanDiagnosa = $('#table-riwayat-diagnosa').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_pm/get_riwayat_diagnosa?no_kunjungan=<?php echo $no_kunjungan?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>",
          "type": "POST"
      },

    });

    /*onchange form module when click tabs*/
    $('#tabs_tindakan').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/process');

    });

    $('#tabs_diagnosa').click(function (e) {    
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveDiagnosa');
      // backToDefaultForm();
    });

    $('#tabs_isi_hasil').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_pm/process_isi_hasil');

    });   

    $('#tabs_penunjang_medis').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'registration/Reg_pm/process');

    });

    $('#tabs_klinik').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'registration/Reg_klinik/process');

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
          // if( data.count == 1 )     {

          //   var obj = data.result[0];

          //   var pending_data_pasien = data.pending; 
          //   var umur_pasien = hitung_usia(obj.tgl_lhr);
          //   console.log(pending_data_pasien);
          //   console.log(hitung_usia(obj.tgl_lhr));

          //   $('#no_mr').text(obj.no_mr);
          //   $('#noMrHidden').val(obj.no_mr);
          //   $('#no_ktp').text(obj.no_ktp);
          //   $('#nama_pasien').text(obj.nama_pasien);
          //   $('#nama_pasien_hidden').val(obj.nama_pasien);
          //   $('#jk').text(obj.jen_kelamin);
          //   $('#umur').text(umur_pasien+' Tahun');          
          //   $('#umur_saat_pelayanan_hidden').val(umur_pasien);
          //   $('#alamat').text(obj.almt_ttp_pasien);
          //   $('#noKartuBpjs').val(obj.no_kartu_bpjs);

          //   penjamin = (obj.nama_perusahaan==null)?'-':obj.nama_perusahaan;
          //   kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;
          //   $('#kode_perusahaan').text(kelompok+' '+obj.nama_perusahaan);



          //   if( obj.url_foto_pasien ){

          //     $('#avatar').attr('src', '<?php echo base_url()?>uploaded/images/photo/'+obj.url_foto_pasien+'');

          //   }else{

          //     if( obj.jen_kelamin == 'L' ){

          //       $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');

          //     }else{

          //       $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');

          //     }

          //   }

          //   /*default show tabs*/
          //   //$("#tabs_form_pelayanan").load('pelayanan/Pl_pelayanan/tindakan/<?php //echo $id?>/<?php //echo $no_kunjungan?>?type=PM&cito=<?php //echo isset($value)?$value->status_cito:''?>&kode_bag=<?php //echo isset($value)?$value->kode_bagian_tujuan:''?>');

          // }   

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
            $('#tabs_riwayat_kunjungan_id').attr('data-id', obj.no_mr);
            $('#tabs_riwayat_transaksi_id').attr('data-id', obj.no_mr);
            $('#tabs_riwayat_perjanjian_id').attr('data-id', obj.no_mr);
            $('#tabs_riwayat_booking_online_id').attr('data-id', obj.no_mr);

            $("#myTab li").removeClass("active");
            $("#tabs_detail_pasien").html("<div class='alert alert-block alert-success center'><p><strong><i class='ace-icon fa fa-glass bigger-150'></i><br>Selamat Datang!</strong><br>Untuk melihat Riwayat Kunjungan Pasien dan Transaksi Pasien, Silahkan cari pasien terlebih dahulu !</p></div>");

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


          }         

    }); 

}

function selesaikanKunjungan(){

  noMr = $('#noMrHidden').val();
  kode_bagian = '<?php echo isset($value)?$value->kode_bagian_tujuan:0?>';
  preventDefault();  
  
  if(confirm('Selesaikan Pasien?')){
    $.ajax({
        url: "pelayanan/Pl_pelayanan_pm/processPelayananSelesai",
        data: $('#form_pelayanan').serialize(),            
        dataType: "json",
        type: "POST",
        success: function (response) {
          /*reset table*/
          reset_table();
          if(response.status==200) {
            $.achtung({message: response.message, timeout:5});
            if(kode_bagian=='050301'){
              $("#page-area-content").load("pelayanan/Pl_pelayanan_pm?type_tujuan="+kode_bagian+"")
            }else{
              $('#after_pasien_selesai').show('fast');
              $('#btn_pasien_selesai').hide('fast');
              $('#btn_add_tindakan').hide('fast');
              $('#btn_add_tindakan_luar').hide('fast');

            }
            //$("#page-area-content").load("pelayanan/Pl_pelayanan_pm?type_tujuan=<?php //echo isset($value)?$value->kode_bagian_tujuan:0 ?>&step=to_pemeriksaan");
           
          }else{
            $.achtung({message: response.message, timeout:5});
          }
          
        }
    }); 
  }else{
    return false;
  }
  

}

function backToDefaultForm(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processPelayananSelesai');
  $('#form_default_pelayanan').hide('fast');
  $('#form_default_pelayanan').html(''); 

}

function rollback(kode_penunjang){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan_pm/rollback",
      data: { kode_penunjang: kode_penunjang},            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          getMenu('pelayanan/Pl_pelayanan_pm?type_tujuan=<?php echo isset($value)?$value->kode_bagian_tujuan:0 ?>');
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

}

function cancel_visit(){

preventDefault();  
achtungShowLoader();
if(confirm('Are you sure?')){
  $.ajax({
      url: "pelayanan/Pl_pelayanan/cancel_visit",
      data: { no_registrasi: $('#no_registrasi').val(), no_kunjungan: $('#no_kunjungan').val(), kode_bag: $('#kode_bagian_val').val(), kode_penunjang: $('#kode_penunjang').val() },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          getMenu('pelayanan/Pl_pelayanan_pm?type_tujuan='+$('#kode_bagian_val').val()+'');
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5});  
        } 
        achtungHideLoader();
      }
  });
}else{
  return false;
}


}

function cetak_slip() {
  
  noMr = $('#noMrHidden').val();
  url = 'pelayanan/Pl_pelayanan_pm/slip?kode_penunjang=<?php echo $id?>';
  title = 'Cetak Slip';
  width = 470;
  height = 600;
  PopupCenter(url, title, width, height); 

}

function perjanjian_pasien_pm(){
  $.getJSON("registration/reg_klinik/search_pasien?keyword=" + $('#noMrHidden').val(), '', function (data) { 
    var obj = data.result[0];
    $('#nama_pasien').val(obj.nama_pasien);    
    show_modal('registration/Input_perjanjian_pm/form?no_kunjungan='+$('#no_kunjungan').val()+'&no_mr='+$('#noMrHidden').val()+'', 'PERJANJIAN PASIEN RADIOLOGI');
  })
  

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
          <input type="hidden" class="form-control" name="no_kunjungan" value="<?php echo isset($value)?$value->no_kunjungan:''?>" id="no_kunjungan">
          <input type="hidden" class="form-control" name="no_registrasi" id="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
          <input type="hidden" class="form-control" name="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
          <input type="hidden" class="form-control" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>">
          <input type="hidden" class="form-control" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
          <input type="hidden" class="form-control" name="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien:''?>">
          <input type="hidden" class="form-control" name="kode_bagian_asal" value="<?php echo isset($value)?$value->kode_bagian_asal:''?>">
          <input type="hidden" class="form-control" name="kode_bagian" value="<?php echo isset($value)?$value->kode_bagian_tujuan:''?>" id="kode_bagian_val">
          <input type="hidden" class="form-control" name="kode_klas" value="<?php echo isset($kode_klas)?$kode_klas:''?>"  id="kode_klas_val">
          <input type="hidden" value="" name="noMrHidden" id="noMrHidden">
          <input type="hidden" name="kode_penunjang" id="kode_penunjang" value="<?php echo ($id)?$id:''?>">
          <input type="hidden" name="nama_pasien_hidden" id="nama_pasien_hidden">
          <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>
          
          <?php if($value->kode_bagian_tujuan=='050101') :?>
            <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'Arief Indra Sanjaya,dr. Sp PK';?>" id="dokter_pemeriksa">
          <?php else: ?>
            <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dokter_pemeriksa">
          <?php endif; ?>

          <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:0?>">
          <input type="hidden" class="form-control" name="flag_mcu" id="flag_mcu" value="<?php echo isset($value->flag_mcu)?$value->flag_mcu:0?>">
          
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

            <!-- end action form  -->
            <div class="pull-right" style="margin-bottom:3px; width: 100%">

              <a href="#" onclick="perjanjian_pasien_pm()" class="btn btn-xs btn-purple"><i class="menu-icon fa fa-calendar"></i><span class="menu-text"> Perjanjian Pasien </span></a>

              <?php if($value->status_daftar==0) :?>
                  <a href="#" class="btn btn-xs btn-primary" id="btn_pasien_selesai" onclick="selesaikanKunjungan()" ><i class="fa fa-home"></i> Pasien Selesai</a>
                  <a href="#" class="btn btn-xs btn-danger" id="btn_pasien_batal" onclick="cancel_visit()" ><i class="fa fa-times-circle"></i> Batalkan Kunjungan</a>
              <?php else: 
                switch ($status) {
                  case 'belum_ditindak':
                    $step = 'pelayanan/Pl_pelayanan_pm?type_tujuan='.$value->kode_bagian_tujuan.' ';
                    break;

                  case 'belum_diperiksa':
                    $step = 'pelayanan/Pl_pelayanan_pm?type_tujuan='.$value->kode_bagian_tujuan.'&step=to_pemeriksaan';
                    break;

                  case 'belum_bayar':
                    $step = 'pelayanan/Pl_pelayanan_pm?type_tujuan='.$value->kode_bagian_tujuan.'&step=to_pemeriksaan';
                    break;
                  
                  case 'belum_isi_hasil':
                    $step = 'pelayanan/Pl_pelayanan_pm?type_tujuan='.$value->kode_bagian_tujuan.'&step=to_isihasil';
                    break;

                  default:
                    $step = 'pelayanan/Pl_pelayanan_pm?type_tujuan='.$value->kode_bagian_tujuan.' ';
                    break;
                }

                ?>
                <a href="#" class="btn btn-xs btn-success" onclick="getMenu('<?php echo $step ?>')"><i class="fa fa-angle-double-left"></i> Kembali ke Daftar Pasien</a>
                <?php if($transaksi!=0):?><a href="#" class="btn btn-xs btn-danger" onclick="rollback(<?php echo $id ?>)"><i class="fa fa-times-circle"></i> Rollback</a><?php endif ?>
              <?php endif;?>

              <a id="after_pasien_selesai" style="display:none" href="#" class="btn btn-xs btn-success" onclick="getMenu('pelayanan/Pl_pelayanan_pm?type_tujuan=<?php echo $value->kode_bagian_tujuan?>&step=to_pemeriksaan')"><i class="fa fa-angle-double-left"></i> Daftar Periksa</a>
                <a href="#" class="btn btn-xs btn-info" onclick="cetak_slip()" ><i class="fa fa-money"></i> Charge Slip</a>

            </div>
            <br>
            <!-- informasi pendaftaran pasien -->
            <table class="table table-bordered">
              <tr style="background-color:#f4ae11">
                <th>Kode</th>
                <th>No Reg</th>
                <th>Tanggal Daftar</th>
                <th>Bagian Asal</th>
                <th>Penjamin</th>
                <th>Petugas</th>
                <th>Kontrol Kembali</th>
              </tr>

              <tr>
                <td><?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?></td>
                <td><?php echo isset($value->no_registrasi)?$value->no_registrasi:''?></td>
                <td><?php echo isset($value->tgl_masuk)?$this->tanggal->formatDateTime($value->tgl_masuk):''?></td>
                <td><?php echo isset($value->nama_bagian)?$value->nama_bagian:'';?></td>
                <td><?php echo isset($value->nama_kelompok)?ucwords($value->nama_kelompok).' / ':'';?>
                <?php echo isset($value->nama_perusahaan)?$value->nama_perusahaan:'';?></td>
                <td><?php echo $this->session->userdata('user')->fullname?></td>
                <td><?php echo isset($tgl_kontrol) ? $this->tanggal->formatDatedmY($tgl_kontrol) : '-'; ?></td>
              </tr>

            </table>            

            <div id="form_default_pelayanan" style="background-color:#77dcd373"></div>

            <p><b><i class="fa fa-edit"></i> FORM PELAYANAN PASIEN </b></p>

            <div class="tabbable">  

              <ul class="nav nav-tabs" id="myTab">
                <?php if($status=='belum_ditindak'): ?>
                  <li>
                    <a data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo $no_kunjungan?>?type=PM&cito=<?php echo isset($value)?$value->status_cito:''?>&kode_bag=<?php echo isset($value)?$value->kode_bagian_tujuan:''?>" data-url="pelayanan/Pl_pelayanan_pm/tindakan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      <i class="green ace-icon fa fa-history bigger-120"></i>
                      TINDAKAN
                    </a>
                  </li>
                <?php endif ?>

                <li>
                    <a data-toggle="tab" id="tabs_diagnosa" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian_tujuan:''?>" data-url="pelayanan/Pl_pelayanan_pm/diagnosa/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                    <i class="red ace-icon fa fa-stethoscope bigger-120"></i>
                    DIAGNOSA
                  </a>
                </li>

                <?php if($status=='belum_isi_hasil'): ?>
                  <li>
                    <a data-toggle="tab" data-id="<?php echo $id?>?mr=<?php echo isset($value)?$value->no_mr:0; echo ($value->flag_mcu==1)?'&is_mcu=2':'' ?>" data-url="pelayanan/Pl_pelayanan_pm/form_isi_hasil/<?php echo $value->no_kunjungan?>/<?php echo isset($value)?$value->kode_bagian_tujuan:''?>" id="tabs_isi_hasil" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
                      <i class="red ace-icon fa fa-file bigger-120"></i>
                      ISI HASIL PEMERIKSAAN LAB
                    </a>
                  </li>
                <?php endif ?>

                  <li>
                    <a data-toggle="tab" data-id="<?php echo $id?>" data-url="billing/Billing/getDetail/<?php echo $value->no_registrasi?>/<?php echo $type_asal ?>" id="tabs_billing_pasien" href="#" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
                      <i class="orange ace-icon fa fa-money bigger-120"></i>
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
                        Untuk memulai pelayanan, Silahkan Tampilkan Pasien terlebih dahulu !
                      </p>
                    </div>
                </div>
                
              </div>

            </div>

          </div>

        </form>
    </div>

</div><!-- /.row -->




