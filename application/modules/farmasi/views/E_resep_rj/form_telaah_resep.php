<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<link rel="stylesheet" href="assets/css/daterangepicker.css" />
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

    sum_total_biaya_farmasi();

    var kode_trans_far = $('#kode_trans_far').val();


    table = $('#temp_data_pesan').DataTable( {
        "processing": true, 
        "serverSide": true,
        "bInfo": false,
        "bPaginate": false,
        "searching": false,
        "bSort": false,
        "ajax": {
            "url": "farmasi/E_resep_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=biasa&tipe_layanan=<?php echo $tipe_layanan?>",
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], //last column
                "orderable": false, //set not orderable
            },
            {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
            { "visible": true, "targets": [ 0 ] },
            { "visible": false, "targets": [ 1 ] },
            { "visible": false, "targets": [ 2 ] },
        ],
    }); 

    $('#temp_data_pesan tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var data = table.row( $(this).parents('tr') ).data();
            var ID = data[ 1 ];
            var flag = data[ 2 ];
            var kode_brg = data[ 6 ];
                      

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
                if( flag == 'racikan' ){
                  $.getJSON("farmasi/Entry_resep_racikan/getDetail/" + ID, '', function (data) {
                      response_data = data;
                      // Open this row
                      row.child( format_html( response_data ) ).show();
                      tr.addClass('shown');
                  });
                }else{
                  $.getJSON("farmasi/E_resep_rj/getDetail/" + kode_brg +'/'+ ID, '', function (data) {
                      response_data = data;
                      // Open this row
                      row.child( format_html( response_data ) ).show();
                      tr.addClass('shown');
                  });
                }
                
                                
            }
    } );

    $('#temp_data_pesan tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            //achtungShowLoader();
            $(this).removeClass('selected');
            //achtungHideLoader();
        }
        else {
            //achtungShowLoader();
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            //achtungHideLoader();
        }
    } );

    table_racikan = $('#temp_data_obat_racikan').DataTable( {
        "processing": true, 
        "serverSide": true,
        "bInfo": false,
        "bPaginate": false,
        "searching": false,
        "bSort": false,
        "ajax": {
            "url": "farmasi/E_resep_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=racikan&tipe_layanan=<?php echo $tipe_layanan?>",
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], //last column
                "orderable": false, //set not orderable
            },
            {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
            { "visible": true, "targets": [ 0 ] },
            { "visible": false, "targets": [ 1 ] },
            { "visible": false, "targets": [ 2 ] },
        ],
    }); 

    $('#temp_data_obat_racikan tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table_racikan.row( tr );
            var data = table_racikan.row( $(this).parents('tr') ).data();
            var ID = data[ 1 ];
            var flag = data[ 2 ];
            var kode_brg = data[ 5 ];
                      

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
                $.getJSON("farmasi/Entry_resep_racikan/getDetail/" + ID, '', function (data) {
                    response_data = data;
                    // Open this row
                    row.child( format_html( response_data ) ).show();
                    tr.addClass('shown');
                });
                                
            }
    } );

    $('#temp_data_obat_racikan tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            //achtungShowLoader();
            $(this).removeClass('selected');
            //achtungHideLoader();
        }
        else {
            //achtungShowLoader();
            table_racikan.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            //achtungHideLoader();
        }
    } );

    $('#form_entry_resep').ajaxForm({      
      beforeSend: function() {        
        achtungShowLoader();    
        $('#btn_submit').attr('disabled', true);  
      },      
      uploadProgress: function(event, position, total, percentComplete) {},      
      complete: function(xhr) {             
        var data=xhr.responseText;        
        var jsonResponse = JSON.parse(data);        
        if(jsonResponse.status === 200){          
          $.achtung({message: jsonResponse.message, timeout:5});             
          // kode trans far
          $('#kode_trans_far').val(jsonResponse.kode_trans_far);
          /*reload table*/
          reload_table();
          /*sum total biaya farmasi*/
          sum_total_biaya_farmasi();
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className:'achtungFail'});   
          $('#btn_submit').attr('disabled', false);       
        }    
        $('#btn_submit').attr('disabled', false);
        achtungHideLoader();  
      }      
    });     

})

