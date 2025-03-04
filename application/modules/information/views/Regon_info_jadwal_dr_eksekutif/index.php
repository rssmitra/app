<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#tabs_detail_jadwal').load('information/regon_info_jadwal_dr_eksekutif/jadwal_dokter');
  })
</script>

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

    </div>  

    <!-- div.dataTables_borderWrap -->

    <div>    

    <div id="tabs_detail_jadwal"></div>

  </div><!-- /.col -->

</div><!-- /.row -->
