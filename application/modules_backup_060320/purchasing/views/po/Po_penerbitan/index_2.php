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

    <!-- page area content -->
    <div class="tabbable">  

      <ul class="nav nav-tabs" id="myTab">

        <li>
          <a data-toggle="tab" id="tabs_gudang_medis" href="#" data-id="" data-url="purchasing/po/Po_penerbitan/view_data?flag=medis" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_po')">
            <i class="green ace-icon fa fa-history bigger-120"></i>
            GUDANG MEDIS
          </a>
        </li>

        <li>
          <a data-toggle="tab" data-id="" data-url="purchasing/po/Po_penerbitan/view_data?flag=non_medis" id="tabs_gudan_nm" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_po')" >
            <i class="purple ace-icon fa fa-money bigger-120"></i>
            GUDANG NON MEDIS / UMUM
          </a>
        </li>

      </ul>

      <div class="tab-content">

        <div id="tabs_form_po">
          <div class="alert alert-block alert-success">
              <p>
                <strong>
                  <i class="ace-icon fa fa-check"></i>
                  Selamat Datang!
                </strong> 
                Silahkan Klik Tabs di atas untuk menampilkan data!
              </p>
          </div>
        </div>

      </div>

    </div>
    <!-- end page area content -->

  </div><!-- /.col -->
</div><!-- /.row -->