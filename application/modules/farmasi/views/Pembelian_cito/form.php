<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>

<script>

var oTable;
var base_url = 'farmasi/Pembelian_cito/get_list_cito?induk='+$('#induk_cito').val()+''; 

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

  //initiate dataTables plugin
  oTable = $('#list-pembelian-cito-table').DataTable({ 
          
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "paging": false,
    "pageLength": 25,
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": base_url,
        "type": "POST"
    },

  });

  $('#list-pembelian-cito-table tbody').on( 'click', 'tr', function () {
      if ( $(this).hasClass('selected') ) {
          $(this).removeClass('selected');
      }
      else {
          oTable.$('tr.selected').removeClass('selected');
          $(this).addClass('selected');
      }
  } );
    
  $('#form_Pembelian_cito').ajaxForm({
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
        $('#induk_cito').val(jsonResponse.induk_cito);
        $('#txt_induk_cito').text(jsonResponse.induk_cito);
        reset_form();
        oTable.ajax.url('farmasi/Pembelian_cito/get_list_cito?induk='+jsonResponse.induk_cito+'').load();
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
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#inputKeyObat').val(label_item);
        $('#kode_brg_hidden').val(val_item);
        var detailObat = getDetailObatByKodeBrg(val_item, '060101');
        $('#jumlah_kcl').focus();

      }
  });

  $( "#jumlah_kcl" )
      .keypress(function(event) {
      var keycode =(event.keyCode?event.keyCode:event.which); 
      if(keycode ==13){
        event.preventDefault();
        if($(this).valid()){
          $('#harga_beli').focus();
        }
        return false;       
      }
  });

  $( "#harga_beli" )
    .keypress(function(event) {
    var keycode =(event.keyCode?event.keyCode:event.which); 
    if(keycode ==13){
      event.preventDefault();
      if($(this).valid()){
        $('#harga_jual').focus();
      }
      return false;       
    }
  });

  $( "#harga_jual" )
    .keypress(function(event) {
    var keycode =(event.keyCode?event.keyCode:event.which); 
    if(keycode ==13){
      event.preventDefault();
      if($(this).valid()){
        $('#tempat_pembelian').focus();
      }
      return false;       
    }
  });

  $( "#tempat_pembelian" )
    .keypress(function(event) {
    var keycode =(event.keyCode?event.keyCode:event.which); 
    if(keycode ==13){
      event.preventDefault();
      if($(this).valid()){
        $('#btnSave').submit();
      }
      return false;       
    }
  });

  $( "#btn_transaksi_selesai" ).click(function(event) {
    $.ajax({
      url: 'farmasi/Pembelian_cito/proses_selesai',
      type: "post",
      data: { ID : $('#induk_cito').val() },
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
          $('#page-area-content').load('farmasi/Pembelian_cito/preview_transaksi?induk='+$('#induk_cito').val()+'');
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }

    });
  });

})

function reset_form(){
  $('#id').val('');
  $('#inputKeyObat').val('');
  $('#kode_brg_hidden').val('');
  $('#jumlah_kcl').val('');
  $('#harga_beli').val('');
  $('#harga_jual').val('');
  $('#tempat_pembelian').val('');
  $('#detailObatHtml').html('');
}

function reload_table(){
   oTable.ajax.reload(); //reload datatable ajax 
}
  
