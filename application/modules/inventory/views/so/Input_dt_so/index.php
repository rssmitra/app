<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
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

    $('#form_input_dt_so_header').ajaxForm({
      beforeSend: function() {
        achtungShowLoader();
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {
        var data = xhr.responseText;
        var jsonResponse = JSON.parse(data);
        if (jsonResponse.status === 200) {
          $.achtung({message: jsonResponse.message, timeout: 5});
          $('#page-area-content').load(jsonResponse.redirect_page);
        } else {
          $.achtung({message: jsonResponse.message, timeout: 5, className: 'achtungFail'});
        }
        achtungHideLoader();
      }
    });

    $('#InputKeyNamaKaryawan').typeahead({
      source: function (query, result) {
        $.ajax({
          url: "templates/references/getNamaKaryawan",
          data: { keyword: query },
          dataType: "json",
          type: "POST",
          success: function (response) {
            result($.map(response, function (item) { return item; }));
          }
        });
      },
      afterSelect: function (item) {
        var val_item = item.split(':')[0];
        $('#kode_petugas').val(val_item);
      }
    });

});
</script>

<style>
  .so-setup-card .panel-heading { padding: 12px 18px; }
  .so-setup-card .panel-body    { padding: 22px 28px; }
  .so-field-icon { color: #31b0d5; margin-right: 5px; width: 14px; text-align: center; }
  .so-setup-card .form-group { margin-bottom: 14px; }
  .so-setup-card .control-label { font-weight: 600; font-size: 13px; }
</style>

<div class="row">
  <div class="col-xs-12">

    <div class="page-header" style="margin-bottom: 16px">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs) ? $breadcrumbs : '' ?>
        </small>
      </h1>
    </div>

    <div class="row">
      <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 so-setup-card">

        <div class="panel panel-info" style="border-radius: 4px; box-shadow: 0 1px 4px rgba(0,0,0,0.08)">

          <div class="panel-heading" style="border-radius: 4px 4px 0 0">
            <h4 class="panel-title" style="font-size: 15px; font-weight: 600">
              <i class="fa fa-pencil-square-o" style="margin-right: 8px"></i>
              Setup Sesi Input Stok Opname
            </h4>
          </div>

          <div class="panel-body">

            <div class="alert alert-info" style="font-size: 12px; padding: 9px 14px; margin-bottom: 20px; border-radius: 3px">
              <i class="fa fa-info-circle"></i>
              Lengkapi informasi berikut untuk memulai sesi input data stok opname.
              Pastikan <strong>Agenda SO</strong>, <strong>Bagian/Unit</strong>, dan <strong>Petugas</strong> dipilih dengan benar.
            </div>

            <form class="form-horizontal" method="post" id="form_input_dt_so_header"
                  action="<?php echo site_url('inventory/so/Input_dt_so/process')?>"
                  enctype="multipart/form-data" autocomplete="off">

              <!-- Agenda SO -->
              <div class="form-group">
                <label class="control-label col-md-4">
                  <i class="fa fa-calendar-check-o so-field-icon"></i>
                  Agenda SO <span class="text-danger">*</span>
                </label>
                <div class="col-md-8">
                  <?php echo $this->master->custom_selection(
                    $params = array(
                      'table' => 'tc_stok_opname_agenda',
                      'id'    => 'agenda_so_id',
                      'name'  => 'agenda_so_name',
                      'where' => array('is_active' => 'Y')
                    ), '#', 'agenda_so_id', 'agenda_so_id', 'form-control input-sm', '', ''
                  ) ?>
                </div>
              </div>

              <!-- Tanggal & Waktu -->
              <div class="form-group">
                <label class="control-label col-md-4">
                  <i class="fa fa-calendar so-field-icon"></i>
                  Tanggal Input <span class="text-danger">*</span>
                </label>
                <div class="col-md-4">
                  <div class="input-group input-group-sm">
                    <input class="form-control date-picker" name="tanggal_input" id="tanggal_input"
                           type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>
                <label class="control-label col-md-2" style="padding-left:5px">
                  <i class="fa fa-clock-o so-field-icon"></i>
                  Jam <span class="text-danger">*</span>
                </label>
                <div class="col-md-2">
                  <input name="waktu_input" id="waktu_input" value="<?php echo date('H:i')?>"
                         placeholder="09:00" class="form-control input-sm" type="text">
                </div>
              </div>

              <!-- Bagian Unit -->
              <div class="form-group">
                <label class="control-label col-md-4">
                  <i class="fa fa-hospital-o so-field-icon"></i>
                  Bagian / Unit <span class="text-danger">*</span>
                </label>
                <div class="col-md-8">
                  <?php echo $this->master->custom_selection(
                    $params = array(
                      'table' => 'mt_bagian',
                      'id'    => 'kode_bagian',
                      'name'  => 'nama_bagian',
                      'where' => array('depo_group !=' => NULL)
                    ), '', 'bagian', 'bagian', 'form-control input-sm', '', ''
                  ) ?>
                </div>
              </div>

              <!-- Petugas -->
              <div class="form-group">
                <label class="control-label col-md-4">
                  <i class="fa fa-user so-field-icon"></i>
                  Petugas Input <span class="text-danger">*</span>
                </label>
                <div class="col-md-8">
                  <input id="InputKeyNamaKaryawan" class="form-control input-sm"
                         name="petugas_input" type="text"
                         placeholder="Ketik nama karyawan (minimal 3 karakter)..." />
                  <input type="hidden" name="kode_petugas" value="" id="kode_petugas">
                </div>
              </div>

              <hr style="margin: 18px 0 14px">

              <!-- Submit -->
              <div class="form-group" style="margin-bottom: 0">
                <div class="col-md-8 col-md-offset-4">
                  <button type="submit" id="btnSave" name="submit"
                          class="btn btn-info btn-sm" style="padding: 6px 18px">
                    <i class="fa fa-sign-in bigger-110"></i>&nbsp;
                    Mulai Sesi Input SO
                  </button>
                </div>
              </div>

            </form>

          </div><!-- /.panel-body -->
        </div><!-- /.panel -->

      </div><!-- /.col -->
    </div><!-- /.row center -->

  </div><!-- /.col-xs-12 -->
</div><!-- /.row -->
