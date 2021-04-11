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
      "searching": true,
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
        { className: "hidden-480", "targets": [ 5 ] },
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


function add_pickup(){
  preventDefault();
  $.ajax({
    url: 'farmasi/Ao_receipt/process',
    type: "post",
    data: { kode : $('#kode_trans_far').val() },
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
        // show poup cetak resep
        reload_table();

      }else{
        $.achtung({message: jsonResponse.message, timeout:5});
      }
      achtungHideLoader();
    }

  });
  
}

function get_summary(){
  $.getJSON("<?php echo site_url('farmasi/Ao_receipt/get_summary') ?>" , function (response) {              
      $('#txt_total_pickup').text(response.pickup);
      $('#txt_total_received').text(response.received);
      $('#txt_total_cost').text(formatMoney(response.cost));
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

    <form class="form-horizontal" method="post" id="form_search" action="farmasi/Ao_receipt/find_data" autocomplete="off">
      
      <!-- div.dataTables_borderWrap -->
      <div class="row">
        <div class="col-md-12">
          <div class="col-md-6">

          <span><b>Delivery today</b>, <span id="periode_from_tgl"><?php echo date('d/m/Y')?></span> </span>
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
        

          <table id="dynamic-table" base-url="farmasi/Ao_receipt/get_data" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th class="center"></th>
                <th class="center">No</th>
                <th>Kode</th>
                <th>Pasien</th>
                <th>Penerima</th>
                <th>Waktu Diterima</th>
                <th>Biaya Kirim</th>
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





