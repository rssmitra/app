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

        <!-- content -->
        <a href="<?php echo base_url().'laporan/Global_report'?>" class="btn btn-xs btn-primary"> Kembali ke Menu Utama</a>
        <br>
        <h4>Pencarian Data Kunjungan Pasien</h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data" target="blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Data Pasien Berdasarkan Kunjungan Pasien dan Umur">
          
          <div class="form-group">
            <label class="control-label col-md-1">Bagian</label>
              <div class="col-md-4">
                <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1)), '' , 'bagian', 'bagian', 'form-control', '', '') ?>
              </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-1">Tahun</label>
              <div class="col-md-1">
               <input type="text" name="year">
              </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-1">Umur Pasien</label>
              <div class="col-md-1">
               <select name="sign" class="form-control">
                 <option value="="> = (sama dengan)</option>
                 <option value=">="> >= (lebih besar sama dengan)</option>
                 <option value="<="> <= (lebih kecil sama dengan)</option>
               </select>
              </div>
              <div class="col-md-1">
               <input type="text" name="age">
              </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2 ">&nbsp;</label>
            <div class="col-md-10" style="margin-left: 5px">
              <button type="submit" name="submit" value="data" class="btn btn-xs btn-default">
                Proses Pencarian
              </button>
              <button type="submit" name="submit" value="excel" class="btn btn-xs btn-success">
                Export Excel
              </button>
              <button type="submit" name="submit" value="format_so" class="btn btn-xs btn-primary">
                Format Form Stok Opname
              </button>
              <button type="submit" name="submit" value="input_so" class="btn btn-xs btn-danger">
                Input Data Stok Opname
              </button>
            </div>
          </div>

        </form>
        <!-- end content -->
        
     </div>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






