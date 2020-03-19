<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>

<script type="text/javascript">
  $(document).ready(function(){
  
    $('#tabs_detail_jadwal').load('reference/tabel/poliklinik/listpoliklinik');

  })
   $(document).ready(function(){
  
    $('#tabs_detail_jadwal').load('reference/tabel/igd/listigd');

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

   

    <!-- div.dataTables_borderWrap -->

    <div>    

    <div class="tabbable" >

        <ul class="nav nav-tabs" id="myTab">
          <li class="active">
            <a data-toggle="tab" id="listpoliklinik" href="#" data-id="0" data-url="reference/tabel/poliklinik/list_poliklinik" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_detail_jadwal')">
              <i class="green ace-icon fa fa-history bigger-120"></i>
              Poliklinik
            </a>
          </li>

          <li>
            <a data-toggle="tab" data-id="1" data-url="reference/tabel/igd/list_igd" id="tabs_igd" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_detail_jadwal')" >
              <i class="red ace-icon fa fa-calendar bigger-120"></i>
              IGD
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
