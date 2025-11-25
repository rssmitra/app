<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script type="text/javascript">
  jQuery(function($) {

    $('.date-picker').datepicker({
      autoclose: true,
      todayHighlight: true
    })
    //show datepicker when clicking on the icon
    .next().on(ace.click_event, function(){
      $(this).prev().focus();
    });

  });

  function click_detail(kode_brg){
    getMenu('inventory/stok/Riwayat_pemakaian_bhp/detail/'+kode_brg+'/'+$('#kode_bagian').val()+'');
  }

  function rollback_stok_bhp(id_kartu, kodeBag, kode_barang){
    if(confirm('Are you sure?')){
      $.ajax({
          url: 'inventory/stok/Riwayat_pemakaian_bhp/rollback_stok_bhp',
          type: "post",
          data: {ID:id_kartu, kode_bagian : kodeBag, kode_brg : kode_barang},
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          uploadProgress: function(event, position, total, percentComplete) {
          },
          complete: function(xhr) {     
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);
            if(jsonResponse.status === 200){
              $.achtung({message: jsonResponse.message, timeout:5});
              reload_table();
            }else{
              $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
          }

        });

    }else{
      return false;
    }
    
  }

</script>

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

    <form class="form-horizontal" method="post" id="form_search" action="inventory/stok/Riwayat_pemakaian_bhp/find_data" autocomplete="off">

        <center>
            <h4><?php echo strtoupper($title)?> <br><small style="font-size:12px">Data yang ditampilkan saat ini adalah stok/depo sampai dengan tanggal hari ini <?php echo date('d/M/Y')?> </small></h4>
        </center>
      
        <br>

        <div class="form-group">
          <label class="control-label col-md-2">Pilih Depo/Unit</label>
          <div class="col-md-3">
            <?php 
              echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1, 'pelayanan' => 1, 'group_bag' => 'Detail')), '060101' , 'kode_bagian', 'kode_bagian', 'form-control', '', '') ?>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Tanggal Pemakaian BHP</label>
            <div class="col-md-2">
            <div class="input-group">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
            <label class="control-label col-md-1" style="margin-left: -8px">s/d Tanggal</label>
            <div class="col-md-2">
            <div class="input-group">
                <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
            <div class="col-md-4" style="margin-left: -1.5%">
              <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                Search
              </a>
              <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reset
              </a>
            </div>
        </div>
        
        <div class="clearfix" style="margin-bottom:-5px">
          <?php echo $this->authuser->show_button('inventory/stok/Riwayat_pemakaian_bhp','C','',1)?>
          <?php echo $this->authuser->show_button('inventory/stok/Riwayat_pemakaian_bhp','D','',5)?>
          <div class="pull-right tableTools-container"></div>
        </div>
        <hr class="separator">
        <!-- div.table-responsive -->
        
        <!-- div.dataTables_borderWrap -->
        <div style="margin-top:-27px">
          <table id="dynamic-table" base-url="inventory/stok/Riwayat_pemakaian_bhp" class="table table-striped table-bordered table-hover" style="width: 100%">
            <thead>
              <tr style="background-color: #87b87f">
                <th width="30px">No</th>
                <th width="180px">Tanggal</th>
                <th>Nama Barang</th>
                <th width="80px">Jumlah</th>
                <th width="200px">Keterangan</th>
                <th width="200px">Rollback</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



