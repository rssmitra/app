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
      "searching": true,
      "bPaginate": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#dynamic-table').attr('base-url'),
          "type": "POST"
      },
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
  var testval = $('input:checkbox:checked.checkbox').map(function(){
  return this.value; }).get().join(",");

  $.ajax({
    url: 'farmasi/Ao_pickup_obat/process',
    type: "post",
    data: { kode : $('#kode_trans_far').val(), jenis: testval },
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
        oTable.ajax.reload(); 

      }else{
        $.achtung({message: jsonResponse.message, timeout:5});
      }
      achtungHideLoader();
    }

  });
  
}

function delete_data(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/Ao_pickup_obat/delete',
        type: "post",
        data: {ID:myid},
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
            oTable.ajax.reload(); 
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

    <form class="form-horizontal" method="post" id="form_search" action="farmasi/Ao_pickup_obat/find_data" autocomplete="off">
      <input type="hidden" name="kode_profit" id="kode_profit" value="2000">
      <div class="row">
        <div class="col-md-12">

          <div class="form-group">
              <label class="control-label col-md-2">Jenis Resep</label>
                <div class="col-md-8">
                  <div class="radio">
                        <label>
                          <input name="jenis_resep[]" type="checkbox" class="checkbox ace" value="7" checked />
                          <span class="lbl"> Resep 7 / Umum</span>
                        </label>
                        <label>
                          <input name="jenis_resep[]" type="checkbox" class="checkbox ace" value="23"  />
                          <span class="lbl"> Resep 23 / Kronis</span>
                        </label>
                  </div>
                </div>  
          </div>

          <div class="form-group">
              <label class="control-label col-md-2">Kode Transaksi</label>
              <div class="col-md-2">
                <input type="text" class="form-control" name="kode_trans_far" id="kode_trans_far">
              </div>
              <label class="control-label col-md-1">Tanggal</label>
              <div class="col-md-2">
                <div class="input-group">
                  <input class="form-control date-picker" name="pickup_time" id="pickup_time" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" readonly/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
              <div class="col-md-1">
                <a href="#" id="btn_add_data" onclick="add_pickup()" class="btn btn-xs btn-primary">
                  <i class="ace-icon fa fa-shopping-cart icon-on-right bigger-110"></i>
                  Pickup
                </a>
              </div>
          </div>

          


        </div>
      </div>

      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div class="row">
        <div class="col-md-12 no-padding">
          <table id="dynamic-table" base-url="farmasi/Ao_pickup_obat/get_data" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th class="center"></th>
                <th class="center">No</th>
                <th>Kode</th>
                <th>Pasien</th>
                <th>Waktu Pickup</th>
                <th>Kurir</th>
                <th>Jenis Resep</th>
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





