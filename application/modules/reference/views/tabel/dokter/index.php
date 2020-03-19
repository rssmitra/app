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
      <?php echo $this->authuser->show_button('reference/tabel/karyawan','C','',1)?>
      <?php echo $this->authuser->show_button('reference/tabel/karyawan','D','',5)?>
    </div>
    <hr class="separator">
    <!-- div.table-responsive -->

    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="reference/tabel/dokter" class="table table-striped table-bordered table-hover">
       <thead>
        <tr>  
          <th class="center">@</th>
          <th class="center"></th>
          <th class="center">No MR</th>
          <th class="center">No Induk</th>
          <th class="center">Nama Dokter</th>
          <th class="center">Spesialis</th>
          <th class="center">Bagian</th>
          <th class="center">Status Dr</th>
          <th class="center">Keterangan</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



