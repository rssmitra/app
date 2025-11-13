<script type="text/javascript" src="<?php echo base_url()?>assets/jQuery-Scanner/jquery.scannerdetection.js"></script>
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script>
jQuery(function($) {

  $('#flag_cart').val($("input[name='flag_gudang']:checked").val());

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
  
  $('#form_cart').ajaxForm({
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

        getMenu('purchasing/pendistribusian/Permintaan_stok_unit?flag='+jsonResponse.flag+'');

        PopupCenter('purchasing/pendistribusian/Permintaan_stok_unit/print_preview/'+jsonResponse.id+'?flag='+jsonResponse.flag+'', 'Cetak Permintaan Barang ke Unit', 900, 600);

      }else{
        $.achtung({message: jsonResponse.message, timeout:5});
      }
      achtungHideLoader();
    }
  }); 

  var id = $('#id').val();
  cartData = $('#cart-data').DataTable({ 
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "paging": false,
      "bInfo": false,
      "searching": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "purchasing/pendistribusian/Permintaan_stok_unit/get_detail_cart?flag="+$('#flag_cart').val()+"&id="+id,
          "type": "POST"
      },
    });


});



function submit_cart(){
  preventDefault();
  $('#form_cart').submit();
}

function show_hide_note(action){
  if (action == 'show') {
    $('#catatan_form').show();
    $('#add_note_span').hide();
    $('#hide_note_span').show();
    $('#catatan').val();
  }else{
    $('#catatan_form').hide();
    $('#add_note_span').show();
    $('#hide_note_span').hide();
    $('#catatan').val('');
  }
}

if(!ace.vars['touch']) {
        $('.chosen-select').chosen({allow_single_deselect:true}); 
    //resize the chosen on window resize

    $(window)
    .off('resize.chosen')
    .on('resize.chosen', function() {
      $('.chosen-select').each(function() {
          var $this = $(this);
          $this.next().css({'width': $this.parent().width()});
      })
    }).trigger('resize.chosen');

  }
</script>

<script>

$(document).ready(function(){

    $( "#inputKeyWord" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
            search_selected_brg(flag, search_by, keyword);       
          }         
          return false;                
        }       
    });  

    // by default unit farmasi, unit dapat mencari stok yang ada di farmasi
    
    $('#inputKeyBarang').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/References/getItemBarangByUnit",
              data: { keyword:query, flag: $("input[name='flag_gudang']:checked").val(), unit: '060201' },            
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
        $('#div_detail_brg').html('');
        // get item detail
        getDetailBarang(val_item);
        $('#qtyBarang').focus();
        $('#inputKeyBarang').val(label_item);
        $('#barcode_value').val('');
        $('#barcode_text').text('');
        $('#barcode_input').hide();
      }
    });

    $('input[name=flag_metode]').change(function(){
    var value = $( 'input[name=flag_metode]:checked' ).val();
      if( value == 'cari_brg' ){
        $('#div_cari_brg').show();
      }else{
        $('#div_cari_brg').hide();
      }
    });


    $('input[name=flag_gudang]').change(function(){
      var value = $( 'input[name=flag_gudang]:checked' ).val();
      $('#flag_cart').val(value);
    });

    $( "#qtyBarang" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
            insert_cart_log();    
          }         
          return false;                
        }       
    });

})

function getDetailBarang(kode_brg){
  preventDefault();
  $('#div_detail_brg').show();
  
  $.getJSON('Templates/References/getItemBarangDetailByUnit?kode_brg=' + kode_brg + '&flag='+$("input[name='flag_gudang']:checked").val()+'&from_unit='+$('#dari_unit').val()+'', '', function (response) {
      // detail barang
      var dt_brg = response.data;
      if(dt_brg == 0){
        $('#stock_card').val( 0 );
      }else{
        $('#stock_card').val( 1 );
      }
      $('#kode_brg_hidden').val(kode_brg);
      $('#nama_brg_hidden').val(dt_brg.nama_brg);
      $('#satuan_brg_hidden').val(dt_brg.satuan_kecil);
      $('#harga_brg_hidden').val(dt_brg.harga_beli);
      $('#qtyBarang').attr('max', parseInt(dt_brg.jml_sat_kcl));
      $('#qtyStok').val(parseInt(dt_brg.jml_sat_kcl));
      $('#qtyBarang').val('');
      $('#qtyBarang').focus();
      $('#div_detail_brg').html( response.html );
       
  });
  
}

