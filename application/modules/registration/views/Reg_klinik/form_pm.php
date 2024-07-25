<script type="text/javascript">
  
$(document).ready(function(){

  oTableOrderPm = $('#table_order_penunjang').DataTable({ 
            
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": true,
      "pageLength": 50,
      "bLengthChange": false,
      "bInfo": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_pm/get_data_order?search_by=no_mr&keyword="+$("#noMrHidden").val()+"&bagian_asal="+$('#bagian_asal').val()+"&no_reg="+$('#no_registrasi').val()+"",
          "type": "POST"
      },
      "columnDefs": [
        { 
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
        },
        {"aTargets" : [0], "mData" : 5, "sClass":  "details-control"}, 
        { "visible": false, "targets": [1,2,3,4,5] },
      ],

  });

  $('#table_order_penunjang tbody').on('click', 'td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = oTableOrderPm.row( tr );
      var data = oTableOrderPm.row( $(this).parents('tr') ).data();
      var no_registrasi = data[ 0 ];
      var no_kunjungan = data[ 1 ];
      var tipe = data[ 2 ];
      var id_pm_tc_penunjang = data[ 3 ];
      var kode_bagian_tujuan = data[ 4 ];
      

      if ( row.child.isShown() ) {
          // This row is already open - close it
          row.child.hide();
          tr.removeClass('shown');
      }
      else {
          /*data*/
          // pelayanan/Pl_pelayanan_pm/order_pemeriksaan_lab/?kode_bagian=050201&no_mr=00298489

          $.getJSON("pelayanan/Pl_pelayanan_pm/order_pemeriksaan_penunjang_detail?no_mr="+$('#noMrHidden').val()+"&kode_bagian="+kode_bagian_tujuan+"&id_pm_tc_penunjang="+id_pm_tc_penunjang+"", '', function (data) {
              response_data = data;
                // Open this row
              row.child( format( response_data ) ).show();
              tr.addClass('shown');
          });
          
      }
  } );

  $('#table_order_penunjang tbody').on( 'click', 'tr', function () {
      if ( $(this).hasClass('selected') ) {
          //achtungShowLoader();
          $(this).removeClass('selected');
          //achtungHideLoader();
      }
      else {
          //achtungShowLoader();
          oTableOrderPm.$('tr.selected').removeClass('selected');
          $(this).addClass('selected');
          //achtungHideLoader();
      }
  } );


})

function format ( data ) {
  return data.html;
}

function cetak_slip(kode_penunjang) {

  noMr = $('#noMrHidden').val();
  url = 'pelayanan/Pl_pelayanan_pm/slip?kode_penunjang='+kode_penunjang+'';
  title = 'Cetak Slip';
  width = 500;
  height = 600;
  PopupCenter(url, title, width, height); 

}

function delete_registrasi(no_reg, no_kunjungan){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'registration/Reg_pasien/delete_registrasi',
        type: "post",
        data: {ID:no_reg, KunjunganID:no_kunjungan},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            $('#table_order_penunjang').DataTable().ajax.reload(null, false);
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }
  
}

function back_previous() {

getMenuTabs('registration/Reg_pm/rujuk_pm/<?php echo $no_reg?>/<?php echo $bagian_asal?>/<?php echo $klas?>/<?php echo $type?>', 'tabs_form_pelayanan')

}
</script>
<p><b> <a href="#" onclick="back_previous()"><i class="fa fa-angle-double-left bigger-120"></i> PENDAFTARAN PENUNJANG MEDIS</a> </b></p>

<input type="hidden" id="no_registrasi_rujuk" name="no_registrasi_rujuk" value="<?php echo isset($no_reg)?$no_reg:''?>">
<input type="hidden" id="klas_rujuk" name="klas_rujuk" value="<?php echo isset($klas)?$klas:0?>">
<input type="hidden" id="bagian_asal" name="bagian_asal" value="<?php echo isset($bagian_asal)?$bagian_asal:0?>">

<div id="change_form_pengantar_pm">
  <div class="form-group">
    <label class="control-label col-sm-3">*Asal Pasien</label>
    <div class="col-sm-6">
        <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1, 'pelayanan' => 1)), isset($bagian_asal)?$bagian_asal:'' , 'asal_pasien_pm', 'asal_pasien_pm', 'form-control', '', '') ?>
    </div>
    <div class="col-md-3">
      <div class="checkbox">
        <label>
          <input name="is_pasien_luar" type="checkbox" class="ace" value="1">
          <span class="lbl"> Pasien Luar</span>
        </label>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-3">*Penunjang Medis</label>
    <div class="col-sm-3">
        <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1, 'validasi' => '0500')), '' , 'pm_tujuan', 'pm_tujuan', 'form-control', '', '') ?>
    </div>
    <label class="control-label col-sm-3">Jenis Layanan</label>
    <div class="col-md-3">
      <div class="radio">
          <label>
            <input name="jenis_layanan_pm" type="radio" class="ace" value="0"  checked />
            <span class="lbl"> Biasa</span>
          </label>

          <label>
            <input name="jenis_layanan_pm" type="radio" class="ace" value="1" />
            <span class="lbl">Cito</span>
          </label>
      </div>
    </div>
  </div>

  <div class="form-group">                
    <label class="control-label col-sm-3">Keterangan </label>  
    <div class="col-md-9">    
      <textarea class="form-control" name="keterangan_pm" id="keterangan_pm" style="height: 50px !important"></textarea>
    </div>
  </div>


  <div class="form-group" style="padding-top: 3px">
      <label class="col-sm-3">&nbsp; </label>   
      <div class="col-sm-2" style="padding-left: 18px">
          <button type="submit" href="#" class="btn btn-xs btn-primary"><i class="fa fa-save"></i> Submit</button>
      </div>
  </div>
  <br>
  <p><b> RIWAYAT PENDAFTARAN PENUNJANG MEDIS <i class="fa fa-angle-double-right bigger-120"></i></b></p>
  <br>
  <div style="margin-top:-27px">
    <table id="table_order_penunjang" base-url="pelayanan/Pl_pelayanan_pm" class="table table-bordered table-hover"> 
      <thead>
        <tr>  
          <th width="40px"></th>
          <th width="30px"></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th>Tgl Daftar</th>
          <th>No MR</th>
          <th>Nama Pasien</th>
          <th>Penjamin</th>
          <th>Tujuan Penunjang</th>
          <th>Asal Pasien</th>
          <th>Status</th>          
          <th>Pengantar PM</th>          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>


