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
    
    $('#inputKeyPoli').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "ws_bpjs/Ws_index/getRef?ref=RefPoli",
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
            var strValue = $.trim(val_item.toString());
            console.log(strValue);
            $('#inputKeyPoli').val(label_item);
            $('#kodePoliHidden').val(strValue);
        }
    });

    $('#InputKeydokterDPJP').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "ws_bpjs/Ws_index/getRef?ref=RefDokterDPJP",
                data: { spesialis:$('#kodePoliHidden').val(),jp:$('input[name=jnsPelayanan]:checked').val(),tgl:$('#tglRencanaKontrol').val() },            
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
        $('#InputKeydokterDPJP').val(label_item);
        $('#KodedokterDPJP').val(val_item);
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
        
    $("#button_delete").click(function(event){
            event.preventDefault();
            var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
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

    $('input[type=radio][name=jnsPelayanan]').change(function () {

        if (this.value == 1) {
            $('#div_noka').show();
            $('#div_no_sep').hide();
        }
        if (this.value == 2) {
            $('#div_noka').hide();
            $('#div_no_sep').show();
        }
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
  
function delete_surat_kontrol(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'ws_bpjs/ws_index/delete_surat_kontrol',
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
          if(jsonResponse.status == 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            reload_table_surat_kontrol();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }
  
}

function show_data_surat_kontrol(myid){
    
    preventDefault();
    $.ajax({

        url: 'ws_bpjs/ws_index/show_data_surat_kontrol',
        type: "post",
        data: {ID:myid},
        dataType: "json",
        success: function(response) {     

            console.log(response);

          if(response.status == 200){
            // put data
            var obj = response.data;
            var peserta = obj.sep.peserta;
            $('#noSuratKontrol').val(obj.noSuratKontrol);
            $('#noSEP').val(obj.sep.noSep);
            $('#tglRencanaKontrol').val(obj.tglRencanaKontrol);
            $('#inputKeyPoli').val(obj.namaPoliTujuan);
            $('#kodePoliHidden').val(obj.poliTujuan);
            $('#InputKeydokterDPJP').val(obj.namaDokter);
            $('#KodedokterDPJP').val(obj.kodeDokter);
            var jnsPelayanan = (obj.sep.jnsPelayanan == 'Rawat Jalan') ? 2 : 1;
            $("input[name=jnsPelayanan][value=" + jnsPelayanan + "]").prop('checked', true);
            if(jnsPelayanan == 1){
                $('#noKartu').val(peserta.noKartu);
                $('#div_noka').show();
                $('#div_no_sep').hide();
            }else{
                $('#div_noka').hide();
                $('#div_no_sep').show();
            }

          }else{
            $.achtung({message: response.message, timeout:5, className: 'achtungFail'});
          }
        }

    });
  
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

                <form class="form-horizontal" method="post" id="form_search" action="ws_bpjs/Ws_index/find_data">

                    <div class="col-md-12 no-padding">
                        <p style="font-weight: bold">LIST RUJUKAN KELUAR RS</p>

                        <div class="form-group">
                            <label class="control-label col-md-2">Dari Tanggal</label>
                            <div class="col-md-2">
                            <div class="input-group">
                                <input name="tglMulai" id="tglMulai" value="<?php echo date('Y-m-d')?>" placeholder="ex : yyyy-MM-dd" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd">
                                <span class="input-group-addon">
                                    <i class="ace-icon fa fa-calendar"></i>
                                </span>
                                </div>
                            </div>
                            <label class="control-label col-md-1">s.d Tanggal</label>
                            <div class="col-md-2">
                            <div class="input-group">
                                <input name="tglAkhir" id="tglAkhir" value="<?php echo date('Y-m-d')?>" placeholder="ex : yyyy-MM-dd" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd">
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
                        <table id="dynamic-table" base-url="ws_bpjs/Ws_index/get_data_rujukan_keluar_rs" class="table">
                            <thead>
                            <tr style="background: #add46d;">  
                                <th width="30px" class="center">No</th>
                                <th width="120px">No. Rujukan</th>
                                <th width="100px">Tgl Rujukan</th>
                                <th width="120px">No SEP</th>
                                <th width="120px">No Kartu</th>
                                <th>Nama</th>
                                <th>PPK Dirujuk</th>
                                <th width="80px">Edit</th>
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


