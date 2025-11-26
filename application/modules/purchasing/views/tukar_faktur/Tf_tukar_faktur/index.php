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

var base_url = $('#dynamic-table').attr('base-url'); 
var params = $('#dynamic-table').attr('data-id');

$(document).ready(function() {
    
    //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "ordering": false,
        "searching": false,
        "bLengthChange": true,
        "bInfo": false,
        "paging": false,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+'/get_data?'+params+'&checked_nama_perusahaan='+$('#checked_nama_perusahaan').val()+'&nama_perusahaan='+$('#nama_perusahaan').val()+'&checked_no_po='+$('#checked_no_po').val()+'&no_po='+$('#no_po').val()+'&tahun='+$('#tahun').val()+'',
            "type": "POST"
        },
        "drawCallback": function (settings) { 
            // Here the response
            var response = settings.json;
            console.log(response.total);
            $('#txt_total_tagihan').text(formatMoney(response.total));
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], //last column
                "orderable": false, //set not orderable
            },
            { "aTargets" : [ 1 ], "mData" : 1, "sClass":  "details-control"}, 
            { "visible": true, "targets": [ 1 ] },
            { "targets": [ 2 ], "visible": false },
        ],

    });

    $('#dynamic-table tbody').on('click', 'td.details-control', function () {
        var url_detail = $('#dynamic-table').attr('url-detail');
        preventDefault();
        var tr = $(this).closest('tr');
        var row = oTable.row( tr );
        var data = oTable.row( $(this).parents('tr') ).data();
        var kode_primary = data[ 2 ];                  
        console.log(data);
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/            
            $.getJSON( url_detail + "/" + kode_primary + "?" +params, '' , function (data) {
                response_data = data;
                // Open this row
                row.child( format_html( response_data ) ).show();
                tr.addClass('shown');
            });
        }
        
    } );

    $('#dynamic-table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

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

    $( ".form-control" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
            $('#btn_search_data').click();    
          }         
          return false;                
        }       
    });

    $( "#nama_perusahaan" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
            event.preventDefault();         
            if($(this).valid()){           
            $('#btn_search_data').click();    
            }         
            return false;                
        }       
    });

    $("#btn_tukar_faktur").click(function(event){
            event.preventDefault();
            var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
            }).toArray();
            if(searchIDs.length < 0){
                alert('Tidak ada item yang dipilih!'); return false;
            }
            tukar_faktur(searchIDs);
            console.log(searchIDs);
    });

    $('#nama_perusahaan').typeahead({
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
            var label_item=item.split(':')[1];
            console.log(label_item);
            $('#nama_perusahaan').val(label_item);
            
        }
    }); 
        
})


function format_html ( data ) {
  return data.html;
}

function find_data_reload(result){

    oTable.ajax.url(base_url+'/get_data?'+result.data).load();
    // $("html, body").animate({ scrollTop: "400px" });

}

function reset_table(){
    oTable.ajax.url(base_url+'/get_data?'+params).load();
    // $("html, body").animate({ scrollDown: "400px" });

}

function checkAll(elm) {

if($(elm).prop("checked") == true){
    $('table .ace').each(function(){
        $(this).prop("checked", true);
    });
}else{
    $('table .ace').prop("checked", false);
}

}

function get_detail_brg_po(myid){

    if(confirm('Are you sure?')){

        $.ajax({
            url: 'purchasing/tukar_faktur/Tf_tukar_faktur/get_detail_brg_po',
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
            getMenuTabs('purchasing/tukar_faktur/Tf_tukar_faktur/create_po/'+$('#flag').val()+'?'+jsonResponse.params+'', 'tabs_form_po');
            achtungHideLoader();
            }

        });

    }else{
        return false;
    }

}

