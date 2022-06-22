<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script type="text/javascript">
        $('#txt_title').text('Laporan Penjualan Tanggal '+$('#from_tgl').val()+' s/d '+$('#to_tgl').val()+'');
$(document).ready(function(){
})

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

    <form class="form-horizontal" method="post" id="form_search" action="farmasi/Lap_penjualan_obat/find_data">

        <center>
            <h4><?php echo strtoupper($title)?> <br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data Penjualan Hari ini.</small></h4>
        </center>
      
        <br>

        <div class="form-group">
          <label class="control-label col-md-2">Tanggal Penjualan/Transaksi</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tanggal</label>
          <div class="col-md-2" style="margin-left:-10px">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <div class="col-md-6" style="margin-left: -1.3%">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Search
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              Reset
            </a>
            <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
              <i class="ace-icon fa fa-excel icon-on-right bigger-110"></i>
              Export Excel
            </a>
          </div>
        </div>
       

        <hr class="separator">
        <div style="margin-top:-27px">

          <table id="dynamic-table" base-url="farmasi/Lap_penjualan_obat" data-id="flag=" url-detail="farmasi/Lap_penjualan_obat/show_detail" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th width="50px"></th>
                <th width="100px">Kode Barang</th>
                <th>Nama Barang</th>
                <th width="100px">Satuan Kecil</th>
                <th width="100px">Harga Satuan</th>
                <th class="center" width="100px">Stok Akhir Gudang</th>
                <th class="center" width="100px">Stok Akhir Farmasi</th>
                <th width="100px" class="center">Jumlah <br>Terjual</th>
                <th width="100px" class="center">Total<br>Penjualan</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

        </div>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->


<script src="<?php echo base_url().'assets/js/custom/als_datatable_with_short.js'?>"></script>



