<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

$(document).ready(function() {

    //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#dynamic-table').attr('base-url'),
          "type": "POST"
      },
      "drawCallback": function (settings) { 
          // Here the response
          var response = settings.json;
          console.log(response.total);
          $('#txt_total_pickup').text(formatMoney(response.total_pickup));
          $('#txt_total_cost').text(formatMoney(response.total_cost));
          // pendapatan bersih
          var profit_sharing = response.total_cost * 0.5;
          $('#txt_pendapatan_bersih_kurir').text(formatMoney(profit_sharing));
          $('#txt_pendapatan_bersih_koperasi').text(formatMoney(profit_sharing));

          $('#periode_from_tgl').text(response.from_tgl);
          $('#periode_to_tgl').text(response.to_tgl);
      },
      "columnDefs": [
        { className: "hidden-480", "targets": [ 4 ] },
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

$('#btn_search_data').click(function (e) {
    var url_search = $('#form_search').attr('action');
    e.preventDefault();
    if( $('#kurir').val() != '' ){
      $.ajax({
        url: url_search,
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        success: function(data) {
          console.log(data.data);
          find_data_reload(data);
        }
      });  
    }else{
      alert('Silahkan pilih kurir !');
      return false;
    }
    
 });

function find_data_reload(result){
  preventDefault();
  oTable.ajax.url($('#dynamic-table').attr('base-url')+'?'+result.data).load();
}

function proses_bagi_hasil(result){
  preventDefault();
  var arr_IDS = $('#dynamic-table tbody input:checkbox:checked').map(function(){
  return this.value; }).get().join(",");

  var post_data = {
    pickup_id : arr_IDS, 
    from_tgl: $('#from_tgl').val(), 
    to_tgl: $('#to_tgl').val(), 
    kurir: $('#kurir').val(),
    total_delivery: parseInt($('#txt_total_pickup').text()),
    total_cost: parseInt(formatNumberFromCurrency($('#txt_total_cost').text())),
  };

  $.ajax({
    url: 'farmasi/Ao_setoran/process',
    type: "post",
    data: post_data,
    dataType: "json",
    beforeSend: function() {
        achtungShowLoader();  
    },
    complete: function(xhr) {     
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);

      if(jsonResponse.status === 200){
        $.achtung({message: jsonResponse.message, timeout:5});
        $('#page-area-content').load('farmasi/Ao_receipt?_=' + (new Date()).getTime());
      }else{
        $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
      }
      achtungHideLoader();
    }

    // success: function(data) {
    //   console.log(data.data);
    //   find_data_reload(data);
    // }
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

    <form class="form-horizontal" method="post" id="form_search" action="farmasi/Ao_setoran/find_data" autocomplete="off">
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
              <label class="control-label col-md-1">Tanggal</label>
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


        </div>
      </div>

      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div class="row">
        <div class="col-md-12">

          <div class="col-md-6">
            <span><b>Periode</b>, <span id="periode_from_tgl"></span> s/d <span id="periode_to_tgl"></span></span>
            <table class="table">
              <tbody><tr>
                <td align="left">
                  Total Pengiriman<br>
                  <a href="#" id="txt_total_pickup" style="font-size: 18px; font-weight: bold">0</a>
                </td>
                <td align="right">
                  Total Biaya Kirim<br>
                  <a href="#" id="txt_total_cost" style="font-size: 18px; font-weight: bold">0</a>
                </td>
              </tr>
            </tbody></table>
          </div>
          <div class="col-md-6">
            <span><b>Skema Bagi Hasil (50%)</b></span>
            <table class="table">
              <tbody><tr>
                <td align="left">
                  Total Pendapatan Kurir<br>
                  <a href="#" id="txt_pendapatan_bersih_kurir" style="font-size: 18px; font-weight: bold">0</a>
                </td>
                <td align="right">
                  Total Pendapatan Koperasi<br>
                  <a href="#" id="txt_pendapatan_bersih_koperasi" style="font-size: 18px; font-weight: bold">0</a>
                </td>
              </tr>
            </tbody></table>
          </div>

          <table id="dynamic-table" base-url="farmasi/Ao_setoran/get_data" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th width="30px" class="center">
                  <div class="center">
                    <label class="pos-rel">
                        <input type="checkbox" class="ace" name="" onClick="checkAll(this);" value=""/>
                        <span class="lbl"></span>
                    </label>
                  </div>
                </th>
                <th class="center" width="50px">No</th>
                <th width="50px">Kode</th>
                <th>Pasien</th>
                <th width="130px">Waktu Pickup</th>
                <th width="150px">Penerima</th>
                <th width="130px">Waktu Diterima</th>
                <th width="100px">Biaya Kirim</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <div class="pull-left no-padding">
            <a href="#" onclick="proses_bagi_hasil()" id="btnSave" name="submit" class="btn btn-sm btn-info">
              <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
              Proses Bagi Hasil
            </a>
          </div>

        </div>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->





