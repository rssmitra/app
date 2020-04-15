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

        <center><h4>LAPORAN UMUM SELURUH MODUL <?php echo APPS_NAME_SORT?></h4></center>

        <!-- laporan akunting -->
        <b>AKUNTING</b>
        <ol>
          <li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=1'?>" target="_blank">Laporan Transaksi Pada</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=2'?>" target="_blank">Transaksi Pasien BPJS</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=3'?>" target="_blank">Laporan BMHP (Barang Medis Habis Pakai)</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=4'?>" target="_blank">Laporan IF (Unit Farmasi)</a></li>
        </ol>

        <!-- laporan pengadaan -->
        <b>PENGADAAN</b>
        <ol>
          <li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=1'?>" target="_blank">Laporan Stok Akhir Barang Non Medis Berdasarkan Tanggal Terakhir Stok</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=8'?>" target="_blank">Laporan Stok Akhir Barang Non Medis Berdasarkan Master Barang</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=3'?>" target="_blank">Laporan Penerimaan Barang</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=10'?>" target="_blank">Laporan Penerimaan Barang Detail</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=2'?>" target="_blank">Laporan Keluar Barang ke Unit Per-periode</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=3'?>" target="_blank">Laporan Rekap Keluar Barang ke Unit Per-periode</a></li>
           <li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=7'?>" target="_blank">Laporan Rekap Keluar Barang ke Unit Per-Barang</a></li>
           <li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=4'?>" target=_blank>Laporan Permintaan Pembelian </a></li>
           <li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=5'?>" target="_blank">Laporan PO </a></li>
           <li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=6'?>" target="_blank">Laporan Pembelian </a></li>
           <li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=9'?>" target="_blank">Laporan Mutasi Per-periode</a></li>
        </ol>

        <!-- laporan farmasi -->
        <b>FARMASI</b>
        <ol>
          <li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=1'?>" target="_blank">Laporan Keluar/Masuk Obat & Alkes Berdasarkan Tahun</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=2'?>" target="_blank">Laporan Stok Akhir Barang Medis Berdasarkan Mutasi Tanggal Terakhir</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=4'?>" target="_blank">Laporan Bon Obat</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=5'?>" target="_blank">Laporan Mutasi Obat & Alkes Per-periode</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=6'?>" target="_blank">Laporan Penjualan Jenis Obat Racikan/Non Racikan</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=7'?>" target="_blank">Laporan Pembelian Obat Cito</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=8'?>" target="_blank">Laporan Pemesanan Resep</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=9'?>" target="_blank">Laporan Penjualan Obat Per Kategori</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/lappembelian'?>" target="_blank">Laporan Pembelian Obat (Operational Level)</a></li>
        </ol>

        <b>STOK OPNAME</b>
        <ol>
          <li><a href="<?php echo base_url().'laporan/Global_report/so?mod=1'?>" target="_blank">Daftar Barang Yang Akan di Stok Opname</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/so?mod=2'?>" target="_blank">Laporan Hasil SO</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/so?mod=3'?>" target="_blank">Laporan Sebelum SO</a></li>
          
        </ol>

        <b>KUNJUNGAN PASIEN</b>
        <ol>
          <li><a href="<?php echo base_url().'laporan/Global_report/kunjungan?mod=1'?>" target="_blank">Daftar Kunjungan Pasien Berdasarkan Usia dan Tahun Kunjungan</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/kunjungan?mod=2'?>" target="_blank">Daftar Kunjungan Pasien Per-hari</a></li>
          <li><a href="<?php echo base_url().'laporan/Global_report/kunjungan?mod=3'?>" target="_blank">Daftar Registrasi Pasien Per-hari</a></li>
        </ol>

        <b>KASIR</b>
        <ol>
          <li><a href="<?php echo base_url().'laporan/Global_report/lainnyabillingdokter?mod=1'?>" target="_blank">Daftar Billing Dokter yang belum dibayarkan Per-periode</a></li>
        </ol>

        <b>REKAM MEDIS</b>
        <ol>
          <li><a href="<?php echo base_url().'laporan/Global_report/laporanrl'?>" target="_blank">Laporan RL</a></li>
        </ol>

      </div>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






