<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#tabs_detail_jadwal').load('information/regon_info_jadwal_dr/jadwal_dokter');

    // Tab click active state
    $('#jadwalTab a').on('click', function(){
      $('#jadwalTab li').removeClass('active');
      $(this).parent('li').addClass('active');
    });
  })
</script>

<style type="text/css">
    /* Page header */
    .page-header-idx {
      border-bottom: 3px solid #2c6fad;
      padding-bottom: 8px;
      margin-bottom: 18px;
    }
    .page-header-idx h1 {
      font-size: 20px;
      color: #1a4f8a;
      font-weight: 700;
      margin: 0;
    }
    .page-header-idx h1 small {
      font-size: 13px;
      color: #888;
      font-weight: 400;
    }

    /* Card wrapper */
    .jd-card {
      border: 1px solid #d0dce8;
      border-radius: 5px;
      background: #fff;
      box-shadow: 0 1px 4px rgba(44,111,173,.07);
      margin-bottom: 14px;
      overflow: hidden;
    }
    .jd-card-hdr {
      background: #1a4f8a;
      color: #fff;
      padding: 10px 16px;
      font-weight: 700;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .jd-card-hdr small {
      font-weight: 400;
      opacity: .85;
      font-size: 12px;
      margin-left: auto;
    }
    .jd-card-body {
      padding: 0;
      background: #fff;
    }

    /* Modern tab styling */
    .jd-tabs {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
      background: #f0f5fb;
      border-bottom: 2px solid #d0dce8;
    }
    .jd-tabs li {
      margin: 0;
    }
    .jd-tabs li a {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 12px 22px;
      font-size: 13px;
      font-weight: 600;
      color: #4a6d8c;
      text-decoration: none;
      border-bottom: 3px solid transparent;
      transition: all 0.2s ease;
      cursor: pointer;
    }
    .jd-tabs li a:hover {
      background: #e4edf7;
      color: #1a4f8a;
    }
    .jd-tabs li.active a {
      color: #1a4f8a;
      border-bottom: 3px solid #2c6fad;
      background: #fff;
    }
    .jd-tabs li a i {
      font-size: 15px;
    }
    .jd-tabs li a .tab-badge {
      background: #2c6fad;
      color: #fff;
      border-radius: 10px;
      padding: 1px 8px;
      font-size: 11px;
      font-weight: 600;
    }
    .jd-tabs li.active a .tab-badge {
      background: #1a4f8a;
    }

    /* Tab content area */
    .jd-tab-content {
      padding: 16px;
      min-height: 200px;
    }

    /* Table styles (shared) */
    table {
      width: 100% !important;
      font-size: 12px;
    }
    .table-custom thead {
      background-color: #2c6fad;
      color: white;
    }
    .table-custom th, td {
      padding: 10px;
      border: 1px solid #c5d0dc;
    }
    .table-custom thead th {
      color: #fff !important;
      font-size: 12px;
      font-weight: 600;
      border-color: #1e5590 !important;
    }
    .table-custom tbody tr:hover {
      background-color: #eef4fb;
    }
</style>

<div class="row">
  <div class="col-xs-12">

    <!-- Page Header -->
    <div class="page-header-idx">
      <h1>
        <i class="fa fa-calendar-check-o" style="color:#2c6fad"></i>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div>

    <!-- Main Card -->
    <div class="jd-card">
      <div class="jd-card-hdr">
        <i class="fa fa-stethoscope"></i> Manajemen Jadwal Dokter
        <small><i class="fa fa-info-circle"></i> Kelola jadwal praktek dan cuti dokter</small>
      </div>
      <div class="jd-card-body">

        <!-- Tab Navigation -->
        <ul class="jd-tabs" id="jadwalTab">
          <li class="active">
            <a data-toggle="tab" id="tabs_jadwal_dokter" href="#" data-id="0"
               data-url="information/regon_info_jadwal_dr/jadwal_dokter"
               onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_detail_jadwal')">
              <i class="fa fa-clock-o" style="color:#2c6fad"></i>
              Jadwal Dokter
            </a>
          </li>
          <li>
            <a data-toggle="tab" data-id="0"
               data-url="information/regon_info_jadwal_dr/cuti_dokter"
               id="tabs_cuti_dokter" href="#"
               onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_detail_jadwal')">
              <i class="fa fa-calendar-times-o" style="color:#d9534f"></i>
              Cuti Dokter
            </a>
          </li>
          <li>
            <a data-toggle="tab" data-id="0"
               data-url="information/regon_info_jadwal_dr/lihat_jadwal_dokter"
               id="tabs_lihat_jadwal_dokter" href="#"
               onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_detail_jadwal')">
              <i class="fa fa-search" style="color:#5cb85c"></i>
              Pencarian Jadwal Dokter
            </a>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="jd-tab-content">
          <div id="tabs_detail_jadwal"></div>
        </div>

      </div>
    </div>

  </div>
</div>