function delete_data(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/Pembelian_cito/delete',
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
            reset_form();
            reload_table();
            if (jsonResponse.count_last_dt == 0) {
              $('#induk_cito').val(0);
            }
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

function getDetailObatByKodeBrg(kode_brg,kode_bag){

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok=0>&kode_perusahaan=0&bag="+kode_bag+"&type=html&type_layan=Rajal", '' , function (response) {
    var obj = response.data;
    $('#harga_beli').val(response.harga_beli);
    $('#harga_jual').val(response.harga_satuan_umum);
    $('#detailObatHtml').html(response.html);

    return response;

  })

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

          <form class="form-horizontal" method="post" id="form_Pembelian_cito" action="<?php echo site_url('farmasi/Pembelian_cito/process')?>" enctype="multipart/form-data" autocomplete="off">
            <br>
            <!-- hidden form -->
            

            <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label col-md-3">ID</label>
                  <div class="col-md-3">
                    <input name="id" id="id" value="<?php echo isset($value)?$value->id_fr_pengadaan_cito:0?>" placeholder="Auto" class="form-control" type="text" readonly>
                  </div>

                  <label class="control-label col-md-3">Kode Cito</label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="induk_cito" id="induk_cito" value="<?php echo isset($value)?$value->induk_cito:0?>" readonly>
                  </div>
                  
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Nama Obat</label>
                  <div class="col-md-9">
                    <input name="nama_obat" id="inputKeyObat" value="<?php echo isset($value)?$value->nama_brg:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                    <input type="hidden" name="kode_brg_hidden" id="kode_brg_hidden" value="<?php echo isset($value)?$value->kode_brg:''?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Jumlah</label>
                  <div class="col-md-3">
                    <input name="jumlah_kcl" id="jumlah_kcl" value="<?php echo isset($value)?$value->jumlah_kcl:''?>" placeholder="" class="format_number form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                  <span style="font-size: 11px; padding-top: 10px; color: red"> (Satuan Kecil)</span>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Harga Beli</label>
                  <div class="col-md-3">
                    <input name="harga_beli" id="harga_beli" value="<?php echo isset($value)?$value->harga_beli:''?>" placeholder="" class="format_number form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                  <label class="control-label col-md-3">Harga Jual</label>
                  <div class="col-md-3">
                    <input name="harga_jual" id="harga_jual" value="<?php echo isset($value)?$value->harga_jual:''?>" placeholder="" class="format_number form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>

                <div class="form-group">
                  
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Tempat Pembelian</label>
                  <div class="col-md-5">
                    <input name="tempat_pembelian" id="tempat_pembelian" value="<?php echo isset($value)?$value->tempat_pembelian:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                  
                </div>

                <div class="form-group">
                  <label class="col-md-3">&nbsp;</label>
                  <div class="col-md-8">
                    <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-yellow">
                      <i class="ace-icon fa fa-shopping-cart dark icon-on-right bigger-120"></i>
                      Simpan
                    </button>
                    <button type="button" onclick="reset_form()" class="btn btn-sm btn-primary">
                      <i class="ace-icon fa fa-plus-circle dark icon-on-right bigger-120"></i>
                      Tambah Baru
                    </button>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-3">&nbsp;</label>
                  <div class="col-md-8">
                    &nbsp;
                  </div>
                </div>
            </div>

            <div class="col-md-6">
              <div style="padding-left: 7px; padding-bottom: 5px" id="detailObatHtml"></div>
            </div>

            <div class="col-md-12" style="padding-top: 20pxs">
              <p class="pull-left" style="font-weight: bold; text-align: left"><u>PEMBELIAN OBAT CITO</u><br>No. <?php echo isset($value)?str_replace("'","", $value->kode_pengadaan):''?> / <span id="txt_induk_cito"> <?php echo isset($value)?$value->induk_cito:''?></span> </p>
              <div class="pull-right">
                <button type="button" id="btn_transaksi_selesai" name="submit" value="transaksi_selesai" class="btn btn-sm btn-primary">
                  <i class="ace-icon fa fa-check-square-o dark icon-on-right bigger-110"></i>
                  Transaksi Selesai
                </button>
                <a onclick="getMenu('farmasi/Pembelian_cito')" href="#" class="btn btn-sm btn-default">
                  Kembali ke daftar
                  <i class="ace-icon fa fa-arrow-right dark icon-on-right bigger-110"></i>
                </a>
              </div>
              <table id="list-pembelian-cito-table" class="table">
                <thead>
                  <tr style="background: #e3e5e5;">  
                    <th width="30px" class="center"></th>
                    <th width="120px">&nbsp;</th>
                    <th width="50px">ID</th>
                    <!-- <th>Kode</th> -->
                    <th>Nama Obat</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Total Beli</th>
                    <th>Tempat Pembelian</th>              
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            


            <!-- <div class="form-actions center">

              <a onclick="getMenu('farmasi/Pembelian_cito')" href="#" class="btn btn-sm btn-success">
                <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                Kembali ke daftar
              </a>
              <?php if($flag != 'read'):?>
              <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                Reset
              </button>
              <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
            <?php endif; ?>
            </div> -->

          </form>

          

        </div>
      </div>

      

    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


