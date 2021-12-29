<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
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

$(document).ready(function(){
  
    $('#form-setup-ttd').ajaxForm({
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
          $('#page-area-content').load('purchasing/persetujuan_pemb/App_setup_ttd?_=' + (new Date()).getTime());
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 

})

</script>
<style type="text/css">
  .dropdown-item{
    height : 100px;
    width: 300px;
  }
</style>
<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
    
      <div class="widget-body">
        <div class="widget-main no-padding">
          <form class="form-horizontal" method="post" id="form-setup-ttd" action="<?php echo site_url('purchasing/persetujuan_pemb/App_setup_ttd/process')?>" enctype="multipart/form-data" >

            <p><b>PENANDATANGAN</b></p>
            <div class="form-group">
              <label class="control-label col-md-2">Kepala <?php echo COMP_FLAG; ?></label>
              <div class="col-md-3">
                <input type="hidden" name="auto_id[ttd_ka_rs]" value="<?php echo $this->master->get_ttd_data('ttd_ka_rs','auto_id');?>">
                <input name="value[ttd_ka_rs]" id="ttd_ka_rs" value="<?php echo $this->master->get_ttd_data('ttd_ka_rs','value'); ?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Nama Pejabat</label>
              <div class="col-md-3">
                <input name="label[ttd_ka_rs]" id="ttd_ka_rs" value="<?php echo $this->master->get_ttd_data('ttd_ka_rs','label'); ?>" class="form-control" type="text">
              </div>
            </div> 
            
            <div class="form-group">
              <label class="control-label col-md-2">Waka Rs Bid Pelayanan</label>
              <div class="col-md-3">
                <input type="hidden" name="auto_id[ttd_waka_rs_bid_pl]" value="<?php echo $this->master->get_ttd_data('ttd_waka_rs_bid_pl','auto_id');?>">
                <input name="value[ttd_waka_rs_bid_pl]" id="ttd_waka_rs_bid_pl" value="<?php echo $this->master->get_ttd_data('ttd_waka_rs_bid_pl','value'); ?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Nama Pejabat</label>
              <div class="col-md-3">
                <input name="label[ttd_waka_rs_bid_pl]" id="ttd_waka_rs_bid_pl" value="<?php echo $this->master->get_ttd_data('ttd_waka_rs_bid_pl','label'); ?>" class="form-control" type="text">
              </div>
            </div>  
            
            <div class="form-group">
              <label class="control-label col-md-2">Waka Rs Bid Adm & Keu</label>
              <div class="col-md-3">
                <input type="hidden" name="auto_id[ttd_waka_rs_bid_adm]" value="<?php echo $this->master->get_ttd_data('ttd_waka_rs_bid_adm','auto_id');?>">
                <input name="value[ttd_waka_rs_bid_adm]" id="ttd_waka_rs_bid_adm" value="<?php echo $this->master->get_ttd_data('ttd_waka_rs_bid_adm','value'); ?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Nama Pejabat</label>
              <div class="col-md-3">
                <input name="label[ttd_waka_rs_bid_adm]" id="ttd_waka_rs_bid_adm" value="<?php echo $this->master->get_ttd_data('ttd_waka_rs_bid_adm','label'); ?>" class="form-control" type="text">
              </div>
            </div>  
            
            <div class="form-group">
              <label class="control-label col-md-2">Kepala Bid PM</label>
              <div class="col-md-3">
                <input type="hidden" name="auto_id[ttd_ka_bid_pm]" value="<?php echo $this->master->get_ttd_data('ttd_ka_bid_pm','auto_id');?>">
                <input name="value[ttd_ka_bid_pm]" id="ttd_ka_bid_pm" value="<?php echo $this->master->get_ttd_data('ttd_ka_bid_pm','value'); ?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Nama Pejabat</label>
              <div class="col-md-3">
                <input name="label[ttd_ka_bid_pm]" id="ttd_ka_bid_pm" value="<?php echo $this->master->get_ttd_data('ttd_ka_bid_pm','label'); ?>" class="form-control" type="text">
              </div>
            </div>  
            
            <div class="form-group">
              <label class="control-label col-md-2">Ketua Tim Pengadaan</label>
              <div class="col-md-3">
                <input type="hidden" name="auto_id[ttd_ka_tim_barjas]" value="<?php echo $this->master->get_ttd_data('ttd_ka_tim_barjas','auto_id');?>">
                <input name="value[ttd_ka_tim_barjas]" id="ttd_ka_tim_barjas" value="<?php echo $this->master->get_ttd_data('ttd_ka_tim_barjas','value'); ?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Nama Pejabat</label>
              <div class="col-md-3">
                <input name="label[ttd_ka_tim_barjas]" id="ttd_ka_tim_barjas" value="<?php echo $this->master->get_ttd_data('ttd_ka_tim_barjas','label'); ?>" class="form-control" type="text">
              </div>
            </div>  
            
            <div class="form-group">
              <label class="control-label col-md-2">Kepala Gudang Medis</label>
              <div class="col-md-3">
                <input type="hidden" name="auto_id[ttd_ka_gdg_m]" value="<?php echo $this->master->get_ttd_data('ttd_ka_gdg_m','auto_id');?>">
                <input name="value[ttd_ka_gdg_m]" id="ttd_ka_gdg_m" value="<?php echo $this->master->get_ttd_data('ttd_ka_gdg_m','value'); ?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Nama Pejabat</label>
              <div class="col-md-3">
                <input name="label[ttd_ka_gdg_m]" id="ttd_ka_gdg_m" value="<?php echo $this->master->get_ttd_data('ttd_ka_gdg_m','label'); ?>" class="form-control" type="text">
              </div>
            </div>  
            
            <div class="form-group">
              <label class="control-label col-md-2">Kepala Gudang Non Medis</label>
              <div class="col-md-3">
                <input type="hidden" name="auto_id[ttd_ka_gdg_nm]" value="<?php echo $this->master->get_ttd_data('ttd_ka_gdg_nm','auto_id');?>">
                <input name="value[ttd_ka_gdg_nm]" id="ttd_ka_gdg_nm" value="<?php echo $this->master->get_ttd_data('ttd_ka_gdg_nm','value'); ?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Nama Pejabat</label>
              <div class="col-md-3">
                <input name="label[ttd_ka_gdg_nm]" id="ttd_ka_gdg_nm" value="<?php echo $this->master->get_ttd_data('ttd_ka_gdg_nm','label'); ?>" class="form-control" type="text">
              </div>
            </div>  
            <br>
            <p><b>VERIFIKATOR PENGADAAN MEDIS</b></p>
            <div class="form-group">
              <label class="control-label col-md-2">Pemeriksa 1</label>
              <div class="col-md-3">
                <input type="hidden" name="auto_id[verifikator_m_1]" value="<?php echo $this->master->get_ttd_data('verifikator_m_1','auto_id');?>">
                <input name="value[verifikator_m_1]" id="verifikator_m_1" value="<?php echo $this->master->get_ttd_data('verifikator_m_1','value'); ?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Nama Pejabat</label>
              <div class="col-md-3">
                <input name="label[verifikator_m_1]" id="verifikator_m_1" value="<?php echo $this->master->get_ttd_data('verifikator_m_1','label'); ?>" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Pemeriksa 2</label>
              <div class="col-md-3">
                <input type="hidden" name="auto_id[verifikator_m_2]" value="<?php echo $this->master->get_ttd_data('verifikator_m_2','auto_id');?>">
                <input name="value[verifikator_m_2]" id="verifikator_m_2" value="<?php echo $this->master->get_ttd_data('verifikator_m_2','value'); ?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Nama Pejabat</label>
              <div class="col-md-3">
                <input name="label[verifikator_m_2]" id="verifikator_m_2" value="<?php echo $this->master->get_ttd_data('verifikator_m_2','label'); ?>" class="form-control" type="text">
              </div>
            </div>
            
            <br>
            <p><b>VERIFIKATOR PENGADAAN NON MEDIS</b></p>
            <div class="form-group">
              <label class="control-label col-md-2">Pemeriksa 1</label>
              <div class="col-md-3">
                <input type="hidden" name="auto_id[verifikator_nm_1]" value="<?php echo $this->master->get_ttd_data('verifikator_nm_1','auto_id');?>">
                <input name="value[verifikator_nm_1]" id="verifikator_nm_1" value="<?php echo $this->master->get_ttd_data('verifikator_nm_1','value'); ?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Nama Pejabat</label>
              <div class="col-md-3">
                <input name="label[verifikator_nm_1]" id="verifikator_nm_1" value="<?php echo $this->master->get_ttd_data('verifikator_nm_1','label'); ?>" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Pemeriksa 2</label>
              <div class="col-md-3">
                <input type="hidden" name="auto_id[verifikator_nm_2]" value="<?php echo $this->master->get_ttd_data('verifikator_nm_2','auto_id');?>">
                <input name="value[verifikator_nm_2]" id="verifikator_nm_2" value="<?php echo $this->master->get_ttd_data('verifikator_nm_2','value'); ?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Nama Pejabat</label>
              <div class="col-md-3">
                <input name="label[verifikator_nm_2]" id="verifikator_nm_2" value="<?php echo $this->master->get_ttd_data('verifikator_nm_2','label'); ?>" class="form-control" type="text">
              </div>
            </div>
            
            <br>
            <div class="form-actions center">

              <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                Reset
              </button>
              <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
              
            </div>

          </form>
        </div>
      </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


