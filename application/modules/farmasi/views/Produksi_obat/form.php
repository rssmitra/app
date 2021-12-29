<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>

<script>

var oTable;
var base_url = 'farmasi/Produksi_obat/get_komposisi_obat'; 

jQuery(function($) {

  $('.format_number').number( true, 0 );

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

  getDetailObatByKodeBrg($('#kode_brg_prod').val(), $('#kode_bagian_gudang').val());
    //initiate dataTables plugin
    oTable = $('#list-produksi-obat-table').DataTable({ 
            
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "paging": false,
      "bInfo": false,
      "pageLength": 25,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": base_url+'?id_tc_prod_obat='+$('#id_tc_prod_obat').val()+'',
          "type": "POST"
      },

    });

    oTable.on( 'xhr', function () {
        var json = oTable.ajax.json();
        $('#subtotal').text(formatMoney(json.subtotal));
        $('#subtotal_val').val(json.subtotal);
        count_jasa_prod();
        hitung_harga_satuan();
        // $('#total_jasa_produksi').text(''+formatMoney(json.subtotal));
        console.log(json);
    } );

    $('#list-produksi-obat-table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
    
  
    $('#form_Produksi_obat').ajaxForm({
      beforeSend: function() {
        achtungShowLoader();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          $('#inputKeyObat').focus();
          $.achtung({message: jsonResponse.message, timeout:5});
          if( jsonResponse.action == 'finish' ){
            $('#page-area-content').load('farmasi/Produksi_obat/?flag=All&timestamp=' + (new Date()).getTime());
          }else{
            $('#page-area-content').load('farmasi/Produksi_obat/form/'+jsonResponse.id_tc_prod_obat+'?flag=All&timestamp=' + (new Date()).getTime());
          }
          $('#id_tc_prod_obat').val(jsonResponse.id_tc_prod_obat);
          oTable.ajax.reload();
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 

    $('#inputKeyObat').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getObatByBagianAutoComplete",
                data: { keyword:query, bag: $('#kode_bagian_gudang').val()},            
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
          $('#kode_brg_hidden_detail').val(val_item);
          var detailObat = getDetailObatByKodeBrg(val_item, $('#kode_bagian_gudang').val());
          $('#jumlah_kcl').focus();

        }
    });

    $('#inputKeyObatProd').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getObatByBagianAutoComplete",
                data: { keyword:query, bag: $('#kode_bagian_gudang').val()},            
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
          $('#inputKeyObatProd').val(label_item);
          $('#kode_brg_prod').val(val_item);
          $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+val_item+"&kode_kelompok=0>&kode_perusahaan=0&bag="+$('#kode_bagian_gudang').val()+"&type=html&type_layan=Rajal", '' , function (response) {
            var obj = response.data;
            $('#rasio').val(obj.content);
            $('#satuan_kecil_prod').text(obj.satuan_kecil);
            $('#satuan_kecil_prod_val').val(obj.satuan_kecil);
            $('#id_obat_prod').val(obj.id_obat);
            $('#detailObatHtml').html(response.html);
          })
                   
          $('#jasa_prod').focus();

        }
    });

    $( "#jasa_prod" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#jumlah_prod').focus();
          }
          return false;       
        }
    });

    $( "#jumlah_prod" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#tgl_prod').focus();
          }
          return false;       
        }
    });

    $( "#next_process" )
      .click(function(event) {
        $('#form_produksi_obat_header').show('fast');
        $('#add_btn').hide();
        $('#btn_udpate_data_bahan_baku').show();
        $(this).hide();
    });

    $( "#btn_udpate_data_bahan_baku" )
      .click(function(event) {
        $('#form_produksi_obat_header').hide('fast');
        $('#add_btn').show();
        $('#next_process').show();
        $(this).hide();
    });

})

function getDetailObatByKodeBrg(kode_brg,kode_bag){

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok=0>&kode_perusahaan=0&bag="+kode_bag+"&type=html&type_layan=Rajal", '' , function (response) {
    var obj = response.data;
    $('#harga_beli').val(response.harga_beli);
    $('#harga_jual').val(response.harga_satuan_umum);
    $('#detailObatHtml').html(response.html);
    return obj;
  })

}

