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
      <?php echo $this->authuser->show_button('farmasi/Pembelian_cito','C','',1)?>
      <?php echo $this->authuser->show_button('farmasi/Pembelian_cito','D','',5)?>
      <?php echo $this->authuser->show_button('farmasi/Pembelian_cito','EX','',1)?>

    </div>
    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="farmasi/Pembelian_cito" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center"></th>
          <th width="120px">&nbsp;</th>
          <th width="50px">ID</th>
          <!-- <th>Kode</th> -->
          <th>Nama Obat</th>
          <th>Tanggal</th>
          <th>Jumlah</th>
          <th>Harga Beli</th>
          <th>Harga Jual</th>
          <th>Total Beli</th>
          <th>Tempat Pembelian</th>
          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



