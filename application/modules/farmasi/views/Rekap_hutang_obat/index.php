<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
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

$( ".form-control" )
  .keypress(function(event) {
    var keycode =(event.keyCode?event.keyCode:event.which); 
    if(keycode ==13){
      event.preventDefault();
      $('#btn_search_data').click();
      return false;       
    }
});

$( "#keyword" )
  .keypress(function(event) {
    var keycode =(event.keyCode?event.keyCode:event.which); 
    if(keycode ==13){
      event.preventDefault();
      submit_search_data();
      return false;       
    }
});

function popUnder(node) {
    var newWindow = window.open("about:blank", node.target, "width=700,height=500"); 
    window.focus();
    newWindow.location.href = node.href;
    return false;
}

</script>

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

  <div class="form-group">
    <label class="control-label col-md-1">Bulan </label>
    <div class="col-md-1">
      <?php echo $this->master->get_bulan('','from_month','from_month','form-control','','');?>
    </div>
    <label class="control-label col-md-1">Tahun</label>
    <div class="col-md-1">
      <?php echo $this->master->get_tahun('','year','year','form-control','','');?>
    </div>
    <div class="col-md-4">
      <button type="submit" name="submit" value="data" class="btn btn-xs btn-default">
        Tampilkan Data
      </button>
      <button type="submit" name="submit" value="excel" class="btn btn-xs btn-success">
        Export Excel
      </button>
    </div>
  </div>

  <hr class="separator">
  <!-- div.dataTables_borderWrap -->
  <div style="margin-top:-27px">
  <table id="dynamic-table" base-url="farmasi/Rekap_hutang_obat" class="table table-bordered table-hover">

      <thead>
        <tr>  
          <th width="30px" class="center">No</th>
          <th>Kode</th>
          <th>Nama Obat</th>
          <th>Jumlah Hutang</th>
          <th>Jumlah Mutasi</th>
          <th>Sisa Hutang</th>
          <th>Stok Farmasi</th>
          <th>Stok Gudang</th>
          <th>Jml Kekurangan</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

</form>

<script src="<?php echo base_url().'assets/js/custom/als_datatable_no_style.js'?>"></script>