function searchObat(num){
  $('#inputKeyObat_'+num+'').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getObatByBagianAutoComplete",
              data: { keyword:query, bag: '060101'},            
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
        $('#inputKeyObat_'+num+'').val(label_item);
        $('#kode_brg_'+num+'').val(val_item);
        $('#kode_brg_td_'+num+'').text(val_item);
        getDetailObatByKodeBrg(val_item, '060101', num);
      }
  });
}

function getDetailObatByKodeBrg(kode_brg,kode_bag,is_edit=''){

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok=<?php echo isset($value)?$value->kode_kelompok:0?>&kode_perusahaan="+$('#kode_perusahaan').val()+"&bag="+kode_bag+"&type=html&type_layan=Rajal&urgensi="+$().val()+"", '' , function (response) {
    $('#sisa_stok').val(response.sisa_stok);

    if(response.sisa_stok <= 0){
      $('#inputKeyObat').focus();
      // $('#btn_submit').attr('disabled', true);
      // $('#jumlah_pesan').attr('disabled', true);
      $('#jumlah_pesan').val('0');
      $('#warning_stok_obat').html('<div class="alert alert-danger"><b><i class="fa fa-exclamation-triangle"></i> Peringatan !</b> Stok sudah habis, silahkan lakukan permintaan ke gudang farmasi.</div>');
      $('#detailPembelianObatHtml').html('');
      $('input[name=prb_ditangguhkan][type=checkbox]').prop('checked',true);
      $('#prb_ditangguhkan').attr('readonly', true);
      $('input[name=resep_ditangguhkan][type=checkbox]').prop('checked',true);
      $('#resep_ditangguhkan').attr('readonly', true);
    }else{
      $('input[name=prb_ditangguhkan][type=checkbox]').prop('checked',false);
      $('#prb_ditangguhkan').attr('readonly', false);
      $('input[name=resep_ditangguhkan][type=checkbox]').prop('checked',false);
      $('#resep_ditangguhkan').attr('readonly', false);

      $('#jumlah_pesan').focus();
      $('#jumlah_pesan').attr('max', response.sisa_stok);
      $('#jml_23').attr('max', response.sisa_stok);
      $('#jumlah_pesan').attr('disabled', false);
      $('#btn_submit').attr('disabled', false);
      $('#warning_stok_obat').html('');
      // cek data obat bpjs yang sudah pernah di beli sebelumnya
      $.getJSON("<?php echo site_url('templates/references/getDataPembelianObat') ?>?kode="+kode_brg+"&kode_perusahaan=<?php echo isset($value)?$value->kode_perusahaan:0?>&bag="+kode_bag+"&type=html&type_layan=Rajal&no_mr="+$('#no_mr').val()+"", '' , function (response) {
        // show warning
        $('#detailPembelianObatHtml').html(response.html);
      })
    }
    /*show detail tarif html*/
    $('#div_detail_obat').show('fast');
    $('#detailObatHtml').html(response.html);
    if( is_edit == 1 ){
      $('#btn_submit').attr('disabled', false);
    }

    return response;

  })

}

function format_html ( data ) {
  return data.html;
}

$('#btn_racikan').click(function () {  
  show_modal('farmasi/Entry_resep_racikan/form/'+$('#kode_trans_far').val()+'?kelompok='+$('#kode_kelompok').val()+'&tipe_layanan='+$('#flag_trans').val()+'&kode_pesan_resep='+$('#no_resep').val()+'', 'RESEP RACIKAN');
})

function checkAllItem(elm) {

  if($(elm).prop("checked") == true){
    $('table .td_checkbox').each(function(){
        $('table .td_checkbox').prop("checked", true);
    });
  }else{
    $('table .td_checkbox').prop("checked", false);
  }

}

