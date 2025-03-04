<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

var oTableDataSuratKontrol;
var oTableJadwalDokter;
var base_url_poli = $('#dynamic-table-poli').attr('base-url'); 
var base_url = $('#dynamic-table-surat-kontrol-by-noka').attr('base-url'); 
var params = $('#dynamic-table-surat-kontrol-by-noka').attr('data-id'); 

$(document).ready(function(){
    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true
        }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
    
    $('#inputKeyPoliSuratKontrol').typeahead({
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
            $('#inputKeyPoliSuratKontrol').val(label_item);
            $('#kodePoliHiddenSuratKontrol').val(strValue);
        }
    });

    $('#InputKeydokterDPJPSuratKontrol').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "ws_bpjs/Ws_index/getRef?ref=RefDokterDPJP",
                data: { spesialis:$('#kodePoliHiddenSuratKontrol').val(),jp:$('input[name=jnsPelayanan]:checked').val(),tgl:$('#tglRencanaKontrol').val() },            
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
        $('#InputKeydokterDPJPSuratKontrol').val(label_item);
        $('#KodedokterDPJPSuratKontrol').val(val_item);
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
            var objDt = jsonResponse.data;
            reload_table_surat_kontrol();
            // reset form
            $('#formCreateSuratKontrol')[0].reset();
            $.achtung({message: jsonResponse.message, timeout:5});
            // put no surat kontrol
            $('#noSuratKontrolPerjanjianForm').val(objDt.noSuratKontrol);
        }else{

          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});

        }

        achtungHideLoader();
      }
    }); 

    //initiate dataTables plugin
    oTableDataSuratKontrol = $('#dynamic-table-surat-kontrol-by-noka').DataTable({ 
          
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
            "url": base_url+'?nomrsuratkontrol='+$('#nomrsuratkontrol').val()+'&jnsKontrol='+$('#jnsKontrol').val()+'&Bulan='+$('#Bulan').val()+'&Tahun='+$('#Tahun').val()+'&Nokartu='+$('#Nokartu').val()+'&filter=2',
            "type": "POST"
        },

    });

    //initiate dataTables plugin
    oTableJadwalDokter = $('#dynamic-table-poli').DataTable({ 
          
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
            "url": base_url_poli,
            "type": "POST"
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

    $('#dynamic-table-poli tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTableJadwalDokter.row( tr );
        var data = oTableJadwalDokter.row( $(this).parents('tr') ).data();

        var kodePoli = data[ 2 ];
        $('#kodePoliHiddenSuratKontrol').val(kodePoli);
        var namaPoli = data[ 4 ];
        $('#inputKeyPoliSuratKontrol').val(namaPoli);

        

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/
            
            $.getJSON("ws_bpjs/Ws_index/get_jadwal_praktek_dokter_html?KdPoli="+kodePoli+"&JnsKontrol=2&TglRencanaKontrol="+$('#tglRencanaKontrol').val()+"" , '', function (data) {
                response_data = data;
                // Open this row
                row.child( format_html( response_data ) ).show();
                tr.addClass('shown');
            });
            
        }
    });

    // table data kunjungan
    var no_mr = $('#noMrHidden').val();
    var url = 'registration/Reg_pasien/get_riwayat_pasien_only_bpjs?mr='+no_mr+'&kode_bagian=';

    table_data_kunjungan = $('#dynamic-table-kunjungan-sep').DataTable({ 
      
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bInfo": false,
    //   "scrollY": "500px",
      //"scrollX": "500px",
      "lengthChange": false,
      "bPaginate": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": url,
          "type": "POST"
      },
    
    });

    $('#dynamic-table-surat-kontrol-by-noka tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTableDataSuratKontrol.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
        
    $("#button_delete").click(function(event){
            event.preventDefault();
            var searchIDs = $("#dynamic-table-surat-kontrol-by-noka input:checkbox:checked").map(function(){
            return $(this).val();
            }).toArray();
            delete_data(''+searchIDs+'')
            console.log(searchIDs);
    });

    $('#btn_search_data_surat_kontrol').click(function (e) {
        
            e.preventDefault();
            $.ajax({
            url: $('#form_search_surat_kontrol_by_noka').attr('action'),
            type: "post",
            data: $('#form_search_surat_kontrol_by_noka').serialize(),
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

function format_html ( data ) {
  return data.html;
}

function find_data_reload(result, base_url){
    var data = result.data;    
    oTableDataSuratKontrol.ajax.url(base_url+'?'+data).load();
}

function reset_table(){
    oTableDataSuratKontrol.ajax.url(base_url).load();
}

function reload_table_surat_kontrol(){
   oTableDataSuratKontrol.ajax.reload(); //reload datatable ajax 
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
            var objDt = response.data;
            console.log(objDt);
            if(response.status == 200){
                // put data
                var obj = response.data;
                var peserta = obj.sep.peserta;
                $('#nomrsuratkontrol').val(obj.noSuratKontrol);
                $('#PerjanjianForm').val(obj.PerjanjianForm);
                $('#noSEP').val(obj.sep.noSep);
                $('#tglRencanaKontrol').val(obj.tglRencanaKontrol);
                $('#inputKeyPoliSuratKontrol').val(obj.namaPoliTujuan);
                $('#kodePoliHiddenSuratKontrol').val(obj.poliTujuan);
                $('#InputKeydokterDPJPSuratKontrol').val(obj.namaDokter);
                $('#KodedokterDPJPSuratKontrol').val(obj.kodeDokter);
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

function find_poli_kontrol(){  
    preventDefault();
    if($('#noSEP').val() == ''){
        alert('Masukan No SEP!');
        return false;
    }
    oTableJadwalDokter.ajax.url(base_url_poli+'?JnsKontrol=2&nomor='+$('#noSEP').val()+'&TglRencanaKontrol='+$('#tglRencanaKontrol').val()).load();
    $('#tglRencanaKontrolEdited').val($('#tglRencanaKontrol').val());
    $("#modal-show-poli-kontrol").modal();
}

function reload_poli_kontrol(){  
    preventDefault();
    oTableJadwalDokter.ajax.url(base_url_poli+'?JnsKontrol=2&nomor='+$('#noSEP').val()+'&TglRencanaKontrol='+$('#tglRencanaKontrolEdited').val()).load();
    $('#tglRencanaKontrol').val($('#tglRencanaKontrolEdited').val());
    $("#modal-show-poli-kontrol").modal();
}

function selected_jadwal_dokter(kode_dokter, nama_dokter){  
    preventDefault();
    $('#KodedokterDPJPSuratKontrol').val(kode_dokter);
    $('#InputKeydokterDPJPSuratKontrol').val(nama_dokter);
    $("#modal-show-poli-kontrol").modal('toggle');
}

function findLastSep(no_mr){  
    preventDefault();
    $("#modal-find-sep").modal();
}

function selectSep(no_sep){  
    preventDefault();
    $("#noSEP").val(no_sep);
    $("#modal-find-sep").modal('toggle');
}

function copySuratKontrol(surat_kontrol){
  preventDefault();
  $('#noSuratSKDP').val(surat_kontrol);
  $('#noSuratKontrolPerjanjianForm').val(surat_kontrol);
  $('#globalModalView').modal('hide');
  $('#show_dpjp').focus();
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
            <form class="form-horizontal" method="post" id="formCreateSuratKontrol" action="<?php echo base_url().'ws_bpjs/ws_index/insertSuratKontrol'?>" enctype="Application/x-www-for
            var objDt = jsonResponse.data;m-urlencoded" autocomplete="off">
                <br>
                <p style="font-weight: bold">FORM PEMBUATAN RENCANA SURAT KONTROL</p>
                <!-- hidden form -->
                <input type="hidden" class="form-control" id="user" name="user" value="<?php echo $this->session->userdata('user')->fullname?>">
                <input type="hidden" class="form-control" id="PerjanjianForm" name="PerjanjianForm" value="0">
                <input type="hidden" name="nomrsuratkontrol" id="nomrsuratkontrol" value="<?php echo isset($_GET['no_mr'])?$_GET['no_mr']:''?>">


                <div class="form-group">
                    <label class="control-label col-md-2">Jenis Pelayanan</label>
                    <div class="col-md-3">
                        <div class="radio">
                            <label>
                            <input name="jnsPelayanan" type="radio" class="ace" value="1" />
                            <span class="lbl"> Rawat Inap</span>
                            </label>
                            <label>
                            <input name="jnsPelayanan" type="radio" class="ace" value="2" checked />
                            <span class="lbl"> Rawat Jalan </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Tanggal Rencana Kontrol</label>
                    <div class="col-md-2">
                        <div class="input-group">
                            <input name="tglRencanaKontrol" id="tglRencanaKontrol" value="<?php echo isset($_GET['tglRencanaKontrol'])?$_GET['tglRencanaKontrol']:date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text">
                                <span class="input-group-addon">
                                    <i class="ace-icon fa fa-calendar"></i>
                                </span>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="div_no_sep">
                    <label class="control-label col-md-2">Nomor SEP Lama</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" name="noSEP" id="noSEP" class="form-control search-query" value="<?php echo isset($_GET['nosep'])?$_GET['nosep']:''?>" onclick="findLastSep(<?php echo isset($_GET['no_mr'])?$_GET['no_mr']:''?>)">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-primary btn-sm" onclick="find_poli_kontrol()">
                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                    Jadwal Poli
                                </button>
                            </span>
                        </div>
                        
                        
                    </div>
                </div>

                <div class="form-group" id="div_noka" style="display:none">
                    <label class="control-label col-md-2">No Kartu BPJS</label>
                    <div class="col-md-2">
                        <input type="text" name="noKartu" id="noKartu" class="form-control" value="<?php echo isset($_GET['noKartu'])?$_GET['noKartu']:''?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Poli Tujuan </label>
                    <div class="col-md-3">
                        <input id="inputKeyPoliSuratKontrol" class="form-control" name="tujuan" type="text"/>
                        <input type="hidden" name="kodePoliHidden" value="" id="kodePoliHiddenSuratKontrol">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-md-2">Dokter DPJP</label>
                    <div class="col-md-3">
                        <input id="InputKeydokterDPJPSuratKontrol" class="form-control" name="dokterDPJP" type="text"/>
                        <input type="hidden" name="KodedokterDPJP" value="" id="KodedokterDPJPSuratKontrol">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-1 no-padding">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="ace-icon fa fa-save icon-on-right bigger-110"></span>
                            Submit
                        </button>
                    </div>
                </div>

            </form>
            <hr>
            <form class="form-horizontal" method="post" id="form_search_surat_kontrol_by_noka" action="ws_bpjs/Ws_index/find_data">
                <div class="col-md-12 no-padding">
                    <p style="font-weight: bold">RENCANA SURAT KONTROL</p>

                    
                    <input type="hidden" name="jnsKontrol" id="jnsKontrol" value="1">

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
                        <div class="col-md-3">
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

                    <hr class="separator">
                    <div style="margin-top:-30px" id="table-data">
                    <table id="dynamic-table-surat-kontrol-by-noka" base-url="ws_bpjs/Ws_index/find_surat_kontrol_by_noka" class="table">
                        <thead>
                        <tr style="background: #add46d;">  
                            <th width="30px" class="center">No</th>
                            <th>No. Surat Kontrol</th>
                            <th>Nama Pasien</th>
                            <th>Jenis Pelayanan</th>
                            <th>Jenis Kontrol</th>
                            <th>Tgl Rencana Kontrol</th>
                            <th>No/Tgl SEP Asal</th>
                            <th>Poli Asal</th>
                            <th>Poli Tujuan</th>
                            <th>Dokter</th>
                            <th width="120px" class="center"></th>
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

<div id="modal-show-poli-kontrol" class="modal fade" tabindex="-1">
    <div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;width:70%; background-color: white">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <span class="white">&times;</span>
                </button>
                <span style="color: white; font-weight: bold">JADWAL POLI SPESIALIS</span>
            </div>
            <div class="modal-body" style="min-height: 400px !important">
                <div class="form-group">
                    <label class="control-label col-md-2" style="margin-top: 4px">Tgl Rencana Kontrol</label>
                    <div class="col-md-2">
                        <div class="input-group">
                            <input name="tglRencanaKontrolEdited" id="tglRencanaKontrolEdited" value="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text">
                                <span class="input-group-addon">
                                    <i class="ace-icon fa fa-calendar"></i>
                                </span>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-primary btn-sm" onclick="reload_poli_kontrol()">
                            <span class="ace-icon fa fa-save icon-on-right bigger-110"></span>
                            Reload
                        </button>
                    </div>
                </div>
                <table id="dynamic-table-poli" base-url="ws_bpjs/Ws_index/get_rencana_kontrol_poli_with_detail" class="table">
                    <thead>
                        <tr style="background: #add46d;">  
                            <th width="30px" class="center">No</th>
                            <th width="30px" class="center"></th>
                            <th width="80px"></th>
                            <th width="50px">Kode Poli</th>
                            <th width="200px">Nama Poli</th>
                            <th width="50px">Kapasitas</th>
                            <th width="110px">Jumlah Rencana Kontrol</th>
                            <th width="50px">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal-find-sep" class="modal fade" tabindex="-1">

    <div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;width:70%; background-color: white">

    <div class="modal-content">

        <div class="modal-header">

        <!-- <div class="table-header"> -->

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

            </button>

            <span style="color: white; font-weight: bold">RIWAYAT KUNJUNGAN PASIEN BPJS</span>

        <!-- </div> -->

        </div>

        <div class="modal-body" style="min-height: 600px !important">

            <table id="dynamic-table-kunjungan-sep" class="table">
                <thead>
                    <tr>  
                        <th width="30px">No</th>
                        <th width="170px">Tanggal</th>
                        <th width="130px">No. SEP</th>
                        <th width="230px">Tujuan Poli</th>
                        <th width="230px">Dokter DPJP</th>
                        <th width="100px">Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>


        </div>

    </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->

</div>


