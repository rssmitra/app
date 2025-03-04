<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

var oTableJadwalDokter;
var base_url = $('#dynamic-table').attr('base-url'); 
var params = $('#dynamic-table').attr('data-id'); 


$(document).ready(function(){
    
    // click default
    $('#btn_search_data_surat_kontrol').click();

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
        

    $('#btn_search_data_surat_kontrol').click(function (e) {
        
            e.preventDefault();
            $.ajax({
            url: $('#form_search_surat_kontrol').attr('action'),
            type: "post",
            data: $('#form_search_surat_kontrol').serialize(),
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

$('#btn_add_surat_kontrol').click(function (e) {    
    e.preventDefault();
    $('#widget_content_surat_kontrol').load('ws_bpjs/ws_index?modWs=CreateSuratKontrol&no_mr='+$('#nomrsuratkontrol').val()+'');
    $('.page-header').hide();
});
  
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
                <div id="widget_content_surat_kontrol">
                    <form class="form-horizontal" method="post" id="form_search_surat_kontrol" action="ws_bpjs/Ws_index/find_data"  enctype="Application/x-www-form-urlencoded" >

                        <input type="hidden" name="nomrsuratkontrol" id="nomrsuratkontrol" value="<?php echo isset($_GET['no_mr'])?$_GET['no_mr']:''?>">
                        <input type="hidden" name="jnsKontrol" id="jnsKontrol" value="1">

                        <div class="col-md-12 no-padding">
                            <p style="font-weight: bold">PENCARIAN SURAT KONTROL DENGAN NOMOR KARTU</p>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Pilih Bulan</label>
                                <div class="col-md-2">
                                    <?php echo $this->master->get_bulan(date('m'),'Bulan','Bulan','form-control','','');?>
                                </div>
                                <label class="col-md-1 control-label">Tahun</label>
                                <div class="col-md-1">
                                    <?php echo $this->master->get_tahun(date('Y'),'Tahun','Tahun','form-control','','');?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">No Kartu BPJS</label>
                                <div class="col-md-3">
                                    <input type="text" name="Nokartu" class="form-control" value="<?php echo isset($_GET['nokartu'])?$_GET['nokartu']:''?>" id="Nokartu">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-2">Pencarian Berdasarkan</label>
                                <div class="col-md-5">
                                    <div class="radio">
                                            <label>
                                            <input name="filter" type="radio" class="ace" value="1"/>
                                            <span class="lbl"> Tanggal Entri</span>
                                            </label>
                                            <label>
                                            <input name="filter" type="radio" class="ace" value="2" checked/>
                                            <span class="lbl"> Tanggal Rencana Kontrol </span>
                                            </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3 no-padding">
                                <a href="#" id="btn_add_surat_kontrol" class="btn btn-xs btn-primary">
                                    <i class="ace-icon fa fa-plus icon-on-right bigger-110"></i>
                                    Buat Surat Kontrol
                                </a>
                                <a href="#" id="btn_search_data_surat_kontrol" class="btn btn-xs btn-default">
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
                            <table id="dynamic-table" base-url="ws_bpjs/Ws_index/find_surat_kontrol_by_noka" class="table">
                                <thead>
                                <tr style="background: #add46d;">  
                                    <th width="30px" class="center">No</th>
                                    <th>Nama Pasien</th>
                                    <th>No Kartu</th>
                                    <th>No Surat Kontrol</th>
                                    <th>Jenis Pelayanan</th>
                                    <th>Jenis Kontrol</th>
                                    <th>Tgl Kontrol</th>
                                    <th>No SEP</th>
                                    <th>Poli Tujuan</th>
                                    <th>Nama Dokter</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


