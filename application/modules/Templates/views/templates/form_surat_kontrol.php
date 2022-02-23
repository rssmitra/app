<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

var oTable;
var oTableJadwalDokter;
var base_url_poli = $('#dynamic-table-poli').attr('base-url'); 
var base_url = $('#dynamic-table').attr('base-url'); 
var params = $('#dynamic-table').attr('data-id'); 

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
        console.log(jsonResponse);

        if(jsonResponse.status == 200){
            var objDt = jsonResponse.data;
            reload_table_surat_kontrol();
            // reset form
            $('#formCreateSuratKontrol')[0].reset();
            $('#noSuratKontrol').val(objDt.noSuratKontrol);
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
          $('#kodePoliHidden').val(kodePoli);
          var namaPoli = data[ 4 ];
          $('#inputKeyPoli').val(namaPoli);
  
          
  
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

function show_form_kontrol(flagType){
    preventDefault();
    if(flagType == 'show'){
        $('#div-form-kontrol').show();
    }else{
        $('#div-form-kontrol').hide();
    }
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
    $('#KodedokterDPJP').val(kode_dokter);
    $('#InputKeydokterDPJP').val(nama_dokter);
    $("#modal-show-poli-kontrol").modal('toggle');

}

function format_html ( data ) {
  return data.html;
}



</script>
<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
        <div class="widget-body">
            <span><i>Hasil pencarian perjanjian pasien...</i></span>
            <table class="table table-bordered">
                <tr style="background: #a9d265a6">
                    <th>Tgl Kontrol</th>
                    <th>Poli Tujuan/Dokter</th>
                    <th>Jam Praktek</th>
                    <th>Tgl Entri</th>
                    <th>Status</th>
                    <th></th>
                </tr>

                <tr>
                    <td id="TglRencanaKontrol">
                        <?php echo $tgl_kunjungan?><br>
                        <?php 
                            echo ($is_bridging == 1) ? '<span style="background: green; padding: 2px; color: white">Bridging</span>' : '<span style="background: red; padding: 2px; color: white">Not Bridging</span>' ;
                        ?>
                    </td>
                    <td id="PoliTujuan"><?php echo $poli?><br><?php echo $nama_dr?></td>
                    <td id="jamPrakter" class="center"><?php echo $jam_praktek?></td>
                    <td id="TglEntri" class="center"><?php echo $input_tgl?></td>
                    <td id="TglEntri" class="center"><?php echo ($tgl_kunjungan == date('Y-m-d')) ? '<span class="green" style="font-weight: bold">Available</span>' : '<span class="red" style="font-weight: bold">Not Available</span>' ; ?></td>
                    <td id="TglEntri" class="center"><a href="#" class="label label-success" onclick="show_form_kontrol('show')">Update</a></td>
                </tr>
            </table>

            <div class="widget-main no-padding" id="div-form-kontrol" style="display: none">

                <form class="form-horizontal" method="post" id="formCreateSuratKontrol" action="<?php echo base_url().'ws_bpjs/ws_index/insertSuratKontrol'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">
                    <!-- hidden form -->
                    <input type="hidden" class="form-control" id="user" name="user" value="<?php echo $this->session->userdata('user')->fullname?>">
                    <input type="hidden" class="form-control" id="noSuratKontrol" name="noSuratKontrol" value="<?php echo $kode_perjanjian?>">
                    <input type="hidden" class="form-control" id="isBridging" name="isBridging" value="<?php echo $is_bridging?>">
                    <input name="jnsPelayanan" type="radio" class="ace" value="2" checked />
                    

                    <p style="padding-top: 5px"><b>UPDATE PERJANJIAN ATAU RENCANA KONTROL PASIEN <a href="#" onclick="show_form_kontrol('hide')"><i class="fa fa-external-link bigger-120"></i></a> </b></p>
                    <div class="form-group">
                        <label class="control-label col-md-2">Tgl Kontrol</label>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input name="tglRencanaKontrol" id="tglRencanaKontrol" value="<?php echo $tgl_rencana_kontrol; ?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text">
                                    <span class="input-group-addon">
                                        <i class="ace-icon fa fa-calendar"></i>
                                    </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">No SEP Lama</label>
                        <div class="col-md-5">
                        <div class="input-group">
                                <input type="text" name="noSEP" id="noSEP" class="form-control search-query" value="<?php echo $no_sep_lama; ?>">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-purple btn-sm" onclick="find_poli_kontrol()">
                                        <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                        Search
                                    </button>
                                </span>
                            </div>

                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Poli Tujuan </label>
                        <div class="col-md-4">
                            <input id="inputKeyPoli" class="form-control" name="tujuan" type="text" readonly value="<?php echo $poli?>" />
                            <input type="hidden" name="kodePoliHidden" value="<?php echo $kode_poli_bpjs?>" id="kodePoliHidden">
                        </div>
                        <label class="control-label col-md-2">Dokter DPJP</label>
                        <div class="col-md-4">
                            <input id="InputKeydokterDPJP" class="form-control" name="dokterDPJP" type="text" value="<?php echo $nama_dr?>" readonly />
                            <input type="hidden" name="KodedokterDPJP" value="<?php echo $kode_dokter_bpjs?>" id="KodedokterDPJP">
                        </div>
                    </div>
                    <div id="response_msg"></div>
                    <div class="form-group">
                        <div class="col-md-1 no-padding">
                            <button type="submit" class="btn btn-primary btn-sm" style="margin-left: 0px">
                                <span class="ace-icon fa fa-save icon-on-right bigger-110"></span>
                                Update Tanggal Rencana Kontrol
                            </button>
                        </div>
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

        <!-- <div class="table-header"> -->

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

            </button>

            <span style="color: white; font-weight: bold">JADWAL POLI SPESIALIS</span>

        <!-- </div> -->

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
                <div class="col-md-1 no-padding">
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
                    <th width="80px">Kode Poli</th>
                    <th>Nama Poli</th>
                    <th width="100px">Kapasitas</th>
                    <th width="100px">Jumlah Rencana Kontrol</th>
                    <th width="100px">Persentase</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>


        </div>

    </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->

</div>



