<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

var oTableJadwalDokter;
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
            $('#KdPoli').val(strValue);
        }
    });

    //initiate dataTables plugin
    oTableJadwalDokter = $('#dynamic-table').DataTable({ 
          
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
            oTableJadwalDokter.$('tr.selected').removeClass('selected');
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
    oTableJadwalDokter.ajax.url(base_url+'?'+data).load();

}

function reset_table(){
    oTableJadwalDokter.ajax.url(base_url).load();

}

function reload_table_jadwal_dokter(){
   oTableJadwalDokter.ajax.reload(); //reload datatable ajax 
}
  
function show_data_jadwal_dokter(myid){
    
    preventDefault();
    $.ajax({

        url: 'ws_bpjs/ws_index/show_data_jadwal_dokter',
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
            $('#KdPoli').val(obj.poliTujuan);
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

                <form class="form-horizontal" method="post" id="form_search" action="ws_bpjs/Ws_index/find_data"  enctype="Application/x-www-form-urlencoded" >

                    <div class="col-md-12 no-padding">
                        <p style="font-weight: bold">PENCARIAN JADWAL PRAKTEK DOKTER</p>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Pilih Poli/Klinik</label>
                            <div class="col-md-3">
                                <input id="inputKeyPoli" class="form-control" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                                <input type="hidden" name="KdPoli" value="" id="KdPoli">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-2">Jenis Kontrol</label>
                            <div class="col-md-5">
                                <div class="radio">
                                        <label>
                                        <input name="JnsKontrol" type="radio" class="ace" value="1" checked/>
                                        <span class="lbl"> SPRI</span>
                                        </label>
                                        <label>
                                        <input name="JnsKontrol" type="radio" class="ace" value="2" />
                                        <span class="lbl"> Rencana Kontrol RJ </span>
                                        </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Tanggal Kontrol</label>
                            <div class="col-md-2">
                            <div class="input-group">
                                <input name="TglRencanaKontrol" id="TglRencanaKontrol" value="<?php echo date('Y-m-d')?>" placeholder="ex : yyyy-MM-dd" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd">
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
                        <table id="dynamic-table" base-url="ws_bpjs/Ws_index/get_jadwal_praktek_dokter" class="table">
                            <thead>
                            <tr style="background: #add46d;">  
                                <th width="30px" class="center">No</th>
                                <th width="100px">Kode Dokter</th>
                                <th>Nama Dokter</th>
                                <th width="120px">Jam Praktek</th>
                                <th width="120px">Kuota</th>
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