function update_cart(kode_brg, id_det='', type_tbl=''){
  preventDefault();
  $('#div_detail_brg').show();

  $.getJSON('purchasing/pendistribusian/Permintaan_stok_unit/get_item_detail?ID=' + id_det +'&flag='+$("input[name='flag_gudang']:checked").val()+'&type='+type_tbl+'', '', function (response) {
      // detail barang
      var dt_brg = response.data;
      $('#id_tc_permintaan_inst_det').val(dt_brg.id_tc_permintaan_inst_det);
      $('#kode_brg_hidden').val(dt_brg.kode_brg);
      $('#inputKeyBarang').val(dt_brg.nama_brg);
      $('#nama_brg_hidden').val(dt_brg.nama_brg);
      $('#satuan_brg_hidden').val(dt_brg.satuan_kecil);
      $('#harga_brg_hidden').val(dt_brg.harga_beli);
      $('#qtyBarang').attr('max', parseInt(dt_brg.jumlah_stok_sebelumnya));
      $('#qtyStok').val(parseInt(dt_brg.jumlah_stok_sebelumnya));
      $('#qtyBarang').val(dt_brg.jumlah_permintaan);
      $('#keterangan_permintaan').val(dt_brg.keterangan_permintaan);
      if (dt_brg.is_bhp == 1) {
        $('#is_bhp').prop('checked', true);
      } else {
        $('#is_bhp').prop('checked', false);
      }

  });
  
}

function show_default_cart(){
  getMenu('purchasing/pendistribusian/Permintaan_stok_unit/form?flag=medis');
}

function insert_cart_log(){

  var post_data = {
    id_tc_permintaan_inst : $('#id').val(), 
    id_tc_permintaan_inst_det : $('#id_tc_permintaan_inst_det').val(), 
    barcode : $('#barcode_value').val(), 
    flag : $("input[name='flag_gudang']:checked"). val(), 
    kode_brg : $('#kode_brg_hidden').val(),
    nama_brg : $('#nama_brg_hidden').val(),
    satuan : $('#satuan_brg_hidden').val(),
    keterangan_permintaan : $('#keterangan_permintaan').val(),
    harga : $('#harga_brg_hidden').val(),
    qty : $('#qtyBarang').val(),
    qtyBefore : $('#qtyStok').val(),
    dari_unit : $('#dari_unit').val(),
    is_bhp : $('input[name=is_bhp]:checked').val(),
    flag_form : 'permintaan_stok_unit',
    type_tbl : $('#type_tbl').val(),
    stock_card : $('#stock_card').val(),

  };
  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'purchasing/pendistribusian/Permintaan_stok_unit/insert_cart_log', //Your form processing file URL
      data      : post_data, //Forms name
      dataType  : 'json',
      beforeSend: function() {
        achtungShowLoader();  
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          
          cartData.ajax.url("purchasing/pendistribusian/Permintaan_stok_unit/get_detail_cart?flag="+jsonResponse.flag+"&id="+$('#id').val()).load();
          // reset form input
          $('#inputKeyBarang').val('');         
          $('#kode_brg_hidden').val('');         
          $('#nama_brg_hidden').val('');
          $('#satuan_brg_hidden').val('');
          $('#harga_brg_hidden').val('');
          $('#qtyBarang').val('');
          $('#qtyStok').val('');
          $('#qtyBarang').val('');
          $('#keterangan_permintaan').val('');
          
        }else{
          $('#div_detail_brg').html( '<span style="color: red">- '+jsonResponse.message+' -</span>' );
        }

        achtungHideLoader();
      }

  })

}

