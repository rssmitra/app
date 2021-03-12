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
    <form class="form-horizontal" method="post" id="form_search" action="akunting/Mst_coa/find_data">
      <!-- hidden form -->
      <!-- <div class="form-group" style="margin-bottom: 3px">
        <div class="control-label col-md-2">
          <div class="checkbox" style="margin-top: -5px">
            <label>
              <input name="checked_kode_akun" id="checked_kode_akun" type="checkbox" class="ace" value="1">
              <span class="lbl"> Kode Akun</span>
            </label>
          </div>
        </div>
        <div class="col-md-2" style="margin-left: -15px">
          <input type="text" class="form-control" name="acc_no" id="inputAccount">
        </div>
        <div class="col-md-3" style="margin-left: -1%">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Cari
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-danger">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reload
          </a>
          <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
            <i class="ace-icon fa fa-file-excel icon-on-right bigger-110"></i>
            Export Excel
          </a>
        </div>

      </div>
      <hr> -->
      <div class="clearfix" style="margin-bottom:-5px">
        <?php echo $this->authuser->show_button('akunting/Mst_coa','C','',1)?>
        <?php echo $this->authuser->show_button('akunting/Mst_coa','D','',5)?>
        <?php echo $this->authuser->show_button('akunting/Mst_coa','EX','',1)?>
      </div>
      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="akunting/Mst_coa" class="table table-bordered table-hover">
           <thead>
            <tr>  
              <th width="30px" class="center"></th>
              <th width="100px">Kode Akun</th>
              <th>Nama Akun</th>
              <th width="100px">Saldo Normal</th>
              <th width="100px">Level COA</th>
              <th width="100px">Status</th>
              <th width="120px">&nbsp;</th>
              
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </form>
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



