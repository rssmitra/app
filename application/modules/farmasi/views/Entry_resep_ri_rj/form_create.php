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

          // kode trans far
          $('#kode_trans_far').val(jsonResponse.kode_trans_far);
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

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok=<?php echo isset($value)?$value->kode_kelompok:0?>&bag="+kode_bag+"&type=html&type_layan=Rajal", '' , function (response) {
    if(response.sisa_stok <= 0){
      $('#inputKeyObat').focus();
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
  $('#harga_r').val(500);

  /*radio*/
  $("input[name=urgensi][value=biasa]").prop('checked', true);

  /*show detail tarif html*/
  $('#div_detail_obat').hide('fast');
  $('#detailObatHtml').html('');

}

function reload_table(){
  var kode_trans_far = $('#kode_trans_far').val();
  table.ajax.url("farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=biasa&tipe_layanan=<?php echo $tipe_layanan?>").load();
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

function sum_total_biaya_farmasi(){

  var kode_trans_far = $('#kode_trans_far').val();
  $.getJSON("<?php echo site_url('farmasi/process_entry_resep/get_total_biaya_farmasi') ?>/"+kode_trans_far, '' , function (response) {

      $('#td_total_biaya_farmasi').html('<b>Rp. '+formatMoney(response.total)+',-</b>');

  })

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
    $('#form_by_jenis_resep').load('farmasi/Entry_resep_ri_rj/form_resep_rj');  
    // default value
    $('#kode_profit').val(2000);
    $('#flag_trans').val( $(this).val().toUpperCase() );
    $('#div_pencarian_obat').hide('fast');
  }  

  if( $(this).val() == 'rl' || $(this).val() == 'pb' ){
    $('#form_by_jenis_resep').load('farmasi/Entry_resep_ri_rj/form_resep_luar');  
    // default value
    $('#flag_trans').val( $(this).val().toUpperCase() );
    $('#kode_profit').val(3000);
    $('#div_pencarian_obat').show('fast');
  }  

  if( $(this).val() == 'rk' ){
    $('#form_by_jenis_resep').load('farmasi/Entry_resep_ri_rj/form_resep_karyawan');  
    // default value
    $('#kode_profit').val(4000);
    $('#flag_trans').val( $(this).val().toUpperCase() );
    $('#div_pencarian_obat').show('fast');
  }  

});


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

    <!-- breadcrumbs -->
    <div class="page-header">  
      <h1>
        <?php echo $title?>        
        <small><i class="ace-icon fa fa-angle-double-right"></i><?php echo isset($breadcrumbs)?$breadcrumbs:''?></small>        
      </h1>
    </div>  
    
    <!-- form -->
    <form class="form-horizontal" method="post" id="form_entry_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/process_entry_resep/process">      
      
      <!-- form_hidden -->
      <input type="hidden" name="kd_tr_resep" id="kd_tr_resep" value="0">
      <input type="hidden" name="no_registrasi" value="0">
      <input type="hidden" name="no_kunjungan" id="no_kunjungan" class="form-control" value="0" >
      <input type="hidden" name="kode_bagian" value="060101" id="kode_bagian">
      <input type="hidden" name="kode_bagian_asal" value="060101">
      <input type="hidden" name="no_resep" id="no_resep" class="form-control" value="0" >
      <input type="hidden"  name="flag_resep" value="biasa">
      <input type="hidden" name="kode_perusahaan" id="kode_perusahaan" class="form-control" value="0" >
      <input type="hidden" name="kode_kelompok" id="kode_kelompok" class="form-control" value="0" >
      <input type="hidden" class="default_value" name="flag_trans" id="flag_trans" value="">
      <input type="hidden" class="default_value" name="no_mr" id="no_mr" value="">
      <input type="hidden" class="default_value" name="nama_pasien" id="nama_pasien" value="">
      <input type="hidden" class="default_value" name="kode_dokter" id="kode_dokter" value="0">
      <input type="hidden" class="default_value" name="dokter_pengirim" id="dokter_pengirim" value="0">
      <input type="hidden" class="default_value" name="kode_profit" id="kode_profit" value="">

      <!-- default form -->
      <div class="row">
        
        <div class="col-sm-12 no-padding">
          <div class="form-group">
            <label class="control-label col-sm-2">Jenis Resep</label>
            <div class="col-md-2">
              <select name="jenis_resep" id="jenis_resep" class=form-control>
                <option value="">-Silahkan Pilih-</option>
                <option value="rj">Pasien Rawat Jalan</option>
                <option value="rl">Resep Luar</option>
                <option value="pb">Pembelian Bebas</option>
                <option value="rk">Resep Karyawan</option>
              </select>
            </div>

            <label class="control-label col-sm-1">Tanggal</label>
            <div class="col-md-2">
              <div class="input-group">
                  <input name="tgl_resep" id="tgl_resep" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>">
                  <span class="input-group-addon">
                    <i class="ace-icon fa fa-calendar"></i>
                  </span>
                </div>
            </div>


          </div>
          <hr class="separator">
          <!-- onchange form jenis resep -->
          <div id="form_by_jenis_resep"></div>
        </div>

      </div>

      <!-- form pencarian obat -->
      <div class="row" id="div_pencarian_obat" style="display:none">

        <div class="col-xs-12 no-padding">
          <div class="pull-left">
            <button type="button" id="btn_racikan" class="btn btn-purple btn-xs">
                  <span class="ace-icon fa fa-plus-square icon-on-right bigger-110"></span>
                  Resep Racikan
            </button>

            <button type="button" id="btn_resep_selesai" class="btn btn-primary btn-xs" name="submit" value="resep_selesai" onclick="resep_farmasi_selesai()">
                  <span class="ace-icon fa fa-check-circle icon-on-right bigger-110"></span>
                  Resep Selesai
            </button>
          </div>
          <div class="pull-right">
            <div style="font-size: 18px" id="td_total_biaya_farmasi"></div>
          </div>
        </div>

        <div class="col-xs-7 no-padding" style="margin-top: 10px">
          <p><b>PENCARIAN OBAT</b></p>
          <div class="form-group">
            <label class="control-label col-sm-2">Kode</label>
            <div class="col-md-2">
              <input type="text" class="form-control" name="kode_trans_far" id="kode_trans_far" readonly>
            </div> 
          </div>
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
                  <input class="form-control" name="catatan" id="catatan" type="text" style="width: 400px" value="Minum secara rutin dan dihabiskan."/>
              </div>
          </div>

          <div class="form-group" style="margin-left: 8px">
            <label class="col-sm-2">&nbsp;</label>
            <div class="col-md-8" >
              <button type="submit" id="btn_submit"  name="submit" class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-plus-circle icon-on-right bigger-110"></i>
                Tambahkan
              </button>
              <button type="button" id="btn_reset" onclick="reset_form()" name="submit" class="btn btn-xs btn-danger">
                <i class="ace-icon fa fa-times-circle icon-on-right bigger-110"></i>
                Batalkan
              </button>
            </div>
          </div> 
        </div>
        
        <div class="col-xs-5 no-padding" id="div_detail_obat" style="display:none">
          <p><b>KETERANGAN STOK DAN OBAT</b></p>
          <div id="warning_stok_obat"></div>
          <div id="detailObatHtml"></div>
        </div>

        <div class="col-xs-12 no-padding" style="margin-top: 10px">

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
            <!-- <hr>
            <div><b>RESEP RACIKAN</b></div>
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
            </table> -->

        </div>

      </div>
        

    </form>

  </div>

</div><!-- /.row -->

