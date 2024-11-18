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

    var reload_by_id = $('select[name="select_racikan"]').val();

    if( reload_by_id != '#' || reload_by_id != '0'){
      show_selected_item_racikan( reload_by_id );
    }

    table_detail_racikan = $('#temp_data_racikan').DataTable( {
        "processing": true, 
        "serverSide": true,
        "bInfo": false,
        "bPaginate": false,
        "searching": false,
        "bSort": false,
        "ajax": {
            "url": "farmasi/Entry_resep_racikan/get_data?id="+reload_by_id,
            "type": "POST"
        }
    }); 
    

    $('#inputKeyObatRacikan').focus();    

    $('#form_entry_resep_racikan').ajaxForm({      

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

          /*show detail tarif html*/
          reset_search_obat();
          reload_table_racikan();
          /*reload select racikan*/
          reload_item_racikan( jsonResponse.data.id_tc_far_racikan );
          show_selected_item_racikan(jsonResponse.data.id_tc_far_racikan);
          $('#kode_trans_far_racikan').val(jsonResponse.kode_trans_far);
          $('#kode_trans_far').val(jsonResponse.kode_trans_far);
          $('#inputKeyObatRacikan').focus();

        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }    
          reload_table();
          achtungHideLoader();
          sum_total_biaya_farmasi();
      }      

    });     

    $('select[name="select_racikan"]').change(function () {      


        if( $(this).val() == '' ){
          
          renew_form();

        }else{

          show_selected_item_racikan($(this).val());
          $('#id_tc_far_racikan').val($(this).val());

        }
         
    }); 

    $('#inputKeyObatRacikan').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getObatByBagianAutoComplete",
              data: { keyword:query, bag: '060101', urgensi: $('input[name="urgensi"]:checked').val() },            
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
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#inputKeyObatRacikan').val(label_item);
        var detailObat = getDetailObatByKodeBrgRacikan(val_item,'060101');
        $('#jumlah_pesan_racikan').focus();
      }
    });

    $( "#nama_racikan" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#jml_racikan').focus();
          }
          return false;       
        }

    });

    $( "#jml_racikan" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#dosis_start_r').focus();
          }
          return false;       
        }
    });

    // $( "#jumlah_obat_23_r" )
    //   .keypress(function(event) {
    //     var keycode =(event.keyCode?event.keyCode:event.which); 
    //     if(keycode ==13){
    //       event.preventDefault();
    //       if($(this).valid()){
    //         $('#dosis_start_r').focus();
    //       }
    //       return false;       
    //     }
    // });

    $( "#dosis_start_r" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#dosis_end_r').focus();
          }
          return false;       
        }
    });

    $( "#dosis_end_r" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#anjuran_pakai_r').focus();
          }
          return false;       
        }
    });

    $( "#anjuran_pakai_r" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#catatan_r').focus();
          }
          return false;       
        }
    });

    $( "#catatan_r" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#btn_submit_racikan').click();
          }
          return false;       
        }
    });

    $( "#jumlah_pesan_racikan" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#btn_submit_obat').click();
          }
          return false;       
        }

    });

    $( "#jumlah_obat_23_rd" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#btn_submit_obat').click();
          }
          return false;       
        }

    });


})

function getDetailObatByKodeBrgRacikan(kode_brg,kode_bag){

  var type_layan = $('#tipe_layanan').val();
  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok=<?php echo isset($kode_kelompok)?$kode_kelompok:0?>&kode_perusahaan=<?php echo isset($value_header->kode_perusahaan)?$value_header->kode_perusahaan:0?>&bag="+kode_bag+"&type=html&type_layan="+type_layan+"", '' , function (response) {
    if(response.sisa_stok <= 0){
      $('#btn_add_obat_racikan').hide('fast');
      $('#btn_submit_obat').attr('disabled', true);
      $('#warning_stok_obat_racikan').html('<span style="color:red"><b><i>Stok sudah habis !</i></b></span>');
    }else{
      $('#btn_submit_obat').attr('disabled', false);
      $('#btn_add_obat_racikan').show('fast');
      $('#warning_stok_obat_racikan').html('');
    }
    /*show detail tarif html*/
    $('#div_detail_obat_racikan').show('fast');
    $('#detailObatHtmlRacikan').html(response.html);

    return response;

  })

}

