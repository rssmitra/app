<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<style>
  .page-header-idx { border-bottom: 3px solid #2c6fad; padding-bottom: 8px; margin-bottom: 14px; }
  .page-header-idx h1 { font-size: 20px; color: #1a4f8a; font-weight: 700; margin: 0; }
  .setup-card { border: 1px solid #d0dce8; border-radius: 5px; background: #fff; box-shadow: 0 1px 4px rgba(44,111,173,.07); margin-bottom: 14px; overflow: hidden; }
  .setup-card-hdr { background: #2c6fad; color: #fff; padding: 9px 16px; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; }
  .setup-card-body { padding: 14px 20px 10px; }
  .setup-card-hdr2 { background: #1a4f8a; color: #fff; padding: 9px 16px; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; }
  .form-actions-bar { display: flex; gap: 8px; padding: 10px 20px; background: #f0f5fb; border-top: 1px solid #d8e6f3; }
</style>

<script>
jQuery(function($) {
  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
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

<div class="page-header-idx">
  <h1>
    <?php echo $title?>
    <small style="font-size:13px;color:#888;font-weight:400">
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form-setup-ttd" action="<?php echo site_url('purchasing/persetujuan_pemb/App_setup_ttd/process')?>" enctype="multipart/form-data">

      <div class="setup-card">
        <div class="setup-card-hdr"><i class="fa fa-pencil-square-o"></i> Penandatangan</div>
        <div class="setup-card-body">

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Direktur Operasional PT. SML</label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[ttd_dir_opr_sml]" value="<?php echo $this->master->get_ttd_data('ttd_dir_opr_sml','auto_id');?>">
              <input name="value[ttd_dir_opr_sml]" value="<?php echo $this->master->get_ttd_data('ttd_dir_opr_sml','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[ttd_dir_opr_sml]" value="<?php echo $this->master->get_ttd_data('ttd_dir_opr_sml','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('ttd_dir_opr_sml','reff_id'), 'reff_id[ttd_dir_opr_sml]', 'reff_id_ttd_dir_opr_sml', 'form-control input-sm', '', '') ?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Direktur Keuangan PT. SML</label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[ttd_dir_keu_rssm]" value="<?php echo $this->master->get_ttd_data('ttd_dir_keu_rssm','auto_id');?>">
              <input name="value[ttd_dir_keu_rssm]" value="<?php echo $this->master->get_ttd_data('ttd_dir_keu_rssm','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[ttd_dir_keu_rssm]" value="<?php echo $this->master->get_ttd_data('ttd_dir_keu_rssm','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('ttd_dir_keu_rssm','reff_id'), 'reff_id[ttd_dir_keu_rssm]', 'reff_id_ttd_dir_keu_rssm', 'form-control input-sm', '', '') ?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Kepala <?php echo COMP_FLAG; ?></label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[ttd_ka_rs]" value="<?php echo $this->master->get_ttd_data('ttd_ka_rs','auto_id');?>">
              <input name="value[ttd_ka_rs]" value="<?php echo $this->master->get_ttd_data('ttd_ka_rs','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[ttd_ka_rs]" value="<?php echo $this->master->get_ttd_data('ttd_ka_rs','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('ttd_ka_rs','reff_id'), 'reff_id[ttd_ka_rs]', 'reff_id_ttd_ka_rs', 'form-control input-sm', '', '') ?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Waka Rs Bid Pelayanan</label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[ttd_waka_rs_bid_pl]" value="<?php echo $this->master->get_ttd_data('ttd_waka_rs_bid_pl','auto_id');?>">
              <input name="value[ttd_waka_rs_bid_pl]" value="<?php echo $this->master->get_ttd_data('ttd_waka_rs_bid_pl','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[ttd_waka_rs_bid_pl]" value="<?php echo $this->master->get_ttd_data('ttd_waka_rs_bid_pl','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('ttd_waka_rs_bid_pl','reff_id'), 'reff_id[ttd_waka_rs_bid_pl]', 'reff_id_ttd_waka_rs_bid_pl', 'form-control input-sm', '', '') ?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Waka Rs Bid Adm &amp; Keu</label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[ttd_waka_rs_bid_adm]" value="<?php echo $this->master->get_ttd_data('ttd_waka_rs_bid_adm','auto_id');?>">
              <input name="value[ttd_waka_rs_bid_adm]" value="<?php echo $this->master->get_ttd_data('ttd_waka_rs_bid_adm','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[ttd_waka_rs_bid_adm]" value="<?php echo $this->master->get_ttd_data('ttd_waka_rs_bid_adm','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('ttd_waka_rs_bid_adm','reff_id'), 'reff_id[ttd_waka_rs_bid_adm]', 'reff_id_ttd_waka_rs_bid_adm', 'form-control input-sm', '', '') ?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Kepala Bid PM</label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[ttd_ka_bid_pm]" value="<?php echo $this->master->get_ttd_data('ttd_ka_bid_pm','auto_id');?>">
              <input name="value[ttd_ka_bid_pm]" value="<?php echo $this->master->get_ttd_data('ttd_ka_bid_pm','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[ttd_ka_bid_pm]" value="<?php echo $this->master->get_ttd_data('ttd_ka_bid_pm','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('ttd_ka_bid_pm','reff_id'), 'reff_id[ttd_ka_bid_pm]', 'reff_id_ttd_ka_bid_pm', 'form-control input-sm', '', '') ?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Ketua Tim Pengadaan</label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[ttd_ka_tim_barjas]" value="<?php echo $this->master->get_ttd_data('ttd_ka_tim_barjas','auto_id');?>">
              <input name="value[ttd_ka_tim_barjas]" value="<?php echo $this->master->get_ttd_data('ttd_ka_tim_barjas','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[ttd_ka_tim_barjas]" value="<?php echo $this->master->get_ttd_data('ttd_ka_tim_barjas','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('ttd_ka_tim_barjas','reff_id'), 'reff_id[ttd_ka_tim_barjas]', 'reff_id_ttd_ka_tim_barjas', 'form-control input-sm', '', '') ?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Kepala Gudang Medis</label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[ttd_ka_gdg_m]" value="<?php echo $this->master->get_ttd_data('ttd_ka_gdg_m','auto_id');?>">
              <input name="value[ttd_ka_gdg_m]" value="<?php echo $this->master->get_ttd_data('ttd_ka_gdg_m','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[ttd_ka_gdg_m]" value="<?php echo $this->master->get_ttd_data('ttd_ka_gdg_m','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('ttd_ka_gdg_m','reff_id'), 'reff_id[ttd_ka_gdg_m]', 'reff_id_ttd_ka_gdg_m', 'form-control input-sm', '', '') ?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Kepala Gudang Non Medis</label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[ttd_ka_gdg_nm]" value="<?php echo $this->master->get_ttd_data('ttd_ka_gdg_nm','auto_id');?>">
              <input name="value[ttd_ka_gdg_nm]" value="<?php echo $this->master->get_ttd_data('ttd_ka_gdg_nm','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[ttd_ka_gdg_nm]" value="<?php echo $this->master->get_ttd_data('ttd_ka_gdg_nm','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('ttd_ka_gdg_nm','reff_id'), 'reff_id[ttd_ka_gdg_nm]', 'reff_id_ttd_ka_gdg_nm', 'form-control input-sm', '', '') ?>
            </div>
          </div>

        </div>
      </div>

      <div class="setup-card">
        <div class="setup-card-hdr2"><i class="fa fa-check-circle-o"></i> Verifikator Pengadaan Medis</div>
        <div class="setup-card-body">

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Pemeriksa 1</label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[verifikator_m_1]" value="<?php echo $this->master->get_ttd_data('verifikator_m_1','auto_id');?>">
              <input name="value[verifikator_m_1]" value="<?php echo $this->master->get_ttd_data('verifikator_m_1','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[verifikator_m_1]" value="<?php echo $this->master->get_ttd_data('verifikator_m_1','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('verifikator_m_1','reff_id'), 'reff_id[verifikator_m_1]', 'reff_id_verifikator_m_1', 'form-control input-sm', '', '') ?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Pemeriksa 2</label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[verifikator_m_2]" value="<?php echo $this->master->get_ttd_data('verifikator_m_2','auto_id');?>">
              <input name="value[verifikator_m_2]" value="<?php echo $this->master->get_ttd_data('verifikator_m_2','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[verifikator_m_2]" value="<?php echo $this->master->get_ttd_data('verifikator_m_2','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('verifikator_m_2','reff_id'), 'reff_id[verifikator_m_2]', 'reff_id_verifikator_m_2', 'form-control input-sm', '', '') ?>
            </div>
          </div>

        </div>
      </div>

      <div class="setup-card">
        <div class="setup-card-hdr2"><i class="fa fa-check-circle-o"></i> Verifikator Pengadaan Non Medis</div>
        <div class="setup-card-body">

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Pemeriksa 1</label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[verifikator_nm_1]" value="<?php echo $this->master->get_ttd_data('verifikator_nm_1','auto_id');?>">
              <input name="value[verifikator_nm_1]" value="<?php echo $this->master->get_ttd_data('verifikator_nm_1','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[verifikator_nm_1]" value="<?php echo $this->master->get_ttd_data('verifikator_nm_1','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('verifikator_nm_1','reff_id'), 'reff_id[verifikator_nm_1]', 'reff_id_verifikator_nm_1', 'form-control input-sm', '', '') ?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2" style="font-size:12px">Pemeriksa 2</label>
            <div class="col-md-3">
              <input type="hidden" name="auto_id[verifikator_nm_2]" value="<?php echo $this->master->get_ttd_data('verifikator_nm_2','auto_id');?>">
              <input name="value[verifikator_nm_2]" value="<?php echo $this->master->get_ttd_data('verifikator_nm_2','value'); ?>" class="form-control input-sm" type="text">
            </div>
            <label class="control-label col-md-2" style="font-size:12px">Nama Pejabat</label>
            <div class="col-md-2">
              <input name="label[verifikator_nm_2]" value="<?php echo $this->master->get_ttd_data('verifikator_nm_2','label'); ?>" class="form-control input-sm" type="text">
            </div>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection(array('table'=>'tmp_user','id'=>'user_id','name'=>'fullname','where'=>array()), $this->master->get_ttd_data('verifikator_nm_2','reff_id'), 'reff_id[verifikator_nm_2]', 'reff_id_verifikator_nm_2', 'form-control input-sm', '', '') ?>
            </div>
          </div>

        </div>
        <div class="form-actions-bar">
          <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
            <i class="fa fa-times"></i> Reset
          </button>
          <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-primary">
            <i class="fa fa-check-square-o"></i> Simpan
          </button>
        </div>
      </div>

    </form>
  </div>
</div>
