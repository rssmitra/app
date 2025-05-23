<script type="text/javascript">

    $(document).ready(function() {
    //initiate dataTables plugin
        oTable = $('#table-pesan-resep').DataTable({ 
            
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "ordering": false,
            "searching": false,
            "bPaginate": false,
            "bInfo": false,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "farmasi/Farmasi_pesan_resep/get_data_by_id?q="+<?php echo $value->no_kunjungan ?>,
                "type": "POST"
            },
            "columnDefs": [
                { 
                    "targets": [ 0 ], //last column
                    "orderable": false, //set not orderable
                },
                {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
                { "visible": true, "targets": [0] },
                { "visible": false, "targets": [4] },
                { "visible": false, "targets": [5] },
            ],

        });

        $('#table-pesan-resep tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = oTable.row( tr );
                var data = oTable.row( $(this).parents('tr') ).data();
                var kode_pesan_resep = data[ 4 ];
                var no_registrasi = data[ 5 ];
                        

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    /*data*/
                    
                    $.getJSON("farmasi/Farmasi_pesan_resep/getDetail/" + kode_pesan_resep + "/" + no_registrasi, '', function (data) {
                        response_data = data;
                        // Open this row
                        row.child( format_html( response_data ) ).show();
                        tr.addClass('shown');
                    });
                    
                }
        } );

        $('#table-pesan-resep tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                //achtungShowLoader();
                $(this).removeClass('selected');
                //achtungHideLoader();
            }
            else {
                //achtungShowLoader();
                oTable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                //achtungHideLoader();
            }
        } );

        // table riwayat
        oTableRiwayat = $('#table-riwayat-pesan-resep').DataTable({ 
            
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "ordering": false,
            "searching": false,
            "bPaginate": false,
            "bInfo": false,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "farmasi/Farmasi_pesan_resep/get_data_by_mr?no_mr="+$('#no_mr_pesan_resep').val()+"",
                "type": "POST"
            },
            "columnDefs": [
                { 
                    "targets": [ 0 ], //last column
                    "orderable": false, //set not orderable
                },
                {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
                { "visible": true, "targets": [0] },
                { "visible": false, "targets": [4] },
            ],

        });

        $('#table-riwayat-pesan-resep tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = oTableRiwayat.row( tr );
                var data = oTableRiwayat.row( $(this).parents('tr') ).data();
                var kode_pesan_resep = data[ 4 ];
                        

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    /*data*/
                    
                    $.getJSON("farmasi/Farmasi_pesan_resep/getDetail/" + kode_pesan_resep, '', function (data) {
                        response_data = data;
                        // Open this row
                        row.child( format_html( response_data ) ).show();
                        tr.addClass('shown');
                    });
                    
                }
        } );

        $('#table-riwayat-pesan-resep tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                //achtungShowLoader();
                $(this).removeClass('selected');
                //achtungHideLoader();
            }
            else {
                //achtungShowLoader();
                oTableRiwayat.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                //achtungHideLoader();
            }
        } );


        $('#kode_dokter_show').typeahead({
            source: function (query, result) {
                    $.ajax({
                        url: "templates/references/getAllDokter",
                        data: 'keyword=' + query + '&bag=' + $('#kode_bagian_tujuan').val(),         
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
                $('#kode_dokter_show').val(label_item);
                $('#kode_dokter').val(val_item);
                
            }
        });

        $('#inputDokterPesanResep').typeahead({
            source: function (query, result) {
                    $.ajax({
                        url: "templates/references/getAllDokter",
                        data: 'keyword=' + query + '&bag=' + $('#kode_bagian_tujuan').val(),         
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
                $('#inputDokterPesanResep').val(label_item);
                $('#kode_dokter_edit').val(val_item);
                
            }
        });
        
    });

    function format_html ( data ) {
    return data.html;
    }

    function preventDefault(e) {
    e = e || window.event;
    if (e.preventDefault)
        e.preventDefault();
    e.returnValue = false;  
    }

    function delete_pesan_resep(id) {
        var answer = confirm('Hapus Pesanan?');
        preventDefault();
        if (answer){
            console.log('yes'); 
            $.ajax({
                url: 'farmasi/Farmasi_pesan_resep/delete',
                type: "post",
                data: {ID:id},
                dataType: "json",
                beforeSend: function() {
                    achtungShowLoader();  
                },
                success: function(data) {
                    //var jsonResponse = JSON.parse(data);  
                    achtungHideLoader();
                    console.log(data) 
                    $('#table-pesan-resep').DataTable().ajax.reload(null, false);
                }
            });
        }else{
            console.log('cancel');      
        }
    }

    function showModalEdit(id) {
        preventDefault();
        $.ajax({
            url: 'farmasi/Farmasi_pesan_resep/get_pesan_resep_by_id',
            type: "post",
            data: {id:id},
            dataType: "json",
            beforeSend: function() {
                $('#notif_status').html('<span class="red"><b>[Session Update]</b></span>');        
            },
            success: function(data) {
                //var jsonResponse = JSON.parse(data);  
                achtungHideLoader();
                if(data.status === 200){     
                    var resep = data.data
                    console.log(resep.tgl_pesan);
                    $('#kode_pesan_resep').val(resep.kode_pesan_resep);
                    $('#tgl_pesan').val(resep.tgl_pesan);
                    $('#jumlah_r').val(resep.jumlah_r);
                    $('#status_tebus').val(resep.status_tebus);
                    $('#lokasi_tebus').val(resep.lokasi_tebus);
                    $('#kode_dokter').val(resep.kode_dokter);
                    $('#kode_dokter_show').val(resep.nama_pegawai);
                    $('#keterangan_pesan_resep').val(resep.keterangan);
                    $('#kode_bagian_asal').val(resep.kode_bagian_asal);
                    $("input[name=jenis_resep][value='"+resep.jenis_resep+"']").prop("checked",true);
                } else {
                    $.achtung({message: data.message, timeout:5});   
                }
            }
        });
         
    }

    function back_to_previous(){
        getMenuTabs('farmasi/Farmasi_pesan_resep/pesan_resep/'+$('#no_kunjungan').val()+'/'+$('#kode_klas').val()+'/'+$('#kode_profit').val()+'', 'tabs_form_pelayanan');
    }

    function form_eresep(kode_pesan_resep){
        getMenuTabs('farmasi/E_resep/form/'+$('#no_registrasi').val()+'/'+kode_pesan_resep+'?no_mr='+$('#no_mr_pesan_resep').val()+'&no_kunjungan='+$('#no_kunjungan').val()+'', 'form_pesan_resep');
    }


</script>

<p><b><a href="#" onclick="back_to_previous()"><i class="fa fa-angle-double-left bigger-120"></i> FORM PESAN RESEP</a> </b></p>

<input type="hidden" value="<?php echo $value->no_registrasi?>" name="no_registrasi" id="no_registrasi">
<input type="hidden" value="<?php echo $value->no_kunjungan?>" name="no_kunjungan" id="no_kunjungan">
<input type="hidden" value="<?php echo $value->no_mr?>" name="no_mr_pesan_resep" id="no_mr_pesan_resep">
<input type="hidden" value="<?php echo $value->kode_perusahaan?>" name="kode_perusahaan" id="kode_perusahaan">
<input type="hidden" value="<?php echo $value->kode_kelompok?>" name="kode_kelompok" id="kode_kelompok">
<input type="hidden" value="<?php echo $value->kode_bagian_tujuan?>" name="kode_bagian_tujuan" id="kode_bagian_tujuan">
<input type="hidden" value="<?php echo $kode_bagian_asal?>" name="kode_bagian_asal" id="kode_bagian_asal">
<input type="hidden" value="<?php echo $kode_klas?>" name="kode_klas" id="kode_klas">
<input type="hidden" value="<?php echo $kode_profit?>" name="kode_profit" id="kode_profit">
<input type="hidden" value="" name="kode_pesan_resep" id="kode_pesan_resep">

<span id="notif_status"></span>
<div class="form-group">
    <label class="control-label col-md-3">Tanggal Resep</label>
    <div class="col-md-3">
        <input class=" datetime form-control" name="tgl_pesan" id="tgl_pesan" type="text" value="<?php echo date("Y-m-d h:i:s") ?>" readonly/>
    </div>
</div>

<div id="form_pesan_resep">
    <div class="form-group">
        <label class="control-label col-md-3">Nama Dokter</label>
        <div class="col-md-6">
            <input id="kode_dokter_show" class="form-control" name="kode_dokter_show" type="text" value="<?php echo $value->nama_pegawai ?>" />
            <input id="kode_dokter" class="form-control" name="kode_dokter" type="hidden" value="<?php echo $value->kode_dokter ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3">Jumlah R/</label>
        <div class="col-md-2">
            <input class="form-control" name="jumlah_r" id="jumlah_r" type="text" value="1"/>
        </div>
        <label class="control-label col-md-2">Lok Tebus</label>
        <div class="col-md-3">
            <select name="lokasi_tebus" id="lokasi_tebus" class="form-control">
                <option value="1">Dalam RS</option>
                <option value="2">Luar RS</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Jenis Resep</label>
        <div class="col-md-6">
            <div class="radio">
                <label>
                    <input name="jenis_resep" id="jenis_resep" value="prb" type="radio" class="ace">
                    <span class="lbl"> PRB</span>
                </label>
                <label>
                    <input name="jenis_resep" id="jenis_resep" value="non_prb" type="radio" class="ace">
                    <span class="lbl"> Non PRB</span>
                </label>
            </div>
            
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3">Keterangan</label>
        <div class="col-md-8">
            <input type="text" name="keterangan_pesan_resep" id="keterangan_pesan_resep" class="form-control" value="Mohon diproses sesuai resep">
        </div>
        <div class="col-sm-1 no-padding">
            <button type="submit" href="#" class="btn btn-xs btn-primary"><i class="fa fa-save"></i> Submit</button>
        </div>
    </div>

    <div style="margin-top:0px">
        <table id="table-pesan-resep" base-url="farmasi/Farmasi_pesan_resep" class="table table-bordered table-hover">
        <thead>
            <tr>  
                <th width="40px"></th>
                <th width="40px"></th>
                <th width="150px">Tgl Resep</th>
                <th>Asal Unit/Dokter</th>
                <th></th>
                <th></th>
                <th width="100px">Keterangan</th>
                <!-- <th>Lokasi Tebus</th> -->
                <!-- <th>Jumlah R</th> -->
                <th width="80px">Status</th>          
                <th>e-Resep</th>          
            </tr>
        </thead>
        <tbody>
        </tbody>
        </table>
        <div style="margin-top: -20px">
        <b>Pemberitahuan</b> : <br><i>Resep yang sudah diproses oleh farmasi tidak dapat diubah lagi, silahkan hubungi farmasi untuk mengubah resep yang sudah diproses.</i>
        </div>
    </div>

    <hr>
    <div style="margin-top:0px">
        <span style="font-size: 20px !important; font-weight: bold">10 Riwayat Resep Sebelumnya</span>
        <table id="table-riwayat-pesan-resep" base-url="farmasi/Farmasi_pesan_resep" class="table table-bordered table-hover">
        <thead>
            <tr>  
                <th width="40px"></th>
                <th width="40px"></th>
                <th width="150px">Tgl Resep</th>
                <th>Asal Unit/Dokter</th>
                <th></th>
                <th width="100px">Keterangan</th>
                <!-- <th>Lokasi Tebus</th> -->
                <!-- <th>Jumlah R</th> -->
                <th width="80px">Status</th>          
                <th>e-Resep</th>          
            </tr>
        </thead>
        <tbody>
        </tbody>
        </table>
    </div>

</div>






