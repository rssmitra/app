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
    
  $("#button_delete").click(function(event){
        event.preventDefault();
        var searchIDs = $("#list-produksi-obat-table input:checkbox:checked").map(function(){
          return $(this).val();
        }).toArray();
        delete_data(''+searchIDs+'')
        console.log(searchIDs);
  });

  $('#btn_search_data').click(function (e) {
      
        e.preventDefault();
        $.ajax({
        url: $('#form_search').attr('action'),
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        success: function(data) {
          achtungHideLoader();
          find_data_reload(data,base_url);
        }
      });
    });

  $('#btn_reset_data').click(function (e) {
          e.preventDefault();
          reset_table();
  });
  
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
          $.achtung({message: jsonResponse.message, timeout:5});
          $('#page-area-content').load('farmasi/Produksi_obat?_=' + (new Date()).getTime());
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
          $('#kode_brg_hidden').val(val_item);
          var detailObat = getDetailObatByKodeBrg(val_item, '060101');
          $('#jumlah_kcl').focus();

        }
    });
})

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
          <form class="form-horizontal" method="post" id="form_Produksi_obat" action="<?php echo site_url('farmasi/Produksi_obat/process')?>" enctype="multipart/form-data" autocomplete="off">
            <br>
            <!-- hidden form -->
            <input type="hidden" name="id_tc_prod_obat" id="id_tc_prod_obat" value="<?php echo isset($value)?$value->id_tc_prod_obat:0?>">

            <p style="font-weight: bold">KOMPOSISI OBAT</p>

            <div class="form-group">
              <label class="control-label col-md-2">Nama Obat</label>
              <div class="col-md-4">
                <input name="name" id="inputKeyObat" value="" placeholder="" class="form-control" type="text" >
                <input type="hidden" name="kode_brg_hidden" id="kode_brg_hidden" value="<?php echo isset($value)?$value->kode_brg:''?>">
              </div>
              <label class="control-label col-md-1">Jumlah</label>
              <div class="col-md-2">
                <input name="name" id="inputKeyObat" value="" placeholder="" class="form-control" type="text" >
              </div>
              <div class="col-md-1" style="margin-left: -2%">
                <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-success">
                  <i class="ace-icon fa fa-plus dark icon-on-right bigger-110"></i>
                  Tambah Komposisi
                </button>
              </div>
            </div>

            <table id="list-produksi-obat-table" class="table-utama" style="width: 85% !important;margin-top: -1%">
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

            <p style="font-weight: bold">PRODUKSI OBAT FARMASI</p>
            <div class="form-group">
              <label class="control-label col-md-2">Nama Obat Produksi</label>
              <div class="col-md-4">
                <input name="name" id="inputKeyObat" value="<?php echo isset($value)?$value->nama_brg:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                <input type="hidden" name="kode_brg_hidden" id="kode_brg_hidden" value="<?php echo isset($value)?$value->kode_brg:''?>">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Jumlah Produksi</label>
              <div class="col-md-1">
                <input name="jumlah_prod" id="jumlah_prod" value="<?php echo isset($value)?$value->jumlah_prod:''?>" placeholder="" class="format_number form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
              </div>

              <label class="control-label col-md-1">Rasio</label>
              <div class="col-md-1">
                <input name="rasio" id="rasio" value="<?php echo isset($value)?$value->rasio:''?>" placeholder="" class="format_number form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
              </div>

              <label class="control-label col-md-2">Jasa Produksi (%)</label>
              <div class="col-md-2">
                <input name="jasa_prod" id="jasa_prod" value="<?php echo isset($value)?$value->jasa_prod:''?>" placeholder="" class="format_number form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
              </div>

            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Tgl Produksi</label>
                <div class="col-md-2">
                    <div class="input-group">
                      <input name="tgl_resep" id="tgl_resep" placeholder="" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo isset($value)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_prod):''?>">
                      <span class="input-group-addon">
                        <i class="ace-icon fa fa-calendar"></i>
                      </span>
                    </div>
                </div>
                <label class="control-label col-md-1">Tgl Expired</label>
                <div class="col-md-2">
                    <div class="input-group">
                      <input name="tgl_resep" id="tgl_resep" data-date-format="yyyy-mm-dd" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo isset($value)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_expired):''?>">
                      <span class="input-group-addon">
                        <i class="ace-icon fa fa-calendar"></i>
                      </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
              <label class="col-md-2">&nbsp;</label>
              <div class="col-md-1">
                <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                  <i class="ace-icon fa fa-check-circle dark icon-on-right bigger-110"></i>
                  Submit
                </button>
              </div>
            </div>

            <!-- <br>
            <p style="font-weight: bold">KETERANGAN HARGA</p>
            <div class="form-group">
              <label class="col-md-2">Total Harga Komposisi</label>
              <div class="col-md-1">
                500,-
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2">Total Harga Produksi</label>
              <div class="col-md-1">
                500,-
              </div>
              <label class="col-md-2">Harga Satuan Produksi</label>
              <div class="col-md-1">
                500,-
              </div>
            </div> -->

          </form>

      </div>

        

    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