function tukar_faktur(data){
    $.ajax({
        url: $('#form_search').attr('action'),
        type: "post",
        data: {IDS : data, flag : $('#flag').val(), checked_nama_perusahaan : $('#checked_nama_perusahaan').val(), checked_no_po : $('#checked_no_po').val(), nama_perusahaan : $('#nama_perusahaan').val(), no_po : $('#no_po').val(), tahun: $('#tahun').val() },
        dataType: "json",
        success: function(response) {
        console.log(response.data);
        getMenu('purchasing/tukar_faktur/Tf_tukar_faktur/form?'+response.data+'');
        }
    });
}


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

    <form class="form-horizontal" method="post" id="form_search" action="purchasing/tukar_faktur/Tf_tukar_faktur/find_data?flag=<?php echo $flag?>" autocomplete="off">

        <!-- hidden form -->
        <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">
        <div class="form-group">
          <div class="control-label col-md-2">
            <div class="checkbox" style="margin-top: -5px">
              <label>
                <input name="checked_nama_perusahaan" id="checked_nama_perusahaan" type="checkbox" class="ace" value="1" <?php echo isset($_GET['checked_nama_perusahaan']) ? ($_GET['checked_nama_perusahaan'] == 1) ? 'checked' : '' : '' ?>>
                <span class="lbl"> Nama Perusahaan</span>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="margin-left: -15px">
              <input type="text" value="<?php echo isset($_GET['nama_perusahaan']) ? $_GET['nama_perusahaan'] : '' ?>" name="nama_perusahaan" id="nama_perusahaan" class="form-control">
          </div>

          <div class="control-label col-md-1">
            <div class="checkbox" style="margin-top: -5px">
              <label>
                <input name="checked_no_po" id="checked_no_po" value="1" type="checkbox" class="ace" <?php echo isset($_GET['checked_no_po']) ? ($_GET['checked_no_po'] == 1) ? 'checked' : '' : '' ?>>
                <span class="lbl"> No. PO</span>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="margin-left: -15px">
              <input type="text" value="<?php echo isset($_GET['no_po']) ? $_GET['no_po'] : '' ?>" name="no_po" id="no_po" class="form-control">
          </div>
          <label class="control-label col-md-1">Tahun</label>
            <div class="col-sm-1">
                <?php echo $this->master->get_tahun(isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'),'tahun','tahun','form-control','','')?>
            </div>
            
          <div class="col-md-3" style="margin-left: -15px">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Tampilkan Data
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-danger">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reload
            </a>
          </div>
        </div>

      
      <hr class="separator">
      <div class="pull-left">
        <b>DATA PENERIMAAN BARANG SUPPLIER</b><br>
        Silahkan lakukan pencarian untuk menampilkan data.<br><br>
        <a href="#" id="btn_tukar_faktur" class="label label-xs label-warning">Tukar Faktur</a>
      </div>
      <div class="pull-right">Total Hutang<br><b><span id="txt_total_tagihan" style="font-size: 18px"></span></b></div>
      <div class="clearfix"></div>
      <div style="margin-top:-25px">

        <table id="dynamic-table" base-url="purchasing/tukar_faktur/Tf_tukar_faktur" data-id="flag=<?php echo $flag?>" url-detail="purchasing/tukar_faktur/Tf_tukar_faktur/get_detail" class="table table-bordered table-hover">

          <thead>
          <tr>  
            <th width="30px" class="center">
              <div class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" name="" onClick="checkAll(this);" value=""/>
                    <span class="lbl"></span>
                </label>
              </div>
            </th>
            <th width="40px" class="center"></th>
            <th width="40px"></th>
            <th width="50px">ID</th>
            <th width="140px">Kode Penerimaan</th>
            <th width="120px">Nomor PO</th>
            <th width="120px">Tgl Diterima</th>
            <th>Nama Supplier</th>
            <th width="120px">No Faktur</th>
            <th width="120px">Penerima</th>
            <th width="120px">Keterangan</th>
            <th width="100px">Total</th>
            <th width="50px" class="center">TF</th>
            <th width="50px" class="center">BAST</th>
            
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




