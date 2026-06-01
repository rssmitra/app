<style>
/* ── Agenda info card ── */
.lhs-agenda-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #0891b2;
    border-radius: 6px;
    padding: 14px 18px;
    margin-bottom: 16px;
}
.lhs-agenda-card .lhs-label {
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 2px;
}
.lhs-agenda-card .lhs-value {
    font-size: 13px;
    color: #1e293b;
    font-weight: 500;
}

/* ── Tabs ── */
.lhs-tab-nav {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 14px;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 10px;
}
.lhs-tab-nav a.lhs-tab-btn {
    padding: 7px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    border: 2px solid #0891b2;
    color: #0891b2;
    text-decoration: none;
    transition: .2s;
    white-space: nowrap;
}
.lhs-tab-nav a.lhs-tab-btn.active,
.lhs-tab-nav a.lhs-tab-btn:hover {
    background: #0891b2;
    color: #fff;
}

/* ── Tab content area ── */
#tabs_so {
    min-height: 120px;
}
.lhs-welcome-box {
    display: flex;
    align-items: center;
    gap: 12px;
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border: 1px solid #bae6fd;
    border-radius: 6px;
    padding: 16px 20px;
    color: #0369a1;
    font-size: 13px;
}
.lhs-welcome-box i {
    font-size: 22px;
    opacity: .8;
}
</style>

<script>
$(document).ready(function() {
    $('#form_Lap_hasil_so').ajaxForm({
        beforeSend: function() { achtungShowLoader(); },
        complete: function(xhr) {
            var jsonResponse = JSON.parse(xhr.responseText);
            if (jsonResponse.status === 200) {
                $.achtung({ message: jsonResponse.message, timeout: 5 });
                $('#page-area-content').load('inventory/so/Lap_hasil_so?_=' + (new Date()).getTime());
            } else {
                $.achtung({ message: jsonResponse.message, timeout: 5, className: 'achtungFail' });
            }
            achtungHideLoader();
        }
    });
});
</script>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_Lap_hasil_so"
          action="<?php echo site_url('inventory/so/Lap_hasil_so/process')?>"
          enctype="multipart/form-data">

      <a onclick="getMenu('inventory/so/Lap_hasil_so')" href="#" class="btn btn-sm btn-default">
        <i class="fa fa-arrow-left"></i> Kembali ke Daftar
      </a>

      <hr class="separator">

      <!-- Agenda Info Card -->
      <div class="lhs-agenda-card">
        <div class="row">
          <div class="col-sm-6 col-md-3" style="margin-bottom:8px">
            <div class="lhs-label">Agenda SO</div>
            <div class="lhs-value"><?php echo isset($value->agenda_so_name) ? $value->agenda_so_name : '-' ?></div>
          </div>
          <div class="col-sm-6 col-md-3" style="margin-bottom:8px">
            <div class="lhs-label">Tanggal Pelaksanaan</div>
            <div class="lhs-value">
              <?php echo isset($value->agenda_so_date) ? $this->tanggal->formatDate($value->agenda_so_date) : '-' ?>
            </div>
          </div>
          <div class="col-sm-6 col-md-3" style="margin-bottom:8px">
            <div class="lhs-label">Penanggung Jawab</div>
            <div class="lhs-value"><?php echo isset($value->agenda_so_spv) ? $value->agenda_so_spv : '-' ?></div>
          </div>
          <div class="col-sm-12 col-md-3" style="margin-bottom:8px">
            <div class="lhs-label">Keterangan</div>
            <div class="lhs-value"><?php echo isset($value->agenda_so_desc) ? $value->agenda_so_desc : '-' ?></div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="lhs-tab-nav">
        <a href="#" class="lhs-tab-btn"
           data-url="inventory/so/Lap_hasil_so/view_data_bag_so"
           data-id="<?php echo $value->agenda_so_id?>/medis"
           onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_so'); $('.lhs-tab-btn').removeClass('active'); $(this).addClass('active'); return false;">
          <i class="fa fa-medkit"></i> Hasil SO Unit (Medis)
        </a>
        <a href="#" class="lhs-tab-btn"
           data-url="inventory/so/Lap_hasil_so/view_data_bag_so"
           data-id="<?php echo $value->agenda_so_id?>/non_medis"
           onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_so'); $('.lhs-tab-btn').removeClass('active'); $(this).addClass('active'); return false;">
          <i class="fa fa-archive"></i> Hasil SO Unit (Non Medis)
        </a>
        <a href="#" class="lhs-tab-btn"
           data-url="inventory/so/Lap_hasil_so/view_data_bag_so_rs"
           data-id="<?php echo $value->agenda_so_id?>/medis"
           onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_so'); $('.lhs-tab-btn').removeClass('active'); $(this).addClass('active'); return false;">
          <i class="fa fa-hospital-o"></i> Stok Opname RS (Medis)
        </a>
        <a href="#" class="lhs-tab-btn"
           data-url="inventory/so/Lap_hasil_so/view_data_bag_so_rs"
           data-id="<?php echo $value->agenda_so_id?>/non_medis"
           onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_so'); $('.lhs-tab-btn').removeClass('active'); $(this).addClass('active'); return false;">
          <i class="fa fa-list-alt"></i> Stok Opname RS (Non Medis)
        </a>
      </div>

      <!-- Tab Content -->
      <div id="tabs_so">
        <div class="lhs-welcome-box">
          <i class="fa fa-info-circle"></i>
          <span>Pilih tab di atas untuk melihat data hasil Stok Opname (SO).</span>
        </div>
      </div>

    </form>

  </div>
</div>
