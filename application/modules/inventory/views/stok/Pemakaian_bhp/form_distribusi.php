<script type="text/javascript" src="<?php echo base_url()?>assets/jQuery-Scanner/jquery.scannerdetection.js"></script>
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

$(document).scannerDetection({
	timeBeforeScanTest: 200, // wait for the next character for upto 200ms
	startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
	endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
	avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
	onComplete: function(barcode, qty){ 
    // here function to get barang
    $('#barcode_input').show();
    $('#kode_brg_hidden').val('');
    $('#inputKeyBarang').val('');
    $('#barcode_value').val(barcode);
    showModal(barcode);
    $('#qtyBarang').focus();
   } // main callback function	
});

$(document).ready(function(){

    show_default_cart();

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


    $('#inputKeyBarang').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/References/getItemBarangByUnit",
              data: { keyword:query, flag: $("input[name='flag_gudang']:checked"). val(), unit: $('#from_unit').val() },            
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
        $('#inputKeyBarang').val(label_item);
        console.log(val_item);
        $('#div_detail_brg').html('');
        // get item detail
        getDetailBarang(val_item);
        $('#qtyBarang').focus();
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
  $.getJSON('Templates/References/getItemBarangDetailByUnit?kode_brg=' + kode_brg + '&flag='+$("input[name='flag_gudang']:checked"). val()+'&from_unit='+$('#from_unit').val()+'', '', function (response) {
      // detail barang
      var dt_brg = response.data;
      $('#kode_brg_hidden').val(kode_brg);
      $('#nama_brg_hidden').val(dt_brg.nama_brg);
      $('#satuan_brg_hidden').val(dt_brg.satuan_kecil);
      $('#harga_brg_hidden').val(dt_brg.harga_beli);
      $('#qtyBarang').attr('max', parseInt(dt_brg.jml_sat_kcl));
      $('#qtyStok').val(parseInt(dt_brg.jml_sat_kcl));
      $('#qtyBarang').val(1);
      $('#qtyBarang').focus();
      $('#div_detail_brg').html( response.html );
       
  });
  
}

function show_default_cart(){
  $('#div_cart').load('inventory/stok/Pemakaian_bhp/show_cart?flag='+$("input[name='flag_gudang']:checked"). val()+'&form=bhp&from_unit='+$('#from_unit').val()+'');
}

function insert_cart_log(){

  var post_data = {
    barcode : $('#barcode_value').val(), 
    flag : $("input[name='flag_gudang']:checked"). val(), 
    kode_brg : $('#kode_brg_hidden').val(),
    nama_brg : $('#nama_brg_hidden').val(),
    satuan : $('#satuan_brg_hidden').val(),
    harga : $('#harga_brg_hidden').val(),
    qty: $('#qtyBarang').val(),
    from_unit: $('#from_unit').val(),
    qtyBefore: $('#qtyStok').val(),
    flag_form: 'bhp',

  };
  console.log($('#qtyBarang').val());
  console.log($('#qtyStok').val());

  if( parseInt($('#qtyBarang').val()) > parseInt($('#qtyStok').val()) ){
    alert('Jumah barang melebihi sisa stok!'); return false;
  }

  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'inventory/stok/Pemakaian_bhp/insert_cart_log', //Your form processing file URL
      data      : post_data, //Forms name
      dataType  : 'json',
      beforeSend: function() {
        achtungShowLoader();  
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        
        $('#div_detail_brg').html( jsonResponse.html );

        if(jsonResponse.status === 200){
          show_default_cart();
        }else{
          $('#div_detail_brg').html( '<span style="color: red">- '+jsonResponse.message+' -</span>' );
        }

        achtungHideLoader();
      }

  })

}