function reload_table(){
  var kode_trans_far = $('#kode_trans_far').val();
  table.ajax.url("farmasi/E_resep_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=biasa&tipe_layanan=<?php echo $tipe_layanan?>").load();
  // table_racikan.ajax.url("farmasi/E_resep_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=racikan&tipe_layanan=<?php echo $tipe_layanan?>").load();
  sum_total_biaya_farmasi();
}

function delete_resep(myid, flag){
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
            reload_table();
            reset_form();
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

function resep_farmasi_selesai(type){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/process_entry_resep/process_selesai_resep',
        type: "post",
        data: { ID : $('#kode_trans_far').val(), 'kode_pesan_resep' : $('#no_resep').val(), 'kode_kelompok' : $('#kode_kelompok').val(), 'kode_perusahaan' : $('#kode_perusahaan').val(), 'kode_profit' : $('#kode_profit').val(), 'nama_pasien' : $('#nama_pasien').val(), 'no_mr' : $('#no_mr').val(), 'submit' : type, 'is_rollback' : $('#is_rollback').val() },
        dataType: "json",
        beforeSend: function() {
          // achtungShowLoader();  
          $('#btn_resep_selesai').attr('disabled', true);
          $('#btn_resep_selesai_diantar').attr('disabled', true);
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            // $.achtung({message: jsonResponse.message, timeout:5});
            // show poup cetak resep
            // PopupCenter('farmasi/Process_entry_resep/nota_farmasi/'+jsonResponse.kode_trans_far+'','Nota Farmasi', 530, 550);
            $('#page-area-content').load('farmasi/Process_entry_resep/preview_entry/'+jsonResponse.kode_trans_far+'?flag=RJ');
            // $('#page-area-content').load('farmasi/E_resep_rj?flag=RJ');

          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          // achtungHideLoader();
          $('#btn_resep_selesai').attr('disabled', false);
          $('#btn_resep_selesai_diantar').attr('disabled', false);
        }

      });

  }else{
    return false;
  }
}

function sum_total_biaya_farmasi(){

  var kode_trans_far = $('#kode_trans_far').val();
  $.getJSON("<?php echo site_url('farmasi/process_entry_resep/get_total_biaya_farmasi') ?>/"+kode_trans_far, '' , function (response) {

      $('#td_total_biaya_farmasi').html('<b>Rp. '+formatMoney(response.total)+',-</b>');

  })

}

