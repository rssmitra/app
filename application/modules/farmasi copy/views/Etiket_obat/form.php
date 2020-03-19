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

    sum_total_biaya_farmasi();

    table = $('#temp_data_pesan').DataTable( {
        "processing": true, 
        "serverSide": true,
        "bInfo": false,
        "bPaginate": false,
        "searching": false,
        "ajax": {
            "url": "farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId=<?php echo $kode_pesan_resep?>&flag=biasa",
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
            var kode_brg = data[ 5 ];
                      

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
                $.getJSON("farmasi/Entry_resep_ri_rj/getDetail/" + kode_brg +'/'+ ID, '', function (data) {
                    response_data = data;
                    // Open this row
                    row.child( format_html( response_data ) ).show();
                    tr.addClass('shown');
                });
                                
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
        "ajax": {
            "url": "farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId=<?php echo $kode_pesan_resep?>&flag=racikan&tipe_layanan=<?php echo $tipe_layanan?>",
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

      },      

      uploadProgress: function(event, position, total, percentComplete) {        

      },      

      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $.achtung({message: jsonResponse.message, timeout:5});             

          /*reload table*/
          reload_table();
          /*sum total biaya farmasi*/
          sum_total_biaya_farmasi();
          /*renew form*/
          reset_form();

        }else{          

          $.achtung({message: jsonResponse.message, timeout:5});          

        }    

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
          console.log(val_item);

          var detailObat = getDetailObatByKodeBrg(val_item,'060101');
          $('#jumlah_pesan').focus();

        }
    });

    $( "#jumlah_pesan" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#aturan_pakai').focus();
          }
          return false;       
        }
    });

    $( "#aturan_pakai" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#bentuk_resep').focus();
          }
          return false;       
        }
    });

    $( "#bentuk_resep" )
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
            $('#btn_submit').focus();
          }
          return false;       
        }
    });


})

function getDetailObatByKodeBrg(kode_brg,kode_bag){

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok=<?php echo isset($value)?$value->kode_kelompok:0?>&bag="+kode_bag+"&type=html&type_layan=Rajal", '' , function (response) {
    if(response.sisa_stok <= 0){
      $('#btn_add_obat').hide('fast');
      $('#warning_stok_obat').html('<span style="color:red"><b><i>Stok sudah habis !</i></b></span>');
    }else{
      $('#btn_add_obat').show('fast');
      $('#warning_stok_obat').html('');
    }
    /*show detail tarif html*/
    $('#div_detail_obat').show('fast');
    $('#detailObatHtml').html(response.html);

    return response;

  })

}

function edit_obat_resep(kode_brg, kode_tr_resep){

  preventDefault();

  var kode_bag = $('#kode_bagian').val();

  $.getJSON("<?php echo site_url('farmasi/Entry_resep_ri_rj/getDetail') ?>/"+kode_brg+"/"+kode_tr_resep, '' , function (response) {

      getDetailObatByKodeBrg(kode_brg, kode_bag);
      var obj = response.resep_data;
      console.log(obj.kode_brg);
      /*show value form*/
      $('#inputKeyObat').val(kode_brg+' : '+obj.nama_brg);
      $('#jumlah_pesan').val(obj.jumlah_pesan);
      $('#jumlah_tebus').val(obj.jumlah_tebus);
      $('#harga_r').val(obj.jasa_r);

      /*radio*/
      $("input[name=urgensi][value="+obj.urgensi+"]").prop('checked', true);

      $('#aturan_pakai').val(obj.aturan_pakai_format);
      $('#bentuk_resep').val(obj.bentuk_resep);
      $('#anjuran_pakai').val(obj.anjuran_pakai);
      $('#catatan').val(obj.catatan_lainnya);
      $('#kd_tr_resep').val(obj.relation_id);

  })

}

function format_html ( data ) {
  return data.html;
}