function reload_table_racikan(){

  var id_tc_far_racikan = $('#id_tc_far_racikan').val();
  table_detail_racikan.ajax.url("farmasi/Entry_resep_racikan/get_data?id="+id_tc_far_racikan).load();
  table.ajax.reload();
  // table_racikan.ajax.reload();
  sum_total_biaya_farmasi();
  
}

function show_selected_item_racikan(id_tc_far_racikan){

  if( id_tc_far_racikan != '0' || id_tc_far_racikan != '#'){
      $.getJSON("<?php echo site_url('farmasi/Entry_resep_racikan/get_resep_racikan_by_id') ?>/" + id_tc_far_racikan, '', function (data) {    

          $('#nama_racikan').val(data.nama_racikan);
          $('#jml_racikan').val(parseInt(data.jml_content));
          $('#satuan_racikan').val(data.satuan_kecil);
          $('#jasa_r_racikan').val(parseInt(data.jasa_r));
          $('#jasa_prod_racikan').val(parseInt(data.jasa_produksi));
          $('#aturan_pakai_racikan').val(data.aturan_pakai_format);
          $('#catatan_racikan').val(data.catatan_aturan_pakai);

          /*show form obat*/  
          $('#data_obat_div').show('fast');
          $('#result_data_racikan_div').show('fast');
          $('#data_racikan_div').hide('fast');
          $('#data_aturan_pakai_div').hide('fast');

          /*fill table value*/
          $('#id_tc_far_racikan').val(data.relation_id);
          $('#kode_r').text(data.kode_brg);
          $('#nama_r').text(data.nama_brg.toUpperCase());
          $('#jml_r').text(parseInt(data.jumlah_tebus)+' '+data.satuan_kecil);
          $('#signa_r').text(data.dosis_per_hari+' x '+data.dosis_obat+' '+data.anjuran_pakai);
          $('#catatan_r').text(data.catatan_aturan_pakai);
          var label_ditangguhkan = 'N';
          if( data.prb_ditangguhkan == 1 ){
            var label_ditangguhkan = 'Ya';
          }
          $('#jml_prb_r').text(data.jumlah_obat_23+' '+data.satuan_kecil +' / '+label_ditangguhkan);
          $('#penangguhan_r').text(label_ditangguhkan);
          $('#petugas_r').text('admin');

          // signa
          $('#dosis_start_r').val(data.dosis_per_hari);
          $('#dosis_end_r').val(data.dosis_obat);
          $('#catatan').val(data.catatan_lainnya);
          $('#satuan_obat_r').val(data.aturan_pakai);
          $('#anjuran_pakai').val(data.anjuran_pakai);

          /*btn action*/
          $('#btn_delete_racikan').attr('onclick', 'delete_racikan('+data.relation_id+', "'+'racikan'+'")');
          $('#btn_submit_racikan_selesai').attr('onclick', 'process_selesai('+data.relation_id+')');

          /*show detail tarif html*/
          reset_search_obat();

          reload_table_racikan();

      }); 
  }else{
    renew_form();
    reload_table_racikan();
  }
  
}

function renew_form(){

  /*show form obat*/  
  $('#data_obat_div').hide('fast');
  $('#result_data_racikan_div').hide('fast');
  $('#data_racikan_div').show('fast');
  $('#data_aturan_pakai_div').show('fast');

  /*reset form*/
  $('#form_entry_resep_racikan')[0].reset();
  reload_item_racikan( 0 );
  $('#btn_update_header_racikan').hide('fast');
  $('#btn_submit_racikan').show('fast');

  table_detail_racikan.ajax.url("farmasi/Entry_resep_racikan/get_data?id=").load();
  
  return false;

}

