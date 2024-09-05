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

$(document).ready(function() {
    // get_total_aset_barang_rs();
    //initiate dataTables plugin
    oTable = $('#dt-bag-so').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "pageLength": 50,
      "paginate": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#dt-bag-so').attr('base-url'),
          "type": "POST"
      },
      "drawCallback": function (response) { 
        // Here the response
          var objData = response.json;
          $('#total_hasil_so_aktif').text( formatMoney(objData.total_rp_aktif) );
          $('#total_hasil_so_not_aktif').text( formatMoney(objData.total_rp_not_aktif) );
          $('#total_hasil_so_exp').text( formatMoney(objData.total_rp_exp) );
          $('#total_hasil_so_will_exp').text( formatMoney(objData.total_rp_will_exp) );
      },
      "columnDefs": [
        { 
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
        },
        { "aTargets" : [1], "sClass":  "hidden-480"}, 
        { "aTargets" : [3], "sClass":  "hidden-480"}, 
      ],

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

});

function reset_table(kode_bag){
    preventDefault();
    oTable.ajax.url('inventory/so/Input_dt_so/get_data?bag='+kode_bag+'').load();
}

function get_total_aset_barang_rs(){
  $.getJSON("inventory/so/Lap_hasil_so/total_aset_barang_rs?agenda_so_id=<?php echo $agenda_so_id?>&flag=<?php echo $flag?>", '', function (data) {
     
  });
}

function get_rincian_log(kode_brg){
  show_modal('inventory/so/Lap_hasil_so/log_barang?agenda_so_id=<?php echo $agenda_so_id?>&kode_brg='+kode_brg+'&flag=<?php echo $flag?>');
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

    <div class="row">

    <div class="pull-right" style="border-left: 1px solid #b2b3b5; padding-left: 10px; padding-right: 10px; background: #d7d7d766">
        <span style="font-size: 14px">Barang Aktif</span>
        <h3 style="font-weight: bold; margin-top : 0px">Rp. <span id="total_hasil_so_aktif"></span>,-</h3>
      </div>

      <div class="pull-right" style="border-left: 1px solid #b2b3b5; padding-left: 10px; padding-right: 10px; background: #d7d7d766">
        <span style="font-size: 14px">Barang Tidak Aktif </span>
        <h3 style="font-weight: bold; margin-top : 0px">Rp. <span id="total_hasil_so_not_aktif"></span>,-</h3>
      </div>

      <div class="pull-right" style="border-left: 1px solid #b2b3b5; padding-left: 10px; padding-right: 10px; background: #d7d7d766">
        <span style="font-size: 14px"> Expired (-3 Bln) </span>
        <h3 style="font-weight: bold; margin-top : 0px">Rp. <span id="total_hasil_so_will_exp"></span>,-</h3>
      </div>

      <div class="pull-right" style="border-left: 1px solid #b2b3b5; padding-left: 10px; padding-right: 10px; background: #d7d7d766">
        <span style="font-size: 14px">Barang Expired </span>
        <h3 style="font-weight: bold; margin-top : 0px">Rp. <span id="total_hasil_so_exp"></span>,-</h3>
      </div>
      
    </div>

    <hr class="separator">
    <!-- div.table-responsive -->

    <!-- div.dataTables_borderWrap -->
    <div class="row">
      <div style="margin-top:-27px">
        <table id="dt-bag-so" base-url="inventory/so/Lap_hasil_so/get_data_bag_so_rs?agenda_so_id=<?php echo $agenda_so_id?>&flag=<?php echo $flag?>" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th style="background-color: black; color: white" width="30px" class="center"></th>
            <th style="background-color: black; color: white" width="100px">Kode Barang</th>
            <th style="background-color: black; color: white">Nama Barang</th>
            <th style="background-color: black; color: white">Satuan Kecil</th>
            <th style="background-color: black; color: white" style="width:30px">Stok Sebelum</th>
            <th style="background-color: black; color: white" style="width:30px">Hasil SO</th>
            <th style="background-color: black; color: white" style="width:30px">Expired</th>
            <th style="background-color: black; color: white" style="width:30px">Expired (-3bln)</th>
            <th style="background-color: black; color: white" style="width:30px">Harga Satuan Pembelian</th>
            <th style="background-color: black; color: white" style="width:30px">Total</th>
            
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



