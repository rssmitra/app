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

    var reload_by_id = $('#id_tc_far_racikan').val();

    if( reload_by_id != '#' || reload_by_id != '0'){
      show_selected_item_racikan( reload_by_id );
    }

    table_detail_item_racikan = $('#temp_data_racikan').DataTable( {
        "processing": true, 
        "serverSide": true,
        "bInfo": false,
        "bPaginate": false,
        "searching": false,
        "ajax": {
            "url": "farmasi/Entry_resep_racikan/get_data?id="+reload_by_id,
            "type": "POST"
        },
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

          $('#inputKeyObatRacikan').focus();
          /*show detail tarif html*/
          reset_search_obat();
          reload_table_racikan();
          /*reload select racikan*/
          reload_item_racikan( jsonResponse.data.id_tc_far_racikan );
          show_selected_item_racikan(jsonResponse.data.id_tc_far_racikan);
          
        }else{          

          $.achtung({message: jsonResponse.message, timeout:5});          

        }    

         achtungHideLoader();
         sum_total_biaya_farmasi();
      }      

    });     

    

    $('select[name="select_racikan"]').change(function () {      

        if( $(this).val() != '' || $(this).val() != '0' ){

          show_selected_item_racikan($(this).val());
          $('#id_tc_far_racikan').val(0);

        }else{

          renew_form();

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
          console.log(val_item);

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
            $('#aturan_pakai_racikan').focus();
          }
          return false;       
        }

    });

    $( "#aturan_pakai_racikan" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#bentuk_resep_racikan').focus();
          }
          return false;       
        }
    });

    $( "#bentuk_resep_racikan" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#anjuran_pakai_racikan').focus();
          }
          return false;       
        }
    });

    $( "#anjuran_pakai_racikan" )
    .keypress(function(event) {
      var keycode =(event.keyCode?event.keyCode:event.which); 
      if(keycode ==13){
        event.preventDefault();
        if($(this).valid()){
          $('#catatan_racikan').focus();
        }
        return false;       
      }
    });

    $( "#catatan_racikan" )
    .keypress(function(event) {
      var keycode =(event.keyCode?event.keyCode:event.which); 
      if(keycode ==13){
        event.preventDefault();
        if($(this).valid()){
          var id_tc_far_racikan = $('#id_tc_far_racikan').val();
          if( id_tc_far_racikan == 0 ){
            $('#btn_submit_racikan').focus();
          }else{
            $('#btn_update_header_racikan').focus();              
          }
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
            var id_tc_far_racikan = $('#id_tc_far_racikan').val();
            if( id_tc_far_racikan == 0 ){
              $('#btn_submit_obat').focus();
            }else{
              $('#btn_submit_obat').focus();              
            }
          }
          return false;       
        }

    });


})

