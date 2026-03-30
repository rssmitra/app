<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/sweetalert2.all.min.js"></script>

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

  $( "#inputSupplier" ).keypress(function(event) {
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
          url: 'purchasing/penerimaan/Riwayat_penerimaan_brg/get_detail_brg_po',
          type: "post",
          data: {ID:myid},
          dataType: "json",
          beforeSend: function() { achtungShowLoader(); },
          uploadProgress: function(event, position, total, percentComplete) {},
          complete: function(xhr) {
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);
            getMenuTabs('purchasing/penerimaan/Riwayat_penerimaan_brg/create_po/'+$('#flag').val()+'?'+jsonResponse.params+'', 'tabs_form_po');
            achtungHideLoader();
          }
      });
    }else{
      return false;
    }
  }

  function rollback_penerimaan(id_penerimaan) {
    var flag = $('#flag').val();
    Swal.fire({
      title: 'Konfirmasi Rollback',
      html: '<p>Apakah anda yakin ingin me-rollback penerimaan ini?</p>' +
            '<p style="color:#c82333;font-size:12px"><i class="fa fa-exclamation-triangle"></i> ' +
            'Tindakan ini akan menghapus data penerimaan, mengembalikan stok, dan mereset status PO menjadi belum diterima.</p>',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#c82333',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Rollback',
      cancelButtonText: 'Batal',
    }).then(function(result) {
      if (result.isConfirmed) {
        achtungShowLoader();
        $.ajax({
          url: 'purchasing/penerimaan/Riwayat_penerimaan_brg/rollback/' + id_penerimaan + '?flag=' + flag,
          type: 'POST',
          dataType: 'json',
          complete: function(xhr) {
            achtungHideLoader();
            var res = JSON.parse(xhr.responseText);
            if (res.status === 200) {
              Swal.fire({
                title: 'Rollback Berhasil',
                text: res.message,
                icon: 'success',
                confirmButtonText: 'OK',
              }).then(function() {
                $('#dynamic-table').DataTable().ajax.reload();
              });
            } else {
              Swal.fire({ title: 'Gagal', text: res.message, icon: 'error', confirmButtonText: 'Tutup' });
            }
          }
        });
      }
    });
  }

  $('select[name="search_by"]').change(function () {
    if( $(this).val() == 'month'){
      $('#div_month').show();
      $('#div_keyword').hide();
      $('#div_supplier').hide();
    }
    if( $(this).val() == 'no_po'){
      $('#div_month').hide();
      $('#div_keyword').show();
      $('#div_supplier').hide();
    }
    if( $(this).val() == 'supplier'){
      $('#div_month').hide();
      $('#div_keyword').hide();
      $('#div_supplier').show();
    }
  });

  $('#inputSupplier').typeahead({
      source: function (query, result) {
              $.ajax({
                  url: "templates/references/getSupplier",
                  data: 'keyword=' + query,
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                  result($.map(response, function (item) {
                      return item;
                  }));
                  }
              });
          },
          afterSelect: function (item) {
          var val_item=item.split(':')[0];
          console.log(val_item);
          $('#kodesupplier').val(val_item);
      }
  });
</script>

<div class="page-header-idx">
  <h1>
    <?php echo $title?>
    <small style="font-size:13px;color:#888;font-weight:400">
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
    </small>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_search" action="purchasing/penerimaan/Riwayat_penerimaan_brg/find_data?flag=<?php echo $flag?>" autocomplete="off">

      <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">

      <div class="srch-card">
        <div class="srch-card-hdr"><i class="fa fa-search"></i> Filter &amp; Pencarian</div>
        <div class="srch-card-body">

          <div class="form-group" style="margin-bottom:8px">
            <label class="control-label col-md-2" style="font-size:12px">Pencarian berdasarkan</label>
            <div class="col-md-2">
              <select name="search_by" id="search_by" class="form-control input-sm">
                <option value="">-Silahkan Pilih-</option>
                <option value="no_po" selected>Nomor PO</option>
                <option value="supplier">Nama Supplier</option>
                <option value="month">Bulan</option>
                <option value="tgl_po">Tanggal PO</option>
              </select>
            </div>

            <div id="div_month" style="display:none">
              <label class="control-label col-md-1" style="font-size:12px">Bulan</label>
              <div class="col-md-2" style="margin-left:-15px">
                <?php echo $this->master->get_bulan('','month','month','form-control input-sm','','')?>
              </div>
            </div>

            <div id="div_supplier" style="display:none">
              <label class="control-label col-md-1" style="font-size:12px">Supplier</label>
              <div class="col-md-5" style="margin-left:-15px">
                <input id="inputSupplier" class="form-control input-sm" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                <input type="hidden" name="kodesupplier" id="kodesupplier" class="form-control" value="">
              </div>
            </div>

            <div id="div_keyword">
              <label class="control-label col-md-1" style="font-size:12px">Keyword</label>
              <div class="col-md-2" style="margin-left:-15px">
                <input type="text" class="form-control input-sm" name="keyword" id="keyword_form">
              </div>
            </div>

          </div>

          <div class="form-group" style="margin-bottom:8px">
            <label class="control-label col-md-2" style="font-size:12px">Tanggal Penerimaan</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control input-sm date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="" placeholder="Dari..."/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
            <label class="control-label col-md-1" style="font-size:12px">s/d</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control input-sm date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="" placeholder="Sampai..."/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
          </div>

        </div>
        <div class="srch-actions">
          <a href="#" id="btn_search_data" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Cari</a>
          <a href="#" id="btn_reset_data" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Reset</a>
          <a href="#" id="btn_export_excel" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</a>
        </div>
      </div>

      <div class="tbl-wrap">
        <div class="tbl-hdr">
          <i class="fa fa-history"></i> Riwayat Penerimaan Barang
          <span style="margin-left:auto;font-weight:400;font-size:11px;opacity:.85">Gudang <?php echo($flag=='non_medis')?'Umum':'Medis'?></span>
        </div>
        <table id="dynamic-table" base-url="purchasing/penerimaan/Riwayat_penerimaan_brg" data-id="flag=<?php echo $flag?>" url-detail="purchasing/penerimaan/Riwayat_penerimaan_brg/get_detail" class="table table-bordered table-hover">
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
              <th>PO</th>
              <th>Tgl Diterima</th>
              <th>Nama Supplier</th>
              <th>No Faktur</th>
              <th>Cetak BAST</th>
              <th>Distribusi</th>
              <th>Rollback</th>
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
