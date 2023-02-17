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

var kode_trans_far = $('#kode_trans_far').val();

$(document).ready(function(){
    
    sum_total_biaya_farmasi();

    table = $('#temp_data_pesan').DataTable( {
        "processing": true, 
        "serverSide": true,
        "bInfo": false,
        "bPaginate": false,
        "searching": false,
        "bSort": false,
        "ajax": {
            "url": "farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=biasa&tipe_layanan="+$('#jenis_resep').val()+"",
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

    $('#inputKeyObat').focus();    

        
    
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
          $('#jumlah_pesan').focus();

        }
    });

    $( "#jumlah_pesan" )
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
    
})

function getDetailObatByKodeBrg(kode_brg,kode_bag){

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok="+$('#kode_kelompok').val()+"&kode_perusahaan="+$('#kode_perusahaan').val()+"&bag="+kode_bag+"&type=html&type_layan=Rajal", '' , function (response) {

    if(response.sisa_stok <= 0){
      $('#inputKeyObat').focus();
      $('#btn_submit').hide('fast');

      $('#jumlah_pesan').val('0');

      $('#warning_stok_obat').html('<div class="alert alert-danger"><b><i class="fa fa-exclamation-triangle"></i> Peringatan !</b> Stok sudah habis, silahkan lakukan permintaan ke gudang farmasi.</div>');
      $('#detailPembelianObatHtml').html('');
      $('input[name=prb_ditangguhkan][type=checkbox]').prop('checked',true);
      $('#prb_ditangguhkan').attr('readonly', true);
      $('input[name=resep_ditangguhkan][type=checkbox]').prop('checked',true);
      $('#resep_ditangguhkan').attr('readonly', true);

    }else{
      $('#btn_submit').show('fast');
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
      $('#jumlah_pesan').val(parseInt(obj.jumlah_tebus));
      $('#jumlah_tebus').val(parseInt(obj.jumlah_tebus));
      $('#harga_r').val(obj.jasa_r);

      /*radio*/
      $("input[name=urgensi][value="+obj.urgensi+"]").prop('checked', true);

      $('#dosis_start').val(obj.dosis_per_hari);
      $('#dosis_end').val(obj.dosis_obat);
      $('#aturan_pakai').val(obj.aturan_pakai_format);
      $('#bentuk_resep').val(obj.bentuk_resep);
      $('#satuan_obat').val(obj.satuan_obat);
      $('#anjuran_pakai').val(obj.anjuran_pakai);
      $('#catatan').val(obj.catatan_lainnya);
      $('#kd_tr_resep').val(obj.relation_id);

  })

}

function reload_table(){
  var kode_trans_far = $('#kode_trans_far').val();
  table.ajax.url("farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=biasa&tipe_layanan="+$('#flag_trans').val()+"").load();
  sum_total_biaya_farmasi();
}

