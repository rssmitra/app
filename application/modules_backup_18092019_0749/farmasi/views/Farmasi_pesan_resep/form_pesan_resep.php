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
    ],

    });

    $('#table-pesan-resep tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );
            var data = oTable.row( $(this).parents('tr') ).data();
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
                    row.child( format( response_data ) ).show();
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
            console.log(val_item);
            $('#kode_dokter').val(val_item);
            
        }
    });
    
});

function format ( data ) {
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
    $('#kode_pesan_resep').val(id)
    $.ajax({
        url: 'farmasi/Farmasi_pesan_resep/get_pesan_resep_by_id',
        type: "post",
        data: {id:id},
        dataType: "json",
        beforeSend: function() {
         
        },
        success: function(data) {
            //var jsonResponse = JSON.parse(data);  
            achtungHideLoader();
            if(data.status === 200){     
                var resep = data.data
                console.log(resep.tgl_pesan)
                $('#tgl_pesan_edit').val(resep.tgl_pesan)
                $('#jumlah_r').val(resep.jumlah_r)
                $('#jumlah_r_edit').val(resep.jumlah_r)
            } else {
                $.achtung({message: data.message, timeout:5});   
            }
        }
    });
    $("#modalEditPesan").modal();  
}

</script>

<p><b>PESAN RESEP <i class="fa fa-angle-double-right bigger-120"></i></b></p>

    <input type="hidden" value="<?php echo $value->no_registrasi?>" name="no_registrasi" id="no_registrasi">
    <input type="hidden" value="<?php echo $value->no_kunjungan?>" name="no_kunjungan" id="no_kunjungan">
    <input type="hidden" value="<?php echo $value->no_mr?>" name="no_mr" id="no_mr">
    <input type="hidden" value="<?php echo $value->kode_perusahaan?>" name="kode_perusahaan" id="kode_perusahaan">
    <input type="hidden" value="<?php echo $value->kode_kelompok?>" name="kode_kelompok" id="kode_kelompok">
    <input type="hidden" value="<?php echo $value->kode_bagian_tujuan?>" name="kode_bagian_tujuan" id="kode_bagian_tujuan">
    <input type="hidden" value="<?php echo $kode_klas?>" name="kode_klas" id="kode_klas">
    <input type="hidden" value="<?php echo $kode_profit?>" name="kode_profit" id="kode_profit">

    <div class="form-group">
        <label class="control-label col-md-2">Tanggal Pesan</label>
        <div class="col-md-3">
            <input class="form-control" name="tgl_pesan" id="tgl_pesan" type="text" value="<?php echo $this->tanggal->formatDateTime(date("Y-m-d h:i:s")) ?>"/>
        </div>

        <label class="control-label col-md-2">Jumlah R/</label>
        <div class="col-md-2">
            <input class="form-control" name="jumlah_r" id="jumlah_r" type="text" value=""/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-2">Nama Dokter</label>
        <div class="col-md-3">
            <input id="kode_dokter_show" class="form-control" name="kode_dokter_show" type="text" value="<?php echo $value->nama_pegawai ?>" />
            <input id="kode_dokter" class="form-control" name="kode_dokter" type="hidden" value="<?php echo $value->kode_dokter ?>" />
        </div>

        <label class="control-label col-md-2">Lokasi Tebus</label>
        <div class="col-md-3">
            <select name="lokasi_tebus" id="lokasi_tebus" class="form-control">
                <option value="1">Dalam RS</option>
                <option value="2">Luar RS</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2">
            <button type="submit" href="#" class="btn btn-xs btn-primary"><i class="fa fa-save"></i> Submit</button>
        </div>
    </div>

</div>

<div style="margin-top:0px">
    <table id="table-pesan-resep" base-url="farmasi/Farmasi_pesan_resep" class="table table-bordered table-hover">
       <thead>
        <tr>  
            <th width="40px"></th>
            <th width="80px"></th>
            <th>Tgl / Jam Pesan</th>
            <th>Bagian</th>
            <th>No Pesan</th>
            <th>Nama Dokter</th>
            <th>Lokasi Tebus</th>
            <th>Jumlah R</th>
            <th>Status</th>          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
</div>


<div id="modalEditPesan" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%; margin-top: 50px; margin-bottom:50px;width:55%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_create_sep">Edit Pesanan Resep</span>

        </div>

      </div>

      <div class="modal-body">

        <input type="hidden" name="kode_pesan_resep" id="kode_pesan_resep">
        <div class="form-group">
            <label class="control-label col-md-2">Tanggal Pesan</label>
            <div class="col-md-4">
                <input class="form-control" name="tgl_pesan_edit" id="tgl_pesan_edit" type="text" readonly />
            </div>

            <label class="control-label col-md-2">Jumlah R/</label>
            <div class="col-md-2">
                <input class="form-control" name="jumlah_r_edit" id="jumlah_r_edit" type="text" value=""/>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2">Nama Dokter</label>
            <div class="col-md-4">
                <input id="inputDokterPesanResep" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" name="inputDokterPesanResep" value="<?php echo $value->nama_pegawai ?>"/>

                <input type="hidden" name="kode_dokter_edit" id="kode_dokter_edit" class="form-control">
            </div>

            <label class="control-label col-md-2">Lokasi Tebus</label>
            <div class="col-md-3">
                <select name="lokasi_tebus" id="lokasi_tebus_edit" class="form-control">
                    <option value="1">Dalam RS</option>
                    <option value="2">Luar RS</option>
                </select>
            </div>
        </div>
       
        <br>
        <center>
            <button type="submit" href="#" class="btn btn-xs btn-primary"><i class="fa fa-save"></i> Submit</button>
            <!-- <a href="#" id="btn_submit_edit" class="btn btn-xs btn-primary"><i class="ace-icon fa fa-save bigger-50"></i>Submit</a> -->
        </center>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>

<!-- <script src="<?php //echo base_url().'assets/js/custom/als_datatable.js'?>"></script> -->




