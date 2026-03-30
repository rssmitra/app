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
  $( "#keyword_form" ).keypress(function(event) {
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){
        event.preventDefault();
        if($(this).valid()){
          $('#btn_search_data').click();
        }
        return false;
      }
  });

  $("#btn_create_po").click(function(event){
        event.preventDefault();
        var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
          return $(this).val();
        }).toArray();
        get_detail_brg_po(''+searchIDs+'')
        console.log(searchIDs);
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
          url: 'purchasing/penerimaan_brg/Pb_riwayat/get_detail_brg_po',
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
            getMenuTabs('purchasing/penerimaan_brg/Pb_riwayat/create_po/'+$('#flag_string').val()+'?'+jsonResponse.params+'', 'tabs_form_po');
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
    Penerimaan Barang dari Supplier
    <small style="font-size:13px;color:#888;font-weight:400">
      <i class="ace-icon fa fa-angle-double-right"></i>
      Gudang <?php echo($flag=='non_medis')?'Umum':'Medis'?> &mdash; Tahun <?php echo date('Y')?>
    </small>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_search" action="permintaan/Req_riwayat_permintaan_pemb">

      <input type="hidden" name="flag_string" id="flag_string" value="<?php echo $flag?>">

      <div class="srch-card">
        <div class="srch-card-hdr"><i class="fa fa-search"></i> Filter &amp; Pencarian</div>
        <div class="srch-card-body">

          <div class="form-group" style="margin-bottom:8px">
            <label class="control-label col-md-2" style="font-size:12px">Cari berdasarkan</label>
            <div class="col-md-2">
              <select name="search_by" id="search_by" class="form-control input-sm">
                <option value="">- Pilih -</option>
                <option value="kode_permintaan" selected>Kode Permintaan</option>
                <option value="month">Bulan</option>
              </select>
            </div>

            <div id="div_month" style="display:none">
              <div class="col-md-2">
                <?php echo $this->master->get_bulan('','month','month','form-control input-sm','','')?>
              </div>
            </div>

            <div id="div_keyword">
              <label class="control-label col-md-1" style="font-size:12px">Keyword</label>
              <div class="col-md-2">
                <input type="text" class="form-control input-sm" name="keyword" id="keyword_form" placeholder="Keyword...">
              </div>
            </div>
          </div>

        </div>
        <div class="srch-actions">
          <a href="#" id="btn_search_data" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Cari</a>
          <a href="#" id="btn_reset_data" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Reset</a>
        </div>
      </div>

      <div class="tbl-wrap">
        <div class="tbl-hdr">
          <i class="fa fa-table"></i> Data Penerimaan Barang
          <span style="margin-left:auto;font-weight:400;font-size:11px;opacity:.85">Gudang <?php echo($flag=='non_medis')?'Umum':'Medis'?></span>
        </div>
        <table id="dynamic-table" base-url="purchasing/penerimaan_brg/Pb_riwayat" url-detail="purchasing/penerimaan_brg/Pb_riwayat/get_detail/<?php echo $flag?>" data-id="flag=<?php echo $flag?>" class="table table-bordered table-hover">
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
              <th width="50px">ID</th>
              <th>Kode Penerimaan</th>
              <th>Tanggal</th>
              <th>No. PO</th>
              <th>Nama Supplier</th>
              <th>No Faktur</th>
              <th>Petugas</th>
              <th>Pengirim</th>
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
