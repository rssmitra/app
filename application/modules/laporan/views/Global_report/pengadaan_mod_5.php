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
        <a href="<?php echo base_url().'laporan/Global_report'?>" class="btn btn-sm glr-btn-back"><i class="fa fa-arrow-left"></i> Menu Laporan</a>
        <br>
        <h4 class="glr-form-title">LAPORAN MONITORING USULAN PERMINTAAN
        </h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data_po">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="LAPORAN MONITORING USULAN PERMINTAAN">


          <div class="form-group">
            <label class="control-label col-md-1">Keterangan</label>
               <div class="col-md-4">
                <select name="keterangan">
                  <option value="medis">-- Medis -- </option>
                  <option value="nmmedis">-- Non Medis --</option>
                </select>
              </div>
          </div>
          <div class="form-group">
            
          </div>
          <div class="form-group">
              <label class="control-label col-md-1">Pencarian dengan</label>
               <div class="col-md-2">
                <select name="search_by" class="form-control">
                  <option value="usulan">Usulan</option>
                  <option value="penerbitan_po">Penerbitan PO</option>
                  <option value="penerimaan">Penerimaan Barang</option>
                </select>
              </div>
              <label class="control-label col-md-1">Bulan </label>
              <div class="col-md-1">
                <?php echo $this->master->get_bulan(date('m'),'from_month','from_month','form-control','','');?>
              </div>
              <label class="control-label col-md-1">Tahun</label>
              <div class="col-md-1">
                <?php echo $this->master->get_tahun(date('Y'),'year','year','form-control','','');?>
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
</body>
</html>






