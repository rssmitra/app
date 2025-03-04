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
    
    $('#inputKeyDiagnosaPrimer').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "ws_bpjs/Ws_index/getRef?ref=RefDiagnosa",
        data: 'keyword=' + query,            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                  }
              });
          }
    });

    $('#inputKeyDiagnosaSekunder').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "ws_bpjs/Ws_index/getRef?ref=RefDiagnosa",
        data: 'keyword=' + query,            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                  }
              });
          }
    });

    $('#inputKeyProcedure').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "ws_bpjs/Ws_index/getRef?ref=RefProcedure",
        data: 'keyword=' + query,            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                  }
              });
          }
    });

    $('#formCreateRujukanKhusus').ajaxForm({
      beforeSend: function() {
        achtungShowLoader();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {    

        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        
        var str = JSON.stringify(jsonResponse, undefined, 4);
        var output_highlight = syntaxHighlight(str);
        console.log(output_highlight);
        $('#find-result').html('<pre>'+output_highlight+'</pre>');
        
        // if(jsonResponse.status == 200){
        //     reload_table_surat_kontrol();
        //     // reset form
        //     $('#formCreateRujukanKhusus')[0].reset();
        //     var str = JSON.stringify(jsonResponse, undefined, 4);
        //     var output_highlight = syntaxHighlight(str);
        //     console.log(output_highlight);
        //     $('#find-result').html('<pre>'+output_highlight+'</pre>');
            
        // }else{

        //     var str = JSON.stringify(jsonResponse, undefined, 4);
        //     var output_highlight = syntaxHighlight(str);
        //     console.log(output_highlight);
        //     $('#find-result').html('<pre>'+output_highlight+'</pre>');

        // }

        achtungHideLoader();
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
            <form class="form-horizontal" method="post" id="formCreateRujukanKhusus" action="<?php echo base_url().'ws_bpjs/ws_index/insertRujukanKhusus'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">
                    <br>
                    <p style="font-weight: bold">FORM PEMBUATAN RUJUKAN KHUSUS</p>
                    <!-- hidden form -->
                    <input type="hidden" class="form-control" id="user" name="user" value="<?php echo $this->session->userdata('user')->fullname?>">
                    
                    <div class="form-group">
                        <label class="control-label col-md-2">Nomor Rujukan</label>
                        <div class="col-md-2">
                            <input type="text" name="noRujukan" id="noRujukan" class="form-control" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Diagnosa Primer</label>
                        <div class="col-md-4">
                        <input id="inputKeyDiagnosaPrimer" class="form-control" name="diagnosa_primer" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Diagnosa Sekunder</label>
                        <div class="col-md-4">
                        <input id="inputKeyDiagnosaSekunder" class="form-control" name="diagnosa_sekunder" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Prosedur</label>
                        <div class="col-md-4">
                            <input type="text" name="procedure" id="inputKeyProcedure" class="form-control" placeholder="Masukan keyword minimal 3 karakter">
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
                <div id="find-result"></div>

                <form class="form-horizontal" method="post" id="form_search" action="ws_bpjs/Ws_index/find_data"  enctype="Application/x-www-form-urlencoded" >

                    <div class="col-md-12 no-padding">
                        <p style="font-weight: bold">DATA RUJUKAN KHUSUS</p>
                        
                        <div class="form-group">
                            <label class="col-md-1 control-label">Pilih Bulan</label>
                            <div class="col-md-2">
                                <?php echo $this->master->get_bulan(date('m'),'Bulan','Bulan','form-control','','');?>
                            </div>
                            <label class="col-md-1 control-label">Tahun</label>
                            <div class="col-md-1">
                                <?php echo $this->master->get_tahun(date('Y'),'Tahun','Tahun','form-control','','');?>
                            </div>
                            <div class="col-md-6" style="margin-left: -1%">
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

                        <hr class="separator">
                        <div style="margin-top:-30px" id="table-data">
                            <table id="dynamic-table" base-url="ws_bpjs/Ws_index/list_rujukan_khusus" class="table">
                                <thead>
                                <tr style="background: #add46d;">  
                                    <th width="30px" class="center">No</th>
                                    <th>ID Rujukan</th>
                                    <th>No Rujukan</th>
                                    <th>No Kartu</th>
                                    <th>Nama Peserta</th>
                                    <th>Diagnosa</th>
                                    <th>Tgl Rujukan Awal</th>
                                    <th>Tgl Rujukan Berkahir</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