function reset_search_obat(){
  /*show detail tarif html*/
  $('#inputKeyObatRacikan').val('');
  $('#jumlah_pesan_racikan').val('');
  $('#div_detail_obat_racikan').hide('fast');
  $('#detailObatHtmlRacikan').html('');

}

function reload_item_racikan(id_tc_far_racikan){

  var kode_trans_far = $('#kode_trans_far').val();
  $('#id_tc_far_racikan').val(id_tc_far_racikan);
  console.log(id_tc_far_racikan);
  $.getJSON("<?php echo site_url('farmasi/Entry_resep_racikan/get_item_racikan') ?>/" + kode_trans_far, '', function (data) {              

      $('#select_racikan option').remove();                

      $('<option value="0">-Silahkan Pilih-</option>').appendTo($('#select_racikan'));                           

      $.each(data, function (i, o) {    

          if(o.id_tc_far_racikan == id_tc_far_racikan){
            var selected = "selected";
          }else{
            var selected = "";
          }

          $('<option value="' + o.id_tc_far_racikan + '" '+selected+' >' + o.nama_racikan.toUpperCase() + '</option>').appendTo($('#select_racikan'));                    
            
      });      


  });

  return false;

}


function delete_item_obat_racikan(id_tc_far_racikan_detail, id_tc_far_racikan){

  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/Entry_resep_racikan/delete_obat',
        type: "post",
        data: {ID:id_tc_far_racikan_detail, id_tc_far_racikan : id_tc_far_racikan},
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
            reload_table_racikan();
            reload_table();
            sum_total_biaya_farmasi();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
          $('#inputKeyObatRacikan').focus();
        }

      });

  }else{
    return false;
  }

}

function delete_racikan(myid, flag){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/process_entry_resep/delete',
        type: "post",
        data: {ID:myid, flag:flag},
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
            $('#id_tc_far_racikan').val('');
          /*renew form*/
          renew_form();
          reload_table_racikan();
          reload_item_racikan( 0 );

          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
          $('#inputKeyObatRacikan').focus();
        }

      });

  }else{
    return false;
  }
  
}

function process_selesai(myid){
  preventDefault();
  
  $.ajax({
      url: 'farmasi/Entry_resep_racikan/process_selesai_racikan',
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
          /*selesai racikan*/
          $('#globalModalView').modal('toggle');
          reload_table();
        
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();

      }

    });
  
}

function btn_update_racikan(){

  var id_tc_far_racikan = $('#id_tc_far_racikan').val();

  $.getJSON("<?php echo site_url('farmasi/Entry_resep_racikan/get_resep_racikan_by_id') ?>/" + id_tc_far_racikan, '', function (data) {              

        $('#id_tc_far_racikan').val(id_tc_far_racikan);
        $('#nama_racikan').val(data.nama_brg);
        $('#jml_racikan').val(parseInt(data.jumlah_tebus));
        $('#jumlah_obat_23_r').val(parseInt(data.jumlah_obat_23));
        $('#satuan_racikan').val(data.satuan_kecil);
        $('#jasa_r_racikan').val(parseInt(data.jasa_r));
        $('#jasa_prod_racikan').val(parseInt(data.jasa_produksi));
        if(data.prb_ditangguhkan == 1){
          $('input[name=prb_ditangguhkan_r][type=checkbox]').prop('checked',true);
        }else{
          $('input[name=prb_ditangguhkan_r][type=checkbox]').prop('checked',false);
        }
        // signa
        $('#_r_r').val(data.dosis_per_hari);
        $('#dosis_end_r').val(data.dosis_obat);
        $('#catatan_r').val(data.catatan_lainnya);
        $('#satuan_obat_r').val(data.aturan_pakai);
        $('#anjuran_pakai_r').val(data.anjuran_pakai);

        /*show form obat*/  
        $('#data_obat_div').hide('fast');
        $('#result_data_racikan_div').hide('fast');
        $('#data_racikan_div').show('fast');
        $('#data_aturan_pakai_div').show('fast');

        $('#btn_update_header_racikan').show('fast');
        $('#btn_submit_racikan').hide('fast');

        reload_table_racikan();

  }); 

}

