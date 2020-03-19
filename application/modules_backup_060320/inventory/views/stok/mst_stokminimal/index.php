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

   <div class="tabbable">  

              <ul class="nav nav-tabs" id="myTab">

                <li class="active">
                  <a data-toggle="tab" href="#" data-url="inventory/distribusi/mst_stokgudang" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')">
                    <i class="green ace-icon fa fa-history bigger-120"></i>
                    Stok Non Medik
                  </a>
                </li>

                <li>
                 <a data-toggle="tab" href="#" data-url="inventory/distribusi/mst_stokminimal" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')">
                    <i class="green ace-icon fa fa-history bigger-120"></i>
                    Stok Minimum
                  </a>
                </li>
              </ul>
    <hr class="separator">
    <!-- div.table-responsive -->

</div>

    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="inventory/distribusi/mst_stokgudang" class="table table-striped table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center"></th>
          <th width="70px">Kode Barang</th>
          <th>Nama Barang</th>
          <th>Satuan</th>
          <th>Kategori</th>
          <th>Golongan</th>
          <th>Sub Gol</th>
          <th>Stok Sekarang</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



