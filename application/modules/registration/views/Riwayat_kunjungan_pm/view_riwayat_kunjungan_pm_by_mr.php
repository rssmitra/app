<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
  
});

$(document).ready(function(){

  
  url = 'registration/Riwayat_kunjungan_pm/get_data_by_mr?search_by=no_mr&keyword='+$('#no_mr_pm').val()+'';
  oTableRiwayatKunjunganPm = $('#tbl_riwayat_kunjungan_pm_by_mr').DataTable({ 
          
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "bPaginate": false,
    "pageLength": 50,
    "bLengthChange": false,
    "bInfo": true,
    "ajax": {
        "url": url,
        "type": "POST"
    },
    "drawCallback": function (response) { 
        // Here the response
          var objData = response.json;
          $('#txt_no_mr').text(objData.no_mr);
          $('#txt_nama_pasien').text(objData.nama_pasien);
    },
    "columnDefs": [
      { 
        "targets": [ -1 ], //last column
        "orderable": false, //set not orderable
      },
      {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
      { "visible": false, "targets": [1,2,3,4] },
    ],

  });

  $('#tbl_riwayat_kunjungan_pm_by_mr tbody').on('click', 'td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = oTableRiwayatKunjunganPm.row( tr );
      var data = oTableRiwayatKunjunganPm.row( $(this).parents('tr') ).data();
      var no_registrasi = data[ 1 ];
      var no_kunjungan = data[ 2 ];
      var kode_penunjang = data[ 3 ];
      var kode_bagian_tujuan = data[ 4 ];
      

      if ( row.child.isShown() ) {
          // This row is already open - close it
          row.child.hide();
          tr.removeClass('shown');
      }
      else {
          /*data*/

          $.getJSON("registration/reg_pasien/form_modal_view_hasil_pm/"+no_registrasi+"/"+no_kunjungan+"/"+kode_penunjang+"/"+kode_bagian_tujuan+"?format=html", '', function (data) {
              response_data = data;
                // Open this row
              row.child( format( response_data ) ).show();
              tr.addClass('shown');
          });
          
      }
  } );

  $('#tbl_riwayat_kunjungan_pm_by_mr tbody').on( 'click', 'tr', function () {
      if ( $(this).hasClass('selected') ) {
          //achtungShowLoader();
          $(this).removeClass('selected');
          //achtungHideLoader();
      }
      else {
          //achtungShowLoader();
          oTableRiwayatKunjunganPm.$('tr.selected').removeClass('selected');
          $(this).addClass('selected');
          //achtungHideLoader();
      }
  } );

  function format ( data ) {
    return data.html;
  }

});

</script>
<div class="row">
  <div class="col-xs-12">
    <?php 
      if(count($pm) == 0){
        echo "Tidak ada penunjang";
        exit;
      }
    ?>
    <form class="form-horizontal" method="post" id="form_search" action="#">
      <input type="hidden" name="no_mr_pm" id="no_mr_pm" value="<?php echo $no_mr?>">
      <table style="width: 100%">
        <tr>
          <td width="10%">No. RM :<br><span style="font-size: 18px; font-weight: bold" id="txt_no_mr"></span></td>
          <td width="70%">Nama Pasien :<br><span style="font-size: 18px; font-weight: bold" id="txt_nama_pasien"></span></td>
        </tr>
      </table>
      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div>
        <span style="font-style: italic">* 10 Riwayat pemeriksaan penunjang medis terakhir</span>
        <table id="tbl_riwayat_kunjungan_pm_by_mr" base-url="registration/Riwayat_kunjungan_pm" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th width="50px"></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th>Tanggal Registrasi</th>
              <th>Tanggal Pemeriksaan</th>
              <th>Asal Unit</th>
              <th>Tujuan Penunjang</th>
              <th>Dokter Pengirim</th>
              <th>Detail Pemeriksaan</th>
              <th>Tanggal Isi Hasil</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<!-- <script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script> -->



