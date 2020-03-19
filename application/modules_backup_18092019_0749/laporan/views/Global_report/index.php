<html>
<head>
  <title>Laporan Umum</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
</head>
<body>
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

        <center><h4>LAPORAN UMUM SELURUH MODUL SIRS</h4></center>

        <!-- laporan akunting -->
        <b>AKUNTING</b>
        <ol>
          <li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=1'?>">Laporan Transaksi Pada Averin</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=2'?>">Transaksi Pasien BPJS</a></li>
        </ol>

        <!-- laporan pengadaan -->
        <b>PENGADAAN</b>
        <ol>
          <li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=1'?>">Laporan Stok Akhir Barang Non Medis Berdasarkan Tanggal Terakhir Stok</a></li>
        </ol>

        <!-- laporan farmasi -->
        <b>FARMASI</b>
        <ol>
          <li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=1'?>">Laporan Keluar/Masuk Obat & Alkes Berdasarkan Tahun</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=2'?>">Laporan Stok Akhir Barang Medis Berdasarkan Mutasi Tanggal Terakhir</a></li>
        </ol>

        <b>STOK OPNAME</b>
        <ol>
          <li><a href="<?php echo base_url().'laporan/Global_report/so?mod=1'?>">Daftar Barang Yang Akan di Stok Opname</a></li>
        </ol>

      </div>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