function delete_cart(kode_brg, id_det, type_tbl){
  preventDefault();
  $.ajax({
    url: 'purchasing/pendistribusian/Permintaan_stok_unit/delete_cart',
    type: "post",
    data: {ID: kode_brg, flag: $('#flag_cart').val(), flag_form : 'permintaan_stok_unit', id_tc_permintaan_inst_det: id_det, type: type_tbl},
    dataType: "json",
    beforeSend: function() {
      achtungShowLoader();  
    },
    success: function(data) {
      achtungHideLoader();
      cartData.ajax.reload();
    }
  });
    
}

if(!ace.vars['touch']) {
        $('.chosen-select').chosen({allow_single_deselect:true}); 
    //resize the chosen on window resize

    $(window)
    .off('resize.chosen')
    .on('resize.chosen', function() {
      $('.chosen-select').each(function() {
          var $this = $(this);
          $this.next().css({'width': $this.parent().width()});
      })
    }).trigger('resize.chosen');

  }

</script>

<style>

.table-custom{
  font-family: calibri;
  font-size: 13px;
  background-color: white;
  width: 100% !important
}
th, td {
    padding: 2px;
    text-align: left;
  }

.blink_me {
  animation: blinker 1s linear infinite;
  }

  @keyframes blinker {
    50% {
      opacity: 0;
    }
  }

  .error{
    color: red;
    font-size: 10px;
  }

  .widget-body {
      min-height: 77px !important;
  }
