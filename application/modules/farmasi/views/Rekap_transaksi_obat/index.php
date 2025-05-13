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

$('select[name="poliklinik"]').change(function () {      
  $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val(), '', function (data) {              
      $('#select_dokter option').remove();                
      $('<option value="">-Pilih Dokter-</option>').appendTo($('#select_dokter'));                         
      $.each(data, function (i, o) {                  
          $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#select_dokter'));                    
      });      
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

      <center>
        <h4>Riwayat Transaksi Farmasi <br> <small>Data yang ditampilkan adalah data transaksi 30 hari terakhir.</small></h4>
      </center>

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
              <label class="control-label col-md-2">Pencarian berdasarkan</label>
              <div class="col-md-2">
                <select name="search_by" class="form-control">
                  <option value="kode_trans_far">Kode Transaksi</option>
                  <option value="no_mr">No MR</option>
                  <option value="nama_pasien">Nama Pasien</option>
                </select>
              </div>

              <label class="control-label col-md-1">Keyword</label>
              <div class="col-md-2">
                <input type="text" class="form-control" name="keyword" id="keyword">
              </div>

              <label class="control-label col-md-1">Tanggal</label>
              <div class="col-md-2">
                <div class="input-group">
                  <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>

              <label class="control-label col-md-1">s/d</label>
              <div class="col-md-2" style="margin-left:-10px">
                <div class="input-group">
                  <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>

          </div>

          <div class="form-group">
            <label class="control-label col-md-2">Poli/Klinik Asal</label>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), '' , 'poliklinik', 'poliklinik', 'form-control', '', '') ?>
            </div>
            <label class="control-label col-md-1">Dokter</label>
            <div class="col-md-3">
              <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'select_dokter', 'select_dokter', 'form-control', '', '') ?>
            </div>
            <div class="col-md-3" style="margin-left: -1.3%">
              <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                Search
              </a>
              <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reset
              </a>
              <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
                <i class="ace-icon fa fa-file icon-on-right bigger-110"></i>
                Excel
              </a>
            </div>
          </div>
          
        </div>
      </div>
      

      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="farmasi/Rekap_transaksi_obat" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th class="center">No</th>
              <th>Tgl Transaksi</th>
              <th>No Mr</th>
              <th>Nama Pasien </th>
              <th>Nama Barang</th>
              <th>Jumlah</th>
              <th>Dokter Pengirim</th>
              <th>Jenis Pelayanan</th>
              <th width="180px">Diagnosa Akhir</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>




