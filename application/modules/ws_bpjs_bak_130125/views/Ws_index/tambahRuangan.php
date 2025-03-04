<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/custom/registrasi/create_sep.js"></script>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div>
<!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
    <div class="col-xs-12">
      <div class="widget-body">
        <div class="widget-main no-padding">

          <form class="form-horizontal" method="post" id="formInsertSep" action="<?php echo base_url().'ws_bpjs/ws_index/insertSep'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">
            <br>
            <div class="form-group">
              <label class="control-label col-md-2">Kelas Rawat</label>
              <div class="col-md-4">
                  <select name="kelasRawat" id="select_option" class="form-control">
                    <option value="">- Silahkan Pilih -</option>
                  </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Kode Ruang</label>
              <div class="col-md-3">
                <input type="text" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Nama Ruangan</label>
              <div class="col-md-3">
                <input type="text" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Kapasitas</label>
              <div class="col-md-3">
                <input type="text" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Ketersediaan</label>
              <div class="col-md-3">
                <input type="text" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Pria</label>
              <div class="col-md-3">
                <input type="text" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Wanita</label>
              <div class="col-md-3">
                <input type="text" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Pria Wanita</label>
              <div class="col-md-3">
                <input type="text" class="form-control">
              </div>
            </div>

              <div class="form-group">
                <label class="control-label col-md-2">&nbsp;</label>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary btn-sm">
                        <span class="ace-icon fa fa-save icon-on-right bigger-110"></span>
                        Submit
                      </button>
                </div>
              </div>

            </div>

          </form>

        </div>
      </div>
    </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


