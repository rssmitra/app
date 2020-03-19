<script type="text/javascript" src="<?php echo base_url()?>assets/jQuery-Scanner/jquery.scannerdetection.js"></script>
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

$(document).scannerDetection({
	timeBeforeScanTest: 200, // wait for the next character for upto 200ms
	startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
	endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
	avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
	onComplete: function(barcode, qty){ 
    // here function to get barang
    showModal(barcode);
    $('#qtyBarang').focus();
   } // main callback function	
});

$(document).ready(function(){

    show_default_cart();

    $('#form_distribusi_brg').ajaxForm({
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
          
          $('#form_div').hide();
          $('#div_table').show();
          $('#page-area-content').load('purchasing/pendistribusian/Distribusi_permintaan/form/'+jsonResponse.id+'?flag='+jsonResponse.flag+'');

        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

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
              url: "templates/References/getItemBarang",
              data: { keyword:query, flag: $("input[name='flag_gudang']:checked"). val() },            
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

        var detailObat = getDetailBarang(val_item, $("input[name='flag_gudang']:checked"). val() );
        $('#qtyBarang').focus();

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

    $( "#qtyBarang" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
            insert_cart_log( $('#barcode_text').text() );    
          }         
          return false;                
        }       
    });

})

function getDetailBarang(kode_brg, flag){
  preventDefault();
  $('#div_detail_brg').show();
  $.getJSON('Templates/References/getItemBarangDetail?kode_brg=' + kode_brg + '&flag='+flag, '', function (response) {
      // detail barang
      $('#div_detail_brg').html( response.html );
  });
  
}

function show_default_cart(){
  $('#div_cart').load('purchasing/pendistribusian/Pengiriman_unit/show_cart');
}

function insert_cart_log(barcode){

  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'purchasing/pendistribusian/Pengiriman_unit/insert_cart_log', //Your form processing file URL
      data      : { barcode: barcode, flag: $("input[name='flag_gudang']:checked"). val(), qty: $('#qtyBarang').val(), kode_bagian: $('#kode_bagian_minta').val()  }, //Forms name
      dataType  : 'json',
      beforeSend: function() {
        achtungShowLoader();  
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          $('#inputKeyBarang').val(jsonResponse.nama_brg);
          $.achtung({message: jsonResponse.message, timeout:5});
          getDetailBarang(jsonResponse.kode_brg, jsonResponse.flag);
          show_default_cart();
        }else{
          $('#div_detail_brg').html( '<span style="color: red">- '+jsonResponse.message+' -</span>' );
        }

        achtungHideLoader();
      }

  })

}

function check_barcode(barcode){
  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'purchasing/pendistribusian/Pengiriman_unit/check_barcode', //Your form processing file URL
      data      : { barcode: barcode, flag: $("input[name='flag_gudang']:checked"). val() }, //Forms name
      dataType  : 'json',
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        $('#barcode_text').text(barcode);
        $('#qtyBarang').val(1);
        if( jsonResponse.status === 200 ){
          $('#kode_brg_hidden').val(jsonResponse.kode_brg);
          $('#detail_brg_modal').html(''+jsonResponse.kode_brg+' - '+jsonResponse.nama_brg+'');
          getDetailBarang(jsonResponse.kode_brg, $("input[name='flag_gudang']:checked"). val() );
        }else{
          $('#kode_brg_hidden').val('');
          $('#detail_brg_modal').html('<span style="color: red">-Barang tidak ditemukan</span>');
          $('#div_detail_brg').html( '<span style="color: red">-Barang tidak ditemukan</span>' );
        }
      }
  })
}

function showModal(barcode){  
  // cek barcode
  check_barcode(barcode);
  $("#result_text").text('Form Input Qty');  
  $("#barcode_text").text( barcode ); 
  $("#modal_input_qty").modal();  
}

function show_hide_form(num){
  $('#form_'+num+'').show();
  $('#text_'+num+'').hide();
}