function getDetailObatByKodeBrgRacikan(kode_brg,kode_bag){

  var type_layan = $('#tipe_layanan').val();
  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok=<?php echo isset($kode_kelompok)?$kode_kelompok:0?>&bag="+kode_bag+"&type=html&type_layan="+type_layan+"", '' , function (response) {
    if(response.sisa_stok <= 0){
      $('#btn_add_obat_racikan').hide('fast');
      $('#warning_stok_obat_racikan').html('<span style="color:red"><b><i>Stok sudah habis !</i></b></span>');
    }else{
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
  table_detail_item_racikan.ajax.url('farmasi/Entry_resep_racikan/get_data?id='+id_tc_far_racikan+'').load();
  table.ajax.reload();
  table_racikan.ajax.reload();
  sum_total_biaya_farmasi();
  
}

function show_selected_item_racikan(id_tc_far_racikan){

  if(id_tc_far_racikan != '0'){
      $.getJSON("<?php echo site_url('farmasi/Entry_resep_racikan/get_resep_racikan_by_id') ?>/" + id_tc_far_racikan, '', function (data) {              

          $('#id_tc_far_racikan').val(data.id_tc_far_racikan);
          $('#nama_racikan').val(data.nama_racikan);
          $('#jml_racikan').val(data.jml_content);
          $('#satuan_racikan').val(data.satuan_kecil);
          $('#jasa_r_racikan').val(data.jasa_r);
          $('#jasa_prod_racikan').val(data.jasa_produksi);
          $('#aturan_pakai_racikan').val(data.aturan_pakai_format);
          $('#catatan_racikan').val(data.catatan_aturan_pakai);

          /*show form obat*/  
          $('#data_obat_div').show('fast');
          $('#result_data_racikan_div').show('fast');
          $('#data_racikan_div').hide('fast');
          $('#data_aturan_pakai_div').hide('fast');

          /*fill table value*/
          $('#kode_r').text(data.id_tc_far_racikan);
          $('#nama_r').text(data.nama_racikan.toUpperCase());
          $('#jml_r').text(data.jml_content+' '+data.satuan_kecil);
          $('#aturan_pakai_r').text(data.aturan_pakai);
          $('#catatan_r').text(data.catatan_aturan_pakai);
          $('#petugas_r').text('admin');
          /*btn action*/
          $('#btn_delete_racikan').attr('onclick', 'delete_racikan('+id_tc_far_racikan+', "'+'racikan'+'")');
          $('#btn_submit_racikan_selesai').attr('onclick', 'process_selesai('+id_tc_far_racikan+')');

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

  var kode_pesan_resep = $('#kode_pesan_resep').val();

  $.getJSON("<?php echo site_url('farmasi/Entry_resep_racikan/get_item_racikan') ?>/" + kode_pesan_resep, '', function (data) {              

      $('#select_racikan option').remove();                

      $('<option value="0">-Silahkan Pilih-</option>').appendTo($('#select_racikan'));                           

      $.each(data, function (i, o) {    

          if(o.id_tc_far_racikan===id_tc_far_racikan){
            var selected = "selected";
          }else{
            var selected = "";
          }

          $('<option value="' + o.id_tc_far_racikan + '" '+selected+' >' + o.nama_racikan.toUpperCase() + '</option>').appendTo($('#select_racikan'));                    
            
      });      


  });

  return false;

}


function delete_item_obat_racikan(id_tc_far_racikan_detail){

  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/Entry_resep_racikan/delete_obat',
        type: "post",
        data: {ID:id_tc_far_racikan_detail},
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
            $('#id_tc_far_racikan').val(' ');
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
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();

      }

    });
  
}

function btn_update_racikan(){

  var id_tc_far_racikan = $('#id_tc_far_racikan').val();

  $.getJSON("<?php echo site_url('farmasi/Entry_resep_racikan/get_resep_racikan_by_id') ?>/" + id_tc_far_racikan, '', function (data) {              

        $('#id_tc_far_racikan').val(data.id_tc_far_racikan);
        $('#nama_racikan').val(data.nama_racikan);
        $('#jml_racikan').val(data.jml_content);
        $('#satuan_racikan').val(data.satuan_kecil);
        $('#jasa_r_racikan').val(data.jasa_r);
        $('#jasa_prod_racikan').val(data.jasa_produksi);
        $('#aturan_pakai_racikan').val(data.aturan_pakai_format);
        $('#catatan_racikan').val(data.catatan_aturan_pakai);

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
        
        <input type="hidden" id="id_tc_far_racikan" name="id_tc_far_racikan" value="<?php echo isset($_GET['id_tc_far_racikan'])?$_GET['id_tc_far_racikan']:0?>">
        <input type="hidden" id="kode_pesan_resep" name="kode_pesan_resep" value="<?php echo $kode_pesan_resep; ?>">
        <input type="hidden" id="kode_trans_far" name="kode_trans_far" value="<?php echo $kode_trans_far; ?>">
        <input type="hidden" id="tipe_layanan" name="tipe_layanan" value="<?php echo $tipe_layanan; ?>">

        <div class="row">


          <div class="col-sm-10">
            <div class="form-group">
              <label class="control-label col-sm-2">Pilih Racikan</label>
              <div class="col-md-6">
                   <?php echo $this->master->custom_selection($params = array('table' => 'tc_far_racikan', 'id' => 'id_tc_far_racikan', 'name' => 'nama_racikan', 'where' => array('kode_pesan_resep' => $kode_pesan_resep)), isset($_GET['id_tc_far_racikan'])?$_GET['id_tc_far_racikan']:'' , 'select_racikan', 'select_racikan', 'form-control', '', '') ?>
              </div>
            </div>            
          </div>

          <div class="col-sm-2">
           <div class="pull-right"><a href="#" onclick="" id="btn_submit_racikan_selesai" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Racikan Selesai</a></div>
          </div>

          <div class="col-sm-12" id="result_data_racikan_div" style="display: none">
           
            <br>
            <table class="table">
              <tr style="background-color:#3a95ec; color:white">
                <th>Kode</th>
                <th>Nama Racikan</th>
                <th>Jumlah</th>
                <th>Aturan Pakai</th>
                <th>Catatan</th>
                <th>Petugas</th>
                <th width="150px" align="center"></th>
              </tr>
              <tr>
                <td id="kode_r">-</td>
                <td id="nama_r">-</td>
                <td id="jml_r">-</td>
                <td id="aturan_pakai_r">-</td>
                <td id="catatan_r">-</td>
                <td id="petugas_r">-</td>
                <td align="left">

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

          <div class="col-sm-10" id="data_racikan_div">

              <p><b>DATA RACIKAN</b></p>

              <div class="form-group">
                <label class="control-label col-sm-2">Racikan</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="nama_racikan" id="nama_racikan">  
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2">Satuan</label>
                <div class="col-md-3">
                    <select name="satuan_racikan" id="satuan_racikan" class="form-control">
                      <option value="TAB">Tablet</option>
                      <option value="KAP">Kapsul</option>
                      <option value="BKS">Bungkus</option>
                      <option value="ML">Ml</option>
                    </select> 
                </div>
                <label class="control-label col-sm-2">Jml Satuan</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="jml_racikan" id="jml_racikan" >  
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2">Jasa Prod</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="jasa_prod_racikan" id="jasa_prod_racikan" value="2000">  
                </div>
                <label class="control-label col-sm-2">Jasa R</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="jasa_r_racikan" id="jasa_r_racikan" value="500">  
                </div>
              </div>

          </div>

          <!-- <div class="col-sm-5" id="data_aturan_pakai_div" >

            <p><b>ATURAN PAKAI</b></p>

            <div class="form-group">
              <label class="control-label col-sm-2">Aturan Pakai</label>
              <div class="col-md-3">
                  <span class="help-inline">
                    <input style="width:80px;text-align:center" name="aturan_pakai_racikan" id="aturan_pakai_racikan" type="text" />
                  </span>
              </div>

              <div class="col-md-3" style="margin-left:-6% !important">
                <select name="bentuk_resep_racikan" id="bentuk_resep_racikan">
                  <option value="TAB">Tablet</option>
                  <option value="KAP">Kapsul</option>
                  <option value="BKS">Bungkus</option>
                  <option value="ML">Ml</option>
                </select>
              </div>

              <div class="col-md-3" style="margin-left:-7% !important">
                <select name="anjuran_pakai_racikan" id="anjuran_pakai_racikan">
                  <option value="Sesudah Makan">Sesudah Makan</option>
                  <option value="Sebelum Makan">Sebelum Makan</option>
                  <option value="Bersama">Bersama</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-12" style="margin-bottom:5px; margin-left:110px">
                <small>Format : 3 * 1 * 1 => [ <b>3</b> x <b>1</b> hari <b>1</b> TAB ]</small>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2">Catatan</label>
              <div class="col-md-8">
                <textarea name="catatan_racikan" class="form-control" id="catatan_racikan" placeholder="ex : Antibiotik / Harus dihabiskan / Waktu Pakai" style="height:50px !important"></textarea>
              </div>
            </div> 

            <div class="form-group" style="margin-top:3px">
              <label class="control-label col-sm-2">&nbsp;</label>
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

          </div> -->


          <div class="col-sm-9" id="data_obat_div" style="display: none" >
            <!-- Data Obat -->
            <p><b>DATA OBAT</b></p>

            <!-- form hidden for this section -->
            <input type="hidden" name="id_tc_far_racikan_detail" id="id_tc_far_racikan_detail" value="0">

            <div class="form-group">
              <label class="control-label col-sm-2">Jenis</label>
              <div class="col-md-5">
                <div class="radio">
                    <label>
                      <input name="urgensi" type="radio" class="ace" value="Cito" />
                      <span class="lbl"> Cito</span>
                    </label>

                    <label>
                      <input name="urgensi" type="radio" class="ace" value="Biasa" checked/>
                      <span class="lbl"> Biasa</span>
                    </label>
                </div>
              </div> 

            </div>

            <!-- cari data pasien -->
            <div class="form-group">
              <label class="control-label col-sm-2">Cari Obat</label>  
              <div class="col-md-8">   
               <input type="text" name="obat" id="inputKeyObatRacikan" class="form-control" placeholder="Masukan Keyword Obat" value=""> 
              </div>
            </div>

            <div class="form-group" style="display:none" id="div_detail_obat_racikan">
                <div class="col-sm-12">
                  <div id="warning_stok_obat_racikan"></div>
                   <div id="detailObatHtmlRacikan"></div>
                </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2">Jumlah</label>
              <div class="col-md-2">
                  <input class="form-control" name="jumlah_pesan_racikan" id="jumlah_pesan_racikan" type="text" style="text-align:center" />
              </div>
              <div class="col-md-3">
                  <button type="submit" id="btn_submit_obat"  value="detail" name="submit" class="btn btn-xs btn-primary">
                   <i class="ace-icon fa fa-plus icon-on-right bigger-110"></i>
                   Tambahkan Obat
                </button>
              </div>
            </div>

          </div>

          <div class="col-sm-12">
            <hr class="separator">
            <div style="margin-top:-27px">
              <table id="temp_data_racikan" base-url="farmasi/Entry_resep_racikan" class="table table-bordered table-hover">
                 <thead>
                  <tr>  
                    <th class="center"></th>
                    <th width="50px"></th>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Nama Obat</th>
                    <th>Satuan</th>
                    <th>Jumlah Pesan</th>
                    <th>Harga Satuan</th>
                    <th>Total (Rp.)</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>

          </div>

        </div>

      </form>

    </div>

</div><!-- /.row -->

