
<style type="text/css">
    table{
      width: 100% !important;
      font-size: 12px;
    }
    .table-custom thead {
      background-color: #14506b;
      color: white;
    }

    .table-custom th, td {
      padding: 10px;
      border: 1px solid #c5d0dc;
    }
    .table-custom tbody tr:hover {background-color: #e6e6e6e0;}
</style>
 <div class="page-header">    

      <h1>      

        <?php echo $title?>        

        <small>        

          <i class="ace-icon fa fa-angle-double-right"></i>          

          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>          

        </small>        

      </h1>      

    </div>  

    <div class="clearfix" style="margin-bottom:-5px">
      <?php echo $this->authuser->show_button('reference/tabel/igd','C','',1)?>
      <?php echo $this->authuser->show_button('reference/tabel/igd','D','',5)?>
    </div>
    <hr class="separator">
    <!-- div.table-responsive -->

    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="reference/tabel/igd" class="table-custom">
       <thead>
        <tr>  
          <th class="center" rowspan="2"></th>
          <th rowspan="2" class="center">KJS</th>
          <th rowspan="2" class="center">Kode Tindakan</th>
          <th rowspan="2" class="center">Nama Tarif</th>
          <th rowspan="2" class="center">Nama Bagian</th>
          <th colspan="4" class="center">tarif</th>
          <th rowspan="2" class="center" width="100px">Total</th>
          <tr>
            <th class="center">Bill Dr 1</th>
            <th class="center">Bill Dr 2</th>
            <th class="center">RS</th>
            <th class="center">BHP</th>
          </tr>
          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
</div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>