function delete_item_komposisi(myid){
  if(confirm('Are you sure?')){
    preventDefault();
    $.ajax({
        url: 'farmasi/Produksi_obat/delete_item_komposisi',
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
            oTable.ajax.reload();
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

function count_jasa_prod(){
  var percent = $('#jasa_prod').val();
  var subtotal = $('#subtotal_val').val();
  var total = parseInt(subtotal) + (parseInt(subtotal) * (parseInt(percent)/100));
  $('#total_jasa_produksi').text(formatMoney(parseInt(total)));
  $('#total_jasa_produksi_val').val(parseInt(total));
  
}

function hitung_harga_satuan(){
  var total_biaya = $('#total_jasa_produksi_val').val();
  var jml_prod = $('#jumlah_prod').val();
  var harga_satuan = parseInt(total_biaya) / parseInt(jml_prod);
  $('#harga_satuan_prod_val').val(parseInt(harga_satuan));
  $('#harga_satuan_prod_txt').text(formatMoney(parseInt(harga_satuan)));
}

</script>

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
          <form class="form-horizontal" method="post" id="form_Produksi_obat" action="<?php echo site_url('farmasi/Produksi_obat/process')?>" enctype="multipart/form-data" autocomplete="off">
            <!-- hidden form -->
            <input type="hidden" name="id_tc_prod_obat" id="id_tc_prod_obat" value="<?php echo isset($value)?$value->id_tc_prod_obat:0?>">
            <input type="hidden" name="subtotal_val" id="subtotal_val" value="">
            <input type="hidden" name="rasio" id="rasio" value="<?php echo isset($value)?$value->rasio:''?>" <?php echo ($flag=='read')?'readonly':''?> >
            <input type="hidden" name="kode_bagian_gudang" id="kode_bagian_gudang" value="<?php echo $kode_bagian_gudang?>">

            <p class="center"><b style="font-size: 14px">PRODUKSI OBAT FARMASI</b><br>Silahkan masukan komposisi obat sesuai dengan takaran.</p>
            <div class="col-xs-7 no-padding">
              <p style="font-weight: bold">KOMPOSISI OBAT PRODUKSI</p>

              <div class="col-xs-8 no-padding">
                <label for="form-field-8">Nama Obat : </label>
                <input name="nama_brg" id="inputKeyObat" value="" placeholder="Masukan Keyword" class="form-control" type="text" >
                <input type="hidden" name="kode_brg_hidden_detail" id="kode_brg_hidden_detail" value="">
                
              </div>

              <div class="col-xs-4">
                <label for="form-field-8">Jumlah Obat : </label>
                <div class="input-group">
                <input name="jumlah_kcl" id="jumlah_kcl" value="" placeholder="" class="form-control" type="text" style="text-align:center;">
                  <span class="input-group-btn" id="add_btn">
                    <button class="btn btn-sm btn-default" type="submit" id="btnSave" name="submit" value="detail">
                      <i class="ace-icon fa fa-shopping-cart bigger-110"></i>
                      Add !
                    </button>
                  </span>
                </div>
                
              </div>

              <div class="col-xs-12 no-padding">
                <table id="list-produksi-obat-table" class="table-utama" style="width: 100% !important;margin-top: -1%">
                  <thead>
                    <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">  
                      <th width="30px" class="center">No</th>
                      <th style="text-align:left; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">Nama Obat</th>
                      <th style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">Satuan</th>
                      <th style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah</th>
                      <th style="text-align:right; width: 50px; border-bottom: 1px solid black; border-collapse: collapse" >Harga Satuan</th>
                      <th style="text-align:right; width: 50px; border-bottom: 1px solid black; border-collapse: collapse" >Sub Total</th>
                      <th width="30px" class="center"></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="5" align="right"><b>Total Harga</b></td>
                      <td align="right"><span id="subtotal">0</span></td>
                    </tr>
                  </tfoot>
                </table>
                <div class="pull-right">
                  
                  
                  <button type="button" id="next_process" name="submit" value="next_to_prod" class="btn btn-sm btn-info">
                    Komposisi Selesai
                    <i class="ace-icon fa fa-arrow-circle-right icon-on-right bigger-110"></i>
                  </button>

                  <button type="button" id="btn_udpate_data_bahan_baku" class="btn btn-sm btn-success" style="display:none">
                    Update Bahan Baku
                    <i class="ace-icon fa fa-pencil icon-on-right bigger-110"></i>
                  </button>

                </div>
              </div>

              <div class="col-xs-12 no-padding" id="form_produksi_obat_header" style="display: none">
                <p style="font-weight: bold">PRODUKSI OBAT FARMASI</p>
                <div>
                  <div class="col-xs-12 no-padding">
                    <label for="form-field-12">Nama Obat Produksi: </label>
                    <input name="nama_brg_prod" id="inputKeyObatProd" value="<?php echo isset($value)?$value->nama_brg:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                    <input type="hidden" name="kode_brg_prod" id="kode_brg_prod" value="<?php echo isset($value)?$value->kode_brg_prod:''?>">
                    <input type="hidden" name="id_obat_prod" id="id_obat_prod" value="<?php echo isset($value)?$value->id_obat_prod:''?>">
                  </div>
                </div>
                <br>
                <br>
                <br>
                <div class="form-group">
                  <label class="control-label col-md-3">Jasa Produksi (%)</label>
                  <div class="col-md-2">
                    <input name="jasa_prod" id="jasa_prod" value="<?php echo isset($value)?$value->jasa_prod:''?>" placeholder="" class="format_number form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> style="text-align: center" onchange="count_jasa_prod()">
                  </div>
                  <label class="col-md-2" style="margin-top: 4px">Total Harga</label>
                  <div class="col-md-2" style="margin-top: 4px; margin-left: 10px">
                    <span id="total_jasa_produksi"><?php echo isset($value)?number_format($value->harga_prod):''?></span>
                    <input type="hidden" name="harga_prod" id="total_jasa_produksi_val" value="<?php echo isset($value)?$value->harga_prod:''?>">
                  </div>
                </div>
                
                <div class="col-xs-12 no-padding">
                  <div class="form-group" >
                    <label class="control-label col-md-3">Jumlah (Satuan Kecil)</label>
                    <div class="col-md-2">
                      <input name="jumlah_prod" id="jumlah_prod" value="<?php echo isset($value)?$value->jumlah_prod:''?>" placeholder="" class="format_number form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> style="text-align: center" onchange="hitung_harga_satuan()">
                      <input type="hidden" name="harga_satuan_prod" id="harga_satuan_prod_val" value="<?php echo isset($value)?$value->harga_satuan:''?>">
                      <input type="hidden" name="satuan_kecil_prod" id="satuan_kecil_prod_val" value="<?php echo isset($value)?$value->satuan_prod:''?>">
                    </div>
                    <div class="col-md-1" style="margin-top: 4px">
                      <span id="satuan_kecil_prod"><?php echo isset($value)?$value->satuan_prod:''?></span>
                    </div>
                    <label class="col-md-3" style="margin-top: 4px">| Harga Satuan</label>
                    <div class="col-md-2" style="margin-top: 4px">
                      <span id="harga_satuan_prod_txt"><?php echo isset($value)?number_format($value->harga_satuan):''?></span>
                    </div>

                  </div>
                 
                  <div class="form-group">
                      <label class="control-label col-md-3">Tgl Produksi</label>
                      <div class="col-md-3">
                          <div class="input-group">
                            <input name="tgl_prod" id="tgl_prod" placeholder="" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo isset($value->tgl_prod)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_prod):date('Y-m-d')?>">
                            <span class="input-group-addon">
                              <i class="ace-icon fa fa-calendar"></i>
                            </span>
                          </div>
                      </div>
                      <label class="control-label col-md-3">Tgl Expired</label>
                      <div class="col-md-3">
                          <div class="input-group">
                            <input name="tgl_expired" id="tgl_expired" data-date-format="yyyy-mm-dd" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo isset($value->tgl_expired)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_expired): date('Y-m-d', strtotime('+1 year', strtotime(date('Y-m-d')))) ?>">
                            <span class="input-group-addon">
                              <i class="ace-icon fa fa-calendar"></i>
                            </span>
                          </div>
                      </div>
                  </div>
                  
                  <div class="form-actions center">
                      <button type="button" onclick="getMenu('farmasi/Produksi_obat')" class="btn btn-sm btn-default">
                        <i class="ace-icon fa fa-arrow-circle-left icon-on-right bigger-110"></i>
                        Kembali ke sebelumnya
                      </button>
                      <button type="submit" id="finish" name="submit" value="finish" class="btn btn-sm btn-info">
                        Produksi Selesai
                        <i class="ace-icon fa fa-arrow-circle-right icon-on-right bigger-110"></i>
                      </button>
                  </div>
                  
                </div>
                
              </div>

            </div>

            <div class="col-xs-5">
              <p style="font-weight: bold">DATA STOK OBAT</p>
              <div id="detailObatHtml">-Tidak ada data ditemukan-</div>
              <div id="SummaryProduksi"></div>
            </div>
            
          </form>

      </div>

        

    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


