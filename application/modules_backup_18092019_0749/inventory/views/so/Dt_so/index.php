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

     <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data" target="blank">
      <br>

      <input type="hidden" name="flag" value="so_mod_1">
      <input type="hidden" name="title" value="Daftar Barang Yang Akan di Stok Opname">

      <div class="form-group">
        <label class="control-label col-md-2">Bagian Unit</label>
          <div class="col-md-4">
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1)), '' , 'bagian', 'bagian', 'form-control', '', '') ?>
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
        </div>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



