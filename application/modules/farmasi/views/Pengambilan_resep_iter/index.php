<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<style>
  /* ===== Card / Wrap ===== */
  .pu-wrap { border:1px solid #b8d0e8; border-radius:7px; overflow:hidden; margin-bottom:16px; box-shadow:0 2px 8px rgba(26,79,138,.09); background:#fff; }
  .pu-hdr  { background:linear-gradient(135deg,#1a4f8a 0%,#2c6fad 100%); color:#fff; padding:10px 16px; font-size:13px; font-weight:700; display:flex; align-items:center; gap:9px; }
  .pu-body { padding:14px 18px; }

  /* ===== Filter form ===== */
  .pf-grid {
    display: flex; flex-wrap: wrap; gap: 12px 16px; align-items: flex-end;
  }
  .pf-item { display: flex; flex-direction: column; gap: 4px; }
  .pf-item-grow { flex: 1; min-width: 160px; }
  .pf-label {
    font-size: 10px; font-weight: 700; color: #6b8cae;
    text-transform: uppercase; letter-spacing: .4px;
  }
  .pf-control {
    height: 30px; border: 1px solid #c0d4e8; border-radius: 4px;
    padding: 2px 8px; font-size: 12px; color: #1a3a5c;
    background: #fff; width: 100%;
    transition: border-color .15s, box-shadow .15s;
  }
  .pf-control:focus {
    outline: none; border-color: #2c6fad;
    box-shadow: 0 0 0 2px rgba(44,111,173,.15);
  }
  .pf-date-row { display: flex; align-items: center; gap: 6px; }
  .pf-sep { font-size: 11px; color: #6b8cae; font-weight: 600; white-space: nowrap; }
  .pf-date-wrap { align-items: center; }
  /* .pf-date-wrap .form-control {
    height: 30px; font-size: 12px; border-radius: 4px 0 0 4px;
    border: 1px solid #c0d4e8; width: 110px;
  } */
  /* .pf-date-wrap .input-group-addon {
    height: 30px; line-height: 1; padding: 0 8px;
    background: #f0f5fb; border: 1px solid #c0d4e8; border-left: none;
    border-radius: 0 4px 4px 0; cursor: pointer;
  } */
  .pf-divider { width: 100%; height: 1px; background: #e2eaf4; margin: 4px 0; }

  /* Filter buttons */
  .pf-btn {
    height: 30px; display: inline-flex; align-items: center; gap: 5px;
    border: none; border-radius: 4px; padding: 0 14px;
    font-size: 12px; font-weight: 600; cursor: pointer;
    text-decoration: none; transition: opacity .15s;
  }
  .pf-btn:hover { opacity: .85; text-decoration: none; }
  .pf-btn-search { background: linear-gradient(135deg,#1a4f8a 0%,#2c6fad 100%); color: #fff !important; }
  .pf-btn-reset  { background: #fef3c7; color: #92400e !important; border: 1px solid #fcd34d; }
</style>

<!-- ── Page Header ── -->
<div class="page-header" style="margin-bottom:14px">
  <h1 style="font-size:18px; color:#1a4f8a; font-weight:700; margin:0; display:flex; align-items:center; gap:8px;">
    <i class="fa fa-medkit" style="font-size:16px"></i>
    <?php echo $title?>
    <small style="font-size:12px; color:#6b8cae; font-weight:400">
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo isset($breadcrumbs) ? $breadcrumbs : ''?>
    </small>
  </h1>
</div>

<div class="row">
<div class="col-xs-12">

  <form class="form-horizontal" method="post" id="form_search" action="Templates/References/find_data" autocomplete="off">

    <!-- ===== Filter Card ===== -->
    <div class="pu-wrap">
      <div class="pu-hdr">
        <i class="fa fa-filter"></i> Filter Pencarian
      </div>
      <div class="pu-body">

        <div class="pf-grid">

          <!-- Search by -->
          <div class="pf-item">
            <label class="pf-label">Cari Berdasarkan</label>
            <select name="search_by" class="pf-control" style="min-width:150px">
              <option value="kode_trans_far">Kode Transaksi</option>
              <option value="no_mr">No. MR</option>
              <option value="nama_pasien">Nama Pasien</option>
            </select>
          </div>

          <!-- Keyword -->
          <div class="pf-item pf-item-grow">
            <label class="pf-label">Keyword</label>
            <input type="text" class="pf-control" name="keyword" id="keyword" placeholder="Masukkan kata kunci...">
          </div>

          <!-- Tanggal range -->
          <div class="pf-item">
            <label class="pf-label">Tanggal</label>
            <div class="pf-date-row">
              <div class="pf-date-wrap input-group">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl"
                       type="text" data-date-format="yyyy-mm-dd" placeholder="Dari..." value=""/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar" style="color:#2c6fad"></i>
                </span>
              </div>
              <span class="pf-sep">s/d</span>
              <div class="pf-date-wrap input-group">
                <input class="form-control date-picker" name="to_tgl" id="to_tgl"
                       type="text" data-date-format="yyyy-mm-dd" placeholder="Sampai..." value=""/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar" style="color:#2c6fad"></i>
                </span>
              </div>
            </div>
          </div>

        </div>

        <div class="pf-divider"></div>

        <div class="pf-grid" style="margin-top:8px">

          <!-- Poli / Klinik Asal -->
          <div class="pf-item pf-item-grow">
            <label class="pf-label"><i class="fa fa-hospital-o" style="color:#2c6fad"></i> Poli / Klinik Asal</label>
            <?php echo $this->master->custom_selection(
              array(
                'table'    => 'mt_bagian',
                'id'       => 'kode_bagian',
                'name'     => 'nama_bagian',
                'where'    => array('pelayanan' => 1, 'status_aktif' => 1),
                'where_in' => array('col' => 'validasi', 'val' => array('0100','0300','0500'))
              ), '', 'bagian', 'bagian', 'pf-control', '', ''
            ) ?>
          </div>

          <!-- Status Iter -->
          <div class="pf-item">
            <label class="pf-label"><i class="fa fa-flag" style="color:#2c6fad"></i> Status Pengambilan</label>
            <select name="status_iter" id="status_iter" class="pf-control" style="min-width:160px">
              <option value="All">— Semua Status —</option>
              <option value="0">Belum Diambil</option>
              <option value="1">Sudah Diambil</option>
            </select>
          </div>

          <!-- Action buttons -->
          <div class="pf-item" style="justify-content:flex-end">
            <label class="pf-label">&nbsp;</label>
            <div style="display:flex; gap:6px">
              <a href="#" id="btn_search_data" class="pf-btn pf-btn-search">
                <i class="fa fa-search"></i> Cari
              </a>
              <a href="#" id="btn_reset_data" class="pf-btn pf-btn-reset">
                <i class="fa fa-refresh"></i> Reset
              </a>
            </div>
          </div>

        </div>

      </div><!-- /.pu-body -->
    </div><!-- /.pu-wrap -->

    <!-- ===== Table Card ===== -->
    <div class="pu-wrap">
      <div class="pu-hdr">
        <i class="fa fa-list-ul"></i> Daftar Resep Iter
      </div>
      <div class="pu-body" style="padding:0">
        <table id="dynamic-table" base-url="farmasi/Pengambilan_resep_iter/get_data?flag=All" class="table table-bordered table-hover" style="margin:0">
          <thead>
            <tr>
              <th class="center">No</th>
              <th>No Resep</th>
              <th>Tgl Pesan</th>
              <th>No MR</th>
              <th>Nama Pasien</th>
              <th>Nama Dokter</th>
              <th>No SEP</th>
              <th>Tgl Pengambilan</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div><!-- /.pu-wrap -->

  </form>

</div><!-- /.col -->
</div><!-- /.row -->

<script>
jQuery(function($) {
  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

$('.pf-control, .form-control').keypress(function(event){
  var keycode = (event.keyCode ? event.keyCode : event.which);
  if(keycode == 13){
    event.preventDefault();
    $('#btn_search_data').click();
    return false;
  }
});

$('#keyword').keypress(function(event){
  var keycode = (event.keyCode ? event.keyCode : event.which);
  if(keycode == 13){
    event.preventDefault();
    $('#btn_search_data').click();
    return false;
  }
});

function popUnder(node) {
  var newWindow = window.open('about:blank', node.target, 'width=700,height=500');
  window.focus();
  newWindow.location.href = node.href;
  return false;
}
</script>

<script src="<?php echo base_url().'assets/js/custom/als_datatable_custom_url.js'?>"></script>
