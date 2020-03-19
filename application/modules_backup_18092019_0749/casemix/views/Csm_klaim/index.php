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

    <div class="clearfix" style="margin-bottom:-5px">
      <?php echo $this->authuser->show_button('casemix/Csm_klaim','C','',1)?>
        <?php echo $this->authuser->show_button('casemix/Csm_klaim','D','',5)?>

    </div>
    <hr class="separator">
    <!-- div.table-responsive -->

    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th width="50px" class="center">Kode</th>
            <th width="100px">Periode</th>
            <th>Waktu Input Klaim</th>
            <th>Petugas Casemix</th>
            <th>Dibuat Tanggal</th>
            <th width="70px" class="center">Dokumen</th>
            <th width="70px" class="center">RJ</th>
            <th width="70px" class="center">RI</th>
            <th>Total (Rp)</th>
            <th width="100px" class="center">Report Klaim</th>
            <th width="100px" class="center">Label</th>
            <th width="100px" class="center">File Klaim</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->


<script src="<?php echo base_url()?>/assets/js/dataTables/jquery.dataTables.js"></script>
<script src="<?php echo base_url()?>/assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
<script src="<?php echo base_url()?>/assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
<script src="<?php echo base_url()?>/assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>
<script src="<?php echo base_url().'assets/js/custom/casemix/Csm_klaim.js'?>"></script>



