<script>
  function view_poliklinik()

{  
    $('#result_text_poliklinik').text('Poliklinik ');

    $('#modal_content_view_detail').load('reference/tabel/v_index'); 

   // $("#modalContentViewDetail").modal();
    
}
<script>
  function view_igd()

{  
    $('#result_text_igd').text('IGD ');

    $('#modal_content_view_detail').load('reference/tabel/igd'); 

   // $("#modalContentViewDetail").modal();
    
}

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
    <!-- PAGE CONTENT BEGINS -->
      <div class="invisible">
        <button data-target="#sidebar2" data-toggle="collapse" type="button" class="pull-left navbar-toggle collapsed">
          <span class="sr-only">Toggle sidebar</span>
          <i class="ace-icon fa fa-dashboard white bigger-125"></i>
        </button>

        <div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse ace-save-state">
          <div class="center">
            <ul class="nav nav-list">
              <li class="active">
                <a href="#" onclick="view_poliklinik(0)"><span class="menu-text"> Poliklinik </span></a><b class="arrow"></b>
              </li>
              <li class="hover">
                <a href="#"  onclick="view_igd(0)"><span class="menu-text"> IGD </span></a><b class="arrow"></b>
              </li>
              <li class="hover">
                <a href="#" id="btn_gelang_pasien"><span class="menu-text"> Ruangan </span></a><b class="arrow"></b>
              </li>
              <li class="hover">
                <a href="#"  id="btn_card_member_temp"><span class="menu-text"> Tindakan RI </span></a><b class="arrow"></b>
              </li>
              <li class="hover">
                <a href="#" id="btn_identitas_berobat_pasien"><span class="menu-text"> Kamar bayi </span></a><b class="arrow"></b>
              </li>
              <li class="hover">
                <a href="#" onclick="showModalEditPasien()"><span class="menu-text"> Tindakan ICU </span></a><b class="arrow"></b>
              </li>
              <li class="hover">
                <a data-toggle="tab" id="tabs_riwayat_kunjungan_id" href="#" data-id="0" data-url="registration/reg_pasien/riwayat_kunjungan" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_detail_pasien')"><span class="menu-text"> VK </span></a><b class="arrow"></b>
              </li>
              <li class="hover">
                <a data-toggle="tab" data-id="1" data-url="registration/reg_pasien/riwayat_transaksi" id="tabs_riwayat_transaksi_id" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_detail_pasien')"><span class="menu-text"> Penunjang </span></a><b class="arrow"></b>
              </li>
              <li class="hover">
                <a data-toggle="tab" data-id="0" data-url="registration/reg_pasien/riwayat_perjanjian" id="tabs_riwayat_perjanjian_id" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_detail_pasien')"><span class="menu-text"> Kartu </span></a><b class="arrow"></b>
              </li>
              <li class="hover">
                <a href="#" onclick="showModalEditPasien()"><span class="menu-text"> Jenis Tindakan </span></a><b class="arrow"></b>
              </li>
               <li class="hover">
                <a href="#" onclick="showModalEditPasien()"><span class="menu-text"> Profit margin </span></a><b class="arrow"></b>
              </li>
               <li class="hover">
                <a href="#" onclick="showModalEditPasien()"><span class="menu-text"> Paket MCU </span></a><b class="arrow"></b>
              </li>
               <li class="hover">
                <a href="#" onclick="showModalEditPasien()"><span class="menu-text"> ODC BEDAH </span></a><b class="arrow"></b>
              </li>
               <li class="hover">
                <a href="#" onclick="showModalEditPasien()"><span class="menu-text"> ODC VK </span></a><b class="arrow"></b>
              </li>
               <li class="hover">
                <a href="#" onclick="showModalEditPasien()"><span class="menu-text"> Paket Lab </span></a><b class="arrow"></b>
              </li>
               <li class="hover">
                <a href="#" onclick="showModalEditPasien()"><span class="menu-text"> Bedah </span></a><b class="arrow"></b>
              </li>
              <li class="hover">
                <a href="#" onclick="showModalEditPasien()"><span class="menu-text"> Paket Bedah </span></a><b class="arrow"></b>
              </li>
            </ul><!-- /.nav-list -->
          </div>
        </div>
      </div>
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
      <?php echo $this->authuser->show_button('reference/tabel/poliklinik','C','',1)?>
      <?php echo $this->authuser->show_button('reference/tabel/poliklinik','D','',5)?>
    </div>
    <hr class="separator">
    <!-- div.table-responsive -->

    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="reference/tabel/poliklinik" class="table table-striped table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center" rowspan="2"></th>
          <th rowspan="2">KJS</th>
          <th rowspan="2">Kode Tindakan</th>
          <th rowspan="2">Nama Tarif</th>
          <th rowspan="2">Nama Bagian</th>
          <th colspan="4">tarif</th>
          <tr>
            <th>Bill Dr 1</th>
            <th>Bill Dr 2</th>
            <th>RS</th>
            <th>BHP</th>
          </tr>
          <th rowspan="2" width="100px">Total</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



