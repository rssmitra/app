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
    <div class="form-group">
      <label class="control-label col-md-3">ID</label>
      <div class="col-md-3">
        <input name="id" id="id" value="<?php echo isset($value)?$value->id:0?>" placeholder="Auto" class="form-control" type="text" readonly>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3">Category</label>
      <div class="col-md-4">
        <select name="category" id="" class="form-control">
          <option <?php echo isset($value->category) ? ($value->category == 1) ? 'selected' : '' : '' ?> value="1">Public Holiday</option>
          <option <?php echo isset($value->category) ? ($value->category == 0) ? 'selected' : '' : '' ?> value="0">Highseason</option>
          <option <?php echo isset($value->category) ? ($value->category == 2) ? 'selected' : '' : '' ?> value="2">Closed</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3">Principal</label>
      <div class="col-md-6">
        <?php echo $this->master->custom_selection_with_db_selected(array('table'=>'m_principals', 'where'=>array('is_active'=>'Y'), 'id'=>'id', 'name' => 'principal_name'),isset($value)?$value->principal_id:'','principal_id','principal_id','chosen-slect form-control',($flag=='read')?'readonly':'','', 'db_mbr');?>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3">Start Date</label>
        <div class="col-md-3">
          <div class="input-group">
            <input class="form-control date-picker" name="date" id="date" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value)?$value->date:''?>"/>
            <span class="input-group-addon">
              <i class="fa fa-calendar bigger-110"></i>
            </span>
          </div>
        </div>
      <label class="control-label col-md-2">End Date</label>
        <div class="col-md-3">
          <div class="input-group">
            <input class="form-control date-picker" name="end_date" id="end_date" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value)?$value->end_date:''?>"/>
            <span class="input-group-addon">
              <i class="fa fa-calendar bigger-110"></i>
            </span>
          </div>
        </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3">Description</label>
      <div class="col-md-8">
        <textarea class="form-control" name="title" id="title" style="height: 100px !important" <?php echo ($flag=='read')?'readonly':''?>><?php echo isset($value)?$value->title:''?></textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Is active?</label>
      <div class="col-md-8">
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
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


