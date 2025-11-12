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
    getMenuTabs('pelayanan/Pl_pelayanan_vk/tindakan/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Rajal&kode_bag=030501', 'tabs_form_pelayanan');

    // show ews indikator
    $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_ews_dt') ?>", {no_kunjungan: $('#no_kunjungan').val()} , function (response) {    
        // show data
        var obj = response.result;
        // set value input
        var ews_ttl = response.ews_ttl;
        $('#score_ews_indikator').html('');
        $.each(ews_ttl, function(key, val) {
          if(val != ''){
            if(val == 0){
              clr_ind = 'success';
              color = 'green';
            }else if(val >=1 && val <=4){
              clr_ind = 'yellow';
              color = 'yellow';
            }else if(val >=5 && val <=6){
              clr_ind = 'warning';
              color = 'orange';
            }else{
              clr_ind = 'danger';
              color = 'red';
            }
            // append to 
            $('<a class="btn btn-xs btn-'+clr_ind+'" style="font-weight: bold; "> '+val+' </a> &nbsp; &nbsp;').appendTo($('#score_ews_indikator'));

            $('#list_group_'+$('#no_mr').val()+'').css('background', color).css('font-weight', 'bold');
          }
      });

    }); 


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
    
    oTablePesanDiagnosa = $('#table-riwayat-diagnosa').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_riwayat_diagnosa?no_kunjungan=<?php echo $no_kunjungan?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>",
          "type": "POST"
      },

    });

    $('#pl_diagnosa').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getICD10",
                data: 'keyword=' + query,            
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
          var label_item=item.split(':')[1];
          var val_item=item.split(':')[0];
          $('#pl_diagnosa').val(label_item);
          $('#pl_diagnosa_hidden').val(val_item);
        }

    });

    $('#pl_diagnosa_awal').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "ws_bpjs/Ws_index/getRef?ref=refDiagnosa",
                data: 'keyword=' + query,            
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
          var label_item=item.split(':')[1];
          var val_item=item.split(':')[0];
          $('#pl_diagnosa_awal').val(label_item);
        }

    });

    $('#btn_add_diagnosa').click(function (e) {   
      e.preventDefault();

      if( $('#pl_diagnosa_awal').val() == '' ){
        alert('Silahkan isi Diagnosa Awal !'); return false;
      }else{
        if( $('#pl_diagnosa').val() == '' ){
          alert('Silahkan isi Diagnosa Akhir !'); return false;
        }
      }

      /*process add pesan ok*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan_ri/process_add_diagnosa",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
            $('#btn_submit_diagnosa').hide('fast');
            $('#pl_diagnosa').attr('readonly', true);
            $('#pl_diagnosa_awal').attr('readonly', true);
            oTablePesanDiagnosa.ajax.url('pelayanan/Pl_pelayanan_ri/get_riwayat_diagnosa?no_kunjungan=<?php echo $no_kunjungan?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>').load();
            }else{
              alert('Silahkan cari pasien !'); return false;
            }
            
          }
      });

    });

    /*onchange form module when click tabs*/
    $('#btn_monitoring_perkembangan_pasien, #btn_form_pengawasan_khusus, #btn_observasi_harian_keperawatan').click(function (e) {     
      e.preventDefault();  
      $("#tabs_modules_pelayanan_ri li").removeClass("active");
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_monitoring');
    });

    $('#btn_form_pemberian_obat').click(function (e) {     
      e.preventDefault();  
      $("#tabs_modules_pelayanan_ri li").removeClass("active");
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_pemberian_obat');
    });

    $('#btn_form_askep').click(function (e) {     
      e.preventDefault();  
      $("#tabs_modules_pelayanan_ri li").removeClass("active");
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_askep');
    });

    $('#btn_ews').click(function (e) {     
      e.preventDefault();  
      $("#tabs_modules_pelayanan_ri li").removeClass("active");
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_ews');
    });

    $('#btn_note').click(function (e) {     
      e.preventDefault();  
      $("#tabs_modules_pelayanan_ri li").removeClass("active");
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_note');
    });

    $('#tabs_cppt, #tabs_catatan, #btn_note, #btn_ews, #btn_form_askep, #btn_form_pemberian_obat, #btn_monitoring_perkembangan_pasien, #btn_form_pengawasan_khusus, #btn_observasi_harian_keperawatan ').click(function (e) {    
      e.preventDefault();  
      $('#form_kelas_tarif').hide();
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

    $('#tabs_cppt').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_cppt');
      $('#form_default_pelayanan').hide('fast');
      $('#form_default_pelayanan').html(''); 

    });

     $('#tabs_catatan').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveCatatanPengkajian');
      $('#form_default_pelayanan').hide('fast');
      $('#form_default_pelayanan').html(''); 

    });

    $('#tabs_klinik').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'registration/Reg_klinik/process');
      $('#form_default_pelayanan').hide('fast');
      $('#form_default_pelayanan').html(''); 

    });

    $('#tabs_billing_pasien').click(function (e) {     
      
      e.preventDefault();  

      getBillingDetail(<?php echo $value->no_registrasi?>,'RI','bill_kamar_perawatan');

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

function edit_diagnosa() {
  $('#btn_submit_diagnosa').show('fast');
  $('#btn_hide_submit_diagnosa').show('fast');
  $('#pl_diagnosa').attr('readonly', false);
  $('#pl_diagnosa_awal').attr('readonly', false);
}

function UnEditDiagnosa() {
  $('#btn_submit_diagnosa').hide('fast');
  $('#btn_hide_submit_diagnosa').hide('fast');
  $('#pl_diagnosa').attr('readonly', true);
  $('#pl_diagnosa_awal').attr('readonly', true);
}

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

function delete_diagnosa(myid){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan/delete_diagnosa',
        type: "post",
        data: {ID:myid},
        dataType: "json",
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
            oTablePesanDiagnosa.ajax.url('pelayanan/Pl_pelayanan_ri/get_riwayat_diagnosa?no_kunjungan=<?php echo $no_kunjungan?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>').load();
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

function reload_page(){
  getMenu('pelayanan/Pl_pelayanan_vk/form/'+$('#id_pasien_vk').val()+'/'+$('#no_kunjungan').val()+'')
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

    <!-- div.dataTables_borderWrap -->

    <div style="margin-top:0px">   
  
        
          <!-- hidden form -->
          <input type="hidden" name="noMrHidden" id="noMrHidden" value="<?php echo isset($value)?$value->no_mr:''?>">
          <input type="hidden" name="id_pasien_vk" id="id_pasien_vk" value="<?php echo ($id)?$id:''?>">
          <input type="hidden" name="nama_pasien_hidden" value="<?php echo isset($value)?$value->nama_pasien:''?>" id="nama_pasien_hidden">
          <input type="hidden" name="kode_dokter_vk" value="<?php echo isset($value->dr_merawat)?$value->dr_merawat:'';?>" id="kode_dokter_vk">
          <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dokter_pemeriksa">
          <input type="hidden" name="no_registrasi" class="form-control" id="no_registrasi" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" readonly>
          <input type="hidden" name="no_kunjungan" class="form-control" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan" readonly>
          <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>
          <input type="hidden" name="kode_ri" id="kode_ri" value="<?php echo (isset($value->kode_ri)?$value->kode_ri:'');?>">
          <input type="hidden" class="form-control" name="kode_kelompok" id="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
          <input type="hidden" class="form-control" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>" id="kode_perusahaan_val">
          <input type="hidden" class="form-control" name="no_mr" id="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
          <input type="hidden" class="form-control" name="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien:''?>">
          <input type="hidden" class="form-control" name="umur_saat_pelayanan_hidden" id="umur_saat_pelayanan_hidden">
          <input type="hidden" class="form-control" name="kode_bagian_asal" id="kode_bagian_asal" value="<?php echo isset($value)?$value->bag_pas:''?>">
          <input type="hidden" class="form-control" name="kode_bagian" value="030501" id="kode_bagian_val">
          <!-- <input type="hidden" class="form-control" name="kode_klas" value="<?php echo isset($kode_klas)?$kode_klas:''?>" id="kode_klas_val"> -->
          <input type="hidden" class="form-control" name="dr_merawat" id="dr_merawat" value="<?php echo isset($value->dr_merawat)?$value->dr_merawat:''?>">


          <!-- form pelayanan -->
          <div class="col-md-12">

            <!-- end action form  -->
            <div class="pull-left" style="margin-bottom:5px; padding-top: 10px">
              <?php if(empty($value->tgl_keluar_vk)) :?>
              <a href="#" class="btn btn-xs btn-primary" id="btn_selesai_igd" onclick="selesaikanKunjungan()"><i class="fa fa-check-circle"></i> Selesaikan Kunjungan</a>
              <a href="#" class="btn btn-xs btn-danger" onclick="cancel_visit(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>,<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>)"><i class="fa fa-times-circle"></i> Batalkan Kunjungan</a>
              <?php else: echo '<a href="#" class="btn btn-xs btn-success" onclick="getMenu('."'pelayanan/Pl_pelayanan_vk'".')"><i class="fa fa-angle-double-left"></i> Kembali ke Daftar Pasien</a> <a href="#" class="btn btn-xs btn-danger" onclick="rollback('.$value->no_registrasi.', '.$value->no_kunjungan.', '.$flag_rollback.')"><i class="fa fa-undo"></i> Rollback Status </a>'; endif;?>
              <a href="#" class="btn btn-xs btn-danger" id="btn_cetak_meninggal" onclick="cetak_surat_kematian(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>)" <?php echo isset($meninggal)?'':'style="display:none"' ?> ><i class="fa fa-file"></i> Cetak Surat Kematian</a>
              <a href="#" class="btn btn-xs btn-danger" id="cetak_keracunan" onclick="cetak_surat_keracunan()" <?php echo isset($keracunan->id_cetak_racun)?'':'style="display:none"'?>><i class="fa fa-file"></i> Cetak Surat Keracunan </a>
            </div>
            
            <!-- <p><b><i class="fa fa-edit"></i> DATA REGISTRASI DAN KUNJUNGAN </b></p> -->
            <table class="table table-bordered">
              <tr style="background-color:#f4ae11">
                <th width="120px">No Kunjungan</th>
                <th width="120px">No Registrasi</th>
                <th>Tanggal Daftar</th>
                <th>Dokter</th>
                <th>Penjamin</th>
                <th>Ruang/Kelas</th>
                <th>Petugas</th>
                <th></th>
              </tr>

              <tr>
                <td><a href="#" style="font-weight: bold; color: blue" onclick="getMenu('pelayanan/Pl_pelayanan_vk/form/<?php echo $id?>/<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>')" ><?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?></td>
                <td><?php echo isset($value->no_registrasi)?$value->no_registrasi:''?></td>
                <td><?php echo isset($value->tgl_masuk)?$this->tanggal->formatDateTime($value->tgl_masuk):''?></td>
                <td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
                <td>
                  <?php echo isset($value->nama_kelompok)?ucwords($value->nama_kelompok).' / ':'';?>
                  <?php echo isset($value->nama_perusahaan)?$value->nama_perusahaan:'';?>
                </td>
                <td><?php echo isset($value->nama_bagian)?$value->nama_bagian:'';?>/ <?php echo isset($value->nama_klas)?$value->nama_klas:'';?></td>
                <td><?php echo isset($value->fullname)?$value->fullname:$this->session->userdata('user')->fullname?></td>
                <td align="center"><a href="#" onclick="reload_page()"><i class="fa fa-refresh green bigger-120"></i></a></td>
              </tr>

            </table>
            
            <?php if(isset($value) AND $value->status_batal==1) :?>
              <span style="margin-left:-19%;position:absolute;transform: rotate(-25deg) !important; margin-top: 21%" class="stamp is-nope-2">Batal</span>
            <?php else:?>
              <?php if(isset($value) AND $value->tgl_keluar_vk!=NULL) :?>
              <span style="margin-left:-19%;position:absolute;transform: rotate(-25deg) !important; margin-top: 21%" class="stamp is-approved">Selesai</span>
              <?php endif;?>  
            <?php endif;?>

            
            <div style="margin-top:10px; margin-bottom: 10px">
              <div class="col-md-12 no-padding">

                  <div class="btn-group dropdown">
                    <button class="btn btn-xs btn-primary" type="button">Early Warning System</button>
                    <button data-toggle="dropdown" class="btn btn-xs btn-primary dropdown-toggle">
                      <span class="ace-icon fa fa-caret-down icon-only"></span>
                    </button>

                    <ul class="dropdown-menu dropdown-primary">
                      <li>
                        <a href="#" id="btn_ews" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/ews/<?php echo $value->kode_ri?>/<?php echo $no_kunjungan?>?type=Ranap&type_form=dewasa&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')">Dewasa</a>
                      </li>

                      <li>
                        <a href="#" id="btn_ews" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/ews/<?php echo $value->kode_ri?>/<?php echo $no_kunjungan?>?type=Ranap&type_form=anak&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')">Anak</a>
                      </li>

                      <li>
                        <a href="#" id="btn_ews" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/ews/<?php echo $value->kode_ri?>/<?php echo $no_kunjungan?>?type=Ranap&type_form=kebidanan&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')">Kebidanan</a>
                      </li>
                    </ul>
                  </div>

                  <a href="#" class="btn btn-xs btn-primary" id="btn_observasi_harian_keperawatan" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/observasi_harian_keperawatan/<?php echo $value->kode_ri?>/<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>&tipe_monitoring=UMUM', 'tabs_form_pelayanan')" >Observasi Harian Keperawatan</a>

                  <a href="#" class="btn btn-xs btn-primary" id="btn_form_pemberian_obat"  onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/pemberian_obat/<?php echo $value->kode_ri?>/<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')" >Rencana Pelaksanaan Pemberian Obat</a>

                  <a href="#" class="btn btn-xs btn-primary" id="btn_form_askep" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/askep/<?php echo $value->kode_ri?>/<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')" >Catatan Asuhan Keperawatan</a>
                  

                  <a href="#" class="btn btn-xs btn-primary" id="btn_note" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/note/<?php echo $value->kode_ri?>/<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')">
                  Catatan Visual Gambar
                  </a>
                  <?php if($value->status_pulang==0) :?>
                    <a href="#" class="btn btn-xs btn-primary" onclick="selesaikanKunjungan()" >Pulangkan Pasien</a>
                  <?php else: ?>
                    <a href="#" class="btn btn-xs btn-primary" onclick="selesaikanKunjungan()" >Resume Medis Pasien Pulang</a>
                    <?php if($transaksi!=0):?><a href="#" class="btn btn-xs btn-danger" onclick="rollback(<?php echo isset($value)?$value->no_registrasi:'' ?>,<?php echo isset($value)?$value->no_kunjungan:''?>)"> Kembalikan ke Ruang Rawat Inap</a><?php else: echo '<a href="#" class="btn btn-xs btn-success"><i class="fa fa-check bigger-120"></i> Lunas</a>'; endif ?>
                  <?php endif;?>

                  <div class="pull-right">
                    <table>
                      <tr>
                        <td><b>SCORE EWS :</b> </td>
                        <td> <div id="score_ews_indikator">-</div></td>
                      </tr>
                    </table>
                  </div>

                </div>

                
              </div>
            </div>

            <hr>

            <div class="col-md-12" style="margin-top:10px;">
              <div class="tabbable" >  

                <ul class="nav nav-tabs" id="tabs_modules_pelayanan_ri">

                  <li>
                    <a data-toggle="tab" id="tabs_cppt" href="#" data-id="<?php echo $no_kunjungan?>?type=Ranap&kode_bag=030501" data-url="pelayanan/Pl_pelayanan_ri/cppt/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      <?php echo INPUT_CPPT?>
                    </a>
                  </li>

                  <li>
                    <a data-toggle="tab" id="tabs_bayi" href="#" data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=030501&no_mr_ibu=<?php echo $no_mr?>" data-url="pelayanan/Pl_pelayanan_vk/form_bayi/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      Data Bayi Lahir
                    </a>
                  </li>

                  <li>
                    <a data-toggle="tab" id="tabs_catatan" href="#" data-id="<?php echo $no_kunjungan?>?type=Ranap&no_mr=<?php echo $no_mr?>" data-url="pelayanan/Pl_pelayanan/catatan_lainnya/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      <?php echo FRM_PENGKAJIAN?>
                    </a>
                  </li>

                  <li>
                    <a data-toggle="tab" id="tabs_cppt" href="#" data-id="<?php echo $no_kunjungan?>?type=Ranap&kode_bag=030501" data-url="pelayanan/Pl_pelayanan_ri/riwayat_medis/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      <?php echo RIWAYAT_MEDIS?>
                    </a>
                  </li>

                  <li>
                    <a data-toggle="tab" href="#rm_tabs" data-url="templates/References/get_riwayat_pm/<?php echo $value->no_mr?>" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')" title="Riwayat Penunjang Medis">
                        Hasil Penunjang
                      </a>
                  </li>

                  <li>
                    <a data-toggle="tab" data-id="<?php echo $id?>" data-url="farmasi/Farmasi_pesan_resep/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>" id="tabs_pesan_resep" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id')+'?kode_bag=030501', 'tabs_form_pelayanan')" >
                      <?php echo ERESEP; ?>
                    </a>
                  </li>

                  <li>
                    <a data-toggle="tab" id="tab_obat_bhp" href="#" data-id="<?php echo $no_kunjungan?>?type=<?php echo $type?>&kode_bag=<?php echo isset($kode_bagian)?$kode_bagian:''?>" data-url="pelayanan/Pl_pelayanan_ri/obat_bhp/<?php echo $value->kode_ri?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                      Input BHP
                    </a>
                  </li>

                  <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="true">
                    Rujuk Internal &nbsp;
                      <i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-info">
                      <li>
                        <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/Reg_klinik/rujuk_klinik/<?php echo $value->no_registrasi?>/030501/ranap/<?php echo $kode_klas?>" id="tabs_klinik" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
                          Rujuk ke Klinik
                        </a>
                      </li>
                      <li>
                        <a data-toggle="tab" data-id="<?php echo $value->kode_ri?>" data-url="pelayanan/Pl_pelayanan_ri/pesan/<?php echo $value->kode_ri?>/<?php echo $value->no_registrasi?>" id="tabs_pesan" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
                          (Kamar Bedah/ VK/ Pindah Ruangan)
                        </a>
                      </li>
                      <li>
                        <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/Reg_pm/rujuk_pm/<?php echo $value->no_registrasi?>/030501/<?php echo $kode_klas?>/ranap" id="tabs_penunjang_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
                          <?php echo EORDER?>
                        </a>
                      </li>
                    </ul>
                  </li>

                  

                  <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="true">
                    Billing Pasien &nbsp;
                      <i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-info">
                      <li>
                        <a data-toggle="tab" id="tabs_tindakan" href="dropdown1" data-id="<?php echo $no_kunjungan?>?type=Ranap&kode_bag=030501" data-url="pelayanan/Pl_pelayanan_vk/tindakan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">Input Tarif Tindakan</a>
                      </li>
                      <li>
                        <a data-toggle="tab" data-id="<?php echo $id?>" data-url="billing/Billing/getDetail/<?php echo $value->no_registrasi?>/RI" id="tabs_billing_pasien" href="#dropdown2" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
                          Resume Billing Pasien
                        </a>
                      </li>
                    </ul>
                  </li>
                

                </ul>

                <div class="tab-content">

                  <div class="row">

                    <div class="col-md-12" style="padding-bottom: 5px !important; display: none" id="form_kelas_tarif">
                      <label style="font-weigth: bold !important"><b>Kelas Tarif :</b> </label><br>
                      <?php echo $this->master->custom_selection($params = array('table' => 'mt_klas', 'id' => 'kode_klas', 'name' => 'nama_klas', 'where' => array('is_active' => 1)), isset($kode_klas)?$kode_klas:$klas_titipan , 'kode_klas', 'kode_klas_val', 'form-control', '', '') ?>
                    </div>

                    <div id="tabs_form_pelayanan" style="padding: 10px !important">
                      <div class="alert alert-block alert-success">
                          <p class="center">
                            <strong style="font-size: 16px">LEMBAR KERJA PELAYANAN PASIEN RAWAT INAP</strong> 
                            <br>
                            Silahkan klik pada Tab diatas untuk mengisi form yang sesuai!.
                          </p>
                        </div>
                    </div>
                  </div>
                  
                </div>

              </div>
            </div>

          </div>

    </div>

</div><!-- /.row -->

