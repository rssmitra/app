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
  
    $('#form_Tmp_mst_function').ajaxForm({
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
          $('#page-area-content').load('akunting/Mst_coa?_=' + (new Date()).getTime());
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 

    $('select[name="coa_level_1"]').change(function () {
        if ($(this).val()) {
            $('#acc_ref').val($(this).val());
            $('#level_coa').val(2);
            $.getJSON("<?php echo site_url('Templates/References/getCoaLvl') ?>?lvl=2&ref=" + $(this).val(), '', function (data) {
                $('#div_coa_lvl_2').show('fast');
                $('#coa_level_2 option').remove();
                $('<option value="">-Pilih Level 2-</option>').appendTo($('#coa_level_2'));
                $.each(data.opt_coa, function (i, o) {
                    $('<option value="' + o.acc_no + '">' + o.acc_nama + '</option>').appendTo($('#coa_level_2'));
                });
                // txt form
                
                $('#acc_no_rs').val(data.new_kode_akun);
                $('#kode_akun_lvl_existing').text('Level 2');
                $('#nama_akun_lvl_existing').text('Level 2');
            });
        } else {
            $('#coa_level_2 option').remove()
        }
    });

    $('select[name="coa_level_2"]').change(function () {
        if ($(this).val()) {
            $('#acc_ref').val($(this).val());
            $('#level_coa').val(3);
            $.getJSON("<?php echo site_url('Templates/References/getCoaLvl') ?>?lvl=3&ref=" + $(this).val(), '', function (data) {
                $('#div_coa_lvl_3').show('fast');
                $('#coa_level_3 option').remove();
                $('<option value="">-Pilih Level 3-</option>').appendTo($('#coa_level_3'));
                $.each(data.opt_coa, function (i, o) {
                    $('<option value="' + o.acc_no + '">' + o.acc_nama + '</option>').appendTo($('#coa_level_3'));
                });
                // txt form
                
                $('#acc_no_rs').val(data.new_kode_akun);
                $('#kode_akun_lvl_existing').text('Level 3');
                $('#nama_akun_lvl_existing').text('Level 3');
            });
        } else {
            $('#coa_level_3 option').remove()
        }
    });

    $('select[name="coa_level_3"]').change(function () {
        if ($(this).val()) {
            $('#acc_ref').val($(this).val());
            $('#level_coa').val(4);
            $.getJSON("<?php echo site_url('Templates/References/getCoaLvl') ?>?lvl=4&ref=" + $(this).val(), '', function (data) {
                $('#div_coa_lvl_4').show('fast');
                $('#coa_level_4 option').remove();
                $('<option value="">-Pilih Level 4-</option>').appendTo($('#coa_level_4'));
                $.each(data.opt_coa, function (i, o) {
                    $('<option value="' + o.acc_no + '">' + o.acc_nama + '</option>').appendTo($('#coa_level_4'));
                });
                // txt form
                
                $('#acc_no_rs').val(data.new_kode_akun);
                $('#kode_akun_lvl_existing').text('Level 4');
                $('#nama_akun_lvl_existing').text('Level 4');
            });
        } else {
            $('#coa_level_4 option').remove()
        }
    });

    $('select[name="coa_level_4"]').change(function () {
        if ($(this).val()) {
            $('#acc_ref').val($(this).val());
            $('#level_coa').val(5);
            $.getJSON("<?php echo site_url('Templates/References/getCoaLvl') ?>?lvl=5&ref=" + $(this).val(), '', function (data) {
                $('#div_coa_lvl_5').show('fast');
                $('#coa_level_5 option').remove();
                $('<option value="">-Pilih Level 5-</option>').appendTo($('#coa_level_5'));
                $.each(data.opt_coa, function (i, o) {
                    $('<option value="' + o.acc_no + '">' + o.acc_nama + '</option>').appendTo($('#coa_level_5'));
                });
                // txt form
                
                $('#acc_no_rs').val(data.new_kode_akun);
                $('#kode_akun_lvl_existing').text('Level 5');
                $('#nama_akun_lvl_existing').text('Level 5');
            });
        } else {
            $('#coa_level_5 option').remove()
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
              <form class="form-horizontal" method="post" id="form_Tmp_mst_function" action="<?php echo site_url('akunting/Mst_coa/process')?>" enctype="multipart/form-data" autocomplete="off">
                <br>

                <div class="form-group">
                  <label class="control-label col-md-2">ID</label>
                  <div class="col-md-1">
                    <input name="id" id="id" value="<?php echo isset($value)?$value->id_mt_account:0?>" placeholder="Auto" class="form-control" type="text" readonly>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">COA Level 1</label>
                  <div class="col-md-3">
                    <?php echo $this->master->custom_selection(array('table'=>'mt_account', 'where'=>array('is_active'=>'Y', 'level_coa' => 1), 'id'=>'acc_no', 'name' => 'acc_nama'), isset($parent[1])?$parent[1]['acc_no']:'','coa_level_1','coa_level_1','chosen-slect form-control',($flag=='read')?'readonly':'','');?>
                  </div>
                </div>

                <div class="form-group" id="div_coa_lvl_2" <?php echo isset($parent[2])?'':'style="display: none"'; ?> >
                  <label class="control-label col-md-2">COA Level 2</label>
                  <div class="col-md-3">
                    <?php echo $this->master->custom_selection(array('table'=>'mt_account', 'where'=>array('is_active'=>'Y', 'level_coa' => 2), 'id'=>'acc_no', 'name' => 'acc_nama'), isset($parent[2])?$parent[2]['acc_no']:'','coa_level_2','coa_level_2','chosen-slect form-control',($flag=='read')?'readonly':'','');?>
                  </div>
                </div>

                <div class="form-group" id="div_coa_lvl_3" <?php echo isset($parent[3])?'':'style="display: none"'; ?>>
                  <label class="control-label col-md-2">COA Level 3</label>
                  <div class="col-md-3">
                    <?php echo $this->master->custom_selection(array('table'=>'mt_account', 'where'=>array('is_active'=>'Y', 'level_coa' => 3), 'id'=>'acc_no', 'name' => 'acc_nama'), isset($parent[3])?$parent[3]['acc_no']:'','coa_level_3','coa_level_3','chosen-slect form-control',($flag=='read')?'readonly':'','');?>
                  </div>
                </div>

                <div class="form-group" id="div_coa_lvl_4" <?php echo isset($parent[4])?'':'style="display: none"'; ?>>
                  <label class="control-label col-md-2">COA Level 4</label>
                  <div class="col-md-3">
                    <?php echo $this->master->custom_selection(array('table'=>'mt_account', 'where'=>array('is_active'=>'Y', 'level_coa' => 4), 'id'=>'acc_no', 'name' => 'acc_nama'), isset($parent[4])?$parent[4]['acc_no']:'','coa_level_4','coa_level_4','chosen-slect form-control',($flag=='read')?'readonly':'','');?>
                  </div>
                </div>

                <!-- acc no ref -->
                <input name="acc_ref" id="acc_ref" type="hidden" value="<?php echo isset($value)?$value->acc_ref:''?>">
                <input name="level_coa" id="level_coa" type="hidden" value="<?php echo isset($parent) ? count($parent): 1; ?>">

                <div class="form-group">
                  <label class="control-label col-md-2">Kode Akun <span id="kode_akun_lvl_existing"> Level <?php echo isset($parent) ? count($parent): 1; ?></span></label>
                  <div class="col-md-2">
                    <input name="acc_no_rs" id="acc_no_rs" value="<?php echo isset($value)?$value->acc_no_rs:''?>" placeholder="" class="form-control" type="text" readonly >
                  </div>
                  <label class="control-label col-md-2">Nama Akun <span id="nama_akun_lvl_existing"> Level <?php echo isset($parent) ? count($parent): 1; ?></span></label>
                  <div class="col-md-4">
                    <input name="acc_nama" id="acc_nama" value="<?php echo isset($value)?$value->acc_nama:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Saldo Normal</label>
                  <div class="col-md-2">
                    <div class="radio">
                          <label>
                            <input name="acc_type" type="radio" class="ace" value="D" <?php echo isset($value) ? ($value->acc_type == 'D') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl"> Debet</span>
                          </label>
                          <label>
                            <input name="acc_type" type="radio" class="ace" value="K" <?php echo isset($value) ? ($value->acc_type == 'K') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                            <span class="lbl"> Kredit</span>
                          </label>
                    </div>
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


                <div class="form-actions center">

                  <a onclick="getMenu('akunting/Mst_coa')" href="#" class="btn btn-sm btn-success">
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