function changeUrgensi(){
  var urgensi = $("input[type='radio'][name='urgensi']:checked").val();
  if(urgensi=='cito'){
    if( $('#pl_sisa_stok_cito').val() > 0){
      $('#jumlah_pesan').attr('disabled', false);
      $('#btn_submit').attr('disabled', false);
    }else{
      $('#btn_submit').attr('disabled', true);
    }
  }else{
    if( $('#pl_sisa_stok').val() > 0){
      $('#jumlah_pesan').attr('disabled', false);
      $('#btn_submit').attr('disabled', false);
    }else{
      $('#btn_submit').attr('disabled', true);
    }
  }
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

  <div class="col-xs-12">
    
    <!-- profile -->
    <table class="table">
      <tr>
        <td style="width: 150px; font-weight: bold">Kode / Tanggal Pesan</td>
        <td> <?php echo isset($value)?ucwords($value->kode_pesan_resep):''?> / <?php echo isset($value)?ucwords($this->tanggal->formatDateTime($value->tgl_pesan)):''?> </td>
        <td style="width: 150px; font-weight: bold">Nama Dokter</td>
        <td> <?php echo isset($value)?$value->nama_pegawai:''?> </td>
      </tr>

      <tr>
        <td style="width: 150px; font-weight: bold">No.MR / Nama Pasien</td>
        <td> <?php echo isset($value)?ucwords($value->no_mr):''?> / <?php echo isset($value)?ucwords($value->nama_pasien):''?> </td>
        <td style="width: 150px; font-weight: bold">Poli / Klinik</td>
        <td> <?php echo isset($value)?ucwords($value->nama_bagian):''?> </td>
      </tr>

      <tr>
        <td style="width: 150px; font-weight: bold">Perusahaan Penjamin</td>
        <td> <?php echo isset($value)?ucwords($value->nama_kelompok):''?><br><?php echo isset($value)?ucwords($value->nama_perusahaan):''?> <?php echo isset($value->kode_perusahaan) ? ($value->kode_perusahaan == 120) ?'('.$value->no_sep.')' : '' :'';?> </td>
        <td style="width: 150px; font-weight: bold">Diagnosa Akhir</td>
        <td> <?php echo isset($value)?$value->diagnosa_akhir:''?> </td>
      </tr>
    </table>

    <form class="form-horizontal" method="post" id="form_entry_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/process_entry_resep/process">      
      
      <!-- form_hidden -->
      <input type="hidden" name="kode_trans_far" id="kode_trans_far" value="0">
      <input type="hidden" name="sisa_stok_hidden" id="sisa_stok" value="0">
      <input type="hidden" name="kd_tr_resep" id="kd_tr_resep" value="0">
      <input type="hidden" name="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
      <input type="hidden" name="no_mr" id="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
      <input type="hidden" id="nama_pasien" name="nama_pasien" value="<?php echo isset($value)?$value->nama_pasien:''?>">
      <input type="hidden" name="kode_dokter" value="<?php echo isset($value)?$value->kode_dokter:''?>">
      <input type="hidden" name="dokter_pengirim" value="<?php echo isset($value)?$value->nama_pegawai:''?>">
      <input type="hidden" name="kode_profit" id="kode_profit" value="<?php echo ($tipe_layanan=='RJ')?2000:1000;?>">
      <input type="hidden" name="kode_bagian" value="<?php echo isset($value)?$value->kode_bagian:''?>" id="kode_bagian">
      <input type="hidden" name="kode_bagian_asal" value="<?php echo isset($value)?$value->kode_bagian_asal:''?>">
      <input type="hidden" name="flag_trans" id="flag_trans" value="<?php echo $tipe_layanan?>">
      <input type="hidden" name="flag_resep" value="biasa">
      <input type="hidden" name="no_kunjungan" id="no_kunjungan" class="form-control" value="<?php echo isset($value)?ucwords($value->no_kunjungan):''?>" >
      <input type="hidden" name="no_resep" id="no_resep" class="form-control" value="<?php echo isset($value)?$value->kode_pesan_resep:''?>" >
      <input type="hidden" name="kode_kelompok" id="kode_kelompok" class="form-control" value="<?php echo isset($value)?$value->kode_kelompok:''?>" >
      <input type="hidden" name="kode_perusahaan" id="kode_perusahaan" class="form-control" value="<?php echo isset($value)?$value->kode_perusahaan:''?>" >
      <input type="hidden" name="kode_poli" id="kode_poli" class="form-control" value="<?php echo isset($value->kode_poli)?$value->kode_poli:0?>" >
      <input type="hidden" name="kode_ri" id="kode_ri" class="form-control" value="<?php echo isset($value->kode_ri)?$value->kode_ri:0?>" >
      <input class="form-control" name="harga_r" id="harga_r" type="hidden" value="500" readonly />
      <input class="form-control" name="is_rollback" id="is_rollback" type="hidden" value="<?php echo isset($_GET['rollback']) ? 1 : 0 ; ?>" readonly />

      <div class="row">
        <!-- form utama -->
        <div class="col-sm-12">
          <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active">
                <a data-toggle="tab" href="#resep_dokter">
                  Resep Dokter
                </a>
              </li>

              <li>
                <a data-toggle="tab" href="#messages">
                  Hasil Penunjang Medis
                </a>
              </li>
            </ul>
            <div class="tab-content">
              <div id="resep_dokter" class="tab-pane fade in active no-padding">
                <div style="overflow-x:auto; width: 100%">
                    <table id="table_resep_dokter" class="table table-bordered table-hover">
                      <thead>
                        <tr>  
                          <th class="center" width="10px">
                            <div class="center">
                              <label class="pos-rel">
                                  <input type="checkbox" class="ace" name="" onClick="checkAllItem(this);" value="0"/>
                                  <span class="lbl"></span>
                              </label>
                            </div>
                          </th>
                          <th width="250px">Nama Obat</th>
                          <th width="400px">Signa / Dosis Obat</th>
                          <th width="100px">Tebus (7)</th>
                          <th width="100px">Kronis (23)</th>
                          <th width="50px">Iter</th>
                          <th width="30px">Cito</th>
                          <th width="100px">Keterangan</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($resep_cart as $row_rc) :?>
                        <tr>  
                          <td class="center" width="10px">
                            <div class="center">
                              <label class="pos-rel">
                                  <input type="checkbox" class="ace td_checkbox" name="checked_<?php echo $row_rc->id?>" id="checked_<?php echo $row_rc->id?>" value="<?php echo $row_rc->id?>"/>
                                  <span class="lbl"></span>
                              </label>
                            </div>
                          </td>

                          <td>
                            <input type="text" class="form-control" onclick="searchObat(<?php echo $row_rc->id?>)" value="<?php echo $row_rc->nama_brg?>" id="inputKeyObat_<?php echo $row_rc->id?>" name="inputKeyObat_<?php echo $row_rc->id?>">
                            <input type="hidden" class="form-control" value="<?php echo $row_rc->kode_brg?>" id="kode_brg_<?php echo $row_rc->id?>" name="kode_brg_<?php echo $row_rc->id?>">
                            Stok 128 Pcs @ 2,500/pcs <br>
                            Stok Cito 0 Pcs @ 2,750/pcs
                          </td>
                          <td align="center">
                            <span class="pull-left">
                              <input name="jml_dosis_<?php echo $row_rc->id?>" id="jml_dosis_<?php echo $row_rc->id?>" type="text" style="width: 40px; text-align: center" value="<?php echo $row_rc->jml_dosis?>"> x <input type="text" style="width: 40px; text-align: center" value="<?php echo $row_rc->jml_dosis_obat?>" name="jml_dosis_obat_<?php echo $row_rc->id?>" id="jml_dosis_obat_<?php echo $row_rc->id?>">
                            </span>
                            <span class="pull-right">
                              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'satuan_obat')), $row_rc->satuan_obat , 'satuan_obat_'.$row_rc->id.'', 'satuan_obat_'.$row_rc->id.'', '', '', 'style="margin-left: -2px; width: 80px"');?>
                              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anjuran_pakai_obat')), $row_rc->aturan_pakai , 'anjuran_pakai_'.$row_rc->id.'', 'anjuran_pakai_'.$row_rc->id.'', '', '', 'style="margin-left: -2px; width: 130px"');?>
                            </span>
                          </td>
                          <td align="left">
                            <span class="pull-left">
                              <?php $a = $row_rc->jml_dosis_obat * 7 ?>
                              <input type="text" class="form-control" style="width: 50px; text-align: center" value="<?php echo $a?>" name="jml_pesan_<?php echo $row_rc->id?>" id="jml_pesan_<?php echo $row_rc->id?>">
                            </span>
                            <label class="inline" style="padding-left: 5px">
                              <input type="checkbox" class="ace" name="jml_pesan_hold_<?php echo $row_rc->id?>" id="jml_pesan_hold_<?php echo $row_rc->id?>" value="1">
                              <span class="lbl"> hold</span>
                            </label>
                          </td>
                          <td align="left">
                            <span class="pull-left">
                              <?php $b = $row_rc->jml_dosis_obat * 23 ?>
                              <input type="text" class="form-control" style="width: 50px; text-align: center" value="<?php echo $b?>" name="jml_kronis_<?php echo $row_rc->id?>" id="jml_kronis_<?php echo $row_rc->id?>">
                            </span>
                            <label class="inline" style="padding-left: 5px">
                              <input type="checkbox" class="ace" name="jml_krnois_hold_<?php echo $row_rc->id?>" id="jml_krnois_hold_<?php echo $row_rc->id?>" value="1">
                              <span class="lbl"> hold</span>
                            </label>
                          </td>
                          <td width="50px">
                            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_iter')), 0 , 'jenis_iter_'.$row_rc->id.'', 'jenis_iter_'.$row_rc->id.'', '', '', '');?>
                          </td>
                          <td class="center" width="10px">
                            <div class="center">
                              <label class="pos-rel">
                                  <input type="checkbox" class="ace" name="is_cito_<?php echo $row_rc->id?>" id="is_cito_<?php echo $row_rc->id?>" value="<?php echo $row_rc->id?>"/>
                                  <span class="lbl"></span>
                              </label>
                            </div>
                          </td>
                          <td><input type="text" class="form-control" value="<?php echo $row_rc->keterangan?>" name="keterangan_resep_<?php echo $row_rc->id?>" id="keterangan_resep_<?php echo $row_rc->id?>"></td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>

                  <div id="div_detail_obat">
                    <div id="warning_stok_obat"></div>
                    <div id="detailObatHtml" style="margin-top: 5px"></div>
                    <div id="detailPembelianObatHtml" style="margin-top: 5px"></div>
                  </div>

                  <a href="#" class="btn btn-xs btn-primary">Proses Resep</a>
                  
              </div>

              <div id="messages" class="tab-pane fade">
                <p>
                  <span style="font-weight: bold">HASIL PENUNJANG TERAKHIR: </span><br>
                  <div style="padding: 3px; border: 1px solid #d4cfcf; margin-bottom: 5px">
                    <div id="hasil_penunjang" style="min-height: 20px; padding: 5px">
                      <?php $no=1; foreach($riwayat_penunjang as $row_rp) : $no++; if($no <= 6) : ?>
                        <a href="<?php echo base_url().'Templates/Export_data/export?type=pdf&flag=LAB&noreg='.$row_rp->no_registrasi.'&pm=547999&kode_pm='.$row_rp->kode_bagian_tujuan.'&no_kunjungan='.$row_rp->no_kunjungan.''?>" style="background: beige; padding: 5px;" target="_blank"><i class="fa fa-folder"></i> <?php echo $this->tanggal->formatDateDmy($row_rp->tgl_masuk); ?></a> 
                      <?php endif; endforeach;?>
                    </div>
                  </div>
                </p>
              </div>

            </div>
            
          </div>
        </div>

        <!-- datatable detail obat -->
        <div class="col-md-12">

          <!-- <table id="temp_data_pesan" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th class="center" width="50px"></th>
                <th class="center"></th>
                <th class="center"></th>
                <th class="center" width="100px"></th>
                <th width="30px">No</th>
                <th width="150px">Tgl Input</th>
                <th>Kode</th>
                <th>Deskripsi Item</th>
                <th width="100px">Jumlah</th>
                <th width="100px">Ditangguhkan</th>
                <th width="100px">Harga Satuan</th>
                <th width="100px">Sub Total</th>
                <th width="100px">Jasa R</th>
                <th width="100px">Total (Rp.)</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table> -->

          <!-- <hr>
          <b>RESEP RACIKAN</b>
          <table id="temp_data_obat_racikan" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th class="center" width="50px"></th>
                <th class="center" ></th>
                <th class="center"></th>
                <th class="center" width="100px"></th>
                <th>Tgl Input</th>
                <th>Kode</th>
                <th>Deskripsi Item</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Sub Total</th>
                <th>Jasa R</th>
                <th>Total (Rp.)</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table> -->

        </div>
        
      </div>

    </form>


  </div>

</div><!-- /.row -->

