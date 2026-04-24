<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Umum</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
  <script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />


  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
  <style>
    body { background: #f0f4f8 !important; margin: 0; padding: 0; }
    .glr-topbar {
      background: -webkit-linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%);
      background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%);
      color: #fff; padding: 12px 20px;
      display: -webkit-flex; display: flex;
      -webkit-align-items: center; align-items: center;
      gap: 10px; font-size: 13px; font-weight: 700; letter-spacing: .2px;
      -webkit-box-shadow: 0 2px 6px rgba(3,105,161,.3); box-shadow: 0 2px 6px rgba(3,105,161,.3);
    }
    .glr-topbar .glr-topbar-home {
      margin-left: auto; color: rgba(255,255,255,.9);
      font-size: 12px; text-decoration: none; font-weight: 500;
    }
    .glr-topbar .glr-topbar-home:hover { color: #fff; text-decoration: none; }
    .glr-card {
      background: #fff; border-radius: 8px;
      -webkit-box-shadow: 0 1px 8px rgba(0,0,0,.09); box-shadow: 0 1px 8px rgba(0,0,0,.09);
      padding: 20px 24px; margin: 16px;
    }
    .glr-card .page-header { border-bottom: 2px solid #0ea5e9 !important; margin-bottom: 16px; padding-bottom: 10px; margin-top: 0; }
    .glr-card .page-header h1 { font-size: 17px; font-weight: 700; color: #1e3a5f; margin: 0; }
    .glr-card .page-header h1 small { font-size: 12px; color: #64748b; }
    h4.glr-form-title {
      font-size: 13px; font-weight: 700; color: #0369a1;
      border-left: 3px solid #0ea5e9; padding-left: 10px; margin: 10px 0 14px;
    }
    label.control-label { font-size: 12.5px; font-weight: 600; color: #374151; }
    .form-control {
      font-size: 12.5px !important; border-color: #d1d5db !important;
      border-radius: 6px !important; height: 32px !important;
    }
    .form-control:focus {
      border-color: #0ea5e9 !important;
      -webkit-box-shadow: 0 0 0 3px rgba(14,165,233,.12) !important;
      box-shadow: 0 0 0 3px rgba(14,165,233,.12) !important;
    }
    select.form-control { padding: 4px 8px !important; height: 32px !important; }
    textarea.form-control { height: auto !important; }
    .input-group-addon { padding: 4px 8px; border-radius: 0 6px 6px 0 !important; border-color: #d1d5db; }
    .btn.glr-btn-back, a.btn.glr-btn-back {
      background: #f1f5f9 !important; color: #475569 !important;
      border: 1px solid #e2e8f0 !important; border-radius: 6px !important;
      font-size: 12px !important; font-weight: 500; text-decoration: none;
    }
    .btn.glr-btn-back:hover, a.btn.glr-btn-back:hover { background: #e2e8f0 !important; color: #1e3a5f !important; }
    .btn.glr-btn-search {
      background: -webkit-linear-gradient(135deg, #0369a1, #0ea5e9) !important;
      background: linear-gradient(135deg, #0369a1, #0ea5e9) !important;
      color: #fff !important; border: none !important; border-radius: 6px !important;
      font-size: 12px !important; font-weight: 600;
    }
    .btn.glr-btn-search:hover { opacity: .9; color: #fff !important; }
    .btn.glr-btn-excel {
      background: -webkit-linear-gradient(135deg, #16a34a, #22c55e) !important;
      background: linear-gradient(135deg, #16a34a, #22c55e) !important;
      color: #fff !important; border: none !important; border-radius: 6px !important;
      font-size: 12px !important; font-weight: 600;
    }
    .btn.glr-btn-excel:hover { opacity: .9; color: #fff !important; }
    .glr-action-row { display: -webkit-flex; display: flex; gap: 8px; padding: 12px 0 4px; -webkit-flex-wrap: wrap; flex-wrap: wrap; }
    hr { border-color: #e2e8f0; }
  </style>
</head>
<body>
  <div class="glr-topbar">
    <i class="fa fa-bar-chart"></i>
    <span>Modul Laporan &mdash; Akunting &amp; Keuangan</span>
    <a href="<?php echo base_url().'laporan/Global_report'?>" class="glr-topbar-home">
      <i class="fa fa-arrow-left"></i>&nbsp;Kembali ke Menu
    </a>
  </div>
  <div class="glr-card">

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

      <div class="col-md-12">

        <!-- content -->
        <a href="<?php echo base_url().'laporan/Global_report'?>" class="btn btn-sm glr-btn-back"><i class="fa fa-arrow-left"></i> Menu Laporan</a>
        <br>
        <h4 class="glr-form-title">Laporan Stok Barang Non Medis Per-periode</h4>
        <form class="form-horizontal" method="post" id="form-default" action="<?php echo base_url()?>laporan/Global_report/show_data_stok_nm" target="_blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Laporan Stok Medis">

        
         <div class="form-group">
            <label class="control-label col-md-2">Tanggal Terakhir Stok</label>
              <div class="col-md-2">
                <input class="form-control" name="tgl" type="text" placeholder="Format : yyyy-mm-dd" value=""/>
              </div>
          </div>
                    <div class="glr-action-row">
            <button type="submit" name="submit" value="data" class="btn btn-sm glr-btn-search">
              <i class="fa fa-search"></i>&nbsp;Tampilkan Data
            </button>
            <button type="submit" name="submit" value="excel" class="btn btn-sm glr-btn-excel">
              <i class="fa fa-file-excel-o"></i>&nbsp;Export Excel
            </button>
          </div>

        </form>
        <!-- end content -->
        
     </div>

    </div><!-- /.col -->
  </div><!-- /.row -->
  </div><!-- /.glr-card -->
</body>
</html>






