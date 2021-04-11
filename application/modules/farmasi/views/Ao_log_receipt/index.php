<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

$(document).ready(function() {
    // get summary
    get_summary();
    //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#dynamic-table').attr('base-url'),
          "type": "POST"
      },
      // "drawCallback": function (settings) { 
      //     // Here the response
      //     var response = settings.json;
      //     console.log(response.total);
      //     $('#txt_total_pickup').text(response.pickup);
      //     $('#txt_total_received').text(response.received);
      //     $('#txt_total_cost').text(formatMoney(response.cost));
      // },
      "columnDefs": [
        { className: "hidden-480", "targets": [ 3 ] },
        { className: "hidden-480", "targets": [ 4 ] },
        // { className: "hidden-480", "targets": [ 5 ] },
        { className: "hidden-480", "targets": [ 6 ] },
      ]

    });


});


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

$( ".form-control" )
  .keypress(function(event) {
    var keycode =(event.keyCode?event.keyCode:event.which); 
    if(keycode ==13){
      event.preventDefault();
      $('#btn_search_data').click();
      return false;       
    }
});

function popUnder(node) {
    var newWindow = window.open("about:blank", node.target, "width=700,height=500"); 
    window.focus();
    newWindow.location.href = node.href;
    return false;
}


function get_summary(){
  $.getJSON("<?php echo site_url('farmasi/Ao_log_receipt/get_summary') ?>" , function (response) {              
      $('#txt_total_pickup').text(response.pickup);
      $('#txt_total_received').text(response.received);
      $('#txt_total_cost').text(formatMoney(response.cost));

      $('#txt_total_bagi_hasil').text(formatMoney(response.bagi_hasil));
      $('#txt_total_diambil').text(formatMoney(response.diambil));
      $('#txt_total_sisa').text(formatMoney(response.blm_diambil));
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

    <form class="form-horizontal" method="post" id="form_search" action="farmasi/Ao_log_receipt/find_data" autocomplete="off">
      
      <!-- div.dataTables_borderWrap -->
      <div class="row">
        <div class="col-md-12">

          <div class="form-group">
            <label class="control-label col-md-1">Kurir</label>
            <div class="col-md-2">
              <select name="kurir" id="kurir" class="form-control">
                <option value="">-Pilih Kurir-</option>
                <?php 
                  foreach ($kurir as $key => $row) {
                    echo '<option value="'.$row->kurir.'">'.$row->kurir.'</option>';
                  }
                ?>
              </select>
            </div>
            <label class="control-label col-md-1">Periode</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
            <label class="control-label col-md-1" style="margin-left: 0.8%">s/d Tgl</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>">
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
            <div class="col-md-3">
              <a href="#" id="btn_search_data"  class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                Tampilkan Data
              </a>
            </div>
          </div>

          <hr>
          
          <div class="col-md-6">
            <span><b>DATA PENGIRIMAN</b></span>
            <table class="table">
              <tbody>
                <tr>
                  <td align="left" width="30%">
                    Total Pickup<br>
                    <a href="#" id="txt_total_pickup" style="font-size: 18px; font-weight: bold">0</a>
                  </td>
                  <td align="left" width="30%">
                    Total Pengiriman<br>
                    <a href="#" id="txt_total_received" style="font-size: 18px; font-weight: bold">0</a>
                  </td>
                  <td align="left" width="40%">
                    Total Biaya Kirim<br>
                    <a href="#" id="txt_total_cost" style="font-size: 18px; font-weight: bold">0</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="col-md-6">
            <span><b>PENDAPATAN BERSIH</b></span>
            <table class="table">
              <tbody>
                <tr>
                  <td align="right" width="33%">
                    Total Bagi Hasil<br>
                    <a href="#" id="txt_total_bagi_hasil" style="font-size: 18px; font-weight: bold">0</a>
                  </td>
                  <td align="right" width="34%">
                    Sudah Diambil<br>
                    <a href="#" id="txt_total_diambil" style="font-size: 18px; font-weight: bold">0</a>
                  </td>
                  <td align="right" width="33%">
                    Sisa Belum Diambil<br>
                    <a href="#" id="txt_total_sisa" style="font-size: 18px; font-weight: bold">0</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <table id="dynamic-table" base-url="farmasi/Ao_log_receipt/get_data" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <!-- <th class="center"></th> -->
                <th class="center">No</th>
                <th>Kode</th>
                <th>Pasien</th>
                <th>Waktu Pickup</th>
                <th>Penerima</th>
                <th>Waktu Diterima</th>
                <th>Kurir</th>
                <th class="center">Biaya Kirim<br>(Rp)</th>
                <th class="center">Bagi Hasil<br>(%)</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->





