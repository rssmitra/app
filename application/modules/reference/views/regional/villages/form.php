<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
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
  
    $('#form_villages').ajaxForm({
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
          $('#page-area-content').load('reference/regional/villages?_=' + (new Date()).getTime());
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 

    $('select[name="provinceId"]').change(function () {
        if ($(this).val()) {
            $.getJSON("<?php echo site_url('Templates/References/getRegencyByProvince') ?>/" + $(this).val(), '', function (data) {
                $('#regencyId option').remove();
                $('<option value="">-Silahkan Pilih-</option>').appendTo($('#regencyId'));
                $.each(data, function (i, o) {
                    $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#regencyId'));
                });

            });
        } else {
            $('#regencyId option').remove()
        }
    });

    $('select[name="regencyId"]').change(function () {
        if ($(this).val()) {
            $.getJSON("<?php echo site_url('Templates/References/getDistrictByRegency') ?>/" + $(this).val(), '', function (data) {
                $('#districtId option').remove();
                $('<option value="">-Silahkan Pilih-</option>').appendTo($('#districtId'));
                $.each(data, function (i, o) {
                    $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#districtId'));
                });

            });
        } else {
            $('#regencyId option').remove()
        }
    });

})

</script>

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
              <form class="form-horizontal" method="post" id="form_villages" action="<?php echo site_url('reference/regional/villages/process')?>" enctype="multipart/form-data">
                <br>

                <div class="form-group">
                  <label class="control-label col-md-2">ID</label>
                  <div class="col-md-1">
                    <input name="id" id="id" value="<?php echo isset($value)?$value->id:0?>" placeholder="Auto" class="form-control" type="text">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Province</label>
                  <div class="col-md-4">
                    <?php echo $this->master->custom_selection($params = array('table' => 'provinces', 'id' => 'id', 'name' => 'name', 'where' => array()), isset($value)?$value->province_id:'' , 'provinceId', 'provinceId', 'form-control', '', '') ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Regency</label>
                  <div class="col-md-4">
                    <?php echo $this->master->custom_selection($params = array('table' => 'regencies', 'id' => 'id', 'name' => 'name', 'where' => array()), isset($value)?$value->regency_id:'' , 'regencyId', 'regencyId', 'form-control', '', '') ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">District</label>
                  <div class="col-md-4">
                    <?php echo $this->master->custom_selection($params = array('table' => 'districts', 'id' => 'id', 'name' => 'name', 'where' => array()), isset($value)?$value->district_id:'' , 'districtId', 'districtId', 'form-control', '', '') ?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Village Name</label>
                  <div class="col-md-4">
                    <input name="name" id="name" value="<?php echo isset($value)?$value->name:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Is active?</label>
                  <div class="col-md-2">
                    <div class="radio">
                          <label>
                            <input name="is_active" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->is_active == 'Y') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl"> Ya</span>
                          </label>
                          <label>
                            <input name="is_active" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->is_active == 'N') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                            <span class="lbl">Tidak</span>
                          </label>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Last update</label>
                  <div class="col-md-8" style="padding-top:8px;font-size:11px">
                      <i class="fa fa-calendar"></i> <?php echo isset($value->updated_date)?$this->tanggal->formatDateTime($value->updated_date):isset($value)?$this->tanggal->formatDateTime($value->created_date):date('d-M-Y H:i:s')?> - 
                      by : <i class="fa fa-user"></i> <?php echo isset($value->updated_by)?$value->updated_by:isset($value->created_by)?$value->created_by:$this->session->userdata('user')->username?>
                  </div>
                </div>


                <div class="form-actions center">

                  <a onclick="getMenu('reference/regional/villages')" href="#" class="btn btn-sm btn-success">
                    <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                    Kembali ke daftar
                  </a>
                  <?php if($flag != 'read'):?>
                  <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                    <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                    Reset
                  </button>
                  <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Submit
                  </button>
                <?php endif; ?>
                </div>
              </form>
            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


