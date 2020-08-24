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
              url: "templates/References/getItemBarangRetur",
              data: { keyword : query, flag : $( 'input[name=flag_gudang]:checked' ).val(), unit : $('#dari_unit').val(), jenis_retur : $('#jenis_retur').val() },            
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
        var string_item = item.replace(/<[^>]+>/g, ':');
        var val_item = string_item.split(':')[1];
        var val_label = string_item.split(':')[3];
        var qtyBrg = string_item.split(':')[4];
        var kode = string_item.split(':')[5];
        console.log(kode);
        $('#qtyStok').val(qtyBrg);
        $('#inputKeyBarang').val(val_label);
        $('#div_detail_brg').html('');
        // get item detail
        getDetailBarang(val_item, 1);
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
      $('#flag_cart').val(value);
      $('select[name=jenis_retur]').change();
    });

    $('select[name=dari_unit]').change(function(){
      $('#inputKeyBarang').attr('disabled', false);
      $('#dari_unit_hidden').val( $(this).val() );
    });
    
    $('select[name=jenis_retur]').change(function(){
      var value = $(this).val();
      if(  value == 'penerimaan_brg'){
        // hide unit
        $('#dari_unit_div').hide();
      }else{
        $('#dari_unit_div').show();
      }
      // reset detail brg
      $('#reff_kode').val('');
      $('#nama_brg_hidden').val('');
      $('#qtyBarang').val('');
      $('#qtyStok').val('');
      $('#div_detail_brg').html('');
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

    $('#qtyBarang').on("input", function() {
      var dInput = this.value;
      console.log(dInput);
      $('#retur_qty_text').text( dInput );
    });

})

function getDetailBarang(kode_brg, jumlah){
  preventDefault();
  $('#div_detail_brg').show();
  $.getJSON('Templates/References/getItemBarangDetailRetur?kode_brg=' + kode_brg + '&flag='+$("input[name='flag_gudang']:checked"). val()+'&qty='+parseInt(jumlah)+'&retur='+$('#jenis_retur').val()+'', '', function (response) {
      // detail barang
      var dt_brg = response.data;
      $('#kode_brg_hidden').val(kode_brg);
      $('#nama_brg_hidden').val(dt_brg.nama_brg);
      $('#satuan_brg_hidden').val(dt_brg.satuan_kecil);
      $('#harga_brg_hidden').val(dt_brg.harga_beli);
      $('#qtyBarang').attr('max', dt_brg.jml_sat_kcl);
      $('#qtyBarang').val(1);
      $('#qtyBarang').focus();
      $('#div_detail_brg').html( response.html );
      
      // fill retur div
      $('#unit_name').text( $('#dari_unit option:selected').text() );

  });
  
}

function show_default_cart(){
  $('#div_cart').load('purchasing/pendistribusian/Pengiriman_unit/show_cart?flag='+$("input[name='flag_gudang']:checked"). val()+'&unit='+$('#dari_unit option:selected').val()+'&form=retur');
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
    kode_bagian: $('#dari_unit option:selected').val(),
    qtyBefore: $('#qtyStok').val(),
    reff_kode: $('#reff_kode').val(),
    flag_form: 'retur',

  };
  if( parseInt($('#qtyBarang').val()) > parseInt($('#qtyStok').val()) ){
    alert('Jumah barang melebihi sisa stok!'); return false;
  }
  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'purchasing/pendistribusian/Pengiriman_unit/insert_cart_log', //Your form processing file URL
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
      url       : 'purchasing/pendistribusian/Pengiriman_unit/check_barcode', //Your form processing file URL
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
    url: 'purchasing/pendistribusian/Pengiriman_unit/delete_cart',
    type: "post",
    data: {ID: kode_brg, flag: $('#flag_string').val(), flag_form: 'retur'},
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