function select_item_racikan(id){
  preventDefault();
  $.getJSON("<?php echo site_url('farmasi/E_resep/getrowresep') ?>?ID="+id, '' , function (response) {
    // autofill
    $('#inputKeyObatRacikan').val(response.nama_brg);
    $('#jumlah_pesan_racikan').val(response.jml_pesan);
    getDetailObatByKodeBrgRacikan(response.kode_brg,'060101');
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

    <div style="margin-top:0px">   
      <form class="form-horizontal" method="post" id="form_entry_resep_racikan" enctype="multipart/form-data" autocomplete="off" action="farmasi/entry_resep_racikan/process">      
        
      

      <input type="hidden" id="kode_pesan_resep" name="kode_pesan_resep" value="<?php echo isset($kode_pesan_resep)?$kode_pesan_resep:''; ?>">
      
      <input type="hidden" id="tipe_layanan" name="tipe_layanan" value="<?php echo $tipe_layanan; ?>">

      <input type="hidden" name="kd_tr_resep" id="kd_tr_resep" value="0">
      <input type="hidden" name="no_registrasi" value="<?php echo isset($value_header->no_registrasi)?$value_header->no_registrasi:''?>">
      <input type="hidden" name="kode_perusahaan" value="<?php echo isset($value_header->kode_perusahaan)?$value_header->kode_perusahaan:''?>" id="kode_perusahaan">
      <input type="hidden" name="no_mr" value="<?php echo isset($value_header->no_mr)?$value_header->no_mr:''?>">
      <input type="hidden" name="nama_pasien" value="<?php echo isset($value_header->nama_pasien)?$value_header->nama_pasien:''?>">
      <input type="hidden" name="kode_dokter" value="<?php echo isset($value_header->kode_dokter)?$value_header->kode_dokter:''?>">
      <input type="hidden" name="dokter_pengirim" value="<?php echo isset($value_header->nama_pegawai)?$value_header->nama_pegawai:''?>">
      <input type="hidden" name="kode_profit" value="<?php echo ($tipe_layanan=='RJ')?2000:1000;?>">
      <input type="hidden" name="kode_bagian" value="<?php echo isset($value_header->kode_bagian)?$value_header->kode_bagian:''?>" id="kode_bagian">
      <input type="hidden" name="kode_bagian_asal" value="<?php echo isset($value_header->kode_bagian_asal)?$value_header->kode_bagian_asal:''?>">
      <input type="hidden" name="flag_trans" id="flag_trans" value="<?php echo $tipe_layanan?>">
      <input type="hidden" name="flag_resep" value="biasa">
      <input type="hidden" name="no_kunjungan" id="no_kunjungan" class="form-control" value="<?php echo isset($value_header)?ucwords($value_header->no_kunjungan):''?>" >
      <input type="hidden" name="no_resep" id="no_resep" class="form-control" value="<?php echo isset($value_header)?ucwords($value_header->kode_pesan_resep):''?>" >


        <div class="col-sm-12">

          <div class="col-md-8 no-padding">
            <div class="form-group">
              <label class="control-label col-sm-2">ID Racikan</label>
              <div class="col-md-1">
                <input type="text" id="id_tc_far_racikan" name="id_tc_far_racikan" value="<?php echo isset($_GET['id_tc_far_racikan'])?$_GET['id_tc_far_racikan']:0?>" class="form-control">
              </div>
              <label class="control-label col-sm-2">Kode Transaksi</label>
              <div class="col-md-2">
                <input type="text" id="kode_trans_far_racikan" name="kode_trans_far_racikan" value="<?php echo $kode_trans_far; ?>" class="form-control">
              </div>
            </div> 

            <!-- pilih racikan -->
            <div class="form-group">
              <label class="control-label col-sm-2">Pilih Racikan</label>
              <div class="col-md-6">
                <?php echo $this->master->custom_selection($params = array('table' => 'fr_tc_far_detail_log', 'id' => 'relation_id', 'name' => 'nama_brg', 'where' => array('kode_trans_far' => $kode_trans_far, 'flag_resep' => 'racikan')), isset($_GET['id_tc_far_racikan'])?$_GET['id_tc_far_racikan']:'' , 'select_racikan', 'select_racikan', 'form-control', '', '') ?>
              </div>
              <div class="col-md-1">
                <a href="#" onclick="renew_form()" class="btn btn-xs btn-primary"><i class="fa fa-plus-circle dark"></i> Buat Racikan Baru</a>
              </div>
            </div> 

            <!-- header racikan -->
            <div id="result_data_racikan_div" style="display: none">
              <table class="table" width="50%">
                <tr style="background-color:#d0e8ec; color:black">
                  <th>Kode</th>
                  <th>Nama Racikan</th>
                  <th>Jumlah</th>
                  <!-- <?php if( strtoupper($tipe_layanan) == 'RJ' ) : ?>
                  <th>Jumlah PRB / Ditangguhkan</th>
                  <?php endif; ?> -->
                  <th>Signa</th>
                  <th>Petugas</th>
                  <th width="80px" align="center"></th>
                </tr>
                <tr>
                  <td id="kode_r">-</td>
                  <td id="nama_r">-</td>
                  <td id="jml_r" class="center">-</td>
                  <!-- <?php if( strtoupper($tipe_layanan) == 'RJ' ) : ?>
                  <td id="jml_prb_r" class="center">-</td>
                  <?php endif; ?> -->
                  <td id="signa_r">-</td>
                  <td id="petugas_rx"><?php echo $this->session->userdata('user')->fullname?></td>
                  <td align="center">
                    <a href="#" id="btn_update_racikan" onclick="btn_update_racikan()" data-id="" class="btn btn-xs btn-success">
                      <i class="ace-icon fa fa-edit icon-on-right bigger-110"></i>
                    </a>
                    <a href="#" id="btn_delete_racikan" onclick="" class="btn btn-xs btn-danger">
                      <i class="ace-icon fa fa-trash icon-on-right bigger-110"></i>
                    </a>

                  </td>
                </tr>
              </table>
            </div>

            <!-- form racikan header -->
            <div id="data_racikan_div">

                <div class="form-group">
                  <label class="control-label col-sm-2">Nama Racikan</label>
                  <div class="col-md-3">
                      <input type="text" class="form-control" name="nama_racikan" id="nama_racikan" value="<?php echo isset($eresep['header_racikan']->nama_brg) ? $eresep['header_racikan']->nama_brg: 'Racikan Kode. '.$kode_trans_far.''?>">  
                  </div>
                  <label class="control-label col-sm-1">Jasa R</label>
                  <div class="col-md-1">
                      <input type="text" class="form-control" name="jasa_r_racikan" id="jasa_r_racikan" value="500">   
                  </div>
                  <label class="control-label col-sm-2">Jasa Prod</label>
                  <div class="col-md-2">
                      <input type="text" class="form-control" name="jasa_prod_racikan" id="jasa_prod_racikan" value="2000">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-2">Jumlah Obat</label>
                  <div class="col-md-1">
                    <input type="text" class="form-control" name="jml_racikan" id="jml_racikan" style="text-align: center;" value="<?php echo isset($eresep['header_racikan']->jml_pesan) ? $eresep['header_racikan']->jml_pesan: 0?>">  
                  </div>
                  <!-- <div class="col-md-6">
                    <label class="inline" style="margin-top: 4px;margin-left: -12px;">
                      <input type="checkbox" class="ace" name="resep_ditangguhkan_r" value="1">
                      <span class="lbl"> Ditangguhkan </span>
                    </label>
                  </div> -->
                </div>

                <!-- <?php if( strtoupper($tipe_layanan) == 'RJ' ) : ?>
                <div class="form-group">
                    <label class="control-label col-sm-2">Resep PRB</label>
                    <div class="col-md-1">
                      <input type="text" class="form-control" name="jumlah_obat_23_r" id="jumlah_obat_23_r" style="text-align: center;" value="">  
                    </div>
                    <div class="col-md-6">
                      <label class="inline" style="margin-top: 4px;margin-left: -12px;">
                        <input type="checkbox" class="ace" name="prb_ditangguhkan_r" value="1">
                        <span class="lbl"> Ditangguhkan </span>
                      </label>
                    </div>
                </div>
                <?php endif; ?> -->
                <div class="form-group">
                  <label class="control-label col-sm-2">Satuan Racikan</label>
                  <div class="col-md-2">
                    <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'satuan_obat')), isset($eresep['header_racikan']->satuan_obat) ? $eresep['header_racikan']->satuan_obat: 'Bks' , 'satuan_racikan', 'satuan_racikan', 'form-control', '', '');?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-2">Signa</label>
                  <div class="col-md-4">
                    <span class="inline">
                      <input name="dosis_start_r" id="dosis_start_r" type="text" style="width: 50px; text-align: center" value="<?php echo isset($eresep['header_racikan']->jml_dosis) ? $eresep['header_racikan']->jml_dosis: 0?>"/>
                    </span>
                    <span class="inline" style="padding-left: 4px;">
                      <i class="fa fa-times bigger-150"></i>
                    </span>
                    <span class="inline">
                      <input name="dosis_end_r" id="dosis_end_r" type="text" style="width: 50px; text-align: center" value="<?php echo isset($eresep['header_racikan']->jml_dosis_obat) ? $eresep['header_racikan']->jml_dosis_obat: 0?>"/>
                    </span>
                    
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-2">Penggunaan</label>
                  <div class="col-md-4">
                    <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anjuran_pakai_obat')), isset($eresep['header_racikan']->aturan_pakai) ? $eresep['header_racikan']->aturan_pakai: 'Sesudah Makan'  , 'anjuran_pakai_r', 'anjuran_pakai_r', 'form-control', '', '');?>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-sm-2">Catatan</label>
                  <div class="col-md-1">
                      <input class="form-control" name="catatan_r" id="catatan_r" type="text" style="width: 400px" value="<?php echo isset($eresep['header_racikan']->keterangan) ? $eresep['header_racikan']->keterangan: ''?>"/>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2">&nbsp;</label>
                  <div class="col-md-8" style="margin-left:6px;">
                    <button type="submit" id="btn_submit_racikan" name="submit" value="header" class="btn btn-xs btn-primary">
                        <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                        Submit
                    </button>
                    <button type="submit" id="btn_update_header_racikan" style="display:none" name="submit" value="header" class="btn btn-xs btn-success">
                          <i class="ace-icon fa fa-edit icon-on-right bigger-110"></i>
                          Update
                      </button>
                  </div>
                </div> 
                
                <hr>

            </div>
          </div>

          <div class="col-md-4">
              <b>KOMPOSISI OBAT RACIKAN (e-Resep)</b>
              <table class="table">
                <tr style="background: #d0e8ec">
                  <th class="center">No</th>
                  <th>Nama Obat</th>
                  <th>Dosis</th>
                </tr>
                <?php $no=0; foreach($eresep['racikan'] as $row_racikan) : if($row_racikan->parent != '0') :$no++; ?>
                  <tr>
                    <td align="center"><?php echo $no; ?></td>
                    <td><a href="#" onclick="select_item_racikan(<?php echo $row_racikan->id?>)"><?php echo $row_racikan->nama_obat; ?></a></td>
                    <td><?php echo $row_racikan->jml_pesan.' '.$row_racikan->satuan_obat; ?></td>
                  </tr>
                <?php endif; endforeach;?>
              </table>
            </div>

          <!-- form obat -->
          <div class="col-sm-12 no-padding" id="data_obat_div" style="display: none" >
            
            <div class="col-sm-8 no-padding">
              <!-- Data Obat -->
              <p><b>KOMPOSISI OBAT RACIKAN</b></p>

              <!-- form hidden for this section -->
              <input type="hidden" name="id_tc_far_racikan_detail" id="id_tc_far_racikan_detail" value="0">

              <div class="form-group">
                <label class="control-label col-sm-2">Jenis</label>
                <div class="col-md-5">
                  <div class="radio">
                      <label>
                        <input name="urgensi_r" type="radio" class="ace" value="cito" />
                        <span class="lbl"> Cito</span>
                      </label>

                      <label>
                        <input name="urgensi_r" type="radio" class="ace" value="biasa" checked/>
                        <span class="lbl"> Biasa</span>
                      </label>
                  </div>
                </div> 

              </div>

              <!-- cari obat -->
              <div class="form-group">
                <label class="control-label col-sm-2">Cari Obat</label>  
                <div class="col-md-8">   
                <input type="text" name="obat" id="inputKeyObatRacikan" class="form-control" placeholder="Masukan Keyword Obat" value=""> 
                </div>
              </div>

              <!-- jumlah -->
              <div class="form-group">
                <label class="control-label col-sm-2">Jumlah Obat</label>
                <div class="col-md-2">
                  <input type="text" class="form-control" name="jumlah_pesan_racikan" id="jumlah_pesan_racikan" style="text-align: center;">  
                </div>
                <!-- <?php if( strtoupper($tipe_layanan) == 'RJ' ) : ?>
                <label class="control-label col-sm-2">Resep PRB</label>
                <div class="col-md-2">
                  <input type="text" class="form-control" name="jumlah_obat_23_rd" id="jumlah_obat_23_rd" style="text-align: center;" value="">  
                </div>
                <div class="col-md-4">
                  <label class="inline" style="margin-top: 4px;margin-left: -12px;">
                    <input type="checkbox" class="ace" name="prb_ditangguhkan_rd" value="1">
                    <span class="lbl"> Ditangguhkan (Jika tidak ada stok)</span>
                  </label>
                </div>
                <?php endif; ?> -->
              </div>
              
              <div class="form-group">
                <label class="col-sm-2">&nbsp;</label>
                <div class="col-md-3" style="margin-left: 6px">
                    <button type="submit" id="btn_submit_obat"  value="detail" name="submit" class="btn btn-xs btn-primary">
                    <i class="ace-icon fa fa-plus icon-on-right bigger-110"></i>
                    Tambahkan Obat
                  </button>
                </div>
              </div>

            </div>
            
            <div class="col-sm-4" style="display:none" id="div_detail_obat_racikan">
              <div id="warning_stok_obat_racikan"></div>
              <div id="detailObatHtmlRacikan"></div>
            </div>

          </div>
          
          <!-- datatable obat -->
          <div class="col-sm-12">
            <hr class="separator">
            <div style="margin-top:-27px">
              <table id="temp_data_racikan" base-url="farmasi/Entry_resep_racikan" class="table table-bordered table-hover">
                 <thead>
                  <tr>  
                    <th width="50px"></th>
                    <th width="50px">ID</th>
                    <th width="100px">Kode</th>
                    <th>Nama Obat</th>
                    <th width="120px">Jumlah Pesan</th>
                    <th width="120px">Satuan</th>
                    <th width="120px">Harga Satuan</th>
                    <th width="120px">Total (Rp.)</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <b>Keterangan :</b><br>
              <i>Barang yang sudah habis di retur jika ingin di transaksikan ulang, maka data yang lama harus dihapus terlebih dahulu.</i>
            </div>

          </div>

        </div>

      </form>

    </div>

</div><!-- /.row -->

