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

function cetak_kartu_stok(){
  var kode_brg = $('#kode_brg').val();
  var kode_bagian = $('#kode_bagian').val();
  var from_tgl = $('#from_tgl').val();
  var to_tgl = $('#to_tgl').val();
  var flag = $('#flag').val();

  PopupCenter('inventory/stok/Inv_stok_gdg/cetak_kartu_stok?kode_brg='+kode_brg+'&kode_bagian='+kode_bagian+'&from_tgl='+from_tgl+'&to_tgl='+to_tgl+'&flag='+flag+'', 'CETAK KARTU STOK', 1000, 650);

}

</script>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">

  <p style="margin-left: 15px;margin-top: 8px; font-size: 20px;"><b><?php echo strtoupper($unit->nama_bagian)?></b></p>
  <div class="col-xs-2">
    <?php $link_image = ( $value->path_image != NULL ) ? PATH_IMG_MST_BRG.$value->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ; ?>
    <a href="<?php echo base_url().$link_image?>" target="_blank"><img src="<?php echo base_url().$link_image?>" alt="" style="max-width: 200px; width: 100%"></a>
  </div>
  <div class="col-xs-10">
    <form class="form-horizontal">
      <div class="form-group">
        <label class="col-md-2">Nama Barang</label>
        <div class="col-md-10">: 
          <b><?php echo isset($value)?$value->kode_brg.' - '.$value->nama_brg:''?></b>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-2">Harga Beli</label>
        <div class="col-md-4">: 
          <?php echo isset($value)?'Rp. '.number_format($value->harga_beli).',- /'.$value->satuan_kecil:''?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-2">Stok Akhir</label>
        <div class="col-md-2">: 
          <?php echo isset($value)?$value->stok_akhir.' '.$value->satuan_kecil:''?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-2">Stok Minimum</label>
        <div class="col-md-2">: 
          <?php echo isset($value)?$value->stok_minimum.' '.$value->satuan_kecil:''?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-2">Rasio</label>
        <div class="col-md-4">: 
          <?php echo isset($value)?$value->content.' '.$value->satuan_kecil.'/'.$value->satuan_besar:''?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-2">Mutasi Terakhir</label>
        <div class="col-md-3">: 
          <?php echo isset($value)?$this->tanggal->formatDateTime($value->tgl_input):''?>
        </div>
      </div>

    </form>
  </div>
</div>

<hr class="separator">

<div class="row">

  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">
        <div style="margin-left: -10px; margin-top: -10px; margin-bottom: 4px;">
          <a onclick="getMenu('inventory/stok/Inv_stok_gdg?flag=<?php echo $flag_string?>')" href="#" class="btn btn-xs btn-success">
              <i class="fa fa-arrow-left"></i> Kembali ke daftar
          </a>
          <a onclick="cetak_kartu_stok()" href="#" class="btn btn-xs btn-primary">
              <i class="fa fa-print"></i> Cetak Kartu Stok
          </a>
          <a href="#" onclick="PopupCenter('<?php echo base_url().'inventory/stok/Inv_stok_gdg/print_label?kode_brg='.$value->kode_brg.'&flag='.$flag_string.''?>', 'PRINT PREVIEW', 1000, 550);" class="btn btn-xs btn-primary" id="button_print_multiple">
            <i class="fa fa-print"></i> Print Label
          </a>
        </div>
        <form class="form-horizontal" method="post" id="form_search" action="inventory/stok/Inv_stok_gdg/find_data?flag=<?php echo $flag_string?>" autocomplete="off">
            <!-- hidden form -->
            <input type="hidden" name="kode_brg" id="kode_brg" value="<?php echo isset($value)?$value->kode_brg:''; ?>">
            <input type="hidden" name="kode_bagian" id="kode_bagian" value="<?php echo isset($unit)?$unit->kode_bagian:''; ?>">
            <input type="hidden" name="flag" id="flag" value="<?php echo $flag_string ; ?>">

            <div class="row">
              <div class="form-group">
                <label class="control-label col-md-2">Tanggal Mutasi</label>
                <div class="col-md-2">
                <div class="input-group">
                    <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>
                <label class="control-label col-md-2">s/d tanggal</label>
                <div class="col-md-2">
                <div class="input-group">
                    <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>
                <div class="col-md-3" style="margin-left: -2%">
                  <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
                    <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                    Search
                  </a>
                  <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
                    <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                    Reset
                  </a>
                </div>
              </div>
            </div>

            <div class="row">
              <table id="dynamic-table" base-url="inventory/stok/Inv_stok_gdg/get_data_mutasi?kode_brg=<?php echo $kode_brg?>&kode_bagian=<?php echo $kode_bagian?>&flag=<?php echo $flag_string; ?>" class="table table-striped table-bordered table-hover" style="width: 100%">
                <thead>
                  <tr style="background-color: #87b87f">
                    <th width="30px">No</th>
                    <th>Tanggal</th>
                    <th>Stok Awal</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Stok Akhir</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>

        </form>

        </div>
      </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->
<script src="<?php echo base_url().'assets/js/custom/als_datatable_custom_url.js'?>"></script>


