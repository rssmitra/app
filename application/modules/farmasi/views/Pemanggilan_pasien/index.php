<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>
  $(document).ready(function() {

    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bInfo": false,
      "pageLength": 5,
      "bPaginate": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#dynamic-table').attr('base-url'),
          "type": "POST"
      },

    });

    oTablePending = $('#dynamic-table-pending').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": true,
      "bInfo": false,
      "pageLength": 10,
      "bPaginate": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#dynamic-table-pending').attr('base-url'),
          "type": "POST"
      },

    });

  } ); 


 function exc_process(kode_trans_far, flag_code){
    preventDefault();
    $.ajax({
        url: 'farmasi/Pemanggilan_pasien/process',
        type: "post",
        data: {ID : kode_trans_far, code: flag_code},
        dataType: "json",
        beforeSend: function() {
          // achtungShowLoader();  
        },
        success: function(data) {
          // achtungHideLoader();
          oTable.ajax.reload();
          oTablePending.ajax.reload();
        }
    });
 }
</script>


<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <form class="form-horizontal" method="post" id="form_search" action="Templates/References/find_data" autocomplete="off">

      <left>
        <h4>Antrian Pemanggilan Pasien Farmasi<br> 
          <small>Data dibawah ini adalah pasien yang siap untuk diberikan resep obatnya.</small>
        </h4>
      </left>

      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="farmasi/Pemanggilan_pasien/get_data?flag=All" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th class="center">No</th>
              <th>Kode</th>
              <th>Tgl Transaksi</th>
              <th>Nama Pasien</th>
              <th>Pelayanan</th>
              <!-- <th>Status</th> -->
              <th width="200px"></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

      <left>
        <h4>Pasien Pending<br> 
          <small>Pasien yang sudah dipanggil akan tetapi tidak datang/belum diambil obatnya.</small>
        </h4>
      </left>

      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dynamic-table-pending" base-url="farmasi/Pemanggilan_pasien/get_data_pending?flag=All" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th class="center">No</th>
              <th>Kode</th>
              <th>Tgl Transaksi</th>
              <th>No Mr</th>
              <th>Nama Pasien</th>
              <th>Pelayanan</th>
              <th>Status</th>
              <th width="200px"></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->





