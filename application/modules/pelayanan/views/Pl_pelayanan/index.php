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

oTable = $('#dynamic-table').DataTable({ 
          
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "ordering": false,
          "searching": false,
          "bLengthChange": false,
          // "pageLength": 25,
          "bInfo": false,
          "paging": false,
          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": $('#dynamic-table').attr('base-url'),
              "type": "POST"
          },
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

$('#btn_search_data').click(function (e) {
    e.preventDefault();
    $.ajax({
    url:  $('#form_search').attr('action'),
    type: "post",
    data: $('#form_search').serialize(),
    dataType: "json",
    beforeSend: function() {
      achtungShowLoader();  
    },
    success: function(data) {
      achtungHideLoader();
      find_data_reload(data);
    }
  });
});

function find_data_reload(result){

  oTable.ajax.url( $('#dynamic-table').attr('base-url')+'&'+result.data).load();
  $("html, body").animate({ scrollTop: "400px" });

}

$('#btn_update_session_poli').click(function (e) {  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan/destroy_session_kode_bagian",
      data: { kode: $('#sess_kode_bagian').val()},            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          getMenu('pelayanan/Pl_pelayanan');
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

});

function cancel_visit(no_registrasi, no_kunjungan){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan/cancel_visit",
      data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#sess_kode_bagian').val() },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          getMenu('pelayanan/Pl_pelayanan');
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

}

function rollback(no_registrasi, no_kunjungan, flag){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan/rollback",
      data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#kode_bagian_val').val(), flag: flag },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          reload_table();
          //getMenu('pelayanan/Pl_pelayanan');
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

}


</script>
<style>
  #idx-wrap {
    font-family: 'Segoe UI', system-ui, Arial, sans-serif;
    font-size: 13px;
  }

  /* ── Header bar ── */
  .idx-header-bar {
    background: #ffffff;
    border-top: 4px solid #0ea5e9;
    border: 1px solid #e2e8f0;
    border-top-width: 4px;
    border-radius: 12px 12px 0 0;
    padding: 16px 20px 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
  }
  .idx-header-title {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 2px;
    line-height: 1.3;
  }
  .idx-header-sub {
    font-size: 11.5px;
    color: #64748b;
    margin: 0;
  }
  .idx-header-date {
    font-size: 11.5px;
    color: #0ea5e9;
    font-weight: 600;
    margin-top: 3px;
  }

  /* ── Body ── */
  .idx-body {
    background: #f1f5f9;
    border: 1px solid #dde3ec;
    border-top: none;
    border-radius: 0 0 12px 12px;
    padding: 14px;
  }

  /* ── Panel ── */
  .idx-panel {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 6px rgba(0,0,0,.05);
    overflow: hidden;
    margin-bottom: 14px;
  }
  .idx-panel-hdr {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    border-left: 3px solid #0ea5e9;
    padding: 9px 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #0f172a;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
  }
  .idx-panel-hdr i { color: #0ea5e9; }
  .idx-panel-body { padding: 14px 16px; }

  /* ── Form label ── */
  .idx-flabel {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: #64748b;
    display: block;
    margin-bottom: 5px;
  }

  /* ── Buttons ── */
  .idx-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .18s, transform .18s;
    color: #fff;
  }
  .idx-btn:hover { opacity: .88; transform: translateY(-1px); color: #fff; text-decoration: none; }
  .idx-btn-blue   { background: linear-gradient(135deg, #0369a1, #0ea5e9); }
  .idx-btn-amber  { background: linear-gradient(135deg, #b45309, #f59e0b); }
  .idx-btn-green  { background: linear-gradient(135deg, #15803d, #22c55e); }

  /* ── Table ── */
  #idx-wrap #dynamic-table thead tr th {
    background: #f0f9ff;
    color: #0369a1;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    border-bottom: 2px solid #bae6fd;
    border-top: none;
    padding: 10px 12px;
    white-space: nowrap;
  }
  #idx-wrap #dynamic-table tbody tr td {
    padding: 10px 12px;
    font-size: 12.5px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
  }
  #idx-wrap #dynamic-table tbody tr:hover td {
    background: #f0f9ff;
  }
  #idx-wrap #dynamic-table {
    border-collapse: collapse;
    width: 100%;
  }
</style>

<div class="row" id="idx-wrap">
  <div class="col-xs-12">

    <!-- ── Header ── -->
    <div class="idx-header-bar">
      <div>
        <p class="idx-header-title">
          <i class="fa fa-hospital-o" style="color:#0ea5e9; margin-right:6px;"></i>
          <?php echo strtoupper($nama_bagian); ?>
        </p>
        <?php if(isset($nama_dokter) && $nama_dokter): ?>
          <p class="idx-header-sub"><i class="fa fa-user-md" style="color:#64748b; margin-right:4px;"></i><?php echo strtoupper($nama_dokter); ?></p>
        <?php endif; ?>
        <p class="idx-header-date"><i class="fa fa-calendar" style="margin-right:4px;"></i>Data per hari ini &mdash; <?php echo $this->tanggal->formatDate(date('Y-m-d')); ?></p>
      </div>
      <div>
        <small style="font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.4px;">
          <?php echo $title; ?> &rsaquo; <?php echo isset($breadcrumbs)?$breadcrumbs:''; ?>
        </small>
      </div>
    </div>

    <!-- ── Body ── -->
    <div class="idx-body">

      <form method="post" id="form_search" action="pelayanan/Pl_pelayanan/find_data" autocomplete="off">
        <input type="hidden" name="sess_kode_bagian" value="<?php echo ($this->session->userdata('kode_bagian'))?$this->session->userdata('kode_bagian'):''?>" id="sess_kode_bagian">

        <!-- ── Filter Panel ── -->
        <div class="idx-panel">
          <div class="idx-panel-hdr">
            <i class="fa fa-filter"></i> Filter &amp; Pencarian
          </div>
          <div class="idx-panel-body">
            <div class="row">

              <div class="col-md-2 col-sm-6">
                <label class="idx-flabel">Cari berdasarkan</label>
                <select name="search_by" class="form-control input-sm">
                  <option value="">-Silahkan Pilih-</option>
                  <option value="tc_kunjungan.no_mr" selected>No MR</option>
                  <option value="pl_tc_poli.nama_pasien">Nama Pasien</option>
                </select>
              </div>

              <div class="col-md-3 col-sm-6">
                <label class="idx-flabel">Keyword</label>
                <input type="text" class="form-control input-sm" name="keyword" id="keyword_form" placeholder="Ketik keyword...">
              </div>

              <div class="col-md-2 col-sm-6">
                <label class="idx-flabel">Tanggal Dari</label>
                <div class="input-group">
                  <input class="form-control input-sm date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="" placeholder="yyyy-mm-dd"/>
                  <span class="input-group-addon" style="cursor:pointer;"><i class="fa fa-calendar"></i></span>
                </div>
              </div>

              <div class="col-md-2 col-sm-6">
                <label class="idx-flabel">Tanggal Sampai</label>
                <div class="input-group">
                  <input class="form-control input-sm date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="" placeholder="yyyy-mm-dd"/>
                  <span class="input-group-addon" style="cursor:pointer;"><i class="fa fa-calendar"></i></span>
                </div>
              </div>

              <div class="col-md-3 col-sm-12">
                <label class="idx-flabel">&nbsp;</label>
                <div style="display:flex; gap:6px; flex-wrap:wrap; margin-top:1px;">
                  <a href="#" id="btn_search_data" class="idx-btn idx-btn-blue">
                    <i class="fa fa-search"></i> Cari
                  </a>
                  <a href="#" id="btn_reset_data" class="idx-btn idx-btn-amber">
                    <i class="fa fa-refresh"></i> Reset
                  </a>
                  <?php if(!isset($_GET['bag'])): ?>
                    <a href="#" id="btn_update_session_poli" class="idx-btn idx-btn-green">
                      <i class="fa fa-bolt"></i> Ganti Sesi
                    </a>
                  <?php endif; ?>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- ── Table Panel ── -->
        <div class="idx-panel">
          <div class="idx-panel-hdr">
            <i class="fa fa-list-alt"></i> Data Kunjungan Pasien
          </div>
          <div style="overflow-x:auto;">
            <table id="dynamic-table" base-url="pelayanan/Pl_pelayanan/get_data?bag=<?php echo isset($kode_bagian)?$kode_bagian:''?>" class="table table-bordered table-hover" style="margin:0;">
              <thead>
                <tr>
                  <th width="36px" class="center">No</th>
                  <th class="center">#</th>
                  <th width="90px">No MR</th>
                  <th>Nama Pasien</th>
                  <th>Penjamin</th>
                  <th>No SEP</th>
                  <th>Waktu Masuk</th>
                  <th>Waktu Keluar</th>
                  <th>Antrian ke-</th>
                  <th>Update Terakhir</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>

      </form>

    </div><!-- /.idx-body -->

  </div><!-- /.col -->
</div><!-- /.row -->




