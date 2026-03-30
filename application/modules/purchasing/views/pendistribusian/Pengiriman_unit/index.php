<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<style>
  .page-header-idx { border-bottom: 3px solid #2c6fad; padding-bottom: 8px; margin-bottom: 14px; }
  .page-header-idx h1 { font-size: 20px; color: #1a4f8a; font-weight: 700; margin: 0; }
  .srch-card { border: 1px solid #d0dce8; border-radius: 5px; background: #fff; box-shadow: 0 1px 4px rgba(44,111,173,.07); margin-bottom: 14px; overflow: hidden; }
  .srch-card-hdr { background: #2c6fad; color: #fff; padding: 9px 16px; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; }
  .srch-card-body { padding: 14px 20px 8px; }
  .srch-actions { display: flex; gap: 6px; padding: 8px 20px; background: #f0f5fb; border-top: 1px solid #d8e6f3; flex-wrap: wrap; align-items: center; }
  .tbl-wrap { border: 1px solid #d0dce8; border-radius: 5px; overflow: hidden; margin-bottom: 14px; }
  .tbl-hdr { background: #1a4f8a; color: #fff; padding: 9px 14px; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  #dynamic-table thead tr { background: #2c6fad !important; }
  #dynamic-table thead th { color: #fff !important; font-size: 12px; font-weight: 600; border-color: #1e5590 !important; }
</style>

<script type="text/javascript">
  jQuery(function($) {
    $('.date-picker').datepicker({
      autoclose: true,
      todayHighlight: true
    })
    .next().on(ace.click_event, function(){
      $(this).prev().focus();
    });
  });

  function checkAll(elm) {
    if($(elm).prop("checked") == true){
      $('.ace').each(function(){
          $(this).prop("checked", true);
      });
    }else{
      $('.ace').prop("checked", false);
    }
  }

  function get_detail_brg_po(myid){
    if(confirm('Are you sure?')){
      $.ajax({
          url: 'purchasing/pendistribusian/Distribusi_permintaan/get_detail_brg_po',
          type: "post",
          data: {ID:myid},
          dataType: "json",
          beforeSend: function() { achtungShowLoader(); },
          uploadProgress: function(event, position, total, percentComplete) {},
          complete: function(xhr) {
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);
            getMenuTabs('purchasing/pendistribusian/Distribusi_permintaan/create_po/'+$('#flag_string').val()+'?'+jsonResponse.params+'', 'tabs_form_po');
            achtungHideLoader();
          }
      });
    }else{
      return false;
    }
  }

  $('select[name="search_by"]').change(function () {
    if( $(this).val() == 'month'){
      $('#div_month').show();
      $('#div_keyword').hide();
    }
    if( $(this).val() == 'kode_permintaan'){
      $('#div_month').hide();
      $('#div_keyword').show();
    }
  });
</script>

<div class="page-header-idx">
  <h1>
    <?php echo isset($title)?$title:'Pengiriman Unit'?>
    <small style="font-size:13px;color:#888;font-weight:400">
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
    </small>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_search" action="purchasing/pendistribusian/Distribusi_permintaan/find_data?flag=<?php echo $flag?>">

      <input type="hidden" name="flag_string" id="flag_string" value="<?php echo $flag?>">

      <div class="srch-card">
        <div class="srch-card-hdr"><i class="fa fa-search"></i> Filter &amp; Pencarian</div>
        <div class="srch-card-body">

          <div class="form-group" style="margin-bottom:8px">
            <label class="control-label col-md-2" style="font-size:12px">Pilih Bagian/Unit</label>
            <div class="col-md-5">
              <?php echo $this->master->custom_selection(array('table'=>'mt_bagian','id'=>'kode_bagian','name'=>'nama_bagian','where'=>array()), '060101', 'kode_bagian', 'kode_bagian', 'form-control input-sm', '', '') ?>
            </div>
          </div>

          <div class="form-group" style="margin-bottom:8px">
            <label class="control-label col-md-2" style="font-size:12px">Tanggal Permintaan</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control input-sm date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" placeholder="Dari..."/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
            <label class="control-label col-md-1" style="font-size:12px">s/d</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control input-sm date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" placeholder="Sampai..."/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
          </div>

        </div>
        <div class="srch-actions">
          <a href="#" id="btn_search_data" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Cari</a>
          <a href="#" id="btn_reset_data" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Reset</a>
          <span style="margin-left:auto">
            <?php echo $this->authuser->show_button('purchasing/pendistribusian/Distribusi_permintaan','C','',7)?>
            <?php echo $this->authuser->show_button('purchasing/pendistribusian/Distribusi_permintaan','D','',5)?>
            <a href="" class="btn btn-sm btn-inverse" id="button_print_multiple"><i class="fa fa-print"></i> Print Selected</a>
          </span>
        </div>
      </div>

      <div class="tbl-wrap">
        <div class="tbl-hdr">
          <i class="fa fa-table"></i> Data Pengiriman Unit
          <span style="margin-left:auto;font-weight:400;font-size:11px;opacity:.85">Gudang <?php echo($flag=='non_medis')?'Umum':'Medis'?></span>
        </div>
        <table id="dynamic-table" base-url="purchasing/pendistribusian/Distribusi_permintaan" data-id="flag=<?php echo $flag?>" url-detail="purchasing/pendistribusian/Distribusi_permintaan/get_detail/<?php echo $flag?>" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="30px" class="center">
                <div class="center">
                  <label class="pos-rel">
                    <input type="checkbox" class="ace" name="" onClick="checkAll(this);" value="0"/>
                    <span class="lbl"></span>
                  </label>
                </div>
              </th>
              <th width="40px" class="center"></th>
              <th width="40px"></th>
              <th width="40px"></th>
              <th width="50px">ID</th>
              <th>Nomor Permintaan</th>
              <th>Tanggal</th>
              <th>Bagian/Unit</th>
              <th>User Input</th>
              <th>Jenis Permintaan</th>
              <th width="100px">Status</th>
              <th width="150px">Keterangan</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>
  </div>
</div>

<script src="<?php echo base_url().'assets/js/custom/als_datatable_with_detail_custom_url.js'?>"></script>
