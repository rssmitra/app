<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script type="text/javascript">
  jQuery(function($) {

    $('.date-picker').datepicker({
      autoclose: true,
      todayHighlight: true
    })
    //show datepicker when clicking on the icon
    .next().on(ace.click_event, function(){
      $(this).prev().focus();
    });

    var base_url = $('#table-monitoring-po').attr('base-url'); 

    //initiate dataTables plugin
    oTable = $('#table-monitoring-po').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": base_url,
          "type": "POST"
      },
      "drawCallback": function (response) {
        var objData = response.json;
          $('#total_po').text('Rp.'+formatMoney(objData.total_po)+',-');
          $('#nm_brg_max').html(objData.nm_brg_max+"<br>"+objData.ttl_brg_max);
      },

    });
    

    $('#table-monitoring-po tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

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
            oTable.ajax.url(base_url).load();
    });

    $('#btn_export_excel').click(function (e) {
      var url_search = $('#form_search').attr('action');
      e.preventDefault();
      $.ajax({
        url: url_search,
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        success: function(data) {
          console.log(data.data);
          export_excel(data);
        }
      });
    });
    
    function export_excel(result){

      window.open('purchasing/Po/Po_monitoring/export_excel?'+result.data+'','_blank'); 

    }

    function find_data_reload(result, base_url){

      var data = result.data;    
      oTable.ajax.url(base_url+'&'+data).load();

    }


  });


  $( "#keyword_form" ).keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){          
        event.preventDefault();         
        if($(this).valid()){           
          $('#btn_search_data').click();    
        }         
        return false;                
      }       
  });


  $('select[name="search_by"]').change(function () {      

      if( $(this).val() == 'month'){
        /*show form month*/
        $('#div_month').show();
        $('#div_keyword').hide();
        $('#div_supplier').hide();
        $('#div_tgl_po').hide();
      }

      if( $(this).val() == 'supplier'){
        /*show form month*/
        $('#div_supplier').show();
        $('#div_keyword').hide();
        $('#div_month').hide();
        $('#div_tgl_po').hide();
      }

      if( $(this).val() == 'no_po'){
        /*show form month*/
        $('#div_month').hide();
        $('#div_keyword').show();
        $('#div_supplier').hide();
        $('#div_tgl_po').hide();
      }

      if( $(this).val() == 'tgl_po'){
        /*show form month*/
        $('#div_month').hide();
        $('#div_keyword').hide();
        $('#div_supplier').hide();
        $('#div_tgl_po').show();
      }

  });

  $('#inputSupplier').typeahead({
    source: function (query, result) {
            $.ajax({
                url: "templates/references/getSupplier",
                data: 'keyword=' + query,         
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
        preventDefault();
        // do what is needed with item
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#inputSupplier').val(label_item);
        $('#kodesupplier').val(val_item);
    }
});


</script>
<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div>

    <form class="form-horizontal" method="post" id="form_search" action="purchasing/po/Po_monitoring/find_data">

      <div class="form-group">
        <label class="control-label col-md-2">Jenis PO</label>
          <div class="col-md-4">
            <div class="radio">
              <label>
                <input name="flag" type="radio" class="ace" value="medis" checked/>
                <span class="lbl"> Medis</span>
              </label>
              <label>
                <input name="flag" type="radio" class="ace" value="non_medis"/>
                <span class="lbl"> Non Medis</span>
              </label>
            </div>
          </div>
      </div>
      
      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by" id="search_by" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <option value="no_po">Nomor PO</option>
              <option value="month" selected>Bulan</option>
              <option value="supplier">Nama Supplier</option>
              <option value="tgl_po">Tanggal PO</option>
            </select>
          </div>

          <div id="div_month">
            <label class="control-label col-md-1">Bulan</label>
            <div class="col-md-1" style="margin-left: -15px">
              <?php echo $this->master->get_bulan(date('m'),'month','month','form-control','','')?>
            </div>
            <div class="col-md-1" style="margin-left: -15px">
              <?php echo $this->master->get_tahun(date('Y'),'year','year','form-control','','')?>
            </div>
          </div>

          <div id="div_supplier" style="display:none">
            <label class="control-label col-md-1">Cari Supplier</label>
            <div class="col-md-3" style="margin-left: -15px">
              <input id="inputSupplier" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />
              <input type="hidden" name="kodesupplier" id="kodesupplier" class="form-control">
            </div>
          </div>

          <div id="div_keyword" style="display:none">
            <label class="control-label col-md-1">Keyword</label>
            <div class="col-md-2" style="margin-left: -15px">
              <input type="text" class="form-control" name="keyword" id="keyword_form">
            </div>
          </div>

          <div id="div_tgl_po" style="display: none">
            <div class="col-md-2" style="margin-left: -1%">
              <div class="input-group">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
              </div>

              <div class="col-md-2" style="margin-left: -1%">
                <div class="input-group">
                  <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
          </div>

          <div class="col-md-3" style="margin-left:-1.1%">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Search
            </a>
            <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
              <i class="ace-icon fa fa-excel icon-on-right bigger-110"></i>
              Export Excel
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              Reset
            </a>
          </div>
      </div>
      <hr>

      <div class="col-md-12 no-padding">
      <table class="table">
        <tr>
          <td style="width: 50%"><i>Total Pembelian</i><br><span style="font-size: 24px; font-weight: bold" id="total_po">Rp.0,-</span></td>
          <td style="width: 50%"><i>Pembelian Barang Terbanyak</i><br><span style="font-size: 14px; font-weight: bold" id="nm_brg_max"></span></td>
        </tr>
      </table>
      <table id="table-monitoring-po" base-url="purchasing/po/Po_monitoring/get_data?flag=" data-id="flag=" class="table" >
          <thead>
          <tr>  
            <th width="30px" class="center">No</th>
            <th width="150px">Nomor PO</th>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Nama Supplier</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Rasio</th>
            <th>Satuan</th>
            <th>Qty</th>
            <th>Harga @</th>
            <th>Disc(%)</th>
            <th>Sub Total</th>
            <th>Status</th>
            
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