function format_html ( data ) {
  return data.html;
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
        data: { ID : $('#kode_trans_far').val(), 'kode_pesan_resep' : $('#no_resep').val(), 'kode_kelompok' : $('#kode_kelompok').val(), 'kode_perusahaan' : $('#kode_perusahaan').val(), 'kode_profit' : $('#kode_profit').val(), 'nama_pasien' : $('#nama_pasien').val(), 'no_mr' : $('#no_mr').val(), 'submit': type, 'is_rollback' : $('#is_rollback').val() },
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
            $('#page-area-content').load('farmasi/Process_entry_resep/preview_entry/'+jsonResponse.kode_trans_far+'?flag='+$('#jenis_resep').val()+'');
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

function rollback_by_kode_trans_far(id, flag){
    preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/process_entry_resep/rollback_by_kode_trans_far',
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
            update_data(id);

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


$('#btn_racikan').click(function () {  
  var kode_kelompok = $('#kode_kelompok').val();
  show_modal('farmasi/Entry_resep_racikan/form/'+$('#kode_trans_far').val()+'?kelompok='+kode_kelompok+'&tipe_layanan='+$('#jenis_resep').val()+'', 'RESEP RACIKAN');
})


$('select[name="jenis_resep"]').change(function () {      
  
  // reset form with class
  $('.default_value').val('');
  $('#kode_trans_far').val('0');
  reload_table();
  
  if( $(this).val() == 'rj' ){
    // default value
    $('#form_by_jenis_resep').load('farmasi/Entry_resep_ri_rj/form_resep_rj');  
    $('#kode_profit').val(2000);
    $('#flag_trans').val( $(this).val().toUpperCase() );
    $('#div_pencarian_obat').hide();
    $('#div_table_riwayat_resep').hide('fast');
    
  }  

  if( $(this).val() == 'rl' || $(this).val() == 'pb' ){
    // default value
    $('#form_by_jenis_resep').show();
    $('#flag_trans').val( $(this).val().toUpperCase() );
    kode_profit = ( $(this).val() == 'rl') ? 3000 : 4000 ;
    $('#kode_profit').val(kode_profit);
    $('#div_pencarian_obat').hide();
    $('#div_table_riwayat_resep').show('fast');
    $('#div_table_riwayat_resep').load('farmasi/Entry_resep_ri_rj/riwayat_resep?type='+$(this).val()+'&profit='+$('#kode_profit').val()+'');
  }  

  if( $(this).val() == 'rk' ){
    // default value
    $('#form_by_jenis_resep').show();
    $('#kode_profit').val(4000);
    $('#flag_trans').val( $(this).val().toUpperCase() );
    $('#div_pencarian_obat').hide();
    $('#div_table_riwayat_resep').show('fast');
    $('#div_table_riwayat_resep').load('farmasi/Entry_resep_ri_rj/riwayat_resep?type='+$(this).val()+'&profit='+$('#kode_profit').val()+'');
  }  
});


</script>
<div class="col-xs-12">
  <div class="pull-left">
    <b>PENCARIAN OBAT</b><br>
    <small>Silahkan masukan obat pada form dibawah ini.</small>
  </div>
  <div class="pull-right">
    Total Biaya, <div style="font-size: 18px" id="td_total_biaya_farmasi"></div>
  </div>
</div>

<div class="col-xs-7" style="margin-top: 10px">
  
  <div class="form-group">
    <label class="control-label col-sm-2">Jenis</label>
    <div class="col-md-9">
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
    <div class="col-md-8">            
      <input type="text" name="obat" id="inputKeyObat" class="form-control" placeholder="Masukan Keyword Obat" value="">
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-2">Jml Pesan</label>
    <div class="col-md-2">
        <input class="form-control" name="jumlah_pesan" id="jumlah_pesan" type="text" style="text-align:center"/>
    </div>
  </div>

  <p style="padding-top: 10px">
    <b>FORM SIGNA</b><br><small>Masukan signa untuk etiket obat.</small>
  </p>

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
      <div class="col-md-1">
          <input class="form-control" name="catatan" id="catatan" type="text" style="width: 400px" value=""/>
      </div>
  </div>

  <div class="form-group" >
    <div class="col-md-12 no-padding" >
        <?php if( !isset($value->status_transaksi) || $value->status_transaksi == null) : ?>
          <button type="submit" id="btn_submit"  name="submit" class="btn btn-xs btn-primary" value="submit_detail">
            <i class="ace-icon fa fa-plus-circle icon-on-right bigger-110"></i>
            Tambahkan
          </button>
          <!-- <button type="button" id="btn_reset" onclick="reset_form()" name="submit" class="btn btn-xs btn-danger">
            <i class="ace-icon fa fa-times-circle icon-on-right bigger-110"></i>
            Batalkan
          </button> -->
          <button type="button" id="btn_racikan" class="btn btn-purple btn-xs">
                <span class="ace-icon fa fa-flask icon-on-right bigger-110"></span>
                Resep Racikan
          </button>

          <!-- <button type="button" id="btn_resep_selesai" class="btn btn-success btn-xs" name="submit" value="resep_selesai" onclick="resep_farmasi_selesai()">
                <span class="ace-icon fa fa-check-circle icon-on-right bigger-110"></span>
                Resep Selesai
          </button> -->

          <button type="button" id="btn_resep_selesai" class="btn btn-success btn-xs" name="submit" value="resep_selesai" onclick="resep_farmasi_selesai('ditunggu')">
                <span class="ace-icon fa fa-check-circle icon-on-right bigger-110"></span>
                Resep Selesai <b style="color: black">(Ditunggu)</b>
          </button>

          <!-- <button type="button" id="btn_resep_selesai_diantar" class="btn btn-success btn-xs" name="submit" value="resep_selesai_diantar" onclick="resep_farmasi_selesai('diantar')">
                <span class="ace-icon fa fa-check-circle icon-on-right bigger-110"></span>
                Resep Selesai <b style="color: black">(Diantar)</b>
          </button> -->


      <?php else : ?>
        <button type="button" id="btn_rollback" onclick="rollback_by_kode_trans_far(<?php echo isset($value->kode_trans_far) ?$value->kode_trans_far : ''?>, '<?php echo $flag?>')" class="btn btn-danger btn-xs" name="rollback" value="rollback">
            <span class="ace-icon fa fa-refresh icon-on-right bigger-110"></span>
            Rollback
        </button>
      <?php endif; ?>
    </div>
  </div> 
</div>

<div class="col-xs-5" id="div_detail_obat" style="display:none">
  <p><b>KETERANGAN STOK DAN OBAT</b></p>
  <div id="warning_stok_obat"></div>
  <div id="detailObatHtml"></div>
</div>

<div class="col-xs-12" style="margin-top: 10px">

  <table id="temp_data_pesan" class="table table-bordered table-hover">
    <thead>
      <tr>  
        <th class="center" width="50px"></th>
        <th class="center"></th>
        <th class="center"></th>
        <th class="center" width="100px"></th>
        <th width="30px">No</th>
        <th width="150px">Tgl Input</th>
        <th width="50px">Kode</th>
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
  </table>

</div>
