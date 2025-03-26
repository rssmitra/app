<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<link rel="stylesheet" href="assets/css/daterangepicker.min.css" />
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

    // show form copy resep
    $('#copy_resep_form').load('farmasi/E_resep_rj/copy_resep/'+$('#no_resep').val()+'?flag='+$('#flag_trans').val()+'');

    var kode_trans_far = $('#kode_trans_far').val();
    table = $('#temp_data_pesan').DataTable( {
        "processing": true, 
        "serverSide": true,
        "bInfo": false,
        "bPaginate": false,
        "searching": false,
        "bSort": false,
        "ajax": {
            "url": "farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=biasa&tipe_layanan=<?php echo $tipe_layanan?>",
            "type": "POST"
        },
        "drawCallback": function (response) { 
          // Here the response
            var objData = response.json;
            $('#txt_total_biaya_farmasi').text('Rp. '+formatMoney(objData.total_billing));
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
            { "visible": false, "targets": [ 3 ] },
        ],
    }); 

    $('#temp_data_pesan tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var data = table.row( $(this).parents('tr') ).data();
            var ID = data[ 1 ];
            var flag = data[ 2 ];
            var kode_brg = data[ 3 ];
                      

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
                  $.getJSON("farmasi/Entry_resep_ri_rj/getDetail/" + kode_brg +'/'+ ID, '', function (data) {
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
            "url": "farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=racikan&tipe_layanan=<?php echo $tipe_layanan?>",
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

    $('#inputKeyObat').focus();    

    $('#form_entry_resep').ajaxForm({      

      beforeSend: function() {        

        achtungShowLoader();    
        $('#btn_submit').attr('disabled', true);  

      },      

      uploadProgress: function(event, position, total, percentComplete) {        

      },      

      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $.achtung({message: jsonResponse.message, timeout:5});             

          /*renew form*/
          reset_form();

          // kode trans far
          $('#kode_trans_far').val(jsonResponse.kode_trans_far);

          /*reload table*/
          reload_table();
          /*sum total biaya farmasi*/
          
          
        }else{          

          $.achtung({message: jsonResponse.message, timeout:5, className:'achtungFail'});   
          $('#btn_submit').attr('disabled', false);       

        }    

        $('#btn_submit').attr('disabled', false);

        achtungHideLoader();  
        
      }      

    });     

    $('#inputKeyObat').typeahead({

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
          $('#inputKeyObat').val(label_item);
          var detailObat = getDetailObatByKodeBrg(val_item,'060101');

        },

    });

    $( "#jumlah_pesan" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#jml_23').focus();
          }
          return false;       
        }
    });

    // $( "#jumlah_tebus" )
    //   .keypress(function(event) {
    //     var keycode =(event.keyCode?event.keyCode:event.which); 
    //     if(keycode ==13){
    //       event.preventDefault();
    //       if($(this).valid()){
    //         $('#jml_23').focus();
    //       }
    //       return false;       
    //     }
    // });

    $( "#jml_23" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#dosis_start').focus();
          }
          return false;       
        }
    });

    $( "#dosis_start" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#dosis_end').focus();
          }
          return false;       
        }
    });

    $( "#dosis_end" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#satuan_obat').focus();
          }
          return false;       
        }
    });

    $( "#satuan_obat" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#anjuran_pakai').focus();
          }
          return false;       
        }
    });

    $( "#anjuran_pakai" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#catatan').focus();
          }
          return false;       
        }
    });

    $( "#catatan" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#btn_submit').click();
          }
          return false;       
        }
    });

    $('#resep_ditangguhkan').click(function() {
      if($('#sisa_stok').val() <= 0){
        if (!$(this).is(':checked')) {
          $('#jumlah_pesan').attr('disabled', true);
          $('#jumlah_pesan').val('0');
          $('#btn_submit').attr('disabled', true);
        }else{
          $('#jumlah_pesan').attr('disabled', false);
          $('#btn_submit').attr('disabled', false);
        }
      }
    });

    $('#prb_ditangguhkan').click(function() {
      if($('#sisa_stok').val() <= 0){
        if (!$(this).is(':checked')) {
          $('#btn_submit').attr('disabled', true);
        }else{
          $('#btn_submit').attr('disabled', false);
        }
      }
    });


})

