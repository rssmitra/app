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

function show_form_kontrol(flagType){
    preventDefault();
    if(flagType == 'show'){
        $('#div-form-kontrol').show();
    }else{
        $('#div-form-kontrol').hide();
    }
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
                    <td id="TglRencanaKontrol"><?php echo $tgl_kunjungan?></td>
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
                    <input type="hidden" class="form-control" id="noSuratKontrol" name="noSuratKontrol" value="<?php echo $kode?>">
                    <input name="jnsPelayanan" type="radio" class="ace" value="2" checked />
                    <input type="hidden" name="kodePoliHidden" value="<?php echo $kode_poli_bpjs?>" id="kodePoliHidden">

                    <p style="padding-top: 5px"><b>UPDATE PERJANJIAN ATAU RENCANA KONTROL PASIEN <a href="#" onclick="show_form_kontrol('hide')"><i class="fa fa-external-link bigger-120"></i></a> </b></p>
                    <div class="form-group">
                        <label class="control-label col-md-3">Tanggal Rencana Kontrol</label>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input name="tglRencanaKontrol" id="tglRencanaKontrol" value="<?php echo $tgl_rencana_kontrol; ?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text">
                                    <span class="input-group-addon">
                                        <i class="ace-icon fa fa-calendar"></i>
                                    </span>
                            </div>
                        </div>
                        <label class="control-label col-md-3">Nomor SEP Lama</label>
                        <div class="col-md-3">
                            <input type="text" name="noSEP" id="noSEP" class="form-control" value="<?php echo $no_sep_lama; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label">Poli Tujuan </label>
                        <div class="col-md-5">
                            <input id="inputKeyPoli" class="form-control" name="tujuan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo $poli?>" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Dokter DPJP</label>
                        <div class="col-md-5">
                            <input id="InputKeydokterDPJP" class="form-control" name="dokterDPJP" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                            <input type="hidden" name="KodedokterDPJP" value="" id="KodedokterDPJP">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-1 no-padding">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <span class="ace-icon fa fa-save icon-on-right bigger-110"></span>
                                Update Tanggal Rencana Kontrol
                            </button>
                        </div>
                    </div>
                </form>

                <hr>

            </div>
        </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


