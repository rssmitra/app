<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#tabs_detail_jadwal').load('information/regon_info_jadwal_dr/jadwal_dokter');
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

    <div class="tabbable" >

        <ul class="nav nav-tabs" id="myTab">
          <li class="active">
            <a data-toggle="tab" id="tabs_jadwal_dokter" href="#" data-id="0" data-url="information/regon_info_jadwal_dr/jadwal_dokter" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_detail_jadwal')">
              <i class="green ace-icon fa fa-history bigger-120"></i>
              Jadwal Dokter
            </a>
          </li>

          <li>
            <a data-toggle="tab" data-id="0" data-url="information/regon_info_jadwal_dr/cuti_dokter" id="tabs_cuti_dokter" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_detail_jadwal')" >
              <i class="red ace-icon fa fa-calendar bigger-120"></i>
              Cuti Dokter
            </a>
          </li>

          <li>
            <a data-toggle="tab" data-id="0" data-url="information/regon_info_jadwal_dr/lihat_jadwal_dokter" id="tabs_lihat_jadwal_dokter" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_detail_jadwal')" >
              <i class="red ace-icon fa fa-calendar bigger-120"></i>
              Pencarian Jadwal Dokter
            </a>
          </li>
        </ul>

        <div class="tab-content">

          <div id="tabs_detail_jadwal"></div>

        </div>

      </div>

    </div>

  </div><!-- /.col -->

</div><!-- /.row -->