</style>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <br>
    <!-- PAGE CONTENT BEGINS -->

        <form class="form-horizontal" method="post" id="form_cart" action="<?php echo base_url().'purchasing/pendistribusian/Permintaan_stok_unit/process'?>" enctype="multipart/form-data" style="margin-top: -10px" autocomplete="off">

          <input class="form-control" type="hidden" name="id" id="id" value="<?php echo isset($value->id_tc_permintaan_inst)?$value->id_tc_permintaan_inst:''?>">
          <input class="form-control" type="hidden" name="id_tc_permintaan_inst_det" id="id_tc_permintaan_inst_det" value="<?php echo isset($value->id_tc_permintaan_inst_det)?$value->id_tc_permintaan_inst_det:''?>">
          
          <input class="form-control" type="hidden" name="type_tbl" id="type_tbl" value="<?php echo isset($value->id_tc_permintaan_inst) ? 'tc_permintaan_inst' : 'cart_log'?>">
          <input class="form-control" type="hidden" name="stock_card" id="stock_card" value="">
          
          <div class="col-md-9">
            <a href="#" onclick="getMenu('purchasing/pendistribusian/Permintaan_stok_unit')" class="btn btn-xs btn-default">
              <i class="fa fa-arrow-left bigger-150"></i>
            </a>
            <?php 
              echo ($flag == 'update') ? '<span style="font-weight: bold; font-style: italic; color: blue">[Update Form]</span>' : '';
            ?>
            <div class="form-group">
              <label class="control-label col-md-2">Jenis Barang</label>
              <div class="col-md-9">
                <div class="radio">
                  <?php 
                    if(isset($cart_data[0]->flag) && $cart_data[0]->flag == 'medis'){
                      $checked_medis = 'checked';
                      $checked_non_medis = '';
                    }else{
                      $checked_non_medis = 'checked';
                      $checked_medis = '';
                    }
                  ?>
                  <label>
                    <input name="flag_gudang" type="radio" class="ace" value="medis" <?php echo ($flag_type == 'medis')?'checked':''?>  />
                    <span class="lbl"> Medis</span>
                  </label>
                  <label>
                    <input name="flag_gudang" type="radio" class="ace" value="non_medis" <?php echo ($flag_type == 'non_medis')?'checked':''?>  />
                    <span class="lbl"> Non Medis</span>
                  </label>
                </div>
              </div>                
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-2">Unit Bagian</label>
              <div class="col-md-7">
                <?php 
                  if(isset($cart_data[0]->kode_bagian)){
                    $kode_bagian_minta = isset($cart_data[0]->kode_bagian)?$cart_data[0]->kode_bagian:'';
                  }else{
                    $kode_bagian_minta = isset($value->kode_bagian_minta)?$value->kode_bagian_minta:'';
                  }
                  echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), $kode_bagian_minta , 'dari_unit', 'dari_unit', 'chosen-select form-control', '', '') ?>
              </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Cari Barang</label>
                <div class="col-md-6">
                  <input type="text" class="form-control" name="keyword" id="inputKeyBarang">
                </div>
            </div>
            <!-- hidden after search barang -->
            <input class="form-control" type="hidden" name="kode_brg_hidden" id="kode_brg_hidden" readonly>
            <input class="form-control" type="hidden" name="nama_brg_hidden" id="nama_brg_hidden">
            <input class="form-control" type="hidden" name="satuan_brg_hidden" id="satuan_brg_hidden">
            <input class="form-control" type="hidden" name="harga_brg_hidden" id="harga_brg_hidden">
            <input type="hidden" name="flag" id="flag_cart" value="<?php echo isset($flag)?$flag:''?>">
            <input type="hidden" name="flag_form" id="flag_form" value="permintaan_stok_unit">

            <div class="form-group">
              <label class="control-label col-md-2">Qty</label>
              <div class="col-md-2" >
                <input class="form-control" type="number" name="qtyBrg" id="qtyBarang">
                <input class="form-control" type="hidden" name="qtyBrgStok" id="qtyStok">
              </div>
              <div class="col-md-4" >
              <label style="padding-top: 3px">
                <input name="is_bhp" id="is_bhp" value="1" type="checkbox" class="ace">
                <span class="lbl"> BHP (Barang Habis Pakai)</span>
              </label>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Keterangan</label>
              <div class="col-md-8" >
                <input class="form-control" type="text" name="keterangan_permintaan" id="keterangan_permintaan">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">&nbsp;</label>
              <div class="col-md-2" style="margin-left: 6px">
                <a href="#" onclick="insert_cart_log()" class="btn btn-xs btn-yellow"><i class="fa fa-shopping-cart"></i> Masukan List</a>
              </div>
            </div>

            <table id="cart-data" base-url="purchasing/pendistribusian/Permintaan_stok_unit" data-id="flag=<?php echo $flag?>" class="table table-bordered table-hover">
              <thead>
                <tr>  
                  <th width="30px" class="center">No</th>
                  <th width="100px">Kode Brg</th>
                  <th>Nama Barang</th>
                  <th class="center">BHP?</th>
                  <th class="center">Stok</th>
                  <th class="center">Qty</th>
                  <th class="center">Satuan</th>
                  <th style="width: 80px">Total</th>
                  <th>Keterangan</th>
                  <th class="center" width="80px">Aksi</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>

            <div style="margin-top: -19px">
              <label style="font-weight: bold">Catatan : </label>
              <textarea class="form-control" style="height: 50px !important; margin-bottom: 5px; width: 100%" id="catatan" name="catatan"><?php echo isset($value->catatan)?$value->catatan:''?></textarea>
            </div>
              
            <hr>
            <div class="center" style="padding-right: 10px; padding-bottom: 5px">

              <a href="#" id="btnSave" name="submit" class="btn btn-xs btn-danger">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reset Form
              </a>
              <a href="#" id="btnSave" onclick="submit_cart()" name="submit" class="btn btn-xs btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </a>
              
            </div>

          </div>
          <div class="col-md-3">
              <div id="div_detail_brg"></div>
          </div>

        </form>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