function click_select_item(kode, qty){
  console.log(kode);
  console.log(qty);
  $('#qtyStok').val(qty);
  $('#qtyBarang').val(qty);
  $('#reff_kode').val(kode);
  $('#qtyBarang').focus();
  $('#nama_brg_hidden').val($('#inputKeyBarang').val());
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

  .dropdown-menu > li > a {
    font-size: 11px;
    padding-left: 11px;
    padding-right: 11px;
    padding-bottom: 4px;
    margin-bottom: 1px;
    margin-top: 1px;
}

.typeahead{
  margin-left: 6px !important;
  width: 100% !important;
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
      <div class="widget-body">
        <div class="widget-main no-padding">

          <form class="form-horizontal">

              <div class="row">

                <div class="col-md-6">
                  <h3 class="header smaller lighter blue" style="font-size: 15px;margin-top: -2px;">Form Retur</h3>
                  
                  <div class="widget-box" style="margin-top: -13px">
                    <div class="widget-body">
                      <div class="widget-main">
                        <div class="form-group">
                          <label class="control-label col-md-3">Jenis Retur</label>
                          <div class="col-md-9">
                            <select name="jenis_retur" id="jenis_retur" class="form-control">
                              <option value="penerimaan_brg">Penerimaan Barang</option>
                              <!-- <option value="pengiriman_brg_unit">Pengiriman Barang Unit</option> -->
                              <option value="lainnya" selected>Lainnya</option>
                            </select>
                          </div>  
                        </div>

                        <div class="form-group">
                          <label class="control-label col-md-3">Retur ke Gudang</label>
                          <div class="col-md-9">
                            <div class="radio">
                              <label>
                                <input name="flag_gudang" type="radio" class="ace" value="medis" checked />
                                <span class="lbl"> Medis</span>
                              </label>
                              <label>
                                <input name="flag_gudang" type="radio" class="ace" value="non_medis"/>
                                <span class="lbl"> Non Medis</span>
                              </label>
                            </div>
                          </div>             
                        </div>

                        <div class="form-group" id="dari_unit_div">
                          <label class="control-label col-md-3">Dari unit</label>
                          <div class="col-md-9">
                            <?php 
                              echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), '060101' , 'dari_unit', 'dari_unit', 'form-control', '', '') ?>
                          </div>  
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Cari Barang</label>
                            <div class="col-md-9">
                              <input type="text" class="form-control" name="keyword" id="inputKeyBarang">
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="widget-box">
                    <div class="widget-body" style="background: #edf3f4;">
                      <div class="widget-main">

                          <div class="form-group" id="barcode_input" style="display: none">
                            <label class="control-label col-md-3">Kode Barcode</label>
                            <div class="col-md-9" style="margin-top: 4px; padding-left: 20px">
                              <span id="barcode_text"><i class="fa fa-question-circle bigger-150 orange"></i></span>
                              <input type="hidden" id="barcode_value">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="control-label col-md-3">Kode Barang</label>
                            <div class="col-md-4" >
                              <input class="form-control" type="text" name="kode_brg_hidden" id="kode_brg_hidden">
                              <input class="form-control" type="hidden" name="nama_brg_hidden" id="nama_brg_hidden">
                              <input class="form-control" type="hidden" name="satuan_brg_hidden" id="satuan_brg_hidden">
                              <input class="form-control" type="hidden" name="harga_brg_hidden" id="harga_brg_hidden">
                            </div>
                            <label class="control-label col-md-1">Qty</label>
                            <div class="col-md-3" >
                              <input class="form-control" type="number" name="qtyBrg" id="qtyBarang" value="0">
                              <input class="form-control" type="hidden" name="qtyBrgStok" id="qtyStok">
                              <input class="form-control" type="hidden" name="reff_kode" id="reff_kode">
                            </div>
                            <div class="col-md-1" style="margin-left: -10px">
                              <a href="#" onclick="insert_cart_log()" class="btn btn-xs btn-yellow"><i class="fa fa-shopping-cart"></i></a>
                            </div>
                          </div>

                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div id="div_detail_brg"></div>
                    </div>
                  </div>

                </div>
                <div class="col-md-6">
                <h3 class="header smaller lighter blue" style="font-size: 15px;margin-top: -2px;margin-left: -12px;">Daftar Retur Barang</h3>
                  <div id="div_cart"></div>
                </div>

              </div>

          </form>
          
        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->
