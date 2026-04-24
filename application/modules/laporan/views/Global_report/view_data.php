<?php

  if($_POST['submit']=='excel') {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$flag.'_'.date('Ymd').".xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <title><?php echo isset($title) ? $title : 'Laporan'; ?> &mdash; <?php echo APPS_NAME_SORT; ?></title>

  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

  <script src="<?php echo base_url()?>assets/js/ace-extra.js"></script>

  <style>
    /* ===== Base ===== */
    body { background: #f0f4f8 !important; }

    /* ===== Navbar ===== */
    .h-navbar {
      background: -webkit-linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%) !important;
      background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%) !important;
    }
    .navbar-brand small { color: #fff; font-size: 15px; font-weight: 600; letter-spacing: .4px; }
    .navbar-brand small i { margin-right: 6px; }

    /* ===== Page Header ===== */
    .page-header { border-bottom: 2px solid #0ea5e9 !important; margin-bottom: 16px; padding-bottom: 10px; }
    .page-header h1 { font-size: 18px; font-weight: 700; color: #1e3a5f; margin: 0; }
    .page-header h1 small { font-size: 12px; color: #64748b; }

    /* ===== Card ===== */
    .vd-card {
      background: #fff; border-radius: 8px; overflow: hidden;
      -webkit-box-shadow: 0 1px 6px rgba(0,0,0,.08); box-shadow: 0 1px 6px rgba(0,0,0,.08);
    }

    /* ===== Toolbar ===== */
    .vd-toolbar {
      display: -webkit-flex; display: flex;
      -webkit-align-items: center; align-items: center;
      -webkit-flex-wrap: wrap; flex-wrap: wrap;
      gap: 8px; padding: 11px 16px;
      background: #f8fafc; border-bottom: 1px solid #e2e8f0;
    }
    .vd-record-badge {
      display: -webkit-inline-flex; display: inline-flex;
      -webkit-align-items: center; align-items: center;
      gap: 5px; background: #e0f2fe; color: #0369a1;
      border: 1px solid #bae6fd; border-radius: 6px;
      padding: 4px 10px; font-size: 12px; font-weight: 700;
    }
    .vd-record-badge .vd-count { font-size: 15px; font-weight: 900; }
    .vd-toolbar-right { margin-left: auto; display: -webkit-flex; display: flex; gap: 6px; }
    .vd-btn-print {
      background: #f1f5f9; border: 1px solid #e2e8f0; color: #475569 !important;
      border-radius: 6px; padding: 5px 12px; font-size: 12px; font-weight: 600;
      cursor: pointer; text-decoration: none; display: inline-block;
    }
    .vd-btn-print:hover { background: #e2e8f0; color: #1e3a5f !important; text-decoration: none; }
    .vd-btn-back {
      background: -webkit-linear-gradient(135deg, #0369a1, #0ea5e9);
      background: linear-gradient(135deg, #0369a1, #0ea5e9);
      border: none; color: #fff !important;
      border-radius: 6px; padding: 5px 14px; font-size: 12px; font-weight: 600;
      cursor: pointer; text-decoration: none; display: inline-block;
    }
    .vd-btn-back:hover { opacity: .88; color: #fff !important; text-decoration: none; }

    /* ===== Table ===== */
    .vd-table-wrap { overflow-x: auto; }
    .vd-table { width: 100%; border-collapse: collapse; font-size: 11.5px; margin: 0; }
    .vd-table thead tr th {
      background: -webkit-linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%);
      background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%);
      color: #fff; font-size: 11px; font-weight: 700;
      padding: 8px 10px; border: 1px solid #1d4ed8;
      text-align: center; white-space: nowrap; letter-spacing: .3px;
    }
    .vd-table thead tr th:first-child { width: 44px; }
    .vd-table tbody tr td {
      padding: 5px 10px; border: 1px solid #e8edf1;
      color: #334155; vertical-align: middle;
    }
    .vd-table tbody tr:nth-child(even) { background: #f8fafc; }
    .vd-table tbody tr:hover { background: #eff6ff; }
    .vd-table tbody tr td:first-child {
      text-align: center; color: #64748b;
      font-size: 11px; font-weight: 600; background: #f8fafc;
    }
    .vd-table tbody tr:nth-child(even) td:first-child { background: #f1f5f9; }

    /* ===== Empty state ===== */
    .vd-empty { text-align: center; padding: 40px 20px; color: #94a3b8; font-size: 13px; }
    .vd-empty i { font-size: 32px; display: block; margin-bottom: 8px; }

    /* ===== Footer ===== */
    .footer { background: #fff; border-top: 1px solid #e2e8f0; }
    .footer-content { color: #64748b; font-size: 13px; }
    .footer-content .brand-color { color: #0369a1; font-weight: 700; }

    /* ===== Print ===== */
    @media print {
      .h-navbar, .footer, #btn-scroll-up, .vd-toolbar { display: none !important; }
      body { background: #fff !important; }
      .main-container, .main-content, .page-content { margin: 0 !important; padding: 0 !important; }
      .vd-card { -webkit-box-shadow: none !important; box-shadow: none !important; border-radius: 0 !important; }
      .vd-print-header { display: block !important; }
      .vd-table thead tr th {
        background: #1e3a5f !important;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
      }
      .vd-table tbody tr:nth-child(even) {
        background: #f8fafc !important;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
      }
    }
    .vd-print-header { display: none; text-align: center; margin-bottom: 16px; }
    .vd-print-header strong { font-size: 15px; display: block; margin-bottom: 4px; color: #1e3a5f; }
    .vd-print-header span { font-size: 12px; color: #64748b; }
  </style>
</head>

<body class="no-skin">

  <div id="navbar" class="navbar navbar-default navbar-collapse h-navbar">
    <script type="text/javascript">try{ace.settings.check('navbar','fixed')}catch(e){}</script>
    <div class="navbar-container" id="navbar-container">
      <div class="navbar-header pull-left">
        <a href="#" class="navbar-brand">
          <small>
            <i class="fa fa-hospital-o"></i>
            <?php echo strtoupper(COMP_LONG); ?>
          </small>
        </a>
        <button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
          <span class="sr-only">Toggle user menu</span>
          <img src="<?php echo base_url()?>assets/avatars/user.jpg" alt="User Photo" />
        </button>
        <button class="pull-right navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#sidebar">
          <span class="sr-only">Toggle sidebar</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
    </div>
  </div>

  <div class="main-container" id="main-container">
    <script type="text/javascript">try{ace.settings.check('main-container','fixed')}catch(e){}</script>

    <div class="main-content" style="margin-left:0!important">
      <div class="main-content-inner">
        <div class="page-content" style="padding:16px 20px 24px">

          <!-- Print-only header -->
          <div class="vd-print-header">
            <strong><?php echo isset($title) ? htmlspecialchars($title) : 'Laporan'; ?></strong>
            <span><?php echo COMP_LONG; ?> &mdash; Dicetak: <?php echo date('d/m/Y H:i'); ?></span>
          </div>

          <!-- Page Header -->
          <div class="page-header">
            <h1>
              <i class="fa fa-table" style="color:#0ea5e9;margin-right:8px;font-size:16px"></i>
              <?php echo isset($title) ? htmlspecialchars($title) : 'Data Laporan'; ?>
              <small><i class="fa fa-angle-double-right"></i> <?php echo APPS_NAME_SORT; ?></small>
            </h1>
          </div>

          <!-- Card -->
          <div class="vd-card">

            <!-- Toolbar -->
            <div class="vd-toolbar">
              <div class="vd-record-badge">
                <i class="fa fa-database"></i>
                Total Data:&nbsp;<span class="vd-count"><?php echo number_format(count($result['data'])); ?></span>&nbsp;record
              </div>
              <div class="vd-toolbar-right">
                <a href="javascript:void(0)" class="vd-btn-print" onclick="window.print()">
                  <i class="fa fa-print"></i>&nbsp;Cetak
                </a>
                <a href="javascript:history.back()" class="vd-btn-back">
                  <i class="fa fa-arrow-left"></i>&nbsp;Kembali
                </a>
              </div>
            </div>

            <!-- Table -->
            <div class="vd-table-wrap">
              <?php if (!empty($result['data'])) : ?>
              <table class="vd-table">
                <thead>
                  <tr>
                    <th>NO</th>
                    <?php foreach ($result['fields'] as $field) : ?>
                    <th><?php echo strtoupper(str_replace('_', ' ', $field->name)); ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 0; foreach ($result['data'] as $row_data) : $no++; ?>
                  <tr>
                    <td><?php echo $no; ?></td>
                    <?php foreach ($result['fields'] as $row_field) :
                      $field_name = $row_field->name; ?>
                    <td><?php echo htmlspecialchars(strtoupper($row_data->$field_name)); ?></td>
                    <?php endforeach; ?>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <?php else : ?>
              <div class="vd-empty">
                <i class="fa fa-inbox"></i>
                Tidak ada data yang ditemukan untuk parameter yang dipilih.
              </div>
              <?php endif; ?>
            </div>

          </div><!-- /.vd-card -->

        </div><!-- /.page-content -->
      </div>
    </div><!-- /.main-content -->

    <div class="footer">
      <div class="footer-inner">
        <div class="footer-content">
          <span class="bigger-120">
            <span class="brand-color"><?php echo APPS_NAME_SORT; ?></span>
            &mdash; <?php echo COMP_LONG; ?> &copy; <?php echo date('Y'); ?>
          </span>
        </div>
      </div>
    </div>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
      <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
  </div><!-- /.main-container -->

  <!--[if !IE]> -->
  <script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo base_url()?>assets/js/jquery.js'>"+"<"+"/script>");
  </script>
  <script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url()?>assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
  </script>
  <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>
  <script src="<?php echo base_url()?>assets/js/ace/ace.js"></script>
  <script src="<?php echo base_url()?>assets/js/ace/ace.settings.js"></script>

</body>
</html>
