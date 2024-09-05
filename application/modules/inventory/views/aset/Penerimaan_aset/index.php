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

  });

  var oTable;
  var base_url = $('#dynamic-table').attr('base-url'); 
  var params = $('#dynamic-table').attr('data-id'); 

  //initiate dataTables plugin
  oTable = $('#dynamic-table').DataTable({ 
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "bInfo": false,
    "paging": false,
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": base_url+'/get_data?'+params,
        "type": "POST"
    },
    // "columnDefs": [
    //     { 
    //       "targets": [ 0 ], //last column
    //       "orderable": false, //set not orderable
    //     },
    //     { "aTargets" : [ 1 ], "mData" : 1, "sClass":  "details-control"}, 
    //     { "visible": true, "targets": [ 1 ] },
    //     { "targets": [ 2 ], "visible": false },
    // ],
  });
  
  // $('#dynamic-table tbody').on('click', 'td.details-control', function () {
  //     var url_detail = $('#dynamic-table').attr('url-detail');
  //     preventDefault();
  //     var tr = $(this).closest('tr');
  //     var row = oTable.row( tr );
  //     var data = oTable.row( $(this).parents('tr') ).data();
  //     var kode_primary = data[ 2 ];                  
  //     console.log(data);
  //     if ( row.child.isShown() ) {
  //         // This row is already open - close it
  //         row.child.hide();
  //         tr.removeClass('shown');
  //     }
  //     else {
  //         /*data*/            
  //         $.getJSON( url_detail + "/" + kode_primary + "?" +params, '' , function (data) {
  //             response_data = data;
  //             // Open this row
  //             row.child( format_html( response_data ) ).show();
  //             tr.addClass('shown');
  //         });
  //     }
      
  // } );

  $('#dynamic-table tbody').on( 'click', 'tr', function () {
      if ( $(this).hasClass('selected') ) {
          $(this).removeClass('selected');
      }
      else {
          oTable.$('tr.selected').removeClass('selected');
          $(this).addClass('selected');
      }
  } );
  
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

  $( "#inputSupplier" ).keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){          
        event.preventDefault();         
        if($(this).valid()){           
          $('#btn_search_data').click();    
        }         
        return false;                
      }       
  });

  $("#btn_create_po").click(function(event){
        event.preventDefault();
        var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
          return $(this).val();
        }).toArray();
        get_detail_brg_po(''+searchIDs+'')
        console.log(searchIDs);
  });

  function checkAll(elm) {
    if($(elm).prop("checked") == true){
      $('.ace').each(function(){
          $(this).prop("checked", true);
      });
    }else{
      $('.ace').prop("checked", false);
    }
  }

  function get_detail_brg_po(myid){
    if(confirm('Are you sure?')){

      $.ajax({
          url: 'inventory/aset/Penerimaan_aset/get_detail_brg_po',
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
            getMenuTabs('inventory/aset/Penerimaan_aset/create_po/'+$('#flag').val()+'?'+jsonResponse.params+'', 'tabs_form_po');
            achtungHideLoader();
          }

      });

    }else{
      return false;
    }
  }

  function format_html ( data ) {
    return data.html;
  }

  function find_data_reload(result){
      oTable.ajax.url(base_url+'/get_data?'+result.data).load();
  }

  function reset_table(){
      oTable.ajax.url(base_url+'/get_data?'+params).load();
  }

  function reload_table(){
    oTable.ajax.reload(); //reload datatable ajax 
  }  


$('select[name="search_by"]').change(function () {      

    if( $(this).val() == 'month'){
      /*show form month*/
      $('#div_month').show();
      $('#div_keyword').hide();
      $('#div_supplier').hide();
    }

    if( $(this).val() == 'no_po'){
      /*show form month*/
      $('#div_month').hide();
      $('#div_keyword').show();
      $('#div_supplier').hide();
    }

    if( $(this).val() == 'supplier'){
      /*show form supplier*/
      $('#div_month').hide();
      $('#div_keyword').hide();
      $('#div_supplier').show();
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
        // do what is needed with item
        var val_item=item.split(':')[0];
        console.log(val_item);
        $('#kodesupplier').val(val_item);
        
    }
});

$('#btn_reset_data').click(function (e) {
    e.preventDefault();
    reset_table();
    $('#form_search')[0].reset();
});

$('#btn_search_data').click(function (e) {
    var url_search = $('#form_search').attr('action');
    e.preventDefault();
    $.ajax({
      url: url_search,
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      success: function(data) {
        console.log(data.data);
        find_data_reload(data);
      }
    });
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

    <form class="form-horizontal" method="post" id="form_search" action="inventory/aset/Penerimaan_aset/find_data?flag=" autocomplete="off">

      <!-- hidden form -->
      <input type="hidden" name="flag" id="flag" value="">

      <div class="form-group">

          <label class="control-label col-md-1">Pencarian</label>
          <div class="col-md-2">
            <select name="search_by" id="search_by" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <option value="no_po" selected>Nomor PO</option>
              <option value="supplier">Nama Supplier</option>
              <option value="month">Bulan</option>
            </select>
          </div>

          <div id="div_month" style="display:none">
            <label class="control-label col-md-1">Bulan</label>
            <div class="col-md-2" style="margin-left: -15px">
              <?php echo $this->master->get_bulan('','month','month','form-control','','')?>
            </div>
          </div>

          <div id="div_supplier" style="display:none">
            <label class="control-label col-md-1">Suppplier</label>
            <div class="col-md-3" style="margin-left: -15px">
                <input id="inputSupplier" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />
                <input type="hidden" name="kodesupplier" id="kodesupplier" class="form-control" value="">
            </div>
          </div>

          <div id="div_keyword">
            <label class="control-label col-md-1">Keyword</label>
            <div class="col-md-2" style="margin-left: -15px">
              <input type="text" class="form-control" name="keyword" id="keyword_form">
            </div>
          </div>
          <div class="col-md-2" style="margin-left: -1%">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Search
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              Reset
            </a>
          </div>

      </div>

      <hr class="separator">

      <div style="margin-top:-25px">

          <table id="dynamic-table" base-url="inventory/aset/Penerimaan_aset" data-id="" url-detail="inventory/aset/Penerimaan_aset/get_detail" class="table table-bordered table-hover">

          <thead>
          <tr>  
            <th width="30px" class="center">
              <div class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" name="" onClick="checkAll(this);" value="0"/>
                    <span class="lbl"></span>
                </label>
              </div>
            </th>
            <th width="50px">ID</th>
            <th>Nomor PO</th>
            <th>Tanggal</th>
            <th>Supplier</th>
            <th>Nama Barang</th>
            <th>Kategori Aset</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Total</th>
            <th>PO</th>
            <th>Terima</th>
            
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




