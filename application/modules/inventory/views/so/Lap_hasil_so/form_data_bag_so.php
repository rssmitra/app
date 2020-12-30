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

  //initiate dataTables plugin
    oTable = $('#dt-bag-so').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "pageLength": 50,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#dt-bag-so').attr('base-url'),
          "type": "POST"
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

    <hr class="separator">
    <!-- div.table-responsive -->

    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dt-bag-so" base-url="inventory/so/Lap_hasil_so/get_data_bag_so?agenda_so_id=<?php echo $agenda_so_id?>&flag=<?php echo $flag?>" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th style="background-color: black; color: white" width="30px" class="center"></th>
          <th style="background-color: black; color: white" width="100px">Kode Bagian</th>
          <th style="background-color: black; color: white">Nama Bagian</th>
          <th style="background-color: black; color: white" style="width:30px" class="center">Barang Aktif</th>
          <th style="background-color: black; color: white" style="width:30px" class="center">Barang Tidak Aktif</th>
          <th style="background-color: black; color: white" style="width:30px" class="center">Barang Expired</th>
          <th style="background-color: black; color: white" style="width:30px" class="center">Total Barang<br>(Aktif + Tidak Aktif)</th>
          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



