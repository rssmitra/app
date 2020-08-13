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
            $('#dosis_end').focus();
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
            $('#satuan_obat').focus();
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
            $('#dosis_start').focus();
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

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok="+$('#kode_kelompok').val()+"&bag="+kode_bag+"&type=html&type_layan=Rajal", '' , function (response) {

    if(response.sisa_stok <= 0){
      $('#inputKeyObat').focus();
      $('#btn_add_obat').hide('fast');
      $('#warning_stok_obat').html('<div class="alert alert-danger"><b><i>Stok sudah habis !</i></b></div>');
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
      $('#jumlah_pesan').val(parseInt(obj.jumlah_pesan));
      $('#jumlah_tebus').val(parseInt(obj.jumlah_tebus));
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

function update_data(kode_trans_far){

  preventDefault();
  $('#form_by_jenis_resep').show();
  $('#div_pencarian_obat').show();
  $('#div_table_riwayat_resep').hide();
  
  if( $('#jenis_resep').val() == 'rk' ){
    $('#form_by_jenis_resep').load('farmasi/Entry_resep_ri_rj/form_resep_karyawan');  
  }

  if( $('#jenis_resep').val() == 'rl' || $('#jenis_resep').val() == 'pb' ){
    $('#form_by_jenis_resep').load('farmasi/Entry_resep_ri_rj/form_resep_luar'); 
  }

  // load form 
  $('#div_default_form_entry').show();
  $('#div_default_form_entry').load('farmasi/Entry_resep_ri_rj/form_default_entry/'+kode_trans_far); 

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

function resep_farmasi_selesai(){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/process_entry_resep/process_selesai_resep',
        type: "post",
        data: { ID : $('#kode_trans_far').val(), 'kode_pesan_resep' : $('#no_resep').val(), 'kode_kelompok' : $('#kode_kelompok').val(), 'kode_perusahaan' : $('#kode_perusahaan').val() },
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
            PopupCenter('farmasi/Process_entry_resep/nota_farmasi/'+jsonResponse.kode_trans_far+'','Nota Farmasi', 530, 550);
            $('#page-area-content').load('farmasi/Entry_resep_ri_rj?flag=RJ');

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
    $('#form_by_jenis_resep').hide();
    $('#flag_trans').val( $(this).val().toUpperCase() );
    kode_profit = ( $(this).val() == 'rl') ? 3000 : 4000 ;
    $('#kode_profit').val(kode_profit);
    $('#div_pencarian_obat').hide();
    $('#div_table_riwayat_resep').show('fast');
    $('#div_table_riwayat_resep').load('farmasi/Entry_resep_ri_rj/riwayat_resep?type='+$(this).val()+'&profit='+$('#kode_profit').val()+'');
  }  

  if( $(this).val() == 'rk' ){
    // default value
    $('#form_by_jenis_resep').hide();
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
    <b>PENCARIAN OBAT</b>
  </div>
  <div class="pull-right">
    <div style="font-size: 18px" id="td_total_biaya_farmasi"></div>
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

  <p style="padding-top: 10px"><b>FORM SIGNA</b></p>

  <div class="form-group">
      <label class="control-label col-sm-2">Signa</label>
      <div class="col-md-10">

        <span class="input-icon">
          <input name="dosis_end" id="dosis_end" type="text" style="width: 50px;"/>
        </span>

        <span class="input-icon" style="padding-left: 4px">
          <i class="fa fa-times bigger-150"></i>
        </span>

        <span class="input-icon">
          <input name="dosis_start" id="dosis_start" type="text" style="width: 50px;"/>
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
          <input class="form-control" name="catatan" id="catatan" type="text" style="width: 400px" value="Minum secara rutin dan dihabiskan."/>
      </div>
  </div>

  <div class="form-group" style="margin-left: 8px">
    <label class="col-sm-2">&nbsp;</label>
    <div class="col-md-10" >
      <button type="submit" id="btn_submit"  name="submit" class="btn btn-xs btn-primary" value="submit_detail">
        <i class="ace-icon fa fa-plus-circle icon-on-right bigger-110"></i>
        Tambahkan
      </button>
      <button type="button" id="btn_reset" onclick="reset_form()" name="submit" class="btn btn-xs btn-danger">
        <i class="ace-icon fa fa-times-circle icon-on-right bigger-110"></i>
        Batalkan
      </button>
      <button type="button" id="btn_racikan" class="btn btn-purple btn-xs">
            <span class="ace-icon fa fa-flask icon-on-right bigger-110"></span>
            Resep Racikan
      </button>

      <button type="button" id="btn_resep_selesai" class="btn btn-success btn-xs" name="submit" value="resep_selesai" onclick="resep_farmasi_selesai()">
            <span class="ace-icon fa fa-check-circle icon-on-right bigger-110"></span>
            Resep Selesai
      </button>
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

</div>