function hide_form(num) {
  $('#form_'+num+'').hide();
  $('#text_'+num+'').text($('#form_'+num+'').val());
  $('#text_'+num+'').show();
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
    <div>
      <div class="row search-page" id="search-page-1">
        <div class="col-xs-12">
          <div class="row">
          <div class="col-xs-12 col-sm-7">
              <div class="row">
                <div class="search-area well col-xs-12">
                  <div class="search-filter-header bg-primary" style="padding: 5px; margin-top: -10px">
                    <h5 class="smaller no-margin-bottom">
                      <i class="ace-icon fa fa-sliders light-green bigger-130"></i>&nbsp; Pencarian barang
                    </h5>
                  </div>
                  <div class="space-10"></div>

                  <form>
                    <div class="row">
                      <div class="col-xs-3">
                        <select name="" id="" class="form-control">
                          <option value="">Barcode</option>
                          <option value="">Nama Barang</option>
                          <option value="">Kode Barang</option>
                          <option value="">Golongan</option>
                          <option value="">Kategori</option>
                        </select>
                      </div>
                      <div class="col-xs-5 no-padding">
                        <div class="input-group">
                          <input type="text" class="form-control" name="keywords" placeholder="Masukan kata kunci pencarian" />
                          <div class="input-group-btn">
                            <button type="button" class="btn btn-default no-border btn-sm">
                              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                  
                </div>
              </div>

              <div class="row">

                <?php for($i=0; $i<8; $i++) :?>
                <div class="col-md-3 no-padding" style="padding: 2px !important">
                  <div class="thumbnail search-thumbnail">
                    <img class="media-object" src="<?php echo base_url().'uploaded/barang/no-image.jpg'?>" />
                    <div class="caption">
                      <b><a href="#" class="blue">Nama Barang</a></b>
                      <p>
                        Cras justo odio, dapibus ac facilisis in, egestas eget quam ...<hr>
                        Qty 
                        <input type="number" style="width: 70px; height: 30px !important">
                        <label class="label label-primary"><i class="fa fa-arrow-right"></i></label>
                      </p>
                    </div>
                  </div>
                </div>
                <?php endfor; ?>
                <ul class="pagination">
                  <li class="disabled">
                    <a href="#">
                      <i class="ace-icon fa fa-angle-double-left"></i>
                    </a>
                  </li>
                  <li class="active">
                    <a href="#">1</a>
                  </li>
                  <li><a href="#">2</a></li>
                  <li>
                    <a href="#">
                      <i class="ace-icon fa fa-angle-double-right"></i>
                    </a>
                  </li>
                </ul>
              </div>
              
            </div>

            <div class="col-xs-12 col-sm-5">
              <div class="search-area well well-sm">
                <div class="search-filter-header bg-primary" style="padding: 5px">
                  <h5 class="smaller no-margin-bottom">
                    <i class="ace-icon fa fa-sliders light-green bigger-130"></i>&nbsp; Daftar barang keluar
                  </h5>
                </div>

                <div class="space-10"></div>

                <form>
                  <div class="row">
                    <div class="form-group">
                        <label class="control-label col-md-3" style="margin-top: 5px">Bagian/Unit</label>
                        <div class="col-md-7 no-padding">
                        <?php 
                            echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), isset($value)?$value->kode_bagian_minta:'' , 'kode_bagian_minta', 'kode_bagian_minta', 'form-control', '', '') ?>
                        </div>
                    </div>
                  </div>
                </form>
                
                <div class="hr hr-dotted"></div>

                <table class="table">
                  <thead>
                      <tr>
                        <th class="center">No</th>
                        <th>Kode dan Nama Barang</th>
                        <th class="center">Qty</th>
                        <th style="text-align: center">Satuan</th>
                        <th style="text-align: center"></th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php for($i=1; $i<6; $i++) :?>
                    <tr>
                      <td class="center"><?php echo $i?></td>
                      <td>
                        <b>G342081</b><br>
                        PC SOHO Core i3 Gen L1990
                      </td>
                      <td class="center">
                        <span onclick="show_hide_form(<?php echo $i?>)" id="text_<?php echo $i?>">1</span>
                        <input type="number" id="form_<?php echo $i?>" style="height: 40px; width: 60px; text-align: center; display: none" value="1" onkeyup="hide_form(<?php echo $i?>)">
                      </td>
                      <td style="text-align: center">Unit</td>
                      <td style="text-align: center"><a href="#"><i class="fa fa-times-circle bigger-120 red"></i></a></td>
                    </tr>
                    <?php endfor; ?>
                  </tbody>
                </table>

                <div class="hr hr-dotted"></div>
                
                <div class="text-center">
                  <button type="button" class="btn btn-default btn-round btn-sm btn-white">
                    <i class="ace-icon fa fa-remove red2"></i>
                    Reset
                  </button>

                  <button type="button" class="btn btn-default btn-round btn-white">
                    <i class="ace-icon fa fa-refresh green"></i>
                    Update
                  </button>
                </div>

                <div class="space-4"></div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->