<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

var oTable;
var base_url = $('#dynamic-table').attr('base-url'); 
var params = $('#dynamic-table').attr('data-id'); 

jQuery(function($) {

  
});

$(document).ready(function(){
    
    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true
        }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
    
    $('#inputKeyFaskes').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "ws_bpjs/Ws_index/getRef?ref=RefFaskes",
                data: { keyword:query,jf:$('input[name=jenis_faskes]:checked').val() }, 
                dataType: "json",
                type: "POST",
                success: function (response) {
                    result($.map(response, function (item) {
                        return item;    
                    }));
                },
                
            });
        },
        afterSelect: function (item) {
            // do what is needed with item
            var val_item=item.split(':')[0];
            var label_item=item.split(':')[1];
            console.log(val_item);
            $('#inputKeyFaskes').val(label_item);
            $('#KodePPKRujukan').val(val_item);
        }
    });

    $('#formCreateSuratKontrol').ajaxForm({
      beforeSend: function() {
        achtungShowLoader();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {    

        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        
        if(jsonResponse.status == 200){
            reload_table_surat_kontrol();
            // reset form
            $('#formCreateSuratKontrol')[0].reset();
            $.achtung({message: jsonResponse.message, timeout:5});
        }else{

          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});

        }

        achtungHideLoader();
      }
    }); 

    //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "ordering": false,
        "searching": false,
        "bInfo": false,
        "bFilter": false,
        "bLengthChange": false,
        "bPaginate": false,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url,
            "type": "POST"
        },

    });
    

    $('#dynamic-table tbody').on( 'click', 'tr', function () {
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
            reset_table();
    });

    
    

})

function find_data_reload(result, base_url){
  
    var data = result.data;    
    oTable.ajax.url(base_url+'?'+data).load();

}

function reset_table(){
    oTable.ajax.url(base_url).load();

}

function reload_table_surat_kontrol(){
   oTable.ajax.reload(); //reload datatable ajax 
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

                <form class="form-horizontal" method="post" id="form_search" action="ws_bpjs/Ws_index/find_data" autocomplete="off">

                    <div class="col-md-12 no-padding">
                        <p style="font-weight: bold">LIST SPESIALISTIK RUJUKAN</p>

                        <div class="form-group">
                            <label class="control-label col-md-2">Jenis Faskes</label>
                            <div class="col-md-6">
                                <div class="radio">
                                    <label>
                                        <input name="jenis_faskes" type="radio" class="ace" value="1" />
                                        <span class="lbl"> Faskes 1 / Puskesmas</span>
                                    </label>
                                    <label>
                                        <input name="jenis_faskes" type="radio" class="ace" value="2" checked/>
                                        <span class="lbl"> Faskes 2 / RS </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label class="col-md-2 control-label">PPK Rujukan</label>
                            <div class="col-md-4">
                                <input type="text" name="PPKRujukanLabel" class="form-control" value="" id="inputKeyFaskes">
                                <input type="hidden" name="PPKRujukan" class="form-control" value="" id="KodePPKRujukan">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Tanggal Rujukan</label>
                            <div class="col-md-2">
                            <div class="input-group">
                                <input name="TglRujukan" id="TglRujukan" value="<?php echo date('Y-m-d')?>" placeholder="ex : yyyy-MM-dd" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd">
                                <span class="input-group-addon">
                                    <i class="ace-icon fa fa-calendar"></i>
                                </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                            <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
                                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                                Search
                            </a>
                            <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
                                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                                Reset
                            </a>
                            </div>

                        </div>

                        </div>
                        
                        <hr class="separator">
                        <div style="margin-top:-30px" id="table-data">
                        <table id="dynamic-table" base-url="ws_bpjs/Ws_index/list_spesialistik_rujukan" class="table">
                            <thead>
                            <tr style="background: #add46d;">  
                                <th width="30px" class="center">No</th>
                                <th width="120px">Kode</th>
                                <th width="100px">Nama Spesialis</th>
                                <th width="120px">Kapasitas</th>
                                <th width="120px">Jumlah Rujukan</th>
                                <th width="120px">Persentase</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    </form>
            </div>
        </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