function reset_form(){

  $('#inputKeyObat').focus();
  $('#form_entry_resep')[0].reset();
  $('#kd_tr_resep').val('0');

  $('#inputKeyObat').val('');
  $('#jumlah_pesan').val('');
  $('#jumlah_tebus').val('');
  $('#harga_r').val(500);

  /*radio*/
  $("input[name=urgensi][value=biasa]").prop('checked', true);

  $('#aturan_pakai').val('');
  $('#bentuk_resep').val('');
  $('#anjuran_pakai').val('');
  $('#catatan').val('');

   /*show detail tarif html*/
    $('#div_detail_obat').hide('fast');
    $('#detailObatHtml').html('');

}

function reload_table(){
  table.ajax.reload();
  table_racikan.ajax.reload();
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

function resep_farmasi_selesai(myid){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/process_entry_resep/process_selesai_resep',
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
            getMenu('farmasi/Entry_resep_ri_rj?flag='+$('#flag_trans').val()+'');
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

function sum_total_biaya_farmasi(){

  var no_resep = $('#no_resep').val();
  $.getJSON("<?php echo site_url('farmasi/process_entry_resep/get_total_biaya_farmasi') ?>/"+no_resep, '' , function (response) {

      $('#td_total_biaya_farmasi').html('<b>'+formatMoney(response.total)+'</b>');


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
        
    <form class="form-horizontal" method="post" id="form_entry_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/process_entry_resep/process">      
      
      <!-- form_hidden -->
      <input type="hidden" name="kd_tr_resep" id="kd_tr_resep" value="0">
      <input type="hidden" name="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
      <input type="hidden" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
      <input type="hidden" name="nama_pasien" value="<?php echo isset($value)?$value->nama_pasien:''?>">
      <input type="hidden" name="kode_dokter" value="<?php echo isset($value)?$value->kode_dokter:''?>">
      <input type="hidden" name="dokter_pengirim" value="<?php echo isset($value)?$value->nama_pegawai:''?>">
      <input type="hidden" name="kode_profit" value="<?php echo ($tipe_layanan=='RJ')?2000:1000;?>">
      <input type="hidden" name="kode_bagian" value="<?php echo isset($value)?$value->kode_bagian:''?>" id="kode_bagian">
      <input type="hidden" name="kode_bagian_asal" value="<?php echo isset($value)?$value->kode_bagian_asal:''?>">
      <input type="hidden" name="flag_trans" id="flag_trans" value="<?php echo $tipe_layanan?>">
      <input type="hidden" name="flag_resep" value="biasa">
      <input type="hidden" name="no_kunjungan" id="no_kunjungan" class="form-control" value="<?php echo isset($value)?ucwords($value->no_kunjungan):''?>" >
      <input type="hidden" name="no_resep" id="no_resep" class="form-control" value="<?php echo isset($value)?ucwords($value->kode_pesan_resep):''?>" >

      <div class="row">

        <div class="col-sm-12">
          <div class="col-xs-6">
            <h4><?php echo isset($value)?ucwords($value->no_mr):''?> - <?php echo isset($value)?ucwords($value->nama_pasien):''?></h4>
          </div>
          <div class="col-xs-6">
            <div class="pull-right">
              <a href="#" onclick="getMenu('farmasi/Entry_resep_ri_rj?flag=<?php echo $tipe_layanan?>')" class="btn btn-xs btn-default"><i class="fa fa-arrow-left"></i> Kembali ke Daftar Utama</a>
              <a href="#" onclick="resep_farmasi_selesai(<?php echo isset($value) ? $value->kode_pesan_resep : 0 ?>)" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Resep Selesai</a>
            </div>
          </div>
          <table class="table">
            <tr style="background-color:#3a95ec; color:white">
              <th width="70px">No Kunjungan</th>
              <th width="70px">Kode Pesan</th>
              <th width="120px">Tanggal Pesan</th>
              <th width="150px">Penjamin</th>
              <th width="150px">Asal Pasien</th>
              <th width="200px">Nama Dokter</th>
              <th width="70px" align="right">Total Biaya</th>
            </tr>
            <tr>
              <td> <?php echo isset($value)?ucwords($value->no_kunjungan):''?> </td>
              <td> <?php echo isset($value)?ucwords($value->kode_pesan_resep):''?> </td>
              <td> <?php echo isset($value)?ucwords($this->tanggal->formatDateTime($value->tgl_pesan)):''?> </td>
              <td> <?php echo isset($value)?ucwords($value->nama_kelompok):''?> <?php echo isset($value)?ucwords($value->nama_perusahaan):''?> </td>
              <td> <?php echo isset($value)?ucwords($value->nama_bagian):''?> </td>
              <td> <?php echo isset($value)?$value->nama_pegawai:''?> </td>
              <td align="right" id="td_total_biaya_farmasi"> <b>Rp.0,-</b>  </td>
            </tr>
          </table>
        </div>

        <div class="col-sm-7">
          <!-- Data Obat -->
          <p><b>DATA OBAT</b></p>

          <div class="form-group">
            <label class="control-label col-sm-2">Tanggal</label>
            
            <div class="col-md-3">
              <div class="input-group">
                  <input name="tgl_resep" id="tgl_resep" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
                  <span class="input-group-addon">
                    
                    <i class="ace-icon fa fa-calendar"></i>
                  
                  </span>
                </div>
            </div>

            <label class="control-label col-sm-2">Jenis</label>
            <div class="col-md-5">
              <div class="radio">
                  <label>
                    <input name="urgensi" type="radio" class="ace" value="cito" />
                    <span class="lbl"> Cito</span>
                  </label>

                  <label>
                    <input name="urgensi" type="radio" class="ace" value="biasa" checked/>
                    <span class="lbl"> Biasa</span>
                  </label>
              </div>
            </div> 

          </div>

          <!-- cari data pasien -->
          <div class="form-group">
            <label class="control-label col-sm-2">Cari Obat</label>            
            <div class="col-md-10">            
              <div class="input-group">
                  <input type="text" name="obat" id="inputKeyObat" class="form-control" placeholder="Masukan Keyword Obat" value="">
                  <span class="input-group-btn">
                    <button type="button" id="btn_racikan" class="btn btn-purple btn-sm" onclick="show_modal('<?php echo base_url().'farmasi/Entry_resep_racikan/form/'.$value->kode_pesan_resep.'?kelompok='.$value->kode_kelompok.'&tipe_layanan='.$tipe_layanan.''?>', 'RESEP RACIKAN')">
                      <span class="ace-icon fa fa-plus-square icon-on-right bigger-110"></span>
                      Resep Racikan
                    </button>

                  </span>

            </div>

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-sm-2">Jml Pesan</label>

            <div class="col-md-2">

                <input class="form-control" name="jumlah_pesan" id="jumlah_pesan" type="text" style="text-align:center" />

            </div>

            <!-- <label class="control-label col-sm-2">Jml Tebus</label>

            <div class="col-md-2">

                <input class="form-control" name="jumlah_tebus" id="jumlah_tebus" type="text" style="text-align:center"/>

            </div> -->

            <label class="control-label col-sm-2">Jasa R</label>

            <div class="col-md-2">

                <input class="form-control" name="harga_r" id="harga_r" type="text" value="500" readonly />

            </div>

          </div>

        </div>

        <div class="col-sm-5" style="display:none" id="div_detail_obat">
            <p><b>DATA OBAT</b></p>
            <div id="warning_stok_obat"></div>
            <div id="detailObatHtml"></div>
        </div>

      </div>

      <div class="row">

        <div class="col-md-12">

            <table id="temp_data_pesan" class="table table-bordered table-hover">
              <thead>
                <tr>  
                  <th class="center" width="50px"></th>
                  <th class="center"></th>
                  <th class="center"></th>
                  <th class="center" width="100px"></th>
                  <th width="150px">Tgl Input</th>
                  <th>Kode</th>
                  <th>Deskripsi Item</th>
                  <th>Jumlah</th>
                  <th>Harga Satuan</th>
                  <th>Sub Total</th>
                  <th>Jasa R</th>
                  <th>Total (Rp.)</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>

            <div style="margin-top: -12px"><b>RESEP RACIKAN</b></div>
            <table id="temp_data_obat_racikan" class="table table-bordered table-hover">
              <thead>
                <tr>  
                  <th class="center" width="50px"></th>
                  <th class="center"></th>
                  <th class="center"></th>
                  <th class="center"></th>
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
            </table>

        </div>

      </div>

    </form>


  </div>

</div><!-- /.row -->