function check_barcode(){
  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'inventory/stok/Pemakaian_bhp/check_barcode', //Your form processing file URL
      data      : { barcode: $('#barcode_value').val(), flag: $("input[name='flag_gudang']:checked"). val(), kode_brg : $('#kode_brg_hidden').val() }, //Forms name
      dataType  : 'json',
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        if( jsonResponse.status === 200 ){
          if (jsonResponse.count == 1) {
            var dt_brg =jsonResponse.data_brg;
            $('#kode_brg_hidden').val(dt_brg.kode_brg);
            $('#nama_brg_hidden').val(dt_brg.nama_brg);
            $('#inputKeyBarang').val(dt_brg.nama_brg);
            $('#satuan_brg_hidden').val(dt_brg.satuan_kecil);
            $('#harga_brg_hidden').val(dt_brg.harga_beli);
            $('#qtyBarang').val(1);
          }else{
            $('#kode_brg_hidden').val('');
            $('#nama_brg_hidden').val('');
            $('#inputKeyBarang').val('');
            $('#satuan_brg_hidden').val('');
            $('#harga_brg_hidden').val('');
          }
          $('#barcode_text').text($('#barcode_value').val());
          $('#div_detail_brg').html( jsonResponse.html );
          
        }else{
          $('#qtyBarang').val('');
          $('#kode_brg_hidden').val('');
          $('#nama_brg_hidden').val('');
          $('#inputKeyBarang').val('');
          $('#satuan_brg_hidden').val('');
          $('#harga_brg_hidden').val('');
          $('#div_detail_brg').html( '<span class="blink_me" style="color: red">-Barang tidak ditemukan</span>' );
        }
      }
  })
}

function showModal(barcode=''){  
  // cek barcode
  $("#result_text").text('Form Input Qty');  
  $("#barcode_text").text( barcode ); 
  check_barcode();
}

function delete_cart(kode_brg){
  preventDefault();
  $.ajax({
    url: 'inventory/stok/Pemakaian_bhp/delete_cart',
    type: "post",
    data: {ID: kode_brg, flag: $('#flag_string').val(), flag_form : 'bhp'},
    dataType: "json",
    beforeSend: function() {
      achtungShowLoader();  
    },
    success: function(data) {
      achtungHideLoader();
      show_default_cart()
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
    <!-- PAGE CONTENT BEGINS -->
      <form class="form-horizontal">

        <div class="col-md-6">

          <div class="form-group">
            <label class="control-label col-md-3">Bagian/Unit</label>
            <div class="col-md-9">
              <?php 
                echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('group_bag' => 'Detail')), '' , 'from_unit', 'from_unit', 'chosen-select form-control', '', '') ?>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Jenis Barang</label>
            <div class="col-md-9">
              <div class="radio">
                <label>
                  <input name="flag_gudang" type="radio" class="ace" value="medis" <?php echo ($flag=='medis')?'checked':'checked'; ?> />
                  <span class="lbl"> Medis</span>
                </label>
                <label>
                  <input name="flag_gudang" type="radio" class="ace" value="non_medis" disabled />
                  <span class="lbl"> Non Medis</span>
                </label>
              </div>
            </div>                
          </div>
          <div class="form-group">
              <label class="control-label col-md-3">Cari Barang</label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="keyword" id="inputKeyBarang">
              </div>
          </div>
          <div class="form-group" id="barcode_input" style="display: none">
            <label class="control-label col-md-3">Kode Barcode</label>
            <div class="col-md-9" style="margin-top: 4px; padding-left: 20px">
              <span id="barcode_text"><i class="fa fa-question-circle bigger-150 orange"></i></span>
              <input type="hidden" id="barcode_value">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Kode Barang</label>
            <div class="col-md-3" >
              <input class="form-control" type="text" name="kode_brg_hidden" id="kode_brg_hidden">
              <input class="form-control" type="hidden" name="nama_brg_hidden" id="nama_brg_hidden">
              <input class="form-control" type="hidden" name="satuan_brg_hidden" id="satuan_brg_hidden">
              <input class="form-control" type="hidden" name="harga_brg_hidden" id="harga_brg_hidden">
            </div>
            <label class="control-label col-md-3">Jumlah Pemakaian BHP</label>
            <div class="col-md-2" >
              <input class="form-control" type="number" name="qtyBrg" id="qtyBarang">
              <input class="form-control" type="hidden" name="qtyBrgStok" id="qtyStok">
            </div>
            <div class="col-md-1" style="margin-left: -10px">
              <a href="#" onclick="insert_cart_log()" class="btn btn-xs btn-yellow"><i class="fa fa-shopping-cart"></i></a>
            </div>
          </div>
          
          <div id="div_detail_brg"></div>
          
        </div>
        <div class="col-md-6">
          <h3 class="header smaller lighter blue" style="font-size: 15px;margin-top: -8px;margin-left: -12px;">Daftar Pemakaian Barang BHP Unit</h3>
          <div id="div_cart"></div>
        </div>
      </form>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


