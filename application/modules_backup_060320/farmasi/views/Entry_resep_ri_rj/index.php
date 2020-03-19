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


    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan/find_data" autocomplete="off">
      <input type="hidden" name="kode_profit" id="kode_profit" value="2000">
      <div class="row">
        <div class="col-md-12">

          <div class="form-group">
              <label class="control-label col-md-2">Pencarian berdasarkan</label>
              <div class="col-md-2">
                <select name="search_by" class="form-control">
                  <option value="no_mr">No MR</option>
                  <option value="nama_pasien">Nama Pasien</option>
                </select>
              </div>

              <label class="control-label col-md-1">Keyword</label>
              <div class="col-md-2">
                <input type="text" class="form-control" name="keyword" id="keyword">
              </div>

              <div class="col-md-5 pull-right">
                <a href="#" class="btn btn-xs btn-primary pull-right" onclick="getMenu('farmasi/Entry_resep_ri_rj/form_create?tipe_layanan=<?php echo $flag?>')"><i class="fa fa-plus"></i> Buat Resep Obat Farmasi</a>
              </div>

          </div>

          <div class="form-group">
            <label class="control-label col-md-2">Tanggal</label>
              <div class="col-md-2">
                <div class="input-group">
                  <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>

              <label class="control-label col-md-1">s/d</label>
              <div class="col-md-2" style="margin-lef:-10px">
                <div class="input-group">
                  <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2">Poli/Klinik</label>
            <div class="col-md-3">
                <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1,'status_aktif' => 1), 'where_in' => array('col' => 'validasi', 'val' => array('0100','0300','0500')) ), '' , 'bagian', 'bagian', 'form-control', '', '') ?>
            </div>
            <label class="control-label col-md-1">Dokter</label>
              <div class="col-md-3">
                  <input id="inputDokter" class="form-control" name="dokter" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" />
                  <input type="hidden" name="dokterHidden" value="" id="dokterHidden">
              </div>
          </div>
          
          <div class="form-group">
            <label class="col-md-2 ">&nbsp;</label>
            <div class="col-md-10" style="margin-left: 5px">
              <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
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
      </div>

      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="farmasi/entry_resep_ri_rj/get_data?flag=<?php echo $flag?>" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th class="center"></th>
              <th>Kode</th>
              <th>Tgl Pesan</th>
              <th>No Mr</th>
              <th>Nama Pasien</th>
              <th>Nama Dokter</th>
              <th>Asal Pasien</th>
              <th width="90px">Jumlah (R)</th>
              <th>Lokasi Tebus</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable_custom_url.js'?>"></script>