function getDetailObatByKodeBrg(kode_brg,kode_bag,is_edit=''){

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok=<?php echo isset($value)?$value->kode_kelompok:0?>&kode_perusahaan="+$('#kode_perusahaan').val()+"&bag="+kode_bag+"&type=html&type_layan=Rajal&urgensi="+$().val()+"", '' , function (response) {
    $('#sisa_stok').val(response.sisa_stok);

    if(response.sisa_stok <= 0){
      $('#inputKeyObat').focus();
      // $('#btn_submit').attr('disabled', true);
      // $('#jumlah_pesan').attr('disabled', true);
      $('#info_stok').html('Stok kosong !').removeClass('green').addClass('red');
      $('#jumlah_pesan').val('0');
      $('#warning_stok_obat').html('<div class="alert alert-danger"><b><i class="fa fa-exclamation-triangle"></i> Peringatan !</b> Stok sudah habis, silahkan lakukan permintaan ke gudang farmasi.</div>');
      $('#detailPembelianObatHtml').html('');
      $('input[name=prb_ditangguhkan][type=checkbox]').prop('checked',true);
      $('#prb_ditangguhkan').attr('readonly', true);
      $('input[name=resep_ditangguhkan][type=checkbox]').prop('checked',true);
      $('#resep_ditangguhkan').attr('readonly', true);
    }else{
      $('#info_stok').html('<i class="fa fa-check green"></i> Stok tersedia ('+response.sisa_stok+' '+response.satuan_kecil+') !').removeClass('red').addClass('green');;
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

function edit_obat_resep(kode_brg, kode_tr_resep){

  preventDefault();

  var kode_bag = $('#kode_bagian').val();
  // show detail barang
  getDetailObatByKodeBrg(kode_brg, kode_bag, 1);
  $.getJSON("<?php echo site_url('farmasi/Entry_resep_ri_rj/getDetail') ?>/"+kode_brg+"/"+kode_tr_resep, '' , function (response) {

      var obj = response.resep_data;
      console.log(obj.kode_brg);
      /*show value form*/
      $('#kode_trans_far').val(obj.kode_trans_far);
      $('#inputKeyObat').val(kode_brg+' : '+obj.nama_brg);
      $('#jumlah_pesan').val(parseInt(obj.jumlah_pesan));
      $('#jumlah_tebus').val(parseInt(obj.jumlah_tebus));
      $('#jml_23').val(obj.jumlah_obat_23);
      $('#harga_r').val(obj.jasa_r);

      /*radio*/
      $("input[name=urgensi][value="+obj.urgensi+"]").prop('checked', true);

      if(obj.prb_ditangguhkan == 1){
        $('input[name=prb_ditangguhkan][type=checkbox]').prop('checked',true);
        $('#btn_submit').attr('disabled', false);
      }else{
        $('input[name=prb_ditangguhkan][type=checkbox]').prop('checked',false);
        $('#btn_submit').attr('disabled', true);
      }

      if(obj.resep_ditangguhkan == 1){
        $('input[name=resep_ditangguhkan][type=checkbox]').prop('checked',true);
        $('#btn_submit').attr('disabled', false);
      }else{
        $('input[name=resep_ditangguhkan][type=checkbox]').prop('checked',false);
        $('#btn_submit').attr('disabled', true);
      }
      
      $('#dosis_start').val(obj.dosis_per_hari);
      $('#dosis_end').val(obj.dosis_obat);
      $('#catatan').val(obj.catatan_lainnya);
      $('#satuan_obat').val(obj.satuan_obat);
      $('#anjuran_pakai').val(obj.anjuran_pakai);
      $('#kd_tr_resep').val(obj.relation_id);

  })
  
}

function format_html ( data ) {
  return data.html;
}

function reset_form(){

  $('#inputKeyObat').focus();
  // $('#form_entry_resep')[0].reset();
  $('#kd_tr_resep').val('0');

  $('#inputKeyObat').val('');
  $('#jumlah_pesan').val('');
  $('#jumlah_tebus').val('');
  $('#jml_23').val('');
  $('#harga_r').val(500);

  /*radio*/
  $("input[name=urgensi][value=biasa]").prop('checked', true);
  $('input[name=prb_ditangguhkan][type=checkbox]').prop('checked',false);
  $('input[name=resep_ditangguhkan][type=checkbox]').prop('checked',false);

  $('#dosis_start').val('');
  $('#dosis_end').val('');
  $('#catatan').val('');
  $('#satuan_obat').val('');
  $('#anjuran_pakai').val('');

   /*show detail tarif html*/
  // $('#div_detail_obat').hide('fast');
  $('#detailObatHtml').html('<img src="<?php echo base_url().'assets/img/no-data.png'?>" width="50%">');
  $('#detailPembelianObatHtml').html('');
}

$('#btn_racikan').click(function () {  
  show_modal('farmasi/Entry_resep_racikan/form/'+$('#kode_trans_far').val()+'?kelompok='+$('#kode_kelompok').val()+'&tipe_layanan='+$('#flag_trans').val()+'&kode_pesan_resep='+$('#no_resep').val()+'', 'RESEP RACIKAN');
})

function reload_table(){
  var kode_trans_far = $('#kode_trans_far').val();
  table.ajax.url("farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=biasa&tipe_layanan=<?php echo $tipe_layanan?>").load();
  // table_racikan.ajax.url("farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=racikan&tipe_layanan=<?php echo $tipe_layanan?>").load();
  
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

function resep_farmasi_selesai(){
  preventDefault();
  if(confirm('Are you sure?')){
    var post_data = { 
      'ID'                : $('#kode_trans_far').val(), 
      'kode_pesan_resep'  : $('#no_resep').val(), 
      'kode_kelompok'     : $('#kode_kelompok').val(), 
      'kode_perusahaan'   : $('#kode_perusahaan').val(), 
      'kode_profit'       : $('#kode_profit').val(), 
      'nama_pasien'       : $('#nama_pasien').val(), 
      'no_mr'             : $('#no_mr').val(), 
      'submit'            : $('#metode_pengambilan').val(), 
      'is_rollback'       : $('#is_rollback').val(),
      'perubahan_resep'   : $('#perubahan_resep').val(),
      'lampiran_lab'      : $('#lampiran_lab').val(),
      'verifikasi'        : $('#verifikasi').serializeArray(),
      'lampiran_memo_inhibitor'   : $('#lampiran_memo_inhibitor').val(),
    }
    $.ajax({
        url: 'farmasi/process_entry_resep/process_selesai_resep',
        type: "post",
        data: $('#form_entry_resep').serialize(),
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
            // $('#page-area-content').load('farmasi/Entry_resep_ri_rj?flag=RJ');

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

function verifikasi_resep(){
  // show modal
  $('#modal_form_verifikasi').modal();
}

function sum_total_biaya_farmasi(){
  var kode_trans_far = $('#kode_trans_far').val();
  $.getJSON("<?php echo site_url('farmasi/process_entry_resep/get_total_biaya_farmasi') ?>/"+kode_trans_far, '' , function (response) {
      $('#td_total_biaya_farmasi').html('<b>Rp. '+formatMoney(response.total)+',-</b>');
  })
}

function duplicate_input(id_input, duplicate_to){
  $('#'+duplicate_to).val( parseInt( $('#'+id_input).val() ) );
}

function rollback_resep_farmasi(id){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/process_entry_resep/rollback',
        type: "post",
        data: { ID : id },
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
            // show poup cetak resep
            $('#page-area-content').load('farmasi/Entry_resep_ri_rj/form/'+$('#no_resep').val()+'?mr='+$('#no_mr').val()+'&tipe_layanan='+$('#flag_trans').val()+'');

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

function select_item(id, tipe, parent=''){
  preventDefault();
  if(tipe == 'racikan'){
    show_modal('farmasi/Entry_resep_racikan/form/'+$('#kode_trans_far').val()+'?kelompok='+$('#kode_kelompok').val()+'&tipe_layanan='+$('#flag_trans').val()+'&kode_pesan_resep='+$('#no_resep').val()+'&no_kunjungan='+$('#no_kunjungan').val()+'&parent='+parent+'', 'RESEP RACIKAN');
  }else{
    $.getJSON("<?php echo site_url('farmasi/E_resep/getrowresep') ?>?ID="+id, '' , function (response) {
        getDetailObatByKodeBrg(response.kode_brg,'060101');
        // autofill
        $('#inputKeyObat').val(response.nama_brg);
        $('#jumlah_pesan').val(response.jml_pesan);
        $('#dosis_start').val(response.jml_dosis);
        $('#dosis_end').val(response.jml_dosis_obat);
        $('#satuan_obat').val(response.satuan_obat);
        $('#anjuran_pakai').val(response.aturan_pakai);
        $('#catatan').val(response.keterangan);
    })
  }
  
}

function checkAll(elm) {

  if($(elm).prop("checked") == true){
    $('.checked_verifikasi').each(function(){
        $(this).prop("checked", true);
    });
  }else{
    $('.checked_verifikasi').prop("checked", false);
  }

}

// perubahan resep
$('#perubahan_resep').click(function (e) {   
  if (($(this).is(':checked'))) {
    $('#perubahan_resep_dokter').show('fast');
  }  else{
    $('#perubahan_resep_dokter').hide('fast');
  }
});

// lampiran hasil lab
$('#lampiran_lab').click(function (e) {   
  if (($(this).is(':checked'))) {
    $('#hasil_penunjang_lab').show('fast');
  }  else{
    $('#hasil_penunjang_lab').hide('fast');
  }
});

</script>

<style type="text/css">
  .pagination{
    margin: 0px 0px !important;
  }
  .well{
    padding: 5px !important;
    margin-bottom: 5px !important;
  }
</style>

<form class="form-horizontal" method="post" id="form_entry_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/process_entry_resep/process">
    <!-- form_hidden -->
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
      <!-- keterangan pasien -->
      <div class="col-sm-12">
        <table class="table">
          <tr style="background-color: #edf3f4;">
            <td style="vertical-align: top; width: 180px">
              <span style="font-weight: bold !important">Nama Pasien :</span><br> 
              [<a href="#" onclick="getMenu('farmasi/Entry_resep_ri_rj/form/<?php echo $value->kode_pesan_resep?>?mr=<?php echo $value->no_mr?>&tipe_layanan=RJ')" style="font-weight: bold"><?php echo isset($value)?ucwords($value->no_mr):''?></a>]  <?php echo isset($value)?ucwords($value->nama_pasien):''?>        
            </td>
            
            <td style="vertical-align: top; width: 200px"> <span style="font-weight: bold !important">Kode/Tgl Pesan :</span><br> <?php echo isset($value)?ucwords($value->kode_pesan_resep):''?> - <?php echo isset($value)?ucwords($this->tanggal->formatDateTimeFormDmy($value->tgl_pesan)):''?></td>
            <td style="vertical-align: top"> <span style="font-weight: bold !important">Penjamin :</span><br> <?php echo isset($value)?ucwords($value->nama_kelompok):''?><br><?php echo isset($value)?ucwords($value->nama_perusahaan):''?> <?php echo isset($value->kode_perusahaan) ? ($value->kode_perusahaan == 120) ?'('.$value->no_sep.')' : '' :'';?></td>
            <td style="vertical-align: top; width: 300px"> <span style="font-weight: bold !important">Dokter :</span><br> <?php echo isset($value)?$value->nama_pegawai:''?><br><?php echo isset($value)?ucwords($value->nama_bagian):''?></td>
            <td style="vertical-align: top"><span style="font-weight: bold !important">Diagnosa Akhir :</span> <br><?php echo isset($value)?$value->diagnosa_akhir:''?> </td>
          </tr>
        </table>
        <?php if(isset($value) AND $value->less_then_min_visit==1) :?>
          <div class="alert alert-danger"><strong>Peringatan!</strong> Pasien kurang dari 30 hari kunjungan Pelayanan BPJS. Pasien berpotensi Gagal Rekam untuk Resep PRB/Kronis</div>
        <?php endif;?>
      </div>
      
      <!-- form utama -->
      <div class="col-sm-7">

        <div class="widget-box">
          <div class="widget-header">
              <span class="widget-title" style="font-size: 14px; font-weight: bold; color: black">Form Input Resep</span>
            <div class="widget-toolbar">
              <?php if($value->status_tebus != 1) :?>
                <button type="button" class="btn btn-success btn-xs" onclick="verifikasi_resep()">
                    <i class="fa fa-check"></i> Resep Selesai
                </button>
              <?php 
                else: 
                  if($trans_farmasi->kode_tc_trans_kasir == '') :
              ?>
                
                <button type="button" id="btn_rollback" onclick="rollback_resep_farmasi(<?php echo $value->kode_pesan_resep?>)" class="btn btn-danger btn-xs" name="rollback" value="rollback">
                    <span class="ace-icon fa fa-refresh icon-on-right bigger-110"></span>
                    Rollback
                </button>
                <!-- <span class="ace-icon fa fa-check-circle icon-on-right bigger-150 green"></span> -->
                  <?php else: echo '<span style="color: green;font-weight: bold;font-size: 26px;border: 1px solid;border-style: dashed; vertical-align: middle; padding: 2px 10px">Lunas'; endif; endif; ?>

            </div>
          </div>
          <div class="widget-body" style="padding:5px; min-height: 278px !important" >
            <!-- Data Obat -->
            <p><b>FORM OBAT</b></p>

            <div class="form-group">
              <label class="control-label col-sm-2">Kode</label>
              <div class="col-md-2">
              <input type="text" class="form-control" name="kode_trans_far" id="kode_trans_far" value="<?php echo isset($trans_farmasi->kode_trans_far)?$trans_farmasi->kode_trans_far:''?>" readonly>
              </div> 
              <label class="control-label col-sm-1">Tanggal</label>
              <div class="col-md-4">
                <div class="input-group">
                    <input name="tgl_trans" id="tgl_trans" data-date-format="yyyy-mm-dd" placeholder="<?php echo date('Y-m-d')?>" class="form-control date-picker" type="text" value="<?php echo isset($trans_farmasi->tgl_trans) ? $trans_farmasi->tgl_trans : date('Y-m-d H:i:s'); ?>">
                    <span class="input-group-addon">
                      <i class="ace-icon fa fa-calendar"></i>
                    </span>
                  </div>
              </div>
              <label class="control-label col-sm-1">Iter</label>
              <div class="col-md-2">
                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_iter')), isset($trans_farmasi->iter) ? $trans_farmasi->iter : 0 , 'jenis_iter', 'jenis_iter', '', '', '');?>
              </div> 
            </div>


            <!-- cari obat -->
            <div class="form-group">
              <label class="control-label col-sm-2">Cari Obat</label>            
              <div class="col-md-6">            
                <input type="text" name="obat" id="inputKeyObat" class="form-control" placeholder="Masukan Minimal 3 Karakter" value="">
              </div>
              <div class="help-block col-xs-12 col-sm-reset inline blink" style="font-weight: bold" id="info_stok"></div>
            </div>

            <!-- Jenis Obat -->
            <div class="form-group">
              <label class="control-label col-sm-2">Jenis</label>
              <div class="col-md-5">
                <div class="radio">
                    <label>
                      <input name="urgensi" type="radio" class="ace" value="cito" onclick="changeUrgensi()" />
                      <span class="lbl"> Cito</span>
                    </label>

                    <label>
                      <input name="urgensi" type="radio" class="ace" value="biasa" onclick="changeUrgensi()" checked/>
                      <span class="lbl"> Biasa</span>
                    </label>
                </div>
              </div> 

            </div>

            <!-- jumlah pesan -->
            <div class="form-group">
              <label class="control-label col-sm-2">Jml Tebus</label>
              <div class="col-md-2">
                  <input class="form-control" name="jumlah_pesan" id="jumlah_pesan" type="number" style="text-align:center; width: 60px !important" onchange="duplicate_input('jumlah_pesan','jumlah_tebus')" value=""/>
              </div>
              <div class="col-md-2">
                <label class="inline" style="margin-top: 4px;margin-left: -20px;">
                  <input type="checkbox" class="ace" name="resep_ditangguhkan" id="resep_ditangguhkan" value="1">
                  <span class="lbl"> Ditangguhkan</span>
                </label>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2">Resep Kronis</label>
              <div class="col-md-2">
                  <input class="form-control" name="jml_23" id="jml_23" type="number" value="" style="text-align:center; width: 60px !important" <?php echo ($value->kode_perusahaan==120) ? '' : 'readonly'?> />
              
              </div>
              <div class="col-md-2">
                <label class="inline" style="margin-top: 4px;margin-left: -20px;">
                  <input type="checkbox" class="ace" name="prb_ditangguhkan" id="prb_ditangguhkan" value="1" <?php echo ($value->kode_perusahaan==120) ? '' : 'disabled'?>>
                  <span class="lbl"> Ditangguhkan</span>
                </label>
              </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Signa</label>
                <div class="col-md-10">

                  <span class="input-icon">
                    <input name="dosis_start" id="dosis_start" type="text" style="width: 50px;"/>
                  </span>

                  <span class="input-icon" style="padding-left: 4px">
                    <i class="fa fa-times bigger-150"></i>
                  </span>

                  <span class="input-icon">
                    <input name="dosis_end" id="dosis_end" type="text" style="width: 50px;"/>
                  </span>

                  <span class="input-icon">
                    <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'satuan_obat')), 'TAB' , 'satuan_obat', 'satuan_obat', '', '', 'style="margin-left: -2px"');?>
                  </span>

                  <span class="input-icon">
                    <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anjuran_pakai_obat')), 'Sesudah Makan' , 'anjuran_pakai', 'anjuran_pakai', '', '', 'style="margin-left: -2px"');?>
                  </span>

                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Catatan</label>
                <div class="col-md-10">
                    <input class="form-control" name="catatan" id="catatan" type="text" style="width: 400px" value=""/>
                </div>
            </div>
            <?php if($value->status_tebus != 1) :?>
            <div class="form-group">
                <label class="col-sm-2">&nbsp;</label>
                <div class="col-md-8" style="margin-left: 4px">
                  <button type="submit" id="btn_submit"  name="submit" class="btn btn-xs btn-primary">
                      <i class="ace-icon fa fa-plus icon-on-right bigger-110"></i>
                      Tambahkan Obat
                  </button>
                  <button type="button" id="btn_racikan" class="btn btn-purple btn-xs">
                    <span class="ace-icon fa fa-flask icon-on-right bigger-110"></span>
                    Resep Racikan
                  </button>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- datatable detail obat -->
            <table id="temp_data_pesan" class="table table-bordered table-hover">
              <thead>
                <tr style="background: #edf3f4;">  
                  <th class="center" width="30px"></th>
                  <th class="center"></th>
                  <th class="center"></th>
                  <th class="center"></th>
                  <th class="center" width="80px"></th>
                  <th width="30px">No</th>
                  <!-- <th width="150px">Tgl Input</th> -->
                  <!-- <th>Kode</th> -->
                  <th>Nama Obat</th>
                  <th width="80px">Jumlah</th>
                  <!-- <th width="100px">Ditangguhkan</th> -->
                  <th width="100px">Harga Satuan</th>
                  <!-- <th width="100px">Sub Total</th> -->
                  <th width="80px">Jasa R</th>
                  <th width="100px">Total (Rp.)</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            <div style="text-align: right; width: 100%">
              Total Biaya Farmasi.<br>
              <span style="font-size: 25px !important; font-weight: bold" id="txt_total_biaya_farmasi"></span>
            </div>

          </div>
        </div>
        
      </div>

      <!-- detail selected obat -->
      <div class="col-sm-5">
        
        <div class="tabbable">
          <ul class="nav nav-tabs" id="myTabInfoDetailFarmasi">
            <li class="active">
              <a data-toggle="tab" href="#tab_eresep">
                e-Resep
              </a>
            </li>

            <li>
              <a data-toggle="tab" href="#tab_riwayat_pm">
                Penunjang Medis
              </a>
            </li>

            <li>
              <a data-toggle="tab" href="#tab_detail_obat">
                Detail Obat
              </a>
            </li>

            <li>
              <a data-toggle="tab" href="#tab_riwayat_pemberian_obat">
                Riwayat Pemberian Obat
              </a>
            </li>

          </ul>

          <div class="tab-content">

            <div id="tab_eresep" class="tab-pane fade in active">
              <?php
                // echo "<pre>"; print_r($eresep_result);die;
                if(isset($eresep[0]->kode_pesan_resep)) : 
                  
                $html = '';
                  $html .= '<div><b>RESEP DOKTER <i>(e-Resep)</i></b><br>';
                  $html .= isset($value)?ucwords($value->nama_bagian).' - ':'';
                  $html .= isset($value)?$value->nama_pegawai:'';
                  $html .= ' - Tanggal. '.$this->tanggal->formatDateTime($eresep[0]->created_date).'';
                  $html .= '</div>';
                  $html .= '<table class="table" id="dt_add_resep_obat">
                    <thead>
                    <tr style="background: #edf3f4;">
                        <th width="30px" class="center">No</th>
                        <th>Nama Obat</th>
                        <th>Signa</th>
                        <th class="center">Qty</th>
                        <th class="center">#</th>
                    </tr>
                    </thead>
                    <tbody style="background: white">';
                    $no = 0;
                    
                    foreach ($eresep as $ker => $ver) {
                      $is_free_text = ($ver->kode_brg == null)?'<br><span style="font-weight: bold; color: red">[free text]</span>':'';
                      $no++;
                      // get child racikan
                      $child_racikan = $this->master->get_child_racikan_data($ver->kode_pesan_resep, $ver->kode_brg);
                      $html_racikan = ($child_racikan != '') ? '<br><div style="padding:10px"><span style="font-size:11px; font-style: italic">bahan racik :</span><br>'.$child_racikan.'</div>' : '' ;
                      $html .= '<tr>';
                      $html .= '<td align="center" valign="top">'.$no.'</td>';
                      $html .= '<td>'.strtoupper($ver->nama_brg).''.$html_racikan.''.$is_free_text.'</td>';
                      $html .= '<td>'.$ver->jml_dosis.' x '.$ver->jml_dosis_obat.' '.$ver->satuan_obat.'<br>'.$ver->aturan_pakai.'</td>';
                      $html .= '<td>'.$ver->jml_pesan.' '.$ver->satuan_obat.'</td>';
                        $html .= '<td align="center" valign="top"><a onclick="select_item('."'".$ver->id."'".','."'".$ver->tipe_obat."'".', '."'".$ver->kode_brg."'".')" class="btn btn-xs btn-success"><i class="fa fa-check"></i></a></td>';
                      $html .= '</tr>';

                    }

                    $html .= '</tbody></table>';
                
                echo $html;
                else :
                  echo "<div class='alert alert-warning'><strong>Tidak ada resep</strong><br>Dokter belum menginput resep kedalam sistem, mohon cek resep manual.</div>";
                endif;
              ?>
              <hr>
              <!-- copy resep -->
                <div id="copy_resep_form"></div>

            </div>

            <div id="tab_riwayat_pm" class="tab-pane fade">
              <p style="font-weight: bold">Riwayat Pemeriksaan Penunjang Medis</p>
              <table class="table table-bordered table-hover">
                <thead>
                  <tr style="background: #edf3f4;">  
                    <th width="30px">No</th>
                    <th>Pemeriksaan Penunjang</th>
                    <th width="30px">Hasil</th>
                  </tr>
                </thead>
                <tbody style="background: white">
                  <?php 
                    $no=0; 
                    $data_lab = isset($penunjang['laboratorium'])?$penunjang['laboratorium']:[];
                    foreach($data_lab as $key_p=>$row_p) : 
                      if($key_p <= 9) :
                        $no++;
                  ?>
                  <tr>
                    <td align="center"><?php echo $no; ?></td>
                    <td>
                      <?php 
                        echo '<b>'.$this->tanggal->formatDateTime($row_p->tgl_daftar).'</b><br>';
                        $arr_str = explode("|",$row_p->nama_tarif);
                        $html_pm = '<ul class="no-padding">';
                        foreach ($arr_str as $key => $value) {
                            if(!empty($value)){
                                $html_pm .= '<li>'.$value.'</li>';
                            }
                        }
                        $html_pm .= '</ul>';
                        echo $html_pm;
                      ?>
                    </td>
                    <td align="center"><a href="#" class="btn btn-xs btn-warning" onclick="show_modal_medium_return_json('registration/reg_pasien/form_modal_view_hasil_pm/<?php echo $row_p->no_registrasi?>/<?php echo $row_p->no_kunjungan?>/<?php echo $row_p->kode_penunjang?>/<?php echo $row_p->kode_bagian_tujuan?>?format=html', 'Hasil Penunjang Medis')"><i class="fa fa-eye"></i></a></td>
                  </tr>
                  <?php endif; endforeach; ?>
                </tbody>
              </table>
            </div>

            <div id="tab_detail_obat" class="tab-pane fade">
              <div id="detailObatHtml" style="margin-top: 5px">
                <div class="alert alert-warning">Silahkan cari Nama Obat terlebih dahulu.</div>
              </div>
            </div>

            <div id="tab_riwayat_pemberian_obat" class="tab-pane fade">
              <div id="detailPembelianObatHtml" style="margin-top: 5px">
                <div class="alert alert-warning">Silahkan cari Nama Obat terlebih dahulu.</div>
              </div>
            </div>
          </div>
        </div>

      </div>
      
    </div>

</form>
<!-- MODAL SEARCH PASIEN -->